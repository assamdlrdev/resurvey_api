<?php
class Chithamodel extends CI_Model {
 
  
	public function districtdetails($distcode) {
		return $this->db->get_where('location',array('dist_code'=>$distcode,'subdiv_code'=>'00','cir_code'=>'00','mouza_pargona_code'=>'00','lot_no'=>'00','vill_townprt_code'=>'00000'))->result_array();
	}
	public function subdivisiondetails($dist) {
		return $this->db->get_where('location',array('dist_code'=>$dist,'subdiv_code !='=>'00','cir_code'=>'00','mouza_pargona_code'=>'00','lot_no'=>'00','vill_townprt_code'=>'00000'))->result_array();
	}
	public function  circledetails($dist,$sub) {
		return $this->db->get_where('location',array('dist_code'=>$dist,'subdiv_code ='=>$sub,'cir_code!='=>'00','mouza_pargona_code'=>'00','lot_no'=>'00','vill_townprt_code'=>'00000'))->result_array();
	}
	public function mouzadetails($dist,$sub,$circle) {
		return $this->db->get_where('location',array('dist_code'=>$dist,'subdiv_code ='=>$sub,'cir_code='=>$circle,'mouza_pargona_code!='=>'00','lot_no'=>'00','vill_townprt_code'=>'00000'))->result_array();
	}
	public function lotdetails($dist,$sub,$circle,$mza) {
		return $this->db->get_where('location',array('dist_code'=>$dist,'subdiv_code ='=>$sub,'cir_code='=>$circle,'mouza_pargona_code='=>$mza,'lot_no!='=>'00','vill_townprt_code'=>'00000'))->result_array();
	}
	public function villagedetails($dist,$sub,$circle,$mza,$lot) {
		return $this->db->get_where('location',array('dist_code'=>$dist,'subdiv_code ='=>$sub,'cir_code='=>$circle,'mouza_pargona_code='=>$mza,'lot_no='=>$lot,'vill_townprt_code!='=>'00000'))->result_array();
	}
    public function districtdetailsreport() {
		return $this->db->get_where('location',array('subdiv_code'=>'00','cir_code'=>'00','mouza_pargona_code'=>'00','lot_no'=>'00','vill_townprt_code'=>'00000'))->result_array();
	}
	/* public function getPattaType() {
		return $this->db->get_where('patta_code',array('type_code!='=>'0000'))->result();
    } */
	public function getPattaType() {
        $ptype = $this->db->query("Select type_code,patta_type from patta_code order by type_code asc");
        return $ptype->result();
	 
    }
	/* public function getLandclasscode() {
		return $this->db->get_where('landclass_code',array('class_code!='=>'0000'))->result();
    } */
	public function getLandclasscode() {
        $landclasstype = $this->db->query("Select class_code,land_type from landclass_code order by class_code asc");
        return $landclasstype->result();
    }
	public function getGuardrelation() {
		return $this->db->get_where('master_guard_rel',array('guard_rel!='=>''))->result();
    }
		
	function patta_type_name($pattatype){
	    $this->db->select('patta_type');
		$qp=$this->db->get_where('patta_code',array('type_code'=>$pattatype));
		$rp=$qp->row_array();
		$patname=$rp['patta_type'];
		return $patname;
	}

	function checkpattadarid(){
		$dist_code = $this->session->userdata('dist_code');
		$subdiv_code = $this->session->userdata('subdiv_code');
		$cir_code = $this->session->userdata('cir_code');
		$mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
		$lot_no = $this->session->userdata('lot_no');
		$vill_townprt_code = $this->session->userdata('vill_townprt_code');
		$patta_no=$this->session->userdata('patta_no');
		$patta_type_code=$this->session->userdata('patta_type');
		$where="(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' and trim(patta_no)=trim('$patta_no') and patta_type_code='$patta_type_code')";
		$this->db->select_max('pdar_id', 'max');
		$query=$this->db->get_where('chitha_pattadar',$where);
		if ($query->num_rows() == 0) {
           return 1;
        }
           $max = $query->row()->max;
           return $max == 0 ? 1 : $max + 1;
	    }
		
	function relation(){
		$this->db->select('guard_rel,guard_rel_desc_as');
		$query=$this->db->get_where('master_guard_rel');
		return $query->result();
	}

	function ntrcode(){
		$this->db->select('trans_code,trans_desc_as');
		$query=$this->db->get_where('nature_trans_code');
		return $query->result();
	}
	
	function fmuttype(){
		$this->db->select('order_type_code,order_type');
		$query=$this->db->get_where('master_field_mut_type');
		return $query->result();
	}
	
	function lmname(){
		$dist_code = $this->session->userdata('dist_code');
		$subdiv_code = $this->session->userdata('subdiv_code');
		$cir_code = $this->session->userdata('cir_code');
		$mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
		$lot_no = $this->session->userdata('lot_no');
		$where="(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no')";
		$this->db->select('lm_code,lm_name');
		$query=$this->db->get_where('lm_code',$where);
		return $query->result();
	}
	
	function coname(){
		$dist_code = $this->session->userdata('dist_code');
		$subdiv_code = $this->session->userdata('subdiv_code');
		$cir_code = $this->session->userdata('cir_code');
		$where="(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and user_desig_code='CO')";
		$this->db->select('user_code,username');
		$query=$this->db->get_where('users',$where);
		return $query->result();
	}
	
	function ordersrno(){
		$dist_code = $this->session->userdata('dist_code');
		$subdiv_code = $this->session->userdata('subdiv_code');
		$cir_code = $this->session->userdata('cir_code');
		$mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
		$lot_no = $this->session->userdata('lot_no');
		$vill_townprt_code = $this->session->userdata('vill_townprt_code');
		$dag_no=$this->session->userdata('dag_no');
		
		$where="(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' and dag_no='$dag_no')";
		$this->db->select_max('col8order_cron_no', 'max');
		$query=$this->db->get_where('chitha_col8_order',$where);
		if ($query->num_rows() == 0) {
           return 1;
        }
           $max = $query->row()->max;
           return $max == 0 ? 1 : $max + 1;
	    }
		
