<?php

include APPPATH . '/libraries/CommonTrait.php';
class ChangeVillageCoController extends CI_Controller
{
    use CommonTrait;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('CommonModel');
        $this->load->model('ChangeVillageModel');
        $this->load->model('UtilsModel');
    }
    public function dashboard()
    {
        $this->dbswitch();
        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $data['base'] = $this->config->item('base_url');
        $loc_query = "select l.loc_name,l.uuid,c.status from nc_villages ncv join location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code
                 and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and ncv.vill_townprt_code = l.vill_townprt_code left outer join change_vill_name c on l.uuid = c.uuid";

        $query = $loc_query . " where ncv.dist_code='$dist_code'and ncv.subdiv_code='$subdiv_code'and ncv.cir_code='$cir_code' and ncv.status='C'";

        $result = $this->db->query($query)->result();

        $data['villages'] = $result;
        $data['count'] = count($result);
        $data['districts'] = $this->Chithamodel->districtdetailsreport();
        $data['subDivisions'] = $this->Chithamodel->subdivisiondetails($dist_code, $subdiv_code);
        $data['circles'] = $this->Chithamodel->circledetails($dist_code, $subdiv_code, $cir_code);
        $data['mouzas'] = $this->Chithamodel->mouzadetails($dist_code, $subdiv_code, $cir_code);
        $data['_view'] = 'change_village/co/change_village_co_dashboard';

        $this->load->view('layout/layout', $data);
    }
    public function filterTable()
    {
        $this->dataswitch();
        $dist_code = $this->input->post('dist_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza = $this->input->post('mouza_pargona_code');
        $lot = $this->input->post('lot_no');
        $loc_query = "select l.loc_name,l.uuid,c.status from nc_villages ncv join location l on ncv.dist_code = l.dist_code and ncv.subdiv_code = l.subdiv_code and ncv.cir_code = l.cir_code
        and ncv.mouza_pargona_code = l.mouza_pargona_code and ncv.lot_no = l.lot_no and ncv.vill_townprt_code = l.vill_townprt_code left outer join change_vill_name c on l.uuid = c.uuid";
        if ($lot == '00') {
            $query = $loc_query . " where ncv.dist_code='$dist_code'and ncv.subdiv_code='$subdiv_code' and ncv.cir_code='$cir_code' and ncv.mouza_pargona_code='$mouza' and ncv.status='C'";
        } else {
            $query = $loc_query . " where ncv.dist_code='$dist_code'and ncv.subdiv_code='$subdiv_code' and ncv.cir_code='$cir_code' and ncv.mouza_pargona_code='$mouza' and ncv.lot_no = '$lot' and ncv.status='C'";
        }
        $vill = $this->db->query($query)->result();
        foreach ($vill as $value) {
            $data['villages'][] = $value;
        }
        if (isset($data['villages'])) {
            echo json_encode($data['villages']);
        } else {
            $data['villages'][] = "0";
            echo json_encode($data['villages']);
        }
    }
    public function villagedetails()
    {
        $this->dataswitch();
        $uuid = $this->input->post('id');
        $vill = $this->db->get_where('location', array('uuid' => $uuid))->row();
        $change_vill = $this->db->get_where('change_vill_name', array('uuid' => $uuid))->row();
        $dist = $this->db->get_where('location', array('dist_code' => $vill->dist_code, 'subdiv_code =' => '00', 'cir_code=' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->row();
        $subdiv = $this->db->get_where('location', array('dist_code' => $vill->dist_code, 'subdiv_code =' => $vill->subdiv_code, 'cir_code=' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->row();
        $cir = $this->db->get_where('location', array('dist_code' => $vill->dist_code, 'subdiv_code =' => $vill->subdiv_code, 'cir_code=' => $vill->cir_code, 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->row();
        $mouza = $this->db->get_where('location', array('dist_code' => $vill->dist_code, 'subdiv_code =' => $vill->subdiv_code, 'cir_code=' => $vill->cir_code, 'mouza_pargona_code' => $vill->mouza_pargona_code, 'lot_no' => '00', 'vill_townprt_code' => '00000'))->row();
        $lot = $this->db->get_where('location', array('dist_code' => $vill->dist_code, 'subdiv_code =' => $vill->subdiv_code, 'cir_code=' => $vill->cir_code, 'mouza_pargona_code' => $vill->mouza_pargona_code, 'lot_no' => $vill->lot_no, 'vill_townprt_code' => '00000'))->row();
        $data['district'] = $dist->loc_name;
        $data['subdiv'] = $subdiv->loc_name;
        $data['cir'] = $cir->loc_name;
        $data['mouza'] = $mouza->loc_name;
        $data['lot'] = $lot->loc_name;
        $data['vill'] = $vill;
        echo json_encode($data);
    }
    public function changeVillageNameRequest()
    {
        $this->dbswitch();
        $dist_code = $this->session->userdata('dcode');
        $data['base'] = $this->config->item('base_url');
        $data['villages'] = $this->db->order_by("status", "asc")->get_where('change_vill_name', array('cu_user_code' => $this->session->userdata('usercode')))->result_array();
        $data['_view'] = 'change_village/co/change_village_all_request';

        $this->load->view('layout/layout', $data);
    }
    public function changeVillageSubmit()
    {
        $data = array();
        $this->form_validation->set_rules('dist_code', 'District Name', 'trim|integer|required');
        $this->form_validation->set_rules('subdiv_code', 'Sub Division Name', 'trim|integer|required');
        $this->form_validation->set_rules('cir_code', 'Circle Name', 'trim|integer|required');
        $this->form_validation->set_rules('mouza_pargona_code', 'Mouza Name', 'trim|integer|required');
        $this->form_validation->set_rules('lot_no', 'Lot Number', 'trim|integer|required');
        $this->form_validation->set_rules('vill_uuid', 'Village Name', 'trim|integer|required');
        $this->form_validation->set_rules('new_vill_name', 'New Village Name', 'trim|max_length[100]|required');
        $this->form_validation->set_rules('new_vill_name_eng', 'New Village Name (English)', 'trim|max_length[100]|required');
        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
            return;
        } else {
            $form_data = [
                'dist_code' => $_POST['dist_code'],
                'subdiv_code' => $_POST['subdiv_code'],
                'cir_code' => $_POST['cir_code'],
                'mouza_pargona_code' => $_POST['mouza_pargona_code'],
                'lot_no' => $_POST['lot_no'],
                'uuid' => $_POST['vill_uuid'],
                'old_vill_name' => $_POST['old_vill_name'],
                'old_vill_name_eng' => $_POST['old_vill_name_eng'],
                'new_vill_name' => $_POST['new_vill_name'],
                'new_vill_name_eng' => $_POST['new_vill_name_eng'],
                'cu_user_code' => "CO",
                'co_user_code' => $this->session->userdata('usercode'),
                'co_verified' => "Y",
                'date_of_update_co' => date('Y-m-d H:i:s'),
                'status' => 'D',
            ];
            $this->dataswitch($form_data['dist_code']);
            // $exist = $this->ChangeVillageModel->checkVillName($_POST['new_vill_name'],$_POST['new_vill_name_eng'],$_POST['vill_uuid']);
            $exist = false;
            if($exist){
                echo json_encode(array('msg' => 'Village name already exist.', 'st' => 0));
            }else{
                $insertNewVill = $this->ChangeVillageModel->insertChangeVillage($form_data);
                if ($insertNewVill) {
                    echo json_encode(array('msg' => 'Submitted Successfully!!', 'st' => 1));
                } else {
                    echo json_encode(array('msg' => 'Failed Try Again', 'st' => 0));
                }
            }
           
        }

    }
}
