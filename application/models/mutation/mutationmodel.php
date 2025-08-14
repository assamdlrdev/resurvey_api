<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MutationModel extends CI_Model
{

    public function getDistricts()
    {
        $this->utilityclass->switchDb($this->session->userdata('dcode'));
        $district = $this->db->query("select * from   location where dist_code !='00'  and "
            . " subdiv_code='00' and cir_code='00' and mouza_pargona_code='00' and "
            . " vill_townprt_code='00000' and lot_no='00'");
        return $district->result();
    }
    //used in search option in co end----------
    public function getAllDistinctVillageList($append)
    {
        $query = "select distinct dist_code,subdiv_code,cir_code,vill_townprt_code,mouza_pargona_code,lot_no from petition_basic fmb left join basundhar_application ba on fmb.case_no=ba.dharitree where fmb.status is null AND fmb.comp_serv_yn is null and fmb.not_fresh is null "
            . "and fmb.lm_note_yn is null and fmb.mut_type='03' and " . $append . " ";
        return $this->db->query($query)->result();
    }

    //used in search option in co end----------
    public function getAllDistinctVillageListOP($dist_code, $subdiv_code, $cir_code, $user_code)
    {
        $query = "select distinct dist_code,subdiv_code,cir_code,vill_townprt_code,mouza_pargona_code,lot_no from petition_basic fmb left join basundhar_application ba on fmb.case_no=ba.dharitree where  dist_code='$dist_code' and (status!='D' or status is null ) and subdiv_code='$subdiv_code' "
            . "and cir_code='$cir_code' and mut_type='04' and co_user_code='$user_code' and (not_fresh is null or not_fresh='')"
            . " and (lm_note_yn is null or lm_note_yn='')";
        return $this->db->query($query)->result();
    }

    public function getAllDistinctVillageListOPCaseSecond($dist_code, $subdiv_code, $cir_code, $user_code)
    {
        $query = "select distinct dist_code,subdiv_code,cir_code,vill_townprt_code,mouza_pargona_code,lot_no from petition_basic fmb left join basundhar_application ba on fmb.case_no=ba.dharitree WHERE fmb.dist_code='$dist_code' "
            . "and fmb.subdiv_code='$subdiv_code' and fmb.cir_code='$cir_code' and fmb.co_user_code='$user_code' and fmb.not_fresh = 'Y' and fmb.status='P' and  fmb.mut_type='04'";
        return $this->db->query($query)->result();
    }
    public function getAllDistinctVillageListCaseSecond($append)
    {
        $query = "select distinct dist_code,subdiv_code,cir_code,vill_townprt_code,mouza_pargona_code,lot_no from petition_basic fmb left join basundhar_application ba on fmb.case_no=ba.dharitree where fmb.status='P' AND fmb.comp_serv_yn is null and fmb.not_fresh='Y'"
            . "and fmb.mut_type='03' and " . $append . " ";
        return $this->db->query($query)->result();
    }

    public function getAllDistinctMouzaList($append)
    {
        $query = "select distinct dist_code,subdiv_code,cir_code,vill_townprt_code,mouza_pargona_code,lot_no from petition_basic fmb left join basundhar_application ba on fmb.case_no=ba.dharitree where fmb.status is null AND fmb.comp_serv_yn is null and fmb.not_fresh is null "
            . "and fmb.lm_note_yn is null and fmb.mut_type='03' and " . $append . " ";
        return $this->db->query($query)->result();
    }
    public function getPendingMutationCasesCOEnd($dist_code, $subdiv_code, $cir_code, $start, $length, $order, $mouza_code, $lot_no, $vill_mouza_code, $vill_lot_no, $village_code, $define_date, $searchByCol_0)
    {
        $col = 0;
        $dir = "asc";
        if (!empty($order)) {
            foreach ($order as $o) {
                $col = $o['column'];
                $dir = $o['dir'];
            }
        }
        if ($dir != "asc" && $dir != 'desc') {
            $dir = 'desc';
        }
        $valid_columns = array(
            0   => 'petition_no',
        );
        if (!isset($valid_columns[$col])) {
            $order = null;
        } else {
            $order = $valid_columns[$col];
        }
        if ($order != null) {
            $this->db->order_by($order, $dir);
        }
        if (!empty($searchByCol_0)) {
            // $this->db->like('case_no', strtoupper($searchByCol_0));
            // $this->db->like('basundhara', strtoupper($searchByCol_0));
            $this->db->where("(application_ref_no like '%$searchByCol_0%' or case_no like '%$searchByCol_0%' or basundhara like '%$searchByCol_0%')");
        }
        if (!empty($mouza_code)) {
            $this->db->where('mouza_pargona_code', $mouza_code);
        }
        if (!empty($lot_no)) {
            $this->db->where('lot_no', $lot_no);
        }
        if (!empty($vill_mouza_code)) {
            $this->db->where('mouza_pargona_code', $vill_mouza_code);
        }
        if (!empty($vill_lot_no)) {
            $this->db->where('lot_no', $vill_lot_no);
        }
        if (!empty($village_code)) {
            $this->db->where('vill_townprt_code', $village_code);
        }
        $this->db->select('petition_basic.*,basundhar_application.basundhara');
        $this->db->join('basundhar_application', 'petition_basic.case_no = basundhar_application.dharitree', 'left');
        $this->db->where('dist_code', $dist_code);
        $this->db->where('subdiv_code', $subdiv_code);
        $this->db->where('cir_code', $cir_code);
        $this->db->where('petition_basic.status', null);
        $this->db->where('petition_basic.comp_serv_yn', null);
        $this->db->where('petition_basic.not_fresh', null);
        $this->db->where('petition_basic.lm_note_yn', null);
        $this->db->where('petition_basic.mut_type', '03');
        $this->db->where('date(date_entry) >=', $define_date);
        $this->db->limit($length, $start);
        $query = $this->db->get('petition_basic');
        //log_message('error',$this->db->last_query());
        if ($query->num_rows() > 0) {
            $data['data_results'] = $query->result();
            if (!empty($mouza_code)) {
                $this->db->where('mouza_pargona_code', $mouza_code);
            }
            if (!empty($lot_no)) {
                $this->db->where('lot_no', $lot_no);
            }
            if (!empty($vill_mouza_code)) {
                $this->db->where('mouza_pargona_code', $vill_mouza_code);
            }
            if (!empty($vill_lot_no)) {
                $this->db->where('lot_no', $vill_lot_no);
            }
            if (!empty($village_code)) {
                $this->db->where('vill_townprt_code', $village_code);
            }
            if (!empty($searchByCol_0)) {
                $this->db->like('case_no', strtoupper($searchByCol_0));
            }
            $this->db->where('dist_code', $dist_code);
            $this->db->where('subdiv_code', $subdiv_code);
            $this->db->where('cir_code', $cir_code);
            $this->db->where('petition_basic.status', null);
            $this->db->where('petition_basic.comp_serv_yn', null);
            $this->db->where('petition_basic.not_fresh', null);
            $this->db->where('petition_basic.lm_note_yn', null);
            $this->db->where('petition_basic.mut_type', '03');
            $this->db->where('date(date_entry) >=', $define_date);
            //$this->db->limit($length, $start);
            $data['total_records'] = $this->db->count_all_results('petition_basic');
            return $data;
        }
    }

    //end---------


    public function getSubDivJSON($distCode)
    {
        $this->utilityclass->switchDb($this->session->userdata('dcode'));
        $district = $this->db->query("select * from   location where dist_code =?  and "
            . " subdiv_code!='00' and cir_code='00' and mouza_pargona_code='00' and "
            . " vill_townprt_code='00000' and lot_no='00'", array($distCode));
        return $district->result();
    }

    public function getCirCodeJSON($distCode, $subdivcode)
    {
        $this->utilityclass->switchDb($this->session->userdata('dcode'));
        $district = $this->db->query("select * from   location where dist_code =?  and "
            . " subdiv_code=? and cir_code!='00' and mouza_pargona_code='00' and "
            . " vill_townprt_code='00000' and lot_no='00'", array($distCode, $subdivcode));
        return $district->result();
    }

    public function getMouzaJSON($distCode, $subdivcode, $circode)
    {
        $this->utilityclass->switchDb($this->session->userdata('dcode'));
        $district = $this->db->query("select * from   location where dist_code =?  and "
            . " subdiv_code=? and cir_code=? and mouza_pargona_code!='00' and "
            . " vill_townprt_code='00000' and lot_no='00'", array($distCode, $subdivcode, $circode));

        return $district->result();
    }

    public function getLotNoJSON($distCode, $subdivcode, $circode, $mouzacode)
    {
        $this->utilityclass->switchDb($this->session->userdata('dcode'));
        $district = $this->db->query("select *  from   location where dist_code =?  and "
            . " subdiv_code=? and cir_code=? and mouza_pargona_code=? and "
            . " vill_townprt_code='00000' and lot_no!='00' order by lot_no", array($distCode, $subdivcode, $circode, $mouzacode));
        return $district->result();
    }

    public function getVillageCodeJSON($distCode, $subdivcode, $circode, $mouzacode, $lotno)
    {
        $this->utilityclass->switchDb($this->session->userdata('dcode'));
        $district = $this->db->query("select distinct loc_name,vill_townprt_code from   location where "
            . "dist_code =?  and "
            . " subdiv_code=? and cir_code=? and mouza_pargona_code=? and "
            . " vill_townprt_code!='00000' and lot_no=? and nc_btad is null", array($distCode, $subdivcode, $circode, $mouzacode, $lotno));

        return $district->result();
    }

    public function getVillageCodeFlaggedJSON($distCode, $subdivcode, $circode, $mouzacode, $lotno)
    {
        $this->utilityclass->switchDb($this->session->userdata('dcode'));
        $district = $this->db->query("select distinct loc_name,vill_townprt_code,nc_btad from   location where "
            . "dist_code =?  and "
            . " subdiv_code=? and cir_code=? and mouza_pargona_code=? and "
            . " vill_townprt_code!='00000' and lot_no=? and nc_btad is not null", array($distCode, $subdivcode, $circode, $mouzacode, $lotno));
        // echo $this->db->last_query();

        return $district->result();
    }
    //added---
    public function getKhatianDetails($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprtcode)
    {
        //$this->utilityclass->switchDb($this->session->userdata('dcode'));
        $district = $this->db->query("select distinct id from khatian where dist_code = '$dist_code' and subdiv_code ='$subdiv_code' and cir_code = '$cir_code' and mouza_pargona_code = '$mouza_pargona_code' and lot_no = '$lot_no' and vill_townprt_code = '$vill_townprtcode'");
        return $district->result();
    }
    public function getAllRecordsKhatian($tablename, $where)
    {
        $this->db->select('');
        $this->db->from($tablename);
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function checkKhatianExistorNot($tablename, $search_field_name, $data)
    {
        $condition = "";
        foreach ($data as $key => $value) {
            $condition .= " AND $key = '$value'";
        }
        $sql = "select count($search_field_name) AS name from $tablename where 1=1 $condition";
        $query = $this->db->query($sql);
        $khatian = $query->row();
        if (isset($khatian)) {
            $khatian = $khatian->name;
            return $khatian;
        } else {
            return NULL;
        }
    }
    public function finalUpdateKhatianByCO($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code, $khatian_no, $new_khatian_no, $date, $co_remarks)
    {
        //$this->utilityclass->switchDb($this->session->userdata('dcode'));
        $district = $this->db->query("insert into khatian (id,dag_no,dist_code,subdiv_code,cir_code,mouza_pargona_code,lot_no,vill_townprt_code,length_posession, paid_cash_kind,payable_cash_kind,tenant_status,special_conditions,remarks,created_date,updated_date,uuid,old_khatian_no,co_remarks) select $new_khatian_no,dag_no,dist_code,subdiv_code,cir_code,mouza_pargona_code,lot_no,vill_townprt_code,length_posession, paid_cash_kind,payable_cash_kind,tenant_status,special_conditions,remarks,created_date,'$date',uuid,$khatian_no,'$co_remarks' from khatian where dist_code='$dist_code' and subdiv_code= '$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='$mouza_code' and lot_no='$lot_no' and vill_townprt_code='$vill_code' and id=$khatian_no");
        // echo $this->db->last_query();
        return $district;
    }
    public function finalUpdateKhatianChithaByCO($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code, $khatian_no, $new_khatian_no, $date)
    {
        //$this->utilityclass->switchDb($this->session->userdata('dcode'));
        $district = $this->db->query("
        insert into chitha_tenant (dist_code,subdiv_code,cir_code,
        mouza_pargona_code,lot_no,vill_townprt_code,dag_no,tenant_name,tenants_father, tenants_add1,tenants_add2
        ,tenants_add3,type_of_tenant,khatian_no,revenue_tenant,
        crop_rate,user_code,date_entry,operation,tenant_id,duration,paid_cash_kind,payable_cash_kind,special_conditions,status,tenant_status,remarks,bigha,katha,lessa,ganda,kranti,uuid,updated_date,old_khatian_no)
        select dist_code,subdiv_code,cir_code,mouza_pargona_code,lot_no,vill_townprt_code,dag_no,tenant_name,tenants_father,tenants_add1,tenants_add2,tenants_add3,type_of_tenant, $new_khatian_no,revenue_tenant,crop_rate,user_code,date_entry,operation,tenant_id,duration,paid_cash_kind,payable_cash_kind,special_conditions,status,tenant_status,remarks,bigha,katha,lessa,ganda,kranti,uuid,'$date',$khatian_no from chitha_tenant where dist_code='$dist_code' and subdiv_code= '$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='$mouza_code' and lot_no='$lot_no' and vill_townprt_code='$vill_code' and khatian_no = $khatian_no");
        // echo $this->db->last_query();
        return $district;
    }

    public function deletedDataInsert($tablename, $data)
    {
        $this->db->insert($tablename, $data);

        return ($this->db->affected_rows() > 0) ? true : false;
    }

    public function deleteKhatianDetails($tablename, $where)
    {
        $this->db->where($where);
        return $this->db->delete($tablename);
    }
    public function updateDeletedRecords($tablename, $where, $data)
    {
        $this->db->where($where);
        return $this->db->update($tablename, $data);
    }

    public function getMouza()
    {
    }

    public function getLotNo()
    {
    }

    public function villageTownPort()
    {
    }

    public function getMutationType()
    {
        $this->utilityclass->switchDb($this->session->userdata('dcode'));
        $mutation = $this->db->query("select * from   master_field_mut_type");
        return $mutation->result();
    }

    public function getTransferType()
    {
        $this->utilityclass->switchDb($this->session->userdata('dcode'));
        $mutation = $this->db->query("select * from   nature_trans_code");
        return $mutation->result();
    }

    public function getLandArea($dag_no)
    {
        $this->utilityclass->switchDb($this->session->userdata('dcode'));
        $dist_code = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $vill_code = $this->session->userdata('vill_code');
        $lot_no = $this->session->userdata('lot_no');
        $other = $dag_no;

        $data = $this->db->query("SElect dag_area_b,dag_area_k,trunc(dag_area_lc,3) as dag_area_lc,dag_area_g from   chitha_basic where trim(dag_no)='$other' and  dist_code ='$dist_code'  and "
            . " subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and "
            . " vill_townprt_code='$vill_code' and lot_no='$lot_no' ")->result();
        return $data;
    }

    public function getPattaType()
    {
        $this->utilityclass->switchDb($this->session->userdata('dcode'));
        $patta_type = $this->db->query("SELECT * FROM   patta_code where type_code !='0000' and (mutation='a' or mutation='i') order by patta_code");
        return $patta_type->result();
    }

    public function getPattaTypeExcludingAksona()
    {
        $this->utilityclass->switchDb($this->session->userdata('dcode'));
        $patta_type = $this->db->query("SELECT * FROM   patta_code where type_code !='0000' and (mutation='a') order by patta_code");
        return $patta_type->result();
    }

    public function getGovtPattaType()
    {
        $this->utilityclass->switchDb($this->session->userdata('dcode'));
        $patta_type = $this->db->query("SELECT * FROM   patta_code where type_code !='0000' and (mutation='n') and (conversion='n') and (jamabandi='n') order by patta_code");
        return $patta_type->result();
    }

    public function getConvertionPattaType()
    {
        $this->utilityclass->switchDb($this->session->userdata('dcode'));
        $patta_conv_type = $this->db->query("SELECT * FROM   patta_code where conversion ='y' ");
        return $patta_conv_type->result();
    }

    public function getLandClass()
    {
        $this->utilityclass->switchDb($this->session->userdata('dcode'));
        $landclass = $this->db->query("select * from   landclass_code");
        return $landclass->result();
    }

    //modified by bondita
    public function getPattatypeJSON()
    {
        $this->utilityclass->switchDb($this->session->userdata('dcode'));
        $district = $this->db->query("Select Type_code,patta_type from   patta_code where Type_code!='0000'");

        return $district->result();
    }

    //

    public function getPattatypeByNoJSON($distCode, $subdivcode, $circode, $mouzacode, $lotno, $villagecode, $pattano)
    {
        $this->utilityclass->switchDb($this->session->userdata('dcode'));
        $q = "select patta_type_code from   chitha_basic where dist_code=? and "
            . "subdiv_code=? and "
            . " cir_code=? and lot_no=? and mouza_pargona_code=?"
            . " and vill_townprt_code=? and "
            . " patta_no=?";

        $data = $this->db->query($q, array($distCode, $subdivcode, $circode, $lotno, $mouzacode, $villagecode, $pattano))->row()->patta_type_code;
        $district = $this->db->query("Select Type_code,patta_type from   patta_code where Type_code='$data'");
        return $district->result();
    }

    public function getPattanoJSON($distCode, $subdivcode, $circode, $mouzacode, $lotno, $villagecode, $pattatypecode)
    {
        $this->utilityclass->switchDb($this->session->userdata('dcode'));
        $district = $this->db->query("select patta_no from   jama_patta  where dist_code =? "
            . " and "
            . " subdiv_code=? and cir_code=? and "
            . " mouza_pargona_code=? and "
            . " vill_townprt_code =? and lot_no=? "
            . " and patta_type_code =?", array($distCode, $subdivcode, $circode, $lotno, $mouzacode, $villagecode, $pattatypecode));

        return $district->result();
    }

    
    ///////////////////////////////// INSERT to Nok TEmp ///////////////////////////
    function FmAddApplicant($arr)
    {
        $arr['dob'] = date('Y-m-d', strtotime($arr['dob']));
        $data = $this->db->insert('nok_tmp', $arr);
        return $data;
    }
    ///////////////////////////////// end INSERT to Nok TEmp ///////////////////////////

    ///////////////////////////////// Get Nok TEmp DATA///////////////////////////
    function NokTempData($case_id)
    {
        $data = $this->db->query("Select * from nok_tmp where case_id=?", array($case_id))->result();
        foreach ($data as $key => $row) {
            $relation_name = $this->db->query("SELECT * FROM  master_guard_rel where guard_rel =?", array($row->relation))->row();
            $data[$key]->relation_name = $relation_name->guard_rel_desc_as;
        }
        return $data;
    }
    ///////////////////////////////// END Get Nok TEmp DATA///////////////////////////

    ///////////////////////////////// DELETE APPLICANT SINGLE ROW START///////////////////////////
    public function DeleteNokTmpFMApp($row_id, $case_id)
    {
        $this->db->where('serial_id', $row_id);
        $this->db->where('case_id', $case_id);
        $data = $this->db->delete('nok_tmp');
        return $data;
    }
    ///////////////////////////////// DELETE APPLICANT SINGLE ROW end///////////////////////////

    ///////////////GET GENDER////////////////////
    public function getGenders()
    {
        return $this->db->query("SELECT * FROM master_gender")->result();
    }
    //////////////24-02-22//////////////////
    function addApplicantOMutation($arr)
    {
        $ins = [
            'case_id' => $arr['case_id'],
            'name_asm' => $arr['name_asm'],
            'guardian_name_asm' => $arr['guardian_name_asm'],
            'relation' => $arr['relation'],
            'gender' => $arr['gender'],
            'address' => $arr['address'],
            'dob' => date('Y-m-d', strtotime($arr['dob'])),
            'user_code' => $arr['user_code'],
            'ip' => $arr['ip'],
        ];
        $data = $this->db->insert('nok_tmp', $ins);
        return $data;
    }
    //////////////END 24-02-22///////////////////
    public function getDocument($case)
    {
        $data = $this->db->query("SELECT * FROM supportive_document WHERE case_no=? order by id ASC", array($case))->result();
        return $data;
    }
    ////////////// 02-04-22///////////////////
    public function getNOCForACPP($case)
    {
        $data = $this->db->query("SELECT * FROM supportive_document WHERE case_no=? 
        AND file_name=? ORDER BY ID DESC LIMIT 1", array($case, NOC));
        return $data;
    }

    public function getAllotmentCertificateACPP($case)
    {
        $data = $this->db->query("SELECT * FROM supportive_document WHERE case_no=? 
        AND file_name=? ORDER BY ID DESC LIMIT 1", array($case, ALLOT_CERT));
        return $data;
    }


    /////06-04-22 for field mutation
    public function getSecondPartyPattadarList($dag, $pno, $ptype, $d, $s, $c, $m, $l, $v, $case)
    {

        $pattadar = $this->db->query("SELECT distinct(A.pdar_id), 
        A.pdar_name, A.pdar_father, A.pdar_add1, A.pdar_mobile FROM chitha_pattadar A 
        JOIN chitha_dag_pattadar B ON A.dist_code=B.dist_code AND A.subdiv_code=B.subdiv_code 
        AND A.cir_code=B.cir_code AND A.mouza_pargona_code=B.mouza_pargona_code 
        AND A.lot_no=B.lot_no AND A.vill_townprt_code=B.vill_townprt_code 
        AND A.patta_no=B.patta_no AND A.patta_type_code=B.patta_type_code 
        AND A.pdar_id=B.pdar_id WHERE B.dag_no=? AND B.p_flag!=? AND A.dist_code=? 
        AND A.subdiv_code=? AND A.cir_code=? AND A.mouza_pargona_code=? AND A.lot_no=? 
        AND A.vill_townprt_code=? AND A.patta_no=? AND A.patta_type_code=? 
        AND A.pdar_id NOT IN 
        (SELECT pdar_id FROM field_mut_pattadar WHERE dist_code=? AND subdiv_code=? 
        AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? 
        AND case_no=? )", array(
            $dag, '1', $d, $s, $c, $m, $l, $v, $pno, $ptype,
            $d, $s, $c, $m, $l, $v, $case
        ));
        return $pattadar;
    }

    /////07-04-22 for office mutation
    public function getSecondPartyPattadarListOM($dag, $pno, $ptype, $d, $s, $c, $m, $l, $v, $petitionNo)
    {

        $pattadar = $this->db->query("SELECT distinct(A.pdar_id), 
        A.pdar_name, A.pdar_father, A.pdar_add1, A.pdar_mobile FROM chitha_pattadar A 
        JOIN chitha_dag_pattadar B ON A.dist_code=B.dist_code AND A.subdiv_code=B.subdiv_code 
        AND A.cir_code=B.cir_code AND A.mouza_pargona_code=B.mouza_pargona_code 
        AND A.lot_no=B.lot_no AND A.vill_townprt_code=B.vill_townprt_code 
        AND A.patta_no=B.patta_no AND A.patta_type_code=B.patta_type_code 
        AND A.pdar_id=B.pdar_id WHERE B.dag_no=? AND B.p_flag!=? AND A.dist_code=? 
        AND A.subdiv_code=? AND A.cir_code=? AND A.mouza_pargona_code=? AND A.lot_no=? 
        AND A.vill_townprt_code=? AND A.patta_no=? AND A.patta_type_code=? 
        AND A.pdar_id NOT IN 
        (SELECT pdar_id FROM petition_pattadar WHERE dist_code=? AND subdiv_code=? 
        AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? 
        AND petition_no=? )", array(
            $dag, '1', $d, $s, $c, $m, $l, $v, $pno, $ptype,
            $d, $s, $c, $m, $l, $v, $petitionNo
        ));
        return $pattadar;
    }


    //get pending office partition cases in co end--------SEARCH-13022023
    public function getPendingOfficePartCaseCOend($dist_code, $subdiv_code, $cir_code, $start, $length, $order, $mouza_code, $lot_no, $vill_mouza_code, $vill_lot_no, $village_code, $searchByCol_0)
    {
        $col = 0;
        $dir = "asc";
        if (!empty($order)) {
            foreach ($order as $o) {
                $col = $o['column'];
                $dir = $o['dir'];
            }
        }
        if ($dir != "asc" && $dir != 'desc') {
            $dir = 'desc';
        }
        $valid_columns = array(
            0   => 'petition_no',
        );
        if (!isset($valid_columns[$col])) {
            $order = null;
        } else {
            $order = $valid_columns[$col];
        }
        if ($order != null) {
            $this->db->order_by($order, $dir);
        }
        if (!empty($searchByCol_0)) {
            // $this->db->like('case_no', strtoupper($searchByCol_0));
            // $this->db->like('basundhara', strtoupper($searchByCol_0));
            $this->db->where("(application_ref_no like '%$searchByCol_0%' or case_no like '%$searchByCol_0%' or basundhara like '%$searchByCol_0%')");
        }
        if (!empty($mouza_code)) {
            $this->db->where('mouza_pargona_code', $mouza_code);
        }
        if (!empty($lot_no)) {
            $this->db->where('lot_no', $lot_no);
        }
        if (!empty($vill_mouza_code)) {
            $this->db->where('mouza_pargona_code', $vill_mouza_code);
        }
        if (!empty($vill_lot_no)) {
            $this->db->where('lot_no', $vill_lot_no);
        }
        if (!empty($village_code)) {
            $this->db->where('vill_townprt_code', $village_code);
        }
        $this->db->select('petition_basic.*,basundhar_application.basundhara');
        $this->db->join('basundhar_application', 'petition_basic.case_no = basundhar_application.dharitree', 'left');
        $this->db->where('dist_code', $dist_code);
        $this->db->where('subdiv_code', $subdiv_code);
        $this->db->where('cir_code', $cir_code);
        $this->db->where('petition_basic.not_fresh', null);
        $this->db->where("(petition_basic.status != 'D' or petition_basic.status is null)");
        $this->db->where('petition_basic.lm_note_yn', null);
        $this->db->where('petition_basic.mut_type', '04');
        // $this->db->where('date(date_entry) >=', $define_date);
        $this->db->limit($length, $start);
        $query = $this->db->get('petition_basic');
        //log_message('error',$this->db->last_query());
        if ($query->num_rows() > 0) {
            $data['data_results'] = $query->result();
            if (!empty($mouza_code)) {
                $this->db->where('mouza_pargona_code', $mouza_code);
            }
            if (!empty($lot_no)) {
                $this->db->where('lot_no', $lot_no);
            }
            if (!empty($vill_mouza_code)) {
                $this->db->where('mouza_pargona_code', $vill_mouza_code);
            }
            if (!empty($vill_lot_no)) {
                $this->db->where('lot_no', $vill_lot_no);
            }
            if (!empty($village_code)) {
                $this->db->where('vill_townprt_code', $village_code);
            }
            if (!empty($searchByCol_0)) {
                $this->db->like('case_no', strtoupper($searchByCol_0));
            }
            $this->db->where('dist_code', $dist_code);
            $this->db->where('subdiv_code', $subdiv_code);
            $this->db->where('cir_code', $cir_code);
            $this->db->where('petition_basic.not_fresh', null);
            $this->db->where("(petition_basic.status != 'D' or petition_basic.status is null)");
            $this->db->where('petition_basic.lm_note_yn', null);
            $this->db->where('petition_basic.mut_type', '04');
            // $this->db->where('date(date_entry) >=', $define_date);
            //$this->db->limit($length, $start);
            $data['total_records'] = $this->db->count_all_results('petition_basic');
            return $data;
        }
    }

    //end---------

    function proceeding_order($case_no, $order = null)
    {

        $dist_code = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $user_code = $this->session->userdata('user_code');
        $user_desig_code = $this->session->userdata('user_desig_code');
        $date_entry = date('Y-m-d h:i:s');

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
}
