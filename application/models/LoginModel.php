<?php
class LoginModel extends CI_Model
{


    public function check_user($details)
    {
        $username = $this->security->xss_clean($details);
        $this->db->from('dataentryusers');
        $this->db->where('dataentryusers.username=', $username);
        return $this->db->count_all_results();
    }
    public function add_user($details)
    {
        $data = $this->security->xss_clean($details);
        $this->db->insert('dataentryusers', $details);
        return 1;
    }

    public function updateUser($conditions, $data, $connection){
        $status = $connection->where($conditions)->update('dataentryusers', $data);

        return $status;
    }

    public function ValidateApiUser($username, $password) {
        // $salt = $this->session->userdata('salt');
        $this->db->select('*');
        $this->db->from('dataentryusers');
        $this->db->where(array('username' => $username));
        $query = $this->db->get();
        $name = $query->row_array();
        if ($name != null) {
            if ($name['username'] == $username && sha1($password) == $name['password']) {
                return $name;
            }
        }
    }

    public function ValidateUser($username, $password)
    {
        $salt = $this->session->userdata('salt');
        $this->db->select('*');
        $this->db->from('dataentryusers');
        $this->db->where(array('username' => $username));
        $query = $this->db->get();
        $name = $query->row_array();
        if ($name != null) {
            if ($name['username'] == $username && $password == sha1($salt . $name['password'])) {
                return $name;
            }
        }
    }
    public function districtdetails($dist)
    {
        return $this->db->get_where('location', array('dist_code' => $dist, 'subdiv_code' => '00', 'cir_code' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->result_array();
    }
    public function districtdetailsall()
    {
        return $this->db->get_where('location', array('subdiv_code' => '00', 'cir_code' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->result_array();
    }
    public function subdivisiondetailsall($dist)
    {
        return $this->db->get_where('location', array('dist_code' => $dist, 'subdiv_code !=' => '00', 'cir_code' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->result_array();
    }
    public function circledetailsall($dist, $sub)
    {
        return $this->db->get_where('location', array('dist_code' => $dist, 'subdiv_code =' => $sub, 'cir_code!=' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->result_array();
    }
    public function mouzadetailsall($dist, $sub, $cir)
    {
        return $this->db->get_where('location', array('dist_code' => $dist, 'subdiv_code' => $sub, 'cir_code' => $cir, 'mouza_pargona_code!=' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->result_array();
    }
    public function lotdetailsall($dist, $sub, $cir, $mouza)
    {
        return $this->db->get_where('location', array('dist_code' => $dist, 'subdiv_code =' => $sub, 'cir_code=' => $cir, 'mouza_pargona_code=' => $mouza, 'lot_no!=' => '00', 'vill_townprt_code' => '00000'))->result_array();
    }

    // Masud Reza 30/05/2022
    // get User Details
    public function getUserDetails($userId)
    {
        $this->db->select('*');
        $this->db->from('dataentryusers');
        $this->db->where('username', $userId);
        $userData = $this->db->get()->row();

        return $userData;
    }

    // user old password validate
    public function validateOldPassword($userId, $hashedpwd)
    {
        return $this->db->select()
            ->where('username', $userId)
            ->where('password', $hashedpwd)
            ->get('dataentryusers')
            ->num_rows();
    }

    // update password
    public function updateUserPassword($userId, $data)
    {
        $this->db->trans_start();
        $this->db->where('username', $userId);
        $this->db->update('dataentryusers', $data);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    public function ValidateSingleSignUser($login_user )
    {
        $this->db->select('*');
        $this->db->from('loginuser_table');

        $this->db->where('use_name', $login_user['use_name'])->where('dist_code', $login_user['dist_code'])->where('subdiv_code',$login_user['subdiv_code'])
        ->where('cir_code',$login_user['cir_code'])->where('mouza_pargona_code',$login_user['mouza_pargona_code'])->where('lot_no',$login_user['lot_no'])->where('user_code', $login_user['user_code']);
        $query = $this->db->get();
        $user = $query->row();

       if($user){
        return $user;
       }
        return null;
    }

    public function UserActivity($userdetails, $description = '') {
       
        $insertArr = [
            'username'=>$userdetails['username'],
            'action'=>$userdetails['action'],
            'created_at'=>date('Y-m-d H:i:s'),
            'ip'=>$_SERVER['REMOTE_ADDR'],
            'dist_code'=>$userdetails['dist_code'],
            'subdiv_code'=>(isset($userdetails['subdiv_code'])) ? $userdetails['subdiv_code'] : '00',
            'cir_code'=>(isset($userdetails['cir_code'])) ? $userdetails['cir_code'] : '00',
            'mouza_pargona_code'=>(isset($userdetails['mouza_pargona_code'])) ? $userdetails['mouza_pargona_code'] : '00',
            'lot_no'=>(isset($userdetails['lot_no'])) ? $userdetails['lot_no'] : '00',
            'user_type'=>(isset($userdetails['user_role'])) ? $userdetails['user_role'] : '',
            'description'=>$description
        ];
        return $this->db->insert('user_activity', $insertArr);
    }

}
