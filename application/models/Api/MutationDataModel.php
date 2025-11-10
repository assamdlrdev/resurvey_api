<?php

class MutationDataModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
        $this->load->model('Api/ChithaModel');
	}

    public function generateCaseName($dist_code, $subdiv_code, $cir_code){
        // $dist_code=$this->session->userdata('dist_code');
        // $subdiv_code=$this->session->userdata('subdiv_code');
        // $cir_code=$this->session->userdata('cir_code');
        
        
        $financialyeardate = (date('m') < '07') ? date('Y', strtotime('-1 year')) . "-" . date('y') : date('Y') . "-" . date('y', strtotime('+1 year'));
        $q = "Select dist_abbr,cir_abbr from location where dist_code=? and subdiv_code=? and cir_code=? and mouza_pargona_code!='00' ";
        $abbrname = $this->db->query($q, [$dist_code, $subdiv_code, $cir_code])->row();
        if(!empty($abbrname))
        {
            $cir_dist_name = $abbrname->dist_abbr . "/" . $abbrname->cir_abbr;
            $case_no = $cir_dist_name . "/" . $financialyeardate . "/" ;
            return $case_no;
        }
        return false;
    }

    public function generateFieldPetitionNo(){
        $petition_no = $this->db->query("select nextval('seq_max_field') as count ")->row()->count;
        return $petition_no;
    }

    // public function autoUpdate($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code, $petition_no, $dag_no) {

    // }


    function proceeding_order($tokenData, $case_no,$order=null){

        $dist_code=$tokenData->dcode;
        $subdiv_code = $tokenData->subdiv_code;
        $cir_code = $tokenData->cir_code;
        $user_code=$tokenData->usercode;
        $user_desig_code = $tokenData->user_desig_code;
        $date_entry=date('Y-m-d h:i:s');

        if($user_desig_code =='CO'){
            $status='Final';
        }
        else{
            $status='Pending';
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
            'ip' => $_SERVER['REMOTE_ADDR']
        );
        $tstatus1=$this->db->insert("petition_proceeding", $data); //********
        if(!$tstatus1 || $this->db->affected_rows() < 1){
            log_message("error"," #ERRCOMUTORDER030 PROCEEDING_INSERT_ERROR".$this->db->last_query());
            return [
                'status' => 'n',
                'msg' => '#ERRCOMUTORDER030 Could not create order!'
            ];
        }
        
        return [
            'status' => 'y',
            'msg' => 'Proceeding Recorded!'
        ];
    }



    public function autoUpdateForField($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code, $petition_no, $dag_no, $user_code)
    {        
            //$db=  $this->session->userdata('db');
            // $locationData = array(
            //     'dist_code' => $dist_code,
            //     'subdiv_code' => $subdiv_code,
            //     'cir_code' => $cir_code,
            //     'lot_no' => $lot_no,
            //     'vill_code' => $vill_code,
            //     'mouza_pargona_code' => $mouza_pargona_code,
            // );
            // $generation_pdar_id=false;
            // $year_no = year_no;

            $col8order_cron_no = $this->db->query("select max(col8order_cron_no)+1 as cron_no from   chitha_col8_order where dist_code='$dist_code' and subdiv_code='$subdiv_code' and "
                    . "cir_code='$cir_code' and lot_no='$lot_no' and mouza_pargona_code='$mouza_pargona_code' and vill_townprt_code='$vill_code' and dag_no='$dag_no'")->row()->cron_no;
            if ($col8order_cron_no == null) {
                $col8order_cron_no = 1;
            }
            
            $t_order_data_query = "select * from   t_chitha_col8_order where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and lot_no='$lot_no' and "
                    . "mouza_pargona_code='$mouza_pargona_code' and vill_townprt_code='$vill_code' and petition_no=$petition_no and dag_no='$dag_no'";// and iscorrected_inco is null";
            $t_data_order = $this->db->query($t_order_data_query);
            if ($t_data_order == null || $t_data_order->num_rows() < 1)
            {
                
                log_message("error"," #ERRCOMUTORDER008 No data found.");
                return [
                    'status' => 'n',
                    'msg' => '#ERRCOMUTORDER008 Could not create order!'
                ];
            }
            $t_data_order = $t_data_order->result();
            $case_no =null;
            foreach ($t_data_order as $ord) {
                $case_no = $ord->case_no;
                // $data_order = array();
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
                $tstatus1=$this->db->insert("chitha_col8_order", $data); //************************************************************************************************ insert query
                if (!$tstatus1 || $this->db->affected_rows() < 1)
                {
                    
                    log_message("error"," #ERRCOMUTORDER007 could not insert chitha_col8_order with district: ".$dist_code.", petition_no: ". $petition_no);
                    return [
                        'status' => 'n',
                        'msg' => '#ERRCOMUTORDER007 Could not create order!'
                    ];
                }

                //Checking for occupents
                $t_occup_query = "select * from   t_chitha_col8_occup where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and lot_no='$lot_no' and "
                        . "mouza_pargona_code='$mouza_pargona_code' and vill_townprt_code='$vill_code' and petition_no=$petition_no and dag_no='$dag_no'";// and iscorrected_inco is null";
                $t_occup_data = $this->db->query($t_occup_query);
                if ($t_occup_data == null || $t_occup_data->num_rows() < 0)
                {
                    
                    log_message("error"," #ERRCOMUTORDER009 No data found in t_chitha_col8_occup with district: ".$dist_code.", petition_no: ". $petition_no);
                    return [
                        'status' => 'n',
                        'msg' => '#ERRCOMUTORDER009 Could not create order!'
                    ];
                }
                $t_occup_data = $t_occup_data->result();

                //updating t_chitha_col8_order iscorrected_inco status
                $update_query = "update t_chitha_col8_order  set iscorrected_inco='Y',iscorrected_inco_date='$corrected' where dist_code='$dist_code' and subdiv_code='$subdiv_code' and "
                        . "cir_code='$cir_code' and lot_no='$lot_no' and mouza_pargona_code='$mouza_pargona_code' and vill_townprt_code='$vill_code' and petition_no=$petition_no and "
                        . "dag_no='$dag_no' and iscorrected_inco is null";
                $this->db->query($update_query); //********************************************************************************************* insert query
                if ($this->db->affected_rows() < 1 )
                {
                    
                    log_message("error"," #ERRCOMUTORDER010 Could not update iscorrected_inco in t_chitha_col8_order with district: ".$dist_code.", petition_no: ". $petition_no);
                    return [
                        'status' => 'n',
                        'msg' => '#ERRCOMUTORDER010 Could not create order!'
                    ];
                }                                
                           
                $chitha_basic_update = FALSE;
                // occupants details starts here
                foreach ($t_occup_data as $occ) {

                    $table = 'chitha_basic';

                    $params = [
                        'jama_yn' => null,
                    ];

                    $where = [
                        'dist_code'          => $occ->dist_code,
                        'subdiv_code'        => $occ->subdiv_code,
                        'cir_code'           => $occ->cir_code,
                        'mouza_pargona_code' => $occ->mouza_pargona_code,
                        'lot_no'             => $occ->lot_no,
                        'vill_townprt_code'  => $occ->vill_townprt_code,
                        'dag_no'             => $occ->dag_no,
                        'patta_no'           => trim($occ->patta_no),  // PHP trim to mimic SQL TRIM()
                        'patta_type_code'    => $occ->patta_type_code,
                    ];

                    // Call your model update method:
                    $result0 = $this->ChithaModel->update_table($table, $params, $where);

                    if ($result0 < 1)
                    {
                        log_message("error"," #ERRCOMUTORDER011 Could not update jama_yn in chitha_basic with district: ".$dist_code.", petition_no: ". $petition_no);
                        return [
                            'status' => 'n',
                            'msg' => '#ERRCOMUTORDER011 Could not create order!'
                        ];
                    }  
                    
                    $data = [];
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
                    
                    $tstatus2 = $this->db->insert("chitha_col8_occup", $data); //************************************************************************************************ insert query
                    if (!$tstatus2 || $this->db->affected_rows() < 1)
                    {
                        log_message("error"," #ERRCOMUTORDER012 Could not insert in chitha_col8_occup with district: ".$dist_code.", petition_no: ". $petition_no);
                        return [
                            'status' => 'n',
                            'msg' => '#ERRCOMUTORDER012 Could not create order!'
                        ];
                    }

                    $dag_pattadar = array();
                    $chitha_pattadar = array();

                    $pdar_id = $occ->pdar_id;
                    
                    if ($ord->order_type_code == '02') {
                        // Order Type Code 02 iIs For Field Partition. and 01 is For Field Mutation
                        $pdar_id = $this->db->query("select max(cast(pdar_id as int))+1 as pdar_id from   chitha_pattadar where dist_code='$dist_code' and subdiv_code='$subdiv_code' and "
                                . "cir_code='$cir_code' and lot_no='$lot_no' and mouza_pargona_code='$mouza_pargona_code' and vill_townprt_code='$vill_code' and "
                                . "TRIM(patta_no)=trim('$occ->new_patta_no')")->row()->pdar_id;
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
                    // if(MULTIGENERATION_ACTIVE==1)
                    // {
                    //     $dag_pattadar['p_flag'] = $occ->pdar_strike;
                    // }
                    
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
                    //////////////////////////
                    $chitha_pattadar['pdar_name_eng'] = $occ->pdar_name_eng;
                    $chitha_pattadar['pdar_guard_eng'] = $occ->pdar_guard_eng;
                    //newly added aadhaar details to chitha pattadar----
                    $flagAadhaar = null;
                    $flagPan = null;
                    if($occ->auth_type == 'AADHAAR'){
                        $chitha_pattadar['pdar_aadharno'] = $occ->id_ref_no;
                        $flagAadhaar = $occ->id_ref_no;
                        $flagPan = null;
                    }else if($occ->auth_type == 'PAN'){
                        $chitha_pattadar['pdar_pan_no'] = $occ->id_ref_no;
                        $flagAadhaar = null;
                        $flagPan = $occ->id_ref_no;
                    }
                    $pdarPhoto = $occ->photo;

                    $chitha_pattadar['pdar_photo'] = $occ->photo;
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
                        if ($this->db->affected_rows() < 1)
                        {
                            log_message("error"," #ERRCOMUTORDER013 Could not update new_dag_no in chitha_col8_order with district: ".$dist_code.", petition_no: ". $petition_no);
                            return [
                                'status' => 'n',
                                'msg' => '#ERRCOMUTORDER013 Could not create order!'
                            ];
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
                    
                    $corrected = date('Y-m-d G:i:s');
                    if ((!$chitha_basic_update) && ($ord->order_type_code == '02')) {

                        // This Block Is For Field Partition
                        $chitha_basic_update = TRUE;
                        $sql = "select dag_area_b,dag_area_k,dag_area_lc,dag_area_g,dag_area_kr,dag_revenue from   chitha_basic where dist_code='$occ->dist_code' and "
                                . "subdiv_code='$occ->subdiv_code' and cir_code='$occ->cir_code' and mouza_pargona_code='$occ->mouza_pargona_code' and lot_no='$occ->lot_no' and "
                                . "vill_townprt_code='$occ->vill_townprt_code' and dag_no='$occ->dag_no' and TRIM(patta_no)=trim('$occ->patta_no') and patta_type_code='$occ->patta_type_code' ";
                        $data = $this->db->query($sql)->row();
                        
                           ////// BARAK VALLEY CODE START ////////////
                        if(in_array($dist_code, BARAK_VALLEY)){
                           $chitha_basic['dag_revenue'] = $ord->min_revenue * (($ord->mut_land_area_b * 6400 + $ord->mut_land_area_k * 320 + $ord->mut_land_area_lc*20 + $ord->mut_land_area_g) / 6400.0);
    
                        }
                        else
                        {
                            $chitha_basic['dag_revenue'] = $ord->min_revenue * (($ord->mut_land_area_b * 100 + $ord->mut_land_area_k * 20 + $ord->mut_land_area_lc) / 100.0);
                        }

                        
                        $chitha_basic['dag_local_tax'] = $chitha_basic['dag_revenue'] / 4.0;
                        
                        // $tstatus_ch = $this->db->insert("chitha_basic", $chitha_basic); //************************************************************************************************ insert query
                        $tstatus_ch = $this->ChithaModel->insert_table('chitha_basic',$chitha_basic);
                        if ($tstatus_ch != 1 )
                        {
                            log_message("error"," #ERRCOMUTORDER014 Could not insert in chitha_basic with district: ".$dist_code.", petition_no: ". $petition_no);
                            return [
                                'status' => 'n',
                                'msg' => '#ERRCOMUTORDER014 Could not create order!'
                            ];
                        }
                        

                        $dataNew['dag_no'] = $chitha_basic['dag_no'];
                        $tstatus_ord = $this->db->insert("chitha_col8_order", $dataNew); //************************************************************************************************ insert query
                        if ($tstatus_ord != 1 )
                        {
                            log_message("error"," #ERRCOMUTORDER015 Could not insert in chitha_col8_order with district: ".$dist_code.", petition_no: ". $petition_no);
                            return [
                                'status' => 'n',
                                'msg' => '#ERRCOMUTORDER015 Could not create order!'
                            ];
                        }

                        ////// BARAK VALLEY CODE START ////////////
                        if(in_array($dist_code, BARAK_VALLEY)){

                            $sourcelessa = $data->dag_area_b * 6400 + $data->dag_area_k * 320 + $data->dag_area_lc * 20 + $data->dag_area_g;
                            $mutationlessa = $ord->mut_land_area_b * 6400 + $ord->mut_land_area_k * 320 + $ord->mut_land_area_lc * 20 + $ord->mut_land_area_g;
                            $remaining_lessa = $sourcelessa - $mutationlessa;
                            $left_b = floor($remaining_lessa / 6400);
                            $left_k = floor(($remaining_lessa - $left_b * 6400) / 320);
                            $left_lc = floor(($remaining_lessa - $left_b * 6400 - $left_k * 320)/20);
                            $left_g = $remaining_lessa - $left_b * 6400 - $left_k * 320 - $left_lc * 20;
                            $left_kr = 0;
                        }
                        else{
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
                        $table = 'chitha_basic';

                        $params = [
                            'jama_yn'        => null,
                            'dag_revenue'    => $dag_revenue_updates,
                            'dag_local_tax'  => $dag_local_tax_update,
                            'dag_area_b'     => $left_b,
                            'dag_area_k'     => $left_k,
                            'dag_area_lc'    => $left_lc,
                            'dag_area_g'     => $left_g,
                            'dag_area_kr'    => $left_kr,
                            'date_entry'     => $d,         // Assuming $d is a formatted date string
                            'operation'      => 'M',
                        ];

                        $where = [
                            'dist_code'          => $occ->dist_code,
                            'subdiv_code'        => $occ->subdiv_code,
                            'cir_code'           => $occ->cir_code,
                            'mouza_pargona_code' => $occ->mouza_pargona_code,
                            'lot_no'             => $occ->lot_no,
                            'vill_townprt_code'  => $occ->vill_townprt_code,
                            'dag_no'             => $occ->dag_no,
                            'patta_no'           => trim($occ->patta_no),  // PHP trim to mimic SQL TRIM()
                            'patta_type_code'    => $occ->patta_type_code,
                        ];

                        // Call your update method:
                        $result1 = $this->ChithaModel->update_table($table, $params, $where);

                        if (!$result1 < 1)
                        {
                            log_message("error"," #ERRCOMUTORDER016 Could not update  jama_yn=null in chitha_basic with district: ".$dist_code.", petition_no: ". $petition_no);
                            return [
                                'status' => 'n',
                                'msg' => '#ERRCOMUTORDER016 Could not create order!'
                            ];
                        } 
                    }
                    
                    $p_id = $dag_pattadar['pdar_id'];
                    
                    if ($ord->order_type_code == '02') {
                        // This Block Is For Field Partition
                        $q = "select count(*) as count from   chitha_pattadar  where dist_code='$occ->dist_code' and subdiv_code='$occ->subdiv_code' and cir_code='$occ->cir_code'"
                                . " and mouza_pargona_code='$occ->mouza_pargona_code' and"
                                . " lot_no='$occ->lot_no' and vill_townprt_code='$occ->vill_townprt_code' "
                                . " and TRIM(patta_no)=trim('$occ->new_patta_no') and"
                                . " patta_type_code='$occ->patta_type_code' and pdar_id='$p_id'";
                        $cPattadarExists = $this->db->query($q)->row()->count;
                    } else {
                        // This Block Is For Field Mutation
                        $q = "select count(*) as count from   chitha_pattadar  where dist_code='$occ->dist_code' and subdiv_code='$occ->subdiv_code' and cir_code='$occ->cir_code' and "
                                . "mouza_pargona_code='$occ->mouza_pargona_code' and lot_no='$occ->lot_no' and vill_townprt_code='$occ->vill_townprt_code' and "
                                . "TRIM(patta_no)=trim('$occ->patta_no') and patta_type_code='$occ->patta_type_code' and pdar_id='$p_id'";
                        $cPattadarExists = $this->db->query($q)->row()->count;
                    }
                    $occ->new_pattadar; // for partition it will always be new pattadar
                    if(($occ->new_pattadar!='N') && $occ->auth_type != null){
                        $p_id=$occ->pdar_id;

                        $table = 'chitha_pattadar';

                        $params = [
                            'pdar_aadharno' => $flagAadhaar,
                            'pdar_pan_no'   => $flagPan,
                            'pdar_photo'    => $pdarPhoto,
                        ];

                        $where = [
                            'dist_code'          => $occ->dist_code,
                            'subdiv_code'        => $occ->subdiv_code,
                            'cir_code'           => $occ->cir_code,
                            'mouza_pargona_code' => $occ->mouza_pargona_code,
                            'lot_no'             => $occ->lot_no,
                            'vill_townprt_code'  => $occ->vill_townprt_code,
                            'patta_no'           => trim($occ->patta_no), // Equivalent to TRIM() in SQL
                            'patta_type_code'    => $occ->patta_type_code,
                            'pdar_id'            => $p_id,
                        ];

                        $result = $this->ChithaModel->update_table($table, $params, $where);

                        if ($this->db->affected_rows() < 1)
                        {
                            log_message("error"," #ERRCOMUTORDER017 Could not update aadhaar details in chitha_pattadar with district: ".$dist_code .", petition_no: ". $petition_no);
                            return [
                                'status' => 'n',
                                'msg' => '#ERRCOMUTORDER017 Could not create order!'
                            ];
                        } 
                    }

                    if (($occ->new_pattadar=='N')){
                        //var_dump($dag_pattadar);
                        //var_dump($chitha_pattadar);
                        // $tstatus3 = $this->db->insert("chitha_dag_pattadar", $dag_pattadar);//************************************************* insert query
                        $tstatus3=$this->ChithaModel->insert_table('chitha_dag_pattadar',$dag_pattadar);
                        if ($tstatus3 != 1 )
                        {
                            log_message("error"," #ERRCOMUTORDER018 Could not insert in  chitha_dag_pattadar with district: ".$dist_code.", petition_no: ". $petition_no);
                            return [
                                'status' => 'n',
                                'msg' => '#ERRCOMUTORDER018 Could not create order!'
                            ];
                        }
                        if(($cPattadarExists == 0)){
                            $chitha_pattadar['f1_case_no']=$case_no;

                            // $tstatus4 = $this->db->insert("chitha_pattadar", $chitha_pattadar);//************************************************************************************************ insert query
                            $tstatus4 = $this->ChithaModel->insert_table('chitha_pattadar',$chitha_pattadar);
                            if ($tstatus4 != 1 )
                            {
                                log_message("error"," #ERRCOMUTORDER019 Could not insert in  chitha_pattadar with district: ".$dist_code.", petition_no: ". $petition_no);
                                return [
                                    'status' => 'n',
                                    'msg' => '#ERRCOMUTORDER019 Could not create order!'
                                ];
                            }
                        }
                    }
                   
                    $t_occup_query = "update t_chitha_col8_occup set iscorrected_inco='Y',iscorrected_inco_date='$corrected',order_passed='Y' where dist_code='$dist_code' and subdiv_code='$subdiv_code' and "
                            . "cir_code='$cir_code' and lot_no='$lot_no' and mouza_pargona_code='$mouza_pargona_code' and "
                            . "vill_townprt_code='$vill_code' and petition_no=$petition_no and dag_no='$dag_no' ";
                    $this->db->query($t_occup_query);//*********************************************************************************** update query
                    if ($this->db->affected_rows() < 1 )
                    {
                        log_message("error"," #ERRCOMUTORDER020 Could not update iscorrected_inco in t_chitha_col8_occup with district: ".$dist_code
                                    .", petition_no: ". $petition_no);
                        return [
                            'status' => 'n',
                            'msg' => '#ERRCOMUTORDER020 Could not create order!'
                        ];
                    } 
                }
                // occupants details ends here

                if ($ord->order_type_code == '02') {
                    foreach ($t_occup_data as $occup) {
                       
                        $table = 'chitha_dag_pattadar';

                        $params = [
                            'p_flag' => '1',
                        ];

                        $where = [
                            'dist_code'          => $dist_code,
                            'subdiv_code'        => $subdiv_code,
                            'cir_code'           => $cir_code,
                            'lot_no'             => $lot_no,
                            'mouza_pargona_code' => $mouza_pargona_code,
                            'vill_townprt_code'  => $vill_code,
                            'dag_no'             => $dag_no,
                            'pdar_id'            => $occup->pdar_id,
                        ];

                        $result = $this->ChithaModel->update_table($table, $params, $where);

                        
                        if ($result < 1 )
                        {
                            log_message("error"," #ERRCOMUTORDER021 Could not update p_flag in chitha_dag_pattadar with district: ".$dist_code
                                    .", petition_no: ". $petition_no);
                            return [
                                'status' => 'n',
                                'msg' => '#ERRCOMUTORDER021 Could not create order!'
                            ];
                        } 
                    }
                }

                if (($ord->order_type_code == '01') || ($ord->order_type_code == '02')) {

                     $t_inplace_query = "select * from   t_chitha_col8_inplace where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and lot_no='$lot_no' and "
                        . "mouza_pargona_code='$mouza_pargona_code' and vill_townprt_code='$vill_code' and dag_no='$dag_no' and iscorrected_inco is null";
                    $t_inplace_data = $this->db->query($t_inplace_query); 
                    
                    if (($ord->order_type_code == '01') && ($t_inplace_data == null || $t_inplace_data->num_rows() < 1))
                    {
                        log_message("error"," #ERRCOMUTORDER022 Could not find data in t_chitha_col8_inplace with district: "
                            .$dist_code.", petition_no: ". $petition_no);
                        return [
                            'status' => 'n',
                            'msg' => '#ERRCOMUTORDER022 Could not create order!'
                        ];
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
                        $data['user_code'] = $user_code;
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
                        if ($count < 1)
                        {
                            $tstatus5 = $this->db->insert("chitha_col8_inplace", $data);//********************************************** insert query
                            if ($tstatus5 != 1 )
                            {
                                log_message("error"," #ERRCOMUTORDER023 Could not insert in chitha_col8_inplace with district: ".$dist_code
                                    .", petition_no: ". $petition_no);
                                return [
                                    'status' => 'n',
                                    'msg' => '#ERRCOMUTORDER023 Could not create order!'
                                ];
                            }
                        }

                        $p_flag = '0';
                        if ($inplace->fmute_strike_out == '1')
                        $p_flag = '1';
                        $corrected = date('Y-m-d G:i:s');
                       //************************************************************************************ update query
                        $table = 'chitha_dag_pattadar';

                        $params = [
                            'p_flag'     => $p_flag,
                            'date_entry' => $corrected,
                        ];

                        $where = [
                            'dist_code'          => $dist_code,
                            'subdiv_code'        => $subdiv_code,
                            'cir_code'           => $cir_code,
                            'lot_no'             => $lot_no,
                            'mouza_pargona_code' => $mouza_pargona_code,
                            'vill_townprt_code'  => $vill_code,
                            'dag_no'             => $dag_no,
                            'pdar_id'            => $inplace->pdar_id,
                        ];

                        $result = $this->ChithaModel->update_table($table, $params, $where);

                        
                        if ($result < 1 )
                        {
                            log_message("error"," #ERRCOMUTORDER024 Could not update p_flag in chitha_dag_pattadar with district: ".$dist_code
                                    .", petition_no: ". $petition_no);
                            return [
                                'status' => 'n',
                                'msg' => '#ERRCOMUTORDER024 Could not create order!'
                            ];
                        } 

                        $t_inplace_query = "update t_chitha_col8_inplace set iscorrected_inco='Y',iscorrected_inco_date='$corrected',order_passed='Y' where dist_code='$dist_code' and "
                                . "subdiv_code='$subdiv_code' and cir_code='$cir_code' and lot_no='$lot_no' and mouza_pargona_code='$mouza_pargona_code' and vill_townprt_code='$vill_code' "
                                . "and dag_no='$dag_no'";
                        $this->db->query($t_inplace_query);//*********************************************************************************** update query
                        if ($this->db->affected_rows() < 1)
                        {
                            log_message("error"," #ERRCOMUTORDER025 Could not update iscorrected_inco in t_chitha_col8_inplace with district: ".$dist_code
                                    .", petition_no: ". $petition_no);
                            return [
                                'status' => 'n',
                                'msg' => '#ERRCOMUTORDER025 Could not create order!'
                            ];
                        } 

                        $date_of_order=date('Y-m-d');
                        $order_update_query = "update field_mut_basic set order_passed='Y',date_of_order='$date_of_order' where dist_code='$dist_code' and subdiv_code='$subdiv_code' and "
                                . "cir_code='$cir_code' and lot_no='$lot_no' and mouza_pargona_code='$mouza_pargona_code' and "
                                . "vill_townprt_code='$vill_code' and petition_no=$petition_no";
                        $this->db->query($order_update_query);//***************************************************************** update query
                        if ($this->db->affected_rows() < 1 )
                        {
                            log_message("error"," #ERRCOMUTORDER026 Could not update order_passed in field_mut_basic with district: ".$dist_code
                                    .", petition_no: ". $petition_no);
                            return [
                                'status' => 'n',
                                'msg' => '#ERRCOMUTORDER026 Could not create order!'
                            ];
                            
                        } 
                    }
                }
            }        
            // if (!$this->db->trans_status()) {
            //     log_message("error"," #ERRCOMUTORDER027 Could not complet autoUpdate for chitha with district: ".$dist_code
            //                         .", petition_no: ". $petition_no);
            //     return [
            //         'status' => 'n',
            //         'msg' => '#ERRCOMUTORDER027 Could not create order!'
            //     ];
            // }
            return [
                'status' => 'y',
                'msg' => 'Successfully created order!'
            ]; 
        }
}

    