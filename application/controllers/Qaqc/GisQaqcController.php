<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class GisQaqcController extends CI_Controller
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
        // $auth_role = $this->session->userdata('usertype');
        $gisRole = $this->UserModel::$SURVEY_GIS_ASSISTANT_CODE;
        $userType = $this->session->userdata('usertype');
        if (!in_array($userType, [$gisRole])) {
            return redirect('/Login/logout');
        }

        $this->dbswitch('default');
        $loc_db = $this->db;

        // $surveyor_villages = $this->db->where('surveyor_code', $surveyor_code)->get('surveyor_villages')->result_array();
        $gis_circle = $loc_db->where('gisassistant_code', $auth_code)->get('gisassistant_circles')->row();

        $query = "SELECT sv.*, deu.name FROM surveyor_villages sv LEFT JOIN dataentryusers deu ON sv.surveyor_code = deu.username where sv.dist_code=? and sv.subdiv_code=? and sv.cir_code=?";
        $surveyor_villages = $loc_db->query($query, array($gis_circle->dist_code, $gis_circle->subdiv_code, $gis_circle->cir_code))->result_array();

        if(count($surveyor_villages)){
            foreach($surveyor_villages as $key => $surveyor_village){
                $this->dbswitch($surveyor_village['dist_code']);
                $surveyor_villages[$key]['dist_name'] = $this->utilityclass->getDistrictName($surveyor_village['dist_code']);
                $surveyor_villages[$key]['subdiv_name'] = $this->utilityclass->getSubDivName($surveyor_village['dist_code'], $surveyor_village['subdiv_code']);
                $surveyor_villages[$key]['circle_name'] = $this->utilityclass->getCircleName($surveyor_village['dist_code'], $surveyor_village['subdiv_code'], $surveyor_village['cir_code']);
                $surveyor_villages[$key]['mouza_name'] = $this->utilityclass->getMouzaName($surveyor_village['dist_code'], $surveyor_village['subdiv_code'], $surveyor_village['cir_code'], $surveyor_village['mouza_pargona_code']);
                $surveyor_villages[$key]['lot_name'] = $this->utilityclass->getLotName($surveyor_village['dist_code'], $surveyor_village['subdiv_code'], $surveyor_village['cir_code'], $surveyor_village['mouza_pargona_code'], $surveyor_village['lot_no']);
                $surveyor_villages[$key]['village_name'] = $this->utilityclass->getVillageName($surveyor_village['dist_code'], $surveyor_village['subdiv_code'], $surveyor_village['cir_code'], $surveyor_village['mouza_pargona_code'], $surveyor_village['lot_no'], $surveyor_village['vill_townprt_code']);
            }
        }
        
        $data['surveyor_villages'] = $surveyor_villages;
        $data['_view'] = 'qa_qc/gis_village_list';
        $this->load->view('layout/layout', $data);

    }

    public function uploadDailyProgress($survey_daily_progress_report_id){
        ini_set('upload_max_filesize', '-1');
        ini_set('post_max_size', '-1');
        $survey_daily_progress_report = $this->db->where('id', $survey_daily_progress_report_id)->get('survey_daily_progress_reports')->row();
        if(!$survey_daily_progress_report){
            return response_json(['success' => false, 'message' => 'No such report found']);
        }

        $report_date = date('Y-m-d');
        if($report_date <= date('Y-m-d', strtotime($survey_daily_progress_report->report_date))){
            return response_json(['success' => false, 'message' => 'You can do this operation after ' . date('d/m/Y', strtotime($report_date))]);
        }

        $extension_parts = explode('.', $_FILES['shape_file']['name']);
        $extension = end($extension_parts);
        $extension = strtolower($extension);
        
        if(!in_array($extension, ['dxf', 'zip'])){
        // if(!in_array($extension, ['dxf'])){
            return response_json(['success' => false, 'message' => 'Only .dxf, .zip (shape file) can be uploaded']);
        }
        $this->dbswitch('default');
        $loc_db = $this->db;
        $usercode = $this->session->userdata('usercode');

        $conditions = [
                        'dist_code' => $survey_daily_progress_report->dist_code,
                        'subdiv_code' => $survey_daily_progress_report->subdiv_code,
                        'cir_code' => $survey_daily_progress_report->cir_code,
                        'mouza_pargona_code' => $survey_daily_progress_report->mouza_pargona_code,
                        'lot_no' => $survey_daily_progress_report->lot_no,
                        'vill_townprt_code' => $survey_daily_progress_report->vill_townprt_code,
                    ];
        $report_ins = $loc_db->where('survey_daily_progress_report_id', $survey_daily_progress_report_id)
                                        ->where($conditions)
                                        ->get('gisassistant_qaqc_reports')
                                        ->row();
        if($report_ins){
            return response_json(['success' => false, 'message' => 'QAQC report already created']);
        }

        // $is_freezed = $loc_db->where('is_freezed', 1)->where($conditions)->get('gisassistant_qaqc_reports')->row();
        // if($is_freezed){
        //     return response_json(['success' => false, 'message' => "QAQC is completed for this village."]);
        // }


        $error_msg = [];
        $validation = [
                        [
                            'field' => 'land_parcel_survey',
                            'label' => 'Land Parcel Survey',
                            'rules' => 'required|numeric|greater_than[0]'
                        ],
                        [
                            'field' => 'area_surveyed',
                            'label' => 'Area Surveyed',
                            'rules' => 'required|numeric|greater_than[0]'
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

        $config['upload_path'] = SURVEYED_FILE_UPLOAD_PATH;
        // $config['allowed_types'] = 'dxf';
        $config['max_size'] = SURVEY_MAX_SIZE;
        $config['allowed_types'] = '*';
        $config['file_name'] = 'gis_qaqc_dly_upld_' . uniqid();
        $this->load->library('upload', $config);
        if($this->upload->do_upload('shape_file'))
        {
            $upload_data = $this->upload->data();
            $file_name = $upload_data['file_name'];
            $file_ext = (explode('.', $upload_data['file_ext']))[1];
            $loc_db->trans_begin();
            try{
                $insertData = [
                                'survey_daily_progress_report_id' => $survey_daily_progress_report_id,
                                'dist_code' => $survey_daily_progress_report->dist_code,
                                'subdiv_code' => $survey_daily_progress_report->subdiv_code,
                                'cir_code' => $survey_daily_progress_report->cir_code,
                                'mouza_pargona_code' => $survey_daily_progress_report->mouza_pargona_code,
                                'lot_no' => $survey_daily_progress_report->lot_no,
                                'vill_townprt_code' => $survey_daily_progress_report->vill_townprt_code,
                                'file_name' => $file_name,
                                'file_ext' => strtolower($file_ext),
                                'user_code' => $usercode,
                                'created_by' => $usercode,
                                'updated_by' => $usercode,
                                'land_parcel_survey' => $this->input->post('land_parcel_survey'),
                                'area_surveyed' => $this->input->post('area_surveyed'),
                                'report_date' => $report_date,
                            ];
                $loc_db->insert('gisassistant_qaqc_reports', $insertData);
                $inserted_id = $loc_db->insert_id();
                $ref_id = $inserted_id . '_gis_qaqc_dly';

                if($loc_db->trans_status() == false){
                    log_message('error', '#ERRUPDLYGISQAQC001: ' .  $loc_db->last_query());
                    throw new Exception("ERRUPDLYGISQAQC001: Something went wrong. Please try again later.");
                }

                if(ENABLE_BHUNAKSHA_DXF_MAP_API){
                    $loc_db->where('id', $inserted_id)->update('gisassistant_qaqc_reports', ['bhunaksha_ref_id' => $ref_id]);
                    $apiData = ['refId' => $ref_id, 'filePath' => $file_name];
                    if(strtolower($file_ext) == 'zip'){
                        $response = callBhunakshaApiForShpProcessMap($apiData);
                    }else{
                        $response = callBhunakshaApiForProcessMap($apiData);
                    }
                    if(!$response['success']){
                        $message = 'Something went wrong with the bhunaksha api';
                        if(isset($response['data']->message)){
                            $message = $response['data']->message;
                        }
                        throw new Exception($message);
                    }
                }
                $this->create_daily_progress_logs($inserted_id);
                $loc_db->where('id', $survey_daily_progress_report_id)->update('survey_daily_progress_reports', ['is_gis_qaqc_completed' => 1]);
            }catch(Exception $e){
                $path = SURVEYED_FILE_UPLOAD_PATH . $file_name;
                unlink($path);
                $loc_db->trans_rollback();
                return response_json(['success' => false, 'message' => $e->getMessage()], 403);
            }

            $loc_db->trans_commit();
        }
        else
        {
            return response_json(['success' => false, 'message' => $this->upload->display_errors()]);
        }

        return response_json(['success' => true, 'message' => 'Daily progress saved successfully.']);
    }

    private function create_daily_progress_logs($gisassistant_qaqc_report_id){
        $gisassistant_qaqc_report = $this->db->where('id', $gisassistant_qaqc_report_id)->get('gisassistant_qaqc_reports')->row();
        $insertData = [
                            'gisassistant_qaqc_report_id' => $gisassistant_qaqc_report->id,
                            'survey_daily_progress_report_id' => $gisassistant_qaqc_report->survey_daily_progress_report_id,
                            'user_code' => $gisassistant_qaqc_report->user_code,
                            'dist_code' => $gisassistant_qaqc_report->dist_code,
                            'subdiv_code' => $gisassistant_qaqc_report->subdiv_code,
                            'cir_code' => $gisassistant_qaqc_report->cir_code,
                            'mouza_pargona_code' => $gisassistant_qaqc_report->mouza_pargona_code,
                            'lot_no' => $gisassistant_qaqc_report->lot_no,
                            'vill_townprt_code' => $gisassistant_qaqc_report->vill_townprt_code,
                            'file_name' => $gisassistant_qaqc_report->file_name,
                            'file_ext' => $gisassistant_qaqc_report->file_ext,
                            'created_by' => $gisassistant_qaqc_report->created_by,
                            'land_parcel_survey' => $gisassistant_qaqc_report->land_parcel_survey,
                            'area_surveyed' => $gisassistant_qaqc_report->area_surveyed,
                            'report_date' => $gisassistant_qaqc_report->report_date,
                            'bhunaksha_ref_id' => $gisassistant_qaqc_report->bhunaksha_ref_id,
                        ];
        $this->db->insert('gisassistant_qaqc_report_logs', $insertData);
    }

    public function updateReport($gisassistant_qaqc_report_id){
        ini_set('upload_max_filesize', '-1');
        ini_set('post_max_size', '-1');
        $usercode = $this->session->userdata('usercode');
        $this->dbswitch('default');
        $loc_db = $this->db;
        $gisassistant_qaqc_report = $loc_db->where('id', $gisassistant_qaqc_report_id)->where('user_code', $usercode)->get('gisassistant_qaqc_reports')->row();
        if(!$gisassistant_qaqc_report){
            return response_json(['success' => false, 'message' => 'No such record found']);
        }

        if($gisassistant_qaqc_report->is_freezed == 1){
            return response_json(['success' => false, 'message' => "You cannot update this record as this process is completed."]);
        }

        if(isset($_FILES['shape_file']) && $_FILES['shape_file']['name'] != ''){
            $extension_parts = explode('.', $_FILES['shape_file']['name']);
            $extension = end($extension_parts);
            $extension = strtolower($extension);
            if(!in_array($extension, ['dxf', 'zip'])){
            // if(!in_array($extension, ['dxf'])){
                return response_json(['success' => false, 'message' => 'Only .dxf, .zip (shape file) can be uploaded']);
            }
        }

        $error_msg = [];
        $validation = [
                        [
                            'field' => 'land_parcel_survey',
                            'label' => 'Land Parcel Survey',
                            'rules' => 'required|numeric|greater_than[0]'
                        ],
                        [
                            'field' => 'area_surveyed',
                            'label' => 'Area Surveyed',
                            'rules' => 'required|numeric|greater_than[0]'
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

        $file_name = NULL;
        $updateData = [
                            'file_name' => $gisassistant_qaqc_report->file_name,
                            'file_ext' => $gisassistant_qaqc_report->file_ext,
                            'land_parcel_survey' => $this->input->post('land_parcel_survey'),
                            'area_surveyed' => $this->input->post('area_surveyed'),
                            'updated_by' => $usercode,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ];
        if(isset($_FILES['shape_file']) && $_FILES['shape_file']['name'] != ''){
            $config['upload_path'] = SURVEYED_FILE_UPLOAD_PATH;
            // $config['allowed_types'] = 'dxf';
            $config['allowed_types'] = '*';
            $config['max_size'] = SURVEY_MAX_SIZE;
            $config['file_name'] = 'gis_qaqc_dly_upld_' . uniqid();
            $this->load->library('upload', $config);
            if($this->upload->do_upload('shape_file'))
            {
                $upload_data = $this->upload->data();
                $file_name = $upload_data['file_name'];
                $file_ext = (explode('.', $upload_data['file_ext']))[1];
                $updateData['file_name'] = $file_name;
                $updateData['file_ext'] = strtolower($file_ext);
            }
            else
            {
                return response_json(['success' => false, 'message' => $this->upload->display_errors()]);
            }
        }
        
        $loc_db->trans_begin();
        try{
            $loc_db->where('id', $gisassistant_qaqc_report_id)->update('gisassistant_qaqc_reports', $updateData);
            $ref_id = $gisassistant_qaqc_report->bhunaksha_ref_id;

            if($loc_db->trans_status() == false){
                log_message('error', '#ERRUPDLYGISQAQC002: ' .  $loc_db->last_query());
                throw new Exception("ERRUPDLYGISQAQC002: Something went wrong. Please try again later.");
            }

            if(ENABLE_BHUNAKSHA_DXF_MAP_API && $file_name){
                $apiData = ['refId' => $ref_id, 'filePath' => $file_name];
                if(strtolower($file_ext) == 'zip'){
                    $response = callBhunakshaApiForUpdateShpProcessMap($apiData);
                }else{
                    $response = callBhunakshaApiForUpdateProcessMap($apiData);
                }
                if(!$response['success']){
                    $message = 'Something went wrong with the bhunaksha api';
                    if(isset($response['data']->message)){
                        $message = $response['data']->message;
                    }
                    throw new Exception($message);
                }
            }
            $this->create_daily_progress_logs($gisassistant_qaqc_report_id);
        }catch(Exception $e){
            if($file_name){
                $path = SURVEYED_FILE_UPLOAD_PATH . $file_name;
                unlink($path);
            }
            $loc_db->trans_rollback();
            return response_json(['success' => false, 'message' => $e->getMessage()], 403);
        }
        $loc_db->trans_commit();

        return response_json(['success' => true, 'message' => 'Report updated successfully.']);
    }

    public function markCompleteReport($surveyor_village_id){
        $this->dbswitch('default');
        $loc_db = $this->db;
        $auth_role = $this->session->userdata('usertype');
        $usercode = $this->session->userdata('usercode');
        $gis_role = $this->UserModel::$SURVEY_GIS_ASSISTANT_CODE;
        if($gis_role != $auth_role){
            return response_json(['success' => false, 'message' => 'You are not authorize to complete the QAQC']);
        }
        $surveyor_village = $this->db->where('id', $surveyor_village_id)->get('surveyor_villages')->row();

        if(!$surveyor_village){
            return response_json(['success' => false, 'message' => 'No such village found']);
        }

        if($surveyor_village->is_surveyor_completed_survey == 0){
            return response_json(['success' => false, 'message' => 'Survey has not been completed yet from the Surveyor end.']);
        }

        if($surveyor_village->is_gis_qaqc_completed == 1){
            return response_json(['success' => false, 'message' => 'Already marked completed this QAQC.']);
        }
        
        $conditions = [
                        'dist_code' => $surveyor_village->dist_code,
                        'subdiv_code' => $surveyor_village->subdiv_code,
                        'cir_code' => $surveyor_village->cir_code,
                        'mouza_pargona_code' => $surveyor_village->mouza_pargona_code,
                        'lot_no' => $surveyor_village->lot_no,
                        'vill_townprt_code' => $surveyor_village->vill_townprt_code,
                    ];

        $survey_daily_progress_report_count = $this->db->where($conditions)->get('survey_daily_progress_reports')->num_rows();
        $gis_qaqc_report_count = $this->db->where($conditions)->get('gisassistant_qaqc_reports')->num_rows();
        if($survey_daily_progress_report_count != $gis_qaqc_report_count){
            return response_json(['success' => false, 'message' => 'First upload the QAQC report for all daily progress report uploaded by Surveyor for this village.']);
        }

        $loc_db->trans_begin();
        try{
            $loc_db->where('user_code', $usercode)
                        ->where($conditions)
                        ->update('gisassistant_qaqc_reports', ['is_freezed' => 1]);

            $loc_db->where('id', $surveyor_village_id)->update('surveyor_villages', ['is_gis_qaqc_completed' => 1]);

            if($loc_db->trans_status() == false){
                log_message('error', '#ERRUPDLYGISQAQC003: ' .  $loc_db->last_query());
                throw new Exception("ERRUPDLYGISQAQC003: Something went wrong. Please try again later.");
            }
        }catch(Exception $e){
            $loc_db->trans_rollback();
            return response_json(['success' => false, 'message' => $e->getMessage()], 403);
        }

        $loc_db->trans_commit();
        
        return response_json(['success' => true, 'message' => 'QAQC is completed.']);
    }
}
