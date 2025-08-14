<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class VillageController extends CI_Controller {

    use CommonTrait;
    public function index()
    {
        $district['base']=$this->config->item('base_url');
        $this->load->helper('html');
        $this->load->view('header');
        $session = $this->session->userdata('username');
        if ($session == 'lm')
        {
            $this->load->view('menu/menu1');
        }
        elseif ($session == 'sk')
        {
            $this->load->view('menu/menu2');
        }
        elseif ($session == 'oc')
        {
            $this->load->view('menu/menu3');
        }
        $this->load->model('mutationmodel');
        $this->load->helper('html');
        $dist_code   = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouzas   = $this->mutationmodel->getMouzaJSON($dist_code, $subdiv_code, $cir_code);
        $district['d'] = $dist_code;
        $district['s'] = $subdiv_code;
        $district['c'] = $cir_code;
        $district['mouzas'] = $mouzas;
        //////////var_dump($mouzas);
        $data = $this->mutationmodel->getDistricts();
        $district['names'] = $data;

        //$this->load->view('menu/menu4');

        $this->load->view('chitha_report/report1', $district);
        // $this->load->view('footer');
    }

    // Added by Dhruwa 13 Feb Start

    public function cacharreport()
    {
        $this->dataswitch();
        $data['base']=$this->config->item('base_url');
        $data['districts'] = $this->Chithamodel->districtdetailsreport();
        $data['_view'] = 'cacherreport';
        $this->load->view('layout/layout', $data);
    }

    // Added by Dhruwa 13 Feb End

    public function districtDetails() {
        //var_dump($this->session->userdata('dist_code'));
        $this->load->helper('html');
        $this->load->view('header');
        $this->load->view('chitha_report/report1');
        $this->load->view('footer');
    }

    public function districtDetails_dc_lao() {
        //var_dump($this->session->userdata('dist_code'));
        $this->load->helper('html');
        $this->load->view('header');
        $this->load->view('chitha_report/reportdclao');
        $this->load->view('footer');
    }

	public function getvillagedetails() {
		$this->dataswitch();
        $dist_code   = $this->input->post('dist_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $circle_code = $this->input->post('cir_code');
        $mouza_code  = $this->input->post('mouza_pargona_code');
        $lot_no      = $this->input->post('lot_no');
        $vill_code   = $this->input->post('vill_townprt_code');

        $this->load->model('MisModel');

        $vill_districtdata = $this->MisModel->getDistrictName($dist_code);
        $vill_districtdata = json_decode(json_encode($vill_districtdata), true);
        $this->session->set_userdata('vill_districtdata', $vill_districtdata[0]['district']);

        $vill_subdivdata   = $this->MisModel->getSubDivName($dist_code, $subdiv_code);
        $vill_subdivdata = json_decode(json_encode($vill_subdivdata), true);
        $this->session->set_userdata('vill_subdivdata', $vill_subdivdata[0]['subdiv']);

        $vill_circledata   = $this->MisModel->getCircleName($dist_code, $subdiv_code, $circle_code);
        $vill_circledata = json_decode(json_encode($vill_circledata), true);
        $this->session->set_userdata('vill_circledata', $vill_circledata[0]['circle']);

        $vill_mouzadata = $this->MisModel->getMouzaName($dist_code, $subdiv_code, $circle_code, $mouza_code);
        $vill_mouzadata = json_decode(json_encode($vill_mouzadata), true);
        $this->session->set_userdata('vill_mouzadata', $vill_mouzadata[0]['mouza']);

        $vill_lotdata = $this->MisModel->getLotName($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no);
        $vill_lotdata = json_decode(json_encode($vill_lotdata), true);
        $this->session->set_userdata('vill_lotdata', $vill_lotdata[0]['lot_no']);

        $vill_villagedata  = $this->MisModel->getVillageName($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code);
        $vill_villagedata = json_decode(json_encode($vill_villagedata), true);
        $this->session->set_userdata('vill_villagedata', $vill_villagedata[0]['village']);
        // print_r($vill_villagedata); die();

        // $sql = "select chitha_dag_pattadar.dag_no, chitha_dag_pattadar.patta_no, patta_code.patta_type, max(pdar_id) from chitha_dag_pattadar LEFT JOIN patta_code ON  chitha_dag_pattadar.patta_type_code = patta_code.type_code where chitha_dag_pattadar.dist_code='22' and chitha_dag_pattadar.subdiv_code='01' and chitha_dag_pattadar.cir_code='04' and chitha_dag_pattadar.mouza_pargona_code='02' and chitha_dag_pattadar.lot_no='01' and chitha_dag_pattadar.vill_townprt_code = '10001' GROUP BY chitha_dag_pattadar.patta_no, chitha_dag_pattadar.dag_no, patta_code.patta_type ORDER BY chitha_dag_pattadar.dag_no DESC ";

        $sql = "select chitha_dag_pattadar.dag_no, chitha_dag_pattadar.patta_no, chitha_dag_pattadar.dag_no_int, max(chitha_dag_pattadar.pdar_id), patta_code.patta_type from chitha_dag_pattadar LEFT JOIN patta_code ON  chitha_dag_pattadar.patta_type_code = patta_code.type_code where chitha_dag_pattadar.dist_code='$dist_code' and chitha_dag_pattadar.subdiv_code='$subdiv_code' and chitha_dag_pattadar.cir_code='$circle_code' and chitha_dag_pattadar.mouza_pargona_code='$mouza_code' and chitha_dag_pattadar.lot_no='$lot_no' and chitha_dag_pattadar.vill_townprt_code = '$vill_code' GROUP BY chitha_dag_pattadar.patta_no, chitha_dag_pattadar.dag_no, patta_code.patta_type, chitha_dag_pattadar.dag_no_int ORDER BY chitha_dag_pattadar.dag_no_int ASC";

        $getvillagedetails = $this->db->query($sql)->result();
        $data['villages'] = $getvillagedetails;
        $data['base']=$this->config->item('base_url');
        $data['_view'] = 'vill_list/vill_report';
        $this->load->view('layout/layout', $data);
    }
}



