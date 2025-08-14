
<?php

include APPPATH . '/libraries/CommonTrait.php';
class NcVillageNameController extends CI_Controller
{
	use CommonTrait;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('CommonModel');
		$this->load->model('UtilsModel');
        $this->load->model('UserModel');
	}

	public function notifications()
	{

		$data['_view'] = 'nc_village/name_change/all_notifications';
		$this->load->view('layout/layout', $data);
	}
	public function getNotifications()
	{
		$filters = $this->input->post('filters');
		$url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/apiGetNotifications";
		$method = 'POST';
		$filter_params = [];
		if($filters){
			foreach($filters as $filter){
				$filter_params[$filter] = 'Y';
			}
		}
		$data = ['filters' => $filter_params ? $filter_params  : []];
		$notifications = callIlrmsApi2($url, $method, $data);
		$total_villages = 0;
		$total_notified_villages = 0;
		$total_final_notifications = 0;
		if (!empty($notifications->notifications)) {
			foreach ($notifications->notifications as &$notification) {
				if (!empty($notification->dist_code) && !empty($notification->proposal_id)) {
					$this->dbswitch($notification->dist_code);
					$this->db->from('nc_villages');
					$this->db->where('section_officer_proposal_id', $notification->proposal_id);
					$notification->village_count = $this->db->count_all_results();
					if ($notification->village_count > 0) {
						$total_villages += $notification->village_count;
					}
					if (isset($notification->js_sign) && $notification->js_sign === 'Y') {
						$total_notified_villages += $notification->village_count;
						$total_final_notifications++;
					}
				} else {
					$notification->village_count = 0;
				}
			}
		}
		echo json_encode(['notifications' => $notifications->notifications, 'total_villages' => $total_villages, 'total_notified_villages' => $total_notified_villages, 'total_final_notifications' => $total_final_notifications]);
	}
	public function dumpNotifications()
	{
		$url = API_LINK_ILRMS . "index.php/nc_village/NcCommonController/apiGetNotifications";
		$method = 'POST';
		$notifications = callIlrmsApi2($url, $method, []);
		dd($notifications);
	}
	public function notificationView($notification_no, $dist_code, $proposal_no, $is_final)
	{
		$this->dbswitch($dist_code);
		$proposal = $this->db->query("select * from nc_village_proposal where proposal_no=?", [$proposal_no])->row();

		$data['dist_code'] = $dist_code;
		$data['notification_no'] = $notification_no;
		$data['proposal_no'] = $proposal->proposal_no;
		$data['is_final'] = $is_final;
		$data['_view'] = 'nc_village/name_change/notification_villages';
		$this->load->view('layout/layout', $data);
	}
	public function getNotificationVillages()
	{
		$dist_code = $this->input->post('dist_code');
		$proposal_no = $this->input->post('proposal_no');

		$this->dbswitch($dist_code);
		$proposal = $this->db->query("select * from nc_village_proposal where proposal_no=?", [$proposal_no])->row();

		if ($proposal->user_type == 'DLR') {
			$villages = $this->db->query("select l_dist.loc_name as dist_name,l_subdiv.loc_name as subdiv_name,l_circle.loc_name as cir_name,l_mouza.loc_name as mouza_name,l_lot.loc_name as lot_name,l_village.loc_name as vill_name,l_village.locname_eng as vill_name_eng,change_vill_name.uuid,change_vill_name.old_vill_name,change_vill_name.new_vill_name,change_vill_name.new_vill_name_eng,nc.* from nc_villages nc
				join location l_dist on l_dist.dist_code=nc.dist_code and l_dist.subdiv_code='00'
				join location l_subdiv on l_subdiv.dist_code=nc.dist_code and l_subdiv.subdiv_code=nc.subdiv_code and l_subdiv.cir_code='00'
				join location l_circle on l_circle.dist_code=nc.dist_code and l_circle.subdiv_code=nc.subdiv_code and l_circle.cir_code=nc.cir_code and l_circle.mouza_pargona_code='00'
				join location l_mouza on l_mouza.dist_code=nc.dist_code and l_mouza.subdiv_code=nc.subdiv_code and l_mouza.cir_code=nc.cir_code and l_mouza.mouza_pargona_code=nc.mouza_pargona_code and l_mouza.lot_no='00'
				join location l_lot on l_lot.dist_code=nc.dist_code and l_lot.subdiv_code=nc.subdiv_code and l_lot.cir_code=nc.cir_code and l_lot.mouza_pargona_code=nc.mouza_pargona_code and l_lot.lot_no=nc.lot_no and l_lot.vill_townprt_code='00000'
				join location l_village on l_village.dist_code=nc.dist_code and l_village.subdiv_code=nc.subdiv_code and l_village.cir_code=nc.cir_code and l_village.mouza_pargona_code=nc.mouza_pargona_code and l_village.lot_no=nc.lot_no and l_village.vill_townprt_code=nc.vill_townprt_code
				join change_vill_name on change_vill_name.uuid=nc.uuid
				where dlr_proposal_id=$proposal->id")->result();
		} elseif ($proposal->user_type == 'SO') {
			$villages = $this->db->query("select l_dist.loc_name as dist_name,l_subdiv.loc_name as subdiv_name,l_circle.loc_name as cir_name,l_mouza.loc_name as mouza_name,l_lot.loc_name as lot_name,l_village.loc_name as vill_name,l_village.locname_eng as vill_name_eng,change_vill_name.uuid,change_vill_name.old_vill_name,change_vill_name.new_vill_name,change_vill_name.new_vill_name_eng,nc.* from nc_villages nc
				join location l_dist on l_dist.dist_code=nc.dist_code and l_dist.subdiv_code='00'
				join location l_subdiv on l_subdiv.dist_code=nc.dist_code and l_subdiv.subdiv_code=nc.subdiv_code and l_subdiv.cir_code='00'
				join location l_circle on l_circle.dist_code=nc.dist_code and l_circle.subdiv_code=nc.subdiv_code and l_circle.cir_code=nc.cir_code and l_circle.mouza_pargona_code='00'
				join location l_mouza on l_mouza.dist_code=nc.dist_code and l_mouza.subdiv_code=nc.subdiv_code and l_mouza.cir_code=nc.cir_code and l_mouza.mouza_pargona_code=nc.mouza_pargona_code and l_mouza.lot_no='00'
				join location l_lot on l_lot.dist_code=nc.dist_code and l_lot.subdiv_code=nc.subdiv_code and l_lot.cir_code=nc.cir_code and l_lot.mouza_pargona_code=nc.mouza_pargona_code and l_lot.lot_no=nc.lot_no and l_lot.vill_townprt_code='00000'
				join location l_village on l_village.dist_code=nc.dist_code and l_village.subdiv_code=nc.subdiv_code and l_village.cir_code=nc.cir_code and l_village.mouza_pargona_code=nc.mouza_pargona_code and l_village.lot_no=nc.lot_no and l_village.vill_townprt_code=nc.vill_townprt_code
				join change_vill_name on change_vill_name.uuid=nc.uuid
				where section_officer_proposal_id=$proposal->id")->result();
		} else {
			$villages = [];
		}
		echo json_encode($villages);
	}
	public function getVillageName()
	{
		$dist_code = $this->input->post('dist_code');
		$uuid = $this->input->post('uuid');

		$response = callLandhubAPI2('POST', 'getVillageByUuid', [
			'dist_code' => $dist_code,
			'uuid' => $uuid
		]);
		echo json_encode($response);
		return;
	}
	public function updateVillageName()
	{
		$dist_code = $this->input->post('dist_code');
		$uuid = $this->input->post('uuid');
		$new_name = $this->input->post('new_name');
		$new_name_eng = $this->input->post('new_name_eng');

		$response = callLandhubAPI2('POST', 'updateVillageName', [
			'dist_code' => $dist_code,
			'uuid' => $uuid,
			'new_name' => $new_name,
			'new_name_eng' => $new_name_eng
		]);
		if ($response->responseType == '2') {
			$this->dbswitch($dist_code);
			$this->db->set('loc_name', $new_name);
			$this->db->set('locname_eng', $new_name_eng);
			$this->db->where('uuid', $uuid);
			$this->db->update('location');

			$this->db->set('is_name_updated', 'Y');
			$this->db->where('uuid', $uuid);
			$this->db->update('nc_villages');

			$db = $this->UserModel->connectLocmaster();
			$db->set('loc_name', $new_name);
			$db->set('locname_eng', $new_name_eng);
			$db->where('uuid', $uuid);
			$db->update('location');
		}
		echo json_encode($response);
		return;
	}
}

?>
