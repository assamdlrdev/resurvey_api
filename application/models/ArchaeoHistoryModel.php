<?php
class ArchaeoHistoryModel extends CI_Model {


    public function getArchoHistoryCode()
    {
        $aprchoHistoryCodes = $this->db->select()
            ->get('archeo_hist_site_code');
        return $aprchoHistoryCodes->result();
    }


    public function checkArchaeoHistId()
    {
        $dist_code = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no = $this->session->userdata('lot_no');
        $vill_townprt_code = $this->session->userdata('vill_townprt_code');
        $dag_no=$this->session->userdata('dag_no');
        $where="(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code'
         and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no'
          and vill_townprt_code='$vill_townprt_code' and dag_no='$dag_no' )";
        $this->db->select_max('archeo_sl_no', 'max');
        $query=$this->db->get_where('chitha_acho_hist',$where);
        if ($query->num_rows() == 0) {
            return 1;
        }
        $max = $query->row()->max;
        return $max == 0 ? 1 : $max + 1;
    }



    public function getArchoHistoryCodeWithId($id)
    {
        $appTypes =  $this->db->select()
            ->where('id',$id)
            ->get('archeo_hist_site_code')
            ->row();

        return $appTypes;
    }


    // save data
    public function insertArchHistorical($data)
    {
        $this->db->trans_start();
        $this->db->insert('chitha_acho_hist', $data);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }







}
