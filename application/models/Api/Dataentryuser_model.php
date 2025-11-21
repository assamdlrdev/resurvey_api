<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dataentryuser_model extends CI_Model {
    protected $table = 'dataentryusers';
    public function __construct(){ parent::__construct(); }

    public function insert_user(array $data)
    {
        $this->db->insert($this->table, $data);
        if ($this->db->affected_rows() === 1) {
            return $this->db->insert_id();
        }
        return false;
    }

    public function username_exists($username)
    {
        $q = $this->db->get_where($this->table, ['username' => $username], 1);
        return $q->num_rows() > 0;
    }
}
