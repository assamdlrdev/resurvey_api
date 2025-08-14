<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bengalicontrol extends CI_Controller{

function __contruct()
	{
	parent::controller();	
	}

	function chkname(){
		$CI=&get_instance();
	    $this->db=$CI->load->database('ccachar', TRUE);

		$this->db->select('*');
		$query=$this->db->get_where('landclass_code');

		foreach($query->result() as $row){
			$lclasscd=$row->class_code;
			$lclass=$row->land_type;
			$aword=$lclass;
			$lclass1=$this->bengali_model->changera($aword);
			$nrow=$this->bengali_model->updbengali($lclasscd,$lclass1);
			if ($nrow>0){
				echo 'Data update';
			}
			//echo $lclasscd." ".$lclass1.'<br>';
		}
	}

	
}
?>