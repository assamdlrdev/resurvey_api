
<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class PartDagController extends CI_Controller
{
    use CommonTrait;
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Api/PartDagModel');
        // $this->load->model('LoginModel');
        // $this->load->model('UserModel');
        // $this->load->helper('security');
        
    }

    public function submitPartDag() {
        $this->load->helper('cookie');
        $authToken = $this->input->cookie('jwt_authorization', TRUE);
        $payload = jwtdecode($authToken);

        header('Content-Type: application/json');
        if ($_SERVER['CONTENT_TYPE'] == 'application/json') {
            $data = json_decode(file_get_contents('php://input', true));
            $msg = null;
            if(empty($payload))
                $msg = "Unauthorized Token,";
            if (!isset($data) || $data == null)
                $msg = $msg . " Missing Parameters,";
            if (!isset($data->api_key) || $data->api_key == null)
                $msg = $msg . " Missing api_key,";
            if (!isset($data->vill_townprt_code) || $data->vill_townprt_code == null)
                $msg = $msg . " Missing Village Code,";
            if (!isset($data->dag_no) || $data->dag_no == null)
                $msg = $msg . " Missing Dag no, ";
            if (!isset($data->part_dag) || $data->part_dag == null)
                $msg = $msg . " Missing Part Dag no, ";
            if (!isset($data->land_class_code) || $data->land_class_code == null)
                $msg = $msg . " Missing Current Land Class";
            // if (!isset($data->area_sm) || $data->area_sm == null)
            //     $msg = $msg . " Missing Area";
            if ($msg != null && !empty($msg)) {
                $response = [
                    'status' => 'n',
                    'msg' => $msg 
                ];
                $this->output->set_status_header(401);  // Change to 400, 401, 500, etc. as needed
                echo json_encode($response);
                exit;
            }

            $apikey = $data->api_key;
            $villageCode = $data->vill_townprt_code;
            $original_dag_no = $data->dag_no;
            $part_dag = $data->part_dag;
            $landClassCode = $data->land_class_code;

            $areaSm = $data->area_sm ? $data->area_sm : 0;
            $dag_land_revenue = $data->dag_land_revenue ? $data->dag_land_revenue : 0;
            $dag_local_tax = $data->dag_local_tax ? $data->dag_local_tax : 0;
            $pattadars = $data->pattadars ? $data->pattadars : [];
        } else {
            $msg = null;
            if(empty($payload))
                $msg = "Unauthorized Token,";
            if (!isset($_POST['api_key']) || empty($_POST['api_key']))
                $msg = $msg . " Missing apikey,";
            if(!isset($_POST['vill_townprt_code']) || empty($_POST['vill_townprt_code']))
                $msg = $msg . " Missing Village Code,";
            if(!isset($_POST['land_class_code']) || empty($_POST['land_class_code']))
                $msg = $msg . " Missing Current Land Class ";
            if(!isset($_POST['dag_no']) || empty($_POST['dag_no']))
                $msg = $msg . " Missing Dag No ";
             if(!isset($_POST['part_dag']) || empty($_POST['part_dag']))
                $msg = $msg . " Missing Part Dag No ";
            if ($msg != null && !empty($msg)) {
                $response = [
                    'status' => 'n',
                    'msg' => $msg 
                ];
                $this->output->set_status_header(401);  // Change to 400, 401, 500, etc. as needed
                echo json_encode($response);
                return;
            }

            $apikey = $_POST['api_key'];
            $villageCode = $_POST['vill_townprt_code'];
            $original_dag_no = $_POST['dag_no'];
            $part_dag = $_POST['part_dag'];
            $landClassCode = $_POST['land_class_code'];


            $areaSm = $_POST['area_sm'] ? $_POST['area_sm'] : 0;
            $dag_land_revenue = $_POST['dag_land_revenue'] ? $_POST['dag_land_revenue'] : 0;
            $dag_local_tax = $_POST['dag_local_tax'] ? $_POST['dag_local_tax'] : 0;
            $pattadars = $_POST['pattadars'] ? $_POST['pattadars'] : [];
            // $user_name = $_POST['user_name'];
            // $password = $_POST['password'];
        }

        $villageCodeArr = explode('-',  $villageCode);
        $dist_code = $villageCodeArr[0];
        $subdiv_code = $villageCodeArr[1];
        $cir_code = $villageCodeArr[2];
        $mouza_pargona_code = $villageCodeArr[3];
        $lot_no = $villageCodeArr[4];
        $vill_townprt_code = $villageCodeArr[5];

        $original_dag_no = $original_dag_no;
        $part_dag = $part_dag;
        $land_class_code = $landClassCode;
        $area_sm = $areaSm;
        $dag_land_revenue = $dag_land_revenue;
        $dag_local_tax = $dag_local_tax;
        $pattadars = $pattadars;


        $this->dbswitch($dist_code);

        $checkWhetherMerged = $this->PartDagModel->whetherDharitreeMerged($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $original_dag_no);
        if($checkWhetherMerged['status'] == 'n') {
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($checkWhetherMerged);
            return;
        }

        $pdParams = $this->PartDagModel->alphaDagParams($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $part_dag);

        $partDagParams = $pdParams['data'];
        $alpha_dag = $partDagParams['alpha_dag'];
        $dag_no_int = $partDagParams['dag_no_int'];

        //dag_area_validation
        $totalDagArea = $this->PartDagModel->getTotalDagArea($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $original_dag_no);
        if(!$totalDagArea) {
            log_message('error', 'Dag could not be found in chitha_basic for '. $dist_code . '-' . $subdiv_code . '-' . $cir_code . '-' . $mouza_pargona_code . '-' . $lot_no . '-' . $vill_townprt_code . '-' . $original_dag_no);
            $response = [
                'status' => 'n',
                'msg' => 'Dag could not be found!' 
            ];
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
        }
        $remainingDagArea = $totalDagArea;

        $enteredAreaTotal = (in_array($dist_code, BARAK_VALLEY)) ? (20 / 4.1806368) * $area_sm : (1 / 13.37803776) * $area_sm;

        if(in_array($dist_code, BARAK_VALLEY)) {
            $bigha = floor($enteredAreaTotal/6400);
            $katha = floor(($enteredAreaTotal - ($bigha * 6400))/320);
            $lessaChatak = floor(($enteredAreaTotal - ($bigha * 6400 + $katha * 320))/20);
            $ganda = number_format($enteredAreaTotal - ($bigha * 6400 + $katha * 320 + $lessaChatak * 20), 4);
            
            $enteredDagArea = $bigha * 6400 + $katha * 320 + $lessaChatak * 20 + $ganda;
        }
        else {
            $bigha = floor($enteredAreaTotal/100);
            $katha = floor(($enteredAreaTotal - ($bigha * 100))/20);
            $lessaChatak = $enteredAreaTotal - ($bigha * 100 + $katha * 20);
            $ganda = '0';

            $enteredDagArea = $bigha * 100 + $katha * 20 + $lessaChatak;
        }
        $entered_total_dag_area = $enteredDagArea * (100 / 747.45);

        if($enteredDagArea > $remainingDagArea) {
            log_message('error', 'Part Dag Area cannot be greater than the total remaining dag area. Entered Total Area: '. $enteredDagArea . ', Total Remaining Dag Area: ' . $remainingDagArea);
            $response = [
                'status' => 'n',
                'msg' => 'Part Dag Area cannot be greater than the total remaining dag area!' 
            ];
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
        }

        $checkNewDag = $this->PartDagModel->checkExistingDag($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $part_dag);
        if($checkNewDag['status'] == 'n') {
            log_message('error', $checkNewDag['msg']);
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($checkNewDag);
            return;
        }

        $getChithaDetails = $this->PartDagModel->getChithaDagDetails($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $original_dag_no);//issue in case not synced with dharitree

        if(empty($getChithaDetails)) {
            log_message('error', 'Splitted From dag could not be found in chitha_basic for ' . $dist_code . '-' . $subdiv_code . '-' . $cir_code . '-' . $mouza_pargona_code . '-' . $lot_no . '-' . $vill_townprt_code . '-' . $original_dag_no);
            $response = [
                'status' => 'n',
                'msg' => 'Splitted From dag could not be found!' 
            ];
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
        }

        if(in_array($dist_code, BARAK_VALLEY)) {
            $chithaBasicArea = $getChithaDetails->dag_area_b * 6400 + $getChithaDetails->dag_area_k * 320 + $getChithaDetails->dag_area_lc * 20 + $getChithaDetails->dag_area_g;
        }
        else {
            $chithaBasicArea = $getChithaDetails->dag_area_b * 100 + $getChithaDetails->dag_area_k * 20 + $getChithaDetails->dag_area_lc;
        }

        $partDagArea = $this->PartDagModel->partDagTotalArea($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $original_dag_no);

        $originalArea = $chithaBasicArea + $partDagArea;

        if(in_array($dist_code, BARAK_VALLEY)) {
            $o_bigha = floor($originalArea / 6400);
            $o_katha = floor(($originalArea - ($o_bigha * 6400))/320);
            $o_lessa = floor(($originalArea - ($o_bigha * 6400 + $o_katha * 320))/20);
            $o_ganda = number_format($originalArea - ($o_bigha * 6400 + $o_katha * 320 + $o_lessa * 20), 2);
        }
        else {
            $o_bigha = floor($originalArea / 100);
            $o_katha = floor(($originalArea - ($o_bigha * 100))/20);
            $o_lessa = number_format($originalArea - ($o_bigha * 100 + $o_katha * 20), 2);
            $o_ganda = '0';
        }

        // echo '<pre>';
        // var_dump($pattadars);
        // die;

        $this->db->trans_begin();

        // insert into chitha_basic_splitted_dags
        $insertChithaSplittedArr = [
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'mouza_pargona_code' => $mouza_pargona_code,
            'lot_no' => $lot_no,
            'vill_townprt_code' => $vill_townprt_code,
            'dag_no' => $getChithaDetails->dag_no,
            'dag_no_int' => $dag_no_int,
            'old_dag_no' => $getChithaDetails->old_dag_no,
            'patta_type_code' => $getChithaDetails->patta_type_code,
            'patta_no' => $getChithaDetails->patta_no,
            'land_class_code' => $land_class_code,
            'dag_area_b' => $bigha,
            'dag_area_k' => $katha,
            'dag_area_lc' => $lessaChatak,
            'dag_area_g' => $ganda,
            'dag_area_are' => $entered_total_dag_area,
            'dag_revenue' => $dag_land_revenue,
            'dag_local_tax' => $dag_local_tax,
            'dag_n_desc' => $getChithaDetails->dag_n_desc,
            'dag_s_desc' => $getChithaDetails->dag_s_desc,
            'dag_e_desc' => $getChithaDetails->dag_e_desc,
            'dag_w_desc' => $getChithaDetails->dag_w_desc,
            'dag_n_dag_no' => $getChithaDetails->dag_n_dag_no,
            'dag_s_dag_no' => $getChithaDetails->dag_s_dag_no,
            'dag_e_dag_no' => $getChithaDetails->dag_e_dag_no,
            'dag_w_dag_no' => $getChithaDetails->dag_w_dag_no,
            'dag_area_kr' => $getChithaDetails->dag_area_kr,
            'dag_nlrg_no' => (!empty($dag_nlrg_no)) ? $dag_nlrg_no : '',
            'dp_flag_yn' => $getChithaDetails->dp_flag_yn,
            'user_code' => $payload->usercode,
            'date_entry' => date("Y-m-d | h:i:sa"),
            'old_patta_no' => $getChithaDetails->old_patta_no,
            'jama_yn' => $getChithaDetails->jama_yn,
            'survey_no' => $part_dag,
            'operation' => 'E',
            'status' => $getChithaDetails->status,
            'zonal_value' => $getChithaDetails->zonal_value,
            'police_station' => $getChithaDetails->police_station ? $getChithaDetails->police_station : '',
            'revenue_paid_upto' => $getChithaDetails->revenue_paid_upto ? $getChithaDetails->revenue_paid_upto : '',
            'block_code' => $getChithaDetails->block_code ? $getChithaDetails->block_code : '',
            'gp_code' => $getChithaDetails->gp_code ? $getChithaDetails->gp_code : '',
            'o_bigha' => $o_bigha,
            'o_katha' => $o_katha,
            'o_lessa' => $o_lessa,
            'o_ganda' => $o_ganda,
            'alpha_dag' => $alpha_dag,
            'dag_area_sqmtr' => $area_sm
        ];
        $insertChithaSplittedStatus = $this->db->insert('chitha_basic_splitted_dags', $insertChithaSplittedArr);
        if(!$insertChithaSplittedStatus || $this->db->affected_rows() < 1) {
            $this->db->trans_rollback();
            log_message('error', 'Error in insertion in chitha_basic_splitted_dags for dag_no: '. $original_dag_no);
            $response = [
                'status' => 'n',
                'msg' => 'Insertion Error in Chitha Basic Part Dags!'
            ];
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
        }

        //chitha_basic insert            
        $insertChithaArr = [
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'mouza_pargona_code' => $mouza_pargona_code,
            'lot_no' => $lot_no,
            'vill_townprt_code' => $vill_townprt_code,
            'dag_no' => $part_dag,
            'dag_no_int' => $dag_no_int,
            'alpha_dag' => $alpha_dag,
            'old_dag_no' => $getChithaDetails->old_dag_no,
            'patta_type_code' => $getChithaDetails->patta_type_code,
            'patta_no' => $getChithaDetails->patta_no,
            'land_class_code' => $land_class_code,
            'dag_area_b' => $bigha,
            'dag_area_k' => $katha,
            'dag_area_lc' => $lessaChatak,
            'dag_area_g' => $ganda,
            'dag_area_are' => $entered_total_dag_area,
            'dag_revenue' => $dag_land_revenue,
            'dag_local_tax' => $dag_local_tax,
            'dag_n_desc' => $getChithaDetails->dag_n_desc,
            'dag_s_desc' => $getChithaDetails->dag_s_desc,
            'dag_e_desc' => $getChithaDetails->dag_e_desc,
            'dag_w_desc' => $getChithaDetails->dag_w_desc,
            'dag_n_dag_no' => $getChithaDetails->dag_n_dag_no,
            'dag_s_dag_no' => $getChithaDetails->dag_s_dag_no,
            'dag_e_dag_no' => $getChithaDetails->dag_e_dag_no,
            'dag_w_dag_no' => $getChithaDetails->dag_w_dag_no,
            'dag_area_kr' => $getChithaDetails->dag_area_kr,
            'dag_nlrg_no' => (!empty($dag_nlrg_no)) ? $dag_nlrg_no : '',
            'dp_flag_yn' => $getChithaDetails->dp_flag_yn,
            'user_code' => $payload->usercode,
            'date_entry' => date("Y-m-d | h:i:sa"),
            'old_patta_no' => $getChithaDetails->old_patta_no,
            'jama_yn' => $getChithaDetails->jama_yn,
            'operation' => 'E',
            'status' => $getChithaDetails->status,
            'zonal_value' => $getChithaDetails->zonal_value,
            'police_station' => $getChithaDetails->police_station ? $getChithaDetails->police_station: '',
            'revenue_paid_upto' => $getChithaDetails->revenue_paid_upto ? $getChithaDetails->revenue_paid_upto : '',
            'block_code' => $getChithaDetails->block_code ? $getChithaDetails->block_code : '',
            'gp_code' => $getChithaDetails->gp_code ? $getChithaDetails->gp_code : ''
        ];
        $insertChithaStatus = $this->db->insert('chitha_basic', $insertChithaArr);
        if(!$insertChithaStatus || $this->db->affected_rows() < 1) {
            $this->db->trans_rollback();
            log_message('error', 'Error in insertion in chitha_basic for part_dag_no: '. $part_dag);
            $response = [
                'status' => 'n',
                'msg' => 'Insertion Error in Chitha Basic!'
            ];
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
        }

        //update previous dag area
        $left_out_area = $remainingDagArea - $enteredDagArea;
        if(in_array($dist_code, BARAK_VALLEY)) {
            $updated_area_bigha = floor($left_out_area / 6400);
            $updated_area_katha = floor(($left_out_area - ($updated_area_bigha * 6400))/320);
            $updated_area_lessa = floor(($left_out_area - ($updated_area_bigha * 6400 + $updated_area_katha * 320))/20);
            $updated_area_ganda = number_format($left_out_area - ($updated_area_bigha * 6400 + $updated_area_katha * 320 + $updated_area_lessa * 20), 2);
        }
        else {
            $updated_area_bigha = floor($left_out_area / 100);
            $updated_area_katha = floor(($left_out_area - ($updated_area_bigha * 100))/20);
            $updated_area_lessa = number_format($left_out_area - ($updated_area_bigha * 100 + $updated_area_katha * 20), 2);
            $updated_area_ganda = '0';
        }
        $updateChithaPreviousDagArr = [
            'dag_area_b' => $updated_area_bigha,
            'dag_area_k' => $updated_area_katha,
            'dag_area_lc' => $updated_area_lessa,
            'dag_area_g' => $updated_area_ganda
        ];
        $this->db->where([
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'mouza_pargona_code' => $mouza_pargona_code,
            'lot_no' => $lot_no,
            'vill_townprt_code' => $vill_townprt_code,
            'dag_no' => $original_dag_no,
            'patta_no' => $getChithaDetails->patta_no,
            'patta_type_code' => $getChithaDetails->patta_type_code
        ]);
        $updateChithaPreviousDagStatus = $this->db->update('chitha_basic', $updateChithaPreviousDagArr);
        if(!$updateChithaPreviousDagStatus || $this->db->affected_rows() < 1) {
            $this->db->trans_rollback();
            log_message('error', 'Error in updation in chitha_basic for dag_no: '. $original_dag_no);
            $response = [
                'status' => 'n',
                'msg' => 'Updation Error in Chitha Basic!'
            ];
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
        }

        // 08-01-05-01-01-10002-0-0209-1

        // insert into chitha_dag_pattadar
        if(!empty($pattadars) && count($pattadars) > 0) {
            foreach($pattadars as $pattadar) {
                $pattadarArr = explode('-', $pattadar->value);
                $dist = $pattadarArr[0];
                $subdiv = $pattadarArr[1];
                $cir = $pattadarArr[2];
                $mouza = $pattadarArr[3];
                $lot = $pattadarArr[4];
                $vill = $pattadarArr[5];
                $patta_no = $pattadarArr[6];
                $patta_type_code = $pattadarArr[7];
                $pdar_id = $pattadarArr[8];

                // $patta_no = $pattadarArr[0];
                // $patta_type_code = $pattadarArr[1];
                // $pdar_id = $pattadarArr[2];

                $chithaDagPattadar = $this->db->query("SELECT cdp.*, cp.pdar_name, cp.pdar_father FROM chitha_dag_pattadar cdp, chitha_pattadar cp WHERE cdp.dist_code=cp.dist_code AND cdp.subdiv_code=cp.subdiv_code AND cdp.cir_code=cp.cir_code AND cdp.mouza_pargona_code=cp.mouza_pargona_code AND cdp.lot_no=cp.lot_no AND cdp.vill_townprt_code=cp.vill_townprt_code AND cdp.patta_no=cp.patta_no AND cdp.patta_type_code=cp.patta_type_code AND cdp.pdar_id=cp.pdar_id AND cdp.dist_code=? AND cdp.subdiv_code=? AND cdp.cir_code=? AND cdp.mouza_pargona_code=? AND cdp.lot_no=? AND cdp.vill_townprt_code=? AND cdp.dag_no=? AND cdp.patta_no=? AND cdp.patta_type_code=? AND cdp.pdar_id=?", [$dist, $subdiv, $cir, $mouza, $lot, $vill, $original_dag_no, $patta_no, $patta_type_code, $pdar_id])->row();

                $insertChithaDagPattadarArr = [
                    'dist_code' => $dist,
                    'subdiv_code' => $subdiv,
                    'cir_code' => $cir,
                    'mouza_pargona_code' => $mouza,
                    'lot_no' => $lot,
                    'vill_townprt_code' => $vill,
                    'dag_no' => $part_dag,
                    'pdar_id' => $pdar_id,
                    'patta_no' => $chithaDagPattadar->patta_no,
                    'patta_type_code' => $chithaDagPattadar->patta_type_code,
                    'dag_por_b' => '0',
                    'dag_por_k' => '0',
                    'dag_por_lc' => '0',
                    'dag_por_g' => '0',
                    'pdar_land_n' => $chithaDagPattadar->pdar_land_n,
                    'pdar_land_s' => $chithaDagPattadar->pdar_land_s,
                    'pdar_land_e' => $chithaDagPattadar->pdar_land_e,
                    'pdar_land_w' => $chithaDagPattadar->pdar_land_w,
                    'pdar_land_acre' => $chithaDagPattadar->pdar_land_acre,
                    'pdar_land_revenue' => $chithaDagPattadar->pdar_land_revenue,
                    'pdar_land_localtax' => $chithaDagPattadar->pdar_land_localtax,
                    'user_code' => $payload->usercode,
                    'date_entry' => date("Y-m-d | h:i:sa"),
                    'operation' => 'E',
                    'p_flag' => $chithaDagPattadar->p_flag,
                    'jama_yn' => 'n',
                ];
                
                $insertChithaDagPattadarStatus = $this->db->insert('chitha_dag_pattadar', $insertChithaDagPattadarArr);
                if(!$insertChithaDagPattadarStatus || $this->db->affected_rows() < 1) {
                    $this->db->trans_rollback();
                    log_message('error', 'Insertion failed in chitha_dag_pattadar table for dag no: '. $part_dag . ', pdar_id: '. $pdar_id);
                    $response = [
                        'status' => 'n',
                        'msg' => 'Insertion Error in Chitha Dag Pattadar!'
                    ];
                    $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
                    echo json_encode($response);
                    return;
                }
            }
        }

        if(!$this->db->trans_status()) {
            $this->db->trans_rollback();
            log_message('error', 'DB Transaction Failed');
            $response = [
                'status' => 'n',
                'msg' => 'DB Transaction Failed!'
            ];
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
            // echo json_encode([
            //     'status' => 'FAILED',
            //     'responseType' => 1,
            //     'msg' => 'DB Transaction Failed'
            // ]);
            // exit;
        } 

        $this->db->trans_commit();

        $response = [
            'status' => 'y',
            'msg' => 'Successfully created Part Dag!'
        ];
        $this->output->set_status_header(200);  // Change to 400, 401, 500, etc. as needed
        echo json_encode($response);
        return;
    }

    public function getTenants() {
        $this->load->helper('cookie');
        $authToken = $this->input->cookie('jwt_authorization', TRUE);
        $payload = jwtdecode($authToken);

        header('Content-Type: application/json');
        if ($_SERVER['CONTENT_TYPE'] == 'application/json') {
            $data = json_decode(file_get_contents('php://input', true));
            $msg = null;
            if(empty($payload))
                $msg = "Unauthorized Token,";
            if (!isset($data) || $data == null)
                $msg = $msg . " Missing Parameters,";
            if (!isset($data->api_key) || $data->api_key == null)
                $msg = $msg . " Missing api_key,";
            if (!isset($data->vill_townprt_code) || $data->vill_townprt_code == null)
                $msg = $msg . " Missing Village Code,";
            if (!isset($data->dag_no) || $data->dag_no == null)
                $msg = $msg . " Missing Dag no, ";
            // if (!isset($data->part_dag) || $data->part_dag == null)
            //     $msg = $msg . " Missing Part Dag no, ";
            // if (!isset($data->land_class_code) || $data->land_class_code == null)
            //     $msg = $msg . " Missing Current Land Class";
            // if (!isset($data->area_sm) || $data->area_sm == null)
            //     $msg = $msg . " Missing Area";
            if ($msg != null && !empty($msg)) {
                $response = [
                    'status' => 'n',
                    'msg' => $msg 
                ];
                $this->output->set_status_header(401);  // Change to 400, 401, 500, etc. as needed
                echo json_encode($response);
                exit;
            }

            $apikey = $data->api_key;
            $villageCode = $data->vill_townprt_code;
            $original_dag_no = $data->dag_no;
        } else {
            $msg = null;
            if(empty($payload))
                $msg = "Unauthorized Token,";
            if (!isset($_POST['api_key']) || empty($_POST['api_key']))
                $msg = $msg . " Missing apikey,";
            if(!isset($_POST['vill_townprt_code']) || empty($_POST['vill_townprt_code']))
                $msg = $msg . " Missing Village Code,";
            if(!isset($_POST['dag_no']) || empty($_POST['dag_no']))
                $msg = $msg . " Missing Dag No ";
            if ($msg != null && !empty($msg)) {
                $response = [
                    'status' => 'n',
                    'msg' => $msg 
                ];
                $this->output->set_status_header(401);  // Change to 400, 401, 500, etc. as needed
                echo json_encode($response);
                return;
            }

            $apikey = $_POST['api_key'];
            $villageCode = $_POST['vill_townprt_code'];
            $original_dag_no = $_POST['dag_no'];
        }

        $villageCodeArr = explode('-',  $villageCode);
        $dist_code = $villageCodeArr[0];
        $subdiv_code = $villageCodeArr[1];
        $cir_code = $villageCodeArr[2];
        $mouza_pargona_code = $villageCodeArr[3];
        $lot_no = $villageCodeArr[4];
        $vill_townprt_code = $villageCodeArr[5];
        $dag_no = $original_dag_no;

        //retrieve tenants
        $data = [];
        $url = LANDHUB_BASE_URL . "/getTenants";
        $method = 'POST';
        $data['location'] = $villageCode;
        $data['dag_no'] = $dag_no;
        $data['apikey'] = "chithaentry_resurvey";
        $api_output = callApiV2($url, $method, $data);

        if (!$api_output) {
            log_message("error", 'LAND HUB API FAIL');
            $response = [
                'status' => 'n',
                'msg' => 'API Failed!' 
            ];
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
        }

        $tenants = json_decode($api_output);

        if($tenants->status !== 'y' || empty($tenants->data)) {
            $response = [
                'status' => 'n',
                'msg' => 'No tenant available!',
                'data' => []
            ];
            $this->output->set_status_header(200);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
        }

        $availableTenants = $tenants->data;
        $payload = [];
        foreach ($availableTenants as $availableTenant) {
            $row = [];
            $row['value'] = $availableTenant->dist_code . '-' . $availableTenant->subdiv_code . '-' . $availableTenant->cir_code . '-' . $availableTenant->mouza_pargona_code . '-' . $availableTenant->lot_no . '-' . $availableTenant->vill_townprt_code . '-' . $availableTenant->dag_no . '-' . $availableTenant->khatian_no . '-' . $availableTenant->tenant_id;
            $row['label'] = $availableTenant->tenant_name . ' (' . $availableTenant->tenants_father . ')';
            $payload[] = $row;
        }

        $response = [
            'status' => 'y',
            'msg' => $tenants->msg,
            'data' => $payload
        ];
        $this->output->set_status_header(200);  // Change to 400, 401, 500, etc. as needed
        echo json_encode($response);
        return;
    }

    public function submitPossessor() {
        $this->load->helper('cookie');
        $authToken = $this->input->cookie('jwt_authorization', TRUE);
        $payload = jwtdecode($authToken);

        header('Content-Type: application/json');
        if ($_SERVER['CONTENT_TYPE'] == 'application/json') {
            $data = json_decode(file_get_contents('php://input', true));
            $msg = null;
            if(empty($payload))
                $msg = "Unauthorized Token,";
            if (!isset($data) || $data == null)
                $msg = $msg . " Missing Parameters,";
            if (!isset($data->api_key) || $data->api_key == null)
                $msg = $msg . " Missing api_key,";
            if (!isset($data->vill_townprt_code) || $data->vill_townprt_code == null)
                $msg = $msg . " Missing Village Code,";
            if (!isset($data->dag_no) || $data->dag_no == null)
                $msg = $msg . " Missing Dag no, ";
            if (!isset($data->part_dag) || $data->part_dag == null)
                $msg = $msg . " Missing Part Dag no, ";
            if (!isset($data->possessor_name) || $data->possessor_name == null)
                $msg = $msg . " Missing Possessor Name, ";
            if (!isset($data->possessor_guardian_name) || $data->possessor_guardian_name == null)
                $msg = $msg . " Missing Possessor Guardian Name, ";
            if (!isset($data->possessor_guardian_relation) || $data->possessor_guardian_relation == null)
                $msg = $msg . " Missing Possessor Guardian Relation ";
            if ($msg != null && !empty($msg)) {
                $response = [
                    'status' => 'n',
                    'msg' => $msg 
                ];
                $this->output->set_status_header(401);  // Change to 400, 401, 500, etc. as needed
                echo json_encode($response);
                exit;
            }

            $apikey = $data->api_key;
            $villageCode = $data->vill_townprt_code;
            $original_dag_no = $data->dag_no;
            $part_dag = $data->part_dag;
            $possessor_name = $data->possessor_name;
            $possessor_guardian_name = $data->possessor_guardian_name;
            $possessor_guardian_relation = $data->possessor_guardian_relation;

            //optional 
            $possessor_pattadar_relation = $data->possessor_pattadar_relation;
            $possessor_mode_of_acquisition = $data->possessor_mode_of_acquisition;
            $possessor_name_mut = $data->possessor_name_mut;
            $possessor_father_name_mut = $data->possessor_father_name_mut;
            $possessor_address_mut = $data->possessor_address_mut;
            $possessor_remark = $data->possessor_remark;

        } else {
            $msg = null;
            if(empty($payload))
                $msg = "Unauthorized Token,";
            if (!isset($_POST['api_key']) || empty($_POST['api_key']))
                $msg = $msg . " Missing apikey,";
            if(!isset($_POST['vill_townprt_code']) || empty($_POST['vill_townprt_code']))
                $msg = $msg . " Missing Village Code,";
            if(!isset($_POST['dag_no']) || empty($_POST['dag_no']))
                $msg = $msg . " Missing Dag No, ";
            if(!isset($_POST['part_dag']) || empty($_POST['part_dag']))
                $msg = $msg . " Missing Part Dag No, ";
            if(!isset($_POST['possessor_name']) || empty($_POST['possessor_name']))
                $msg = $msg . " Missing Possessor Name, ";
            if(!isset($_POST['possessor_guardian_name']) || empty($_POST['possessor_guardian_name']))
                $msg = $msg . " Missing Possessor Guardian Name, ";
            if(!isset($_POST['possessor_guardian_relation']) || empty($_POST['possessor_guardian_relation']))
                $msg = $msg . " Missing Possessor Guardian Relation ";
            if ($msg != null && !empty($msg)) {
                $response = [
                    'status' => 'n',
                    'msg' => $msg 
                ];
                $this->output->set_status_header(401);  // Change to 400, 401, 500, etc. as needed
                echo json_encode($response);
                return;
            }

            $apikey = $_POST['api_key'];
            $villageCode = $_POST['vill_townprt_code'];
            $original_dag_no = $_POST['dag_no'];
            $part_dag = $_POST['part_dag'];
            $possessor_name = $_POST['possessor_name'];
            $possessor_guardian_name = $_POST['possessor_guardian_name'];
            $possessor_guardian_relation = $_POST['possessor_guardian_relation'];

            //optional 
            $possessor_pattadar_relation = $_POST['possessor_pattadar_relation'];
            $possessor_mode_of_acquisition = $_POST['possessor_mode_of_acquisition'];
            $possessor_name_mut = $_POST['possessor_name_mut'];
            $possessor_father_name_mut = $_POST['possessor_father_name_mut'];
            $possessor_address_mut = $_POST['possessor_address_mut'];
            $possessor_remark = $_POST['possessor_remark'];
        }

        // echo '<pre>';
        // var_dump($possessor_pattadar_relation);
        // die;

        $villageCodeArr = explode('-',  $villageCode);
        $dist_code = $villageCodeArr[0];
        $subdiv_code = $villageCodeArr[1];
        $cir_code = $villageCodeArr[2];
        $mouza_pargona_code = $villageCodeArr[3];
        $lot_no = $villageCodeArr[4];
        $vill_townprt_code = $villageCodeArr[5];

        $possessor_pattadar_relation = (isset($possessor_pattadar_relation) && $possessor_pattadar_relation!=='' ) ? $possessor_pattadar_relation : null;
        $possessor_mode_of_acquisition = (isset($possessor_mode_of_acquisition) && $possessor_mode_of_acquisition!=='' ) ? $possessor_mode_of_acquisition : null;
        $possessor_name_mut = (isset($possessor_name_mut) && $possessor_name_mut!=='' ) ? $possessor_name_mut : null;
        $possessor_father_name_mut = (isset($possessor_father_name_mut) && $possessor_father_name_mut!=='' ) ? $possessor_father_name_mut : null;
        $possessor_address_mut = (isset($possessor_address_mut) && $possessor_address_mut!=='' ) ? $possessor_address_mut : null;
        $possessor_remark = (isset($possessor_remark) && $possessor_remark!=='' ) ? $possessor_remark : null;


        $this->dbswitch($dist_code);

        $max_possessor_id = $this->db->query("SELECT MAX(possessor_id) as possessor_id_max FROM splitted_dags_possessors WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND old_dag_no=? AND part_dag=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $original_dag_no, $part_dag])->row()->possessor_id_max;

        if(!$max_possessor_id || empty($max_possessor_id)) {
            $possessor_id = 1;
        }
        else {
            $possessor_id = $max_possessor_id + 1;
        }

        $this->db->trans_begin();

        $insertArr = [
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'mouza_pargona_code' => $mouza_pargona_code,
            'lot_no' => $lot_no,
            'vill_townprt_code' => $vill_townprt_code,
            'old_dag_no' => $original_dag_no,
            'part_dag' => $part_dag,
            'possessor_id' => $possessor_id,
            'name' => $possessor_name,
            'guard_name' => $possessor_guardian_name,
            'guard_relation' => $possessor_guardian_relation,
            'pattadar_relation' => $possessor_pattadar_relation,
            'mode_of_acquisition' => $possessor_mode_of_acquisition,
            'mut_possessor_name' => $possessor_name_mut,
            'mut_possessor_father_name' => $possessor_father_name_mut,
            'mut_possessor_address' => $possessor_address_mut,
            'remarks' => $possessor_remark,
            'user_code' => $payload->usercode,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $insertStatus = $this->db->insert('splitted_dags_possessors', $insertArr);

        if(!$insertStatus || $this->db->affected_rows() < 1) {
            log_message('error', 'Could not be inserted into splitted_dags_possessors!');
            $this->db->trans_rollback();
            $response = [
                'status' => 'n',
                'msg' => 'Could not be added!' 
            ];
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
        }

        if(!$this->db->trans_status()) {
            log_message('error', 'DB Transaction Failed!');
            $this->db->trans_rollback();
            $response = [
                'status' => 'n',
                'msg' => 'DB Transaction Failed!' 
            ];
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
        }

        $this->db->trans_commit();

        $response = [
            'status' => 'y',
            'msg' => 'Successfully added possessor!'
        ];
        $this->output->set_status_header(200);  // Change to 400, 401, 500, etc. as needed
        echo json_encode($response);
        return;
    }

    public function deletePossessor() {
        $this->load->helper('cookie');
        $authToken = $this->input->cookie('jwt_authorization', TRUE);
        $payload = jwtdecode($authToken);

        header('Content-Type: application/json');
        if ($_SERVER['CONTENT_TYPE'] == 'application/json') {
            $data = json_decode(file_get_contents('php://input', true));
            $msg = null;
            if(empty($payload))
                $msg = "Unauthorized Token,";
            if (!isset($data) || $data == null)
                $msg = $msg . " Missing Parameters,";
            if (!isset($data->api_key) || $data->api_key == null)
                $msg = $msg . " Missing api_key,";
            if (!isset($data->possessor) || $data->possessor == null)
                $msg = $msg . " Missing Possessor,";
            if ($msg != null && !empty($msg)) {
                $response = [
                    'status' => 'n',
                    'msg' => $msg 
                ];
                $this->output->set_status_header(401);  // Change to 400, 401, 500, etc. as needed
                echo json_encode($response);
                exit;
            }

            $apikey = $data->api_key;
            $possessor = $data->possessor;

        } else {
            $msg = null;
            if(empty($payload))
                $msg = "Unauthorized Token,";
            if (!isset($_POST['api_key']) || empty($_POST['api_key']))
                $msg = $msg . " Missing apikey,";
            if(!isset($_POST['possessor']) || empty($_POST['possessor']))
                $msg = $msg . " Missing Possessor,";
            if ($msg != null && !empty($msg)) {
                $response = [
                    'status' => 'n',
                    'msg' => $msg 
                ];
                $this->output->set_status_header(401);  // Change to 400, 401, 500, etc. as needed
                echo json_encode($response);
                return;
            }

            $apikey = $_POST['api_key'];
            $possessor = $_POST['possessor'];
        }

        $possessorArr = explode('-', $possessor);
        $dist_code = $possessorArr[0];
        $subdiv_code = $possessorArr[1];
        $cir_code = $possessorArr[2];
        $mouza_pargona_code = $possessorArr[3];
        $lot_no = $possessorArr[4];
        $vill_townprt_code = $possessorArr[5];
        $old_dag_no = $possessorArr[6];
        $part_dag = $possessorArr[7];
        $possessor_id = $possessorArr[8];

        $this->dbswitch($dist_code);

        $deleteArr = [
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'mouza_pargona_code' => $mouza_pargona_code,
            'lot_no' => $lot_no,
            'vill_townprt_code' => $vill_townprt_code,
            'old_dag_no' => $old_dag_no,
            'part_dag' => $part_dag,
            'possessor_id' => $possessor_id
        ];
        $deleteStatus = $this->db->delete('splitted_dags_possessors', $deleteArr);
        if(!$deleteStatus || $this->db->affected_rows() < 1) {
            $response = [
                'status' => 'n',
                'msg' => 'Could not delete possessor!' 
            ];
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
        }

        $response = [
            'status' => 'y',
            'msg' => 'Successfully deleted possessor!' 
        ];
        $this->output->set_status_header(200);  // Change to 400, 401, 500, etc. as needed
        echo json_encode($response);
        return;
    }

    public function updatePartDag() {
        $this->load->helper('cookie');
        $authToken = $this->input->cookie('jwt_authorization', TRUE);
        $payload = jwtdecode($authToken);

        header('Content-Type: application/json');
        if ($_SERVER['CONTENT_TYPE'] == 'application/json') {
            $data = json_decode(file_get_contents('php://input', true));
            $msg = null;
            if(empty($payload))
                $msg = "Unauthorized Token,";
            if (!isset($data) || $data == null)
                $msg = $msg . " Missing Parameters,";
            if (!isset($data->api_key) || $data->api_key == null)
                $msg = $msg . " Missing api_key,";
            if (!isset($data->vill_townprt_code) || $data->vill_townprt_code == null)
                $msg = $msg . " Missing Village Code,";
            if (!isset($data->dag_no) || $data->dag_no == null)
                $msg = $msg . " Missing Dag no, ";
            if (!isset($data->part_dag) || $data->part_dag == null)
                $msg = $msg . " Missing Part Dag no, ";
            if (!isset($data->land_class_code) || $data->land_class_code == null)
                $msg = $msg . " Missing Current Land Class";
            if ($msg != null && !empty($msg)) {
                $response = [
                    'status' => 'n',
                    'msg' => $msg 
                ];
                $this->output->set_status_header(401);  // Change to 400, 401, 500, etc. as needed
                echo json_encode($response);
                exit;
            }

            $apikey = $data->api_key;
            $villageCode = $data->vill_townprt_code;
            $original_dag_no = $data->dag_no;
            $part_dag = $data->part_dag;
            $landClassCode = $data->land_class_code;

            $areaSm = $data->area_sm ? $data->area_sm : 0;
            $dag_land_revenue = $data->dag_land_revenue ? $data->dag_land_revenue : 0;
            $dag_local_tax = $data->dag_local_tax ? $data->dag_local_tax : 0;
            $pattadars = $data->pattadars ? $data->pattadars : [];

        } else {
            $msg = null;
            if(empty($payload))
                $msg = "Unauthorized Token,";
            if (!isset($_POST['api_key']) || empty($_POST['api_key']))
                $msg = $msg . " Missing apikey,";
            if(!isset($_POST['vill_townprt_code']) || empty($_POST['vill_townprt_code']))
                $msg = $msg . " Missing Village Code,";
            if(!isset($_POST['land_class_code']) || empty($_POST['land_class_code']))
                $msg = $msg . " Missing Current Land Class ";
            if(!isset($_POST['dag_no']) || empty($_POST['dag_no']))
                $msg = $msg . " Missing Dag No ";
             if(!isset($_POST['part_dag']) || empty($_POST['part_dag']))
                $msg = $msg . " Missing Part Dag No ";
            if ($msg != null && !empty($msg)) {
                $response = [
                    'status' => 'n',
                    'msg' => $msg 
                ];
                $this->output->set_status_header(401);  // Change to 400, 401, 500, etc. as needed
                echo json_encode($response);
                return;
            }

            $apikey = $_POST['api_key'];
            $villageCode = $_POST['vill_townprt_code'];
            $original_dag_no = $_POST['dag_no'];
            $part_dag = $_POST['part_dag'];
            $landClassCode = $_POST['land_class_code'];


            $areaSm = $_POST['area_sm'] ? $_POST['area_sm'] : 0;
            $dag_land_revenue = $_POST['dag_land_revenue'] ? $_POST['dag_land_revenue'] : 0;
            $dag_local_tax = $_POST['dag_local_tax'] ? $_POST['dag_local_tax'] : 0;
            $pattadars = $_POST['pattadars'] ? $_POST['pattadars'] : [];
        }

        $villageCodeArr = explode('-',  $villageCode);
        $dist_code = $villageCodeArr[0];
        $subdiv_code = $villageCodeArr[1];
        $cir_code = $villageCodeArr[2];
        $mouza_pargona_code = $villageCodeArr[3];
        $lot_no = $villageCodeArr[4];
        $vill_townprt_code = $villageCodeArr[5];

        $this->dbswitch($dist_code);

        $checkPartDag = $this->db->query("SELECT survey_no, dag_area_b, dag_area_k, dag_area_lc, dag_area_g FROM chitha_basic_splitted_dags WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND dag_no=? AND survey_no=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $original_dag_no, $part_dag])->row();

        $checkPartDagChitha = $this->db->query("SELECT dag_no FROM chitha_basic WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND dag_no=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $part_dag])->row();

        if(empty($checkPartDag) || empty($checkPartDagChitha)) {
            $response = [
                'status' => 'n',
                'msg' => 'Part Dag has not been created yet. Could not update!' 
            ];
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
        }

        //dag_area_validation
        $totalDagArea = $this->PartDagModel->getTotalDagArea($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $original_dag_no);
        if(!$totalDagArea) {
            log_message('error', 'Original Dag could not be found in chitha_basic for '. $dist_code . '-' . $subdiv_code . '-' . $cir_code . '-' . $mouza_pargona_code . '-' . $lot_no . '-' . $vill_townprt_code . '-' . $original_dag_no);
            $response = [
                'status' => 'n',
                'msg' => 'Original Dag could not be found!' 
            ];
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
        }
        if(in_array($dist_code, BARAK_VALLEY)) {
            $previousPartDagTotalArea = $checkPartDag->dag_area_b * 6400 + $checkPartDag->dag_area_k * 320 + $checkPartDag->dag_area_lc * 20 + $checkPartDag->dag_area_g;
        }
        else {
            $previousPartDagTotalArea = $checkPartDag->dag_area_b * 100 + $checkPartDag->dag_area_k * 20 + $checkPartDag->dag_area_lc;
        }
        $remainingDagArea = $totalDagArea + $previousPartDagTotalArea;

        $enteredAreaTotal = (in_array($dist_code, BARAK_VALLEY)) ? (20 / 4.1806368) * $areaSm : (1 / 13.37803776) * $areaSm;//sq meter to lessa / ganda

        if(in_array($dist_code, BARAK_VALLEY)) {
            $bigha = floor($enteredAreaTotal/6400);
            $katha = floor(($enteredAreaTotal - ($bigha * 6400))/320);
            $lessaChatak = floor(($enteredAreaTotal - ($bigha * 6400 + $katha * 320))/20);
            $ganda = number_format($enteredAreaTotal - ($bigha * 6400 + $katha * 320 + $lessaChatak * 20), 4);
            
            $enteredDagArea = $bigha * 6400 + $katha * 320 + $lessaChatak * 20 + $ganda;
        }
        else {
            $bigha = floor($enteredAreaTotal/100);
            $katha = floor(($enteredAreaTotal - ($bigha * 100))/20);
            $lessaChatak = $enteredAreaTotal - ($bigha * 100 + $katha * 20);
            $ganda = '0';

            $enteredDagArea = $bigha * 100 + $katha * 20 + $lessaChatak;
        }
        $entered_total_dag_area = $enteredDagArea * (100 / 747.45);

        if($enteredDagArea > $remainingDagArea) {
            log_message('error', 'Part Dag Area cannot be greater than the total remaining dag area. Entered Total Area: '. $enteredDagArea . ', Total Remaining Dag Area: ' . $remainingDagArea);
            $response = [
                'status' => 'n',
                'msg' => 'Part Dag Area cannot be greater than the total remaining dag area!' 
            ];
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
        }

        $this->db->trans_begin();

        $updateArr = [
            'land_class_code' => $landClassCode,
            'dag_area_sqmtr' => $areaSm,
            'dag_revenue' => $dag_land_revenue,
            'dag_local_tax' => $dag_local_tax,
            'dag_area_b' => $bigha,
            'dag_area_k' => $katha,
            'dag_area_lc' => $lessaChatak,
            'dag_area_g' => $ganda,
            'dag_area_are' => $entered_total_dag_area
        ];
        $this->db->where([
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'mouza_pargona_code' => $mouza_pargona_code,
            'lot_no' => $lot_no,
            'vill_townprt_code' => $vill_townprt_code,
            'dag_no' => $original_dag_no,
            'survey_no' => $part_dag
        ]);
        $updStatus = $this->db->update('chitha_basic_splitted_dags', $updateArr);
        if(!$updStatus || $this->db->affected_rows() < 1) {
            $this->db->trans_rollback();
            $response = [
                'status' => 'n',
                'msg' => 'Could not update Part Dag!' 
            ];
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
        }

        $updateChithaArr = [
            'land_class_code' => $landClassCode,
            'dag_revenue' => $dag_land_revenue,
            'dag_local_tax' => $dag_local_tax,
            'dag_area_b' => $bigha,
            'dag_area_k' => $katha,
            'dag_area_lc' => $lessaChatak,
            'dag_area_g' => $ganda,
            'dag_area_are' => $entered_total_dag_area
        ];
        $this->db->where([
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'mouza_pargona_code' => $mouza_pargona_code,
            'lot_no' => $lot_no,
            'vill_townprt_code' => $vill_townprt_code,
            'dag_no' => $part_dag
        ]);
        $updChithaStatus = $this->db->update('chitha_basic', $updateChithaArr);
        if(!$updChithaStatus || $this->db->affected_rows() < 1) {
            $this->db->trans_rollback();
            $response = [
                'status' => 'n',
                'msg' => 'Could not update Part Dag in Chitha!' 
            ];
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
        }


        //pattadar update
        $chithaPattadars = $this->db->query("SELECT * FROM chitha_dag_pattadar WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND dag_no=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $part_dag])->result();

        $available_pattadars = [];
        if(!empty($chithaPattadars)) {
            foreach ($chithaPattadars as $chithaPattadar) {
                $available_pattadars[] = $chithaPattadar->dist_code . '-' . $chithaPattadar->subdiv_code . '-' . $chithaPattadar->cir_code . '-' . $chithaPattadar->mouza_pargona_code . '-' . $chithaPattadar->lot_no . '-' . $chithaPattadar->vill_townprt_code . '-' . $chithaPattadar->patta_no . '-' . $chithaPattadar->patta_type_code . '-' . $chithaPattadar->pdar_id;
            }
        }

        $input_pattadars = [];
        if(!empty($pattadars)) {
            foreach($pattadars as $pdar) {
                $input_pattadars[] = $pdar->value;
            }
        }

        foreach($available_pattadars as $available_pattadar) {
            if(!in_array($available_pattadar, $input_pattadars)) {
                //delete from part dag pattadars
                $availablePdarArr = explode('-', $available_pattadar);
                $d = $availablePdarArr[0];
                $s = $availablePdarArr[1];
                $c = $availablePdarArr[2];
                $m = $availablePdarArr[3];
                $l = $availablePdarArr[4];
                $v = $availablePdarArr[5];
                $patta_no = $availablePdarArr[6];
                $patta_type_code = $availablePdarArr[7];
                $pdar_id = $availablePdarArr[8];

                $deletePdarArr = [
                    'dist_code' => $d,
                    'subdiv_code' => $s,
                    'cir_code' => $c,
                    'mouza_pargona_code' => $m,
                    'lot_no' => $l,
                    'vill_townprt_code' => $v,
                    'patta_no' => $patta_no,
                    'patta_type_code' => $patta_type_code,
                    'dag_no' => $part_dag,
                    'pdar_id' => $pdar_id
                ];

                $deletePdarStatus = $this->db->delete('chitha_dag_pattadar', $deletePdarArr);
                if(!$deletePdarStatus || $this->db->affected_rows() < 1) {
                    log_message('error', 'Could not delete pattadars for the part dag ' . $part_dag);
                    $this->db->trans_rollback();
                    $response = [
                        'status' => 'n',
                        'msg' => 'Could not update Pattadars!' 
                    ];
                    $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
                    echo json_encode($response);
                    return;
                }
            }
        }

        foreach ($input_pattadars as $input_pattadar) {
            if(!in_array($input_pattadar, $available_pattadars)) {
                //entry partdag pattadar
                $inputPdarArr = explode('-', $input_pattadar);
                $d = $inputPdarArr[0];
                $s = $inputPdarArr[1];
                $c = $inputPdarArr[2];
                $m = $inputPdarArr[3];
                $l = $inputPdarArr[4];
                $v = $inputPdarArr[5];
                $patta_no = $inputPdarArr[6];
                $patta_type_code = $inputPdarArr[7];
                $pdar_id = $inputPdarArr[8];

                $chithaDagPattadar = $this->db->query("SELECT cdp.*, cp.pdar_name, cp.pdar_father FROM chitha_dag_pattadar cdp, chitha_pattadar cp WHERE cdp.dist_code=cp.dist_code AND cdp.subdiv_code=cp.subdiv_code AND cdp.cir_code=cp.cir_code AND cdp.mouza_pargona_code=cp.mouza_pargona_code AND cdp.lot_no=cp.lot_no AND cdp.vill_townprt_code=cp.vill_townprt_code AND cdp.patta_no=cp.patta_no AND cdp.patta_type_code=cp.patta_type_code AND cdp.pdar_id=cp.pdar_id AND cdp.dist_code=? AND cdp.subdiv_code=? AND cdp.cir_code=? AND cdp.mouza_pargona_code=? AND cdp.lot_no=? AND cdp.vill_townprt_code=? AND cdp.dag_no=? AND cdp.pdar_id=?", [$d, $s, $c, $m, $l, $v, $original_dag_no, $pdar_id])->row();

                $insertChithaDagPattadarArr = [
                    'dist_code' => $d,
                    'subdiv_code' => $s,
                    'cir_code' => $c,
                    'mouza_pargona_code' => $m,
                    'lot_no' => $l,
                    'vill_townprt_code' => $v,
                    'dag_no' => $part_dag,
                    'pdar_id' => $pdar_id,
                    'patta_no' => $chithaDagPattadar->patta_no,
                    'patta_type_code' => $chithaDagPattadar->patta_type_code,
                    'dag_por_b' => '0',
                    'dag_por_k' => '0',
                    'dag_por_lc' => '0',
                    'dag_por_g' => '0',
                    'pdar_land_n' => $chithaDagPattadar->pdar_land_n,
                    'pdar_land_s' => $chithaDagPattadar->pdar_land_s,
                    'pdar_land_e' => $chithaDagPattadar->pdar_land_e,
                    'pdar_land_w' => $chithaDagPattadar->pdar_land_w,
                    'pdar_land_acre' => $chithaDagPattadar->pdar_land_acre,
                    'pdar_land_revenue' => $chithaDagPattadar->pdar_land_revenue,
                    'pdar_land_localtax' => $chithaDagPattadar->pdar_land_localtax,
                    'user_code' => $payload->usercode,
                    'date_entry' => date("Y-m-d | h:i:sa"),
                    'operation' => 'E',
                    'p_flag' => $chithaDagPattadar->p_flag,
                    'jama_yn' => 'n',
                ];

                $insertPdarStatus = $this->db->insert('chitha_dag_pattadar', $insertChithaDagPattadarArr);
                if(!$insertPdarStatus || $this->db->affected_rows() < 1) {
                    log_message('error', 'Could not insert into chitha_dag_pattadar for the part dag ' . $part_dag);
                    $this->db->trans_rollback();
                    $response = [
                        'status' => 'n',
                        'msg' => 'Could not update Pattadars!' 
                    ];
                    $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
                    echo json_encode($response);
                    return;
                }
            }
        }

        if(!$this->db->trans_status()) {
            $this->db->trans_rollback();
            $response = [
                'status' => 'n',
                'msg' => 'DB Transaction failed!' 
            ];
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
        }

        $this->db->trans_commit();

        $response = [
            'status' => 'y',
            'msg' => 'Successfully updated Part Dag!' 
        ];
        $this->output->set_status_header(200);  // Change to 400, 401, 500, etc. as needed
        echo json_encode($response);
        return;

    }
}