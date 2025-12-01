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

    /**
     * Get paginated users
     * @param int $limit
     * @param int $offset
     * @param array $filters (supports 'search')
     * @param string $sort_by
     * @param string $sort_dir
     * @return array of result objects
     */
    public function get_users_paginated($limit, $offset, $filters = [], $sort_by = 'id', $sort_dir = 'asc',$user_type)
    {
        $this->db->select('serial_no, username, name, email, mobile_no, user_role, dist_code, subdiv_code, cir_code,designation');
        $this->db->from($this->table);

        // ROLE FILTER
        $allowed_roles   =   [];
        if($user_type=='1' || $user_type=='2'){
            $allowed_roles = ['00', '9', '10', '11', '14','15'];
        }else if($user_type=='10'){
            $allowed_roles = ['11', '14','15'];
        }
        $this->db->where_in('user_role', $allowed_roles);

        if (!empty($filters['search'])) {
            // normalize and split into words
            $search = trim($filters['search']);
            $search = preg_replace('/\s+/', ' ', $search);        // collapse spaces
            $search = mb_strtolower($search);                    // lowercase (multibyte-safe)
            $words = explode(' ', $search);

            $this->db->group_start(); // outer group for all words (AND)
            foreach ($words as $idx => $w) {
                $w = $this->db->escape_like_str($w);            // escape wildcards
                // For each word, require it appears in ANY of these columns (OR)
                $this->db->group_start();
                $this->db->like('LOWER(username)', $w);
                $this->db->or_like('LOWER(name)', $w);
                $this->db->or_like('LOWER(email)', $w);
                $this->db->or_like('LOWER(mobile_no)', $w);
                $this->db->group_end();
                // Because outer group is AND, each word must match somewhere
            }
            $this->db->group_end();
        }

        // safe order by: column names validated in controller
        $this->db->order_by($sort_by, $sort_dir);
        $this->db->limit((int)$limit, (int)$offset);

        $q = $this->db->get();
        return $q->result();
    }

    /**
     * Count users with same filters
     */
    public function count_users($filters = [],$user_type)
    {
        $this->db->from($this->table);
        // ROLE FILTER
        $allowed_roles   =   [];
        if($user_type=='1' || $user_type=='2'){
            $allowed_roles = ['00', '9', '10', '11', '14'];
        }else if($user_type=='10'){
            $allowed_roles = ['11', '14'];
        }
        $this->db->where_in('user_role', $allowed_roles);

        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('username', $filters['search']);
            $this->db->or_like('name', $filters['search']);
            $this->db->or_like('email', $filters['search']);
            $this->db->group_end();
        }

        return (int) $this->db->count_all_results();
    }

    public function get_user_by_id($id)
    {
        $this->db->select('serial_no, username, name, email, mobile_no, user_role, dist_code, subdiv_code, cir_code','designation');
        $this->db->from($this->table);
        $this->db->where('serial_no', (int)$id);
        $this->db->limit(1);
        $q = $this->db->get();

        if ($q && $q->num_rows() > 0) {
            return $q->row();
        }
        return null;
    }


    /**
     * Update user row by id
     */
    public function update_user($id, array $data)
    {
        if (empty($data)) return false;
        $this->db->where('serial_no', (int)$id);
        $this->db->update($this->table, $data);
        return $this->db->affected_rows() >= 0; // >=0 because update with same data returns 0
    }

    /**
     * Check email exists excluding a specific user id
     */
    public function email_exists_except($email, $except_id)
    {
        $q = $this->db->from($this->table)
                    ->where('email', $email)
                    ->where('serial_no !=', (int)$except_id)
                    ->limit(1)
                    ->get();
        return $q->num_rows() > 0;
    }

    /**
     * Check phone exists excluding a specific user id
     */
    public function phone_exists_except($phone, $except_id)
    {
        $q = $this->db->from($this->table)
                    ->where('mobile_no', $phone)
                    ->where('serial_no !=', (int)$except_id)
                    ->limit(1)
                    ->get();
        return $q->num_rows() > 0;
    }


}
