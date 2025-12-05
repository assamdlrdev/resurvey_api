<?php

class StrikeoutDataModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
        $this->load->model('Api/ChithaModel');
	}

    public function genearteMiscPetitionNo(){
        $petition_no = $this->db->query("select nextval('seq_max_misc') as count ")->row()->count;
        return $petition_no;
    }

    function generateCaseName($dist_code, $subdiv_code, $cir_code){
        $financialyeardate = (date('m') < '07') ? date('Y', strtotime('-1 year')) . "-" . date('y') : date('Y') . "-" . date('y', strtotime('+1 year'));
        $q = "Select dist_abbr,cir_abbr from location where dist_code=? and subdiv_code=? and cir_code=? and mouza_pargona_code!='00' ";
        $abbrname = $this->db->query($q, [$dist_code, $subdiv_code, $cir_code])->row();
        if($abbrname)
        {
            $cir_dist_name = $abbrname->dist_abbr . "/" . $abbrname->cir_abbr;
            $case_no = $cir_dist_name . "/" . $financialyeardate . "/" ;
            return $case_no;
        }
        return false;
    }

    public function authorizeLM($dist_code, $subdiv_code, $cir_code, $user_code) {
        $lmData = $this->db->query("SELECT * FROM lm_code WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND lm_code=? AND status='E'", [$dist_code, $subdiv_code, $cir_code, $user_code])->row();

        if(empty($lmData)) {
            return [
                'status' => 'n'
            ];
        }

        return [
            'status' => 'y',
            'data' => $lmData
        ];
    }


    public function updateChithaNameStrikeOut($case_no, $misc_case_petition_no, $user_code) {
        $sql="SELECT case_no FROM t_chitha_rmk_other_opp_party WHERE 
        case_no='$case_no' AND iscorrected_inco is null ";

        $q = "SELECT * FROM misc_case_basic mcb, t_chitha_rmk_infavor_of c8 WHERE
        mcb.dist_code = c8.dist_code AND mcb.subdiv_code = c8.subdiv_code AND 
        mcb.cir_code= c8.cir_code AND mcb.lot_no = c8.lot_no AND 
        mcb.mouza_pargona_code = c8.mouza_pargona_code AND 
        mcb.vill_townprt_code = c8.vill_townprt_code AND mcb.misc_case_no=c8.ord_no AND 
        TRIM(mcb.patta_no) = TRIM(c8.patta_no) AND c8.iscorrected_inco IS NULL AND 
        c8.ord_no='$case_no' AND c8.petition_no = '$misc_case_petition_no' AND 
        c8.ord_no IN ($sql) ";

        $data = $this->db->query($q)->result();
        $ord_cron_no = 1;
        foreach ($data as $d) {
            $dist_code = $d->dist_code;
            $subdiv_code = $d->subdiv_code;
            $cir_code = $d->cir_code;
            $lot_no = $d->lot_no;
            $mouza_pargona_code = $d->mouza_pargona_code;
            $vill_townprt_code = $d->vill_townprt_code;
            $dag_no = $d->dag_no;
            $patta_no = $d->patta_no;
            $pdar_id_for_aadhaar = $d->pdar_id;
            $patta_type_code = $d->patta_type_code;
            $auth_type = $d->auth_type;
            $ref_no = $d->id_ref_no;
            $photo = $d->photo; 

            $q = "SELECT max(rmk_type_hist_no)+1 AS c2 FROM chitha_rmk_gen 
            WHERE dist_code=? and subdiv_code=? and cir_code=? AND lot_no=? 
            AND vill_townprt_code=? and mouza_pargona_code=?";
            $rmk_type_hist_no = $this->db->query($q, array($dist_code, $subdiv_code, $cir_code,
            $lot_no, $vill_townprt_code, $mouza_pargona_code))->row()->c2;
            
            if ($rmk_type_hist_no == null) {
                $rmk_type_hist_no = 1;
            }
            
            $query = $this->db->query("SELECT * FROM t_chitha_rmk_infavor_of WHERE 
            ord_no=?", array($d->ord_no));

            if($query->num_rows() < 1)
            {
                return [
                    'status' => 'n',
                    'msg' => 'No Data Found in t_chitha_rmk_infavor_of for case no: ' . $case_no
                ];
            }

            $infve = $query->result();
            foreach ($infve as $infv) {
                unset($infv->year_no);
                unset($infv->petition_no);
                unset($infv->pdar_id);
                unset($infv->revenue);
                unset($infv->iscorrected_inco);
                unset($infv->iscorrected_inco_date);
                unset($infv->iscorrected_rkg_record);
                unset($infv->iscorrected_rkg_date);
                unset($infv->infavor_is_copdar);
                unset($infv->make_mdb);
                unset($infv->new_pattadar);
                unset($infv->iscorrected_inco_date);
                $infv->rmk_type_hist_no = $rmk_type_hist_no;
                $infv->ord_cron_no = $ord_cron_no++;
                $infv->user_code = $user_code;
                $infv->date_entry = date('Y-m-d');
                $infv->operation = 'E';

                $infvIns = $this->db->insert("chitha_rmk_infavor_of", $infv);
                if($infvIns != 1)
                {
                    return [
                        'status' => 'n',
                        'msg' => 'Error in inserting into chitha_rmk_infavor_of for case no: ' . $case_no
                    ];
                }

                $query = "UPDATE t_chitha_rmk_infavor_of SET iscorrected_inco='Y' WHERE 
                dist_code=? AND subdiv_code=? AND cir_code=? AND lot_no=? AND  
                mouza_pargona_code=? AND vill_townprt_code=? AND ord_no=? AND petition_no=?";
                $this->db->query($query, array($d->dist_code, $d->subdiv_code, $d->cir_code,
                $d->lot_no, $d->mouza_pargona_code, $d->vill_townprt_code, $case_no, 
                $misc_case_petition_no));
                
                if($this->db->affected_rows() < 1){
                    return [
                        'status' => 'n',
                        'msg' => 'Error in updating into t_chitha_rmk_infavor_of for case no: ' . $case_no
                    ];
                }
            }
            $query = $this->db->query("SELECT * FROM t_chitha_rmk_other_opp_party 
            WHERE case_no=?", array($d->ord_no));
            if($query->num_rows() < 1)
            {
                return [
                    'status' => 'n',
                    'msg' => 'Data not found in t_chitha_rmk_other_opp_party for case no: ' . $case_no
                ];
            }

            $ordparty = $query->result();
            foreach ($ordparty as $infv) {
                unset($infv->iscorrected_inco);
                unset($infv->iscorrected_inco_date);
                unset($infv->iscorrected_rkg_record);
                unset($infv->iscorrected_rkg_date);
                unset($infv->infavor_is_copdar);
                unset($infv->make_mdb);
                unset($infv->new_pattadar);
                unset($infv->case_no);
                $infv->rmk_type_hist_no = $rmk_type_hist_no;
                $infv->ord_cron_no = $ord_cron_no++;
                $infv->user_code = $user_code;
                $infv->date_entry = date('Y-m-d');
                $infv->operation = 'E';
                //var_dump($infv);
                $chithaROOPIns = $this->db->insert("chitha_rmk_other_opp_party", $infv);
                if($chithaROOPIns != 1)
                {
                    return [
                        'status' => 'n',
                        'msg' => 'Error in inserting into chitha_rmk_other_opp_party for case no: ' . $case_no
                    ];
                }

                $this->db->query("UPDATE t_chitha_rmk_other_opp_party SET
                iscorrected_inco='Y' WHERE dist_code=? AND subdiv_code=? AND cir_code=? 
                AND lot_no=? AND mouza_pargona_code=? AND vill_townprt_code=? 
                AND case_no=?", array($d->dist_code, $d->subdiv_code, $d->cir_code, 
                $d->lot_no, $d->mouza_pargona_code, $d->vill_townprt_code, $d->ord_no));

                if($this->db->affected_rows() < 1)
                {
                    return [
                        'status' => 'n',
                        'msg' => 'Error in updating into t_chitha_rmk_other_opp_party for case no: ' . $case_no
                    ];
                }

                $this->db->query("UPDATE chitha_dag_pattadar SET p_flag='1', jama_yn=null 
                WHERE TRIM(patta_no)=trim(?) AND pdar_id=? AND dist_code=? 
                AND subdiv_code=? AND cir_code=? AND lot_no=? AND mouza_pargona_code=? 
                AND dag_no=? and vill_townprt_code=? ",
                array($d->patta_no, $infv->name_for_id, $d->dist_code, $d->subdiv_code, 
                $d->cir_code, $d->lot_no, $d->mouza_pargona_code, $dag_no, 
                $d->vill_townprt_code));

                if($this->db->affected_rows() < 1)
                {
                    return [
                        'status' => 'n',
                        'msg' => 'Error in updating into chitha_dag_pattadar for case no: ' . $case_no
                    ];
                }
            }

            $test="select lm_code as lm_code from t_chitha_rmk_ordbasic where ord_no='$case_no' ";
            $data = $this->db->query($test)->row()->lm_code;

            $d = array(
                'dist_code' => $dist_code,
                'subdiv_code' => $subdiv_code,
                'cir_code' => $cir_code,
                'lot_no' => $lot_no,
                'mouza_pargona_code' => $mouza_pargona_code,
                'vill_townprt_code' => $vill_townprt_code,
                'rmk_type_hist_no' => $rmk_type_hist_no,
                'dag_no' => $dag_no,
                'ord_no' => $case_no,
                'ord_date' => date('Y-m-d'),
                'ord_type_code' => '07',
                'ord_cron_no' => $ord_cron_no,
                'ord_passby_sign_yn' => 'Y',
                'ord_passby_desig' => 'CO',
                'co_sign_yn' => 'Y',
                'user_code' => $user_code,
                'date_entry' => date('Y-m-d'),
                'operation' => 'E',
                'm_dag_area_b' => 0.0,
                'm_dag_area_k' => 0.0,
                'm_dag_area_lc' => 0.0,
                'm_dag_area_g' => 0.0,
                'm_dag_area_kr' => 0.0,
                'area_left_b' => 0.0,
                'area_left_k ' => 0.0,
                'area_left_lc' => 0.0,
                'area_left_g' => 0.0,
                'area_left_kr' => 0.0,
                'lm_code'=>$data,
            );            
            $chithaROBins = $this->db->insert("chitha_rmk_ordbasic", $d);
            if($chithaROBins != 1)
            {
                return [
                    'status' => 'n',
                    'msg' => 'Error in inserting into chitha_rmk_ordbasic for case no: ' . $case_no
                ];
            }
            $d = array(
                'dist_code' => $dist_code,
                'subdiv_code' => $subdiv_code,
                'cir_code' => $cir_code,
                'lot_no' => $lot_no,
                'mouza_pargona_code' => $mouza_pargona_code,
                'vill_townprt_code' => $vill_townprt_code,
                'rmk_type_hist_no' => $rmk_type_hist_no,
                'dag_no' => $dag_no,
                'rmk_type_code' => '01',
                'rmk_type_hist_no' => $rmk_type_hist_no,
                'user_code' => $user_code,
                'date_entry' => date('Y-m-d'),
                'operation' => 'E',
            );
            $chithaRGins = $this->db->insert("chitha_rmk_gen", $d);
            if($chithaRGins != 1)
            {
                return [
                    'status' => 'n',
                    'msg' => 'Error in inserting into chitha_rmk_gen for case no: ' . $case_no
                ];
            }

            // $this->db->query("UPDATE chitha_basic SET jama_yn=null WHERE 
            // dist_code=? AND subdiv_code=? AND cir_code=? AND lot_no=? AND 
            // mouza_pargona_code=? AND vill_townprt_code=? AND dag_no=?", 
            // array($d['dist_code'], $d['subdiv_code'], $d['cir_code'], $d['lot_no'], 
            // $d['mouza_pargona_code'], $d['vill_townprt_code'], $dag_no));
            // echo $this->db->last_query();
            // return;die;exit();
            $table = 'chitha_basic';

            $params = [
                'jama_yn' => null, // or '' if you want empty string as in your example
            ];

            $where = [
                'dist_code'          => $d['dist_code'],
                'subdiv_code'        => $d['subdiv_code'],
                'cir_code'           => $d['cir_code'],
                'lot_no'             => $d['lot_no'],
                'mouza_pargona_code' => $d['mouza_pargona_code'],
                'vill_townprt_code'  => $d['vill_townprt_code'],
                'dag_no'             => $dag_no,
            ];

            // Then call your model method, assuming it uses CodeIgniter's Active Record to update
            $result = $this->update_table($table, $params, $where);

            if($result < 1)
            {
                return [
                    'status' => 'n',
                    'msg' => 'Error in updating into chitha_basic for case no: ' . $case_no
                ];
            }
            //changes done for aadhaar data updated against pattdar id---------03122022
            log_message('error',$auth_type);
            log_message('error',$patta_no);
            log_message('error',$patta_type_code);
            log_message('error',$pdar_id_for_aadhaar);
            log_message('error',$photo);
            if(isset($auth_type)){
               if($auth_type == 'AADHAAR'){
                  $aadharNo = $ref_no;
                  $panNo = null;
                  $photo = $photo;
                }elseif($auth_type == 'PAN'){
                  $aadharNo = null;
                  $panNo = $ref_no;
                  $photo = null;
                }
                if($aadharNo != null || $panNo != null){
                    //   $this->db->query("UPDATE chitha_pattadar SET pdar_aadharno='$aadharNo',pdar_pan_no='$panNo',pdar_photo=null WHERE 
                    //   dist_code=? AND subdiv_code=? AND cir_code=? AND lot_no=? AND 
                    //   mouza_pargona_code=? AND vill_townprt_code=? AND pdar_id=? AND patta_no= ? AND patta_type_code =?", 
                    //   array($d['dist_code'], $d['subdiv_code'], $d['cir_code'], $d['lot_no'], 
                    //   $d['mouza_pargona_code'], $d['vill_townprt_code'], $pdar_id_for_aadhaar,$patta_no,$patta_type_code));

                    $table = 'chitha_pattadar';
                    $params = [
                        'pdar_aadharno' => $aadharNo,
                        'pdar_pan_no'   => $panNo,
                        'pdar_photo'    => null,
                        'f1_case_no'         => $case_no,
                    ];
                    $where = [
                        'dist_code'          => $d['dist_code'],
                        'subdiv_code'        => $d['subdiv_code'],
                        'cir_code'           => $d['cir_code'],
                        'lot_no'             => $d['lot_no'],
                        'mouza_pargona_code' => $d['mouza_pargona_code'],
                        'vill_townprt_code'  => $d['vill_townprt_code'],
                        'pdar_id'            => $pdar_id_for_aadhaar,
                        'patta_no'           => trim($patta_no),
                        'patta_type_code'    => $patta_type_code,
                    ];

                    $result = $this->update_table($table, $params, $where);
                    // echo $this->db->last_query();die;
                    if($result < 1)
                    {
                        return [
                            'status' => 'n',
                            'msg' => 'Error in updating into chitha_pattadar for case no: ' . $case_no
                        ];
                    }
                }
            }
        }

        return [
            'status' => 'y',
            'msg' => 'Order Successfully Passed!'
        ];
        
    }

    public function update_table($table, $params, $where)
    {
        if ($table == 'chitha_basic') {
            $sqlPattaTypeAllowed = "SELECT type_code FROM patta_code WHERE jamabandi='n'";
            $patta_array         = array_column(
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
            log_message('error',"UPDATEE-".$table."#######".$this->db->last_query());
            return 0;
        }
    }
}