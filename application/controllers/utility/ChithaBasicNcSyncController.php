<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');

include APPPATH . '/libraries/CommonTrait.php';

class ChithaBasicNcSyncController extends CI_Controller
{
    use CommonTrait;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('nc_village/PortingModel');
        $this->load->library('FileWrite');
        $this->load->model('UserModel');
        $this->load->model('NcVillageModel');
        $this->load->model('DagReportModel');
        $this->load->model('CommonModel');
    }
    public function index()
    {
        $data['districts'] = $this->db->get_where('location', array('subdiv_code' => '00', 'cir_code' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->result_array();
        $data['_view'] = 'utility/chitha_basic_sync/chitha_basic_sync_index';
        $this->load->view('layout/layout', $data);
    }
    public function index_bhunaksa()
    {
        $data['districts'] = $this->db->get_where('location', array('subdiv_code' => '00', 'cir_code' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->result_array();
        $data['_view'] = 'utility/chitha_basic_nc_sync/chitha_basic_nc_sync_bhunaksa';
        $this->load->view('layout/layout', $data);
    }
    public function viewVillage($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code,$uuid)
    {
        $this->dbswitch($dist_code);
        $village = $this->CommonModel->getVillageByUuid(
			$dist_code,
			$uuid
		);
        $chitha_basic_dags = $this->db->query("select * from chitha_basic where dist_code=? and subdiv_code=? and cir_code=? and mouza_pargona_code=? and lot_no=? and vill_townprt_code=? order by dag_no_int", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code])->result();
        $chitha_basic_nc_dags = $this->db->query("select * from chitha_basic_nc where dist_code=? and subdiv_code=? and cir_code=? and mouza_pargona_code=? and lot_no=? and vill_townprt_code=? order by dag_no_int", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code])->result();
        foreach ($chitha_basic_nc_dags as $chitha_basic_nc_dag) {
            $chitha_basic_dag = $this->db->query("select * from chitha_basic where dist_code=? and subdiv_code=? and cir_code=? and mouza_pargona_code=? and lot_no=? and vill_townprt_code=? and dag_no=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $chitha_basic_nc_dag->dag_no])->row();
            if (($chitha_basic_dag->dag_area_b != $chitha_basic_nc_dag->dag_area_b) || ($chitha_basic_dag->dag_area_k != $chitha_basic_nc_dag->dag_area_k) || ($chitha_basic_dag->dag_area_lc != $chitha_basic_nc_dag->dag_area_lc) || ($chitha_basic_dag->dag_area_g != $chitha_basic_nc_dag->dag_area_g) || ($chitha_basic_dag->dag_area_kr != $chitha_basic_nc_dag->dag_area_kr)) {
                $chitha_basic_nc_dag->is_synced = 'N';
            } else {
                $chitha_basic_nc_dag->is_synced = 'Y';
            }
        }
        $data['chitha_basic_dags'] = $chitha_basic_dags;
        $data['chitha_basic_nc_dags'] = $chitha_basic_nc_dags;
        $data['village'] = $village;
        $data['_view'] = 'utility/chitha_basic_sync/chitha_basic_sync_view_village';
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
        foreach ($formdata as $value) {
            $vill_townprt_code = $value['vill_townprt_code'];
            $last_port = $db_loc_master->get_where('dhar_porting_log', array(
                'dist_code' => $dis,
                'subdiv_code =' => $subdiv,
                'cir_code=' => $cir,
                'mouza_pargona_code=' => $mza,
                'lot_no=' => $lot,
                'vill_townprt_code' => $vill_townprt_code
            ))->row();
            $is_nc_village_exists = $this->db->query("select uuid,dc_chitha_sign from nc_villages where dist_code=? and subdiv_code=? and cir_code=? and mouza_pargona_code=? and lot_no=? and vill_townprt_code=? ", [$dis, $subdiv, $cir, $mza, $lot, $vill_townprt_code])->row();
            $value['is_nc_village_exists'] = $is_nc_village_exists;
            $value['last_port'] = $last_port;


            $chitha_basic_dags_count = $this->db->query("select * from chitha_basic where dist_code=? and subdiv_code=? and cir_code=? and mouza_pargona_code=? and lot_no=? and vill_townprt_code=? order by dag_no_int", [$dis, $subdiv, $cir, $mza, $lot, $vill_townprt_code])->num_rows();
            $chitha_basic_nc_dags = $this->db->query("select * from chitha_basic_nc where dist_code=? and subdiv_code=? and cir_code=? and mouza_pargona_code=? and lot_no=? and vill_townprt_code=? order by dag_no_int", [$dis, $subdiv, $cir, $mza, $lot, $vill_townprt_code])->result();
            $is_synced = 'Y';
            if ($chitha_basic_nc_dags) {
                if ($chitha_basic_dags_count != count($chitha_basic_nc_dags)) {
                    $is_synced = 'N';
                } else {
                    foreach ($chitha_basic_nc_dags as $chitha_basic_nc_dag) {
                        $chitha_basic_dag = $this->db->query("select * from chitha_basic where dist_code=? and subdiv_code=? and cir_code=? and mouza_pargona_code=? and lot_no=? and vill_townprt_code=? and dag_no=?", [$dis, $subdiv, $cir, $mza, $lot, $vill_townprt_code, $chitha_basic_nc_dag->dag_no])->row();
                        if (($chitha_basic_dag->dag_area_b != $chitha_basic_nc_dag->dag_area_b) || ($chitha_basic_dag->dag_area_k != $chitha_basic_nc_dag->dag_area_k) || ($chitha_basic_dag->dag_area_lc != $chitha_basic_nc_dag->dag_area_lc) || ($chitha_basic_dag->dag_area_g != $chitha_basic_nc_dag->dag_area_g) || ($chitha_basic_dag->dag_area_kr != $chitha_basic_nc_dag->dag_area_kr)) {
                            $is_synced = 'N';
                        }
                    }
                }
            }
            $value['is_synced'] = $is_synced;
            $data[] = $value;
        }
        echo json_encode($data);
    }
    public function syncData()
    {
        $dist_code = $this->input->post('dist_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $vill_townprt_code = $this->input->post('vill_townprt_code');
        $this->dbswitch($dist_code);
        $this->db->trans_begin();
        $chitha_basic_nc_dags = $this->db->query("select * from chitha_basic_nc where dist_code=? and subdiv_code=? and cir_code=? and mouza_pargona_code=? and lot_no=? and vill_townprt_code=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code])->result_array();
        foreach ($chitha_basic_nc_dags as $chitha_basic_nc_dag) {
            $is_exists_cb = $this->db->query("select * from chitha_basic where dist_code=? and subdiv_code=? and cir_code=? and mouza_pargona_code=? and lot_no=? and vill_townprt_code=? and dag_no=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $chitha_basic_nc_dag['dag_no']])->result();
            if ($is_exists_cb) {
                $this->db->where('dist_code', $dist_code);
                $this->db->where('subdiv_code', $subdiv_code);
                $this->db->where('cir_code', $cir_code);
                $this->db->where('mouza_pargona_code', $mouza_pargona_code);
                $this->db->where('lot_no', $lot_no);
                $this->db->where('vill_townprt_code', $vill_townprt_code);
                $this->db->where('dag_no', $chitha_basic_nc_dag['dag_no']);
                $this->db->update('chitha_basic', [
                    'dag_area_b' => $chitha_basic_nc_dag['dag_area_b'],
                    'dag_area_k' => $chitha_basic_nc_dag['dag_area_k'],
                    'dag_area_lc' => $chitha_basic_nc_dag['dag_area_lc'],
                    'dag_area_g' => $chitha_basic_nc_dag['dag_area_g'],
                    'dag_area_kr' => $chitha_basic_nc_dag['dag_area_kr'],
                ]);
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $response = array(
                        "st" => 'failed',
                        "msgs" => '<p style="color:red;">Unable to update data to chitha_basic.</p>',
                    );
                    echo json_encode($response);
                }
            } else {
                $this->db->insert('chitha_basic', $chitha_basic_nc_dag);
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $response = array(
                        "st" => 'failed',
                        "msgs" => '<p style="color:red;">Unable to insert data to chitha_basic.</p>',
                    );
                    echo json_encode($response);
                }
            }
        }
        $this->db->trans_commit();
        $response = array(
            "st" => 'success',
            "msgs" => '<p style="color:green;">Synced chitha_basic with chitha_basic_nc successfully.</p>',
        );
        echo json_encode($response);
    }
    public function villages_bhunaksa_sync()
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
        foreach ($formdata as $value) {
            $vill_townprt_code = $value['vill_townprt_code'];
            $last_port = $db_loc_master->get_where('dhar_porting_log', array(
                'dist_code' => $dis,
                'subdiv_code =' => $subdiv,
                'cir_code=' => $cir,
                'mouza_pargona_code=' => $mza,
                'lot_no=' => $lot,
                'vill_townprt_code' => $vill_townprt_code
            ))->row();
            $is_nc_village_exists = $this->db->query("select uuid,dc_chitha_sign from nc_villages where dist_code=? and subdiv_code=? and cir_code=? and mouza_pargona_code=? and lot_no=? and vill_townprt_code=? ", [$dis, $subdiv, $cir, $mza, $lot, $vill_townprt_code])->row();
            $value['is_nc_village_exists'] = $is_nc_village_exists;
            $value['last_port'] = $last_port;
            $data[] = $value;
        }
        echo json_encode($data);
    }
    public function sync_bhunaksa_view_dags($loc)
    {
        $loc_array = explode('_', $loc);
        $dist_code = $loc_array[0];
        $subdiv_code = $loc_array[1];
        $cir_code = $loc_array[2];
        $mouza_pargona_code = $loc_array[3];
        $lot_no = $loc_array[4];
        $vill_townprt_code = $loc_array[5];
        $uuid = $loc_array[6];

        $this->dbswitch($dist_code);
        $village = $this->CommonModel->getVillageByUuid(
			$dist_code,
			$uuid
		);
        $url = LANDHUB_BASE_URL_NEW . "BhunakshaApiController/getVillageDagDetails";
        $method = 'POST';
        $data['location'] = $dist_code . '_' . $subdiv_code . '_' . $cir_code . '_' . $mouza_pargona_code . '_' . $lot_no . '_' . $vill_townprt_code;

        $data = json_decode($this->NcVillageModel->callApiV2($url, $method, $data));

        $bhunaksa_dags = array();

        foreach ($data->plotInfo as $key => $dag) {
            $dag_no = (int)$dag->plotNo;
            $area = $this->DagReportModel->areaSqMetertoBKL($dag->plotArea);
            $chitha_data = $this->DagReportModel->getChithaDags($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag_no);

            $bhunaksa_dags[$key]['dist_code'] = $dist_code;
            $bhunaksa_dags[$key]['subdiv_code'] = $subdiv_code;
            $bhunaksa_dags[$key]['cir_code'] = $cir_code;
            $bhunaksa_dags[$key]['mouza_pargona_code'] = $mouza_pargona_code;
            $bhunaksa_dags[$key]['lot_no'] = $lot_no;
            $bhunaksa_dags[$key]['vill_townprt_code'] = $vill_townprt_code;

            $bhunaksa_dags[$key]['dag_no'] = "$dag_no";
            $bhunaksa_dags[$key]['dag_area_b'] = $area[0];
            $bhunaksa_dags[$key]['dag_area_k'] = $area[1];
            $bhunaksa_dags[$key]['dag_area_lc'] = $area[2];
            $bhunaksa_dags[$key]['dag_area_g'] = 0;
            $bhunaksa_dags[$key]['dag_area_kr'] = 0;
            $bhunaksa_dags[$key]['dag_no_int'] = $dag_no . '00';
            $bhunaksa_dags[$key]['is_synced'] = 'Y';

            if ($chitha_data) {
                $bhunaksa_dags[$key]['patta_no'] = $chitha_data->patta_no;
                $bhunaksa_dags[$key]['patta_type_code'] = $chitha_data->patta_type_code;
                $bhunaksa_dags[$key]['land_class_code'] = $chitha_data->land_class_code;
                $bhunaksa_dags[$key]['operation'] = $chitha_data->operation;
                $bhunaksa_dags[$key]['block_code'] = $chitha_data->block_code;
                $bhunaksa_dags[$key]['gp_code'] = $chitha_data->gp_code;
                $bhunaksa_dags[$key]['zonal_value'] = $chitha_data->zonal_value;
                $bhunaksa_dags[$key]['category_id'] = $chitha_data->category_id;
                $bhunaksa_dags[$key]['uuid'] = $chitha_data->uuid;
                $bhunaksa_dags[$key]['user_code'] = $chitha_data->user_code;
                $bhunaksa_dags[$key]['date_entry'] = $chitha_data->date_entry;
            } else {
                $bhunaksa_dags[$key]['patta_no'] = '0';
                $bhunaksa_dags[$key]['patta_type_code'] = '0209';
                $bhunaksa_dags[$key]['land_class_code'] = '0134';
                $bhunaksa_dags[$key]['operation'] = 'E';
                $bhunaksa_dags[$key]['block_code'] = null;
                $bhunaksa_dags[$key]['gp_code'] = null;
                $bhunaksa_dags[$key]['zonal_value'] = null;
                $bhunaksa_dags[$key]['category_id'] = null;
                $bhunaksa_dags[$key]['uuid'] = null;
                $bhunaksa_dags[$key]['user_code'] = $this->session->userdata('user_code');
                $bhunaksa_dags[$key]['date_entry'] = date('Y/m/d');
            }
        }
        $is_synced_all = 'Y';
        $chitha_basic_nc_dags = $this->db->query("select * from chitha_basic_nc where dist_code=? and subdiv_code=? and cir_code=? and mouza_pargona_code=? and lot_no=? and vill_townprt_code=? order by dag_no_int", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code])->result();
        foreach ($bhunaksa_dags as $key => $bhunaksa_dag) {
            $chitha_basic_nc_dag = $this->db->query("select * from chitha_basic_nc where dist_code=? and subdiv_code=? and cir_code=? and mouza_pargona_code=? and lot_no=? and vill_townprt_code=? and dag_no=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $bhunaksa_dag['dag_no']])->row();
            if (!$chitha_basic_nc_dag) {
                $bhunaksa_dags[$key]['is_synced'] = 'N';
                $is_synced_all = 'N';
            } else {
                if (($chitha_basic_nc_dag->dag_area_b != $bhunaksa_dag['dag_area_b']) || ($chitha_basic_nc_dag->dag_area_k != $bhunaksa_dag['dag_area_k']) || ($chitha_basic_nc_dag->dag_area_lc != $bhunaksa_dag['dag_area_lc']) || ($chitha_basic_nc_dag->dag_area_g != $bhunaksa_dag['dag_area_g']) || ($chitha_basic_nc_dag->dag_area_kr != $bhunaksa_dag['dag_area_kr'])) {
                    $bhunaksa_dags[$key]['is_synced'] = 'N';
                    $is_synced_all = 'N';
                }
            }
        }
        $data_view['chitha_basic_nc_dags'] = $chitha_basic_nc_dags;
        $data_view['bhunaksa_dags'] = $bhunaksa_dags;
        $data_view['is_synced_all'] = $is_synced_all;
        $data_view['dist_code'] = $dist_code;
        $data_view['subdiv_code'] = $subdiv_code;
        $data_view['cir_code'] = $cir_code;
        $data_view['mouza_pargona_code'] = $mouza_pargona_code;
        $data_view['lot_no'] = $lot_no;
        $data_view['vill_townprt_code'] = $vill_townprt_code;
        $data_view['village'] = $village;
        $data_view['_view'] = 'utility/chitha_basic_nc_sync/chitha_basic_nc_sync_bhunaksa_view_village';
        $this->load->view('layout/layout', $data_view);
    }
    public function syncChithaBasicNcWithBhunaksa()
    {
        $dist_code = $this->input->post('dist_code');
        $this->dbswitch($dist_code);
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $vill_townprt_code = $this->input->post('vill_townprt_code');

        $url = LANDHUB_BASE_URL_NEW . "BhunakshaApiController/getVillageDagDetails";
        $method = 'POST';
        $data['location'] = $dist_code . '_' . $subdiv_code . '_' . $cir_code . '_' . $mouza_pargona_code . '_' . $lot_no . '_' . $vill_townprt_code;

        $data = json_decode($this->NcVillageModel->callApiV2($url, $method, $data));

        $chitha = array();
        $dag_where['dist_code'] = $dist_code;
        $dag_where['subdiv_code'] = $subdiv_code;
        $dag_where['cir_code'] = $cir_code;
        $dag_where['mouza_pargona_code'] = $mouza_pargona_code;
        $dag_where['lot_no'] = $lot_no;
        $dag_where['vill_townprt_code'] = $vill_townprt_code;
        $this->db->trans_begin();
        foreach ($data->plotInfo as $key => $dag) {
            $dag_details = [];
            $dag_no = (int)$dag->plotNo;
            $area = $this->DagReportModel->areaSqMetertoBKL($dag->plotArea);
            $chitha_data = $this->DagReportModel->getChithaDags($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, "$dag_no");

            $dag_where['dag_no'] = "$dag_no";

            $dag_details['dag_area_b'] = $area[0];
            $dag_details['dag_area_k'] = $area[1];
            $dag_details['dag_area_lc'] = $area[2];
            $dag_details['dag_area_g'] = 0;
            $dag_details['dag_area_kr'] = 0;
            $dag_details['dag_no_int'] = $dag_no . '00';

            if ($chitha_data) {
                $dag_details['patta_no'] = $chitha_data->patta_no;
                $dag_details['patta_type_code'] = $chitha_data->patta_type_code;
                $dag_details['land_class_code'] = $chitha_data->land_class_code;
                $dag_details['operation'] = $chitha_data->operation;
                $dag_details['block_code'] = $chitha_data->block_code;
                $dag_details['gp_code'] = $chitha_data->gp_code;
                $dag_details['zonal_value'] = $chitha_data->zonal_value;
                $dag_details['category_id'] = $chitha_data->category_id;
                $dag_details['uuid'] = $chitha_data->uuid;
                $dag_details['user_code'] = $chitha_data->user_code;
                $dag_details['date_entry'] = $chitha_data->date_entry;
            } else {
                $dag_details['patta_no'] = '0';
                $dag_details['patta_type_code'] = '0209';
                $dag_details['land_class_code'] = '0134';
                $dag_details['operation'] = 'E';
                $dag_details['block_code'] = null;
                $dag_details['gp_code'] = null;
                $dag_details['zonal_value'] = null;
                $dag_details['category_id'] = null;
                $dag_details['uuid'] = null;
                $dag_details['user_code'] = $this->session->userdata('user_code');
                $dag_details['date_entry'] = date('Y/m/d');
            }
            $chitha_basic_nc_dag_count = $this->db->query("select * from chitha_basic_nc where dist_code=? and subdiv_code=? and cir_code=? and mouza_pargona_code=? and lot_no=? and vill_townprt_code=? and dag_no=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, "$dag_no"])->num_rows();
            if ($chitha_basic_nc_dag_count == 0) {
                $dag_details['dist_code'] = $dist_code;
                $dag_details['subdiv_code'] = $subdiv_code;
                $dag_details['cir_code'] = $cir_code;
                $dag_details['mouza_pargona_code'] = $mouza_pargona_code;
                $dag_details['lot_no'] = $lot_no;
                $dag_details['vill_townprt_code'] = $vill_townprt_code;
                $dag_details['dag_no'] = "$dag_no";
                $this->db->insert('chitha_basic_nc',$dag_details);
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $response = array(
                        "st" => 'failed',
                        "msgs" => '<p style="color:red;">Unable to insert into chitha_basic_nc.</p>',
                    );
                    echo json_encode($response);
                }
            }else{
                $this->db->update('chitha_basic_nc',$dag_details,$dag_where);
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $response = array(
                        "st" => 'failed',
                        "msgs" => '<p style="color:red;">Unable to update to chitha_basic_nc.</p>',
                    );
                    echo json_encode($response);
                }
            }
        }
        $this->db->trans_commit();
        $response = array(
            "st" => 'success',
            "msgs" => '<p style="color:green;">Synced chitha_basic_nc dags with bhunaksa dags successfully.</p>',
        );
        echo json_encode($response);
    }
}
