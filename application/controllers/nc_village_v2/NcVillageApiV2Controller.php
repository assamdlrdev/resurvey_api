<?php
include APPPATH . '/libraries/CommonTrait.php';

class NcVillageApiV2Controller extends CI_Controller
{
    use CommonTrait;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('UtilsModel');
        $this->load->model('NcVillageModel');
        // $this->load->model('ChangeVillageModel');
        // $this->load->model('Chithamodel');
        $this->load->model('CommonModel');
        // $this->load->model('DagReportModel');
    }

    public function get_users(){
        $desig_code = $this->input->post('desig_code');
        $dist_code = $this->input->post('dist_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');

        if(empty($dist_code)){
            return response_json(['success' => false, 'message' => 'No district found'], 403);
        }

        $this->dbswitch($dist_code);

        switch($desig_code){
            case 'DC':
                $query = "SELECT l.use_name as user_name, l.user_code, u.username as name FROM loginuser_table as l JOIN users as u on l.user_code = u.user_code and l.dist_code=u.dist_code and l.subdiv_code=u.subdiv_code and l.cir_code=u.cir_code WHERE u.user_desig_code='$desig_code' and l.dist_code='$dist_code' and l.dis_enb_option='E' and l.user_map='y'";

                break;
            case 'CO':
                $query = "SELECT l.use_name as user_name, l.user_code, u.username as name FROM loginuser_table as l JOIN users as u on l.user_code = u.user_code and l.dist_code=u.dist_code and l.subdiv_code=u.subdiv_code and l.cir_code=u.cir_code WHERE u.user_desig_code='$desig_code' and l.dist_code='$dist_code' and l.subdiv_code='$subdiv_code' and l.cir_code='$cir_code' and l.dis_enb_option='E' and l.user_map='y'";

                break;
            case 'SK':
                $query = "SELECT l.use_name as user_name, l.user_code, u.username as name FROM loginuser_table as l JOIN users as u on l.user_code = u.user_code and l.dist_code=u.dist_code and l.subdiv_code=u.subdiv_code and l.cir_code=u.cir_code WHERE u.user_desig_code='$desig_code' and l.dist_code='$dist_code' and l.subdiv_code='$subdiv_code' and l.cir_code='$cir_code' and l.dis_enb_option='E' and l.user_map='y'";

                break;
            case 'LM':
                $query = "SELECT l.use_name as user_name, l.user_code, u.lm_name as name FROM loginuser_table as l JOIN lm_code as u on l.user_code = u.lm_code and l.dist_code=u.dist_code and l.subdiv_code=u.subdiv_code and l.cir_code=u.cir_code and l.mouza_pargona_code=u.mouza_pargona_code and l.lot_no=u.lot_no WHERE l.dist_code='$dist_code' and l.subdiv_code='$subdiv_code' and l.cir_code='$cir_code' and l.mouza_pargona_code='$mouza_pargona_code' and l.lot_no='$lot_no' and l.dis_enb_option='E' and l.user_map='y'";

                break;
            default:
                return response_json(['success' => false, 'message' => 'Wrong desig code passed'],403);

        }

        $login_users = $this->db->query($query)->result_array();

        if(count($login_users)){
            foreach($login_users as &$login_user){
                $login_user['designation'] = $desig_code;
            }
        }

        echo json_encode(['success' => true, 'users' => $login_users, 'message' => 'Users fetched successfully']);
        exit;
        return response_json(['success' => true, 'users' => $login_users, 'message' => 'Users fetched successfully'], 200);
    }

    public function get_user(){
        $desig_code = $this->input->post('desig_code');
        $dist_code = $this->input->post('dist_code');
        $user_code = $this->input->post('user_code');

        if(empty($dist_code)){
            return response_json(['success' => false, 'message' => 'No district found'], 403);
        }

        $this->dbswitch($dist_code);

        switch($desig_code){
            case 'DC':
                $query = "SELECT l.use_name as user_name, l.user_code, u.username as name FROM loginuser_table as l JOIN users as u on l.user_code = u.user_code and l.dist_code=u.dist_code and l.subdiv_code=u.subdiv_code and l.cir_code=u.cir_code WHERE u.user_desig_code='$desig_code' and l.dist_code='$dist_code' and l.user_code='$user_code' and l.dis_enb_option='E' and l.user_map='y'";

                break;
            case 'CO':
                $query = "SELECT l.use_name as user_name, l.user_code, u.username as name FROM loginuser_table as l JOIN users as u on l.user_code = u.user_code and l.dist_code=u.dist_code and l.subdiv_code=u.subdiv_code and l.cir_code=u.cir_code WHERE u.user_desig_code='$desig_code' and l.dist_code='$dist_code' and l.user_code='$user_code' and l.dis_enb_option='E' and l.user_map='y'";

                break;
            case 'SK':
                $query = "SELECT l.use_name as user_name, l.user_code, u.username as name FROM loginuser_table as l JOIN users as u on l.user_code = u.user_code and l.dist_code=u.dist_code and l.subdiv_code=u.subdiv_code and l.cir_code=u.cir_code WHERE u.user_desig_code='$desig_code' and l.dist_code='$dist_code' and l.user_code='$user_code' and l.dis_enb_option='E' and l.user_map='y'";

                break;
            case 'LM':
                $query = "SELECT l.use_name as user_name, l.user_code, u.lm_name as name FROM loginuser_table as l JOIN lm_code as u on l.user_code = u.lm_code and l.dist_code=u.dist_code and l.subdiv_code=u.subdiv_code and l.cir_code=u.cir_code and l.mouza_pargona_code=u.mouza_pargona_code and l.lot_no=u.lot_no WHERE l.dist_code='$dist_code' and l.user_code='$user_code' and l.dis_enb_option='E' and l.user_map='y'";

                break;
            default:
                return response_json(['success' => false, 'message' => 'Wrong desig code passed'],403);

        }

        $login_user = $this->db->query($query)->row_array();

        if(empty($login_user)){
            return response_json(['success' => false, 'message' => 'No such user found.'], 403);
        }
        $login_user['designation'] = $desig_code;

        return response_json(['success' => true, 'user' => $login_user, 'message' => 'Users fetched successfully'], 200);
    }    

	/** getDlrPendingProposal */
    public function getDlrPendingProposal()
    {
        $d_list = (array) $this->input->post('d');

        foreach ($d_list as $k => $d) {
            $this->dbswitch($d['dist_code']);
            $dist_code = $d['dist_code'];
            $d_list[$k]['count'] = $this->db->query("select count(*) as count from nc_villages where
 				dist_code='$dist_code' and status = 'L' and dlr_proposal is null")->row()->count;
        }

        return response_json(['success' => true, 'data' => $d_list, 'message' => 'Village fetched successfully'], 200);
    }

    /** apiDlrProposal */
    public function apiDlrProposal()
    {
        $this->dbswitch('auth');
        $auth_connection = $this->db;
        $this->load->model('CommonModel');
        $dist_code = $this->UtilsModel->cleanPattern($this->input->post('d'));
        $this->dbswitch($dist_code);
        $data['locations'] = $this->CommonModel->getLocations($dist_code);
        $query = "select nc.*,l.loc_name,ch.old_vill_name,ch.new_vill_name from nc_villages nc join change_vill_name ch
					on nc.dist_code = ch.dist_code and nc.uuid = ch.uuid
					 join location l on nc.dist_code = l.dist_code and nc.subdiv_code = l.subdiv_code and nc.cir_code = l.cir_code
                 and nc.mouza_pargona_code = l.mouza_pargona_code and nc.lot_no = l.lot_no and nc.vill_townprt_code = l.vill_townprt_code where
					 nc.dist_code='$dist_code'and nc.status ='L'";

        $data['villages'] = $nc_village = $this->db->query($query)->result();

        foreach ($nc_village as $k => $v) {
            $nc_village[$k]->circle_name = $this->db->select('loc_name,dist_code,subdiv_code,cir_code,mouza_pargona_code,locname_eng')
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

            $total_lessa = $this->totalLessa($vill_area->total_bigha, $vill_area->total_katha, $vill_area->total_lessa);
            $nc_village[$k]->total_b_k_l = $this->Total_Bigha_Katha_Lessa($total_lessa);

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
            if(count($merge_village_requests)){
                foreach($merge_village_requests as $key1 => $merge_village_request){
                    $vill_loc = $this->CommonModel->getLocations($merge_village_request['request_dist_code'], $merge_village_request['request_subdiv_code'], $merge_village_request['request_cir_code'], $merge_village_request['request_mouza_pargona_code'], $merge_village_request['request_lot_no'], $merge_village_request['request_vill_townprt_code']);
                    array_push($merge_village_name_arr, $vill_loc['village']['loc_name']);
                    $merge_village_requests[$key1]['village_name'] = $vill_loc['village']['loc_name'];
                    $merge_village_requests[$key1]['vill_loc'] = $vill_loc;
                }
            }
            $nc_village[$k]->merge_village_names = implode(', ', $merge_village_name_arr);
            $nc_village[$k]->merge_village_requests = $merge_village_requests;
            $nc_village[$k]->is_end_village_cadastral_village = ($v->case_type == 'NC_TO_C') ? TRUE : FALSE;

            $dc_proposal = $this->db->where('id', $v->dc_proposal_id)->get('nc_village_proposal')->row();
            $nc_village[$k]->dc_proposal_no = $dc_proposal ? $dc_proposal->proposal_no : '';
        }

        $this->db->select('id, dist_code, proposal_no, created_at, updated_at, status, proposal_note');
        $this->db->where('dist_code', $dist_code);
        $this->db->where_not_in('status', ['E', 'R']);
        $this->db->where_in('user_type', ['DLR', 'ADLR']); // Using where_in to specify multiple values for user_type
        $this->db->order_by('updated_at', 'desc');
        $query = $this->db->get('nc_village_proposal');
        $data['approve_proposal']  = $query->result();

        $data['nc_village'] = $nc_village;

        echo json_encode($data);
        return;
    }

    public function apiDlrRevert()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE'])) {
            $contentType = $_SERVER['CONTENT_TYPE'];

            if (strcasecmp($contentType, 'application/x-www-form-urlencoded') === 0) {
                $dist_code = $this->UtilsModel->cleanPattern($this->input->post('dist_code'));
                $application_no = $this->UtilsModel->cleanPattern($this->input->post('application_no'));
                $dlr_note = $this->UtilsModel->cleanPattern($this->input->post('dlr_note'));
                $user_code = $this->UtilsModel->cleanPattern($this->input->post('user_code'));
                $user = $this->input->post('user');
                $pre_user = $this->input->post('pre_user');
                $pre_user_dig = $this->input->post('pre_user_dig');
                $cur_user = $this->input->post('cur_user');
                $cur_user_dig = $this->input->post('cur_user_dig');

                if ($user == 'ADLR') {
                    $from = 'ADLR';
                    $task = 'Village Reverted by ADLR';
                } elseif ($user == 'DLR') {
                    $from = 'DLR';
                    $task = 'Village Reverted by DLRS';
                }

                $this->dbswitch($dist_code);

                $this->db->trans_begin();

                $proceeding_id_max = $this->db->query("select max(proceeding_id) as id from settlement_proceeding where case_no = '$application_no'")->row()->id;
                $insPetProceed = array(
                    'case_no' => $application_no,
                    'proceeding_id' => !$proceeding_id_max ? 1 : (int) $proceeding_id_max + 1,
                    'date_of_hearing' => date('Y-m-d h:i:s'),
                    'next_date_of_hearing' => date('Y-m-d h:i:s'),
                    'note_on_order' => $dlr_note,
                    'status' => 'B',
                    'user_code' => $user_code,
                    'date_entry' => date('Y-m-d h:i:s'),
                    'operation' => 'E',
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'office_from' => $from,
                    'office_to' => 'DC',
                    'task' => $task,
                );
                $this->db->insert('settlement_proceeding', $insPetProceed);

                if ($user == 'ADLR') {
                    $this->db->where('application_no', $application_no)
                        ->update(
                            'nc_villages',
                            [
                                'updated_at' => date('Y-m-d H:i:s'),
                                'dc_proposal' => null,
                                'dc_verified' => null,
                                'dc_verified_at' => null,
                                'dlr_note' => $dlr_note,
                                'dlr_user_code' => $user_code,
                                'status' => 'B',
                                'pre_user' => $from,
                                'cu_user' => 'DC',
                            ]
                        );
                } elseif ($user == 'DLR') {
                    $this->db->where('application_no', $application_no)
                        ->update(
                            'nc_villages',
                            [
                                'updated_at' => date('Y-m-d H:i:s'),
                                'dc_proposal' => null,
                                'dc_verified' => null,
                                'dc_verified_at' => null,
                                'adlr_note' => $dlr_note,
                                'adlr_user_code' => $user_code,
                                'status' => 'B',
                                'pre_user' => $from,
                                'cu_user' => 'DC',
                            ]
                        );
                }

                if ($this->db->affected_rows() > 0) {
                    $nc_village = $this->db->query("SELECT * FROM nc_villages WHERE application_no='".$application_no."'")->row();
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
                                        'pre_user' => $pre_user, 
                                        'cur_user' => $cur_user, 
                                        'pre_user_dig' => $pre_user_dig, 
                                        'cur_user_dig' => $cur_user_dig, 
                                        'remark' => $dlr_note,
                                        'application_no' => $nc_village->application_no,
                                        'proceeding_type' => 0,
                                    ];

                    $output = $this->NcVillageModel->callApiV2($url, $method, $update_data);
                    $response = $output ? json_decode($output, true) : [];
                    if($response['status'] == 1){
                        $this->db->trans_commit();
                        $arr = array(
                            'data' => 'Y',
                            'nc_village' => $nc_village = $this->db->query("select * from nc_villages where application_no='$application_no'")->row(),
                            'status_code' => 200,
                        );
                        echo json_encode($arr);
                        return;
                    }

                }

                $this->db->trans_rollback();

                $arr = array(
                    'data' => 'N',
                    'status_code' => 200,
                );
                echo json_encode($arr);
            } else {
                $arr = array(
                    'data' => [],
                    'status_code' => 404
                );
                echo json_encode($arr);
            }
        } else {
            $arr = array(
                'data' => [],
                'status_code' => 404
            );
            echo json_encode($arr);
        }
    }

        /** DLR forward to JDS */
    public function dlrForwardToJds()
    {
        $dist_code = $this->UtilsModel->cleanPattern($this->input->post('dist_code'));
        $application_no = $this->UtilsModel->cleanPattern($this->input->post('application_no'));
        $dlr_note = $this->UtilsModel->cleanPattern($this->input->post('dlr_note'));
        $user_code = $this->UtilsModel->cleanPattern($this->input->post('user_code'));
        $uuid = $this->input->post('uuid');
        $user = $this->input->post('user');
        $pre_user = $this->input->post('pre_user');
        $pre_user_dig = $this->input->post('pre_user_dig');
        $cur_user = $this->input->post('cur_user');
        $cur_user_dig = $this->input->post('cur_user_dig');

        $this->dbswitch($dist_code);

        if ($user == 'DLR') {
            $from = 'DLR';
            $task = 'Village forwarded to JDS by DLR';
        }

        $proceeding_id_max = $this->db->query("select max(proceeding_id) as id from settlement_proceeding where case_no = '$application_no'")->row()->id;
        $this->db->trans_start();

        $insPetProceed = array(
            'case_no' => $application_no,
            'proceeding_id' => !$proceeding_id_max ? 1 : (int) $proceeding_id_max + 1,
            'date_of_hearing' => date('Y-m-d h:i:s'),
            'next_date_of_hearing' => date('Y-m-d h:i:s'),
            'note_on_order' => $dlr_note,
            'status' => 'P',
            'user_code' => $user_code,
            'date_entry' => date('Y-m-d h:i:s'),
            'operation' => 'E',
            'ip' => $_SERVER['REMOTE_ADDR'],
            'office_from' => $from,
            'office_to' => 'JDS',
            'task' => $task,
        );
        $this->db->insert('settlement_proceeding', $insPetProceed);

        if ($user == 'DLR') {
            $this->db->where('application_no', $application_no)
                ->update(
                    'nc_villages',
                    [
                        'updated_at' => date('Y-m-d H:i:s'),
                        //						'dlr_verified' => 'Y',
                        'dlr_verified_at' => date('Y-m-d H:i:s'),
                        'dlr_note' => $dlr_note,
                        'dlr_user_code' => $user_code,
                        'status' => 'P',
                        'pre_user' => $from,
                        'cu_user' => 'JDS',
                    ]
                );
        }

        if ($this->db->affected_rows() > 0) {
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
                                'pre_user' => $pre_user, 
                                'cur_user' => $cur_user, 
                                'pre_user_dig' => $pre_user_dig, 
                                'cur_user_dig' => $cur_user_dig, 
                                'remark' => $dlr_note,
                                'application_no' => $nc_village->application_no,
                                'proceeding_type' => 3,
                            ];

            $output = $this->NcVillageModel->callApiV2($url, $method, $update_data);
            $response = $output ? json_decode($output, true) : [];
            if($response['status'] == 1){
                $this->db->trans_complete();
                $arr = array(
                    'data' => 'Y',
                    'nc_village' => $nc_village = $this->db->query("select * from nc_villages where application_no='$application_no'")->row(),
                    'status_code' => 200,
                );
                echo json_encode($arr);
                return;
            }
            /*
            $this->db->trans_complete();
            $arr = array(
                'data' => 'Y',
                'nc_village' => $nc_village = $this->db->query("select * from nc_villages where application_no='$application_no'")->row(),
                'status_code' => 200,
            );
            echo json_encode($arr);
            return;
            */
        }

        $arr = array(
            'data' => 'N',
            'nc_village' => $nc_village = $this->db->query("select * from nc_villages where application_no='$application_no'")->row(),
            'status_code' => 200,
        );
        echo json_encode($arr);
    }

    /** get Proposal from adlr and jds */
    public function getDlrForwardedVillages()
    {
        // Copied from old process
        $data = [];
        foreach (json_decode(NC_DISTIRTCS) as $dist_code) {
            $data_dist['dist_code'] = $dist_code;
            $this->dbswitch($dist_code);
            $query = "select 
                ll.loc_name as dist_name, 
                l.loc_name as village_name,
                lc.loc_name as circle_name,
                ncv.created_at,
                ncv.status,
                ncv.dist_code,
                ncv.subdiv_code,
                ncv.cir_code,
                ncv.mouza_pargona_code,
                ncv.lot_no,
                ncv.vill_townprt_code,
                ncv.application_no,
                ncv.uuid,
                ncv.dc_verified,
                ncv.dc_verified_at,
                ncv.dc_note,
                ncv.jds_verified,
                ncv.jds_verified_at,
                ncv.jds_note,
                ncv.ads_verified,
                ncv.ads_verified_at,
                ncv.ads_note,
                ncv.dlr_note 
            from nc_villages ncv 
            join location l 
                on ncv.dist_code = l.dist_code 
                and ncv.subdiv_code = l.subdiv_code 
                and ncv.cir_code = l.cir_code
                and ncv.mouza_pargona_code = l.mouza_pargona_code 
                and ncv.lot_no = l.lot_no 
                and ncv.vill_townprt_code = l.vill_townprt_code
            join location ll 
                on ncv.dist_code = ll.dist_code 
                and ll.subdiv_code='00'
            join location lc 
                on ncv.dist_code = lc.dist_code 
                and ncv.subdiv_code = lc.subdiv_code 
                and ncv.cir_code = lc.cir_code 
                and lc.mouza_pargona_code = '00' 
                and lc.lot_no = '00' 
                and lc.vill_townprt_code = '00000'
            where ncv.status='P' and ncv.app_version='V2'
            order by ncv.created_at desc";
            $pendings = $this->db->query($query)->result();
            foreach ($pendings as $p) {
                $data[] = $p;
            }
        }
        echo json_encode($data);
        return;
    }

    /** DLR forward to JDS */
    public function jdsRevertToDlr()
    {
        $remark = $this->UtilsModel->cleanPattern($this->input->post('remark'));
        $dist_code = $this->UtilsModel->cleanPattern($this->input->post('dist_code'));
        $application_no = $this->UtilsModel->cleanPattern($this->input->post('application_no'));
        $user_code = $this->UtilsModel->cleanPattern($this->input->post('user_code'));
        $user = $this->input->post('user');
        $pre_user = $this->input->post('pre_user');
        $pre_user_dig = $this->input->post('pre_user_dig');
        $cur_user = $this->input->post('cur_user');
        $cur_user_dig = $this->input->post('cur_user_dig');

        $this->dbswitch($dist_code);

        if ($user == 'JDS') {
            $from = 'JDS';
            $task = 'Village reverted back to DLR by JDS';
        }

        $proceeding_id_max = $this->db->query("select max(proceeding_id) as id from settlement_proceeding where case_no = '$application_no'")->row()->id;
        $this->db->trans_start();

        $insPetProceed = array(
            'case_no' => $application_no,
            'proceeding_id' => !$proceeding_id_max ? 1 : (int) $proceeding_id_max + 1,
            'date_of_hearing' => date('Y-m-d h:i:s'),
            'next_date_of_hearing' => date('Y-m-d h:i:s'),
            'note_on_order' => 'JDS reverted back to dlr',
            'status' => 'P',
            'user_code' => $user_code,
            'date_entry' => date('Y-m-d h:i:s'),
            'operation' => 'E',
            'ip' => $_SERVER['REMOTE_ADDR'],
            'office_from' => $from,
            'office_to' => 'JDS',
            'task' => $task,
        );
        $this->db->insert('settlement_proceeding', $insPetProceed);

        if ($user == 'JDS') {
            $this->db->where('application_no', $application_no)
                ->update(
                    'nc_villages',
                    [
                        'updated_at' => date('Y-m-d H:i:s'),
                        'jds_revert_note_to_dlr' => $remark,
                        'dlr_verified_at' => NULL,
                        'dlr_note' => '',
                        'dlr_user_code' => '',
                        'status' => 'L', // There will two proceedings at DLRS end hence making the status 'L' instead of 'I'
                        'pre_user' => $from,
                        'cu_user' => 'DLR',
                    ]
                );
        }

        if ($this->db->affected_rows() > 0) {
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
                                'pre_user' => $pre_user, 
                                'cur_user' => $cur_user, 
                                'pre_user_dig' => $pre_user_dig, 
                                'cur_user_dig' => $cur_user_dig, 
                                'remark' => $remark,
                                'application_no' => $nc_village->application_no,
                                'proceeding_type' => 1,
                            ];

            $output = $this->NcVillageModel->callApiV2($url, $method, $update_data);
            $response = $output ? json_decode($output, true) : [];
            if($response['status'] == 1){
                $this->db->trans_complete();
                echo json_encode('Y');
                return;
            }
        }

        echo json_encode('N');
        return;
    }

    /** Total Lessa */
    public function totalLessa($bigha, $katha, $lessa)
    {
        $total_lessa = $lessa + ($katha * 20) + ($bigha * 100);
        return $total_lessa;
    }

    /** Bigha Katha Lessa */
    public function Total_Bigha_Katha_Lessa($total_lessa)
    {
        $bigha = $total_lessa / 100;
        $rem_lessa = fmod($total_lessa, 100);
        $katha = $rem_lessa / 20;
        $r_lessa = fmod($rem_lessa, 20);
        $mesaure = array();
        $mesaure[] .= floor($bigha);
        $mesaure[] .= floor($katha);
        $mesaure[] .= $r_lessa;
        return $mesaure;
    }

    public function apiGetPendingVillage()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE'])) {
            $contentType = $_SERVER['CONTENT_TYPE'];

            if (strcasecmp($contentType, 'application/x-www-form-urlencoded') === 0) {
                $dist_code = $this->UtilsModel->cleanPattern($this->input->post('d'));
                $application_no = $this->UtilsModel->cleanPattern($this->input->post('application_no'));
                $for_user = $this->input->post('for_user');

                $this->dbswitch($dist_code);

                $nc_village = $this->db->query("select * from nc_villages where
				application_no='$application_no' and dist_code='$dist_code'")->row();

                if($nc_village){
                    $merge_village_requests = $this->db->where('nc_village_id', $nc_village->id)->get('merge_village_requests')->result_array();
                    $merge_village_name_arr = [];
                    if(count($merge_village_requests)){
                        foreach($merge_village_requests as $key1 => $merge_village_request){
                            $vill_loc = $this->CommonModel->getLocations($merge_village_request['request_dist_code'], $merge_village_request['request_subdiv_code'], $merge_village_request['request_cir_code'], $merge_village_request['request_mouza_pargona_code'], $merge_village_request['request_lot_no'], $merge_village_request['request_vill_townprt_code']);
                            array_push($merge_village_name_arr, $vill_loc['village']['loc_name']);
                            $merge_village_requests[$key1]['village_name'] = $vill_loc['village']['loc_name'];
                            $merge_village_requests[$key1]['vill_loc'] = $vill_loc;
                        }
                    }
                    $nc_village->merge_village_names = implode(', ', $merge_village_name_arr);
                    $nc_village->merge_village_requests = $merge_village_requests;
                    $data['nc_village'] = $nc_village;

                    $data['pdf_base64'] = null;
                    $pdfFilePath = FCPATH . $nc_village->chitha_dir_path;
                    if (file_exists($pdfFilePath)) {
                        $pdfData = file_get_contents($pdfFilePath);
                        $data['pdf_base64'] = base64_encode($pdfData);
                    }
                    $data['proposal_pdf_base64'] = null;

                    if(!empty($for_user)){
                        if($for_user == 'SO'){
                            $approve_proposal = $this->db->select('proposal_no')
                                ->where(array(
                                    'dist_code' => $dist_code,
                                    'id' => $nc_village->dlr_proposal_id,
                                    'user_type' => 'DLR',
                                    'status' => 'A'
                                ))
                                ->get('nc_village_proposal')->row();
                            $data['approved_proposal'] = $approve_proposal;
                        }else if($for_user == 'ASO'){
                            if($nc_village->select_village_on_prop_sign_from_so == 0){
                                $approve_proposal = $this->db->select('proposal_no')
                                    ->where(array(
                                        'dist_code' => $dist_code,
                                        'id' => $nc_village->dlr_proposal_id,
                                        'user_type' => 'DLR',
                                        'status' => 'A'
                                    ))
                                    ->get('nc_village_proposal')->row();
                                $data['approved_proposal'] = $approve_proposal;
                            }else{
                                $approve_proposal = $this->db->select('proposal_no')
                                    ->where(array(
                                        'dist_code' => $dist_code,
                                        'id' => $nc_village->section_officer_proposal_id,
                                        'user_type' => 'SO',
                                        'status' => 'a'
                                    ))
                                    ->get('nc_village_proposal')->row();
                                $data['approved_proposal'] = $approve_proposal;
                            }
                        }
                    }else{
                        $approve_proposal = $this->db->select('proposal_no')
                            ->where(array(
                                'dist_code' => $dist_code,
                                'id' => $nc_village->dc_proposal_id,
                                'user_type' => 'DC',
                                'status' => 'A'
                            ))
                            ->get('nc_village_proposal')->row();

                        $proposalpdfFilePath = FCPATH . NC_VILLAGE_PROPOSAL_DIR . "dc/" . $approve_proposal->proposal_no . '.pdf';
                        if (file_exists($proposalpdfFilePath)) {
                            $proposalpdfData = file_get_contents($proposalpdfFilePath);
                            $data['proposal_pdf_base64'] = base64_encode($proposalpdfData);
                        }
                        $data['approved_proposal'] = $approve_proposal;
                    }

                    $data['locations'] = $this->CommonModel->getLocations(
                        $nc_village->dist_code,
                        $nc_village->subdiv_code,
                        $nc_village->cir_code,
                        $nc_village->mouza_pargona_code,
                        $nc_village->lot_no,
                        $nc_village->vill_townprt_code
                    );
                }else{
                    $data = [
                                'nc_village' => null,
                                'pdf_base64' => null,
                                'proposal_pdf_base64' => null,
                                'approved_proposal' => null,
                                'locations' => []
                            ];
                }

                $arr = array(
                    'data' => $data,
                    'status_code' => 200,
                );
                echo json_encode($arr);
            } else {
                $arr = array(
                    'data' => [],
                    'status_code' => 404
                );
                echo json_encode($arr);
            }
        } else {
            $arr = array(
                'data' => [],
                'status_code' => 404
            );
            echo json_encode($arr);
        }
    }

    /** get dags for department */
    public function apiGetDags()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE'])) {
            $contentType = $_SERVER['CONTENT_TYPE'];

            if (strcasecmp($contentType, 'application/x-www-form-urlencoded') === 0) {
                $dist_code = $this->UtilsModel->cleanPattern($this->input->post('d'));
                $application_no = $this->UtilsModel->cleanPattern($this->input->post('application_no'));

                $this->dbswitch($dist_code);

                $data['dc'] = $this->db->query("select * from users where dist_code='$dist_code' and subdiv_code='00'
					and cir_code='00' and user_desig_code='DC'")->row();

                $dags = $data['dags'] = $this->CommonModel->getNcVillageDags($application_no);

                $uuid = $this->db->select('uuid')->where(array('dist_code' => $dist_code, 'subdiv_code' => $dags[0]->subdiv_code, 'cir_code' => $dags[0]->cir_code, 'mouza_pargona_code' => $dags[0]->mouza_pargona_code, 'lot_no' => $dags[0]->lot_no, 'vill_townprt_code' => $dags[0]->vill_townprt_code))->get('location')->row_array();

                $data['change_vill'] = $this->db->get_where('change_vill_name', array('uuid' => $uuid['uuid']))->row();

                $arr = array(
                    'data' => $data,
                    'status_code' => 200,
                );
                echo json_encode($arr);
            } else {
                $arr = array(
                    'data' => [],
                    'status_code' => 404
                );
                echo json_encode($arr);
            }
        } else {
            $arr = array(
                'data' => [],
                'status_code' => 404
            );
            echo json_encode($arr);
        }
    }

    /** apiDlrProposalVillageWise */
    public function apiDlrProposalVillageWise()
    {
        $this->load->model('CommonModel');
        $dist_code = $this->UtilsModel->cleanPattern($this->input->post('d'));
        $village_id = implode(",", $this->input->post('village_id'));

        $this->dbswitch($dist_code);
        $data['user'] = $user = $this->input->post('user');
        $data['locations'] = $this->CommonModel->getLocations($dist_code);

        $query = "select nc.*,l.loc_name,ch.old_vill_name,ch.new_vill_name from nc_villages nc join change_vill_name ch
					on nc.dist_code = ch.dist_code and nc.uuid = ch.uuid
					 join location l on nc.dist_code = l.dist_code and nc.subdiv_code = l.subdiv_code and nc.cir_code = l.cir_code
                 and nc.mouza_pargona_code = l.mouza_pargona_code and nc.lot_no = l.lot_no and nc.vill_townprt_code = l.vill_townprt_code where
					 nc.id IN ($village_id) and nc.dist_code='$dist_code'and nc.status ='L'";

        $data['villages'] = $nc_village = $this->db->query($query)->result();

        foreach ($nc_village as $k => $v) {
            $nc_village[$k]->circle_name = $this->db->select('loc_name,dist_code,subdiv_code,cir_code,mouza_pargona_code,locname_eng')
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

            $total_lessa = $this->totalLessa($vill_area->total_bigha, $vill_area->total_katha, $vill_area->total_lessa);
            $nc_village[$k]->total_b_k_l = $this->Total_Bigha_Katha_Lessa($total_lessa);

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
            if(count($merge_village_requests)){
                foreach($merge_village_requests as $key1 => $merge_village_request){
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
        $data['proposal'] = $this->load->view('nc_village/dlr/approval_notification', $data, true);

        echo json_encode($data);
        return;
    }

    public function updateDlrProposal()
    {
        $proposal_no = $this->input->post('proposal_no');
        $sign_key = $this->input->post('sign_key');
        $dist_code = $this->input->post('dist_code');
        $user_code = $this->input->post('user_code');
        $user_type = $this->input->post('user_type');
        $village_id = $this->input->post('village_id');
        $forward_to = $this->input->post('forward_to');
        $dlr_note = $this->input->post('dlr_note');
        $forward_to_user_type = $this->input->post('forward_to_user_type');
        $forwarded_to_user = NULL;

        $this->dbswitch($dist_code);

        if ($forward_to == 'M') {
            $status = 'B';
            $from = 'DLRS';
            $to = 'ADLR';
            $task = 'Forwarded to ADLR';
        } else if ($forward_to == 'A') {
            $status = 'A';
            if ($user_type == 'DLR') {
                $from = 'DLRS';
                $to = 'Senior Most Secretary';
                $task = 'Forwarded to Senior Most Secretary';
            } elseif ($user_type == 'ADLR') {
                $from = 'ADLR';
                $to = 'Senior Most Secretary';
                $task = 'Forwarded to Senior Most Secretary';
            }
        }else if(!in_array($forward_to, ['', 'M', 'A'])){
            $forwarded_to_user = $forward_to;
            $status = 'A';
            $from = 'DLR';
            $to = 'Section Officer (Survey & Settlement)';
            $task = 'Proposal forwarded to Section Officer (Survey & Settlement).';
        }else {
            echo json_encode(array(
                            'status' => '0',
                        ));
                        return;
        }

        $data_array = [
                        "forwarded_to_user_type" => !empty($forward_to_user_type) ? $forward_to_user_type : NULL,
                        "forwarded_to" => !empty($forwarded_to_user) ? $forwarded_to_user : NULL,
                        'user_code' => $user_code,
                        'user_type' => $user_type,
                        'sign_key' => $sign_key,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'status' => $status,
                        'proposal_note' => $dlr_note,
                    ];
        if(!in_array($forward_to, ['', 'M', 'A'])){
            $data_array = $data_array + ["reverted" => null];
        }

        $is_exists = $this->db->get_where('nc_village_proposal', ['proposal_no' => $proposal_no])->num_rows();
        $this->db->trans_begin();
        if ($is_exists) {
            $this->db->where('proposal_no', $proposal_no)
                ->update('nc_village_proposal', $data_array);
        } else {
            $data_array = $data_array + [
                                            'proposal_no' => $proposal_no,
                                            'sign_key' => $sign_key,
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'dist_code' => $dist_code,
                                        ];
            
            $this->db->insert('nc_village_proposal', $data_array);

            $lastid = $this->db->insert_id();

            foreach ($village_id as $v) {
                $this->db->where('dist_code', $dist_code)
                    ->where('status', 'L')
                    ->where('id', $v)
                    ->update('nc_villages', array('status' => 'A', 'dlr_proposal' => 'Y', 'dlr_proposal_id' => $lastid));

                if ($this->db->affected_rows() != 1) {
                    $this->db->trans_rollback();
                    log_message("error", 'NC_Village_DLR_PROPOSAL_Update: ' . json_encode('#NCPROPDLR0051 Unable to update data.'));
                    echo json_encode(array(
                        'status' => '0',
                    ));
                    break;
                    return;
                }
            }
        }
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            log_message("error", 'NC_Village_DLR_PROPOSAL_Update: ' . json_encode('#NCPROPDLR0001 Unable to update data.'));
            echo json_encode(array(
                'status' => '0',
            ));
            return;
        } else {
            $this->db->trans_commit();
            $return =  $this->insertProceeding($proposal_no, $user_code, $dlr_note, $from, $to, $task, $dist_code);
            echo json_encode(array(
                'status' => '1',
            ));
            return;
        }
    }

    public function getNcVillageData(){
        $village_ids = $this->input->post('village_ids');
        $dist_code = $this->input->post('dist_code');
        $this->dbswitch($dist_code);

        $data = [];
        foreach ($village_ids as $village_id) {
            $data[] = $this->db->where('dist_code', $dist_code)
                    ->where('id', $village_id)
                    ->get('nc_villages')->row();            
        }

        echo json_encode(array(
            'status' => '1',
            'data' => $data,
        ));
        return;
    }

    /** Insert Proceeding  */
    public function insertProceeding($proposal_no, $user_code, $note, $from, $to, $task, $dist_code)
    {
        $this->dbswitch($dist_code);
        $proceeding_id_max = $this->db->query("select max(proceeding_id) as id from settlement_proceeding where case_no = '$proposal_no'")->row()->id;
        $insPetProceed = array(
            'case_no' => $proposal_no,
            'proceeding_id' => !$proceeding_id_max ? 1 : (int) $proceeding_id_max + 1,
            'date_of_hearing' => date('Y-m-d h:i:s'),
            'next_date_of_hearing' => date('Y-m-d h:i:s'),
            'note_on_order' => $note,
            'status' => 'A',
            'user_code' => $user_code,
            'date_entry' => date('Y-m-d h:i:s'),
            'operation' => 'E',
            'ip' => $_SERVER['REMOTE_ADDR'],
            'office_from' => $from,
            'office_to' => $to,
            'task' => $task,
        );
        $data = $this->db->insert('settlement_proceeding', $insPetProceed);
        return $data;
    }

    /** apiProposal */
    public function apiProposal()
    {
        $dist_code = $this->UtilsModel->cleanPattern($this->input->post('d'));
        $user_type = $this->input->post('user_type');
        $this->load->model('CommonModel');
        $this->dbswitch($dist_code);
        $data['locations'] = $this->CommonModel->getLocations($dist_code);

        $nc_village = null;
        if($user_type == 'section_officer'){
            $query = "select nc.*,l.loc_name,ch.old_vill_name,ch.new_vill_name from nc_villages nc join change_vill_name ch on nc.dist_code = ch.dist_code and nc.uuid = ch.uuid join location l on nc.dist_code = l.dist_code and nc.subdiv_code = l.subdiv_code and nc.cir_code = l.cir_code and nc.mouza_pargona_code = l.mouza_pargona_code and nc.lot_no = l.lot_no and nc.vill_townprt_code = l.vill_townprt_code where nc.dist_code='$dist_code'and nc.status ='a' and nc.section_officer is not null and nc.section_officer_verified='Y'";
            $data['villages'] = $nc_village = $this->db->query($query)->result();
        }else if($user_type == 'asst_section_officer'){
            $query = "select nc.*,l.loc_name,ch.old_vill_name,ch.new_vill_name from nc_villages nc join change_vill_name ch on nc.dist_code = ch.dist_code and nc.uuid = ch.uuid join location l on nc.dist_code = l.dist_code and nc.subdiv_code = l.subdiv_code and nc.cir_code = l.cir_code and nc.mouza_pargona_code = l.mouza_pargona_code and nc.lot_no = l.lot_no and nc.vill_townprt_code = l.vill_townprt_code where nc.dist_code='$dist_code'and nc.status ='c' and nc.asst_section_officer is not null and nc.asst_section_officer_verified='Y'";
            
            $data['villages'] = $nc_village = $this->db->query($query)->result();
            // log_message('error', '##' . $this->db->last_query());
        }
        
        // if(empty($nc_village)){
        //     echo json_encode(['status_code' => 404, 'message' => 'No data found.']);
        //     return;
        // }

        foreach ($nc_village as $k => $v) {
            $nc_village[$k]->circle_name = $this->db->select('loc_name,dist_code,subdiv_code,cir_code,mouza_pargona_code,locname_eng')
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

            $total_lessa = $this->totalLessa($vill_area->total_bigha, $vill_area->total_katha, $vill_area->total_lessa);
            $nc_village[$k]->total_b_k_l = $this->Total_Bigha_Katha_Lessa($total_lessa);

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
            if(count($merge_village_requests)){
                foreach($merge_village_requests as $key1 => $merge_village_request){
                    $vill_loc = $this->CommonModel->getLocations($merge_village_request['request_dist_code'], $merge_village_request['request_subdiv_code'], $merge_village_request['request_cir_code'], $merge_village_request['request_mouza_pargona_code'], $merge_village_request['request_lot_no'], $merge_village_request['request_vill_townprt_code']);
                    array_push($merge_village_name_arr, $vill_loc['village']['loc_name']);
                    $merge_village_requests[$key1]['village_name'] = $vill_loc['village']['loc_name'];
                    $merge_village_requests[$key1]['vill_loc'] = $vill_loc;
                }
            }
            $nc_village[$k]->merge_village_names = implode(', ', $merge_village_name_arr);
            $nc_village[$k]->merge_village_requests = $merge_village_requests;
            
            $dlr_proposal = $this->db->where('id', $v->dlr_proposal_id)->get('nc_village_proposal')->row();
            // $so_proposal = $this->db->where('id', $v->section_officer_proposal_id)->get('nc_village_proposal')->row();
            // $aso_proposal = $this->db->where('id', $v->asst_section_officer_proposal_id)->get('nc_village_proposal')->row();
            
            $nc_village[$k]->dlr_proposal_no = $dlr_proposal ? $dlr_proposal->proposal_no : '';
            // $nc_village[$k]->so_proposal_no = $so_proposal ? $so_proposal->proposal_no : '';
            // $nc_village[$k]->aso_proposal_no = $aso_proposal ? $aso_proposal->proposal_no : '';
        }

        $this->db->select('id, dist_code, proposal_no, created_at, updated_at, status, proposal_note');
        $this->db->where('dist_code', $dist_code);
        $this->db->where_not_in('status', ['E', 'R']);
        $this->db->where_in('user_type', ['DLR', 'ADLR']); // Using where_in to specify multiple values for user_type
        $this->db->order_by('updated_at', 'desc');
        $query = $this->db->get('nc_village_proposal');
        $data['approve_proposal']  = $query->result();

        $data['nc_village'] = $nc_village;
        //		$data['proposal'] = $this->load->view('nc_village/dlr/approval_notification', $data, true);

        echo json_encode($data);
        return;
    }

    public function updateProposal()
    {
        $proposal_no = $this->input->post('proposal_no');
        $sign_key = $this->input->post('sign_key');
        $dist_code = $this->input->post('dist_code');
        $user_code = $this->input->post('user_code');
        $pre_user = $this->input->post('unique_user_id');
        $cur_user_dig = $this->input->post('lower_level_desig');
        $user_type = $this->input->post('user_type');
        $village_id = $this->input->post('village_id');
        $note = $this->input->post('note');
        $type = $this->input->post('type');
        $forward_user = $this->input->post('forward_to');

        $this->dbswitch($dist_code);


        if($user_type == 'SO'){
            $data_array = [
                            'user_type' => $user_type,
                            'sign_key' => $sign_key,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'status' => 'a',
                            'reverted' => NULL,
                            // 'dlr_verified_at' => date('Y-m-d H:i:s'),
                            // 'dlr_verified' => 'Y',
                            'section_officer_proposal_note' => $note,
                            'section_officer_user_code' => $user_code,
                            'section_officer_verified_at' => date('Y-m-d H:i:s'),
                            "section_officer_verified" => 'Y',
                            "asst_section_officer_verified" => null,
                            "so_forwarded_to_aso" => $forward_user,
                        ];

            $from = 'SO';
            $to = 'Assistant Section Officer (Survey & Settlement)';
            $task = 'Proposal forwarded to Assistant Section Officer (Survey & Settlement).';
        }elseif($user_type == 'ASO'){
            $data_array = [
                            'user_type' => $user_type,
                            'sign_key' => $sign_key,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'status' => 'b',
                            'reverted' => NULL,
                            // 'dlr_verified_at' => date('Y-m-d H:i:s'),
                            // 'dlr_verified' => 'Y',
                            'section_officer_verified_at' => date('Y-m-d H:i:s'),
                            "section_officer_verified" => 'Y',
                            'asst_section_officer_proposal_note' => $note,
                            'asst_section_officer_user_code' => $user_code,
                            'asst_section_officer_verified_at' => date('Y-m-d H:i:s'),
                            // "asst_section_officer_verified" => 'Y',
                        ];

            $from = 'ASO';
            $to = 'Assistant Section Officer (Survey & Settlement)';
            $task = 'Proposal made at Assistant Section Officer (Survey & Settlement).';
        }

        $is_exists = $this->db->get_where('nc_village_proposal', ['proposal_no' => $proposal_no])->num_rows();
        $this->db->trans_begin();
        if ($is_exists) {
            $this->db->where('proposal_no', $proposal_no)
                ->update('nc_village_proposal', $data_array);
        } else {
            $data_array = $data_array + [
                                            'proposal_no' => $proposal_no,
                                            'sign_key' => $sign_key,
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'dist_code' => $dist_code,
                                        ];

            $this->db->insert('nc_village_proposal', $data_array);

            $lastid = $this->db->insert_id();

            foreach ($village_id as $v) {
                if($user_type == 'SO'){
                    if(isset($type) && $type=='revert'){
                        $this->db->where('dist_code', $dist_code)
                            ->where('status', 'b')
                            ->where('id', $v)
                            ->update('nc_villages', array('status' => 'b', 'section_officer_proposal' => 'Y', 'section_officer_proposal_id' => $lastid, 'section_officer_note' => $note));
                    } else{
                        $this->db->where('dist_code', $dist_code)
                            ->where('status', 'a')
                            ->where('id', $v)
                            ->update('nc_villages', array('status' => 'b', 'section_officer_proposal' => 'Y', 'section_officer_proposal_id' => $lastid, 'section_officer_note' => $note));
                    }

                    $response = $this->insertIlrmsNcVillageDetails($v, $proposal_no, $pre_user, $forward_user, $note, 'SO', $cur_user_dig);
                    $response = $response ? json_decode($response, true) : [];
                
                    if ($this->db->affected_rows() != 1 && $response['status'] != 1) {
                        $this->db->trans_rollback();
                        log_message("error", 'NC_Village_SO_PROPOSAL_Update: ' . json_encode('#NCPROPSO0051 Unable to update data.'). '000'.$type);
                        echo json_encode(array(
                            'status' => '0',
                        ));
                        break;
                        return;
                    }
                }elseif($user_type == 'ASO'){
                    $this->db->where('dist_code', $dist_code)
                        ->where('status', 'c')
                        ->where('id', $v)
                        ->update('nc_villages', array('status' => 'd', 'asst_section_officer_proposal' => 'Y', 'asst_section_officer_proposal_id' => $lastid));
                
                    if ($this->db->affected_rows() != 1) {
                        $this->db->trans_rollback();
                        log_message("error", 'NC_Village_ASO_PROPOSAL_Update: ' . json_encode('#NCPROPASO0051 Unable to update data.'));
                        echo json_encode(array(
                            'status' => '0',
                        ));
                        break;
                        return;
                    }
                }

            }
        }
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            log_message("error", 'NC_Village_SO_ASO_PROPOSAL_Update: ' . json_encode('#NCPROPSO_ASO0001 Unable to update data.'));
            echo json_encode(array(
                'status' => '0',
            ));
            return;
        } else {
            $this->db->trans_commit();
            $return =  $this->insertProceeding($proposal_no, $user_code, $note, $from, $to, $task, $dist_code);
            echo json_encode(array(
                'status' => '1',
            ));
            return;
        }
    }

    public function getDeptProposal()
    {
        $d_list = (array) $this->input->post('d');
        $type = $this->input->post('type');
        $user = $this->input->post('user');
        $dlr_skipped_to_another_user_module_enable = $this->input->post('dlr_skipped_to_another_user_module_enable');
        $village_selection_option_on_proposal_send_enable = $this->input->post('village_selection_option_on_proposal_send_enable');
        if(!$dlr_skipped_to_another_user_module_enable) $dlr_skipped_to_another_user_module_enable = FALSE;
        if(!$village_selection_option_on_proposal_send_enable) $village_selection_option_on_proposal_send_enable = FALSE;

        $data = array();
        foreach ($d_list as $k => $d) {
            $dist_code = $d['dist_code'];
            $this->dbswitch($dist_code);
            if ($user == 'ps' && $type == 'pending') {
                if(!$dlr_skipped_to_another_user_module_enable){
                    $pending_proposal = $this->db->query("select nc.*, l.loc_name from nc_village_proposal as nc join
                    location as l on nc.dist_code = l.dist_code where
                    nc.dist_code='$dist_code' and (nc.user_type = 'DLR' or nc.user_type = 'ADLR')
                    and nc.status ='A' and nc.ps_verified is null  and nc.reverted is null
                    and l.subdiv_code ='00' order by nc.updated_at desc")->result();
                }else{
                    $pending_proposal = $this->db->query("select nc.*, l.loc_name from nc_village_proposal as nc join
                    location as l on nc.dist_code = l.dist_code where
                    nc.dist_code='$dist_code' and (nc.user_type = 'DLR' or nc.user_type = 'ADLR')
                    and nc.status ='A' and nc.ps_verified is null and nc.forwarded_to is null and nc.reverted is null
                    and l.subdiv_code ='00' order by nc.updated_at desc")->result();
                }
                foreach ($pending_proposal as $p) {
                    $data[] = $p;
                }
            } elseif ($user == 'ps' && $type == 'verified') {
                $pending_proposal = $this->db->query("select nc.*, l.loc_name from nc_village_proposal as nc join
 				location as l on nc.dist_code = l.dist_code where
 				nc.dist_code='$dist_code' and (nc.user_type = 'DLR' or nc.user_type = 'ADLR')  and nc.status ='A'
 				and nc.ps_verified ='Y' and l.subdiv_code ='00'")->result();
                foreach ($pending_proposal as $p) {
                    $data[] = $p;
                }
            } elseif ($user == 'ps' && $type == 'reverted') {
                $pending_proposal = $this->db->query("select nc.*, l.loc_name from nc_village_proposal as nc join
 				location as l on nc.dist_code = l.dist_code where
 				nc.dist_code='$dist_code' and (nc.user_type = 'DLR' or nc.user_type = 'ADLR')  and nc.status ='A' and nc.reverted ='Y'
 				and nc.secretary_verified is null 
 				and nc.ps_verified ='Y' and l.subdiv_code ='00'")->result();
                foreach ($pending_proposal as $p) {
                    $data[] = $p;
                }
            } elseif ($user == 'secretary' && $type == 'pending') {
                $user_name = $this->input->post('user_name');
                $pending_proposal = $this->db->query("select nc.*, l.loc_name from nc_village_proposal as nc join
 				location as l on nc.dist_code = l.dist_code where
 				nc.dist_code='$dist_code' and (nc.user_type = 'DLR' or nc.user_type = 'ADLR') and nc.status ='A' and 
 				nc.reverted is null and nc.ps_forwarded_to_sec='$user_name'
 				 and nc.ps_verified ='Y' and nc.secretary_verified is null and l.subdiv_code ='00'")->result();
                foreach ($pending_proposal as $p) {
                    $data[] = $p;
                }
            } elseif ($user == 'secretary' && $type == 'verified') {
                $pending_proposal = $this->db->query("select nc.*, l.loc_name from nc_village_proposal as nc join
 				location as l on nc.dist_code = l.dist_code where
 				nc.dist_code='$dist_code' and (nc.user_type = 'DLR' or nc.user_type = 'ADLR') and nc.status ='A' 
 				 and nc.ps_verified ='Y' and nc.secretary_verified ='Y' and l.subdiv_code ='00'")->result();
                foreach ($pending_proposal as $p) {
                    $data[] = $p;
                }
            } elseif ($user == 'secretary' && $type == 'reverted') {
                $user_name = $this->input->post('user_name');
                $pending_proposal = $this->db->query("select nc.*, l.loc_name from nc_village_proposal as nc join
 				location as l on nc.dist_code = l.dist_code where
 				nc.dist_code='$dist_code' and (nc.user_type = 'DLR' or nc.user_type = 'ADLR')  
 				and nc.status ='A' and nc.reverted ='Y' and nc.ps_forwarded_to_sec='$user_name'
 				and nc.joint_secretary_verified is null and nc.secretary_verified ='Y'
 				and nc.ps_verified ='Y' and l.subdiv_code ='00'")->result();
                foreach ($pending_proposal as $p) {
                    $data[] = $p;
                }
            } elseif ($user == 'joint_secretary' && $type == 'pending') {
                $user_name = $this->input->post('user_name');
                $pending_proposal = $this->db->query("select nc.*, l.loc_name from nc_village_proposal as nc join
 				location as l on nc.dist_code = l.dist_code where
 				nc.dist_code='$dist_code' and (nc.user_type = 'DLR' or nc.user_type = 'ADLR')  and nc.status ='A'
 				and nc.ps_verified ='Y' and nc.reverted is null and nc.sec_forwarded_to_jsec='$user_name' and   
 				nc.secretary_verified ='Y' and nc.joint_secretary_verified is null and l.subdiv_code ='00'")->result();
                foreach ($pending_proposal as $p) {
                    $data[] = $p;
                }
            } elseif ($user == 'joint_secretary' && $type == 'verified') {
                $pending_proposal = $this->db->query("select nc.*, l.loc_name from nc_village_proposal as nc join
 				location as l on nc.dist_code = l.dist_code where
 				nc.dist_code='$dist_code' and (nc.user_type = 'DLR' or nc.user_type = 'ADLR') and nc.status ='A'
 				and nc.ps_verified ='Y' and
 				 nc.secretary_verified ='Y' and nc.joint_secretary_verified='Y' and l.subdiv_code ='00'")->result();
                foreach ($pending_proposal as $p) {
                    $data[] = $p;
                }
            } elseif ($user == 'joint_secretary' && $type == 'reverted') {
                $user_name = $this->input->post('user_name');
                $pending_proposal = $this->db->query("select nc.*, l.loc_name from nc_village_proposal as nc join
 				location as l on nc.dist_code = l.dist_code where
 				nc.dist_code='$dist_code' and (nc.user_type = 'DLR' or nc.user_type = 'ADLR') and nc.sec_forwarded_to_jsec='$user_name'
 				 and nc.status ='A' and nc.reverted ='Y'
 				and nc.joint_secretary_verified='Y' and nc.section_officer_verified is null and nc.secretary_verified ='Y'
 				and nc.ps_verified ='Y' and l.subdiv_code ='00'")->result();
                foreach ($pending_proposal as $p) {
                    $data[] = $p;
                }
            } elseif ($user == 'section_officer' && $type == 'pending') {
                $user_name = $this->input->post('user_name');
                if(!$dlr_skipped_to_another_user_module_enable){
                    $pending_proposal = $this->db->query("select nc.*, l.loc_name from nc_village_proposal as nc join
                    location as l on nc.dist_code = l.dist_code where
                    nc.dist_code='$dist_code' and (nc.user_type = 'DLR' or nc.user_type = 'ADLR') and nc.status ='A'
                    and nc.ps_verified ='Y' and  nc.reverted is null and nc.jsec_forwarded_to_so='$user_name' and   
                    nc.secretary_verified ='Y' and nc.joint_secretary_verified ='Y' 
                    and nc.section_officer_verified is null and l.subdiv_code ='00'")->result();
                }else{
                    $pending_proposal = $this->db->query("select nc.*, l.loc_name from nc_village_proposal as nc join
                    location as l on nc.dist_code = l.dist_code where
                    nc.dist_code='$dist_code' and (nc.user_type = 'DLR' or nc.user_type = 'ADLR') and nc.status ='A' and  nc.reverted is null and nc.forwarded_to='$user_name'
                    and nc.section_officer_verified is null and l.subdiv_code ='00'")->result();
                }
                foreach ($pending_proposal as $p) {
                    $data[] = $p;
                }
            } elseif ($user == 'section_officer' && $type == 'verified') {
                $pending_proposal = $this->db->query("select nc.*, l.loc_name from nc_village_proposal as nc join
 				location as l on nc.dist_code = l.dist_code where
 				nc.dist_code='$dist_code' and (nc.user_type = 'DLR' or nc.user_type = 'ADLR') and nc.status ='A'
 				and nc.ps_verified ='Y' and
 				nc.secretary_verified ='Y' and nc.joint_secretary_verified='Y'
			  	and nc.section_officer_verified='Y' and l.subdiv_code ='00'")->result();
                foreach ($pending_proposal as $p) {
                    $data[] = $p;
                }
                
                $forwarded_proposal = $this->db->query("select nc.*, l.loc_name from nc_village_proposal as nc join
 				location as l on nc.dist_code = l.dist_code where
 				nc.dist_code='$dist_code' and nc.user_type = 'SO' and nc.status ='a'
 				and nc.section_officer_verified ='Y' and nc.reverted is null and l.subdiv_code ='00'")->result();
                foreach ($forwarded_proposal as $p) {
                    $data[] = $p;
                }
            } elseif ($user == 'section_officer' && $type == 'reverted') {
                $user_name = $this->input->post('user_name');

                $pending_proposal = $this->db->query("select nc.*, l.loc_name from nc_village_proposal as nc join
                location as l on nc.dist_code = l.dist_code join nc_villages ncv on ncv.dlr_proposal_id = nc.id OR ncv.section_officer_proposal_id = nc.id where nc.dist_code='$dist_code'  and l.subdiv_code ='00'
                and (nc.user_type = 'DLR' or nc.user_type = 'ADLR' or nc.user_type = 'SO') 
                and nc.reverted ='Y' and (CASE WHEN user_type='SO' 
                THEN nc.status = 'a' ELSE  nc.jsec_forwarded_to_so='section_officer_assam'
                and nc.joint_secretary_verified='Y' and nc.section_officer_verified='Y' and nc.secretary_verified ='Y'
                and nc.ps_verified ='Y' and nc.status='A' END)")->result();

                foreach ($pending_proposal as $p) {
                    $data[] = $p;
                }
            } elseif ($user == 'asst_section_officer' && $type == 'pending') {
                $user_name = $this->input->post('user_name');
                if($dlr_skipped_to_another_user_module_enable){
                    if($village_selection_option_on_proposal_send_enable){
                        $pending_proposal = $this->db->query("select nvp.*, l.loc_name from nc_village_proposal as nvp join
                        location as l on nvp.dist_code = l.dist_code join nc_villages nc on nc.section_officer_proposal_id = nvp.id where nvp.dist_code='$dist_code' and nvp.user_type = 'SO' and nvp.status ='a'
                        and nvp.reverted is null and nvp.so_forwarded_to_aso='$user_name' and nvp.section_officer_verified ='Y' and nvp.asst_section_officer_verified is null and l.subdiv_code ='00'")->result();
                        
                    }else{
                        $pending_proposal = $this->db->query("select nc.*, l.loc_name from nc_village_proposal as nc join
                        location as l on nc.dist_code = l.dist_code where
                        nc.dist_code='$dist_code' and (nc.user_type = 'DLR' or nc.user_type = 'ADLR') and nc.status ='A'
                        and nc.reverted is null and nc.so_forwarded_to_aso='$user_name'
                        and nc.section_officer_verified ='Y'
                        and nc.asst_section_officer_verified is null and l.subdiv_code ='00'")->result();
                    }
                }else{
                    $pending_proposal = $this->db->query("select nc.*, l.loc_name from nc_village_proposal as nc join
                    location as l on nc.dist_code = l.dist_code where
                    nc.dist_code='$dist_code' and (nc.user_type = 'DLR' or nc.user_type = 'ADLR') and nc.status ='A'
                    and nc.ps_verified ='Y' and  nc.reverted is null and nc.so_forwarded_to_aso='$user_name' and
                    nc.secretary_verified ='Y' and nc.joint_secretary_verified ='Y' and nc.section_officer_verified ='Y'
                    and nc.asst_section_officer_verified is null and l.subdiv_code ='00'")->result();
                }
                foreach ($pending_proposal as $p) {
                    $data[] = $p;
                }
            }
        }

        echo json_encode($data);
        return;
    }

    public function getDeptProposalNew(){
        $dist_wise_locations = (array) $this->input->post('dist_wise_locations');
        
        $data = [];

        if(count($dist_wise_locations)){
            foreach($dist_wise_locations as $d_code => $dist_locations){
                if(count($dist_locations)){
                    $this->dbswitch($d_code);
                    $proposal_ids = [];
                    foreach($dist_locations as $location){
                        list($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $v_uuid, $cur_user_desig) = explode('_', $location);

                        if($cur_user_desig != ''){
                            $nc_village = $this->db->where('dist_code', $dist_code)
                                                    ->where('subdiv_code', $subdiv_code)
                                                    ->where('cir_code', $cir_code)
                                                    ->where('mouza_pargona_code', $mouza_pargona_code)
                                                    ->where('lot_no', $lot_no)
                                                    ->where('vill_townprt_code', $vill_townprt_code)
                                                    ->where('uuid', $v_uuid)
                                                    ->where('app_version', 'V2')
                                                    ->get('nc_villages')->row();
                            if($cur_user_desig == 'ASO'){
                                if($nc_village){
                                    $section_officer_proposal_id = $nc_village->section_officer_proposal_id;
                                    if(!in_array($section_officer_proposal_id, $proposal_ids)){
                                        array_push($proposal_ids, $section_officer_proposal_id);
                                    }
                                }
                            }
                        }
                    }
                    if(count($proposal_ids)){
                        foreach($proposal_ids as $proposal_id){
                            $pending_proposal = $this->db->query("select nvp.*, l.loc_name from nc_village_proposal as nvp join
                                                location as l on nvp.dist_code = l.dist_code where nvp.dist_code='$dist_code' and nvp.user_type = 'SO' and nvp.status ='a'
                                                and nvp.reverted is null and nvp.id='$proposal_id' and nvp.section_officer_verified ='Y' and nvp.asst_section_officer_verified is null and l.subdiv_code ='00'")->row();
                            if(!empty($pending_proposal)){
                                $data[] = $pending_proposal;
                            }
                        }
                    }
                }
            }
        }

        echo json_encode($data);
        return;
    }

    /** villages proposal wise */
    public function getVillagesProposalWise()
    {
        $this->load->model('CommonModel');
        $dist_code = $this->UtilsModel->cleanPattern($this->input->post('d'));
        $proposal_id = $this->input->post('proposal_id');


        $this->dbswitch($dist_code);
        $data['locations'] = $this->CommonModel->getLocations($dist_code);

        $nc_vill_prop = $this->db->where('id', $proposal_id)->get('nc_village_proposal')->row();
        
        $query = "select nc.*,l.loc_name,ch.old_vill_name,ch.new_vill_name from nc_villages nc join change_vill_name ch
					on nc.dist_code = ch.dist_code and nc.uuid = ch.uuid
					 join location l on nc.dist_code = l.dist_code and nc.subdiv_code = l.subdiv_code and nc.cir_code = l.cir_code
                 and nc.mouza_pargona_code = l.mouza_pargona_code and nc.lot_no = l.lot_no and nc.vill_townprt_code = l.vill_townprt_code where
					 nc.dist_code='$dist_code'";
        $data['dir'] = 'dlr';
        if($nc_vill_prop->user_type == 'SO'){
            $data['dir'] = 'so';
            $query .= " and nc.section_officer_proposal_id = '$proposal_id'";
        }elseif($nc_vill_prop->user_type == 'ASO'){
            $data['dir'] = 'aso';
            $query .= " and nc.asst_section_officer_proposal_id = '$proposal_id'";
        }else{
            $query .= " and nc.dlr_proposal_id = '$proposal_id'";
        }

        $data['villages'] = $nc_village = $this->db->query($query)->result();

        foreach ($nc_village as $k => $v) {
            $nc_village[$k]->circle_name = $this->db->select('loc_name,dist_code,subdiv_code,cir_code,mouza_pargona_code,locname_eng')
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

            $merge_village_name_arr = [];
            $merge_village_requests = $this->db->where('nc_village_id', $v->id)->get('merge_village_requests')->result_array();
            if(count($merge_village_requests)){
                foreach($merge_village_requests as $key1 => $merge_village_request){
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

        echo json_encode($data);
        return;
    }

    /** generate Notification asst section officer */
    public function generateNcVillNotification()
    {
        $village_selection_option_on_proposal_send_enable = $this->input->post('village_selection_option_on_proposal_send_enable');
        if(!$village_selection_option_on_proposal_send_enable) $village_selection_option_on_proposal_send_enable = FALSE;
        
        $this->load->model('CommonModel');

        $dist_code = $this->UtilsModel->cleanPattern($this->input->post('dist_code'));
        $data['proposal_id'] = $proposal_id = $this->UtilsModel->cleanPattern($this->input->post('proposal_id'));
        $data['proposal_no'] = $proposal_no = $this->input->post('proposal_no');

        $this->dbswitch('auth');
        $auth_connection = $this->db;
        $this->dbswitch($dist_code);
        $data['locations'] = $this->CommonModel->getLocations($dist_code);

        if($village_selection_option_on_proposal_send_enable){
            
            $query = "select nc.*,l.loc_name,ch.old_vill_name,ch.new_vill_name from nc_villages nc join change_vill_name ch on nc.dist_code = ch.dist_code and nc.uuid = ch.uuid join location l on nc.dist_code = l.dist_code and nc.subdiv_code = l.subdiv_code and nc.cir_code = l.cir_code
                    and nc.mouza_pargona_code = l.mouza_pargona_code and nc.lot_no = l.lot_no and nc.vill_townprt_code = l.vill_townprt_code where
                        nc.dist_code='$dist_code'and nc.status ='b' and nc.section_officer_proposal_id='$proposal_id' and nc.section_officer_proposal='Y'";
        }else{
            $query = "select nc.*,l.loc_name,ch.old_vill_name,ch.new_vill_name from nc_villages nc join change_vill_name ch
                        on nc.dist_code = ch.dist_code and nc.uuid = ch.uuid
                        join location l on nc.dist_code = l.dist_code and nc.subdiv_code = l.subdiv_code and nc.cir_code = l.cir_code
                    and nc.mouza_pargona_code = l.mouza_pargona_code and nc.lot_no = l.lot_no and nc.vill_townprt_code = l.vill_townprt_code where
                        nc.dist_code='$dist_code'and nc.status ='A' and nc.dlr_proposal_id='$proposal_id' and nc.dlr_proposal='Y'";
        }

        $data['villages'] = $nc_village = $this->db->query($query)->result();

        foreach ($nc_village as $k => $v) {
            $nc_village[$k]->circle_name = $this->db->select('loc_name,dist_code,subdiv_code,cir_code,mouza_pargona_code,locname_eng')
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


            $merge_village_requests = $this->db->where('nc_village_id', $v->id)->get('merge_village_requests')->result_array();
            $merge_village_name_arr = [];
            if(count($merge_village_requests)){
                foreach($merge_village_requests as $key1 => $merge_village_request){
                    $vill_loc = $this->CommonModel->getLocations($merge_village_request['request_dist_code'], $merge_village_request['request_subdiv_code'], $merge_village_request['request_cir_code'], $merge_village_request['request_mouza_pargona_code'], $merge_village_request['request_lot_no'], $merge_village_request['request_vill_townprt_code']);
                    array_push($merge_village_name_arr, $vill_loc['village']['loc_name']);
                    $merge_village_requests[$key1]['village_name'] = $vill_loc['village']['loc_name'];
                    $merge_village_requests[$key1]['vill_loc'] = $vill_loc;
                }
            }
            $nc_village[$k]->merge_village_names = implode(', ', $merge_village_name_arr);
            $nc_village[$k]->merge_village_requests = $merge_village_requests;
            $nc_village[$k]->is_end_village_cadastral_village = ($v->case_type == 'NC_TO_C') ? TRUE : FALSE;
        }

        $data['proposal'] = $this->db->select('*')
            ->where(array('dist_code' => $dist_code, 'id' => $proposal_id, 'proposal_no' => $proposal_no))
            ->get('nc_village_proposal')->row();

        $data['nc_village'] = $nc_village;
        $data['ecf_no'] = $proposal_id . '/' . date('Y');
        $data = $this->load->view('nc_village/asst_section_officer/notification', $data);

        echo json_encode($data);
        return;
    }

    /** Insert POST Proceeding  */
    public function insertProceedingPost()
    {
        $proposal_no = $this->input->post('proposal_no');
        $user_code = $this->input->post('user_code');
        $note = $this->input->post('note');
        $from =  $this->input->post('from');
        $to =  $this->input->post('to');
        $task = $this->input->post('task');
        $dist_code = $this->input->post('dist_code');;
        $this->dbswitch($dist_code);
        $proceeding_id_max = $this->db->query("select max(proceeding_id) as id from settlement_proceeding where case_no = '$proposal_no'")->row()->id;
        $insPetProceed = array(
            'case_no' => $proposal_no,
            'proceeding_id' => !$proceeding_id_max ? 1 : (int) $proceeding_id_max + 1,
            'date_of_hearing' => date('Y-m-d h:i:s'),
            'next_date_of_hearing' => date('Y-m-d h:i:s'),
            'note_on_order' => $note,
            'status' => 'A',
            'user_code' => $user_code,
            'date_entry' => date('Y-m-d h:i:s'),
            'operation' => 'E',
            'ip' => $_SERVER['REMOTE_ADDR'],
            'office_from' => $from,
            'office_to' => $to,
            'task' => $task,
        );
        $data = $this->db->insert('settlement_proceeding', $insPetProceed);
        if ($data == true) {
            echo json_encode('Y');
            return;
        } else {
            echo json_encode('N');
            return;
        }
    }

    /** Dept User Forward Proposal */
    public function deptForwardProposal()
    {
        $dist_code = $this->input->post('dist_code');
        $user = $this->input->post('user');
        $note = $this->input->post('note');
        $proposal_id = $this->input->post('proposal_id');
        $user_code = $this->input->post('user_code');
        $proposal_no = $this->input->post('proposal_no');
        $forward_to = $this->input->post('forwarded_to');
        $forward_to_user_type = $this->input->post('forwarded_to_user_type');

        $this->dbswitch($dist_code);
        if ($user == 'ps') {
            $forwarded_to = $this->input->post('forwarded_to');
            $this->db->set([
                'ps_note' => $note,
                'ps_verified' => "Y",
                'ps_verified_at' => date('Y-m-d H:i:s'),
                "ps_user_code" => $user_code,
                "reverted" => null,
                "ps_forwarded_to_sec" => $forwarded_to,
            ]);

            $from = 'Senior Most Secretary';
            $to = 'Secretary (Survey & Settlement)';
            $task = 'Proposal forwarded to Secretary (Survey & Settlement).';
        } elseif ($user == 'secretary') {
            $forward_user = $this->input->post('forward_user');
            $this->db->set([
                'secretary_note' => $note,
                'secretary_verified' => "Y",
                'secretary_verified_at' => date('Y-m-d H:i:s'),
                "secretary_user_code" => $user_code,
                "reverted" => null,
                "sec_forwarded_to_jsec" => $forward_user,
            ]);
            $from = 'Secretary (Survey & Settlement)';
            $to = 'Joint Secretary (Survey & Settlement)';
            $task = 'Proposal forwarded to Joint Secretary (Survey & Settlement).';
        } elseif ($user == 'joint_secretary') {
            $forward_user = $this->input->post('forward_user');
            $this->db->set([
                'joint_secretary_note' => $note,
                'joint_secretary_verified' => "Y",
                'joint_secretary_verified_at' => date('Y-m-d H:i:s'),
                "joint_secretary_user_code" => $user_code,
                "reverted" => null,
                "jsec_forwarded_to_so" => $forward_user,
            ]);
            $from = 'Joint Secretary (Survey & Settlement)';
            $to = 'Section Officer (Survey & Settlement)';
            $task = 'Proposal forwarded to Section Officer (Survey & Settlement).';
        } elseif ($user == 'section_officer') {
            $forward_user = $this->input->post('forward_user');
            $this->db->set([
                'section_officer_note' => $note,
                'section_officer_verified' => "Y",
                'section_officer_verified_at' => date('Y-m-d H:i:s'),
                "section_officer_user_code" => $user_code,
                "reverted" => null,
                "asst_section_officer_verified" => null,
                "so_forwarded_to_aso" => $forward_user,
            ]);
            $from = 'Section Officer (Survey & Settlement)';
            $to = 'Assistant Section Officer (Survey & Settlement)';
            $task = 'Proposal forwarded to Assistant Section Officer (Survey & Settlement).';
        } elseif ($user == 'asst_section_officer') {
            $this->db->set([
                'asst_section_officer_note' => $note,
                'asst_section_officer_verified' => "Y",
                'asst_section_officer_verified_at' => date('Y-m-d H:i:s'),
                "asst_section_officer_user_code" => $user_code,
            ]);

            $from = 'Assistant Section Officer (Survey & Settlement)';
            $to = 'Section Officer (Survey & Settlement)';
            $task = 'Proposal & Notification forwarded to Section Officer (Survey & Settlement).';
        } elseif ($user == 'adlr') {
            $this->db->set([
                'adlr_note' => $note,
                'adlr_verified' => "Y",
                'adlr_verified_at' => date('Y-m-d H:i:s'),
                "adlr_user_code" => $user_code,
                "status" => 'C',
            ]);
            $from = 'ADLR';
            $to = 'JDS';
            $task = 'Proposal forwarded to JDS.';
        } elseif ($user == 'dlr') {
            $this->db->set([
                'proposal_note' => $note,
                'updated_at' => date('Y-m-d H:i:s'),
                "user_code" => $user_code,
                "status" => 'A',
                "reverted" => null,
            ]);

            $from = 'DLRS';
            $to = 'Senior Most Secretary';
            $task = 'Proposal forwarded to Senior Most Secretary.';
        } elseif ($user == 'adlrasdlr') {
            $this->db->set([
                'proposal_note' => $note,
                'updated_at' => date('Y-m-d H:i:s'),
                "user_code" => $user_code,
                "status" => 'A',
                "reverted" => null,
            ]);

            $from = 'ADLR';
            $to = 'Senior Most Secretary';
            $task = 'Proposal forwarded to Senior Most Secretary.';
        }

        $this->db->where('id', $proposal_id);
        $this->db->where('dist_code', $dist_code);
        $this->db->update('nc_village_proposal');
        if ($this->db->affected_rows() > 0) {
            $this->insertProceeding($proposal_no, $user_code, $note, $from, $to, $task, $dist_code);
            echo json_encode('Y');
            return;
        } else {
            echo json_encode('N');
            return;
        }
    }

    public function insertIlrmsNcVillageDetails($village_id, $proposal_no, $pre_user, $cur_user, $note, $pre_user_dig, $cur_user_dig, $proceeding_type = 1, $process_type = 'PROPOSAL'){
        // log_message("error", $village_id);
        $nc_village = $this->db->query("SELECT * FROM nc_villages WHERE id='".$village_id."'")->row();
        $url = API_LINK_ILRMS . "index.php/nc_village_v2/NcCommonController/insertNcVillageDetails";
        $method = 'POST';
        $update_data = [ 
                            'proccess_type' => $process_type, 
                            'dist_code' => $nc_village->dist_code,
                            'subdiv_code' => $nc_village->subdiv_code,
                            'cir_code' => $nc_village->cir_code,
                            'mouza_pargona_code' => $nc_village->mouza_pargona_code,
                            'lot_no' => $nc_village->lot_no,
                            'vill_townprt_code' => $nc_village->vill_townprt_code,
                            'uuid' => $nc_village->uuid,
                            'pre_user' => $pre_user, 
                            'cur_user' => $cur_user, 
                            'pre_user_dig' => $pre_user_dig, 
                            'cur_user_dig' => $cur_user_dig, 
                            'remark' => $note,
                            'application_no' => $nc_village->application_no,
                            'proposal_no' => $proposal_no,
                            'proceeding_type' => $proceeding_type,
                        ];

        return $this->NcVillageModel->callApiV2($url, $method, $update_data);
    }

    /** GET PROPOSAL COUNT */
    public function getDeptPendingProposalCount()
    {
        $dist_wise_locations = (array) $this->input->post('dist_wise_locations');
        
        $data['pending_proposal'] = 0;
        $data['forwarded_proposal'] = 0;
        $data['reverted_proposal'] = 0;
        $pending_proposal_count = 0;

        if(count($dist_wise_locations)){
            foreach($dist_wise_locations as $d_code => $dist_locations){
                if(count($dist_locations)){
                    $this->dbswitch($d_code);
                    $proposal_ids = [];
                    foreach($dist_locations as $location){
                        list($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $v_uuid, $cur_user_desig) = explode('_', $location);

                        if($cur_user_desig != ''){
                            $nc_village = $this->db->where('dist_code', $dist_code)
                                                    ->where('subdiv_code', $subdiv_code)
                                                    ->where('cir_code', $cir_code)
                                                    ->where('mouza_pargona_code', $mouza_pargona_code)
                                                    ->where('lot_no', $lot_no)
                                                    ->where('vill_townprt_code', $vill_townprt_code)
                                                    ->where('uuid', $v_uuid)
                                                    ->where('app_version', 'V2')
                                                    ->get('nc_villages')->row();

                            if($cur_user_desig == 'ASO'){
                                if($nc_village){
                                    $section_officer_proposal_id = $nc_village->section_officer_proposal_id;
                                    if(!in_array($section_officer_proposal_id, $proposal_ids)){
                                        array_push($proposal_ids, $section_officer_proposal_id);
                                    }
                                }
                            }
                        }
                    }
                    $pending_proposal_count += count($proposal_ids);
                }
            }
        }

        $data['pending_proposal'] = $pending_proposal_count;
        
        echo json_encode($data);
        return;
    }

    public function getNameChangeDcForwardedVillages()
    {
        $dist_wise_locations = (array) $this->input->post('dist_wise_locations');
        
        $data = [];

        if(count($dist_wise_locations)){
            foreach($dist_wise_locations as $d_code => $dist_locations){
                if(count($dist_locations)){
                    $this->dbswitch($d_code);
                    $proposal_ids = [];
                    foreach($dist_locations as $location){
                        list($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $v_uuid) = explode('_', $location);

                        $query = "select ll.loc_name as dist_name, l.loc_name as village_name,ncv.created_at,ncv.status,ncv.dist_code,ncv.subdiv_code,ncv.cir_code,ncv.mouza_pargona_code,ncv.lot_no,ncv.vill_townprt_code,ncv.application_no,ncv.uuid,
                                    ncv.dc_verified,ncv.dc_verified_at,ncv.dc_note,ncv.jds_verified,ncv.jds_verified_at,ncv.jds_note,ncv.ads_verified,ncv.ads_verified_at,ncv.ads_note,ncv.dlr_note from
                                    nc_villages ncv join
                                    location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code
                                    and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and
                                    ncv.vill_townprt_code = l.vill_townprt_code
                                    join location ll on ncv.dist_code = ll.dist_code and ll.subdiv_code='00' 
                                    where ncv.status='M' and ncv.dist_code='$dist_code' and ncv.subdiv_code='$subdiv_code' and ncv.cir_code='$cir_code' and ncv.mouza_pargona_code='$mouza_pargona_code' and ncv.lot_no='$lot_no' and ncv.vill_townprt_code='$vill_townprt_code' and ncv.uuid='$v_uuid' and app_version='V2' order by ncv.created_at desc"; 

                        $nc_village = $this->db->query($query)->row();
                        $data[] = $nc_village;
                    }
                }
            }
        }

        echo json_encode($data);
        return;
    }

    public function jdsRevertToDc()
    {
        $remark = $this->input->post('note');
        $dist_code = $this->UtilsModel->cleanPattern($this->input->post('dist_code'));
        $application_no = $this->UtilsModel->cleanPattern($this->input->post('application_no'));
        $user_code = $this->UtilsModel->cleanPattern($this->input->post('user_code'));
        $user = $this->input->post('user');
        $next_user = $this->input->post('next_user');
        $next_user_code = $this->input->post('next_user_code');
        $pre_user_code = $this->input->post('pre_user_code');

        $this->dbswitch($dist_code);
        
        if ($user == 'JDS') {
            $from = 'JDS';
            $task = 'Village reverted back to DC by JDS';
        }

        $proceeding_id_max = $this->db->query("select max(proceeding_id) as id from settlement_proceeding where case_no = '$application_no'")->row()->id;
        $this->db->trans_start();

        $insPetProceed = array(
            'case_no' => $application_no,
            'proceeding_id' => !$proceeding_id_max ? 1 : (int) $proceeding_id_max + 1,
            'date_of_hearing' => date('Y-m-d h:i:s'),
            'next_date_of_hearing' => date('Y-m-d h:i:s'),
            'note_on_order' => 'JDS reverted back to DC',
            'status' => 'G',
            'user_code' => $user_code,
            'date_entry' => date('Y-m-d h:i:s'),
            'operation' => 'E',
            'ip' => $_SERVER['REMOTE_ADDR'],
            'office_from' => $from,
            'office_to' => 'DC',
            'task' => $task,
        );
        $this->db->insert('settlement_proceeding', $insPetProceed);

        if ($user == 'JDS') {
            $this->db->where('application_no', $application_no)
                ->update(
                    'nc_villages',
                    [
                        'updated_at' => date('Y-m-d H:i:s'),
                        'status' => 'G',
                        'pre_user' => $from,
                        'cu_user' => 'DC',
                    ]
                );
        }

        if ($this->db->affected_rows() > 0) {
            $nc_village = $this->db->query("SELECT * FROM nc_villages WHERE application_no='".$application_no."' and app_version='V2'")->row();
            $res = true;
            if($nc_village){
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
                                    'pre_user' => $pre_user_code, 
                                    'cur_user' => $next_user_code, 
                                    'pre_user_dig' => 'JDS', 
                                    'cur_user_dig' => $next_user, 
                                    'remark' => trim($remark),
                                    'application_no' => $nc_village->application_no,
                                    'proceeding_type' => 1,
                                ];

                $output = $this->NcVillageModel->callApiV2($url, $method, $update_data);
                $response = $output ? json_decode($output, true) : [];
                if($response['status'] != 1){
                    $res = false;
                }
            }

            if($res){
                $this->db->trans_complete();
                echo json_encode('Y');
                return;
            }
        }

        echo json_encode('N');
        return;
    }

    public function forwardNameChangeMap()
    {
        $dist_code = $this->input->post('dist_code');
        $note = $this->input->post('note');
        $user = $this->input->post('user');
        $application_no = $this->input->post('application_no');
        $user_code = $this->input->post('user_code');
        $next_user = $this->input->post('next_user');
        $next_user_code = $this->input->post('next_user_code');
        $pre_user_code = $this->input->post('pre_user_code');

        $this->dbswitch($dist_code);
        $proceeding_type = '';
        if ($user == 'JDS') {
            $proceeding_type = 3;
            $this->db->set([
                'jds_note' => $note,
                'jds_verified' => "Y",
                'jds_verified_at' => date('Y-m-d H:i:s'),
                "jds_user_code" => $user_code,
                "status" => 'N',
            ]);
            $from = 'Joint Director of Surveys';
            $to = 'ASSISTANT DIRECTOR OF SURVEYS';
            $task = 'NC Village Forwarded to ASSISTANT DIRECTOR OF SURVEYS for Map Upload';
        } else if ($user == 'ADS') {
            $this->db->set([
                'ads_note' => $note,
                'ads_verified' => "Y",
                'ads_verified_at' => date('Y-m-d H:i:s'),
                "ads_user_code" => $user_code,
                "status" => 'Q',
            ]);
            $from = 'ASSISTANT DIRECTOR OF SURVEYS';
            $to = 'Joint Director of Surveys';
            $task = 'Map Re Uploaded and NC Village Forwarded to Joint Director of Surveys.';
        } else if ($user == 'JDSTODC') {
            $proceeding_type = 3;
            $this->db->set([
                'jds_note' => $note,
                'jds_verified' => "Y",
                'jds_verified_at' => date('Y-m-d H:i:s'),
                "jds_user_code" => $user_code,
                "status" => 'f',
                "dc_verified" => null,
                "dc_proposal" => null,
                "updated_at" => date('Y-m-d H:i:s'),
                "pre_user" => 'JDS',
                "cu_user" => 'DC',
            ]);
            $from = 'Joint Director of Surveys';
            $to = 'District Commissioner';
            $task = 'NC Village Forwarded to District Commissioner from JDS after map reupload.';
        }

        $this->db->where('application_no', $application_no);
        $this->db->where('dist_code', $dist_code);
        $this->db->update('nc_villages');
        if ($this->db->affected_rows() > 0) {
            $return = $this->insertProceeding($application_no, $user_code, $note, $from, $to, $task, $dist_code);

            $nc_village = $this->db->query("SELECT * FROM nc_villages WHERE application_no='".$application_no."' and app_version='V2'")->row();
            if($nc_village){
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
                                    'pre_user' => $pre_user_code, 
                                    'cur_user' => $next_user_code, 
                                    'pre_user_dig' => 'JDS', 
                                    'cur_user_dig' => $next_user, 
                                    'remark' => trim($note),
                                    'application_no' => $nc_village->application_no,
                                    'proceeding_type' => $proceeding_type,
                                ];

                $output = $this->NcVillageModel->callApiV2($url, $method, $update_data);
                $response = $output ? json_decode($output, true) : [];

            }

            echo json_encode('Y');
            return;
        } else {
            log_message("error", 'NC_VILLAGE_Update: ' . json_encode('#UGO0000100 Unable to update data.'));
            echo json_encode('N');
            return;
        }
    }
    public function getDistWiseSndProceedingVillageCount()
    {
        $d_list = (array) $this->input->post('d');
        $user_type = $this->input->post('user_type');

        foreach ($d_list as $k => $d) {
            $this->dbswitch($d['dist_code']);
            $dist_code = $d['dist_code'];
            if($user_type == 'section_officer'){
                $d_list[$k]['count'] = $this->db->query("select count(*) as count from nc_villages where dist_code='$dist_code' and section_officer is not null and section_officer_verified='Y' and status='a'")->row()->count;
            }elseif($user_type == 'asst_section_officer'){
                $d_list[$k]['count'] = $this->db->query("select count(*) as count from nc_villages where dist_code='$dist_code' and asst_section_officer is not null and asst_section_officer_verified='Y' and status='c'")->row()->count;
            }
        }

        $arr = array(
            'data' => $d_list,
            'status_code' => 200,
        );
        echo json_encode($arr);
        return;
    }
    
    /** proposal revert back */
    public function revertBackProposal()
    {
        $dist_code = $this->input->post('dist_code');
        $user = $this->input->post('user');
        $note = $this->input->post('note');
        $proposal_id = $this->input->post('proposal_id');
        $proposal_no = $this->input->post('proposal_no');
        $user_code = $this->input->post('user_code');

        $this->dbswitch($dist_code);
        if ($user == 'adlr') {
            $this->db->set([
                'adlr_note' => $note,
                'adlr_verified' => "Y",
                'adlr_verified_at' => date('Y-m-d H:i:s'),
                "adlr_user_code" => $user_code,
                "status" => 'E',
            ]);
            $from = 'ADLR';
            $to = 'DLRS';
            $task = 'Revert to DLRS';
        } elseif ($user == 'jds') {
            $this->db->set([
                'jds_note' => $note,
                'jds_verified' => "Y",
                'jds_verified_at' => date('Y-m-d H:i:s'),
                "jds_user_code" => $user_code,
                "status" => 'D',
            ]);
            $from = 'JDS';
            $to = 'ADLR';
            $task = 'Revert to ADLR';
        } elseif ($user == 'ps') {
            $this->db->set([
                'ps_note' => $note,
                'ps_verified_at' => date('Y-m-d H:i:s'),
                "ps_user_code" => $user_code,
                "reverted" => 'Y',
            ]);
            $from = 'PS';
            $to = 'DLR';
            $task = 'Revert to DLR';
        } elseif ($user == 'sec') {
            $this->db->set([
                'secretary_note' => $note,
                'secretary_verified_at' => date('Y-m-d H:i:s'),
                "secretary_user_code" => $user_code,
                "reverted" => 'Y',
            ]);
            $from = 'Secretary';
            $to = 'Senior Most Secretary';
            $task = 'Revert to Senior Most Secretary';
        } elseif ($user == 'joint_sec') {
            $this->db->set([
                'joint_secretary_note' => $note,
                'joint_secretary_verified_at' => date('Y-m-d H:i:s'),
                "joint_secretary_user_code" => $user_code,
                "reverted" => 'Y',
            ]);
            $from = 'Joint Secretary';
            $to = 'Secretary';
            $task = 'Revert to Secretary';
        } elseif ($user == 'section_officer') {
            $this->db->set([
                'section_officer_note' => $note,
                'section_officer_verified_at' => date('Y-m-d H:i:s'),
                "section_officer_user_code" => $user_code,
                "reverted" => 'Y',
            ]);
            $from = 'Section Officer';
            $to = 'Joint Secretary';
            $task = 'Revert to Joint Secretary';
        } elseif ($user == 'asst_section_officer') {
            $this->db->set([
                'asst_section_officer_note' => $note,
                'asst_section_officer_verified_at' => date('Y-m-d H:i:s'),
                "asst_section_officer_user_code" => $user_code,
                "reverted" => 'Y',
            ]);
            $from = 'Assistant Section Officer';
            $to = 'Section Officer';
            $task = 'Revert to Section Officer';
        }

        $this->db->where('id', $proposal_id);
        $this->db->where('dist_code', $dist_code);
        $this->db->update('nc_village_proposal');
        if ($this->db->affected_rows() > 0) {
            $this->insertProceeding($proposal_no, $user_code, $note, $from, $to, $task, $dist_code);
            echo json_encode('Y');
            return;
        } else {
            log_message("error", 'PROPOSAL_Update: ' . json_encode('#PRO00001 Unable to update data.'));
            echo json_encode('N');
            return;
        }
    }

    /** apiProposalVillages */
    public function apiProposalVillages()
    {
        $this->dbswitch('auth');
        $auth_connection = $this->db;

        $dist_code = $this->UtilsModel->cleanPattern($this->input->post('d'));
        $proposal_id = $this->input->post('proposal_id');
        $this->load->model('CommonModel');
        $this->dbswitch($dist_code);
        $data['locations'] = $this->CommonModel->getLocations($dist_code);

        $nc_village = null;
        $proposal = $this->db->where('id', $proposal_id)->get('nc_village_proposal')->row();
        if($proposal){
            if(in_array($proposal->user_type, ['ADLR', 'DLR'])){
                $conditions = ['dlr_proposal_id' => $proposal->id];
            }elseif($proposal->user_type == 'SO'){
                $conditions = ['section_officer_proposal_id' => $proposal->id];
            }else{
                log_message('error', 'Unhandled proposal user_type');
                return;
            }
        }else{
            log_message('error', 'Proposal not found');
            return;
        }

        $data['villages'] = $nc_village = $this->db->where($conditions)->get('nc_villages')->result();
        
        if(empty($nc_village)){
            log_message('error', 'Nc village record not found');
            return;
        }

        foreach ($nc_village as $k => $v) {
            $nc_village[$k]->circle_name = $this->db->select('loc_name,dist_code,subdiv_code,cir_code,mouza_pargona_code,locname_eng')
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
                    'mouza_pargona_code' => $v->mouza_pargona_code,
                    'lot_no' => $v->lot_no,
                    'vill_townprt_code' => $v->vill_townprt_code,
                ))
                ->get('chitha_basic_nc')->row();

            $total_lessa = $this->totalLessa($vill_area->total_bigha, $vill_area->total_katha, $vill_area->total_lessa);
            $nc_village[$k]->total_b_k_l = $this->Total_Bigha_Katha_Lessa($total_lessa);

            $nc_village[$k]->occupiers = $this->db->select('count(*) as occupiers')
                ->where(array(
                    'dist_code' => $v->dist_code,
                    'subdiv_code' => $v->subdiv_code,
                    'cir_code' => $v->cir_code,
                    'mouza_pargona_code' => $v->mouza_pargona_code,
                    'lot_no' => $v->lot_no,
                    'vill_townprt_code' => $v->vill_townprt_code,
                ))
                ->get('chitha_rmk_encro')->row()->occupiers;

            $merge_village_requests = $this->db->where('nc_village_id', $v->id)->get('merge_village_requests')->result_array();
            $merge_village_name_arr = [];
            if(count($merge_village_requests)){
                foreach($merge_village_requests as $key1 => $merge_village_request){
                    $vill_loc = $this->CommonModel->getLocations($merge_village_request['request_dist_code'], $merge_village_request['request_subdiv_code'], $merge_village_request['request_cir_code'], $merge_village_request['request_mouza_pargona_code'], $merge_village_request['request_lot_no'], $merge_village_request['request_vill_townprt_code']);
                    array_push($merge_village_name_arr, $vill_loc['village']['loc_name']);
                    $merge_village_requests[$key1]['village_name'] = $vill_loc['village']['loc_name'];
                    $merge_village_requests[$key1]['vill_loc'] = $vill_loc;
                }
            }
            $nc_village[$k]->merge_village_names = implode(', ', $merge_village_name_arr);
            $nc_village[$k]->merge_village_requests = $merge_village_requests;
            $nc_village[$k]->is_end_village_cadastral_village = ($v->case_type == 'NC_TO_C') ? TRUE : FALSE;
        }

        $this->db->select('id, dist_code, proposal_no, created_at, updated_at, status, proposal_note');
        $this->db->where('dist_code', $dist_code);
        $this->db->where_not_in('status', ['E', 'R']);
        $this->db->where_in('user_type', ['DLR', 'ADLR']); // Using where_in to specify multiple values for user_type
        $this->db->order_by('updated_at', 'desc');
        $query = $this->db->get('nc_village_proposal');
        $data['approve_proposal']  = $query->result();

        $data['nc_village'] = $nc_village;

        echo json_encode($data);
        return;
    }

    /** proposalRawVillageDatas */
    public function proposalRawVillageDatas()
    {
        $this->dbswitch('auth');
        // $auth_connection = $this->db;

        $dist_code = $this->UtilsModel->cleanPattern($this->input->post('d'));
        $proposal_id = $this->input->post('proposal_id');
        $this->load->model('CommonModel');
        $this->dbswitch($dist_code);
        // $data['locations'] = $this->CommonModel->getLocations($dist_code);

        $nc_village = null;
        $proposal = $this->db->where('id', $proposal_id)->get('nc_village_proposal')->row();
        if($proposal){
            if(in_array($proposal->user_type, ['ADLR', 'DLR'])){
                $conditions = ['dlr_proposal_id' => $proposal->id];
            }elseif($proposal->user_type == 'SO'){
                $conditions = ['section_officer_proposal_id' => $proposal->id];
            }else{
                log_message('error', 'Unhandled proposal user_type');
                return;
            }
        }else{
            log_message('error', 'Proposal not found');
            return;
        }

        $data['villages'] = $nc_village = $this->db->where($conditions)->get('nc_villages')->result();
        /*
        if(empty($nc_village)){
            log_message('error', 'Nc village record not found');
            return;
        }

        foreach ($nc_village as $k => $v) {
            $nc_village[$k]->circle_name = $this->db->select('loc_name,dist_code,subdiv_code,cir_code,mouza_pargona_code,locname_eng')
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
                    'mouza_pargona_code' => $v->mouza_pargona_code,
                    'lot_no' => $v->lot_no,
                    'vill_townprt_code' => $v->vill_townprt_code,
                ))
                ->get('chitha_basic_nc')->row();

            $total_lessa = $this->totalLessa($vill_area->total_bigha, $vill_area->total_katha, $vill_area->total_lessa);
            $nc_village[$k]->total_b_k_l = $this->Total_Bigha_Katha_Lessa($total_lessa);

            $nc_village[$k]->occupiers = $this->db->select('count(*) as occupiers')
                ->where(array(
                    'dist_code' => $v->dist_code,
                    'subdiv_code' => $v->subdiv_code,
                    'cir_code' => $v->cir_code,
                    'mouza_pargona_code' => $v->mouza_pargona_code,
                    'lot_no' => $v->lot_no,
                    'vill_townprt_code' => $v->vill_townprt_code,
                ))
                ->get('chitha_rmk_encro')->row()->occupiers;

            $merge_village_requests = $this->db->where('nc_village_id', $v->id)->get('merge_village_requests')->result_array();
            $merge_village_name_arr = [];
            if(count($merge_village_requests)){
                foreach($merge_village_requests as $key1 => $merge_village_request){
                    $vill_loc = $this->CommonModel->getLocations($merge_village_request['request_dist_code'], $merge_village_request['request_subdiv_code'], $merge_village_request['request_cir_code'], $merge_village_request['request_mouza_pargona_code'], $merge_village_request['request_lot_no'], $merge_village_request['request_vill_townprt_code']);
                    array_push($merge_village_name_arr, $vill_loc['village']['loc_name']);
                    $merge_village_requests[$key1]['village_name'] = $vill_loc['village']['loc_name'];
                    $merge_village_requests[$key1]['vill_loc'] = $vill_loc;
                }
            }
            $nc_village[$k]->merge_village_names = implode(', ', $merge_village_name_arr);
            $nc_village[$k]->merge_village_requests = $merge_village_requests;
            $nc_village[$k]->is_end_village_cadastral_village = ($v->case_type == 'NC_TO_C') ? TRUE : FALSE;
        }

        $this->db->select('id, dist_code, proposal_no, created_at, updated_at, status, proposal_note');
        $this->db->where('dist_code', $dist_code);
        $this->db->where_not_in('status', ['E', 'R']);
        $this->db->where_in('user_type', ['DLR', 'ADLR']); // Using where_in to specify multiple values for user_type
        $this->db->order_by('updated_at', 'desc');
        $query = $this->db->get('nc_village_proposal');
        $data['approve_proposal']  = $query->result();
        */

        $data['nc_village'] = $nc_village;

        echo json_encode($data);
        return;
    }

    /** apiProposalVillageWise */
    public function apiProposalVillageWise()
    {
        $this->load->model('CommonModel');
        $dist_code = $this->UtilsModel->cleanPattern($this->input->post('d'));
        $village_id = implode(",", $this->input->post('village_id'));

        $this->dbswitch($dist_code);
        $user_type = $this->input->post('user_type');
        $data['locations'] = $this->CommonModel->getLocations($dist_code);

        $nc_village = null;
        $proposal_view = '';
        if($user_type == 'section_officer'){
            $data['user_name'] = $this->input->post('user_name');
            $query = "select nc.*,l.loc_name,ch.old_vill_name,ch.new_vill_name from nc_villages nc join change_vill_name ch on nc.dist_code = ch.dist_code and nc.uuid = ch.uuid join location l on nc.dist_code = l.dist_code and nc.subdiv_code = l.subdiv_code and nc.cir_code = l.cir_code and nc.mouza_pargona_code = l.mouza_pargona_code and nc.lot_no = l.lot_no and nc.vill_townprt_code = l.vill_townprt_code where nc.id IN ($village_id) and nc.dist_code='$dist_code' and (nc.status ='a' OR nc.status ='b') and nc.section_officer is not null and nc.section_officer_verified='Y'";
            $data['villages'] = $nc_village = $this->db->query($query)->result();

            $proposal_view = 'nc_village/section_officer/approval_notification';
        }else if($user_type == 'asst_section_officer'){
            $data['user_name'] = $this->input->post('user_name');
            $query = "select nc.*,l.loc_name,ch.old_vill_name,ch.new_vill_name from nc_villages nc join change_vill_name ch on nc.dist_code = ch.dist_code and nc.uuid = ch.uuid join location l on nc.dist_code = l.dist_code and nc.subdiv_code = l.subdiv_code and nc.cir_code = l.cir_code and nc.mouza_pargona_code = l.mouza_pargona_code and nc.lot_no = l.lot_no and nc.vill_townprt_code = l.vill_townprt_code where nc.id IN ($village_id) and nc.dist_code='$dist_code'and nc.status ='c' and nc.asst_section_officer is not null and nc.asst_section_officer_verified='Y'";
            $data['villages'] = $nc_village = $this->db->query($query)->result();
             
            $proposal_view = 'nc_village/asst_section_officer/approval_notification';
        }else{
            return;
        }


        foreach ($nc_village as $k => $v) {
            $nc_village[$k]->circle_name = $this->db->select('loc_name,dist_code,subdiv_code,cir_code,mouza_pargona_code,locname_eng')
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

            $total_lessa = $this->totalLessa($vill_area->total_bigha, $vill_area->total_katha, $vill_area->total_lessa);
            $nc_village[$k]->total_b_k_l = $this->Total_Bigha_Katha_Lessa($total_lessa);

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
            if(count($merge_village_requests)){
                foreach($merge_village_requests as $key1 => $merge_village_request){
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
        $data['proposal'] = $this->load->view($proposal_view, $data, true);

        echo json_encode($data);
        return;
    }

    public function getApplicationNoByProposalId(){
        $dist_code = $this->input->post('dist_code');
        $proposal_id = $this->input->post('proposal_id');
        $type = $this->input->post('type');
        $this->dbswitch($dist_code);

        $this->db->select('application_no');
        $this->db->from('nc_villages');
        if($type == 'SO'){
            $this->db->where('section_officer_proposal_id', $proposal_id);
        }
        $nc_village = $this->db->get()->row();
        $arr = array(
            'data' => $nc_village,
            'status_code' => 200,
        );
        echo json_encode($arr);
    }

    public function apiVillageRevert()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE'])) {
            $contentType = $_SERVER['CONTENT_TYPE'];

            if (strcasecmp($contentType, 'application/x-www-form-urlencoded') === 0) {
                $dist_code = $this->UtilsModel->cleanPattern($this->input->post('dist_code'));
                $application_no = $this->UtilsModel->cleanPattern($this->input->post('application_no'));
                $note = $this->UtilsModel->cleanPattern($this->input->post('note'));
                $forward_user = $this->input->post('forward_user');
                $cur_user_dig = $this->UtilsModel->cleanPattern($this->input->post('cur_dig'));
                $user_code = $this->input->post('user_code');
                $unique_user_id = $this->input->post('unique_user_id');
                $user = $this->input->post('user');
                if ($user == 'SO') {
                    $from = 'SO';
                    $task = 'Village Reverted by SO';
                }
                else {

                }

                $this->dbswitch($dist_code);
                $this->db->trans_begin();

                $proceeding_id_max = $this->db->query("select max(proceeding_id) as id from settlement_proceeding where case_no = '$application_no'")->row()->id;
                $insPetProceed = array(
                    'case_no' => $application_no,
                    'proceeding_id' => !$proceeding_id_max ? 1 : (int) $proceeding_id_max + 1,
                    'date_of_hearing' => date('Y-m-d h:i:s'),
                    'next_date_of_hearing' => date('Y-m-d h:i:s'),
                    'note_on_order' => $note,
                    'status' => 'e',
                    'user_code' => $user_code,
                    'date_entry' => date('Y-m-d h:i:s'),
                    'operation' => 'E',
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'office_from' => $from,
                    'office_to' => 'DLR',
                    'task' => $task,
                );
                $insertStatus = $this->db->insert('settlement_proceeding', $insPetProceed);
                if(!$insertStatus || $this->db->affected_rows() < 1) {
                    $this->db->trans_rollback();
                    $arr = array(
                        'data' => [],
                        'status_code' => 500
                    );
                    echo json_encode($arr);
                    return;
                }

                if($user == 'SO') {
                    $ncv = $this->db->where('application_no', $application_no)->get('nc_villages')->row();
                    if($ncv){
                        $process_type = 'PROPOSAL';
                        if($ncv->section_officer_proposal_id){
                            $this->db->select('nc_villages.id, nc_village_proposal.proposal_no');
                            $this->db->from('nc_villages');
                            $this->db->join('nc_village_proposal', 'nc_village_proposal.id = nc_villages.section_officer_proposal_id');
                            $this->db->where('nc_villages.application_no', $application_no);
                        }else{
                            $process_type = 'REVERT';
                            $this->db->select('nc_villages.id, nc_village_proposal.proposal_no');
                            $this->db->from('nc_villages');
                            $this->db->join('nc_village_proposal', 'nc_village_proposal.id = nc_villages.dlr_proposal_id');
                            $this->db->where('nc_villages.application_no', $application_no);
                        }
                        $query = $this->db->get();
                        $nc_village = $query->row();
                        
                        $this->db->where('application_no', $application_no);
                        $updateStatus = $this->db->update(
                            'nc_villages',
                            [
                                'updated_at' => date('Y-m-d H:i:s'),
                                'dlr_proposal' => null,
                                'dlr_verified' => null,
                                'dlr_verified_at' => null,
                                'section_officer_proposal_id' => null,
                                'section_officer_note' => $note,
                                'section_officer_user_code' => $user_code,
                                'status' => 'e',
                                'pre_user' => $from,
                                'cu_user' => 'DLR',
                            ]
                        );
                        
                        $response = $this->insertIlrmsNcVillageDetails($nc_village->id, $nc_village->proposal_no, $unique_user_id, $forward_user, $note, 'SO', $cur_user_dig, 0, $process_type);
                        $response = $response ? json_decode($response, true) : [];
                    }else{
                        $updateStatus = false;
                    }
                }
                else if ($user == 'ASO') {
                    $updateStatus = false;
                }
                else {
                    $updateStatus = false;
                }

                if(!$updateStatus || $this->db->affected_rows() < 1) {
                    $this->db->trans_rollback();
                    $arr = array(
                        'data' => [],
                        'status_code' => 500
                    );
                    echo json_encode($arr);
                    return;
                }

                if(!$this->db->trans_status() && $response['status'] != 1) {
                    $this->db->trans_rollback();
                    $arr = array(
                        'data' => [],
                        'status_code' => 500
                    );
                    echo json_encode($arr);
                    return;
                }

                $this->db->trans_commit();

                $arr = array(
                    'data' => 'Y',
                    'nc_village' => $nc_village = $this->db->query("select * from nc_villages where application_no='$application_no'")->row(),
                    'status_code' => 200,
                );
                echo json_encode($arr);
                return;
            } else {
                $arr = array(
                    'data' => [],
                    'status_code' => 404
                );
                echo json_encode($arr);
                return;
            }
        } else {
            $arr = array(
                'data' => [],
                'status_code' => 404
            );
            echo json_encode($arr);
            return;
        }
    }

    /** GET ALL PROPOSALS */
    public function getProposals()
    {
        $d_list = (array) $this->input->post('d');
        $data = [];
        foreach ($d_list as $k => $d) {
            $this->dbswitch($d['dist_code']);
            $dist_code = $d['dist_code'];
            $proposals = $this->db->query("select count(ncv.*) as total_villages,ncvp.* from nc_village_proposal ncvp join nc_villages ncv on ncvp.id=ncv.dlr_proposal_id group by ncvp.id")->result();
            foreach ($proposals as $proposal) {
                $data_single = [];
                $data_single['dist_code'] = $dist_code;
                $data_single['dist_name'] = $d['loc_name'];
                $data_single['proposal'] = $proposal;
                $data[] = $data_single;
            }
        }
        $arr = array(
            'data' => $data,
            'status_code' => 200,
        );
        echo json_encode($arr);
        return;
    }

    public function getProposalsForDlr()
    {
        $user = $this->UtilsModel->cleanPattern($this->input->post('user'));
        $type = $this->UtilsModel->cleanPattern($this->input->post('type'));

        $data = array();
        if ($user == 'dlr' && $type == 'revertedfordlr') {
            $notification = $this->input->post('notification', true);
            $data['all_reverted_villages'] = array();
            foreach (json_decode(NC_DISTIRTCS) as $dist_code) {
                $so_village_reverted = $this->db->query("SELECT * FROM nc_villages WHERE status='e' AND cu_user='DLR' AND dlr_verified IS NULL AND dlr_verified_at IS NULL AND dlr_proposal IS NULL")->result();
                
                if(!empty($so_village_reverted)) {
                    foreach ($so_village_reverted as $so_village) {
                        $so_village->reverted_from = 'SO';

                        $so_village->dist_name = $this->db->query("SELECT loc_name FROM location WHERE dist_code=? AND subdiv_code='00'", [$so_village->dist_code])->row()->loc_name;
                        $so_village->vill_townprt_name = $this->db->query("SELECT loc_name FROM location WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=?", [$so_village->dist_code, $so_village->subdiv_code, $so_village->cir_code, $so_village->mouza_pargona_code, $so_village->lot_no, $so_village->vill_townprt_code])->row()->loc_name;

                        $merge_village_requests = $this->db->where('nc_village_id', $so_village->id)->get('merge_village_requests')->result();
                        if(!empty($merge_village_requests)) {
                            $merge_village_name_arr = [];
                            foreach($merge_village_requests as $merge_village_request) {
                                $vill_loc = $this->CommonModel->getLocations($merge_village_request->dist_code, $merge_village_request->subdiv_code, $merge_village_request->cir_code, $merge_village_request->mouza_pargona_code, $merge_village_request->lot_no, $merge_village_request->vill_townprt_code);
                                $merge_village_name_arr[] = $vill_loc['village']['loc_name'];
                                $merge_village_request->village_name = $vill_loc['village']['loc_name'];
                                $merge_village_request->vill_loc = $vill_loc;
                            }
                            $so_village->merge_village_names = implode(', ', $merge_village_name_arr);
                            $so_village->merge_village_requests = $merge_village_requests;
                        }
                        else {
                            $so_village->merge_village_names = 'N.A.';
                            $so_village->merge_village_requests = $merge_village_requests;
                        }

                        $data['all_reverted_villages'][] = $so_village;
                    }
                }

            }
        }
        echo json_encode($data);
        return;
    }

    public function apiDlrRevertedCertify()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE'])) {
            $contentType = $_SERVER['CONTENT_TYPE'];

            if (strcasecmp($contentType, 'application/x-www-form-urlencoded') === 0) {
                $dist_code = $this->UtilsModel->cleanPattern($this->input->post('dist_code'));
                $application_no = $this->UtilsModel->cleanPattern($this->input->post('application_no'));
                $dlr_note = $this->UtilsModel->cleanPattern($this->input->post('dlr_note'));
                $remarks = $this->input->post('remarks');
                $user_code = $this->UtilsModel->cleanPattern($this->input->post('user_code'));
                $new_vill_name = $this->input->post('new_vill_name');
                $new_vill_name_eng = $this->input->post('new_vill_name_eng');
                $uuid = $this->input->post('uuid');
                $user = $this->input->post('user');

                $pre_user = $this->input->post('pre_user');
                $pre_user_dig = $this->input->post('pre_user_dig');
                $cur_user = $this->input->post('cur_user');
                $cur_user_dig = $this->input->post('cur_user_dig');

                $this->dbswitch($dist_code);

                $nc_village = $this->db->where('application_no', $application_no)->get('nc_villages')->row();

                if ($user == 'ADLR') {
                    $from = 'ADLR';
                    $task = 'Village verified by ADLR';
                } elseif ($user == 'DLR') {
                    $from = 'DLR';
                    $task = 'Village verified by DLR';
                }

                $proceeding_id_max = $this->db->query("select max(proceeding_id) as id from settlement_proceeding where case_no = '$application_no'")->row()->id;
                $this->db->trans_start();

                $insPetProceed = array(
                    'case_no' => $application_no,
                    'proceeding_id' => !$proceeding_id_max ? 1 : (int) $proceeding_id_max + 1,
                    'date_of_hearing' => date('Y-m-d h:i:s'),
                    'next_date_of_hearing' => date('Y-m-d h:i:s'),
                    'note_on_order' => $remarks,
                    'status' => 'L',
                    'user_code' => $user_code,
                    'date_entry' => date('Y-m-d h:i:s'),
                    'operation' => 'E',
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'office_from' => $from,
                    'office_to' => 'DEPT',
                    'task' => $task,
                );
                $this->db->insert('settlement_proceeding', $insPetProceed);

                if ($user == 'ADLR') {
                    $this->db->where('application_no', $application_no)
                        ->update(
                            'nc_villages',
                            [
                                'updated_at' => date('Y-m-d H:i:s'),
                                'adlr_verified' => 'Y',
                                'adlr_verified_at' => date('Y-m-d H:i:s'),
                                'adlr_note' => $dlr_note,
                                'adlr_user_code' => $user_code,
                                'status' => 'L',
                                'pre_user' => $from,
                                'cu_user' => 'DEPT',
                            ]
                        );
                } elseif ($user == 'DLR') {
                    $this->db->where('application_no', $application_no)
                        ->update(
                            'nc_villages',
                            [
                                'updated_at' => date('Y-m-d H:i:s'),
                                'dlr_proposal' => null,
                                'dlr_proposal_id' => null,
                                'dlr_verified' => 'Y',
                                'dlr_verified_at' => date('Y-m-d H:i:s'),
                                'dlr_note' => $dlr_note,
                                'dlr_user_code' => $user_code,
                                'status' => 'L',
                                'pre_user' => $from,
                                'cu_user' => 'DEPT',
                            ]
                        );
                }


                if ($this->db->affected_rows() > 0) {
                    $response = $this->insertIlrmsNcVillageDetails($nc_village->id, NULL, $pre_user, $cur_user, $dlr_note, $pre_user_dig, $cur_user_dig, 1, 'FORWARD');
                    $response = $response ? json_decode($response, true) : [];
                    
                    if($response['status'] == 1){
                        $this->db->trans_complete();
                        $arr = array(
                            'data' => 'Y',
                            'nc_village' => $nc_village = $this->db->query("select * from nc_villages where application_no='$application_no'")->row(),
                            'status_code' => 200,
                        );
                        echo json_encode($arr);
                        return;
                    }
                }

                $arr = array(
                    'data' => 'N',
                    'nc_village' => $nc_village = $this->db->query("select * from nc_villages where application_no='$application_no'")->row(),
                    'status_code' => 200,
                );
                echo json_encode($arr);
            } else {
                $arr = array(
                    'data' => [],
                    'status_code' => 404
                );
                echo json_encode($arr);
            }
        } else {
            $arr = array(
                'data' => [],
                'status_code' => 404
            );
            echo json_encode($arr);
        }
    }

    public function changeVill()
    {
        $dist_code = $this->UtilsModel->cleanPattern($this->input->post('dist_code'));
        $new_vill_name = $this->input->post('new_vill_name');
        $new_vill_name_eng = $this->input->post('new_vill_name_eng');
        $uuid = $this->input->post('uuid');
        $application_no = $this->UtilsModel->cleanPattern($this->input->post('application_no'));
        $user_code = $this->UtilsModel->cleanPattern($this->input->post('user_code'));
        $change_vill_remark = $this->UtilsModel->cleanPattern($this->input->post('change_vill_remark'));
        $user = $this->input->post('user');
        $pre_user = $this->input->post('pre_user');
        $pre_user_dig = $this->input->post('pre_user_dig');
        $cur_user = $this->input->post('cur_user');
        $cur_user_dig = $this->input->post('cur_user_dig');

        $this->dbswitch($dist_code);

        $nc_village = $this->db->where('application_no', $application_no)->get('nc_villages')->row();
        if(empty($nc_village)){
            log_message('error', "Case not found for application no: {$application_no}");
            $arr = array(
                    'data' => 'N',
                    'status_code' => 200,
                );
            echo json_encode($arr);
            return;
        }

        if ($user == 'SO') {
            $cu_user_code = 'SO';
            $task = 'Village Forwarded for Name Change by ADLR to JDS';
        }else{
            log_message('error', "Undefined User {$user} for changing the village name");
            $arr = array(
                    'data' => 'N',
                    'status_code' => 200,
                );
            echo json_encode($arr);
            return;
        }

        $this->db->trans_start();
        $this->db->set([
            'new_vill_name' => $new_vill_name,
            'new_vill_name_eng' => $new_vill_name_eng,
            'cu_user_code' => $cu_user_code,
            'prev_user_code' => "DLR",
            'status' => "D", // D is added for CO
            'date_of_update_so' => date('Y-m-d H:i:s'),
            'so_verified' => "Y",
            "so_user_code" => $user_code,
        ]);
        $this->db->where('uuid', $uuid);
        $this->db->where('dist_code', $dist_code);
        $this->db->update('change_vill_name');
        //        if ($this->db->affected_rows() > 0) {
        //            $this->db->where('application_no', $application_no)
        //                ->update(
        //                    'nc_villages',
        //                    [
        //                        'pre_user' => $cu_user_code,
        //                        'cu_user' => 'JDS',
        //                        'dlr_user_code' => $user_code,
        //                        'updated_at' => date('Y-m-d H:i:s'),
        //                        'dlr_note' => trim($change_vill_remark),
        //                        'status' => 'P',
        //                    ]
        //                );

        if ($this->db->affected_rows() > 0) {
            $proceeding_id_max = $this->db->query("select max(proceeding_id) as id from settlement_proceeding where case_no = '$application_no'")->row()->id;
            $insPetProceed = array(
                'case_no' => $application_no,
                'proceeding_id' => !$proceeding_id_max ? 1 : (int) $proceeding_id_max + 1,
                'date_of_hearing' => date('Y-m-d h:i:s'),
                'next_date_of_hearing' => date('Y-m-d h:i:s'),
                'note_on_order' => trim($change_vill_remark),
                'status' => $nc_village->status,
                'user_code' => $user_code,
                'date_entry' => date('Y-m-d h:i:s'),
                'operation' => 'E',
                'ip' => $_SERVER['REMOTE_ADDR'],
                'office_from' => $cu_user_code,
                'office_to' => $cu_user_code,
                'task' => $task,
            );
            $this->db->insert('settlement_proceeding', $insPetProceed);

            if ($this->db->affected_rows() > 0) {
                if ($this->db->trans_status() === false) {
                    $this->db->trans_rollback();
                    $arr = array(
                        'data' => 'N',
                        'status_code' => 200,
                    );
                    echo json_encode($arr);
                    return;
                } else {
                    $nc_village = $this->db->where('application_no', $application_no)->get('nc_villages')->row();
                    $url = API_LINK_ILRMS . "index.php/nc_village_v2/NcCommonController/insertNcVillageDetails";
                    $method = 'POST';
                    $update_data = [ 
                                        'proccess_type' => 'CHANGED_VILLAGE_NAME', 
                                        'dist_code' => $nc_village->dist_code,
                                        'subdiv_code' => $nc_village->subdiv_code,
                                        'cir_code' => $nc_village->cir_code,
                                        'mouza_pargona_code' => $nc_village->mouza_pargona_code,
                                        'lot_no' => $nc_village->lot_no,
                                        'vill_townprt_code' => $nc_village->vill_townprt_code,
                                        'uuid' => $nc_village->uuid,
                                        'pre_user' => $pre_user, 
                                        'cur_user' => $cur_user, 
                                        'pre_user_dig' => $pre_user, 
                                        'cur_user_dig' => $pre_user, 
                                        'only_for_log' => 'Y', 
                                        // 'proceeding_type' => 1,
                                        'remark' => trim($change_vill_remark),
                                    ];

                    $output = $this->NcVillageModel->callApiV2($url, $method, $update_data);
                    $response = $output ? json_decode($output, true) : [];
                    if($response['status'] != 1){
                        $this->db->trans_rollback();
                        return false;
                    }
                        

                    $this->db->trans_commit();
                    $arr = array(
                        'data' => 'Y',
                        'status_code' => 200,
                    );
                    echo json_encode($arr);
                    return;
                }
            } else {
                $this->db->trans_rollback();
                $arr = array(
                    'data' => 'N',
                    'status_code' => 200,
                );
                echo json_encode($arr);
                return;
            }
        } else {
            $this->db->trans_rollback();
            $arr = array(
                'data' => 'N',
                'status_code' => 200,
            );
            echo json_encode($arr);
            return;
        }
        //        } else {
        //            $this->db->trans_rollback();
        //            $arr = array(
        //                'data' => 'N',
        //                'status_code' => 200,
        //            );
        //            echo json_encode($arr);
        //            return;
        //        }
    }

    public function getProposal(){
        $dist_code = $this->input->post('dist_code');
        $proposal_no = $this->input->post('proposal_no');

        if(!empty($dist_code)){
            $this->dbswitch($dist_code);

            $proposal = $this->db->where(array(
                    'dist_code' => $dist_code,
                    'proposal_no' => $proposal_no
                ))
                ->get('nc_village_proposal')->row();
            
            if($proposal){
                $data['proposal'] = $proposal;
                $data['proposal_pdf_base64'] = null;
                $dir = strtolower($proposal->user_type);
                $data['dir'] = $dir;
                if(in_array($dir, ['co', 'dc'])){
                    $proposalpdfFilePath = FCPATH . NC_VILLAGE_PROPOSAL_DIR . $dir . "/" . $proposal->proposal_no . '.pdf';
                    if (file_exists($proposalpdfFilePath)) {
                        $proposalpdfData = file_get_contents($proposalpdfFilePath);
                        $data['proposal_pdf_base64'] = base64_encode($proposalpdfData);
                    }
                }

                echo json_encode([
                        'data' => $data,
                        'status_code' => 200,
                    ]);

                return;
            }

        }

        echo json_encode([
                'data' => [],
                'status_code' => 404,
                'message' => 'Something went wrong. Please try again.',
            ]);

        return;
    }

    /** forward to DLR for dismissal */
    public function forwardToDlrForDismissal(){
        $dist_code = $this->input->post('dist_code');
        $note = $this->input->post('note');
        $user = $this->input->post('user');
        $application_no = $this->input->post('application_no');
        $user_code = $this->input->post('user_code');
        $cur_user_code = $this->input->post('cur_user_code');
        $cur_user_desig = $this->input->post('cur_user_desig');
        $pre_user_code = $this->input->post('pre_user_code');
        $pre_user_desig = $this->input->post('pre_user_desig');

        $this->dbswitch($dist_code);
        $this->db->trans_begin();
        if ($user == 'JDS') {
            $this->db->set([
                'jds_note' => $note,
                "jds_user_code" => $user_code,
                "status" => 'g',
                "pre_user" => 'JDS',
                "cu_user" => 'DLR',
                "updated_at" => date('Y-m-d H:i:s'),
            ]);
            $from = 'Joint Director of Surveys';
            $to = 'Director of Land Records and Surveys';
            $task = 'NC Village Forwarded to Director of Land Records and Surveys for Dismissal';
        }

        $this->db->where('application_no', $application_no);
        $this->db->where('dist_code', $dist_code);
        $this->db->update('nc_villages');
        if ($this->db->affected_rows() > 0) {
            $nc_village = $this->db->query("SELECT * FROM nc_villages WHERE application_no='".$application_no."' and app_version='V2'")->row();
            if($nc_village){
                $url = API_LINK_ILRMS . "index.php/nc_village_v2/NcCommonController/insertNcVillageDetails";
                $method = 'POST';
                $update_data = [ 
                    'proccess_type' => 'DISMISS', 
                    'dist_code' => $nc_village->dist_code,
                    'subdiv_code' => $nc_village->subdiv_code,
                    'cir_code' => $nc_village->cir_code,
                    'mouza_pargona_code' => $nc_village->mouza_pargona_code,
                    'lot_no' => $nc_village->lot_no,
                    'vill_townprt_code' => $nc_village->vill_townprt_code,
                    'uuid' => $nc_village->uuid,
                    'pre_user' => $pre_user_code, 
                    'cur_user' => $cur_user_code, 
                    'pre_user_dig' => $pre_user_desig, 
                    'cur_user_dig' => $cur_user_desig, 
                    'remark' => trim($note),
                    'application_no' => $nc_village->application_no,
                    'proceeding_type' => 2,
                ];

                $output = $this->NcVillageModel->callApiV2($url, $method, $update_data);
            }
            $this->db->trans_commit();
            $return = $this->insertProceeding($application_no, $user_code, $note, $from, $to, $task, $dist_code);
            echo json_encode('Y');
            return;
        } else {
            $this->db->trans_rollback();
            log_message("error", 'NC_VILLAGE_Update: ' . json_encode('#UGO0000100 Unable to update data.'));
            echo json_encode('N');
            return;
        }
    }

    public function apiGetAllDismissVillageDlr(){
        $d_list = (array) json_decode(NC_DISTIRTCS);

        $village = array();
        foreach ($d_list as $k => $dist_code) {
            $this->dbswitch($dist_code);

            $query = "select ll.loc_name as dist_name, l.loc_name,ncv.lm_verified_at,ncv.status,ncv.dist_code,ncv.application_no,ncv.lm_note,
                ncv.co_verified,ncv.co_note,ncv.dc_verified,ncv.dc_verified_at,ncv.dc_note,ncv.ads_verified, ncv.id as nc_village_id,ncv.jds_note from
                nc_villages ncv join
                location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code
                 and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and
                 ncv.vill_townprt_code = l.vill_townprt_code
                 join location ll on ncv.dist_code = ll.dist_code";
            $query = $query . " where ncv.dist_code='$dist_code' and ll.subdiv_code ='00' and ncv.status='g'";
            $nc_villages = $this->db->query($query)->result();
            foreach ($nc_villages as $nc) {
                $merge_village_requests = $this->db->where('nc_village_id', $nc->nc_village_id)->get('merge_village_requests')->result_array();
                $merge_village_name_arr = [];
                if(count($merge_village_requests)){
                    foreach($merge_village_requests as $key1 => $merge_village_request){
                        $vill_loc = $this->CommonModel->getLocations($merge_village_request['request_dist_code'], $merge_village_request['request_subdiv_code'], $merge_village_request['request_cir_code'], $merge_village_request['request_mouza_pargona_code'], $merge_village_request['request_lot_no'], $merge_village_request['request_vill_townprt_code']);
                        array_push($merge_village_name_arr, $vill_loc['village']['loc_name']);
                        $merge_village_requests[$key1]['village_name'] = $vill_loc['village']['loc_name'];
                        $merge_village_requests[$key1]['vill_loc'] = $vill_loc;
                    }
                }
                $nc->merge_village_names = implode(', ', $merge_village_name_arr);
                $nc->merge_village_requests = $merge_village_requests;

                $village[] = $nc;
            }
        }
        echo json_encode($village);
        return;
    }

    public function pullbackList(){
        $dist = $this->UtilsModel->cleanPattern($this->input->post('dist_code'));
        $subdiv = $this->UtilsModel->cleanPattern($this->input->post('subdiv_code'));
        $circle = $this->UtilsModel->cleanPattern($this->input->post('cir_code'));
        $mouza = $this->UtilsModel->cleanPattern($this->input->post('mouza_pargona_code'));
        $lot = $this->UtilsModel->cleanPattern($this->input->post('lot_no'));
        $request_villages = $this->input->post('vill_townprt_codes');
        $status = $this->UtilsModel->cleanPattern($this->input->post('status'));
        $pullback_from = $this->UtilsModel->cleanPattern($this->input->post('pullback_from'));
        $this->dbswitch($dist);
        
        switch($pullback_from){
            case 'LM':
                $query = "select l.loc_name,ncv.id,ncv.lm_verified_at,ncv.status,ncv.application_no,ncv.lm_note,ncv.sk_verified_at,ncv.sk_note,ncv.co_verified,ncv.co_note, ncv.dist_code, ncv.subdiv_code, ncv.cir_code, ncv.mouza_pargona_code, ncv.lot_no, ncv.vill_townprt_code from nc_villages ncv join location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and ncv.vill_townprt_code = l.vill_townprt_code where ncv.dist_code='$dist' and ncv.subdiv_code='$subdiv' and ncv.cir_code='$circle' and ncv.mouza_pargona_code='$mouza' and ncv.lot_no='$lot' and ncv.status ='$status' and pre_user='SK' and cu_user='LM'";

                break;
            case 'SK':
                if($status == 'S'){
                    $query = "select l.loc_name,ncv.id,ncv.lm_verified_at,ncv.status,ncv.application_no,ncv.lm_note,ncv.sk_verified_at,ncv.sk_note,ncv.co_verified,ncv.co_note, ncv.dist_code, ncv.subdiv_code, ncv.cir_code, ncv.mouza_pargona_code, ncv.lot_no, ncv.vill_townprt_code from nc_villages ncv join location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and ncv.vill_townprt_code = l.vill_townprt_code where ncv.dist_code='$dist' and ncv.subdiv_code='$subdiv' and ncv.cir_code='$circle' and ncv.mouza_pargona_code='$mouza' and ncv.lot_no='$lot' and ncv.status ='$status'";
                }elseif($status == 'H'){
                    $query = "select l.loc_name,ncv.id,ncv.lm_verified_at,ncv.status,ncv.application_no,ncv.lm_note,ncv.sk_verified_at,ncv.sk_note,ncv.co_verified,ncv.co_note, ncv.dist_code, ncv.subdiv_code, ncv.cir_code, ncv.mouza_pargona_code, ncv.lot_no, ncv.vill_townprt_code from nc_villages ncv join location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and ncv.vill_townprt_code = l.vill_townprt_code where ncv.dist_code='$dist' and ncv.subdiv_code='$subdiv' and ncv.cir_code='$circle' and ncv.mouza_pargona_code='$mouza' and ncv.lot_no='$lot' and ncv.status ='$status' and pre_user='CO' and cu_user='SK'";
                }

                break;
            case 'JDS':
                if($status == 'P'){
                    $query = "select l.loc_name,ncv.id,ncv.lm_verified_at,ncv.status,ncv.application_no,ncv.lm_note,ncv.sk_verified_at,ncv.sk_note,ncv.co_verified,ncv.co_note, ncv.dist_code, ncv.subdiv_code, ncv.cir_code, ncv.mouza_pargona_code, ncv.lot_no, ncv.vill_townprt_code from nc_villages ncv join location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and ncv.vill_townprt_code = l.vill_townprt_code where ncv.dist_code='$dist' and ncv.subdiv_code='$subdiv' and ncv.cir_code='$circle' and ncv.mouza_pargona_code='$mouza' and ncv.lot_no='$lot' and ncv.status ='$status'";
                }elseif($status == 'g'){
                    $query = "select l.loc_name,ncv.id,ncv.lm_verified_at,ncv.status,ncv.application_no,ncv.lm_note,ncv.sk_verified_at,ncv.sk_note,ncv.co_verified,ncv.co_note, ncv.dist_code, ncv.subdiv_code, ncv.cir_code, ncv.mouza_pargona_code, ncv.lot_no, ncv.vill_townprt_code from nc_villages ncv join location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and ncv.vill_townprt_code = l.vill_townprt_code where ncv.dist_code='$dist' and ncv.subdiv_code='$subdiv' and ncv.cir_code='$circle' and ncv.mouza_pargona_code='$mouza' and ncv.lot_no='$lot' and ncv.status ='$status'";
                }elseif($status == 'M'){
                    $query = "select l.loc_name,ncv.id,ncv.lm_verified_at,ncv.status,ncv.application_no,ncv.lm_note,ncv.sk_verified_at,ncv.sk_note,ncv.co_verified,ncv.co_note, ncv.dist_code, ncv.subdiv_code, ncv.cir_code, ncv.mouza_pargona_code, ncv.lot_no, ncv.vill_townprt_code from nc_villages ncv join location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and ncv.vill_townprt_code = l.vill_townprt_code where ncv.dist_code='$dist' and ncv.subdiv_code='$subdiv' and ncv.cir_code='$circle' and ncv.mouza_pargona_code='$mouza' and ncv.lot_no='$lot' and ncv.status ='$status' and pre_user='DLR' and cu_user='JDS'";
                }
                break;
            case 'DC':
                if($status == 'G'){
                    $query = "select l.loc_name,ncv.id,ncv.lm_verified_at,ncv.status,ncv.application_no,ncv.lm_note,ncv.sk_verified_at,ncv.sk_note,ncv.co_verified,ncv.co_note, ncv.dist_code, ncv.subdiv_code, ncv.cir_code, ncv.mouza_pargona_code, ncv.lot_no, ncv.vill_townprt_code from nc_villages ncv join location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and ncv.vill_townprt_code = l.vill_townprt_code where ncv.dist_code='$dist' and ncv.subdiv_code='$subdiv' and ncv.cir_code='$circle' and ncv.mouza_pargona_code='$mouza' and ncv.lot_no='$lot' and ncv.status ='$status'";
                }elseif($status == 'K'){
                    $query = "select l.loc_name,ncv.id,ncv.lm_verified_at,ncv.status,ncv.application_no,ncv.lm_note,ncv.sk_verified_at,ncv.sk_note,ncv.co_verified,ncv.co_note, ncv.dist_code, ncv.subdiv_code, ncv.cir_code, ncv.mouza_pargona_code, ncv.lot_no, ncv.vill_townprt_code from nc_villages ncv join location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and ncv.vill_townprt_code = l.vill_townprt_code where ncv.dist_code='$dist' and ncv.subdiv_code='$subdiv' and ncv.cir_code='$circle' and ncv.mouza_pargona_code='$mouza' and ncv.lot_no='$lot' and ncv.status ='$status'";
                }elseif($status == 'M|N'){
                    $query = "select l.loc_name,ncv.id,ncv.lm_verified_at,ncv.status,ncv.application_no,ncv.lm_note,ncv.sk_verified_at,ncv.sk_note,ncv.co_verified,ncv.co_note, ncv.dist_code, ncv.subdiv_code, ncv.cir_code, ncv.mouza_pargona_code, ncv.lot_no, ncv.vill_townprt_code from nc_villages ncv join location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and ncv.vill_townprt_code = l.vill_townprt_code where ncv.dist_code='$dist' and ncv.subdiv_code='$subdiv' and ncv.cir_code='$circle' and ncv.mouza_pargona_code='$mouza' and ncv.lot_no='$lot'and (ncv.status = 'M' or ncv.status = 'N')";
                }elseif($status == 'B'){
                    $query = "select l.loc_name,ncv.id,ncv.lm_verified_at,ncv.status,ncv.application_no,ncv.lm_note,ncv.sk_verified_at,ncv.sk_note,ncv.co_verified,ncv.co_note, ncv.dist_code, ncv.subdiv_code, ncv.cir_code, ncv.mouza_pargona_code, ncv.lot_no, ncv.vill_townprt_code from nc_villages ncv join location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and ncv.vill_townprt_code = l.vill_townprt_code where ncv.dist_code='$dist' and ncv.subdiv_code='$subdiv' and ncv.cir_code='$circle' and ncv.mouza_pargona_code='$mouza' and ncv.lot_no='$lot' and ncv.status ='$status'";
                }

                break;
            default:
                // CO list
                $query = "select l.loc_name,ncv.id,ncv.lm_verified_at,ncv.status,ncv.application_no,ncv.lm_note,ncv.sk_verified_at,ncv.sk_note,ncv.co_verified,ncv.co_note, ncv.dist_code, ncv.subdiv_code, ncv.cir_code, ncv.mouza_pargona_code, ncv.lot_no, ncv.vill_townprt_code from nc_villages ncv join location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and ncv.vill_townprt_code = l.vill_townprt_code where ncv.dist_code='$dist' and ncv.subdiv_code='$subdiv' and ncv.cir_code='$circle' and ncv.mouza_pargona_code='$mouza' and ncv.lot_no='$lot' and ncv.status ='$status'";
                break;
        }


        if(!empty($request_villages) && is_array($request_villages) && count($request_villages)){
            $request_villages_arr = array_map(function($request_village){
                return "'" . $request_village . "'";
            }, $request_villages);

            $request_villages_str = implode(',', $request_villages_arr);
            
            $query .= " and ncv.vill_townprt_code in ($request_villages_str)" ;
        } 
        // $query = $query . " where ncv.dist_code='$dist' and ncv.subdiv_code='$subdiv' and ncv.cir_code='$circle' and ncv.status ='T'";

        $villages = $this->db->query($query)->result();

        return response_json(['data' => $villages, 'status_code' => 200]);
    }

    public function pullbackUpdate(){
        $application_no = $this->input->post('application_no');
        $dist = $this->UtilsModel->cleanPattern($this->input->post('dist_code'));
        $subdiv = $this->UtilsModel->cleanPattern($this->input->post('subdiv_code'));
        $circle = $this->UtilsModel->cleanPattern($this->input->post('cir_code'));
        $mouza = $this->UtilsModel->cleanPattern($this->input->post('mouza_pargona_code'));
        $lot = $this->UtilsModel->cleanPattern($this->input->post('lot_no'));
        $request_villages = $this->input->post('vill_townprt_codes');
        $pullback_case_id_with_loc = $this->input->post('pullback_case_id_with_loc');
        $remark = $this->UtilsModel->cleanPattern($this->input->post('remark'));
        $created_by_unique_user_id = $this->input->post('created_by_unique_user_id');
        $created_by_designation = $this->input->post('created_by_designation');
        $created_by_user_code = $this->input->post('created_by_user_code');
        $pullback_from = $this->UtilsModel->cleanPattern($this->input->post('pullback_from'));
        
        $this->dbswitch($dist);

        $conditions = [
                        'dist_code' => $dist,
                        'subdiv_code' => $subdiv,
                        'cir_code' => $circle,
                        'mouza_pargona_code' => $mouza,
                        'lot_no' => $lot,
                    ];

        $this->db->trans_begin();
        try{
            if(count($request_villages)){
                foreach($request_villages as $vill_townprt_code){
                    $new_cond = $conditions;
                    $new_cond = $new_cond + ['vill_townprt_code' => $vill_townprt_code];
                    $nc_village = $this->db->where($new_cond)->get('nc_villages')->row_array();
                    $chitha_basic_nc = $this->db->where($new_cond)->get('chitha_basic_nc')->result_array();
                    $nc_village_dags = $this->db->where($new_cond)->get('nc_village_dags')->result_array();

                    $co_proposal = $dc_proposal = $dlr_proposal = $so_proposal = [];
                    $co_proposal_id = $dc_proposal_id = $dlr_proposal_id = $so_proposal_id = NULL;
                    $proposal_ids = [];

                    switch($pullback_from){
                        case 'LM':
                            if($nc_village){
                                // Revert case
                                $merge_village_requests = $this->db->where($new_cond)->where('nc_village_id', $nc_village['id'])->get('merge_village_requests')->result_array();
                                if($co_proposal_id = $nc_village['proposal_id']){
                                    $co_proposal = $this->db->where('id', $co_proposal_id)->get('nc_village_proposal')->row_array();
                                    if($co_proposal_id && $co_proposal){
                                        array_push($proposal_ids, $co_proposal_id);
                                    }
                                }
                                if($dc_proposal_id = $nc_village['dc_proposal_id']){
                                    $dc_proposal = $this->db->where('id', $dc_proposal_id)->get('nc_village_proposal')->row_array();
                                    if($dc_proposal_id && $dc_proposal){
                                        array_push($proposal_ids, $dc_proposal_id);
                                    }
                                }
                                if($dlr_proposal_id = $nc_village['dlr_proposal_id']){
                                    $dlr_proposal = $this->db->where('id', $dlr_proposal_id)->get('nc_village_proposal')->row_array();
                                    if($dlr_proposal_id && $dlr_proposal){
                                        array_push($proposal_ids, $dlr_proposal_id);
                                    }
                                }
                            }else{
                                //
                            }

                            break;
                        case 'SK':
                            $merge_village_requests = $this->db->where($new_cond)->where('nc_village_id', $nc_village['id'])->get('merge_village_requests')->result_array();
                            if($co_proposal_id = $nc_village['proposal_id']){
                                $co_proposal = $this->db->where('id', $co_proposal_id)->get('nc_village_proposal')->row_array();
                                if($co_proposal_id && $co_proposal){
                                    array_push($proposal_ids, $co_proposal_id);
                                }
                            }
                            if($dc_proposal_id = $nc_village['dc_proposal_id']){
                                $dc_proposal = $this->db->where('id', $dc_proposal_id)->get('nc_village_proposal')->row_array();
                                if($dc_proposal_id && $dc_proposal){
                                    array_push($proposal_ids, $dc_proposal_id);
                                }
                            }
                            if($dlr_proposal_id = $nc_village['dlr_proposal_id']){
                                $dlr_proposal = $this->db->where('id', $dlr_proposal_id)->get('nc_village_proposal')->row_array();
                                if($dlr_proposal_id && $dlr_proposal){
                                    array_push($proposal_ids, $dlr_proposal_id);
                                }
                            }

                            if($so_proposal_id = $nc_village['section_officer_proposal_id']){
                                $so_proposal = $this->db->where('id', $so_proposal_id)->get('nc_village_proposal')->row_array();
                                if($so_proposal_id && $so_proposal){
                                    array_push($proposal_ids, $so_proposal_id);
                                }
                            }

                            break;
                        case 'JDS':
                            $merge_village_requests = $this->db->where($new_cond)->where('nc_village_id', $nc_village['id'])->get('merge_village_requests')->result_array();
                            if($co_proposal_id = $nc_village['proposal_id']){
                                $co_proposal = $this->db->where('id', $co_proposal_id)->get('nc_village_proposal')->row_array();
                                if($co_proposal_id && $co_proposal){
                                    array_push($proposal_ids, $co_proposal_id);
                                }
                            }
                            if($dc_proposal_id = $nc_village['dc_proposal_id']){
                                $dc_proposal = $this->db->where('id', $dc_proposal_id)->get('nc_village_proposal')->row_array();
                                if($dc_proposal_id && $dc_proposal){
                                    array_push($proposal_ids, $dc_proposal_id);
                                }
                            }
                            if($dlr_proposal_id = $nc_village['dlr_proposal_id']){
                                $dlr_proposal = $this->db->where('id', $dlr_proposal_id)->get('nc_village_proposal')->row_array();
                                if($dlr_proposal_id && $dlr_proposal){
                                    array_push($proposal_ids, $dlr_proposal_id);
                                }
                            }
                            if($so_proposal_id = $nc_village['section_officer_proposal_id']){
                                $so_proposal = $this->db->where('id', $so_proposal_id)->get('nc_village_proposal')->row_array();
                                if($so_proposal_id && $so_proposal){
                                    array_push($proposal_ids, $so_proposal_id);
                                }
                            }

                            // $nc_village_n = $this->db->query("SELECT * FROM nc_villages WHERE application_no='".$application_no."' and app_version='V2'")->row();
                            // if($nc_village_n){
                            //     $url = API_LINK_ILRMS . "index.php/nc_village_v2/NcCommonController/insertNcVillageDetails";
                            //     $method = 'POST';
                            //     $update_data = [ 
                            //         'proccess_type' => 'DISMISS', 
                            //         'dist_code' => $nc_village_n->dist_code,
                            //         'subdiv_code' => $nc_village_n->subdiv_code,
                            //         'cir_code' => $nc_village_n->cir_code,
                            //         'mouza_pargona_code' => $nc_village_n->mouza_pargona_code,
                            //         'lot_no' => $nc_village_n->lot_no,
                            //         'vill_townprt_code' => $nc_village_n->vill_townprt_code,
                            //         'uuid' => $nc_village_n->uuid,
                            //         'pre_user' => $created_by_unique_user_id, 
                            //         'cur_user' => NULL, 
                            //         'pre_user_dig' => $created_by_designation, 
                            //         'cur_user_dig' => NULL, 
                            //         'remark' => trim('DISMISS'),
                            //         'application_no' => $nc_village_n->application_no,
                            //         'proceeding_type' => -1,
                            //     ];

                            //     $outputRes = $this->NcVillageModel->callApiV2($url, $method, $update_data);
                            //     if(!$outputRes){
                            //         throw new Exception('#ERRPULLBACKUPDATE0001: Something went wrong.');
                            //     }
                            // }

                            break;
                        case 'DC':
                            $merge_village_requests = $this->db->where($new_cond)->where('nc_village_id', $nc_village['id'])->get('merge_village_requests')->result_array();
                            if($co_proposal_id = $nc_village['proposal_id']){
                                $co_proposal = $this->db->where('id', $co_proposal_id)->get('nc_village_proposal')->row_array();
                                if($co_proposal_id && $co_proposal){
                                    array_push($proposal_ids, $co_proposal_id);
                                }
                            }
                            if($dc_proposal_id = $nc_village['dc_proposal_id']){
                                $dc_proposal = $this->db->where('id', $dc_proposal_id)->get('nc_village_proposal')->row_array();
                                if($dc_proposal_id && $dc_proposal){
                                    array_push($proposal_ids, $dc_proposal_id);
                                }
                            }
                            if($dlr_proposal_id = $nc_village['dlr_proposal_id']){
                                $dlr_proposal = $this->db->where('id', $dlr_proposal_id)->get('nc_village_proposal')->row_array();
                                if($dlr_proposal_id && $dlr_proposal){
                                    array_push($proposal_ids, $dlr_proposal_id);
                                }
                            }
                            if($so_proposal_id = $nc_village['section_officer_proposal_id']){
                                $so_proposal = $this->db->where('id', $so_proposal_id)->get('nc_village_proposal')->row_array();
                                if($so_proposal_id && $so_proposal){
                                    array_push($proposal_ids, $so_proposal_id);
                                }
                            }

                            break;
                        default:
                            // for CO
                            $merge_village_requests = $this->db->where($new_cond)->where('nc_village_id', $nc_village['id'])->get('merge_village_requests')->result_array();

                            if($co_proposal_id = $nc_village['proposal_id']){
                                $co_proposal = $this->db->where('id', $co_proposal_id)->get('nc_village_proposal')->row_array();
                                if($co_proposal_id && $co_proposal){
                                    array_push($proposal_ids, $co_proposal_id);
                                }
                            }
                            if($dc_proposal_id = $nc_village['dc_proposal_id']){
                                $dc_proposal = $this->db->where('id', $dc_proposal_id)->get('nc_village_proposal')->row_array();
                                if($dc_proposal_id && $dc_proposal){
                                    array_push($proposal_ids, $dc_proposal_id);
                                }
                            }
                            if($dlr_proposal_id = $nc_village['dlr_proposal_id']){
                                $dlr_proposal = $this->db->where('id', $dlr_proposal_id)->get('nc_village_proposal')->row_array();
                                if($dlr_proposal_id && $dlr_proposal){
                                    array_push($proposal_ids, $dlr_proposal_id);
                                }
                            }

                            if($so_proposal_id = $nc_village['section_officer_proposal_id']){
                                $so_proposal = $this->db->where('id', $so_proposal_id)->get('nc_village_proposal')->row_array();
                                if($so_proposal_id && $so_proposal){
                                    array_push($proposal_ids, $so_proposal_id);
                                }
                            }
                            break;
                    }

                    $loc = $dist . '_' . $subdiv . '_' . $circle . '_' . $mouza . '_' . $lot . '_' . $vill_townprt_code;
                    $pullback_case_id = $pullback_case_id_with_loc[$loc];
                    $data = [
                                'dist_code' => $dist,
                                'subdiv_code' => $subdiv,
                                'cir_code' => $circle,
                                'mouza_pargona_code' => $mouza,
                                'lot_no' => $lot,
                                'vill_townprt_code' => $vill_townprt_code,
                                'ilrms_pullback_case_id' => $pullback_case_id,
                                'nc_villages_data' => json_encode($nc_village),
                                'chitha_basic_nc_data' => json_encode($chitha_basic_nc),
                                'nc_village_dags_data' => json_encode($nc_village_dags),
                                'co_proposal_data' => json_encode($co_proposal),
                                'dc_proposal_data' => json_encode($dc_proposal),
                                'dlr_proposal_data' => json_encode($dlr_proposal),
                                'so_proposal_data' => json_encode($so_proposal),
                                'merge_village_requests_data' => isset($merge_village_requests) ? json_encode($merge_village_requests) : NULL,
                                'created_by_unique_user_id' => $created_by_unique_user_id,
                                'created_by_user_code' => $created_by_user_code,
                                'created_at' => date('Y-m-d H:i:s'),
                                'remarks' => $remark,
                            ];

                    $this->db->insert('pullback_nc_village_cases', $data);

                    if($this->db->affected_rows() != 1){
                        log_message('error', '#ERRPULLBACKUPDATE0001: ' . $this->db->last_query());
                        throw new Exception('#ERRPULLBACKUPDATE0001: Something went wrong.');
                    }

                    $nc_village_n = $this->db->query("SELECT * FROM nc_villages WHERE application_no='".$application_no."' and app_version='V2'")->row();
                    if($nc_village_n){
                        $url = API_LINK_ILRMS . "index.php/nc_village_v2/NcCommonController/insertNcVillageDetails";
                        $method = 'POST';
                        $update_data = [ 
                            'proccess_type' => 'DISMISS', 
                            'dist_code' => $nc_village_n->dist_code,
                            'subdiv_code' => $nc_village_n->subdiv_code,
                            'cir_code' => $nc_village_n->cir_code,
                            'mouza_pargona_code' => $nc_village_n->mouza_pargona_code,
                            'lot_no' => $nc_village_n->lot_no,
                            'vill_townprt_code' => $nc_village_n->vill_townprt_code,
                            'uuid' => $nc_village_n->uuid,
                            'pre_user' => $created_by_unique_user_id, 
                            'cur_user' => NULL, 
                            'pre_user_dig' => $created_by_designation, 
                            'cur_user_dig' => NULL, 
                            'remark' => trim('DISMISS'),
                            'application_no' => $nc_village_n->application_no,
                            'proceeding_type' => -1,
                        ];

                        $outputRes = $this->NcVillageModel->callApiV2($url, $method, $update_data);
                        if(!$outputRes){
                            $this->db->trans_rollback();
                            throw new Exception('#ERRPULLBACKUPDATE0001: Something went wrong.');
                        }
                    }

                    $this->db->where($new_cond)->delete('nc_villages');
                    $this->db->where($new_cond)->delete('chitha_basic_nc');
                    $this->db->where($new_cond)->delete('nc_village_dags');
                    if($nc_village){
                        $this->db->where($new_cond)->where('nc_village_id', $nc_village['id'])->delete('merge_village_requests');
                    }
                    
                    if(count($proposal_ids) > 0){
                        $this->db->where_in('id', $proposal_ids)->delete('nc_village_proposal');
                        if($this->db->affected_rows() != count($proposal_ids)){
                            log_message('error', '#ERRPULLBACKUPDATE0002: ' . $this->db->last_query());
                            throw new Exception('#ERRPULLBACKUPDATE0002: Something went wrong.');
                        }
                    }
                }

            }else{
                throw new Exception('Villages not found.');
            }
        }catch(Exception $e){
            $this->db->trans_rollback();
            return response_json(['data' => [], 'message' => $e->getMessage(), 'status_code' => 400], 400);
        }
        
        $this->db->trans_commit();

        return response_json(['data' => [], 'message' => 'Pullback action has been done successfully', 'status_code' => 200]);
    }

    

    public function addVNlog(){
        $application_no = $this->input->post('application_no');
        $dist_code = $this->input->post('dist_code');
        $pre_user = $this->input->post('pre_user');
        $pre_user_dig = $this->input->post('pre_user_dig');
        $note = $this->input->post('note');
        $this->dbswitch($dist_code);

        $village = $this->db->query("SELECT * FROM nc_villages WHERE application_no='".$application_no."' and app_version='V2'")->row();
        if($village){
            $this->db->trans_begin();
            $url = API_LINK_ILRMS . "index.php/nc_village_v2/NcCommonController/insertNcVillageDetails";
            $method = 'POST';
            $update_data = [ 
                'proccess_type' => 'DISMISS', 
                'dist_code' => $village->dist_code,
                'subdiv_code' => $village->subdiv_code,
                'cir_code' => $village->cir_code,
                'mouza_pargona_code' => $village->mouza_pargona_code,
                'lot_no' => $village->lot_no,
                'vill_townprt_code' => $village->vill_townprt_code,
                'uuid' => $village->uuid,
                'pre_user' => $pre_user, 
                'cur_user' => NULL, 
                'pre_user_dig' => $pre_user_dig, 
                'cur_user_dig' => NULL, 
                'remark' => trim($note),
                'application_no' => $village->application_no,
                'proceeding_type' => -2,
            ];

            $output = $this->NcVillageModel->callApiV2($url, $method, $update_data);
            if(!$output){
                $this->db->trans_rollback();
                return response_json(['status_code' => 400], 400);
            }

            $this->db->trans_commit();
            return response_json(['status_code' => 200]);
        } else{
            return response_json(['status_code' => 400], 400);
        }
    }

    public function changeVillDLR()
    {

        $dist_code = $this->UtilsModel->cleanPattern($this->input->post('dist_code'));
        $new_vill_name = $this->input->post('new_vill_name');
        $new_vill_name_eng = $this->input->post('new_vill_name_eng');
        $uuid = $this->input->post('uuid');
        $application_no = $this->UtilsModel->cleanPattern($this->input->post('application_no'));
        $user_code = $this->UtilsModel->cleanPattern($this->input->post('user_code'));
        $change_vill_remark = $this->UtilsModel->cleanPattern($this->input->post('change_vill_remark'));
        $user = $this->input->post('user');
        $pre_user = $this->input->post('pre_user');
        $pre_user_dig = $this->input->post('pre_user_dig');
        $cur_user = $this->input->post('cur_user');
        $cur_user_dig = $this->input->post('cur_user_dig');
        $this->dbswitch($dist_code);

        if ($user == 'ADLR') {
            $cu_user_code = 'ADLR';
            $task = 'Village Forwarded for Name Change by ADLR to JDS';
        } elseif ($user == 'DLR') {
            $cu_user_code = 'DLR';
            $task = 'Village Name Updated by DLRS';
        }

        $this->db->trans_start();
        $this->db->set([
            'new_vill_name' => $new_vill_name,
            'new_vill_name_eng' => $new_vill_name_eng,
            'cu_user_code' => $cu_user_code,
            'prev_user_code' => "DC",
            'status' => "F",
            'date_of_update_dlr' => date('Y-m-d H:i:s'),
            'dlr_verified' => "Y",
            "dlr_user_code" => $user_code,
        ]);
        $this->db->where('uuid', $uuid);
        $this->db->where('dist_code', $dist_code);
        $this->db->update('change_vill_name');
        //        if ($this->db->affected_rows() > 0) {
        //            $this->db->where('application_no', $application_no)
        //                ->update(
        //                    'nc_villages',
        //                    [
        //                        'pre_user' => $cu_user_code,
        //                        'cu_user' => 'JDS',
        //                        'dlr_user_code' => $user_code,
        //                        'updated_at' => date('Y-m-d H:i:s'),
        //                        'dlr_note' => trim($change_vill_remark),
        //                        'status' => 'P',
        //                    ]
        //                );

        log_message("error", "query: ".$this->db->last_query());
        if ($this->db->affected_rows() > 0) {
            $proceeding_id_max = $this->db->query("select max(proceeding_id) as id from settlement_proceeding where case_no = '$application_no'")->row()->id;
            $insPetProceed = array(
                'case_no' => $application_no,
                'proceeding_id' => !$proceeding_id_max ? 1 : (int) $proceeding_id_max + 1,
                'date_of_hearing' => date('Y-m-d h:i:s'),
                'next_date_of_hearing' => date('Y-m-d h:i:s'),
                'note_on_order' => trim($change_vill_remark),
                'status' => 'P',
                'user_code' => $user_code,
                'date_entry' => date('Y-m-d h:i:s'),
                'operation' => 'E',
                'ip' => $_SERVER['REMOTE_ADDR'],
                'office_from' => $cu_user_code,
                'office_to' => $cu_user_code,
                'task' => $task,
            );
            $this->db->insert('settlement_proceeding', $insPetProceed);

            if ($this->db->affected_rows() > 0) {
                $village = $this->db->query("SELECT * FROM nc_villages WHERE application_no='".$application_no."' and app_version='V2'")->row();
                if($village){
                    $url = API_LINK_ILRMS . "index.php/nc_village_v2/NcCommonController/insertNcVillageDetails";
                    $method = 'POST';
                    $update_data = [ 
                        'proccess_type' => 'CHANGED_VILLAGE_NAME', 
                        'dist_code' => $village->dist_code,
                        'subdiv_code' => $village->subdiv_code,
                        'cir_code' => $village->cir_code,
                        'mouza_pargona_code' => $village->mouza_pargona_code,
                        'lot_no' => $village->lot_no,
                        'vill_townprt_code' => $village->vill_townprt_code,
                        'uuid' => $village->uuid,
                        'pre_user' => $pre_user, 
                        'cur_user' => $cur_user, 
                        'pre_user_dig' => $pre_user_dig, 
                        'cur_user_dig' => $cur_user_dig, 
                        'remark' => "Changed Village Name",
                        'application_no' => $village->application_no,
                        'proceeding_type' => 3,
                        'only_for_log' => 'Y'
                    ];

                    $res = $this->NcVillageModel->callApiV2($url, $method, $update_data);
                    if(!$res){
                        $this->db->trans_rollback();
                        $arr = array(
                            'data' => 'N',
                            'status_code' => 200,
                        );
                        echo json_encode($arr);
                        return;
                    }
                    
                    $this->db->trans_commit();
                    $arr = array(
                        'data' => 'Y',
                        'status_code' => 200,
                    );
                    echo json_encode($arr);
                    return;
                } else{
                    $this->db->trans_rollback();
                    $arr = array(
                        'data' => 'N',
                        'status_code' => 200,
                    );
                    echo json_encode($arr);
                    return;
                }
            } else {
                $this->db->trans_rollback();
                $arr = array(
                    'data' => 'N',
                    'status_code' => 200,
                );
                echo json_encode($arr);
                return;
            }
        } else {
            $this->db->trans_rollback();
            $arr = array(
                'data' => 'N',
                'status_code' => 200,
            );
            echo json_encode($arr);
            return;
        }
        //        } else {
        //            $this->db->trans_rollback();
        //            $arr = array(
        //                'data' => 'N',
        //                'status_code' => 200,
        //            );
        //            echo json_encode($arr);
        //            return;
        //        }
    }

    /** get locations by application nos */
    public function getLocationsByApplicationNos()
    {
        $params = $this->input->post('villages');

        $this->load->model('CommonModel');

        if (is_array($params)) {
            foreach ($params as $key => $village) {
                $this->dbswitch($village['dist_code']);
                $location = $this->CommonModel->getLocations(
                    $village['dist_code'],
                    $village['subdiv_code'],
                    $village['cir_code'],
                    $village['mouza_pargona_code'],
                    $village['lot_no'],
                    $village['vill_townprt_code']
                );
                $params[$key]['dist_name'] = isset($location['dist']['loc_name']) ? $location['dist']['loc_name'] : '';
                $params[$key]['village_name'] = isset($location['village']['loc_name']) ? $location['village']['loc_name'] : '';
            }
        }
        echo json_encode($params);
        return;
    }

    public function migrationNcVillage()
    {
        $dist_code = $this->input->post('dist_code');
        $proposal_id = $this->input->post('proposal_id');
        $this->dbswitch($dist_code);
        $this->db->set([
            'app_version' => 'V2',
        ]);
        $this->db->where('asst_section_officer_proposal_id', $proposal_id);
        $this->db->update('nc_villages');
        if ($this->db->affected_rows() > 0) {
            $arr = array(
                'data' => 'Y',
                'nc_village_data' => $this->db->query("select * from nc_villages where asst_section_officer_proposal_id='".$proposal_id."'")->row(),
                'status_code' => 200,
            );
            echo json_encode($arr);
            return;
        } else{
            $arr = array(
                'data' => 'N',
                'status_code' => 200,
            );
            echo json_encode($arr);
            return;
        }
    }
}