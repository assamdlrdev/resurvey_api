<?php
require_once APPPATH . '/libraries/CommonTrait.php';

class PartitionModel extends CI_Model
{
    use CommonTrait;

    private $CURR_DATE;
    private $CURR_YEAR;

    public function __construct()
    {
        parent::__construct();
        $this->CURR_DATE = date('Y-m-d');
        $this->CURR_YEAR = date('Y');
    }

    public function getLmVillages($data)
    {
        // Subquery
        $subquery = $this->db
            ->select('lot_no')
            ->from('lm_code lc2')
            ->where([
                'lc2.lm_code' => $data->usercode,
                'lc2.dist_code' => $data->dcode,
                'lc2.subdiv_code' => $data->subdiv_code,
                'lc2.cir_code' => $data->cir_code,
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
            'lc.lm_code' => $data->usercode,
            'lc.dist_code' => $data->dcode,
            'lc.subdiv_code' => $data->subdiv_code,
            'lc.cir_code' => $data->cir_code
        ]);

        $this->db->where('l.vill_townprt_code !=', '00000');

        // Add subquery condition for lot_no
        $this->db->where("lc.lot_no IN ($subquery)", null, false);

        $query = $this->db->get();
        $result = $query->result();

        return $result;
    }

    public function getPattaNumbers($data): array
    {
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

        return $query->result_array();
    }

    function getDagNumbers($data)
    {
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

        return $query->result_array();
    }

    function getDagPattadarInfo($data)
    {
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
                    cp.dag_por_b,
                    cp.dag_por_k,
                    cp.dag_por_lc,
                    cp.dag_por_g,
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
        return $query->result();
    }

    function genearteCaseNameOld($userdata)
    {
        $dist_code = $userdata('dist_code');
        $subdiv_code = $userdata('subdiv_code');
        $cir_code = $userdata('cir_code');


        $financialyeardate = (date('m') < '07') ? date('Y', strtotime('-1 year')) . "-" . date('y') : date('Y') . "-" . date('y', strtotime('+1 year'));

        $q = "Select dist_abbr,cir_abbr from location where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code!='00' ";

        $abbrname = $this->db->query($q)->row();

        if ($abbrname) {
            $cir_dist_name = $abbrname->dist_abbr . "/" . $abbrname->cir_abbr;
            $caseNo = $cir_dist_name . "/" . $financialyeardate . "/";
            return $caseNo;
        }

        return false;
    }

    function getLandAreaInfo($data)
    {
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
        return $query->result();
    }

    /**
     * Generate Case Name
     * @param mixed $userData
     * @return bool|string
     */
    function generateCaseName($userData)
    {
        // Fetch values safely
        $distCode = $userData['dist_code'] ?? null;
        $subdivCode = $userData['subdiv_code'] ?? null;
        $cirCode = $userData['cir_code'] ?? null;

        if (!$distCode || !$subdivCode || !$cirCode) {
            return false;
        }

        // Financial year (July → June)
        $financialYear = (date('m') < 7)
            ? date('Y', strtotime('-1 year')) . "-" . date('y')
            : date('Y') . "-" . date('y', strtotime('+1 year'));

        // Query Builder
        $this->db->select('dist_abbr, cir_abbr')
            ->from('location')
            ->where([
                'dist_code' => $distCode,
                'subdiv_code' => $subdivCode,
                'cir_code' => $cirCode
            ])
            ->where('mouza_pargona_code !=', '00');

        $result = $this->db->get()->row();

        if ($result) {
            $cirDistName = $result->dist_abbr . '/' . $result->cir_abbr;
            return $cirDistName . '/' . $financialYear . '/';
        }

        return false;
    }


    /**
     * Generate Field Petition Number
     */
    function generateFieldPetitionNo($distCode)
    {
        // Make sure schema path is correct for PostgreSQL
        $this->db->query("SET search_path TO public");

        // Query the sequence
        $query = $this->db->query("SELECT nextval('seq_max_field') AS petition_no");

        // Ensure result exists
        if ($query && $query->num_rows() > 0) {
            return (int) $query->row()->petition_no;
        }

        // fallback on failure
        return false;
    }

