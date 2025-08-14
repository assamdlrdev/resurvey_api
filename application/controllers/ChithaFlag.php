<?php
class ChithaFlag extends CI_Controller
{
	public function __construct()
    {
        parent::__construct();
        $this->load->model('mutation/mutationmodel');
        $this->load->model('patta/pattamodel');
        $this->load->model('chitha/ChithaModel');
        $this->load->model('chitha/ChithaFlagModel');
        $this->load->helper(array('form', 'url', 'Language'));
        $this->load->model('TransactionModel');
        $this->load->model('UtilsModel');
        $this->utilityclass->switchDb($this->session->userdata('dcode'));
        $this->lang->load('assamese_lang', 'assamese');
    }

    public function locationDetails()
    {
        if ($this->session->userdata('user_desig_code') != "LM") {
            echo json_encode("Not Authorised..!, Please Login With LM's Credentials!");
            exit;
        }
        
        $this->load->model('Dagflagmodel');
        $dist_code = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no = $this->session->userdata('lot_no');
        $dist_name = $this->utilityclass->getDistrictName($dist_code);
        $sub_div_name = $this->utilityclass->getSubDivName($dist_code, $subdiv_code);
        $cir_name = $this->utilityclass->getCircleName($dist_code, $subdiv_code, $cir_code);
        $lot_name = $this->utilityclass->getLotName($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no);


        $data['datas'] = array(
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'dist_name' => $dist_name,
            'sub_div_name' => $sub_div_name,
            'cir_name' => $cir_name,
            'lot_name' => $lot_name,
            'lot_no' => $lot_no,
            'mouza_pargona_code' => $mouza_pargona_code
        );

        //Area Mapping =====
        $villages = $this->mutationmodel->getVillageCodeJSON($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no);

        foreach ($villages as $key => $value) {
            $rejected_data = $this->ChithaFlagModel->getRejectedCountOfVillage($dist_code,$subdiv_code,$cir_code,$mouza_pargona_code, $lot_no,$value->vill_townprt_code,'R');
            $villages[$key]->rejectedCount = count($rejected_data);

            $forward_to_data = $this->ChithaFlagModel->getForwardToCOCountOfVillage($dist_code,$subdiv_code,$cir_code,$mouza_pargona_code, $lot_no,$value->vill_townprt_code);
            $villages[$key]->forward_to_co_count = count($forward_to_data);
        }


        $data['villages'] = $villages;
        $data['_view'] = 'chitha_flag/village_list_for_flag';
        $this->load->view('layout/layout_dhar', $data);
    }

    //PARTIAL MAPPING===================
    public function partialmapping()
    {

        if ($this->session->userdata('user_desig_code') != "LM") {
            echo json_encode("Not Authorised..!, Please Login With LM's Credentials!");
            exit;
        }
        // $this->load->model('chitha/ChithaFlagModel');
        $this->load->model('Dagflagmodel');
        $dist_code = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no = $this->session->userdata('lot_no');
        $dist_name = $this->utilityclass->getDistrictName($dist_code);
        $sub_div_name = $this->utilityclass->getSubDivName($dist_code, $subdiv_code);
        $cir_name = $this->utilityclass->getCircleName($dist_code, $subdiv_code, $cir_code);
        $vill_code = $this->input->get('no');
        $data['vill_name'] = $vill_name = $this->utilityclass->getVillageName($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code);
        $data['datas'] = array(
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'dist_name' => $dist_name,
            'sub_div_name' => $sub_div_name,
            'cir_name' => $cir_name,
            'lot_no' => $lot_no,
            'mouza_pargona_code' => $mouza_pargona_code,
            'vill_name' => $vill_name,
            'vill_code' => $vill_code
        );
        // echo $dist_code."-".$subdiv_code."-".$cir_code."-".$mouza_pargona_code."-".$lot_no."-".$vill_code;
        // die;
        // $daginfo = $this->ChithaFlagModel->getDagforchithaAll($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code);

        // $data['daginfo'] = $daginfo;
        $query = "Select * from dag_flag_master where flagid in (3,7,4,5,6) and is_active = 'A'";
        $zone_details = $this->db->query($query)->result();
        $data['zone_details'] = $zone_details;



        $sql1 = "Select * from settlement_premium_category order by pcid asc";
        $area = $this->db->query($sql1)->result();
        $data['area'] = $area;

        $query1 = "Select * from chitha_dag_all_flag_details join settlement_premium_area on chitha_dag_all_flag_details.area_flag = settlement_premium_area.paid where status is null and dist_code = ? and subdiv_code = ? and cir_code = ? and mouza_pargona_code = ? and lot_no = ? and vill_townprt_code = ? ";
        $insertedMappingDetails = $this->db->query($query1,array($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code))->result();
        $data['insertedMappingDetails'] = $insertedMappingDetails;


        $data['_view'] = 'chitha_flag/select_location_for_lm_partial';
        $this->load->view('layout/layout_dhar', $data);
    }