	function occupantid(){
		$dist_code = $this->session->userdata('dist_code');
		$subdiv_code = $this->session->userdata('subdiv_code');
		$cir_code = $this->session->userdata('cir_code');
		$mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
		$lot_no = $this->session->userdata('lot_no');
		$vill_townprt_code = $this->session->userdata('vill_townprt_code');
		$dag_no=$this->session->userdata('dag_no');
		
		$where="(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' and dag_no='$dag_no')";
		$this->db->select_max('occupant_id', 'max');
		$query=$this->db->get_where('chitha_col8_occup',$where);
		if ($query->num_rows() == 0) {
           return 1;
        }
           $max = $query->row()->max;
           return $max == 0 ? 1 : $max + 1;
	    }
		
	function inplaceid(){
		$dist_code = $this->session->userdata('dist_code');
		$subdiv_code = $this->session->userdata('subdiv_code');
		$cir_code = $this->session->userdata('cir_code');
		$mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
		$lot_no = $this->session->userdata('lot_no');
		$vill_townprt_code = $this->session->userdata('vill_townprt_code');
		$dag_no=$this->session->userdata('dag_no');
		$col8crno=$this->session->userdata('col8crno');
		
		$where="(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' and dag_no='$dag_no' and col8order_cron_no=$col8crno)";
		$this->db->select_max('inplace_of_id', 'max');
		$query=$this->db->get_where('chitha_col8_inplace',$where);
		if ($query->num_rows() == 0) {
           return 1;
        }
           $max = $query->row()->max;
           return $max == 0 ? 1 : $max + 1;
	    }

		function relationame($pdar_rel){
			$this->db->select('guard_rel_desc_as');
			$qp=$this->db->get_where('master_guard_rel',array('guard_rel'=>$pdar_rel));
			$rp=$qp->row_array();
			$relname=$rp['guard_rel_desc_as'];
			return $relname;
		}
	
	function pdardag($patta_no,$pattatype){
		$dcode = $this->session->userdata('dist_code');
		$scode = $this->session->userdata('subdiv_code');
		$ccode = $this->session->userdata('cir_code');
		$mcode = $this->session->userdata('mouza_pargona_code');
		$lcode = $this->session->userdata('lot_no');
		$vcode = $this->session->userdata('vill_townprt_code');
		$where="(dist_code='$dcode' and subdiv_code='$scode' and cir_code='$ccode' and mouza_pargona_code='$mcode' and lot_no='$lcode' and vill_townprt_code='$vcode' and patta_no='$patta_no' and patta_type_code='$pattatype')";
		$this->db->select('dag_no');
		$this->db->distinct();
		$query=$this->db->get_where('chitha_dag_pattadar',$where);
		return $query->result();
	}
	
	
	function pattadardet($patta_no,$pattatype){
	   
		$dcode = $this->session->userdata('dist_code');
		$scode = $this->session->userdata('subdiv_code');
		$ccode = $this->session->userdata('cir_code');
		$mcode = $this->session->userdata('mouza_pargona_code');
		$lcode = $this->session->userdata('lot_no');
		$vcode = $this->session->userdata('vill_townprt_code');
		$sql="select a.pdar_id,a.pdar_name,a.patta_no,a.patta_type_code,b.dag_no from chitha_pattadar as a join chitha_dag_pattadar as b on a.patta_no=b.patta_no and a.patta_type_code=b.patta_type_code and a.dist_code=b.dist_code and a.subdiv_code=b.subdiv_code and a.cir_code=b.cir_code and a.mouza_pargona_code=b.mouza_pargona_code and a.lot_no=b.lot_no and a.vill_townprt_code=b.vill_townprt_code and a.pdar_id=b.pdar_id where a.patta_no='$patta_no' and a.patta_type_code='$pattatype' and a.dist_code='$dcode' and a.subdiv_code='$scode' and a.cir_code='$ccode' and a.mouza_pargona_code='$mcode' and a.lot_no='$lcode' and a.vill_townprt_code='$vcode' order by b.dag_no";
	   
		$query=$this->db->query($sql);
		
		if($query->num_rows() > 0){
			 return $query->result();
		 } else {
			return false;
		 }
	 }

	function pattadardagdet($patta_no,$pattatype,$dagno){
       
		$dcode = $this->session->userdata('dist_code');
		$scode = $this->session->userdata('subdiv_code');
		$ccode = $this->session->userdata('cir_code');
		$mcode = $this->session->userdata('mouza_pargona_code');
		$lcode = $this->session->userdata('lot_no');
		$vcode = $this->session->userdata('vill_townprt_code');
		$sql="select a.pdar_id,a.pdar_name,a.patta_no,a.patta_type_code,b.dag_no from chitha_pattadar as a join chitha_dag_pattadar as b on a.patta_no=b.patta_no and a.patta_type_code=b.patta_type_code and a.dist_code=b.dist_code and a.subdiv_code=b.subdiv_code and a.cir_code=b.cir_code and a.mouza_pargona_code=b.mouza_pargona_code and a.lot_no=b.lot_no and a.vill_townprt_code=b.vill_townprt_code and a.pdar_id=b.pdar_id where b.dag_no='$dagno' and a.patta_no='$patta_no' and a.patta_type_code='$pattatype' and a.dist_code='$dcode' and a.subdiv_code='$scode' and a.cir_code='$ccode' and a.mouza_pargona_code='$mcode' and a.lot_no='$lcode' and a.vill_townprt_code='$vcode' order by b.dag_no";
	   
		$query=$this->db->query($sql);
		
		$str='';
		
		 $str=$str.'<table class="table" border=0 bgcolor="#BFFFE6">';
		 $str=$str.'<tr><td></td><td>Id</td><td>Name</td></tr>';
			   if ($query) {
				  foreach($query->result() as $row) { 
					 $pid=$row->pdar_id;
					 $pname=$row->pdar_name;
					 $patta=$row->patta_no;
					 $ptype=$row->patta_type_code;
					 $dno=$row->dag_no;
					 $vl=$pid.','.$pname.','.$patta.','.$ptype.','.$dno;
					$str=$str.'<tr><td><input type="checkbox" name="chk[]" id="chk[]" value="' .$vl . '"></td><td>'.$row->pdar_id.'</td><td>'.$row->pdar_name.'</td><tr>';
				} } 
		 $str=$str.'</table>';
		 return $str;
	 }

