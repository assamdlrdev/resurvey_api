<?php

class UtilsModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');

    }


    // ********** code by Masud Reza


    function defaultValue($input, $value)
    {
        if (empty($input)) return $value;

        return $input;
    }


    // to get Area Details (Assamese B + K + L)
    public function getAreaDetails($district, $subdiv, $circle, $mouza, $lot, $village, $dag)
    {
        $area = $this->db->query("select dag_area_b,dag_area_k,dag_area_lc,dag_area_g,dag_area_kr,patta_no, 
        patta_type_code from chitha_basic where dist_code=? and cir_code=? and 
        subdiv_code=? and vill_townprt_code=? and mouza_pargona_code=? 
        and lot_no=? and dag_no=?", array($district,$circle,$subdiv,$village,$mouza,$lot,$dag));
        $object = $area->row();

        $totalArea = 0;

        if(in_array($district, BARAK_VALLEY))
        {
            $bigha = $this->defaultValue(trim($object->dag_area_b),0);
            $katha = $this->defaultValue(trim($object->dag_area_k),0);
            $lessa = $this->defaultValue(trim($object->dag_area_lc),0);
            $ganda = $this->defaultValue(trim($object->dag_area_g),0);
            $totalArea = ($bigha * 6400) + ($katha * 320)  + ($lessa * 20) + $ganda;
            return $totalArea;
        }
        else
        {
            $bigha = $this->defaultValue(trim($object->dag_area_b),0);
            $katha = $this->defaultValue(trim($object->dag_area_k),0);
            $lessa = $this->defaultValue(trim($object->dag_area_lc),0);
            $totalArea = ($bigha * 100) + ($katha * 20)  + $lessa;
            return $totalArea;

        }

    }



    // get dist name
    public function getDistrictNameByDistCode($distCode)
    {
        return $this->db->select('loc_name')
            ->where('dist_code',$distCode)
            ->where('subdiv_code','00')
            ->get('location')
            ->row();

    }
    // get dist name english
    public function getEngDistrictNameByDistCode($distCode)
    {
        return $this->db->select('locname_eng')
            ->where('dist_code',$distCode)
            ->where('subdiv_code','00')
            ->get('location')
            ->row();

    }

    // get subdiv name english
    public function getEngSubdivNameByDistCode($distCode, $subdiv_code)
    {
        return $this->db->select('locname_eng')
            ->where('dist_code',$distCode)
            ->where('subdiv_code',$subdiv_code)
            ->where('cir_code', '00')
            ->where('mouza_pargona_code', '00')
            ->where('vill_townprt_code', '00000')
            ->where('lot_no', '00')
            ->get('location')
            ->row();

    }

    // get subdivision details
    public function getSubDivisionDetailsByDist($distCode,$diviCode)
    {
        return $this->db->select('loc_name')
            ->where('dist_code',$distCode)
            ->where('subdiv_code',$diviCode)
            ->where('cir_code','00')
            ->get('location')
            ->row();
    }


    // get circle details
    public function getCircleDetailsByDistDivision($distCode,$diviCode,$circleCode)
    {
        return $this->db->select('loc_name')
            ->where('dist_code',$distCode)
            ->where('subdiv_code',$diviCode)
            ->where('cir_code',$circleCode)
            ->where('mouza_pargona_code','00')
            ->get('location')
            ->row();
    }


    // get mouza details
    public function getMouzaDetailsByDistDivisionCircle($distCode,$diviCode,$circleCode,$mouzaCode)
    {
        return $this->db->select('loc_name')
            ->where('dist_code',$distCode)
            ->where('subdiv_code',$diviCode)
            ->where('cir_code',$circleCode)
            ->where('mouza_pargona_code',$mouzaCode)
            ->where('lot_no','00')
            ->get('location')
            ->row();
    }


    // get lot details
    public function getLotDetailsNameByDistDivisionCircleMouza($distCode,$diviCode,$circleCode,$mouzaCode,$lotCode)
    {
        return $this->db->select('loc_name')
            ->where('dist_code',$distCode)
            ->where('subdiv_code',$diviCode)
            ->where('cir_code',$circleCode)
            ->where('mouza_pargona_code',$mouzaCode)
            ->where('lot_no',$lotCode)
            ->where('vill_townprt_code','00000')
            ->get('location')
            ->row();
    }


    // get village details
    public function getVillageDetailsNameByDistDivisionCircleMouzaLot($distCode,$diviCode,$circleCode,$mouzaCode,$lotCode,$vilCode)
    {
        return $this->db->select('loc_name')
            ->where('dist_code',$distCode)
            ->where('subdiv_code',$diviCode)
            ->where('cir_code',$circleCode)
            ->where('mouza_pargona_code',$mouzaCode)
            ->where('lot_no',$lotCode)
            ->where('vill_townprt_code',$vilCode)
            ->get('location')
            ->row();
    }


    // get user name dc
    public function getUserNameDc($distCode)
    {
        $this->db->select('users.username');
        $this->db->from('users');
        $this->db->join('loginuser_table','loginuser_table.user_code = users.user_code');
        $this->db->where('users.dist_code',$distCode );
        $this->db->where('loginuser_table.dist_code',$distCode );
        $this->db->where('users.user_desig_code',MB_DEPUTY_COMM );
        $this->db->where('loginuser_table.dis_enb_option','E' );
        $this->db->order_by('loginuser_table.date_of_creation', 'desc');
        $data = $this->db->get()->row();
        return $data;
    }



    function downloadExcelReport($filename, $result_array)
    {
        require_once 'application/libraries/Xlsxwriter.class.php';
        ini_set('display_errors', 1);
        ini_set('log_errors', 1);
        // var_dump($result_array[0]);
        //$head_array[] = array_keys($result_array[0]);
        foreach($result_array[0] as $key=>$head)
        {
            $final_head[$key]='string';
        }
        $styles1 = array( 'font'=>'Arial','font-size'=>14,'font-style'=>'bold', 'fill'=>'#FFFF00',
                          'halign'=>'center', 'border'=>'left,right,top,bottom');
        $styles7 = array( 'border'=>'left,right,top,bottom');
        header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        //header("Content-Type: application/vnd.ms-excel");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        ob_clean();
        $writer = new XLSXWriter();
        $writer->setAuthor('Dharitree');
        $writer->writeSheetHeader('Sheet1', $final_head,$styles1);
        foreach($result_array as $row)
            $writer->writeSheetRow('Sheet1', (array)$row,$styles7);
        ob_end_clean();
        $writer->writeToStdOut();
        exit(0);
    }
    public function cleanPattern($input) {
        return json_decode(preg_replace(PATTERN, '0', json_encode($input)));
      }

}
