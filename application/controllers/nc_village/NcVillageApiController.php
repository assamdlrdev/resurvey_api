<?php
include APPPATH . '/libraries/CommonTrait.php';

class NcVillageApiController extends CI_Controller
{
    use CommonTrait;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('UtilsModel');
        $this->load->model('ChangeVillageModel');
        $this->load->model('Chithamodel');
        $this->load->model('CommonModel');
        $this->load->model('DagReportModel');
    }

    /** get all pending villages for department */
    public function apiGetNcVillaqes()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE'])) {
            $contentType = $_SERVER['CONTENT_TYPE'];
            if (strcasecmp($contentType, 'application/x-www-form-urlencoded') === 0) {
                $dist_code = $this->UtilsModel->cleanPattern($this->input->post('d'));
                $subdiv_code = $this->UtilsModel->cleanPattern($this->input->post('s'));
                $cir_code = $this->UtilsModel->cleanPattern($this->input->post('c'));
                $mouza_pargona_code = $this->UtilsModel->cleanPattern($this->input->post('m'));
                $lot_no = $this->UtilsModel->cleanPattern($this->input->post('l'));
                $filter = $this->UtilsModel->cleanPattern($this->input->post('f'));
                $pending = $this->UtilsModel->cleanPattern($this->input->post('pending'));
                $verified = $this->UtilsModel->cleanPattern($this->input->post('verified'));
                $user = $this->UtilsModel->cleanPattern($this->input->post('user'));

                $this->dbswitch($dist_code);

                $query = "select ll.loc_name as dist_name, l.loc_name,ncv.lm_verified_at,ncv.status,ncv.dist_code,ncv.application_no,ncv.lm_note,
				ncv.co_verified,ncv.co_note,ncv.dc_verified,ncv.dc_verified_at,ncv.dc_note,ncv.ads_verified, ncv.id as nc_village_id from
				nc_villages ncv join
				location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code
                 and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and
                 ncv.vill_townprt_code = l.vill_townprt_code
                 join location ll on ncv.dist_code = ll.dist_code";

                $query = $query . " where ncv.dist_code='$dist_code' and ll.subdiv_code = '00'";
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
                if ($user == 'DLR') {
                    if ($filter == $pending) {
                        $query = $query . " and ncv.status='$pending'";
                    }
                    if ($filter == $verified) {
                        $query = $query . " and ncv.dlr_verified = 'Y'";
                    }
                } else {
                    if ($filter == $pending) {
                        $query = $query . " and ncv.status='$pending'";
                    }
                    if ($filter == $verified) {
                        $query = $query . " and ncv.status = '$verified'";
                    }
                }

                $nc_villages = $this->db->query($query)->result();
                foreach ($nc_villages as $key => $nc) {
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
                    $nc_villages[$key]->merge_village_names = implode(', ', $merge_village_name_arr);
                    $nc_villages[$key]->merge_village_requests = $merge_village_requests;

                }

                $arr = array(
                    'data' => $nc_villages,
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

    /** get pending village for department */
    public function apiGetRevertedVillage()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE'])) {
            $contentType = $_SERVER['CONTENT_TYPE'];

            if (strcasecmp($contentType, 'application/x-www-form-urlencoded') === 0) {
                $dist_code = $this->UtilsModel->cleanPattern($this->input->post('d'));
                $application_no = $this->UtilsModel->cleanPattern($this->input->post('application_no'));

                $this->dbswitch($dist_code);

                $nc_village = $this->db->query("select * from nc_villages where
				application_no='$application_no' and dist_code='$dist_code'")->row();

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
                $approve_proposal = $this->db->select('proposal_no')
                    ->where(array(
                        'dist_code' => $dist_code,
                        'id' => $nc_village->dc_proposal_id,
                        'user_type' => 'DC',
                        'status' => 'A'
                    ))
                    ->get('nc_village_proposal')->row();

                $data['proposal_pdf_base64'] = null;
                $proposalpdfFilePath = FCPATH . NC_VILLAGE_PROPOSAL_DIR . "dc/" . $approve_proposal->proposal_no . '.pdf';
                if (file_exists($proposalpdfFilePath)) {
                    $proposalpdfData = file_get_contents($proposalpdfFilePath);
                    $data['proposal_pdf_base64'] = base64_encode($proposalpdfData);
                }

                $data['locations'] = $this->CommonModel->getLocations(
                    $nc_village->dist_code,
                    $nc_village->subdiv_code,
                    $nc_village->cir_code,
                    $nc_village->mouza_pargona_code,
                    $nc_village->lot_no,
                    $nc_village->vill_townprt_code
                );

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

    public function apiGetRevertVillage() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE'])) {
            $contentType = $_SERVER['CONTENT_TYPE'];

            if (strcasecmp($contentType, 'application/x-www-form-urlencoded') === 0) {
                $dist_code = $this->UtilsModel->cleanPattern($this->input->post('d'));
                $application_no = $this->UtilsModel->cleanPattern($this->input->post('application_no'));
                $for_user = $this->input->post('for_user');

                $this->dbswitch($dist_code);

                $nc_village = $this->db->query("select * from nc_villages where
				application_no='$application_no' and dist_code='$dist_code'")->row();

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
                        if($nc_village->select_village_on_prop_sign_from_so == 1) {
                            $approve_proposal = $this->db->select('proposal_no')
                            ->where(array(
                                'dist_code' => $dist_code,
                                'id' => $nc_village->section_officer_proposal_id,
                                'user_type' => 'SO',
                                'status' => 'a'
                            ))
                            ->get('nc_village_proposal')->row();
                        }
                        else {
                            $approve_proposal = $this->db->select('proposal_no')
                            ->where(array(
                                'dist_code' => $dist_code,
                                'id' => $nc_village->dlr_proposal_id,
                                'user_type' => 'DLR',
                                'status' => 'A'
                            ))
                            ->get('nc_village_proposal')->row();
                        }
                        
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
                if ($this->db->trans_status() === false) {
                    $this->db->trans_rollback();
                    $arr = array(
                        'data' => 'N',
                        'status_code' => 200,
                    );
                    echo json_encode($arr);
                    return;
                } else {
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

    /** DLR Certify */
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

                $this->dbswitch($dist_code);

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
                    $this->db->trans_complete();
                    $arr = array(
                        'data' => 'Y',
                        'nc_village' => $nc_village = $this->db->query("select * from nc_villages where application_no='$application_no'")->row(),
                        'status_code' => 200,
                    );
                    echo json_encode($arr);
                    return;
                    // $approveVill = $this->ChangeVillageModel->approveChangeVillage($uuid, $dist_code, $user_code);
                    // if ($approveVill) {
                    //     $this->db->trans_complete();
                    //     $arr = array(
                    //         'data' => 'Y',
                    //         'nc_village' => $nc_village = $this->db->query("select * from nc_villages where application_no='$application_no'")->row(),
                    //         'status_code' => 200,
                    //     );
                    //     echo json_encode($arr);
                    //     return;
                    // } else {
                    //     $arr = array(
                    //         'data' => 'N',
                    //         'nc_village' => $nc_village = $this->db->query("select * from nc_villages where application_no='$application_no'")->row(),
                    //         'status_code' => 200,
                    //     );
                    //     echo json_encode($arr);
                    // }
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

    public function apiDlrCertify()
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

                $this->dbswitch($dist_code);

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
                    $this->db->trans_complete();
                    $arr = array(
                        'data' => 'Y',
                        'nc_village' => $nc_village = $this->db->query("select * from nc_villages where application_no='$application_no'")->row(),
                        'status_code' => 200,
                    );
                    echo json_encode($arr);
                    return;
                    // $approveVill = $this->ChangeVillageModel->approveChangeVillage($uuid, $dist_code, $user_code);
                    // if ($approveVill) {
                    //     $this->db->trans_complete();
                    //     $arr = array(
                    //         'data' => 'Y',
                    //         'nc_village' => $nc_village = $this->db->query("select * from nc_villages where application_no='$application_no'")->row(),
                    //         'status_code' => 200,
                    //     );
                    //     echo json_encode($arr);
                    //     return;
                    // } else {
                    //     $arr = array(
                    //         'data' => 'N',
                    //         'nc_village' => $nc_village = $this->db->query("select * from nc_villages where application_no='$application_no'")->row(),
                    //         'status_code' => 200,
                    //     );
                    //     echo json_encode($arr);
                    // }
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

    public function apiDlrUndoCertify()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE'])) {
            $contentType = $_SERVER['CONTENT_TYPE'];

            if (strcasecmp($contentType, 'application/x-www-form-urlencoded') === 0) {
                $dist_code = $this->UtilsModel->cleanPattern($this->input->post('dist_code'));
                $application_no = $this->UtilsModel->cleanPattern($this->input->post('application_no'));
                $user_code = $this->UtilsModel->cleanPattern($this->input->post('user_code'));
                $this->dbswitch($dist_code);
                $task = 'Undo Certification by DLR';

                $proceeding_id_max = $this->db->query("select max(proceeding_id) as id from settlement_proceeding where case_no = '$application_no'")->row()->id;
                $this->db->trans_start();

                $insPetProceed = array(
                    'case_no' => $application_no,
                    'proceeding_id' => !$proceeding_id_max ? 1 : (int) $proceeding_id_max + 1,
                    'date_of_hearing' => date('Y-m-d h:i:s'),
                    'next_date_of_hearing' => date('Y-m-d h:i:s'),
                    'note_on_order' => '',
                    'status' => 'I',
                    'user_code' => $user_code,
                    'date_entry' => date('Y-m-d h:i:s'),
                    'operation' => 'E',
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'office_from' => 'DLR',
                    'office_to' => 'DLR',
                    'task' => $task,
                );
                $this->db->insert('settlement_proceeding', $insPetProceed);

                $this->db->where('application_no', $application_no)
                    ->update(
                        'nc_villages',
                        [
                            'updated_at' => date('Y-m-d H:i:s'),
                            'dlr_verified' => null,
                            'dlr_verified_at' => null,
                            'dlr_note' => '',
                            'dlr_user_code' => $user_code,
                            'status' => 'I',
                            'pre_user' => 'DC',
                            'cu_user' => 'DLR',
                        ]
                    );


                if ($this->db->affected_rows() > 0) {
                    $this->db->trans_complete();
                    $arr = array(
                        'data' => 'Y',
                        'nc_village' => $nc_village = $this->db->query("select * from nc_villages where application_no='$application_no'")->row(),
                        'status_code' => 200,
                    );
                    echo json_encode($arr);
                    return;
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
    /** DLR Revert to dc */
    public function apiAsoRevert() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE'])) {
            $contentType = $_SERVER['CONTENT_TYPE'];

            if (strcasecmp($contentType, 'application/x-www-form-urlencoded') === 0) {
                $dist_code = $this->UtilsModel->cleanPattern($this->input->post('dist_code'));
                $application_no = $this->UtilsModel->cleanPattern($this->input->post('application_no'));
                $aso_note = $this->UtilsModel->cleanPattern($this->input->post('aso_note'));
                $user_code = $this->UtilsModel->cleanPattern($this->input->post('user_code'));
                $user = $this->input->post('user');
                // if ($user == 'ADLR') {
                //     $from = 'ADLR';
                //     $task = 'Village Reverted by ADLR';
                // } elseif ($user == 'DLR') {
                //     $from = 'DLR';
                //     $task = 'Village Reverted by DLRS';
                // }
                if ($user == 'ASO') {
                    $from = 'ASO';
                    $task = 'Village Reverted by ASO';
                }

                $this->dbswitch($dist_code);
                $this->db->trans_begin();

                $proceeding_id_max = $this->db->query("select max(proceeding_id) as id from settlement_proceeding where case_no = '$application_no'")->row()->id;
                $insPetProceed = array(
                    'case_no' => $application_no,
                    'proceeding_id' => !$proceeding_id_max ? 1 : (int) $proceeding_id_max + 1,
                    'date_of_hearing' => date('Y-m-d h:i:s'),
                    'next_date_of_hearing' => date('Y-m-d h:i:s'),
                    'note_on_order' => $aso_note,
                    'status' => 'f',
                    'user_code' => $user_code,
                    'date_entry' => date('Y-m-d h:i:s'),
                    'operation' => 'E',
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'office_from' => $from,
                    'office_to' => 'SO',
                    'task' => $task,
                );
                $insertStatus = $this->db->insert('settlement_proceeding', $insPetProceed);
                if(!$insertStatus || $this->db->affected_rows() < 1) {
                    $this->db->trans_rollback();
                    $arr = array(
                        'data' => [],
                        'status_code' => 500,
                    );
                    echo json_encode($arr);
                    return;
                }

                if($user == 'ASO') {
                    $this->db->where('application_no', $application_no);
                    $updateStatus = $this->db->update(
                        'nc_villages',
                        [
                            'updated_at' => date('Y-m-d H:i:s'),
                            'section_officer_proposal' => null,
                            'section_officer_verified' => null,
                            'section_officer_verified_at' => null,
                            'asst_section_officer_note' => $aso_note,
                            'asst_section_officer_user_code' => $user_code,
                            'status' => 'f',
                            'pre_user' => $from,
                            'cu_user' => 'SO',
                        ]
                    );
                }
                else {
                    $updateStatus = false;
                }

                if(!$updateStatus || $this->db->affected_rows() < 1) {
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
            }
        } else {
            $arr = array(
                'data' => [],
                'status_code' => 404
            );
            echo json_encode($arr);
        }
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
                if ($user == 'ADLR') {
                    $from = 'ADLR';
                    $task = 'Village Reverted by ADLR';
                } elseif ($user == 'DLR') {
                    $from = 'DLR';
                    $task = 'Village Reverted by DLRS';
                }

                $this->dbswitch($dist_code);

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
                    $arr = array(
                        'data' => 'Y',
                        'nc_village' => $nc_village = $this->db->query("select * from nc_villages where application_no='$application_no'")->row(),
                        'status_code' => 200,
                    );
                    echo json_encode($arr);
                    return;
                }

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

    public function apiVillageRevertSODLR() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE'])) {
            $contentType = $_SERVER['CONTENT_TYPE'];

            if (strcasecmp($contentType, 'application/x-www-form-urlencoded') === 0) {
                $dist_code = $this->UtilsModel->cleanPattern($this->input->post('dist_code'));
                $application_no = $this->UtilsModel->cleanPattern($this->input->post('application_no'));
                $note = $this->UtilsModel->cleanPattern($this->input->post('note'));
                $user_code = $this->input->post('user_code');
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
                    $this->db->where('application_no', $application_no);
                    $updateStatus = $this->db->update(
                        'nc_villages',
                        [
                            'updated_at' => date('Y-m-d H:i:s'),
                            'dlr_proposal' => null,
                            'dlr_verified' => null,
                            'dlr_verified_at' => null,
                            'section_officer_note' => $note,
                            'section_officer_user_code' => $user_code,
                            'status' => 'e',
                            'pre_user' => $from,
                            'cu_user' => 'DLR',
                        ]
                    );
                }
                else if ($user == 'ASO') {
                    $updateStatus = false;
                }
                else {
                    $updateStatus = false;
                }

                // if ($user == 'ADLR') {
                //     $this->db->where('application_no', $application_no)
                //         ->update(
                //             'nc_villages',
                //             [
                //                 'updated_at' => date('Y-m-d H:i:s'),
                //                 'dc_proposal' => null,
                //                 'dc_verified' => null,
                //                 'dc_verified_at' => null,
                //                 'dlr_note' => $dlr_note,
                //                 'dlr_user_code' => $user_code,
                //                 'status' => 'B',
                //                 'pre_user' => $from,
                //                 'cu_user' => 'DC',
                //             ]
                //         );
                // } elseif ($user == 'DLR') {
                //     $this->db->where('application_no', $application_no)
                //         ->update(
                //             'nc_villages',
                //             [
                //                 'updated_at' => date('Y-m-d H:i:s'),
                //                 'dc_proposal' => null,
                //                 'dc_verified' => null,
                //                 'dc_verified_at' => null,
                //                 'adlr_note' => $dlr_note,
                //                 'adlr_user_code' => $user_code,
                //                 'status' => 'B',
                //                 'pre_user' => $from,
                //                 'cu_user' => 'DC',
                //             ]
                //         );
                // }
                
                if(!$updateStatus || $this->db->affected_rows() < 1) {
                    $this->db->trans_rollback();
                    $arr = array(
                        'data' => [],
                        'status_code' => 500
                    );
                    echo json_encode($arr);
                    return;
                }

                if(!$this->db->trans_status()) {
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

    public function apiVillageRevert()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE'])) {
            $contentType = $_SERVER['CONTENT_TYPE'];

            if (strcasecmp($contentType, 'application/x-www-form-urlencoded') === 0) {
                $dist_code = $this->UtilsModel->cleanPattern($this->input->post('dist_code'));
                $application_no = $this->UtilsModel->cleanPattern($this->input->post('application_no'));
                $note = $this->UtilsModel->cleanPattern($this->input->post('note'));
                $user_code = $this->input->post('user_code');
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
                }
                else if ($user == 'ASO') {
                    $updateStatus = false;
                }
                else {
                    $updateStatus = false;
                }

                // if ($user == 'ADLR') {
                //     $this->db->where('application_no', $application_no)
                //         ->update(
                //             'nc_villages',
                //             [
                //                 'updated_at' => date('Y-m-d H:i:s'),
                //                 'dc_proposal' => null,
                //                 'dc_verified' => null,
                //                 'dc_verified_at' => null,
                //                 'dlr_note' => $dlr_note,
                //                 'dlr_user_code' => $user_code,
                //                 'status' => 'B',
                //                 'pre_user' => $from,
                //                 'cu_user' => 'DC',
                //             ]
                //         );
                // } elseif ($user == 'DLR') {
                //     $this->db->where('application_no', $application_no)
                //         ->update(
                //             'nc_villages',
                //             [
                //                 'updated_at' => date('Y-m-d H:i:s'),
                //                 'dc_proposal' => null,
                //                 'dc_verified' => null,
                //                 'dc_verified_at' => null,
                //                 'adlr_note' => $dlr_note,
                //                 'adlr_user_code' => $user_code,
                //                 'status' => 'B',
                //                 'pre_user' => $from,
                //                 'cu_user' => 'DC',
                //             ]
                //         );
                // }
                
                if(!$updateStatus || $this->db->affected_rows() < 1) {
                    $this->db->trans_rollback();
                    $arr = array(
                        'data' => [],
                        'status_code' => 500
                    );
                    echo json_encode($arr);
                    return;
                }

                if(!$this->db->trans_status()) {
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

    /** Department Certify */
    public function apiDepartCertify()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE'])) {
            $contentType = $_SERVER['CONTENT_TYPE'];

            if (strcasecmp($contentType, 'application/x-www-form-urlencoded') === 0) {
                $dist_code = $this->UtilsModel->cleanPattern($this->input->post('dist_code'));
                $application_no = $this->UtilsModel->cleanPattern($this->input->post('application_no'));
                $depart_note = $this->UtilsModel->cleanPattern($this->input->post('depart_note'));
                $user_code = $this->UtilsModel->cleanPattern($this->input->post('user_code'));

                $this->dbswitch($dist_code);

                $proceeding_id_max = $this->db->query("select max(proceeding_id) as id from settlement_proceeding where case_no = '$application_no'")->row()->id;
                $insPetProceed = array(
                    'case_no' => $application_no,
                    'proceeding_id' => !$proceeding_id_max ? 1 : (int) $proceeding_id_max + 1,
                    'date_of_hearing' => date('Y-m-d h:i:s'),
                    'next_date_of_hearing' => date('Y-m-d h:i:s'),
                    'note_on_order' => $depart_note,
                    'status' => 'C',
                    'user_code' => $user_code,
                    'date_entry' => date('Y-m-d h:i:s'),
                    'operation' => 'E',
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'office_from' => 'DEPT',
                    'office_to' => 'CO',
                    'task' => 'Village verified by Department',
                );
                $this->db->insert('settlement_proceeding', $insPetProceed);

                $this->db->where('application_no', $application_no)
                    ->update(
                        'nc_villages',
                        [
                            'updated_at' => date('Y-m-d H:i:s'),
                            'depart_verified' => 'Y',
                            'depart_verified_at' => date('Y-m-d H:i:s'),
                            'depart_note' => $depart_note,
                            'depart_user_code' => $user_code,
                            'status' => 'C',
                            'pre_user' => 'DEPT',
                            'cu_user' => 'CO',
                        ]
                    );

                if ($this->db->affected_rows() > 0) {
                    $arr = array(
                        'data' => 'Y',
                        'nc_village' => $nc_village = $this->db->query("select * from nc_villages where application_no='$application_no'")->row(),
                        'status_code' => 200,
                    );
                    echo json_encode($arr);
                    return;
                }

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

    /** ADS Certify */
    public function apiAdsCertify()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE'])) {
            $contentType = $_SERVER['CONTENT_TYPE'];

            if (strcasecmp($contentType, 'application/x-www-form-urlencoded') === 0) {

                $dist_code = $this->UtilsModel->cleanPattern($this->input->post('dist_code'));
                $application_no = $this->UtilsModel->cleanPattern($this->input->post('application_no'));
                $ads_note = $this->UtilsModel->cleanPattern($this->input->post('ads_note'));
                $map_dir_path = $this->input->post('map_dir_path');
                //                $pdf = $this->UtilsModel->cleanPattern($this->input->post('pdf'));

                $this->dbswitch($dist_code);

                $proceeding_id_max = $this->db->query("select max(proceeding_id) as id from settlement_proceeding where case_no = '$application_no'")->row()->id;
                $insPetProceed = array(
                    'case_no' => $application_no,
                    'proceeding_id' => !$proceeding_id_max ? 1 : (int) $proceeding_id_max + 1,
                    'date_of_hearing' => date('Y-m-d h:i:s'),
                    'next_date_of_hearing' => date('Y-m-d h:i:s'),
                    'note_on_order' => $ads_note,
                    'status' => 'C',
                    'user_code' => $this->session->userdata('user_code'),
                    'date_entry' => date('Y-m-d h:i:s'),
                    'operation' => 'E',
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'office_from' => 'ADS',
                    'office_to' => 'DEPT',
                    'task' => 'Map Uploaded by ADS',
                );
                $this->db->insert('settlement_proceeding', $insPetProceed);

                $this->db->where('application_no', $application_no)
                    ->update(
                        'nc_villages',
                        [
                            'updated_at' => date('Y-m-d H:i:s'),
                            'map_dir_path' => $map_dir_path,
                            'ads_verified' => 'Y',
                            'ads_verified_at' => date('Y-m-d H:i:s'),
                            'ads_note' => $ads_note,
                        ]
                    );

                if ($this->db->affected_rows() > 0) {
                    $arr = array(
                        'data' => 'Y',
                        'nc_village' => $nc_village = $this->db->query("select * from nc_villages where application_no='$application_no'")->row(),
                        'status_code' => 200,
                    );
                    echo json_encode($arr);
                    return;
                }

                $arr = array(
                    'data' => 'N',
                    'status_code' => 200,
                );
                echo json_encode($arr);
            } else {
                $arr = array(
                    'data' => null,
                    'status_code' => 404,
                );
                echo json_encode($arr);
            }
        } else {
            $arr = array(
                'data' => null,
                'status_code' => 404,
            );
            echo json_encode($arr);
        }
    }

    /** JS Notification */
    public function apiJsNotification()
    {
        $dist_code = $this->UtilsModel->cleanPattern($this->input->post('dist_code'));
        $uuid = $this->UtilsModel->cleanPattern($this->input->post('uuid'));
        $js_note = $this->UtilsModel->cleanPattern($this->input->post('js_note'));
        $user_code = $this->input->post('user_code');

        $this->dbswitch($dist_code);

        $this->db->where('uuid', $uuid)
            ->update(
                'nc_villages',
                [
                    'updated_at' => date('Y-m-d H:i:s'),
                    'js_verified' => 'Y',
                    'js_verified_at' => date('Y-m-d H:i:s'),
                    'js_note' => $js_note,
                    'js_user_code' => $user_code,
                    'status' => 'E',
                ]
            );

        if ($this->db->affected_rows() > 0) {
            $arr = array(
                'data' => 'Y',
                'nc_village' => $nc_village = $this->db->query("select * from nc_villages where uuid='$uuid'")->row(),
                'status_code' => 200,
            );
            echo json_encode($arr);
            return;
        }

        $arr = array(
            'data' => 'N',
            'status_code' => 200,
        );
        echo json_encode($arr);
    }

    /** SO, ASO Certify */
    public function apiVillageProcessReverted() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE'])) {
            $contentType = $_SERVER['CONTENT_TYPE'];

            if (strcasecmp($contentType, 'application/x-www-form-urlencoded') === 0) {
                $dist_code = $this->UtilsModel->cleanPattern($this->input->post('dist_code'));
                $application_no = $this->UtilsModel->cleanPattern($this->input->post('application_no'));
                $note = $this->UtilsModel->cleanPattern($this->input->post('note'));
                $remarks = $this->input->post('remarks');
                $user_code = $this->input->post('user_code');
                $user_name = $this->input->post('user_name');
                $user = $this->input->post('user');
                $proceeding_type = $this->input->post('proceeding_type');

                $this->dbswitch($dist_code);

                $this->db->trans_begin();
                try{
                    $status = '';
                    $nc_village_data = [];
                    if ($user == 'section_officer') {
                        if($proceeding_type == '1st_to_2nd'){
                            $status = 'a';
                            $from = 'Section Officer (Survey & Settlement)';
                            $task = 'Village verified by Section Officer';
                            $nc_village_data = [
                                                    'select_village_on_prop_sign_from_so' => 1,
                                                    'updated_at' => date('Y-m-d H:i:s'),
                                                    'section_officer_verified' => 'Y',
                                                    'section_officer_verified_at' => date('Y-m-d H:i:s'),
                                                    'section_officer_note' => $note,
                                                    'section_officer_user_code' => $user_code,
                                                    'section_officer' => $user_name,
                                                    'status' => $status,
                                                    'pre_user' => 'SO',
                                                    'cu_user' => 'SO',
                                                ];
                        }elseif($proceeding_type == '2nd_to_1st'){
                            $status = 'A';
                            $from = 'DLR';
                            $to = 'Section Officer (Survey & Settlement)';
                            $task = 'Village pulled backed to 1st proceeding by Section Officer';
                            $nc_village_data = [
                                                    'updated_at' => date('Y-m-d H:i:s'),
                                                    'section_officer_verified' => NULL,
                                                    'section_officer_verified_at' => NULL,
                                                    'section_officer_note' => NULL,
                                                    'section_officer_user_code' => $user_code,
                                                    'section_officer' => $user_name,
                                                    'status' => $status,
                                                    'pre_user' => 'SO',
                                                    'cu_user' => 'SO',
                                                ];

                        }else{
                            throw new Exception("Invalid request.");
                        }
                    } elseif ($user == 'asst_section_officer') {
                        if($proceeding_type == '1st_to_2nd'){
                            $status = 'c';
                            $from = 'Assistant Section Officer (Survey & Settlement)';
                            $task = 'Village verified by Assistant Section Officer';
                            $nc_village_data = [
                                                    'select_village_on_prop_sign_from_so' => 1,
                                                    'updated_at' => date('Y-m-d H:i:s'),
                                                    'asst_section_officer_verified' => 'Y',
                                                    'asst_section_officer_verified_at' => date('Y-m-d H:i:s'),
                                                    'asst_section_officer_note' => $note,
                                                    'asst_section_officer_user_code' => $user_code,
                                                    'asst_section_officer' => $user_name,
                                                    'status' => $status,
                                                    'pre_user' => 'ASO',
                                                    'cu_user' => 'ASO',
                                                ];
                        }elseif($proceeding_type == '2nd_to_1st'){
                            $req_nc_village = $this->db->where('application_no', $application_no)->get('nc_villages')->row();
                            if($req_nc_village->section_officer_proposal_id){
                                // Proposal has been made on SO level
                                $status = 'b';
                            }else{
                                // Proposal Just Forwarded from SO (Old Process)
                                $status = 'A';
                            }
                            $from = 'SO';
                            $to = 'Assistant Section Officer (Survey & Settlement)';
                            $task = 'Village pulled backed to 1st proceeding by Assistant Section Officer';
                            $nc_village_data = [
                                                    'updated_at' => date('Y-m-d H:i:s'),
                                                    'asst_section_officer_verified' => NULL,
                                                    'asst_section_officer_verified_at' => NULL,
                                                    'asst_section_officer_note' => NULL,
                                                    'asst_section_officer_user_code' => $user_code,
                                                    'asst_section_officer' => $user_name,
                                                    'status' => $status,
                                                    'pre_user' => 'ASO',
                                                    'cu_user' => 'ASO',
                                                ];

                        }else{
                            throw new Exception("Invalid request.");
                        }
                    }

                    $proceeding_id_max = $this->db->query("select max(proceeding_id) as id from settlement_proceeding where case_no = '$application_no'")->row()->id;

                    $insPetProceed = array(
                        'case_no' => $application_no,
                        'proceeding_id' => !$proceeding_id_max ? 1 : (int) $proceeding_id_max + 1,
                        'date_of_hearing' => date('Y-m-d h:i:s'),
                        'next_date_of_hearing' => date('Y-m-d h:i:s'),
                        'note_on_order' => $remarks,
                        'status' => $status,
                        'user_code' => $user_code,
                        'date_entry' => date('Y-m-d h:i:s'),
                        'operation' => 'E',
                        'ip' => $_SERVER['REMOTE_ADDR'],
                        'office_from' => $from,
                        'office_to' => 'DEPT',
                        'task' => $task,
                    );
                    $this->db->insert('settlement_proceeding', $insPetProceed);

                    $this->db->where('application_no', $application_no)->update('nc_villages', $nc_village_data);

                    $dlr_proposal = $this->db->query("SELECT dlr_proposal_id FROM nc_villages WHERE application_no=?", [$application_no])->row();
                    if(!empty($dlr_proposal)) {
                        $this->db->where([
                            'id' => $dlr_proposal->dlr_proposal_id
                        ]);
                        $dlr_proposal_update = $this->db->update('nc_village_proposal', ['reverted' => null, 'section_officer_verified' => null]);
                    }

                    if ($this->db->affected_rows() > 0) {
                        $this->db->trans_commit();
                        $arr = array(
                            'data' => 'Y',
                            'nc_village' => $nc_village = $this->db->query("select * from nc_villages where application_no='$application_no'")->row(),
                            'status_code' => 200,
                        );
                        echo json_encode($arr);
                        return;
                    }else{
                        throw new Exception('Something went wrong. Please try again later.');
                    }
                }catch(Exception $e){
                    $this->db->trans_rollback();
                    $arr = array(
                        'data' => [],
                        'message' => $e->getMessage(),
                        'status_code' => 404
                    );
                    // echo json_encode($arr);
                    // $arr = array(
                    //     'data' => 'N',
                    //     'nc_village' => $nc_village = $this->db->query("select * from nc_villages where application_no='$application_no'")->row(),
                    //     'status_code' => 200,
                    // );
                    echo json_encode($arr);
                }
 
            } else {
                $arr = array(
                    'data' => [],
                    'message' => 'Something went wrong. Please try again later',
                    'status_code' => 404
                );
                echo json_encode($arr);
            }
        } else {
            $arr = array(
                'data' => [],
                'message' => 'Request type must be POST',
                'status_code' => 404
            );
            echo json_encode($arr);
        }
    }
    public function apiVillageProcess()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE'])) {
            $contentType = $_SERVER['CONTENT_TYPE'];

            if (strcasecmp($contentType, 'application/x-www-form-urlencoded') === 0) {
                $dist_code = $this->UtilsModel->cleanPattern($this->input->post('dist_code'));
                $application_no = $this->UtilsModel->cleanPattern($this->input->post('application_no'));
                $note = $this->UtilsModel->cleanPattern($this->input->post('note'));
                $remarks = $this->input->post('remarks');
                $user_code = $this->input->post('user_code');
                $user_name = $this->input->post('user_name');
                $user = $this->input->post('user');
                $proceeding_type = $this->input->post('proceeding_type');

                $this->dbswitch($dist_code);

                $this->db->trans_begin();
                try{
                    $status = '';
                    $nc_village_data = [];
                    if ($user == 'section_officer') {
                        if($proceeding_type == '1st_to_2nd'){
                            $status = 'a';
                            $from = 'Section Officer (Survey & Settlement)';
                            $task = 'Village verified by Section Officer';
                            $nc_village_data = [
                                                    'select_village_on_prop_sign_from_so' => 1,
                                                    'updated_at' => date('Y-m-d H:i:s'),
                                                    'section_officer_verified' => 'Y',
                                                    'section_officer_verified_at' => date('Y-m-d H:i:s'),
                                                    'section_officer_note' => $note,
                                                    'section_officer_user_code' => $user_code,
                                                    'section_officer' => $user_name,
                                                    'status' => $status,
                                                    'pre_user' => 'SO',
                                                    'cu_user' => 'SO',
                                                ];
                        }elseif($proceeding_type == '2nd_to_1st'){
                            $status = 'A';
                            $from = 'DLR';
                            $to = 'Section Officer (Survey & Settlement)';
                            $task = 'Village pulled backed to 1st proceeding by Section Officer';
                            $nc_village_data = [
                                                    'updated_at' => date('Y-m-d H:i:s'),
                                                    'section_officer_verified' => NULL,
                                                    'section_officer_verified_at' => NULL,
                                                    'section_officer_note' => NULL,
                                                    'section_officer_user_code' => $user_code,
                                                    'section_officer' => $user_name,
                                                    'status' => $status,
                                                    'pre_user' => 'SO',
                                                    'cu_user' => 'SO',
                                                ];

                        }else{
                            throw new Exception("Invalid request.");
                        }
                    } elseif ($user == 'asst_section_officer') {
                        if($proceeding_type == '1st_to_2nd'){
                            $status = 'c';
                            $from = 'Assistant Section Officer (Survey & Settlement)';
                            $task = 'Village verified by Assistant Section Officer';
                            $nc_village_data = [
                                                    'select_village_on_prop_sign_from_so' => 1,
                                                    'updated_at' => date('Y-m-d H:i:s'),
                                                    'asst_section_officer_verified' => 'Y',
                                                    'asst_section_officer_verified_at' => date('Y-m-d H:i:s'),
                                                    'asst_section_officer_note' => $note,
                                                    'asst_section_officer_user_code' => $user_code,
                                                    'asst_section_officer' => $user_name,
                                                    'status' => $status,
                                                    'pre_user' => 'ASO',
                                                    'cu_user' => 'ASO',
                                                ];
                        }elseif($proceeding_type == '2nd_to_1st'){
                            $req_nc_village = $this->db->where('application_no', $application_no)->get('nc_villages')->row();
                            if($req_nc_village->section_officer_proposal_id){
                                // Proposal has been made on SO level
                                $status = 'b';
                            }else{
                                // Proposal Just Forwarded from SO (Old Process)
                                $status = 'A';
                            }
                            $from = 'SO';
                            $to = 'Assistant Section Officer (Survey & Settlement)';
                            $task = 'Village pulled backed to 1st proceeding by Assistant Section Officer';
                            $nc_village_data = [
                                                    'updated_at' => date('Y-m-d H:i:s'),
                                                    'asst_section_officer_verified' => NULL,
                                                    'asst_section_officer_verified_at' => NULL,
                                                    'asst_section_officer_note' => NULL,
                                                    'asst_section_officer_user_code' => $user_code,
                                                    'asst_section_officer' => $user_name,
                                                    'status' => $status,
                                                    'pre_user' => 'ASO',
                                                    'cu_user' => 'ASO',
                                                ];

                        }else{
                            throw new Exception("Invalid request.");
                        }
                    }

                    $proceeding_id_max = $this->db->query("select max(proceeding_id) as id from settlement_proceeding where case_no = '$application_no'")->row()->id;

                    $insPetProceed = array(
                        'case_no' => $application_no,
                        'proceeding_id' => !$proceeding_id_max ? 1 : (int) $proceeding_id_max + 1,
                        'date_of_hearing' => date('Y-m-d h:i:s'),
                        'next_date_of_hearing' => date('Y-m-d h:i:s'),
                        'note_on_order' => $remarks,
                        'status' => $status,
                        'user_code' => $user_code,
                        'date_entry' => date('Y-m-d h:i:s'),
                        'operation' => 'E',
                        'ip' => $_SERVER['REMOTE_ADDR'],
                        'office_from' => $from,
                        'office_to' => 'DEPT',
                        'task' => $task,
                    );
                    $this->db->insert('settlement_proceeding', $insPetProceed);

                    $this->db->where('application_no', $application_no)->update('nc_villages', $nc_village_data);

                    if ($this->db->affected_rows() > 0) {
                        $this->db->trans_commit();
                        $arr = array(
                            'data' => 'Y',
                            'nc_village' => $nc_village = $this->db->query("select * from nc_villages where application_no='$application_no'")->row(),
                            'status_code' => 200,
                        );
                        echo json_encode($arr);
                        return;
                    }else{
                        throw new Exception('Something went wrong. Please try again later.');
                    }
                }catch(Exception $e){
                    $this->db->trans_rollback();
                    $arr = array(
                        'data' => [],
                        'message' => $e->getMessage(),
                        'status_code' => 404
                    );
                    // echo json_encode($arr);
                    // $arr = array(
                    //     'data' => 'N',
                    //     'nc_village' => $nc_village = $this->db->query("select * from nc_villages where application_no='$application_no'")->row(),
                    //     'status_code' => 200,
                    // );
                    echo json_encode($arr);
                }
 
            } else {
                $arr = array(
                    'data' => [],
                    'message' => 'Something went wrong. Please try again later',
                    'status_code' => 404
                );
                echo json_encode($arr);
            }
        } else {
            $arr = array(
                'data' => [],
                'message' => 'Request type must be POST',
                'status_code' => 404
            );
            echo json_encode($arr);
        }
    }

    /** get dags for ADS MAP */
    public function apiGetDagsMap()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE'])) {
            $contentType = $_SERVER['CONTENT_TYPE'];

            if (strcasecmp($contentType, 'application/x-www-form-urlencoded') === 0) {
                $dist_code = $this->UtilsModel->cleanPattern($this->input->post('d'));
                $subdiv_code = $this->UtilsModel->cleanPattern($this->input->post('s'));
                $cir_code = $this->UtilsModel->cleanPattern($this->input->post('c'));
                $mouza_pargona_code = $this->UtilsModel->cleanPattern($this->input->post('m'));
                $lot_no = $this->UtilsModel->cleanPattern($this->input->post('l'));
                $vill_townprt_code = $this->UtilsModel->cleanPattern($this->input->post('v'));

                $this->dbswitch($dist_code);

                $q = "SELECT * FROM chitha_basic WHERE dist_code='$dist_code' and
						subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code'
						and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code'
        				order by CAST(coalesce(dag_no_int, '0') AS numeric)";

                $query = $this->db->query($q);

                $data['dags'] = $query->result();

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

    public function apiDagCount(){
        $dist_code = $this->input->post('dist_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $vill_townprt_code = $this->input->post('vill_townprt_code');
        $vill_townprt_codes = $this->input->post('vill_townprt_codes');
        if(!empty($vill_townprt_codes) && count($vill_townprt_codes)){
            //
        }else{
            $vill_townprt_codes = [$vill_townprt_code];
        }

        $this->dbswitch($dist_code);
        $village_wise_dag_count = [];
        if(count($vill_townprt_codes)){
            foreach($vill_townprt_codes as $vill_code){
                $query = $this->makeQuery($this->db, $dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code);
                $lms = $query->result();
                foreach ($lms as $lm) {
                    $lm_s = [];
                    $lm_s['dist_code'] = $lm->dist_code;
                    $lm_s['dist_name'] = $lm->dist_name;
                    $lm_s['subdiv_code'] = $lm->subdiv_code;
                    $lm_s['subdiv_name'] = $lm->subdiv_name;
                    $lm_s['cir_code'] = $lm->cir_code;
                    $lm_s['circle_name'] = $lm->circle_name;
                    $lm_s['mouza_pargona_code'] = $lm->mouza_pargona_code;
                    $lm_s['mouza_name'] = $lm->mouza_name;
                    $lm_s['lot_no'] = $lm->lot_no;
                    $lm_s['lot_name'] = $lm->lot_name;
                    $lm_s['vill_townprt_code'] = $lm->vill_townprt_code;
                    $lm_s['vill_name'] = $lm->vill_name;
                    $lm_s['dag_count'] = $lm->dag_count;
                    $village_wise_dag_count[] = $lm_s;
                }
            
            }
        }

        // print_r($lms_all);
        return response_json(['status_code' => 200, 'data' => $village_wise_dag_count]);
    }

    public function makeQuery($connection, $dist_code, $subdiv_code, $cir_code, $mouza_code, $lot_no, $vill_townprt_code)
    {
        $sql = "SELECT
            l6.dist_code,
            l5.subdiv_code,
            l4.cir_code,
            l3.mouza_pargona_code,
            l2.lot_no,
            l1.vill_townprt_code,
            l6.loc_name AS dist_name,
            l5.loc_name AS subdiv_name,
            l4.loc_name AS circle_name,
            l3.loc_name AS mouza_name,
            l2.loc_name AS lot_name,
            l1.loc_name AS vill_name,
            COUNT(dag_no) AS dag_count
            FROM chitha_basic 
            JOIN location l1 ON chitha_basic.dist_code = l1.dist_code
                AND chitha_basic.subdiv_code = l1.subdiv_code 
                AND chitha_basic.cir_code = l1.cir_code 
                AND chitha_basic.mouza_pargona_code = l1.mouza_pargona_code
                AND chitha_basic.lot_no = l1.lot_no
                AND chitha_basic.vill_townprt_code = l1.vill_townprt_code
                -- AND l1.status = 'L'
            JOIN location l2 ON chitha_basic.dist_code = l2.dist_code
                AND chitha_basic.subdiv_code = l2.subdiv_code 
                AND chitha_basic.cir_code = l2.cir_code 
                AND chitha_basic.mouza_pargona_code = l2.mouza_pargona_code
                AND chitha_basic.lot_no = l2.lot_no
            JOIN location l3 ON chitha_basic.dist_code = l3.dist_code
                AND chitha_basic.subdiv_code = l3.subdiv_code 
                AND chitha_basic.cir_code = l3.cir_code 
                AND chitha_basic.mouza_pargona_code = l3.mouza_pargona_code 
            JOIN location l4 ON chitha_basic.dist_code = l4.dist_code
                AND chitha_basic.subdiv_code = l4.subdiv_code 
                AND chitha_basic.cir_code = l4.cir_code 
            JOIN location l5 ON chitha_basic.dist_code = l5.dist_code 
                AND chitha_basic.subdiv_code = l5.subdiv_code
            JOIN location l6 ON chitha_basic.dist_code = l6.dist_code
            WHERE 
            l2.vill_townprt_code = '00000' 
            AND l3.lot_no = '00' 
            AND l4.mouza_pargona_code = '00' 
            AND l5.cir_code = '00' 
            AND l6.subdiv_code = '00'";

        if (isset($dist_code)) {
            $sql .= " AND chitha_basic.dist_code = '$dist_code'";
        }

        if (isset($subdiv_code)) {
            $sql .= " AND chitha_basic.subdiv_code = '$subdiv_code'";
        }

        if (isset($cir_code)) {
            $sql .= " AND chitha_basic.cir_code = '$cir_code'";
        }

        if (isset($mouza_code)) {
            $sql .= " AND chitha_basic.mouza_pargona_code = '$mouza_code'";
        }

        if (isset($lot_no)) {
            $sql .= " AND chitha_basic.lot_no = '$lot_no'";
        }

        if (isset($vill_townprt_code)) {
            $sql .= " AND chitha_basic.vill_townprt_code = '$vill_townprt_code'";
        }

        // $sql .= " GROUP BY l6.loc_name, l5.loc_name, l4.loc_name, l3.loc_name, l2.loc_name, l1.loc_name";
        $sql .= " GROUP BY l6.loc_name, l6.dist_code, l5.loc_name, l5.subdiv_code, l4.loc_name, l4.cir_code, l3.loc_name, l3.mouza_pargona_code, l2.loc_name, l2.lot_no, l1.loc_name, l1.vill_townprt_code";
        // echo $sql;exit;
        return $connection->query($sql);
    }

    public function svamitvaDataEnteredVillages()
    {

        if ($this->input->get('api_key') == "chitha_application" or $_POST['api_key'] == "chitha_application") {

            if ($this->input->get('uuids')) {
                $uuids = $this->input->get('uuids');
            } else {
                $uuids = $_POST['uuids'];
            }
            $villages = [];
            foreach (json_decode(NC_DISTIRTCS) as $dist_code) {
                $this->dbswitch($dist_code);
                foreach ($uuids as $uuid) {
                    $village = $this->db->query("select * from location where uuid = '$uuid'")->row();
                    if ($village) {
                        $govt_dags = $this->DagReportModel->getGovtDags($village->dist_code, $village->subdiv_code, $village->cir_code, $village->mouza_pargona_code, $village->lot_no, $village->vill_townprt_code);
                        if (count($govt_dags) > 0) {
                            $village->count = count($govt_dags);
                            $village->location = $this->CommonModel->getLocations($village->dist_code, $village->subdiv_code, $village->cir_code, $village->mouza_pargona_code, $village->lot_no, $village->vill_townprt_code);
                            $villages[] = $village;
                        }
                    }
                }
            }
            $arr = array(
                'data' => $villages,
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
    }

    public function svamitvaDataEnteredVillagesCount()
    {

        if ($this->input->get('api_key') == "chitha_application" or $_POST['api_key'] == "chitha_application") {

            if ($this->input->get('uuids')) {
                $uuids = $this->input->get('uuids');
            } else {
                $uuids = $_POST['uuids'];
            }
            $villages = [];
            foreach (json_decode(NC_DISTIRTCS) as $dist_code) {
                $this->dbswitch($dist_code);
                foreach ($uuids as $uuid) {
                    $village = $this->db->query("select * from location where uuid = '$uuid'")->row();
                    if ($village) {
                        $govt_dags = $this->DagReportModel->getGovtDags($village->dist_code, $village->subdiv_code, $village->cir_code, $village->mouza_pargona_code, $village->lot_no, $village->vill_townprt_code);
                        if (count($govt_dags) > 0) {
                            $village->count = count($govt_dags);
                            $villages[] = $village;
                        }
                    }
                }
            }
            $arr = array(
                'data' => count($villages),
                'status_code' => 200,
            );
            echo json_encode($arr);
        } else {
            $arr = array(
                'data' => 0,
                'status_code' => 404,
            );
            echo json_encode($arr);
        }
    }

    /** get Dashboard Data */
    public function apiGetDashboardCount()
    {
        $this->db = $this->load->database('default', true);
        $d = date('Y-m-d');
        $query = $this->db->query("select * from dashboard_count where service_name = '1' and updated_at='$d'")->row();
        if ($query) {
            $data = (array) json_decode($query->json);
            $data['updated_at'] = $query->updated_at;
            $data['updated_at_time'] = $query->updated_at_time;
        } else {
            $data = $this->insertOrUpdateNcDashboardCount();
        }
        if ($data) {
            $arr = array(
                'data' => $data,
                'status_code' => 200,
            );
            echo json_encode($arr);
        } else {
            $arr = array(
                'data' => [],
                'status_code' => 404,
            );
            echo json_encode($arr);
        }
    }
    public function apiGetDistricts()
    {
        $type = $this->input->post('type');

        $this->db = $this->load->database('default', true);
        $query = $this->db->query("select * from dashboard_count where service_name = '1'")->row();
        if ($query) {
            $d = (array) json_decode($query->json);
            $data['districts'] = $d['data_dist_wise'];
            if ($data['districts']) {
                foreach ($data['districts'] as $dist_code => $data_dist) {
                    $data_dist->loc_name = $this->CommonModel->getLocations($dist_code);
                }
            }
        }
        if ($data) {
            $data['type'] = $type;
            $arr = array(
                'data' => $data,
                'status_code' => 200,
            );
            echo json_encode($arr);
        } else {
            $data['type'] = $type;
            $arr = array(
                'data' => [],
                'status_code' => 404,
            );
            echo json_encode($arr);
        }
    }

    public function insertOrUpdateNcDashboardCount()
    {
        $verified_lm_count = 0;
        $certified_co_count = 0;
        $digi_signature_dc_count = 0;
        $data_dist_wise = [];

        foreach (json_decode(NC_DISTIRTCS) as $dist_code) {
            $this->dbswitch($dist_code);
            $verified_lm_count_dist = $this->db->query("select * from nc_villages where lm_verified = 'Y' ")->num_rows();
            $certified_co_count_dist = $this->db->query("select * from nc_villages where co_verified = 'Y' ")->num_rows();
            $digi_signature_dc_count_dist = $this->db->query("select * from nc_villages where dc_verified = 'Y' ")->num_rows();

            $verified_lm_count += $verified_lm_count_dist;
            $certified_co_count += $certified_co_count_dist;
            $digi_signature_dc_count += $digi_signature_dc_count_dist;

            $data_dist_wise[$dist_code] = [
                'verified_lm_count' => $verified_lm_count_dist,
                'certified_co_count' => $certified_co_count_dist,
                'digi_signature_dc_count' => $digi_signature_dc_count_dist,
            ];
        }
        $data = [
            'verified_lm_count' => $verified_lm_count,
            'certified_co_count' => $certified_co_count,
            'digi_signature_dc_count' => $digi_signature_dc_count,
            'data_dist_wise' => $data_dist_wise,
            'updated_at' => date('Y-m-d'),
            'updated_at_time' => date('H:i:s'),
        ];
        $json = json_encode($data);
        $this->db = $this->load->database('default', true);
        $query = $this->db->query("select * from dashboard_count where service_name = '1'")->row();

        if ($query) {
            $this->db->set('json', $json);
            $this->db->set('updated_at', date('Y-m-d'));
            $this->db->set('updated_at_time', date('H:i:s'));
            $this->db->where('service_name', '1');
            $this->db->update('dashboard_count');
        } else {
            $this->db->insert('dashboard_count', [
                'service_name' => '1',
                'json' => $json,
                'updated_at' => date('Y-m-d'),
                'updated_at_time' => date('H:i:s'),
            ]);
        }
        return ($data);
    }

    public function getNcVillagesByStatus()
    {
        $dist_code = $this->input->post('dist_code');
        $type = $this->input->post('type');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');

        $this->dbswitch($dist_code);

        $query = "select
        (select loc_name from location where subdiv_code=ncv.subdiv_code and cir_code=ncv.cir_code and mouza_pargona_code='00') as circle,
        (select loc_name from location where subdiv_code=ncv.subdiv_code and cir_code=ncv.cir_code and mouza_pargona_code=ncv.mouza_pargona_code and lot_no='00') as mouza,
        (select loc_name from location where subdiv_code=ncv.subdiv_code and cir_code=ncv.cir_code and mouza_pargona_code=ncv.mouza_pargona_code and lot_no=ncv.lot_no and vill_townprt_code='00000') as lot,
        (select loc_name from location where subdiv_code=ncv.subdiv_code and cir_code=ncv.cir_code and mouza_pargona_code=ncv.mouza_pargona_code and lot_no=ncv.lot_no and vill_townprt_code=ncv.vill_townprt_code) as village,
        ncv.dc_verified_at,ncv.status,ncv.application_no,ncv.lm_note,ncv.co_verified,ncv.co_note,ncv.dc_verified,ncv.dist_code,ncv.co_verified_at,ncv.dlr_verified,ncv.dc_note from nc_villages ncv";

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

        if ($type) {
            if ($type == 'verified_lm') {
                $query = $query . " and ncv.lm_verified = 'Y'";
            }
            if ($type == 'certified_co') {
                $query = $query . " and ncv.co_verified = 'Y'";
            }
            if ($type == 'digi_signature_dc') {
                $query = $query . " and ncv.dc_verified = 'Y'";
            }
        }

        $data = $this->db->query($query);

        if ($data) {
            $arr = array(
                'data' => $data->result_array(),
                'status_code' => 200,
            );
            echo json_encode($arr);
        } else {
            $arr = array(
                'data' => [],
                'status_code' => 404,
            );
            echo json_encode($arr);
        }
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

        $arr = array(
            'data' => $d_list,
            'status_code' => 200,
        );
        echo json_encode($arr);
        return;
    }

    /** getDlrPendingVillages */
    public function getDlrPendingVillagesNew()
    {
        $d_list = (array) $this->input->post('d');
        $data['count'] = 0;
        $data['pro_count'] = 0;
        $data['revert_proposal'] = 0;
        $data['forwarded_name_change'] = 0;
        $data['dismiss_villages_pending_count'] = 0;

        foreach ($d_list as $k => $d) {
            $dist_code = $d['dist_code'];
            $this->dbswitch($dist_code);

            $data['count'] += $this->db->query("select count(*) as count from nc_villages where
 				dist_code='$dist_code' and status = 'I'")->row()->count;
             $data['pro_count'] += $this->db->query("select count(*) as count from nc_villages where
 				dist_code='$dist_code' and status = 'L' and dlr_proposal is null")->row()->count;
            $data['dismiss_villages_pending_count'] += $this->db->query("select count(*) as count from nc_villages where
 				dist_code='$dist_code' and status = 'g'")->row()->count;

            // $data['revert_proposal'] += $this->db->query("select count(*) as count from nc_village_proposal where
 			// 	dist_code='$dist_code' and status = 'E' and adlr_verified='Y'")->row()->count;
            $data['forwarded_name_change'] += $this->db->query("select count(*) as count from nc_villages where
                dist_code='$dist_code' and (status = 'P' or status ='M' or status = 'N')")->row()->count;

            // $data['revert_proposal'] += $this->db->query("select count(*) as count from nc_village_proposal where
 			// 	dist_code='$dist_code' and status = 'A' and ps_verified is null and reverted='Y'")->row()->count;

            $adlr_proposal_reverted = $this->db->query("select id,dist_code,created_at,proposal_no,proposal_note,adlr_note,
            status from nc_village_proposal where status = 'E' and adlr_verified ='Y' and (user_type = 'DLR' or user_type='ADLR') order by updated_at desc ")->result();
            $ps_proposal_reverted = $this->db->query("select id,dist_code,created_at,proposal_no,proposal_note,adlr_note,ps_note,
            status from nc_village_proposal where status = 'A' and (user_type = 'DLR' or user_type='ADLR') and ps_verified is null 
            and reverted = 'Y' order by updated_at desc ")->result();
            $data['revert_proposal'] += $this->db->query("SELECT COUNT(*) AS so_reverted_count FROM nc_villages WHERE status='e' AND cu_user='DLR' AND dlr_verified IS NULL AND dlr_verified_at IS NULL AND dlr_proposal IS NULL")->row()->so_reverted_count;

            if(!empty($adlr_proposal_reverted)) {
                foreach ($adlr_proposal_reverted as $adlr_proposal) {
                    $data['revert_proposal'] += $this->db->query("SELECT COUNT(*) as adlr_reverted_count FROM nc_villages WHERE dlr_proposal_id=?", [$adlr_proposal->id])->row()->adlr_reverted_count;
                }
            }

            if(!empty($ps_proposal_reverted)) {
                foreach ($ps_proposal_reverted as $ps_proposal) {
                    $data['revert_proposal'] += $this->db->query("SELECT COUNT(*) as ps_reverted_count FROM nc_villages WHERE dlr_proposal_id=? AND status='A'", [$ps_proposal->id])->row()->ps_reverted_count;
                }
            }

        }

        $arr = array(
            'data' => $data,
            'status_code' => 200,
        );
        echo json_encode($arr);
        return;
    }
    public function getDlrPendingVillages()
    {
        $d_list = (array) $this->input->post('d');
        $data['count'] = 0;
        $data['pro_count'] = 0;
        $data['revert_proposal'] = 0;
        $data['forwarded_name_change'] = 0;
        $data['dismiss_villages_pending_count'] = 0;
        foreach ($d_list as $k => $d) {
            $dist_code = $d['dist_code'];
            $this->dbswitch($dist_code);

            $data['count'] += $this->db->query("select count(*) as count from nc_villages where
 				dist_code='$dist_code' and status = 'I'")->row()->count;

            $data['pro_count'] += $this->db->query("select count(*) as count from nc_villages where
 				dist_code='$dist_code' and status = 'L' and dlr_proposal is null")->row()->count;
            $data['dismiss_villages_pending_count'] += $this->db->query("select count(*) as count from nc_villages where
 				dist_code='$dist_code' and status = 'g'")->row()->count;
            $data['revert_proposal'] += $this->db->query("select count(*) as count from nc_village_proposal where
 				dist_code='$dist_code' and status = 'E' and adlr_verified='Y'")->row()->count;
            $data['forwarded_name_change'] += $this->db->query("select count(*) as count from nc_villages where
                dist_code='$dist_code' and (status = 'P' or status ='M' or status = 'N')")->row()->count;

            $data['revert_proposal'] += $this->db->query("select count(*) as count from nc_village_proposal where
 				dist_code='$dist_code' and status = 'A' and ps_verified is null and reverted='Y'")->row()->count;
        }

        $arr = array(
            'data' => $d_list,
            'status_code' => 200,
        );
        echo json_encode($arr);
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
            // $this->db->insert('nc_village_proposal', array(
            //     'proposal_no' => $proposal_no,
            //     'user_code' => $user_code,
            //     'user_type' => $user_type,
            //     'sign_key' => $sign_key,
            //     'updated_at' => date('Y-m-d H:i:s'),
            //     'created_at' => date('Y-m-d H:i:s'),
            //     'status' => $status,
            //     'dist_code' => $dist_code,
            //     'proposal_note' => $dlr_note,
            // ));
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

    /** get all pending village for DLR */
    public function apiGetAllPendingVillageDlr()
    {
        $d_list = (array) json_decode(NC_DISTIRTCS);

        $village = array();
        foreach ($d_list as $k => $dist_code) {
            $this->dbswitch($dist_code);

            $query = "select ll.loc_name as dist_name, l.loc_name,ncv.lm_verified_at,ncv.status,ncv.dist_code,ncv.application_no,ncv.lm_note,
				ncv.co_verified,ncv.co_note,ncv.dc_verified,ncv.dc_verified_at,ncv.dc_note,ncv.ads_verified, ncv.id as nc_village_id, ncv.case_type from
				nc_villages ncv join
				location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code
                 and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and
                 ncv.vill_townprt_code = l.vill_townprt_code
                 join location ll on ncv.dist_code = ll.dist_code";

            $query = $query . " where ncv.dist_code='$dist_code' and ll.subdiv_code ='00' and ncv.status='I'";

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

    /** get Dept Pending Villages */
    public function getDeptPendingVillages()
    {
        $d_list = (array) $this->input->post('d');
        $data['count'] = 0;
        foreach ($d_list as $k => $d) {
            $dist_code = $d['dist_code'];
            $this->dbswitch($dist_code);

            $data['count'] += $this->db->query("select count(*) as count from nc_villages where
 				dist_code='$dist_code' and status = 'A'")->row()->count;
        }

        echo json_encode($data);
        return;
    }

    /** get all pending village for Depart */
    public function apiGetAllPendingVillageDepart()
    {
        $d_list = (array) json_decode(NC_DISTIRTCS);

        $village = array();
        foreach ($d_list as $k => $dist_code) {
            $this->dbswitch($dist_code);

            $query = "select ll.loc_name as dist_name, l.loc_name,ncv.lm_verified_at,ncv.status,ncv.dist_code,ncv.application_no,ncv.lm_note,
				ncv.co_verified,ncv.co_note,ncv.dc_verified,ncv.dc_verified_at,ncv.dc_note,ncv.ads_verified from
				nc_villages ncv join
				location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code
                 and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and
                 ncv.vill_townprt_code = l.vill_townprt_code
                 join location ll on ncv.dist_code = ll.dist_code";

            $query = $query . " where ncv.dist_code='$dist_code' and ll.subdiv_code ='00' and ncv.status='A'";

            $nc_villages = $this->db->query($query)->result();
            array_push($village, $nc_villages);
        }
        echo json_encode($village);
        return;
    }

    /** get pending village for department */
    public function apiGetPendingVillageDepart()
    {
        $data = array();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE'])) {
            $contentType = $_SERVER['CONTENT_TYPE'];

            if (strcasecmp($contentType, 'application/x-www-form-urlencoded') === 0) {
                $dist_code = $this->UtilsModel->cleanPattern($this->input->post('d'));
                $application_no = $this->UtilsModel->cleanPattern($this->input->post('application_no'));

                $this->dbswitch($dist_code);

                $data['nc_village'] = $nc_village = $this->db->query("select * from nc_villages where
				application_no='$application_no' and dist_code='$dist_code'")->row();

                $data['pdf_base64'] = null;
                $pdfFilePath = FCPATH . $nc_village->chitha_dir_path;
                if (file_exists($pdfFilePath)) {
                    $pdfData = file_get_contents($pdfFilePath);
                    $data['pdf_base64'] = base64_encode($pdfData);
                }
                $data['approve_proposal'] = $this->db->select('proposal_no')
                    ->where(array(
                        'dist_code' => $dist_code,
                        'id' => $nc_village->dlr_proposal_id,
                        'user_type' => 'DLR',
                        'status' => 'A'
                    ))
                    ->get('nc_village_proposal')->row();

                $data['locations'] = $this->CommonModel->getLocations(
                    $nc_village->dist_code,
                    $nc_village->subdiv_code,
                    $nc_village->cir_code,
                    $nc_village->mouza_pargona_code,
                    $nc_village->lot_no,
                    $nc_village->vill_townprt_code
                );

                echo json_encode($data);
                return;
            } else {
                echo json_encode($data);
            }
        } else {
            echo json_encode($data);
        }
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

    public function getNcCirclesByStatus()
    {
        $dist_code = $this->input->post('dist_code');
        $type = $this->input->post('type');

        $this->dbswitch($dist_code);
        $ncData = [];

        $data = $this->db->get_where('location', array('dist_code' => $dist_code, 'subdiv_code !=' => '00', 'cir_code!=' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->result_array();

        foreach ($data as $datum) {
            $subdiv_code = $datum["subdiv_code"];
            $cir_code = $datum["cir_code"];
            $mouza_pargona_code = $datum["mouza_pargona_code"];
            $lot_no = $datum["lot_no"];
            $loc_name = $datum["loc_name"];
            $query = "select
        count(*) from nc_villages ncv";

            $query = $query . " where ncv.dist_code='$dist_code'";
            if ($subdiv_code) {
                $query = $query . " and ncv.subdiv_code = '$subdiv_code'";
            }
            if ($cir_code) {
                $query = $query . " and ncv.cir_code = '$cir_code'";
            }
            // if ($mouza_pargona_code) {
            //     $query = $query . " and ncv.mouza_pargona_code = '$mouza_pargona_code'";
            // }
            // if ($lot_no) {
            //     $query = $query . " and ncv.lot_no = '$lot_no'";
            // }

            if ($type) {
                if ($type == 'verified_lm') {
                    $query = $query . " and ncv.lm_verified = 'Y'";
                }
                if ($type == 'lm_pending') {
                    $query = $query . " and (ncv.lm_verified is null or ncv.status ='U')";
                }
                if ($type == 'sk_pending') {
                    $query = $query . " and ncv.lm_verified ='Y' and (ncv.status ='S' or ncv.status ='H')";
                }
                if ($type == 'certified_co') {
                    $query = $query . " and ncv.co_verified = 'Y'";
                }
                if ($type == 'co_pending') {
                    $query = $query . " and ncv.sk_verified ='Y' and (ncv.status ='F' or ncv.status ='O')";
                }
                if ($type == 'digi_signature_dc') {
                    $query = $query . " and ncv.dc_verified = 'Y'";
                }
                if ($type == 'dc_forwarded') {
                    $query = $query . " and ncv.dc_verified = 'Y' and (ncv.status='I' or ncv.dlr_verified = 'Y') and ncv.dc_proposal_id is not null";
                }
                if ($type == 'dc_pending') {
                    $query = $query . " and ncv.co_verified = 'Y' and (ncv.status='G' or ncv.status='K' or ncv.status='B') ";
                }
                if ($type == 'dlrs_pending') {
                    $query = $query . " and ncv.dc_verified = 'Y' and (ncv.status='I' or ncv.status='L')";
                }
                if ($type == 'dlr_forwarded') {
                    $query = $query . " and ncv.dlr_verified = 'Y' and ncv.status!='L' and ncv.dlr_proposal_id is not null";
                }
            }
            $nc = $this->db->query($query)->result_array();
            if ($nc[0]["count"] != 0) {
                $ncdatum = [
                    'loc_name' => $loc_name,
                    'count' => $nc[0]["count"],
                    'dist_code' => $dist_code,
                    'subdiv_code' => $subdiv_code,
                    'cir_code' => $cir_code,
                ];
                $ncData[] = $ncdatum;
            }
        }

        if ($data) {
            $arr = array(
                'data' => $ncData,
                'status_code' => 200,
            );
            echo json_encode($arr);
        } else {
            $arr = array(
                'data' => [],
                'status_code' => 404,
            );
            echo json_encode($arr);
        }
    }

    public function getNcMouzasByStatus()
    {

        $dist_code = $this->input->post('dist_code');
        $type = $this->input->post('type');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $type = $this->input->post('type');
        $this->dbswitch($dist_code);
        $ncData = [];

        $circle = $this->db->select('loc_name,dist_code,subdiv_code,cir_code,locname_eng')
            ->where(array(
                'dist_code' => $dist_code,
                'subdiv_code' => $subdiv_code,
                'cir_code' => $cir_code,
                'mouza_pargona_code' => '00',
                'lot_no' => '00',
                'vill_townprt_code' => '00000'
            ))->get('location')->row_array();

        $data = $this->db->get_where('location', array('dist_code' => $dist_code, 'subdiv_code =' => $subdiv_code, 'cir_code=' => $cir_code, 'mouza_pargona_code !=' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->result_array();
        foreach ($data as $datum) {
            $subdiv_code = $datum["subdiv_code"];
            $cir_code = $datum["cir_code"];
            $mouza_pargona_code = $datum["mouza_pargona_code"];
            $lot_no = $datum["lot_no"];
            $loc_name = $datum["loc_name"];
            $query = "select
        count(*) from nc_villages ncv";

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
            // if ($lot_no) {
            //     $query = $query . " and ncv.lot_no = '$lot_no'";
            // }

            if ($type) {
                if ($type == 'verified_lm') {
                    $query = $query . " and ncv.lm_verified = 'Y'";
                }
                if ($type == 'lm_pending') {
                    $query = $query . " and (ncv.lm_verified is null or ncv.status='U')";
                }
                if ($type == 'sk_pending') {
                    $query = $query . " and ncv.lm_verified ='Y' and (ncv.status ='S' or ncv.status='H')";
                }
                if ($type == 'certified_co') {
                    $query = $query . " and ncv.co_verified = 'Y'";
                }
                if ($type == 'co_pending') {
                    $query = $query . " and ncv.sk_verified ='Y' and (ncv.status='F' or ncv.status = 'O')";
                }
                if ($type == 'digi_signature_dc') {
                    $query = $query . " and ncv.dc_verified = 'Y'";
                }
                if ($type == 'dc_forwarded') {
                    $query = $query . " and ncv.dc_verified = 'Y' and (ncv.status='I' or ncv.dlr_verified = 'Y') and ncv.dc_proposal_id is not null";
                }
                if ($type == 'dc_pending') {
                    $query = $query . " and ncv.co_verified = 'Y' and (ncv.status='G' or ncv.status='K' or ncv.status='B') ";
                }
                if ($type == 'dlrs_pending') {
                    $query = $query . " and ncv.dc_verified = 'Y' and (ncv.status='I' or ncv.status='L')";
                }
                if ($type == 'dlr_forwarded') {
                    $query = $query . " and ncv.dlr_verified = 'Y' and ncv.status!='L' and ncv.dlr_proposal_id is not null";
                }
            }
            $nc = $this->db->query($query)->result_array();
            if ($nc[0]["count"] != 0) {
                $ncdatum = [
                    'loc_name' => $loc_name,
                    'circle_name' => $circle,
                    'count' => $nc[0]["count"],
                    'dist_code' => $dist_code,
                    'subdiv_code' => $subdiv_code,
                    'cir_code' => $cir_code,
                    'mouza_pargona_code' => $mouza_pargona_code,
                ];
                $ncData[] = $ncdatum;
            }
        }

        if ($data) {
            $arr = array(
                'data' => $ncData,
                'status_code' => 200,
            );
            echo json_encode($arr);
        } else {
            $arr = array(
                'data' => [],
                'status_code' => 404,
            );
            echo json_encode($arr);
        }
    }

    public function getNcLotsByStatus()
    {
        $dist_code = $this->input->post('dist_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $type = $this->input->post('type');
        $this->dbswitch($dist_code);
        $ncData = [];
        $circle = $this->db->select('loc_name,dist_code,subdiv_code,cir_code,locname_eng')
            ->where(array(
                'dist_code' => $dist_code,
                'subdiv_code' => $subdiv_code,
                'cir_code' => $cir_code,
                'mouza_pargona_code' => '00',
                'lot_no' => '00',
                'vill_townprt_code' => '00000'
            ))->get('location')->row_array();
        $data = $this->db->get_where('location', array('dist_code' => $dist_code, 'subdiv_code =' => $subdiv_code, 'cir_code=' => $cir_code, 'mouza_pargona_code' => $mouza_pargona_code, 'lot_no !=' => '00', 'vill_townprt_code' => '00000'))->result_array();
        foreach ($data as $datum) {
            $subdiv_code = $datum["subdiv_code"];
            $cir_code = $datum["cir_code"];
            $mouza_pargona_code = $datum["mouza_pargona_code"];
            $lot_no = $datum["lot_no"];
            $loc_name = $datum["loc_name"];
            $query = "select count(*) from nc_villages ncv";

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

            if ($type) {
                if ($type == 'verified_lm') {
                    $query = $query . " and ncv.lm_verified = 'Y'";
                }
                if ($type == 'lm_pending') {
                    $query = $query . " and (ncv.lm_verified is null or ncv.status='U')";
                }
                if ($type == 'sk_pending') {
                    $query = $query . " and ncv.lm_verified='Y' and (ncv.status='S' or ncv.status='H')";
                }
                if ($type == 'certified_co') {
                    $query = $query . " and ncv.co_verified = 'Y'";
                }
                if ($type == 'co_pending') {
                    $query = $query . " and ncv.sk_verified ='Y' and (ncv.status='F' or ncv.status = 'O')";
                }
                if ($type == 'digi_signature_dc') {
                    $query = $query . " and ncv.dc_verified = 'Y'";
                }
                if ($type == 'dc_forwarded') {
                    $query = $query . " and ncv.dc_verified = 'Y' and (ncv.status='I' or ncv.dlr_verified = 'Y') and ncv.dc_proposal_id is not null";
                }
                if ($type == 'dc_pending') {
                    $query = $query . " and ncv.co_verified = 'Y' and (ncv.status='G' or ncv.status='K' or ncv.status='B') ";
                }
                if ($type == 'dlrs_pending') {
                    $query = $query . " and ncv.dc_verified = 'Y' and (ncv.status='I' or ncv.status='L')";
                }
                if ($type == 'dlr_forwarded') {
                    $query = $query . " and ncv.dlr_verified = 'Y' and ncv.status!='L' and ncv.dlr_proposal_id is not null";
                }
            }
            $nc = $this->db->query($query)->result_array();
            if ($nc[0]["count"] != 0) {
                $ncdatum = [
                    'loc_name' => $loc_name,
                    'circle_name' => $circle,
                    'count' => $nc[0]["count"],
                    'dist_code' => $dist_code,
                    'subdiv_code' => $subdiv_code,
                    'cir_code' => $cir_code,
                    'mouza_pargona_code' => $mouza_pargona_code,
                    'lot_no' => $lot_no,
                ];
                $ncData[] = $ncdatum;
            }
        }

        if ($data) {
            $arr = array(
                'data' => $ncData,
                'status_code' => 200,
            );
            echo json_encode($arr);
        } else {
            $arr = array(
                'data' => [],
                'status_code' => 404,
            );
            echo json_encode($arr);
        }
    }

    public function getNcLotVillagesByStatus()
    {
        $dist_code = $this->input->post('dist_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $type = $this->input->post('type');
        $this->dbswitch($dist_code);
        $ncData = [];

        $data = $this->db->get_where('location', array('dist_code' => $dist_code, 'subdiv_code =' => $subdiv_code, 'cir_code=' => $cir_code, 'mouza_pargona_code' => $mouza_pargona_code, 'lot_no' => $lot_no, 'vill_townprt_code !=' => '00000'))->result_array();
        foreach ($data as $datum) {
            $subdiv_code = $datum["subdiv_code"];
            $cir_code = $datum["cir_code"];
            $mouza_pargona_code = $datum["mouza_pargona_code"];
            $vill_townprt_code = $datum["vill_townprt_code"];
            $lot_no = $datum["lot_no"];
            $loc_name = $datum["loc_name"];
            $circle = $this->db->select('loc_name,dist_code,subdiv_code,cir_code,locname_eng')
                ->where(array(
                    'dist_code' => $dist_code,
                    'subdiv_code' => $subdiv_code,
                    'cir_code' => $cir_code,
                    'mouza_pargona_code' => '00',
                    'lot_no' => '00',
                    'vill_townprt_code' => '00000'
                ))->get('location')->row_array();

            $query = "select dist_code, application_no,created_at,sk_verified_at,
            lm_verified_at,co_verified_at,dc_verified_at,dlr_verified,dlr_verified_at,dc_proposal_id,
             dlr_proposal_id from nc_villages ncv";

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
            if ($lot_no) {
                $query = $query . " and ncv.vill_townprt_code = '$vill_townprt_code'";
            }

            if ($type) {
                if ($type == 'verified_lm') {
                    $query = $query . " and ncv.lm_verified = 'Y'";
                }
                if ($type == 'lm_pending') {
                    $query = $query . " and (ncv.lm_verified is null or ncv.status='U')";
                }
                if ($type == 'sk_pending') {
                    $query = $query . " and ncv.lm_verified ='Y' and ( ncv.status='S' or ncv.status='H')";
                }
                if ($type == 'certified_co') {
                    $query = $query . " and ncv.co_verified = 'Y'";
                }
                if ($type == 'co_pending') {
                    $query = $query . " and ncv.sk_verified ='Y' and (ncv.status='F' or ncv.status = 'O')";
                }
                if ($type == 'digi_signature_dc') {
                    $query = $query . " and ncv.dc_verified = 'Y'";
                }
                if ($type == 'dc_forwarded') {
                    $query = $query . " and ncv.dc_verified = 'Y' and (ncv.status='I' or ncv.dlr_verified = 'Y') and ncv.dc_proposal_id is not null";
                }
                if ($type == 'dc_pending') {
                    $query = $query . " and ncv.co_verified = 'Y' and (ncv.status='G' or ncv.status='K' or ncv.status='B') ";
                }
                if ($type == 'dlrs_pending') {
                    $query = $query . " and ncv.dc_verified = 'Y' and (ncv.status='I' or ncv.status='L')";
                }
                if ($type == 'dlr_forwarded') {
                    $query = $query . " and ncv.dlr_verified = 'Y' and ncv.status!='L' and ncv.dlr_proposal_id is not null";
                }
            }
            $nc = $this->db->query($query)->result_array();
            $nco = [];
            if ($nc) {
                if ($type == 'verified_lm') {
                    $nco = $nc[0]["lm_verified_at"];
                }
                if ($type == 'lm_pending') {
                    $nco = $nc[0]["created_at"];
                }
                if ($type == 'sk_pending') {
                    $nco = $nc[0]["lm_verified_at"];
                }
                if ($type == 'certified_co') {
                    $nco = $nc[0]["co_verified_at"];
                }
                if ($type == 'co_pending') {
                    $nco = $nc[0]["sk_verified_at"];
                }
                if ($type == 'digi_signature_dc') {
                    $nco = $nc[0]["dc_verified_at"];
                }
                if ($type == 'dc_forwarded') {
                    $nco = $nc[0]["dc_verified_at"];
                }
                if ($type == 'dc_pending') {
                    $nco = $nc[0]["co_verified_at"];
                }
                if ($type == 'dlrs_pending') {
                    $nco = $nc[0]["dc_verified_at"];
                }
                if ($type == 'dlr_forwarded') {
                    $nco = $nc[0]["dlr_verified_at"];
                }
            }
            if ($nco) {
                $ncdatum = [
                    'loc_name' => $loc_name,
                    'circle_name' => $circle,
                    'verified' => $nc,
                    'dist_code' => $dist_code,
                    'subdiv_code' => $subdiv_code,
                    'cir_code' => $cir_code,
                    'mouza_pargona_code' => $mouza_pargona_code,
                    'lot_no' => $lot_no,
                    'vill_townprt_code' => $vill_townprt_code,
                ];
                $ncData[] = $ncdatum;
            }
        }

        if ($data) {
            $arr = array(
                'data' => $ncData,
                'status_code' => 200,
            );
            echo json_encode($arr);
        } else {
            $arr = array(
                'data' => [],
                'status_code' => 404,
            );
            echo json_encode($arr);
        }
    }

    /** get Dashboard Data */
    public function apiGetNcDashboardCount()
    {
        $json = $this->getDashboardData();
        $data = (array) json_decode(json_encode($json), true);
        $data['updated_at'] = $json["updated_at"];
        $data['updated_at_time'] = $json["updated_at_time"];
        if ($data) {
            $arr = array(
                'data' => $data,
                'status_code' => 200,
            );
            echo json_encode($arr);
        } else {
            $arr = array(
                'data' => [],
                'status_code' => 404,
            );
            echo json_encode($arr);
        }
    }
    public function apiGetNcDistricts()
    {
        $type = "";

        $this->db = $this->load->database('default', true);
        $json = $this->getDashboardData();
        $d = (array) json_decode(json_encode($json), true);
        $data['districts'] = $d['data_dist_wise'];
        if ($data['districts']) {
            foreach ($data['districts'] as $dist_code => $data_dist) {
                $this->dbswitch($dist_code);
                $data['districts'][(string) $dist_code]["loc_name"] = $this->CommonModel->getLocations((string) $dist_code);
            }
        }
        if ($data) {
            $data['type'] = $type;
            $arr = array(
                'data' => $data,
                'status_code' => 200,
            );
            echo json_encode($arr);
        } else {
            $data['type'] = $type;
            $arr = array(
                'data' => [],
                'status_code' => 404,
            );
            echo json_encode($arr);
        }
    }

    public function getDashboardData()
    {
        $dlr_skipped_to_another_user_module_enable = (isset($_REQUEST['dlr_skipped_to_another_user_module_enable']) && $_REQUEST['dlr_skipped_to_another_user_module_enable']) ? TRUE : FALSE;
        
        $verified_lm_count = 0;
        $pending_lm_count = 0;
        $pending_sk_count = 0;
        $certified_co_count = 0;
        $pending_co_count = 0;
        $digi_signature_dc_count = 0;
        $dc_forwarded = 0;
        $pending_dc_count = 0;
        $dlrs_pending = 0;
        $dlrs_forwarded = 0;
        $pending_at_ps = 0;
        $pending_at_sec = 0;
        $pending_at_js = 0;
        $pending_at_so = 0;
        $pending_at_aso = 0;
        $data_dist_wise = [];
        $dlr_revert_proposal = 0;

        foreach (json_decode(NC_DISTIRTCS) as $dist_code) {
            $this->dbswitch($dist_code);
            $verified_lm_count_dist = $this->db->query("select * from nc_villages where lm_verified = 'Y' ")->num_rows();
            $pending_lm_count_dist = $this->db->query("select * from nc_villages where lm_verified is null or status='U'")->num_rows();
            $pending_sk_count_dist = $this->db->query("select * from nc_villages where lm_verified ='Y' and (status='S' or status='H')")->num_rows();
            $certified_co_count_dist = $this->db->query("select * from nc_villages where co_verified = 'Y' ")->num_rows();
            $pending_co_count_dist = $this->db->query("select * from nc_villages where sk_verified = 'Y'  and (status='F' or status='O' or status='J')")->num_rows();
            $digi_signature_dc_count_dist = $this->db->query("select * from nc_villages where dc_verified = 'Y' ")->num_rows();
            $dc_forwarded_dist = $this->db->query("select * from nc_villages where dc_verified = 'Y' and (status='I' or dlr_verified = 'Y') and dc_proposal_id is not null ")->num_rows();
            $pending_dc_count_dist = $this->db->query("select * from nc_villages where co_verified = 'Y' and (status='G' or status='K' or status ='B')")->num_rows();
            $dlrs_pending_dist = $this->db->query("select * from nc_villages where dc_verified = 'Y' and (status='I' or status='L')")->num_rows();
            $dlrs_forwarded_dist = $this->db->query("select * from nc_villages where dlr_verified = 'Y' and status != 'L' and dlr_proposal_id is not null ")->num_rows();

            /**Added on 02-05-2024 - Start */
            $adlr_proposal_reverted = $this->db->query("select id,dist_code,created_at,proposal_no,proposal_note,adlr_note,
            status from nc_village_proposal where status = 'E' and adlr_verified ='Y' and (user_type = 'DLR' or user_type='ADLR') order by updated_at desc ")->result();
            $ps_proposal_reverted = $this->db->query("select id,dist_code,created_at,proposal_no,proposal_note,adlr_note,ps_note,
            status from nc_village_proposal where status = 'A' and (user_type = 'DLR' or user_type='ADLR') and ps_verified is null 
            and reverted = 'Y' order by updated_at desc ")->result();
            $dlr_revert_proposal += $this->db->query("SELECT COUNT(*) AS so_reverted_count FROM nc_villages WHERE status='e' AND cu_user='DLR' AND dlr_verified IS NULL AND dlr_verified_at IS NULL AND dlr_proposal IS NULL")->row()->so_reverted_count;

            if(!empty($adlr_proposal_reverted)) {
                foreach ($adlr_proposal_reverted as $adlr_proposal) {
                    $dlr_revert_proposal += $this->db->query("SELECT COUNT(*) as adlr_reverted_count FROM nc_villages WHERE dlr_proposal_id=?", [$adlr_proposal->id])->row()->adlr_reverted_count;
                }
            }

            if(!empty($ps_proposal_reverted)) {
                foreach ($ps_proposal_reverted as $ps_proposal) {
                    $dlr_revert_proposal += $this->db->query("SELECT COUNT(*) as ps_reverted_count FROM nc_villages WHERE dlr_proposal_id=? AND status='A'", [$ps_proposal->id])->row()->ps_reverted_count;
                }
            }
            /**Added on 02-05-2024 - end */

            $pending_at_ps_dist = $pending_at_sec_dist = $pending_at_js_dist = $pending_at_so_dist = $pending_at_aso_dist = 0;
            if($dlr_skipped_to_another_user_module_enable){

                $pending_at_ps_dist = $this->db->query("select count(*) as count from nc_village_proposal where dist_code='$dist_code' and (user_type = 'DLR' or user_type='ADLR') and forwarded_to_user_type is null and status='A' and reverted is null and ps_verified is null")->row()->count;
                $pending_at_ps_dist += $this->db->query("select count(*) as count from nc_village_proposal where dist_code='$dist_code' and (user_type = 'DLR' or user_type='ADLR') and status='A' and ps_verified = 'Y' and reverted ='Y' and secretary_verified is null")->row()->count;

                $pending_at_so_dist = $this->db->query("select count(*) as count from nc_village_proposal where dist_code='$dist_code' and (user_type = 'DLR' or user_type='ADLR') and (forwarded_to_user_type='SO' or (jsec_forwarded_to_so is not null and 
                    secretary_verified='Y' and joint_secretary_verified ='Y' and ps_verified = 'Y')) and status='A' and section_officer_verified is null")->row()->count;
                $pending_at_so_dist += $this->db->query("select count(*) as count from nc_village_proposal where dist_code='$dist_code' and (user_type = 'DLR' or user_type='ADLR') and (forwarded_to_user_type='SO' or (jsec_forwarded_to_so is not null and 
                    secretary_verified='Y' and joint_secretary_verified ='Y' and ps_verified = 'Y')) and status='A' and reverted ='Y' and section_officer_verified='Y'")->row()->count;

                $pending_at_aso_dist = $this->db->query("select count(*) as count from nc_village_proposal where dist_code='$dist_code' and (user_type = 'DLR' or user_type='ADLR') and (CASE WHEN forwarded_to_user_type IS NULL THEN secretary_verified='Y' and joint_secretary_verified ='Y' and ps_verified = 'Y' ELSE secretary_verified is null and joint_secretary_verified is null END) and so_forwarded_to_aso is not null and status='A' and section_officer_verified='Y' and asst_section_officer_verified is null")->row()->count;

                $pending_at_sec_dist = $this->db->query("select count(*) as count from nc_village_proposal where dist_code='$dist_code' and (user_type = 'DLR' or user_type='ADLR') and (CASE WHEN forwarded_to_user_type IS NULL THEN ps_forwarded_to_sec is not null and ps_verified = 'Y' END) and status='A' and reverted is null and secretary_verified is null")->row()->count;

                $pending_at_sec_dist += $this->db->query("select count(*) as count from nc_village_proposal where dist_code='$dist_code' and (user_type = 'DLR' or user_type='ADLR') and ps_forwarded_to_sec is not null and status='A' and ps_verified = 'Y' and reverted ='Y' and secretary_verified ='Y' and joint_secretary_verified is null")->row()->count;

                $pending_at_js_dist = $this->db->query("select count(*) as count from nc_village_proposal where dist_code='$dist_code' and (user_type = 'DLR' or user_type='ADLR') and (CASE WHEN forwarded_to_user_type IS NULL THEN sec_forwarded_to_jsec is not null and ps_verified = 'Y' and secretary_verified='Y' END) and status='A' and  reverted is null and joint_secretary_verified is null")->row()->count;

                $pending_at_js_dist += $this->db->query("select count(*) as count from nc_village_proposal where dist_code='$dist_code' and (user_type = 'DLR' or user_type='ADLR') and sec_forwarded_to_jsec is not null and status='A' and ps_verified = 'Y' and reverted ='Y' and secretary_verified ='Y' and joint_secretary_verified='Y'")->row()->count;


                // $pending_at_ps_dist = $this->db->query("select * from nc_village_proposal where dlr_verified = 'Y' and status != 'L' and dlr_proposal_id is not null ")->num_rows();
                // $pending_at_sec_dist = $this->db->query("select * from nc_villages where dlr_verified = 'Y' and status != 'L' and dlr_proposal_id is not null ")->num_rows();
                // $pending_at_js_dist = $this->db->query("select * from nc_villages where dlr_verified = 'Y' and status != 'L' and dlr_proposal_id is not null ")->num_rows();
                // // $pending_at_so_dist = $this->db->query("select * from nc_villages where dlr_verified = 'Y' and status != 'L' and dlr_proposal_id is not null ")->num_rows();
                // $pending_at_aso_dist = $this->db->query("select * from nc_villages where dlr_verified = 'Y' and status != 'L' and dlr_proposal_id is not null ")->num_rows();
            }else{
                // Will be handled, if requried
                // $pending_at_ps_dist = $this->db->query("select * from nc_villages where dlr_verified = 'Y' and status != 'L' and dlr_proposal_id is not null ")->num_rows();
                // $pending_at_sec_dist = $this->db->query("select * from nc_villages where dlr_verified = 'Y' and status != 'L' and dlr_proposal_id is not null ")->num_rows();
                // $pending_at_js_dist = $this->db->query("select * from nc_villages where dlr_verified = 'Y' and status != 'L' and dlr_proposal_id is not null ")->num_rows();
                // $pending_at_so_dist = $this->db->query("select * from nc_villages where dlr_verified = 'Y' and status != 'L' and dlr_proposal_id is not null ")->num_rows();
                // $pending_at_aso_dist = $this->db->query("select * from nc_villages where dlr_verified = 'Y' and status != 'L' and dlr_proposal_id is not null ")->num_rows();
            }

            $verified_lm_count += $verified_lm_count_dist;
            $pending_lm_count += $pending_lm_count_dist;
            $pending_sk_count += $pending_sk_count_dist;
            $certified_co_count += $certified_co_count_dist;
            $pending_co_count += $pending_co_count_dist;
            $digi_signature_dc_count += $digi_signature_dc_count_dist;
            $dc_forwarded += $dc_forwarded_dist;
            $pending_dc_count += $pending_dc_count_dist;
            $dlrs_pending += $dlrs_pending_dist;
            $dlrs_forwarded += $dlrs_forwarded_dist;
            $pending_at_ps += $pending_at_ps_dist;
            $pending_at_sec += $pending_at_sec_dist;
            $pending_at_js += $pending_at_js_dist;
            $pending_at_so += $pending_at_so_dist;
            $pending_at_aso += $pending_at_aso_dist;

            $data_dist_wise[$dist_code] = [
                'verified_lm_count' => $verified_lm_count_dist,
                'pending_sk_count' => $pending_sk_count_dist,
                'pending_lm_count' => $pending_lm_count_dist,
                'certified_co_count' => $certified_co_count_dist,
                'pending_co_count' => $pending_co_count_dist,
                'digi_signature_dc_count' => $digi_signature_dc_count_dist,
            ];
        }
        $data = [
            'verified_lm_count' => $verified_lm_count,
            'pending_lm_count' => $pending_lm_count,
            'pending_sk_count' => $pending_sk_count,
            'certified_co_count' => $certified_co_count,
            'pending_co_count' => $pending_co_count,
            'digi_signature_dc_count' => $digi_signature_dc_count,
            'data_dist_wise' => $data_dist_wise,
            'dc_forwarded' => $dc_forwarded,
            'pending_dc_count' => $pending_dc_count,
            'dlrs_pending' => $dlrs_pending + $dlr_revert_proposal,
            'dlr_forwarded' => $dlrs_forwarded,
            'pending_at_ps' => $pending_at_ps,
            'pending_at_sec' => $pending_at_sec,
            'pending_at_js' => $pending_at_js,
            'pending_at_so' => $pending_at_so,
            'pending_at_aso' => $pending_at_aso,
            'updated_at' => date('Y-m-d'),
            'updated_at_time' => date('H:i:s'),
        ];

        return ($data);
    }

    public function svamitvaDataEnteredData()
    {

        if ($this->input->get('api_key') == "chitha_application" or $_POST['api_key'] == "chitha_application") {

            if ($this->input->get('uuids')) {
                $uuids = $this->input->get('uuids');
            } else {
                $uuids = $_POST['uuids'];
            }

            $villages = [];
            foreach (json_decode(NC_DISTIRTCS) as $dist_code) {
                $this->dbswitch($dist_code);
                foreach ($uuids as $uuid) {
                    $village = $this->db->query("select * from location where uuid = '$uuid'")->row();
                    if ($village) {
                        $govt_patta_codes = GovtPattaCode;
                        $codes = [];
                        foreach ($govt_patta_codes as $code) {
                            $codes[] = "'" . $code . "'";
                        }
                        $govt_patta_codes = implode(',', $codes);
                        $q = "SELECT landclass_code.land_type as full_land_type_name,chitha_basic.* FROM chitha_basic
                join landclass_code on landclass_code.class_code = chitha_basic.land_class_code
                WHERE chitha_basic.dist_code='$dist_code' AND chitha_basic.subdiv_code='$village->subdiv_code' AND chitha_basic.cir_code='$village->cir_code'
                AND chitha_basic.mouza_Pargona_code='$village->mouza_pargona_code' AND chitha_basic.lot_No='$village->lot_no' AND chitha_basic.vill_townprt_code='$village->vill_townprt_code'
                AND chitha_basic.patta_type_code in ($govt_patta_codes)
                order by CAST(coalesce(dag_no_int, '0') AS numeric)";

                        $query = $this->db->query($q);

                        $govt_dags = $query->result();

                        if (count($govt_dags) > 0) {
                            $village->count = count($govt_dags);
                            $villages[$village->dist_code]["data"][] = $village;
                            $villages[$village->dist_code]["loc_name"] = $this->CommonModel->getLocations($village->dist_code, $village->subdiv_code, $village->cir_code, $village->mouza_pargona_code, $village->lot_no, $village->vill_townprt_code);
                            if (isset($villages[$village->dist_code]["total_count"])) {
                                $villages[$village->dist_code]["total_count"] = $villages[$village->dist_code]["total_count"] + $village->count;
                            } else {
                                $villages[$village->dist_code]["total_count"] = $village->count;
                            }
                        }
                    }
                }
            }

            $arr = array(
                'data' => $villages,
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
    }

    public function svamitvaDataEnteredCircle()
    {

        if ($this->input->get('api_key') == "chitha_application" or $_POST['api_key'] == "chitha_application") {

            if ($this->input->get('uuids')) {
                $uuids = $this->input->get('uuids');
            } else {
                $uuids = $_POST['uuids'];
            }
            $dist_code = $this->input->post('dist_code');
            // $vill_townprt_codes = implode(", ", $uuids);
            $villages = [];
            $this->dbswitch($dist_code);
            foreach ($uuids as $uuid) {
                $village = $this->db->query("select * from location where uuid = '$uuid'")->row();
                if ($village) {
                    $govt_patta_codes = GovtPattaCode;
                    $codes = [];
                    foreach ($govt_patta_codes as $code) {
                        $codes[] = "'" . $code . "'";
                    }
                    $govt_patta_codes = implode(',', $codes);
                    $q = "SELECT landclass_code.land_type as full_land_type_name,chitha_basic.* FROM chitha_basic
                    join landclass_code on landclass_code.class_code = chitha_basic.land_class_code
                    WHERE chitha_basic.dist_code='$dist_code' AND chitha_basic.subdiv_code='$village->subdiv_code' AND chitha_basic.cir_code='$village->cir_code'
                    AND chitha_basic.mouza_Pargona_code='$village->mouza_pargona_code' AND chitha_basic.lot_No='$village->lot_no' AND chitha_basic.vill_townprt_code='$village->vill_townprt_code'
                    AND chitha_basic.patta_type_code in ($govt_patta_codes)
                    order by CAST(coalesce(dag_no_int, '0') AS numeric)";

                    $query = $this->db->query($q);

                    $govt_dags = $query->result();

                    if (count($govt_dags) > 0) {
                        $village->count = count($govt_dags);
                        $villages[$village->cir_code]["loc_name"] = $this->CommonModel->getLocations($village->dist_code, $village->subdiv_code, $village->cir_code, $village->mouza_pargona_code, $village->lot_no, $village->vill_townprt_code);
                        $villages[$village->cir_code]["data"][] = $village;
                        if (isset($villages[$village->cir_code]["total_count"])) {
                            $villages[$village->cir_code]["total_count"] = $villages[$village->cir_code]["total_count"] + $village->count;
                        } else {
                            $villages[$village->cir_code]["total_count"] = $village->count;
                        }
                    }
                }
            }

            $arr = array(
                'data' => $villages,
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
    }

    public function svamitvaDataEnteredMouza()
    {

        if ($this->input->get('api_key') == "chitha_application" or $_POST['api_key'] == "chitha_application") {

            if ($this->input->get('uuids')) {
                $uuids = $this->input->get('uuids');
            } else {
                $uuids = $_POST['uuids'];
            }
            $dist_code = $this->input->post('dist_code');
            $villages = [];
            $this->dbswitch($dist_code);
            foreach ($uuids as $uuid) {
                $village = $this->db->query("select * from location where uuid = '$uuid'")->row();
                if ($village) {
                    $govt_patta_codes = GovtPattaCode;
                    $codes = [];
                    foreach ($govt_patta_codes as $code) {
                        $codes[] = "'" . $code . "'";
                    }
                    $govt_patta_codes = implode(',', $codes);
                    $q = "SELECT landclass_code.land_type as full_land_type_name,chitha_basic.* FROM chitha_basic
                    join landclass_code on landclass_code.class_code = chitha_basic.land_class_code
                    WHERE chitha_basic.dist_code='$dist_code' AND chitha_basic.subdiv_code='$village->subdiv_code' AND chitha_basic.cir_code='$village->cir_code'
                    AND chitha_basic.mouza_Pargona_code='$village->mouza_pargona_code' AND chitha_basic.lot_No='$village->lot_no' AND chitha_basic.vill_townprt_code='$village->vill_townprt_code'
                    AND chitha_basic.patta_type_code in ($govt_patta_codes)
                    order by CAST(coalesce(dag_no_int, '0') AS numeric)";

                    $query = $this->db->query($q);

                    $govt_dags = $query->result();

                    if (count($govt_dags) > 0) {
                        $village->count = count($govt_dags);
                        $villages[$village->mouza_pargona_code]["loc_name"] = $this->CommonModel->getLocations($village->dist_code, $village->subdiv_code, $village->cir_code, $village->mouza_pargona_code, $village->lot_no, $village->vill_townprt_code);
                        $villages[$village->mouza_pargona_code]["data"][] = $village;
                        if (isset($villages[$village->mouza_pargona_code]["total_count"])) {
                            $villages[$village->mouza_pargona_code]["total_count"] = $villages[$village->mouza_pargona_code]["total_count"] + $village->count;
                        } else {
                            $villages[$village->mouza_pargona_code]["total_count"] = $village->count;
                        }
                    }
                }
            }

            $arr = array(
                'data' => $villages,
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
    }

    public function svamitvaDataEnteredLot()
    {

        if ($this->input->get('api_key') == "chitha_application" or $_POST['api_key'] == "chitha_application") {

            if ($this->input->get('uuids')) {
                $uuids = $this->input->get('uuids');
            } else {
                $uuids = $_POST['uuids'];
            }
            $dist_code = $this->input->post('dist_code');
            $villages = [];
            $this->dbswitch($dist_code);
            foreach ($uuids as $uuid) {
                $village = $this->db->query("select * from location where uuid = '$uuid'")->row();
                if ($village) {
                    $govt_patta_codes = GovtPattaCode;
                    $codes = [];
                    foreach ($govt_patta_codes as $code) {
                        $codes[] = "'" . $code . "'";
                    }
                    $govt_patta_codes = implode(',', $codes);
                    $q = "SELECT landclass_code.land_type as full_land_type_name,chitha_basic.* FROM chitha_basic
                    join landclass_code on landclass_code.class_code = chitha_basic.land_class_code
                    WHERE chitha_basic.dist_code='$dist_code' AND chitha_basic.subdiv_code='$village->subdiv_code' AND chitha_basic.cir_code='$village->cir_code'
                    AND chitha_basic.mouza_Pargona_code='$village->mouza_pargona_code' AND chitha_basic.lot_No='$village->lot_no' AND chitha_basic.vill_townprt_code='$village->vill_townprt_code'
                    AND chitha_basic.patta_type_code in ($govt_patta_codes)
                    order by CAST(coalesce(dag_no_int, '0') AS numeric)";

                    $query = $this->db->query($q);

                    $govt_dags = $query->result();

                    if (count($govt_dags) > 0) {
                        $village->count = count($govt_dags);
                        $villages[$village->lot_no]["loc_name"] = $this->CommonModel->getLocations($village->dist_code, $village->subdiv_code, $village->cir_code, $village->mouza_pargona_code, $village->lot_no, $village->vill_townprt_code);
                        $villages[$village->lot_no]["data"][] = $village;
                        if (isset($villages[$village->lot_no]["total_count"])) {
                            $villages[$village->lot_no]["total_count"] = $villages[$village->lot_no]["total_count"] + $village->count;
                        } else {
                            $villages[$village->lot_no]["total_count"] = $village->count;
                        }
                    }
                }
            }

            $arr = array(
                'data' => $villages,
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
    }

    public function svamitvaDataEnteredLotVillages()
    {

        if ($this->input->get('api_key') == "chitha_application" or $_POST['api_key'] == "chitha_application") {

            if ($this->input->get('uuids')) {
                $uuids = $this->input->get('uuids');
            } else {
                $uuids = $_POST['uuids'];
            }
            $dist_code = $this->input->post('dist_code');
            $villages = [];
            $this->dbswitch($dist_code);
            foreach ($uuids as $uuid) {
                $village = $this->db->query("select * from location where uuid = '$uuid'")->row();
                if ($village) {
                    $govt_patta_codes = GovtPattaCode;
                    $codes = [];
                    foreach ($govt_patta_codes as $code) {
                        $codes[] = "'" . $code . "'";
                    }
                    $govt_patta_codes = implode(',', $codes);
                    $q = "SELECT landclass_code.land_type as full_land_type_name,chitha_basic.* FROM chitha_basic
                    join landclass_code on landclass_code.class_code = chitha_basic.land_class_code
                    WHERE chitha_basic.dist_code='$dist_code' AND chitha_basic.subdiv_code='$village->subdiv_code' AND chitha_basic.cir_code='$village->cir_code'
                    AND chitha_basic.mouza_Pargona_code='$village->mouza_pargona_code' AND chitha_basic.lot_No='$village->lot_no' AND chitha_basic.vill_townprt_code='$village->vill_townprt_code'
                    AND chitha_basic.patta_type_code in ($govt_patta_codes)
                    order by CAST(coalesce(dag_no_int, '0') AS numeric)";

                    $query = $this->db->query($q);

                    $govt_dags = $query->result();

                    if (count($govt_dags) > 0) {
                        $village->count = count($govt_dags);
                        $villages[$village->vill_townprt_code]["loc_name"] = $this->CommonModel->getLocations($village->dist_code, $village->subdiv_code, $village->cir_code, $village->mouza_pargona_code, $village->lot_no, $village->vill_townprt_code);
                        $villages[$village->vill_townprt_code]["data"][] = $village;
                        if (isset($villages[$village->vill_townprt_code]["total_count"])) {
                            $villages[$village->vill_townprt_code]["total_count"] = $villages[$village->vill_townprt_code]["total_count"] + $village->count;
                        } else {
                            $villages[$village->vill_townprt_code]["total_count"] = $village->count;
                        }
                    }
                }
            }

            $arr = array(
                'data' => $villages,
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
    }

    public function getNcVillagesByNCStatus()
    {
        $dist_code = $this->input->post('dist_code');

        $this->dbswitch($dist_code);

        $query = "select
        (select loc_name from location where subdiv_code=ncv.subdiv_code and cir_code=ncv.cir_code and mouza_pargona_code='00') as circle,
        (select loc_name from location where subdiv_code=ncv.subdiv_code and cir_code=ncv.cir_code and mouza_pargona_code=ncv.mouza_pargona_code and lot_no='00') as mouza,
        (select loc_name from location where subdiv_code=ncv.subdiv_code and cir_code=ncv.cir_code and mouza_pargona_code=ncv.mouza_pargona_code and lot_no=ncv.lot_no and vill_townprt_code='00000') as lot,
        (select loc_name from location where subdiv_code=ncv.subdiv_code and cir_code=ncv.cir_code and mouza_pargona_code=ncv.mouza_pargona_code and lot_no=ncv.lot_no and vill_townprt_code=ncv.vill_townprt_code) as village,
        * from nc_villages ncv";
        $query = $query . " where dist_code='$dist_code' and lm_verified='Y'";
        $data = $this->db->query($query)->result_array();

        if ($data) {
            $arr = array(
                'data' => $data,
                'status_code' => 200,
            );
            echo json_encode($arr);
        } else {
            $arr = array(
                'data' => [],
                'status_code' => 404,
            );
            echo json_encode($arr);
        }
    }

    /** Get village for all dept user dist wise */
    public function deptNcVillageDistricts()
    {
        $dists = (array) json_decode(NC_DISTIRTCS);
        $type = $this->input->post('type');
        $data = [];

        if ($type == 'dc_forwarded') {
            if ($dists) {
                foreach ($dists as $dist_code) {
                    $this->dbswitch($dist_code);
                    $data['districts'][(string)$dist_code]["loc_name"] = $this->CommonModel->getLocations((string)$dist_code);

                    $village = $this->db->query("select count(*) as count from nc_villages where
 					dist_code='$dist_code' and dc_verified = 'Y' and (status ='I' or dlr_verified = 'Y') and dc_proposal_id is not null")->row()->count;

                    $data['districts'][(string)$dist_code]["village"] = $village;
                }
            }
        } elseif ($type == 'dc_pending') {
            if ($dists) {
                foreach ($dists as $dist_code) {
                    $this->dbswitch($dist_code);
                    $data['districts'][(string)$dist_code]["loc_name"] = $this->CommonModel->getLocations((string)$dist_code);

                    $village = $this->db->query("select count(*) as count from nc_villages where
 				dist_code='$dist_code' and co_verified = 'Y' and (status='G' or status='K' or status ='B')")->row()->count;

                    $data['districts'][(string)$dist_code]["village"] = $village;
                }
            }
        } elseif ($type == 'dlrs_pending') {
            if ($dists) {
                foreach ($dists as $dist_code) {
                    $this->dbswitch($dist_code);
                    $data['districts'][(string)$dist_code]["loc_name"] = $this->CommonModel->getLocations((string)$dist_code);

                    $village = $this->db->query("select count(*) as count from nc_villages where
 				dist_code='$dist_code' and dc_verified = 'Y' and (status='I' or status='L')")->row()->count;

                    $data['districts'][(string)$dist_code]["village"] = $village;
                }
            }
        } elseif ($type == 'dlr_forwarded') {
            if ($dists) {
                foreach ($dists as $dist_code) {
                    $this->dbswitch($dist_code);
                    $data['districts'][(string)$dist_code]["loc_name"] = $this->CommonModel->getLocations((string)$dist_code);

                    $village = $this->db->query("select count(*) as count from nc_villages where
 					dist_code='$dist_code' and dlr_verified = 'Y' and status != 'L' and dlr_proposal_id is not null")->row()->count;

                    $data['districts'][(string)$dist_code]["village"] = $village;
                }
            }
        } elseif ($type == 'ps_pending') {
            if ($dists) {
                foreach ($dists as $dist_code) {
                    $this->dbswitch($dist_code);
                    $data['districts'][(string)$dist_code]["loc_name"] = $this->CommonModel->getLocations((string)$dist_code);

                    $proposal = $this->db->query("select count(*) as count from nc_village_proposal where
 					dist_code='$dist_code' and user_type = 'DLR' and ps_verified is null and status ='A'")->row()->count;

                    $data['districts'][(string)$dist_code]["proposal"] = $proposal;
                }
            }
        }
        if ($data) {
            $data['type'] = $type;
            $arr = array(
                'data' => $data,
                'status_code' => 200,
            );
            echo json_encode($arr);
        } else {
            $data['type'] = $type;
            $arr = array(
                'data' => [],
                'status_code' => 404,
            );
            echo json_encode($arr);
        }
    }

    /** get dept vill list */
    public function deptNcVillList()
    {
        $type = $this->input->post('type');
        $dist_code = $this->input->post('d');
        $data = [];
        if ($type == 'dc_forwarded') {
            if ($dist_code) {
                $this->dbswitch($dist_code);
                $data["loc_name"] = $this->CommonModel->getLocations((string)$dist_code);

                $village = $this->db->query("select * from nc_villages where
 					dist_code='$dist_code' and dc_verified = 'Y' and dc_proposal_id is not null")->result();
                foreach ($village as $v) {
                    $this->CommonModel->getLocations((string)$dist_code, $village['']);
                }
                $data["village"] = $village;
            }
        } elseif ($type == 'dlrs_pending') {
            if ($dist_code) {
                $this->dbswitch($dist_code);
                $data["loc_name"] = $this->CommonModel->getLocations((string)$dist_code);

                $proposal = $this->db->query("select * from nc_village_proposal where
 				dist_code='$dist_code' and user_type = 'DC' and dlr_verified is null and status ='A'")->result();
                $data["proposal"] = $proposal;
            }
        } elseif ($type == 'dlr_forwarded') {
            if ($dist_code) {
                $this->dbswitch($dist_code);
                $data["loc_name"] = $this->CommonModel->getLocations((string)$dist_code);

                $proposal = $this->db->query("select * from nc_village_proposal where
 				dist_code='$dist_code' and user_type = 'DLR' and status ='A'")->result();
                $data["proposal"] = $proposal;
            }
        } elseif ($type == 'ps_pending') {
            if ($dist_code) {
                $this->dbswitch($dist_code);
                $data["loc_name"] = $this->CommonModel->getLocations((string)$dist_code);

                $proposal = $this->db->query("select * from nc_village_proposal where
 				dist_code='$dist_code' and user_type = 'DLR' and ps_verified is null and status ='A'")->result();
                $data["proposal"] = $proposal;
            }
        }

        if ($data) {
            $data['type'] = $type;
            $arr = array(
                'data' => $data,
                'status_code' => 200,
            );
            echo json_encode($arr);
        } else {
            $data['type'] = $type;
            $arr = array(
                'data' => [],
                'status_code' => 404,
            );
            echo json_encode($arr);
        }
    }

    /** View dc proposal */
    public function viewDcProposal()
    {
        $dist_code = $this->input->post('d');
        $proposal_id = $this->input->post('proposal_id');

        $this->dbswitch($dist_code);
        $approve_proposal = $this->db->select('proposal_no')
            ->where(array(
                'dist_code' => $dist_code,
                'id' => $proposal_id,
                'user_type' => 'DC',
                'status' => 'A'
            ))
            ->get('nc_village_proposal')->row();

        $data['proposal_pdf_base64'] = null;
        $proposalpdfFilePath = FCPATH . NC_VILLAGE_PROPOSAL_DIR . "dc/" . $approve_proposal->proposal_no . '.pdf';
        if (file_exists($proposalpdfFilePath)) {
            $proposalpdfData = file_get_contents($proposalpdfFilePath);
            $data['proposal_pdf_base64'] = base64_encode($proposalpdfData);
        }

        echo json_encode($data);
    }

    /** View dlr proposal */
    public function viewDlrProposal()
    {
        $dist_code = $this->input->post('d');
        $proposal_id = $this->input->post('proposal_id');

        $this->dbswitch($dist_code);
        $approve_proposal = $this->db->select('proposal_no')
            ->where(array(
                'dist_code' => $dist_code,
                'id' => $proposal_id,
                'user_type' => 'DLR',
                'status' => 'A'
            ))
            ->get('nc_village_proposal')->row();

        echo json_encode($approve_proposal);
    }

    /** GET DLR PROPOSAL COUNT FOR ALL DEPT USER */
    public function getDeptPendingProposalCount()
    {
        $d_list = (array) $this->input->post('d');
        $type = $this->input->post('type');
        $data['pending_proposal'] = $data['pending_proposal_1st_proceeding'] = $data['pending_proposal_2nd_proceeding'] = 0;
        $data['forwarded_proposal'] = 0;
        $data['reverted_proposal'] = 0;
        $dlr_skipped_to_another_user_module_enable = $this->input->post('dlr_skipped_to_another_user_module_enable');
        if(!$dlr_skipped_to_another_user_module_enable) $dlr_skipped_to_another_user_module_enable = FALSE;
        
        $village_selection_option_on_proposal_send_enable = $this->input->post('village_selection_option_on_proposal_send_enable');
        if(!$village_selection_option_on_proposal_send_enable) $village_selection_option_on_proposal_send_enable = FALSE;

        foreach ($d_list as $k => $d) {
            $dist_code = $d['dist_code'];
            $this->dbswitch($dist_code);
            if ($type == 'ps_dashboard') {
                if(!$dlr_skipped_to_another_user_module_enable){
                    $data['pending_proposal'] += $this->db->query("select count(*) as count from nc_village_proposal where
                    dist_code='$dist_code' and (user_type = 'DLR' or user_type='ADLR') and status='A'  and reverted is null and ps_verified is null")->row()->count;

                    $data['forwarded_proposal'] += $this->db->query("select count(*) as count from nc_village_proposal where
                    dist_code='$dist_code' and (user_type = 'DLR' or user_type='ADLR') and status='A' and ps_verified = 'Y' and reverted is null")->row()->count;

                    $data['reverted_proposal'] += $this->db->query("select count(*) as count from nc_village_proposal where
                    dist_code='$dist_code' and (user_type = 'DLR' or user_type='ADLR') and status='A' and ps_verified = 'Y' 
                    and reverted ='Y' and secretary_verified is null")->row()->count;
                }else{
                    $data['pending_proposal'] += $this->db->query("select count(*) as count from nc_village_proposal where
                    dist_code='$dist_code' and (user_type = 'DLR' or user_type='ADLR') and status='A'  and reverted is null and (CASE WHEN forwarded_to is null THEN ps_verified is null ELSE ps_verified='NA' END)")->row()->count;

                    $data['forwarded_proposal'] += $this->db->query("select count(*) as count from nc_village_proposal where
                    dist_code='$dist_code' and (user_type = 'DLR' or user_type='ADLR') and status='A' and ps_verified = 'Y' and reverted is null")->row()->count;

                    $data['reverted_proposal'] += $this->db->query("select count(*) as count from nc_village_proposal where
                    dist_code='$dist_code' and (user_type = 'DLR' or user_type='ADLR') and status='A' and (CASE WHEN forwarded_to is null THEN ps_verified = 'Y' and secretary_verified is null ELSE ps_verified='NA' END) 
                    and reverted ='Y'")->row()->count;
                }
            } elseif ($type == 'sec_dashboard') {
                $user_name = $this->input->post('user_name');
                $data['pending_proposal'] += $this->db->query("select count(*) as count from nc_village_proposal where
 				dist_code='$dist_code' and (user_type = 'DLR' or user_type='ADLR') and ps_forwarded_to_sec='$user_name'
 				 and status='A' and ps_verified = 'Y' and reverted is null
 				 and secretary_verified is null")->row()->count;

                $data['forwarded_proposal'] += $this->db->query("select count(*) as count from nc_village_proposal where
 				dist_code='$dist_code' and (user_type = 'DLR' or user_type='ADLR') and ps_forwarded_to_sec='$user_name'
 				and status='A' and ps_verified = 'Y' and secretary_verified = 'Y'")->row()->count;

                $data['reverted_proposal'] += $this->db->query("select count(*) as count from nc_village_proposal where
 				dist_code='$dist_code' and (user_type = 'DLR' or user_type='ADLR') and ps_forwarded_to_sec='$user_name'
 				and status='A' and ps_verified = 'Y' and reverted ='Y' and
 				 secretary_verified ='Y' and joint_secretary_verified is null")->row()->count;
            } elseif ($type == 'joint_sec_dashboard') {
                $user_name = $this->input->post('user_name');
                $data['pending_proposal'] += $this->db->query("select count(*) as count from nc_village_proposal where
 				dist_code='$dist_code' and (user_type = 'DLR' or user_type='ADLR') and sec_forwarded_to_jsec='$user_name'
 				and status='A' and ps_verified = 'Y' and  reverted is null and
 				secretary_verified='Y' and joint_secretary_verified is null")->row()->count;

                $data['forwarded_proposal'] += $this->db->query("select count(*) as count from nc_village_proposal where
 				dist_code='$dist_code' and (user_type = 'DLR' or user_type='ADLR') and sec_forwarded_to_jsec='$user_name'
 				and status='A' and ps_verified = 'Y'  and reverted is null
 				and secretary_verified = 'Y' and joint_secretary_verified='Y'")->row()->count;

                $data['reverted_proposal'] += $this->db->query("select count(*) as count from nc_village_proposal where
 				dist_code='$dist_code' and (user_type = 'DLR' or user_type='ADLR') and sec_forwarded_to_jsec='$user_name'
 				and status='A' and ps_verified = 'Y' and reverted ='Y' and
 				 secretary_verified ='Y' and joint_secretary_verified='Y'")->row()->count;
            } elseif ($type == 'so_dashboard') {
                $user_name = $this->input->post('user_name');
                $user_code = $this->input->post('user_code');
                if(!$dlr_skipped_to_another_user_module_enable){
                    $data['pending_proposal'] += $this->db->query("select count(*) as count from nc_village_proposal where
                    dist_code='$dist_code' and (user_type = 'DLR' or user_type='ADLR') and ps_verified = 'Y' and status='A' 
                    and jsec_forwarded_to_so='$user_name' and  
                    secretary_verified='Y' and joint_secretary_verified ='Y' and section_officer_verified is null")->row()->count;

                    $data['forwarded_proposal'] += $this->db->query("select count(*) as count from nc_village_proposal where
                    dist_code='$dist_code' and (user_type = 'DLR' or user_type='ADLR') and ps_verified = 'Y' and status='A' and reverted is null
                    and secretary_verified = 'Y' and joint_secretary_verified='Y' and section_officer_verified='Y'")->row()->count;

                    $data['reverted_proposal'] += $this->db->query("select count(*) as count from nc_village_proposal where
                    dist_code='$dist_code' and (user_type = 'DLR' or user_type='ADLR') and status='A' and ps_verified = 'Y'
                    and jsec_forwarded_to_so='$user_name' and reverted ='Y' and
                    secretary_verified ='Y' and joint_secretary_verified='Y'  and section_officer_verified='Y'")->row()->count;
                }else{
                    if($village_selection_option_on_proposal_send_enable){
                        $data['pending_proposal_1st_proceeding'] += $this->db->query("select count(*) as count from nc_village_proposal as nvp join nc_villages as nv on nv.dlr_proposal_id = nvp.id where nvp.dist_code='$dist_code' and (nvp.user_type = 'DLR' or nvp.user_type='ADLR') and nvp.status='A' and (CASE WHEN nvp.forwarded_to is not null THEN nvp.forwarded_to='$user_name' ELSE nvp.jsec_forwarded_to_so='$user_name' and nvp.secretary_verified='Y' and nvp.joint_secretary_verified ='Y' END) and nvp.section_officer_verified is null and nv.status='A'")->row()->count;

                        $getPendingFirstProceeding = $this->db->query("select nv.*, nvp.* from nc_village_proposal as nvp join nc_villages as nv on nv.dlr_proposal_id = nvp.id where nvp.dist_code='$dist_code' and (nvp.user_type = 'DLR' or nvp.user_type='ADLR') and nvp.status='A' and (CASE WHEN nvp.forwarded_to is not null THEN nvp.forwarded_to='$user_name' ELSE nvp.jsec_forwarded_to_so='$user_name' and nvp.secretary_verified='Y' and nvp.joint_secretary_verified ='Y' END) and nvp.section_officer_verified is null and nv.status='A'")->result_array();

                        if(!empty($getPendingFirstProceeding)){
                            foreach($getPendingFirstProceeding as $proceeding){
                                $application_no = $proceeding['application_no'];

                                $nc_village_data = [
                                    'select_village_on_prop_sign_from_so' => 1,
                                    'updated_at' => date('Y-m-d H:i:s'),
                                    'section_officer_verified' => 'Y',
                                    'section_officer_verified_at' => date('Y-m-d H:i:s'),
                                    'section_officer_note' => 'Auto Update From 1st proceeding',
                                    'section_officer_user_code' => $user_code,
                                    'section_officer' => $user_name,
                                    'status' => 'a',
                                    'pre_user' => 'SO',
                                    'cu_user' => 'SO',
                                ];
                                $this->db->where('application_no', $application_no)->update('nc_villages', $nc_village_data);
                            }
                        }

                        $data['pending_proposal_2nd_proceeding'] += $this->db->query("select count(*) as count from nc_villages where dist_code='$dist_code' and section_officer='$user_name' and section_officer_verified='Y' and status='a'")->row()->count;
                        
                        $data['forwarded_proposal'] += $this->db->query("select count(*) as count from nc_village_proposal where
                        dist_code='$dist_code' and (user_type = 'DLR' or user_type='ADLR') and status='A' and reverted is null and (CASE WHEN forwarded_to is null THEN secretary_verified = 'Y' and joint_secretary_verified='Y' and ps_verified = 'Y' END) and section_officer_verified='Y'")->row()->count;

                        $data['forwarded_proposal'] += $this->db->query("select count(*) as count from nc_village_proposal where
                        dist_code='$dist_code' and user_type = 'SO' and status='a' and section_officer_verified='Y' and reverted is null")->row()->count;
                        
                        // $data['reverted_proposal'] += $this->db->query("SELECT COUNT(*) FROM nc_villages WHERE status='b' AND cu_user='SO' AND select_village_on_prop_sign_from_so=1 AND dist_code='".$dist_code."'")->row()->count;
                        
                        // $aso_proposal_reverts = $this->db->query("select * from nc_village_proposal where
                        // dist_code='$dist_code' and (user_type = 'DLR' or user_type='ADLR') and status='A' and reverted ='Y' and section_officer_verified='Y'")->result();
                        // if(!empty($aso_proposal_reverts)) {
                        //     foreach ($aso_proposal_reverts as $aso_proposal_revert) {
                        //         $data['reverted_proposal'] += $this->db->query("SELECT COUNT(*) FROM nc_villages WHERE dlr_proposal_id=? AND dist_code=? AND select_village_on_prop_sign_from_so=0 AND status='A'", [$aso_proposal_revert->id, $dist_code])->row()->count;
                        //     }
                        // }

                        $data['reverted_proposal'] = $this->db->query("select COUNT(*) as count from nc_village_proposal as nc join
                        location as l on nc.dist_code = l.dist_code join nc_villages ncv on ncv.dlr_proposal_id = nc.id OR ncv.section_officer_proposal_id = nc.id where nc.dist_code='$dist_code'  and l.subdiv_code ='00'
                        and (nc.user_type = 'DLR' or nc.user_type = 'ADLR' or nc.user_type = 'SO') 
                        and nc.reverted ='Y' and (CASE WHEN user_type='SO' 
                        THEN nc.status = 'a' ELSE  nc.jsec_forwarded_to_so='section_officer_assam'
                        and nc.joint_secretary_verified='Y' and nc.section_officer_verified='Y' and nc.secretary_verified ='Y'
                        and nc.ps_verified ='Y' and nc.status='A' END)")->row()->count;

                        log_message('error', '##' . $this->db->last_query());

                    }else{
                        $data['pending_proposal'] += $this->db->query("select count(*) as count from nc_village_proposal where
                        dist_code='$dist_code' and (user_type = 'DLR' or user_type='ADLR') and status='A' 
                        and (CASE WHEN forwarded_to is not null THEN forwarded_to='$user_name' ELSE jsec_forwarded_to_so='$user_name' and  
                        secretary_verified='Y' and joint_secretary_verified ='Y' END) and section_officer_verified is null")->row()->count;

                        $data['forwarded_proposal'] += $this->db->query("select count(*) as count from nc_village_proposal where
                        dist_code='$dist_code' and (user_type = 'DLR' or user_type='ADLR') and status='A' and reverted is null and (CASE WHEN forwarded_to is null THEN secretary_verified = 'Y' and joint_secretary_verified='Y' and ps_verified = 'Y' END) and section_officer_verified='Y'")->row()->count;

                        $data['reverted_proposal'] += $this->db->query("select count(*) as count from nc_village_proposal where
                        dist_code='$dist_code' and (user_type = 'DLR' or user_type='ADLR') and status='A' and ps_verified = 'Y'
                        and jsec_forwarded_to_so='$user_name' and reverted ='Y' and
                        secretary_verified ='Y' and joint_secretary_verified='Y'  and section_officer_verified='Y'")->row()->count;
                    }
                }
            } elseif ($type == 'aso_dashboard') {
                $user_name = $this->input->post('user_name');
                if($dlr_skipped_to_another_user_module_enable){
                    if($village_selection_option_on_proposal_send_enable){
                        // $data['pending_proposal_1st_proceeding'] += $this->db->query("select count(*) as count from nc_village_proposal where dist_code='$dist_code' and so_forwarded_to_aso='$user_name' and section_officer_verified='Y' and asst_section_officer_verified is null and ((user_type = 'DLR' or user_type='ADLR') and status='A') OR (user_type='SO' and status='a')")->row()->count;

                        // $query1 = "select * from nc_village_proposal where dist_code='$dist_code' and so_forwarded_to_aso='$user_name' and section_officer_verified='Y' and asst_section_officer_verified is null and ((user_type = 'DLR' or user_type='ADLR') and status='A') OR (user_type='SO' and status='a')";
                        // $nc_village_proposals = $this->db->query($query1)->result_array();
                        // $first_proceeding_count = 0;
                        // if(count($nc_village_proposals)){
                        //     foreach($nc_village_proposals as $nc_village_proposal){
                        //         if($nc_village_proposal['user_type'] == 'SO'){
                        //             $nc_vills = $this->db->where('section_officer_proposal_id', $nc_village_proposal['id'])->where('section_officer is NOT NULL', NULL, false)->where('status', 'b')->where('asst_section_officer is NULL')->get('nc_villages')->result_array();
                        //             $first_proceeding_count += count($nc_vills);
                        //         }elseif(in_array($nc_village_proposal['user_type'], ['ADLR', 'DLR'])){
                        //             $nc_vills = $this->db->where('dlr_proposal_id', $nc_village_proposal['id'])->where('asst_section_officer is NULL')->get('nc_villages')->result_array();
                                    
                        //             $first_proceeding_count += count($nc_vills);
                        //         }
                        //     }
                        // }

                        $first_proceeding_count = $this->db->query("select count(*) as count from nc_village_proposal as nvp join
                        location as l on nvp.dist_code = l.dist_code join nc_villages nc on nc.section_officer_proposal_id = nvp.id where nvp.dist_code='$dist_code' and nvp.user_type = 'SO' and nvp.status ='a'
                        and nvp.reverted is null and nvp.so_forwarded_to_aso is not null and nvp.section_officer_verified ='Y' and nvp.asst_section_officer_verified is null and l.subdiv_code ='00'")->row()->count;

                        $data['pending_proposal_1st_proceeding'] += $first_proceeding_count;
                        
                        $data['pending_proposal_2nd_proceeding'] += $this->db->query("select count(*) as count from nc_villages where dist_code='$dist_code' and asst_section_officer='$user_name' and asst_section_officer_verified='Y' and status='c'")->row()->count;
                        
                        $data['pending_proposal'] += $this->db->query("select count(*) as count from nc_village_proposal nvp join nc_villages nc on nc.asst_section_officer_proposal_id = nvp.id where
                        nvp.dist_code='$dist_code' and nvp.user_type='ASO' and nvp.status='b' and nvp.asst_section_officer_verified is null and nc.asst_section_officer='$user_name'")->row()->count;
                    }else{
                        $data['pending_proposal'] += $this->db->query("select count(*) as count from nc_village_proposal where
                        dist_code='$dist_code' and (user_type = 'DLR' or user_type='ADLR') and status='A' 
                        and so_forwarded_to_aso='$user_name' and section_officer_verified='Y' 
                        and asst_section_officer_verified is null")->row()->count;
                    }


                }else{
                    $data['pending_proposal'] += $this->db->query("select count(*) as count from nc_village_proposal where
                    dist_code='$dist_code' and (user_type = 'DLR' or user_type='ADLR') and ps_verified = 'Y' and status='A' and 
                    secretary_verified='Y' and so_forwarded_to_aso='$user_name' and joint_secretary_verified ='Y' and section_officer_verified='Y' 
                    and asst_section_officer_verified is null")->row()->count;
                }
            }
        }
        
        echo json_encode($data);
        return;
    }

    public function apiProposalWiseVillages()
    {
        $d_list = (array) $this->input->post('d');
        $user_type = $this->input->post('user_type');
        $user_name = $this->input->post('user_name');
        
        $village = array();
        foreach ($d_list as $k => $dl) {
            $dist_code = $dl['dist_code'];
            $this->dbswitch($dist_code);

            if($user_type == 'section_officer'){
                $query = "select nv.*, ll.loc_name, l.loc_name as dist_name from nc_village_proposal as nc join location as l 
                on nc.dist_code = l.dist_code and l.subdiv_code ='00' join nc_villages as nv on nv.dlr_proposal_id=nc.id join location as ll 
                on nv.dist_code = ll.dist_code and nv.subdiv_code = ll.subdiv_code  and nv.cir_code = ll.cir_code 
                and nv.mouza_pargona_code = ll.mouza_pargona_code  and nv.lot_no = ll.lot_no and ll.vill_townprt_code = nv.vill_townprt_code 
                where nc.dist_code='$dist_code'  and (nc.user_type = 'DLR' or nc.user_type = 'ADLR') 
                and nc.status ='A'  and  nc.reverted is null 
                and nc.section_officer_verified is null  and nv.status='A' and 
                (CASE WHEN nc.forwarded_to is not null THEN nc.forwarded_to='$user_name' 
                ELSE nc.jsec_forwarded_to_so='$user_name' and nc.secretary_verified='Y' 
                and nc.joint_secretary_verified ='Y' END)";
                // $query = "select nv.*, ll.loc_name, l.loc_name as dist_name from nc_village_proposal as nc join location as l on nc.dist_code = l.dist_code  join nc_villages as nv on nv.dlr_proposal_id=nc.id join location as ll  on nv.dist_code = ll.dist_code and nv.subdiv_code = ll.subdiv_code  and nv.cir_code = ll.cir_code and nv.mouza_pargona_code = ll.mouza_pargona_code  and nv.lot_no = ll.lot_no and ll.vill_townprt_code = nv.vill_townprt_code where nc.dist_code='$dist_code'  and (nc.user_type = 'DLR' or nc.user_type = 'ADLR') and nc.status ='A'  and  nc.reverted is null and nc.forwarded_to is not null  and nc.section_officer_verified is null and l.subdiv_code ='00' and nv.status='A' and (CASE WHEN nc.forwarded_to is not null THEN nc.forwarded_to='$user_name' ELSE nc.jsec_forwarded_to_so='$user_name' and nc.secretary_verified='Y' and nc.joint_secretary_verified ='Y' END)";

                // $nc_villages = $this->db->query($query)->result();
                // foreach ($nc_villages as $nc) {
                //     $merge_village_requests = $this->db->where('nc_village_id', $nc->id)->get('merge_village_requests')->result_array();
                //     $merge_village_name_arr = [];
                //     if(count($merge_village_requests)){
                //         foreach($merge_village_requests as $key1 => $merge_village_request){
                //             $vill_loc = $this->CommonModel->getLocations($merge_village_request['request_dist_code'], $merge_village_request['request_subdiv_code'], $merge_village_request['request_cir_code'], $merge_village_request['request_mouza_pargona_code'], $merge_village_request['request_lot_no'], $merge_village_request['request_vill_townprt_code']);
                //             array_push($merge_village_name_arr, $vill_loc['village']['loc_name']);
                //             $merge_village_requests[$key1]['village_name'] = $vill_loc['village']['loc_name'];
                //             $merge_village_requests[$key1]['vill_loc'] = $vill_loc;
                //         }
                //     }
                //     $nc->merge_village_names = implode(', ', $merge_village_name_arr);
                //     $nc->merge_village_requests = $merge_village_requests;

                //     $village[] = $nc;
                // }
            }elseif($user_type == 'asst_section_officer'){
                $query1 = "select * from nc_village_proposal where dist_code='$dist_code' and so_forwarded_to_aso='$user_name' and section_officer_verified='Y' and asst_section_officer_verified is null and ((user_type = 'DLR' or user_type='ADLR') and status='A') OR (user_type='SO' and status='a')";
                $nc_village_proposals = $this->db->query($query1)->result_array();
                $nc_vill_ids = [];
                if(count($nc_village_proposals)){
                    foreach($nc_village_proposals as $nc_village_proposal){
                        if($nc_village_proposal['user_type'] == 'SO'){
                            $nc_vills = $this->db->where('section_officer_proposal_id', $nc_village_proposal['id'])->where('section_officer is NOT NULL', NULL, false)->where('status', 'b')->get('nc_villages')->result_array();
                            if(count($nc_vills)){
                                foreach($nc_vills as $nc_vill){
                                    array_push($nc_vill_ids, $nc_vill['id']);
                                }
                            }
                        }elseif(in_array($nc_village_proposal['user_type'], ['ADLR', 'DLR'])){
                            $nc_vills = $this->db->where('dlr_proposal_id', $nc_village_proposal['id'])->get('nc_villages')->result_array();
                            
                            if(count($nc_vills)){
                                foreach($nc_vills as $nc_vill){
                                    array_push($nc_vill_ids, $nc_vill['id']);
                                }
                            }
                        }
                    }
                }
                
                if(count($nc_vill_ids)){
                    $nc_vill_ids_string_arr = array_map(function($nc_vill_id){
                                                        return "'" . $nc_vill_id . "'";
                                                    },$nc_vill_ids);
                    $nc_vill_ids_string = implode(',', $nc_vill_ids_string_arr);
                    
                    $query = "select nv.*, ll.loc_name, l.loc_name as dist_name from nc_villages as nv join location as l on nv.dist_code = l.dist_code and l.subdiv_code='00' join location as ll  on nv.dist_code = ll.dist_code and nv.subdiv_code = ll.subdiv_code  and nv.cir_code = ll.cir_code and nv.mouza_pargona_code = ll.mouza_pargona_code  and nv.lot_no = ll.lot_no and ll.vill_townprt_code = nv.vill_townprt_code where nv.id in ($nc_vill_ids_string) and nv.asst_section_officer is NULL";

                }
            }

            if(!isset($query)){
                echo json_encode([]);
                return;
            }
            $nc_villages = $this->db->query($query)->result();
            foreach ($nc_villages as $nc) {
                $merge_village_requests = $this->db->where('nc_village_id', $nc->id)->get('merge_village_requests')->result_array();
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

    /** getDistWise2ndProceedingVillageCount */
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
        //		$data['proposal'] = $this->load->view('nc_village/dlr/approval_notification', $data, true);

        echo json_encode($data);
        return;
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

    public function updateProposal()
    {
        $proposal_no = $this->input->post('proposal_no');
        $sign_key = $this->input->post('sign_key');
        $dist_code = $this->input->post('dist_code');
        $user_code = $this->input->post('user_code');
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
                
                    if ($this->db->affected_rows() != 1) {
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

    /** GET DLR PROPOSAL FOR ALL DEPT USER */
    public function getDeptVillages() {
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
            
            if ($user == 'section_officer' && $type == 'reverted') {
                $user_name = $this->input->post('user_name');

                $aso_reverted_villages = $this->db->query("SELECT nc.*, l.loc_name FROM nc_villages nc, location l WHERE nc.status='f' AND nc.cu_user='SO' AND nc.dist_code=l.dist_code AND l.subdiv_code='00' AND nc.dist_code=? AND nc.select_village_on_prop_sign_from_so=1", [$dist_code])->result();

                $aso_reverted_proposals = $this->db->query("select * from nc_village_proposal where
                dist_code='$dist_code' and (user_type = 'DLR' or user_type='ADLR') and status='A' and reverted ='Y' and section_officer_verified='Y'")->result();

                if(!empty($aso_reverted_villages)) {
                    foreach ($aso_reverted_villages as $aso_reverted_village) {
                        $aso_reverted_village->aso_status = 'Reverted village from ASO';
                        $aso_reverted_village->village_name = $this->db->query("SELECT loc_name FROM location WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=?", [$aso_reverted_village->dist_code, $aso_reverted_village->subdiv_code, $aso_reverted_village->cir_code, $aso_reverted_village->mouza_pargona_code, $aso_reverted_village->lot_no, $aso_reverted_village->vill_townprt_code])->row()->loc_name;
                        $data[] = $aso_reverted_village;
                    }
                }

                if(!empty($aso_reverted_proposals)) {
                    foreach ($aso_reverted_proposals as $aso_reverted_proposal) {
                        $proposal_villages = $this->db->query("SELECT nc.*, l.loc_name FROM nc_villages nc, location l WHERE nc.dlr_proposal_id=? AND nc.dist_code=l.dist_code AND l.subdiv_code='00' AND nc.dist_code=? AND nc.select_village_on_prop_sign_from_so=0 AND nc.status='A'", [$aso_reverted_proposal->id, $dist_code])->result();

                        foreach ($proposal_villages as $proposal_village) {
                            $proposal_village->aso_status = 'Reverted Proposal from ASO';
                            $proposal_village->village_name = $this->db->query("SELECT loc_name FROM location WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=?", [$proposal_village->dist_code, $proposal_village->subdiv_code, $proposal_village->cir_code, $proposal_village->mouza_pargona_code, $proposal_village->lot_no, $proposal_village->vill_townprt_code])->row()->loc_name;
                            $data[] = $proposal_village;
                        }
                    }
                }

                // $pending_proposal = $this->db->query("select nc.*, l.loc_name from nc_village_proposal as nc join
 				// location as l on nc.dist_code = l.dist_code where nc.dist_code='$dist_code' and (nc.user_type = 'DLR' or nc.user_type = 'ADLR') 
 				// and nc.status ='A' and nc.reverted ='Y' and nc.reverted ='Y' and nc.jsec_forwarded_to_so='$user_name'
 				// and nc.joint_secretary_verified='Y' and nc.section_officer_verified='Y' and nc.secretary_verified ='Y'
 				// and nc.ps_verified ='Y' and l.subdiv_code ='00'")->result();
                // foreach ($pending_proposal as $p) {
                //     $data[] = $p;
                // }
            }
        }

        echo json_encode($data);
        return;
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
                    // $merge_village_name_arr = [];
                    // $nc_village = $this->db->where('dlr_proposal_id', $p->id)->get('nc_villages')->row();
                    // $merge_village_requests = $this->db->where('nc_village_id', $nc_village->id)->get('merge_village_requests')->result_array();
                    // if(count($merge_village_requests)){
                    //     foreach($merge_village_requests as $key1 => $merge_village_request){
                    //         $vill_loc = $this->CommonModel->getLocations($merge_village_request['request_dist_code'], $merge_village_request['request_subdiv_code'], $merge_village_request['request_cir_code'], $merge_village_request['request_mouza_pargona_code'], $merge_village_request['request_lot_no'], $merge_village_request['request_vill_townprt_code']);
                    //         array_push($merge_village_name_arr, $vill_loc['village']['loc_name']);
                    //         $merge_village_requests[$key1]['village_name'] = $vill_loc['village']['loc_name'];
                    //         $merge_village_requests[$key1]['vill_loc'] = $vill_loc;
                    //     }
                    // }
                    // $p->merge_village_names = implode(', ', $merge_village_name_arr);
                    // $p->merge_village_requests = $merge_village_requests;

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


                // $pending_proposal = $this->db->query("select nc.*, l.loc_name from nc_village_proposal as nc join
 				// location as l on nc.dist_code = l.dist_code where nc.dist_code='$dist_code' and (nc.user_type = 'DLR' or nc.user_type = 'ADLR' or nc.user_type = 'SO') 
 				// and nc.reverted ='Y' and (nc.status = 'A' OR nc.status = 'a') and (CASE WHEN forwarded_to_user_type IS NULL THEN nc.jsec_forwarded_to_so='$user_name'
 				// and nc.joint_secretary_verified='Y' and nc.section_officer_verified='Y' and nc.secretary_verified ='Y'
 				// and nc.ps_verified ='Y' ELSE nc.forwarded_to='$user_name' END) and l.subdiv_code ='00'")->result();

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
                        // $pending_proposal = $this->db->query("select nvp.*, l.loc_name from nc_village_proposal as nvp join
                        // location as l on nvp.dist_code = l.dist_code join nc_villages nc on nc.asst_section_officer_proposal_id = nvp.id where
                        // nvp.dist_code='$dist_code' and nvp.user_type = 'ASO' and nvp.status ='b'
                        // and nvp.reverted is null and nc.asst_section_officer='$user_name'
                        // and nvp.asst_section_officer_verified is null and l.subdiv_code ='00'")->result();

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
            // $query = "select nc.*,l.loc_name,ch.old_vill_name,ch.new_vill_name from nc_villages nc join change_vill_name ch on nc.dist_code = ch.dist_code and nc.uuid = ch.uuid join location l on nc.dist_code = l.dist_code and nc.subdiv_code = l.subdiv_code and nc.cir_code = l.cir_code
            //         and nc.mouza_pargona_code = l.mouza_pargona_code and nc.lot_no = l.lot_no and nc.vill_townprt_code = l.vill_townprt_code where
            //             nc.dist_code='$dist_code'and nc.status ='d' and nc.asst_section_officer_proposal_id='$proposal_id' and nc.asst_section_officer_proposal='Y'";

            log_message("error", "select nc.*,l.loc_name,ch.old_vill_name,ch.new_vill_name from nc_villages nc join change_vill_name ch on nc.dist_code = ch.dist_code and nc.uuid = ch.uuid join location l on nc.dist_code = l.dist_code and nc.subdiv_code = l.subdiv_code and nc.cir_code = l.cir_code
                    and nc.mouza_pargona_code = l.mouza_pargona_code and nc.lot_no = l.lot_no and nc.vill_townprt_code = l.vill_townprt_code where
                        nc.dist_code='$dist_code'and nc.status ='b' and nc.section_officer_proposal_id='$proposal_id' and nc.section_officer_proposal='Y'");

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

            //			$nc_village[$k]->total_dag_area = $vill_area = $this->db->select('count(*) as total_dag, SUM(dag_area_b) as total_bigha,
            //				SUM(dag_area_k) as total_katha,SUM(dag_area_lc) as total_lessa')
            //				->where(array(
            //					'dist_code' => $v->dist_code, 'subdiv_code' => $v->subdiv_code, 'cir_code' => $v->cir_code, '
            //				mouza_pargona_code' => $v->mouza_pargona_code, 'lot_no' => $v->lot_no,
            //					'vill_townprt_code' => $v->vill_townprt_code,
            //				))
            //				->get('chitha_basic_nc')->row();

            //			$total_lessa = $this->totalLessa($vill_area->total_bigha, $vill_area->total_katha, $vill_area->total_lessa);
            //			$nc_village[$k]->total_b_k_l = $this->Total_Bigha_Katha_Lessa($total_lessa);

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

        $data['proposal'] = $this->db->select('*')
            ->where(array('dist_code' => $dist_code, 'id' => $proposal_id, 'proposal_no' => $proposal_no))
            ->get('nc_village_proposal')->row();

        $data['nc_village'] = $nc_village;
        $data['ecf_no'] = $proposal_id . '/' . date('Y');
        $data = $this->load->view('nc_village/asst_section_officer/notification', $data);

        echo json_encode($data);
        return;
    }


    /** DLR REVERT BACK PROPOSAL */
    public function deptRevertBackProposal()
    {

        $dist_code = $this->input->post('dist_code');
        $note = $this->input->post('note');
        $proposal_id = $this->input->post('proposal_id');
        $user_code = $this->input->post('user_code');
        $user = $this->input->post('user');

        if ($user == 'DLR') {
            $from = 'DLR';
            $task = 'Village Reverted by DLR';
        } elseif ($user == 'ADLR') {
            $from = 'ADLR';
            $task = 'Village Reverted by ADLR';
        }
        $this->dbswitch($dist_code);
        $this->db->trans_begin();
        if ($user == 'DLR') {
            $this->db->set([
                'dlr_note' => $note,
                'status' => "R",
                'dlr_verified_at' => date('Y-m-d H:i:s'),
                "user_code" => $user_code,
            ]);
        } elseif ($user == 'ADLR') {
            $this->db->set([
                'adlr_note' => $note,
                'status' => "R",
                'adlr_verified_at' => date('Y-m-d H:i:s'),
                "user_code" => $user_code,
            ]);
        }

        $this->db->where('id', $proposal_id);
        $this->db->where('dist_code', $dist_code);
        $this->db->update('nc_village_proposal');
        if ($this->db->affected_rows() > 0) {
            $nc_villages = $this->db->query("select * from nc_villages where
 				dist_code = ? and 
 				dlr_proposal_id = ?", array($dist_code, $proposal_id))->result();

            foreach ($nc_villages as $nc) {
                $proceeding_id_max = $this->db->query("select max(proceeding_id) as id from settlement_proceeding where case_no = '$nc->application_no'")->row()->id;
                $insPetProceed = array(
                    'case_no' => $nc->application_no,
                    'proceeding_id' => !$proceeding_id_max ? 1 : (int) $proceeding_id_max + 1,
                    'date_of_hearing' => date('Y-m-d h:i:s'),
                    'next_date_of_hearing' => date('Y-m-d h:i:s'),
                    'note_on_order' => $note,
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

                if ($user == 'DLR') {
                    $this->db->where('application_no', $nc->application_no)
                        ->update(
                            'nc_villages',
                            [
                                'updated_at' => date('Y-m-d H:i:s'),
                                'dc_proposal' => null,
                                'dc_verified' => null,
                                'dlr_proposal' => null,
                                'dlr_verified' => null,
                                'adlr_verified' => null,
                                'dlr_note' => $note,
                                'dlr_user_code' => $user_code,
                                'status' => 'B',
                                'pre_user' => $from,
                                'cu_user' => 'DC',
                            ]
                        );
                } elseif ($user == 'ADLR') {
                    $this->db->where('application_no', $nc->application_no)
                        ->update(
                            'nc_villages',
                            [
                                'updated_at' => date('Y-m-d H:i:s'),
                                'dc_proposal' => null,
                                'dc_verified' => null,
                                'dlr_proposal' => null,
                                'dlr_verified' => null,
                                'adlr_verified' => null,
                                'adlr_note' => $note,
                                'adlr_user_code' => $user_code,
                                'status' => 'B',
                                'pre_user' => $from,
                                'cu_user' => 'DC',
                            ]
                        );
                }
                if ($this->db->affected_rows() != 1) {
                    $this->db->trans_rollback();
                    echo json_encode('N');
                    break;
                    return;
                }
            }

            $this->db->trans_commit();
            echo json_encode('Y');
            return;
        } else {
            $this->db->trans_rollback();
            echo json_encode('N');
            return;
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

    /** GET PROPOSAL Count */
    public function getProposalCount()
    {
        $user = $this->UtilsModel->cleanPattern($this->input->post('user'));

        if ($user == 'adlr') {
            $pending_proposal = 0;
            $forwarded_proposal = 0;
            $revert_back_to_dlr = 0;
            $revert_back_from_jds = 0;

            foreach (json_decode(NC_DISTIRTCS) as $dist_code) {
                $this->dbswitch($dist_code);
                $pending_proposal_dist = $this->db->query("select * from nc_village_proposal where status = 'B' and adlr_verified is null ")->num_rows();
                $forwarded_proposal_dist = $this->db->query("select * from nc_village_proposal where status = 'C' and adlr_verified ='Y'  ")->num_rows();
                $revert_back_to_dlr_dist = $this->db->query("select * from nc_village_proposal where status = 'E' and adlr_verified ='Y'")->num_rows();
                $revert_back_from_jds_dist = $this->db->query("select * from nc_village_proposal where status = 'D' and adlr_verified ='Y'")->num_rows();

                $pending_proposal += $pending_proposal_dist;
                $forwarded_proposal += $forwarded_proposal_dist;
                $revert_back_to_dlr += $revert_back_to_dlr_dist;
                $revert_back_from_jds += $revert_back_from_jds_dist;
            }
            $data = [
                'pending_proposal' => $pending_proposal,
                'forwarded_proposal' => $forwarded_proposal,
                'revert_back_to_dlr' => $revert_back_to_dlr,
                'revert_back_from_jds' => $revert_back_from_jds,
            ];
        } elseif ($user == 'jds') {
            $pending_proposal = 0;
            $revert_back_to_adlr = 0;

            foreach (json_decode(NC_DISTIRTCS) as $dist_code) {
                $this->dbswitch($dist_code);
                $pending_proposal_dist = $this->db->query("select * from nc_village_proposal where status = 'C' and jds_verified is NULL and adlr_verified ='Y' ")->num_rows();

                $revert_back_to_adlr_dist = $this->db->query("select * from nc_village_proposal where status = 'D' and adlr_verified ='Y' and jds_verified ='Y'")->num_rows();

                $pending_proposal += $pending_proposal_dist;
                $revert_back_to_adlr += $revert_back_to_adlr_dist;
            }
            $data = [
                'pending_proposal' => $pending_proposal,
                'revert_back_to_adlr' => $revert_back_to_adlr,
            ];
        }
        echo json_encode($data);
        return;
    }

    /** get Proposal from adlr and jds */
    public function getProposal()
    {
        $user = $this->UtilsModel->cleanPattern($this->input->post('user'));
        $type = $this->UtilsModel->cleanPattern($this->input->post('type'));

        $data = array();
        if ($user == 'adlr' && $type == 'forwardedbydlr') {
            foreach (json_decode(NC_DISTIRTCS) as $dist_code) {
                $this->dbswitch($dist_code);
                $pending_proposals = $this->db->query("select id,dist_code,created_at,proposal_no,proposal_note,
 				status from nc_village_proposal where status = 'B' and adlr_verified is null order by created_at desc ")->result();

                foreach ($pending_proposals as $p) {
                    $data[] = $p;
                }
            }
            echo json_encode($data);
            return;
        } elseif ($user == 'dlr' && $type == 'revertedfromadlrps') {
            $data['adlr_reverted'] = array();
            $data['ps_reverted'] = array();
            foreach (json_decode(NC_DISTIRTCS) as $dist_code) {
                $this->dbswitch($dist_code);
                $pending_proposals = $this->db->query("select id,dist_code,created_at,proposal_no,proposal_note,adlr_note,
 				status from nc_village_proposal where status = 'E' and adlr_verified ='Y' order by updated_at desc ")->result();

                foreach ($pending_proposals as $p) {
                    $data['adlr_reverted'][] = $p;
                }

                $reverted_proposals_ps = $this->db->query("select id,dist_code,created_at,proposal_no,proposal_note,adlr_note,ps_note,
 				status from nc_village_proposal where status = 'A' and ps_verified is null 
 				and reverted = 'Y' order by updated_at desc ")->result();
                foreach ($reverted_proposals_ps as $d) {
                    $data['ps_reverted'][] = $d;
                }
            }
            echo json_encode($data);
            return;
        } elseif ($user == 'adlr' && $type == 'revertbackjds') {
            foreach (json_decode(NC_DISTIRTCS) as $dist_code) {
                $this->dbswitch($dist_code);
                $pending_proposals = $this->db->query("select id,dist_code,created_at,proposal_no,proposal_note,adlr_note,jds_note,
 				status from nc_village_proposal where status = 'D' and adlr_verified ='Y' order by jds_verified_at desc ")->result();

                foreach ($pending_proposals as $p) {
                    $data[] = $p;
                }
            }
            echo json_encode($data);
            return;
        } elseif ($user == 'adlr' && $type == 'revertbacktodlr') {
            foreach (json_decode(NC_DISTIRTCS) as $dist_code) {
                $this->dbswitch($dist_code);
                $pending_proposals = $this->db->query("select id,dist_code,created_at,proposal_no,proposal_note,adlr_note,jds_note,
 				status from nc_village_proposal where status != 'C' and status != 'D' and adlr_verified ='Y' order by adlr_verified_at desc ")->result();

                foreach ($pending_proposals as $p) {
                    $data[] = $p;
                }
            }
            echo json_encode($data);
            return;
        } elseif ($user == 'adlr' && $type == 'forwardedtojds') {
            foreach (json_decode(NC_DISTIRTCS) as $dist_code) {
                $this->dbswitch($dist_code);
                $pending_proposals = $this->db->query("select id,dist_code,created_at,proposal_no,proposal_note,adlr_note,jds_note,
 				status from nc_village_proposal where status = 'C' and adlr_verified ='Y' order by adlr_verified_at desc ")->result();

                foreach ($pending_proposals as $p) {
                    $data[] = $p;
                }
            }
            echo json_encode($data);
            return;
        } elseif ($user == 'jds' && $type == 'forwardedbyadlr') {
            $data['pending_proposal'] = array();
            $data['revert_proposal'] = array();
            foreach (json_decode(NC_DISTIRTCS) as $dist_code) {
                $this->dbswitch($dist_code);
                $pending_proposals = $this->db->query("select id,dist_code,created_at,proposal_no,proposal_note,adlr_note,
 				status from nc_village_proposal where status = 'C' and adlr_verified ='Y' and jds_verified is null order by created_at desc ")->result();

                foreach ($pending_proposals as $p) {
                    $data['pending_proposal'][] = $p;
                }
                $revert_proposals = $this->db->query("select id,dist_code,created_at,proposal_no,proposal_note,adlr_note,jds_note,
 				status from nc_village_proposal where status = 'D' and adlr_verified ='Y' and jds_verified ='Y' order by created_at desc ")->result();

                foreach ($revert_proposals as $r) {
                    $data['revert_proposal'][] = $r;
                }
            }
            echo json_encode($data);
            return;
        } elseif ($user == 'adlr' && $type == 'revertedfromadlrps') {
            $data['ps_reverted'] = array();
            foreach (json_decode(NC_DISTIRTCS) as $dist_code) {
                $this->dbswitch($dist_code);

                $reverted_proposals_ps = $this->db->query("select id,dist_code,created_at,proposal_no,proposal_note,adlr_note,ps_note,
 				status from nc_village_proposal where status = 'A' and ps_verified is null 
 				and reverted = 'Y' order by updated_at desc ")->result();
                foreach ($reverted_proposals_ps as $d) {
                    $data['ps_reverted'][] = $d;
                }
            }
            echo json_encode($data);
            return;
        }
    }


    public function getProposalsForDlr()
    {
        $user = $this->UtilsModel->cleanPattern($this->input->post('user'));
        $type = $this->UtilsModel->cleanPattern($this->input->post('type'));

        $data = array();
        if ($user == 'dlr' && $type == 'revertedfordlr') {
            $notification = $this->input->post('notification', true);
            $data['all_reverted_villages'] = array();
            // $data['adlr_reverted_villages'] = array();
            // $data['ps_reverted_villages'] = array();
            // $data['so_reverted_villages'] = array();
            foreach (json_decode(NC_DISTIRTCS) as $dist_code) {
                $this->dbswitch($dist_code);
                $adlr_proposal_reverted = $this->db->query("select id,dist_code,created_at,proposal_no,proposal_note,adlr_note,
                status from nc_village_proposal where status = 'E' and adlr_verified ='Y' and (user_type = 'DLR' or user_type='ADLR') order by updated_at desc ")->result();
                $ps_proposal_reverted = $this->db->query("select id,dist_code,created_at,proposal_no,proposal_note,adlr_note,ps_note,
 				status from nc_village_proposal where status = 'A' and (user_type = 'DLR' or user_type='ADLR') and ps_verified is null 
 				and reverted = 'Y' order by updated_at desc ")->result();
                $so_village_reverted = $this->db->query("SELECT * FROM nc_villages WHERE status='e' AND cu_user='DLR' AND dlr_verified IS NULL AND dlr_verified_at IS NULL AND dlr_proposal IS NULL")->result();

                if(!empty($adlr_proposal_reverted)) {
                    foreach ($adlr_proposal_reverted as $adlr_proposal) {
                        $adlr_proposal_villages = $this->db->query("SELECT * FROM nc_villages WHERE dlr_proposal_id=?", [$adlr_proposal->id])->result();
                        foreach($adlr_proposal_villages as $adlr_proposal_village) {
                            $adlr_proposal_village->reverted_from = 'ADLR';

                            $adlr_proposal_village->dist_name = $this->db->query("SELECT loc_name FROM location WHERE dist_code=? AND subdiv_code='00'", [$adlr_proposal_village->dist_code])->row()->loc_name;
                            $adlr_proposal_village->vill_townprt_name = $this->db->query("SELECT loc_name FROM location WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=?", [$adlr_proposal_village->dist_code, $adlr_proposal_village->subdiv_code, $adlr_proposal_village->cir_code, $adlr_proposal_village->mouza_pargona_code, $adlr_proposal_village->lot_no, $adlr_proposal_village->vill_townprt_code])->row()->loc_name;

                            $merge_village_requests = $this->db->where('nc_village_id', $adlr_proposal_village->id)->get('merge_village_requests')->result();
                            if(!empty($merge_village_requests)) {
                                $merge_village_name_arr = [];
                                foreach($merge_village_requests as $merge_village_request) {
                                    $vill_loc = $this->CommonModel->getLocations($merge_village_request->dist_code, $merge_village_request->subdiv_code, $merge_village_request->cir_code, $merge_village_request->mouza_pargona_code, $merge_village_request->lot_no, $merge_village_request->vill_townprt_code);
                                    $merge_village_name_arr[] = $vill_loc['village']['loc_name'];
                                    $merge_village_request->village_name = $vill_loc['village']['loc_name'];
                                    $merge_village_request->vill_loc = $vill_loc;
                                }
                                $adlr_proposal_village->merge_village_names = implode(', ', $merge_village_name_arr);
                                $adlr_proposal_village->merge_village_requests = $merge_village_requests;
                            }
                            else {
                                $adlr_proposal_village->merge_village_names = 'N.A.';
                                $adlr_proposal_village->merge_village_requests = $merge_village_requests;
                            }

                            $data['all_reverted_villages'][] = $adlr_proposal_village;
                        }
                    }
                }
                
                if(!empty($ps_proposal_reverted)) {
                    foreach ($ps_proposal_reverted as $ps_proposal) {
                        $ps_proposal_villages = $this->db->query("SELECT * FROM nc_villages WHERE dlr_proposal_id=? AND status='A'", [$ps_proposal->id])->result();
                        foreach($ps_proposal_villages as $ps_proposal_village) {
                            $ps_proposal_village->reverted_from = 'PS';

                            $ps_proposal_village->dist_name = $this->db->query("SELECT loc_name FROM location WHERE dist_code=? AND subdiv_code='00'", [$ps_proposal_village->dist_code])->row()->loc_name;
                            $ps_proposal_village->vill_townprt_name = $this->db->query("SELECT loc_name FROM location WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=?", [$ps_proposal_village->dist_code, $ps_proposal_village->subdiv_code, $ps_proposal_village->cir_code, $ps_proposal_village->mouza_pargona_code, $ps_proposal_village->lot_no, $ps_proposal_village->vill_townprt_code])->row()->loc_name;

                            $merge_village_requests = $this->db->where('nc_village_id', $ps_proposal_village->id)->get('merge_village_requests')->result();
                            if(!empty($merge_village_requests)) {
                                $merge_village_name_arr = [];
                                foreach($merge_village_requests as $merge_village_request) {
                                    $vill_loc = $this->CommonModel->getLocations($merge_village_request->dist_code, $merge_village_request->subdiv_code, $merge_village_request->cir_code, $merge_village_request->mouza_pargona_code, $merge_village_request->lot_no, $merge_village_request->vill_townprt_code);
                                    $merge_village_name_arr[] = $vill_loc['village']['loc_name'];
                                    $merge_village_request->village_name = $vill_loc['village']['loc_name'];
                                    $merge_village_request->vill_loc = $vill_loc;
                                }
                                $ps_proposal_village->merge_village_names = implode(', ', $merge_village_name_arr);
                                $ps_proposal_village->merge_village_requests = $merge_village_requests;
                            }
                            else {
                                $ps_proposal_village->merge_village_names = 'N.A.';
                                $ps_proposal_village->merge_village_requests = $merge_village_requests;
                            }

                            $data['all_reverted_villages'][] = $ps_proposal_village;
                        }
                    }
                }
                
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

    public function updateDlrProposalReverted()
    {
        $proposal_no = $this->input->post('proposal_no');
        $sign_key = $this->input->post('sign_key');
        $dist_code = $this->input->post('dist_code');
        $user_code = $this->input->post('user_code');
        $user_type = $this->input->post('user_type');
        $status = $this->input->post('forward_to');
        $dlr_note = $this->input->post('dlr_note');
        $this->dbswitch($dist_code);

        $is_exists = $this->db->get_where('nc_village_proposal', ['proposal_no' => $proposal_no])->num_rows();
        $this->db->trans_begin();
        if ($is_exists) {
            $this->db->where('proposal_no', $proposal_no)
                ->update('nc_village_proposal', array(
                    'user_code' => $user_code,
                    'user_type' => $user_type,
                    'sign_key' => $sign_key,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'status' => $status,
                    'proposal_note' => $dlr_note,
                ));
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
            $from = $user_type;
            $to = 'Senior Most Secretary';
            $task = 'Proposal Forwarded to Senior Most Secretary';
            $this->insertProceeding($proposal_no, $user_code, $dlr_note, $from, $to, $task, $dist_code);
            echo json_encode(array(
                'status' => '1',
            ));
            return;
        }
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

    /** notification proceeding revert back */
    public function revertBackNotificationProceeding()
    {
        $dist_code = $this->input->post('dist_code');
        $user = $this->input->post('user');
        $proposal_no = $this->input->post('proposal_no');
        $user_code = $this->input->post('user_code');
        $note = $this->input->post('note');

        $this->dbswitch($dist_code);
        if ($user == 'ps') {
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
            $from = 'Section Officer';
            $to = 'Assistant Section Officer';
            $task = 'Notification Revert to Assistant Section Officer';
            $return = $this->insertProceeding($proposal_no, $user_code, $note, $from, $to, $task, $dist_code);

            if ($return == 1) {
                echo json_encode('Y');
                return;
            } else {
                log_message("error", 'NC Proceeding: ' . json_encode('#PRO00002 Unable to update data.'));
                echo json_encode('N');
                return;
            }
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

    /** get village details */
    public function getVillageDetails()
    {
        $dist_code = $this->UtilsModel->cleanPattern($this->input->post('d'));
        $application_no = $this->UtilsModel->cleanPattern($this->input->post('application_no'));

        $this->dbswitch($dist_code);

        $nc_village = $this->db->query("select * from nc_villages where
				application_no='$application_no' and dist_code='$dist_code'")->row();
        $merge_village_name_arr = [];
        $merge_village_requests = $this->db->where('nc_village_id', $nc_village->id)->get('merge_village_requests')->result_array();
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

        $data['locations'] = $this->CommonModel->getLocations(
            $nc_village->dist_code,
            $nc_village->subdiv_code,
            $nc_village->cir_code,
            $nc_village->mouza_pargona_code,
            $nc_village->lot_no,
            $nc_village->vill_townprt_code
        );
        echo json_encode($data);
    }

    public function getNameChangeMapCount()
    {
        $count_name_change_m = 0;
        $count_name_change_p = 0;
        $count_name_change_e = 0;
        foreach (json_decode(NC_DISTIRTCS) as $dist_code) {
            $this->dbswitch($dist_code);
            $pending_name_change_m = $this->db->query("select * from nc_villages where status = 'M'")->num_rows();
            $count_name_change_m += $pending_name_change_m;
            $pending_name_change_p = $this->db->query("select * from nc_villages where status='P'")->num_rows();
            $count_name_change_p += $pending_name_change_p;
            $pending_name_change_e = $this->db->query("select * from nc_villages where status = 'Q'")->num_rows();
            $count_name_change_e += $pending_name_change_e;
        }
        $data = [
            'count_name_change_m' => $count_name_change_m,
            'count_name_change_p' => $count_name_change_p,
            'count_name_change_e' => $count_name_change_e,
        ];
        echo json_encode($data);
        return;
    }

    public function getNameChangeMapCountAds()
    {
        $count = 0;
        foreach (json_decode(NC_DISTIRTCS) as $dist_code) {
            $this->dbswitch($dist_code);
            $pending_name_change_map = $this->db->query("select * from nc_villages where status = 'N'")->num_rows();
            $count += $pending_name_change_map;
        }
        $data = [
            'count' => $count,
        ];
        echo json_encode($data);
        return;
    }

    /** get Proposal from adlr and jds */
    public function getNameChangeMapVillages()
    {
        $data = [];
        foreach (json_decode(NC_DISTIRTCS) as $dist_code) {
            $data_dist['dist_code'] = $dist_code;
            $this->dbswitch($dist_code);
            $query = "select ll.loc_name as dist_name, l.loc_name as village_name,ncv.created_at,ncv.status,ncv.dist_code,ncv.subdiv_code,ncv.cir_code,ncv.mouza_pargona_code,ncv.lot_no,ncv.vill_townprt_code,ncv.application_no,
				ncv.dc_verified,ncv.dc_verified_at,ncv.dc_note,ncv.jds_verified,ncv.jds_verified_at,ncv.jds_note,ncv.ads_verified,ncv.ads_verified_at,ncv.ads_note,ncv.dlr_note from
				nc_villages ncv join
				location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code
                 and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and
                 ncv.vill_townprt_code = l.vill_townprt_code
                 join location ll on ncv.dist_code = ll.dist_code and ll.subdiv_code='00' 
                 where (ncv.status='M' or ncv.status='P') order by ncv.created_at desc";
            $pendings = $this->db->query($query)->result();
            foreach ($pendings as $p) {
                $data[] = $p;
            }
        }
        echo json_encode($data);
        return;
    }
    /** get Proposal from adlr and jds */
    public function getDlrForwardedVillages()
    {
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
            where ncv.status='P' 
            order by ncv.created_at desc";
            $pendings = $this->db->query($query)->result();
            foreach ($pendings as $p) {
                $data[] = $p;
            }
        }
        echo json_encode($data);
        return;
    }
    public function getNameChangeDcForwardedVillages()
    {
        $data = [];
        foreach (json_decode(NC_DISTIRTCS) as $dist_code) {
            $data_dist['dist_code'] = $dist_code;
            $this->dbswitch($dist_code);
            $query = "select ll.loc_name as dist_name, l.loc_name as village_name,ncv.created_at,ncv.status,ncv.dist_code,ncv.subdiv_code,ncv.cir_code,ncv.mouza_pargona_code,ncv.lot_no,ncv.vill_townprt_code,ncv.application_no,ncv.uuid,
				ncv.dc_verified,ncv.dc_verified_at,ncv.dc_note,ncv.jds_verified,ncv.jds_verified_at,ncv.jds_note,ncv.ads_verified,ncv.ads_verified_at,ncv.ads_note,ncv.dlr_note from
				nc_villages ncv join
				location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code
                 and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and
                 ncv.vill_townprt_code = l.vill_townprt_code
                 join location ll on ncv.dist_code = ll.dist_code and ll.subdiv_code='00' 
                 where ncv.status='M' order by ncv.created_at desc";
            $pendings = $this->db->query($query)->result();
            foreach ($pendings as $p) {
                $data[] = $p;
            }
        }
        echo json_encode($data);
        return;
    }
    public function forwardNameChangeMap()
    {
        $dist_code = $this->input->post('dist_code');
        $note = $this->input->post('note');
        $user = $this->input->post('user');
        $application_no = $this->input->post('application_no');
        $user_code = $this->input->post('user_code');

        $this->dbswitch($dist_code);
        if ($user == 'JDS') {
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
            echo json_encode('Y');
            return;
        } else {
            log_message("error", 'NC_VILLAGE_Update: ' . json_encode('#UGO0000100 Unable to update data.'));
            echo json_encode('N');
            return;
        }
    }
    public function forwardToCoForChithaCorrection(){
        $dist_code = $this->input->post('dist_code');
        $note = $this->input->post('jds_remark');
        $user = $this->input->post('user');
        $application_no = $this->input->post('application_no');
        $user_code = $this->input->post('user_code');
        $this->dbswitch($dist_code);

        $this->db->set([
                'jds_note' => $note,
                "jds_user_code" => $user_code,
                "status" => 'h',
                "updated_at" => date('Y-m-d H:i:s'),
                "pre_user" => 'JDS',
                "cu_user" => 'CO',
                "co_verified" => null,
                "co_verified_at" => null,
                "dc_verified" => null,
                "dc_verified_at" => null,
                "dc_chitha_sign" => null
            ]);
            $from = 'Joint Director of Surveys';
            $to = 'Circle Officer';
            $task = 'NC Village Forwarded to Circle Officer from JDS for chitha/map or both correction.';

        $this->db->where('application_no', $application_no);
        $this->db->where('dist_code', $dist_code);
        $this->db->update('nc_villages');
        if ($this->db->affected_rows() > 0) {
            $return = $this->insertProceeding($application_no, $user_code, $note, $from, $to, $task, $dist_code);
            echo json_encode('Y');
            return;
        } else {
            log_message("error", 'NC_VILLAGE_Update: ' . json_encode('#UGO0000100 Unable to update data.'));
            echo json_encode('N');
            return;
        }
    }
    /** forward to DLR for dismissal */
    public function forwardToDlrForDismissal(){
        $dist_code = $this->input->post('dist_code');
        $note = $this->input->post('note');
        $user = $this->input->post('user');
        $application_no = $this->input->post('application_no');
        $user_code = $this->input->post('user_code');

        $this->dbswitch($dist_code);
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
            $return = $this->insertProceeding($application_no, $user_code, $note, $from, $to, $task, $dist_code);
            echo json_encode('Y');
            return;
        } else {
            log_message("error", 'NC_VILLAGE_Update: ' . json_encode('#UGO0000100 Unable to update data.'));
            echo json_encode('N');
            return;
        }
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
    /** get Proposal from adlr and jds */
    public function getNameChangeMapVillagesAds()
    {
        $data = [];
        foreach (json_decode(NC_DISTIRTCS) as $dist_code) {
            $data_dist['dist_code'] = $dist_code;
            $this->dbswitch($dist_code);
            $query = "select ll.loc_name as dist_name, l.loc_name as village_name,ncv.created_at,ncv.status,ncv.dist_code,ncv.uuid,ncv.application_no,
				ncv.dc_verified,ncv.dc_verified_at,ncv.dc_note,ncv.jds_verified,ncv.jds_verified_at,ncv.jds_note,ncv.ads_verified,ncv.ads_verified_at,ncv.ads_note from
				nc_villages ncv join
				location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code
                 and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and
                 ncv.vill_townprt_code = l.vill_townprt_code
                 join location ll on ncv.dist_code = ll.dist_code and ll.subdiv_code='00' 
                 where ncv.status='N' order by ncv.created_at desc";
            $pendings = $this->db->query($query)->result();
            foreach ($pendings as $p) {
                $data[] = $p;
            }
        }
        echo json_encode($data);
        return;
    }

    /** get Proposal from adlr and jds */
    public function getNameChangeMapVillagesE()
    {
        $data = [];
        foreach (json_decode(NC_DISTIRTCS) as $dist_code) {
            $data_dist['dist_code'] = $dist_code;
            $this->dbswitch($dist_code);
            $query = "select ll.loc_name as dist_name, l.loc_name as village_name,ncv.created_at,ncv.status,ncv.dist_code,ncv.application_no,
				ncv.dc_verified,ncv.dc_verified_at,ncv.dc_note,ncv.jds_verified,ncv.jds_verified_at,ncv.jds_note,ncv.ads_verified,ncv.ads_verified_at,ncv.ads_note from
				nc_villages ncv join
				location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code
                 and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and
                 ncv.vill_townprt_code = l.vill_townprt_code
                 join location ll on ncv.dist_code = ll.dist_code and ll.subdiv_code='00' 
                 where ncv.status='Q' order by ncv.created_at desc";
            $pendings = $this->db->query($query)->result();
            foreach ($pendings as $p) {
                $data[] = $p;
            }
        }
        echo json_encode($data);
        return;
    }

    /** get all pending village for DLR */
    public function apiGetAllNameChangePendingsDlr()
    {
        $d_list = (array) json_decode(NC_DISTIRTCS);

        $village = array();
        foreach ($d_list as $k => $dist_code) {
            $this->dbswitch($dist_code);

            $query = "select ll.loc_name as dist_name, l.loc_name as village_name,
                ncv.status,ncv.dist_code,ncv.application_no,ncv.dc_verified,ncv.dc_verified_at,ncv.dc_note,ncv.ads_verified,ncv.ads_verified_at,ncv.ads_note,ncv.jds_verified,ncv.jds_verified_at,ncv.jds_note,ncv.dlr_note from
				nc_villages ncv join
				location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code
                 and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and
                 ncv.vill_townprt_code = l.vill_townprt_code
                 join location ll on ncv.dist_code = ll.dist_code";

            $query = $query . " where ncv.dist_code='$dist_code' and ll.subdiv_code ='00' and (ncv.status='P' or ncv.status='M' or ncv.status='N')";

            $nc_villages = $this->db->query($query)->result();
            foreach ($nc_villages as $nc) {
                $village[] = $nc;
            }
        }
        echo json_encode($village);
        return;
    }

    /** Api for bhunaksha and area insert */
    public function pushBhunaksa()
    {
        $d_list = (array) json_decode(NC_DISTIRTCS);
        //sq meter
        $b = 1337.803776;
        $k = 267.5607552;
        $l = 13.37803776;

        foreach ($d_list as $dist_code) {
            $this->dbswitch($dist_code);

            $q2 = $this->db->query("select * from nc_villages");
            $r2 =  $q2->result();

            foreach ($r2 as $r) {
                $query = $this->db->select('count(*) as total_dag, SUM(dag_area_b) as total_bigha,
				SUM(dag_area_k) as total_katha,SUM(dag_area_lc) as total_lessa')
                    ->where(array(
                        'dist_code' => $r->dist_code,
                        'subdiv_code' => $r->subdiv_code,
                        'cir_code' => $r->cir_code,
                        '
						mouza_pargona_code' => $r->mouza_pargona_code,
                        'lot_no' => $r->lot_no,
                        'vill_townprt_code' => $r->vill_townprt_code
                    ))
                    ->get('chitha_basic')->row();
                $bigha_sq_m = $query->total_bigha * $b;
                $katha_sq_m = $query->total_katha * $k;
                $lessa_sq_m = $query->total_lessa * $l;

                $url = LANDHUB_BASE_URL_NEW . "BhunakshaApiController/getVillageInfo";
                $method = 'POST';

                $data['location'] = $r->dist_code . '_' . $r->subdiv_code . '_' . $r->cir_code . '_' .
                    $r->mouza_pargona_code . '_' . $r->lot_no . '_' . $r->vill_townprt_code;

                $api_output = $this->callApiV2($url, $method, $data);
                if (!$api_output) {
                    log_message("error", 'LAND HUB API FAIL');
                    echo "API FAIL";
                    return;
                }
                $api_output = json_decode($api_output);

                $bhunaksa_area_sq_km = 0;
                if (isset($api_output->totalVillageArea)) {
                    $bhunaksa_area_sq_km = $api_output->totalVillageArea;
                    $bhunaksa_area_sq_km = $bhunaksa_area_sq_km / 1000000;
                }
                $bhunaksa_total_dag = $api_output->totalPlots;
                $total_sq_km = ($bigha_sq_m + $katha_sq_m + $lessa_sq_m) / 1000000;

                $this->db->where('id', $r->id)
                    ->update(
                        'nc_villages',
                        array(
                            'chitha_total_dag' => $query->total_dag,
                            'chitha_total_area_skm' => round($total_sq_km, 5),
                            'bhunaksa_total_dag' => $bhunaksa_total_dag,
                            'bhunaksa_total_area_skm' => round($bhunaksa_area_sq_km, 5),
                        )
                    );
            }
        }
        echo "gg";
    }

    /** command api v2 */
    public static function callApiV2($url, $method, $data = null)
    {
        $curl = curl_init();

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

        $response = curl_exec($curl);

        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);
        if ($httpcode != 200) {
            log_message("error", 'API FAIL');
            return false;
        }

        return $response;
    }

    /** Get Chitha Dags from Nc village Dags */
    public function apiGetChithaDags()
    {
        $dist_code = $this->input->post('d');
        $application_no = $this->input->post('application_no');

        $this->dbswitch($dist_code);

        $q = "SELECT * from nc_village_dags WHERE application_no='$application_no' order by CAST(dag_no AS numeric)";

        $dags = $this->db->query($q)->result();

        echo json_encode($dags);
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
            $this->db->trans_complete();
            $arr = array(
                'data' => 'Y',
                'nc_village' => $nc_village = $this->db->query("select * from nc_villages where application_no='$application_no'")->row(),
                'status_code' => 200,
            );
            echo json_encode($arr);
            return;
        }

        $arr = array(
            'data' => 'N',
            'nc_village' => $nc_village = $this->db->query("select * from nc_villages where application_no='$application_no'")->row(),
            'status_code' => 200,
        );
        echo json_encode($arr);
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
    /** GET ALL PROPOSALS */
    public function getProposalSingle()
    {
        $dist_code = $this->input->post('dist_code');
        $this->dbswitch($dist_code);
        $proposal_no = $this->input->post('proposal_no');
        $proposal = $this->db->query("select id,created_at,proposal_no,user_code,user_type,status,created_at,dist_code,ps_verified,ps_verified_at,dlr_verified,dlr_verified_at,secretary_verified,secretary_verified_at,joint_secretary_verified,joint_secretary_verified_at,section_officer_verified,section_officer_verified_at,asst_section_officer_verified,asst_section_officer_verified_at,ps_forwarded_to_sec,sec_forwarded_to_jsec,jsec_forwarded_to_so,so_forwarded_to_aso from nc_village_proposal where proposal_no='$proposal_no'")->row();
        $proposal->villages = $this->db->query("select location_dist.loc_name as dist_name,
        location_circle.loc_name as circle_name,
        location_mouza.loc_name as mouza_name,
        location_lot.loc_name as lot_name,
        location_village.loc_name as vill_name,
         ncv.dist_code,ncv.subdiv_code,ncv.cir_code,ncv.mouza_pargona_code,ncv.lot_no,ncv.vill_townprt_code,ncv.dlr_proposal_id
         from nc_villages ncv 
         join location location_dist on location_dist.dist_code=ncv.dist_code and location_dist.subdiv_code='00'
         join location location_circle on location_circle.dist_code=ncv.dist_code and location_circle.subdiv_code=ncv.subdiv_code and location_circle.cir_code=ncv.cir_code and location_circle.mouza_pargona_code='00'
         join location location_mouza on location_mouza.dist_code=ncv.dist_code and location_mouza.subdiv_code=ncv.subdiv_code and location_mouza.cir_code=ncv.cir_code and location_mouza.mouza_pargona_code=ncv.mouza_pargona_code and location_mouza.lot_no='00'
         join location location_lot on location_lot.dist_code=ncv.dist_code and location_lot.subdiv_code=ncv.subdiv_code and location_lot.cir_code=ncv.cir_code and location_lot.mouza_pargona_code=ncv.mouza_pargona_code and location_lot.lot_no=ncv.lot_no and location_lot.vill_townprt_code='00000'
         join location location_village on location_village.dist_code=ncv.dist_code and location_village.subdiv_code=ncv.subdiv_code and location_village.cir_code=ncv.cir_code and location_village.mouza_pargona_code=ncv.mouza_pargona_code and location_village.lot_no=ncv.lot_no and location_village.vill_townprt_code=ncv.vill_townprt_code
         where ncv.dlr_proposal_id='$proposal->id'")->result();

        $arr = array(
            'data' => $proposal,
            'status_code' => 200,
        );
        echo json_encode($arr);
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
                        'status' => 'I',
                        'pre_user' => $from,
                        'cu_user' => 'DLR',
                    ]
                );
        }

        if ($this->db->affected_rows() > 0) {
            $this->db->trans_complete();
            echo json_encode('Y');
            return;
        }

        echo json_encode('N');
        return;
    }
    public function jdsRevertToDc()
    {
        $remark = $this->UtilsModel->cleanPattern($this->input->post('note'));
        $dist_code = $this->UtilsModel->cleanPattern($this->input->post('dist_code'));
        $application_no = $this->UtilsModel->cleanPattern($this->input->post('application_no'));
        $user_code = $this->UtilsModel->cleanPattern($this->input->post('user_code'));
        $user = $this->input->post('user');

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
            $this->db->trans_complete();
            echo json_encode('Y');
            return;
        }

        echo json_encode('N');
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
        $dist = $this->UtilsModel->cleanPattern($this->input->post('dist_code'));
        $subdiv = $this->UtilsModel->cleanPattern($this->input->post('subdiv_code'));
        $circle = $this->UtilsModel->cleanPattern($this->input->post('cir_code'));
        $mouza = $this->UtilsModel->cleanPattern($this->input->post('mouza_pargona_code'));
        $lot = $this->UtilsModel->cleanPattern($this->input->post('lot_no'));
        $request_villages = $this->input->post('vill_townprt_codes');
        $pullback_case_id_with_loc = $this->input->post('pullback_case_id_with_loc');
        $remark = $this->UtilsModel->cleanPattern($this->input->post('remark'));
        $created_by_unique_user_id = $this->input->post('created_by_unique_user_id');
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

    public function getVillageListForTracking(){
        $dist_code = $this->UtilsModel->cleanPattern($this->input->post('dist_code'));
        $subdiv_code = $this->UtilsModel->cleanPattern($this->input->post('subdiv_code'));
        $cir_code = $this->UtilsModel->cleanPattern($this->input->post('cir_code'));
        $mouza_pargona_code = $this->UtilsModel->cleanPattern($this->input->post('mouza_pargona_code'));
        $lot_no = $this->UtilsModel->cleanPattern($this->input->post('lot_no'));

        $this->dbswitch($dist_code);
        $query = "SELECT nc.*, l1.loc_name as vill_name FROM nc_villages nc JOIN location l1 ON l1.dist_code = nc.dist_code and l1.subdiv_code = nc.subdiv_code and l1.cir_code=nc.cir_code and l1.mouza_pargona_code=nc.mouza_pargona_code and l1.lot_no=nc.lot_no and l1.vill_townprt_code=nc.vill_townprt_code WHERE nc.dist_code=? and nc.subdiv_code=? and nc.cir_code=? and nc.mouza_pargona_code=? and nc.lot_no=?";

        $villages = $this->db->query($query, ['dist_code' => $dist_code, 'subdiv_code' => $subdiv_code, 'cir_code' => $cir_code, 'mouza_pargona_code' => $mouza_pargona_code, 'lot_no' => $lot_no]);
        $nc_villages = $villages->result_array();

        $arr = array(
            'data' => $nc_villages,
            'status_code' => 200,
        );
        echo json_encode($arr);
    }

    public function apiVillageTrackingView(){
        $dist_code = $this->input->post('dist_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $vill_townprt_code = $this->input->post('vill_townprt_code');
        $vill_uuid = $this->input->post('vill_uuid');

        $data = $this->villageWiseTracking($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $vill_uuid);

        $html = $this->load->view('nc_village/common/case_tracking_ui', $data, true);

        $output_data['html'] = $html;
        echo json_encode(['data' => $output_data]);
    }

    public function getVillagesTracking(){
        $dist_code = $this->UtilsModel->cleanPattern($this->input->post('dist_code'));

        $this->dbswitch($dist_code);
        $query = "SELECT nc.dist_code, nc.subdiv_code, nc.cir_code, nc.mouza_pargona_code, nc.lot_no, nc.vill_townprt_code, nc.uuid, l1.loc_name as vill_name, l2.loc_name as dist_name, l3.loc_name as cir_name FROM nc_villages nc JOIN location l1 ON l1.dist_code = nc.dist_code and l1.subdiv_code = nc.subdiv_code and l1.cir_code=nc.cir_code and l1.mouza_pargona_code=nc.mouza_pargona_code and l1.lot_no=nc.lot_no and l1.vill_townprt_code=nc.vill_townprt_code JOIN location l2 ON l2.dist_code=nc.dist_code and l2.subdiv_code='00' JOIN location l3 ON l3.dist_code=nc.dist_code and l3.subdiv_code=nc.subdiv_code and l3.cir_code=nc.cir_code and l3.mouza_pargona_code='00' WHERE nc.dist_code=? and nc.app_version='V1'";

        $villages = $this->db->query($query, ['dist_code' => $dist_code]);
        $nc_villages = $villages->result_array();

        if(count($nc_villages)){
            foreach($nc_villages as $key => $nc_village){
                $data = $this->villageWiseTracking($nc_village['dist_code'], $nc_village['subdiv_code'], $nc_village['cir_code'], $nc_village['mouza_pargona_code'], $nc_village['lot_no'], $nc_village['vill_townprt_code'], $nc_village['uuid']);
                $nc_villages[$key] = array_merge($nc_villages[$key], $data);
            }
        }

        $arr = array(
            'data' => $nc_villages,
            'status_code' => 200,
        );
        echo json_encode($arr);
    }

    public function getVillagesTrackingNew(){
        $dist_code = $this->UtilsModel->cleanPattern($this->input->post('dist_code'));

        $this->dbswitch($dist_code);
        $query = "SELECT nc.dist_code, nc.subdiv_code, nc.cir_code, nc.mouza_pargona_code, nc.lot_no, nc.vill_townprt_code, nc.uuid, l1.loc_name as vill_name, l2.loc_name as dist_name, l3.loc_name as cir_name, nc.app_version FROM nc_villages nc JOIN location l1 ON l1.dist_code = nc.dist_code and l1.subdiv_code = nc.subdiv_code and l1.cir_code=nc.cir_code and l1.mouza_pargona_code=nc.mouza_pargona_code and l1.lot_no=nc.lot_no and l1.vill_townprt_code=nc.vill_townprt_code JOIN location l2 ON l2.dist_code=nc.dist_code and l2.subdiv_code='00' JOIN location l3 ON l3.dist_code=nc.dist_code and l3.subdiv_code=nc.subdiv_code and l3.cir_code=nc.cir_code and l3.mouza_pargona_code='00' WHERE nc.dist_code=?";

        $villages = $this->db->query($query, ['dist_code' => $dist_code]);
        $nc_villages = $villages->result_array();

        if(count($nc_villages)){
            foreach($nc_villages as $key => $nc_village){
                $data = $this->villageWiseTrackingNew($nc_village['dist_code'], $nc_village['subdiv_code'], $nc_village['cir_code'], $nc_village['mouza_pargona_code'], $nc_village['lot_no'], $nc_village['vill_townprt_code'], $nc_village['uuid']);
                $nc_villages[$key] = array_merge($nc_villages[$key], $data);
            }
        }

        $arr = array(
            'data' => $nc_villages,
            'status_code' => 200,
        );
        echo json_encode($arr);
    }

    private function villageWiseTracking($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $vill_uuid){
        /*
        0 => Case has not been reached on this user
        1 => Case has been passed from this user end, 
        2 => Case is pending on this user end (Fresh Case or Revert Case), 
        */
        $this->dbswitch($dist_code);
        $co_status = $lm_status = $sk_status = $dc_status = 0;
        $dlr_status = $ps_1_status = $secretary_1_status = $js_1_status = $so_1_status = 0;
        $aso_status = $so_2_status = $js_2_status = $secretary_2_status = $ps_2_status = 0;
        $minister_status = $ps_3_status = $js_3_status = 0;

        $data['dlr_proposal_date'] = '';
        $data['ps_1_action_date'] = '';
        $data['secretary_1_action_date'] = '';
        $data['js_1_action_date'] = '';
        $data['so_1_action_date'] = '';
        $data['aso_1_action_date'] = '';
        $data['so_2_action_date'] = '';
        $data['js_2_action_date'] = '';
        $data['sec_2_action_date'] = '';
        $data['ps_action_date'] = '';
        $data['minister_action_date'] = '';
        $data['ps_2_action_date'] = '';
        $data['js_3_action_date'] = '';

        $nc_village = $this->db->where('dist_code', $dist_code)->where('subdiv_code', $subdiv_code)->where('cir_code', $cir_code)->where('mouza_pargona_code', $mouza_pargona_code)->where('lot_no', $lot_no)->where('vill_townprt_code', $vill_townprt_code)->get('nc_villages')->row();
        
        $dlr_proposal = $this->db->where('id', $nc_village->dlr_proposal_id)->get('nc_village_proposal')->row();
        $section_officer_proposal = $this->db->where('id', $nc_village->section_officer_proposal_id)->get('nc_village_proposal')->row();
        $asst_section_officer_proposal = $this->db->where('id', $nc_village->asst_section_officer_proposal_id)->get('nc_village_proposal')->row();

        $get_map_url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/apiGetMapByLocation";
        $method = 'POST';
        $post_data = [
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'mouza_pargona_code' => $mouza_pargona_code,
            'lot_no' => $lot_no,
            'vill_townprt_code' => $vill_townprt_code,
            'vill_uuid' => $vill_uuid
        ];
        $map_output = callIlrmsApi($get_map_url, $method, $post_data);
        $map = null;
        if(!empty($map_output) && !empty($map_output->data) && $map_output->status_code == 200){
            $map = $map_output->data;
        }

        // ------------------- LM CASE START ------------------- 
        if($map){
            if($map->flag == 'B'){
                $lm_status = 2;
            }else if($nc_village){
                if($nc_village->status == 'U' && $nc_village->pre_user == 'SK' && $nc_village->cu_user == 'LM'){
                    // Reverted
                    $lm_status = 2;
                }else{
                    $lm_status = 1;
                }
            }
        }
        // ------------------- LM CASE END ------------------- 
        
        // ------------------- SK CASE START ------------------- 
        if($nc_village){
            if($nc_village->status == 'S'){
                $sk_status = 2;
            }else if($nc_village->status == 'H' && $nc_village->pre_user == 'CO' && $nc_village->cu_user == 'SK'){
                // Reverted
                $sk_status = 2;
            }else{
                $sk_status = 1;
            }
        }
        // ------------------- SK CASE END ------------------- 

        // ------------------- CO CASE START ------------------- 
        if($map){
            if($map->flag == 'F'){
                $co_status = 2;
            }else{
                if($nc_village){
                    if($nc_village->status == 'T'){
                        $co_status = 2;
                    }else if($nc_village->status == 'J' && $nc_village->pre_user == 'DC' && $nc_village->cu_user == 'CO'){
                        // Reverted
                        $co_status = 2;
                    }else if($nc_village->status == 'O' && $nc_village->co_proposal == NULL){
                        $co_status = 2;
                    }elseif(!$nc_village->co_proposal){
                        $co_status = 1;
                    }
                }
            }
        }
        // ------------------- CO CASE END ------------------- 

        // ------------------- DC CASE START ------------------- 
        if($nc_village){
            if($nc_village->status == 'G'){
                $dc_status = 2;
            }else if($nc_village->status == 'K'){
                $dc_status = 2;
            }else if($nc_village->status == 'B'){
                // Reverted
                $dc_status = 2;
            }else if($nc_village->dc_proposal != NULL){
                $dc_status = 1;
            }
        }
        // ------------------- DC CASE END ------------------- 

        // ------------------- DLR CASE START --------------------
        if(in_array($nc_village->status, ['I', 'L'])){
            $dlr_status = 2;
        }else{
            if($nc_village->dlr_proposal == 'Y'){
                if($dlr_proposal->ps_verified == NULL && $dlr_proposal->reverted == NULL){
                    $dlr_status = 1;
                    $data['dlr_proposal_date'] = $nc_village->dlr_verified_at;
                }elseif($dlr_proposal->ps_verified == 'Y' && $dlr_proposal->reverted == NULL){
                    $dlr_status = 1;
                    $data['dlr_proposal_date'] = $nc_village->dlr_verified_at;
                }
                // elseif($dlr_proposal->ps_verified == 'Y'){
                //     $dlr_status = 1;
                //     $data['dlr_proposal_date'] = $nc_village->dlr_verified_at;
                // }
            }
            if($nc_village->status == 'e'){
                // Revert in New Flow
                $dlr_status = 2;
            }else{
                if($dlr_proposal && $nc_village->select_village_on_prop_sign_from_so == 0 && $dlr_proposal->ps_verified == NULL && $dlr_proposal->reverted == 'Y'){
                    // Revert in OLD Flow
                    // $dlr_status = 2;
                    if($nc_village->status == 'P'){
                        // DLR revert to JDS
                        $dlr_status = 0;
                    }elseif($nc_village->status == 'N'){
                        // DLR reverted to JDS [JDS forwarded to ADS]
                        $dlr_status = 0;
                    }else{
                        $dlr_status = 2;
                    }
                }
            }
        }
        // ------------------- DLR CASE END --------------------

        // ------------------- PS_step1 START --------------------
        if($dlr_proposal){
            if(!$dlr_proposal->forwarded_to){
                if($dlr_proposal->ps_verified == 'Y' && $dlr_proposal->reverted == NULL){
                    $ps_1_status = 1;
                    $data['ps_1_action_date'] = $dlr_proposal->ps_verified_at;
                }else{
                    // reverted + pending case
                    if($dlr_proposal->ps_verified == NULL && $dlr_proposal->reverted == 'Y'){
                        // Reverted
                        $ps_1_status = 0;
                    }else{
                        $ps_1_status = 2;
                    }
                }
            }
        }
        // ------------------- PS_step1 END --------------------

        // ------------------- SECRETARY_step1 START --------------------
        if($dlr_proposal){
            if(!$dlr_proposal->forwarded_to){
                if($dlr_proposal->ps_verified == 'Y' && $dlr_proposal->ps_forwarded_to_sec && $dlr_proposal->status == 'A'){
                    if($dlr_proposal->reverted == NULL && $dlr_proposal->secretary_verified == 'Y'){
                        $secretary_1_status = 1;
                        $data['secretary_1_action_date'] = $dlr_proposal->secretary_verified_at;
                    }else{
                        if($dlr_proposal->reverted == 'Y' && $dlr_proposal->secretary_verified == 'Y' && $dlr_proposal->joint_secretary_verified == NULL){
                            $secretary_1_status = 2;
                        }elseif($dlr_proposal->reverted == NULL && $dlr_proposal->secretary_verified == NULL){
                            $secretary_1_status = 2;
                        }
                    }
                }
            }
        }
        // ------------------- SECRETARY_step1 END --------------------
        
        // ------------------- JS_step1 START --------------------
        if($dlr_proposal){
            if(!$dlr_proposal->forwarded_to){
                if($dlr_proposal->ps_verified == 'Y' && $dlr_proposal->secretary_verified == 'Y' && $dlr_proposal->sec_forwarded_to_jsec && $dlr_proposal->status == 'A'){
                    if($dlr_proposal->reverted == NULL && $dlr_proposal->joint_secretary_verified == 'Y'){
                        $js_1_status = 1;
                        $data['js_1_action_date'] = $dlr_proposal->joint_secretary_verified_at;
                    }else{
                        if($dlr_proposal->reverted == 'Y' && $dlr_proposal->secretary_verified == 'Y' && $dlr_proposal->joint_secretary_verified == NULL){
                            $js_1_status = 2;
                        }elseif($dlr_proposal->reverted == NULL && $dlr_proposal->ps_verified == 'Y' && $dlr_proposal->secretary_verified == 'Y' && $dlr_proposal->joint_secretary_verified == NULL){
                            $js_1_status = 2;
                        }
                    }
                }
            }
        }
        // ------------------- JS_step1 END --------------------
        
        // ------------------- SO_step1 START --------------------
        if($dlr_proposal){
            if(!$dlr_proposal->forwarded_to){
                // Old Process
                if($dlr_proposal->section_officer_verified == 'Y' && $dlr_proposal->reverted == NULL){
                    $so_1_status = 1;
                    $data['so_1_action_date'] = $dlr_proposal->section_officer_verified_at;
                }else{
                    // reverted + pending case
                    if($dlr_proposal->secretary_verified == 'Y' && $dlr_proposal->joint_secretary_verified == 'Y'){
                        if($dlr_proposal->section_officer_verified == NULL){
                            // $so_1_status = 2;
                            if($nc_village->status == 'e'){
                                // reverted in new flow
                                // Section Officer Reverted village to DLR
                            }else{
                                if($section_officer_proposal){
                                    // In the new flow SO has made new proposal
                                    $so_1_status = 1;
                                }else{
                                    $so_1_status = 2;
                                }
                            }
                        }
                        if($dlr_proposal->section_officer_verified == 'Y' && $dlr_proposal->reverted == 'Y'){
                            $so_1_status = 2;
                        }
                    }
                }
            }else{
                // Skip Module (proposal forward + village forward)
                // New Process
                if($dlr_proposal->section_officer_verified == NULL && in_array($nc_village->status, ['A','a'])){
                    $so_1_status = 2;
                }else{
                    if($nc_village->select_village_on_prop_sign_from_so == 0){
                        // if($dlr_proposal->section_officer_verified == 'Y'){
                        //     $so_1_status = 1;
                        //     $data['so_1_action_date'] = $dlr_proposal->section_officer_verified_at;
                        // }
                        if($dlr_proposal->section_officer_verified == 'Y' && $dlr_proposal->reverted == NULL){
                            $so_1_status = 1;
                            $data['so_1_action_date'] = $dlr_proposal->section_officer_verified_at;
                        }
                    }else{
                        if($section_officer_proposal && $nc_village->section_officer_verified == 'Y'){
                            $so_1_status = 1;
                            $data['so_1_action_date'] = $nc_village->section_officer_verified_at;
                        }
                    }
                }

                // Revert is pending
            }
        }
        // ------------------- SO_step1 END --------------------
        
        // ------------------- ASO START --------------------
        if($dlr_proposal){
            if($nc_village->select_village_on_prop_sign_from_so == 0){
                // Proposal Forward Process (Old Process)
                if($dlr_proposal->so_forwarded_to_aso){
                    if($dlr_proposal->forwarded_to){
                        // if($dlr_proposal->section_officer_verified == 'Y' && $dlr_proposal->asst_section_officer_verified == NULL){
                        if($dlr_proposal->section_officer_verified == 'Y' && $dlr_proposal->asst_section_officer_verified == NULL && $dlr_proposal->reverted == NULL){
                            $aso_status = 2;
                        }elseif($dlr_proposal->section_officer_verified == 'Y' && $dlr_proposal->asst_section_officer_verified == 'Y'){
                            $proposal_id = $dlr_proposal->id;
                            $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/getNotificationByProposalId/$proposal_id";
                            $method = 'GET';
                            $output = callIlrmsApi($url, $method);
                            if(!empty($output) && !empty($output->notification)){
                                $notification = $output->notification;
                                if($notification->reverted != 'Y' && $notification->asst_section_officer_verified == 'Y'){
                                    $aso_status = 1;
                                    $data['aso_1_action_date'] = $notification->asst_section_officer_verified_at;
                                } else{
                                    $aso_status = 2;
                                }
                            }
                        }
                    }else{
                        if($dlr_proposal->secretary_verified == 'Y' && $dlr_proposal->joint_secretary_verified == 'Y' && $dlr_proposal->section_officer_verified == 'Y' && $dlr_proposal->asst_section_officer_verified == NULL){
                            $aso_status = 2;
                        }elseif($dlr_proposal->secretary_verified == 'Y' && $dlr_proposal->joint_secretary_verified == 'Y' && $dlr_proposal->section_officer_verified == 'Y' && $dlr_proposal->asst_section_officer_verified == 'Y'){
                            $proposal_id = $dlr_proposal->id;
                            $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/getNotificationByProposalId/$proposal_id";
                            $method = 'GET';
                            $output = callIlrmsApi($url, $method);
                            if(!empty($output) && !empty($output->notification)){
                                $notification = $output->notification;
                                if($notification->reverted != 'Y' && $notification->asst_section_officer_verified == 'Y'){
                                    $aso_status = 1;
                                    $data['aso_1_action_date'] = $notification->asst_section_officer_verified_at;
                                } else{
                                    $aso_status = 2;
                                }
                            }
                        }
                    }
                }
            }else{
                // Village Forward Process (New Process)
                if($section_officer_proposal && $nc_village->section_officer_verified == 'Y'){
                    if($asst_section_officer_proposal){
                        $proposal_id = $asst_section_officer_proposal->id;
                        $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/getNotificationByProposalId/$proposal_id";
                        $method = 'GET';
                        $output = callIlrmsApi($url, $method);
                        if(!empty($output) && !empty($output->notification)){
                            $notification = $output->notification;
                            if($notification->reverted != 'Y' && $notification->asst_section_officer_verified == 'Y'){
                                $aso_status = 1;
                                $data['aso_1_action_date'] = $notification->asst_section_officer_verified_at;
                            } else{
                                $aso_status = 2;
                            }
                        }
                    }else{
                        $aso_status = 2;
                    }
                }
            }
        }
        // ------------------- ASO END --------------------
        
        // ------------------- SO_step2 START --------------------
        if($dlr_proposal){
            $proposal_id = '';
            if($nc_village->select_village_on_prop_sign_from_so == 0){
                $proposal_id = $dlr_proposal->id;
            } else{
                if($asst_section_officer_proposal){
                    $proposal_id = $asst_section_officer_proposal->id;
                }
            }
            
            if($proposal_id != ''){
                $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/getNotificationByProposalId/$proposal_id";
                $method = 'GET';
                $output = callIlrmsApi($url, $method);
                if(!empty($output) && !empty($output->notification)){
                    $notification = $output->notification;
                    if($notification->reverted != 'Y' && $notification->asst_section_officer_verified == 'Y'){
                        if($notification->section_officer_verified == 'Y'){
                            $so_2_status = 1;
                            $data['so_2_action_date'] = $notification->section_officer_verified_at;
                        } else{
                            $so_2_status = 2;
                        }
                    }
                }
            }
        }
        // ------------------- SO_step2 END --------------------
        
        // ------------------- JS_step2 START --------------------
        if($dlr_proposal){
            $proposal_id = '';
            if($nc_village->select_village_on_prop_sign_from_so == 0){
                $proposal_id = $dlr_proposal->id;
            } else{
                if($asst_section_officer_proposal){
                    $proposal_id = $asst_section_officer_proposal->id;
                }
            }

            if($proposal_id != ''){
                $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/getNotificationByProposalId/$proposal_id";
                $method = 'GET';
                $output = callIlrmsApi($url, $method);
                if(!empty($output) && !empty($output->notification)){
                    $notification = $output->notification;
                    if($notification->reverted != 'Y' && $notification->asst_section_officer_verified == 'Y' && $notification->section_officer_verified == 'Y'){
                        if($notification->joint_secretary_verified == 'Y'){
                            $js_2_status = 1;
                            $data['js_2_action_date'] = $notification->joint_secretary_verified_at;
                        } else{
                            $js_2_status = 2;
                        }
                    }
                }
            }
        }
        // ------------------- JS_step2 END --------------------
        
        // ------------------- SECRETARY_step2 START --------------------
        if($dlr_proposal){
            $proposal_id = '';
            if($nc_village->select_village_on_prop_sign_from_so == 0){
                $proposal_id = $dlr_proposal->id;
            } else{
                if($asst_section_officer_proposal){
                    $proposal_id = $asst_section_officer_proposal->id;
                }
            }

            if($proposal_id != ''){
                $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/getNotificationByProposalId/$proposal_id";
                $method = 'GET';
                $output = callIlrmsApi($url, $method);
                if(!empty($output) && !empty($output->notification)){
                    $notification = $output->notification;
                    if($notification->reverted != 'Y' && $notification->asst_section_officer_verified == 'Y' && $notification->section_officer_verified == 'Y' && $notification->joint_secretary_verified == 'Y'){
                        if($notification->secretary_verified == 'Y'){
                            $secretary_2_status = 1;
                            $data['sec_2_action_date'] = $notification->secretary_verified_at;
                        } else{
                            $secretary_2_status = 2;
                        }
                    }
                }
            }
        }
        // ------------------- SECRETARY_step2 END --------------------
        
        // ------------------- PS_step2 START --------------------
        if($dlr_proposal){
            $proposal_id = '';
            if($nc_village->select_village_on_prop_sign_from_so == 0){
                $proposal_id = $dlr_proposal->id;
            } else{
                if($asst_section_officer_proposal){
                    $proposal_id = $asst_section_officer_proposal->id;
                }
            }

            if($proposal_id != ''){
                $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/getNotificationByProposalId/$proposal_id";
                $method = 'GET';
                $output = callIlrmsApi($url, $method);
                if(!empty($output) && !empty($output->notification)){
                    $notification = $output->notification;
                    if($notification->reverted != 'Y' && $notification->asst_section_officer_verified == 'Y' && $notification->section_officer_verified == 'Y' && $notification->joint_secretary_verified == 'Y' && $notification->secretary_verified){
                        if($notification->ps_verified == 'Y'){
                            $ps_2_status = 1;
                            $data['ps_action_date'] = $notification->ps_verified_at;$data['ps_2_action_date'] = $notification->ps_verified_at;
                        } else{
                            $ps_2_status = 2;
                        }
                    }
                }
            }
        }
        // ------------------- PS_step2 END --------------------
        
        // ------------------- MINISTER START --------------------
        if($dlr_proposal){
            $proposal_id = '';
            if($nc_village->select_village_on_prop_sign_from_so == 0){
                $proposal_id = $dlr_proposal->id;
            } else{
                if($asst_section_officer_proposal){
                    $proposal_id = $asst_section_officer_proposal->id;
                }
            }

            if($proposal_id != ''){
                $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/getNotificationByProposalId/$proposal_id";
                $method = 'GET';
                $output = callIlrmsApi($url, $method);
                if(!empty($output) && !empty($output->notification)){
                    $notification = $output->notification;
                    if($notification->reverted != 'Y' && $notification->asst_section_officer_verified == 'Y' && $notification->section_officer_verified == 'Y' && $notification->joint_secretary_verified == 'Y' && $notification->secretary_verified && $notification->ps_verified == 'Y'){
                        if($notification->minister_verified == 'Y'){
                            $minister_status = 1;
                            $data['minister_action_date'] = $notification->minister_verified_at;
                        } else{
                            $minister_status = 2;
                        }
                    }
                }
            }
        }
        // ------------------- MINISTER END --------------------
        
        // ------------------- PS_step3 START --------------------
        if($dlr_proposal){
            $proposal_id = '';
            if($nc_village->select_village_on_prop_sign_from_so == 0){
                $proposal_id = $dlr_proposal->id;
            } else{
                if($asst_section_officer_proposal){
                    $proposal_id = $asst_section_officer_proposal->id;
                }
            }

            if($proposal_id != ''){
                $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/getNotificationByProposalId/$proposal_id";
                $method = 'GET';
                $output = callIlrmsApi($url, $method);
                if(!empty($output) && !empty($output->notification)){
                    $notification = $output->notification;
                    if($notification->reverted != 'Y' && $notification->asst_section_officer_verified == 'Y' && $notification->section_officer_verified == 'Y' && $notification->joint_secretary_verified == 'Y' && $notification->secretary_verified && $notification->ps_verified == 'Y' && $notification->minister_verified == 'Y'){
                        if($notification->ps_sign == 'Y'){
                            $ps_3_status = 1;
                            $data['ps_2_action_date'] = $notification->ps_sign_at;
                        } else{
                            $ps_3_status = 2;
                        }
                    }
                }
            }
        }
        // ------------------- PS_step3 END --------------------
        
        // ------------------- JS_step3 START --------------------
        if($dlr_proposal){
            $proposal_id = '';
            if($nc_village->select_village_on_prop_sign_from_so == 0){
                $proposal_id = $dlr_proposal->id;
            } else{
                if($asst_section_officer_proposal){
                    $proposal_id = $asst_section_officer_proposal->id;
                }
            }

            if($proposal_id != ''){
                $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/getNotificationByProposalId/$proposal_id";
                $method = 'GET';
                $output = callIlrmsApi($url, $method);
                if(!empty($output) && !empty($output->notification)){
                    $notification = $output->notification;
                    if($notification->reverted != 'Y' && $notification->asst_section_officer_verified == 'Y' && $notification->section_officer_verified == 'Y' && $notification->joint_secretary_verified == 'Y' && $notification->secretary_verified && $notification->ps_verified == 'Y' && $notification->minister_verified == 'Y' && $notification->ps_sign == 'Y'){
                        if($notification->js_sign == 'Y'){
                            $js_3_status = 1;
                            $data['js_3_action_date'] = $notification->js_sign_at;
                        } else{
                            $js_3_status = 2;
                        }
                    }
                }
            }
        }
        // ------------------- JS_step3 END --------------------

        $data['lm_status'] = $lm_status;
        $data['sk_status'] = $sk_status;
        $data['co_status'] = $co_status;
        $data['dc_status'] = $dc_status;
        $data['dlr_status'] = $dlr_status;
        $data['ps_1_status'] = $ps_1_status;
        $data['secretary_1_status'] = $secretary_1_status;
        $data['js_1_status'] = $js_1_status;
        $data['so_1_status'] = $so_1_status;
        $data['aso_status'] = $aso_status;
        $data['so_2_status'] = $so_2_status;
        $data['js_2_status'] = $js_2_status;
        $data['secretary_2_status'] = $secretary_2_status;
        $data['ps_2_status'] = $ps_2_status;
        $data['minister_status'] = $minister_status;
        $data['ps_3_status'] = $ps_3_status;
        $data['js_3_status'] = $js_3_status;

        return $data;
    }

    public function updateNcVillageCaseType(){
        $dist_code = $this->input->post('dist_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $vill_townprt_code = $this->input->post('vill_townprt_code');
        $vill_uuid = $this->input->post('uuid');
        $case_type = $this->input->post('case_type');

        $this->dbswitch($dist_code);
        $nc_village = $this->db->where('dist_code', $dist_code)
                            ->where('subdiv_code', $subdiv_code)
                            ->where('cir_code', $cir_code)
                            ->where('mouza_pargona_code', $mouza_pargona_code)
                            ->where('lot_no', $lot_no)
                            ->where('vill_townprt_code', $vill_townprt_code)
                            ->where('uuid', $vill_uuid)
                            ->get('nc_villages')->row();

        $this->db->trans_begin();
        try{
            if($nc_village){
                $status = $this->db->where('id', $nc_village->id)->update('nc_villages', ['case_type' => $case_type]);
                if(!$status){
                    throw new Exception('Something went wrong');
                }
            }
        }catch(Exception $e){
            $this->db->trans_rollback();
            return response_json(['data' => [], 'message' => $e->getMessage(), 'status_code' => 400], 400);
        }
        
        $this->db->trans_commit();

        return response_json(['data' => [], 'message' => 'Updated successfully', 'status_code' => 200], 200);
    }

    public function coToLmForwardCaseDelete(){
        die;
        // For kamrup(m), below schema has been executed -- 24
        // CREATE TABLE IF NOT EXISTS public.data_archive_logs
        // (
        //     id bigint NOT NULL DEFAULT nextval('data_archive_logs_id_seq'::regclass),
        //     dist_code character varying(2) COLLATE pg_catalog."default",
        //     subdiv_code character varying(2) COLLATE pg_catalog."default",
        //     cir_code character varying(2) COLLATE pg_catalog."default",
        //     mouza_pargona_code character varying(2) COLLATE pg_catalog."default",
        //     lot_no character varying(2) COLLATE pg_catalog."default",
        //     vill_townprt_code character varying(5) COLLATE pg_catalog."default",
        //     json_data json,
        //     created_at timestamp without time zone,
        //     type character varying(50) COLLATE pg_catalog."default",
        //     
        // );


        $application_no = $this->UtilsModel->cleanPattern($this->input->get('appno'));
        $this->dbswitch(24);
        $nc_village = $this->db->where('application_no', $application_no)->get('nc_villages')->result_array();

        $merge_village_requests = $this->db->where('nc_village_id', $nc_village[0]['id'])->get('merge_village_requests')->result_array();
        
        $nc_village_dags = $this->db->where('application_no', $application_no)->get('nc_village_dags')->result_array();

        $chitha_basic_nc_data = $this->db->where('dist_code', $nc_village[0]['dist_code'])
                                ->where('subdiv_code', $nc_village[0]['subdiv_code'])
                                ->where('cir_code', $nc_village[0]['cir_code'])
                                ->where('mouza_pargona_code', $nc_village[0]['mouza_pargona_code'])
                                ->where('lot_no', $nc_village[0]['lot_no'])
                                ->where('vill_townprt_code', $nc_village[0]['vill_townprt_code'])
                                ->get('chitha_basic_nc')->result_array();

        $nc_village_proposal_data = $this->db->where_in('id', [$nc_village[0]['proposal_id'], $nc_village[0]['dc_proposal_id'], $nc_village[0]['dlr_proposal_id']])
                                ->get('nc_village_proposal')->result_array();

        $new_arr = [];
        if(!empty($nc_village)){
            $new_arr = array_merge($new_arr, array('nc_villages' => $nc_village));
        }
        if(!empty($nc_village_dags) && !empty($new_arr)){
            $new_arr = array_merge($new_arr, array('nc_village_dags' => $nc_village_dags));
        }
        if(!empty($merge_village_requests) && !empty($new_arr)){
            $new_arr = array_merge($new_arr, array('merge_village_requests' => $merge_village_requests));
        }
        if(!empty($chitha_basic_nc_data) && !empty($new_arr)){
            $new_arr = array_merge($new_arr, array('chitha_basic_nc' => $chitha_basic_nc_data));
        }
        if(!empty($nc_village_proposal_data) && !empty($new_arr)){
            $new_arr = array_merge($new_arr, array('nc_village_proposal' => $nc_village_proposal_data));
        }
        // echo "<pre/>";
        // print_r(json_encode($new_arr, true));
        // exit;
        $this->db->trans_begin();
        try{
            $data = [
                'dist_code' => $nc_village[0]['dist_code'],
                'subdiv_code' => $nc_village[0]['subdiv_code'],
                'cir_code' => $nc_village[0]['cir_code'],
                'mouza_pargona_code' => $nc_village[0]['mouza_pargona_code'],
                'lot_no' => $nc_village[0]['lot_no'],
                'vill_townprt_code' => $nc_village[0]['vill_townprt_code'],
                'json_data' => json_encode($new_arr, true),
                'created_at' => date('Y-m-d H:i:s'),
                'type' => "LM Case",
            ];

            $this->db->insert('data_archive_logs', $data);

            if($this->db->affected_rows() != 1){
                log_message('error', '#ERRPULLBACKUPDATE0001: ' . $this->db->last_query());
                throw new Exception('#ERRPULLBACKUPDATE0001: Something went wrong.');
            }

            $this->db->where('application_no', $application_no)->delete('nc_villages');
            $this->db->where('application_no', $application_no)->delete('nc_village_dags');

            $this->db
            ->where('dist_code', $nc_village[0]['dist_code'])
            ->where('subdiv_code', $nc_village[0]['subdiv_code'])
            ->where('cir_code', $nc_village[0]['cir_code'])
            ->where('mouza_pargona_code', $nc_village[0]['mouza_pargona_code'])
            ->where('lot_no', $nc_village[0]['lot_no'])
            ->where('vill_townprt_code', $nc_village[0]['vill_townprt_code'])
            ->delete('chitha_basic_nc');

            $this->db->where_in('id', [$nc_village[0]['proposal_id'], $nc_village[0]['dc_proposal_id'], $nc_village[0]['dlr_proposal_id']])->delete('nc_village_proposal');

            $this->db->where('nc_village_id', $nc_village[0]['id'])->delete('merge_village_requests');

            // $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/updateFlag";
            // $method = 'POST';
            // $filter_data = ['map_id' => 332, 'flag' => 'B', 'co_user_code' => 'CO13'];
            // $output = callIlrmsApi($url, $method, $filter_data);

        } catch(Exception $e){
            $this->db->trans_rollback();
            return response_json(['data' => [], 'message' => $e->getMessage(), 'status_code' => 400], 400);
        }

        $this->db->trans_commit();
        return response_json(['message' => 'Data archive has been done successfully', 'status_code' => 200]);
    }

    public function fetchUserFromDharitree123(){
        $url = LANDHUB_BASE_URL . "majuli_fetch";

        $dist_code = '34'; // Majuli
        $users = $loginuser_tables = $lm_codes = [];
        $method = 'POST';
        $data = ['dist_code' => $dist_code, 'table_name' => 'users'];
        $output = callIlrmsApi($url, $method, $data);
        if(!empty($output)){
            $users = json_decode(json_encode($output), true);
        }
        $data = ['dist_code' => $dist_code, 'table_name' => 'loginuser_table'];
        $output = callIlrmsApi($url, $method, $data);
        if(!empty($output)){
            $loginuser_tables = json_decode(json_encode($output), true);
        }
        $data = ['dist_code' => $dist_code, 'table_name' => 'lm_code'];
        $output = callIlrmsApi($url, $method, $data);
        if(!empty($output)){
            $lm_codes = json_decode(json_encode($output), true);
        }
        echo "<pre/>";
        print_r($loginuser_tables);
        die;
        $this->dbSwitch($dist_code);

        if(count($loginuser_tables)){
            $this->db->trans_begin();
            try{
                foreach($loginuser_tables as $loginuser_table){
                    $user_code = $loginuser_table['user_code'];
                    $lot_no = $loginuser_table['lot_no'];
                    $lgu_data = [
                                    'use_name' => $loginuser_table['use_name'],
                                    'user_code' => $loginuser_table['user_code'],
                                    'date_of_creation' => $loginuser_table['date_of_creation'],
                                    'dis_enb_option' => $loginuser_table['dis_enb_option'],
                                    'first_login' => $loginuser_table['first_login'],
                                    'activity' => $loginuser_table['activity'],
                                    'date_password_changed' => $loginuser_table['date_password_changed'],
                                    'dist_code' => $loginuser_table['dist_code'],
                                    'subdiv_code' => $loginuser_table['subdiv_code'],
                                    'cir_code' => $loginuser_table['cir_code'],
                                    'mouza_pargona_code' => $loginuser_table['mouza_pargona_code'],
                                    'lot_no' => $loginuser_table['lot_no'],
                                    'password' => $loginuser_table['password'],
                                    'prev_password1' => $loginuser_table['prev_password1'],
                                    'prev_password2' => $loginuser_table['prev_password2'],
                                    // 'noc_user' => $loginuser_table['noc_user'],
                                    'user_map' => $loginuser_table['user_map'],
                                    'special_status' => $loginuser_table['special_status'],
                                    'password_change_flag' => $loginuser_table['password_change_flag'],
                                    // 'prev_password3' => $loginuser_table['prev_password3'],
                                    'permission_allowed' => $loginuser_table['permission_allowed'],
                                    'parent_code' => $loginuser_table['parent_code'],
                                ];
                    // if(!empty($lot_no) && str_starts_with($user_code, 'M')){
                    $check_local_user = $this->db->where([
                                                            'dist_code' => $loginuser_table['dist_code'],
                                                            'subdiv_code' => $loginuser_table['subdiv_code'],
                                                            'cir_code' => $loginuser_table['cir_code'],
                                                            'mouza_pargona_code' => $loginuser_table['mouza_pargona_code'],
                                                            'lot_no' => $loginuser_table['lot_no'],
                                                            'use_name' => $loginuser_table['use_name'],
                                                            'user_code' => $loginuser_table['user_code'],
                                                        ])->get('loginuser_table')->row_array();

                    // if(str_starts_with($user_code, 'M')){
                    if(substr($user_code, 0, 1)=== 'M'){
                        // LM block
                        if(empty($check_local_user)){
                            $lm_data = [];
                            foreach($lm_codes as $lm_code){
                                if(
                                    $lm_code['dist_code'] == $loginuser_table['dist_code'] &&
                                    $lm_code['subdiv_code'] == $loginuser_table['subdiv_code'] &&
                                    $lm_code['cir_code'] == $loginuser_table['cir_code'] &&
                                    $lm_code['mouza_pargona_code'] == $loginuser_table['mouza_pargona_code'] &&
                                    $lm_code['lot_no'] == $loginuser_table['lot_no'] &&
                                    $lm_code['lm_code'] == $loginuser_table['user_code']
                                ){
                                    $lm_data = [
                                                    'dist_code' => $lm_code['dist_code'],
                                                    'subdiv_code' => $lm_code['subdiv_code'],
                                                    'cir_code' => $lm_code['cir_code'],
                                                    'mouza_pargona_code' => $lm_code['mouza_pargona_code'],
                                                    'lot_no' => $lm_code['lot_no'],
                                                    'lm_name' => $lm_code['lm_name'],
                                                    'lm_code' => $lm_code['lm_code'],
                                                    'status' => $lm_code['status'],
                                                    'corres_sk_code' => $lm_code['corres_sk_code'],
                                                    'dt_from' => $lm_code['dt_from'],
                                                    'dt_to' => $lm_code['dt_to'],
                                                    'lm_thumb_imp' => $lm_code['lm_thumb_imp'],
                                                    'lm_sign1' => $lm_code['lm_sign1'],
                                                    'lm_sign2' => $lm_code['lm_sign2'],
                                                    'lm_sign3' => $lm_code['lm_sign3'],
                                                    'phone_no' => $lm_code['phone_no'],
                                                    //'lm_user' => $lm_code['lmuser'],
                                                ];
                                    break;
                                }
                            }

                            if(count($lm_data)){
                                $loc_lm_code = $this->db->where([
                                                                    'dist_code' => $lm_data['dist_code'],
                                                                    'subdiv_code' => $lm_data['subdiv_code'],
                                                                    'cir_code' => $lm_data['cir_code'],
                                                                    'mouza_pargona_code' => $lm_data['mouza_pargona_code'],
                                                                    'lot_no' => $lm_data['lot_no'],
                                                                    'lm_code' => $lm_data['lm_code'],
                                                                ])->get('lm_code')->row_array();
                                if(empty($loc_lm_code)){
                                    $this->db->insert('loginuser_table', $lgu_data);
                                    $this->db->insert('lm_code', $lm_data);
                                    if($this->db->affected_rows() < 1){
                                        $this->db->trans_rollback();
                                        echo $this->db->last_query();
                                        return;
                                    }
                                }
                            }

                            // if($this->db->affected_row() < 1){
                            //     $this->db->trans_rollback();
                            //     echo $this->db->last_query();
                            //     return;
                            // }

                        }
                    }else{
                        // SK, CO, DC Block
                        if(empty($check_local_user)){
                            $user_data = [];
                            foreach($users as $user_row){
                                if(
                                    $user_row['dist_code'] == $loginuser_table['dist_code'] &&
                                    $user_row['subdiv_code'] == $loginuser_table['subdiv_code'] &&
                                    $user_row['cir_code'] == $loginuser_table['cir_code'] &&
                                    $user_row['user_code'] == $loginuser_table['user_code']
                                ){
                                    $user_data = [
                                                    'dist_code' => $user_row['dist_code'],
                                                    'subdiv_code' => $user_row['subdiv_code'],
                                                    'cir_code' => $user_row['cir_code'],
                                                    'username' => $user_row['username'],
                                                    'user_code' => $user_row['user_code'],
                                                    'user_desig_code' => $user_row['user_desig_code'],
                                                    'status' => $user_row['status'],
                                                    'date_from' => $user_row['date_from'],
                                                    'date_to' => $user_row['date_to'],
                                                    'user_thumb_imp' => $user_row['user_thumb_imp'],
                                                    'user_sign1' => $user_row['user_sign1'],
                                                    'user_sign2' => $user_row['user_sign2'],
                                                    'user_sign3' => $user_row['user_sign3'],
                                                    // 'phone_number' => $user_row['phone_number'],
                                                    'usernm' => $user_row['usernm'],
                                                    'phone_no' => $user_row['phone_no'],
                                                    'emailid' => $user_row['emailid'],
                                                    'priority' => $user_row['priority'],
                                                    'display_name' => $user_row['display_name'],
                                                    // 'aadhar_no' => $user_row['aadhar_no'],
                                                ];
                                    break;
                                }
                            }

                            if(count($user_data)){
                                $loc_user = $this->db->where([
                                                                'dist_code' => $user_data['dist_code'],
                                                                'subdiv_code' => $user_data['subdiv_code'],
                                                                'cir_code' => $user_data['cir_code'],
                                                                'username' => $user_data['username'],
                                                                'user_code' => $user_data['user_code'],
                                                            ])->get('users')->row_array();
                                if(empty($loc_user)){
                                    $this->db->insert('loginuser_table', $lgu_data);
                                    $this->db->insert('users', $user_data);
                                    if($this->db->affected_rows() < 1){
                                        $this->db->trans_rollback();
                                        echo $this->db->last_query();
                                        return;
                                    }
                                }
                            }
                        }
                    }
                }
            }catch(Exception $e){
                $this->db->trans_rollback();
                echo $e->getMessage();
                exit;
            }

            $this->db->trans_commit();
            echo 'Done';
        }
    }

    public function multiSqlRun(){
        // die;
        // 05 => barpeta, '13' => Bongaigaon, '23' => cachar, '10' => Chirang
        $arr = ['26', '05', '35', '13', '23', '37', '10', '08', '25', '02', '17', '03', '14', '22', '36', '15', '07', '24', '21', '01', '12', '34', '32', '33', '06', '16', '11'];
        foreach($arr as $value){
            $this->dbswitch($value);
        
            try{
                /*
                $sql1 = "-- Create audit schema if not exists
                            CREATE SCHEMA IF NOT EXISTS deletes;

                            -- Create delete_log table
                            CREATE TABLE IF NOT EXISTS deletes.location_updates AS
                            SELECT *, now() AS deleted_at FROM public.location WHERE 1=0;

                            CREATE SCHEMA IF NOT EXISTS updates;
                            -- Create update_log table
                            CREATE TABLE IF NOT EXISTS updates.location_updates AS
                            SELECT *, now() AS updated_at FROM public.location WHERE 1=0;

                            ---------------------

                            CREATE OR REPLACE FUNCTION public.log_location_changes()
                            RETURNS TRIGGER AS $$
                            BEGIN
                                IF (TG_OP = 'DELETE') THEN
                                    INSERT INTO deletes.location_updates
                                    SELECT OLD.*, now();
                                    RETURN OLD;

                                ELSIF (TG_OP = 'UPDATE') THEN
                                    INSERT INTO updates.location_updates
                                    SELECT OLD.*, now();
                                    RETURN NEW;
                                END IF;

                                RETURN NULL;
                            END;
                            $$ LANGUAGE plpgsql;

                            ----------------------

                            DROP TRIGGER IF EXISTS trg_location ON public.location;

                            CREATE TRIGGER trg_location
                            AFTER UPDATE OR DELETE ON public.location
                            FOR EACH ROW
                            EXECUTE FUNCTION public.log_location_changes();
                        ";
                */
                // $sql1 = "alter table nc_villages add column case_type varchar(50)";
                // $sql1 = "alter table change_vill_name add column date_of_update_so timestamp without time zone;
                //     alter table change_vill_name add column so_verified varchar(1);
                //     alter table change_vill_name add column so_user_code varchar(20);";
                // $sql1 = "alter table pullback_nc_village_cases add column so_proposal_data text";
                /*
                $sql1 = "alter table nc_villages add column select_village_on_prop_sign_from_so smallint default 0;
                    alter table nc_villages add column section_officer_verified varchar(10);
                    alter table nc_villages add column section_officer_proposal varchar(10);
                    alter table nc_villages add column section_officer_verified_at varchar(100);
                    alter table nc_villages add column section_officer_user_code varchar(100);
                    alter table nc_villages add column section_officer varchar(100);
                    alter table nc_villages add column section_officer_note text;
                    alter table nc_villages add column section_officer_proposal_id bigint;

                    alter table nc_villages add column asst_section_officer_verified varchar(10);
                    alter table nc_villages add column asst_section_officer_verified_at varchar(100);
                    alter table nc_villages add column asst_section_officer_user_code varchar(100);
                    alter table nc_villages add column asst_section_officer varchar(100);
                    alter table nc_villages add column asst_section_officer_note text;
                    alter table nc_villages add column asst_section_officer_proposal_id bigint;
                    alter table nc_villages add column asst_section_officer_proposal varchar(10);

                    alter table nc_village_proposal add column section_officer_proposal_note text;
                    alter table nc_village_proposal add column asst_section_officer_proposal_note text;";
                */
                // $sql1 = "CREATE TABLE IF NOT EXISTS public.test01
                // (
                //     id bigserial,
                //     dist_code character varying(2)
                // );
                // CREATE TABLE IF NOT EXISTS public.test02
                // (
                //     id bigserial,
                //     dist_code character varying(2)
                // );

                // DO $$ 
                // BEGIN 
                //     IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_name = 'test01' AND column_name = 'section_officer_proposal_note') THEN 
                //         ALTER TABLE test01 ADD COLUMN section_officer_proposal_note TEXT;
                //     END IF;
                // END $$;
                
                // ";
            
                $this->db->query($sql1);
                echo "Success, ";
            } catch(Exception $e){
                return response_json(['data' => [], 'message' => $e->getMessage(), 'status_code' => 400], 400);
            }
        }
    }

    public function jsSignPendingCount(){
        $tot_js_pending_cnt = 0;
        foreach (json_decode(NC_DISTIRTCS) as $dist_code) {
            $this->dbswitch($dist_code);
                $dist_pending_cnt = 0;
            $nc_villages = $this->db->where('dc_proposal_id is NOT NULL', NULL, FALSE)
                        ->where('dlr_proposal_id is NOT NULL', NULL, FALSE)->where('status <>', 'P')
                        ->get('nc_villages')->result_array();
            if(!empty($nc_villages)){
               //  $dist_pending_cnt = 0;
                foreach($nc_villages as $nc_village){
                    $proposal_id = '';
                    if($nc_village['asst_section_officer_proposal_id'] != NULL){
                        $proposal_id = $nc_village['asst_section_officer_proposal_id'];
                    }
                    if($nc_village['dlr_proposal_id']!= NULL && $proposal_id == ''){
                        $proposal_id = $nc_village['dlr_proposal_id'];
                    }

                    if($proposal_id == ''){
                       // $tot_js_pending_cnt++;
                        $dist_pending_cnt++;
                    } else{
                        $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/getNotificationByProposalId/$proposal_id";
                        $method = 'GET';
                        $output = callIlrmsApi($url, $method);
                       // echo "OUTPUT" . $output->notification . "<br>";
                        if($output->notification == 0){
                          //  $tot_js_pending_cnt++;
                            $dist_pending_cnt++;
                        }
                    }

                }
                $tot_js_pending_cnt+= $dist_pending_cnt;
            }
            echo "<b>Total District(".$dist_code.") Pending: ".$dist_pending_cnt."</b><br/>";
           // echo "Total Pending: ".$tot_js_pending_cnt . "<br>";
            // echo "<pre/>";
            // print_r($nc_villages);
        }
        echo "Total Pending: ".$tot_js_pending_cnt . "<br>";

    }


    public function getNCVillageV2Status($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $vill_uuid, $check_desig, $process_type) {
        $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/getNCVillageStatus";
        $method = 'POST';
        $data = [
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'mouza_pargona_code' => $mouza_pargona_code,
            'lot_no' => $lot_no,
            'vill_townprt_code' => $vill_townprt_code,
            'vill_uuid' => $vill_uuid,
            'check_desig' => $check_desig,
            'process_type' => $process_type
        ];
        $output = callIlrmsApi($url, $method, $data);
        if(empty($output) || empty($output->data)) {
            return (object) [
                'status' => 'FAILED',
                'responseType' => 1,
                'msg' => 'API Error',
                'data' => ''
            ];
        }
        
        return $output;
    }


    private function villageWiseTrackingNew($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $vill_uuid){
        /*
        0 => Case has not been reached on this user
        1 => Case has been passed from this user end, 
        2 => Case is pending on this user end (Fresh Case or Revert Case), 
        */
        $this->dbswitch($dist_code);
        $co_status = $lm_status = $sk_status = $dc_status = 0;
        $dlr_status = $ps_1_status = $secretary_1_status = $js_1_status = $so_1_status = 0;
        $aso_status = $so_2_status = $js_2_status = $secretary_2_status = $ps_2_status = 0;
        $minister_status = $ps_3_status = $js_3_status = 0;

        $data['dlr_proposal_date'] = '';
        $data['ps_1_action_date'] = '';
        $data['secretary_1_action_date'] = '';
        $data['js_1_action_date'] = '';
        $data['so_1_action_date'] = '';
        $data['aso_1_action_date'] = '';
        $data['so_2_action_date'] = '';
        $data['js_2_action_date'] = '';
        $data['sec_2_action_date'] = '';
        $data['ps_action_date'] = '';
        $data['minister_action_date'] = '';
        $data['ps_2_action_date'] = '';
        $data['js_3_action_date'] = '';

        $nc_village = $this->db->where('dist_code', $dist_code)->where('subdiv_code', $subdiv_code)->where('cir_code', $cir_code)->where('mouza_pargona_code', $mouza_pargona_code)->where('lot_no', $lot_no)->where('vill_townprt_code', $vill_townprt_code)->get('nc_villages')->row();
        
        $dlr_proposal = $this->db->where('id', $nc_village->dlr_proposal_id)->get('nc_village_proposal')->row();
        $section_officer_proposal = $this->db->where('id', $nc_village->section_officer_proposal_id)->get('nc_village_proposal')->row();
        $asst_section_officer_proposal = $this->db->where('id', $nc_village->asst_section_officer_proposal_id)->get('nc_village_proposal')->row();

        $get_map_url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/apiGetMapByLocation";
        $method = 'POST';
        $post_data = [
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'mouza_pargona_code' => $mouza_pargona_code,
            'lot_no' => $lot_no,
            'vill_townprt_code' => $vill_townprt_code,
            'vill_uuid' => $vill_uuid
        ];
        $map_output = callIlrmsApi($get_map_url, $method, $post_data);
        $map = null;
        if(!empty($map_output) && !empty($map_output->data) && $map_output->status_code == 200){
            $map = $map_output->data;
        }

        // ------------------- LM CASE START ------------------- 
        if($map){
            if($map->flag == 'B'){
                $lm_status = 2;
            }else if($nc_village){
                if($nc_village->status == 'U' && $nc_village->pre_user == 'SK' && $nc_village->cu_user == 'LM'){
                    // Reverted
                    $lm_status = 2;
                }else{
                    $lm_status = 1;
                }
            }
        }
        // ------------------- LM CASE END ------------------- 
        
        // ------------------- SK CASE START ------------------- 
        if($nc_village){
            if($nc_village->status == 'S'){
                $sk_status = 2;
            }else if($nc_village->status == 'H' && $nc_village->pre_user == 'CO' && $nc_village->cu_user == 'SK'){
                // Reverted
                $sk_status = 2;
            }else{
                $sk_status = 1;
            }
        }
        // ------------------- SK CASE END ------------------- 

        // ------------------- CO CASE START ------------------- 
        if($map){
            if($map->flag == 'F'){
                $co_status = 2;
            }else{
                if($nc_village){
                    if($nc_village->status == 'T'){
                        $co_status = 2;
                    }else if($nc_village->status == 'J' && $nc_village->pre_user == 'DC' && $nc_village->cu_user == 'CO'){
                        // Reverted
                        $co_status = 2;
                    }else if($nc_village->status == 'O' && $nc_village->co_proposal == NULL){
                        $co_status = 2;
                    }elseif(!$nc_village->co_proposal){
                        $co_status = 1;
                    }
                }
            }
        }
        // ------------------- CO CASE END ------------------- 

        // ------------------- DC CASE START ------------------- 
        if($nc_village){
            if($nc_village->status == 'G'){
                $dc_status = 2;
            }else if($nc_village->status == 'K'){
                $dc_status = 2;
            }else if($nc_village->status == 'B'){
                // Reverted
                $dc_status = 2;
            }else if($nc_village->dc_proposal != NULL){
                $dc_status = 1;
            }
        }
        // ------------------- DC CASE END ------------------- 

        // ------------------- DLR CASE START --------------------
        if(in_array($nc_village->status, ['I', 'L'])){
            $dlr_status = 2;
        }else{
            if($nc_village->dlr_proposal == 'Y'){
                if($dlr_proposal->ps_verified == NULL && $dlr_proposal->reverted == NULL){
                    $dlr_status = 1;
                    $data['dlr_proposal_date'] = $nc_village->dlr_verified_at;
                }elseif($dlr_proposal->ps_verified == 'Y' && $dlr_proposal->reverted == NULL){
                    $dlr_status = 1;
                    $data['dlr_proposal_date'] = $nc_village->dlr_verified_at;
                }
                // elseif($dlr_proposal->ps_verified == 'Y'){
                //     $dlr_status = 1;
                //     $data['dlr_proposal_date'] = $nc_village->dlr_verified_at;
                // }
            }
            if($nc_village->status == 'e'){
                // Revert in New Flow
                $dlr_status = 2;
            }else{
                if($dlr_proposal && $nc_village->select_village_on_prop_sign_from_so == 0 && $dlr_proposal->ps_verified == NULL && $dlr_proposal->reverted == 'Y'){
                    // Revert in OLD Flow
                    // $dlr_status = 2;
                    if($nc_village->status == 'P'){
                        // DLR revert to JDS
                        $dlr_status = 0;
                    }elseif($nc_village->status == 'N'){
                        // DLR reverted to JDS [JDS forwarded to ADS]
                        $dlr_status = 0;
                    }else{
                        $dlr_status = 2;
                    }
                }
            }
        }
        // ------------------- DLR CASE END --------------------

        // ------------------- PS_step1 START --------------------
        if($map && $map->app_version == 'V1') {
            if($dlr_proposal){
                if(!$dlr_proposal->forwarded_to){
                    if($dlr_proposal->ps_verified == 'Y' && $dlr_proposal->reverted == NULL){
                        $ps_1_status = 1;
                        $data['ps_1_action_date'] = $dlr_proposal->ps_verified_at;
                    }else{
                        // reverted + pending case
                        if($dlr_proposal->ps_verified == NULL && $dlr_proposal->reverted == 'Y'){
                            // Reverted
                            $ps_1_status = 0;
                        }else{
                            $ps_1_status = 2;
                        }
                    }
                }
            }
        }
        else {
            $nc_villages_v2 = $this->getNCVillageV2Status($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $vill_uuid, DPT_PS, 'PROPOSAL');

            if($nc_villages_v2->status == 'FAILED' || $nc_villages_v2->responseType == 1) {
                $ps_1_status = 0;
            }
            else {
                $ps_1_status = $nc_villages_v2->data;
            }
        }

        
        
        // ------------------- PS_step1 END --------------------

        // ------------------- SECRETARY_step1 START --------------------
        if($map && $map->app_version == 'V1') {
            if($dlr_proposal){
                if(!$dlr_proposal->forwarded_to){
                    if($dlr_proposal->ps_verified == 'Y' && $dlr_proposal->ps_forwarded_to_sec && $dlr_proposal->status == 'A'){
                        if($dlr_proposal->reverted == NULL && $dlr_proposal->secretary_verified == 'Y'){
                            $secretary_1_status = 1;
                            $data['secretary_1_action_date'] = $dlr_proposal->secretary_verified_at;
                        }else{
                            if($dlr_proposal->reverted == 'Y' && $dlr_proposal->secretary_verified == 'Y' && $dlr_proposal->joint_secretary_verified == NULL){
                                $secretary_1_status = 2;
                            }elseif($dlr_proposal->reverted == NULL && $dlr_proposal->secretary_verified == NULL){
                                $secretary_1_status = 2;
                            }
                        }
                    }
                }
            }
        }
        else {
            $nc_villages_v2 = $this->getNCVillageV2Status($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $vill_uuid, DPT_SEC, 'PROPOSAL');

            if($nc_villages_v2->status == 'FAILED' || $nc_villages_v2->responseType == 1) {
                $secretary_1_status = 0;
            }
            else {
                $secretary_1_status = $nc_villages_v2->data;
            }
        }
        
        // ------------------- SECRETARY_step1 END --------------------
        
        // ------------------- JS_step1 START --------------------
        if($map && $map->app_version == 'V1') {
            if($dlr_proposal){
                if(!$dlr_proposal->forwarded_to){
                    if($dlr_proposal->ps_verified == 'Y' && $dlr_proposal->secretary_verified == 'Y' && $dlr_proposal->sec_forwarded_to_jsec && $dlr_proposal->status == 'A'){
                        if($dlr_proposal->reverted == NULL && $dlr_proposal->joint_secretary_verified == 'Y'){
                            $js_1_status = 1;
                            $data['js_1_action_date'] = $dlr_proposal->joint_secretary_verified_at;
                        }else{
                            if($dlr_proposal->reverted == 'Y' && $dlr_proposal->secretary_verified == 'Y' && $dlr_proposal->joint_secretary_verified == NULL){
                                $js_1_status = 2;
                            }elseif($dlr_proposal->reverted == NULL && $dlr_proposal->ps_verified == 'Y' && $dlr_proposal->secretary_verified == 'Y' && $dlr_proposal->joint_secretary_verified == NULL){
                                $js_1_status = 2;
                            }
                        }
                    }
                }
            }
        }
        else {
            $nc_villages_v2 = $this->getNCVillageV2Status($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $vill_uuid, DPT_JSEC, 'PROPOSAL');

            if($nc_villages_v2->status == 'FAILED' || $nc_villages_v2->responseType == 1) {
                $js_1_status = 0;
            }
            else {
                $js_1_status = $nc_villages_v2->data;
            }
        }
        
        // ------------------- JS_step1 END --------------------
        
        // ------------------- SO_step1 START --------------------
        if($map && $map->app_version == 'V1') {
            if($dlr_proposal){
                if(!$dlr_proposal->forwarded_to){
                    // Old Process
                    if($dlr_proposal->section_officer_verified == 'Y' && $dlr_proposal->reverted == NULL){
                        $so_1_status = 1;
                        $data['so_1_action_date'] = $dlr_proposal->section_officer_verified_at;
                    }else{
                        // reverted + pending case
                        if($dlr_proposal->secretary_verified == 'Y' && $dlr_proposal->joint_secretary_verified == 'Y'){
                            if($dlr_proposal->section_officer_verified == NULL){
                                // $so_1_status = 2;
                                if($nc_village->status == 'e'){
                                    // reverted in new flow
                                    // Section Officer Reverted village to DLR
                                }else{
                                    if($section_officer_proposal){
                                        // In the new flow SO has made new proposal
                                        $so_1_status = 1;
                                    }else{
                                        $so_1_status = 2;
                                    }
                                }
                            }
                            if($dlr_proposal->section_officer_verified == 'Y' && $dlr_proposal->reverted == 'Y'){
                                $so_1_status = 2;
                            }
                        }
                    }
                }else{
                    // Skip Module (proposal forward + village forward)
                    // New Process
                    if($dlr_proposal->section_officer_verified == NULL && in_array($nc_village->status, ['A','a'])){
                        $so_1_status = 2;
                    }else{
                        if($nc_village->select_village_on_prop_sign_from_so == 0){
                            // if($dlr_proposal->section_officer_verified == 'Y'){
                            //     $so_1_status = 1;
                            //     $data['so_1_action_date'] = $dlr_proposal->section_officer_verified_at;
                            // }
                            if($dlr_proposal->section_officer_verified == 'Y' && $dlr_proposal->reverted == NULL){
                                $so_1_status = 1;
                                $data['so_1_action_date'] = $dlr_proposal->section_officer_verified_at;
                            }
                        }else{
                            if($section_officer_proposal && $nc_village->section_officer_verified == 'Y'){
                                $so_1_status = 1;
                                $data['so_1_action_date'] = $nc_village->section_officer_verified_at;
                            }
                        }
                    }

                    // Revert is pending
                }
            }
        }
        else {
            $nc_villages_v2 = $this->getNCVillageV2Status($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $vill_uuid, DPT_SO, 'PROPOSAL');

            if($nc_villages_v2->status == 'FAILED' || $nc_villages_v2->responseType == 1) {
                $so_1_status = 0;
            }
            else {
                $so_1_status = $nc_villages_v2->data;
            }
        }
        // ------------------- SO_step1 END --------------------
        
        // ------------------- ASO START --------------------
        if($map && $map->app_version == 'V1') {
            if($dlr_proposal){
                if($nc_village->select_village_on_prop_sign_from_so == 0){
                    // Proposal Forward Process (Old Process)
                    if($dlr_proposal->so_forwarded_to_aso){
                        if($dlr_proposal->forwarded_to){
                            // if($dlr_proposal->section_officer_verified == 'Y' && $dlr_proposal->asst_section_officer_verified == NULL){
                            if($dlr_proposal->section_officer_verified == 'Y' && $dlr_proposal->asst_section_officer_verified == NULL && $dlr_proposal->reverted == NULL){
                                $aso_status = 2;
                            }elseif($dlr_proposal->section_officer_verified == 'Y' && $dlr_proposal->asst_section_officer_verified == 'Y'){
                                $proposal_id = $dlr_proposal->id;
                                $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/getNotificationByProposalId/$proposal_id";
                                $method = 'GET';
                                $output = callIlrmsApi($url, $method);
                                if(!empty($output) && !empty($output->notification)){
                                    $notification = $output->notification;
                                    if($notification->reverted != 'Y' && $notification->asst_section_officer_verified == 'Y'){
                                        $aso_status = 1;
                                        $data['aso_1_action_date'] = $notification->asst_section_officer_verified_at;
                                    } else{
                                        $aso_status = 2;
                                    }
                                }
                            }
                        }else{
                            if($dlr_proposal->secretary_verified == 'Y' && $dlr_proposal->joint_secretary_verified == 'Y' && $dlr_proposal->section_officer_verified == 'Y' && $dlr_proposal->asst_section_officer_verified == NULL){
                                $aso_status = 2;
                            }elseif($dlr_proposal->secretary_verified == 'Y' && $dlr_proposal->joint_secretary_verified == 'Y' && $dlr_proposal->section_officer_verified == 'Y' && $dlr_proposal->asst_section_officer_verified == 'Y'){
                                $proposal_id = $dlr_proposal->id;
                                $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/getNotificationByProposalId/$proposal_id";
                                $method = 'GET';
                                $output = callIlrmsApi($url, $method);
                                if(!empty($output) && !empty($output->notification)){
                                    $notification = $output->notification;
                                    if($notification->reverted != 'Y' && $notification->asst_section_officer_verified == 'Y'){
                                        $aso_status = 1;
                                        $data['aso_1_action_date'] = $notification->asst_section_officer_verified_at;
                                    } else{
                                        $aso_status = 2;
                                    }
                                }
                            }
                        }
                    }
                }else{
                    // Village Forward Process (New Process)
                    if($section_officer_proposal && $nc_village->section_officer_verified == 'Y'){
                        if($asst_section_officer_proposal){
                            $proposal_id = $asst_section_officer_proposal->id;
                            $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/getNotificationByProposalId/$proposal_id";
                            $method = 'GET';
                            $output = callIlrmsApi($url, $method);
                            if(!empty($output) && !empty($output->notification)){
                                $notification = $output->notification;
                                if($notification->reverted != 'Y' && $notification->asst_section_officer_verified == 'Y'){
                                    $aso_status = 1;
                                    $data['aso_1_action_date'] = $notification->asst_section_officer_verified_at;
                                } else{
                                    $aso_status = 2;
                                }
                            }
                        }else{
                            $aso_status = 2;
                        }
                    }
                }
            }
        }
        else {
            $nc_villages_v2 = $this->getNCVillageV2Status($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $vill_uuid, DPT_ASO, 'ALL');

            if($nc_villages_v2->status == 'FAILED' || $nc_villages_v2->responseType == 1) {
                $aso_status = 0;
            }
            else {
                $aso_status = $nc_villages_v2->data;
            }
        }
        
        // ------------------- ASO END --------------------
        
        // ------------------- SO_step2 START --------------------
        if($map && $map->app_version == 'V1') {
            if($dlr_proposal){
                $proposal_id = '';
                if($nc_village->select_village_on_prop_sign_from_so == 0){
                    $proposal_id = $dlr_proposal->id;
                } else{
                    if($asst_section_officer_proposal){
                        $proposal_id = $asst_section_officer_proposal->id;
                    }
                }
                
                if($proposal_id != ''){
                    $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/getNotificationByProposalId/$proposal_id";
                    $method = 'GET';
                    $output = callIlrmsApi($url, $method);
                    if(!empty($output) && !empty($output->notification)){
                        $notification = $output->notification;
                        if($notification->reverted != 'Y' && $notification->asst_section_officer_verified == 'Y'){
                            if($notification->section_officer_verified == 'Y'){
                                $so_2_status = 1;
                                $data['so_2_action_date'] = $notification->section_officer_verified_at;
                            } else{
                                $so_2_status = 2;
                            }
                        }
                    }
                }
            }
        }
        else {
            $nc_villages_v2 = $this->getNCVillageV2Status($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $vill_uuid, DPT_SO, 'NOTIFICATION');

            if($nc_villages_v2->status == 'FAILED' || $nc_villages_v2->responseType == 1) {
                $so_2_status = 0;
            }
            else {
                $so_2_status = $nc_villages_v2->data;
            }
        }
        // ------------------- SO_step2 END --------------------
        
        // ------------------- JS_step2 START --------------------
        if($map && $map->app_version == 'V1') {
            if($dlr_proposal){
                $proposal_id = '';
                if($nc_village->select_village_on_prop_sign_from_so == 0){
                    $proposal_id = $dlr_proposal->id;
                } else{
                    if($asst_section_officer_proposal){
                        $proposal_id = $asst_section_officer_proposal->id;
                    }
                }

                if($proposal_id != ''){
                    $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/getNotificationByProposalId/$proposal_id";
                    $method = 'GET';
                    $output = callIlrmsApi($url, $method);
                    if(!empty($output) && !empty($output->notification)){
                        $notification = $output->notification;
                        if($notification->reverted != 'Y' && $notification->asst_section_officer_verified == 'Y' && $notification->section_officer_verified == 'Y'){
                            if($notification->joint_secretary_verified == 'Y'){
                                $js_2_status = 1;
                                $data['js_2_action_date'] = $notification->joint_secretary_verified_at;
                            } else{
                                $js_2_status = 2;
                            }
                        }
                    }
                }
            }
        }
        else {
            $nc_villages_v2 = $this->getNCVillageV2Status($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $vill_uuid, DPT_JSEC, 'NOTIFICATION');

            if($nc_villages_v2->status == 'FAILED' || $nc_villages_v2->responseType == 1) {
                $js_2_status = 0;
            }
            else {
                $js_2_status = $nc_villages_v2->data;
            }
        }
        // ------------------- JS_step2 END --------------------
        
        // ------------------- SECRETARY_step2 START --------------------
        if($map && $map->app_version == 'V1') {
            if($dlr_proposal){
                $proposal_id = '';
                if($nc_village->select_village_on_prop_sign_from_so == 0){
                    $proposal_id = $dlr_proposal->id;
                } else{
                    if($asst_section_officer_proposal){
                        $proposal_id = $asst_section_officer_proposal->id;
                    }
                }

                if($proposal_id != ''){
                    $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/getNotificationByProposalId/$proposal_id";
                    $method = 'GET';
                    $output = callIlrmsApi($url, $method);
                    if(!empty($output) && !empty($output->notification)){
                        $notification = $output->notification;
                        if($notification->reverted != 'Y' && $notification->asst_section_officer_verified == 'Y' && $notification->section_officer_verified == 'Y' && $notification->joint_secretary_verified == 'Y'){
                            if($notification->secretary_verified == 'Y'){
                                $secretary_2_status = 1;
                                $data['sec_2_action_date'] = $notification->secretary_verified_at;
                            } else{
                                $secretary_2_status = 2;
                            }
                        }
                    }
                }
            }
        }
        else {
            $nc_villages_v2 = $this->getNCVillageV2Status($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $vill_uuid, DPT_SEC, 'NOTIFICATION');

            if($nc_villages_v2->status == 'FAILED' || $nc_villages_v2->responseType == 1) {
                $secretary_2_status = 0;
            }
            else {
                $secretary_2_status = $nc_villages_v2->data;
            }
        }
        
        // ------------------- SECRETARY_step2 END --------------------
        
        // ------------------- PS_step2 START --------------------
        if($map && $map->app_version == 'V1') {
            if($dlr_proposal){
                $proposal_id = '';
                if($nc_village->select_village_on_prop_sign_from_so == 0){
                    $proposal_id = $dlr_proposal->id;
                } else{
                    if($asst_section_officer_proposal){
                        $proposal_id = $asst_section_officer_proposal->id;
                    }
                }

                if($proposal_id != ''){
                    $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/getNotificationByProposalId/$proposal_id";
                    $method = 'GET';
                    $output = callIlrmsApi($url, $method);
                    if(!empty($output) && !empty($output->notification)){
                        $notification = $output->notification;
                        if($notification->reverted != 'Y' && $notification->asst_section_officer_verified == 'Y' && $notification->section_officer_verified == 'Y' && $notification->joint_secretary_verified == 'Y' && $notification->secretary_verified){
                            if($notification->ps_verified == 'Y'){
                                $ps_2_status = 1;
                                $data['ps_action_date'] = $notification->ps_verified_at;$data['ps_2_action_date'] = $notification->ps_verified_at;
                            } else{
                                $ps_2_status = 2;
                            }
                        }
                    }
                }
            }
        }
        else {
            $nc_villages_v2 = $this->getNCVillageV2Status($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $vill_uuid, DPT_PS, 'NOTIFICATION');

            if($nc_villages_v2->status == 'FAILED' || $nc_villages_v2->responseType == 1) {
                $ps_2_status = 0;
            }
            else {
                $ps_2_status = $nc_villages_v2->data;
            }
        }
        // ------------------- PS_step2 END --------------------
        
        // ------------------- MINISTER START --------------------
        if($map && $map->app_version == 'V1') {
            if($dlr_proposal){
                $proposal_id = '';
                if($nc_village->select_village_on_prop_sign_from_so == 0){
                    $proposal_id = $dlr_proposal->id;
                } else{
                    if($asst_section_officer_proposal){
                        $proposal_id = $asst_section_officer_proposal->id;
                    }
                }

                if($proposal_id != ''){
                    $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/getNotificationByProposalId/$proposal_id";
                    $method = 'GET';
                    $output = callIlrmsApi($url, $method);
                    if(!empty($output) && !empty($output->notification)){
                        $notification = $output->notification;
                        if($notification->reverted != 'Y' && $notification->asst_section_officer_verified == 'Y' && $notification->section_officer_verified == 'Y' && $notification->joint_secretary_verified == 'Y' && $notification->secretary_verified && $notification->ps_verified == 'Y'){
                            if($notification->minister_verified == 'Y'){
                                $minister_status = 1;
                                $data['minister_action_date'] = $notification->minister_verified_at;
                            } else{
                                $minister_status = 2;
                            }
                        }
                    }
                }
            }
        }
        else {
            $nc_villages_v2 = $this->getNCVillageV2Status($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $vill_uuid, MINISTER, 'NOTIFICATION');

            if($nc_villages_v2->status == 'FAILED' || $nc_villages_v2->responseType == 1) {
                $minister_status = 0;
            }
            else {
                $minister_status = $nc_villages_v2->data;
            }
        }
        
        // ------------------- MINISTER END --------------------
        
        // ------------------- PS_step3 START --------------------
        if($map && $map->app_version == 'V1') {
            if($dlr_proposal){
                $proposal_id = '';
                if($nc_village->select_village_on_prop_sign_from_so == 0){
                    $proposal_id = $dlr_proposal->id;
                } else{
                    if($asst_section_officer_proposal){
                        $proposal_id = $asst_section_officer_proposal->id;
                    }
                }

                if($proposal_id != ''){
                    $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/getNotificationByProposalId/$proposal_id";
                    $method = 'GET';
                    $output = callIlrmsApi($url, $method);
                    if(!empty($output) && !empty($output->notification)){
                        $notification = $output->notification;
                        if($notification->reverted != 'Y' && $notification->asst_section_officer_verified == 'Y' && $notification->section_officer_verified == 'Y' && $notification->joint_secretary_verified == 'Y' && $notification->secretary_verified && $notification->ps_verified == 'Y' && $notification->minister_verified == 'Y'){
                            if($notification->ps_sign == 'Y'){
                                $ps_3_status = 1;
                                $data['ps_2_action_date'] = $notification->ps_sign_at;
                            } else{
                                $ps_3_status = 2;
                            }
                        }
                    }
                }
            }
        }
        else {
            $nc_villages_v2 = $this->getNCVillageV2Status($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $vill_uuid, DPT_PS, 'NOTIFICATION');

            if($nc_villages_v2->status == 'FAILED' || $nc_villages_v2->responseType == 1) {
                $ps_3_status = 0;
            }
            else {
                $ps_3_status = $nc_villages_v2->data;
            }
        }
        
        // ------------------- PS_step3 END --------------------
        
        // ------------------- JS_step3 START --------------------
        if($map && $map->app_version == 'V1') {
            if($dlr_proposal){
                $proposal_id = '';
                if($nc_village->select_village_on_prop_sign_from_so == 0){
                    $proposal_id = $dlr_proposal->id;
                } else{
                    if($asst_section_officer_proposal){
                        $proposal_id = $asst_section_officer_proposal->id;
                    }
                }

                if($proposal_id != ''){
                    $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/getNotificationByProposalId/$proposal_id";
                    $method = 'GET';
                    $output = callIlrmsApi($url, $method);
                    if(!empty($output) && !empty($output->notification)){
                        $notification = $output->notification;
                        if($notification->reverted != 'Y' && $notification->asst_section_officer_verified == 'Y' && $notification->section_officer_verified == 'Y' && $notification->joint_secretary_verified == 'Y' && $notification->secretary_verified && $notification->ps_verified == 'Y' && $notification->minister_verified == 'Y' && $notification->ps_sign == 'Y'){
                            if($notification->js_sign == 'Y'){
                                $js_3_status = 1;
                                $data['js_3_action_date'] = $notification->js_sign_at;
                            } else{
                                $js_3_status = 2;
                            }
                        }
                    }
                }
            }
        }
        else {
            $nc_villages_v2 = $this->getNCVillageV2Status($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $vill_uuid, DPT_JSEC, 'NOTIFICATION');

            if($nc_villages_v2->status == 'FAILED' || $nc_villages_v2->responseType == 1) {
                $js_3_status = 0;
            }
            else {
                $js_3_status = $nc_villages_v2->data;
            }
        }
        // ------------------- JS_step3 END --------------------

        $data['lm_status'] = $lm_status;
        $data['sk_status'] = $sk_status;
        $data['co_status'] = $co_status;
        $data['dc_status'] = $dc_status;
        $data['dlr_status'] = $dlr_status;
        $data['ps_1_status'] = $ps_1_status;
        $data['secretary_1_status'] = $secretary_1_status;
        $data['js_1_status'] = $js_1_status;
        $data['so_1_status'] = $so_1_status;
        $data['aso_status'] = $aso_status;
        $data['so_2_status'] = $so_2_status;
        $data['js_2_status'] = $js_2_status;
        $data['secretary_2_status'] = $secretary_2_status;
        $data['ps_2_status'] = $ps_2_status;
        $data['minister_status'] = $minister_status;
        $data['ps_3_status'] = $ps_3_status;
        $data['js_3_status'] = $js_3_status;

        return $data;
    }


    public function fetchUserFromDharitree($d){
        $url = LANDHUB_BASE_URL . "majuli_fetch";
        
        $dist_code = $d; // Majuli
        $users = $loginuser_tables = $lm_codes = [];
        $method = 'POST';
        $data = ['dist_code' => $dist_code, 'table_name' => 'users'];
        $output = callIlrmsApi($url, $method, $data);
        if(!empty($output)){
            $users = json_decode(json_encode($output), true);
        }
        $data = ['dist_code' => $dist_code, 'table_name' => 'loginuser_table'];
        $output = callIlrmsApi($url, $method, $data);
        if(!empty($output)){
            $loginuser_tables = json_decode(json_encode($output), true);
        }
        $data = ['dist_code' => $dist_code, 'table_name' => 'lm_code'];
        $output = callIlrmsApi($url, $method, $data);
        if(!empty($output)){
            $lm_codes = json_decode(json_encode($output), true);
        }
        // echo "<pre/>";
        // var_dump($loginuser_tables);
        // die;
        $this->dbswitch($dist_code);
        $this->db->trans_begin();


        //loginuser_table
        if(count($loginuser_tables) > 0) {
            foreach ($loginuser_tables as $loginuser_table) {
                $check_local_user = $this->db->where([
                    'dist_code' => $loginuser_table['dist_code'],
                    'subdiv_code' => $loginuser_table['subdiv_code'],
                    'cir_code' => $loginuser_table['cir_code'],
                    'mouza_pargona_code' => $loginuser_table['mouza_pargona_code'],
                    'lot_no' => $loginuser_table['lot_no'],
                    // 'use_name' => $loginuser_table['use_name'],
                    'user_code' => $loginuser_table['user_code'],
                ])->get('loginuser_table')->row();

                if(empty($check_local_user)) {
                    //entry
                    $lgu_data = [
                        'use_name' => $loginuser_table['use_name'],
                        'user_code' => $loginuser_table['user_code'],
                        'date_of_creation' => $loginuser_table['date_of_creation'],
                        'dis_enb_option' => $loginuser_table['dis_enb_option'],
                        'first_login' => $loginuser_table['first_login'],
                        'activity' => $loginuser_table['activity'],
                        'date_password_changed' => $loginuser_table['date_password_changed'],
                        'dist_code' => $loginuser_table['dist_code'],
                        'subdiv_code' => $loginuser_table['subdiv_code'],
                        'cir_code' => $loginuser_table['cir_code'],
                        'mouza_pargona_code' => $loginuser_table['mouza_pargona_code'],
                        'lot_no' => $loginuser_table['lot_no'],
                        'password' => $loginuser_table['password'],
                        'prev_password1' => $loginuser_table['prev_password1'],
                        'prev_password2' => $loginuser_table['prev_password2'],
                        // 'noc_user' => $loginuser_table['noc_user'],
                        'user_map' => $loginuser_table['user_map'],
                        'special_status' => $loginuser_table['special_status'],
                        'password_change_flag' => $loginuser_table['password_change_flag'],
                        // 'prev_password3' => $loginuser_table['prev_password3'],
                        'permission_allowed' => $loginuser_table['permission_allowed'] ? $loginuser_table['permission_allowed'] : null,
                        'parent_code' => $loginuser_table['parent_code'] ? $loginuser_table['parent_code'] : null,
                        'created_by' => $loginuser_table['created_by'] ? $loginuser_table['created_by'] : null
                    ];
                    $insertLguStatus = $this->db->insert('loginuser_table', $lgu_data);
                    if(!$insertLguStatus || $this->db->affected_rows() < 1) {
                        $this->db->trans_rollback();
                        echo $this->db->last_query();
                        return;
                    }
                }
            }
        }

        //lm_code
        if(count($lm_codes) > 0) {
            foreach ($lm_codes as $lm_code) {
                $check_lm_code = $this->db->where([
                    'dist_code' => $lm_code['dist_code'],
                    'subdiv_code' => $lm_code['subdiv_code'],
                    'cir_code' => $lm_code['cir_code'],
                    'mouza_pargona_code' => $lm_code['mouza_pargona_code'],
                    'lot_no' => $lm_code['lot_no'],
                    'lm_code' => $lm_code['lm_code'],
                ])->get('lm_code')->row();

                if(empty($check_lm_code)) {
                    //insert
                    $lm_data = [
                        'dist_code' => $lm_code['dist_code'],
                        'subdiv_code' => $lm_code['subdiv_code'],
                        'cir_code' => $lm_code['cir_code'],
                        'mouza_pargona_code' => $lm_code['mouza_pargona_code'],
                        'lot_no' => $lm_code['lot_no'],
                        'lm_name' => $lm_code['lm_name'],
                        'lm_code' => $lm_code['lm_code'],
                        'status' => $lm_code['status'],
                        'corres_sk_code' => $lm_code['corres_sk_code'],
                        'dt_from' => $lm_code['dt_from'],
                        'dt_to' => $lm_code['dt_to'],
                        'lm_thumb_imp' => $lm_code['lm_thumb_imp'],
                        'lm_sign1' => $lm_code['lm_sign1'],
                        'lm_sign2' => $lm_code['lm_sign2'],
                        'lm_sign3' => $lm_code['lm_sign3'],
                        'phone_no' => $lm_code['phone_no'],
                        'lmuser' => $lm_code['lmuser']
                        //'lm_user' => $lm_code['lmuser'],
                    ];
                    $insertLmcodeStatus = $this->db->insert('lm_code', $lm_data);
                    if(!$insertLmcodeStatus || $this->db->affected_rows() < 1) {
                        $this->db->trans_rollback();
                        echo $this->db->last_query();
                        return;
                    }
                }
            }
        }

        //users
        if(count($users) > 0) {
            foreach ($users as $user) {
                $check_users = $this->db->where([
                    'dist_code' => $user['dist_code'],
                    'subdiv_code' => $user['subdiv_code'],
                    'cir_code' => $user['cir_code'],
                    'user_code' => $user['user_code'],
                    // 'user_desig_code' => $user['user_desig_code']
                ])->get('users')->row();

                if(empty($check_users)) {
                    $user_data = [
                        'dist_code' => $user['dist_code'],
                        'subdiv_code' => $user['subdiv_code'],
                        'cir_code' => $user['cir_code'],
                        'username' => $user['username'],
                        'user_code' => $user['user_code'],
                        'user_desig_code' => $user['user_desig_code'],
                        'status' => $user['status'],
                        'date_from' => $user['date_from'],
                        'date_to' => $user['date_to'],
                        'user_thumb_imp' => $user['user_thumb_imp'],
                        'user_sign1' => $user['user_sign1'],
                        'user_sign2' => $user['user_sign2'],
                        'user_sign3' => $user['user_sign3'],
                        // 'phone_number' => $user_row['phone_number'],
                        'usernm' => $user['usernm'],
                        'phone_no' => $user['phone_no'],
                        'emailid' => $user['emailid'],
                        'priority' => $user['priority'],
                        'display_name' => $user['display_name'],
                        // 'aadhar_no' => $user_row['aadhar_no'],
                    ];
                    $insertUsersStatus = $this->db->insert('users', $user_data);
                    if(!$insertUsersStatus || $this->db->affected_rows() < 1) {
                        $this->db->trans_rollback();
                        echo $this->db->last_query();
                        return;
                    }
                }
            }
        }

        $this->db->trans_commit();
        echo 'Successfully Done';
        die;
    }
}
