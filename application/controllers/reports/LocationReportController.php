<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class LocationReportController extends CI_Controller
{
    use CommonTrait;
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Chithamodel');
    }
    public function index(){
        $data['district'] = $this->Chithamodel->districtdetails($this->session->userdata('dcode'));
        $data['sub_divs'] = $this->Chithamodel->subdivisiondetails($this->session->userdata('dcode'));
        $data['_view'] = 'reports/locations_report';
        $this->load->view('layout/layout', $data);
    }
    public function getLocations(){
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $vill_code = $this->input->post('vill_code');

        $this->dataswitch();
        $sql = $this->makeQuery($subdiv_code,$cir_code,$mouza_code,$lot_no,$vill_code);
        $q = $this->db->query($sql);

        echo  json_encode(['data' => $q->result_array()]);
    }
    private function makeQuery($subdiv_code,$cir_code,$mouza_code,$lot_no,$vill_code){
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
        if($subdiv_code){
            $sub_query = $sub_query." and subdiv_code='$subdiv_code'";
        }
        if($cir_code){
            $sub_query = $sub_query." and cir_code='$cir_code'";
        }
        if($mouza_code){
            $sub_query = $sub_query." and mouza_pargona_code='$mouza_code'";
        }
        if($lot_no){
            $sub_query = $sub_query." and lot_no='$lot_no'";
        }
        if($vill_code){
            $sub_query = $sub_query." and vill_townprt_code='$vill_code'";
        }
        return $sql.$sub_query;
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