<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class VlbController extends CI_Controller
{
    use CommonTrait;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('UtilityClass');
        $this->load->model('MasterModel');
        $this->load->model('vlb/VlbModel');
        $this->load->library('form_validation');
    }
    public function dashboard()
    {
        $this->dataswitch();
        $user_code = $this->session->userdata('usercode');
        $LAND_BANK_STATUS_PENDING = LAND_BANK_STATUS_PENDING;
        $LAND_BANK_STATUS_REVERT_BACK = LAND_BANK_STATUS_REVERT_BACK;
        $LAND_BANK_STATUS_APPROVED = LAND_BANK_STATUS_APPROVED;
        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');

        $data['loc_names'] = $this->MasterModel->getLocNames($dist_code, $subdiv_code, $cir_code);

        $data['total_pending'] = $this->db->query("select count(*) as c from land_bank_details where user_code = '$user_code' and status = '$LAND_BANK_STATUS_PENDING'")->row()->c;
        $data['total_reverted'] = $this->db->query("select count(*) as c from land_bank_details where user_code = '$user_code' and status = '$LAND_BANK_STATUS_REVERT_BACK'")->row()->c;
        $data['total_approved'] = $this->db->query("select count(*) as c from land_bank_details where user_code = '$user_code' and status = '$LAND_BANK_STATUS_APPROVED'")->row()->c;

        $vlb_not_in_clb = $this->VlbModel->getVlbNotInClb($dist_code, $subdiv_code, $cir_code);
        $data['to_add_count'] = $vlb_not_in_clb - $data['total_pending'];
        $data['_view'] = 'vlb/deo/dashboard';
        $this->load->view('layout/layout', $data);
    }
    public function dashboard_mouza()
    {
        $this->dataswitch();
        $user_code = $this->session->userdata('usercode');
        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $data['loc_names'] = $this->MasterModel->getLocNames($dist_code, $subdiv_code, $cir_code);
        $LAND_BANK_STATUS_PENDING = LAND_BANK_STATUS_PENDING;
        $LAND_BANK_STATUS_REVERT_BACK = LAND_BANK_STATUS_REVERT_BACK;
        $LAND_BANK_STATUS_APPROVED = LAND_BANK_STATUS_APPROVED;

        $mouzas = $this->MasterModel->getMouzas($dist_code, $subdiv_code, $cir_code);
        foreach ($mouzas as $mouza) {
            $mouza->total_pending = $this->db->query("select count(*) as c from land_bank_details where user_code = '$user_code' and status = '$LAND_BANK_STATUS_PENDING' and mouza_pargona_code='$mouza->mouza_pargona_code'")->row()->c;
            $mouza->total_reverted = $this->db->query("select count(*) as c from land_bank_details where user_code = '$user_code' and status = '$LAND_BANK_STATUS_REVERT_BACK' and mouza_pargona_code='$mouza->mouza_pargona_code'")->row()->c;
            $mouza->total_approved = $this->db->query("select count(*) as c from land_bank_details where user_code = '$user_code' and status = '$LAND_BANK_STATUS_APPROVED' and mouza_pargona_code='$mouza->mouza_pargona_code'")->row()->c;
            $vlb_not_in_clb = $this->VlbModel->getVlbNotInClb($dist_code, $subdiv_code, $cir_code,$mouza->mouza_pargona_code);
            $mouza->to_add_count = $vlb_not_in_clb - $mouza->total_pending;
        }
        $data['mouzas'] = $mouzas;
        $data['_view'] = 'vlb/deo/dashboard_mouzas';

        $data['breadcrumbs'] = "
        <li class='breadcrumb-item'>Process</li>
        <li class='breadcrumb-item'><a href='".base_url("index.php/vlb/VlbController/dashboard")."'>Village Land Bank</a></li>
        <li class='breadcrumb-item active'>".$data['loc_names']['circle']->loc_name."</li>";

        $this->load->view('layout/layout', $data);
    }
    public function dashboard_lot($mouza_code)
    {
        $this->dataswitch();
        $user_code = $this->session->userdata('usercode');
        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');

        $LAND_BANK_STATUS_PENDING = LAND_BANK_STATUS_PENDING;
        $LAND_BANK_STATUS_REVERT_BACK = LAND_BANK_STATUS_REVERT_BACK;
        $LAND_BANK_STATUS_APPROVED = LAND_BANK_STATUS_APPROVED;

        $lots = $this->MasterModel->getLots($dist_code, $subdiv_code, $cir_code, $mouza_code);
        foreach ($lots as $lot) {
            $lot->total_pending = $this->db->query("select count(*) as c from land_bank_details where user_code = '$user_code' and status = '$LAND_BANK_STATUS_PENDING' and mouza_pargona_code='$lot->mouza_pargona_code' and lot_no='$lot->lot_no'")->row()->c;
            $lot->total_reverted = $this->db->query("select count(*) as c from land_bank_details where user_code = '$user_code' and status = '$LAND_BANK_STATUS_REVERT_BACK' and mouza_pargona_code='$lot->mouza_pargona_code' and lot_no='$lot->lot_no'")->row()->c;
            $lot->total_approved = $this->db->query("select count(*) as c from land_bank_details where user_code = '$user_code' and status = '$LAND_BANK_STATUS_APPROVED' and mouza_pargona_code='$lot->mouza_pargona_code' and lot_no='$lot->lot_no'")->row()->c;
            $vlb_not_in_clb = $this->VlbModel->getVlbNotInClb($dist_code, $subdiv_code, $cir_code,$lot->mouza_pargona_code,$lot->lot_no);
            $lot->to_add_count = $vlb_not_in_clb - $lot->total_pending;
        }
        $data['lots'] = $lots;
        $data['mouza_code'] = $mouza_code;
        $data['loc_names'] = $this->MasterModel->getLocNames($dist_code, $subdiv_code, $cir_code, $mouza_code);
        $data['_view'] = 'vlb/deo/dashboard_lots';
        $data['breadcrumbs'] = "
        <li class='breadcrumb-item'>Process</li>
        <li class='breadcrumb-item'><a href='".base_url("index.php/vlb/VlbController/dashboard")."'>Village Land Bank</a></li>
        <li class='breadcrumb-item'><a href='".base_url("index.php/vlb/VlbController/dashboard_mouza")."/".$data['loc_names']['mouza']->mouza_pargona_code."'>".$data['loc_names']['circle']->loc_name."</a></li>
        <li class='breadcrumb-item active'>".$data['loc_names']['mouza']->loc_name."</li>";
        $this->load->view('layout/layout', $data);
    }
    public function dashboard_vills($mouza_code,$lot_no)
    {
        $this->dataswitch();
        $user_code = $this->session->userdata('usercode');
        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');

        $LAND_BANK_STATUS_PENDING = LAND_BANK_STATUS_PENDING;
        $LAND_BANK_STATUS_REVERT_BACK = LAND_BANK_STATUS_REVERT_BACK;
        $LAND_BANK_STATUS_APPROVED = LAND_BANK_STATUS_APPROVED;

        $villages = $this->MasterModel->getVillages($dist_code, $subdiv_code, $cir_code, $mouza_code, $lot_no);
        foreach ($villages as $village) {
            $village->total_pending = $this->db->query("select count(*) as c from land_bank_details where user_code = '$user_code' and status = '$LAND_BANK_STATUS_PENDING' and mouza_pargona_code='$village->mouza_pargona_code' and lot_no='$village->lot_no' and vill_townprt_code='$village->vill_townprt_code'")->row()->c;
            $village->total_reverted = $this->db->query("select count(*) as c from land_bank_details where user_code = '$user_code' and status = '$LAND_BANK_STATUS_REVERT_BACK' and mouza_pargona_code='$village->mouza_pargona_code' and lot_no='$village->lot_no' and vill_townprt_code='$village->vill_townprt_code'")->row()->c;
            $village->total_approved = $this->db->query("select count(*) as c from land_bank_details where user_code = '$user_code' and status = '$LAND_BANK_STATUS_APPROVED' and mouza_pargona_code='$village->mouza_pargona_code' and lot_no='$village->lot_no' and vill_townprt_code='$village->vill_townprt_code'")->row()->c;
            $vlb_not_in_clb = $this->VlbModel->getVlbNotInClb($dist_code, $subdiv_code, $cir_code,$village->mouza_pargona_code,$village->lot_no,$village->vill_townprt_code);
            $village->to_add_count = $vlb_not_in_clb - $village->total_pending;
        }
        $data['villages'] = $villages;
        $data['loc_names'] = $this->MasterModel->getLocNames($dist_code, $subdiv_code, $cir_code, $mouza_code, $lot_no);
        $data['_view'] = 'vlb/deo/dashboard_villages';
        $data['breadcrumbs'] = "
        <li class='breadcrumb-item'>Process</li>
        <li class='breadcrumb-item'><a href='".base_url("index.php/vlb/VlbController/dashboard")."'>Village Land Bank</a></li>
        <li class='breadcrumb-item'><a href='".base_url("index.php/vlb/VlbController/dashboard_mouza")."'>".$data['loc_names']['circle']->loc_name."</a></li>
        <li class='breadcrumb-item'><a href='".base_url("index.php/vlb/VlbController/dashboard_lot")."/".$data['loc_names']['mouza']->mouza_pargona_code."'>".$data['loc_names']['mouza']->loc_name."</a></li>
        <li class='breadcrumb-item active'>".$data['loc_names']['lot']->loc_name."</li>";
        $this->load->view('layout/layout', $data);
    }
    public function pending_deo()
    {
        $this->dataswitch();
        $data['location_names'] = $this->MasterModel->getLocNames($this->session->userdata('dcode'), $this->session->userdata('subdiv_code'), $this->session->userdata('cir_code'));
        $data['mouzas'] = $this->Chithamodel->mouzadetails($this->session->userdata('dcode'), $this->session->userdata('subdiv_code'), $this->session->userdata('cir_code'));
        $data['genders'] = json_encode($this->MasterModel->getAllGenderList());
        $data['castes'] = json_encode($this->MasterModel->getAllCasteList());
        $data['_view'] = 'vlb/deo/pending';
        $this->load->view('layout/layout', $data);
    }
    public function approved_deo()
    {
        $this->dataswitch();
        $data['location_names'] = $this->MasterModel->getLocNames($this->session->userdata('dcode'), $this->session->userdata('subdiv_code'), $this->session->userdata('cir_code'));
        $data['mouzas'] = $this->Chithamodel->mouzadetails($this->session->userdata('dcode'), $this->session->userdata('subdiv_code'), $this->session->userdata('cir_code'));
        $data['genders'] = json_encode($this->MasterModel->getAllGenderList());
        $data['castes'] = json_encode($this->MasterModel->getAllCasteList());
        $data['_view'] = 'vlb/deo/approved';
        $this->load->view('layout/layout', $data);
    }
    public function reverted_deo()
    {
        $this->dataswitch();
        $total_reverted = $this->db->get_where('land_bank_details', ['user_code' => $this->session->userdata('usercode'), 'status' => LAND_BANK_STATUS_REVERT_BACK])->num_rows();
        $data['total_reverted'] = $total_reverted;
        $data['location_names'] = $this->MasterModel->getLocNames($this->session->userdata('dcode'), $this->session->userdata('subdiv_code'), $this->session->userdata('cir_code'));
        $data['mouzas'] = $this->Chithamodel->mouzadetails($this->session->userdata('dcode'), $this->session->userdata('subdiv_code'), $this->session->userdata('cir_code'));
        $data['genders'] = json_encode($this->MasterModel->getAllGenderList());
        $data['castes'] = json_encode($this->MasterModel->getAllCasteList());
        $data['_view'] = 'vlb/deo/reverted';
        $this->load->view('layout/layout', $data);
    }
    public function show_dags($mouza_code = null,$lot_no = null,$vill_code = null)
    {
        $this->dataswitch();
        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');

        $data['location_names'] = $this->MasterModel->getLocNames($dist_code, $subdiv_code, $cir_code,$mouza_code,$lot_no,$vill_code);
        $data['mouzas'] = $this->Chithamodel->mouzadetails($dist_code, $subdiv_code, $cir_code);
        $data['lots'] = $this->Chithamodel->lotdetails($dist_code, $subdiv_code, $cir_code, $mouza_code);
        $data['villages'] = $this->Chithamodel->villagedetails($dist_code, $subdiv_code, $cir_code, $mouza_code, $lot_no);

        $data['genders'] = json_encode($this->MasterModel->getAllGenderList());
        $data['castes'] = json_encode($this->MasterModel->getAllCasteList());
        $data['mouza_code'] = $mouza_code;
        $data['lot_no'] = $lot_no;
        $data['vill_code'] = $vill_code;
        $data['_view'] = 'vlb/deo/show_dags';
        $data['breadcrumbs'] = "
        <li class='breadcrumb-item'>Process</li>
        <li class='breadcrumb-item'><a href='".base_url("index.php/vlb/VlbController/dashboard")."'>Village Land Bank</a></li>
        <li class='breadcrumb-item'><a href='".base_url("index.php/vlb/VlbController/dashboard_mouza")."'>".$data['location_names']['circle']->loc_name."</a></li>
        <li class='breadcrumb-item'><a href='".base_url("index.php/vlb/VlbController/dashboard_lot")."/".$data['location_names']['mouza']->mouza_pargona_code."'>".$data['location_names']['mouza']->loc_name."</a></li>
        <li class='breadcrumb-item'><a href='".base_url("index.php/vlb/VlbController/dashboard_vills")."/".$data['location_names']['mouza']->mouza_pargona_code."/".$data['location_names']['lot']->lot_no."'>".$data['location_names']['lot']->loc_name."</a></li>
        <li class='breadcrumb-item active'>".$data['location_names']['village']->loc_name."</li>";
        $this->load->view('layout/layout', $data);
    }
    public function getDags()
    {
        $dist_code = $this->input->post('dist_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $vill_code = $this->input->post('vill_townprt_code');

        $this->dataswitch();
        $query = $this->dagsByVillageQuery($dist_code, $subdiv_code, $cir_code, $mouza_code, $lot_no, $vill_code);
        $dags = $query->result();
        $dags_all = [];
        foreach ($dags as $dag) {
            $dag_s = [];
            $dag_s['dag_no'] = $dag->dag_no;
            $dag_s['land_class'] = $this->utilityclass->getLandClassCode($dag->land_class_code);
            $dag_s['dag_area_b'] = $dag->dag_area_b ? $dag->dag_area_b : '0';
            $dag_s['dag_area_k'] = $dag->dag_area_k ? $dag->dag_area_k : '0';
            $dag_s['dag_area_l'] = $dag->dag_area_lc ? $dag->dag_area_lc : '0';
            $dag_s['dag_area_g'] = $dag->dag_area_g ? $dag->dag_area_g : '0';
            $dag_s['dag_area_kr'] = $dag->dag_area_kr ? $dag->dag_area_kr : '0';
            $dag_s['action'] = '<button @click="addDetailsModalOpen(' . $dag->dag_no . ')" class="btn btn-primary btn-sm"><i class="fa fa-plus" aria-hidden="true"></i>Add Details</button>';
            $dags_all[] = $dag_s;
        }
        echo  json_encode(['data' => $dags_all]);
    }
    private function dagsByVillageQuery($dist_code, $subdiv_code, $cir_code, $mouza_code, $lot_no, $vill_code)
    {
        $loc_query = "where dist_code = '$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_code' and lot_no='$lot_no' and vill_townprt_code='$vill_code' ";

        $not_in_vlb_query = "and (dist_code,subdiv_code,cir_code,mouza_pargona_code,lot_no,vill_townprt_code,trim(dag_no)) 
        not in (select dist_code,subdiv_code,cir_code,mouza_pargona_code,lot_no,vill_townprt_code,
        trim(dag_no) from land_bank_details " . $loc_query . ")";
        $sql = "select * from chitha_basic " . $loc_query . " and patta_type_code  in 
                (select type_code from patta_code where jamabandi='n') and 
                (dag_area_b*100+dag_area_k*20+dag_area_lc::int) > 0 and 
                (subdiv_code,cir_code,mouza_pargona_code, lot_no,vill_townprt_code) 
                in (select subdiv_code,cir_code,mouza_pargona_code, lot_no,vill_townprt_code from 
                location) " . $not_in_vlb_query;
        return $this->db->query($sql);
    }
    public function getLandBankDetails()
    {
        $this->dataswitch();
        $dist_code = $this->input->post('dist_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $vill_townprt_code = $this->input->post('vill_townprt_code');
        $dag_no = $this->input->post('dag_no');
        $land_bank_det = $this->db->get_where('land_bank_details', [
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'mouza_pargona_code' => $mouza_pargona_code,
            'lot_no' => $lot_no,
            'vill_townprt_code' => $vill_townprt_code,
            'dag_no' => $dag_no,
        ])->row();
        if ($land_bank_det) {
            $encroachers = $this->db->get_where('land_bank_encroacher_details', [
                'land_bank_details_id' => $land_bank_det->id
            ])->result();
        }
        echo json_encode(['status' => '1', 'land_bank_det' => $land_bank_det, 'encroachers' => $encroachers]);
        return;
    }
    public function getLandBankDetailsById()
    {
        $land_bank_id = $this->input->post('land_bank_id');
        $vlb_type = $this->input->post('vlb_type');
        $this->dataswitch();
        if ($vlb_type == 'approved') {
            $land_bank_det = $this->db->get_where('c_land_bank_details', [
                'id' => $land_bank_id
            ])->row();
            if ($land_bank_det) {
                $encroachers = $this->db->get_where('c_land_bank_encroacher_details', [
                    'c_land_bank_details_id' => $land_bank_det->id
                ])->result();
            }
        } else {
            $land_bank_det = $this->db->get_where('land_bank_details', [
                'id' => $land_bank_id
            ])->row();
            if ($land_bank_det) {
                $encroachers = $this->db->get_where('land_bank_encroacher_details', [
                    'land_bank_details_id' => $land_bank_det->id
                ])->result();
            }
        }
        echo json_encode(['status' => '1', 'land_bank_det' => $land_bank_det, 'encroachers' => $encroachers]);
        return;
    }
    public function storeLandBankDetails()
    {
        $dist_code = $this->input->post('dist_code');
        $whether_encroached = $this->input->post('whether_encroached');
        $encroachers = json_decode($this->input->post('encroachers'));
        $this->form_validation->set_rules('dist_code', 'District', 'required|callback_check_script|max_length[2]|trim');
        $this->form_validation->set_rules('subdiv_code', 'Sub-division', 'required|callback_check_script|max_length[2]|trim');
        $this->form_validation->set_rules('cir_code', 'Circle', 'required|callback_check_script|max_length[2]|trim');
        $this->form_validation->set_rules('mouza_pargona_code', 'Mouza', 'required|callback_check_script|max_length[2]|trim');
        $this->form_validation->set_rules('lot_no', 'Lot', 'required|callback_check_script|max_length[2]|trim');
        $this->form_validation->set_rules('vill_townprt_code', 'Village', 'required|callback_check_script|max_length[5]|trim');

        $this->form_validation->set_rules('dag_no', 'Dag No', 'required|callback_check_script|max_length[12]|trim');
        $this->form_validation->set_rules('nature_of_reservation', 'Type Of Govt Land', 'required|callback_check_script|less_than[9]|trim');
        $this->form_validation->set_rules('whether_encroached', 'Whether Encroached ', 'required|callback_check_script|exact_length[1]|trim');
        if ($whether_encroached == 'Y') {
            $this->form_validation->set_rules('en_area_b', '(Encroach-Area)-Bigha ', 'required|integer|greater_than_equal_to[0]');
            $this->form_validation->set_rules('en_area_k', '(Encroach-Area)-Katha ', 'required|integer|less_than[5]|greater_than_equal_to[0]');
            $this->form_validation->set_rules('en_area_lc', '(Encroach-Area)-Lessa ', 'required|numeric|less_than[20]|greater_than_equal_to[0]');
            if (in_array($dist_code, BARAK_VALLEY)) {
                $this->form_validation->set_rules('en_area_g', '(Encroach-Area)-Ganda ', 'required|numeric|greater_than_equal_to[0]');
                $this->form_validation->set_rules('en_area_kr', '(Encroach-Area)-Kranti ', 'required|numeric|greater_than_equal_to[0]');
            }
            if (count($encroachers) == 0) {
                echo json_encode(array('msg' => 'Please add atleast one Encroacher.', 'status' => '0'));
                return;
            }
        }



        $this->form_validation->set_rules('longitude', 'Longitude ', 'callback_check_script|numeric|trim');
        $this->form_validation->set_rules('latitude', 'Latitude ', 'callback_check_script|numeric|trim');


        if ($this->form_validation->run() == false) {
            echo json_encode(array('msg' => validation_errors(), 'status' => '0'));
            return;
        } else {
            $this->dataswitch();
            $subdiv_code = $this->input->post('subdiv_code');
            $cir_code = $this->input->post('cir_code');
            $mouza_code = $this->input->post('mouza_pargona_code');
            $lot_no = $this->input->post('lot_no');
            $vill_code = $this->input->post('vill_townprt_code');
            $dag_no = $this->input->post('dag_no');
            $nature_of_reservation = $this->input->post('nature_of_reservation');
            $en_area_b = $this->input->post('en_area_b');
            $en_area_k = $this->input->post('en_area_k');
            $en_area_lc = $this->input->post('en_area_lc');
            $en_area_g = $this->input->post('en_area_g');
            $en_area_kr = $this->input->post('en_area_kr');
            $longitude = $this->input->post('longitude');
            $latitude = $this->input->post('latitude');
            $user_code = $this->session->userdata('usercode');

            $existing_vlb = $this->VlbModel->checkPreviousEntries($dist_code, $subdiv_code, $cir_code, $mouza_code, $lot_no, $vill_code, $dag_no);
            if ($existing_vlb) {
                if ($existing_vlb->status == LAND_BANK_STATUS_PENDING) {
                    echo json_encode(
                        [
                            'status' => '0',
                            'msg' => "Previous Entries Of This Dag Is Already Pending For Approval..!, After Approval By CO Only New Entries Can Be Updated!",
                        ]
                    );
                    return;
                }
            }
            if ($existing_vlb->status == LAND_BANK_STATUS_APPROVED) {
                echo json_encode(
                    [
                        'status' => '0',
                        'msg' => "Previous Entries Of This Dag Has Approved Recently..!, Please Refresh The Page and Add Again..!",
                    ]
                );
                exit;
            }

            $village_uuid = $this->MasterModel->getVillageUuid($dist_code, $subdiv_code, $cir_code, $mouza_code, $lot_no, $vill_code);
            date_default_timezone_set("Asia/Calcutta");
            $land_bank_details_data = [
                'dist_code' => $dist_code,
                'subdiv_code' => $subdiv_code,
                'cir_code' => $cir_code,
                'mouza_pargona_code' => $mouza_code,
                'lot_no' => $lot_no,
                'vill_townprt_code' => $vill_code,
                'year' => date("Y"),
                'dag_no' => $dag_no,
                'village_uuid' => $village_uuid,
                'nature_of_reservation' => $nature_of_reservation,
                'whether_encroached' => $whether_encroached,
                'created_at' => date('Y-m-d H:i:s'),
                'en_area_b' => $en_area_b ? $en_area_b : '0',
                'en_area_k' => $en_area_k ? $en_area_k : '0',
                'en_area_lc' => $en_area_lc ? $en_area_lc : '0',
                'en_area_g' => $en_area_g ? $en_area_g : '0',
                'en_area_kr' => $en_area_kr ? $en_area_kr : '0',
                'no_of_encroacher' =>  count($encroachers),
                'longitude' => $longitude,
                'latitude' => $latitude,
                'status' => LAND_BANK_STATUS_PENDING,
                'user_code' => $user_code
            ];

            error_reporting(0);
            $error_msg = [];
            foreach ($encroachers as $key => $encroacher) {
                $_POST['v_name'] = $encroacher->name;
                $_POST['v_fathers_name'] = $encroacher->fathers_name;
                $_POST['v_gender'] = $encroacher->gender;
                $_POST['v_encroachment_from'] = $encroacher->encroachment_from;
                $_POST['v_encroachment_to'] = $encroacher->encroachment_to;
                $_POST['v_landless_indigenous'] = $encroacher->landless_indigenous;
                $_POST['v_landless'] = $encroacher->landless;
                $_POST['v_caste'] = $encroacher->caste;
                $_POST['v_erosion'] = $encroacher->erosion;
                $_POST['v_landslide'] = $encroacher->landslide;
                $_POST['v_type_of_land_use'] = $encroacher->type_of_land_use;
                $_POST['v_type_of_encroacher'] = $encroacher->type_of_encroacher;
                $lb_enc_val = $this->makeEncroValidRule($key);
                $this->form_validation->set_rules($lb_enc_val);
                $this->form_validation->set_message('check_script', 'Please Fill The %s Correctly!');
                $this->form_validation->set_message('date_valid', 'Please Fill The %s Correctly!');
                if ($this->form_validation->run() == FALSE) {
                    foreach ($lb_enc_val as $rule) {
                        if (form_error($rule['field'])) {
                            array_push($error_msg, form_error($rule['field']));
                        }
                    }
                }
                $from_date = strtotime($_POST['v_encroachment_from']);
                $to_date   = strtotime($_POST['v_encroachment_to']);
                $date_now = strtotime(date("Y-m-d"));
                if ($from_date > $date_now) {
                    echo json_encode(
                        [
                            'result' => 'logical_validation_error',
                            'msg' => "'Encroachment From' Can't Be Greater Than Today in Row-no " . ((int)$key + 1) . ", Please fill the dates correctly!"
                        ]
                    );
                    exit;
                }
                if ($to_date > $date_now) {
                    echo json_encode(
                        [
                            'result' => 'logical_validation_error',
                            'msg' => "'Encroachment To' Can't Be Greater Than Today in Row-no " . ((int)$key + 1) . ", Please fill the dates correctly!"
                        ]
                    );
                    exit;
                }
                if ($from_date > $to_date) {
                    echo json_encode(
                        [
                            'result' => 'logical_validation_error',
                            'msg' => "'Encroachment From' Can't Be Greater Than 'Encroachment To' in Row-no " . ((int)$key + 1) . ", Please fill the dates correctly!"
                        ]
                    );
                    exit;
                }
            }
            if (count($error_msg) != 0) {
                echo json_encode(['result' => '0', 'msg' => $error_msg]);
                exit;
            }
            if ($existing_vlb) {
                $this->VlbModel->updateVlb($land_bank_details_data, $encroachers);
            } else {
                $this->db->trans_begin();
                $insert_lb_details = $this->db->insert('land_bank_details', $land_bank_details_data);
                if ($insert_lb_details != true) {
                    $this->db->trans_rollback();
                    log_message("error", "#LB001, Error in insert, table 'land_bank_details' with data :" . json_encode($land_bank_details_data));
                    echo json_encode(['status' => '0', 'msg' => 'Some error occured, Error-Code : #LB001']);
                }
                $land_bank_inserted_id = $this->db->insert_id();
                foreach ($encroachers as $key => $encroacher) {
                    $insert_lb_enc = $this->db->insert('land_bank_encroacher_details', [
                        'land_bank_details_id' => $land_bank_inserted_id,
                        'name' => $encroacher->name,
                        'fathers_name' => $encroacher->fathers_name,
                        'gender' => $encroacher->gender,
                        'encroachment_from' => $encroacher->encroachment_from,
                        'encroachment_to' => $encroacher->encroachment_to,
                        'landless_indigenous' => $encroacher->landless_indigenous,
                        'landless' => $encroacher->landless,
                        'caste' => $encroacher->caste,
                        'erosion' => $encroacher->erosion,
                        'landslide' => $encroacher->landslide,
                        'type_of_land_use' => $encroacher->type_of_land_use,
                        'type_of_encroacher' => $encroacher->type_of_encroacher,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                    if ($insert_lb_enc != true) {
                        $this->db->trans_rollback();
                        log_message("error", "#LB002, Error in insert, table 'land_bank_encroacher_details'");
                        echo json_encode(['status' => '0', 'msg' => 'Some error occured, Error-Code : #LB002']);
                    }
                }

                if ($this->db->trans_status() == FALSE) {
                    $this->db->trans_rollback();
                    log_message("error", "#LB005, Transaction Status Error In Land Bank Tables");
                    echo json_encode(['status' => '0', 'msg' => 'Some error occured, Error-Code : #LB005']);
                } else {
                    $this->db->trans_commit();
                    echo json_encode(['status' => '1', 'msg' => 'Land Bank Details Added Successfully And Forwarded To CO For Approval!']);
                }
            }
        }
    }
    public function deleteEncroacher()
    {
        $this->dataswitch();
        $encroacher_id = $this->input->post('encroacher_id');
        $land_bank_details_id = $this->input->post('land_bank_details_id');
        if ($encroacher_id && $land_bank_details_id) {
            $this->db->where('land_bank_details_id', $land_bank_details_id)->where('id', $encroacher_id)->delete('land_bank_encroacher_details');
        }
        echo json_encode(['status' => '1', 'msg' => 'Encroacher deleted successfully.']);
        return;
    }
    function check_script($str)
    {

        if (strpos(trim(strtolower($str)), '<') !== false) {
            return FALSE;
        }

        if (strpos(trim(strtolower($str)), '>') !== false) {
            return FALSE;
        }

        if (strpos(trim(strtolower($str)), '<script>') !== false) {
            return FALSE;
        }
        if (strpos(trim(strtolower($str)), '</script>') !== false) {
            return FALSE;
        }
        return TRUE;
    }
    private function makeEncroValidRule($key)
    {
        $key  += 1;
        return [
            [
                'field' => 'v_name',
                'label' => "Encroacher Name (Row - $key)",
                'rules' => 'required|callback_check_script|max_length[50]|trim'
            ],
            [
                'field' => 'v_fathers_name',
                'label' => "Encroacher Father Name (Row - $key)",
                'rules' => 'required|callback_check_script|max_length[50]|trim'
            ],
            [
                'field' => 'v_gender',
                'label' => "Gender (Row- $key )",
                'rules' => 'required|callback_check_script|less_than_equal_to[3]|numeric|trim'
            ],
            [
                'field' => 'v_encroachment_from',
                'label' => "Encroacher From date (YYYY-MM-DD) (Row- $key )",
                'rules' => 'required|callback_check_script|callback_date_valid|trim'
            ],
            [
                'field' => 'v_encroachment_to',
                'label' => "Encroacher To date (YYYY-MM-DD) (Row-$key )",
                'rules' => 'required|callback_check_script|callback_date_valid|trim'
            ],
            [
                'field' => 'v_landless_indigenous',
                'label' => "Landless Indigenous (Row- $key )",
                'rules' => 'required|callback_check_script|exact_length[1]|trim'
            ],
            [
                'field' => 'v_landless',
                'label' => "Landless (Row- $key )",
                'rules' => 'required|callback_check_script|exact_length[1]|trim'
            ],
            [
                'field' => 'v_caste',
                'label' => "Caste (Row- $key )",
                'rules' => 'required|callback_check_script|less_than_equal_to[6]|trim'
            ],
            [
                'field' => 'v_erosion',
                'label' => "Erosion (Row- $key )",
                'rules' => 'required|callback_check_script|exact_length[1]|trim'
            ],
            [
                'field' => 'v_landslide',
                'label' => "Landslide (Row- $key )",
                'rules' => 'required|callback_check_script|exact_length[1]|trim'
            ],
            [
                'field' => 'v_type_of_land_use',
                'label' => "Type Of Land Use (Row- $key )",
                'rules' => 'required|callback_check_script|less_than[6]|trim'
            ],
            [
                'field' => 'v_type_of_land_use',
                'label' => "Type Of Land Use (Row- $key )",
                'rules' => 'required|callback_check_script|less_than[6]|trim'
            ],
            [
                'field' => 'v_type_of_encroacher',
                'label' => "Type Of Encroacher Use (Row- $key )",
                'rules' => 'required|callback_check_script|less_than[6]|trim'
            ],
        ];
    }
    function date_valid($date)
    {
        if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date))
            return false;

        $day = (int) substr($date, 8, 2);
        $month = (int) substr($date, 5, 2);
        $year = (int) substr($date, 0, 4);
        return checkdate($month, $day, $year);
    }
    public function getPendingListsDeo()
    {
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $vill_code = $this->input->post('vill_townprt_code');

        $this->dataswitch();
        $vlbs = $this->VlbModel->getVlbListByLocation($subdiv_code, $cir_code, $mouza_code, $lot_no, $vill_code, $this->session->userdata('usercode'), [LAND_BANK_STATUS_PENDING]);
        $vlbs_all = [];
        foreach ($vlbs as $vlb) {
            $vlb_s = [];
            $vlb_s['village'] = $this->utilityclass->getVillageName(
                $vlb->dist_code,
                $vlb->subdiv_code,
                $vlb->cir_code,
                $vlb->mouza_pargona_code,
                $vlb->lot_no,
                $vlb->vill_townprt_code
            );
            $vlb_s['dag_no'] = $vlb->dag_no;
            $vlb_s['pending_with'] = $vlb->status == LAND_BANK_STATUS_PENDING ? 'CO' : '';
            $vlb_s['created_at'] = $vlb->created_at;
            $vlb_s['action'] = '<button @click="viewDetailsModalOpen(' . $vlb->id . ')" class="btn btn-primary btn-sm"><i class="fa fa-eye" aria-hidden="true"></i>View</button>';
            $vlbs_all[] = $vlb_s;
        }
        echo  json_encode(['data' => $vlbs_all]);
    }
    public function getRevertedListsDeo()
    {
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $vill_code = $this->input->post('vill_townprt_code');

        $this->dataswitch();
        $vlbs = $this->VlbModel->getVlbListByLocation($subdiv_code, $cir_code, $mouza_code, $lot_no, $vill_code, $this->session->userdata('usercode'), [LAND_BANK_STATUS_REVERT_BACK]);
        $vlbs_all = [];
        foreach ($vlbs as $vlb) {
            $rejected_user = $this->VlbModel->getLBRevertUser($vlb->id);
            $vlb_s = [];
            $vlb_s['village'] = $this->utilityclass->getVillageName(
                $vlb->dist_code,
                $vlb->subdiv_code,
                $vlb->cir_code,
                $vlb->mouza_pargona_code,
                $vlb->lot_no,
                $vlb->vill_townprt_code
            );
            $vlb_s['dag_no'] = $vlb->dag_no;
            $vlb_s['rejected_by'] = $rejected_user == 'CO' ?: 'NA';
            $vlb_s['rejected_time'] = $vlb->created_at;
            $vlb_s['remark'] = '<button @click="viewReamrkModalOpen(' . $vlb->id . ')" class="btn btn-primary btn-sm"><i class="fa fa-eye" aria-hidden="true"></i>View Remark</button>';
            $vlb_s['action'] = '<button @click="viewDetailsModalOpen(' . $vlb->id . ')" class="btn btn-primary btn-sm"><i class="fa fa-eye" aria-hidden="true"></i>View</button>';
            $vlbs_all[] = $vlb_s;
        }
        echo  json_encode(['data' => $vlbs_all]);
    }
    public function getApprovedListsDeo()
    {
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $vill_code = $this->input->post('vill_townprt_code');

        $this->dataswitch();
        $vlbs = $this->VlbModel->getVlbListByLocation($subdiv_code, $cir_code, $mouza_code, $lot_no, $vill_code, $this->session->userdata('usercode'), [LAND_BANK_STATUS_APPROVED]);
        $vlbs_all = [];
        foreach ($vlbs as $vlb) {
            $vlb_s = [];
            $vlb_s['village'] = $this->utilityclass->getVillageName(
                $vlb->dist_code,
                $vlb->subdiv_code,
                $vlb->cir_code,
                $vlb->mouza_pargona_code,
                $vlb->lot_no,
                $vlb->vill_townprt_code
            );
            $vlb_s['dag_no'] = $vlb->dag_no;
            $vlb_s['approved_at'] = $vlb->created_at;
            $vlb_s['action'] = '<button @click="viewDetailsModalOpen(' . $vlb->id . ')" class="btn btn-primary btn-sm"><i class="fa fa-eye" aria-hidden="true"></i>View</button>';
            $vlbs_all[] = $vlb_s;
        }
        echo  json_encode(['data' => $vlbs_all]);
    }
    public function getRevertedRemark(){
        $this->dataswitch();
        $land_bank_details_id = $this->input->post('land_bank_id');
        $proceeding = $this->VlbModel->getVlbRejectedRmk($land_bank_details_id);
        echo json_encode(['status' => '1','proceeding' => $proceeding]);
    }
}
