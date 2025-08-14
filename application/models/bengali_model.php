<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bengali_model extends CI_Model{

function bengali_model()
{
parent::__construct();
	$this->load->database();
}


function changera($aword){
	$nm=$aword; 
	$u=$nm;
	$nn=mb_strlen($nm);
	for ($i=0;$i<$nn;$i++){
		$arrayy[]=mb_substr($u, $i, 1,"UTF-8");
	}
	$cr='';
	//$nums=array(array('০','0'),array('১','1'),array('২','2'),array('৩','3'),array('৪','4'),array('৫','5'),array('৬','6'),array('৭','7'),array('৮','8'),array('৯','9'),array('.','.'),array(',',','),array('/','/'),array('(','('),array(')',')'),array("ক","Ka"),array("খ","Kha"));
	$nums=array(array('ৰ','র'));
	//$nums=array(array('0','0'));
	$flag='false';
for ($i=0;$i<count($arrayy);$i++){	
	
  for ($j=0;$j<1;$j++){
	
			if ($arrayy[$i]==$nums[$j][0]){
			
			  $cr = $cr.$nums[$j][1];
			  $flag='true';
			  $count[0]='T';
			  break;
		  } else {
			$cr = $cr.$arrayy[$i];
		  }
		  if ($flag=='true'){
			  break;
		  }
		
  }
}
return $cr;

}


function updbengali($lclasscd,$lclass1){
    $data=array('land_type'=>$lclass1);
	$this->db->where(array('class_code'=>$lclasscd));
	$this->db->update('landclass_code',$data);
	$arows=$this->db->affected_rows();
	return $arows;

}



}
?>