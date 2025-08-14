<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class InstrumentAssignerController extends CI_Controller
{
    use CommonTrait;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('UserModel');
        // $this->load->helper('security');
    }

    public function index()
    {
        $this->dbswitch('default');
        $query = "SELECT ui.*, deu.name from user_instruments ui left join dataentryusers deu on deu.username=ui.user_code ORDER BY ui.id DESC";
        $data['user_instruments'] = $this->db->query($query)->result_array();
        $data['surveyors'] = $this->db->where('user_role', $this->UserModel::$SURVEYOR_CODE)->get('dataentryusers')->result_array();
        $data['_view'] = 'instrument_assigner/index';
        $this->load->view('layout/layout', $data);
    }

    public function store()
    {
        $this->dbswitch('default');
        $usercode = $this->session->userdata('usercode');
        $error_msg = [];
        $validation = [
                        [
                            'field' => 'user_code',
                            'label' => 'Surveyor',
                            'rules' => 'required'
                        ],
                        [
                            'field' => 'serial_no',
                            'label' => 'Serial No',
                            'rules' => 'required'
                        ],
                        [
                            'field' => 'controller_no',
                            'label' => 'Controller No',
                            'rules' => 'required'
                        ],
                        [
                            'field' => 'assign_date',
                            'label' => 'Assign Date',
                            'rules' => 'required'
                        ],
                    ];
        $this->form_validation->set_rules($validation);
        if ($this->form_validation->run() == FALSE)
        {               
            $this->form_validation->set_error_delimiters('', '');
            foreach($validation as $rule){
                if (form_error($rule['field'])) {
                    $field_name = str_replace("[]", "", $rule['field']);
                    $error_msg[$field_name] = form_error($rule['field']);
                }
            }              
            return response_json(['success' => false, 'errors' => $error_msg], 403);
        }

        $assignee = $this->input->post('user_code');
        $serial_no = $this->input->post('serial_no');
        $controller_no = $this->input->post('controller_no');
        $assigned_date = $this->input->post('assign_date');
        $user_instrument = $this->db->where('user_code', $assignee)->get('user_instruments')->row();
        if($user_instrument){
            return response_json(['success' => false, 'message' => 'Instrument already assigned for this surveyor']);
        }

        $this->db->trans_begin();

        try{
            $data = [
                'serial_no' => $serial_no,
                'controller_no' => $controller_no,
                'assigned_date' => date('Y-m-d H:i:s', strtotime($assigned_date)),
                'user_code' => $assignee,
                'slug' => uniqid(),
                'created_by' => $usercode,
            ];
            $this->db->insert('user_instruments', $data);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                return response_json(['success' => false, 'message' => 'Something went wrong. Please try again later.']);
            }
            $user_instrument_id = $this->db->insert_id();
            
            $this->create_user_instrument_logs($user_instrument_id);
        }catch(Exception $e){
            $this->db->trans_rollback();
            return response_json(['success' => false, 'message' => $e->getMessage()]);
        }

        $this->db->trans_commit();

        return response_json(['success' => true, 'message' => 'Instrument assigned successfully']);
    }

    public function update($user_instrument_id)
    {
        $this->dbswitch('default');
        $user_instrument = $this->db->where('id', $user_instrument_id)->get('user_instruments')->row();
        if(!$user_instrument){
            return response_json(['success' => false, 'message' => 'No such record found']);
        }

        $usercode = $this->session->userdata('usercode');
        $error_msg = [];
        $validation = [
                        [
                            'field' => 'serial_no',
                            'label' => 'Serial No',
                            'rules' => 'required'
                        ],
                        [
                            'field' => 'controller_no',
                            'label' => 'Controller No',
                            'rules' => 'required'
                        ],
                        [
                            'field' => 'assign_date',
                            'label' => 'Assign Date',
                            'rules' => 'required'
                        ],
                        [
                            'field' => 'note',
                            'label' => 'Note',
                            'rules' => 'required'
                        ],
                    ];
        $this->form_validation->set_rules($validation);
        if ($this->form_validation->run() == FALSE)
        {               
            $this->form_validation->set_error_delimiters('', '');
            foreach($validation as $rule){
                if (form_error($rule['field'])) {
                    $field_name = str_replace("[]", "", $rule['field']);
                    $error_msg[$field_name] = form_error($rule['field']);
                }
            }              
            return response_json(['success' => false, 'errors' => $error_msg], 403);
        }

        $note = $this->input->post('note');
        $serial_no = $this->input->post('serial_no');
        $controller_no = $this->input->post('controller_no');
        $assigned_date = $this->input->post('assign_date');

        $this->db->trans_begin();

        try{
            $data = [
                'note' => $note,
                'serial_no' => $serial_no,
                'controller_no' => $controller_no,
                'assigned_date' => date('Y-m-d H:i:s', strtotime($assigned_date)),
                'created_by' => $usercode,
            ];
            $this->db->where('id', $user_instrument_id)->update('user_instruments', $data);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                return response_json(['success' => false, 'message' => 'Something went wrong. Please try again later.']);
            }
            
            $this->create_user_instrument_logs($user_instrument_id);
        }catch(Exception $e){
            $this->db->trans_rollback();
            return response_json(['success' => false, 'message' => $e->getMessage()]);
        }

        $this->db->trans_commit();

        return response_json(['success' => true, 'message' => 'Instrument assign record updated successfully']);
    }

    private function create_user_instrument_logs($user_instrument_id){
        $usercode = $this->session->userdata('usercode');
        $user_instrument = $this->db->where('id', $user_instrument_id)->get('user_instruments')->row();
        $data = [
                    'user_instrument_id' => $user_instrument_id,
                    'user_code' => $user_instrument->user_code,
                    'serial_no' => $user_instrument->serial_no,
                    'controller_no' => $user_instrument->controller_no,
                    'note' => $user_instrument->note,
                    'assigned_date' => date('Y-m-d H:i:s', strtotime($user_instrument->assigned_date)),
                    'slug' => uniqid(),
                    'created_by' => $usercode,
                ];

        $this->db->insert('user_instrument_logs', $data);
        if($this->db->affected_rows() != 1){
            log_message('error', '#ERRINSTRMNTLOG0001: Data not inserted in user_instrument_logs.');
            throw new Exception('#ERRINSTRMNTLOG0001: Something went wrong. Please try again later');
        }
    }
    
}
