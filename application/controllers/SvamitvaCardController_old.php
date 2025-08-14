<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';
class SvamitvaCardController extends CI_Controller
{
    use CommonTrait;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('RemarkModel');
        $this->load->model('ArchaeoHistoryModel');
        $this->load->model('SvamitvaModel');
        $this->load->helper('security');
        $this->load->helper(['form', 'url']);
    }

    public function location()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $distCode = $this->session->userdata('dcode');

        $data['districts'] = $this->Chithamodel->districtdetails($distCode);
        $data['blocks'] = $this->SvamitvaModel->getAllBlockDistrictWise($distCode);
        if ($this->session->userdata('gram_panch_code') and $this->session->userdata('current_url') == current_url()) {
            $dist = $this->session->userdata('sdcode');
            $subdiv = $this->session->userdata('ssubdiv_code');
            $circle = (string) $this->session->userdata('scir_code');
            $mza = (string) $this->session->userdata('smouza_pargona_code');
            $lot = (string) $this->session->userdata('slot_no');
            $vill = (string) $this->session->userdata('svill_townprt_code');
            $currentURL = (string) $this->session->userdata('current_url');
            $block = $this->session->userdata('block_code');
            $panch = $this->session->userdata('gram_panch_code');

            // dd($vill);
            $data['locations'] = $this->Chithamodel->getSessionLoc($dist, $subdiv, $circle, $mza, $lot, $vill);
            // dd($data['locations']);
            $data['current_url'] = $currentURL;
            $data['block'] = $block;
            $data['panch'] = $panch;
            $data['panches'] = $this->getRequestAllGramPanchayatByBlockCode($block, $dist);
        } else {
            $data['locations'] = null;
            $data['current_url'] = null;
            $data['block'] = null;
            $data['panch'] = null;
            $data['panches'] = null;
        }
        $data['_view'] = 'svamitva_card/location';
        $data['page_title'] = 'Select Location | Svamitva Chitha Entry/Edit';
        $data['page_header'] = 'Location Details For Svamitva';
        $data['breadcrumbs'] = '
        <li class="breadcrumb-item"><a href="#">Data Entry</a></li>
        <li class="breadcrumb-item"><a href="#">Svamitva Chitha Entry/Edit</a></li>
        <li class="breadcrumb-item active">Select Location Details</li>';
        $this->load->view('layout/layout', $data);
    }
    public function submitLocation()
    {
        $this->form_validation->set_rules('dist_code', 'District Name', 'trim|integer|required');
        $this->form_validation->set_rules('subdiv_code', 'Sub Division Name', 'trim|integer|required');
        $this->form_validation->set_rules('cir_code', 'Circle Name', 'trim|integer|required');
        $this->form_validation->set_rules('mouza_pargona_code', 'Mouza Name', 'trim|integer|required');
        $this->form_validation->set_rules('lot_no', 'Lot Number', 'trim|integer|required');
        $this->form_validation->set_rules('vill_townprt_code', 'Village Name', 'trim|integer|required');
        $this->form_validation->set_rules('block_code', 'Block Name', 'trim|integer|required');
        $this->form_validation->set_rules('gram_Panch_code', 'Gram Panchayat Name', 'trim|integer|required');

        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
            return;
        } else {
            $vill = $this->input->post('vill_townprt_code');
            $block = $this->input->post('block_code');
            $panch = $this->input->post('gram_Panch_code');

            $this->session->set_userdata('vill_townprt_code', $vill);

            $this->load->library('user_agent');
            $this->session->set_userdata('current_url', $this->agent->referrer());
            $this->session->set_userdata('sdcode', $this->session->userdata('dcode'));
            $this->session->set_userdata('ssubdiv_code', $this->session->userdata('subdiv_code'));
            $this->session->set_userdata('scir_code', $this->session->userdata('cir_code'));
            $this->session->set_userdata('smouza_pargona_code', $this->session->userdata('mouza_pargona_code'));
            $this->session->set_userdata('slot_no', $this->session->userdata('lot_no'));
            $this->session->set_userdata('svill_townprt_code', $this->session->userdata('vill_townprt_code'));
            $this->session->set_userdata('block_code', $block);
            $this->session->set_userdata('gram_panch_code', $panch);
            echo json_encode(array('msg' => 'Proceed for dag details entry', 'st' => 1));
        }
    }
    public function dagDetails()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $data['pattype'] = $this->Chithamodel->getPattaType();
        $data['pcode'] = $this->input->post('patta_type_code');
        $data['lclass'] = $this->Chithamodel->getLandclasscode();
        $data['lcode'] = $this->input->post('land_class_code');
        $data['locationname'] = $this->setLocationNames();

        $data['_view'] = 'svamitva_card/dag_details';
        $data['page_title'] = 'Dag Details | Svamitva Chitha Entry/Edit';
        $data['page_header'] = 'Enter Dag Details';
        $data['breadcrumbs'] = '
        <li class="breadcrumb-item"><a href="#">Data Entry</a></li>
        <li class="breadcrumb-item"><a href="#">Svamitva Chitha Entry/Edit</a></li>
        <li class="breadcrumb-item active">Dag Details</li>';
        $this->load->view('layout/layout', $data);
    }
    public function dagEntrySvamitva()
    {
        $this->dataswitch();

        $this->form_validation->set_rules('dag_no', 'Dag Number', 'callback_validvalue|trim|integer|required');
        $this->form_validation->set_rules('patta_type_code', 'Patta Type', 'trim|required|max_length[4]|numeric');
        $this->form_validation->set_rules('land_class_code', 'Land Class', 'trim|required|max_length[4]|min_length[1]');
        $this->form_validation->set_rules('dag_land_revenue', 'Dag Land Revenue', 'trim|required|max_length[10]|numeric');
        $this->form_validation->set_rules('dag_local_tax', 'Dag Local Tax', 'trim|required|max_length[10]|numeric');
        $this->form_validation->set_rules('dag_area_b', 'Dag Area Bigha ', 'trim|required|numeric');
        $this->form_validation->set_rules('dag_area_r', 'Dag Area In Are ', 'trim|required|numeric');
        $this->form_validation->set_rules('zonal_value', 'Zonal Value ', 'trim|required|numeric|greater_than_equal_to[0]');
        $this->form_validation->set_rules('revenue_paid', 'Revenue Paid Up To', 'trim|is_natural|min_length[4]');
        $this->form_validation->set_rules('police_station', 'Police Station', 'trim|max_length[200]');
        if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
            $this->form_validation->set_rules('dag_area_k', 'Dag Area Katha ', 'callback_kathavalue|trim|required|numeric');
            $this->form_validation->set_rules('dag_area_lc', 'Dag Area Chatak ', 'callback_chatakvalue|trim|required|numeric');
            $this->form_validation->set_rules('dag_area_g', 'Dag Area Ganda ', 'callback_gandavalue|trim|required|numeric');
        } else {
            $this->form_validation->set_rules('dag_area_k', 'Dag Area Katha ', 'callback_kathavalue|trim|required|numeric');
            $this->form_validation->set_rules('dag_area_lc', 'Dag Area Lessa ', 'callback_lessavalue|trim|required|numeric');
        }
        if (in_array($this->input->post('patta_type_code'), GovtPattaCode)) {
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
            $patta_type = $this->input->post('patta_type_code');
            $patta_no = $this->input->post('patta_no');
            $patta_no_old = $this->input->post('patta_no_old');
            $newpattach = $this->Chithamodel->checknewpatta($patta_type, $patta_no);
            $this->session->set_userdata('newpatta', $newpattach);

            $nrows = $this->Chithamodel->insertdag(true);
            if ($nrows > 0) {
                $patta_type = $this->input->post('patta_type_code');
                $patta_no = $this->input->post('patta_no');
                $patta_no_old = $this->input->post('patta_no_old');
                $dag_no = $this->input->post('dag_no');
                $old_dag_no = $this->input->post('old_dag_no');
                $bigha = $this->input->post('dag_area_b');
                $katha = $this->input->post('dag_area_k');
                $lessa = $this->input->post('dag_area_lc');
                $ganda = $this->input->post('dag_area_g');
                $are = $this->input->post('dag_area_r');
                $zonalValue = $this->input->post('zonal_value');
                $revenuePaid = $this->input->post('revenue_paid');

                $this->session->set_userdata('patta_type', $patta_type);
                $this->session->set_userdata('patta_no', $patta_no);
                $this->session->set_userdata('patta_no_old', $patta_no_old);
                $this->session->set_userdata('old_dag_no', $old_dag_no);
                $this->session->set_userdata('dag_no', $dag_no);
                $this->session->set_userdata('bigha', $bigha);
                $this->session->set_userdata('katha', $katha);
                $this->session->set_userdata('lessa', $lessa);
                $this->session->set_userdata('ganda', $ganda);
                $this->session->set_userdata('are', $are);

                $this->session->set_userdata('zonal_value', $zonalValue);
                $this->session->set_userdata('revenue_paid', $revenuePaid);
                echo json_encode(array('msg' => 'Proceed for occupier details entry', 'st' => 1));
            } else {
                echo json_encode(array('msg' => 'Error in dag entry', 'st' => 0));
                return;
            }
        }
    }
    public function occupants()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $data['locationname'] = $this->setLocationNames();

        $occupants = $this->SvamitvaModel->occupiers();

        $data['_view'] = 'svamitva_card/occupants';
        $data['occupants'] = $occupants;
        $data['page_title'] = 'Occupants | Svamitva Chitha Entry/Edit';
        $data['page_header'] = 'View Occupants';
        $data['breadcrumbs'] = '
        <li class="breadcrumb-item"><a href="#">Data Entry</a></li>
        <li class="breadcrumb-item"><a href="#">Svamitva Chitha Entry/Edit</a></li>
        <li class="breadcrumb-item active">Occupants</li>';
        $this->load->view('layout/layout', $data);
    }
    public function occupierDetails()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $data['locationname'] = $this->setLocationNames();
        $data['relname'] = $this->Chithamodel->relation();
        $data['classcode'] = $this->RemarkModel->getencroclasscode();
        $data['landusedfor'] = $this->RemarkModel->getencrolandusedfor();
        $data['master_casts'] = $this->RemarkModel->master_casts();
        $data['master_genders'] = $this->RemarkModel->master_genders();
        $data['master_occupations'] = $this->RemarkModel->master_occupations();
        $data['master_property_types'] = $this->RemarkModel->master_property_types();
        $encro_id = $this->RemarkModel->checkencroseid();
        $data['encroId'] = $encro_id;
        $data['_view'] = 'svamitva_card/occupier_details';
        $data['page_title'] = 'Occupier Details | Svamitva Chitha Entry/Edit';
        $data['page_header'] = 'Enter Occupier Details';
        $data['breadcrumbs'] = '
        <li class="breadcrumb-item"><a href="#">Data Entry</a></li>
        <li class="breadcrumb-item"><a href="#">Svamitva Chitha Entry/Edit</a></li>
        <li class="breadcrumb-item active">Occupier Details</li>';
        $this->load->view('layout/layout', $data);
    }
    public function editOccupierDetails($encro_id)
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $data['locationname'] = $this->setLocationNames();
        $data['relname'] = $this->Chithamodel->relation();
        $data['classcode'] = $this->RemarkModel->getencroclasscode();
        $data['landusedfor'] = $this->RemarkModel->getencrolandusedfor();
        $data['master_casts'] = $this->RemarkModel->master_casts();
        $data['master_genders'] = $this->RemarkModel->master_genders();
        $data['master_occupations'] = $this->RemarkModel->master_occupations();
        $data['master_property_types'] = $this->RemarkModel->master_property_types();
        $data['encroId'] = $encro_id;

        $occupant = $this->SvamitvaModel->occupierSingle($encro_id);
        if (!$occupant) {
            show_404('');
            return;
        }
        $families = $this->SvamitvaModel->families($encro_id);

        $data['occupant'] = $occupant;
        $data['families'] = $families;
        $data['_view'] = 'svamitva_card/edit_occupier_details';
        $data['page_title'] = 'Occupier Details | Svamitva Chitha Entry/Edit';
        $data['page_header'] = 'Enter Occupier Details';
        $data['breadcrumbs'] = '
        <li class="breadcrumb-item"><a href="#">Data Entry</a></li>
        <li class="breadcrumb-item"><a href="#">Svamitva Chitha Entry/Edit</a></li>
        <li class="breadcrumb-item active">Occupier Details</li>';
        $this->load->view('layout/layout', $data);
    }
    public function occupierEntry()
    {
        $this->dataswitch();
        //svamitva occupants are stored into chitha_rmk_encro table
        $this->form_validation->set_rules('occupier_name', 'Occupier Name', 'trim|required|max_length[100]');
        $this->form_validation->set_rules('occupier_guar_name', 'Guardian Name', 'trim|required|max_length[100]');
        $this->form_validation->set_rules('marital_status', 'Marital Status', 'trim|required');
        $this->form_validation->set_rules('gender', 'Gender', 'trim|required|max_length[6]');
        $this->form_validation->set_rules('category', 'Category ', 'trim|required');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|max_length[13]');
        $this->form_validation->set_rules('current_occupation', 'Current Occupation', 'trim|required');
        $this->form_validation->set_rules('address', 'Address', 'trim|required');
        $this->form_validation->set_rules('occupier_class_code', 'Nature of Occupier\'s Land', 'trim|required');
        $this->form_validation->set_rules('property_type_code', 'Property Type', 'trim|required');
        $this->form_validation->set_rules('occupier_land_b', 'Land Area(Bigha)', 'callback_areavalidate|trim|required');
        $this->form_validation->set_rules('occupier_land_k', 'Land Area(Katha)', 'trim|required');
        if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
            $this->form_validation->set_rules('occupier_land_lc', 'Land Area Chatak', 'trim|required');
            $this->form_validation->set_rules('occupier_land_g', 'Land Area Ganda', 'trim|required');
        } else {
            $this->form_validation->set_rules('occupier_land_lc', 'Land Area Lessa', 'trim|required');
        }
        $this->form_validation->set_rules('occupier_evicted_yn', 'Occupier Evicted', 'trim|required');
        $mm = $this->input->post('encro_evicted_yn');
        if ($mm == 'Y') {
            $this->form_validation->set_rules('encro_evic_date', 'Encroacher Evic Date', 'trim|required');
        }
        $marital_status = $this->input->post('marital_status');
        if ($marital_status == 'm') {
            $this->form_validation->set_rules('spouse_name', 'Spouse Name', 'trim|required|max_length[100]');
        }
        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
            return;
        } else {
            $dist_code = $this->session->userdata('dist_code');
            $subdiv_code = $this->session->userdata('subdiv_code');
            $cir_code = $this->session->userdata('cir_code');
            $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
            $lot_no = $this->session->userdata('lot_no');
            $vill_townprt_code = $this->session->userdata('vill_townprt_code');
            $dag_no = $this->session->userdata('dag_no');
            $encro_id = $this->input->post('encro_id');

            $occupier = array(
                'dist_code' => $dist_code,
                'subdiv_code' => $subdiv_code,
                'cir_code' => $cir_code,
                'mouza_pargona_code' => $mouza_pargona_code,
                'lot_no' => $lot_no,
                'vill_townprt_code' => $vill_townprt_code,
                'dag_no' => $dag_no,
                'encro_id' => $encro_id,
                'rmk_type_hist_no' => $this->input->post('encro_id'),
                'encro_name' => $this->input->post('occupier_name'),
                'encro_guardian' => $this->input->post('occupier_guar_name'),
                'encro_guar_relation' => $this->input->post('occupier_guar_relation'),
                'encro_add' => $this->input->post('address'),
                'encro_class_code' => $this->input->post('occupier_class_code'),
                // 'nature_land_code' => $this->input->post('land_used_for'),
                //'nature_land_code' => $this->input->post('nature_land_code'),
                //'encro_land_used_for' => $this->input->post('nature_land_code'),
                'property_type_code' => $this->input->post('property_type_code'),
                'encro_land_b' => $this->input->post('occupier_land_b'),
                'encro_land_k' => $this->input->post('occupier_land_k'),
                'encro_land_lc' => $this->input->post('occupier_land_lc'),
                'encro_land_g' => $this->input->post('occupier_land_lc_g'),
                'encro_evicted_yn' => $this->input->post('occupier_evicted_yn'),
                'marital_status' => $marital_status,
                'gender' => $this->input->post('gender'),
                'category' => $this->input->post('category'),
                'mobile' => $this->input->post('mobile'),
                'current_occupation' => $this->input->post('current_occupation'),
                'encro_land_g' => '00',
                'co_approval' => '00',
                'user_code' => $this->session->userdata('usercode'),
                'date_entry' => date("Y-m-d | h:i:sa"),
                'operation' => 'D',
                'other_description' => $this->input->post('other_description'),
                'approx_property_value' => $this->input->post('approx_property_value') ? $this->input->post('approx_property_value') : 0,
            );
            if ($this->input->post('dob')) {
                $occupier['dob'] = $this->input->post('dob');
            }
            if ($this->input->post('occupier_evic_date')) {
                $occupier['encro_evic_date'] = $this->input->post('occupier_evic_date');
            }
            if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
                $occupier['encro_land_g'] = $this->input->post('occupier_land_g');
            }
            if ($marital_status == 'm') {
                $occupier['spouse_name'] = $this->input->post('spouse_name');
            }
            $f_member_relatios = $this->input->post('f_meb_relation');
            $f_member_names = $this->input->post('f_meb_name');
            $f_member_genders = $this->input->post('f_mem_gender');
            $f_member_occupations = $this->input->post('f_meb_occupation');
            $this->RemarkModel->add_encroacherdeails($occupier);
            if ($this->db->trans_status() === false) {
                echo json_encode(array('msg' => 'Error in Ocuupier Entry', 'st' => 0));
                return;
            } else {
                if ($f_member_names) {
                    foreach ($f_member_names as $key => $f_member_name) {
                        if ($f_member_name) {
                            $f_member = [
                                'dist_code' => $dist_code,
                                'subdiv_code' => $subdiv_code,
                                'cir_code' => $cir_code,
                                'mouza_pargona_code' => $mouza_pargona_code,
                                'lot_no' => $lot_no,
                                'vill_townprt_code' => $vill_townprt_code,
                                'dag_no' => $dag_no,
                                'encro_id' => $encro_id,
                                'occupier_fmember_name' => $f_member_name,
                                'occupier_fmember_relation' => $f_member_relatios[$key],
                                'occupier_fmember_gender' => $f_member_genders[$key],
                                'occupier_fmember_occupation' => $f_member_occupations[$key],
                                'family_member_id' => $this->SvamitvaModel->maxFamilyId($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag_no, $encro_id),
                            ];
                            $this->SvamitvaModel->insert_encroacher_family($f_member);
                        }
                    }
                }
                if ($this->db->trans_status() === false) {
                    echo json_encode(array('msg' => 'Error in Ocuupier Entry', 'st' => 0));
                    return;
                } else {
                    echo json_encode(array('msg' => 'Data saved for Occupier Details', 'st' => 1));
                    return;
                }
            }
        }
    }
    public function occupierUpdate()
    {
        $this->dataswitch();
        $this->form_validation->set_rules('occupier_name', 'Occupier Name', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('occupier_guar_name', 'Guardian Name', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('marital_status', 'Marital Status', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('gender', 'Gender', 'trim|required|max_length[6]');
        $this->form_validation->set_rules('category', 'Category ', 'trim|required');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|max_length[13]');
        $this->form_validation->set_rules('current_occupation', 'Current Occupation', 'trim|required');
        $this->form_validation->set_rules('address', 'Address', 'trim|required');
        $this->form_validation->set_rules('occupier_class_code', 'Nature of Occupier\'s Land', 'trim|required');
        $this->form_validation->set_rules('property_type_code', 'Property Type', 'trim|required');
        $this->form_validation->set_rules('occupier_land_b', 'Land Area(Bigha)', 'callback_areaValidateOccupierUpdate|trim|required');
        $this->form_validation->set_rules('occupier_land_k', 'Land Area(Katha)', 'trim|required');
        if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
            $this->form_validation->set_rules('occupier_land_lc', 'Land Area Chatak', 'trim|required');
            $this->form_validation->set_rules('occupier_land_g', 'Land Area Ganda', 'trim|required');
        } else {
            $this->form_validation->set_rules('occupier_land_lc', 'Land Area Lessa', 'trim|required');
        }
        $this->form_validation->set_rules('occupier_evicted_yn', 'Occupier Evicted', 'trim|required');
        $mm = $this->input->post('encro_evicted_yn');
        if ($mm == 'Y') {
            $this->form_validation->set_rules('encro_evic_date', 'Encroacher Evic Date', 'trim|required');
        }
        $marital_status = $this->input->post('marital_status');
        if ($marital_status == 'm') {
            $this->form_validation->set_rules('spouse_name', 'Spouse Name', 'trim|required|max_length[100]');
        }
        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
            return;
        } else {
            $dist_code = $this->session->userdata('dist_code');
            $subdiv_code = $this->session->userdata('subdiv_code');
            $cir_code = $this->session->userdata('cir_code');
            $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
            $lot_no = $this->session->userdata('lot_no');
            $vill_townprt_code = $this->session->userdata('vill_townprt_code');
            $dag_no = $this->session->userdata('dag_no');
            $encro_id = $this->input->post('encro_id');
            $where = [
                'dist_code' => $dist_code,
                'subdiv_code' => $subdiv_code,
                'cir_code' => $cir_code,
                'mouza_pargona_code' => $mouza_pargona_code,
                'lot_no' => $lot_no,
                'vill_townprt_code' => $vill_townprt_code,
                'dag_no' => $dag_no,
                'encro_id' => $encro_id,
            ];
            $occupier = array(
                'encro_name' => $this->input->post('occupier_name'),
                'encro_guardian' => $this->input->post('occupier_guar_name'),
                'encro_guar_relation' => $this->input->post('occupier_guar_relation'),
                'encro_add' => $this->input->post('address'),
                'encro_class_code' => $this->input->post('occupier_class_code'),
                // 'nature_land_code' => $this->input->post('land_used_for'),
                //'nature_land_code' => $this->input->post('nature_land_code'),
                //'encro_land_used_for' => $this->input->post('nature_land_code'),
                'property_type_code' => $this->input->post('property_type_code'),
                'encro_land_b' => $this->input->post('occupier_land_b'),
                'encro_land_k' => $this->input->post('occupier_land_k'),
                'encro_land_lc' => $this->input->post('occupier_land_lc'),
                'encro_land_g' => $this->input->post('occupier_land_lc_g'),
                'encro_evicted_yn' => $this->input->post('occupier_evicted_yn'),
                'encro_evic_date' => $this->input->post('occupier_evicted_yn') == 'Y' ? $this->input->post('occupier_evic_date') : null,
                'marital_status' => $this->input->post('marital_status'),
                'gender' => $this->input->post('gender'),
                'category' => $this->input->post('category'),
                'mobile' => $this->input->post('mobile'),
                'current_occupation' => $this->input->post('current_occupation'),
                'encro_land_g' => '00',
                'user_code' => $this->session->userdata('usercode'),
                'date_entry' => date("Y-m-d | h:i:sa"),
                'other_description' => $this->input->post('other_description'),
                'approx_property_value' => $this->input->post('approx_property_value') ? $this->input->post('approx_property_value') : 0,
            );
            if ($this->input->post('dob')) {
                $occupier['dob'] = $this->input->post('dob');
            }
            if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
                $occupier['encro_land_g'] = $this->input->post('occupier_land_g');
            }
            if ($marital_status == 'm') {
                $occupier['spouse_name'] = $this->input->post('spouse_name');
            }
            $f_member_relatios = $this->input->post('f_meb_relation');
            $f_member_names = $this->input->post('f_meb_name');
            $f_member_genders = $this->input->post('f_mem_gender');
            $f_member_occupations = $this->input->post('f_meb_occupation');

            $this->db->trans_start();
            $this->db->where($where);
            $this->security->xss_clean($occupier);
            $this->db->update('chitha_rmk_encro', $occupier);

            if ($f_member_names) {
                foreach ($f_member_names as $key => $f_member_name) {
                    if ($f_member_name) {
                        $f_member = [
                            'dist_code' => $dist_code,
                            'subdiv_code' => $subdiv_code,
                            'cir_code' => $cir_code,
                            'mouza_pargona_code' => $mouza_pargona_code,
                            'lot_no' => $lot_no,
                            'vill_townprt_code' => $vill_townprt_code,
                            'dag_no' => $dag_no,
                            'encro_id' => $encro_id,
                            'occupier_fmember_name' => $f_member_name,
                            'occupier_fmember_relation' => $f_member_relatios[$key],
                            'occupier_fmember_gender' => $f_member_genders[$key],
                            'occupier_fmember_occupation' => $f_member_occupations[$key],
                            'family_member_id' => $this->SvamitvaModel->maxFamilyId($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag_no, $encro_id),
                        ];
                        $this->SvamitvaModel->insert_encroacher_family($f_member);
                    }
                }
            }
            $this->db->trans_complete();
            if ($this->db->trans_status() === false) {
                echo json_encode(array('msg' => 'Error in Ocuupier Entry', 'st' => 0));
                return;
            } else {
                echo json_encode(array('msg' => 'Data updated for Occupier Details', 'st' => 1));
                return;
            }
        }
    }
    public function deleteOccupant()
    {
        $this->dataswitch();
        $encro_id = $this->input->post('encro_id');
        $is_deleted = $this->SvamitvaModel->deleteOccupant($encro_id);
        if (!$is_deleted) {
            echo json_encode(array('msg' => 'Error in occupant deletion', 'st' => 0));
            return;
        } else {
            echo json_encode(array('msg' => 'Deleted successfully', 'st' => 1));
            return;
        }
    }
    public function areaValidateOccupierUpdate()
    {
        $encro_id = $this->input->post('encro_id');
        $encro_land_b = $this->input->post('occupier_land_b');
        $encro_land_k = $this->input->post('occupier_land_k');
        $encro_land_lc = $this->input->post('occupier_land_lc');
        $encro_land_g = $this->input->post('occupier_land_lc_g');
        $area_submitted = $this->SvamitvaModel->areaSubmitted($encro_land_b, $encro_land_k, $encro_land_lc, $encro_land_g);
        $dag_area = $this->SvamitvaModel->getDagArea();
        $area_occupied = $this->SvamitvaModel->dagAreaOccupied();
        $area_occupied_single_occupier = $this->SvamitvaModel->dagAreaOccupiedSingleOccupier($encro_id);

        // if ($area_submitted == 0) {
        //     $this->form_validation->set_message('areaValidateOccupierUpdate', 'Area can not be 0.');
        //     return false;
        // }
        if ((($area_submitted + $area_occupied) - $area_occupied_single_occupier) > $dag_area) {
            $this->form_validation->set_message('areaValidateOccupierUpdate', 'Dag area provided ' . round($area_submitted, 3) . ' are exceeding the available dag area ' . (round($dag_area - ($area_occupied - $area_occupied_single_occupier), 3)) . ' are');
            return false;
        } else {
            return true;
        }
    }
    public function areavalidate()
    {
        $encro_land_b = $this->input->post('occupier_land_b');
        $encro_land_k = $this->input->post('occupier_land_k');
        $encro_land_lc = $this->input->post('occupier_land_lc');
        $encro_land_g = $this->input->post('occupier_land_lc_g');
        $area_submitted = $this->SvamitvaModel->areaSubmitted($encro_land_b, $encro_land_k, $encro_land_lc, $encro_land_g);
        $dag_area = $this->SvamitvaModel->getDagArea();
        $area_occupied = $this->SvamitvaModel->dagAreaOccupied($encro_land_b, $encro_land_k, $encro_land_lc, $encro_land_g);

        // if ($area_submitted == 0) {
        //     $this->form_validation->set_message('areavalidate', 'Area can not be 0.');
        //     return false;
        // }
        if (($area_submitted + $area_occupied) > $dag_area) {
            $this->form_validation->set_message('areavalidate', 'Dag area provided ' . round($area_submitted, 3) . ' are exceeding the available dag area ' . (round($dag_area - $area_occupied, 3)) . ' are');
            return false;
        } else {
            return true;
        }
    }
    public function updateMember()
    {
        $this->dataswitch();
        $this->form_validation->set_rules('member_id_edit', 'Inavlid data', 'trim|required');
        $this->form_validation->set_rules('encro_id', 'Inavlid data', 'trim|required');
        $this->form_validation->set_rules('f_meb_name_edit', 'Name', 'trim|required|max_length[50]');
        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
            return;
        } else {
            $dist_code = $this->session->userdata('dist_code');
            $subdiv_code = $this->session->userdata('subdiv_code');
            $cir_code = $this->session->userdata('cir_code');
            $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
            $lot_no = $this->session->userdata('lot_no');
            $vill_townprt_code = $this->session->userdata('vill_townprt_code');
            $dag_no = $this->session->userdata('dag_no');
            $family_member_id = $this->input->post('member_id_edit');
            $encro_id = $this->input->post('encro_id');

            $where = [
                'dist_code' => $dist_code,
                'subdiv_code' => $subdiv_code,
                'cir_code' => $cir_code,
                'mouza_pargona_code' => $mouza_pargona_code,
                'lot_no' => $lot_no,
                'vill_townprt_code' => $vill_townprt_code,
                'dag_no' => $dag_no,
                'encro_id' => $encro_id,
                'family_member_id' => $family_member_id,
            ];
            $data = [
                'occupier_fmember_name' => $this->input->post('f_meb_name_edit'),
                'occupier_fmember_relation' => $this->input->post('f_meb_relation_edit'),
                'occupier_fmember_gender' => $this->input->post('f_mem_gender_edit'),
                'occupier_fmember_occupation' => $this->input->post('f_meb_occupation_edit'),
            ];
            $this->db->trans_start();
            $this->db->where($where);
            $this->db->update('chitha_pattadar_family', $data);

            $this->db->trans_complete();
            if ($this->db->trans_status() === false) {
                echo json_encode(array('msg' => 'Error in family member updation', 'st' => 0));
                return;
            } else {
                echo json_encode(array('msg' => 'Updated successfully', 'st' => 1));
                return;
            }
        }
    }
    public function deleteMember()
    {
        $this->dataswitch();
        $dist_code = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no = $this->session->userdata('lot_no');
        $vill_townprt_code = $this->session->userdata('vill_townprt_code');
        $dag_no = $this->session->userdata('dag_no');
        $family_member_id = $this->input->post('family_member_id');
        $encro_id = $this->input->post('encro_id');

        $where = [
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'mouza_pargona_code' => $mouza_pargona_code,
            'lot_no' => $lot_no,
            'vill_townprt_code' => $vill_townprt_code,
            'dag_no' => $dag_no,
            'encro_id' => $encro_id,
            'family_member_id' => $family_member_id,
        ];
        $this->db->trans_start();
        $this->db->where($where);
        $this->db->delete('chitha_pattadar_family');

        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            echo json_encode(array('msg' => 'Error in family member deletion', 'st' => 0));
            return;
        } else {
            echo json_encode(array('msg' => 'Deleted successfully', 'st' => 1));
            return;
        }
    }
    public function locationForSvamitvaCard()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $distCode = $this->session->userdata('dcode');

        $data['districts'] = $this->Chithamodel->districtdetails($distCode);
        $data['blocks'] = $this->SvamitvaModel->getAllBlockDistrictWise($distCode);

        $data['_view'] = 'svamitva_card/location_for_card';
        $data['page_title'] = 'Report | Svamitva Card View';
        $data['page_header'] = 'Location for Svamitva Card View';
        $data['breadcrumbs'] = '
        <li class="breadcrumb-item"><a href="#">Report</a></li>
        <li class="breadcrumb-item active" ><a href="#">Svamitva Crad View</a></li>';
        $this->load->view('layout/layout', $data);
    }
    public function getSvamitvaCard()
    {
        $this->dataswitch();
        $this->load->library('UtilityClass');

        $this->form_validation->set_rules('dist_code', 'Ditrict ', 'trim|integer|required');
        $this->form_validation->set_rules('subdiv_code', 'Subdiv ', 'trim|integer|required');
        $this->form_validation->set_rules('cir_code', 'Circle ', 'trim|integer|required');
        $this->form_validation->set_rules('mouza_pargona_code', 'Mouza', 'trim|integer|required');
        $this->form_validation->set_rules('lot_no', 'Lot No', 'trim|integer|required');
        $this->form_validation->set_rules('vill_townprt_code', 'Village', 'trim|integer|required');
        $this->form_validation->set_rules('dag_no', 'Dag No', 'trim|integer|required');

        if ($this->form_validation->run() == false) {
            $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
            $this->locationForSvamitvaCard();
            return;
        } else {
            $this->dataswitch();
            $dist_code = trim($this->input->post('dist_code'));
            $subdiv_code = trim($this->input->post('subdiv_code'));
            $circle_code = trim($this->input->post('cir_code'));
            $mouza_code = trim($this->input->post('mouza_pargona_code'));
            $lot_no = trim($this->input->post('lot_no'));
            $vill_code = trim($this->input->post('vill_townprt_code'));
            $dag_no = trim($this->input->post('dag_no'));

            $this->session->set_userdata('mouza_pargona_code', $mouza_code);
            $this->session->set_userdata('lot_no', $lot_no);
            $this->session->set_userdata('vill_townprt_code', $vill_code);
            $this->session->set_userdata('dag_no', $dag_no);

            $data['districtName'] = $this->SvamitvaModel->getDistrictName($dist_code);
            $data['subdivName'] = $this->SvamitvaModel->getSubDivName($dist_code, $subdiv_code);
            $data['circleName'] = $this->SvamitvaModel->getCircleName($dist_code, $subdiv_code, $circle_code);
            $data['mouzaName'] = $this->SvamitvaModel->getMouzaName($dist_code, $subdiv_code, $circle_code, $mouza_code);
            $data['lotName'] = $this->SvamitvaModel->getLotName($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no);
            $data['master_casts'] = $this->RemarkModel->master_casts();
            $data['master_genders'] = $this->RemarkModel->master_genders();
            $data['master_occupations'] = $this->RemarkModel->master_occupations();

            $data['villageName'] = $this->SvamitvaModel->getVillageName($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code);
            $data['dag_no'] = $dag_no;
            $occupiers_without_family = $this->SvamitvaModel->occupiers();
            $occupiers_with_family = [];
            foreach ($occupiers_without_family as $occupier) {
                $families = $this->SvamitvaModel->families($occupier->encro_id);
                $occupiers_with_family[] = [
                    'occupier' => $occupier,
                    'families' => $families,
                ];
            }

            $data['dag'] = $this->SvamitvaModel->getChithaBasic($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code, $dag_no);
            $data['blockGPName'] = $this->SvamitvaModel->getBlockName($dist_code, $data['dag']->block_code, $data['dag']->gp_code);
            $data['occupiers'] = $occupiers_with_family;
            $data['page_title'] = 'Report | Svamitva Card View';
            $data['page_header'] = 'Svamitva Card';
            $data['breadcrumbs'] = '
            <li class="breadcrumb-item"><a href="#">Report</a></li>
            <li class="breadcrumb-item active" ><a href="#">Svamitva Crad View</a></li>';

            $data['_view'] = 'svamitva_card/svamitva_card_view';
            $this->load->view('layout/layout', $data);
        }
    }
    public function getDags()
    {
        $this->dataswitch();
        $this->load->model('chitha/DharChithaModel');

        $dist_code = $this->input->post('dist_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $vill_townprt_code = $this->input->post('vill_townprt_code');

        $daginfo = $this->SvamitvaModel->getDags($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code);

        $json = array();

        foreach ($daginfo as $d) {
            $json[] = array('dag' => $d->dag_no, 'dag_no_int' => $d->dag_no_int);
        }
        echo json_encode($json);
    }
    private function setLocationNames()
    {
        $dist = $this->session->userdata('dist_code');
        $subdiv = $this->session->userdata('subdiv_code');
        $circle = $this->session->userdata('cir_code');
        $mouza = $this->session->userdata('mouza_pargona_code');
        $lot = $this->session->userdata('lot_no');
        $village = $this->session->userdata('vill_townprt_code');
        $block = $this->session->userdata('block_code');
        $panch = $this->session->userdata('gram_panch_code');

        $data = $this->Chithamodel->getlocationnames($dist, $subdiv, $circle, $mouza, $lot, $village, $block, $panch);
        return $data;
    }
    public function getAllGramPanchayatByBlockCode()
    {
        $this->dataswitch();
        $data[] = '';

        $blockCode = $this->input->post('id');
        $distCode = $this->session->userdata('dcode');
        $allPanch = $this->SvamitvaModel->getAllPanchByBlockCode($distCode, $blockCode);

        foreach ($allPanch as $value) {
            $data['panch_code'][] = $value;
        }
        echo json_encode($data['panch_code']);
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
        dag_nlrg_no,old_patta_no,zonal_value,revenue_paid_upto,
        dag_n_desc,dag_s_desc,dag_e_desc,dag_w_desc,
        dag_n_dag_no,dag_s_dag_no,dag_e_dag_no,dag_w_dag_no,police_station');
        $query = $this->db->get_where('chitha_basic', $where);
        if ($query->num_rows() > 0) {
            $row = $query->row();
            echo json_encode($row);
        } else {
            echo json_encode(null);
        }
    }

    public function getSvamitvaVillages()
    {
        $this->dataswitch();
        $data = [];
        $dist = $this->input->post('dis');
        $subdiv = $this->input->post('subdiv');
        $circle = $this->input->post('cir');
        $mouza = $this->input->post('mza');
        $lot = $this->input->post('lot');
        $this->session->set_userdata('lot_no', $lot);

        $svamitva_villages = callLandhubAPI('POST', 'getVillages_svamitva', [
            'dist_code' => $dist,
            'subdiv_code' => $subdiv,
            'cir_code' => $circle,
            'mouza_pargona_code' => $mouza,
            'lot_no' => $lot,
            'status' => '1',
        ]);
        $villages = $svamitva_villages != 'N' ? $svamitva_villages : [];

        echo json_encode($villages);
    }
    public function getRequestAllGramPanchayatByBlockCode($blockCode, $distCode)
    {
        $this->dataswitch();
        $data[] = '';
        $allPanch = $this->SvamitvaModel->getAllPanchByBlockCode($distCode, $blockCode);

        foreach ($allPanch as $value) {
            $data['panch_code'][] = $value;
        }
        return ($data['panch_code']);
    }
}
