<?php
defined('BASEPATH') or exit('No direct script access allowed');
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class ReportApiController extends CI_Controller
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
        $this->load->model('Api/Report_model');
    }

    // 1️⃣ Get Districts summary
    public function districts()
    {
        $resurvey_districts = RESURVEY_DISTRICTS; // assumed constant: [ '17' => 'Dibrugarh', ... ]
        $data = [];

        foreach ($resurvey_districts as $dist_code => $district_name) {
            // switch DB connection for the district
            $this->dbswitch($dist_code);

            // fetch circle count safely
            $circles_count_query = $this->db->query("
                SELECT COUNT(*) AS cnt 
                FROM location 
                WHERE dist_code = ? 
                AND cir_code != '00' 
                AND mouza_pargona_code = '00'
            ", ["$dist_code"]);


            $circles_count = ($circles_count_query->num_rows() > 0) ? $circles_count_query->row()->cnt : 0;

            $data_count_query = $this->db->query("select count(*) as cnt from chitha_basic_splitted_dags where dist_code = ?", ["$dist_code"]);
            $data_count = ($data_count_query->num_rows() > 0) ? $data_count_query->row()->cnt : 0;


            // push to data array as associative (not object index)
            $data[] = [
                'dist_code'      => $dist_code,
                'name'           => $district_name,
                'circles_count'  => (int) $circles_count,
                'data_collection'     => (int) $data_count
            ];
        }

        echo json_encode($data);
    }

    // 2️⃣ Get Circles by District
    public function circles()
    {
        $district = $this->input->get('district');
        if (!$district) return $this->_error("Missing district code");
        $this->dbswitch($district);
        $result = $this->Report_model->getReportCircles($district);
        echo json_encode($result);
    }

    // 3️⃣ Get Mouzas by Circle
    public function mouzas()
    {
        $circle = $this->input->get('circle');
        if (!$circle) return $this->_error("Missing circle code");
        $codes = explode('-', $circle);
        if (count($codes) != 3) return $this->_error("Invalid circle code format");
        $district = $codes[0];
        $subdiv = $codes[1];
        $circode = $codes[2];
        $this->dbswitch($district);
        $result = $this->Report_model->getReportMouzas($district, $subdiv, $circode);
        echo json_encode($result);
    }

    // 4️⃣ Get Lots by Mouza
    public function lots()
    {
        $mouza = $this->input->get('mouza');
        if (!$mouza) return $this->_error("Missing mouza code");
        $codes = explode('-', $mouza);
        if (count($codes) != 4) return $this->_error("Invalid mouza code format");
        $district = $codes[0];
        $subdiv = $codes[1];
        $circode = $codes[2];
        $mouzacode = $codes[3];
        $this->dbswitch($district);
        $result = $this->Report_model->getReportLots($district, $subdiv, $circode, $mouzacode);
        echo json_encode($result);
    }

    // 5️⃣ Get Villages by Lot
    public function villages()
    {
        $lot = $this->input->get('lot');
        if (!$lot) return $this->_error("Missing lot code");
        $codes = explode('-', $lot);
        if (count($codes) != 5) return $this->_error("Invalid lot code format");
        $district = $codes[0];
        $subdiv = $codes[1];
        $circode = $codes[2];
        $mouzacode = $codes[3];
        $lotcode = $codes[4];
        $this->dbswitch($district);
        $result = $this->Report_model->getReportVillages($district, $subdiv, $circode, $mouzacode, $lotcode);
        echo json_encode($result);
    }

    // 6️⃣ Final DAG-level report for selected village
    public function dags()
    {
        $village = $this->input->get('village');
        if (!$village) return $this->_error("Missing village code");
        $codes = explode('-', $village);
        if (count($codes) != 6) return $this->_error("Invalid village code format");
        $district = $codes[0];
        $this->dbswitch($district);
        $subdiv = $codes[1];
        $circode = $codes[2];
        $mouzacode = $codes[3];
        $lotcode = $codes[4];
        $villcode = $codes[5];
        $this->dbswitch($district);
        $result = $this->Report_model->getDagReport($district, $subdiv, $circode, $mouzacode, $lotcode, $villcode);
        echo json_encode($result);
    }

    // 6️⃣ Final DAG-level report for selected village
    public function collection_report()
    {
        $village = $this->input->get('village');
        if (!$village) return $this->_error("Missing village code");
        $codes = explode('-', $village);
        if (count($codes) != 6) return $this->_error("Invalid village code format");
        $district = $codes[0];
        $this->dbswitch($district);
        $subdiv = $codes[1];
        $circode = $codes[2];
        $mouzacode = $codes[3];
        $lotcode = $codes[4];
        $villcode = $codes[5];
        $this->dbswitch($district);
        $result = $this->Report_model->getSurveyCollectionReport($district, $subdiv, $circode, $mouzacode, $lotcode, $villcode);
        echo json_encode($result);
    }


    private function _error($message)
    {
        echo json_encode(["status" => false, "error" => $message]);
        exit;
    }
}
