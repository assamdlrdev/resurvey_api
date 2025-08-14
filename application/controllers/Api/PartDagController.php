
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
}