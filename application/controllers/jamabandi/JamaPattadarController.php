<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';
class JamaPattadarController extends CI_Controller
{
    use CommonTrait;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('JamabandiModel');
        $this->load->model('Chithamodel');
        $this->load->helper('security');
        $this->load->helper(['form', 'url']);
    }
    public function locationForm()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $distcode = $this->session->userdata('dcode');

        $data['districts'] = $this->Chithamodel->districtdetails($distcode);
        if ($this->session->userdata('svill_townprt_code') and $this->session->userdata('current_url') == current_url()) {
            $dist = $this->session->userdata('sdcode');
            $subdiv = $this->session->userdata('ssubdiv_code');
            $circle = (string) $this->session->userdata('scir_code');
            $mza = (string) $this->session->userdata('smouza_pargona_code');
            $lot = (string) $this->session->userdata('slot_no');
            $vill = (string) $this->session->userdata('svill_townprt_code');
            $currentURL = (string) $this->session->userdata('current_url');

            $data['locations'] = $this->Chithamodel->getSessionLoc($dist, $subdiv, $circle, $mza, $lot, $vill);
            $data['current_url'] = $currentURL;
            $data['patta_no'] = $patta_no = $this->session->userdata('patta_no');
            $data['patta_nos'] = $this->getReqPattaNos($dist, $subdiv, $circle, $mza, $lot, $vill);
            $data['patta_type'] = $this->session->userdata('patta_type_code');
            $data['patta_types'] = $this->getReqJamabandiPattaTypes($dist, $subdiv, $circle, $mza, $lot, $vill, $patta_no);
            //dd($data['patta_types']);
        } else {
            $data['locations'] = null;
            $data['current_url'] = null;
            $data['patta_no'] = null;
            $data["patta_nos"] = null;
            $data["patta_type"] = null;
            $data["patta_types"] = null;
        }
        $data['_view'] = 'jama_pattadar/set_location';

        $this->load->view('layout/layout', $data);
    }

    public function getJamabandiPattadars()
    {
        $this->form_validation->set_rules('dist_code', 'District Name', 'trim|integer|required');
        $this->form_validation->set_rules('subdiv_code', 'Sub Division Name', 'trim|integer|required');
        $this->form_validation->set_rules('cir_code', 'Circle Name', 'trim|integer|required');
        $this->form_validation->set_rules('mouza_pargona_code', 'Mouza Name', 'trim|integer|required');
        $this->form_validation->set_rules('lot_no', 'Lot Number', 'trim|integer|required');
        $this->form_validation->set_rules('vill_townprt_code', 'Village Name', 'trim|integer|required');
        $this->form_validation->set_rules('patta_no', 'Patta No', 'trim|integer|required');
        $this->form_validation->set_rules('patta_type', 'Patta Type', 'trim|integer|required');
        if ($this->form_validation->run() == false) {
            $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
            $this->locationForm();
            return;
        } else {
            $this->dataswitch();
            $dist_code = $this->input->post('dist_code');
            $subdiv_code = $this->input->post('subdiv_code');
            $cir_code = $this->input->post('cir_code');
            $mouza_pargona_code = $this->input->post('mouza_pargona_code');
            $lot_no = $this->input->post('lot_no');
            $vill_townprt_code = $this->input->post('vill_townprt_code');
            $patta_no = $this->input->post('patta_no');
            $patta_type_code = $this->input->post('patta_type');
            $jama_dets = [
                'dist_code' => $dist_code,
                'subdiv_code' => $subdiv_code,
                'cir_code' => $cir_code,
                'mouza_pargona_code' => $mouza_pargona_code,
                'lot_no' => $lot_no,
                'vill_townprt_code' => $vill_townprt_code,
                'patta_no' => $patta_no,
                'patta_type_code' => $patta_type_code,
            ];

            $this->load->library('user_agent');
            $vill = $this->input->post('vill_townprt_code');
            $this->session->set_userdata('vill_townprt_code', $vill);
            $this->session->set_userdata('current_url', $this->agent->referrer());
            $this->session->set_userdata('sdcode', $dist_code);
            $this->session->set_userdata('ssubdiv_code', $subdiv_code);
            $this->session->set_userdata('scir_code', $cir_code);
            $this->session->set_userdata('smouza_pargona_code', $mouza_pargona_code);
            $this->session->set_userdata('slot_no', $lot_no);
            $this->session->set_userdata('svill_townprt_code', $vill_townprt_code);
            $this->session->set_userdata('patta_no', $patta_no);
            $this->session->set_userdata('patta_type_code', $patta_type_code);

            $this->session->set_userdata(array('jama_dets' => $jama_dets));

            $query = "select pdar_id,pdar_name,pdar_father,pdar_sl_no from jama_pattadar
        where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code'
        and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code'
        and patta_no='$patta_no' and patta_type_code='$patta_type_code'";
            $locationname = $this->Chithamodel->getlocationnames($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code);
            $locationname['patta_no'] = $patta_no;
            $pattadars = $this->db->query($query)->result();

            $query2 = "select patta_type from patta_code where type_code = '$patta_type_code'";
            $patta_type = $this->db->query($query2)->row();

            $locationname['patta_type'] = $patta_type->patta_type;

            $data['pattadars'] = $pattadars;
            $data['locationname'] = $locationname;
            $data['base'] = $this->config->item('base_url');
            $data['_view'] = 'jama_pattadar/jama_pattadars';
            $this->load->view('layout/layout', $data);
        }
    }
    public function getJamabandiPattadarsView()
    {
        $this->dataswitch();
        $jama_dets = $this->session->userdata('jama_dets');
        $dist_code = $jama_dets['dist_code'];
        $subdiv_code = $jama_dets['subdiv_code'];
        $cir_code = $jama_dets['cir_code'];
        $mouza_pargona_code = $jama_dets['mouza_pargona_code'];
        $lot_no = $jama_dets['lot_no'];
        $vill_townprt_code = $jama_dets['vill_townprt_code'];
        $patta_no = $jama_dets['patta_no'];
        $patta_type_code = $jama_dets['patta_type_code'];

        $query = "select pdar_id,pdar_name,pdar_father,pdar_sl_no from jama_pattadar
        where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code'
        and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code'
        and patta_no='$patta_no' and patta_type_code='$patta_type_code'";
        $locationname = $this->Chithamodel->getlocationnames($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code);
        $locationname['patta_no'] = $patta_no;
        $pattadars = $this->db->query($query)->result();

        $query2 = "select patta_type from patta_code where type_code = '$patta_type_code'";
        $patta_type = $this->db->query($query2)->row();

        $locationname['patta_type'] = $patta_type->patta_type;

        $data['pattadars'] = $pattadars;
        $data['locationname'] = $locationname;
        $data['base'] = $this->config->item('base_url');
        $data['_view'] = 'jama_pattadar/jama_pattadars';
        $this->load->view('layout/layout', $data);
    }
    public function updateJamapattadar()
    {
        $this->dataswitch();
        $jama_dets = $this->session->userdata('jama_dets');
        $dist_code = $jama_dets['dist_code'];
        $subdiv_code = $jama_dets['subdiv_code'];
        $cir_code = $jama_dets['cir_code'];
        $mouza_pargona_code = $jama_dets['mouza_pargona_code'];
        $lot_no = $jama_dets['lot_no'];
        $vill_townprt_code = $jama_dets['vill_townprt_code'];
        $patta_no = $jama_dets['patta_no'];
        $patta_type_code = $jama_dets['patta_type_code'];
        $serial_no = $this->input->post('serial_no');
        $pdar_id = $this->input->post('pdar_id');
        $this->db->trans_start();
        $this->db->where([
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'mouza_pargona_code' => $mouza_pargona_code,
            'lot_no' => $lot_no,
            'vill_townprt_code' => $vill_townprt_code,
            'patta_no' => $patta_no,
            'patta_type_code' => $patta_type_code,
            'pdar_id' => $pdar_id,
        ]);
        $this->db->update('jama_pattadar', ['pdar_sl_no' => $serial_no]);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            echo json_encode(array('msg' => 'Something went wrong. Please try again later.', 'st' => 0));
            return;
        } else {
            echo json_encode(array('msg' => 'Updated successfully', 'st' => 1));
            return;
        }
    }
    public function getJamabandiPattaTypes()
    {
        $this->dataswitch();
        $dist_code = $this->input->post('dist_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $circle_code = $this->input->post('cir_code');
        $mouza_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $vill_code = $this->input->post('vill_townprt_code');
        $patta_no = $this->input->post('patta_no');

        $query = "select patta_type,type_code from chitha_basic,patta_code where chitha_basic.patta_type_code"
            . " = patta_code.type_code and TRIM(chitha_basic.patta_no)=trim('$patta_no') and"
            . " dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$circle_code' and"
            . " mouza_pargona_code='$mouza_code' and lot_no='$lot_no' and vill_townprt_code='$vill_code' group by type_code";

        $type = $this->db->query($query)->result();
        echo json_encode(array('msg' => array_unique($type), 'st' => 1));
    }

    public function getReqJamabandiPattaTypes($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code, $patta_no)
    {
        $this->dataswitch();
        $query = "select patta_type,type_code from chitha_basic,patta_code where chitha_basic.patta_type_code"
            . " = patta_code.type_code and TRIM(chitha_basic.patta_no)=trim('$patta_no') and"
            . " dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$circle_code' and"
            . " mouza_pargona_code='$mouza_code' and lot_no='$lot_no' and vill_townprt_code='$vill_code' group by type_code";

        $type = $this->db->query($query)->result();
        return $type;
    }

    public function getPattaNos()
    {
        $this->dataswitch();
        $data = [];
        $this->form_validation->set_rules('dist_code', 'District Name', 'trim|integer|required');
        $this->form_validation->set_rules('subdiv_code', 'Sub Division Name', 'trim|integer|required');
        $this->form_validation->set_rules('cir_code', 'Circle Name', 'trim|integer|required');
        $this->form_validation->set_rules('mouza_pargona_code', 'Mouza Name', 'trim|integer|required');
        $this->form_validation->set_rules('lot_no', 'Lot Number', 'trim|integer|required');
        $this->form_validation->set_rules('vill_townprt_code', 'Village Name', 'trim|integer|required');
        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
            return;
        } else {
            $dist_code = $this->input->post('dist_code');
            $subdiv_code = $this->input->post('subdiv_code');
            $circle_code = $this->input->post('cir_code');
            $mouza_code = $this->input->post('mouza_pargona_code');
            $lot_no = $this->input->post('lot_no');
            $vill_code = $this->input->post('vill_townprt_code');

            $this->db->select('patta_no');
            $this->db->distinct();
            $this->db->where('dist_code', $dist_code);
            $this->db->where('subdiv_code', $subdiv_code);
            $this->db->where('cir_code', $circle_code);
            $this->db->where('mouza_pargona_code', $mouza_code);
            $this->db->where('lot_no', $lot_no);
            $this->db->where('vill_townprt_code', $vill_code);
            $this->db->from('jama_dag');
            $pDetails = $this->db->get();

            $jama_dags = $pDetails->result();

            // foreach ($jama_dags as $jama_dag) {
            //     $data['patta_no'][] = $jama_dag;
            // }
            // dd($data['patta_no']);
            // echo json_encode($data['patta_no']);
            $patta_nos_all = [];
            foreach ($jama_dags as $jama_dag) {
                $patta_nos_all[] = $jama_dag->patta_no;
            }
            //dd($patta_nos_all);
            echo json_encode(array('msg' => $patta_nos_all, 'st' => 1));
        }
    }

    public function getReqPattaNos($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code)
    {
        $this->dataswitch();
        $this->db->select('patta_no');
        $this->db->distinct();
        $this->db->where('dist_code', $dist_code);
        $this->db->where('subdiv_code', $subdiv_code);
        $this->db->where('cir_code', $circle_code);
        $this->db->where('mouza_pargona_code', $mouza_code);
        $this->db->where('lot_no', $lot_no);
        $this->db->where('vill_townprt_code', $vill_code);
        $this->db->from('jama_dag');
        $pDetails = $this->db->get();

        $jama_dags = $pDetails->result();
        foreach ($jama_dags as $jama_dag) {
            $data['patta_nos_all'][] = $jama_dag;
        }

        return ($data['patta_nos_all']);
    }

    /********* Jama Pattdar View Location Form ******/
    public function setJamaPattadarLocation()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $distcode = $this->session->userdata('dcode');
        $this->load->model('LoginModel');
        $LoginModel = new LoginModel();
        $data['districts'] = $LoginModel->districtdetailsall();

        $data['_view'] = 'jama_pattadar/Jama_pattdar_bulk_update/set_location';

        $this->load->view('layout/layout', $data);
    }

    /***** Get Chitha Pattadar for Jama Pattadar Updatation ***/
    public function jamaPattdarGetChithaPattadars()
    {
        $data = array();
        $this->form_validation->set_rules('dist_code', 'District Name', 'trim|integer|required');
        $this->form_validation->set_rules('subdiv_code', 'Sub Division Name', 'trim|integer|required');
        $this->form_validation->set_rules('cir_code', 'Circle Name', 'trim|integer|required');
        $this->form_validation->set_rules('mouza_pargona_code', 'Mouza Name', 'trim|integer|required');
        $this->form_validation->set_rules('lot_no', 'Lot Number', 'trim|integer|required');
        $this->form_validation->set_rules('vill_townprt_code', 'Village Name', 'trim|integer|required');

        if ($this->form_validation->run() == false) {
            $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
            $this->setJamaPattadarLocation();
            return;
        } else {
            $data['dist_code'] = $dist_code = $this->input->post('dist_code');
            $data['subdiv_code'] = $subdiv_code = $this->input->post('subdiv_code');
            $data['cir_code'] = $cir_code = $this->input->post('cir_code');
            $data['mouza_pargona_code'] = $mouza_pargona_code = $this->input->post('mouza_pargona_code');
            $data['lot_no'] = $lot_no = $this->input->post('lot_no');
            $data['vill_townprt_code'] = $vill_townprt_code = $this->input->post('vill_townprt_code');

            $data['locationname'] = $this->Chithamodel->getlocationnames($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code);

            $this->dataSwitchforAdmin($dist_code);

            $query = "select dist_code,subdiv_code,cir_code,mouza_pargona_code,
			lot_no,vill_townprt_code,patta_no,patta_type_code, count(*) as pattadars from chitha_pattadar
			where dist_code=? and subdiv_code=? and cir_code=?
			and mouza_pargona_code=? and lot_no=? and vill_townprt_code=?
			group by dist_code,subdiv_code,cir_code,mouza_pargona_code,
			lot_no,vill_townprt_code,patta_no,patta_type_code";

            $data['chitha_pattadars'] = $this->db->query($query, array(
                $dist_code, $subdiv_code, $cir_code,
                $mouza_pargona_code, $lot_no, $vill_townprt_code,
            ))->result();

            $data['base'] = $this->config->item('base_url');
            $data['_view'] = 'jama_pattadar/Jama_pattdar_bulk_update/jama_pattadar_chitha_pattadars';
            $this->load->view('layout/layout', $data);
        }
    }

    /***** Update Chitha Pattadar for Jama Pattadar Updatation ***/
    public function updateJamaPattdarFromChithaPattadars()
    {
        $data['dist_code'] = $dist_code = $this->input->post('dist_code');
        $data['subdiv_code'] = $subdiv_code = $this->input->post('subdiv_code');
        $data['cir_code'] = $cir_code = $this->input->post('cir_code');
        $data['mouza_pargona_code'] = $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $data['lot_no'] = $lot_no = $this->input->post('lot_no');
        $data['vill_townprt_code'] = $vill_townprt_code = $this->input->post('vill_townprt_code');

        $this->dataSwitchforAdmin($dist_code);

        //        var_dump($cir_code);exit();

        $query = "select * from chitha_pattadar
			where dist_code=? and subdiv_code=? and cir_code=? and mouza_pargona_code=? and lot_no=? and vill_townprt_code=?";

        $chitha_pattadars = $this->db->query($query, array(
            $dist_code, $subdiv_code, $cir_code,
            $mouza_pargona_code, $lot_no, $vill_townprt_code,
        ))->result();

        $this->db->trans_begin();
        foreach ($chitha_pattadars as $ch) {
            $jama_array = [];
            $jama_array['dist_code'] = $ch->dist_code;
            $jama_array['subdiv_code'] = $ch->subdiv_code;
            $jama_array['cir_code'] = $ch->cir_code;
            $jama_array['mouza_pargona_code'] = $ch->mouza_pargona_code;
            $jama_array['lot_no'] = $ch->lot_no;
            $jama_array['vill_townprt_code'] = $ch->vill_townprt_code;
            $jama_array['patta_type_code'] = $ch->patta_type_code;
            $jama_array['patta_no'] = $ch->patta_no;
            $jama_array['pdar_id'] = $ch->pdar_id;
            $jama_array['pdar_name'] = $ch->pdar_name;
            $jama_array['pdar_father'] = $ch->pdar_father;
            $jama_array['pdar_add1'] = $ch->pdar_add1;
            $jama_array['pdar_add2'] = $ch->pdar_add2;
            $jama_array['pdar_add3'] = $ch->pdar_add3;
            $jama_array['pdar_pan_no'] = $ch->pdar_pan_no;
            $jama_array['pdar_citizen_no'] = $ch->pdar_citizen_no;
            $jama_array['pdar_thumb_imp'] = $ch->pdar_thumb_imp;
            $jama_array['pdar_photo'] = $ch->pdar_photo;
            $jama_array['pdar_land_b'] = 0;
            $jama_array['pdar_land_k'] = 0;
            $jama_array['pdar_land_lc'] = 0;
            $jama_array['pdar_land_g'] = 0;
            $jama_array['pdar_land_kr'] = 0;
            $jama_array['pdar_land_acre'] = 0;
            $jama_array['pdar_land_revenue'] = 0;
            $jama_array['pdar_land_localtax'] = 0;
            //            $jama_array['pdar_land_n'] = $ch->pdar_land_n;
            //            $jama_array['pdar_land_s'] = $ch->pdar_land_s;
            //            $jama_array['pdar_land_e'] = $ch->pdar_land_e;
            //            $jama_array['pdar_land_w'] = $ch->pdar_land_w;
            //            $jama_array['pdar_land_map'] = $ch->pdar_land_map;
            //            $jama_array['p_flag'] = $ch->p_flag;
            $jama_array['user_code'] = $ch->user_code;
            $jama_array['entry_date'] = $ch->date_entry;
            $jama_array['entry_mode'] = 'U';

            $jama_array['new_pdar_name'] = $ch->new_pdar_name;
            //            $jama_array['pdar_sl_no'] = $ch->pdar_sl_no;
            $jama_array['pdar_gender'] = $ch->pdar_gender;
            $jama_array['pdar_minor_yn'] = $ch->pdar_minor_yn;
            $jama_array['pdar_minor_dob'] = $ch->pdar_minor_dob;
            $jama_array['pdar_mother'] = $ch->pdar_mother;
            $jama_array['pdar_aadharno'] = $ch->pdar_aadharno;
            $jama_array['pdar_mobile'] = $ch->pdar_mobile;
            $jama_array['pdar_nrcno'] = $ch->pdar_nrcno;
            $jama_array['pdar_guard_rel'] = $ch->pdar_guard_rel;

            $q = "select count(*) as count from jama_pattadar
			where dist_code=? and subdiv_code=? and cir_code=?
			and mouza_pargona_code=? and lot_no=? and vill_townprt_code=? and patta_type_code='$ch->patta_type_code'
			and TRIM(patta_no)=trim('$ch->patta_no') and pdar_id='$ch->pdar_id'";

            $count = $this->db->query($q, array(
                $dist_code, $subdiv_code, $cir_code,
                $mouza_pargona_code, $lot_no, $vill_townprt_code,
            ))->row()->count;

            if ($count == 0) {
                $this->db->insert('jama_pattadar', $jama_array);
            }
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $report = array(
                'error' => true,
                'msg' => 'Database Transactions Failed.',
            );
            echo json_encode($report);
        } else {
            $this->db->trans_commit();
            $report = array(
                'error' => false,
                'msg' => 'Jama Pattadar Table Updated Successfully.',
            );
            echo json_encode($report);
        }
    }

    /********* DBSWITCH FOR ADMIN ********/
    public function dataSwitchforAdmin($dist_code)
    {
        $CI = &get_instance();
        if ($dist_code == "02") {
            $this->db = $CI->load->database('lsp3', true);
        } else if ($dist_code == "05") {
            $this->db = $CI->load->database('lsp1', true);
        } else if ($dist_code == "13") {
            $this->db = $CI->load->database('lsp2', true);
        } else if ($dist_code == "17") {
            $this->db = $CI->load->database('lsp4', true);
        } else if ($dist_code == "15") {
            $this->db = $CI->load->database('lsp5', true);
        } else if ($dist_code == "14") {
            $this->db = $CI->load->database('lsp6', true);
        } else if ($dist_code == "07") {
            $this->db = $CI->load->database('lsp7', true);
        } else if ($dist_code == "03") {
            $this->db = $CI->load->database('lsp8', true);
        } else if ($dist_code == "18") {
            $this->db = $CI->load->database('lsp9', true);
        } else if ($dist_code == "12") {
            $this->db = $CI->load->database('lsp13', true);
        } else if ($dist_code == "24") {
            $this->db = $CI->load->database('lsp10', true);
        } else if ($dist_code == "06") {
            $this->db = $CI->load->database('lsp11', true);
        } else if ($dist_code == "11") {
            $this->db = $CI->load->database('lsp12', true);
        } else if ($dist_code == "12") {
            $this->db = $CI->load->database('lsp13', true);
        } else if ($dist_code == "16") {
            $this->db = $CI->load->database('lsp14', true);
        } else if ($dist_code == "32") {
            $this->db = $CI->load->database('lsp15', true);
        } else if ($dist_code == "33") {
            $this->db = $CI->load->database('lsp16', true);
        } else if ($dist_code == "34") {
            $this->db = $CI->load->database('lsp17', true);
        } else if ($dist_code == "21") {
            $this->db = $CI->load->database('lsp18', true);
        } else if ($dist_code == "08") {
            $this->db = $CI->load->database('lsp19', true);
        } else if ($dist_code == "35") {
            $this->db = $CI->load->database('lsp20', true);
        } else if ($dist_code == "36") {
            $this->db = $CI->load->database('lsp21', true);
        } else if ($dist_code == "37") {
            $this->db = $CI->load->database('lsp22', true);
        } else if ($dist_code == "25") {
            $this->db = $CI->load->database('lsp23', true);
        } else if ($dist_code == "10") {
            $this->db = $CI->load->database('lsp24', true);
        } else if ($dist_code == "38") {
            $this->db = $CI->load->database('lsp25', true);
        } else if ($dist_code == "39") {
            $this->db = $CI->load->database('lsp26', true);
        } else if ($dist_code == "22") {
            $this->db = $CI->load->database('lsp27', true);
        } else if ($dist_code == "23") {
            $this->db = $CI->load->database('lsp28', true);
        } else if ($dist_code == "01") {
            $this->db = $CI->load->database('lsp29', true);
        } else if ($dist_code == "27") {
            $this->db = $CI->load->database('lsp30', true);
        }
    }
}