	 function pattadarinsdet($patta,$ptype,$dagno){
		
		$sql="select a.pdar_id,a.pdar_name,a.patta_no,a.patta_type_code,b.dag_no from chitha_pattadar as a join chitha_dag_pattadar as b on a.patta_no=b.patta_no and a.patta_type_code=b.patta_type_code and a.dist_code=b.dist_code and a.subdiv_code=b.subdiv_code and a.cir_code=b.cir_code and a.mouza_pargona_code=b.mouza_pargona_code and a.lot_no=b.lot_no and a.vill_townprt_code=b.vill_townprt_code and a.pdar_id=b.pdar_id where b.dag_no='$dagno' and a.patta_no='$patta' and a.patta_type_code='$ptype' order by b.dag_no";
	  
		$query=$this->db->query($sql);
		
		$str='';
		
		 $str=$str.'<table class="table" border=0 bgcolor="#BFFFE6">';
		 $str=$str.'<tr><td>Id</td><td>Name</td></tr>';
			   if ($query) {
				  foreach($query->result() as $row) { 
					$str=$str.'<tr><td>'.$row->pdar_id.'</td><td>'.$row->pdar_name.'</td><tr>';
				} } 
		 $str=$str.'</table>';
		 return $str;
	}

	function insertexitdag($pid,$pname,$patta,$ptype){

		$edagno=$this->input->post('edag');

		$dcode=$this->session->userdata('dist_code');
		$scode=$this->session->userdata('subdiv_code');
		$ccode=$this->session->userdata('cir_code');
		$mcode=$this->session->userdata('mouza_pargona_code');
		$lcode=$this->session->userdata('lot_no');
		$vcode=$this->session->userdata('vill_townprt_code');

		$where="(dist_code='$dcode' and subdiv_code='$scode' and cir_code='$ccode' and mouza_pargona_code='$mcode' and lot_no='$lcode' and vill_townprt_code='$vcode' and  dag_no='$edagno' and pdar_id=$pid and patta_no='$patta' and patta_type_code='$ptype')";
		$this->db->select('dag_no,patta_no');
		$query=$this->db->get_where('chitha_dag_pattadar',$where);
		if($query->num_rows() == 0){
		    
		$data['data'] = array(
			'dist_code' => $this->session->userdata('dist_code'),
			'subdiv_code' => $this->session->userdata('subdiv_code'),
			'cir_code' => $this->session->userdata('cir_code'),
			'mouza_pargona_code' => $this->session->userdata('mouza_pargona_code'),
			'lot_no' => $this->session->userdata('lot_no'),
			'vill_townprt_code' => $this->session->userdata('vill_townprt_code'),
			'dag_no' => $edagno,
			'pdar_id' => $pid,
			'patta_no' => $patta,
			'patta_type_code' => $ptype,
			'dag_por_b' => 0,
			'dag_por_k' => 0,
			'dag_por_lc' => 0,
			'dag_por_g' =>  0,
			'pdar_land_n' => '',
			'pdar_land_s' => '',
			'pdar_land_e' => '',
			'pdar_land_w' => '',
			'pdar_land_acre' => 0,
			'pdar_land_revenue' => 0,
			'pdar_land_localtax' => 0,
			'user_code' => 'aaa',
			'date_entry' => date("Y-m-d | h:i:sa"),
			'operation' => 'E',
			'p_flag' => 0,
			'jama_yn' => 'n',
			  
		  );
		  $data['data_1'] = $this->security->xss_clean($data['data']);
		  $nrows=$this->db->insert('chitha_dag_pattadar', $data['data_1']);
		  
		  return $nrows;
		}

	}

	
	function occupnm($patta_no,$pattatype,$dag_no){

	    $sql="select a.pdar_id,a.pdar_name,a.pdar_father,a.pdar_relation,b.dag_por_b,b.dag_por_k,b.dag_por_lc from chitha_pattadar as a join chitha_dag_pattadar as b on a.patta_no=b.patta_no and a.patta_type_code=b.patta_type_code and a.dist_code=b.dist_code and a.subdiv_code=b.subdiv_code and a.cir_code=b.cir_code and a.mouza_pargona_code=b.mouza_pargona_code and a.lot_no=b.lot_no and a.vill_townprt_code=b.vill_townprt_code and a.pdar_id=b.pdar_id where b.dag_no='$dag_no' and a.patta_no='$patta_no' and a.patta_type_code='$pattatype'";
	   
	   $query=$this->db->query($sql);
	   
	   if($query->num_rows() > 0){
			return $query->result();
		} else {
           return false;
		}
	}
	
