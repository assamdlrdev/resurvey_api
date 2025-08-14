<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
ini_set('max_execution_time', 0);
include APPPATH . '/libraries/CommonTrait.php';

class ChithaReport extends CI_Controller
{
    use CommonTrait;
    public function index()
    {
        $district['base'] = $this->config->item('base_url');
        $this->load->helper('html');
        $this->load->view('header');
        $session = $this->session->userdata('username');
        if ($session == 'lm') {
            $this->load->view('menu/menu1');
        } elseif ($session == 'sk') {
            $this->load->view('menu/menu2');
        } elseif ($session == 'oc') {
            $this->load->view('menu/menu3');
        }
        $this->load->model('mutationmodel');
        $this->load->helper('html');
        $dist_code = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouzas = $this->mutationmodel->getMouzaJSON($dist_code, $subdiv_code, $cir_code);
        $district['d'] = $dist_code;
        $district['s'] = $subdiv_code;
        $district['c'] = $cir_code;
        $district['mouzas'] = $mouzas;
        //////////var_dump($mouzas);
        $data = $this->mutationmodel->getDistricts();
        $district['names'] = $data;

        //$this->load->view('menu/menu4');

        $this->load->view('chitha_report/report1', $district);
        // $this->load->view('footer');
    }

    public function districtDetails()
    {
        //var_dump($this->session->userdata('dist_code'));
        $this->load->helper('html');
        $this->load->view('header');
        $this->load->view('chitha_report/report1');
        $this->load->view('footer');
    }

    public function districtDetails_dc_lao()
    {
        //var_dump($this->session->userdata('dist_code'));
        $this->load->helper('html');
        $this->load->view('header');
        $this->load->view('chitha_report/reportdclao');
        $this->load->view('footer');
    }

    public function jamadistrictDetails_dc_lao()
    {
        //var_dump($this->session->userdata('dist_code'));
        $this->load->helper('html');
        $this->load->view('header');
        $this->load->view('chitha_report/JamaAutoUpdate');
        $this->load->view('footer');
    }

    public function generateDagChitha($is_redirect = false)
    {
        $this->dataswitch();

        if (!$is_redirect) {
            $dist_code = $this->input->post('dist_code');
            $subdiv_code = $this->input->post('subdiv_code');
            $circle_code = $this->input->post('cir_code');
            $mouza_code = $this->input->post('mouza_pargona_code');
            $lot_no = $this->input->post('lot_no');
            $vill_code = $this->input->post('vill_townprt_code');
            $this->load->library('user_agent');
            $this->session->set_userdata('vill_townprt_code', $vill_code);
            $this->session->set_userdata('current_url', $this->agent->referrer());
            $this->session->set_userdata('sdcode', $dist_code);
            $this->session->set_userdata('ssubdiv_code', $subdiv_code);
            $this->session->set_userdata('scir_code', $circle_code);
            $this->session->set_userdata('smouza_pargona_code', $mouza_code);
            $this->session->set_userdata('slot_no', $lot_no);
            $this->session->set_userdata('svill_townprt_code', $vill_code);
        } else {
            $dist_code = $this->session->userdata('chitha_dist_code');
            $subdiv_code = $this->session->userdata('chitha_subdiv_code');
            $circle_code = $this->session->userdata('chitha_cir_code');
            $mouza_code = $this->session->userdata('chitha_mouza_pargona_code');
            $lot_no = $this->session->userdata('chitha_lot_no');
            $vill_code = $this->session->userdata('chitha_vill_code');
        }

        $this->load->model('MisModel');

        $districtdata = $this->MisModel->getDistrictName($dist_code);
        $subdivdata = $this->MisModel->getSubDivName($dist_code, $subdiv_code);
        $circledata = $this->MisModel->getCircleName($dist_code, $subdiv_code, $circle_code);
        $mouzadata = $this->MisModel->getMouzaName($dist_code, $subdiv_code, $circle_code, $mouza_code);
        $lotdata = $this->MisModel->getLotName($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no);
        $villagedata = $this->MisModel->getVillageName($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code);
        $maindata['namedata'] = array_merge($districtdata, $subdivdata, $circledata, $mouzadata, $lotdata, $villagedata);

        $locationData = array('chitha_dist_code' => $dist_code, 'chitha_subdiv_code' => $subdiv_code, 'chitha_cir_code' => $circle_code, 'chitha_mouza_pargona_code' => $mouza_code, 'chitha_lot_no' => $lot_no, 'chitha_vill_code' => $vill_code);

        $this->session->set_userdata($locationData);

        $this->load->model('chitha/DharChithaModel');
        $daginfo = $this->DharChithaModel->getDagforchitha($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code, 0);
        //var_dump ($daginfo);
        $daginformation['dagrange'] = $daginfo;
        $pattatype = $this->DharChithaModel->pattatypeforchitha();
        $pattatypeinformation['pattatype'] = $pattatype;
        $chithadetailsmain = array_merge($daginformation, $pattatypeinformation, $maindata);

        $chithadetailsmain['base'] = $this->config->item('base_url');
        $chithadetailsmain['_view'] = 'chitha_report/report2';
        $this->load->view('layout/layout', $chithadetailsmain);

        //$this->load->view('chitha_report/report2', $chithadetailsmain);
        // $this->load->view('footer');
    }

