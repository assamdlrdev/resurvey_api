<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class SurveyController extends CI_Controller
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
        $surveySuperAdminCode = $this->UserModel::$SURVEY_SUPER_ADMIN_CODE;
        $supervisorCode = $this->UserModel::$SUPERVISOR_CODE;
        $surveyorCode = $this->UserModel::$SURVEYOR_CODE;
        $spmuCode = $this->UserModel::$SPMU_CODE;
        $gisCode = $this->UserModel::$SURVEY_GIS_ASSISTANT_CODE;
        $userType = $this->session->userdata('usertype');
        if (!in_array($userType, [$surveySuperAdminCode, $supervisorCode, $surveyorCode, $spmuCode, $gisCode])) {
            return redirect('/Login/logout');
        }

        $search_dist_code = $this->input->post('dist_code');
        $search_subdiv_code = $this->input->post('subdiv_code');
        $search_cir_code = $this->input->post('cir_code');
        $search_mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $search_lot_no = $this->input->post('lot_no');
        $search_vill_townprt_code = $this->input->post('vill_townprt_code');
        $search_user = $this->input->post('user');

        $conditions = [];
        if(!empty($search_dist_code)) $conditions['dist_code'] = $search_dist_code;
        if(!empty($search_subdiv_code)) $conditions['subdiv_code'] = $search_subdiv_code;
        if(!empty($search_cir_code)) $conditions['cir_code'] = $search_cir_code;
        if(!empty($search_mouza_pargona_code)) $conditions['mouza_pargona_code'] = $search_mouza_pargona_code;
        if(!empty($search_lot_no)) $conditions['lot_no'] = $search_lot_no;
        if(!empty($search_vill_townprt_code)) $conditions['vill_townprt_code'] = $search_vill_townprt_code;

        $total_villages_count = $survey_completed_count = $qc_completed_count = $draft_map_prepared_count = $final_map_prepared_count = 0;
        $total_completed_village_area_count = $total_village_area_count = 0;

        $total_land_parcel_count = $this->db->select_sum('land_parcel_survey')->where($conditions)->get('survey_daily_progress_reports')->row()->land_parcel_survey;
        $this->dbswitch('default');
        $loc_db = $this->db;
        $enable_report_link = false;

        if($userType == $surveySuperAdminCode){
            $total_villages_count = $this->getVillageCount($search_dist_code, $search_subdiv_code, $search_cir_code, $search_mouza_pargona_code, $search_lot_no, $search_user);
            $survey_completed_count = $loc_db->where($conditions)->where('final_report_uploaded', 1)->get('surveyor_villages')->num_rows();
            $total_village_area_count = $loc_db->select_sum('apprx_area')->where($conditions)->get('surveyor_villages')->row()->apprx_area;
            $total_completed_village_area_count = $loc_db->select_sum('area_surveyed')->where($conditions)->get('survey_daily_progress_reports')->row()->area_surveyed;
        }elseif($userType == $spmuCode){
            //
        }elseif($userType == $supervisorCode){
            $enable_report_link = true;
            $super_cir_query = "SELECT dist_code, subdiv_code, cir_code from supervisor_circles where supervisor_code=? GROUP BY dist_code, subdiv_code, cir_code";
            $supervisor_circles = $loc_db->query($super_cir_query, array($auth_code))->result_array();
            $team_members = $loc_db->query("SELECT team_id from team_members where user_code=?", array($auth_code))->result_array();
            $team_ids = [];
            if(count($team_members)){
                foreach($team_members as $team_member){
                    $total_villages_count += $loc_db->where('team_id', $team_member['team_id'])->get('surveyor_villages')->num_rows();
                    array_push($team_ids,$team_member['team_id']);
                }
            }
            $survey_completed_count = $loc_db->where_in('team_id', $team_ids)->where('final_report_uploaded', 1)->get('surveyor_villages')->num_rows();

            if(count($supervisor_circles)){
                foreach($supervisor_circles as $supervisor_circle){
                    // $total_villages_count += $this->getVillageCount($supervisor_circle['dist_code'], $supervisor_circle['subdiv_code'], $supervisor_circle['cir_code']);
                    $total_village_area_count += $loc_db->select_sum('apprx_area')->where('dist_code', $supervisor_circle['dist_code'])->where('subdiv_code', $supervisor_circle['subdiv_code'])->where('cir_code', $supervisor_circle['cir_code'])->get('surveyor_villages')->row()->apprx_area;
                    $total_completed_village_area_count += $loc_db->select_sum('area_surveyed')->where('dist_code', $supervisor_circle['dist_code'])->where('subdiv_code', $supervisor_circle['subdiv_code'])->where('cir_code', $supervisor_circle['cir_code'])->get('survey_daily_progress_reports')->row()->area_surveyed;
                }
            }
        }elseif($userType == $surveyorCode){
            $total_villages_count = $loc_db->where('surveyor_code', $auth_code)->get('surveyor_villages')->num_rows();
            $survey_completed_count = $loc_db->where('surveyor_code', $auth_code)->where('final_report_uploaded', 1)->get('surveyor_villages')->num_rows();
            $total_village_area_count = $loc_db->select_sum('apprx_area')->where('surveyor_code', $auth_code)->get('surveyor_villages')->row()->apprx_area;
            $total_completed_village_area_count = $loc_db->select_sum('area_surveyed')->where('surveyor_code', $auth_code)->get('survey_daily_progress_reports')->row()->area_surveyed;
            $total_land_parcel_count = $this->db->select_sum('land_parcel_survey')->where('surveyor_code', $auth_code)->get('survey_daily_progress_reports')->row()->land_parcel_survey;
        }elseif($userType == $gisCode){
            $gis_assistant_circle = $loc_db->where('gisassistant_code', $auth_code)->get('gisassistant_circles')->row();
            $gis_conditions = [
                                    'dist_code' => $gis_assistant_circle->dist_code,
                                    'subdiv_code' => $gis_assistant_circle->subdiv_code,
                                    'cir_code' => $gis_assistant_circle->cir_code
                                ];
            $total_villages_count = $loc_db->where($gis_conditions)->get('surveyor_villages')->num_rows();

            // $survey_completed_count = $loc_db->where($gis_conditions)->where('final_report_uploaded', 1)->get('surveyor_villages')->num_rows();
            $total_village_area_count = $loc_db->select_sum('apprx_area')->where($gis_conditions)->get('surveyor_villages')->row()->apprx_area;
            $total_completed_village_area_count = $loc_db->select_sum('area_surveyed')->where($gis_conditions)->where('user_code', $auth_code)->get('gisassistant_qaqc_reports')->row()->area_surveyed;
            $total_land_parcel_count = $this->db->select_sum('land_parcel_survey')->where($gis_conditions)->where('user_code', $auth_code)->get('gisassistant_qaqc_reports')->row()->land_parcel_survey;
        }
        
        $show_user_counter_section = $show_filter_section = $show_allotment_section = false;
        
        if(in_array($userType, [$surveySuperAdminCode])){
            $show_user_counter_section = true;
            $show_filter_section = true;
            $show_allotment_section = true;
            $enable_report_link = true;
        }

        if($show_user_counter_section){
            $data['supervisor_count'] = $loc_db->where('user_role', $supervisorCode)->where('user_status', 'E')->get('dataentryusers')->num_rows();
            $data['surveyor_count'] = $loc_db->where('user_role', $surveyorCode)->where('user_status', 'E')->get('dataentryusers')->num_rows();
            $data['spmu_count'] = $loc_db->where('user_role', $spmuCode)->where('user_status', 'E')->get('dataentryusers')->num_rows();
            $data['team_count'] = $loc_db->get('teams')->num_rows();
        }

        if($show_allotment_section){
            $supervisor_total_completed_count = $supervisor_total_assigned_count = 0;
            $surveyor_total_completed_count = $surveyor_total_assigned_count = 0;
            $conditions1 = [];
            $conditions2 = [];

            $supervisor_total_assigned_count = $loc_db->where($conditions1)->get('supervisor_circles')->num_rows();
            $surveyor_total_assigned_count = $loc_db->where($conditions2)->get('surveyor_villages')->num_rows();
            $surveyor_total_completed_count = $loc_db->where($conditions2)->where('final_report_uploaded', 1)->get('surveyor_villages')->num_rows();
            
            $data['supervisor_total_completed_count'] = $supervisor_total_completed_count;
            $data['supervisor_total_assigned_count'] = $supervisor_total_assigned_count;
            $data['surveyor_total_completed_count'] = $surveyor_total_completed_count;
            $data['surveyor_total_assigned_count'] = $surveyor_total_assigned_count;
        }

        $data['total_villages'] = $total_villages_count ? $total_villages_count : 0;
        $data['survey_completed_count'] = $survey_completed_count ? $survey_completed_count : 0;
        $data['qc_completed_count'] = $qc_completed_count ? $qc_completed_count : 0;
        $data['draft_map_prepared_count'] = $draft_map_prepared_count ? $draft_map_prepared_count : 0;
        $data['final_map_prepared_count'] = $final_map_prepared_count ? $final_map_prepared_count : 0;
        $data['total_completed_village_area_count'] = $total_completed_village_area_count ? $total_completed_village_area_count : 0;
        $data['total_village_area_count'] = $total_village_area_count ? $total_village_area_count : 0;
        $data['total_land_parcel_count'] = $total_land_parcel_count ? $total_land_parcel_count : 0;
        $data['show_user_counter_section'] = $show_user_counter_section;
        $data['show_filter_section'] = $show_filter_section;
        $data['show_allotment_section'] = $show_allotment_section;
        $data['enable_report_link'] = $enable_report_link;

        if ($this->input->server('REQUEST_METHOD') === 'GET') {
            $this->dbswitch('default');
            if($show_filter_section){
                $data['districts'] = $this->getAllDistricts();
            }
            $data['users'] = $this->UserModel->getSurveyUsers();
            $data['_view'] = 'survey_dashboard/dashboard';
            $this->load->view('layout/layout', $data);
        } elseif ($this->input->server('REQUEST_METHOD') === 'POST') {
            $html = $this->load->view('survey_dashboard/dashboard_counter', $data, TRUE);
            return response_json(['success' => true, 'html' => $html, 'message' => 'Data fetched successfully']);
        }
    }

    public function getSurveyVillageList(){
        $auth_code = $this->session->userdata('usercode');
        $userType = $this->session->userdata('usertype');
        $surveySuperAdminCode = $this->UserModel::$SURVEY_SUPER_ADMIN_CODE;
        $supervisorCode = $this->UserModel::$SUPERVISOR_CODE;
        if (!in_array($userType, [$surveySuperAdminCode, $supervisorCode])) {
            show_404();
        }

        $this->dbswitch('default');
        $loc_db = $this->db;
        $districts_villages = [];
        if($userType == $surveySuperAdminCode){
            $districts = $this->getAllDistricts();
            foreach($districts as $district){
                if($this->checkDatabaseExists($district['dist_code'])){
                    $this->dbswitch($district['dist_code']);
                    $villages = $this->Chithamodel->allVillagedetails($district['dist_code'], null, null, null, null, SURVEY_NC_BTAD_VILLAGE_STATUS);
                    if(count($villages)){
                        $nc_btad_villages = [];
                        foreach($villages as $village){
                            if(isset($village['nc_btad']) && $village['nc_btad'] == SURVEY_NC_BTAD_VILLAGE_STATUS){
                                $condition = [
                                                'dist_code' => $village['dist_code'],
                                                'subdiv_code' => $village['subdiv_code'],
                                                'cir_code' => $village['cir_code'],
                                                'mouza_pargona_code' => $village['mouza_pargona_code'],
                                                'vill_townprt_code' => $village['vill_townprt_code'],
                                            ];
                                $single_village = $village;
                                $single_village['district_name'] = $this->utilityclass->getDistrictName($village['dist_code']);
                                $single_village['subdivision'] = $this->utilityclass->getSubDivName($village['dist_code'], $village['subdiv_code']);
                                $single_village['circle'] = $this->utilityclass->getCircleName($village['dist_code'], $village['subdiv_code'], $village['cir_code']);
                                $single_village['mouza_pargona'] = $this->utilityclass->getMouzaName($village['dist_code'], $village['subdiv_code'], $village['cir_code'], $village['mouza_pargona_code']);
                                $single_village['lot_name'] = $this->utilityclass->getLotLocationName($village['dist_code'], $village['subdiv_code'], $village['cir_code'], $village['mouza_pargona_code'], $village['lot_no']);
                                $single_village['loc_name'] = $village['loc_name'];
                                // $single_village['vill_townprt_code'] = $vill_townprt_code;
                                $single_village['has_survey_started'] = $loc_db->where($condition)->get('survey_daily_progress_reports')->num_rows() == 0 ? false : true;
                                $single_village['has_survey_completed'] = $loc_db->where($condition)->where('final_report_uploaded', 1)->get('surveyor_villages')->num_rows() == 0 ? false : true;
                                $single_village['has_gis_qaqc_initiated'] = $loc_db->where($condition)->where('is_gis_qaqc_completed', 1)->get('survey_daily_progress_reports')->num_rows() == 0 ? false : true;
                                $single_village['has_gis_qaqc_completed'] = $loc_db->where($condition)->where('is_gis_qaqc_completed', 1)->get('surveyor_villages')->num_rows() == 0 ? false : true;
                                $nc_btad_villages[] = $single_village;
                            }
                        }
                        if(count($nc_btad_villages)){
                            $districts_villages[$district['dist_code']] = $nc_btad_villages;
                        }
                    }
                    // $this->dbswitch($district['dist_code']);
                    // $village_count += $this->db->where('vill_townprt_code !=', '00000')->where('nc_btad', SURVEY_NC_BTAD_VILLAGE_STATUS)->get('location')->num_rows();
                }
            }

        }elseif($userType == $supervisorCode){
            $districts_villages = [];
            $team_members = $loc_db->query("SELECT team_id from team_members where user_code=?", array($auth_code))->result_array();
            $team_ids = [];
            if(count($team_members)){
                foreach($team_members as $team_member){
                    array_push($team_ids,$team_member['team_id']);
                }
            }
            $surveyor_villages = $loc_db->where_in('team_id', $team_ids)->get('surveyor_villages')->result_array();

            foreach($surveyor_villages as $village){
                if(!isset($districts_villages[$village['dist_code']])){
                    $districts_villages[$village['dist_code']] = [];
                }
                $condition = [
                                'dist_code' => $village['dist_code'],
                                'subdiv_code' => $village['subdiv_code'],
                                'cir_code' => $village['cir_code'],
                                'mouza_pargona_code' => $village['mouza_pargona_code'],
                                'vill_townprt_code' => $village['vill_townprt_code'],
                            ];
                $this->dbswitch($village['dist_code']);
                $single_village = $village;
                $single_village['district_name'] = $this->utilityclass->getDistrictName($village['dist_code']);
                $single_village['subdivision'] = $this->utilityclass->getSubDivName($village['dist_code'], $village['subdiv_code']);
                $single_village['circle'] = $this->utilityclass->getCircleName($village['dist_code'], $village['subdiv_code'], $village['cir_code']);
                $single_village['mouza_pargona'] = $this->utilityclass->getMouzaName($village['dist_code'], $village['subdiv_code'], $village['cir_code'], $village['mouza_pargona_code']);
                $single_village['lot_name'] = $this->utilityclass->getLotLocationName($village['dist_code'], $village['subdiv_code'], $village['cir_code'], $village['mouza_pargona_code'], $village['lot_no']);
                $single_village['loc_name'] = $this->utilityclass->getVillageName($village['dist_code'], $village['subdiv_code'], $village['cir_code'], $village['mouza_pargona_code'], $village['lot_no'], $village['vill_townprt_code']);
                // $single_village['vill_townprt_code'] = $vill_townprt_code;
                $single_village['has_survey_started'] = $loc_db->where($condition)->get('survey_daily_progress_reports')->num_rows() == 0 ? false : true;
                $single_village['has_survey_completed'] = $loc_db->where($condition)->where('final_report_uploaded', 1)->get('surveyor_villages')->num_rows() == 0 ? false : true;
                $single_village['has_gis_qaqc_initiated'] = $loc_db->where($condition)->where('is_gis_qaqc_completed', 1)->get('survey_daily_progress_reports')->num_rows() == 0 ? false : true;
                $single_village['has_gis_qaqc_completed'] = $loc_db->where($condition)->where('is_gis_qaqc_completed', 1)->get('surveyor_villages')->num_rows() == 0 ? false : true;
                
                $districts_villages[$village['dist_code']][] = $single_village;
            }
        }
        
        $data['districts_villages'] = $districts_villages;
        $data['_view'] = 'survey_dashboard/village_list';
        $this->load->view('layout/layout', $data);
    }

    public function getCompletedVillageList(){
        $auth_code = $this->session->userdata('usercode');
        $userType = $this->session->userdata('usertype');
        $surveySuperAdminCode = $this->UserModel::$SURVEY_SUPER_ADMIN_CODE;
        $supervisorCode = $this->UserModel::$SUPERVISOR_CODE;
        if (!in_array($userType, [$surveySuperAdminCode, $supervisorCode])) {
            show_404();
        }

        $this->dbswitch('default');
        $loc_db = $this->db;
        $conditions = [];
        if($userType == $surveySuperAdminCode){
            $query = "SELECT DISTINCT dist_code FROM surveyor_villages";
        }elseif($userType == $supervisorCode){
            $team_members = $loc_db->query("SELECT team_id from team_members where user_code=?", array($auth_code))->result_array();
            $team_ids = [];
            if(count($team_members)){
                foreach($team_members as $team_member){
                    array_push($team_ids, $team_member['team_id']);
                }
                $team_id_str = implode(',', $team_ids);
            }
            $query = "SELECT DISTINCT dist_code FROM surveyor_villages where team_id in ($team_id_str)";
        }

        $surveyed_village_districts = $loc_db->query($query)->result_array();


        $villages_group_by_dist = [];
        $dist_taken = [];
        if(count($surveyed_village_districts)){
            foreach($surveyed_village_districts as $surveyed_village_district){
                $this->dbswitch($surveyed_village_district['dist_code']);
                if($userType == $surveySuperAdminCode){
                    $survey_completed_village_list = $loc_db->where('dist_code', $surveyed_village_district['dist_code'])->where('final_report_uploaded', 1)->get('surveyor_villages')->result_array();
                }elseif($userType == $supervisorCode){
                    $survey_completed_village_list = $loc_db->where('dist_code', $surveyed_village_district['dist_code'])->where_in('team_id', $team_ids)->where('final_report_uploaded', 1)->get('surveyor_villages')->result_array();
                }
                // $survey_completed_village_list = $loc_db->where('dist_code', $surveyed_village_district['dist_code'])->get('surveyor_villages')->result_array();
                if(count($survey_completed_village_list)){
                    if(!in_array($surveyed_village_district, $dist_taken)){
                        array_push($dist_taken, $surveyed_village_district['dist_code']);
                        $villages_group_by_dist[$surveyed_village_district['dist_code']] = [];
                    }
                    foreach ($survey_completed_village_list as $key => $survey_completed_village) {
                        $survey_completed_village_list[$key]['district_name'] = $this->utilityclass->getDistrictName($survey_completed_village['dist_code']);
                        $survey_completed_village_list[$key]['subdivision'] = $this->utilityclass->getSubDivName($survey_completed_village['dist_code'], $survey_completed_village['subdiv_code']);
                        $survey_completed_village_list[$key]['circle'] = $this->utilityclass->getCircleName($survey_completed_village['dist_code'], $survey_completed_village['subdiv_code'], $survey_completed_village['cir_code']);
                        $survey_completed_village_list[$key]['mouza_pargona'] = $this->utilityclass->getMouzaName($survey_completed_village['dist_code'], $survey_completed_village['subdiv_code'], $survey_completed_village['cir_code'], $survey_completed_village['mouza_pargona_code']);
                        $survey_completed_village_list[$key]['lot_name'] = $this->utilityclass->getLotLocationName($survey_completed_village['dist_code'], $survey_completed_village['subdiv_code'], $survey_completed_village['cir_code'], $survey_completed_village['mouza_pargona_code'], $survey_completed_village['lot_no']);
                        $survey_completed_village_list[$key]['village_name'] = $this->utilityclass->getVillageName($survey_completed_village['dist_code'], $survey_completed_village['subdiv_code'], $survey_completed_village['cir_code'], $survey_completed_village['mouza_pargona_code'], $survey_completed_village['lot_no'], $survey_completed_village['vill_townprt_code']);
                    }

                    $villages_group_by_dist[$surveyed_village_district['dist_code']] = $survey_completed_village_list;
                }
            }
        }

        $data['villages_group_by_dist'] = $villages_group_by_dist;
        $data['_view'] = 'survey_dashboard/completed_village_list';
        $this->load->view('layout/layout', $data);
    }

    private function getAllDistricts(){
        $this->dbswitch('default');
        return $this->db->where_in('dist_code', SURVEY_DISTRICTS)->where('subdiv_code', '00')->get('location')->result_array();
    }

    private function getVillageCount($search_dist_code = NULL, $search_subdiv_code = NULL, $search_cir_code = NULL, $search_mouza_pargona_code = NULL, $search_lot_no = NULL, $search_user = NULL){
        $village_count = 0;
        if(empty($search_dist_code)){
            $districts = $this->getAllDistricts();
            foreach($districts as $district){
                $this->dbswitch($district['dist_code']);
                if($this->checkDatabaseExists($district['dist_code'])){
                    $villages = $this->Chithamodel->allVillagedetails($district['dist_code'], null, null, null, null, SURVEY_NC_BTAD_VILLAGE_STATUS);
                    if(count($villages)){
                        foreach($villages as $village){
                            if(isset($village['nc_btad']) && $village['nc_btad'] == SURVEY_NC_BTAD_VILLAGE_STATUS){
                                $village_count++;
                            }
                        }
                    }
                    // $village_count += $this->db->where('vill_townprt_code !=', '00000')->where('nc_btad', SURVEY_NC_BTAD_VILLAGE_STATUS)->get('location')->num_rows();
                }
            }
        }else{
            $this->dbswitch($search_dist_code);
            $conditions['dist_code'] = $search_dist_code;
            if(!empty($search_subdiv_code)) $conditions['subdiv_code'] = $search_subdiv_code;
            if(!empty($search_cir_code)) $conditions['cir_code'] = $search_cir_code;
            if(!empty($search_mouza_pargona_code)) $conditions['mouza_pargona_code'] = $search_mouza_pargona_code;
            if(!empty($search_lot_no)) $conditions['lot_no'] = $search_lot_no;
            
            $villages = $this->Chithamodel->allVillagedetails($search_dist_code, $search_subdiv_code, $search_cir_code, $search_mouza_pargona_code, $search_lot_no, SURVEY_NC_BTAD_VILLAGE_STATUS);
            $village_count = 0;
            if(count($villages)){
                foreach($villages as $village){
                    if(isset($village['nc_btad']) && $village['nc_btad'] == SURVEY_NC_BTAD_VILLAGE_STATUS){
                        $village_count++;
                    }
                }
            }
            // $village_count = $this->db->where($conditions)->where('vill_townprt_code !=', '00000')->where('nc_btad', SURVEY_NC_BTAD_VILLAGE_STATUS)->get('location')->num_rows();
        }

        return $village_count;
    }

    public function surveyorVillageList($team_id, $surveyor_code){
        $this->dbswitch('default');
        $loc_db = $this->db;
        $surveyor_code = urldecode($surveyor_code);
        $auth_role = $this->session->userdata('usertype');
        $surveyor_role = $this->UserModel::$SURVEYOR_CODE;
        $query1 = "SELECT tm.* from team_members tm join dataentryusers deu on tm.user_code = deu.username where deu.user_role=? and tm.team_id=? and tm.user_code=?";
        if($this->db->query($query1, array($surveyor_role, $team_id, $surveyor_code))->num_rows() == 0){
            show_error('404 Page not found', 404);
        }

        $surveyor_villages = $loc_db->where('surveyor_code', $surveyor_code)->where('team_id', $team_id)->get('surveyor_villages')->result_array();
        if(count($surveyor_villages)){
            foreach($surveyor_villages as $key => $surveyor_village){
                $is_freezed = $loc_db->where('is_freezed', 1)
                                        ->where('surveyor_code', $surveyor_village['surveyor_code'])
                                        ->where('dist_code', $surveyor_village['dist_code'])
                                        ->where('subdiv_code', $surveyor_village['subdiv_code'])
                                        ->where('cir_code', $surveyor_village['cir_code'])
                                        ->where('mouza_pargona_code', $surveyor_village['mouza_pargona_code'])
                                        ->where('lot_no', $surveyor_village['lot_no'])
                                        ->where('vill_townprt_code', $surveyor_village['vill_townprt_code'])
                                        ->get('survey_daily_progress_reports')->row();

                $this->dbswitch($surveyor_village['dist_code']);
                $surveyor_villages[$key]['dist_name'] = $this->utilityclass->getDistrictName($surveyor_village['dist_code']);
                $surveyor_villages[$key]['subdiv_name'] = $this->utilityclass->getSubDivName($surveyor_village['dist_code'], $surveyor_village['subdiv_code']);
                $surveyor_villages[$key]['circle_name'] = $this->utilityclass->getCircleName($surveyor_village['dist_code'], $surveyor_village['subdiv_code'], $surveyor_village['cir_code']);
                $surveyor_villages[$key]['mouza_name'] = $this->utilityclass->getMouzaName($surveyor_village['dist_code'], $surveyor_village['subdiv_code'], $surveyor_village['cir_code'], $surveyor_village['mouza_pargona_code']);
                $surveyor_villages[$key]['lot_name'] = $this->utilityclass->getLotName($surveyor_village['dist_code'], $surveyor_village['subdiv_code'], $surveyor_village['cir_code'], $surveyor_village['mouza_pargona_code'], $surveyor_village['lot_no']);
                $surveyor_villages[$key]['village_name'] = $this->utilityclass->getVillageName($surveyor_village['dist_code'], $surveyor_village['subdiv_code'], $surveyor_village['cir_code'], $surveyor_village['mouza_pargona_code'], $surveyor_village['lot_no'], $surveyor_village['vill_townprt_code']);
                $surveyor_villages[$key]['is_survey_completed'] = $is_freezed ? true : false;
            }
        }

        $data['can_upload_data'] = $auth_role == $surveyor_role ? true : false;
        $data['team'] = $loc_db->where('id', $team_id)->get('teams')->row();
        $data['surveyor_villages'] = $surveyor_villages;
        $data['_view'] = 'survey/surveyor_village_list';
        $this->load->view('layout/layout', $data);
    }

    public function uploadDailyInit(){
        /**
        * Currently surveyor is associated with a single team. 
        * If in future surveyor will be a part of multiple teams then this method will not work
        */
        $surveyor_role = $this->UserModel::$SURVEYOR_CODE;
        $auth_role = $this->session->userdata('usertype');
        $usercode = $this->session->userdata('usercode');
        if($auth_role !== $surveyor_role){
            show_404();
        }
        $this->dbswitch('default');
        $loc_db = $this->db;

        $team_member = $loc_db->where('user_code', $usercode)->get('team_members')->row();
        if(!$team_member){
            show_error('No village list available');
        }
        $team_id = $team_member->team_id;

        return redirect(base_url('index.php/surveyor-village/' . $team_id . '/' . $usercode . '/list'));
    }

    public function uploadDailyProgress($surveyor_village_id){
        ini_set('upload_max_filesize', '-1');
        ini_set('post_max_size', '-1');
        $extension_parts = explode('.', $_FILES['shape_file']['name']);
        $extension = end($extension_parts);
        $extension = strtolower($extension);
        if(!in_array($extension, ['dxf'])){
            return response_json(['success' => false, 'message' => 'Only .dxf can be uploaded']);
        }
        $this->dbswitch('default');
        $report_date = date('Y-m-d');
        $usercode = $this->session->userdata('usercode');
        $surveyor_village = $this->db->where('id', $surveyor_village_id)->where('surveyor_code', $usercode)->get('surveyor_villages')->row();
        if(!$surveyor_village){
            return response_json(['success' => false, 'message' => 'No such village found for this surveyor']);
        }

        $team_member = $this->db->where('team_id', $surveyor_village->team_id)->where('user_code', $surveyor_village->surveyor_code)->get('team_members')->row();
        if(!$team_member){
            return response_json(['success' => false, 'message' => 'Surveyor-Team mapping is not matching']);
        }

        $conditions = [
                        'surveyor_code' => $surveyor_village->surveyor_code,
                        'dist_code' => $surveyor_village->dist_code,
                        'subdiv_code' => $surveyor_village->subdiv_code,
                        'cir_code' => $surveyor_village->cir_code,
                        'mouza_pargona_code' => $surveyor_village->mouza_pargona_code,
                        'lot_no' => $surveyor_village->lot_no,
                        'vill_townprt_code' => $surveyor_village->vill_townprt_code,
                    ];

        $daily_report_ins = $this->db->where('report_date', $report_date)
                                        ->where($conditions)
                                        ->get('survey_daily_progress_reports')
                                        ->row();
        if($daily_report_ins){
            return response_json(['success' => false, 'message' => 'Daily report already created']);
        }

        $is_freezed = $this->db->where('is_freezed', 1)->where($conditions)->get('survey_daily_progress_reports')->row();
        if($is_freezed){
            return response_json(['success' => false, 'message' => "You cannot upload daily progress as this survey is completed."]);
        }

        $error_msg = [];
        $validation = [
                        // [
                        //     'field' => 'shape_file',
                        //     'label' => 'Shape File',
                        //     'rules' => 'required'
                        // ],
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
        $config['allowed_types'] = '*';
        $config['max_size'] = SURVEY_MAX_SIZE;
        $config['file_name'] = 'survey_dly_upld_' . uniqid();
        $this->load->library('upload', $config);
        if($this->upload->do_upload('shape_file'))
        {
            $upload_data = $this->upload->data();
            $file_name = $upload_data['file_name'];
            $file_ext = (explode('.', $upload_data['file_ext']))[1];
            $this->db->trans_begin();
            try{
                $insertData = [
                                'team_id' => $surveyor_village->team_id,
                                'team_member_id' => $team_member->id,
                                'surveyor_code' => $surveyor_village->surveyor_code,
                                'dist_code' => $surveyor_village->dist_code,
                                'subdiv_code' => $surveyor_village->subdiv_code,
                                'cir_code' => $surveyor_village->cir_code,
                                'mouza_pargona_code' => $surveyor_village->mouza_pargona_code,
                                'lot_no' => $surveyor_village->lot_no,
                                'vill_townprt_code' => $surveyor_village->vill_townprt_code,
                                'file_name' => $file_name,
                                'file_ext' => strtolower($file_ext),
                                'created_by' => $usercode,
                                'updated_by' => $usercode,
                                'land_parcel_survey' => $this->input->post('land_parcel_survey'),
                                'area_surveyed' => $this->input->post('area_surveyed'),
                                'report_date' => $report_date,
                            ];
                $this->db->insert('survey_daily_progress_reports', $insertData);
                $inserted_id = $this->db->insert_id();
                $ref_id = $inserted_id . '_dly';

                if($this->db->trans_status() == false){
                    log_message('error', '#ERRUPDLYSRV001: ' .  $this->db->last_query());
                    throw new Exception("ERRUPDLYSRV001: Something went wrong. Please try again later.");
                }

                if(ENABLE_BHUNAKSHA_DXF_MAP_API && SURVEYOR_BHUNAKSHA_DXF_UPLOAD_MAP){
                    $this->db->where('id', $inserted_id)->update('survey_daily_progress_reports', ['bhunaksha_ref_id' => $ref_id]);
                    $apiData = ['refId' => $ref_id, 'filePath' => $file_name];
                    $response = callBhunakshaApiForProcessMap($apiData);
                    if(!$response['success']){
                        $message = 'Something went wrong with the bhunaksha api';
                        if(isset($response['data']->message)){
                            $message = $response['data']->message;
                        }
                        throw new Exception($message);
                    }
                }
                $this->create_daily_progress_logs($inserted_id);
            }catch(Exception $e){
                $path = SURVEYED_FILE_UPLOAD_PATH . $file_name;
                unlink($path);
                $this->db->trans_rollback();
                return response_json(['success' => false, 'message' => $e->getMessage()], 403);
            }

            $this->db->trans_commit();
        }
        else
        {
            return response_json(['success' => false, 'message' => $this->upload->display_errors()]);
        }

        return response_json(['success' => true, 'message' => 'Daily progress saved successfully.']);
    }

    public function updateDailyProgress($survey_daily_progress_report_id){
        ini_set('upload_max_filesize', '-1');
        ini_set('post_max_size', '-1');
        if(isset($_FILES['shape_file']) && $_FILES['shape_file']['name'] != ''){
            $extension_parts = explode('.', $_FILES['shape_file']['name']);
            $extension = end($extension_parts);
            $extension = strtolower($extension);
            if(!in_array($extension, ['dxf'])){
                return response_json(['success' => false, 'message' => 'Only .dxf can be uploaded']);
            }
        }

        $this->dbswitch('default');
        $usercode = $this->session->userdata('usercode');
        $survey_daily_progress_report = $this->db->where('id', $survey_daily_progress_report_id)->get('survey_daily_progress_reports')->row();
        if(!$survey_daily_progress_report){
            return response_json(['success' => false, 'message' => 'No such report found for this surveyor']);
        }

        if($survey_daily_progress_report->is_freezed == 1){
            return response_json(['success' => false, 'message' => "You cannot update daily progress as this survey is completed."]);
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
                            'file_name' => $survey_daily_progress_report->file_name,
                            'file_ext' => $survey_daily_progress_report->file_ext,
                            'land_parcel_survey' => $this->input->post('land_parcel_survey'),
                            'area_surveyed' => $this->input->post('area_surveyed'),
                            'updated_by' => $usercode,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ];
        if(isset($_FILES['shape_file']) && $_FILES['shape_file']['name'] != ''){
            $config['upload_path'] = SURVEYED_FILE_UPLOAD_PATH;
            // $config['allowed_types'] = 'dxf';
            $config['max_size'] = SURVEY_MAX_SIZE;
            $config['allowed_types'] = '*';
            $config['file_name'] = 'survey_dly_upld_' . uniqid();
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
        
        $this->db->trans_begin();
        try{
            $this->db->where('id', $survey_daily_progress_report_id)->update('survey_daily_progress_reports', $updateData);
            $ref_id = $survey_daily_progress_report->bhunaksha_ref_id;

            if($this->db->trans_status() == false){
                log_message('error', '#ERRUPDLYSRV002: ' .  $this->db->last_query());
                throw new Exception("ERRUPDLYSRV002: Something went wrong. Please try again later.");
            }

            if(ENABLE_BHUNAKSHA_DXF_MAP_API && $file_name && SURVEYOR_BHUNAKSHA_DXF_UPLOAD_MAP){
                $apiData = ['refId' => $ref_id, 'filePath' => $file_name];
                $response = callBhunakshaApiForUpdateProcessMap($apiData);
                if(!$response['success']){
                    $message = 'Something went wrong with the bhunaksha api';
                    if(isset($response['data']->message)){
                        $message = $response['data']->message;
                    }
                    throw new Exception($message);
                }
            }
            $this->create_daily_progress_logs($survey_daily_progress_report_id);
        }catch(Exception $e){
            if($file_name){
                $path = SURVEYED_FILE_UPLOAD_PATH . $file_name;
                unlink($path);
            }
            $this->db->trans_rollback();
            return response_json(['success' => false, 'message' => $e->getMessage()], 403);
        }
        $this->db->trans_commit();

        return response_json(['success' => true, 'message' => 'Daily progress updated successfully.']);
    }

    private function create_daily_progress_logs($survey_daily_progress_report_id){
        $survey_daily_progress_report = $this->db->where('id', $survey_daily_progress_report_id)->get('survey_daily_progress_reports')->row();
        $insertData = [
                            'survey_daily_progress_report_id' => $survey_daily_progress_report->id,
                            'team_id' => $survey_daily_progress_report->team_id,
                            'team_member_id' => $survey_daily_progress_report->team_member_id,
                            'surveyor_code' => $survey_daily_progress_report->surveyor_code,
                            'dist_code' => $survey_daily_progress_report->dist_code,
                            'subdiv_code' => $survey_daily_progress_report->subdiv_code,
                            'cir_code' => $survey_daily_progress_report->cir_code,
                            'mouza_pargona_code' => $survey_daily_progress_report->mouza_pargona_code,
                            'lot_no' => $survey_daily_progress_report->lot_no,
                            'vill_townprt_code' => $survey_daily_progress_report->vill_townprt_code,
                            'file_name' => $survey_daily_progress_report->file_name,
                            'file_ext' => $survey_daily_progress_report->file_ext,
                            'created_by' => $survey_daily_progress_report->created_by,
                            'updated_by' => $survey_daily_progress_report->updated_by,
                            'land_parcel_survey' => $survey_daily_progress_report->land_parcel_survey,
                            'area_surveyed' => $survey_daily_progress_report->area_surveyed,
                            'report_date' => $survey_daily_progress_report->report_date,
                        ];
        $this->db->insert('survey_daily_progress_report_logs', $insertData);
    }

    public function getPreviousLogs($surveyor_village_id){
        $this->dbswitch('default');
        $loc_db = $this->db;
        $auth_role = $this->session->userdata('usertype');
        $usercode = $this->session->userdata('usercode');
        $surveyor_role = $this->UserModel::$SURVEYOR_CODE;
        $gis_role = $this->UserModel::$SURVEY_GIS_ASSISTANT_CODE;
        $can_access_daily_progress_edit_btn = $show_gis_qa_qc_btn = false;

        $surveyor_village = $loc_db->where('id', $surveyor_village_id);
        if($auth_role == $surveyor_role){
            $can_access_daily_progress_edit_btn = true;
            $surveyor_village = $surveyor_village->where('surveyor_code', $usercode);
        }
        
        if($auth_role == $gis_role){
            $show_gis_qa_qc_btn = true;
        }

        $surveyor_village = $surveyor_village->get('surveyor_villages')->row();

        if(!$surveyor_village){
            return response_json(['success' => false, 'message' => 'No such village found for this surveyor']);
        }

        $daily_reports = $loc_db->where('surveyor_code', $surveyor_village->surveyor_code)
                                    ->where('dist_code', $surveyor_village->dist_code)
                                    ->where('subdiv_code', $surveyor_village->subdiv_code)
                                    ->where('cir_code', $surveyor_village->cir_code)
                                    ->where('mouza_pargona_code', $surveyor_village->mouza_pargona_code)
                                    ->where('lot_no', $surveyor_village->lot_no)
                                    ->where('vill_townprt_code', $surveyor_village->vill_townprt_code)
                                    ->order_by('id', "desc")
                                    ->get('survey_daily_progress_reports')
                                    ->result_array();

        if(count($daily_reports)){
            foreach($daily_reports as $key => $daily_report){
                $daily_reports[$key]['gis_assistant_qaqc_report'] = $loc_db->where('survey_daily_progress_report_id', $daily_report['id'])->get('gisassistant_qaqc_reports')->row_array();
            }
        }
        
        $can_complete_survey = $auth_role == $surveyor_role ? true : false;
        $can_complete_gis_qaqc = $auth_role == $gis_role ? true : false;
        if(count($daily_reports) && $daily_reports[0]['is_freezed'] == 1){
            $can_complete_survey = false;
        }
        // if(count($daily_reports) && $daily_reports[0]['is_freezed'] == 1){
        //     $can_complete_gis_qaqc = false;
        // }

        $final_upload_data = [];
        if($surveyor_village->final_report_uploaded == 1){
            $final_upload_data = $this->SurveyorVillageFinalReportModel->getFinalDatasBySurevyorVillageId($loc_db, $surveyor_village->id);
        }

        $revert_query = "SELECT svr.*, deu.name, deu.user_role from dataentryusers deu join surveyor_village_reverts svr on svr.created_by=deu.username where svr.surveyor_village_id=?";
        $revert_logs = $loc_db->query($revert_query, array($surveyor_village->id))->result_array();
        
        $data['daily_reports'] = $daily_reports;
        $data['final_upload_data'] = $final_upload_data;
        $data['revert_logs'] = $revert_logs;
        $data['show_action'] = in_array($auth_role, [$surveyor_role, $gis_role]) ? true : false;
        $data['surveyor_village'] = $surveyor_village;
        $data['can_complete_survey'] = $can_complete_survey;
        $data['can_complete_gis_qaqc'] = $can_complete_gis_qaqc;
        $data['can_access_daily_progress_edit_btn'] = $can_access_daily_progress_edit_btn;
        $data['show_gis_qa_qc_btn'] = $show_gis_qa_qc_btn;

        $previous_records_html = $this->load->view('survey/previous_progess', $data, TRUE);
        
        return response_json(['success' => true, 'html' => $previous_records_html, 'message' => 'Previous logs fetched successfully']);
    }

    public function showMap($id, $type){
        $this->dbswitch('default');
        if(!in_array($type, ['dly', 'gis_qaqc_dly', 'fnl'])){
            return response_json(['success' => false, 'message' => 'Type is not matching.']);
        }

        if($type == 'dly'){
            $record = $this->db->where('id', $id)->where('file_ext', 'dxf')->get('survey_daily_progress_reports')->row();
            if(!$record){
                return response_json(['success' => false, 'message' => 'No such record found']);
            }
            if(!$record->bhunaksha_ref_id){
                show_error('No such record found');
                // return response_json(['success' => false, 'message' => 'No such record found']);
            }

            $data = ['refId' => $record->bhunaksha_ref_id];
            $response = callBhunakshaApiForFetchingMapGeo($data);

            if ($this->input->server('REQUEST_METHOD') === 'GET') {
                $data['type'] = $type;
                $data['daily_report'] = $record;
                $data['map_data'] = json_encode($response['data']);
                $data['_view'] = 'survey/show_map';

                $this->load->view('layout/layout', $data);
                return;
            }else{
                echo json_encode($response['data']);
                return;
            }
            // if($response['success']){
            //     return response_json(['success' => true, 'message' => 'Map fetched successfully', 'map_data' => $response['data']]);
            // }

            // return response_json(['success' => false, 'message' => 'Unable to fetch the map']);
        }elseif($type == 'gis_qaqc_dly'){
            $record = $this->db->where('id', $id)->get('gisassistant_qaqc_reports')->row();
            if(!$record){
                return response_json(['success' => false, 'message' => 'No such record found']);
            }

            $data = ['refId' => $record->bhunaksha_ref_id];
            $response = callBhunakshaApiForFetchingMapGeo($data);
        
            if ($this->input->server('REQUEST_METHOD') === 'GET') {
                $data['type'] = $type;
                $data['daily_report'] = $record;
                $data['map_data'] = json_encode($response['data']);
                $data['_view'] = 'survey/show_map';

                $this->load->view('layout/layout', $data);
                return;
            }else{
                echo json_encode($response['data']);
                return;
            }
        }elseif($type == 'fnl'){
            return response_json(['success' => false, 'message' => 'Under development mode']);
        }
    }

    public function completeSurvey($surveyor_village_id){
        $this->dbswitch('default');
        $auth_role = $this->session->userdata('usertype');
        $usercode = $this->session->userdata('usercode');
        $surveyor_role = $this->UserModel::$SURVEYOR_CODE;
        if($surveyor_role != $auth_role){
            return response_json(['success' => false, 'message' => 'You are not authorize to complete the survey']);
        }
        $surveyor_village = $this->db->where('id', $surveyor_village_id)->where('surveyor_code', $usercode)->get('surveyor_villages')->row();

        if(!$surveyor_village){
            return response_json(['success' => false, 'message' => 'No such village found for this surveyor']);
        }
        
        $conditions = [
                        'surveyor_code' => $surveyor_village->surveyor_code,
                        'dist_code' => $surveyor_village->dist_code,
                        'subdiv_code' => $surveyor_village->subdiv_code,
                        'cir_code' => $surveyor_village->cir_code,
                        'mouza_pargona_code' => $surveyor_village->mouza_pargona_code,
                        'lot_no' => $surveyor_village->lot_no,
                        'vill_townprt_code' => $surveyor_village->vill_townprt_code,
                    ];

        $survey_daily_progress_report = $this->db->where($conditions)->where('surveyor_code', $surveyor_village->surveyor_code)->get('survey_daily_progress_reports')->row();
        if(!$survey_daily_progress_report){
            return response_json(['success' => false, 'message' => 'First upload the daily survey report']);
        }

        if($survey_daily_progress_report->is_freezed == 1){
            return response_json(['success' => false, 'message' => 'This survey is already completed.']);
        }

        $this->db->trans_begin();
        try{
            $this->db->where('surveyor_code', $surveyor_village->surveyor_code)
                        ->where($conditions)
                        ->update('survey_daily_progress_reports', ['is_freezed' => 1]);

            $this->db->where('id', $surveyor_village_id)->update('surveyor_villages', ['is_surveyor_completed_survey' => 1]);

            if($this->db->trans_status() == false){
                log_message('error', '#ERRCMSRV001: ' .  $this->db->last_query());
                throw new Exception("ERRCMSRV001: Something went wrong. Please try again later.");
            }
        }catch(Exception $e){
            $this->db->trans_rollback();
            return response_json(['success' => false, 'message' => $e->getMessage()], 403);
        }

        $this->db->trans_commit();
        
        return response_json(['success' => true, 'message' => 'Survey is completed. Now you can upload the final data.']);
    }

    public function finalUpload($surveyor_village_id){
        if(isset($_FILES['final_surveyed_data']) && $_FILES['final_surveyed_data']['name'] != ''){
            $extension_parts = explode('.', $_FILES['final_surveyed_data']['name']);
            $extension = end($extension_parts);
            $extension = strtolower($extension);
            if(!in_array($extension, ['dxf'])){
                return response_json(['success' => false, 'errors' => ['final_surveyed_data' => 'The filetype you are attempting to upload is not allowed.']], 403);
                // return response_json(['success' => false, 'message' => 'Only .dxf can be uploaded']);
            }
        }

        $this->dbswitch('default');
        $usercode = $this->session->userdata('usercode');
        $surveyor_village = $this->db->where('id', $surveyor_village_id)->where('surveyor_code', $usercode)->get('surveyor_villages')->row();
        if(!$surveyor_village){
            return response_json(['success' => false, 'message' => 'No such village found for this surveyor']);
        }

        if($surveyor_village->final_report_uploaded == 1){
            return response_json(['success' => false, 'message' => 'Final report has already been uploaded.']);
        }

        $conditions = [
                        'surveyor_code' => $surveyor_village->surveyor_code,
                        'dist_code' => $surveyor_village->dist_code,
                        'subdiv_code' => $surveyor_village->subdiv_code,
                        'cir_code' => $surveyor_village->cir_code,
                        'mouza_pargona_code' => $surveyor_village->mouza_pargona_code,
                        'lot_no' => $surveyor_village->lot_no,
                        'vill_townprt_code' => $surveyor_village->vill_townprt_code,
                    ];

        $is_freezed = $this->db->where('is_freezed', 1)->where($conditions)->get('survey_daily_progress_reports')->row();
        if(!$is_freezed){
            return response_json(['success' => false, 'message' => "First you have to complete the survey."]);
        }

        $error_messages = [];

        $this->load->library('upload');
        $config['upload_path'] = FINAL_SURVEYED_FILE_UPLOAD_PATH;
        $config['max_size'] = SURVEY_MAX_SIZE;
        // $config['allowed_types'] = 'dxf';
        $config['allowed_types'] = '*';
        $config['file_name'] = 'final_surveyed_data_' . uniqid();
        $this->upload->initialize($config);
        if(!$this->upload->do_upload('final_surveyed_data'))
        {
            // $error_messages[] = strip_tags($this->upload->display_errors());
            $error_message = $this->upload->display_errors('', '');
            $error_messages['final_surveyed_data'] = $error_message;
        }else{
            $upload_data = $this->upload->data();
            $final_surveyed_data_name = $upload_data['file_name'];
            $final_surveyed_data_ext = (explode('.', $upload_data['file_ext']))[1];
        }

        $config1['upload_path'] = FINAL_SURVEYED_FILE_UPLOAD_PATH;
        $config1['max_size'] = SURVEY_MAX_SIZE;
        $config1['allowed_types'] = 'jpg|jpeg|pdf';
        $config1['file_name'] = 'field_survey_completion_report_' . uniqid();
        $this->upload->initialize($config1);
        if(!$this->upload->do_upload('field_survey_completion_report'))
        {
            $error_message = $this->upload->display_errors('', '');
            $error_messages['field_survey_completion_report'] = $error_message;
        }else{
            $upload_data = $this->upload->data();
            $field_survey_completion_report_name = $upload_data['file_name'];
            $field_survey_completion_report_ext = (explode('.', $upload_data['file_ext']))[1];
        }

        if(count($error_messages)){
            return response_json(['success' => false, 'errors' => $error_messages], 403);
        }
        
        $this->db->trans_begin();
        try{
            $insertData = [
                                [
                                    'surveyor_village_id' => $surveyor_village->id,
                                    'file_name' => $final_surveyed_data_name,
                                    'file_ext' => strtolower($final_surveyed_data_ext),
                                    'file_identifier' => 'final_surveyed_data',
                                    'created_by' => $usercode
                                ],
                                [
                                    'surveyor_village_id' => $surveyor_village->id,
                                    'file_name' => $field_survey_completion_report_name,
                                    'file_ext' => strtolower($field_survey_completion_report_ext),
                                    'file_identifier' => 'field_survey_completion_report',
                                    'created_by' => $usercode
                                ],
                            ];
            $this->db->insert_batch('surveyor_village_final_reports', $insertData);

            if($this->db->affected_rows() != 2){
                log_message('error', '#ERRENLUPSRV001: ' .  $this->db->last_query());
                throw new Exception("ERRENLUPSRV001: Something went wrong. Please try again later.");
            }

            $this->db->where('id', $surveyor_village_id)->update('surveyor_villages', ['final_report_uploaded' => 1]);

            if($this->db->trans_status() == false){
                log_message('error', '#ERRENLUPSRV002: ' .  $this->db->last_query());
                throw new Exception("ERRENLUPSRV002: Something went wrong. Please try again later.");
            }
        }catch(Exception $e){
            $path = FINAL_SURVEYED_FILE_UPLOAD_PATH . $field_survey_completion_report_name;
            unlink($path);
            $path = FINAL_SURVEYED_FILE_UPLOAD_PATH . $final_surveyed_data_name;
            unlink($path);

            $this->db->trans_rollback();
            return response_json(['success' => false, 'message' => $e->getMessage()], 403);
        }

        $this->db->trans_commit();

        return response_json(['success' => true, 'message' => 'Final data uploaded successfully.']);
    }

    public function subdivisiondetails()
    {
        $data = [];
        $dist_code = $this->input->post('dist_code');
        $this->dbswitch($dist_code);
        $this->session->set_userdata('dist_code', $dist_code);
        // $subdiv_code = $this->session->userdata('subdiv_code');
        $formdata = $this->Chithamodel->subdivisiondetails($dist_code);
        return response_json(['success' => true, 'data' => $formdata]);
    }

    public function circledetails()
    {
        $dist_code = $this->input->post('dist_code');
        $this->dbswitch($dist_code);
        $subdiv = $this->input->post('subdiv_code');
        // $cir_code = $this->session->userdata('cir_code');
        $this->session->set_userdata('subdiv_code', $subdiv);
        $formdata = $this->Chithamodel->circledetails($dist_code, $subdiv);
        return response_json(['success' => true, 'data' => $formdata]);
    }

    public function mouzadetails()
    {
        $dist_code = $this->input->post('dist_code');
        $this->dbswitch($dist_code);
        $subdiv = $this->input->post('subdiv_code');
        $cir = $this->input->post('cir_code');
        $this->session->set_userdata('cir_code', $cir);
        $formdata = $this->Chithamodel->mouzadetails($dist_code, $subdiv, $cir);
        return response_json(['success' => true, 'data' => $formdata]);
    }

    public function lotdetails()
    {
        $dist_code = $this->input->post('dist_code');
        $this->dbswitch($dist_code);
        $subdiv = $this->input->post('subdiv_code');
        $cir = $this->input->post('cir_code');
        $mza = $this->input->post('mouza_pargona_code');
        $this->session->set_userdata('mouza_pargona_code', $mza);
        $formdata = $this->Chithamodel->lotdetails($dist_code, $subdiv, $cir, $mza);
        return response_json(['success' => true, 'data' => $formdata]);
    }

    public function villagedetails()
    {
        // $this->dataswitch();
        // $data = [];
        $dist_code = $this->input->post('dist_code');
        $this->dbswitch($dist_code);
        $subdiv = $this->input->post('subdiv_code');
        $cir = $this->input->post('cir_code');
        $mza = $this->input->post('mouza_pargona_code');
        $lot = $this->input->post('lot_no');
        $this->session->set_userdata('lot_no', $lot);
        $villages = $this->Chithamodel->villagedetails($dist_code, $subdiv, $cir, $mza, $lot);
        // foreach ($villages as $village) {
        //     // $data['test'][] = $value;
        //     $vill_townprt_code = $village['vill_townprt_code'];
        //     $is_surveyor_village_exists = $this->db->query("select * from surveyor_villages where dist_code='$dis' and subdiv_code='$subdiv' and mouza_pargona_code='$mza' and lot_no='$lot' and vill_townprt_code='$vill_townprt_code' and team_id='$team_id' and surveyor_code='$surveyor_code'")->row();
        //     $data_single = [];
        //     $data_single['district_name'] = $this->utilityclass->getDistrictName($village['dist_code']);
        //     $data_single['subdivision'] = $this->utilityclass->getSubDivName($village['dist_code'], $village['subdiv_code']);
        //     $data_single['circle'] = $this->utilityclass->getCircleName($village['dist_code'], $village['subdiv_code'], $village['cir_code']);
        //     $data_single['mouza_pargona'] = $this->utilityclass->getMouzaName($village['dist_code'], $village['subdiv_code'], $village['cir_code'], $village['mouza_pargona_code']);
        //     $data_single['lot_name'] = $this->utilityclass->getLotLocationName($village['dist_code'], $village['subdiv_code'], $village['cir_code'], $village['mouza_pargona_code'], $village['lot_no']);
        //     $data_single['loc_name'] = $village['loc_name'];
        //     $data_single['vill_townprt_code'] = $vill_townprt_code;
        //     if ($is_surveyor_village_exists) {
        //         $data_single['surveyor_village'] = $vill_townprt_code;
        //         $data_single['apprx_area'] = $is_surveyor_village_exists->apprx_area;
        //     } else {
        //         $data_single['surveyor_village'] = '';
        //         $data_single['apprx_area'] = 0;
        //     }
        //     $data[] = $data_single;
        // }
        return response_json(['success' => true, 'data' => $villages]);
    }
}
