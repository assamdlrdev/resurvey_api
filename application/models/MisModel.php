<?php

//this file is used by pranob//pranob//pranob//pranob
class MisModel extends CI_Model {

    //function created for displaying the district name
    public function getDistrictName($dist_code) {
        //$db=  $this->session->userdata('db');
        // $this->dbswitch();
        $district = $this->db->query("select loc_name AS district from   location where dist_code ='$dist_code'  and "
                . " subdiv_code='00' and cir_code='00' and mouza_pargona_code='00' and "
                . " vill_townprt_code='00000' and lot_no='00'");
        return $district->result();
    }

    //function created for displaying the subdivision name
    public function getSubDivName($dist_code, $subdiv_code) {
        // $this->dbswitch();
        //$db=  $this->session->userdata('db');
        $subdiv = $this->db->query("select loc_name AS subdiv from   location where dist_code ='$dist_code'  and "
                . " subdiv_code='$subdiv_code' and cir_code='00' and mouza_pargona_code='00' and "
                . " vill_townprt_code='00000' and lot_no='00'");
        return $subdiv->result();
    }

    //function created for displaying the circle name
    public function getCircleName($dist_code, $subdiv_code, $circle_code) {
		//$db=  $this->session->userdata('db');
        // $this->dbswitch();
        $circle = $this->db->query("select loc_name AS circle from   location where dist_code ='$dist_code'  and "
                . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='00' and "
                . " vill_townprt_code='00000' and lot_no='00'");
        return $circle->result();
    }

    //function created for displaying the mouza name
    public function getMouzaName($dist_code, $subdiv_code, $circle_code, $mouza_code) {
		//$db=  $this->session->userdata('db');
        // $this->dbswitch();
        $mouza = $this->db->query("select loc_name AS mouza from   location where dist_code ='$dist_code'  and "
                . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='$mouza_code' and "
                . " vill_townprt_code='00000' and lot_no='00'");
        return $mouza->result();
    }

    //function created for displaying the lot No
    public function getLotName($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no) {
		//$db=  $this->session->userdata('db');
        // $this->dbswitch();
        $lot = $this->db->query("select loc_name as lot_no from   location where dist_code ='$dist_code'  and "
                . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='$mouza_code' and "
                . " vill_townprt_code='00000' and lot_no='$lot_no'");
        return $lot->result();
    }

    //function created for displaying the village name
    public function getVillageName($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code) {
		//$db=  $this->session->userdata('db');
        // $this->dbswitch();
        $village = $this->db->query("select loc_name AS village from   location where dist_code ='$dist_code'  and "
                . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='$mouza_code' and "
                . " vill_townprt_code='$vill_code' and lot_no='$lot_no'");
        return $village->result();
    }
    
    public function getMouzaList($dist_code,$subdiv_code,$cir_code){
		//$db=  $this->session->userdata('db');
        // $this->dbswitch();
        $mouza = $this->db->query("select loc_name AS mouza, mouza_pargona_code AS mouza_code  from   location where dist_code ='$dist_code'  and "
                . " subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code <> '00' and "
                . " vill_townprt_code='00000' and lot_no='00'");
        return $mouza->result();
    }
    public function getVillageList($dist_code,$subdiv_code,$cir_code,$mouza_pargona_code,$lot_no){
		//$db=  $this->session->userdata('db');
        // $this->dbswitch();
        $village = $this->db->query("select loc_name AS village, vill_townprt_code AS vill_code from   location where dist_code ='$dist_code'  and "
                . " subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and "
                . " vill_townprt_code <> '00000' and lot_no='$lot_no'");
        return $village->result();
    }

	
	 //modified by bondita
    
      public function getPattaTypeName($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code,$pattano){
		//$db=  $this->session->userdata('db');
        // $this->dbswitch();
        $village = $this->db->query("select loc_name AS village from   location where dist_code ='$dist_code'  and "
                . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='$mouza_code' and "
                . " vill_townprt_code='$vill_code' and lot_no='$lot_no'");
        return $village->result();
    }
      public function getpattatypeNameforJamabandi($pattatypecode){
		  $db=  $this->session->userdata('db');
        $village = $this->db->query("Select patta_type from   patta_code where Type_code='$pattatypecode'");
        return $village->result();
    }
    
    //modification ends here
	
    //function starts for irregated and non irregated land area
    public function getCrop($dist_code, $subdiv_code, $circle_code, $mouza_code) {
        //$db=  $this->session->userdata('db');
        // $this->dbswitch();
        $crop = $this->db->query("select COUNT(dag_no) AS dag_no,SUM(crop_land_area_b) AS bigha,"
                . "SUM(crop_land_area_k) AS katha, SUM(crop_land_area_lc) AS lessa from   chitha_mcrop where dist_code ='$dist_code'  and "
                . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='$mouza_code' and "
                . " source_of_water!='02'");
        return $crop->result();
    }

