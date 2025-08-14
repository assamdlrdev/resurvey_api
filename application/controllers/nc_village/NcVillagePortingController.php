<?php

include APPPATH . '/libraries/CommonTrait.php';

class NcVillagePortingController extends CI_Controller
{
    use CommonTrait;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('nc_village/PortingModel');
        $this->load->library('FileWrite');
        $this->load->model('UserModel');
    }
    public function index()
    {
        $data['districts'] = $this->db->get_where('location', array('subdiv_code' => '00', 'cir_code' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->result_array();
        $data['_view'] = 'nc_village/porting/porting';
        $this->load->view('layout/layout', $data);
    }
    public function index_vlb()
    {
        $data['districts'] = $this->db->get_where('location', array('subdiv_code' => '00', 'cir_code' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->result_array();
        $data['_view'] = 'nc_village/porting/porting_vlb';
        $this->load->view('layout/layout', $data);
    }

    public function index_nc() {
        $districts_raw = $this->db->get_where('location', array('subdiv_code' => '00', 'cir_code' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->result_array();
        // $data['districts'] = $this->db->get_where('location', array('subdiv_code' => '00', 'cir_code' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->result_array();
        $districts = [];
        if(!empty($districts_raw)) {
            foreach ($districts_raw as $district_raw) {
                if(in_array($district_raw['dist_code'], json_decode(NC_DISTIRTCS))) {
                    $districts[] = $district_raw;
                }
            }
        }
        $data['districts'] = $districts;
        $data['_view'] = 'nc_village/porting/portingNc';
        $this->load->view('layout/layout', $data);
    }

    public function portVillageToDharitreeNC() {
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
            'type' => $this->PortingModel::$PORT_TYPE_CHITHA
        ];

        if ($send_to_diff_vill == 'Y') {
            $data['dist_code_to'] = $dist_code;
            $data['subdiv_code_to'] = $subdiv_code;
            $data['cir_code_to'] = $cir_code;
            $data['mouza_pargona_code_to'] = $mouza_pargona_code;
            $data['lot_no_to'] = $lot_no;
            $data['vill_townprt_code_to'] = $vill_townprt_code_to;
        }

        $logInsertStatus = $db_loc_master->insert('dhar_porting_log', $data);
        $last_id = $db_loc_master->insert_id();

        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '10240M');

        $this->dbswitch($dist_code);
        $villageTables = $this->PortingModel->tablesUptoVillage();
        $dataArr = [];

        foreach ($villageTables as $table_port) {
            if ($this->db->table_exists($table_port['table']) && in_array($table_port['table'], ALLOWED_DHAR_PORTING_TABLES)) {
                $d['table_name'] = $table_port['table'];
		        $vill_column = '';
                $where = [
                    'dist_code' => $dist_code,
                    'subdiv_code' => $subdiv_code,
                    'lot_no' => $lot_no,
                ];
                if ($table_port['is_cir_code']) {
                    $where['cir_code'] = $cir_code;
                }
                if ($table_port['is_circle_code']) {
                    $where['circle_code'] = $cir_code;
                }
                if ($table_port['is_mp_code']) {
                    $where['mp_code'] = $mouza_pargona_code;
                }
                if ($table_port['is_mouza_pargona_code']) {
                    $where['mouza_pargona_code'] = $mouza_pargona_code;
                }
                if ($table_port['is_mouza_code']) {
                    $where['mouza_code'] = $mouza_pargona_code;
                }
                if ($table_port['is_vill_townprt_code']) {
                    $where['vill_townprt_code'] = $vill_townprt_code;
                    $vill_column = 'vill_townprt_code';
                }
                if ($table_port['is_vill_code']) {
                    $where['vill_code'] = $vill_townprt_code;
                    $vill_column = 'vill_code';
                }
                if ($table_port['is_vt_code']) {
                    $where['vt_code'] = $vill_townprt_code;
                    $vill_column = 'vt_code';
                }
                if ($table_port['is_vill_town_code']) {
                    $where['vill_town_code'] = $vill_townprt_code;
                    $vill_column = 'vill_town_code';
                }
            
                //  FOR NC VILLAGE PORTING
                if ($table_port['table'] == 'chitha_basic') {
                    $table_data = $this->db->get_where('chitha_basic_nc', $where)->result();
                } else if ($table_port['table'] == 'chitha_rmk_encro') {
                    $this->db->select('dag_no');
                    $dags = array_column($this->db->get_where('chitha_basic_nc', $where)->result_array(),'dag_no');
                    if(!empty($dags)) {
                        $this->db->where($where);
                        $this->db->where_in('dag_no',$dags);
                        $table_data = $this->db->get('chitha_rmk_encro')->result();
                    }
                    else {
                        $table_data = [];
                    }
                    
                } else {
                    $table_data = $this->db->get_where($table_port['table'], $where)->result();
                }

                if($table_port['table'] == 'chitha_basic') {
                    if(in_array($dist_code, BARAK_VALLEY)) {
                        foreach ($table_data as $tab) {
                            unset($tab->alpha_dag);
                            unset($tab->alpha_patta);
                        }
                    }
                }
                if($dist_code == '21') {
                    if($table_port['table'] == 'chitha_pattadar') {
                        if(in_array($dist_code, BARAK_VALLEY)) {
                            if($table_data && !empty($table_data)) {
                                foreach ($table_data as $tab) {
                                    unset($tab->cast_category);
                                    unset($tab->occupation);
                                    unset($tab->pdar_relation);
                                    unset($tab->relation);
                                }
                            }
                        }
                    }
                }

		        if ($send_to_diff_vill == 'Y') {
                    if($table_data && !empty($table_data)) {
                        if($vill_column != '') {
                            foreach ($table_data as $table_data_single) {
                                $table_data_single->{$vill_column} = $vill_townprt_code_to;
                            }
                        }
                    }
                }

                $d['table_data'] = $table_data;
                $dataArr[] = $d;
            }
        }

        $response = callLandhubAPI2(
            'POST',
            'insertChithaVillageData',
            [
                'tables' => json_encode($dataArr),
                'dist_code' => $dist_code,
                'user_id' => $this->session->userdata('usercode'),
                'subdiv_code' => $subdiv_code,
                'cir_code' => $cir_code,
                'mouza_pargona_code' => $mouza_pargona_code,
                'lot_no' => $lot_no,
                'vill_townprt_code' => ($send_to_diff_vill == 'Y') ? $vill_townprt_code_to : $vill_townprt_code
            ]
        );
        if ($response) {
            $db_loc_master = $this->UserModel->connectLocmaster();
            if ($response->st == 'success') {
                $update_data = [
                    'status' => $this->PortingModel::$ENUM_SUCCESS,
                    'msgs' => $response->msgs
                ];
                $db_loc_master->where('id', $last_id);
                $db_loc_master->update('dhar_porting_log', $update_data);
                $this->dbswitch($dist_code);
                if($this->db->field_exists('porting_status', 'location')) {
                    $updArr = [
                        'porting_status' => 1
                    ];
                    $this->db->where([
                        'dist_code' => $dist_code,
                        'subdiv_code' => $subdiv_code,
                        'cir_code' => $cir_code,
                        'mouza_pargona_code' => $mouza_pargona_code,
                        'lot_no' => $lot_no,
                        'vill_townprt_code' => $vill_townprt_code
                    ]);
                    $this->db->update('location', $updArr);
                }
            } else {
                $update_data = [
                    'status' => $this->PortingModel::$ENUM_FAILED,
                    'msgs' => $response->msgs
                ];
                $db_loc_master->where('id', $last_id);
                $db_loc_master->update('dhar_porting_log', $update_data);
            }
            echo json_encode(['st' => $response->st, 'msgs' => $response->msgs]);
        } else {
            echo json_encode(['st' => 'failed', 'msgs' => '<p style="color:red;">Something went wrong.</p>']);
        }
    }

    public function portVillageToDharitree()
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
            'type' => $this->PortingModel::$PORT_TYPE_CHITHA
        ];

        if ($send_to_diff_vill == 'Y') {
            $data['dist_code_to'] = $dist_code;
            $data['subdiv_code_to'] = $subdiv_code;
            $data['cir_code_to'] = $cir_code;
            $data['mouza_pargona_code_to'] = $mouza_pargona_code;
            $data['lot_no_to'] = $lot_no;
            $data['vill_townprt_code_to'] = $vill_townprt_code_to;
        }

        $db_loc_master->insert('dhar_porting_log', $data);
        $last_id = $db_loc_master->insert_id();

        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '10240M');

        $this->dbswitch($dist_code);
        $data = [];
        foreach ($this->PortingModel->tablesUptoVillage() as $table_port) {
            if ($this->db->table_exists($table_port['table']) && in_array($table_port['table'], ALLOWED_DHAR_PORTING_TABLES)) {
                $d['table_name'] = $table_port['table'];
                $where = [
                    'dist_code' => $dist_code,
                    'subdiv_code' => $subdiv_code,
                    'lot_no' => $lot_no,
                ];
                if ($table_port['is_cir_code']) {
                    $where['cir_code'] = $cir_code;
                }
                if ($table_port['is_circle_code']) {
                    $where['circle_code'] = $cir_code;
                }
                if ($table_port['is_mp_code']) {
                    $where['mp_code'] = $mouza_pargona_code;
                }
                if ($table_port['is_mouza_pargona_code']) {
                    $where['mouza_pargona_code'] = $mouza_pargona_code;
                }
                if ($table_port['is_mouza_code']) {
                    $where['mouza_code'] = $mouza_pargona_code;
                }
                if ($table_port['is_vill_townprt_code']) {
                    $where['vill_townprt_code'] = $vill_townprt_code;
                    $vill_column = 'vill_townprt_code';
                }
                if ($table_port['is_vill_code']) {
                    $where['vill_code'] = $vill_townprt_code;
                    $vill_column = 'vill_code';
                }
                if ($table_port['is_vt_code']) {
                    $where['vt_code'] = $vill_townprt_code;
                    $vill_column = 'vt_code';
                }
                if ($table_port['is_vill_town_code']) {
                    $where['vill_town_code'] = $vill_townprt_code;
                    $vill_column = 'vill_town_code';
                }

                //  FOR NC VILLAGE PORTING
                // if ($table_port['table'] == 'chitha_basic') {
                //     $table_data = $this->db->get_where('chitha_basic_nc', $where)->result();
                // } else if ($table_port['table'] == 'chitha_rmk_encro') {
                //     $this->db->select('dag_no');
                //     $dags = array_column($this->db->get_where('chitha_basic_nc', $where)->result_array(),'dag_no');
                //     $this->db->where($where);
                //     $this->db->where_in('dag_no',$dags);
                //     $table_data = $this->db->get('chitha_rmk_encro')->result();
                // } else {
                //     $table_data = $this->db->get_where($table_port['table'], $where)->result();
                // }
                // IF NOT NC VILLAGE
                $table_data = $this->db->get_where($table_port['table'], $where)->result();
                if ($send_to_diff_vill == 'Y') {
                    foreach ($table_data as $table_data_single) {
                        $table_data_single->{$vill_column} = $vill_townprt_code_to;
                    }
                }
                // if($table_port['table'] == 'chitha_basic') {
                //     if(in_array($dist_code, BARAK_VALLEY)) {
                //         foreach ($table_data as $tab) {
                //             unset($tab->alpha_dag);
                //             unset($tab->alpha_patta);
                //         }
                //     }
                // }
                if($dist_code == '21') {
                    if($table_port['table'] == 'chitha_pattadar') {
                        if(in_array($dist_code, BARAK_VALLEY)) {
                            foreach ($table_data as $tab) {
                                unset($tab->cast_category);
                                unset($tab->occupation);
                                unset($tab->pdar_relation);
                                unset($tab->relation);
                            }
                        }
                    }
                }
                
                $d['table_data'] = $table_data;
                $data[] = $d;
            }
        }

        $response = callLandhubAPI2(
            'POST',
            'insertChithaVillageData',
            [
                'tables' => json_encode($data),
                'dist_code' => $dist_code,
                'user_id' => $this->session->userdata('usercode'),
                'subdiv_code' => $subdiv_code,
                'cir_code' => $cir_code,
                'mouza_pargona_code' => $mouza_pargona_code,
                'lot_no' => $lot_no,
                'vill_townprt_code' => $vill_townprt_code
            ]
        );
        if ($response) {
            $db_loc_master = $this->UserModel->connectLocmaster();
            if ($response->st == 'success') {
                $update_data = [
                    'status' => $this->PortingModel::$ENUM_SUCCESS,
                    'msgs' => $response->msgs
                ];
                $db_loc_master->where('id', $last_id);
                $db_loc_master->update('dhar_porting_log', $update_data);

		        $this->dbswitch($dist_code);
                if($this->db->field_exists('porting_status', 'location')) {
                    $updArr = [
                        'porting_status' => 1
                    ];
                    $this->db->where([
                        'dist_code' => $dist_code,
                        'subdiv_code' => $subdiv_code,
                        'cir_code' => $cir_code,
                        'mouza_pargona_code' => $mouza_pargona_code,
                        'lot_no' => $lot_no,
                        'vill_townprt_code' => $vill_townprt_code
                    ]);
                    $this->db->update('location', $updArr);
                }


            } else {
                $update_data = [
                    'status' => $this->PortingModel::$ENUM_FAILED,
                    'msgs' => $response->msgs
                ];
                $db_loc_master->where('id', $last_id);
                $db_loc_master->update('dhar_porting_log', $update_data);
            }
            echo json_encode(['st' => $response->st, 'msgs' => $response->msgs]);
        } else {
            echo json_encode(['st' => 'failed', 'msgs' => '<p style="color:red;">Something went wrong.</p>']);
        }
    }

    public function portVlbDataToDharitree()
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
            'type' => $this->PortingModel::$PORT_TYPE_VLB
        ];
        if ($send_to_diff_vill == 'Y') {
            $data['dist_code_to'] = $dist_code;
            $data['subdiv_code_to'] = $subdiv_code;
            $data['cir_code_to'] = $cir_code;
            $data['mouza_pargona_code_to'] = $mouza_pargona_code;
            $data['lot_no_to'] = $lot_no;
            $data['vill_townprt_code_to'] = $vill_townprt_code_to;
        }
        $db_loc_master->insert('dhar_porting_log', $data);
        $last_id = $db_loc_master->insert_id();

        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '10240M');

        $this->dbswitch($dist_code);
        $data = [];

        //c_land_bank_details
        $d['table_name'] = 'c_land_bank_details';
        $c_land_bank_details =  $this->db->get_where('c_land_bank_details', [
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'mouza_pargona_code' => $mouza_pargona_code,
            'lot_no' => $lot_no,
            'vill_townprt_code' => $vill_townprt_code
        ])->result();
        $c_lb_data = [];
        if (!empty($c_land_bank_details)) {
            foreach ($c_land_bank_details as $c_land_bank_detail) {
                if ($send_to_diff_vill == 'Y') {
                    $c_land_bank_detail->vill_townprt_code = $vill_townprt_code_to;
                }
                $c_land_bank_encroacher_details = $this->db->get_where('c_land_bank_encroacher_details', ['c_land_bank_details_id' => $c_land_bank_detail->id])->result();
                if(empty($c_land_bank_encroacher_details)) {
                    // $this->db->where([
                    //     'id' => $c_land_bank_detail->id
                    // ]);
                    // $this->db->update('c_land_bank_details', [
                    //     'whether_encroached' => 'N'
                    // ]);
                    $c_land_bank_detail->whether_encroached = 'N';
                }

                $c_lb_data_single['c_land_bank_details'] = $c_land_bank_detail;
                $c_lb_data_single['c_land_bank_encroacher_details'] = $c_land_bank_encroacher_details;
                $c_lb_data[] = $c_lb_data_single;
            }
        }
        $d['table_data'] = $c_lb_data;

        $data[] = $d;

        //land_bank_details
        $d['table_name'] = 'land_bank_details';
        $land_bank_details =  $this->db->get_where('land_bank_details', [
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'mouza_pargona_code' => $mouza_pargona_code,
            'lot_no' => $lot_no,
            'vill_townprt_code' => $vill_townprt_code
        ])->result();
        $c_lb_data = [];
        if (!empty($land_bank_details)) {
           foreach ($land_bank_details as $land_bank_detail) {
                if ($send_to_diff_vill == 'Y') {
                    $land_bank_detail->vill_townprt_code = $vill_townprt_code_to;
                }
                $land_bank_encroacher_details = $this->db->get_where('land_bank_encroacher_details', ['land_bank_details_id' => $land_bank_detail->id])->result();
                if(empty($land_bank_encroacher_details)) {
                    // $this->db->where([
                    //     'id' => $land_bank_detail->id
                    // ]);
                    // $this->db->update('land_bank_details', [
                    //     'whether_encroached' => 'N'
                    // ]);
                    $land_bank_detail->whether_encroached = 'N';
                }
                $lb_data_single['land_bank_details'] = $land_bank_detail;
                $lb_data_single['land_bank_encroacher_details'] = $land_bank_encroacher_details;
                $c_lb_data[] = $lb_data_single;
            }
        }
        $d['table_data'] = $c_lb_data;

        $data[] = $d;

	    if($send_to_diff_vill == 'Y') {
            $vill_townprt_code = $vill_townprt_code_to;
        }

        $response = callLandhubAPI2(
            'POST',
            'insertVlbVillageData',
            [
                'tables' => json_encode($data),
                'dist_code' => $dist_code,
                'user_id' => $this->session->userdata('usercode'),
                'subdiv_code' => $subdiv_code,
                'cir_code' => $cir_code,
                'mouza_pargona_code' => $mouza_pargona_code,
                'lot_no' => $lot_no,
                'vill_townprt_code' => $vill_townprt_code
            ]
        );
        if ($response) {
            $db_loc_master = $this->UserModel->connectLocmaster();
            if ($response->st == 'success') {
                $update_data = [
                    'status' => $this->PortingModel::$ENUM_SUCCESS,
                    'msgs' => $response->msgs
                ];
                $db_loc_master->where('id', $last_id);
                $db_loc_master->update('dhar_porting_log', $update_data);
            } else {
                $update_data = [
                    'status' => $this->PortingModel::$ENUM_FAILED,
                    'msgs' => $response->msgs
                ];
                $db_loc_master->where('id', $last_id);
                $db_loc_master->update('dhar_porting_log', $update_data);
            }
            echo json_encode(['st' => $response->st, 'msgs' => $response->msgs]);
        } else {
            echo json_encode(['st' => 'failed', 'msgs' => '<p style="color:red;">Something went wrong.</p>']);
        }
    }

    public function getNcVillages()
    {
        $dist_code = $this->input->post('dist_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');

        $this->dbswitch($dist_code);
        $query = "select l.loc_name,l.dist_code,l.subdiv_code,l.cir_code,l.mouza_pargona_code,l.lot_no,l.vill_townprt_code,l.uuid,ncv.is_dhar_ported from nc_villages ncv join location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code
                 and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and ncv.vill_townprt_code = l.vill_townprt_code";

        $query = $query . " where ncv.dist_code='$dist_code' and ncv.subdiv_code='$subdiv_code' and ncv.cir_code='$cir_code'";
        if ($mouza_pargona_code) {
            $query = $query . " and ncv.mouza_pargona_code = '$mouza_pargona_code'";
        }
        if ($lot_no) {
            $query = $query . " and ncv.lot_no = '$lot_no'";
        }
        $result = $this->db->query($query)->result();

        echo json_encode($result);
    }
    public function villageLog($loc_code)
    {
        $codes = explode('-', $loc_code);
        $dist_code = $codes[0];
        $subdiv_code = $codes[1];
        $cir_code = $codes[2];
        $mouza_pargona_code = $codes[3];
        $lot_no = $codes[4];
        $vill_townprt_code = $codes[5];
        $db_loc_master = $this->UserModel->connectLocmaster();
        $data['logs'] = $db_loc_master->get_where('dhar_porting_log', array(
            'dist_code' => $dist_code,
            'subdiv_code =' => $subdiv_code,
            'cir_code=' => $cir_code,
            'mouza_pargona_code=' => $mouza_pargona_code,
            'lot_no=' => $lot_no,
            'vill_townprt_code' => $vill_townprt_code
        ))->result();
        $data['_view'] = 'nc_village/porting/porting_logs';
        $this->load->view('layout/layout', $data);
    }
    public function villagedetails()
    {
        // $this->dataswitch();
        $data = [];
        $dis = $this->input->post('dis');
        $this->dbswitch($dis);
        $subdiv = $this->input->post('subdiv');
        $cir = $this->input->post('cir');
        $mza = $this->input->post('mza');
        $lot = $this->input->post('lot');
        $formdata = $this->Chithamodel->villagedetails($dis, $subdiv, $cir, $mza, $lot);
        $db_loc_master = $this->UserModel->connectLocmaster();
        $is_btc = in_array($dis, ['21', '22', '23', '24']) ? 'Y' : 'N';
        foreach ($formdata as $value) {
            $vill_townprt_code = $value['vill_townprt_code'];
            $last_port = $db_loc_master->query("select * from dhar_porting_log where dist_code=? 
            and subdiv_code=? and cir_code=? and mouza_pargona_code=? and lot_no=? and 
            vill_townprt_code=? and type='chitha_data' order by created_at desc", [$dis, $subdiv, $cir, $mza, $lot, $vill_townprt_code])->row();
            $is_alpha = false;
            if ($is_btc == 'Y') {
                $verified_village_check = $this->Chithamodel->checkVerifiedVillage($dis, $subdiv, $cir, $mza, $lot, $vill_townprt_code);
                if ($verified_village_check) {
                    $check = '(<span style="color: green;">Verified by CO</span>)';
                } else {
                    $check = '(<span style="color: red;">Not Verified by CO</span>)';
                }
                $value['verified'] = $check;
                if ($this->db->field_exists('alpha_dag', 'chitha_basic')) {
                    $is_alpha = $this->db->query("select * from chitha_basic where dist_code=? and subdiv_code=? and cir_code=? and mouza_pargona_code=? and lot_no=? and vill_townprt_code=? and (alpha_dag='1' or alpha_patta='1')", [$dis, $subdiv, $cir, $mza, $lot, $vill_townprt_code])->num_rows();
                }
            }
            $value['is_alpha'] = $is_alpha;
            if ($last_port) {
                $value['last_port'] = $last_port;
            }
            $value['show_port'] = 1;
            if($dis == '21') {
                if($value['status'] == 'L') {
                    $value['show_port'] = 1;
                }
                else {
                    $value['show_port'] = 0;
                }
            }
            if(!empty($last_port) && $last_port->status == 'success') {
                if ($this->db->field_exists('porting_status', 'location')) {
                    $updArr = [
                        'porting_status' => 1
                    ];
                    $this->db->where([
                        'dist_code' => $dis,
                        'subdiv_code' => $subdiv,
                        'cir_code' => $cir,
                        'mouza_pargona_code' => $mza,
                        'lot_no' => $lot,
                        'vill_townprt_code' => $vill_townprt_code
                    ]);
                    $this->db->update('location', $updArr);
                }
            }

            $data[] = $value;
        }
        echo json_encode($data);
    }
    public function villagedetailsVlb()
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
            vill_townprt_code=? and type='vlb_data' order by created_at desc", [$dis, $subdiv, $cir, $mza, $lot, $vill_townprt_code])->row();
            if ($last_port) {
                $value['last_port'] = $last_port;
            }
            $data[] = $value;
        }
        echo json_encode($data);
    }
}
