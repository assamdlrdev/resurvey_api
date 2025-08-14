<?php
class RemoveTestingDataModel extends CI_Model
{

    public function dataswitch()
    {
        $CI =& get_instance();
        if ($this->session->userdata('dcode') == "02") {
            $this->db = $CI->load->database('lsp3', TRUE);
        } else if ($this->session->userdata('dcode') == "05") {
            $this->db = $CI->load->database('lsp1', TRUE);
        } else if ($this->session->userdata('dcode') == "13") {
            $this->db = $CI->load->database('lsp2', TRUE);
        } else if ($this->session->userdata('dcode') == "17") {
            $this->db = $CI->load->database('lsp4', TRUE);
        } else if ($this->session->userdata('dcode') == "15") {
            $this->db = $CI->load->database('lsp5', TRUE);
        } else if ($this->session->userdata('dcode') == "14") {
            $this->db = $CI->load->database('lsp6', TRUE);
        } else if ($this->session->userdata('dcode') == "07") {
            $this->db = $CI->load->database('lsp7', TRUE);
        } else if ($this->session->userdata('dcode') == "03") {
            $this->db = $CI->load->database('lsp8', TRUE);
        } else if ($this->session->userdata('dcode') == "18") {
            $this->db = $CI->load->database('lsp9', TRUE);
        } else if ($this->session->userdata('dcode') == "12") {
            $this->db = $CI->load->database('lsp13', TRUE);
        } else if ($this->session->userdata('dcode') == "24") {
            $this->db = $CI->load->database('lsp10', TRUE);
        } else if ($this->session->userdata('dcode') == "06") {
            $this->db = $CI->load->database('lsp11', TRUE);
        } else if ($this->session->userdata('dcode') == "11") {
            $this->db = $CI->load->database('lsp12', TRUE);
        } else if ($this->session->userdata('dcode') == "12") {
            $this->db = $CI->load->database('lsp13', TRUE);
        } else if ($this->session->userdata('dcode') == "16") {
            $this->db = $CI->load->database('lsp14', TRUE);
        } else if ($this->session->userdata('dcode') == "32") {
            $this->db = $CI->load->database('lsp15', TRUE);
        } else if ($this->session->userdata('dcode') == "33") {
            $this->db = $CI->load->database('lsp16', TRUE);
        } else if ($this->session->userdata('dcode') == "34") {
            $this->db = $CI->load->database('lsp17', TRUE);
        } else if ($this->session->userdata('dcode') == "21") {
            $this->db = $CI->load->database('lsp18', TRUE);
        } else if ($this->session->userdata('dcode') == "08") {
            $this->db = $CI->load->database('lsp19', TRUE);
        } else if ($this->session->userdata('dcode') == "35") {
            $this->db = $CI->load->database('lsp20', TRUE);
        } else if ($this->session->userdata('dcode') == "36") {
            $this->db = $CI->load->database('lsp21', TRUE);
        } else if ($this->session->userdata('dcode') == "37") {
            $this->db = $CI->load->database('lsp22', TRUE);
        } else if ($this->session->userdata('dcode') == "25") {
            $this->db = $CI->load->database('lsp23', TRUE);
        } else if ($this->session->userdata('dcode') == "10") {
            $this->db = $CI->load->database('lsp24', TRUE);
        } else if ($this->session->userdata('dcode') == "38") {
            $this->db = $CI->load->database('lsp25', TRUE);
        } else if($this->session->userdata('dcode') == "39") {
            $this->db = $CI->load->database('lsp26', TRUE);
        } else if($this->session->userdata('dcode') == "22") {
            $this->db = $CI->load->database('lsp27', TRUE);
        } else if($this->session->userdata('dcode') == "23") {
            $this->db = $CI->load->database('lsp28', TRUE);
        }else if ($this->session->userdata('dcode') == "27") {
            $this->db = $CI->load->database('lsp30', TRUE);
        }
    }


    // for testing data remove By Masud Reza 04/06/2022


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







}