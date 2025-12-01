<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class AdminLocationController extends CI_Controller
{
	use CommonTrait;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Chithamodel');
	}

	/***** Get Sub Div For Admin *****/
	public function getSubdivs($subdiv_code = null){
		$this->dataswitch();
		$data = [];
		$dist_code = $this->input->post('id');
		$formdata = $this->Chithamodel->subdivisiondetails($dist_code, $subdiv_code);
		foreach ($formdata as $value) {
			$data['subdiv_code'][] = $value;
		}
		echo json_encode($data['subdiv_code']);
	}
	public function getCircles(){
		$this->dataswitch();
		$data = [];
		$dist_code = $this->input->post('dis');
		$subdiv_code = $this->input->post('subdiv');
		$formdata = $this->Chithamodel->circledetails($dist_code,$subdiv_code,$cir_code = null);
		foreach ($formdata as $value) {
			$data['cir_code'][] = $value;
		}
		echo json_encode($data['cir_code']);
	}
	public function getMouzas(){
		$this->dataswitch();
		$data = [];
		$dist_code = $this->input->post('dis');
		$subdiv_code = $this->input->post('subdiv');
		$cir_code = $this->input->post('cir');
		$formdata = $this->Chithamodel->mouzadetails($dist_code,$subdiv_code,$cir_code);
		foreach ($formdata as $value) {
			$data['mouza_pargona_code'][] = $value;
		}
		echo json_encode($data['mouza_pargona_code']);
	}
	public function getLots(){
		$this->dataswitch();
		$data = [];
		$dist_code = $this->input->post('dis');
		$subdiv_code = $this->input->post('subdiv');
		$cir_code = $this->input->post('cir');
		$mouza_pargona_code = $this->input->post('mza');
		$formdata = $this->Chithamodel->lotdetails($dist_code,$subdiv_code,$cir_code,$mouza_pargona_code);
		foreach ($formdata as $value) {
			$data['lot_no'][] = $value;
		}
		echo json_encode($data['lot_no']);
	}
	public function getVillages(){
		$this->dataswitch();
		$data = [];
		$dist_code = $this->input->post('dis');
		$subdiv_code = $this->input->post('subdiv');
		$cir_code = $this->input->post('cir');
		$mouza_pargona_code = $this->input->post('mza');
		$lot_no = $this->input->post('lot');
		$formdata = $this->Chithamodel->villagedetails($dist_code,$subdiv_code,$cir_code,$mouza_pargona_code,$lot_no);
		foreach ($formdata as $value) {
			$data['vill_townprt_code'][] = $value;
		}
		echo json_encode($data['vill_townprt_code']);
	}

	public function locationSubmit()
	{
		$data = array();
		$this->form_validation->set_rules('dist_code', 'District Name', 'trim|integer|required');
		$this->form_validation->set_rules('subdiv_code', 'Sub Division Name', 'trim|integer|required');
		$this->form_validation->set_rules('cir_code', 'Circle Name', 'trim|integer|required');
		$this->form_validation->set_rules('mouza_pargona_code', 'Mouza Name', 'trim|integer|required');
		$this->form_validation->set_rules('lot_no', 'Lot Number', 'trim|integer|required');
		$this->form_validation->set_rules('vill_townprt_code', 'Village Name', 'trim|integer|required');

		if ($this->form_validation->run() == false) {
			$text = str_ireplace('<\/p>', '', validation_errors());
			$text = str_ireplace('<p>', '', $text);
			$text = str_ireplace('</p>', '', $text);
			echo json_encode(array('msg' => $text, 'st' => 0));
			return;
		} else {
			$location = array(
				'd' => $this->input->post('dist_code'),
				'sb' => $this->input->post('subdiv_code'),
				'c' => $this->input->post('cir_code'),
				'm' => $this->input->post('mouza_pargona_code'),
				'l' => $this->input->post('lot_no'),
				'v' => $this->input->post('vill_townprt_code'),
			);
			echo json_encode(array('msg' => 'Proceed for Jama Pattadar Bulk Update', 'location' =>$location, 'st' => 1));
		}
	}
}