    public function districtDetails32()
    {
        //var_dump($this->session->userdata('dist_code'));
        $this->load->helper('html');
        $this->load->view('header');
        $this->load->view('chitha_report/report1new');
        $this->load->view('footer');
    }

    public function districtDetails32dclao()
    {
        //var_dump($this->session->userdata('dist_code'));
        $this->load->helper('html');
        $this->load->view('header');
        $this->load->view('chitha_report/reportnewdclao');
        $this->load->view('footer');
    }

    public function generateDagChitha32()
    {
        $this->load->helper('html');
        $this->load->view('header');

        $dist_code = $this->input->post('dist_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $circle_code = $this->input->post('circle_code');
        $mouza_code = $this->input->post('mouza_code');
        $lot_no = $this->input->post('lot_no');
        $vill_code = $this->input->post('vill_code');

        $this->load->model('MisModel');

        $districtdata = $this->MisModel->getDistrictName($dist_code);
        $subdivdata = $this->MisModel->getSubDivName($dist_code, $subdiv_code);
        $circledata = $this->MisModel->getCircleName($dist_code, $subdiv_code, $circle_code);
        $mouzadata = $this->MisModel->getMouzaName($dist_code, $subdiv_code, $circle_code, $mouza_code);
        $lotdata = $this->MisModel->getLotName($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no);
        $villagedata = $this->MisModel->getVillageName($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code);
        $maindata['namedata'] = array_merge($districtdata, $subdivdata, $circledata, $mouzadata, $lotdata, $villagedata);

        $locationData = array('chitha_dist_code' => $dist_code, 'chitha_subdiv_code' => $subdiv_code, 'chitha_cir_code' => $circle_code, 'chitha_mouza_pargona_code' => $mouza_code, 'chitha_lot_no' => $lot_no, 'chitha_vill_code' => $vill_code);

        $this->session->set_userdata($locationData);

        $this->load->model('chitha/ChithaModel');
        $daginfo = $this->ChithaModel->getDagforchitha($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code, 0);
        //var_dump ($daginfo);
        $daginformation['dagrange'] = $daginfo;

        $pattatype = $this->ChithaModel->pattatypeforchitha();
        $pattatypeinformation['pattatype'] = $pattatype;
        $chithadetailsmain = array_merge($daginformation, $pattatypeinformation, $maindata);
        ////var_dump($chithadetailsmain);

        $this->load->view('chitha_report/report2new', $chithadetailsmain);
        $this->load->view('footer');
    }

    public function getDags($p)
    {
        $this->dataswitch();
        $this->load->model('chitha/DharChithaModel');

        $dist_code = $this->session->userdata('chitha_dist_code');
        $subdiv_code = $this->session->userdata('chitha_subdiv_code');
        $circle_code = $this->session->userdata('chitha_cir_code');
        $mouza_code = $this->session->userdata('chitha_mouza_pargona_code');
        $lot_no = $this->session->userdata('chitha_lot_no');
        $vill_code = $this->session->userdata('chitha_vill_code');

        if ($p == 0000) {
            $daginfo = $this->DharChithaModel->getDagforchithaAll($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code);
        } else {
            $daginfo = $this->DharChithaModel->getDagforchitha1111($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code, $p);
        }

        $json = array();

        foreach ($daginfo as $d) {
            $json[] = array('dag' => $d->dag_no, 'dag_no_int' => $d->dag_no_int);
        }
        echo json_encode($json);
    }

    public function getDagMiscCase($p, $dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code, $pno)
    {
        $this->load->model('chitha/ChithaModel');

        $daginfo = $this->ChithaModel->getDagforchithaMiscCase($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code, $p, $pno);
        $json = array();

        foreach ($daginfo as $d) {
            $json[] = array('dag' => $d->dag_no, 'dag_no_int' => $d->dag_no_int);
        }
        echo json_encode($json);
    }

    public function getDagslower($l, $p)
    {
        $this->dataswitch();
        $this->load->model('chitha/DharChithaModel');

        $dist_code = $this->session->userdata('chitha_dist_code');
        $subdiv_code = $this->session->userdata('chitha_subdiv_code');
        $circle_code = $this->session->userdata('chitha_cir_code');
        $mouza_code = $this->session->userdata('chitha_mouza_pargona_code');
        $lot_no = $this->session->userdata('chitha_lot_no');
        $vill_code = $this->session->userdata('chitha_vill_code');

        if ($p == 0000) {
            $daginfo = $this->DharChithaModel->getDagforALLchithalower($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code, $l);
        } else {
            $daginfo = $this->DharChithaModel->getDagforchithalower($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code, $l, $p);
        }

        $json = array();

        foreach ($daginfo as $d) {
            $json[] = array('dag' => $d->dag_no, 'dag_no_int' => $d->dag_no_int);
        }
        echo json_encode($json);
    }

    public function getPattalower($l, $p, $dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code)
    {

        $this->load->model('chitha/ChithaModel');

        if ($p == 0000) {
            $daginfo = $this->ChithaModel->getPattaforALLchithalower($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code, $l);
        } else {
            $daginfo = $this->ChithaModel->getPattaforchithalower($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code, $l, $p);
        }

        $json = array();

        foreach ($daginfo as $d) {
            $json[] = array('patta_no' => $d->patta_no);
        }
        echo json_encode($json);
    }

    public function generateChitha()
    {
        $this->dataswitch();
        $this->load->library('UtilityClass');
        $this->load->model('chitha/DharChithaModel');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->helper('security');
        $this->form_validation->set_rules('patta_code', 'Patta Type', 'required|trim|strip_tags|xss_clean');
        $this->form_validation->set_rules('dag_no_lower', 'Dag No From', 'required|trim|strip_tags|xss_clean');
        $this->form_validation->set_rules('dag_no_upper', 'Dag No To', 'required|trim|strip_tags|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', "Please enter all the fields correctly.");
            $this->generateDagChitha(true);
        } else {

            if (isset($_GET['case_no'])) {
                $case_no = $this->input->get('case_no');
                $district_code = $this->session->userdata('dist_code');
                $subdivision_code = $this->session->userdata('subdiv_code');
                $circlecode = $this->session->userdata('cir_code');
                if (isset($_GET['dag'])) {
                    $dag = $this->input->get('dag');
                }
                if ($case_no == '0') {
                    ////var_dump($this->session->all_userdata());
                    $district_code = $this->session->userdata('dist_code');
                    $subdivision_code = $this->session->userdata('subdiv_code');
                    $circlecode = $this->session->userdata('cir_code');
                    $mouzacode = $this->session->userdata('mouza_pargona_code');
                    $lot_code = $this->session->userdata('lot_no');
                    $village_code = $this->session->userdata('vill_code');
                    $patta_code = $this->session->userdata('patta_type_code');
                    $dag_no_lower = $this->session->userdata('dag_no');
                    $dag_no_upper = $this->session->userdata('dag_no');
                    $dag_no_lower = $dag_no_lower . '00';
                    $dag_no_upper = $dag_no_upper . '00';
                } elseif ($case_no == '2') {
                    //var_dump($this->session->all_userdata());
                    $district_code = $this->session->userdata('dist_code');
                    $subdivision_code = $this->session->userdata('subdiv_code');
                    $circlecode = $this->session->userdata('cir_code');
                    $mouzacode = $this->session->userdata('mouza_pargona_code');
                    $lot_code = $this->session->userdata('lot_no');
                    $village_code = $this->session->userdata('vill_townprt_code');
                    $patta_code = '0201';
                    //$dag_no_lower = $this -> session -> userdata('dag_no');
                    //$dag_no_upper = $this -> session -> userdata('dag_no');
                    $dag_no_lower = $dag . '00';
                    $dag_no_upper = $dag . '00';
                } elseif ($case_no == '3') {
                    //var_dump($this->session->all_userdata());
                    $district_code = $this->session->userdata('dist_code');
                    $subdivision_code = $this->session->userdata('subdiv_code');
                    $circlecode = $this->session->userdata('cir_code');
                    $mouzacode = $this->input->get('m');
                    $lot_code = $this->input->get('l');
                    $village_code = $this->input->get('v');
                    $patta_code = $this->input->get('p');
                    //$dag_no_lower = $this -> session -> userdata('dag_no');
                    //$dag_no_upper = $this -> session -> userdata('dag_no');
                    $dag_no_lower = $dag . '00';
                    $dag_no_upper = $dag . '00';
                } elseif ($case_no == '4') {
                    //var_dump($this->session->all_userdata());
                    $district_code = $this->input->get('dist');
                    $subdivision_code = $this->input->get('sub_div');
                    $circlecode = $this->input->get('cir');
                    $mouzacode = $this->input->get('m');
                    $lot_code = $this->input->get('l');
                    $village_code = $this->input->get('v');
                    $patta_code = $this->input->get('p');
                    //$dag_no_lower = $this -> session -> userdata('dag_no');
                    //$dag_no_upper = $this -> session -> userdata('dag_no');
                    $dag_no_lower = $dag . '00';
                    $dag_no_upper = $dag . '00';
                } elseif ($case_no == '1') {
                    //this is for land reclassification
                    $case_id = $this->input->get('case_id');
                    $proposal_no = $this->input->get('proposal_no');
                    $t_reclassification = $this->db->query("Select * from t_reclassification where proposal_no = '$proposal_no' and case_no = '$case_id'")->row();
                    $district_code = $t_reclassification->dist_code;
                    $subdivision_code = $t_reclassification->subdiv_code;
                    $circlecode = $t_reclassification->cir_code;
                    $mouzacode = $t_reclassification->mouza_pargona_code;
                    $lot_code = $t_reclassification->lot_no;
                    $village_code = $t_reclassification->vill_townprt_code;

                    $patta_code = $t_reclassification->patta_type_code;
                    $dag_no_lower = $t_reclassification->dag_no;
                    $dag_no_upper = $t_reclassification->dag_no;
                    $dag_no_lower = $dag_no_lower . '00';
                    $dag_no_upper = $dag_no_upper . '00';
                } else {
                    $petition_basic = $this->db->query("Select * from petition_basic where case_no = '$case_no' and dist_code='$district_code' and subdiv_code='$subdivision_code' and cir_code='$circlecode' ")->row();
                    $landdetails = $this->db->query("select dag_no,m_dag_area_b,m_dag_area_k,m_dag_area_lc,patta_no,patta_type_code from petition_dag_details where dist_code='$petition_basic->dist_code' and" . " subdiv_code='$petition_basic->subdiv_code' and cir_code='$petition_basic->cir_code' and " . "lot_no='$petition_basic->lot_no' and vill_townprt_code='$petition_basic->vill_townprt_code' and " . "mouza_pargona_code='$petition_basic->mouza_pargona_code' and petition_no='$petition_basic->petition_no'")->row_array();

                    $district_code = $petition_basic->dist_code;
                    $subdivision_code = $petition_basic->subdiv_code;
                    $circlecode = $petition_basic->cir_code;
                    $mouzacode = $petition_basic->mouza_pargona_code;
                    $lot_code = $petition_basic->lot_no;
                    $village_code = $petition_basic->vill_townprt_code;

                    $patta_code = $landdetails['patta_type_code'];
                    $dag_no_lower = $landdetails['dag_no'];
                    $dag_no_upper = $landdetails['dag_no'];
                    $dag_no_lower = $dag_no_lower . '00';
                    $dag_no_upper = $dag_no_upper . '00';
                }
            } else {

                $location = $this->utilityclass->getLocationFromSession();
                $district_code = $this->session->userdata('chitha_dist_code');
                $subdivision_code = $this->session->userdata('chitha_subdiv_code');
                $circlecode = $this->session->userdata('chitha_cir_code');
                $mouzacode = $this->session->userdata('chitha_mouza_pargona_code');
                $lot_code = $this->session->userdata('chitha_lot_no');
                $village_code = $this->session->userdata('chitha_vill_code');
                $patta_code = $this->input->post('patta_code');
                $dag_no_lower = $this->input->post('dag_no_lower');
                $dag_no_upper = $this->input->post('dag_no_upper');
            }

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

            //        if ($patta_code != '0000')
            //        {
            //            $chithainfo1['data'] = $this->DharChithaModel->getchithaDetails123($district_code, $subdivision_code, $circlecode, $mouzacode, $lot_code, $village_code, $dag_no_lower, $dag_no_upper, $patta_code);
            //
            //        } else {
            //              $chithainfo1['data'] = $this->DharChithaModel->getchithaDetailsALL($district_code, $subdivision_code, $circlecode, $mouzacode, $lot_code, $village_code, $dag_no_lower, $dag_no_upper);
            //        }

            $chithainfo1['data'] = $this->DharChithaModel->getchithaDetailsALL($district_code, $subdivision_code, $circlecode, $mouzacode, $lot_code, $village_code, $dag_no_lower, $dag_no_upper);

            $maindataforchitha = array_merge($data, $secondSelection, $chithainfo1, $pattatype);

            if ($dag_no_upper == $dag_no_lower) {
                $maindataforchitha['single_dag'] = '1';

            } else {
                $maindataforchitha['single_dag'] = '0';
            }

            $maindataforchitha['uuid'] = $this->db->query("select uuid from location where dist_code='$district_code' and subdiv_code='$subdivision_code' and cir_code='$circlecode' and mouza_pargona_code='$mouzacode' and lot_no='$lot_code' and vill_townprt_code='$village_code'")->row();
            //        printf('<pre>');
            //        print_r($maindataforchitha);
            //        print_r($innerdata58);
            //        die();

            $this->load->helper('language');
            $district_code = $this->session->userdata('dist_code');
            if (in_array($district_code, BARAK_VALLEY)) {
                $this->lang->load("bengali", "bengali");
            } else {
                $this->lang->load("assamese", "assamese");
            }
            $maindataforchitha['_view'] = 'chitha_report/saveChithaReport';
            $this->load->view('layout/layout', $maindataforchitha);
        }
    }

