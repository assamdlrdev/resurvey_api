<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';
class TranslateController extends CI_Controller
{
	use CommonTrait;

	function __construct()
	{
		parent::__construct();
		$this->load->model('CommonModel');
		$this->load->model('SvamitvaModel');
		$this->load->helper('security');
		$this->load->helper(['form', 'url']);
		if ($this->session->userdata('usertype') != '2' && $this->session->userdata('usertype') != '00') {
			$data['heading'] = "ERROR:: 404 Page Not Found";
			$data['message'] = 'The page you requested was not found';
			$this->load->view('errors/html/error_404', $data);
			$this->CI =& get_instance();
			$this->CI->output->_display();
			die;
		}
	}

	/** Location Select */
	public function location()
	{
		$data['base'] = $this->config->item('base_url');

		if ($this->session->userdata('usertype') == '2'){
			$this->db = $this->load->database('default', TRUE);
			$data['districts'] = $this->getDistList();
			$data['_view'] = 'svamitva_card/translate_name/location';
		}
		else if ($this->session->userdata('usertype') == '00')
		{
			$this->dataswitch();
			$distcode = $this->session->userdata('dcode');
			$subdiv_code = $this->session->userdata('subdiv_code');
			$cir = (string) $this->session->userdata('cir_code');
			$data['locations'] = $this->CommonModel->getLocations($distcode, $subdiv_code, $cir);
			$formdata = $this->Chithamodel->mouzadetails($distcode,$subdiv_code,$cir);
			foreach ($formdata as $value) {
				$data['mouza_pargona_code'][] = $value;
			}
			$data['_view'] = 'svamitva_card/translate_name/location_deo';
		}

		$data['page_title'] = 'Select Location | Encroachers Name Translate';
		$data['page_header'] = 'Location Details For Encroachers Name Translate';
		$data['breadcrumbs'] = '
        <li class="breadcrumb-item"><a href="#">Data Entry</a></li>
        <li class="breadcrumb-item"><a href="#">Encroachers Name Translate</a></li>
        <li class="breadcrumb-item active">Select Location Details</li>';

		if ($this->session->userdata('usertype') == '2'){
			$data['_view'] = 'svamitva_card/translate_name/location';
		}else if ($this->session->userdata('usertype') == '00'){
			$data['_view'] = 'svamitva_card/translate_name/location_deo';
		}
		$this->load->view('layout/layout', $data);
	}

