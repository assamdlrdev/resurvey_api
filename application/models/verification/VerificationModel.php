<?php

class VerificationModel extends CI_Model
{
    // public function __construct() {
    //     parent::__construct();

    // }

    public function getVillages()
    {
        $user_desig_code = $this->session->userdata('user_desig_code');
        $user_code = $this->session->userdata('usercode');
        $dist_code = $this->session->userdata('dcode');
        if ($dist_code == '21') {
            if ($user_desig_code == 'LM') {
                $subdiv_code = $this->session->userdata('subdiv_code');
                $cir_code = $this->session->userdata('cir_code');
                $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
                $lot_no = $this->session->userdata('lot_no');

                $villages = $this->db->query("SELECT 
                loc.*,
                loc_lot.loc_name AS lot_name,
                loc_mouza.loc_name AS mouza_name
            FROM 
                location loc
            JOIN 
                location loc_lot ON loc.dist_code = loc_lot.dist_code 
                    AND loc.subdiv_code = loc_lot.subdiv_code 
                    AND loc.cir_code = loc_lot.cir_code 
                    AND loc.mouza_pargona_code = loc_lot.mouza_pargona_code 
                    AND loc.lot_no = loc_lot.lot_no 
                    AND loc_lot.vill_townprt_code = '00000'
            JOIN 
                location loc_mouza ON loc.dist_code = loc_mouza.dist_code 
                    AND loc.subdiv_code = loc_mouza.subdiv_code 
                    AND loc.cir_code = loc_mouza.cir_code 
                    AND loc.mouza_pargona_code = loc_mouza.mouza_pargona_code 
                    AND loc_mouza.lot_no = '00'
            WHERE loc.dist_code = '$dist_code' 
                AND loc.subdiv_code = '$subdiv_code' 
                AND loc.cir_code = '$cir_code' 
                AND loc.mouza_pargona_code = '$mouza_pargona_code' 
                AND loc.lot_no = '$lot_no' 
                AND loc.vill_townprt_code != '00000'
                AND loc.status = 'L' 
                    ")->result();
                foreach ($villages as $village) {
                    $total_dag = $this->db->query("SELECT COUNT(dag_no) as total_dag from chitha_basic where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$village->vill_townprt_code'")->row()->total_dag;
                    $village->total_dag = $total_dag;
                    $village->alloted_dag = $total_dag;
                    $village->verified_dag = $total_dag;
                }
                return $villages;
            } else if ($user_desig_code == 'SK' || $user_desig_code == 'CO') {
                $subdiv_code = $this->session->userdata('subdiv_code');
                $cir_code = $this->session->userdata('cir_code');
                $villages = $this->db->query("SELECT 
                loc.*,
                loc_lot.loc_name AS lot_name,
                loc_mouza.loc_name AS mouza_name
            FROM 
                location loc
            JOIN 
                location loc_lot ON loc.dist_code = loc_lot.dist_code 
                    AND loc.subdiv_code = loc_lot.subdiv_code 
                    AND loc.cir_code = loc_lot.cir_code 
                    AND loc.mouza_pargona_code = loc_lot.mouza_pargona_code 
                    AND loc.lot_no = loc_lot.lot_no 
                    AND loc_lot.vill_townprt_code = '00000'
            JOIN 
                location loc_mouza ON loc.dist_code = loc_mouza.dist_code 
                    AND loc.subdiv_code = loc_mouza.subdiv_code 
                    AND loc.cir_code = loc_mouza.cir_code 
                    AND loc.mouza_pargona_code = loc_mouza.mouza_pargona_code 
                    AND loc_mouza.lot_no = '00'
            WHERE loc.dist_code = '$dist_code' 
                AND loc.subdiv_code = '$subdiv_code' 
                AND loc.cir_code = '$cir_code' 
                AND loc.vill_townprt_code != '00000'
                AND loc.status = 'L' 
                    ")->result();
                foreach ($villages as $village) {
                    $village->total_dag = $this->db->query("SELECT COUNT(dag_no) as total_dag from chitha_basic where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$village->mouza_pargona_code' and lot_no='$village->lot_no' and vill_townprt_code='$village->vill_townprt_code'")->row()->total_dag;
                    // $village->alloted_dag = $this->db->query("SELECT COUNT(dag_no) as alloted_dag from alloted_dags where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$village->mouza_pargona_code' and lot_no='$village->lot_no' and vill_townprt_code='$village->vill_townprt_code' and alloted_to='$user_code'")->row()->alloted_dag;
                    // $village->verified_dag = $this->db->query("SELECT COUNT(dag_no) as verified_dag from alloted_dags where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$village->mouza_pargona_code' and lot_no='$village->lot_no' and vill_townprt_code='$village->vill_townprt_code' and alloted_to='$user_code' and is_signed='1'")->row()->verified_dag;
                    $village->alloted_dag = $this->db->query("SELECT COUNT(dag_no) as alloted_dag from alloted_dags where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$village->mouza_pargona_code' and lot_no='$village->lot_no' and vill_townprt_code='$village->vill_townprt_code'")->row()->alloted_dag;
                    $village->verified_dag = $this->db->query("SELECT COUNT(dag_no) as verified_dag from alloted_dags where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$village->mouza_pargona_code' and lot_no='$village->lot_no' and vill_townprt_code='$village->vill_townprt_code' and is_signed='1'")->row()->verified_dag;
                }
                return $villages;
            } else if ($user_desig_code == 'ADC' || $user_desig_code == 'DC') {
                $villages = $this->db->query("SELECT 
                loc.*,
                loc_lot.loc_name AS lot_name,
                loc_mouza.loc_name AS mouza_name
            FROM 
                location loc
            JOIN 
                location loc_lot ON loc.dist_code = loc_lot.dist_code 
                    AND loc.subdiv_code = loc_lot.subdiv_code 
                    AND loc.cir_code = loc_lot.cir_code 
                    AND loc.mouza_pargona_code = loc_lot.mouza_pargona_code 
                    AND loc.lot_no = loc_lot.lot_no 
                    AND loc_lot.vill_townprt_code = '00000'
            JOIN 
                location loc_mouza ON loc.dist_code = loc_mouza.dist_code 
                    AND loc.subdiv_code = loc_mouza.subdiv_code 
                    AND loc.cir_code = loc_mouza.cir_code 
                    AND loc.mouza_pargona_code = loc_mouza.mouza_pargona_code 
                    AND loc_mouza.lot_no = '00'
            WHERE loc.dist_code = '$dist_code'
                AND loc.vill_townprt_code != '00000'
                AND loc.status = 'L' 
                    ")->result();
                foreach ($villages as $village) {
                    $village->total_dag = $this->db->query("SELECT COUNT(dag_no) as total_dag from chitha_basic where dist_code='$dist_code' and subdiv_code='$village->subdiv_code' and cir_code='$village->cir_code' and mouza_pargona_code='$village->mouza_pargona_code' and lot_no='$village->lot_no' and vill_townprt_code='$village->vill_townprt_code'")->row()->total_dag;
                    $village->alloted_dag = $this->db->query("SELECT COUNT(dag_no) as alloted_dag from alloted_dags where dist_code='$dist_code' and subdiv_code='$village->subdiv_code' and cir_code='$village->cir_code' and mouza_pargona_code='$village->mouza_pargona_code' and lot_no='$village->lot_no' and vill_townprt_code='$village->vill_townprt_code' and alloted_to='$user_code'")->row()->alloted_dag;
                    $village->verified_dag = $this->db->query("SELECT COUNT(dag_no) as verified_dag from alloted_dags where dist_code='$dist_code' and subdiv_code='$village->subdiv_code' and cir_code='$village->cir_code' and mouza_pargona_code='$village->mouza_pargona_code' and lot_no='$village->lot_no' and vill_townprt_code='$village->vill_townprt_code' and alloted_to='$user_code' and is_signed='1'")->row()->verified_dag;
                }
                return $villages;
            }
        } else {
            if ($user_desig_code == 'LM') {
                $subdiv_code = $this->session->userdata('subdiv_code');
                $cir_code = $this->session->userdata('cir_code');
                $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
                $lot_no = $this->session->userdata('lot_no');

                $villages = $this->db->query("SELECT 
                loc.*,
                loc_lot.loc_name AS lot_name,
                loc_mouza.loc_name AS mouza_name
            FROM 
                location loc
            JOIN 
                location loc_lot ON loc.dist_code = loc_lot.dist_code 
                    AND loc.subdiv_code = loc_lot.subdiv_code 
                    AND loc.cir_code = loc_lot.cir_code 
                    AND loc.mouza_pargona_code = loc_lot.mouza_pargona_code 
                    AND loc.lot_no = loc_lot.lot_no 
                    AND loc_lot.vill_townprt_code = '00000'
            JOIN 
                location loc_mouza ON loc.dist_code = loc_mouza.dist_code 
                    AND loc.subdiv_code = loc_mouza.subdiv_code 
                    AND loc.cir_code = loc_mouza.cir_code 
                    AND loc.mouza_pargona_code = loc_mouza.mouza_pargona_code 
                    AND loc_mouza.lot_no = '00'
            WHERE loc.dist_code = '$dist_code' 
                AND loc.subdiv_code = '$subdiv_code' 
                AND loc.cir_code = '$cir_code' 
                AND loc.mouza_pargona_code = '$mouza_pargona_code' 
                AND loc.lot_no = '$lot_no' 
                AND loc.vill_townprt_code != '00000'
                    ")->result();
                foreach ($villages as $village) {
                    $total_dag = $this->db->query("SELECT COUNT(dag_no) as total_dag from chitha_basic where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$village->vill_townprt_code'")->row()->total_dag;
                    $village->total_dag = $total_dag;
                    $village->alloted_dag = $total_dag;
                    $village->verified_dag = $total_dag;
                }
                return $villages;
            } else if ($user_desig_code == 'SK' || $user_desig_code == 'CO') {
                $subdiv_code = $this->session->userdata('subdiv_code');
                $cir_code = $this->session->userdata('cir_code');
                $villages = $this->db->query("SELECT 
                loc.*,
                loc_lot.loc_name AS lot_name,
                loc_mouza.loc_name AS mouza_name
            FROM 
                location loc
            JOIN 
                location loc_lot ON loc.dist_code = loc_lot.dist_code 
                    AND loc.subdiv_code = loc_lot.subdiv_code 
                    AND loc.cir_code = loc_lot.cir_code 
                    AND loc.mouza_pargona_code = loc_lot.mouza_pargona_code 
                    AND loc.lot_no = loc_lot.lot_no 
                    AND loc_lot.vill_townprt_code = '00000'
            JOIN 
                location loc_mouza ON loc.dist_code = loc_mouza.dist_code 
                    AND loc.subdiv_code = loc_mouza.subdiv_code 
                    AND loc.cir_code = loc_mouza.cir_code 
                    AND loc.mouza_pargona_code = loc_mouza.mouza_pargona_code 
                    AND loc_mouza.lot_no = '00'
            WHERE loc.dist_code = '$dist_code' 
                AND loc.subdiv_code = '$subdiv_code' 
                AND loc.cir_code = '$cir_code' 
                AND loc.vill_townprt_code != '00000'
                    ")->result();
                foreach ($villages as $village) {
                    $village->total_dag = $this->db->query("SELECT COUNT(dag_no) as total_dag from chitha_basic where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$village->mouza_pargona_code' and lot_no='$village->lot_no' and vill_townprt_code='$village->vill_townprt_code'")->row()->total_dag;
                    // $village->alloted_dag = $this->db->query("SELECT COUNT(dag_no) as alloted_dag from alloted_dags where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$village->mouza_pargona_code' and lot_no='$village->lot_no' and vill_townprt_code='$village->vill_townprt_code' and alloted_to='$user_code'")->row()->alloted_dag;
                    // $village->verified_dag = $this->db->query("SELECT COUNT(dag_no) as verified_dag from alloted_dags where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$village->mouza_pargona_code' and lot_no='$village->lot_no' and vill_townprt_code='$village->vill_townprt_code' and alloted_to='$user_code' and is_signed='1'")->row()->verified_dag;
                    $village->alloted_dag = $this->db->query("SELECT COUNT(dag_no) as alloted_dag from alloted_dags where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$village->mouza_pargona_code' and lot_no='$village->lot_no' and vill_townprt_code='$village->vill_townprt_code'")->row()->alloted_dag;
                    $village->verified_dag = $this->db->query("SELECT COUNT(dag_no) as verified_dag from alloted_dags where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$village->mouza_pargona_code' and lot_no='$village->lot_no' and vill_townprt_code='$village->vill_townprt_code' and is_signed='1'")->row()->verified_dag;
                }
                return $villages;
            } else if ($user_desig_code == 'ADC' || $user_desig_code == 'DC') {
                $villages = $this->db->query("SELECT 
                loc.*,
                loc_lot.loc_name AS lot_name,
                loc_mouza.loc_name AS mouza_name
            FROM 
                location loc
            JOIN 
                location loc_lot ON loc.dist_code = loc_lot.dist_code 
                    AND loc.subdiv_code = loc_lot.subdiv_code 
                    AND loc.cir_code = loc_lot.cir_code 
                    AND loc.mouza_pargona_code = loc_lot.mouza_pargona_code 
                    AND loc.lot_no = loc_lot.lot_no 
                    AND loc_lot.vill_townprt_code = '00000'
            JOIN 
                location loc_mouza ON loc.dist_code = loc_mouza.dist_code 
                    AND loc.subdiv_code = loc_mouza.subdiv_code 
                    AND loc.cir_code = loc_mouza.cir_code 
                    AND loc.mouza_pargona_code = loc_mouza.mouza_pargona_code 
                    AND loc_mouza.lot_no = '00'
            WHERE loc.dist_code = '$dist_code'
                AND loc.vill_townprt_code != '00000'
                    ")->result();
                foreach ($villages as $village) {
                    $village->total_dag = $this->db->query("SELECT COUNT(dag_no) as total_dag from chitha_basic where dist_code='$dist_code' and subdiv_code='$village->subdiv_code' and cir_code='$village->cir_code' and mouza_pargona_code='$village->mouza_pargona_code' and lot_no='$village->lot_no' and vill_townprt_code='$village->vill_townprt_code'")->row()->total_dag;
                    $village->alloted_dag = $this->db->query("SELECT COUNT(dag_no) as alloted_dag from alloted_dags where dist_code='$dist_code' and subdiv_code='$village->subdiv_code' and cir_code='$village->cir_code' and mouza_pargona_code='$village->mouza_pargona_code' and lot_no='$village->lot_no' and vill_townprt_code='$village->vill_townprt_code' and alloted_to='$user_code'")->row()->alloted_dag;
                    $village->verified_dag = $this->db->query("SELECT COUNT(dag_no) as verified_dag from alloted_dags where dist_code='$dist_code' and subdiv_code='$village->subdiv_code' and cir_code='$village->cir_code' and mouza_pargona_code='$village->mouza_pargona_code' and lot_no='$village->lot_no' and vill_townprt_code='$village->vill_townprt_code' and alloted_to='$user_code' and is_signed='1'")->row()->verified_dag;
                }
                return $villages;
            }
        }
    }

    public function isCertificateReadyToEsign($villages)
    {
        $status = true;
        $message = '';
        $location_arr = [];

        if (count((array) $villages) > 0) {
            foreach ($villages as $village) {
                if ($village->alloted_dag > 0) {
                    if ($village->alloted_dag != $village->verified_dag) {
                        $location = $village->mouza_name . ', ' . $village->lot_name . ', ' . $village->loc_name;
                        array_push($location_arr, $location);
                        $status = false;
                    }
                } else {
                    // Dags have not been alloted yet
                    $location = $village->mouza_name . ', ' . $village->lot_name . ', ' . $village->loc_name;
                    array_push($location_arr, $location);
                    $status = false;
                }
            }

            if (!$status) {
                $message = 'Dags have not been verified or not alloted yet for the following villages';
            }
        } else {
            $status = false;
            $message = 'No villages found to esign';
        }

        return [
            'success' => $status,
            'message' => $message,
            'locations' => implode(' | ', $location_arr)
        ];
    }
}
