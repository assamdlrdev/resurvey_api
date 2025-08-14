<?php
class RemarkModel extends CI_Model {

   
  public function getSerial($dist,$sub,$circle,$mza,$lot,$vill,$dag) {
    $this->db->select_max('rmk_type_hist_no');
    $this->db->where(array('dist_code'=>$dist,'subdiv_code ='=>$sub,'cir_code='=>$circle,'mouza_pargona_code='=>$mza,'lot_no='=>$lot,'vill_townprt_code'=>$vill,'dag_no'=>$dag));
    $query=$this->db->get('chitha_rmk_ordbasic');
    $row = $query->row_array();
    $id = $row['rmk_type_hist_no'] + 1;
    return $id;
  }
   public function getSerialCronNo($dist,$sub,$circle,$mza,$lot,$vill,$dag) {
    $this->db->select_max('ord_cron_no');
    $this->db->where(array('dist_code'=>$dist,'subdiv_code ='=>$sub,'cir_code='=>$circle,'mouza_pargona_code='=>$mza,'lot_no='=>$lot,'vill_townprt_code'=>$vill,'dag_no'=>$dag));
    $query=$this->db->get('chitha_rmk_ordbasic');
    $row = $query->row_array();
    $id = $row['ord_cron_no'] + 1;
    return $id;
  }
  
  function add_remarkdetails($details)
    {
    $data = $this->security->xss_clean($details);
    $this->db->insert('chitha_rmk_ordbasic',$details);
    return 1;
    }
  
    function add_Infavorofdetails($details)
    {
    $data = $this->security->xss_clean($details);
    $this->db->insert('chitha_rmk_infavor_of',$details);
    return 1;
    }
    function add_alongwithdetails($details)
    {
    $data = $this->security->xss_clean($details);
    $this->db->insert('chitha_rmk_alongwith',$details);
    return 1;
    }
    function add_inplaceofdetails($details)
    {
    $data = $this->security->xss_clean($details);
    $this->db->insert('chitha_rmk_inplace_of',$details);
    return 1;
    }
    function add_onbehalfofdetails($details)
    {
    $data = $this->security->xss_clean($details);
    $this->db->insert('chitha_rmk_onbehalf',$details);
    return 1;
    }
   public function getOrderType() {

    return $this->db->get('master_office_mut_type')->result_array();
    }
 
   public function MandalNamedetails($dist,$sub,$circle,$mza,$lot) {
   return $this->db->get_where('lm_code',array('dist_code'=>$dist,'subdiv_code ='=>$sub,'cir_code='=>$circle,'mouza_pargona_code='=>$mza,'lot_no='=>$lot))->result_array();
  }
   public function SKNamedetails($dist,$sub,$circle) {
   return $this->db->get_where('users',array('dist_code'=>$dist,'subdiv_code ='=>$sub,'cir_code='=>$circle,'user_desig_code='=> 'SK'))->result_array();
  }
   public function CONamedetails($dist,$sub,$circle) {
   return $this->db->get_where('users',array('dist_code'=>$dist,'subdiv_code ='=>$sub,'cir_code='=>$circle,'user_desig_code='=> 'CO'))->result_array();
  }
   public function getPattaType() {

    return $this->db->get_where('patta_code',array('type_code!='=>'0000'))->result_array();
    }
    public function getRelation() {

    return $this->db->get('master_guard_rel')->result_array();
    }
    
public function getByRight() {

    return $this->db->get_where('nature_trans_code',array('trans_code!='=>'00'))->result_array();
    }  

    
//  function add_basicdetails($details)
//    {
//    $data = $this->security->xss_clean($details);
//    $this->db->insert('chitha_basic',$details);
//    return 1;
//    }
//    
//    public function checkFieldExist($tablename, $search_field_name, $where) {
//        $this->db->select($search_field_name);
//        $this->db->from($tablename);
//        $this->db->where($where);
//        $query = $this->db->get();
//        $name = $query->row();
//        if (isset($name)) {
//            $name = $name->$search_field_name;
//            return $name;
//        } else {
//            return NULL;
//        }
//    }
//    public function fetchDataCondition($tablename, $search_field_name, $where) {
//        $this->db->select($search_field_name);
//        $this->db->from($tablename);
//        $this->db->where($where);
//        $query = $this->db->get();
//        return $query->result_array();
//    }
//
//   function add_pattadars($pattadar)
//    {
//    $data = $this->security->xss_clean($pattadar);
//    $this->db->insert('chitha_pattadar',$pattadar);
//    return 1;
//    }
//
//    function add_dagpattadars($dagpattadar)
//    {
//    $data = $this->security->xss_clean($dagpattadar);
//    $this->db->insert('chitha_dag_pattadar',$dagpattadar);
//    return 1;
//    }
//
//
//  public function getByRight() {

