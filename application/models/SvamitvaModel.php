<?php
class SvamitvaModel extends CI_Model
{

    public function getDagArea()
    {
        $dist_code   = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code    = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no      = $this->session->userdata('lot_no');
        $vill_townprt_code = $this->session->userdata('vill_townprt_code');
        $dag_no = $this->session->userdata('dag_no');

        $where = "(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' and dag_no='$dag_no')";
        $this->db->select('dag_area_b,dag_area_k,dag_area_lc,dag_area_g,dag_area_are,dag_no,patta_no,patta_type_code');
        $query = $this->db->get_where('chitha_basic', $where);
        $dag = $query->row();
        if ($dag) {
            if (($dist_code == '21') || ($dist_code == '22') || ($dist_code == '23')) {
                $b = $dag->dag_area_b;
                $k = $dag->dag_area_k;
                $l = $dag->dag_area_lc;
                $g = $dag->dag_area_g;
                $area_ganda = $b * 6400 + $k * 320 + $l * 20 + $g;
                $area_are_b = $area_ganda * (13.37804 / 6400);
                $total_area_are = $area_are_b;
            } else {
                $b = $dag->dag_area_b;
                $k = $dag->dag_area_k;
                $l = $dag->dag_area_lc;
                $area_lessa = $b * 100 + $k * 20 + $l;
                $area_are = $area_lessa * (100 / 747.45);
                $total_area_are = $area_are;
            }
            return $total_area_are;
        }
        return false;
    }
    public function dagAreaOccupiedSingleOccupier($encro_id)
    {
        $dist_code   = $this->session->userdata('dist_code');
        $occupier = $this->occupierSingle($encro_id);
        if (($dist_code == '21') || ($dist_code == '22') || ($dist_code == '23')) {
            $b = $occupier->encro_land_b;
            $k = $occupier->encro_land_k;
            $l = $occupier->encro_land_lc;
            $g = $occupier->encro_land_g;
            $area_ganda = $b * 6400 + $k * 320 + $l * 20 + $g;
            $area_are_b = $area_ganda * (13.37804 / 6400);
            $total_area_are = $area_are_b;
        } else {
            $b = $occupier->encro_land_b;
            $k = $occupier->encro_land_k;
            $l = $occupier->encro_land_lc;
            $area_lessa = $b * 100 + $k * 20 + $l;
            $area_are = $area_lessa * (100 / 747.45);
            $total_area_are = $area_are;
        }
        return $total_area_are;
    }
    public function dagAreaOccupied()
    {
        $dist_code   = $this->session->userdata('dist_code');
        $area_total = 0;
        $occupiers = $this->occupiers();
        foreach ($occupiers as $occupier) {
            if (($dist_code == '21') || ($dist_code == '22') || ($dist_code == '23')) {
                $b = $occupier->encro_land_b;
                $k = $occupier->encro_land_k;
                $l = $occupier->encro_land_lc;
                $g = $occupier->encro_land_g;
                $area_ganda = $b * 6400 + $k * 320 + $l * 20 + $g;
                $area_are_b = $area_ganda * (13.37804 / 6400);
                $total_area_are = $area_are_b;
            } else {
                $b = $occupier->encro_land_b;
                $k = $occupier->encro_land_k;
                $l = $occupier->encro_land_lc;
                $area_lessa = $b * 100 + $k * 20 + $l;
                $area_are = $area_lessa * (100 / 747.45);
                $total_area_are = $area_are;
            }
            $area_total += $total_area_are;
        }
        return $area_total;
    }
    public function areaSubmitted($b, $k, $lc, $g)
    {
        $dist_code   = $this->session->userdata('dist_code');
        if (($dist_code == '21') || ($dist_code == '22') || ($dist_code == '23')) {
            $area_ganda = $b * 6400 + $k * 320 + $lc * 20 + $g;
            $area_are_b = $area_ganda * (13.37804 / 6400);
            $total_area_are = $area_are_b;
        } else {
            $area_lessa = $b * 100 + $k * 20 + $lc;
            $area_are = $area_lessa * (100 / 747.45);
            $total_area_are = $area_are;
        }
        return $total_area_are;
    }
    function maxFamilyId($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code, $dag_no, $encro_id)
    {
        $where = "(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='$mouza_code' and lot_no='$lot_no' and vill_townprt_code='$vill_code' and trim(dag_no)=trim('$dag_no') and encro_id='$encro_id')";
        $this->db->select_max('family_member_id', 'max');
        $query = $this->db->get_where('chitha_pattadar_family', $where);
        if ($query->num_rows() == 0) {
            return 1;
        }
        $max = $query->row()->max;
        return $max == 0 ? 1 : $max + 1;
    }
    public function families($encro_id)
    {
        $dist_code   = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code    = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no      = $this->session->userdata('lot_no');
        $vill_townprt_code = $this->session->userdata('vill_townprt_code');
        $dag_no = $this->session->userdata('dag_no');

        $q = $this->db->select('*')
            ->where('dist_code', $dist_code)
            ->where('subdiv_code', $subdiv_code)
            ->where('cir_code', $cir_code)
            ->where('mouza_pargona_code', $mouza_pargona_code)
            ->where('lot_no', $lot_no)
            ->where('vill_townprt_code', $vill_townprt_code)
            ->where('dag_no', $dag_no)
            ->where('encro_id', $encro_id)
            ->get('chitha_pattadar_family');

        return $q->result();
    }
    public function deleteOccupant($encro_id)
    {
        $dist_code   = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code    = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no      = $this->session->userdata('lot_no');
        $vill_townprt_code = $this->session->userdata('vill_townprt_code');
        $dag_no = $this->session->userdata('dag_no');

        $this->db->trans_start();
        $this->db->where('dist_code', $dist_code)
            ->where('subdiv_code', $subdiv_code)
            ->where('cir_code', $cir_code)
            ->where('mouza_pargona_code', $mouza_pargona_code)
            ->where('lot_no', $lot_no)
            ->where('vill_townprt_code', $vill_townprt_code)
            ->where('dag_no', $dag_no)
            ->where('encro_id', $encro_id);
        $this->db->delete('chitha_rmk_encro');
        $this->db->trans_complete();
        if($this->db->trans_status() == true){
            $this->db->trans_start();
            $this->db->where('dist_code', $dist_code)
            ->where('subdiv_code', $subdiv_code)
            ->where('cir_code', $cir_code)
            ->where('mouza_pargona_code', $mouza_pargona_code)
            ->where('lot_no', $lot_no)
            ->where('vill_townprt_code', $vill_townprt_code)
            ->where('dag_no', $dag_no)
            ->where('encro_id', $encro_id);
            $this->db->delete('chitha_pattadar_family');
            $this->db->trans_complete();
            return $this->db->trans_status();
        }else{
            return false;
        }
    }
    public function occupierSingle($encro_id)
    {
        $dist_code   = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code    = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no      = $this->session->userdata('lot_no');
        $vill_townprt_code = $this->session->userdata('vill_townprt_code');
        $dag_no = $this->session->userdata('dag_no');

        $q = $this->db->select('*')
            ->where('dist_code', $dist_code)
            ->where('subdiv_code', $subdiv_code)
            ->where('cir_code', $cir_code)
            ->where('mouza_pargona_code', $mouza_pargona_code)
            ->where('lot_no', $lot_no)
            ->where('vill_townprt_code', $vill_townprt_code)
            ->where('dag_no', $dag_no)
            ->where('encro_id', $encro_id)
            ->get('chitha_rmk_encro');

        return $q->row();
    }

