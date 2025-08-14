<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class GisCircleAssignController extends CI_Controller
{
    use CommonTrait;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('UserModel');
        // $this->load->helper('security');
    }

    public function index()
    {
        $spmu_role_code = $this->UserModel::$SPMU_CODE;
        if(!in_array($this->session->userdata('usertype'), [$spmu_role_code])){
            show_404();
        }
        $this->dbswitch('default');
        $loc_db = $this->db;
        $data['gis_assistants'] = $loc_db->where('user_role', $this->UserModel::$SURVEY_GIS_ASSISTANT_CODE)->where('user_status', 'E')->get('dataentryusers')->result_array();
        $data['districts'] = $loc_db->get_where('location', array('subdiv_code' => '00', 'cir_code' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->result_array();
        $gis_circles = $loc_db->order_by('id', 'ASC')->get('gisassistant_circles')->result_array();
        if(count($gis_circles)){
            foreach($gis_circles as $key => $gis_circle){
                $this->dbswitch($gis_circle['dist_code']);
                $gis_circles[$key]['district_name'] = $this->utilityclass->getDistrictName($gis_circle['dist_code']);
                $gis_circles[$key]['subdivision_name'] = $this->utilityclass->getSubDivName($gis_circle['dist_code'], $gis_circle['subdiv_code']);
                $gis_circles[$key]['circle_name'] = $this->utilityclass->getCircleName($gis_circle['dist_code'], $gis_circle['subdiv_code'], $gis_circle['cir_code']);
                $gis_circles[$key]['user_name'] = $loc_db->select('name')->where('username', $gis_circle['gisassistant_code'])->get('dataentryusers')->row()->name;
            }
        }

        $data['gis_circles'] = $gis_circles;
        $data['_view'] = 'circle_assign_to_gis_assistant/index';
        $this->load->view('layout/layout', $data);
    }

    public function subdivisiondetails()
    {
        // $this->dataswitch();
        $data = [];
        $dist_code = $this->input->post('id');
        $this->dbswitch($dist_code);
        $this->session->set_userdata('dist_code', $dist_code);
        // $subdiv_code = $this->session->userdata('subdiv_code');
        $formdata = $this->Chithamodel->subdivisiondetails($dist_code);
        foreach ($formdata as $value) {
            $data['subdiv_code'][] = $value;
        }
        echo json_encode($formdata);
    }

    public function circledetails()
    {
        // $this->dataswitch();
        $dist_code = $this->input->post('dis');
        $this->dbswitch($dist_code);
        $subdiv = $this->input->post('subdiv');
        // $cir_code = $this->session->userdata('cir_code');
        $this->session->set_userdata('subdiv_code', $subdiv);
        $formdata = $this->Chithamodel->circledetails($dist_code, $subdiv); 
        $data = [];
        $this->dbswitch('default');
        foreach ($formdata as $village) {
            // $data['test'][] = $value;
            $cir_code = $village['cir_code'];
            $data_single = [];
            $data_single['loc_name'] = $village['loc_name'];
            $data_single['cir_code'] = $cir_code;
            $data[] = $data_single;
        }
        echo json_encode($data);
    }

    public function saveGisCircle()
    {
        $spmu_role_code = $this->UserModel::$SPMU_CODE;
        $gisassistant_role_code = $this->UserModel::$SURVEY_GIS_ASSISTANT_CODE;
        if(!in_array($this->session->userdata('usertype'), [$spmu_role_code])){
            show_404();
        }
        $this->dbswitch('default');
        $loc_db = $this->db;
        $dist_code = $this->input->post('dist_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $circle = $this->input->post('cir_code');
        $gis_assistant_code = $this->input->post('gis_assistant');

        $error_msg = [];
        $validation = [
                        [
                            'field' => 'dist_code',
                            'label' => 'District',
                            'rules' => 'required'
                        ],
                        [
                            'field' => 'subdiv_code',
                            'label' => 'Sub-division',
                            'rules' => 'required'
                        ],
                        [
                            'field' => 'cir_code',
                            'label' => 'Circle',
                            'rules' => 'required'
                        ],
                        [
                            'field' => 'gis_assistant',
                            'label' => 'GIS Assistant',
                            'rules' => 'required'
                        ],
                    ];
        $this->form_validation->set_rules($validation);
        if ($this->form_validation->run() == FALSE)
        {               
            $this->form_validation->set_error_delimiters('', '');
            foreach($validation as $rule){
                if (form_error($rule['field'])) {
                    // array_push($error_msg, [$rule['field'] => form_error($rule['field'])]);
                    $field_name = str_replace("[]", "", $rule['field']);
                    $error_msg[$field_name] = form_error($rule['field']);
                }
            }         

            $msgs = [];
            foreach($error_msg as $err_msg){
                array_push($msgs, $err_msg);
            }
            return response_json(['success' => false, 'message' => implode(', ', $error_msg)]);
        }
        
        $gis_assistant = $loc_db->query("SELECT * FROM dataentryusers where user_role=? and username=? and user_status='E'", array($gisassistant_role_code, $gis_assistant_code))->row();
        if(!$gis_assistant){
            return response_json(['success' => false, 'message' => 'No such GIS Assistant found']);
        }

        $gis_has_already_circle = $loc_db->where('gisassistant_code', $gis_assistant_code)->get('gisassistant_circles')->row();
        if($gis_has_already_circle){
            if($gis_has_already_circle->dist_code == $dist_code && $gis_has_already_circle->subdiv_code == $subdiv_code && $gis_has_already_circle->cir_code == $circle){
                return response_json(['success' => false, 'message' => 'Selected circle has already assigned to the selected GIS Assistant']);
            }

            return response_json(['success' => false, 'message' => 'GIS Assistant has already a circle']);
        }

        $is_circle_already_assigned_query = "SELECT deu.* from gisassistant_circles gc right join dataentryusers deu on deu.username=gc.gisassistant_code where gc.dist_code=? and gc.subdiv_code=? and gc.cir_code=?";
        $is_circle_already_assigned = $loc_db->query($is_circle_already_assigned_query, array($dist_code, $subdiv_code, $circle))->row();

        if($is_circle_already_assigned){
            return response_json(['success' => false, 'message' => 'Circle already assigned to ' . $is_circle_already_assigned->name]);
        }
        
        $loc_db->trans_begin();

        try{
            $loc_db->insert('gisassistant_circles', [
                'dist_code' => $dist_code,
                'subdiv_code' => $subdiv_code,
                'cir_code' => $circle,
                'gisassistant_code' => $gis_assistant_code,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by_usercode' => $this->session->userdata('usercode'),
                'created_by_role' => $this->session->userdata('usertype')
            ]);

            $batch_id = uniqid();
            $inserted_id = $loc_db->insert_id();
            if($loc_db->affected_rows() != 1){
                log_message('error', '#ERRGISCIRASSGN0001: ' . $loc_db->last_query());
                throw new Exception('#ERRGISCIRASSGN0001 => Something went wrong. Please try again later.');
            }
            $this->createGisAssistantCircleLog($loc_db, $batch_id, $inserted_id);
            if($loc_db->affected_rows() != 1){
                log_message('error', '#ERRGISCIRASSGN0002: ' . $loc_db->last_query());
                throw new Exception('#ERRGISCIRASSGN0002 => Something went wrong. Please try again later.');
            }

            if($loc_db->trans_status() == false){
                log_message('error', '#ERRGISCIRASSGN0003: ' . $loc_db->last_query());
                throw new Exception('#ERRGISCIRASSGN0003 => Something went wrong. Please try again later.');
            }
        }catch(Exception $e){
            $loc_db->trans_rollback();
            return response_json(['success' => false, 'message' => $e->getMessage()]);
        }

        $loc_db->trans_commit();
        
        return response_json(['success' => true, 'message' => 'Circle has been assigned successfully']);
    }

    public function destroy($gis_circle_id){
        $spmu_role_code = $this->UserModel::$SPMU_CODE;
        if(!in_array($this->session->userdata('usertype'), [$spmu_role_code])){
            return response_json(['success' => false, 'message' => 'Permission denied']);
        }
        $this->dbswitch('default');
        $loc_db = $this->db;
        $gis_circle = $loc_db->where('id', $gis_circle_id)->get('gisassistant_circles')->row();
        if(!$gis_circle){
            return response_json(['success' => false, 'message' => 'No such circle found']);
        }

        // Validate...whether it is deletable or not

        $loc_db->trans_begin();
        try{
            $batch_id = uniqid();
            $this->createGisAssistantCircleLog($loc_db, $batch_id, $gis_circle_id, true);
            $loc_db->where('id', $gis_circle_id)->delete('gisassistant_circles');
            if($loc_db->affected_rows() != 1){
                log_message('error', '#ERRGISCIRDEL001: ' . $loc_db->last_query());
                throw new Exception('#ERRGISCIRDEL001: Something went wrong. Pleasen try again later.');
            }
        }catch(Exception $e){
            $loc_db->trans_rollback();
            return response_json(['success' => false, 'message' => $e->getMessage()]);
        }
        $loc_db->trans_commit();

        return response_json(['success' => true, 'message' => 'Circle removed successfully from GIS Assistant']);
    }

    private function createGisAssistantCircleLog($connection, $batch_id, $gis_circle_id, $is_delete_log = false){
        $gis_circle = $connection->where('id', $gis_circle_id)->get('gisassistant_circles')->row();
        $data = [
                    'gisassistant_circle_id' => $gis_circle_id,
                    'batch_id' => $batch_id,
                    'dist_code' => $gis_circle->dist_code,
                    'subdiv_code' => $gis_circle->subdiv_code,
                    'cir_code' => $gis_circle->cir_code,
                    'gisassistant_code' => $gis_circle->gisassistant_code,
                    'uuid' => $gis_circle->uuid,
                    'created_by_usercode' => $gis_circle->created_by_usercode,
                    'created_by_role' => $gis_circle->created_by_role,
                    'deleted_by' => $is_delete_log ? $this->session->userdata('usercode') : null
                ];

        $connection->insert('gisassistant_circle_logs', $data);
    }
}