	function insertdag(){
		$dag_no=$this->input->post('dag_no');
		$dag_no_int=$dag_no * 100;
		$dcode=$this->session->userdata('dist_code');
		$scode=$this->session->userdata('subdiv_code');
		$ccode=$this->session->userdata('cir_code');
		$mcode=$this->session->userdata('mouza_pargona_code');
		$lcode=$this->session->userdata('lot_no');
		$vcode=$this->session->userdata('vill_townprt_code');
		
		
		$where="(dist_code='$dcode' and subdiv_code='$scode' and cir_code='$ccode' and mouza_pargona_code='$mcode' and lot_no='$lcode' and vill_townprt_code='$vcode' and  dag_no='$dag_no')";
		$this->db->select('dag_no,patta_no');
		$query=$this->db->get_where('chitha_basic',$where);
		if($query->num_rows() == 0){
		
		$details = array(
          'dist_code' => $dcode,
          'subdiv_code' => $scode,
          'cir_code' => $ccode,
          'mouza_pargona_code' => $mcode,
          'lot_no' => $lcode,
          'vill_townprt_code' => $vcode,
          'old_dag_no' => $this->input->post('old_dag_no'),
          'dag_no' => $dag_no,
		  'dag_no_int' => $dag_no_int,
          'patta_type_code' => $this->input->post('patta_type_code'),
          'patta_no' => $this->input->post('patta_no'),
          'land_class_code' => $this->input->post('land_class_code'),
          'dag_area_b' => $this->input->post('dag_area_b'),
          'dag_area_k' => $this->input->post('dag_area_k'),
          'dag_area_lc' => $this->input->post('dag_area_lc'),
	  'dag_area_g' => $this->input->post('dag_area_g'),
          'dag_revenue' => $this->input->post('dag_land_revenue'),
          'dag_local_tax' => $this->input->post('dag_local_tax'),
          'dag_n_desc' => $this->input->post('dag_n_desc'),
          'dag_s_desc' => $this->input->post('dag_s_desc'),
          'dag_e_desc' => $this->input->post('dag_e_desc'),
          'dag_w_desc' => $this->input->post('dag_w_desc'),
          'dag_n_dag_no' => $this->input->post('dag_n_dag_no'),
          'dag_s_dag_no' => $this->input->post('dag_s_dag_no'),
          'dag_e_dag_no' => $this->input->post('dag_e_dag_no'),
          'dag_w_dag_no' => $this->input->post('dag_w_dag_no'),
          'dag_area_kr' => '00',
		  'dag_nlrg_no' => $this->input->post('dag_nlrg_no'),
		  'dp_flag_yn' => $this->input->post('dp_flag_yn'),
          'user_code' => '00',
          'date_entry' => date("Y-m-d | h:i:sa"),
          'operation' => 'E',  
          'jama_yn' => 'n',		  
		  );
		  
		  $data = $this->security->xss_clean($details);
		  $nrows=$this->db->insert('chitha_basic',$details);
		  return $nrows;
		  
		} else {
			
	    $details = array(
          'old_dag_no' => $this->input->post('old_dag_no'),
		  'patta_type_code'=>$this->input->post('patta_type_code'),
          'patta_no'=>$this->input->post('patta_no'),
          'land_class_code' => $this->input->post('land_class_code'),
          'dag_area_b' => $this->input->post('dag_area_b'),
          'dag_area_k' => $this->input->post('dag_area_k'),
          'dag_area_lc' => $this->input->post('dag_area_lc'),
		  'dag_area_g' => $this->input->post('dag_area_g'),
          'dag_revenue' => $this->input->post('dag_land_revenue'),
          'dag_local_tax' => $this->input->post('dag_local_tax'),
          'dag_n_desc' => $this->input->post('dag_n_desc'),
          'dag_s_desc' => $this->input->post('dag_s_desc'),
          'dag_e_desc' => $this->input->post('dag_e_desc'),
          'dag_w_desc' => $this->input->post('dag_w_desc'),
          'dag_n_dag_no' => $this->input->post('dag_n_dag_no'),
          'dag_s_dag_no' => $this->input->post('dag_s_dag_no'),
          'dag_e_dag_no' => $this->input->post('dag_e_dag_no'),
          'dag_w_dag_no' => $this->input->post('dag_w_dag_no'),
          'dag_area_kr' => '0',
		  'dag_nlrg_no' => $this->input->post('dag_nlrg_no'),
		  'dp_flag_yn' => $this->input->post('dp_flag_yn'),
          'user_code' => '00',
          'date_entry' => date("Y-m-d | h:i:sa")
		  );
		  $this->db->where(array('dist_code'=>$dcode,'subdiv_code'=>$scode,'cir_code'=>$ccode,'mouza_pargona_code'=>$mcode,'lot_no'=>$lcode,'vill_townprt_code'=>$vcode,'dag_no'=>$dag_no));
		  $data = $this->security->xss_clean($details);
	      $nrows=$this->db->update('chitha_basic',$data);
	      return $nrows;
		}
		
	}
	
	
	function insertpattadar(){
			
			  $data['data'] = array(
	          'dist_code' => $this->session->userdata('dist_code'),
			  'subdiv_code' => $this->session->userdata('subdiv_code'),
			  'cir_code' => $this->session->userdata('cir_code'),
			  'mouza_pargona_code' => $this->session->userdata('mouza_pargona_code'),
			  'lot_no' => $this->session->userdata('lot_no'),
			  'vill_townprt_code' => $this->session->userdata('vill_townprt_code'),
			  'dag_no' => $this->input->post('dag_no'),
			  'pdar_id' => $this->input->post('pdar_id'),
			  'patta_no' => $this->input->post('patta_no'),
			  'patta_type_code' => $this->input->post('patta_type_code'),
			  'dag_por_b' => $this->input->post('dag_por_b'),
			  'dag_por_k' => $this->input->post('dag_por_k'),
			  'dag_por_lc' => $this->input->post('dag_por_lc'),
			  'dag_por_g' =>  $this->input->post('dag_por_g'),
			  'pdar_land_n' => $this->input->post('pdar_land_n'),
			  'pdar_land_s' => $this->input->post('pdar_land_s'),
			  'pdar_land_e' => $this->input->post('pdar_land_e'),
			  'pdar_land_w' => $this->input->post('pdar_land_w'),
			  'pdar_land_acre' => $this->input->post('pdar_land_acre'),
			  'pdar_land_revenue' => $this->input->post('pdar_land_revenue'),
			  'pdar_land_localtax' => $this->input->post('pdar_land_localtax'),
			  'user_code' => 'aaa',
			  'date_entry' => date("Y-m-d | h:i:sa"),
			  'operation' => 'E',
			  'p_flag' => $this->input->post('p_flag'),
			  'jama_yn' => 'n',
                
            );
			$data['data_1'] = $this->security->xss_clean($data['data']);
            $this->db->insert('chitha_dag_pattadar', $data['data_1']);
			
			$data['data'] = array(
	          'dist_code' => $this->session->userdata('dist_code'),
			  'subdiv_code' => $this->session->userdata('subdiv_code'),
			  'cir_code' => $this->session->userdata('cir_code'),
			  'mouza_pargona_code' => $this->session->userdata('mouza_pargona_code'),
			  'lot_no' => $this->session->userdata('lot_no'),
			  'vill_townprt_code' => $this->session->userdata('vill_townprt_code'),
			  'pdar_id' => $this->input->post('pdar_id'),
			  'patta_no' => $this->input->post('patta_no'),
			  'patta_type_code' => $this->input->post('patta_type_code'),
			  'pdar_name' => $this->input->post('pdar_name'),
			  'pdar_relation' => $this->input->post('pdar_relation'),
			  'pdar_father' => $this->input->post('pdar_father'),
			  'pdar_add1' => $this->input->post('pdar_add1'),
			  'pdar_add2' => $this->input->post('pdar_add2'),
			  'pdar_add3' => $this->input->post('pdar_add3'),
			  'pdar_pan_no' => $this->input->post('pdar_pan_no'),
			  'pdar_citizen_no' => $this->input->post('pdar_citizen_no'),
			  'user_code' => 'aaa',
			  'date_entry' => date("Y-m-d | h:i:sa"),
			  'operation' => 'o',
			  'jama_yn' => 'n',
                
            );
			$data['data_1'] = $this->security->xss_clean($data['data']);
            $this->db->insert('chitha_pattadar', $data['data_1']);
            $nrows = $this->db->affected_rows();
            return $nrows;
	}
	