    public function getNonCrop($dist_code, $subdiv_code, $circle_code, $mouza_code) {
        //$db=  $this->session->userdata('db');
        // $this->dbswitch();
        $noncrop = $this->db->query("select COUNT(dag_no) AS dag_no, SUM(crop_land_area_b) AS bigha,"
                . "SUM(crop_land_area_k) AS katha, SUM(crop_land_area_lc) AS lessa from   chitha_mcrop where dist_code ='$dist_code'  and "
                . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='$mouza_code' and "
                . " source_of_water='02'");

        return $noncrop->result();
    }

    //function ends for irregated and non irregated land area
    //function created for tenant list
    public function getTenantData($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code) {
        //$db=  $this->session->userdata('db');
        // $this->dbswitch();
        $tenant = $this->db->query("select * from   chitha_tenant AS ct JOIN tenant_type AS tt ON ct.type_of_tenant=tt.type_code where ct.dist_code ='$dist_code'  and "
                . " ct.subdiv_code='$subdiv_code' and ct.cir_code='$circle_code' and ct.mouza_pargona_code='$mouza_code' and "
                . " ct.vill_townprt_code='$vill_code' and ct.lot_no='$lot_no' ORDER BY ct.dag_no ASC");

        return $tenant->result();
    }

    public function getMouzawiseVillages($dist_code, $subdiv_code, $circle_code, $mouza_code) {
		//$db=  $this->session->userdata('db');
        // $this->dbswitch();
        $village = $this->db->query("select loc_name AS village,lot_no from   location where dist_code ='$dist_code'  and "
                . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='$mouza_code' and "
                . "vill_townprt_code<>'00000'");
        return $village->result();
    }

    public function getTenantList($dist_code, $subdiv_code, $circle_code, $mouza_code) {
		//$db=  $this->session->userdata('db');
        // $this->dbswitch();
        $tenantList = $this->db->query("select count(*) AS no_of_tenant from   chitha_tenant AS ct JOIN tenant_type AS tt ON ct.type_of_tenant=tt.type_code where ct.dist_code ='$dist_code'  and "
                . " ct.subdiv_code='$subdiv_code' and ct.cir_code='$circle_code' and ct.mouza_pargona_code='$mouza_code' ");

        return $tenantList->result();
    }

