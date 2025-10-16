<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class Login extends CI_Controller
{
    use CommonTrait;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('LoginModel');
        $this->load->model('UserModel');
        $this->load->helper('security');
    }
    public function index()
    {
        $data['base'] = $this->config->item('base_url');
        $this->load->helper('captcha');
        $file = $this->session->userdata('filename');
        if ($file && file_exists($file)) {
            unlink('./captcha/' . $file);
        }
        $vals = array(
            'img_path' => './captcha/',
            'img_url' => base_url('captcha'),
            'img_width' => '120',
            'img_height' => 30,
            'expiration' => 7200,
            'word_length' => 5,
            'font_size' => 50,
            'pool' => '123456790',
            'colors' => array(
                'background' => array(255, 255, 255),
                'border' => array(255, 255, 255),
                'text' => array(0, 0, 0),
                'grid' => array(255, 100, 180),
            ),
        );
        try {
            $data['captcha'] = create_captcha($vals);
        } catch (Exception $err) {
            var_dump($err);
        }

        $this->session->set_userdata('captcha_key', $data['captcha']['word']);
        $this->session->set_userdata('filename', $data['captcha']['filename']);
        $this->load->view('login', $data);
    }

    public function superadminindex()
    {
        if ($this->session->userdata('usertype') != '2') {
            $this->logout();
        }
        $data['base'] = $this->config->item('base_url');
        $LoginModel = new LoginModel();
        $data['districts'] = $LoginModel->districtdetailsall();
        $data['_view'] = 'superadminindex';
        $this->load->view('layout/layout', $data);
    }

    public function usercreationIndex() {
        if ($this->session->userdata('usertype') != 13) {
            $this->logout();
        }
        $data['base'] = $this->config->item('base_url');
        $LoginModel = new LoginModel();
        $data['districts'] = $LoginModel->districtdetailsall();
        $data['_view'] = 'survey_users/usercreationindex';
        $this->load->view('layout/layout', $data);
    }

    // public function supervisorIndex() {
    //     if ($this->session->userdata('usertype') != 10) {
    //         $this->logout();
    //     }
    //     $data['base'] = $this->config->item('base_url');
    //     $LoginModel = new LoginModel();
    //     $data['districts'] = $LoginModel->districtdetailsall();
    //     $data['_view'] = 'supervisorDashboard';
    //     $this->load->view('layout/layout', $data);
    // }

    // public function surveyorIndex() {
    //     if ($this->session->userdata('usertype') != 11) {
    //         $this->logout();
    //     }
    //     $data['base'] = $this->config->item('base_url');
    //     $LoginModel = new LoginModel();
    //     $data['districts'] = $LoginModel->districtdetailsall();
    //     $data['_view'] = 'surveyorDashboard';
    //     $this->load->view('layout/layout', $data);
    // }

    public function adminindex()
    {
        if ($this->session->userdata('usertype') != '1') {
            $this->logout();
        }
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $LoginModel = new LoginModel();
        $dist = $this->session->userdata('dcode');
        $data['districts'] = $LoginModel->districtdetails($dist);
        $data['_view'] = 'adminindex';
        $this->load->view('layout/layout', $data);
    }

    public function dashboard()
    {
        $data['_view'] = 'dashboard';
        $this->load->view('layout/layout', $data);
    }

    public function LoginSubmit()
    {
        $data = array();
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim');
        $this->form_validation->set_rules('captcha', 'Captcha', 'trim|required');
        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
        } else {
            $captchakey = $this->session->userdata('captcha_key');
            $username = $this->input->post('username');
            $password = $this->input->post('hashedpwd');

            //            printf('<pre>');
            //            print_r($username);
            //            printf('<br>');
            //            print_r($password);
            //
            //            die();

            $captcha = $this->input->post('captcha');
            unlink('./captcha/' . $this->session->userdata('filename'));
            if ($captchakey == $captcha) {
                $logindetails = false;
                $validateuserdetails = $this->LoginModel->ValidateUser($username, $password);
                if (isset($validateuserdetails) && !empty($validateuserdetails)) {
                    $logindetails = true;
                    $usertype = $validateuserdetails['user_role'];
                    $usercode = $validateuserdetails['username'];
                    $distcode = $validateuserdetails['dist_code'];

                    $subdiv_code = $validateuserdetails['subdiv_code'];
                    $cir_code = $validateuserdetails['cir_code'];
                    $is_password_changed = $validateuserdetails['is_password_changed'];
                    $is_set_mobile = $validateuserdetails['mobile_no'];
                    if (ENABLE_MOBILE_VERIFICATION == '1' && $is_set_mobile) {
                        $this->session->set_userdata('is_set_mobile', '1');
                        $this->session->set_userdata('is_otp_verified', '0');
                        $otp_generated = mt_rand(100000, 999999);
                        $this->session->set_userdata('login_otp', $otp_generated);
                        sendOtpToPhone($validateuserdetails['mobile_no'], $otp_generated);
                    }
                    $this->session->set_userdata('loggedin', true);
                    $this->session->set_userdata('usertype', $usertype);
                    $this->session->set_userdata('usercode', $usercode);
                    $this->session->set_userdata('dcode', $distcode);
                    $this->session->set_userdata('subdiv_code', $subdiv_code);
                    $this->session->set_userdata('cir_code', $cir_code);
                    $this->session->set_userdata('is_password_changed', $is_password_changed ? '1' : false);
                }

                if ($logindetails) {
                    //insert into user_activity
                    $validateuserdetails['action'] = $this->UserModel::$USER_ACTIVITY_LOGIN;
                    $insertStatus = $this->LoginModel->UserActivity($validateuserdetails, 'Login from Chitha Entry Application');
                    echo json_encode(array('msg' => 'Login Successful', 'st' => 1, 'ut' => $usertype));
                } else {
                    echo json_encode(array('msg' => 'Login Failed Wrong Username or Password', 'st' => 0));
                }
            } else {
                echo json_encode(array('msg' => 'Wrong Captcha', 'st' => 0));
            }
        }
    }

    /* public function LoginSubmit() {
    $data=array();
    $this->form_validation->set_rules('username', 'Username', 'trim|required');
    $this->form_validation->set_rules('password', 'Password', 'trim');
    if($this->form_validation->run()==false) {
    $text=str_ireplace('<\/p>','',validation_errors());
    $text=str_ireplace('<p>','',$text);
    $text=str_ireplace('</p>','',$text);
    echo json_encode(array('msg'=>$text, 'st'=>0));

    }
    else {

    $username = $this->input->post('username');
    $password = $this->input->post('hashedpwd');
    $logindetails=FALSE;
    $validateuserdetails = $this->LoginModel->ValidateUser($username,$password);
    if(isset($validateuserdetails) && !empty($validateuserdetails))
    {
    $logindetails=TRUE;
    $usertype=$validateuserdetails['user_role'];
    $usercode=$validateuserdetails['username'];
    $this->session->set_userdata('loggedin', TRUE);
    $this->session->set_userdata('usertype', $usertype);
    $this->session->set_userdata('usercode', $usercode);
    }

    if ($logindetails) {
    echo json_encode(array('msg' => 'Login Successful', 'st' => 1,'ut' => $usertype));
    } else {
    echo json_encode(array('msg' => 'Login Failed Wrong Username or Password', 'st' => 0));
    }
    }

    } */

    public function SuperAdminLoginSubmit()
    {
        $data = array();
        $this->form_validation->set_rules('user_type', 'User Type', 'trim|integer|required');
        if ($this->input->post('user_type') == '1') {
            $this->form_validation->set_rules('dist_code', 'District Name', 'trim|integer|required');
        }
        $this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[50]|xss_clean');
        $this->form_validation->set_rules('username', 'User Name', 'trim|required|max_length[50]|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|callback_validate_password');
        $this->form_validation->set_rules('password-confirm', 'Confirm Password', 'trim|required|matches[password]');
        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
        } else {
            $user_type = $this->input->post('user_type');
            $data = array(
                'dist_code' => ($user_type == '1') ? $this->input->post('dist_code') : '00',
                'subdiv_code' => '00',
                'cir_code' => '00',
                'user_role' => ($user_type == '1') ? '01' : '09',
                'user_status' => 'E',
                'username' => $this->input->post('username'),
                'password' => sha1($this->input->post('password')),
                'date_of_creation' => date("Y-m-d | h:i:sa"),
                'name' => $this->input->post('name'),
            );
            $usercheck = $this->LoginModel->check_user($this->input->post('username'));
            if ($usercheck < 1) {
                $userdetails = $this->LoginModel->add_user($data);
                if ($userdetails) {
                    echo json_encode(array('msg' => 'User Details Submitted Successfully', 'st' => 1));
                } else {
                    echo json_encode(array('msg' => 'Error in User Details Entry', 'st' => 0));
                }
            } else {
                echo json_encode(array('msg' => 'Username Already Exist', 'st' => 0));
            }
        }
    }

    public function AdminLoginSubmit()
    {
        $user_code = $this->input->post('user_code');
        $data = array();
        if($user_code == '00' || $user_code == 4) {
            $this->form_validation->set_rules('dist_code', 'District Name', 'trim|integer|required');
            $this->form_validation->set_rules('subdiv_code', 'Sub Division Name', 'trim|integer|required');
            $this->form_validation->set_rules('cir_code', 'Circle Name', 'trim|integer|required');
            $this->form_validation->set_rules('username', 'User Name', 'trim|required|max_length[5]');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|callback_validate_password');
            $this->form_validation->set_rules('user_code', 'User Type', 'trim|required');
            $this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[100]');
        }
        else if($user_code == 10 || $user_code == 11 || $user_code == 12) {
            $this->form_validation->set_rules('dist_code', 'District Name', 'trim|integer|required');
            // $this->form_validation->set_rules('subdiv_code', 'Sub Division Name', 'trim|integer|required');
            // $this->form_validation->set_rules('cir_code', 'Circle Name', 'trim|integer|required');
            $this->form_validation->set_rules('username', 'User Name', 'trim|required|max_length[5]');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|callback_validate_password');
            $this->form_validation->set_rules('user_code', 'User Type', 'trim|required');
            $this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[100]');
        }

        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
            exit;
        } 
        // else {
        
        if($user_code == '00' || $user_code == 4) {
            $data = array(
                'dist_code' => $this->input->post('dist_code'),
                'subdiv_code' => $this->input->post('subdiv_code'),
                'cir_code' => $this->input->post('cir_code'),
                'username' => $this->input->post('username'),
                'name' => $this->input->post('name'),
                'password' => sha1($this->input->post('password')),
                'user_role' => $this->input->post('user_code'),
                'user_status' => 'E',
                'date_of_creation' => date("Y-m-d | h:i:sa"),
            );
            $username = $this->input->post('username');
            $usercheck = $this->LoginModel->check_user($username);
            if ($usercheck < 1) {
                $userdetails = $this->LoginModel->add_user($data);
                if ($userdetails) {
                    echo json_encode(array('msg' => 'User Details Submitted Successfully', 'st' => 1));
                } else {
                    echo json_encode(array('msg' => 'Error in User Details Entry', 'st' => 0));
                }
            } else {
                echo json_encode(array('msg' => 'Username Already Exist', 'st' => 0));
            }
        }
        else if($user_code == 10 || $user_code == 11 || $user_code == 12) {
            $data = array(
                'dist_code' => $this->input->post('dist_code'),
                'subdiv_code' => '00',
                'cir_code' => '00',
                'username' => $this->input->post('username'),
                'name' => $this->input->post('name'),
                'password' => sha1($this->input->post('password')),
                'user_role' => $this->input->post('user_code'),
                'user_status' => 'E',
                'date_of_creation' => date("Y-m-d | h:i:sa"),
            );
            $username = $this->input->post('username');
            $usercheck = $this->LoginModel->check_user($username);
            if ($usercheck < 1) {
                $userdetails = $this->LoginModel->add_user($data);
                if ($userdetails) {
                    echo json_encode(array('msg' => 'User Details Submitted Successfully', 'st' => 1));
                } else {
                    echo json_encode(array('msg' => 'Error in User Details Entry', 'st' => 0));
                }
            } else {
                echo json_encode(array('msg' => 'Username Already Exist', 'st' => 0));
            }
        }
       
        // }
    }

    public function userCreateSubmit() {
        $this->form_validation->set_rules('username', 'User Name', 'trim|required|max_length[5]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|callback_validate_password');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[password]');
        $this->form_validation->set_rules('phoneno', 'Phone No', 'trim|required|min_length[10]');
        $this->form_validation->set_rules('user_code', 'User Type', 'trim|required');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[100]');
        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
            exit;
        }
        $data = array(
            'dist_code' => '00',
            'subdiv_code' => '00',
            'cir_code' => '00',
            'username' => $this->input->post('username'),
            'name' => $this->input->post('name'),
            'password' => sha1($this->input->post('password')),
            'user_role' => $this->input->post('user_code'),
            'mobile_no' => $this->input->post('phoneno'),
            'user_status' => 'E',
            'date_of_creation' => date("Y-m-d | h:i:sa"),
        );
        $username = $this->input->post('username');
        $usercheck = $this->LoginModel->check_user($username);
        if ($usercheck < 1) {
            $userdetails = $this->LoginModel->add_user($data);
            if ($userdetails) {
                echo json_encode(array('msg' => 'User Details Submitted Successfully', 'st' => 1));
            } else {
                echo json_encode(array('msg' => 'Error in User Details Entry', 'st' => 0));
            }
        } else {
            echo json_encode(array('msg' => 'Username Already Exist', 'st' => 0));
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        if ($this->session->userdata('fromSingleSign')) {
            $this->session->sess_destroy();
            redirect(SINGLESIGN_LINK);
        }
        $this->session->sess_destroy();
        redirect('/');
    }

    public function subdivisiondetailsall()
    {
        $this->dataswitch();
        $data = [];
        $Loginmodel = new LoginModel();
        $dis = $this->input->post('id');
        $formdata = $Loginmodel->subdivisiondetailsall($dis);
        foreach ($formdata as $value) {
            $data['subdiv_code'][] = $value;
        }
        echo json_encode($data['subdiv_code']);
    }

    public function circledetailsall()
    {
        $this->dataswitch();
        $data = [];
        $Loginmodel = new LoginModel();
        $dis = $this->input->post('dis');
        $subdiv = $this->input->post('subdiv');
        $formdata = $Loginmodel->circledetailsall($dis, $subdiv);
        foreach ($formdata as $value) {
            $data['cir_code'][] = $value;
        }
        echo json_encode($data['cir_code']);
    }

    /**
     * Sign in user from single sign in.
     *
     * If user does not exist insert into DB
     *
     * @ridirect to their appropriate dashboard according to their role.
     */
    public function singleSignRedirect()
    {
        $district = $this->input->get('district');
        $is_lm = $this->input->get('is_lm');
        $login_user = $this->input->get('login_user');
        $user = $this->input->get('user');
        $lm_code = $this->input->get('lm_code');
        if ($this->input->get('user_desig_code') == 'DEO') {
            $this->session->sess_destroy();
            redirect(SINGLESIGN_LINK);
        }
        $this->session->set_userdata('dcode', $district);
        $db = $this->UserModel->connectLocmaster();
        $rnd_id = openssl_decrypt($this->input->get('id'), "AES-128-CTR", "singleENCRYPT", 0, '1234567893032221');
        $query = $db->get_where('login_log', array('id' => $rnd_id, 'expired' => 0))->row();
        if ($query) {
            $db->set([
                'expired' => 1,
            ]);
            $db->where('id', $rnd_id);
            $db->update('login_log');
            $logindetails = false;
            $this->session->set_userdata('dcode', $district);
            $this->dataswitch($district);

            $logindetails = false;
            $validateuserdetails = $this->LoginModel->ValidateSingleSignUser($login_user);
            if ($validateuserdetails) {
                if ($is_lm == 'y') {
                    $this->db->select('*');
                    $this->db->from('lm_code');

                    $this->db->where('dist_code', $lm_code['dist_code'])->where('subdiv_code', $lm_code['subdiv_code'])
                        ->where('cir_code', $lm_code['cir_code'])->where('mouza_pargona_code', $lm_code['mouza_pargona_code'])->where('lot_no', $lm_code['lot_no'])
                        ->where('lm_name', $lm_code['lm_name'])
                        ->where('lm_code', $lm_code['lm_code']);
                    $query = $this->db->get();
                    $lm_user_with_name_exists = $query->row();
                    if (!$lm_user_with_name_exists) {
                        $this->db->where('dist_code', $lm_code['dist_code'])->where('subdiv_code', $lm_code['subdiv_code'])
                            ->where('cir_code', $lm_code['cir_code'])->where('mouza_pargona_code', $lm_code['mouza_pargona_code'])->where('lot_no', $lm_code['lot_no'])
                            ->where('lm_code', $lm_code['lm_code']);
                        $this->db->update('lm_code', $lm_code);
                    }
                }
                $logindetails = true;
            } else {
                $this->db->trans_begin();
                $this->db->insert('loginuser_table', $login_user);
                if ($is_lm == 'y') {
                    $this->db->select('*');
                    $this->db->from('lm_code');

                    $this->db->where('dist_code', $lm_code['dist_code'])->where('subdiv_code', $lm_code['subdiv_code'])
                        ->where('cir_code', $lm_code['cir_code'])->where('mouza_pargona_code', $lm_code['mouza_pargona_code'])->where('lot_no', $lm_code['lot_no'])->where('lm_code', $lm_code['lm_code']);
                    $query = $this->db->get();
                    $lm_user_exists = $query->row();

                    if ($lm_user_exists) {
                        $this->db->select('*');
                        $this->db->from('lm_code');

                        $this->db->where('dist_code', $lm_code['dist_code'])->where('subdiv_code', $lm_code['subdiv_code'])
                            ->where('cir_code', $lm_code['cir_code'])->where('mouza_pargona_code', $lm_code['mouza_pargona_code'])->where('lot_no', $lm_code['lot_no'])
                            ->where('lm_name', $lm_code['lm_name'])
                            ->where('lm_code', $lm_code['lm_code']);
                        $query = $this->db->get();
                        $lm_user_with_name_exists = $query->row();
                        if (!$lm_user_with_name_exists) {
                            $this->db->where('dist_code', $lm_code['dist_code'])->where('subdiv_code', $lm_code['subdiv_code'])
                                ->where('cir_code', $lm_code['cir_code'])->where('mouza_pargona_code', $lm_code['mouza_pargona_code'])->where('lot_no', $lm_code['lot_no'])
                                ->where('lm_code', $lm_code['lm_code']);
                            $this->db->update('lm_code', $lm_code);
                        }
                    } else {
                        $this->db->insert('lm_code', $lm_code);
                    }
                } else {
                    $this->db->select('*');
                    $this->db->from('users');

                    $this->db->where('dist_code', $user['dist_code'])->where('subdiv_code', $user['subdiv_code'])
                        ->where('cir_code', $user['cir_code'])->where('user_code', $user['user_code']);
                    $query = $this->db->get();
                    $user_exists = $query->row();

                    if ($user_exists) {
                    } else {
                        $this->db->insert('users', $user);
                    }
                }

                $this->db->trans_complete();
                $logindetails = true;
            }
            if ($is_lm == 'y') {
                $user_desig_code = 'LM';
            } else {
                $user_desig_code = $user['user_desig_code'];
            }
            $this->session->set_userdata('fromSingleSign', true);
            $usertype = $this->UserModel->getRoleCodeFromDharCode($user_desig_code);
            if (!$usertype) {
                $this->logout();
            }
            if ($logindetails) {
                $this->session->set_userdata('loggedin', true);
                $this->session->set_userdata('usertype', $usertype);
                $this->session->set_userdata('usercode', $login_user['user_code']);
                $this->session->set_userdata('user_code', $login_user['user_code']);
                $this->session->set_userdata('user_desig_code', $user_desig_code);
                $this->session->set_userdata('dcode', $login_user['dist_code']);
                $this->session->set_userdata('dist_code', $login_user['dist_code']);
                $this->session->set_userdata('subdiv_code', $login_user['subdiv_code']);
                $this->session->set_userdata('cir_code', $login_user['cir_code']);
                $this->session->set_userdata('mouza_pargona_code', $login_user['mouza_pargona_code']);
                $this->session->set_userdata('lot_no', $login_user['lot_no']);
                $this->session->set_userdata('language', 'english');
                $this->session->set_userdata('is_password_changed', '1');
                $this->session->set_userdata('is_set_mobile', '1');
                $this->session->set_userdata('is_otp_verified', '1');

                $userDetails = [
                    'username' => $login_user['use_name'],
                    'dist_code' => $login_user['dist_code'],
                    'subdiv_code' => (isset($login_user['subdiv_code'])) ? $login_user['subdiv_code'] : '00',
                    'cir_code' => (isset($login_user['cir_code'])) ? $login_user['cir_code'] : '00',
                    'mouza_pargona_code' => (isset($login_user['mouza_pargona_code'])) ? $login_user['mouza_pargona_code'] : '00',
                    'lot_no' => (isset($login_user['lot_no'])) ? $login_user['lot_no'] : '00',
                    'user_role' => isset($usertype) ? $usertype : '',
                    'action' => $this->UserModel::$USER_ACTIVITY_LOGIN
                ];
                $CI = &get_instance();
                $this->db = $CI->load->database('default', TRUE);
                $insertStatus = $this->LoginModel->UserActivity($userDetails, 'Login from '.SINGLESIGN_LINK);

                if ($usertype === 00) {
                    redirect('/Chithacontrol/index');
                } else if ($usertype == 1) { //Admin
                    redirect('/Login/adminindex');
                } else if ($usertype == 2) { //SuperAdmin
                    redirect('/Login/superadminindex');
                } else if ($usertype == 3) { //LM
                    redirect('/nc_village_v2/NcVillageLmController/dashboard');
                } else if ($usertype == 4) { //CO
                    redirect('/nc_village_v2/NcVillageCoController/dashboard');
                } else if ($usertype == 7) { //DC
                    redirect('/nc_village_v2/NcVillageDcController/dashboard');
                } else if ($usertype == 5) { //SK
                    redirect('/nc_village_v2/NcVillageSkController/dashboard');
                } else if ($usertype == 6 or $usertype == 8) { //5-SK 6-ADC 8-SDO
                    redirect('/Login/dashboard');
                } else if ($usertype == 9) {
                    redirect('/reports/DagReportController/index');
                }
            } else {
                $data['heading'] = "ERROR:: 404 Page Not Found";
                $data['message'] = 'The page you requested was not found';
                $this->load->view('errors/html/error_404', $data);
            }
        } else {
            $data['heading'] = "ERROR:: 404 Page Not Found";
            $data['message'] = 'The page you requested was not found';
            $this->load->view('errors/html/error_404', $data);
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


    public function DCdashboard()
    {
        $this->dataswitch();
        $dist_code = $this->session->userdata('dcode');
        $dag_patta_count = $this->db->query("SELECT COUNT(dag_no) as count FROM chitha_basic WHERE dist_code=?", array($dist_code))->row()->count;
        $data['total_count'] = $dag_patta_count;
        $data['_view'] = 'dashboard/dc_dashboard';
        $this->load->view('layout/layout', $data);
    }

    public function COdashboard()
    {
        $this->dataswitch();
        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');

        $dag_patta_count = $this->db->query("SELECT COUNT(dag_no) as count FROM chitha_basic WHERE dist_code=? AND subdiv_code=? AND cir_code=?", array($dist_code, $subdiv_code, $cir_code))->row()->count;

        $data['total_count'] = $dag_patta_count;
        $data['_view'] = 'dashboard/co_dashboard';
        $this->load->view('layout/layout', $data);
    }

    public function LMdashboard()
    {
        $this->dataswitch();
        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_code = $this->session->userdata('lot_no');

        $dag_patta_count = $this->db->query("SELECT COUNT(dag_no) as count FROM chitha_basic WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=?", array($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_code))->row()->count;

        $data['total_count'] = $dag_patta_count;
        $data['_view'] = 'dashboard/lm_dashboard';
        $this->load->view('layout/layout', $data);
    }
}
