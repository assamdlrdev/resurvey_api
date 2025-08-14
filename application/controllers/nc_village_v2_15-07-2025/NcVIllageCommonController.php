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
		$data['location'] = $_POST['location'];
		$data['data'] = $_POST;
		$area= $_POST['area'];
		$data['area'] = 0;
		if($area != 0)
		{
			$data['area'] = round($area/1000000,5);
		}

		$data['_view'] = 'nc_village_v2/common/bhunaksa_map';
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

    public function processed_cases(){
        $user_code = $this->session->userdata('user_code');

        $url = API_LINK_ILRMS . "index.php/nc_village_v2/NcCommonController/get_processed_cases";
        $method = 'POST';
        $output = callIlrmsApi($url, $method, ['case_for_user' => $user_code]);
        $cases = [];
        if(!empty($output) && !empty($output->data)){
            $cases = json_decode(json_encode($output->data), true);
        }
        
        $data['cases'] = $cases;
        $data['_view'] = 'nc_village_v2/common/processed_cases';
		$this->load->view('layout/layout', $data);
    }

	public function showDags()
    {
        $this->dbswitch();
        $application_no = $_GET['application_no'];

        $nc_village = $this->db->query("select * from nc_villages where
		application_no='$application_no'")->row();
        if (!$nc_village) {
            show_404();
            return;
        }

		$dist_code = $nc_village->dist_code;
		$subdiv_code = $nc_village->subdiv_code;
		$cir_code = $nc_village->cir_code;
		$mouza_pargona_code = $nc_village->mouza_pargona_code;
		$lot_no = $nc_village->lot_no;
		$vill_townprt_code = $nc_village->vill_townprt_code;

        $data['locations'] = $this->CommonModel->getLocations($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code);
        $q3 = "select count(*) as count from nc_village_dags where dist_code=? AND subdiv_code=? AND cir_code=?
        	AND mouza_Pargona_code=? AND lot_No=? AND vill_townprt_code=? and co_verified IS NULL";
        $check_unverified_dag = $this->db->query($q3, array(
            $dist_code, $subdiv_code, $cir_code,
            $nc_village->mouza_pargona_code, $nc_village->lot_no, $nc_village->vill_townprt_code,
        ))->row()->count;

        $data['change_vill'] = $this->db->get_where('change_vill_name', array('uuid' => $data['locations']['village']['uuid']))->row();
        $data['verified'] = 'N';
        $data['base'] = $this->config->item('base_url');
        if ($check_unverified_dag == 0) {
            $data['verified'] = 'Y';
        }
        $data['nc_village'] = $nc_village;

        $api_data['d'] = $dist_code;
        $api_data['s'] = $subdiv_code;
        $api_data['c'] = $cir_code;
        $api_data['m'] = $mouza_pargona_code;
        $api_data['l'] = $lot_no;
        $api_data['v'] = $vill_townprt_code;
        $api_data['v_uuid'] = $nc_village->uuid;
        
        $url = API_LINK_ILRMS . "index.php/nc_village_v2/NcCommonController/apiGetMapByLocation";

        $method = 'POST';
        $output = callIlrmsApi($url, $method, $api_data);

        $data['maps'] = array();
        if (!empty($output) && !empty($output->data) && sizeof($output->data->map_lists) != 0) {
            $data['maps'] = $output->data->map_lists;
        }else{
            show_404();
            return;
        }

		$full_histories = [];
		$url2 = API_LINK_ILRMS . "index.php/nc_village_v2/NcCommonController/get_full_case_histories";

        $method = 'POST';
        $output = callIlrmsApi($url2, $method, $api_data);
		if(!empty($output)){
			$full_histories = $output->data;
		}

        $merge_village_requests = $this->db->where('nc_village_id', $nc_village->id)->get('merge_village_requests')->result_array();
        if(count($merge_village_requests)){
            foreach($merge_village_requests as $key => $merge_village_request){
                $merge_village_requests[$key]['vill_loc'] = $vill_loc = $this->CommonModel->getLocations($merge_village_request['request_dist_code'], $merge_village_request['request_subdiv_code'], $merge_village_request['request_cir_code'], $merge_village_request['request_mouza_pargona_code'], $merge_village_request['request_lot_no'], $merge_village_request['request_vill_townprt_code']);
            }
        }
        $data['merge_village_requests'] = $merge_village_requests;
        $data['full_histories'] = json_decode(json_encode($full_histories), true);

        $data['_view'] = 'nc_village_v2/common/view_village_details';
        $this->load->view('layout/layout', $data);
    }

    public function getDags()
    {
        $this->dbswitch();
        $this->form_validation->set_rules('application_no', 'application no', 'trim|required');

        if ($this->form_validation->run() == false) {
            echo json_encode(array('errors' => validation_errors(), 'st' => 0));
            return;
        }
        $application_no = $this->input->post('application_no');
        $nc_village = $this->db->query("select * from nc_villages where application_no='$application_no'")->row();
        
        $dags = $this->CommonModel->getNcVillageDags($application_no);
        foreach ($dags as $dag) {
            $dag->occupiers = $this->DagReportModel->occupierNames($dag->dist_code, $dag->subdiv_code, $dag->cir_code, $dag->mouza_pargona_code, $dag->lot_no, $dag->vill_townprt_code, $dag->dag_no);
        }
        echo json_encode(array('dags' => $dags));
    }

	/** apiProposalVillageWise */
    public function apiProposalVillageWise()
    {
        $this->load->model('CommonModel');
        $dist_code = $this->UtilsModel->cleanPattern($this->input->post('d'));
        $village_id = implode(",", $this->input->post('village_id'));

        $this->dbswitch($dist_code);
        $user_type = $this->input->post('user_type');
        $data['locations'] = $this->CommonModel->getLocations($dist_code);

        $nc_village = null;
        $proposal_view = '';
        if($user_type == 'section_officer'){
            $data['user_name'] = $this->input->post('user_name');
            $query = "select nc.*,l.loc_name,ch.old_vill_name,ch.new_vill_name from nc_villages nc join change_vill_name ch on nc.dist_code = ch.dist_code and nc.uuid = ch.uuid join location l on nc.dist_code = l.dist_code and nc.subdiv_code = l.subdiv_code and nc.cir_code = l.cir_code and nc.mouza_pargona_code = l.mouza_pargona_code and nc.lot_no = l.lot_no and nc.vill_townprt_code = l.vill_townprt_code where nc.id IN ($village_id) and nc.dist_code='$dist_code' and (nc.status ='a' OR nc.status ='b') and nc.section_officer is not null and nc.section_officer_verified='Y'";
            $data['villages'] = $nc_village = $this->db->query($query)->result();

            $proposal_view = 'nc_village_v2/section_officer/approval_notification';
        }else if($user_type == 'asst_section_officer'){
            $data['user_name'] = $this->input->post('user_name');
            $query = "select nc.*,l.loc_name,ch.old_vill_name,ch.new_vill_name from nc_villages nc join change_vill_name ch on nc.dist_code = ch.dist_code and nc.uuid = ch.uuid join location l on nc.dist_code = l.dist_code and nc.subdiv_code = l.subdiv_code and nc.cir_code = l.cir_code and nc.mouza_pargona_code = l.mouza_pargona_code and nc.lot_no = l.lot_no and nc.vill_townprt_code = l.vill_townprt_code where nc.id IN ($village_id) and nc.dist_code='$dist_code'and nc.status ='c' and nc.asst_section_officer is not null and nc.asst_section_officer_verified='Y'";
            $data['villages'] = $nc_village = $this->db->query($query)->result();
             
            $proposal_view = 'nc_village_v2/asst_section_officer/approval_notification';
        }else{
            return;
        }


        foreach ($nc_village as $k => $v) {
            $nc_village[$k]->circle_name = $this->db->select('loc_name,dist_code,subdiv_code,cir_code,mouza_pargona_code,locname_eng')
                ->where(array('dist_code' => $v->dist_code, 'subdiv_code' => $v->subdiv_code, 'cir_code' => $v->cir_code, '
				mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))
                ->get('location')->row();

            $nc_village[$k]->mouza_name = $this->db->select('loc_name,dist_code,subdiv_code,cir_code,mouza_pargona_code,locname_eng')
                ->where(array('dist_code' => $v->dist_code, 'subdiv_code' => $v->subdiv_code, 'cir_code' => $v->cir_code, '
				mouza_pargona_code' => $v->mouza_pargona_code, 'lot_no' => '00', 'vill_townprt_code' => '00000'))
                ->get('location')->row();

            $nc_village[$k]->lot_name = $this->db->select('loc_name,dist_code,subdiv_code,cir_code,mouza_pargona_code,locname_eng')
                ->where(array('dist_code' => $v->dist_code, 'subdiv_code' => $v->subdiv_code, 'cir_code' => $v->cir_code, '
				mouza_pargona_code' => $v->mouza_pargona_code, 'lot_no' => $v->lot_no, 'vill_townprt_code' => '00000'))
                ->get('location')->row();

            $nc_village[$k]->total_dag_area = $vill_area = $this->db->select('count(*) as total_dag, SUM(dag_area_b) as total_bigha,
				SUM(dag_area_k) as total_katha,SUM(dag_area_lc) as total_lessa')
                ->where(array(
                    'dist_code' => $v->dist_code,
                    'subdiv_code' => $v->subdiv_code,
                    'cir_code' => $v->cir_code,
                    '
				mouza_pargona_code' => $v->mouza_pargona_code,
                    'lot_no' => $v->lot_no,
                    'vill_townprt_code' => $v->vill_townprt_code,
                ))
                ->get('chitha_basic_nc')->row();

            $total_lessa = $this->totalLessa($vill_area->total_bigha, $vill_area->total_katha, $vill_area->total_lessa);
            $nc_village[$k]->total_b_k_l = $this->Total_Bigha_Katha_Lessa($total_lessa);

            $nc_village[$k]->occupiers = $this->db->select('count(*) as occupiers')
                ->where(array(
                    'dist_code' => $v->dist_code,
                    'subdiv_code' => $v->subdiv_code,
                    'cir_code' => $v->cir_code,
                    '
				mouza_pargona_code' => $v->mouza_pargona_code,
                    'lot_no' => $v->lot_no,
                    'vill_townprt_code' => $v->vill_townprt_code,
                ))
                ->get('chitha_rmk_encro')->row()->occupiers;

            $merge_village_requests = $this->db->where('nc_village_id', $v->id)->get('merge_village_requests')->result_array();
            $merge_village_name_arr = [];
            if(count($merge_village_requests)){
                foreach($merge_village_requests as $key1 => $merge_village_request){
                    $vill_loc = $this->CommonModel->getLocations($merge_village_request['request_dist_code'], $merge_village_request['request_subdiv_code'], $merge_village_request['request_cir_code'], $merge_village_request['request_mouza_pargona_code'], $merge_village_request['request_lot_no'], $merge_village_request['request_vill_townprt_code']);
                    array_push($merge_village_name_arr, $vill_loc['village']['loc_name']);
                    $merge_village_requests[$key1]['village_name'] = $vill_loc['village']['loc_name'];
                    $merge_village_requests[$key1]['vill_loc'] = $vill_loc;
                }
            }
            $nc_village[$k]->merge_village_names = implode(', ', $merge_village_name_arr);
            $nc_village[$k]->merge_village_requests = $merge_village_requests;
        }

        $data['nc_village'] = $nc_village;
        $data['proposal'] = $this->load->view($proposal_view, $data, true);

        echo json_encode($data);
        return;
    }

    public function viewUploadedMap()
    {
        $id = $data['id'] = $_GET['id'];
        $dist_code = $data['d'] = $this->session->userdata('dist_code');

        $url = API_LINK_ILRMS . "index.php/nc_village_v2/NcCommonController/apiGetMapBase64";

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
}
