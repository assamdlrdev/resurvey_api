<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class LocationController extends CI_Controller
{
    use CommonTrait;
    private $jwt_data;
    public function __construct()
    {
        parent::__construct();
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



    public function getDistricts()
    {
        $this->load->helper('cookie');
        header('Content-Type: application/json');
        if ($_SERVER['CONTENT_TYPE'] == 'application/json') {
            $data = json_decode(file_get_contents('php://input', true));
            $msg = null;
            if (!isset($data) || $data == null)
                $msg = $msg . " Missing Parameters,";

            if ($msg != null && !empty($msg)) {
                $response = [
                    'status' => 'n',
                    'msg' => $msg
                ];
                $this->output->set_status_header(401);  // Change to 400, 401, 500, etc. as needed
                echo json_encode($response);
                exit;
            }
        } else {
            $msg = null;
            
            if ($msg != null && !empty($msg)) {
                $response = [
                    'status' => 'n',
                    'msg' => $msg
                ];
                $this->output->set_status_header(401);  // Change to 400, 401, 500, etc. as needed
                echo json_encode($response);
                return;
            }

            // $user_name = $_POST['user_name'];
            // $password = $_POST['password'];
        }

        $resurvey_districts = RESURVEY_DISTRICTS;

        // $payload = jwtencode($resurvey_districts);

        $response = [
            'status' => 'y',
            'msg' => 'Successfully retrieved data!',
            'data' => $resurvey_districts
        ];
        $this->output->set_status_header(200);
        echo json_encode($response);
        return;
    }

    public function getCircles()
    {
        $this->load->helper('cookie');
        header('Content-Type: application/json');
        if ($_SERVER['CONTENT_TYPE'] == 'application/json') {
            $data = json_decode(file_get_contents('php://input', true));
            $msg = null;
            
            if (!isset($data) || $data == null)
                $msg = $msg . " Missing Parameters,";

            if (!isset($data->dist_code) || $data->dist_code == null)
                $msg = $msg . " Missing District Code";
            if ($msg != null && !empty($msg)) {
                $response = [
                    'status' => 'n',
                    'msg' => $msg
                ];
                $this->output->set_status_header(401);  // Change to 400, 401, 500, etc. as needed
                echo json_encode($response);
                exit;
            }


            $dist_code = $data->dist_code;
        } else {
            $msg = null;
            
            
            if (!isset($_POST['dist_code']) || empty($_POST['dist_code']))
                $msg = $msg . " Missing dist code";
            if ($msg != null && !empty($msg)) {
                $response = [
                    'status' => 'n',
                    'msg' => $msg
                ];
                $this->output->set_status_header(401);  // Change to 400, 401, 500, etc. as needed
                echo json_encode($response);
                return;
            }


            $dist_code = $_POST['dist_code'];
            // $user_name = $_POST['user_name'];
            // $password = $_POST['password'];
        }

        $this->dbswitch($dist_code);

        $circles = $this->db->query("SELECT dist_code, subdiv_code, cir_code, loc_name, locname_eng FROM location WHERE dist_code=? AND subdiv_code!='00' AND cir_code!='00' AND mouza_pargona_code='00' AND lot_no='00' AND vill_townprt_code='00000'", [$dist_code])->result();

        // $payload = jwtencode($circles);

        $response = [
            'status' => 'y',
            'msg' => 'Successfully retrieved data!',
            'data' => $circles
        ];
        $this->output->set_status_header(200);
        echo json_encode($response);
        return;
    }

    public function getMouzas()
    {
        $this->load->helper('cookie');
        header('Content-Type: application/json');
        if ($_SERVER['CONTENT_TYPE'] == 'application/json') {
            $data = json_decode(file_get_contents('php://input', true));
            $msg = null;
            
            if (!isset($data) || $data == null)
                $msg = $msg . " Missing Parameters,";

            if (!isset($data->cir_code) || $data->cir_code == null)
                $msg = $msg . " Missing Circle Code";
            if ($msg != null && !empty($msg)) {
                $response = [
                    'status' => 'n',
                    'msg' => $msg
                ];
                $this->output->set_status_header(401);  // Change to 400, 401, 500, etc. as needed
                echo json_encode($response);
                exit;
            }


            $cirCode = $data->cir_code;
        } else {
            $msg = null;
            
            
            if (!isset($_POST['cir_code']) || empty($_POST['cir_code']))
                $msg = $msg . " Missing Circle code";
            if ($msg != null && !empty($msg)) {
                $response = [
                    'status' => 'n',
                    'msg' => $msg
                ];
                $this->output->set_status_header(401);  // Change to 400, 401, 500, etc. as needed
                echo json_encode($response);
                return;
            }


            $cirCode = $_POST['cir_code'];
            // $user_name = $_POST['user_name'];
            // $password = $_POST['password'];
        }



        $cirCodeArr = explode('-', $cirCode);



        $dist_code = $cirCodeArr[0];
        $subdiv_code = $cirCodeArr[1];
        $cir_code = $cirCodeArr[2];

        $data = [];
        $url = LANDHUB_BASE_URL . "/NicApi/getMouzas";
        $method = 'POST';
        $data['dist_code'] = $dist_code;
        $data['subdiv_code'] = $subdiv_code;
        $data['cir_code'] = $cir_code;
        $data['apikey'] = "chithaentry_resurvey";

        $api_output = callApiV2($url, $method, $data);

        if (!$api_output) {
            log_message("error", 'LAND HUB API FAIL LMController');
            $response = [
                'status' => 'n',
                'msg' => 'API Failed!'
            ];
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
        }
        $mouzas = json_decode($api_output);


        if (!isset($mouzas->data) || empty($mouzas->data)) {
            $response = [
                'status' => 'n',
                'msg' => 'No data Found!'
            ];
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
        }

        $payload = $mouzas->data;

        foreach ($payload as $data) {
            $data->dist_code = $dist_code;
            $data->subdiv_code = $subdiv_code;
            $data->cir_code = $cir_code;
        }

        $response = [
            'status' => 'y',
            'msg' => 'Successfully retrieved data!',
            'data' => $payload
        ];
        $this->output->set_status_header(200);
        echo json_encode($response);
        return;
    }

    public function getLots()
    {
        $this->load->helper('cookie');
        header('Content-Type: application/json');
        if ($_SERVER['CONTENT_TYPE'] == 'application/json') {
            $data = json_decode(file_get_contents('php://input', true));
            $msg = null;
            
            if (!isset($data) || $data == null)
                $msg = $msg . " Missing Parameters,";

            if (!isset($data->mouza_pargona_code) || $data->mouza_pargona_code == null)
                $msg = $msg . " Missing Mouza Code";
            if ($msg != null && !empty($msg)) {
                $response = [
                    'status' => 'n',
                    'msg' => $msg
                ];
                $this->output->set_status_header(401);  // Change to 400, 401, 500, etc. as needed
                echo json_encode($response);
                exit;
            }


            $mouzaCode = $data->mouza_pargona_code;
        } else {
            $msg = null;
            
            
            if (!isset($_POST['mouza_pargona_code']) || empty($_POST['mouza_pargona_code']))
                $msg = $msg . " Missing Mouza code";
            if ($msg != null && !empty($msg)) {
                $response = [
                    'status' => 'n',
                    'msg' => $msg
                ];
                $this->output->set_status_header(401);  // Change to 400, 401, 500, etc. as needed
                echo json_encode($response);
                return;
            }


            $mouzaCode = $_POST['mouza_pargona_code'];
            // $user_name = $_POST['user_name'];
            // $password = $_POST['password'];
        }

        $mouzaCodeArr = explode('-', $mouzaCode);

        $dist_code = $mouzaCodeArr[0];
        $subdiv_code = $mouzaCodeArr[1];
        $cir_code = $mouzaCodeArr[2];
        $mouza_pargona_code = $mouzaCodeArr[3];

        $data = [];
        $url = LANDHUB_BASE_URL . "/NicApi/getLots";
        $method = 'POST';
        $data['dist_code'] = $dist_code;
        $data['subdiv_code'] = $subdiv_code;
        $data['cir_code'] = $cir_code;
        $data['mouza_pargona_code'] = $mouza_pargona_code;
        $data['apikey'] = "chithaentry_resurvey";

        $api_output = callApiV2($url, $method, $data);

        if (!$api_output) {
            log_message("error", 'LAND HUB API FAIL LMController');

            $response = [
                'status' => 'n',
                'msg' => 'API Failed!'
            ];
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
        }
        $lots = json_decode($api_output);

        if (!isset($lots->data) || empty($lots->data)) {
            $response = [
                'status' => 'n',
                'msg' => 'No data Found!'
            ];
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
        }

        $payload = $lots->data;
        foreach ($payload as $data) {
            $data->dist_code = $dist_code;
            $data->subdiv_code = $subdiv_code;
            $data->cir_code = $cir_code;
            $data->mouza_code = $mouza_pargona_code;
        }

        $response = [
            'status' => 'y',
            'msg' => 'Successfully retrieved data!',
            'data' => $payload
        ];
        $this->output->set_status_header(200);
        echo json_encode($response);
        return;
    }

    public function getVills()
    {
        $this->load->helper('cookie');
        header('Content-Type: application/json');
        if ($_SERVER['CONTENT_TYPE'] == 'application/json') {
            $data = json_decode(file_get_contents('php://input', true));
            $msg = null;
            
            if (!isset($data) || $data == null)
                $msg = $msg . " Missing Parameters,";

            if (!isset($data->lot_no) || $data->lot_no == null)
                $msg = $msg . " Missing Lot No";
            if ($msg != null && !empty($msg)) {
                $response = [
                    'status' => 'n',
                    'msg' => $msg
                ];
                $this->output->set_status_header(401);  // Change to 400, 401, 500, etc. as needed
                echo json_encode($response);
                exit;
            }


            $lotNo = $data->lot_no;
        } else {
            $msg = null;
            
            
            if (!isset($_POST['lot_no']) || empty($_POST['lot_no']))
                $msg = $msg . " Missing Lot No";
            if ($msg != null && !empty($msg)) {
                $response = [
                    'status' => 'n',
                    'msg' => $msg
                ];
                $this->output->set_status_header(401);  // Change to 400, 401, 500, etc. as needed
                echo json_encode($response);
                return;
            }


            $lotNo = $_POST['lot_no'];
            // $user_name = $_POST['user_name'];
            // $password = $_POST['password'];
        }

        $lotNoArr = explode('-', $lotNo);

        $dist_code = $lotNoArr[0];
        $subdiv_code = $lotNoArr[1];
        $cir_code = $lotNoArr[2];
        $mouza_pargona_code = $lotNoArr[3];
        $lot_no = $lotNoArr[4];

        $data = [];
        $url = LANDHUB_BASE_URL . "/NicApi/getVillages";
        $method = 'POST';
        $data['dist_code'] = $dist_code;
        $data['subdiv_code'] = $subdiv_code;
        $data['cir_code'] = $cir_code;
        $data['mouza_pargona_code'] = $mouza_pargona_code;
        $data['lot_no'] = $lot_no;
        $data['apikey'] = "chithaentry_resurvey";

        $api_output = callApiV2($url, $method, $data);

        if (!$api_output) {
            log_message("error", 'LAND HUB API FAIL LMController');
            $response = [
                'status' => 'n',
                'msg' => 'API Failed!'
            ];
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
        }
        $villages = json_decode($api_output);

        if (!isset($villages->data) || empty($villages->data)) {
            $response = [
                'status' => 'n',
                'msg' => 'No data Found!'
            ];
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
        }

        $payload = $villages->data;
        foreach ($payload as $data) {
            $data->dist_code = $dist_code;
            $data->subdiv_code = $subdiv_code;
            $data->cir_code = $cir_code;
            $data->mouza_code = $mouza_pargona_code;
            $data->lot_no = $lot_no;
        }

        $response = [
            'status' => 'y',
            'msg' => 'Successfully retrieved data!',
            'data' => $payload
        ];
        $this->output->set_status_header(200);
        echo json_encode($response);
        return;
    }

    public function getDags()
    {
        $this->load->helper('cookie');
        header('Content-Type: application/json');
        if ($_SERVER['CONTENT_TYPE'] == 'application/json') {
            $data = json_decode(file_get_contents('php://input', true));
            $msg = null;
            
            if (!isset($data) || $data == null)
                $msg = $msg . " Missing Parameters,";

            if (!isset($data->vill_townprt_code) || $data->vill_townprt_code == null)
                $msg = $msg . " Missing Village Code";
            if ($msg != null && !empty($msg)) {
                $response = [
                    'status' => 'n',
                    'msg' => $msg
                ];
                $this->output->set_status_header(401);  // Change to 400, 401, 500, etc. as needed
                echo json_encode($response);
                exit;
            }


            $villageCode = $data->vill_townprt_code;
        } else {
            $msg = null;
            
            
            if (!isset($_POST['vill_townprt_code']) || empty($_POST['vill_townprt_code']))
                $msg = $msg . " Missing Village Code";
            if ($msg != null && !empty($msg)) {
                $response = [
                    'status' => 'n',
                    'msg' => $msg
                ];
                $this->output->set_status_header(401);  // Change to 400, 401, 500, etc. as needed
                echo json_encode($response);
                return;
            }


            $villageCode = $_POST['vill_townprt_code'];
            // $user_name = $_POST['user_name'];
            // $password = $_POST['password'];
        }

        $villageCodeArr = explode('-', $villageCode);

        $dist_code = $villageCodeArr[0];
        $subdiv_code = $villageCodeArr[1];
        $cir_code = $villageCodeArr[2];
        $mouza_pargona_code = $villageCodeArr[3];
        $lot_no = $villageCodeArr[4];
        $vill_townprt_code = $villageCodeArr[5];

        $this->dbswitch($dist_code);


        $checkMergingStatus = $this->db->query("SELECT is_merged FROM resurvey_villages WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code])->row();
        if (empty($checkMergingStatus)) {
            $this->db->trans_begin();
            //merge and update
            $mergeStatus = $this->mergeVillage($villageCode);
            if ($mergeStatus['status'] != 'y') {
                $this->db->trans_rollback();
                $response = [
                    'status' => 'n',
                    'msg' => 'Could not sync dharitree data with chitha!'
                ];
                $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
                echo json_encode($response);
                return;
            }

            //insert into resurvey_villages
            $insertArr = [
                'dist_code' => $dist_code,
                'subdiv_code' => $subdiv_code,
                'cir_code' => $cir_code,
                'mouza_pargona_code' => $mouza_pargona_code,
                'lot_no' => $lot_no,
                'vill_townprt_code' => $vill_townprt_code,
                'is_merged' => 1,
                'user_code' => $this->jwt_data->usercode,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $insertStatus = $this->db->insert('resurvey_villages', $insertArr);
            if (!$insertStatus || $this->db->affected_rows() < 1) {
                $this->db->trans_rollback();
                $response = [
                    'status' => 'n',
                    'msg' => 'Could not update into resurvey villages!'
                ];
                $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
                echo json_encode($response);
                return;
            }

            if (!$this->db->trans_status()) {
                $this->db->trans_rollback();
                $response = [
                    'status' => 'n',
                    'msg' => 'DB Transaction Failed...'
                ];
                $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
                echo json_encode($response);
                return;
            }

            $this->db->trans_commit();
        } else {
            if ($checkMergingStatus->is_merged != 1) {
                $this->db->trans_begin();
                $mergeStatus = $this->mergeVillage($villageCode);
                if ($mergeStatus['status'] != 'y') {
                    $this->db->trans_rollback();
                    $response = [
                        'status' => 'n',
                        'msg' => 'Could not sync dharitree data with chitha!'
                    ];
                    $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
                    echo json_encode($response);
                    return;
                }
                $updArr = [
                    'is_merged' => 1
                ];
                $this->db->where([
                    'dist_code' => $dist_code,
                    'subdiv_code' => $subdiv_code,
                    'cir_code' => $cir_code,
                    'mouza_pargona_code' => $mouza_pargona_code,
                    'lot_no' => $lot_no,
                    'vill_townprt_code' => $vill_townprt_code
                ]);
                $updStatus = $this->db->update('resurvey_villages', $updArr);
                if (!$updStatus || $this->db->affected_rows() < 1) {
                    $this->db->trans_rollback();
                    $response = [
                        'status' => 'n',
                        'msg' => 'Could not update into resurvey villages!'
                    ];
                    $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
                    echo json_encode($response);
                    return;
                }
                if (!$this->db->trans_status()) {
                    $this->db->trans_rollback();
                    $response = [
                        'status' => 'n',
                        'msg' => 'DB Transaction Failed...!'
                    ];
                    $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
                    echo json_encode($response);
                    return;
                }
                $this->db->trans_commit();
            }
        }
        $data = [];
        $url = LANDHUB_BASE_URL . "/NicApi/getDagsForChithaReservey";
        $method = 'POST';
        $data['dist_code'] = $dist_code;
        $data['subdiv_code'] = $subdiv_code;
        $data['cir_code'] = $cir_code;
        $data['mouza_pargona_code'] = $mouza_pargona_code;
        $data['lot_no'] = $lot_no;
        $data['vill_code'] = $vill_townprt_code;
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
            // echo "API FAIL";
            // return;
        }

        $dags = json_decode($api_output);

        if (!isset($dags->data) || empty($dags->data)) {
            $response = [
                'status' => 'n',
                'msg' => 'No data Found!'
            ];
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
        }

        $payload = $dags->data;


        $url = DRAFT_MAP_API;
		$method = 'POST';
		$data2['location'] = $dist_code.'_'.$subdiv_code.'_'.$cir_code.'_'.$mouza_pargona_code.'_'.$lot_no.'_'.$vill_townprt_code;

		$map_geojon = callApiV3($url, $method, $data2);
        $map_geojon_decoded = json_decode($map_geojon);
        $response = [
            'status' => 'y',
            'msg' => 'Successfully retrieved data!',
            'data' => $payload,
            'map_geojson' => $map_geojon_decoded->features ? $map_geojon_decoded : ''
        ];
        $this->output->set_status_header(200);
        echo json_encode($response);
        return;
    }

    public function getDagData()
    {
        $this->load->helper('cookie');
        $authToken = $this->input->cookie('jwt_authorization', TRUE);
        jwtVerify($authToken);

        header('Content-Type: application/json');
        if ($_SERVER['CONTENT_TYPE'] == 'application/json') {
            $data = json_decode(file_get_contents('php://input', true));
            $msg = null;
            
            if (!isset($data) || $data == null)
                $msg = $msg . " Missing Parameters,";

            if (!isset($data->vill_townprt_code) || $data->vill_townprt_code == null)
                $msg = $msg . " Missing Village Code,";
            if (!isset($data->dag_no) || $data->dag_no == null)
                $msg = $msg . " Missing Dag No";
            if ($msg != null && !empty($msg)) {
                $response = [
                    'status' => 'n',
                    'msg' => $msg
                ];
                $this->output->set_status_header(401);  // Change to 400, 401, 500, etc. as needed
                echo json_encode($response);
                exit;
            }


            $villageCode = $data->vill_townprt_code;
            $dagNo = $data->dag_no;
        } else {
            $msg = null;
            
            
            if (!isset($_POST['vill_townprt_code']) || empty($_POST['vill_townprt_code']))
                $msg = $msg . " Missing Village Code,";
            if (!isset($_POST['dag_no']) || empty($_POST['dag_no']))
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


            $villageCode = $_POST['vill_townprt_code'];
            $dagNo = $_POST['dag_no'];
        }

        $locationArr = explode('-', $villageCode);

        $dist_code = $locationArr[0];
        $subdiv_code = $locationArr[1];
        $cir_code = $locationArr[2];
        $mouza_pargona_code = $locationArr[3];
        $lot_no = $locationArr[4];
        $vill_townprt_code = $locationArr[5];
        $dag_no = $dagNo;

        $this->dbswitch($dist_code);


        $originalDagForEntry = $this->db->query("
            SELECT cb.*, pc.patta_type, lc.land_type 
            FROM chitha_basic cb
            LEFT JOIN patta_code pc 
                ON cb.patta_type_code = pc.type_code
            LEFT JOIN landclass_code lc 
                ON cb.land_class_code = lc.class_code
            WHERE cb.dist_code = ?
            AND cb.subdiv_code = ?
            AND cb.cir_code = ?
            AND cb.mouza_pargona_code = ?
            AND cb.lot_no = ?
            AND cb.vill_townprt_code = ?
            AND cb.dag_no = ?
        ", [
            $dist_code,
            $subdiv_code,
            $cir_code,
            $mouza_pargona_code,
            $lot_no,
            $vill_townprt_code,
            $dag_no
        ])->row();

        if (empty($originalDagForEntry)) {
            $response = [
                'status' => 'n',
                'msg' => 'Dag not yet merged in Chitha'
            ];
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
        }

        $data = [];
        $url = "/PartDags";
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
        } else {
            $partDags = $api_output['data']->partDags;
            $locationCode = $api_output['data']->locationCode;
            $inputOldDagNo = $api_output['data']->inputOldDagNo;
        }



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

                    $partDagsForEntry[] = $row;
                }
            }
        }

        //retrieve Land Classes and Patta types
        $data = [];
        $url = LANDHUB_BASE_URL . "/NicApi/getLandClassesAndPattaTypes";
        $method = 'POST';
        $data['dist_code'] = $dist_code;
        $data['apikey'] = "chithaentry_resurvey";
        $api_output = callApiV2($url, $method, $data);

        if (!$api_output) {
            log_message("error", 'LAND HUB API FAIL');
            $response = [
                'status' => 'n',
                'msg' => 'getLandClassesAndPattaTypes API Failed!'
            ];
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
            // echo "API FAIL";
            // return;
        }

        $landClassesAndPattaTypes = json_decode($api_output);

        if (!$landClassesAndPattaTypes->data || !$landClassesAndPattaTypes->data->land_classes || !$landClassesAndPattaTypes->data->patta_types) {
            $response = [
                'status' => 'n',
                'msg' => 'Land Classes and PattaTypes could not be retrieved!'
            ];
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
        }






        // pattadars
        $pattadars = $this->db->query("SELECT cdp.*, cp.pdar_name, cp.pdar_father,cp.pdar_add1,cp.pdar_add2 FROM chitha_dag_pattadar cdp, chitha_pattadar cp WHERE cdp.dist_code=cp.dist_code AND cdp.subdiv_code=cp.subdiv_code AND cdp.cir_code=cp.cir_code AND cdp.mouza_pargona_code=cp.mouza_pargona_code AND cdp.lot_no=cp.lot_no AND cdp.vill_townprt_code=cp.vill_townprt_code AND cdp.patta_no=cp.patta_no AND cdp.patta_type_code=cp.patta_type_code AND cdp.pdar_id=cp.pdar_id AND cdp.dist_code=? AND cdp.subdiv_code=? AND cdp.cir_code=? AND cdp.mouza_pargona_code=? AND cdp.lot_no=? AND cdp.vill_townprt_code=? AND cdp.dag_no=? AND cdp.patta_no=? AND cdp.patta_type_code=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag_no, $originalDagForEntry->patta_no, $originalDagForEntry->patta_type_code])->result();

        $pdarArray = [];

        if (!empty($pattadars)) {
            foreach ($pattadars as $pattadar) {
                $value = $pattadar->dist_code . '-' . $pattadar->subdiv_code . '-' . $pattadar->cir_code . '-' . $pattadar->mouza_pargona_code . '-' . $pattadar->lot_no . '-' . $pattadar->vill_townprt_code . '-' . $pattadar->patta_no . '-' . $pattadar->patta_type_code . '-' . $pattadar->pdar_id;
                $label = $pattadar->pdar_name . ' (' . $pattadar->pdar_father . ')';

                $row = [];
                $row['value'] = $value;
                $row['label'] = $label;
                $row['pdar_name'] = $pattadar->pdar_name;
                $row['pdar_father'] = $pattadar->pdar_father;
                $row['pdar_add1'] = $pattadar->pdar_add1;
                $row['pdar_add2'] = $pattadar->pdar_add2;
                $pdarArray[] = $row;
            }
        }

        $revenue_data = $this->calcLandRevenue($originalDagForEntry, $locationArr);
        if ($revenue_data) {
            $originalDagForEntry->dag_revenue = $revenue_data->dag_revenue;
            $originalDagForEntry->dag_local_tax = $revenue_data->dag_local_tax;
        }

        $resp = [];
        $resp['dharitree_data'] = $originalDagForEntry;
        $resp['part_dags'] = $partDagsForEntry;
        $resp['land_classes'] = $landClassesAndPattaTypes->data->land_classes;
        $resp['patta_types'] = $landClassesAndPattaTypes->data->patta_types;
        $resp['land_groups'] = $landClassesAndPattaTypes->data->land_groups;
        $resp['pattadars'] = $pdarArray;


        $response = [
            'status' => 'y',
            'msg' => 'Successfully retrieved data!',
            'data' => $resp
        ];
        $this->output->set_status_header(200);  // Change to 400, 401, 500, etc. as needed
        echo json_encode($response);
        return;
    }

    private function calcLandRevenue($dag_data, $locationArr)
    {
        $data['dist_code'] = $locationArr[0];
        $data['subdiv_code'] = $locationArr[1];
        $data['cir_code'] = $locationArr[2];
        $data['mouza_pargona_code'] = $locationArr[3];
        $data['lot_no'] = $locationArr[4];
        $data['vill_townprt_code'] = $locationArr[5];
        $data['land_class_code'] = $dag_data->land_class_code;

        $dag_no = $dag_data->dag_no;
        $bigha = $dag_data->dag_area_b;
        $katha = $dag_data->dag_area_k;
        $lessaChatak = $dag_data->dag_area_lc;
        $ganda = $dag_data->dag_area_g;

        if (in_array($data['dist_code'], BARAK_VALLEY)) {
            $eq_bigha = $bigha + ($katha / 20) + ($lessaChatak / 320) + ($ganda / 6400);
        } else {
            $eq_bigha = $bigha + ($katha / 5) + ($lessaChatak / 100);
        }

        $url = LANDHUB_BASE_URL . "/NicApi/getRevenueLandClassCodeWise";
        $method = 'POST';
        $data['apikey'] = "chithaentry_resurvey";
        $api_output = callApiV2($url, $method, $data);

        if (!$api_output) {
            log_message("error", 'LAND HUB API FAIL');
            $response = [
                'status' => 'n',
                'msg' => 'API FAIL!'
            ];
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
        }
        $response = json_decode($api_output);
        $revenue_details = $response->data;

        $this->dbswitch($data['dist_code']);
        $location = $this->db->query("SELECT rural_urban FROM location WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=?", [$data['dist_code'], $data['subdiv_code'], $data['cir_code'], $data['mouza_pargona_code'], $data['lot_no'], $data['vill_townprt_code']])->row();
        if ($location->rural_urban == 'U') {
            if (empty($revenue_details) || $revenue_details->ruralurban != 'Urban') {
                $response = [
                    'status' => 'n',
                    'msg' => 'Revenue Land Class Wise Does not exist for this land class!'
                ];
                $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
                echo json_encode($response);
                return;
            }

            $revenue_details->dag_revenue = $eq_bigha * $revenue_details->dag_revenue_perbigha;
            $revenue_details->dag_local_tax = $eq_bigha * $revenue_details->dag_local_tax_min;
        } else if ($location->rural_urban == 'R') {
            if (empty($revenue_details) || $revenue_details->ruralurban != 'Rural') {
                $response = [
                    'status' => 'n',
                    'msg' => 'Revenue Land Class Wise Does not exist for this land class!'
                ];
                $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
                echo json_encode($response);
                return;
            }

            $revenue_details->dag_revenue = $eq_bigha * $revenue_details->dag_revenue_perbigha;
            $revenue_details->dag_local_tax = $eq_bigha * $revenue_details->dag_local_tax_min;
        }
        return $revenue_details;
    }

    public function getPartdagData()
    {
        $this->load->helper('cookie');

        header('Content-Type: application/json');
        if ($_SERVER['CONTENT_TYPE'] == 'application/json') {
            $data = json_decode(file_get_contents('php://input', true));
            $msg = null;
            
            if (!isset($data) || $data == null)
                $msg = $msg . " Missing Parameters,";

            if (!isset($data->vill_townprt_code) || $data->vill_townprt_code == null)
                $msg = $msg . " Missing Village Code,";
            if (!isset($data->dag_no) || $data->dag_no == null)
                $msg = $msg . " Missing Dag No";
            if (!isset($data->part_dag) || $data->part_dag == null)
                $msg = $msg . " Missing Part Dag No";
            if ($msg != null && !empty($msg)) {
                $response = [
                    'status' => 'n',
                    'msg' => $msg
                ];
                $this->output->set_status_header(401);  // Change to 400, 401, 500, etc. as needed
                echo json_encode($response);
                exit;
            }


            $villageCode = $data->vill_townprt_code;
            $dagNo = $data->dag_no;
            $partDag = $data->part_dag;
        } else {
            $msg = null;
            
            
            if (!isset($_POST['vill_townprt_code']) || empty($_POST['vill_townprt_code']))
                $msg = $msg . " Missing Village Code,";
            if (!isset($_POST['dag_no']) || empty($_POST['dgetag_no']))
                $msg = $msg . " Missing Dag No ";
            if (!isset($_POST['part_dag']) || empty($_POST['part_dag']))
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


            $villageCode = $_POST['vill_townprt_code'];
            $dagNo = $_POST['dag_no'];
            $partDag = $_POST['part_dag'];
            // $user_name = $_POST['user_name'];
            // $password = $_POST['password'];
        }

        $locationArr = explode('-', $villageCode);

        $dist_code = $locationArr[0];
        $subdiv_code = $locationArr[1];
        $cir_code = $locationArr[2];
        $mouza_pargona_code = $locationArr[3];
        $lot_no = $locationArr[4];
        $vill_townprt_code = $locationArr[5];
        $dag_no = $dagNo;
        $part_dag = $partDag;

        $this->dbswitch($dist_code);

        //first search in chitha whether updated
        $partDagDetailsChitha = $this->db->query('SELECT * FROM chitha_basic_splitted_dags WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND dag_no=? AND survey_no=?', [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag_no, $part_dag])->row();

        if (!empty($partDagDetailsChitha)) {
            $partDagDetailsChitha->from_chitha = 1;
            $partDagDetailsChitha->from_bhunaksha = 0;

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

            $pattadars = $this->db->query("SELECT cdp.*, cp.pdar_name, cp.pdar_father FROM chitha_dag_pattadar cdp, chitha_pattadar cp WHERE cdp.dist_code=cp.dist_code AND cdp.subdiv_code=cp.subdiv_code AND cdp.cir_code=cp.cir_code AND cdp.mouza_pargona_code=cp.mouza_pargona_code AND cdp.lot_no=cp.lot_no AND cdp.vill_townprt_code=cp.vill_townprt_code AND cdp.patta_no=cp.patta_no AND cdp.patta_type_code=cp.patta_type_code AND cdp.pdar_id=cp.pdar_id AND cdp.dist_code=? AND cdp.subdiv_code=? AND cdp.cir_code=? AND cdp.mouza_pargona_code=? AND cdp.lot_no=? AND cdp.vill_townprt_code=? AND cdp.patta_no=? AND cdp.patta_type_code=? AND cdp.dag_no=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $partDagDetailsChitha->patta_no, $partDagDetailsChitha->patta_type_code, $partDagDetailsChitha->survey_no])->result();

            // $partDagPattadars = [];
            if (!empty($pattadars)) {
                foreach ($pattadars as $pattadar) {
                    $pattadar->label = $pattadar->pdar_name . ' (' . $pattadar->pdar_father . ')';
                    $pattadar->value = $dist_code . '-' . $subdiv_code . '-' . $cir_code . '-' . $mouza_pargona_code . '-' . $lot_no . '-' . $vill_townprt_code . '-' . $pattadar->patta_no . '-' . $pattadar->patta_type_code . '-' . $pattadar->pdar_id;

                    // $row['label'] = $pattadar->pdar_name . ' (' . $pattadar->pdar_father . ')';
                    // $row['value'] = $dist_code . '-' . $subdiv_code . '-' . $cir_code . '-' . $mouza_pargona_code . '-' . $lot_no . '-' . $vill_townprt_code . '-' . $pattadar->patta_no . '-' . $pattadar->patta_type_code . '-' . $pattadar->pdar_id;

                    // $partDagPattadars[] = $row;
                }
            }

            $partDagDetailsChitha->pattadars = $pattadars;
            $partDagDetailsChitha->possessors = $possessors;

            $partDagDetailsChitha->tenants = $this->db->query("SELECT * FROM chitha_tenant WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND dag_no=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $partDagDetailsChitha->survey_no])->result();

            $response = [
                'status' => 'y',
                'msg' => 'Successfully retrieved data!',
                'data' => $partDagDetailsChitha
            ];
            $this->output->set_status_header(200);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
        }

        //if not available then search in bhunaksha
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
                'msg' => 'API Failed!'
            ];
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
        }

        $partDags = $api_output['data']->partDags;
        $locationCode = $api_output['data']->locationCode;
        $inputOldDagNo = $api_output['data']->inputOldDagNo;

        if (!empty($partDags)) {
            foreach ($partDags as $pDag) {
                $partDag = $pDag->newDagNo;
                $dag_area_sqmtr = $pDag->plotArea;
                //check in chitha whether created
                if ($part_dag == $partDag) {
                    $row = [];
                    $row['survey_no'] = $partDag;
                    $row['from_chitha'] = 0;
                    $row['from_bhunaksha'] = 1;
                    $row['dag_area_sqmtr'] = $dag_area_sqmtr;

                    $response = [
                        'status' => 'y',
                        'msg' => 'Successfully retrieved data!',
                        'data' => $row
                    ];
                    $this->output->set_status_header(200);  // Change to 400, 401, 500, etc. as needed
                    echo json_encode($response);
                    return;
                }
            }
        }

        $row = [];
        $row['survey_no'] = $part_dag;
        $row['from_chitha'] = 0;
        $row['from_bhunaksha'] = 0;
        $row['dag_area_sqmtr'] = 0;

        $response = [
            'status' => 'y',
            'msg' => 'Successfully retrieved data!',
            'data' => $row
        ];
        $this->output->set_status_header(200);  // Change to 400, 401, 500, etc. as needed
        echo json_encode($response);
        return;
    }

    public function getLandRevenue()
    {
        $this->load->helper('cookie');

        header('Content-Type: application/json');
        if ($_SERVER['CONTENT_TYPE'] == 'application/json') {
            $data = json_decode(file_get_contents('php://input', true));
            $msg = null;
            
            if (!isset($data) || $data == null)
                $msg = $msg . " Missing Parameters,";

            if (!isset($data->vill_townprt_code) || $data->vill_townprt_code == null)
                $msg = $msg . " Missing Village Code,";
            if (!isset($data->land_class_code) || $data->land_class_code == null)
                $msg = $msg . " Missing Land Class";
            if (!isset($data->area_sm) || $data->area_sm == null)
                $msg = $msg . " Missing Area";
            if ($msg != null && !empty($msg)) {
                $response = [
                    'status' => 'n',
                    'msg' => $msg
                ];
                $this->output->set_status_header(401);  // Change to 400, 401, 500, etc. as needed
                echo json_encode($response);
                exit;
            }


            $villageCode = $data->vill_townprt_code;
            $landClassCode = $data->land_class_code;
            $areaSm = $data->area_sm;
        } else {
            $msg = null;
            
            
            if (!isset($_POST['vill_townprt_code']) || empty($_POST['vill_townprt_code']))
                $msg = $msg . " Missing Village Code,";
            if (!isset($_POST['land_class_code']) || empty($_POST['land_class_code']))
                $msg = $msg . " Missing Land Class ";
            if (!isset($_POST['area_sm']) || empty($_POST['area_sm']))
                $msg = $msg . " Missing Area ";
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
            $landClassCode = $_POST['land_class_code'];
            $areaSm = $_POST['area_sm'];
            // $user_name = $_POST['user_name'];
            // $password = $_POST['password'];
        }

        $locationArr = explode('-', $villageCode);

        $dist_code = $locationArr[0];
        $subdiv_code = $locationArr[1];
        $cir_code = $locationArr[2];
        $mouza_pargona_code = $locationArr[3];
        $lot_no = $locationArr[4];
        $vill_townprt_code = $locationArr[5];
        $land_class_code = $landClassCode;
        $area_sm = $areaSm;

        $areaTotal = (in_array($dist_code, BARAK_VALLEY)) ? (20 / 4.1806368) * $area_sm : (1 / 13.37803776) * $area_sm;

        if (in_array($dist_code, BARAK_VALLEY)) {
            $bigha = floor($areaTotal / 6400);
            $katha = floor(($areaTotal - ($bigha * 6400)) / 320);
            $lessaChatak = floor(($areaTotal - ($bigha * 6400 + $katha * 320)) / 20);
            $ganda = number_format($areaTotal - ($bigha * 6400 + $katha * 320 + $lessaChatak * 20), 4);
        } else {
            $bigha = floor($areaTotal / 100);
            $katha = floor(($areaTotal - ($bigha * 100)) / 20);
            $lessaChatak = $areaTotal - ($bigha * 100 + $katha * 20);
        }

        if (in_array($dist_code, BARAK_VALLEY)) {
            $eq_bigha = $bigha + ($katha / 20) + ($lessaChatak / 320) + ($ganda / 6400);
        } else {
            $eq_bigha = $bigha + ($katha / 5) + ($lessaChatak / 100);
        }

        $this->dbswitch($dist_code);

        $location = $this->db->query('SELECT * FROM location WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=?', [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code])->row();
        if (empty($location)) {
            $response = [
                'status' => 'n',
                'msg' => 'Could not find location!'
            ];
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
        }
        if ($location->rural_urban == 'N' || $location->rural_urban == '' || $location->rural_urban == null) {
            $response = [
                'status' => 'n',
                'msg' => 'Village not Flagged in location as rural urban. Cannot process!'
            ];
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
        }

        // $this->load->model('NcVillageModel');
        $data = [];
        $url = LANDHUB_BASE_URL . "/NicApi/getRevenueLandClassCodeWise";
        $method = 'POST';
        $data['dist_code'] = $dist_code;
        $data['subdiv_code'] = $subdiv_code;
        $data['cir_code'] = $cir_code;
        $data['land_class_code'] = $land_class_code;
        $data['apikey'] = "chithaentry_resurvey";
        $api_output = callApiV2($url, $method, $data);

        if (!$api_output) {
            log_message("error", 'LAND HUB API FAIL');
            $response = [
                'status' => 'n',
                'msg' => 'API FAIL!'
            ];
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
        }
        $response = json_decode($api_output);

        $revenue_details = $response->data;

        if ($location->rural_urban == 'U') {
            if (empty($revenue_details) || $revenue_details->ruralurban != 'Urban') {
                $response = [
                    'status' => 'n',
                    'msg' => 'Revenue Land Class Wise Does not exist for this land class!'
                ];
                $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
                echo json_encode($response);
                return;
            }

            $revenue_details->dag_revenue = $eq_bigha * $revenue_details->dag_revenue_perbigha;
            $revenue_details->dag_local_tax = $eq_bigha * $revenue_details->dag_local_tax_min;

            $response = [
                'status' => 'y',
                'msg' => 'Data Retrieved!',
                'data' => $revenue_details
            ];
            $this->output->set_status_header(200);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
        } else if ($location->rural_urban == 'R') {
            if (empty($revenue_details) || $revenue_details->ruralurban != 'Rural') {
                $response = [
                    'status' => 'n',
                    'msg' => 'Revenue Land Class Wise Does not exist for this land class!'
                ];
                $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
                echo json_encode($response);
                return;
            }

            $revenue_details->dag_revenue = $eq_bigha * $revenue_details->dag_revenue_perbigha;
            $revenue_details->dag_local_tax = $eq_bigha * $revenue_details->dag_local_tax_min;

            $response = [
                'status' => 'y',
                'msg' => 'Data Retrieved!',
                'data' => $revenue_details
            ];
            $this->output->set_status_header(200);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
        }
    }






    public function mergeVillage($input_string)
    {
        // $input_string = $this->input->post('vill_townprt_code', true);
        $inputArr = explode('-', $input_string);
        $dist_code = $inputArr[0];
        $subdiv_code = $inputArr[1];
        $cir_code = $inputArr[2];
        $mouza_pargona_code = $inputArr[3];
        $lot_no = $inputArr[4];
        $vill_townprt_code = $inputArr[5];

        // $this->dbswitch($dist_code);
        // $this->db->trans_begin();

        $location = $this->db->query("SELECT * FROM location WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code])->row();

        if (empty($location)) {
            $locationApi = callLandhubAPIMerge('POST', 'NicApiMerge/getLocation', [
                'dist_code' => $dist_code,
                'subdiv_code' => $subdiv_code,
                'cir_code' => $cir_code,
                'mouza_pargona_code' => $mouza_pargona_code,
                'lot_no' => $lot_no,
                'vill_townprt_code' => $vill_townprt_code
            ]);

            if ($locationApi->responseType != 2) {
                // $this->db->trans_rollback();
                return [
                    'status' => 'n',
                    'msg' => 'Could not retrieve location from API for merging!'
                ];
                // echo json_encode([
                //     "status" => "FAILED",
                //     "responseType" => 1,
                //     "msg" => "Could not retrieve location from API"
                // ]);
                // exit;
            }

            if (empty($locationApi->data)) {
                // $this->db->trans_rollback();
                return [
                    'status' => 'n',
                    'msg' => 'Location does not exist in dharitree!'
                ];
                // echo json_encode([
                //     "status" => "FAILED",
                //     "responseType" => 1,
                //     "msg" => "Location does not exist in dharitree"
                // ]);
                // exit;
            }

            // if(!empty($locationApi) && !empty($locationApi->data)) {
            //insert
            $insertLocationArr = [
                'dist_code' => $dist_code,
                'subdiv_code' => $subdiv_code,
                'cir_code' => $cir_code,
                'mouza_pargona_code' => $mouza_pargona_code,
                'lot_no' => $lot_no,
                'vill_townprt_code' => $vill_townprt_code,
                'loc_name' => $locationApi->data->loc_name,
                'unique_loc_code' => $locationApi->data->unique_loc_code,
                'locname_eng' => $locationApi->data->locname_eng,
                'cir_abbr' => $locationApi->data->cir_abbr,
                'dist_abbr' => $locationApi->data->dist_abbr,
                'rural_urban' => $locationApi->data->rural_urban,
                'uuid' => $locationApi->data->uuid,
                'is_gmc' => (isset($locationApi->data->is_gmc) && $locationApi->data->is_gmc != null) ? $locationApi->data->is_gmc : null,
                'lgd_code' => (isset($locationApi->data->lgd_code) && $locationApi->data->lgd_code != null) ? $locationApi->data->lgd_code : null,
                'village_status' => (isset($locationApi->data->village_status) && $locationApi->data->village_status != null) ? $locationApi->data->village_status : null,
                'is_map' => (isset($locationApi->data->is_map) && $locationApi->data->is_map != null) ? $locationApi->data->is_map : null,
                'created_date' => (isset($locationApi->data->created_date) && $locationApi->data->created_date != null) ? $locationApi->data->created_date : null,
                'updated_date' => (isset($locationApi->data->updated_date) && $locationApi->data->updated_date != null) ? $locationApi->data->updated_date : null,
                'user_code' => (isset($locationApi->data->user_code) && $locationApi->data->user_code != null) ? $locationApi->data->user_code : null,
                'status' => (isset($locationApi->data->status) && $locationApi->data->status != null) ? $locationApi->data->status : null,
                'nc_btad' => (isset($locationApi->data->nc_btad) && $locationApi->data->nc_btad != null) ? $locationApi->data->nc_btad : null,
                'is_periphary' => (isset($locationApi->data->is_periphary) && $locationApi->data->is_periphary != null) ? $locationApi->data->is_periphary : null,
                'is_tribal' => (isset($locationApi->data->is_tribal) && $locationApi->data->is_tribal != null) ? $locationApi->data->is_tribal : null,
                'district_headquater' => (isset($locationApi->data->district_headquater) && $locationApi->data->district_headquater != null) ? $locationApi->data->district_headquater : null
            ];
            // if($this->db->field_exists('village_status', 'location')) {
            //     $insertLocationArr['village_status'] = isset($locationApi->data->village_status) ? $locationApi->data->village_status : null;
            // }


            $status = $this->db->insert('location', $insertLocationArr);
            if (!$status || $this->db->affected_rows() < 1) {
                // $this->db->trans_rollback();
                return [
                    'status' => 'n',
                    'msg' => 'Could not insert location in chitha!'
                ];
                // echo json_encode([
                //     "status" => "FAILED",
                //     "responseType" => 1,
                //     "msg" => "Could not insert location in chitha"
                // ]);
                // exit;
            }
            // }
        }

        $dagsApi = callLandhubAPIMerge('POST', 'NicApiMerge/getDags', [
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'mouza_pargona_code' => $mouza_pargona_code,
            'lot_no' => $lot_no,
            'vill_townprt_code' => $vill_townprt_code
        ]);

        if ($dagsApi->responseType != 2) {
            // $this->db->trans_rollback();
            return [
                'status' => 'n',
                'msg' => 'Could not retrieve dags from API!'
            ];
            // echo json_encode([
            //     "status" => "FAILED",
            //     "responseType" => 1,
            //     "msg" => "Could not retrieve dags from API"
            // ]);
            // exit;
        }

        // if(empty($dagsApi->data)) {
        //     $this->db->trans_rollback();
        //     echo json_encode([
        //         "status" => "FAILED",
        //         "responseType" => 1,
        //         "msg" => "Nothing to merge. No dags available in this village!"
        //     ]);
        //     exit;
        // }

        if (!empty($dagsApi->data)) {
            foreach ($dagsApi->data as $dag) {
                //check in local database
                $dag_no = $dag->dag_no;
                $dag_exist = $this->db->query("SELECT dag_no FROM chitha_basic WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND dag_no=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag_no])->row();
                if (empty($dag_exist)) {
                    //then insert into chitha
                    $insertChithaArr = [
                        'dist_code' => $dist_code,
                        'subdiv_code' => $subdiv_code,
                        'cir_code' => $cir_code,
                        'mouza_pargona_code' => $mouza_pargona_code,
                        'lot_no' => $lot_no,
                        'vill_townprt_code' => $vill_townprt_code,
                        'dag_no' => $dag_no,
                        'dag_no_int' => $dag->dag_no_int,
                        // 'alpha_dag' => 0,
                        'old_dag_no' => $dag->old_dag_no,
                        'patta_type_code' => $dag->patta_type_code,
                        'patta_no' => $dag->patta_no,
                        'land_class_code' => $dag->land_class_code,
                        'dag_area_b' => $dag->dag_area_b,
                        'dag_area_k' => $dag->dag_area_k,
                        'dag_area_lc' => $dag->dag_area_lc,
                        'dag_area_kr' => $dag->dag_area_kr,
                        'dag_area_g' => $dag->dag_area_g,
                        'dag_area_are' => $dag->dag_area_are,
                        'dag_revenue' => $dag->dag_revenue,
                        'dag_local_tax' => $dag->dag_local_tax,
                        'dag_n_desc' => $dag->dag_n_desc,
                        'dag_s_desc' => $dag->dag_s_desc,
                        'dag_e_desc' => $dag->dag_e_desc,
                        'dag_w_desc' => $dag->dag_w_desc,
                        'dag_n_dag_no' => $dag->dag_n_dag_no,
                        'dag_s_dag_no' => $dag->dag_s_dag_no,
                        'dag_e_dag_no' => $dag->dag_e_dag_no,
                        'dag_w_dag_no' => $dag->dag_w_dag_no,
                        'dag_nlrg_no' => (!empty($dag->dag_nlrg_no)) ? $dag->dag_nlrg_no : '',
                        'dp_flag_yn' => $dag->dp_flag_yn,
                        'user_code' => $dag->user_code, //
                        'date_entry' => $dag->date_entry, //
                        'old_patta_no' => $dag->old_patta_no,
                        'jama_yn' => $dag->jama_yn,
                        // 'survey_no' => $split_dag,
                        'operation' => $dag->operation, //
                        'status' => (isset($dag->status) && $dag->status != null) ? $dag->status : null,
                        'zonal_value' => (isset($dag->zonal_value) && $dag->zonal_value != null) ? $dag->zonal_value : null,
                        'police_station' => (isset($dag->police_station) && $dag->police_station != null) ? $dag->police_station : null,
                        'revenue_paid_upto' => (isset($dag->revenue_paid_upto) && $dag->revenue_paid_upto != null) ? $dag->revenue_paid_upto : null,
                        'block_code' => (isset($dag->block_code) && $dag->block_code != null) ? $dag->block_code : null,
                        'gp_code' => (isset($dag->gp_code) && $dag->gp_code != null) ? $dag->gp_code : null,
                        'category_id' => (isset($dag->category_id) && $dag->category_id != null) ? $dag->category_id : null
                    ];
                    $insertChithaStatus = $this->db->insert('chitha_basic', $insertChithaArr);
                    if (!$insertChithaStatus || $this->db->affected_rows() < 1) {
                        // $this->db->trans_rollback();
                        return [
                            'status' => 'n',
                            'msg' => 'Dag entry Failed in chitha basic!'
                        ];
                        // echo json_encode([
                        //     "status" => "FAILED",
                        //     "responseType" => 1,
                        //     "msg" => "Dag entry Failed in chitha basic"
                        // ]);
                        // exit;
                    }
                }
            }
        }

        //chitha_pattadars
        $chithaPattadars = callLandhubAPIMerge('POST', 'NicApiMerge/getChithaPattadars', [
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'mouza_pargona_code' => $mouza_pargona_code,
            'lot_no' => $lot_no,
            'vill_townprt_code' => $vill_townprt_code
        ]);

        if ($chithaPattadars->responseType != 2) {
            // $this->db->trans_rollback();
            return [
                'status' => 'n',
                'msg' => 'Could not retrieve from API!'
            ];
            // echo json_encode([
            //     "status" => "FAILED",
            //     "responseType" => 1,
            //     "msg" => "Could not retrieve from API"
            // ]);
            // exit;
        }
        // if(empty($chithaPattadars->data)) {
        //     $this->db->trans_rollback();
        //     echo json_encode([
        //         "status" => "FAILED",
        //         "responseType" => 1,
        //         "msg" => "Nothing to merge. No chitha pattadars available in this village!"
        //     ]);
        //     exit;
        // }
        if (!empty($chithaPattadars->data)) {
            foreach ($chithaPattadars->data as $chithaPdar) {
                $pdarCheck = $this->db->query("SELECT pdar_id, patta_no, patta_type_code FROM chitha_pattadar WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND patta_no=? AND patta_type_code=? AND pdar_id=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $chithaPdar->patta_no, $chithaPdar->patta_type_code, $chithaPdar->pdar_id])->row();

                if (empty($pdarCheck)) {
                    //insert chitha_pattadar
                    $chithaPattadarArr = [
                        'dist_code' => $dist_code,
                        'subdiv_code' => $subdiv_code,
                        'cir_code' => $cir_code,
                        'mouza_pargona_code' => $mouza_pargona_code,
                        'lot_no' => $lot_no,
                        'vill_townprt_code' => $vill_townprt_code,
                        'pdar_id' => $chithaPdar->pdar_id,
                        'patta_no' => $chithaPdar->patta_no,
                        'patta_type_code' => $chithaPdar->patta_type_code,
                        'pdar_name' => $chithaPdar->pdar_name,
                        'pdar_guard_reln' => $chithaPdar->pdar_guard_reln,
                        'pdar_father' => $chithaPdar->pdar_father,
                        'pdar_add1' => (isset($chithaPdar->pdar_add1) && $chithaPdar->pdar_add1 != null) ? $chithaPdar->pdar_add1 : null,
                        'pdar_add2' => (isset($chithaPdar->pdar_add2) && $chithaPdar->pdar_add2 != null) ? $chithaPdar->pdar_add2 : null,
                        'pdar_add3' => (isset($chithaPdar->pdar_add3) && $chithaPdar->pdar_add3 != null) ? $chithaPdar->pdar_add3 : null,
                        'pdar_pan_no' => (isset($chithaPdar->pdar_pan_no) && $chithaPdar->pdar_pan_no != null) ? $chithaPdar->pdar_pan_no : null,
                        'pdar_citizen_no' => (isset($chithaPdar->pdar_citizen_no) && $chithaPdar->pdar_citizen_no != null) ? $chithaPdar->pdar_citizen_no : null,
                        'pdar_gender' => (isset($chithaPdar->pdar_gender) && $chithaPdar->pdar_gender != null) ? $chithaPdar->pdar_gender : null,
                        'user_code' => $chithaPdar->user_code,
                        'date_entry' => $chithaPdar->date_entry,
                        'operation' => $chithaPdar->operation,
                        'jama_yn' => $chithaPdar->jama_yn,
                    ];
                    if ($this->db->field_exists('pdar_relation', 'chitha_pattadar') && isset($chithaPdar->pdar_relation) && $chithaPdar->pdar_relation != null) {
                        $chithaPattadarArr['pdar_relation'] = $chithaPdar->pdar_relation;
                    }

                    $chithaPdarStatus = $this->db->insert('chitha_pattadar', $chithaPattadarArr);
                    if (!$chithaPdarStatus || $this->db->affected_rows() < 1) {
                        // $this->db->trans_rollback();
                        return [
                            'status' => 'n',
                            'msg' => 'Chitha Pattadar entry Failed in chitha pattadar!'
                        ];
                        // echo json_encode([
                        //     "status" => "FAILED",
                        //     "responseType" => 1,
                        //     "msg" => "Chitha Pattadar entry Failed in chitha pattadar"
                        // ]);
                        // exit;
                    }
                }
            }
        }

        //chitha_dag_pattadar
        $chithaDagPattadars = callLandhubAPIMerge('POST', 'NicApiMerge/getChithaDagPattadars', [
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'mouza_pargona_code' => $mouza_pargona_code,
            'lot_no' => $lot_no,
            'vill_townprt_code' => $vill_townprt_code
        ]);

        if ($chithaDagPattadars->responseType != 2) {
            // $this->db->trans_rollback();
            return [
                'status' => 'n',
                'msg' => 'Could not retrieve dag pattadars from API!'
            ];
            // echo json_encode([
            //     "status" => "FAILED",
            //     "responseType" => 1,
            //     "msg" => "Could not retrieve dag pattadars from API"
            // ]);
            // exit;
        }
        // if(empty($chithaDagPattadars->data)) {
        //     $this->db->trans_rollback();
        //     echo json_encode([
        //         "status" => "FAILED",
        //         "responseType" => 1,
        //         "msg" => "Nothing to merge. No chitha dag pattadars available in this village!"
        //     ]);
        //     exit;
        // }
        if (!empty($chithaDagPattadars->data)) {
            foreach ($chithaDagPattadars->data as $dagPdar) {
                $checkDagPdar = $this->db->query("SELECT * FROM chitha_dag_pattadar WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND patta_no=? AND patta_type_code=? AND dag_no=? AND pdar_id=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dagPdar->patta_no, $dagPdar->patta_type_code, $dagPdar->dag_no, $dagPdar->pdar_id])->row();

                $checkDagInChitha = $this->db->query("SELECT * FROM chitha_basic WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND dag_no=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dagPdar->dag_no])->row();

                if (empty($checkDagPdar) && !empty($checkDagInChitha)) {
                    //insert into chitha_dag_pattadar
                    $dagPattadarArr = array(
                        'dist_code' => $dist_code,
                        'subdiv_code' => $subdiv_code,
                        'cir_code' => $cir_code,
                        'mouza_pargona_code' => $mouza_pargona_code,
                        'lot_no' => $lot_no,
                        'vill_townprt_code' => $vill_townprt_code,
                        'dag_no' => $dagPdar->dag_no,
                        'pdar_id' => $dagPdar->pdar_id,
                        'patta_no' => $dagPdar->patta_no,
                        'patta_type_code' => $dagPdar->patta_type_code,
                        'dag_por_b' => $dagPdar->dag_por_b,
                        'dag_por_k' => $dagPdar->dag_por_k,
                        'dag_por_lc' => $dagPdar->dag_por_lc,
                        'dag_por_g' => $dagPdar->dag_por_g,
                        'dag_por_kr' => (isset($dagPdar->dag_por_kr) && $dagPdar->dag_por_kr != null) ? $dagPdar->dag_por_kr : null,
                        'pdar_land_n' => (isset($dagPdar->pdar_land_n) && $dagPdar->pdar_land_n != null) ? $dagPdar->pdar_land_n : null,
                        'pdar_land_s' => (isset($dagPdar->pdar_land_s) && $dagPdar->pdar_land_s != null) ? $dagPdar->pdar_land_s : null,
                        'pdar_land_e' => (isset($dagPdar->pdar_land_e) && $dagPdar->pdar_land_e != null) ? $dagPdar->pdar_land_e : null,
                        'pdar_land_w' => (isset($dagPdar->pdar_land_w) && $dagPdar->pdar_land_w != null) ? $dagPdar->pdar_land_w : null,
                        'pdar_land_acre' => (isset($dagPdar->pdar_land_acre) && $dagPdar->pdar_land_acre != null) ? $dagPdar->pdar_land_acre : null,
                        'pdar_land_revenue' => (isset($dagPdar->pdar_land_revenue) && $dagPdar->pdar_land_revenue != null) ? $dagPdar->pdar_land_revenue : null,
                        'pdar_land_localtax' => (isset($dagPdar->pdar_land_localtax) && $dagPdar->pdar_land_localtax != null) ? $dagPdar->pdar_land_localtax : null,
                        'user_code' => $dagPdar->user_code,
                        'date_entry' => $dagPdar->date_entry,
                        'operation' => $dagPdar->operation,
                        'p_flag' => (isset($dagPdar->p_flag) && $dagPdar->p_flag != null) ? $dagPdar->p_flag : null,
                        'jama_yn' => (isset($dagPdar->jama_yn) && $dagPdar->jama_yn != null) ? $dagPdar->jama_yn : null,
                        'pdar_land_map' => (isset($dagPdar->pdar_land_map) && $dagPdar->pdar_land_map != null) ? $dagPdar->pdar_land_map : null,

                    );
                    $dagPattadarStatus = $this->db->insert('chitha_dag_pattadar', $dagPattadarArr);
                    if (!$dagPattadarStatus || $this->db->affected_rows() < 1) {
                        // $this->db->trans_rollback();
                        return [
                            'status' => 'n',
                            'msg' => 'Chitha Dag Pattadar entry Failed in chitha dag pattadar!'
                        ];
                        // echo json_encode([
                        //     "status" => "FAILED",
                        //     "responseType" => 1,
                        //     "msg" => "Chitha Dag Pattadar entry Failed in chitha dag pattadar"
                        // ]);
                        // exit;
                    }
                }
            }
        }


        //chitha_rmk_lmnote
        $chithaLmNotes = callLandhubAPIMerge('POST', 'NicApiMerge/getChithaLmNote', [
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'mouza_pargona_code' => $mouza_pargona_code,
            'lot_no' => $lot_no,
            'vill_townprt_code' => $vill_townprt_code
        ]);

        if ($chithaLmNotes->responseType != 2) {
            // $this->db->trans_rollback();
            return [
                'status' => 'n',
                'msg' => 'Could not retrieve lm notes from API!'
            ];
            // echo json_encode([
            //     "status" => "FAILED",
            //     "responseType" => 1,
            //     "msg" => "Could not retrieve lm notes from API"
            // ]);
            // exit;
        }


        if (!empty($chithaLmNotes->data)) {
            foreach ($chithaLmNotes->data as $lmnote) {
                $checkLmNote = $this->db->query("SELECT * FROM chitha_rmk_lmnote WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND dag_no=? AND lm_note_cron_no=? AND rmk_type_hist_no=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $lmnote->dag_no, $lmnote->lm_note_cron_no, $lmnote->rmk_type_hist_no])->row();

                $checkDagInChitha = $this->db->query("SELECT * FROM chitha_basic WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND dag_no=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $lmnote->dag_no])->row();

                if (empty($checkLmNote) && !empty($checkDagInChitha)) {
                    $lmnoteArr = [
                        'dist_code' => $dist_code,
                        'subdiv_code' => $subdiv_code,
                        'cir_code' => $cir_code,
                        'mouza_pargona_code' => $mouza_pargona_code,
                        'lot_no' => $lot_no,
                        'vill_townprt_code' => $vill_townprt_code,
                        'dag_no' => $lmnote->dag_no,
                        'lm_note_cron_no' => $lmnote->lm_note_cron_no,
                        'rmk_type_hist_no' => $lmnote->rmk_type_hist_no,
                        'lm_note_lno' => $lmnote->lm_note_lno,
                        'lm_note' => $lmnote->lm_note,
                        'lm_note_date' => (isset($lmnote->lm_note_date) && $lmnote->lm_note_date != null) ? $lmnote->lm_note_date : null,
                        'lm_code' => (isset($lmnote->lm_code) && $lmnote->lm_code != null) ? $lmnote->lm_code : null,
                        'lm_sign' => $lmnote->lm_sign,
                        'co_approval' => $lmnote->co_approval,
                        'user_code' => $lmnote->user_code,
                        'date_entry' => $lmnote->date_entry,
                        'operation' => $lmnote->operation
                    ];
                    $lmnoteStatus = $this->db->insert('chitha_rmk_lmnote', $lmnoteArr);
                    if (!$lmnoteStatus || $this->db->affected_rows() < 1) {
                        // $this->db->trans_rollback();
                        return [
                            'status' => 'n',
                            'msg' => 'Chitha LM Note entry Failed in lmnote table!'
                        ];
                        // echo json_encode([
                        //     "status" => "FAILED",
                        //     "responseType" => 1,
                        //     "msg" => "Chitha LM Note entry Failed in lmnote table"
                        // ]);
                        // exit;
                    }
                }
            }
        }

        // if(!$this->db->trans_status()) {
        //     $this->db->trans_rollback();
        //     return [
        //         'status' => 'n',
        //         'msg' => 'DB Transaction Failed!' 
        //     ];
        // }

        // $this->db->trans_commit();

        return [
            'status' => 'y',
            'msg' => 'Successfully merged all dharitree data to chitha!'
        ];

        // echo json_encode([
        //     "status" => "SUCCESS",
        //     "responseType" => 2,
        //     "msg" => "Successfully merged all dharitree data to chitha",
        // ]);
        // exit;
    }


    public function getPartdagDataPreview()
    {
        $this->load->helper('cookie');

        header('Content-Type: application/json');
        if ($_SERVER['CONTENT_TYPE'] == 'application/json') {
            $data = json_decode(file_get_contents('php://input', true));
            $msg = null;
            
            if (!isset($data) || $data == null)
                $msg = $msg . " Missing Parameters,";

            if (!isset($data->vill_townprt_code) || $data->vill_townprt_code == null)
                $msg = $msg . " Missing Village Code,";
            if (!isset($data->dag_no) || $data->dag_no == null)
                $msg = $msg . " Missing Dag No";
            if (!isset($data->part_dag) || $data->part_dag == null)
                $msg = $msg . " Missing Part Dag No";
            if ($msg != null && !empty($msg)) {
                $response = [
                    'status' => 'n',
                    'msg' => $msg
                ];
                $this->output->set_status_header(401);  // Change to 400, 401, 500, etc. as needed
                echo json_encode($response);
                exit;
            }


            $villageCode = $data->vill_townprt_code;
            $dagNo = $data->dag_no;
            $partDag = $data->part_dag;
        } else {
            $msg = null;
            
            
            if (!isset($_POST['vill_townprt_code']) || empty($_POST['vill_townprt_code']))
                $msg = $msg . " Missing Village Code,";
            if (!isset($_POST['dag_no']) || empty($_POST['dgetag_no']))
                $msg = $msg . " Missing Dag No ";
            if (!isset($_POST['part_dag']) || empty($_POST['part_dag']))
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


            $villageCode = $_POST['vill_townprt_code'];
            $dagNo = $_POST['dag_no'];
            $partDag = $_POST['part_dag'];
            // $user_name = $_POST['user_name'];
            // $password = $_POST['password'];
        }

        $locationArr = explode('-', $villageCode);

        $dist_code = $locationArr[0];
        $subdiv_code = $locationArr[1];
        $cir_code = $locationArr[2];
        $mouza_pargona_code = $locationArr[3];
        $lot_no = $locationArr[4];
        $vill_townprt_code = $locationArr[5];
        $dag_no = $dagNo;
        $part_dag = $partDag;

        $this->dbswitch($dist_code);

        //first search in chitha whether updated
        $partDagDetailsChitha = $this->db->query('SELECT * FROM chitha_basic_splitted_dags WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND dag_no=? AND survey_no=?', [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag_no, $part_dag])->row();
        $partDagDetailsChitha->dist_name = $this->db->query("SELECT loc_name FROM location WHERE dist_code=? and subdiv_code=?", [$dist_code, '00'])->row()->loc_name;
        $partDagDetailsChitha->subdiv_name = $this->db->query("SELECT loc_name FROM location WHERE dist_code=? and subdiv_code=? and cir_code=?", [$dist_code, $subdiv_code, '00'])->row()->loc_name;
        $partDagDetailsChitha->cir_name = $this->db->query("SELECT loc_name FROM location WHERE dist_code=? and subdiv_code=? and cir_code=? and mouza_pargona_code=?", [$dist_code, $subdiv_code, $cir_code, '00'])->row()->loc_name;
        $partDagDetailsChitha->mouza_name = $this->db->query("SELECT loc_name FROM location WHERE dist_code=? and subdiv_code=? and cir_code=? and mouza_pargona_code=? and lot_no=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, '00'])->row()->loc_name;
        $partDagDetailsChitha->lot_name = $this->db->query("SELECT loc_name FROM location WHERE dist_code=? and subdiv_code=? and cir_code=? and mouza_pargona_code=? and lot_no=? and vill_townprt_code=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, '00000'])->row()->loc_name;
        $partDagDetailsChitha->village_name = $this->db->query("SELECT loc_name FROM location WHERE dist_code=? and subdiv_code=? and cir_code=? and mouza_pargona_code=? and lot_no=? and vill_townprt_code=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code])->row()->loc_name;
        $current_landclass_name = $this->db->query("SELECT name_ass FROM land_class_groups WHERE land_class_code=?", [$partDagDetailsChitha->land_class_code])->row();
        $partDagDetailsChitha->current_landclass_name = (!empty($current_landclass_name)) ? $current_landclass_name->name_ass : '';

        if (!empty($partDagDetailsChitha)) {
            $partDagDetailsChitha->from_chitha = 1;
            $partDagDetailsChitha->from_bhunaksha = 0;

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

            $pattadars = $this->db->query("SELECT cdp.*, cp.pdar_name, cp.pdar_father FROM chitha_dag_pattadar cdp, chitha_pattadar cp WHERE cdp.dist_code=cp.dist_code AND cdp.subdiv_code=cp.subdiv_code AND cdp.cir_code=cp.cir_code AND cdp.mouza_pargona_code=cp.mouza_pargona_code AND cdp.lot_no=cp.lot_no AND cdp.vill_townprt_code=cp.vill_townprt_code AND cdp.patta_no=cp.patta_no AND cdp.patta_type_code=cp.patta_type_code AND cdp.pdar_id=cp.pdar_id AND cdp.dist_code=? AND cdp.subdiv_code=? AND cdp.cir_code=? AND cdp.mouza_pargona_code=? AND cdp.lot_no=? AND cdp.vill_townprt_code=? AND cdp.patta_no=? AND cdp.patta_type_code=? AND cdp.dag_no=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $partDagDetailsChitha->patta_no, $partDagDetailsChitha->patta_type_code, $partDagDetailsChitha->survey_no])->result();

            // $partDagPattadars = [];
            if (!empty($pattadars)) {
                foreach ($pattadars as $pattadar) {
                    $pattadar->label = $pattadar->pdar_name . ' (' . $pattadar->pdar_father . ')';
                    $pattadar->value = $dist_code . '-' . $subdiv_code . '-' . $cir_code . '-' . $mouza_pargona_code . '-' . $lot_no . '-' . $vill_townprt_code . '-' . $pattadar->patta_no . '-' . $pattadar->patta_type_code . '-' . $pattadar->pdar_id;

                    // $row['label'] = $pattadar->pdar_name . ' (' . $pattadar->pdar_father . ')';
                    // $row['value'] = $dist_code . '-' . $subdiv_code . '-' . $cir_code . '-' . $mouza_pargona_code . '-' . $lot_no . '-' . $vill_townprt_code . '-' . $pattadar->patta_no . '-' . $pattadar->patta_type_code . '-' . $pattadar->pdar_id;

                    // $partDagPattadars[] = $row;
                }
            }

            $partDagDetailsChitha->pattadars = $pattadars;
            $partDagDetailsChitha->possessors = $possessors;

            $partDagDetailsChitha->tenants = $this->db->query("SELECT * FROM chitha_tenant WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND dag_no=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $partDagDetailsChitha->survey_no])->result();

            $response = [
                'status' => 'y',
                'msg' => 'Successfully retrieved data!',
                'data' => $partDagDetailsChitha
            ];
            $this->output->set_status_header(200);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
        }else{
            $response = [
                'status' => 'n',
                'msg' => 'No data found for the given part dag!',
                'data' => null
            ];
            $this->output->set_status_header(200);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
        }

        
    }

}
