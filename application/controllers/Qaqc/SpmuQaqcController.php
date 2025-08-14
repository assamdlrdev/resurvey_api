<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class SpmuQaqcController extends CI_Controller
{
    use CommonTrait;
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');

        $this->load->model('UserModel');
        $this->load->model('SurveyorVillageFinalReportModel');
        // $this->load->helper('security');
    }

    public function index() {
        $auth_code = $this->session->userdata('usercode');
        $supervisorCode = $this->UserModel::$SUPERVISOR_CODE;
        $spmuCode = $this->UserModel::$SPMU_CODE;
        $userType = $this->session->userdata('usertype');
        if (!in_array($userType, [$spmuCode])) {
            return redirect('/Login/logout');
        }

        $search_dist_code = $this->input->post('dist_code');
        $search_subdiv_code = $this->input->post('subdiv_code');
        $search_cir_code = $this->input->post('cir_code');
        $search_mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $search_lot_no = $this->input->post('lot_no');
        $search_vill_townprt_code = $this->input->post('vill_townprt_code');
        $search_user = $this->input->post('user');

        $this->dbswitch('default');
        $auth_role = $this->session->userdata('usertype');
        $surveyor_role = $this->UserModel::$SURVEYOR_CODE;

        // $surveyor_villages = $this->db->where('surveyor_code', $surveyor_code)->get('surveyor_villages')->result_array();
        $surveyor_villages = $this->db->where('final_report_uploaded', 1)->get('surveyor_villages')->result_array();

        if(count($surveyor_villages)){
            foreach($surveyor_villages as $key => $surveyor_village){
                $surveyor_villages[$key]['dist_name'] = $this->utilityclass->getDistrictName($surveyor_village['dist_code']);
                $surveyor_villages[$key]['subdiv_name'] = $this->utilityclass->getSubDivName($surveyor_village['dist_code'], $surveyor_village['subdiv_code']);
                $surveyor_villages[$key]['circle_name'] = $this->utilityclass->getCircleName($surveyor_village['dist_code'], $surveyor_village['subdiv_code'], $surveyor_village['cir_code']);
                $surveyor_villages[$key]['mouza_name'] = $this->utilityclass->getMouzaName($surveyor_village['dist_code'], $surveyor_village['subdiv_code'], $surveyor_village['cir_code'], $surveyor_village['mouza_pargona_code']);
                $surveyor_villages[$key]['lot_name'] = $this->utilityclass->getLotName($surveyor_village['dist_code'], $surveyor_village['subdiv_code'], $surveyor_village['cir_code'], $surveyor_village['mouza_pargona_code'], $surveyor_village['lot_no']);
                $surveyor_villages[$key]['village_name'] = $this->utilityclass->getVillageName($surveyor_village['dist_code'], $surveyor_village['subdiv_code'], $surveyor_village['cir_code'], $surveyor_village['mouza_pargona_code'], $surveyor_village['lot_no'], $surveyor_village['vill_townprt_code']);
            }
        }

        $data['surveyor_villages'] = $surveyor_villages;
        $data['_view'] = 'qa_qc/village_list';
        $this->load->view('layout/layout', $data);

    }

    public function revert($surveyor_village_id){
        $auth_code = $this->session->userdata('usercode');
        $supervisorCode = $this->UserModel::$SUPERVISOR_CODE;
        $this->dbswitch('default');
        if(!$surveyor_village = $this->db->where('id', $surveyor_village_id)->where('final_report_uploaded', 1)->get('surveyor_villages')->row()){
            return response_json(['success' => false, 'message' => 'No such surveyed village found']);
        }

        if($surveyor_village->surveyor_village_revert_id){
            return response_json(['success' => false, 'message' => 'This survey is already reverted']);
        }
        
        $error_msg = [];
        $validation = [
                        [
                            'field' => 'reason',
                            'label' => 'Reason',
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
            return response_json(['success' => false, 'errors' => $error_msg], 403);
        }

        $team_id = $surveyor_village->team_id;
        $query = "SELECT deu.* from dataentryusers deu left join team_members tm on tm.user_code=deu.username where deu.user_role=? and tm.team_id=?";
        $supervisor = $this->db->query($query, array($supervisorCode, $team_id))->row();
        $supervisor_code = $supervisor->username;

        $this->db->trans_begin();
        try{
            $data = [
                'reason' => $this->input->post('reason'),
                'revert_to' => $supervisor_code,
                'surveyor_village_id' => $surveyor_village->id,
                'dist_code' => $surveyor_village->dist_code,
                'subdiv_code' => $surveyor_village->subdiv_code,
                'cir_code' => $surveyor_village->cir_code,
                'mouza_pargona_code' => $surveyor_village->mouza_pargona_code,
                'lot_no' => $surveyor_village->lot_no,
                'vill_townprt_code' => $surveyor_village->vill_townprt_code,
                'created_by' => $auth_code,
                'status' => SURVEY_REVERT_PENDING,
            ];
            $this->db->insert('surveyor_village_reverts', $data);
            if ($this->db->affected_rows() != 1) {
                log_message('error', '#ERRSURVRVRT0001: Last query => ' . $this->db->last_query());
                throw new Exception("#ERRSURVRVRT0001: Something went wrong. Please try again.");
            }
            $surveyor_village_revert_id = $this->db->insert_id();
            
            $this->db->where('id', $surveyor_village_id)->update('surveyor_villages', [
                'surveyor_village_revert_id' => $surveyor_village_revert_id
            ]);
            
            if ($this->db->affected_rows() != 1) {
                log_message('error', '#ERRSURVRVRT0002: Last query => ' . $this->db->last_query());
                throw new Exception("#ERRSURVRVRT0002: Something went wrong. Please try again.");
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                log_message('error', '#ERRSURVRVRT0003: Last query => ' . $this->db->last_query());
                throw new Exception("#ERRSURVRVRT0003: Something went wrong. Please try again.");
            }
        }catch(Exception $e){
            $this->db->trans_rollback();
            return response_json(['success' => false, 'message' => $e->getMessage()]);
        }

        $this->db->trans_commit();

        return response_json(['success' => true, 'message' => 'Survey is successfully reverted to the supervisor.']);
    }
}
