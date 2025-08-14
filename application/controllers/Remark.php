<?php
include APPPATH . '/libraries/CommonTrait.php';

class Remark extends CI_Controller
{
    use CommonTrait;
    function __construct()
    {
        parent::__construct();
        //$this->load->model('UtilityModel');
        $this->load->model('RemarkModel');
        $this->load->model('ArchaeoHistoryModel');
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

    public function remarkHome()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $data['_view'] = 'rmk_home';
        $data['remark'] = $this->RemarkModel->getRemarktype();
        $data['locationname'] = $this->setLocationNames();
        $data['daghd'] = 'Dag no:' . $this->session->userdata('dag_no') . ' Patta no:' . $this->session->userdata('patta_no') . ' (' . $this->session->userdata('patta_type_name') . ')';
        if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Chatak:' . $this->session->userdata('lessa') . ' Ganda:' . $this->session->userdata('ganda');
        } else {
            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Lessa:' . $this->session->userdata('lessa');
        }

        $this->load->view('layout/layout', $data);
    }

    public function remarkHomeSubmit()
    {
        $this->dataswitch();
        $data = array();
        //$this->form_validation->set_rules('rmk_type_hist_no', 'Remark Type History Number', 'trim|integer|required');
        $this->form_validation->set_rules(
            'remark_type',
            'Remark Type',
            'trim|integer|required|in_list[01,02,03,04,06]'
        );
        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
        } else {
            $data = array(
                'dist_code' => $this->session->userdata('dist_code'),
                'subdiv_code' => $this->session->userdata('subdiv_code'),
                'cir_code' => $this->session->userdata('cir_code'),
                'mouza_pargona_code' => $this->session->userdata('mouza_pargona_code'),
                'lot_no' => $this->session->userdata('lot_no'),
                'vill_townprt_code' => $this->session->userdata('vill_townprt_code'),
                'dag_no' => $this->session->userdata('dag_no'),
                // 'rmk_type_hist_no' => $this->input->post('rmk_type_hist_no'),
                'remark_type' => $this->input->post('remark_type'),
            );
            $remark_type = $this->input->post('remark_type');

            if ($remark_type == '01') {
                echo json_encode(array('st' => 1, 'rt' => '01'));
            } else if ($remark_type == '02') {
                echo json_encode(array('st' => 1, 'rt' => '02'));
            } else if ($remark_type == '03') {
                echo json_encode(array('st' => 1, 'rt' => '03'));
            } elseif ($remark_type == '04') {
                echo json_encode(array('st' => 1, 'rt' => '04'));
            } elseif ($remark_type == '06') {
                echo json_encode(array('st' => 1, 'rt' => '06'));
            } else {
                echo json_encode(array('msg' => 'Error in Remark Type Selection', 'st' => 0));
            }
        }
    }


    // get Archaeological History page Masud Reza

    public function ArchaeologicalHistoryForm()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $dist = $this->session->userdata('dist_code');
        $sub = $this->session->userdata('subdiv_code');
        $circle = $this->session->userdata('cir_code');
        $mza  = $this->session->userdata('mouza_pargona_code');
        $lot  = $this->session->userdata('lot_no');
        $vill = $this->session->userdata('vill_townprt_code');
        $dag  = $this->session->userdata('dag_no');

        $data['codes'] = $this->ArchaeoHistoryModel->getArchoHistoryCode();
        $data['sl_id'] = $this->ArchaeoHistoryModel->checkArchaeoHistId();

        $data['_view'] = 'archaeological_history';
        $data['locationname'] = $this->setLocationNames();

        $this->load->view('layout/layout', $data);
    }



    public function kathavalue($str)
    {
        if ($str != '') {
            if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
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



    // save Archaeological History data Masud Reza
    public function saveArchaeologicalHistoricalData()
    {

        $this->dataswitch();
        $this->form_validation->set_rules('archHisId', 'Archaeological Historical Place Id', 'trim|integer|required|numeric');
        $this->form_validation->set_rules('archHistoPlace', 'Archaeological Historical Place Name', 'trim|required|numeric');
        $this->form_validation->set_rules('placeDescription', 'Description About The Place', 'trim|required|max_length[199]');

        $this->form_validation->set_rules('land_b', 'Area Bigha ', 'trim|required|numeric');
        if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
            $this->form_validation->set_rules('land_k', 'Area Katha ', 'callback_kathavalue|trim|required|numeric');
            $this->form_validation->set_rules('land_lc', 'Area Chetak ', 'callback_chatakvalue|trim|required|numeric');
            $this->form_validation->set_rules('land_g', 'Area Ganda ', 'callback_gandavalue|trim|required|numeric');
        } else {
            $this->form_validation->set_rules('land_k', 'Area Katha ', 'callback_kathavalue|trim|required|numeric');
            $this->form_validation->set_rules('land_lc', 'Area Lessa ', 'callback_lessavalue|trim|required|numeric');
        }
        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
            return;
        } else {

            $archoCode = $this->ArchaeoHistoryModel->getArchoHistoryCodeWithId($this->input->post('archHistoPlace'));
            $sl_id     = $this->ArchaeoHistoryModel->checkArchaeoHistId();

            $data = array(
                'dist_code'          => $this->session->userdata('dist_code'),
                'subdiv_code'        => $this->session->userdata('subdiv_code'),
                'cir_code'           => $this->session->userdata('cir_code'),
                'mouza_pargona_code' => $this->session->userdata('mouza_pargona_code'),
                'lot_no'             => $this->session->userdata('lot_no'),
                'vill_townprt_code'  => $this->session->userdata('vill_townprt_code'),
                'dag_no'             => $this->session->userdata('dag_no'),

                'archeo_sl_no'          => $sl_id,
                'archeo_hist_code'      => $archoCode->archeo_hist_code,
                'archeo_hist_site_desc' => $this->input->post('placeDescription'),
                'hist_land_area_b'      => $this->input->post('land_b'),
                'hist_land_area_k'      => $this->input->post('land_k'),
                'hist_land_area_lc'     => $this->input->post('land_lc'),
                'hist_land_area_g'      => $this->input->post('land_g'),

                'user_code' => $this->session->userdata('usercode'),
                'date_entry' => date("Y-m-d | h:i:sa"),
                'operation' => 'E',
            );

            $this->ArchaeoHistoryModel->insertArchHistorical($data);

            if ($this->db->trans_status() === FALSE) {
                echo json_encode(array('msg' => 'Error in Archaeological Historical Entry', 'st' => 0));
                return;
            } else {
                echo json_encode(array('msg' => 'Data successfully saved ', 'st' => 1));
                return;
            }
        }
    }





    public function LmNoteForm()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $dist = $this->session->userdata('dist_code');
        $sub = $this->session->userdata('subdiv_code');
        $circle = $this->session->userdata('cir_code');
        $mza = $this->session->userdata('mouza_pargona_code');
        $lot = $this->session->userdata('lot_no');
        $vill = $this->session->userdata('vill_townprt_code');
        $dag = $this->session->userdata('dag_no');
        $data['lm_notes'] = $this->RemarkModel->get_lmnotes();
        $data['mandal_name'] = $this->RemarkModel->MandalNamedetails($dist, $sub, $circle, $mza, $lot);
        $lmnote_id = $this->RemarkModel->checklmnoteid();
        $data['lmnoteId'] = $lmnote_id;
        $data['_view'] = 'rmk_lmnote';
        $data['locationname'] = $this->setLocationNames();
        $this->load->view('layout/layout', $data);
    }

    public function LmNoteFormSubmit()
    {
        $this->dataswitch();
        $data = array();
        $this->form_validation->set_rules('lm_note_cron_no', 'LM Note Cron No', 'trim|integer|required');
        //$this->form_validation->set_rules('lm_note_lno', 'LM Note Line No', 'trim|integer|required');
        //$this->form_validation->set_rules('rmk_type_hist_no', 'Remark Type History Number', 'trim|integer|required');
        $this->form_validation->set_rules('lm_note', 'LM Note', 'trim|required');
        $this->form_validation->set_rules('lm_note_date', 'Note Date', 'required|trim');
        $this->form_validation->set_rules('lm_code', 'Mandal Name', 'trim|required');
        $this->form_validation->set_rules('lm_sign', 'Mandal Sign', 'trim|required');
        $this->form_validation->set_rules('co_approval', 'CO Approval', 'trim|required');
        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
        } else {
            $data = array(
                'dist_code' => $this->session->userdata('dist_code'),
                'subdiv_code' => $this->session->userdata('subdiv_code'),
                'cir_code' => $this->session->userdata('cir_code'),
                'mouza_pargona_code' => $this->session->userdata('mouza_pargona_code'),
                'lot_no' => $this->session->userdata('lot_no'),
                'vill_townprt_code' => $this->session->userdata('vill_townprt_code'),
                'dag_no' => $this->session->userdata('dag_no'),
                'lm_note_cron_no' => $this->input->post('lm_note_cron_no'),
                //'lm_note_lno' => $this->input->post('lm_note_lno'),
                //'rmk_type_hist_no' => $this->input->post('rmk_type_hist_no'),
                'lm_note_lno' => 0,
                'rmk_type_hist_no' => 0,
                'lm_note' => $this->input->post('lm_note'),
                'lm_note_date' => $this->input->post('lm_note_date'),
                'lm_code' => $this->input->post('lm_code'),
                'lm_sign' => $this->input->post('lm_sign'),
                'co_approval' => $this->input->post('co_approval'),
                'user_code' => $this->session->userdata('usercode'),
                'date_entry' => date("Y-m-d | h:i:sa"),
                'operation' => 'D',
            );
            $notedetails = $this->RemarkModel->add_lmnote($data);
            if ($notedetails) {
                echo json_encode(array('msg' => 'Data saved for LM Note Details', 'st' => 1));
            } else {
                echo json_encode(array('msg' => 'Error in LM Note Entry', 'st' => 0));
            }
        }
    }

    public function deleteRemark()
    {
        $this->dataswitch();
        $dist_code = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no = $this->session->userdata('lot_no');
        $vill_townprt_code = $this->session->userdata('vill_townprt_code');
        $dag_no = $this->session->userdata('dag_no');
        $lm_note_cron_no = $this->input->post('lm_note_cron_no');
        $where_query = [
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'mouza_pargona_code' => $mouza_pargona_code,
            'lot_no' => $lot_no,
            'vill_townprt_code' => $vill_townprt_code,
            'dag_no' => $dag_no,
            'lm_note_cron_no' => $lm_note_cron_no
        ];
        try {
            $this->db->delete('chitha_rmk_lmnote', $where_query);
            $response = ['message' => 'Deleted Remark successfully', 'status' => 'success'];
            echo json_encode($response);
            return;
        } catch (Exception $e) {
            $response = ['message' => $e, 'status' => 'failed'];
            echo json_encode($response);
            return;
        }
    }

    public function updateRemark()
    {
        $this->dataswitch();
        $this->form_validation->set_rules('lm_note_cron_no', 'LM Note Cron No', 'trim|integer|required');
        $this->form_validation->set_rules('lm_note', 'LM Note', 'trim|required');
        $this->form_validation->set_rules('lm_note_date', 'Note Date', 'required|trim');
        $this->form_validation->set_rules('lm_code', 'Mandal Name', 'trim|required');
        $this->form_validation->set_rules('lm_sign', 'Mandal Sign', 'trim|required');
        $this->form_validation->set_rules('co_approval', 'CO Approval', 'trim|required');
        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('message' => $text, 'status' => 'failed'));
        } else {
            $dist_code = $this->session->userdata('dist_code');
            $subdiv_code = $this->session->userdata('subdiv_code');
            $cir_code = $this->session->userdata('cir_code');
            $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
            $lot_no = $this->session->userdata('lot_no');
            $vill_townprt_code = $this->session->userdata('vill_townprt_code');
            $dag_no = $this->session->userdata('dag_no');
            $lm_note_cron_no = $this->input->post('lm_note_cron_no');
            $where_query = [
                'dist_code' => $dist_code,
                'subdiv_code' => $subdiv_code,
                'cir_code' => $cir_code,
                'mouza_pargona_code' => $mouza_pargona_code,
                'lot_no' => $lot_no,
                'vill_townprt_code' => $vill_townprt_code,
                'dag_no' => $dag_no,
                'lm_note_cron_no' => $lm_note_cron_no
            ];
            try {
                $this->db->where($where_query);
                $this->db->update('chitha_rmk_lmnote', [
                    'lm_note_cron_no' => $this->input->post('lm_note_cron_no'),
                    'lm_note' => $this->input->post('lm_note'),
                    'lm_note_date' => $this->input->post('lm_note_date'),
                    'lm_code' => $this->input->post('lm_code'),
                    'lm_sign' => $this->input->post('lm_sign'),
                    'co_approval' => $this->input->post('co_approval')
                ]);
                $response = ['message' => 'Updated Remark successfully', 'status' => 'success'];
                echo json_encode($response);
                return;
            } catch (Exception $e) {
                $response = ['message' => $e, 'status' => 'failed'];
                echo json_encode($response);
                return;
            }
        }
    }

    public function SkNoteForm()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $dist = $this->session->userdata('dist_code');
        $sub = $this->session->userdata('subdiv_code');
        $circle = $this->session->userdata('cir_code');
        $data['sk_name'] = $this->RemarkModel->SKNamedetails($dist, $sub, $circle);
        $data['_view'] = 'rmk_sknote';
        $data['locationname'] = $this->setLocationNames();
        $this->load->view('layout/layout', $data);
    }

    public function SKNoteFormSubmit()
    {
        $this->dataswitch();
        $data = array();
        $this->form_validation->set_rules('sk_note_cron_no', 'SK Note Cron No', 'trim|integer|required');
        $this->form_validation->set_rules('sk_note_lno', 'SK Note Line No', 'trim|integer|required');
        $this->form_validation->set_rules('rmk_type_hist_no', 'Remark Type History Number', 'trim|integer|required');
        $this->form_validation->set_rules('sk_note', 'SK Note', 'trim|required');
        $this->form_validation->set_rules('sk_note_date', 'Note Date', 'trim');
        $this->form_validation->set_rules('sk_code', 'SK Name', 'trim|required');
        $this->form_validation->set_rules('sk_sign', 'SK Sign', 'trim|required');
        $this->form_validation->set_rules('co_approval', 'CO Approval', 'trim|required');
        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
        } else {
            $data = array(
                'dist_code' => $this->session->userdata('dist_code'),
                'subdiv_code' => $this->session->userdata('subdiv_code'),
                'cir_code' => $this->session->userdata('cir_code'),
                'mouza_pargona_code' => $this->session->userdata('mouza_pargona_code'),
                'lot_no' => $this->session->userdata('lot_no'),
                'vill_townprt_code' => $this->session->userdata('vill_townprt_code'),
                'dag_no' => $this->session->userdata('dag_no'),
                'sk_note_cron_no' => $this->input->post('sk_note_cron_no'),
                'sk_note_lno' => $this->input->post('sk_note_lno'),
                'rmk_type_hist_no' => $this->input->post('rmk_type_hist_no'),
                'sk_note' => $this->input->post('sk_note'),
                'sk_note_date' => $this->input->post('sk_note_date'),
                'sk_code' => $this->input->post('sk_code'),
                'sk_sign' => $this->input->post('sk_sign'),
                'co_approval' => $this->input->post('co_approval'),
                'user_code' => $this->session->userdata('usercode'),
                'date_entry' => date("Y-m-d | h:i:sa"),
                'operation' => 'D',
            );
            $notedetails = $this->RemarkModel->add_sknote($data);
            if ($notedetails) {
                echo json_encode(array('msg' => 'Data saved for SK Note Details', 'st' => 1));
            } else {
                echo json_encode(array('msg' => 'Error in SK Note Entry', 'st' => 0));
            }
        }
    }

    public function EncroacherForm()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $data['_view'] = 'rmk_encroacher';
        $data['relation'] = $this->RemarkModel->getRelation();
        $data['classcode'] = $this->RemarkModel->getencroclasscode();
        $data['landusedfor'] = $this->RemarkModel->getencrolandusedfor();
        $data['locationname'] = $this->setLocationNames();
        $encro_id = $this->RemarkModel->checkencroseid();
        $data['encroId'] = $encro_id;
        $this->load->view('layout/layout', $data);
    }

    public function EncroacherFormSubmit()
    {
        $this->dataswitch();
        $this->form_validation->set_rules('encro_id', 'Encro ID', 'trim|integer|required');
        $this->form_validation->set_rules('rmk_type_hist_no', 'Remark Type History Number', 'trim|integer|required');
        $this->form_validation->set_rules('encro_name', 'Encroacher Name', 'trim|required');
        $this->form_validation->set_rules('encro_guardian', 'Encroacher Guardian Name', 'trim|required');
        $this->form_validation->set_rules('encro_guar_relation', 'Relation with Guardian', 'trim|required');
        $this->form_validation->set_rules('encro_add', 'Encroacher Address', 'trim|required');
        $this->form_validation->set_rules('encro_class_code', 'Encroacher Land Used For', 'trim|required');
        $this->form_validation->set_rules('nature_land_code', 'Nature of Encroacher Land', 'trim|required');
        $this->form_validation->set_rules('encro_land_b', 'Land Area(Bigha)', 'trim|required');
        $this->form_validation->set_rules('encro_land_k', 'Land Area(Katha)', 'trim|required');
        $this->form_validation->set_rules('encro_land_lc', 'Land Area(Lessa)', 'trim|required');
        $this->form_validation->set_rules('encro_evicted_yn', 'Encroacher Evicted', 'trim|required');

        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
            return;
        } else {
            $mm = $this->input->post('encro_evicted_yn');

            if ($mm == 'N') {
                $dataSave = array(
                    'dist_code'   => $this->session->userdata('dist_code'),
                    'subdiv_code' => $this->session->userdata('subdiv_code'),
                    'cir_code'    => $this->session->userdata('cir_code'),
                    'mouza_pargona_code' => $this->session->userdata('mouza_pargona_code'),
                    'lot_no'      => $this->session->userdata('lot_no'),
                    'vill_townprt_code' => $this->session->userdata('vill_townprt_code'),
                    'dag_no' => $this->session->userdata('dag_no'),
                    'encro_id' => $this->input->post('encro_id'),
                    'rmk_type_hist_no' => $this->input->post('rmk_type_hist_no'),
                    'encro_name' => $this->input->post('encro_name'),
                    'encro_guardian' => $this->input->post('encro_guardian'),
                    'encro_guar_relation' => $this->input->post('encro_guar_relation'),
                    'encro_add' => $this->input->post('encro_add'),
                    'encro_class_code' => $this->input->post('encro_class_code'),
                    'nature_land_code' => $this->input->post('nature_land_code'),
                    'encro_land_used_for' => $this->input->post('nature_land_code'),
                    'encro_land_b' => $this->input->post('encro_land_b'),
                    'encro_land_k' => $this->input->post('encro_land_k'),
                    'encro_land_lc' => $this->input->post('encro_land_lc'),
                    'encro_evicted_yn' => $this->input->post('encro_evicted_yn'),
                    'encro_land_g' => '00',
                    'co_approval' => '00',
                    'user_code' => $this->session->userdata('usercode'),
                    'date_entry' => date("Y-m-d | h:i:sa"),
                    'operation' => 'D',
                );

                $this->RemarkModel->add_encroacherdeails($dataSave);

                if ($this->db->trans_status() === FALSE) {
                    echo json_encode(array('msg' => 'Error in Encroacher Entry', 'st' => 0));
                    return;
                } else {
                    echo json_encode(array('msg' => 'Data saved for Encroacher Details', 'st' => 1));
                    return;
                }
            }
            if ($mm == 'Y') {
                $this->form_validation->set_rules('encro_evic_date', 'Encroacher Evic Date', 'trim|required');
                if ($this->form_validation->run() == false) {
                    $text = str_ireplace('<\/p>', '', validation_errors());
                    $text = str_ireplace('<p>', '', $text);
                    $text = str_ireplace('</p>', '', $text);
                    echo json_encode(array('msg' => $text, 'st' => 0));
                    return;
                }
                $dataSave = array(
                    'dist_code'   => $this->session->userdata('dist_code'),
                    'subdiv_code' => $this->session->userdata('subdiv_code'),
                    'cir_code'    => $this->session->userdata('cir_code'),
                    'mouza_pargona_code' => $this->session->userdata('mouza_pargona_code'),
                    'lot_no'      => $this->session->userdata('lot_no'),
                    'vill_townprt_code' => $this->session->userdata('vill_townprt_code'),
                    'dag_no' => $this->session->userdata('dag_no'),
                    'encro_id' => $this->input->post('encro_id'),
                    'rmk_type_hist_no' => $this->input->post('rmk_type_hist_no'),
                    'encro_name' => $this->input->post('encro_name'),
                    'encro_guardian' => $this->input->post('encro_guardian'),
                    'encro_guar_relation' => $this->input->post('encro_guar_relation'),
                    'encro_add' => $this->input->post('encro_add'),
                    'encro_class_code' => $this->input->post('encro_class_code'),
                    'nature_land_code' => $this->input->post('nature_land_code'),
                    'encro_land_used_for' => $this->input->post('nature_land_code'),
                    'encro_land_b' => $this->input->post('encro_land_b'),
                    'encro_land_k' => $this->input->post('encro_land_k'),
                    'encro_land_lc' => $this->input->post('encro_land_lc'),
                    'encro_evicted_yn' => $this->input->post('encro_evicted_yn'),
                    'encro_evic_date' => $this->input->post('encro_evic_date'),
                    'encro_land_g' => '00',
                    'co_approval' => '00',
                    'user_code' => $this->session->userdata('usercode'),
                    'date_entry' => date("Y-m-d | h:i:sa"),
                    'operation' => 'D',
                );

                $this->RemarkModel->add_encroacherdeails($dataSave);

                if ($this->db->trans_status() === FALSE) {
                    echo json_encode(array('msg' => 'Error in Encroacher Entry', 'st' => 0));
                    return;
                } else {
                    echo json_encode(array('msg' => 'Data saved for Encroacher Details', 'st' => 1));
                    return;
                }
            }
        }
    }

    public function remarkForm()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $dist = $this->session->userdata('dist_code');
        $sub = $this->session->userdata('subdiv_code');
        $circle = $this->session->userdata('cir_code');
        $mza = $this->session->userdata('mouza_pargona_code');
        $lot = $this->session->userdata('lot_no');
        $vill = $this->session->userdata('vill_townprt_code');
        $dag = $this->session->userdata('dag_no');
        $data['rmk_type_hist_no'] = $this->RemarkModel->getSerial($dist, $sub, $circle, $mza, $lot, $vill, $dag);
        $data['ord_cron_no'] = $this->RemarkModel->getSerialCronNo($dist, $sub, $circle, $mza, $lot, $vill, $dag);
        $data['order_type'] = $this->RemarkModel->getOrderType();
        $data['mandal_name'] = $this->RemarkModel->MandalNamedetails($dist, $sub, $circle, $mza, $lot);
        $data['sk_name'] = $this->RemarkModel->SKNamedetails($dist, $sub, $circle);
        $data['co_name'] = $this->RemarkModel->CONamedetails($dist, $sub, $circle);
        $data['_view'] = 'rmk2_ordbasic';
        $data['locationname'] = $this->setLocationNames();
        $data['daghd'] = 'Dag no:' . $this->session->userdata('dag_no') . ' Patta no:' . $this->session->userdata('patta_no') . ' (' . $this->session->userdata('patta_type_name') . ')';
        if (($this->session->userdata('dist_code') == '21') || ($this->session->userdata('dist_code') == '22') || ($this->session->userdata('dist_code') == '23')) {
            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Chatak:' . $this->session->userdata('lessa') . ' Ganda:' . $this->session->userdata('ganda');
        } else {
            $data['landhd'] = 'Bigha:' . $this->session->userdata('bigha') . ' Katha:' . $this->session->userdata('katha') . ' Lessa:' . $this->session->userdata('lessa');
        }
        $this->load->view('layout/layout', $data);
    }

    public function remarkFormsubmit()
    {
        $this->dataswitch();
        $data = array();
        $this->form_validation->set_rules('rmk_type_hist_no', 'rmk_type_hist_no', 'trim|integer|required');
        $this->form_validation->set_rules('ord_cron_no', 'Ord_cron_no', 'trim|integer|required');
        $this->form_validation->set_rules('ord_no', 'Order Number', 'trim|required');
        $this->form_validation->set_rules('ord_date', 'Order Date', 'trim|required');
        $this->form_validation->set_rules('ord_type_code', 'Order Type', 'trim|integer|required');
        $this->form_validation->set_rules('ord_passby_desig', 'Order Pass by', 'trim|required');
        $this->form_validation->set_rules('ord_passby_sign_yn', 'Order Pass Sign', 'trim|required');
        $this->form_validation->set_rules('lm_code', 'Mandal Name', 'trim|required');
        $this->form_validation->set_rules('co_code', 'CO Name', 'trim|required');
        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
        } else {
            $data = array(
                'dist_code' => $this->session->userdata('dist_code'),
                'subdiv_code' => $this->session->userdata('subdiv_code'),
                'cir_code' => $this->session->userdata('cir_code'),
                'mouza_pargona_code' => $this->session->userdata('mouza_pargona_code'),
                'lot_no' => $this->session->userdata('lot_no'),
                'vill_townprt_code' => $this->session->userdata('vill_townprt_code'),
                'dag_no' => $this->session->userdata('dag_no'),
                'rmk_type_hist_no' => $this->input->post('rmk_type_hist_no'),
                'ord_no' => $this->input->post('ord_no'),
                'ord_date' => $this->input->post('ord_date'),
                'ord_type_code' => $this->input->post('ord_type_code'),
                'ord_cron_no' => $this->input->post('ord_cron_no'),
                'case_no' => $this->input->post('case_no'),
                'ord_on_gl_type' => $this->input->post('ord_on_gl_type'),
                'ord_passby_sign_yn' => $this->input->post('ord_passby_sign_yn'),
                'ord_passby_desig' => $this->input->post('ord_passby_desig'),
                'ord_ref_let_no' => $this->input->post('ord_ref_let_no'),
                'lm_code' => $this->input->post('lm_code'),
                'lm_sign_yn' => $this->input->post('lm_sign_yn'),
                'lm_sign_date' => $this->input->post('lm_sign_date'),
                'sk_code' => $this->input->post('sk_code'),
                'sk_sign_yn' => $this->input->post('sk_sign_yn'),
                'sk_sign_date' => $this->input->post('sk_sign_date'),
                'co_code' => $this->input->post('co_code'),
                'co_sign_yn' => $this->input->post('co_sign_yn'),
                'co_ord_date' => $this->input->post('co_ord_date'),
                'wrt_order1' => $this->input->post('wrt_order1'),
                'wrt_order2' => $this->input->post('wrt_order2'),
                'wrt_order3' => $this->input->post('wrt_order3'),
                'wrt_order4' => $this->input->post('wrt_order4'),
                'wrt_order5' => $this->input->post('wrt_order5'),
                'user_code' => $this->session->userdata('usercode'),
                'date_entry' => date("Y-m-d | h:i:sa"),
                'operation' => 'D',
                //          'm_dag_area_b' => $this->input->post('m_dag_area_b'),
                //          'm_dag_area_k' => $this->input->post('m_dag_area_k'),
                //          'm_dag_area_lc'=>$this->input->post('m_dag_area_lc'),
                //          'm_dag_area_g' => $this->input->post('m_dag_area_g'),
                //          'm_dag_area_kr' => $this->input->post('m_dag_area_kr'),
                //          'area_left_b' => $this->input->post('area_left_b'),
                //          'area_left_k' => $this->input->post('area_left_k'),
                //          'area_left_lc'=>$this->input->post('area_left_lc'),
                //          'area_left_g' => $this->input->post('area_left_g'),
                //          'area_left_kr' => $this->input->post('area_left_kr'),
                //          'user_code' => '00',
                //          'date_entry' => date("Y-m-d | h:i:sa"),
                //          'operation' => 'D',
                'm_dag_area_b' => '00',
                'm_dag_area_k' => '00',
                'm_dag_area_lc' => '00',
                'm_dag_area_g' => '00',
                'm_dag_area_kr' => '00',
                'area_left_b' => '00',
                'area_left_k' => '00',
                'area_left_lc' => '00',
                'area_left_g' => '00',
                'area_left_kr' => '00',
            );
            $remarkdetails = $this->RemarkModel->add_remarkdetails($data);
            $ordertypecode = $this->input->post('ord_type_code');
            if ($remarkdetails && $ordertypecode == '03') {
                //      $data['patta_type'] = $this->RemarkModel->getPattaType();
                //       $data['relation'] = $this->RemarkModel->getRelation();
                //      $this->load->view('rmk10_infavor_of',$data);
                echo json_encode(array('msg' => 'Proceed for In Favor of Details entry', 'st' => 1));
            } else {
                echo json_encode(array('msg' => 'Error in Remark entry', 'st' => 0));
            }
        }
    }

    public function remarkForm_in_favor_of()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $data['patta_type'] = $this->RemarkModel->getPattaType();
        $data['relation'] = $this->RemarkModel->getRelation();
        $data['byright'] = $this->RemarkModel->getByRight();
        //$this->load->view('rmk10_infavor_of',$data);
        $data['_view'] = 'rmk10_infavor_of';
        $data['locationname'] = $this->setLocationNames();
        $this->load->view('layout/layout', $data);
    }

    public function remarkForm_in_favor_of_submit()
    {
        $this->dataswitch();
        $data = array();
        $this->form_validation->set_rules('infavor_of_id', 'Infavor Of ID', 'trim|integer|required');
        $this->form_validation->set_rules('rmk_type_hist_no', 'Remark Type History Number', 'trim|integer|required');
        $this->form_validation->set_rules('patta_type_code', 'Patta Type', 'trim|required');
        $this->form_validation->set_rules('patta_no', 'Patta Number', 'trim|required');
        $this->form_validation->set_rules('infavor_of_name', 'In Favor of Name', 'trim|required');
        $this->form_validation->set_rules('by_right_of', 'By Right of', 'trim|required');
        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
        } else {
            $data = array(
                'dist_code' => $this->session->userdata('dist_code'),
                'subdiv_code' => $this->session->userdata('subdiv_code'),
                'cir_code' => $this->session->userdata('cir_code'),
                'mouza_pargona_code' => $this->session->userdata('mouza_pargona_code'),
                'lot_no' => $this->session->userdata('lot_no'),
                'vill_townprt_code' => $this->session->userdata('vill_townprt_code'),
                'dag_no' => $this->session->userdata('dag_no'),
                'infavor_of_id' => $this->input->post('infavor_of_id'),
                'rmk_type_hist_no' => $this->input->post('rmk_type_hist_no'),
                'ord_no' => $this->input->post('ord_no'),
                'ord_date' => $this->input->post('ord_date'),
                'ord_cron_no' => $this->input->post('ord_cron_no'),
                'patta_type_code' => $this->input->post('patta_type_code'),
                'patta_no' => $this->input->post('patta_no'),
                'infavor_of_name' => $this->input->post('infavor_of_name'),
                'infavor_of_guardian' => $this->input->post('infavor_of_guardian'),
                'infav_of_guar_relation' => $this->input->post('infav_of_guar_relation'),
                'infavor_of_add1' => $this->input->post('infavor_of_add1'),
                'infavor_of_add2' => $this->input->post('infavor_of_add2'),
                'by_right_of' => $this->input->post('by_right_of'),
                'land_area_b' => $this->input->post('land_area_b'),
                'land_area_k' => $this->input->post('land_area_k'),
                'land_area_lc' => $this->input->post('land_area_lc'),
                'new_dag_no' => $this->input->post('new_dag_no'),
                'new_patta_no' => $this->input->post('new_patta_no'),
                'reg_deal_no' => $this->input->post('reg_deal_no'),
                'reg_date' => $this->input->post('reg_date'),
                'sub_reg_office' => $this->input->post('sub_reg_office'),
                'user_code' => $this->session->userdata('usercode'),
                'date_entry' => date("Y-m-d | h:i:sa"),
                'operation' => 'D',
                'land_area_g' => '00',
                'land_area_kr' => '00',
            );

            $infavdetails = $this->RemarkModel->add_Infavorofdetails($data);
            if ($infavdetails) {
                echo json_encode(array('msg' => 'Data saved for In Favor Details', 'st' => 1));
            } else {
                echo json_encode(array('msg' => 'Error in In Favor Details entry', 'st' => 0));
            }
        }
    }

    public function remarkForm_along_with()
    {
        $data['base'] = $this->config->item('base_url');
        $data['relation'] = $this->RemarkModel->getRelation();
        //  $this->load->view('rmk9_alongwith',$data);
        $data['_view'] = 'rmk9_alongwith';
        $data['locationname'] = $this->setLocationNames();
        $this->load->view('layout/layout', $data);
    }

    public function remarkForm_along_with_submit()
    {
        $this->dataswitch();
        $data = array();
        $this->form_validation->set_rules('alongwith_id', 'Along with ID', 'trim|integer|required');
        $this->form_validation->set_rules('rmk_type_hist_no', 'Remark Type History Number', 'trim|integer|required');
        $this->form_validation->set_rules('ord_no', 'Order Number', 'trim');
        $this->form_validation->set_rules('ord_date', 'Order Date', 'trim');
        $this->form_validation->set_rules('ord_cron_no', 'Order Cron No', 'trim');
        $this->form_validation->set_rules('alongwith_name', 'Along with Name', 'trim|required');
        $this->form_validation->set_rules('alongwith_guardian', 'Along with Name', 'trim');
        $this->form_validation->set_rules('alongwith_rel_gur', 'Relation', 'trim');
        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
        } else {
            $data = array(
                'dist_code' => $this->session->userdata('dist_code'),
                'subdiv_code' => $this->session->userdata('subdiv_code'),
                'cir_code' => $this->session->userdata('cir_code'),
                'mouza_pargona_code' => $this->session->userdata('mouza_pargona_code'),
                'lot_no' => $this->session->userdata('lot_no'),
                'vill_townprt_code' => $this->session->userdata('vill_townprt_code'),
                'dag_no' => $this->session->userdata('dag_no'),
                'alongwith_id' => $this->input->post('alongwith_id'),
                'rmk_type_hist_no' => $this->input->post('rmk_type_hist_no'),
                'ord_no' => $this->input->post('ord_no'),
                'ord_date' => $this->input->post('ord_date'),
                'ord_cron_no' => $this->input->post('ord_cron_no'),
                'alongwith_name' => $this->input->post('alongwith_name'),
                'alongwith_guardian' => $this->input->post('alongwith_guardian'),
                'alongwith_rel_gur' => $this->input->post('alongwith_rel_gur'),
                'user_code' => $this->session->userdata('usercode'),
                'date_entry' => date("Y-m-d | h:i:sa"),
                'operation' => 'D',
            );

            $alongwithdetails = $this->RemarkModel->add_alongwithdetails($data);
            if ($alongwithdetails) {
                echo json_encode(array('msg' => 'Data saved for Along with Details', 'st' => 1));
            } else {
                echo json_encode(array('msg' => 'Error in Along with Details entry', 'st' => 0));
            }
        }
    }

    public function remarkForm_in_place_of()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $data['relation'] = $this->RemarkModel->getRelation();
        // $this->load->view('rmk8_inplace_of',$data);
        $data['_view'] = 'rmk8_inplace_of';
        $data['locationname'] = $this->setLocationNames();
        $this->load->view('layout/layout', $data);
    }

    public function remarkForm_in_place_of_submit()
    {

        $this->dataswitch();

        $data = array();
        $this->form_validation->set_rules('inplace_of_id', 'In place of ID', 'trim|integer|required');
        $this->form_validation->set_rules('rmk_type_hist_no', 'Remark Type History Number', 'trim|integer|required');
        $this->form_validation->set_rules('ord_no', 'Order Number', 'trim');
        $this->form_validation->set_rules('ord_date', 'Order Date', 'trim');
        $this->form_validation->set_rules('ord_cron_no', 'Order Cron No', 'trim');
        $this->form_validation->set_rules('inplace_of_name', 'In Place Of Name', 'trim|required');
        $this->form_validation->set_rules('inplace_of_guardian', 'In Place Of Gurdain Name', 'trim');
        $this->form_validation->set_rules('inplace_of_relation', 'Relation', 'trim');
        $this->form_validation->set_rules('inplace_of_add1', 'Address 1', 'trim');
        $this->form_validation->set_rules('inplace_of_add2', 'Address 2', 'trim');
        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
        } else {
            $data = array(
                'dist_code' => $this->session->userdata('dist_code'),
                'subdiv_code' => $this->session->userdata('subdiv_code'),
                'cir_code' => $this->session->userdata('cir_code'),
                'mouza_pargona_code' => $this->session->userdata('mouza_pargona_code'),
                'lot_no' => $this->session->userdata('lot_no'),
                'vill_townprt_code' => $this->session->userdata('vill_townprt_code'),
                'dag_no' => $this->session->userdata('dag_no'),
                'inplace_of_id' => $this->input->post('inplace_of_id'),
                'rmk_type_hist_no' => $this->input->post('rmk_type_hist_no'),
                'ord_no' => $this->input->post('ord_no'),
                'ord_date' => $this->input->post('ord_date'),
                'ord_cron_no' => $this->input->post('ord_cron_no'),
                'inplace_of_name' => $this->input->post('inplace_of_name'),
                'inplace_of_guardian' => $this->input->post('inplace_of_guardian'),
                'inplace_of_relation' => $this->input->post('inplace_of_relation'),
                'inplace_of_add1' => $this->input->post('inplace_of_add1'),
                'inplace_of_add2' => $this->input->post('inplace_of_add2'),
                'user_code' => $this->session->userdata('usercode'),
                'date_entry' => date("Y-m-d | h:i:sa"),
                'operation' => 'D',
            );

            $inplaceofdetails = $this->RemarkModel->add_inplaceofdetails($data);
            if ($inplaceofdetails) {
                echo json_encode(array('msg' => 'Data saved for In Place Of Details', 'st' => 1));
            } else {
                echo json_encode(array('msg' => 'Error in In Place Of Details entry', 'st' => 0));
            }
        }
    }

    public function remarkForm_on_behalf_of()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $data['relation'] = $this->RemarkModel->getRelation();
        // $this->load->view('rmk11_onbehalf',$data);
        $data['_view'] = 'rmk11_onbehalf';
        $data['locationname'] = $this->setLocationNames();
        $this->load->view('layout/layout', $data);
    }

    public function remarkForm__on_behalf_of_submit()
    {
        $this->dataswitch();
        $data = array();
        $this->form_validation->set_rules('onbehalf_id', 'On Behalf of ID', 'trim|integer|required');
        $this->form_validation->set_rules('rmk_type_hist_no', 'Remark Type History Number', 'trim|integer|required');
        $this->form_validation->set_rules('ord_no', 'Order Number', 'trim');
        $this->form_validation->set_rules('ord_date', 'Order Date', 'trim');
        $this->form_validation->set_rules('onbehalf_name', 'On Behalf Of Name', 'trim|required');
        $this->form_validation->set_rules('onbehalf_guardian', 'On Behalf Of Gurdain Name', 'trim');
        $this->form_validation->set_rules('onbehalf_rel_gur', 'Relation', 'trim');
        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
        } else {
            $data = array(
                'dist_code' => $this->session->userdata('dist_code'),
                'subdiv_code' => $this->session->userdata('subdiv_code'),
                'cir_code' => $this->session->userdata('cir_code'),
                'mouza_pargona_code' => $this->session->userdata('mouza_pargona_code'),
                'lot_no' => $this->session->userdata('lot_no'),
                'vill_townprt_code' => $this->session->userdata('vill_townprt_code'),
                'dag_no' => $this->session->userdata('dag_no'),
                'onbehalf_id' => $this->input->post('onbehalf_id'),
                'rmk_type_hist_no' => $this->input->post('rmk_type_hist_no'),
                'ord_no' => $this->input->post('ord_no'),
                'ord_date' => $this->input->post('ord_date'),
                'onbehalf_name' => $this->input->post('onbehalf_name'),
                'onbehalf_guardian' => $this->input->post('onbehalf_guardian'),
                'onbehalf_rel_gur' => $this->input->post('onbehalf_rel_gur'),
                'user_code' => $this->session->userdata('usercode'),
                'date_entry' => date("Y-m-d | h:i:sa"),
                'operation' => 'D',
            );

            $onhehalfofdetails = $this->RemarkModel->add_onbehalfofdetails($data);
            if ($onhehalfofdetails) {
                echo json_encode(array('msg' => 'Data saved for On Behalf Of Details', 'st' => 1));
            } else {
                echo json_encode(array('msg' => 'Error in  On Behalf Of Details entry', 'st' => 0));
            }
        }
    }

    //  public function remarkcomplete() {
    //    redirect('/');
    //  }

}
