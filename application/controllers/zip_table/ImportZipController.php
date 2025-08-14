<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class ImportZipController extends CI_Controller
{
    use CommonTrait;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('UserModel');
        if (!in_array($this->session->userdata('usertype'), [$this->UserModel::$ADMIN_CODE, $this->UserModel::$SUPERADMIN_CODE])) {
            show_error('<svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><g fill="none" stroke="#FF0000" stroke-linecap="round" stroke-width="2"><path d="M12 9v5m0 3.5v.5"/><path stroke-linejoin="round" d="M2.232 19.016L10.35 3.052c.713-1.403 2.59-1.403 3.302 0l8.117 15.964C22.45 20.36 21.544 22 20.116 22H3.883c-1.427 0-2.334-1.64-1.65-2.984Z"/></g></svg> <p>Unauthorized access</p>', "403");
        }
        $this->load->model('ZiptableModel');
        $this->load->model('MasterModel');
        $this->load->model('CommonModel');
    }
    public function index()
    {
        $add_queries = $this->CommonModel::$ADD_QUERIES;
        $fks = [];
        foreach ($add_queries as $add_query) {
            $fks[] = $add_query['name'];
        }
        $data['add_queries'] = $add_queries;
        $data['fks'] = $fks;
        $data['_view'] = 'import_zip/select_location';
        $this->load->view('layout/layout', $data);
    }
    public function updateVlbSeq()
    {
        $sequences = [
            'land_bank_details_id_seq',
            'land_bank_encroacher_details_id_seq',
            'land_bank_proceeding_details_id_seq',
            'c_land_bank_details_id_seq',
            'c_land_bank_encroacher_details_id_seq'
        ];
        foreach ($sequences as $seq) {
            $this->db->query("SELECT nextval('$seq')");
        }
        return true;
    }
    public function ImportZip()
    {
        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '10240M');
        $this->dataswitch();
        $this->updateVlbSeq();
        $this->load->helper('directory');
        $config['upload_path']          = 'export/uploads/';
        $config['allowed_types']        = 'gif|jpg|png|zip';
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('zip')) {
            $data['error'] = array('error' => $this->upload->display_errors());
        } else {
            $uploadData = $this->upload->data();
            $filename = $uploadData['file_name'];
            $zip = new ZipArchive;
            $res = $zip->open("export/uploads/" . $filename);
            if ($res === TRUE) {
                $extractpath = "export/extract/";
                $zip->extractTo($extractpath);
                $zip->close();

                $this->load->helper("file");
                unlink(FCPATH . 'export/uploads/' . $filename);

                $dir = $this->ZiptableModel->getDirName($filename);
                $loc_codes = $this->ZiptableModel->getLocCodes($filename);

                $files = directory_map("export/extract/" . $dir);
                foreach ($files as $file_single) {
                    $table_name = explode(".", $file_single)[0];
                    if (!in_array($table_name, ['land_bank_details', 'land_bank_encroacher_details', 'land_bank_proceeding_details', 'c_land_bank_details', 'c_land_bank_encroacher_details'])) {
                        $fields = $this->db->field_data($table_name);
                        $file = fopen("export/extract/" . $dir . '/' . $file_single, "r");
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
                            $col_names = ($importData_arr[0]);
                            unset($importData_arr[0]);
                            $importData_arr = array_values($importData_arr);
                            $this->db->trans_start();
                            $this->db->delete($table_name, $loc_codes);
                            $records_updated = [];
                            foreach ($importData_arr as $record) {
                                $record_array = [];
                                foreach ($col_names as $key => $col_name) {
                                    $col_type = null;
                                    foreach ($fields as $field) {
                                        if ($field->name == $col_name) {
                                            $col_type = $field->type;
                                        }
                                    }
                                    if ($col_type == 'bigint') {
                                        if ($record[$key]) {
                                            $record_array[$col_name] = (int)$record[$key];
                                        }
                                    } else if ($col_type == 'smallint' || $col_type == 'integer') {
                                        $record_array[$col_name] = (int)$record[$key];
                                    } else if ($col_type == 'numeric') {
                                        $record_array[$col_name] = (float)$record[$key];
                                    } else if ($col_type == 'timestamp without time zone') {
                                        if ($record[$key]) {
                                            $record_array[$col_name] = $record[$key];
                                        }
                                    } else {
                                        $record_array[$col_name] = $record[$key];
                                    }
                                }
                                $records_updated[] = $record_array;
                            }
                            if(count($records_updated)){
                                $this->db->insert_batch($table_name, $records_updated);
                            }
                            $this->db->trans_complete();
                        }
                    }
                }
                if ($this->db->trans_status() == TRUE) {
                    //VLB TABLES
                    $status = $this->importVlb($dir, $loc_codes);
                    if ($status == TRUE) {
                        $this->session->set_flashdata('success', 'Uploaded & Extracted all data successfully');
                    } else {
                        $this->session->set_flashdata('error', 'Failed to upload VLB data but other tables are uploaded');
                    }
                } else {
                    $this->session->set_flashdata('error', 'Failed to extract');
                }
            } else {
                $this->session->set_flashdata('error', 'Failed to extract');
            }
        }
        $this->index();
    }
    public function importVlb($dir, $loc_codes)
    {
        $dist_code = $loc_codes['dist_code'];
        $subdiv_code = $loc_codes['subdiv_code'];
        $cir_code = $loc_codes['cir_code'];
        $mouza_pargona_code = $loc_codes['mouza_pargona_code'];
        $lot_no = $loc_codes['lot_no'];
        $vill_townprt_code = $loc_codes['vill_townprt_code'];

        //get land_bank_details from csv
        $table_name = 'land_bank_details';
        $fields = $this->db->field_data($table_name);
        $file = fopen("export/extract/" . $dir . '/' . $table_name . '.csv', "r");
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

        //get land_bank_encroacher_details from csv
        $fields_lb_enc = $this->db->field_data('land_bank_encroacher_details');
        $file_lb_enc = fopen("export/extract/" . $dir . '/land_bank_encroacher_details.csv', "r");
        $i = 0;
        $importData_arr_lb_enc = array();
        while (($filedata = fgetcsv($file_lb_enc, 1000, ",")) !== FALSE) {
            $num = count($filedata);

            for ($c = 0; $c < $num; $c++) {
                $importData_arr_lb_enc[$i][] = $filedata[$c];
            }
            $i++;
        }
        fclose($file_lb_enc);

        //get land_bank_proceeding_details from csv
        $fields_lb_proceeding = $this->db->field_data('land_bank_proceeding_details');
        $file_lb_proceeding = fopen("export/extract/" . $dir . '/land_bank_proceeding_details.csv', "r");
        $i = 0;
        $importData_arr_lb_proceeding = array();
        while (($filedata = fgetcsv($file_lb_proceeding, 1000, ",")) !== FALSE) {
            $num = count($filedata);

            for ($c = 0; $c < $num; $c++) {
                $importData_arr_lb_proceeding[$i][] = $filedata[$c];
            }
            $i++;
        }
        fclose($file_lb_proceeding);

        //get c_land_bank_details from csv
        $table_name = 'c_land_bank_details';
        $fields = $this->db->field_data($table_name);
        $file = fopen("export/extract/" . $dir . '/' . $table_name . '.csv', "r");
        $i = 0;
        $importData_arr_c_lbs = array();
        while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
            $num = count($filedata);

            for ($c = 0; $c < $num; $c++) {
                $importData_arr_c_lbs[$i][] = $filedata[$c];
            }
            $i++;
        }
        fclose($file);

        //get c_land_bank_encroacher_details from csv
        $table_name = 'c_land_bank_encroacher_details';
        $fields_c_lb_encs = $this->db->field_data($table_name);
        $file = fopen("export/extract/" . $dir . '/' . $table_name . '.csv', "r");
        $i = 0;
        $importData_arr_c_lb_encs = array();
        while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
            $num = count($filedata);

            for ($c = 0; $c < $num; $c++) {
                $importData_arr_c_lb_encs[$i][] = $filedata[$c];
            }
            $i++;
        }
        fclose($file);

        $this->db->trans_start();

        if (count($importData_arr) > 0) {
            $col_names = ($importData_arr[0]);
            unset($importData_arr[0]);
            $importData_arr = array_values($importData_arr);

            $del_land_bank_enc_query = "DELETE FROM land_bank_encroacher_details where land_bank_details_id in (SELECT id FROM land_bank_details where dist_code = '$dist_code' and subdiv_code = '$subdiv_code' and cir_code = '$cir_code' and mouza_pargona_code = '$mouza_pargona_code' and lot_no = '$lot_no' and vill_townprt_code = '$vill_townprt_code')";
            $del_land_bank_proceeding_query = "DELETE FROM land_bank_proceeding_details where land_bank_details_id in (SELECT id FROM land_bank_details where dist_code = '$dist_code' and subdiv_code = '$subdiv_code' and cir_code = '$cir_code' and mouza_pargona_code = '$mouza_pargona_code' and lot_no = '$lot_no' and vill_townprt_code = '$vill_townprt_code')";
            $del_land_bank_query = "DELETE FROM land_bank_details  where dist_code = '$dist_code' and subdiv_code = '$subdiv_code' and cir_code = '$cir_code' and mouza_pargona_code = '$mouza_pargona_code' and lot_no = '$lot_no' and vill_townprt_code = '$vill_townprt_code'";


            $this->db->query($del_land_bank_enc_query);
            $this->db->query($del_land_bank_proceeding_query);
            $this->db->query($del_land_bank_query);


            foreach ($importData_arr as $record) {
                $record_array = [];
                $lb_id = null;
                foreach ($col_names as $key => $col_name) {
                    if ($col_name == 'id') {
                        $lb_id = $record[$key];
                    }
                    if ($col_name != 'id') {
                        $col_type = null;
                        foreach ($fields as $field) {
                            if ($field->name == $col_name) {
                                $col_type = $field->type;
                            }
                        }
                        if ($col_type == 'bigint') {
                            if ($record[$key]) {
                                $record_array[$col_name] = (int)$record[$key];
                            }
                        } else if ($col_type == 'smallint' || $col_type == 'integer') {
                            $record_array[$col_name] = (int)$record[$key];
                        } else if ($col_type == 'numeric') {
                            $record_array[$col_name] = (float)$record[$key];
                        } else if ($col_type == 'timestamp without time zone') {
                            if ($record[$key]) {
                                $record_array[$col_name] = $record[$key];
                            }
                        } else {
                            $record_array[$col_name] = $record[$key];
                        }
                    }
                }
                $this->db->insert('land_bank_details', $record_array);
                $new_lb_id = $this->db->insert_id();
                //INSERT LB_ENCROACHERS
                $land_bank_encroachers = $this->getLandbankEncroachers($lb_id, $importData_arr_lb_enc, $new_lb_id, $fields_lb_enc);
                if (count($land_bank_encroachers)) {
                    $this->db->insert_batch('land_bank_encroacher_details', $land_bank_encroachers);
                }

                //INSERT LB_PROCEEDING_DETAILS
                $land_bank_proceeding_details = $this->getLandbankProceeding($lb_id, $importData_arr_lb_proceeding, $new_lb_id, $fields_lb_proceeding);
                if (count($land_bank_proceeding_details)) {
                    $this->db->insert_batch('land_bank_proceeding_details', $land_bank_proceeding_details);
                }
            }
        }

        if (count($importData_arr_c_lbs) > 0) {
            $col_names = ($importData_arr_c_lbs[0]);
            unset($importData_arr_c_lbs[0]);
            $importData_arr_c_lbs = array_values($importData_arr_c_lbs);

            $del_c_land_bank_enc_query = "DELETE FROM c_land_bank_encroacher_details where c_land_bank_details_id in (SELECT id FROM c_land_bank_details where dist_code = '$dist_code' and subdiv_code = '$subdiv_code' and cir_code = '$cir_code' and mouza_pargona_code = '$mouza_pargona_code' and lot_no = '$lot_no' and vill_townprt_code = '$vill_townprt_code')";
            $del_c_land_bank_query = "DELETE FROM c_land_bank_details  where dist_code = '$dist_code' and subdiv_code = '$subdiv_code' and cir_code = '$cir_code' and mouza_pargona_code = '$mouza_pargona_code' and lot_no = '$lot_no' and vill_townprt_code = '$vill_townprt_code'";


            $this->db->query($del_c_land_bank_enc_query);
            $this->db->query($del_c_land_bank_query);


            foreach ($importData_arr_c_lbs as $record) {
                $record_array = [];
                $lb_id = null;
                foreach ($col_names as $key => $col_name) {
                    if ($col_name == 'id') {
                        $lb_id = $record[$key];
                    }
                    $col_type = null;
                    foreach ($fields as $field) {
                        if ($field->name == $col_name) {
                            $col_type = $field->type;
                        }
                    }
                    if ($col_name != 'id') {
                        if ($col_type == 'bigint') {
                            if ($record[$key]) {
                                $record_array[$col_name] = (int)$record[$key];
                            }
                        } else if ($col_type == 'smallint' || $col_type == 'integer') {
                            $record_array[$col_name] = (int)$record[$key];
                        } else if ($col_type == 'numeric') {
                            $record_array[$col_name] = (float)$record[$key];
                        } else if ($col_type == 'timestamp without time zone') {
                            if ($record[$key]) {
                                $record_array[$col_name] = $record[$key];
                            }
                        } else {
                            $record_array[$col_name] = $record[$key];
                        }
                    }
                }
                $this->db->insert('c_land_bank_details', $record_array);
                $c_new_lb_id = $this->db->insert_id();

                //INSERT C_LB_ENCROACHERS
                $c_land_bank_encroachers = $this->getCLandbankEncroachers($lb_id, $importData_arr_c_lb_encs, $c_new_lb_id, $fields_c_lb_encs);
                if (count($c_land_bank_encroachers)) {
                    $this->db->insert_batch('c_land_bank_encroacher_details', $c_land_bank_encroachers);
                }
            }
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    public function getLandbankEncroachers($land_bank_details_id, $lb_encroachers, $new_lb_id, $fields)
    {
        $col_names = $lb_encroachers[0];
        $columnToFilter = 'land_bank_details_id';
        $columnIndex = array_search($columnToFilter, $col_names);
        $encs = [];
        foreach ($lb_encroachers as $enc) {
            $enc_single = [];
            if ($enc[$columnIndex] == $land_bank_details_id) {
                foreach ($col_names as $key => $col_name) {
                    $col_type = null;
                    foreach ($fields as $field) {
                        if ($field->name == $col_name) {
                            $col_type = $field->type;
                        }
                    }
                    if ($col_name != 'id') {
                        if ($col_type == 'bigint') {
                            if ($enc[$key]) {
                                $enc_single[$col_name] = (int)$enc[$key];
                            }
                        } else if ($col_type == 'smallint' || $col_type == 'integer') {
                            $enc_single[$col_name] = (int)$enc[$key];
                        } else if ($col_type == 'numeric') {
                            $enc_single[$col_name] = (float)$enc[$key];
                        } else if ($col_type == 'timestamp without time zone') {
                            if ($enc[$key]) {
                                $enc_single[$col_name] = $enc[$key];
                            }
                        } else {
                            $enc_single[$col_name] = $enc[$key];
                        }
                    }
                }
                $enc_single['land_bank_details_id'] = $new_lb_id;
                $encs[] = $enc_single;
            }
        }
        return $encs;
    }
    public function getLandbankProceeding($land_bank_details_id, $land_bank_proceedings, $new_lb_id, $fields)
    {
        $col_names = $land_bank_proceedings[0];
        $columnToFilter = 'land_bank_details_id';
        $columnIndex = array_search($columnToFilter, $col_names);
        $lb_proceedings = [];
        foreach ($land_bank_proceedings as $enc) {
            $lb_proceeding_single = [];
            if ($enc[$columnIndex] == $land_bank_details_id) {
                foreach ($col_names as $key => $col_name) {
                    $col_type = null;
                    foreach ($fields as $field) {
                        if ($field->name == $col_name) {
                            $col_type = $field->type;
                        }
                    }
                    if ($col_name != 'id') {
                        if ($col_type == 'bigint') {
                            if ($enc[$key]) {
                                $lb_proceeding_single[$col_name] = (int)$enc[$key];
                            }
                        } else if ($col_type == 'smallint' || $col_type == 'integer') {
                            $lb_proceeding_single[$col_name] = (int)$enc[$key];
                        } else if ($col_type == 'numeric') {
                            $lb_proceeding_single[$col_name] = (float)$enc[$key];
                        } else if ($col_type == 'timestamp without time zone') {
                            if ($enc[$key]) {
                                $lb_proceeding_single[$col_name] = $enc[$key];
                            }
                        } else {
                            $lb_proceeding_single[$col_name] = $enc[$key];
                        }
                    }
                }
                $lb_proceeding_single['land_bank_details_id'] = $new_lb_id;
                $lb_proceedings[] = $lb_proceeding_single;
            }
        }
        return $lb_proceedings;
    }
    public function getCLandbankEncroachers($land_bank_details_id, $c_lb_encroachers, $new_c_lb_id, $fields)
    {
        $col_names = $c_lb_encroachers[0];
        $columnToFilter = 'c_land_bank_details_id';
        $columnIndex = array_search($columnToFilter, $col_names);
        $encs = [];
        foreach ($c_lb_encroachers as $enc) {
            $enc_single = [];
            if ($enc[$columnIndex] == $land_bank_details_id) {
                foreach ($col_names as $key => $col_name) {
                    $col_type = null;
                    foreach ($fields as $field) {
                        if ($field->name == $col_name) {
                            $col_type = $field->type;
                        }
                    }
                    if ($col_name != 'id') {
                        if ($col_type == 'bigint') {
                            if ($enc[$key]) {
                                $enc_single[$col_name] = (int)$enc[$key];
                            }
                        } else if ($col_type == 'smallint' || $col_type == 'integer') {
                            $enc_single[$col_name] = (int)$enc[$key];
                        } else if ($col_type == 'numeric') {
                            $enc_single[$col_name] = (float)$enc[$key];
                        } else if ($col_type == 'timestamp without time zone') {
                            if ($enc[$key]) {
                                $enc_single[$col_name] = $enc[$key];
                            }
                        } else {
                            $enc_single[$col_name] = $enc[$key];
                        }
                    }
                }
                $enc_single['c_land_bank_details_id'] = $new_c_lb_id;
                $encs[] = $enc_single;
            }
        }
        return $encs;
    }
}
