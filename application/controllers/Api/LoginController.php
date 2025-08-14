
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
            if (!isset($data->api_key) || $data->api_key == null)
                $msg = $msg . " Missing api_key,";
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

            $apikey = $data->api_key;
            $user_name = $data->user_name;
            $password = $data->password;
        } else {
            $msg = null;

            if (!isset($_POST['api_key']) || $_POST['api_key'] == null)
                $msg = "Missing apikey,";
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

            $apikey = $_POST['apikey'];
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

}