    public function getMutPart($dist_code, $subdiv_code, $circle_code, $year, $month_name) {
        //$db=  $this->session->userdata('db');
        // $this->dbswitch();
        $month = $month_name;
        $year = $year;

        $First_Last = $this->utilityclass->First_Last_Date_of_Month($year, $month);

        //print_r($First_Last);
        $start_date = $First_Last[0] . " 00:00:00";
        $end_date = $First_Last[1] . " 23:59:59";

        $FieldMutPartData = $this->db->query("select distinct(case_no),order_type_code,new_dag_no from   chitha_col8_order where dist_code ='$dist_code'  and "
                . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and "
                . " extract(month from   date_entry)='$month' and extract(year from   date_entry)='$year' ");
		// echo "select * from   chitha_col8_order where dist_code ='$dist_code'  and "
                // . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and "
                // . " extract(month from   date_entry)='$month' and extract(year from   date_entry)='$year' ";
		
        $FieldMutPartData1['FMut'] = $FieldMutPartData->result();

        $fieldMutpartTot = $this->db->query("select distinct(case_no),mut_type from   field_mut_basic where dist_code='$dist_code'  and  subdiv_code='$subdiv_code' and cir_code='$circle_code' and 	"
                . " order_passed is not null and extract(month from   date_entry)='$month' and extract(year from   date_entry)='$year'");
		//echo "<br>";		
		// echo "select * from   field_mut_basic where dist_code='$dist_code'  and  subdiv_code='$subdiv_code' and cir_code='$circle_code' and "
                // . " order_passed is not null and extract(month from   date_entry)='$month' and extract(year from   date_entry)='$year'";
		
				
        $FieldMutPartData1['FMutTot'] = $fieldMutpartTot->result();
        //print_r($FieldMutPartData1);
        //we will get field mutation & partition data from   this query
        //Office mutation PASS ORDERS
       
        $OfficeMutationPassData = $this->db->query("select * from   petition_basic where dist_code ='$dist_code'  and "
                . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and "
                . " (status='F' or order_passed is not null) and  extract(month from   submission_date)='$month' and extract(year from   submission_date)='$year' ");
		// echo "select * from   petition_basic where dist_code ='$dist_code'  and "
                // . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and "
                // . " (status='F' or order_passed is not null) and  extract(month from   submission_date)='$month' and extract(year from   submission_date)='$year' ";
				
		
        $OfficeMutationPassData1['OMutPass'] = $OfficeMutationPassData->result();

        //AP cancellation/////////////////
        $sql = "Select count(case_no) as tot from   apcancel_petition_basic where 
		extract(month from   submission_date)='$month' and extract(year from   submission_date)='$year' and  dist_code ='$dist_code'  and "
                . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and "
                . " order_passed='Y' ";
        $apcancellation['registercase'] = $this->db->query($sql)->row();
        $sql = "Select count(case_no) as deliver from    apcancel_petition_basic where extract(month from   submission_date)='$month' and extract(year from   submission_date)='$year' and  dist_code ='$dist_code'  and "
                . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and "
                . " co_chitha_corrected_yn='Y' or co_chitha_corrected_yn='y'";
			
        $apcancellation['chithacorrectcase'] = $this->db->query($sql)->row();
        //we will get office mutation & partition data from   this query
             $sql = "Select count(misc_case_no) as tot from   misc_case_basic where extract(month from   submission_date)='$month' and extract(year from   submission_date)='$year' and  dist_code ='$dist_code'  and "
                . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and "
                . " status='10' ";
            $namecorrection['register'] = $this->db->query($sql)->row();
            $sql = "Select count(misc_case_no) as deliver from    misc_case_basic where extract(month from   submission_date)='$month' and extract(year from   submission_date)='$year' and  dist_code ='$dist_code'  and "
                . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and "
                . " status='10' ";
            $namecorrection['chithacorrect'] = $this->db->query($sql)->row();
        
        //query for office mutation (CORRECTED)
            //we will getreclassification
            $sql = "Select count(case_no) as tot from   chitha_rmk_reclassification where 
			extract(month from   co_chitha_updated_date)='$month' and extract(year from   co_chitha_updated_date)='$year' and  dist_code ='$dist_code'  and "
                . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and "
                . " co_chitha_updated_yn='Y' ";
           $reclass['reclassreg'] = $this->db->query($sql)->row();
           $sql = "Select count(case_no) as deliver from    t_reclassification where extract(month from   co_chitha_updated_date)='$month' and extract(year from   co_chitha_updated_date)='$year' and  dist_code ='$dist_code'  and "
                . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and "
                . " rkg_chitha_updated_yn='Y' ";
			
           $reclass['reclasscorr'] = $this->db->query($sql)->row();
        
        //query for office mutation (CORRECTED)
            
            

        $OfficeMutationCOrrData = $this->db->query("select distinct(case_no),ord_type_code from   Chitha_Rmk_Ordbasic where dist_code ='$dist_code'  and "
                . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and "
                . " extract(month from   ord_date)='$month' and extract(year from   ord_date)='$year'");

        $OfficeMutationCOrrData1['OMutCorr'] = $OfficeMutationCOrrData->result();

        $data = array_merge($FieldMutPartData1, $OfficeMutationPassData1, $OfficeMutationCOrrData1,$apcancellation,$namecorrection,$reclass);
        //var_dump($data);
        return $data;
    }

    public function getAP_PP_SPP_SAP_landarea($dist_code, $subdiv_code, $circle_code, $mouza_code) {
        //$db=  $this->session->userdata('db');
        // $this->dbswitch();
        $patta11 = $this->db->query("select patta_type from   patta_code  where type_code IN ('0201','0202','0203','0204','0216','0217','0223','0230','0231','0232') ORDER BY type_code ASC");

        $patta['patta_type'] = $patta11->result();

        $chitha_basic_data1 = $this->db->query("select chitha_basic.dag_area_b,chitha_basic.dag_area_k,chitha_basic.dag_area_lc,chitha_basic.patta_type_code from   chitha_basic JOIN patta_code ON patta_code.type_code=chitha_basic.patta_type_code "
                . "where chitha_basic.dist_code ='$dist_code' and chitha_basic.subdiv_code='$subdiv_code' and chitha_basic.cir_code='$circle_code' "
                . "and chitha_basic.mouza_pargona_code='$mouza_code' ORDER BY chitha_basic.patta_type_code ASC");

        $chithadata1['AreaLand'] = $chitha_basic_data1->result();

        $data = array_merge($patta, $chithadata1);

        return $data;
    }

    public function getAP_PP_SPP_SAP_landarea_vill($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code) {
        //$db=  $this->session->userdata('db');
        // $this->dbswitch();
		
		$patta11 = $this->db->query("select patta_type from   patta_code  where type_code IN ('0201','0202','0203','0204','0216','0217','0223','0230','0231','0232') ORDER BY type_code ASC");

        $patta['patta_type'] = $patta11->result();

        $chitha_basic_data1 = $this->db->query("select chitha_basic.dag_area_b,chitha_basic.dag_area_k,chitha_basic.dag_area_lc,chitha_basic.patta_type_code from   chitha_basic JOIN patta_code ON patta_code.type_code=chitha_basic.patta_type_code "
                . " where chitha_basic.dist_code ='$dist_code' and chitha_basic.subdiv_code='$subdiv_code' and chitha_basic.cir_code='$circle_code' "
                . " and chitha_basic.mouza_pargona_code='$mouza_code' and "
                . " chitha_basic.vill_townprt_code='$vill_code' and chitha_basic.lot_no='$lot_no'"
                . " ORDER BY chitha_basic.patta_type_code ASC");


        $chithadata1['AreaLand'] = $chitha_basic_data1->result();

        $data = array_merge($patta, $chithadata1);

        return $data;
    }
    
