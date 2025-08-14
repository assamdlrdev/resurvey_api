<?php
include APPPATH . '/libraries/CommonTrait.php';

class NcMigrationController extends CI_Controller
{
    use CommonTrait;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('UtilsModel');
        $this->load->model('NcVillageModel');
        $this->load->model('CommonModel');
    }

    public function getVillages(){
        $dist_code = $this->input->post('dist_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $vill_townprt_code = $this->input->post('vill_townprt_code');
        $uuid = $this->input->post('uuid');

        $this->dbswitch($dist_code);
        $dist_code = $dist_code;
        $data['villages'] = $villages = $this->db->query("select * from nc_villages where dist_code='$dist_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' and uuid='$uuid'")->result();

        foreach ($villages as $k => $v) {
            $villages[$k]->subdiv_name = $this->db->select('loc_name,dist_code,subdiv_code,cir_code,mouza_pargona_code,locname_eng')
                ->where(array('dist_code' => $v->dist_code, 'subdiv_code' => $v->subdiv_code, 'cir_code' => '00', '
				mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))
                ->get('location')->row();

            $villages[$k]->circle_name = $this->db->select('loc_name,dist_code,subdiv_code,cir_code,mouza_pargona_code,locname_eng')
                ->where(array('dist_code' => $v->dist_code, 'subdiv_code' => $v->subdiv_code, 'cir_code' => $v->cir_code, '
				mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))
                ->get('location')->row();

            $villages[$k]->mouza_name = $this->db->select('loc_name,dist_code,subdiv_code,cir_code,mouza_pargona_code,locname_eng')
                ->where(array('dist_code' => $v->dist_code, 'subdiv_code' => $v->subdiv_code, 'cir_code' => $v->cir_code, '
				mouza_pargona_code' => $v->mouza_pargona_code, 'lot_no' => '00', 'vill_townprt_code' => '00000'))
                ->get('location')->row();

            $villages[$k]->lot_name = $this->db->select('loc_name,dist_code,subdiv_code,cir_code,mouza_pargona_code,locname_eng')
                ->where(array('dist_code' => $v->dist_code, 'subdiv_code' => $v->subdiv_code, 'cir_code' => $v->cir_code, '
				mouza_pargona_code' => $v->mouza_pargona_code, 'lot_no' => $v->lot_no, 'vill_townprt_code' => '00000'))
                ->get('location')->row();

            $villages[$k]->village_name = $this->db->query("SELECT loc_name FROM location WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=?", [$v->dist_code, $v->subdiv_code, $v->cir_code, $v->mouza_pargona_code, $v->lot_no, $v->vill_townprt_code])->row();

            $merge_village_name_arr = [];
            $merge_village_requests = $this->db->where('nc_village_id', $v->id)->get('merge_village_requests')->result_array();
            if(count($merge_village_requests)){
                foreach($merge_village_requests as $key1 => $merge_village_request){
                    $vill_loc = $this->CommonModel->getLocations($merge_village_request['request_dist_code'], $merge_village_request['request_subdiv_code'], $merge_village_request['request_cir_code'], $merge_village_request['request_mouza_pargona_code'], $merge_village_request['request_lot_no'], $merge_village_request['request_vill_townprt_code']);
                    array_push($merge_village_name_arr, $vill_loc['village']['loc_name']);
                    $merge_village_requests[$key1]['village_name'] = $vill_loc['village']['loc_name'];
                    $merge_village_requests[$key1]['vill_loc'] = $vill_loc;
                }
            }
            $villages[$k]->merge_village_names = implode(', ', $merge_village_name_arr);
            $villages[$k]->merge_village_requests = $merge_village_requests;
        }

        $data['nc_village'] = $villages;

        return response_json(['success' => true, 'villages' => $villages, 'dist_code' => $dist_code], 200);
    }

    public function updateNcVillage(){
        $dist_code = $this->input->post('dist_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $vill_townprt_code = $this->input->post('vill_townprt_code');
        $uuid = $this->input->post('uuid');

        $this->dbswitch($dist_code);
        $this->db->set([
            "app_version" => 'V2',
        ]);

        $this->db->where('dist_code', $dist_code);
        $this->db->where('subdiv_code', $subdiv_code);
        $this->db->where('cir_code', $cir_code);
        $this->db->where('mouza_pargona_code', $mouza_pargona_code);
        $this->db->where('lot_no', $lot_no);
        $this->db->where('vill_townprt_code', $vill_townprt_code);
        $this->db->where('uuid', $uuid);
        $this->db->update('nc_villages');
        if ($this->db->affected_rows() > 0) {
            return response_json(['success' => true], 200);
            return;
        } else {
            return response_json(['success' => false], 500);
            return;
        }
    }

}
?>