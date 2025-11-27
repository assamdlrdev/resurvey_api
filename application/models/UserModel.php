<?php

class UserModel extends CI_Model
{
    public static $DEO_CODE = '00';
    public static $ADMIN_CODE = '1';//district admin
    public static $SUPERADMIN_CODE = '2';
    public static $LM_CODE = '3'; //LRA
    public static $CO_CODE = '4';
    public static $SK_CODE = '5';//Lrs
    public static $ADC_CODE = '6';
    public static $DC_CODE = '7';
    public static $SDO_CODE = '8';
    public static $GUEST_CODE = '9';
    public static $SUPERVISOR_CODE = '10';
    public static $SURVEYOR_CODE = '11';
    public static $SPMU_CODE = '12';
    public static $SURVEY_SUPER_ADMIN_CODE = '13';
    public static $SURVEY_GIS_ASSISTANT_CODE = '14';//circle
    public static $STATE_GIS_CODE = '15';// state

    public static $USER_ACTIVITY_LOGIN = 'LOGIN';
    public static $USER_ACTIVITY_PW_CHANGED = 'PASSWORD_UPDATED';
    public static $USER_ACTIVITY_MOBILE_CHANGED = 'MOBILE_NUMBER_UPDATED';

    public function getRoleCodeFromDharCode($user_code)
    {
        $code = '';
        switch ($user_code) {
            case 'DEO':
                $code = self::$DEO_CODE;
                break;
            case 'LM':
                $code = self::$LM_CODE;
                break;
            case 'CO':
                $code = self::$CO_CODE;
                break;
            case 'SK':
                $code = self::$SK_CODE;
                break;
            case 'ADC':
                $code = self::$ADC_CODE;
                break;
            case 'DC':
                $code = self::$DC_CODE;
                break;
            case 'SDO':
                $code = self::$SDO_CODE;
                break;
            case 'SPVR':
                $code = self::$SUPERVISOR_CODE;
                break;
            case 'SVR':
                $code = self::$SURVEYOR_CODE;
                break;
            case 'SPMU':
                $code = self::$SPMU_CODE;
                break;
            case 'ADMIN':
                $code = self::$ADMIN_CODE;
                break;
            case 'SADM':
                $code = self::$SUPERADMIN_CODE;
                break;
            case 'GUEST':
                $code = self::$GUEST_CODE;
                break;
            case 'SSADM':
                $code = self::$SURVEY_SUPER_ADMIN_CODE;
                break;
            case 'GISA':
                $code = self::$SURVEY_GIS_ASSISTANT_CODE;
                break;

            default:
                $code = '';
                break;
        }
        return $code;
    }
    public function getRoleNameFromCode($code)
    {
        $name = '';
        switch ($code) {
            case 0:
                $name = 'DEO';
                break;
            case 1:
                $name = 'ADM';
                break;
            case 3:
                $name = 'LM';
                break;
            case 4:
                $name = 'CO';
                break;
            case 5:
                $name = 'SK';
                break;
            case 6:
                $name = 'ADC';
                break;
            case 7:
                $name = 'DC';
                break;
            case 8:
                $name = 'SDO';
                break;
            case 10:
                $name = 'SPVR';
                break;
            case 11:
                $name = 'SVR';
                break;
            case 12:
                $name = 'SPMU';
                break;
            case 13:
                $name = 'SSADM';
                break;
            case 14:
                $name = 'GISA';
                break;
            case 2:
                $name = 'SADM';
                break;
            case 9:
                $name = 'GUEST';
                break;
            default:
                $name = '';
                break;
        }
        return $name;
    }
    public function districts()
    {
        return $this->db->get_where('location', array('dist_code !=' => '00', 'subdiv_code' => '00', 'cir_code' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->result_array();
    }
    public function insertLM($form_data)
    {
        $user_code = $this->insertLmCode($form_data);
        if ($user_code) {
            return $this->insertDtUser($form_data, $user_code);
        } else {
            return false;
        }
    }
    public function insertNoneLm($form_data)
    {
        $user_code = $this->insertUser($form_data);
        if ($user_code) {
            return $this->insertDtUser($form_data, $user_code);
        } else {
            return false;
        }
    }
    public function updateLM($form_data)
    {
        $serial_no = $form_data['serial_no'];
        $db = $this->UserModel->connectLocmaster();
        try {
            $dt_user = $db->query("SELECT * FROM dataentryusers WHERE serial_no='$serial_no'")->row();
            if ($dt_user) {
                $user_code = $dt_user->user_code;
                $dist_code = $dt_user->dist_code;
                $subdiv_code = $dt_user->subdiv_code;
                $cir_code = $dt_user->cir_code;
                $mouza_pargona_code = $dt_user->mouza_pargona_code;
                $lot_no = $dt_user->lot_no;
                $user = $this->db->query("SELECT * FROM lm_code WHERE dist_code='$dist_code' AND subdiv_code='$subdiv_code' AND cir_code='$cir_code' AND mouza_pargona_code='$mouza_pargona_code' AND lot_no='$lot_no' AND lm_code='$user_code'")->row();

                if ($user) {
                    $this->db->set([
                        'lm_name' => $form_data['name'],
                        'phone_no' => $form_data['phone_no'],
                        'dt_from' => $form_data['date_of_joining'],
                    ]);
                    $this->db->where('dist_code', $dist_code);
                    $this->db->where('subdiv_code', $subdiv_code);
                    $this->db->where('cir_code', $cir_code);
                    $this->db->where('mouza_pargona_code', $mouza_pargona_code);
                    $this->db->where('lot_no', $lot_no);
                    $this->db->where('lm_code', $user_code);
                    $this->db->update('lm_code');
                } else {
                    $user_insert_status = $this->insertLmCode($form_data);
                }
                if ($form_data['password']) {
                    $db = $this->connectLocmaster();
                    $db->set('password', sha1($form_data['password']));
                    $db->where('serial_no', $serial_no);
                    $db->update('dataentryusers');
                }
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    public function updateNoneLm($form_data)
    {
        $serial_no = $form_data['serial_no'];
        $db = $this->UserModel->connectLocmaster();
        try {
            $dt_user = $db->query("SELECT * FROM dataentryusers WHERE serial_no='$serial_no'")->row();
            if ($dt_user) {
                $user_code = $dt_user->user_code;
                $dist_code = $dt_user->dist_code;
                $subdiv_code = $dt_user->subdiv_code;
                $cir_code = $dt_user->cir_code;

                $user = $this->db->query("SELECT * FROM users WHERE dist_code='$dist_code' AND subdiv_code='$subdiv_code' AND cir_code='$cir_code' AND user_code='$user_code'")->row();

                if ($user) {
                    $this->db->set([
                        'username' => $form_data['name'],
                        'phone_no' => $form_data['phone_no'],
                        'date_from' => $form_data['date_of_joining'],
                    ]);
                    $this->db->where('dist_code', $dist_code);
                    $this->db->where('subdiv_code', $subdiv_code);
                    $this->db->where('cir_code', $cir_code);
                    $this->db->where('user_code', $user_code);
                    $this->db->update('users');
                } else {
                    $user_insert_status = $this->insertUser($form_data);
                }
                if ($form_data['password']) {
                    $db = $this->connectLocmaster();
                    $db->set('password', sha1($form_data['password']));
                    $db->where('serial_no', $serial_no);
                    $db->update('dataentryusers');
                }
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    public function insertLmCode($form_data)
    {
        $dist_code = $form_data['dist_code'];
        $subdiv_code = $form_data['subdiv_code'];
        $circle_code = $form_data['cir_code'];
        $mouza_pargona_code = $form_data['mouza_pargona_code'];
        $sk_code = $form_data['sk_name'];

        for ($i = 1; $i < 9999; $i++) {
            $lm_code = "M" . +$i;
            $existance_lm = $this->db->query("select count(lm_code) as d from lm_code where dist_code = '$dist_code' and subdiv_code = '$subdiv_code' and cir_code = '$circle_code' and mouza_pargona_code = '$mouza_pargona_code' and lm_code = '$lm_code'")->row()->d;
            if ($existance_lm == '0') {
                break;
            }
        }

        $user_code_for_lm = $lm_code;
        $this->db->trans_start();
        $this->db->insert('lm_code', [
            'dist_code' => $form_data['dist_code'],
            'subdiv_code' => $form_data['subdiv_code'] ? $form_data['subdiv_code'] : '00',
            'cir_code' => $form_data['cir_code'] ? $form_data['cir_code'] : '00',
            'mouza_pargona_code' => $form_data['mouza_pargona_code'],
            'lot_no' => $form_data['lot_no'],
            'lm_name' => $form_data['name'],
            'lm_code' => $user_code_for_lm,
            'status' => $form_data['status'],
            'phone_no' => $form_data['phone_no'],
            'corres_sk_code' => $sk_code,
            'dt_from' => $form_data['date_of_joining'],
        ]);
        $this->db->trans_complete();
        if ($this->db->trans_status()) {
            return $user_code_for_lm;
        } else {
            return false;
        }
    }
    public function insertUser($form_data)
    {
        $dist_code = $form_data['dist_code'];
        for ($i = 1; $i < 9999; $i++) {
            $user_code = $form_data['role'] . +$i;
            $existance = $this->db->query("select count(user_code) as d from users where dist_code = '$dist_code' and user_code = '$user_code'")->row()->d;
            if ($existance == '0') {
                break;
            }
        }
        $this->db->trans_start();
        $this->db->insert('users', [
            'dist_code' => $form_data['dist_code'],
            'subdiv_code' => $form_data['subdiv_code'] ? $form_data['subdiv_code'] : '00',
            'cir_code' => $form_data['cir_code'] ? $form_data['cir_code'] : '00',
            'username' => $form_data['name'],
            'user_code' => $user_code,
            'user_desig_code' => $form_data['role'],
            'status' => $form_data['status'],
            'phone_no' => $form_data['phone_no'],
            'date_from' => $form_data['date_of_joining'],
        ]);
        $this->db->trans_complete();
        if ($this->db->trans_status()) {
            return $user_code;
        } else {
            return false;
        }
    }
    public function insertDtUser($form_data, $user_code)
    {
        $db = $this->connectLocmaster();
        $db->trans_start();
        $db->insert('dataentryusers', [
            'dist_code' => $form_data['dist_code'],
            'subdiv_code' => $form_data['subdiv_code'] ? $form_data['subdiv_code'] : '00',
            'cir_code' => $form_data['cir_code'] ? $form_data['cir_code'] : '00',
            'mouza_pargona_code' => $form_data['mouza_pargona_code'] ? $form_data['mouza_pargona_code'] : '00',
            'lot_no' => $form_data['lot_no'] ? $form_data['lot_no'] : '00',
            'user_role' => $this->getRoleCode($form_data['role']),
            'user_status' => $form_data['status'],
            'date_of_creation' => date("Y-m-d | h:i:sa"),
            'username' => $form_data['username'],
            'password' => sha1($form_data['password']),
            'user_code' => $user_code,
        ]);
        $db->trans_complete();
        return $db->trans_status();
    }
    public function generateLmUserCode($form_data)
    {
        $code = 'LM' . now();
        $query = $this->db->get_where('lm_code', array('lmuser' => $code));
        if ($query->num_rows() > 0) {
            $this->generateLmUserCode($form_data);
        }
        return $code;
    }
    public function connectLocmaster()
    {
        $CI = &get_instance();
        $db = $CI->load->database('default', true);
        return $db;
    }
    public function isUserNameExists($username)
    {
        $db = $this->connectLocmaster();
        $query = $db->get_where('dataentryusers', array('username' => $username));
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function getDesignationName($role_code)
    {
        $user_desig_code = $this->getRoleNameFromCode($role_code);
        $designation = $this->db->query("select * from master_user_designation where user_desig_code = '$user_desig_code'")->row();
        if ($designation) {
            return $designation->user_desig_as;
        } else {
            return null;
        }
    }
    public function getPrivilageCodeFromRoleCode($role_code)
    {
        $user_desig_code = $this->getRoleNameFromCode($role_code);
        $designation = $this->db->query("select * from master_user_designation where user_desig_code = '$user_desig_code'")->row();
        if ($designation) {
            return $designation->privilege;
        } else {
            return null;
        }
    }
    public function getPrivilageCodeFromRoleName($role_code)
    {
        $designation = $this->db->query("select * from master_user_designation where user_desig_code = '$role_code'")->row();
        if ($designation) {
            return $designation->privilege;
        } else {
            return null;
        }
    }
    public function getUser($dist_code, $subdiv_code, $cir_code, $username)
    {
        $CI = &get_instance();
        $db = $CI->load->database('default', true);
        return $db->get_where('dataentryusers', ['dist_code' => $dist_code, 'subdiv_code' => $subdiv_code, ' cir_code' => $cir_code, 'username' => $username])->row();
    }

    public function getSKUsers($dist, $sub, $circle)
    {
        return $this->db->get_where('users', array(
            'dist_code' => $dist,
            'subdiv_code' => $sub,
            'cir_code' => $circle,
            'user_desig_code' => 'SK',
        ))->result_array();
    }

    public function getLMs($district, $subDivision, $circle, $mouza, $lot)
    {
        //dd($this->db->database);
        $queryResult = $this->db->get_where('lm_code', array(
            'dist_code' => $district,
            'subdiv_code' => $subDivision,
            'cir_code' => $circle,
            'mouza_pargona_code' => $mouza,
            'lot_no' => $lot,
        ))->result_array();
        if ($queryResult) {
            return $queryResult;
        } else {
            return false;
        }
    }

    public function checkIfLMUserExist($data)
    {
        $query = $this->db->get_where('lm_code', array(
            'dist_code' => $data['dist_code'],
            'subdiv_code' => $data['subdiv_code'],
            'cir_code' => $data['cir_code'],
            'mouza_pargona_code' => $data['mouza_pargona_code'],
            'lot_no' => $data['lot_no'],
            'lm_code' => $data['lm_code'],
        ));

        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function getLMUserDetail($district, $subDivision, $circle, $mouza, $lot, $lmCode)
    {
        $user = $this->db->get_where('lm_code', array(
            'dist_code' => $district,
            'subdiv_code' => $subDivision,
            'cir_code' => $circle,
            'mouza_pargona_code' => $mouza,
            'lot_no' => $lot,
            'lm_code' => $lmCode,
        ))->row();
        if ($user) {
            return $user;
        } else {
            return false;
        }
    }

    public function getSurveyUsers()
    {
        $users = $this->db->query("SELECT * FROM dataentryusers WHERE (user_role='10' OR user_role='11' OR user_role='12' OR user_role='13' OR user_role='14') AND user_status='E' ORDER BY date_of_creation DESC")->result();

        foreach ($users as $user) {
            if ($user->user_role == '13') {
                $user->role_name = 'SuperAdmin';
            } else if ($user->user_role == '10') {
                $user->role_name = 'Supervisor';
            } else if ($user->user_role == '11') {
                $user->role_name = 'Surveyor';
            } else if ($user->user_role == '12') {
                $user->role_name = 'SPMU';
            } else if ($user->user_role == '14') {
                $user->role_name = 'GIS Assistant';
            }
        }

        return $users;
    }


    
}