    public function modalgenerateChitha()
    {
        if (isset($_GET['case_no'])) {
            $case_no = $this->input->get('case_no');
            $district_code = $this->session->userdata('dist_code');
            $subdivision_code = $this->session->userdata('subdiv_code');
            $circlecode = $this->session->userdata('cir_code');
            if ($case_no) {
                $petition_basic = $this->db->query("Select * from petition_basic where case_no = '$case_no' and dist_code='$district_code' and subdiv_code='$subdivision_code' and cir_code='$circlecode'   ")->row();
                $landdetails = $this->db->query("select dag_no,m_dag_area_b,m_dag_area_k,m_dag_area_lc,patta_no,patta_type_code from petition_dag_details where dist_code='$petition_basic->dist_code' and" . " subdiv_code='$petition_basic->subdiv_code' and cir_code='$petition_basic->cir_code' and " . "lot_no='$petition_basic->lot_no' and vill_townprt_code='$petition_basic->vill_townprt_code' and " . "mouza_pargona_code='$petition_basic->mouza_pargona_code' and petition_no='$petition_basic->petition_no'")->row_array();

                $district_code = $petition_basic->dist_code;
                $subdivision_code = $petition_basic->subdiv_code;
                $circlecode = $petition_basic->cir_code;
                $mouzacode = $petition_basic->mouza_pargona_code;
                $lot_code = $petition_basic->lot_no;
                $village_code = $petition_basic->vill_townprt_code;

                $patta_code = $landdetails['patta_type_code'];
                $dag_no_lower = $landdetails['dag_no'];
                $dag_no_upper = $landdetails['dag_no'];
                $dag_no_lower = $dag_no_lower . '00';
                $dag_no_upper = $dag_no_upper . '00';
            } else {
                $location = $this->utilityclass->getLocationFromSession();
                ////var_dump($location);
                $district_code = $this->session->userdata('chitha_dist_code');
                $subdivision_code = $this->session->userdata('chitha_subdiv_code');
                $circlecode = $this->session->userdata('chitha_cir_code');
                $mouzacode = $this->session->userdata('chitha_mouza_pargona_code');
                $lot_code = $this->session->userdata('chitha_lot_no');
                $village_code = $this->session->userdata('chitha_vill_code');

                $patta_code = $this->input->post('patta_code');
                $dag_no_lower = $this->input->post('dag_no_lower');
                $dag_no_upper = $this->input->post('dag_no_upper');
            }
            //echo $dag_no_lower." and patta code".$dag_no_upper;
            $dist_name = $this->utilityclass->getDistrictName($district_code);
            $subdiv_name = $this->utilityclass->getSubDivName($district_code, $subdivision_code);
            $cir_name = $this->utilityclass->getCircleName($district_code, $subdivision_code, $circlecode);
            $mouza_pargona_code_name = $this->utilityclass->getMouzaName($district_code, $subdivision_code, $circlecode, $mouzacode);
            $lot_no = $this->utilityclass->getLotName($district_code, $subdivision_code, $circlecode, $mouzacode, $lot_code);
            $vill_townprt_code_name = $this->utilityclass->getVillageName($district_code, $subdivision_code, $circlecode, $mouzacode, $lot_code, $village_code);

            $data['location'] = array('dist' => $dist_name, 'sub' => $subdiv_name, 'cir' => $cir_name, 'mouza' => $mouza_pargona_code_name, 'lot' => $lot_no, 'vill' => $vill_townprt_code_name);
            $secondSelection = array('patta_code' => $patta_code, 'dag_no_lower' => $dag_no_lower, 'dag_no_upper' => $dag_no_upper);
            //$maindataforchitha = array_merge($data,$secondSelection);
            $this->load->model('chitha/ChithaModel');
            $pattatype['chithaPattatypeinfo'] = $this->ChithaModel->getpattatype($patta_code);
            //var_dump($pattatype);
            $this->session->set_userdata(array('patta_type' => $pattatype['chithaPattatypeinfo'][0]->patta_type));
            if ($patta_code != '0000') {
                $chithainfo1['data'] = $this->ChithaModel->getchithaDetails123($district_code, $subdivision_code, $circlecode, $mouzacode, $lot_code, $village_code, $dag_no_lower, $dag_no_upper, $patta_code);
                // echo'hiii';
                //var_dump($chithainfo1);
            } else {
                $chithainfo1['chithainfo'] = $this->ChithaModel->getchithaDetails2($district_code, $subdivision_code, $circlecode, $mouzacode, $lot_code, $village_code, $dag_no_lower, $dag_no_upper);
            }

            $maindataforchitha = array_merge($data, $secondSelection, $chithainfo1, $pattatype);
            //var_dump ($maindataforchitha);
            $content = $this->load->view('chitha_report/saveChithaReport', $maindataforchitha);
            /* $this->load->library('pdf');
            $this->pdf->load_view('chitha_report/saveChithaReport', $maindataforchitha,true);
            $this->pdf->render();
            $this->pdf->stream("welcome.pdf"); */
            //$this -> load -> view('footer');
        }
    }
    public function generateChithaCitizen()
    {
        $location = $this->utilityclass->getLocationFromSession();
        ////var_dump($location);
        $district_code = $this->session->userdata('dist_code');
        $subdivision_code = $this->session->userdata('subdiv_code');
        $circlecode = $this->session->userdata('cir_code');
        $mouzacode = $this->session->userdata('mouza_pargona_code');
        $lot_code = $this->session->userdata('lot_no');
        $village_code = $this->session->userdata('vill_townprt_code');
        $patta_code = $this->session->userdata('patta_type_code');
        $dag_no_lower = $this->input->post('dag_no') . "00";
        $dag_no_upper = $this->input->post('dag_no') . "00";

        $dist_name = $this->utilityclass->getDistrictName($district_code);
        $subdiv_name = $this->utilityclass->getSubDivName($district_code, $subdivision_code);
        $cir_name = $this->utilityclass->getCircleName($district_code, $subdivision_code, $circlecode);
        $mouza_pargona_code_name = $this->utilityclass->getMouzaName($district_code, $subdivision_code, $circlecode, $mouzacode);
        $lot_no = $this->utilityclass->getLotName($district_code, $subdivision_code, $circlecode, $mouzacode, $lot_code);
        $vill_townprt_code_name = $this->utilityclass->getVillageName($district_code, $subdivision_code, $circlecode, $mouzacode, $lot_code, $village_code);

        $data['location'] = array('dist' => $dist_name, 'sub' => $subdiv_name, 'cir' => $cir_name, 'mouza' => $mouza_pargona_code_name, 'lot' => $lot_no, 'vill' => $vill_townprt_code_name);

        //$data['loc']=$location;
        //var_dump($data);
        $this->load->helper('html');
        $this->load->view('header');

        //
        // echo  $patta_code.'<br>'.$dag_no_lower.'<br>'.$dag_no_upper;
        $secondSelection = array('patta_code' => $patta_code, 'dag_no_lower' => $dag_no_lower, 'dag_no_upper' => $dag_no_upper);
        //$maindataforchitha = array_merge($data,$secondSelection);
        //var_dump($secondSelection);
        $this->load->model('chitha/ChithaModel');
        $pattatype['chithaPattatypeinfo'] = $this->ChithaModel->getpattatype($patta_code);
        //var_dump($pattatype);
        $this->session->set_userdata(array('patta_type' => $pattatype['chithaPattatypeinfo'][0]->patta_type));
        if ($patta_code != '0000') {
            $chithainfo1['data'] = $this->ChithaModel->getchithaDetails123($district_code, $subdivision_code, $circlecode, $mouzacode, $lot_code, $village_code, $dag_no_lower, $dag_no_upper, $patta_code);
        } else {
            $chithainfo1['chithainfo'] = $this->ChithaModel->getchithaDetails2($district_code, $subdivision_code, $circlecode, $mouzacode, $lot_code, $village_code, $dag_no_lower, $dag_no_upper);
        }
        $maindataforchitha = array_merge($data, $secondSelection, $chithainfo1, $pattatype);
        //  var_dump($data);
        $this->load->view('chitha_report/saveChithaReport', $maindataforchitha);
        $this->load->view('footer');
    }

