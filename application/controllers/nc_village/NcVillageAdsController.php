<?php

include APPPATH . '/libraries/CommonTrait.php';
class NcVillageAdsController extends CI_Controller
{
	use CommonTrait;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('CommonModel');
		$this->load->model('UtilsModel');
	}

	/** Show villages */
	public function showVillages()
	{
		$this->db = $this->load->database('default', TRUE);
		$data['locations'] = $this->db->get_where('location', array('dist_code !=' => '00', 'subdiv_code' => '00',
			'cir_code' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))
			->result_array();

		$data['_view'] = 'nc_village/ads/villages_g';
		$this->load->view('layout/layout', $data);
	}

	/** get villages */
	public function getVillagesG()
	{
		$dist_code = $this->input->post('dist_code');
		$subdiv_code = $this->input->post('subdiv_code');
		$cir_code = $this->input->post('cir_code');
		$mouza_pargona_code = $this->input->post('mouza_pargona_code');
		$lot_no = $this->input->post('lot_no');
		$filter = $this->input->post('filter');

		$this->dbswitch($dist_code);
		$query = "select l.loc_name,ncv.dist_code,ncv.dc_verified_at,ncv.status,ncv.application_no,ncv.lm_note,ncv.co_verified,ncv.co_note,ncv.dc_verified,ncv.co_verified_at,ncv.dc_note from nc_villages ncv join location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code
                 and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and ncv.vill_townprt_code = l.vill_townprt_code";

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
//		if ($filter == 'G') {
//			$query = $query . " and ncv.status='G'";
//		}
		if ($filter == 'I') {
			$query = $query . " and ncv.dc_verified = 'Y'";
		}

		$result = $this->db->query($query)->result();

		echo json_encode($result);
	}

	/** Show village dags */
	public function showDags()
	{
		$dist_code = $_GET['dist'];
		$this->dbswitch($dist_code);
		$application_no = $_GET['application_no'];

		$nc_village = $this->db->query("select * from nc_villages where application_no='$application_no' and dist_code='$dist_code'")->row();
		if (!$nc_village) {
			show_404();
			return;
		}
		$data['locations'] = $this->CommonModel->getLocations(
			$dist_code,
			$nc_village->subdiv_code,
			$nc_village->cir_code,
			$nc_village->mouza_pargona_code,
			$nc_village->lot_no,
			$nc_village->vill_townprt_code
		);

		$q3 = "select count(*) as count from nc_village_dags where dist_code=? AND subdiv_code=? AND cir_code=? 
        	AND mouza_Pargona_code=? AND lot_No=? AND vill_townprt_code=? and dc_verified IS NULL and dc_chitha_sign IS NULL";
		$check_unverified_dag = $this->db->query($q3, array(
			$dist_code, $nc_village->subdiv_code, $nc_village->cir_code,
			$nc_village->mouza_pargona_code, $nc_village->lot_no, $nc_village->vill_townprt_code
		))->row()->count;

		$data['verified'] = 'N';
		if ($check_unverified_dag == 0) {
			$data['verified'] = 'Y';
		}

		$data['nc_village'] = $nc_village;
		$data['_view'] = 'nc_village/ads/village_g_dags';
		$this->load->view('layout/layout', $data);
	}

	/** get Dags */
	public function getDags()
	{
		$dist_code = $_POST['dist_code'];
		$this->dbswitch($dist_code);
		$application_no = $this->input->post('application_no');
		$nc_village = $this->db->query("select * from nc_villages where application_no='$application_no'")->row();

		$dc = $this->db->query("select * from users where dist_code='$dist_code' and subdiv_code='00' 
					and cir_code='00' and user_desig_code='DC'")->row();
		$co = $this->db->query("select * from users where dist_code='$nc_village->dist_code' and subdiv_code='$nc_village->subdiv_code' and cir_code='$nc_village->cir_code' and user_code='$nc_village->co_code'")->row();

		$dags = $this->CommonModel->getNcVillageDags($application_no);

		echo json_encode(array('dags' => $dags, 'dc_name' => $dc->username, 'co_name' => $co ? $co->username : ''));

		return;
	}
}

?>
