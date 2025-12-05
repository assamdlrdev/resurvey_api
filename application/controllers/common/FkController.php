<?php
include APPPATH . '/libraries/CommonTrait.php';

class FkController extends CI_Controller
{
    use CommonTrait;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('CommonModel');
        $this->load->helper('url');
    }
    public function removeFk()
    {
        try {
            $this->dataswitch();
            $remove_fk_queries = $this->CommonModel::$REMOVE_QUERIES;
            $fks = $this->input->post('fks');
            ini_set('max_execution_time', '0');
            $this->db->trans_start();
            log_message('error', 'db remove fk - ' . $this->db->database);
            foreach ($fks as $fk) {
                foreach ($remove_fk_queries as $query) {
                    if ($query['name'] == $fk) {
                        log_message('error', 'remove fk - ' . $fk);
                        $this->db->query($query['query']);
                    }
                }
            }
            $this->db->trans_complete();
            $response = ['message' => 'Removed FK successfully', 'description' => '', 'status' => '1'];
            echo json_encode($response);
            return;
        } catch (Exception $e) {
            $response = ['message' => $e->getMessage(), 'status' => '0'];
            echo json_encode($response);
            return;
        }
    }
    public function addFk()
    {
        try {
            $this->dataswitch();
            $add_fk_queries = $this->CommonModel::$ADD_QUERIES;
            $fks = $this->input->post('fks');
            ini_set('max_execution_time', '0');
            $this->db->trans_start();
            log_message('error', 'db ad fk - ' . $this->db->database);
            foreach ($fks as $fk) {
                foreach ($add_fk_queries as $query) {
                    if ($query['name'] == $fk) {
                        log_message('error', 'ad fk - ' . $fk);
                        $this->db->query($query['query']);
                    }
                }
            }
            $this->db->trans_complete();
            $response = ['message' => 'Added FK successfully', 'description' => '', 'status' => '1'];
            echo json_encode($response);
            return;
        } catch (Exception $e) {
            $response = ['message' => $e->getMessage(), 'status' => '0'];
            echo json_encode($response);
            return;
        }
    }
}
