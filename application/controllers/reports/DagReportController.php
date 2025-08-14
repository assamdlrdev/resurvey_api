<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class DagReportController extends CI_Controller
{
    use CommonTrait;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Chithamodel');
        $this->load->model('DagReportModel');
    }
    public function index()
    {
        if ($this->session->userdata('usertype') != '9') {
//            $this->dataswitch();
        }
        $data['base'] = $this->config->item('base_url');
        $data['districts'] = $this->Chithamodel->districtdetailsreport();
        if ($this->session->userdata('vill_townprt_code') and $this->session->userdata('current_url') == current_url()) {
            $dist = $this->session->userdata('sdcode');
            $subdiv = $this->session->userdata('ssubdiv_code');
            $circle = (string) $this->session->userdata('scir_code');
            $mza = (string) $this->session->userdata('smouza_pargona_code');
            $lot = (string) $this->session->userdata('slot_no');
            $vill = (string) $this->session->userdata('svill_townprt_code');
            $currentURL = (string) $this->session->userdata('current_url');

            // dd($vill);
            $data['locations'] = $this->Chithamodel->getSessionLoc($dist, $subdiv, $circle, $mza, $lot, $vill);
            // dd($data['locations']);
            $data['current_url'] = $currentURL;
        } else {
            $data['locations'] = null;
            $data['current_url'] = null;
        }
        $data['_view'] = 'reports/dag/location';
        $this->load->view('layout/layout', $data);
    }
    public function newLocation()
    {
        if ($this->session->userdata('usertype') != '9') {
            $this->dataswitch();
        }
        $data['base'] = $this->config->item('base_url');
        $data['districts'] = $this->Chithamodel->districtdetailsreport();
        if ($this->session->userdata('vill_townprt_code') and $this->session->userdata('current_url') == current_url()) {
            $dist = $this->session->userdata('sdcode');
            $subdiv = $this->session->userdata('ssubdiv_code');
            $circle = (string) $this->session->userdata('scir_code');
            $mza = (string) $this->session->userdata('smouza_pargona_code');
            $lot = (string) $this->session->userdata('slot_no');
            $vill = (string) $this->session->userdata('svill_townprt_code');
            $currentURL = (string) $this->session->userdata('current_url');

            // dd($vill);
            $data['locations'] = $this->Chithamodel->getSessionLoc($dist, $subdiv, $circle, $mza, $lot, $vill);
            // dd($data['locations']);
            $data['current_url'] = $currentURL;
        } else {
            $data['locations'] = null;
            $data['current_url'] = null;
        }
        $data['_view'] = 'reports/dag/location_new';
        $this->load->view('layout/layout', $data);
    }
    public function subdivisiondetails()
    {
        $data = [];
        $dist_code = $this->input->post('dis');
        $this->session->set_userdata('dcode', $dist_code);
        $this->dataswitch();
        $formdata = $this->Chithamodel->subdivisiondetails($dist_code);
        foreach ($formdata as $value) {
            $data['subdiv_code'][] = $value;
        }
        echo json_encode($data['subdiv_code']);
    }

    public function circledetails()
    {
        $this->dataswitch();
        $data = [];
        $dist_code = $this->input->post('dis');
        $subdiv = $this->input->post('subdiv');
        $cir_code = '';
        $formdata = $this->Chithamodel->circledetails($dist_code, $subdiv, $cir_code);
        foreach ($formdata as $value) {
            $data['cir_code'][] = $value;
        }
        echo json_encode($data['cir_code']);
    }

    public function mouzadetails()
    {
        $this->dataswitch();
        $data = [];
        $dis = $this->input->post('dis');
        $subdiv = $this->input->post('subdiv');
        $cir = $this->input->post('cir');
        $this->session->set_userdata('cir_code', $cir);
        $formdata = $this->Chithamodel->mouzadetails($dis, $subdiv, $cir);
        foreach ($formdata as $value) {
            $data['cir_code'][] = $value;
        }
        echo json_encode($data['cir_code']);
    }

    public function lotdetails()
    {
        $this->dataswitch();
        $data = [];
        $dis = $this->input->post('dis');
        $subdiv = $this->input->post('subdiv');
        $cir = $this->input->post('cir');
        $mza = $this->input->post('mza');
        $this->session->set_userdata('mouza_pargona_code', $mza);
        $formdata = $this->Chithamodel->lotdetails($dis, $subdiv, $cir, $mza);
        foreach ($formdata as $value) {
            $data['test'][] = $value;
        }
        echo json_encode($data['test']);
    }

    public function villagedetails()
    {
        $data = [];
        $dis = $this->input->post('dis');
        $this->session->set_userdata('dcode', $dis);
        $subdiv = $this->input->post('subdiv');
        $cir = $this->input->post('cir');
        $mza = $this->input->post('mza');
        $lot = $this->input->post('lot');
        $this->session->set_userdata('lot_no', $lot);
        $this->dataswitch();

        $formdata = $this->Chithamodel->villagedetails($dis, $subdiv, $cir, $mza, $lot);

        foreach ($formdata as $value) {
            $govt_dags = $this->DagReportModel->getGovtDags($value['dist_code'], $value['subdiv_code'], $value['cir_code'], $value['mouza_pargona_code'], $value['lot_no'], $value['vill_townprt_code']);
            if (count($govt_dags) > 0) {
                $data[] = $value;
            }
        }
        echo json_encode($data);
    }

    public function villagesAll()
    {
        $data = [];
        $dis = $this->input->post('dis');
        $this->session->set_userdata('dcode', $dis);
        $subdiv = $this->input->post('subdiv');
        $cir = $this->input->post('cir');
        $mza = $this->input->post('mza');
        $lot = $this->input->post('lot');
        $this->session->set_userdata('lot_no', $lot);
        $this->dataswitch();

        $formdata = $this->Chithamodel->villagedetails($dis, $subdiv, $cir, $mza, $lot);

        foreach ($formdata as $value) {
            $data[] = $value;
        }
        echo json_encode($data);
    }
    public function villagesSvamitva()
    {
        $data = [];
        $dis = $this->input->post('dis');
        $this->session->set_userdata('dcode', $dis);
        $subdiv = $this->input->post('subdiv');
        $cir = $this->input->post('cir');
        $mza = $this->input->post('mza');
        $lot = $this->input->post('lot');
        $this->session->set_userdata('lot_no', $lot);
        $this->dataswitch();

        $formdata = $this->Chithamodel->svamitvaVillages($dis, $subdiv, $cir, $mza, $lot);

        foreach ($formdata as $value) {
            $data[] = $value;
        }
        echo json_encode($data);
    }
    public function dagReport()
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
            $dist_code = $this->input->post('dist_code');
            $subdiv_code = $this->input->post('subdiv_code');
            $cir_code = $this->input->post('cir_code');
            $mouza_pargona_code = $this->input->post('mouza_pargona_code');
            $lot_no = $this->input->post('lot_no');
            $vill_townprt_code = $this->input->post('vill_townprt_code');
            $dags = $this->DagReportModel->getGovtDags($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code);
            foreach ($dags as $dag) {
                $encroachers = $this->DagReportModel->occupiers($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag->dag_no);
                foreach ($encroachers as $encroacher) {
                    $encroacher->families = $this->DagReportModel->families($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag->dag_no, $encroacher->encro_id);
                }
                $dag->encroachers = $encroachers;
            }

            $vill = $this->input->post('vill_townprt_code');

            $this->session->set_userdata('vill_townprt_code', $vill);
            $this->load->library('user_agent');
            $this->session->set_userdata('current_url', $this->agent->referrer());
            $this->session->set_userdata('sdcode', $this->session->userdata('dcode'));
            $this->session->set_userdata('ssubdiv_code', $this->session->userdata('subdiv_code'));
            $this->session->set_userdata('scir_code', $this->session->userdata('cir_code'));
            $this->session->set_userdata('smouza_pargona_code', $this->session->userdata('mouza_pargona_code'));
            $this->session->set_userdata('slot_no', $this->session->userdata('lot_no'));
            $this->session->set_userdata('svill_townprt_code', $this->session->userdata('vill_townprt_code'));

            $data['dags'] = $dags;
            $data['_view'] = 'reports/dag/dag_report';
            $data['locationname'] = $this->Chithamodel->getlocationnames($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code);
            $data['location'] = array(
                'dist_code' => $dist_code,
                'subdiv_code' => $subdiv_code,
                'cir_code' => $cir_code,
                'mouza_pargona_code' => $mouza_pargona_code,
                'lot_no' => $lot_no,
                'vill_townprt_code' => $vill_townprt_code,
            );
            $this->load->view('layout/layout', $data);
        } else {
            $this->index();
        }
    }
    public function dagReportNew()
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
            $dist_code = $this->input->post('dist_code');
            $subdiv_code = $this->input->post('subdiv_code');
            $cir_code = $this->input->post('cir_code');
            $mouza_pargona_code = $this->input->post('mouza_pargona_code');
            $lot_no = $this->input->post('lot_no');
            $vill_townprt_code = $this->input->post('vill_townprt_code');
            $dags = $this->DagReportModel->getGovtDags($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code);
            foreach ($dags as $dag) {
                $encroachers = $this->DagReportModel->occupiers($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag->dag_no);
                $dag->encroachers = $encroachers;
            }

            $vill = $this->input->post('vill_townprt_code');

            $this->session->set_userdata('vill_townprt_code', $vill);
            $this->load->library('user_agent');
            $this->session->set_userdata('current_url', $this->agent->referrer());
            $this->session->set_userdata('sdcode', $this->session->userdata('dcode'));
            $this->session->set_userdata('ssubdiv_code', $this->session->userdata('subdiv_code'));
            $this->session->set_userdata('scir_code', $this->session->userdata('cir_code'));
            $this->session->set_userdata('smouza_pargona_code', $this->session->userdata('mouza_pargona_code'));
            $this->session->set_userdata('slot_no', $this->session->userdata('lot_no'));
            $this->session->set_userdata('svill_townprt_code', $this->session->userdata('vill_townprt_code'));

            $data['dags'] = $dags;
            $data['_view'] = 'reports/dag/dag_report_new';
            $data['locationname'] = $this->Chithamodel->getlocationnames($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code);
            $data['location'] = array(
                'dist_code' => $dist_code,
                'subdiv_code' => $subdiv_code,
                'cir_code' => $cir_code,
                'mouza_pargona_code' => $mouza_pargona_code,
                'lot_no' => $lot_no,
                'vill_townprt_code' => $vill_townprt_code,
            );
            $this->load->view('layout/layout', $data);
        } else {
            $this->index();
        }
    }

    /**** Download Excel Dag details ****/
    public function downloadExcelDagDetails()
    {
        $dist_code = $_GET['d'];
        $subdiv_code = $_GET['s'];
        $cir_code = $_GET['c'];
        $mouza_pargona_code = $_GET['m'];
        $lot_no = $_GET['l'];
        $vill_townprt_code = $_GET['v'];

        /***** Fetch records from database and store in an array *****/
        $dag_details = $this->dagReportForDownloadExcel($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code);

        /***** Excel file name for download  *****/
        $fileName = "Dag_details_" . date('Y-m-d') . ".xlsx";

        /***** Sheet name  *****/
        $sheet_head = array('Location : Dist - ' . $dag_details['locationname']['dist_name']['loc_name'] . ', Sub-div - ' . $dag_details['locationname']['subdiv_name']['loc_name'] . ', Circle - ' . $dag_details['locationname']['cir_name']['loc_name'] . ', Mouza - ' . $dag_details['locationname']['mouza_name']['loc_name'] . ', Lot - ' . $dag_details['locationname']['lot']['loc_name'] . ', Village - ' . $dag_details['locationname']['village']['loc_name']);

        /***** Define column names *****/
        $table_header = array('Dag No', 'Land Class', 'Area (B-K-L)', 'Occupiers', 'Families');

        /** Get Dag Details & Encroachers Name & Family */
        $excelData = array();
        foreach ($dag_details['dags'] as $key => $dag) {
            $dag_no = $dag->dag_no;
            $dag_area_b = 'B-' . $dag->dag_area_b;
            $dag_area_k = ', K-' . $dag->dag_area_k;
            $dag_area_lc = ', L-' . $dag->dag_area_lc;
            $dag_area = $dag_area_b . $dag_area_k . $dag_area_lc;

            /** Get Encroachers Name & Family */
            if (sizeof($dag->encroachers) != 0) {
                foreach ($dag->encroachers as $key2 => $oc) {
                    $oc_dag_area_b = 'B-' . $oc->encro_land_b;
                    $oc_dag_area_k = ', K-' . $oc->encro_land_k;
                    $oc_dag_area_lc = ', L-' . $oc->encro_land_lc;
                    $oc_dag_area = ' (' . $oc_dag_area_b . $oc_dag_area_k . $oc_dag_area_lc . ')';

                    $occupiers = $oc->encro_name . $oc_dag_area . ' ,Guardian : ' . $oc->encro_guardian . '(' . $this->getRelationName($oc->encro_guar_relation) . ')' . ' ,Mobile : ' . $oc->mobile;

                    /** Get Encroachers  Family */
                    if (sizeof($oc->families) != 0) {
                        $relation = null;
                        foreach ($oc->families as $key3 => $ocf) {
                            if (trim($ocf->occupier_fmember_relation) == 'f') {
                                $relation = ' ( পিতৃ )';
                            } elseif (trim($ocf->occupier_fmember_relation) == 'm') {
                                $relation = ' ( মাতৃ )';
                            } elseif (trim($ocf->occupier_fmember_relation) == 'h') {
                                $relation = ' ( পতি )';
                            } elseif (trim($ocf->occupier_fmember_relation) == 'w') {
                                $relation = ' ( পত্নী )';
                            } elseif (trim($ocf->occupier_fmember_relation) == 'a') {
                                $relation = ' ( অধ্যক্ষ মাতা )';
                            } elseif (trim($ocf->occupier_fmember_relation) == '') {
                                $relation = ' ( অভিভাৱক )';
                            } elseif (trim($ocf->occupier_fmember_relation) == 'নাই') {
                                $relation = ' ( নাই )';
                            }

                            $family = $ocf->occupier_fmember_name . $relation;
                            if ($key3 == 0 && $key2 == 0) {
                                $lineData = array($dag_no, $dag->full_land_type_name, $dag_area, ++$key2 . '. ' . $occupiers, ++$key3 . '. ' . $family);
                                $excelData[] = $lineData;
                            } elseif ($key3 == 0 && $key2 != 0) {
                                $lineData = array('', '', '', ++$key2 . '. ' . $occupiers, ++$key3 . '. ' . $family);
                                $excelData[] = $lineData;
                            } else {
                                $lineData = array('', '', '', '', ++$key3 . '. ' . $family);
                                $excelData[] = $lineData;
                            }
                        }
                    } else {
                        if ($key2 != 0) {
                            $lineData = array('', '', '', ++$key2 . '. ' . $occupiers, 'No Family Details');
                            $excelData[] = $lineData;
                        } else {
                            $lineData = array($dag_no, $dag->full_land_type_name, $dag_area, ++$key2 . '. ' . $occupiers, 'No Family Details');
                            $excelData[] = $lineData;
                        }
                    }
                }
            } else {
                $lineData = array($dag_no, $dag->full_land_type_name, $dag_area, 'No Occupiers');
                $excelData[] = $lineData;
            }
        }

        /**** Generate Excel***/
        $this->generateExcel($sheet_head, $table_header, $fileName, $excelData);
    }

    /**** Get Dag Report For Download Excel ****/
    public function dagReportForDownloadExcel($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code)
    {
        $this->dataswitch();
        $data = array();
        $dags = $this->DagReportModel->getGovtDags($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code);
        foreach ($dags as $dag) {
            $encroachers = $this->DagReportModel->occupiers($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag->dag_no);
            foreach ($encroachers as $encroacher) {
                $encroacher->families = $this->DagReportModel->families($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag->dag_no, $encroacher->encro_id);
            }
            $dag->encroachers = $encroachers;
        }
        $data['dags'] = $dags;
        $data['locationname'] = $this->Chithamodel->getlocationnames($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code);

        return $data;
    }

    /***** Generate Excel *****/
    public function generateExcel($sheet_head, $table_header, $filename, $result_array)
    {
        /***** Include XLSX generator library *****/
        include APPPATH . '/libraries/Xlsxwriter.class.php';

        ini_set('display_errors', 1);
        ini_set('log_errors', 1);

        $styles1 = array(
            'font' => 'Arial', 'font-size' => 12, 'font-style' => 'bold', 'fill' => '#FFFF00',
            'halign' => 'center', 'border' => 'left,right,top,bottom',
        );
        $styles7 = array('font' => 'Arial', 'font-size' => 12, 'border' => 'left,right,top,bottom');

        header('Content-disposition: attachment; filename="' . XLSXWriter::sanitize_filename($filename) . '"');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        ob_clean();

        $writer = new XLSXWriter();
        $writer->setAuthor('Chitha_Entry');
        $sheet_name = 'Sheet1';
        $writer->writeSheetRow($sheet_name, $sheet_head, $styles7);
        $writer->writeSheetRow($sheet_name, $table_header, $styles1);

        /***** Generate rows *****/
        foreach ($result_array as $row) {
            $writer->writeSheetRow($sheet_name, $row, $styles7);
        }

        $writer->markMergedCell($sheet_name, $start_row = 0, $start_col = 0, $end_row = 0, $end_col = 4);
        $writer->writeToStdOut();
        exit(0);
    }
    public function getRelationName($code)
    {
        switch ($code) {
            case 'f':
                $relation = 'পিতৃ';
                break;
            case 'm':
                $relation = 'মাতৃ';
                break;
            case 'h':
                $relation = 'পতি';
                break;
            case 'w':
                $relation = 'পত্নী';
                break;
            case 'a':
                $relation = 'অধ্যক্ষ মাতা';
                break;
            case '':
                $relation = 'অভিভাৱক';
                break;
            case 'n':
                $relation = 'নাই';
                break;
            default:
                $relation = '';
                break;
        }
        return $relation;
    }
}
