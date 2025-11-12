<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class PartitionController extends CI_Controller
{
    use CommonTrait;
    private $jwt_data;
    private $tokenData;

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

        $this->tokenData = $this->jwt_data;

        // switch another DB
        $this->dbswitch($this->tokenData->dcode);

        /* Token Data
        object(stdClass)#25 (8) {
            ["usertype"]=>
            string(1) "3"
            ["loggedin"]=>
            bool(true)
            ["usercode"]=>
            string(3) "M14"
            ["dcode"]=>
            string(2) "17"
            ["subdiv_code"]=>
            string(2) "01"
            ["cir_code"]=>
            string(2) "01"
            ["user_desig_code"]=>
            string(2) "LM"
            ["is_password_changed"]=>
            string(1) "1"
            }
        */
    }


    public function getLmVillages()
    {
        // Subquery
        $subquery = $this->db
            ->select('lot_no')
            ->from('lm_code lc2')
            ->where([
                'lc2.lm_code' => $this->tokenData->usercode,
                'lc2.dist_code' => $this->tokenData->dcode,
                'lc2.subdiv_code' => $this->tokenData->subdiv_code,
                'lc2.cir_code' => $this->tokenData->cir_code,
                'lc2.status' => 'E'
            ])
            ->get_compiled_select();

        // Main query
        $this->db->distinct();
        $this->db->select('
                            l.dist_code, 
                            l.subdiv_code, 
                            l.cir_code, 
                            l.mouza_pargona_code, 
                            l.lot_no, 
                            l.vill_townprt_code,
                            l.loc_name, 
                            l.locname_eng, 
                            l.unique_loc_code, 
                            lc.lm_name, 
                            lc.lm_code
                        ');
        $this->db->from('lm_code lc');
        $this->db->join(
            'location l',
            'l.dist_code = lc.dist_code AND 
                        l.subdiv_code = lc.subdiv_code AND 
                        l.cir_code = lc.cir_code AND 
                        l.mouza_pargona_code = lc.mouza_pargona_code AND 
                        l.lot_no = lc.lot_no',
            'right'
        );
        $this->db->where([
            'lc.lm_code' => $this->tokenData->usercode,
            'lc.dist_code' => $this->tokenData->dcode,
            'lc.subdiv_code' => $this->tokenData->subdiv_code,
            'lc.cir_code' => $this->tokenData->cir_code
        ]);

        $this->db->where('l.vill_townprt_code !=', '00000');

        // Add subquery condition for lot_no
        $this->db->where("lc.lot_no IN ($subquery)", null, false);

        $query = $this->db->get();
        $result = $query->result();

        $response = [
            'status' => 'y',
            'msg' => 'Successfull',
            'data' => $result
        ];
        $this->output->set_status_header(200);  // Change to 400, 401, 500, etc. as needed
        echo json_encode($response);
        return;

    }


    /**
     * Get Patta Types For Lot Mondal
     * @return JSON
     */
    public function getPattaTypes()
    {
        $query = $this->db->get('patta_code'); // table name

        $response = [
            'status' => 'y',
            'msg' => 'Successfull',
            'data' => $query->result_array()
        ];
        $this->output->set_status_header(200);  // Change to 400, 401, 500, etc. as needed
        echo json_encode($response);
        return 0;
    }

    /**
     * Get Patta Numbers based on selected village and patta types.
     * @return JSON
     */
    public function getPattaNumbers()
    {
        $data = json_decode(file_get_contents('php://input')); // get raw JSON input

        if (isset($data->data)) {
            $village = json_decode($data->data); // decode nested JSON string
            $patta_type_code = $data->patta_type_code;

            // Merge all together
            $data = [
                'dist_code' => $village->dist_code,
                'subdiv_code' => $village->subdiv_code,
                'cir_code' => $village->cir_code,
                'mouza_pargona_code' => $village->mouza_pargona_code,
                'lot_no' => $village->lot_no,
                'vill_townprt_code' => $village->vill_townprt_code,
                'patta_type_code' => $patta_type_code
            ];

        }

        $this->db->distinct()
            ->select('patta_no')
            ->from('chitha_basic')
            ->where([
                'dist_code' => $data['dist_code'],
                'subdiv_code' => $data['subdiv_code'],
                'cir_code' => $data['cir_code'],
                'mouza_pargona_code' => $data['mouza_pargona_code'],
                'lot_no' => $data['lot_no'],
                'vill_townprt_code' => $data['vill_townprt_code'],
                'patta_type_code' => $data['patta_type_code']
            ]);

        $query = $this->db->get();

        // Debug: see generated SQL
        // echo $this->db->last_query();
        // exit;

        $result = $query->result_array();

        $response = [
            'status' => !empty($result) ? 'y' : 'n',
            'msg' => !empty($result) ? 'Successful' : 'No records found',
            'data' => $result
        ];

        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($response));
    }

    /**
     * Get Patta Numbers based on selected village and patta types and Patta Numbers.
     * @return JSON
     */
    public function getDagNumbers()
    {
        $data = json_decode(file_get_contents('php://input')); // get raw JSON input

        if (isset($data->data)) {
            $village = json_decode($data->data); // decode nested JSON string
            $patta_type_code = $data->patta_type_code;
            $patta_no = $data->patta_no;

            // Merge all together
            $data = [
                'dist_code' => $village->dist_code,
                'subdiv_code' => $village->subdiv_code,
                'cir_code' => $village->cir_code,
                'mouza_pargona_code' => $village->mouza_pargona_code,
                'lot_no' => $village->lot_no,
                'vill_townprt_code' => $village->vill_townprt_code,
                'patta_type_code' => $patta_type_code,
                'patta_no' => $patta_no
            ];

        }

        $this->db->distinct()
            ->select('dag_no')
            ->from('chitha_basic')
            ->where([
                'dist_code' => $data['dist_code'],
                'subdiv_code' => $data['subdiv_code'],
                'cir_code' => $data['cir_code'],
                'mouza_pargona_code' => $data['mouza_pargona_code'],
                'lot_no' => $data['lot_no'],
                'vill_townprt_code' => $data['vill_townprt_code'],
                'patta_type_code' => $data['patta_type_code'],
                'patta_no' => $data['patta_no']
            ]);

        $query = $this->db->get();

        // Debug: see generated SQL
        // echo $this->db->last_query();
        // exit;

        $result = $query->result_array();

        $response = [
            'status' => !empty($result) ? 'y' : 'n',
            'msg' => !empty($result) ? 'Successful' : 'No records found',
            'data' => $result
        ];

        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($response));
    }


    /**
     * Get Patta Numbers based on selected village and patta types and Patta Numbers.
     * @return JSON
     */
    public function getDagPattadarInfo()
    {
        $input = json_decode(file_get_contents('php://input')); // get raw JSON input

        if (isset($input->data)) {
            $village = json_decode($input->data); // decode nested JSON string
            $patta_type_code = $input->patta_type_code;
            $patta_no = $input->patta_no;
            $dag_no = $input->dag_no;

            // Merge all together
            $data = [
                'dist_code' => $village->dist_code,
                'subdiv_code' => $village->subdiv_code,
                'cir_code' => $village->cir_code,
                'mouza_pargona_code' => $village->mouza_pargona_code,
                'lot_no' => $village->lot_no,
                'vill_townprt_code' => $village->vill_townprt_code,
                'patta_type_code' => $patta_type_code,
                'patta_no' => $patta_no,
                'dag_no' => $dag_no
            ];
            // $this->test($data); exit;
        }

        $this->db->select('
                    cp.patta_no, 
                    cp.dag_no, 
                    cp.dist_code, 
                    cp.subdiv_code, 
                    cp.cir_code, 
                    cp.mouza_pargona_code, 
                    cp.lot_no, 
                    cp.vill_townprt_code, 
                    cp.patta_type_code, 
                    cp.pdar_id, 
                    MAX(cp2.pdar_name) AS pdar_name, 
                    MAX(cp2.pdar_father) AS pdar_father
                ');
        $this->db->from('chitha_dag_pattadar cp');
        $this->db->join('chitha_pattadar cp2', 'cp.pdar_id = cp2.pdar_id');

        // WHERE conditions
        $this->db->where('cp.patta_no', $data['patta_no']);
        $this->db->where('cp.dist_code', $data['dist_code']);
        $this->db->where('cp.subdiv_code', $data['subdiv_code']);
        $this->db->where('cp.cir_code', $data['cir_code']);
        $this->db->where('cp.mouza_pargona_code', $data['mouza_pargona_code']);
        $this->db->where('cp.lot_no', $data['lot_no']);
        $this->db->where('cp.vill_townprt_code', $data['vill_townprt_code']);
        $this->db->where('cp.patta_type_code', $data['patta_type_code']);
        $this->db->where('cp.dag_no', $data['dag_no']);

        // GROUP BY clause
        $this->db->group_by([
            'cp.patta_no',
            'cp.dag_no',
            'cp.dist_code',
            'cp.subdiv_code',
            'cp.cir_code',
            'cp.mouza_pargona_code',
            'cp.lot_no',
            'cp.vill_townprt_code',
            'cp.patta_type_code',
            'cp.pdar_id'
        ]);

        // Execute the query
        $query = $this->db->get();
        $result = $query->result();


        $response = [
            'status' => !empty($result) ? 'y' : 'n',
            'msg' => !empty($result) ? 'Successful' : 'No records found',
            'data' => $result
        ];

        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($response));
    }

    /**
     * Get Land Area Info of Particular Dag of a Particular Location.
     * @return void
     */
    public function getLandAreaInfo()
    {
        $input = json_decode(file_get_contents('php://input')); // get raw JSON input

        if (isset($input->data)) {
            $village = json_decode($input->data); // decode nested JSON string
            $patta_type_code = $input->patta_type_code;
            $patta_no = $input->patta_no;
            $dag_no = $input->dag_no;

            // Merge all together
            $data = [
                'dist_code' => $village->dist_code,
                'subdiv_code' => $village->subdiv_code,
                'cir_code' => $village->cir_code,
                'mouza_pargona_code' => $village->mouza_pargona_code,
                'lot_no' => $village->lot_no,
                'vill_townprt_code' => $village->vill_townprt_code,
                'patta_type_code' => $patta_type_code,
                'patta_no' => $patta_no,
                'dag_no' => $dag_no
            ];
            // $this->test($data); exit;
        }

        $this->db->select('
                    cb.patta_no, 
                    cb.dag_no, 
                    cb.dist_code, 
                    cb.subdiv_code, 
                    cb.cir_code, 
                    cb.mouza_pargona_code, 
                    cb.lot_no, 
                    cb.vill_townprt_code, 
                    cb.patta_type_code, 
                    cb.dag_area_b as total_bigha, 
                    cb.dag_area_k as total_katha, 
                    cb.dag_area_lc as total_lessa,
                    cb.dag_revenue,
                    cb.dag_local_tax
                ');
        $this->db->from('chitha_basic cb');

        // WHERE conditions
        $this->db->where('cb.patta_no', $data['patta_no']);
        $this->db->where('cb.dist_code', $data['dist_code']);
        $this->db->where('cb.subdiv_code', $data['subdiv_code']);
        $this->db->where('cb.cir_code', $data['cir_code']);
        $this->db->where('cb.mouza_pargona_code', $data['mouza_pargona_code']);
        $this->db->where('cb.lot_no', $data['lot_no']);
        $this->db->where('cb.vill_townprt_code', $data['vill_townprt_code']);
        $this->db->where('cb.patta_type_code', $data['patta_type_code']);
        $this->db->where('cb.dag_no', $data['dag_no']);

        // Execute the query
        $query = $this->db->get();
        $result = $query->result();


        $response = [
            'status' => !empty($result) ? 'y' : 'n',
            'msg' => !empty($result) ? 'Successful' : 'No records found',
            'data' => $result
        ];

        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($response));
    }

    function test($data)
    {
        echo "<pre>";
        var_dump($data);
    }

}