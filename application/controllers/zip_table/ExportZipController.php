<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class ExportZipController extends CI_Controller
{
    use CommonTrait;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ZiptableModel');
        $this->load->model('MasterModel');
    }
    public function index()
    {
        $this->dataswitch();
        $data['district'] = $this->Chithamodel->districtdetails($this->session->userdata('dcode'));
        $data['sub_divs'] = $this->Chithamodel->subdivisiondetails($this->session->userdata('dcode'));
        $data['_view'] = 'export_zip/select_location';
        $this->load->view('layout/layout', $data);
    }
    public function exportToZip()
    {
        $this->dataswitch();
        $this->load->helper('csv');
        $this->load->library('zip');
        $this->load->dbutil();
        $this->load->helper('file');

        $dist_code = $this->input->post('dist_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $vill_townprt_code = $this->input->post('vill_townprt_code');
        $submit_type = $this->input->post('submit_type');

        try {
            $file_names = [];
            $tmpFolderPath = 'export/tmp/' . $dist_code . '-' . $subdiv_code . '-' . $cir_code . '-' . $mouza_pargona_code . '-' . $lot_no . '-' . $vill_townprt_code . '/';
            $zipFolderPath = 'export/zip/';

            if (!is_dir($tmpFolderPath)) {
                mkdir($tmpFolderPath, 0777, true);
            }
            if (!is_dir($zipFolderPath)) {
                mkdir($zipFolderPath, 0777, true);
            }
            foreach ($this->ZiptableModel::$TABLES as $table) {
                $delimiter = ",";
                $newline = "\r\n";

                if ($table == 'land_bank_details') {
                    if ($this->db->table_exists('land_bank_details')) {
                        $filename = $tmpFolderPath . 'land_bank_details.csv';
                        $file_names[] = $filename;
                        $land_bank_query = "SELECT * FROM $table where dist_code = '$dist_code' and subdiv_code = '$subdiv_code' and cir_code = '$cir_code' and mouza_pargona_code = '$mouza_pargona_code' and lot_no = '$lot_no' and vill_townprt_code = '$vill_townprt_code'";
                        $result = $this->db->query($land_bank_query);
                        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
                        write_file($filename, $data);
                    }

                    if ($this->db->table_exists('land_bank_encroacher_details')) {
                        $filename = $tmpFolderPath . 'land_bank_encroacher_details.csv';
                        $file_names[] = $filename;
                        $land_bank_enc_query = "SELECT * FROM land_bank_encroacher_details where land_bank_details_id in (SELECT id FROM land_bank_details where dist_code = '$dist_code' and subdiv_code = '$subdiv_code' and cir_code = '$cir_code' and mouza_pargona_code = '$mouza_pargona_code' and lot_no = '$lot_no' and vill_townprt_code = '$vill_townprt_code')";
                        $result = $this->db->query($land_bank_enc_query);
                        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
                        write_file($filename, $data);
                    }

                    if ($this->db->table_exists('land_bank_proceeding_details')) {
                        $filename = $tmpFolderPath . 'land_bank_proceeding_details.csv';
                        $file_names[] = $filename;
                        $land_bank_proceeding_query = "SELECT * FROM land_bank_proceeding_details where land_bank_details_id in (SELECT id FROM land_bank_details where dist_code = '$dist_code' and subdiv_code = '$subdiv_code' and cir_code = '$cir_code' and mouza_pargona_code = '$mouza_pargona_code' and lot_no = '$lot_no' and vill_townprt_code = '$vill_townprt_code')";
                        $result = $this->db->query($land_bank_proceeding_query);
                        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
                        write_file($filename, $data);
                    }

                    if ($this->db->table_exists('c_land_bank_details')) {
                        $filename = $tmpFolderPath . 'c_land_bank_details.csv';
                        $file_names[] = $filename;
                        $c_land_bank_query = "SELECT * FROM c_land_bank_details where dist_code = '$dist_code' and subdiv_code = '$subdiv_code' and cir_code = '$cir_code' and mouza_pargona_code = '$mouza_pargona_code' and lot_no = '$lot_no' and vill_townprt_code = '$vill_townprt_code'";
                        $result = $this->db->query($c_land_bank_query);
                        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
                        write_file($filename, $data);
                    }

                    if ($this->db->table_exists('c_land_bank_encroacher_details')) {
                        $filename = $tmpFolderPath . 'c_land_bank_encroacher_details.csv';
                        $file_names[] = $filename;
                        $c_land_bank_enc_query = "SELECT * FROM c_land_bank_encroacher_details where c_land_bank_details_id in (SELECT id FROM c_land_bank_details where dist_code = '$dist_code' and subdiv_code = '$subdiv_code' and cir_code = '$cir_code' and mouza_pargona_code = '$mouza_pargona_code' and lot_no = '$lot_no' and vill_townprt_code = '$vill_townprt_code')";
                        $result = $this->db->query($c_land_bank_enc_query);
                        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
                        write_file($filename, $data);
                    }
                } else {
                    $filename = $tmpFolderPath . $table . '.csv';
                    $file_names[] = $filename;

                    $query = "SELECT * FROM $table where dist_code = '$dist_code' and subdiv_code = '$subdiv_code' and cir_code = '$cir_code' and mouza_pargona_code = '$mouza_pargona_code' and lot_no = '$lot_no' and vill_townprt_code = '$vill_townprt_code'";
                    $result = $this->db->query($query);

                    $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
                    write_file($filename, $data);
                }
            }
            $this->zip->read_dir($tmpFolderPath, FALSE);
            $this->zip->archive($zipFolderPath . $dist_code . '-' . $subdiv_code . '-' . $cir_code . '-' . $mouza_pargona_code . '-' . $lot_no . '-' . $vill_townprt_code . ".zip");
            echo json_encode(['status' => '1', 'msg' => 'Exported successfully']);
        } catch (\Throwable $th) {
            echo json_encode($th);
        }
    }

    public function list_files_from_directory()
    {
        $this->dataswitch();
        $this->load->helper('directory');
        $dist_code = $this->input->post('dist_code');
        $subdiv_code = $this->input->post('subdiv_code');
        $cir_code = $this->input->post('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');

        $directory_path = 'export/zip';
        $files = directory_map($directory_path);
        $villages = [];
        foreach ($files as $file) {
            $name = explode('-', $file);
            $last_part = explode('.', $name[5]);
            $name[5] = $last_part[0];

            $f_dist_code  = $name[0];
            $f_subdiv_code  = $name[1];
            $f_cir_code  = $name[2];
            $f_mouza_pargona_code  = $name[3];
            $f_lot_no  = $name[4];
            $f_vill_townprt_code  = $name[5];

            if ($dist_code == $f_dist_code && $subdiv_code == $f_subdiv_code && $cir_code == $f_cir_code && $mouza_pargona_code == $f_mouza_pargona_code && $lot_no == $f_lot_no) {
                $vill['file'] = base_url() . 'index.php/zip_table/ExportZipController/downloadFile/' . $file;
                $vill['file_name'] = $file;
                $vill['vill_name'] = $this->db->select(['loc_name'])->where(array('dist_code' => $f_dist_code, 'subdiv_code' => $f_subdiv_code, 'cir_code' => $f_cir_code, 'mouza_pargona_code' => $f_mouza_pargona_code, 'lot_no' => $f_lot_no, 'vill_townprt_code' => $f_vill_townprt_code))->get('location')->row()->loc_name;
                $villages[] = $vill;
            }
        }
        echo json_encode(['villages' => $villages]);
    }
    public function downloadFile($file_name)
    {
        $this->load->helper('download');
        $file_path = FCPATH . 'export/zip/' . $file_name;

        if (file_exists($file_path)) {
            force_download($file_path, NULL);
        } else {
            show_error('File not found.');
        }
    }
    public function deleteZip()
    {
        $this->load->helper("file");
        $file = $this->input->post('file');
        try {
            if ($file) {
                $name = explode('-', $file);
                $last_part = explode('.', $name[5]);
                $name[5] = $last_part[0];

                $f_dist_code  = $name[0];
                $f_subdiv_code  = $name[1];
                $f_cir_code  = $name[2];
                $f_mouza_pargona_code  = $name[3];
                $f_lot_no  = $name[4];
                $f_vill_townprt_code  = $name[5];

                $dir = FCPATH . 'export/tmp/' . $f_dist_code . '-' . $f_subdiv_code . '-' . $f_cir_code . '-' . $f_mouza_pargona_code . '-' . $f_lot_no . '-' . $f_vill_townprt_code;
                delete_files($dir);

                unlink(FCPATH . 'export/zip/' . $file);
                echo json_encode(['status' => '1', 'msg' => 'Zip file removed successfully for this village']);
            }
        } catch (\Throwable $th) {
            echo json_encode(['status' => '0', 'msg' => $th]);
        }
    }
}
