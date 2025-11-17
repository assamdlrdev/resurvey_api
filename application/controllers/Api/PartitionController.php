<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class PartitionController extends CI_Controller
{
    use CommonTrait;
    private $tokenData;

    private $CURR_DATE;
    private $CURR_YEAR;


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

        $this->tokenData = $auth['data'];

        // switch another DB
        $this->dbswitch($this->tokenData->dcode);

        $this->load->model('Api/PartitionModel', 'pm');

        $this->CURR_DATE = date('Y-m-d');
        $this->CURR_YEAR = date('Y');
    }

    public function getLmVillages()
    {
        $result = $this->pm->getLmVillages($this->tokenData);

        if (!empty($result)) {

            // Success Response
            $response = [
                'status' => 'Y',
                'message' => 'Successful',
                'data' => $result
            ];

            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json')
                ->set_output(json_encode($response));

        } else {

            // Failure Response
            $response = [
                'status' => 'N',
                'message' => 'No data found',
                'data' => []
            ];

            $this->output
                ->set_status_header(404)   // Not Found
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        }

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
        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
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

        $result = $this->pm->getPattaNumbers($data);

        $response = [
            'status' => !empty($result) ? 'y' : 'n',
            'msg' => !empty($result) ? 'Successful' : 'No records found',
            'data' => $result
        ];

        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($response));

        return;
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

        $result = $this->pm->getDagNumbers($data);

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
        }

        $result = $this->pm->getDagPattadarInfo($data);

        $response = [
            'status' => !empty($result) ? 'y' : 'n',
            'msg' => !empty($result) ? 'Successful' : 'No records found',
            'data' => $result
        ];

        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($response));

        return;
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
        }

        $result = $this->pm->getLandAreaInfo($data);

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
     * Lot Mondal(LM) Forward Partition Application To Circle Officer(CO)
     * @return void
     */
    function LmForwardPartitionAplcnToCO()
    {
        # Get Form request data
        $input = json_decode(file_get_contents('php://input')); // get raw JSON input

        if (isset($input->villages)) {
            $village = json_decode($input->villages); // decode nested JSON string

            $landAreaInfo = json_decode($input->land_area_info); // decode nested JSON string

            $applicants = json_decode($input->applicants); // decode nested JSON string

            // Merge all together
            $data = [
                'dist_code' => $village->dist_code,
                'subdiv_code' => $village->subdiv_code,
                'cir_code' => $village->cir_code,
                'mouza_pargona_code' => $village->mouza_pargona_code,
                'lot_no' => $village->lot_no,
                'vill_townprt_code' => $village->vill_townprt_code,
                'patta_type_code' => $input->patta_type_code,
                'patta_no' => $input->patta_no,
                'dag_no' => $input->dag_no,
                'user_dist_code' => $this->tokenData->dcode,
                'user_code' => $this->tokenData->usercode,
                'user_type' => $this->tokenData->usertype,
                'user_desig_code' => $this->tokenData->user_desig_code,
                'land_area_info' => $landAreaInfo,
                'remarks' => $input->remarks
            ];
        }

        $inputData = $data;

        # Form validation

        # Form Sequrity Header Validation 

        # Start Process

        $caseName = $this->pm->generateCaseName($inputData);
        if (empty($caseName)) {
            $data = array(
                'error' => "Network Issue or Session Out. Please try Again!"
            );
            echo json_encode($data);
            exit(0);
        }

        ///////////////////////////
        // Generate petition number prefix: e.g., 2024 + '000'
        $seqPrefix = year_no . '000';
        // Get next sequence number
        $nextSeq = $this->pm->generateFieldPetitionNo($inputData['dist_code']);

        // Final petition number
        $petitionNo = $seqPrefix . $nextSeq;
        // Final case number
        $caseNo = $caseName . $petitionNo . "/FPART/RESURVEY";

        /*--------------- TRANSACTION STARTS HERE ------------ */
        $this->db->trans_begin();
        // --------------------------
        // Prepare INSERT data
        // --------------------------

        $inserted = $this->pm->insertFieldMutBasicData($inputData, $caseNo, $petitionNo);

        if (!$inserted) {
            $this->db->trans_rollback();

            log_message(
                'error',
                '#ERRFPART001/RESURVEY: Insert failed in field_mut_basic for Case No ' . $caseNo
            );

            $message = "#ERRFPART001/RESURVEY: Registration of Field Partition failed for case no : " . $caseNo;

            $response = [
                'status' => 'n',
                'msg' => $message,
                'data' => $data
            ];

            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response));

            return;
        }

        $dagDetailsStatus = $this->pm->insertDagDetails($inputData, $caseNo, $petitionNo);


        $fieldPartPetitionerStatus = $this->pm->insertFieldPartPetitioner($applicants, $inputData, $caseNo, $petitionNo);


        if (!$dagDetailsStatus || !$fieldPartPetitionerStatus || $this->db->trans_status() == FALSE) {
            $this->db->trans_rollback();
            $message = "Error in submitting form. Please try Again!";
            $data = null;
        } else {
            $this->db->trans_commit();
            $data = null;
            $message = "Application Forwarded to Circle Officer Successfully with case no: $caseNo";
            $data['case_num'] = $caseNo;
        }

        $response = [
            'status' => !empty($data) ? 'y' : 'n',
            'msg' => $message,
            'data' => $data
        ];

        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($response));

        return;
    }









}