	/** View  Encroachers List */
	public function viewEncroachers()
	{
		$data['base'] = $this->config->item('base_url');
		$this->form_validation->set_rules('dist_code', 'District Name', 'trim|required');
		$this->form_validation->set_rules('subdiv_code', 'Subdivision Name', 'trim|required');
		$this->form_validation->set_rules('cir_code', 'Circle Name', 'trim|required');
		$this->form_validation->set_rules('mouza_pargona_code', 'Mouza Name', 'trim|required');
		$this->form_validation->set_rules('lot_no', 'Lot Name', 'trim|required');
		$this->form_validation->set_rules('vill_townprt_code', 'Village Name', 'trim|required');

		if ($this->form_validation->run() == FALSE) {
			$this->db = $this->load->database('default', TRUE);
			$data['districts'] = $this->getDistList();
			$data['_view'] = 'svamitva_card/translate_name/location';
			$data['page_title'] = 'Select Location | Encroachers Name Translate';
			$data['page_header'] = 'Location Details For Encroachers Name Translate';
			$data['breadcrumbs'] = '
			<li class="breadcrumb-item"><a href="#">Data Entry</a></li>
			<li class="breadcrumb-item"><a href="#">Encroachers Name Translate</a></li>
			<li class="breadcrumb-item active">Select Location Details</li>';
			$this->load->view('layout/layout', $data);
			return;
		}
		$dist_code = $this->input->post('dist_code');
		$subdiv_code = $this->input->post('subdiv_code');
		$cir_code = $this->input->post('cir_code');
		$mouza_pargona_code = $this->input->post('mouza_pargona_code');
		$lot_no = $this->input->post('lot_no');
		$vill_townprt_code = $this->input->post('vill_townprt_code');

		$this->dbswitch($dist_code);

		$this->db->select('*');
		$this->db->from('chitha_rmk_encro');
		$this->db->where('dist_code', $dist_code);
		$this->db->where('subdiv_code', $subdiv_code);
		$this->db->where('cir_code',$cir_code);
		$this->db->where('mouza_pargona_code',$mouza_pargona_code);
		$this->db->where('lot_no',$lot_no);
		$this->db->where('vill_townprt_code',$vill_townprt_code);
		$query = $this->db->get();
		$encroachers = [];
		$data['null_encroachers'] = [];
		if ( $query->num_rows() > 0 )
		{
			$encroachers = $query->result_array();

			$this->db->select('*');
			$this->db->from('chitha_rmk_encro');
			$this->db->where('dist_code', $dist_code);
			$this->db->where('subdiv_code', $subdiv_code);
			$this->db->where('cir_code',$cir_code);
			$this->db->where('mouza_pargona_code',$mouza_pargona_code);
			$this->db->where('lot_no',$lot_no);
			$this->db->where('vill_townprt_code',$vill_townprt_code);
			$this->db->where('encro_name_as is NULL', NULL, FALSE);
			$query2 = $this->db->get();
			$data['null_encroachers'] =  $query2->result_array();;

		}
		$data['encroachers'] = $encroachers;

		$data['locations'] = $this->CommonModel->getLocations($dist_code, $subdiv_code, $cir_code,$mouza_pargona_code,$lot_no,$vill_townprt_code);

		$data['_view'] = 'svamitva_card/translate_name/encroachers';
		$data['page_title'] = 'Encroachers List | Encroachers Name Translate';
		$data['page_header'] = 'Encroachers List For Encroachers Name Translate';
		$data['breadcrumbs'] = '
        <li class="breadcrumb-item"><a href="#">Data Entry</a></li>
        <li class="breadcrumb-item"><a href="#">Encroachers Name Translate</a></li>
        <li class="breadcrumb-item active">Encroachers List</li>';
		$this->load->view('layout/layout', $data);
	}

	/** Enchroacher Name Translate */
	public function nameTranslate()
	{
		$array = $this->input->post('array');
		$dist_code = $array['dist_code'];
		$subdiv_code = $array['subdiv_code'];
		$cir_code = $array['cir_code'];
		$mouza_pargona_code = $array['mouza_pargona_code'];
		$lot_no = $array['lot_no'];
		$vill_townprt_code = $array['vill_townprt_code'];
		$dag_no = $array['dag_no'];
		$encro_name = $array['encro_name'];
		$encro_guardian = $array['encro_guardian'];

		$url = TRANSLATE_API."/CDAC-EnhanceTransliterationAPI/GetSingleResult.aspx";

		$data['itext'] = $array['encro_name'].',/'.$array['encro_guardian'];
		$data['locale'] = 'as_in';
		$data['transliteration'] = 'name';
		$data['transRev'] = 'true';

		$error['error'] = 'Y';

		$method = 'GET';
		$output = $this->callApi($url,$method,$data);

		if ($output != null)
		{
			$name = explode(',/', $output);
			$data = array(
			'encro_name' => ucfirst($name[0]),
			'encro_guardian' => ucfirst($name[1]),
			'encro_name_as' => $array['encro_name'],
			'encro_guardian_as' => $array['encro_guardian'],
			'updated_by' => $this->session->userdata('usercode'),
			);

			$this->dbswitch($dist_code);

			$this->db->trans_begin();
			$this->db->where('dist_code', $dist_code);
			$this->db->where('subdiv_code', $subdiv_code);
			$this->db->where('cir_code', $cir_code);
			$this->db->where('mouza_pargona_code', $mouza_pargona_code);
			$this->db->where('lot_no', $lot_no);
			$this->db->where('vill_townprt_code', $vill_townprt_code);
			$this->db->where('dag_no', $dag_no);
			$this->db->where('encro_name', $encro_name);
			$this->db->where('encro_guardian', $encro_guardian);
			$this->db->update('chitha_rmk_encro', $data);

			if($this->db->trans_status() === FALSE || $this->db->affected_rows() != 1){
				$this->db->trans_rollback();
				$error['dag_no'] = $dag_no;
				$error['encro_name'] = $encro_name;
				$error['encro_guardian'] = $encro_guardian;
				$error['msg'] = 'Commit Failed.';
			}else{
				$this->db->trans_commit();
				$error['error'] = 'N';
			}

			echo json_encode(array('error' => $error));
			return;
		}
		else{
			$error['error'] = 'YES';
			$error['msg'] = 'API FAILED.';
			echo json_encode(array('error' => $error));
			return;
		}
	}