    public function generateChithaRegistration()
    {
        $district_code = $_GET['distcode'];
        //$this->input->post('dist_code');
        $subdivision_code = $_GET['subdivcode'];
        //$this->input->post('subdiv_code');
        $circlecode = $_GET['circlecode'];
        //$this->input->post('cir_code');
        $mouzacode = $_GET['mousacode'];
        //$this->input->post('mouza_pargona_code');
        $lot_code = $_GET['lotno'];
        //$this->input->post('lot_no');
        $village_code = $_GET['villcode'];
        //$this->input->post('vill_code');
        $patta_code = $_GET['pattatype'];
        //$this->input->post('patta_type_code');
        $dag_no_lower = $_GET['dagno'] * 100;
        //$this->input->post('dag_no');
        $dag_no_upper = $_GET['dagno'] * 100;
        //$this->input->post('dag_no');

        $dist_name = $this->utilityclass->getDistrictName($district_code);
        $subdiv_name = $this->utilityclass->getSubDivName($district_code, $subdivision_code);
        $cir_name = $this->utilityclass->getCircleName($district_code, $subdivision_code, $circlecode);
        $mouza_pargona_code_name = $this->utilityclass->getMouzaName($district_code, $subdivision_code, $circlecode, $mouzacode);
        $lot_no = $this->utilityclass->getLotName($district_code, $subdivision_code, $circlecode, $mouzacode, $lot_code);
        $vill_townprt_code_name = $this->utilityclass->getVillageName($district_code, $subdivision_code, $circlecode, $mouzacode, $lot_code, $village_code);

        $data['location'] = array('dist' => $dist_name, 'sub' => $subdiv_name, 'cir' => $cir_name, 'mouza' => $mouza_pargona_code_name, 'lot' => $lot_no, 'vill' => $vill_townprt_code_name);

        $secondSelection = array('patta_code' => $patta_code, 'dag_no_lower' => $dag_no_lower, 'dag_no_upper' => $dag_no_upper);

        $this->load->model('chitha/ChithaModel');

        $pattatype['chithaPattatypeinfo'] = $this->ChithaModel->getpattatype($patta_code);

        $this->session->set_userdata(array('patta_type' => $pattatype['chithaPattatypeinfo'][0]->patta_type));
        if ($patta_code != '0000') {
            $chithainfo1['data'] = $this->ChithaModel->getchithaDetails123($district_code, $subdivision_code, $circlecode, $mouzacode, $lot_code, $village_code, $dag_no_lower, $dag_no_upper, $patta_code);
        } else {
            $chithainfo1['chithainfo'] = $this->ChithaModel->getchithaDetails2($district_code, $subdivision_code, $circlecode, $mouzacode, $lot_code, $village_code, $dag_no_lower, $dag_no_upper);
        }

        $maindataforchitha = array_merge($data, $secondSelection, $chithainfo1, $pattatype);

        $content = $this->load->view('chitha_report/saveChithaReport', $maindataforchitha, true);

        header("Access-Control-Allow-Origin: *");

        echo json_encode(array('d' => $content));
    }