	function insertcol8order(){
		
		$deed_value=$this->input->post('deed_value');
		if (!$deed_value){
			$deed_value='0.0000';
		}
		$deed_date=$this->input->post('deed_date');
		if (!$deed_date){
			$deed_date='1900-01-01';
		}
		$data['data'] = array(
	          'dist_code' => $this->session->userdata('dist_code'),
			  'subdiv_code' => $this->session->userdata('subdiv_code'),
			  'cir_code' => $this->session->userdata('cir_code'),
			  'mouza_pargona_code' => $this->session->userdata('mouza_pargona_code'),
			  'lot_no' => $this->session->userdata('lot_no'),
			  'vill_townprt_code' => $this->session->userdata('vill_townprt_code'),
			  'dag_no' => $this->input->post('dag_no'),
			  'col8order_cron_no' => $this->input->post('col8order_cron_no'),
			  'order_pass_yn' => $this->input->post('order_pass_yn'),
			  'order_type_code' => $this->input->post('order_type_code'),
			  'nature_trans_code' => $this->input->post('nature_trans_code'),
			  'lm_code' => $this->input->post('lm_code'),
			  'lm_sign_yn' => $this->input->post('lm_sign_yn'),
			  'lm_note_date' => $this->input->post('lm_note_date'),
			  'co_code' => $this->input->post('co_code'),
			  'co_sign_yn' => $this->input->post('co_sign_yn'),
			  'co_ord_date' => $this->input->post('co_ord_date'),
			  'user_code' => 'deo',
			  'date_entry' => date("Y-m-d | h:i:sa"),
			  'operation' => 'E',
			  'jama_updated' => 'n',
			  'deed_reg_no' => $this->input->post('deed_reg_no'),
			  'deed_value' => $deed_value,
			  'deed_date' => $deed_date,
			  'case_no' => $this->input->post('case_no'),
			  'mut_land_area_b' => 0,
			  'mut_land_area_k' => 0,
			  'mut_land_area_lc' => 0,
			  'mut_land_area_g' => 0,
			  'mut_land_area_kr' => 0,
			  'land_area_left_b' => 0,
			  'land_area_left_k' => 0,
			  'land_area_left_lc' => 0,
			  'land_area_left_g' => 0,
			  'land_area_left_kr' => 0,
			  
			  
		 );
		    $data['data_1'] = $this->security->xss_clean($data['data']);
            $this->db->insert('chitha_col8_order', $data['data_1']);
            $nrows = $this->db->affected_rows();
            return $nrows;
	}
	
	function insertcol8occup(){
		$data['data'] = array(
	          'dist_code' => $this->session->userdata('dist_code'),
			  'subdiv_code' => $this->session->userdata('subdiv_code'),
			  'cir_code' => $this->session->userdata('cir_code'),
			  'mouza_pargona_code' => $this->session->userdata('mouza_pargona_code'),
			  'lot_no' => $this->session->userdata('lot_no'),
			  'vill_townprt_code' => $this->session->userdata('vill_townprt_code'),
			  'dag_no' => $this->input->post('dag_no'),
			  'col8order_cron_no' => $this->input->post('col8order_cron_no'),
			  'occupant_id' => $this->input->post('occupant_id'),
			  'occupant_name' => $this->input->post('occupantnm'),
			  'occupant_fmh_name' => $this->input->post('occupant_fmh_name'),
			  'occupant_fmh_flag' => $this->input->post('occupant_fmh_flag'),
			  'occupant_add1' => $this->input->post('occupant_add1'),
			  'occupant_add2' => $this->input->post('occupant_add2'),
			  'occupant_add3' => $this->input->post('occupant_add3'),
			  'land_area_b' => $this->input->post('land_area_b'),
			  'land_area_k' => $this->input->post('land_area_k'),
			  'land_area_lc' => $this->input->post('land_area_lc'),
			  'land_area_g' => $this->input->post('land_area_g'),
			  'land_area_kr' => 0,
			  'old_patta_no' => $this->input->post('old_patta_no'),
			  'new_patta_no' => $this->input->post('new_patta_no'),
			  'old_dag_no' => $this->input->post('old_dag_no'),
			  'new_dag_no' => $this->input->post('new_dag_no'),
			  'user_code' => 'deo',
			  'date_entry' => date("Y-m-d | h:i:sa"),
			  'operation' => 'E',
			  'chitha_up' => 'n',
			  
		 );
		    $data['data_1'] = $this->security->xss_clean($data['data']);
            $this->db->insert('chitha_col8_occup', $data['data_1']);
            $nrows = $this->db->affected_rows();
            return $nrows;
	}
	
	function insertcol8inplace(){
		$data['data'] = array(
	          'dist_code' => $this->session->userdata('dist_code'),
			  'subdiv_code' => $this->session->userdata('subdiv_code'),
			  'cir_code' => $this->session->userdata('cir_code'),
			  'mouza_pargona_code' => $this->session->userdata('mouza_pargona_code'),
			  'lot_no' => $this->session->userdata('lot_no'),
			  'vill_townprt_code' => $this->session->userdata('vill_townprt_code'),
			  'dag_no' => $this->input->post('dag_no'),
			  'col8order_cron_no' => $this->input->post('col8order_cron_no'),
			  'inplace_of_id' => $this->input->post('inplace_of_id'),
			  'inplaceof_alongwith' => $this->input->post('inplaceof_alongwith'),
			  'inplace_of_name' => $this->input->post('inplace_of_name'),
			  'land_area_b' => $this->input->post('land_area_b'),
			  'land_area_k' => $this->input->post('land_area_k'),
			  'land_area_lc' => $this->input->post('land_area_lc'),
			  'land_area_g' => $this->input->post('land_area_g'),
			  'land_area_kr' => 0,
			  'user_code' => 'deo',
			  'date_entry' => date("Y-m-d | h:i:sa"),
			  'operation' => 'E',
			  
		 );
		    $data['data_1'] = $this->security->xss_clean($data['data']);
            $this->db->insert('chitha_col8_inplace', $data['data_1']);
            $nrows = $this->db->affected_rows();
            return $nrows;
	}
	
	function relname(){
	    $this->db->select('guard_rel,guard_rel_desc_as');
		$query=$this->db->get_where('master_guard_rel');
			
		if($query->num_rows() > 0){
			return $query->result();	
		} else {
      return false;
      }
	}
	
	function tentype(){
	    $this->db->select('type_code,tenant_type');
		$query=$this->db->get_where('tenant_type');
			
		if($query->num_rows() > 0){
			return $query->result();	
		} else {
      return false;
      }
	}
	