	/** command api */
	public static function callApi($url,$method='GET', $data=null)
	{
		$postFields = http_build_query($data);

		$curl = curl_init();
		curl_setopt_array($curl, array(
                CURLOPT_URL => $url . '?' . $postFields,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded'
                ),
            ));

		$response = curl_exec($curl);
		$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);
		if($httpcode != 200)
		{
			return $response = null;
		}
		if ($response) {
			return $response;
		}
		else {
			return null;
		}
	}

	/** Get district list */
	public function getDistList()
	{
		return $this->db->get_where('location', array('dist_code !=' => '00', 'subdiv_code' => '00', 'cir_code' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->result_array();
	}

	/***** Get Sub Div For Admin *****/
	public function getSubdivs($subdiv_code = null){
		$data = [];
		$dist_code = $this->input->post('id');
		$this->dbswitch($dist_code);
		$formdata = $this->Chithamodel->subdivisiondetails($dist_code, $subdiv_code);
		foreach ($formdata as $value) {
			$data['subdiv_code'][] = $value;
		}
		echo json_encode($data['subdiv_code']);
	}
	public function getCircles(){
		$data = [];
		$dist_code = $this->input->post('dis');
		$subdiv_code = $this->input->post('subdiv');

		$this->dbswitch($dist_code);
		$formdata = $this->Chithamodel->circledetails($dist_code,$subdiv_code,$cir_code = null);
		foreach ($formdata as $value) {
			$data['cir_code'][] = $value;
		}
		echo json_encode($data['cir_code']);
	}

	public function getMouzas(){
		$data = [];
		$dist_code = $this->input->post('dis');
		$subdiv_code = $this->input->post('subdiv');
		$cir_code = $this->input->post('cir');
		$this->dbswitch($dist_code);
		$formdata = $this->Chithamodel->mouzadetails($dist_code,$subdiv_code,$cir_code);
		foreach ($formdata as $value) {
			$data['mouza_pargona_code'][] = $value;
		}
		echo json_encode($data['mouza_pargona_code']);
	}
	public function getLots(){
		$data = [];
		$dist_code = $this->input->post('dis');
		$subdiv_code = $this->input->post('subdiv');
		$cir_code = $this->input->post('cir');
		$mouza_pargona_code = $this->input->post('mza');
		$this->dbswitch($dist_code);
		$formdata = $this->Chithamodel->lotdetails($dist_code,$subdiv_code,$cir_code,$mouza_pargona_code);
		foreach ($formdata as $value) {
			$data['lot_no'][] = $value;
		}
		echo json_encode($data['lot_no']);
	}
	public function getVillages(){
		$data = [];
		$dist_code = $this->input->post('dis');
		$subdiv_code = $this->input->post('subdiv');
		$cir_code = $this->input->post('cir');
		$mouza_pargona_code = $this->input->post('mza');
		$lot_no = $this->input->post('lot');
		$this->dbswitch($dist_code);
		$formdata = $this->Chithamodel->villagedetails($dist_code,$subdiv_code,$cir_code,$mouza_pargona_code,$lot_no);
		foreach ($formdata as $value) {
			$data['vill_townprt_code'][] = $value;
		}
		echo json_encode($data['vill_townprt_code']);
	}
}