    public function generateChithaforSro()
    {
        //var_dump($this->input->get());
        //var_dump($this->session->all_userdata());

        $case_no = $this->input->get('case_no');

        $district_code = $this->session->userdata('dist_code');
        $subdivision_code = $this->session->userdata('subdiv_code');
        $circlecode = $this->session->userdata('cir_code');
        $mouzacode = $this->input->get('m');
        $lot_code = $this->input->get('l');
        $village_code = $this->input->get('v');
        $patta_code = $this->input->get('p');

        $dag = $this->input->get('dag');
        if (isset($_GET['case_no'])) {

            if (isset($_GET['dag'])) {
            }
            if ($case_no == '0') {
                ////var_dump($this->session->all_userdata());
                $district_code = $this->session->userdata('dist_code');
                $subdivision_code = $this->session->userdata('subdiv_code');
                $circlecode = $this->session->userdata('cir_code');
                $mouzacode = $this->session->userdata('mouza_pargona_code');
                $lot_code = $this->session->userdata('lot_no');
                $village_code = $this->session->userdata('vill_code');
                $patta_code = $this->session->userdata('patta_type_code');
                $dag_no_lower = $this->session->userdata('dag_no');
                $dag_no_upper = $this->session->userdata('dag_no');
                $dag_no_lower = $dag_no_lower . '00';
                $dag_no_upper = $dag_no_upper . '00';
            } elseif ($case_no == '2') {
                //var_dump($this->session->all_userdata());
                $district_code = $this->session->userdata('dist_code');
                $subdivision_code = $this->session->userdata('subdiv_code');
                $circlecode = $this->session->userdata('cir_code');
                $mouzacode = $this->session->userdata('mouza_pargona_code');
                $lot_code = $this->session->userdata('lot_no');
                $village_code = $this->session->userdata('vill_townprt_code');
                $patta_code = '0201';
                //$dag_no_lower = $this -> session -> userdata('dag_no');
                //$dag_no_upper = $this -> session -> userdata('dag_no');
                $dag_no_lower = $dag . '00';
                $dag_no_upper = $dag . '00';
            } elseif ($case_no == '3') {
                //var_dump($this->session->all_userdata());
                $district_code = $this->session->userdata('dist_code');
                $subdivision_code = $this->session->userdata('subdiv_code');
                $circlecode = $this->session->userdata('cir_code');

                //$dag_no_lower = $this -> session -> userdata('dag_no');
                //$dag_no_upper = $this -> session -> userdata('dag_no');
                $dag_no_lower = $dag . '00';
                $dag_no_upper = $dag . '00';
            } elseif ($case_no == '4') {
                //var_dump($this->session->all_userdata());
                $district_code = $this->input->get('dist');
                $subdivision_code = $this->input->get('sub_div');
                $circlecode = $this->input->get('cir');
                $mouzacode = $this->input->get('m');
                $lot_code = $this->input->get('l');
                $village_code = $this->input->get('v');
                $patta_code = $this->input->get('p');
                //$dag_no_lower = $this -> session -> userdata('dag_no');
                //$dag_no_upper = $this -> session -> userdata('dag_no');
                $dag_no_lower = $dag . '00';
                $dag_no_upper = $dag . '00';
            } elseif ($case_no == '1') {
                //this is for land reclassification
                $proposal_no = $this->input->get('proposal_no');
                $t_reclassification = $this->db->query("Select * from t_reclassification where proposal_no = '$proposal_no'")->row();
                $district_code = $t_reclassification->dist_code;
                $subdivision_code = $t_reclassification->subdiv_code;
                $circlecode = $t_reclassification->cir_code;
                $mouzacode = $t_reclassification->mouza_pargona_code;
                $lot_code = $t_reclassification->lot_no;
                $village_code = $t_reclassification->vill_townprt_code;

                $patta_code = $t_reclassification->patta_type_code;
                $dag_no_lower = $t_reclassification->dag_no;
                $dag_no_upper = $t_reclassification->dag_no;
                $dag_no_lower = $dag_no_lower . '00';
                $dag_no_upper = $dag_no_upper . '00';
            } else {
                $petition_basic = $this->db->query("Select * from petition_basic where case_no = '$case_no' and dist_code='$district_code' and subdiv_code='$subdivision_code' and cir_code='$circlecode' ")->row();
                $landdetails = $this->db->query("select dag_no,m_dag_area_b,m_dag_area_k,m_dag_area_lc,patta_no,patta_type_code from petition_dag_details where dist_code='$petition_basic->dist_code' and" . " subdiv_code='$petition_basic->subdiv_code' and cir_code='$petition_basic->cir_code' and " . "lot_no='$petition_basic->lot_no' and vill_townprt_code='$petition_basic->vill_townprt_code' and " . "mouza_pargona_code='$petition_basic->mouza_pargona_code' and petition_no='$petition_basic->petition_no'")->row_array();

                $district_code = $petition_basic->dist_code;
                $subdivision_code = $petition_basic->subdiv_code;
                $circlecode = $petition_basic->cir_code;
                $mouzacode = $petition_basic->mouza_pargona_code;
                $lot_code = $petition_basic->lot_no;
                $village_code = $petition_basic->vill_townprt_code;

                $patta_code = $landdetails['patta_type_code'];
                $dag_no_lower = $landdetails['dag_no'];
                $dag_no_upper = $landdetails['dag_no'];
                $dag_no_lower = $dag_no_lower . '00';
                $dag_no_upper = $dag_no_upper . '00';
            }
        } else {
            $location = $this->utilityclass->getLocationFromSession();
            ////var_dump($location);
            $district_code = $this->session->userdata('chitha_dist_code');
            $subdivision_code = $this->session->userdata('chitha_subdiv_code');
            $circlecode = $this->session->userdata('chitha_cir_code');
            $mouzacode = $this->session->userdata('chitha_mouza_pargona_code');
            $lot_code = $this->session->userdata('chitha_lot_no');
            $village_code = $this->session->userdata('chitha_vill_code');

            $patta_code = $this->input->post('patta_code');
            $dag_no_lower = $this->input->post('dag_no_lower');
            $dag_no_upper = $this->input->post('dag_no_upper');
        }

        $dist_name = $this->utilityclass->getDistrictName($district_code);
        $subdiv_name = $this->utilityclass->getSubDivName($district_code, $subdivision_code);
        $cir_name = $this->utilityclass->getCircleName($district_code, $subdivision_code, $circlecode);
        $mouza_pargona_code_name = $this->utilityclass->getMouzaName($district_code, $subdivision_code, $circlecode, $mouzacode);
        $lot_no = $this->utilityclass->getLotLocationName($district_code, $subdivision_code, $circlecode, $mouzacode, $lot_code);
        $vill_townprt_code_name = $this->utilityclass->getVillageName($district_code, $subdivision_code, $circlecode, $mouzacode, $lot_code, $village_code);

        $data['location'] = array('dist' => $dist_name, 'sub' => $subdiv_name, 'cir' => $cir_name, 'mouza' => $mouza_pargona_code_name, 'lot' => $lot_no, 'vill' => $vill_townprt_code_name);

        $secondSelection = array('patta_code' => $patta_code, 'dag_no_lower' => $dag_no_lower, 'dag_no_upper' => $dag_no_upper);

        $this->load->model('chitha/ChithaModel');
        $pattatype['chithaPattatypeinfo'] = $this->ChithaModel->getpattatype($patta_code);
        //var_dump($pattatype);
        $this->session->set_userdata(array('patta_type' => $pattatype['chithaPattatypeinfo'][0]->patta_type));
        if ($patta_code != '0000') {
            $chithainfo1['data'] = $this->ChithaModel->getchithaDetails123($district_code, $subdivision_code, $circlecode, $mouzacode, $lot_code, $village_code, $dag_no_lower, $dag_no_upper, $patta_code);
            //var_dump($chithainfo1);
        } else {
            $chithainfo1['data'] = $this->ChithaModel->getchithaDetailsALL($district_code, $subdivision_code, $circlecode, $mouzacode, $lot_code, $village_code, $dag_no_lower, $dag_no_upper);
            //$chithainfo1['data'] = $this->ChithaModel->getchithaDetails2($district_code, $subdivision_code, $circlecode, $mouzacode, $lot_code, $village_code, $dag_no_lower, $dag_no_upper);
            //var_dump($chithainfo1);
        }

        $maindataforchitha = array_merge($data, $secondSelection, $chithainfo1, $pattatype);
        //var_dump ($data);
        $this->load->helper('html');
        $this->load->view('header');
        $content = $this->load->view('chitha_report/saveChithaReport', $maindataforchitha);
        $this->load->view('footer');
    }
}
