<?php

include APPPATH . '/libraries/CommonTrait.php';
class NcVillageDcController extends CI_Controller
{
    use CommonTrait;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('CommonModel');
        $this->load->model('UtilsModel');
        $this->load->model('DagReportModel');
    }
    public function dashboard()
    {
        if ($this->session->userdata('usertype') != 7) {
            show_error('<svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><g fill="none" stroke="#FF0000" stroke-linecap="round" stroke-width="2"><path d="M12 9v5m0 3.5v.5"/><path stroke-linejoin="round" d="M2.232 19.016L10.35 3.052c.713-1.403 2.59-1.403 3.302 0l8.117 15.964C22.45 20.36 21.544 22 20.116 22H3.883c-1.427 0-2.334-1.64-1.65-2.984Z"/></g></svg> <p>Unauthorized access</p>', "403");
        }

        //co notifications count
        $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/apiGetDistrictNotifications";
        $method = 'POST';
        $dist_code = (string)$this->session->userdata('dist_code');
        $api_params = ['dist_code' => $dist_code];
        $notifications = callIlrmsApi2($url, $method, $api_params);
        $data['notifications_count'] = $notifications ? count($notifications->notifications) : 'N/A';


        $this->dbswitch();
        $dist_code = $this->session->userdata('dcode');
        $data['reverted'] = $this->db->query("select count(*) as c from nc_villages where dist_code='$dist_code' and status='B'")->row()->c;
        $data['dsign_pending_count'] = $this->db->query("select count(*) as c from nc_villages where  dist_code='$dist_code' and status='G'")->row()->c;
        $data['dc_proposal_pending'] = $this->db->query("select count(*) as c from nc_villages where  dist_code='$dist_code' and  status = 'K'")->row()->c;
        $data['forwarded_name_change'] = $this->db->query("select count(*) as c from nc_villages where  dist_code='$dist_code' and (status = 'M' or status = 'N')")->row()->c;
        $data['reverted_name_change'] = $this->db->query("select count(*) as c from nc_villages where  dist_code='$dist_code' and status='f'")->row()->c;


        $data['_view'] = 'nc_village/dc/nc_village_dc_dashboard';
        $this->load->view('layout/layout', $data);
    }
    public function showVillages($type = null)
    {
        if ($this->session->userdata('usertype') != 7) {
            show_error('<svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><g fill="none" stroke="#FF0000" stroke-linecap="round" stroke-width="2"><path d="M12 9v5m0 3.5v.5"/><path stroke-linejoin="round" d="M2.232 19.016L10.35 3.052c.713-1.403 2.59-1.403 3.302 0l8.117 15.964C22.45 20.36 21.544 22 20.116 22H3.883c-1.427 0-2.334-1.64-1.65-2.984Z"/></g></svg> <p>Unauthorized access</p>', "403");
        }
        $this->dbswitch();
        $dist = $this->session->userdata('dist_code');
        $data['locations'] = $this->CommonModel->getLocations($dist);
        $data['sub_divs'] = $this->db->select('loc_name,dist_code,subdiv_code')
            ->where(array('dist_code' => $dist, 'subdiv_code !=' => '00', 'cir_code' => '00'))
            ->get('location')->result_array();

        if ($type && $type == 'f') {
            $data['_view'] = 'nc_village/dc/villages_sm_f';
        } else {
            $data['_view'] = 'nc_village/dc/villages_g';
        }
        $this->load->view('layout/layout', $data);
    }

    public function getVillagesG()
    {
        $dist_code = $this->input->post('dist_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $filter = $this->input->post('filter');

        $this->dataswitch();
        $query = "select l.loc_name,ncv.lm_verified_at,ncv.status,ncv.application_no,ncv.lm_note,ncv.co_verified,ncv.co_note,ncv.dc_verified,ncv.co_verified_at,ncv.dc_note, ncv.id as nc_village_id,ncv.case_type from nc_villages ncv join location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code
                 and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and ncv.vill_townprt_code = l.vill_townprt_code";

        $query = $query . " where ncv.dist_code='$dist_code'";
        if ($subdiv_code) {
            $query = $query . " and ncv.subdiv_code = '$subdiv_code'";
        }
        if ($cir_code) {
            $query = $query . " and ncv.cir_code = '$cir_code'";
        }
        if ($mouza_pargona_code) {
            $query = $query . " and ncv.mouza_pargona_code = '$mouza_pargona_code'";
        }
        if ($lot_no) {
            $query = $query . " and ncv.lot_no = '$lot_no'";
        }
        if ($filter == 'pending') {
            $query = $query . " and ncv.status='G'";
        }
        if ($filter == 'dc_verified') {
            $query = $query . " and ncv.dc_verified = 'Y'";
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
    public function getVillagesSmf(){
        $dist_code = $this->input->post('dist_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');

        $this->dataswitch();
        $query = "select l.loc_name,ncv.lm_verified_at,ncv.status,ncv.application_no,ncv.lm_note,ncv.co_verified,ncv.co_note,ncv.dc_verified,ncv.co_verified_at,ncv.dc_note, ncv.id as nc_village_id,ncv.jds_verified_at,jds_note from nc_villages ncv join location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code
                 and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and ncv.vill_townprt_code = l.vill_townprt_code";

        $query = $query . " where ncv.dist_code='$dist_code'";
        if ($subdiv_code) {
            $query = $query . " and ncv.subdiv_code = '$subdiv_code'";
        }
        if ($cir_code) {
            $query = $query . " and ncv.cir_code = '$cir_code'";
        }
        if ($mouza_pargona_code) {
            $query = $query . " and ncv.mouza_pargona_code = '$mouza_pargona_code'";
        }
        if ($lot_no) {
            $query = $query . " and ncv.lot_no = '$lot_no'";
        }
        $query = $query . " and ncv.status='f'";
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
    public function showDags()
    {
        if ($this->session->userdata('usertype') != 7) {
            show_error('<svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><g fill="none" stroke="#FF0000" stroke-linecap="round" stroke-width="2"><path d="M12 9v5m0 3.5v.5"/><path stroke-linejoin="round" d="M2.232 19.016L10.35 3.052c.713-1.403 2.59-1.403 3.302 0l8.117 15.964C22.45 20.36 21.544 22 20.116 22H3.883c-1.427 0-2.334-1.64-1.65-2.984Z"/></g></svg> <p>Unauthorized access</p>', "403");
        }
        $this->dbswitch();
        $application_no = $_GET['application_no'];
        $dist_code = $this->session->userdata('dist_code');

        $nc_village = $this->db->query("select * from nc_villages where application_no='$application_no' and dist_code='$dist_code'")->row();
        if (!$nc_village) {
            show_404();
            return;
        }
        $data['locations'] = $this->CommonModel->getLocations(
            $dist_code,
            $nc_village->subdiv_code,
            $nc_village->cir_code,
            $nc_village->mouza_pargona_code,
            $nc_village->lot_no,
            $nc_village->vill_townprt_code
        );
        $q3 = "select count(*) as count from nc_village_dags where dist_code=? AND subdiv_code=? AND cir_code=?
        	AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? and dc_verified IS NULL and dc_chitha_sign IS NULL";
        $check_unverified_dag = $this->db->query($q3, array(
            $dist_code,
            $nc_village->subdiv_code,
            $nc_village->cir_code,
            $nc_village->mouza_pargona_code,
            $nc_village->lot_no,
            $nc_village->vill_townprt_code,
        ))->row()->count;
        $data['change_vill'] = $this->db->get_where('change_vill_name', array('uuid' => $data['locations']['village']['uuid']))->row();
        $data['verified'] = 'N';
        if ($check_unverified_dag == 0) {
            $data['verified'] = 'Y';
        }

        $data['d'] = $dist_code;
        $data['s'] = $nc_village->subdiv_code;
        $data['c'] = $nc_village->cir_code;
        $data['m'] = $nc_village->mouza_pargona_code;
        $data['l'] = $nc_village->lot_no;
        $data['v'] = $nc_village->vill_townprt_code;
        $data['for_dc'] = 'Y';

        $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/apiGetMap";

        $method = 'POST';
        $output = $this->callApi($url, $method, $data);
        $data['map'] = array();
        $data['old_maps'] = array();
        if (sizeof($output->data) != 0) {
            $data['map'] = $output->data;
        }
        if (sizeof($output->old_maps) != 0) {
            $data['old_maps'] = $output->old_maps;
        }

        $data['map_row'] = $output->map_row;

        $data['base'] = $this->config->item('base_url');

        $data['nc_village'] = $nc_village;
        $data['approve_proposal'] = $this->db->select('proposal_no')
            ->where(array('dist_code' => $dist_code, 'id' => $nc_village->proposal_id, 'user_type' => 'CO', 'status' => 'A'))
            ->get('nc_village_proposal')->row();
        $data['application_no'] = $application_no;

        $merge_village_requests = $this->db->where('nc_village_id', $nc_village->id)->get('merge_village_requests')->result_array();
        if (count($merge_village_requests)) {
            foreach ($merge_village_requests as $key => $merge_village_request) {
                $merge_village_requests[$key]['vill_loc'] = $vill_loc = $this->CommonModel->getLocations($merge_village_request['request_dist_code'], $merge_village_request['request_subdiv_code'], $merge_village_request['request_cir_code'], $merge_village_request['request_mouza_pargona_code'], $merge_village_request['request_lot_no'], $merge_village_request['request_vill_townprt_code']);
            }
        }
        $data['merge_village_requests'] = $merge_village_requests;

        $data['_view'] = 'nc_village/dc/village_g_dags';
        $this->load->view('layout/layout', $data);
    }
    public function getShowDagsVillage()
    {
        $this->dbswitch();
        $application_no = $this->input->post('application_no');
        $dist_code = $this->session->userdata('dist_code');

        $nc_village = $this->db->query("select * from nc_villages where application_no='$application_no' and dist_code='$dist_code'")->row();
        echo json_encode($nc_village);
    }
    /** View Map */
    public function viewUploadedMap()
    {
        if ($this->session->userdata('usertype') != 7) {
            show_error('<svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><g fill="none" stroke="#FF0000" stroke-linecap="round" stroke-width="2"><path d="M12 9v5m0 3.5v.5"/><path stroke-linejoin="round" d="M2.232 19.016L10.35 3.052c.713-1.403 2.59-1.403 3.302 0l8.117 15.964C22.45 20.36 21.544 22 20.116 22H3.883c-1.427 0-2.334-1.64-1.65-2.984Z"/></g></svg> <p>Unauthorized access</p>', "403");
        }
        $id = $data['id'] = $_GET['id'];
        $dist_code = $data['d'] = $this->session->userdata('dist_code');

        $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/apiGetMapBase64";

        $method = 'POST';
        $output = $this->callApi($url, $method, $data);
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
    /** View Map */
    public function getMapForSign()
    {
        if ($this->session->userdata('usertype') != 7) {
            show_error('<svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><g fill="none" stroke="#FF0000" stroke-linecap="round" stroke-width="2"><path d="M12 9v5m0 3.5v.5"/><path stroke-linejoin="round" d="M2.232 19.016L10.35 3.052c.713-1.403 2.59-1.403 3.302 0l8.117 15.964C22.45 20.36 21.544 22 20.116 22H3.883c-1.427 0-2.334-1.64-1.65-2.984Z"/></g></svg> <p>Unauthorized access</p>', "403");
        }
        $id = $data['id'] = $_GET['id'];
        $dist_code = $data['d'] = $this->session->userdata('dist_code');

        $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/apiGetMapBase64";

        $method = 'POST';
        $output = $this->callApi($url, $method, $data);
        if ($output->data) {
            if ($output->data->base64 != null) {
                header("Content-type: " . $output->data->mime);
                $data = $output->data->base64;
                $map_dir_path = $output->data->map_dir_path;
                $mime = $output->data->mime;
                $map_dir_path_arr = explode('/', $map_dir_path);
                $map_dir = NC_VILLAGE_MAP_DIR . $map_dir_path_arr[count($map_dir_path_arr) - 2];
                $map_path = $map_dir . '/' . $map_dir_path_arr[count($map_dir_path_arr) - 1];
                $map_path_unsigned = $map_dir . '/map_path_unsigned-' . $map_dir_path_arr[count($map_dir_path_arr) - 1];
                if ((!file_exists($map_dir) == true)) {
                    mkdir($map_dir, 0777, true);
                }
                if ((file_put_contents($map_path, base64_decode($data)) !== false) && (file_put_contents($map_path_unsigned, base64_decode($data)) !== false)) {
                    $this->session->set_userdata('map_dir_path', $map_path);
                    $this->session->set_userdata('nc_map_mime', $mime);
                    $this->session->set_userdata('map_file_name', $map_dir_path_arr[count($map_dir_path_arr) - 1]);
                    echo base64_decode($data);
                    die;
                } else {
                    echo "Unable to save the map..!";
                    return;
                }
            } else {
                echo "Map Not Found..!";
                return;
            }
        }
        echo "Map Not Found..!";
    }
    public function getMapBase()
    {
        $id = $data['id'] = $this->UtilsModel->cleanPattern($this->input->post('id'));
        $base64 = base64_encode(file_get_contents(API_LINK_ILRMS . 'index.php/nc_village/NcCommonController/getUploadedMapBase?id=' . $id));
        echo $base64;
    }

    /** API */
    public static function callApi($url, $method = 'GET', $data = null)
    {
        $curl = curl_init();
        if ($method == 'POST') {
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_POSTFIELDS => http_build_query($data),
                CURLOPT_VERBOSE => 1,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded',
                ),
            ));
        } else {
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                ),
            ));
        }

        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($httpcode != 200) {
            $arr = (object) array(
                'data' => array(),
                'status_code' => 404,
            );
            return $arr;
        }
        if ($response) {
            return json_decode($response);
        } else {
            $arr = (object) array(
                'data' => array(),
                'status_code' => 404,
            );
            return $arr;
        }
    }

    /** get Dags  */
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
        $co = $this->db->query("select * from users where dist_code='$nc_village->dist_code' and subdiv_code='$nc_village->subdiv_code' and cir_code='$nc_village->cir_code' and user_code='$nc_village->co_code'")->row();
        $dags = $this->CommonModel->getNcVillageDags($application_no);
        foreach ($dags as $dag) {
            $dag->occupiers = $this->DagReportModel->occupierNames($dag->dist_code, $dag->subdiv_code, $dag->cir_code, $dag->mouza_pargona_code, $dag->lot_no, $dag->vill_townprt_code, $dag->dag_no);
        }
        echo json_encode(array('dags' => $dags, 'co_name' => $co ? $co->username : ''));

        return;
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
            ->update('nc_village_dags', array('dc_verified' => 'Y', 'updated_at' => date('Y-m-d H:i:s'), 'dc_verified_at' => date('Y-m-d H:i:s')));

        if ($this->db->affected_rows() > 0) {
            echo json_encode(array(
                'submitted' => 'Y',
                'application_no' => $application_no,
                'msg' => 'Dag Successfully Verified.',
            ));
        } else {
            log_message("error", 'NC_Village_DC_Pass: ' . json_encode('#NC0095 Unable to Submit.' . $this->db->last_query()));
            echo json_encode(array(
                'submitted' => 'N',
                'application_no' => $application_no,
                'msg' => '#NC0095 Unable to verify dag.',
            ));
        }
    }

    /** DC certify */
    public function certifyVillage()
    {
        $application_no = $this->input->post('application_no');
        $user_code = $this->session->userdata('user_code');
        $dc_certification = $this->UtilsModel->cleanPattern($this->input->post('dc_certification'));
        $dc_remark = $this->UtilsModel->cleanPattern($this->input->post('remark'));
        $change_vill_remark = $this->UtilsModel->cleanPattern($this->input->post('change_vill_remark'));
        $remarks = "DC Remark: <br>" . $dc_remark . "<br>Village Name Change:<br>" . $change_vill_remark;

        $this->form_validation->set_rules('application_no', 'Application NO', 'trim|required');
        if ($this->form_validation->run() == false) {
            echo json_encode(array('errors' => validation_errors(), 'st' => 0));
            return;
        }
        $this->dataswitch();
        $sign_verify = $this->db->query("select count(*) as count from nc_villages where
			application_no = '$application_no' and dc_chitha_sign='Y'")->row()->count;
        if ($sign_verify == 0) {
            echo json_encode(array(
                'submitted' => 'N',
                'msg' => 'Please sign draft chitha.',
                'nc_village' => $nc_village = $this->db->query("select * from nc_villages where application_no='$application_no'")->row(),
            ));
            return;
        }
        $this->db->trans_begin();
        $this->db->where('application_no', $application_no)
            ->update(
                'nc_villages',
                [
                    'pre_user' => 'DC',
                    'cu_user' => 'DLR',
                    'dc_code' => $user_code,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'dc_verified' => 'Y',
                    'dc_verified_at' => date('Y-m-d H:i:s'),
                    'dc_certification' => trim($dc_certification),
                    'dc_note' => trim($dc_remark),
                    'status' => 'K',
                    'dlr_verified' => null,
                    'dc_proposal' => null,
                ]
            );

        if ($this->db->affected_rows() > 0) {
            $proceeding_id_max = $this->db->query("select max(proceeding_id) as id from settlement_proceeding where case_no = '$application_no'")->row()->id;

            $insPetProceed = array(
                'case_no' => $application_no,
                'proceeding_id' => !$proceeding_id_max ? 1 : (int) $proceeding_id_max + 1,
                'date_of_hearing' => date('Y-m-d h:i:s'),
                'next_date_of_hearing' => date('Y-m-d h:i:s'),
                'note_on_order' => trim($remarks),
                'status' => 'K',
                'user_code' => $this->session->userdata('user_code'),
                'date_entry' => date('Y-m-d h:i:s'),
                'operation' => 'E',
                'ip' => $_SERVER['REMOTE_ADDR'],
                'office_from' => 'DC',
                'office_to' => 'DLR',
                'task' => 'Village verified by DC',
            );
            $this->db->insert('settlement_proceeding', $insPetProceed);

            $this->db->set([
                'cu_user_code' => "DC",
                'prev_user_code' => "CO",
                'status' => "P",
                'date_of_update_dc' => date('Y-m-d H:i:s'),
                'dc_user_code' => $this->session->userdata('user_code'),
            ]);

            $this->db->where('uuid', $this->input->post('uuid'));
            $this->db->update('change_vill_name');

            if ($this->db->affected_rows() > 0) {
                if ($this->db->trans_status() === false) {
                    $this->db->trans_rollback();
                    log_message("error", 'NC_Village_DC_Update: ' . json_encode('#NC00056 Unable to update data.'));

                    echo json_encode(array(
                        'submitted' => 'N',
                        'msg' => '#NC00056 Transactions Failed.',
                    ));
                    return;
                } else {
                    $this->db->trans_commit();
                    echo json_encode(array(
                        'submitted' => 'Y',
                        'msg' => 'Successfully Submitted, Forwarded to Director of Land Records and Surveys.',
                        'nc_village' => $nc_village = $this->db->query("select * from nc_villages where application_no='$application_no'")->row(),
                    ));
                    return;
                }
            } else {
                $this->db->trans_rollback();
                log_message("error", 'NC_Village_DC_Update: ' . json_encode('#NCCV00001 Unable to update data.'));

                echo json_encode(array(
                    'submitted' => 'N',
                    'msg' => '#NC00056 Transactions Failed.',
                ));
                return;
            }
        } else {
            log_message("error", 'NC_Village_DC_Pass: ' . json_encode('#NC00055 Unable to Submit.' . $this->db->last_query()));
            echo json_encode(array(
                'submitted' => 'N',
                'msg' => '#NC00055 Unable to Submit.',
            ));
            return;
        }
    }

    /** DC revert back to CO */
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
        $this->db->trans_begin();
        $this->db->where('application_no', $application_no)
            ->update(
                'nc_villages',
                [
                    'pre_user' => 'DC',
                    'cu_user' => 'CO',
                    'dc_code' => $user_code,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'dc_note' => trim($remark),
                    'status' => 'J',
                    'proposal_id' => null,
                    'co_proposal' => null,
                    'co_verified' => null,
                    'dc_verified' => null,
                    'dc_verified_at' => null,
                    'dc_chitha_sign' => null,
                ]
            );

        $this->db->where('application_no', $application_no)
            ->update(
                'nc_village_dags',
                [
                    'updated_at' => date('Y-m-d H:i:s'),
                    'co_verified' => null,
                    'co_verified_at' => null,
                    'dc_verified' => null,
                    'dc_verified_at' => null,
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
                'status' => 'J',
                'user_code' => $this->session->userdata('user_code'),
                'date_entry' => date('Y-m-d h:i:s'),
                'operation' => 'E',
                'ip' => $_SERVER['REMOTE_ADDR'],
                'office_from' => 'DC',
                'office_to' => 'CO',
                'task' => 'Village Reverted by DC',
            );
            $this->db->insert('settlement_proceeding', $insPetProceed);

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                log_message("error", 'NC_Village_DC_Save: ' . json_encode('#NC00036 Unable to insert data.'));

                echo json_encode(array(
                    'submitted' => 'N',
                    'msg' => '#NC00036 Transactions Failed.',
                ));
                return;
            } else {
                $this->db->trans_commit();
                echo json_encode(array(
                    'submitted' => 'Y',
                    'msg' => 'Successfully Reverted Back to CO.',
                    'nc_village' => $nc_village = $this->db->query("select * from nc_villages where application_no='$application_no'")->row(),
                ));
                return;
            }
        } else {
            log_message("error", 'NC_Village_DC_Pass: ' . json_encode('#NC00035 Unable to Submit.' . $this->db->last_query()));
            echo json_encode(array(
                'submitted' => 'N',
                'msg' => '#NC00035 Unable to Submit.',
            ));
            return;
        }
    }

    public function getBaseSFile()
    {
        $pdfFilePath = FCPATH . $this->input->post('pdf_url');
        // Check if the file exists
        if (file_exists($pdfFilePath)) {
            // Read the PDF file
            //            $pdfData = file_get_contents($pdfFilePath);

            //new
            $dist_code = $this->UtilsModel->cleanPattern($this->input->post('dist_code'));
            $cir_code = $this->UtilsModel->cleanPattern($this->input->post('cir_code'));
            $subdiv_code = $this->UtilsModel->cleanPattern($this->input->post('subdiv_code'));
            $mouza_pargona_code = $this->UtilsModel->cleanPattern($this->input->post('mouza_pargona_code'));
            $lot_no = $this->UtilsModel->cleanPattern($this->input->post('lot_no'));
            $vill_townprt_code = $this->UtilsModel->cleanPattern($this->input->post('vill_townprt_code'));
            $application_no = $this->UtilsModel->cleanPattern($this->input->post('application_no'));
            $dc_certification = $this->UtilsModel->cleanPattern($this->input->post('dc_certification'));
            $user_code = $this->session->userdata('user_code');

            $this->dataswitch();
            $this->db->where('application_no', $application_no)
                ->update(
                    'nc_villages',
                    [
                        'dc_code' => $user_code,
                        'dc_verified_at' => date('Y-m-d H:i:s'),
                        'dc_certification' => $dc_certification,
                    ]
                );

            $pdfData = $this->dcRegenerateChitha($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code);

            ini_set("pcre.backtrack_limit", "500000000");
            ini_set('max_execution_time', '0');
            ini_set('memory_limit', '-1');
            include 'vendor/mpdf/vendor/autoload.php';
            $mpdf = new \Mpdf\Mpdf([
                'default_font_size' => 9,
                'default_font' => 'dejavusans',
                'orientation' => 'P',
                'format' => 'A4',
            ]);
            $mpdf->autoScriptToLang = true;
            $mpdf->autoLangToFont = true;
            $mpdf->writeHTML($pdfData);
            header('Content-type: application/pdf');
            ob_clean();
            $mpdf->Output($pdfFilePath, 'F');

            $pdfData = file_get_contents($pdfFilePath);

            //end new

            // Encode the PDF data as Base64
            $base64EncodedPDF = base64_encode($pdfData);

            // Output the Base64 encoded PDF or store it as needed
            echo json_encode($base64EncodedPDF);
        } else {
            echo json_encode("PDF file not found.");
        }
    }
    public function storeSignedPdf()
    {
        $this->dataswitch();
        $pdfFilePath = FCPATH . $this->input->post('pdf_url');
        $application_no = $this->input->post('application_no');
        $sign_key = $this->input->post('sign_key');
        $pdfbase = $this->input->post('pdfbase');
        $pdf_content = base64_decode($pdfbase);

        if ($pdfbase !== false) {

            $pdf_path = $pdfFilePath;

            // Save the PDF content to the file
            if (file_put_contents($pdf_path, $pdf_content) !== false) {

                $this->db->where('application_no', $application_no)
                    ->update('nc_villages', array(
                        'dc_chitha_sign' => 'Y',
                        'sign_key' => $sign_key,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ));
                $nc_village = $this->db->query("select * from nc_villages where application_no='$application_no'")->row();
                if ($this->db->affected_rows() > 0) {
                    echo json_encode(array(
                        'status' => '1',
                        'update' => '1',
                        'msg' => 'Pdf signed successfully',
                        'nc_village' => $nc_village,
                    ));
                    return;
                } else {
                    echo json_encode(array(
                        'status' => '1',
                        'update' => '0',
                        'msg' => 'Pdf signed successfully, But update failed.',
                    ));
                    return;
                }
            } else {
                echo json_encode(array(
                    'status' => '0',
                    'update' => '0',
                    'msg' => 'Failed Pdf signing',
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

    /** DC Re generate Chitha and save */
    public function dcRegenerateChitha($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code)
    {
        $this->dbswitch();
        $this->load->model('NcVillageModel');
        $loc['dist_code'] = $district_code = $dist_code;
        $loc['subdiv_code'] = $subdivision_code = $subdiv_code;
        $loc['cir_code'] = $circlecode = $cir_code;
        $loc['mouza_pargona_code'] = $mouzacode = $mouza_pargona_code;
        $loc['lot_no'] = $lot_code = $lot_no;
        $loc['vill_townprt_code'] = $village_code = $vill_townprt_code;

        $dag_no_max_min = $this->db->query(
            "select min(dag_no_int) as min_dag_no, max(dag_no_int) as max_dag_no from chitha_basic",
            array(
                $dist_code,
                $subdiv_code,
                $cir_code,
                $mouza_pargona_code,
                $lot_no,
                $vill_townprt_code,
            )
        )->row();

        $dag_no_lower = $dag_no_max_min->min_dag_no;
        $dag_no_upper = $dag_no_max_min->max_dag_no;

        $dist_name = $this->utilityclass->getDistrictName($district_code);
        $subdiv_name = $this->utilityclass->getSubDivName($district_code, $subdivision_code);
        $cir_name = $this->utilityclass->getCircleName($district_code, $subdivision_code, $circlecode);
        $mouza_pargona_code_name = $this->utilityclass->getMouzaName($district_code, $subdivision_code, $circlecode, $mouzacode);
        $lot_no = $this->utilityclass->getLotLocationName($district_code, $subdivision_code, $circlecode, $mouzacode, $lot_code);
        $vill_townprt_code_name = $this->utilityclass->getVillageName($district_code, $subdivision_code, $circlecode, $mouzacode, $lot_code, $village_code);

        $data['location'] = array('dist' => $dist_name, 'sub' => $subdiv_name, 'cir' => $cir_name, 'mouza' => $mouza_pargona_code_name, 'lot' => $lot_no, 'vill' => $vill_townprt_code_name);

        $chithainfo1['data'] = $this->NcVillageModel->getchithaDetailsALL($district_code, $subdivision_code, $circlecode, $mouzacode, $lot_code, $village_code, $dag_no_lower, $dag_no_upper);

        $chithainfo1['nc_village'] = $nc_village = $this->db->query("select * from nc_villages where dist_code='$district_code' and subdiv_code='$subdivision_code' and cir_code='$circlecode' and mouza_pargona_code='$mouzacode' and lot_no='$lot_code' and vill_townprt_code='$village_code'")->row();

        $chithainfo1['co_name'] = $this->db->query("select username from users where dist_code='$nc_village->dist_code' and subdiv_code='$nc_village->subdiv_code' and cir_code='$nc_village->cir_code' and user_code='$nc_village->co_code'")->row()->username;
        $chithainfo1['dc_name'] = $this->db->query("select username from users where dist_code='$nc_village->dist_code' and subdiv_code='00' and cir_code='00' and user_code='$nc_village->dc_code'")->row()->username;

        $maindataforchitha = array_merge($data, $chithainfo1);

        if ($dag_no_upper == $dag_no_lower) {
            $maindataforchitha['single_dag'] = '1';
        } else {
            $maindataforchitha['single_dag'] = '0';
        }

        $maindataforchitha['uuid'] = $this->db->query("select uuid from location where dist_code='$district_code' and subdiv_code='$subdivision_code' and cir_code='$circlecode' and mouza_pargona_code='$mouzacode' and lot_no='$lot_code' and vill_townprt_code='$village_code'")->row();

        $this->load->helper('language');
        $district_code = $this->session->userdata('dist_code');
        if (in_array($district_code, BARAK_VALLEY)) {
            $this->lang->load("bengali", "bengali");
        } else {
            $this->lang->load("assamese", "assamese");
        }

        $content = $this->load->view('svamitva_card/chitha/chitha_view_occupiers_pdf_format', $maindataforchitha, true);
        return $content;
    }

    /** reverted village from dlr */
    public function revertedVillages()
    {
        $this->dbswitch();
        $data['_view'] = 'nc_village/dc/nc_villages_reverted';
        $this->load->view('layout/layout', $data);
    }

    /** view revert village from DLR */
    public function getVillagesH()
    {
        $dist_code = $this->session->userdata('dcode');

        $this->dataswitch();
        $query = "select l.loc_name,ncv.co_verified_at,ncv.status,ncv.application_no,ncv.co_note,ncv.dc_note,ncv.dlr_note,ncv.adlr_note from nc_villages ncv join location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code
                 and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and ncv.vill_townprt_code = l.vill_townprt_code";

        $query = $query . " where ncv.dist_code='$dist_code' and ncv.status='B' and (ncv.pre_user='DLR' or ncv.pre_user='ADLR') and ncv.cu_user='DC'";

        $result = $this->db->query($query)->result();

        echo json_encode($result);
    }

    /** showProposalVillages */
    public function showProposalVillages()
    {
        $this->dbswitch('auth');
        $auth_connection = $this->db;
        $this->dbswitch();
        $dist = $this->session->userdata('dist_code');
        $data['dist_name'] = $this->db->select('loc_name,dist_code,locname_eng')->where(array(
            'dist_code' => $dist,
            'subdiv_code' => '00',
            'cir_code' => '00',
            'mouza_pargona_code' => '00',
            'lot_no' => '00',
            'vill_townprt_code' => '00000',
        ))->get('location')->row();
        $query = "select nc.*,l.loc_name,ch.old_vill_name,ch.new_vill_name from nc_villages nc join change_vill_name ch
					on nc.dist_code = ch.dist_code and nc.uuid = ch.uuid
					 join location l on nc.dist_code = l.dist_code and nc.subdiv_code = l.subdiv_code and nc.cir_code = l.cir_code
                 and nc.mouza_pargona_code = l.mouza_pargona_code and nc.lot_no = l.lot_no and nc.vill_townprt_code = l.vill_townprt_code where
					 nc.dist_code='$dist' and nc.status ='K'";

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
                    'vill_townprt_code' => $v->vill_townprt_code,
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
                    'vill_townprt_code' => $v->vill_townprt_code,
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
        $data['nc_villages'] = $nc_village;
        $data['proposal'] = $this->load->view('nc_village/dc/approval_notification', $data, true);

        $data['approve_proposal'] = $this->db->select('*')
            ->where(array('dist_code' => $dist, 'user_type' => 'DC', 'status' => 'A'))
            ->get('nc_village_proposal')->result();

        $data['_view'] = 'nc_village/dc/proposal_villages';
        $this->load->view('layout/layout', $data);
    }
    /** showProposalVillages */
    public function getProposalVillages()
    {
        $this->dbswitch('auth');
        $auth_connection = $this->db;
        $this->dbswitch();
        $dist = $this->session->userdata('dist_code');

        $query = "select nc.*,l.loc_name,ch.old_vill_name,ch.new_vill_name from nc_villages nc join change_vill_name ch
                on nc.dist_code = ch.dist_code and nc.uuid = ch.uuid
                 join location l on nc.dist_code = l.dist_code and nc.subdiv_code = l.subdiv_code and nc.cir_code = l.cir_code
             and nc.mouza_pargona_code = l.mouza_pargona_code and nc.lot_no = l.lot_no and nc.vill_townprt_code = l.vill_townprt_code where
                 nc.dist_code='$dist' and nc.status ='K'";

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
                    'vill_townprt_code' => $v->vill_townprt_code,
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
                    'vill_townprt_code' => $v->vill_townprt_code,
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
        echo json_encode($nc_village);
    }
    public function generateProposal()
    {
        $content = $this->load->view('svamitva_card/chitha/chitha_view_occupiers_pdf_format', $maindataforchitha, true);
        ini_set("pcre.backtrack_limit", "500000000");
        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '-1');
        include 'vendor/mpdf/vendor/autoload.php';
        $mpdf = new \Mpdf\Mpdf([
            'default_font_size' => 9,
            'default_font' => 'dejavusans',
            'orientation' => 'P',
            'format' => 'A4',
        ]);

        mkdir(FCPATH . NC_VILLAGE_CHITHA_PDF_DIR . $file_name, 0777, true);
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        $mpdf->writeHTML($content);

        header('Content-type: application/pdf');
        ob_clean();
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

        $data['dist_name'] = $this->db->select('loc_name,dist_code,locname_eng')->where(array(
            'dist_code' => $dist,
            'subdiv_code' => '00',
            'cir_code' => '00',
            'mouza_pargona_code' => '00',
            'lot_no' => '00',
            'vill_townprt_code' => '00000',
        ))->get('location')->row();
        $query = "select nc.*,l.loc_name,ch.old_vill_name,ch.new_vill_name from nc_villages nc join change_vill_name ch
					on nc.dist_code = ch.dist_code and nc.uuid = ch.uuid
					 join location l on nc.dist_code = l.dist_code and nc.subdiv_code = l.subdiv_code and nc.cir_code = l.cir_code
                 and nc.mouza_pargona_code = l.mouza_pargona_code and nc.lot_no = l.lot_no and nc.vill_townprt_code = l.vill_townprt_code where
					 nc.dist_code='$dist' and nc.status ='K' and nc.application_no in ($case_nos_str)";

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
                    'vill_townprt_code' => $v->vill_townprt_code,
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
                    'vill_townprt_code' => $v->vill_townprt_code,
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

        $data['nc_villages'] = $nc_village;

        $content = $this->load->view('nc_village/dc/approval_notification', $data, true);
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

        $file_name = "PR_" . $dist . "_" . $file_id . "_NC";
        $this->session->set_userdata('dc_proposal_file_name', $file_name);
        if (!is_dir(FCPATH . NC_VILLAGE_PROPOSAL_DIR . 'dc')) {
            mkdir(FCPATH . NC_VILLAGE_PROPOSAL_DIR . 'dc', 0777, true);
        }
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        $mpdf->writeHTML($content);

        header('Content-type: application/pdf');
        ob_clean();

        $file_path = FCPATH . NC_VILLAGE_PROPOSAL_DIR . 'dc' . '/' . $file_name . '.pdf';
        $mpdf->Output(FCPATH . NC_VILLAGE_PROPOSAL_DIR . 'dc' . '/' . $file_name . '.pdf', 'F');

        $pdfData = file_get_contents($file_path);
        $base64EncodedPDF = base64_encode($pdfData);
        echo json_encode($base64EncodedPDF);
    }
    public function storeSignedProposal()
    {
        $case_nos_arr = $this->session->userdata('proposal_case_nos');
        $this->dataswitch();
        $pdfFilePath = FCPATH . NC_VILLAGE_PROPOSAL_DIR . 'dc' . '/' . $this->session->userdata('dc_proposal_file_name') . '.pdf';
        $proposal_no = $this->session->userdata('dc_proposal_file_name');
        $sign_key = $this->input->post('sign_key');
        $pdfbase = $this->input->post('pdfbase');
        $pdf_content = base64_decode($pdfbase);

        $dist = $this->session->userdata('dist_code');

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
                            'user_type' => 'DC',
                            'sign_key' => $sign_key,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'status' => 'A',
                        ));
                } else {
                    $this->db->insert('nc_village_proposal', array(
                        'proposal_no' => $proposal_no,
                        'user_code' => $this->session->userdata('user_code'),
                        'dist_code' => $this->session->userdata('dist_code'),
                        'user_type' => 'DC',
                        'sign_key' => $sign_key,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s'),
                        'status' => 'A',
                    ));

                    $lastid = $this->db->insert_id();

                    $this->db->where('dist_code', $dist)
                        ->where('status', 'K')
                        ->where_in('application_no', $case_nos_arr)
                        ->update('nc_villages', array('status' => 'I', 'dc_proposal' => 'Y', 'dc_proposal_id' => $lastid));
                }
                if ($this->db->trans_status() === false) {
                    $this->db->trans_rollback();
                    log_message("error", 'NC_Village_DC_PROPOSAL_Update: ' . json_encode('#NCPROPDC0001 Unable to update data.'));
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
    public function sendProposalSignByPass()
    {
        $case_nos_arr = $this->session->userdata('proposal_case_nos');
        $this->dataswitch();
        $proposal_no = $this->session->userdata('dc_proposal_file_name');

        $dist = $this->session->userdata('dist_code');

        $is_exists = $this->db->get_where('nc_village_proposal', ['proposal_no' => $proposal_no])->num_rows();
        $this->db->trans_begin();
        if ($is_exists) {
            $this->db->where('proposal_no', $proposal_no)
                ->update('nc_village_proposal', array(
                    'user_code' => $this->session->userdata('user_code'),
                    'dist_code' => $this->session->userdata('dist_code'),
                    'user_type' => 'DC',
                    'sign_key' => 'tested',
                    'updated_at' => date('Y-m-d H:i:s'),
                    'status' => 'A',
                ));
        } else {
            $this->db->insert('nc_village_proposal', array(
                'proposal_no' => $proposal_no,
                'user_code' => $this->session->userdata('user_code'),
                'dist_code' => $this->session->userdata('dist_code'),
                'user_type' => 'DC',
                'sign_key' => 'tested',
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'status' => 'A',
            ));

            $lastid = $this->db->insert_id();

            $this->db->where('dist_code', $dist)
                ->where('status', 'K')
                ->where_in('application_no', $case_nos_arr)
                ->update('nc_villages', array('status' => 'I', 'dc_proposal' => 'Y', 'dc_proposal_id' => $lastid));
        }
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            log_message("error", 'NC_Village_DC_PROPOSAL_Update: ' . json_encode('#NCPROPDC0001 Unable to update data.'));
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

    public function storeSignedMap()
    {
        $this->dataswitch();
        $sign_key = $this->input->post('sign_key');
        $pdfbase = $this->input->post('pdfbase');
        $id = $this->input->post('id');

        if ($pdfbase !== false) {

            $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/storeSignedMap";
            $method = 'POST';
            $data = ['id' => $id, 'sign_key' => $sign_key, 'pdfbase' => $pdfbase];
            $output = callIlrmsApi($url, $method, $data);
            $response = $output;

            echo json_encode($response);
        } else {
            echo json_encode(array(
                'status' => '0',
                'update' => '0',
                'msg' => 'Invalid base64-encoded PDF content',
            ));
            return;
        }
    }
    /** View Map */
    public function viewUploadedMapBase()
    {
        if ($this->session->userdata('usertype') != 7) {
            show_error('<svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><g fill="none" stroke="#FF0000" stroke-linecap="round" stroke-width="2"><path d="M12 9v5m0 3.5v.5"/><path stroke-linejoin="round" d="M2.232 19.016L10.35 3.052c.713-1.403 2.59-1.403 3.302 0l8.117 15.964C22.45 20.36 21.544 22 20.116 22H3.883c-1.427 0-2.334-1.64-1.65-2.984Z"/></g></svg> <p>Unauthorized access</p>', "403");
        }
        $id = $data['id'] = $this->input->post('map_id');
        $dist_code = $data['d'] = $this->session->userdata('dist_code');
        header('Content-type: application/pdf');

        $map_path = $this->session->userdata('map_dir_path');
        $pdfData = file_get_contents($map_path);
        $base64EncodedPDF = base64_encode($pdfData);
        echo json_encode($base64EncodedPDF);
        return;

        $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/apiGetMapBase64";

        $method = 'POST';
        $output = $this->callApi($url, $method, $data);
        if ($output->data) {
            if ($output->data->base64 != null) {
                // header("Content-type: " . $output->data->mime);
                $data = $output->data->base64;
                echo json_encode($data);
                die;
            } else {
                echo "Map Not Found..!";
                return;
            }
        }
        echo "Map Not Found..!";
    }
    public function getProposalBase()
    {
        $pdfFilePath = FCPATH . NC_VILLAGE_PROPOSAL_DIR . 'dc' . '/' . $this->session->userdata('dc_proposal_file_name') . '.pdf';
        header("Content-Type: application/pdf");
        echo (file_get_contents($pdfFilePath));
        return;
    }

    /** reverted village from dlr */
    public function jdsVillages()
    {
        $this->dbswitch();
        $data['_view'] = 'nc_village/dc/nc_villages_forwarded_jds';
        $this->load->view('layout/layout', $data);
    }
    public function getVillagesM()
    {
        $dist_code = $this->session->userdata('dcode');

        $this->dataswitch();
        $query = "select l.loc_name,ncv.co_verified_at,ncv.status,ncv.application_no,ncv.co_note,ncv.dc_note,ncv.dlr_note from nc_villages ncv join location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code
                 and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and ncv.vill_townprt_code = l.vill_townprt_code";

        $query = $query . " where ncv.dist_code='$dist_code' and ncv.status='M'";

        $result = $this->db->query($query)->result();

        echo json_encode($result);
    }
}
