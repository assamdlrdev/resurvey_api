<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class KhatianReportController extends CI_Controller
{
    use CommonTrait;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Chithamodel');
        $this->load->library('UtilityClass');
    }
    public function showFormLocation()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        //dd($this->db->database);
        $data['districts'] = $this->Chithamodel->districtdetails($this->session->userdata('dcode'));
        if ($this->session->userdata('svill_townprt_code') and $this->session->userdata('current_url') == current_url()) {
            $dist = $this->session->userdata('sdcode');
            $subdiv = $this->session->userdata('ssubdiv_code');
            $circle = (string) $this->session->userdata('scir_code');
            $mza = (string) $this->session->userdata('smouza_pargona_code');
            $lot = (string) $this->session->userdata('slot_no');
            $vill = (string) $this->session->userdata('svill_townprt_code');
            $currentURL = (string) $this->session->userdata('current_url');
            $data["khatian_start"] = $this->session->userdata('khatian_no_start');
            $data["khatian_end"] = $this->session->userdata('khatian_no_end');
            $data['locations'] = $this->Chithamodel->getSessionLoc($dist, $subdiv, $circle, $mza, $lot, $vill);
            $data["khatians"] = $this->getReqKhatianNos($dist, $subdiv, $circle, $mza, $lot, $vill);
            $data['current_url'] = $currentURL;
        } else {
            $data['locations'] = null;
            $data["khatian_start"] = null;
            $data["khatian_end"] = null;
            $data["khatians"] = null;
            $data['current_url'] = null;
        }
        $data['_view'] = 'reports/khatian/location_for_khatian_report';
        $this->load->view('layout/layout', $data);
    }
    public function showKhatianReport()
    {
        $this->load->library('UtilityClass');
        $this->form_validation->set_rules('dist_code', 'Ditrict ', 'trim|integer|required');
        $this->form_validation->set_rules('subdiv_code', 'Subdiv ', 'trim|integer|required');
        $this->form_validation->set_rules('cir_code', 'Circle ', 'trim|integer|required');
        $this->form_validation->set_rules('mouza_pargona_code', 'Mouza', 'trim|integer|required');
        $this->form_validation->set_rules('lot_no', 'Lot No', 'trim|integer|required');
        $this->form_validation->set_rules('vill_townprt_code', 'Village', 'trim|integer|required');
        $this->form_validation->set_rules('khatian_no_start', 'Khatian No Start', 'trim|integer|required');
        $this->form_validation->set_rules('khatian_no_end', 'Khatian No End', 'trim|integer|required');

        if ($this->form_validation->run() == false) {
            $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
            $this->showFormLocation();
            return;
        } else {
            $this->dataswitch();
            $dist_code = $this->input->post('dist_code');
            $subdiv_code = $this->input->post('subdiv_code');
            $cir_code = $this->input->post('cir_code');
            $mouza_pargona_code = $this->input->post('mouza_pargona_code');
            $lot_no = $this->input->post('lot_no');
            $vill_townprt_code = $this->input->post('vill_townprt_code');
            $khatian_no_start = $this->input->post('khatian_no_start');
            $khatian_no_end = $this->input->post('khatian_no_end');

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
            $this->session->set_userdata('khatian_no_start', $khatian_no_start);
            $this->session->set_userdata('khatian_no_end', $khatian_no_end);

            $query = "select * from chitha_tenant as c where c.dist_code='$dist_code' and c.subdiv_code='$subdiv_code' and "
                . " c.cir_code='$cir_code' and c.mouza_pargona_code='$mouza_pargona_code' and c.lot_no='$lot_no' and c.vill_townprt_code='$vill_townprt_code' and  c.khatian_no BETWEEN $khatian_no_start AND $khatian_no_end";
            $tenants = $this->db->query($query)->result();

            foreach ($tenants as $tenant) {
                $query = "select * from chitha_basic where dist_code='$dist_code' and subdiv_code='$subdiv_code' and "
                    . " cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' and dag_no='$tenant->dag_no'";
                $dag = $this->db->query($query)->row();
                if ($dag) {
                    $sql = "select cp.pdar_name,cp.pdar_father,cp.pdar_mother,cp.pdar_id,cp.subdiv_code,cp.dist_code,cp.cir_code,cp.mouza_pargona_code,cp.lot_no,cp.vill_townprt_code,cp.patta_no,cdp.dag_no from chitha_pattadar cp  join chitha_dag_pattadar cdp on cp.dist_code=cdp.dist_code  and cp.subdiv_code=cdp.subdiv_code and cp.cir_code=cdp.cir_code
	            and cp.mouza_pargona_code=cdp.mouza_pargona_code and cp.lot_no=cdp.lot_no and cp.vill_townprt_code=cdp.vill_townprt_code and cp.patta_type_code=cdp.patta_type_code and trim(cp.patta_no)=trim(cdp.patta_no) and cdp.pdar_id=cp.pdar_id where dag_no='$dag->dag_no' and cp.pdar_id=cdp.pdar_id and cp.dist_code='$dist_code' and cp.subdiv_code='$subdiv_code' and cp.cir_code='$cir_code' and cp.mouza_pargona_code='$mouza_pargona_code' and cp.lot_no='$lot_no' and cp.vill_townprt_code='$vill_townprt_code'";
                    $dag->pattadars = $this->db->query($sql)->result();
                }
                $tenant->dag_det = $dag;
            }
            $data['tenants'] = $tenants;
            $data['_view'] = 'reports/khatian/khatian_view';
            $this->load->view('layout/layout', $data);
        }
    }
    public function getKhatianNos()
    {
        $this->dataswitch();
        $data = [];
        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $vill_townprt_code = $this->input->post('vill_townprt_code');
        $query = "select distinct khatian_no from chitha_tenant where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and "
            . " mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' order by khatian_no";
        $khatians = $this->db->query($query)->result_array();
        foreach ($khatians as $value) {
            $data['khatian_no'][] = $value;
        }
        echo json_encode($data['khatian_no']);
    }

    public function getReqKhatianNos($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code)
    {
        $this->dataswitch();
        $data = [];
        $query = "select distinct khatian_no from chitha_tenant where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and "
            . " mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' order by khatian_no";
        $khatians = $this->db->query($query)->result_array();
        foreach ($khatians as $value) {
            $data['khatian_no'][] = $value;
        }
        return ($data['khatian_no']);
    }
}
