<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class MutationDataController extends CI_Controller
{
    use CommonTrait;
    private $jwt_data;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Api/ChithaModel');
        $this->load->model('Api/LocationModel');
        $this->load->model('Api/MutationDataModel');
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

    public function getLmVillages() {
        $tokenData = $this->jwt_data;

        $dist_code = $tokenData->dcode;
        $subdiv_code = $tokenData->subdiv_code;
        $cir_code = $tokenData->cir_code;
        $user_code = $tokenData->usercode;

        $this->dbswitch($dist_code);

        $lmuser = $this->db->query("SELECT * FROM lm_code WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND lm_code=?", [$dist_code, $subdiv_code, $cir_code, $user_code])->row();

        $mouza_pargona_code = $lmuser->mouza_pargona_code;
        $lot_no = $lmuser->lot_no;

        $villages = $this->db->query("SELECT * FROM location WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code!='00000'", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no])->result();


        $this->output->set_status_header(200);
        echo json_encode([
            'status' => 'y',
            'msg' => 'Successfully retrieved villages!',
            'data' => $villages
        ]);
        exit;
    }

    public function getPattaTypes() {
        $tokenData = $this->jwt_data;
        $dist_code = $tokenData->dcode;
        $subdiv_code = $tokenData->subdiv_code;
        $cir_code = $tokenData->cir_code;
        $user_code = $tokenData->usercode;
        $this->dbswitch($dist_code);

        $pattaTypes = $this->db->query("SELECT type_code, patta_type, pattatype_eng, mutation FROM patta_code")->result();

        $this->output->set_status_header(200);
        echo json_encode([
            'status' => 'y',
            'msg' => 'Successfully patta types!',
            'data' => $pattaTypes
        ]);
        exit;
    }

    public function getPattaNos() {
        $tokenData = $this->jwt_data;
        
        $data = json_decode(file_get_contents('php://input', true));
        $vill_code = $data->vill_townprt_code;
        $patta_type_code = $data->patta_type_code;

        $dist_code = $tokenData->dcode;
        $subdiv_code = $tokenData->subdiv_code;
        $cir_code = $tokenData->cir_code;

        $villArr = explode('-', $vill_code);
        $mouza_pargona_code = $villArr[3];
        $lot_no = $villArr[4];
        $vill_townprt_code = $villArr[5];



        $this->dbswitch($dist_code);

        $this->db->trans_begin();

        $mergeStatus = $this->LocationModel->mergeVillageData($vill_code, $tokenData->usercode);
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

        $pattaNos = $this->db->query("SELECT patta_no FROM chitha_basic WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND patta_type_code=? GROUP BY dist_code, subdiv_code, cir_code, mouza_pargona_code, lot_no, vill_townprt_code, patta_type_code, patta_no", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $patta_type_code])->result();

        $this->output->set_status_header(200);
        echo json_encode([
            'status' => 'y',
            'msg' => 'Successfully retrieved patta nos!',
            'data' => $pattaNos
        ]);
        exit;
    }

    public function getDags() {
        $tokenData = $this->jwt_data;
        $data = json_decode(file_get_contents('php://input', true));

        $vill_code = $data->vill_townprt_code;
        $patta_type_code = $data->patta_type_code;
        $patta_no = $data->patta_no;

        $dist_code = $tokenData->dcode;
        $subdiv_code = $tokenData->subdiv_code;
        $cir_code = $tokenData->cir_code;

        $villArr = explode('-', $vill_code);
        $mouza_pargona_code = $villArr[3];
        $lot_no = $villArr[4];
        $vill_townprt_code = $villArr[5];

        $this->dbswitch($dist_code);

        $dags = $this->db->query("SELECT dist_code, dag_no, dag_area_b, dag_area_k, dag_area_lc, dag_area_g FROM chitha_basic WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND patta_type_code=? AND patta_no=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $patta_type_code, $patta_no])->result();

        $this->output->set_status_header(200);
        echo json_encode([
            'status' => 'y',
            'msg' => 'Successfully retrieved dags!',
            'data' => $dags
        ]);
        exit;

    }

    public function getPattadars() {
        $tokenData = $this->jwt_data;
        $data = json_decode(file_get_contents('php://input', true));
        
        $vill_code = $data->vill_townprt_code;
        $patta_type_code = $data->patta_type_code;
        $patta_no = $data->patta_no;
        $dag_nos = $data->dag_nos;

        $dist_code = $tokenData->dcode;
        $subdiv_code = $tokenData->subdiv_code;
        $cir_code = $tokenData->cir_code;

        $villArr = explode('-', $vill_code);
        $mouza_pargona_code = $villArr[3];
        $lot_no = $villArr[4];
        $vill_townprt_code = $villArr[5];

        $this->dbswitch($dist_code);

        $pattadars = [];

        if(!empty($dag_nos)) {
            foreach ($dag_nos as $dag_no) {
                $dagPattadars = $this->db->query("SELECT cp.* FROM chitha_pattadar cp, chitha_dag_pattadar cdp WHERE cdp.dist_code=cp.dist_code AND cdp.subdiv_code=cp.subdiv_code AND cdp.cir_code=cp.cir_code AND cdp.mouza_pargona_code=cp.mouza_pargona_code AND cdp.lot_no=cp.lot_no AND cdp.vill_townprt_code=cp.vill_townprt_code AND cdp.patta_type_code=cp.patta_type_code AND cdp.patta_no=cp.patta_no AND cdp.pdar_id=cp.pdar_id AND cdp.dist_code=? AND cdp.subdiv_code=? AND cdp.cir_code=? AND cdp.mouza_pargona_code=? AND cdp.lot_no=? AND cdp.vill_townprt_code=? AND cdp.patta_type_code=? AND cdp.patta_no=? AND cdp.dag_no=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $patta_type_code, $patta_no, $dag_no])->result();

                if(!empty($dagPattadars)) {
                    foreach ($dagPattadars as $dagPattadar) {
                        $pdarString = $dagPattadar->dist_code . '-' . $dagPattadar->subdiv_code . '-' . $dagPattadar->cir_code . '-' . $dagPattadar->mouza_pargona_code . '-' . $dagPattadar->lot_no . '-' . $dagPattadar->vill_townprt_code . '-' . $dagPattadar->patta_type_code . '-' . $dagPattadar->patta_no . '-' . $dagPattadar->pdar_id;

                        if(!in_array($pdarString, $pattadars)) {
                            $pattadars[] = $pdarString;
                        }
                    }
                }
            }
        }

        $pdarData = [];

        if(!empty($pattadars)) {
            foreach ($pattadars as $pdar) {
                $pdarArr = explode('-', $pdar);
                $d = $pdarArr[0];
                $s = $pdarArr[1];
                $c = $pdarArr[2];
                $m = $pdarArr[3];
                $l = $pdarArr[4];
                $v = $pdarArr[5];
                $ptc = $pdarArr[6];
                $pn = $pdarArr[7];
                $id = $pdarArr[8];

                $pdarDetails = $this->db->query("SELECT pdar_name, pdar_father FROM chitha_pattadar WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND patta_type_code=? AND patta_no=? AND pdar_id=?", [$d, $s, $c, $m, $l, $v, $ptc, $pn, $id])->row();

                $rowPdar['unique_id'] = $pdar;
                $rowPdar['pdar_name'] = $pdarDetails->pdar_name;
                $rowPdar['pdar_father'] = $pdarDetails->pdar_father;

                $pdarData[] = $rowPdar;
            }
        }
        
        $this->output->set_status_header(200);
        echo json_encode([
            'status' => 'y',
            'msg' => 'Successfully retrieved pattadars!',
            'data' => $pdarData
        ]);
        exit;
    }

    public function getPdarDags() {
        $tokenData = $this->jwt_data;
        $data = json_decode(file_get_contents('php://input', true));

        $unique_id = $data->unique_id;
        $dag_nos = $data->dag_nos;

        $dataArr = explode('-', $unique_id);
        $dist_code = $dataArr[0];
        $subdiv_code = $dataArr[1];
        $cir_code = $dataArr[2];
        $mouza_pargona_code = $dataArr[3];
        $lot_no = $dataArr[4];
        $vill_townprt_code = $dataArr[5];
        $patta_type_code = $dataArr[6];
        $patta_no = $dataArr[7];
        $pdar_id = $dataArr[8];

        if(empty($dag_nos)) {
            $this->output->set_status_header(500);
            echo json_encode([
                'status' =>'n',
                'msg' => 'No dags Selected!'
            ]);
            return;
        }
        
        $dag_nos_string = "('" . implode("','", $dag_nos) . "')";

        $this->dbswitch($dist_code);

        $pdar_dags = $this->db->query("SELECT dag_no FROM chitha_dag_pattadar WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND patta_no=? AND patta_type_code=? AND pdar_id=? AND dag_no IN " . $dag_nos_string, [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $patta_no, $patta_type_code, $pdar_id])->result();

        if(empty($pdar_dags)) {
            $this->output->set_status_header(500);
            echo json_encode([
                'status' =>'n',
                'msg' => 'No dags Found!',
                'data' => []
            ]);
            return;
        }

        $this->output->set_status_header(200);
        echo json_encode([
            'status' =>'y',
            'msg' => 'Successfully retrieved dags',
            'data' => $pdar_dags
        ]);
        return;
    }

    public function getTransferTypes() {
        $tokenData = $this->jwt_data;
        $dist_code = $tokenData->dcode;

        $this->dbswitch($dist_code);

        $transferTypes = $this->db->query("SELECT trans_code, trans_desc_as, trans_type FROM nature_trans_code")->result();

        $this->output->set_status_header(200);
        echo json_encode([
            'status' =>'y',
            'msg' => 'Successfully retrieved data',
            'data' => $transferTypes
        ]);
        return;
    }


    public function getPattadarDags() {
        $tokenData = $this->jwt_data;
        $dcode = $tokenData->dcode;

        $data = json_decode(file_get_contents('php://input', true));

        if(!$data) {
            $this->output->set_status_header(401);
            echo json_encode([
                'status' =>'n',
                'msg' => 'No Input found!'
            ]);
            return;
        }

        $pattadars = $data->pattadars;
        $dag_nos = $data->dag_nos;

        // echo '<pre>';
        // var_dump($dag_nos, $pattadars);
        // die;

        if(empty($pattadars)) {
            $this->output->set_status_header(401);
            echo json_encode([
                'status' =>'n',
                'msg' => 'No Input found!'
            ]);
            return;
        }

        $this->dbswitch($dcode);

        $finalArr = [];

        foreach ($pattadars as $pdar) {
            $pdarArr = explode('-', $pdar);
            $dist_code = $pdarArr[0];
            $subdiv_code = $pdarArr[1];
            $cir_code = $pdarArr[2];
            $mouza_pargona_code = $pdarArr[3];
            $lot_no = $pdarArr[4];
            $vill_townprt_code = $pdarArr[5];
            $patta_type_code = $pdarArr[6];
            $patta_no = $pdarArr[7];
            $pdar_id = $pdarArr[8];

            $pdarDags = $this->db->query("SELECT cdp.dist_code, cdp.subdiv_code, cdp.cir_code, cdp.mouza_pargona_code, cdp.lot_no, cdp.vill_townprt_code, cdp.patta_type_code, cdp.patta_no, cdp.pdar_id, cdp.dag_no, cp.pdar_name, cp.pdar_father FROM chitha_dag_pattadar cdp, chitha_pattadar cp WHERE cdp.dist_code=cp.dist_code AND cdp.subdiv_code=cp.subdiv_code AND cdp.cir_code=cp.cir_code AND cdp.mouza_pargona_code=cp.mouza_pargona_code AND cdp.lot_no=cp.lot_no AND cdp.vill_townprt_code=cp.vill_townprt_code AND cdp.patta_type_code=cp.patta_type_code AND cdp.patta_no=cp.patta_no AND cdp.pdar_id=cp.pdar_id AND cdp.dist_code=? AND cdp.subdiv_code=? AND cdp.cir_code=? AND cdp.mouza_pargona_code=? AND cdp.lot_no=? AND cdp.vill_townprt_code=? AND cdp.patta_type_code=? AND cdp.patta_no=? AND cdp.pdar_id=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $patta_type_code, $patta_no, $pdar_id])->result();

            if(!empty($pdarDags)) {
                foreach ($pdarDags as $pdarDag) {
                    if(in_array($pdarDag->dag_no, $dag_nos)) {
                        $pdarDag->unique_id = $pdarDag->dist_code . '-' . $pdarDag->subdiv_code . '-' . $pdarDag->cir_code . '-' . $pdarDag->mouza_pargona_code . '-' . $pdarDag->lot_no . '-' . $pdarDag->vill_townprt_code . '-' . $pdarDag->patta_type_code . '-' . $pdarDag->patta_no . '-' . $pdarDag->pdar_id . '-' . $pdarDag->dag_no;

                        $finalArr[] = $pdarDag;
                    }
                }
            }
        }



        $this->output->set_status_header(200);
        echo json_encode([
            'status' =>'y',
            'msg' => 'Successfully retrieved data',
            'data' => $finalArr
        ]);
        return;

    }

    public function submitLmMutation() {
        $tokenData = $this->jwt_data;
        $dcode = $tokenData->dcode;
        $subdiv_code = $tokenData->subdiv_code;
        $cir_code = $tokenData->cir_code;
        $user_code = $tokenData->usercode;

        $this->dbswitch($dcode);

        $lmData = $this->MutationDataModel->authorizeLM ($dcode, $subdiv_code, $cir_code, $user_code);
        if($lmData['status'] != 'y') {
            $this->output->set_status_header(401);
            echo json_encode([
                'status' =>'n',
                'msg' => 'Not Authorized!'
            ]);
            return;
        }
        // $lmData = $lmData['data'];

        $data = json_decode(file_get_contents('php://input', true));

        $location = $data->location;
        $locationArr = explode('-', $location);
        $dist_code = $locationArr[0];
        $subdiv_code = $locationArr[1];
        $cir_code = $locationArr[2];
        $mouza_pargona_code = $locationArr[3];
        $lot_no = $locationArr[4];
        $vill_townprt_code = $locationArr[5];
        $patta_type_code = $data->patta_type_code;
        $patta_no = $data->patta_no;
        $dag_nos = $data->dag_nos;
        $pattadars = $data->pattadars;
        $inplace_alongwith = $data->inplace_alongwith;
        $applicants = $data->applicants;
        $transfer_type = $data->transfer_type;
        $deed_date = $data->deed_date ? $data->deed_date : null;
        $deed_no = $data->deed_no ? $data->deed_no : null;
        $deed_value = $data->deed_value ? $data->deed_value : null;
        $rajah_adalat = $data->rajah_adalat ? ($data->rajah_adalat == 'y' ? $data->rajah_adalat : '0') : '0';
        $dispute = $data->dispute ? ($data->dispute == 'y' ? '1' : '0') : '0';
        $applicant_possession = $data->applicant_possession ? $data->applicant_possession : null;
        $m_bigha = $data->m_bigha;
        $m_katha = $data->m_katha;
        $m_lessa_chatak = $data->m_lessa_chatak;
        $m_ganda = $data->m_ganda;
        $remarks = $data->remarks;


        // echo '<pre>';
        // var_dump($data);
        // die;

        if(empty($applicants)) {
             $this->output->set_status_header(401);
            echo json_encode([
                'status' =>'n',
                'msg' => 'Applicants not found!'
            ]);
            return;
        }

        if(empty($pattadars)) {
            $this->output->set_status_header(401);
            echo json_encode([
                'status' =>'n',
                'msg' => 'Pattadars not found!'
            ]);
            return;
        }

        if(empty($dag_nos)) {
            $this->output->set_status_header(401);
            echo json_encode([
                'status' =>'n',
                'msg' => 'Dag Nos not found!'
            ]);
            return;
        }

        

        $case_name = $this->MutationDataModel->generateCaseName($dist_code, $subdiv_code, $cir_code);
        $seq_pet = year_no . '000';

        $petition_no = $seq_pet . $this->MutationDataModel->generateFieldPetitionNo();
        $case_no = $case_name . $petition_no . "/FMUT/RESURVEY";

        $trans_code = explode('-', $transfer_type)[0];
        $type = explode('-', $transfer_type)[1];
        

        $this->db->trans_begin();
        $basic = [
            'dist_code'=>$dist_code,
            'subdiv_code'=>$subdiv_code,
            'cir_code'=>$cir_code,
            'mouza_pargona_code'=>$mouza_pargona_code,
            'lot_no'=>$lot_no,
            'vill_townprt_code'=>$vill_townprt_code,
            'user_code'=>$tokenData->usercode,
            'date_entry'=>date('Y-m-d'),
            'case_no'=>$case_no,
            'trans_code'=> $trans_code,
            'dispute_yn'=>$dispute,
            'possession_yn'=>$applicant_possession,
            'petition_no'=>$petition_no,
            'year_no'=>date('Y'),
            'report_date'=>date('Y-m-d'),
            'mut_type'=>'01',                    
            'operation'=>'E',
            // 'noc_no'=>$data['secParty'][0]->noc_no,
            // 'noc_date'=>$data['secParty'][0]->noc_date,
            'reg_deed_no' => $deed_no,
            'deed_value' => $deed_value,
            'reg_deed_date' => $deed_date,
            'rajah_adalat' => $rajah_adalat,
            'add_off_desig' => 'CO'
        ];
        $mutBasicStatus = $this->db->insert('field_mut_basic', $basic);
        if(!$mutBasicStatus || $this->db->affected_rows() < 1) {
            $this->db->trans_rollback();
            log_message('error', '#MUTAPPLY001 Error in inserting into field_mut_basic');
            $this->output->set_status_header(500);
            echo json_encode([
                'status' =>'n',
                'msg' => 'Error code: #MUTAPPLY001!'
            ]);
            return;
        }

        $i = 0;
        foreach ($applicants as $applicant) {
            $applicant_unique_id = $applicant->applicant_unique_id;
            $applicant_name = $applicant->applicant_name;
            $applicant_guard_name = $applicant->applicant_guard_name;
            $applicant_guard_rel = $applicant->applicant_guard_rel ? $applicant->applicant_guard_rel : null;
            $applicant_gender = $applicant->applicant_gender ? ($applicant->applicant_gender == 'm' ? 'M' : ($applicant->applicant_gender == 'f' ? 'F' : ($applicant->applicant_gender == 'o' ? 'O' : null))) : null;
            $applicant_mobile = $applicant->applicant_mobile;

            $mutPetitioner = [
                'dist_code' => $dist_code,
                'subdiv_code' => $subdiv_code,
                'cir_code' => $cir_code,
                'mouza_pargona_code' => $mouza_pargona_code,
                'lot_no' => $lot_no,
                'vill_townprt_code' => $vill_townprt_code,
                'user_code' => $tokenData->usercode,
                'date_entry' => date('Y-m-d'),
                'case_no' => $case_no,
                'petition_no' => $petition_no,
                'year_no' => date('Y'),
                'operation' => 'E',
                'pet_name' => $applicant_name,
                'guard_name' => $applicant_guard_name,
                'guard_rel' => $applicant_guard_rel,
                'pet_gender'=> $applicant_gender,
                'pet_minor_yn' => 'N',
                'hus_wife' => '0',
                'add1' => null,//
                'add2' => null,//
                'pet_id' => ++$i,
                'pdar_mobile' => $applicant_mobile,
                'new_pet_name' => 'N',
                'pdar_id' => null,
                'self_declaration' => null,
                'auth_type' => null,
                'id_ref_no'=> null,
                'photo' => null,//
                'pdar_name_eng' => null,
                'pdar_guard_eng' => null,

                // 'marital_status' => null, //
                // 'applicant_occupation' => null,//
                // 'caste_category' => null,//
                // 'tribe_category' => null,
            ];
            $mutPetInsertStatus = $this->db->insert('field_mut_petitioner', $mutPetitioner);
            if(!$mutPetInsertStatus || $this->db->affected_rows() < 1) {
                $this->db->trans_rollback();
                log_message('error', '#MUTAPPLY002 Error in inserting into field_mut_petitioner');
                $this->output->set_status_header(500);
                echo json_encode([
                    'status' =>'n',
                    'msg' => 'Error code: #MUTAPPLY002!'
                ]);
                return;
            }
        }

        // field_mut_pattadar
        $dag_nos_in = "('" . implode("','", $dag_nos) . "')";
        foreach ($pattadars as $pdar) {
            $pdarArr = explode('-', $pdar);
            $pdar_dist_code = $pdarArr[0];
            $pdar_subdiv_code = $pdarArr[1];
            $pdar_cir_code = $pdarArr[2];
            $pdar_mouza_pargona_code = $pdarArr[3];
            $pdar_lot_no = $pdarArr[4];
            $pdar_vill_townprt_code = $pdarArr[5];
            $pdar_patta_type_code = $pdarArr[6];
            $pdar_patta_no = $pdarArr[7];
            $pdar_id = $pdarArr[8];

            
            $pdar_in_dag_nos = $this->db->query("SELECT cdp.dag_no, cp.pdar_name, cp.pdar_father, cp.pdar_guard_reln, cp.pdar_gender FROM chitha_dag_pattadar cdp, chitha_pattadar cp WHERE cdp.dist_code=cp.dist_code AND cdp.subdiv_code=cp.subdiv_code AND cdp.cir_code=cp.cir_code AND cdp.mouza_pargona_code=cp.mouza_pargona_code AND cdp.lot_no=cp.lot_no AND cdp.vill_townprt_code=cp.vill_townprt_code AND cdp.patta_no=cp.patta_no AND cdp.patta_type_code=cp.patta_type_code AND cdp.pdar_id=cp.pdar_id AND cdp.dist_code=? AND cdp.subdiv_code=? AND cdp.cir_code=? AND cdp.mouza_pargona_code=? AND cdp.lot_no=? AND cdp.vill_townprt_code=? AND cdp.patta_no=? AND cdp.patta_type_code=? AND cdp.pdar_id=? AND cdp.dag_no in " . $dag_nos_in, [$pdar_dist_code, $pdar_subdiv_code, $pdar_cir_code, $pdar_mouza_pargona_code, $pdar_lot_no, $pdar_vill_townprt_code, $pdar_patta_no, $pdar_patta_type_code, $pdar_id])->result();

            if(empty($pdar_in_dag_nos)) {
                $this->db->trans_rollback();
                log_message('error', '#MUTAPPLY003 pattadar not found for the selected dags');
                $this->output->set_status_header(500);
                echo json_encode([
                    'status' =>'n',
                    'msg' => 'Error code: #MUTAPPLY003!'
                ]);
                return;
            }

            foreach ($pdar_in_dag_nos as $pdar_dag) {
                $name = $pdar_dist_code . '-' . $pdar_subdiv_code . '-' . $pdar_cir_code . '-' . $pdar_mouza_pargona_code . '-' . $pdar_lot_no . '-' . $pdar_vill_townprt_code . '-' . $pdar_patta_type_code . '-' . $pdar_patta_no . '-' . $pdar_id . '-' . $pdar_dag->dag_no;
                if($type == 'i') {
                    $striked_out = '1';
                }
                else {
                    $inplacealong = '';
                    foreach ($inplace_alongwith as $inalong) {
                        if($inalong->name == $name) {
                            $inplacealong = $inalong->value;
                        }
                    }
                    if($inplacealong == 'inplace') {
                        $striked_out = '1';
                    }
                    else {
                        $striked_out = '0';
                    }
                }
                $sellerInsert = [
                    'dist_code'             =>  $pdar_dist_code,
                    'subdiv_code'           =>  $pdar_subdiv_code,
                    'cir_code'              =>  $pdar_cir_code,
                    'mouza_pargona_code'    =>  $pdar_mouza_pargona_code,
                    'lot_no'                =>  $pdar_lot_no,
                    'vill_townprt_code'     =>  $pdar_vill_townprt_code,
                    'user_code'             =>  $tokenData->usercode,
                    'date_entry'            =>  date('Y-m-d'),
                    'case_no'               =>  $case_no,
                    'petition_no'           =>  $petition_no,
                    'year_no'               =>  date('Y'),
                    'operation'             =>  'E',
                    'dag_no'                =>  $pdar_dag->dag_no,
                    'patta_no'              =>  $pdar_patta_no,
                    'patta_type_code'       =>  $pdar_patta_type_code,
                    'pdar_id'               =>  $pdar_id,
                    'pdar_cron_no'          =>  $pdar_id,
                    'pdar_name'             =>  $pdar_dag->pdar_name,
                    'pdar_guardian'         =>  $pdar_dag->pdar_father,
                    'striked_out'           =>  $striked_out,
                    // 'striked_out'           =>  $this->input->post($pet->mutation_deed_id . '_' . $pet->chitha_pdar_id),/////for inheritance//////
                    'pdar_rel_guar'         =>  isset($pdar_dag->pdar_guard_reln) ? $pdar_dag->pdar_guard_reln : 'u',//$this->utilityclass->relationRevertBasu($data['app']->dist_code,$pet->gurdian_relation_id),/////////////
                    'pdar_gender'           =>  isset($pdar_dag->pdar_gender) ? $pdar_dag->pdar_gender : 'M',//$this->utilityclass->gnderRevertBasu($data['app']->dist_code,$pet->gender),
                ];

                $mutPdarInsert = $this->db->insert('field_mut_pattadar', $sellerInsert);

                if(!$mutPdarInsert || $this->db->affected_rows() < 1) {
                    $this->db->trans_rollback();
                    log_message('error', '#MUTAPPLY004 Error in inserting into field_mut_pattadar');
                    $this->output->set_status_header(500);
                    echo json_encode([
                        'status' =>'n',
                        'msg' => 'Error code: #MUTAPPLY004!'
                    ]);
                    return;
                }
            }
        }

        foreach ($dag_nos as $dagNo) {
            $chithaBasicDetails = $this->db->query("SELECT * FROM chitha_basic WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND patta_no=? AND patta_type_code=? AND dag_no=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $patta_no, $patta_type_code, $dagNo])->row();

            if(empty($chithaBasicDetails)) {
                $this->db->trans_rollback();
                log_message('error', '#MUTAPPLY005 Dag No not found in chitha_basic');
                $this->output->set_status_header(500);
                echo json_encode([
                    'status' =>'n',
                    'msg' => 'Error code: #MUTAPPLY005!'
                ]);
                return;
            }

            if(in_array($trans_code, ['11', '01', '02'])){
                $m_bigha = $m_katha = $m_lessa_chatak = $m_ganda = 0;
            }

            $dagDetails = [
                'dist_code'           =>    $chithaBasicDetails->dist_code,
                'subdiv_code'         =>    $chithaBasicDetails->subdiv_code,
                'cir_code'            =>    $chithaBasicDetails->cir_code,
                'mouza_pargona_code'  =>    $chithaBasicDetails->mouza_pargona_code,
                'lot_no'              =>    $chithaBasicDetails->lot_no,
                'vill_townprt_code'   =>    $chithaBasicDetails->vill_townprt_code,
                'user_code'           =>    $tokenData->usercode,
                'date_entry'          =>    date('Y-m-d'),
                'case_no'             =>    $case_no,
                'petition_no'         =>    $petition_no,
                'year_no'             =>    date('Y'),
                'operation'           =>    'E',
                'dag_no'              =>    $dagNo,
                'patta_no'            =>    $patta_no,
                'patta_type_code'     =>    $patta_type_code,
                'm_dag_area_b'        =>    $m_bigha,
                'm_dag_area_k'        =>    $m_katha,
                'm_dag_area_lc'       =>    $m_lessa_chatak,
                'm_dag_area_g'        =>    $m_ganda,
                'm_dag_area_kr'       =>    '0',
                'dag_area_b'          =>    $chithaBasicDetails->dag_area_b,
                'dag_area_k'          =>    $chithaBasicDetails->dag_area_k,
                'dag_area_lc'         =>    $chithaBasicDetails->dag_area_lc,  
                'dag_area_g'          =>    $chithaBasicDetails->dag_area_g,
                'dag_area_kr'         =>    $chithaBasicDetails->dag_area_kr,
                'remark'              =>    addslashes(trim($remarks)),
                'deed_reg_no'         =>    $deed_no,
                'deed_date'           =>    $deed_date,
                'deed_value'          =>    $deed_value,
            ];

            $mutDagDetailsInsert = $this->db->insert('field_mut_dag_details', $dagDetails);
            if(!$mutDagDetailsInsert || $this->db->affected_rows() < 1) {
                $this->db->trans_rollback();
                log_message('error', '#MUTAPPLY006 Error in inserting into field_mut_dag_details');
                $this->output->set_status_header(500);
                echo json_encode([
                    'status' =>'n',
                    'msg' => 'Error code: #MUTAPPLY006!'
                ]);
                return;
            }
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
            'co_order' => null,
            'note_on_order' => 'Forwarded to CO',
            'next_date_of_hearing' => $date_entry,
            'status' => 'Pending',
            'user_code' => $tokenData->usercode,
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
            log_message('error', '#MUTAPPLY007 Error in inserting into petition_proceeding');
            $this->output->set_status_header(500);
            echo json_encode([
                'status' =>'n',
                'msg' => 'Error code: #MUTAPPLY007!'
            ]);
            return;
        }

        // $uuid 
        // $basundhara = [
        //     'dharitree' => $case_no,
        //     'basundhara' => $application_no,
        //     'date_reg' => date('Y-m-d'),
        //     'reg_by' => $tokenData->usercode,
        //     'app_status' => 'P',
        //     'pending_with' => 'CO',
        //     'uuid' => 
        // ];

        if(!$this->db->trans_status()) {
            $this->db->trans_rollback();
            log_message('error', '#MUTAPPLY008 DB Transaction Failed!');
            $this->output->set_status_header(500);
            echo json_encode([
                'status' =>'n',
                'msg' => 'Error code: #MUTAPPLY008!'
            ]);
            return;
        }

        $this->db->trans_commit();

        $this->output->set_status_header(200);
        echo json_encode([
            'status' =>'y',
            'msg' => 'Successfully Generated Mutation Case No. ' . $case_no
        ]);
        return;
    }

    public function getLmMutCases() {
        $tokenData = $this->jwt_data;
        $dcode = $tokenData->dcode;
        $subdiv_code = $tokenData->subdiv_code;
        $cir_code = $tokenData->cir_code;
        $user_code = $tokenData->usercode;

        $data = json_decode(file_get_contents('php://input', true));

        $this->dbswitch($dcode);

        $lmData = $this->MutationDataModel->authorizeLM ($dcode, $subdiv_code, $cir_code, $user_code);
        if($lmData['status'] != 'y') {
            $this->output->set_status_header(500);
            echo json_encode([
                'status' =>'n',
                'msg' => 'Not Authorized!'
            ]);
            return;
        }
        $lmData = $lmData['data'];

        // echo '<pre>';
        // var_dump($tokenData, $lmData);
        // die;

        $mutBasic = $this->db->query("SELECT * FROM field_mut_basic WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=?", [$lmData->dist_code, $lmData->subdiv_code, $lmData->cir_code, $lmData->mouza_pargona_code, $lmData->lot_no])->result();
        if(empty($mutBasic)) {
            $this->output->set_status_header(500);
            echo json_encode([
                'status' =>'n',
                'msg' => 'No data available!'
            ]);
            return;
        }

        foreach ($mutBasic as $mut) {
            $dagDetails = $this->db->query("SELECT * FROM field_mut_dag_details WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND case_no=?", [$mut->dist_code, $mut->subdiv_code, $mut->cir_code, $mut->mouza_pargona_code, $mut->lot_no, $mut->vill_townprt_code, $mut->case_no])->result();

            if($mut->order_passed == 'y' && $mut->date_of_order != null && $mut->date_of_order != '') {
                $mut->status = 'Order passed by CO';
            }
            else {
                $mut->status = 'Order pending by CO';
            }

            // $total_area = 0;

            // $bigha = 0;
            // $katha = 0;
            // $lessaChatak = 0;
            // $ganda = 0;

            if(!empty($dagDetails)) {
                foreach ($dagDetails as $dagDetail) {
                    $b = $dagDetail->m_dag_area_b;
                    $k = $dagDetail->m_dag_area_k;
                    $lc = $dagDetail->m_dag_area_lc;
                    $g = $dagDetail->m_dag_area_g;

                    $mut->m_dag_area_b = $b;
                    $mut->m_dag_area_k = $k;
                    $mut->m_dag_area_lc = $lc;
                    $mut->m_dag_area_g = $g;


                    // if(in_array($dcode, BARAK_VALLEY)) {
                    //     $total_area += $b * 6400 + $k * 320 + $lc * 20 + $g;
                    // }
                    // else {
                    //     $total_area += $b * 100 + $k * 20 + $lc;
                    // }
                }
            }

            // if(in_array($dcode, BARAK_VALLEY)) {
            //     $bigha = floor($total_area / 6400);
            //     $katha = floor(($total_area - ($bigha * 6400)) / 320);
            //     $lessaChatak = floor(($total_area - ($bigha * 6400 + $katha * 320)) / 20);
            //     $ganda = number_format($total_area - ($bigha * 6400 + $katha * 320 + $lessaChatak * 20), 4);
            // }
            // else {
            //     $bigha = floor($total_area / 100);
            //     $katha = floor(($total_area - ($bigha * 100)) / 20);
            //     $lessaChatak = $total_area - ($bigha * 100 + $katha * 20);
            //     $ganda = '0';
            // }


            // $mut->m_bigha = $bigha;
            // $mut->m_katha = $katha;
            // $mut->m_lessa_chatak = $lessaChatak;
            // $mut->m_ganda = $ganda;

            $village = $this->db->query("SELECT loc_name, locname_eng FROM location WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=?", [$mut->dist_code, $mut->subdiv_code, $mut->cir_code, $mut->mouza_pargona_code, $mut->lot_no, $mut->vill_townprt_code])->row();

            if(empty($village)) {
                $mut->vill_townprt_name = '';
            }
            else {
                $mut->vill_townprt_name = $village->loc_name . '(' . $village->locname_eng . ')';
            }

            $mut->date_entry_name = date('d-m-Y', strtotime($mut->date_entry));

            $proceeding = $this->db->query("SELECT co_order, note_on_order FROM petition_proceeding WHERE case_no=? ORDER BY proceeding_id DESC LIMIT 1", [$mut->case_no])->row();
            $mut->co_order = $proceeding->co_order;
            $mut->note_on_order = $proceeding->note_on_order;



        }
        
        $this->output->set_status_header(200);
        echo json_encode([
            'status' =>'y',
            'msg' => 'Successfully Retrieved Data!',
            'data' => $mutBasic
        ]);
        return;

    }

    public function getCOMutCases() {
        $tokenData = $this->jwt_data;
        $dcode = $tokenData->dcode;
        $subdiv_code = $tokenData->subdiv_code;
        $cir_code = $tokenData->cir_code;

        // $data = json_decode(file_get_contents('php://input', true));

        $this->dbswitch($dcode);

        $cases = $this->db->query("SELECT * FROM field_mut_basic WHERE dist_code=? AND subdiv_code=? AND cir_code=?", [$dcode, $subdiv_code, $cir_code])->result();

        if(empty($cases)) {
            $this->output->set_status_header(500);
            echo json_encode([
                'status' =>'n',
                'msg' => 'No data available!'
            ]);
            return;
        }

        foreach ($cases as $case) {
            $case->date_entry_name = date('d-m-Y', strtotime($case->date_entry));
            $case->vill_townprt_name = $this->utilityclass->getVillageName($case->dist_code, $case->subdiv_code, $case->cir_code, $case->mouza_pargona_code, $case->lot_no, $case->vill_townprt_code);
            $case->lot_name = $this->utilityclass->getLotName($case->dist_code, $case->subdiv_code, $case->cir_code, $case->mouza_pargona_code, $case->lot_no);
            $case->mouza_pargona_name = $this->utilityclass->getMouzaName($case->dist_code, $case->subdiv_code, $case->cir_code, $case->mouza_pargona_code);

            if($case->order_passed == 'y' && $case->date_of_order != null && $case->date_of_order != '') {
                $case->status = 1;
            }
            else {
                $case->status = 0;
            }
        }
        // echo '<pre>';
        // var_dump($cases);
        // die;

        $this->output->set_status_header(200);
        echo json_encode([
            'status' =>'y',
            'msg' => 'Successfully Retrieved Data!',
            'data' => $cases
        ]);
        return;
    }

    public function getCoCase() {
        $tokenData = $this->jwt_data;
        $data = json_decode(file_get_contents('php://input', true));

        if(!$data || !$tokenData) {
            $this->output->set_status_header(401);
            echo json_encode([
                'status' =>'n',
                'msg' => 'Error in token and input!'
            ]);
            return;
        }

        $dcode = $tokenData->dcode;
        $subdiv_code = $tokenData->subdiv_code;
        $cir_code = $tokenData->cir_code;

        $case_no = $data->case_no;

        $this->dbswitch($dcode);

        $coCases = $this->db->query("SELECT * FROM field_mut_basic WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND case_no=? AND (order_passed IS NULL OR order_passed != 'y')", [$dcode, $subdiv_code, $cir_code, $case_no])->row();

        if(empty($coCases)) {
            $this->output->set_status_header(500);
            echo json_encode([
                'status' =>'n',
                'msg' => 'Could not find case!'
            ]);
            return;
        }

        

        $coCases->mouza_pargona_name = $this->utilityclass->getMouzaName($coCases->dist_code, $coCases->subdiv_code, $coCases->cir_code, $coCases->mouza_pargona_code);
        $coCases->lot_name = $this->utilityclass->getLotName($coCases->dist_code, $coCases->subdiv_code, $coCases->cir_code, $coCases->mouza_pargona_code, $coCases->lot_no);
        $coCases->vill_townprt_name = $this->utilityclass->getVillageName($coCases->dist_code, $coCases->subdiv_code, $coCases->cir_code, $coCases->mouza_pargona_code, $coCases->lot_no, $coCases->vill_townprt_code);
        $transfertype = $this->db->query("SELECT trans_desc_as FROM nature_trans_code WHERE trans_code=?", [$coCases->trans_code])->row();
        if(empty($transfertype)) {
            $coCases->transfer_type = '';
        }
        else {
            $coCases->transfer_type = $transfertype->trans_desc_as;
        }
        


        $caseStatus = $this->db->query("SELECT case_no, date_of_hearing, note_on_order, co_order, status FROM petition_proceeding WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND case_no=? ORDER BY proceeding_id DESC LIMIT 1", [$coCases->dist_code, $coCases->subdiv_code, $coCases->cir_code, $coCases->case_no])->row();

        
        $dagDetails = $this->db->query("SELECT dag_no, m_dag_area_b, m_dag_area_k, m_dag_area_lc, m_dag_area_g, dag_area_b, dag_area_k, dag_area_lc, dag_area_g, deed_reg_no, deed_value, deed_date, patta_no, patta_type_code FROM field_mut_dag_details WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND case_no=?", [$coCases->dist_code, $coCases->subdiv_code, $coCases->cir_code, $coCases->mouza_pargona_code, $coCases->lot_no, $coCases->vill_townprt_code, $coCases->case_no])->result();

        $mutPetitioner = $this->db->query("SELECT pet_id, pet_name, guard_name, guard_rel, add1, add2, pet_gender, pdar_mobile FROM field_mut_petitioner WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND case_no=?", [$coCases->dist_code, $coCases->subdiv_code, $coCases->cir_code, $coCases->mouza_pargona_code, $coCases->lot_no, $coCases->vill_townprt_code, $coCases->case_no])->result();

        $mutPattadar = $this->db->query("SELECT dag_no, patta_no, patta_type_code, pdar_id, pdar_name, pdar_guardian, striked_out, pdar_rel_guar, pdar_gender FROM field_mut_pattadar WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND case_no=?", [$coCases->dist_code, $coCases->subdiv_code, $coCases->cir_code, $coCases->mouza_pargona_code, $coCases->lot_no, $coCases->vill_townprt_code, $coCases->case_no])->result();

        if(!empty($mutPetitioner)) {
            foreach ($mutPetitioner as $mutPet) {
                $mutPet->guard_rel_name = $this->utilityclass->get_relation($mutPet->guard_rel);
                $mutPet->pet_gender_name = $this->utilityclass->getGenderName($mutPet->pet_gender);
            }
        }

        if(!empty($dagDetails)) {
            $coCases->patta_no = $dagDetails[0]->patta_no;
            $coCases->patta_type_code = $dagDetails[0]->patta_type_code;
            $coCases->patta_type_name = $this->utilityclass->getPattaName($coCases->patta_type_code);
        }
        else {
            $coCases->patta_no = '';
            $coCases->patta_type_code = '';
            $coCases->patta_type_name = '';
        }

        $row['case_details'] = $coCases;
        $row['case_status'] = $caseStatus;
        $row['dag_details'] = $dagDetails;
        $row['petitioners'] = $mutPetitioner;
        $row['pattadars'] = $mutPattadar;

        // $finalArr = [$row];


        $this->output->set_status_header(200);
        echo json_encode([
            'status' =>'y',
            'msg' => 'Successfully retrieved data!',
            'data' => $row
        ]);
        return;

        
    }

    public function submitCase() {
        $tokenData = $this->jwt_data;
        $data = json_decode(file_get_contents('php://input', true));

        $dcode = $tokenData->dcode;
        $subdiv_code = $tokenData->subdiv_code;
        $cir_code = $tokenData->cir_code;

        $case_no = $data->case_no;

        $this->dbswitch($dcode);

        $fmb = $this->db->query("SELECT * FROM field_mut_basic WHERE case_no=?", [$case_no])->row();

        $dags = $this->db->query("SELECT * FROM field_mut_dag_details WHERE case_no=?", [$case_no])->result();

        if(empty($fmb) || empty($dags)) {
            $this->output->set_status_header(500);
            echo json_encode([
                'status' =>'n',
                'msg' => 'Could not find case details!'
            ]);
            return;
        }

        $this->db->trans_begin();

        foreach ($dags as $dag) {
            $pattadars_in_chitha_pattadar = $this->db->query("select max(pdar_id::int)+1 as cp from chitha_pattadar where dist_code='$fmb->dist_code' and "
            . " subdiv_code='$fmb->subdiv_code' and cir_code='$fmb->cir_code' and mouza_pargona_code='$fmb->mouza_pargona_code' and"
            . " lot_no='$fmb->lot_no' and vill_townprt_code='$fmb->vill_townprt_code' and patta_type_code='$dag->patta_type_code' and TRIM(patta_no)=trim('$dag->patta_no')")->row()->cp;

            $pattadars_in_jama_pattadar = $this->db->query("select max(pdar_id::int)+1 as jp from jama_pattadar where dist_code='$fmb->dist_code' and "
            . " subdiv_code='$fmb->subdiv_code' and cir_code='$fmb->cir_code' and mouza_pargona_code='$fmb->mouza_pargona_code' and"
            . " lot_no='$fmb->lot_no' and vill_townprt_code='$fmb->vill_townprt_code' and patta_type_code='$dag->patta_type_code' and TRIM(patta_no)=trim('$dag->patta_no')")->row()->jp;
            $pattadars_in_chithaDag_pattadar = $this->db->query("select max(pdar_id::int)+1 as dp from chitha_dag_pattadar where dist_code='$fmb->dist_code' and "
            . " subdiv_code='$fmb->subdiv_code' and cir_code='$fmb->cir_code' and mouza_pargona_code='$fmb->mouza_pargona_code' and"
            . " lot_no='$fmb->lot_no' and vill_townprt_code='$fmb->vill_townprt_code' and patta_type_code='$dag->patta_type_code' and TRIM(patta_no)=trim('$dag->patta_no') and dag_no='$dag->dag_no'")->row()->dp;

            $pdar_id = null;
            if($pattadars_in_chitha_pattadar > $pattadars_in_jama_pattadar){
                if($pattadars_in_chithaDag_pattadar > $pattadars_in_chitha_pattadar){
                    $pdar_id= $pattadars_in_chithaDag_pattadar;
                }else{
                    $pdar_id= $pattadars_in_chitha_pattadar;
                }
            }elseif($pattadars_in_chithaDag_pattadar > $pattadars_in_jama_pattadar){
                $pdar_id = $pattadars_in_chithaDag_pattadar;
            }else{
                $pdar_id = $pattadars_in_jama_pattadar;
            }
            if($pdar_id === null){
                $pdar_id = 1;
            }


            $tchithacol8order=array(
                'dist_code'=>$fmb->dist_code,
                'subdiv_code' =>$fmb->subdiv_code,
                'cir_code' =>$fmb->cir_code,
                'mouza_pargona_code' =>$fmb->mouza_pargona_code,
                'lot_no' =>$fmb->lot_no,
                'vill_townprt_code'=>$fmb->vill_townprt_code,
                'dag_no'=>$dag->dag_no,
                'year_no'=>date('Y'),
                'petition_no'=>$fmb->petition_no,
                'order_pass_yn'=>'y',
                'order_type_code'=>$fmb->mut_type,
                'nature_trans_code' =>$fmb->trans_code,
                'lm_code' =>$fmb->user_code,
                'lm_sign_yn' =>'y',
                'lm_note_date' =>$fmb->date_entry,
                'co_code' =>$tokenData->usercode,
                'co_sign_yn' =>'y',
                'co_ord_date' =>date('Y-m-d'),
                'date_of_order' =>date('Y-m-d'),
                'mut_land_area_b'=>$dag->m_dag_area_b,
                'mut_land_area_k'=>$dag->m_dag_area_k,
                'mut_land_area_lc' =>$dag->m_dag_area_lc,
                'mut_land_area_g'=>$dag->m_dag_area_g,
                'mut_land_area_kr' =>$dag->m_dag_area_kr,
                'land_area_left_b' =>0,
                'land_area_left_k' =>0,
                'land_area_left_lc' =>0,
                'land_area_left_g' =>0,
                'land_area_left_kr' =>0,
                'rajah_adalat'=>$fmb->rajah_adalat,
                'deed_reg_no' =>$fmb->reg_deed_no,
                'deed_value' =>$fmb->deed_value,
                'deed_date' =>$fmb->reg_deed_date,
                'sk_code' =>$fmb->sk_id,
                'sk_sign_yn' =>$fmb->sk_id != null ? 'y' : '',
                'sk_note_date' =>$fmb->sk_note_date,
                'case_no' =>$fmb->case_no,
                'min_revenue' =>'15.00',
                'noc_no' =>$fmb->noc_no,
                'noc_date'=>$fmb->noc_date,
            );
            $tOrderStatus = $this->db->insert('t_chitha_col8_order', $tchithacol8order);
            if(!$tOrderStatus || $this->db->affected_rows() < 1) {
                $this->db->trans_rollback();
                log_message('error', '#ERRCOMUTORDER001 Could not insert into t_chitha_col8_order!');
                $this->output->set_status_header(500);
                echo json_encode([
                    'status' =>'n',
                    'msg' => '#ERRCOMUTORDER001 . Could not create order!'
                ]);
                return;
            }

            $applicants = $this->db->query("SELECT * FROM field_mut_petitioner WHERE case_no=?", [$case_no])->result();

            if(empty($applicants)) {
                $this->db->trans_rollback();
                log_message('error', '#ERRCOMUTORDER002 First party information not found!');
                $this->output->set_status_header(500);
                echo json_encode([
                    'status' =>'n',
                    'msg' => '#ERRCOMUTORDER002 . First party information not found!'
                ]);
                return;
            }
            $i=0;
            foreach ($applicants as $fmp) {
                $dec= null;
                if(isset($fmp->self_declaration) && $fmp->self_declaration != null){
                    $dec       = $fmp->self_declaration;
                }
                if($fmp->auth_type != null){
                    $auth_type = $fmp->auth_type;
                    $id_ref_no = $fmp->id_ref_no;
                    $photo     = null;
                }
                else {
                    $auth_type = null;
                    $id_ref_no = null;
                    $photo = null;
                }

                $tchithacol8occ = array(
                    'dist_code'=>$fmb->dist_code,
                    'subdiv_code' =>$fmb->subdiv_code,
                    'cir_code' =>$fmb->cir_code,
                    'mouza_pargona_code' =>$fmb->mouza_pargona_code,
                    'lot_no' =>$fmb->lot_no,
                    'vill_townprt_code'=>$fmb->vill_townprt_code,
                    'dag_no'=>$dag->dag_no,
                    'year_no'=>date('Y'),
                    'petition_no'=>$fmb->petition_no,
                    'occupant_id'=>++$i,
                    'patta_type_code'=>$dag->patta_type_code,
                    'patta_no'=>$dag->patta_no,
                    'pdar_id' => $fmp->pdar_id == null ? $pdar_id++ : $fmp->pdar_id,
                    'occupant_name'=>$fmp->pet_name,
                    'occupant_fmh_name' =>$fmp->guard_name,
                    'occupant_fmh_flag' =>$fmp->guard_rel,
                    'occupant_add1' => isset($fmp->add1) ? $fmp->add1 : null,
                    'occupant_add2' =>isset($fmp->add2) ? $fmp->add2 : null,
                    'land_area_b' => $dag->m_dag_area_b == null ? 0 : $dag->m_dag_area_b,
                    'land_area_k' => $dag->m_dag_area_k == null ? 0 : $dag->m_dag_area_k,
                    'land_area_lc' => $dag->m_dag_area_lc == null ? 0 : $dag->m_dag_area_lc,
                    'land_area_g' => $dag->m_dag_area_g == null ? 0 : $dag->m_dag_area_g,
                    'land_area_kr' => $dag->m_dag_area_kr == null ? 0 : $dag->m_dag_area_kr,
                    'order_passed' =>'y',
                    'new_pattadar'=>$fmp->new_pet_name,
                    'hus_wife'=>$fmp->hus_wife,
                    'occup_gender'=>$fmp->pet_gender,
                    'occup_minor_yn'=>$fmp->pet_minor_yn,
                    'occup_minor_dob'=>$fmp->pet_minor_dob,
                    'occup_mother'=>$fmp->pet_mother,
                    'self_declaration' => $dec,
                    'auth_type' => $auth_type,
                    'id_ref_no'=> $id_ref_no,
                    'photo'=> $photo,
                    'pdar_name_eng'=>$fmp->pdar_name_eng,
                    'pdar_guard_eng'=>$fmp->pdar_guard_eng
                );
                            
                $tOccStatus = $this->db->insert('t_chitha_col8_occup', $tchithacol8occ);
                if(!$tOccStatus || $this->db->affected_rows() < 1) {
                    $this->db->trans_rollback();
                    log_message('error', '#ERRCOMUTORDER003 Error in inserting into t_chitha_col8_occup!');
                    $this->output->set_status_header(500);
                    echo json_encode([
                        'status' =>'n',
                        'msg' => '#ERRCOMUTORDER003 . Could not create order!'
                    ]);
                    return;
                }
            }
        }

        $j = 0;

        $pattadars = $this->db->query("SELECT * FROM field_mut_pattadar WHERE case_no=?", [$case_no])->result();

        if(empty($pattadars)) {
            $this->db->trans_rollback();
            log_message('error', '#ERRCOMUTORDER004 pattadar details not found!');
            $this->output->set_status_header(500);
            echo json_encode([
                'status' =>'n',
                'msg' => '#ERRCOMUTORDER004 . Could not create order!'
            ]);
            return;
        }

        foreach ($pattadars as $inplace) {
            $t_chitha_col8_inplace = array(
                'dist_code'=>$fmb->dist_code,
                'subdiv_code' =>$fmb->subdiv_code,
                'cir_code' =>$fmb->cir_code,
                'mouza_pargona_code' =>$fmb->mouza_pargona_code,
                'lot_no' =>$fmb->lot_no,
                'vill_townprt_code'=>$fmb->vill_townprt_code,
                // 'dag_no'=>$dag->dag_no,
                'dag_no'=>$inplace->dag_no,
                'year_no'=>date('Y'),
                'petition_no'=>$fmb->petition_no,
                'pdar_id'=>$inplace->pdar_id,
                'inplace_of_id'=>++$j,
                'inplace_of_name'=>$inplace->pdar_name,
                'land_area_b'=>0,
                'land_area_k'=>0,
                'land_area_lc' =>0,
                'land_area_g' =>0,
                'land_area_kr' =>0,
                'order_passed' =>'y',
                //'date_of_order' =>date('Y-m-d'),
                'fmute_strike_out'=>$inplace->striked_out,
                'inplace_of_gender'=>$inplace->pdar_gender,
                'inplace_of_minor_yn'=>$inplace->pdar_minor_yn,
                'inplace_of_minor_dob'=>$inplace->pdar_minor_dob,
                'inplace_of_father' =>$inplace->pdar_guardian,
                'inplace_of_mother' =>$inplace->pdar_mother,
            );
            $tInplaceStatus = $this->db->insert('t_chitha_col8_inplace', $t_chitha_col8_inplace);
            if(!$tInplaceStatus) {
                $this->db->trans_rollback();
                log_message('error', '#ERRCOMUTORDER005 could not insert into t_chitha_col8_inplace!');
                $this->output->set_status_header(500);
                echo json_encode([
                    'status' =>'n',
                    'msg' => '#ERRCOMUTORDER005 . Could not create order!'
                ]);
                return;
            }
        }


        foreach($dags as $dag) {
            $autoUpdateStatus = $this->MutationDataModel->autoUpdateForField($fmb->dist_code, $fmb->subdiv_code, $fmb->cir_code, $fmb->mouza_pargona_code, $fmb->lot_no, $fmb->vill_townprt_code, $fmb->petition_no, $dag->dag_no, $tokenData->usercode); 

            if(!$autoUpdateStatus || $autoUpdateStatus['status'] == 'n') {
                $this->db->trans_rollback();
                log_message('error', '#ERRCOMUTORDER027 could not autoupdate for field mutation!');
                $this->output->set_status_header(500);
                echo json_encode([
                    'status' =>'n',
                    'msg' => '#ERRCOMUTORDER027 . Could not create order!'
                ]);
                return;
            }
        }

        $order_date = date('Y-m-d');
        $q = "update field_mut_basic set order_passed='y',date_of_order='$order_date' where case_no='$fmb->case_no' ";
        $this->db->query($q);
        if ($this->db->affected_rows() < 1)
        {
            $this->db->trans_rollback();
            log_message('error', '#ERRCOMUTORDER028 Order for Case No ' . $fmb->case_no . ' Not Successfull. Error Code(#FMBFINAL001)!');
            $this->output->set_status_header(500);
            echo json_encode([
                'status' =>'n',
                'msg' => '#ERRCOMUTORDER028 . Could not create order!'
            ]);
            return;
        }
        $q = "update t_chitha_col8_order set order_passed='y',date_of_order='$order_date' where case_no='$fmb->case_no' ";
        $this->db->query($q);
        if ($this->db->affected_rows() < 1)
        {
            $this->db->trans_rollback();
            log_message('error', '#ERRCOMUTORDER029 Order for Case No ' . $fmb->case_no . ' Not Successfull. Error Code(#FMBFINAL002)!');
            $this->output->set_status_header(500);
            echo json_encode([
                'status' =>'n',
                'msg' => '#ERRCOMUTORDER029 . Could not create order!'
            ]);
            return;
        }
        

        $rmrk='CO order';

        $proInsert = $this->MutationDataModel->proceeding_order($tokenData, $case_no, $rmrk);


        if($proInsert['status'] == 'n') {
            $this->db->trans_rollback();
            log_message('error', '#ERRCOMUTORDER031 Proceeding Updation failed(#OMUTCOFM001)'.$case_no);
            $this->output->set_status_header(500);
            echo json_encode([
                'status' =>'n',
                'msg' => '#ERRCOMUTORDER031 . Could not create order!'
            ]);
            return;
        }

        if(!$this->db->trans_status()) {
            $this->db->trans_rollback();
            log_message('error', '#ERRCOMUTORDER032 DB Updation Failed.');
            $this->output->set_status_header(500);
            echo json_encode([
                'status' =>'n',
                'msg' => '#ERRCOMUTORDER032 . Could not create order!'
            ]);
            return;
        }

        $this->db->trans_commit();

        $this->output->set_status_header(200);
        echo json_encode([
            'status' =>'y',
            'msg' => 'Successfully created final order!'
        ]);
        return;
        
        // echo '<pre>';
        // var_dump($proInsert);
        // $this->db->trans_rollback();
        // die;

        

        
    }

    // public function submitApplicant() {
    //     $tokenData = $this->jwt_data;
    //     $dist_code = $tokenData->dcode;
        
    //     $data = json_decode(file_get_contents('php://input', true));

    //     if(!$data) {
    //         $this->output->set_status_header(401);
    //         echo json_encode([
    //             'status' =>'n',
    //             'msg' => 'Input Data not set!'
    //         ]);
    //         return;
    //     }

    //     if(!$data->vill_code || !$data->patta_type_code || !$data->patta_no || !$data->dag_nos || count($data->dag_nos) < 1 || !$data->applicant_name || $data->applicant_name == '' || !$data->applicant_guard_name || $data->applicant_guard_name == '' || !$data->applicant_guard_rel || $data->applicant_guard_rel == '' || !$data->applicant_gender || $data->applicant_gender == '' || !$data->applicant_mobile || $data->applicant_mobile == '') {
    //         $this->output->set_status_header(401);
    //         echo json_encode([
    //             'status' =>'n',
    //             'msg' => 'Input data not found!'
    //         ]);
    //         return;
    //     }

    //     $villCode = $data->vill_code;
    //     $patta_type_code = $data->patta_type_code;
    //     $patta_no = $data->patta_no;
    //     $dag_nos = $data->dag_nos;
    //     $applicant_name = $data->applicant_name;
    //     $applicant_guard_name = $data->applicant_guard_name;
    //     $applicant_guard_rel = $data->applicant_guard_rel;
    //     $applicant_gender = $data->applicant_gender;
    //     $applicant_mobile = $data->applicant_mobile;


        



    //     echo '<pre>';
    //     var_dump($data);
    //     die;

    // }

    


}

