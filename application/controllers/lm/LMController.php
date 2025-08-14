<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class LMController extends CI_Controller
{
    use CommonTrait;

    function __construct()
    {
        parent::__construct();
        $this->load->model('UserModel');
        $this->load->model('Chithamodel');
        $this->load->model('MasterModel');
        $this->load->model('LoginModel');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->helper('security');
    }
    public function index()
    {
        $this->dataswitch();
        $data['page_header'] = 'Lot Mondals';
        $data['district'] = $this->Chithamodel->districtdetails($this->session->userdata('dcode'));
        $data['sub_divs'] = $this->Chithamodel->subdivisiondetails($this->session->userdata('dcode'));
        $data['_view'] = 'lm/list_lot_mondal';
        $this->load->view('layout/layout', $data);
    }

    public function getAllDetails()
    {
        $dist_code = $this->input->post('dist_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');

        $this->dataswitch();
        $query = $this->makeQuery($dist_code, $subdiv_code, $cir_code, $mouza_code, $lot_no);
        $lms = $query->result();
        $lms_all = [];
        foreach ($lms as $lm) {
            $lm_s = [];
            $lm_s['dist_name'] = $lm->dist_name;
            $lm_s['dist_code'] = $lm->dist_code;
            $lm_s['subdiv_name'] = $lm->subdiv_name;
            $lm_s['subdiv_code'] = $lm->subdiv_code;
            $lm_s['circle_name'] = $lm->circle_name;
            $lm_s['circle_code'] = $lm->circle_code;
            $lm_s['mouza_name'] = $lm->mouza_name;
            $lm_s['mouza_pargona_code'] = $lm->mouza_pargona_code;
            $lm_s['lot_name'] = $lm->lot_name;
            $lm_s['lot_no'] = $lm->lot_no;
            $lm_s['lm_name'] = $lm->lm_name;
            $lm_s['lm_code'] = $lm->lm_code;
            $actionLink = base_url('index.php/lm/LMController/editLotMondal/' . $lm->dist_code . '/' . $lm->subdiv_code . '/' . $lm->circle_code . '/' . $lm->mouza_pargona_code . '/' . $lm->lot_no . '/' . $lm->lm_code);
            $lm_s['action'] = "<a href='$actionLink'><button type='button' class='btn btn-info'>Edit <i class=\"fa fa-edit\" aria-hidden=\"true\"></i></button></a>";
            $lms_all[] = $lm_s;
        }
        echo  json_encode(['data' => $lms_all]);
    }
    public function makeQuery($dist_code, $subdiv_code, $cir_code, $mouza_code, $lot_no)
    {
        $sql = "SELECT 
            l5.loc_name AS dist_name,
            l4.loc_name AS subdiv_name,
            l3.loc_name AS circle_name,
            l2.loc_name AS mouza_name,
            l1.loc_name AS lot_name,
            lm_code.lm_name,
            lm_code.lm_code,
            lm_code.dist_code AS dist_code,
            lm_code.subdiv_code AS subdiv_code,
            lm_code.cir_code AS circle_code,
            lm_code.mouza_pargona_code AS mouza_pargona_code,
            lm_code.lot_no as lot_no
        FROM lm_code 
        JOIN location l1 ON lm_code.dist_code = l1.dist_code
            AND lm_code.subdiv_code = l1.subdiv_code 
            AND lm_code.cir_code = l1.cir_code 
            AND lm_code.mouza_pargona_code = l1.mouza_pargona_code
            AND lm_code.lot_no = l1.lot_no
        JOIN location l2 ON lm_code.dist_code = l2.dist_code
            AND lm_code.subdiv_code = l2.subdiv_code 
            AND lm_code.cir_code = l2.cir_code 
            AND lm_code.mouza_pargona_code = l2.mouza_pargona_code
        JOIN location l3 ON lm_code.dist_code = l3.dist_code
            AND lm_code.subdiv_code = l3.subdiv_code 
            AND lm_code.cir_code = l3.cir_code 
        JOIN location l4 ON lm_code.dist_code = l4.dist_code
            AND lm_code.subdiv_code = l4.subdiv_code 
        JOIN location l5 ON lm_code.dist_code = l5.dist_code
        WHERE 
            l1.vill_townprt_code = '00000' 
            AND l2.lot_no = '00' 
            AND l3.mouza_pargona_code = '00' 
            AND l4.cir_code = '00' 
            AND l5.subdiv_code = '00'";

        if ($dist_code) {
            $sql .= " AND lm_code.dist_code = '$dist_code'";
        }

        if ($subdiv_code) {
            $sql .= " AND lm_code.subdiv_code = '$subdiv_code'";
        }

        if ($cir_code) {
            $sql .= " AND lm_code.cir_code = '$cir_code'";
        }

        if ($mouza_code) {
            $sql .= " AND lm_code.mouza_pargona_code = '$mouza_code'";
        }

        if ($lot_no) {
            $sql .= " AND lm_code.lot_no = '$lot_no'";
        }

        $text = "ORDER BY lm_name ASC";
        $sql .= " " . $text;


        return $this->db->query($sql);
    }

    public function addLotMondal()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $distcode = $this->session->userdata('dcode');
        $data['districts'] = $this->Chithamodel->districtdetails($distcode);
        $data['page_header'] = 'Create Lot Mondal';
        $data['_view'] = 'lm/add_lot_mondal';
        $this->load->view('layout/layout', $data);
    }

    public function createLotMondal()
    {
        $this->dataswitch();
        $data = array();
        $this->form_validation->set_rules('dist_code', 'District Name', 'trim|integer|required|strip_tags|xss_clean', array('required' => 'District Name is Required.'));
        $this->form_validation->set_rules('subdiv_code', 'Sub Division Name', 'trim|integer|required|strip_tags|xss_clean', array('required' => 'Sub Division Name is Required.'));
        $this->form_validation->set_rules('cir_code', 'Circle Name', 'trim|integer|required|strip_tags|xss_clean', array('required' => 'Circle Name is Required.'));
        $this->form_validation->set_rules('mouza_pargona_code', 'Mouza Name', 'trim|integer|required|strip_tags|xss_clean', array('required' => 'Mouza Name is Required.'));
        $this->form_validation->set_rules('lot_no', 'Lot Number', 'trim|integer|required|strip_tags|xss_clean', array('required' => 'Lot Number is Required.'));
        $this->form_validation->set_rules('lm_name', 'Lot Mondal Name', 'trim|strip_tags|required|max_length[50]', array('required' => 'Lot Mondal Name is Required.'));
        $this->form_validation->set_rules('status', 'Status', 'required|trim|strip_tags|xss_clean|callback_validateStatus', array('required' => 'Status is Required.'));
        $this->form_validation->set_rules('date_of_joining', 'Date of Joining', 'required|trim|strip_tags|xss_clean', array('required' => 'Date of Joining is Required.'));
        $this->form_validation->set_rules('corres_sk_code', 'SK', 'required|trim|strip_tags|xss_clean', array('required' => 'Supervisor Name is Required.'));
        $this->form_validation->set_rules('phone_no', 'Phone Number', 'trim|strip_tags|xss_clean|required|max_length[10]', array('required' => 'Phone Number is Required.'));
        $this->form_validation->set_message('validateStatus', 'Select Correct Status.');
        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
        } else {
            $data = array(
                'dist_code'   => $this->input->post('dist_code'),
                'subdiv_code' => $this->input->post('subdiv_code'),
                'cir_code'    => $this->input->post('cir_code'),
                'mouza_pargona_code'    => $this->input->post('mouza_pargona_code'),
                'lot_no'   => $this->input->post('lot_no'),
                'lm_name' => $this->input->post('lm_name'),
                'phone_no'    => $this->input->post('phone_no'),
                'status'    => $this->input->post('status'),
                'date_of_joining'   => $this->input->post('date_of_joining'),
                'corres_sk_code'    => $this->input->post('corres_sk_code'),
            );
            $insert = $this->insertLm($data);
            if ($insert) {
                echo json_encode(array('msg' => 'LM Created Successfuly', 'st' => 1));
            } else {
                echo json_encode(array('msg' => 'LM Not Created. Try Again Later.', 'st' => 0));
            }
        }
    }


    public function editLotMondal()
    {

        $formData = array(
            'dist_code'   => $this->uri->segment(4),
            'subdiv_code' => $this->uri->segment(5),
            'cir_code'    => $this->uri->segment(6),
            'mouza_pargona_code' => $this->uri->segment(7),
            'lot_no' => $this->uri->segment(8),
            'lm_code' => $this->uri->segment(9),
        );
        $this->dataswitch();
        $checkIfLMExist = $this->UserModel->checkIfLMUserExist($formData);
        if ($checkIfLMExist) {
            $data['page_header'] = 'Edit Lot Mondal';
            $data['base'] = $this->config->item('base_url');
            $data['districts'] = $this->LoginModel->districtdetailsall();
            $data['subdistricts'] = $this->LoginModel->subdivisiondetailsall($formData['dist_code']);
            $data['circles'] = $this->LoginModel->circledetailsall($formData['dist_code'], $formData['subdiv_code']);
            $data['mouzas'] = $this->LoginModel->mouzadetailsall($formData['dist_code'], $formData['subdiv_code'], $formData['cir_code']);
            $data['lots'] = $this->LoginModel->lotdetailsall($formData['dist_code'], $formData['subdiv_code'], $formData['cir_code'], $formData['mouza_pargona_code']);

            $data['sks'] = $this->UserModel->getSKUsers($formData['dist_code'], $formData['subdiv_code'], $formData['cir_code']);

            $data['userDetail'] = $this->UserModel->getLMUserDetail($formData['dist_code'], $formData['subdiv_code'], $formData['cir_code'], $formData['mouza_pargona_code'], $formData['lot_no'], $formData['lm_code']);
            $data['_view'] = 'lm/edit_lot_mondal';
            $this->load->view('layout/layout', $data);
        } else {
            $this->index();
        }
    }

    public function updateLotMondal()
    {
        $this->dataswitch();
        $data = array();
        $this->form_validation->set_rules('dist_code', 'District Name', 'trim|integer|required|strip_tags|xss_clean', array('required' => 'District Name is Required.'));
        $this->form_validation->set_rules('subdiv_code', 'Sub Division Name', 'trim|integer|required|strip_tags|xss_clean', array('required' => 'Sub Division Name is Required.'));
        $this->form_validation->set_rules('cir_code', 'Circle Name', 'trim|integer|required|strip_tags|xss_clean', array('required' => 'Circle Name is Required.'));
        $this->form_validation->set_rules('mouza_pargona_code', 'Mouza Name', 'trim|integer|required|strip_tags|xss_clean', array('required' => 'Mouza Name is Required.'));
        $this->form_validation->set_rules('lot_no', 'Lot Number', 'trim|integer|required|strip_tags|xss_clean', array('required' => 'Lot Number is Required.'));
        $this->form_validation->set_rules('lm_name', 'Lot Mondal Name', 'trim|required|strip_tags|xss_clean|max_length[50]', array('required' => 'Lot Mondal Name is Required.'));
        $this->form_validation->set_rules('status', 'Status', 'required|trim|xss_clean|callback_validateStatus', array('required' => 'Status is Required.'));
        $this->form_validation->set_rules('date_of_joining', 'Date of Joining', 'required|trim|strip_tags|xss_clean', array('required' => 'Date of Joining is Required.'));
        $this->form_validation->set_rules('corres_sk_code', 'SK', 'required|trim|strip_tags|xss_clean', array('required' => 'Supervisor Name is Required.'));
        $this->form_validation->set_rules('phone_no', 'Phone Number', 'trim|required|strip_tags|xss_clean|max_length[10]|integer', array('required' => 'Phone Number is Required.'));
        $this->form_validation->set_message('validateStatus', 'Select Correct Status.');

        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
        } else {

            $dataToBeUpdated = array(
                'lm_name' => $this->input->post('lm_name'),
                'phone_no' => $this->input->post('phone_no'),
                'status' => $this->input->post('status'),
                'dt_from' => $this->input->post('date_of_joining'),
                'corres_sk_code' => $this->input->post('corres_sk_code')
            );

            $conditions = array(
                'dist_code' => $this->input->post('dist_code'),
                'subdiv_code' => $this->input->post('subdiv_code'),
                'cir_code' => $this->input->post('cir_code'),
                'mouza_pargona_code' => $this->input->post('mouza_pargona_code'),
                'lot_no'   => $this->input->post('lot_no'),
                'lm_code' => $this->input->post('lm_code'),
            );
            $this->db->where($conditions);
            $result =  $this->db->update('lm_code', $dataToBeUpdated);
            if ($result) {
                echo json_encode(array('msg' => 'LM Record Updated Successfuly', 'st' => 1));
            } else {
                echo json_encode(array('msg' => 'LotM Record Not Updated. Try Again Later.', 'st' => 0));
            }
        }
    }

    public function validateStatus($status)
    {
        $allowedStatus = array("E", "D", "O", "S");
        if (in_array($status, $allowedStatus)) {
            return true;
        } else {
            return false;
        }
    }

    public function listSKUsers()
    {
        $this->dataswitch();
        $district = $this->input->post('dis');
        $subDivision = $this->input->post('subdiv');
        $circle = $this->input->post('cir');
        $this->session->set_userdata('cir_no', $cir);
        $formdata = $this->UserModel->getSKUsers($district, $subDivision, $circle);
        foreach ($formdata as $value) {
            $data[] = $value;
        }
        echo json_encode($data);
    }

    public function generateLmUserCode($form_data)
    {
        $code = 'LM' . now();
        $query = $this->db->get_where('lm_code', array('lmuser' => $code));
        if ($query->num_rows() > 0) {
            $this->generateLmUserCode($form_data);
        }
        return $code;
    }

    public function insertLm($form_data)
    {
        $dist_code = $form_data['dist_code'];
        $subdiv_code = $form_data['subdiv_code'];
        $circle_code = $form_data['cir_code'];
        $mouza_pargona_code = $form_data['mouza_pargona_code'];
        $sk_code = $form_data['corres_sk_code'];

        for ($i = 1; $i < 9999; $i++) {
            $lm_code = "M" . +$i;
            $existance_lm = $this->db->query("select count(lm_code) as d from lm_code where dist_code = '$dist_code' and subdiv_code = '$subdiv_code' and cir_code = '$circle_code' and mouza_pargona_code = '$mouza_pargona_code' and lm_code = '$lm_code'")->row()->d;
            if ($existance_lm == '0') {
                break;
            }
        }
        $user_code_for_lm = $lm_code;
        $this->db->trans_start();
        $this->db->insert('lm_code', [
            'dist_code' => $form_data['dist_code'],
            'subdiv_code' => $form_data['subdiv_code'] ? $form_data['subdiv_code'] : '00',
            'cir_code' => $form_data['cir_code'] ? $form_data['cir_code'] : '00',
            'mouza_pargona_code' => $form_data['mouza_pargona_code'],
            'lot_no' => $form_data['lot_no'],
            'lm_name' => $form_data['lm_name'],
            'lm_code' => $user_code_for_lm,
            'status' => $form_data['status'],
            'phone_no' => $form_data['phone_no'],
            'corres_sk_code' => $sk_code,
            'dt_from' => $form_data['date_of_joining'],
            'lmuser' => $this->generateLmUserCode($form_data)
        ]);
        $this->db->trans_complete();
        if ($this->db->trans_status()) {
            return true;
        } else {
            return false;
        }
    }
}
