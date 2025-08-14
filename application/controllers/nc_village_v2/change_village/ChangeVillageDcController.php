<?php

include APPPATH . '/libraries/CommonTrait.php';
class ChangeVillageDcController extends CI_Controller
{
    use CommonTrait;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('CommonModel');
        $this->load->model('ChangeVillageModel');
        $this->load->model('UtilsModel');
        $this->load->model('NcVillageModel');
    }
    // public function dashboard()
    // {
    //     $this->dbswitch();
    //     $dist_code = $this->session->userdata('dcode');
    //     $data['base'] = $this->config->item('base_url');
    //     $data['districts'] = $this->Chithamodel->districtdetailsreport();
    //     $data['villages'] = $this->db->order_by("status", "asc")->get('change_vill_name')->result_array();
    //     $data['_view'] = 'change_village/dc/change_village_dc_dashboard';

    //     $this->load->view('layout/layout', $data);
    // }
    // public function subdivisiondetails()
    // {
    //     $this->dataswitch();
    //     $data = [];
    //     $dist_code = $this->input->post('id');
    //     $this->session->set_userdata('dist_code', $dist_code);
    //     $formdata = $this->db->get_where('location', array('dist_code' => $dist_code, 'subdiv_code !=' => '00', 'cir_code' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->result_array();
    //     foreach ($formdata as $value) {
    //         $data['subdiv_code'][] = $value;
    //     }
    //     echo json_encode($data['subdiv_code']);
    // }
    // public function circledetails()
    // {
    //     $this->dataswitch();
    //     $data = [];
    //     $dist_code = $this->input->post('dis');
    //     $subdiv = $this->input->post('subdiv');
    //     $this->session->set_userdata('subdiv_code', $subdiv);
    //     $formdata = $this->db->get_where('location', array('dist_code' => $dist_code, 'subdiv_code' => $subdiv, 'cir_code!=' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->result_array();
    //     foreach ($formdata as $value) {
    //         $data['cir_code'][] = $value;
    //     }
    //     echo json_encode($data['cir_code']);
    // }
    // public function mouzadetails()
    // {
    //     $this->dataswitch();
    //     $data = [];
    //     $dis = $this->input->post('dis');
    //     $subdiv = $this->input->post('subdiv');
    //     $cir = $this->input->post('cir');
    //     $this->session->set_userdata('cir_code', $cir);
    //     $formdata = $this->Chithamodel->mouzadetails($dis, $subdiv, $cir);
    //     foreach ($formdata as $value) {
    //         $data['cir_code'][] = $value;
    //     }
    //     echo json_encode($data['cir_code']);
    // }
    // public function villagedetails()
    // {
    //     $this->dataswitch();
    //     $uuid = $this->input->post('id');
    //     $vill = $this->db->get_where('location', array('uuid' => $uuid))->row();
    //     $change_vill = $this->db->get_where('change_vill_name', array('uuid' => $uuid))->row();
    //     $dist = $this->db->get_where('location', array('dist_code' => $vill->dist_code, 'subdiv_code =' => '00', 'cir_code=' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->row();
    //     $subdiv = $this->db->get_where('location', array('dist_code' => $vill->dist_code, 'subdiv_code =' => $vill->subdiv_code, 'cir_code=' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->row();
    //     $cir = $this->db->get_where('location', array('dist_code' => $vill->dist_code, 'subdiv_code =' => $vill->subdiv_code, 'cir_code=' => $vill->cir_code, 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->row();
    //     $mouza = $this->db->get_where('location', array('dist_code' => $vill->dist_code, 'subdiv_code =' => $vill->subdiv_code, 'cir_code=' => $vill->cir_code, 'mouza_pargona_code' => $vill->mouza_pargona_code, 'lot_no' => '00', 'vill_townprt_code' => '00000'))->row();
    //     $lot = $this->db->get_where('location', array('dist_code' => $vill->dist_code, 'subdiv_code =' => $vill->subdiv_code, 'cir_code=' => $vill->cir_code, 'mouza_pargona_code' => $vill->mouza_pargona_code, 'lot_no' => $vill->lot_no, 'vill_townprt_code' => '00000'))->row();
    //     $data['district'] = $dist->loc_name;
    //     $data['subdiv'] = $subdiv->loc_name;
    //     $data['cir'] = $cir->loc_name;
    //     $data['mouza'] = $mouza->loc_name;
    //     $data['lot'] = $lot->loc_name;
    //     $data['change'] = $change_vill;
    //     echo json_encode($data);
    // }
    // public function approveVillageChangeName()
    // {
    //     $this->dataswitch();
    //     $uuid = $this->input->post('id');
    //     $vill_name = $this->input->post('new_vill_name');
    //     $vill_name_eng = $this->input->post('new_vill_name_eng');

    //     $approveVill = $this->ChangeVillageModel->approveChangeVillage($uuid, $vill_name, $vill_name_eng);
    //     if ($approveVill) {

    //         echo json_encode(['msg' => 'Successfully Appoved!!', 'st' => 1]);
    //     } else {
    //         echo json_encode(['msg' => 'Failed!! Try Again', 'st' => 0]);
    //     }
    // }
    // public function filterTable()
    // {
    //     $this->dataswitch();
    //     $dist_code = $this->input->post('dist_code');
    //     $subdiv_code = $this->input->post('subdiv_code');
    //     $cir_code = $this->input->post('cir_code');
    //     $mouza = $this->input->post('mouza_pargona_code');
    //     $lot = $this->input->post('lot_no');
    //     if ($subdiv_code == '00') {
    //         $vill = $this->db->order_by("status", "asc")->get_where('change_vill_name', array('dist_code' => $dist_code))->result_array();
    //     } else if ($cir_code == '00') {
    //         $vill = $this->db->order_by("status", "asc")->get_where('change_vill_name', array('dist_code' => $dist_code, 'subdiv_code' => $subdiv_code))->result_array();
    //     } else if ($mouza == '00') {
    //         $vill = $this->db->order_by("status", "asc")->get_where('change_vill_name', array('dist_code' => $dist_code, 'subdiv_code' => $subdiv_code, 'cir_code=' => $cir_code))->result_array();
    //     } else if ($lot == '00') {
    //         $vill = $this->db->order_by("status", "asc")->get_where('change_vill_name', array('dist_code' => $dist_code, 'subdiv_code' => $subdiv_code, 'cir_code=' => $cir_code, 'mouza_pargona_code' => $mouza))->result_array();
    //     } else {
    //         $vill = $this->db->order_by("status", "asc")->get_where('change_vill_name', array('dist_code' => $dist_code, 'subdiv_code' => $subdiv_code, 'cir_code=' => $cir_code, 'mouza_pargona_code' => $mouza, 'lot_no' => $lot))->result_array();
    //     }
    //     foreach ($vill as $value) {
    //         $data['villages'][] = $value;
    //     }
    //     if (isset($data['villages'])) {
    //         echo json_encode($data['villages']);
    //     } else {
    //         $data['villages'][] = "0";
    //         echo json_encode($data['villages']);
    //     }
    // }
    public function changeVillageSubmit()
    {
        $this->form_validation->set_rules('dist_code', 'District Name', 'trim|integer|required');
        $this->form_validation->set_rules('vill_uuid', 'Village Name', 'trim|integer|required');
        $this->form_validation->set_rules('new_vill_name', 'New Village Name', 'trim|max_length[100]|required');
        $this->form_validation->set_rules('new_vill_name_eng', 'New Village Name (English)', 'trim|max_length[100]|required');
        $this->form_validation->set_rules('application_no', 'Application NO', 'trim|required');
        $this->form_validation->set_rules('user', 'JDS', 'trim|required');
        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
            return;
        } else {
            $this->dataswitch($_POST['dist_code']);
            $application_no = $_POST['application_no'];
            // $exist = $this->ChangeVillageModel->checkVillName($_POST['new_vill_name'], $_POST['new_vill_name_eng'], $_POST['vill_uuid']);
            $exist = false;
            if ($exist) {
                echo json_encode(array('msg' => 'Village name already exist.', 'st' => 0));
            } else {
                $is_forwarded = $this->forwardToJds($application_no);
                if ($is_forwarded == true) {
                    echo json_encode(array('msg' => 'Submitted Successfully!!', 'st' => 1));
                } else {
                    echo json_encode(array('msg' => 'Failed. Please Try Again', 'st' => 0));
                }
            }
        }
    }
    public function forwardToJds($application_no)
    {
        try {
            $user_code = $this->session->userdata('user_code');
            $change_vill_remark = $this->UtilsModel->cleanPattern($_POST['change_vill_remark']);
            $remarks = $change_vill_remark;
            $jds_code = $this->input->post('user');

            $this->db->trans_begin();
            $this->db->where('application_no', $application_no)
                ->update(
                    'nc_villages',
                    [
                        'pre_user' => 'DC',
                        'cu_user' => 'JDS',
                        'dc_code' => $user_code,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'dc_note' => trim($change_vill_remark),
                        'status' => 'M',
                    ]
                );

            if ($this->db->affected_rows() > 0) {
                $proceeding_id_max = $this->db->query("select max(proceeding_id) as id from settlement_proceeding where case_no = '$application_no'")->row()->id;
                $this->db->set([
                    'cu_user_code' => "DC",
                    'prev_user_code' => "CO",
                    'new_vill_name' => $_POST['new_vill_name'],
                    'new_vill_name_eng' => $_POST['new_vill_name_eng'],
                    'status' => "P",
                    'dc_verified' => "Y",
                    'date_of_update_dc' => date('Y-m-d H:i:s'),
                    'dc_user_code' => $this->session->userdata('usercode'),
                ]);

                $this->db->where('uuid', $_POST['vill_uuid']);
                $this->db->update('change_vill_name');

                $insPetProceed = array(
                    'case_no' => $application_no,
                    'proceeding_id' => !$proceeding_id_max ? 1 : (int) $proceeding_id_max + 1,
                    'date_of_hearing' => date('Y-m-d h:i:s'),
                    'next_date_of_hearing' => date('Y-m-d h:i:s'),
                    'note_on_order' => trim($remarks),
                    'status' => 'M',
                    'user_code' => $this->session->userdata('user_code'),
                    'date_entry' => date('Y-m-d h:i:s'),
                    'operation' => 'E',
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'office_from' => 'DC',
                    'office_to' => 'JDS',
                    'task' => 'Village Forwarded for Name Change by DC to JDS',
                );
                $this->db->insert('settlement_proceeding', $insPetProceed);

                if ($this->db->affected_rows() > 0) {
                    if ($this->db->trans_status() === false) {
                        return false;
                    } else {
                        $nc_village = $this->db->where('application_no', $application_no)->get('nc_villages')->row();
                        $url = API_LINK_ILRMS . "index.php/nc_village_v2/NcCommonController/insertNcVillageDetails";
                        $method = 'POST';
                        $update_data = [ 
                                            'proccess_type' => 'CHANGED_VILLAGE_NAME', 
                                            'dist_code' => $nc_village->dist_code,
                                            'subdiv_code' => $nc_village->subdiv_code,
                                            'cir_code' => $nc_village->cir_code,
                                            'mouza_pargona_code' => $nc_village->mouza_pargona_code,
                                            'lot_no' => $nc_village->lot_no,
                                            'vill_townprt_code' => $nc_village->vill_townprt_code,
                                            'uuid' => $nc_village->uuid,
                                            'pre_user' => $this->session->userdata('user_code'), 
                                            'cur_user' => $jds_code, 
                                            'pre_user_dig' => 'DC', 
                                            'cur_user_dig' => 'JDS', 
                                            'proceeding_type' => 2,
                                            'remark' => trim($remarks),
                                        ];

                        $output = $this->NcVillageModel->callApiV2($url, $method, $update_data);
                        $response = $output ? json_decode($output, true) : [];
                        if($response['status'] != 1){
                            $this->db->trans_rollback();
                            return false;
                        }
                        $this->db->trans_commit();
                        return true;
                    }
                } else {
                    $this->db->trans_rollback();
                    return false;
                }
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            return false;
        }
    }
}