    public function occupiers()
    {
        $dist_code   = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code    = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no      = $this->session->userdata('lot_no');
        $vill_townprt_code = $this->session->userdata('vill_townprt_code');
        $dag_no = $this->session->userdata('dag_no');
        $q = $this->db->select('*')
            ->where('dist_code', $dist_code)
            ->where('subdiv_code', $subdiv_code)
            ->where('cir_code', $cir_code)
            ->where('mouza_pargona_code', $mouza_pargona_code)
            ->where('lot_no', $lot_no)
            ->where('vill_townprt_code', $vill_townprt_code)
            ->where('dag_no', $dag_no)
            ->order_by('encro_id', 'ASC')
            ->get('chitha_rmk_encro');
        return $q->result();
    }
    // chitha dag basic
    public function getChithaBasic($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code, $dag_no)
    {

        $this->db->select('chitha_basic.*,landclass_code.land_type as full_land_type_name');
        $this->db->from('chitha_basic');
        $this->db->join('landclass_code', 'landclass_code.class_code = chitha_basic.land_class_code');
        $this->db->where('chitha_basic.dist_code', $dist_code);
        $this->db->where('chitha_basic.subdiv_code', $subdiv_code);
        $this->db->where('chitha_basic.cir_code', $circle_code);
        $this->db->where('chitha_basic.mouza_pargona_code', $mouza_code);
        $this->db->where('chitha_basic.lot_no', $lot_no);
        $this->db->where('chitha_basic.vill_townprt_code', $vill_code);
        $this->db->where('chitha_basic.dag_no', $dag_no);
        $this->db->where_in('chitha_basic.jama_yn', ['n','N']);
        return $this->db->get()->row();
    }
    public function getDags($district_code, $subdivision_code, $circle_code, $mouza_code, $lot_code, $village_code)
    {

        $q = "Select dag_no, dag_no_int from Chitha_Basic where Dist_code='$district_code' and Subdiv_code='$subdivision_code' and "
            . "Cir_code='$circle_code' and Mouza_Pargona_code='$mouza_code' and Lot_No='$lot_code' "
            . "and Vill_townprt_code='$village_code' order by CAST(coalesce(dag_no_int, '0') AS numeric)";

        $district = $this->db->query($q);

        return $district->result();
    }
    function insert_encroacher_family($data)
    {
        $data = $this->security->xss_clean($data);

        $this->db->trans_start();
        $this->db->insert('chitha_pattadar_family', $data);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    // get All Block under selected district
    public function getAllBlockDistrictWise($distCode)
    {
        $this->db->select('block_code,block_name');
        $this->db->distinct('block_code');
        $this->db->where('dhar_dist_code', $distCode);
        $this->db->from('block_panch');
        $blocks = $this->db->get();

        return $blocks->result();
    }


    // get all Gram Panchayat by Block Code
    public function getAllPanchByBlockCode($distCode, $blockCode)
    {
        $this->db->select('panch_code,panch_name');
        $this->db->distinct('panch_code');
        $this->db->where('dhar_dist_code', $distCode);
        $this->db->where('block_code', $blockCode);
        $this->db->from('block_panch');
        $panchs = $this->db->get();

        return $panchs->result();
    }


    function pattadarDetails($patta_no, $pattatype)
    {

        $dcode = $this->session->userdata('dist_code');
        $scode = $this->session->userdata('subdiv_code');
        $ccode = $this->session->userdata('cir_code');
        $mcode = $this->session->userdata('mouza_pargona_code');
        $lcode = $this->session->userdata('lot_no');
        $vcode = $this->session->userdata('vill_townprt_code');
        //        $sql="select a.pdar_id,a.pdar_name,a.patta_no,a.patta_type_code,b.dag_no from chitha_pattadar as a join chitha_dag_pattadar as b on a.patta_no=b.patta_no and a.patta_type_code=b.patta_type_code and a.dist_code=b.dist_code and a.subdiv_code=b.subdiv_code and a.cir_code=b.cir_code and a.mouza_pargona_code=b.mouza_pargona_code and a.lot_no=b.lot_no and a.vill_townprt_code=b.vill_townprt_code and a.pdar_id=b.pdar_id where a.patta_no='$patta_no' and a.patta_type_code='$pattatype' and a.dist_code='$dcode' and a.subdiv_code='$scode' and a.cir_code='$ccode' and a.mouza_pargona_code='$mcode' and a.lot_no='$lcode' and a.vill_townprt_code='$vcode' and b.dag_no!='$dag_no' order by b.dag_no";
        $sql = "select pdar_id,pdar_name,patta_no,patta_type_code from chitha_pattadar where patta_no='$patta_no' and patta_type_code='$pattatype' and dist_code='$dcode' and subdiv_code='$scode' and cir_code='$ccode' and mouza_pargona_code='$mcode' and lot_no='$lcode' and vill_townprt_code='$vcode' order by pdar_id";
        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }


    function pattadarinsdet($patta, $ptype, $dagno)
    {
        $base = $this->config->item('base_url');
        $dcode = $this->session->userdata('dist_code');
        $scode = $this->session->userdata('subdiv_code');
        $ccode = $this->session->userdata('cir_code');
        $mcode = $this->session->userdata('mouza_pargona_code');
        $lcode = $this->session->userdata('lot_no');
        $vcode = $this->session->userdata('vill_townprt_code');
        $sql = "select a.pdar_id,a.pdar_name,a.patta_no,a.patta_type_code,b.dag_no from chitha_pattadar as a join chitha_dag_pattadar as b on a.patta_no=b.patta_no and a.patta_type_code=b.patta_type_code and a.dist_code=b.dist_code and a.subdiv_code=b.subdiv_code and a.cir_code=b.cir_code and a.mouza_pargona_code=b.mouza_pargona_code and a.lot_no=b.lot_no and a.vill_townprt_code=b.vill_townprt_code and a.pdar_id=b.pdar_id where b.dag_no='$dagno' and a.patta_no='$patta' and a.patta_type_code='$ptype' and a.dist_code='$dcode' and a.subdiv_code='$scode' and a.cir_code='$ccode' and a.mouza_pargona_code='$mcode' and a.lot_no='$lcode' and a.vill_townprt_code='$vcode' order by a.pdar_id";

        $query = $this->db->query($sql);

        $str = '';

        $str = $str . '<table class="table" border=0 bgcolor="#BFFFE6">';
        $str = $str . '<tr><td>Id</td><td>Name</td></tr>';
        if ($query) {
            foreach ($query->result() as $row) {
                $pid = $row->pdar_id;
                $pname = $row->pdar_name;
                $patta = $row->patta_no;
                $ptype = $row->patta_type_code;
                $dno = $row->dag_no;
                $vll = $pid . '-' . $patta . '-' . $ptype . '-' . $dno;
                //$str=$str.'<tr><td>'.$row->pdar_id.'</td><td>'.$row->pdar_name.'</td><tr>';
                $str = $str . '<tr><td>' . $row->pdar_id . '</td><td><a href=' . $base . 'index.php/ChithaSvamitvaController/pdareditSvamitva/' . $vll . ' title="Click here to edit pattadar details"><u>' . $row->pdar_name . '</u></a></td><tr>';
            }
        }
        $str = $str . '</table>';
        return $str;
    }






    public function chithaBasicDetailSvamitvaCardWithLandClassType($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code, $patta_type, $patta_no, $block_code, $gp_code)
    {

        $this->db->select('chitha_basic.*,landclass_code.land_type as full_land_type_name');
        $this->db->from('chitha_basic');
        $this->db->join('landclass_code', 'landclass_code.class_code = chitha_basic.land_class_code');
        $this->db->where('chitha_basic.dist_code', $dist_code);
        $this->db->where('chitha_basic.subdiv_code', $subdiv_code);
        $this->db->where('chitha_basic.cir_code', $circle_code);
        $this->db->where('chitha_basic.mouza_pargona_code', $mouza_code);
        $this->db->where('chitha_basic.lot_no', $lot_no);
        $this->db->where('chitha_basic.vill_townprt_code', $vill_code);
        $this->db->where('chitha_basic.patta_type_code', $patta_type);
        $this->db->where('chitha_basic.patta_no', $patta_no);
        $this->db->where('chitha_basic.block_code', $block_code);
        $this->db->where('chitha_basic.gp_code', $gp_code);
        $this->db->where('chitha_basic.jama_yn', 'n');
        $cDetails = $this->db->get();

        return $cDetails->result();
    }





    // district name
    public function getDistrictName($dist_code)
    {
        $district = $this->db->query("select loc_name AS district from   location where dist_code ='$dist_code'  and "
            . " subdiv_code='00' and cir_code='00' and mouza_pargona_code='00' and "
            . " vill_townprt_code='00000' and lot_no='00'");
        return $district->row();
    }

    // subdivision name
    public function getSubDivName($dist_code, $subdiv_code)
    {
        $subdiv = $this->db->query("select loc_name AS subdiv from   location where dist_code ='$dist_code'  and "
            . " subdiv_code='$subdiv_code' and cir_code='00' and mouza_pargona_code='00' and "
            . " vill_townprt_code='00000' and lot_no='00'");
        return $subdiv->row();
    }

    //circle name
    public function getCircleName($dist_code, $subdiv_code, $circle_code)
    {
        $circle = $this->db->query("select loc_name AS circle from   location where dist_code ='$dist_code'  and "
            . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='00' and "
            . " vill_townprt_code='00000' and lot_no='00'");
        return $circle->row();
    }

    // mouza name
    public function getMouzaName($dist_code, $subdiv_code, $circle_code, $mouza_code)
    {
        $mouza = $this->db->query("select loc_name AS mouza from   location where dist_code ='$dist_code'  and "
            . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='$mouza_code' and "
            . " vill_townprt_code='00000' and lot_no='00'");
        return $mouza->row();
    }

    // lot No
    public function getLotName($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no)
    {
        $lot = $this->db->query("select loc_name as lot_no from   location where dist_code ='$dist_code'  and "
            . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='$mouza_code' and "
            . " vill_townprt_code='00000' and lot_no='$lot_no'");
        return $lot->row();
    }

    //village name
    public function getVillageName($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code)
    {
        $village = $this->db->query("select loc_name AS village from   location where dist_code ='$dist_code'  and "
            . " subdiv_code='$subdiv_code' and cir_code='$circle_code' and mouza_pargona_code='$mouza_code' and "
            . " vill_townprt_code='$vill_code' and lot_no='$lot_no'");
        return $village->row();
    }


    //block name
    public function getBlockName($dist_code, $block_code, $gp_code)
    {

        $this->db->select('block_name,panch_name');
        $this->db->distinct('panch_code');
        $this->db->where('dhar_dist_code', $dist_code);
        $this->db->where('block_code', $block_code);
        $this->db->where('panch_code', $gp_code);
        $this->db->from('block_panch');
        $panchs = $this->db->get()->row();

        return $panchs;
    }


    // pattadar
    public function pattadarDetailSvamitvaCard($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code, $patta_type, $patta_no)
    {
        $cDetails = $this->db->select('*')
            ->where('dist_code', $dist_code)
            ->where('subdiv_code', $subdiv_code)
            ->where('cir_code', $circle_code)
            ->where('mouza_pargona_code', $mouza_code)
            ->where('lot_no', $lot_no)
            ->where('vill_townprt_code', $vill_code)
            ->where('patta_type_code', $patta_type)
            ->where('patta_no', $patta_no)
            ->get('chitha_pattadar');

        return $cDetails->result();
    }


    // pattadar family
    public function pattadarFamilyDetailSvamitvaCard($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code, $patta_type, $patta_no)
    {
        $cDetails = $this->db->select('*')
            ->where('dist_code', $dist_code)
            ->where('subdiv_code', $subdiv_code)
            ->where('cir_code', $circle_code)
            ->where('mouza_pargona_code', $mouza_code)
            ->where('lot_no', $lot_no)
            ->where('vill_townprt_code', $vill_code)
            ->where('patta_type_code', $patta_type)
            ->where('patta_no', $patta_no)
            ->get('chitha_pattadar_family');

        return $cDetails->result();
    }




    // pattadar with pattadar id
    public function pattadarDetailSvamitvaCardWithPdarId($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code, $patta_type, $patta_no, $pattadar)
    {
        $cDetails = $this->db->select('*')
            ->where('dist_code', $dist_code)
            ->where('subdiv_code', $subdiv_code)
            ->where('cir_code', $circle_code)
            ->where('mouza_pargona_code', $mouza_code)
            ->where('lot_no', $lot_no)
            ->where('vill_townprt_code', $vill_code)
            ->where('patta_type_code', $patta_type)
            ->where('patta_no', $patta_no)
            ->where('pdar_id', $pattadar)
            ->get('chitha_pattadar');

        return $cDetails->result();
    }


    // pattadar family with pattadar id
    public function pattadarFamilyDetailSvamitvaCardWithPdarId($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code, $patta_type, $patta_no, $pattadar)
    {
        $cDetails = $this->db->select('*')
            ->where('dist_code', $dist_code)
            ->where('subdiv_code', $subdiv_code)
            ->where('cir_code', $circle_code)
            ->where('mouza_pargona_code', $mouza_code)
            ->where('lot_no', $lot_no)
            ->where('vill_townprt_code', $vill_code)
            ->where('patta_type_code', $patta_type)
            ->where('patta_no', $patta_no)
            ->where('pdar_id', $pattadar)
            ->get('chitha_pattadar_family');

        return $cDetails->result();
    }


    public function pattadarexispid($pid, $pattano, $pattatype, $dagno)
    {
        $dcode = $this->session->userdata('dist_code');
        $scode = $this->session->userdata('subdiv_code');
        $ccode = $this->session->userdata('cir_code');
        $mcode = $this->session->userdata('mouza_pargona_code');
        $lcode = $this->session->userdata('lot_no');
        $vcode = $this->session->userdata('vill_townprt_code');

        $sql = "select a.pdar_id,a.pdar_gender,a.pdar_name,a.patta_no,a.patta_type_code,a.pdar_relation,a.pdar_father,a.pdar_add1,a.pdar_add2,a.pdar_add3,a.pdar_pan_no,a.pdar_citizen_no,
        a.pdar_mobile,a.pdar_pan_no,a.cast_category,a.marital_status,a.occupation,
b.dag_por_b,b.dag_por_k,b.dag_por_lc,b.dag_por_g,b.pdar_land_n,b.pdar_land_s,b.pdar_land_e,b.pdar_land_w,b.pdar_land_revenue,b.pdar_land_localtax,b.p_flag 
from chitha_pattadar as a join chitha_dag_pattadar as b on a.patta_no=b.patta_no and a.patta_type_code=b.patta_type_code and a.dist_code=b.dist_code and a.subdiv_code=b.subdiv_code 
and a.cir_code=b.cir_code and a.mouza_pargona_code=b.mouza_pargona_code and a.lot_no=b.lot_no and a.vill_townprt_code=b.vill_townprt_code and a.pdar_id=b.pdar_id where a.pdar_id=$pid 
and b.dag_no='$dagno' and a.patta_no='$pattano' and a.patta_type_code='$pattatype' and a.dist_code='$dcode' and a.subdiv_code='$scode' and a.cir_code='$ccode' and a.mouza_pargona_code='$mcode' 
and a.lot_no='$lcode' and a.vill_townprt_code='$vcode' order by b.dag_no";

        $query = $this->db->query($sql);
        $row = $query->row_array();
        $pid = $row['pdar_id'];
        $pname = $row['pdar_name'];
        $prel = $row['pdar_relation'];
        $pfath = $row['pdar_father'];
        $padd1 = $row['pdar_add1'];
        $padd2 = $row['pdar_add2'];
        $padd3 = $row['pdar_add3'];
        $ppan = $row['pdar_pan_no'];
        $pcit = $row['pdar_citizen_no'];
        $bigha = $row['dag_por_b'];
        $katha = $row['dag_por_k'];
        $lessa = $row['dag_por_lc'];
        $ganda = $row['dag_por_g'];
        $landn = $row['pdar_land_n'];
        $lands = $row['pdar_land_s'];
        $lande = $row['pdar_land_e'];
        $landw = $row['pdar_land_w'];
        $lrev = $row['pdar_land_revenue'];
        $ltax = $row['pdar_land_localtax'];
        $pflag = $row['p_flag'];
        $pGender = $row['pdar_gender'];
        $pMobile = $row['pdar_mobile'];
        $pPanNo = $row['pdar_pan_no'];
        $pCaste = $row['cast_category'];
        $pMStatus = $row['marital_status'];
        $pOccupation = $row['occupation'];

        return $pdet = $pid . '$' . $pname . '$' . $prel . '$' . $pfath . '$' . $padd1 . '$' . $padd2 . '$' . $padd3 . '$' .
            $ppan . '$' . $pcit . '$' . $bigha . '$' . $katha . '$' . $lessa . '$' . $ganda . '$' . $landn . '$' . $lands . '$' .
            $lande . '$' . $landw . '$' . $lrev . '$' . $ltax . '$' . $pflag . '$' . $pGender . '$' .
            $pMobile . '$' . $pPanNo . '$' . $pCaste . '$' . $pMStatus . '$' . $pOccupation;
    }

    public function getGovtDags($district_code, $subdivision_code, $circle_code, $mouza_code, $lot_code, $village_code)
    {
        $govt_patta_codes = GovtPattaCode;
        $codes = [];
        foreach($govt_patta_codes as $code){
            $codes[] = "'".$code."'";
        }
        $govt_patta_codes = implode(',',$codes);
        $q = "SELECT dag_no, dag_no_int FROM chitha_Basic WHERE dist_code='$district_code' AND subdiv_code='$subdivision_code' AND cir_code='$circle_code' 
        AND mouza_Pargona_code='$mouza_code' AND lot_No='$lot_code' AND vill_townprt_code='$village_code' 
        AND patta_type_code in ($govt_patta_codes)
        order by CAST(coalesce(dag_no_int, '0') AS numeric)";

        $district = $this->db->query($q);

        return $district->result();
    }
}
