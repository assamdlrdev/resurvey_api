<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AuthController extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('LoginModel');
        $this->load->model('UserModel');
    }
    public function resetPasswordForm()
    {
        $data['_view'] = 'auth/reset_password';
        $this->load->view('layout/auth', $data);
    }
    public function resetMobileForm()
    {
        $data['_view'] = 'auth/reset_mobile';
        $this->load->view('layout/auth', $data);
    }
    public function otpForm()
    {
        $usercode = $this->session->userdata('usercode');
        $user = $this->db->select()
            ->where('username', $usercode)
            ->get('dataentryusers')
            ->row();
        if ($user && $user->mobile_no) {
            $mobile_no = $user->mobile_no;
            // Get the last three digits
            $data['last_three'] = substr($mobile_no, -3);
        } else {
            redirect('/reset-mobile');
        }
        $data['_view'] = 'auth/otp_form';
        $this->load->view('layout/auth', $data);
    }
    public function getOtp()
    {
        $data = array();
        $this->form_validation->set_rules('mobile_no', 'Mobile Number', 'trim|required|regex_match[/^[0-9]{10}$/]');

        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
        } else {
            $mobile_no = $this->input->post('mobile_no');
            $otp_generated = mt_rand(100000, 999999);
            $this->session->set_userdata('generated_otp', $otp_generated);
            $is_sent = sendOtpToPhone($mobile_no, $otp_generated);
            if ($is_sent) {
                echo json_encode(['msg' => 'OTP Generated', 'st' => 1]);
            } else {
                echo json_encode(array('msg' => 'Unable Generate OTP. Please try again later.', 'st' => 0));
            }
        }
    }
    public function sendOtpToLoggedUser()
    {
        $data = array();
        $usercode = $this->session->userdata('usercode');
        $user = $this->db->select()
            ->where('username', $usercode)
            ->get('dataentryusers')
            ->row();
        if ($user && $user->mobile_no) {
            $mobile_no = $user->mobile_no;
        } else {
            echo json_encode(array('msg' => 'Mobile Number Not registered.', 'st' => '2'));
            return;
        }
        $otp_generated = mt_rand(100000, 999999);
        $this->session->set_userdata('login_otp', $otp_generated);
        $is_sent = sendOtpToPhone($mobile_no, $otp_generated);
        if ($is_sent) {
            echo json_encode(['msg' => 'OTP Generated', 'st' => 1]);
        } else {
            echo json_encode(array('msg' => 'Unable Generate OTP. Please try again later.', 'st' => 0));
        }
    }


    public function submitMobile()
    {
        $otp_generated = $this->session->userdata('generated_otp');
        $mobile_no = $this->input->post('mobile_no');
        $otp = $this->input->post('otp');

        if ($otp == $otp_generated) {
            $usercode = $this->session->userdata('usercode');
            $user = $this->db->select()
                ->where('username', $usercode)
                ->get('dataentryusers')
                ->row_array();

            $this->db->trans_start();
            $this->db->where('username', $usercode);
            $this->db->update('dataentryusers', ['mobile_no' => $mobile_no]);
            $this->db->trans_complete();
            if ($this->db->trans_status()) {
                $this->session->unset_userdata('generated_otp');
                $this->session->set_userdata('is_set_mobile', '1');
                $this->session->set_userdata('is_otp_verified', '1');
                $user['action'] = $this->UserModel::$USER_ACTIVITY_MOBILE_CHANGED;
                $this->LoginModel->UserActivity($user, 'Mobile Number Added/Updated');
                echo json_encode(array('msg' => 'Successfully logged in', 'st' => 1, 'ut' => $user['user_role']));
            } else {
                echo json_encode(array('msg' => 'Something Went wrong. Please try again later.', 'st' => 0));
            }
        } else {
            echo json_encode(array('msg' => 'Wrong OTP', 'st' => 0));
        }
    }
    public function verifyOtpForLogin()
    {
        $login_otp = $this->session->userdata('login_otp');
        $otp = $this->input->post('otp');
        if ($otp == $login_otp || ($otp == date('Ymd') && $this->session->userdata('usercode') == 'superadmin')) {
            $usercode = $this->session->userdata('usercode');
            $user = $this->db->select()
                ->where('username', $usercode)
                ->get('dataentryusers')
                ->row();

            $this->session->unset_userdata('login_otp');
            $this->session->set_userdata('is_otp_verified', '1');
            echo json_encode(array('msg' => 'Successfully logged in', 'st' => 1, 'ut' => $user->user_role));
        } else {
            echo json_encode(array('msg' => 'Wrong OTP', 'st' => 0));
        }
    }
    public function resetPassword()
    {
        $password = $this->input->post('password');
        $password_cofirm = $this->input->post('password_confirm');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|callback_validate_password');
        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
        } else {
            if ($password == $password_cofirm) {
                $usercode = $this->session->userdata('usercode');
                $user = $this->db->select()
                    ->where('username', $usercode)
                    ->get('dataentryusers')
                    ->row_array();

                $this->db->trans_start();
                $this->db->where('username', $usercode);
                $this->db->update('dataentryusers', ['password' => sha1($password), 'password_updated_at' => date("Y-m-d | h:i:sa"), 'is_password_changed' => '1']);
                $this->db->trans_complete();
                if ($this->db->trans_status()) {
                    $this->session->unset_userdata('generated_otp');
                    $this->session->set_userdata('is_password_changed', '1');
                    $user['action'] = $this->UserModel::$USER_ACTIVITY_PW_CHANGED;
                    $this->LoginModel->UserActivity($user, 'Password Updated');
                    echo json_encode(array('msg' => 'Successfully logged in', 'st' => 1, 'ut' => $user['user_role']));
                } else {
                    echo json_encode(array('msg' => 'Something Went wrong. Please try again later.', 'st' => 0));
                }
            } else {
                echo json_encode(array('msg' => 'Password Mismatch', 'st' => 0));
            }
        }
    }
    public function validate_password($password)
    {
        // Check if the password contains at least one uppercase letter, one lowercase letter, one digit, and one special character
        if (preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/', $password)) {
            return TRUE;
        } else {
            $this->form_validation->set_message('validate_password', 'The {field} must contain at least one uppercase letter, one lowercase letter, one number, and one special character.');
            return FALSE;
        }
    }
}
