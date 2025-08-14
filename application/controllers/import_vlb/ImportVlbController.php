<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');

include APPPATH . '/libraries/CommonTrait.php';

class ImportVlbController extends CI_Controller
{
    use CommonTrait;

    function __construct()
    {
        parent::__construct();
        $this->load->helper('security');
        $this->load->helper(['form', 'url']);
        if (!($this->session->userdata('usertype') != '2' || $this->session->userdata('usertype') != '1')) {
            $data['heading'] = "ERROR:: 404 Page Not Found";
            $data['message'] = 'The page you requested was not found';
            $this->load->view('errors/html/error_404', $data);
            $this->CI = &get_instance();
            $this->CI->output->_display();
            die;
        }
    }
    public function select_village()
    {
        $data['districts'] = $this->Chithamodel->districtdetailsreport();
        $data['_view'] = 'import_vlb/import_vlb_select_village';
        $this->load->view('layout/layout', $data);
    }
    public function importVlb()
    {
        $this->load->model('RemarkModel');
        $this->dataswitch();
        $this->form_validation->set_rules('dist_code', 'District Name', 'trim|integer|required');
        $this->form_validation->set_rules('subdiv_code', 'Sub Division Name', 'trim|integer|required');
        $this->form_validation->set_rules('cir_code', 'Circle Name', 'trim|integer|required');
        $this->form_validation->set_rules('mouza_pargona_code', 'Mouza Name', 'trim|integer|required');
        $this->form_validation->set_rules('lot_no', 'Lot Number', 'trim|integer|required');
        $this->form_validation->set_rules('vill_townprt_code', 'Village Name', 'trim|integer|required');
        if ($this->form_validation->run()) {
            $dist_code   = $this->input->post('dist_code');
            $subdiv_code = $this->input->post('subdiv_code');
            $cir_code = $this->input->post('cir_code');
            $mouza_pargona_code  = $this->input->post('mouza_pargona_code');
            $lot_no      = $this->input->post('lot_no');
            $vill_townprt_code   = $this->input->post('vill_townprt_code');
            $uuid = $this->db->query("select uuid from location where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code'")->row()->uuid;

            $land_bank_details_exists = $this->db->get_where('land_bank_details', [
                'village_uuid' => $uuid
            ]);
            if ($land_bank_details_exists->num_rows() > 0) {
                $del_land_bank_enc_query = "DELETE FROM land_bank_encroacher_details where land_bank_details_id in (SELECT id FROM land_bank_details where dist_code = '$dist_code' and subdiv_code = '$subdiv_code' and cir_code = '$cir_code' and mouza_pargona_code = '$mouza_pargona_code' and lot_no = '$lot_no' and vill_townprt_code = '$vill_townprt_code')";
                $del_land_bank_proceeding_query = "DELETE FROM land_bank_proceeding_details where land_bank_details_id in (SELECT id FROM land_bank_details where dist_code = '$dist_code' and subdiv_code = '$subdiv_code' and cir_code = '$cir_code' and mouza_pargona_code = '$mouza_pargona_code' and lot_no = '$lot_no' and vill_townprt_code = '$vill_townprt_code')";
                $del_land_bank_query = "DELETE FROM land_bank_details  where dist_code = '$dist_code' and subdiv_code = '$subdiv_code' and cir_code = '$cir_code' and mouza_pargona_code = '$mouza_pargona_code' and lot_no = '$lot_no' and vill_townprt_code = '$vill_townprt_code'";

                $this->db->query($del_land_bank_enc_query);
                $this->db->query($del_land_bank_proceeding_query);
                $this->db->query($del_land_bank_query);
            }

            $c_land_bank_details_exists = $this->db->get_where('c_land_bank_details', [
                'village_uuid' => $uuid
            ]);
            if ($c_land_bank_details_exists->num_rows() > 0) {
                $del_c_land_bank_enc_query = "DELETE FROM c_land_bank_encroacher_details where c_land_bank_details_id in (SELECT id FROM c_land_bank_details where dist_code = '$dist_code' and subdiv_code = '$subdiv_code' and cir_code = '$cir_code' and mouza_pargona_code = '$mouza_pargona_code' and lot_no = '$lot_no' and vill_townprt_code = '$vill_townprt_code')";
                $del_c_land_bank_query = "DELETE FROM c_land_bank_details  where dist_code = '$dist_code' and subdiv_code = '$subdiv_code' and cir_code = '$cir_code' and mouza_pargona_code = '$mouza_pargona_code' and lot_no = '$lot_no' and vill_townprt_code = '$vill_townprt_code'";

                $this->db->query($del_c_land_bank_enc_query);
                $this->db->query($del_c_land_bank_query);
            }

            ini_set('max_execution_time', '0');
            ini_set('memory_limit', '10240M');
            $this->dataswitch();
            $path         = 'import_chitha/uploads/';
            $this->upload_config($path);
            if (!$this->upload->do_upload('csv')) {
                $this->session->set_flashdata('error', 'Failed to upload');
                $error = $this->upload->display_errors(); // Get error message
                log_message('error', 'Upload error: ' . $error); // Log the error
                dd($error);
            } else {
                $file_data     = $this->upload->data();
                $inserted_count = 0;
                $file_name     = $path . $file_data['file_name'];
                $arr_file     = explode('.', $file_name);
                $file = fopen($file_name, "r");
                $i = 0;
                $importData_arr = array();
                while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
                    $num = count($filedata);

                    for ($c = 0; $c < $num; $c++) {
                        $importData_arr[$i][] = $filedata[$c];
                    }
                    $i++;
                }
                fclose($file);
                if (count($importData_arr) > 0) {
                    unset($importData_arr[0]);
                    $importData_arr = array_values($importData_arr);
                    $this->db->trans_start();

                    foreach ($importData_arr as $record) {
                        if (!trim($record[1])) {
                            continue;
                        }


                        $land_bank_data = [
                            'dist_code' => $dist_code,
                            'subdiv_code' => $subdiv_code,
                            'cir_code' => $cir_code,
                            'mouza_pargona_code' => $mouza_pargona_code,
                            'lot_no' => $lot_no,
                            'vill_townprt_code' => $vill_townprt_code,
                            'dag_no' => trim($record[0]),
                            'created_at' => date("Y-m-d | h:i:sa"),
                            'en_area_b' => '0',
                            'en_area_k' => '0',
                            'en_area_lc' => '0',
                            'en_area_g' => '0',
                            'en_area_kr' => '0',
                            'user_code' => $this->session->userdata('usercode'),
                            'village_uuid' => $uuid,
                            'nature_of_reservation' => 7,
                            'whether_encroached' => 'Y',
                            'year' => '2023',
                            'no_of_encroacher' => count($importData_arr),
                            'status' => 'A'
                        ];

                        $c_land_bank_details_exists = $this->db->get_where('c_land_bank_details', [
                            'village_uuid' => $uuid,
                            'dag_no' => trim($record[0])
                        ])->row();
                        if ($c_land_bank_details_exists) {
                            $c_land_bank_lastid = $c_land_bank_details_exists->id;
                        } else {
                            //INSERT C_LAND_BANK_DETAILS
                            $this->db->insert('c_land_bank_details', $land_bank_data);
                            $c_land_bank_lastid = $this->db->insert_id();
                        }

                        $land_bank_details_exists = $this->db->get_where('land_bank_details', [
                            'village_uuid' => $uuid,
                            'dag_no' => trim($record[0])
                        ])->row();
                        if ($land_bank_details_exists) {
                            $land_bank_lastid = $land_bank_details_exists->id;
                        } else {
                            //INSERT LAND_BANK_DETAILS
                            $this->db->insert('land_bank_details', $land_bank_data);
                            $land_bank_lastid = $this->db->insert_id();
                        }

                        $this->db->insert('c_land_bank_encroacher_details', [
                            'c_land_bank_details_id' => $c_land_bank_lastid,
                            'name' => trim($record[1]),
                            'fathers_name' => trim($record[2]),
                            'encroachment_from' => date('Y-m-d'),
                            'encroachment_to' => date('Y-m-d'),
                            'landless_indigenous' => 'N',
                            'erosion' => 'N',
                            'landless' => '',
                            'gender' => 0,
                            'caste' => 0,
                            'created_at' => date("Y-m-d | h:i:sa"),
                            'landslide' => 'N',
                            'type_of_land_use' => 0,
                        ]);

                        $this->db->insert('land_bank_encroacher_details', [
                            'land_bank_details_id' => $land_bank_lastid,
                            'name' => trim($record[1]),
                            'fathers_name' => trim($record[2]),
                            'encroachment_from' => date('Y-m-d'),
                            'encroachment_to' => date('Y-m-d'),
                            'landless_indigenous' => 'N',
                            'erosion' => 'N',
                            'landless' => '',
                            'gender' => 0,
                            'caste' => 0,
                            'created_at' => date("Y-m-d | h:i:sa"),
                            'landslide' => 'N',
                            'type_of_land_use' => 0,
                        ]);
                        $inserted_count++;
                    }
                    $this->db->trans_complete();
                    if ($this->db->trans_status() == TRUE) {
                        $this->session->unset_userdata('error');
                        $this->session->set_flashdata('success', 'Import completed!. Total Inserted - ' . $inserted_count);
                    } else {
                        $error = $this->db->error();
                        dd($error);
                        $this->session->unset_userdata('success');
                        $this->session->set_flashdata('error', 'Failed to import.');
                    }
                } else {
                    $this->session->unset_userdata('error');
                    $this->session->set_flashdata('success', 'No Records found');
                }
            }
            redirect('import_vlb/ImportVlbController/select_village');
        } else {
            dd(validation_errors());
            $this->session->set_flashdata('error', 'Please select all the fields properly.');
            redirect('import_vlb/ImportVlbController/select_village');
        }
    }
    public function getClassCode($class)
    {
        $code = '0134';
        switch (trim($class)) {
            case 'BARI':
                $code = '0122';
                break;
            case 'KHETI':
                $code = '0101';
                break;
            default:
                # code...
                break;
        }
        return $code;
    }
    public function upload_config($path)
    {
        if (!is_dir($path))
            mkdir($path, 0777, TRUE);
        $config['upload_path']         = './' . $path;
        $config['allowed_types']     = 'csv';
        $config['max_filename']         = '255';
        $config['encrypt_name']     = TRUE;
        $config['max_size']         = 4096;
        $this->load->library('upload', $config);
    }
}
