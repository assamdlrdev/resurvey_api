<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');

include APPPATH . '/libraries/CommonTrait.php';

class ImportChithaController extends CI_Controller
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
        $data['_view'] = 'import_chitha/import_chitha_select_village';
        $this->load->view('layout/layout', $data);
    }
    public function select_village_encroent()
    {
        $data['districts'] = $this->Chithamodel->districtdetailsreport();
        $data['_view'] = 'import_chitha/import_chitha_withencro_select_village';
        $this->load->view('layout/layout', $data);
    }
    public function importVillageChitha()
    {
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

            ini_set('max_execution_time', '0');
            ini_set('memory_limit', '10240M');
            $this->dataswitch();
            $path         = 'import_chitha/uploads/';
            $this->upload_config($path);
            if (!$this->upload->do_upload('xl')) {
                $this->session->set_flashdata('error', 'Failed to upload');
            } else {
                $file_data     = $this->upload->data();
                $updated_count = 0;
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
                        $q = $this->db->get_where('chitha_basic', [
                            'dist_code' => $dist_code,
                            'subdiv_code' => $subdiv_code,
                            'cir_code' => $cir_code,
                            'mouza_pargona_code' => $mouza_pargona_code,
                            'lot_no' => $lot_no,
                            'vill_townprt_code' => $vill_townprt_code,
                            'dag_no' => $record[0]
                        ]);
                        if ($q->num_rows() > 0) {
                            $this->db->update('chitha_basic', [
                                'dag_area_b' => $record[1],
                                'dag_area_k' => $record[2],
                                'dag_area_lc' => $record[3],
                            ], [
                                'dist_code' => $dist_code,
                                'subdiv_code' => $subdiv_code,
                                'cir_code' => $cir_code,
                                'mouza_pargona_code' => $mouza_pargona_code,
                                'lot_no' => $lot_no,
                                'vill_townprt_code' => $vill_townprt_code,
                                'dag_no' => $record[0]
                            ]);
                            $updated_count++;
                        } else {
                            $this->db->insert('chitha_basic',  [
                                'dist_code' => $dist_code,
                                'subdiv_code' => $subdiv_code,
                                'cir_code' => $cir_code,
                                'mouza_pargona_code' => $mouza_pargona_code,
                                'lot_no' => $lot_no,
                                'vill_townprt_code' => $vill_townprt_code,
                                'dag_no' => $record[0],
                                'dag_no_int' => $record[0] * 100,
                                'dag_area_b' => $record[1],
                                'dag_area_k' => $record[2],
                                'dag_area_lc' => $record[3],
                                'dag_area_g' => 0,
                                'dag_area_kr' => 0,
                                'patta_type_code' => '0209',
                                'patta_no' => '0',
                                'category_id' => $record[5],
                                'land_class_code' => '0134',
                                'user_code' => 'admin',
                                'date_entry' => date("Y-m-d | h:i:sa"),
                                'operation' => 'E',
                                'jama_yn' => 'n',
                            ]);
                            $inserted_count++;
                        }
                    }
                    $this->db->trans_complete();
                    if ($this->db->trans_status() == TRUE) {
                        $this->session->set_flashdata('success', 'Import completed!. Updated dags :' . $updated_count . ' , Inserted Dags : ' . $inserted_count);
                    } else {
                        $this->session->set_flashdata('error', 'Failed to import.');
                    }
                } else {
                    $this->session->set_flashdata('success', 'No Records found');
                }
            }
            $this->select_village();
        } else {
            $this->select_village();
        }
    }
    public function importVillageChithaNencroacher()
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
            // dd($dist_code.'-'.$subdiv_code.'-'.$cir_code.'-'.$mouza_pargona_code.'-'.$lot_no.'-'.$vill);
            $q2 = $this->db->get_where('chitha_basic', [
                'dist_code' => $dist_code,
                'subdiv_code' => $subdiv_code,
                'cir_code' => $cir_code,
                'mouza_pargona_code' => $mouza_pargona_code,
                'lot_no' => $lot_no,
                'vill_townprt_code' => $vill_townprt_code
            ]);
            if ($q2->num_rows() > 0) {
                $this->session->unset_userdata('success');
                $this->session->set_flashdata('error', 'Import failed. Data is already available on the chitha.');
                redirect('import_chitha/ImportChithaController/select_village_encroent');
                return;
            }

            ini_set('max_execution_time', '0');
            ini_set('memory_limit', '10240M');
            $this->dataswitch();
            $path         = 'import_chitha/uploads/';
            $this->upload_config($path);
            if (!$this->upload->do_upload('xl')) {
                $this->session->set_flashdata('error', 'Failed to upload');
            } else {
                $file_data     = $this->upload->data();
                $updated_count = 0;
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
                        if (!trim($record[0])) {
                            continue;
                        }

                        $q = $this->db->get_where('chitha_basic', [
                            'dist_code' => $dist_code,
                            'subdiv_code' => $subdiv_code,
                            'cir_code' => $cir_code,
                            'mouza_pargona_code' => $mouza_pargona_code,
                            'lot_no' => $lot_no,
                            'vill_townprt_code' => $vill_townprt_code,
                            'dag_no' => trim($record[0])
                        ]);
                        if ($q->num_rows() > 0) {
                            // $this->db->update('chitha_basic', [
                            //     'dag_area_b' => $record[1],
                            //     'dag_area_k' => $record[2],
                            //     'dag_area_lc' => $record[3],
                            // ], [
                            //     'dist_code' => $dist_code,
                            //     'subdiv_code' => $subdiv_code,
                            //     'cir_code' => $cir_code,
                            //     'mouza_pargona_code' => $mouza_pargona_code,
                            //     'lot_no' => $lot_no,
                            //     'vill_townprt_code' => $vill_townprt_code,
                            //     'dag_no' => $record[0]
                            // ]);
                            $updated_count++;
                        } else if (trim($record[0])) {
                            $this->db->insert('chitha_basic',  [
                                'dist_code' => $dist_code,
                                'subdiv_code' => $subdiv_code,
                                'cir_code' => $cir_code,
                                'mouza_pargona_code' => $mouza_pargona_code,
                                'lot_no' => $lot_no,
                                'vill_townprt_code' => $vill_townprt_code,
                                'dag_no' => trim($record[0]),
                                'dag_no_int' => trim($record[0]) * 100,
                                'dag_area_b' => 0,
                                'dag_area_k' => 0,
                                'dag_area_lc' => 0,
                                'dag_area_g' => 0,
                                'dag_area_kr' => 0,
                                'patta_type_code' => '0209',
                                'patta_no' => '0',
                                'land_class_code' => trim($record[3]) ? $this->getClassCode(trim($record[3])) : '0134',
                                'user_code' => $this->session->userdata('usercode'),
                                'date_entry' => date("Y-m-d | h:i:sa"),
                                'operation' => 'E',
                                'jama_yn' => 'n',
                                'category_id' => trim($record[6])
                            ]);
                            $inserted_count++;
                        }
                        //insert encroacher
                        $occupier = array(
                            'dist_code' => $dist_code,
                            'subdiv_code' => $subdiv_code,
                            'cir_code' => $cir_code,
                            'mouza_pargona_code' => $mouza_pargona_code,
                            'lot_no' => $lot_no,
                            'vill_townprt_code' => $vill_townprt_code,
                            'dag_no' => trim($record[0]),
                        );

                        if (trim($record[1])) {
                            $where = "(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' and trim(dag_no)=trim('$record[0]'))";
                            $this->db->select_max('encro_id', 'max');
                            $query = $this->db->get_where('chitha_rmk_encro', $where);
                            if ($query->num_rows() == 0) {
                                $encro_id = 1;
                            } else {
                                $max = $query->row()->max;
                                $encro_id = $max == 0 ? 1 : $max + 1;
                            }
                            $occupier['encro_id'] = $encro_id;
                            $occupier['rmk_type_hist_no'] = $encro_id;
                            $occupier['encro_name'] = trim($record[1]);
                            $occupier['encro_guardian'] = trim($record[2]);
                            $occupier['mobile'] = strlen(trim($record[4])) == 10 ? trim($record[4]) : null;
                            $occupier['user_code'] = $this->session->userdata('usercode');
                            $occupier['date_entry'] = date("Y-m-d | h:i:sa");
                            $occupier['operation'] = 'D';
                            $occupier['co_approval'] = '00';
                            $occupier['encro_land_b'] = 0;
                            $occupier['encro_land_k'] = 0;
                            $occupier['encro_land_lc'] = 0;
                            $occupier['encro_land_g'] = 0;
                            $this->RemarkModel->add_encroacherdeails($occupier);
                        }
                    }
                    $this->db->trans_complete();
                    if ($this->db->trans_status() == TRUE) {
                        $this->session->unset_userdata('error');
                        $this->session->set_flashdata('success', 'Import completed!. Updated dags :' . $updated_count . ' , Inserted Dags : ' . $inserted_count);
                    } else {
                        $this->session->unset_userdata('success');
                        $this->session->set_flashdata('error', 'Failed to import.');
                    }
                } else {
                    $this->session->unset_userdata('error');
                    $this->session->set_flashdata('success', 'No Records found');
                }
            }
            redirect('import_chitha/ImportChithaController/select_village_encroent');
        } else {
            redirect('import_chitha/ImportChithaController/select_village_encroent');
        }
    }
    public function getClassCode($class)
    {
        $code = '0134';
        switch (trim($class)) {
            case 'LAHI':
                $code = '0102';
                break;
            case 'BAO':
                $code = '0103';
                break;
            case 'FARING':
                $code = '0104';
                break;
            case 'JALATAK':
                $code = '0106';
                break;
            case 'AHU':
                $code = '0117';
                break;
            case 'GORABAT':
                $code = '0127';
                break;
            case 'RESERVE':
                $code = '0128';
                break;
            case 'JALATAN':
                $code = '0167';
                break;
            case 'JALATANBIL':
                $code = '0168';
                break;
            case 'HILL':
                $code = '0176';
                break;
            case 'JALDOBA':
                $code = '0177';
                break;
            case 'KHETI':
                $code = '0101';
                break;
            case 'SPFARING':
                $code = '0105';
                break;
            case 'TG':
                $code = '0116';
                break;
            case 'DONG':
                $code = '0185';
                break;
            case 'MARKET':
                $code = '0115';
                break;
            case 'BARI':
                $code = '0122';
                break;
            case 'INDUSTRY':
                $code = '0140';
                break;
            case 'RIVER':
                $code = '0179';
                break;
            case 'JAAN':
                $code = '0180';
                break;
            case 'STREAM':
                $code = '0181';
                break;
            case 'SWAMP':
                $code = '0183';
                break;
            case 'WATERBODY':
                $code = '0184';
                break;
            case 'HISTIRICAL_PLACE':
                $code = '0188';
                break;
            case 'ARCHOLOGICAL_PLACE':
                $code = '0189';
                break;
            case 'BHATA':
                $code = '0190';
                break;
            default:
                $code = '0134';
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
