
<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class StrikeOutController extends CI_Controller
{
    use CommonTrait;
    private $jwt_data;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Api/ChithaModel');
        $this->load->model('Api/LocationModel');
        $this->load->model('Api/StrikeoutDataModel');
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


    public function getLmStrikeDags() {
        $tokenData = $this->jwt_data;

        $dist_code = $tokenData->dcode;
        $subdiv_code = $tokenData->subdiv_code;
        $cir_code = $tokenData->cir_code;
        $user_code = $tokenData->usercode;

        $data = json_decode(file_get_contents('php://input', true));

        $vill_code_string = $data->vill_townprt_code;

        $vill_code_arr = explode('-', $vill_code_string);
        $mouza_pargona_code = $vill_code_arr[3];
        $lot_no = $vill_code_arr[4];
        $vill_townprt_code = $vill_code_arr[5];

        $this->dbswitch($dist_code);
        $this->db->trans_begin();

        $mergeStatus = $this->LocationModel->mergeVillageData($vill_code_string, $user_code);
        if($mergeStatus['status'] != 'y') {
            $this->db->trans_rollback();
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($mergeStatus);
            return;
        }

        if(!$this->db->trans_status()) {
            $this->db->trans_rollback();
            $this->output->set_status_header(500);  // Change to 400, 401, 500, etc. as needed
            echo json_encode([
                'status' => 'n',
                'msg' => 'DB Transaction Failed!'
            ]);
            exit;
        }

        $this->db->trans_commit();


        $dags = $this->db->query("SELECT dist_code, subdiv_code, cir_code, mouza_pargona_code, lot_no, vill_townprt_code, dag_no FROM chitha_basic WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? ORDER BY dag_no_int ASC", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code])->result();

        $this->output->set_status_header(200);
        echo json_encode([
            'status' => 'y',
            'msg' => 'Successfully retrieved dags!',
            'data' => $dags
        ]);
        exit;
    }

    public function getLmStrikeDagData() {
        $tokenData = $this->jwt_data;

        $dist_code = $tokenData->dcode;
        $subdiv_code = $tokenData->subdiv_code;
        $cir_code = $tokenData->cir_code;
        $user_code = $tokenData->usercode;

        $data = json_decode(file_get_contents('php://input', true));

        if(empty($data) || !isset($data->dag_no) || $data->dag_no == '') {
            $this->output->set_status_header(401);
            echo json_encode([
                'status' => 'n',
                'msg' => 'Input Missing!'
            ]);
            exit;
        }

        $dag_string = $data->dag_no;

        $dag_arr = explode('-', $dag_string);

        $mouza_pargona_code = $dag_arr[3];
        $lot_no = $dag_arr[4];
        $vill_townprt_code = $dag_arr[5];
        $dag_no = $dag_arr[6];

        $this->dbswitch($dist_code);

        $dag_data = $this->db->query("SELECT dist_code, subdiv_code, cir_code, mouza_pargona_code, lot_no, vill_townprt_code, dag_no, patta_no, patta_type_code, dag_area_b, dag_area_k, dag_area_lc, dag_area_g FROM chitha_basic WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND dag_no=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag_no])->row();

        if(empty($dag_data)) {
            $this->output->set_status_header(500);
            echo json_encode([
                'status' => 'n',
                'msg' => 'Dag Data not found!'
            ]);
            exit;
        }

        $dag_data->patta_type_name = $this->db->query("SELECT patta_type FROM patta_code WHERE type_code=?", [$dag_data->patta_type_code])->row()->patta_type;

        $dag_pattadars = $this->db->query("SELECT cdp.dist_code, cdp.subdiv_code, cdp.cir_code, cdp.mouza_pargona_code, cdp.lot_no, cdp.vill_townprt_code, cdp.dag_no, cdp.patta_no, cdp.patta_type_code, cdp.pdar_id, cp.pdar_name, cp.pdar_father FROM chitha_dag_pattadar cdp, chitha_pattadar cp WHERE cdp.dist_code=cp.dist_code AND cdp.subdiv_code=cp.subdiv_code AND cdp.cir_code=cp.cir_code AND cdp.mouza_pargona_code=cp.mouza_pargona_code AND cdp.lot_no=cp.lot_no AND cdp.vill_townprt_code=cp.vill_townprt_code AND cdp.patta_no=cp.patta_no AND cdp.patta_type_code=cp.patta_type_code AND cdp.pdar_id=cp.pdar_id AND cdp.dist_code=? AND cdp.subdiv_code=? AND cdp.cir_code=? AND cdp.mouza_pargona_code=? AND cdp.lot_no=? AND cdp.vill_townprt_code=? AND cdp.patta_type_code=? AND cdp.patta_no=? AND cdp.dag_no=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag_data->patta_type_code, $dag_data->patta_no, $dag_data->dag_no])->result();

        if(empty($dag_pattadars)) {
            $this->output->set_status_header(500);
            echo json_encode([
                'status' => 'n',
                'msg' => 'No Pattadars available for Strike Out Name!'
            ]);
            exit;
        }

        $row['dag_data'] = $dag_data;
        $row['dag_pattadars'] = $dag_pattadars;


        $this->output->set_status_header(200);
        echo json_encode([
            'status' => 'y',
            'msg' => 'Data Retrieved Successfully!',
            'data' => $row
        ]);
        exit;
    }

    public function getLmStrikeoutSubmit() {
        $this->form_validation->set_rules('vill_townprt_code', 'Village Name', 'trim|required');
        $this->form_validation->set_rules('dag_no', 'Dag No.', 'trim|required');
        $this->form_validation->set_rules('applicant', 'Applicant', 'trim|required');
        $this->form_validation->set_rules('selectedPattadars', 'Pattadars', 'trim|required');
        if (!$this->form_validation->run()) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            $this->output->set_status_header(401);
            echo json_encode([
                'status' => 'n',
                'msg' => $text
            ]);
            exit;
        }

        $tokenData = $this->jwt_data;
        $dist_code = $tokenData->dcode;
        $subdiv_code = $tokenData->subdiv_code;
        $cir_code = $tokenData->cir_code;
        $user_code = $tokenData->usercode;

        // $data = json_decode(file_get_contents('php://input', true));

        $vill_townprt_code = $this->input->post('vill_townprt_code', true);
        $dag_no_string = $this->input->post('dag_no', true);
        $applicant = $this->input->post('applicant', true);
        $selectedPattadars = json_decode($this->input->post('selectedPattadars', true));
        $remarks = (isset($_POST['remarks']) && $_POST['remarks'] != '') ? $this->input->post('remarks', true) : null;

        $locationArr = explode('-', $dag_no_string);
        $mouza_pargona_code = $locationArr[3];
        $lot_no = $locationArr[4];
        $vill_townprt_code = $locationArr[5];
        $dag_no = $locationArr[6];

        if(!is_array($selectedPattadars) || count($selectedPattadars) < 1) {
            $this->output->set_status_header(401);
            echo json_encode([
                'status' => 'n',
                'msg' => 'Invalid pattadar input!'
            ]);
            exit;
        }

        if(in_array($applicant, $selectedPattadars)) {
            $this->output->set_status_header(401);
            echo json_encode([
                'status' => 'n',
                'msg' => 'Applicant cannot be in the selected pattadars list!'
            ]);
            exit;
        }

        // 17-01-01-01-01-10017-11-93-0201-1

        $applicant_arr = explode('-', $applicant);
        $patta_no = $applicant_arr[7];
        $patta_type_code = $applicant_arr[8];
        $applicant_pdar_id = $applicant_arr[9];


        $this->dbswitch($dist_code);
        $this->db->trans_begin();

        $seq_pet = year_no . '00';
        $case_name = $this->StrikeoutDataModel->generateCaseName($dist_code, $subdiv_code, $cir_code);

        $petition_no = $seq_pet . $this->StrikeoutDataModel->genearteMiscPetitionNo();
        $case_no = $case_name . $petition_no . "/MiND/RESURVEY";


        // echo '<pre>';
        // var_dump($_FILES);
        // die;



        if(!empty($_FILES)) {
            foreach ($_FILES as $key => $value) {
                $filename = $_FILES[$key]['name'];
                $filetype = $_FILES[$key]['type'];
                $tmpname = $_FILES[$key]['tmp_name'];
                $filesize = $_FILES[$key]['size'];
                $error = $_FILES[$key]['error'];

                $exp  = explode("/",$filetype);
                $ext = $exp[count($exp) - 1];

                $filename = $key . '_' . $petition_no . '.' . $ext;

                $upload_path = FCPATH . 'uploads/StrikeOutNameDocs/';

                if (!is_dir($upload_path)) {
                    mkdir($upload_path, 0777, true);
                }

                $config['upload_path']   = $upload_path;
                $config['allowed_types'] = RESURVEY_FILE_TYPE;
                $config['max_size']  = RESURVEY_MAX_SIZE;
                $config['file_name'] = $filename;
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if(!$this->upload->do_upload($key)) {
                    $this->db->trans_rollback();
                    $this->output->set_status_header(500);
                    echo json_encode([
                        'status' =>'n',
                        'msg' => 'Document not uploaded successfully!'
                    ]);
                    return;
                }

                $docInsertArr = [
                    'case_no' => $case_no,
                    'file_name' => strtoupper($key),
                    'file_path' => 'uploads/StrikeOutNameDocs/',
                    'file_type' => $filetype,
                    'fetch_file_name' => $filename,
                    'mut_type' => 'NC',
                    'user_code' => $user_code,
                    'date_entry' => date('Y-m-d H:i:s')
                ];

                $docInsertStatus = $this->db->insert('supportive_document', $docInsertArr);
                if(!$docInsertStatus || $this->db->affected_rows() < 1) {
                    $this->db->trans_rollback();
                    $this->output->set_status_header(500);
                    echo json_encode([
                        'status' =>'n',
                        'msg' => 'Document not updated!'
                    ]);
                    return;
                }
            }
        }




        $getApplicantDetails = $this->db->query("SELECT cp.pdar_name, cp.pdar_father FROM chitha_pattadar cp, chitha_dag_pattadar cdp WHERE cdp.dist_code=cp.dist_code AND cdp.subdiv_code=cp.subdiv_code AND cdp.cir_code=cp.cir_code AND cdp.mouza_pargona_code=cp.mouza_pargona_code AND cdp.lot_no=cp.lot_no AND cdp.vill_townprt_code=cp.vill_townprt_code AND cdp.patta_no=cp.patta_no AND cdp.patta_type_code=cp.patta_type_code AND cdp.pdar_id=cp.pdar_id AND cdp.dist_code=? AND cdp.subdiv_code=? AND cdp.cir_code=? AND cdp.mouza_pargona_code=? AND cdp.lot_no=? AND cdp.vill_townprt_code=? AND cdp.dag_no=? AND cdp.patta_no=? AND cdp.patta_type_code=? AND cdp.pdar_id=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag_no, $patta_no, $patta_type_code, $applicant_pdar_id])->row();

        if(empty($getApplicantDetails)) {
            // $this->db->trans_rollback();
            $this->output->set_status_header(500);
            echo json_encode([
                'status' => 'n',
                'msg' => 'Could not find Applicant!'
            ]);
            exit;
        }


        // echo '<pre>';
        // var_dump($petition_no, $case_no, $applicant, $_FILES);
        // die;


        $miscCaseBasicArr = [
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'mouza_pargona_code' => $mouza_pargona_code,
            'lot_no' => $lot_no,
            'vill_townprt_code' => $vill_townprt_code,
            'year_no' => date('Y'),
            'misc_case_petition_no' => $petition_no,
            'misc_case_no' => $case_no,
            'misc_case_type' => '07',
            'patta_no' => $patta_no,
            'patta_type_code' => $patta_type_code,
            'submission_date' => date('Y-m-d G:i:s'),
            'supported_doc_yn' => 'Y',
            'supported_doc_code' => date('Y-m-d G:i:s'),
            'lm_note_yn' => 'Y',
            'fresh_yn' => 'Y',
            'status' => '02',
            'operation' => 'l',
            'proceeding_yn' => 'Y',
            'user_code' => $user_code,
            'date_of_operation' => date('Y-m-d G:i:s'),
            'add_to_officer' => '',
            'dag_no' => $dag_no
        ];
        $insertMiscCaseBasicStatus = $this->db->insert("misc_case_basic", $miscCaseBasicArr);
        if(!$insertMiscCaseBasicStatus || $this->db->affected_rows() < 1) {
            $this->db->trans_rollback();
            log_message('error', '#MISCCASEAPPLY001 Error in inserting into misc_case_basic');
            $this->output->set_status_header(500);
            echo json_encode([
                'status' => 'n',
                'msg' => 'ERROR CODE: #MISCCASEAPPLY001!'
            ]);
            exit;
        }

        $insertFirstPartyArr = [
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'petition_pdar_id' =>  $applicant_pdar_id,
            'misc_case_no' => $case_no,
            'petition_pdar_name_old' => $getApplicantDetails->pdar_name,
            'submission_date' => date('Y-m-d G:i:s'),
            'user_code' => $user_code,
            'operation' => 'l',
            'misc_case_petition_no' => $petition_no,
            'self_declaration' => null,
            'auth_type' => null,
            'id_ref_no'=> null,
            'photo'=> null
        ];
        $insertFirstPartyStatus = $this->db->insert("misc_case_first_party", $insertFirstPartyArr);
        if(!$insertFirstPartyStatus || $this->db->affected_rows() < 1) {
            $this->db->trans_rollback();
            log_message('error', '#MISCCASEAPPLY002 Error in inserting into misc_case_first_party');
            $this->output->set_status_header(500);
            echo json_encode([
                'status' => 'n',
                'msg' => 'ERROR CODE: #MISCCASEAPPLY002!'
            ]);
            exit;
        }

        foreach ($selectedPattadars as $selectedPattadar) {
            $selectedPattadarArr = explode('-', $selectedPattadar);
            $pdar_id = $selectedPattadarArr[9];

            $selectedPdarDetails = $this->db->query("SELECT cp.pdar_name, cp.pdar_father FROM chitha_pattadar cp, chitha_dag_pattadar cdp WHERE cdp.dist_code=cp.dist_code AND cdp.subdiv_code=cp.subdiv_code AND cdp.cir_code=cp.cir_code AND cdp.mouza_pargona_code=cp.mouza_pargona_code AND cdp.lot_no=cp.lot_no AND cdp.vill_townprt_code=cp.vill_townprt_code AND cdp.patta_no=cp.patta_no AND cdp.patta_type_code=cp.patta_type_code AND cdp.pdar_id=cp.pdar_id AND cdp.dist_code=? AND cdp.subdiv_code=? AND cdp.cir_code=? AND cdp.mouza_pargona_code=? AND cdp.lot_no=? AND cdp.vill_townprt_code=? AND cdp.dag_no=? AND cdp.patta_no=? AND cdp.patta_type_code=? AND cdp.pdar_id=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag_no, $patta_no, $patta_type_code, $pdar_id])->row();

            if(empty($selectedPdarDetails)) {
                $this->db->trans_rollback();
                log_message('error', '#MISCCASEAPPLY003 Could not find pattadar id' . $pdar_id . ' for striking out name');
                $this->output->set_status_header(500);
                echo json_encode([
                    'status' => 'n',
                    'msg' => 'ERROR CODE: #MISCCASEAPPLY003!'
                ]);
                exit;
            }

            $insertSecondPartyArr = array(
                'dist_code' => $dist_code,
                'subdiv_code' =>$subdiv_code,
                'cir_code' => $cir_code,
                'opp_pdar_id' => $pdar_id,
                'misc_case_no' => $case_no,
                'opp_comment' => $remarks,
                'submission_date' => date('Y-m-d G:i:s'),
                'user_code' => $user_code,
                'operation' => 'E'
            );
            $insertSecondPartyStatus = $this->db->insert("misc_case_scnd_party", $insertSecondPartyArr);
            if(!$insertSecondPartyStatus || $this->db->affected_rows() < 1) {
                $this->db->trans_rollback();
                log_message('error', '#MISCCASEAPPLY004 Error in inserting into misc_case_scnd_party');
                $this->output->set_status_header(500);
                echo json_encode([
                    'status' => 'n',
                    'msg' => 'ERROR CODE: #MISCCASEAPPLY004!'
                ]);
                exit;
            }
        }

        $sql = "select MAX(note_no)+1 AS note_no from misc_case_process_reports where misc_case_no=? and misc_case_petition_no = ?";
        $note_no = $this->db->query($sql, [$case_no, $petition_no])->row()->note_no;

        $noteDetails = $this->db->query("select note_no from misc_case_process_reports where misc_case_no=? and misc_case_petition_no = ? ORDER BY note_no DESC", [$case_no, $petition_no])->row();

        if(empty($noteDetails)) {
            $note_no = 1;
        }
        else {
            $note_no = $noteDetails->note_no + 1;
        }

        // $this->db->trans_rollback();
        // echo '<pre>';
        // var_dump($note_no);
        // die;
        $processDataArr = [
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'note_no' => $note_no,
            'misc_case_no' => $case_no,
            'co_fresh_proceeding' => 'Y',
            'process_note' => $remarks,
            'note_date' => date('Y-m-d'),
            'user_code' => $user_code,
            'operation' => 'l',
            'misc_case_petition_no' => $petition_no
        ];
        $processDataInsertStatus = $this->db->insert("misc_case_process_reports", $processDataArr);
        if(!$processDataInsertStatus || $this->db->affected_rows() < 1) {
            $this->db->trans_rollback();
            log_message('error', '#MISCCASEAPPLY006 Error in inserting into misc_case_process_reports');
            $this->output->set_status_header(500);
            echo json_encode([
                'status' => 'n',
                'msg' => 'ERROR CODE: #MISCCASEAPPLY006!'
            ]);
            exit;
        }

        $proceedingDetails = $this->db->query("SELECT proceeding_id FROM petition_proceeding WHERE case_no=? ORDER BY proceeding_id DESC", [$case_no])->row();
        if(empty($proceedingDetails)) {
            $proceeding_id = 1;
        }
        else {
            $proceeding_id = $proceedingDetails->proceeding_id + 1;
        }
        $date_entry=date('Y-m-d h:i:s');
        $proceedingData = [
            'case_no' => $case_no,
            'proceeding_id' => $proceeding_id,
            'date_of_hearing' => $date_entry,
            'co_order' => $remarks,
            'note_on_order' => 'Forwarded to CO',
            'next_date_of_hearing' => $date_entry,
            'status' => 'Pending',
            'user_code' => $user_code,
            'date_entry' => $date_entry,
            'dist_code' => $dist_code,
            'cir_code' => $cir_code,
            'subdiv_code' => $subdiv_code,
            'operation' => 'E',
            'ip' => $_SERVER['REMOTE_ADDR']
        ];
        $proceedingInsertStatus = $this->db->insert('petition_proceeding', $proceedingData);
        if(!$proceedingInsertStatus || $this->db->affected_rows() < 1) {
            $this->db->trans_rollback();
            log_message('error', '#MISCCASEAPPLY007 Error in inserting into petition_proceeding');
            $this->output->set_status_header(500);
            echo json_encode([
                'status' =>'n',
                'msg' => 'Error code: #MISCCASEAPPLY007!'
            ]);
            return;
        }

        if(!$this->db->trans_status()) {
            $this->db->trans_rollback();
            log_message('error', '#MISCCASEAPPLY005. DB Transaction Failed!');
            $this->output->set_status_header(500);
            echo json_encode([
                'status' => 'n',
                'msg' => 'ERROR CODE: #MISCCASEAPPLY005!'
            ]);
            exit;
        }

        $this->db->trans_commit();

        $this->output->set_status_header(200);
        echo json_encode([
            'status' => 'y',
            'msg' => 'Successfully generated Case No: ' . $case_no
        ]);
        exit;
        
    }

    public function getLmStrikeoutCases() {
        $tokenData = $this->jwt_data;
        $dcode = $tokenData->dcode;
        $subdiv_code = $tokenData->subdiv_code;
        $cir_code = $tokenData->cir_code;
        $user_code = $tokenData->usercode;

        $this->dbswitch($dcode);

        $lmData = $this->StrikeoutDataModel->authorizeLM ($dcode, $subdiv_code, $cir_code, $user_code);
        if($lmData['status'] != 'y') {
            $this->output->set_status_header(500);
            echo json_encode([
                'status' =>'n',
                'msg' => 'Not Authorized!'
            ]);
            return;
        }
        $lmData = $lmData['data'];

         $miscCaseBasic = $this->db->query("SELECT * FROM misc_case_basic WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND misc_case_type='07'", [$lmData->dist_code, $lmData->subdiv_code, $lmData->cir_code, $lmData->mouza_pargona_code, $lmData->lot_no])->result();
        if(empty($miscCaseBasic)) {
            $this->output->set_status_header(500);
            echo json_encode([
                'status' =>'n',
                'msg' => 'No data available!'
            ]);
            return;
        }

        foreach ($miscCaseBasic as $miscCase) {
            $village = $this->db->query("SELECT loc_name, locname_eng FROM location WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=?", [$miscCase->dist_code, $miscCase->subdiv_code, $miscCase->cir_code, $miscCase->mouza_pargona_code, $miscCase->lot_no, $miscCase->vill_townprt_code])->row();

            if(empty($village)) {
                $miscCase->vill_townprt_name = '';
            }
            else {
                $miscCase->vill_townprt_name = $village->loc_name . '(' . $village->locname_eng . ')';
            }
            $miscCase->date_entry_name = date('d-m-Y', strtotime($miscCase->submission_date));

            if($miscCase->status == '10' && $miscCase->operation == 'E') {
                $miscCase->status = 'Order passed by CO';
                $miscCase->status_flag = 'red';
            }
            else {
                $miscCase->status = 'Order pending by CO';
                $miscCase->status_flag = 'green';
            }
        }

        $this->output->set_status_header(200);
        echo json_encode([
            'status' =>'y',
            'msg' => 'Successfully Retrieved Data!',
            'data' => $miscCaseBasic
        ]);
        return;
    }

    public function getCoStrikeoutCases() {
        $tokenData = $this->jwt_data;
        $dcode = $tokenData->dcode;
        $subdiv_code = $tokenData->subdiv_code;
        $cir_code = $tokenData->cir_code;
        $user_code = $tokenData->usercode;

        $this->dbswitch($dcode);

        $coCases = $this->db->query("SELECT * FROM misc_case_basic WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND misc_case_type='07'", [$dcode, $subdiv_code, $cir_code])->result();

        if(empty($coCases)) {
            $this->output->set_status_header(500);
            echo json_encode([
                'status' =>'n',
                'msg' => 'No Cases Found!'
            ]);
            return;
        }

        foreach ($coCases as $coCase) {
            $coCase->mouza_pargona_name = $this->utilityclass->getMouzaName($coCase->dist_code, $coCase->subdiv_code, $coCase->cir_code, $coCase->mouza_pargona_code);
            $coCase->lot_name = $this->utilityclass->getLotName($coCase->dist_code, $coCase->subdiv_code, $coCase->cir_code, $coCase->mouza_pargona_code, $coCase->lot_no);
            $coCase->vill_townprt_name = $this->utilityclass->getVillageName($coCase->dist_code, $coCase->subdiv_code, $coCase->cir_code, $coCase->mouza_pargona_code, $coCase->lot_no, $coCase->vill_townprt_code);

            $coCase->date_entry_name = date('d-m-Y', strtotime($coCase->submission_date));

            if($coCase->status == '10' && $coCase->operation == 'E') {
                $coCase->status = 1;
            }
            else {
                $coCase->status = 0;
            }
        }

        $this->output->set_status_header(200);
        echo json_encode([
            'status' =>'y',
            'msg' => 'Successfully Retrieved Data!',
            'data' => $coCases
        ]);
        return;
    }

    public function getCoStrikeoutCase() {
        $tokenData = $this->jwt_data;
        $dcode = $tokenData->dcode;
        $subdiv_code = $tokenData->subdiv_code;
        $cir_code = $tokenData->cir_code;

        $data = json_decode(file_get_contents('php://input', true));

        // echo '<pre>';
        // var_dump($data);
        // die;

        if(empty($data) || !isset($data->case_no) || $data->case_no == '') {
            $this->output->set_status_header(401);
            echo json_encode([
                'status' =>'n',
                'msg' => 'Input Parameters Missing!'
            ]);
            return;
        }

        $case_no = $data->case_no;

        $this->dbswitch($dcode);

        $miscCaseBasic = $this->db->query("SELECT * FROM misc_case_basic WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND misc_case_type='07' AND lm_note_yn='Y' AND notice_generated_yn IS NULL AND status!='10' AND operation!='E' AND misc_case_no=?", [$dcode, $subdiv_code, $cir_code, $case_no])->row();

        if(empty($miscCaseBasic)) {
            $this->output->set_status_header(401);
            echo json_encode([
                'status' =>'n',
                'msg' => 'Not Authorized!'
            ]);
            return;
        }

        $miscCaseFirst = $this->db->query("SELECT * FROM misc_case_first_party WHERE misc_case_no=?", [$miscCaseBasic->misc_case_no])->result();

        $miscCaseSecond = $this->db->query("SELECT * FROM misc_case_scnd_party WHERE misc_case_no=?", [$miscCaseBasic->misc_case_no])->result();

        $miscDocs = $this->db->query("SELECT id, case_no, file_name, file_type, file_path, fetch_file_name FROM supportive_document WHERE case_no=? AND mut_type='NC'", [$miscCaseBasic->misc_case_no])->result();

        if(!empty($miscCaseFirst)) {
            foreach ($miscCaseFirst as $first) {
                $first->mouza_pargona_name = $this->utilityclass->getMouzaName($first->dist_code, $first->subdiv_code, $first->cir_code, $miscCaseBasic->mouza_pargona_code);
                $first->lot_name = $this->utilityclass->getLotName($first->dist_code, $first->subdiv_code, $first->cir_code, $miscCaseBasic->mouza_pargona_code, $miscCaseBasic->lot_no);
                $first->vill_townprt_name = $this->utilityclass->getVillageName($first->dist_code, $first->subdiv_code, $first->cir_code, $miscCaseBasic->mouza_pargona_code, $miscCaseBasic->lot_no, $miscCaseBasic->vill_townprt_code);
                $first->patta_no = $miscCaseBasic->patta_no;
                $first->patta_type_code = $miscCaseBasic->patta_type_code;
                $cPattadar = $this->db->query("SELECT * FROM chitha_pattadar WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND patta_no=? AND patta_type_code=? AND pdar_id=?", [$first->dist_code, $first->subdiv_code, $first->cir_code, $miscCaseBasic->mouza_pargona_code, $miscCaseBasic->lot_no, $miscCaseBasic->vill_townprt_code, $miscCaseBasic->patta_no, $miscCaseBasic->patta_type_code, $first->petition_pdar_id])->row();
                $first->pdar_name = $cPattadar->pdar_name;
                $first->pdar_father = $cPattadar->pdar_father;
                $first->dag_no = $miscCaseBasic->dag_no;
            }
        }

        if(!empty($miscCaseSecond)) {
            foreach ($miscCaseSecond as $second) {
                $second->mouza_pargona_name = $this->utilityclass->getMouzaName($second->dist_code, $second->subdiv_code, $second->cir_code, $miscCaseBasic->mouza_pargona_code);
                $second->lot_name = $this->utilityclass->getLotName($second->dist_code, $second->subdiv_code, $second->cir_code, $miscCaseBasic->mouza_pargona_code, $miscCaseBasic->lot_no);
                $second->vill_townprt_name = $this->utilityclass->getVillageName($second->dist_code, $second->subdiv_code, $second->cir_code, $miscCaseBasic->mouza_pargona_code, $miscCaseBasic->lot_no, $miscCaseBasic->vill_townprt_code);
                $second->patta_no = $miscCaseBasic->patta_no;
                $second->patta_type_code = $miscCaseBasic->patta_type_code;
                $cPattadar = $this->db->query("SELECT * FROM chitha_pattadar WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND patta_no=? AND patta_type_code=? AND pdar_id=?", [$second->dist_code, $second->subdiv_code, $second->cir_code, $miscCaseBasic->mouza_pargona_code, $miscCaseBasic->lot_no, $miscCaseBasic->vill_townprt_code, $miscCaseBasic->patta_no, $miscCaseBasic->patta_type_code, $second->opp_pdar_id])->row();
                $second->pdar_name = $cPattadar->pdar_name;
                $second->pdar_father = $cPattadar->pdar_father;
                $second->dag_no = $miscCaseBasic->dag_no;
            }
        }

        $miscCaseBasic->mouza_pargona_name = $this->utilityclass->getMouzaName($miscCaseBasic->dist_code, $miscCaseBasic->subdiv_code, $miscCaseBasic->cir_code, $miscCaseBasic->mouza_pargona_code);
        $miscCaseBasic->lot_name = $this->utilityclass->getLotName($miscCaseBasic->dist_code, $miscCaseBasic->subdiv_code, $miscCaseBasic->cir_code, $miscCaseBasic->mouza_pargona_code, $miscCaseBasic->lot_no);
        $miscCaseBasic->vill_townprt_name = $this->utilityclass->getVillageName($miscCaseBasic->dist_code, $miscCaseBasic->subdiv_code, $miscCaseBasic->cir_code, $miscCaseBasic->mouza_pargona_code, $miscCaseBasic->lot_no, $miscCaseBasic->vill_townprt_code);
        $miscCaseBasic->patta_type_name=$this->utilityclass->getPattaType($miscCaseBasic->patta_type_code);

        if(!empty($miscDocs)) {
            foreach ($miscDocs as $miscDoc) {
                $miscDoc->full_path = $miscDoc->file_path . $miscDoc->fetch_file_name;
            }
        }

        $row['strikeout_details'] = $miscCaseBasic;
        $row['strikeout_petitioners'] = $miscCaseFirst;
        $row['strikeout_pattadars'] = $miscCaseSecond;
        $row['strikeout_docs'] = $miscDocs;

        $this->output->set_status_header(200);
        echo json_encode([
            'status' =>'y',
            'msg' => 'Successfully Retrieved data!',
            'data' => $row
        ]);
        return;
    }

    public function coFinalSubmit() {
        $tokenData = $this->jwt_data;
        $dcode = $tokenData->dcode;
        $subdiv_code = $tokenData->subdiv_code;
        $cir_code = $tokenData->cir_code;
        $user_code = $tokenData->usercode;

        $data = json_decode(file_get_contents('php://input', true));

        $misc_case_no = $data->case_no;
        $remarks = $data->remarks;

        $this->dbswitch($dcode);
        $this->db->trans_begin();

        $miscCaseBasic = $this->db->query("SELECT misc_case_no FROM misc_case_basic WHERE status=? AND operation=? AND misc_case_no=?", ['10', 'E', $misc_case_no])->row();
        if(!empty($miscCaseBasic)) {
            $this->db->trans_rollback();
            log_message('error', '#ERRSTRIKEOUTSUBMIT001: Data alaready exist in misc_case_basic for misc case no : '.$misc_case_no);
            $this->output->set_status_header(500);
            echo json_encode([
                'status' =>'n',
                'msg' => 'Error Code: #ERRSTRIKEOUTSUBMIT001. Detail already available for case no : ' . $misc_case_no
            ]);
            return;
        }

        $miscCaseBasicDetails = $this->db->query("SELECT * FROM misc_case_basic WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND misc_case_no=?", [$dcode, $subdiv_code, $cir_code, $misc_case_no])->row();
        if(empty($miscCaseBasicDetails)) {
             $this->db->trans_rollback();
            log_message('error', 'Not authorized for this circle for misc case no : '.$misc_case_no);
            $this->output->set_status_header(500);
            echo json_encode([
                'status' =>'n',
                'msg' => 'Not Authorized'
            ]);
            return;
        }

        $tChithaRmkOrdBasic = $this->db->query("SELECT ord_no FROM t_chitha_rmk_ordbasic WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND ord_no=? AND dag_no=? AND year_no=?", [$miscCaseBasicDetails->dist_code, $miscCaseBasicDetails->subdiv_code, $miscCaseBasicDetails->cir_code, $miscCaseBasicDetails->mouza_pargona_code, $miscCaseBasicDetails->lot_no, $miscCaseBasicDetails->vill_townprt_code, $misc_case_no, $miscCaseBasicDetails->dag_no, $miscCaseBasicDetails->year_no])->result();
        if(!empty($tChithaRmkOrdBasic)) {
            $this->db->trans_rollback();
            log_message('error', '#ERRSTRIKEOUTSUBMIT002: Data already exist in t_chitha_rmk_ordbasic for misc case no : '.$misc_case_no);
            $this->output->set_status_header(500);
            echo json_encode([
                'status' =>'n',
                'msg' => 'Error Code: #ERRSTRIKEOUTSUBMIT002. Could not pass order!'
            ]);
            return;
        }

        $tChithaRmkInfavorOf = $this->db->query("SELECT ord_no FROM t_chitha_rmk_infavor_of WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND ord_no=? AND dag_no=? AND year_no=?", [$miscCaseBasicDetails->dist_code, $miscCaseBasicDetails->subdiv_code, $miscCaseBasicDetails->cir_code, $miscCaseBasicDetails->mouza_pargona_code, $miscCaseBasicDetails->lot_no, $miscCaseBasicDetails->vill_townprt_code, $misc_case_no, $miscCaseBasicDetails->dag_no, $miscCaseBasicDetails->year_no])->result();
        if(!empty($tChithaRmkInfavorOf)) {
            $this->db->trans_rollback();
            log_message('error', '#ERRSTRIKEOUTSUBMIT003: Data already exist in t_chitha_rmk_infavor_of for misc case no : '.$misc_case_no);
            $this->output->set_status_header(500);
            echo json_encode([
                'status' =>'n',
                'msg' => 'Error Code: #ERRSTRIKEOUTSUBMIT003. Could not pass order!'
            ]);
            return;
        }

        $note = $this->db->query("SELECT note_no FROM misc_case_process_reports WHERE misc_case_no=? ORDER BY note_no DESC LIMIT 1", [$misc_case_no])->row();
        if(empty($note)) {
            $note_no = 1;
        }
        else {
            $note_no = $note->note_no + 1;
        }
        $processReport = [
            'dist_code' => $miscCaseBasicDetails->dist_code,
            'subdiv_code' => $miscCaseBasicDetails->subdiv_code,
            'cir_code' => $miscCaseBasicDetails->cir_code,
            'note_no' => $note_no,
            'misc_case_no' => $misc_case_no,
            'co_fresh_proceeding' => 'Y',
            'process_note' => $remarks,
            'note_date' => date('Y-m-d'),
            'user_code' => $user_code,
            'operation' => 'c'
        ];
        $insertProcessReportStatus = $this->db->insert("misc_case_process_reports", $processReport);
        if(!$insertProcessReportStatus || $this->db->affected_rows() < 1) {
            $this->db->trans_rollback();
            log_message('error', '#ERRSTRIKEOUTSUBMIT004: Could not insert into misc_case_process_reports for misc case no : '.$misc_case_no);
            $this->output->set_status_header(500);
            echo json_encode([
                'status' =>'n',
                'msg' => 'Error Code: #ERRSTRIKEOUTSUBMIT004. Could not pass order!'
            ]);
            return;
        }

        $proceedingDetails = $this->db->query("SELECT proceeding_id FROM petition_proceeding WHERE case_no=? ORDER BY proceeding_id DESC LIMIT 1", [$misc_case_no])->row();
        if(empty($proceedingDetails)) {
            $proceeding_id = 1;
        }
        else {
            $proceeding_id = $proceedingDetails->proceeding_id + 1;
        }
        $date_entry=date('Y-m-d h:i:s');
        $proceedingData = [
            'case_no' => $misc_case_no,
            'proceeding_id' => $proceeding_id,
            'date_of_hearing' => $date_entry,
            'co_order' => $remarks,
            'note_on_order' => 'Final Order Passed',
            'next_date_of_hearing' => $date_entry,
            'status' => 'Complete',
            'user_code' => $user_code,
            'date_entry' => $date_entry,
            'dist_code' => $miscCaseBasicDetails->dist_code,
            'cir_code' => $miscCaseBasicDetails->cir_code,
            'subdiv_code' => $miscCaseBasicDetails->subdiv_code,
            'operation' => 'E',
            'ip' => $_SERVER['REMOTE_ADDR']
        ];
        $proceedingInsertStatus = $this->db->insert('petition_proceeding', $proceedingData);
        if(!$proceedingInsertStatus || $this->db->affected_rows() < 1) {
            $this->db->trans_rollback();
            log_message('error', '#ERRSTRIKEOUTSUBMIT005 Error in inserting into petition_proceeding for case no: ' . $misc_case_no);
            $this->output->set_status_header(500);
            echo json_encode([
                'status' =>'n',
                'msg' => 'Error Code: #ERRSTRIKEOUTSUBMIT005. Could not pass order!'
            ]);
            return;
        }


        $tchithaRmkOrdBasic = [
            'dist_code' => $miscCaseBasicDetails->dist_code,
            'subdiv_code' => $miscCaseBasicDetails->subdiv_code,
            'cir_code' => $miscCaseBasicDetails->cir_code,
            'mouza_pargona_code' => $miscCaseBasicDetails->mouza_pargona_code,
            'lot_no' => $miscCaseBasicDetails->lot_no,
            'vill_townprt_code' => $miscCaseBasicDetails->vill_townprt_code,
            'dag_no' => $miscCaseBasicDetails->dag_no,
            'year_no' => $miscCaseBasicDetails->year_no,
            'petition_no' => $miscCaseBasicDetails->misc_case_petition_no,
            'ord_no' => $misc_case_no,
            'case_no' => $misc_case_no,
            'ord_passby_sign_yn' => 'Y',
            'ord_passby_desig' => 'CO',
            'ord_ref_let_no' => NULL,
            'lm_code' => $miscCaseBasicDetails->user_code,
            'lm_sign_yn' => $miscCaseBasicDetails->lm_note_yn,
            'lm_sign_date' => date('Y-m-d',strtotime($miscCaseBasicDetails->submission_date)),
            'sk_code' => null,
            'sk_sign_yn' => null,
            'sk_sign_date' => null,
            'co_code' => $user_code,
            'co_sign_yn' => 'Y',
            'co_ord_date' => date('Y-m-d G:i:s'),
            'ord_date' => date('Y-m-d',strtotime($miscCaseBasicDetails->submission_date)),
            'ord_type_code' => '05'
        ];
        $tchithaRmkOrdBasicStatus = $this->db->insert("t_chitha_rmk_ordbasic", $tchithaRmkOrdBasic);
        if(!$tchithaRmkOrdBasicStatus || $this->db->affected_rows() < 1) {
            $this->db->trans_rollback();
            log_message('error', '#ERRSTRIKEOUTSUBMIT006 Error in inserting into t_chitha_rmk_ordbasic for case no: ' . $misc_case_no);
            $this->output->set_status_header(500);
            echo json_encode([
                'status' =>'n',
                'msg' => 'Error Code: #ERRSTRIKEOUTSUBMIT006. Could not pass order!'
            ]);
            return;
        }

        $firstParty = $this->db->query("SELECT * FROM misc_case_first_party WHERE misc_case_no=? AND petition_pdar_id NOT IN (SELECT pdar_id FROM t_chitha_rmk_infavor_of WHERE ord_no=?) LIMIT 1", [$misc_case_no, $misc_case_no])->row();
        if(empty( $firstParty)) {
            $this->db->trans_rollback();
            log_message('error', '#ERRSTRIKEOUTSUBMIT014 Data not found in misc_case_first_party or already in t_chitha_rmk_infavor_of for case no: ' . $misc_case_no);
            $this->output->set_status_header(500);
            echo json_encode([
                'status' =>'n',
                'msg' => 'Error Code: #ERRSTRIKEOUTSUBMIT014. Could not pass order!'
            ]);
            return;
        }

        $inFavor = $this->db->query("select infavor_of_id from t_chitha_rmk_infavor_of where ord_no=? ORDER BY infavor_of_id DESC LIMIT 1", [$misc_case_no])->row();
        if(empty($inFavor)) {
            $inFavID = 1;
        }
        else {
            $inFavID = $inFavor->infavor_of_id + 1;
        }

        $tchithaInfavor = [
            'dist_code' => $miscCaseBasicDetails->dist_code,
            'subdiv_code' =>$miscCaseBasicDetails->subdiv_code,
            'cir_code' => $miscCaseBasicDetails->cir_code,
            'mouza_pargona_code' => $miscCaseBasicDetails->mouza_pargona_code,
            'lot_no' => $miscCaseBasicDetails->lot_no,
            'vill_townprt_code' => $miscCaseBasicDetails->vill_townprt_code,
            'dag_no' => $miscCaseBasicDetails->dag_no,
            'year_no' => $miscCaseBasicDetails->year_no,
            'petition_no' => $miscCaseBasicDetails->misc_case_petition_no,
            'infavor_of_id' => $inFavID,
            'ord_no' => $misc_case_no,
            'ord_date' => date('Y-m-d G:i:s'),
            'patta_type_code' => $miscCaseBasicDetails->patta_type_code,
            'patta_no' => $miscCaseBasicDetails->patta_no,
            'pdar_id'=>  $firstParty->petition_pdar_id,
            'infavor_of_name' => $firstParty->petition_pdar_name_old,
            'by_right_of' => '07',
            'land_area_b' => 0,
            'land_area_k' => 0,
            'land_area_lc' => 0,
            'land_area_g' => 0,
            'land_area_kr' => 0,
            'revenue' => 0,
            'self_declaration' => $firstParty->self_declaration ? $firstParty->self_declaration : null,
            'id_ref_no' => $firstParty->id_ref_no ? $firstParty->id_ref_no : null,
            'auth_type' => $firstParty->auth_type ? $firstParty->auth_type : null,
            'photo' => $firstParty->photo ? $firstParty->photo : null
        ];
        $tchithaInfavorStatus = $this->db->insert("t_chitha_rmk_infavor_of", $tchithaInfavor);
        if(!$tchithaInfavorStatus || $this->db->affected_rows() < 1) {
            $this->db->trans_rollback();
            log_message('error', '#ERRSTRIKEOUTSUBMIT007 Error in inserting into t_chitha_rmk_infavor_of for case no: ' . $misc_case_no);
            $this->output->set_status_header(500);
            echo json_encode([
                'status' =>'n',
                'msg' => 'Error Code: #ERRSTRIKEOUTSUBMIT007. Could not pass order!'
            ]);
            return;
        }
        
        $secondParty = $this->db->query("SELECT msp.*, cp.pdar_name, cp.pdar_father, cp.pdar_guard_reln FROM misc_case_scnd_party msp, chitha_pattadar cp WHERE msp.dist_code=cp.dist_code AND msp.subdiv_code=cp.subdiv_code AND msp.cir_code=cp.cir_code AND msp.opp_pdar_id=cp.pdar_id AND msp.misc_case_no=? AND cp.mouza_pargona_code=? AND cp.lot_no=? AND cp.vill_townprt_code=? AND cp.patta_no=? AND cp.patta_type_code=?", [$misc_case_no, $miscCaseBasicDetails->mouza_pargona_code, $miscCaseBasicDetails->lot_no, $miscCaseBasicDetails->vill_townprt_code, $miscCaseBasicDetails->patta_no, $miscCaseBasicDetails->patta_type_code])->result();
        if(empty($secondParty)) {
            $this->db->trans_rollback();
            log_message('error', '#ERRSTRIKEOUTSUBMIT008 No data in misc_case_scnd_party for case no: ' . $misc_case_no);
            $this->output->set_status_header(500);
            echo json_encode([
                'status' =>'n',
                'msg' => 'Error Code: #ERRSTRIKEOUTSUBMIT008. Could not pass order!'
            ]);
            return;
        }

        foreach ($secondParty as $second) {
            $tchithaOpp = [
                'dist_code' => $second->dist_code,
                'subdiv_code' =>$second->subdiv_code,
                'cir_code' => $second->cir_code,
                'mouza_pargona_code' => $miscCaseBasicDetails->mouza_pargona_code,
                'lot_no' => $miscCaseBasicDetails->lot_no,
                'vill_townprt_code' => $miscCaseBasicDetails->vill_townprt_code,
                'dag_no' => $miscCaseBasicDetails->dag_no,
                'ord_no' => $misc_case_no,
                'ord_date' => date('Y-m-d',strtotime($miscCaseBasicDetails->submission_date)),
                'name_for_id' => $second->opp_pdar_id,
                'name_for' => $second->pdar_name,
                'name_for_guardian' => $second->pdar_father,
                'name_for_guar_relation' => $second->pdar_guard_reln,
                'case_type_code' => '07',     
                'name_for_land_b' => 0,
                'name_for_land_k' => 0,
                'name_for_land_lc' => 0,
                'name_for_land_g' => 0,
                'name_for_land_kr' => 0,
                'case_no' => $misc_case_no
            ];
            $tChithaOppInsertStatus = $this->db->insert("t_chitha_rmk_other_opp_party", $tchithaOpp);
            if(!$tChithaOppInsertStatus || $this->db->affected_rows() < 1) {
                $this->db->trans_rollback();
                log_message('error', '#ERRSTRIKEOUTSUBMIT009. Error in inserting into misc_case_scnd_party for case no: ' . $misc_case_no);
                $this->output->set_status_header(500);
                echo json_encode([
                    'status' =>'n',
                    'msg' => 'Error Code: #ERRSTRIKEOUTSUBMIT009. Could not pass order!'
                ]);
                return;
            }
        }

        $updateBasic = [
            'date_of_operation' => date('Y-m-d'),
            'status' => '10',
            'user_code' => $user_code,
            'operation' => 'E'
        ];
        $this->db->where('misc_case_no', $misc_case_no);
        $updStatus = $this->db->update('misc_case_basic', $updateBasic);
        if(!$updStatus || $this->db->affected_rows() < 1) {
            $this->db->trans_rollback();
            log_message('error', '#ERRSTRIKEOUTSUBMIT010. Error in updating misc_case_basic for case no: ' . $misc_case_no);
            $this->output->set_status_header(500);
            echo json_encode([
                'status' =>'n',
                'msg' => 'Error Code: #ERRSTRIKEOUTSUBMIT010. Could not pass order!'
            ]);
            return;
        }

        $updateFirstParty = [
            'user_code' => $user_code,
            'operation' => 'E'
        ];
        $this->db->where('misc_case_no', $misc_case_no);
        $updMiscCaseFirstStatus = $this->db->update('misc_case_first_party', $updateFirstParty);
        if(!$updMiscCaseFirstStatus || $this->db->affected_rows() < 1) {
            $this->db->trans_rollback();
            log_message('error', '#ERRSTRIKEOUTSUBMIT011. Error in updating misc_case_first_party for case no: ' . $misc_case_no);
            $this->output->set_status_header(500);
            echo json_encode([
                'status' =>'n',
                'msg' => 'Error Code: #ERRSTRIKEOUTSUBMIT011. Could not pass order!'
            ]);
            return;
        }

        $chithaUpdStatus = $this->StrikeoutDataModel->updateChithaNameStrikeOut($misc_case_no, $miscCaseBasicDetails->misc_case_petition_no, $user_code);

        if($chithaUpdStatus['status'] != 'y') {
            $this->db->trans_rollback();
            log_message('error', '#ERRSTRIKEOUTSUBMIT012. Error in updating final chitha data for case no: ' . $misc_case_no);
            $this->output->set_status_header(500);
            echo json_encode([
                'status' =>'n',
                'msg' => 'Error Code: #ERRSTRIKEOUTSUBMIT012. Could not pass order!'
            ]);
            return;
        }

        if(!$this->db->trans_status()) {
            $this->db->trans_rollback();
            log_message('error', '#ERRSTRIKEOUTSUBMIT013. DB Transaction Failed for case no: ' . $misc_case_no);
            $this->output->set_status_header(500);
            echo json_encode([
                'status' =>'n',
                'msg' => 'Error Code: #ERRSTRIKEOUTSUBMIT013. Could not pass order!'
            ]);
            return;
        }

        $this->db->trans_commit();

        $this->output->set_status_header(200);
        echo json_encode([
            'status' =>'y',
            'msg' => 'Successfully passed order for case no: ' . $misc_case_no
        ]);
        return;
    }
}