<?php

include APPPATH . '/libraries/CommonTrait.php';

class PortDharDataController extends CI_Controller
{
    use CommonTrait;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('nc_village/PortingModel');
        $this->load->model('UserModel');
    }
    public function index()
    {
        $data['districts'] = $this->db->get_where('location', array('subdiv_code' => '00', 'cir_code' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->result_array();
        $data['_view'] = 'utility/port_dhar_data/port_dhar_data';
        $this->load->view('layout/layout', $data);
    }
    public function villagedetails()
    {
        $data = [];
        $dis = $this->input->post('dis');
        $this->dbswitch($dis);
        $subdiv = $this->input->post('subdiv');
        $cir = $this->input->post('cir');
        $mza = $this->input->post('mza');
        $lot = $this->input->post('lot');
        $formdata = $this->Chithamodel->villagedetails($dis, $subdiv, $cir, $mza, $lot);
        $db_loc_master = $this->UserModel->connectLocmaster();
        foreach ($formdata as $value) {
            $vill_townprt_code = $value['vill_townprt_code'];
            $last_port = $db_loc_master->query("select * from dhar_porting_log where dist_code=? 
            and subdiv_code=? and cir_code=? and mouza_pargona_code=? and lot_no=? and 
            vill_townprt_code=? and type='dhar_chitha_data' order by created_at desc", [$dis, $subdiv, $cir, $mza, $lot, $vill_townprt_code])->row();
            if ($last_port) {
                $value['last_port'] = $last_port;
            }
            $data[] = $value;
        }
        echo json_encode($data);
    }
    public function portDataFromDharitree()
    {
        $dist_code = $this->input->post('dist_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $vill_townprt_code = $this->input->post('vill_townprt_code');
        $send_to_diff_vill = $this->input->post('send_to_diff_vill');
        if ($send_to_diff_vill == 'Y') {
            $vill_townprt_code_to = $this->input->post('vill_townprt_code_to');
            if (!$vill_townprt_code_to) {
                echo json_encode(['st' => 'failed', 'msgs' => '<p style="color:red;">Please select the destination village.</p>']);
            }
        }
        
        $port_msgs = '<p style="color:green;"><h6>Porting data from dhar started</h6></p>';

        $db_loc_master = $this->UserModel->connectLocmaster();
        $data = [
            'status' => '',
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'mouza_pargona_code' => $mouza_pargona_code,
            'lot_no' => $lot_no,
            'vill_townprt_code' => $vill_townprt_code,
            'user_code' => $this->session->userdata('usercode'),
            'created_at' => date('Y-m-d H:i:s'),
            'type' => $this->PortingModel::$PORT_TYPE_CHITHA_FROM_DHAR
        ];

        $db_loc_master->insert('dhar_porting_log', $data);
        $last_id = $db_loc_master->insert_id();

        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '10240M');
        $api_data = [
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'mouza_pargona_code' => $mouza_pargona_code,
            'lot_no' => $lot_no,
            'vill_townprt_code' => $vill_townprt_code
        ];
        if ($send_to_diff_vill == 'Y') {
            $api_data['send_to_diff_vill'] = 'Y';
            $api_data['vill_townprt_code_to'] = $vill_townprt_code_to;
        }
        else {
            $api_data['send_to_diff_vill'] = 'N';
        }
        if(isset($_POST['import_tables']) && !empty($_POST['import_tables'])) {
            $tables_to_be_imported = $this->input->post('import_tables');
            $api_data['import_tables'] = $tables_to_be_imported;
        }
        else {
            $api_data['import_tables'] == null;
        }
        
        $response = callLandhubAPI2(
            'POST',
            'getVillageData',
            $api_data
        );
        if ($response) {
            if ($response->st == 'success') {
                $this->dbswitch($dist_code);
                $tables = $response->data;
                foreach ($tables as $table) {
                    if ($this->db->table_exists($table->table_name)) {
                        $is_exists = $this->db->get_where($table->table_name, (array)$table->where_query)->num_rows();
                        if ($is_exists != 0) {
                            log_message('error', 'Dhar porting - Data already exists on table ' . $table->table_name . ' - ' . $is_exists);
                            $port_msgs = $port_msgs . '<small style="color:orange;">Data already exists on table ' . $table->table_name . ' - ' . $is_exists . '</small></br>';
                        } else {
                            if ($table->table_name == 'c_land_bank_details') {
                                $data = $table->table_data;
                                if (count($data) > 0) {
                                    $inserted_c_lb = 0;
                                    $inserted_c_lb_enc = 0;
                                    $exists = 0;
                                    foreach ($data as $c_land_bank_data) {
                                        $c_land_bank_details = (array)$c_land_bank_data->c_land_bank_details;
                                        unset($c_land_bank_details['id']);
                                        $c_land_bank_encroacher_details = $c_land_bank_data->c_land_bank_encroacher_details;
                                        $where =  [
                                            'dist_code' => $c_land_bank_details['dist_code'],
                                            'subdiv_code' => $c_land_bank_details['subdiv_code'],
                                            'cir_code' => $c_land_bank_details['cir_code'],
                                            'mouza_pargona_code' => $c_land_bank_details['mouza_pargona_code'],
                                            'lot_no' => $c_land_bank_details['lot_no'],
                                            'vill_townprt_code' => $c_land_bank_details['vill_townprt_code'],
                                            'dag_no' => $c_land_bank_details['dag_no'],
                                        ];
                                        $is_exists = $this->db->get_where($table->table_name, $where)->num_rows();
                                        if ($is_exists == 0) {
                                            $this->db->insert($table->table_name, $c_land_bank_details);
                                            if ($this->db->affected_rows() > 0) {
                                                $last_record = $this->db->get_where($table->table_name, $where)->row();
                                                $inserted_c_lb = $inserted_c_lb + 1;
                                                foreach ($c_land_bank_encroacher_details as $c_land_bank_encroacher_details_single) {
                                                    $c_land_bank_encroacher_details_single = (array)$c_land_bank_encroacher_details_single;
                                                    unset($c_land_bank_encroacher_details_single['id']);
                                                    $c_land_bank_encroacher_details_single['c_land_bank_details_id'] = $last_record->id;
                                                    $this->db->insert('c_land_bank_encroacher_details', $c_land_bank_encroacher_details_single);
                                                    $inserted_c_lb_enc = $inserted_c_lb_enc + 1;
                                                }
                                            }
                                        } else {
                                            // $this->db->update($table->table_name, $c_land_bank_details, $where);
                                            $exists = $exists + 1;
                                        }
                                    }
                                    $port_msgs = $port_msgs . '<small style="color:green;">Inserted to ' . $table->table_name . ' - ' . $inserted_c_lb . '</small></br>';
                                    $port_msgs = $port_msgs . '<small style="color:green;">Inserted to c_land_bank_encroacher_details' . $inserted_c_lb_enc . '</small></br>';
                                    if ($exists > 0) {
                                        $port_msgs = $port_msgs . '<small style="color:yellow;">Duplicate in ' . $table->table_name . ' - ' . $exists . '</small></br>';
                                    }
                                } else {
                                    $port_msgs = $port_msgs . '<small style="">No records on table ' . $table->table_name . '</small></br>';
                                }
                            } else if ($table->table_name == 'land_bank_details') {
                                $data = $table->table_data;
                                if (count($data) > 0) {
                                    $inserted_lb = 0;
                                    $inserted_lb_enc = 0;
                                    $exists = 0;
                                    foreach ($data as $land_bank_data) {
                                        $land_bank_details = (array)$land_bank_data->land_bank_details;

                                        unset($land_bank_details['id']);
                                        $land_bank_encroacher_details = $land_bank_data->land_bank_encroacher_details;
                                        $where =  [
                                            'dist_code' => $land_bank_details['dist_code'],
                                            'subdiv_code' => $land_bank_details['subdiv_code'],
                                            'cir_code' => $land_bank_details['cir_code'],
                                            'mouza_pargona_code' => $land_bank_details['mouza_pargona_code'],
                                            'lot_no' => $land_bank_details['lot_no'],
                                            'vill_townprt_code' => $land_bank_details['vill_townprt_code'],
                                            'dag_no' => $land_bank_details['dag_no'],
                                        ];
                                        $is_exists = $this->db->get_where($table->table_name, $where)->num_rows();
                                        if ($is_exists == 0) {
                                            $this->db->insert($table->table_name, $land_bank_details);
                                            if ($this->db->affected_rows() > 0) {
                                                $last_record = $this->db->get_where($table->table_name, $where)->row();
                                                $inserted_lb = $inserted_lb + 1;
                                                foreach ($land_bank_encroacher_details as $land_bank_encroacher_details_single) {
                                                    $land_bank_encroacher_details_single = (array)$land_bank_encroacher_details_single;
                                                    unset($land_bank_encroacher_details_single['id']);
                                                    $land_bank_encroacher_details_single['land_bank_details_id'] = $last_record->id;
                                                    $this->db->insert('land_bank_encroacher_details', $land_bank_encroacher_details_single);
                                                    $inserted_lb_enc = $inserted_lb_enc + 1;
                                                }
                                            }
                                        } else {
                                            // $this->db->update($table->table_name, $land_bank_details, $where);
                                            $exists = $exists + 1;
                                        }
                                    }
                                    $port_msgs = $port_msgs . '<small style="color:green;">Inserted to ' . $table->table_name . ' - ' . $inserted_lb . '</small></br>';
                                    $port_msgs = $port_msgs . '<small style="color:green;">Inserted to land_bank_encroacher_details' . $inserted_lb_enc . '</small></br>';
                                    if ($exists > 0) {
                                        $port_msgs = $port_msgs . '<small style="color:yellow;">Duplicate in ' . $table->table_name . ' - ' . $exists . '</small></br>';
                                    }
                                } else {
                                    $port_msgs = $port_msgs . '<small style="">No records on table ' . $table->table_name . '</small></br>';
                                }
                            } else if ($table->table_name == 't_land_bank_details') {
                                $data = $table->table_data;
                                if (count($data) > 0) {
                                    $inserted_t_lb = 0;
                                    $inserted_t_lb_enc = 0;
                                    $exists = 0;
                                    foreach ($data as $t_land_bank_data) {
                                        $t_land_bank_details = (array)$t_land_bank_data->t_land_bank_details;
                                        unset($t_land_bank_details['id']);
                                        $t_land_bank_encroacher_details = $t_land_bank_data->t_land_bank_encroacher_details;
                                        $where =  [
                                            'dist_code' => $t_land_bank_details['dist_code'],
                                            'subdiv_code' => $t_land_bank_details['subdiv_code'],
                                            'cir_code' => $t_land_bank_details['cir_code'],
                                            'mouza_pargona_code' => $t_land_bank_details['mouza_pargona_code'],
                                            'lot_no' => $t_land_bank_details['lot_no'],
                                            'vill_townprt_code' => $t_land_bank_details['vill_townprt_code'],
                                            'dag_no' => $t_land_bank_details['dag_no'],
                                        ];
                                        $is_exists = $this->db->get_where($table->table_name, $where)->num_rows();
                                        if ($is_exists == 0) {
                                            $this->db->insert($table->table_name, $t_land_bank_details);
                                            if ($this->db->affected_rows() > 0) {
                                                $last_record = $this->db->get_where($table->table_name, $where)->row();
                                                $inserted_t_lb = $inserted_t_lb + 1;
                                                foreach ($t_land_bank_encroacher_details as $t_land_bank_encroacher_details_single) {
                                                    $t_land_bank_encroacher_details_single = (array)$t_land_bank_encroacher_details_single;
                                                    unset($t_land_bank_encroacher_details_single['id']);
                                                    $t_land_bank_encroacher_details_single['t_land_bank_details_id'] = $last_record->id;
                                                    $this->db->insert('t_land_bank_encroacher_details', $t_land_bank_encroacher_details_single);
                                                    $inserted_t_lb_enc = $inserted_t_lb_enc + 1;
                                                }
                                            }
                                        } else {
                                            // $this->db->update($table->table_name, $t_land_bank_details, $where);
                                            $exists = $exists + 1;
                                        }
                                    }
                                    $port_msgs = $port_msgs . '<small style="color:green;">Inserted to ' . $table->table_name . ' - ' . $inserted_t_lb . '</small></br>';
                                    $port_msgs = $port_msgs . '<small style="color:green;">Inserted to t_land_bank_encroacher_details' . $inserted_t_lb_enc . '</small></br>';
                                    if ($exists > 0) {
                                        $port_msgs = $port_msgs . '<small style="color:yellow;">Duplicate in ' . $table->table_name . ' - ' . $exists . '</small></br>';
                                    }
                                } else {
                                    $port_msgs = $port_msgs . '<small style="">No records on table ' . $table->table_name . '</small></br>';
                                }
                            } else {
                                $data = $table->table_data;
                                if (count($data) > 0) {
                                    $this->db->trans_start();
                                    $this->db->insert_batch($table->table_name, $data);
                                    $this->db->trans_complete();
                                    if ($this->db->trans_status() == TRUE) {
                                        $port_msgs = $port_msgs . '<small style="color:green;">Inserted batch to ' . $table->table_name . ' - ' . count($data) . '</small></br>';
                                    } else {
                                        $port_msgs = $port_msgs . '<small style="color:red;">Insert batch query failed ' . $table->table_name . ' - ' . count($data) .' - Error - '.$this->db->error(). '</small></br>';
                                        // log_message('error', 'Dhar porting failed -  ' . $this->db->error());
                                    }
                                } else {
                                    $port_msgs = $port_msgs . '<small style="">No records on table ' . $table->table_name . '</small></br>';
                                }
                            }
                        }
                    } else {
                        $port_msgs = $port_msgs . '<small style="color:red;">Table does not exist ' . $table->table_name . ' - ' . count($data) . '</small></br>';
                    }
                }
                $db_loc_master = $this->UserModel->connectLocmaster();
                $update_data = [
                    'status' => $this->PortingModel::$ENUM_SUCCESS,
                    'msgs' => $port_msgs
                ];
                $db_loc_master->where('id', $last_id);
                $db_loc_master->update('dhar_porting_log', $update_data);
            } else {
                $port_msgs = $response->msgs;
                $db_loc_master = $this->UserModel->connectLocmaster();
                $update_data = [
                    'status' => $this->PortingModel::$ENUM_FAILED,
                    'msgs' => $response->msgs
                ];
                $db_loc_master->where('id', $last_id);
                $db_loc_master->update('dhar_porting_log', $update_data);
            }
            echo json_encode(['st' => $response->st, 'msgs' => $port_msgs]);
        } else {
            echo json_encode(['st' => 'failed', 'msgs' => '<p style="color:red;">Something went wrong.</p>']);
        }
    }
}
