<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class ResurveyReportController extends CI_Controller
{
    use CommonTrait;
    private $jwt_data;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Api/ResurveyReportModel');

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
    public function getResurveyReportData()
    {
        $this->load->helper('cookie');


        header('Content-Type: application/json');
        $response = [
            'status' => 'y',
            'msg' => 'Resurvey Report Data fetched successfully',
            'data' => [
                'entered_splitted_dags' => $this->getEnteredSplittedDags(),
            ]
        ];
        echo json_encode($response);
    }
    public function getEnteredSplittedDags()
    {
        $CI = &get_instance();
        $data = [];
        $usercode = $this->jwt_data->usercode;

        foreach (RESURVEY_DISTRICTS as $dcode => $name) {
            try {
                $this->dbswitch($dcode);
                $dist_data = [];
                $dist_data['dist_code'] = $dcode;
                $dist_data['dist_name'] = $name;
                if ($this->db->table_exists('chitha_basic_splitted_dags')) {
                    // Get total count for this user
                    $totalCount = $this->db->query(
                        "SELECT COUNT(*) as total
                        FROM chitha_basic_splitted_dags cb
                        LEFT JOIN location l_circle ON cb.dist_code = l_circle.dist_code AND cb.subdiv_code = l_circle.subdiv_code AND cb.cir_code = l_circle.cir_code
                        WHERE l_circle.mouza_pargona_code = '00'
                        AND cb.user_code = '{$usercode}'"
                    )->row()->total;
                    
                    // Get all data with patta type and land class
                    $chitha_basic_splitted_dags = $this->db->query(
                        "SELECT cb.dist_code, cb.subdiv_code, cb.cir_code, cb.mouza_pargona_code, cb.lot_no, cb.vill_townprt_code, cb.dag_no as old_dag_no, cb.survey_no as dag_no, cb.patta_no, cb.patta_type_code, pc.patta_type as patta_type, cb.land_class_code, lcg.name as land_class, cb.user_code, cb.date_entry
                        ,l_village.loc_name as village_name, l_circle.loc_name as circle_name
                        FROM chitha_basic_splitted_dags cb
                        LEFT JOIN patta_code pc ON cb.patta_type_code = pc.type_code
                        LEFT JOIN land_class_groups lcg ON cb.land_class_code = lcg.land_class_code
                        LEFT JOIN location l_village ON cb.dist_code = l_village.dist_code AND cb.subdiv_code = l_village.subdiv_code AND cb.cir_code = l_village.cir_code AND cb.mouza_pargona_code = l_village.mouza_pargona_code AND cb.lot_no = l_village.lot_no AND cb.vill_townprt_code = l_village.vill_townprt_code
                        LEFT JOIN location l_circle ON cb.dist_code = l_circle.dist_code AND cb.subdiv_code = l_circle.subdiv_code AND cb.cir_code = l_circle.cir_code
                        WHERE l_circle.mouza_pargona_code = '00'
                        AND cb.user_code = '{$usercode}'
                        ORDER BY cb.date_entry DESC limit 10"
                    )->result();
                    $dist_data['total_count'] = $totalCount;
                    $dist_data['chitha_basic_splitted_dags'] = $chitha_basic_splitted_dags;
                } else {
                    $dist_data['chitha_basic_splitted_dags'] = "Table not found";
                }
                $this->db->close(); // cleanup connection
                $data[$name] = $dist_data;
            } catch (Exception $e) {
                $data[$name] = "Connection failed: " . $e->getMessage();
            }
        }
        return $data;
    }

    public function getResurveyReportDistData()
    {
        $this->load->helper('cookie');
        $request_data = json_decode(file_get_contents('php://input', true));
        $dist_code   = isset($request_data->dist_code) ? $request_data->dist_code : null;
        $page        = isset($request_data->page) ? (int)$request_data->page : 1;
        $pageSize    = isset($request_data->pageSize) ? (int)$request_data->pageSize : 10;
        $usercode = $this->jwt_data->usercode;

        header('Content-Type: application/json');

        if (!$dist_code) {
            echo json_encode([
                'status' => 'n',
                'msg'    => 'District code is required',
            ]);
            return;
        }

        $this->dbswitch($dist_code);
        $dist_data = [];
        $dist_data['dist_name'] = RESURVEY_DISTRICTS[$dist_code] ?? null;

        if ($this->db->table_exists('chitha_basic_splitted_dags')) {
            // 1️⃣ Total count query
            $totalCount = $this->db->query("
            SELECT COUNT(*) as total
            FROM chitha_basic_splitted_dags cb
            LEFT JOIN location l_circle 
                ON cb.dist_code = l_circle.dist_code 
                AND cb.subdiv_code = l_circle.subdiv_code 
                AND cb.cir_code = l_circle.cir_code
            WHERE l_circle.mouza_pargona_code = '00'
            AND cb.user_code = '{$usercode}'
        ")->row()->total;

            // 2️⃣ Pagination calculation
            $offset = ($page - 1) * $pageSize;

            // 3️⃣ Main query with LIMIT + OFFSET
            $chitha_basic_splitted_dags = $this->db->query("
            SELECT 
                cb.dist_code, cb.subdiv_code, cb.cir_code, cb.mouza_pargona_code, cb.lot_no, cb.vill_townprt_code,
                cb.dag_no as old_dag_no, cb.survey_no as dag_no, cb.patta_no, cb.patta_type_code,
                pc.patta_type as patta_type, cb.land_class_code, lcg.name as land_class, 
                cb.user_code, cb.date_entry,
                l_village.loc_name as village_name, 
                l_circle.loc_name as circle_name
            FROM chitha_basic_splitted_dags cb
            LEFT JOIN patta_code pc ON cb.patta_type_code = pc.type_code
            LEFT JOIN land_class_groups lcg ON cb.land_class_code = lcg.land_class_code
            LEFT JOIN location l_village 
                ON cb.dist_code = l_village.dist_code 
                AND cb.subdiv_code = l_village.subdiv_code 
                AND cb.cir_code = l_village.cir_code 
                AND cb.mouza_pargona_code = l_village.mouza_pargona_code 
                AND cb.lot_no = l_village.lot_no 
                AND cb.vill_townprt_code = l_village.vill_townprt_code
            LEFT JOIN location l_circle 
                ON cb.dist_code = l_circle.dist_code 
                AND cb.subdiv_code = l_circle.subdiv_code 
                AND cb.cir_code = l_circle.cir_code
            WHERE l_circle.mouza_pargona_code = '00'
            AND cb.user_code = '{$usercode}'
            ORDER BY cb.date_entry DESC
            LIMIT {$pageSize} OFFSET {$offset}
        ")->result();

            $dist_data['chitha_basic_splitted_dags'] = $chitha_basic_splitted_dags;
            $dist_data['totalCount'] = $totalCount; // 👈 add totalCount for frontend
        } else {
            $dist_data['chitha_basic_splitted_dags'] = [];
            $dist_data['totalCount'] = 0;
        }

        $this->db->close(); // cleanup connection

        $response = [
            'status' => 'y',
            'msg'    => 'Resurvey Report Data fetched successfully',
            'data'   => $dist_data
        ];
        echo json_encode($response);
    }
    public function getResurveyReportCoData()
    {
        $this->load->helper('cookie');
        $request_data = json_decode(file_get_contents('php://input', true));
        $dist_code   = $this->jwt_data->dcode;
        $subdiv_code = $this->jwt_data->subdiv_code;
        $cir_code    = $this->jwt_data->cir_code;
        $page        = isset($request_data->page) ? (int)$request_data->page : 1;
        $pageSize    = isset($request_data->pageSize) ? (int)$request_data->pageSize : 10;

        header('Content-Type: application/json');

        if (!$dist_code || !$subdiv_code || !$cir_code) {
            echo json_encode([
                'status' => 'n',
                'msg'    => 'Location codes are required',
            ]);
            return;
        }

        $this->dbswitch($dist_code);
        $data = [];
        $location = $this->db->query("
            SELECT l.loc_name as circle_name, l_dist.loc_name as dist_name, l_subdiv.loc_name as subdiv_name
            FROM location l
            join location l_dist 
                ON l.dist_code = l_dist.dist_code 
                AND l_dist.subdiv_code = '00'
            join location l_subdiv 
                ON l.dist_code = l_subdiv.dist_code 
                AND l.subdiv_code = l_subdiv.subdiv_code 
                AND l_subdiv.cir_code = '00'
            WHERE l.dist_code = '{$dist_code}' 
            AND l.subdiv_code = '{$subdiv_code}' 
            AND l.cir_code = '{$cir_code}' 
            AND l.mouza_pargona_code = '00'
        ")->row();
        $data['circle_name'] = $location ? $location->circle_name : null;
        $data['dist_name'] = $location ? $location->dist_name : null;
        $data['subdiv_name'] = $location ? $location->subdiv_name : null;


        if ($this->db->table_exists('chitha_basic_splitted_dags')) {
            // Total count query
            $totalCount = $this->db->query("
            SELECT COUNT(*) as total
            FROM chitha_basic_splitted_dags cb
            LEFT JOIN location l_circle 
                ON cb.dist_code = l_circle.dist_code 
                AND cb.subdiv_code = l_circle.subdiv_code 
                AND cb.cir_code = l_circle.cir_code
            WHERE l_circle.mouza_pargona_code = '00'
            AND cb.subdiv_code = '{$subdiv_code}'
            AND cb.cir_code = '{$cir_code}'
        ")->row()->total;

            // Pagination calculation
            $offset = ($page - 1) * $pageSize;

            //  Main query with LIMIT + OFFSET
            $chitha_basic_splitted_dags = $this->db->query("
            SELECT 
                cb.dist_code, cb.subdiv_code, cb.cir_code, cb.mouza_pargona_code, cb.lot_no, cb.vill_townprt_code,
                cb.dag_no as old_dag_no, cb.survey_no as dag_no, cb.patta_no, cb.patta_type_code,
                pc.patta_type as patta_type, cb.land_class_code, lcg.name as land_class, 
                cb.user_code, cb.date_entry,
                l_village.loc_name as village_name, 
                l_circle.loc_name as circle_name
            FROM chitha_basic_splitted_dags cb
            LEFT JOIN patta_code pc ON cb.patta_type_code = pc.type_code
            LEFT JOIN land_class_groups lcg ON cb.land_class_code = lcg.land_class_code
            LEFT JOIN location l_village 
                ON cb.dist_code = l_village.dist_code 
                AND cb.subdiv_code = l_village.subdiv_code 
                AND cb.cir_code = l_village.cir_code 
                AND cb.mouza_pargona_code = l_village.mouza_pargona_code 
                AND cb.lot_no = l_village.lot_no 
                AND cb.vill_townprt_code = l_village.vill_townprt_code
            LEFT JOIN location l_circle 
                ON cb.dist_code = l_circle.dist_code 
                AND cb.subdiv_code = l_circle.subdiv_code 
                AND cb.cir_code = l_circle.cir_code
            WHERE l_circle.mouza_pargona_code = '00'
            AND cb.subdiv_code = '{$subdiv_code}'
            AND cb.cir_code = '{$cir_code}'
            ORDER BY cb.date_entry DESC
            LIMIT {$pageSize} OFFSET {$offset}
        ")->result();

            $data['chitha_basic_splitted_dags'] = $chitha_basic_splitted_dags;
            $data['totalCount'] = $totalCount;
        } else {
            $data['chitha_basic_splitted_dags'] = [];
            $data['totalCount'] = 0;
        }

        $this->db->close(); // cleanup connection

        $response = [
            'status' => 'y',
            'msg'    => 'Resurvey Report Data fetched successfully',
            'data'   => $data
        ];
        echo json_encode($response);
    }

    public function getResurveyCoDashData()
    {
        $dist_code   = $this->jwt_data->dcode;
        $subdiv_code = $this->jwt_data->subdiv_code;
        $cir_code    = $this->jwt_data->cir_code;

        header('Content-Type: application/json');

        if (!$dist_code || !$subdiv_code || !$cir_code) {
            echo json_encode([
                'status' => 'n',
                'msg'    => 'Location codes are required',
            ]);
            return;
        }

        $this->dbswitch($dist_code);
        $data = [];

        $location = $this->db->query("
            SELECT l.loc_name as circle_name, l_dist.loc_name as dist_name, l_subdiv.loc_name as subdiv_name
            FROM location l
            join location l_dist 
                ON l.dist_code = l_dist.dist_code 
                AND l_dist.subdiv_code = '00'
            join location l_subdiv 
                ON l.dist_code = l_subdiv.dist_code 
                AND l.subdiv_code = l_subdiv.subdiv_code 
                AND l_subdiv.cir_code = '00'
            WHERE l.dist_code = '{$dist_code}' 
            AND l.subdiv_code = '{$subdiv_code}' 
            AND l.cir_code = '{$cir_code}' 
            AND l.mouza_pargona_code = '00'
        ")->row();
        $data['circle_name'] = $location ? $location->circle_name : null;
        $data['dist_name'] = $location ? $location->dist_name : null;
        $data['subdiv_name'] = $location ? $location->subdiv_name : null;

        if ($this->db->table_exists('chitha_basic_splitted_dags')) {
            // Total count query
            $totalCount = $this->db->query("
                SELECT COUNT(*) as total
                FROM chitha_basic_splitted_dags cb
                LEFT JOIN location l_circle 
                    ON cb.dist_code = l_circle.dist_code 
                    AND cb.subdiv_code = l_circle.subdiv_code 
                    AND cb.cir_code = l_circle.cir_code
                WHERE l_circle.mouza_pargona_code = '00'
                AND cb.subdiv_code = '{$subdiv_code}'
                AND cb.cir_code = '{$cir_code}'
            ")->row()->total;
            $data['totalCount'] = $totalCount;
        } else {
            $data['totalCount'] = 0;
        }

        $this->db->close(); // cleanup connection

        $response = [
            'status' => 'y',
            'msg'    => 'Resurvey Report Data fetched successfully',
            'data'   => $data
        ];
        echo json_encode($response);
    }

    public function getResurveyLmDashData()
    {
        $dist_code   = $this->jwt_data->dcode;
        $subdiv_code = $this->jwt_data->subdiv_code;
        $cir_code    = $this->jwt_data->cir_code;
        $mouza_pargona_code = $this->jwt_data->mouza_pargona_code;
        $lot_no = $this->jwt_data->lot_no;

        header('Content-Type: application/json');

        if (!$dist_code || !$subdiv_code || !$cir_code || !$mouza_pargona_code || !$lot_no) {
            echo json_encode([
                'status' => 'n',
                'msg'    => 'Location codes are required',
            ]);
            return;
        }

        $this->dbswitch($dist_code);
        $data = [];

        $location = $this->db->query("
            SELECT 
            l.loc_name as circle_name, 
            l_dist.loc_name as dist_name, 
            l_subdiv.loc_name as subdiv_name,
            l_mouza.loc_name as mouza_name,
            l_lot.loc_name as lot_name
            FROM location l
            JOIN location l_dist 
            ON l.dist_code = l_dist.dist_code 
            AND l_dist.subdiv_code = '00'
            JOIN location l_subdiv 
            ON l.dist_code = l_subdiv.dist_code 
            AND l.subdiv_code = l_subdiv.subdiv_code 
            AND l_subdiv.cir_code = '00'
            JOIN location l_mouza
            ON l.dist_code = l_mouza.dist_code
            AND l.subdiv_code = l_mouza.subdiv_code
            AND l.cir_code = l_mouza.cir_code
            AND l.mouza_pargona_code = l_mouza.mouza_pargona_code
            AND l_mouza.lot_no = '00'
            JOIN location l_lot
            ON l.dist_code = l_lot.dist_code
            AND l.subdiv_code = l_lot.subdiv_code
            AND l.cir_code = l_lot.cir_code
            AND l.mouza_pargona_code = l_lot.mouza_pargona_code
            AND l.lot_no = l_lot.lot_no
            WHERE l.dist_code = '{$dist_code}' 
            AND l.subdiv_code = '{$subdiv_code}' 
            AND l.cir_code = '{$cir_code}' 
            AND l.mouza_pargona_code = '{$mouza_pargona_code}'
            AND l.lot_no = '{$lot_no}'
        ")->row();

        $data['circle_name'] = $location ? $location->circle_name : null;
        $data['dist_name'] = $location ? $location->dist_name : null;
        $data['subdiv_name'] = $location ? $location->subdiv_name : null;
        $data['mouza_name'] = $location ? $location->mouza_name : null;
        $data['lot_name'] = $location ? $location->lot_name : null;

        if ($this->db->table_exists('chitha_basic_splitted_dags')) {
            // Total count query up to lot_no
            $totalCount = $this->db->query("
            SELECT COUNT(*) as total
            FROM chitha_basic_splitted_dags cb
            LEFT JOIN location l_circle 
                ON cb.dist_code = l_circle.dist_code 
                AND cb.subdiv_code = l_circle.subdiv_code 
                AND cb.cir_code = l_circle.cir_code
            WHERE l_circle.mouza_pargona_code = '00'
            AND cb.subdiv_code = '{$subdiv_code}'
            AND cb.cir_code = '{$cir_code}'
            AND cb.mouza_pargona_code = '{$mouza_pargona_code}'
            AND cb.lot_no = '{$lot_no}'
            ")->row()->total;
            $data['totalCount'] = $totalCount;
        } else {
            $data['totalCount'] = 0;
        }

        $this->db->close(); // cleanup connection

        $response = [
            'status' => 'y',
            'msg'    => 'Resurvey Report Data fetched successfully',
            'data'   => $data
        ];
        echo json_encode($response);
    }

    public function getResurveyReport()
    {
        $this->load->helper('cookie');
        $request_data = json_decode(file_get_contents('php://input', true));

        // Extract filter parameters
        $dist_code   = isset($request_data->dist_code) ? $request_data->dist_code : null;
        $subdiv_code = isset($request_data->subdiv_code) ? $request_data->subdiv_code : null;
        $cir_code    = isset($request_data->cir_code) ? $request_data->cir_code : null;
        $mouza_pargona_code = isset($request_data->mouza_pargona_code) ? $request_data->mouza_pargona_code : null;
        $lot_no = isset($request_data->lot_no) ? $request_data->lot_no : null;
        $vill_townprt_code = isset($request_data->vill_townprt_code) ? $request_data->vill_townprt_code : null;
        $page        = isset($request_data->page) ? (int)$request_data->page : 1;
        $pageSize    = isset($request_data->pageSize) ? (int)$request_data->pageSize : 10;

        header('Content-Type: application/json');

        // Validate district code
        if (!$dist_code) {
            echo json_encode([
                'status' => 'n',
                'msg'    => 'District code is required',
            ]);
            return;
        }

        $this->dbswitch($dist_code);
        $dist_data = [];
        $dist_data['dist_name'] = RESURVEY_DISTRICTS[$dist_code] ?? null;

        // Build the dynamic WHERE clause
        $whereConditions = "WHERE l_circle.mouza_pargona_code = '00'"; // Default condition (common for all)

        // Add conditions based on the filters provided
        if ($subdiv_code) {
            $whereConditions .= " AND cb.subdiv_code = '{$subdiv_code}'";
        }
        if ($cir_code) {
            $whereConditions .= " AND cb.cir_code = '{$cir_code}'";
        }
        if ($mouza_pargona_code) {
            $whereConditions .= " AND cb.mouza_pargona_code = '{$mouza_pargona_code}'";
        }
        if ($lot_no) {
            $whereConditions .= " AND cb.lot_no = '{$lot_no}'";
        }
        if ($vill_townprt_code) {
            $whereConditions .= " AND cb.vill_townprt_code = '{$vill_townprt_code}'";
        }

        // 1️⃣ Total count query with dynamic WHERE
        $totalCount = $this->db->query("
        SELECT COUNT(*) as total
        FROM chitha_basic_splitted_dags cb
        LEFT JOIN location l_circle 
            ON cb.dist_code = l_circle.dist_code 
            AND cb.subdiv_code = l_circle.subdiv_code 
            AND cb.cir_code = l_circle.cir_code
        {$whereConditions}
    ")->row()->total;

        // 2️⃣ Pagination calculation
        $offset = ($page - 1) * $pageSize;

        // 3️⃣ Main query with dynamic WHERE and LIMIT + OFFSET
        $chitha_basic_splitted_dags = $this->db->query("
        SELECT 
            cb.dist_code, cb.subdiv_code, cb.cir_code, cb.mouza_pargona_code, cb.lot_no, cb.vill_townprt_code,
            cb.dag_no as old_dag_no, cb.survey_no as dag_no, cb.patta_no, cb.patta_type_code,
            pc.patta_type as patta_type, cb.land_class_code, lcg.name as land_class, 
            cb.user_code, cb.date_entry,
            l_village.loc_name as village_name, 
            l_circle.loc_name as circle_name
        FROM chitha_basic_splitted_dags cb
        LEFT JOIN patta_code pc ON cb.patta_type_code = pc.type_code
        LEFT JOIN land_class_groups lcg ON cb.land_class_code = lcg.land_class_code
        LEFT JOIN location l_village 
            ON cb.dist_code = l_village.dist_code 
            AND cb.subdiv_code = l_village.subdiv_code 
            AND cb.cir_code = l_village.cir_code 
            AND cb.mouza_pargona_code = l_village.mouza_pargona_code 
            AND cb.lot_no = l_village.lot_no 
            AND cb.vill_townprt_code = l_village.vill_townprt_code
        LEFT JOIN location l_circle 
            ON cb.dist_code = l_circle.dist_code 
            AND cb.subdiv_code = l_circle.subdiv_code 
            AND cb.cir_code = l_circle.cir_code
        {$whereConditions}
        ORDER BY cb.date_entry DESC
        LIMIT {$pageSize} OFFSET {$offset}
    ")->result();

        $dist_data['chitha_basic_splitted_dags'] = $chitha_basic_splitted_dags;
        $dist_data['totalCount'] = $totalCount; // 👈 add totalCount for frontend

        $this->db->close(); // cleanup connection

        $response = [
            'status' => 'y',
            'msg'    => 'Resurvey Report Data fetched successfully',
            'data'   => $dist_data
        ];
        echo json_encode($response);
    }
}
