<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class CircleAssignToSupervisorController extends CI_Controller
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
        $this->dbswitch('default');
        $data['supervisors'] = $this->db->where('user_role', $this->UserModel::$SUPERVISOR_CODE)->get('dataentryusers')->result_array();
        $data['districts'] = $this->db->get_where('location', array('subdiv_code' => '00', 'cir_code' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->result_array();
        $data['_view'] = 'circle_asssign_to_supervisor/index';
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
        $supervisor_code = $this->input->post('supervisor_code');   
        $data = [];
        $this->dbswitch('default');
        foreach ($formdata as $village) {
            // $data['test'][] = $value;
            $cir_code = $village['cir_code'];
            $is_supervisor_circle_exists = $this->db->query("select * from supervisor_circles where dist_code='$dist_code' and subdiv_code='$subdiv' and cir_code='$cir_code' and supervisor_code='$supervisor_code'")->row();
            $data_single = [];
            $data_single['loc_name'] = $village['loc_name'];
            $data_single['cir_code'] = $cir_code;
            if ($is_supervisor_circle_exists) {
                $data_single['supervisor_circle'] = $cir_code;
            } else {
                $data_single['supervisor_circle'] = '';
            }
            $data[] = $data_single;
        }
        echo json_encode($data);
    }

    // public function mouzadetails()
    // {
    //     // $this->dataswitch();
    //     $data = [];
    //     $dis = $this->input->post('dis');
    //     $this->dbswitch($dis);
    //     $subdiv = $this->input->post('subdiv');
    //     $cir = $this->input->post('cir');
    //     $this->session->set_userdata('cir_code', $cir);
    //     $formdata = $this->Chithamodel->mouzadetails($dis, $subdiv, $cir);
    //     foreach ($formdata as $value) {
    //         $data['cir_code'][] = $value;
    //     }
    //     echo json_encode($formdata);
    // }

    // public function lotdetails()
    // {
    //     // $this->dataswitch();
    //     $data = [];
    //     $dis = $this->input->post('dis');
    //     $this->dbswitch($dis);
    //     $subdiv = $this->input->post('subdiv');
    //     $cir = $this->input->post('cir');
    //     $mza = $this->input->post('mza');
    //     $this->session->set_userdata('mouza_pargona_code', $mza);
    //     $formdata = $this->Chithamodel->lotdetails($dis, $subdiv, $cir, $mza);
    //     foreach ($formdata as $value) {
    //         $data['test'][] = $value;
    //     }
    //     echo json_encode($formdata);
    // }

    // public function villagedetails()
    // {
    //     $this->dataswitch();
    //     $data = [];
    //     $dis = $this->input->post('dis');
    //     // $this->dbswitch($dis);
    //     $subdiv = $this->input->post('subdiv');
    //     $cir = $this->input->post('cir');
    //     $mza = $this->input->post('mza');
    //     $lot = $this->input->post('lot');
    //     $this->session->set_userdata('lot_no', $lot);
    //     $villages = $this->Chithamodel->villagedetails($dis, $subdiv, $cir, $mza, $lot);
    //     $supervisor_code = $this->input->post('supervisor_code');   
    //     $data = [];
    //     foreach ($villages as $village) {
    //         // $data['test'][] = $value;
    //         $cir_code = $village['cir_code'];
    //         $is_supervisor_circle_exists = $this->db->query("select * from supervisor_circles where dist_code='$dis' and subdiv_code='$subdiv' and mouza_pargona_code='$mza' and lot_no='$lot' and cir_code='$cir_code' and supervisor_code='$supervisor_code'")->row();
    //         $data_single = [];
    //         $data_single['loc_name'] = $village['loc_name'];
    //         $data_single['cir_code'] = $cir_code;
    //         if ($is_supervisor_circle_exists) {
    //             $data_single['supervisor_circle'] = $cir_code;
    //         } else {
    //             $data_single['supervisor_circle'] = '';
    //         }
    //         $data[] = $data_single;
    //     }
    //     echo json_encode($data);
    // }

    public function saveSupervisorCircles()
    {
        $dist_code = $this->input->post('dist_code');
        // $this->dbswitch($dist_code);
        $this->dbswitch('default');
        $subdiv_code = $this->input->post('subdiv_code');
        $requested_circles = $this->input->post('circles');
        $supervisor_code = $this->input->post('supervisor');

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
                            'field' => 'supervisor',
                            'label' => 'Supervisor',
                            'rules' => 'required'
                        ],
                        [
                            'field' => 'circles[]',
                            'label' => 'Circle',
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

        $all_supervisor_circles = $this->db->query("select cir_code from supervisor_circles where dist_code='$dist_code' and subdiv_code='$subdiv_code' and supervisor_code='$supervisor_code'")->result();

        // Validation Start
        $supervisor_teams = $this->db->where('user_code', $supervisor_code)->get('team_members')->result_array();
        if(count($supervisor_teams)){
            $team_ids = [];
            foreach($supervisor_teams as $supervisor_team){
                array_push($team_ids, $supervisor_team['team_id']);
            }
            $team_ids_str = implode(',', $team_ids);

            $query1 = "SELECT DISTINCT dist_code, subdiv_code, cir_code, team_id from surveyor_villages where team_id in ($team_ids_str)";
            $surveyor_villages = $this->db->query($query1)->result_array();
            
            if(count($surveyor_villages)){
                $requested_cir_loc = [];
                foreach($requested_circles as $requested_circle){
                    $loc = $dist_code . '_' . $subdiv_code . '_' . $requested_circle;
                    array_push($requested_cir_loc, $loc);
                }
                
                foreach($surveyor_villages as $surveyor_village){
                    $existing_cir_loc = $surveyor_village['dist_code'] . '_' . $surveyor_village['subdiv_code'] . '_' . $surveyor_village['cir_code'];
                    if(($surveyor_village['dist_code'] == $dist_code && $surveyor_village['subdiv_code'] == $subdiv_code) && !in_array($existing_cir_loc, $requested_cir_loc)){
                        $this->dbswitch($dist_code);
                        $circle_name = $this->utilityclass->getCircleName($surveyor_village['dist_code'], $surveyor_village['subdiv_code'], $surveyor_village['cir_code']);
                        return response_json(['success' => false, 'message' => 'You cannot remove "' . $circle_name . '". As villages had already been assigned to surveyor(s)']);
                    }
                }
            }
        }
        // Validation End
        
        $this->dbswitch('default');
        foreach ($all_supervisor_circles as $surveyor_village_exisiting) {
            if (!in_array($surveyor_village_exisiting->cir_code, $requested_circles)) {
                $this->db->query("delete from supervisor_circles where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$surveyor_village_exisiting->cir_code' and supervisor_code='$supervisor_code'");
            }
        }
        
        $msgs = [];
        foreach ($requested_circles as $circle) {
            $is_exists = $this->db->query("select * from supervisor_circles where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$circle' and supervisor_code='$supervisor_code'")->row();
            if (!$is_exists) {
                $this->db->insert('supervisor_circles', [
                    'dist_code' => $dist_code,
                    'subdiv_code' => $subdiv_code,
                    'cir_code' => $circle,
                    'supervisor_code' => $supervisor_code,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by_usercode' => $this->session->userdata('usercode'),
                    'created_by_role' => $this->session->userdata('usertype')
                ]);
            } else {
                // $is_exists_self = $this->db->query("select * from surveyor_villages where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$circle' and supervisor_code='$supervisor_code'")->row();
                // if (!$is_exists_self) {
                //     // $lm = $this->db->query("select lut.use_name,lm.* from lm_code lm
                //     // join loginuser_table lut on lut.dist_code = lm.dist_code and lut.subdiv_code=lm.subdiv_code and lut.cir_code=lm.cir_code and lut.mouza_pargona_code=lm.mouza_pargona_code and lut.lot_no=lm.lot_no and lut.user_code=lm.surveyor_code 
                //     // where lm.dist_code='$dist_code' and lm.subdiv_code='$subdiv_code' and lm.cir_code='$cir_code' and lm.mouza_pargona_code='$mouza_pargona_code' and lm.lot_no='$lot_no' and lm.surveyor_code='$surveyor_code'")->row();
                //     // $msgs[] = '<p style="color:red;">Village ' . $village . ' already assigned to ' . $lm->lm_name . '.</p>';
                // }
            }
        }

        $batch_id = uniqid();
        $supervisor_circles = $this->db->where('supervisor_code', $supervisor_code)
                                        ->where('dist_code', $dist_code)
                                        ->where('subdiv_code', $subdiv_code)
                                        ->get('supervisor_circles')->result();
        foreach($supervisor_circles as $supervisor_circle){
            $this->createSupervisorCircleLog($batch_id, $supervisor_circle->id);
        }
        $msgs[] = '<p style="color:green;">Successfully Saved.</p>';
        // echo json_encode(['st' => 'success', 'msgs' => $msgs]);
        return response_json(['success' => true, 'message' => $msgs]);
    }

    private function createSupervisorCircleLog($batch_id, $supervisor_circle_id){
        $this->dbswitch('default');
        $supervisor_circle = $this->db->where('id', $supervisor_circle_id)->get('supervisor_circles')->row();
        $data = [
                    'supervisor_circle_id' => $supervisor_circle_id,
                    'batch_id' => $batch_id,
                    'dist_code' => $supervisor_circle->dist_code,
                    'subdiv_code' => $supervisor_circle->subdiv_code,
                    'cir_code' => $supervisor_circle->cir_code,
                    'supervisor_code' => $supervisor_circle->supervisor_code,
                    'uuid' => $supervisor_circle->uuid,
                    'created_by_usercode' => $supervisor_circle->created_by_usercode,
                    'created_by_role' => $supervisor_circle->created_by_role,
                ];

        $this->db->insert('supervisor_circle_logs', $data);
    }
}
