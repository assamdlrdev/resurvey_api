<?php

include APPPATH . '/libraries/CommonTrait.php';
class NcVillageCoController extends CI_Controller
{
    use CommonTrait;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('CommonModel');
        $this->load->model('UtilsModel');
        $this->load->model('DagReportModel');
        if ($this->session->userdata('usertype') != 4) {
            show_error('<svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><g fill="none" stroke="#FF0000" stroke-linecap="round" stroke-width="2"><path d="M12 9v5m0 3.5v.5"/><path stroke-linejoin="round" d="M2.232 19.016L10.35 3.052c.713-1.403 2.59-1.403 3.302 0l8.117 15.964C22.45 20.36 21.544 22 20.116 22H3.883c-1.427 0-2.334-1.64-1.65-2.984Z"/></g></svg> <p>Unauthorized access</p>', "403");
        }
    }
    public function dashboard()
    {

        $this->dbswitch();
        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');

        //MAPS COUNT FROM ILRMS
        $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/apiGetMapsCount";
        $method = 'POST';
        $output = callIlrmsApi($url, $method, ['d' => $dist_code, 's' => $subdiv_code, 'c' => $cir_code, 'filter_flag_is' => 'F']);
        $data['maps_count'] = $output ? ($output->data ? $output->data : 0) : 0;

        //FORWARDED FROM SK COUNTF
        $data['fowarded_sk_count'] = $this->db->query("select count(*) as c from nc_villages where  dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and status='T'")->row()->c;
        //REVERTED FROM DC COUNT
        $data['reverted'] = $this->db->query("select count(*) as c from nc_villages where  dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and status='J' and pre_user='DC' and cu_user='CO'")->row()->c;

        //co proposal pending count
        $data['co_proposal_pending'] = $this->db->query("select count(*) as c from nc_villages where  dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and  status = 'O'")->row()->c;

        //forwarded from jds for chitha correction
        $data['forwarded_jds_count'] = $this->db->query("select count(*) as c from nc_villages where  dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and status='h' and pre_user='JDS' and cu_user='CO'")->row()->c;

        //co notifications count
        $filters['dist_code'] = (string)$this->session->userdata('dist_code');
        $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/apiGetNotifications";
        $method = 'POST';
        $api_params = ['filters' => $filters ? $filters  : []];
        $notifications = callIlrmsApi2($url, $method, $api_params);
        $data['notifications_count'] = $notifications ? count($notifications->notifications) : 'N/A';

        $data['_view'] = 'nc_village/co/nc_village_co_dashboard';
        $this->load->view('layout/layout', $data);
    }
    public function showVillages()
    {
        $this->dbswitch();
        $dist = $this->session->userdata('dist_code');
        $subdiv = $this->session->userdata('subdiv_code');
        $circle = $this->session->userdata('cir_code');
        $data['locations'] = $this->CommonModel->getLocations($dist, $subdiv, $circle);
        $data['mouzas'] = $this->db->select('loc_name,dist_code,subdiv_code,cir_code,mouza_pargona_code')
            ->where(array('dist_code' => $dist, 'subdiv_code' => $subdiv, 'cir_code' => $circle, 'mouza_pargona_code !=' => '00', 'lot_no' => '00'))
            ->get('location')->result_array();

        $this->dataswitch();
        $query = "select l.loc_name,ncv.lm_verified_at,ncv.status,ncv.application_no,ncv.lm_note,ncv.sk_verified_at,ncv.sk_note,ncv.co_verified,ncv.co_note, ncv.id as nc_village_id,ncv.case_type from nc_villages ncv join location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code
                 and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and ncv.vill_townprt_code = l.vill_townprt_code";

        $query = $query . " where ncv.dist_code='$dist' and ncv.subdiv_code='$subdiv' and ncv.cir_code='$circle' and ncv.status ='T'";

        $villages = $this->db->query($query)->result();
        if (count((array) $villages)) {
            foreach ($villages as $key => $village) {
                $merge_village_requests = $this->db->where('nc_village_id', $village->nc_village_id)->get('merge_village_requests')->result_array();
                $merge_village_name_arr = [];
                if (count($merge_village_requests)) {
                    foreach ($merge_village_requests as $key1 => $merge_village_request) {
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

        $data['_view'] = 'nc_village/co/villages_f';
        $this->load->view('layout/layout', $data);
    }
    public function showDags()
    {
        $this->dbswitch();
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
        	AND mouza_Pargona_code=? AND lot_No=? AND vill_townprt_code=? and co_verified IS NULL";
        $check_unverified_dag = $this->db->query($q3, array(
            $dist_code,
            $subdiv_code,
            $cir_code,
            $nc_village->mouza_pargona_code,
            $nc_village->lot_no,
            $nc_village->vill_townprt_code,
        ))->row()->count;

        $data['change_vill'] = $this->db->get_where('change_vill_name', array('uuid' => $data['locations']['village']['uuid']))->row();
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
        $data['for_co'] = 'Y';

        $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/apiGetMap";

        $method = 'POST';
        $output = callIlrmsApi($url, $method, $data);
        $data['maps'] = array();
        $data['old_maps'] = array();
        if (sizeof($output->data) != 0) {
            $data['maps'] = $output->data;
        }
        if(isset($output->old_maps) && sizeof($output->old_maps) != 0) {
            $data['old_maps'] = $output->old_maps;
        }
        $data['map_row'] = $output->map_row;

        $merge_village_requests = $this->db->where('nc_village_id', $nc_village->id)->get('merge_village_requests')->result_array();
        if (count($merge_village_requests)) {
            foreach ($merge_village_requests as $key => $merge_village_request) {
                $merge_village_requests[$key]['vill_loc'] = $vill_loc = $this->CommonModel->getLocations($merge_village_request['request_dist_code'], $merge_village_request['request_subdiv_code'], $merge_village_request['request_cir_code'], $merge_village_request['request_mouza_pargona_code'], $merge_village_request['request_lot_no'], $merge_village_request['request_vill_townprt_code']);
            }
        }
        $data['merge_village_requests'] = $merge_village_requests;

        $data['_view'] = 'nc_village/co/village_f_dags';
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
        $sk = $this->db->query("select users.username from users where 
		dist_code='$nc_village->dist_code' and subdiv_code='$nc_village->subdiv_code' and cir_code='$nc_village->cir_code'
		and user_code='$nc_village->sk_user_code'")->row();
        $dags = $this->CommonModel->getNcVillageDags($application_no);
        foreach ($dags as $dag) {
            $dag->occupiers = $this->DagReportModel->occupierNames($dag->dist_code, $dag->subdiv_code, $dag->cir_code, $dag->mouza_pargona_code, $dag->lot_no, $dag->vill_townprt_code, $dag->dag_no);
        }
        echo json_encode(array('dags' => $dags, 'sk_name' => $sk ? $sk->username : ''));

        return;
    }
    public function getVillagesSmH()
    {
        $dist_code = $this->input->post('dist_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $filter = $this->input->post('filter');

        $this->dataswitch();
        $query = "select l.loc_name,ncv.lm_verified_at,ncv.status,ncv.application_no,ncv.lm_note,ncv.sk_verified_at,ncv.sk_note,ncv.co_verified,ncv.co_note, ncv.id as nc_village_id,ncv.jds_note,ncv.updated_at from nc_villages ncv join location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code
                 and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and ncv.vill_townprt_code = l.vill_townprt_code";

        $query = $query . " where ncv.dist_code='$dist_code' and ncv.subdiv_code='$subdiv_code' and ncv.cir_code='$cir_code'";
        if ($mouza_pargona_code) {
            $query = $query . " and ncv.mouza_pargona_code = '$mouza_pargona_code'";
        }
        if ($lot_no) {
            $query = $query . " and ncv.lot_no = '$lot_no'";
        }

        $query = $query . " and ncv.status = 'h'";

        $result = $this->db->query($query)->result();

        if (count((array) $result)) {
            foreach ($result as $key => $village) {
                $merge_village_requests = $this->db->where('nc_village_id', $village->nc_village_id)->get('merge_village_requests')->result_array();
                $merge_village_name_arr = [];

                if (count($merge_village_requests)) {
                    foreach ($merge_village_requests as $key1 => $merge_village_request) {
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

        echo json_encode($result);
    }
    public function getVillagesF()
    {
        $dist_code = $this->input->post('dist_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $filter = $this->input->post('filter');

        $this->dataswitch();
        $query = "select l.loc_name,ncv.lm_verified_at,ncv.status,ncv.application_no,ncv.lm_note,ncv.sk_verified_at,ncv.sk_note,ncv.co_verified,ncv.co_note, ncv.id as nc_village_id from nc_villages ncv join location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code
                 and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and ncv.vill_townprt_code = l.vill_townprt_code";

        $query = $query . " where ncv.dist_code='$dist_code' and ncv.subdiv_code='$subdiv_code' and ncv.cir_code='$cir_code'";
        if ($mouza_pargona_code) {
            $query = $query . " and ncv.mouza_pargona_code = '$mouza_pargona_code'";
        }
        if ($lot_no) {
            $query = $query . " and ncv.lot_no = '$lot_no'";
        }
        if ($filter == 'pending') {
            $query = $query . " and ncv.status ='T'";
        }
        if ($filter == 'co_verified') {
            $query = $query . " and ncv.co_verified = 'Y'";
        }
        if ($filter == 'co_reverted') {
            $query = $query . " and ncv.status = 'H'";
        }

        $result = $this->db->query($query)->result();

        if (count((array) $result)) {
            foreach ($result as $key => $village) {
                $merge_village_requests = $this->db->where('nc_village_id', $village->nc_village_id)->get('merge_village_requests')->result_array();
                $merge_village_name_arr = [];

                if (count($merge_village_requests)) {
                    foreach ($merge_village_requests as $key1 => $merge_village_request) {
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

        echo json_encode($result);
    }
    public function verifyDag()
    {
        $application_no = $this->input->post('application_no');
        $dag_no = $this->input->post('dag_no');
        $patta_type_code = $this->input->post('patta_type_code');
        $patta_no = $this->input->post('patta_no');
        $this->dataswitch();

        $this->db->where('application_no', $application_no)
            ->where('dag_no', $dag_no)
            ->where('patta_type_code', (string) $patta_type_code)
            ->where('patta_no', (string) $patta_no)
            ->update('nc_village_dags', array('co_verified' => 'Y', 'updated_at' => date('Y-m-d H:i:s'), 'co_verified_at' => date('Y-m-d H:i:s')));

        if ($this->db->affected_rows() > 0) {
            echo json_encode(array(
                'submitted' => 'Y',
                'application_no' => $application_no,
                'msg' => 'Dag Successfully Verified.',
            ));
        } else {
            log_message("error", 'NC_Village_CO_Pass: ' . json_encode('#NC0005 Unable to Submit.' . $this->db->last_query()));
            echo json_encode(array(
                'submitted' => 'N',
                'application_no' => $application_no,
                'msg' => '#NC0005 Unable to verify dag.',
            ));
        }
    }

    /** CO final certification */
    public function certifyVillage()
    {
        $application_no = $this->input->post('application_no');
        $user_code = $this->session->userdata('user_code');
        $co_certification = $this->UtilsModel->cleanPattern($this->input->post('co_certification'));
        $co_remark = $this->UtilsModel->cleanPattern($this->input->post('remark'));
        $change_vill_remark = $this->UtilsModel->cleanPattern($this->input->post('change_vill_remark'));
        $remarks = "CO Remark: <br>" . $co_remark . "<br>Village Name Change:<br>" . $change_vill_remark;

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
                    'pre_user' => 'CO',
                    'cu_user' => 'DC',
                    'co_code' => $user_code,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'co_verified' => 'Y',
                    'co_verified_at' => date('Y-m-d H:i:s'),
                    'co_note' => trim($co_remark),
                    'co_certification' => $co_certification,
                    'status' => 'O',
                    'dc_certification' => null,
                    'dc_chitha_sign' => null,
                    'sign_key' => null,
                ]
            );

        if ($this->db->affected_rows() > 0) {
            $proceeding_id_max = $this->db->query("select max(proceeding_id) as id from settlement_proceeding where case_no = '$application_no'")->row()->id;

            $insPetProceed = array(
                'case_no' => $application_no,
                'proceeding_id' => !$proceeding_id_max ? 1 : (int) $proceeding_id_max + 1,
                'date_of_hearing' => date('Y-m-d h:i:s'),
                'next_date_of_hearing' => date('Y-m-d h:i:s'),
                'note_on_order' => $remarks,
                'status' => 'O',
                'user_code' => $this->session->userdata('user_code'),
                'date_entry' => date('Y-m-d h:i:s'),
                'operation' => 'E',
                'ip' => $_SERVER['REMOTE_ADDR'],
                'office_from' => 'CO',
                'office_to' => 'DC',
                'task' => 'Village Certified by CO',
            );

            $this->db->insert('settlement_proceeding', $insPetProceed);

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                log_message("error", 'NC_Village_CO_Update: ' . json_encode('#NC00066 Unable to update data.'));

                echo json_encode(array(
                    'submitted' => 'N',
                    'msg' => '#NC00066 Transactions Failed.',
                ));
                return;
            } else {
                $this->db->trans_commit();
                echo json_encode(array(
                    'submitted' => 'Y',
                    'msg' => 'Successfully Submitted, Forwarded to DC.',
                    'nc_village' => $nc_village = $this->db->query("select * from nc_villages where application_no='$application_no'")->row(),
                ));
                return;
            }
        } else {
            log_message("error", 'NC_Village_CO_Update: ' . json_encode('#NC00065 Unable to Submit.' . $this->db->last_query()));
            echo json_encode(array(
                'submitted' => 'N',
                'msg' => '#NC00065 Unable to Submit.',
            ));
            return;
        }
    }

    /** check verified dags */
    public function checkVerifiedDags($application_no)
    {
        $q3 = "select count(*) as count from nc_village_dags where application_no=? and co_verified IS NULL";
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

    /** CO revert back to SK */
    public function revertVillage()
    {
        $application_no = $this->input->post('application_no');
        $user_code = $this->session->userdata('user_code');
        $remark = $this->UtilsModel->cleanPattern($this->input->post('remark'));

        $this->form_validation->set_rules('application_no', 'Application NO', 'trim|required');
        if ($this->form_validation->run() == false) {
            echo json_encode(array('errors' => validation_errors(), 'st' => 0));
            return;
        }
        $this->dataswitch();
        $this->db->where('application_no', $application_no)
            ->update(
                'nc_villages',
                [
                    'pre_user' => 'CO',
                    'cu_user' => 'SK',
                    'co_code' => $user_code,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'co_note' => trim($remark),
                    'status' => 'H',
                    'sk_verified' => 'N',
                    'co_verified' => null,
                    'co_verified_at' => null,
                ]
            );

        $this->db->where('application_no', $application_no)
            ->update(
                'nc_village_dags',
                [
                    'updated_at' => date('Y-m-d H:i:s'),
                    'sk_verified' => null,
                    'sk_verified_at' => null,
                    'co_verified' => null,
                    'co_verified_at' => null,
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
                'status' => 'H',
                'user_code' => $this->session->userdata('user_code'),
                'date_entry' => date('Y-m-d h:i:s'),
                'operation' => 'E',
                'ip' => $_SERVER['REMOTE_ADDR'],
                'office_from' => 'CO',
                'office_to' => 'SK',
                'task' => 'Village Reverted by CO',
            );
            $this->db->insert('settlement_proceeding', $insPetProceed);

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                log_message("error", 'NC_Village_CO_Update: ' . json_encode('#NC0006 Unable to update data.'));

                echo json_encode(array(
                    'submitted' => 'N',
                    'msg' => '#NC0006 Transactions Failed.',
                ));
                return;
            } else {
                $this->db->trans_commit();
                echo json_encode(array(
                    'submitted' => 'Y',
                    'msg' => 'Successfully Submitted, Reverted to SK.',
                    'nc_village' => $nc_village = $this->db->query("select * from nc_villages where application_no='$application_no'")->row(),
                ));
                return;
            }
        } else {
            log_message("error", 'NC_Village_CO_Revert: ' . json_encode('#NC0005 Unable to Revert Back.' . $this->db->last_query()));
            echo json_encode(array(
                'submitted' => 'N',
                'msg' => '#NC0005 Unable to Revert Back.',
            ));
            return;
        }
    }

    /** CO verify draft chitha */
    public function verifyDraftChitha()
    {
        $this->dbswitch();

        $this->form_validation->set_rules('application_no', 'application_no', 'trim|required');

        if ($this->form_validation->run() == false) {
            echo json_encode(array('errors' => validation_errors(), 'st' => 0));
            return;
        }

        $loc['application_no'] = $dist_code = $this->UtilsModel->cleanPattern($this->input->post('application_no'));

        $q = "select * from nc_village_dags where application_no=? ";
        $nc_village_dags = $this->db->query($q, array($loc['application_no']))->result();

        $this->db->trans_begin();
        foreach ($nc_village_dags as $dag) {
            $this->db->where('application_no', $loc['application_no'])
                ->where('dag_no', $dag->dag_no)
                ->where('patta_type_code', $dag->patta_type_code)
                ->where('patta_no', $dag->patta_no)
                ->update('nc_village_dags', array('co_verified' => 'Y', 'updated_at' => date('Y-m-d H:i:s'), 'co_verified_at' => date('Y-m-d H:i:s')));

            if ($this->db->affected_rows() == 0) {
                $this->db->trans_rollback();
                log_message("error", 'NC_Village_Dag_CO_Update: ' . json_encode('#NC0024 Unable to Update data.'));
                echo array(
                    'application_no' => $dag->application_no,
                    'error' => true,
                    'msg' => '#NC0024 Unable to update data.',
                );
            }
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            log_message("error", 'NC_Village_or_dag_CO_Update: ' . json_encode('#NC0020 Unable to update data.'));
            echo json_encode(array(
                'application_no' => $nc_village_dags[0]->application_no,
                'error' => true,
                'msg' => '#NC0020 Unable to update data.',
            ));
        } else {
            $this->db->trans_commit();
            echo json_encode(array(
                'application_no' => $nc_village_dags[0]->application_no,
                'error' => false,
                'msg' => null,
            ));
        }
    }

    /** reverted village from dc */
    public function revertedVillages()
    {
        $this->dbswitch();
        $data['_view'] = 'nc_village/co/nc_villages_reverted';
        $this->load->view('layout/layout', $data);
    }

    /** view revert village from DC */
    public function getVillagesH()
    {
        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');

        $this->dataswitch();
        $query = "select l.loc_name,ncv.lm_verified_at,ncv.status,ncv.application_no,ncv.lm_note,ncv.sk_verified_at,ncv.sk_note,ncv.dc_note from nc_villages ncv join location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code
                 and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and ncv.vill_townprt_code = l.vill_townprt_code";

        $query = $query . " where ncv.dist_code='$dist_code' and ncv.subdiv_code='$subdiv_code' and ncv.cir_code='$cir_code' and ncv.status='J' and ncv.pre_user='DC' and ncv.cu_user='CO'";

        $result = $this->db->query($query)->result();

        echo json_encode($result);
    }

    public function showVillagesDraftMap()
    {
        $this->dbswitch();
        $dist = $this->session->userdata('dist_code');
        $subdiv = $this->session->userdata('subdiv_code');
        $circle = $this->session->userdata('cir_code');
        $data['locations'] = $this->CommonModel->getLocations($dist, $subdiv, $circle);
        $data['mouzas'] = $this->db->select('loc_name,dist_code,subdiv_code,cir_code,mouza_pargona_code')
            ->where(array('dist_code' => $dist, 'subdiv_code' => $subdiv, 'cir_code' => $circle, 'mouza_pargona_code !=' => '00', 'lot_no' => '00'))
            ->get('location')->result_array();

        //MAPS COUNT FROM ILRMS
        $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/apiGetMaps";
        $method = 'POST';
        $output = callIlrmsApi($url, $method, ['d' => $dist, 's' => $subdiv, 'c' => $circle, 'filter_flag_is' => 'F']);
        $maps = $output ? $output->data : [];
        foreach ($maps as $map) {
            $mouza = $this->CommonModel->getLocations($map->dist_code, $map->subdiv_code, $map->cir_code, $map->mouza_pargona_code);
            $village = $this->CommonModel->getLocations($map->dist_code, $map->subdiv_code, $map->cir_code, $map->mouza_pargona_code, $map->lot_no, $map->vill_townprt_code);

            $map->mouza_name = $mouza ? $mouza['mouza']['loc_name'] : '';
            $map->village_name = $village ? $village['village']['loc_name'] : '';
        }
        $data['maps'] = $output ? $output->data : [];

        $data['_view'] = 'nc_village/co/villages_maps';
        $this->load->view('layout/layout', $data);
    }
    public function jdsForwardedVillages()
    {
        $this->dbswitch();
        $dist = $this->session->userdata('dist_code');
        $subdiv = $this->session->userdata('subdiv_code');
        $circle = $this->session->userdata('cir_code');
        $data['locations'] = $this->CommonModel->getLocations($dist, $subdiv, $circle);
        $data['mouzas'] = $this->db->select('loc_name,dist_code,subdiv_code,cir_code,mouza_pargona_code')
            ->where(array('dist_code' => $dist, 'subdiv_code' => $subdiv, 'cir_code' => $circle, 'mouza_pargona_code !=' => '00', 'lot_no' => '00'))
            ->get('location')->result_array();

        $this->dataswitch();
        $query = "select l.loc_name,ncv.lm_verified_at,ncv.status,ncv.application_no,ncv.lm_note,ncv.sk_verified_at,ncv.sk_note,ncv.co_verified,ncv.co_note, ncv.id as nc_village_id,ncv.jds_note,ncv.updated_at from nc_villages ncv join location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code
                 and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and ncv.vill_townprt_code = l.vill_townprt_code";

        $query = $query . " where ncv.dist_code='$dist' and ncv.subdiv_code='$subdiv' and ncv.cir_code='$circle' and ncv.status ='h'";

        $villages = $this->db->query($query)->result();
        if (count((array) $villages)) {
            foreach ($villages as $key => $village) {
                $merge_village_requests = $this->db->where('nc_village_id', $village->nc_village_id)->get('merge_village_requests')->result_array();
                $merge_village_name_arr = [];
                if (count($merge_village_requests)) {
                    foreach ($merge_village_requests as $key1 => $merge_village_request) {
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

        $data['_view'] = 'nc_village/co/villages_sm_h';
        $this->load->view('layout/layout', $data);
    }
    public function getVillagesDraftMap()
    {
        $this->dbswitch();
        $dist = $this->session->userdata('dist_code');
        $subdiv = $this->session->userdata('subdiv_code');
        $circle = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $filter_status = $this->input->post('filter_status');


        //MAPS COUNT FROM ILRMS
        $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/apiGetMaps";
        $method = 'POST';
        $filter_data = ['d' => $dist, 's' => $subdiv, 'c' => $circle];
        if ($mouza_pargona_code) {
            $filter_data['m'] = $mouza_pargona_code;
        }
        if ($lot_no) {
            $filter_data['l'] = $lot_no;
        }
        if ($filter_status == 'lm_forwarded') {
            $filter_data['forwarded_co'] = 'F';
        }
        if ($filter_status == 'co_pending') {
            $filter_data['pending_co'] = 'F';
        }
        $output = callIlrmsApi($url, $method, $filter_data);
        $maps = $output ? $output->data : [];
        foreach ($maps as $map) {
            $mouza = $this->CommonModel->getLocations($map->dist_code, $map->subdiv_code, $map->cir_code, $map->mouza_pargona_code);
            $village = $this->CommonModel->getLocations($map->dist_code, $map->subdiv_code, $map->cir_code, $map->mouza_pargona_code, $map->lot_no, $map->vill_townprt_code);

            $map->mouza_name = $mouza ? $mouza['mouza']['loc_name'] : '';
            $map->village_name = $village ? $village['village']['loc_name'] : '';
        }
        $maps = $output ? $output->data : [];
        echo json_encode($maps);
    }
    /** View Map */
    public function viewUploadedMap()
    {
        if ($this->session->userdata('usertype') != 4) {
            show_error('<svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><g fill="none" stroke="#FF0000" stroke-linecap="round" stroke-width="2"><path d="M12 9v5m0 3.5v.5"/><path stroke-linejoin="round" d="M2.232 19.016L10.35 3.052c.713-1.403 2.59-1.403 3.302 0l8.117 15.964C22.45 20.36 21.544 22 20.116 22H3.883c-1.427 0-2.334-1.64-1.65-2.984Z"/></g></svg> <p>Unauthorized access</p>', "403");
        }
        $id = $data['id'] = $_GET['id'];
        $dist_code = $data['d'] = $this->session->userdata('dist_code');

        $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/apiGetMapBase64";

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
    public function forwardToLm()
    {
        $this->dbswitch();

        $this->form_validation->set_rules('flag', 'flag', 'trim|required');
        $this->form_validation->set_rules('map_id', 'Map ID', 'trim|required');

        if ($this->form_validation->run() == false) {
            echo json_encode(array('errors' => validation_errors(), 'status' => '0'));
            return;
        }

        $map_id = $this->input->post('map_id');
        $flag = $this->input->post('flag');

        //UPDATE MAPS FLAG
        $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/updateFlag";
        $method = 'POST';
        $filter_data = ['map_id' => $map_id, 'flag' => $flag, 'co_user_code' => $this->session->userdata('user_code')];
        $output = callIlrmsApi($url, $method, $filter_data);
        $response = $output ? $output->status : [];

        echo json_encode(array(
            'status' => $response,
        ));
    }

    public function showProposalVillages()
    {
        $this->dbswitch('auth');
        $auth_connection = $this->db;
        $this->dbswitch();
        $dist = $this->session->userdata('dist_code');
        $subdiv = $this->session->userdata('subdiv_code');
        $circle = $this->session->userdata('cir_code');
        $data['locations'] = $this->CommonModel->getLocations($dist, $subdiv, $circle);
        $data['mouzas'] = $this->db->select('loc_name,dist_code,subdiv_code,cir_code,mouza_pargona_code')
            ->where(array('dist_code' => $dist, 'subdiv_code' => $subdiv, 'cir_code' => $circle, 'mouza_pargona_code !=' => '00', 'lot_no' => '00'))
            ->get('location')->result_array();

        $query = "select nc.*,l.loc_name,ch.old_vill_name,ch.new_vill_name from nc_villages nc 
                    left join change_vill_name ch on nc.dist_code = ch.dist_code and nc.uuid = ch.uuid
					 join location l on nc.dist_code = l.dist_code and nc.subdiv_code = l.subdiv_code and nc.cir_code = l.cir_code
                 and nc.mouza_pargona_code = l.mouza_pargona_code and nc.lot_no = l.lot_no and nc.vill_townprt_code = l.vill_townprt_code where 
					 nc.dist_code='$dist' and nc.subdiv_code='$subdiv' and nc.cir_code='$circle' and nc.status ='O'";

        $data['villages'] = $nc_village = $this->db->query($query)->result();
        foreach ($nc_village as $k => $v) {
            $nc_village[$k]->mouza_name = $this->db->select('loc_name,dist_code,subdiv_code,cir_code,mouza_pargona_code,locname_eng')
                ->where(array('dist_code' => $v->dist_code, 'subdiv_code' => $v->subdiv_code, 'cir_code' => $v->cir_code, '
				mouza_pargona_code' => $v->mouza_pargona_code, 'lot_no' => '00', 'vill_townprt_code' => '00000'))
                ->get('location')->row();

            $nc_village[$k]->lot_name = $this->db->select('loc_name,dist_code,subdiv_code,cir_code,mouza_pargona_code,locname_eng')
                ->where(array('dist_code' => $v->dist_code, 'subdiv_code' => $v->subdiv_code, 'cir_code' => $v->cir_code, '
				mouza_pargona_code' => $v->mouza_pargona_code, 'lot_no' => $v->lot_no, 'vill_townprt_code' => '00000'))
                ->get('location')->row();

            $nc_village[$k]->total_dag_area = $vill_area = $this->db->select('count(*) as total_dag, SUM(dag_area_b) as total_bigha,
				SUM(dag_area_k) as total_katha,SUM(dag_area_lc) as total_lessa')
                ->where(array(
                    'dist_code' => $v->dist_code,
                    'subdiv_code' => $v->subdiv_code,
                    'cir_code' => $v->cir_code,
                    '
				mouza_pargona_code' => $v->mouza_pargona_code,
                    'lot_no' => $v->lot_no,
                    'vill_townprt_code' => $v->vill_townprt_code
                ))
                ->get('chitha_basic_nc')->row();

            $total_lessa = $this->CommonModel->totalLessa($vill_area->total_bigha, $vill_area->total_katha, $vill_area->total_lessa);
            $nc_village[$k]->total_b_k_l = $this->CommonModel->Total_Bigha_Katha_Lessa($total_lessa);

            $nc_village[$k]->occupiers = $this->db->select('count(*) as occupiers')
                ->where(array(
                    'dist_code' => $v->dist_code,
                    'subdiv_code' => $v->subdiv_code,
                    'cir_code' => $v->cir_code,
                    '
				mouza_pargona_code' => $v->mouza_pargona_code,
                    'lot_no' => $v->lot_no,
                    'vill_townprt_code' => $v->vill_townprt_code
                ))
                ->get('chitha_rmk_encro')->row()->occupiers;

            $merge_village_requests = $this->db->where('nc_village_id', $v->id)->get('merge_village_requests')->result_array();
            $merge_village_name_arr = [];
            if (count($merge_village_requests)) {
                foreach ($merge_village_requests as $key1 => $merge_village_request) {
                    $vill_loc = $this->CommonModel->getLocations($merge_village_request['request_dist_code'], $merge_village_request['request_subdiv_code'], $merge_village_request['request_cir_code'], $merge_village_request['request_mouza_pargona_code'], $merge_village_request['request_lot_no'], $merge_village_request['request_vill_townprt_code']);
                    array_push($merge_village_name_arr, $vill_loc['village']['loc_name']);
                    $merge_village_requests[$key1]['village_name'] = $vill_loc['village']['loc_name'];
                    $merge_village_requests[$key1]['vill_loc'] = $vill_loc;
                }
            }
            $nc_village[$k]->merge_village_names = implode(', ', $merge_village_name_arr);
            $nc_village[$k]->merge_village_requests = $merge_village_requests;
            // $is_end_village_cadastral_village = $auth_connection->where('dist_code', $v->dist_code)
            //                                                     ->where('subdiv_code', $v->subdiv_code)
            //                                                     ->where('cir_code', $v->cir_code)
            //                                                     ->where('mouza_pargona_code', $v->mouza_pargona_code)
            //                                                     ->where('lot_no', $v->lot_no)
            //                                                     ->where('vill_townprt_code', $v->vill_townprt_code)
            //                                                     ->where('uuid', $v->uuid)
            //                                                     ->where(['nc_btad' => NULL])
            //                                                     ->get('location')
            //                                                     ->num_rows();

            // $nc_village[$k]->is_end_village_cadastral_village = $is_end_village_cadastral_village > 0 ? TRUE : FALSE;
            $nc_village[$k]->is_end_village_cadastral_village = ($v->case_type == 'NC_TO_C') ? TRUE : FALSE;
        }
        $data['approve_proposal'] = $this->db->select('proposal_no,created_at')
            ->where(array('dist_code' => $dist, 'subdiv_code' => $subdiv, 'cir_code' => $circle, 'user_type' => 'CO', 'status' => 'A'))
            ->order_by('id', 'desc')
            ->get('nc_village_proposal')->result();


        $data['nc_village'] = $nc_village;
        $data['proposal'] = $this->load->view('nc_village/co/approval_notification', $data, true);

        $data['_view'] = 'nc_village/co/proposal_villages';
        $this->load->view('layout/layout', $data);
    }
    public function getProposalVillages()
    {
        $this->dbswitch('auth');
        $auth_connection = $this->db;
        $this->dbswitch();
        $dist = $this->session->userdata('dist_code');
        $subdiv = $this->session->userdata('subdiv_code');
        $circle = $this->session->userdata('cir_code');
        $query = "select nc.*,l.loc_name,ch.old_vill_name,ch.new_vill_name from nc_villages nc 
                    left join change_vill_name ch on nc.dist_code = ch.dist_code and nc.uuid = ch.uuid
					 join location l on nc.dist_code = l.dist_code and nc.subdiv_code = l.subdiv_code and nc.cir_code = l.cir_code
                 and nc.mouza_pargona_code = l.mouza_pargona_code and nc.lot_no = l.lot_no and nc.vill_townprt_code = l.vill_townprt_code where 
					 nc.dist_code='$dist' and nc.subdiv_code='$subdiv' and nc.cir_code='$circle' and nc.status ='O'";

        $villages = $this->db->query($query)->result();
        if (count((array) $villages)) {
            foreach ($villages as $key => $village) {
                $merge_village_requests = $this->db->where('nc_village_id', $village->id)->get('merge_village_requests')->result_array();
                $merge_village_name_arr = [];
                if (count($merge_village_requests)) {
                    foreach ($merge_village_requests as $key1 => $merge_village_request) {
                        $vill_loc = $this->CommonModel->getLocations($merge_village_request['request_dist_code'], $merge_village_request['request_subdiv_code'], $merge_village_request['request_cir_code'], $merge_village_request['request_mouza_pargona_code'], $merge_village_request['request_lot_no'], $merge_village_request['request_vill_townprt_code']);
                        array_push($merge_village_name_arr, $vill_loc['village']['loc_name']);
                        // $merge_village_requests[$key1]['village_name'] = $vill_loc['village']['loc_name'];
                        // $merge_village_requests[$key1]['vill_loc'] = $vill_loc;
                    }
                }
                $villages[$key]->merge_village_names = implode(', ', $merge_village_name_arr);
                // $villages[$key]->merge_village_requests = $merge_village_requests;
                // $is_end_village_cadastral_village = $auth_connection->where('dist_code', $village->dist_code)
                //                                                 ->where('subdiv_code', $village->subdiv_code)
                //                                                 ->where('cir_code', $village->cir_code)
                //                                                 ->where('mouza_pargona_code', $village->mouza_pargona_code)
                //                                                 ->where('lot_no', $village->lot_no)
                //                                                 ->where('vill_townprt_code', $village->vill_townprt_code)
                //                                                 ->where('uuid', $village->uuid)
                //                                                 ->where(['nc_btad' => NULL])
                //                                                 ->get('location')
                //                                                 ->num_rows();

                // $villages[$key]->is_end_village_cadastral_village = $is_end_village_cadastral_village > 0 ? TRUE : FALSE;
                $villages[$key]->is_end_village_cadastral_village = ($village->case_type == 'NC_TO_C') ? TRUE : FALSE;
            }
        }

        // echo json_encode($this->db->query($query)->result());
        echo json_encode($villages);
    }

    /** view approval notification */
    public function saveProposalPdf()
    {
        $case_nos = $this->input->post('cases');
        if (empty($case_nos)) {
            return response_json(['success' => false, 'message' => 'Please select atleast one village']);
        }

        $case_nos_str_arr = array_map(function ($case_no) {
            return "'" . $case_no . "'";
        }, $case_nos);
        $case_nos_str = implode(',', $case_nos_str_arr);
        $this->session->set_userdata('proposal_case_nos', $case_nos);

        $this->dbswitch();

        $dist = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');

        $query = "select nc.*,ch.old_vill_name,ch.new_vill_name from nc_villages nc join change_vill_name ch 
					on nc.dist_code = ch.dist_code and nc.uuid = ch.uuid where
					 nc.dist_code='$dist' and nc.subdiv_code='$subdiv_code' and nc.cir_code='$cir_code' and nc.status ='O' and nc.application_no in ($case_nos_str)";

        $nc_village = $this->db->query($query)->result();
        foreach ($nc_village as $k => $v) {
            $nc_village[$k]->circle_name = $this->db->select('loc_name,dist_code,subdiv_code,cir_code,locname_eng')
                ->where(array('dist_code' => $v->dist_code, 'subdiv_code' => $v->subdiv_code, 'cir_code' => $v->cir_code, '
				mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))
                ->get('location')->row();

            $nc_village[$k]->mouza_name = $this->db->select('loc_name,dist_code,subdiv_code,cir_code,mouza_pargona_code,locname_eng')
                ->where(array('dist_code' => $v->dist_code, 'subdiv_code' => $v->subdiv_code, 'cir_code' => $v->cir_code, '
				mouza_pargona_code' => $v->mouza_pargona_code, 'lot_no' => '00', 'vill_townprt_code' => '00000'))
                ->get('location')->row();

            $nc_village[$k]->lot_name = $this->db->select('loc_name,dist_code,subdiv_code,cir_code,mouza_pargona_code,locname_eng')
                ->where(array('dist_code' => $v->dist_code, 'subdiv_code' => $v->subdiv_code, 'cir_code' => $v->cir_code, '
				mouza_pargona_code' => $v->mouza_pargona_code, 'lot_no' => $v->lot_no, 'vill_townprt_code' => '00000'))
                ->get('location')->row();

            $nc_village[$k]->total_dag_area = $vill_area = $this->db->select('count(*) as total_dag, SUM(dag_area_b) as total_bigha,
				SUM(dag_area_k) as total_katha,SUM(dag_area_lc) as total_lessa')
                ->where(array(
                    'dist_code' => $v->dist_code,
                    'subdiv_code' => $v->subdiv_code,
                    'cir_code' => $v->cir_code,
                    '
				mouza_pargona_code' => $v->mouza_pargona_code,
                    'lot_no' => $v->lot_no,
                    'vill_townprt_code' => $v->vill_townprt_code
                ))
                ->get('chitha_basic_nc')->row();

            $total_lessa = $this->CommonModel->totalLessa($vill_area->total_bigha, $vill_area->total_katha, $vill_area->total_lessa);
            $nc_village[$k]->total_b_k_l = $this->CommonModel->Total_Bigha_Katha_Lessa($total_lessa);

            $nc_village[$k]->occupiers = $this->db->select('count(*) as occupiers')
                ->where(array(
                    'dist_code' => $v->dist_code,
                    'subdiv_code' => $v->subdiv_code,
                    'cir_code' => $v->cir_code,
                    '
				mouza_pargona_code' => $v->mouza_pargona_code,
                    'lot_no' => $v->lot_no,
                    'vill_townprt_code' => $v->vill_townprt_code
                ))
                ->get('chitha_rmk_encro')->row()->occupiers;

            $merge_village_requests = $this->db->where('nc_village_id', $v->id)->get('merge_village_requests')->result_array();
            $merge_village_name_arr = [];
            if (count($merge_village_requests)) {
                foreach ($merge_village_requests as $key1 => $merge_village_request) {
                    $vill_loc = $this->CommonModel->getLocations($merge_village_request['request_dist_code'], $merge_village_request['request_subdiv_code'], $merge_village_request['request_cir_code'], $merge_village_request['request_mouza_pargona_code'], $merge_village_request['request_lot_no'], $merge_village_request['request_vill_townprt_code']);
                    array_push($merge_village_name_arr, $vill_loc['village']['loc_name']);
                    $merge_village_requests[$key1]['village_name'] = $vill_loc['village']['loc_name'];
                    $merge_village_requests[$key1]['vill_loc'] = $vill_loc;
                }
            }
            $nc_village[$k]->merge_village_names = implode(', ', $merge_village_name_arr);
            $nc_village[$k]->merge_village_requests = $merge_village_requests;
        }
        $data['nc_village'] = $nc_village;
        $data['locations'] = $this->CommonModel->getLocations($dist, $subdiv_code, $cir_code);
        $content = $this->load->view('nc_village/co/approval_notification', $data, true);
        ini_set("pcre.backtrack_limit", "500000000");
        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '-1');
        include 'vendor/mpdf/vendor/autoload.php';
        $mpdf = new \Mpdf\Mpdf([
            'default_font_size' => 12,
            'default_font' => 'dejavusans',
            'orientation' => 'P',
            'format' => 'A4',
        ]);

        $file_id = time();

        $application_no = "PR/" . $dist . "/" . $cir_code . "/" . $file_id . "/NC";
        $file_name = "PR_" . $dist . "_" . $cir_code . "_" . $file_id . "_NC";
        $this->session->set_userdata('proposal_file_name', $file_name);
        if (!is_dir(FCPATH . NC_VILLAGE_PROPOSAL_DIR . 'co')) {
            mkdir(FCPATH . NC_VILLAGE_PROPOSAL_DIR . 'co', 0777, true);
        }
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        $mpdf->writeHTML($content);

        header('Content-type: application/pdf');
        ob_clean();

        $file_path = FCPATH . NC_VILLAGE_PROPOSAL_DIR . 'co' . '/' . $file_name . '.pdf';
        $mpdf->Output(FCPATH . NC_VILLAGE_PROPOSAL_DIR . 'co' . '/' . $file_name . '.pdf', 'F');

        $pdfData = file_get_contents($file_path);
        $base64EncodedPDF = base64_encode($pdfData);

        return response_json(['success' => true, 'message' => 'Pdf generated successfully', 'data' => json_encode($base64EncodedPDF)]);
        // echo json_encode($base64EncodedPDF);
    }
    public function storeSignedProposal()
    {
        $case_nos_arr = $this->session->userdata('proposal_case_nos');
        $this->dataswitch();
        $pdfFilePath = FCPATH . NC_VILLAGE_PROPOSAL_DIR . 'co' . '/' . $this->session->userdata('proposal_file_name') . '.pdf';
        $proposal_no = $this->session->userdata('proposal_file_name');
        $sign_key = $this->input->post('sign_key');
        $pdfbase = $this->input->post('pdfbase');
        $pdf_content = base64_decode($pdfbase);

        $dist = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');

        if ($pdfbase !== false) {

            $pdf_path = $pdfFilePath;

            // Save the PDF content to the file
            if (file_put_contents($pdf_path, $pdf_content) !== false) {
                $is_exists = $this->db->get_where('nc_village_proposal', ['proposal_no' => $proposal_no])->num_rows();
                $this->db->trans_begin();
                if ($is_exists) {
                    $this->db->where('proposal_no', $proposal_no)
                        ->update('nc_village_proposal', array(
                            'user_code' => $this->session->userdata('user_code'),
                            'dist_code' => $this->session->userdata('dist_code'),
                            'subdiv_code' => $this->session->userdata('subdiv_code'),
                            'user_type' => 'CO',
                            'sign_key' => $sign_key,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'status' => 'A'
                        ));
                } else {
                    $this->db->insert('nc_village_proposal', array(
                        'proposal_no' => $proposal_no,
                        'user_code' => $this->session->userdata('user_code'),
                        'dist_code' => $this->session->userdata('dist_code'),
                        'subdiv_code' => $this->session->userdata('subdiv_code'),
                        'cir_code' => $this->session->userdata('cir_code'),
                        'user_type' => 'CO',
                        'sign_key' => $sign_key,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s'),
                        'status' => 'A'
                    ));

                    $lastid = $this->db->insert_id();

                    $this->db->where('dist_code', $dist)
                        ->where('subdiv_code', $subdiv_code)
                        ->where('cir_code', $cir_code)
                        ->where('status', 'O')
                        ->where_in('application_no', $case_nos_arr)
                        ->update('nc_villages', array('status' => 'G', 'co_proposal' => 'Y', 'proposal_id' => $lastid));
                }
                if ($this->db->trans_status() === false) {
                    $this->db->trans_rollback();
                    log_message("error", 'NC_Village_CO_PROPOSAL_Update: ' . json_encode('#NCPROP0001 Unable to update data.'));
                    echo json_encode(array(
                        'status' => '0',
                        'update' => '0',
                        'msg' => 'Failed Proposal signing',
                    ));
                    return;
                } else {
                    $this->db->trans_commit();
                    echo json_encode(array(
                        'status' => '1',
                        'update' => '1',
                        'msg' => 'Proposal signed successfully',
                    ));
                    return;
                }
            } else {
                echo json_encode(array(
                    'status' => '0',
                    'update' => '0',
                    'msg' => 'Failed Proposal signing',
                ));
                return;
            }
        } else {
            echo json_encode(array(
                'status' => '0',
                'update' => '0',
                'msg' => 'Invalid base64-encoded PDF content',
            ));
            return;
        }
    }
    public function storeProposalBypassSign()
    {
        $case_nos_arr = $this->session->userdata('proposal_case_nos');
        $this->dataswitch();
        $proposal_no = $this->session->userdata('proposal_file_name');
        $dist = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');

        $is_exists = $this->db->get_where('nc_village_proposal', ['proposal_no' => $proposal_no])->num_rows();
        $this->db->trans_begin();
        if ($is_exists) {
            $this->db->where('proposal_no', $proposal_no)
                ->update('nc_village_proposal', array(
                    'user_code' => $this->session->userdata('user_code'),
                    'dist_code' => $this->session->userdata('dist_code'),
                    'subdiv_code' => $this->session->userdata('subdiv_code'),
                    'user_type' => 'CO',
                    'sign_key' => 'tested',
                    'updated_at' => date('Y-m-d H:i:s'),
                    'status' => 'A'
                ));
            $this->db->where('dist_code', $dist)
                ->where('subdiv_code', $subdiv_code)
                ->where('cir_code', $cir_code)
                ->where('status', 'O')
                ->where_in('application_no', $case_nos_arr)
                ->update('nc_villages', array('status' => 'G', 'co_proposal' => 'Y'));
        } else {
            $this->db->insert('nc_village_proposal', array(
                'proposal_no' => $proposal_no,
                'user_code' => $this->session->userdata('user_code'),
                'dist_code' => $this->session->userdata('dist_code'),
                'subdiv_code' => $this->session->userdata('subdiv_code'),
                'cir_code' => $this->session->userdata('cir_code'),
                'user_type' => 'CO',
                'sign_key' => 'tested',
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'status' => 'A'
            ));

            $lastid = $this->db->insert_id();

            $this->db->where('dist_code', $dist)
                ->where('subdiv_code', $subdiv_code)
                ->where('cir_code', $cir_code)
                ->where('status', 'O')
                ->where_in('application_no', $case_nos_arr)
                ->update('nc_villages', array('status' => 'G', 'co_proposal' => 'Y', 'proposal_id' => $lastid));
        }
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            log_message("error", 'NC_Village_CO_PROPOSAL_Update: ' . json_encode('#NCPROP0001 Unable to update data.'));
            echo json_encode(array(
                'status' => '0',
                'update' => '0',
                'msg' => 'Failed Proposal signing',
            ));
            return;
        } else {
            $this->db->trans_commit();
            echo json_encode(array(
                'status' => '1',
                'update' => '1',
                'msg' => 'Proposal signed successfully',
            ));
            return;
        }
    }
    public function getProposalBase()
    {
        $pdfFilePath = FCPATH . NC_VILLAGE_PROPOSAL_DIR . 'co' . '/' . $this->session->userdata('proposal_file_name') . '.pdf';
        header("Content-Type: application/pdf");
        echo (file_get_contents($pdfFilePath));
        return;
    }
}