    public function proceedingOrder($userData, $caseNo, $orderText = null)
    {
        // --------------------------
        // Extract user & case params
        // --------------------------
        $insertData = [
            'dist_code' => $userData['user_dist_code'],
            'subdiv_code' => $userData['subdiv_code'],
            'cir_code' => $userData['cir_code'],
            'user_code' => $userData['user_code'],
            'operation' => 'E',
            'ip' => getClientIP(),
        ];

        $dateTime = date('Y-m-d H:i:s');

        // --------------------------
        // Determine Proceeding Status
        // --------------------------
        $insertData['status'] = ($userData['user_desig_code'] === 'CO')
            ? 'Final'
            : 'Pending';

        // --------------------------
        // Generate Next Proceeding ID
        // --------------------------
        $proceedingId = $this->db->select("COALESCE(MAX(proceeding_id), 0) + 1 AS pid")
            ->from("petition_proceeding")
            ->where("case_no", $caseNo)
            ->get()
            ->row()
            ->pid;

        // --------------------------
        // Prepare Insert Data
        // --------------------------
        $insertData = array_merge($insertData, [
            'case_no' => $caseNo,
            'proceeding_id' => $proceedingId,
            'date_of_hearing' => $dateTime,
            'next_date_of_hearing' => $dateTime,
            'co_order' => $orderText,
            'date_entry' => $dateTime,
        ]);

        // test($insertData,1);

        // --------------------------
        // Insert Into Database
        // --------------------------
        $this->db->insert("petition_proceeding", $insertData);

        if ($this->db->affected_rows() <= 0) {
            log_message('error', "PROCEEDING_INSERT_ERROR: " . $this->db->last_query());
            return false;
        }

        return true;
    }

    public function insertFieldMutBasicData($data, $caseNo, $petitionNo)
    {
        $field_mut_basic_data = array(
            'dist_code' => $data['dist_code'],
            'subdiv_code' => $data['subdiv_code'],
            'cir_code' => $data['cir_code'],
            'mouza_pargona_code' => $data['mouza_pargona_code'],
            'lot_no' => $data['lot_no'],
            'vill_townprt_code' => $data['vill_townprt_code'],
            'user_code' => $data['user_code'],
            'date_entry' => $this->CURR_DATE,
            'case_no' => $caseNo,
            'trans_code' => '01',
            'dispute_yn' => 0,
            'possession_yn' => 'y',
            'petition_no' => $petitionNo,
            'year_no' => $this->CURR_YEAR,
            'report_date' => $this->CURR_DATE,
            'mut_type' => '02',
            'operation' => 'E',
        );

        // --------------------------
        // Insert into field_mut_basic
        // --------------------------
        return $this->db->insert('field_mut_basic', $field_mut_basic_data);
    }

    public function insertDagDetails($data, $caseNo, $petitionNo)
    {
        // Base FMD structure
        $fmd = [
            'dist_code' => $data['dist_code'],
            'subdiv_code' => $data['subdiv_code'],
            'cir_code' => $data['cir_code'],
            'mouza_pargona_code' => $data['mouza_pargona_code'],
            'lot_no' => $data['lot_no'],
            'vill_townprt_code' => $data['vill_townprt_code'],
            'user_code' => $data['user_code'],
            'date_entry' => $this->CURR_DATE,
            'case_no' => $caseNo,
            'petition_no' => $petitionNo,
            'year_no' => $this->CURR_YEAR,
            'operation' => 'E',

            'dag_no' => $data['dag_no'],
            'patta_no' => $data['patta_no'],
            'patta_type_code' => $data['patta_type_code'],

            'm_dag_area_b' => $data['land_area_info']->bigha,  #apply
            'm_dag_area_k' => $data['land_area_info']->katha,
            'm_dag_area_lc' => $data['land_area_info']->lessa,

            'dag_area_b' => $data['land_area_info']->totalBigha,  #original
            'dag_area_k' => $data['land_area_info']->totalKatha,
            'dag_area_lc' => $data['land_area_info']->totalLessa,

            // Defaults (will modify below for Barak Valley)
            'm_dag_area_g' => '0.00',
            'dag_area_g' => '0',

            // Always zero in your logic
            'm_dag_area_kr' => '0',
            'dag_area_kr' => '0',

            'remark' => addslashes(trim($data['remarks'], true)),
        ];

        // ---- BARAK VALLEY SPECIAL CASE ----
        if (in_array($data['user_dist_code'], BARAK_VALLEY)) {
            // $fmd['m_dag_area_g'] = $data['mut_area_g'];
            // $fmd['dag_area_g'] = $data['dag_area_g'];

            //TODO later need to develop
        }

        // ---- INSERT RECORD ----
        if (!$this->db->insert('field_mut_dag_details', $fmd)) {
            log_message('error', '#ERRFPART002: Insert Failed (field_mut_dag_details). Case: ' . $caseNo);
            return false;
        }

        // ---- INSERT PROCEEDING ORDER ----
        $remark = $fmd['remark'];


        if (!$this->proceedingOrder($data, $caseNo, $remark)) {
            log_message('error', '#FPARTLM001: Proceeding order insert failed - ' . $this->db->last_query());
            return false;
        }

        return true;
    }

