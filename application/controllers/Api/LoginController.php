
<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class LoginController extends CI_Controller
{
    use CommonTrait;
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('LoginModel');
        $this->load->model('UserModel');
        // $this->load->helper('security');
    }

    public function login () {
        header('Content-Type: application/json');
        if ($_SERVER['CONTENT_TYPE'] == 'application/json') {
            $data = json_decode(file_get_contents('php://input', true));

            $msg = null;
            if (!isset($data) || $data == null)
                $msg = "Missing Parameters,";
            // if (!isset($data->api_key) || $data->api_key == null)
            //     $msg = $msg . " Missing api_key,";
            if (!isset($data->user_name) || $data->user_name == null)
                $msg = $msg . " Missing User Name";
            if (!isset($data->password) || $data->password == null)
                $msg = " Missing Password";
            if ($msg != null && !empty($msg)) {
                $response = [
                    'status' => 'n',
                    'msg' => $msg 
                ];
                $this->output->set_status_header(401);  // Change to 400, 401, 500, etc. as needed
                echo json_encode($response);
                exit;
            }

            // $apikey = $data->api_key;
            $user_name = $data->user_name;
            $password = $data->password;
        } else {
            $msg = null;

            // if (!isset($_POST['api_key']) || $_POST['api_key'] == null)
            //     $msg = "Missing apikey,";
            if (!isset($_POST['user_name']) || $_POST['user_name'] == null)
                $msg = $msg . " Missing User Name";
            if (!isset($_POST['password']) || $_POST['password'] == null)
                $msg = " Missing Password";
            if ($msg != null && !empty($msg)) {
                $response = [
                    'status' => 'n',
                    'msg' => $msg 
                ];
                $this->output->set_status_header(401);  // Change to 400, 401, 500, etc. as needed
                echo json_encode($response);
                return;
            }

            // $apikey = $_POST['apikey'];
            $user_name = $_POST['user_name'];
            $password = $_POST['password'];
        }

        $payload = [];
        $logindetails = false;
        // $salt = rand(1000, 10000);
        // $this->session->set_userdata('salt', $salt);
        $validateuserdetails = $this->LoginModel->ValidateApiUser($user_name, $password);
        if (isset($validateuserdetails) && !empty($validateuserdetails)) {
            $logindetails = true;
            $usertype = $validateuserdetails['user_role'];
            $usercode = $validateuserdetails['username'];
            $distcode = $validateuserdetails['dist_code'];
            $subdiv_code = $validateuserdetails['subdiv_code'];
            $cir_code = $validateuserdetails['cir_code'];
            $is_password_changed = $validateuserdetails['is_password_changed'];
            $is_set_mobile = $validateuserdetails['mobile_no'];

            $payload = [
                'usertype' => $usertype,
                'loggedin' => true,
                'usercode' => $usercode,
                'dcode' => $distcode,
                'subdiv_code' => $subdiv_code,
                'cir_code' => $cir_code,
                'is_password_changed' => $is_password_changed ? '1' : false
            ];

            // if (ENABLE_MOBILE_VERIFICATION == '1' && $is_set_mobile) {
            //     // $this->session->set_userdata('is_set_mobile', '1');
            //     // $this->session->set_userdata('is_otp_verified', '0');
            //     $otp_generated = mt_rand(100000, 999999);
            //     // $this->session->set_userdata('login_otp', $otp_generated);
            //     sendOtpToPhone($validateuserdetails['mobile_no'], $otp_generated);
            // }

        }
        if(!$logindetails || empty($payload)) {
            $response = [
                'status' => 'n',
                'msg' => 'Login Failed Wrong Username or Password'
            ];
            $this->output->set_status_header(401);  // Change to 400, 401, 500, etc. as needed
            echo json_encode($response);
            return;
        }
        // $this->load->library('JWT');
        // $secret_key = JWT_SECRET_KEY;
        // $token = JWT::encode($payload, $secret_key, 'HS256');
        $token = jwtencode($payload);
        
        //insert into user_activity
        $validateuserdetails['action'] = $this->UserModel::$USER_ACTIVITY_LOGIN;
        $insertStatus = $this->LoginModel->UserActivity($validateuserdetails, 'Login from Chitha Entry Application');
        $response = [
            'status' => 'y',
            'msg' => 'Successfully logged in!',
            'data' => $token
        ];
        $this->output->set_status_header(200);  // Change to 400, 401, 500, etc. as needed
        echo json_encode($response);
        return;
    }




    public function addLoginLog()
    {
        if ($this->input->get('api_key') == "resurvey_application" || $_POST['api_key'] == "resurvey_application") {
            try {
                $form_data = [
                    'dist_code' => $this->input->get('dist_code') ? $this->input->get('dist_code') : $_POST['dist_code'],
                    'username' => $this->input->get('username') ? $this->input->get('username') : $_POST['username'],
                    'id' => time(),
                    'expired' => 0
                ];
                // $form_data['id'] = time();
                $encrypt = openssl_encrypt($form_data['id'], "AES-128-CTR", "singleENCRYPT", 0, '1234567893032221');
                // $form_data['expired'] = 0;
                $db = $this->UserModel->connectLocmaster();
                $db->trans_start();
                $db->insert('login_log', [
                    'dist_code' => $form_data['dist_code'],
                    'expired' => $form_data['expired'],
                    'id' => $form_data['id'],
                    'username' => $form_data['username'],
                ]);
                $db->trans_complete();
                Header('Access-Control-Allow-Origin: *');
                echo json_encode(['message' => 'Successfully Added', 'responseCode' => 1, 'id' => $encrypt]);
            } catch (Exception $e) {
                Header('Access-Control-Allow-Origin: *');
                echo json_encode(['message' => 'Error while inserting.', 'responseCode' => 0]);
            }
        }
    }

    public function singleSignRedirect()
    {
        $queryString = $_POST['resurvey_data'];
        parse_str($queryString, $output);
        $resurvey_data = $output;

        $district = $this->input->post('district', true);
        $id = $this->input->post('id', true);
        $is_lm = $resurvey_data['is_lm'];
        $user_desig_code = $resurvey_data['user_desig_code'];
        $login_user = $resurvey_data['login_user'];
        $user = $resurvey_data['user'];
        $password_change_flag = $login_user['password_change_flag'];
        $rnd_id = openssl_decrypt($id, "AES-128-CTR", "singleENCRYPT", 0, '1234567893032221');
        if($is_lm == 'y') {
            $lm_code = $resurvey_data['lm_code'];
        }
        $db = $this->UserModel->connectLocmaster();

        $query = $db->get_where('login_log', array('id' => $rnd_id, 'expired' => 0))->row();
        if(empty($query)) {
            $this->output->set_status_header(401);
            echo json_encode([
                'status' => 'n',
                'msg' => 'The page you requested was not found!'
            ]);
            return;
        }
        $db->set([
            'expired' => 1,
        ]);
        $db->where('id', $rnd_id);
        $db->update('login_log');

        $logindetails = false;

        $dist = $login_user['dist_code'];

        $this->dbswitch($dist);
        $validateuserdetails = $this->LoginModel->ValidateSingleSignUser($login_user);

        if($validateuserdetails) {
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
        }
        else {
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
            $user_desig_code = $user_desig_code;
        }
        // $this->session->set_userdata('fromSingleSign', true);
        $usertype = $this->UserModel->getRoleCodeFromDharCode($user_desig_code);
        if (!$usertype) {
            $this->output->set_status_header(401);
            echo json_encode([
                'status' => 'n',
                'msg' => 'Not Authorized for this UserType!'
            ]);
            return;
        }

        if (!$logindetails) {
            $this->output->set_status_header(401);
            echo json_encode([
                'status' => 'n',
                'msg' => 'User Not Validated!'
            ]);
            return;
        }

        $dist_code = $login_user['dist_code'];
        $subdiv_code = $login_user['subdiv_code'];
        $cir_code = $login_user['cir_code'];
        $mouza_pargona_code = $login_user['mouza_pargona_code'];
        $lot_no = $login_user['lot_no'];
        $user_code = $login_user['user_code'];

        $payload = [
            'usertype' => $usertype,
            'loggedin' => true,
            'usercode' => $user_code,
            'dcode' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'user_desig_code' => $user_desig_code,
            'is_password_changed' => ($password_change_flag == 1) ? '1' : null
        ];

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
        $insertStatus = $this->LoginModel->UserActivity($userDetails, 'Login from ' . SINGLESIGN_LINK);

        $token = jwtencode($payload);

        $response = [
            'status' => 'y',
            'msg' => 'Successfully logged in!',
            'data' => $token,
            'usertype' => $usertype
        ];
        $this->output->set_status_header(200);  // Change to 400, 401, 500, etc. as needed
        echo json_encode($response);
        return;

    }
}