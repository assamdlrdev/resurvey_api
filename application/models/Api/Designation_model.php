<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Designation_model extends CI_Model
{
    protected $table = 'survey_designations'; // change if your table name differs

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get designations by array of codes
     * @param array $codes  e.g. ['01','02']
     * @return array of arrays
     */
    public function get_by_codes(array $codes = [])
    {
        if (empty($codes)) return [];

        $this->db->select('designation_code, designation_name');
        $this->db->from($this->table);
        $this->db->where_in('designation_code', $codes);
        $this->db->order_by('designation_code', 'asc');
        $q = $this->db->get();

        return $q ? $q->result_array() : [];
    }

    /**
     * (optional) Get all designations
     */
    public function get_all()
    {
        $this->db->select('designation_code, designation_name');
        $this->db->from($this->table);
        $this->db->order_by('designation_code', 'asc');
        $q = $this->db->get();
        return $q ? $q->result_array() : [];
    }

    public function get_by_code($code)
    {
        if (empty($code)) return '';

        $this->db->select('designation_name');
        $this->db->from($this->table);
        $this->db->where('designation_code', $code);
        $this->db->limit(1);

        $q = $this->db->get();

        if ($q && $q->num_rows() > 0) {
            return $q->row()->designation_name;  // ← return only name
        }

        return '';
    }

}
