<?php
include APPPATH . '/libraries/CommonTrait.php';

class NcVillageCommonController extends CI_Controller
{
	use CommonTrait;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('UtilsModel');
		$this->load->model('DagReportModel');
		$this->load->model('Chithamodel');
		$this->load->model('CommonModel');
		$this->load->model('chitha/DharChithaModel');
		$this->load->model('NcVillageModel');
	}

	/** Bhunaksha Map */
	public function viewBhunaksaMap()
	{
		$data['data'] = $_POST;
		$area= $_POST['area'];
		$data['area'] = 0;
		if($area != 0)
		{
			$data['area'] = round($area/1000000,5);
		}
		$data['case_type'] = $_POST['case_type'];
		$data['merge_with_c_village'] = isset($_POST['merge_with_c_village']) && $_POST['merge_with_c_village'] ? json_decode($_POST['merge_with_c_village']) : '';
		$data['map_row'] = isset($_POST['map_row']) && $_POST['map_row'] ? json_decode($_POST['map_row']) : '';

		if($_POST['case_type'] == 'NC_TO_C')
		{
			$data['location'] = $data['merge_with_c_village']->request_dist_code.'_'. $data['merge_with_c_village']->request_subdiv_code.'_'.$data['merge_with_c_village']->request_cir_code.'_'.$data['merge_with_c_village']->request_mouza_pargona_code.'_'.$data['merge_with_c_village']->request_lot_no.'_'.$data['merge_with_c_village']->request_vill_townprt_code;
		}else{
			$data['location'] = $_POST['location'];
		}

		$data['_view'] = 'nc_village/common/bhunaksa_map';
		$this->load->view('layout/layout', $data);
	}

	public function viewBhunaksaMapPost()
	{
		$url = "https://landhub.assam.gov.in/api/index.php/BhunakshaApiController/getVillageGeoJson";
		$method = 'POST';
		$data['location'] = $_POST['location'];

		$output = $this->NcVillageModel->callApiV2($url, $method, $data);
		echo ($output);
		return;
	}
	public function notifications()
	{

		$data['_view'] = 'nc_village/common/notifications_common';
		$this->load->view('layout/layout', $data);
	}
	public function notificationView($notification_no, $dist_code, $proposal_no)
	{
		$this->dbswitch($dist_code);
		$proposal = $this->db->query("select * from nc_village_proposal where proposal_no=?", [$proposal_no])->row();
		$data['dist_code'] = $dist_code;
		$data['notification_no'] = $notification_no;
		$data['proposal_no'] = $proposal->proposal_no;
	$data['_view'] = 'nc_village/common/notification_villages_common';
		$this->load->view('layout/layout', $data);
	}
}
