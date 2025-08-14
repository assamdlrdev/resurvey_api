<?php 
class TransactionModel extends CI_Model {

    var $base_query;
    var $dist_code;
    var $subdiv_code;
    var $cir_code;

    public function __construct() {
        parent::__construct();
        $location = $this->utilityclass->getLocationFromSession();
        $this->dist_code = $location['dist_code'];
        $this->subdiv_code = $location['subdiv_code'];
        $this->cir_code = $location['cir_code'];
		$db=  $this->session->userdata('db');
        $define_date = define_date;
        $year_no = year_no;
        $this->base_query = "dist_code = '$this->dist_code' and subdiv_code ='$this->subdiv_code'  and cir_code ='$this->cir_code'   ";
    }

        //this function is created for finding all datas i.e states data from state_code
    public function find_all_against_id($tablename, $key_field_name, $keyvalue) {
        $sql = "select * from $tablename where $key_field_name=?";
        $query = $this->db->query($sql, array($keyvalue));
        return $query->result_array();
    }

    public function find_field_against_id($tablename, $key_field_name, $keyvalue) {
        $sql = "select * from $tablename where $key_field_name=?";
        $query = $this->db->query($sql, array($keyvalue));
        return $query->row();
    }


    //this common function is created for update the datas against the key value
    public function update($tablename, $keyfield, $data, $keyvalue) {
        $this->db->where($keyfield, $keyvalue);
        $this->db->update($tablename, $data);
        return $this->db->affected_rows();
    }

    public function update_multiple_condition($tablename, $where, $data) {
        $this->db->where($where);
        $this->db->update($tablename, $data);
        return $this->db->affected_rows();
    }

    //this common function is created for delete the datas against the key value
    public function delete($tablename, $keyfield, $keyvalue) {
        $this->db->where($keyfield, $keyvalue);
        $this->db->delete($tablename);
        return $this->db->affected_rows();
    }

    public function delete_by_multiple_condition($tablename, $where) {
        $this->db->where($where);
        $this->db->delete($tablename);
        return $this->db->affected_rows();
    }

    //this common function is created for insert the datas into table
    public function insert($tablename, $data) {
        
        $this->db->insert($tablename, $data);

        return ($this->db->affected_rows() > 0) ? true : false;
    }

    public function get_all_records_condition($tablename, $where) {
        $this->db->select('');
        $this->db->from($tablename);
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result();
    }
}
?>