<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class PattaController extends CI_Controller
{

    // for Patta By Udipta Gogoi 04/08/2022
    use CommonTrait;
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('UtilityClass');
        $this->load->helper('security');
        $this->load->model('PattaModel');
    }



    // set dag location details
    public function selectLocation()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouzas = $this->Chithamodel->mouzadetails($dist_code, $subdiv_code, $cir_code);
        $data['mouzas'] = $mouzas;

        $data['_view'] = 'patta/select_location';

        $this->load->view('layout/layout', $data);
    }

    public function storePattaLocation()
    {
        $this->dataswitch();

        $this->form_validation->set_rules('vill_code', 'Village', 'required|trim|strip_tags|xss_clean|integer|callback_villageCodeCheck|exact_length[5]');
        $this->form_validation->set_rules('application_type', 'Application Type', 'required|trim|integer');
        $this->form_validation->set_rules('patta_type', 'Patta Type', 'required|trim|strip_tags|xss_clean|integer');
        $this->form_validation->set_rules('patta_no', 'Patta No', 'required|trim|integer|callback_pattaNoCheck');
        $this->form_validation->set_rules('mouza_code', 'Mouza', 'required|trim|integer');
        $this->form_validation->set_rules('lot_no', 'Lot', 'required|trim|integer');
        
        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_code');
        $lot_no = $this->input->post('lot_no');
        $this->session->set_userdata('mouza_pargona_code', $mouza_pargona_code);
        $this->session->set_userdata('lot_no', $lot_no);

        if ($this->form_validation->run() == FALSE) {
            $this->selectLocation();
        } else {
            $data['base'] = $this->config->item('base_url');
            $vill_code = $this->input->post('vill_code');
            $patta_type_code = $this->input->post('patta_type');
            $patta_no = $this->input->post('patta_no');
            $application_type = $this->input->post('application_type');
            $patta_application_type = null;
            $patta_application_type = json_decode(PATTA_APPLICATION_TYPE);
            $patta_type = null;
            if ($application_type == $patta_application_type[0]->CODE) {
                $patta_type = 'PP';
            } else {
                $patta_type = 'AP';
            }

            $case_name = $this->genearteCaseName();
            $petition_no = $this->geneartePetitionNo();

            $case_no = $case_name . $petition_no . '/' . $patta_type;

            $sql = $this->db->query(
                "select dag_no from chitha_basic where 
                dist_code =?  and subdiv_code=? and 
                cir_code=? and mouza_pargona_code=? and  lot_no=? 
                and vill_townprt_code=? and patta_type_code=? and patta_no=?",
                array(
                    $dist_code, $subdiv_code, $cir_code,
                    $mouza_pargona_code, $lot_no, $vill_code, $patta_type_code, $patta_no
                )
            );
            $dag_no = null;
            if ($sql->num_rows() > 0) {
                $dag_no = $sql->result();
            }

            $sql4 = "select pdar_id,pdar_name from chitha_pattadar where 
                dist_code =?  and subdiv_code=? and 
                cir_code=? and mouza_pargona_code=? and  lot_no=? 
                and vill_townprt_code=? and patta_type_code=? and patta_no=?";
            $res4 = $this->db->query($sql4, array(
                $dist_code, $subdiv_code, $cir_code,
                $mouza_pargona_code, $lot_no, $vill_code, $patta_type_code, $patta_no
            ));
            $pattadar = null;
            if ($res4->num_rows() > 0) {
                $pattadar = $res4->result();
            }

            $data['relation'] = $this->db->query("select guard_rel, guard_rel_desc_as from 
                        master_guard_rel where id!='07'")->result();

            $data['dag_no'] = $dag_no;
            $data['pattadar'] = $pattadar;

            $data['case_no'] = $case_no;
            $data['petition_no'] = $petition_no;
            $data['dist_code'] = $this->session->userdata('dcode');
            $data['subdiv_code'] = $this->session->userdata('subdiv_code');
            $data['cir_code'] = $this->session->userdata('cir_code');
            $data['mouza_pargona_code'] = $this->session->userdata('mouza_pargona_code');
            $data['lot_no'] = $this->session->userdata('lot_no');
            $data['vill_code'] = $vill_code;
            $data['patta_type'] = $patta_type_code;
            $data['patta_no'] = $patta_no;
            $data['application_type'] = $application_type;
            $sql_for_exisiting_patta = "select * from patta_basic where dist_code=? and subdiv_code=? and cir_code=? and mouza_pargona_code=? and lot_no=? and vill_townprt_code=? and patta_type=? and patta_type_code=? and patta_no=?";

            $patta_basic_existing = $this->db->query(
                $sql_for_exisiting_patta,
                array($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code, $application_type, $patta_type_code, $patta_no)
            );
            if ($patta_basic_existing->num_rows() > 0) {
                $data['patta_basic_existing'] = $patta_basic_existing->row();
                $sql2 = "select * from patta_basic_dag where case_no =? and dist_code=? and subdiv_code=? and cir_code=?";
                $patta_basic_dag = $this->db->query(
                    $sql2,
                    array($data['patta_basic_existing']->case_no, $dist_code, $subdiv_code, $cir_code)
                );
                if ($patta_basic_dag->num_rows() > 0) {
                    $data['existing_dags'] = $patta_basic_dag->result();
                }
            }

            $data['_view'] = 'patta/main_patta_form';

            $this->load->view('layout/layout', $data);
        }
    }

    public function getPattaTypes()
    {
        $this->dataswitch();
        $data = null;
        $application_type = $this->input->post('application_type');
        $patta_application_type = json_decode(PATTA_APPLICATION_TYPE);
        if ($application_type == $patta_application_type[0]->CODE) {
            $sql = "select type_code,patta_type from patta_code where
            type_code=?";
            $data = $this->db->query(
                $sql,
                array("0201")
            )->result();
        } else {
            $sql = "select type_code,patta_type from patta_code where
            type_code=?";
            $data = $this->db->query(
                $sql,
                array("0202")
            )->result();
        }
        echo json_encode($data);
    }
    public function getPattaNo()
    {
        $this->dataswitch();
        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $vill_code = $this->input->post('vill_code');
        $patta_type = $this->input->post('patta_type');


        $sql = "select distinct(patta_no) from patta_basic where 
            dist_code=? and subdiv_code=? and cir_code=? and 
            mouza_pargona_code=? and lot_no=? and vill_townprt_code=? 
            and patta_type_code=?";
        $res = $this->db->query(
            $sql,
            array(
                $dist_code, $subdiv_code, $cir_code,
                $mouza_pargona_code, $lot_no, $vill_code, $patta_type
            )
        );
        $patta_no = null;
        if ($res->num_rows() > 0) {
            $patta_no = $res->result();
        }
        echo json_encode($patta_no);
    }
    public function getPattaNoChitha()
    {
        $this->dataswitch();
        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->input->post('mouza_pargona_code');
        $lot_no = $this->input->post('lot_no');
        $vill_code = $this->input->post('vill_code');
        $patta_type = $this->input->post('patta_type');


        $sql = "select distinct(patta_no) from chitha_basic where 
            dist_code=? and subdiv_code=? and cir_code=? and 
            mouza_pargona_code=? and lot_no=? and vill_townprt_code=? 
            and patta_type_code=?";
        $res = $this->db->query(
            $sql,
            array(
                $dist_code, $subdiv_code, $cir_code,
                $mouza_pargona_code, $lot_no, $vill_code, $patta_type
            )
        );
        $patta_no = null;
        if ($res->num_rows() > 0) {
            $patta_no = $res->result();
        }
        echo json_encode($patta_no);
    }
    /** get Dags  **/
    public function getDagNo()
    {
        $this->dataswitch();
        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no = $this->session->userdata('lot_no');
        $vill_code = $this->input->post('vill_code');

        $patta_type_code = $this->input->post('patta_type');
        $patta_no = $this->input->post('patta_no');

        $sql = $this->db->query(
            "select dag_no from chitha_basic where 
            dist_code =?  and subdiv_code=? and 
            cir_code=? and mouza_pargona_code=? and  lot_no=? 
            and vill_townprt_code=? and patta_type_code=? and patta_no=?",
            array(
                $dist_code, $subdiv_code, $cir_code,
                $mouza_pargona_code, $lot_no, $vill_code, $patta_type_code, $patta_no
            )
        );

        $data['dag'] = false;
        if ($sql->num_rows() > 0) {
            $data['dag'] = true;
            $data['dag_no'] = $sql->result();
        }
        echo json_encode($data);
    }
    /**** get pattadar Details *****/
    public function getPattadarDetails()
    {
        $this->dataswitch();
        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no = $this->session->userdata('lot_no');
        $vill_code = $this->input->post('vill_code');
        $patta_type_code = $this->input->post('patta_type');
        $patta_no = $this->input->post('patta_no');
        $pattadar_id = $this->input->post('pattadar_id');

        $sql4 = "select pdar_father from chitha_pattadar where 
             dist_code =?  and subdiv_code=? and 
             cir_code=? and mouza_pargona_code=? and  lot_no=? 
             and vill_townprt_code=? and patta_type_code=? and patta_no=? and pdar_id=?";
        $res4 = $this->db->query($sql4, array(
            $dist_code, $subdiv_code, $cir_code,
            $mouza_pargona_code, $lot_no, $vill_code, $patta_type_code, $patta_no, $pattadar_id
        ));
        $pattadar_father = null;
        if ($res4->num_rows() > 0) {
            $pattadar_father = $res4->row()->pdar_father;
        }

        echo json_encode($pattadar_father);
    }
    /*** get dag Area *******/
    public function getDagArea()
    {
        $this->dataswitch();
        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no = $this->session->userdata('lot_no');
        $vill_code = $this->input->post('vill_code');
        $dag_no = $this->input->post('dag_no');

        $sql = $this->db->query(
            "select * from chitha_basic where 
            dist_code =?  and subdiv_code=? and 
            cir_code=? and mouza_pargona_code=? and  lot_no=? 
            and vill_townprt_code=? and dag_no=?",
            array(
                $dist_code, $subdiv_code, $cir_code,
                $mouza_pargona_code, $lot_no, $vill_code, $dag_no
            )
        );
        $dag_no = null;
        $data['row'] = false;
        if ($sql->num_rows() > 0) {
            $dag_no = $sql->row();
            $data['row'] = true;
        }
        $data['dag_no'] = $dag_no;
        echo json_encode($data);
        return;
    }
    public function storeMainPattaForm()
    {
        $this->dataswitch();
        $patta_application_type = json_decode(PATTA_APPLICATION_TYPE);

        $dist_code = $this->session->userdata('dcode');
        $this->form_validation->set_rules('case_no', 'Case No', 'required|trim|xss_clean');
        $this->form_validation->set_rules('pattadar_name', 'Application Name / Pattadar Name', 'required|trim|xss_clean|max_length[100]|callback_pattadarCheck');
        $this->form_validation->set_rules('guardian_name', 'Guardian Name', 'required|trim|xss_clean|max_length[100]');
        $this->form_validation->set_rules('relation', 'Relation with Guardian', 'required|trim|xss_clean|exact_length[1]');
        $this->form_validation->set_rules('mobile_no', 'Mobile No', 'trim|xss_clean|integer|exact_length[10]');
        $this->form_validation->set_rules('dag_no[]', 'Dag No', 'required|trim|xss_clean|callback_dagNoCheck');
        $this->form_validation->set_rules('vill_code', 'Village', 'required|trim|xss_clean|integer|callback_villageCodeCheck|exact_length[5]');
        $this->form_validation->set_rules('time_period', 'For How Many Years(s)', 'required|trim|xss_clean|integer');
        $this->form_validation->set_rules('upto_date', 'Upto which Date', 'required|trim|xss_clean');
        if ($this->input->post('application_type') == $patta_application_type[0]->CODE) {
            $this->form_validation->set_rules('installment1', 'First Installment', 'required|trim|xss_clean');
            $this->form_validation->set_rules('installment2', 'Second Installment', 'required|trim|xss_clean');
            $this->form_validation->set_rules('revenue_to_be_paid1', 'Revenue to be Paid', 'required|trim|xss_clean|numeric');
            $this->form_validation->set_rules('revenue_to_be_paid2', 'Second Revenue to be Paid', 'required|trim|xss_clean|numeric');
        } else {
            $this->form_validation->set_rules('revenue_to_be_paid1', 'Revenue to be Paid', 'required|trim|xss_clean|numeric');
        }
        if ($this->form_validation->run() == FALSE) {
            $errors = validation_errors();
            echo json_encode(['error' => $errors]);
            return;
        } else {
            $data['validation'] = true;
            $case_no = $this->input->post('case_no');
            $petition_no = $this->input->post('petition_no');
            $subdiv_code = $this->session->userdata('subdiv_code');
            $cir_code = $this->session->userdata('cir_code');
            $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
            $lot_no = $this->session->userdata('lot_no');
            $vill_townprt_code = $this->input->post('vill_code');

            // $uuid = $this->db->query("select uuid from location where 
            // dist_code =? and subdiv_code=? and 
            // cir_code=? and mouza_pargona_code=? and  lot_no=? 
            // and vill_townprt_code=?", array(
            //     $dist_code, $subdiv_code, $cir_code,
            //     $mouza_pargona_code, $lot_no, $vill_townprt_code
            // ))->row()->uuid;

            $application_type = $this->input->post('application_type');
            $patta_type_code = $this->input->post('patta_type');
            $patta_no = $this->input->post('patta_no');
            $time_period = $this->input->post('time_period');
            $upto_date = $this->input->post('upto_date');
            if ($this->input->post('application_type') == $patta_application_type[0]->CODE) {
                $installment1 = date('Y-m-d', strtotime($this->input->post('installment1')));
                $installment2 = date('Y-m-d', strtotime($this->input->post('installment2')));
                $revenue_to_be_paid1 = $this->input->post('revenue_to_be_paid1');
                $revenue_to_be_paid2 = $this->input->post('revenue_to_be_paid2');
            } else {
                $revenue_to_be_paid1 = $this->input->post('revenue_to_be_paid1');
            }

            $dag_no = $this->input->post('dag_no');

            $pattadar_id = $this->input->post('pattadar_name');

            $sql5 = "select pdar_name,pdar_father from chitha_pattadar where dist_code =? and subdiv_code=? and 
            cir_code=? and mouza_pargona_code=? and  lot_no=? 
            and vill_townprt_code=? and patta_type_code=? and patta_no=? and pdar_id=?";
            $pattadar = $this->db->query($sql5, array(
                $dist_code, $subdiv_code, $cir_code,
                $mouza_pargona_code, $lot_no, $vill_townprt_code, $patta_type_code, $patta_no, $pattadar_id
            ))->row();
            $pattadar_name = $pattadar->pdar_name;
            $guardian_name = $pattadar->pdar_father;
            $relation = $this->input->post('relation');
            $mobile_no = $this->input->post('mobile_no');
            if ($mobile_no == '' || $mobile_no == null) {
                $mobile_no = null;
            }
            $this->db->trans_begin();
            $data['error_save'] = false;
            $flag = false;

            $patta_basic['case_no'] = $case_no;
            $patta_basic['petition_no'] = $petition_no;
            $patta_basic['dist_code'] = $dist_code;
            $patta_basic['subdiv_code'] = $subdiv_code;
            $patta_basic['cir_code'] = $cir_code;
            $patta_basic['mouza_pargona_code'] = $mouza_pargona_code;
            $patta_basic['lot_no'] = $lot_no;
            $patta_basic['vill_townprt_code'] = $vill_townprt_code;
            // $patta_basic['uuid'] = $uuid;
            $patta_basic['time_period'] = $time_period;
            $patta_basic['upto_date'] = $upto_date;
            if ($this->input->post('application_type') == $patta_application_type[0]->CODE) {
                $patta_basic['installment1'] = $installment1;
                $patta_basic['installment2'] = $installment2;
                $patta_basic['revenue_to_be_paid1'] = $revenue_to_be_paid1;
                $patta_basic['revenue_to_be_paid2'] = $revenue_to_be_paid2;
            } else {
                $patta_basic['revenue_to_be_paid1'] = $revenue_to_be_paid1;
            }
            $patta_basic['patta_type'] = $application_type;
            $patta_basic['patta_type_code'] = $patta_type_code;
            $patta_basic['patta_no'] = $patta_no;
            $patta_basic['pattadar_name'] = $pattadar_name;
            $patta_basic['guardian_name'] = $guardian_name;
            $patta_basic['pattadar_id'] = $pattadar_id;
            $patta_basic['mobile_no'] = $mobile_no;
            $patta_basic['relation'] = $relation;
            $patta_basic['status'] = 'P';
            $patta_basic['lm_code'] = $this->session->userdata('user_code');
            $patta_basic['created_date'] = date('d-m-Y H:i:s');
            //CHECK IS EXISTS
            $sql_for_exisiting_patta = "select * from patta_basic where dist_code=? and subdiv_code=? and cir_code=? and mouza_pargona_code=? and lot_no=? and vill_townprt_code=? and patta_type=? and patta_type_code=? and patta_no=?";
            $patta_basic_existing = $this->db->query(
                $sql_for_exisiting_patta,
                array($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $application_type, $patta_type_code, $patta_no)
            );
            if ($patta_basic_existing->num_rows() > 0) {
                $patta_basic_save = $this->PattaModel->updatePattaDetails($patta_basic);
            } else {
                $patta_basic_save = $this->db->insert('patta_basic', $patta_basic);
            }
            if ($patta_basic_save != true) {
                $flag = true;
                $data['error_save'] = true;
                $data['error_msg'] = "#PATTA10001 Unable to insert data";
                log_message("error", "#PATTA10001 Unable to insert in patta_basic
                                for dist_code: " . $this->session->userdata('dcode'));
                $this->db->trans_rollback();
                echo json_encode($data);
                return;
            }

            if (count($dag_no) !== count(array_unique($dag_no))) {
                $data['error_save'] = true;
                $data['error_msg'] = "Dag no(s) are should be unique.";
                $this->db->trans_rollback();
                echo json_encode($data);
                return;
            }

            foreach ($dag_no as $dag) {
                $sql_dags_existing = "select * from patta_basic_dag where case_no =? and dist_code=? and subdiv_code=? and cir_code=? and dag_no=?";
                $dags_existing = $this->db->query($sql_dags_existing, array($case_no, $dist_code, $subdiv_code, $cir_code, $dag));
                if ($dags_existing->num_rows() == 0) {
                    $sql6 = "select dag_area_b,dag_area_k,dag_area_lc,dag_area_g,dag_area_kr,land_class_code,dag_revenue,dag_local_tax from 
                chitha_basic where dist_code =? and subdiv_code=? and 
                cir_code=? and mouza_pargona_code=? and  lot_no=? 
                and vill_townprt_code=? and patta_type_code=? and patta_no=? and dag_no=?";
                    $dag_row = $this->db->query($sql6, array(
                        $dist_code, $subdiv_code, $cir_code,
                        $mouza_pargona_code, $lot_no, $vill_townprt_code, $patta_type_code, $patta_no, $dag
                    ))->row();

                    $patta_basic_dag['case_no'] = $case_no;
                    $patta_basic_dag['petition_no'] = $petition_no;
                    $patta_basic_dag['dist_code'] = $dist_code;
                    $patta_basic_dag['subdiv_code'] = $subdiv_code;
                    $patta_basic_dag['cir_code'] = $cir_code;
                    $patta_basic_dag['mouza_pargona_code'] = $mouza_pargona_code;
                    $patta_basic_dag['lot_no'] = $lot_no;
                    $patta_basic_dag['vill_townprt_code'] = $vill_townprt_code;
                    // $patta_basic_dag['uuid'] = $uuid;
                    $patta_basic_dag['patta_type'] = $application_type;
                    $patta_basic_dag['patta_type_code'] = $patta_type_code;
                    $patta_basic_dag['patta_no'] = $patta_no;
                    $patta_basic_dag['dag_no'] = $dag;
                    $patta_basic_dag['dag_area_b'] = $dag_row->dag_area_b;
                    $patta_basic_dag['dag_area_k'] = $dag_row->dag_area_k;
                    $patta_basic_dag['dag_area_lc'] = $dag_row->dag_area_lc;
                    $patta_basic_dag['dag_area_g'] = $dag_row->dag_area_g;
                    $patta_basic_dag['dag_area_kr'] = $dag_row->dag_area_kr;
                    $patta_basic_dag['land_class_code'] = $dag_row->land_class_code;
                    $patta_basic_dag['dag_revenue'] = $dag_row->dag_revenue;
                    $patta_basic_dag['dag_local_tax'] = $dag_row->dag_local_tax;
                    $patta_basic_dag_save = $this->db->insert('patta_basic_dag', $patta_basic_dag);
                    if ($patta_basic_dag_save != true) {
                        $flag = true;
                        $data['error_save'] = true;
                        $data['error_msg'] = "#PATTA10002 Unable to insert data";
                        log_message("error", "#PATTA10002 Unable to insert in patta_basic_dag
                                for dist_code: " . $this->session->userdata('dcode'));
                        $this->db->trans_rollback();
                        echo json_encode($data);
                        return;
                    }
                }
            }

            if ($this->db->trans_status() === FALSE || $flag == true) {
                $this->db->trans_rollback();
                $data['error_save'] = true;
                $data['error_msg'] = "#PATTA10003 Unable to save data";
                log_message("error", "#PATTA10003 Unable to save data
                                for dist_code: " . $this->session->userdata('dcode'));
                echo json_encode($data);
                return;
            } else {
                $data['save_data'] = true;
                $this->db->trans_commit();
                $this->session->set_flashdata('message', "Patta Generated Successfully !!");
                $data['error_save'] = false;
                echo json_encode($data);
                return;
            }
        }
    }
    // delete dag
    public function deleteDag()
    {
        $dag_id = $this->input->post('dag_id');
        try {
            if ($dag_id) {
                $this->dataswitch();
                $this->db->delete('patta_basic_dag', ['id' => $dag_id]);
                echo json_encode(['msg' => 'deleted successfully.']);
            }
        } catch (Exception $e) {
            header('Content-type: application/json');
            http_response_code(500);
            echo json_encode([
                'message' => 'Something went wrong'
            ]);
        }
    }
    /*** generate case name ******/
    function genearteCaseName()
    {
        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $financialyeardate = (date('m') < '07') ? date('Y', strtotime('-1 year')) . "-" . date('y') : date('Y') . "-" . date('y', strtotime('+1 year'));
        $q = "Select dist_abbr,cir_abbr from location where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code!='00' ";
        $abbrname = $this->db->query($q)->row();
        if ($abbrname) {
            $cir_dist_name = $abbrname->dist_abbr . "/" . $abbrname->cir_abbr;
            $case_no = $cir_dist_name . "/" . $financialyeardate . "/";
            return $case_no;
        }
        return false;
    }

    /*** generate Petition No ******/
    function geneartePetitionNo()
    {
        $petition_no = $this->db->query("select nextval('seq_max_patta') as count ")->row()->count;
        return $petition_no;
    }
    /***** view patta ******/
    public function viewPatta()
    {

        $this->dataswitch();
        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        if ($this->input->get('case_no')) {
            $mouza_code = $this->input->get('mouza_code');
            $lot_no = $this->input->get('lot_no');
            $vill_code = $this->input->get('vill_code');
            $patta_type = $this->input->get('patta_type');
            $patta_type_code = $this->input->get('patta_type_code');
            $patta_no = $this->input->get('patta_no');
        } else {
            $this->form_validation->set_rules('application_type', 'Application Type', 'required|trim|integer');
            $this->form_validation->set_rules('patta_type_code', 'Patta Type', 'required|trim|strip_tags|xss_clean|integer');
            $this->form_validation->set_rules('patta_no', 'Patta No', 'required|trim|integer');
            $this->form_validation->set_rules('mouza_code', 'Mouza Code', 'required|trim|integer');
            $this->form_validation->set_rules('lot_no', 'Lot No', 'required|trim|integer');
            $this->form_validation->set_rules('vill_code', 'Village Code', 'required|trim|integer');

            $mouza_code = $this->input->post('mouza_code');
            $lot_no = $this->input->post('lot_no');
            $vill_code = $this->input->post('vill_code');
            $patta_type = $this->input->post('application_type');
            $patta_type_code = $this->input->post('patta_type_code');
            $patta_no = $this->input->post('patta_no');
            if ($this->form_validation->run() == FALSE) {
                redirect(base_url() . "index.php/PattaController/selectPattaView");
            }
        }
        $sql = "select * from patta_basic where dist_code=? and subdiv_code=? and cir_code=? and mouza_pargona_code=? and lot_no=? and vill_townprt_code=? and patta_type=? and patta_type_code=? and patta_no=?";
        $patta_basic = $this->db->query(
            $sql,
            array($dist_code, $subdiv_code, $cir_code,$mouza_code, $lot_no,$vill_code, $patta_type, $patta_type_code, $patta_no)
        );
        if ($patta_basic->num_rows() > 0) {
            $data['patta_basic'] = $patta_basic->row();
            $case_no = $data['patta_basic']->case_no;
            $sql2 = "select * from patta_basic_dag where case_no =? and 
            dist_code=? and subdiv_code=? and cir_code=?";
            $patta_basic_dag = $this->db->query(
                $sql2,
                array($case_no, $dist_code, $subdiv_code, $cir_code)
            );
            if ($patta_basic_dag->num_rows() > 0) {
                $data['patta_basic_dag'] = $patta_basic_dag->result();
                $patta_application_type = json_decode(PATTA_APPLICATION_TYPE);

                /**** if periodic patta *****/
                if ($data['patta_basic']->patta_type == $patta_application_type[0]->CODE) {
                    $data['_view'] = 'patta/periodic_patta_view';
                    $this->load->view('layout/layout', $data);
                } else {
                    $data['_view'] = 'patta/annual_patta_view';
                    $this->load->view('layout/layout', $data);
                }
            } else {
                $this->session->set_flashdata('message', "Patta Not Found !!");
                redirect(base_url() . "index.php/PattaController/selectPattaView");
            }
        } else {
            $this->session->set_flashdata('message', "Patta Not Found !!");
            redirect(base_url() . "index.php/PattaController/selectPattaView");
        }
    }
    public function selectPattaView()
    {
        $this->dataswitch();
        $data['base'] = $this->config->item('base_url');
        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouzas = $this->Chithamodel->mouzadetails($dist_code, $subdiv_code, $cir_code);
        $data['mouzas'] = $mouzas;
        $data['_view'] = 'patta/select_patta_view';
        $this->load->view('layout/layout', $data);
    }
    /******** CHECK VILLAGE CODE CALL BACK FUNCTION *********/
    public function villageCodeCheck($vill_code)
    {
        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no = $this->session->userdata('lot_no');
        $vill_code = $vill_code;

        $villages_code_check = $this->db->query(
            "select vill_townprt_code from location where 
            dist_code =?  and subdiv_code=? and 
            cir_code=? and mouza_pargona_code=? and  lot_no=? 
            and vill_townprt_code=?",
            array(
                $dist_code, $subdiv_code, $cir_code,
                $mouza_pargona_code, $lot_no, $vill_code
            )
        );
        if ($villages_code_check->num_rows() == 1) {
            return TRUE;
        } else {
            $this->form_validation->set_message('villageCodeCheck', 'This %s is invalid');
            return FALSE;
        }
    }

    /******** CHECK Patta No CALL BACK FUNCTION *********/
    public function pattaNoCheck($patta_no)
    {
        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no = $this->session->userdata('lot_no');
        $vill_code = $this->input->post('vill_code');
        $patta_type_code = $this->input->post('patta_type');

        $count = $this->db->query(
            "select count(*) as c from chitha_basic where 
            dist_code =?  and subdiv_code=? and 
            cir_code=? and mouza_pargona_code=? and  lot_no=? 
            and vill_townprt_code=? and patta_type_code=? and patta_no=?",
            array(
                $dist_code, $subdiv_code, $cir_code,
                $mouza_pargona_code, $lot_no, $vill_code, $patta_type_code, $patta_no
            )
        )->row()->c;
        if ($count > 0) {
            return TRUE;
        } else {
            $this->form_validation->set_message('pattaNoCheck', 'This %s is invalid');
            return FALSE;
        }
    }

    /*** pattadar check *****/
    public function pattadarCheck($pattadar_id)
    {
        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no = $this->session->userdata('lot_no');
        $vill_townprt_code = $this->input->post('vill_code');
        $patta_type = $this->input->post('patta_type');
        $patta_no = $this->input->post('patta_no');

        $sql = "select count(*) as c from chitha_pattadar where dist_code =? and subdiv_code=? and 
        cir_code=? and mouza_pargona_code=? and  lot_no=? 
        and vill_townprt_code=? and patta_type_code=? and patta_no=? and pdar_id=?";
        $res = $this->db->query($sql, array(
            $dist_code, $subdiv_code, $cir_code,
            $mouza_pargona_code, $lot_no, $vill_townprt_code, $patta_type, $patta_no, $pattadar_id
        ))->row()->c;

        if ($res == 0) {
            $this->form_validation->set_message('pattadarCheck', 'This %s is invalid');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /** check Dag no *****/
    public function dagNoCheck($dag)
    {
        $dist_code = $this->session->userdata('dcode');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no = $this->session->userdata('lot_no');
        $vill_townprt_code = $this->input->post('vill_code');
        $patta_type = $this->input->post('patta_type');
        $patta_no = $this->input->post('patta_no');

        $sql = "select count(*) as c from chitha_basic where dist_code =?  and subdiv_code=? and 
        cir_code=? and mouza_pargona_code=? and  lot_no=? 
        and vill_townprt_code=? and patta_type_code=? and patta_no=? and dag_no=?";
        $res = $this->db->query($sql, array(
            $dist_code, $subdiv_code, $cir_code,
            $mouza_pargona_code, $lot_no, $vill_townprt_code, $patta_type, $patta_no, $dag
        ))->row()->c;
        if ($res == 0) {
            $this->form_validation->set_message('dagNoCheck', 'This %s is invalid');
            return FALSE;
        } else {
            return TRUE;
        }
    }
}
