<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class LocationController extends CI_Controller
{
    use CommonTrait;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Chithamodel');
    }

    /** Ads Get Subdiv */
	public function subdivisiondetails()
	{
		$this->db = $this->load->database('auth', TRUE);
		$dist_code = $this->input->post('dis');
		$formdata = $this->Chithamodel->subdivisiondetails($dist_code, $subdiv_code=null);
		foreach ($formdata as $value) {
			$data['subdiv_code'][] = $value;
		}
		echo json_encode($data['subdiv_code']);
	}

	/** Ads Get getCircles */
	public function circledetails()
	{
		$this->db = $this->load->database('auth', TRUE);
		$dist_code = $this->input->post('dis');
		$subdiv_code = $this->input->post('subdiv');
		$formdata =  $this->Chithamodel->circledetails($dist_code, $subdiv_code);

		foreach ($formdata as $value) {
			$data['cir_code'][] = $value;
		}
		echo json_encode($data['cir_code']);
	}


	/** Ads Get mouzadetails */
	public function mouzadetails()
	{
		$this->db = $this->load->database('auth', TRUE);
		$dist_code = $this->input->post('dis');
		$subdiv_code = $this->input->post('subdiv');
		$cir_code = $this->input->post('cir');
		$formdata = $this->Chithamodel->mouzadetails($dist_code, $subdiv_code, $cir_code);
		foreach ($formdata as $value) {
			$data['mouza_pargona_code'][] = $value;
		}
		echo json_encode($data['mouza_pargona_code']);
	}

	/** Ads Get lotdetails */
	public function lotdetails()
	{
		$this->db = $this->load->database('auth', TRUE);
		$dist_code = $this->input->post('dis');
		$subdiv_code = $this->input->post('subdiv');
		$cir_code = $this->input->post('cir');
		$mouza_pargona_code = $this->input->post('mza');
		$formdata = $this->Chithamodel->lotdetails($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code);
		foreach ($formdata as $value) {
			$data['lot_no'][] = $value;
		}
		echo json_encode($data['lot_no']);
	}

    public function getSubdivs($dist_code = null)
    {
        $this->dataswitch();
        $data = [];
        $dist_code = $dist_code ? $dist_code : $this->session->userdata('dcode');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $formdata = $this->Chithamodel->subdivisiondetails($dist_code, $subdiv_code);
        foreach ($formdata as $value) {
            $data['subdiv_code'][] = $value;
        }
        echo json_encode($data['subdiv_code']);
    }
    public function getCircles()
    {
        $this->dataswitch();
        $data = [];
        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $formdata = $this->Chithamodel->circledetails($dist_code, $subdiv_code, $cir_code);
        foreach ($formdata as $value) {
            $data['cir_code'][] = $value;
        }
        echo json_encode($data['cir_code']);
    }
    public function getMouzas()
    {
        $this->dataswitch();
        $data = [];
        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $formdata = $this->Chithamodel->mouzadetails($dist_code, $subdiv_code, $cir_code);
        foreach ($formdata as $value) {
            $data['mouza_pargona_code'][] = $value;
        }
        echo json_encode($data['mouza_pargona_code']);
    }
    public function getLots()
    {
        $this->dataswitch();
        $data = [];
        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $formdata = $this->Chithamodel->lotdetails($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code);
        foreach ($formdata as $value) {
            $data['lot_no'][] = $value;
        }
        echo json_encode($data['lot_no']);
    }
    public function getVillages()
    {
        $this->dataswitch();
        $data = [];
        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $formdata = $this->Chithamodel->villagedetails($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no);
        foreach ($formdata as $value) {
            $data['vill_townprt_code'][] = $value;
        }
        echo json_encode($data['vill_townprt_code']);
    }

    /********* DBSWITCH FOR ADMIN ********/
    public function dataSwitchforAdmin($dist_code)
    {
        $CI = &get_instance();
        if ($dist_code == "02") {
            $this->db = $CI->load->database('lsp3', TRUE);
        } else if ($dist_code == "05") {
            $this->db = $CI->load->database('lsp1', TRUE);
        } else if ($dist_code == "13") {
            $this->db = $CI->load->database('lsp2', TRUE);
        } else if ($dist_code == "17") {
            $this->db = $CI->load->database('lsp4', TRUE);
        } else if ($dist_code == "15") {
            $this->db = $CI->load->database('lsp5', TRUE);
        } else if ($dist_code == "14") {
            $this->db = $CI->load->database('lsp6', TRUE);
        } else if ($dist_code == "07") {
            $this->db = $CI->load->database('lsp7', TRUE);
        } else if ($dist_code == "03") {
            $this->db = $CI->load->database('lsp8', TRUE);
        } else if ($dist_code == "18") {
            $this->db = $CI->load->database('lsp9', TRUE);
        } else if ($dist_code == "12") {
            $this->db = $CI->load->database('lsp13', TRUE);
        } else if ($dist_code == "24") {
            $this->db = $CI->load->database('lsp10', TRUE);
        } else if ($dist_code == "06") {
            $this->db = $CI->load->database('lsp11', TRUE);
        } else if ($dist_code == "11") {
            $this->db = $CI->load->database('lsp12', TRUE);
        } else if ($dist_code == "12") {
            $this->db = $CI->load->database('lsp13', TRUE);
        } else if ($dist_code == "16") {
            $this->db = $CI->load->database('lsp14', TRUE);
        } else if ($dist_code == "32") {
            $this->db = $CI->load->database('lsp15', TRUE);
        } else if ($dist_code == "33") {
            $this->db = $CI->load->database('lsp16', TRUE);
        } else if ($dist_code == "34") {
            $this->db = $CI->load->database('lsp17', TRUE);
        } else if ($dist_code == "21") {
            $this->db = $CI->load->database('lsp18', TRUE);
        } else if ($dist_code == "08") {
            $this->db = $CI->load->database('lsp19', TRUE);
        } else if ($dist_code == "35") {
            $this->db = $CI->load->database('lsp20', TRUE);
        } else if ($dist_code == "36") {
            $this->db = $CI->load->database('lsp21', TRUE);
        } else if ($dist_code == "37") {
            $this->db = $CI->load->database('lsp22', TRUE);
        } else if ($dist_code == "25") {
            $this->db = $CI->load->database('lsp23', TRUE);
        } else if ($dist_code == "10") {
            $this->db = $CI->load->database('lsp24', TRUE);
        } else if ($dist_code == "38") {
            $this->db = $CI->load->database('lsp25', TRUE);
        } else if ($dist_code == "39") {
            $this->db = $CI->load->database('lsp26', TRUE);
        } else if ($dist_code == "22") {
            $this->db = $CI->load->database('lsp27', TRUE);
        } else if ($dist_code == "23") {
            $this->db = $CI->load->database('lsp28', TRUE);
        } else if ($dist_code == "01") {
            $this->db = $CI->load->database('lsp29', TRUE);
        } else if ($dist_code == "27") {
            $this->db = $CI->load->database('lsp30', TRUE);
        }
    }
}