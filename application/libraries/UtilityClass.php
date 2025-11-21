<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class UtilityClass {
    protected $CI;

    public function __construct() {
        // Get the CodeIgniter super object
        $this->CI =& get_instance();
    }
    public function setSession($data) {
        foreach ($data as $key => $value) {
            
        }
    }

    public function getLocationFromSession() {
        $CI = & get_instance();
        $CI->load->library('session');
        $location = array(
            'dist_code' => $CI->session->userdata('dist_code'),
            'subdiv_code' => $CI->session->userdata('subdiv_code'),
            'cir_code' => $CI->session->userdata('cir_code'),
            'lot_no' => $CI->session->userdata('lot_no'),
            'vill_townprt_code' => $CI->session->userdata('vill_code'),
            'mouza_pargona_code' => $CI->session->userdata('mouza_pargona_code')
        );
        return $location;
    }

    function Total_Lessa($bigha, $katha, $lessa) {
        $total_lessa = $lessa + ($katha * 20) + ($bigha * 100);
        return $total_lessa;
    }

    function TotalAre($total_lesa) {
        $centiarr = (10000 / 747) * $total_lesa;
        $totalAre = ($centiarr / 100);
        return $totalAre;
    }

    function get_Hec_Are_CAre($bigha, $katha, $lessa) {

        $total_lesa = ($bigha * 5 * 20) + ($katha * 20) + $lessa;
        $centiarr = (10000 / 747) * $total_lesa;
        $hectar = $centiarr / 10000;

        $wholeHector = floor($hectar);      // 1
        $fraction1 = $hectar - $wholeHector; // .25
        $arr = 100 * $fraction1;
        $wholeArr = floor($arr);

        $fraction2 = $arr - $wholeArr;
        $arr2 = $fraction2 * 100;
        $wholeCArr = round($arr2,4);

        $hec_are_care = $wholeHector . "-" . $wholeArr . "-" . $wholeCArr;
        return $hec_are_care;
    }

    function Total_Bigha_Katha_Lessa($total_lessa) {
        $bigha = $total_lessa / 100;
        $rem_lessa = fmod($total_lessa, 100);
        $katha = $rem_lessa / 20;
        $r_lessa = fmod($rem_lessa, 20);
        $mesaure = array();
        $mesaure[].=floor($bigha);
        $mesaure[].=floor($katha);
        $mesaure[].=$r_lessa;
        return $mesaure;
    }

    public function getDistrictName($dist_code) {
        $CI = & get_instance();

        $q = "select loc_name AS district from location where dist_code ='$dist_code'  and "
                . " subdiv_code='00' and cir_code='00' and mouza_pargona_code='00' and "
                . " vill_townprt_code='00000' and lot_no='00'";


        $district = $CI->db->query("select loc_name AS district from location where dist_code ='$dist_code'  and "
                . " subdiv_code='00' and cir_code='00' and mouza_pargona_code='00' and "
                . " vill_townprt_code='00000' and lot_no='00'");
        return $district->row()->district;
    }

    public function getDistrictNamebydbload($dist_code) {
        $CI = & get_instance();

        $db = $CI->load->database($dist_code, TRUE);
        $CI->dbc = $db;

        $q = "select loc_name AS district from location where dist_code ='$dist_code'  and "
                . " subdiv_code='00' and cir_code='00' and mouza_pargona_code='00' and "
                . " vill_townprt_code='00000' and lot_no='00'";


        $district = $CI->dbc->query("select loc_name AS district from location where dist_code ='$dist_code'  and "
                . " subdiv_code='00' and cir_code='00' and mouza_pargona_code='00' and "
                . " vill_townprt_code='00000' and lot_no='00'");
        return $district->row()->district;
    }

//function created for displaying the subdivision name
    public function getSubDivName($dist_code, $subdiv_code) {
        $CI = & get_instance();
        $subdiv = $CI->db->query("select loc_name AS subdiv from location where dist_code ='$dist_code'  and "
                . " subdiv_code='$subdiv_code' and cir_code='00' and mouza_pargona_code='00' and "
                . " vill_townprt_code='00000' and lot_no='00'");
        return $subdiv->row()->subdiv;
    }

//function created for displaying the circle name
    public function getCircleNamebydbload($dist_code, $subdiv_code, $circle_code) {
        $CI = & get_instance();
        $db = $CI->load->database($dist_code, TRUE);
        $CI->dbc = $db;

        $circle = $CI->dbc->query("select loc_name AS circle from location where dist_code ='$dist_code'  and "
                . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='00' and "
                . " vill_townprt_code='00000' and lot_no='00'");

        return $circle->row()->circle;
    }

    public function getCircleName($dist_code, $subdiv_code, $circle_code) {
        $CI = & get_instance();
        $circle = $CI->db->query("select loc_name AS circle from location where dist_code ='$dist_code'  and "
                . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='00' and "
                . " vill_townprt_code='00000' and lot_no='00'");

        return $circle->row()->circle;
    }

//function created for displaying the mouza name
    public function getMouzaName($dist_code, $subdiv_code, $circle_code, $mouza_code) {
        $CI = & get_instance();
        $mouza = $CI->db->query("select loc_name AS mouza from location where dist_code ='$dist_code'  and "
                . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='$mouza_code' and "
                . " vill_townprt_code='00000' and lot_no='00'");
        return $mouza->row()->mouza;
    }

//function for all the Circl
    public function getAllCircleName($dist_code, $subdiv_code) {
        $CI = & get_instance();
        $cir_code = $CI->db->query("select cir_code as cir_code ,loc_name as loc_name from location where dist_code ='$dist_code'  and "
                        . " subdiv_code='$subdiv_code' and cir_code !='00' and mouza_pargona_code='00' and "
                        . " vill_townprt_code='00000' and lot_no='00'")->result();

        return $cir_code;
    }

//function created for displaying the lot No
    public function getLotName($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no) {
        $CI = & get_instance();
        $lot = $CI->db->query("select loc_name from location where dist_code ='$dist_code'  and "
                . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='$mouza_code' and "
                . " vill_townprt_code='00000' and lot_no='$lot_no'");
        return $lot->row()->loc_name;
    }

    //function created for displaying the lot Name
    public function getLotLocationName($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no) {
        $CI = & get_instance();
        $lot = $CI->db->query("select loc_name from location where dist_code ='$dist_code'  and "
                . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='$mouza_code' and "
                . " vill_townprt_code='00000' and lot_no='$lot_no'")->row();
       /*echo "select loc_name from location where dist_code ='$dist_code'  and "
                . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='$mouza_code' and "
                . " vill_townprt_code='00000' and lot_no='$lot_no'";*/
        if($lot){
            return $lot->loc_name;
        }else{
            return '';
        }
		// return $lot->row()->loc_name;
    }

//function created for displaying the village name
    public function getVillageName($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code) {
        $CI = & get_instance();
        // $q = "select loc_name AS village from location where dist_code ='$dist_code'  and "
        //         . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='$mouza_code' and "
        //         . " vill_townprt_code='$vill_code' and lot_no='$lot_no'";

        $village = $CI->db->query("select loc_name AS village from location where dist_code ='$dist_code'  and "
                . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='$mouza_code' and "
                . " vill_townprt_code='$vill_code' and lot_no='$lot_no'");

        return $village->row()->village;
    }

    public function getTransferType($transcode) {
        $CI = & get_instance();
        $data = $CI->db->get_where('nature_trans_code', array('trans_code' => $transcode))->row()->trans_desc_as;

        return $data;
    }

    public function getMonth($month) {
        $month = trim($month);
        switch ($month) {
            case '01':
                $month = "January";
                break;
            case '02':
                $month = "February";
                break;
            case '03':
                $month = "March";
                break;
            case '04':
                $month = "April";
                break;
            case '05':
                $month = "May";
                break;
            case '06':
                $month = "June";
                break;
            case '07':
                $month = "July";
                break;
            case '08':
                $month = "August";
                break;
            case '09':
                $month = "September";
                break;
            case '10':
                $month = "October";
                break;
            case '11':
                $month = "November";
                break;
            case '12':
                $month = "December";
                break;
            case '00':
                $month = "Full Year";
                break;
            default:
                break;
        }
        return $month;
    }

//function created for find the first and last day of the month
//#################################################################
    public function First_Last_Date_of_Month($year, $month) {
        $start_date = $year . "-" . $month . "-01";
        if (($month == 1) || ($month == 3) || ($month == 5) || ($month == 7) || ($month == 8) || ($month == 10) || ($month == 12)) {
            $last_date = $year . "-" . $month . "-31";
        } elseif (($month == 4) || ($month == 6) || ($month == 9) || ($month == 11)) {
            $last_date = $year . "-" . $month . "-30";
        } elseif (($month == 2)) {
            $checkLeapYear = $year % 4;
            if ($checkLeapYear == 0) {
                $last_date = $year . "-" . $month . "-29";
            } else {
                $last_date = $year . "-" . $month . "-28";
            }
        }
        $First_Last = array();
        $First_Last[].=$start_date;
        $First_Last[].=$last_date;
        return $First_Last;
    }

//#################################################################

    public function get_name($pattadar) {
        $CI = & get_instance();
        $CI->load->library('session');
        $location = $this->getLocationFromSession();
//var_dump($location);
        $dist_code = $location['dist_code'];
        $subdiv_code = $location['subdiv_code'];
        $cir_code = $location['cir_code'];
        $lot_no = $location['lot_no'];
        $mouza_pargona_code = $location['mouza_pargona_code'];
        $vill_townprt_code = $location['vill_townprt_code'];
        $dag_no = $CI->session->userdata['dag_no'];
        $patta_no = trim($CI->session->userdata['patta_no']);


        $q = "select p.pdar_id,p.pdar_name,p.pdar_father,p.pdar_add1,p.pdar_add2,p.pdar_add3,p.pdar_guard_reln from chitha_pattadar p join chitha_dag_pattadar d 
            on p.dist_code = d.dist_code and p.subdiv_code = d.subdiv_code and p.cir_code = d.cir_code and p.lot_no = d.lot_no 
            and p.vill_townprt_code = d.vill_townprt_code and p.mouza_pargona_code = d.mouza_pargona_code and p.pdar_id = d.pdar_id 
            where p.dist_code='$dist_code' and p.subdiv_code='$subdiv_code' and p.cir_code='$cir_code' and p.mouza_pargona_code='$mouza_pargona_code'
            and p.vill_townprt_code='$vill_townprt_code' and d.lot_no='$lot_no' 
            and d.dag_no='$dag_no' and TRIM(p.patta_no)='$patta_no' and p.patta_type_code='0202' and d.pdar_id='$pattadar'";
//echo $q;

        $query = $CI->db->query("select p.pdar_id,p.pdar_name,p.pdar_father,p.pdar_add1,p.pdar_add2,p.pdar_add3,p.pdar_guard_reln from chitha_pattadar p join chitha_dag_pattadar d 
            on p.dist_code = d.dist_code and p.subdiv_code = d.subdiv_code and p.cir_code = d.cir_code and p.lot_no = d.lot_no 
            and p.vill_townprt_code = d.vill_townprt_code and p.mouza_pargona_code = d.mouza_pargona_code and p.pdar_id = d.pdar_id 
            where p.dist_code='$dist_code' and p.subdiv_code='$subdiv_code' and p.cir_code='$cir_code' and p.mouza_pargona_code='$mouza_pargona_code'
            and p.vill_townprt_code='$vill_townprt_code' and d.lot_no='$lot_no' 
            and d.dag_no='$dag_no' and TRIM(p.patta_no)='$patta_no' and p.patta_type_code='0202' and d.pdar_id='$pattadar'");

        return $query->row()->pdar_name;
    }

    public function get_relation($relation) {
        $CI = & get_instance();
        $relation = strtoLower($relation);
        $query = "select guard_rel_desc_as from master_guard_rel where guard_rel = '$relation'";

        $relation = $CI->db->query($query);
        $row = $relation->num_rows();
        if ($row != 0) {
            return $relation->row()->guard_rel_desc_as;
        }

        return "unknown";
    }

    public function getLapseDays($submission_date) {
//date created from the string
        $sub_date = date_create($submission_date);
        $today = date_create(date("Y-m-d"));
//difference counted
        $diff = date_diff($today, $sub_date);
        $date = $diff->format("%R%a");
//find the sign
        $d = $diff->format("%R%");
//take the integer value
        $days = intval($date);

        if ($d == '+') {
            $dif = "Due for " . $days . " days";
        } elseif ($d == '-') {
//explode by - sign
            $dd = explode("-", $days);
            $dif = "Lapsed by " . $dd[1] . " days";
        }

        return $dif;
    }

    public function getOfficeMutType($order) {
        $CI = & get_instance();
        $order = trim($order);

        $relation = $CI->db->query("select order_type from master_office_mut_type"
                        . " where order_type_code = '$order'")->row()->order_type;
        return $relation;
    }

    public function getMondalsName($d, $s, $c, $m, $l) {
        $CI = & get_instance();
        $q = "select lm_name,lm_code from lm_code"
                . " where dist_code='$d' and subdiv_code='$s' and cir_code='$c' "
                . " and mouza_pargona_code='$m' and lot_no='$m'";

        $relation = $CI->db->query("select lm_name,lm_code from lm_code"
                        . " where dist_code='$d' and subdiv_code='$s' and cir_code='$c' "
                        . " and mouza_pargona_code='$m' and lot_no='$m'")->result();

        return $relation;
    }

    public function getSKName($d, $s, $c, $name = "") {
        $CI = & get_instance();
        $q = "select user_code,username from users"
                . " where dist_code='$d' and subdiv_code='$s' and cir_code='$c' and user_code='$name' "
                . " ";

        if ($name != null) {
            $relation = $CI->db->query("select user_code,username from users"
                            . " where dist_code='$d' and subdiv_code='$s' and cir_code='$c' and user_code='$name' "
                            . " ")->result();
        } else {
            $relation = $CI->db->query("select user_code,username from users"
                            . " where dist_code='$d' and subdiv_code='$s' and cir_code='$c' "
                            . " ")->result();
        }


        return $relation;
    }

    public function getCOName($d, $s, $c, $name = "") {

        $CI = & get_instance();
        if ($name != null) {
            $q = "select user_code,username from users"
                    . " where dist_code='$d' and subdiv_code='$s' and cir_code='$c' and user_code='$name' "
                    . " ";

            $relation = $CI->db->query("select user_code,username from users"
                            . " where dist_code='$d' and subdiv_code='$s' and cir_code='$c' and user_code='$name' "
                            . " ")->result();
        } else {
            $q = "select user_code,username from users"
                    . " where dist_code='$d' and subdiv_code='$s' and cir_code='$c'"
                    . " ";

            $relation = $CI->db->query("select user_code,username from users"
                            . " where dist_code='$d' and subdiv_code='$s' and cir_code='$c'"
                            . " ")->result();
        }

        return $relation;
    }

    public function getSelectedAssttName($d, $s, $c, $l) {
        $CI = & get_instance();
        $query = "select username from users where dist_code='$d' and subdiv_code='$s' and cir_code='$c' and "
                . "user_code='$l'";

        return $CI->db->query($query)->row();
    }

    public function getSelectedMondalsName($d, $s, $c, $m, $l) {
        $CI = & get_instance();
        $query = "select lm_name,lm_code from lm_code where dist_code='$d' and subdiv_code='$s' and cir_code='$c' and "
                . "mouza_pargona_code='$m' and lot_no='$l'";

        return $CI->db->query($query)->row();
    }

    public function getDefinedSKName($d, $s, $c, $code) {
        $CI = & get_instance();
        $query = "select user_code,username from users where dist_code='$d' and subdiv_code='$s' and cir_code='$c' and "
                . "user_code='$code'";

        return $CI->db->query($query)->row();
    }

    public function getDefinedMondalsName($d, $s, $c, $m, $l, $code) {
        $CI = & get_instance();

        $query = "select lm_name,lm_code from lm_code where dist_code='$d' and subdiv_code='$s' and cir_code='$c' and "
                . "mouza_pargona_code='$m' and lot_no='$l' and lm_code='$code'";
		//echo $query;
        return $CI->db->query($query)->row();
    }

    public function getDefinedBOName($d, $user) {
        $CI = & get_instance();
        $query = "select username,user_code from users where dist_code='$d' and user_code='$user'";
        return $CI->db->query($query)->row();
    }

    public function getSelectedSKName($d, $s, $c) {
        $CI = & get_instance();
        $query = "select username,user_code from users where dist_code='$d' and subdiv_code='$s' and cir_code='$c' and"
                . " user_desig_code='SK'";
        return $CI->db->query($query)->row();
    }

    public function getSelectedCOName($d, $s, $c, $user) {
        $CI = & get_instance();
        $query = "select username,user_code from users where dist_code='$d' and subdiv_code='$s' and cir_code='$c' and"
                . " user_code='$user'";

        return $CI->db->query($query)->row();
    }

    public function getSelectedASOName($d, $s, $c, $user) {
        $CI = & get_instance();
        $query = "select username,user_code from users where dist_code='$d' and subdiv_code='$s' and cir_code='$c' and"
                . " user_code='$user'";

        return $CI->db->query($query)->row();
    }

    function getSelectedRkgName($d, $s, $c, $user) {
        $CI = & get_instance();
        $query = "select username,user_code from users where dist_code='$d' and subdiv_code='$s' and cir_code='$c' and"
                . " user_code='$user'";
        return $CI->db->query($query)->row();
    }

    function getSelectedRSName($d, $s, $c, $user) {
        $CI = & get_instance();
        $query = "select username,user_code from users where dist_code='$d' and subdiv_code='$s' and cir_code='$c' and"
                . " user_code='$user'";
        return $CI->db->query($query)->row();
    }

    function getSelectedjadName($d, $s, $c, $user) {
        $CI = & get_instance();
        $query = "select username,user_code from users where dist_code='$d' and subdiv_code='$s' and cir_code='$c' and"
                . " user_code='$user'";
        return $CI->db->query($query)->row();
    }

    function getSelectedsadName($d, $s, $c, $user) {
        $CI = & get_instance();
        $query = "select username,user_code from users where dist_code='$d' and subdiv_code='$s' and cir_code='$c' and"
                . " user_code='$user'";
        return $CI->db->query($query)->row();
    }

    public function getPdarName($d, $s, $c, $m, $l, $v, $pid, $dag) {
        $CI = & get_instance();
        $query = "select pdar_name from chitha_pattadar cp join chitha_dag_pattadar dp on"
                . " cp.dist_code=dp.dist_code and cp.subdiv_code=dp.subdiv_code and cp.cir_code=dp.cir_code and "
                . " cp.mouza_pargona_code=dp.mouza_pargona_code and cp.lot_no = dp.lot_no and "
                . " cp.vill_townprt_code = dp.vill_townprt_code and TRIM(cp.patta_no)=TRIM(dp.patta_no) and"
                . " cp.patta_type_code = dp.patta_type_code and cp.pdar_id = dp.pdar_id and TRIM(cp.patta_no) = "
                . " TRIM(dp.patta_no) where dp.dag_no='$dag' and cp.pdar_id=$pid"
                . " and cp.dist_code='$d' and cp.subdiv_code='$s' and cp.cir_code='$c' and cp.mouza_pargona_code='$m' and"
                . " cp.lot_no='$l' and cp.vill_townprt_code='$v'"
                . "";

        return $CI->db->query($query)->row()->pdar_name;
    }

    public function getCertName($cert) {
        $CI = & get_instance();
        $query = "Select cert_type from cert_type where cert_code='$cert'";
        return $CI->db->query($query)->row()->cert_type;
    }

    public function getCertCode($cert) {
        $CI = & get_instance();
        $query = "Select cert_name_code from cert_type where cert_code='$cert'";
        return $CI->db->query($query)->row()->cert_name_code;
    }

    public function getRevenuLoc($d, $s, $c) {
        $CI = & get_instance();
        $query = "Select rev_name from cert_revenue_location where dist_code='$d' and subdiv_code='$s' and cir_code='$c' ";
        //echo $query;
        return $CI->db->query($query)->row()->rev_name;
    }

    public function getPattaName($d) {
        $CI = & get_instance();
        $query = "Select patta_type from patta_code where type_code='$d'";
        //echo $query;
        return $CI->db->query($query)->row()->patta_type;
    }

    public function getLandClassCode($d) {
        $CI = & get_instance();
        $query = "Select land_type from landclass_code where class_code='$d'";
        return $CI->db->query($query)->row()->land_type;
    }

    public function getLandClasses() {
        $CI = & get_instance();
        $query = "Select * from landclass_code";
        return $CI->db->query($query)->result();
    }

    public function getLmByCode($lm_code) {
        $CI = & get_instance();
        $sql = "select lm_name,lm_code from lm_code where lm_code='$lm_code'";
        return $CI->db->query($sql)->row();
    }

    public function getSKByCode($d, $s, $c, $sk_code) {
        $CI = & get_instance();

        $sql = "select username,user_code from users where user_code='$sk_code' and dist_code='$d' and subdiv_code='$s' and cir_code='$c' ";
        return $CI->db->query($sql)->row();
    }

    public function getCOCode($d, $s, $c, $co_code) {
        $CI = & get_instance();
        $sql = "select username,user_code from users where user_code='$co_code' and dist_code='$d' and subdiv_code='$s' and cir_code='$c'";
        return $CI->db->query($sql)->row();
    }

    public function getPattaType($code) {
        $CI = & get_instance();
        $sql = "select patta_type from patta_code where type_code='$code'";
        return $CI->db->query($sql)->row()->patta_type;
    }

    public function getDaysAfter($diff) {
        $today = date('Y-m-d');
        $nextdate = date('Y-m-d', strtotime($today . ' + ' . $diff . ' days'));
        return $nextdate;
    }

    public function GetCaseStatus($code) {
        $CI = & get_instance();
        $sql = "SELECT * FROM case_status where status_code='$code'";
        //echo $sql;
        return $CI->db->query($sql)->row()->description;
    }

    public function MondalName($d, $s, $c, $m, $l) {
        $CI = & get_instance();
        $relation = $CI->db->query("select lm_name from lm_code"
                        . " where dist_code='$d' and subdiv_code='$s' and cir_code='$c' "
                        . " and mouza_pargona_code='$m' and lot_no='$l'")->row();
        return $relation;
    }

    public function EnabledMondalName($d, $s, $c, $m, $l) {
        $CI = & get_instance();
        $relation = $CI->db->query("SELECT * FROM lm_code as c JOIN loginuser_table as t ON c.dist_code=t.dist_code and "
                        . "c.subdiv_code = t.subdiv_code and c.cir_code = t.cir_code and c.mouza_pargona_code=t.mouza_pargona_code and c.lot_no=t.lot_no "
                        . "and c.lm_code = t.user_code and t.dist_code='$d' and "
                        . "t.subdiv_code='$s' and t.cir_code='$c' and t.mouza_pargona_code = '$m' and t.lot_no = '$l' and t.dis_enb_option='E'")->row();
        return $relation;
    }

    function get_user_status($controllers, $v) {
        $CI = & get_instance();
        $controller_name = $controllers;
        $function_name = $v;
        $sql = "select user_desig_code from user_permission where controller_name = '$controller_name' and function_name = '$function_name'";
        $sql = $CI->db->query($sql)->result();
        //$result = $controllers."-".$v;
        return $sql;
    }

    function ByrightOf($d) {
        $CI = & get_instance();
        $sql = "select order_type from master_office_mut_type where order_type_code = '$d' ";
        $sql = $CI->db->query($sql)->row();
        //$result = $controllers."-".$v;
        return $sql;
    }

    function crop_category_code($n) {
        $CI = & get_instance();
        $sql = "select * from crop_category_code where crop_categ_code = '$n' ";
        $sql = $CI->db->query($sql)->row();
        return $sql;
    }

    function getMutationTypeObject($n) {
        $CI = & get_instance();
        $sql = "Select order_type from col8_order_type where order_type_code = '$n' ";
        $sql = $CI->db->query($sql)->row();
        return $sql;
    }

    function dagnumbr($d, $s, $c, $m, $l, $v, $p) {
        $CI = & get_instance();
        $sql = "Select dag_no from petition_dag_details where dist_code ='$d' and subdiv_code='$s' and cir_code='$c' "
                . "and mouza_pargona_code='$m' and lot_no='$l' and vill_townprt_code='$v' and petition_no='$p' ";
        $sql = $CI->db->query($sql)->row();
        return $sql;
    }

    function ordinal_suffix_of($i) {
        $j = $i % 10;
        $k = $i % 100;
        if ($j == 1 && $k != 11) {
            return $i . "st";
        } elseif ($j == 2 && $k != 12) {
            return $i . "nd";
        } elseif ($j == 3 && $k != 13) {
            return $i . "rd";
        } else {
            return $i . "th";
        }
    }

    public function getsk_mapping($sk_code) {
        $CI = & get_instance();
        $CI->load->library('session');
        $dist_code = $CI->session->userdata('dist_code');
        $subdiv_code = $CI->session->userdata('subdiv_code');
        $cir_code = $CI->session->userdata('cir_code');
        $check_sk = $CI->db->query("Select count(*) as check_sk from loginuser_table where dist_code = '$dist_code' and subdiv_code = '$subdiv_code' and "
                        . "cir_code = '$cir_code' and user_code = '$sk_code' and dis_enb_option = 'E'")->row()->check_sk;
        return $check_sk;
    }

    function allote_scheme_name($id) {
        $CI = & get_instance();
        $q = "SElect scheme_name_ass as name from allote_scheme_name where sid='$id'";
        $scname = $CI->db->query($q)->row()->name;
        return $scname;
    }

    function dcname($d, $u) {
        $CI = & get_instance();
        $q = "SElect username as name from users where dist_code='$d' and user_code='$u'";
        $dcname = $CI->db->query($q)->row()->name;
        return $dcname;
    }

    function caste_name($id) {
        $CI = & get_instance();
        $q = "SElect caste_name_ass as name from master_caste where caste_id='$id'";
        $scname = $CI->db->query($q)->row()->name;
        return $scname;
    }

    function gender($id) {
        $CI = & get_instance();
        $q = "SElect gen_name_ass as name from master_gender where id='$id'";
        $scname = $CI->db->query($q)->row()->name;
        return $scname;
    }

    function getGenderName($short_name) {
        $CI = & get_instance();
        $q = "SELECT gen_name_ass FROM master_gender WHERE short_name=?";
        $scname = $CI->db->query($q, [$short_name])->row()->gen_name_ass;
        return $scname;
    }

    function maxdag($d, $s, $c, $m, $l, $v) {
        $CI = & get_instance();
        $q = "Select max(dag_no_int) as new_dag from chitha_basic where dist_code='$d' and subdiv_code='$s' and cir_code='$c' and mouza_pargona_code='$m' and lot_no='$l' and vill_townprt_code='$v'";
        $new_dag = $CI->db->query($q)->row()->new_dag;
        $new_dag = $new_dag / 100;
        $new_dag = $new_dag + 1;
        return $new_dag;
    }

    function maxpatta($d, $s, $c, $m, $l, $v, $pp) {
        $CI = & get_instance();
        $newpatta = 0;
        $q = "Select patta_no from chitha_basic where dist_code='$d' and subdiv_code='$s' and cir_code='$c' and mouza_pargona_code='$m' and lot_no='$l' and vill_townprt_code='$v' and patta_type_code='$pp' ";
        $patta_no = $CI->db->query($q)->result();
        foreach ($patta_no as $p) {
            $p = trim($p->patta_no);
            $p = (int) ($p);
            if ($newpatta < $p) {
                $newpatta = $p;
            }
        }
        $newpatta = $newpatta + 1;
        return $newpatta;
    }

    function getPartitionPattaType() {
        $CI = & get_instance();
        $sql = "Select type_code,patta_type from patta_code where mutation='a' ";
        $sql = $CI->db->query($sql)->result();
        return $sql;
    }

    function allDags($d, $s, $c, $m, $l, $v, $pno, $pc) {
        $CI = & get_instance();
        $query = "select * from chitha_basic where dist_code= '$d' and subdiv_code='$s' and "
                . " cir_code='$c' and lot_no='$l' and mouza_pargona_code='$m' and "
                . " vill_townprt_code='$v' and trim(patta_no)=trim('$pno') and patta_type_code='$pc'  ";
        $sql = $CI->db->query($query)->result();
        return $sql;
    }

    function getnameByPdarId($d, $s, $c, $m, $l, $v, $pno, $pc, $id) {
        $CI = & get_instance();
        $query = "select pdar_name as name from chitha_pattadar where dist_code= '$d' and subdiv_code='$s' and "
                . " cir_code='$c' and lot_no='$l' and mouza_pargona_code='$m' and "
                . " vill_townprt_code='$v' and trim(patta_no)=trim('$pno') and patta_type_code='$pc' and pdar_id='$id'  ";
        $sql = $CI->db->query($query)->row()->name;
        return $sql;
    }

    function appRelation($r) {
        $CI = & get_instance();
        $query = "select guard_rel_desc_as as name from master_guard_rel where guard_rel='$r' ";
        $sql = $CI->db->query($query)->row()->name;
        return $sql;
    }

    function cassnum($convnum) {
        //$nm = $convnum;
        $nm = strval($convnum);
                
        mb_internal_encoding('UTF-8');    
        $text = html_entity_decode($nm, ENT_QUOTES, "UTF-8");                 
        $nn = mb_strlen($text);
       
        $cr = '';
        $nums = array(array('0', '০'), array('1', '১'), array('2', '২'), array('3', '৩'), array('4', '৪'), array('5', '৫'), array('6', '৬'), array('7', '৭'), array('8', '৮'), array('9', '৯'), array('.', '.'), array(',', ','), array('-', '-'), array('/', '/'), array('খ', 'খ'), array('ক', 'ক'));
        $flag = 'false';
        for ($i = 0; $i < $nn; $i++) {
            for ($j = 0; $j < 16; $j++) {
                for ($m = 0; $m < 2; $m++) {
                   if ($nm[$i] == $nums[$j][0]) {
                        $cr = $cr . $nums[$j][1];
                        $flag = 'true';
                        //$count[0]='T';
                        break;
                    }
                    if ($flag == 'true') {
                        break;
                    }
                }
            }
        }
        return $cr;
    }
    
    function cassnumfordags($convnum) {
        $nm = TRIM($convnum);
        $value = explode(' ',$nm);
        //echo sizeof($value);
        if(sizeof($value) > 1){
            $end = end(explode(' ',$nm));
        } else {
            $end = '';
        }
        
                
        mb_internal_encoding('UTF-8');    
        $text = html_entity_decode($nm, ENT_QUOTES, "UTF-8");                 
        $nn = mb_strlen($text);
       
        $cr = '';
        $nums = array(array('0', '০'), array('1', '১'), array('2', '২'), array('3', '৩'), array('4', '৪'), array('5', '৫'), array('6', '৬'), array('7', '৭'), array('8', '৮'), array('9', '৯'), array('.', '.'), array(',', ','), array('-', '-'), array('/', '/'), array('খ', 'খ'), array('ক', 'ক'));
        $flag = 'false';
        for ($i = 0; $i < $nn; $i++) {
            for ($j = 0; $j < 16; $j++) {
                for ($m = 0; $m < 2; $m++) {
                   if ($nm[$i] == $nums[$j][0]) {
                        $cr = $cr . $nums[$j][1];
                        $flag = 'true';
                        //$count[0]='T';
                        break;
                    }
                    if ($flag == 'true') {
                        break;
                    }
                }
            }
        }
        return $cr." ".$end;
    }

    public function getMenuPermission($utility_type) {
        $CI = & get_instance();
        $menupermission = $CI->db->query("SELECT user_desig_code as user_desig_code FROM user_permission where function_name = '$utility_type' ")->row()->user_desig_code;
        return $menupermission;
    }
    
    public function getBacklogPermission($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no,$type) {
        $CI = & get_instance();
        
        $backlogperission = $CI->db->query("select status as status from backlog_request where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and "
                . "mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and request_for = '$type' and operation = 'P' ")->row();

        if(empty($backlogperission)){
            $backlogperission = 'D';
        } else {
            $backlogperission = $backlogperission->status;
        }
        return $backlogperission;
    }
    
    public function getCountBacklogPermission($dist_code, $subdiv_code, $cir_code) {
        $CI = & get_instance();
        
        $backlogCountperission = $CI->db->query("select count(*) as count from backlog_request where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and "
                . "operation = 'P' and status = 'P' ")->row();
        return $backlogCountperission;
    }
    
    public function getCountBacklogMutation($dist_code, $subdiv_code, $cir_code) {
        $CI = & get_instance();

        $qF = "select count(*) as count from field_mut_basic where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and order_passed = 'B'";
        $qF = $CI->db->query($qF)->row()->count;

        $qP = "select count(*) as count from petition_basic where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mut_type = '03' and order_passed = 'B' and status = 'B'";
        $qP = $CI->db->query($qP)->row()->count;

        $backlogMutCount = $qF + $qP;
        return $backlogMutCount;
    }

    public function getCountBacklogPartition($dist_code, $subdiv_code, $cir_code) {
        $CI = & get_instance();
        
        $qF = "select count(*) as count from t_chitha_col8_order where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and (iscorrected_inco is null or iscorrected_inco='' ) and case_no like '%-BL' ";
        $qF = $CI->db->query($qF)->row()->count;
        
        $qP = "Select count(*) as count from t_chitha_rmk_ordbasic where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and (iscorrected_inco is null or iscorrected_inco='') and case_no like '%-BL' and case_no not like '%CONV-BL'  ";
        $qP = $CI->db->query($qP)->row()->count;
        
        $backlogPartCount = $qF+$qP;
        return $backlogPartCount;
    }
    
    public function getCountBacklogConversion($dist_code, $subdiv_code, $cir_code) {
        $CI = & get_instance();

        $qP = "select count(*) as count from petition_basic where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mut_type = '01' and order_passed = 'B' and status = 'B'";
        $qP = $CI->db->query($qP)->row()->count;

        $backlogMutCount = $qP;
        return $backlogMutCount;
    }
        
    public function getRequestFor($type){
        $request_for = '';
        switch ($type) {
            case 'M':
                $request_for = 'Field / Office Mutation';
                break;
            case 'C':
                $request_for = 'Land Conversion';
                break;
            case 'P':
                $request_for = 'Field / Office Partition';
                break;
            case 'R':
                $request_for = 'Land Reclassification';
                break;
        }   
        return $request_for;
    }

    function checkExistData() {
        $q = "SElect petition_no from petition_basic status='P' and dist_code=''  and subdiv_code='' and cir_code='' and mouza_pargona_code='' and 
			lot_no=''  and vill_townprt_code='' ";
        $sql = "SElect * from field_mut_basic where dist_code=''  and subdiv_code='' and cir_code='' and mouza_pargona_code='' and 
			lot_no=''  and vill_townprt_code='' and dag_no='' and patta_no='' and (petition_no in $q )";
	}


    function generateToken($claims, $time, $ttl, $algorithm, $secret) {
        $algorithms = array('HS256' => 'sha256', 'HS384' => 'sha384', 'HS512' => 'sha512');
        $header = array();
        $header['typ'] = 'JWT';
        $header['alg'] = $algorithm;
        $token = array();
        $token[0] = rtrim(strtr(base64_encode(json_encode((object) $header)), '+/', '-_'), '=');
        $claims['iat'] = $time;
        $claims['exp'] = $time + $ttl;
        $token[1] = rtrim(strtr(base64_encode(json_encode((object) $claims)), '+/', '-_'), '=');
        if (!isset($algorithms[$algorithm]))
            return false;
        $hmac = $algorithms[$algorithm];
        $signature = hash_hmac($hmac, "$token[0].$token[1]", $secret, true);
        $token[2] = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');
        return implode('.', $token);
    }
    
    function get_strike_out_status($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no,$vill_townprt_code,$patta_type_code,$patta_no,$pdar_id) {
        $CI = & get_instance();
        
        $chitha_pattadar = "select count(*) as count from chitha_dag_pattadar where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' "
                    . "and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' and TRIM(patta_no)='$patta_no' "
                    . "and patta_type_code='$patta_type_code' and pdar_id = '$pdar_id' and p_flag = '1' ";
        return $CI->db->query($chitha_pattadar)->row()->count;
    }
    public function getVillageUUID($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code)
    {
        $this->switchDb($dist_code);
        $villageCode = $this->CI->db->query("select uuid AS village from location where dist_code ='$dist_code' and "
            . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='$mouza_code' and "
            . " vill_townprt_code='$vill_code' and lot_no='$lot_no'");

        return $villageCode->row()->village;
    }

    public function switchDb($dist_code)
    {
        if ($dist_code == "02") {
            $this->CI->db = $this->CI->load->database('lsp3', TRUE);
        } else if ($dist_code == "05") {
            $this->CI->db = $this->CI->load->database('lsp1', TRUE);
        } else if ($dist_code == "13") {
            $this->CI->db = $this->CI->load->database('lsp2', TRUE);
        } else if ($dist_code == "17") {
            $this->CI->db = $this->CI->load->database('lsp4', TRUE);
        } else if ($dist_code == "15") {
            $this->CI->db = $this->CI->load->database('lsp5', TRUE);
        } else if ($dist_code == "14") {
            $this->CI->db = $this->CI->load->database('lsp6', TRUE);
        } else if ($dist_code == "07") {
            $this->CI->db = $this->CI->load->database('lsp7', TRUE);
        } else if ($dist_code == "03") {
            $this->CI->db = $this->CI->load->database('lsp8', TRUE);
        } else if ($dist_code == "18") {
            $this->CI->db = $this->CI->load->database('lsp9', TRUE);
        } else if ($dist_code == "12") {
            $this->CI->db = $this->CI->load->database('lsp13', TRUE);
        } else if ($dist_code == "24") {
            $this->CI->db = $this->CI->load->database('lsp10', TRUE);
        } else if ($dist_code == "06") {
            $this->CI->db = $this->CI->load->database('lsp11', TRUE);
        } else if ($dist_code == "11") {
            $this->CI->db = $this->CI->load->database('lsp12', TRUE);
        } else if ($dist_code == "12") {
            $this->CI->db = $this->CI->load->database('lsp13', TRUE);
        } else if ($dist_code == "16") {
            $this->CI->db = $this->CI->load->database('lsp14', TRUE);
        } else if ($dist_code == "32") {
            $this->CI->db = $this->CI->load->database('lsp15', TRUE);
        } else if ($dist_code == "33") {
            $this->CI->db = $this->CI->load->database('lsp16', TRUE);
        } else if ($dist_code == "34") {
            $this->CI->db = $this->CI->load->database('lsp17', TRUE);
        } else if ($dist_code == "21") {
            $this->CI->db = $this->CI->load->database('lsp18', TRUE);
        } else if ($dist_code == "08") {
            $this->CI->db = $this->CI->load->database('lsp19', TRUE);
        } else if ($dist_code == "35") {
            $this->CI->db = $this->CI->load->database('lsp20', TRUE);
        } else if ($dist_code == "36") {
            $this->CI->db = $this->CI->load->database('lsp21', TRUE);
        } else if ($dist_code == "37") {
            $this->CI->db = $this->CI->load->database('lsp22', TRUE);
        } else if ($dist_code == "25") {
            $this->CI->db = $this->CI->load->database('lsp23', TRUE);
        } else if ($dist_code == "10") {
            $this->CI->db = $this->CI->load->database('lsp24', TRUE);
        } else if ($dist_code == "38") {
            $this->CI->db = $this->CI->load->database('lsp25', TRUE);
        } else if ($dist_code == "39") {
            $this->CI->db = $this->CI->load->database('lsp26', TRUE);
        } else if ($dist_code == "22") {
            $this->CI->db = $this->CI->load->database('lsp27', TRUE);
        } else if ($dist_code == "23") {
            $this->CI->db = $this->CI->load->database('lsp28', TRUE);
        } else if ($dist_code == "01") {
            $this->CI->db = $this->CI->load->database('lsp29', TRUE);
        }else if ($dist_code == "26") {
            $this->CI->db = $this->CI->load->database('lsp31', TRUE);
        }
        return $this->CI->db;
    }

    public function isAlphanumeric($char)
    {
        $isAlphaNumeric = false;
        if (is_int($char) != 1 && !ctype_alnum($char)) {
            $isAlphaNumeric = true;
        }
        return $isAlphaNumeric;
    }


}