    public function getAP_PP_SPP_SAP_landarea_area($dist_code, $subdiv_code, $circle_code, $mouza_code) {
        //$db=  $this->session->userdata('db');
        // $this->dbswitch();
        $patta11 = $this->db->query("select patta_type,type_code from   patta_code  where type_code IN ('0201','0202','0203','0204') ORDER BY type_code ASC");

        $patta['patta_type'] = $patta11->result();

        $chitha_basic_data1 = $this->db->query("select chitha_basic.dag_area_b,chitha_basic.dag_area_k,chitha_basic.dag_area_lc,chitha_basic.patta_type_code from   chitha_basic JOIN patta_code ON patta_code.type_code=chitha_basic.patta_type_code "
                . "where chitha_basic.dist_code ='$dist_code' and chitha_basic.subdiv_code='$subdiv_code' and chitha_basic.cir_code='$circle_code' "
                . "and chitha_basic.mouza_pargona_code='$mouza_code' ORDER BY chitha_basic.patta_type_code ASC");

        $chithadata1['AreaLand'] = $chitha_basic_data1->result();

        $data = array_merge($patta, $chithadata1);

        return $data;
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


public function getDistrictNameUuid() {

        $data=$this->db->query("Select uuid,dag_no from supportive_document group by uuid,dag_no");
        $alldata=$data->result();


        foreach($alldata as $key => $val) {

        $doc=$this->db->query("Select doc_flag from supportive_document where dag_no='$val->dag_no' and uuid='$val->uuid' order by doc_flag asc ");
        $doc_flag=$doc->result();

        $district = $this->db->query("select dist_code,subdiv_code,cir_code,mouza_pargona_code,lot_no,vill_townprt_code from   location where  uuid='$val->uuid'");
        $districtdata= $district->row();

        $districtname= $this->db->query("select loc_name AS districtname from   location where  dist_code='$districtdata->dist_code' and "
                . " subdiv_code='00' and cir_code='00' and mouza_pargona_code='00' and "
                . " vill_townprt_code='00000' and lot_no='00'")->row();


        $subname= $this->db->query("select loc_name AS subdiv from   location where dist_code ='$districtdata->dist_code'  and "
                . " subdiv_code='$districtdata->subdiv_code' and cir_code='00' and mouza_pargona_code='00' and "
                . " vill_townprt_code='00000' and lot_no='00'")->row();

        $cirname= $this->db->query("select loc_name AS cirname from   location where dist_code ='$districtdata->dist_code'  and "
                . " subdiv_code='$districtdata->subdiv_code'  and cir_code='$districtdata->cir_code' and mouza_pargona_code='00' and "
                . " vill_townprt_code='00000' and lot_no='00'")->row();

        $mouzaname= $this->db->query("select loc_name AS mouza from   location where dist_code ='$districtdata->dist_code'  and "
                . " subdiv_code='$districtdata->subdiv_code'  and cir_code='$districtdata->cir_code' and mouza_pargona_code='$districtdata->mouza_pargona_code'  and "
                . " vill_townprt_code='00000' and lot_no='00'")->row();

        $lotname= $this->db->query("select loc_name as lot_no from   location where dist_code ='$districtdata->dist_code'  and "
                . " subdiv_code='$districtdata->subdiv_code'  and cir_code='$districtdata->cir_code' and mouza_pargona_code='$districtdata->mouza_pargona_code'  and "
                . " vill_townprt_code='00000' and lot_no='$districtdata->lot_no'")->row();


        $villname= $this->db->query("select loc_name as villname from   location where dist_code ='$districtdata->dist_code'  and "
                . " subdiv_code='$districtdata->subdiv_code'  and cir_code='$districtdata->cir_code' and mouza_pargona_code='$districtdata->mouza_pargona_code'  and "
                . " vill_townprt_code='$districtdata->vill_townprt_code' and lot_no='$districtdata->lot_no'")->row();
        //echo $this->db->last_query();

       
        $application[] = array(
                'districtname' =>$districtname->districtname,
                'cirname' =>$cirname->cirname,
                'villname' =>$villname->villname,
                'dag_no' =>$val->dag_no,
                'doc_flag'=>$doc_flag

        );



        }
        // var_dump($application);
        // exit;

        return $application;
        
    }


}