	function checktenantid(){
		$dist_code = $this->session->userdata('dist_code');
		$subdiv_code = $this->session->userdata('subdiv_code');
		$cir_code = $this->session->userdata('cir_code');
		$mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
		$lot_no = $this->session->userdata('lot_no');
		$vill_townprt_code = $this->session->userdata('vill_townprt_code');
		$dag_no = $this->session->userdata('dag_no');
		$where="(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' and trim(dag_no)=trim('$dag_no'))";
		$this->db->select_max('tenant_id', 'max');
		$query=$this->db->get_where('chitha_tenant',$where);
		if ($query->num_rows() == 0) {
           return 1;
        }
           $max = $query->row()->max;
           return $max == 0 ? 1 : $max + 1;
	    }
	
	
	function inserttenant(){
		
		$details = array(
          'dist_code' => $this->session->userdata('dist_code'),
          'subdiv_code' => $this->session->userdata('subdiv_code'),
          'cir_code' => $this->session->userdata('cir_code'),
          'mouza_pargona_code' => $this->session->userdata('mouza_pargona_code'),
          'lot_no' => $this->session->userdata('lot_no'),
          'vill_townprt_code' => $this->session->userdata('vill_townprt_code'),
          'dag_no' => $this->session->userdata('dag_no'),
          'tenant_id'=> $this->input->post('tenant_id'),
		  'tenant_name'=> $this->input->post('tenant_name'),
		  'tenants_father'=> $this->input->post('tenants_father'),
		  'relation'=> $this->input->post('guard_rel'),
		  'tenants_add1'=> $this->input->post('tenants_add1'),
		  'tenants_add2'=> $this->input->post('tenants_add2'),
		  'tenants_add3'=> $this->input->post('tenants_add3'),
		  'type_of_tenant'=> $this->input->post('type_of_tenant'),
		  'khatian_no'=> $this->input->post('khatian_no'),
		  'revenue_tenant'=> $this->input->post('revenue_tenant'),
		  'crop_rate'=> $this->input->post('crop_rate'),
		  'user_code'=> 'd1',
		  'date_entry' => date("Y-m-d | h:i:sa"),
		  'operation'=>'E',
		  'status'=>'O',
		  'year_no'=>'2021'
		  );
		  $data = $this->security->xss_clean($details);
		  $nrows=$this->db->insert('chitha_tenant',$details);
		  return $nrows;
	}
	
	
	public function tenidsub(){
	    //$this->db->select('tenant_id,tenant_name');
		//$query=$this->db->get_where('chitha_tenant');
		$query = $this->db->query("select tenant_id,tenant_name from chitha_tenant");// where dist_code='24' and subdiv_code='01' and cir_code='01' and mouza_pargona_code='01' and lot_no='01' and vill_townprt_code='10001' and dag_no='01'");
		return $query->result();
	 	}
	
	function checksubtenantid(){
		 $dist_code = $this->session->userdata('dist_code');
		 $subdiv_code = $this->session->userdata('subdiv_code');
		 $cir_code = $this->session->userdata('cir_code');
		 $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
		 $lot_no = $this->session->userdata('lot_no');
		 $vill_townprt_code = $this->session->userdata('vill_townprt_code');
		 $dag_no = $this->session->userdata('dag_no');
		 $where="(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' and trim(dag_no)=trim('$dag_no'))";
		 $this->db->select_max('subtenant_id', 'max');
		 $query=$this->db->get_where('chitha_subtenant',$where);
		 if ($query->num_rows() == 0) {
           return 1;
        }
           $max = $query->row()->max;
           return $max == 0 ? 1 : $max + 1;
	    }
	
	public function insertsubtenant(){
		
		$details = array(
          'dist_code' => $this->session->userdata('dist_code'),
          'subdiv_code' => $this->session->userdata('subdiv_code'),
          'cir_code' => $this->session->userdata('cir_code'),
          'mouza_pargona_code' => $this->session->userdata('mouza_pargona_code'),
          'lot_no' => $this->session->userdata('lot_no'),
          'vill_townprt_code' => $this->session->userdata('vill_townprt_code'),
          'dag_no' => $this->session->userdata('dag_no'),
          'subtenant_id'=> $this->input->post('subtenant_id'),
		  'tenant_id'=> $this->input->post('tenantid'),
		  'subtenant_name'=> $this->input->post('subtennm'),
		  'subtenants_father'=> $this->input->post('subtenants_father'),
		  'relation'=> $this->input->post('guard_rel'),
		  'subtenants_add1'=> $this->input->post('subtenants_add1'),
		  'subtenants_add2'=> $this->input->post('subtenants_add2'),
		  'subtenants_add3'=> $this->input->post('subtenants_add3'),
		  'user_code'=> 'd1',
		  'date_entry' => date("Y-m-d | h:i:sa"),
		  'operation'=>'E',
		  'year_no'=>'2021'
		  );
		  $data = $this->security->xss_clean($details);
		  $nrows=$this->db->insert('chitha_subtenant',$details);
		  return $nrows;
	}
	
	function cropname(){
	    $this->db->select('crop_code,crop_name');
		$query=$this->db->get_where('crop_code');
			
		if($query->num_rows() > 0){
			return $query->result();	
		} else {
      return false;
      }
	}
	function cropcat(){
	    $this->db->select('crop_categ_code,crop_categ_desc');
		$query=$this->db->get_where('crop_category_code');
			
		if($query->num_rows() > 0){
			return $query->result();	
		} else {
      return false;
      }
	}
	function cropseason(){
	    $this->db->select('season_code,crop_season');
		$query=$this->db->get_where('crop_season');
			
		if($query->num_rows() > 0){
			return $query->result();	
		} else {
      return false;
      }
	}
	function watersource(){
	    $this->db->select('water_source_code,source');
		$query=$this->db->get_where('source_water');
			
		if($query->num_rows() > 0){
			return $query->result();	
		} else {
      return false;
      }
	}
	
	function checkcropid(){
		$dist_code = $this->session->userdata('dist_code');
		$subdiv_code = $this->session->userdata('subdiv_code');
		$cir_code = $this->session->userdata('cir_code');
		$mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
		$lot_no = $this->session->userdata('lot_no');
		$vill_townprt_code = $this->session->userdata('vill_townprt_code');
		$dag_no = $this->session->userdata('dag_no');
		$where="(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' and trim(dag_no)=trim('$dag_no'))";
		$this->db->select_max('crop_sl_no', 'max');
		$query=$this->db->get_where('chitha_mcrop',$where);
		if ($query->num_rows() == 0) {
           return 1;
        }
           $max = $query->row()->max;
           return $max == 0 ? 1 : $max + 1;
	    }
	
