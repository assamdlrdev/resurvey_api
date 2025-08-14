<?php
class Chithamodel extends CI_Model
{

    public function districtdetails($distcode)
    {
        // $districts = callLandhubAPI('POST', 'getDists_svamitva', [
        //     'dist_code' => $distcode,
        // ]);
        // $district = [];
        // if ($districts != 'N') {
        //     foreach ($districts as $key => $dist) {
        //         if ($dist->dist_code == $distcode) {
        //             $district[] = json_decode(json_encode($dist), true);
        //         }
        //     }
        // }
        // return $district;
        return ($this->db->get_where('location', array('dist_code' => $distcode, 'subdiv_code' => '00', 'cir_code' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->result_array());
    }
    public function subdivisiondetails($dist, $subdiv_code = null)
    {

        if (in_array($dist, json_decode(BTC_DISTIRTCS))) {
            if ($subdiv_code && $subdiv_code != '00') {
                return $this->db->get_where('location', array('dist_code' => $dist, 'subdiv_code ' => $subdiv_code, 'cir_code' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->result_array();
            } else {
                return $this->db->get_where('location', array('dist_code' => $dist, 'subdiv_code !=' => '00', 'cir_code' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->result_array();
            }
        } else {
            $subDivs = callLandhubAPI('POST', 'getSubdivs', [
                'dist_code' => $dist,
            ]);

            if ($subdiv_code && $subdiv_code != '00') {
                $subDiv = [];
                if ($subDivs != 'N') {
                    foreach ($subDivs as $key => $sub) {
                        if ($sub->subdiv_code == $subdiv_code) {
                            $subDiv[] = json_decode(json_encode($sub), true);
                        }
                    }
                }
                return $subDiv;
            } else {
                return json_decode(json_encode($subDivs), true);
            }
        }
    }
    public function circledetails($dist, $sub, $cir_code = null)
    {
        if (in_array($dist, json_decode(BTC_DISTIRTCS))) {
            if ($cir_code && $cir_code != '00') {
                return $this->db->get_where('location', array('dist_code' => $dist, 'subdiv_code =' => $sub, 'cir_code ' => $cir_code, 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->result_array();
            } else {
                return $this->db->get_where('location', array('dist_code' => $dist, 'subdiv_code =' => $sub, 'cir_code!=' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->result_array();
            }
        } else {
            $circles = callLandhubAPI('POST', 'getCircles', [
                'dist_code' => $dist,
                'subdiv_code' => $sub,
            ]);
            if ($cir_code && $cir_code != '00') {
                $circle = [];
                if ($circles != 'N') {
                    foreach ($circles as $key => $cir) {
                        if ($cir->cir_code == $cir_code) {
                            $circle[] = json_decode(json_encode($cir), true);
                        }
                    }
                }
                return $circle;
            } else {
                return json_decode(json_encode($circles), true);
            }
        }
    }
    public function mouzadetails($dist, $sub, $circle)
    {
        if (in_array($dist, json_decode(BTC_DISTIRTCS))) {
            return $this->db->get_where('location', array('dist_code' => $dist, 'subdiv_code =' => $sub, 'cir_code=' => $circle, 'mouza_pargona_code!=' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->result_array();
        } else {
            $mouzas = callLandhubAPI('POST', 'getMouzas', [
                'dist_code' => $dist,
                'subdiv_code' => $sub,
                'cir_code' => $circle,
            ]);
            if ($mouzas != 'N') {
                foreach ($mouzas as $key => $mza) {
                    $mza->mouza_pargona_code = $mza->mouza_code;
                }
            }
            return json_decode(json_encode($mouzas), true);
        }
    }
    public function lotdetails($dist, $sub, $circle, $mza)
    {
        if (in_array($dist, json_decode(BTC_DISTIRTCS))) {
            return $this->db->get_where('location', array('dist_code' => $dist, 'subdiv_code =' => $sub, 'cir_code=' => $circle, 'mouza_pargona_code=' => $mza, 'lot_no!=' => '00', 'vill_townprt_code' => '00000'))->result_array();
        } else {
            $lots = callLandhubAPI('POST', 'getLots', [
                'dist_code' => $dist,
                'subdiv_code' => $sub,
                'cir_code' => $circle,
                'mouza_pargona_code' => $mza,
            ]);
            return json_decode(json_encode($lots), true);
        }
    }
    public function villagedetails($dist, $sub, $circle, $mza, $lot)
    {
        if (in_array($dist, json_decode(BTC_DISTIRTCS))) {
            return $this->db->get_where('location', array('dist_code' => $dist, 'subdiv_code =' => $sub, 'cir_code=' => $circle, 'mouza_pargona_code=' => $mza, 'lot_no=' => $lot, 'vill_townprt_code!=' => '00000'))->result_array();
        } else {
            $svamitva_villages = callLandhubAPI('POST', 'getVillages_svamitva', [
                'dist_code' => $dist,
                'subdiv_code' => $sub,
                'cir_code' => $circle,
                'mouza_pargona_code' => $mza,
                'lot_no' => $lot,
            ]);
            $villages = [];
            if ($svamitva_villages != 'N') {
                foreach ($svamitva_villages as $village) {
                    if ($village->dist_code == $dist and $village->subdiv_code == $sub and $village->cir_code == $circle and $village->mouza_pargona_code == $mza and $village->lot_no == $lot) {
                        $villages[] = $village;
                    }
                }
            }
            return json_decode(json_encode($villages), true);
        }
    }

    public function allVillagedetails($dist, $sub = null, $circle = null, $mza = null, $lot = null, $nc_btad = null)
    {
        $where = ['dist_code' => $dist];
        if ($sub) $where = $where + ['subdiv_code' => $sub];
        if ($circle) $where = $where + ['cir_code' => $circle];
        if ($mza) $where = $where + ['mouza_pargona_code' => $mza];
        if ($lot) $where = $where + ['lot_no' => $lot];
        if ($nc_btad) $where = $where + ['nc_btad' => $nc_btad];

        if (in_array($dist, json_decode(BTC_DISTIRTCS))) {
            return $this->db->where($where)->where('vill_townprt_code !=', '00000')->get('location')->result_array();
        } else {
            $svamitva_villages = callLandhubAPI('POST', 'getVillages', $where);
            $villages = [];
            if ($svamitva_villages && $svamitva_villages != 'N') {
                foreach ($svamitva_villages as $key => $village) {
                    $svamitva_villages[$key]->dist_code = $dist;
                    $svamitva_villages[$key]->mouza_pargona_code = $svamitva_villages[$key]->mouza_code;
                    $svamitva_villages[$key]->vill_townprt_code = $svamitva_villages[$key]->village_code;
                    $villages[] = $svamitva_villages[$key];
                }
            }
            return json_decode(json_encode($villages), true);
        }
    }

    public function svamitvaVillages($dist, $sub, $circle, $mza, $lot)
    {
        if (in_array($dist, json_decode(BTC_DISTIRTCS))) {
            return $this->db->get_where('location', array('dist_code' => $dist, 'subdiv_code =' => $sub, 'cir_code=' => $circle, 'mouza_pargona_code=' => $mza, 'lot_no=' => $lot, 'vill_townprt_code!=' => '00000'))->result_array();
        } else {
            $svamitva_villages = callLandhubAPI('POST', 'getVillages_svamitva', [
                'dist_code' => $dist,
                'subdiv_code' => $sub,
                'cir_code' => $circle,
                'mouza_pargona_code' => $mza,
                'lot_no' => $lot,
                'status' => '1'
            ]);
            $villages = [];
            if ($svamitva_villages != 'N') {
                foreach ($svamitva_villages as $village) {
                    if ($village->dist_code == $dist and $village->subdiv_code == $sub and $village->cir_code == $circle and $village->mouza_pargona_code == $mza and $village->lot_no == $lot) {
                        $villages[] = $village;
                    }
                }
            }
            return json_decode(json_encode($villages), true);
        }
    }
    public function districtdetailsreport()
    {
        return $this->db->get_where('location', array('subdiv_code' => '00', 'cir_code' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->result_array();
    }
    /* public function getPattaType() {
    return $this->db->get_where('patta_code',array('type_code!='=>'0000'))->result();
    } */
    public function getPattaType()
    {
        $ptype = $this->db->query("Select type_code,patta_type from patta_code order by type_code asc");
        return $ptype->result();
    }
    /* public function getLandclasscode() {
    return $this->db->get_where('landclass_code',array('class_code!='=>'0000'))->result();
    } */
    public function getLandclasscode()
    {
        $landclasstype = $this->db->query("Select class_code,land_type from landclass_code order by class_code asc");
        return $landclasstype->result();
    }

    public function getGuardrelation()
    {
        return $this->db->get_where('master_guard_rel', array('guard_rel!=' => ''))->result();
    }

    public function patta_type_name($pattatype)
    {
        $this->db->select('patta_type');
        $qp = $this->db->get_where('patta_code', array('type_code' => $pattatype));
        $rp = $qp->row_array();
        $patname = $rp['patta_type'];
        return $patname;
    }

    public function checkpattadarid()
    {
        $dist_code = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no = $this->session->userdata('lot_no');
        $vill_townprt_code = $this->session->userdata('vill_townprt_code');
        $patta_no = $this->session->userdata('patta_no');
        $patta_type_code = $this->session->userdata('patta_type');
        $where = "(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' and trim(patta_no)=trim('$patta_no') and patta_type_code='$patta_type_code')";
        $this->db->select_max('pdar_id', 'max');
        $query = $this->db->get_where('chitha_pattadar', $where);
        if ($query->num_rows() == 0) {
            return 1;
        }
        $max = $query->row()->max;
        return $max == 0 ? 1 : $max + 1;
    }

    public function relation()
    {
        $this->db->select('guard_rel,guard_rel_desc_as');
        $query = $this->db->get_where('master_guard_rel');
        return $query->result();
    }

    public function ntrcode()
    {
        $this->db->select('trans_code,trans_desc_as');
        $query = $this->db->get_where('nature_trans_code');
        return $query->result();
    }

    public function fmuttype()
    {
        $this->db->select('order_type_code,order_type');
        $query = $this->db->get_where('master_field_mut_type');
        return $query->result();
    }

    public function lmname()
    {
        $dist_code = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no = $this->session->userdata('lot_no');
        $where = "(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no')";
        $this->db->select('lm_code,lm_name');
        $query = $this->db->get_where('lm_code', $where);
        return $query->result();
    }

    public function coname()
    {
        $dist_code = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $where = "(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and user_desig_code='CO')";
        $this->db->select('user_code,username');
        $query = $this->db->get_where('users', $where);
        return $query->result();
    }

    public function ordersrno()
    {
        $dist_code = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no = $this->session->userdata('lot_no');
        $vill_townprt_code = $this->session->userdata('vill_townprt_code');
        $dag_no = $this->session->userdata('dag_no');

        $where = "(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' and dag_no='$dag_no')";
        $this->db->select_max('col8order_cron_no', 'max');
        $query = $this->db->get_where('chitha_col8_order', $where);
        if ($query->num_rows() == 0) {
            return 1;
        }
        $max = $query->row()->max;
        return $max == 0 ? 1 : $max + 1;
    }

    public function occupantid()
    {
        $dist_code = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no = $this->session->userdata('lot_no');
        $vill_townprt_code = $this->session->userdata('vill_townprt_code');
        $dag_no = $this->session->userdata('dag_no');

        $where = "(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' and dag_no='$dag_no')";
        $this->db->select_max('occupant_id', 'max');
        $query = $this->db->get_where('chitha_col8_occup', $where);
        if ($query->num_rows() == 0) {
            return 1;
        }
        $max = $query->row()->max;
        return $max == 0 ? 1 : $max + 1;
    }

    public function inplaceid()
    {
        $dist_code = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no = $this->session->userdata('lot_no');
        $vill_townprt_code = $this->session->userdata('vill_townprt_code');
        $dag_no = $this->session->userdata('dag_no');
        $col8crno = $this->session->userdata('col8crno');

        $where = "(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' and dag_no='$dag_no' and col8order_cron_no=$col8crno)";
        $this->db->select_max('inplace_of_id', 'max');
        $query = $this->db->get_where('chitha_col8_inplace', $where);
        if ($query->num_rows() == 0) {
            return 1;
        }
        $max = $query->row()->max;
        return $max == 0 ? 1 : $max + 1;
    }

    public function relationame($pdar_rel)
    {
        $this->db->select('guard_rel_desc_as');
        $qp = $this->db->get_where('master_guard_rel', array('guard_rel' => $pdar_rel));
        $rp = $qp->row_array();
        $relname = $rp['guard_rel_desc_as'];
        return $relname;
    }

    public function pdardag($patta_no, $pattatype, $dag_no)
    {
        $dcode = $this->session->userdata('dist_code');
        $scode = $this->session->userdata('subdiv_code');
        $ccode = $this->session->userdata('cir_code');
        $mcode = $this->session->userdata('mouza_pargona_code');
        $lcode = $this->session->userdata('lot_no');
        $vcode = $this->session->userdata('vill_townprt_code');
        $where = "(dist_code='$dcode' and subdiv_code='$scode' and cir_code='$ccode' and mouza_pargona_code='$mcode' and lot_no='$lcode' and vill_townprt_code='$vcode' and patta_no='$patta_no' and patta_type_code='$pattatype' and dag_no!='$dag_no')";
        $this->db->select('dag_no');
        $this->db->distinct();
        $query = $this->db->get_where('chitha_dag_pattadar', $where);
        return $query->result();
    }

    public function checknewpatta($patta_type, $patta_no)
    {
        $dcode = $this->session->userdata('dist_code');
        $scode = $this->session->userdata('subdiv_code');
        $ccode = $this->session->userdata('cir_code');
        $mcode = $this->session->userdata('mouza_pargona_code');
        $lcode = $this->session->userdata('lot_no');
        $vcode = $this->session->userdata('vill_townprt_code');

        $where = "(dist_code='$dcode' and subdiv_code='$scode' and cir_code='$ccode' and mouza_pargona_code='$mcode' and lot_no='$lcode' and vill_townprt_code='$vcode' and trim(patta_no)=trim('$patta_no') and patta_type_code='$patta_type')";
        $this->db->select('patta_no');
        $query = $this->db->get_where('chitha_basic', $where);
        $count = $query->num_rows();
        if ($count > 0) {
            $newpatta = 'N';
        } else {
            $newpatta = 'Y';
        }

        return $newpatta;
    }

    public function pattadardet($patta_no, $pattatype, $dag_no)
    {

        $dcode = $this->session->userdata('dist_code');
        $scode = $this->session->userdata('subdiv_code');
        $ccode = $this->session->userdata('cir_code');
        $mcode = $this->session->userdata('mouza_pargona_code');
        $lcode = $this->session->userdata('lot_no');
        $vcode = $this->session->userdata('vill_townprt_code');
        $sql = "select a.pdar_id,a.pdar_name,a.patta_no,a.patta_type_code,b.dag_no from chitha_pattadar as a join chitha_dag_pattadar as b on a.patta_no=b.patta_no and a.patta_type_code=b.patta_type_code and a.dist_code=b.dist_code and a.subdiv_code=b.subdiv_code and a.cir_code=b.cir_code and a.mouza_pargona_code=b.mouza_pargona_code and a.lot_no=b.lot_no and a.vill_townprt_code=b.vill_townprt_code and a.pdar_id=b.pdar_id where a.patta_no='$patta_no' and a.patta_type_code='$pattatype' and a.dist_code='$dcode' and a.subdiv_code='$scode' and a.cir_code='$ccode' and a.mouza_pargona_code='$mcode' and a.lot_no='$lcode' and a.vill_townprt_code='$vcode' and b.dag_no!='$dag_no' order by b.dag_no";

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function pattadardagdet($patta_no, $pattatype, $dagno)
    {

        $dcode = $this->session->userdata('dist_code');
        $scode = $this->session->userdata('subdiv_code');
        $ccode = $this->session->userdata('cir_code');
        $mcode = $this->session->userdata('mouza_pargona_code');
        $lcode = $this->session->userdata('lot_no');
        $vcode = $this->session->userdata('vill_townprt_code');
        $sql = "select a.pdar_id,a.pdar_name,a.patta_no,a.patta_type_code,a.lot_no,a.vill_townprt_code,b.dag_no from chitha_pattadar as a join chitha_dag_pattadar as b on a.patta_no=b.patta_no and a.patta_type_code=b.patta_type_code and a.dist_code=b.dist_code and a.subdiv_code=b.subdiv_code and a.cir_code=b.cir_code and a.mouza_pargona_code=b.mouza_pargona_code and a.lot_no=b.lot_no and a.vill_townprt_code=b.vill_townprt_code and a.pdar_id=b.pdar_id where b.dag_no='$dagno' and a.patta_no='$patta_no' and a.patta_type_code='$pattatype' and a.dist_code='$dcode' and a.subdiv_code='$scode' and a.cir_code='$ccode' and a.mouza_pargona_code='$mcode' and a.lot_no='$lcode' and a.vill_townprt_code='$vcode' order by b.dag_no";

        $query = $this->db->query($sql);

        $str = '';

        $str = $str . '<table class="table" border=0 bgcolor="#BFFFE6">';
        $str = $str . '<tr><td></td><td>Id</td><td>Name</td></tr>';
        if ($query) {
            foreach ($query->result() as $row) {
                $pid = $row->pdar_id;
                $pname = $row->pdar_name;
                $patta = $row->patta_no;
                $ptype = $row->patta_type_code;
                $dno = $row->dag_no;
                $vl = $pid . ',' . $pname . ',' . $patta . ',' . $ptype . ',' . $dno;
                $str = $str . '<tr><td><input type="checkbox" name="chk[]" id="chk[]" value="' . $vl . '"></td><td>' . $row->pdar_id . '</td><td>' . $row->pdar_name . '</td><tr>';
            }
        }
        $str = $str . '</table>';
        return $str;
    }

    public function pattadarinsdet($patta, $ptype, $dagno)
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
                $str = $str . '<tr><td>' . $row->pdar_id . '</td><td><a href=' . $base . 'index.php/Chithacontrol/pdaredit/' . $vll . ' title="Click here to edit pattadar details"><u>' . $row->pdar_name . '</u></a></td><tr>';
            }
        }
        $str = $str . '</table>';
        return $str;
    }

    public function pattadarexispid($pid, $pattano, $pattatype, $dagno)
    {
        $dcode = $this->session->userdata('dist_code');
        $scode = $this->session->userdata('subdiv_code');
        $ccode = $this->session->userdata('cir_code');
        $mcode = $this->session->userdata('mouza_pargona_code');
        $lcode = $this->session->userdata('lot_no');
        $vcode = $this->session->userdata('vill_townprt_code');
        $sql = "select a.pdar_id,a.pdar_gender,a.pdar_name,a.patta_no,a.patta_type_code,a.pdar_guard_reln,a.pdar_father,a.pdar_add1,a.pdar_add2,a.pdar_add3,a.pdar_pan_no,a.pdar_citizen_no,
b.dag_por_b,b.dag_por_k,b.dag_por_lc,b.dag_por_g,b.pdar_land_n,b.pdar_land_s,b.pdar_land_e,b.pdar_land_w,b.pdar_land_revenue,b.pdar_land_localtax,b.p_flag
from chitha_pattadar as a join chitha_dag_pattadar as b on a.patta_no=b.patta_no and a.patta_type_code=b.patta_type_code and a.dist_code=b.dist_code and a.subdiv_code=b.subdiv_code and a.cir_code=b.cir_code and a.mouza_pargona_code=b.mouza_pargona_code and a.lot_no=b.lot_no and a.vill_townprt_code=b.vill_townprt_code and a.pdar_id=b.pdar_id where a.pdar_id=$pid and b.dag_no='$dagno' and a.patta_no='$pattano' and a.patta_type_code='$pattatype' and a.dist_code='$dcode' and a.subdiv_code='$scode' and a.cir_code='$ccode' and a.mouza_pargona_code='$mcode' and a.lot_no='$lcode' and a.vill_townprt_code='$vcode' order by b.dag_no";

        $query = $this->db->query($sql);
        $row = $query->row_array();
        $pid = $row['pdar_id'];
        $pname = $row['pdar_name'];
        $prel = $row['pdar_guard_reln'];
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

        return $pdet = $pid . '$' . $pname . '$' . $prel . '$' . $pfath . '$' . $padd1 . '$' . $padd2 . '$' . $padd3 . '$' . $ppan . '$' . $pcit . '$' . $bigha . '$' . $katha . '$' . $lessa . '$' . $ganda . '$' . $landn . '$' . $lands . '$' . $lande . '$' . $landw . '$' . $lrev . '$' . $ltax . '$' . $pflag . '$' . $pGender;
    }

    public function insertexitdag($pid, $pname, $patta, $ptype)
    {

        $edagno = $this->input->post('edag');

        $dcode = $this->session->userdata('dist_code');
        $scode = $this->session->userdata('subdiv_code');
        $ccode = $this->session->userdata('cir_code');
        $mcode = $this->session->userdata('mouza_pargona_code');
        $lcode = $this->session->userdata('lot_no');
        $vcode = $this->session->userdata('vill_townprt_code');

        $where = "(dist_code='$dcode' and subdiv_code='$scode' and cir_code='$ccode' and mouza_pargona_code='$mcode' and lot_no='$lcode' and vill_townprt_code='$vcode' and  dag_no='$edagno' and pdar_id=$pid and patta_no='$patta' and patta_type_code='$ptype')";
        $this->db->select('dag_no,patta_no');
        $query = $this->db->get_where('chitha_dag_pattadar', $where);
        if ($query->num_rows() == 0) {

            $data['data'] = array(
                'dist_code' => $this->session->userdata('dist_code'),
                'subdiv_code' => $this->session->userdata('subdiv_code'),
                'cir_code' => $this->session->userdata('cir_code'),
                'mouza_pargona_code' => $this->session->userdata('mouza_pargona_code'),
                'lot_no' => $this->session->userdata('lot_no'),
                'vill_townprt_code' => $this->session->userdata('vill_townprt_code'),
                'dag_no' => $edagno,
                'pdar_id' => $pid,
                'patta_no' => $patta,
                'patta_type_code' => $ptype,
                'dag_por_b' => 0,
                'dag_por_k' => 0,
                'dag_por_lc' => 0,
                'dag_por_g' => 0,
                'pdar_land_n' => '',
                'pdar_land_s' => '',
                'pdar_land_e' => '',
                'pdar_land_w' => '',
                'pdar_land_acre' => 0,
                'pdar_land_revenue' => 0,
                'pdar_land_localtax' => 0,
                'user_code' => $this->session->userdata('usercode'),
                'date_entry' => date("Y-m-d | h:i:sa"),
                'operation' => 'E',
                'p_flag' => 0,
                'jama_yn' => 'n',

            );
            $data['data_1'] = $this->security->xss_clean($data['data']);
            $nrows = $this->db->insert('chitha_dag_pattadar', $data['data_1']);

            return $nrows;
        }
    }

    public function updatepattadar()
    {

        $dagno = $this->input->post('dag_no');
        $pid = $this->input->post('pdar_id');
        $patta = $this->input->post('patta_no');
        $ptype = $this->input->post('patta_type_code');

        $dcode = $this->session->userdata('dist_code');
        $scode = $this->session->userdata('subdiv_code');
        $ccode = $this->session->userdata('cir_code');
        $mcode = $this->session->userdata('mouza_pargona_code');
        $lcode = $this->session->userdata('lot_no');
        $vcode = $this->session->userdata('vill_townprt_code');

        //$where="(dist_code='$dcode' and subdiv_code='$scode' and cir_code='$ccode' and mouza_pargona_code='$mcode' and lot_no='$lcode' and vill_townprt_code='$vcode' and  dag_no='$edagno' and pdar_id=$pid and patta_no='$patta' and patta_type_code='$ptype')";
        //$this->db->select('dag_no,patta_no');
        //$query=$this->db->get_where('chitha_dag_pattadar',$where);
        //if($query->num_rows() == 0){

        $data['data'] = array(
            'dist_code' => $this->session->userdata('dist_code'),
            'subdiv_code' => $this->session->userdata('subdiv_code'),
            'cir_code' => $this->session->userdata('cir_code'),
            'mouza_pargona_code' => $this->session->userdata('mouza_pargona_code'),
            'lot_no' => $this->session->userdata('lot_no'),
            'vill_townprt_code' => $this->session->userdata('vill_townprt_code'),
            'dag_no' => $dagno,
            'pdar_id' => $pid,
            'patta_no' => $patta,
            'patta_type_code' => $ptype,
            'dag_por_b' => $this->input->post('dag_por_b') ? $this->input->post('dag_por_b') : 0,
            'dag_por_k' => $this->input->post('dag_por_k') ? $this->input->post('dag_por_k') : 0,
            'dag_por_lc' => $this->input->post('dag_por_lc') ? $this->input->post('dag_por_lc') : 0,
            'dag_por_g' => $this->input->post('dag_por_g') ? $this->input->post('dag_por_g') : 0,
            'pdar_land_n' => $this->input->post('pdar_land_n'),
            'pdar_land_s' => $this->input->post('pdar_land_s'),
            'pdar_land_e' => $this->input->post('pdar_land_e'),
            'pdar_land_w' => $this->input->post('pdar_land_w'),
            'pdar_land_acre' => 0,
            'pdar_land_revenue' => $this->input->post('pdar_land_revenue') ? $this->input->post('pdar_land_revenue') : 0,
            'pdar_land_localtax' => $this->input->post('pdar_land_localtax') ? $this->input->post('pdar_land_localtax') : 0,
            'p_flag' => $this->input->post('p_flag'),
            'jama_yn' => 'n',
        );
        $this->db->where(array('dist_code' => $dcode, 'subdiv_code' => $scode, 'cir_code' => $ccode, 'mouza_pargona_code' => $mcode, 'lot_no' => $lcode, 'vill_townprt_code' => $vcode, 'dag_no' => $dagno, 'pdar_id' => $pid, 'patta_no' => $patta, 'patta_type_code' => $ptype));
        $data['data_1'] = $this->security->xss_clean($data['data']);
        $nrows = $this->db->update('chitha_dag_pattadar', $data['data_1']);
        $data['data'] = array(
            'dist_code' => $this->session->userdata('dist_code'),
            'subdiv_code' => $this->session->userdata('subdiv_code'),
            'cir_code' => $this->session->userdata('cir_code'),
            'mouza_pargona_code' => $this->session->userdata('mouza_pargona_code'),
            'lot_no' => $this->session->userdata('lot_no'),
            'vill_townprt_code' => $this->session->userdata('vill_townprt_code'),
            'pdar_id' => $this->input->post('pdar_id'),
            'patta_no' => $this->input->post('patta_no'),
            'patta_type_code' => $this->input->post('patta_type_code'),
            'pdar_name' => $this->input->post('pdar_name'),
            'pdar_gender' => $this->input->post('p_gender'),
            'pdar_guard_reln' => $this->input->post('pdar_relation'),
            'pdar_father' => $this->input->post('pdar_father'),
            'pdar_add1' => $this->input->post('pdar_add1'),
            'pdar_add2' => $this->input->post('pdar_add2'),
            'pdar_add3' => $this->input->post('pdar_add3'),
            'pdar_pan_no' => $this->input->post('pdar_pan_no'),
            'pdar_citizen_no' => $this->input->post('pdar_citizen_no'),
            'jama_yn' => 'n',
        );
        if ($this->db->field_exists('pdar_relation', 'chitha_pattadar')) {
            $data['data']['pdar_relation'] = $this->input->post('pdar_relation');
        }
        $this->db->where(array('dist_code' => $dcode, 'subdiv_code' => $scode, 'cir_code' => $ccode, 'mouza_pargona_code' => $mcode, 'lot_no' => $lcode, 'vill_townprt_code' => $vcode, 'pdar_id' => $pid, 'patta_no' => $patta, 'patta_type_code' => $ptype));
        $data['data_1'] = $this->security->xss_clean($data['data']);
        $this->db->update('chitha_pattadar', $data['data_1']);
        $nrows = $this->db->affected_rows();
        return $nrows;
        //}

    }

    public function occupnm($patta_no, $pattatype, $dag_no)
    {

        $sql = "select a.pdar_id,a.pdar_name,a.pdar_father,a.pdar_relation,b.dag_por_b,b.dag_por_k,b.dag_por_lc from chitha_pattadar as a join chitha_dag_pattadar as b on a.patta_no=b.patta_no and a.patta_type_code=b.patta_type_code and a.dist_code=b.dist_code and a.subdiv_code=b.subdiv_code and a.cir_code=b.cir_code and a.mouza_pargona_code=b.mouza_pargona_code and a.lot_no=b.lot_no and a.vill_townprt_code=b.vill_townprt_code and a.pdar_id=b.pdar_id where b.dag_no='$dag_no' and a.patta_no='$patta_no' and a.patta_type_code='$pattatype'";

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function checkVillageInDharitree()
    {
        $dcode = $this->session->userdata('dist_code');
        $scode = $this->session->userdata('subdiv_code');
        $ccode = $this->session->userdata('cir_code');
        $mcode = $this->session->userdata('mouza_pargona_code');
        $lcode = $this->session->userdata('lot_no');
        $vcode = $this->session->userdata('vill_townprt_code');
        // $dag_no = $this->input->post('dag_no');
        // $patta_type_code = $this->input->post('patta_type_code');
        // $patta_no = $this->input->post('patta_no');

        $chitha_basic_data = callLandhubAPIForChithaCount('POST', 'getChithaBasicCount', [
            'dist_code' => $dcode,
            'subdiv_code' => $scode,
            'cir_code' => $ccode,
            'mouza_pargona_code' => $mcode,
            'lot_no' => $lcode,
            'vill_townprt_code' => $vcode
            // 'dag_no' => $dag_no,
            // 'patta_type_code' => $patta_type_code,
            // 'patta_no' =>$patta_no
        ]);
        if ($chitha_basic_data->responseType == 3) {
            return [
                'status' => 'FAILED',
                'responseType' => 3,
                'msg' => 'Api Validation Failed'
            ];
        } else if ($chitha_basic_data->responseType == 2) {
            if ($chitha_basic_data->data < 1 || $chitha_basic_data->data == null) {
                return [
                    'status' => 'SUCCESS',
                    'responseType' => 2,
                    'msg' => 'No Data in Dharitree',
                    'data' => $chitha_basic_data->data
                ];
            } else {
                return [
                    'status' => 'SUCCESS',
                    'responseType' => 1,
                    'msg' => 'Data Already available in dharitree',
                    'data' => $chitha_basic_data->data
                ];
            }
        }
    }

    public function insertdag($is_svamitva = false)
    {
        $dag_no = $this->input->post('dag_no');
        $dag_no_int = $dag_no * 100;
        $dcode = $this->session->userdata('dist_code');
        $scode = $this->session->userdata('subdiv_code');
        $ccode = $this->session->userdata('cir_code');
        $mcode = $this->session->userdata('mouza_pargona_code');
        $lcode = $this->session->userdata('lot_no');
        $vcode = $this->session->userdata('vill_townprt_code');

        $where = "(dist_code='$dcode' and subdiv_code='$scode' and cir_code='$ccode' and mouza_pargona_code='$mcode' and lot_no='$lcode' and vill_townprt_code='$vcode' and  dag_no='$dag_no')";
        $this->db->select('dag_no,patta_no');
        $query = $this->db->get_where('chitha_basic', $where);
        $is_lm_edit = $this->session->userdata('lm_edit');
        if ($is_lm_edit == 'Y') {
            $query_nc = $this->db->get_where('chitha_basic_nc', $where);
            if ($query_nc->num_rows() > 0) {
                $details = array(
                    'land_class_code' => $this->input->post('land_class_code'),
                    'dag_n_desc' => $this->input->post('dag_n_desc'),
                    'dag_s_desc' => $this->input->post('dag_s_desc'),
                    'dag_e_desc' => $this->input->post('dag_e_desc'),
                    'dag_w_desc' => $this->input->post('dag_w_desc'),
                    'dag_n_dag_no' => $this->input->post('dag_n_dag_no'),
                    'dag_s_dag_no' => $this->input->post('dag_s_dag_no'),
                    'dag_e_dag_no' => $this->input->post('dag_e_dag_no'),
                    'dag_w_dag_no' => $this->input->post('dag_w_dag_no'),
                    'user_code' => $this->session->userdata('usercode'),
                );
                $this->db->where(array('dist_code' => $dcode, 'subdiv_code' => $scode, 'cir_code' => $ccode, 'mouza_pargona_code' => $mcode, 'lot_no' => $lcode, 'vill_townprt_code' => $vcode, 'dag_no' => $dag_no));
                $data = $this->security->xss_clean($details);
                $nrows = $this->db->update('chitha_basic_nc', $data);
            }
        }
        if ($query->num_rows() == 0) {
            $details = array(
                'dist_code' => $dcode,
                'subdiv_code' => $scode,
                'cir_code' => $ccode,
                'mouza_pargona_code' => $mcode,
                'lot_no' => $lcode,
                'vill_townprt_code' => $vcode,
                'old_dag_no' => $this->input->post('old_dag_no'),
                'dag_no' => $dag_no,
                'dag_no_int' => $dag_no_int,
                'patta_type_code' => $this->input->post('patta_type_code'),
                'patta_no' => $this->input->post('patta_no'),
                'land_class_code' => $this->input->post('land_class_code'),
                'dag_area_b' => $this->input->post('dag_area_b'),
                'dag_area_k' => $this->input->post('dag_area_k'),
                'dag_area_lc' => $this->input->post('dag_area_lc'),
                'dag_area_g' => $this->input->post('dag_area_g') ? $this->input->post('dag_area_g') : 0,
                'dag_area_are' => $this->input->post('dag_area_r'),
                'dag_revenue' => $is_svamitva ? 0 : $this->input->post('dag_land_revenue'),
                'dag_local_tax' => $is_svamitva ? 0 : $this->input->post('dag_local_tax'),
                'dag_n_desc' => $this->input->post('dag_n_desc'),
                'dag_s_desc' => $this->input->post('dag_s_desc'),
                'dag_e_desc' => $this->input->post('dag_e_desc'),
                'dag_w_desc' => $this->input->post('dag_w_desc'),
                'dag_n_dag_no' => $this->input->post('dag_n_dag_no'),
                'dag_s_dag_no' => $this->input->post('dag_s_dag_no'),
                'dag_e_dag_no' => $this->input->post('dag_e_dag_no'),
                'dag_w_dag_no' => $this->input->post('dag_w_dag_no'),
                'dag_area_kr' => '00',
                'dag_nlrg_no' => $this->input->post('dag_nlrg_no'),
                'dp_flag_yn' => $this->input->post('dp_flag_yn'),
                'user_code' => $this->session->userdata('usercode'),
                'date_entry' => date("Y-m-d | h:i:sa"),
                'operation' => 'E',
                'jama_yn' => 'n',

                'old_patta_no' => $this->input->post('patta_no_old'),

            );
            if ($is_svamitva) {
                $details['status'] = 'S';
            }
            if ($this->input->post('zonal_value')) {
                $details['zonal_value'] = $this->input->post('zonal_value');
            }
            if ($this->input->post('police_station')) {
                $details['police_station'] = $this->input->post('police_station');
            }
            if ($this->input->post('revenue_paid_upto')) {
                $details['revenue_paid_upto'] = $this->input->post('revenue_paid');
            }
            if ($this->session->userdata('block_code')) {
                $details['block_code'] = $this->session->userdata('block_code');
            }
            if ($this->session->userdata('gram_panch_code')) {
                $details['gp_code'] = $this->session->userdata('gram_panch_code');
            }
            $data = $this->security->xss_clean($details);
            $nrows = $this->db->insert('chitha_basic', $details);
            return $nrows;
        } else {
            $details = array(
                'land_class_code' => $this->input->post('land_class_code'),
            );
            if ($is_lm_edit != 'Y') {
                $details = array(
                    'old_dag_no' => $this->input->post('old_dag_no'),
                    'patta_type_code' => $this->input->post('patta_type_code'),
                    'patta_no' => $this->input->post('patta_no'),
                    'land_class_code' => $this->input->post('land_class_code'),
                    'dag_area_b' => $this->input->post('dag_area_b'),
                    'dag_area_k' => $this->input->post('dag_area_k'),
                    'dag_area_lc' => $this->input->post('dag_area_lc'),
                    'dag_area_g' => $this->input->post('dag_area_g') ? $this->input->post('dag_area_g') : 0,
                    'dag_area_are' => $this->input->post('dag_area_r'),
                    'dag_revenue' => $is_svamitva ? 0 : $this->input->post('dag_land_revenue'),
                    'dag_local_tax' => $is_svamitva ? 0 : $this->input->post('dag_local_tax'),
                    'dag_n_desc' => $this->input->post('dag_n_desc'),
                    'dag_s_desc' => $this->input->post('dag_s_desc'),
                    'dag_e_desc' => $this->input->post('dag_e_desc'),
                    'dag_w_desc' => $this->input->post('dag_w_desc'),
                    'dag_n_dag_no' => $this->input->post('dag_n_dag_no'),
                    'dag_s_dag_no' => $this->input->post('dag_s_dag_no'),
                    'dag_e_dag_no' => $this->input->post('dag_e_dag_no'),
                    'dag_w_dag_no' => $this->input->post('dag_w_dag_no'),
                    'dag_area_kr' => '0',
                    'dag_nlrg_no' => $this->input->post('dag_nlrg_no'),
                    'dp_flag_yn' => $this->input->post('dp_flag_yn'),
                    'user_code' => $this->session->userdata('usercode'),
                    'date_entry' => date("Y-m-d | h:i:sa"),
    
                    'old_patta_no' => $this->input->post('patta_no_old'),
                    'jama_yn' => 'n',
    
                );
                if ($is_svamitva) {
                    $details['status'] = 'S';
                }
                if ($this->input->post('zonal_value')) {
                    $details['zonal_value'] = $this->input->post('zonal_value');
                }
                if ($this->input->post('police_station')) {
                    $details['police_station'] = $this->input->post('police_station');
                }
                if ($this->input->post('revenue_paid_upto')) {
                    $details['revenue_paid_upto'] = $this->input->post('revenue_paid');
                }
                if ($this->session->userdata('block_code')) {
                    $details['block_code'] = $this->session->userdata('block_code');
                }
                if ($this->session->userdata('gram_panch_code')) {
                    $details['gp_code'] = $this->session->userdata('gram_panch_code');
                }
            }
            $this->db->where(array('dist_code' => $dcode, 'subdiv_code' => $scode, 'cir_code' => $ccode, 'mouza_pargona_code' => $mcode, 'lot_no' => $lcode, 'vill_townprt_code' => $vcode, 'dag_no' => $dag_no));
            $data = $this->security->xss_clean($details);
            $nrows = $this->db->update('chitha_basic', $data);
            return $nrows;
        }
    }

    public function insertpattadar()
    {

        $data['data'] = array(
            'dist_code' => $this->session->userdata('dist_code'),
            'subdiv_code' => $this->session->userdata('subdiv_code'),
            'cir_code' => $this->session->userdata('cir_code'),
            'mouza_pargona_code' => $this->session->userdata('mouza_pargona_code'),
            'lot_no' => $this->session->userdata('lot_no'),
            'vill_townprt_code' => $this->session->userdata('vill_townprt_code'),
            'dag_no' => $this->input->post('dag_no', true),
            'pdar_id' => $this->input->post('pdar_id', true),
            'patta_no' => $this->input->post('patta_no', true),
            'patta_type_code' => $this->input->post('patta_type_code', true),
            'dag_por_b' => $this->input->post('dag_por_b') ? $this->input->post('dag_por_b', true) : 0,
            'dag_por_k' => $this->input->post('dag_por_k') ? $this->input->post('dag_por_k', true) : 0,
            'dag_por_lc' => $this->input->post('dag_por_lc') ? $this->input->post('dag_por_lc', true) : 0,
            'dag_por_g' => $this->input->post('dag_por_g') ? $this->input->post('dag_por_g', true) : 0,
            'pdar_land_n' => $this->input->post('pdar_land_n', true),
            'pdar_land_s' => $this->input->post('pdar_land_s', true),
            'pdar_land_e' => $this->input->post('pdar_land_e', true),
            'pdar_land_w' => $this->input->post('pdar_land_w', true),
            'pdar_land_acre' => $this->input->post('pdar_land_acre') ? $this->input->post('pdar_land_acre', true) : 0,
            'pdar_land_revenue' => $this->input->post('pdar_land_revenue') ? $this->input->post('pdar_land_revenue', true) : 0,
            'pdar_land_localtax' => $this->input->post('pdar_land_localtax') ? $this->input->post('pdar_land_localtax', true) : 0,
            'user_code' => $this->session->userdata('usercode'),
            'date_entry' => date("Y-m-d | h:i:sa"),
            'operation' => 'E',
            'p_flag' => $this->input->post('p_flag', true),
            'jama_yn' => 'n',

        );
        $data['data_1'] = $this->security->xss_clean($data['data']);
        $this->db->insert('chitha_dag_pattadar', $data['data_1']);

        $data['data3'] = array(
            'dist_code' => $this->session->userdata('dist_code'),
            'subdiv_code' => $this->session->userdata('subdiv_code'),
            'cir_code' => $this->session->userdata('cir_code'),
            'mouza_pargona_code' => $this->session->userdata('mouza_pargona_code'),
            'lot_no' => $this->session->userdata('lot_no'),
            'vill_townprt_code' => $this->session->userdata('vill_townprt_code'),
            'pdar_id' => $this->input->post('pdar_id', true),
            'patta_no' => $this->input->post('patta_no', true),
            'patta_type_code' => $this->input->post('patta_type_code', true),
            'pdar_name' => $this->input->post('pdar_name', true),
            'pdar_guard_reln' => $this->input->post('pdar_relation', true),
            'pdar_father' => $this->input->post('pdar_father', true),
            'pdar_add1' => $this->input->post('pdar_add1', true),
            'pdar_add2' => $this->input->post('pdar_add2', true),
            'pdar_add3' => $this->input->post('pdar_add3', true),
            'pdar_pan_no' => $this->input->post('pdar_pan_no', true),
            'pdar_citizen_no' => $this->input->post('pdar_citizen_no', true),
            'pdar_gender' => $this->input->post('p_gender', true),
            'user_code' => $this->session->userdata('usercode'),
            'date_entry' => date("Y-m-d | h:i:sa"),
            'operation' => 'o',
            'jama_yn' => 'n',
        );

        if ($this->db->field_exists('pdar_relation', 'chitha_pattadar')) {
            $data['data3']['pdar_relation'] = $this->input->post('pdar_relation', true);
        }

        $data['data_2'] = $this->security->xss_clean($data['data3']);
        $this->db->insert('chitha_pattadar', $data['data_2']);
        $nrows = $this->db->affected_rows();
        return $nrows;
    }

    public function insertcol8order()
    {

        $deed_value = $this->input->post('deed_value');
        if (!$deed_value) {
            $deed_value = '0.0000';
        }
        $deed_date = $this->input->post('deed_date');
        if (!$deed_date) {
            $deed_date = '1900-01-01';
        }
        $data['data'] = array(
            'dist_code' => $this->session->userdata('dist_code'),
            'subdiv_code' => $this->session->userdata('subdiv_code'),
            'cir_code' => $this->session->userdata('cir_code'),
            'mouza_pargona_code' => $this->session->userdata('mouza_pargona_code'),
            'lot_no' => $this->session->userdata('lot_no'),
            'vill_townprt_code' => $this->session->userdata('vill_townprt_code'),
            'dag_no' => $this->input->post('dag_no'),
            'col8order_cron_no' => $this->input->post('col8order_cron_no'),
            'order_pass_yn' => $this->input->post('order_pass_yn'),
            'order_type_code' => $this->input->post('order_type_code'),
            'nature_trans_code' => $this->input->post('nature_trans_code'),
            'lm_code' => $this->input->post('lm_code'),
            'lm_sign_yn' => $this->input->post('lm_sign_yn'),
            'lm_note_date' => $this->input->post('lm_note_date'),
            'co_code' => $this->input->post('co_code'),
            'co_sign_yn' => $this->input->post('co_sign_yn'),
            'co_ord_date' => $this->input->post('co_ord_date'),
            'user_code' => $this->session->userdata('usercode'),
            'date_entry' => date("Y-m-d | h:i:sa"),
            'operation' => 'E',
            'jama_updated' => 'n',
            'deed_reg_no' => $this->input->post('deed_reg_no'),
            'deed_value' => $deed_value,
            'deed_date' => $deed_date,
            'case_no' => $this->input->post('case_no'),
            'mut_land_area_b' => 0,
            'mut_land_area_k' => 0,
            'mut_land_area_lc' => 0,
            'mut_land_area_g' => 0,
            'mut_land_area_kr' => 0,
            'land_area_left_b' => 0,
            'land_area_left_k' => 0,
            'land_area_left_lc' => 0,
            'land_area_left_g' => 0,
            'land_area_left_kr' => 0,

        );
        $data['data_1'] = $this->security->xss_clean($data['data']);
        $this->db->insert('chitha_col8_order', $data['data_1']);
        $nrows = $this->db->affected_rows();
        return $nrows;
    }

    public function insertcol8occup()
    {
        $data['data'] = array(
            'dist_code' => $this->session->userdata('dist_code'),
            'subdiv_code' => $this->session->userdata('subdiv_code'),
            'cir_code' => $this->session->userdata('cir_code'),
            'mouza_pargona_code' => $this->session->userdata('mouza_pargona_code'),
            'lot_no' => $this->session->userdata('lot_no'),
            'vill_townprt_code' => $this->session->userdata('vill_townprt_code'),
            'dag_no' => $this->input->post('dag_no'),
            'col8order_cron_no' => $this->input->post('col8order_cron_no'),
            'occupant_id' => $this->input->post('occupant_id'),
            'occupant_name' => $this->input->post('occupantnm'),
            'occupant_fmh_name' => $this->input->post('occupant_fmh_name'),
            'occupant_fmh_flag' => $this->input->post('occupant_fmh_flag'),
            'occupant_add1' => $this->input->post('occupant_add1'),
            'occupant_add2' => $this->input->post('occupant_add2'),
            'occupant_add3' => $this->input->post('occupant_add3'),
            'land_area_b' => $this->input->post('land_area_b'),
            'land_area_k' => $this->input->post('land_area_k'),
            'land_area_lc' => $this->input->post('land_area_lc'),
            'land_area_g' => $this->input->post('land_area_g'),
            'land_area_kr' => 0,
            'old_patta_no' => $this->input->post('old_patta_no'),
            'new_patta_no' => $this->input->post('new_patta_no'),
            'old_dag_no' => $this->input->post('old_dag_no'),
            'new_dag_no' => $this->input->post('new_dag_no'),
            'user_code' => $this->session->userdata('usercode'),
            'date_entry' => date("Y-m-d | h:i:sa"),
            'operation' => 'E',
            'chitha_up' => 'n',

        );
        $data['data_1'] = $this->security->xss_clean($data['data']);
        $this->db->insert('chitha_col8_occup', $data['data_1']);
        $nrows = $this->db->affected_rows();
        return $nrows;
    }

    public function insertcol8inplace()
    {
        $data['data'] = array(
            'dist_code' => $this->session->userdata('dist_code'),
            'subdiv_code' => $this->session->userdata('subdiv_code'),
            'cir_code' => $this->session->userdata('cir_code'),
            'mouza_pargona_code' => $this->session->userdata('mouza_pargona_code'),
            'lot_no' => $this->session->userdata('lot_no'),
            'vill_townprt_code' => $this->session->userdata('vill_townprt_code'),
            'dag_no' => $this->input->post('dag_no'),
            'col8order_cron_no' => $this->input->post('col8order_cron_no'),
            'inplace_of_id' => $this->input->post('inplace_of_id'),
            'inplaceof_alongwith' => $this->input->post('inplaceof_alongwith'),
            'inplace_of_name' => $this->input->post('inplace_of_name'),
            'land_area_b' => $this->input->post('land_area_b'),
            'land_area_k' => $this->input->post('land_area_k'),
            'land_area_lc' => $this->input->post('land_area_lc'),
            'land_area_g' => $this->input->post('land_area_g'),
            'land_area_kr' => 0,
            'user_code' => $this->session->userdata('usercode'),
            'date_entry' => date("Y-m-d | h:i:sa"),
            'operation' => 'E',

        );
        $data['data_1'] = $this->security->xss_clean($data['data']);
        $this->db->insert('chitha_col8_inplace', $data['data_1']);
        $nrows = $this->db->affected_rows();
        return $nrows;
    }

    public function relname()
    {
        $this->db->select('guard_rel,guard_rel_desc_as');
        $query = $this->db->get_where('master_guard_rel');

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function tentype()
    {
        $this->db->select('type_code,tenant_type');
        $query = $this->db->get_where('tenant_type');

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function checktenantid()
    {
        $dist_code = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no = $this->session->userdata('lot_no');
        $vill_townprt_code = $this->session->userdata('vill_townprt_code');
        $dag_no = $this->session->userdata('dag_no');
        $where = "(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' and trim(dag_no)=trim('$dag_no'))";
        $this->db->select_max('tenant_id', 'max');
        $query = $this->db->get_where('chitha_tenant', $where);
        if ($query->num_rows() == 0) {
            return 1;
        }
        $max = $query->row()->max;
        return $max == 0 ? 1 : $max + 1;
    }

    public function inserttenant()
    {

        $details = array(
            'dist_code' => $this->session->userdata('dist_code'),
            'subdiv_code' => $this->session->userdata('subdiv_code'),
            'cir_code' => $this->session->userdata('cir_code'),
            'mouza_pargona_code' => $this->session->userdata('mouza_pargona_code'),
            'lot_no' => $this->session->userdata('lot_no'),
            'vill_townprt_code' => $this->session->userdata('vill_townprt_code'),
            'dag_no' => $this->session->userdata('dag_no'),
            'tenant_id' => $this->input->post('tenant_id'),
            'tenant_name' => $this->input->post('tenant_name'),
            'tenants_father' => $this->input->post('tenants_father'),
            'relation' => $this->input->post('guard_rel'),
            'tenants_add1' => $this->input->post('tenants_add1'),
            'tenants_add2' => $this->input->post('tenants_add2'),
            'tenants_add3' => $this->input->post('tenants_add3'),
            'type_of_tenant' => $this->input->post('type_of_tenant'),
            'khatian_no' => $this->input->post('khatian_no'),
            'revenue_tenant' => $this->input->post('revenue_tenant'),
            'crop_rate' => $this->input->post('crop_rate'),
            'user_code' => $this->session->userdata('usercode'),
            'date_entry' => date("Y-m-d | h:i:sa"),
            'operation' => 'E',
            'status' => 'O',
            // 'year_no' => '2021',
            'bigha' => $this->input->post('possession_area_b'),
            'katha' => $this->input->post('possession_area_k'),
            'lessa' => $this->input->post('possession_area_l'),
            'ganda' => $this->input->post('possession_area_g') ? $this->input->post('possession_area_g') : 0,
            'kranti' => $this->input->post('possession_area_k') ? $this->input->post('possession_area_k') : 0,
            'duration' => $this->input->post('possession_length'),
            'tenant_status' => $this->input->post('tenant_status'),
            'paid_cash_kind' => $this->input->post('paid_cash_kind'),
            'payable_cash_kind' => $this->input->post('payable_cash_kind'),
            'special_conditions' => $this->input->post('special_condition'),
            'remarks' => $this->input->post('remark'),
        );
        $data = $this->security->xss_clean($details);
        $nrows = $this->db->insert('chitha_tenant', $data);
        return $nrows;
    }

    public function tenidsub()
    {
        //$this->db->select('tenant_id,tenant_name');
        //$query=$this->db->get_where('chitha_tenant');
        $dist_code = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no = $this->session->userdata('lot_no');
        $vill_townprt_code = $this->session->userdata('vill_townprt_code');
        $dag_no = $this->session->userdata('dag_no');

        //$query = $this->db->query("select tenant_id,tenant_name from chitha_tenant");// where dist_code='24' and subdiv_code='01' and cir_code='01' and mouza_pargona_code='01' and lot_no='01' and vill_townprt_code='10001' and dag_no='01'");
        $query = $this->db->query("select tenant_id,tenant_name from chitha_tenant where dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' and trim(dag_no)=trim('$dag_no')");
        //$query="(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' and trim(dag_no)=trim('$dag_no'))";
        return $query->result();
    }

    public function checksubtenantid()
    {
        $dist_code = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no = $this->session->userdata('lot_no');
        $vill_townprt_code = $this->session->userdata('vill_townprt_code');
        $dag_no = $this->session->userdata('dag_no');
        $where = "(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' and trim(dag_no)=trim('$dag_no'))";
        $this->db->select_max('subtenant_id', 'max');
        $query = $this->db->get_where('chitha_subtenant', $where);
        if ($query->num_rows() == 0) {
            return 1;
        }
        $max = $query->row()->max;
        return $max == 0 ? 1 : $max + 1;
    }

    public function insertsubtenant()
    {

        $details = array(
            'dist_code' => $this->session->userdata('dist_code'),
            'subdiv_code' => $this->session->userdata('subdiv_code'),
            'cir_code' => $this->session->userdata('cir_code'),
            'mouza_pargona_code' => $this->session->userdata('mouza_pargona_code'),
            'lot_no' => $this->session->userdata('lot_no'),
            'vill_townprt_code' => $this->session->userdata('vill_townprt_code'),
            'dag_no' => $this->session->userdata('dag_no'),
            'subtenant_id' => $this->input->post('subtenant_id'),
            'tenant_id' => $this->input->post('tenantid'),
            'subtenant_name' => $this->input->post('subtennm'),
            'subtenants_father' => $this->input->post('subtenants_father'),
            'relation' => $this->input->post('guard_rel'),
            'subtenants_add1' => $this->input->post('subtenants_add1'),
            'subtenants_add2' => $this->input->post('subtenants_add2'),
            'subtenants_add3' => $this->input->post('subtenants_add3'),
            'user_code' => $this->session->userdata('usercode'),
            'date_entry' => date("Y-m-d | h:i:sa"),
            'operation' => 'E',
            // 'year_no' => '2021',
        );
        $data = $this->security->xss_clean($details);
        $nrows = $this->db->insert('chitha_subtenant', $details);
        return $nrows;
    }

    public function cropname()
    {
        $this->db->select('crop_code,crop_name');
        $query = $this->db->get_where('crop_code');

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function cropcat()
    {
        $this->db->select('crop_categ_code,crop_categ_desc');
        $query = $this->db->get_where('crop_category_code');

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function cropseason()
    {
        $this->db->select('season_code,crop_season');
        $query = $this->db->get_where('crop_season');

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function watersource()
    {
        $this->db->select('water_source_code,source');
        $query = $this->db->get_where('source_water');

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function checkcropid()
    {
        $dist_code = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no = $this->session->userdata('lot_no');
        $vill_townprt_code = $this->session->userdata('vill_townprt_code');
        $dag_no = $this->session->userdata('dag_no');
        $where = "(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' and trim(dag_no)=trim('$dag_no'))";
        $this->db->select_max('crop_sl_no', 'max');
        $query = $this->db->get_where('chitha_mcrop', $where);
        if ($query->num_rows() == 0) {
            return 1;
        }
        $max = $query->row()->max;
        return $max == 0 ? 1 : $max + 1;
    }

    public function insertcrop()
    {
        $details = array(
            'dist_code' => $this->session->userdata('dist_code'),
            'subdiv_code' => $this->session->userdata('subdiv_code'),
            'cir_code' => $this->session->userdata('cir_code'),
            'mouza_pargona_code' => $this->session->userdata('mouza_pargona_code'),
            'lot_no' => $this->session->userdata('lot_no'),
            'vill_townprt_code' => $this->session->userdata('vill_townprt_code'),
            'dag_no' => $this->session->userdata('dag_no'),
            'crop_sl_no' => $this->input->post('cropslno'),
            'yearno' => $this->input->post('yearno'),
            'crop_code' => $this->input->post('cropname'),
            'crop_season' => $this->input->post('cropseason'),
            'source_of_water' => $this->input->post('sourcewater'),
            'crop_land_area_b' => $this->input->post('croparea_b'),
            'crop_land_area_k' => $this->input->post('croparea_k'),
            'crop_land_area_lc' => $this->input->post('croparea_lc'),
            'crop_land_area_g' => 0,
            'crop_land_area_kr' => 0,
            'user_code' => $this->session->userdata('usercode'),
            'date_entry' => date("Y-m-d | h:i:sa"),
            'operation' => 'E',
            'crop_categ_code' => $this->input->post('cropcatg'),
        );
        //var_dump($details);
        $data = $this->security->xss_clean($details);
        $nrows = $this->db->insert('chitha_mcrop', $details);
        return $nrows;
    }

    public function ncropname()
    {
        $this->db->select('used_noncrop_type_code,noncrop_type');
        $query = $this->db->get_where('used_noncrop_type');

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function checknoncropid()
    {
        $dist_code = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no = $this->session->userdata('lot_no');
        $vill_townprt_code = $this->session->userdata('vill_townprt_code');
        $dag_no = $this->session->userdata('dag_no');
        $where = "(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' and trim(dag_no)=trim('$dag_no'))";
        $this->db->select_max('noncrop_use_id', 'max');
        $query = $this->db->get_where('chitha_noncrop', $where);
        if ($query->num_rows() == 0) {
            return 1;
        }
        $max = $query->row()->max;
        return $max == 0 ? 1 : $max + 1;
    }

    public function insertnoncrop()
    {
        $details = array(
            'dist_code' => $this->session->userdata('dist_code'),
            'subdiv_code' => $this->session->userdata('subdiv_code'),
            'cir_code' => $this->session->userdata('cir_code'),
            'mouza_pargona_code' => $this->session->userdata('mouza_pargona_code'),
            'lot_no' => $this->session->userdata('lot_no'),
            'vill_townprt_code' => $this->session->userdata('vill_townprt_code'),
            'dag_no' => $this->session->userdata('dag_no'),
            'noncrop_use_id' => $this->input->post('ncropslno'),
            'yn' => $this->input->post('yearno'),
            'type_of_used_noncrop' => $this->input->post('ncropcode'),
            'noncrop_land_area_b' => $this->input->post('ncroparea_b'),
            'noncrop_land_area_k' => $this->input->post('ncroparea_k'),
            'noncrop_land_area_lc' => $this->input->post('ncroparea_lc'),
            'noncrop_land_area_g' => 0,
            'noncrop_land_area_kr' => 0,
            'user_code' => $this->session->userdata('usercode'),
            'date_entry' => date("Y-m-d | h:i:sa"),
            'operation' => 'E',
        );
        //var_dump($details);
        $data = $this->security->xss_clean($details);
        $nrows = $this->db->insert('chitha_noncrop', $details);
        return $nrows;
    }

    public function fruitname()
    {
        $this->db->select('fruit_code,fruit_name');
        $query = $this->db->get_where('fruit_tree_code');

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function checkfruitid()
    {
        $dist_code = $this->session->userdata('dist_code');
        $subdiv_code = $this->session->userdata('subdiv_code');
        $cir_code = $this->session->userdata('cir_code');
        $mouza_pargona_code = $this->session->userdata('mouza_pargona_code');
        $lot_no = $this->session->userdata('lot_no');
        $vill_townprt_code = $this->session->userdata('vill_townprt_code');
        $dag_no = $this->session->userdata('dag_no');
        $where = "(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' and trim(dag_no)=trim('$dag_no'))";
        $this->db->select_max('fruit_plant_id', 'max');
        $query = $this->db->get_where('chitha_fruit', $where);
        if ($query->num_rows() == 0) {
            return 1;
        }
        $max = $query->row()->max;
        return $max == 0 ? 1 : $max + 1;
    }

    public function insertfruit()
    {
        $details = array(
            'dist_code' => $this->session->userdata('dist_code'),
            'subdiv_code' => $this->session->userdata('subdiv_code'),
            'cir_code' => $this->session->userdata('cir_code'),
            'mouza_pargona_code' => $this->session->userdata('mouza_pargona_code'),
            'lot_no' => $this->session->userdata('lot_no'),
            'vill_townprt_code' => $this->session->userdata('vill_townprt_code'),
            'dag_no' => $this->session->userdata('dag_no'),
            'fruit_plant_id' => $this->input->post('frplantid'),
            'fruit_plants_name' => $this->input->post('frname'),
            'no_of_plants' => $this->input->post('fplantno'),
            //'fruit_land_area_b'=> 0,
            //'fruit_land_area_k'=> 0,
            //'fruit_land_area_lc'=> 0,
            //'fruit_land_area_g'=> 0,
            //'fruit_land_area_kr'=> 0,
            'user_code' => $this->session->userdata('usercode'),
            'date_entry' => date("Y-m-d | h:i:sa"),
            'operation' => 'E',
        );
        //var_dump($details);
        $data = $this->security->xss_clean($details);
        $nrows = $this->db->insert('chitha_fruit', $details);
        return $nrows;
    }

    public function getlocationnames($dist, $sub, $cir, $mouza, $lot, $village)
    {

        $location['dist_name'] = $this->db->select('loc_name')->where(array('dist_code' => $dist, 'subdiv_code' => '00', 'cir_code' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->get('location')->row_array();
        $location['subdiv_name'] = $this->db->select('loc_name')->where(array('dist_code' => $dist, 'subdiv_code' => $sub, 'cir_code' => '00', 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->get('location')->row_array();
        $location['cir_name'] = $this->db->select('loc_name')->where(array('dist_code' => $dist, 'subdiv_code' => $sub, 'cir_code' => $cir, 'mouza_pargona_code' => '00', 'lot_no' => '00', 'vill_townprt_code' => '00000'))->get('location')->row_array();
        $location['mouza_name'] = $this->db->select('loc_name')->where(array('dist_code' => $dist, 'subdiv_code' => $sub, 'cir_code' => $cir, 'mouza_pargona_code' => $mouza, 'lot_no' => '00', 'vill_townprt_code' => '00000'))->get('location')->row_array();
        $location['lot'] = $this->db->select('loc_name')->where(array('dist_code' => $dist, 'subdiv_code' => $sub, 'cir_code' => $cir, 'mouza_pargona_code' => $mouza, 'lot_no' => $lot, 'vill_townprt_code' => '00000'))->get('location')->row_array();
        $location['village'] = $this->db->select('loc_name')->where(array('dist_code' => $dist, 'subdiv_code' => $sub, 'cir_code' => $cir, 'mouza_pargona_code' => $mouza, 'lot_no' => $lot, 'vill_townprt_code' => $village))->get('location')->row_array();

        return $location;
    }

    public function gettenants()
    {
        $dcode = $this->session->userdata('dist_code');
        $scode = $this->session->userdata('subdiv_code');
        $ccode = $this->session->userdata('cir_code');
        $mcode = $this->session->userdata('mouza_pargona_code');
        $lcode = $this->session->userdata('lot_no');
        $vcode = $this->session->userdata('vill_townprt_code');
        $dagno = $this->session->userdata('dag_no');
        $where = "(dist_code='$dcode' and subdiv_code='$scode' and cir_code='$ccode' and mouza_pargona_code='$mcode' and lot_no='$lcode' and vill_townprt_code='$vcode' and dag_no='$dagno')";
        $this->db->select('tenant_id,tenant_name,tenants_father,dist_code,subdiv_code,cir_code,mouza_pargona_code,lot_no,vill_townprt_code,dag_no');
        $this->db->order_by('tenant_id');
        $query = $this->db->get_where('chitha_tenant', $where);
        return $query->result();
    }

    public function idtendet($nm)
    {
        $tn = explode("-", $nm);
        $tid = $tn[0];
        $dagno = $tn[1];
        $dcode = $tn[2];
        $scode = $tn[3];
        $ccode = $tn[4];
        $mcode = $tn[5];
        $lcode = $tn[6];
        $vcode = $tn[7];

        $where = "(dist_code='$dcode' and subdiv_code='$scode' and cir_code='$ccode' and mouza_pargona_code='$mcode' and lot_no='$lcode' and vill_townprt_code='$vcode' and dag_no='$dagno' and tenant_id=$tid)";
        $this->db->select('tenant_id,tenant_name,tenants_father,relation,tenants_add1,tenants_add2,tenants_add3,type_of_tenant,khatian_no,revenue_tenant,crop_rate');
        $query = $this->db->get_where('chitha_tenant', $where);
        //return $query->result();
        $row = $query->row_array();
        $tnid = $row['tenant_id'];
        $tnme = $row['tenant_name'];
        $tfme = $row['tenants_father'];
        $trel = $row['relation'];
        $tad1 = $row['tenants_add1'];
        $tad2 = $row['tenants_add2'];
        $tad3 = $row['tenants_add3'];
        $ttyp = $row['type_of_tenant'];
        $khno = $row['khatian_no'];
        $trev = $row['revenue_tenant'];
        $crte = $row['crop_rate'];
        return $tdet = $tnid . '$' . $tnme . '$' . $tfme . '$' . $trel . '$' . $tad1 . '$' . $tad2 . '$' . $tad3 . '$' . $ttyp . '$' . $khno . '$' . $trev . '$' . $crte;
    }

    public function getTenant($loc)
    {
        $tn = explode("-", $loc);
        $tid = $tn[0];
        $dagno = $tn[1];
        $dcode = $tn[2];
        $scode = $tn[3];
        $ccode = $tn[4];
        $mcode = $tn[5];
        $lcode = $tn[6];
        $vcode = $tn[7];

        $where = "(dist_code='$dcode' and subdiv_code='$scode' and cir_code='$ccode' and mouza_pargona_code='$mcode' and lot_no='$lcode' and vill_townprt_code='$vcode' and dag_no='$dagno' and tenant_id=$tid)";
        $this->db->select('tenant_id,tenant_name,tenants_father,relation,tenants_add1,tenants_add2,tenants_add3,type_of_tenant,khatian_no,revenue_tenant,crop_rate,bigha,katha,lessa,ganda,kranti,duration,tenant_status,paid_cash_kind,payable_cash_kind,special_conditions,remarks');
        $query = $this->db->get_where('chitha_tenant', $where);
        return $query->row();
    }

    public function updatetenant()
    {
        $dcode = $this->session->userdata('dist_code');
        $scode = $this->session->userdata('subdiv_code');
        $ccode = $this->session->userdata('cir_code');
        $mcode = $this->session->userdata('mouza_pargona_code');
        $lcode = $this->session->userdata('lot_no');
        $vcode = $this->session->userdata('vill_townprt_code');
        $dagno = $this->session->userdata('dag_no');
        $tenid = $this->input->post('tenant_id');

        $details = array(
            'tenant_name' => $this->input->post('tenant_name'),
            'tenants_father' => $this->input->post('tenants_father'),
            'relation' => $this->input->post('guard_rel'),
            'tenants_add1' => $this->input->post('tenants_add1'),
            'tenants_add2' => $this->input->post('tenants_add2'),
            'tenants_add3' => $this->input->post('tenants_add3'),
            'type_of_tenant' => $this->input->post('type_of_tenant'),
            'khatian_no' => $this->input->post('khatian_no'),
            'revenue_tenant' => $this->input->post('revenue_tenant'),
            'crop_rate' => $this->input->post('crop_rate'),
            'bigha' => $this->input->post('possession_area_b'),
            'katha' => $this->input->post('possession_area_k'),
            'lessa' => $this->input->post('possession_area_l'),
            'ganda' => $this->input->post('possession_area_g') ? $this->input->post('possession_area_g') : 0,
            'kranti' => $this->input->post('possession_area_k') ? $this->input->post('possession_area_k') : 0,
            'duration' => $this->input->post('possession_length'),
            'tenant_status' => $this->input->post('tenant_status'),
            'paid_cash_kind' => $this->input->post('paid_cash_kind'),
            'payable_cash_kind' => $this->input->post('payable_cash_kind'),
            'special_conditions' => $this->input->post('special_condition'),
            'remarks' => $this->input->post('remark'),
        );
        $this->db->where(array('dist_code' => $dcode, 'subdiv_code' => $scode, 'cir_code' => $ccode, 'mouza_pargona_code' => $mcode, 'lot_no' => $lcode, 'vill_townprt_code' => $vcode, 'dag_no' => $dagno, 'tenant_id' => $tenid));
        $data = $this->security->xss_clean($details);
        $nrows = $this->db->update('chitha_tenant', $data);
        return $nrows;
    }

    // ******* newly by Masud Reza 11/05/2022

    public function getSubTenants()
    {
        $dcode = $this->session->userdata('dist_code');
        $scode = $this->session->userdata('subdiv_code');
        $ccode = $this->session->userdata('cir_code');
        $mcode = $this->session->userdata('mouza_pargona_code');
        $lcode = $this->session->userdata('lot_no');
        $vcode = $this->session->userdata('vill_townprt_code');
        $dagno = $this->session->userdata('dag_no');
        $where = "(dist_code='$dcode' and subdiv_code='$scode' and cir_code='$ccode' and mouza_pargona_code='$mcode' and lot_no='$lcode' and vill_townprt_code='$vcode' and dag_no='$dagno')";
        $this->db->select('chitha_subtenant.*');
        $this->db->order_by('subtenant_id');
        $query = $this->db->get_where('chitha_subtenant', $where);
        return $query->result();
    }

    public function idSubTenantDetails($nm)
    {
        $tn = explode("-", $nm);
        $tid = $tn[0];
        $dagno = $tn[1];
        $dcode = $tn[2];
        $scode = $tn[3];
        $ccode = $tn[4];
        $mcode = $tn[5];
        $lcode = $tn[6];
        $vcode = $tn[7];

        $where = "(dist_code='$dcode' and subdiv_code='$scode' and cir_code='$ccode' and mouza_pargona_code='$mcode' and lot_no='$lcode' and vill_townprt_code='$vcode' and dag_no='$dagno' and subtenant_id=$tid)";
        $this->db->select('chitha_subtenant.*');
        $query = $this->db->get_where('chitha_subtenant', $where);
        //return $query->result();
        $row = $query->row_array();
        $tnid = $row['subtenant_id'];
        $tnme = $row['subtenant_name'];
        $tfme = $row['subtenants_father'];
        $trel = $row['relation'];
        $tad1 = $row['subtenants_add1'];
        $tad2 = $row['subtenants_add2'];
        $tad3 = $row['subtenants_add3'];
        return $tdet = $tnid . '$' . $tnme . '$' . $tfme . '$' . $trel . '$' . $tad1 . '$' . $tad2 . '$' . $tad3;
    }

    public function updateSubTenant()
    {
        $dcode = $this->session->userdata('dist_code');
        $scode = $this->session->userdata('subdiv_code');
        $ccode = $this->session->userdata('cir_code');
        $mcode = $this->session->userdata('mouza_pargona_code');
        $lcode = $this->session->userdata('lot_no');
        $vcode = $this->session->userdata('vill_townprt_code');
        $dagno = $this->session->userdata('dag_no');
        $tenid = $this->input->post('subTenantId');

        $details = array(
            'subtenant_name' => $this->input->post('subTenantName'),
            'subtenants_father' => $this->input->post('subTenantsFather'),
            'relation' => $this->input->post('guard_rel'),
            'subtenants_add1' => $this->input->post('subTenants_add1'),
            'subtenants_add2' => $this->input->post('subTenants_add2'),
            'subtenants_add3' => $this->input->post('subTenants_add3'),

        );
        $this->db->where(array('dist_code' => $dcode, 'subdiv_code' => $scode, 'cir_code' => $ccode, 'mouza_pargona_code' => $mcode, 'lot_no' => $lcode, 'vill_townprt_code' => $vcode, 'dag_no' => $dagno, 'subtenant_id' => $tenid));
        $data = $this->security->xss_clean($details);
        $nrows = $this->db->update('chitha_subtenant', $data);
        return $nrows;
    }

    public function getCropList()
    {
        $dcode = $this->session->userdata('dist_code');
        $scode = $this->session->userdata('subdiv_code');
        $ccode = $this->session->userdata('cir_code');
        $mcode = $this->session->userdata('mouza_pargona_code');
        $lcode = $this->session->userdata('lot_no');
        $vcode = $this->session->userdata('vill_townprt_code');
        $dagno = $this->session->userdata('dag_no');

        $this->db->select('chitha_mcrop.*,crop_code.crop_name,
        crop_category_code.crop_categ_desc,crop_season.crop_season');
        $this->db->where('chitha_mcrop.dist_code', $dcode);
        $this->db->where('chitha_mcrop.subdiv_code', $scode);
        $this->db->where('chitha_mcrop.cir_code', $ccode);
        $this->db->where('chitha_mcrop.mouza_pargona_code', $mcode);
        $this->db->where('chitha_mcrop.lot_no', $lcode);
        $this->db->where('chitha_mcrop.vill_townprt_code', $vcode);
        $this->db->where('chitha_mcrop.dag_no', $dagno);
        $this->db->from('chitha_mcrop');
        $this->db->join('crop_code', 'crop_code.crop_code=chitha_mcrop.crop_code');
        $this->db->join('crop_category_code', 'crop_category_code.crop_categ_code=chitha_mcrop.crop_categ_code');
        $this->db->join('crop_season', 'crop_season.season_code=chitha_mcrop.crop_season');
        $this->db->order_by('chitha_mcrop.crop_sl_no', 'asc');
        $allCrop = $this->db->get();
        return $allCrop->result();
    }

    public function cropDetailsWithId($crId)
    {
        $tn = explode("-", $crId);
        $tid = $tn[0];
        $dagno = $tn[1];
        $dcode = $tn[2];
        $scode = $tn[3];
        $ccode = $tn[4];
        $mcode = $tn[5];
        $lcode = $tn[6];
        $vcode = $tn[7];

        $this->db->select('chitha_mcrop.*');
        $this->db->where('chitha_mcrop.dist_code', $dcode);
        $this->db->where('chitha_mcrop.subdiv_code', $scode);
        $this->db->where('chitha_mcrop.cir_code', $ccode);
        $this->db->where('chitha_mcrop.mouza_pargona_code', $mcode);
        $this->db->where('chitha_mcrop.lot_no', $lcode);
        $this->db->where('chitha_mcrop.vill_townprt_code', $vcode);
        $this->db->where('chitha_mcrop.dag_no', $dagno);
        $this->db->where('chitha_mcrop.crop_sl_no', $tid);
        $this->db->from('chitha_mcrop');
        $row = $this->db->get()->row();

        return $row;
    }

    public function updateCropDetails()
    {
        $dcode = $this->session->userdata('dist_code');
        $scode = $this->session->userdata('subdiv_code');
        $ccode = $this->session->userdata('cir_code');
        $mcode = $this->session->userdata('mouza_pargona_code');
        $lcode = $this->session->userdata('lot_no');
        $vcode = $this->session->userdata('vill_townprt_code');
        $dagno = $this->session->userdata('dag_no');
        $cropId = $this->input->post('cropslno');

        $details = array(
            'yearno' => $this->input->post('yearno'),
            'crop_code' => $this->input->post('cropname'),
            'crop_season' => $this->input->post('cropseason'),
            'source_of_water' => $this->input->post('sourcewater'),
            'crop_land_area_b' => $this->input->post('croparea_b'),
            'crop_land_area_k' => $this->input->post('croparea_k'),
            'crop_land_area_lc' => $this->input->post('croparea_lc'),
            'crop_categ_code' => $this->input->post('cropcatg'),
        );

        $this->db->where(array(
            'dist_code' => $dcode,
            'subdiv_code' => $scode,
            'cir_code' => $ccode,
            'mouza_pargona_code' => $mcode,
            'lot_no' => $lcode,
            'vill_townprt_code' => $vcode,
            'dag_no' => $dagno,
            'crop_sl_no' => $cropId,
        ));
        $data = $this->security->xss_clean($details);
        $nrows = $this->db->update('chitha_mcrop', $data);
        return $nrows;
    }

    public function getNonCropList()
    {
        $dcode = $this->session->userdata('dist_code');
        $scode = $this->session->userdata('subdiv_code');
        $ccode = $this->session->userdata('cir_code');
        $mcode = $this->session->userdata('mouza_pargona_code');
        $lcode = $this->session->userdata('lot_no');
        $vcode = $this->session->userdata('vill_townprt_code');
        $dagno = $this->session->userdata('dag_no');

        $this->db->select('chitha_noncrop.*,used_noncrop_type.noncrop_type,');
        $this->db->where('chitha_noncrop.dist_code', $dcode);
        $this->db->where('chitha_noncrop.subdiv_code', $scode);
        $this->db->where('chitha_noncrop.cir_code', $ccode);
        $this->db->where('chitha_noncrop.mouza_pargona_code', $mcode);
        $this->db->where('chitha_noncrop.lot_no', $lcode);
        $this->db->where('chitha_noncrop.vill_townprt_code', $vcode);
        $this->db->where('chitha_noncrop.dag_no', $dagno);
        $this->db->from('chitha_noncrop');
        $this->db->join('used_noncrop_type', 'used_noncrop_type.used_noncrop_type_code=chitha_noncrop.type_of_used_noncrop');
        $this->db->order_by('chitha_noncrop.noncrop_use_id', 'asc');
        $allNonCrop = $this->db->get();
        return $allNonCrop->result();
    }

    public function nonCropDetailsWithId($ncId)
    {
        $tn = explode("-", $ncId);
        $tid = $tn[0];
        $dagno = $tn[1];
        $dcode = $tn[2];
        $scode = $tn[3];
        $ccode = $tn[4];
        $mcode = $tn[5];
        $lcode = $tn[6];
        $vcode = $tn[7];

        $this->db->select('chitha_noncrop.*');
        $this->db->where('chitha_noncrop.dist_code', $dcode);
        $this->db->where('chitha_noncrop.subdiv_code', $scode);
        $this->db->where('chitha_noncrop.cir_code', $ccode);
        $this->db->where('chitha_noncrop.mouza_pargona_code', $mcode);
        $this->db->where('chitha_noncrop.lot_no', $lcode);
        $this->db->where('chitha_noncrop.vill_townprt_code', $vcode);
        $this->db->where('chitha_noncrop.dag_no', $dagno);
        $this->db->where('chitha_noncrop.noncrop_use_id', $tid);
        $this->db->from('chitha_noncrop');
        $row = $this->db->get()->row();

        return $row;
    }

    public function updateNonCropDetails()
    {
        $dcode = $this->session->userdata('dist_code');
        $scode = $this->session->userdata('subdiv_code');
        $ccode = $this->session->userdata('cir_code');
        $mcode = $this->session->userdata('mouza_pargona_code');
        $lcode = $this->session->userdata('lot_no');
        $vcode = $this->session->userdata('vill_townprt_code');
        $dagno = $this->session->userdata('dag_no');
        $ncId = $this->input->post('ncropslno');

        $details = array(
            'yn' => $this->input->post('yearno'),
            'type_of_used_noncrop' => $this->input->post('ncropcode'),
            'noncrop_land_area_b' => $this->input->post('ncroparea_b'),
            'noncrop_land_area_k' => $this->input->post('ncroparea_k'),
            'noncrop_land_area_lc' => $this->input->post('ncroparea_lc'),
        );

        $this->db->where(array(
            'dist_code' => $dcode,
            'subdiv_code' => $scode,
            'cir_code' => $ccode,
            'mouza_pargona_code' => $mcode,
            'lot_no' => $lcode,
            'vill_townprt_code' => $vcode,
            'dag_no' => $dagno,
            'noncrop_use_id' => $ncId,
        ));
        $data = $this->security->xss_clean($details);
        $nrows = $this->db->update('chitha_noncrop', $data);
        return $nrows;
    }

    public function getFruitList()
    {
        $dcode = $this->session->userdata('dist_code');
        $scode = $this->session->userdata('subdiv_code');
        $ccode = $this->session->userdata('cir_code');
        $mcode = $this->session->userdata('mouza_pargona_code');
        $lcode = $this->session->userdata('lot_no');
        $vcode = $this->session->userdata('vill_townprt_code');
        $dagno = $this->session->userdata('dag_no');

        $this->db->select('chitha_fruit.*,fruit_tree_code.fruit_name,');
        $this->db->where('chitha_fruit.dist_code', $dcode);
        $this->db->where('chitha_fruit.subdiv_code', $scode);
        $this->db->where('chitha_fruit.cir_code', $ccode);
        $this->db->where('chitha_fruit.mouza_pargona_code', $mcode);
        $this->db->where('chitha_fruit.lot_no', $lcode);
        $this->db->where('chitha_fruit.vill_townprt_code', $vcode);
        $this->db->where('chitha_fruit.dag_no', $dagno);
        $this->db->from('chitha_fruit');
        $this->db->join('fruit_tree_code', 'fruit_tree_code.fruit_code=chitha_fruit.fruit_plants_name');
        $this->db->order_by('chitha_fruit.fruit_plant_id', 'asc');
        $allNonCrop = $this->db->get();
        return $allNonCrop->result();
    }

    public function fruitDetailsWithId($fId)
    {
        $tn = explode("-", $fId);
        $tid = $tn[0];
        $dagno = $tn[1];
        $dcode = $tn[2];
        $scode = $tn[3];
        $ccode = $tn[4];
        $mcode = $tn[5];
        $lcode = $tn[6];
        $vcode = $tn[7];

        $this->db->select('chitha_fruit.*');
        $this->db->where('chitha_fruit.dist_code', $dcode);
        $this->db->where('chitha_fruit.subdiv_code', $scode);
        $this->db->where('chitha_fruit.cir_code', $ccode);
        $this->db->where('chitha_fruit.mouza_pargona_code', $mcode);
        $this->db->where('chitha_fruit.lot_no', $lcode);
        $this->db->where('chitha_fruit.vill_townprt_code', $vcode);
        $this->db->where('chitha_fruit.dag_no', $dagno);
        $this->db->where('chitha_fruit.fruit_plant_id', $tid);
        $this->db->from('chitha_fruit');
        $row = $this->db->get()->row();

        return $row;
    }

    public function updateFruitDetails()
    {
        $dcode = $this->session->userdata('dist_code');
        $scode = $this->session->userdata('subdiv_code');
        $ccode = $this->session->userdata('cir_code');
        $mcode = $this->session->userdata('mouza_pargona_code');
        $lcode = $this->session->userdata('lot_no');
        $vcode = $this->session->userdata('vill_townprt_code');
        $dagno = $this->session->userdata('dag_no');
        $frId = $this->input->post('frplantid');

        $details = array(
            'fruit_plants_name' => $this->input->post('frname'),
            'no_of_plants' => $this->input->post('fplantno'),
        );

        $this->db->where(array(
            'dist_code' => $dcode,
            'subdiv_code' => $scode,
            'cir_code' => $ccode,
            'mouza_pargona_code' => $mcode,
            'lot_no' => $lcode,
            'vill_townprt_code' => $vcode,
            'dag_no' => $dagno,
            'fruit_plant_id' => $frId,
        ));
        $data = $this->security->xss_clean($details);
        $nrows = $this->db->update('chitha_fruit', $data);
        return $nrows;
    }

    // 18/05/2022 Masud Reza
    public function landDetails()
    {
        $dcode = $this->session->userdata('dist_code');
        $scode = $this->session->userdata('subdiv_code');
        $ccode = $this->session->userdata('cir_code');
        $mcode = $this->session->userdata('mouza_pargona_code');
        $lcode = $this->session->userdata('lot_no');
        $vcode = $this->session->userdata('vill_townprt_code');
        $dagno = $this->session->userdata('dag_no');

        $this->db->select('chitha_basic.*');
        $this->db->where('chitha_basic.dist_code', $dcode);
        $this->db->where('chitha_basic.subdiv_code', $scode);
        $this->db->where('chitha_basic.cir_code', $ccode);
        $this->db->where('chitha_basic.mouza_pargona_code', $mcode);
        $this->db->where('chitha_basic.lot_no', $lcode);
        $this->db->where('chitha_basic.vill_townprt_code', $vcode);
        $this->db->where('chitha_basic.dag_no', $dagno);
        $this->db->from('chitha_basic');
        $row = $this->db->get()->row();

        return $row;
    }

    public function landDetailsInCrop($year)
    {
        $dcode = $this->session->userdata('dist_code');
        $scode = $this->session->userdata('subdiv_code');
        $ccode = $this->session->userdata('cir_code');
        $mcode = $this->session->userdata('mouza_pargona_code');
        $lcode = $this->session->userdata('lot_no');
        $vcode = $this->session->userdata('vill_townprt_code');
        $dagno = $this->session->userdata('dag_no');

        $this->db->select('chitha_mcrop.*');
        $this->db->where('chitha_mcrop.dist_code', $dcode);
        $this->db->where('chitha_mcrop.subdiv_code', $scode);
        $this->db->where('chitha_mcrop.cir_code', $ccode);
        $this->db->where('chitha_mcrop.mouza_pargona_code', $mcode);
        $this->db->where('chitha_mcrop.lot_no', $lcode);
        $this->db->where('chitha_mcrop.vill_townprt_code', $vcode);
        $this->db->where('chitha_mcrop.dag_no', $dagno);
        $this->db->where('chitha_mcrop.yearno', $year);
        $this->db->from('chitha_mcrop');
        $allCrop = $this->db->get();
        return $allCrop->result();
    }

    public function landDetailsInNonCrop($year)
    {
        $dcode = $this->session->userdata('dist_code');
        $scode = $this->session->userdata('subdiv_code');
        $ccode = $this->session->userdata('cir_code');
        $mcode = $this->session->userdata('mouza_pargona_code');
        $lcode = $this->session->userdata('lot_no');
        $vcode = $this->session->userdata('vill_townprt_code');
        $dagno = $this->session->userdata('dag_no');

        $this->db->select('chitha_noncrop.*');
        $this->db->where('chitha_noncrop.dist_code', $dcode);
        $this->db->where('chitha_noncrop.subdiv_code', $scode);
        $this->db->where('chitha_noncrop.cir_code', $ccode);
        $this->db->where('chitha_noncrop.mouza_pargona_code', $mcode);
        $this->db->where('chitha_noncrop.lot_no', $lcode);
        $this->db->where('chitha_noncrop.vill_townprt_code', $vcode);
        $this->db->where('chitha_noncrop.dag_no', $dagno);
        $this->db->where('chitha_noncrop.yn', $year);
        $this->db->from('chitha_noncrop');
        $allCrop = $this->db->get();
        return $allCrop->result();
    }

    public function getPattaNo($dis, $subdiv, $cir, $mza, $lot, $vill)
    {
        $dtype = $this->db->query("Select dag_no from chitha_basic where
        dist_code='$dis' and subdiv_code='$subdiv' and cir_code='$cir' and mouza_pargona_code='$mza' and lot_no='$lot' and vill_townprt_code='$vill' order by dag_no_int asc");

        return $dtype->result();
    }

    public function getVillageuuid($dis, $subdiv, $cir, $mza, $lot, $vill)
    {
        $dtype = $this->db->query("Select uuid from location where
        dist_code='$dis' and subdiv_code='$subdiv' and cir_code='$cir' and mouza_pargona_code='$mza' and lot_no='$lot' and vill_townprt_code='$vill'");

        return $dtype->row();
    }

    public function allData()
    {
        $data = $this->db->query("Select * from supportive_document ");
        return $data->result();
    }
    public function getSessionLoc($dis, $subdiv, $cir, $mza, $lot, $vill)
    {
        $subdivs = $this->subdivisiondetails($dis);
        $cirs = $this->circledetails($dis, $subdiv);
        $mzas = $this->mouzadetails($dis, $subdiv, $cir);
        $lots = $this->lotdetails($dis, $subdiv, $cir, $mza);
        $vills = $this->villagedetails($dis, $subdiv, $cir, $mza, $lot);
        $locations = [];
        $locations["dist"]['code'] = $dis;
        $locations["subdivs"]['all'] = $subdivs;
        $locations["subdivs"]['code'] = $subdiv;
        $locations["cir"]['all'] = $cirs;
        $locations["cir"]['code'] = $cir;
        $locations["mza"]['all'] = $mzas;
        $locations["mza"]['code'] = $mza;
        $locations["lot"]['all'] = $lots;
        $locations["lot"]['code'] = $lot;
        $locations["vill"]['all'] = $vills;
        $locations["vill"]['code'] = $vill;
        return $locations;
    }
    public function checkIfRecordExistinChithaBasic($data)
    {
        $query = $this->db->get_where('chitha_basic', array(
            'dist_code' => $data['dist_code'],
            'subdiv_code' => $data['subdiv_code'],
            'cir_code' => $data['cir_code'],
            'mouza_pargona_code' => $data['mouza_pargona_code'],
            'lot_no' => $data['lot_no'],
            'vill_townprt_code' => $data['vill_townprt_code'],
            'dag_no' => $data['dag_no'],
            'patta_no' => $data['patta_no'],
        ));

        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function checkVerifiedVillage($dis, $subdiv, $cir, $mza, $lot, $vill_townprt_code)
    {
        $alloted_dags_count = $this->db->query("SELECT COUNT(*) AS dag_count FROM alloted_dags WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND user_desig_code=?", [$dis, $subdiv, $cir, $mza, $lot, $vill_townprt_code, 'CO'])->row();

        $alloted_dags_signed_count = $this->db->query("SELECT COUNT(*) AS signed_dag_count FROM alloted_dags WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND user_desig_code=? AND is_signed=1", [$dis, $subdiv, $cir, $mza, $lot, $vill_townprt_code, 'CO'])->row();

        if ($alloted_dags_signed_count->signed_dag_count > 0 && $alloted_dags_count->dag_count > 0 && ($alloted_dags_signed_count->signed_dag_count == $alloted_dags_count->dag_count)) {
            return true;
        }

        return false;
    }
}
