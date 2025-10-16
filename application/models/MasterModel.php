<?php
class MasterModel extends CI_Model
{
    public function getAllGenderList()
    {
        $sql = "Select * from master_gender";
        return $this->db->query($sql)->result();
    }
    public function getAllCasteList()
    {
        $sql = "Select * from master_caste";
        return $this->db->query($sql)->result();
    }
    public function getVillageUuid($dist_code, $subdiv_code, $cir_code, $mouza_code, $lot_no, $vill_code)
    {
        $sql = "select uuid from location where dist_code = '$dist_code' and subdiv_code = '$subdiv_code' and cir_code = '$cir_code' 
                and mouza_pargona_code = '$mouza_code' and lot_no = '$lot_no' and vill_townprt_code  = '$vill_code'";
        $query = $this->db->query($sql);
        $result = $query->result();

        if (count($result) != 0) {
            return $result[0]->uuid;
        } else {
            return 0;
        }
    }
    public function getLocNames($dist = null, $sub = null, $cir = null, $mouza = null, $lot = null, $village = null)
    {
        if ($dist) {
            $location['district'] = $this->db->select(['loc_name', 'dist_code'])->where(array('dist_code' => $dist, 'subdiv_code' => '00', 'cir_code' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->get('location')->row();
        }
        if ($sub) {
            $location['subdiv'] = $this->db->select(['loc_name', 'dist_code', 'subdiv_code'])->where(array('dist_code' => $dist, 'subdiv_code' => $sub, 'cir_code' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->get('location')->row();
        }
        if ($cir) {
            $location['circle'] = $this->db->select(['loc_name', 'dist_code', 'subdiv_code', 'cir_code'])->where(array('dist_code' => $dist, 'subdiv_code' => $sub, 'cir_code' => $cir, 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->get('location')->row();
        }
        if ($mouza) {
            $location['mouza'] = $this->db->select(['loc_name', 'dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code'])->where(array('dist_code' => $dist, 'subdiv_code' => $sub, 'cir_code' => $cir, 'mouza_pargona_code' => $mouza, 'lot_no' => '00', 'vill_townprt_code' => '00000'))->get('location')->row();
        }
        if ($lot) {
            $location['lot'] = $this->db->select(['loc_name', 'dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no'])->where(array('dist_code' => $dist, 'subdiv_code' => $sub, 'cir_code' => $cir, 'mouza_pargona_code' => $mouza, 'lot_no' => $lot, 'vill_townprt_code' => '00000'))->get('location')->row();
        }
        if ($village) {
            $location['village'] = $this->db->select(['loc_name', 'dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code'])->where(array('dist_code' => $dist, 'subdiv_code' => $sub, 'cir_code' => $cir, 'mouza_pargona_code' => $mouza, 'lot_no' => $lot, 'vill_townprt_code' => $village))->get('location')->row();
        }

        return $location;
    }
    public function getMouzas($dist_code, $subdiv_code, $cir_code)
    {
        return $this->db->select(['loc_name', 'dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code'])->where(array('dist_code' => $dist_code, 'subdiv_code' => $subdiv_code, 'cir_code' => $cir_code, 'mouza_pargona_code !=' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->get('location')->result();
    }
    public function getLots($dist_code, $subdiv_code, $cir_code, $mouza_code)
    {
        return $this->db->select(['loc_name', 'dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no'])->where(array('dist_code' => $dist_code, 'subdiv_code' => $subdiv_code, 'cir_code' => $cir_code, 'mouza_pargona_code' => $mouza_code, 'lot_no !=' => '00', 'vill_townprt_code' => '00000'))->get('location')->result();
    }
    public function getVillages($dist_code, $subdiv_code, $cir_code, $mouza_code, $lot_no)
    {
        return $this->db->select(['loc_name', 'dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code'])->where(array('dist_code' => $dist_code, 'subdiv_code' => $subdiv_code, 'cir_code' => $cir_code, 'mouza_pargona_code' => $mouza_code, 'lot_no' => $lot_no, 'vill_townprt_code !=' => '00000'))->get('location')->result();
    }
}