    public function getAllDagsForMapping()
    {

        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $order = $this->input->post('order');
        $vill_code = $this->input->post('village_code');
        $searchByCol_0 = trim($this->input->post('columns')[0]['search']['value']);
        $searchByCol_1 = trim($this->input->post('columns')[1]['search']['value']);
        $dist_code = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $user_code = $this->session->userdata('user_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no = $this->session->userdata('lot_no');
        //////////////////MARKING FOR CIRCLE WISE LOT MAPPING===========


        $results = $this->ChithaFlagModel->getDagforchithaAllNewMapping($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code,$start,$length,$order,$searchByCol_0,$searchByCol_1);

        $total_records = count($this->ChithaFlagModel->getDagforchithaAllNewMappingTotRecords($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code,$start,$length,$order,$searchByCol_0,$searchByCol_1));

    

        if (!empty($results)) {
            $data_rows = $results;
            foreach ($data_rows as $key => $rows) {

                $json[] = array(
                    $rows->dag_no,
                    '<span class="px-3"><strong>' . $rows->dag_no . '</strong></span>',
                    $rows->land_type
                );
            }

            
            $response = array(
                'draw' => $draw,
                'recordsTotal' => $total_records,
                'recordsFiltered' => $total_records,
                'data' => $json,
            );
            echo json_encode($response);

        } else {
            $response = array(
                'draw' => $draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            );
            echo json_encode($response);
        }
    }


    public function saveMappingWithChitha(){
    	$dag_list = $this->input->post('dag_list');
    	$dag_array = explode(',',$dag_list);


        if(empty($dag_array) || $dag_array == null){
            echo json_encode(array('responseType' => 3 ,'msg' => "#ERROR-056===Kindly Select One Dag..."));
            return;
        }
    	$area = $this->input->post('MappingCat');
        $MappingSubCat = $this->input->post('MappingSubCat');
        if($area == null || $area == '' || $MappingSubCat == null || $MappingSubCat == ''){
            echo json_encode(array('responseType' => 3 ,'msg' => "#ERROR-056===Kindly Select Mapping Category..."));
            return;
        }
    
    	$chaad_dag = $this->input->post('CD');
    	$erroded = $this->input->post('ED');
    	$missing_land = $this->input->post('ML');
        $sar_area = $this->input->post('CA');
        $wet_land = $this->input->post('WD');
    	$totalInsertableCount = count($dag_array);
    	$count = 0;
    	$dist_code = $this->session->userdata('dist_code');
    	$subdiv_code = $this->session->userdata('subdiv_code');
    	$cir_code = $this->session->userdata('cir_code');
    	$mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
    	$lot_no = $this->session->userdata('lot_no');
    	$vill_code = $this->input->post('vill_code');

        if($vill_code == null){
            echo json_encode(array('responseType' => 3 ,'msg' => "#ERROR-05632===Something went wrong..."));
            return;
        }
        // $this->db->trans_begin();
    	foreach ($dag_array as $key => $value) {
    		$uuid = $this->utilityclass->getVillageUUID($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code);
    		$dataInsert = array(
		    	'dist_code'  => $dist_code,
		    	'subdiv_code'  => $subdiv_code, 
		    	'cir_code' => $cir_code,
		    	'mouza_pargona_code' => $mouza_pargona_code,
		    	'lot_no' => $lot_no,
		    	'vill_townprt_code' => $vill_code,
		    	'dag_no' => $value,
		    	'area_flag' => $MappingSubCat,
		    	'is_eroded' => isset($erroded) ? $erroded : null,
		    	'is_landclassless' => isset($missing_land) ? $missing_land : null,
		    	'is_sad' => isset($chaad_dag) ? $chaad_dag : null,
                'is_sar' => isset($sar_area) ? $sar_area : null,
                'wet_land' => isset($wet_land) ? $wet_land : null,
		    	'user_code' => $this->session->userdata('user_code'),
		    	'creation_date_time' => date('Y-m-d H:i:s'),
		    	'updation_date_time' => date('Y-m-d H:i:s'),
		    	'status' => null,
		    	'uuid' => $uuid

	    	);
	    	$insertStatus = $this->TransactionModel->insert("chitha_dag_all_flag_details",$dataInsert);
            if($insertStatus != 1){
                log_message('error',"#ERROR-01===insertion failed on chitha_dag_all_flag_details==".$this->db->last_query());
                // $this->db->trans_rollback();
                echo json_encode(array('responseType' => 3 ,'msg' => "#ERROR-01===Mapping failed for the dag no.===".$value));
                return;
            }
    	}
        // $this->db->trans_commit();

    	echo json_encode(array('responseType' => 2 ,'msg' => "Mapping List Created successfully, Check below list for forward to CO"));


    	
    }

    public function updateMappingWithChitha(){
        $dag_list = $this->input->post('dag_list');
        $dag_array = explode(',',$dag_list);
        $area = $this->input->post('MappingCat');
        $MappingSubCat = $this->input->post('MappingSubCat');
        
        $chaad_dag = $this->input->post('CD');
        $erroded = $this->input->post('ED');
        $missing_land = $this->input->post('ML');
        $sar_area = $this->input->post('CA');
        $wet_land = $this->input->post('WD');
        $totalInsertableCount = count($dag_array);
        $count = 0;
        $dist_code = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no = $this->session->userdata('lot_no');
        $vill_code = $this->input->post('vill_code');



        if(empty($dag_array) || $dag_array == null){
            echo json_encode(array('responseType' => 3 ,'msg' => "#ERROR-87===Kindly Select One Dag..."));
            return;
        }

        if($area == null || $area == '' || $MappingSubCat == null || $MappingSubCat == ''){
            echo json_encode(array('responseType' => 3 ,'msg' => "#ERROR-88===Kindly Select Mapping Category..."));
            return;
        }
        if($vill_code == null || $vill_code == ''){
            echo json_encode(array('responseType' => 3 ,'msg' => "#ERROR-89===Something went wrong..."));
            return;
        }



        // $this->db->trans_begin();
        foreach ($dag_array as $key => $value) {
            $uuid = $this->utilityclass->getVillageUUID($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code);
            $dataInsert = array(
                'area_flag' => $MappingSubCat,
                'is_eroded' => isset($erroded) ? $erroded : null,
                'is_landclassless' => isset($missing_land) ? $missing_land : null,
                'is_sad' => isset($chaad_dag) ? $chaad_dag : null,
                'is_sar' => isset($sar_area) ? $sar_area : null,
                'wet_land' => isset($wet_land) ? $wet_land : null,
                'updation_date_time' => date('Y-m-d H:i:s'),
                'status' => 'V'
            );
            $where =array(
                'dist_code'  => $dist_code,
                'subdiv_code'  => $subdiv_code, 
                'cir_code' => $cir_code,
                'mouza_pargona_code' => $mouza_pargona_code,
                'lot_no' => $lot_no,
                'vill_townprt_code' => $vill_code,
                'dag_no' => $value,
                'status' => 'R'
            );
            $updateStatus = $this->TransactionModel->update_multiple_condition("chitha_dag_all_flag_details",$where,$dataInsert);
            if($updateStatus <= 0){
                log_message('error',"#ERROR-09===update failed on chitha_dag_all_flag_details==".$this->db->last_query());
                // $this->db->trans_rollback();
                echo json_encode(array('responseType' => 3 ,'msg' => "#ERROR-09===Update failed"));
                return;
            }
        }
        echo json_encode(array('responseType' => 2 ,'msg' => "Update successfully"));
        return;
        // if($this->db->trans_status() === FALSE ){
        //     $this->db->trans_rollback();
        //     return;
        // }else{
        //     $this->db->trans_commit();
        //     echo json_encode(array('responseType' => 2 ,'msg' => "Update successfully"));
        //     return;
        // }
    }

    public function updateApproveMappingWithChitha(){
        $dag_list = $this->input->post('dag_list');
        $dag_array = explode(',',$dag_list);
        $area = $this->input->post('MappingCat');
        $MappingSubCat = $this->input->post('MappingSubCat');
        $chaad_dag = $this->input->post('CD');
        $erroded = $this->input->post('ED');
        $missing_land = $this->input->post('ML');
        $sar_area = $this->input->post('CA');
        $wet_land = $this->input->post('WD');
        $totalInsertableCount = count($dag_array);
        $count = 0;
        $dist_code = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no = $this->session->userdata('lot_no');
        $vill_code = $this->input->post('vill_code');


        if(empty($dag_array) || $dag_array == null){
            echo json_encode(array('responseType' => 3 ,'msg' => "#ERROR-0561===Kindly Select One Dag..."));
            return;
        }
        if($area == null || $area == '' || $MappingSubCat == null || $MappingSubCat == ''){
            echo json_encode(array('responseType' => 3 ,'msg' => "#ERROR-05612===Kindly Select Mapping Category..."));
            return;
        }

        if($vill_code == null || $vill_code == ''){
            echo json_encode(array('responseType' => 3 ,'msg' => "#ERROR-058===Something went wrong...contact administrator"));
            return;
        }



        // $this->db->trans_begin();
        foreach ($dag_array as $key => $value) {
            $uuid = $this->utilityclass->getVillageUUID($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code);
            $dataInsert = array(
                'area_flag' => $MappingSubCat,
                'is_eroded' => isset($erroded) ? $erroded : null,
                'is_landclassless' => isset($missing_land) ? $missing_land : null,
                'is_sad' => isset($chaad_dag) ? $chaad_dag : null,
                'is_sar' => isset($sar_area) ? $sar_area : null,
                'wet_land' => isset($wet_land) ? $wet_land : null,
                'status' => 'U',
                'updation_date_time' => date('Y-m-d H:i:s')
            );
            $where =array(
                'dist_code'  => $dist_code,
                'subdiv_code'  => $subdiv_code, 
                'cir_code' => $cir_code,
                'mouza_pargona_code' => $mouza_pargona_code,
                'lot_no' => $lot_no,
                'vill_townprt_code' => $vill_code,
                'dag_no' => $value,
            );
            $updateStatus = $this->TransactionModel->update_multiple_condition("chitha_dag_all_flag_details",$where,$dataInsert);
            if($updateStatus <= 0){
                log_message('error',"#ERROR-09===update failed on chitha_dag_all_flag_details==".$this->db->last_query());
                // $this->db->trans_rollback();
                echo json_encode(array('responseType' => 3 ,'msg' => "#ERROR-09===Update failed"));
                return;
            }
        }
        echo json_encode(array('responseType' => 2 ,'msg' => "Update successfully"));
        return;
        // if($this->db->trans_status() === FALSE ){
        //     $this->db->trans_rollback();
        //     return;
        // }else{
        //     $this->db->trans_commit();
        //     echo json_encode(array('responseType' => 2 ,'msg' => "Update successfully"));
        //     return;
        // }
    }
    public function forwardToCORevert(){
        $dist_code = $this->input->post('dist_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $vill_townprt_code = $this->input->post('vill_townprt_code');



        if($dist_code == null || $subdiv_code == null || $cir_code == null || $mouza_pargona_code == null || $lot_no == null || $vill_townprt_code == null){
            echo json_encode(array('responseType' => 3,'msg' => '#ERROR-078 Forward to CO failed=='));
            return;
        }



        $this->db->trans_begin();
        $where = array(
            'dist_code' => $this->input->post('dist_code'),
            'subdiv_code' => $this->input->post('subdiv_code'),
            'cir_code' => $this->input->post('cir_code'),
            'mouza_pargona_code' => $this->input->post('mouza_pargona_code'),
            'lot_no' => $this->input->post('lot_no'),
            'vill_townprt_code' => $this->input->post('vill_townprt_code'),
            'status' => 'V'
        );
        $data = array(

            'status' => 'P',
            'updation_date_time' => date('Y-m-d H:i:s')
        );
        $affectedRows = $this->TransactionModel->update_multiple_condition('chitha_dag_all_flag_details', $where, $data);
        if($affectedRows <= 0){
            
            log_message("error",'#ERROR-04 Forward to CO failed=='.$this->db->last_query());
            $this->db->trans_rollback();
            echo json_encode(array('responseType' => 3,'msg' => '#ERROR-04 Forward to CO failed=='));
            return;
        }

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return;

        }else{
            $this->db->trans_commit();
            echo json_encode(array('responseType' => 2,'msg' => 'Forwarded to CO successfully'));
            return;
        }
    }
    public function forwardToCO(){
		$dist_code = $this->input->post('dist_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $vill_townprt_code = $this->input->post('vill_townprt_code');
        

        if($dist_code == null || $subdiv_code == null || $cir_code == null || $mouza_pargona_code == null || $lot_no == null || $vill_townprt_code == null){
            echo json_encode(array('responseType' => 3,'msg' => '#ERROR-048 Forward to CO failed=='));
            return;
        }
        $this->db->trans_begin();
        $where = array(
        	'dist_code' => $this->input->post('dist_code'),
	        'subdiv_code' => $this->input->post('subdiv_code'),
	        'cir_code' => $this->input->post('cir_code'),
	        'mouza_pargona_code' => $this->input->post('mouza_pargona_code'),
	        'lot_no' => $this->input->post('lot_no'),
	        'vill_townprt_code' => $this->input->post('vill_townprt_code'),
            'status' => null
        );
        $data = array(
        	'status' => 'P',
            'updation_date_time' => date('Y-m-d H:i:s')
        );
        $affectedRows = $this->TransactionModel->update_multiple_condition('chitha_dag_all_flag_details', $where, $data);
        if($affectedRows <= 0){
        	
            log_message("error",'#ERROR-041 Forward to CO failed=='.$this->db->last_query());
            $this->db->trans_rollback();
        	echo json_encode(array('responseType' => 3,'msg' => '#ERROR-41 Forward to CO failed=='));
            return;
        }

        if($this->db->trans_status() === FALSE){
        	$this->db->trans_rollback();
            return;

        }else{
        	$this->db->trans_commit();
        	echo json_encode(array('responseType' => 2,'msg' => 'Forwarded to CO successfully'));
            return;
        }
    }
    public function forwardToCOForUpdate(){
        $dist_code = $this->input->post('dist_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $vill_townprt_code = $this->input->post('vill_townprt_code');


        if($dist_code == null || $subdiv_code == null || $cir_code == null || $mouza_pargona_code == null || $lot_no == null || $vill_townprt_code == null){
            echo json_encode(array('responseType' => 3,'msg' => '#ERROR-045 Forward to CO failed=='));
            return;
        }


        $this->db->trans_begin();
        $where = array(
            'dist_code' => $this->input->post('dist_code'),
            'subdiv_code' => $this->input->post('subdiv_code'),
            'cir_code' => $this->input->post('cir_code'),
            'mouza_pargona_code' => $this->input->post('mouza_pargona_code'),
            'lot_no' => $this->input->post('lot_no'),
            'vill_townprt_code' => $this->input->post('vill_townprt_code'),
            'status' => 'U'
        );
        $data = array(

            'status' => 'P',
            'updation_date_time' => date('Y-m-d H:i:s')
        );
        $affectedRows = $this->TransactionModel->update_multiple_condition('chitha_dag_all_flag_details', $where, $data);
        if($affectedRows <= 0){
            
            log_message("error",'#ERROR-042 Forward to CO failed=='.$this->db->last_query());
            $this->db->trans_rollback();
            echo json_encode(array('responseType' => 3,'msg' => '#ERROR-042 Forward to CO failed=='));
            return;
        }

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return;

        }else{
            $this->db->trans_commit();
            echo json_encode(array('responseType' => 2,'msg' => 'Forwarded to CO successfully'));
            return;
        }
    }
    public function FlagIndex(){      
	   //***************chechink-user-designation**********/
	   if($this->session->userdata('user_desig_code') != "CO"){
	      echo json_encode("Not Authorised..!, Please Login With CO's Credentials!");
	      exit;
	   }
	    $dist_code = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
	   //**************************************************/         
	   $data['pending_count'] = count($this->ChithaFlagModel->getPendingCountOfVillage($dist_code,$subdiv_code,$cir_code,'P'));  
	   $data['approve_count'] = count($this->ChithaFlagModel->getApprovedCountOfVillage($dist_code,$subdiv_code,$cir_code,'F'));      
	   $data['_view'] = 'chitha_flag/index';
	   $this->load->view('layout/layout_dhar',$data);
	}


	//location select form 
   public function index(){      
   //***************chechink-user-designation**********/
   if($this->session->userdata('user_desig_code') != "CO"){
      echo json_encode("Not Authorised..!, Please Login With CO's Credentials!");
      exit;
   }
   //**************************************************/         
   $data['pending_count'] = $this->LandBankCOModel->getPendingLbCount();      
   $data['approve_count'] = $this->LandBankCOModel->getApproveLbCount();      
   $data['_view'] = 'land_bank_co/index';
   $this->load->view('layout/layout_dhar',$data);
   }

   //displaying pending list for CO
   public function ChithaFlagPendingList(){
	   //***************chechink-user-designation**********/
	   if($this->session->userdata('user_desig_code') != "CO"){
	      echo json_encode("Not Authorised..!, Please Login With CO's Credentials!");
	      exit;
	   }
	   //**************************************************/

	   $data['dist_code'] = $dist_code = $this->session->userdata('dist_code');
	   $data['subdiv_code'] = $subdiv_code = $this->session->userdata('subdiv_code');
	   $data['circle_code'] = $cir_code = $this->session->userdata('cir_code');    
	   $data['pending_list'] = $getLbPendingList = $this->ChithaFlagModel->getPendingCountOfVillage($dist_code,$subdiv_code,$cir_code,'P');
	   $data['_view'] = 'chitha_flag/pending_list';
	   $this->load->view('layout/layout_dhar',$data);
   }

   	public function viewPendingDagFlagVillageWise(){
      $subdiv_code = $this->input->post('subdiv_code');
      $cir_code = $this->input->post('cir_code');
      $dist_code   = $this->input->post('dist_code');
      $mouza_pargona_code   = $this->input->post('mouza_pargona_code');
      $lot_no   = $this->input->post('lot_no');
      $vill_code   = $this->input->post('vill_code');
      $data = array();
      $data['subdiv_code'] =$subdiv_code;
      $data['cir_code'] =$cir_code;
      $data['dist_code'] =$dist_code;
      $data['mouza_pargona_code'] =$mouza_pargona_code;
      $data['lot_no'] =$lot_no;
      $data['vill_code'] =$vill_code;     
      $data['vill_name'] = $this->utilityclass->getVillageName($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code,$lot_no,$vill_code);
      $data['pendingDagFlag'] = $this->ChithaFlagModel->getPendingDagFlagList($dist_code,$subdiv_code,$cir_code,$mouza_pargona_code,$lot_no,$vill_code,'P');
      $data['status'] = true;
      $data['approve_reject'] = $this->input->post('flag');
      $this->load->view('chitha_flag/pending_village_dag_flag_list', $data);
	}

    public function saveandApprove()
    {
        $dist_code = $this->input->post('dist_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $vill_townprt_code = $this->input->post('vill_townprt_code');
        // if($this->input->post('co_remarks') == null){
        //     echo json_encode(array('responseType' => 3,'msg' => '#ERROR-011 Remarks field is mandatory'));
        //     return;
        // }

        if($dist_code == null || $subdiv_code == null || $cir_code == null || $mouza_pargona_code == null || $lot_no == null || $vill_townprt_code == null){
            echo json_encode(array('responseType' => 3,'msg' => '#ERROR-023 Forward to CO failed=='));
            return;
        }
        $this->db->trans_begin();

        $where = array(
            'dist_code' => $this->input->post('dist_code'),
            'subdiv_code' => $this->input->post('subdiv_code'),
            'cir_code' => $this->input->post('cir_code'),
            'mouza_pargona_code' => $this->input->post('mouza_pargona_code'),
            'lot_no' => $this->input->post('lot_no'),
            'vill_townprt_code' => $this->input->post('vill_townprt_code'),
            'status'=>'P'
        );
        $approve_revert = $this->input->post('approve_reject');
        $task = "Reverted";
        $status = 'R';
        if($approve_revert == 'A'){
            $task = "Approved";
            $status = 'F';
        }
        $data = array(

            'status' => $status,
            // 'remarks' => $this->input->post('co_remarks'),

            'updation_date_time' => date('Y-m-d H:i:s')
        );
        $villname = $this->utilityclass->getVillageName($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code,$lot_no,$vill_townprt_code);
        if($status == 'F'){
            $resultt = $this->TransactionModel->get_all_records_condition("chitha_dag_all_flag_details",$where);
            if(empty($resultt)){
                echo json_encode(array('responseType' => 3,'msg' => '#ERROR-0235 Process failed=='));
                return;
            }

            foreach ($resultt as $key => $value) {
                $where_check = array(
                    'dist_code' => $this->input->post('dist_code'),
                    'subdiv_code' => $this->input->post('subdiv_code'),
                    'cir_code' => $this->input->post('cir_code'),
                    'mouza_pargona_code' => $this->input->post('mouza_pargona_code'),
                    'lot_no' => $this->input->post('lot_no'),
                    'vill_townprt_code' => $this->input->post('vill_townprt_code'),
                    'status'=>'F',
                    'dag_no' => $value->dag_no
                );
                $checkValResult = $this->TransactionModel->get_all_records_condition("chitha_dag_all_flag_details_final",$where_check);
              
                if(!empty($checkValResult) && $checkValResult[0]->dag_no != null){
                    $where_final = array(
                            'dist_code' => $this->input->post('dist_code'),
                            'subdiv_code' => $this->input->post('subdiv_code'),
                            'cir_code' => $this->input->post('cir_code'),
                            'mouza_pargona_code' => $this->input->post('mouza_pargona_code'),
                            'lot_no' => $this->input->post('lot_no'),
                            'vill_townprt_code' => $this->input->post('vill_townprt_code'),
                            'dag_no'=>$value->dag_no
                        );
                         $data_final=array(
                            'area_flag' => $value->area_flag,
                            'is_eroded' => $value->is_eroded,
                            'is_landclassless' => $value->is_landclassless,
                            'is_sad' => $value->is_sad,
                            'is_sar' => $value->is_sar,
                            'wet_land' => $value->wet_land,
                            'status' => 'F',
                            'updation_date_time' =>date('Y-m-d H:i:s'),
                            'remarks' => $value->remarks
                         );
                  
                    $affectedRows = $this->TransactionModel->update_multiple_condition('chitha_dag_all_flag_details_final', $where_final, $data_final);
                    if($affectedRows <= 0){
                        
                        log_message("error", '#ERROR-067 '.$task.' failed=='.$villname);
                        $this->db->trans_rollback();
                        echo json_encode(array('responseType' => 3,'msg' => '#ERROR-067 '.$task.' failed=='.$villname));
                        return;
                    }
                }else{
                    $value->status = "F";
                    log_message("error","FInal Insert Array==========".json_encode($value));
                    $insertStatus = $this->TransactionModel->insert("chitha_dag_all_flag_details_final",$value);
                    if($insertStatus != 1){
                        $this->db->trans_rollback();
                        echo json_encode(array('responseType' => 3,'msg' => '#ERROR-068 '.$task.' failed=='.$villname));
                        return;
                    }
                }
            }
            //end for loop=============
        }
        



        $affectedRows = $this->TransactionModel->update_multiple_condition('chitha_dag_all_flag_details', $where, $data);


        
        if($affectedRows <= 0){
            $this->db->trans_rollback();
            log_message("error",'#ERROR-06 '.$task.' failed=='.$villname);
            echo json_encode(array('responseType' => 3,'msg' => '#ERROR-06 '.$task.' failed=='.$villname));
            return;
        }

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return;

        }else{
            $this->db->trans_commit();
            echo json_encode(array('responseType' => 2,'msg' => $task.' successfully for the village=='.$villname));
            return;
        }
    }

    //displaying pending list for CO
    public function ChithaFlagApprovedList(){
       //***************chechink-user-designation**********/
       if($this->session->userdata('user_desig_code') != "CO"){
          echo json_encode("Not Authorised..!, Please Login With CO's Credentials!");
          exit;
       }
       //**************************************************/

       $data['dist_code'] = $dist_code = $this->session->userdata('dist_code');
       $data['subdiv_code'] = $subdiv_code = $this->session->userdata('subdiv_code');
       $data['circle_code'] = $cir_code = $this->session->userdata('cir_code');    
       $data['pending_list'] = $getLbPendingList = $this->ChithaFlagModel->getApprovedCountOfVillage($dist_code,$subdiv_code,$cir_code,'F');
       $data['_view'] = 'chitha_flag/approved_list';
       $this->load->view('layout/layout_dhar',$data);
   }



   public function viewApprovedDagFlagVillageWise(){
      $subdiv_code = $this->input->post('subdiv_code');
      $cir_code = $this->input->post('cir_code');
      $dist_code   = $this->input->post('dist_code');
      $mouza_pargona_code   = $this->input->post('mouza_pargona_code');
      $lot_no   = $this->input->post('lot_no');
      $vill_code   = $this->input->post('vill_code');
      $data = array();
      $data['subdiv_code'] =$subdiv_code;
      $data['cir_code'] =$cir_code;
      $data['dist_code'] =$dist_code;
      $data['mouza_pargona_code'] =$mouza_pargona_code;
      $data['lot_no'] =$lot_no;
      $data['vill_code'] =$vill_code;     
      $data['vill_name'] = $this->utilityclass->getVillageName($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code,$lot_no,$vill_code);
      $data['pendingDagFlag'] = $this->ChithaFlagModel->getApprovedFinalDagFlagList($dist_code,$subdiv_code,$cir_code,$mouza_pargona_code,$lot_no,$vill_code,'F');

      // $data['pendingDagFlag'] = $this->ChithaFlagModel->getPendingDagFlagList($dist_code,$subdiv_code,$cir_code,$mouza_pargona_code,$lot_no,$vill_code,'F');
      $data['status'] = false;
      $this->load->view('chitha_flag/approved_village_dag_flag_list', $data);
    }

    //PARTIAL MAPPING===================
    public function viewMappingDetails()
    {

        if ($this->session->userdata('user_desig_code') != "LM") {
            echo json_encode("Not Authorised..!, Please Login With LM's Credentials!");
            exit;
        }
        $this->load->model('chitha/ChithaFlagModel');
        $this->load->model('Dagflagmodel');
        $dist_code = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no = $this->session->userdata('lot_no');
        $dist_name = $this->utilityclass->getDistrictName($dist_code);
        $sub_div_name = $this->utilityclass->getSubDivName($dist_code, $subdiv_code);
        $cir_name = $this->utilityclass->getCircleName($dist_code, $subdiv_code, $cir_code);
        $vill_code = $this->input->get('no');
        $data['vill_name'] = $vill_name = $this->utilityclass->getVillageName($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code);
        $data['datas'] = array(
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'dist_name' => $dist_name,
            'sub_div_name' => $sub_div_name,
            'cir_name' => $cir_name,
            'lot_no' => $lot_no,
            'mouza_pargona_code' => $mouza_pargona_code,
            'vill_name' => $vill_name,
            'vill_code' => $vill_code
        );

        $query = "Select * from dag_flag_master where flagid in (3,7,4,5,6) and is_active = 'A'";
        $zone_details = $this->db->query($query)->result();
        $data['zone_details'] = $zone_details;


        $sql1 = "Select * from settlement_premium_category order by pcid asc";
        $area = $this->db->query($sql1)->result();
        $data['area'] = $area;


        $query1 = "Select * from chitha_dag_all_flag_details join settlement_premium_area on chitha_dag_all_flag_details.area_flag = settlement_premium_area.paid where status = 'U' and dist_code = ? and subdiv_code = ? and cir_code = ? and mouza_pargona_code = ? and lot_no = ? and vill_townprt_code = ? ";
        $insertedMappingDetails = $this->db->query($query1,array($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code))->result();
        $data['insertedMappingDetails'] = $insertedMappingDetails;

        // $data['_view'] = 'chitha_flag/select_location_for_lm_partial_view';
        $data['_view'] = 'chitha_flag/select_location_for_lm_partial_update_approve';
        $this->load->view('layout/layout_dhar', $data);
    }




    public function partialmappingRejected()
    {

        if ($this->session->userdata('user_desig_code') != "LM") {
            echo json_encode("Not Authorised..!, Please Login With LM's Credentials!");
            exit;
        }
        $this->load->model('chitha/ChithaFlagModel');
        $this->load->model('Dagflagmodel');
        $dist_code = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no = $this->session->userdata('lot_no');
        $dist_name = $this->utilityclass->getDistrictName($dist_code);
        $sub_div_name = $this->utilityclass->getSubDivName($dist_code, $subdiv_code);
        $cir_name = $this->utilityclass->getCircleName($dist_code, $subdiv_code, $cir_code);
        $vill_code = $this->input->get('no');
        $data['vill_name'] = $vill_name = $this->utilityclass->getVillageName($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code);
        $data['datas'] = array(
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'dist_name' => $dist_name,
            'sub_div_name' => $sub_div_name,
            'cir_name' => $cir_name,
            'lot_no' => $lot_no,
            'mouza_pargona_code' => $mouza_pargona_code,
            'vill_name' => $vill_name,
            'vill_code' => $vill_code
        );

        $query = "Select * from dag_flag_master where flagid in (3,7,4,5,6) and is_active = 'A'";
        $zone_details = $this->db->query($query)->result();
        $data['zone_details'] = $zone_details;


        $sql1 = "Select * from settlement_premium_category order by pcid asc";
        $area = $this->db->query($sql1)->result();
        $data['area'] = $area;
        $data['remarks_co'] = null;
        $query2 = "Select remarks from chitha_dag_all_flag_details where status in ('R','V') and dist_code = ? and subdiv_code = ? and cir_code = ? and mouza_pargona_code = ? and lot_no = ? and vill_townprt_code = ? ";
        $remarkList = $this->db->query($query2,array($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code))->row();
        if(isset($remarkList) && $remarkList != null){
            $data['remarks_co'] = $remarkList->remarks;
        }
        $query1 = "Select * from chitha_dag_all_flag_details join settlement_premium_area on chitha_dag_all_flag_details.area_flag = settlement_premium_area.paid where status = 'V' and dist_code = ? and subdiv_code = ? and cir_code = ? and mouza_pargona_code = ? and lot_no = ? and vill_townprt_code = ? ";
        $insertedMappingDetails = $this->db->query($query1,array($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code))->result();
        $data['insertedMappingDetails'] = $insertedMappingDetails;

        $data['_view'] = 'chitha_flag/select_location_for_lm_partial_reverted';
        $this->load->view('layout/layout_dhar', $data);
    }


    public function getAllDagsForMappingView()
    {

        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $order = $this->input->post('order');
        $vill_code = $this->input->post('village_code');
        $searchByCol_0 = trim($this->input->post('columns')[0]['search']['value']);
        $searchByCol_1 = trim($this->input->post('columns')[1]['search']['value']);
        $dist_code = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $user_code = $this->session->userdata('user_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no = $this->session->userdata('lot_no');
        //////////////////MARKING FOR CIRCLE WISE LOT MAPPING===========

        $status ='F';
        $results = $this->ChithaFlagModel->getDagforchithaAllNewMappingView($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code,$start,$length,$order,$searchByCol_0,$searchByCol_1,$status);

        $total_records = count($this->ChithaFlagModel->getDagforchithaAllNewMappingTotRecordsView($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code,$status));

    

        if (!empty($results)) {
            $data_rows = $results;
            foreach ($data_rows as $key => $rows) {
                
                $json[] = array(
                    '<span class="px-3"><strong>' . $rows->dag_no . '</strong></span>',
                    $rows->area,
                    $rows->is_eroded == 7 ? "Yes" : "N/A",
                    $rows->is_landclassless == 4 ? "Yes" : "N/A",
                    $rows->is_sad == 3 ? "Yes" : "N/A",
                    $rows->land_type,
                    $rows->status == 'F' ? "<b>Approved</b>" : "--"
                );
            }

            
            $response = array(
                'draw' => $draw,
                'recordsTotal' => $total_records,
                'recordsFiltered' => $total_records,
                'data' => $json,
            );
            echo json_encode($response);

        } else {
            $response = array(
                'draw' => $draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
            );
            echo json_encode($response);
        }
    }


    public function getAllDagsForMappingRevert()
    {

        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $order = $this->input->post('order');
        $vill_code = $this->input->post('village_code');
        $searchByCol_0 = trim($this->input->post('columns')[0]['search']['value']);
        $searchByCol_1 = trim($this->input->post('columns')[1]['search']['value']);
        $dist_code = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $user_code = $this->session->userdata('user_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no = $this->session->userdata('lot_no');
        //////////////////MARKING FOR CIRCLE WISE LOT MAPPING===========
        $status ='R';

        $results = $this->ChithaFlagModel->getDagforchithaAllNewMappingRevert($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code,$start,$length,$order,$searchByCol_0,$searchByCol_1,$status);

        $total_records = count($this->ChithaFlagModel->getDagforchithaAllNewMappingTotRecordsRevert($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code,$status));

    

        if (!empty($results)) {
            $data_rows = $results;
            foreach ($data_rows as $key => $rows) {
                
                $json[] = array(
                    $rows->dag_no,
                    '<span class="px-3"><strong>' . $rows->dag_no . '</strong></span>',
                    $rows->area,
                    $rows->is_eroded == 7 ? "Yes" : "N/A",
                    $rows->is_landclassless == 4 ? "Yes" : "N/A",
                    $rows->is_sad == 3 ? "Yes" : "N/A",
                    $rows->is_sar == 5 ? "Yes" : "N/A",
                    $rows->wet_land == 6 ? "Yes" : "N/A",
                    $rows->land_type
                );
            }

            
            $response = array(
                'draw' => $draw,
                'recordsTotal' => $total_records,
                'recordsFiltered' => $total_records,
                'data' => $json,
            );
            echo json_encode($response);

        } else {
            $response = array(
                'draw' => $draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
            );
            echo json_encode($response);
        }
    }

    public function removeMappingFlag(){
        $dist_code = $this->input->post('dist_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $vill_townprt_code = $this->input->post('vill_townprt_code');
        $dag_no = $this->input->post('dag_no');

        if($dist_code == null || $subdiv_code == null || $cir_code == null || $mouza_pargona_code == null || $lot_no == null || $vill_townprt_code == null || $dag_no == null){
            echo json_encode(array('responseType' => 3,'msg' => '#ERROR-02563 Something went wrong=='));
            return;
        }


        $this->db->trans_begin();
        $where = array(
            'dist_code' => $this->input->post('dist_code'),
            'subdiv_code' => $this->input->post('subdiv_code'),
            'cir_code' => $this->input->post('cir_code'),
            'mouza_pargona_code' => $this->input->post('mouza_pargona_code'),
            'lot_no' => $this->input->post('lot_no'),
            'vill_townprt_code' => $this->input->post('vill_townprt_code'),
            'dag_no' => $dag_no
        );

        $checkStatus = $this->TransactionModel->get_all_records_condition("chitha_dag_all_flag_details_final",$where);

        if(!empty($checkStatus) && $checkStatus[0]->dag_no != null){
            $this->db->trans_rollback();
            echo json_encode(array('responseType' => 3,'msg' => '#ERROR-078 Dag no already approved once so Remove flag failed for the Dag no--'.$dag_no));
            return;
        }
        
        $affectedRows = $this->TransactionModel->delete_by_multiple_condition('chitha_dag_all_flag_details', $where);
        if($affectedRows <= 0){
            $this->db->trans_rollback();
            echo json_encode(array('responseType' => 3,'msg' => '#ERROR-07 Remove flag failed=='.$dag_no));
            return;
        }

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return;

        }else{
            $this->db->trans_commit();
            echo json_encode(array('responseType' => 2,'msg' => 'Remove flag successfully for the dag no=='.$dag_no));
            return;
        }
    }


    public function getAllDagsForMappingViewCOend()
    {

        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $order = $this->input->post('order');
        $vill_code = $this->input->post('village_code');
        $searchByCol_0 = trim($this->input->post('columns')[0]['search']['value']);
        $searchByCol_1 = trim($this->input->post('columns')[1]['search']['value']);
        $dist_code = $this->input->post('dist_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        //////////////////MARKING FOR CIRCLE WISE LOT MAPPING===========

        $status ='P';
        $results = $this->ChithaFlagModel->getDagforchithaAllNewMappingPendingInCO($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code,$start,$length,$order,$searchByCol_0,$searchByCol_1,$status);

        $total_records = count($this->ChithaFlagModel->getDagforchithaAllNewMappingTotRecordsPendingInCO($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code,$start,$length,$order,$searchByCol_0,$searchByCol_1,$status));


        if (!empty($results)) {
            $data_rows = $results;
            foreach ($data_rows as $key => $rows) {
                
                $json[] = array(
                    $rows->dag_no,
                    '<span class="px-3"><strong>' . $rows->dag_no . '</strong></span>',
                    $rows->area,
                    $rows->is_eroded == 7 ? "Yes" : "N/A",
                    $rows->is_landclassless == 4 ? "Yes" : "N/A",
                    $rows->is_sad == 3 ? "Yes" : "N/A",
                    $rows->is_sar == 5 ? "Yes" : "N/A",
                    $rows->wet_land == 6 ? "Yes" : "N/A",
                    $rows->land_type
                );
            }

            
            $response = array(
                'draw' => $draw,
                'recordsTotal' => $total_records,
                'recordsFiltered' => $total_records,
                'data' => $json,
            );
            echo json_encode($response);

        } else {
            $response = array(
                'draw' => $draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
            );
            echo json_encode($response);
        }
    }

    public function getAllDagsForMappingViewCOendApproved()
    {

        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $order = $this->input->post('order');
        $vill_code = $this->input->post('village_code');
        $searchByCol_0 = trim($this->input->post('columns')[0]['search']['value']);
        $searchByCol_1 = trim($this->input->post('columns')[1]['search']['value']);
        $dist_code = $this->input->post('dist_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        //////////////////MARKING FOR CIRCLE WISE LOT MAPPING===========

        $status ='F';
        $results = $this->ChithaFlagModel->getDagforchithaAllNewMappingView($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code,$start,$length,$order,$searchByCol_0,$searchByCol_1,$status);

        $total_records = count($this->ChithaFlagModel->getDagforchithaAllNewMappingTotRecordsView($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code,$start,$length,$order,$searchByCol_0,$searchByCol_1,$status));


        if (!empty($results)) {
            $data_rows = $results;
            foreach ($data_rows as $key => $rows) {
           
                $json[] = array(
                    '<span class="px-3"><strong>' . $rows->dag_no . '</strong></span>',
                    $rows->area,
                    $rows->is_eroded == 7 ? "Yes" : "N/A",
                    $rows->is_landclassless == 4 ? "Yes" : "N/A",
                    $rows->is_sad == 3 ? "Yes" : "N/A",
                    $rows->is_sar == 5 ? "Yes" : "N/A",
                    $rows->wet_land == 6 ? "Yes" : "N/A",
                    $rows->land_type,
                    $rows->status == 'F' ? "<b>Approved</b>" : "--"
                );
            }

            
            $response = array(
                'draw' => $draw,
                'recordsTotal' => $total_records,
                'recordsFiltered' => $total_records,
                'data' => $json,
            );
            echo json_encode($response);

        } else {
            $response = array(
                'draw' => $draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
            );
            echo json_encode($response);
        }
    }


    public function getAllDagsForMappingUpdate()
    {

        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $order = $this->input->post('order');
        $vill_code = $this->input->post('village_code');
        $searchByCol_0 = trim($this->input->post('columns')[0]['search']['value']);
        $searchByCol_1 = trim($this->input->post('columns')[1]['search']['value']);
        $dist_code = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $user_code = $this->session->userdata('user_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no = $this->session->userdata('lot_no');
        //////////////////MARKING FOR CIRCLE WISE LOT MAPPING===========

        $status ='F';
        $results = $this->ChithaFlagModel->getDagforchithaAllNewMappingUpdate($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code,$start,$length,$order,$searchByCol_0,$searchByCol_1,$status);

        $total_records = count($this->ChithaFlagModel->getDagforchithaAllNewMappingTotRecordsUpdate($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code,$start,$length,$order,$searchByCol_0,$searchByCol_1,$status));

    

        if (!empty($results)) {
            $data_rows = $results;
            foreach ($data_rows as $key => $rows) {
                
                $json[] = array(
                    $rows->dag_no,
                    '<span class="px-3"><strong>' . $rows->dag_no . '</strong></span>',
                    $rows->area,
                    $rows->is_eroded == 7 ? "Yes" : "N/A",
                    $rows->is_landclassless == 4 ? "Yes" : "N/A",
                    $rows->is_sad == 3 ? "Yes" : "N/A",
                    $rows->is_sar == 5 ? "Yes" : "N/A",
                    $rows->wet_land == 6 ? "Yes" : "N/A",
                    $rows->land_type,
                    $rows->status == 'F' ? "<b>Approved</b>" : "--"
                );
            }

            
            $response = array(
                'draw' => $draw,
                'recordsTotal' => $total_records,
                'recordsFiltered' => $total_records,
                'data' => $json,
            );
            echo json_encode($response);

        } else {
            $response = array(
                'draw' => $draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
            );
            echo json_encode($response);
        }
    }

    public function FlagIndexLM(){      
        //***************chechink-user-designation**********/
        if($this->session->userdata('user_desig_code') != "LM"){
            echo json_encode("Not Authorised..!, Please Login With LM's Credentials!");
            exit;
        }
        $dist_code = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $data['mouza_pargona_code'] = $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');    
        $data['lot_no'] = $lot_no = $this->session->userdata('lot_no'); 
        //**************************************************/         
        $data['pending_count'] = count($this->ChithaFlagModel->getPendingCountOfVillageLM($dist_code,$subdiv_code,$cir_code,$mouza_pargona_code,$lot_no,'P'));  
        $data['approve_count'] = count($this->ChithaFlagModel->getApprovedCountOfVillageLM($dist_code,$subdiv_code,$cir_code,$mouza_pargona_code,$lot_no,'F'));      
        $data['_view'] = 'chitha_flag/indexLM';
        $this->load->view('layout/layout_dhar',$data);
    }

    //displaying pending list for LM
    public function ChithaFlagApprovedListLM(){
       //***************chechink-user-designation**********/
       if($this->session->userdata('user_desig_code') != "LM"){
          echo json_encode("Not Authorised..!, Please Login With LM's Credentials!");
          exit;
       }
       //**************************************************/

       $data['dist_code'] = $dist_code = $this->session->userdata('dist_code');
       $data['subdiv_code'] = $subdiv_code = $this->session->userdata('subdiv_code');
       $data['circle_code'] = $cir_code = $this->session->userdata('cir_code'); 
       $data['mouza_pargona_code'] = $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');    
       $data['lot_no'] = $lot_no = $this->session->userdata('lot_no'); 
       $data['pending_list'] = $getLbPendingList = $this->ChithaFlagModel->getApprovedCountOfVillageLM($dist_code,$subdiv_code,$cir_code,$mouza_pargona_code,$lot_no,'F');
       $data['_view'] = 'chitha_flag/approved_list';
       $this->load->view('layout/layout_dhar',$data);
    }

    public function getSubCategory(){

        $category = $this->input->post('category');
        $data['subCategory'] = $subCategory = $this->ChithaFlagModel->getSubCategory($category);
        echo json_encode($data);


    }

    public function RevertMappingWithChitha()
    {
        $dist_code = $this->input->post('dist_code1');
        $subdiv_code = $this->input->post('subdiv_code1');
        $cir_code = $this->input->post('cir_code1');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code1');
        $lot_no = $this->input->post('lot_no1');
        $vill_code = $this->input->post('vill_code');
        if($this->input->post('co_remarks') == null){
            echo json_encode(array('responseType' => 3,'msg' => '#ERROR-0112 Remarks field is mandatory'));
            return;
        }
        $this->db->trans_begin();

        $dag_list = $this->input->post('dag_list');
        $dag_array = explode(',',$dag_list);

        if(empty($dag_array) || $dag_array == null){
            echo json_encode(array('responseType' => 3,'msg' => '#ERROR-01123 Dag list not found...'));
            return;
        }
        
        
        $villname = $this->utilityclass->getVillageName($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code,$lot_no,$vill_code);
        foreach ($dag_array as $key => $value) {
            $where = array(
                'dist_code' =>  $this->input->post('dist_code1'),
                'subdiv_code' =>  $this->input->post('subdiv_code1'),
                'cir_code' =>  $this->input->post('cir_code1'),
                'mouza_pargona_code' =>  $this->input->post('mouza_pargona_code1'),
                'lot_no' =>  $this->input->post('lot_no1'),
                'vill_townprt_code' =>  $vill_code,
                'status'=>'P',
                'dag_no' => $value
            );
            $data = array(
                'status' => 'R',
                'remarks' => $this->input->post('co_remarks'),
                'updation_date_time' => date('Y-m-d H:i:s')
            );

            $affectedRows = $this->TransactionModel->update_multiple_condition('chitha_dag_all_flag_details', $where, $data);

            if($affectedRows <= 0){
                $this->db->trans_rollback();
                log_message("error",'#ERROR-064 Revert failed for the village'.$villname);
                echo json_encode(array('responseType' => 3,'msg' => '#ERROR-064 Revert failed for the village '.$villname));
                return;
            }
        }

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return;

        }else{
            $this->db->trans_commit();
            echo json_encode(array('responseType' => 2,'msg' => 'Revert successfully for the dags '.$dag_list.' and village=='.$villname));
            return;
        }

        
        
    }


    public function getAllSelectedDags()
    {

        // $this->load->model('chitha/ChithaFlagModel');
        $this->load->model('Dagflagmodel');
        $dist_code = $this->input->post('dist_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $vill_code = $this->input->post('vill_townprt_code');

        $q = "select string_agg(t1.dag_no::text, ',') from 
        (
            Select * from  chitha_basic join landclass_code on
            chitha_basic.land_class_code = landclass_code.class_code  where dist_code=? and subdiv_code=? and  cir_code=? 
            and mouza_pargona_code=? and lot_no=?
            and vill_townprt_code=? order by CAST(coalesce(dag_no_int, '0') AS numeric)
        ) as t1
        left join (

            Select * from  chitha_dag_all_flag_details  where dist_code=? and subdiv_code=? and  cir_code=? 
            and mouza_pargona_code=? and lot_no=?
            and vill_townprt_code=? 

        ) as t2 
        on t1.dist_code = t2.dist_code
        and t1.subdiv_code = t2.subdiv_code 
        and t1.cir_code = t2.cir_code 
        and t1.Mouza_Pargona_code = t2.Mouza_Pargona_code
        and t1.lot_no = t2.lot_no 
        and t1.Vill_townprt_code = t2.Vill_townprt_code 
        and t1.dag_no=t2.dag_no 
        where t2.dag_no is null";


        $district = $this->db->query($q,array($dist_code,$subdiv_code,$cir_code,$mouza_pargona_code,$lot_no,$vill_code,$dist_code,$subdiv_code,$cir_code,$mouza_pargona_code,$lot_no,$vill_code));
        if(!empty($district->row())){
            $dags = $district->row()->string_agg;
            if($dags == null){
                echo json_encode(array('responseType' => 3,'select_dags' => null));
                return;
            }else{
                echo json_encode(array('responseType' => 2,'select_dags' => $dags));
                return;

            }
        }else{
            echo json_encode(array('responseType' => 3,'select_dags' => null));
            return;
        }
        
    }


    public function generateFlaggingReport()
    {
        $user_desig_code = $this->session->userdata('user_desig_code');

        if ($user_desig_code == 'CO') {
            $file_name  = "Chitha-dag-flag-report".time()."_dag.xlsx";

            $dist_code = $this->session->userdata('dist_code');
            $subdiv_code = $this->session->userdata('subdiv_code');
            $cir_code = $this->session->userdata('cir_code');

            $data = $this->db->query("select 
                        
                        (select loc_name from location l where
                        dist_code= cb.dist_code and subdiv_code ='00'
                        ) as dist_name,
                        (select loc_name from location l where
                        dist_code= cb.dist_code and subdiv_code =cb.subdiv_code and cir_code = '00'
                        ) as subdiv_name,
                        (select loc_name from location l where
                        dist_code= cb.dist_code and subdiv_code =cb.subdiv_code and cir_code = cb.cir_code and mouza_pargona_code = '00'
                        ) as cir_name,
                        (select loc_name from location l where
                        dist_code= cb.dist_code and subdiv_code =cb.subdiv_code and cir_code = cb.cir_code and mouza_pargona_code = cb.mouza_pargona_code
                        and lot_no ='00' 
                        ) as mouza_name,
                        (select loc_name from location l where
                        dist_code= cb.dist_code and subdiv_code =cb.subdiv_code and cir_code = cb.cir_code and mouza_pargona_code = cb.mouza_pargona_code
                        and lot_no =cb.lot_no and vill_townprt_code = '00000'
                        ) as lot,
                        (select loc_name from location l where
                        dist_code= cb.dist_code and subdiv_code =cb.subdiv_code and cir_code = cb.cir_code and mouza_pargona_code = cb.mouza_pargona_code
                        and lot_no =cb.lot_no and vill_townprt_code = cb.vill_townprt_code
                        ) as village,count(cb.dag_no) as chitha_dag_count,
                        (select count(*) from chitha_dag_all_flag_details_final cdf where dist_code= cb.dist_code and subdiv_code =cb.subdiv_code and cir_code = cb.cir_code and mouza_pargona_code = cb.mouza_pargona_code
                        and lot_no =cb.lot_no and vill_townprt_code = cb.vill_townprt_code and area_flag in (10,11,12,13,14,15,16,17,18,19,20,21,22)) as dag_flag_count
                        from chitha_basic cb where cb.dist_code = '$dist_code' and cb.subdiv_code = '$subdiv_code' and cb.cir_code='$cir_code'
                        group by cb.dist_code,cb.subdiv_code,cb.cir_code,cb.mouza_pargona_code,cb.lot_no,cb.vill_townprt_code")->result_array();

            $this->UtilsModel->downloadExcelReport($file_name,$data);
        }else{
            echo "Access Denied--------------ERROR019--*";
            die;
        }

    }

}

?>