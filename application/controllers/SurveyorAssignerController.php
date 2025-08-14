<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class SurveyorAssignerController extends CI_Controller
{
    use CommonTrait;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('UserModel');
        // $this->load->helper('security');
        // $this->load->library('UtilityClass');
    }

    public function indexOld()
    {
        $data['surveyors'] = $this->db->where('user_role', $this->UserModel::$SURVEYOR_CODE)->get('dataentryusers')->result_array();
        $data['districts'] = $this->db->get_where('location', array('subdiv_code' => '00', 'cir_code' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->result_array();
        $data['_view'] = 'surveyor_assign/index-old';
        $this->load->view('layout/layout', $data);
    }
    
    public function index()
    {
        $this->dbswitch('default');
        $user_code = $this->session->userdata('usercode');
        $query = "SELECT t.* from teams t left join team_members tm on t.id=tm.team_id where tm.user_code=?";
        $circle_query = "SELECT l.* from location l left join supervisor_circles sc on l.dist_code=sc.dist_code and l.subdiv_code=sc.subdiv_code and l.cir_code=sc.cir_code where l.mouza_pargona_code='00' and l.lot_no='00' and l.vill_townprt_code='00000' and sc.supervisor_code=?";
        $teams = $this->db->query($query, array($user_code))->result_array();
        $data['teams'] = $teams;
        $data['circles'] = $this->db->query($circle_query, array($user_code))->result_array();
        $data['_view'] = 'surveyor_assign/index';
        $this->load->view('layout/layout', $data);
    }

    public function surveyordetails()
    {
        $this->dbswitch('default');
        $data = [];
        $surveyor_code = $this->UserModel::$SURVEYOR_CODE;
        $team_id = $this->input->post('team_id');
        $query = "SELECT deu.* from dataentryusers deu join team_members tm on deu.username = tm.user_code where tm.team_id=? and deu.user_role=?";
        // $this->session->set_userdata('dist_code', $dist_code);
        // $subdiv_code = $this->session->userdata('subdiv_code');
        $surveyors = $this->db->query($query, array($team_id, $surveyor_code))->result_array();
        // foreach ($surveyors as $value) {
        //     $data['subdiv_code'][] = $value;
        // }
        // echo json_encode($formdata);
        echo json_encode($surveyors);
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
        $data = [];
        $dist_code = $this->input->post('dis');
        $this->dbswitch($dist_code);
        $subdiv = $this->input->post('subdiv');
        // $cir_code = $this->session->userdata('cir_code');
        $this->session->set_userdata('subdiv_code', $subdiv);
        $formdata = $this->Chithamodel->circledetails($dist_code, $subdiv);
        foreach ($formdata as $value) {
            $data['cir_code'][] = $value;
        }
        echo json_encode($formdata);
    }

    public function mouzadetails()
    {
        // $this->dataswitch();
        $data = [];
        $dis = $this->input->post('dis');
        $this->dbswitch($dis);
        $subdiv = $this->input->post('subdiv');
        $cir = $this->input->post('cir');
        $this->session->set_userdata('cir_code', $cir);
        $formdata = $this->Chithamodel->mouzadetails($dis, $subdiv, $cir);
        foreach ($formdata as $value) {
            $data['cir_code'][] = $value;
        }
        echo json_encode($formdata);
    }

    public function lotdetails()
    {
        // $this->dataswitch();
        $data = [];
        $dis = $this->input->post('dis');
        $this->dbswitch($dis);
        $subdiv = $this->input->post('subdiv');
        $cir = $this->input->post('cir');
        $mza = $this->input->post('mza');
        $this->session->set_userdata('mouza_pargona_code', $mza);
        $formdata = $this->Chithamodel->lotdetails($dis, $subdiv, $cir, $mza);
        foreach ($formdata as $value) {
            $data['test'][] = $value;
        }
        echo json_encode($formdata);
    }

    public function villagedetails()
    {
        // $this->dataswitch();
        $data = [];
        $this->dbswitch('default');
        $loc_db = $this->db;

        $dis = $this->input->post('dis');
        $this->dbswitch($dis);
        $subdiv = $this->input->post('subdiv');
        $cir = $this->input->post('cir');
        $mza = $this->input->post('mza');
        $lot = $this->input->post('lot');
        $this->session->set_userdata('lot_no', $lot);
        $villages = $this->Chithamodel->allVillagedetails($dis, $subdiv, $cir, $mza, $lot, SURVEY_NC_BTAD_VILLAGE_STATUS);
        $team_id = $this->input->post('team_id');   
        $surveyor_code = $this->input->post('surveyor_code');   
        $data = [];
        
        foreach ($villages as $village) {
            if($village['nc_btad'] == SURVEY_NC_BTAD_VILLAGE_STATUS){
                // $data['test'][] = $value;
                $vill_townprt_code = $village['vill_townprt_code'];
                $is_surveyor_village_exists = $loc_db->query("select * from surveyor_villages where dist_code='$dis' and subdiv_code='$subdiv' and mouza_pargona_code='$mza' and lot_no='$lot' and vill_townprt_code='$vill_townprt_code' and team_id='$team_id' and surveyor_code='$surveyor_code'")->row();
                $data_single = [];
                $data_single['district_name'] = $this->utilityclass->getDistrictName($village['dist_code']);
                $data_single['subdivision'] = $this->utilityclass->getSubDivName($village['dist_code'], $village['subdiv_code']);
                $data_single['circle'] = $this->utilityclass->getCircleName($village['dist_code'], $village['subdiv_code'], $village['cir_code']);
                $data_single['mouza_pargona'] = $this->utilityclass->getMouzaName($village['dist_code'], $village['subdiv_code'], $village['cir_code'], $village['mouza_pargona_code']);
                $data_single['lot_name'] = $this->utilityclass->getLotLocationName($village['dist_code'], $village['subdiv_code'], $village['cir_code'], $village['mouza_pargona_code'], $village['lot_no']);
                $data_single['loc_name'] = $village['loc_name'];
                $data_single['vill_townprt_code'] = $vill_townprt_code;
                if ($is_surveyor_village_exists) {
                    $data_single['surveyor_village'] = $vill_townprt_code;
                    $data_single['apprx_area'] = $is_surveyor_village_exists->apprx_area;
                } else {
                    $data_single['surveyor_village'] = '';
                    $data_single['apprx_area'] = 0;
                }
                $data[] = $data_single;

            }
        }
        echo json_encode($data);
    }

    public function saveSurveyorVillages()
    {
        $dist_code = $this->input->post('dist_code');
        // $this->dbswitch($dist_code);
        $this->dbswitch('default');
        $loc_db = $this->db;
        // dd($this->db->database);
        $team_id = $this->input->post('team_id');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $surveyor_code = $this->input->post('surveyor');
        $villages = $this->input->post('villages');
        $apprx_areas = $this->input->post('areas');
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
                            'field' => 'mouza_pargona_code',
                            'label' => 'Mouza',
                            'rules' => 'required'
                        ],
                        [
                            'field' => 'lot_no',
                            'label' => 'Sub-division',
                            'rules' => 'required'
                        ],
                        [
                            'field' => 'surveyor',
                            'label' => 'Surveyor',
                            'rules' => 'required'
                        ],
                        [
                            'field' => 'villages[]',
                            'label' => 'Village',
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
                array_push($msgs, '<p style="color:red;">' . $err_msg . '.</p>');
            }
            echo json_encode(['st' => 'fail', 'msgs' => $msgs]);
            exit;
            // return response_json(['success' => false, 'errors' => $error_msg], 403);
        }

        $all_surveyor_villages = $loc_db->query("select vill_townprt_code from surveyor_villages where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and surveyor_code='$surveyor_code' and team_id='$team_id'")->result();

        $conditions = [
                        'dist_code' => $dist_code,
                        'subdiv_code' => $subdiv_code,
                        'cir_code' => $cir_code,
                        'mouza_pargona_code' => $mouza_pargona_code,
                        'lot_no' => $lot_no
                    ];
        // Check is same village assign to same surveyor in other team or not Start
        foreach ($villages as $village) {
            $village_name = $this->utilityclass->getVillageName($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $village);
            if(!isset($apprx_areas[$village]) || empty($apprx_areas[$village])){
                $msgs[] = '<p style="color:red;">Please enter approx area for ' . $village_name . '.</p>';
                echo json_encode(['st' => 'fail', 'msgs' => $msgs]);
                exit;
            }

            $already_assigned_to_other_surveyor = $loc_db->where($conditions)
                                                    ->where('vill_townprt_code', $village)
                                                    ->get('surveyor_villages')
                                                    ->row();

            if($already_assigned_to_other_surveyor){
                $other_surveyor = $loc_db->where('username', $already_assigned_to_other_surveyor->surveyor_code)->get('dataentryusers')->row();
                if($already_assigned_to_other_surveyor->surveyor_code != $surveyor_code){
                    $msgs[] = '<p style="color:red;">' . $village_name . ' is already assigned to ' . $other_surveyor->name . '.</p>';
                    echo json_encode(['st' => 'fail', 'msgs' => $msgs]);
                    exit;
                }else{
                    // Check whether team is same or not 
                    if($already_assigned_to_other_surveyor->team_id != $team_id){
                        $assigned_team = $loc_db->where('id', $already_assigned_to_other_surveyor->team_id)->get('teams')->row();
                        if(!$assigned_team){
                            $msgs[] = '<p style="color:red;">Assigned team is missing. Please contact with the administrator.</p>';
                            echo json_encode(['st' => 'fail', 'msgs' => $msgs]);
                            exit;
                        }

                        $msgs[] = '<p style="color:red;">' . $village_name . ' is already assigned to ' . $other_surveyor->name . ' for ' . $assigned_team->name . '.</p>';
                        echo json_encode(['st' => 'fail', 'msgs' => $msgs]);
                        exit;
                    }
                }
            }
        }
        // Check is same village assign to same surveyor in other team or not End

        // If uncheck already assigned villages, then check whether surveyor started working on that villages or not -- START
        foreach ($all_surveyor_villages as $surveyor_village_exisiting) {
            if (!in_array($surveyor_village_exisiting->vill_townprt_code, $villages)) {
                $survey_daily_progress_reports = $loc_db->where($conditions)->where('vill_townprt_code', $surveyor_village_exisiting->vill_townprt_code)->get('survey_daily_progress_reports')->row();
                if($survey_daily_progress_reports){
                    $village_name = $this->utilityclass->getVillageName($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $surveyor_village_exisiting->vill_townprt_code);
                    $msgs[] = '<p style="color:red;">You cannot remove ' . $village_name . '. As the survey has been started on that village.</p>';
                    echo json_encode(['st' => 'fail', 'msgs' => $msgs]);
                    exit;
                }
            }
        }
        // If uncheck already assigned villages, then check whether surveyor started working on that villages or not -- END

        foreach ($all_surveyor_villages as $surveyor_village_exisiting) {
            if (!in_array($surveyor_village_exisiting->vill_townprt_code, $villages)) {
                $loc_db->query("delete from surveyor_villages where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$surveyor_village_exisiting->vill_townprt_code' and surveyor_code='$surveyor_code' and team_id='$team_id'");
            }
        }
        $msgs = [];
        foreach ($villages as $village) {
            $is_exists = $loc_db->query("select * from surveyor_villages where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$village' and team_id='$team_id'")->row();
            if (!$is_exists) {
                $loc_db->insert('surveyor_villages', [
                    'dist_code' => $dist_code,
                    'subdiv_code' => $subdiv_code,
                    'cir_code' => $cir_code,
                    'mouza_pargona_code' => $mouza_pargona_code,
                    'lot_no' => $lot_no,
                    'vill_townprt_code' => $village,
                    'surveyor_code' => $surveyor_code,
                    'team_id' => $team_id,
                    'apprx_area' => $apprx_areas[$village],
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by_usercode' => $this->session->userdata('usercode'),
                    'created_by_role' => $this->session->userdata('usertype')
                ]);
            } else {
                $loc_db->where('id', $is_exists->id)->update('surveyor_villages', ['apprx_area' => $apprx_areas[$village]]);
                // $is_exists_self = $this->db->query("select * from surveyor_villages where dist_code='$dist_code' and subdiv_code='$subdiv_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$village' and surveyor_code='$surveyor_code'")->row();
                // if (!$is_exists_self) {
                //     // $lm = $this->db->query("select lut.use_name,lm.* from lm_code lm
                //     // join loginuser_table lut on lut.dist_code = lm.dist_code and lut.subdiv_code=lm.subdiv_code and lut.cir_code=lm.cir_code and lut.mouza_pargona_code=lm.mouza_pargona_code and lut.lot_no=lm.lot_no and lut.user_code=lm.surveyor_code 
                //     // where lm.dist_code='$dist_code' and lm.subdiv_code='$subdiv_code' and lm.cir_code='$cir_code' and lm.mouza_pargona_code='$mouza_pargona_code' and lm.lot_no='$lot_no' and lm.surveyor_code='$surveyor_code'")->row();
                //     // $msgs[] = '<p style="color:red;">Village ' . $village . ' already assigned to ' . $lm->lm_name . '.</p>';
                // }
            }
        }

        $batch_id = uniqid();
        $surveyor_villages = $loc_db->where('team_id', $team_id)
                                    ->where('dist_code', $dist_code)
                                    ->where('subdiv_code', $subdiv_code)
                                    ->where('cir_code', $cir_code)
                                    ->where('mouza_pargona_code', $mouza_pargona_code)
                                    ->where('lot_no', $lot_no)
                                    ->where('surveyor_code', $surveyor_code)
                                    ->get('surveyor_villages')->result();
        foreach($surveyor_villages as $surveyor_village){
            $this->createSurveyorVillageLog($batch_id, $surveyor_village->id);
        }

        $msgs[] = '<p style="color:green;">Successfully Saved.</p>';
        echo json_encode(['st' => 'success', 'msgs' => $msgs]);
    }

    private function createSurveyorVillageLog($batch_id, $surveyor_village_id){
        $this->dbswitch('default');
        $surveyor_village = $this->db->where('id', $surveyor_village_id)->get('surveyor_villages')->row();
        $data = [
                    'surveyor_village_id' => $surveyor_village_id,
                    'batch_id' => $batch_id,
                    'team_id' => $surveyor_village->team_id,
                    'dist_code' => $surveyor_village->dist_code,
                    'subdiv_code' => $surveyor_village->subdiv_code,
                    'cir_code' => $surveyor_village->cir_code,
                    'mouza_pargona_code' => $surveyor_village->mouza_pargona_code,
                    'lot_no' => $surveyor_village->lot_no,
                    'vill_townprt_code' => $surveyor_village->vill_townprt_code,
                    'surveyor_code' => $surveyor_village->surveyor_code,
                    'uuid' => $surveyor_village->uuid,
                    'created_by_usercode' => $surveyor_village->created_by_usercode,
                    'created_by_role' => $surveyor_village->created_by_role,
                    'apprx_area' => $surveyor_village->apprx_area,
                    'final_report_uploaded' => $surveyor_village->final_report_uploaded,
                    'surveyor_village_revert_id' => $surveyor_village->surveyor_village_revert_id,
                ];

        $this->db->insert('surveyor_village_logs', $data);
    }
}
