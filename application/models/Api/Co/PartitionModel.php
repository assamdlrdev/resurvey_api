<?php
require_once APPPATH . '/libraries/CommonTrait.php';

class PartitionModel extends CI_Model
{
    use CommonTrait;

    public function __construct()
    {
        parent::__construct();
    }

    public function getPartitionList($data): array
    {
        $data = (array) $data;

        $this->db->distinct()
            ->select('*')
            ->from('field_mut_basic fmb')
            ->where([
                'fmb.dist_code' => $data['dcode'],
                'fmb.subdiv_code' => $data['subdiv_code'],
                'fmb.cir_code' => $data['cir_code'],
                'fmb.mut_type' => '02'
            ]);

        $query = $this->db->get();
        return $query->result_array();
    }


    function getFieldMutBasicDetails($data)
    {
        $this->db->distinct()
            ->select('*')
            ->from('field_mut_basic')
            ->where([
                'dist_code' => $data['dist_code'],
                'subdiv_code' => $data['subdiv_code'],
                'cir_code' => $data['cir_code'],
                'case_no' => $data['case_num'],
                'mut_type' => '02'
            ]);

        $query = $this->db->get();

        return $query->row();
    }

    function fieldMutDagDetails($data)
    {
        return $this->db->select("*, patta_type")
            ->from("field_mut_dag_details d")
            ->join("patta_code p", "d.patta_type_code=p.type_code")
            ->where([
                "d.case_no" => $data['case_no'],
                "dist_code" => $data['dist_code'],
                "subdiv_code" => $data['subdiv_code'],
                "cir_code" => $data['cir_code'],
                "mouza_pargona_code" => $data['mouza_pargona_code'],
                "lot_no" => $data['lot_no'],
                "vill_townprt_code" => $data['vill_townprt_code']
            ])
            ->get()->row();
    }

    //todo need to debug json issue
    function fieldPartPetitionerDetails($data)
    {
        $this->db
            ->select('*')
            ->from('field_part_petitioner')
            ->where([
                'dist_code' => $data['dist_code'],
                'subdiv_code' => $data['subdiv_code'],
                'cir_code' => $data['cir_code'],
                'case_no' => $data['case_num'],
            ]);

        $query = $this->db->get();
        return $query->result();
    }


    function getChithaBasicDetails($data)
    {
        $this->db->select('*');
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
        // $this->db->where('cb.dag_no', $data['dag_no']);  
        // //todo ?? uncomment ?

        // Execute the query
        $query = $this->db->get();

        //   test($this->db->last_query(), 1);
        return $query->row();
    }

    function getDagNumbersOfParticularPatta($data)
    {
        $this->db->distinct();
        $this->db->select('cb.dag_no');
        $this->db->from('chitha_basic cb');
        $this->db->where([
            'cb.dist_code' => $data['dist_code'],
            'cb.subdiv_code' => $data['subdiv_code'],
            'cb.cir_code' => $data['cir_code'],
            'cb.mouza_pargona_code' => $data['mouza_pargona_code'],
            'cb.lot_no' => $data['lot_no'],
            'cb.vill_townprt_code' => $data['vill_townprt_code'],
        ]);
        $this->db->order_by('cb.dag_no', 'ASC');

        $query = $this->db->get()->result_array();

        return array_column($query, 'dag_no'); // ← Extract only dag_no values
    }


    function getSuggestedPattaNumbers($data)
    {
        $this->db->distinct();
        $this->db->select('cb.patta_no');
        $this->db->from('chitha_basic cb');

        $this->db->where([
            'cb.dist_code' => $data['dist_code'],
            'cb.subdiv_code' => $data['subdiv_code'],
            'cb.cir_code' => $data['cir_code'],
            'cb.mouza_pargona_code' => $data['mouza_pargona_code'],
            'cb.lot_no' => $data['lot_no'],
            'cb.vill_townprt_code' => $data['vill_townprt_code'],
            'cb.patta_type_code' => $data['patta_type_code']
        ]);

        $this->db->where("TRIM(patta_no)!='' AND TRIM(patta_no)!='.'", NULL, FALSE);
        // apply subquery
        $this->db->where("cb.patta_type_code IN (SELECT type_code FROM patta_code WHERE mutation='a')", NULL, FALSE);

        $this->db->order_by('cb.patta_no', 'ASC');

        $query = $this->db->get()->result_array();

        return array_column($query, 'patta_no');  // return clean array like [1, 2, 3, 4, ...]
    }

