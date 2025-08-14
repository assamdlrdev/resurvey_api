<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class VlbCoController extends CI_Controller
{
    use CommonTrait;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('UtilityClass');
        $this->load->model('MasterModel');
        $this->load->model('vlb/VlbModel');
        $this->load->model('UserModel');
        $this->load->library('form_validation');
    }
    public function dashboard()
    {
        $this->dataswitch();
        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');

        $total_pending = $this->db->get_where('land_bank_details', ['dist_code' => $dist_code, 'subdiv_code' => $subdiv_code, 'cir_code' => $cir_code, 'status' => LAND_BANK_STATUS_PENDING])->num_rows();
        $total_approved = $this->db->get_where('land_bank_details', ['dist_code' => $dist_code, 'subdiv_code' => $subdiv_code, 'cir_code' => $cir_code, 'status' => LAND_BANK_STATUS_APPROVED])->num_rows();

        $data['total_pending'] = $total_pending;
        $data['total_approved'] = $total_approved;

        $data['_view'] = 'vlb/co/dashboard';
        $this->load->view('layout/layout', $data);
    }
    public function pending_co()
    {
        $this->dataswitch();
        $total_pending = $this->db->get_where('land_bank_details', ['user_code' => $this->session->userdata('usercode'), 'status' => 'P'])->num_rows();
        $data['total_pending'] = $total_pending;
        $data['location_names'] = $this->MasterModel->getLocNames($this->session->userdata('dcode'), $this->session->userdata('subdiv_code'), $this->session->userdata('cir_code'));

        $data['mouzas'] = $this->Chithamodel->mouzadetails($this->session->userdata('dcode'), $this->session->userdata('subdiv_code'), $this->session->userdata('cir_code'));
        $data['genders'] = json_encode($this->MasterModel->getAllGenderList());
        $data['castes'] = json_encode($this->MasterModel->getAllCasteList());
        $data['_view'] = 'vlb/co/pending';
        $this->load->view('layout/layout', $data);
    }
    public function approved_co()
    {
        $this->dataswitch();
        $data['location_names'] = $this->MasterModel->getLocNames($this->session->userdata('dcode'), $this->session->userdata('subdiv_code'), $this->session->userdata('cir_code'));

        $data['mouzas'] = $this->Chithamodel->mouzadetails($this->session->userdata('dcode'), $this->session->userdata('subdiv_code'), $this->session->userdata('cir_code'));
        $data['genders'] = json_encode($this->MasterModel->getAllGenderList());
        $data['castes'] = json_encode($this->MasterModel->getAllCasteList());
        $data['_view'] = 'vlb/co/approved';
        $this->load->view('layout/layout', $data);
    }
    public function getPendingListsCo()
    {
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $vill_code = $this->input->post('vill_townprt_code');

        $this->dataswitch();
        $vlbs = $this->VlbModel->getVlbListByLocation($subdiv_code, $cir_code, $mouza_code, $lot_no, $vill_code, null, [LAND_BANK_STATUS_PENDING]);
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
            $vlb_s['action'] = '
            <button @click="viewDetailsModalOpen(' . $vlb->id . ')" class="btn btn-primary btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> View</button>
            <button @click="approveCoModalOpen(' . $vlb->id . ')" class="btn btn-success btn-sm"><i class="fa fa-check" aria-hidden="true"></i> Approve</button>
            <button @click="revertCoModalOpen(' . $vlb->id . ')" class="btn btn-danger btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Revert</button>
            ';
            $vlbs_all[] = $vlb_s;
        }
        echo  json_encode(['data' => $vlbs_all]);
    }
    public function getApprovedListsCo()
    {
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $vill_code = $this->input->post('vill_townprt_code');

        $this->dataswitch();
        $vlbs = $this->VlbModel->getVlbListByLocation($subdiv_code, $cir_code, $mouza_code, $lot_no, $vill_code, null, [LAND_BANK_STATUS_APPROVED]);
        $vlbs_all = [];
        foreach ($vlbs as $vlb) {
            $vlb_s = [];
            $user = $this->UserModel->getUser($vlb->dist_code,$vlb->subdiv_code,$vlb->cir_code,$vlb->user_code);
            $vlb_s['village'] = $this->utilityclass->getVillageName(
                $vlb->dist_code,
                $vlb->subdiv_code,
                $vlb->cir_code,
                $vlb->mouza_pargona_code,
                $vlb->lot_no,
                $vlb->vill_townprt_code
            );
            $vlb_s['dag_no'] = $vlb->dag_no;
            $vlb_s['created_by'] = $user ? $user->name : '';
            $vlb_s['created_at'] = $vlb->created_at;
            $vlb_s['action'] = '
            <button @click="viewDetailsModalOpen(' . $vlb->id . ')" class="btn btn-primary btn-sm"><i class="fa fa-eye" aria-hidden="true"></i>View</button>
            ';
            $vlbs_all[] = $vlb_s;
        }
        echo  json_encode(['data' => $vlbs_all]);
    }
    public function approveCo()
    {
        $this->form_validation->set_rules('land_bank_id', 'Inavalid Data', 'required');
        $this->form_validation->set_rules('co_remark', 'Co Remark ', 'required|max_length[200]');
        if ($this->form_validation->run() == false) {
            echo json_encode(array('msg' => validation_errors(), 'status' => '0'));
            return;
        } else {
            $this->dataswitch();
            $land_bank_id = $this->input->post('land_bank_id');
            $co_remark = $this->input->post('co_remark');
            $this->VlbModel->approveVlbByCo($land_bank_id,$co_remark);
        }
    }
    public function revertCo()
    {
        $this->form_validation->set_rules('land_bank_id', 'Inavaid Data', 'required');
        $this->form_validation->set_rules('revert_remark', 'Revert Remark ', 'required|max_length[200]');
        if ($this->form_validation->run() == false) {
            echo json_encode(array('msg' => validation_errors(), 'status' => '0'));
            return;
        } else {
            $this->dataswitch();
            $land_bank_id = $this->input->post('land_bank_id');
            $revert_remark = $this->input->post('revert_remark');
            $this->VlbModel->revertVlbCo($land_bank_id,$revert_remark);
        }
    }
}
