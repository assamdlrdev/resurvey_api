<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class MapCertifiedVillagesController extends CI_Controller
{
    use CommonTrait;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Chithamodel');
        $this->load->model('CommonModel');
    }
    public function index($type, $dist_code)
    {
        $this->db = $this->load->database('default', TRUE);
        $data['districts'] = $this->db->get_where('location', array(
            'dist_code !=' => '00', 'subdiv_code' => '00',
            'cir_code' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'
        ))->result_array();
        $this->dbswitch();
        $data['type'] = $type;
        $data['dist_code'] = $dist_code;
        $data['sub_divs'] = $this->Chithamodel->subdivisiondetails($this->session->userdata('dcode'));
        $data['_view'] = 'reports/nc_village/villages';
        $this->load->view('layout/layout', $data);
    }

    public function dashboard()
    {
        $this->db = $this->load->database('default', TRUE);
        $d = date('Y-m-d');
        $query = $this->db->query("select * from dashboard_count where service_name = '1' and updated_at='$d'")->row();
        if ($query) {
            $data = (array)json_decode($query->json);
            $data['updated_at'] = $query->updated_at;
            $data['updated_at_time'] = $query->updated_at_time;
        } else {
            $data = $this->insertOrUpdateNcDashboardCount();
        }
        $data['_view'] = 'reports/nc_village/dashboard';
        $this->load->view('layout/layout', $data);
    }
    public function districts($type)
    {
        $this->db = $this->load->database('default', TRUE);
        $query = $this->db->query("select * from dashboard_count where service_name = '1'")->row();
        if ($query) {
            $d = (array)json_decode($query->json);
            $data['districts'] = $d['data_dist_wise'];
            if ($data['districts']) {
                foreach ($data['districts'] as $dist_code => $data_dist) {
                    $data_dist->loc_name = $this->CommonModel->getLocations($dist_code);
                }
            }
        }
        $data['type'] = $type;
        $data['_view'] = 'reports/nc_village/districts';
        $this->load->view('layout/layout', $data);
    }
    public function insertOrUpdateNcDashboardCount()
    {

        $verified_lm_count = 0;
        $certified_co_count = 0;
        $digi_signature_dc_count = 0;
        $data_dist_wise = [];

        foreach (json_decode(NC_DISTIRTCS) as $dist_code) {
            $this->dbswitch($dist_code);
            $verified_lm_count_dist = $this->db->query("select * from nc_villages where lm_verified = 'Y' ")->num_rows();
            $certified_co_count_dist = $this->db->query("select * from nc_villages where co_verified = 'Y' ")->num_rows();
            $digi_signature_dc_count_dist = $this->db->query("select * from nc_villages where dc_verified = 'Y' ")->num_rows();

            $verified_lm_count += $verified_lm_count_dist;
            $certified_co_count += $certified_co_count_dist;
            $digi_signature_dc_count += $digi_signature_dc_count_dist;

            $data_dist_wise[$dist_code] = [
                'verified_lm_count' => $verified_lm_count,
                'certified_co_count' => $certified_co_count,
                'digi_signature_dc_count' => $digi_signature_dc_count
            ];
        }
        $data = [
            'verified_lm_count' => $verified_lm_count,
            'certified_co_count' => $certified_co_count,
            'digi_signature_dc_count' => $digi_signature_dc_count,
            'data_dist_wise' => $data_dist_wise,
            'updated_at' => date('Y-m-d'),
            'updated_at_time' => date('H:i:s')
        ];
        $json = json_encode($data);
        $this->db = $this->load->database('default', TRUE);
        $query = $this->db->query("select * from dashboard_count where service_name = '1'")->row();

        if ($query) {
            $this->db->set('json', $json);
            $this->db->set('updated_at', date('Y-m-d'));
            $this->db->set('updated_at_time', date('H:i:s'));
            $this->db->where('service_name', '1');
            $this->db->update('dashboard_count');
        } else {
            $this->db->insert('dashboard_count', [
                'service_name' => '1',
                'json' => $json,
                'updated_at' => date('Y-m-d'),
                'updated_at_time' => date('H:i:s')
            ]);
        }
        return ($data);
    }

    public function getNcVillagesByStatus()
    {
        $dist_code = $this->input->post('dist_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $filter_status = $this->input->post('filter_status');

        $this->dbswitch($dist_code);
        // $sql = "select
        //             (select loc_name from location where subdiv_code=d.subdiv_code and cir_code=d.cir_code and mouza_pargona_code='00') as Circle,
        //             (select loc_name from location where subdiv_code=d.subdiv_code and cir_code=d.cir_code and mouza_pargona_code=d.mouza_pargona_code and lot_no='00') as Mouza,
        //             (select loc_name from location where subdiv_code=d.subdiv_code and cir_code=d.cir_code and mouza_pargona_code=d.mouza_pargona_code and lot_no=d.lot_no and vill_townprt_code='00000') as Lot,(select loc_name from location where uuid=d.unique_village_code::bigint) as Village,
        //             (select zone_name from zonal_master where zone_code::int=d.zone_id::int) as Zone,
        //             (select subclass_name from subclass_master where subclass_code::int=d.subclass_id::int) as Subclass,
        //             d.dag_no from dagwise_zone_info d left join villagewise_zone_info v on d.unique_village_code=v.unique_village_code
        //             and v.zone_code::int=d.zone_id::int and v.subclass_code::int=d.subclass_id::int
        //             join location l on l.uuid=d.unique_village_code::bigint
        //             join chitha_basic b on b.subdiv_code = d.subdiv_code and b.cir_code = d.cir_code and
        //             b.mouza_pargona_code = d.mouza_pargona_code and b.lot_no = d.lot_no and b.vill_townprt_code = d.vill_code and b.dag_no=d.dag_no	
        //             where d.flag='1' and  (v.flag='0' or v.land_rate is null or v.land_rate='' )
        //             and d.subdiv_code='$subdiv_code' and d.cir_code='$cir_code'
        //             and  l.nc_btad is null and b.dag_flag_type is null and  b.dag_no not in ( select dag_no from chitha_dag_all_flag_details_final df where df.subdiv_code = b.subdiv_code and df.cir_code = b.cir_code and
        //             df.mouza_pargona_code = b.mouza_pargona_code and df.lot_no = b.lot_no and df.vill_townprt_code = b.vill_townprt_code and df.dag_no = b.dag_no and (df.is_eroded ='7' or df.is_landclassless ='4' or df.is_sad ='3'))";



        $query = "select
        (select loc_name from location where subdiv_code=ncv.subdiv_code and cir_code=ncv.cir_code and mouza_pargona_code='00') as circle,
        (select loc_name from location where subdiv_code=ncv.subdiv_code and cir_code=ncv.cir_code and mouza_pargona_code=ncv.mouza_pargona_code and lot_no='00') as mouza,
        (select loc_name from location where subdiv_code=ncv.subdiv_code and cir_code=ncv.cir_code and mouza_pargona_code=ncv.mouza_pargona_code and lot_no=ncv.lot_no and vill_townprt_code='00000') as lot,
        (select loc_name from location where subdiv_code=ncv.subdiv_code and cir_code=ncv.cir_code and mouza_pargona_code=ncv.mouza_pargona_code and lot_no=ncv.lot_no and vill_townprt_code=ncv.vill_townprt_code) as village,
        ncv.dc_verified_at,ncv.status,ncv.application_no,ncv.lm_note,ncv.co_verified,ncv.co_note,ncv.dc_verified,ncv.co_verified_at,ncv.dc_note from nc_villages ncv";

        $query = $query . " where ncv.dist_code='$dist_code'";
        if ($subdiv_code) {
            $query = $query . " and ncv.subdiv_code = '$subdiv_code'";
        }
        if ($cir_code) {
            $query = $query . " and ncv.cir_code = '$cir_code'";
        }
        if ($mouza_pargona_code) {
            $query = $query . " and ncv.mouza_pargona_code = '$mouza_pargona_code'";
        }
        if ($lot_no) {
            $query = $query . " and ncv.lot_no = '$lot_no'";
        }
        if ($filter_status) {
            if ($filter_status == 'verified_lm') {
                $query = $query . " and ncv.lm_verified = 'Y'";
            }
            if ($filter_status == 'certified_co') {
                $query = $query . " and ncv.co_verified = 'Y'";
            }
            if ($filter_status == 'digi_signature_dc') {
                $query = $query . " and ncv.dc_verified = 'Y'";
            }
        }

        $query = $this->db->query($query);

        echo  json_encode(['data' => $query->result_array()]);
    }
    private function makeQuery($subdiv_code, $cir_code, $mouza_code, $lot_no, $vill_code)
    {
        $sql = "select 
        (select loc_name from location where dist_code=v.dist_code and subdiv_code='00') district,
        (select loc_name from location where dist_code=v.dist_code and subdiv_code=v.subdiv_code and cir_code='00') subdiv,
        (select loc_name from location where dist_code=v.dist_code and subdiv_code=v.subdiv_code and cir_code=v.cir_code and mouza_pargona_code='00') circle,
        (select loc_name from location where dist_code=v.dist_code and subdiv_code=v.subdiv_code and cir_code=v.cir_code and mouza_pargona_code=v.mouza_pargona_code and lot_no='00') mouza,
        (select loc_name from location where dist_code=v.dist_code and subdiv_code=v.subdiv_code and cir_code=v.cir_code and mouza_pargona_code=v.mouza_pargona_code and lot_no=v.lot_no and vill_townprt_code='00000') lot,
        (select loc_name from location where dist_code=v.dist_code and subdiv_code=v.subdiv_code and cir_code=v.cir_code and mouza_pargona_code=v.mouza_pargona_code and lot_no=v.lot_no and vill_townprt_code=v.vill_townprt_code) village,
        (select locname_eng from location where dist_code=v.dist_code and subdiv_code=v.subdiv_code and cir_code=v.cir_code and mouza_pargona_code=v.mouza_pargona_code and lot_no=v.lot_no and vill_townprt_code=v.vill_townprt_code) villageeng,
        rural_urban
        from location v where vill_townprt_code<>'00000'";
        $sub_query = '';
        if ($subdiv_code) {
            $sub_query = $sub_query . " and subdiv_code='$subdiv_code'";
        }
        if ($cir_code) {
            $sub_query = $sub_query . " and cir_code='$cir_code'";
        }
        if ($mouza_code) {
            $sub_query = $sub_query . " and mouza_pargona_code='$mouza_code'";
        }
        if ($lot_no) {
            $sub_query = $sub_query . " and lot_no='$lot_no'";
        }
        if ($vill_code) {
            $sub_query = $sub_query . " and vill_townprt_code='$vill_code'";
        }
        return $sql . $sub_query;
    }
    private function setLocationNames()
    {
        $dist    = $this->session->userdata('dcode');
        $subdiv  = $this->session->userdata('subdiv_code');
        $circle  = $this->session->userdata('cir_code');
        $mouza   = $this->session->userdata('mouza_pargona_code');
        $lot     = $this->session->userdata('lot_no');
        $village = $this->session->userdata('vill_townprt_code');
        $block   = $this->session->userdata('block_code');
        $panch   = $this->session->userdata('gram_panch_code');

        $data = $this->Chithamodel->getlocationnames($dist, $subdiv, $circle, $mouza, $lot, $village, $block, $panch);
        return $data;
    }
}