    function checkPattaApplicant($data)
    {
        $sql = "SELECT COUNT(*) AS total
            FROM chitha_pattadar p 
            JOIN chitha_dag_pattadar d 
                ON p.dist_code = d.dist_code 
                AND p.subdiv_code = d.subdiv_code 
                AND p.cir_code = d.cir_code 
                AND p.lot_no = d.lot_no 
                AND p.vill_townprt_code = d.vill_townprt_code 
                AND p.mouza_pargona_code = d.mouza_pargona_code 
                AND p.pdar_id = d.pdar_id
            WHERE p.dist_code=?
              AND p.subdiv_code=?
              AND p.cir_code=?
              AND p.mouza_pargona_code=?
              AND p.vill_townprt_code=?
              AND d.lot_no=?
              AND d.dag_no=?
              AND TRIM(p.patta_no)=TRIM(?)
              AND p.patta_type_code=?
              AND (d.p_flag='0' OR d.p_flag IS NULL)
              AND p.pdar_id NOT IN (SELECT pdar_id FROM field_part_petitioner WHERE case_no=?)";

        $query = $this->db->query($sql, [
            $data['dist_code'],
            $data['subdiv_code'],
            $data['cir_code'],
            $data['mouza_pargona_code'],
            $data['vill_townprt_code'],
            $data['lot_no'],
            $data['dag_no'],
            $data['patta_no'],           // TRIM applied inside SQL
            $data['patta_type_code'],
            $data['case_no']
        ]);

        return $query->row()->total;   // returns the count value
    }

    //Copy / OLD
    function proceedingOrderOLD($data, $order = null)
    {
        $dist_code = $data['dist_code'];
        $subdiv_code = $data['subdiv_code'];
        $cir_code = $data['cir_code'];
        $user_code = $data['user_code'];
        $user_desig_code = $data['user_desig_code'];
        $date_entry = date('Y-m-d h:i:s');
        $case_no = $data['case_no'];

        if ($user_desig_code == 'CO') {
            $status = 'Final';
        } else {
            $status = 'Pending';
        }

        $proceeding_id = $this->db->query("select count(proceeding_id)+1 as pid from petition_proceeding where case_no='$case_no' ")->row()->pid;
        if ($proceeding_id == null) {
            $proceeding_id = 1;
        }
        $data = array(
            'case_no' => $case_no,
            'proceeding_id' => $proceeding_id,
            'date_of_hearing' => $date_entry,
            'co_order' => $order,
            'next_date_of_hearing' => $date_entry,
            'status' => $status,
            'user_code' => $user_code,
            'date_entry' => $date_entry,
            'dist_code' => $dist_code,
            'cir_code' => $cir_code,
            'subdiv_code' => $subdiv_code,
            'operation' => 'E',
            'ip' => $this->utilityclass->get_client_ip()
        );
        $tstatus1 = $this->db->insert("petition_proceeding", $data); //********
        if ($this->db->affected_rows() <= 0) {
            log_message('error', "PROCEEDING_INSERT_ERROR" . $this->db->last_query());
        }
        return $this->db->affected_rows() > 0 ? true : false;
    }


