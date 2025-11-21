<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserManagementController extends CI_Controller {
    public function __construct()
    {
        // echo "asasa";
        parent::__construct();
        // load model & form_validation
        $this->load->model('Api/Dataentryuser_model');
        $this->load->library('form_validation');
        $this->load->helper(array('url','security'));

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
            'phone_no'    => isset($data['phone_no'])    ? $this->security->xss_clean($data['phone_no'])    : null,
            'email'   => isset($data['email'])   ? $this->security->xss_clean($data['email'])   : null,
        ];

        // Setup validation rules (programmatic)
        $this->form_validation->reset_validation();
        $this->form_validation->set_data($input);

        $this->form_validation->set_rules('dist_code', 'District code', 'trim|required|alpha_numeric');
        // $this->form_validation->set_rules('subdiv_code', 'Subdivision code', 'trim|required|alpha_numeric');
        $this->form_validation->set_rules('cir_code', 'Circle code', 'trim|required|regex_match[/^[0-9\-]+$/]');
        $this->form_validation->set_rules('user_role', 'User role', 'trim|required|alpha_numeric');
        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[3]|max_length[50]|alpha_numeric|callback__username_unique_api');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('name', 'Full name', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('phone_no', 'Phone no', 'trim|required|numeric|exact_length[10]|callback_phone_unique');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

        $parts = explode("-", $input['cir_code']);

        // Result:
        // $dist = $parts[0];   // 17
        $subdiv  = $parts[1];   // 01
        $cir     = $parts[2];   // 01

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
            'dist_code'   => $input['dist_code'],
            'subdiv_code' => $subdiv,
            'cir_code'    => $cir,
            'user_role'   => $input['user_role'],
            'username'    => $input['username'],
            'password'    => password_hash($input['password'], PASSWORD_DEFAULT),
            'name'        => $input['name'],
            'phone_no'    => $input['phone_no'],
            'mobile_no'   => $input['phone_no'],
            'email'   => $input['email'],
            'user_status' => 'E',
            'date_of_creation'  => date('Y-m-d H:i:s'),
        ];

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
            ->where('phone_no', $phone)
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
}
