<?php

include APPPATH . '/libraries/CommonTrait.php';
class NcVillageReportController extends CI_Controller
{
	use CommonTrait;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('CommonModel');
		$this->load->model('UtilsModel');
	}

	/** Show villages */
	public function index()
	{
		$this->db = $this->load->database('default', TRUE);
		$data['locations'] = $this->db->get_where('location', array(
			'dist_code !=' => '00',
			'subdiv_code' => '00',
			'cir_code' => '00',
			'mouza_pargona_code' => '00',
			'lot_no' => '00',
			'vill_townprt_code' => '00000'
		))
			->result_array();
		$this->dbswitch('25');
		$data['columns'] = $this->db->list_fields('nc_villages');
		$data['_view'] = 'nc_village/report/nc_village_report';
		$this->load->view('layout/layout', $data);
	}
	/** Show villages */
	public function index_track()
	{
		$data['districts'] = $this->db->get_where('location', array('subdiv_code' => '00', 'cir_code' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->result_array();
		$data['_view'] = 'nc_village/report/nc_village_track';
		$this->load->view('layout/layout', $data);
	}

	public function getVillages()
	{
		$dist_code = $this->input->post('dist_code');
		$subdiv_code = $this->input->post('subdiv_code');
		$cir_code = $this->input->post('cir_code');
		$mouza_pargona_code = $this->input->post('mouza_pargona_code');
		$lot_no = $this->input->post('lot_no');
		$filter = $this->input->post('filter');

		$this->dbswitch($dist_code);
		$query = "select l.loc_name,l2.loc_name as lot_name,l3.loc_name as mouza_name,l4.loc_name as circle_name,ncv.*,lm.lm_name,user_sk.username as sk_name,user_co.username as co_name,user_dc.username as dc_name from nc_villages ncv 
        join location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and ncv.vill_townprt_code = l.vill_townprt_code 
        join location l2 on ncv.dist_code = l2.dist_code and ncv.subdiv_code = l2.subdiv_code and ncv.cir_code = l2.cir_code and ncv.mouza_pargona_code = l2.mouza_pargona_code and ncv.lot_no = l2.lot_no and l2.vill_townprt_code='00000'
        join location l3 on ncv.dist_code = l3.dist_code and ncv.subdiv_code = l3.subdiv_code and ncv.cir_code = l3.cir_code and ncv.mouza_pargona_code = l3.mouza_pargona_code and l3.lot_no='00'
        join location l4 on ncv.dist_code = l4.dist_code and ncv.subdiv_code = l4.subdiv_code and ncv.cir_code = l4.cir_code and l4.mouza_pargona_code='00'
        left join lm_code lm on ncv.dist_code = lm.dist_code and ncv.subdiv_code = lm.subdiv_code and ncv.cir_code = lm.cir_code and ncv.mouza_pargona_code=lm.mouza_pargona_code and ncv.lot_no=lm.lot_no and ncv.lm_code=lm.lm_code
        left join users user_sk on ncv.dist_code = user_sk.dist_code and ncv.subdiv_code = user_sk.subdiv_code and ncv.cir_code = user_sk.cir_code and ncv.sk_user_code=user_sk.user_code
        left join users user_co on ncv.dist_code = user_co.dist_code and ncv.subdiv_code = user_co.subdiv_code and ncv.cir_code = user_co.cir_code and ncv.co_code=user_co.user_code
        left join users user_dc on ncv.dist_code = user_dc.dist_code and ncv.dc_code=user_dc.user_code";

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

		// if ($filter == 'I') {
		// 	$query = $query . " and ncv.dc_verified = 'Y'";
		// }

		$result = $this->db->query($query)->result();

		echo json_encode($result);
	}
	public function getNcVillages()
	{
		$dist_code = $this->input->post('dist_code');
		$subdiv_code = $this->input->post('subdiv_code');
		$cir_code = $this->input->post('cir_code');
		$mouza_pargona_code = $this->input->post('mouza_pargona_code');
		$lot_no = $this->input->post('lot_no');
		$villages = $this->ncVillages($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no);
		echo json_encode($villages);
	}
	public function ncVillages($dist_code, $subdiv_code = null, $cir_code = null, $mouza_pargona_code = null, $lot_no = null)
	{

		$this->dbswitch($dist_code);
		$query = "
			SELECT 
				l.loc_name,
				l2.loc_name AS lot_name,
				l3.loc_name AS mouza_name,
				l4.loc_name AS circle_name,
				ncv.*,
				lm.lm_name,
				user_sk.username AS sk_name,
				user_co.username AS co_name,
				user_dc.username AS dc_name,
				COUNT(ncvd.id) AS co_chitha_verified,
				COUNT(ncvd2.id) AS dc_chitha_verified,
				ncvp.proposal_no,
				ncvp2.proposal_no as proposal_no_dc
			FROM 
				nc_villages ncv
			JOIN location l 
				ON ncv.dist_code = l.dist_code 
				AND ncv.subdiv_code = l.subdiv_code 
				AND ncv.cir_code = l.cir_code 
				AND ncv.mouza_pargona_code = l.mouza_pargona_code 
				AND ncv.lot_no = l.lot_no 
				AND ncv.vill_townprt_code = l.vill_townprt_code
			JOIN location l2 
				ON ncv.dist_code = l2.dist_code 
				AND ncv.subdiv_code = l2.subdiv_code 
				AND ncv.cir_code = l2.cir_code 
				AND ncv.mouza_pargona_code = l2.mouza_pargona_code 
				AND ncv.lot_no = l2.lot_no 
				AND l2.vill_townprt_code = '00000'
			JOIN location l3 
				ON ncv.dist_code = l3.dist_code 
				AND ncv.subdiv_code = l3.subdiv_code 
				AND ncv.cir_code = l3.cir_code 
				AND ncv.mouza_pargona_code = l3.mouza_pargona_code 
				AND l3.lot_no = '00'
			JOIN location l4 
				ON ncv.dist_code = l4.dist_code 
				AND ncv.subdiv_code = l4.subdiv_code 
				AND ncv.cir_code = l4.cir_code 
				AND l4.mouza_pargona_code = '00'
			LEFT JOIN nc_village_dags ncvd 
				ON ncv.dist_code = ncvd.dist_code 
				AND ncv.subdiv_code = ncvd.subdiv_code 
				AND ncv.cir_code = ncvd.cir_code 
				AND ncv.mouza_pargona_code = ncvd.mouza_pargona_code 
				AND ncv.lot_no = ncvd.lot_no 
				AND ncv.vill_townprt_code = ncvd.vill_townprt_code 
				AND ncvd.co_verified = 'Y'
			LEFT JOIN nc_village_dags ncvd2 
				ON ncv.dist_code = ncvd.dist_code 
				AND ncv.subdiv_code = ncvd.subdiv_code 
				AND ncv.cir_code = ncvd.cir_code 
				AND ncv.mouza_pargona_code = ncvd.mouza_pargona_code 
				AND ncv.lot_no = ncvd.lot_no 
				AND ncv.vill_townprt_code = ncvd.vill_townprt_code 
				AND ncvd.dc_verified = 'Y'
			LEFT JOIN nc_village_proposal ncvp 
				ON ncv.proposal_id = ncvp.id and ncvp.user_type='CO'
			LEFT JOIN nc_village_proposal ncvp2 
				ON ncv.dc_proposal_id = ncvp2.id and ncvp2.user_type='DC'
			LEFT JOIN lm_code lm 
				ON ncv.dist_code = lm.dist_code 
				AND ncv.subdiv_code = lm.subdiv_code 
				AND ncv.cir_code = lm.cir_code 
				AND ncv.mouza_pargona_code = lm.mouza_pargona_code 
				AND ncv.lot_no = lm.lot_no 
				AND ncv.lm_code = lm.lm_code
			LEFT JOIN users user_sk 
				ON ncv.dist_code = user_sk.dist_code 
				AND ncv.subdiv_code = user_sk.subdiv_code 
				AND ncv.cir_code = user_sk.cir_code 
				AND ncv.sk_user_code = user_sk.user_code
			LEFT JOIN users user_co 
				ON ncv.dist_code = user_co.dist_code 
				AND ncv.subdiv_code = user_co.subdiv_code 
				AND ncv.cir_code = user_co.cir_code 
				AND ncv.co_code = user_co.user_code
			LEFT JOIN users user_dc 
				ON ncv.dist_code = user_dc.dist_code 
				AND ncv.dc_code = user_dc.user_code
		";

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
		$query = $query . "GROUP BY 
				l.loc_name,
				l2.loc_name,
				l3.loc_name,
				l4.loc_name,
				lm.lm_name,
				user_sk.username,
				user_co.username,
				user_dc.username,
				ncv.dist_code,
				ncv.subdiv_code,
				ncv.cir_code,
				ncv.mouza_pargona_code,
				ncv.lot_no,
				ncv.vill_townprt_code,
				ncv.sk_user_code,
				ncv.co_code,
				ncv.dc_code,
				ncv.id,
				ncv.application_no,
				ncvp.proposal_no,
				ncvp2.proposal_no
				";

		return $this->db->query($query)->result();
	}
	public function getNcMaps()
	{
		$data['d'] = $this->input->post('dist_code');
		$data['s'] = $this->input->post('subdiv_code');
		$data['c'] = $this->input->post('cir_code');
		$data['m'] = $this->input->post('mouza_pargona_code');
		$data['l'] = $this->input->post('lot_no');
		$data['v'] = $this->input->post('vill_townprt_code');

		$url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/apiGetMap";

		$method = 'POST';
		$output = callIlrmsApi($url, $method, $data);
		if (sizeof($output->data) != 0) {
			echo json_encode(['maps' => $output->data]);
		} else {
			echo 'NA';
		}
	}
	/** View Map */
	public function viewUploadedMap()
	{
		$data['id'] = $_GET['id'];
		$data['d'] = $this->session->userdata('dist_code');

		$url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/apiGetMapBase64";

		$method = 'POST';
		$output = callIlrmsApi($url, $method, $data);
		if ($output->data) {
			if ($output->data->base64 != null) {
				header("Content-type: " . $output->data->mime);
				$data = $output->data->base64;
				echo base64_decode($data);
				die;
			} else {
				echo "Map Not Found..!";
				return;
			}
		}
		echo "Map Not Found..!";
	}
	public function ncVillagesExcelDownload()
	{
		$dist_code = $_GET['d'];
		$subdiv_code = $_GET['s'];
		$cir_code = $_GET['c'];
		$mouza_pargona_code = $_GET['m'] ?: null;
		$lot_no = $_GET['l'] ?: null;

		/***** Fetch records from database and store in an array *****/
		$villages = $this->ncVillages($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no);

		/***** Excel file name for download  *****/
		$fileName = "nc_villages_status" . date('Y-m-d') . ".xlsx";

		//make sheet head string
		$district = $this->db->query("select loc_name from location where dist_code=? and subdiv_code='00'", [$dist_code])->row();
		$header_string = "Location: District - '$district->loc_name'";
		if ($subdiv_code) {
			$subdiv = $this->db->query("select loc_name from location where dist_code=? and subdiv_code=? and cir_code='00'", [$dist_code, $subdiv_code])->row();
			$header_string = $header_string . ", Sub-division - '$subdiv->loc_name'";
		}
		if ($cir_code) {
			$circle = $this->db->query("select loc_name from location where dist_code=? and subdiv_code=? and cir_code=? and mouza_pargona_code='00'", [$dist_code, $subdiv_code, $cir_code])->row();
			$header_string = $header_string . ", Circle - '$circle->loc_name'";
		}
		if ($mouza_pargona_code) {
			$mouza = $this->db->query("select loc_name from location where dist_code=? and subdiv_code=? and cir_code=? and mouza_pargona_code=? and lot_no='00'", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code])->row();
			$header_string = $header_string . ", Mouza - '$mouza->loc_name'";
		}
		if ($lot_no) {
			$lot = $this->db->query("select loc_name from location where dist_code=? and subdiv_code=? and cir_code=? and mouza_pargona_code=? and lot_no=? and vill_townprt_code='00000'", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no])->row();
			$header_string = $header_string . ", Lot - '$lot->loc_name'";
		}
		/***** Sheet head  *****/
		$sheet_head = array($header_string);

		/***** Define column names *****/
		$table_header = [
			'Circle',
			'Mouza',
			'Lot',
			'Village Name',
			'Current User',
			'Previous User',
			'LM Verified',
			'SK Verified',
			'CO Verified',
			'CO Chitha Certified',
			'CO Proposal Sent',
			'DC Verified',
			'DC Chitha Certified',
			'DC Chitha Sign',
			'DC Proposal Sent'
		];

		/** Make data rows fro excel */
		$dataRows = array();
		foreach ($villages as $key => $v) {
			$is_lm_verified = $v->lm_verified == 'Y' ? 'Yes' : 'No';
			$is_sk_verified = $v->sk_verified == 'Y' ? 'Yes' : 'No';
			$is_co_verified = $v->co_verified == 'Y' ? 'Yes' : 'No';
			$is_co_chitha_verified = $v->co_chitha_verified > 0 ? 'Yes' : 'No';
			$is_co_proposal_sent = $v->co_proposal == 'Y' ? 'Yes' : 'No';
			$is_dc_verified = $v->dc_verified == 'Y' ? 'Yes' : 'No';
			$is_dc_chitha_verified = $v->dc_chitha_verified > 0 ? 'Yes' : 'No';
			$is_dc_chitha_sign = $v->dc_chitha_sign > 0 ? 'Yes' : 'No';
			$is_dc_proposal_sent = $v->dc_proposal == 'Y' ? 'Yes' : 'No';
			$lineData = [
				$v->circle_name,
				$v->mouza_name,
				$v->lot_name,
				$v->loc_name,
				$v->cu_user,
				$v->pre_user,
				$is_lm_verified,
				$is_sk_verified,
				$is_co_verified,
				$is_co_chitha_verified,
				$is_co_proposal_sent,
				$is_dc_verified,
				$is_dc_chitha_verified,
				$is_dc_chitha_sign,
				$is_dc_proposal_sent
			];
			$dataRows[] = $lineData;
		}

		/**** Generate Excel***/
		$this->generateExcel($sheet_head, $table_header, $fileName, $dataRows);
	}


	/***** Generate Excel *****/
	public function generateExcel($sheet_head, $table_header, $filename, $result_array)
	{
		/***** Include XLSX generator library *****/
		include APPPATH . '/libraries/Xlsxwriter.class.php';

		ini_set('display_errors', 1);
		ini_set('log_errors', 1);

		$styles1 = array(
			'font' => 'Arial',
			'font-size' => 12,
			'font-style' => 'bold',
			'fill' => '#FFFF00',
			'halign' => 'center',
			'border' => 'left,right,top,bottom',
		);
		$styles7 = array('font' => 'Arial', 'font-size' => 12, 'border' => 'left,right,top,bottom');

		header('Content-disposition: attachment; filename="' . XLSXWriter::sanitize_filename($filename) . '"');
		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		header('Content-Transfer-Encoding: binary');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		ob_clean();

		$writer = new XLSXWriter();
		$writer->setAuthor('Chitha_Entry');
		$sheet_name = 'Sheet1';
		$writer->writeSheetRow($sheet_name, $sheet_head, $styles7);
		$writer->writeSheetRow($sheet_name, $table_header, $styles1);

		/***** Generate rows *****/
		foreach ($result_array as $row) {
			$writer->writeSheetRow($sheet_name, $row, $styles7);
		}

		$writer->markMergedCell($sheet_name, $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 4);
		$writer->writeToStdOut();
		exit(0);
	}
}
