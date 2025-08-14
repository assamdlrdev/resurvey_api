<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class DataController extends CI_Controller
{
    use CommonTrait;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('UserModel');
        if (!in_array($this->session->userdata('usertype'), [$this->UserModel::$ADMIN_CODE, $this->UserModel::$SUPERADMIN_CODE])) {
            show_error('<svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><g fill="none" stroke="#FF0000" stroke-linecap="round" stroke-width="2"><path d="M12 9v5m0 3.5v.5"/><path stroke-linejoin="round" d="M2.232 19.016L10.35 3.052c.713-1.403 2.59-1.403 3.302 0l8.117 15.964C22.45 20.36 21.544 22 20.116 22H3.883c-1.427 0-2.334-1.64-1.65-2.984Z"/></g></svg> <p>Unauthorized access</p>', "403");
        }
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('VillageDataModel');
        $this->load->model('Chithamodel');
    }

    public function selectLocation()
    {
        // $data['districts'] = $this->VillageDataModel::$DISTRICTS;
        $data['districts'] = $this->Chithamodel->districtdetailsreport();
        $data['base'] = $this->config->item('base_url');
        $data['_view'] = 'data_manage/select_village';
        $this->load->view('layout/layout', $data);
    }

    public function getDbDetails()
    {
        $dist_code = $this->input->post('dist_code');
        $this->dbswitch($dist_code);
        $msg = 'Please confirm to  proceed ?';
        $msg = $msg . '<p>Host ' . $this->db->hostname . '</p>';
        $msg = $msg . '<p>Database : ' . $this->db->database . '</p>';
        $msg = $msg . '<h5> Tables to remove data from </h5>';
        foreach ($this->VillageDataModel::$TABLES as $table) {
            $msg = $msg . '<h6>' . $table . '</h6>';
        }
        echo json_encode($msg);
    }

    public function getSubDivisions()
    {
        $data = [];
        $dist_code = $this->input->post('dist_code');
        $this->dbswitch($dist_code);
        $sub_divisions = $this->db->get_where('location', ['dist_code' => $dist_code, 'subdiv_code !=' => '00', 'cir_code' => '00', 'mouza_pargona_code' => '00', 'vill_townprt_code' => '00000', 'lot_no' => '00'])->result();
        foreach ($sub_divisions as $value) {
            $data[] = $value;
        }
        echo json_encode($data);
    }

    public function getCircles()
    {
        $data = [];
        $dist_code = $this->input->post('dist_code');
        $this->dbswitch($dist_code);
        $subdiv_code = $this->input->post('subdiv_code');
        $circles = $this->db->get_where('location', ['dist_code' => $dist_code, 'subdiv_code' => $subdiv_code, 'cir_code !=' => '00', 'mouza_pargona_code' => '00', 'vill_townprt_code' => '00000', 'lot_no' => '00'])->result();
        $data = [];
        foreach ($circles as $value) {
            $data[] = $value;
        }
        echo json_encode($data);
    }
    public function getMouzas()
    {
        $data = [];
        $dist_code = $this->input->post('dist_code');
        $this->dbswitch($dist_code);
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');

        $mouzas = $this->db->get_where('location', ['dist_code' => $dist_code, 'subdiv_code' => $subdiv_code, 'cir_code' => $cir_code, 'mouza_pargona_code !=' => '00', 'vill_townprt_code' => '00000', 'lot_no' => '00'])->result();
        $data = [];
        foreach ($mouzas as $value) {
            $data[] = $value;
        }
        echo json_encode($data);
    }
    public function getLotNos()
    {
        $data = [];
        $dist_code = $this->input->post('dist_code');
        $this->dbswitch($dist_code);
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');

        $lots = $this->db->get_where('location', ['dist_code' => $dist_code, 'subdiv_code' => $subdiv_code, 'cir_code' => $cir_code, 'mouza_pargona_code' => $mouza_pargona_code, 'vill_townprt_code' => '00000', 'lot_no !=' => '00'])->result();
        $data = [];
        foreach ($lots as $value) {
            $data[] = $value;
        }
        echo json_encode($data);
    }
    public function getVillages()
    {
        $data = [];
        $dist_code = $this->input->post('dist_code');
        $this->dbswitch($dist_code);
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');

        $villages = $this->db->get_where('location', ['dist_code' => $dist_code, 'subdiv_code' => $subdiv_code, 'cir_code' => $cir_code, 'mouza_pargona_code' => $mouza_pargona_code, 'vill_townprt_code !=' => '00000', 'lot_no' => $lot_no])->result();
        $data = [];
        foreach ($villages as $value) {
            $data[] = $value;
        }
        echo json_encode($data);
    }
    public function startProcess()
    {
        $this->form_validation->set_rules('dist_code', 'District', 'required|trim|integer');
        $this->form_validation->set_rules('subdiv_code', 'Sub-division', 'required|trim|integer');
        $this->form_validation->set_rules('cir_code', 'Circle', 'required|trim|integer');
        $this->form_validation->set_rules('mouza_pargona_code', 'Mouza', 'required|trim|integer');
        $this->form_validation->set_rules('lot_no', 'Lot No', 'required|trim|integer');
        $this->form_validation->set_rules('vill_townprt_code', 'Village', 'required|trim|integer');
        $this->form_validation->set_rules('password', 'Password', 'required');
        if ($this->form_validation->run() == FALSE) {
            $response = ['message' => validation_errors(), 'status' => $this->VillageDataModel::$VALIDATION_ERROR];
            echo json_encode($response);
            return;
        } else {
            if ($this->input->post('password') != $this->VillageDataModel::$PW) {
                $response = ['message' => 'Incorrect Password', 'status' => $this->PortingModel::$INCORRECT_PW];
                echo json_encode($response);
                return;
            }
            $dist_code = $this->input->post('dist_code');
            $this->dbswitch($dist_code);
            $subdiv_code = $this->input->post('subdiv_code');
            $cir_code = $this->input->post('cir_code');
            $mouza_pargona_code = $this->input->post('mouza_pargona_code');
            $lot_no = $this->input->post('lot_no');
            $vill_townprt_code = $this->input->post('vill_townprt_code');
            try {
                $description = '';
                ini_set('max_execution_time', '0');
                $this->db->trans_start();
                log_message('error', 'deletion started by - ' . $this->session->userdata('usercode'));
                foreach ($this->VillageDataModel::$TABLES as $table) {
                    if ($this->db->table_exists($table)) {
                        $delete_count =  $this->db->get_where($table, ['dist_code' => $dist_code, 'subdiv_code' => $subdiv_code, 'cir_code' => $cir_code, 'mouza_pargona_code' => $mouza_pargona_code, 'lot_no' => $lot_no, 'vill_townprt_code' => $vill_townprt_code])->num_rows();
                        log_message('error', 'del ' . $table . ' - ' . $delete_count);
                        $description = $description . '<p>Removed from table ' . $table . ' - ' . $delete_count . '</p>';
                        if ($delete_count > 0) {
                            $this->db->delete($table, ['dist_code' => $dist_code, 'subdiv_code' => $subdiv_code, 'cir_code' => $cir_code, 'mouza_pargona_code' => $mouza_pargona_code, 'lot_no' => $lot_no, 'vill_townprt_code' => $vill_townprt_code]);
                        }
                    } else {
                        log_message('error', 'table ' . $table . ' does not exist');
                    }
                }
                $this->db->trans_complete();
                $response = ['message' => 'Removed data successfully', 'description' => $description, 'status' => $this->VillageDataModel::$SUCCESS];
                echo json_encode($response);
                return;
            } catch (Exception $e) {
                $response = ['message' => $e, 'status' => $this->VillageDataModel::$UNKNOWN_ERROR];
                echo json_encode($response);
                return;
            }
        }
    }
   
}