    //related sub method
    public function jamaCheckToDeleteorNot(
        $dist_code,
        $subdiv_code,
        $circle_code,
        $mouza_code,
        $lot_no,
        $vill_code,
        $dag_number,
        $patta_number,
        $patta_type
    ) {

        $q33 = "select count(*) as c from jama_dag where 
            dist_code=? and subdiv_code=? and cir_code=? and 
            mouza_pargona_code=? and lot_no=? and vill_townprt_code=? and dag_no=?";
        $q33_result = $this->db->query($q33, array(
            $dist_code,
            $subdiv_code,
            $circle_code,
            $mouza_code,
            $lot_no,
            $vill_code,
            $dag_number
        ));

        if ($q33_result->row()->c <= 0)
            return $this->generateFunRespData(true, "q33_result failed!");
        // return;  //todo original code.

        $this->db->delete(
            'jama_dag',
            array(
                'dist_code' => $dist_code,
                'subdiv_code' => $subdiv_code,
                'cir_code' => $circle_code,
                'mouza_pargona_code' => $mouza_code,
                'lot_no' => $lot_no,
                'vill_townprt_code' => $vill_code,
                'dag_no' => $dag_number
            )
        );
        if ($this->db->affected_rows() != $q33_result->row()->c) {
            $this->db->trans_rollback();

            log_message("error", "#OPDELJAMA001 Delete failed, Unable to delete data from jama_dag
                         for dist_code:" . $dist_code . ", dag no: " . $dag_number);

            return $this->generateFunRespData(false, "Unable to Pass the Order. Error Code (#OPDELJAMA001)");
        }

        //////////check multiple dag////////
        $q34 = "select count(*) as c from jama_dag where 
        dist_code=? and subdiv_code=? and cir_code=? and 
        mouza_pargona_code=? and lot_no=? and vill_townprt_code=? 
        and dag_no !=? and patta_no=? and patta_type_code=?";
        $q34_result = $this->db->query($q34, array(
            $dist_code,
            $subdiv_code,
            $circle_code,
            $mouza_code,
            $lot_no,
            $vill_code,
            $dag_number,
            $patta_number,
            $patta_type
        ));

        if ($q34_result->row()->c > 0)
            return $this->generateFunRespData(true, "q34_result failed!");

        ///////Delete jama_patta///////////
        $q35 = "select count(*) as c from jama_patta where 
            dist_code=? and subdiv_code=? and cir_code=? and 
            mouza_pargona_code=? and lot_no=? and vill_townprt_code=? 
            and patta_no=? and patta_type_code=?";
        $q35_result = $this->db->query($q35, array(
            $dist_code,
            $subdiv_code,
            $circle_code,
            $mouza_code,
            $lot_no,
            $vill_code,
            $patta_number,
            $patta_type
        ));
        if ($q35_result->row()->c > 0) {
            $this->db->delete(
                'jama_patta',
                array(
                    'dist_code' => $dist_code,
                    'subdiv_code' => $subdiv_code,
                    'cir_code' => $circle_code,
                    'mouza_pargona_code' => $mouza_code,
                    'lot_no' => $lot_no,
                    'vill_townprt_code' => $vill_code,
                    'patta_no' => $patta_number,
                    'patta_type_code' => $patta_type
                )
            );
            if ($this->db->affected_rows() != $q35_result->row()->c) {
                $this->db->trans_rollback();

                log_message("error", "#OPDELJAMA002 Delete failed, Unable to delete data from jama_patta
                     for dist_code:" . $dist_code . ", patta_no: " . $patta_number);

                return $this->generateFunRespData(false, "Unable to Pass the Order. Error Code (#OPDELJAMA002)");
            }
        }
        ///////Delete jama_pattadar///////////
        $q36 = "select count(*) as c from jama_pattadar where 
            dist_code=? and subdiv_code=? and cir_code=? and 
            mouza_pargona_code=? and lot_no=? and vill_townprt_code=? 
            and patta_no=? and patta_type_code=?";
        $q36_result = $this->db->query($q36, array(
            $dist_code,
            $subdiv_code,
            $circle_code,
            $mouza_code,
            $lot_no,
            $vill_code,
            $patta_number,
            $patta_type
        ));
        if ($q36_result->row()->c > 0) {
            $this->db->delete(
                'jama_pattadar',
                array(
                    'dist_code' => $dist_code,
                    'subdiv_code' => $subdiv_code,
                    'cir_code' => $circle_code,
                    'mouza_pargona_code' => $mouza_code,
                    'lot_no' => $lot_no,
                    'vill_townprt_code' => $vill_code,
                    'patta_no' => $patta_number,
                    'patta_type_code' => $patta_type
                )
            );
            if ($this->db->affected_rows() != $q36_result->row()->c) {
                $this->db->trans_rollback();

                log_message("error", "#OPDELJAMA003 Delete failed, Unable to delete data from jama_pattadar
                     for dist_code:" . $dist_code . ", patta_no: " . $patta_number);

                return $this->generateFunRespData(false, "Unable to Pass the Order. Error Code (#OPDELJAMA003)");
            }
        }
        ///////Delete jama_remark///////////
        $q37 = "select count(*) as c from jama_remark where 
            dist_code=? and subdiv_code=? and cir_code=? and 
            mouza_pargona_code=? and lot_no=? and vill_townprt_code=? 
            and patta_no=? and patta_type_code=?";
        $q37_result = $this->db->query($q37, array(
            $dist_code,
            $subdiv_code,
            $circle_code,
            $mouza_code,
            $lot_no,
            $vill_code,
            $patta_number,
            $patta_type
        ));
        if ($q37_result->row()->c > 0) {
            $this->db->delete(
                'jama_remark',
                array(
                    'dist_code' => $dist_code,
                    'subdiv_code' => $subdiv_code,
                    'cir_code' => $circle_code,
                    'mouza_pargona_code' => $mouza_code,
                    'lot_no' => $lot_no,
                    'vill_townprt_code' => $vill_code,
                    'patta_no' => $patta_number,
                    'patta_type_code' => $patta_type
                )
            );
            if ($this->db->affected_rows() != $q37_result->row()->c) {
                $this->db->trans_rollback();
                log_message("error", "#OPDELJAMA004 Delete failed, Unable to delete data from jama_remark
                     for dist_code:" . $dist_code . ", patta_no: " . $patta_number);

                return $this->generateFunRespData(false, "Unable to Pass the Order. Error Code (#OPDELJAMA004)");
            }
        }

        return $this->generateFunRespData(true, "Success!");
    }

    public function proceedingOrder($userData, $orderText = null)
    {
        // --------------------------
        // Extract user & case params
        // --------------------------
        $caseNo = $userData['case_no'];

        $insertData = [
            'dist_code' => $userData['dist_code'],
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


    protected function generateFunRespData($status, $msg = null)
    {
        return $response = [
            'status' => $status,
            'msg' => $msg
        ];
    }


    public function update_table($table, $params, $where)
    {
        if ($table == 'chitha_basic') {
            $sqlPattaTypeAllowed = "SELECT type_code FROM patta_code WHERE jamabandi='n'";
            $patta_array = array_column(
                $this->db->query($sqlPattaTypeAllowed)->result_array(),
                'type_code'
            );
            // var_dump($params);die;

            if (
                isset($params['patta_type_code']) &&
                !in_array($params['patta_type_code'], $patta_array)
            ) {
                if (
                    (isset($params['dag_local_tax'], $params['dag_revenue']) &&
                        (empty($params['dag_local_tax']) || empty($params['dag_revenue'])))
                    || (isset($params['patta_no']) && empty($params['patta_no']))
                ) {
                    return 0;
                }
            }

            if ((isset($params['land_class_code']) && empty($params['land_class_code']))) {
                // echo "EMPTY";
                return 0;
            }

        }
        if (isset($where['patta_no'])) {
            $pattaNo = trim($where['patta_no']);
            $this->db->where("TRIM(patta_no) = " . $this->db->escape($pattaNo), null, false);
            unset($where['patta_no']);
        }
        $this->db->where($where);
        $this->db->update($table, $params);
        // echo $this->db->last_query();
        // if($table == 'chitha_rmk_ordbasic')
        if ($this->db->affected_rows() >= 1) {
            // return 1;
            return $this->db->affected_rows();
        } else {
            log_message('error', "UPDATEE-" . $table . "#######" . $this->db->last_query());
            return 0;
        }
    }

    public function maxpdarIdCheck($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $patta_type_code, $patta_no)
    {

        $pattadars_in_chitha_pattadar = $pattadars_in_jama_pattadar = $pattadars_in_chithaDag_pattadar = 0;
        $pattadars_in_chitha_pattadar = $this->db->query("select max(pdar_id::int)+1 as cp from chitha_pattadar where
              dist_code=? and subdiv_code=? and cir_code=? and mouza_pargona_code=? and lot_no=? and vill_townprt_code=? and  patta_type_code=? and TRIM(patta_no)::varchar=trim(?)", array($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $patta_type_code, (string) $patta_no));
        // echo $this->db->last_query();
        if ($pattadars_in_chitha_pattadar->num_rows() > 0) {
            $pattadars_in_chitha_pattadar = $pattadars_in_chitha_pattadar->row()->cp;
        }
        $pattadars_in_jama_pattadar = $this->db->query("select max(pdar_id::int)+1 as jp from jama_pattadar where dist_code=? and subdiv_code=? and cir_code=? and mouza_pargona_code=? and lot_no=? and vill_townprt_code=? and patta_type_code=? and TRIM(patta_no)=trim(?)", array($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $patta_type_code, (string) $patta_no));
        if ($pattadars_in_jama_pattadar->num_rows() > 0) {
            $pattadars_in_jama_pattadar = $pattadars_in_jama_pattadar->row()->jp;
        }
        // echo $this->db->last_query();
        $pattadars_in_chithaDag_pattadar = $this->db->query("select max(pdar_id::int)+1 as dp from chitha_dag_pattadar where dist_code=? and subdiv_code=? and cir_code=? and mouza_pargona_code=? and lot_no=? and vill_townprt_code=? and patta_type_code=? and  TRIM(patta_no)=trim(?)", array($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $patta_type_code, (string) $patta_no));
        if ($pattadars_in_chithaDag_pattadar->num_rows() > 0) {
            $pattadars_in_chithaDag_pattadar = $pattadars_in_chithaDag_pattadar->row()->dp;
        }
        // echo $this->db->last_query();
        // log_message('error', "###############" . $this->db->last_query());
        if ($pattadars_in_chitha_pattadar > $pattadars_in_jama_pattadar) {
            if ($pattadars_in_chithaDag_pattadar > $pattadars_in_chitha_pattadar) {
                $pdar_id = $pattadars_in_chithaDag_pattadar;
            } else {
                $pdar_id = $pattadars_in_chitha_pattadar;
            }
        } elseif ($pattadars_in_chithaDag_pattadar > $pattadars_in_jama_pattadar) {
            $pdar_id = $pattadars_in_chithaDag_pattadar;
        } else {
            $pdar_id = $pattadars_in_jama_pattadar;
        }
        if ($pdar_id == null) {
            $pdar_id = 1;
        }
        return $pdar_id;
    }

    public function insert_table($table, $params)
    {
        if ($table == 'chitha_basic') {
            if (!in_array($params['patta_type_code'], ['1001', '2001'])) {
                if (empty($params['dag_local_tax']) || empty($params['dag_revenue'])) {
                    return 0;
                }
                if (empty($params['patta_no'])) {
                    return 0;
                }
            }
            if (empty($params['land_class_code']) || empty($params['patta_type_code'])) {
                return 0;
            }
        }
        $this->db->insert($table, $params);
        // echo $this->db->last_query();
        // echo "<br>";
        // if($table == 'chitha_rmk_ordbasic')
        if ($this->db->affected_rows() >= 1) {
            // return 1;
            return $this->db->affected_rows();
        } else {
            log_message('error', "INSERTT-" . $table . "#######" . $this->db->last_query());
            return 0;
        }
    }


}