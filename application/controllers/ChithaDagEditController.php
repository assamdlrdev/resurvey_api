<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class ChithaDagEditController extends CI_Controller
{
    use CommonTrait;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('UtilityClass');
        $this->load->helper('security');
        $this->load->model('DagEditModel');
        $this->load->model('Chithamodel');
        $this->load->library('session');
        $this->load->helper(['form', 'url']);
    }

    public function location()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $distcode = $this->session->userdata('dcode');

        $data['districts'] = $this->Chithamodel->districtdetails($distcode);
        $data['_view'] = 'dag_edit/location';

        $this->dataswitch();
        $sql = "select type_code,patta_type from patta_code ";
        $data['patta_types'] = $this->db->query($sql)->result();
        $this->load->view('layout/layout', $data);
    }
    public function insertPattadars()
    {
        $this->dataswitch();
        if (isset($_POST['chk'])) {
            foreach ($_POST['chk'] as $item) {
                $cval = $item;
                $cc = explode(',', $cval);
                $pid = $cc[0];
                $pname = $cc[1];
                $patta = $cc[2];
                $ptype = $cc[3];
                $dag = $cc[4];
                $this->DagEditModel->insertPattadarFromOtherDag($pid, $pname, $patta, $ptype, $dag);
            }
        } else {
            echo json_encode(array('msg' => 'Please select the pattadars', 'st' => 0));
            return;
        }

        echo json_encode(array('msg' => '', 'st' => 1));
    }
    public function deleteExistingPattadar()
    {
        $this->dataswitch();
        $dist_code = $this->session->userdata('dag_dist_code');
        $subdiv_code = $this->session->userdata('dag_subdiv_code');
        $cir_code = $this->session->userdata('dag_cir_code');
        $mouza_pargona_code = $this->session->userdata('dag_mouza_pargona_code');
        $lot_no = $this->session->userdata('dag_lot_no');
        $vill_code = $this->session->userdata('dag_vill_code');
        $patta_type_code = $this->session->userdata('dag_patta_type_code');
        $patta_no = $this->session->userdata('dag_patta_no');
        $new_dag_no = $this->session->userdata('new_dag_no');
        $pdar_id = $this->input->post('pdar_id');

        $where = "(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_code' and trim(patta_no)=trim('$patta_no') and patta_type_code='$patta_type_code' and dag_no='$new_dag_no' and pdar_id='$pdar_id')";
        $query = $this->db->get_where('edited_chitha_dag_pattadar', $where);
        if ($query->num_rows() > 0) {
            $this->db->delete('edited_chitha_dag_pattadar', $where);
        }
        echo json_encode(['msg' => 'Deleted successfully.']);
    }
    function get_filtered_pattadars()
    {
        $this->dataswitch();
        $dag_no = $this->input->post('dag_no') ? $this->input->post('dag_no') : null;
        $patta_no = $this->input->post('patta_no');
        $patta_type_code = $this->input->post('patta_type_code');
        $current_dag_no = $this->input->post('current_dag_no');
        $str = $this->DagEditModel->get_pattadars_by_dags($patta_no, $patta_type_code, $dag_no, $current_dag_no);
        echo json_encode(array('msg' => $str));
    }
    public function getPattaNos()
    {
        $this->dataswitch();
        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $vill_code = $this->input->post('vill_townprt_code');
        $patta_type = $this->input->post('patta_type_code');


        $sql = "select distinct(patta_no) from chitha_basic where 
            dist_code=? and subdiv_code=? and cir_code=? and 
            mouza_pargona_code=? and lot_no=? and vill_townprt_code=? 
            and patta_type_code=?";
        $res = $this->db->query(
            $sql,
            array(
                $dist_code, $subdiv_code, $cir_code,
                $mouza_pargona_code, $lot_no, $vill_code, $patta_type
            )
        );
        $patta_no = null;
        if ($res->num_rows() > 0) {
            $patta_no = $res->result();
        }
        echo json_encode($patta_no);
    }
    public function getDagPattaDetails()
    {
        $this->dataswitch();
        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $vill_code = $this->input->post('vill_townprt_code');
        $dag_no = $this->input->post('dag_no');
        
        $where = "(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_code' and dag_no='$dag_no')";
        $this->db->select('old_dag_no,dag_no,patta_no,patta_type_code');
        $query = $this->db->get_where('chitha_basic', $where);
        $dag = $query->row();

        echo json_encode(['patta_no' => $dag->patta_no,'patta_type_code' => $dag->patta_type_code]);
    }
    public function getDags()
    {
        $this->dataswitch();
        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $vill_code = $this->input->post('vill_townprt_code');
        $patta_type_code = $this->input->post('patta_type_code');
        $patta_no = $this->input->post('patta_no');

        $where = "(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_code')";
        $this->db->select('old_dag_no,dag_no,patta_no,patta_type_code');
        $query = $this->db->order_by('dag_no_int', 'ASC')->get_where('chitha_basic', $where);
        $dags = $query->result();

        echo json_encode($dags);
    }
    public function currentPattadars()
    {

        $this->form_validation->set_rules('dist_code', 'District', 'trim|integer|required');
        $this->form_validation->set_rules('subdiv_code', 'Sub Division', 'trim|integer|required');
        $this->form_validation->set_rules('cir_code', 'Circle', 'trim|integer|required');
        $this->form_validation->set_rules('mouza_pargona_code', 'Mouza', 'trim|integer|required');
        $this->form_validation->set_rules('lot_no', 'Lot', 'trim|integer|required');
        $this->form_validation->set_rules('vill_townprt_code', 'Village', 'trim|integer|required');
        $this->form_validation->set_rules('patta_type_code', 'Patta Type', 'trim|max_length[4]|numeric|required');
        $this->form_validation->set_rules('patta_no', 'Patta No', 'trim|required');
        $this->form_validation->set_rules('dag_no', 'Dag No', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Required parameters are empty');
            redirect(base_url('index.php/ChithaDagEditController/location'));
            return;
        }

        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->input->post('subdiv_code', true);
        $cir_code = $this->input->post('cir_code', true);
        $mouza_pargona_code = $this->input->post('mouza_pargona_code', true);
        $lot_no = $this->input->post('lot_no', true);
        $vill_code = $this->input->post('vill_townprt_code', true);
        $patta_type_code = $this->input->post('patta_type_code', true);
        $patta_no = $this->input->post('patta_no', true);
        $dag_no = $this->input->post('dag_no', true);

        $this->dataswitch();

        $this->session->set_userdata('dag_dist_code', $dist_code);
        $this->session->set_userdata('dag_subdiv_code', $subdiv_code);
        $this->session->set_userdata('dag_cir_code', $cir_code);
        $this->session->set_userdata('dag_mouza_pargona_code', $mouza_pargona_code);
        $this->session->set_userdata('dag_lot_no', $lot_no);
        $this->session->set_userdata('dag_vill_code', $vill_code);
        $this->session->set_userdata('dag_patta_type_code', $patta_type_code);
        $this->session->set_userdata('dag_patta_no', $patta_no);
        $this->session->set_userdata('dag_dag_no', $dag_no);

        $sql = "select cp.pdar_name,cp.pdar_father,cp.pdar_mother,cp.pdar_id,cp.subdiv_code,cp.dist_code,cp.cir_code,cp.mouza_pargona_code,cp.lot_no,cp.vill_townprt_code,cp.patta_no from chitha_pattadar cp  join chitha_dag_pattadar cdp on cp.dist_code=cdp.dist_code  and cp.subdiv_code=cdp.subdiv_code and cp.cir_code=cdp.cir_code 
	            and cp.mouza_pargona_code=cdp.mouza_pargona_code and cp.lot_no=cdp.lot_no and cp.vill_townprt_code=cdp.vill_townprt_code and cp.patta_type_code=cdp.patta_type_code and trim(cp.patta_no)=trim(cdp.patta_no) and cdp.pdar_id=cp.pdar_id where dag_no='$dag_no' and trim(cp.patta_no)=trim('$patta_no') and cp.pdar_id=cdp.pdar_id and cp.patta_type_code='$patta_type_code' and cp.dist_code='$dist_code' and cp.subdiv_code='$subdiv_code' and cp.cir_code='$cir_code' and cp.mouza_pargona_code='$mouza_pargona_code' and cp.lot_no='$lot_no' and cp.vill_townprt_code='$vill_code'";
        $pattadars = $this->db->query($sql)->result();

        $data['pattadars'] = $pattadars;

        $data['dist_code'] = $dist_code;
        $data['subdiv_code'] = $subdiv_code;
        $data['cir_code'] = $cir_code;
        $data['mouza_pargona_code'] = $mouza_pargona_code;
        $data['lot_no'] = $lot_no;
        $data['vill_code'] = $vill_code;
        $data['patta_type_code'] = $patta_type_code;
        $data['patta_no'] = $patta_no;
        $data['dag_no'] = $dag_no;

        $data['_view'] = 'dag_edit/pattadars_current';
        $this->load->view('layout/layout', $data);
    }
    public function viewPattadars()
    {
        $this->dataswitch();
        $dist_code = $this->session->userdata('dag_dist_code');
        $subdiv_code = $this->session->userdata('dag_subdiv_code');
        $cir_code = $this->session->userdata('dag_cir_code');
        $mouza_pargona_code = $this->session->userdata('dag_mouza_pargona_code');
        $lot_no = $this->session->userdata('dag_lot_no');
        $vill_code = $this->session->userdata('dag_vill_code');
        $patta_type_code = $this->session->userdata('dag_patta_type_code');
        $new_patta_type_code = $this->session->userdata('new_patta_type_code');
        $patta_no = $this->session->userdata('dag_patta_no');
        $new_patta_no = $this->session->userdata('new_patta_no');
        $dag_no = $this->session->userdata('dag_dag_no');
        $new_dag_no = $this->session->userdata('new_dag_no');
        $where = "(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_code' and trim(old_patta_no)=trim('$patta_no') and old_patta_type_code='$patta_type_code' and dag_no='$new_dag_no')";
        $query = $this->db->get_where('edited_chitha_basic',$where);

        //echo $query->num_rows(); die();
        if($query->num_rows() == 0){
            show_404();
            return;
        }

        $sql = "select cp.pdar_name,cp.pdar_father,cp.pdar_mother,cp.pdar_id,cp.subdiv_code,cp.dist_code,cp.cir_code,cp.mouza_pargona_code,cp.lot_no,cp.vill_townprt_code,cp.patta_no,cdp.dag_no from edited_chitha_pattadar cp  join edited_chitha_dag_pattadar cdp on cp.dist_code=cdp.dist_code  and cp.subdiv_code=cdp.subdiv_code and cp.cir_code=cdp.cir_code 
	            and cp.mouza_pargona_code=cdp.mouza_pargona_code and cp.lot_no=cdp.lot_no and cp.vill_townprt_code=cdp.vill_townprt_code and cp.patta_type_code=cdp.patta_type_code and trim(cp.patta_no)=trim(cdp.patta_no) and cdp.pdar_id=cp.pdar_id where dag_no='$new_dag_no' and trim(cp.patta_no)=trim('$patta_no') and cp.pdar_id=cdp.pdar_id and cp.patta_type_code='$patta_type_code' and cp.dist_code='$dist_code' and cp.subdiv_code='$subdiv_code' and cp.cir_code='$cir_code' and cp.mouza_pargona_code='$mouza_pargona_code' and cp.lot_no='$lot_no' and cp.vill_townprt_code='$vill_code' and cdp.p_flag!='1'";
        $pattadars = $this->db->query($sql)->result();

        //print_r($pattadars); die();
        
        $other_pattadars_all = $this->DagEditModel->other_pattadars($patta_no, $patta_type_code, $dag_no);

        // print_r($other_pattadars_all); die();
        $other_pattadars = [];
        if($other_pattadars_all){
            foreach ($other_pattadars_all as $other_pattdar) {
                $is_exists = false;
                foreach ($pattadars as $pattadar) {
                    if ($other_pattdar->dag_no == $pattadar->dag_no && $other_pattdar->pdar_id == $pattadar->pdar_id) {
                        $is_exists = true;
                    }
                }
                if (!$is_exists) {
                    $p['pdar_id'] = $other_pattdar->pdar_id;
                    $p['pdar_name'] = $other_pattdar->pdar_name;
                    $p['dag_no'] = $other_pattdar->dag_no;
                    $p['patta_no'] = $other_pattdar->patta_no;
                    $p['patta_type_code'] = $other_pattdar->patta_type_code;
                    $other_pattadars[] = $p;
                }
            }
        }        

        $other_pattadars_edited = $this->DagEditModel->other_pattadars_edited($patta_no, $patta_type_code, $dag_no);
        if ($other_pattadars_edited) {
            foreach ($other_pattadars_edited as $other_pattdar) {
                $is_exists = false;
                foreach ($pattadars as $pattadar) {
                    if ($other_pattdar->dag_no == $pattadar->dag_no && $other_pattdar->pdar_id == $pattadar->pdar_id) {
                        $is_exists = true;
                    }
                }
                foreach ($other_pattadars_all as $pattadar) {
                    if ($other_pattdar->dag_no == $pattadar->dag_no && $other_pattdar->pdar_id == $pattadar->pdar_id) {
                        $is_exists = true;
                    }
                }
                if (!$is_exists) {
                    $p['pdar_id'] = $other_pattdar->pdar_id;
                    $p['pdar_name'] = $other_pattdar->pdar_name;
                    $p['dag_no'] = $other_pattdar->dag_no;
                    $p['patta_no'] = $other_pattdar->patta_no;
                    $p['patta_type_code'] = $other_pattdar->patta_type_code;
                    $other_pattadars[] = $p;
                }
            }
        }
        $data['other_dags'] = $this->DagEditModel->other_dags($patta_no, $patta_type_code, $dag_no);
        $data['other_pattadars'] = $other_pattadars;
        $data['base'] = $this->config->item('base_url');
        $data['pattadars'] = $pattadars;
        $data['dist_code'] = $dist_code;
        $data['subdiv_code'] = $subdiv_code;
        $data['cir_code'] = $cir_code;
        $data['mouza_pargona_code'] = $mouza_pargona_code;
        $data['lot_no'] = $lot_no;
        $data['vill_code'] = $vill_code;
        $data['patta_type_code'] = $patta_type_code;
        $data['new_patta_type_code'] = $new_patta_type_code;
        $data['patta_no'] = $patta_no;
        $data['new_patta_no'] = $new_patta_no;
        $data['dag_no'] = $dag_no;
        $data['new_dag_no'] = $new_dag_no;
        $data['relname'] = $this->Chithamodel->relation();
        $data['_view'] = 'dag_edit/pattadars';
        $this->load->view('layout/layout', $data);
    }
    public function dagDetails()
    {
        $this->dataswitch();
        $dist_code = $this->session->userdata('dag_dist_code');
        $subdiv_code = $this->session->userdata('dag_subdiv_code');
        $cir_code = $this->session->userdata('dag_cir_code');
        $mouza_pargona_code = $this->session->userdata('dag_mouza_pargona_code');
        $lot_no = $this->session->userdata('dag_lot_no');
        $vill_code = $this->session->userdata('dag_vill_code');
        $patta_type_code = $this->session->userdata('dag_patta_type_code');
        $patta_no = $this->session->userdata('dag_patta_no');
        $dag_no = $this->session->userdata('dag_dag_no');

        $where = "(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_code' and patta_type_code='$patta_type_code' and patta_no='$patta_no' and dag_no='$dag_no')";
        $this->db->select('dag_area_b,dag_area_k,dag_area_lc,dag_area_g,dag_area_are,dag_revenue,dag_local_tax,land_class_code,old_dag_no,dag_no,patta_no,patta_type_code,dag_nlrg_no,dp_flag_yn,dag_area_are,dag_n_desc,dag_s_desc,dag_e_desc,dag_w_desc,dag_n_dag_no,dag_s_dag_no,dag_e_dag_no,dag_w_dag_no');
        $query = $this->db->get_where('chitha_basic', $where);
        $dag = $query->row();
        if (!$dag) {
            show_404();
            return;
        }
        $data['dag'] = $dag;
        $data['base'] = $this->config->item('base_url');
        $data['pattype'] = $this->Chithamodel->getPattaType();
        $data['pcode'] = $this->input->post('patta_type_code');
        $data['lclass'] = $this->Chithamodel->getLandclasscode();
        $data['lcode'] = $this->input->post('land_class_code');
        $data['_view'] = 'dag_edit/dag_details';
        $this->load->view('layout/layout', $data);
    }
    public function editExistingDag($new_dag_no)
    {
        $this->dataswitch();
        $this->session->set_userdata('new_dag_no', $new_dag_no);

        $dist_code = $this->session->userdata('dag_dist_code');
        $subdiv_code = $this->session->userdata('dag_subdiv_code');
        $cir_code = $this->session->userdata('dag_cir_code');
        $mouza_pargona_code = $this->session->userdata('dag_mouza_pargona_code');
        $lot_no = $this->session->userdata('dag_lot_no');
        $vill_code = $this->session->userdata('dag_vill_code');
        $patta_type_code = $this->session->userdata('dag_patta_type_code');
        $patta_no = $this->session->userdata('dag_patta_no');
        $dag_no = $this->session->userdata('dag_dag_no');

        $where = "(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_code' and dag_no='$new_dag_no')";
        $this->db->select('dag_area_b,dag_area_k,dag_area_lc,dag_area_g,dag_area_are,dag_revenue,dag_local_tax,land_class_code,old_dag_no,dag_no,patta_no,patta_type_code,dag_nlrg_no,dp_flag_yn,dag_area_are,dag_n_desc,dag_s_desc,dag_e_desc,dag_w_desc,dag_n_dag_no,dag_s_dag_no,dag_e_dag_no,dag_w_dag_no');
        $query = $this->db->get_where('edited_chitha_basic', $where);
        $dag = $query->row();
        if (!$dag) {
            show_404();
            return;
        }
        $data['dag'] = $dag;
        $data['dag_no'] = $dag_no;
        $data['base'] = $this->config->item('base_url');
        $data['pattype'] = $this->Chithamodel->getPattaType();
        $data['pcode'] = $this->input->post('patta_type_code');
        $data['lclass'] = $this->Chithamodel->getLandclasscode();
        $data['lcode'] = $this->input->post('land_class_code');
        $data['_view'] = 'dag_edit/dag_details_edit';
        $this->load->view('layout/layout', $data);
    }
    public function getNewPattadarId()
    {
        $this->dataswitch();
        $pattadar_id = $this->DagEditModel->checkpattadarid();
        echo json_encode($pattadar_id);
    }
    public function updatePattadar()
    {
        $this->dataswitch();
        $this->form_validation->set_rules('pdar_name', 'Pattadar Name', 'trim|required|max_length[50]');
        if (!in_array($this->input->post('patta_type_code'), GovtPattaCode)) {
            $this->form_validation->set_rules('pdar_father', 'Guardian Name', 'trim|required|max_length[50]');
            $this->form_validation->set_rules('pdar_relation', 'Guardian Relation', 'trim|required|max_length[50]');
            $this->form_validation->set_rules('p_flag', 'Pattadar Stricked Out', 'trim|required|max_length[1]');
            $this->form_validation->set_rules('dag_por_b', 'Dag Area Bigha ', 'trim|required|numeric');
            if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
                $this->form_validation->set_rules('dag_por_k', 'Dag Area Katha ', 'callback_kathavalue|trim|required|numeric');
                $this->form_validation->set_rules('dag_por_lc', 'Dag Area Chatak ', 'callback_chatakvalue|trim|required|numeric');
                $this->form_validation->set_rules('dag_por_g', 'Dag Area Ganda ', 'callback_gandavalue|trim|required|numeric');
            } else {
                $this->form_validation->set_rules('dag_por_k', 'Dag Area Katha ', 'callback_kathavalue|trim|required|numeric');
                $this->form_validation->set_rules('dag_por_lc', 'Dag Area Lessa ', 'callback_lessavalue|trim|required|numeric');
            }
        }
        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
            return;
        } else {
            $nrows = $this->DagEditModel->updatepattadar();
            if ($nrows) {
                echo json_encode(array('msg' => 'Data modified for Pattadar', 'st' => 1));
            } else {
                echo json_encode(array('msg' => 'Error in pattadar entry', 'st' => 0));
            }
        }
    }
    public function storePattadar()
    {
        $this->dataswitch();
        $this->form_validation->set_rules('pdar_name', 'Pattadar Name', 'trim|required|max_length[50]');
        if (!in_array($this->input->post('patta_type_code'), GovtPattaCode)) {
            $this->form_validation->set_rules('pdar_father', 'Guardian Name', 'trim|required|max_length[50]');
            $this->form_validation->set_rules('pdar_relation', 'Guardian Relation', 'trim|required|max_length[50]');
            $this->form_validation->set_rules('p_flag', 'Pattadar Stricked Out', 'trim|required|max_length[1]');
            $this->form_validation->set_rules('dag_por_b', 'Dag Area Bigha ', 'trim|required|numeric');

            if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
                $this->form_validation->set_rules('dag_por_k', 'Dag Area Katha ', 'callback_kathavalue|trim|required|numeric');
                $this->form_validation->set_rules('dag_por_lc', 'Dag Area Chatak ', 'callback_chatakvalue|trim|required|numeric');
                $this->form_validation->set_rules('dag_por_g', 'Dag Area Ganda ', 'callback_gandavalue|trim|required|numeric');
            } else {
                $this->form_validation->set_rules('dag_por_k', 'Dag Area Katha ', 'callback_kathavalue|trim|required|numeric');
                $this->form_validation->set_rules('dag_por_lc', 'Dag Area Lessa ', 'callback_lessavalue|trim|required|numeric');
            }
        }

        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
            return;
        } else {
            $nrows = $this->DagEditModel->insertpattadar();
            if ($nrows) {
                echo json_encode(array('msg' => 'Data saved for Pattadar', 'st' => 1));
            } else {
                echo json_encode(array('msg' => 'Error in pattadar entry', 'st' => 0));
            }
        }
    }
    public function isDagExists()
    {
        $this->dataswitch();
        $dist_code = $this->session->userdata('dag_dist_code');
        $subdiv_code = $this->session->userdata('dag_subdiv_code');
        $cir_code = $this->session->userdata('dag_cir_code');
        $mouza_pargona_code = $this->session->userdata('dag_mouza_pargona_code');
        $lot_no = $this->session->userdata('dag_lot_no');
        $vill_code = $this->session->userdata('dag_vill_code');
        $patta_type_code = $this->session->userdata('dag_patta_type_code');
        $patta_no = $this->session->userdata('dag_patta_no');
        $dag_no = $this->input->post('new_dag_no');

        $where = "(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_code' and dag_no='$dag_no')";
        $this->db->select('dag_area_b,dag_area_k,dag_area_lc,dag_area_g,dag_area_are,dag_revenue,dag_local_tax,land_class_code,old_dag_no,dag_no,patta_no,patta_type_code,dag_nlrg_no,dp_flag_yn,dag_area_are,dag_n_desc,dag_s_desc,dag_e_desc,dag_w_desc,dag_n_dag_no,dag_s_dag_no,dag_e_dag_no,dag_w_dag_no');
        $query = $this->db->get_where('edited_chitha_basic', $where);
        $count = $query->num_rows();
        if ($count > 0) {
            echo json_encode(['st' => '1']);
        } else {
            echo json_encode(['st' => '0']);
        }
    }
    
    public function updateDag()
    {
        $this->dataswitch();
		
		$this->session->set_userdata('new_patta_type_code', $_POST['patta_type_code']);
        $this->session->set_userdata('new_patta_no', $_POST['patta_no']);

        $this->form_validation->set_rules('dag_no', 'Dag Number', 'callback_validvalue|trim|integer|required');
        $this->form_validation->set_rules('patta_no', 'Patta Number', 'callback_validvalue|trim|integer|required');
        $this->form_validation->set_rules('patta_type_code', 'Patta Type', 'trim|required');
        $this->form_validation->set_rules('land_class_code', 'Land Class', 'trim|required|max_length[4]|min_length[1]');
        $this->form_validation->set_rules('dag_land_revenue', 'Dag Land Revenue', 'trim|required|max_length[10]|numeric');
        $this->form_validation->set_rules('dag_local_tax', 'Dag Local Tax', 'trim|required|max_length[10]|numeric');
        $this->form_validation->set_rules('dag_area_b', 'Dag Area Bigha ', 'trim|required|numeric');
        $this->form_validation->set_rules('dag_area_r', 'Dag Area In Are ', 'callback_dagarea|trim|required|numeric');
        if (($this->session->userdata('dag_dist_code') == '21') || ($this->session->userdata('dag_dist_code') == '22') || ($this->session->userdata('dag_dist_code') == '23')) {
            $this->form_validation->set_rules('dag_area_k', 'Dag Area Katha ', 'callback_kathavalue|trim|required|numeric');
            $this->form_validation->set_rules('dag_area_lc', 'Dag Area Chetak ', 'callback_chatakvalue|trim|required|numeric');
            $this->form_validation->set_rules('dag_area_g', 'Dag Area Ganda ', 'callback_gandavalue|trim|required|numeric');
        } else {
            $this->form_validation->set_rules('dag_area_k', 'Dag Area Katha ', 'callback_kathavalue|trim|required|numeric');
            $this->form_validation->set_rules('dag_area_lc', 'Dag Area Lessa ', 'callback_lessavalue|trim|required|numeric');
        }
        if ($this->form_validation->run() == false) {
            $this->redirectDagDetails();
        } else {
            $this->dataswitch();

            $nrows = $this->DagEditModel->updateDag();
            // print_r($nrows); die();
            if ($nrows) {
                $this->session->set_flashdata('success', 'Dag updated successfully.');
                redirect('ChithaDagEditController/viewPattadars');
            } else {
                $this->session->set_flashdata('error', 'Something went wrong. Please try again later.');
                $this->redirectDagDetails();
            }
        }
    }
    public function updateExistingDag()
    {
        $this->dataswitch();

        $this->form_validation->set_rules('new_dag_no', 'Dag Number', 'callback_validvalue|trim|integer|required');
        $this->form_validation->set_rules('land_class_code', 'Land Class', 'trim|required|max_length[4]|min_length[1]');
        $this->form_validation->set_rules('dag_land_revenue', 'Dag Land Revenue', 'trim|required|max_length[10]|numeric');
        $this->form_validation->set_rules('dag_local_tax', 'Dag Local Tax', 'trim|required|max_length[10]|numeric');
        $this->form_validation->set_rules('dag_area_b', 'Dag Area Bigha ', 'trim|required|numeric');
        $this->form_validation->set_rules('dag_area_r', 'Dag Area In Are ', 'callback_dagarea|trim|required|numeric');
        if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
            $this->form_validation->set_rules('dag_area_k', 'Dag Area Katha ', 'callback_kathavalue|trim|required|numeric');
            $this->form_validation->set_rules('dag_area_lc', 'Dag Area Chetak ', 'callback_chatakvalue|trim|required|numeric');
            $this->form_validation->set_rules('dag_area_g', 'Dag Area Ganda ', 'callback_gandavalue|trim|required|numeric');
        } else {
            $this->form_validation->set_rules('dag_area_k', 'Dag Area Katha ', 'callback_kathavalue|trim|required|numeric');
            $this->form_validation->set_rules('dag_area_lc', 'Dag Area Lessa ', 'callback_lessavalue|trim|required|numeric');
        }
        if ($this->form_validation->run() == false) {
            $this->editExistingDag($this->session->userdata('new_dag_no'));
        } else {
            $this->dataswitch();

            $nrows = $this->DagEditModel->updateExistingDag();
            if ($nrows) {
                $this->session->set_flashdata('success', 'Dag updated successfully.');
                redirect('ChithaDagEditController/viewPattadars');
            } else {
                $this->session->set_flashdata('error', 'Something went wrong. Please try again later.');
                $this->redirectDagDetails();
            }
        }
    }
    public function dagarea()
    {
        $this->dataswitch();
        $dag_area = $this->DagEditModel->getDagArea();
        if ($dag_area['area'] == 0) {
            $this->form_validation->set_message('dagarea', 'Dag area is 0. No further split is possible.');
            return false;
        }
        $dag_area_submitted = $this->DagEditModel->getSubmittedDagArea();
        if ($dag_area_submitted > $dag_area) {
            $this->form_validation->set_message('dagarea', 'Dag area provided ' . $dag_area_submitted . ' exceeding the original dag area of ' . $dag_area);
            return false;
        } else {
            return true;
        }
    }
    public function redirectDagDetails()
    {
        $this->dataswitch();
        $dist_code = $this->session->userdata('dag_dist_code');
        $subdiv_code = $this->session->userdata('dag_subdiv_code');
        $cir_code = $this->session->userdata('dag_cir_code');
        $mouza_pargona_code = $this->session->userdata('dag_mouza_pargona_code');
        $lot_no = $this->session->userdata('dag_lot_no');
        $vill_code = $this->session->userdata('dag_vill_code');
        $patta_type_code = $this->session->userdata('dag_patta_type_code');
        $patta_no = $this->session->userdata('dag_patta_no');
        $dag_no = $this->session->userdata('dag_dag_no');

        $where = "(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_code' and patta_type_code='$patta_type_code' and patta_no='$patta_no' and dag_no='$dag_no')";
        $this->db->select('dag_area_b,dag_area_k,dag_area_lc,dag_area_g,dag_area_are,dag_revenue,dag_local_tax,land_class_code,old_dag_no,dag_no,patta_no,patta_type_code,dag_nlrg_no,dp_flag_yn,dag_area_are,dag_n_desc,dag_s_desc,dag_e_desc,dag_w_desc,dag_n_dag_no,dag_s_dag_no,dag_e_dag_no,dag_w_dag_no');
        $query = $this->db->get_where('chitha_basic', $where);
        $dag = $query->row();
        if (!$dag) {
            show_404();
            return;
        }
        $data['dag'] = $dag;
        $data['base'] = $this->config->item('base_url');
        $data['pattype'] = $this->Chithamodel->getPattaType();
        $data['pcode'] = $this->input->post('patta_type_code');
        $data['lclass'] = $this->Chithamodel->getLandclasscode();
        $data['lcode'] = $this->input->post('land_class_code');
        $data['_view'] = 'dag_edit/dag_details';
        $this->load->view('layout/layout', $data);
    }
    // Added New Change 13-01-2023 S
    public function validvalue($str)
    {
        $patta_type_code = $this->session->userdata('dag_patta_type_code');
        if ($str != '') {
            $result = substr($str, 0, 1);
            if ($result != '0') {
                return true;
            } else if ($result == '0' && $patta_type_code == '0209' || $patta_type_code == '0212' || $patta_type_code == '0213' || $patta_type_code == '0214') {
                return true;
            }else if($result == '0' && $patta_type_code != '0209' || $patta_type_code != '0212' || $patta_type_code != '0213' || $patta_type_code != '0214'){
               $this->form_validation->set_message('validvalue', 'The %s field cannot start with zero');
                return FALSE; 
            }
        }
    }
    // Added New Change 13-01-2023 E
    public function kathavalue($str)
    {
        if ($str != '') {
            if (($this->session->userdata('dag_dist_code') == '21') || ($this->session->userdata('dag_dist_code') == '22') || ($this->session->userdata('dag_dist_code') == '23')) {
                if ($str < 20) {
                    return true;
                } else if ($str >= 20) {
                    $this->form_validation->set_message('kathavalue', 'The %s field cannot be more than 20');
                    return FALSE;
                }
            } else {
                if ($str < 5) {
                    return true;
                } else if ($str >= 5) {
                    $this->form_validation->set_message('kathavalue', 'The %s field cannot be more than 4');
                    return FALSE;
                }
            }
        }
    }
    public function chatakvalue($str)
    {
        if ($str != '') {
            if ($str < 16) {
                return true;
            } else if ($str >= 16) {
                $this->form_validation->set_message('chatakvalue', 'The %s field cannot be more than 16');
                return FALSE;
            }
        }
    }
    public function gandavalue($str)
    {
        if ($str != '') {
            if ($str < 20) {
                return true;
            } else if ($str >= 20) {
                $this->form_validation->set_message('gandavalue', 'The %s field cannot be more than 20');
                return FALSE;
            }
        }
    }
    public function lessavalue($str)
    {
        if ($str != '') {
            if ($str < 20) {
                return true;
            } else if ($str >= 20) {
                $this->form_validation->set_message('lessavalue', 'The %s field cannot be more than 19');
                return FALSE;
            }
        }
    }
    public function editPattadarGetDetails()
    {
        $this->dataswitch();
        $patta_type_code = $this->input->post('patta_type_code');
        $patta_no = $this->input->post('patta_no');
        $dag_no = $this->session->userdata('new_dag_no');
        $pdar_id = $this->input->post('pdar_id');

        $data['patta_type_name'] = $this->Chithamodel->patta_type_name($patta_type_code);
        $pattadar = $this->DagEditModel->pattadarexispid($pdar_id, $patta_no, $patta_type_code, $dag_no);
        if ($pattadar) {
            echo json_encode($pattadar);
        } else {
            echo json_encode('not found');
        }
    }
}
