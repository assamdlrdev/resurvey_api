<?php
class JamaRemarkModel extends CI_Model
{


    // save the message
    public function saveJamabandiRemarks($data)
    {
        $this->db->trans_start();
        $this->db->insert('jama_remark', $data);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    // count remarks exist or not
    public function checkJamabandiRemarksExistOrNot($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code, $pattatypeCode, $patta_no, $slNo)
    {
        return $this->db->select()
            ->where('dist_code', $dist_code)
            ->where('subdiv_code', $subdiv_code)
            ->where('cir_code', $circle_code)
            ->where('mouza_pargona_code', $mouza_code)
            ->where('lot_no', $lot_no)
            ->where('vill_townprt_code', $vill_code)
            ->where('patta_type_code', $pattatypeCode)
            ->where('patta_no', $patta_no)
            ->where('rmk_line_no', $slNo)
            ->get('jama_remark')
            ->num_rows();
    }

    // update the jamabandi remarks
    public function updateJamabandiRemarkDetails($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code, $pattatypeCode, $patta_no, $slNo, $updateData)
    {
        $this->db->trans_start();
        $this->db->where('dist_code', $dist_code);
        $this->db->where('subdiv_code', $subdiv_code);
        $this->db->where('cir_code', $circle_code);
        $this->db->where('mouza_pargona_code', $mouza_code);
        $this->db->where('lot_no', $lot_no);
        $this->db->where('vill_townprt_code', $vill_code);
        $this->db->where('patta_type_code', $pattatypeCode);
        $this->db->where('patta_no', $patta_no);
        $this->db->where('rmk_line_no', $slNo);
        $this->db->update('jama_remark', $updateData);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }



    // update the jamabandi remarks
    public function deleteJamabandiRemarkDetails($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code, $pattatypeCode, $patta_no, $slNo)
    {
        $this->db->trans_start();
        $this->db->where('dist_code', $dist_code);
        $this->db->where('subdiv_code', $subdiv_code);
        $this->db->where('cir_code', $circle_code);
        $this->db->where('mouza_pargona_code', $mouza_code);
        $this->db->where('lot_no', $lot_no);
        $this->db->where('vill_townprt_code', $vill_code);
        $this->db->where('patta_type_code', $pattatypeCode);
        $this->db->where('patta_no', $patta_no);
        $this->db->where('rmk_line_no', $slNo);
        $this->db->delete('jama_remark');
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
}
