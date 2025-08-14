<?php
class PattaModel extends CI_Model {


    public function updatePattaDetails($updateData)
    {
        $this->db->trans_start();
        $this->db->where('dist_code',$updateData['dist_code']);
        $this->db->where('subdiv_code',$updateData['subdiv_code']);
        $this->db->where('cir_code',$updateData['cir_code']);
        $this->db->where('mouza_pargona_code',$updateData['mouza_pargona_code']);
        $this->db->where('lot_no',$updateData['lot_no']);
        $this->db->where('vill_townprt_code',$updateData['vill_townprt_code']);
        $this->db->where('patta_type',$updateData['patta_type']);
        $this->db->where('patta_type_code',$updateData['patta_type_code']);
        $this->db->where('patta_no',$updateData['patta_no']);
        $this->db->update('patta_basic', [
            'time_period' => $updateData['time_period'],
            'upto_date' => $updateData['upto_date'],
            'installment1' => $updateData['installment1'],
            'installment2' => $updateData['installment2'],
            'revenue_to_be_paid1' => $updateData['revenue_to_be_paid1'],
            'revenue_to_be_paid2' => $updateData['revenue_to_be_paid2'],
            'guardian_name' => $updateData['guardian_name'],
            'pattadar_id' => $updateData['pattadar_id'],
            'mobile_no' => $updateData['mobile_no'],
            'relation' => $updateData['relation'],
        ]);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

}