<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class UtilityClass
{

    public function __construct()
    {
    }

 
    public function getLocationFromSession()
    {
        $CI = &get_instance();
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

    
    public function getDistrictName($dist_code)
    {
        $CI = &get_instance();

        $q = "select loc_name AS district from location where dist_code ='$dist_code'  and "
            . " subdiv_code='00' and cir_code='00' and mouza_pargona_code='00' and "
            . " vill_townprt_code='00000' and lot_no='00'";


        $district = $CI->db->query("select loc_name AS district from location where dist_code ='$dist_code'  and "
            . " subdiv_code='00' and cir_code='00' and mouza_pargona_code='00' and "
            . " vill_townprt_code='00000' and lot_no='00'");
        return $district->row()->district;
    }

    public function getDistrictNamebydbload($dist_code)
    {
        $CI = &get_instance();

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
    public function getSubDivName($dist_code, $subdiv_code)
    {
        $CI = &get_instance();
        $subdiv = $CI->db->query("select loc_name AS subdiv from location where dist_code ='$dist_code'  and "
            . " subdiv_code='$subdiv_code' and cir_code='00' and mouza_pargona_code='00' and "
            . " vill_townprt_code='00000' and lot_no='00'");
        return $subdiv->row()->subdiv;
    }

    //function created for displaying the circle name
    public function getCircleNamebydbload($dist_code, $subdiv_code, $circle_code)
    {
        $CI = &get_instance();
        $db = $CI->load->database($dist_code, TRUE);
        $CI->dbc = $db;

        $circle = $CI->dbc->query("select loc_name AS circle from location where dist_code ='$dist_code'  and "
            . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='00' and "
            . " vill_townprt_code='00000' and lot_no='00'");

        return $circle->row()->circle;
    }

    public function getCircleName($dist_code, $subdiv_code, $circle_code)
    {
        $CI = &get_instance();
        $circle = $CI->db->query("select loc_name AS circle from location where dist_code ='$dist_code'  and "
            . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='00' and "
            . " vill_townprt_code='00000' and lot_no='00'");

        return $circle->row()->circle;
    }

    //function created for displaying the mouza name
    public function getMouzaName($dist_code, $subdiv_code, $circle_code, $mouza_code)
    {
        $CI = &get_instance();
        $mouza = $CI->db->query("select loc_name AS mouza from location where dist_code ='$dist_code'  and "
            . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='$mouza_code' and "
            . " vill_townprt_code='00000' and lot_no='00'");
        return $mouza->row()->mouza;
    }

    //function for all the Circl
    public function getAllCircleName($dist_code, $subdiv_code)
    {
        $CI = &get_instance();
        $cir_code = $CI->db->query("select cir_code as cir_code ,loc_name as loc_name from location where dist_code ='$dist_code'  and "
            . " subdiv_code='$subdiv_code' and cir_code !='00' and mouza_pargona_code='00' and "
            . " vill_townprt_code='00000' and lot_no='00'")->result();

        return $cir_code;
    }

    //function created for displaying the lot No
    public function getLotName($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no)
    {
        $CI = &get_instance();
        $lot = $CI->db->query("select loc_name from location where dist_code ='$dist_code'  and "
            . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='$mouza_code' and "
            . " vill_townprt_code='00000' and lot_no='$lot_no'");
        return $lot->row()->loc_name;
    }

    //function created for displaying the lot Name
    public function getLotLocationName($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no)
    {
        $CI = &get_instance();
        $lot = $CI->db->query("select loc_name from location where dist_code ='$dist_code'  and "
            . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='$mouza_code' and "
            . " vill_townprt_code='00000' and lot_no='$lot_no'");
        /*echo "select loc_name from location where dist_code ='$dist_code'  and "
                . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='$mouza_code' and "
                . " vill_townprt_code='00000' and lot_no='$lot_no'";*/
        return $lot->row()->loc_name;
    }

    //function created for displaying the village name
    public function getVillageName($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code)
    {
        $CI = &get_instance();
        $q = "select loc_name AS village from location where dist_code ='$dist_code'  and "
            . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='$mouza_code' and "
            . " vill_townprt_code='$vill_code' and lot_no='$lot_no'";

        $village = $CI->db->query("select loc_name AS village from location where dist_code ='$dist_code'  and "
            . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='$mouza_code' and "
            . " vill_townprt_code='$vill_code' and lot_no='$lot_no'");

        return $village->row()->village;
    }

    

    public function getMondalsName($d, $s, $c, $m, $l)
    {
        $CI = &get_instance();
        $q = "select lm_name,lm_code from lm_code"
            . " where dist_code='$d' and subdiv_code='$s' and cir_code='$c' "
            . " and mouza_pargona_code='$m' and lot_no='$m'";

        $relation = $CI->db->query("select lm_name,lm_code from lm_code"
            . " where dist_code='$d' and subdiv_code='$s' and cir_code='$c' "
            . " and mouza_pargona_code='$m' and lot_no='$m'")->result();

        return $relation;
    }

    public function getSKName($d, $s, $c, $name = "")
    {
        $CI = &get_instance();
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

    public function getCOName($d, $s, $c, $name = "")
    {

        $CI = &get_instance();
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

    public function getSelectedAssttName($d, $s, $c, $l)
    {
        $CI = &get_instance();
        $query = "select username from users where dist_code='$d' and subdiv_code='$s' and cir_code='$c' and "
            . "user_code='$l'";

        return $CI->db->query($query)->row();
    }

    public function getSelectedMondalsName($d, $s, $c, $m, $l)
    {
        $CI = &get_instance();
        $query = "select lm_name,lm_code from lm_code where dist_code='$d' and subdiv_code='$s' and cir_code='$c' and "
            . "mouza_pargona_code='$m' and lot_no='$l'";

        return $CI->db->query($query)->row();
    }

    public function getDefinedSKName($d, $s, $c, $code)
    {
        $CI = &get_instance();
        $query = "select user_code,username from users where dist_code='$d' and subdiv_code='$s' and cir_code='$c' and "
            . "user_code='$code'";

        return $CI->db->query($query)->row();
    }

    public function getDefinedMondalsName($d, $s, $c, $m, $l, $code)
    {
        $CI = &get_instance();

        $query = "select lm_name,lm_code from lm_code where dist_code='$d' and subdiv_code='$s' and cir_code='$c' and "
            . "mouza_pargona_code='$m' and lot_no='$l' and lm_code='$code'";
        //echo $query;
        return $CI->db->query($query)->row();
    }

    public function getDefinedBOName($d, $user)
    {
        $CI = &get_instance();
        $query = "select username,user_code from users where dist_code='$d' and user_code='$user'";
        return $CI->db->query($query)->row();
    }

    public function getSelectedSKName($d, $s, $c)
    {
        $CI = &get_instance();
        $query = "select username,user_code from users where dist_code='$d' and subdiv_code='$s' and cir_code='$c' and"
            . " user_desig_code='SK'";
        return $CI->db->query($query)->row();
    }

    public function getSelectedCOName($d, $s, $c, $user)
    {
        $CI = &get_instance();
        $query = "select username,user_code from users where dist_code='$d' and subdiv_code='$s' and cir_code='$c' and"
            . " user_code='$user'";

        return $CI->db->query($query)->row();
    }

    public function getSelectedASOName($d, $s, $c, $user)
    {
        $CI = &get_instance();
        $query = "select username,user_code from users where dist_code='$d' and subdiv_code='$s' and cir_code='$c' and"
            . " user_code='$user'";

        return $CI->db->query($query)->row();
    }

    function getSelectedRkgName($d, $s, $c, $user)
    {
        $CI = &get_instance();
        $query = "select username,user_code from users where dist_code='$d' and subdiv_code='$s' and cir_code='$c' and"
            . " user_code='$user'";
        return $CI->db->query($query)->row();
    }

    function getSelectedRSName($d, $s, $c, $user)
    {
        $CI = &get_instance();
        $query = "select username,user_code from users where dist_code='$d' and subdiv_code='$s' and cir_code='$c' and"
            . " user_code='$user'";
        return $CI->db->query($query)->row();
    }

    function getSelectedjadName($d, $s, $c, $user)
    {
        $CI = &get_instance();
        $query = "select username,user_code from users where dist_code='$d' and subdiv_code='$s' and cir_code='$c' and"
            . " user_code='$user'";
        return $CI->db->query($query)->row();
    }

    function getSelectedsadName($d, $s, $c, $user)
    {
        $CI = &get_instance();
        $query = "select username,user_code from users where dist_code='$d' and subdiv_code='$s' and cir_code='$c' and"
            . " user_code='$user'";
        return $CI->db->query($query)->row();
    }

    public function getPdarName($d, $s, $c, $m, $l, $v, $pid, $dag)
    {
        $CI = &get_instance();
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

    public function getCertName($cert)
    {
        $CI = &get_instance();
        $query = "Select cert_type from cert_type where cert_code='$cert'";
        return $CI->db->query($query)->row()->cert_type;
    }

    public function getCertCode($cert)
    {
        $CI = &get_instance();
        $query = "Select cert_name_code from cert_type where cert_code='$cert'";
        return $CI->db->query($query)->row()->cert_name_code;
    }

    public function getRevenuLoc($d, $s, $c)
    {
        $CI = &get_instance();
        $query = "Select rev_name from cert_revenue_location where dist_code='$d' and subdiv_code='$s' and cir_code='$c' ";
        //echo $query;
        return $CI->db->query($query)->row()->rev_name;
    }

    public function getPattaName($d)
    {
        $CI = &get_instance();
        $query = "Select patta_type from patta_code where type_code='$d'";
        //echo $query;
        return $CI->db->query($query)->row()->patta_type;
    }

    public function getLandClassCode($d)
    {
        $CI = &get_instance();
        $query = "Select land_type from landclass_code where class_code='$d'";
        return $CI->db->query($query)->row()->land_type;
    }

    public function getLandClasses()
    {
        $CI = &get_instance();
        $query = "Select * from landclass_code";
        return $CI->db->query($query)->result();
    }

    public function getLmByCode($lm_code)
    {
        $CI = &get_instance();
        $sql = "select lm_name,lm_code from lm_code where lm_code='$lm_code'";
        return $CI->db->query($sql)->row();
    }

    public function getSKByCode($d, $s, $c, $sk_code)
    {
        $CI = &get_instance();

        $sql = "select username,user_code from users where user_code='$sk_code' and dist_code='$d' and subdiv_code='$s' and cir_code='$c' ";
        return $CI->db->query($sql)->row();
    }

    public function getCOCode($d, $s, $c, $co_code)
    {
        $CI = &get_instance();
        $sql = "select username,user_code from users where user_code='$co_code' and dist_code='$d' and subdiv_code='$s' and cir_code='$c'";
        return $CI->db->query($sql)->row();
    }

    public function getPattaType($code)
    {
        $CI = &get_instance();
        $sql = "select patta_type from patta_code where type_code='$code'";
        return $CI->db->query($sql)->row()->patta_type;
    }

    public function getDaysAfter($diff)
    {
        $today = date('Y-m-d');
        $nextdate = date('Y-m-d', strtotime($today . ' + ' . $diff . ' days'));
        return $nextdate;
    }

    public function GetCaseStatus($code)
    {
        $CI = &get_instance();
        $sql = "SELECT * FROM case_status where status_code='$code'";
        //echo $sql;
        return $CI->db->query($sql)->row()->description;
    }

    public function MondalName($d, $s, $c, $m, $l)
    {
        $CI = &get_instance();
        $relation = $CI->db->query("select lm_name from lm_code"
            . " where dist_code='$d' and subdiv_code='$s' and cir_code='$c' "
            . " and mouza_pargona_code='$m' and lot_no='$l'")->row();
        return $relation;
    }

    public function EnabledMondalName($d, $s, $c, $m, $l)
    {
        $CI = &get_instance();
        $relation = $CI->db->query("SELECT * FROM lm_code as c JOIN loginuser_table as t ON c.dist_code=t.dist_code and "
            . "c.subdiv_code = t.subdiv_code and c.cir_code = t.cir_code and c.mouza_pargona_code=t.mouza_pargona_code and c.lot_no=t.lot_no "
            . "and c.lm_code = t.user_code and t.dist_code='$d' and "
            . "t.subdiv_code='$s' and t.cir_code='$c' and t.mouza_pargona_code = '$m' and t.lot_no = '$l' and t.dis_enb_option='E'")->row();
        return $relation;
    }

    function get_user_status($controllers, $v)
    {
        $CI = &get_instance();
        $controller_name = $controllers;
        $function_name = $v;
        $sql = "select user_desig_code from user_permission where controller_name = '$controller_name' and function_name = '$function_name'";
        $sql = $CI->db->query($sql)->result();
        //$result = $controllers."-".$v;
        return $sql;
    }

    function ByrightOf($d)
    {
        $CI = &get_instance();
        $sql = "select order_type from master_office_mut_type where order_type_code = '$d' ";
        $sql = $CI->db->query($sql)->row();
        //$result = $controllers."-".$v;
        return $sql;
    }

    function crop_category_code($n)
    {
        $CI = &get_instance();
        $sql = "select * from crop_category_code where crop_categ_code = '$n' ";
        $sql = $CI->db->query($sql)->row();
        return $sql;
    }

    function getMutationTypeObject($n)
    {
        $CI = &get_instance();
        $sql = "Select order_type from col8_order_type where order_type_code = '$n' ";
        $sql = $CI->db->query($sql)->row();
        return $sql;
    }

    function dagnumbr($d, $s, $c, $m, $l, $v, $p)
    {
        $CI = &get_instance();
        $sql = "Select dag_no from petition_dag_details where dist_code ='$d' and subdiv_code='$s' and cir_code='$c' "
            . "and mouza_pargona_code='$m' and lot_no='$l' and vill_townprt_code='$v' and petition_no='$p' ";
        $sql = $CI->db->query($sql)->row();
        return $sql;
    }

    function ordinal_suffix_of($i)
    {
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

    public function getsk_mapping($sk_code)
    {
        $CI = &get_instance();
        $CI->load->library('session');
        $dist_code = $CI->session->userdata('dist_code');
        $subdiv_code = $CI->session->userdata('subdiv_code');
        $cir_code = $CI->session->userdata('cir_code');
        $check_sk = $CI->db->query("Select count(*) as check_sk from loginuser_table where dist_code = '$dist_code' and subdiv_code = '$subdiv_code' and "
            . "cir_code = '$cir_code' and user_code = '$sk_code' and dis_enb_option = 'E'")->row()->check_sk;
        return $check_sk;
    }

    function allote_scheme_name($id)
    {
        $CI = &get_instance();
        $q = "SElect scheme_name_ass as name from allote_scheme_name where sid='$id'";
        $scname = $CI->db->query($q)->row()->name;
        return $scname;
    }

    function dcname($d, $u)
    {
        $CI = &get_instance();
        $q = "SElect username as name from users where dist_code='$d' and user_code='$u'";
        $dcname = $CI->db->query($q)->row()->name;
        return $dcname;
    }

    function caste_name($id)
    {
        $CI = &get_instance();
        $q = "SElect caste_name_ass as name from master_caste where caste_id='$id'";
        $scname = $CI->db->query($q)->row()->name;
        return $scname;
    }

    function gender($id)
    {
        $CI = &get_instance();
        $q = "SElect gen_name_ass as name from master_gender where id='$id'";
        $scname = $CI->db->query($q)->row()->name;
        return $scname;
    }

    function maxdag($d, $s, $c, $m, $l, $v)
    {
        $CI = &get_instance();
        $q = "Select max(dag_no_int) as new_dag from chitha_basic where dist_code='$d' and subdiv_code='$s' and cir_code='$c' and mouza_pargona_code='$m' and lot_no='$l' and vill_townprt_code='$v'";
        $new_dag = $CI->db->query($q)->row()->new_dag;
        $new_dag = $new_dag / 100;
        $new_dag = $new_dag + 1;
        return $new_dag;
    }

    function maxpatta($d, $s, $c, $m, $l, $v, $pp)
    {
        $CI = &get_instance();
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

    function getPartitionPattaType()
    {
        $CI = &get_instance();
        $sql = "Select type_code,patta_type from patta_code where mutation='a' ";
        $sql = $CI->db->query($sql)->result();
        return $sql;
    }

    function allDags($d, $s, $c, $m, $l, $v, $pno, $pc)
    {
        $CI = &get_instance();
        $query = "select * from chitha_basic where dist_code= '$d' and subdiv_code='$s' and "
            . " cir_code='$c' and lot_no='$l' and mouza_pargona_code='$m' and "
            . " vill_townprt_code='$v' and trim(patta_no)=trim('$pno') and patta_type_code='$pc'  ";
        $sql = $CI->db->query($query)->result();
        return $sql;
    }

    function getnameByPdarId($d, $s, $c, $m, $l, $v, $pno, $pc, $id)
    {
        $CI = &get_instance();
        $query = "select pdar_name as name from chitha_pattadar where dist_code= '$d' and subdiv_code='$s' and "
            . " cir_code='$c' and lot_no='$l' and mouza_pargona_code='$m' and "
            . " vill_townprt_code='$v' and trim(patta_no)=trim('$pno') and patta_type_code='$pc' and pdar_id='$id'  ";
        $sql = $CI->db->query($query)->row()->name;
        return $sql;
    }

    function appRelation($r)
    {
        $CI = &get_instance();
        $query = "select guard_rel_desc_as as name from master_guard_rel where guard_rel='$r' ";
        $sql = $CI->db->query($query)->row()->name;
        return $sql;
    }

    function cassnum($convnum)
    {
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

    function cassnumfordags($convnum)
    {
        $nm = TRIM($convnum);
        $value = explode(' ', $nm);
        //echo sizeof($value);
        if (sizeof($value) > 1) {
            $end = end(explode(' ', $nm));
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
        return $cr . " " . $end;
    }

    public function getMenuPermission($utility_type)
    {
        $CI = &get_instance();
        $menupermission = $CI->db->query("SELECT user_desig_code as user_desig_code FROM user_permission where function_name = '$utility_type' ")->row()->user_desig_code;
        return $menupermission;
    }

    public function getBacklogPermission($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $type)
    {
        $CI = &get_instance();

        $backlogperission = $CI->db->query("select status as status from backlog_request where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and "
            . "mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and request_for = '$type' and operation = 'P' ")->row();

        if (empty($backlogperission)) {
            $backlogperission = 'D';
        } else {
            $backlogperission = $backlogperission->status;
        }
        return $backlogperission;
    }

    public function getCountBacklogPermission($dist_code, $subdiv_code, $cir_code)
    {
        $CI = &get_instance();

        $backlogCountperission = $CI->db->query("select count(*) as count from backlog_request where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and "
            . "operation = 'P' and status = 'P' ")->row();
        return $backlogCountperission;
    }

    public function getCountBacklogMutation($dist_code, $subdiv_code, $cir_code)
    {
        $CI = &get_instance();

        $qF = "select count(*) as count from field_mut_basic where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and order_passed = 'B'";
        $qF = $CI->db->query($qF)->row()->count;

        $qP = "select count(*) as count from petition_basic where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mut_type = '03' and order_passed = 'B' and status = 'B'";
        $qP = $CI->db->query($qP)->row()->count;

        $backlogMutCount = $qF + $qP;
        return $backlogMutCount;
    }

    public function getCountBacklogPartition($dist_code, $subdiv_code, $cir_code)
    {
        $CI = &get_instance();

        $qF = "select count(*) as count from t_chitha_col8_order where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and (iscorrected_inco is null or iscorrected_inco='' ) and case_no like '%-BL' ";
        $qF = $CI->db->query($qF)->row()->count;

        $qP = "Select count(*) as count from t_chitha_rmk_ordbasic where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and (iscorrected_inco is null or iscorrected_inco='') and case_no like '%-BL' and case_no not like '%CONV-BL'  ";
        $qP = $CI->db->query($qP)->row()->count;

        $backlogPartCount = $qF + $qP;
        return $backlogPartCount;
    }

    public function getCountBacklogConversion($dist_code, $subdiv_code, $cir_code)
    {
        $CI = &get_instance();

        $qP = "select count(*) as count from petition_basic where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mut_type = '01' and order_passed = 'B' and status = 'B'";
        $qP = $CI->db->query($qP)->row()->count;

        $backlogMutCount = $qP;
        return $backlogMutCount;
    }

    public function getRequestFor($type)
    {
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

    function checkExistData()
    {
        $q = "SElect petition_no from petition_basic status='P' and dist_code=''  and subdiv_code='' and cir_code='' and mouza_pargona_code='' and 
			lot_no=''  and vill_townprt_code='' ";
        $sql = "SElect * from field_mut_basic where dist_code=''  and subdiv_code='' and cir_code='' and mouza_pargona_code='' and 
			lot_no=''  and vill_townprt_code='' and dag_no='' and patta_no='' and (petition_no in $q )";
    }


    function generateToken($claims, $time, $ttl, $algorithm, $secret)
    {
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

    function get_strike_out_status($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $patta_type_code, $patta_no, $pdar_id)
    {
        $CI = &get_instance();

        $chitha_pattadar = "select count(*) as count from chitha_dag_pattadar where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' "
            . "and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' and TRIM(patta_no)='$patta_no' "
            . "and patta_type_code='$patta_type_code' and pdar_id = '$pdar_id' and p_flag = '1' ";
        return $CI->db->query($chitha_pattadar)->row()->count;
    }

    public function checkUserAuthForCaseForDc($case_no)
    {
        $CI = &get_instance();
        $CI->load->library('session');
        $ses_dist = $CI->session->userdata['dist_code'];
        $ses_sub  = $CI->session->userdata['subdiv_code'];
        $ses_user = $CI->session->userdata['user_desig_code'];

        if ($ses_user != MB_DEPUTY_COMM) {
            $CI->session->set_flashdata('message', "Unauthorized access for case no # " . $case_no);
            redirect(base_url() . "index.php/home");
        }

        $sql = "SELECT * FROM settlement_basic WHERE case_no = '$case_no' AND dist_code = '$ses_dist'";
        $result = $CI->db->query($sql);

        if ($result->num_rows() == 0) {
            $CI->session->set_flashdata('message', "Unauthorized access for case no # " . $case_no);
            redirect(base_url() . "index.php/home");
        }
    }

    // check Auth user DC with Rollback
    public function checkUserAuthForCaseForDcWithRollback($case_no)
    {

        $CI = &get_instance();
        $CI->load->library('session');
        $ses_dist = $CI->session->userdata['dist_code'];
        $ses_sub  = $CI->session->userdata['subdiv_code'];
        $ses_user = $CI->session->userdata['user_desig_code'];

        if ($ses_user != MB_DEPUTY_COMM) {
            $this->db->trans_rollback();
            $CI->session->set_flashdata('message', "Unauthorized access for case no # " . $case_no);
            redirect(base_url() . "index.php/home");
        }

        $sql = "SELECT * FROM settlement_basic WHERE case_no = '$case_no' AND dist_code = '$ses_dist'";
        $result = $this->db->query($sql);

        if ($result->num_rows() == 0) {
            $this->db->trans_rollback();
            $CI->session->set_flashdata('message', "Unauthorized access for case no # " . $case_no);
            redirect(base_url() . "index.php/home");
        }
    }



    // check Auth user ADC
    public function checkUserAuthForCaseForAdc($case_no)
    {
        $CI = &get_instance();
        $CI->load->library('session');
        $ses_dist = $CI->session->userdata['dist_code'];
        $ses_sub  = $CI->session->userdata['subdiv_code'];
        $ses_user = $CI->session->userdata['user_desig_code'];

        if ($ses_user != MB_ADD_DEPUTY_COMM) {
            $CI->session->set_flashdata('message', "Unauthorized access for case no # " . $case_no);
            redirect(base_url() . "index.php/home");
        }

        $sql = "SELECT * FROM settlement_basic WHERE case_no = '$case_no' AND dist_code = '$ses_dist'";
        $result = $CI->db->query($sql);

        if ($result->num_rows() == 0) {
            $CI->session->set_flashdata('message', "Unauthorized access for case no # " . $case_no);
            redirect(base_url() . "index.php/home");
        }
    }

    // check Auth user ADC  with Rollback
    public function checkUserAuthForCaseForAdcWithRollback($case_no)
    {

        $CI = &get_instance();
        $CI->load->library('session');
        $ses_dist = $CI->session->userdata['dist_code'];
        $ses_sub  = $CI->session->userdata['subdiv_code'];
        $ses_user = $CI->session->userdata['user_desig_code'];

        if ($ses_user != MB_ADD_DEPUTY_COMM) {
            $CI->db->trans_rollback();
            $CI->session->set_flashdata('message', "Unauthorized access for case no # " . $case_no);
            redirect(base_url() . "index.php/home");
        }

        $sql = "SELECT * FROM settlement_basic WHERE case_no = '$case_no' AND dist_code = '$ses_dist'";
        $result = $CI->db->query($sql);

        if ($result->num_rows() == 0) {
            $CI->db->trans_rollback();
            $CI->session->set_flashdata('message', "Unauthorized access for case no # " . $case_no);
            redirect(base_url() . "index.php/home");
        }
    }



    // check Auth user SDO
    public function checkUserAuthForCaseForSdo($case_no)
    {
        $CI = &get_instance();
        $CI->load->library('session');
        $ses_dist = $CI->session->userdata['dist_code'];
        $ses_sub  = $CI->session->userdata['subdiv_code'];
        $ses_user = $CI->session->userdata['user_desig_code'];

        if ($ses_user != MB_SUB_DIV_COMM) {
            $this->db->trans_rollback();
            $CI->session->set_flashdata('message', "Unauthorized access for case no # " . $case_no);
            redirect(base_url() . "index.php/home");
        }

        $sql = "SELECT * FROM settlement_basic WHERE case_no = '$case_no' AND dist_code = '$ses_dist'  AND subdiv_code = '$ses_sub'";
        $result = $this->db->query($sql);

        if ($result->num_rows() == 0) {
            $this->db->trans_rollback();
            $CI->session->set_flashdata('message', "Unauthorized access for case no # " . $case_no);
            redirect(base_url() . "index.php/home");
        }
    }

    // check Auth user SDO with Rollback
    public function checkUserAuthForCaseForSdoWithRollback($case_no)
    {
        $CI = &get_instance();
        $CI->load->library('session');
        $ses_dist = $CI->session->userdata['dist_code'];
        $ses_sub  = $CI->session->userdata['subdiv_code'];
        $ses_user = $CI->session->userdata['user_desig_code'];

        if ($ses_user != MB_SUB_DIV_COMM) {
            $this->db->trans_rollback();
            $CI->session->set_flashdata('message', "Unauthorized access for case no # " . $case_no);
            redirect(base_url() . "index.php/home");
        }

        $sql = "SELECT * FROM settlement_basic WHERE case_no = '$case_no' AND dist_code = '$ses_dist'  AND subdiv_code = '$ses_sub'";
        $result = $this->db->query($sql);

        if ($result->num_rows() == 0) {
            $this->db->trans_rollback();
            $CI->session->set_flashdata('message', "Unauthorized access for case no # " . $case_no);
            redirect(base_url() . "index.php/home");
        }
    }

    // *** End code by Masud Reza







    // total ganda ....for Bengali version...13/6/18

    function Total_ganda($bigha, $katha, $lessa, $ganda)
    {
        $total_ganda = $ganda + ($lessa * 20) + ($katha * 320) + ($bigha * 6400);
        return $total_ganda;
    }




    //--------for Bengali version 13/6/18

    function get_Hec_Are_CAre2($bigha, $katha, $lessa, $ganda)
    {
        $total_ganda = $ganda + ($lessa * 20) + ($katha * 320) + ($bigha * 6400);
        // $totalAre = round((13.37804/6400)*$total_ganda,5);
        //  $total_lesa = ($bigha * 5 * 20) + ($katha * 20) + $lessa;
        $centiarr = (10000 / 747) * $total_ganda;
        $hectar = $centiarr / 10000;

        $wholeHector = floor($hectar);      // 1
        $fraction1 = $hectar - $wholeHector; // .25
        $arr = 100 * $fraction1;
        $wholeArr = floor($arr);

        $fraction2 = $arr - $wholeArr;
        $arr2 = $fraction2 * 100;
        $wholeCArr = round($arr2, 2);

        $hec_are_care = $wholeHector . "-" . $wholeArr . "-" . $wholeCArr;
        return $hec_are_care;
    }

    // ---------


    //-------- for Bengali version 13/6/18
    function Total_Bigha_Katha_Lessa2($total_ganda)
    {
        $mm = 0;
        if ($total_ganda < 0) {
            $mm = 1;
            $total_ganda = abs($total_ganda);
        }

        $bigha = $total_ganda / 6400;
        $rem_ganda = $total_ganda % 6400;
        $katha = $rem_ganda / 320;
        $rem_ganda2 = $rem_ganda % 320;
        $chatak = $rem_ganda2 / 20;
        $rem_ganda3 =  $rem_ganda2 % 20;


        $mesaure = array();
        $mesaure[] .= ($mm == 1) ? -floor($bigha) : floor($bigha);
        $mesaure[] .= ($mm == 1) ? -floor($katha) : floor($katha);
        $mesaure[] .= ($mm == 1) ? -floor($chatak) : floor($chatak);
        $mesaure[] .= ($mm == 1) ? - (number_format($rem_ganda3, 4)) : number_format($rem_ganda3, 4);

        return $mesaure;
    }
    //----------------


    public function mutType($transcode)
    {
        $CI = &get_instance();
        // $ds=$CI->session->userdata['dist_code'];
        // $this->dbswitch($ds);
        $data = $this->db->get_where("nature_trans_code", array('trans_type' => $transcode))->result_array();
        return $data;
    }






    function maxpattaAP($d, $s, $c, $m, $l, $v, $pp)
    {
        $CI = &get_instance();
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

    function getNocPriv($user, $d, $s, $c)
    {
        $CI = &get_instance();
        $q = "Select usnm as c from single_sign where user_code='$user' and dist_code='$d' and subdiv_code='$s' and cir_code='$c' ";
        $c = $CI->db->query($q)->row()->c;
        if ($c != NULL) {
            return $c;
        } else {
            return 0;
        }
    }

    public function dbswitch($dist_code)
    {
        $CI = &get_instance();
        if ($dist_code == "02") {
            $this->db = $CI->load->database('lsp3', TRUE);
        } else if ($dist_code == "05") {
            $this->db = $CI->load->database('lsp1', TRUE);
        } else if ($dist_code == "13") {
            $this->db = $CI->load->database('lsp2', TRUE);
        } else if ($dist_code == "17") {
            $this->db = $CI->load->database('lsp4', TRUE);
        } else if ($dist_code == "15") {
            $this->db = $CI->load->database('lsp5', TRUE);
        } else if ($dist_code == "14") {
            $this->db = $CI->load->database('lsp6', TRUE);
        } else if ($dist_code == "07") {
            $this->db = $CI->load->database('lsp7', TRUE);
        } else if ($dist_code == "03") {
            $this->db = $CI->load->database('lsp8', TRUE);
        } else if ($dist_code == "18") {
            $this->db = $CI->load->database('lsp9', TRUE);
        } else if ($dist_code == "12") {
            $this->db = $CI->load->database('lsp13', TRUE);
        } else if ($dist_code == "24") {
            $this->db = $CI->load->database('lsp10', TRUE);
        } else if ($dist_code == "06") {
            $this->db = $CI->load->database('lsp11', TRUE);
        } else if ($dist_code == "11") {
            $this->db = $CI->load->database('lsp12', TRUE);
        } else if ($dist_code == "12") {
            $this->db = $CI->load->database('lsp13', TRUE);
        } else if ($dist_code == "16") {
            $this->db = $CI->load->database('lsp14', TRUE);
        } else if ($dist_code == "32") {
            $this->db = $CI->load->database('lsp15', TRUE);
        } else if ($dist_code == "33") {
            $this->db = $CI->load->database('lsp16', TRUE);
        } else if ($dist_code == "34") {
            $this->db = $CI->load->database('lsp17', TRUE);
        } else if ($dist_code == "21") {
            $this->db = $CI->load->database('lsp18', TRUE);
        } else if ($dist_code == "08") {
            $this->db = $CI->load->database('lsp19', TRUE);
        } else if ($dist_code == "35") {
            $this->db = $CI->load->database('lsp20', TRUE);
        } else if ($dist_code == "36") {
            $this->db = $CI->load->database('lsp21', TRUE);
        } else if ($dist_code == "37") {
            $this->db = $CI->load->database('lsp22', TRUE);
        } else if ($dist_code == "25") {
            $this->db = $CI->load->database('lsp23', TRUE);
        } else if ($dist_code == "10") {
            $this->db = $CI->load->database('lsp24', TRUE);
        } else if ($dist_code == "38") {
            $this->db = $CI->load->database('lsp25', TRUE);
        } else if ($dist_code == "39") {
            $this->db = $CI->load->database('lsp26', TRUE);
        } else if ($dist_code == "22") {
            $this->db = $CI->load->database('lsp27', TRUE);
        } else if ($dist_code == "23") {
            $this->db = $CI->load->database('lsp28', TRUE);
        } else if ($dist_code == "01") {
            $this->db = $CI->load->database('lsp29', TRUE);
        }
    }
    ////////////////////
    function getPattaTypeNo($d, $s, $c, $m, $l, $v, $dag)
    {
        $CI = &get_instance();
        $this->dbswitch($d);
        $query = "select sum(dag_revenue+dag_local_tax) as sum,dag_revenue,dag_local_tax,dag_area_b,dag_area_k,dag_area_lc,dag_area_g,dag_area_kr,patta_no,patta_type_code,land_class_code from chitha_basic where dist_code= '$d' and subdiv_code='$s' and cir_code='$c' and lot_no='$l' and mouza_pargona_code='$m' and "
            . " vill_townprt_code='$v' and  trim(dag_no)=trim('$dag') group by dag_revenue,dag_local_tax,dag_area_b,dag_area_k,dag_area_lc,dag_area_g,dag_area_kr,patta_no,patta_type_code,land_class_code  ";
        $sql = $this->db->query($query)->row();
        return $sql;
    }
    function appRelationbyID($d, $r)
    {
        $CI = &get_instance();
        $this->dbswitch($d);
        $query = "select guard_rel_desc_as as name from master_guard_rel where id='$r' ";
        $sql = $this->db->query($query)->row()->name;
        return $sql;
    }
    function relationRevertBasu($d, $r)
    {
        $CI = &get_instance();
        $this->dbswitch($d);
        $query = "select guard_rel as name from master_guard_rel where id='$r' ";
        $sql = $this->db->query($query)->row()->name;
        return $sql;
    }
    function gnderRevertBasu($d, $r)
    {
        $CI = &get_instance();
        $this->dbswitch($d);
        $query = "select short_name as name from master_gender where id='$r' ";
        $sql = $this->db->query($query)->row()->name;
        return $sql;
    }

    function assToeng($number)
    {
        $bn = array("১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০");
        $en = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
        return str_replace($bn, $en, $number);
    }
    public function getGender($g)
    {
        $CI = &get_instance();
        $query = "Select gen_name_ass from master_gender where short_name='$g'";
        return $CI->db->query($query)->row()->gen_name_ass;
    }

    public function encryptData($src_str)
    {
        $ciphering = "AES-128-CTR";
        $encryption_iv = '1234567891011121';
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;

        $encryption = openssl_encrypt(
            $src_str,
            $ciphering,
            ENC_KEY,
            $options,
            $encryption_iv
        );

        return $encryption;
    }
    ////////////
    public function getAllMouzaDetails($d, $s, $c)
    {
        $query = "Select mouza_pargona_code,loc_name from location where 
            dist_code=? and subdiv_code=? and 
            cir_code=? and mouza_pargona_code!=? and 
            lot_no=? and vill_townprt_code=?";
        return $this->db->query($query, array($d, $s, $c, '00', '00', '00000'))->result();
    }
    //checking entries whether to show for update in land bank
    public function checkUpdateStatus($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code, $dag_no, $flag)
    {
        if ($flag == 2) {
            //update
            $status_arr = array(LAND_BANK_STATUS_APPROVED, LAND_BANK_STATUS_REVERT_BACK);
            $this->db->select("*")
                ->limit(1)
                ->order_by('id', "DESC")
                ->where('dist_code', $dist_code)->where('subdiv_code', $subdiv_code)
                ->where('cir_code', $circle_code)->where('mouza_pargona_code', $mouza_code)
                ->where('lot_no', $lot_no)
                ->where('vill_townprt_code', $vill_code)->where('dag_no', trim($dag_no))
                ->where_in('status', $status_arr)
                ->from('land_bank_details');
            $query = $this->db->get();
            //echo $this->db->last_query();
            //return count($query->result());
            if (count($query->result()) != 0) {
                return true;
            } else {
                return false;
            }
        } else {
            //add
            $this->db->select("*")
                ->limit(1)
                ->order_by('id', "DESC")
                ->where('dist_code', $dist_code)->where('subdiv_code', $subdiv_code)
                ->where('cir_code', $circle_code)->where('mouza_pargona_code', $mouza_code)
                ->where('lot_no', $lot_no)
                ->where('vill_townprt_code', $vill_code)->where('dag_no', trim($dag_no))
                ->from('land_bank_details');
            $query = $this->db->get();
            if ($query->num_rows() != 0) {
                $lb_details = $query->result();
                //echo json_encode($lb_details[0]);
                if ($lb_details[0]->status == LAND_BANK_STATUS_REVERT_BACK) {
                    $this->db->select("*")
                        ->limit(1)
                        ->order_by('id', "DESC")
                        ->where('dist_code', $dist_code)->where('subdiv_code', $subdiv_code)
                        ->where('cir_code', $circle_code)->where('mouza_pargona_code', $mouza_code)
                        ->where('lot_no', $lot_no)
                        ->where('vill_townprt_code', $vill_code)->where('dag_no', trim($dag_no))
                        ->from('c_land_bank_details');
                    $query = $this->db->get();
                    $c_lb_details = $query->result();
                    if (count($c_lb_details) == 0) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return true;
            }
        }
    }
    ///////////Zonal Value 17-06-22/////////////
    public function getVillageUUID($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code)
    {
        $CI = &get_instance();
        $this->dbswitch($dist_code);
        $villageCode = $this->db->query("select uuid AS village from location where dist_code ='$dist_code' and "
            . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='$mouza_code' and "
            . " vill_townprt_code='$vill_code' and lot_no='$lot_no'");

        return $villageCode->row()->village;
    }


    public function getVillageType($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code)
    {
        $CI = &get_instance();
        $this->dbswitch($dist_code);
        $villageType = $this->db->query("select rural_urban AS type from location where dist_code ='$dist_code' and "
            . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='$mouza_code' and "
            . " vill_townprt_code='$vill_code' and lot_no='$lot_no'");
        return $villageType->row()->type;
    }


    public function getZoneName($d)
    {
        $CI = &get_instance();
        $query = "Select zone_name from zonal_master where zone_code='$d'";
        return $CI->db->query($query)->row()->zone_name;
    }

    public function getSubclassName($d)
    {
        $CI = &get_instance();
        $query = "Select subclass_name from subclass_master where subclass_code='$d'";
        return $CI->db->query($query)->row()->subclass_name;
    }

    ////////////////////////////////////////
    //session userdata validation method
    //use "00" where field is not required
    public function validateSessionUserData($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no)
    {
        //return $_SESSION['credentials']['mouza_pargona_code'];
        if (
            $dist_code != $_SESSION['credentials']['dist_code']
            ||  $subdiv_code != $_SESSION['credentials']['subdiv_code']
            ||  $circle_code != $_SESSION['credentials']['cir_code']
            ||  $mouza_code != $_SESSION['credentials']['mouza_pargona_code']
            ||  $lot_no != $_SESSION['credentials']['lot_no']
        ) {
            return false;
        } else {
            return true;
        }
    }
    public function getVillageNameByruralUrban($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code)
    {
        $CI = &get_instance();
        $this->dbswitch($dist_code);
        //$ds=$CI->session->userdata['db'];
        $q = "select loc_name AS village,rural_urban from location where dist_code ='$dist_code'  and "
            . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='$mouza_code' and "
            . " vill_townprt_code='$vill_code' and lot_no='$lot_no'";

        $village = $this->db->query("select loc_name AS village,rural_urban from location where dist_code ='$dist_code'  and "
            . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='$mouza_code' and "
            . " vill_townprt_code='$vill_code' and lot_no='$lot_no'");

        return $village->row();
    }
    public function getEncroacherDetails($id)
    {
        $CI = &get_instance();
        $sql = "select * from c_land_bank_encroacher_details where id='$id'";
        $result  = $CI->db->query($sql);
        // var_dump($result->num_rows());
        if ($result->num_rows() > 0)
            $result = $result->row()->fathers_name;
        else
            $result = null;
        // if ($result == true)
        // return $result->row()->fathers_name;
        // else
        // $result = null;
        //return $result->row()->fathers_name;
        return $result;
    }

    // -js- 26-09-2022
    public function get_relation_id($relation)
    {
        $CI = &get_instance();
        $ds = $CI->session->userdata['dist_code'];
        $this->dbswitch($ds);
        $relation = strtoLower($relation);
        $query = "select guard_rel_desc_as from master_guard_rel where id = '$relation'";

        $relation = $this->db->query($query);
        $row = $relation->num_rows;
        if ($row != 0) {
            return $relation->row()->guard_rel_desc_as;
        }

        return "unkown";
    }

    function appRelationbyIDMB2($r)
    {
        $CI = &get_instance();
        $d = $CI->session->userdata['dist_code'];
        $this->dbswitch($d);
        $query = "select guard_rel_desc as name from master_guard_rel where id='$r' ";
        $sql = $this->db->query($query)->row()->name;
        return $sql;
    }
    function RelationbyIDMB2($r)
    {
        $CI = &get_instance();
        $d = $CI->session->userdata['dist_code'];
        $this->dbswitch($d);
        $query = "select guard_rel_desc as name from master_guard_rel where id='$r' ";
        $sql = $this->db->query($query)->row()->name;
        return $sql;
    }


    public function checkUserAuthForCaseForLm($dist, $s, $c, $m, $l)
    {
        $CI = &get_instance();
        $CI->load->library('session');
        $d = $CI->session->userdata['dist_code'];
        $this->dbswitch($d);
        $session_dist_code = $CI->session->userdata('dist_code');
        $session_subdiv_code = $CI->session->userdata('subdiv_code');
        $session_cir_code = $CI->session->userdata('cir_code');
        $session_mouza_pargona_code = $CI->session->userdata('mouza_pargona_code');
        $session_lot_no = $CI->session->userdata('lot_no');

        if (($session_dist_code == $dist) && ($session_subdiv_code == $s) && ($session_cir_code == $c) && ($session_mouza_pargona_code == $m) && ($session_lot_no == $l)) {
            return true;
        } else {
            return false;
        }
    }

    public function checkUserAuthForCaseForCo($case_no)
    {
        $CI = &get_instance();
        $CI->load->library('session');
        $d = $CI->session->userdata['dist_code'];
        $this->dbswitch($d);
        $session_dist_code = $CI->session->userdata('dist_code');
        $session_subdiv_code = $CI->session->userdata('subdiv_code');
        $session_cir_code = $CI->session->userdata('cir_code');
        $session_user_code = $CI->session->userdata('user_code');


        $session_mouza_pargona_code = $CI->session->userdata('mouza_pargona_code');
        $session_lot_no = $CI->session->userdata('lot_no');

        if (($session_mouza_pargona_code != '00') && ($session_lot_no != '00')) {
            $CI->session->set_flashdata('message', "#ERRCO103303 :Unauthorized access: You might be using multiple login. Kindly logout and login again!! case no # " . $case_no);
            log_message('error', '#ERRCO103303: Falied to forward to CO');
            redirect(base_url() . "index.php/home");
        }


        // $sql = "SELECT * FROM settlement_basic WHERE case_no = '$case_no' AND dist_code = '$session_dist_code' AND subdiv_code = '$session_subdiv_code' AND cir_code = '$session_cir_code' AND co_code = '$session_user_code'";
        $sql = "SELECT * FROM settlement_basic WHERE case_no = '$case_no' AND dist_code = '$session_dist_code' AND subdiv_code = '$session_subdiv_code' AND cir_code = '$session_cir_code'";

        $result = $this->db->query($sql);

        if ($result->num_rows() == 0) {
            $CI->session->set_flashdata('message', "#ERRCO403303 :Unauthorized access for case no # " . $case_no);
            log_message('error', '#ERRCO403303: Falied to forward to CO ' . $this->db->last_query());
            redirect(base_url() . "index.php/home");
        }
    }

    public function checkUserAuthForCaseForSk($case_no)
    {
        $CI = &get_instance();
        $CI->load->library('session');
        $d = $CI->session->userdata['dist_code'];
        $this->dbswitch($d);
        $session_dist_code = $CI->session->userdata('dist_code');
        $session_subdiv_code = $CI->session->userdata('subdiv_code');
        $session_cir_code = $CI->session->userdata('cir_code');
        $session_user_code = $CI->session->userdata('user_code');


        $session_mouza_pargona_code = $CI->session->userdata('mouza_pargona_code');
        $session_lot_no = $CI->session->userdata('lot_no');

        if (($session_mouza_pargona_code != '00') && ($session_lot_no != '00')) {
            $CI->session->set_flashdata('message', "#ERRCO7503303 : Unauthorized access: You might be using multiple login. Kindly logout and login again!! case no # " . $case_no);
            redirect(base_url() . "index.php/home");
        }


        // $sql = "SELECT * FROM settlement_basic WHERE case_no = '$case_no' AND dist_code = '$session_dist_code' AND subdiv_code = '$session_subdiv_code' AND cir_code = '$session_cir_code' AND sk_code = '$session_user_code'";
        $sql = "SELECT * FROM settlement_basic WHERE case_no = '$case_no' AND dist_code = '$session_dist_code' AND subdiv_code = '$session_subdiv_code' AND cir_code = '$session_cir_code' AND sk_code like '%SK%'";

        $result = $this->db->query($sql);

        if ($result->num_rows() == 0) {
            $CI->session->set_flashdata('message', "#ERRCO503303 : Unauthorized access for case no # " . $case_no);
            log_message('error', '#ERRCO503303: Falied to forward to CO ' . $this->db->last_query());
            redirect(base_url() . "index.php/home");
        }
    }



    public function getApplidFromCaseNo($case_no)
    {

        $CI = &get_instance();
        $d = $CI->session->userdata['dist_code'];
        $this->dbswitch($d);
        $applid = $this->db->query("select applid from settlement_basic where case_no ='$case_no'");
        return $applid->row()->applid;
    }
    ///////////////////
    function classCodeFromChitha($d, $s, $c, $m, $l, $v, $dag)
    {
        $CI = &get_instance();
        $newpatta = 0;
        $q = "Select land_class_code from chitha_basic where dist_code=? and subdiv_code=? and cir_code=? and mouza_pargona_code=? and lot_no=? and vill_townprt_code=? and dag_no=? ";
        $result = $CI->db->query($q, array($d, $s, $c, $m, $l, $v, $dag));
        if ($result->num_rows() > 0) {
            return $result->row()->land_class_code;
        } else {
            return null;
        }
    }
    function getRevenuePerBigha($d, $s, $c, $code)
    {
        $CI = &get_instance();
        $sql1 = "Select dag_revenue_perbigha,dag_local_tax_min from revenue_land_class_wise where dist_code=? and subdiv_code=? and cir_code=? and class_code=? order by year_no desc, dag_revenue_perbigha desc";
        $result = $CI->db->query($sql1, array($d, $s, $c, $code));
        if ($result->num_rows() > 0) {
            return $result->row()->dag_revenue_perbigha;
        } else {
            return null;
        }
    }

    function getServiceCode($case_no)
    {
        $CI = &get_instance();
        $sql = $CI->db->query("SELECT service_code from settlement_basic where case_no = '$case_no'");

        if ($sql->num_rows > 0) {
            return $service_code = $sql->row()->service_code;
        } else {
            return false;
        }
    }
    function relationByID($id)
    {
        $CI = &get_instance();
        $query = "select guard_rel as name from master_guard_rel where id='$id' ";
        $sql = $this->db->query($query)->row()->name;
        return $sql;
    }

    function getrelationByID($id)
    {
        $CI = &get_instance();
        $query = "select guard_rel_desc_as as name from master_guard_rel where id='$id' ";
        $sql = $this->db->query($query)->row()->name;
        return $sql;
    }
    //function created for displaying the village name by uuid-------19122022
    public function getVillageNameByUUID($uuid)
    {
        $CI = &get_instance();
        // $this->dbswitch($dist_code);
        //$ds=$CI->session->userdata['db'];
        $village = $CI->db->query("select loc_name AS village from location where uuid = ? ", array($uuid));
        return $village->row()->village;
    }
    function createTokenJwt()
    {
        $timestamp = date("Y-m-d H:i:s");
        $CI = &get_instance();
        $CI->output->set_header("Access-Control-Allow-Origin:*");
        $jwt = new JWT();
        $key = SECRET_KEY;
        $payload = array(
            "timestamp" => $timestamp
        );
        $token = $jwt->encode($payload, $key, 'HS256');
        return $token;
    }
    public function getZonalValue($dist_code, $uuid, $dag_no)
    {
        $CI = &get_instance();
        //$ds=$CI->session->userdata['db'];
        $q = "select unique_village_code,dag_no,zone_id,subclass_id from dagwise_zone_info where flag='1' and dist_code='$dist_code' and unique_village_code='$uuid' and dag_no='" . trim($dag_no) . "'";
        $zonaldata = $CI->db->query($q)->num_rows();

        if ($zonaldata > 0) {
            $zonaldata = $CI->db->query($q)->row();
            $zonalrate = $CI->db->query("select land_rate from villagewise_zone_info where flag='1' and unique_village_code='$zonaldata->unique_village_code' and
            zone_code='$zonaldata->zone_id' and subclass_code='$zonaldata->subclass_id'");
            return $zonalrate->row()->land_rate;
        } else {
            return null;
        }
    }


    public function getEnglishMouzaName($dist_code, $subdiv_code, $circle_code, $mouza_code)
    {
        $CI = &get_instance();
        $this->dbswitch($dist_code);
        //$ds=$CI->session->userdata['db'];
        $mouza = $this->db->query("select locname_eng AS mouza from location where dist_code ='$dist_code'  and "
            . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='$mouza_code' and "
            . " vill_townprt_code='00000' and lot_no='00'");
        return $mouza->row()->mouza;
    }

    public function getEnglishVillageName($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code)
    {
        $CI = &get_instance();
        $this->dbswitch($dist_code);
        //$ds=$CI->session->userdata['db'];
        $q = "select loc_name AS village from location where dist_code ='$dist_code'  and "
            . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='$mouza_code' and "
            . " vill_townprt_code='$vill_code' and lot_no='$lot_no'";

        $village = $this->db->query("select locname_eng AS village from location where dist_code ='$dist_code'  and "
            . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='$mouza_code' and "
            . " vill_townprt_code='$vill_code' and lot_no='$lot_no'");

        return $village->row()->village;
    }
    public function getEnglishCircleName($dist_code, $subdiv_code, $circle_code)
    {
        $CI = &get_instance();
        //$ds=$CI->session->userdata['db'];
        $this->dbswitch($dist_code);
        $circle = $this->db->query("select locname_eng AS circle from location where dist_code ='$dist_code'  and "
            . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='00' and "
            . " vill_townprt_code='00000' and lot_no='00'");

        return $circle->row()->circle;
    }

    public function checkIfAlreadyUpdatedByLm($case_no)
    {
        $CI = &get_instance();
        $sql = $CI->db->query("SELECT pending_officer FROM settlement_basic WHERE case_no = '$case_no'");
        if ($sql->num_rows() > 0) {
            if ($sql->row()->pending_officer == 'LM') {
                return 'y';
            } else {
                return 'n';
            }
        } else {
            return false;
        }
    }


    public function getNomineeOfSdlacMember($ucode, $dist)
    {

        $CI = &get_instance();
        $sql = $CI->db->query("SELECT * FROM sdlac_nominee_list       
                              WHERE sdlac_user_code=? AND district=?", array($ucode, $dist));
        if ($sql->num_rows() > 0) {
            return $sql->result();
        } else {
            return false;
        }
    }


    public function getSelectedNomineeOfSdlac($pno, $nom_id, $s_code)
    {

        $CI = &get_instance();
        $sql = $CI->db->query(
            "SELECT * FROM sdlac_nominee_proposal_list       
                              WHERE proposal_no=? AND nominee_id=? AND service_code=?",
            array($pno, $nom_id, $s_code)
        );
        if ($sql->num_rows() > 0) {
            return 'selected';
        } else {
            return false;
        }
    }

    public function getUserNameByUserCode($ucode)
    {

        $CI = &get_instance();
        $sql = $CI->db->query("SELECT use_name FROM loginuser_table WHERE user_code=?", array($ucode));
        if ($sql->num_rows() > 0) {
            return $sql->row()->use_name;
        } else {
            return false;
        }
    }

    public function getEmailIdByUserCode($ucode)
    {

        $CI = &get_instance();
        $sql = $CI->db->query("SELECT emailid FROM users WHERE user_code=?", array($ucode));
        if ($sql->num_rows() > 0) {
            return $sql->row()->emailid;
        } else {
            return false;
        }
    }

    public function curlPost($url, $arrayData)
    {
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $url);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST,  2);
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, http_build_query($arrayData));
        $result = curl_exec($curl_handle);
        $httpcode = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);
        curl_close($curl_handle);
        if ($httpcode != 200 || $result == null) {
            return false;
        } else {
            return $result;
        }
    }




    // get all dag no
    public function getAllDagDetailsWithCaseNo($caseNo)
    {
        $CI = &get_instance();
        $sql = $CI->db->query("SELECT dag_no,total_lessa FROM settlement_premium       
                              WHERE case_no=? AND is_final=?", array($caseNo, 1));
        if ($sql->num_rows() > 0) {
            return $sql->result();
        } else {
            return false;
        }
    }


    //get service name by service code
    public function getServiceName($scode)
    {
        if ($scode == SETTLEMENT_TENANT_ID) {
            return 'Occupancy Tenant';
        }
        if ($scode == SETTLEMENT_AP_TRANSFER_ID) {
            return "AP";
        }
        if ($scode == SETTLEMENT_TRIBAL_COMMUNITY_ID) {
            return "Tribal Community";
        }
        if ($scode == SETTLEMENT_KHAS_LAND_ID) {
            return "Khas Land";
        }
        if ($scode == SETTLEMENT_PGR_VGR_LAND_ID) {
            return "PGR VGR";
        }
        if ($scode == SETTLEMENT_SPECIAL_CULTIVATORS_ID) {
            return "Special Cultivators";
        }
    }


    public function getNameOfUserByUserCode($ucode)
    {

        $CI = &get_instance();
        $sql = $CI->db->query("SELECT username FROM users WHERE user_code=?", array($ucode));
        if ($sql->num_rows() > 0) {
            return $sql->row()->username;
        } else {
            return false;
        }
    }

    public function getNomineeNameOfSdlacUserByUserCode($ucode)
    {

        $CI = &get_instance();
        $sql = $CI->db->query("SELECT nominee_name FROM sdlac_nominee_list WHERE sdlac_user_code=?", array($ucode));
        if ($sql->num_rows() > 0) {
            return $sql->row()->nominee_name;
        } else {
            return false;
        }
    }

    public function getNomineeNameOfNomineeId($numId)
    {

        $CI = &get_instance();
        $sql = $CI->db->query("SELECT nominee_name FROM sdlac_nominee_list WHERE id=?", array($numId));
        if ($sql->num_rows() > 0) {
            return $sql->row()->nominee_name;
        } else {
            return false;
        }
    }




    public function meetingNameById($meetingId)
    {

        $CI = &get_instance();
        $sql = $CI->db->query("SELECT meeting_name FROM proposal_meeting_list WHERE id=?", array($meetingId));
        if ($sql->num_rows() > 0) {
            return $sql->row()->meeting_name;
        } else {
            return false;
        }
    }

    //get designation by usercode
    public function getDesignationNameByUserCode($usercode)
    {
        $CI = &get_instance();
        $sql = $CI->db->query("select A.user_desig_code, B.user_desig from users A
                    join master_user_designation B on A.user_desig_code=B.user_desig_code
                    where A.user_code=?", array($usercode));
        if ($sql->num_rows() > 0) {
            return $sql->row();
        } else {
            return false;
        }
    }

    public function getLocationFromUUID($uuid)
    {
        $CI = &get_instance();
        $sql = $CI->db->query("SELECT l.dist_code, l.subdiv_code, l.cir_code, l.mouza_pargona_code, l.lot_no, l.vill_townprt_code,
        (SELECT loc_name AS dist_name FROM location t WHERE t.dist_code= l.dist_code AND t.subdiv_code = '00'),
        (SELECT loc_name AS subdiv_name FROM location t WHERE t.dist_code= l.dist_code AND t.subdiv_code = l.subdiv_code AND t.cir_code = '00'),
        (SELECT loc_name AS cir_name FROM location t WHERE t.dist_code= l.dist_code AND t.subdiv_code = l.subdiv_code AND t.cir_code = l.cir_code AND t.mouza_pargona_code = '00'),
        (SELECT loc_name AS mouza_name FROM location t WHERE t.dist_code= l.dist_code AND t.subdiv_code = l.subdiv_code AND t.cir_code = l.cir_code AND t.mouza_pargona_code = l.mouza_pargona_code AND t.lot_no = '00'),
        (SELECT loc_name AS lot_name FROM location t WHERE t.dist_code= l.dist_code AND t.subdiv_code = l.subdiv_code AND t.cir_code = l.cir_code AND t.mouza_pargona_code = l.mouza_pargona_code AND t.lot_no = l.lot_no AND t.vill_townprt_code = '00000'),
        (SELECT loc_name AS village_name FROM location t WHERE t.dist_code= l.dist_code AND t.subdiv_code = l.subdiv_code AND t.cir_code = l.cir_code AND t.mouza_pargona_code = l.mouza_pargona_code AND t.lot_no = l.lot_no AND t.vill_townprt_code = l.vill_townprt_code) 
        FROM location l WHERE uuid = ?", array($uuid));

        if ($sql->num_rows() > 0) {
            return $sql->row();
        } else {
            return false;
        }
    }



    
}
