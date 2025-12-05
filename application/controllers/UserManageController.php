<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserManageController extends CI_Controller
{

    function __construct() {
        parent::__construct();
        $this->load->model('LoginModel');
    }

    public function valid_password($password = '')
    {
        $password = trim($password);
        $regex_lowercase = '/[a-zA-Z]/';
//        $regex_uppercase = '/[A-Z]/'; // upper case validate
        $regex_number = '/[0-9]/';
        $regex_special = '/[!@#$%^&*()\-_=+{};:,<.>§~]/';
        if (empty($password))
        {
            $this->form_validation->set_message('valid_password', 'The {field} field is required.');
            return FALSE;
        }
        if (preg_match_all($regex_lowercase, $password) < 1)
        {
            $this->form_validation->set_message('valid_password', 'The {field} field must be at least one letter.');
            return FALSE;
        }
//        if (preg_match_all($regex_uppercase, $password) < 1)
//        {
//            $this->form_validation->set_message('valid_password', 'The {field} field must be at least one uppercase letter.');
//            return FALSE;
//        }
        if (preg_match_all($regex_number, $password) < 1)
        {
            $this->form_validation->set_message('valid_password', 'The {field} field must have at least one number.');
            return FALSE;
        }
        if (preg_match_all($regex_special, $password) < 1)
        {
            $this->form_validation->set_message('valid_password', 'The {field} field must have at least one special character.' . ' ' . htmlentities('!@#$%^&*()\-_=+{};:,<.>§~'));
            return FALSE;
        }
        if (strlen($password) < 3)
        {
            $this->form_validation->set_message('valid_password', 'The {field} field must be at least 3 characters in length.');
            return FALSE;
        }
        if (strlen($password) > 30)
        {
            $this->form_validation->set_message('valid_password', 'The {field} field cannot exceed 30 characters in length.');
            return FALSE;
        }
        return TRUE;
    }



    // get user details
    public function changeUserPassword()
    {
        $userId = $this->session->userdata('usercode');
        $base = $this->config->item('base_url');

        $userDetails = $this->LoginModel->getUserDetails($userId);
        if($userDetails == NULL)
        {
            echo json_encode(array('msg' => 'Use not found !', 'st' => 0));
            redirect($base.'index.php/logout');
        }

        $data['base'] = $base;
        $data['_view'] = 'user/change_password';

        $this->load->view('layout/layout', $data);
    }


    // set new Password
    public function setNewPasswordByLoginUser()
    {
        $this->form_validation->set_rules('oldHashedPassword', 'Old Password',
            'trim|required');
        $this->form_validation->set_rules('hashedPassword', 'New Password',
            'trim|required');
        // $this->form_validation->set_rules('confirm_password', 'Confirmation  Password','trim|required|min_length[7]|max_length[30]|matches[password]');

        $base = $this->config->item('base_url');

        if($this->form_validation->run()==false)
        {
            $text=str_ireplace('<\/p>','',validation_errors());
            $text=str_ireplace('<p>','',$text);
            $text=str_ireplace('</p>','',$text);

            $this->session->set_flashdata('error',$text);
            redirect($base.'index.php/get-change-password');
        }
        else
        {
            $userId    = $this->session->userdata('usercode');
            // $password  = $this->input->post('old_password');
            // $salt      = $this->session->userdata('salt');
            // $hashedpwd = sha1($password);
            $hashedpwd = $this->input->post('oldHashedPassword');
            $validateuserdetails = $this->LoginModel->validateOldPassword($userId,$hashedpwd);
            if($validateuserdetails == 0)
            {
                $this->session->set_flashdata('error','Old Password Not Match');
                redirect($base.'index.php/get-change-password');
            }


            $data = array(
                // 'password' => sha1($this->input->post('password')),
                'password' => $this->input->post('hashedPassword'),
            );

            $this->LoginModel->updateUserPassword($userId,$data);
            if ($this->db->trans_status() === FALSE)
            {
                $this->session->set_flashdata('error',"There is some problem, Please try again");
                redirect($base.'index.php/get-change-password');
            }
            else
            {
                $this->session->set_flashdata('success',"Password successfully updated ");
                redirect($base.'index.php/get-change-password');
            }
        }
    }






}
