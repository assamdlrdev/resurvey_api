<?php

include APPPATH . '/libraries/CommonTrait.php';

class UserManagement extends CI_Controller
{
    use CommonTrait;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('UserModel');
        if(!in_array($this->session->userdata('usertype'),[$this->UserModel::$ADMIN_CODE,$this->UserModel::$SUPERADMIN_CODE])){
            show_404();
        }
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->helper('security');
        $this->load->library('session');
        $this->load->library('UtilityClass');
    }
    public function index()
    {
        $this->dataswitch();

        $privileges = $this->db->query("SELECT * from privilege order by priv_code")->result();
        $data = array(
            'districts' => $this->UserModel->districts(),
            'privileges' => $privileges
        );
        $role_type = $this->input->post('role_type');
        $users = [];
        if ($role_type) {
            $role = $this->input->post('role');
            $role_code = $this->UserModel->getRoleCode($role);
            $status = $this->input->post('status');
            $dist_code = $this->input->post('dist_code');
            $subdiv_code = $this->input->post('subdiv_code');
            $cir_code = $this->input->post('circle_code');

            $roles = $this->db->query("select * from   master_user_designation where privilege = '$role_type'")->result();
            $sub_divs = $this->db->get_where('location', array('dist_code' => $dist_code, 'subdiv_code !=' => '00', 'cir_code' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->result_array();
            $circles = $this->db->get_where('location', array('dist_code' => $dist_code, 'subdiv_code =' => $subdiv_code, 'cir_code!=' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->result_array();

            $data['roles'] = $roles;
            $data['sub_divs'] = $sub_divs;
            $data['circles'] = $circles;

            $db = $this->UserModel->connectLocmaster();
            $query = $db->query("SELECT * FROM dataentryusers WHERE user_role='$role_code' AND user_status='$status' AND dist_code='$dist_code' AND subdiv_code='$subdiv_code' AND cir_code='$cir_code'");

            $dt_users = $query->result();
            foreach ($dt_users as $key => $dt_user) {
                $user_single = [];
                $user_single['serial_no'] = $dt_user->serial_no;
                $mouza_pargona_code = $dt_user->mouza_pargona_code;
                $lot_no = $dt_user->lot_no;    
                $user_code = $dt_user->user_code;
                if ($role != 'LM') {
                    $user = $this->db->query("SELECT * FROM users WHERE dist_code='$dist_code' AND subdiv_code='$subdiv_code' AND cir_code='$cir_code' AND user_code='$user_code'")->row();
                    $user_single['status'] = $dt_user->user_status;
                    $user_single['login_name'] = $dt_user->username;
                    $user_single['role'] = $this->UserModel->getDesignationName($dt_user->user_role);
                    $user_single['dist'] = $this->utilityclass->getDistrictName($dt_user->dist_code);
                    $user_single['subdiv'] = $this->utilityclass->getSubDivName($dt_user->dist_code, $dt_user->subdiv_code);
                    $user_single['circle'] = $this->utilityclass->getCircleName($dt_user->dist_code, $dt_user->subdiv_code, $dt_user->cir_code);
                    $user_single['mouza'] = null;
                    $user_single['lot'] = null;
                    if ($user) {
                        $user_single['name'] = $user->username;
                    }
                    $users[] = $user_single;
                } else {
                    $user = $this->db->query("SELECT * FROM lm_code WHERE dist_code='$dist_code' AND subdiv_code='$subdiv_code' AND cir_code='$cir_code' AND mouza_pargona_code='$mouza_pargona_code' AND lot_no='$lot_no' AND lm_code='$user_code'")->row();
                    $user_single['status'] = $dt_user->user_status;
                    $user_single['login_name'] = $dt_user->username;
                    $user_single['role'] = $this->UserModel->getDesignationName($dt_user->user_role);
                    $user_single['dist'] = $this->utilityclass->getDistrictName($dt_user->dist_code);
                    $user_single['subdiv'] = $this->utilityclass->getSubDivName($dt_user->dist_code, $dt_user->subdiv_code);
                    $user_single['circle'] = $this->utilityclass->getCircleName($dt_user->dist_code, $dt_user->subdiv_code, $dt_user->cir_code);
                    if ($user) {
                        $user_single['name'] = $user->lm_name;
                        $user_single['mouza'] = $this->utilityclass->getMouzaName($dt_user->dist_code, $dt_user->subdiv_code, $dt_user->cir_code, $user->mouza_pargona_code);
                        $user_single['lot'] = $this->utilityclass->getLotName($dt_user->dist_code, $dt_user->subdiv_code, $dt_user->cir_code, $user->mouza_pargona_code, $user->lot_no);
                    }
                    $users[] = $user_single;
                }
            }
        }
        $data['users'] = $users;
        $data['base'] = $this->config->item('base_url');
        $data['_view'] = 'user/index';
        $this->load->view('layout/layout', $data);
    }
    public function createUser()
    {
        $this->dataswitch();

        $privileges = $this->db->query("SELECT * from privilege order by priv_code")->result();
        $data = array(
            'districts' => $this->UserModel->districts(),
            'privileges' => $privileges
        );
        $roles = [];
        $subdivs = [];
        $circles = [];
        $mouzas = [];
        $sks = [];
        $lots = [];
        if(isset($_POST['role_type'])){
            $roles = $this->rolesByType($_POST['role_type']);
        }
        if(isset($_POST['dist_code'])){
            $subdivs = $this->subdivisiondetailsall($_POST['dist_code']);
        }
        if(isset($_POST['dist_code']) && isset($_POST['subdiv_code'])){
            $circles = $this->circledetailsall($_POST['dist_code'],$_POST['subdiv_code']);
        }
        if(isset($_POST['dist_code']) && isset($_POST['subdiv_code']) && isset($_POST['circle_code'])){
            $mouzas = $this->mouzas($_POST['dist_code'],$_POST['subdiv_code'],$_POST['circle_code']);
            $sks = $this->sks($_POST['dist_code'],$_POST['subdiv_code'],$_POST['circle_code']);
        }
        if(isset($_POST['dist_code']) && isset($_POST['subdiv_code']) && isset($_POST['circle_code']) && isset($_POST['mouza_pargona_code'])){
            $lots = $this->lots($_POST['dist_code'],$_POST['subdiv_code'],$_POST['circle_code'],$_POST['mouza_pargona_code']);
        }
        $data['roles'] = $roles;
        $data['subdivs'] = $subdivs;
        $data['circles'] = $circles;
        $data['mouzas'] = $mouzas;
        $data['lots'] = $lots;
        $data['sks'] = $sks;
        $data['base'] = $this->config->item('base_url');
        $data['_view'] = 'user/create';
        $this->load->view('layout/layout', $data);
    }
    public function storeUser()
    {
        $this->dataswitch();

        $this->form_validation->set_rules('name', 'Name', 'required|trim|strip_tags|xss_clean');
        $this->form_validation->set_rules('role_type', 'Role Type', 'required|trim|strip_tags|xss_clean');
        $this->form_validation->set_rules('status', 'Status', 'required|trim|strip_tags|xss_clean');
        $this->form_validation->set_rules('role', 'Role', 'required|trim|strip_tags|xss_clean');
        $this->form_validation->set_rules('type', 'Type', 'required|trim|strip_tags|xss_clean');
        $this->form_validation->set_rules('phone_no', 'Contact Number', 'required|trim|strip_tags|xss_clean');
        $this->form_validation->set_rules('date_of_joining', 'Date of Joining', 'required|trim|strip_tags|xss_clean');
        $this->form_validation->set_rules('dist_code', 'District', 'required|trim|strip_tags|xss_clean');
        $this->form_validation->set_rules('subdiv_code', 'Sub-division', 'required|trim|strip_tags|xss_clean');
        $this->form_validation->set_rules('circle_code', 'Circle Code', 'required|trim|strip_tags|xss_clean');
        if ($this->input->post('role') == 'LM') {
            $this->form_validation->set_rules('mouza_pargona_code', 'Mouza/Porgona Code', 'required|trim|strip_tags|xss_clean');
            $this->form_validation->set_rules('lot_no', 'Lot No', 'required|trim|strip_tags|xss_clean');
            $this->form_validation->set_rules('sk_name', 'SK', 'required|trim|strip_tags|xss_clean');
        }
        if($this->input->post('role') == 'DEO'){
            $this->form_validation->set_rules('username', 'User Name', 'required|trim|callback_unique_user|min_length[2]|max_length[5]|strip_tags|xss_clean');
        }else{
            $this->form_validation->set_rules('username', 'User Name', 'required|trim|callback_unique_user|min_length[2]|max_length[255]|strip_tags|xss_clean');
        }
        $this->form_validation->set_rules('password', 'Password', 'required|trim|strip_tags|min_length[8]|max_length[12]|xss_clean');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|trim|matches[password]|strip_tags|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', "Please enter all the fields correctly.");
            $this->createUser();
        } else {
            $form_data = [
                'name' => $this->input->post('name'),
                'role_type' => $this->input->post('role_type'),
                'status' => $this->input->post('status'),
                'role' => $this->input->post('role'),
                'type' => $this->input->post('type'),
                'phone_no' => $this->input->post('phone_no'),
                'date_of_joining' => $this->input->post('date_of_joining'),
                'dist_code' => $this->input->post('dist_code'),
                'subdiv_code' => $this->input->post('subdiv_code'),
                'cir_code' => $this->input->post('circle_code'),
                'mouza_pargona_code' => $this->input->post('mouza_pargona_code'),
                'lot_no' => $this->input->post('lot_no'),
                'username' => $this->input->post('username'),
                'password' => $this->input->post('password'),
                'confirm_password' => $this->input->post('confirm_password')
            ];
            if ($form_data['role'] == 'LM') {
                $form_data['sk_name'] = $this->input->post('sk_name');
                $reponse = $this->UserModel->insertLM($form_data);
            }else{
                $reponse = $this->UserModel->insertNoneLm($form_data);
            }
            if ($reponse == false) {
                $this->session->set_flashdata('error', "Could not create the user. Please try again later.");
                redirect(base_url() . "index.php/UserManagement/createUser");
            } else {
                $this->session->set_flashdata('success', "User " . $this->input->post('name') . " Successfully Created.");
                redirect(base_url() . "index.php/UserManagement/createUser");
            }
        }
    }
    public function edit($id = null)
    {
        $this->dataswitch();
        $serial_no = $id ? $id : $this->input->get('serial_no');
        $db = $this->UserModel->connectLocmaster();
        $dt_user = $db->query("SELECT * FROM dataentryusers WHERE serial_no='$serial_no'")->row();
        if ($dt_user) {
            $user_single = [];
            $user_single['serial_no'] = $dt_user->serial_no;
            $user_single['role_type'] = $this->UserModel->getPrivilageCodeFromRoleCode($dt_user->user_role);
            $sks = [];
            $dist_code = $dt_user->dist_code;
            $subdiv_code = $dt_user->subdiv_code;
            $cir_code = $dt_user->cir_code;
            $mouza_pargona_code = $dt_user->mouza_pargona_code;
            $lot_no = $dt_user->lot_no;
            $user_code = $dt_user->user_code;

            if ($dt_user->user_role != $this->UserModel::$LM_CODE) {
                $user = $this->db->query("SELECT * FROM users WHERE dist_code='$dist_code' AND subdiv_code='$subdiv_code' AND cir_code='$cir_code' AND user_code='$user_code'")->row();
                if ($user) {
                    $user_single['name'] = $user->username;
                    $user_single['phone_no'] = $user->phone_no;
                    $user_single['date_of_joining'] = $user->date_from;
                } else {
                    $user_single['name'] = '';
                    $user_single['phone_no'] = '';
                    $user_single['date_of_joining'] = '';
                }

                $user_single['status'] = $dt_user->user_status;
                $user_single['role'] = $this->UserModel->getRoleNameFromCode($dt_user->user_role);
                $user_single['dist_code'] = $dt_user->dist_code;
                $user_single['subdiv_code'] = $dt_user->subdiv_code;
                $user_single['circle_code'] = $dt_user->cir_code;
                $user_single['mouza_pargona_code'] = null;
                $user_single['lot_no'] = null;
                $user_single['sk_name'] = null;
                $user_single['username'] = $dt_user->username;
            } else {
                $user = $this->db->query("SELECT * FROM lm_code WHERE dist_code='$dist_code' AND subdiv_code='$subdiv_code' AND cir_code='$cir_code' AND mouza_pargona_code='$mouza_pargona_code' AND lot_no='$lot_no' AND lm_code='$user_code'")->row();
                if ($user) {
                    $user_single['name'] = $user->lm_name;
                    $user_single['phone_no'] = $user->phone_no;
                    $user_single['date_of_joining'] = $user->dt_from;
                    $user_single['sk_name'] = $user->corres_sk_code;
                } else {
                    $user_single['name'] = '';
                    $user_single['phone_no'] = '';
                    $user_single['date_of_joining'] = '';
                    $user_single['sk_name'] = '';
                }
                $user_single['status'] = $dt_user->user_status;
                $user_single['role'] = $this->UserModel->getRoleNameFromCode($dt_user->user_role);
                $user_single['dist_code'] = $dt_user->dist_code;
                $user_single['subdiv_code'] = $dt_user->subdiv_code;
                $user_single['circle_code'] = $dt_user->cir_code;
                $user_single['mouza_pargona_code'] = $user->mouza_pargona_code;
                $user_single['lot_no'] = $user->lot_no;
                $user_single['username'] = $dt_user->username;

                $dist_code = $user_single['dist_code'];
                $subdiv_code = $user_single['subdiv_code'];
                $cir_code = $user_single['circle_code'];
    
                $sk_role = $this->UserModel::$SK_CODE;
                $db = $this->UserModel->connectLocmaster();
                $sks = $db->query("select * FROM dataentryusers AS u where u.dist_code = '$dist_code' and u.subdiv_code = '$subdiv_code' and u.cir_code = '$cir_code' "
                    . "and u.user_role = '$sk_role' and u.user_status='E'")->result();
                
            }
            $privileges = $this->db->query("SELECT * from privilege order by priv_code")->result();
            $role_type = $user_single['role_type'];
            $dist_code = $user_single['dist_code'];
            $subdiv_code = $user_single['subdiv_code'];
            $roles = $this->db->query("select * from master_user_designation where privilege = '$role_type'")->result();
            $sub_divs = $this->db->get_where('location', array('dist_code' => $dist_code, 'subdiv_code !=' => '00', 'cir_code' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->result_array();
            $circles = $this->db->get_where('location', array('dist_code' => $dist_code, 'subdiv_code =' => $subdiv_code, 'cir_code!=' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->result_array();
            $mouzas = [];
            $lots = [];
            if ($user_single['circle_code']) {
                $mouzas = $this->db->get_where('location', array('dist_code' => $dist_code, 'subdiv_code =' => $subdiv_code, 'cir_code' => $user_single['circle_code'], 'mouza_pargona_code !=' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->result_array();
            }
            if ($user_single['mouza_pargona_code']) {
                $lots = $this->db->get_where('location', array('dist_code' => $dist_code, 'subdiv_code =' => $subdiv_code, 'cir_code' => $user_single['circle_code'], 'mouza_pargona_code' => $user_single['mouza_pargona_code'], 'lot_no !=' => '00', 'vill_townprt_code' => '00000'))->result_array();
            }
            $data = array(
                'districts' => $this->UserModel->districts(),
                'privileges' => $privileges,
                'roles' => $roles,
                'sub_divs' => $sub_divs,
                'circles' => $circles,
                'mouzas' => $mouzas,
                'lots' => $lots,
                'sks' => $sks,
                'serial_no' => $serial_no
            );
            $data['base'] = $this->config->item('base_url');
            $data['user'] = $user_single;
            $data['_view'] = 'user/edit';
            $this->load->view('layout/layout', $data);
        }
    }
    public function updateUser()
    {
        $this->dataswitch();

        $this->form_validation->set_rules('phone_no', 'Contact Number', 'required|trim|strip_tags|xss_clean');
        $this->form_validation->set_rules('phone_no', 'Contact Number', 'required|trim|strip_tags|xss_clean');
        if($this->input->post('password')){
            $this->form_validation->set_rules('password', 'Password', 'trim|strip_tags|min_length[8]|max_length[12]|xss_clean');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|trim|matches[password]|strip_tags|xss_clean');    
        }

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', "Please enter all the fields correctly.");
            $this->edit($this->input->post('serial_no'));
        }else{
            $form_data = [
                'serial_no' => $this->input->post('serial_no'),
                'name' => $this->input->post('name'),
                'role_type' => $this->input->post('role_type'),
                'status' => $this->input->post('status'),
                'role' => $this->input->post('role'),
                'type' => $this->input->post('type'),
                'phone_no' => $this->input->post('phone_no'),
                'date_of_joining' => $this->input->post('date_of_joining'),
                'dist_code' => $this->input->post('dist_code'),
                'subdiv_code' => $this->input->post('subdiv_code'),
                'cir_code' => $this->input->post('circle_code'),
                'mouza_pargona_code' => $this->input->post('mouza_pargona_code'),
                'lot_no' => $this->input->post('lot_no'),
                'username' => $this->input->post('username'),
                'password' => $this->input->post('password'),
                'confirm_password' => $this->input->post('confirm_password')
            ];
            if ($form_data['role'] == 'LM') {
                $reponse = $this->UserModel->updateLM($form_data);
            }else{
                $reponse = $this->UserModel->updateNoneLm($form_data);
            }
            if ($reponse == false) {
                $this->session->set_flashdata('error', "Could not update the user. Please try again later.");
            } else {
                $this->session->set_flashdata('success', "User " . $this->input->post('name') . " Successfully updated.");
            }
            $this->edit($this->input->post('serial_no'));
        }
    }
    public function getMouzas()
    {
        $dist_code        = $this->input->post('dist_code');
        $subdiv_code     = $this->input->post('subdiv_code');
        $circle_code     = $this->input->post('circle_code');
        $data = $this->mouzas($dist_code,$subdiv_code,$circle_code);
        echo json_encode($data);
    }
    public function mouzas($dist_code,$subdiv_code,$cir_code)
    {
        return $this->db->get_where('location', array('dist_code' => $dist_code, 'subdiv_code' => $subdiv_code, 'cir_code' => $cir_code, 'mouza_pargona_code !=' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->result_array();
    }
    public function getLots()
    {
        $dist_code        = $this->input->post('dist_code');
        $subdiv_code     = $this->input->post('subdiv_code');
        $circle_code     = $this->input->post('circle_code');
        $mouza_pargona_code     = $this->input->post('mouza_pargona_code');
        $data = $this->lots($dist_code,$subdiv_code,$circle_code,$mouza_pargona_code);
        echo json_encode($data);
    }
    public function lots($dist_code,$subdiv_code,$circle_code,$mouza_pargona_code)
    {
        return $this->db->get_where('location', array('dist_code' => $dist_code, 'subdiv_code' => $subdiv_code, 'cir_code' => $circle_code, 'mouza_pargona_code' => $mouza_pargona_code, 'lot_no !=' => '00', 'vill_townprt_code' => '00000'))->result_array();
    }
    public function getRoles()
    {
        $roles = $this->rolesByType();
        echo json_encode($roles);
    }
    public function rolesByType($role_type = null)
    {
        $this->dataswitch();
        $role_type = $role_type ? $role_type : $this->input->post('role');
        $designation = $this->db->query("select * from   master_user_designation where privilege = '$role_type'");


        $data = $designation->result();
        $json = array();
        foreach ($data as $object) {
            if (in_array(trim($object->user_desig_code), ['CO', 'DEO', 'LM', 'SK'])) {
                $json[] = array('user_desig_code' => trim($object->user_desig_code), 'user_desig_as' => trim($object->user_desig_as));
            }
        }
        return $json;
    }
    public function getSks()
    {
        $dist_code  = $this->input->post('dist_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $data = $this->sks($dist_code,$subdiv_code,$cir_code);   
        $json = array();
        foreach ($data as $object) {
            $json[] = array('user_code' => $object->user_code, 'username' => $object->username);
        }
        echo json_encode($json);
    }
    public function sks($dist_code,$subdiv_code,$cir_code)
    {
        $db = $this->UserModel->connectLocmaster();
        $sk_role = $this->UserModel::$SK_CODE;
        $sk = $db->query("select * FROM dataentryusers AS u where u.dist_code = '$dist_code' and u.subdiv_code = '$subdiv_code' and u.cir_code = '$cir_code' "
            . "and u.user_role = '$sk_role' and u.user_status='E'");
        return $sk->result();
    }

    public function unique_user($username)
    {
        if ($this->UserModel->isUserNameExists($username)) {
            $this->form_validation->set_message('unique_user', 'The {field} ' . $username . ' already exists.');
            return FALSE;
        } else {
            return TRUE;
        }
    }
    public function changeStatus()
    {
        $serial_no = $this->input->post('serial_no');
        $action = $this->input->post('action');
        if ($serial_no) {
            $db = $this->UserModel->connectLocmaster();
            $this->dataswitch();
            $user = $db->get_where('dataentryusers',['serial_no' => $serial_no])->row();
            if($user){
                $user_code = $user->user_code;
                $dist_code = $user->dist_code;
                $subdiv_code = $user->subdiv_code;
                $cir_code = $user->cir_code;
                $mouza_pargona_code = $user->mouza_pargona_code;
                $lot_no = $user->lot_no;

                if($user->user_role == $this->UserModel::$LM_CODE){
                    $sql = "update lm_code set status='$action' WHERE dist_code='$dist_code' AND subdiv_code='$subdiv_code' AND cir_code='$cir_code' AND mouza_pargona_code='$mouza_pargona_code' AND lot_no='$lot_no' AND lm_code='$user_code'";
                }else{
                    $sql = "update users set status='$action' WHERE dist_code='$dist_code' AND subdiv_code='$subdiv_code' AND cir_code='$cir_code' AND user_code='$user_code'";
                }
                $this->db->query($sql);
                $sql2 = "update dataentryusers set user_status='$action' where serial_no = '$serial_no'";
                $db->query($sql2);    
                echo json_encode('updated');
                return;        
            }
        }
        echo json_encode('failed');
        return;
    }
    public function subdivisiondetailsall($dist_code = null)
    {
        $dist_code = $dist_code ? $dist_code : $this->input->post('dist_code');
        $this->load->model('LoginModel');
        $Loginmodel = new LoginModel();
        return $Loginmodel->subdivisiondetailsall($dist_code);
    }
    public function circledetailsall($dist_code,$subdiv_code)
    {
        $this->load->model('LoginModel');
        $Loginmodel = new LoginModel();
        return $Loginmodel->circledetailsall($dist_code, $subdiv_code);
    }
   
}
