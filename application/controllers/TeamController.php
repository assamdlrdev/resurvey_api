<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class TeamController extends CI_Controller
{
    use CommonTrait;
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');

        $this->load->model('UserModel');
        // $this->load->helper('security');
    }

    public function index()
    {
        $this->dbswitch('default');
        $supervisor_role = $this->UserModel::$SUPERVISOR_CODE;
        $data['supervisors'] = $this->db->where('user_role', $supervisor_role)->get('dataentryusers')->result_array();
        $data['surveyors'] = $this->db->where('user_role', $this->UserModel::$SURVEYOR_CODE)->get('dataentryusers')->result_array();
        $teams = $this->db->order_by('id', 'DESC')->get('teams')->result_array();
        if (count($teams)) {
            foreach ($teams as $key => $team) {
                $sql = "SELECT tm.*, deu.name, deu.user_role FROM team_members tm left join dataentryusers deu on tm.user_code = deu.username where tm.team_id=? order by deu.user_role";
                $team_members = $this->db->query($sql, array($team['id']))->result_array();
                $teams[$key]['team_members'] = $team_members;
            }
        }
        $data['supervisor_role'] = $supervisor_role;
        $data['teams'] = $teams;
        $data['_view'] = 'team/index';
        $this->load->view('layout/layout', $data);
    }

    public function store()
    {
        $this->dbswitch('default');
        $usercode = $this->session->userdata('usercode');
        $error_msg = [];
        $validation = [
                        [
                            'field' => 'name',
                            'label' => 'Team name',
                            'rules' => 'required'
                        ],
                        [
                            'field' => 'supervisor',
                            'label' => 'Supervisor',
                            'rules' => 'required'
                        ],
                        [
                            'field' => 'surveyor[]',
                            'label' => 'Surveyor',
                            'rules' => 'required'
                        ],
                    ];
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

        $supervisor = $this->input->post('supervisor');
        $surveyors = $this->input->post('surveyor');
        foreach($surveyors as $surveyor){
            $query1 = "SELECT t.*, deu.name as username from teams t left join team_members tm on t.id = tm.team_id join dataentryusers deu on deu.username = ? where tm.user_code=?";
            $check_team = $this->db->query($query1, array($surveyor, $surveyor))->row();
            if($check_team){
                $message = "\"" . $check_team->username . "\" is already associated with \"" . $check_team->name . "\"";
                return response_json(['success' => false, 'message' => $message]);
            }
        }

        $this->db->trans_begin();

        try{
            $data = [
                'name' => $this->input->post('name'),
                'slug' => uniqid(),
                'created_by' => $usercode,
            ];
            $this->db->insert('teams', $data);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                return response_json(['success' => false, 'message' => 'Something went wrong. Please try again later.']);
            }
            $team_id = $this->db->insert_id();
            
            $supervisor_row = $this->db->where('username', $supervisor)->get('dataentryusers')->row();
            $team_members_data[] = [
                'team_id' => $team_id,
                'user_code' => $supervisor_row->username,
                // 'dataentryuser_serial_no' => $this->input->post('supervisor')
            ];
            // foreach ($supervisors as $supervisor) {
            //     $supervisor_row = $this->db->where('username', $supervisor)->get('dataentryusers')->row();
            //     $team_members_data[] = [
            //         'team_id' => $team_id,
            //         'user_code' => $supervisor_row->username,
            //         // 'dataentryuser_serial_no' => $surveyor
            //     ];
            // }

            foreach ($surveyors as $surveyor) {
                $surveyor_row = $this->db->where('username', $surveyor)->get('dataentryusers')->row();
                $team_members_data[] = [
                    'team_id' => $team_id,
                    'user_code' => $surveyor_row->username,
                    // 'dataentryuser_serial_no' => $surveyor
                ];
            }

            $this->db->insert_batch('team_members', $team_members_data);
            if ($this->db->affected_rows() < 1) {
                $this->db->trans_rollback();
                return response_json(['success' => false, 'message' => 'Something went wrong. Please try again later.']);
            }
            $this->create_team_logs($team_id);
        }catch(Exception $e){
            $this->db->trans_rollback();
            return response_json(['success' => false, 'message' => $e->getMessage()]);
        }

        $this->db->trans_commit();

        return response_json(['success' => true, 'message' => 'Team has been created successfully']);
    }

    public function update($team_id)
    {
        $this->dbswitch('default');
        if(!$team = $this->db->where('id', $team_id)->get('teams')->row()){
            return response_json(['success' => false, 'message' => 'No such team found']);
        }
        
        $error_msg = [];
        $validation = [
                        [
                            'field' => 'name',
                            'label' => 'Team name',
                            'rules' => 'required'
                        ],
                        [
                            'field' => 'supervisor',
                            'label' => 'Supervisor',
                            'rules' => 'required'
                        ],
                        [
                            'field' => 'surveyor[]',
                            'label' => 'Surveyor',
                            'rules' => 'required'
                        ],
                    ];
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
        $supervisor = $this->input->post('supervisor');
        $surveyors = $this->input->post('surveyor');
        $team_id = $team->id;
        foreach($surveyors as $surveyor){
            $query1 = "SELECT t.*, deu.name as username from teams t left join team_members tm on t.id = tm.team_id join dataentryusers deu on deu.username = ? where tm.user_code=? and tm.team_id != ?";
            $check_team = $this->db->query($query1, array($surveyor, $surveyor, $team_id))->row();
            if($check_team){
                $message = "\"" . $check_team->username . "\" is already associated with \"" . $check_team->name . "\"";
                return response_json(['success' => false, 'message' => $message]);
            }
        }
        $supervisor_role_code = $this->UserModel::$SUPERVISOR_CODE;
        $surveyor_role_code = $this->UserModel::$SURVEYOR_CODE;
        $query1 = "SELECT deu.* from dataentryusers deu join team_members tm on tm.user_code=deu.username where tm.team_id=? and deu.user_role=?";
        $existing_supervisor = $this->db->query($query1, array($team_id, $supervisor_role_code))->row();
        if($existing_supervisor->username != $supervisor){
            $is_assigned_surveyors = $this->db->where('team_id', $team_id)->get('surveyor_villages')->row();
            if($is_assigned_surveyors){
                return response_json(['success' => false, 'message' => 'You cannot change supervisor as he/she has assigned village(s) surveyor(s)']);
            }
        }

        $query2 = "SELECT deu.username, deu.name from dataentryusers deu join team_members tm on deu.username=tm.user_code where tm.user_code!=? and tm.team_id=?";
        $existing_surveyors = $this->db->query($query2, array($existing_supervisor->username, $team_id))->result_array();
        foreach($existing_surveyors as $existing_surveyor) {
            if(!in_array($existing_surveyor['username'], $surveyors)){
                // If getting removing request for surveyor. Then system needs to check whether the surveyor had been assigned to any villages for this team or not
                $is_used = $this->db->where('team_id', $team_id)->where('surveyor_code', $existing_surveyor['username'])->get('surveyor_villages')->row();
                if($is_used){
                    return response_json(['success' => false, 'message' => 'You cannot remove "' . $existing_surveyor['name'] . '" from this team as village has already been assigned to him/her']);
                }
            }
        }

        $this->db->trans_begin();
        try{
            $data = [
                'name' => $this->input->post('name')
            ];
            $this->db->where('id', $team_id)->update('teams', $data);
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                return response_json(['success' => false, 'message' => 'Something went wrong. Please try again later.']);
            }
            
            $memberIds = $surveyors;
            // $memberIds = array_merge($memberIds, $supervisors);
            array_push($memberIds, $supervisor);
            
            foreach ($memberIds as $member) {
                $member_row = $this->db->where('username', $member)->get('dataentryusers')->row();
                if($member_row){
                    $team_member = $this->db->where('team_id', $team_id)->where('user_code', $member)->get('team_members')->row();
                    if(!$team_member){
                        $team_member_data = [
                                                'team_id' => $team_id,
                                                'user_code' => $member_row->username,
                                                // 'dataentryuser_serial_no' => $member
                                            ];
                        $this->db->insert('team_members', $team_member_data);
                    }
                }else{
                    $this->db->trans_rollback();
                    return response_json(['success' => false, 'message' => 'Something went wrong. Please try again later']);
                }
            }

            $this->db->where('team_id', $team_id)->where_not_in('user_code', $memberIds)->delete('team_members');
            
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return response_json(['success' => false, 'message' => 'Something went wrong. Please try again later.']);
            }
            $this->create_team_logs($team_id);
        }catch(Exception $e){
            $this->db->trans_rollback();
            return response_json(['success' => false, 'message' => $e->getMessage()]);
        }

        $this->db->trans_commit();

        return response_json(['success' => true, 'message' => 'Team has been updated successfully']);
    }

    public function destroy($team_id)
    {
        $this->dbswitch('default');
        if(!$team = $this->db->where('id', $team_id)->get('teams')->row()){
            return response_json(['success' => false, 'message' => 'No such team found']);
        }
        $team_id = $team->id;
        $is_used = $this->db->where('team_id', $team_id)->get('surveyor_villages')->row();
        if($is_used){
            return response_json(['success' => false, 'message' => 'You cannot delete this team as it has records for surveyors']);
        }

        $this->db->trans_begin();
        
        $this->db->where('id', $team_id)->delete('teams');
        if ($this->db->affected_rows() != 1) {
            $this->db->trans_rollback();
            return response_json(['success' => false, 'message' => 'Something went wrong. Please try again later.']);
        }
        
        $this->db->where('team_id', $team_id)->delete('team_members');
        if ($this->db->affected_rows() < 1) {
            $this->db->trans_rollback();
            return response_json(['success' => false, 'message' => 'Something went wrong. Please try again later.']);
        }
        
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return response_json(['success' => false, 'message' => 'Something went wrong. Please try again later.']);
        }

        $this->db->trans_commit();

        return response_json(['success' => true, 'message' => 'Team has been deleted successfully']);
    }

    private function create_team_logs($team_id){
        $usercode = $this->session->userdata('usercode');
        $team = $this->db->where('id', $team_id)->get('teams')->row_array();
        $team_members = $this->db->where('team_id', $team_id)->get('team_members')->result_array();

        $team_log_data = [
            'team_id' => $team_id,
            'name' => $team['name'],
            'slug' => uniqid(),
            'created_by' => $usercode,
        ];
        $this->db->insert('team_logs', $team_log_data);
        if($this->db->affected_rows() != 1){
            log_message('error', '#ERRTMLOG0001: Data not inserted in team_logs.');
            throw new Exception('#ERRTMLOG0001: Something went wrong. Please try again later');
        }
        $team_log_id = $this->db->insert_id();

        for($i=0; $i < count($team_members); $i++){
            unset($team_members[$i]['id'], $team_members[$i]['team_id'], $team_members[$i]['created_at'], $team_members[$i]['updated_at']);
            $team_members[$i]['team_log_id'] = $team_log_id;
        }
        $this->db->insert_batch('team_member_logs', $team_members);

        if($this->db->affected_rows() < 1){
            log_message('error', '#ERRTMLOG0002: Data not inserted in team_member_logs.');
            throw new Exception('#ERRTMLOG0002: Something went wrong. Please try again later');
        }
    }

    public function myteams()
    {
        $this->dbswitch('default');
        $user_role = $this->session->userdata('usertype');
        $user_code = $this->session->userdata('usercode');
        $supervisor_role = $this->UserModel::$SUPERVISOR_CODE;
        $query1 = "SELECT t.* FROM teams t JOIN team_members tm on t.id=tm.team_id where tm.user_code=?";
        $teams = $this->db->query($query1, array($user_code))->result_array();
        if (count($teams)) {
            foreach ($teams as $key => $team) {
                $sql = "SELECT tm.*, deu.name, deu.user_role FROM team_members tm left join dataentryusers deu on tm.user_code = deu.username where tm.team_id=? order by deu.user_role";
                $team_members = $this->db->query($sql, array($team['id']))->result_array();
                $teams[$key]['team_members'] = $team_members;
            }
        }
        $data['auth_user_code'] = $user_code;
        $data['can_check_progress'] = $user_role == $supervisor_role ? true : false;
        $data['supervisor_role'] = $supervisor_role;
        $data['teams'] = $teams;
        $data['_view'] = 'team/my-team';
        $this->load->view('layout/layout', $data);
    }
}