	function insertcrop(){
		$details = array(
          'dist_code' => $this->session->userdata('dist_code'),
          'subdiv_code' => $this->session->userdata('subdiv_code'),
          'cir_code' => $this->session->userdata('cir_code'),
          'mouza_pargona_code' => $this->session->userdata('mouza_pargona_code'),
          'lot_no' => $this->session->userdata('lot_no'),
          'vill_townprt_code' => $this->session->userdata('vill_townprt_code'),
          'dag_no' => $this->session->userdata('dag_no'),
          'crop_sl_no'=> $this->input->post('cropslno'),
		  'yearno'=> $this->input->post('yearno'),
		  'crop_code'=> $this->input->post('cropname'),
		  'crop_season'=> $this->input->post('cropseason'),
		  'source_of_water'=> $this->input->post('sourcewater'),
		  'crop_land_area_b'=> $this->input->post('croparea_b'),
		  'crop_land_area_k'=> $this->input->post('croparea_k'),
		  'crop_land_area_lc'=> $this->input->post('croparea_lc'),
		  'crop_land_area_g'=> $this->input->post('croparea_g'),
		  'crop_land_area_kr'=> 0,
		  'user_code'=> 'd1',
		  'date_entry' => date("Y-m-d | h:i:sa"),
		  'operation'=>'E',
		  'yearno'=>$this->input->post('yearno'),
		  'crop_categ_code'=>$this->input->post('cropcatg')
		  );
		  //var_dump($details);
		  $data = $this->security->xss_clean($details);
		  $nrows=$this->db->insert('chitha_mcrop',$details);
		  return $nrows;
	}
	
	function ncropname(){
	    $this->db->select('used_noncrop_type_code,noncrop_type');
		$query=$this->db->get_where('used_noncrop_type');
			
		if($query->num_rows() > 0){
			return $query->result();	
		} else {
      return false;
      }
	}
	
	function checknoncropid(){
		$dist_code = $this->session->userdata('dist_code');
		$subdiv_code = $this->session->userdata('subdiv_code');
		$cir_code = $this->session->userdata('cir_code');
		$mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
		$lot_no = $this->session->userdata('lot_no');
		$vill_townprt_code = $this->session->userdata('vill_townprt_code');
		$dag_no = $this->session->userdata('dag_no');
		$where="(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' and trim(dag_no)=trim('$dag_no'))";
		$this->db->select_max('noncrop_use_id', 'max');
		$query=$this->db->get_where('chitha_noncrop',$where);
		if ($query->num_rows() == 0) {
           return 1;
        }
           $max = $query->row()->max;
           return $max == 0 ? 1 : $max + 1;
	    }
	
	function insertnoncrop(){
		$details = array(
          'dist_code' => $this->session->userdata('dist_code'),
          'subdiv_code' => $this->session->userdata('subdiv_code'),
          'cir_code' => $this->session->userdata('cir_code'),
          'mouza_pargona_code' => $this->session->userdata('mouza_pargona_code'),
          'lot_no' => $this->session->userdata('lot_no'),
          'vill_townprt_code' => $this->session->userdata('vill_townprt_code'),
          'dag_no' => $this->session->userdata('dag_no'),
          'noncrop_use_id'=> $this->input->post('ncropslno'),
		  'yn'=> $this->input->post('yearno'),
		  'type_of_used_noncrop'=> $this->input->post('ncropcode'),
		  'noncrop_land_area_b'=> $this->input->post('ncroparea_b'),
		  'noncrop_land_area_k'=> $this->input->post('ncroparea_k'),
		  'noncrop_land_area_lc'=> $this->input->post('ncroparea_lc'),
		  'noncrop_land_area_g'=> 0,
		  'noncrop_land_area_kr'=> 0,
		  'user_code'=> 'd1',
		  'date_entry' => date("Y-m-d | h:i:sa"),
		  'operation'=>'E'
		  );
		  //var_dump($details);
		  $data = $this->security->xss_clean($details);
		  $nrows=$this->db->insert('chitha_noncrop',$details);
		  return $nrows;
	}
	
	function fruitname(){
	    $this->db->select('fruit_code,fruit_name');
		$query=$this->db->get_where('fruit_tree_code');
			
		if($query->num_rows() > 0){
			return $query->result();	
		} else {
      return false;
      }
	}
	
	
	function checkfruitid(){
		$dist_code = $this->session->userdata('dist_code');
		$subdiv_code = $this->session->userdata('subdiv_code');
		$cir_code = $this->session->userdata('cir_code');
		$mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
		$lot_no = $this->session->userdata('lot_no');
		$vill_townprt_code = $this->session->userdata('vill_townprt_code');
		$dag_no = $this->session->userdata('dag_no');
		$where="(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' and trim(dag_no)=trim('$dag_no'))";
		$this->db->select_max('fruit_plant_id', 'max');
		$query=$this->db->get_where('chitha_fruit',$where);
		if ($query->num_rows() == 0) {
           return 1;
        }
           $max = $query->row()->max;
           return $max == 0 ? 1 : $max + 1;
	    }
	
	function insertfruit(){
		$details = array(
          'dist_code' => $this->session->userdata('dist_code'),
          'subdiv_code' => $this->session->userdata('subdiv_code'),
          'cir_code' => $this->session->userdata('cir_code'),
          'mouza_pargona_code' => $this->session->userdata('mouza_pargona_code'),
          'lot_no' => $this->session->userdata('lot_no'),
          'vill_townprt_code' => $this->session->userdata('vill_townprt_code'),
          'dag_no' => $this->session->userdata('dag_no'),
          'fruit_plant_id'=> $this->input->post('frplantid'),
		  'fruit_plants_name'=> $this->input->post('frname'),
		  'no_of_plants'=> $this->input->post('fplantno'),
		  //'fruit_land_area_b'=> 0,
		  //'fruit_land_area_k'=> 0,
		  //'fruit_land_area_lc'=> 0,
		  //'fruit_land_area_g'=> 0,
		  //'fruit_land_area_kr'=> 0,
		  'user_code'=> 'd1',
		  'date_entry' => date("Y-m-d | h:i:sa"),
		  'operation'=>'E',
		  'year_no'=>$this->input->post('yearno')
		  );
		  //var_dump($details);
		  $data = $this->security->xss_clean($details);
		  $nrows=$this->db->insert('chitha_fruit',$details);
		  return $nrows;
	}
	
