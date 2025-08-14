<?php

include APPPATH . '/libraries/CommonTrait.php';
class NcVillageSkController extends CI_Controller
{
    use CommonTrait;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('CommonModel');
        $this->load->model('UtilsModel');
        $this->load->model('DagReportModel');
        $this->load->model('NcVillageModel');
        if ($this->session->userdata('usertype') != 5) {
            show_error('<svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><g fill="none" stroke="#FF0000" stroke-linecap="round" stroke-width="2"><path d="M12 9v5m0 3.5v.5"/><path stroke-linejoin="round" d="M2.232 19.016L10.35 3.052c.713-1.403 2.59-1.403 3.302 0l8.117 15.964C22.45 20.36 21.544 22 20.116 22H3.883c-1.427 0-2.334-1.64-1.65-2.984Z"/></g></svg> <p>Unauthorized access</p>', "403");
        }
    }
    public function dashboard()
    {

        $this->dbswitch();
        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $user_code = $this->session->userdata('user_code');

        //FORWARDED FROM LM COUNT
        // $url = API_LINK_ILRMS . "index.php/nc_village_v2/NcCommonController/apiGetMapsCount";
        $url = API_LINK_ILRMS . "index.php/nc_village_v2/NcCommonController/apiGetMapsCountByProceedings";
        $method = 'POST';
        $output = callIlrmsApi($url, $method, ['d' => $dist_code, 's' => $subdiv_code, 'c' => $cir_code, 'logedin_user_code' => $user_code, 'proceeding_types' => [1, 0]]);
        
        $forwarded_sk_count = $reverted_count = 0;
        if($output && !empty($output->data)){
            $forwarded_sk_count = $output->data->proceeding_1;
            $reverted_count = $output->data->proceeding_0;
            $processed_case_count = $output->data->processed_case_count;
        }
        $data['fowarded_sk_count'] = $forwarded_sk_count;
        $data['processed_case_count'] = $processed_case_count;

        // $data['fowarded_sk_count'] = $this->db->query("select count(*) as c from nc_villages where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and status='S'")->row()->c;
        //REVERTED FROM CO COUNT
        // $data['reverted'] = $this->db->query("select count(*) as c from nc_villages where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and status='H' and pre_user='CO' and cu_user='SK'")->row()->c;
        $data['reverted'] = $reverted_count;

        //co notifications count

		$url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/apiGetNotifications";
		$method = 'POST';
        $dist_code = (string)$this->session->userdata('dist_code');
        $api_params = ['dist_code' => $dist_code];
		$notifications = callIlrmsApi2($url, $method, $api_params);
		$data['notifications_count'] = $notifications ? count($notifications->notifications) : 'N/A';

        $data['_view'] = 'nc_village_v2/sk/nc_village_sk_dashboard';
        $this->load->view('layout/layout', $data);
    }

    public function showVillages()
    {
        $this->dbswitch();
        $user_code = $this->session->userdata('user_code');
        $dist_code = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $data['locations'] = $this->CommonModel->getLocations($dist_code, $subdiv_code, $cir_code);
        $data['mouzas'] = $this->db->select('loc_name,dist_code,subdiv_code,cir_code,mouza_pargona_code')
            ->where(array('dist_code' => $dist_code, 'subdiv_code' => $subdiv_code, 'cir_code' => $cir_code, 'mouza_pargona_code !=' => '00', 'lot_no' => '00'))
            ->get('location')->result_array();

        $url = API_LINK_ILRMS . "index.php/nc_village_v2/NcCommonController/apiGetMapsCount";
        $method = 'POST';
        $output = callIlrmsApi($url, $method, ['d' => $dist_code, 's' => $subdiv_code, 'c' => $cir_code, 'logedin_user_code' => $user_code, 'proceeding_type' => 1]);
        
        $data['villages'] = [];
        if($output && !empty($output->data)){
            $this->dataswitch();
            $query = "select l.loc_name,ncv.lm_verified_at,ncv.status,ncv.application_no,ncv.lm_note,ncv.sk_verified,ncv.sk_note, ncv.id as nc_village_id from nc_villages ncv join location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code
                    and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and ncv.vill_townprt_code = l.vill_townprt_code";

            $query = $query . " where ncv.dist_code='".$dist_code."' and ncv.subdiv_code='".$subdiv_code."' and ncv.cir_code='".$cir_code."' and ncv.status ='S'";

            $villages = $this->db->query($query)->result();
            if(count((array) $villages)){
                foreach($villages as $key => $village){
                    $merge_village_requests = $this->db->where('nc_village_id', $village->nc_village_id)->get('merge_village_requests')->result_array();
                    $merge_village_name_arr = [];
                    if(count($merge_village_requests)){
                        foreach($merge_village_requests as $key1 => $merge_village_request){
                            $vill_loc = $this->CommonModel->getLocations($merge_village_request['request_dist_code'], $merge_village_request['request_subdiv_code'], $merge_village_request['request_cir_code'], $merge_village_request['request_mouza_pargona_code'], $merge_village_request['request_lot_no'], $merge_village_request['request_vill_townprt_code']);
                            array_push($merge_village_name_arr, $vill_loc['village']['loc_name']);
                            $merge_village_requests[$key1]['village_name'] = $vill_loc['village']['loc_name'];
                            $merge_village_requests[$key1]['vill_loc'] = $vill_loc;
                        }
                    }
                    $villages[$key]->merge_village_names = implode(', ', $merge_village_name_arr);
                    $villages[$key]->merge_village_requests = $merge_village_requests;
                }
            }
            $data['villages'] = $villages;
        }

        $data['_view'] = 'nc_village_v2/sk/villages_s';
        $this->load->view('layout/layout', $data);
    }

    /**Filter Villages */
    public function getVillagesS()
    {
        $user_code = $this->session->userdata('user_code');
        $dist_code = $this->input->post('dist_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $filter = $this->input->post('filter');

        $url = API_LINK_ILRMS . "index.php/nc_village_v2/NcCommonController/apiGetMapsCount";
        $method = 'POST';
        $output = callIlrmsApi($url, $method, ['d' => $dist_code, 's' => $subdiv_code, 'c' => $cir_code, 'logedin_user_code' => $user_code]);
        
        $result = [];
        if($output && !empty($output->data)){
            $this->dataswitch();
            $query = "select l.loc_name,ncv.lm_verified_at,ncv.status,ncv.application_no,ncv.lm_note,ncv.sk_verified,ncv.sk_note, ncv.id as nc_village_id from nc_villages ncv join location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code
                    and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and ncv.vill_townprt_code = l.vill_townprt_code";

            $query = $query . " where ncv.dist_code='$dist_code' and ncv.subdiv_code='$subdiv_code' and ncv.cir_code='$cir_code'";
            if ($mouza_pargona_code) {
                $query = $query . " and ncv.mouza_pargona_code = '$mouza_pargona_code'";
            }
            if ($lot_no) {
                $query = $query . " and ncv.lot_no = '$lot_no'";
            }
            if ($filter == 'pending') {
                $query = $query . " and ncv.status ='S'";
            }
            if ($filter == 'sk_verified') {
                $query = $query . " and ncv.sk_verified = 'Y'";
            }
            if ($filter == 'sk_reverted') {
                $query = $query . " and ncv.status = 'U'";
            }

            $query = $query . " and ncv.app_version = 'V2'";

            $result = $this->db->query($query)->result();

            if(count((array) $result)){
                foreach($result as $key => $village){
                    $merge_village_requests = $this->db->where('nc_village_id', $village->nc_village_id)->get('merge_village_requests')->result_array();
                    $merge_village_name_arr = [];

                    if(count($merge_village_requests)){
                        foreach($merge_village_requests as $key1 => $merge_village_request){
                            $vill_loc = $this->CommonModel->getLocations($merge_village_request['request_dist_code'], $merge_village_request['request_subdiv_code'], $merge_village_request['request_cir_code'], $merge_village_request['request_mouza_pargona_code'], $merge_village_request['request_lot_no'], $merge_village_request['request_vill_townprt_code']);
                            array_push($merge_village_name_arr, $vill_loc['village']['loc_name']);
                            $merge_village_requests[$key1]['village_name'] = $vill_loc['village']['loc_name'];
                            $merge_village_requests[$key1]['vill_loc'] = $vill_loc;
                        }
                    }
                    $result[$key]->merge_village_names = implode(', ', $merge_village_name_arr);
                    $result[$key]->merge_village_requests = $merge_village_requests;
                }
            }
        }

        echo json_encode($result);
    }

    /**Get dags start */
    public function showDags($proceeding_type = 1)
    {
        $this->dbswitch();
        $user_code = $this->session->userdata('user_code');
        $application_no = $_GET['application_no'];
        $dist_code = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');

        $nc_village = $this->db->query("select * from nc_villages where
		application_no='$application_no' and dist_code='$dist_code' and
		subdiv_code='$subdiv_code' and cir_code='$cir_code'")->row();
        if (!$nc_village) {
            show_404();
            return;
        }
        $data['locations'] = $this->CommonModel->getLocations($dist_code, $subdiv_code, $cir_code, $nc_village->mouza_pargona_code, $nc_village->lot_no, $nc_village->vill_townprt_code);
        $q3 = "select count(*) as count from nc_village_dags where dist_code=? AND subdiv_code=? AND cir_code=?
        	AND mouza_Pargona_code=? AND lot_No=? AND vill_townprt_code=? and sk_verified IS NULL";
        $check_unverified_dag = $this->db->query($q3, array(
            $dist_code, $subdiv_code, $cir_code,
            $nc_village->mouza_pargona_code, $nc_village->lot_no, $nc_village->vill_townprt_code,
        ))->row()->count;

        $data['verified'] = 'N';
        $data['base'] = $this->config->item('base_url');
        if ($check_unverified_dag == 0) {
            $data['verified'] = 'Y';
        }
        $data['nc_village'] = $nc_village;

        $data['d'] = $nc_village->dist_code;
        $data['s'] = $nc_village->subdiv_code;
        $data['c'] = $nc_village->cir_code;
        $data['m'] = $nc_village->mouza_pargona_code;
        $data['l'] = $nc_village->lot_no;
        $data['v'] = $nc_village->vill_townprt_code;
        $data['v_uuid'] = $nc_village->uuid;
        $data['logedin_user_code'] = $user_code;
        $data['proceeding_status'] = $proceeding_type;

        $url = API_LINK_ILRMS . "index.php/nc_village_v2/NcCommonController/apiGetMap";

        $method = 'POST';
        $output = callIlrmsApi($url, $method, $data);
        $data['maps'] = array();
        if(!empty($output->data)){
            $data['maps'] = $output->data;
        }else{
            return redirect(base_url('index.php/nc_village_v2/NcVillageSkController/dashboard'));
        }

        $merge_village_requests = $this->db->where('nc_village_id', $nc_village->id)->get('merge_village_requests')->result_array();
        if(count($merge_village_requests)){
            foreach($merge_village_requests as $key => $merge_village_request){
                $merge_village_requests[$key]['vill_loc'] = $vill_loc = $this->CommonModel->getLocations($merge_village_request['request_dist_code'], $merge_village_request['request_subdiv_code'], $merge_village_request['request_cir_code'], $merge_village_request['request_mouza_pargona_code'], $merge_village_request['request_lot_no'], $merge_village_request['request_vill_townprt_code']);
            }
        }
        $data['merge_village_requests'] = $merge_village_requests;
        $data['all_cos'] = getAllCO($dist_code, $subdiv_code, $cir_code);
        $data['all_lms'] = getAllLm($dist_code, $subdiv_code, $cir_code, $nc_village->mouza_pargona_code, $nc_village->lot_no);
        
        $data['_view'] = 'nc_village_v2/sk/village_s_dags';
        $this->load->view('layout/layout', $data);
    }

    public function getDags()
    {
        $this->dbswitch();
        $this->form_validation->set_rules('application_no', 'application no', 'trim|required');

        if ($this->form_validation->run() == false) {
            echo json_encode(array('errors' => validation_errors(), 'st' => 0));
            return;
        }
        $application_no = $this->input->post('application_no');
        $nc_village = $this->db->query("select * from nc_villages where application_no='$application_no'")->row();
        $lm = $this->db->query("select lm_code.lm_name from lm_code where
		dist_code='$nc_village->dist_code' and subdiv_code='$nc_village->subdiv_code' and cir_code='$nc_village->cir_code'
		and mouza_pargona_code='$nc_village->mouza_pargona_code' and lot_no='$nc_village->lot_no'
		and lm_code='$nc_village->lm_code'")->row();
        $dags = $this->CommonModel->getNcVillageDags($application_no);
        foreach ($dags as $dag) {
            $dag->occupiers = $this->DagReportModel->occupierNames($dag->dist_code, $dag->subdiv_code, $dag->cir_code, $dag->mouza_pargona_code, $dag->lot_no, $dag->vill_townprt_code, $dag->dag_no);
        }
        echo json_encode(array('dags' => $dags, 'lm_name' => $lm ? $lm->lm_name : ''));

        return;
    }

    /**Get dags end */

    /** View Map */
    public function viewUploadedMap()
    {
        if ($this->session->userdata('usertype') != 5) {
            show_error('<svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><g fill="none" stroke="#FF0000" stroke-linecap="round" stroke-width="2"><path d="M12 9v5m0 3.5v.5"/><path stroke-linejoin="round" d="M2.232 19.016L10.35 3.052c.713-1.403 2.59-1.403 3.302 0l8.117 15.964C22.45 20.36 21.544 22 20.116 22H3.883c-1.427 0-2.334-1.64-1.65-2.984Z"/></g></svg> <p>Unauthorized access</p>', "403");
        }
        $id = $data['id'] = $_GET['id'];
        $dist_code = $data['d'] = $this->session->userdata('dist_code');

        $url = API_LINK_ILRMS . "index.php/nc_village_v2/NcCommonController/apiGetMapBase64";

        $method = 'POST';
        $output = callIlrmsApi($url, $method, $data);
        if ($output->data) {
            if ($output->data->base64 != null) {
                header("Content-type: " . $output->data->mime);
                $data = $output->data->base64;
                echo base64_decode($data);
                die;
            } else {
                echo "Map Not Found..!";
                return;
            }
        }
        echo "Map Not Found..!";
    }

    /** SK verify draft chitha */
    public function verifyDraftChitha()
    {
        $this->dbswitch();

        $this->form_validation->set_rules('application_no', 'application_no', 'trim|required');

        if ($this->form_validation->run() == false) {
            echo json_encode(array('errors' => validation_errors(), 'st' => 0));
            return;
        }
        
        $application_no = $this->UtilsModel->cleanPattern($this->input->post('application_no'));
        $nc_village = $this->db->query("SELECT * FROM nc_villages WHERE application_no='".$application_no."'")->row();

        $q = "select * from nc_village_dags where application_no=? ";
        $nc_village_dags = $this->db->query($q, array($application_no))->result();

        $this->db->trans_begin();
        foreach ($nc_village_dags as $dag) {
            $this->db->where('application_no', $application_no)
                ->where('dag_no', $dag->dag_no)
                ->where('patta_type_code', $dag->patta_type_code)
                ->where('patta_no', $dag->patta_no)
                ->update('nc_village_dags', array('sk_verified' => 'Y', 'updated_at' => date('Y-m-d H:i:s'), 'sk_verified_at' => date('Y-m-d H:i:s')));

            if ($this->db->affected_rows() == 0) {
                $this->db->trans_rollback();
                log_message("error", 'NC_Village_Dag_SK_Update: ' . json_encode('#NC00SK24 Unable to Update data.'));
                echo array(
                    'application_no' => $dag->application_no,
                    'error' => true,
                    'msg' => '#NC00SK24 Unable to update data.',
                );
            }
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            log_message("error", 'NC_Village_or_dag_SK_Update: ' . json_encode('#NC00SK20 Unable to update data.'));
            echo json_encode(array(
                'application_no' => $nc_village_dags[0]->application_no,
                'error' => true,
                'msg' => '#NC00SK20 Unable to update data.',
            ));
        } else {
            $sk_user = getSK($nc_village->dist_code, $this->session->userdata('user_code'));
            $sk_name = 'SK';
            if(count($sk_user)){
                $sk_name = $sk_user['name'];
            }
            
            $url = API_LINK_ILRMS . "index.php/nc_village_v2/NcCommonController/insertNcVillageDetails";
            $method = 'POST';
            $update_data = [ 
                                'proccess_type' => 'VERIFY_DRAFT_CHITHA', 
                                'dist_code' => $nc_village->dist_code,
                                'subdiv_code' => $nc_village->subdiv_code,
                                'cir_code' => $nc_village->cir_code,
                                'mouza_pargona_code' => $nc_village->mouza_pargona_code,
                                'lot_no' => $nc_village->lot_no,
                                'vill_townprt_code' => $nc_village->vill_townprt_code,
                                'uuid' => $nc_village->uuid,
                                'only_for_log' => 'Y', 
                                'pre_user' => $this->session->userdata('user_code'), 
                                'cur_user' => $this->session->userdata('user_code'), 
                                'pre_user_dig' => 'SK', 
                                'cur_user_dig' => 'SK', 
                                'remark' => $sk_name . ' verified draft chitha'
                            ];

            $output = $this->NcVillageModel->callApiV2($url, $method, $update_data);
            $response = $output ? json_decode($output, true) : [];
            if($response['status'] != 1){
                $this->db->trans_rollback();
                log_message("error", 'NC_Village_LM_Save: ' . json_encode('#NCV2L0010 Unable to insert data.'));
                return array(
                    'application_no' => $application_no['case_no'],
                    'error' => true,
                    'msg' => '#NCV2L0010 Unable to insert data.',
                );
            }
            
            $this->db->trans_commit();
            echo json_encode(array(
                'application_no' => $nc_village_dags[0]->application_no,
                'error' => false,
                'msg' => null,
            ));
        }
    }

    
    /** reverted village from CO */
    public function revertedVillages()
    {
        $this->dbswitch();
        $data['_view'] = 'nc_village_v2/sk/nc_villages_reverted';
        $this->load->view('layout/layout', $data);
    }

    /** view revert village from CO */
    public function getVillagesH()
    {
        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $user_code = $this->session->userdata('user_code');

        $url = API_LINK_ILRMS . "index.php/nc_village_v2/NcCommonController/apiGetMapsCount";
        $method = 'POST';
        $output = callIlrmsApi($url, $method, ['d' => $dist_code, 's' => $subdiv_code, 'c' => $cir_code, 'logedin_user_code' => $user_code, 'proceeding_type' => 0]);
        
        $result = [];
        if($output && !empty($output->data)){
            $this->dataswitch();
            $query = "select l.loc_name,ncv.lm_verified_at,ncv.status,ncv.application_no,ncv.lm_note,ncv.co_note from nc_villages ncv join location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code
                    and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and ncv.vill_townprt_code = l.vill_townprt_code";

            $query = $query . " where ncv.dist_code='$dist_code' and ncv.subdiv_code='$subdiv_code' and ncv.cir_code='$cir_code' and ncv.status='H' and ncv.pre_user='CO' and ncv.cu_user='SK' and ncv.app_version = 'V2'";

            $result = $this->db->query($query)->result();
        }


        echo json_encode($result);
    }

        /** SK revert back to LM */
    public function revertVillage()
    {
        $application_no = $this->input->post('application_no');
        $user_code = $this->session->userdata('user_code');
        $remark = $this->UtilsModel->cleanPattern($this->input->post('remark'));
        $revert_to_user = $this->UtilsModel->cleanPattern($this->input->post('user'));

        $this->form_validation->set_rules('application_no', 'Application NO', 'trim|required');
        $this->form_validation->set_rules('remark', 'Remark', 'trim|required');
        $this->form_validation->set_rules('user', 'LRA', 'trim|required');
        if ($this->form_validation->run() == false) {
            echo json_encode(array('errors' => validation_errors(), 'st' => 0));
            return;
        }
        $this->dataswitch();
        $this->db->where('application_no', $application_no)
            ->update(
                'nc_villages',
                [
                    'pre_user' => 'SK',
                    'cu_user' => 'LM',
                    'sk_user_code' => $user_code,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'sk_note' => trim($remark),
                    'status' => 'U',
                    'lm_verified' => 'N',
                    'sk_verified' => null,
                    'sk_verified_at' => null,
                ]
            );

        $this->db->where('application_no', $application_no)
            ->update(
                'nc_village_dags',
                [
                    'updated_at' => date('Y-m-d H:i:s'),
                    'lm_verified' => null,
                    'lm_verified_at' => null,
                    'sk_verified' => null,
                    'sk_verified_at' => null,
                ]
            );

        if ($this->db->affected_rows() > 0) {
            $proceeding_id_max = $this->db->query("select max(proceeding_id) as id from settlement_proceeding where case_no = '$application_no'")->row()->id;
            $insPetProceed = array(
                'case_no' => $application_no,
                'proceeding_id' => !$proceeding_id_max ? 1 : (int) $proceeding_id_max + 1,
                'date_of_hearing' => date('Y-m-d h:i:s'),
                'next_date_of_hearing' => date('Y-m-d h:i:s'),
                'note_on_order' => $remark,
                'status' => 'U',
                'user_code' => $this->session->userdata('user_code'),
                'date_entry' => date('Y-m-d h:i:s'),
                'operation' => 'E',
                'ip' => $_SERVER['REMOTE_ADDR'],
                'office_from' => 'SK',
                'office_to' => 'LM',
                'task' => 'Village Reverted by SK',
            );
            $this->db->insert('settlement_proceeding', $insPetProceed);

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                log_message("error", 'NC_Village_SK_Update: ' . json_encode('#NC00SK06 Unable to update data.'));

                echo json_encode(array(
                    'submitted' => 'N',
                    'msg' => '#NC00SK06 Transactions Failed.',
                ));
                return;
            } else {
                $nc_village = $this->db->where('application_no', $application_no)->get('nc_villages')->row();
                $url = API_LINK_ILRMS . "index.php/nc_village_v2/NcCommonController/insertNcVillageDetails";
                $method = 'POST';
                $update_data = [ 
                                    'proccess_type' => 'REVERT', 
                                    'dist_code' => $nc_village->dist_code,
                                    'subdiv_code' => $nc_village->subdiv_code,
                                    'cir_code' => $nc_village->cir_code,
                                    'mouza_pargona_code' => $nc_village->mouza_pargona_code,
                                    'lot_no' => $nc_village->lot_no,
                                    'vill_townprt_code' => $nc_village->vill_townprt_code,
                                    'uuid' => $nc_village->uuid,
                                    'pre_user' => $this->session->userdata('user_code'), 
                                    'cur_user' => $revert_to_user, 
                                    'pre_user_dig' => 'SK', 
                                    'cur_user_dig' => 'LM', 
                                    'remark' => $remark,
                                    'application_no' => $nc_village->application_no,
                                    'proceeding_type' => 0,
                                ];

                $output = $this->NcVillageModel->callApiV2($url, $method, $update_data);
                $response = $output ? json_decode($output, true) : [];

                if(!count($response) || $response['status'] != 1){
                    $this->db->trans_rollback();
                    log_message("error", '#NCV200022: Unable to update log');

                    echo json_encode(array(
                        'submitted' => 'N',
                        'msg' => '#NCV200022 => Unable to update log',
                    ));
                    return;
                }

                $this->db->trans_commit();
                echo json_encode(array(
                    'submitted' => 'Y',
                    'msg' => 'Successfully Submitted, Reverted to LRA.',
                    'nc_village' => $nc_village = $this->db->query("select * from nc_villages where application_no='$application_no'")->row(),
                ));
                return;
            }
        } else {
            log_message("error", 'NC_Village_SK_Revert: ' . json_encode('#NC00SK05 Unable to Revert Back.' . $this->db->last_query()));
            echo json_encode(array(
                'submitted' => 'N',
                'msg' => '#NC00SK05 Unable to Revert Back.',
            ));
            return;
        }
    }

    /** SK final Verification */
    public function certifyVillage()
    {
        $forward_to_user = $this->input->post('forward_to_user');
        $application_no = $this->input->post('application_no');
        $user_code = $this->session->userdata('user_code');
        $sk_remark = $this->UtilsModel->cleanPattern($this->input->post('remark'));

        $this->form_validation->set_rules('application_no', 'Application NO', 'trim|required');
        if ($this->form_validation->run() == false) {
            echo json_encode(array('errors' => validation_errors(), 'st' => 0));
            return;
        }

        $this->dataswitch();
        $dag_verify_check = $this->checkVerifiedDags(
            $application_no
        );

        if ($dag_verify_check['flag'] != 'Y') {
            echo json_encode(array(
                'submitted' => 'N',
                'msg' => $dag_verify_check['msg'],
            ));
            return;
        }

        $this->db->trans_begin();
        $this->db->where('application_no', $application_no)
            ->update(
                'nc_villages',
                [
                    'pre_user' => 'SK',
                    'cu_user' => 'CO',
                    'sk_user_code' => $user_code,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'sk_verified' => 'Y',
                    'sk_verified_at' => date('Y-m-d H:i:s'),
                    'sk_note' => trim($sk_remark),
                    'status' => 'T',
                    'co_certification' => null,
                ]
            );

        if ($this->db->affected_rows() > 0) {
            $proceeding_id_max = $this->db->query("select max(proceeding_id) as id from settlement_proceeding where case_no = '$application_no'")->row()->id;

            $insPetProceed = array(
                'case_no' => $application_no,
                'proceeding_id' => !$proceeding_id_max ? 1 : (int) $proceeding_id_max + 1,
                'date_of_hearing' => date('Y-m-d h:i:s'),
                'next_date_of_hearing' => date('Y-m-d h:i:s'),
                'note_on_order' => trim($sk_remark),
                'status' => 'T',
                'user_code' => $this->session->userdata('user_code'),
                'date_entry' => date('Y-m-d h:i:s'),
                'operation' => 'E',
                'ip' => $_SERVER['REMOTE_ADDR'],
                'office_from' => 'SK',
                'office_to' => 'CO',
                'task' => 'Village Verified by SK',
            );

            $this->db->insert('settlement_proceeding', $insPetProceed);

            $nc_village = $this->db->query("SELECT * FROM nc_villages WHERE application_no='".$application_no."'")->row();
            $url = API_LINK_ILRMS . "index.php/nc_village_v2/NcCommonController/insertNcVillageDetails";
            $method = 'POST';
            $update_data = [ 
                                'proccess_type' => 'FORWARD', 
                                'dist_code' => $nc_village->dist_code,
                                'subdiv_code' => $nc_village->subdiv_code,
                                'cir_code' => $nc_village->cir_code,
                                'mouza_pargona_code' => $nc_village->mouza_pargona_code,
                                'lot_no' => $nc_village->lot_no,
                                'vill_townprt_code' => $nc_village->vill_townprt_code,
                                'uuid' => $nc_village->uuid,
                                'pre_user' => $this->session->userdata('user_code'), 
                                'cur_user' => $forward_to_user, 
                                'pre_user_dig' => 'SK', 
                                'cur_user_dig' => 'CO', 
                                'remark' => trim($sk_remark),
                                'application_no' => $nc_village->application_no,
                                'proceeding_type' => 2,
                            ];

            $output = $this->NcVillageModel->callApiV2($url, $method, $update_data);
            $response = $output ? json_decode($output, true) : [];

            if ($this->db->trans_status() === false && $response['status'] != '1') {
                $this->db->trans_rollback();
                log_message("error", 'NC_Village_SK_Update: ' . json_encode('#NC00SK066 Unable to update data.'));

                echo json_encode(array(
                    'submitted' => 'N',
                    'msg' => '#NC00SK066 Transactions Failed.',
                ));
                return;
            } else {
                $this->db->trans_commit();
                echo json_encode(array(
                    'submitted' => 'Y',
                    'msg' => 'Successfully Submitted, Forwarded to CO.',
                    'nc_village' => $nc_village = $this->db->query("select * from nc_villages where application_no='$application_no'")->row(),
                ));
                return;
            }
        } else {
            log_message("error", 'NC_Village_SK_Update: ' . json_encode('#NC00SK065 Unable to Submit.' . $this->db->last_query()));
            echo json_encode(array(
                'submitted' => 'N',
                'msg' => '#NC00SK065 Unable to Submit.',
            ));
            return;
        }
    }

    /** check verified dags */
    public function checkVerifiedDags($application_no)
    {
        $q3 = "select count(*) as count from nc_village_dags where application_no=? and sk_verified IS NULL";
        $check_unverified_dag = $this->db->query($q3, array(
            $application_no,
        ))->row()->count;

        if ($check_unverified_dag > 0) {
            $data = array(
                'msg' => 'Please Verify Draft Chitha.',
                'flag' => 'N',
            );
            return $data;
        } else {
            $data = array(
                'msg' => 'Draft Chitha verified.',
                'flag' => 'Y',
            );
            return $data;
        }
    }
}