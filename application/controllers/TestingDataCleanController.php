<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class TestingDataCleanController extends CI_Controller
{
    use CommonTrait;
    // for Jamabandi By Masud Reza 23/05/2022

    public function __construct() {
        parent::__construct();
        $this->load->library('UtilityClass');
        $this->load->model('JamaRemarkModel');

    }



    // set dag location details
    public function setDatabaseDetails()
    {
        $this->dataswitch();
        $data['base']= $this->config->item('base_url');
        $data['distcode'] = $this->session->userdata('dcode');

        $data['_view'] = 'jamabandi/remove_test_data';

        $this->load->view('layout/layout', $data);
    }


    public function getDatabaseDetails()
    {
        $data['base']=$this->config->item('base_url');

        $this->dataswitch();
        $this->form_validation->set_rules('dbSwitchCode', 'Database Switch Code', 'trim|required');
        $this->form_validation->set_rules('dbCode', 'Database Code', 'trim|required');

        if ($this->form_validation->run() == false) {
            $text = str_ireplace('<\/p>', '', validation_errors());
            $text = str_ireplace('<p>', '', $text);
            $text = str_ireplace('</p>', '', $text);
            echo json_encode(array('msg' => $text, 'st' => 0));
            return;
        }
        else
        {

            $deletedTable = array(
                1 => 'chitha_basic',
                2 => 'chitha_col8_inplace',
                3 => 'chitha_col8_occup',
                4 => 'chitha_col8_order',
                5 => 'chitha_dag_pattadar',
                6 => 'chitha_fruit',
                7 => 'chitha_mcrop',
                8 => 'chitha_noncrop',
                9 => 'chitha_pattadar',
                10 => 'chitha_rmk_allottee',
                11 => 'chitha_rmk_alongwith',
                12 => 'chitha_rmk_convorder',
                13 => 'chitha_rmk_encro',
                14 => 'chitha_rmk_gen',
                15 => 'chitha_rmk_infavor_of',
                16 => 'chitha_rmk_inplace_of',
                17 => 'chitha_rmk_lmnote',
                18 => 'chitha_rmk_onbehalf',
                19 => 'chitha_rmk_ordbasic',
                20 => 'chitha_rmk_sknote',
                21 => 'chitha_subtenant',
                22 => 'chitha_tenant',
                23 => 'jama_dag',
                24 => 'jama_patta',
                25 => 'jama_pattadar',
                26 => 'jama_remark',

            );

            $data['dbCode']   = $this->input->post('dbCode');
            $data['dbSCode']  = $this->input->post('dbSwitchCode');
            $data['deleteDB'] = $deletedTable;
        }

        $data['base']= $this->config->item('base_url');

        $data['_view'] = 'jamabandi/remove_test_data_details';

        $this->load->view('layout/layout', $data);
    }



    // delete test data final
    public function deleteTestingDataByDistrictDetailsFinal()
    {
        echo '<h1>'.' Test Needed '.'</h1>';
    }





}