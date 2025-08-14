<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MutationModel extends CI_Model {

    public function getDistricts() {
		//$this->dbswitch();
        $district = $this->db->query("select * from   location where dist_code !='00'  and "
                . " subdiv_code='00' and cir_code='00' and mouza_pargona_code='00' and "
                . " vill_townprt_code='00000' and lot_no='00'");
        return $district->result();
    }

    public function getSubDivJSON($distCode) {
		//$this->dbswitch();
        $district = $this->db->query("select * from   location where dist_code =?  and "
                . " subdiv_code!='00' and cir_code='00' and mouza_pargona_code='00' and "
                . " vill_townprt_code='00000' and lot_no='00'",array($distCode));
        return $district->result();
    }

    public function getCirCodeJSON($distCode, $subdivcode) {
		//$this->dbswitch();
        $district = $this->db->query("select * from   location where dist_code =?  and "
                . " subdiv_code=? and cir_code!='00' and mouza_pargona_code='00' and "
                . " vill_townprt_code='00000' and lot_no='00'",array($distCode,$subdivcode));
        return $district->result();
    }

    public function getMouzaJSON($distCode, $subdivcode, $circode) {
		//$this->dbswitch();
        $district = $this->db->query("select * from   location where dist_code =?  and "
                . " subdiv_code=? and cir_code=? and mouza_pargona_code!='00' and "
                . " vill_townprt_code='00000' and lot_no='00'",array($distCode,$subdivcode,$circode));
        
        return $district->result();
    }

    public function getLotNoJSON($distCode, $subdivcode, $circode, $mouzacode) {
	//$this->dbswitch();
        $district = $this->db->query("select *  from   location where dist_code =?  and "
                . " subdiv_code=? and cir_code=? and mouza_pargona_code=? and "
                . " vill_townprt_code='00000' and lot_no!='00' order by lot_no",array($distCode,$subdivcode,$circode,$mouzacode));
        return $district->result();
    }

    public function getVillageCodeJSON($distCode, $subdivcode, $circode, $mouzacode, $lotno) {
        //$this->dbswitch();
        $district = $this->db->query("select distinct loc_name,vill_townprt_code from   location where "
                . "dist_code =?  and "
                . " subdiv_code=? and cir_code=? and mouza_pargona_code=? and "
                . " vill_townprt_code!='00000' and lot_no=?",array($distCode,$subdivcode,$circode,$mouzacode,$lotno));
        
        return $district->result();
    }

    public function getMouza() {
        
    }

    public function getLotNo() {
        
    }

    public function villageTownPort() {
        
    }

    public function getMutationType() {
		//$this->dbswitch();
        $mutation = $this->db->query("select * from   master_field_mut_type");
        return $mutation->result();
    }

    public function getTransferType() {
		//$this->dbswitch();
        $mutation = $this->db->query("select * from   nature_trans_code");
        return $mutation->result();
    }

    public function getLandArea($dag_no) {
		//$this->dbswitch();
		$dist_code = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $vill_code = $this->session->userdata('vill_code');
        $lot_no = $this->session->userdata('lot_no');
		$other =$dag_no;

        $data=$this->db->query("SElect * from   chitha_basic where trim(dag_no)='$other' and  dist_code ='$dist_code'  and "
                . " subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and "
                . " vill_townprt_code='$vill_code' and lot_no='$lot_no' ")->result();
        return $data;
    }

    public function getPattaType() {
		//$this->dbswitch();
        $patta_type = $this->db->query("SELECT * FROM   patta_code where type_code !='0000' and (mutation='a' or mutation='i') order by patta_code");
        return $patta_type->result();
    }
    
    public function getPattaTypeExcludingAksona() {
		//$this->dbswitch();
        $patta_type = $this->db->query("SELECT * FROM   patta_code where type_code !='0000' and (mutation='a') order by patta_code");
        return $patta_type->result();
    }

    public function getGovtPattaType() {
		//$this->dbswitch();
        $patta_type = $this->db->query("SELECT * FROM   patta_code where type_code !='0000' and (mutation='n') and (conversion='n') and (jamabandi='n') order by patta_code");
        return $patta_type->result();
    }
    
    public function getConvertionPattaType() {
		//$this->dbswitch();
        $patta_conv_type = $this->db->query("SELECT * FROM   patta_code where conversion ='y' ");
        return $patta_conv_type->result();
    }

    public function getLandClass() {
		//$this->dbswitch();
        $landclass = $this->db->query("select * from   landclass_code");
        return $landclass->result();
    }

    //modified by bondita
    public function getPattatypeJSON() {
		//$this->dbswitch();
        $district = $this->db->query("Select Type_code,patta_type from   patta_code where Type_code!='0000'");

        return $district->result();
    }

    //

    public function getPattatypeByNoJSON($distCode, $subdivcode, $circode, $mouzacode, $lotno, $villagecode, $pattano) {
		//$this->dbswitch();
        $q = "select patta_type_code from   chitha_basic where dist_code=? and "
                . "subdiv_code=? and "
                . " cir_code=? and lot_no=? and mouza_pargona_code=?"
                . " and vill_townprt_code=? and "
                . " patta_no=?";
        
        $data = $this->db->query($q,array($distCode,$subdivcode,$circode,$lotno,$mouzacode,$villagecode,$pattano))->row()->patta_type_code;
        $district = $this->db->query("Select Type_code,patta_type from   patta_code where Type_code='$data'");
        return $district->result();
    }

    public function getPattanoJSON($distCode, $subdivcode, $circode, $mouzacode, $lotno, $villagecode, $pattatypecode) {
        $this->dbswitch();
		$district = $this->db->query("select patta_no from   jama_patta  where dist_code =? "
                . " and "
                . " subdiv_code=? and cir_code=? and "
                . " mouza_pargona_code=? and "
                . " vill_townprt_code =? and lot_no=? "
                . " and patta_type_code =?",array($distCode,$subdivcode,$circode,$lotno,$mouzacode,$villagecode,$pattatypecode));

        return $district->result();
    }

    public function dbswitch(){       
     $CI=&get_instance();
     if($this->session->userdata('dist_code') == "02"){
        $this->db=$CI->load->database('dha3', TRUE);    
     } else if($this->session->userdata('dist_code') == "05"){
        $this->db=$CI->load->database('dha1', TRUE);    
      } else if($this->session->userdata('dist_code') == "10"){
        $this->db=$CI->load->database('dha24', TRUE);       
     } else if($this->session->userdata('dist_code') == "13"){
        $this->db=$CI->load->database('dha2', TRUE);    
     }  else if($this->session->userdata('dist_code') == "17"){
        $this->db=$CI->load->database('dha4', TRUE);    
     }  else if($this->session->userdata('dist_code') == "15"){
        $this->db=$CI->load->database('dha5', TRUE);    
     }  else if($this->session->userdata('dist_code') == "14"){
        $this->db=$CI->load->database('dha6', TRUE);    
     }  else if($this->session->userdata('dist_code') == "07"){
        $this->db=$CI->load->database('dha7', TRUE);    
     }  else if($this->session->userdata('dist_code') == "03"){
        $this->db=$CI->load->database('dha8', TRUE);    
     }  else if($this->session->userdata('dist_code') == "18"){
        $this->db=$CI->load->database('dha9', TRUE);    
     }  else if($this->session->userdata('dist_code') == "12"){
        $this->db=$CI->load->database('dha13', TRUE);   
     }  else if($this->session->userdata('dist_code') == "24"){
        $this->db=$CI->load->database('dha10', TRUE);   
     }  else if($this->session->userdata('dist_code') == "06"){
        $this->db=$CI->load->database('dha11', TRUE);   
     }  else if($this->session->userdata('dist_code') == "11"){
        $this->db=$CI->load->database('dha12', TRUE);   
     }  else if($this->session->userdata('dist_code') == "12"){
        $this->db=$CI->load->database('dha13', TRUE);   
     }  else if($this->session->userdata('dist_code') == "16"){
        $this->db=$CI->load->database('dha14', TRUE);   
     }  else if($this->session->userdata('dist_code') == "32"){
        $this->db=$CI->load->database('dha15', TRUE);   
     }  else if($this->session->userdata('dist_code') == "33"){
        $this->db=$CI->load->database('dha16', TRUE);   
     }  else if($this->session->userdata('dist_code') == "34"){
        $this->db=$CI->load->database('dha17', TRUE);   
     }  else if($this->session->userdata('dist_code') == "21"){
        $this->db=$CI->load->database('dha18', TRUE);   
     }  else if($this->session->userdata('dist_code') == "08"){
        $this->db=$CI->load->database('dha19', TRUE);   
     }  else if($this->session->userdata('dist_code') == "35"){
        $this->db=$CI->load->database('dha20', TRUE);   
     }  else if($this->session->userdata('dist_code') == "36"){
        $this->db=$CI->load->database('dha21', TRUE);   
     }  else if($this->session->userdata('dist_code') == "37"){
        $this->db=$CI->load->database('dha22', TRUE);   
     }  else if($this->session->userdata('dist_code') == "25"){
        $this->db=$CI->load->database('dha23', TRUE);   
     }                                                                                                                                                                                                              
}

}
