<?php
include APPPATH . '/libraries/CommonTrait.php';

class NcVillageLmController extends CI_Controller
{
    use CommonTrait;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('UtilsModel');
        $this->load->model('DagReportModel');
        $this->load->model('Chithamodel');
        $this->load->model('CommonModel');
        $this->load->model('chitha/DharChithaModel');
        $this->load->model('NcVillageModel');
    }

    public function dashboard()
    {
        if ($this->session->userdata('usertype') != 3) {
            show_error('<svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><g fill="none" stroke="#FF0000" stroke-linecap="round" stroke-width="2"><path d="M12 9v5m0 3.5v.5"/><path stroke-linejoin="round" d="M2.232 19.016L10.35 3.052c.713-1.403 2.59-1.403 3.302 0l8.117 15.964C22.45 20.36 21.544 22 20.116 22H3.883c-1.427 0-2.334-1.64-1.65-2.984Z"/></g></svg> <p>Unauthorized access</p>', "403");
        }
        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no = $this->session->userdata('lot_no');

        //MAPS COUNT FROM ILRMS
        $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/apiGetMapsCount";
        $method = 'POST';
        $output = callIlrmsApi($url, $method, ['d' => $dist_code, 's' => $subdiv_code, 'c' => $cir_code, 'm' => $mouza_pargona_code, 'l' => $lot_no, 'filter_flag_is' => 'B']);
        $maps_count = ($output && !empty($output->data)) ? $output->data : 0;

        $this->dbswitch();
        $data['cases_e'] = $this->db->query("select count(*) as c from nc_villages where  dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and status !='E'")->row()->c;
        $data['lm_not_verified'] = $maps_count;

        $data['reverted'] = $this->db->query("select count(*) as c from nc_villages where  dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and status='U' and pre_user='SK' and cu_user='LM'")->row()->c;
        $data['_view'] = 'nc_village/lm/nc_village_lm_dashboard';

        //co notifications count

        $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/apiGetNotifications";
        $method = 'POST';
        $dist_code = (string)$this->session->userdata('dist_code');
        $api_params = ['dist_code' => $dist_code];
        $notifications = callIlrmsApi2($url, $method, $api_params);
        $data['notifications_count'] = $notifications ? count($notifications->notifications) : 0;

        $this->load->view('layout/layout', $data);
    }

    /** get nc villages */
    public function ncVillages($vill_townprt_code, $case_type)
    {
        $this->dbswitch();
        $dist = $this->session->userdata('dcode');
        $subdiv = $this->session->userdata('subdiv_code');
        $circle = $this->session->userdata('cir_code');
        $mouza = $this->session->userdata('mouza_pargona_code');
        $lot = $this->session->userdata('lot_no');
        $data['locations'] = $this->CommonModel->getLocations($dist, $subdiv, $circle, $mouza, $lot, $vill_townprt_code);

        $data['nc_village'] = null;
        $data['vill_townprt_code'] = $vill_townprt_code;

        // if (isset($_GET['application_no'])) {
        //     $application_no = $_GET['application_no'];
        //     if ($application_no) {
        //         $data['nc_village'] = $this->db->query("select * from nc_villages where application_no = '$application_no'")->row();
        //     }
        // }

        $data['d'] = $dist;
        $data['s'] = $subdiv;
        $data['c'] = $circle;
        $data['m'] = $mouza;
        $data['l'] = $lot;
        $data['v'] = $vill_townprt_code;

        $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/apiGetMap";

        $method = 'POST';
        $output = callIlrmsApi($url, $method, $data);
        $data['maps'] = array();
        if (sizeof($output->data) != 0) {
            $data['maps'] = $output->data;
        }
        if (!$data['maps']) {
            redirect(base_url() . 'index.php/nc_village/NcVillageLmController/dashboard');
        }
        $data['map_row'] = $output->map_row;
        $data['map_id'] = $map_id = $data['map_row']->id;
        $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/apiGetMergeVillageRequests";
        $method = 'POST';
        $output = callIlrmsApi($url, $method, ['map_id' => $map_id]);
        $data['merge_village_requests'] = json_decode(json_encode($output->data), true);
        $data['case_type'] = $case_type;
        $data['_view'] = 'nc_village/lm/nc_village_verified_draft_for_lm';
        $this->load->view('layout/layout', $data);
    }

    /** Get Dags */
    public function getDags()
    {
        $this->dbswitch();
        $this->form_validation->set_rules('dist_code', 'dist_code', 'trim|required');
        $this->form_validation->set_rules('subdiv_code', 'subdiv_code', 'trim|required');
        $this->form_validation->set_rules('cir_code', 'cir_code', 'trim|required');
        $this->form_validation->set_rules('mouza_pargona_code', 'mouza_pargona_code', 'trim|required');
        $this->form_validation->set_rules('lot_no', 'lot_no', 'trim|required');
        $this->form_validation->set_rules('vill_townprt_code', 'vill_townprt_code', 'trim|required');

        if ($this->form_validation->run() == false) {
            echo json_encode(array('errors' => validation_errors(), 'st' => 0));
            return;
        }

        $loc['dist_code'] = $dist_code = $this->UtilsModel->cleanPattern($this->input->post('dist_code'));
        $loc['cir_code'] = $cir_code = $this->UtilsModel->cleanPattern($this->input->post('cir_code'));
        $loc['subdiv_code'] = $subdiv_code = $this->UtilsModel->cleanPattern($this->input->post('subdiv_code'));
        $loc['mouza_pargona_code'] = $mouza_pargona_code = $this->UtilsModel->cleanPattern($this->input->post('mouza_pargona_code'));
        $loc['lot_no'] = $lot_no = $this->UtilsModel->cleanPattern($this->input->post('lot_no'));
        $loc['vill_townprt_code'] = $vill_townprt_code = $this->UtilsModel->cleanPattern($this->input->post('vill_townprt_code'));
        $case_type = $this->input->post('case_type');
        $merge_with_c_village = $this->input->post('merge_with_c_village');
        $min_dag_no = $this->input->post('min_dag_no');
        $max_dag_no = $this->input->post('max_dag_no');

        if ($case_type == 'NC_TO_C' && !$merge_with_c_village) {
            echo json_encode(array('errors' => 'Something went wrong.Merge with village details not found. Please contact the system admin.', 'st' => 0));
            return;
        }

        $q = "select * from nc_villages where dist_code=? AND subdiv_code=? AND cir_code=?
        AND mouza_Pargona_code=? AND lot_No=? AND vill_townprt_code=?";
        $nc_village = $this->db->query($q, array(
            $dist_code,
            $subdiv_code,
            $cir_code,
            $mouza_pargona_code,
            $lot_no,
            $vill_townprt_code,
        ))->row();

        $dags = null;
        if ($nc_village == null) {
            //            $dags = $this->DagReportModel->getGovtDags($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code);
            $check_chitha_basic_nc = $this->DagReportModel->checkChithaBasicNc($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code);
            if (!$check_chitha_basic_nc) {
                $insert_bhunaksa_dags = $this->DagReportModel->insertBhunaksadags($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $case_type, $merge_with_c_village, $min_dag_no, $max_dag_no);
                if ($insert_bhunaksa_dags['code'] == 500) {
                    echo json_encode(array('errors' => $insert_bhunaksa_dags['message'], 'st' => 0));
                    return;
                }
            }
            $dags = $this->DagReportModel->getGovtDagsFromChithaBasicNc($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code);

            foreach ($dags as $key => $dag) {
                $dag->occupiers = $this->DagReportModel->occupierNames($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag->dag_no);
                //				$dags[$key]->chitha_data = $this->DagReportModel->getChithaDags($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag->dag_no);
            }
            $data = $this->getChithaNBhunaksa($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $case_type, $merge_with_c_village);

            $chitha_data = array(
                'chitha_total_dag' => $data['total_dags'],
                'chitha_total_area_skm' => round($data['total_sq_km'], 5),
                'bhunaksa_total_dag' => $data['bhunaksa_total_dag'],
                'bhunaksa_total_area_skm' => round($data['bhunaksa_area_sq_km'], 5),
            );
            echo json_encode(array('data' => $dags, 'lm_verified' => null, 'application_no' => '', 'st' => 1, 'status' => null, 'chitha_data' => $chitha_data));

            return;
        } else {
            $check_chitha_basic_nc = $this->DagReportModel->checkChithaBasicNc($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code);
            if (!$check_chitha_basic_nc) {
                //				$insert_bhunaksa_dags = $this->DagReportModel->insertBhunaksadags($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code);

                $dags = $this->CommonModel->getNcVillageDagsOldChitha($nc_village->application_no);
            } else {
                $dags = $this->CommonModel->getNcVillageDags($nc_village->application_no);
            }

            foreach ($dags as $dag) {
                $dag->occupiers = $this->DagReportModel->occupierNames($dag->dist_code, $dag->subdiv_code, $dag->cir_code, $dag->mouza_pargona_code, $dag->lot_no, $dag->vill_townprt_code, $dag->dag_no);
            }
            $data = $this->getChithaNBhunaksa($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $case_type, $merge_with_c_village);
            //			$chitha_data = array(
            //				'chitha_total_dag' => $nc_village->chitha_total_dag,
            //				'chitha_total_area_skm' => $nc_village->chitha_total_area_skm,
            //				'bhunaksa_total_dag' => $nc_village->bhunaksa_total_dag,
            //				'bhunaksa_total_area_skm' => $nc_village->bhunaksa_total_area_skm,
            //			);
            $chitha_data = array(
                'chitha_total_dag' => $data['total_dags'],
                'chitha_total_area_skm' => round($data['total_sq_km'], 5),
                'bhunaksa_total_dag' => $data['bhunaksa_total_dag'],
                'bhunaksa_total_area_skm' => round($data['bhunaksa_area_sq_km'], 5),
            );
            echo json_encode(array('data' => $dags, 'lm_verified' => $nc_village->lm_verified, 'application_no' => $nc_village->application_no, 'st' => 1, 'status' => $nc_village->status, 'chitha_data' => $chitha_data));

            return;
        }
    }

    public function syncDagsWithBhunakshaAgain()
    {
        $this->dbswitch();
        $this->form_validation->set_rules('dist_code', 'dist_code', 'trim|required');
        $this->form_validation->set_rules('subdiv_code', 'subdiv_code', 'trim|required');
        $this->form_validation->set_rules('cir_code', 'cir_code', 'trim|required');
        $this->form_validation->set_rules('mouza_pargona_code', 'mouza_pargona_code', 'trim|required');
        $this->form_validation->set_rules('lot_no', 'lot_no', 'trim|required');
        $this->form_validation->set_rules('vill_townprt_code', 'vill_townprt_code', 'trim|required');

        if ($this->form_validation->run() == false) {
            echo json_encode(array('errors' => validation_errors(), 'st' => 0));
            return;
        }

        $loc['dist_code'] = $dist_code = $this->UtilsModel->cleanPattern($this->input->post('dist_code'));
        $loc['cir_code'] = $cir_code = $this->UtilsModel->cleanPattern($this->input->post('cir_code'));
        $loc['subdiv_code'] = $subdiv_code = $this->UtilsModel->cleanPattern($this->input->post('subdiv_code'));
        $loc['mouza_pargona_code'] = $mouza_pargona_code = $this->UtilsModel->cleanPattern($this->input->post('mouza_pargona_code'));
        $loc['lot_no'] = $lot_no = $this->UtilsModel->cleanPattern($this->input->post('lot_no'));
        $loc['vill_townprt_code'] = $vill_townprt_code = $this->UtilsModel->cleanPattern($this->input->post('vill_townprt_code'));

        $case_type = $this->input->post('case_type');
        $merge_with_c_village = $this->input->post('merge_with_c_village');
        $min_dag_no = $this->input->post('min_dag_no');
        $max_dag_no = $this->input->post('max_dag_no');

        if ($case_type == 'NC_TO_C' && !$merge_with_c_village) {
            echo json_encode(array('errors' => 'Something went wrong.Merge with village details not found. Please contact the system admin.', 'st' => 0));
            return;
        }

        $q = "select * from nc_villages where dist_code=? AND subdiv_code=? AND cir_code=?
        AND mouza_Pargona_code=? AND lot_No=? AND vill_townprt_code=?";
        $nc_village = $this->db->query($q, array(
            $dist_code,
            $subdiv_code,
            $cir_code,
            $mouza_pargona_code,
            $lot_no,
            $vill_townprt_code,
        ))->row();

        if ($nc_village == null) {
            //            $dags = $this->DagReportModel->getGovtDags($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code);
            $check_chitha_basic_nc = $this->DagReportModel->checkChithaBasicNc($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code);
            $this->db->trans_begin();
            try {
                if (!$check_chitha_basic_nc) {
                    $insert_bhunaksa_dags = $this->DagReportModel->insertBhunaksadags($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $case_type, $merge_with_c_village, $min_dag_no, $max_dag_no);
                    if ($insert_bhunaksa_dags['code'] == 500) {
                        echo json_encode(array('errors' => $insert_bhunaksa_dags['message'], 'st' => 0));
                        return;
                    }
                } else {
                    $insert_bhunaksa_dags = $this->DagReportModel->insertBhunaksadagsIfCountNotMatchedWithChitha($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code);
                }
            } catch (Exception $e) {
                $this->db->trans_rollback();
                return response_json(['success' => false, 'message' => $e->getMessage()]);
            }
            $this->db->trans_commit();

            return response_json(['success' => true, 'message' => 'Dags successfully synced with Bhunaksha.']);
        } else {
            $this->db->trans_begin();
            try {
                $check_chitha_basic_nc = $this->DagReportModel->checkChithaBasicNc($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code);
                if ($nc_village->lm_verified == 'Y') {
                    throw new Exception('Village has already been verified by ' . LM_LABEL);
                }

                if (!$check_chitha_basic_nc) {
                    // $insert_bhunaksa_dags = $this->DagReportModel->insertBhunaksadags($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code);
                } else {
                    $this->DagReportModel->insertBhunaksadagsIfCountNotMatchedWithChitha($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $nc_village->application_no);
                }
            } catch (Exception $e) {
                $this->db->trans_rollback();
                return response_json(['success' => false, 'message' => $e->getMessage()]);
            }
            $this->db->trans_commit();

            return response_json(['success' => true, 'message' => 'Dags successfully synced with Bhunaksha.']);
        }
    }

    /** Update Dag */
    public function updateDag()
    {
        $this->dbswitch();
        $this->form_validation->set_rules('dist_code', 'dist_code', 'trim|required');
        $this->form_validation->set_rules('subdiv_code', 'subdiv_code', 'trim|required');
        $this->form_validation->set_rules('cir_code', 'cir_code', 'trim|required');
        $this->form_validation->set_rules('mouza_pargona_code', 'mouza_pargona_code', 'trim|required');
        $this->form_validation->set_rules('lot_no', 'lot_no', 'trim|required');
        $this->form_validation->set_rules('vill_townprt_code', 'vill_townprt_code', 'trim|required');
        $this->form_validation->set_rules('patta_type_code', 'patta_type_code', 'trim|required');
        $this->form_validation->set_rules('patta_no', 'patta_no', 'trim|required');
        $this->form_validation->set_rules('dag_no', 'dag_no', 'trim|required');

        if ($this->form_validation->run() == false) {
            echo json_encode(array('errors' => validation_errors(), 'st' => 0));
            return;
        }

        $loc['dist_code'] = $dist_code = $this->UtilsModel->cleanPattern($this->input->post('dist_code'));
        $loc['cir_code'] = $cir_code = $this->UtilsModel->cleanPattern($this->input->post('cir_code'));
        $loc['subdiv_code'] = $subdiv_code = $this->UtilsModel->cleanPattern($this->input->post('subdiv_code'));
        $loc['mouza_pargona_code'] = $mouza_pargona_code = $this->UtilsModel->cleanPattern($this->input->post('mouza_pargona_code'));
        $loc['lot_no'] = $lot_no = $this->UtilsModel->cleanPattern($this->input->post('lot_no'));
        $loc['vill_townprt_code'] = $vill_townprt_code = $this->UtilsModel->cleanPattern($this->input->post('vill_townprt_code'));
        $loc['patta_type_code'] = $patta_type_code = $this->UtilsModel->cleanPattern($this->input->post('patta_type_code'));
        $loc['patta_no'] = $patta_no = $this->UtilsModel->cleanPattern($this->input->post('patta_no'));
        $loc['dag_no'] = $dag_no = $this->UtilsModel->cleanPattern($this->input->post('dag_no'));

        $q = "select * from nc_villages where dist_code=? AND subdiv_code=? AND cir_code=?
        AND mouza_Pargona_code=? AND lot_No=? AND vill_townprt_code=?";
        $nc_village = $this->db->query($q, array(
            $dist_code,
            $subdiv_code,
            $cir_code,
            $mouza_pargona_code,
            $lot_no,
            $vill_townprt_code,
        ))->row();

        if (!$nc_village) {
            $govtdags = $this->DagReportModel->getGovtDags($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code);

            /** save if village not found in nc_villages table */
            $application = $this->saveNcVillagesAndDags($loc, $govtdags, $patta_type_code, $patta_no, $dag_no);

            $dag_from_new_table = $this->CommonModel->getNcVillageDags($application['application_no']);

            echo json_encode(array('data' => $dag_from_new_table, 'application_no' => $application['application_no'], 'lm_verified' => null, 'st' => 1));

            return;
        } else {
            /** Update if village found in nc_villages table */
            $application = $this->updateNcVillageDags($loc, $patta_type_code, $patta_no, $dag_no, $nc_village->application_no);

            $dag_from_new_table = $this->CommonModel->getNcVillageDags($application['application_no']);

            if ($application['submitted'] == 'Y') {
                echo json_encode(array('data' => $dag_from_new_table, 'application_no' => $application['application_no'], 'lm_verified' => $nc_village->lm_verified, 'st' => 1));
            } else {
                echo json_encode(array('data' => $dag_from_new_table, 'application_no' => $application['application_no'], 'lm_verified' => $nc_village->lm_verified, 'st' => 0));
            }

            return;
        }
    }

    /** save nc villages and dags */
    public function saveNcVillagesAndDags($loc, $govtDags, $patta_type_code, $patta_no, $dag_no)
    {
        $this->dbswitch();

        $application_no = $this->CommonModel->genearteCaseNo(SERVICE_CODE_SVAMITVA);

        $user_code = $this->session->userdata('user_code');

        $dist_code = $loc['dist_code'];
        $subdiv_code = $loc['subdiv_code'];
        $cir_code = $loc['cir_code'];
        $mouza_pargona_code = $loc['mouza_pargona_code'];
        $lot_no = $loc['lot_no'];
        $vill_townprt_code = $loc['vill_townprt_code'];
        $uuid = $this->db->query("select uuid from location where dist_code='$dist_code' and subdiv_code='$subdiv_code'
        and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no'
        and vill_townprt_code='$vill_townprt_code'")->row()->uuid;

        /** save Nc Villages **/
        $this->db->trans_begin();
        $nc_village_details = array(
            'dist_code' => $loc['dist_code'],
            'subdiv_code' => $loc['subdiv_code'],
            'cir_code' => $loc['cir_code'],
            'mouza_pargona_code' => $loc['mouza_pargona_code'],
            'lot_no' => $loc['lot_no'],
            'vill_townprt_code' => $loc['vill_townprt_code'],
            'application_no' => $application_no['case_no'],
            'status' => 'E',
            'lm_code' => $user_code,
            'co_code' => null,
            'dc_code' => null,
            'lm_verified' => null,
            'co_verified' => null,
            'dc_verified' => null,
            'pre_user' => null,
            'cu_user' => "LM",
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'chitha_generated_at' => date('Y-m-d H:i:s'),
            'uuid' => $uuid,
        );

        $insert_nc_villages = $this->db->insert('nc_villages', $nc_village_details);
        if (!$insert_nc_villages) {
            log_message("error", 'NC_Village_LM_Save_Village: ' . json_encode('#NC0004 Unable to insert
			data into nc_villages table.'));
        }

        /** save Nc Villages **/
        foreach ($govtDags as $dag) {
            $lm_verify = null;
            if ($dag->dag_no == $dag_no && $dag->patta_no == $patta_no && $dag->patta_type_code == $patta_type_code) {
                $lm_verify = 'Y';
            }
            $nc_village_dags = array(
                'dist_code' => $loc['dist_code'],
                'subdiv_code' => $loc['subdiv_code'],
                'cir_code' => $loc['cir_code'],
                'mouza_pargona_code' => $loc['mouza_pargona_code'],
                'lot_no' => $loc['lot_no'],
                'vill_townprt_code' => $loc['vill_townprt_code'],
                'application_no' => $application_no['case_no'],
                'dag_no' => $dag->dag_no,
                'dag_no_int' => $dag->dag_no_int,
                'dag_area_b' => $dag->dag_area_b,
                'dag_area_g' => $dag->dag_area_g,
                'dag_area_k' => $dag->dag_area_k,
                'dag_area_kr' => $dag->dag_area_kr,
                'dag_area_lc' => $dag->dag_area_lc,
                'patta_type_code' => $dag->patta_type_code,
                'patta_no' => $dag->patta_no,
                'lm_verified' => $lm_verify,
                'co_verified' => null,
                'dc_verified' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );

            $insert_nc_village_dags = $this->db->insert('nc_village_dags', $nc_village_dags);
            if (!$insert_nc_village_dags) {
                log_message("error", 'NC_Village_LM_Save_Dags: ' . json_encode('#NC0003 Unable to insert
			data into nc_village_dags table.'));
            }
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            log_message("error", 'NC_Village_LM_Save: ' . json_encode('#NC0002 Unable to insert data.'));
            return array(
                'application_no' => null,
                'error' => true,
                'msg' => '#NC0002 Unable to insert data.',
            );
        } else {
            $this->db->trans_commit();
            return array(
                'application_no' => $application_no['case_no'],
                'error' => false,
                'msg' => null,
            );
        }
    }

    /** Update nc village dag */
    public function updateNcVillageDags($loc, $patta_type_code, $patta_no, $dag_no, $application_no)
    {
        $this->db->where('dist_code', $loc['dist_code'])
            ->where('subdiv_code', $loc['subdiv_code'])
            ->where('cir_code', $loc['cir_code'])
            ->where('mouza_pargona_code', $loc['mouza_pargona_code'])
            ->where('lot_no', $loc['lot_no'])
            ->where('vill_townprt_code', $loc['vill_townprt_code'])
            ->where('application_no', $application_no)
            ->where('dag_no', $dag_no)
            ->where('patta_type_code', $patta_type_code)
            ->where('patta_no', $patta_no)
            ->update('nc_village_dags', array('lm_verified' => 'Y', 'updated_at' => date('Y-m-d H:i:s'), 'lm_verified_at' => date('Y-m-d H:i:s')));

        if ($this->db->affected_rows() > 0) {
            return array(
                'submitted' => 'Y',
                'application_no' => $application_no,
                'msg' => 'Dag Successfully Verified.',
            );
        } else {
            log_message("error", 'NC_Village_LM_Pass: ' . json_encode('#NC0005 Unable to Submit.' . $application_no));
            return array(
                'submitted' => 'N',
                'application_no' => $application_no,
                'msg' => '#NC0005 Unable to verify dag.',
            );
        }
    }

    /** view Chitha in modal */
    public function generateChitha()
    {
        $this->dbswitch();
        $co_note = array();
        if (isset($_POST['dag_no'])) {
            $case_no = 4;
            //$case_no = $this->input->get('case_no');
            $district_code = $this->session->userdata('dcode');
            $subdivision_code = $this->session->userdata('subdiv_code');
            $circlecode = $this->session->userdata('cir_code');
            //$db = $this->session->userdata('db');

            if (isset($_POST['dag_no'])) {
                $dag = $this->input->post('dag_no');
            }
            if ($case_no == '4') {
                $district_code = $this->input->get('dist_code');
                $subdivision_code = $this->input->get('subdiv_code');
                $circlecode = $this->input->get('cir_code');
                $mouzacode = $this->input->get('mouza_pargona_code');
                $lot_code = $this->input->get('lot_no');
                $village_code = $this->input->get('vill_townprt_code');
                $patta_code = $this->input->get('patta_no');
                $dag_no_lower = $dag . '00';
                $dag_no_upper = $dag . '00';
            }
        }

        $dist_name = $this->utilityclass->getDistrictName($district_code);
        $subdiv_name = $this->utilityclass->getSubDivName($district_code, $subdivision_code);
        $cir_name = $this->utilityclass->getCircleName($district_code, $subdivision_code, $circlecode);
        $mouza_pargona_code_name = $this->utilityclass->getMouzaName($district_code, $subdivision_code, $circlecode, $mouzacode);
        $lot_no = $this->utilityclass->getLotLocationName($district_code, $subdivision_code, $circlecode, $mouzacode, $lot_code);
        $vill_townprt_code_name = $this->utilityclass->getVillageName($district_code, $subdivision_code, $circlecode, $mouzacode, $lot_code, $village_code);

        $data['location'] = array('dist' => $dist_name, 'sub' => $subdiv_name, 'cir' => $cir_name, 'mouza' => $mouza_pargona_code_name, 'lot' => $lot_no, 'vill' => $vill_townprt_code_name);

        $secondSelection = array('patta_code' => $patta_code, 'dag_no_lower' => $dag_no_lower, 'dag_no_upper' => $dag_no_upper);

        $this->load->model('chitha/ChithaModel');
        $pattatype['chithaPattatypeinfo'] = $this->ChithaModel->getpattatype($patta_code);
        $this->session->set_userdata(array('patta_type' => $pattatype['chithaPattatypeinfo'][0]->patta_type));

        if ($patta_code != '0000') {
            if (in_array($this->session->userdata('dcode'), json_decode(BARAK_VALLEY))) {
                $chithainfo1['data'] = $this->ChithaModel->getchithaDetails123Barak($district_code, $subdivision_code, $circlecode, $mouzacode, $lot_code, $village_code, $dag_no_lower, $dag_no_upper, $patta_code);
            } else {
                $chithainfo1['data'] = $this->ChithaModel->getchithaDetails123($district_code, $subdivision_code, $circlecode, $mouzacode, $lot_code, $village_code, $dag_no_lower, $dag_no_upper, $patta_code);
            }
        } else {
            if (in_array($this->session->userdata('dcode'), json_decode(BARAK_VALLEY))) {
                $chithainfo1['data'] = $this->ChithaModel->getchithaDetailsALLBarak($district_code, $subdivision_code, $circlecode, $mouzacode, $lot_code, $village_code, $dag_no_lower, $dag_no_upper);
            } else {
                $chithainfo1['data'] = $this->ChithaModel->getchithaDetailsALL($district_code, $subdivision_code, $circlecode, $mouzacode, $lot_code, $village_code, $dag_no_lower, $dag_no_upper);
            }
        }

        $maindataforchitha = array_merge($data, $secondSelection, $chithainfo1, $pattatype, $co_note);

        echo json_encode(array(
            'assam_schedule_xxxvii_form_no_30' => $this->lang->line('assam_schedule_xxxvii_form_no_30'),
            'chitha_for_surveyed_villages' => $this->lang->line('chitha_for_surveyed_villages'),
            'data' => $maindataforchitha,
        ));
        return;
    }

    /** LM FInal Submit */
    public function lmFinalSubmit()
    {
        $this->dbswitch();
        $this->form_validation->set_rules('dist_code', 'dist_code', 'trim|required');
        $this->form_validation->set_rules('subdiv_code', 'subdiv_code', 'trim|required');
        $this->form_validation->set_rules('cir_code', 'cir_code', 'trim|required');
        $this->form_validation->set_rules('mouza_pargona_code', 'mouza_pargona_code', 'trim|required');
        $this->form_validation->set_rules('lot_no', 'lot_no', 'trim|required');
        $this->form_validation->set_rules('vill_townprt_code', 'vill_townprt_code', 'trim|required');
        $this->form_validation->set_rules('remark', 'remark', 'trim|required');
        $this->form_validation->set_rules('map_id', 'map id', 'trim|required');

        if ($this->form_validation->run() == false) {
            echo json_encode(array('errors' => validation_errors(), 'st' => 0));
            return;
        }

        $loc['dist_code'] = $dist_code = $this->UtilsModel->cleanPattern($this->input->post('dist_code'));
        $loc['cir_code'] = $cir_code = $this->UtilsModel->cleanPattern($this->input->post('cir_code'));
        $loc['subdiv_code'] = $subdiv_code = $this->UtilsModel->cleanPattern($this->input->post('subdiv_code'));
        $loc['mouza_pargona_code'] = $mouza_pargona_code = $this->UtilsModel->cleanPattern($this->input->post('mouza_pargona_code'));
        $loc['lot_no'] = $lot_no = $this->UtilsModel->cleanPattern($this->input->post('lot_no'));
        $loc['vill_townprt_code'] = $vill_townprt_code = $this->UtilsModel->cleanPattern($this->input->post('vill_townprt_code'));
        $loc['remark'] = $remark = $this->UtilsModel->cleanPattern($this->input->post('remark'));

        $dag_verify_check = $this->checkVerifiedDags(
            $dist_code,
            $subdiv_code,
            $cir_code,
            $mouza_pargona_code,
            $lot_no,
            $vill_townprt_code
        );

        if ($dag_verify_check['flag'] == 'Y') {
            $q5 = "select application_no from nc_villages where dist_code=? AND subdiv_code=? AND cir_code=?
        	AND mouza_Pargona_code=? AND lot_No=? AND vill_townprt_code=?";

            $application_no = $this->db->query($q5, array(
                $dist_code,
                $subdiv_code,
                $cir_code,
                $mouza_pargona_code,
                $lot_no,
                $vill_townprt_code,
            ))->row()->application_no;

            $this->db->trans_begin();

            $map_id = $this->input->post('map_id');
            $url2 = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/apiGetMapByMapId";
            $method = 'POST';
            $output2 = callIlrmsApi($url2, $method, ['map_id' => $map_id]);
            $map_data = json_decode(json_encode($output2->data), true);

            $update = $this->db->where('dist_code', $loc['dist_code'])
                ->where('subdiv_code', $subdiv_code)
                ->where('cir_code', $cir_code)
                ->where('mouza_pargona_code', $mouza_pargona_code)
                ->where('lot_no', $lot_no)
                ->where('vill_townprt_code', $vill_townprt_code)
                ->where('application_no', $application_no)
                ->update('nc_villages', array(
                    'case_type' => $map_data['case_type'],
                    'status' => 'S',
                    'pre_user' => 'LM',
                    'cu_user' => 'SK',
                    'updated_at' => date('Y-m-d H:i:s'),
                    'lm_verified' => 'Y',
                    'lm_verified_at' => date('Y-m-d H:i:s'),
                    'lm_note' => trim($remark),
                    'lm_code' => $this->session->userdata('user_code'),
                ));

            if ($this->db->affected_rows() > 0) {
                $insPetProceed = array(
                    'case_no' => $application_no,
                    'proceeding_id' => 1,
                    'date_of_hearing' => date('Y-m-d h:i:s'),
                    'next_date_of_hearing' => date('Y-m-d h:i:s'),
                    'note_on_order' => trim($remark),
                    'status' => 'S',
                    'user_code' => $this->session->userdata('user_code'),
                    'date_entry' => date('Y-m-d h:i:s'),
                    'operation' => 'E',
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'office_from' => 'LM',
                    'office_to' => 'SK',
                    'task' => 'NC Village Verified by LM',
                );
                $insertProceeding = $this->db->insert('settlement_proceeding', $insPetProceed);

                if ($this->db->trans_status() === true) {
                    $map_id = $this->input->post('map_id');
                    $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/apiGetMergeVillageRequests";
                    $method = 'POST';
                    $output = callIlrmsApi($url, $method, ['map_id' => $map_id]);
                    $merge_village_requests = json_decode(json_encode($output->data), true);
                    if (count($merge_village_requests)) {
                        $nc_village = $this->db->where('dist_code', $loc['dist_code'])
                            ->where('subdiv_code', $subdiv_code)
                            ->where('cir_code', $cir_code)
                            ->where('mouza_pargona_code', $mouza_pargona_code)
                            ->where('lot_no', $lot_no)
                            ->where('vill_townprt_code', $vill_townprt_code)
                            ->where('application_no', $application_no)
                            ->get('nc_villages')->row();
                        $exists = $this->db
                            ->where('nc_village_id', $nc_village->id)
                            ->get('merge_village_requests')
                            ->num_rows();
                        if ($exists == 0) {
                            $merge_data = [];
                            foreach ($merge_village_requests as $merge_village_request) {
                                // Check if already exists by nc_village_id

                                $merge_data[] = [
                                    'nc_village_id' => $nc_village->id,
                                    'dist_code' => $merge_village_request['dist_code'],
                                    'subdiv_code' => $merge_village_request['subdiv_code'],
                                    'cir_code' => $merge_village_request['cir_code'],
                                    'mouza_pargona_code' => $merge_village_request['mouza_pargona_code'],
                                    'lot_no' => $merge_village_request['lot_no'],
                                    'vill_townprt_code' => $merge_village_request['vill_townprt_code'],
                                    'uuid' => $merge_village_request['uuid'],
                                    'request_dist_code' => $merge_village_request['request_dist_code'],
                                    'request_subdiv_code' => $merge_village_request['request_subdiv_code'],
                                    'request_cir_code' => $merge_village_request['request_cir_code'],
                                    'request_mouza_pargona_code' => $merge_village_request['request_mouza_pargona_code'],
                                    'request_lot_no' => $merge_village_request['request_lot_no'],
                                    'request_vill_townprt_code' => $merge_village_request['request_vill_townprt_code'],
                                    'request_uuid' => $merge_village_request['request_uuid'],
                                    'created_by' => $this->session->userdata('usercode'),
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s'),
                                ];
                            }

                            $this->db->insert_batch('merge_village_requests', $merge_data);
                        }
                    }

                    //UPDATE MAPS FLAG
                    $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/updateFlag";
                    $method = 'POST';
                    $filter_data = ['map_id' => $map_id, 'flag' => 'C'];
                    $output = callIlrmsApi($url, $method, $filter_data);
                    $response = $output ? $output->status : [];
                }
                if ($this->db->trans_status() === false && $response != '1') {
                    $this->db->trans_rollback();
                    log_message("error", 'NC_Village_LM_Save: ' . json_encode('#NC0006 Unable to insert data.'));

                    echo json_encode(array(
                        'submitted' => 'N',
                        'msg' => '#NC0006 Transactions Failed.',
                    ));
                    return;
                } else {
                    $this->db->trans_commit();
                    echo json_encode(array(
                        'submitted' => 'Y',
                        'msg' => 'Successfully Submitted, Forwarded to ' . SK_LABEL . '.',
                    ));
                    return;
                }
            } else {
                log_message("error", 'NC_Village_LM_Pass: ' . json_encode('#NC0001 Unable to Submit.' . $application_no));

                $this->db->trans_rollback();

                echo json_encode(array(
                    'submitted' => 'N',
                    'msg' => '#NC0001 Unable to Submit.',
                ));
                return;
            }
        } else {
            echo json_encode($dag_verify_check);
            return;
        }
    }

    /** check verified dags */
    public function checkVerifiedDags($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code)
    {
        $q2 = "select count(*) as count from nc_villages where dist_code=? AND subdiv_code=? AND cir_code=?
        AND mouza_Pargona_code=? AND lot_No=? AND vill_townprt_code=? and chitha_dir_path IS NOT NULL";
        $result_check_vill = $this->db->query($q2, array(
            $dist_code,
            $subdiv_code,
            $cir_code,
            $mouza_pargona_code,
            $lot_no,
            $vill_townprt_code,
        ))->row()->count;

        if ($result_check_vill > 0) {
            $q3 = "select count(*) as count from nc_village_dags where dist_code=? AND subdiv_code=? AND cir_code=?
        	AND mouza_Pargona_code=? AND lot_No=? AND vill_townprt_code=? and lm_verified IS NULL";
            $check_unverified_dag = $this->db->query($q3, array(
                $dist_code,
                $subdiv_code,
                $cir_code,
                $mouza_pargona_code,
                $lot_no,
                $vill_townprt_code,
            ))->row()->count;

            if ($check_unverified_dag > 0) {
                $data = array(
                    'dag' => true,
                    'unverified_dag' => true,
                    'msg' => 'Please Verify All Dags.',
                    'flag' => 'N',
                    'submitted' => 'N',
                );
                return $data;
            } else {
                $data = array(
                    'dag' => true,
                    'unverified_dag' => false,
                    'msg' => 'All dags verified.',
                    'flag' => 'Y',
                    'submitted' => 'N',
                );
                return $data;
            }
        } else {
            $data = array(
                'dag' => false,
                'unverified_dag' => true,
                'msg' => 'Please Verify Draft Chitha .',
                'flag' => 'N',
                'submitted' => 'N',
            );
            return $data;
        }
    }

    /** check LM Already submitted */
    public function checkAlreadySubmitted($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code)
    {
        $q4 = "select count(*) as count from nc_villages where dist_code=? AND subdiv_code=? AND cir_code=?
        AND mouza_Pargona_code=? AND lot_No=? AND vill_townprt_code=? AND cu_user=? AND status=?";

        $result_check_submitted = $this->db->query($q4, array(
            $dist_code,
            $subdiv_code,
            $cir_code,
            $mouza_pargona_code,
            $lot_no,
            $vill_townprt_code,
            'LM',
            'E',
        ))->row()->count;

        if ($result_check_submitted == 0) {
            return 'Y';
        } else {
            return 'N';
        }
    }

    /** generate village dags chitha */
    public function generateVillageDagsChitha()
    {
        $this->dbswitch();
        $this->form_validation->set_rules('dist_code', 'dist_code', 'trim|required');
        $this->form_validation->set_rules('subdiv_code', 'subdiv_code', 'trim|required');
        $this->form_validation->set_rules('cir_code', 'cir_code', 'trim|required');
        $this->form_validation->set_rules('mouza_pargona_code', 'mouza_pargona_code', 'trim|required');
        $this->form_validation->set_rules('lot_no', 'lot_no', 'trim|required');
        $this->form_validation->set_rules('vill_townprt_code', 'vill_townprt_code', 'trim|required');

        if ($this->form_validation->run() == false) {
            echo json_encode(array('errors' => validation_errors(), 'st' => 0));
            return;
        }

        $loc['dist_code'] = $district_code = $dist_code = $this->UtilsModel->cleanPattern($this->input->post('dist_code'));
        $loc['cir_code'] = $circlecode = $cir_code = $this->UtilsModel->cleanPattern($this->input->post('cir_code'));
        $loc['subdiv_code'] = $subdivision_code = $subdiv_code = $this->UtilsModel->cleanPattern($this->input->post('subdiv_code'));
        $loc['mouza_pargona_code'] = $mouzacode = $mouza_pargona_code = $this->UtilsModel->cleanPattern($this->input->post('mouza_pargona_code'));
        $loc['lot_no'] = $lot_code = $lot_no = $this->UtilsModel->cleanPattern($this->input->post('lot_no'));
        $loc['vill_townprt_code'] = $village_code = $vill_townprt_code = $this->UtilsModel->cleanPattern($this->input->post('vill_townprt_code'));

        /** @var check data exit or not $q */

        $check_nc_village_dags = 'N';

        $q2 = "select count(*) as count from nc_villages where dist_code=? AND subdiv_code=? AND cir_code=?
        AND mouza_pargona_code=? AND lot_No=? AND vill_townprt_code=?";
        $nc_villages = $this->db->query($q2, array(
            $dist_code,
            $subdiv_code,
            $cir_code,
            $mouza_pargona_code,
            $lot_no,
            $vill_townprt_code,
        ))->row()->count;

        if ($nc_villages > 0) {
            $q = "select count(*) as count from nc_village_dags where dist_code=? AND subdiv_code=? AND cir_code=?
        AND mouza_pargona_code=? AND lot_No=? AND vill_townprt_code=? and lm_verified is null";
            $nc_village_dags = $this->db->query($q, array(
                $dist_code,
                $subdiv_code,
                $cir_code,
                $mouza_pargona_code,
                $lot_no,
                $vill_townprt_code,
            ))->row()->count;

            if ($nc_village_dags == 0) {
                $check_nc_village_dags = 'Y';
            }
        }

        $file_name = $district_code . '_' . $subdivision_code . '_' . $circlecode . '_' . $mouzacode . '_' . $lot_code . '_' . $village_code;

        if (file_exists(FCPATH . NC_VILLAGE_CHITHA_PDF_DIR . $file_name . '/' . $file_name . '.pdf')) {
            if ($nc_villages > 0) {
                $this->db->where('dist_code', $loc['dist_code'])
                    ->where('subdiv_code', $subdiv_code)
                    ->where('cir_code', $cir_code)
                    ->where('mouza_pargona_code', $mouza_pargona_code)
                    ->where('lot_no', $lot_no)
                    ->where('vill_townprt_code', $vill_townprt_code)
                    ->update('nc_villages', array(
                        'chitha_dir_path' => NC_VILLAGE_CHITHA_PDF_DIR . $file_name . '/' . $file_name . '.pdf',
                        'chitha_generated_at' => date('Y-m-d H:i:s'),
                    ));
            }

            echo json_encode(
                array(
                    'upload' => true,
                    'file_name' => $file_name,
                    'msg' => 'Already exists',
                    'lm_verified' => $check_nc_village_dags,
                )
            );
            return;
        }

        /** @var To do $patta_code */

        $dag_no_max_min = $this->db->query(
            "select min(dag_no_int) as min_dag_no, max(dag_no_int) as max_dag_no from chitha_basic_nc",
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
        $mpdf->Output(FCPATH . NC_VILLAGE_CHITHA_PDF_DIR . $file_name . '/' . $file_name . '.pdf', 'F');

        if (file_exists(FCPATH . NC_VILLAGE_CHITHA_PDF_DIR . $file_name . '/' . $file_name . '.pdf')) {
            echo json_encode(
                array(
                    'upload' => true,
                    'file_name' => $file_name,
                    'msg' => null,
                    'lm_verified' => $check_nc_village_dags,
                )
            );
            return;
        } else {
            echo json_encode(
                array(
                    'upload' => false,
                    'file_name' => null,
                    'msg' => 'Something went wrong.',
                    'lm_verified' => $check_nc_village_dags,
                )
            );
            return;
        }
    }

    /** verify dags chitha and save */
    public function verifyDagsChithaAndSave()
    {
        $this->dbswitch();
        $this->form_validation->set_rules('dist_code', 'dist_code', 'trim|required');
        $this->form_validation->set_rules('subdiv_code', 'subdiv_code', 'trim|required');
        $this->form_validation->set_rules('cir_code', 'cir_code', 'trim|required');
        $this->form_validation->set_rules('mouza_pargona_code', 'mouza_pargona_code', 'trim|required');
        $this->form_validation->set_rules('lot_no', 'lot_no', 'trim|required');
        $this->form_validation->set_rules('vill_townprt_code', 'vill_townprt_code', 'trim|required');
        $this->form_validation->set_rules('chitha_total_dag', 'chitha_total_dag', 'trim|required');
        $this->form_validation->set_rules('chitha_total_area', 'chitha_total_area', 'trim|required');
        $this->form_validation->set_rules('bhunaksa_total_dag', 'bhunaksa_total_dag', 'trim|required');
        $this->form_validation->set_rules('bhunaksa_total_area', 'bhunaksa_total_area', 'trim|required');

        if ($this->form_validation->run() == false) {
            echo json_encode(array('errors' => validation_errors(), 'st' => 0));
            return;
        }

        $loc['dist_code'] = $dist_code = $this->UtilsModel->cleanPattern($this->input->post('dist_code'));
        $loc['subdiv_code'] = $subdiv_code = $this->UtilsModel->cleanPattern($this->input->post('subdiv_code'));
        $loc['cir_code'] = $cir_code = $this->UtilsModel->cleanPattern($this->input->post('cir_code'));
        $loc['mouza_pargona_code'] = $mouza_pargona_code = $this->UtilsModel->cleanPattern($this->input->post('mouza_pargona_code'));
        $loc['lot_no'] = $lot_no = $this->UtilsModel->cleanPattern($this->input->post('lot_no'));
        $loc['vill_townprt_code'] = $vill_townprt_code = $this->UtilsModel->cleanPattern($this->input->post('vill_townprt_code'));
        $loc['chitha_total_dag'] = $chitha_total_dag = $this->UtilsModel->cleanPattern($this->input->post('chitha_total_dag'));
        $loc['chitha_total_area'] = $chitha_total_area = $this->UtilsModel->cleanPattern($this->input->post('chitha_total_area'));
        $loc['bhunaksa_total_dag'] = $bhunaksa_total_dag = $this->UtilsModel->cleanPattern($this->input->post('bhunaksa_total_dag'));
        $loc['bhunaksa_total_area'] = $bhunaksa_total_area = $this->UtilsModel->cleanPattern($this->input->post('bhunaksa_total_area'));


        $q = "select * from nc_villages where dist_code=? AND subdiv_code=? AND cir_code=?
        AND mouza_Pargona_code=? AND lot_No=? AND vill_townprt_code=?";
        $nc_village = $this->db->query($q, array(
            $dist_code,
            $subdiv_code,
            $cir_code,
            $mouza_pargona_code,
            $lot_no,
            $vill_townprt_code,
        ))->row();

        $q2 = "select * from chitha_basic_nc where dist_code=? AND subdiv_code=? AND cir_code=?
        AND mouza_Pargona_code=? AND lot_No=? AND vill_townprt_code=?";

        $chitha_basic_nc_check = $this->db->query($q2, array(
            $dist_code,
            $subdiv_code,
            $cir_code,
            $mouza_pargona_code,
            $lot_no,
            $vill_townprt_code,
        ))->row();

        if (!$chitha_basic_nc_check) {
            echo json_encode(array('data' => null, 'application_no' => null, 'error' => 'YY', 'lm_verified' => 'N', 'st' => 1));
            return;
        }

        if (!$nc_village) {
            //            $govtdags = $this->DagReportModel->getGovtDags($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code);
            $govtdags = $this->DagReportModel->getGovtDagsFromChithaBasicNc($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code);

            /** save if village not found in nc_villages table */
            $application = $this->saveOrVerifiedNcVillagesAndDags($loc, $govtdags, $nc_data = 'N', $application_no = null);

            $error = 'N';
            $lm_verified = 'Y';
            if ($application['error'] == true) {
                $error = 'Y';
                $lm_verified = 'N';
            }

            $dag_from_new_table = $this->CommonModel->getNcVillageDags($application['application_no']);
            foreach ($dag_from_new_table as $dag) {
                $dag->occupiers = $this->DagReportModel->occupierNames($dag->dist_code, $dag->subdiv_code, $dag->cir_code, $dag->mouza_pargona_code, $dag->lot_no, $dag->vill_townprt_code, $dag->dag_no);
            }
            echo json_encode(array('data' => $dag_from_new_table, 'application_no' => $application['application_no'], 'error' => $error, 'lm_verified' => $lm_verified, 'st' => 1));

            return;
        } else {

            $dags = $this->CommonModel->getNcVillageDags($nc_village->application_no);

            $application = $this->saveOrVerifiedNcVillagesAndDags($loc, $dags, $nc_data = 'Y', $nc_village->application_no);

            $error = 'N';
            $lm_verified = 'Y';
            if ($application['error'] == true) {
                $error = 'Y';
                $lm_verified = 'N';
            }
            $dag_from_new_table = $this->CommonModel->getNcVillageDags($nc_village->application_no);
            foreach ($dag_from_new_table as $dag) {
                $dag->occupiers = $this->DagReportModel->occupierNames($dag->dist_code, $dag->subdiv_code, $dag->cir_code, $dag->mouza_pargona_code, $dag->lot_no, $dag->vill_townprt_code, $dag->dag_no);
            }
            echo json_encode(array('data' => $dag_from_new_table, 'application_no' => $nc_village->application_no, 'error' => $error, 'lm_verified' => $lm_verified, 'st' => 1));

            return;
        }
    }

    /** Re generate Chitha and save */
    public function regenerateChitha()
    {
        $this->dbswitch();
        $this->form_validation->set_rules('dist_code', 'dist_code', 'trim|required');
        $this->form_validation->set_rules('subdiv_code', 'subdiv_code', 'trim|required');
        $this->form_validation->set_rules('cir_code', 'cir_code', 'trim|required');
        $this->form_validation->set_rules('mouza_pargona_code', 'mouza_pargona_code', 'trim|required');
        $this->form_validation->set_rules('lot_no', 'lot_no', 'trim|required');
        $this->form_validation->set_rules('vill_townprt_code', 'vill_townprt_code', 'trim|required');

        if ($this->form_validation->run() == false) {
            echo json_encode(array('errors' => validation_errors(), 'st' => 0));
            return;
        }
        $case_type = $this->input->post('case_type');
        $merge_with_c_village = $this->input->post('merge_with_c_village');

        $loc['dist_code'] = $district_code = $dist_code = $this->UtilsModel->cleanPattern($this->input->post('dist_code'));
        $loc['cir_code'] = $circlecode = $cir_code = $this->UtilsModel->cleanPattern($this->input->post('cir_code'));
        $loc['subdiv_code'] = $subdivision_code = $subdiv_code = $this->UtilsModel->cleanPattern($this->input->post('subdiv_code'));
        $loc['mouza_pargona_code'] = $mouzacode = $mouza_pargona_code = $this->UtilsModel->cleanPattern($this->input->post('mouza_pargona_code'));
        $loc['lot_no'] = $lot_code = $lot_no = $this->UtilsModel->cleanPattern($this->input->post('lot_no'));
        $loc['vill_townprt_code'] = $village_code = $vill_townprt_code = $this->UtilsModel->cleanPattern($this->input->post('vill_townprt_code'));

        $check_chitha_basic_nc = $this->DagReportModel->checkChithaBasicNc($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code);
        if (!$check_chitha_basic_nc) {
            $insert_bhunaksa_dags = $this->DagReportModel->insertBhunaksadags($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $case_type, $merge_with_c_village);
            if ($insert_bhunaksa_dags['code'] == 500) {
                echo json_encode(array('errors' => $insert_bhunaksa_dags['message'], 'st' => 0));
                return;
            }
        } else {
            $update_chitha_basic_nc = $this->DagReportModel->updateChithaBasicNc($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code);
        }

        $dag_no_max_min = $this->db->query(
            "select min(dag_no_int) as min_dag_no, max(dag_no_int) as max_dag_no from chitha_basic_nc",
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
        ini_set("pcre.backtrack_limit", "5000000000");
        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '-1');
        include 'vendor/mpdf/vendor/autoload.php';
        $mpdf = new \Mpdf\Mpdf([
            'default_font_size' => 9,
            'default_font' => 'dejavusans',
            'orientation' => 'P',
            'format' => 'A4',
        ]);

        $file_name = $district_code . '_' . $subdivision_code . '_' . $circlecode . '_' . $mouzacode . '_' . $lot_code . '_' . $village_code;

        $dir_path = FCPATH . NC_VILLAGE_CHITHA_PDF_DIR . $file_name;
        if (!is_dir($dir_path)) {
            mkdir(FCPATH . NC_VILLAGE_CHITHA_PDF_DIR . $file_name, 0777, true);
        }

        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        $mpdf->writeHTML($content);
        header('Content-type: application/pdf');
        ob_clean();
        $mpdf->Output(FCPATH . NC_VILLAGE_CHITHA_PDF_DIR . $file_name . '/' . $file_name . '.pdf', 'F');

        if (file_exists(FCPATH . NC_VILLAGE_CHITHA_PDF_DIR . $file_name . '/' . $file_name . '.pdf')) {

            $nc_village = $this->db->query(
                "select * from nc_villages where
			dist_code=? AND subdiv_code=? AND cir_code=?
        	AND mouza_Pargona_code=? AND lot_No=? AND vill_townprt_code=?",
                array(
                    $dist_code,
                    $subdiv_code,
                    $cir_code,
                    $mouza_pargona_code,
                    $lot_code,
                    $vill_townprt_code,
                )
            )->row();

            if (!$nc_village) {
                echo json_encode(array(
                    'update' => true,
                    'upload' => true,
                    'file_name' => $file_name,
                    'msg' => '',
                    'lm_verified' => 'N',
                ));
                return;
            }

            $application_no = $nc_village->application_no;

            $this->db->where('application_no', $application_no);
            $this->db->delete('nc_village_dags');

            /** re save dags  */
            //            $govtdags = $this->DagReportModel->getGovtDags($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_code, $vill_townprt_code);
            $govtdags = $this->DagReportModel->getGovtDagsFromChithaBasicNc($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_code, $vill_townprt_code);

            foreach ($govtdags as $dag) {
                $lm_verify = null;
                $nc_village_dags = array(
                    'dist_code' => $loc['dist_code'],
                    'subdiv_code' => $loc['subdiv_code'],
                    'cir_code' => $loc['cir_code'],
                    'mouza_pargona_code' => $loc['mouza_pargona_code'],
                    'lot_no' => $loc['lot_no'],
                    'vill_townprt_code' => $loc['vill_townprt_code'],
                    'application_no' => $application_no,
                    'dag_no' => $dag->dag_no,
                    'dag_no_int' => $dag->dag_no_int,
                    'dag_area_b' => $dag->dag_area_b,
                    'dag_area_g' => $dag->dag_area_g,
                    'dag_area_k' => $dag->dag_area_k,
                    'dag_area_kr' => $dag->dag_area_kr,
                    'dag_area_lc' => $dag->dag_area_lc,
                    'patta_type_code' => $dag->patta_type_code,
                    'patta_no' => $dag->patta_no,
                    'lm_verified' => $lm_verify,
                    'co_verified' => null,
                    'dc_verified' => null,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );

                $insert_nc_village_dags = $this->db->insert('nc_village_dags', $nc_village_dags);

                if (!$insert_nc_village_dags) {
                    log_message("error", 'NC_Village_LM_Re_Save_Dags: ' . json_encode('#NC0083 Unable to re insert
					data into nc_village_dags table.'));
                }
            }

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                log_message("error", 'NC_Village_LM_Re_Save: ' . json_encode('#NC00082 Unable to insert data.'));

                echo json_encode(array(
                    'update' => false,
                    'upload' => true,
                    'file_name' => $file_name,
                    'msg' => '#NC00082 Unable to re insert data.',
                    'lm_verified' => 'N',
                ));
                return;
            } else {
                $this->db->trans_commit();
                echo json_encode(array(
                    'update' => true,
                    'upload' => true,
                    'file_name' => $file_name,
                    'msg' => '',
                    'lm_verified' => 'N',
                ));
                return;
            }
        } else {
            echo json_encode(
                array(
                    'update' => false,
                    'upload' => false,
                    'file_name' => null,
                    'msg' => 'Something went wrong.',
                    'lm_verified' => 'N',
                )
            );
            return;
        }
    }

    /** Save or Verified NC Village and dags */
    public function saveOrVerifiedNcVillagesAndDags($loc, $dags, $nc_data, $application_no = null)
    {
        $user_code = $this->session->userdata('user_code');

        /** save Nc Villages **/
        if ($nc_data == 'N') {
            $this->db->trans_begin();
            $application_no = $this->CommonModel->genearteCaseNo(SERVICE_CODE_SVAMITVA);
            $file_name = $loc['dist_code'] . '_' . $loc['subdiv_code'] . '_' . $loc['cir_code'] . '_' . $loc['mouza_pargona_code'] . '_' . $loc['lot_no'] . '_' . $loc['vill_townprt_code'];

            $chitha_dir_path = null;
            if (file_exists(FCPATH . NC_VILLAGE_CHITHA_PDF_DIR . $file_name . '/' . $file_name . '.pdf')) {
                $chitha_dir_path = NC_VILLAGE_CHITHA_PDF_DIR . $file_name . '/' . $file_name . '.pdf';
            }
            $dist_code = $loc['dist_code'];
            $subdiv_code = $loc['subdiv_code'];
            $cir_code = $loc['cir_code'];
            $mouza_pargona_code = $loc['mouza_pargona_code'];
            $lot_no = $loc['lot_no'];
            $vill_townprt_code = $loc['vill_townprt_code'];
            $uuid = $this->db->query("select uuid from location where dist_code='$dist_code' and subdiv_code='$subdiv_code'
        and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no'
        and vill_townprt_code='$vill_townprt_code'")->row()->uuid;

            $nc_village_details = array(
                'dist_code' => $loc['dist_code'],
                'subdiv_code' => $loc['subdiv_code'],
                'cir_code' => $loc['cir_code'],
                'mouza_pargona_code' => $loc['mouza_pargona_code'],
                'lot_no' => $loc['lot_no'],
                'vill_townprt_code' => $loc['vill_townprt_code'],
                'application_no' => $application_no['case_no'],
                'status' => 'E',
                'lm_code' => $user_code,
                'co_code' => null,
                'dc_code' => null,
                'chitha_dir_path' => $chitha_dir_path,
                'map_dir_path' => null,
                'co_verified' => null,
                'dc_verified' => null,
                'pre_user' => null,
                'cu_user' => "LM",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'chitha_generated_at' => date('Y-m-d H:i:s'),
                'uuid' => $uuid,
                'chitha_total_dag' => trim($loc['chitha_total_dag']),
                'chitha_total_area_skm' => trim($loc['chitha_total_area']),
                'bhunaksa_total_dag' => trim($loc['bhunaksa_total_dag']),
                'bhunaksa_total_area_skm' => trim($loc['bhunaksa_total_area']),
            );

            $insert_nc_villages = $this->db->insert('nc_villages', $nc_village_details);
            if (!$insert_nc_villages) {
                log_message("error", 'NC_Village_LM_Save_Village: ' . json_encode('#NC0012 Unable to insert
			data into nc_villages table.'));
            }

            /** save Nc Villages **/
            foreach ($dags as $dag) {
                $nc_village_dags = array(
                    'dist_code' => $loc['dist_code'],
                    'subdiv_code' => $loc['subdiv_code'],
                    'cir_code' => $loc['cir_code'],
                    'mouza_pargona_code' => $loc['mouza_pargona_code'],
                    'lot_no' => $loc['lot_no'],
                    'vill_townprt_code' => $loc['vill_townprt_code'],
                    'application_no' => $application_no['case_no'],
                    'dag_no' => $dag->dag_no,
                    'dag_no_int' => $dag->dag_no_int,
                    'dag_area_b' => $dag->dag_area_b,
                    'dag_area_g' => $dag->dag_area_g,
                    'dag_area_k' => $dag->dag_area_k,
                    'dag_area_kr' => $dag->dag_area_kr,
                    'dag_area_lc' => $dag->dag_area_lc,
                    'patta_type_code' => $dag->patta_type_code,
                    'patta_no' => $dag->patta_no,
                    'lm_verified' => 'Y',
                    'co_verified' => null,
                    'dc_verified' => null,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'lm_verified_at' => date('Y-m-d H:i:s'),

                );

                $insert_nc_village_dags = $this->db->insert('nc_village_dags', $nc_village_dags);
                if (!$insert_nc_village_dags) {
                    log_message("error", 'NC_Village_LM_Save_Dags: ' . json_encode('#NC0011 Unable to insert
			data into nc_village_dags table.'));
                }
            }
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                log_message("error", 'NC_Village_LM_Save: ' . json_encode('#NC0010 Unable to insert data.'));
                return array(
                    'application_no' => $application_no['case_no'],
                    'error' => true,
                    'msg' => '#NC0010 Unable to insert data.',
                );
            } else {
                $this->db->trans_commit();
                return array(
                    'application_no' => $application_no['case_no'],
                    'error' => false,
                    'msg' => null,
                );
            }
        } else if ($nc_data == 'Y') {

            $this->db->trans_begin();
            /** Update area */
            $this->db->where('dist_code', $loc['dist_code'])
                ->where('subdiv_code', $loc['subdiv_code'])
                ->where('cir_code', $loc['cir_code'])
                ->where('mouza_pargona_code', $loc['mouza_pargona_code'])
                ->where('lot_no', $loc['lot_no'])
                ->where('vill_townprt_code', $loc['vill_townprt_code'])
                ->where('application_no', $application_no)
                ->update(
                    'nc_villages',
                    array(
                        'chitha_total_dag' => trim($loc['chitha_total_dag']),
                        'chitha_total_area_skm' => trim($loc['chitha_total_area']),
                        'bhunaksa_total_dag' => trim($loc['bhunaksa_total_dag']),
                        'bhunaksa_total_area_skm' => trim($loc['bhunaksa_total_area'])
                    )
                );

            if ($this->db->affected_rows() == 0) {
                $this->db->trans_rollback();
                log_message("error", 'NC_Village_LM_Update: ' . json_encode('#NC0017 Unable to update data.'));
                return array(
                    'application_no' => $application_no,
                    'error' => true,
                    'msg' => '#NC0017 Unable to update data.',
                );
            }

            /** Update dags */
            foreach ($dags as $dag) {
                $this->db->where('dist_code', $loc['dist_code'])
                    ->where('subdiv_code', $loc['subdiv_code'])
                    ->where('cir_code', $loc['cir_code'])
                    ->where('mouza_pargona_code', $loc['mouza_pargona_code'])
                    ->where('lot_no', $loc['lot_no'])
                    ->where('vill_townprt_code', $loc['vill_townprt_code'])
                    ->where('dag_no', $dag->dag_no)
                    ->where('patta_type_code', $dag->patta_type_code)
                    ->where('patta_no', $dag->patta_no)
                    ->update('nc_village_dags', array('lm_verified' => 'Y', 'updated_at' => date('Y-m-d H:i:s'), 'lm_verified_at' => date('Y-m-d H:i:s')));

                if ($this->db->affected_rows() == 0) {
                    $this->db->trans_rollback();
                    log_message("error", 'NC_Village_LM_Save: ' . json_encode('#NC0014 Unable to insert data.'));
                    return array(
                        'application_no' => $application_no,
                        'error' => true,
                        'msg' => '#NC0014 Unable to insert data.',
                    );
                }
            }

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                log_message("error", 'NC_Village_LM_Update: ' . json_encode('#NC0010 Unable to update data.'));
                return array(
                    'application_no' => $application_no,
                    'error' => true,
                    'msg' => '#NC0010 Unable to update data.',
                );
            } else {
                $this->db->trans_commit();
                return array(
                    'application_no' => $application_no,
                    'error' => false,
                    'msg' => null,
                );
            }
        }
    }

    public function revertedVillages()
    {
        $this->dbswitch();
        $data['_view'] = 'nc_village/lm/nc_villages_reverted';
        $this->load->view('layout/layout', $data);
    }

    public function getVillagesH()
    {
        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no = $this->session->userdata('lot_no');

        $this->dataswitch();
        $query = "select l.loc_name,l.vill_townprt_code,ncv.lm_verified_at,ncv.status,ncv.application_no,ncv.lm_note,ncv.sk_note,ncv.case_type from nc_villages ncv join location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code
                 and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and ncv.vill_townprt_code = l.vill_townprt_code";

        $query = $query . " where ncv.dist_code='$dist_code' and ncv.subdiv_code='$subdiv_code' and ncv.cir_code='$cir_code' and ncv.mouza_pargona_code = '$mouza_pargona_code' and ncv.lot_no = '$lot_no' and ncv.status='U' and ncv.pre_user='SK' and ncv.cu_user='LM'";

        $result = $this->db->query($query)->result();

        echo json_encode($result);
    }
    /** get nc villages */
    public function pendingVillages()
    {
        $this->dbswitch();
        $dist = $this->session->userdata('dcode');
        $subdiv = $this->session->userdata('subdiv_code');
        $circle = $this->session->userdata('cir_code');
        $mouza = $this->session->userdata('mouza_pargona_code');
        $lot = $this->session->userdata('lot_no');

        //MAPS COUNT FROM ILRMS
        $url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/apiGetMaps";
        $method = 'POST';
        $output = callIlrmsApi($url, $method, ['d' => $dist, 's' => $subdiv, 'c' => $circle, 'm' => $mouza, 'l' => $lot, 'filter_flag_is' => 'B']);
        $maps = $output ? $output->data : [];
        foreach ($maps as $key => $map) {
            $mouza = $this->CommonModel->getLocations($map->dist_code, $map->subdiv_code, $map->cir_code, $map->mouza_pargona_code);
            $lot = $this->CommonModel->getLocations($map->dist_code, $map->subdiv_code, $map->cir_code, $map->mouza_pargona_code, $map->lot_no);
            $village = $this->CommonModel->getLocations($map->dist_code, $map->subdiv_code, $map->cir_code, $map->mouza_pargona_code, $map->lot_no, $map->vill_townprt_code);

            $map->mouza_name = $mouza ? $mouza['mouza']['loc_name'] : '';
            $map->lot_name = $lot ? $lot['lot']['loc_name'] : '';
            $map->village_name = $village ? $village['village']['loc_name'] : '';
            $map->proceed_url = base_url() . 'index.php/nc_village/NcVillageLmController/ncVillages/' . $map->vill_townprt_code . '/' . $map->case_type;
            unset($maps[$key]->jds_remark);
            unset($maps[$key]->ads_note);
        }
        $map_output = $output ? $output->data : [];
        $data['maps'] = json_encode($map_output);

        $data['_view'] = 'nc_village/lm/villages_maps';
        $this->load->view('layout/layout', $data);
    }
    /** View Map */
    public function viewUploadedMap()
    {
        if ($this->session->userdata('usertype') != 3) {
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

    /** getChithaNBhunaksa */
    public function getChithaNBhunaksa($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $case_type, $merge_with_c_village = null)
    {
        try {
            //sq meter
            $b = 1337.803776;
            $k = 267.5607552;
            $l = 13.37803776;

            $query = $this->db->select('count(*) as total_dag, SUM(dag_area_b) as total_bigha,
                SUM(dag_area_k) as total_katha,SUM(dag_area_lc) as total_lessa')
                ->where(array(
                    'dist_code' => $dist_code,
                    'subdiv_code' => $subdiv_code,
                    'cir_code' => $cir_code,
                    'mouza_pargona_code' => $mouza_pargona_code,
                    'lot_no' => $lot_no,
                    'vill_townprt_code' => $vill_townprt_code
                ))
                ->get('chitha_basic_nc')->row();
            if ($query->total_dag == '0') {
                $query = $this->db->select('count(*) as total_dag, SUM(dag_area_b) as total_bigha,
                SUM(dag_area_k) as total_katha,SUM(dag_area_lc) as total_lessa')
                    ->where(array(
                        'dist_code' => $dist_code,
                        'subdiv_code' => $subdiv_code,
                        'cir_code' => $cir_code,
                        '
                    mouza_pargona_code' => $mouza_pargona_code,
                        'lot_no' => $lot_no,
                        'vill_townprt_code' => $vill_townprt_code
                    ))
                    ->get('chitha_basic')->row();
            }
            $bigha_sq_m = $query->total_bigha * $b;
            $katha_sq_m = $query->total_katha * $k;
            $lessa_sq_m = $query->total_lessa * $l;

            if ($case_type == 'NC_TO_C') {
                $url = LANDHUB_BASE_URL_NEW . "BhunakshaApiController/getVillageDagDetails";
                $method = 'POST';
                $data['location'] = $merge_with_c_village['dist_code'] . '_' . $merge_with_c_village['subdiv_code'] . '_' . $merge_with_c_village['cir_code'] . '_' .
                    $merge_with_c_village['mouza_pargona_code'] . '_' . $merge_with_c_village['lot_no'] . '_' . $merge_with_c_village['vill_townprt_code'];

                $api_output = $this->NcVillageModel->callApiV2($url, $method, $data);

                if (!$api_output) {
                    log_message("error", 'LAND HUB API FAIL LMController');
                    throw new Exception('Failed to fetch data from Landhub API (getVillageDagDetails).');
                }
                $api_output = json_decode($api_output);

                $chitha_dags = $this->db->query("select dag_no from chitha_basic_nc where dist_code=? and subdiv_code=? and cir_code=? and mouza_pargona_code=? and lot_no=? and vill_townprt_code=?", [
                    $dist_code,
                    $subdiv_code,
                    $cir_code,
                    $mouza_pargona_code,
                    $lot_no,
                    $vill_townprt_code
                ])->result_array();

                if (count($chitha_dags) == 0) {
                    $chitha_dags = $this->db->query("select dag_no from chitha_basic where dist_code=? and subdiv_code=? and cir_code=? and mouza_pargona_code=? and lot_no=? and vill_townprt_code=?", [
                        $dist_code,
                        $subdiv_code,
                        $cir_code,
                        $mouza_pargona_code,
                        $lot_no,
                        $vill_townprt_code
                    ])->result_array();
                }

                $totalArea = 0;

                if (!empty($api_output->plotInfo)) {
                    foreach ($chitha_dags as $chitha_dag) {
                        foreach ($api_output->plotInfo as $bhunaksha_dag_raw) {
                            // Compare dag numbers as string or int as needed
                            if ((string)$chitha_dag['dag_no'] == (string)$bhunaksha_dag_raw->plotNo) {
                                $totalArea += (float)$bhunaksha_dag_raw->plotArea;
                                break;
                            }
                        }
                    }
                }
                $api_output->totalVillageArea = $totalArea;
                $api_output->totalPlots = count($chitha_dags);
            } else {
                $url = LANDHUB_BASE_URL_NEW . "BhunakshaApiController/getVillageInfo";
                $method = 'POST';
                $data['location'] = $dist_code . '_' . $subdiv_code . '_' . $cir_code . '_' .
                    $mouza_pargona_code . '_' . $lot_no . '_' . $vill_townprt_code;

                $api_output = $this->NcVillageModel->callApiV2($url, $method, $data);

                if (!$api_output) {
                    log_message("error", 'LAND HUB API FAIL LMController');
                    throw new Exception('Failed to fetch data from Landhub API (getVillageInfo).');
                }
                $api_output = json_decode($api_output);
            }

            $data['bhunaksa_area_sq_km'] = 0;
            $data['bhunaksa_total_dag'] = 0;
            if (isset($api_output->totalVillageArea)) {
                $bhunaksa_area_sq_km = $api_output->totalVillageArea;
                $data['bhunaksa_area_sq_km'] = $bhunaksa_area_sq_km / 1000000;
                $data['bhunaksa_total_dag'] = $api_output->totalPlots;
            }
            $data['total_sq_km'] = ($bigha_sq_m + $katha_sq_m + $lessa_sq_m) / 1000000;
            $data['total_dags'] = $query->total_dag;
            return $data;
        } catch (\Throwable $e) {
            log_message("error", 'getChithaNBhunaksa Exception: ' . $e->getMessage());
            return [
                'error' => true,
                'message' => 'An error occurred while calculating Chitha and Bhunaksha data: ' . $e->getMessage()
            ];
        }
    }

    public function revertSyncBhuSoftDelete($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code)
    {
        // This method is for developer
        show_404();
        $this->dbswitch();
        $before_sync_dags_with_bhunaksha_logs = $this->db->where('dist_code', $dist_code)
            ->where('subdiv_code', $subdiv_code)
            ->where('cir_code', $cir_code)
            ->where('mouza_pargona_code', $mouza_pargona_code)
            ->where('lot_no', $lot_no)
            ->where('vill_townprt_code', $vill_townprt_code)
            ->order_by('id', 'DESC')
            ->get('before_sync_dags_with_bhunaksha_logs')
            ->result_array();

        if (count($before_sync_dags_with_bhunaksha_logs)) {
            $before_sync_dags_with_bhunaksha_log = $before_sync_dags_with_bhunaksha_logs[0];
            $nc_village = json_decode($before_sync_dags_with_bhunaksha_log['nc_villages_data'], true);
            $chitha_basic_nc = json_decode($before_sync_dags_with_bhunaksha_log['chitha_basic_nc_data'], true);
            $nc_village_dags = json_decode($before_sync_dags_with_bhunaksha_log['nc_village_dags_data'], true);

            $conditions = [
                'dist_code' => $dist_code,
                'subdiv_code' => $subdiv_code,
                'cir_code' => $cir_code,
                'mouza_pargona_code' => $mouza_pargona_code,
                'lot_no' => $lot_no,
                'vill_townprt_code' => $vill_townprt_code,
            ];

            $query = $this->db->where($conditions);
            if ($query->get('nc_villages')->num_rows() > 0) {
                $query->delete('nc_villages');
            }

            $nc_village = $nc_village[0];
            unset($nc_village['id']);
            $this->db->insert('nc_villages', $nc_village);


            if ($query->get('nc_village_dags')->num_rows() > 0) {
                $query->delete('nc_village_dags');
            }

            foreach ($nc_village_dags as $nc_village_dag) {
                unset($nc_village_dag['id']);
                $this->db->insert('nc_village_dags', $nc_village_dag);
            }


            if ($query->get('chitha_basic_nc')->num_rows() > 0) {
                $query->delete('chitha_basic_nc');
            }

            foreach ($chitha_basic_nc as $cb_nc) {
                $this->db->insert('chitha_basic_nc', $cb_nc);
            }

            echo 'Success';
        }
    }
}
