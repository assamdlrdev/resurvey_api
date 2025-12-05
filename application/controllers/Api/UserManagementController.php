<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UserManagementController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // load model & form_validation
        $this->load->model('Api/Dataentryuser_model');
        $this->load->model('UserModel');
        $this->load->model('Api/LocationModel');
        $this->load->library('form_validation');
        $this->load->helper(array('url', 'security'));
        $this->load->model('Api/Designation_model');

        // Force JSON responses
        header('Content-Type: application/json; charset=utf-8');
        $auth = validate_jwt();
        if (!$auth['status']) {
            $this->output
                ->set_status_header(401)
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => $auth['message']]))
                ->_display();
            exit;
        }

        $this->jwt_data = $auth['data'];
    }

    /**
     * POST /api/users
     * Create a new user.
     * Accepts JSON body or form-encoded data.
     */
    public function create()
    {
        // echo "User creation API called";
        // Basic optional API key check (replace/extend with real auth)
        // $api_key = $this->input->get_request_header('X-API-Key', TRUE);
        // if ($api_key !== 'your-api-key-here') { http_response_code(401); echo json_encode(['status'=>0,'message'=>'Unauthorized']); return; }
        $usercode  =  $this->jwt_data->usercode;

        // Read JSON body if present
        $raw = trim(file_get_contents("php://input"));
        $data = [];
        if (!empty($raw)) {
            $decoded = json_decode($raw, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $data = $decoded;
            }
        }
        // Fallback to $_POST (form data)
        if (empty($data)) {
            $data = $this->input->post();
        }

        // map inputs (avoid undefined index)
        $input = [
            'dist_code'   => isset($data['dist_code'])   ? $this->security->xss_clean($data['dist_code'])   : null,
            // 'subdiv_code' => isset($data['subdiv_code']) ? $this->security->xss_clean($data['subdiv_code']) : null,
            'cir_code'    => isset($data['cir_code'])    ? $this->security->xss_clean($data['cir_code'])    : null,
            'user_role'   => isset($data['user_role'])   ? $this->security->xss_clean($data['user_role'])   : null,
            'username'    => isset($data['username'])    ? $this->security->xss_clean($data['username'])    : null,
            'password'    => isset($data['password'])    ? $data['password'] : null, // don't xss_clean password
            'name'        => isset($data['name'])        ? $this->security->xss_clean($data['name'])        : null,
            'mobile_no'    => isset($data['mobile_no'])    ? $this->security->xss_clean($data['mobile_no'])    : null,
            'email'   => isset($data['email'])   ? $this->security->xss_clean($data['email'])   : null,
            'designation'   => isset($data['designation'])   ? $this->security->xss_clean($data['designation'])   : null,
        ];

        // Setup validation rules (programmatic)
        $this->form_validation->reset_validation();
        $this->form_validation->set_data($input);

        // dist_code and cir_code required only when user_role == 15
        $isCircleRequired = ((string)(int)$input['user_role'] !== '15');

        if ($isCircleRequired) {
            $this->form_validation->set_rules('dist_code', 'District code', 'trim|required|alpha_numeric');
            $this->form_validation->set_rules('cir_code', 'Circle code', 'trim|required|regex_match[/^[0-9\-]+$/]');
            $parts = explode("-", $input['cir_code']);
            $subdiv  = $parts[1];   // 01
            $cir     = $parts[2];   // 01
        } else {
            $this->form_validation->set_rules('dist_code', 'District code', 'trim|alpha_numeric');
            $this->form_validation->set_rules('cir_code', 'Circle code', 'trim|regex_match[/^[0-9\-]+$/]');
        }
        $this->form_validation->set_rules('user_role', 'User role', 'trim|required|alpha_numeric');
        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[3]|max_length[50]|alpha_numeric|callback__username_unique_api');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('name', 'Full name', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('mobile_no', 'Phone no', 'trim|required|numeric|exact_length[10]|callback_phone_unique');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('designation', 'Designation Code', 'trim|regex_match[/^[0-9]{2}$/]');



        if ($this->form_validation->run() === FALSE) {
            http_response_code(422);
            echo json_encode([
                'status' => 0,
                'message' => 'Validation failed',
                'errors' => $this->form_validation->error_array()
            ]);
            return;
        }

        // Prepare insert data (hash password)
        $insert = [
            'user_role'   => $input['user_role'],
            'username'    => $input['username'],
            'password'    => sha1($input['password']),
            'name'        => $input['name'],
            // 'mobile_no'    => $input['mobile_no'],
            'mobile_no'   => $input['mobile_no'],
            'email'       => $input['email'],
            'designation' => $input['designation'],
            'user_status' => 'E',
            'date_of_creation'  => date('Y-m-d H:i:s'),
            'created_by' => $usercode
        ];
        if ($isCircleRequired) {
            $insert['dist_code'] = $input['dist_code'];
            $insert['subdiv_code'] = $subdiv;
            $insert['cir_code'] = $cir;
        }else{
            $insert['dist_code'] = '00';
            $insert['subdiv_code'] = '00';
            $insert['cir_code'] = '00';
        }

        // transaction
        $this->db->trans_begin();
        $new_id = $this->Dataentryuser_model->insert_user($insert);

        if ($this->db->trans_status() === FALSE || !$new_id) {
            $this->db->trans_rollback();
            http_response_code(500);
            echo json_encode([
                'status' => 0,
                'message' => 'Failed to create user'
            ]);
            return;
        }

        $this->db->trans_commit();
        http_response_code(201);
        echo json_encode([
            'status' => 1,
            'message' => 'User created',
            'data' => [
                'id' => (int)$new_id,
                'username' => $insert['username'],
                'name' => $insert['name'],
                'dist_code' => $insert['dist_code']
            ]
        ]);
    }

    /**
     * Callback validator for API flow
     */
    public function _username_unique_api($username)
    {
        if ($this->Dataentryuser_model->username_exists($username)) {

            $this->suggested_username = $this->generate_unique_username_limited($username);

            $this->form_validation->set_message(
                '_username_unique_api',
                'The {field} is already taken. Suggested: ' . $this->suggested_username
            );

            return FALSE;
        }
        return TRUE;
    }

    public function phone_unique($phone)
    {
        $exists = $this->db
            ->where('mobile_no', $phone)
            ->get('dataentryusers')
            ->row();

        if ($exists) {
            $this->form_validation->set_message('phone_unique', 'The {field} already exists.');
            return false;
        }
        return true;
    }

    private function generate_unique_username_limited($base)
    {
        // sanitize and enforce <= 5 characters
        $base = preg_replace('/[^a-zA-Z0-9]/', '', strtolower($base));
        $base = substr($base, 0, 5);

        // If available, return as is
        if (!$this->Dataentryuser_model->username_exists($base)) {
            return $base;
        }

        // Try appending/replacing digits
        for ($i = 1; $i <= 99999; $i++) {
            // create a suffix (1–5 digits)
            $suffix = (string)$i;

            // Trim base to make total length <= 5
            $trimmed = substr($base, 0, 5 - strlen($suffix));

            $candidate = $trimmed . $suffix;

            if (strlen($candidate) <= 5 && !$this->Dataentryuser_model->username_exists($candidate)) {
                return $candidate;
            }
        }

        // fallback (very unlikely)
        return substr($base, 0, 5);
    }

    public function list()
    {
        $user_type  =  $this->jwt_data->usertype;
        // $user = $this->Dataentryuser_model->get_user_by_id($jwt['user_id']);
        // print_r($this->jwt_data->usertype);
        // print(json_decode($this->session->userdata));
        // print(json_decode($this->UserModel::$ADMIN_CODE));
        // Read & sanitize inputs
        $page = (int) $this->input->get('page', TRUE) ?: 1;
        $limit = (int) $this->input->get('limit', TRUE) ?: 10;
        $search = $this->input->get('search', TRUE);
        $sort_by = $this->input->get('sort_by', TRUE) ?: 'id';
        $sort_dir = strtolower($this->input->get('sort_dir', TRUE) ?: 'asc');
        $sort_dir = ($sort_dir === 'desc') ? 'desc' : 'asc';

        // sanitize sort_by to allowed columns (prevent SQL injection)
        $allowed_sort = ['serial_no', 'username', 'name', 'email', 'mobile_no', 'user_role', 'dist_code', 'subdiv_code', 'cir_code', 'designation'];
        if (! in_array($sort_by, $allowed_sort, true)) {
            $sort_by = 'serial_no';
        }

        if ($limit < 1) $limit = 10;
        $offset = ($page - 1) * $limit;
        if ($offset < 0) $offset = 0;

        // Build filters
        $filters = [];
        if ($search) {
            // we'll let the model handle the search safely
            $filters['search'] = $search;
        }

        // total count
        $total = $this->Dataentryuser_model->count_users($filters, $user_type);

        // fetch data
        $users = $this->Dataentryuser_model->get_users_paginated($limit, $offset, $filters, $sort_by, $sort_dir, $user_type);
        // print_r($users);

        // map DB columns to desired json keys
        $data = array_map(function ($u) {
            return [
                'id' => (int)$u->serial_no,
                'username' => $u->username,
                'name' => $u->name,
                'email' => isset($u->email) ? $u->email : null,
                'mobile_no' => $u->mobile_no,
                'role'      => $this->UserModel->getRoleNameFromCode($u->user_role),
                // 'district_code' => $u->dist_code,
                'district' => $this->LocationModel->getDistrict($u->dist_code),
                'circle' => $this->LocationModel->getCircle($u->dist_code, $u->subdiv_code, $u->cir_code),
                // 'subdiv_code' =>  $u->subdiv_code,
                'designation'   =>  $this->Designation_model->get_by_code($u->designation)
                // 'designation'   =>  $u->designation
            ];
        }, $users);

        $response = [
            'data'  => $data,
            'total' => (int)$total,
            'page'  => (int)$page,
            'limit' => (int)$limit
        ];

        echo json_encode($response);
    }

    public function show($id = null)
    {
        header('Content-Type: application/json; charset=utf-8');

        if ($id === null || !ctype_digit((string)$id)) {
            http_response_code(400);
            echo json_encode([
                'status' => 0,
                'message' => 'Invalid user id'
            ]);
            return;
        }

        try {
            $user = $this->Dataentryuser_model->get_user_by_id((int)$id);

            if (!$user) {
                http_response_code(404);
                echo json_encode([
                    'status' => 0,
                    'message' => 'User not found'
                ]);
                return;
            }

            // role name
            $roleName = $this->UserModel->getRoleFullNameFromCode($user->user_role);

            // district object
            $districtInfo = $this->LocationModel->getDistrict(
                $user->dist_code
            );

            // circle object
            $circleInfo = $this->LocationModel->getCircle(
                $user->dist_code,
                $user->subdiv_code,
                $user->cir_code
            );

            // final data format
            $data = [
                'id'        => (int)$user->serial_no,
                'username'  => $user->username,
                'name'      => $user->name,
                'email'     => $user->email ?? null,
                'mobile_no'  => $user->mobile_no,
                'role_name'      => $roleName,
                'role'      => $user->user_role,

                // object, not string
                'district' => $districtInfo,
                'circle'   => $circleInfo
            ];

            echo json_encode([
                'status' => 1,
                'data'   => $data
            ]);
        } catch (Throwable $e) {
            log_message('error', 'UserController::show error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'status' => 0,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Update user (only: name, email, mobile_no, password)
     * Accepts JSON body or form-encoded data.
     * URL: /index.php/api/users/{id}
     */
    public function update($id = null)
    {
        header('Content-Type: application/json; charset=utf-8');
        $usercode  =  $this->jwt_data->usercode;

        // basic id check
        if ($id === null || !ctype_digit((string)$id)) {
            http_response_code(400);
            echo json_encode(['status' => 0, 'message' => 'Invalid user id']);
            return;
        }

        // read JSON body (or fallback to post)
        $raw = trim(file_get_contents("php://input"));
        $data = [];
        if (!empty($raw)) {
            $decoded = json_decode($raw, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $data = $decoded;
            }
        }
        if (empty($data)) {
            $data = $this->input->post();
        }

        // only allow these fields
        $input = [
            'name'     => isset($data['name']) ? $this->security->xss_clean($data['name']) : null,
            'email'    => isset($data['email']) ? $this->security->xss_clean($data['email']) : null,
            'mobile_no' => isset($data['mobile_no']) ? $this->security->xss_clean($data['mobile_no']) : null,
            'password' => isset($data['password']) ? $data['password'] : null, // do NOT xss_clean password
            'district' => isset($data['district']) ? $this->security->xss_clean($data['district']) : null,
            'circle'   => isset($data['circle']) ? $this->security->xss_clean($data['circle']) : null,
            'subdivision'   => isset($data['subdivision']) ? $this->security->xss_clean($data['subdivision']) : null
        ];

        // load form validation & set data
        $this->load->library('form_validation');
        $this->form_validation->reset_validation();
        $this->form_validation->set_data($input);

        // rules: only validate if value provided (optional fields)
        $this->form_validation->set_rules('name', 'Full name', 'trim|max_length[255]');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
        $this->form_validation->set_rules('mobile_no', 'Phone no', 'trim|numeric|exact_length[10]');
        $this->form_validation->set_rules('password', 'Password', 'trim|min_length[6]');

        // run basic validation
        if ($this->form_validation->run() === FALSE) {
            http_response_code(422);
            echo json_encode([
                'status' => 0,
                'message' => 'Validation failed',
                'errors' => $this->form_validation->error_array()
            ]);
            return;
        }

        // load model(s)
        $this->load->model('Dataentryuser_model');

        // check user exists
        $existing = $this->Dataentryuser_model->get_user_by_id((int)$id);
        if (!$existing) {
            http_response_code(404);
            echo json_encode(['status' => 0, 'message' => 'User not found']);
            return;
        }

        // uniqueness checks (exclude current user)
        $errors = [];

        if (!empty($input['email'])) {
            if ($this->Dataentryuser_model->email_exists_except($input['email'], (int)$id)) {
                $errors['email'] = 'Email is already in use.';
            }
        }

        if (!empty($input['mobile_no'])) {
            if ($this->Dataentryuser_model->phone_exists_except($input['mobile_no'], (int)$id)) {
                $errors['mobile_no'] = 'Phone number is already in use.';
            }
        }

        if (!empty($errors)) {
            http_response_code(422);
            echo json_encode(['status' => 0, 'message' => 'Validation failed', 'errors' => $errors]);
            return;
        }

        // prepare update array (only include fields provided)
        $update = [];
        if ($input['name'] !== null)     $update['name'] = $input['name'];
        if ($input['email'] !== null)    $update['email'] = $input['email'];
        if ($input['mobile_no'] !== null) {
            // $update['mobile_no'] = $input['mobile_no'];
            // optional: mirror mobile_no as in create
            $update['mobile_no'] = $input['mobile_no'];
        }
        if (!empty($input['password'])) {
            $update['password'] = password_hash($input['password'], PASSWORD_DEFAULT);
        }
        if (!empty($input['district'])) {
            $update['dist_code'] = $input['district'];
        }
        if (!empty($input['circle'])) {
            $update['cir_code'] = $input['circle'];
        }
        if (!empty($input['subdivision'])) {
            $update['subdiv_code'] = $input['subdivision'];
        }
        if (empty($update)) {
            // nothing to update
            echo json_encode(['status' => 1, 'message' => 'No changes', 'data' => []]);
            return;
        }
        $update['updated_by'] = $usercode;
        $update['updated_on'] = date('Y-m-d H:i:s');

        // perform update inside transaction
        $this->db->trans_begin();
        $ok = $this->Dataentryuser_model->update_user((int)$id, $update);

        if ($this->db->trans_status() === FALSE || !$ok) {
            $this->db->trans_rollback();
            http_response_code(500);
            echo json_encode(['status' => 0, 'message' => 'Failed to update user']);
            return;
        }

        $this->db->trans_commit();

        // return updated user (optional: fetch again)
        $user = $this->Dataentryuser_model->get_user_by_id((int)$id);


        $roleName = $this->UserModel->getRoleNameFromCode($user->user_role);
        $districtInfo = $this->LocationModel->getDistrict($user->dist_code);
        $circleInfo = $this->LocationModel->getCircle($user->dist_code, $user->subdiv_code, $user->cir_code);

        $respData = [
            'id'       => (int)$user->serial_no,
            'username' => $user->username,
            'name'     => $user->name,
            'email'    => $user->email ?? null,
            'mobile_no' => $user->mobile_no,
            'role'     => $roleName,
            'district' => $districtInfo,
            'circle'   => $circleInfo
        ];

        echo json_encode(['status' => 1, 'message' => 'User updated', 'data' => $respData]);
    }


    /**
     * GET /api/designations?role=002
     * Returns designation list based on role code (static array)
     */
    public function designations_by_role()
    {
        header('Content-Type: application/json; charset=utf-8');

        $role = $this->input->get('role', TRUE);

        if (empty($role)) {
            echo json_encode(['status' => 0, 'message' => 'Role code is required', 'data' => []]);
            return;
        }

        // Normalize role: "010" -> "10", "014" -> "14"
        $role = (string)(int)$role;

        $role_map = [
            '10' => ['01', '02', '03', '04', '05', '06', '07', '08', '09'],
            '14' => ['10'],
            '15' => ['10', '11'],
        ];

        if (!isset($role_map[$role]) || empty($role_map[$role])) {
            echo json_encode(['status' => 0, 'data' => []]);
            return;
        }

        $codes = $role_map[$role];


        $rows = $this->Designation_model->get_by_codes($codes);

        $result = array_map(function ($r) {
            return [
                'designation_code' => $r['designation_code'],
                'designation_name' => $r['designation_name']
            ];
        }, $rows);

        echo json_encode(['status' => 1, 'data' => $result]);
    }

    
    public function change_password()
    {
        // Force JSON
        header('Content-Type: application/json; charset=utf-8');

        // Only allow POST (you can relax this if you want)
        if (strtoupper($this->input->method()) !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'status'  => 0,
                'message' => 'Method Not Allowed'
            ]);
            return;
        }

        // Read JSON body if present
        $raw = trim(file_get_contents("php://input"));
        $data = [];
        if (!empty($raw)) {
            $decoded = json_decode($raw, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $data = $decoded;
            }
        }
        // Fallback to $_POST
        if (empty($data)) {
            $data = $this->input->post();
        }

        // Map inputs (do NOT xss_clean passwords)
        $input = [
            'current_password' => isset($data['current_password']) ? $data['current_password'] : null,
            'new_password'     => isset($data['new_password'])     ? $data['new_password']     : null,
            'confirm_password' => isset($data['confirm_password']) ? $data['confirm_password'] : null,
        ];

        // Validation
        $this->form_validation->reset_validation();
        $this->form_validation->set_data($input);

        $this->form_validation->set_rules('current_password', 'Current Password', 'trim|required');
        $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[8]|regex_match[/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^A-Za-z0-9]).+$/]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[new_password]');


        if ($this->form_validation->run() === FALSE) {
            http_response_code(422);
            echo json_encode([
                'status'  => 0,
                'message' => 'Validation failed',
                'errors'  => $this->form_validation->error_array()
            ]);
            return;
        }

        // Get current user identity from JWT (set in __construct)
        $jwt = $this->jwt_data;
        // print_r($jwt);

        $userId   = null;
        $username = null;

        // if (!empty($jwt['user_id']))       $userId   = (int)$jwt['user_id'];
        // elseif (!empty($jwt['id']))        $userId   = (int)$jwt['id'];
        // elseif (!empty($jwt['serial_no'])) $userId   = (int)$jwt['serial_no'];
        // elseif (!empty($jwt['uid']))       $userId   = (int)$jwt['uid'];

        if (!empty($jwt->usercode))      $username = $jwt->usercode;

        // Load user from DB
        $user = null;
        if ($userId) {
            $user = $this->Dataentryuser_model->get_user_by_id($userId);
        } elseif ($username) {
            if (method_exists($this->Dataentryuser_model, 'get_user_by_username')) {
                $user = $this->Dataentryuser_model->get_user_by_username($username);
            }
        }

        if (!$user) {
            http_response_code(404);
            echo json_encode([
                'status'  => 0,
                'message' => 'User not found'
            ]);
            return;
        }

        // Check current password (stored as sha1)
        $currentHash = sha1($input['current_password']);
        if ($currentHash !== $user->password) {
            http_response_code(422);
            echo json_encode([
                'status'  => 0,
                'message' => 'Validation failed',
                'errors'  => [
                    'current_password' => 'Current password is incorrect.'
                ]
            ]);
            return;
        }

        // Prevent reusing same password (optional but recommended)
        $newHash = sha1($input['new_password']);
        if ($newHash === $user->password) {
            http_response_code(422);
            echo json_encode([
                'status'  => 0,
                'message' => 'Validation failed',
                'errors'  => [
                    'new_password' => 'New password must be different from current password.'
                ]
            ]);
            return;
        }

        // Update password
        $update = [
            'password' => $newHash,
            'is_password_changed'=> 1,
            'password_updated_at' => date('Y-m-d H:i:s'),
        ];

        $this->db->trans_begin();
        $ok = $this->Dataentryuser_model->update_user($user->serial_no, $update);

        if ($this->db->trans_status() === FALSE || !$ok) {
            $this->db->trans_rollback();
            http_response_code(500);
            echo json_encode([
                'status'  => 0,
                'message' => 'Failed to change password'
            ]);
            return;
        }

        $this->db->trans_commit();

        http_response_code(200);
        echo json_encode([
            'status'  => 1,
            'message' => 'Password changed successfully'
        ]);
    }


        
}
