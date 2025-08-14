<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class SurveyUserController extends CI_Controller
{
    use CommonTrait;
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');

        $this->load->model('UserModel');
        $this->load->model('LoginModel');
        // $this->load->model('SurveyorVillageFinalReportModel');
        // $this->load->helper('security');
    }

    public function index() {
        // $this->dataswitch();
        $this->dbswitch('default');
        $users = $this->UserModel->getSurveyUsers();

        $data['users'] = $users;
        $data['_view'] = 'survey_users/view_users';

        $this->load->view('layout/layout', $data);
    }

    // public function usercreationIndex() {
    //     if ($this->session->userdata('usertype') != 13) {
    //         $this->logout();
    //     }
    //     $data['base'] = $this->config->item('base_url');
    //     $LoginModel = new LoginModel();
    //     $data['districts'] = $LoginModel->districtdetailsall();
    //     $data['_view'] = 'survey_users/usercreationindex';
    //     $this->load->view('layout/layout', $data);
    // }

    // public function userCreateSubmit() {
    //     $this->form_validation->set_rules('username', 'User Name', 'trim|required|max_length[5]');
    //     $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|callback_validate_password');
    //     $this->form_validation->set_rules('phoneno', 'Phone No', 'trim|required|min_length[10]');
    //     $this->form_validation->set_rules('user_code', 'User Type', 'trim|required');
    //     $this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[100]');
    //     if ($this->form_validation->run() == false) {
    //         $text = str_ireplace('<\/p>', '', validation_errors());
    //         $text = str_ireplace('<p>', '', $text);
    //         $text = str_ireplace('</p>', '', $text);
    //         echo json_encode(array('msg' => $text, 'st' => 0));
    //         exit;
    //     }
    //     $data = array(
    //         'dist_code' => '00',
    //         'subdiv_code' => '00',
    //         'cir_code' => '00',
    //         'username' => $this->input->post('username'),
    //         'name' => $this->input->post('name'),
    //         'password' => sha1($this->input->post('password')),
    //         'user_role' => $this->input->post('user_code'),
    //         'mobile_no' => $this->input->post('phoneno'),
    //         'user_status' => 'E',
    //         'date_of_creation' => date("Y-m-d | h:i:sa"),
    //     );
    //     $username = $this->input->post('username');
    //     $usercheck = $this->LoginModel->check_user($username);
    //     if ($usercheck < 1) {
    //         $userdetails = $this->LoginModel->add_user($data);
    //         if ($userdetails) {
    //             echo json_encode(array('msg' => 'User Details Submitted Successfully', 'st' => 1));
    //         } else {
    //             echo json_encode(array('msg' => 'Error in User Details Entry', 'st' => 0));
    //         }
    //     } else {
    //         echo json_encode(array('msg' => 'Username Already Exist', 'st' => 0));
    //     }
    // }

    public function edit($usercode){
        $SURVEY_SUPER_ADMIN_CODE = $this->UserModel::$SURVEY_SUPER_ADMIN_CODE;
        $usercode = urldecode($usercode);
        $this->dbswitch('default');
        $loc_db = $this->db;
        $user = $loc_db->where('username', $usercode)->get('dataentryusers')->row();
        if(!$user){
            show_404();
        }

        if($user->user_role == $SURVEY_SUPER_ADMIN_CODE){
            show_error('Permission denied to update');
        }

        $data['user'] = $user;
        $data['_view'] = 'survey_users/edit';
        $this->load->view('layout/layout', $data);
    }

    public function update($usercode) {
        $SURVEY_SUPER_ADMIN_CODE = $this->UserModel::$SURVEY_SUPER_ADMIN_CODE;
        $usercode = urldecode($usercode);
        $this->dbswitch('default');
        $loc_db = $this->db;
        $user = $loc_db->where('username', $usercode)->get('dataentryusers')->row();
        if(!$user){
            show_404();
        }

        if($user->user_role == $SURVEY_SUPER_ADMIN_CODE){
            show_error('Permission denied to update');
        }

        $name = $this->input->post('name');
        $phoneno = $this->input->post('phoneno');
        $password = $this->input->post('password');
        $confirm_password = $this->input->post('confirm_password');
        $user_role = $this->input->post('user_role');
        $reset_on_login = $this->input->post('reset_on_login');
        
        $error_msg = [];
        $validation = [
                        // [
                        //     'field' => 'user_role',
                        //     'label' => 'User Type',
                        //     'rules' => 'trim|required'
                        // ],
                        [
                            'field' => 'phoneno',
                            'label' => 'Phone No',
                            'rules' => 'trim|required|numeric|min_length[10]'
                        ],
                        [
                            'field' => 'name',
                            'label' => 'Name',
                            'rules' => 'trim|required|max_length[100]'
                        ],
                    ];
        
        if(!empty($password) || !empty($confirm_password)){
        
            $validation[] =[
                                'field' => 'password',
                                'label' => 'Password',
                                'rules' => 'trim|required|min_length[6]|callback_validate_password'
                            ];

            $validation[] = [
                                'field' => 'confirm_password',
                                'label' => 'Confirm Password',
                                'rules' => 'trim|required|matches[password]'
                            ];
        }

        $this->form_validation->set_rules($validation);
        if ($this->form_validation->run() == FALSE)
        {               
            $this->form_validation->set_error_delimiters('', '');
            foreach($validation as $rule){
                if (form_error($rule['field'])) {
                    // array_push($error_msg, [$rule['field'] => form_error($rule['field'])]);
                    $field_name = str_replace("[]", "", $rule['field']);
                    $error_msg[$field_name] = form_error($rule['field']);
                }
            }              
            return response_json(['success' => false, 'errors' => $error_msg], 403);
        }
        
        $data = array(
            'name' => $name,
            // 'user_role' => $this->input->post('user_role'),
            'mobile_no' => $phoneno,
        );

        if(!empty($password)){
            $data += [
                        'password' => sha1($password),
                        'is_password_changed' => $reset_on_login ? NULL : '1'
                    ];
        }
        
        $loc_db->trans_begin();
        try{
            $conditions = ['username' => $usercode];
            $this->LoginModel->updateUser($conditions, $data, $loc_db);
            if($loc_db->affected_rows() != 1 || $loc_db->trans_status() == false){
                log_message('error', '#ERRUSRUPD0001 => ' . $loc_db->last_query());
                throw new Exception("#ERRUSRUPD0001: Something went wrong. Please try again later");
            }
        }catch(Exception $e){
            $loc_db->trans_rollback();
            return response_json(['success' => false, 'message' => $e->getMessage()]);
        }
        $loc_db->trans_commit();

        $redirect_url = base_url('index.php/survey-users');
        return response_json(['success' => true, 'message' => 'User profile updated successfully.', 'redirect_url' => $redirect_url]);        
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
