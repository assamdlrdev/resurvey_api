<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class ResurveyDataController extends CI_Controller
{
    use CommonTrait;
    private $jwt_data;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Api/ChithaModel');
        $auth = validate_jwt();
        if (!$auth['status']) {
            $this->output
                ->set_status_header(401)
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => $auth['message']]))
                ->_display();
            exit;
        }

        $this->jwt_data = $auth['data'];
    }

    public function getResurveyMasterData()
    {
        $this->load->helper('cookie');

        $raw_input = file_get_contents("php://input");
        $request_data = json_decode($raw_input);

        if (!$request_data || !isset($request_data->dist_code)) {
            header('Content-Type: application/json');
            echo json_encode([
                "success" => false,
                "message" => "Invalid request: dist_code missing"
            ]);
            return;
        }

        $dist_code = $request_data->dist_code;

        $url = LANDHUB_BASE_URL . "api/resurvey/v1/masterdata";
        $method = 'POST';
        $data = [
            "dist_code" => $dist_code,
            "apikey"    => LANDHUB_APIKEY
        ];

        $api_output = callApiV2($url, $method, $data);

        if (is_string($api_output)) {
            $api_output = json_decode($api_output, true);
        }

        $masterData = [];
        if (isset($api_output['data'])) {
            $masterData = $api_output['data'];
        }
        $transfer_types = [];
        foreach (TRANSFER_TYPES as $key => $value) {
            $transfer_types[] = [
                "value" => $key,
                "label" => $value
            ];
        }
        $masterData['transfer_types'] = $transfer_types;
        $status  = $api_output['status']  ?? null;
        $message = $api_output['msg'] ?? null;

        header('Content-Type: application/json');
        echo json_encode([
            "status"  => $status,
            "message" => $message,
            "data" => $masterData
        ]);
    }


    public function getResurveyDagData()
    {
        $this->load->helper('cookie');

        $request_data = json_decode(file_get_contents('php://input', true));

        $location = $request_data->vill_townprt_code;

        $locationArr = explode('-', $location);

        $dist_code = $locationArr[0];
        $subdiv_code = $locationArr[1];
        $cir_code = $locationArr[2];
        $mouza_pargona_code = $locationArr[3];
        $lot_no = $locationArr[4];
        $vill_townprt_code = $locationArr[5];
        $lgd_code = $locationArr[6];
        $dag_no = $request_data->dag_no;


        header('Content-Type: application/json');
        $data = [];
        $url = LANDHUB_BASE_URL . "api/resurvey/v1/dag-data";
        $method = 'POST';
        $data['dist_code'] = $dist_code;
        $data['subdiv_code'] = $subdiv_code;
        $data['cir_code'] = $cir_code;
        $data['mouza_pargona_code'] = $mouza_pargona_code;
        $data['lot_no'] = $lot_no;
        $data['vill_townprt_code'] = $vill_townprt_code;
        $data['dag_no'] = $dag_no;
        $data['lgd_code'] = $lgd_code;

        $data['apikey'] = LANDHUB_APIKEY;
        $api_output = callApiV2($url, $method, $data);

        $api_data = json_decode($api_output);
        $data = $api_data->data;
        $this->output->set_status_header(200);

        //return response
        $response['dhar_dag'] = $data->chitha_dag;
        $response['dhar_pattadars'] = $data->pattadars;
        $response['dhar_tenants'] = $data->tenants;


        $data = [];
        $url = "/NicApi/PartDags";
        $method = 'POST';
        $data['locationCode'] = $dist_code . '_' . $subdiv_code . '_' . $cir_code . '_' . $mouza_pargona_code . '_' . $lot_no . '_' . $vill_townprt_code;
        $data['oldDagNo'] = $dag_no;

        $api_output = callLandhubAPIWithHeader($method, $url, $data);

        if (empty($api_output) || $api_output['error'] != '' || $api_output['http_status'] != 200) {
            log_message("error", 'LAND HUB API FAIL');
            $response = [
                'status' => 'n',
                'msg' => 'Bhunaksa API PartDags Failed!'
            ];
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            $partDags = [];
            $locationCode = '';
            $inputOldDagNo = '';
            echo json_encode($response);
            exit;
        } else {
            $partDags = $api_output['data']->partDags;
            $locationCode = $api_output['data']->locationCode;
            $inputOldDagNo = $api_output['data']->inputOldDagNo;
        }


        $this->dbswitch($dist_code);

        $partDagsForEntry = [];
        $checkPartDags = [];

        if (!empty($partDags)) {
            foreach ($partDags as $partDag) {
                $part_dag = $partDag->newDagNo;
                $dag_area_sqmtr = $partDag->plotArea;
                $from_bhunaksha = 1;
                //check in chitha whether created
                $chithaPartDag = $this->db->query("SELECT cbsd.*, lcg.name as land_class_name, lcg.name_ass as land_class_name_ass, pc.patta_type FROM chitha_basic_splitted_dags cbsd 
                LEFT JOIN land_class_groups lcg ON cbsd.land_class_code = lcg.land_class_code
                LEFT JOIN patta_code pc ON cbsd.patta_type_code = pc.type_code
                WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND dag_no=? AND survey_no=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag_no, $part_dag])->row();

                if (!empty($chithaPartDag)) {
                    $dag_area_sqmtr = ($chithaPartDag->dag_area_sqmtr && $chithaPartDag->dag_area_sqmtr != '') ? $chithaPartDag->dag_area_sqmtr : $dag_area_sqmtr;
                    $from_bhunaksha = 0;
                }

                $row = [];

                $row['part_dag'] = $part_dag;
                $row['name'] = $part_dag;
                $row['id'] = $part_dag . '-' . $from_bhunaksha;
                $row['from_bhunaksha'] = $from_bhunaksha;
                $row['dag_area_sqmtr'] = $dag_area_sqmtr;
                $row['old_dag_no'] = $chithaPartDag ? $chithaPartDag->dag_no : '';
                $row['current_land_class'] = $chithaPartDag->land_class_name ?? '';
                $row['current_land_class_ass'] = $chithaPartDag->land_class_name_ass ?? '';
                $row['patta_type'] = $chithaPartDag->patta_type ?? '';
                $row['patta_no'] = $chithaPartDag->patta_no ?? '';
                $row['survey_no'] = $chithaPartDag->survey_no ?? '';
                $row['location'] = $location;

                $partDagsForEntry[] = $row;
                $checkPartDags[] = $part_dag;
            }
        }

        $addedPartDags = $this->db->query("SELECT cbsd.*, lcg.name as land_class_name, lcg.name_ass as land_class_name_ass, pc.patta_type FROM chitha_basic_splitted_dags cbsd 
                LEFT JOIN land_class_groups lcg ON cbsd.land_class_code = lcg.land_class_code
                LEFT JOIN patta_code pc ON cbsd.patta_type_code = pc.type_code WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND dag_no=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag_no])->result();

        if (!empty($addedPartDags)) {
            foreach ($addedPartDags as $addedPartDag) {
                if (!in_array(trim($addedPartDag->survey_no), $checkPartDags)) {
                    $row = [];
                    $row['part_dag'] = $addedPartDag->survey_no;
                    $row['name'] = $addedPartDag->survey_no;
                    $row['id'] = $addedPartDag->survey_no . '-0';
                    $row['from_bhunaksha'] = 0;
                    $row['dag_area_sqmtr'] = $addedPartDag->dag_area_sqmtr;
                    $row['old_dag_no'] = $addedPartDag ? $addedPartDag->dag_no : '';
                    $row['current_land_class'] = $addedPartDag->land_class_name ?? '';
                    $row['current_land_class_ass'] = $addedPartDag->land_class_name_ass ?? '';
                    $row['patta_type'] = $addedPartDag->patta_type ?? '';
                    $row['patta_no'] = $addedPartDag->patta_no ?? '';
                    $row['survey_no'] = $addedPartDag->survey_no ?? '';
                    $row['location'] = $location;

                    $partDagsForEntry[] = $row;
                }
            }
        }
        $response['part_dags'] = $partDagsForEntry;
        if(empty($lgd_code)) {
            $response = [
                'status' => 'n',
                'msg' => 'Missing Location lgd!'
            ];
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            exit;
        }

        $requestEpanjeeyanData = [
            // "lgdCode"=> $lgd_code,
            "district_code" => $dist_code,
            "subdivision" => $subdiv_code,
            "circle" => $cir_code,
            "mouza" => $mouza_pargona_code,
            "lot" => $lot_no,
            "village" => $vill_townprt_code,
            "dagno" => $dag_no
            // "dagNo" => $dag_no
        ];

        $requestNgdrsData = [
            "lgdCode"=> $lgd_code,
            "dagNo" => $dag_no
        ];

        $epanjeeyanResp = callEpanjeeyanApi("Epanjeeyan/fetchRecordsurvey", "POST", $requestEpanjeeyanData);
        $ngdrsResp = callNgdrsApi("v1/search_deed.php", "POST", json_encode($requestNgdrsData));
        
        if($ngdrsResp->success) {
            $ngdrsData = $ngdrsResp->data;
            if($ngdrsData->source == 'epanjeeyan') {
                $dhar_ngdrs_deeds = [];
            }
            else if ($ngdrsData->source == 'ngdrs') {
                if(!empty($ngdrsData->data)) {
                    $dhar_ngdrs_deeds = $ngdrsData->data;
                }
                else {
                    $dhar_ngdrs_deeds = [];
                }
            }
            else {
                $dhar_ngdrs_deeds = [];
            }
        }
        else {
            $dhar_ngdrs_deeds = [];
        }


        if($epanjeeyanResp['success'] == 'y' && !empty($epanjeeyanResp['data'])) {
            $dhar_epanjeeyan_deeds = $epanjeeyanResp['data'];
        }
        else {
            $dhar_epanjeeyan_deeds = [];
        }

        $api_dhar_deeds = [];

        if(!empty($dhar_epanjeeyan_deeds)) {
            foreach ($dhar_epanjeeyan_deeds as $deed) {
                $row_deeds['from_epanjeeyan_ngdrs'] = 'epanjeeyan';
                $row_deeds['unique_id'] = $deed->fcaseno . ' & ' . $deed->comcaseno;
                $row_deeds['deed_type'] = $deed->deed_type;
                $row_deeds['registration_date'] = $deed->dtcomple;
                $row_deeds['dbname'] = $deed->dbname;
                $row_deeds['fcaseno'] = $deed->fcaseno;
                $row_deeds['comcaseno'] = $deed->comcaseno;
                $row_deeds['dag_no'] = $deed->dagno;

                $api_dhar_deeds[] = $row_deeds;
            }
        }

        if(!empty($dhar_ngdrs_deeds)) {
            foreach ($dhar_ngdrs_deeds as $deed) {
                $row_deeds['from_epanjeeyan_ngdrs'] = 'ngdrs';
                $row_deeds['unique_id'] = $deed->documentRegistrationNumber . ' & ' . $deed->id;
                $row_deeds['deed_type'] = '';
                $row_deeds['registration_date'] = $deed->documentRegistrationDate;
                $row_deeds['dbname'] = strtolower($deed->district);
                $row_deeds['fcaseno'] = $deed->documentRegistrationNumber;
                $row_deeds['comcaseno'] = $deed->id;
                $row_deeds['dag_no'] = $deed->dagNo;

                $api_dhar_deeds[] = $row_deeds;
            }
        }

        $response['dhar_deeds'] = $api_dhar_deeds;

        echo json_encode([
            'status' => 'y',
            'msg' => 'Successfully retrieved data!',
            'data' => $response
        ]);
        return;
    }

    public function getSurveyNoData()
    {
        $request_data = json_decode(file_get_contents('php://input', true));

        $locationArr = explode('-', $request_data->loc_code);

        $dist_code = $locationArr[0];
        $subdiv_code = $locationArr[1];
        $cir_code = $locationArr[2];
        $mouza_pargona_code = $locationArr[3];
        $lot_no = $locationArr[4];
        $vill_townprt_code = $locationArr[5];
        $lgd_code = $locationArr[6];
        $partDag = $request_data->partDag;
        $bhunaksa_survey_no = $request_data->bhunaksa_survey_no;
        $this->dbswitch($dist_code);

        $partdag = $this->db->query("SELECT cbsd.*, lcg.name as land_class_name, lcg.name_ass as land_class_name_ass, pc.patta_type FROM chitha_basic_splitted_dags cbsd 
                LEFT JOIN land_class_groups lcg ON cbsd.land_class_code = lcg.land_class_code
                LEFT JOIN patta_code pc ON cbsd.patta_type_code = pc.type_code WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND survey_no=? AND bhunaksha_survey_no=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $partDag, $bhunaksa_survey_no])->row();
        $data['part_dag'] = $partdag;
        echo json_encode([
            'status' => 'y',
            'msg' => 'Successfully retrieved data!',
            'data' => $data
        ]);
        return;
    }

    public function getChithaData()
    {
        $request_data = json_decode(file_get_contents('php://input', true));
        $id = $request_data->id;
        $requestDataArr = explode('-', $id);

        $dist_code = $requestDataArr[0];
        $subdiv_code = $requestDataArr[1];
        $cir_code = $requestDataArr[2];
        $mouza_pargona_code = $requestDataArr[3];
        $lot_no = $requestDataArr[4];
        $vill_townprt_code = $requestDataArr[5];
        $lgd_code = $requestDataArr[6];
        $dag_no = $requestDataArr[7];
        $part_dag = $requestDataArr[8];


        $url = LANDHUB_BASE_URL . "api/resurvey/v1/get-location";
        $method = 'POST';
        $params = [
            "dist_code" => $dist_code,
            "subdiv_code" => $subdiv_code,
            "cir_code" => $cir_code,
            "mouza_pargona_code" => $mouza_pargona_code,
            "lot_no" => $lot_no,
            "vill_townprt_code" => $vill_townprt_code,
            "apikey"    => LANDHUB_APIKEY
        ];


        $api_output = callApiV2($url, $method, $params);

        if (is_string($api_output)) {
            $api_output = json_decode($api_output, true);
        }

        $location = [];
        if (isset($api_output['data'])) {
            $location = $api_output['data'];
        }
        $data['location'] = $location;

        $this->dbswitch($dist_code);
        $data['part_dag'] = $this->db->query("SELECT cbsd.dag_no,cbsd.survey_no,cbsd.bhunaksha_survey_no,cbsd.patta_type_code,cbsd.patta_no, cbsd.land_class_code, cbsd.dag_area_b, cbsd.dag_area_k, cbsd.dag_area_lc, cbsd.dag_area_g, cbsd.dag_revenue, cbsd.dag_local_tax,cbsd.dag_area_sqmtr, lcg.name as land_current_use, pc.patta_type FROM chitha_basic_splitted_dags cbsd 
                LEFT JOIN land_class_groups lcg ON cbsd.land_class_code = lcg.land_class_code
                LEFT JOIN patta_code pc ON cbsd.patta_type_code = pc.type_code 
                WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND dag_no=? AND survey_no=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag_no, $part_dag])->row();
        $data['dag'] = $this->db->query("SELECT cb.*, lc.land_type as land_class_old,pc.patta_type as patta_type_old FROM chitha_basic cb 
        LEFT JOIN landclass_code lc ON cb.land_class_code = lc.class_code
        LEFT JOIN patta_code pc ON cb.patta_type_code = pc.type_code
        WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND dag_no=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag_no])->row();

        $data['pattadars'] = $this->db->query("SELECT cdp.*, cp.pdar_name, cp.pdar_father, cp.pdar_add1, cp.pdar_add2, cp.pdar_add3 FROM chitha_dag_pattadar cdp, chitha_pattadar cp WHERE cdp.dist_code=cp.dist_code AND cdp.subdiv_code=cp.subdiv_code AND cdp.cir_code=cp.cir_code AND cdp.mouza_pargona_code=cp.mouza_pargona_code AND cdp.lot_no=cp.lot_no AND cdp.vill_townprt_code=cp.vill_townprt_code AND cdp.patta_no=cp.patta_no AND cdp.patta_type_code=cp.patta_type_code AND cdp.pdar_id=cp.pdar_id AND cdp.dist_code=? AND cdp.subdiv_code=? AND cdp.cir_code=? AND cdp.mouza_pargona_code=? AND cdp.lot_no=? AND cdp.vill_townprt_code=? AND cdp.patta_no=? AND cdp.patta_type_code=? AND cdp.dag_no=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $data['part_dag']->patta_no, $data['part_dag']->patta_type_code, $part_dag])->result();

        $possessors = $this->db->query("SELECT * FROM splitted_dags_possessors WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND old_dag_no=? AND part_dag=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag_no, $part_dag])->result();
        if (!empty($possessors)) {
            foreach ($possessors as $possessor) {
            $guard_relation = $possessor->guard_relation;
            $pattadar_relation = $possessor->pattadar_relation;
            $mode_of_acquisition = $possessor->mode_of_acquisition;

            $guard_relation_data = $this->db->query("SELECT guard_rel_desc_as, guard_rel_desc FROM master_guard_rel WHERE guard_rel=?", [$guard_relation])->row();
            $pdar_relation_data = $this->db->query("SELECT guard_rel_desc_as, guard_rel_desc FROM master_guard_rel WHERE guard_rel=?", [$pattadar_relation])->row();

            $guard_relation_name = (!empty($guard_relation_data)) ? $guard_relation_data->guard_rel_desc_as . ' (' . $guard_relation_data->guard_rel_desc . ')' : '';
            $pattadar_relation_name = (!empty($pdar_relation_data)) ? $pdar_relation_data->guard_rel_desc_as . ' (' . $pdar_relation_data->guard_rel_desc . ')' : '';

            $mode_of_acquisition_name = '';
            foreach(TRANSFER_TYPES as $key => $t_type) {
                if($key == $mode_of_acquisition) {
                $mode_of_acquisition_name = $t_type;
                break;
                }
            }

            $possessor->guard_relation_name = $guard_relation_name;
            $possessor->pattadar_relation_name = $pattadar_relation_name;
            $possessor->mode_of_acquisition_name = $mode_of_acquisition_name;
            $possessor->ownership_documents = $this->db->query("select * from ownership_documents where possessor_u_id= ? ",[$possessor->possessor_u_id])->result();
            }
        }
        $data['possessors'] = $possessors;
        $data['tenants'] = $this->db->query("SELECT * FROM chitha_tenant WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND dag_no=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $part_dag])->result();

        // $data = $this->ChithaModel->getchithaDetailsALL($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag_no_lower, $dag_no_upper);
        echo json_encode([
            'status' => 'y',
            'msg' => 'Successfully retrieved data!',
            'data' => $data
        ]);
        return;
    }

    public function getDeed() {
        $request_data = json_decode(file_get_contents('php://input', true));

        $unique_id = $request_data->unique_id;
        $dbname = $request_data->dbname;
        $from_epanjeeyan_ngdrs = $request_data->from_epanjeeyan_ngdrs;

        if($from_epanjeeyan_ngdrs == 'epanjeeyan') {
            // $deedPdf = calGetDeed('deed/get_deed_view.php?dbname=naharkatia&comcaseno=284/2013', 'GET');
            $deedPdf = callGetDeed('deed/get_deed_view.php?dbname='. $dbname .'&comcaseno='. $unique_id, 'GET');
        }
        else if($from_epanjeeyan_ngdrs == 'ngdrs') {
            $id = $unique_id;
            $data = json_encode([
                'id' => $id
            ]);
            $deedResponse = callNgdrsDeed('v1/get_deed.php', 'POST', json_encode($data));
            if(empty($deedResponse)) {
                $deedPdf = null;
            }
            $deedPdf = base64_decode($deedResponse->base64);
        }
        else {
            $deedPdf = null;
        }

        if(!$deedPdf) {
            echo json_encode([
                'status' => 'n',
                'msg' => 'Pdf could not be generated!!'
            ]);
            exit;
        }

        $upload_path = FCPATH . 'uploads/deeds/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        file_put_contents($upload_path . "document.pdf", $deedPdf);

        $file = $upload_path . "document.pdf";

        if (file_exists($file)) {
            $this->output->set_status_header(200);
            echo json_encode(['status' => 'y', 'msg' => 'Retrieved File!', 'data' => 'uploads/deeds/document.pdf']);
        } else {
            $this->output->set_status_header(404);
            echo json_encode(['status' => 'n', 'msg' => 'File not found']);
        }
        exit;
    }
}
