<?php
class JamabandiModel extends CI_Model
{
 // for Jamabandi By Masud Reza 23/05/2022


    // add jamabandi
    public function insertJamabandiDetails($dataSave)
    {
        $this->db->trans_start();
        $this->db->insert('jama_dag', $dataSave);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }





    // chitha dag basic
    public function chithaBasicDetailJamabandi
    ($dist,$subdiv,$circle,$mouza,$lot,$village,$pattaType,$pattaNum)
    {

        $this->db->select('chitha_basic.*');
        $this->db->where('chitha_basic.patta_type_code',$pattaType);
        $this->db->where('chitha_basic.patta_no',$pattaNum);
        $this->db->where_in('chitha_basic.jama_yn',['n','N']);
        $this->db->where('chitha_basic.dist_code',$dist);
        $this->db->where('chitha_basic.subdiv_code',$subdiv);
        $this->db->where('chitha_basic.cir_code',$circle);
        $this->db->where('chitha_basic.mouza_pargona_code',$mouza);
        $this->db->where('chitha_basic.lot_no',$lot);
        $this->db->where('chitha_basic.vill_townprt_code',$village);
        $this->db->from('chitha_basic');
        $cDetails = $this->db->get();

        return $cDetails->result();
    }



    // chitha dag pattadar
    public function chithaDagPattadarDetailJamabandi
    ($dist,$subdiv,$circle,$mouza,$lot,$village,$pattaType,$pattaNum)
    {

        $this->db->select('chitha_dag_pattadar.*');
        $this->db->where('chitha_dag_pattadar.dist_code',$dist);
        $this->db->where('chitha_dag_pattadar.subdiv_code',$subdiv);
        $this->db->where('chitha_dag_pattadar.cir_code',$circle);
        $this->db->where('chitha_dag_pattadar.mouza_pargona_code',$mouza);
        $this->db->where('chitha_dag_pattadar.lot_no',$lot);
        $this->db->where('chitha_dag_pattadar.vill_townprt_code',$village);
        $this->db->where('chitha_dag_pattadar.patta_type_code',$pattaType);
        $this->db->where('chitha_dag_pattadar.patta_no',$pattaNum);
        $this->db->from('chitha_dag_pattadar');
        $pDetails = $this->db->get();

        return $pDetails->result();
    }



    // chitha pattadar
    public function chithaPattadarDetailJamabandi
    ($dist,$subdiv,$circle,$mouza,$lot,$village,$pattaType,$pattaNum)
    {

        $this->db->select('chitha_pattadar.*');
        $this->db->where('chitha_pattadar.dist_code',$dist);
        $this->db->where('chitha_pattadar.subdiv_code',$subdiv);
        $this->db->where('chitha_pattadar.cir_code',$circle);
        $this->db->where('chitha_pattadar.mouza_pargona_code',$mouza);
        $this->db->where('chitha_pattadar.lot_no',$lot);
        $this->db->where('chitha_pattadar.vill_townprt_code',$village);
        $this->db->where('chitha_pattadar.patta_type_code',$pattaType);
        $this->db->where('chitha_pattadar.patta_no',$pattaNum);
        $this->db->from('chitha_pattadar');
        $pDetails = $this->db->get();

        return $pDetails->result();
    }


    // get jamabandi with location details
    public function getAllJamabandiDetailsByLocation($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code)
    {
        $this->db->select('patta_no');
        $this->db->where('dist_code',$dist_code);
        $this->db->where('subdiv_code',$subdiv_code);
        $this->db->where('cir_code',$circle_code);
        $this->db->where('mouza_pargona_code',$mouza_code);
        $this->db->where('lot_no',$lot_no);
        $this->db->where('vill_townprt_code',$vill_code);
        $this->db->from('jama_dag');
        $pDetails = $this->db->get();

        return $pDetails->result();
    }


    // get all patta type
    public function getPattaType()
    {
        $this->db->select('patta_code.type_code,patta_code.patta_type,');
        $this->db->from('patta_code');
        $pDetails = $this->db->get();
        return $pDetails->result();
    }

    public function getPattaTypeNameDetails($pattaType)
    {
        $this->db->select('patta_code.patta_type,');
        $this->db->from('patta_code');
        $this->db->where('patta_code.type_code',$pattaType);
        $pDetails = $this->db->get()->row();
        return $pDetails;
    }






    public function getpattatypeNameforJamabandi($pattatypecode)
    {
        $village = $this->db->query("Select patta_type from   patta_code where Type_code='$pattatypecode'");
        return $village->result();
    }



    // district name
    public function getDistrictName($dist_code) {
        $district = $this->db->query("select loc_name AS district from   location where dist_code ='$dist_code'  and "
            . " subdiv_code='00' and cir_code='00' and mouza_pargona_code='00' and "
            . " vill_townprt_code='00000' and lot_no='00'");
        return $district->result();
    }

    // subdivision name
    public function getSubDivName($dist_code, $subdiv_code) {
        $subdiv = $this->db->query("select loc_name AS subdiv from   location where dist_code ='$dist_code'  and "
            . " subdiv_code='$subdiv_code' and cir_code='00' and mouza_pargona_code='00' and "
            . " vill_townprt_code='00000' and lot_no='00'");
        return $subdiv->result();
    }

    //circle name
    public function getCircleName($dist_code, $subdiv_code, $circle_code) {
        $circle = $this->db->query("select loc_name AS circle from   location where dist_code ='$dist_code'  and "
            . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='00' and "
            . " vill_townprt_code='00000' and lot_no='00'");
        return $circle->result();
    }

    // mouza name
    public function getMouzaName($dist_code, $subdiv_code, $circle_code, $mouza_code) {
        $mouza = $this->db->query("select loc_name AS mouza from   location where dist_code ='$dist_code'  and "
            . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='$mouza_code' and "
            . " vill_townprt_code='00000' and lot_no='00'");
        return $mouza->result();
    }

    // lot No
    public function getLotName($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no) {
        $lot = $this->db->query("select loc_name as lot_no from   location where dist_code ='$dist_code'  and "
            . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='$mouza_code' and "
            . " vill_townprt_code='00000' and lot_no='$lot_no'");
        return $lot->result();
    }

    //village name
    public function getVillageName($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code) {
        $village = $this->db->query("select loc_name AS village from   location where dist_code ='$dist_code'  and "
            . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='$mouza_code' and "
            . " vill_townprt_code='$vill_code' and lot_no='$lot_no'");
        return $village->result();
    }







}