	 public function getlocationnames($dist,$sub,$cir,$mouza,$lot,$village) {
   
	   $location['dist_name']= $this->db->select('loc_name')->where(array('dist_code'=>$dist,'subdiv_code'=>'00','cir_code'=>'00','mouza_pargona_code'=>'00','lot_no'=>'00','vill_townprt_code'=>'00000'))->get('location')->row_array();
	   $location['subdiv_name']= $this->db->select('loc_name')->where(array('dist_code'=>$dist,'subdiv_code'=>$sub,'cir_code'=>'00','mouza_pargona_code'=>'00','lot_no'=>'00','vill_townprt_code'=>'00000'))->get('location')->row_array();
	   $location['cir_name']= $this->db->select('loc_name')->where(array('dist_code'=>$dist,'subdiv_code'=>$sub,'cir_code'=>$cir,'mouza_pargona_code'=>'00','lot_no'=>'00','vill_townprt_code'=>'00000'))->get('location')->row_array();
	   $location['mouza_name']= $this->db->select('loc_name')->where(array('dist_code'=>$dist,'subdiv_code'=>$sub,'cir_code'=>$cir,'mouza_pargona_code'=>$mouza,'lot_no'=>'00','vill_townprt_code'=>'00000'))->get('location')->row_array();
	   $location['lot']= $this->db->select('loc_name')->where(array('dist_code'=>$dist,'subdiv_code'=>$sub,'cir_code'=>$cir,'mouza_pargona_code'=>$mouza,'lot_no'=>$lot,'vill_townprt_code'=>'00000'))->get('location')->row_array();
	   $location['village']= $this->db->select('loc_name')->where(array('dist_code'=>$dist,'subdiv_code'=>$sub,'cir_code'=>$cir,'mouza_pargona_code'=>$mouza,'lot_no'=>$lot,'vill_townprt_code'=>$village))->get('location')->row_array();
			 
	   return $location;
 }

 function gettenants(){
	$dcode = $this->session->userdata('dist_code');
	$scode = $this->session->userdata('subdiv_code');
	$ccode = $this->session->userdata('cir_code');
	$mcode = $this->session->userdata('mouza_pargona_code');
	$lcode = $this->session->userdata('lot_no');
	$vcode = $this->session->userdata('vill_townprt_code');
	$dagno = $this->session->userdata('dag_no');
	$where="(dist_code='$dcode' and subdiv_code='$scode' and cir_code='$ccode' and mouza_pargona_code='$mcode' and lot_no='$lcode' and vill_townprt_code='$vcode' and dag_no='$dagno')";
	$this->db->select('tenant_id,tenant_name,tenants_father,dist_code,subdiv_code,cir_code,mouza_pargona_code,lot_no,vill_townprt_code,dag_no');
	$this->db->order_by('tenant_id');
	$query=$this->db->get_where('chitha_tenant',$where);
	return $query->result();
}

function idtendet($nm){
	$tn=explode("-",$nm);
	$tid=$tn[0];
	$dagno=$tn[1];
	$dcode=$tn[2];
	$scode=$tn[3];
	$ccode=$tn[4];
	$mcode=$tn[5];
	$lcode=$tn[6];
	$vcode=$tn[7];

	$where="(dist_code='$dcode' and subdiv_code='$scode' and cir_code='$ccode' and mouza_pargona_code='$mcode' and lot_no='$lcode' and vill_townprt_code='$vcode' and dag_no='$dagno' and tenant_id=$tid)";
	$this->db->select('tenant_id,tenant_name,tenants_father,relation,tenants_add1,tenants_add2,tenants_add3,type_of_tenant,khatian_no,revenue_tenant,crop_rate');
	$query=$this->db->get_where('chitha_tenant',$where);
	//return $query->result();
	$row = $query->row_array();
    $tnid = $row['tenant_id'];
	$tnme = $row['tenant_name'];
	$tfme = $row['tenants_father'];
	$trel = $row['relation'];
	$tad1 = $row['tenants_add1'];
	$tad2 = $row['tenants_add2'];
	$tad3 = $row['tenants_add3'];
	$ttyp = $row['type_of_tenant'];
	$khno = $row['khatian_no'];
	$trev = $row['revenue_tenant'];
	$crte = $row['crop_rate'];
    return $tdet= $tnid.'$'.$tnme.'$'. $tfme.'$'.$trel.'$'.$tad1.'$'.$tad2.'$'. $tad3.'$'.$ttyp.'$'.$khno.'$'.$trev.'$'.$crte;
  }

  function updatetenant() {
	    $dcode=$this->session->userdata('dist_code');
		$scode=$this->session->userdata('subdiv_code');
		$ccode=$this->session->userdata('cir_code');
		$mcode=$this->session->userdata('mouza_pargona_code');
		$lcode=$this->session->userdata('lot_no');
		$vcode=$this->session->userdata('vill_townprt_code');
		$dagno = $this->session->userdata('dag_no');
		$tenid=$this->input->post('tenant_id');
	
		$details = array(
			'tenant_name'=> $this->input->post('tenant_name'),
			'tenants_father'=> $this->input->post('tenants_father'),
			'relation'=> $this->input->post('guard_rel'),
			'tenants_add1'=> $this->input->post('tenants_add1'),
			'tenants_add2'=> $this->input->post('tenants_add2'),
			'tenants_add3'=> $this->input->post('tenants_add3'),
			'type_of_tenant'=> $this->input->post('type_of_tenant'),
			'khatian_no'=> $this->input->post('khatian_no'),
			'revenue_tenant'=> $this->input->post('revenue_tenant'),
			'crop_rate'=> $this->input->post('crop_rate')
			);
			$this->db->where(array('dist_code'=>$dcode,'subdiv_code'=>$scode,'cir_code'=>$ccode,'mouza_pargona_code'=>$mcode,'lot_no'=>$lcode,'vill_townprt_code'=>$vcode,'dag_no'=>$dagno,'tenant_id'=>$tenid));
			$data = $this->security->xss_clean($details);
			$nrows=$this->db->update('chitha_tenant',$data);
			return $nrows;
		}
}