    public function getPattadarInfo($data)
    {

        return $this->db
            ->select('*')
            ->from('chitha_pattadar')
            ->where('patta_no', $data['patta_no'])
            ->where('pdar_id', $data['pdar_id'])
            ->where('dist_code', $data['dist_code'])
            ->where('subdiv_code', $data['subdiv_code'])
            ->where('cir_code', $data['cir_code'])
            ->where('mouza_pargona_code', $data['mouza_pargona_code'])
            ->where('lot_no', $data['lot_no'])
            ->where('vill_townprt_code', $data['vill_townprt_code'])
            ->where('patta_type_code', $data['patta_type_code'])
            ->get()
            ->row_array();
    }

    /**
     * Insert Applicant Details in field_part_petitioner.
     * @param mixed $data
     * @param integer $caseNo
     * @param integer $petitionNo
     * @return bool
     */
    public function insertFieldPartPetitioner($applicants, $data, $caseNo, $petitionNo)
    {
        $i = 1;

        $dec = null;
        $auth_type = null;
        $id_ref_no = null;
        $photo = null;

        foreach ($applicants as $applicant) {

            $pattadarInfo = (object) $this->getPattadarInfo((array) $applicant);

            $petitioner = array(
                'dist_code' => $applicant->dist_code,
                'subdiv_code' => $applicant->subdiv_code,
                'cir_code' => $applicant->cir_code,
                'mouza_pargona_code' => $applicant->mouza_pargona_code,
                'lot_no' => $applicant->lot_no,
                'vill_townprt_code' => $applicant->vill_townprt_code,
                'user_code' => $data['user_code'],
                'date_entry' => $this->CURR_DATE,
                'case_no' => $caseNo,
                'petition_no' => $petitionNo,
                'year_no' => $this->CURR_YEAR,
                'operation' => 'E',
                'dag_no' => $applicant->dag_no,
                'patta_no' => $applicant->patta_no,
                'patta_type_code' => $applicant->patta_type_code,

                'pdar_id' => $applicant->pdar_id,  # Applicant Info
                'pdar_cron_no' => $i++,
                'pdar_name' => $applicant->pdar_name,
                'pdar_guardian' => $pattadarInfo->pdar_father,
                'pdar_rel_guar' => $pattadarInfo->pdar_guard_reln ?? null,  //todo
                // $this->utilityclass->relationRevertBasu($applicant->dist_code, $applicant->gurdian_relation_id),/////////////gurdian_relation_id
                'pdar_gender' => $pattadarInfo->pdar_gender ?? null, //todo
                // $this->utilityclass->gnderRevertBasu($applicant->dist_code, $pattadarInfo->pdar_gender),
                'pdar_dag_por_b' => $applicant->dag_por_b,
                'pdar_dag_por_k' => $applicant->dag_por_k,
                'pdar_dag_por_lc' => $applicant->dag_por_lc,
                'pdar_dag_por_g' => $applicant->dag_por_g,

                'self_declaration' => $dec,
                'auth_type' => $auth_type,
                'id_ref_no' => $id_ref_no,
                'photo' => $photo
            );

            $insPetFPART = $this->db->insert('field_part_petitioner', $petitioner);

            if ($insPetFPART != 1) {
                $this->db->trans_rollback();
                log_message('error', '#ERRFPART003/RESURVEY: Insertion failed in field_part_petitioner for Case No ' . $caseNo);
                return false;
            }

        }

        return true;
    }

}