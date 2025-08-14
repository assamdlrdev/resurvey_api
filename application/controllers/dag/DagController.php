<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';
class DagController extends CI_Controller
{
    use CommonTrait;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('security');
        $this->load->model('Chithamodel');
        $this->load->model('DagModel');
        $this->load->helper(['form', 'url']);
        $this->load->library('pagination');
    }
    public function dagLocation()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $distcode = $this->session->userdata('dcode');

        $data['districts'] = $this->Chithamodel->districtdetails($distcode);
        if ($this->session->userdata('svill_townprt_code') and $this->session->userdata('current_url') == current_url()) {
            $dist = $this->session->userdata('sdcode');
            $subdiv = $this->session->userdata('ssubdiv_code');
            $circle = (string) $this->session->userdata('scir_code');
            $mza = (string) $this->session->userdata('smouza_pargona_code');
            $lot = (string) $this->session->userdata('slot_no');
            $vill = (string) $this->session->userdata('svill_townprt_code');
            $currentURL = (string) $this->session->userdata('current_url');
            $data['locations'] = $this->Chithamodel->getSessionLoc($dist, $subdiv, $circle, $mza, $lot, $vill);
            $data['current_url'] = $currentURL;
        } else {
            $data['locations'] = null;
            $data['current_url'] = null;
        }
        $data['_view'] = 'dag/set_location';

        $this->load->view('layout/layout', $data);
    }
    public function showDags($is_back = false)
    {
        if (!$is_back) {

            $this->form_validation->set_rules('dist_code', 'District Name', 'trim|integer|required');
            $this->form_validation->set_rules('subdiv_code', 'Sub Division Name', 'trim|integer|required');
            $this->form_validation->set_rules('cir_code', 'Circle Name', 'trim|integer|required');
            $this->form_validation->set_rules('mouza_pargona_code', 'Mouza Name', 'trim|integer|required');
            $this->form_validation->set_rules('lot_no', 'Lot Number', 'trim|integer|required');
            $this->form_validation->set_rules('vill_townprt_code', 'Village Name', 'trim|integer|required');
            if ($this->form_validation->run() == false) {
                $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
                $this->dagLocation();
                return;
            } else {
                $this->dataswitch();
                $dist_code = $this->input->post('dist_code');
                $subdiv_code = $this->input->post('subdiv_code');
                $cir_code = $this->input->post('cir_code');
                $mouza_pargona_code = $this->input->post('mouza_pargona_code');
                $lot_no = $this->input->post('lot_no');
                $vill_townprt_code = $this->input->post('vill_townprt_code');
                $dag_del_dets = [
                    'dist_code' => $dist_code,
                    'subdiv_code' => $subdiv_code,
                    'cir_code' => $cir_code,
                    'mouza_pargona_code' => $mouza_pargona_code,
                    'lot_no' => $lot_no,
                    'vill_townprt_code' => $vill_townprt_code,
                ];

                $this->load->library('user_agent');
                $vill = $this->input->post('vill_townprt_code');
                $this->session->set_userdata('vill_townprt_code', $vill);
                $this->session->set_userdata('current_url', $this->agent->referrer());
                $this->session->set_userdata('sdcode', $dist_code);
                $this->session->set_userdata('ssubdiv_code', $subdiv_code);
                $this->session->set_userdata('scir_code', $cir_code);
                $this->session->set_userdata('smouza_pargona_code', $mouza_pargona_code);
                $this->session->set_userdata('slot_no', $lot_no);
                $this->session->set_userdata('svill_townprt_code', $vill_townprt_code);

                $this->session->set_userdata(array('dag_del_dets' => $dag_del_dets));

                $locationname = $this->Chithamodel->getlocationnames($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code);

                $data['locationname'] = $locationname;
                $data['base'] = $this->config->item('base_url');
                $data['_view'] = 'dag/dags';
                $this->load->view('layout/layout', $data);
            }
        } else {
            $dag_del_dets = $this->session->userdata('dag_del_dets');
            $dist_code = $dag_del_dets['dist_code'];
            $subdiv_code = $dag_del_dets['subdiv_code'];
            $cir_code = $dag_del_dets['cir_code'];
            $mouza_pargona_code = $dag_del_dets['mouza_pargona_code'];
            $lot_no = $dag_del_dets['lot_no'];
            $vill_townprt_code = $dag_del_dets['vill_townprt_code'];
            $locationname = $this->Chithamodel->getlocationnames($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code);

            $data['locationname'] = $locationname;
            $data['base'] = $this->config->item('base_url');
            $data['_view'] = 'dag/dags';
            $this->load->view('layout/layout', $data);
        }
    }
    public function getDags()
    {
        $this->dataswitch();
        $postData = $this->input->post();
        $dags = $this->DagModel->getDags($postData);
        echo json_encode($dags);
    }
    public function deleteDags()
    {
        $this->dataswitch();
        $dag_del_dets = $this->session->userdata('dag_del_dets');
        $dist_code = $dag_del_dets['dist_code'];
        $subdiv_code = $dag_del_dets['subdiv_code'];
        $cir_code = $dag_del_dets['cir_code'];
        $mouza_pargona_code = $dag_del_dets['mouza_pargona_code'];
        $lot_no = $dag_del_dets['lot_no'];
        $vill_townprt_code = $dag_del_dets['vill_townprt_code'];
        $tables = $this->tables_list();
        $dags = $this->input->post('selected_dags');
        try {
            $description = '';
            ini_set('max_execution_time', '0');
            $this->db->trans_start();
            log_message('error', 'dag deletion started by - ' . $this->session->userdata('usercode'));
            foreach ($dags as $dag) {
                foreach ($tables as $table) {
                    $where_query = [
                        'dist_code' => $dist_code,
                        'subdiv_code' => $subdiv_code,
                        'lot_no' => $lot_no,
                        'dag_no' => $dag,
                    ];
                    if ($table['is_cir_code']) {
                        $where_query['cir_code'] = $cir_code;
                    } else if ($table['is_circle_code']) {
                        $where_query['circle_code'] = $cir_code;
                    }
                    if ($table['is_vill_townprt_code']) {
                        $where_query['vill_townprt_code'] = $vill_townprt_code;
                    }
                    if ($table['is_vill_code']) {
                        $where_query['vill_code'] = $vill_townprt_code;
                    }
                    if ($table['is_vt_code']) {
                        $where_query['vt_code'] = $vill_townprt_code;
                    }
                    if ($table['is_vill_town_code']) {
                        $where_query['vill_town_code'] = $vill_townprt_code;
                    }
                    if ($table['is_mp_code']) {
                        $where_query['mp_code'] = $mouza_pargona_code;
                    }
                    if ($table['is_mouza_pargona_code']) {
                        $where_query['mouza_pargona_code'] = $mouza_pargona_code;
                    }
                    if ($table['is_mouza_code']) {
                        $where_query['mouza_code'] = $mouza_pargona_code;
                    }

                    if ($this->db->table_exists($table['name'])) {

                        $delete_count = $this->db->get_where($table['name'], $where_query)->num_rows();
                        log_message('error', 'del ' . $table['name'] . ' - ' . $delete_count);
                        $description = $description . '<p>Removed from table ' . $table['name'] . ' - ' . $delete_count . '</p>';
                        if ($delete_count > 0) {
                            if ($table['name'] == 'c_land_bank_details') {
                                $records = $this->db->get_where('c_land_bank_details', $where_query)->result();
                                if (count($records) > 0) {
                                    foreach ($records as $record) {
                                        $this->db->delete('c_land_bank_encroacher_details', ['c_land_bank_details_id' => $record->id]);
                                        if ($this->db->trans_status() === true) {
                                            $this->db->delete('c_land_bank_details', ['id' => $record->id]);
                                        }
                                    }
                                }
                            } else if ($table['name'] == 'land_bank_details') {
                                $records = $this->db->get_where('land_bank_details', $where_query)->result();
                                if (count($records) > 0) {
                                    foreach ($records as $record) {
                                        $this->db->delete('land_bank_encroacher_details', ['land_bank_details_id' => $record->id]);
                                        if ($this->db->trans_status() === true) {
                                            $this->db->delete('land_bank_details', ['id' => $record->id]);
                                        }
                                    }
                                }
                            } else if ($table['name'] == 't_land_bank_details') {
                                $records = $this->db->get_where('t_land_bank_details', $where_query)->result();
                                if (count($records) > 0) {
                                    foreach ($records as $record) {
                                        $this->db->delete('t_land_bank_encroacher_details', ['t_land_bank_details_id' => $record->id]);
                                        if ($this->db->trans_status() === true) {
                                            $this->db->delete('t_land_bank_details', ['id' => $record->id]);
                                        }
                                    }
                                }
                            } else if ($table['name'] == 'land_share_details') {
                                $records = $this->db->get_where('land_share_details', $where_query)->result();
                                if (count($records) > 0) {
                                    foreach ($records as $record) {
                                        $this->db->delete('land_share_individual_details', ['land_share_details_id' => $record->id]);
                                        if ($this->db->trans_status() === true) {
                                            $this->db->delete('land_share_details', ['id' => $record->id]);
                                        }
                                    }
                                }
                            } else {
                                if ($table['name'] == 'chitha_dag_pattadar') {
                                    $chitha_dag_pattadars = $this->db->get_where('chitha_dag_pattadar', $where_query)->result();
                                    foreach ($chitha_dag_pattadars as $chitha_dag_pattadar) {
                                        $where_query_chitha_dag = [
                                            'dist_code' => $dist_code,
                                            'subdiv_code' => $subdiv_code,
                                            'cir_code' => $cir_code,
                                            'mouza_pargona_code' => $mouza_pargona_code,
                                            'lot_no' => $lot_no,
                                            'vill_townprt_code' => $vill_townprt_code,
                                            'patta_no' => $chitha_dag_pattadar->patta_no,
                                            'patta_type_code' => $chitha_dag_pattadar->patta_type_code,
                                            'pdar_id' => $chitha_dag_pattadar->pdar_id,
                                        ];
                                        $rows_count = $this->db->get_where('chitha_dag_pattadar', $where_query_chitha_dag)->num_rows();
                                        if ($rows_count == 1) {
                                            $this->db->delete('chitha_pattadar', $where_query_chitha_dag);
                                            $where_query_chitha_dag['dag_no'] = $chitha_dag_pattadar->dag_no;
                                            $this->db->delete('chitha_dag_pattadar', $where_query_chitha_dag);
                                        } else {
                                            $where_query_chitha_dag['dag_no'] = $chitha_dag_pattadar->dag_no;
                                            $this->db->delete('chitha_dag_pattadar', $where_query_chitha_dag);
                                        }
                                    }
                                } else {
                                    $this->db->delete($table['name'], $where_query);
                                }
                            }
                        }
                    }
                }
            }
            $this->db->trans_complete();
            $response = ['message' => 'Removed data successfully', 'description' => $description, 'status' => 'success'];
            echo json_encode($response);
            return;
        } catch (Exception $e) {
            $response = ['message' => $e, 'status' => 'failed'];
            echo json_encode($response);
            return;
        }
    }
    public function viewPattadars($dag_no)
    {
        $this->dataswitch();
        $dag_del_dets = $this->session->userdata('dag_del_dets');
        $dist_code = $dag_del_dets['dist_code'];
        $subdiv_code = $dag_del_dets['subdiv_code'];
        $cir_code = $dag_del_dets['cir_code'];
        $mouza_pargona_code = $dag_del_dets['mouza_pargona_code'];
        $lot_no = $dag_del_dets['lot_no'];
        $vill_townprt_code = $dag_del_dets['vill_townprt_code'];
        $dag_details = $this->DagModel->dagDetails($dag_no);
        $locationname = $this->Chithamodel->getlocationnames($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code);
        $locationname['patta_no'] = $dag_details->patta_no;
        $locationname['patta_type'] = $dag_details->patta_type;
        $locationname['dag_no'] = $dag_details->dag_no;

        $pattadar_del_dets = [
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'mouza_pargona_code' => $mouza_pargona_code,
            'lot_no' => $lot_no,
            'vill_townprt_code' => $vill_townprt_code,
            'dag_no' => $dag_details->dag_no,
            'patta_no' => $dag_details->patta_no,
            'patta_type_code' => $dag_details->patta_type_code,
        ];
        $this->session->set_userdata(array('pattadar_del_dets' => $pattadar_del_dets));

        $sql = "select cp.pdar_name,cp.pdar_father,cp.pdar_mother,cp.pdar_id,cp.subdiv_code,cp.dist_code,cp.cir_code,cp.mouza_pargona_code,cp.lot_no,cp.vill_townprt_code,cp.patta_no,cdp.dag_no from chitha_pattadar cp  join chitha_dag_pattadar cdp on cp.dist_code=cdp.dist_code  and cp.subdiv_code=cdp.subdiv_code and cp.cir_code=cdp.cir_code
	            and cp.mouza_pargona_code=cdp.mouza_pargona_code and cp.lot_no=cdp.lot_no and cp.vill_townprt_code=cdp.vill_townprt_code and cp.patta_type_code=cdp.patta_type_code and trim(cp.patta_no)=trim(cdp.patta_no) and cdp.pdar_id=cp.pdar_id where dag_no='$dag_no' and trim(cp.patta_no)=trim('$dag_details->patta_no') and cp.pdar_id=cdp.pdar_id and cp.patta_type_code='$dag_details->patta_type_code' and cp.dist_code='$dist_code' and cp.subdiv_code='$subdiv_code' and cp.cir_code='$cir_code' and cp.mouza_pargona_code='$mouza_pargona_code' and cp.lot_no='$lot_no' and cp.vill_townprt_code='$vill_townprt_code'";
        $pattadars = $this->db->query($sql)->result();
        $data['pattadars'] = $pattadars;
        $data['locationname'] = $locationname;
        $data['base'] = $this->config->item('base_url');
        $data['_view'] = 'dag/pattadars';
        $this->load->view('layout/layout', $data);
    }
    public function deletePattadars()
    {
        $this->dataswitch();
        $pattadar_del_dets = $this->session->userdata('pattadar_del_dets');
        $dist_code = $pattadar_del_dets['dist_code'];
        $subdiv_code = $pattadar_del_dets['subdiv_code'];
        $cir_code = $pattadar_del_dets['cir_code'];
        $mouza_pargona_code = $pattadar_del_dets['mouza_pargona_code'];
        $lot_no = $pattadar_del_dets['lot_no'];
        $vill_townprt_code = $pattadar_del_dets['vill_townprt_code'];
        $patta_no = $pattadar_del_dets['patta_no'];
        $patta_type_code = $pattadar_del_dets['patta_type_code'];
        $dag_no = $pattadar_del_dets['dag_no'];

        $pattadar_ids = $this->input->post('selected_pattadars');
        try {
            ini_set('max_execution_time', '0');
            $this->db->trans_start();
            log_message('error', 'pattadar deletion started by - ' . $this->session->userdata('usercode'));
            foreach ($pattadar_ids as $pdar_id) {
                $where_query = [
                    'dist_code' => $dist_code,
                    'subdiv_code' => $subdiv_code,
                    'cir_code' => $cir_code,
                    'mouza_pargona_code' => $mouza_pargona_code,
                    'lot_no' => $lot_no,
                    'vill_townprt_code' => $vill_townprt_code,
                    'patta_no' => $patta_no,
                    'patta_type_code' => $patta_type_code,
                    'pdar_id' => $pdar_id,
                ];

                $rows_count = $this->db->get_where('chitha_dag_pattadar', $where_query)->num_rows();
                if ($rows_count == 1) {
                    $this->db->delete('chitha_pattadar', $where_query);
                    $where_query['dag_no'] = $dag_no;
                    $this->db->delete('chitha_dag_pattadar', $where_query);
                } else {
                    $where_query['dag_no'] = $dag_no;
                    $this->db->delete('chitha_dag_pattadar', $where_query);
                }
            }
            $this->db->where([
                'dist_code' => $dist_code,
                'subdiv_code' => $subdiv_code,
                'cir_code' => $cir_code,
                'mouza_pargona_code' => $mouza_pargona_code,
                'lot_no' => $lot_no,
                'vill_townprt_code' => $vill_townprt_code,
                'dag_no' => $dag_no,
            ]);
            $this->db->update('chitha_basic', ['jama_yn' => 'n']);
            $this->db->trans_complete();
            $response = ['message' => 'Deleted Pattadars successfully', 'status' => 'success'];
            echo json_encode($response);
            return;
        } catch (Exception $e) {
            $response = ['message' => $e, 'status' => 'failed'];
            echo json_encode($response);
            return;
        }
    }
    public function tables()
    {
        $this->dataswitch();
        try {
            $ts = $this->db->list_tables();
            sort($ts);
            $tables = [];
            foreach ($ts as $table) {
                if (($this->db->field_exists('vill_townprt_code', $table) || $this->db->field_exists('vill_code', $table) || $this->db->field_exists('vt_code', $table) || $this->db->field_exists('vill_town_code', $table)) && $this->db->field_exists('dag_no', $table)) {
                    $t['is_cir_code'] = $this->db->field_exists('cir_code', $table) ? true : false;
                    $t['is_circle_code'] = $this->db->field_exists('circle_code', $table) ? true : false;
                    $t['is_vill_townprt_code'] = $this->db->field_exists('vill_townprt_code', $table) ? true : false;
                    $t['is_vill_code'] = $this->db->field_exists('vill_code', $table) ? true : false;
                    $t['is_vt_code'] = $this->db->field_exists('vt_code', $table) ? true : false;
                    $t['is_vill_town_code'] = $this->db->field_exists('vill_town_code', $table) ? true : false;
                    $t['is_mp_code'] = $this->db->field_exists('mp_code', $table) ? true : false;
                    $t['is_mouza_pargona_code'] = $this->db->field_exists('mouza_pargona_code', $table) ? true : false;
                    $t['is_mouza_code'] = $this->db->field_exists('mouza_code', $table) ? true : false;
                    $t['name'] = $table;
                    $tables[] = $t;
                }
            }
            return $tables;
        } catch (\Throwable $th) {
            return $th;
        }
    }
    public function tables_list()
    {
        return [
            ["name" => "allotment_pet_dag", "is_cir_code" => false, "is_circle_code" => true, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "alot_petitioner_other_lands", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => false, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => true, "is_mp_code" => false, "is_mouza_pargona_code" => false, "is_mouza_code" => true],
            ["name" => "apcancel_dag_details", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "apcancel_petition_pattadar", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "apt_chitha_rmk_ordbasic", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "apt_chitha_rmk_other", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "backlog_orders", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "cert_dag_details", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "change_chitha_basic", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "change_chitha_col8_order", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "chitha_acho_hist", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "chitha_basic_entry", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "chitha_col8_inplace", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "chitha_col8_occup", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "chitha_col8_order", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "chitha_col8_tenant", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "chitha_dag_pattadar", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "chitha_dag_pattadar_entry", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "chitha_fruit", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "chitha_mcrop", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "chitha_noncrop", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "chitha_pattadar_family", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "chitha_pattadar_view", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "chitha_reservation_vgr", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "chitha_rmk_allottee", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "chitha_rmk_alongwith", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "chitha_rmk_convorder", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "chitha_rmk_encro", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "chitha_rmk_gen", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "chitha_rmk_infavor_of", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "chitha_rmk_inplace_of", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "chitha_rmk_lmnote", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "chitha_rmk_onbehalf", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "chitha_rmk_ordbasic", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "chitha_rmk_other_opp_party", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "chitha_rmk_reclassification", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "chitha_rmk_sknote", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "chitha_settlement_allottee", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "chitha_subtenant", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "chitha_tenant", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "chitha_basic", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "current_doul_demand", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "dagwise_zone_info", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => false, "is_vill_code" => true, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "dashboard_data", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "edit_jama_dag", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "edit_jama_pattadar", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "field_mut_dag_details", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "field_mut_objection", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => false, "is_vill_code" => false, "is_vt_code" => true, "is_vill_town_code" => false, "is_mp_code" => true, "is_mouza_pargona_code" => false, "is_mouza_code" => false],
            ["name" => "field_mut_pattadar", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "field_part_petitioner", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "jama_dag", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "jama_dag_doul", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "jama_wasil", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "jama_wasil_payee_list", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "jama_wasil_transaction", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "khatian", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "misc_case_basic", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "nc_village_dags", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "patta_basic_dag", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "petition_bo_note", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "petition_dag_details", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "petition_lm_note", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "petition_pattadar", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "petitioner_part", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "settlement_additional_property", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "settlement_applicant", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "settlement_applicant_delete", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "settlement_dag_details", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "settlement_dag_details_deleted", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "settlement_reservation", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "sro_note", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "t_chitha_col8_inplace", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "t_chitha_col8_occup", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "t_chitha_col8_order", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "t_chitha_rmk_allottee", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "t_chitha_rmk_alongwith", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "t_chitha_rmk_convorder", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "t_chitha_rmk_infavor_of", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "t_chitha_rmk_inplace_of", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "t_chitha_rmk_onbehalf", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "t_chitha_rmk_ordbasic", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "t_chitha_rmk_other_opp_party", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "t_land_share_details", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "t_legacyupdation", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "t_reclassification", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "temp_khatian", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "temp_mutation_land_area", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => false, "is_vill_code" => true, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "temp_mutation_pattadar_info", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => false, "is_vill_code" => true, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "trace_map", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "c_land_bank_details", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "t_land_bank_details", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "land_bank_details", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
            ["name" => "land_share_details", "is_cir_code" => true, "is_circle_code" => false, "is_vill_townprt_code" => true, "is_vill_code" => false, "is_vt_code" => false, "is_vill_town_code" => false, "is_mp_code" => false, "is_mouza_pargona_code" => true, "is_mouza_code" => false],
        ];
    }
}
