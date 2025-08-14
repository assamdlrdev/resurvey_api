<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class Chithacontrol extends CI_Controller
{

    use CommonTrait;

    public function index()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $distcode = $this->session->userdata('dcode');
        $data['districts'] = $this->Chithamodel->districtdetails($distcode);
        if ($this->session->userdata('svill_townprt_code')) {
            $dist = $this->session->userdata('sdcode');
            $subdiv = $this->session->userdata('ssubdiv_code');
            $circle = (string) $this->session->userdata('scir_code');
            $mza = (string) $this->session->userdata('smouza_pargona_code');
            $lot = (string) $this->session->userdata('slot_no');
            $vill = (string) $this->session->userdata('svill_townprt_code');
            $currentURL = (string) $this->session->userdata('current_url');

            $data['locations'] = $this->Chithamodel->getSessionLoc($dist, $subdiv, $circle, $mza, $lot, $vill);
            $data['current_url'] = $currentURL;
        } else {
            $data['locations'] = null;
            $data['current_url'] = null;

        }
        $doc = $this->db->query("SELECT count(*) as c FROM chitha_basic WHERE dist_code='$distcode'")->row();
        $count = $doc->c;
        $data['_view'] = 'location';
        $data['count'] = $count;
        $this->load->view('layout/layout', $data);
    }

    public function dag_filter()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $distcode = $this->session->userdata('dcode');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir = (string) $this->session->userdata('cir_code');
        $data['districts'] = $this->Chithamodel->districtdetails($distcode);
        $doc = $this->db->query("SELECT * FROM chitha_basic INNER JOIN location ON chitha_basic.vill_townprt_code = location.vill_townprt_code INNER JOIN patta_code ON chitha_basic.patta_type_code = patta_code.type_code WHERE location.dist_code = chitha_basic.dist_code AND location.subdiv_code = chitha_basic.subdiv_code and location.cir_code = chitha_basic.cir_code and location.mouza_pargona_code = chitha_basic.mouza_pargona_code and location.lot_no = chitha_basic.lot_no and chitha_basic.dist_code = '$distcode' AND chitha_basic.subdiv_code = '$subdiv_code' and chitha_basic.cir_code = '$cir' ")->result_array();
        $count = sizeof($doc);
        $data['_view'] = 'dag_dashboard';
        $data['count'] = $count;
        $data['villages'] = $doc;
        $this->load->view('layout/layout', $data);
    }

    public function filterTable()
    {
        $this->dataswitch();
        $dist_code = $this->input->post('dist_code',TRUE);
        $subdiv_code = $this->input->post('subdiv_code',TRUE);
        $cir_code = $this->input->post('cir_code',TRUE);
        $mouza = $this->input->post('mouza_pargona_code',TRUE);
        $lot = $this->input->post('lot_no',TRUE);
        if ($subdiv_code == '00') {
            $vill = $this->db->query("SELECT * FROM chitha_basic INNER JOIN location ON chitha_basic.vill_townprt_code = location.vill_townprt_code INNER JOIN patta_code ON chitha_basic.patta_type_code = patta_code.type_code WHERE location.dist_code = chitha_basic.dist_code AND location.subdiv_code = chitha_basic.subdiv_code and location.cir_code = chitha_basic.cir_code and location.mouza_pargona_code = chitha_basic.mouza_pargona_code and location.lot_no = chitha_basic.lot_no and chitha_basic.dist_code = '$dist_code'")->result_array();
        } else if ($cir_code == '00') {
            $vill = $this->db->query("SELECT * FROM chitha_basic INNER JOIN location ON chitha_basic.vill_townprt_code = location.vill_townprt_code INNER JOIN patta_code ON chitha_basic.patta_type_code = patta_code.type_code WHERE location.dist_code = chitha_basic.dist_code AND location.subdiv_code = chitha_basic.subdiv_code and location.cir_code = chitha_basic.cir_code and location.mouza_pargona_code = chitha_basic.mouza_pargona_code and location.lot_no = chitha_basic.lot_no and chitha_basic.dist_code = '$dist_code' AND chitha_basic.subdiv_code = '$subdiv_code'")->result_array();
        } else if ($mouza == '00') {
            $vill = $this->db->query("SELECT * FROM chitha_basic INNER JOIN location ON chitha_basic.vill_townprt_code = location.vill_townprt_code INNER JOIN patta_code ON chitha_basic.patta_type_code = patta_code.type_code WHERE location.dist_code = chitha_basic.dist_code AND location.subdiv_code = chitha_basic.subdiv_code and location.cir_code = chitha_basic.cir_code and location.mouza_pargona_code = chitha_basic.mouza_pargona_code and location.lot_no = chitha_basic.lot_no and chitha_basic.dist_code = '$dist_code' AND chitha_basic.subdiv_code = '$subdiv_code' and chitha_basic.cir_code = '$cir_code' ")->result_array();
        } else if ($lot == '00') {
            $vill = $this->db->query("SELECT * FROM chitha_basic INNER JOIN location ON chitha_basic.vill_townprt_code = location.vill_townprt_code INNER JOIN patta_code ON chitha_basic.patta_type_code = patta_code.type_code WHERE location.dist_code = chitha_basic.dist_code AND location.subdiv_code = chitha_basic.subdiv_code and location.cir_code = chitha_basic.cir_code and location.mouza_pargona_code = chitha_basic.mouza_pargona_code and location.lot_no = chitha_basic.lot_no and chitha_basic.dist_code = '$dist_code' AND chitha_basic.subdiv_code = '$subdiv_code' and chitha_basic.cir_code = '$cir_code' and chitha_basic.mouza_pargona_code = '$mouza' ")->result_array();
        } else {
            $vill = $this->db->query("SELECT * FROM chitha_basic INNER JOIN location ON chitha_basic.vill_townprt_code = location.vill_townprt_code INNER JOIN patta_code ON chitha_basic.patta_type_code = patta_code.type_code WHERE location.dist_code = chitha_basic.dist_code AND location.subdiv_code = chitha_basic.subdiv_code and location.cir_code = chitha_basic.cir_code and location.mouza_pargona_code = chitha_basic.mouza_pargona_code and location.lot_no = chitha_basic.lot_no and chitha_basic.dist_code = '$dist_code' AND chitha_basic.subdiv_code = '$subdiv_code' and chitha_basic.cir_code = '$cir_code' and chitha_basic.mouza_pargona_code = '$mouza' and chitha_basic.lot_no = '$lot' ")->result_array();
        }
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
    public function Reportindex()
    {

        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $data['districts'] = $this->Chithamodel->districtdetailsreport();
        if ($this->session->userdata('svill_townprt_code')) {
            $dist = $this->session->userdata('sdcode');
            $subdiv = $this->session->userdata('ssubdiv_code');
            $circle = (string) $this->session->userdata('scir_code');
            $mza = (string) $this->session->userdata('smouza_pargona_code');
            $lot = (string) $this->session->userdata('slot_no');
            $vill = (string) $this->session->userdata('svill_townprt_code');
            $currentURL = (string) $this->session->userdata('current_url');

            $data['locations'] = $this->Chithamodel->getSessionLoc($dist, $subdiv, $circle, $mza, $lot, $vill);

            $data['current_url'] = $currentURL;
        } else {
            $data['locations'] = null;
            $data['current_url'] = null;

        }
        $data['_view'] = 'reportlocation';
        $this->load->view('layout/layout', $data);
    }

    public function indexSubmit()
    {
        $this->dataswitch();
        $data = array();
        $this->form_validation->set_rules('dist_code', 'District Name', 'trim|integer|required');
        $this->form_validation->set_rules('subdiv_code', 'Sub Division Name', 'trim|integer|required');
        $this->form_validation->set_rules('cir_code', 'Circle Name', 'trim|integer|required');
        $this->form_validation->set_rules('mouza_pargona_code', 'Mouza Name', 'trim|integer|required');
        $this->form_validation->set_rules('lot_no', 'Lot Number', 'trim|integer|required');
        $this->form_validation->set_rules('vill_townprt_code', 'Village Name', 'trim|integer|required');
        if ($this->form_validation->run()) {
            $data['post'] = $this->setLocationSession($_POST);
            $data['patta_type'] = $this->Chithamodel->getPattaType();
            $data['land_type'] = $this->Chithamodel->getLandclasscode();

            $data['_view'] = 'basic_details';
            $data['locationname'] = $this->setLocationNames();
            $this->load->view('layout/layout', $data);
        } else {
            $data['districts'] = $this->Chithamodel->districtdetails();
            $data['_view'] = 'location';
            $this->load->view('layout/layout', $data);
        }
    }

    private function setLocationNames()
    {
        $dist = $this->session->userdata('dist_code');
        $subdiv = $this->session->userdata('subdiv_code');
        $circle = $this->session->userdata('cir_code');
        $mouza = $this->session->userdata('mouza_pargona_code');
        $lot = $this->session->userdata('lot_no');
        $village = $this->session->userdata('vill_townprt_code');
        $data = $this->Chithamodel->getlocationnames($dist, $subdiv, $circle, $mouza, $lot, $village);
        return $data;
    }

    public function locationSubmit()
    {
        $data = array();
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
            $this->load->library('user_agent');
            $vill = $this->input->post('vill_townprt_code',TRUE);
            $this->session->set_userdata('vill_townprt_code', $vill);
            $this->session->set_userdata('current_url', $this->agent->referrer());
            $this->session->set_userdata('sdcode', $this->session->userdata('dcode'));
            $this->session->set_userdata('ssubdiv_code', $this->session->userdata('subdiv_code'));
            $this->session->set_userdata('scir_code', $this->session->userdata('cir_code'));
            $this->session->set_userdata('smouza_pargona_code', $this->session->userdata('mouza_pargona_code'));
            $this->session->set_userdata('slot_no', $this->session->userdata('lot_no'));
            $this->session->set_userdata('svill_townprt_code', $this->session->userdata('vill_townprt_code'));
            echo json_encode(array('msg' => 'Proceed for dag details entry', 'st' => 1));
        }
    }

    public function locationSubmitUpload()
    {
        $data = array();
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
            $vill = $this->input->post('vill_townprt_code',TRUE);
            $this->session->set_userdata('vill_townprt_code', $vill);
            echo json_encode(array('msg' => 'Proceed for dag details entry', 'st' => 1));
        }
    }

    public function dagbasic()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $data['pattype'] = $this->Chithamodel->getPattaType();
        $data['pcode'] = $this->input->post('patta_type_code',TRUE);
        $data['lclass'] = $this->Chithamodel->getLandclasscode();
        $data['lcode'] = $this->input->post('land_class_code',TRUE);
        $data['locationname'] = $this->setLocationNames();
        $data['_view'] = 'dag_details';
        $this->load->view('layout/layout', $data);
    }

    public function dagUpload()
    {
        $this->dataswitch();
        $data['user'] = $user = $this->session->userdata('usercode');
        $data['base'] = $this->config->item('base_url');
        $dis = $this->input->post('dist_code',TRUE);
        $subdiv = $this->input->post('subdiv_code',TRUE);
        $cir = $this->input->post('cir_code',TRUE);
        $mza = $this->input->post('mouza_pargona_code',TRUE);
        $lot = $this->input->post('lot_no',TRUE);
        $vill = $this->input->post('vill_townprt_code',TRUE);
        $pattatype = $this->input->post('patta_type',TRUE);
        $dag_no = $this->input->post('dag_no',TRUE);
        $data['loc_code'] = $loc_code = $dis . $subdiv . $cir . $mza . $lot . $vill;
        $data['uuid'] = $uuid = $this->input->post('uuid',TRUE);

        //$data['dag'] = $pattatypen;
        $data['dag_no'] = $dag_no;

        $this->load->model('MisModel');

        $districtdata = $this->MisModel->getDistrictName($dis);
        $subdivdata = $this->MisModel->getSubDivName($dis, $subdiv);
        $circledata = $this->MisModel->getCircleName($dis, $subdiv, $cir);
        $mouzadata = $this->MisModel->getMouzaName($dis, $subdiv, $cir, $mza);
        $lotdata = $this->MisModel->getLotName($dis, $subdiv, $cir, $mza, $lot);
        $villagedata = $this->MisModel->getVillageName($dis, $subdiv, $cir, $mza, $lot, $vill);
        $data['namedata'] = array_merge($districtdata, $subdivdata, $circledata, $mouzadata, $lotdata, $villagedata);

        $doc1_id = $this->db->query("SELECT doc_flag,file_name,uuid,dag_no FROM supportive_document WHERE doc_flag='1' and loc_code=? and dag_no= ? and uuid=? ", array($loc_code, $dag_no, $uuid));
        if ($doc1_id->num_rows() > 0) {
            $data['doc1_id'] = $doc1_id->row();
        }

        $doc2_id = $this->db->query("SELECT doc_flag,file_name,uuid,dag_no FROM supportive_document WHERE doc_flag='2' and loc_code=? and dag_no= ? and uuid = ?", array($loc_code, $dag_no, $uuid));
        if ($doc2_id->num_rows() > 0) {
            $data['doc2_id'] = $doc2_id->row();
        }

        $doc3_id = $this->db->query("SELECT doc_flag,file_name,uuid,dag_no FROM supportive_document WHERE doc_flag='3' and loc_code=? and dag_no= ? and uuid= ? ", array($loc_code, $dag_no, $uuid));
        if ($doc3_id->num_rows() > 0) {
            $data['doc3_id'] = $doc3_id->row();
        }

        $doc4_id = $this->db->query("SELECT doc_flag,file_name,uuid,dag_no FROM supportive_document WHERE doc_flag='4' and loc_code=? and dag_no= ? and uuid = ? ", array($loc_code, $dag_no, $uuid));
        if ($doc4_id->num_rows() > 0) {
            $data['doc4_id'] = $doc4_id->row();
        }

        $doc5_id = $this->db->query("SELECT doc_flag,file_name,uuid,dag_no FROM supportive_document WHERE doc_flag='5' and loc_code=? and dag_no= ? and uuid = ? ", array($loc_code, $dag_no, $uuid));
        if ($doc5_id->num_rows() > 0) {
            $data['doc5_id'] = $doc5_id->row();
        }

        $data['_view'] = 'document_upload/dag_select';
        $this->load->view('layout/layout', $data);
    }

    public function checknewdag()
    {
        $this->dataswitch();
        $dist_code = $this->input->post('dist_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $vill_townprt_code = $this->input->post('vill_townprt_code');
        $dag_no = $this->input->post('dag_no');

        $where = "(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' and dag_no='$dag_no')";
        $this->db->select('dag_area_b,dag_area_k,dag_area_lc,dag_area_g,dag_area_are,
        dag_revenue,dag_local_tax,land_class_code,old_dag_no,patta_no,patta_type_code,
        dag_nlrg_no,old_patta_no,
        dag_n_desc,dag_s_desc,dag_e_desc,dag_w_desc,
        dag_n_dag_no,dag_s_dag_no,dag_e_dag_no,dag_w_dag_no');
        $query = $this->db->get_where('chitha_basic', $where);
        if ($query->num_rows() > 0) {
            $row = $query->row();
            echo json_encode($row);
        } else {
            echo json_encode(null);
        }
    }

    public function checknewpatta1()
    {
        $this->dataswitch();
        $dcode = $this->input->get('p1');
        $scode = $this->input->get('p2');
        $ccode = $this->input->get('p3');
        $mcode = $this->input->get('p4');
        $lcode = $this->input->get('p5');
        $vcode = $this->input->get('p6');
        $pcode = $this->input->get('p7');
        $ptype = $this->input->get('p8');
        $dagcode = $this->input->get('p9');

        $data = array();

        $where = "(dist_code='$dcode' and subdiv_code='$scode' and cir_code='$ccode' and mouza_pargona_code='$mcode' and lot_no='$lcode' and vill_townprt_code='$vcode' and trim(patta_no)=trim('$pcode') and patta_type_code='$ptype')";
        $this->db->select('dag_area_b,dag_area_k,dag_area_lc,dag_area_g,dag_area_are,dag_revenue,dag_local_tax,land_class_code,old_dag_no,patta_no,patta_type_code,dag_nlrg_no');
        $query1 = $this->db->get_where('chitha_basic', $where);
        if ($query1->num_rows() > 0) {

            $where = "(dist_code='$dcode' and subdiv_code='$scode' and cir_code='$ccode' and mouza_pargona_code='$mcode' and lot_no='$lcode' and vill_townprt_code='$vcode' and dag_no='$dagcode' and trim(patta_no)=trim('$pcode') and patta_type_code='$ptype')";
            $this->db->select('dag_area_b,dag_area_k,dag_area_lc,dag_area_g,dag_area_are,dag_revenue,dag_local_tax,land_class_code,old_dag_no,patta_no,patta_type_code,dag_nlrg_no');
            $query = $this->db->get_where('chitha_basic', $where);
            if ($query->num_rows() > 0) {
                $row = $query->row_array();
                $newpatta = 'N';
                foreach ($query->result() as $row) {
                    array_push($data, $row->dag_area_b, $row->dag_area_k, number_format($row->dag_area_lc, 2, '.', ''), number_format($row->dag_area_g, 2, '.', ''), $row->dag_revenue, $row->dag_local_tax, $row->land_class_code, $row->old_dag_no, $row->dag_nlrg_no, $newpatta);
                }
                echo json_encode($data);
            } else {
                $dag_area_b = "";
                $dag_area_k = "";
                $dag_area_lc = "";
                $dag_area_g = "0";
                $dag_revenue = "";
                $dag_local_tax = "";
                $land_class_code = "";
                $old_dag_no = "";
                $dag_nlrg_no = "";
                $newpatta = "N";
                array_push($data, $dag_area_b, $dag_area_k, $dag_area_lc, $dag_area_g, $dag_revenue, $dag_local_tax, $land_class_code, $old_dag_no, $dag_nlrg_no, $newpatta);
                echo json_encode($data);
            }
        } else {
            $dag_area_b = "";
            $dag_area_k = "";
            $dag_area_lc = "";
            $dag_area_g = "0";
            $dag_revenue = "";
            $dag_local_tax = "";
            $land_class_code = "";
            $old_dag_no = "";
            $dag_nlrg_no = "";
            $newpatta = "Y";
            array_push($data, $dag_area_b, $dag_area_k, $dag_area_lc, $dag_area_g, $dag_revenue, $dag_local_tax, $land_class_code, $old_dag_no, $dag_nlrg_no, $newpatta);
            echo json_encode($data);
        }
    }

    public function checkoccup()
    {
        $this->dataswitch();
        $occup = urldecode($this->input->post('p1',TRUE));

        if ($occup != '') {
            $oc = explode("/", $occup);
            $pdar_name = $oc[0];
            $pdar_father = $oc[1];
            $pdar_rel = $oc[2];
            $dag_por_b = $oc[3];
            $dag_por_k = $oc[4];
            $dag_por_lc = $oc[5];

            $pdar_relation = $this->Chithamodel->relationame($pdar_rel);

            $data = array();
            array_push($data, $pdar_father, $pdar_relation, $dag_por_b, $dag_por_k, $dag_por_lc, $pdar_rel, $pdar_name);
            echo json_encode($data);
        }
    }

    public function dagentry()
    {
        $this->dataswitch();

        $this->form_validation->set_rules('dag_no', 'Dag Number', 'callback_validvalue|trim|integer|required');
        $this->form_validation->set_rules('patta_type_code', 'Patta Type', 'trim|required|max_length[4]|numeric');
        $this->form_validation->set_rules('land_class_code', 'Land Class', 'trim|required|max_length[4]|min_length[1]');
        $this->form_validation->set_rules('dag_land_revenue', 'Dag Land Revenue', 'trim|required|max_length[10]|numeric');
        $this->form_validation->set_rules('dag_local_tax', 'Dag Local Tax', 'trim|required|max_length[10]|numeric');
        $this->form_validation->set_rules('dag_area_b', 'Dag Area Bigha ', 'trim|required|numeric');
        $this->form_validation->set_rules('dag_area_r', 'Dag Area In Are ', 'trim|required|numeric');
        if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
            $this->form_validation->set_rules('dag_area_k', 'Dag Area Katha ', 'callback_kathavalue|trim|required|numeric');
            $this->form_validation->set_rules('dag_area_lc', 'Dag Area Chetak ', 'callback_chatakvalue|trim|required|numeric');
            $this->form_validation->set_rules('dag_area_g', 'Dag Area Ganda ', 'callback_gandavalue|trim|required|numeric');
        } else {
            $this->form_validation->set_rules('dag_area_k', 'Dag Area Katha ', 'callback_kathavalue|trim|required|numeric');
            $this->form_validation->set_rules('dag_area_lc', 'Dag Area Lessa ', 'callback_lessavalue|trim|required|numeric');
        }

        if (in_array($this->input->post('patta_type_code',TRUE), GovtPattaCode)) {
            $this->form_validation->set_rules('patta_no', 'Patta No.', 'trim|required|max_length[4]|min_length[1]');
        } else {
            $this->form_validation->set_rules('patta_no', 'Patta No.', 'callback_validvalue|trim|required|max_length[4]|min_length[1]');
        }

        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
            return;
        } else {

            $patta_type = $this->input->post('patta_type_code',TRUE);
            $patta_no = $this->input->post('patta_no',TRUE);
            $newpattach = $this->Chithamodel->checknewpatta($patta_type, $patta_no);
            $this->session->set_userdata('newpatta', $newpattach);
            
            $dc = $this->session->userdata('dist_code');
            $sc = $this->session->userdata('subdiv_code');
            $cc = $this->session->userdata('cir_code');
            $mc = $this->session->userdata('mouza_pargona_code');
            $lc = $this->session->userdata('lot_no');
            $vc = $this->session->userdata('vill_townprt_code');

            $exclude_dhar_check = $dc . '-' . $sc . '-' . $cc . '-' . $mc . '-' . $lc . '-' . $vc;

            if(!in_array($exclude_dhar_check, EXCLUDE_DHAR_CHECK)) {
                $checkVillageInDharitree = $this->Chithamodel->checkVillageInDharitree();
                if($checkVillageInDharitree['status'] == 'FAILED' || $checkVillageInDharitree['responseType'] == 1) {
                    echo json_encode(array('msg' => $checkVillageInDharitree['msg'], 'st' => 0));
                    return;
                }
            }
            $nrows = $this->Chithamodel->insertdag();
            if ($nrows > 0) {
                $patta_type = $this->input->post('patta_type_code',TRUE);
                $patta_no = $this->input->post('patta_no',TRUE);
                $dag_no = $this->input->post('dag_no',TRUE);
                $bigha = $this->input->post('dag_area_b',TRUE);
                $katha = $this->input->post('dag_area_k',TRUE);
                $lessa = $this->input->post('dag_area_lc',TRUE);
                $ganda = $this->input->post('dag_area_g',TRUE);
                $are = $this->input->post('dag_area_r',TRUE);
                $this->session->set_userdata('patta_type', $patta_type);
                $this->session->set_userdata('patta_no', $patta_no);
                $this->session->set_userdata('dag_no', $dag_no);
                //$newpatta=$this->input->post('newpatta',TRUE);
                //$this->session->set_userdata('newpatta',$newpatta);
                $this->session->set_userdata('bigha', $bigha);
                $this->session->set_userdata('katha', $katha);
                $this->session->set_userdata('lessa', $lessa);
                $this->session->set_userdata('ganda', $ganda);
                $this->session->set_userdata('are', $are);
                //$newpattach=$this->Chithamodel->checknewpatta($patta_type,$patta_no);
                //$this->session->set_userdata('newpatta',$newpattach);
                echo json_encode(array('msg' => 'Proceed for pattadar details entry', 'st' => 1));
            } else {
                echo json_encode(array('msg' => 'Error in dag entry', 'st' => 0));
                return;
            }
        }
    }

    public function pattadardet()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $patta_no = $this->session->userdata('patta_no');
        $data['patta_no'] = $this->session->userdata('patta_no');
        $pattatype = $this->session->userdata('patta_type');
        $data['patta_type_code'] = $pattatype;
        $data['patta_type_name'] = $this->Chithamodel->patta_type_name($pattatype);
        $patta_type_name = $data['patta_type_name'];
        $this->session->set_userdata('patta_type_name', $patta_type_name);
        $data['dag_no'] = $this->session->userdata('dag_no');
        $pattadar_id = $this->Chithamodel->checkpattadarid();
        $data['pattaderId'] = $pattadar_id;
        $data['relname'] = $this->Chithamodel->relation();
        $data['relname1'] = $this->input->post('pdar_relation',TRUE);
        $newpatta = $this->session->userdata('newpatta');
        $data['locationname'] = $this->setLocationNames();
        $data['daghd'] = 'Dag no:' . $this->session->userdata('dag_no') . ' Patta no:' . $this->session->userdata('patta_no') . ' (' . $this->session->userdata('patta_type_name') . ')';
        if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Chatak:' . $this->session->userdata('lessa') . ' Ganda:' . $this->session->userdata('ganda');
        } else {
            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Lessa:' . $this->session->userdata('lessa');
        }
        if ($newpatta == 'Y') {
            $data['_view'] = 'pattardar_entry';
            $data['locationname'] = $this->setLocationNames();
            $this->load->view('layout/layout', $data);
        } else {
            redirect('Chithacontrol/pattadardetmod');
        }
    }

    public function pattadardetmod()
    {
        $this->dataswitch();
        $data['opt'] = '';
        $data['base'] = $this->config->item('base_url');
        $patta_no = $this->session->userdata('patta_no');
        $data['patta_no'] = $this->session->userdata('patta_no');
        $pattatype = $this->session->userdata('patta_type');
        $patta_type_name = $this->Chithamodel->patta_type_name($pattatype);
        $this->session->set_userdata('patta_type_name', $patta_type_name);
        $data['patta_type_code'] = $pattatype;
        $dag_no = $this->session->userdata('dag_no');
        $data['pdag'] = $this->Chithamodel->pdardag($patta_no, $pattatype, $dag_no);
        $data['pdag_no1'] = $this->input->post('pdag',TRUE);
        $data['dag_no'] = $this->session->userdata('dag_no');
        $patta = $patta_no;
        $ptype = $pattatype;
        $dagno = $dag_no;
        $data['pstr'] = $this->Chithamodel->pattadarinsdet($patta, $ptype, $dagno);
        $data['query'] = $this->Chithamodel->pattadardet($patta_no, $pattatype, $dag_no);
        if (!$data['query']) {
            $data['opt'] = 1;
        }
        $data['locationname'] = $this->setLocationNames();
        $data['daghd'] = 'Dag no:' . $this->session->userdata('dag_no') . ' Patta no:' . $this->session->userdata('patta_no') . ' (' . $this->session->userdata('patta_type_name') . ')';
        if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Chatak:' . $this->session->userdata('lessa') . ' Ganda:' . $this->session->userdata('ganda');
        } else {
            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Lessa:' . $this->session->userdata('lessa');
        }
        $data['_view'] = 'pattadar_modify';
        $this->load->view('layout/layout', $data);
    }

    public function pdaredit($vll)
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $pt = explode("-", $vll);
        $pid = $pt[0];
        $pattano = $pt[1];
        $pattatype = $pt[2];
        $dagno = $pt[3];
        $data['pattano'] = $pattano;
        $data['patta_type_name'] = $this->Chithamodel->patta_type_name($pattatype);
        $pidext = $this->Chithamodel->pattadarexispid($pid, $pattano, $pattatype, $dagno);
        $pp = explode("$", $pidext);
        $data['pdarid'] = $pp[0];
        $data['pname'] = $pp[1];
        $data['relname'] = $this->Chithamodel->relation();
        $data['relname1'] = $pp[2];
        $data['pfname'] = $pp[3];
        $data['padd1'] = $pp[4];
        $data['padd2'] = $pp[5];
        $data['padd3'] = $pp[6];
        $data['ppan'] = $pp[7];
        $data['pcit'] = $pp[8];
        $data['bigha'] = $pp[9];
        $data['katha'] = $pp[10];
        $data['lessa'] = $pp[11];
        $data['ganda'] = $pp[12];
        $data['landn'] = $pp[13];
        $data['lands'] = $pp[14];
        $data['lande'] = $pp[15];
        $data['landw'] = $pp[16];
        $data['lrev'] = $pp[17];
        $data['ltax'] = $pp[18];
        $data['pflag'] = $pp[19];
        $data['pGender'] = $pp[20];
        $data['dag_no'] = $dagno;
        $data['pdar_id'] = $pid;
        $data['ptype'] = $pattatype;

        $data['locationname'] = $this->setLocationNames();
        $data['daghd'] = 'Dag no:' . $this->session->userdata('dag_no') . ' Patta no:' . $this->session->userdata('patta_no') . ' (' . $this->session->userdata('patta_type_name') . ')';
        if ($this->session->userdata('dist_code') == '21') {
            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Chatak:' . $this->session->userdata('lessa') . ' Ganda:' . $this->session->userdata('ganda');
        } else {
            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Lessa:' . $this->session->userdata('lessa');
        }

        $data['_view'] = 'pattardar_exedit';
        $this->load->view('layout/layout', $data);
    }

    public function pattadarothdag()
    {
        $this->dataswitch();
        $dagno = $this->input->post('pdag',TRUE);
        $patta_no = $this->session->userdata('patta_no');
        $pattatype = $this->session->userdata('patta_type');
        $str = $this->Chithamodel->pattadardagdet($patta_no, $pattatype, $dagno);
        //var_dump($str);
        echo json_encode(array('msg' => $str));
    }

    public function inserpdarfexit()
    {
        $this->dataswitch();
        $dagno = $this->input->post('edag',TRUE);

        if (isset($_POST['chk'])) {
            foreach ($_POST['chk'] as $item) {
                $cval = $item;
                $cc = explode(',', $cval);
                $pid = $cc[0];
                $pname = $cc[1];
                $patta = $cc[2];
                $ptype = $cc[3];
                $dag = $cc[4];

                $this->Chithamodel->insertexitdag($pid, $pname, $patta, $ptype, $dag);
            }
        } else {
            echo json_encode(array('msg' => 'Please select the pattadars', 'st' => 0));
            return;
        }

        $str = $this->Chithamodel->pattadarinsdet($patta, $ptype, $dagno);
        echo json_encode(array('msg' => $str, 'st' => 1));
    }

    public function pdarexupdate()
    {
        $this->dataswitch();
        $this->form_validation->set_rules('pdar_name', 'Pattadar Name', 'trim|required|max_length[50]');
        if (!in_array($this->input->post('patta_type_code',TRUE), GovtPattaCode)) {
            $this->form_validation->set_rules('pdar_father', 'Guardian Name', 'trim|required|max_length[50]');
            $this->form_validation->set_rules('pdar_relation', 'Guardian Relation', 'trim|required|max_length[50]');
            $this->form_validation->set_rules('p_flag', 'Pattadar Stricked Out', 'trim|required|max_length[1]');
            $this->form_validation->set_rules('p_gender', 'Pattadar Gender', 'required');
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
            $nrows = $this->Chithamodel->updatepattadar();
            if ($nrows > 0) {
                echo json_encode(array('msg' => 'Data modified for Pattadar', 'st' => 1));
            } else {
                echo json_encode(array('msg' => 'Error in pattadar entry', 'st' => 0));
            }
        }
    }

    public function pdarentry()
    {
        $this->dataswitch();
        $this->form_validation->set_rules('pdar_name', 'Pattadar Name', 'trim|required|max_length[50]');
        if (!in_array($this->input->post('patta_type_code',TRUE), GovtPattaCode)) {
            $this->form_validation->set_rules('pdar_father', 'Guardian Name', 'trim|required|max_length[50]');
            $this->form_validation->set_rules('pdar_relation', 'Guardian Relation', 'trim|required|max_length[50]');
            $this->form_validation->set_rules('p_flag', 'Pattadar Striked Out', 'trim|required|max_length[1]');
            $this->form_validation->set_rules('dag_por_b', 'Dag Area Bigha ', 'trim|required|numeric');
            $this->form_validation->set_rules('p_gender', 'Pattadar Gender ', 'required');

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
            $nrows = $this->Chithamodel->insertpattadar();
            if ($nrows > 0) {
                echo json_encode(array('msg' => 'Data saved for Pattadar', 'st' => 1));
            } else {
                echo json_encode(array('msg' => 'Error in pattadar entry', 'st' => 0));
            }
        }
    }

    public function pdarexentry()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $patta_no = $this->session->userdata('patta_no');
        $data['patta_no'] = $this->session->userdata('patta_no');
        $pattatype = $this->session->userdata('patta_type');
        $data['patta_type_code'] = $pattatype;
        $data['patta_type_name'] = $this->Chithamodel->patta_type_name($pattatype);
        $data['dag_no'] = $this->session->userdata('dag_no');
        $pattadar_id = $this->Chithamodel->checkpattadarid();
        $data['pattaderId'] = $pattadar_id;
        $data['relname'] = $this->Chithamodel->relation();
        $data['relname1'] = $this->input->post('pdar_relation',TRUE);
        $data['locationname'] = $this->setLocationNames();
        $data['daghd'] = 'Dag no:' . $this->session->userdata('dag_no') . ' Patta no:' . $this->session->userdata('patta_no') . ' (' . $this->session->userdata('patta_type_name') . ')';
        if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Chatak:' . $this->session->userdata('lessa') . ' Ganda:' . $this->session->userdata('ganda');
        } else {
            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Lessa:' . $this->session->userdata('lessa');
        }
        $data['_view'] = 'pattardar_exentry';
        $this->load->view('layout/layout', $data);
    }

    public function pdarentrymod()
    {
        $this->dataswitch();
        $this->form_validation->set_rules('pdar_name', 'Pattadar Name', 'trim|required|max_length[50]');
        if (!in_array($this->input->post('patta_type_code',TRUE), GovtPattaCode)) {
            $this->form_validation->set_rules('pdar_father', 'Guardian Name', 'trim|required|max_length[50]');
            $this->form_validation->set_rules('pdar_relation', 'Guardian Relation', 'trim|required|max_length[50]');
            $this->form_validation->set_rules('p_flag', 'Pattadar Stricked Out', 'trim|required|max_length[1]');
            //        $this->form_validation->set_rules('p_gender','Pattadar Gender','trim|required|max_length[1]');
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
            $nrows = $this->Chithamodel->insertpattadar();
            if ($nrows > 0) {
                echo json_encode(array('msg' => 'Data saved for Pattadar', 'st' => 1));
            } else {
                echo json_encode(array('msg' => 'Error in pattadar entry', 'st' => 0));
            }
        }
    }

    public function orderdet()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $data['dag_no'] = $this->session->userdata('dag_no');
        $data['ntrcode'] = $this->Chithamodel->ntrcode();
        $data['ntrcode1'] = $this->input->post('nature_trans_code',TRUE);
        $data['fmutype'] = $this->Chithamodel->fmuttype();
        $data['fmutype1'] = $this->input->post('order_type_code',TRUE);
        $data['lmname'] = $this->Chithamodel->lmname();
        $data['lmname1'] = $this->input->post('lm_code',TRUE);
        $data['coname'] = $this->Chithamodel->coname();
        $data['coname1'] = $this->input->post('co_code',TRUE);
        $data['ordersno'] = $this->Chithamodel->ordersrno();
        $data['_view'] = 'col8_order';
        $data['locationname'] = $this->setLocationNames();
        $data['daghd'] = 'Dag no:' . $this->session->userdata('dag_no') . ' Patta no:' . $this->session->userdata('patta_no') . ' (' . $this->session->userdata('patta_type_name') . ')';
        if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Chatak:' . $this->session->userdata('lessa') . ' Ganda:' . $this->session->userdata('ganda');
        } else {
            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Lessa:' . $this->session->userdata('lessa');
        }
        $this->load->view('layout/layout', $data);
    }

    public function orderdetentry()
    {
        $this->dataswitch();
        $this->form_validation->set_rules('order_type_code', 'Order Type', 'trim|required|max_length[10]');
        $this->form_validation->set_rules('nature_trans_code', 'Nature of Transfer', 'trim|required|max_length[10]');
        $this->form_validation->set_rules('lm_code', 'LM Name', 'trim|required|max_length[100]');
        $this->form_validation->set_rules('lm_note_date', 'LM Note Date', 'trim|required|date');
        $this->form_validation->set_rules('co_code', 'CO Name', 'trim|required|max_length[100]');
        $this->form_validation->set_rules('co_ord_date', 'CO Order Date', 'trim|required|date');
        $this->form_validation->set_rules('case_no', 'Case No', 'trim|required|max_length[50]');
        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
            return;
        } else {
            $nrows = $this->Chithamodel->insertcol8order();
            if ($nrows > 0) {
                $col8crno = $this->input->post('col8order_cron_no',TRUE);
                $this->session->set_userdata('col8crno', $col8crno);
                echo json_encode(array('msg' => 'Proceed for Occupants entry', 'st' => 1));
            } else {
                echo json_encode(array('msg' => 'Error in order entry', 'st' => 0));
            }
        }
    }

    public function occupant()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $data['col8crno'] = $this->session->userdata('col8crno');
        $dag_no = $this->session->userdata('dag_no');
        $data['dag_no'] = $this->session->userdata('dag_no');
        $patta_no = $this->session->userdata('patta_no');
        $pattatype = $this->session->userdata('patta_type');
        $data['occupnm'] = $this->Chithamodel->occupnm($patta_no, $pattatype, $dag_no);
        $data['occupid'] = $this->Chithamodel->occupantid();
        $data['locationname'] = $this->setLocationNames();
        $data['daghd'] = 'Dag no:' . $this->session->userdata('dag_no') . ' Patta no:' . $this->session->userdata('patta_no') . ' (' . $this->session->userdata('patta_type_name') . ')';
        if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Chatak:' . $this->session->userdata('lessa') . ' Ganda:' . $this->session->userdata('ganda');
        } else {
            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Lessa:' . $this->session->userdata('lessa');
        }
        $data['_view'] = 'col8_occupant';
        $this->load->view('layout/layout', $data);
    }

    public function occupdetentry()
    {
        $this->dataswitch();
        $this->form_validation->set_rules('occupant_name', 'Occupant Name', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('occupant_fmh_name', 'Guardian Name', 'trim|required|max_length[100]');
        $this->form_validation->set_rules('land_area_b', 'Land Area Bigha', 'trim|required|Numeric');
        if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
            $this->form_validation->set_rules('land_area_k', 'Land Area Katha ', 'callback_kathavalue|trim|required|numeric');
            $this->form_validation->set_rules('land_area_lc', 'Land Area Chatak ', 'callback_chatakvalue|trim|required|numeric');
            $this->form_validation->set_rules('land_area_g', 'Land Area Ganda ', 'callback_gandavalue|trim|required|numeric');
        } else {
            $this->form_validation->set_rules('land_area_k', 'Land Area Katha', 'callback_kathavalue|trim|required|Numeric');
            $this->form_validation->set_rules('land_area_lc', 'Land Area Lessa', 'callback_lessavalue|trim|required|Numeric');
        }
        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
            return;
        } else {
            $nrows = $this->Chithamodel->insertcol8occup();
            if ($nrows > 0) {
                $col8crno = $this->input->post('col8order_cron_no',TRUE);
                $this->session->set_userdata('col8crno', $col8crno);
                echo json_encode(array('msg' => 'Data saved for occupant', 'st' => 1));
            } else {
                echo json_encode(array('msg' => 'Error in occupant entry', 'st' => 0));
            }
        }
    }

    public function inplace()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $data['col8crno'] = $this->session->userdata('col8crno');
        $dag_no = $this->session->userdata('dag_no');
        $data['dag_no'] = $this->session->userdata('dag_no');
        $patta_no = $this->session->userdata('patta_no');
        $pattatype = $this->session->userdata('patta_type');
        $data['inplaceid'] = $this->Chithamodel->inplaceid();
        $data['_view'] = 'col8_inplace';
        $data['locationname'] = $this->setLocationNames();
        $data['daghd'] = 'Dag no:' . $this->session->userdata('dag_no') . ' Patta no:' . $this->session->userdata('patta_no') . ' (' . $this->session->userdata('patta_type_name') . ')';
        if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Chatak:' . $this->session->userdata('lessa') . ' Ganda:' . $this->session->userdata('ganda');
        } else {
            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Lessa:' . $this->session->userdata('lessa');
        }
        $this->load->view('layout/layout', $data);
    }

    public function inplacentry()
    {
        $this->dataswitch();
        $this->form_validation->set_rules('inplace_of_name', 'Name', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('inplaceof_alongwith', 'Inplace/Alongwith', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('land_area_b', 'Land Area Bigha', 'trim|required|Numeric');
        if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
            $this->form_validation->set_rules('land_area_k', 'Land Area Katha ', 'callback_kathavalue|trim|required|numeric');
            $this->form_validation->set_rules('land_area_lc', 'Land Area Chatak ', 'callback_chatakvalue|trim|required|numeric');
            $this->form_validation->set_rules('land_area_g', 'Land Area Ganda ', 'callback_gandavalue|trim|required|numeric');
        } else {
            $this->form_validation->set_rules('land_area_k', 'Land Area Katha', 'callback_kathavalue|trim|required|Numeric');
            $this->form_validation->set_rules('land_area_lc', 'Land Area Lessa', 'callback_lessavalue|trim|required|Numeric');
        }
        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
            return;
        } else {
            $nrows = $this->Chithamodel->insertcol8inplace();
            if ($nrows > 0) {
                echo json_encode(array('msg' => 'Data saved for inpaceof/Alongwith', 'st' => 1));
            } else {
                echo json_encode(array('msg' => 'Error in inpaceof/Alongwith', 'st' => 0));
            }
        }
    }

    public function kathavalue($str)
    {
        if ($str != '') {
            if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
                if ($str < 20) {
                    return true;
                } else if ($str >= 20) {
                    $this->form_validation->set_message('kathavalue', 'The %s field cannot be more than 20');
                    return false;
                }
            } else {
                if ($str < 5) {
                    return true;
                } else if ($str >= 5) {
                    $this->form_validation->set_message('kathavalue', 'The %s field cannot be more than 4');
                    return false;
                }
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
                return false;
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
                return false;
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
                return false;
            }
        }
    }

    public function validvalue($str)
    {
        if ($str != '') {
            $result = substr($str, 0, 1);
            if ($result != '0') {
                return true;
            } else if ($result == '0') {
                $this->form_validation->set_message('validvalue', 'The %s field cannot start with zero');
                return false;
            }
        }
    }

    public function namecheck($str)
    {
        $pattern = '/^[a-zA-Z\/, \s_.-: ]{2,50000}$/';

        if ($str != '') {
            if (preg_match($pattern, $str)) {
                return true;
            } else {
                $this->form_validation->set_message('namecheck', 'The %s field can not contain special Character.');
                return false;
            }
        }
    }

    private function setLocationSession($data)
    {
        $location = [
            'dist_code' => $data['dist_code'],
            'subdiv_code' => $data['subdiv_code'],
            'cir_code' => $data['cir_code'],
            'mouza_pargona_code' => $data['mouza_pargona_code'],
            'lot_no' => $data['lot_no'],
            'vill_townprt_code' => $data['vill_townprt_code'],
        ];
        $this->session->set_userdata($location);
        return true;
    }

    public function subdivisiondetails()
    {
        // $this->dataswitch();
        $data = [];
        $dist_code = $this->input->post('id',TRUE);
        $this->dbswitch($dist_code);
        $this->session->set_userdata('dist_code', $dist_code);
        // $subdiv_code = $this->session->userdata('subdiv_code');
        $formdata = $this->Chithamodel->subdivisiondetails($dist_code);
        foreach ($formdata as $value) {
            $data['subdiv_code'][] = $value;
        }
        echo json_encode($formdata);
    }

    public function circledetails()
    {
        // $this->dataswitch();
        $data = [];
        $dist_code = $this->input->post('dis',TRUE);
        $this->dbswitch($dist_code);
        $subdiv = $this->input->post('subdiv',TRUE);
        // $cir_code = $this->session->userdata('cir_code');
        $this->session->set_userdata('subdiv_code', $subdiv);
        $formdata = $this->Chithamodel->circledetails($dist_code, $subdiv);
        foreach ($formdata as $value) {
            $data['cir_code'][] = $value;
        }
        echo json_encode($formdata);
    }

    public function mouzadetails()
    {
        // $this->dataswitch();
        $data = [];
        $dis = $this->input->post('dis',TRUE);
        $this->dbswitch($dis);
        $subdiv = $this->input->post('subdiv',TRUE);
        $cir = $this->input->post('cir',TRUE);
        $this->session->set_userdata('cir_code', $cir);
        $formdata = $this->Chithamodel->mouzadetails($dis, $subdiv, $cir);
        foreach ($formdata as $value) {
            $data['cir_code'][] = $value;
        }
        echo json_encode($formdata);
    }

    public function lotdetails()
    {
        // $this->dataswitch();
        $data = [];
        $dis = $this->input->post('dis',TRUE);
        $this->dbswitch($dis);
        $subdiv = $this->input->post('subdiv',TRUE);
        $cir = $this->input->post('cir',TRUE);
        $mza = $this->input->post('mza',TRUE);
        $this->session->set_userdata('mouza_pargona_code', $mza);
        $formdata = $this->Chithamodel->lotdetails($dis, $subdiv, $cir, $mza);
        foreach ($formdata as $value) {
            $data['test'][] = $value;
        }
        echo json_encode($formdata);
    }

    public function villagedetails()
    {
        $this->dataswitch();
        $data = [];
        $dis = $this->input->post('dis',TRUE);
        // $this->dbswitch($dis);
        $subdiv = $this->input->post('subdiv',TRUE);
        $cir = $this->input->post('cir',TRUE);
        $mza = $this->input->post('mza',TRUE);
        $lot = $this->input->post('lot',TRUE);
        $this->session->set_userdata('lot_no', $lot);
        $formdata = $this->Chithamodel->villagedetails($dis, $subdiv, $cir, $mza, $lot);
        foreach ($formdata as $value) {
            $data['test'][] = $value;
        }
        echo json_encode($formdata);
    }

    public function tenant()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $data['relanm'] = $this->Chithamodel->relname();
        $data['relnm'] = $this->input->post('guard_rel',TRUE);
        $data['tentype'] = $this->Chithamodel->tentype();
        $data['tenttype1'] = $this->input->post('type_of_tenant',TRUE);
        $tenant_id = $this->Chithamodel->checktenantid();
        $data['tenantId'] = $tenant_id;
        $data['locationname'] = $this->setLocationNames();
        $data['daghd'] = 'Dag no:' . $this->session->userdata('dag_no') . ' Patta no:' . $this->session->userdata('patta_no') . ' (' . $this->session->userdata('patta_type_name') . ')';
        if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Chatak:' . $this->session->userdata('lessa') . ' Ganda:' . $this->session->userdata('ganda');
        } else {
            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Lessa:' . $this->session->userdata('lessa');
        }

        $data['_view'] = 'tenantentry';
        $this->load->view('layout/layout', $data);
    }

    public function tenantentry()
    {
        $this->dataswitch();
        $this->form_validation->set_rules('tenant_name', 'Tenant Name', 'trim|required');
        $this->form_validation->set_rules('tenants_father', 'Guardian Name', 'trim|required');
        $this->form_validation->set_rules('guard_rel', 'Relation', 'trim|required');
        $this->form_validation->set_rules('khatian_no', 'Khatian No', 'trim|required');
        $this->form_validation->set_rules('possession_area_b', 'Bigha', 'trim|required');
        $this->form_validation->set_rules('possession_area_k', 'Katha', 'trim|required');
        if (in_array($this->session->userdata('dist_code'), BARAK_VALLEY)) {
            $this->form_validation->set_rules('possession_area_l', 'Chatak', 'trim|required');
            $this->form_validation->set_rules('possession_area_g', 'Ganda', 'trim|required');
        } else {
            $this->form_validation->set_rules('possession_area_l', 'Lessa', 'trim|required');
        }
        $this->form_validation->set_rules('possession_length', 'Length of Possession', 'trim|required');
        $this->form_validation->set_rules('tenant_status', 'Status of Tenant', 'trim|required');

        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
            return;
        } else {
            $nrows = $this->Chithamodel->inserttenant();
            if ($nrows > 0) {
                echo json_encode(array('msg' => 'Data saved', 'st' => 1));
            } else {
                echo json_encode(array('msg' => 'Error in tenant entry', 'st' => 0));
            }
        }
    }

    public function subtenant()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        //$data['tid']=$this->session->userdata('tenant');
        $data['relanm'] = $this->Chithamodel->relname();
        $data['relnm'] = $this->input->post('guard_rel',TRUE);
        $data['tntsub'] = $this->Chithamodel->tenidsub();
        $data['tidsub'] = $this->input->post('tname',TRUE);
        $subtenant_id = $this->Chithamodel->checksubtenantid();
        $data['subtenantId'] = $subtenant_id;

        //$this->load->view('subtenantentry',$data);
        $data['_view'] = 'subtenantentry';
        $data['locationname'] = $this->setLocationNames();
        $data['daghd'] = 'Dag no:' . $this->session->userdata('dag_no') . ' Patta no:' . $this->session->userdata('patta_no') . ' (' . $this->session->userdata('patta_type_name') . ')';
        if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Chatak:' . $this->session->userdata('lessa') . ' Ganda:' . $this->session->userdata('ganda');
        } else {
            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Lessa:' . $this->session->userdata('lessa');
        }

        $this->load->view('layout/layout', $data);
    }

    public function subtenantentry()
    {
        $this->dataswitch();
        $this->form_validation->set_rules('tenantid', 'Corresponding Tenant Name', 'trim|required');
        $this->form_validation->set_rules('subtennm', 'Subtenant Name', 'trim|required');
        $this->form_validation->set_rules('subtenants_father', 'Subtenants Guardian', 'trim|required');
        $this->form_validation->set_rules('guard_rel', 'Relation', 'trim|required');

        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
            return;
        } else {
            $nrows = $this->Chithamodel->insertsubtenant();
            if ($nrows > 0) {
                echo json_encode(array('msg' => 'Data saved', 'st' => 1));
            } else {
                echo json_encode(array('msg' => 'Error in subtenant entry', 'st' => 0));
            }
        }
    }

    public function crop()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        //var_dump('crop');die();
        $data['cropnm'] = $this->Chithamodel->cropname();
        $data['crpnm'] = $this->input->post('cropname',TRUE);
        $data['cropcat'] = $this->Chithamodel->cropcat();
        $data['crpcat'] = $this->input->post('cropcatg',TRUE);
        $data['cropsn'] = $this->Chithamodel->cropseason();
        $data['crpsn'] = $this->input->post('cropsn',TRUE);
        $data['watersrc'] = $this->Chithamodel->watersource();
        $data['waters'] = $this->input->post('watersrc',TRUE);
        $crop_id = $this->Chithamodel->checkcropid();
        $data['cropId'] = $crop_id;
        $landDetails = $this->Chithamodel->landDetails();

        //$this->load->view('cropentry',$data);
        $data['_view'] = 'cropentry';
        $data['locationname'] = $this->setLocationNames();
        $data['daghd'] = 'Dag no:' . $this->session->userdata('dag_no') . ' Patta no:' . $this->session->userdata('patta_no') . ' (' . $this->session->userdata('patta_type_name') . ')';
        if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
            $landInLessa = $landDetails->crop_land_area_g + $landDetails->dag_area_lc * 20 + $landDetails->dag_area_k * 320 + $landDetails->dag_area_b * 6400;
            $data['landInLessa'] = $landInLessa;
            $data['landType'] = 'Ganda';

            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Chatak:' . $this->session->userdata('lessa') . ' Ganda:' . $this->session->userdata('ganda');
        } else {
            $landInLessa = $landDetails->dag_area_lc + $landDetails->dag_area_k * 20 + $landDetails->dag_area_b * 100;
            $data['landInLessa'] = $landInLessa;
            $data['landType'] = 'Lessa';

            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Lessa:' . $this->session->userdata('lessa');
        }

        $this->load->view('layout/layout', $data);
    }

    public function cropentry()
    {
        $this->dataswitch();
        $this->form_validation->set_rules('yearno', 'Year no', 'trim|required');
        $this->form_validation->set_rules('cropname', 'Crop Name', 'trim|required');
        //$this->form_validation->set_rules('cropname', 'Crop Category', 'trim|required');
        $this->form_validation->set_rules('cropseason', 'Crop Season', 'trim|required');
        $this->form_validation->set_rules('sourcewater', 'Water Source', 'trim|required');

        $this->form_validation->set_rules('croparea_b', 'Dag Area Bigha ', 'trim|required|numeric');
        $this->form_validation->set_rules('croparea_k', 'Dag Area Katha ', 'callback_kathavalue|trim|required|numeric');
        $this->form_validation->set_rules('croparea_lc', 'Dag Area Lessa ', 'callback_lessavalue|trim|required|numeric');

        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
            return;
        } else {
            $bighaF = intval($this->input->post('croparea_b',TRUE));
            $kathaF = intval($this->input->post('croparea_k',TRUE));
            $lessaF = intval($this->input->post('croparea_lc',TRUE));
            $gandaF = 0;

            $landDetails = $this->Chithamodel->landDetails();
            $year = intval($this->input->post('yearno',TRUE));
            $landDetailsInCrop = $this->Chithamodel->landDetailsInCrop($year);
            $landDetailsInNonCrop = $this->Chithamodel->landDetailsInNonCrop($year);

            $bigha = 0;
            $katha = 0;
            $lessa = 0;
            $ganda = 0;
            $bigha2 = 0;
            $katha2 = 0;
            $lessa2 = 0;
            $ganda2 = 0;
            $useLand = 0;
            $useLand1 = 0;
            $useLand2 = 0;
            $remainingLand = 0;
            foreach ($landDetailsInCrop as $mm) {
                $bigha += $mm->crop_land_area_b;
                $katha += $mm->crop_land_area_k;
                $lessa += $mm->crop_land_area_lc;
                $ganda += $mm->crop_land_area_g;
            }
            foreach ($landDetailsInNonCrop as $nn) {
                $bigha2 += $nn->noncrop_land_area_b;
                $katha2 += $nn->noncrop_land_area_k;
                $lessa2 += $nn->noncrop_land_area_lc;
                $ganda2 += $nn->noncrop_land_area_g;
            }
            if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
                $useLand1 = $bigha * 6400 + $katha * 320 + $lessa * 20 + $ganda;
                $useLand2 = $bigha2 * 6400 + $katha2 * 320 + $lessa2 * 20 + $ganda2;
                $useLand = $useLand1 + $useLand2;
                $landInLessa = $landDetails->crop_land_area_g + $landDetails->dag_area_lc * 20 + $landDetails->dag_area_k * 320 + $landDetails->dag_area_b * 6400;
                $remainingLand = $landInLessa - $useLand;

                $addLand = $bighaF * 6400 + $kathaF * 320 + $lessaF * 20 + $gandaF;
            } else {
                $useLand1 = $bigha * 100 + $katha * 20 + $lessa;
                $useLand2 = $bigha2 * 100 + $katha2 * 20 + $lessa2;
                $useLand = $useLand1 + $useLand2;
                $landInLessa = $landDetails->dag_area_lc + $landDetails->dag_area_k * 20 + $landDetails->dag_area_b * 100;
                $remainingLand = $landInLessa - $useLand;
                if ($kathaF > 4) {
                    echo json_encode(array('msg' => 'Error in crop entry Katha is more the 4', 'st' => 0));
                    return;
                }
                if ($lessaF > 19.9) {
                    echo json_encode(array('msg' => 'Error in crop entry Lessa is more the 19.9', 'st' => 0));
                    return;
                }

                $addLand = $bighaF * 100 + $kathaF * 20 + $lessaF;
            }
            if ($remainingLand < $addLand) {
                echo json_encode(array('msg' => 'Error in crop entry land is more then Total Remaining Land', 'st' => 0));
                return;
            }

            $nrows = $this->Chithamodel->insertcrop();
            if ($nrows > 0) {
                echo json_encode(array('msg' => 'Data saved', 'st' => 1));
            } else {
                echo json_encode(array('msg' => 'Error in crop entry', 'st' => 0));
                return;
            }
        }
    }

    public function noncrop()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $data['ncropnm'] = $this->Chithamodel->ncropname();
        $data['ncropnm1'] = $this->input->post('ncropcode',TRUE); // noncrop_type_code
        $noncrop_id = $this->Chithamodel->checknoncropid();
        $data['noncropId'] = $noncrop_id;
        $landDetails = $this->Chithamodel->landDetails();

        //$this->load->view('noncropentry',$data);
        $data['_view'] = 'noncropentry';
        $data['locationname'] = $this->setLocationNames();
        $data['daghd'] = 'Dag no:' . $this->session->userdata('dag_no') . ' Patta no:' . $this->session->userdata('patta_no') . ' (' . $this->session->userdata('patta_type_name') . ')';

        if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
            $landInLessa = $landDetails->crop_land_area_g + $landDetails->dag_area_lc * 20 + $landDetails->dag_area_k * 320 + $landDetails->dag_area_b * 6400;
            $data['landInLessa'] = $landInLessa;
            $data['landType'] = 'Ganda';
            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Chatak:' . $this->session->userdata('lessa') . ' Ganda:' . $this->session->userdata('ganda');
        } else {
            $landInLessa = $landDetails->dag_area_lc + $landDetails->dag_area_k * 20 + $landDetails->dag_area_b * 100;
            $data['landInLessa'] = $landInLessa;
            $data['landType'] = 'Lessa';
            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Lessa:' . $this->session->userdata('lessa');
        }

        $this->load->view('layout/layout', $data);
    }

    public function noncropentry()
    {
        $this->dataswitch();
        $this->form_validation->set_rules('yearno', 'Year no', 'trim|required');
        $this->form_validation->set_rules('ncropcode', 'Type of Usage', 'trim|required');
        $this->form_validation->set_rules('ncroparea_b', 'Dag Area Bigha ', 'trim|required|numeric');
        $this->form_validation->set_rules('ncroparea_k', 'Dag Area Katha ', 'callback_kathavalue|trim|required|numeric');
        $this->form_validation->set_rules('ncroparea_lc', 'Dag Area Lessa ', 'callback_lessavalue|trim|required|numeric');

        /*$this->form_validation->set_rules('vill_townprt_code', 'Village Name', 'trim|integer|required');*/

        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
            return;
        } else {
            $bighaF = intval($this->input->post('ncroparea_b',TRUE));
            $kathaF = intval($this->input->post('ncroparea_k',TRUE));
            $lessaF = intval($this->input->post('ncroparea_lc',TRUE));
            $gandaF = 0;

            $landDetails = $this->Chithamodel->landDetails();
            $year = intval($this->input->post('yearno',TRUE));
            $landDetailsInCrop = $this->Chithamodel->landDetailsInCrop($year);
            $landDetailsInNonCrop = $this->Chithamodel->landDetailsInNonCrop($year);

            $bigha = 0;
            $katha = 0;
            $lessa = 0;
            $ganda = 0;
            $bigha2 = 0;
            $katha2 = 0;
            $lessa2 = 0;
            $ganda2 = 0;
            $useLand = 0;
            $useLand1 = 0;
            $useLand2 = 0;
            $remainingLand = 0;
            foreach ($landDetailsInCrop as $mm) {
                $bigha += $mm->crop_land_area_b;
                $katha += $mm->crop_land_area_k;
                $lessa += $mm->crop_land_area_lc;
                $ganda += $mm->crop_land_area_g;
            }
            foreach ($landDetailsInNonCrop as $nn) {
                $bigha2 += $nn->noncrop_land_area_b;
                $katha2 += $nn->noncrop_land_area_k;
                $lessa2 += $nn->noncrop_land_area_lc;
                $ganda2 += $nn->noncrop_land_area_g;
            }
            if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
                $useLand1 = $bigha * 6400 + $katha * 320 + $lessa * 20 + $ganda;
                $useLand2 = $bigha2 * 6400 + $katha2 * 320 + $lessa2 * 20 + $ganda2;
                $useLand = $useLand1 + $useLand2;
                $landInLessa = $landDetails->crop_land_area_g + $landDetails->dag_area_lc * 20 + $landDetails->dag_area_k * 320 + $landDetails->dag_area_b * 6400;
                $remainingLand = $landInLessa - $useLand;

                $addLand = $bighaF * 6400 + $kathaF * 320 + $lessaF * 20 + $gandaF;
            } else {
                $useLand1 = $bigha * 100 + $katha * 20 + $lessa;
                $useLand2 = $bigha2 * 100 + $katha2 * 20 + $lessa2;
                $useLand = $useLand1 + $useLand2;
                $landInLessa = $landDetails->dag_area_lc + $landDetails->dag_area_k * 20 + $landDetails->dag_area_b * 100;
                $remainingLand = $landInLessa - $useLand;
                if ($kathaF > 4) {
                    echo json_encode(array('msg' => 'Error in crop entry Katha is more the 4', 'st' => 0));
                    return;
                }
                if ($lessaF > 19.9) {
                    echo json_encode(array('msg' => 'Error in crop entry Lessa is more the 19.9', 'st' => 0));
                    return;
                }

                $addLand = $bighaF * 100 + $kathaF * 20 + $lessaF;
            }
            if ($remainingLand < $addLand) {
                echo json_encode(array('msg' => 'Error in crop entry land is more then Total Remaining Land', 'st' => 0));
                return;
            }
            $nrows = $this->Chithamodel->insertnoncrop();
            if ($nrows > 0) {
                echo json_encode(array('msg' => 'Data saved', 'st' => 1));
            } else {
                echo json_encode(array('msg' => 'Error in crop entry', 'st' => 0));
            }
        }
    }

    public function fruit()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $data['fruitnm'] = $this->Chithamodel->fruitname();
        $data['fruitnm1'] = $this->input->post('frname',TRUE);
        $fruit_id = $this->Chithamodel->checkfruitid();
        $data['fruitId'] = $fruit_id;
        //$this->load->view('fruitentry',$data);
        $data['_view'] = 'fruitentry';
        $data['locationname'] = $this->setLocationNames();
        $data['daghd'] = 'Dag no:' . $this->session->userdata('dag_no') . ' Patta no:' . $this->session->userdata('patta_no') . ' (' . $this->session->userdata('patta_type_name') . ')';
        if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Chatak:' . $this->session->userdata('lessa') . ' Ganda:' . $this->session->userdata('ganda');
        } else {
            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Lessa:' . $this->session->userdata('lessa');
        }

        $this->load->view('layout/layout', $data);
    }

    public function fruitentry()
    {
        $this->dataswitch();
        $this->form_validation->set_rules('frname', 'Fruit Name', 'trim|required');
        $this->form_validation->set_rules('fplantno', 'Fruit plant no', 'required|numeric');

        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
        } else {
            $nrows = $this->Chithamodel->insertfruit();
            if ($nrows > 0) {
                echo json_encode(array('msg' => 'Data saved', 'st' => 1));
            } else {
                echo json_encode(array('msg' => 'Error in Fruit entry', 'st' => 0));
            }
        }
    }

    public function tenantedit()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $data['msg'] = '';
        $chktenant = $this->Chithamodel->checktenantid();
        $data['tno'] = $chktenant;
        if ($chktenant > 1) {
            $data['tendet'] = $this->Chithamodel->gettenants();
            $data['locationname'] = $this->setLocationNames();
            $data['daghd'] = 'Dag no:' . $this->session->userdata('dag_no') . ' Patta no:' . $this->session->userdata('patta_no') . ' (' . $this->session->userdata('patta_type_name') . ')';
            if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
                $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Chatak:' . $this->session->userdata('lessa') . ' Ganda:' . $this->session->userdata('ganda');
            } else {
                $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Lessa:' . $this->session->userdata('lessa');
            }
        } else {
            $data['msg'] = "No Tenant Record";
        }
        $data['_view'] = 'tenantedit';
        $this->load->view('layout/layout', $data);
    }

    public function tenantmod($nm)
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $data['tenant'] = $this->Chithamodel->getTenant($nm);
        $data['relanm'] = $this->Chithamodel->relname();
        $data['relnm'] = $this->Chithamodel->relname();
        $data['tentype'] = $this->Chithamodel->tentype();
        $data['_view'] = 'tenantmodify';
        $data['locationname'] = $this->setLocationNames();
        $data['daghd'] = 'Dag no:' . $this->session->userdata('dag_no') . ' Patta no:' . $this->session->userdata('patta_no') . ' (' . $this->session->userdata('patta_type_name') . ')';
        if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Chatak:' . $this->session->userdata('lessa') . ' Ganda:' . $this->session->userdata('ganda');
        } else {
            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Lessa:' . $this->session->userdata('lessa');
        }

        $this->load->view('layout/layout', $data);
    }

    public function tenantmodentry()
    {
        $this->dataswitch();
        $this->form_validation->set_rules('tenant_name', 'Tenant Name', 'trim|required');
        $this->form_validation->set_rules('tenants_father', 'Guardian Name', 'trim|required');
        $this->form_validation->set_rules('guard_rel', 'Relation', 'trim|required');
        $this->form_validation->set_rules('khatian_no', 'Khatian No', 'trim|required');
        $this->form_validation->set_rules('possession_area_b', 'Bigha', 'trim|required');
        $this->form_validation->set_rules('possession_area_k', 'Katha', 'trim|required');
        if (in_array($this->session->userdata('dist_code'), BARAK_VALLEY)) {
            $this->form_validation->set_rules('possession_area_l', 'Chatak', 'trim|required');
            $this->form_validation->set_rules('possession_area_g', 'Ganda', 'trim|required');
        } else {
            $this->form_validation->set_rules('possession_area_l', 'Lessa', 'trim|required');
        }
        $this->form_validation->set_rules('possession_length', 'Length of Possession', 'trim|required');
        $this->form_validation->set_rules('tenant_status', 'Status of Tenant', 'trim|required');

        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
        } else {

            $nrows = $this->Chithamodel->updatetenant();
            if ($nrows > 0) {
                echo json_encode(array('msg' => 'Data updated', 'st' => 1));
            } else {
                echo json_encode(array('msg' => 'Error in tenant entry', 'st' => 0));
            }
        }
    }

    // ******** newly by Masud 11/05/2022

    // subtenant list
    public function editSubTenantList()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $data['msg'] = '';
        $chkSubTenant = $this->Chithamodel->checksubtenantid();
        $data['tno'] = $chkSubTenant;

        if ($chkSubTenant > 1) {
            $data['tendet'] = $this->Chithamodel->getSubTenants();

            $data['locationname'] = $this->setLocationNames();
            $data['daghd'] = 'Dag no:' . $this->session->userdata('dag_no') . ' Patta no:' . $this->session->userdata('patta_no') . ' (' . $this->session->userdata('patta_type_name') . ')';
            if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
                $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Chatak:' . $this->session->userdata('lessa') . ' Ganda:' . $this->session->userdata('ganda');
            } else {
                $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Lessa:' . $this->session->userdata('lessa');
            }
        } else {
            $data['msg'] = "No Subtenant Record";
        }
        $data['_view'] = 'sub_tenant_edit';
        $this->load->view('layout/layout', $data);
    }

    // subtenant details (Update)
    public function subTenantModify($nm)
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $tn = explode("-", $nm);
        $tmod = $this->Chithamodel->idSubTenantDetails($nm);
        $tt = explode("$", $tmod);
        $data['tnid'] = $tt[0];
        $data['tnam'] = $tt[1];
        $data['tfam'] = $tt[2];
        $relnm = $tt[3];
        $data['tadd1'] = $tt[4];
        $data['tadd2'] = $tt[5];
        $data['tadd3'] = $tt[6];
        $data['relanm'] = $this->Chithamodel->relname();
        $data['relnm'] = $relnm;
        $data['_view'] = 'sub_tenant_modify';
        $data['locationname'] = $this->setLocationNames();
        $data['daghd'] = 'Dag no:' . $this->session->userdata('dag_no') . ' Patta no:' . $this->session->userdata('patta_no') . ' (' . $this->session->userdata('patta_type_name') . ')';
        if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Chatak:' . $this->session->userdata('lessa') . ' Ganda:' . $this->session->userdata('ganda');
        } else {
            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Lessa:' . $this->session->userdata('lessa');
        }
        $this->load->view('layout/layout', $data);
    }

    // subtenant details data update
    public function updateSubTenantDetails()
    {
        $this->dataswitch();
        $this->form_validation->set_rules('subTenantName', 'Subtenant Name', 'trim|required');
        $this->form_validation->set_rules('subTenantsFather', 'Guardian Name', 'trim|required');
        $this->form_validation->set_rules('guard_rel', 'Relation', 'trim|required');
        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
        } else {
            $nrows = $this->Chithamodel->updateSubTenant();
            if ($nrows > 0) {
                echo json_encode(array('msg' => 'Data updated', 'st' => 1));
            } else {
                echo json_encode(array('msg' => 'Error in subtenant entry', 'st' => 0));
            }
        }
    }

    // crop list
    public function editCropList()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $data['msg'] = '';
        $crop_id = $this->Chithamodel->checkcropid();

        $data['cId'] = $crop_id;
        if ($crop_id > 1) {
            $data['cropList'] = $this->Chithamodel->getCropList();
            $data['locationname'] = $this->setLocationNames();

            $data['daghd'] = 'Dag no:' . $this->session->userdata('dag_no') . ' Patta no:' . $this->session->userdata('patta_no') . ' (' . $this->session->userdata('patta_type_name') . ')';
            if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
                $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Chatak:' . $this->session->userdata('lessa') . ' Ganda:' . $this->session->userdata('ganda');
            } else {
                $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Lessa:' . $this->session->userdata('lessa');
            }
        } else {
            $data['msg'] = "No Crop Record";
        }
        $data['_view'] = 'crop_edit_list';
        $this->load->view('layout/layout', $data);
    }

    // crop details (Update)
    public function cropModify($crId)
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $cropDetails = $this->Chithamodel->cropDetailsWithId($crId);

        $data['cropD'] = $cropDetails;
        $data['cropnm'] = $this->Chithamodel->cropname();
        $data['cropnm'] = $this->Chithamodel->cropname();
        $data['crpnm'] = $this->input->post('cropname',TRUE);
        $data['cropcat'] = $this->Chithamodel->cropcat();
        $data['crpcat'] = $this->input->post('cropcatg',TRUE);
        $data['cropsn'] = $this->Chithamodel->cropseason();
        $data['crpsn'] = $this->input->post('cropsn',TRUE);
        $data['watersrc'] = $this->Chithamodel->watersource();
        $data['waters'] = $this->input->post('watersrc',TRUE);
        $data['cropId'] = $cropDetails->crop_sl_no;
        $landDetails = $this->Chithamodel->landDetails();
        $data['_view'] = 'crop_details_modify';
        $data['locationname'] = $this->setLocationNames();
        $data['daghd'] = 'Dag no:' . $this->session->userdata('dag_no') . ' Patta no:' . $this->session->userdata('patta_no') . ' (' . $this->session->userdata('patta_type_name') . ')';
        if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
            $landInLessa = $landDetails->crop_land_area_g + $landDetails->dag_area_lc * 20 + $landDetails->dag_area_k * 320 + $landDetails->dag_area_b * 6400;
            $data['landInLessa'] = $landInLessa;
            $data['landType'] = 'Ganda';

            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Chatak:' . $this->session->userdata('lessa') . ' Ganda:' . $this->session->userdata('ganda');
        } else {
            $landInLessa = $landDetails->dag_area_lc + $landDetails->dag_area_k * 20 + $landDetails->dag_area_b * 100;
            $data['landInLessa'] = $landInLessa;
            $data['landType'] = 'Lessa';

            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Lessa:' . $this->session->userdata('lessa');
        }
        $this->load->view('layout/layout', $data);
    }

    // crop details data update
    public function updateCropDetailsData()
    {
        $this->dataswitch();
        $this->form_validation->set_rules('cropslno', 'Crop Serial Id', 'trim|required');
        $this->form_validation->set_rules('yearno', 'Year no', 'trim|required');
        $this->form_validation->set_rules('cropname', 'Crop Name', 'trim|required');
        $this->form_validation->set_rules('cropname', 'Crop Category', 'trim|required');
        $this->form_validation->set_rules('cropseason', 'Crop Season', 'trim|required');
        $this->form_validation->set_rules('sourcewater', 'Water Source', 'trim|required');
        $this->form_validation->set_rules('croparea_b', 'Dag Area Bigha ', 'trim|required|numeric');
        $this->form_validation->set_rules('croparea_k', 'Dag Area Katha ', 'callback_kathavalue|trim|required|numeric');
        $this->form_validation->set_rules('croparea_lc', 'Dag Area Lessa ', 'callback_lessavalue|trim|required|numeric');

        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
            return;
        } else {
            $bighaF = intval($this->input->post('croparea_b',TRUE));
            $kathaF = intval($this->input->post('croparea_k',TRUE));
            $lessaF = intval($this->input->post('croparea_lc',TRUE));
            $cropId = intval($this->input->post('cropslno',TRUE));
            $gandaF = 0;

            $landDetails = $this->Chithamodel->landDetails();
            $year = intval($this->input->post('yearno',TRUE));
            $landDetailsInCrop = $this->Chithamodel->landDetailsInCrop($year);
            $landDetailsInNonCrop = $this->Chithamodel->landDetailsInNonCrop($year);

            $bigha = 0;
            $katha = 0;
            $lessa = 0;
            $ganda = 0;
            $bigha2 = 0;
            $katha2 = 0;
            $lessa2 = 0;
            $ganda2 = 0;
            $useLand = 0;
            $useLand1 = 0;
            $useLand2 = 0;
            $remainingLand = 0;
            foreach ($landDetailsInCrop as $mm) {
                if ($mm->crop_sl_no != $cropId) {
                    $bigha += $mm->crop_land_area_b;
                    $katha += $mm->crop_land_area_k;
                    $lessa += $mm->crop_land_area_lc;
                    $ganda += $mm->crop_land_area_g;
                }
            }
            foreach ($landDetailsInNonCrop as $nn) {
                $bigha2 += $nn->noncrop_land_area_b;
                $katha2 += $nn->noncrop_land_area_k;
                $lessa2 += $nn->noncrop_land_area_lc;
                $ganda2 += $nn->noncrop_land_area_g;
            }
            if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
                $useLand1 = $bigha * 6400 + $katha * 320 + $lessa * 20 + $ganda;
                $useLand2 = $bigha2 * 6400 + $katha2 * 320 + $lessa2 * 20 + $ganda2;
                $useLand = $useLand1 + $useLand2;
                $landInLessa = $landDetails->crop_land_area_g + $landDetails->dag_area_lc * 20 + $landDetails->dag_area_k * 320 + $landDetails->dag_area_b * 6400;
                $remainingLand = $landInLessa - $useLand;

                $addLand = $bighaF * 6400 + $kathaF * 320 + $lessaF * 20 + $gandaF;
            } else {
                $useLand1 = $bigha * 100 + $katha * 20 + $lessa;
                $useLand2 = $bigha2 * 100 + $katha2 * 20 + $lessa2;
                $useLand = $useLand1 + $useLand2;
                $landInLessa = $landDetails->dag_area_lc + $landDetails->dag_area_k * 20 + $landDetails->dag_area_b * 100;
                $remainingLand = $landInLessa - $useLand;
                if ($kathaF > 4) {
                    echo json_encode(array('msg' => 'Error in non agricultural entry Katha is more the 4', 'st' => 0));
                    return;
                }
                if ($lessaF > 19.9) {
                    echo json_encode(array('msg' => 'Error in non agricultural entry Lessa is more the 19.9', 'st' => 0));
                    return;
                }
                $addLand = $bighaF * 100 + $kathaF * 20 + $lessaF;
            }
            if ($remainingLand < $addLand) {
                echo json_encode(array('msg' => 'Error in non agricultural entry land is more then Total Remaining Land', 'st' => 0));
                return;
            }
            $nrows = $this->Chithamodel->updateCropDetails();
            if ($nrows > 0) {
                echo json_encode(array('msg' => 'Data Updated', 'st' => 1));
                return;
            } else {
                echo json_encode(array('msg' => 'Error in crop entry', 'st' => 0));
                return;
            }
        }
    }

    // non crop list
    public function editNonCropList()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $data['msg'] = '';
        $nonCropId = $this->Chithamodel->checknoncropid();

        $data['ncId'] = $nonCropId;
        if ($nonCropId > 1) {
            $data['nonCropList'] = $this->Chithamodel->getNonCropList();

            $data['locationname'] = $this->setLocationNames();

            $data['daghd'] = 'Dag no:' . $this->session->userdata('dag_no') . ' Patta no:' . $this->session->userdata('patta_no') . ' (' . $this->session->userdata('patta_type_name') . ')';
            if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
                $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Chatak:' . $this->session->userdata('lessa') . ' Ganda:' . $this->session->userdata('ganda');
            } else {
                $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Lessa:' . $this->session->userdata('lessa');
            }
        } else {
            $data['msg'] = "No Crop Record";
        }
        $data['_view'] = 'non_crop_edit_list';

        $this->load->view('layout/layout', $data);
    }

    // non crop details (Update)
    public function nonCropModify($ncId)
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $nonCropDetails = $this->Chithamodel->nonCropDetailsWithId($ncId);

        $data['nonCrop'] = $nonCropDetails;
        $data['ncropnm'] = $this->Chithamodel->ncropname();
        $data['ncropnm1'] = $this->input->post('ncropcode',TRUE);
        $data['nonCropId'] = $nonCropDetails->noncrop_use_id;
        $landDetails = $this->Chithamodel->landDetails();

        $data['_view'] = 'non_crop_details_modify';
        $data['locationname'] = $this->setLocationNames();
        $data['daghd'] = 'Dag no:' . $this->session->userdata('dag_no') . ' Patta no:' . $this->session->userdata('patta_no') . ' (' . $this->session->userdata('patta_type_name') . ')';
        if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
            $landInLessa = $landDetails->crop_land_area_g + $landDetails->dag_area_lc * 20 + $landDetails->dag_area_k * 320 + $landDetails->dag_area_b * 6400;
            $data['landInLessa'] = $landInLessa;
            $data['landType'] = 'Ganda';

            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Chatak:' . $this->session->userdata('lessa') . ' Ganda:' . $this->session->userdata('ganda');
        } else {
            $landInLessa = $landDetails->dag_area_lc + $landDetails->dag_area_k * 20 + $landDetails->dag_area_b * 100;
            $data['landInLessa'] = $landInLessa;
            $data['landType'] = 'Lessa';

            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Lessa:' . $this->session->userdata('lessa');
        }
        $this->load->view('layout/layout', $data);
    }

    // non crop data update
    public function updateNonCropDetailsData()
    {
        $this->dataswitch();
        $this->form_validation->set_rules('ncropslno', 'Non crop Id', 'trim|required');
        $this->form_validation->set_rules('yearno', 'Year no', 'trim|required');
        $this->form_validation->set_rules('ncropcode', 'Type of Usage', 'trim|required');
        $this->form_validation->set_rules('ncroparea_b', 'Dag Area Bigha ', 'trim|required|numeric');
        $this->form_validation->set_rules('ncroparea_k', 'Dag Area Katha ', 'callback_kathavalue|trim|required|numeric');
        $this->form_validation->set_rules('ncroparea_lc', 'Dag Area Lessa ', 'callback_lessavalue|trim|required|numeric');

        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
            return;
        } else {
            $bighaF = intval($this->input->post('ncroparea_b',TRUE));
            $kathaF = intval($this->input->post('ncroparea_k',TRUE));
            $lessaF = intval($this->input->post('ncroparea_lc',TRUE));
            $nonCropId = intval($this->input->post('ncropslno',TRUE));
            $gandaF = 0;

            $landDetails = $this->Chithamodel->landDetails();
            $year = intval($this->input->post('yearno',TRUE));
            $landDetailsInCrop = $this->Chithamodel->landDetailsInCrop($year);
            $landDetailsInNonCrop = $this->Chithamodel->landDetailsInNonCrop($year);

            $bigha = 0;
            $katha = 0;
            $lessa = 0;
            $ganda = 0;
            $bigha2 = 0;
            $katha2 = 0;
            $lessa2 = 0;
            $ganda2 = 0;
            $useLand = 0;
            $useLand1 = 0;
            $useLand2 = 0;
            $remainingLand = 0;
            foreach ($landDetailsInCrop as $mm) {

                $bigha += $mm->crop_land_area_b;
                $katha += $mm->crop_land_area_k;
                $lessa += $mm->crop_land_area_lc;
                $ganda += $mm->crop_land_area_g;
            }
            foreach ($landDetailsInNonCrop as $nn) {
                if ($mm->noncrop_use_id != $nonCropId) {
                    $bigha2 += $nn->noncrop_land_area_b;
                    $katha2 += $nn->noncrop_land_area_k;
                    $lessa2 += $nn->noncrop_land_area_lc;
                    $ganda2 += $nn->noncrop_land_area_g;
                }
            }
            if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
                $useLand1 = $bigha * 6400 + $katha * 320 + $lessa * 20 + $ganda;
                $useLand2 = $bigha2 * 6400 + $katha2 * 320 + $lessa2 * 20 + $ganda2;
                $useLand = $useLand1 + $useLand2;
                $landInLessa = $landDetails->crop_land_area_g + $landDetails->dag_area_lc * 20 + $landDetails->dag_area_k * 320 + $landDetails->dag_area_b * 6400;
                $remainingLand = $landInLessa - $useLand;

                $addLand = $bighaF * 6400 + $kathaF * 320 + $lessaF * 20 + $gandaF;
            } else {
                $useLand1 = $bigha * 100 + $katha * 20 + $lessa;
                $useLand2 = $bigha2 * 100 + $katha2 * 20 + $lessa2;
                $useLand = $useLand1 + $useLand2;
                $landInLessa = $landDetails->dag_area_lc + $landDetails->dag_area_k * 20 + $landDetails->dag_area_b * 100;
                $remainingLand = $landInLessa - $useLand;
                if ($kathaF > 4) {
                    echo json_encode(array('msg' => 'Error in non agricultural entry Katha is more the 4', 'st' => 0));
                    return;
                }
                if ($lessaF > 19.9) {
                    echo json_encode(array('msg' => 'Error in non agricultural entry Lessa is more the 19.9', 'st' => 0));
                    return;
                }
                $addLand = $bighaF * 100 + $kathaF * 20 + $lessaF;
            }
            if ($remainingLand < $addLand) {
                echo json_encode(array('msg' => 'Error in non agricultural entry land is more then Total Remaining Land', 'st' => 0));
                return;
            }
            $nrows = $this->Chithamodel->updateNonCropDetails();
            if ($nrows > 0) {
                echo json_encode(array('msg' => 'Data Updated', 'st' => 1));
            } else {
                echo json_encode(array('msg' => 'Error in Non agri entry', 'st' => 0));
            }
        }
    }

    // fruit list
    public function editFruitList()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $data['msg'] = '';
        $fruitId = $this->Chithamodel->checkfruitid();

        $data['fruitId'] = $fruitId;
        if ($fruitId > 1) {
            $data['fruitList'] = $this->Chithamodel->getFruitList();

            $data['locationname'] = $this->setLocationNames();

            $data['daghd'] = 'Dag no:' . $this->session->userdata('dag_no') . ' Patta no:' . $this->session->userdata('patta_no') . ' (' . $this->session->userdata('patta_type_name') . ')';
            if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
                $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Chatak:' . $this->session->userdata('lessa') . ' Ganda:' . $this->session->userdata('ganda');
            } else {
                $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Lessa:' . $this->session->userdata('lessa');
            }
        } else {
            $data['msg'] = "No Fruit Record";
        }
        $data['_view'] = 'fruit_edit_list';

        $this->load->view('layout/layout', $data);
    }

    // fruit details (Update)
    public function fruitModify($fId)
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $fruitDetails = $this->Chithamodel->fruitDetailsWithId($fId);

        $data['fruitD'] = $fruitDetails;
        $data['fruitnm'] = $this->Chithamodel->fruitname();
        $data['fruitnm1'] = $this->input->post('frname',TRUE);
        $data['fruitId'] = $fruitDetails->fruit_plant_id;

        $data['_view'] = 'fruit_details_modify';
        $data['locationname'] = $this->setLocationNames();
        $data['daghd'] = 'Dag no:' . $this->session->userdata('dag_no') . ' Patta no:' . $this->session->userdata('patta_no') . ' (' . $this->session->userdata('patta_type_name') . ')';
        if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Chatak:' . $this->session->userdata('lessa') . ' Ganda:' . $this->session->userdata('ganda');
        } else {
            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Lessa:' . $this->session->userdata('lessa');
        }
        $this->load->view('layout/layout', $data);
    }

    // fruit data update
    public function updateFruitDetailsData()
    {
        $this->dataswitch();
        $this->form_validation->set_rules('frplantid', 'Non Fruit Id', 'trim|required');
        $this->form_validation->set_rules('frname', 'Fruit Name', 'trim|required');
        $this->form_validation->set_rules('fplantno', 'Fruit plant no', 'required|numeric');

        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
            return;
        } else {
            $nrows = $this->Chithamodel->updateFruitDetails();
            if ($nrows > 0) {
                echo json_encode(array('msg' => 'Data Updated', 'st' => 1));
            } else {
                echo json_encode(array('msg' => 'Error in Fruit entry', 'st' => 0));
            }
        }
    }

    // get crop + Non-crop land details
    public function getLandDetailsInCrop()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $this->form_validation->set_rules('year', 'Year', 'trim|required');
        $year = intval($this->input->post('year',TRUE));

        $landDetailsInCrop = $this->Chithamodel->landDetailsInCrop($year);
        $landDetailsInNonCrop = $this->Chithamodel->landDetailsInNonCrop($year);

        $bigha = 0;
        $katha = 0;
        $lessa = 0;
        $ganda = 0;
        $bigha2 = 0;
        $katha2 = 0;
        $lessa2 = 0;
        $ganda2 = 0;
        $useLand = 0;
        $useLand1 = 0;
        $useLand2 = 0;
        foreach ($landDetailsInCrop as $mm) {
            $bigha += $mm->crop_land_area_b;
            $katha += $mm->crop_land_area_k;
            $lessa += $mm->crop_land_area_lc;
            $ganda += $mm->crop_land_area_g;
        }
        foreach ($landDetailsInNonCrop as $nn) {
            $bigha2 += $nn->noncrop_land_area_b;
            $katha2 += $nn->noncrop_land_area_k;
            $lessa2 += $nn->noncrop_land_area_lc;
            $ganda2 += $nn->noncrop_land_area_g;
        }
        if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
            $useLand1 = $bigha * 6400 + $katha * 320 + $lessa * 20 + $ganda;
            $useLand2 = $bigha2 * 6400 + $katha2 * 320 + $lessa2 * 20 + $ganda2;
            $useLand = $useLand1 + $useLand2;
        } else {
            $useLand1 = $bigha * 100 + $katha * 20 + $lessa;
            $useLand2 = $bigha2 * 100 + $katha2 * 20 + $lessa2;
            $useLand = $useLand1 + $useLand2;
        }

        echo json_encode($useLand);
    }

    //////////upload documents////////
    public function uploadDocument()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $distcode = $this->session->userdata('dcode');

        $data['districts'] = $this->Chithamodel->districtdetails($distcode);
        $data['_view'] = 'document_upload/location_select';

        $this->load->view('layout/layout', $data);
    }

    public function uploadDocumentReport()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');

        // $data['loc_code']=$loc_code=$this->Chithamodel->allData();
        $this->load->model('MisModel');

        $data['all_data'] = $this->MisModel->getDistrictNameUuid();
        //var_dump($data);

        $data['_view'] = 'document_upload/upload_report';

        $this->load->view('layout/layout', $data);
    }

    public function pattatype()
    {
        // $this->dataswitch();
        $data = [];
        $dis = $this->input->post('dis',TRUE);
        $this->dataswitch($dis);
        $formdata = $this->Chithamodel->getPattaType();
        foreach ($formdata as $value) {
            $data['test'][] = $value;
        }

        echo json_encode($data['test']);
        // echo json_encode($data);
    }

    public function villUuid()
    {
        // $this->dataswitch();
        $data = [];
        $dis = $this->input->post('dis',TRUE);
        $this->dataswitch();
        $subdiv = $this->input->post('subdiv',TRUE);
        $cir = $this->input->post('cir',TRUE);
        $mza = $this->input->post('mza',TRUE);
        $lot = $this->input->post('lot',TRUE);
        $vill = $this->input->post('vill',TRUE);
        $formdata = $this->Chithamodel->getVillageuuid($dis, $subdiv, $cir, $mza, $lot, $vill);
        // foreach ($formdata as $value) {
        //     $data = $value;
        // }
        $data = $formdata;

        echo json_encode($data);
        // echo json_encode($data);
    }

    //////////
    public function uploadSupportiveDocs()
    {
        $val = $this->input->post();
        // var_dump($val);
        // exit;
        $this->dataswitch();
        $flag = $val['flag'];
        $loc_code = $val['loc_code'];
        $dag = $val['dag'];
        $doc1 = $val['doc1'];
        $doc2 = $val['doc2'];
        $doc3 = $val['doc3'];
        $doc4 = $val['doc4'];
        $doc5 = $val['doc5'];
        $uuid = $val['uuid'];
        $user = $val['user'];
        //var_dump($flag);

        if ($loc_code == null || $loc_code == '' || empty($loc_code)) {
            $validation['img_upload'] = false;
            echo json_encode($validation);
            return;
        }

        $folder = 'docupload/' . $uuid . '/' . $dag . '/';

        $name = (($flag == 1) ? 'doc1_file' : (($flag == 2) ? 'doc2_file' : (($flag == 3) ? 'doc3_file' : (($flag == 4) ? 'doc4_file' : (($flag == 5) ? 'doc5_file' : 'null')))));

        $sl = (($flag == 1) ? '1' : (($flag == 2) ? '2' : (($flag == 3) ? '3' : (($flag == 4) ? '4' : (($flag == 5) ? '5' : 'null')))));

        $file_name = (($flag == 1) ? $doc1 : (($flag == 2) ? $doc2 : (($flag == 3) ? $doc3 : (($flag == 4) ? $doc4 : (($flag == 5) ? $doc5 : 'null')))));

        $ext = pathinfo($_FILES[$name]['name'], PATHINFO_EXTENSION);
        $_FILES[$name]['name'] = $file_name . '_' . $sl . '.' . $ext;

        if (!file_exists(NOK_UPLOAD_PATH . $folder)) {
            mkdir(NOK_UPLOAD_PATH . $folder, 0777, true);
            $path = NOK_UPLOAD_PATH . $folder;
        } else {
            $path = NOK_UPLOAD_PATH . $folder;
        }
        //echo $path;
        $config = [
            'upload_path' => $path,
            'allowed_types' => FILE_TYPE,
            'max_size' => MAX_SIZE,
        ];
        $FILES_TYPE_VALIDATION_ARR = explode('|', FILE_TYPE);
        $checkFileExt = false;
        foreach ($FILES_TYPE_VALIDATION_ARR as $file_type) {
            if ($ext == $file_type) {
                $checkFileExt = true;
                break;
            }
        }
        $validation = null;
        //log_message('error',json_encode($_FILES[$name]['size']));
        if (!$checkFileExt) {
            $validation['error'][] = array('message' => ' Only allowed types ' . FILE_TYPE . '.');
        } else if ($_FILES[$name]['size'] > (MAX_SIZE * 1024)) {
            $validation['error'][] = array('message' => ' Larger file size selected.');
        } else {
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            //var_dump($this->db);

            $count = $this->db->query("SELECT * FROM supportive_document where loc_code=? and dag_no =? and doc_flag = ? and uuid=? ", array($loc_code, $dag, $flag, $uuid))->num_rows();
            // var_dump($count);
            //  exit;

            if ($count == 0) {
                if ($this->upload->do_upload($name)) {
                    $up = $this->upload->data();
                    $img = [
                        'file_name' => $file_name,
                        'user_code' => $user,
                        'file_type' => $up['file_type'],
                        'file_path' => $path . $file_name . '_' . $sl . $up['file_ext'],
                        'date_entry' => date('Y-m-d h:i:s'),
                        'fetch_file_name' => $file_name . '_' . $sl . $up['file_ext'],
                        'loc_code' => $loc_code,
                        'dag_no' => $dag,
                        'doc_flag' => $flag,
                        'uuid' => $uuid,
                    ];
                    $ins = $this->db->insert('supportive_document', $img);
                    //echo $this->db->last_query();
                    if ($ins == true) {
                        $id = $this->db->query("SELECT * FROM supportive_document WHERE loc_code=? AND dag_no=? and uuid= ?", array($loc_code, $dag, $uuid))->row();
                        $validation['img_upload'] = true;
                        $validation['flag_set'] = $flag;
                        $validation['doc_flag'] = $flag;
                        $validation['dag_no'] = $id->dag_no;
                        $validation['uuid'] = $id->uuid;
                        $validation['filename'] = $file_name;
                    } else {
                        $validation['img_upload'] = false;
                    }
                } //end do upload
                else {
                    $validation['img_upload'] = false;
                }
            } // end count if

            else { //overwrite previous one

                $validation['doc_update'] = true;

                $file = $this->db->query("SELECT * FROM supportive_document where loc_code=? and  dag_no =? and doc_flag= ? and uuid =?", array($loc_code, $dag, $flag, $uuid))->row()->file_path;
                // echo $file;
                // exit;
                unlink($file);
                if ($this->upload->do_upload($name)) {
                    $up = $this->upload->data();
                    $overwrite = [
                        'file_name' => $file_name,
                        'file_type' => $up['file_type'],
                        'file_path' => $path . $file_name . '_' . $sl . $up['file_ext'],
                        'date_entry' => date('Y-m-d h:i:s'),
                        'fetch_file_name' => $file_name . '_' . $sl . $up['file_ext'],
                    ];
                    $this->db->where(['loc_code' => $loc_code, 'doc_flag' => $flag, 'dag_no' => $dag]);
                    $this->db->update('supportive_document', $overwrite);
                    if ($this->db->affected_rows() != 1) //if no updation made
                    {
                        $validation['img_upload'] = false;
                    } else {
                        $id = $this->db->query("SELECT * FROM supportive_document WHERE loc_code=? AND dag_no=? and uuid= ?", array($loc_code, $dag, $uuid))->row();

                        $validation['img_upload'] = true;
                        $validation['flag_set'] = $flag;
                        $validation['doc_flag'] = $flag;
                        $validation['dag_no'] = $id->dag_no;
                        $validation['uuid'] = $id->uuid;
                        $validation['filename'] = $file_name;
                    }
                }
            }
        }

        echo json_encode($validation);
    }

    public function removeSupportiveDocs()
    {
        $this->dataswitch();
        $flag = $this->input->post('flag',TRUE);
        $doc1 = $this->input->post('doc1',TRUE);
        $doc2 = $this->input->post('doc2',TRUE);
        $doc3 = $this->input->post('doc3',TRUE);
        $doc4 = $this->input->post('doc4',TRUE);
        $dag = $this->input->post('dag',TRUE);
        $loc_code = $this->input->post('loc_code',TRUE);
        $uuid = $this->input->post('uuid',TRUE);

        $getFileDetails = $this->db->query("SELECT fetch_file_name, file_path,file_name FROM supportive_document WHERE loc_code=? and doc_flag=? and dag_no= ? and uuid =?", array($loc_code, $flag, $dag, $uuid))->row();

        // var_dump($getFileDetails[0]->file_name);
        // exit;
        if ($getFileDetails) {
            $file_name = (($flag == 1) ? $getFileDetails->file_name : (($flag == 2) ? $getFileDetails->file_name : (($flag == 3) ? $getFileDetails->file_name : (($flag == 4) ? $getFileDetails->file_name : (($flag == 5) ? $getFileDetails->file_name : 'null')))));

            $getFile = $this->db->query("SELECT fetch_file_name, file_path,doc_flag,dag_no,uuid FROM supportive_document WHERE loc_code=? AND file_name=? and doc_flag = ? and dag_no=? and uuid =?", array($loc_code, $file_name, $flag, $dag, $uuid))->row();
            $file_path = $getFile->file_path;
            $delete = $this->db->query("DELETE FROM supportive_document WHERE doc_flag=? and uuid =? and dag_no= ? ", array($getFile->doc_flag, $getFile->uuid, $getFile->dag_no));
            // echo $this->db->last_query();
            // exit;

            if ($delete == true) {
                unlink($file_path);
                $validation['flag'] = $flag;
            }
        } else {
            $file_name = (($flag == 1) ? $getFileDetails->file_name : (($flag == 2) ? $getFileDetails->file_name : (($flag == 3) ? $getFileDetails->file_name : (($flag == 4) ? $getFileDetails->file_name : (($flag == 5) ? $getFileDetails->file_name : 'null')))));

            $getFile = $this->db->query("SELECT fetch_file_name, file_path FROM supportive_document WHERE loc_code=? AND file_name=? and doc_flag =? and uuid =?", array($loc_code, $file_name, $flag, $uuid))->row();
            $delete = $this->db->query("DELETE FROM supportive_document WHERE doc_flag=? and uuid =? and dag_no= ? ", array($getFile->doc_flag, $getFile->uuid, $getFile->dag_no));

            if ($delete == true) {
                unlink($getFile->file_path);
                $validation['flag'] = $flag;
            }
        }
        echo json_encode($validation);
    }

    public function downloadDocuments()
    {
        $this->dataswitch();
        $doc_flag = $this->input->get('doc_flag');
        $uuid = $this->input->get('uuid');
        $dag_no = $this->input->get('dag_no');

        if (isset($doc_flag, $uuid, $dag_no)) {
            $result = $this->db->query("SELECT * FROM supportive_document WHERE doc_flag=? and uuid =? and dag_no= ?", array($doc_flag, $uuid, $dag_no))->row_array();
            //"C:\\xampp\\htdocs\\DharitreeSVN_demo\\"
            $file = $result['file_path'];
            log_message("error", 'DOwnloaded file path: ' . json_encode($file));
            $content_type = $result['file_type'];
            header('Content-Type: ' . $content_type);
            header('Content-Length: ' . filesize($file));
            ob_clean();
            echo file_get_contents($file);
        } else {
            echo "No Data Found..";
        }
    }

    public function viewDownloads()
    {

        $this->dataswitch();

        $uuid = $this->input->get('uuid');
        $data['dag_no'] = $dag_no = $this->input->get('dag_no');

        $district = $this->db->query("select dist_code,subdiv_code,cir_code,mouza_pargona_code,lot_no,vill_townprt_code from   location where  uuid='$uuid'");
        $loc_code = $district->row();
        $this->load->model('MisModel');

        $districtdata = $this->MisModel->getDistrictName($loc_code->dist_code);
        $subdivdata = $this->MisModel->getSubDivName($loc_code->dist_code, $loc_code->subdiv_code);
        $circledata = $this->MisModel->getCircleName($loc_code->dist_code, $loc_code->subdiv_code, $loc_code->cir_code);
        $mouzadata = $this->MisModel->getMouzaName($loc_code->dist_code, $loc_code->subdiv_code, $loc_code->cir_code, $loc_code->mouza_pargona_code);
        $lotdata = $this->MisModel->getLotName($loc_code->dist_code, $loc_code->subdiv_code, $loc_code->cir_code, $loc_code->mouza_pargona_code, $loc_code->lot_no);
        $villagedata = $this->MisModel->getVillageName($loc_code->dist_code, $loc_code->subdiv_code, $loc_code->cir_code, $loc_code->mouza_pargona_code, $loc_code->lot_no, $loc_code->vill_townprt_code);
        $data['namedata'] = array_merge($districtdata, $subdivdata, $circledata, $mouzadata, $lotdata, $villagedata);

        $doc1_id = $this->db->query("SELECT doc_flag,file_name,dag_no,uuid FROM supportive_document WHERE doc_flag='1' and dag_no= ? and uuid= ?", array($dag_no, $uuid));
        if ($doc1_id->num_rows() > 0) {
            $data['doc1_id'] = $doc1_id->row();
        }

        $doc2_id = $this->db->query("SELECT doc_flag,file_name,dag_no,uuid FROM supportive_document WHERE doc_flag='2' and dag_no= ? and uuid= ? ", array($dag_no, $uuid));
        if ($doc2_id->num_rows() > 0) {
            $data['doc2_id'] = $doc2_id->row();
        }

        $doc3_id = $this->db->query("SELECT doc_flag,file_name,dag_no,uuid FROM supportive_document WHERE doc_flag='3' and dag_no= ? and uuid= ? ", array($dag_no, $uuid));
        if ($doc3_id->num_rows() > 0) {
            $data['doc3_id'] = $doc3_id->row();
        }

        $doc4_id = $this->db->query("SELECT doc_flag,file_name,dag_no,uuid FROM supportive_document WHERE doc_flag='4' and dag_no= ? and uuid = ? ", array($dag_no, $uuid));
        if ($doc4_id->num_rows() > 0) {
            $data['doc4_id'] = $doc4_id->row();
        }

        $doc5_id = $this->db->query("SELECT doc_flag,file_name,dag_no,uuid FROM supportive_document WHERE doc_flag='5' and dag_no= ? and uuid = ? ", array($dag_no, $uuid));
        if ($doc5_id->num_rows() > 0) {
            $data['doc5_id'] = $doc5_id->row();
        }

        $data['_view'] = 'document_upload/view_files';
        $this->load->view('layout/layout', $data);
    }

    public function pattanoDetails()
    {

        $data = [];
        $dis = $this->input->post('dis',TRUE);
        $this->dataswitch();
        $subdiv = $this->input->post('subdiv',TRUE);
        $cir = $this->input->post('cir',TRUE);
        $mza = $this->input->post('mza',TRUE);
        $lot = $this->input->post('lot',TRUE);
        $vill = $this->input->post('vill',TRUE);

        $location = $dis . '_' . $subdiv . '_' . $cir . '_' . $mza . '_' . $lot . '_' . $vill;
        $formdata = $this->Chithamodel->getPattaNo($dis, $subdiv, $cir, $mza, $lot, $vill);
        foreach ($formdata as $value) {
            $data['test'][] = $value;
        }
        $data['location'] = $location;
        // echo json_encode($data['test']);
        echo json_encode($data);
    }

    /** generate dag chitha */
    public function generateDagChitha()
    {
        $this->load->model('chitha/DharChithaModel');
        $this->dataswitch();
        if (isset($_GET['dag'])) {
            $dag = $this->input->get('dag');
        }
        $district_code = $this->input->get('dist');
        $subdivision_code = $this->input->get('sub_div');
        $circlecode = $this->input->get('cir');
        $mouzacode = $this->input->get('m');
        $lot_code = $this->input->get('l');
        $village_code = $this->input->get('v');
        $patta_code = $this->input->get('p');
        $dag_no_lower = $dag . '00';
        $dag_no_upper = $dag . '00';

        $dist_name = $this->utilityclass->getDistrictName($district_code);
        $subdiv_name = $this->utilityclass->getSubDivName($district_code, $subdivision_code);
        $cir_name = $this->utilityclass->getCircleName($district_code, $subdivision_code, $circlecode);
        $mouza_pargona_code_name = $this->utilityclass->getMouzaName($district_code, $subdivision_code, $circlecode, $mouzacode);
        $lot_no = $this->utilityclass->getLotLocationName($district_code, $subdivision_code, $circlecode, $mouzacode, $lot_code);
        $vill_townprt_code_name = $this->utilityclass->getVillageName($district_code, $subdivision_code, $circlecode, $mouzacode, $lot_code, $village_code);

        $data['location'] = array('dist' => $dist_name, 'sub' => $subdiv_name, 'cir' => $cir_name, 'mouza' => $mouza_pargona_code_name, 'lot' => $lot_no, 'vill' => $vill_townprt_code_name);

        $secondSelection = array('patta_code' => $patta_code, 'dag_no_lower' => $dag_no_lower, 'dag_no_upper' => $dag_no_upper);

        $pattatype['chithaPattatypeinfo'] = $this->DharChithaModel->getpattatype($patta_code);
        $this->session->set_userdata(array('patta_type' => $pattatype['chithaPattatypeinfo'][0]->patta_type));

        $chithainfo1['data'] = $this->DharChithaModel->getchithaDetailsALL($district_code, $subdivision_code, $circlecode, $mouzacode, $lot_code, $village_code, $dag_no_lower, $dag_no_upper);

        $maindataforchitha = array_merge($data, $secondSelection, $chithainfo1, $pattatype);

        if ($dag_no_upper == $dag_no_lower) {
            $maindataforchitha['single_dag'] = '1';
        } else {
            $maindataforchitha['single_dag'] = '0';
        }

        $maindataforchitha['uuid'] = $this->db->query("select uuid from location where dist_code='$district_code' and subdiv_code='$subdivision_code' and cir_code='$circlecode' and mouza_pargona_code='$mouzacode' and lot_no='$lot_code' and vill_townprt_code='$village_code'")->row();

        $this->load->helper('language');
        $district_code = $this->session->userdata('dist_code');
        if (in_array($district_code, BARAK_VALLEY)) {
            $this->lang->load("bengali", "bengali");
        } else {
            $this->lang->load("assamese", "assamese");
        }
        $maindataforchitha['_view'] = 'svamitva_card/chitha/chitha_view_occupiers';
        $this->load->view('layout/layout', $maindataforchitha);
    }

    /** get Map from Bhunaksha */
    public function getMapBhunaksha()
    {
        ini_set("pcre.backtrack_limit", "500000000");
        ini_set('max_execution_time', '0');
        $district_code = $this->input->get('dist');
        $subdivision_code = $this->input->get('sub_div');
        $circlecode = $this->input->get('cir');
        $mouzacode = $this->input->get('m');
        $lot_code = $this->input->get('l');
        $village_code = $this->input->get('v');
        $dag = $this->input->get('dag');

        $gis_code = $district_code . '_' . $subdivision_code . '_' . $circlecode . '_' . $mouzacode . '_' . $lot_code . '_' . $village_code;
        $gis_code = '07_01_01_02_05_10002';
        $plot_no = $dag;
        $plot_no = '100';

        $pdf_dir = NC_VILLAGE_MAP_PDF_DIR . $gis_code . '_' . $plot_no . '/';
        $pdf_name = $gis_code . '_' . $plot_no . '.pdf';
        $pdf_path = $pdf_dir . $pdf_name;

        if (file_exists(FCPATH . $pdf_path)) {
            header("Content-type: application/pdf");
            header("Content-Disposition: inline; filename=" . FCPATH . $pdf_path);
            @readfile(FCPATH . $pdf_path);
            return;
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, BHUNAKSHA_MAP_API_LINK);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt(
            $ch,
            CURLOPT_POSTFIELDS,
            "gis_code=$gis_code&plot_no=$plot_no"
        );

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close($ch);

        if ($server_output) {
            $content = base64_decode($server_output);
            mkdir($pdf_dir, 0777, true);
            file_put_contents($pdf_path, $content);

            header('Content-Type: application/pdf');
            echo $content;
        } else {
            echo '<h1 style="color:red"><center>Map not available.</center></h1>';
        }
    }
}
