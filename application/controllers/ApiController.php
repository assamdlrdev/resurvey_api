<?php
defined('BASEPATH') or exit('No direct script access allowed');
include APPPATH . '/libraries/CommonTrait.php';

class ApiController extends CI_Controller
{
    use CommonTrait;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('api/Chithamodel');
        $this->load->model('UserModel');
    }
    public function addLoginLog()
    {
        if ($this->input->get('api_key') == "chitha_application" or $_POST['api_key'] == "chitha_application") {
            try {
                $form_data = [
                    'dist_code' => $this->input->get('dist_code') ? $this->input->get('dist_code') : $_POST['dist_code'],
                    'username' => $this->input->get('username') ? $this->input->get('username') : $_POST['username'],
                ];
                $form_data['id'] = time();
                $encrypt = openssl_encrypt($form_data['id'], "AES-128-CTR", "singleENCRYPT", 0, '1234567893032221');
                $form_data['expired'] = 0;
                $db = $this->UserModel->connectLocmaster();
                $db->trans_start();
                $db->insert('login_log', [
                    'dist_code' => $form_data['dist_code'],
                    'expired' => $form_data['expired'],
                    'id' => $form_data['id'],
                    'username' => $form_data['username'],
                ]);
                $db->trans_complete();
                Header('Access-Control-Allow-Origin: *');
                echo json_encode(['message' => 'Successfully Added', 'responseCode' => 1, 'id' => $encrypt]);
            } catch (Exception $e) {
                Header('Access-Control-Allow-Origin: *');
                echo json_encode(['message' => 'Error while inserting.', 'responseCode' => 0]);
            }
        }
    }
    public function addNewLocation()
    {
        if ($this->input->get('api_key') == "chitha_application" or $_POST['api_key'] == "chitha_application") {
            try {
                //code...

                $form_data = [
                    'dist_code' => $this->input->get('dist_code') ? $this->input->get('dist_code') : $_POST['dist_code'],
                    'subdiv_code' => $this->input->get('subdiv_code') ? $this->input->get('subdiv_code') : $_POST['subdiv_code'],
                    'cir_code' => $this->input->get('cir_code') ? $this->input->get('cir_code') : $_POST['cir_code'],
                    'mouza_pargona_code' => $this->input->get('mouza_pargona_code') ? $this->input->get('mouza_pargona_code') : $_POST['mouza_pargona_code'],
                    'lot_no' => $this->input->get('lot_no') ? $this->input->get('lot_no') : $_POST['lot_no'],
                    'vill_townprt_code' => $this->input->get('vill_townprt_code') ? $this->input->get('vill_townprt_code') : $_POST['vill_townprt_code'],
                    'loc_name' => $this->input->get('loc_name') ? $this->input->get('loc_name') : $_POST['loc_name'],
                    'unique_loc_code' => $this->input->get('unique_loc_code') ? $this->input->get('unique_loc_code') : $_POST['unique_loc_code'],
                ];
                if (isset($_POST['cir_abbr'])) {
                    $form_data['cir_abbr'] = $_POST['cir_abbr'];
                } else {
                    $form_data['cir_abbr'] = null;

                }
                if (isset($_POST['locname_eng'])) {
                    $form_data['locname_eng'] = $_POST['locname_eng'];
                } else {
                    $form_data['locname_eng'] = null;

                }
                if (isset($_POST['dist_abbr'])) {
                    $form_data['dist_abbr'] = $_POST['dist_abbr'];
                } else {
                    $form_data['dist_abbr'] = null;

                }
                if (isset($_POST['state_code_cen11'])) {
                    $form_data['state_code_cen11'] = $_POST['state_code_cen11'];
                } else {
                    $form_data['state_code_cen11'] = null;

                }
                if (isset($_POST['dist_code_cen11'])) {
                    $form_data['dist_code_cen11'] = $_POST['dist_code_cen11'];
                } else {
                    $form_data['dist_code_cen11'] = null;

                }
                if (isset($_POST['tehsil_code_cen11'])) {
                    $form_data['tehsil_code_cen11'] = $_POST['tehsil_code_cen11'];
                } else {
                    $form_data['tehsil_code_cen11'] = null;

                }
                if (isset($_POST['vill_code_cen_11'])) {
                    $form_data['vill_code_cen_11'] = $_POST['vill_code_cen_11'];
                } else {
                    $form_data['vill_code_cen_11'] = null;

                }
                if (isset($_POST['rural_urban'])) {
                    $form_data['rural_urban'] = $_POST['rural_urban'];
                } else {
                    $form_data['rural_urban'] = null;

                }
                if (isset($_POST['is_gmc'])) {
                    $form_data['is_gmc'] = $_POST['is_gmc'];
                } else {
                    $form_data['is_gmc'] = null;

                }
                if (isset($_POST['uuid'])) {
                    $form_data['uuid'] = $_POST['uuid'];
                } else {
                    $form_data['uuid'] = null;

                }
                if (isset($_POST['village_status'])) {
                    $form_data['village_status'] = $_POST['village_status'];
                } else {
                    $form_data['village_status'] = null;

                }
                if (isset($_POST['is_map'])) {
                    $form_data['is_map'] = $_POST['is_map'];
                } else {
                    $form_data['is_map'] = null;

                }
                if (isset($_POST['lgd_code'])) {
                    $form_data['lgd_code'] = $_POST['lgd_code'];
                } else {
                    $form_data['lgd_code'] = null;

                }
                if (isset($_POST['status'])) {
                    $form_data['status'] = $_POST['status'];
                } else {
                    $form_data['status'] = null;

                }
                if (isset($_POST['nc_btad'])) {
                    $form_data['nc_btad'] = $_POST['nc_btad'];
                } else {
                    $form_data['nc_btad'] = null;

                }
                if (isset($_POST['is_periphary'])) {
                    $form_data['is_periphary'] = $_POST['is_periphary'];
                } else {
                    $form_data['is_periphary'] = null;

                }
                if (isset($_POST['is_tribal'])) {
                    $form_data['is_tribal'] = $_POST['is_tribal'];
                } else {
                    $form_data['is_tribal'] = null;

                }
                if (isset($_POST['district_headquater'])) {
                    $form_data['district_headquater'] = $_POST['district_headquater'];
                } else {
                    $form_data['district_headquater'] = null;

                }

                $d = $form_data['dist_code'];
                $s = $form_data['subdiv_code'];
                $c = $form_data['cir_code'];
                $m = $form_data['mouza_pargona_code'];
                $l = $form_data['lot_no'];
                $v = $form_data['vill_townprt_code'];
                $this->session->set_userdata('dcode', $d);
                $this->dataswitch($d);
                $query = $this->db->get_where('location', array('dist_code' => $d, 'subdiv_code =' => $s, 'cir_code=' => $c, 'mouza_pargona_code=' => $m, 'lot_no=' => $l, 'vill_townprt_code' => $v))->row();
                if (!$query) {
                    $this->db->trans_start();
                    $this->db->insert('location', [
                        'dist_code' => $form_data['dist_code'],
                        'subdiv_code' => $form_data['subdiv_code'] ? $form_data['subdiv_code'] : '00',
                        'cir_code' => $form_data['cir_code'] ? $form_data['cir_code'] : '00',
                        'mouza_pargona_code' => $form_data['mouza_pargona_code'] ? $form_data['mouza_pargona_code'] : '00',
                        'lot_no' => $form_data['lot_no'] ? $form_data['lot_no'] : '00',
                        'vill_townprt_code' => $form_data['vill_townprt_code'],
                        'loc_name' => $form_data['loc_name'],
                        'unique_loc_code' => $form_data['unique_loc_code'],
                        'locname_eng' => $form_data['locname_eng'] ? $form_data['locname_eng'] : null,
                        'cir_abbr' => $form_data['cir_abbr'] ? $form_data['cir_abbr'] : null,
                        'state_code_cen11' => $form_data['state_code_cen11'] ? $form_data['state_code_cen11'] : null,
                        'dist_code_cen11' => $form_data['dist_code_cen11'] ? $form_data['dist_code_cen11'] : null,
                        'vill_code_cen_11' => $form_data['vill_code_cen_11'] ? $form_data['vill_code_cen_11'] : null,
                        'tehsil_code_cen11' => $form_data['tehsil_code_cen11'] ? $form_data['tehsil_code_cen11'] : null,
                        'rural_urban' => $form_data['rural_urban'] ? $form_data['rural_urban'] : null,
                        'is_gmc' => $form_data['is_gmc'] ? $form_data['is_gmc'] : null,
                        'uuid' => $form_data['uuid'] ? $form_data['uuid'] : null,
                        'village_status' => $form_data['village_status'] ? $form_data['village_status'] : null,
                        'lgd_code' => $form_data['lgd_code'] ? $form_data['lgd_code'] : null,
                        'is_map' => $form_data['is_map'] ? $form_data['is_map'] : null,
                        'status' => $form_data['status'] ? $form_data['status'] : null,
                        'nc_btad' => $form_data['nc_btad'] ? $form_data['nc_btad'] : null,
                        'is_periphary' => $form_data['is_periphary'] ? $form_data['is_periphary'] : null,
                        'is_tribal' => $form_data['is_tribal'] ? $form_data['is_tribal'] : null,
                        'district_headquater' => $form_data['district_headquater'] ? $form_data['district_headquater'] : null,
                    ]);
                    $this->db->trans_complete();
                    Header('Access-Control-Allow-Origin: *');
                    echo json_encode(['message' => 'Successfully Added', 'responseCode' => 2]);
                } else {
                    Header('Access-Control-Allow-Origin: *');
                    echo json_encode(['message' => 'Location Already Exist', 'responseCode' => 1]);
                }
            } catch (Exception $e) {
                Header('Access-Control-Allow-Origin: *');
                echo json_encode(['message' => 'Error while inserting.', 'responseCode' => 3]);
            }
        }
    }

    public function updateLocation()
    {
        if ($this->input->get('api_key') == "chitha_application" or $_POST['api_key'] == "chitha_application") {
            try {
                //code...

                $form_data = [
                    'dist_code' => $this->input->get('dist_code') ? $this->input->get('dist_code') : $_POST['dist_code'],
                    'subdiv_code' => $this->input->get('subdiv_code') ? $this->input->get('subdiv_code') : $_POST['subdiv_code'],
                    'cir_code' => $this->input->get('cir_code') ? $this->input->get('cir_code') : $_POST['cir_code'],
                    'mouza_pargona_code' => $this->input->get('mouza_pargona_code') ? $this->input->get('mouza_pargona_code') : $_POST['mouza_pargona_code'],
                    'lot_no' => $this->input->get('lot_no') ? $this->input->get('lot_no') : $_POST['lot_no'],
                    'vill_townprt_code' => $this->input->get('vill_townprt_code') ? $this->input->get('vill_townprt_code') : $_POST['vill_townprt_code'],
                    'loc_name' => $this->input->get('loc_name') ? $this->input->get('loc_name') : $_POST['loc_name'],
                    'unique_loc_code' => $this->input->get('unique_loc_code') ? $this->input->get('unique_loc_code') : $_POST['unique_loc_code'],
                ];
                if (isset($_POST['cir_abbr'])) {
                    $form_data['cir_abbr'] = $_POST['cir_abbr'];
                } else {
                    $form_data['cir_abbr'] = null;

                }
                if (isset($_POST['locname_eng'])) {
                    $form_data['locname_eng'] = $_POST['locname_eng'];
                } else {
                    $form_data['locname_eng'] = null;

                }
                if (isset($_POST['dist_abbr'])) {
                    $form_data['dist_abbr'] = $_POST['dist_abbr'];
                } else {
                    $form_data['dist_abbr'] = null;

                }
                if (isset($_POST['state_code_cen11'])) {
                    $form_data['state_code_cen11'] = $_POST['state_code_cen11'];
                } else {
                    $form_data['state_code_cen11'] = null;

                }
                if (isset($_POST['dist_code_cen11'])) {
                    $form_data['dist_code_cen11'] = $_POST['dist_code_cen11'];
                } else {
                    $form_data['dist_code_cen11'] = null;

                }
                if (isset($_POST['tehsil_code_cen11'])) {
                    $form_data['tehsil_code_cen11'] = $_POST['tehsil_code_cen11'];
                } else {
                    $form_data['tehsil_code_cen11'] = null;

                }
                if (isset($_POST['vill_code_cen_11'])) {
                    $form_data['vill_code_cen_11'] = $_POST['vill_code_cen_11'];
                } else {
                    $form_data['vill_code_cen_11'] = null;

                }
                if (isset($_POST['rural_urban'])) {
                    $form_data['rural_urban'] = $_POST['rural_urban'];
                } else {
                    $form_data['rural_urban'] = null;

                }
                if (isset($_POST['is_gmc'])) {
                    $form_data['is_gmc'] = $_POST['is_gmc'];
                } else {
                    $form_data['is_gmc'] = null;

                }
                if (isset($_POST['uuid'])) {
                    $form_data['uuid'] = $_POST['uuid'];
                } else {
                    $form_data['uuid'] = null;

                }
                if (isset($_POST['village_status'])) {
                    $form_data['village_status'] = $_POST['village_status'];
                } else {
                    $form_data['village_status'] = null;

                }
                if (isset($_POST['is_map'])) {
                    $form_data['is_map'] = $_POST['is_map'];
                } else {
                    $form_data['is_map'] = null;

                }
                if (isset($_POST['lgd_code'])) {
                    $form_data['lgd_code'] = $_POST['lgd_code'];
                } else {
                    $form_data['lgd_code'] = null;

                }
                if (isset($_POST['status'])) {
                    $form_data['status'] = $_POST['status'];
                } else {
                    $form_data['status'] = null;

                }
                if (isset($_POST['nc_btad'])) {
                    $form_data['nc_btad'] = $_POST['nc_btad'];
                } else {
                    $form_data['nc_btad'] = null;

                }
                if (isset($_POST['is_periphary'])) {
                    $form_data['is_periphary'] = $_POST['is_periphary'];
                } else {
                    $form_data['is_periphary'] = null;

                }
                if (isset($_POST['is_tribal'])) {
                    $form_data['is_tribal'] = $_POST['is_tribal'];
                } else {
                    $form_data['is_tribal'] = null;

                }
                if (isset($_POST['district_headquater'])) {
                    $form_data['district_headquater'] = $_POST['district_headquater'];
                } else {
                    $form_data['district_headquater'] = null;

                }
                $d = $form_data['dist_code'];
                $s = $form_data['subdiv_code'];
                $c = $form_data['cir_code'];
                $m = $form_data['mouza_pargona_code'];
                $l = $form_data['lot_no'];
                $v = $form_data['vill_townprt_code'];
                $this->session->set_userdata('dcode', $d);
                $this->dataswitch($d);
                $query = $this->db->get_where('location', array('dist_code' => $d, 'subdiv_code =' => $s, 'cir_code=' => $c, 'mouza_pargona_code=' => $m, 'lot_no=' => $l, 'vill_townprt_code' => $v))->row();
                if ($query) {
                    $this->db->trans_start();
                    if ($form_data['dist_code']) {
                        $this->db->set([
                            'dist_code' => $form_data['dist_code'],
                        ]);
                    }
                    if ($form_data['subdiv_code']) {
                        $this->db->set([
                            'subdiv_code' => $form_data['subdiv_code'],
                        ]);
                    }
                    if ($form_data['cir_code']) {
                        $this->db->set([
                            'cir_code' => $form_data['cir_code'],
                        ]);
                    }
                    if ($form_data['mouza_pargona_code']) {
                        $this->db->set([
                            'mouza_pargona_code' => $form_data['mouza_pargona_code'],
                        ]);
                    }
                    if ($form_data['lot_no']) {
                        $this->db->set([
                            'lot_no' => $form_data['lot_no'],
                        ]);
                    }
                    if ($form_data['vill_townprt_code']) {
                        $this->db->set([
                            'vill_townprt_code' => $form_data['vill_townprt_code'],
                        ]);
                    }
                    if ($form_data['loc_name']) {
                        $this->db->set([
                            'loc_name' => $form_data['loc_name'],
                        ]);
                    }
                    if ($form_data['unique_loc_code']) {
                        $this->db->set([
                            'unique_loc_code' => $form_data['unique_loc_code'],
                        ]);
                    }
                    if ($form_data['locname_eng']) {
                        $this->db->set([
                            'locname_eng' => $form_data['locname_eng'],
                        ]);
                    }
                    if ($form_data['cir_abbr']) {
                        $this->db->set([
                            'cir_abbr' => $form_data['cir_abbr'],
                        ]);
                    }
                    if ($form_data['state_code_cen11']) {
                        $this->db->set([
                            'state_code_cen11' => $form_data['state_code_cen11'],
                        ]);
                    }
                    if ($form_data['dist_code_cen11']) {
                        $this->db->set([
                            'dist_code_cen11' => $form_data['dist_code_cen11'],
                        ]);
                    }
                    if ($form_data['vill_code_cen_11']) {
                        $this->db->set([
                            'vill_code_cen_11' => $form_data['vill_code_cen_11'],
                        ]);
                    }
                    if ($form_data['tehsil_code_cen11']) {
                        $this->db->set([
                            'tehsil_code_cen11' => $form_data['tehsil_code_cen11'],
                        ]);
                    }
                    if ($form_data['rural_urban']) {
                        $this->db->set([
                            'rural_urban' => $form_data['rural_urban'],
                        ]);
                    }
                    if ($form_data['is_gmc']) {
                        $this->db->set([
                            'is_gmc' => $form_data['is_gmc'],
                        ]);
                    }
                    if ($form_data['uuid']) {
                        $this->db->set([
                            'uuid' => $form_data['uuid'],
                        ]);
                    }
                    if ($form_data['village_status']) {
                        $this->db->set([
                            'village_status' => $form_data['village_status'],
                        ]);
                    }
                    if ($form_data['is_map']) {
                        $this->db->set([
                            'is_map' => $form_data['is_map'],
                        ]);
                    }
                    if ($form_data['lgd_code']) {
                        $this->db->set([
                            'lgd_code' => $form_data['lgd_code'],
                        ]);
                    }
                    if ($form_data['status']) {
                        $this->db->set([
                            'status' => $form_data['status'],
                        ]);
                    }
                    if ($form_data['nc_btad']) {
                        $this->db->set([
                            'nc_btad' => $form_data['nc_btad'],
                        ]);
                    }
                    if ($form_data['is_periphary']) {
                        $this->db->set([
                            'is_periphary' => $form_data['is_periphary'],
                        ]);
                    }
                    if ($form_data['is_tribal']) {
                        $this->db->set([
                            'is_tribal' => $form_data['is_tribal'],
                        ]);
                    }
                    if ($form_data['district_headquater']) {
                        $this->db->set([
                            'district_headquater' => $form_data['district_headquater'],
                        ]);
                    }
                    $this->db->where('subdiv_code', $s);
                    $this->db->where('cir_code', $c);
                    $this->db->where('mouza_pargona_code', $m);
                    $this->db->where('lot_no', $l);
                    $this->db->where('vill_townprt_code', $v);

                    $this->db->update('location');

                    $this->db->trans_complete();
                    Header('Access-Control-Allow-Origin: *');
                    echo json_encode(['message' => 'Successfully Updated', 'responseCode' => 4]);
                } else {
                    Header('Access-Control-Allow-Origin: *');
                    echo json_encode(['message' => 'No Location Found', 'responseCode' => 6]);
                }
            } catch (Exception $e) {
                Header('Access-Control-Allow-Origin: *');
                echo json_encode(['message' => 'Error while updating.', 'responseCode' => 5]);
            }
        }
    }

}
