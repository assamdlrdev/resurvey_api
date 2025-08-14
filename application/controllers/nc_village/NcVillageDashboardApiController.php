<?php
include APPPATH . '/libraries/CommonTrait.php';

class NcVillageDashboardApiController extends CI_Controller
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
				ncv.co_verified,ncv.co_note,ncv.dc_verified,ncv.dc_verified_at,ncv.dc_note,ncv.ads_verified from
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
    public function apiGetPendingVillage()
    {
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
                $approve_proposal = $this->db->select('proposal_no')
                    ->where(array('dist_code' => $dist_code, 'id' => $nc_village->dc_proposal_id,
                        'user_type' => 'DC', 'status' => 'A'))
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
        $user_code = $this->UtilsModel->cleanPattern($this->input->post('user_code'));
        $this->dbswitch($dist_code);

        $this->db->trans_start();
        $this->db->set([

            'new_vill_name' => $new_vill_name,
            'new_vill_name_eng' => $new_vill_name_eng,
            'cu_user_code' => "DLR",
            'prev_user_code' => "DC",
            'status' => "F",
            'date_of_update_dlr' => date('Y-m-d H:i:s'),
            'dlr_verified' => "Y",
            "dlr_user_code" => $user_code,

        ]);
        $this->db->where('uuid', $uuid);
        $this->db->where('dist_code', $dist_code);
        $this->db->update('change_vill_name');
        if ($this->db->affected_rows() > 0) {

            // $approveVill = $this->ChangeVillageModel->approveChangeVillage($uuid, $new_vill_name, $new_vill_name_eng);

            // if ($approveVill) {
            $this->db->trans_complete();
            $arr = array(
                'data' => 'Y',
                'status_code' => 200,
            );
            echo json_encode($arr);
            return;
            // } else {
            //     $arr = array(
            //         'data' => 'NM',
            //         'status_code' => 200,
            //     );
            //     echo json_encode($arr);

            // }
        }

        $arr = array(
            'data' => 'N',
            'status_code' => 200,
        );
        echo json_encode($arr);
    }

    /** DLR Certify */
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

                $this->dbswitch($dist_code);

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
                    'office_from' => 'DLR',
                    'office_to' => 'DEPT',
                    'task' => 'Village verified by DLR',
                );
                $this->db->insert('settlement_proceeding', $insPetProceed);

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
                            'pre_user' => 'DLR',
                            'cu_user' => 'DEPT',
                        ]
                    );

                if ($this->db->affected_rows() > 0) {

                    $approveVill = $this->ChangeVillageModel->approveChangeVillage($uuid, $dist_code, $user_code);
                    if ($approveVill) {
                        $this->db->trans_complete();
                        $arr = array(
                            'data' => 'Y',
                            'nc_village' => $nc_village = $this->db->query("select * from nc_villages where application_no='$application_no'")->row(),
                            'status_code' => 200,
                        );
                        echo json_encode($arr);
                        return;
                    } else {
                        $arr = array(
                            'data' => 'N',
                            'nc_village' => $nc_village = $this->db->query("select * from nc_villages where application_no='$application_no'")->row(),
                            'status_code' => 200,
                        );
                        echo json_encode($arr);
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

    /** DLR Revert to dc */
    public function apiDlrRevert()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE'])) {
            $contentType = $_SERVER['CONTENT_TYPE'];

            if (strcasecmp($contentType, 'application/x-www-form-urlencoded') === 0) {
                $dist_code = $this->UtilsModel->cleanPattern($this->input->post('dist_code'));
                $application_no = $this->UtilsModel->cleanPattern($this->input->post('application_no'));
                $dlr_note = $this->UtilsModel->cleanPattern($this->input->post('dlr_note'));
                $user_code = $this->UtilsModel->cleanPattern($this->input->post('user_code'));

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
                    'office_from' => 'DLR',
                    'office_to' => 'DC',
                    'task' => 'Village Reverted by DLR',
                );
                $this->db->insert('settlement_proceeding', $insPetProceed);

                $this->db->where('application_no', $application_no)
                    ->update(
                        'nc_villages',
                        [
                            'updated_at' => date('Y-m-d H:i:s'),
                            'proposal_id' => null,
                            'dc_proposal' => null,
                            'dc_verified' => null,
                            'dc_verified_at' => null,
                            'dlr_note' => $dlr_note,
                            'dlr_user_code' => $user_code,
                            'status' => 'B',
                            'pre_user' => 'DLR',
                            'cu_user' => 'DC',
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

    public function svamitvaDataEnteredVillages()
    {

        if ($this->input->get('api_key') == "chitha_application" or $_POST['api_key'] == "chitha_application") {

            $dist_code = $this->input->post('dist_code');
            $subdiv_code = $this->input->post('subdiv_code');
            $cir_code = $this->input->post('cir_code');
            $mouza_pargona_code = $this->input->post('mouza_pargona_code');
            $lot_no = $this->input->post('lot_no');

            $uuids = $this->getSvamitvaVillageUuids($dist_code,$subdiv_code,$cir_code,$mouza_pargona_code,$lot_no);

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

            $uuids = $this->getSvamitvaVillageUuids();
           
            $villages_count=0;
            foreach (json_decode(NC_DISTIRTCS) as $dist_code) {
                $this->dbswitch($dist_code);
                foreach ($uuids as $uuid) {
                    $village = $this->db->query("select * from location where uuid = '$uuid'")->row();
                    if ($village) {
                        $govt_dags = $this->DagReportModel->getGovtDags($village->dist_code, $village->subdiv_code, $village->cir_code, $village->mouza_pargona_code, $village->lot_no, $village->vill_townprt_code);
                        if (count($govt_dags) > 0) {
                           $villages_count++;
                        }
                    }
                }
            }
            $arr = array(
                'data' => $villages_count,
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
                    'dist_code' => $v->dist_code, 'subdiv_code' => $v->subdiv_code, 'cir_code' => $v->cir_code, '
				mouza_pargona_code' => $v->mouza_pargona_code, 'lot_no' => $v->lot_no,
                    'vill_townprt_code' => $v->vill_townprt_code,
                ))
                ->get('chitha_basic')->row();

            $total_lessa = $this->totalLessa($vill_area->total_bigha, $vill_area->total_katha, $vill_area->total_lessa);
            $nc_village[$k]->total_b_k_l = $this->Total_Bigha_Katha_Lessa($total_lessa);

            $nc_village[$k]->occupiers = $this->db->select('count(*) as occupiers')
                ->where(array(
                    'dist_code' => $v->dist_code, 'subdiv_code' => $v->subdiv_code, 'cir_code' => $v->cir_code, '
				mouza_pargona_code' => $v->mouza_pargona_code, 'lot_no' => $v->lot_no,
                    'vill_townprt_code' => $v->vill_townprt_code,
                ))
                ->get('chitha_rmk_encro')->row()->occupiers;
        }

        $data['approve_proposal'] = $this->db->select('*')
            ->where(array('dist_code' => $dist_code, 'user_type' => 'DLR'))
            ->get('nc_village_proposal')->result();

        $data['nc_village'] = $nc_village;
        $data['proposal'] = $this->load->view('nc_village/dlr/approval_notification', $data, true);

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
    public function getDlrPendingVillages()
    {
        $d_list = (array) $this->input->post('d');
        $data['count'] = 0;
        $data['pro_count'] = 0;
        foreach ($d_list as $k => $d) {
            $dist_code = $d['dist_code'];
            $this->dbswitch($dist_code);

            $data['count'] += $this->db->query("select count(*) as count from nc_villages where
 				dist_code='$dist_code' and status = 'I'")->row()->count;

            $data['pro_count'] += $this->db->query("select count(*) as count from nc_villages where
 				dist_code='$dist_code' and status = 'L' and dlr_proposal is null")->row()->count;
        }

        $arr = array(
            'data' => $data,
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
                    'status' => 'A',
                ));
        } else {
            $this->db->insert('nc_village_proposal', array(
                'proposal_no' => $proposal_no,
                'user_code' => $user_code,
                'user_type' => $user_type,
                'sign_key' => $sign_key,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'status' => 'A',
                'dist_code' => $dist_code,
            ));

            $lastid = $this->db->insert_id();

            $this->db->where('dist_code', $dist_code)
                ->where('status', 'L')
                ->update('nc_villages', array('status' => 'A', 'dlr_proposal' => 'Y', 'dlr_proposal_id' => $lastid));
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
				ncv.co_verified,ncv.co_note,ncv.dc_verified,ncv.dc_verified_at,ncv.dc_note,ncv.ads_verified from
				nc_villages ncv join
				location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code
                 and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and
                 ncv.vill_townprt_code = l.vill_townprt_code
                 join location ll on ncv.dist_code = ll.dist_code";

            $query = $query . " where ncv.dist_code='$dist_code' and ll.subdiv_code ='00' and ncv.status='I'";

            $nc_villages = $this->db->query($query)->result();
            foreach ($nc_villages as $nc) {
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
                    ->where(array('dist_code' => $dist_code, 'id' => $nc_village->dlr_proposal_id,
                        'user_type' => 'DLR', 'status' => 'A'))
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
                    $query = $query . " and ncv.dlr_verified = 'Y'  and ncv.status!='L' and ncv.dlr_proposal_id is not null";
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
                    $query = $query . " and ncv.dlr_verified = 'Y' and ncv.status!='L'  and ncv.dlr_proposal_id is not null";
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
                    $query = $query . " and ncv.dlr_verified = 'Y' and ncv.status!='L'  and ncv.dlr_proposal_id is not null";
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
                ->where(array('dist_code' => $dist_code, 'subdiv_code' => $subdiv_code, 'cir_code' => $cir_code,
                    'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->get('location')->row_array();

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
                    $query = $query . " and ncv.dlr_verified = 'Y' and ncv.status!='L'  and ncv.dlr_proposal_id is not null";
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
		$data_dist_wise = [];

		foreach (json_decode(NC_DISTIRTCS) as $dist_code) {
			$this->dbswitch($dist_code);
			$verified_lm_count_dist = $this->db->query("select * from nc_villages where lm_verified = 'Y' ")->num_rows();
			$pending_lm_count_dist = $this->db->query("select * from nc_villages where lm_verified is null or status='U'")->num_rows();
			$pending_sk_count_dist = $this->db->query("select * from nc_villages where lm_verified ='Y' and (status='S' or status='H')")->num_rows();
			$certified_co_count_dist = $this->db->query("select * from nc_villages where co_verified = 'Y' ")->num_rows();
			$pending_co_count_dist = $this->db->query("select * from nc_villages where sk_verified = 'Y' and (status='F' or status='O' or status='J')")->num_rows();
			$digi_signature_dc_count_dist = $this->db->query("select * from nc_villages where dc_verified = 'Y' ")->num_rows();
			$dc_forwarded_dist = $this->db->query("select * from nc_villages where dc_verified = 'Y' and (status='I' or dlr_verified = 'Y') and dc_proposal_id is not null ")->num_rows();
			$pending_dc_count_dist = $this->db->query("select * from nc_villages where co_verified = 'Y' and (status='G' or status='K' or status ='B')")->num_rows();
			$dlrs_pending_dist = $this->db->query("select * from nc_villages where dc_verified = 'Y' and (status='I' or status='L')")->num_rows();
			$dlrs_forwarded_dist = $this->db->query("select * from nc_villages where dlr_verified = 'Y' and status != 'L' and dlr_proposal_id is not null ")->num_rows();

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
			'dlrs_pending' => $dlrs_pending,
			'dlr_forwarded' => $dlrs_forwarded,
			'updated_at' => date('Y-m-d'),
			'updated_at_time' => date('H:i:s'),
		];

		return ($data);
    }

    public function svamitvaDataEnteredData()
    {

        if ($this->input->get('api_key') == "chitha_application" or $_POST['api_key'] == "chitha_application") {

            $uuids = $this->getSvamitvaVillageUuids();

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

            $dist_code = $this->input->post('dist_code');
            $this->dbswitch($dist_code);
            $circles = $this->db->query("select loc_name,dist_code,subdiv_code,cir_code from location where dist_code='$dist_code' and subdiv_code!='00' and cir_code!='00' and mouza_pargona_code='00'")->result();
            $svamitva_villages = $this->getSvamitvaVillages($dist_code);

            $data = [];
            foreach($circles as $circle){
                $data_single = [];
                $data_single['circle'] = $circle;
                $circle_data_count = 0;
                foreach($svamitva_villages as $svamitva_village){
                    if($svamitva_village->subdiv_code == $circle->subdiv_code && $svamitva_village->cir_code == $circle->cir_code){
                        $govt_dags = $this->DagReportModel->getGovtDags($svamitva_village->dist_code, $svamitva_village->subdiv_code, $svamitva_village->cir_code, $svamitva_village->mouza_pargona_code, $svamitva_village->lot_no, $svamitva_village->vill_townprt_code);
                        $circle_data_count += count($govt_dags);
                    }
                }
                $data_single['circle_data_count'] = $circle_data_count;
                $data[] = $data_single;
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
    }

    public function svamitvaDataEnteredMouza()
    {

        if ($this->input->get('api_key') == "chitha_application" or $_POST['api_key'] == "chitha_application") {

            // if ($this->input->get('uuids')) {
            //     $uuids = $this->input->get('uuids');
            // } else {
            //     $uuids = $_POST['uuids'];
            // }
            $dist_code = $this->input->post('dist_code');
            $subdiv_code = $this->input->post('subdiv_code');
            $cir_code = $this->input->post('cir_code');
            $uuids = $this->getSvamitvaVillageUuids($dist_code,$subdiv_code,$cir_code);
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

            // if ($this->input->get('uuids')) {
            //     $uuids = $this->input->get('uuids');
            // } else {
            //     $uuids = $_POST['uuids'];
            // }
            $dist_code = $this->input->post('dist_code');
            $subdiv_code = $this->input->post('subdiv_code');
            $cir_code = $this->input->post('cir_code');
            $mouza_pargona_code = $this->input->post('mouza_pargona_code');
            $uuids = $this->getSvamitvaVillageUuids($dist_code,$subdiv_code,$cir_code,$mouza_pargona_code);
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
                    $data['districts'][(string) $dist_code]["loc_name"] = $this->CommonModel->getLocations((string) $dist_code);

                    $village = $this->db->query("select count(*) as count from nc_villages where
 					dist_code='$dist_code' and dc_verified = 'Y' and (status ='I' or dlr_verified = 'Y') and dc_proposal_id is not null")->row()->count;

                    $data['districts'][(string) $dist_code]["village"] = $village;
                }
            }
        }
        elseif ($type == 'dc_pending'){
			if ($dists) {
				foreach ($dists as $dist_code) {
					$this->dbswitch($dist_code);
					$data['districts'][(string)$dist_code]["loc_name"] = $this->CommonModel->getLocations((string)$dist_code);

					$village = $this->db->query("select count(*) as count from nc_villages where
 				dist_code='$dist_code' and co_verified = 'Y' and (status='G' or status='K' or status ='B')")->row()->count;

					$data['districts'][(string)$dist_code]["village"] = $village;

				}
			}
		}
        elseif ($type == 'dlrs_pending') {
            if ($dists) {
                foreach ($dists as $dist_code) {
                    $this->dbswitch($dist_code);
                    $data['districts'][(string) $dist_code]["loc_name"] = $this->CommonModel->getLocations((string) $dist_code);

                    $village = $this->db->query("select count(*) as count from nc_villages where
 				dist_code='$dist_code' and dc_verified = 'Y' and (status='I' or status='L')")->row()->count;

                    $data['districts'][(string) $dist_code]["village"] = $village;

                }
            }
        } elseif ($type == 'dlr_forwarded') {
            if ($dists) {
                foreach ($dists as $dist_code) {
                    $this->dbswitch($dist_code);
                    $data['districts'][(string) $dist_code]["loc_name"] = $this->CommonModel->getLocations((string) $dist_code);

                    $village = $this->db->query("select count(*) as count from nc_villages where
 					dist_code='$dist_code' and dlr_verified = 'Y' and status != 'L' and dlr_proposal_id is not null")->row()->count;

                    $data['districts'][(string) $dist_code]["village"] = $village;
                }
            }
        } elseif ($type == 'ps_pending') {
            if ($dists) {
                foreach ($dists as $dist_code) {
                    $this->dbswitch($dist_code);
                    $data['districts'][(string) $dist_code]["loc_name"] = $this->CommonModel->getLocations((string) $dist_code);

                    $proposal = $this->db->query("select count(*) as count from nc_village_proposal where
 					dist_code='$dist_code' and user_type = 'DLR' and ps_verified is null and status ='A'")->row()->count;

                    $data['districts'][(string) $dist_code]["proposal"] = $proposal;
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
                $data["loc_name"] = $this->CommonModel->getLocations((string) $dist_code);

                $village = $this->db->query("select * from nc_villages where
 					dist_code='$dist_code' and dc_verified = 'Y' and dc_proposal_id is not null")->result();
                foreach ($village as $v) {
                    $this->CommonModel->getLocations((string) $dist_code, $village['']);
                }
                $data["village"] = $village;
            }
        } elseif ($type == 'dlrs_pending') {
            if ($dist_code) {
                $this->dbswitch($dist_code);
                $data["loc_name"] = $this->CommonModel->getLocations((string) $dist_code);

                $proposal = $this->db->query("select * from nc_village_proposal where
 				dist_code='$dist_code' and user_type = 'DC' and dlr_verified is null and status ='A'")->result();
                $data["proposal"] = $proposal;
            }
        } elseif ($type == 'dlr_forwarded') {
            if ($dist_code) {
                $this->dbswitch($dist_code);
                $data["loc_name"] = $this->CommonModel->getLocations((string) $dist_code);

                $proposal = $this->db->query("select * from nc_village_proposal where
 				dist_code='$dist_code' and user_type = 'DLR' and status ='A'")->result();
                $data["proposal"] = $proposal;
            }
        } elseif ($type == 'ps_pending') {
            if ($dist_code) {
                $this->dbswitch($dist_code);
                $data["loc_name"] = $this->CommonModel->getLocations((string) $dist_code);

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
            ->where(array('dist_code' => $dist_code, 'id' => $proposal_id,
                'user_type' => 'DC', 'status' => 'A'))
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
            ->where(array('dist_code' => $dist_code, 'id' => $proposal_id,
                'user_type' => 'DLR', 'status' => 'A'))
            ->get('nc_village_proposal')->row();

        echo json_encode($approve_proposal);
    }
    public function getSvamitvaVillageUuids($dist_code = null,$subdiv_code = null,$cir_code=null,$mouza_pargona_code = null,$lot_no = null)
    {
        $params = [
            'status' => '1',
        ];
        if($dist_code){
            $params['dist_code'] = $dist_code;
        }
        if($subdiv_code){
            $params['subdiv_code'] = $subdiv_code;
        }
        if($cir_code){
            $params['cir_code'] = $cir_code;
        }
        if($mouza_pargona_code){
            $params['mouza_pargona_code'] = $mouza_pargona_code;
        }
        if($lot_no){
            $params['lot_no'] = $lot_no;
        }
        $svamitva_villages = callLandhubAPI('POST', 'getVillages_svamitva', $params);
        $villages = $svamitva_villages != 'N' ? $svamitva_villages : [];
        $uuids = [];
        foreach($villages as $nc_village){
            $uuids[] = $nc_village->uuid;
        }
        return $uuids;
    }
    public function getSvamitvaVillages($dist_code = null,$subdiv_code = null,$cir_code=null,$mouza_pargona_code = null,$lot_no = null)
    {
        $params = [
            'status' => '1',
        ];
        if($dist_code){
            $params['dist_code'] = $dist_code;
        }
        if($subdiv_code){
            $params['subdiv_code'] = $subdiv_code;
        }
        if($cir_code){
            $params['cir_code'] = $cir_code;
        }
        if($mouza_pargona_code){
            $params['mouza_pargona_code'] = $mouza_pargona_code;
        }
        if($lot_no){
            $params['lot_no'] = $lot_no;
        }
        $svamitva_villages = callLandhubAPI('POST', 'getVillages_svamitva', $params);
        $villages = $svamitva_villages != 'N' ? $svamitva_villages : [];
        
        return $villages;
    }
}