  //  return $this->db->get_where('nature_trans_code',array('trans_code!='=>'00'))->result_array();
 //   }
//  
//  
//  
//  
//  
// public function districtdetails() {
//   
//   
//    return $this->db->get_where('location',array('subdiv_code'=>'00','cir_code'=>'00','mouza_pargona_code'=>'00','lot_no'=>'00','vill_townprt_code'=>'00000'))->result_array();
//  
//    
//   
// }
// public function subdivisiondetails($dist) {
//   
//   
//   return $this->db->get_where('location',array('dist_code'=>$dist,'subdiv_code !='=>'00','cir_code'=>'00','mouza_pargona_code'=>'00','lot_no'=>'00','vill_townprt_code'=>'00000'))->result_array();
// 
//    
//   
// }
// public function circledetails($dist,$sub) {
//   
//   
//   return $this->db->get_where('location',array('dist_code'=>$dist,'subdiv_code ='=>$sub,'cir_code!='=>'00','mouza_pargona_code'=>'00','lot_no'=>'00','vill_townprt_code'=>'00000'))->result_array();
// 
//    
//   
// }
//  public function mouzadetails($dist,$sub,$circle) {
//   
//   
//   return $this->db->get_where('location',array('dist_code'=>$dist,'subdiv_code ='=>$sub,'cir_code='=>$circle,'mouza_pargona_code!='=>'00','lot_no'=>'00','vill_townprt_code'=>'00000'))->result_array();
// 
//    
//   
// }
//   public function lotdetails($dist,$sub,$circle,$mza) {
//   
//   
//   return $this->db->get_where('location',array('dist_code'=>$dist,'subdiv_code ='=>$sub,'cir_code='=>$circle,'mouza_pargona_code='=>$mza,'lot_no!='=>'00','vill_townprt_code'=>'00000'))->result_array();
// 
//    
//   
// }
// 
//    public function villagedetails($dist,$sub,$circle,$mza,$lot) {
//   
//   
//   return $this->db->get_where('location',array('dist_code'=>$dist,'subdiv_code ='=>$sub,'cir_code='=>$circle,'mouza_pargona_code='=>$mza,'lot_no='=>$lot,'vill_townprt_code!='=>'00000'))->result_array();
// 
//    
//   
// }
//
//  public function getPattaType() {
//
//    return $this->db->get_where('patta_code',array('type_code!='=>'0000'))->result();
//    }
//
//  public function getLandclasscode() {
//
//    return $this->db->get_where('landclass_code',array('class_code!='=>'0000'))->result();
//    }
//
//    // public function getPattaname($patta_type_code){
//    //   return $this->db->get_where('patta_code',array('type_code ='=>$patta_type_code))->row();
//    // }
//
//     public function getPattaname($patta_type_code) {
//        $CI = & get_instance();
//        //$ds=$CI->session->userdata['db'];
//        $query = "Select patta_type from patta_code where type_code='$patta_type_code'";
//        return $CI->db->query($query)->row()->patta_type;
//    }
//
//    function geneartePdarid(){
//       $pdar = $this->db->query("select nextval('pdar_id') as count ")->row()->count;
//            return $pdar;
//         }
//
//      function nextPattadrID($where){
//        $this->db->select('pdar_id');
//        $this->db->from('chitha_pattadar');
//        $this->db->where($where);
//        $this->db->order_by('pdar_id','desc');
//        $query = $this->db->get();
//        $name = $query->row();
//        if (isset($name)) {
//            $name = $name->pdar_id;
//            return $name;
//        } else {
//            return NULL;
//        }
//           
//      }

 
        
}
