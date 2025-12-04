<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class PartitionController extends CI_Controller
{
    use CommonTrait;
    protected $tokenData;

    public function __construct()
    {
        parent::__construct();

        authCheck();
        $this->tokenData = authData();

        // switch another DB
        $this->dbswitch($this->tokenData->dcode);

        $this->load->model('Api/Co/PartitionModel', 'pm');
    }

    /**
     * Generate API Response
     * @param mixed $result
     * @return void
     */
    protected function generateResponse($result, $msg = null)
    {
        $response = [
            'status' => !empty($result) ? 'Y' : 'N',
            'msg' => !empty($result) ? 'Successful' : ($msg ?? 'No records found'),
            'data' => $result
        ];

        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($response));
    }

    protected function generateFunRespData($status, $msg = null)
    {
        return $response = [
            'status' => $status,
            'msg' => $msg
        ];
    }



    private function refinedPartitionList($data)
    {
        $newData = null;

        if (isset($data)) {
            foreach ($data as $row) {
                $row['district_name'] = trim($this->utilityclass->getDistrictName($row['dist_code']));

                $row['subdiv_name'] = trim($this->utilityclass->getSubDivName($row['dist_code'], $row['subdiv_code']));

                $row['cir_name'] = trim($this->utilityclass->getCircleName($row['dist_code'], $row['subdiv_code'], $row['cir_code']));

                $row['mouza_name'] = trim($this->utilityclass->getMouzaName($row['dist_code'], $row['subdiv_code'], $row['cir_code'], $row['mouza_pargona_code']));


                $row['village_name'] = trim($this->utilityclass->getVillageName($row['dist_code'], $row['subdiv_code'], $row['cir_code'], $row['mouza_pargona_code'], $row['lot_no'], $row['vill_townprt_code']));

                $newData[] = $row;
            }

            return $newData;
        }

        return null;
    }

    function getPartitionList()
    {
        $result = $this->pm->getPartitionList($this->tokenData);

        $result = $this->refinedPartitionList($result);

        if (!empty($result)) {
            // Success Response
            return $this->generateResponse($result);
        }

        // Failure Response
        return $this->generateResponse(null);
    }

    /**
     * Get Case Details
     */
    function getCaseDetails()
    {
        $input = json_decode(file_get_contents('php://input')); // get raw JSON input

        if (isset($input)) {
            $data['user_code'] = $this->tokenData->usercode;
            $data['dist_code'] = $this->tokenData->dcode;
            $data['subdiv_code'] = $this->tokenData->subdiv_code;
            $data['cir_code'] = $this->tokenData->cir_code;
            $data['user_desig_code'] = $this->tokenData->user_desig_code;
            $data['case_num'] = $input->case_num;

            $data['co_name'] = $this->utilityclass->getCOName($data['dist_code'], $data['subdiv_code'], $data['cir_code'], $data['user_code'])[0]->username;

            $resultFmbDetails = $this->pm->getFieldMutBasicDetails($data);

            $data['mouza_pargona_code'] = $resultFmbDetails->mouza_pargona_code;
            $data['vill_townprt_code'] = $resultFmbDetails->vill_townprt_code;
            $data['lot_no'] = $resultFmbDetails->lot_no;
            $data['case_no'] = $resultFmbDetails->case_no;

            $resultFieldMutDagDetails = $this->pm->fieldMutDagDetails($data);

            $data['date_entry'] = $resultFmbDetails->date_entry;
            $data['user_code'] = $resultFmbDetails->user_code;
            $data['petition_no'] = $resultFmbDetails->petition_no;

            $data['lra_name'] = $this->utilityclass->getDefinedMondalsName($data['dist_code'], $data['subdiv_code'], $data['cir_code'], $data['mouza_pargona_code'], $data['lot_no'], $data['user_code'])->lm_name ?? null;

            $data['dag_no'] = $resultFieldMutDagDetails->dag_no;
            $data['total_dag_area_b'] = $resultFieldMutDagDetails->dag_area_b;
            $data['total_dag_area_k'] = $resultFieldMutDagDetails->dag_area_k;
            $data['total_dag_area_lc'] = $resultFieldMutDagDetails->dag_area_lc;

            $data['m_dag_area_b'] = $resultFieldMutDagDetails->m_dag_area_b;
            $data['m_dag_area_k'] = $resultFieldMutDagDetails->m_dag_area_k;
            $data['m_dag_area_lc'] = $resultFieldMutDagDetails->m_dag_area_lc;

            $data['patta_no'] = $resultFieldMutDagDetails->patta_no;
            $data['patta_type_code'] = $resultFieldMutDagDetails->patta_type_code;

            $data['patta_type'] = $this->utilityclass->getPattaName($data['patta_type_code']);

            $data['lra_note'] = $resultFieldMutDagDetails->remark;


            $fieldPartPetitionerDetails = $this->pm->fieldPartPetitionerDetails($data);
            $chithaBasicDetails = $this->pm->getChithaBasicDetails($data);

            $data['existing_dag_list'] = $this->pm->getDagNumbersOfParticularPatta($data);
            $data['existing_patta_no_list'] = $this->pm->getSuggestedPattaNumbers($data);

            $data['dag_revenue'] = $resultFmbDetails->min_revenue ?? 0;

            $dagLocalTax = $resultFmbDetails->min_revenue / 4;
            $dagLocalTax = ($dagLocalTax == null) ? "3.5" : (($dagLocalTax <= 3.5) ? "15" : $dagLocalTax);
            $data['dag_local_tax'] = $dagLocalTax;

            $fieldPartPetitionerArray = null;

            $this->load->library('UtilityClass');

            foreach ($fieldPartPetitionerDetails as $rs) {

                $temp = array(
                    'pdar_id' => $rs->pdar_id,
                    'pdar_name' => $rs->pdar_name,
                    'pdar_guardian' => $rs->pdar_guardian,
                    'pdar_rel_guar' => $this->utilityclass->get_relation($rs->pdar_rel_guar),
                    'pdar_dag_por_b' => $rs->pdar_dag_por_b,
                    'pdar_dag_por_k' => $rs->pdar_dag_por_k,
                    'pdar_dag_por_lc' => $rs->pdar_dag_por_lc,
                    'pdar_dag_por_g' => $rs->pdar_dag_por_g,
                );
                $fieldPartPetitionerArray[] = $temp;
            }

            $data['applicant_list'] = $fieldPartPetitionerArray;

            $data['checkPattaApplicant'] = $this->pm->checkPattaApplicant($data);
            $data['supportive_docs'] = $this->pm->getCaseSupportiveDocs(['case_no' => $data['case_no']]);

            return $this->generateResponse($data);
        }

        return $this->generateResponse(null);
    }

    /**
     * Final Procee of CO submitting Partition Form.
     */
    function savePartitionForm()
    {
        $input = json_decode(file_get_contents('php://input')); // get raw JSON input

        if (!isset($input)) {
            return $this->generateResponse(null);
        }

        if ($this->tokenData->user_desig_code != 'CO') {
            return $this->generateResponse(null, "Unauthorized!");
        }

        $data['user_code'] = $this->tokenData->usercode;
        $data['dist_code'] = $this->tokenData->dcode;
        $data['subdiv_code'] = $this->tokenData->subdiv_code;
        $data['cir_code'] = $this->tokenData->cir_code;
        $data['user_desig_code'] = $this->tokenData->user_desig_code;
        $data['case_no'] = $data['case_num'] = $input->case_no;
        $data['remarks'] = $input->remarks;
        $data['sugg_dag_no'] = $input->nextDagNo;
        $data['sugg_patta_no'] = $input->nextPattaNo;

        $resultFmbDetails = $this->pm->getFieldMutBasicDetails($data);

        $data['mouza_pargona_code'] = $resultFmbDetails->mouza_pargona_code;
        $data['vill_townprt_code'] = $resultFmbDetails->vill_townprt_code;
        $data['lot_no'] = $resultFmbDetails->lot_no;
        $data['date_entry'] = $resultFmbDetails->date_entry;
        $data['lm_code'] = $resultFmbDetails->user_code;
        $data['lm_date'] = $resultFmbDetails->date_entry;
        $data['petition_no'] = $resultFmbDetails->petition_no;

        //todo
        // $this->AgriStackCaseHistory->CreateLogFile($dist_code, $case_no);
        $resultFieldMutDagDetails = $this->pm->fieldMutDagDetails($data);

        $data['dag_no'] = $resultFieldMutDagDetails->dag_no;
        $data['patta_no'] = $resultFieldMutDagDetails->patta_no;
        $data['patta_type_code'] = $resultFieldMutDagDetails->patta_type_code;

        $data['bigha_applied'] = $resultFieldMutDagDetails->m_dag_area_b;
        $data['katha_applied'] = $resultFieldMutDagDetails->m_dag_area_k;
        $data['lessa_applied'] = $resultFieldMutDagDetails->m_dag_area_lc;
        $data['genda_applied'] = $resultFieldMutDagDetails->m_dag_area_g;

        $chithaBasicDetails = $this->pm->getChithaBasicDetails($data);

        $data['dag_revenue'] = $chithaBasicDetails->dag_revenue;

        $dagLocalTax = $chithaBasicDetails->dag_local_tax;
        $dagLocalTax = ($dagLocalTax == null) ? "3.5" : (($dagLocalTax <= 3.5) ? "15" : $dagLocalTax);
        $data['dag_local_tax'] = $dagLocalTax;

        $data['land_area_left_b'] = $resultFieldMutDagDetails->dag_area_b;
        $data['land_area_left_k'] = $resultFieldMutDagDetails->dag_area_k;
        $data['land_area_left_lc'] = $resultFieldMutDagDetails->dag_area_lc;

        $this->db->trans_begin();

        #------------------------------------------------------------------------#
        # Petitioner details retrival from "field_part_petitioner" table.
        #------------------------------------------------------------------------#
        $occup_data = $this->db->select('*')
            ->from('field_part_petitioner')
            ->where('case_no', $data['case_no'])
            ->where('mouza_pargona_code', $data['mouza_pargona_code'])
            ->where('lot_no', $data['lot_no'])
            ->where('vill_townprt_code', $data['vill_townprt_code'])
            ->where('dist_code', $data['dist_code'])
            ->where('subdiv_code', $data['subdiv_code'])
            ->where('cir_code', $data['cir_code'])
            ->get();

        if ($occup_data == null || $occup_data->num_rows() <= 0) {

            $this->db->trans_rollback();

            log_message("error", "#ERRFP002: No Petitioner found in field_part_petitioner 
                        for dist:" . $data['dist_code'] . ", case: " . $data['case_no']);

            return $this->generateResponse(null, "Could not get petitioner details. Error Code(#ERRFP002)");
        }

        $occup_data = $occup_data->result();

        #------------------------------------------------------------------------#
        # "field_mut_basic" data retrival, to check whether new DAG Number 
        # is already EXISTS or not.
        #------------------------------------------------------------------------#
        $get_mut_type = $this->db->select('*')
            ->from('field_mut_basic')
            ->where('case_no', $data['case_no'])
            ->where('mouza_pargona_code', $data['mouza_pargona_code'])
            ->where('lot_no', $data['lot_no'])
            ->where('vill_townprt_code', $data['vill_townprt_code'])
            ->where('dist_code', $data['dist_code'])
            ->where('subdiv_code', $data['subdiv_code'])
            ->where('cir_code', $data['cir_code'])
            ->get()
            ->row();

        $oldDagNumber = null;
        $pattaTypeCode = null;

        //Field Partition (mut_type=2 => filed partition)
        if ($resultFmbDetails->mut_type == '02') {

            $new_pattadar = 'N';

            $dd = $this->db->select('patta_type_code, dag_no')
                ->from('field_mut_dag_details')
                ->where('case_no', $data['case_no'])
                ->get();

            if ($dd == null || $dd->num_rows() <= 0) {
                $this->db->trans_rollback();

                log_message("error", "#ERRFP003: No petitioner found in field_mut_dag_details for dist:" . $data['dist_code'] . ", case: " . $data['case_no']);

                return $this->generateResponse(null, "Could not get petitioner details. Error Code(#ERRFP003)");
            }

            $dd = $dd->row();       // get the first row
            $pp_code = $dd->patta_type_code;
            $old = $dd->dag_no;

            $oldDagNumber = $old;
            $pattaTypeCode = $pp_code;

            # compare new and old dag number in chetha_basic.
            $count = $this->db->select('COUNT(*) AS d')
                ->from('chitha_basic')
                ->where('mouza_pargona_code', $data['mouza_pargona_code'])
                ->where('lot_no', $data['lot_no'])
                ->where('vill_townprt_code', $data['vill_townprt_code'])
                ->where('dist_code', $data['dist_code'])
                ->where('subdiv_code', $data['subdiv_code'])
                ->where('cir_code', $data['cir_code'])
                ->where('dag_no !=', $old)
                ->where('dag_no', '=', $data['sugg_dag_no'])
                ->get()
                ->row()->d;

            if ($count != null && $count > 0) {
                $this->db->trans_rollback();

                log_message("error", "#ERRFP004: The Dag no you have given already exist !, Dag No: " . $data['sugg_dag_no'] . ", case: " . $data['case_no']);

                return $this->generateResponse(null, "The Dag no: " . $data['sugg_dag_no'] . " you have given already exist ! Please re-verify the dag no again (#ERRFP004)");
            }

        } else {
            $new_pattadar = '';
        }


        #----------------------------------------------------------------------------#
        # "field_part_petitioner" data retrival & Loop through "field_part_petitioner" 
        # to insert data from "field_part_petitioner" to "t_chitha_col8_occup". 
        #----------------------------------------------------------------------------#
        foreach ($occup_data as $occup):

            if (in_array($data['dist_code'], BARAK_VALLEY)) {
                $occup_ganda = $occup->pdar_dag_por_g;
            } else {
                $occup_ganda = '0';
                $ganda = '0';
            }
            $dec = null;
            if (isset($occup->self_declaration) && $occup->self_declaration != null) {
                $dec = $occup->self_declaration;
            }
            if ($occup->auth_type != null) {
                $auth_type = $occup->auth_type;
                $id_ref_no = $occup->id_ref_no;
                $photo = null;
            } else {
                $auth_type = null;
                $id_ref_no = null;
                $photo = null;
            }

            # ----------------- Prepare Data For "t_chitha_col8_occup" Table --------------------------
            $t_chitha_col8_occup = array(
                'dist_code' => $occup->dist_code,
                'subdiv_code' => $occup->subdiv_code,
                'cir_code' => $occup->cir_code,
                'mouza_pargona_code' => $occup->mouza_pargona_code,
                'lot_no' => $occup->lot_no,
                'vill_townprt_code' => $occup->vill_townprt_code,
                'dag_no' => $occup->dag_no,//$new_dag, //should be the new dag
                'year_no' => $occup->year_no,
                'petition_no' => $occup->petition_no,
                'occupant_id' => $occup->pdar_cron_no,
                'patta_type_code' => $occup->patta_type_code,
                'patta_no' => $occup->patta_no,//$new_patta,  //should be the new patta no
                'pdar_id' => $occup->pdar_id,
                'occupant_name' => $occup->pdar_name,
                'occupant_fmh_name' => $occup->pdar_guardian,
                'occupant_fmh_flag' => $occup->pdar_rel_guar,
                'occupant_add1' => $occup->pdar_add1,
                'occupant_add2' => $occup->pdar_add2,
                'land_area_b' => $occup->pdar_dag_por_b,
                'land_area_k' => $occup->pdar_dag_por_k,
                'land_area_lc' => $occup->pdar_dag_por_lc,
                'land_area_g' => $occup_ganda,
                'land_area_kr' => '0',
                'old_patta_no' => $occup->patta_no,
                'new_patta_no' => $data['sugg_patta_no'],
                'old_dag_no' => $occup->dag_no,
                'new_dag_no' => $data['sugg_dag_no'],
                'new_pattadar' => $new_pattadar,
                'revenue' => $data['dag_revenue'],
                'self_declaration' => $dec,
                'auth_type' => $auth_type,
                'id_ref_no' => $id_ref_no,
                'photo' => $photo
            );
            $tstatus1 = $this->db->insert("t_chitha_col8_occup", $t_chitha_col8_occup);

            if ($tstatus1 != 1) {
                $this->db->trans_rollback();

                log_message("error", "#ERRFP006 Nill petition from t_chitha_col8_occup for dist:" . $data['dist_code'] . ", case: " . $data['case_no']);

                return $this->generateResponse(null, "Error in Processing. Please try Again. Error Code(#ERRFP006)");
            }

        endforeach;


        #------------------------------------------------------------------------#
        # Data Insert in "insert_t_chitha_col8_order" Table.
        #------------------------------------------------------------------------#
        # ----------------- Prepare Data For "insert_t_chitha_col8_order" Table ------------------
        $insert_t_chitha_col8_order = array(
            'dist_code' => $data['dist_code'],
            'subdiv_code' => $data['subdiv_code'],
            'cir_code' => $data['cir_code'],
            'mouza_pargona_code' => $data['mouza_pargona_code'],
            'lot_no' => $data['lot_no'],
            'vill_townprt_code' => $data['vill_townprt_code'],
            'dag_no' => $oldDagNumber,
            'year_no' => currentYear(),
            'petition_no' => $get_mut_type->petition_no,
            'order_pass_yn' => 'y',
            'order_type_code' => '02',
            'nature_trans_code' => $get_mut_type->trans_code,
            'lm_code' => $data['lm_code'],
            'lm_sign_yn' => 'y',
            'lm_note_date' => date('Y-m-d', strtotime($data['lm_date'])),
            'co_code' => $data['user_code'],
            'co_sign_yn' => 'y',
            'co_ord_date' => date('Y-m-d h:i:s'),
            'iscorrected_inco' => 'y',
            'iscorrected_inco_date' => date('Y-m-d h:i:s'),
            'mut_land_area_b' => $data['bigha_applied'],
            'mut_land_area_k' => $data['katha_applied'],
            'mut_land_area_lc' => $data['lessa_applied'],
            'mut_land_area_g' => $ganda ?? 0,
            'mut_land_area_kr' => '0',
            'land_area_left_b' => $data['land_area_left_b'],
            'land_area_left_k' => $data['land_area_left_k'],
            'land_area_left_lc' => $data['land_area_left_lc'],
            'land_area_left_g' => $ganda ?? 0,
            'land_area_left_kr' => '0',
            'rajah_adalat' => $get_mut_type->rajah_adalat,
            'case_no' => $data['case_no'],
            'min_revenue' => $data['dag_revenue'],
        );
        $instChithaCol8Order = $this->db->insert("t_chitha_col8_order", $insert_t_chitha_col8_order);

        if ($instChithaCol8Order != 1) {
            $this->db->trans_rollback();

            log_message("error", "#ERRFP009 unable to insert into t_chitha_col8_order for dist:" . ", case: " . $data['case_no']);

            return $this->generateResponse(null, "Error in Processing. Please try Again. Error Code(#ERRFP009)");
        }

        #-------------------------------------------------------------------------------------#
        # Load petitioner(s) from 'field_part_petitioner' joined with 'field_mut_dag_details'. 
        # Fail if none.
        #--------------------------------------------------------------------------------------#
        $this->db->select("
                        mp.dist_code, mp.subdiv_code, mp.cir_code, mp.mouza_pargona_code,
                        mp.lot_no, mp.vill_townprt_code, mp.pdar_id, mp.year_no, mp.petition_no, 
                        mp.pdar_add1, mp.pdar_add2, mp.pdar_name, mp.pdar_guardian, mp.pdar_rel_guar, 
                        dd.patta_no, dd.patta_type_code, mp.pdar_dag_por_b, mp.pdar_dag_por_k, 
                        mp.pdar_dag_por_lc, dd.dag_no
                    ");

        $this->db->from('field_part_petitioner mp');
        $this->db->join('field_mut_dag_details dd', 'mp.cir_code = dd.cir_code AND mp.case_no = dd.case_no', 'inner');

        $this->db->where('mp.case_no', $data['case_no']);
        $this->db->where('mp.cir_code', $data['cir_code']);
        $this->db->where('mp.subdiv_code', $data['subdiv_code']);
        $this->db->where('mp.mouza_pargona_code', $data['mouza_pargona_code']);
        $this->db->where('mp.lot_no', $data['lot_no']);
        $this->db->where('mp.vill_townprt_code', $data['vill_townprt_code']);

        $this->db->limit(1);

        $query = $this->db->get();

        if ($query->num_rows() <= 0) {
            $this->db->trans_rollback();

            log_message("error", "#ERRFP001: No Petitioner found in field_part_petitioner 
                for dist:" . $data['dist_code'] . ", case: " . $data['case_no']);

            return $this->generateResponse(null, "Could not get petitioner details. Error Code(#ERRFP001)");
        }

        $petitioner_save = $query->row();

        $dist_code = $petitioner_save->dist_code;
        $subdiv_code = $petitioner_save->subdiv_code;
        $cir_code = $petitioner_save->cir_code;
        $mouza_pargona_code = $petitioner_save->mouza_pargona_code;
        $lot_no = $petitioner_save->lot_no;
        $vill_townprt_code = $petitioner_save->vill_townprt_code;
        $dag_no = $petitioner_save->dag_no;
        $petition_no = $petitioner_save->petition_no;

        #------------------------------------------------------------------------#
        #       --------------- UPDATE JAMABANDI/ CHITHA -------------------
        #------------------------------------------------------------------------#
        # --------- IF OLD Dag And New Dag Number are same -------------------
        if ($occup->dag_no == $data['sugg_dag_no']) {

            #------------------------------------------------------------------------#
            #       --------------- AUTO UPDATE FULLDAG FIELD -------------------
            #------------------------------------------------------------------------#
            $ok = $this->autoUpdate_fulldag_field($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $petition_no, $dag_no, $data['user_code'], $data['case_no']);


            if ($ok['status'] == false) {
                return $this->generateResponse(null, $ok['msg']);
            }

            #------------------------------------------------------------------------#
            #       --------------- AUTO UPDATE FULLDAG FIELD -------------------
            #------------------------------------------------------------------------#
            $jamaStatus = $this->pm->jamaCheckToDeleteorNot(
                $data['dist_code'],
                $data['subdiv_code'],
                $data['cir_code'],
                $data['mouza_pargona_code'],
                $data['lot_no'],
                $data['vill_townprt_code'],
                $occup->dag_no,
                $data['patta_no'],
                $data['patta_type_code']
            );

            if ($jamaStatus['status'] == false) {
                return $this->generateResponse(null, $jamaStatus['msg']);
            }

            // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            //TODO ===============>>>> TEST DONE ==============
            // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            //todo.  NEED TO TEST CORRECTLY !!!
            // test($jamaStatus, 1);  

        } else {

            #------------------------------------------------------------------------#
            #       --------------- For New Dag Number: AUTO UPDATE CHITHA -------------------
            #------------------------------------------------------------------------#

            $ok = $this->autoUpdateForField($data['dist_code'], $data['subdiv_code'], $data['cir_code'], $data['mouza_pargona_code'], $data['lot_no'], $data['vill_townprt_code'], $data['petition_no'], $occup->dag_no, $data['user_code']);


            if ($ok != true) {
                $this->db->trans_rollback();

                log_message("error", "#ERRFP010 unable to update chitha from autoUpdate dist:" . $data['dist_code'] . ", case: " . $data['case_no']);

                return $this->generateResponse(null, "Chitha Could not be updated. Please try Again. Error Code(#ERRFP010)");
            }

            $order_date = date('Y-m-d');

            # ----------------------- Update field_mut_basic Table ----------------------#
            $this->db->query("UPDATE field_mut_basic SET order_passed=?, date_of_order=? 
                WHERE case_no=? ", array('y', $order_date, $data['case_no']));

            if ($this->db->affected_rows() <= 0) {
                $this->db->trans_rollback();

                log_message("error", "##ERRFP011 unable to update chitha from autoUpdate dist:" . $data['dist_code'] . ", case: " . $data['case_no']);

                return $this->generateResponse(null, "Chitha Could not be updated. Please try Again. Error Code(#ERRFP011)");
            }

            #------------------------------------------------------------------------#
            #       --------------- Update Chitha Temporary Table  -------------------
            # --------------------- Update t_chitha_col8_order Table ----------------#
            #------------------------------------------------------------------------#

            $this->db->query("UPDATE t_chitha_col8_order SET order_passed=?, 
                date_of_order=? WHERE case_no=? ", array('y', $order_date, $data['case_no']));

            if ($this->db->affected_rows() <= 0) {
                $this->db->trans_rollback();

                log_message("error", "##ERRFP012 unable to update chitha from autoUpdate dist:" . $data['dist_code'] . ", case: " . $data['case_no']);

                return $this->generateResponse(null, "Chitha Could not be updated. Please try Again. Error Code(#ERRFP012)");
            }
        }

        #----------------------------------------------------------------------------------------------#
        #       --------------- PROCEEDING ORDER (insert in petition_proceeding Table-------------------
        #----------------------------------------------------------------------------------------------#
        $rmrk = 'CO Order';


        // $proInsert = $this->mutationmodel->proceeding_order($data['case_no'], $rmrk);
        $proInsert = $this->pm->proceedingOrder($data, $rmrk);


        if ($proInsert == false || $proInsert === false) {
            $this->db->trans_rollback();

            log_message("error", "#OPARTCO001:" . $this->db->last_query());

            return $this->generateResponse(null, "Updation failed(#OPARTCO001)" . $data['case_no']);
        }


        #------------------------------------------------------------------------#
        #   --------------- COMMIT / ROLLBACK TRANSACTION -------------------
        #------------------------------------------------------------------------#
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();

            return $this->generateResponse(null, "Order Cannot be passed. Error Code [T-TABLE_HAS_DATA] . Contact help desk with case no. " . $data['case_no']);

        } else {
            // $this->db->trans_commit(); 

            return $this->generateResponse(true, "Order passed. Case Pending with mandal for parition Map Correction." . $data['case_no']);
        }

    }


    public function autoUpdateForField(
        $dist_code,
        $subdiv_code,
        $cir_code,
        $mouza_pargona_code,
        $lot_no,
        $vill_code,
        $petition_no,
        $dag_no,
        $user_code
    ) {
        //$db=  $this->session->userdata('db');
        $locationData = array(
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'lot_no' => $lot_no,
            'vill_code' => $vill_code,
            'mouza_pargona_code' => $mouza_pargona_code,
        );
        //var_dump($locationData);
        $year_no = year_no;

        $col8order_cron_no = $this->db->query("select max(col8order_cron_no)+1 as cron_no from   chitha_col8_order where dist_code='$dist_code' and subdiv_code='$subdiv_code' and "
            . "cir_code='$cir_code' and lot_no='$lot_no' and mouza_pargona_code='$mouza_pargona_code' and vill_townprt_code='$vill_code' and dag_no='$dag_no'")->row()->cron_no;
        // /echo $this->db->last_query(); return;
        if ($col8order_cron_no == null) {
            $col8order_cron_no = 1;
        }

        $t_order_data_query = "select * from   t_chitha_col8_order where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and lot_no='$lot_no' and "
            . "mouza_pargona_code='$mouza_pargona_code' and vill_townprt_code='$vill_code' and petition_no='$petition_no' and dag_no='$dag_no'";// and iscorrected_inco is null";
        $t_data_order = $this->db->query($t_order_data_query);

        if ($t_data_order == null || $t_data_order->num_rows() <= 0) {
            $this->db->trans_rollback();
            log_message("error", "#ERR001 No data found in t_chitha_col8_order with district: " . $dist_code . ", petition_no: " . $petition_no);
            return false;
        }
        $t_data_order = $t_data_order->result();
        //var_dump($t_data_order);
        $case_no = null;
        foreach ($t_data_order as $ord) {
            $case_no = $ord->case_no;
            $data_order = array();
            foreach ($ord as $key => $value) {
                $data[$key] = $value;
            }
            $data['col8order_cron_no'] = $col8order_cron_no;
            $data['user_code'] = $user_code;
            $data['date_entry'] = date('Y-m-d G:i:s');
            $data['operation'] = date('E');
            unset($data['year_no']);
            unset($data['petition_no']);
            unset($data['iscorrected_inco']);
            unset($data['iscorrected_inco_date']);
            unset($data['isdataposted_torkg_db']);
            unset($data['iscorrected_rkg_record']);
            unset($data['iscorrected_rkg_date']);
            unset($data['order_passed']);
            unset($data['date_of_order']);
            unset($data['make_mdb']);
            unset($data['date_of_order']);
            unset($data['not_consistent']);
            $corrected = date('Y-m-d G:i:s');
            $dataNew = $data;
            $tstatus1 = $this->db->insert("chitha_col8_order", $data); //************************************************************************************************ 
            // insert query
            if ($tstatus1 != 1) {
                $this->db->trans_rollback();
                log_message("error", " #ERR002 could not insert chitha_col8_order with district: " . $dist_code . ", petition_no: " . $petition_no);
                return false;
            }

            //Checking for occupents
            $t_occup_query = "select * from   t_chitha_col8_occup where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and lot_no='$lot_no' and "
                . "mouza_pargona_code='$mouza_pargona_code' and vill_townprt_code='$vill_code' and petition_no=$petition_no and dag_no='$dag_no'";// and iscorrected_inco is null";
            $t_occup_data = $this->db->query($t_occup_query);
            if ($t_occup_data == null || $t_occup_data->num_rows() <= 0) {
                $this->db->trans_rollback();
                log_message("error", "#ERR003 No data found in t_chitha_col8_occup with district: " . $dist_code . ", petition_no: " . $petition_no);
                return false;
            }
            $t_occup_data = $t_occup_data->result();

            //updating t_chitha_col8_order iscorrected_inco status
            // $update_query = "update t_chitha_col8_order  set iscorrected_inco='Y',iscorrected_inco_date='$corrected' where dist_code='$dist_code' and subdiv_code='$subdiv_code' and "
            //         . "cir_code='$cir_code' and lot_no='$lot_no' and mouza_pargona_code='$mouza_pargona_code' and vill_townprt_code='$vill_code' and petition_no=$petition_no and "
            //         . "dag_no='$dag_no' ";
            // $this->db->query($update_query); //
            // echo $this->db->last_query();
            // echo $this->db->affected_rows();
            // die();
            // if ($this->db->affected_rows()<=0 )
            // {
            //     $this->db->trans_rollback();
            //     log_message("error","#ERR004 Could not update iscorrected_inco in t_chitha_col8_order with district: ".$dist_code.", petition_no: ". $petition_no);
            //     return false;
            // }                                

            $chitha_basic_update = FALSE;
            // occupants details starts here
            foreach ($t_occup_data as $occ) {

                // $sql = "update chitha_basic set jama_yn=null where dist_code='$occ->dist_code' and subdiv_code='$occ->subdiv_code' and cir_code='$occ->cir_code' and "
                //         . "mouza_pargona_code='$occ->mouza_pargona_code' and lot_no='$occ->lot_no' and vill_townprt_code='$occ->vill_townprt_code' and dag_no='$occ->dag_no' "
                //         . "and TRIM(patta_no)=trim('$occ->patta_no') and patta_type_code='$occ->patta_type_code' ";
                // $this->db->query($sql); //************************************************************************************************ update query

                $table = 'chitha_basic';

                $params = [
                    'jama_yn' => null,
                ];

                $where = [
                    'dist_code' => $occ->dist_code,
                    'subdiv_code' => $occ->subdiv_code,
                    'cir_code' => $occ->cir_code,
                    'mouza_pargona_code' => $occ->mouza_pargona_code,
                    'lot_no' => $occ->lot_no,
                    'vill_townprt_code' => $occ->vill_townprt_code,
                    'dag_no' => $occ->dag_no,
                    'patta_no' => trim($occ->patta_no),
                    'patta_type_code' => $occ->patta_type_code,
                ];

                // Assuming your model has a method like update_table($table, $params, $where)
                $result4 = $this->pm->update_table($table, $params, $where);

                if ($result4 <= 0) {
                    $this->db->trans_rollback();
                    log_message("error", "#ERR005 Could not update jama_yn in chitha_basic with district: " . $dist_code . ", petition_no: " . $petition_no);
                    return false;
                }

                $data = array();
                foreach ($occ as $key => $value) {
                    $data[$key] = $value;
                }
                unset($data['year_no']);
                unset($data['petition_no']);
                unset($data['iscorrected_inco']);
                unset($data['iscorrected_inco_date']);
                unset($data['isdataposted_torkg_db']);
                unset($data['iscorrected_rkg_record']);
                unset($data['iscorrected_rkg_date']);
                unset($data['order_passed']);
                unset($data['date_of_order']);
                unset($data['make_mdb']);
                unset($data['date_of_order']);
                unset($data['patta_type_code']);
                unset($data['patta_no']);
                unset($data['pdar_id']);
                unset($data['revenue']);
                unset($data['new_pattadar']);
                $data['col8order_cron_no'] = $col8order_cron_no;
                $data['user_code'] = $user_code;
                $data['date_entry'] = date('Y-m-d G:i:s');
                $data['operation'] = date('E');
                $occupData = $data;
                //var_dump($data);

                $tstatus2 = $this->db->insert("chitha_col8_occup", $data); //************************************************************************************************ insert query
                if ($tstatus2 != 1) {
                    $this->db->trans_rollback();
                    log_message("error", "#ERR006 Could not insert in chitha_col8_occup with district: " . $dist_code . ", petition_no: " . $petition_no);
                    return false;
                }

                $dag_pattadar = array();
                $chitha_pattadar = array();

                $pdar_id = $occ->pdar_id;

                if ($ord->order_type_code == '02') {
                    // Order Type Code 02 iIs For Field Partition. and 01 is For Field Mutation
                    $pdar_id = $this->pm->maxpdarIdCheck($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code, $occ->patta_type_code, trim($occ->new_patta_no));
                }
                if ($pdar_id == null) {
                    $pdar_id = 1;
                }
                //echo $pdar_id;
                $dag_pattadar['dist_code'] = $dist_code;
                $dag_pattadar['subdiv_code'] = $subdiv_code;
                $dag_pattadar['cir_code'] = $cir_code;
                $dag_pattadar['lot_no'] = $lot_no;
                $dag_pattadar['mouza_pargona_code'] = $mouza_pargona_code;
                $dag_pattadar['vill_townprt_code'] = $vill_code;

                if ($ord->order_type_code == '02') {
                    $dag_pattadar['dag_no'] = $occ->new_dag_no;
                    $newDag = $this->utilityclass->maxdag($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code);
                    if ($newDag < $occ->new_dag_no) {
                        $this->db->trans_rollback();
                        log_message("error", "#ERR00001212 Kindly Check the New Dag no you have Assigned ($occ->new_dag_no) ####  " . $petition_no);
                        return false;
                    }
                } else {
                    $dag_pattadar['dag_no'] = $dag_no;
                }
                if (($occ->pdar_id) && (!($ord->order_type_code == '02'))) {
                    $dag_pattadar['pdar_id'] = $occ->pdar_id;
                } else {
                    $dag_pattadar['pdar_id'] = $pdar_id;
                }

                if ($ord->order_type_code == '02') {
                    $dag_pattadar['patta_no'] = trim($occ->new_patta_no);
                    $chitha_pattadar['patta_no'] = trim($occ->new_patta_no);
                } else {
                    $dag_pattadar['patta_no'] = trim($occ->patta_no);
                    $chitha_pattadar['patta_no'] = trim($occ->patta_no);
                }
                $dag_pattadar['p_flag'] = '0';
                $dag_pattadar['patta_type_code'] = $occ->patta_type_code;
                $dag_pattadar['dag_por_b'] = $occ->land_area_b;
                $dag_pattadar['dag_por_k'] = $occ->land_area_k;
                $dag_pattadar['dag_por_lc'] = $occ->land_area_lc;
                $dag_pattadar['dag_por_g'] = $occ->land_area_g;
                $dag_pattadar['dag_por_kr'] = $occ->land_area_kr;

                $dag_pattadar['user_code'] = $user_code;
                $dag_pattadar['date_entry'] = date('Y-m-d G:i:s');
                $dag_pattadar['operation'] = date('E');

                $chitha_pattadar['dist_code'] = $dist_code;
                $chitha_pattadar['subdiv_code'] = $subdiv_code;
                $chitha_pattadar['cir_code'] = $cir_code;
                $chitha_pattadar['lot_no'] = $lot_no;
                $chitha_pattadar['mouza_pargona_code'] = $mouza_pargona_code;
                $chitha_pattadar['vill_townprt_code'] = $vill_code;

                $chitha_pattadar['pdar_id'] = $pdar_id;
                $chitha_pattadar['new_pdar_name'] = $occ->new_pattadar;
                $chitha_pattadar['patta_type_code'] = $occ->patta_type_code;
                $chitha_pattadar['pdar_name'] = $occ->occupant_name;
                $chitha_pattadar['pdar_father'] = $occ->occupant_fmh_name;
                $chitha_pattadar['pdar_add1'] = $occ->occupant_add1;
                $chitha_pattadar['pdar_add2'] = $occ->occupant_add2;
                $chitha_pattadar['pdar_add3'] = $occ->occupant_add3;
                $chitha_pattadar['pdar_name'] = $occ->occupant_name;
                $chitha_pattadar['pdar_name'] = $occ->occupant_name;
                $chitha_pattadar['pdar_name'] = $occ->occupant_name;
                $chitha_pattadar['pdar_guard_reln'] = $occ->occupant_fmh_flag;
                $chitha_pattadar['user_code'] = $user_code;
                $chitha_pattadar['date_entry'] = date('Y-m-d G:i:s');
                $chitha_pattadar['operation'] = date('E');
                $chitha_pattadar['jama_yn'] = 'N';
                //newly added aadhaar details to chitha pattadar----
                $flagAadhaar = null;
                $flagPan = null;
                if ($occ->auth_type == 'AADHAAR') {
                    $chitha_pattadar['pdar_aadharno'] = $occ->id_ref_no;
                    $flagAadhaar = $occ->id_ref_no;
                    $flagPan = null;
                } else if ($occ->auth_type == 'PAN') {
                    $chitha_pattadar['pdar_pan_no'] = $occ->id_ref_no;
                    $flagAadhaar = null;
                    $flagPan = $occ->id_ref_no;
                }
                // $chitha_pattadar['pdar_photo'] = $occ->photo;
                //end-----------


                $chitha_basic_query = "select land_class_code from   chitha_basic where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and lot_no='$lot_no' "
                    . "and mouza_pargona_code='$mouza_pargona_code' and TRIM(patta_no)=trim('$occ->patta_no') and patta_type_code='$occ->patta_type_code' and dag_no='$dag_no'";
                $result = $this->db->query($chitha_basic_query)->row();

                $chitha_basic = array();
                $chitha_basic['dist_code'] = $dist_code;
                $chitha_basic['subdiv_code'] = $subdiv_code;
                $chitha_basic['cir_code'] = $cir_code;
                $chitha_basic['mouza_pargona_code'] = $mouza_pargona_code;
                $chitha_basic['lot_no'] = $lot_no;
                $chitha_basic['vill_townprt_code'] = $vill_code;
                $chitha_basic['dag_area_b'] = $ord->mut_land_area_b;
                $chitha_basic['dag_area_k'] = $ord->mut_land_area_k;
                $chitha_basic['dag_area_lc'] = $ord->mut_land_area_lc;
                $chitha_basic['dag_area_g'] = $ord->mut_land_area_g;
                $chitha_basic['dag_area_kr'] = $ord->mut_land_area_kr;
                $chitha_basic['user_code'] = $user_code;
                $chitha_basic['date_entry'] = date('Y-m-d G:i:s');
                $chitha_basic['land_class_code'] = $result->land_class_code;

                //Partition to new dag
                if ($ord->order_type_code == '02') {
                    $old_dag = $dag_no;
                    $chitha_basic['dag_no'] = $occ->new_dag_no;
                    $chitha_basic['dag_no_int'] = $occ->new_dag_no . '00';
                    $chitha_basic['old_dag_no '] = $dag_no;
                    $old_patta = trim($occ->old_patta_no);
                    $chitha_basic['patta_no'] = trim($occ->new_patta_no);

                    $q = "update chitha_col8_order set new_dag_no='$occ->new_dag_no' where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and "
                        . "mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_code' and col8order_cron_no=$col8order_cron_no and dag_no='$dag_no' ";
                    $this->db->query($q); //************************************************************************************************ update query
                    if ($this->db->affected_rows() <= 0) {
                        $this->db->trans_rollback();
                        log_message("error", "#ERR007 Could not update new_dag_no in chitha_col8_order with district: " . $dist_code . ", petition_no: " . $petition_no);
                        return false;
                    }
                } else {
                    $chitha_basic['dag_no'] = $dag_no;

                    $q = "select dag_no_int as dag_no_int from   chitha_basic where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and "
                        . "mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_code' and patta_type_code='$occ->patta_type_code' and "
                        . "TRIM(patta_no)=trim('$occ->patta_no')";
                    $dag_no_int = $this->db->query($q)->row()->dag_no_int;

                    $chitha_basic['dag_no_int'] = $dag_no_int;
                    $chitha_basic['patta_no'] = trim($occ->patta_no);
                }

                $chitha_basic['patta_type_code'] = $occ->patta_type_code;
                $chitha_basic['operation'] = "E";
                //var_dump($dag_pattadar);

                $corrected = date('Y-m-d G:i:s');
                if ((!$chitha_basic_update) && ($ord->order_type_code == '02')) {
                    // This Block Is For Field Partition
                    $chitha_basic_update = TRUE;
                    $sql = "select dag_area_b,dag_area_k,dag_area_lc,dag_area_g,dag_area_kr,dag_revenue from   chitha_basic where dist_code='$occ->dist_code' and "
                        . "subdiv_code='$occ->subdiv_code' and cir_code='$occ->cir_code' and mouza_pargona_code='$occ->mouza_pargona_code' and lot_no='$occ->lot_no' and "
                        . "vill_townprt_code='$occ->vill_townprt_code' and dag_no='$occ->dag_no' and TRIM(patta_no)=trim('$occ->patta_no') and patta_type_code='$occ->patta_type_code' ";
                    $data = $this->db->query($sql)->row();

                    ////// BARAK VALLEY CODE START ////////////
                    if (in_array($dist_code, BARAK_VALLEY)) {
                        $chitha_basic['dag_revenue'] = $ord->min_revenue * (($ord->mut_land_area_b * 6400 + $ord->mut_land_area_k * 320 + $ord->mut_land_area_lc * 20 + $ord->mut_land_area_g) / 100.0);
                    } else {
                        $chitha_basic['dag_revenue'] = $ord->min_revenue * (($ord->mut_land_area_b * 100 + $ord->mut_land_area_k * 20 + $ord->mut_land_area_lc) / 100.0);

                    }


                    $chitha_basic['dag_local_tax'] = $chitha_basic['dag_revenue'] / 4.0;

                    // $tstatus_ch = $this->db->insert("chitha_basic", $chitha_basic); //************************************************************************************************ insert query
                    $tstatus_ch = $this->pm->insert_table('chitha_basic', $chitha_basic);

                    if ($tstatus_ch != 1) {
                        $this->db->trans_rollback();
                        log_message("error", "#ERR008 Could not insert in chitha_basic with district: " . $dist_code . ", petition_no: " . $petition_no);
                        return false;
                    }


                    $dataNew['dag_no'] = $chitha_basic['dag_no'];
                    $tstatus_ord = $this->db->insert("chitha_col8_order", $dataNew); //************************************************************************************************ insert query
                    if ($tstatus_ord != 1) {
                        $this->db->trans_rollback();
                        log_message("error", "#ERR009 Could not insert in chitha_col8_order with district: " . $dist_code . ", petition_no: " . $petition_no);
                        return false;
                    }


                    if (in_array($dist_code, BARAK_VALLEY)) {
                        $sourcelessa = $data->dag_area_b * 6400 + $data->dag_area_k * 320 + $data->dag_area_lc * 20 + $data->dag_area_g;
                        $mutationlessa = $ord->mut_land_area_b * 6400 + $ord->mut_land_area_k * 320 + $ord->mut_land_area_lc * 20 + $ord->mut_land_area_g;
                        $remaining_lessa = $sourcelessa - $mutationlessa;

                        $left_b = floor($remaining_lessa / 6400);
                        $left_k = floor(($remaining_lessa - $left_b * 6400) / 320);
                        $left_lc = floor(($remaining_lessa - $left_b * 6400 - $left_k * 320) / 20);
                        $left_g = $remaining_lessa - $left_b * 6400 - $left_k * 320 - $left_lc * 20;
                        $left_kr = 0;
                    } else {
                        $sourcelessa = $data->dag_area_b * 100 + $data->dag_area_k * 20 + $data->dag_area_lc;
                        $mutationlessa = $ord->mut_land_area_b * 100 + $ord->mut_land_area_k * 20 + $ord->mut_land_area_lc;
                        $remaining_lessa = $sourcelessa - $mutationlessa;

                        $left_b = floor($remaining_lessa / 100);
                        $left_k = floor(($remaining_lessa - $left_b * 100) / 20);
                        $left_lc = $remaining_lessa - $left_b * 100 - $left_k * 20;
                        $left_g = 0;
                        $left_kr = 0;
                    }

                    $d = date('Y-m-d G:i:s');

                    $dag_revenue_updates = $data->dag_revenue;

                    if ($dag_revenue_updates == null) {
                        $dag_revenue_updates = 0;
                    }
                    $dag_local_tax_update = $dag_revenue_updates / 4;
                    // $sql = "update chitha_basic set jama_yn=null,dag_revenue=$dag_revenue_updates,dag_local_tax=$dag_local_tax_update,dag_area_b=$left_b,dag_area_k=$left_k,"
                    //         . "dag_area_lc=$left_lc,dag_area_g=$left_g,dag_area_kr=$left_kr,date_entry='$d',operation='M' where dist_code='$occ->dist_code' and "
                    //         . "subdiv_code='$occ->subdiv_code' and cir_code='$occ->cir_code' and mouza_pargona_code='$occ->mouza_pargona_code' and lot_no='$occ->lot_no' and "
                    //         . "vill_townprt_code='$occ->vill_townprt_code' and dag_no='$occ->dag_no' and TRIM(patta_no)=trim('$occ->patta_no') and patta_type_code='$occ->patta_type_code' ";
                    // $this->db->query($sql); //************************************************************************************************ update query

                    $table = 'chitha_basic';

                    $params = [
                        'jama_yn' => null,
                        'dag_revenue' => $dag_revenue_updates,
                        'dag_local_tax' => $dag_local_tax_update,
                        'dag_area_b' => $left_b,
                        'dag_area_k' => $left_k,
                        'dag_area_lc' => $left_lc,
                        'dag_area_g' => $left_g,
                        'dag_area_kr' => $left_kr,
                        'date_entry' => $d,
                        'operation' => 'M',
                    ];

                    $where = [
                        'dist_code' => $occ->dist_code,
                        'subdiv_code' => $occ->subdiv_code,
                        'cir_code' => $occ->cir_code,
                        'mouza_pargona_code' => $occ->mouza_pargona_code,
                        'lot_no' => $occ->lot_no,
                        'vill_townprt_code' => $occ->vill_townprt_code,
                        'dag_no' => $occ->dag_no,
                        'patta_no' => trim($occ->patta_no),
                        'patta_type_code' => $occ->patta_type_code,
                    ];

                    // Call your model method to update
                    $result5 = $this->pm->update_table($table, $params, $where);

                    if ($result5 <= 0) {
                        $this->db->trans_rollback();
                        log_message("error", "#ERR010 Could not update  jama_yn=null in chitha_basic with district: " . $dist_code . ", petition_no: " . $petition_no);
                        return false;
                    }
                }

                $p_id = $dag_pattadar['pdar_id'];

                if ($ord->order_type_code == '02') {
                    // This Block Is For Field Partition
                    $q = "select count(*) as count from   chitha_dag_pattadar  where dist_code='$occ->dist_code' and subdiv_code='$occ->subdiv_code' and cir_code='$occ->cir_code' "
                        . "and mouza_pargona_code='$occ->mouza_pargona_code' and lot_no='$occ->lot_no' and vill_townprt_code='$occ->vill_townprt_code' and dag_no='$occ->dag_no' "
                        . "and TRIM(patta_no)=trim('$occ->new_patta_no') and patta_type_code='$occ->patta_type_code' and pdar_id='$p_id'";
                    $cDagPattadarExists = $this->db->query($q)->row()->count;

                    $q = "select count(*) as count from   chitha_pattadar  where dist_code='$occ->dist_code' and subdiv_code='$occ->subdiv_code' and cir_code='$occ->cir_code'"
                        . " and mouza_pargona_code='$occ->mouza_pargona_code' and"
                        . " lot_no='$occ->lot_no' and vill_townprt_code='$occ->vill_townprt_code' "
                        . " and TRIM(patta_no)=trim('$occ->new_patta_no') and"
                        . " patta_type_code='$occ->patta_type_code' and pdar_id='$p_id'";
                    $cPattadarExists = $this->db->query($q)->row()->count;
                } else {
                    // This Block Is For Field Mutation
                    $q = "select count(*) as count from   chitha_dag_pattadar  where dist_code='$occ->dist_code' and subdiv_code='$occ->subdiv_code' and cir_code='$occ->cir_code' "
                        . "and mouza_pargona_code='$occ->mouza_pargona_code' and lot_no='$occ->lot_no' and vill_townprt_code='$occ->vill_townprt_code' and dag_no='$occ->dag_no' "
                        . "and TRIM(patta_no)=trim('$occ->patta_no') and patta_type_code='$occ->patta_type_code' and pdar_id='$p_id'";
                    $cDagPattadarExists = $this->db->query($q)->row()->count;

                    $q = "select count(*) as count from   chitha_pattadar  where dist_code='$occ->dist_code' and subdiv_code='$occ->subdiv_code' and cir_code='$occ->cir_code' and "
                        . "mouza_pargona_code='$occ->mouza_pargona_code' and lot_no='$occ->lot_no' and vill_townprt_code='$occ->vill_townprt_code' and "
                        . "TRIM(patta_no)=trim('$occ->patta_no') and patta_type_code='$occ->patta_type_code' and pdar_id='$p_id'";
                    $cPattadarExists = $this->db->query($q)->row()->count;
                }
                //var_dump($dag_pattadar);
                $occ->new_pattadar; // for partition it will always be new pattadar
                if (($occ->new_pattadar == 'N')) {
                    //var_dump($dag_pattadar);
                    //var_dump($chitha_pattadar);
                    // $tstatus3 = $this->db->insert("chitha_dag_pattadar", $dag_pattadar);//************************************************* insert query
                    $tstatus3 = $this->pm->insert_table('chitha_dag_pattadar', $dag_pattadar);
                    if ($tstatus3 != 1) {
                        $this->db->trans_rollback();
                        log_message("error", "#ERR011 Could not insert in  chitha_dag_pattadar with district: " . $dist_code . ", petition_no: " . $petition_no);
                        return false;
                    }
                    if (($cPattadarExists == 0)) {
                        // $tstatus4 = $this->db->insert("chitha_pattadar", $chitha_pattadar);//************************************************************************************************ insert query
                        $chitha_pattadar['f1_case_no'] = $case_no;
                        $tstatus4 = $this->pm->insert_table('chitha_pattadar', $chitha_pattadar);
                        if ($tstatus4 != 1) {
                            $this->db->trans_rollback();
                            log_message("error", "#ERR012 Could not insert in  chitha_pattadar with district: " . $dist_code . ", petition_no: " . $petition_no);
                            return false;
                        }
                    }
                }
                $today = date('Y-m-d');
                $t_occup_query = "update t_chitha_col8_occup set iscorrected_inco='Y',iscorrected_inco_date='$corrected',order_passed='Y' where dist_code='$dist_code' and subdiv_code='$subdiv_code' and "
                    . "cir_code='$cir_code' and lot_no='$lot_no' and mouza_pargona_code='$mouza_pargona_code' and "
                    . "vill_townprt_code='$vill_code' and petition_no=$petition_no and dag_no='$dag_no' ";
                $this->db->query($t_occup_query);//*********************************************************************************** update query
                if ($this->db->affected_rows() <= 0) {
                    $this->db->trans_rollback();
                    log_message("error", "#ERR013 Could not update iscorrected_inco in t_chitha_col8_occup with district: " . $dist_code
                        . ", petition_no: " . $petition_no);
                    return false;
                }
            }
            // occupants details ends here

            if ($ord->order_type_code == '02') {
                foreach ($t_occup_data as $occup) {
                    // $sql = "update chitha_dag_pattadar set p_flag='1' where   dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and lot_no='$lot_no' and "
                    //         . "mouza_pargona_code='$mouza_pargona_code' and vill_townprt_code='$vill_code' and dag_no='$dag_no' and pdar_id=$occup->pdar_id";
                    // $this->db->query($sql);//************************************************************************************************ update query
                    $sqlCheck = "Select * from chitha_dag_pattadar where dist_code=? and subdiv_code=? and cir_code=? and mouza_pargona_code=? and lot_no=? and vill_townprt_code=? and dag_no=? and pdar_id=? and (p_flag is null or p_flag!='1') ";
                    $cdpCheck_12 = $this->db->query($sqlCheck, [$occ->dist_code, $occ->subdiv_code, $occ->cir_code, $occ->mouza_pargona_code, $occ->lot_no, $occ->vill_townprt_code, $occ->dag_no, $occup->pdar_id]);
                    if ($cdpCheck_12->num_rows() == 0) {
                        $this->db->trans_rollback();
                        log_message("error", "#ERR01444 Pattadar Already Strike: " . $dist_code
                            . ", petition_no: " . $petition_no . "QUERY##" . $this->db->last_query());
                        return false;
                    }
                    /////////////////////////////////////////
                    $sqlCheck = "Select * from chitha_dag_pattadar where dist_code=? and subdiv_code=? and cir_code=? and mouza_pargona_code=? and lot_no=? and vill_townprt_code=? and dag_no=? and pdar_id!=? and (p_flag is null or p_flag!='1') ";
                    $cdpCheck_1 = $this->db->query($sqlCheck, [$occ->dist_code, $occ->subdiv_code, $occ->cir_code, $occ->mouza_pargona_code, $occ->lot_no, $occ->vill_townprt_code, $occ->dag_no, $occup->pdar_id]);
                    if ($cdpCheck_1->num_rows() == 0) {
                        $params = [
                            'p_flag' => '0',
                        ];
                    } else {
                        $params = [
                            'p_flag' => '1',
                        ];
                    }
                    $table = 'chitha_dag_pattadar';
                    $where = [
                        'dist_code' => $dist_code,
                        'subdiv_code' => $subdiv_code,
                        'cir_code' => $cir_code,
                        'lot_no' => $lot_no,
                        'mouza_pargona_code' => $mouza_pargona_code,
                        'vill_townprt_code' => $vill_code,
                        'dag_no' => $dag_no,
                        'pdar_id' => $occup->pdar_id,
                    ];

                    // Then call the update method:
                    $result = $this->pm->update_table($table, $params, $where);

                    if ($result <= 0) {
                        $this->db->trans_rollback();
                        log_message("error", "#ERR014 Could not update p_flag in chitha_dag_pattadar with district: " . $dist_code
                            . ", petition_no: " . $petition_no);
                        return false;
                    }
                }
            }

            if (($ord->order_type_code == '01') || ($ord->order_type_code == '02')) {

                $t_inplace_query = "select * from   t_chitha_col8_inplace where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and lot_no='$lot_no' and "
                    . "mouza_pargona_code='$mouza_pargona_code' and vill_townprt_code='$vill_code' and dag_no='$dag_no' and iscorrected_inco is null";
                $t_inplace_data = $this->db->query($t_inplace_query);

                if (($ord->order_type_code == '01') && ($t_inplace_data == null || $t_inplace_data->num_rows() <= 0)) {
                    $this->db->trans_rollback();
                    log_message("error", "#ERR015 Could not find data in t_chitha_col8_inplace with district: "
                        . $dist_code . ", petition_no: " . $petition_no);
                    return false;
                }
                $t_inplace_data = $t_inplace_data->result();

                foreach ($t_inplace_data as $inplace) {
                    $data = array();

                    foreach ($inplace as $key => $value) {
                        $data[$key] = $value;
                    }
                    unset($data['occupant_id']);
                    unset($data['year_no']);
                    unset($data['petition_no']);
                    unset($data['occupant_name']);
                    unset($data['occupant_fmh_name']);
                    unset($data['occupant_fmh_flag']);
                    unset($data['occupant_add1']);
                    unset($data['occupant_add2']);
                    unset($data['occupant_add3']);
                    unset($data['old_patta_no']);
                    unset($data['new_patta_no']);
                    unset($data['old_dag_no']);
                    unset($data['patta_type_code']);
                    unset($data['patta_no']);
                    unset($data['pdar_id']);
                    unset($data['iscorrected_inco']);
                    unset($data['iscorrected_inco_date']);
                    unset($data['isdataposted_torkg_db']);
                    unset($data['iscorrected_rkg_record']);
                    unset($data['new_dag_no']);
                    unset($data['order_passed']);
                    unset($data['date_of_order']);
                    unset($data['make_mdb']);
                    unset($data['iscorrected_rkg_date']);
                    unset($data['revenue']);
                    unset($data['new_pattadar']);
                    unset($data['hus_wife']);
                    unset($data['revenue']);


                    if ($data['fmute_strike_out'] == '1') {
                        $data['inplaceof_alongwith'] = 'i';
                    } else {
                        $data['inplaceof_alongwith'] = 'a';
                    }
                    unset($data['fmute_strike_out']);
                    $data['col8order_cron_no'] = $col8order_cron_no;
                    $data['user_code'] = $$user_code;
                    $data['date_entry'] = date('Y-m-d G:i:s');
                    $data['operation'] = date('E');
                    // var_dump($data);
                    $key = array(
                        'dist_code' => $data['dist_code'],
                        'subdiv_code' => $data['subdiv_code'],
                        'cir_code' => $data['cir_code'],
                        'mouza_pargona_code' => $data['mouza_pargona_code'],
                        'lot_no' => $data['lot_no'],
                        'vill_townprt_code' => $data['vill_townprt_code'],
                        'dag_no' => $data['dag_no'],
                        'col8order_cron_no' => $data['col8order_cron_no'],
                        'inplace_of_id' => $data['inplace_of_id'],
                    );

                    $queryCheck = "select count(*) as c from   chitha_col8_inplace where dist_code='$data[dist_code]' and subdiv_code='$data[subdiv_code]' and cir_code='$data[cir_code]' and "
                        . " mouza_pargona_code='$data[mouza_pargona_code]' and lot_no='$data[lot_no]' and vill_townprt_code='$data[vill_townprt_code]' and dag_no='$data[dag_no]' and "
                        . "col8order_cron_no='$data[col8order_cron_no]' and inplace_of_id='$data[inplace_of_id]' ";
                    $count = $this->db->query($queryCheck)->row()->c;
                    if ($count <= 0) {
                        $tstatus5 = $this->db->insert("chitha_col8_inplace", $data);//********************************************** insert query
                        if ($tstatus5 != 1) {
                            $this->db->trans_rollback();
                            log_message("error", "#ERR016 Could not insert in chitha_col8_inplace with district: " . $dist_code
                                . ", petition_no: " . $petition_no);
                            return false;
                        }
                    }

                    $p_flag = '0';
                    if ($inplace->fmute_strike_out == '1')
                        $p_flag = '1';
                    $corrected = date('Y-m-d G:i:s');
                    // $update_query = "update chitha_dag_pattadar  set p_flag='$p_flag',date_entry='$corrected' where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and "
                    //         . "lot_no='$lot_no' and mouza_pargona_code='$mouza_pargona_code' and vill_townprt_code='$vill_code' and dag_no='$dag_no' and pdar_id=$inplace->pdar_id";

                    // $this->db->query($update_query);//************************************************************************************ update query
                    $table = 'chitha_dag_pattadar';

                    $params = [
                        'p_flag' => $p_flag,
                        'date_entry' => $corrected,
                    ];

                    $where = [
                        'dist_code' => $dist_code,
                        'subdiv_code' => $subdiv_code,
                        'cir_code' => $cir_code,
                        'lot_no' => $lot_no,
                        'mouza_pargona_code' => $mouza_pargona_code,
                        'vill_townprt_code' => $vill_code,
                        'dag_no' => $dag_no,
                        'pdar_id' => $inplace->pdar_id,
                    ];

                    // Execute update
                    $result = $this->pm->update_table($table, $params, $where);


                    if ($result <= 0) {
                        $this->db->trans_rollback();
                        log_message("error", "#ERR017 Could not update p_flag in chitha_dag_pattadar with district: " . $dist_code
                            . ", petition_no: " . $petition_no);
                        return false;
                    }

                    $t_inplace_query = "update t_chitha_col8_inplace set iscorrected_inco='Y',iscorrected_inco_date='$corrected',order_passed='Y' where dist_code='$dist_code' and "
                        . "subdiv_code='$subdiv_code' and cir_code='$cir_code' and lot_no='$lot_no' and mouza_pargona_code='$mouza_pargona_code' and vill_townprt_code='$vill_code' "
                        . "and dag_no='$dag_no'";
                    $this->db->query($t_inplace_query);//*********************************************************************************** update query
                    if ($this->db->affected_rows() <= 0) {
                        $this->db->trans_rollback();
                        log_message("error", "#ERR018 Could not update iscorrected_inco in t_chitha_col8_inplace with district: " . $dist_code
                            . ", petition_no: " . $petition_no);
                        return false;
                    }

                    $date_of_order = date('Y-m-d');
                    $order_update_query = "update field_mut_basic set order_passed='Y',date_of_order='$date_of_order' where dist_code='$dist_code' and subdiv_code='$subdiv_code' and "
                        . "cir_code='$cir_code' and lot_no='$lot_no' and mouza_pargona_code='$mouza_pargona_code' and "
                        . "vill_townprt_code='$vill_code' and petition_no=$petition_no";
                    $this->db->query($order_update_query);//***************************************************************** update query
                    if ($this->db->affected_rows() <= 0) {
                        $this->db->trans_rollback();
                        log_message("error", " #ERR019 Could not update order_passed in field_mut_basic with district: " . $dist_code
                            . ", petition_no: " . $petition_no);
                        return false;
                    }
                }
            }
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            log_message("error", "#ERR020 Could not complet autoUpdate for chitha with district: " . $dist_code
                . ", petition_no: " . $petition_no);
            return false;
        }
        return true;
    }

    public function autoUpdate_fulldag_field(
        $dist_code,
        $subdiv_code,
        $cir_code,
        $mouza_pargona_code,
        $lot_no,
        $vill_code,
        $petition_no,
        $dag_no,
        $userCode,
        $case_no
    ) {

        $locationData = array(
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'lot_no' => $lot_no,
            'vill_code' => $vill_code,
            'mouza_pargona_code' => $mouza_pargona_code,
        );

        $year_no = year_no;
        $col8order_cron_no = $this->db->query("select max(col8order_cron_no)+1 as cron_no from   chitha_col8_order where dist_code='$dist_code' and subdiv_code='$subdiv_code' and "
            . "cir_code='$cir_code' and lot_no='$lot_no' and mouza_pargona_code='$mouza_pargona_code' and "
            . "vill_townprt_code='$vill_code' and dag_no='$dag_no'")->row()->cron_no;
        //echo "select max(col8order_cron_no)+1 as cron_no from   chitha_col8_order";           
        if ($col8order_cron_no == null) {
            $col8order_cron_no = 1;
        }
        $t_order_data_query = "select * from   t_chitha_col8_order where dist_code='$dist_code' and subdiv_code='$subdiv_code' and "
            . "cir_code='$cir_code' and lot_no='$lot_no' and mouza_pargona_code='$mouza_pargona_code' and "
            . "vill_townprt_code='$vill_code' and petition_no=$petition_no and dag_no='$dag_no'";
        //echo $t_order_data_query;           
        $t_data_order = $this->db->query($t_order_data_query);

        if ($t_data_order == null || $t_data_order->num_rows() <= 0) {
            $this->db->trans_rollback();

            log_message("error", "#FPTCO007 No detail available in t_chitha_col8_order for dist:" . $dist_code . ", petition no: " . $petition_no);

            return $this->generateFunRespData(false, "FPTCO007: Unable to pass order !");
        }

        $t_data_order = $t_data_order->result();
        $i = 1;

        foreach ($t_data_order as $ord) {
            log_message("error", "MPR:****************************: count=" . $i++);
            $data_order = array();
            foreach ($ord as $key => $value) {
                $data[$key] = $value;
            }
            //var_dump($data);
            $data['col8order_cron_no'] = $col8order_cron_no;
            $data['user_code'] = $userCode;
            $data['date_entry'] = date('Y-m-d G:i:s');
            $data['operation'] = date('E');
            unset($data['year_no']);
            unset($data['petition_no']);
            unset($data['iscorrected_inco']);
            unset($data['iscorrected_inco_date']);
            unset($data['isdataposted_torkg_db']);
            unset($data['iscorrected_rkg_record']);
            unset($data['iscorrected_rkg_date']);
            unset($data['order_passed']);
            unset($data['date_of_order']);
            unset($data['make_mdb']);
            unset($data['date_of_order']);
            unset($data['not_consistent']);
            $corrected = date('Y-m-d G:i:s');
            $dataNew = $data;
            //var_dump($data);
            $tstatus11 = $this->db->insert("chitha_col8_order", $data); //*************************

            if ($tstatus11 != 1) {
                $this->db->trans_rollback();

                log_message("error", "#FPCC008 Insertion failed in chitha_col8_order for dist: "
                    . $dist_code . ", petition no: " . $petition_no);
                redirect(base_url() . "index.php/home");

                return $this->generateFunRespData(false, "FPCC008: Unable to pass order !");
            }

            $update_query = "update t_chitha_col8_order  set iscorrected_inco='Y',iscorrected_inco_date='$corrected' where dist_code='$dist_code' and subdiv_code='$subdiv_code' and "
                . "cir_code='$cir_code' and lot_no='$lot_no' and mouza_pargona_code='$mouza_pargona_code' and "
                . "vill_townprt_code='$vill_code' and petition_no=$petition_no and  dag_no='$dag_no' ";
            $this->db->query($update_query); //************************

            if ($this->db->affected_rows() <= 0) {
                $this->db->trans_rollback();

                log_message("error", "#FPTCC009 Updation failed in t_chitha_col8_order for dist: " . $dist_code . ", petition no: " . $petition_no);

                return $this->generateFunRespData(false, "FPTCC009: Unable to pass order !");
            }

            $t_occup_query = "select * from   t_chitha_col8_occup where dist_code='$dist_code' and subdiv_code='$subdiv_code' and "
                . "cir_code='$cir_code' and lot_no='$lot_no' and mouza_pargona_code='$mouza_pargona_code' and "
                . "vill_townprt_code='$vill_code' and petition_no=$petition_no and dag_no='$dag_no' "
                . " and iscorrected_inco is null";

            $t_occup_data = $this->db->query($t_occup_query);
            if ($t_occup_data == null || $t_occup_data->num_rows() <= 0) {
                $this->db->trans_rollback();

                log_message("error", "#FPTCC010 Data not available in t_chitha_col8_occup for dist:" . $dist_code . ", petition no: " . $petition_no);

                return $this->generateFunRespData(false, "FPTCC010: Unable to pass order !");
            }

            $chitha_update = 0;
            $t_occup_data = $t_occup_data->result();
            //var_dump($t_occup_data);

            $chitha_basic_update = FALSE;

            foreach ($t_occup_data as $occ) {

                if ($chitha_update == 0) {

                    $table = 'chitha_basic';

                    $params = [
                        'jama_yn' => null,
                        'patta_no' => $occ->new_patta_no,
                        'old_patta_no' => $occ->patta_no,
                    ];

                    $where = [
                        'dist_code' => $occ->dist_code,
                        'subdiv_code' => $occ->subdiv_code,
                        'cir_code' => $occ->cir_code,
                        'mouza_pargona_code' => $occ->mouza_pargona_code,
                        'lot_no' => $occ->lot_no,
                        'vill_townprt_code' => $occ->vill_townprt_code,
                        'dag_no' => $occ->dag_no,
                        'patta_no' => trim($occ->patta_no),
                        'patta_type_code' => $occ->patta_type_code,
                    ];

                    $result2 = $this->pm->update_table($table, $params, $where);

                    if ($result2 <= 0) {
                        $this->db->trans_rollback();

                        log_message("error", "#FPCB011 Updation failed in chitha_basic for dist: " . $dist_code . ", petition no: " . $petition_no . " Query:" . $this->db->affected_rows());

                        return $this->generateFunRespData(false, "FPCB011: Unable to pass order !");
                    }
                    $chitha_update = 1;
                }

                $data = array();
                foreach ($occ as $key => $value) {
                    $data[$key] = $value;
                }

                unset($data['year_no']);
                unset($data['petition_no']);
                unset($data['iscorrected_inco']);
                unset($data['iscorrected_inco_date']);
                unset($data['isdataposted_torkg_db']);
                unset($data['iscorrected_rkg_record']);
                unset($data['iscorrected_rkg_date']);
                unset($data['order_passed']);
                unset($data['date_of_order']);
                unset($data['make_mdb']);
                unset($data['date_of_order']);
                unset($data['patta_type_code']);
                unset($data['patta_no']);
                unset($data['pdar_id']);
                unset($data['revenue']);
                unset($data['new_pattadar']);
                $data['col8order_cron_no'] = $col8order_cron_no;
                $data['user_code'] = $userCode;
                $data['date_entry'] = date('Y-m-d G:i:s');
                $data['operation'] = date('E');
                $occupData = $data;
                //var_dump($data);
                $tstatus12 = $this->db->insert("chitha_col8_occup", $data); // ******************

                if ($tstatus12 != 1) {
                    $this->db->trans_rollback();

                    log_message("error", "#FPCCO012 Insertion failed in chitha_col8_occup for dist:" . $dist_code . ", petition no: " . $petition_no);

                    return $this->generateFunRespData(false, "FPCCO012: Unable to pass order !");
                }

                $dag_pattadar = array();
                $chitha_pattadar = array();

                $pdar_id = null;

                if ($ord->order_type_code == '02') {

                    $pdar_id = $this->pm->maxpdarIdCheck($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code, $occ->patta_type_code, trim($occ->patta_no));
                } else {
                    //todo
                    $pdar_id = $this->pm->maxpdarIdCheck($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code, $occ->patta_type_code, trim($occ->patta_no));
                }
                if ($pdar_id == null) {
                    $pdar_id = 1;
                }
                $dag_pattadar['dist_code'] = $dist_code;
                $dag_pattadar['subdiv_code'] = $subdiv_code;
                $dag_pattadar['cir_code'] = $cir_code;
                $dag_pattadar['lot_no'] = $lot_no;
                $dag_pattadar['mouza_pargona_code'] = $mouza_pargona_code;
                $dag_pattadar['vill_townprt_code'] = $vill_code;
                if ($ord->order_type_code == '02') {
                    $dag_pattadar['dag_no '] = $occ->new_dag_no;
                } else {
                    $dag_pattadar['dag_no '] = $dag_no;
                }
                if (($occ->pdar_id) && (!($ord->order_type_code == '02'))) {
                    $dag_pattadar['pdar_id'] = $occ->pdar_id;
                } else {
                    $dag_pattadar['pdar_id'] = $pdar_id;
                }
                if ($ord->order_type_code == '02') {
                    $dag_pattadar['patta_no'] = trim($occ->new_patta_no);
                    $chitha_pattadar['patta_no'] = trim($occ->new_patta_no);
                } else {
                    $dag_pattadar['patta_no'] = trim($occ->patta_no);
                    $chitha_pattadar['patta_no'] = trim($occ->patta_no);
                }
                $dag_pattadar['p_flag'] = '0';
                $dag_pattadar['patta_type_code'] = $occ->patta_type_code;
                $dag_pattadar['dag_por_b'] = $occ->land_area_b;
                $dag_pattadar['dag_por_k'] = $occ->land_area_k;
                $dag_pattadar['dag_por_lc'] = $occ->land_area_lc;
                $dag_pattadar['dag_por_g'] = $occ->land_area_g;
                $dag_pattadar['dag_por_kr'] = $occ->land_area_kr;

                $dag_pattadar['user_code'] = $data['user_code'];
                $dag_pattadar['date_entry'] = date('Y-m-d G:i:s');
                $dag_pattadar['operation'] = date('E');

                $chitha_pattadar['dist_code'] = $dist_code;
                $chitha_pattadar['subdiv_code'] = $subdiv_code;
                $chitha_pattadar['cir_code'] = $cir_code;
                $chitha_pattadar['lot_no'] = $lot_no;
                $chitha_pattadar['mouza_pargona_code'] = $mouza_pargona_code;
                $chitha_pattadar['vill_townprt_code'] = $vill_code;

                $chitha_pattadar['pdar_id'] = $occ->pdar_id;
                $chitha_pattadar['new_pdar_name'] = $occ->new_pattadar;
                $chitha_pattadar['patta_type_code'] = $occ->patta_type_code;
                $chitha_pattadar['pdar_name'] = $occ->occupant_name;
                $chitha_pattadar['pdar_father'] = $occ->occupant_fmh_name;
                $chitha_pattadar['pdar_add1'] = $occ->occupant_add1;
                $chitha_pattadar['pdar_add2'] = $occ->occupant_add2;
                $chitha_pattadar['pdar_add3'] = $occ->occupant_add3;
                $chitha_pattadar['pdar_name'] = $occ->occupant_name;
                $chitha_pattadar['pdar_name'] = $occ->occupant_name;
                $chitha_pattadar['pdar_name'] = $occ->occupant_name;
                $chitha_pattadar['pdar_guard_reln'] = $occ->occupant_fmh_flag;
                $chitha_pattadar['user_code'] = $data['user_code'];
                $chitha_pattadar['date_entry'] = date('Y-m-d G:i:s');
                $chitha_pattadar['operation'] = date('E');
                $chitha_pattadar['jama_yn'] = 'N';
                //newly added aadhaar details to chitha pattadar----
                $flagAadhaar = null;
                $flagPan = null;
                if ($occ->auth_type == 'AADHAAR') {
                    $chitha_pattadar['pdar_aadharno'] = $occ->id_ref_no;
                    $flagAadhaar = $occ->id_ref_no;
                    $flagPan = null;
                } else if ($occ->auth_type == 'PAN') {
                    $chitha_pattadar['pdar_pan_no'] = $occ->id_ref_no;
                    $flagAadhaar = null;
                    $flagPan = $occ->id_ref_no;
                }

                $chitha_basic_query = "select land_class_code from   chitha_basic "
                    . "where dist_code='$dist_code' and subdiv_code='$subdiv_code' and"
                    . " cir_code='$cir_code' and lot_no='$lot_no' and"
                    . " mouza_pargona_code='$mouza_pargona_code' and TRIM(patta_no)=trim('$occ->new_patta_no') and"
                    . " patta_type_code='$occ->patta_type_code' and dag_no='$dag_no'";

                $result = $this->db->query($chitha_basic_query);

                if ($result == null || $result->num_rows() <= 0) {
                    $this->db->trans_rollback();

                    log_message("error", "#FPLCCO013 Data not available in land_class_code for dist:"
                        . $dist_code . ", petition no: " . $this->db->last_query());
                    $this->session->set_flashdata('message', "FPLCCO013: Unable to pass order !");

                    return $this->generateFunRespData(false, "FPLCCO013: Unable to pass order !");
                }

                $result = $result->row();

                $chitha_basic = array();
                $chitha_basic['dist_code'] = $dist_code;
                $chitha_basic['subdiv_code'] = $subdiv_code;
                $chitha_basic['cir_code'] = $cir_code;
                $chitha_basic['mouza_pargona_code'] = $mouza_pargona_code;
                $chitha_basic['lot_no'] = $lot_no;
                $chitha_basic['vill_townprt_code'] = $vill_code;
                $chitha_basic['dag_area_b'] = $ord->mut_land_area_b;
                $chitha_basic['dag_area_k'] = $ord->mut_land_area_k;
                $chitha_basic['dag_area_lc'] = $ord->mut_land_area_lc;
                $chitha_basic['dag_area_g'] = $ord->mut_land_area_g;
                $chitha_basic['dag_area_kr'] = $ord->mut_land_area_kr;
                $chitha_basic['user_code'] = $data['user_code'];
                $chitha_basic['date_entry'] = date('Y-m-d G:i:s');
                $chitha_basic['land_class_code'] = $result->land_class_code;

                //var_dump($chitha_basic);
                if ($ord->order_type_code == '02') {
                    $old_dag = $dag_no;

                    $chitha_basic['dag_no'] = $occ->new_dag_no;
                    $chitha_basic['dag_no_int'] = $occ->new_dag_no . '00';
                    $chitha_basic['old_dag_no '] = $dag_no;
                    $old_patta = trim($occ->patta_no);
                    $chitha_basic['patta_no'] = trim($occ->new_patta_no);

                    $q = "update chitha_col8_order set new_dag_no='$occ->new_dag_no' where "
                        . "dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and "
                        . " mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and"
                        . " vill_townprt_code='$vill_code' and col8order_cron_no=$col8order_cron_no";

                    $this->db->query($q); //***********************

                    if ($this->db->affected_rows() <= 0) {
                        $this->db->trans_rollback();

                        log_message("error", "#FPCCO014 Updation failed in chitha_col8_order for dist:"
                            . $dist_code . ", petition no: " . $this->db->last_query());

                        return $this->generateFunRespData(false, "FPCCO014: Unable to pass order !");
                    }
                } else {
                    $chitha_basic['dag_no'] = $dag_no;

                    $q = "select dag_no_int as dag_no_int from   chitha_basic where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code'" .
                        " and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_code' and patta_type_code='$occ->patta_type_code' and TRIM(patta_no)=trim('$occ->patta_no')";

                    $dag_no_int = $this->db->query($q)->row()->dag_no_int;

                    $chitha_basic['dag_no_int'] = $dag_no_int;
                    $chitha_basic['patta_no'] = trim($occ->patta_no);
                }
                $chitha_basic['patta_type_code'] = $occ->patta_type_code;

                $chitha_basic['operation'] = "E";
                //var_dump($chitha_basic);
                //var_dump($dag_pattadar);
                $corrected = date('Y-m-d G:i:s');
                if ((!$chitha_basic_update) && ($ord->order_type_code == '02')) {

                    $chitha_basic_update = TRUE;

                    $sql = "select dag_area_b,dag_area_k,dag_area_lc,dag_area_g,dag_area_kr,dag_revenue from chitha_basic where"
                        . "  dist_code='$occ->dist_code' and subdiv_code='$occ->subdiv_code' and cir_code='$occ->cir_code'"
                        . " and mouza_pargona_code='$occ->mouza_pargona_code' and"
                        . " lot_no='$occ->lot_no' and vill_townprt_code='$occ->vill_townprt_code' "
                        . " and dag_no='$occ->dag_no' and TRIM(patta_no)=trim('$occ->new_patta_no') and"
                        . " patta_type_code='$occ->patta_type_code' ";
                    //echo $sql;
                    $data = $this->db->query($sql)->row();

                    if (in_array($dist_code, BARAK_VALLEY)) {
                        $chitha_basic['dag_revenue'] = $ord->min_revenue * (($ord->mut_land_area_b * 6400 + $ord->mut_land_area_k * 320 + $ord->mut_land_area_lc * 20 + $ord->mut_land_area_g) / 100.0);
                    } else {
                        $chitha_basic['dag_revenue'] = $ord->min_revenue * (($ord->mut_land_area_b * 100 + $ord->mut_land_area_k * 20 + $ord->mut_land_area_lc) / 100.0);

                    }
                    $chitha_basic['dag_local_tax'] = $chitha_basic['dag_revenue'] / 4.0;
                    //$this->db->insert('chitha_basic', $chitha_basic); //********************************* not required

                    $dataNew['dag_no'] = $chitha_basic['dag_no'];


                    if (in_array($dist_code, BARAK_VALLEY)) {
                        $sourcelessa = $data->dag_area_b * 6400 + $data->dag_area_k * 320 + $data->dag_area_lc * 20 + $data->dag_area_g;
                        $mutationlessa = $ord->mut_land_area_b * 6400 + $ord->mut_land_area_k * 320 + $ord->mut_land_area_lc * 20 + $ord->mut_land_area_g;
                        $sourcelessa;
                        $mutationlessa;
                        $remaining_lessa = $sourcelessa - $mutationlessa;

                        $left_b = floor($remaining_lessa / 6400);
                        $left_k = floor(($remaining_lessa - $left_b * 6400) / 320);
                        $left_lc = floor(($remaining_lessa - $left_b * 6400 - $left_k * 320) / 20);
                        $left_g = $remaining_lessa - $left_b * 6400 - $left_k * 320 - $left_lc * 20;
                        $left_kr = 0;

                    } else {

                        $sourcelessa = $data->dag_area_b * 100 + $data->dag_area_k * 20 + $data->dag_area_lc;
                        $mutationlessa = $ord->mut_land_area_b * 100 + $ord->mut_land_area_k * 20 + $ord->mut_land_area_lc;
                        $sourcelessa;
                        $mutationlessa;
                        $remaining_lessa = $sourcelessa - $mutationlessa;

                        $left_b = floor($remaining_lessa / 100);
                        $left_k = floor(($remaining_lessa - $left_b * 100) / 20);
                        $left_lc = $remaining_lessa - $left_b * 100 - $left_k * 20;
                        $left_g = 0;
                        $left_kr = 0;
                    }

                    $d = date('Y-m-d G:i:s');

                    $dag_revenue_updates = $data->dag_revenue; //$ord->min_revenue; // * (($left_b * 100 + $left_k * 20 + $left_lc));
                    //$old_patta_no = $data->dag_revenue;
                    if ($dag_revenue_updates == null) {
                        $dag_revenue_updates = 0;
                    }
                    $dag_local_tax_update = $dag_revenue_updates / 4;


                    $table = 'chitha_basic';

                    $params = [
                        'jama_yn' => null,
                        'dag_revenue' => $dag_revenue_updates,
                        'dag_local_tax' => $dag_local_tax_update,
                        'dag_area_b' => $ord->mut_land_area_b,
                        'dag_area_k' => $ord->mut_land_area_k,
                        'dag_area_lc' => $ord->mut_land_area_lc,
                        'dag_area_g' => $left_g,
                        'dag_area_kr' => $left_kr,
                        'date_entry' => $d,
                        'operation' => 'M',
                    ];

                    $where = [
                        'dist_code' => $occ->dist_code,
                        'subdiv_code' => $occ->subdiv_code,
                        'cir_code' => $occ->cir_code,
                        'mouza_pargona_code' => $occ->mouza_pargona_code,
                        'lot_no' => $occ->lot_no,
                        'vill_townprt_code' => $occ->vill_townprt_code,
                        'dag_no' => $occ->dag_no,
                        'patta_no' => trim($occ->new_patta_no),
                        'patta_type_code' => $occ->patta_type_code,
                    ];

                    // Then update like this:
                    $result3 = $this->pm->update_table($table, $params, $where); //todo


                    if ($result3 <= 0) {
                        $this->db->trans_rollback();

                        log_message("error", "#FPCBO016 Failed to update jama_yn=null in chitha_basic for dist:"
                            . $dist_code . ", petition no: " . $petition_no);

                        return $this->generateFunRespData(false, "FPCBO016: Unable to pass order !");
                    }
                }

                $p_id = $occ->pdar_id;
                $q = "select count(*) as count from   chitha_dag_pattadar  where dist_code='$occ->dist_code' and subdiv_code='$occ->subdiv_code' and cir_code='$occ->cir_code'"
                    . " and mouza_pargona_code='$occ->mouza_pargona_code' and"
                    . " lot_no='$occ->lot_no' and vill_townprt_code='$occ->vill_townprt_code' "
                    . " and dag_no='$occ->dag_no' and TRIM(patta_no)=trim('$occ->patta_no') and"
                    . " patta_type_code='$occ->patta_type_code'";// and pdar_id=$p_id";
                //echo $q;
                $cDagPattadarExists = $this->db->query($q)->row()->count;

                $q = "select count(*) as count from   chitha_pattadar  where dist_code='$occ->dist_code' and subdiv_code='$occ->subdiv_code' and cir_code='$occ->cir_code'"
                    . " and mouza_pargona_code='$occ->mouza_pargona_code' and"
                    . " lot_no='$occ->lot_no' and vill_townprt_code='$occ->vill_townprt_code' "
                    . " and TRIM(patta_no)=trim('$occ->new_patta_no') and"
                    . " patta_type_code='$occ->patta_type_code' and pdar_id=$p_id";
                //echo $q;
                $cPattadarExists = $this->db->query($q)->row()->count;

                $occ->new_pattadar;

                $table = 'chitha_dag_pattadar';

                $params = [
                    'patta_no' => $occ->new_patta_no,
                    'p_flag' => '0',
                    'jama_yn' => 'n',
                ];

                $where = [
                    'dist_code' => $occ->dist_code,
                    'subdiv_code' => $occ->subdiv_code,
                    'cir_code' => $occ->cir_code,
                    'mouza_pargona_code' => $occ->mouza_pargona_code,
                    'lot_no' => $occ->lot_no,
                    'vill_townprt_code' => $occ->vill_townprt_code,
                    'dag_no' => $occ->dag_no,
                    'patta_no' => trim($occ->patta_no),  // trim in PHP
                    'patta_type_code' => $occ->patta_type_code,
                    'pdar_id' => $p_id,
                ];

                // Then you can call the update function:
                $result = $this->pm->update_table($table, $params, $where);  //todo

                if ($result <= 0) {
                    $this->db->trans_rollback();

                    log_message("error", "#FPCDP017 Updation failed in chitha_dag_pattadar for dist:"
                        . $dist_code . ", petition no: " . $petition_no);

                    return $this->generateFunRespData(false, "FPCDP017: Unable to pass order !");
                }

                if ($cPattadarExists == 0) {
                    //var_dump ($chitha_pattadar);
                    // $tstatus_pat = $this->db->insert("chitha_pattadar", $chitha_pattadar); // ********************
                    $chitha_pattadar['f1_case_no'] = $case_no;
                    $tstatus_pat = $this->pm->insert_table('chitha_pattadar', $chitha_pattadar);  //todo

                    if ($tstatus_pat != 1) {
                        $this->db->trans_rollback();

                        log_message("error", "#FPCP0017 Failed to insert chitha_pattadar for dist:"
                            . $dist_code . ", petition no: " . $petition_no);

                        return $this->generateFunRespData(false, "FPCDP017: Unable to pass order !");
                    }
                }

                $today = date('Y-m-d');
                $t_occup_query = "update t_chitha_col8_occup set iscorrected_inco='Y',iscorrected_inco_date='$corrected',order_passed='Y' where dist_code='$dist_code' and subdiv_code='$subdiv_code' and "
                    . "cir_code='$cir_code' and lot_no='$lot_no' and mouza_pargona_code='$mouza_pargona_code' and "
                    . "vill_townprt_code='$vill_code' and petition_no=$petition_no and dag_no='$dag_no' ";

                $this->db->query($t_occup_query); // ********************

                if ($this->db->affected_rows() <= 0) {
                    $this->db->trans_rollback();

                    log_message("error", "#FPTCC018 Failed to update iscorrected_inco in t_chitha_col8_occup for dist:"
                        . $dist_code . ", petition no: " . $petition_no);

                    return $this->generateFunRespData(false, "FPTCC018: Unable to pass order !");
                }
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;  //todo
        } else {
            #$this->db->trans_commit();
            $order_update_query = "update field_mut_basic set order_passed='Y' where dist_code='$dist_code' and subdiv_code='$subdiv_code' and "
                . "cir_code='$cir_code' and lot_no='$lot_no' and mouza_pargona_code='$mouza_pargona_code' and "
                . "vill_townprt_code='$vill_code' and petition_no='$petition_no'";
            $this->db->query($order_update_query);

            if ($this->db->affected_rows() <= 0) {
                $this->db->trans_rollback();

                log_message("error", "#FPFINAL001 Failed to update order_passed in field_mut_basic for dist:"
                    . $dist_code . ", petition no: " . $petition_no);

                return $this->generateFunRespData(false, "FPFINAL001: Unable to pass order !");
            }

            return $this->generateFunRespData(true, "Success!");
        }
    }


    // Validation callback for file input
    public function file_check($str)
    {
        $allowed_mime_types = array(
            'application/pdf',
            'image/gif',
            'image/jpeg',
            'image/pjpeg',
            'image/png',
            'image/x-png',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        );

        if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != "") {
            $mime = get_mime_by_extension($_FILES['file']['name']);

            if (in_array($mime, $allowed_mime_types)) {
                return TRUE;
            } else {
                $this->form_validation->set_message('file_check', 'Please select only PDF, Image, or Document files.');
                return FALSE;
            }
        } else {
            $this->form_validation->set_message('file_check', 'Please choose a file to upload.');
            return FALSE;
        }
    }

    function saveCoPartitionSupportiveDocs()
    {
        // Get user data
        $data['user_code'] = $this->tokenData->usercode;
        $data['dist_code'] = $this->tokenData->dcode;
        $data['subdiv_code'] = $this->tokenData->subdiv_code;
        $data['cir_code'] = $this->tokenData->cir_code;
        $data['user_desig_code'] = $this->tokenData->user_desig_code;

        // Check authorization
        if ($this->tokenData->user_desig_code !== 'CO') {
            return $this->generateResponse(false, "Unauthorized!");
        }

        // Get input data
        $caseNo = $this->input->post('case_no', true);
        $id = $this->input->post('id', true);           // file sl number
        $id += 1;

        $fileName = $this->input->post('name', true);   // user define file name

        // Validate inputs
        if (empty($_FILES) || empty($id) || empty($fileName) || empty($caseNo)) {
            return $this->generateResponse(false, "Please provide all required fields!");
        }

        // Get case details
        $caseData = [
            'case_num' => $caseNo,
            'user_code' => $data['user_code'],
            'dist_code' => $data['dist_code'],
            'subdiv_code' => $data['subdiv_code'],
            'cir_code' => $data['cir_code'],
            'user_desig_code' => $data['user_desig_code']
        ];

        $resultFmbDetails = $this->pm->getFieldMutBasicDetails($caseData);

        if (!$resultFmbDetails) {
            return $this->generateResponse(false, "Case details not found!");
        }

        // Validate petition number
        if (empty($resultFmbDetails->petition_no)) {
            return $this->generateResponse(false, "Petition number is required!");
        }

        // Load libraries
        $this->load->library('form_validation');
        $this->load->library('upload');
        $this->load->helper('file');

        // Setup upload validation
        $this->form_validation->set_rules('file', 'File', 'callback_file_check');

        if ($this->form_validation->run() !== TRUE) {
            return $this->generateResponse(false, $this->form_validation->error_string());
        }

        // Create upload folder if not exists
        $folder = UPLOAD_PARTITION_DIR . $data['dist_code'] . UPLOAD_SEPARATOR . 'PART/';

        if (!file_exists($folder)) {
            if (!mkdir($folder, 0777, true)) {
                return $this->generateResponse(false, "Failed to create upload directory!");
            }
        }

        // Configure upload
        $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
        $fileName_generated = $resultFmbDetails->petition_no . '_' . $id . '.' . $ext;

        $config = [
            'upload_path' => './' . $folder,
            'allowed_types' => FILE_TYPE,
            'max_size' => MAX_SIZE * 1024,
            'file_name' => $fileName_generated,
            'overwrite' => false
        ];

        $this->upload->initialize($config);

        // Check if file already exists
        $existingFile = $this->db
            ->where([
                'case_no' => $caseNo,
                'user_code' => $data['user_code'],
                'doc_flag' => $id
            ])
            ->get('supportive_document')
            ->row();


        if ($existingFile) {
            //unlink existing file
            unlink($existingFile->file_path);
        }

        // Upload file
        if (!$this->upload->do_upload('file')) {
            return $this->generateResponse(false, $this->upload->display_errors());
        }

        $uploadData = $this->upload->data();
        $filePath = $folder . $fileName_generated;

        // Prepare file data
        $fileData = [
            'case_no' => $caseNo,
            'file_name' => $fileName,
            'user_code' => $data['user_code'],
            'fetch_file_name' => $fileName_generated,
            'file_type' => $uploadData['file_type'],
            'file_path' => $filePath,
            'date_entry' => date('Y-m-d H:i:s'),
            'mut_type' => 'FP',
            'doc_flag' => $id
        ];

        try {
            if ($existingFile) {

                // Update existing file
                $this->db->where([
                    'case_no' => $caseNo,
                    'doc_flag' => $id,
                    'user_code' => $data['user_code']
                ]);
                $this->db->update('supportive_document', $fileData);

                if ($this->db->affected_rows() === 0) {
                    return $this->generateResponse(false, "Failed to update file record!");
                }

                $response = [
                    'img_upload' => true,
                    'flag_set' => $existingFile->id,
                    'doc_id' => $existingFile->id,
                    'filename' => $fileName
                ];

            } else {
                // Insert new file record
                $this->db->insert('supportive_document', $fileData);

                if ($this->db->affected_rows() === 0) {
                    return $this->generateResponse(false, "Failed to save file record!");
                }

                $insertedId = $this->db->insert_id();

                $response = [
                    'img_upload' => true,
                    'doc_id' => $insertedId,
                    'filename' => $fileName
                ];
            }

            return $this->generateResponse($response, "File uploaded successfully!");

        } catch (Exception $e) {
            return $this->generateResponse(false, "Error: " . $e->getMessage());
        }
    }


    function removeCoPartitionSupportiveDocs()
    {
        $data['user_desig_code'] = $this->tokenData->user_desig_code;

        // Check authorization
        if ($this->tokenData->user_desig_code !== 'CO') {
            return $this->generateResponse(false, "Unauthorized!");
        }

        // Get input data
        $caseNo = $this->input->post('case_no', true);
        $id = $this->input->post('id', true);           // file sl number
        $id += 1;

        $fileName = $this->input->post('name', true);   // user define file name

        // Validate inputs
        if (empty($id) || empty($fileName) || empty($caseNo)) {
            return $this->generateResponse(false, "Please provide all required fields!");
        }

        // Get case details
        $caseData = [
            'case_no' => $caseNo,
            'doc_flag' => $id,
        ];

        $getFile = $this->pm->getParticularCaseSupportiveDocs($caseData);

        if (!$getFile) {
            return $this->generateResponse(false, "File not found!");
        }

        $isDeleted = $this->pm->deleteParticularCaseSupportiveDoc($caseData);

        if ($isDeleted) {
            //unlink existing file
            unlink($getFile->file_path);

            return $this->generateResponse($getFile, "File deleted successfully!");
        }


        return $this->generateResponse(false, "Error: File could not be deletd !");
    }


    /**
     * OLD Method For Reference.
     */
    public function finalOrderFieldPartitionCOSave()
    {
        //xss & security validation starts
        $errorMessageStr = '';
        $resp = checkRequestSpecChar($_POST, [], [], ['inFavourGurd' => true]);
        if ($resp['status'] == 'n') {
            $errorMessageStr .= $resp['messages'];
        }
        $resp = checkRequestValidQuery($_POST);
        if ($resp['status'] == 'n') {
            $errorMessageStr .= $resp['messages'];
        }

        // $resp = $this->checkuthenticationAndValidationFpart($_POST);
        // if($resp['responseType'] == 1)
        // {
        //    $errorMessageStr .= $resp['message'];
        // }      

        if ($errorMessageStr != '') {
            $this->session->set_flashdata('message', $errorMessageStr);
            return redirect($_SERVER['HTTP_REFERER']);
        }
        //xss & security validation ends 
        if ($this->session->userdata('user_desig_code') != 'CO') {
            echo "<p class='text-danger'>Error. You are not authorized</p>";
            return;
        }
        $dist_code = $this->input->post('dist_code');
        $cir_code = $this->input->post('cir_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $vill_townprt_code = $this->input->post('vill_townprt_code');

        $case_no = $this->input->post('case_no');
        $this->AgriStackCaseHistory->CreateLogFile($dist_code, $case_no);
        $new_dag = trim($this->input->post('sugg_dag_no'));
        $new_patta = trim($this->input->post('sugg_patta_no'));
        $dag_revenue = $this->input->post('dag_revenue');
        $dag_local_tax = $this->input->post('dag_local_tax');
        $bigha = $this->input->post('bigha_applied');
        $katha = $this->input->post('katha_applied');
        $lessa = $this->input->post('lessa_applied');

        $old_dag_bc = null;

        $caseInfoEsc = $this->db->query("SELECT * FROM field_mut_basic WHERE case_no=?", array($case_no))->row();
        if ($caseInfoEsc->es_flag == 1 && ESCALATION_ENABLE == 1) {
            $executionDate = date('Y-m-d- H-i-s');
            $user_code = $$user_code;
            $user_desig_code = $this->session->userdata('user_desig_code');
            $escalationUpdateTimeFrame = $this->Escalationmodel->escalationUpdateTimeFrame($executionDate, $dist_code, $case_no, $user_code, $user_desig_code, 'FPART');
            log_message("error", "#ESC8432, transaction-error-STATUS======" . json_encode($escalationUpdateTimeFrame));
            if ($escalationUpdateTimeFrame['responseType'] == 1) {
                log_message("error", "#ESC8435, transaction-error in method 'Partition/finalOrderFieldPartitionCOSave' with case-no :" . $case_no);
                $this->session->set_flashdata('message', "Something went wrong.ACPP- Error Code(#ESC8435)");
                redirect(base_url() . "index.php/home");
                return;
            }
            ////////////////////END////////////////////////////
        }


        if (ENABLED_BLOCKCHAIN == 1 && in_array($this->session->userdata('dist_code'), json_decode(ENABLED_BLOCKCHAIN_FOR_DIST))) {

            //==========check dag pending in blockchain or not=================
            $this->load->model('propChain/PropChainCommonModel');
            $oldDagNo = $this->input->post('old_dag');
            $checkVal = $this->PropChainCommonModel->checkDagExistsInPropChainInPending($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $oldDagNo);
            if ($checkVal === false) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('message', "#ERRORBLOCCHAIN7303 : You cannot procced as dag no is pending for property chain update...");
                redirect(base_url() . "index.php/home");
            }


            ///=============end CODE=====================




            ////////////////////////////////////////////////////// property chain code ////////////////////////////////////
            $ulpin = $this->input->post('ulpin');
            $old_dag_bc = $this->input->post('old_dag');
            // get the dag details for the full dag partion before chitha update



            $get_old_revenue = $this->PropChainModel->getDagRevenue($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $this->input->post('old_patta'), $this->input->post('old_dag'));
            ///////////////////////////////////////////////////////////////////////////////////////////////////////// 

        }
        // var_dump($pattadar_details);exit;

        if (empty($new_patta) || $new_patta == null) {
            echo "<p class='text-danger'>Error. Patta no cann't be empty</p>";
            return;
        }
        if (in_array($this->session->userdata('dist_code'), json_decode(BARAK_VALLEY))) {
            $ganda = $this->input->post('ganda_applied');
        }

        $date = date('Y-m-d');

        // if (($this->input->post('check_count') == '0') && ($this->input->post('land_area_check') != '0')) {
        //     $this->session->set_flashdata('message', "For all selected pattadar, dag with partial area partition is not allowed");
        //     redirect(base_url() . "index.php/home");
        //     return;
        // }

        $this->db->trans_begin();

        //ESCALATION ==============
        $es_flag_data = $this->db->query("select es_flag,out_of_esc from  field_mut_basic where case_no=?", array($case_no))->row();
        if (ESCALATION_ENABLE == 1 && $es_flag_data->es_flag == 1 && $es_flag_data->out_of_esc == 0) {

            $responseEsc = $this->Escalationmodel->escalationRemarkCheckandUpdate($case_no, $this->input->post('esc_remark'), $this->session->userdata('user_desig_code'));
            if ($responseEsc['responseType'] == 1) {
                $this->db->trans_rollback();
                $data = array(
                    'error' => "#ERRESCREMARKPART00111 : Error in submitting in escalation remarks. Please try Again"
                );
                echo json_encode($data);
                return false;
            }

        }
        ///END+==================
        //////////////////////////////////
        if (isset($_FILES['fileUpload']['name'])) {
            $this->form_validation->set_rules('fileText[]', 'Document Details', 'trim|xss_clean|required');
            $fileCount = count($_FILES['fileUpload']['name']);
            // validation for file type and file size
            for ($i = 0; $i < $fileCount; $i++) {
                if ($_FILES['fileUpload']['name'][$i] && $_FILES['fileUpload']['size'][$i] && $_FILES['fileUpload']['tmp_name'][$i]) {
                    $name = $_FILES['fileUpload']['name'][$i];
                    $size = $_FILES['fileUpload']['size'][$i];
                    $mime = mime_content_type($_FILES['fileUpload']['tmp_name'][$i]);
                    $exp = explode("/", $mime);
                    $ext = $exp[1];
                    if ($name != NULL) {
                        if ($ext == NULL) {
                            // todo error show extension missing
                            $this->session->set_flashdata('message', "File Not Supported. Error Code(#FAPL001)");
                            redirect(base_url() . "index.php/home");
                        }
                        if (!in_array($ext, UPLOAD_TYPE_VALIDATION)) {
                            // todo error show file allow type not match
                            $this->session->set_flashdata('message', "File Not Supported (ONLY JPG/PNG/PDF). Error Code(#FAPL002)");
                            redirect(base_url() . "index.php/home");
                        }
                        if ($size > UPLOAD_MAX_SIZE) {
                            $this->session->set_flashdata('message', "Maximum 2MB file size. Error Code(#FAPL003)");
                            redirect(base_url() . "index.php/home");
                        }
                    } else {
                        $this->session->set_flashdata('message', "File name cann't be empty. Error Code(#FAPL004)");
                        redirect(base_url() . "index.php/home");
                    }
                } else {
                    $this->session->set_flashdata('message', "File is required. Error Code(#FAPL005)");
                    redirect(base_url() . "index.php/home");
                }
            }
        }
        ///////////////////Insert attached file////////////////////////
        if (isset($_FILES['fileUpload']['name'])) {
            for ($i = 0; $i < $fileCount; $i++) {
                $_FILES['file']['name'] = $_FILES['fileUpload']['name'][$i];
                $_FILES['file']['type'] = $_FILES['fileUpload']['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES['fileUpload']['tmp_name'][$i];
                $_FILES['file']['error'] = $_FILES['fileUpload']['error'][$i];
                $_FILES['file']['size'] = $_FILES['fileUpload']['size'][$i];
                $mime = mime_content_type($_FILES['fileUpload']['tmp_name'][$i]);
                $exp = explode("/", $mime);
                $onlyExtension = $exp[1];
                $replaceCase = str_replace("/", "-", $case_no);
                $fileRename = $replaceCase . "-" . time() . '.' . $onlyExtension;
                $config['upload_path'] = MANUAL_ATTACHMENT;
                $config['allowed_types'] = UPLOAD_ALLOW_TYPE;
                $config['max_size'] = UPLOAD_MAX_SIZE;
                ;
                $config['file_name'] = $fileRename;
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if ($this->upload->do_upload('file')) {
                    $document = array(
                        'case_no' => $case_no,
                        'file_name' => $_POST['fileText'][$i],
                        'user_code' => $$user_code,
                        // 'fetch_file_name' => $_FILES['file']['name'],
                        'fetch_file_name' => $_POST['fileText'][$i],
                        'file_type' => $_FILES['file']['type'],
                        'file_path' => MANUAL_ATTACHMENT . $fileRename,
                        'date_entry' => date('Y-m-d h:i:s'),
                        'mut_type' => 'FP',
                    );
                    // save data in attachment file
                    $addMoreDocQuery = $this->db->insert('supportive_document', $document);
                    if ($addMoreDocQuery != 1) {
                        $this->db->trans_rollback();
                        log_message('error', '#ERRADDDOC0001: Insertion failed in supportive document RTPS Case No ' . $case_no);
                        $this->session->set_flashdata('error_data', "#ERRADDDOC0001: Registration of Settlement failed for case no : " . $case_no);
                        redirect(base_url() . "index.php/home");
                        return false;
                    }
                } else {
                    $this->db->trans_rollback();
                    // todo error show
                    // redirect to respected route with error mgs
                    log_message('error', '#ERRADDDOC0001: Insertion failed in supportive document RTPS Case No ' . $case_no);
                    $this->session->set_flashdata('error_data', "#ERRADDDOC0001: Registration of Settlement failed for case no : " . $case_no);
                    redirect(base_url() . "index.php/home");
                    return false;
                }
            }
        }
        //////////////////////////////////////////

        $occup_query = "SELECT mp.dist_code, mp.subdiv_code, mp.cir_code, mp.mouza_pargona_code,
        mp.lot_no, mp.vill_townprt_code, mp.pdar_id, mp.year_no, mp.petition_no, mp.pdar_add1,
        mp.pdar_add2, mp.pdar_name, mp.pdar_guardian, mp.pdar_rel_guar, dd.patta_no,
        dd.patta_type_code, mp.pdar_dag_por_b, mp.pdar_dag_por_k, mp.pdar_dag_por_lc, dd.dag_no 
        FROM field_part_petitioner mp 
        JOIN field_mut_dag_details dd ON mp.cir_code=dd.cir_code AND mp.case_no = dd.case_no
        WHERE mp.case_no=? AND mp.cir_code=? AND mp.subdiv_code=? AND mp.mouza_pargona_code=? 
        AND mp.lot_no=? AND mp.vill_townprt_code=? limit 1";
        $petitioner_save = $this->db->query($occup_query, array(
            $case_no,
            $cir_code,
            $subdiv_code,
            $mouza_pargona_code,
            $lot_no,
            $vill_townprt_code
        ));
        // echo $this->db->last_query();
        // return;

        if ($petitioner_save == null || $petitioner_save->num_rows() <= 0) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('message', "Could not get petitioner details. Error Code(#ERRFP001)");
            log_message("error", "#ERRFP001: No Petitioner found in field_part_petitioner 
                for dist:" . $dist_code . ", case: " . $case_no);
            redirect(base_url() . "index.php/home");
            return;
        }
        $petitioner_save = $petitioner_save->row();


        $occup_data = "SELECT * FROM field_part_petitioner WHERE case_no=? AND 
            mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND 
            dist_code=? AND subdiv_code=? AND cir_code=?";
        $occup_data = $this->db->query($occup_data, array(
            $case_no,
            $mouza_pargona_code,
            $lot_no,
            $vill_townprt_code,
            $dist_code,
            $subdiv_code,
            $cir_code
        ));


        if ($occup_data == null || $occup_data->num_rows() <= 0) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('message', "Could not get petitioner details. Error Code(#ERRFP002)");
            log_message("error", "#ERRFP002: No Petitioner found in field_part_petitioner 
                for dist:" . $dist_code . ", case: " . $case_no);
            redirect(base_url() . "index.php/home");
            return;
        }
        $occup_data = $occup_data->result();

        $get_mut_type = $this->db->query("SELECT * FROM field_mut_basic 
            WHERE case_no=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? 
            AND dist_code=? AND subdiv_code=? AND cir_code=?",
            array(
                $case_no,
                $mouza_pargona_code,
                $lot_no,
                $vill_townprt_code,
                $dist_code,
                $subdiv_code,
                $cir_code
            )
        )->row();

        //Field Partition
        if ($get_mut_type->mut_type == '02') {
            $new_pattadar = 'N';
            $sql = "SELECT patta_type_code, dag_no FROM field_mut_dag_details WHERE case_no=?";
            $dd = $this->db->query($sql, $case_no);
            if ($dd == null || $dd->num_rows() <= 0) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('message', "Could not get petitioner details. Error Code(#ERRFP003)");
                log_message("error", "#ERRFP003 No petitioner found in field_mut_dag_details 
                    for dist:" . $dist_code . ", case: " . $case_no);
                redirect(base_url() . "index.php/home");
                return;
            }
            $dd = $dd->row();
            $pp_code = $dd->patta_type_code;
            $old = $dd->dag_no;

            $sql = "SELECT COUNT(*) AS d FROM chitha_basic WHERE mouza_pargona_code=? 
                AND lot_no=? AND vill_townprt_code=? AND dist_code=? AND subdiv_code=? 
                AND cir_code=? AND dag_no=? AND dag_no!=? ";
            $count = $this->db->query($sql, array(
                $mouza_pargona_code,
                $lot_no,
                $vill_townprt_code,
                $dist_code,
                $subdiv_code,
                $cir_code,
                $new_dag,
                $old
            ))->row()->d;
            if ($count != null && $count > 0) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('message', "The Dag no you have given already exist ! Please re-verify the dag no again (#ERRFP004)");
                log_message("error", "#ERRFP004 No petitioner in chitha_basic for 
                    dist:" . $dist_code . ", case: " . $case_no);
                redirect(base_url() . "index.php/home");
                return;
            }

            $sql = "SELECT COUNT(*) AS c FROM chitha_pattadar WHERE 
                mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND dist_code=? 
                AND subdiv_code=? AND cir_code=? AND patta_no=? AND patta_type_code=? ";
            $count = $this->db->query($sql, array(
                $mouza_pargona_code,
                $lot_no,
                $vill_townprt_code,
                $dist_code,
                $subdiv_code,
                $cir_code,
                $new_patta,
                $pp_code
            ))->row()->c;

            // if($count != null && $count>0){                
            //     $this->db->trans_rollback();
            //     $this->session->set_flashdata('message', "The patta no you have selected already exist pattadar (#ERRFP005)");
            //     log_message("error","#ERRFP005 No detail available in chitha_pattadar 
            //         for dist:".$dist_code.", case: ". $case_no);
            //     redirect(base_url() . "index.php/home");
            //     return;
            // }
        } else {
            $new_pattadar = '';
        }

        //var_dump($occup_data);
        foreach ($occup_data as $occup) {

            if (in_array($this->session->userdata('dist_code'), json_decode(BARAK_VALLEY))) {
                $occup_ganda = $occup->pdar_dag_por_g;
            } else {
                $occup_ganda = '0';
                $ganda = '0';
            }
            $dec = null;
            if (isset($occup->self_declaration) && $occup->self_declaration != null) {
                $dec = $occup->self_declaration;
            }
            if ($occup->auth_type != null) {
                /*if($occup->auth_type=='AADHAAR' && $occup->photo == null){
                    $this->db->trans_rollback();
                    log_message('error', '#ERRFMUTI005 : Aadhaar Photo fetching error');
                    $this->session->set_flashdata('message', "#ERRFMUTI005:Aadhaar fetching error-img");
                    redirect(base_url() . "index.php/home");
        }*/
                $auth_type = $occup->auth_type;
                $id_ref_no = $occup->id_ref_no;
                //$photo     = $occup->photo;
                $photo = null;
            } else {
                $auth_type = null;
                $id_ref_no = null;
                $photo = null;
            }

            $t_chitha_col8_occup = array(
                'dist_code' => $occup->dist_code,
                'subdiv_code' => $occup->subdiv_code,
                'cir_code' => $occup->cir_code,
                'mouza_pargona_code' => $occup->mouza_pargona_code,
                'lot_no' => $occup->lot_no,
                'vill_townprt_code' => $occup->vill_townprt_code,
                'dag_no' => $occup->dag_no,//$new_dag, //should be the new dag
                'year_no' => $occup->year_no,
                'petition_no' => $occup->petition_no,
                'occupant_id' => $occup->pdar_cron_no,
                'patta_type_code' => $occup->patta_type_code,
                'patta_no' => $occup->patta_no,//$new_patta,  //should be the new patta no
                'pdar_id' => $occup->pdar_id,
                'occupant_name' => $occup->pdar_name,
                'occupant_fmh_name' => $occup->pdar_guardian,
                'occupant_fmh_flag' => $occup->pdar_rel_guar,
                'occupant_add1' => $occup->pdar_add1,
                'occupant_add2' => $occup->pdar_add2,
                'land_area_b' => $occup->pdar_dag_por_b,
                'land_area_k' => $occup->pdar_dag_por_k,
                'land_area_lc' => $occup->pdar_dag_por_lc,
                'land_area_g' => $occup_ganda,
                'land_area_kr' => '0',
                'old_patta_no' => $occup->patta_no,
                'new_patta_no' => $new_patta,
                'old_dag_no' => $occup->dag_no,
                'new_dag_no' => $new_dag,
                'new_pattadar' => $new_pattadar,
                'revenue' => $dag_revenue,
                'self_declaration' => $dec,
                'auth_type' => $auth_type,
                'id_ref_no' => $id_ref_no,
                'photo' => $photo
            );
            //var_dump($t_chitha_col8_occup);
            $tstatus1 = $this->db->insert("t_chitha_col8_occup", $t_chitha_col8_occup); //****************************
            if ($tstatus1 != 1) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('message', "Error in Processing. Please try Again. Error Code(#ERRFP006)");
                log_message("error", "#ERRFP006 Nill petition from t_chitha_col8_occup for dist:"
                    . $dist_code . ", case: " . $case_no);
                redirect(base_url() . "index.php/home");
            }
            // ////////////Set Patta For JamaUpdation 14/10/2020/////////////////
            $patta_no = $new_patta;
            $patta_type_code = $occup->patta_type_code;
            // ////////////////////////////
        }


        $insert_t_chitha_col8_order = array(
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'mouza_pargona_code' => $mouza_pargona_code,
            'lot_no' => $lot_no,
            'vill_townprt_code' => $vill_townprt_code,
            'dag_no' => $this->input->post('old_dag'),
            'year_no' => date('Y'),
            'petition_no' => $get_mut_type->petition_no,
            'order_pass_yn' => 'y',
            'order_type_code' => '02',
            'nature_trans_code' => $get_mut_type->trans_code,
            'lm_code' => $this->input->post('lm_code'),
            'lm_sign_yn' => 'y',
            'lm_note_date' => date('Y-m-d', strtotime($this->input->post('lm_date'))),
            'co_code' => $this->input->post('co_code'),
            'co_sign_yn' => 'y',
            'co_ord_date' => date('Y-m-d h:i:s'),
            'iscorrected_inco' => 'y',
            'iscorrected_inco_date' => date('Y-m-d h:i:s'),
            'mut_land_area_b' => $this->input->post('bigha_applied'),
            'mut_land_area_k' => $this->input->post('katha_applied'),
            'mut_land_area_lc' => $this->input->post('lessa_applied'),
            'mut_land_area_g' => $ganda,
            'mut_land_area_kr' => '0',
            'land_area_left_b' => $this->input->post('bigha'),
            'land_area_left_k' => $this->input->post('katha'),
            'land_area_left_lc' => $this->input->post('lessa'),
            'land_area_left_g' => $ganda,
            'land_area_left_kr' => '0',
            'rajah_adalat' => $get_mut_type->rajah_adalat,
            'case_no' => $case_no,
            'min_revenue' => $this->input->post('dag_revenue'),
        );
        $instChithaCol8Order = $this->db->insert("t_chitha_col8_order", $insert_t_chitha_col8_order);
        if ($instChithaCol8Order != 1) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('message', "Error in Processing. Please try Again. Error Code(#ERRFP009)");
            log_message("error", "#ERRFP009 unable to insert into t_chitha_col8_order for dist:"
                . $dist_code . ", case: " . $case_no);
            redirect(base_url() . "index.php/home");
            return;
        }



        $dist_code = $petitioner_save->dist_code;
        $subdiv_code = $petitioner_save->subdiv_code;
        $cir_code = $petitioner_save->cir_code;
        $mouza_pargona_code = $petitioner_save->mouza_pargona_code;
        $lot_no = $petitioner_save->lot_no;
        $vill_townprt_code = $petitioner_save->vill_townprt_code;
        $dag_no = $petitioner_save->dag_no;
        $petition_no = $petitioner_save->petition_no;

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $db_debug = $this->db->db_debug;
            $this->db->db_debug = TRUE;
            //echo $this->db->_error_message();
            $this->db->db_debug = $db_debug;
            $url = "<a href='cofieldmutation/pendingmaps' class='text-success'>Kindly Click Here to Remove Temporary Data </a>";
            $this->session->set_flashdata("message", "Order Cannot be passed. Error Code [T-TABLE_HAS_DATA] . Contact help desk with case no. $url");
            redirect(base_url() . "index.php/home");
            return;
        } else {
            $this->session->set_flashdata("message", "Order passed. Case Pending with mandal for parition Map Correction");
        }

        $this->load->model('jamabandi/jamabandiAutoUpdateModel');

        if ($occup->dag_no == $new_dag) {
            $ok = $this->autoUpdate_fulldag_field($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $petition_no, $dag_no);
            $this->jamabandiAutoUpdateModel->jamaCheckToDeleteorNot(
                $dist_code,
                $subdiv_code,
                $cir_code,
                $mouza_pargona_code,
                $lot_no,
                $vill_townprt_code,
                $occup->dag_no,
                $this->input->post('old_patta'),
                $this->input->post('patta_code')
            );
        } else {
            $ok = $this->autoUpdateForField($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $petition_no, $occup->dag_no);
            //var_dump($ok);
            // return;
            //die();
            if ($ok != true) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('message', "Chitha Could not be updated. Please try Again. Error Code(#ERRFP010)");
                log_message("error", "#ERRFP010 unable to update chitha from autoUpdate dist:"
                    . $dist_code . ", case: " . $case_no);
                redirect(base_url() . "index.php/home");
                return;
            }

            $order_date = date('Y-m-d');
            $this->db->query("UPDATE field_mut_basic SET order_passed=?, date_of_order=? 
                WHERE case_no=? ", array('y', $order_date, $case_no));
            if ($this->db->affected_rows() <= 0) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('message', "Chitha Could not be updated. Please try Again. Error Code(#ERRFP011)");
                log_message("error", "##ERRFP011 unable to update chitha from autoUpdate dist:"
                    . $dist_code . ", case: " . $case_no);
                redirect(base_url() . "index.php/home");
                return;
            }

            $this->db->query("UPDATE t_chitha_col8_order SET order_passed=?, 
                date_of_order=? WHERE case_no=? ", array('y', $order_date, $case_no));
            if ($this->db->affected_rows() <= 0) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('message', "Chitha Could not be updated. Please try Again. Error Code(#ERRFP012)");
                log_message("error", "##ERRFP012 unable to update chitha from autoUpdate dist:"
                    . $dist_code . ", case: " . $case_no);
                redirect(base_url() . "index.php/home");
                return;
            }
        }

        $rmrk = 'CO order';

        $proInsert = $this->mutationmodel->proceeding_order($case_no, $rmrk);


        if ($proInsert == false || $proInsert === false) {
            log_message('error', "#OPARTCO001:" . $this->db->last_query());
            $this->db->trans_rollback();
            $this->session->set_flashdata('message', "Updation failed(#OPARTCO001)" . $case_no);
            redirect(base_url() . "index.php/home");
        }

        if ($ok) {

            //ESCALATION CODE INTEGRATION================SANMRI
            $dist_code = $this->session->userdata('dist_code');
            $subdiv_code = $this->session->userdata('subdiv_code');
            $cir_code = $this->session->userdata('cir_code');
            $query1 = $this->db->query(
                "SELECT es_flag,out_of_esc FROM field_mut_basic WHERE case_no=?",
                array($case_no)
            )->row();
            $user_code = $$user_code;
            $executionDate = $this->input->post('executionDate');
            if ($query1->es_flag == 1 && ESCALATION_ENABLE == 1 && $query1->out_of_esc == 0) {

                $escalationUpdateStatus = $this->Escalationmodel->escalationFinalOrderCOFPART($executionDate, $dist_code, $subdiv_code, $cir_code, $case_no, $user_code);
                log_message("error", "#ESC8610, transaction-error-STATUS======" . json_encode($escalationUpdateStatus));

                if ($escalationUpdateStatus['responseType'] == 0) {
                    $this->db->trans_rollback();
                    log_message("error", "#ESC8610, transaction-error in method 'cofieldmutation/finalorderCO' with case-no :" . $case_no);
                    $this->session->set_flashdata('message', "Something went wrong. Error Code(#ESC8610)");
                    redirect(base_url() . "index.php/home");
                }
            }

            //////////////////////////////////////////////////////////////////////////////////////////
            ///////////////////////////////////////Property Chain Code////////////////////////////////
            //////////////////////////////////////////////////////////////////////////////////////////




            if (ENABLED_BLOCKCHAIN == 1 && in_array($this->session->userdata('dist_code'), json_decode(ENABLED_BLOCKCHAIN_FOR_DIST))) {

                $pattadar_details = $this->PropChainModel->getPattadars($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $new_patta, $this->input->post('old_dag'));

                if ($old_dag_bc != null && ($new_dag != $old_dag_bc)) {
                    // $checkValidate = true;
                    $checkValidate = $this->PropChainCommonModel->checkValidateMapDagForOrderPass($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $new_dag, $old_dag_bc);

                    if ($checkValidate > 0) {
                        $this->db->trans_rollback();
                        $this->session->set_flashdata('message', "ERROR7711 : Update map is pending for dag no ($new_dag,$old_dag_bc)==" . $case_no);
                        redirect(base_url() . "index.php/home");
                    }
                }

                $ulpinFlag = $this->input->post('ulpinCheckFlag');
                $compareFlag = $this->input->post('compareCheckFlag');


                if ($compareFlag == 'Y' && $ulpinFlag == 1) {
                    $type = LOC_TYPE_RURAL;
                    $old_dag = $this->input->post('old_dag');
                    $old_patta = $this->input->post('old_patta');
                    $old_ulpin = $this->input->post('old_ulpin');
                    if (!isset($old_ulpin))
                        $old_ulpin = "";
                    $new_dag_chain = $new_dag;
                    $new_patta_chain = $new_patta;

                    $patta_type_code = $this->input->post('patta_code');
                    $reference_id = $case_no;
                    $certmnemonic = CERTMNEMONIC_PRT;
                    $property_signature = "base64 encoded signature";
                    $property_signer_key = "base64 encoded public key";
                    $office_code = $this->session->userdata('cir_code');
                    $user_code = $$user_code;


                    $dag_query = "select m_dag_area_b, m_dag_area_k, m_dag_area_lc, m_dag_area_g, m_dag_area_kr, dag_area_b, dag_area_k, dag_area_lc, dag_area_g, dag_area_kr from  field_mut_dag_details where case_no='$case_no' ";

                    $dag_details = $this->db->query($dag_query)->row();
                    $bigha_chain_source = $dag_details->dag_area_b;
                    $katha_chain_source = $dag_details->dag_area_k;
                    $lessa_chain_source = $dag_details->dag_area_lc;
                    $ganda_chain_source = $dag_details->dag_area_g;

                    $bigha_chain_new = $dag_details->m_dag_area_b;
                    $katha_chain_new = $dag_details->m_dag_area_k;
                    $lessa_chain_new = $dag_details->m_dag_area_lc;
                    $ganda_chain_new = $dag_details->m_dag_area_g;


                    if (in_array($this->session->userdata('dist_code'), json_decode(BARAK_VALLEY))) {
                        $source_lessa = $bigha_chain_source * 6400 + $katha_chain_source * 320 + $lessa_chain_source * 20 + $ganda_chain_source;
                        $partition_lessa = $bigha_chain_new * 6400 + $katha_chain_new * 320 + $lessa_chain_new * 20 + $ganda_chain_new;

                        $remaining_lessa = $source_lessa - $partition_lessa;

                        $remaining_b = floor($remaining_lessa / 6400);
                        $remaining_k = floor(($remaining_lessa - $remaining_b * 6400) / 320);
                        $remaining_lc = floor(($remaining_lessa - $remaining_b * 6400 - $remaining_k * 320) / 20);
                        $remaining_g = $remaining_lessa - $remaining_b * 6400 - $remaining_k * 320 - $remaining_lc * 20;
                        $remaining_kr = 0;

                    } else {

                        $source_lessa = $bigha_chain_source * 100 + $katha_chain_source * 20 + $lessa_chain_source;
                        $partition_lessa = $bigha_chain_new * 100 + $katha_chain_new * 20 + $lessa_chain_new;

                        $remaining_lessa = $source_lessa - $partition_lessa;

                        $remaining_b = floor($remaining_lessa / 100);
                        $remaining_k = floor(($remaining_lessa - $remaining_b * 100) / 20);
                        $remaining_lc = $remaining_lessa - $remaining_b * 100 - $remaining_k * 20;
                        $remaining_g = 0;
                        $remaining_kr = 0;
                    }


                    // $source_lessa = $bigha_chain_source * 100 + $katha_chain_source * 20 + $lessa_chain_source;

                    // $partition_lessa = $bigha_chain_new * 100 + $katha_chain_new * 20 + $lessa_chain_new;

                    // $remaining_lessa = $source_lessa - $partition_lessa;

                    // $remaining_b = floor($remaining_lessa / 100);
                    // $remaining_k = floor(($remaining_lessa - $remaining_b * 100) / 20);
                    // $remaining_lc = $remaining_lessa - $remaining_b * 100 - $remaining_k * 20;
                    // $remaining_g = 0;
                    // $remaining_kr = 0;

                    $chain_result = new \stdClass;
                    $chain_result_2 = new \stdClass;
                    $chain_result_3 = new \stdClass;
                    $chain_result_4 = new \stdClass;

                    // since the below paramaters are not applicable in partition send empty string
                    $old_land_class_code = $new_patta_type_code = "";

                    if ($occup->dag_no == $new_dag) {

                        $land_class_code_query = "select land_class_code from chitha_basic where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' and patta_no='$new_patta_chain' and dag_no='$old_dag'";

                        $land_class_code = $this->db->query($land_class_code_query)->row()->land_class_code;

                        // TODO: when the format of the property id changes the follwing attributes will be 
                        $bigha_chain_new = $katha_chain_new = $lessa_chain_new = $ganda_chain_new = '';
                        $new_dag_no = '';

                        $chain_data_params = array(
                            'pattadar_details' => $pattadar_details,
                            'dist_code' => $dist_code,
                            'subdiv_code' => $subdiv_code,
                            'cir_code' => $cir_code,
                            'mouza_pargona_code' => $mouza_pargona_code,
                            'lot_no' => $lot_no,
                            'vill_townprt_code' => $vill_townprt_code,
                            'reference_id' => $reference_id,
                            'old_dag' => $old_dag,
                            'old_patta' => $old_patta,
                            'patta_type_code' => $patta_type_code,
                            'land_class_code' => $land_class_code,
                            'remaining_b' => $bigha_chain_source,
                            'remaining_k' => $katha_chain_source,
                            'remaining_lc' => $lessa_chain_source,
                            'remaining_g' => $ganda_chain_source,
                            'certmnemonic' => $certmnemonic,
                            'property_signature' => $property_signature,
                            'property_signer_key' => $property_signer_key,
                            'office_code' => $office_code,
                            'user_code' => $user_code,
                            'ulpin' => $ulpin,
                            'old_ulpin' => $old_ulpin,
                            'dag_revenue' => $dag_revenue,
                            'dag_local_tax' => $dag_local_tax,
                            'new_patta_no' => $new_patta_chain,
                            'old_dag_revenue' => $get_old_revenue->dag_revenue,
                            'old_dag_local_tax' => $get_old_revenue->dag_local_tax,
                            'old_land_class_code' => $old_land_class_code,
                            'new_bigha' => $bigha_chain_new,
                            'new_katha' => $katha_chain_new,
                            'new_lessa' => $lessa_chain_new,
                            'new_ganda' => $ganda_chain_new,
                            'new_patta_type_code' => $new_patta_type_code
                        );

                        $fullDagProcess = $this->PropChainModel->chainFullDagProcess((object) $chain_data_params);
                        // var_dump($fullDagProcess);
                        // die;
                        $chain_result_2 = $fullDagProcess;
                    } else {

                        $property_id_update = $this->blockchainutilityclass->generatePropertyId($type, $vill_townprt_code, $old_patta, $old_dag, $ulpin);

                        $land_class_code_query_update = "select land_class_code from chitha_basic where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' and patta_no='$old_patta' and dag_no='$old_dag'";

                        $land_class_code_update = $this->db->query($land_class_code_query_update)->row()->land_class_code;
                        ///////////////////////// update property chain ///////////////////

                        $chain_data_params = array(
                            'dist_code' => $dist_code,
                            'subdiv_code' => $subdiv_code,
                            'cir_code' => $cir_code,
                            'mouza_pargona_code' => $mouza_pargona_code,
                            'lot_no' => $lot_no,
                            'vill_townprt_code' => $vill_townprt_code,
                            'old_patta' => $old_patta,
                            'old_dag' => $old_dag,
                            'patta_type_code' => $patta_type_code,
                            'reference_id' => $reference_id,
                            'land_class_code' => $land_class_code_update,
                            'remaining_b' => $remaining_b,
                            'remaining_k' => $remaining_k,
                            'remaining_lc' => $remaining_lc,
                            'remaining_g' => $remaining_g,
                            'certmnemonic' => $certmnemonic,
                            'property_signature' => $property_signature,
                            'property_signer_key' => $property_signer_key,
                            'office_code' => $office_code,
                            'user_code' => $user_code,
                            'ulpin' => $ulpin,
                            'old_ulpin' => $old_ulpin,
                            'dag_revenue' => $dag_revenue,
                            'dag_local_tax' => $dag_local_tax,
                            'new_patta_no' => $new_patta_chain,
                            'new_dag_no' => $new_dag_chain,
                            'old_dag_revenue' => $get_old_revenue->dag_revenue,
                            'old_dag_local_tax' => $get_old_revenue->dag_local_tax,
                            'old_land_class_code' => $old_land_class_code,
                            'bigha_new' => $bigha_chain_new,
                            'katha_new' => $katha_chain_new,
                            'lessa_new' => $lessa_chain_new,
                            'ganda_new' => $ganda_chain_new,
                            'new_patta_type_code' => $new_patta_type_code
                        );
                        // $this->db->trans_rollback();
                        $update_chain_api = $this->PropChainModel->chainPartialDagProcessN((object) $chain_data_params);

                        // $update_chain_api = $this->PropChainModel->chainPartialDagProcess($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $old_patta, $old_dag, $patta_type_code, $reference_id, $land_class_code_update, $remaining_b, $remaining_k,  $remaining_lc, $remaining_g, $certmnemonic, $property_signature, $property_signer_key, $office_code, $user_code, $ulpin, $old_ulpin, $dag_revenue, $dag_local_tax, $new_patta_chain, $new_dag_chain, $get_old_revenue->dag_revenue, $get_old_revenue->dag_local_tax, $old_land_class_code, $bigha_chain_new, $katha_chain_new, $lessa_chain_new, $ganda_chain_new, $new_patta_type_code);



                        $chain_result_2 = $update_chain_api;

                    }
                } else {
                    //uses for default==============
                    $chain_result_2 = new \stdClass;
                    $chain_result_2->success = 1;

                }
            } else {

                //uses for default==============
                $chain_result_2 = new \stdClass;
                $chain_result_2->success = 1;
            }

            if ($chain_result_2->success == 1) {

                //autoupdate jamabandi starts here for old patta
                $this->db->trans_commit();
                $this->AgriStackCaseHistory->CreateLog($dist_code, $case_no);
                //code for updating zonal informtion of dag------------17032023--

                //code for generating village uuid------------
                $village_uuid = $this->utilityclass->getVillageUUID($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code);

                //zonal row fetch row for insertion same row for new dag----------
                $ZonalStatus = $this->utilityclass->getZonalRowFetchAndInsert($dist_code, $village_uuid, $this->input->post('old_dag'), $new_dag);
                if ($ZonalStatus == 'N') {
                    log_message('error', '#RES001 : Zonal value of new dag could not been updated. OLD Dag :' . $this->input->post('old_dag') . " and New dag : " . $new_dag . " CASE NO : " . $case_no);
                } elseif ($ZonalStatus == 'Y') {
                    log_message('error', '#RES002 : Zonal value of new dag updated successfully. OLD Dag :' . $this->input->post('old_dag') . " and New dag : " . $new_dag . " CASE NO : " . $case_no);
                }
                //end-----------17032023




                $jamaUpdate = $this->jamabandiAutoUpdateModel->updateJamabandi(
                    $this->input->post('old_patta'),
                    $this->input->post('patta_code'),
                    $dist_code,
                    $subdiv_code,
                    $cir_code,
                    $mouza_pargona_code,
                    $lot_no,
                    $vill_townprt_code,
                    $case_no
                );
                //////////
                $this->DashboardDataFinal($case_no);
                ///////
                $basundhara = $this->basundharamodel->checkExistBasundhar($case_no);
                if ($basundhara) {
                    $rmk = 'Order passed';
                    $status = 'F';
                    $task = 'CO';
                    $pen = 'NA';
                    $case = $case_no;
                    $this->basundharamodel->postApiBasundharaSec($case, $rmk, $status, $task, $pen);
                }

                //////////////////////////////////
                $this->session->set_flashdata('message', "Order for Case No $case_no Successfully Saved.");
                $this->session->set_flashdata('message', "Chitha Has Been Updated");
                //////////////JamaBandi Update///////////////////
                $location = array(
                    'd' => $dist_code,
                    's' => $subdiv_code,
                    'c' => $cir_code,
                    'm' => $mouza_pargona_code,
                    'l' => $lot_no,
                    'v' => $vill_townprt_code,
                );
                //var_dump($location);
                $this->session->set_userdata(array('loc' => $location));
                // echo $patta_no."-".$patta_type_code;
                // exit;
                $popUpmsg = "<h4>Order for Case No $case_no Successfully Saved.Chitha has been Updated !!! Updating JamaBandi Now<h4>";
                $msgggg = "<script type='text/javascript'>alert(' " . $popUpmsg . " ');</script>";
                //echo $msgggg;
                if (ENABLED_BLOCKCHAIN == 1 && in_array($this->session->userdata('dist_code'), json_decode(ENABLED_BLOCKCHAIN_FOR_DIST))) {
                    if ($compareFlag == 'Y' && $ulpinFlag == 1) {
                        redirect('JamaBandi/step3/' . $patta_no . '/' . $patta_type_code . '/' . urlencode(base64_encode($case_no)));
                    }
                }
                redirect('JamaBandi/step3/' . $patta_no . '/' . $patta_type_code);

            } elseif ($chain_result_2->success === 0) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('message', $chain_result_2->message . ": " . $chain_result_2->error_msg . ". Property Chain updation for Case No $case_no Not Successfull. Error Code(" . $chain_result_2->error_code . ")");
                redirect(base_url() . "index.php/home");
            } else {
                $this->db->trans_rollback();
                $this->session->set_flashdata('message', "Error occured. Property Chain updation and creation for Case No $case_no Not Successfull.");
                redirect(base_url() . "index.php/home");
            }
            //redirect(base_url() . "index.php/home");
        } else {
            $this->db->trans_rollback();
            $this->session->set_flashdata('message', "Chitha Could not be updated for case no $case_no.Contact Helpdesk with case no");
            redirect(base_url() . "index.php/home");
        }
    }



}