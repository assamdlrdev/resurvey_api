<?php
class DagEditModel extends CI_Model
{
    function updatepattadar()
    {
        $dist_code = $this->session->userdata('dag_dist_code');
        $subdiv_code = $this->session->userdata('dag_subdiv_code');
        $cir_code = $this->session->userdata('dag_cir_code');
        $mouza_pargona_code = $this->session->userdata('dag_mouza_pargona_code');
        $lot_no = $this->session->userdata('dag_lot_no');
        $vill_code = $this->session->userdata('dag_vill_code');
        $patta_type_code = $this->input->post('patta_type_code');
        $patta_no = $this->input->post('patta_no');
        $dag_no = $this->input->post('new_dag_no');
        $pdar_id = $this->input->post('pdar_id');

        $this->db->trans_start();
        $data['data'] = array(
            'dag_por_b'   => $this->input->post('dag_por_b') ? $this->input->post('dag_por_b') : 0,
            'dag_por_k'   => $this->input->post('dag_por_k') ? $this->input->post('dag_por_k') : 0,
            'dag_por_lc'  => $this->input->post('dag_por_lc') ? $this->input->post('dag_por_lc') : 0,
            'dag_por_g'   => $this->input->post('dag_por_g') ? $this->input->post('dag_por_g') : 0,
            'pdar_land_n' => $this->input->post('pdar_land_n'),
            'pdar_land_s' => $this->input->post('pdar_land_s'),
            'pdar_land_e' => $this->input->post('pdar_land_e'),
            'pdar_land_w' => $this->input->post('pdar_land_w'),
            'pdar_land_acre' => 0,
            'pdar_land_revenue' => $this->input->post('pdar_land_revenue') ? $this->input->post('pdar_land_revenue') : 0,
            'pdar_land_localtax' => $this->input->post('pdar_land_localtax') ? $this->input->post('pdar_land_localtax') : 0,
            'p_flag' => $this->input->post('p_flag'),
        );
        $this->db->where(array('dist_code' => $dist_code, 'subdiv_code' => $subdiv_code, 'cir_code' => $cir_code, 'mouza_pargona_code' => $mouza_pargona_code, 'lot_no' => $lot_no, 'vill_townprt_code' => $vill_code, 'dag_no' => $dag_no, 'pdar_id' => $pdar_id, 'patta_no' => $patta_no, 'patta_type_code' => $patta_type_code));
        $data['data_1'] = $this->security->xss_clean($data['data']);
        $nrows = $this->db->update('edited_chitha_dag_pattadar', $data['data_1']);
        $data['data'] = array(
            'pdar_name' => $this->input->post('pdar_name'),
            'pdar_gender' => $this->input->post('p_gender'),
            'pdar_guard_reln' => $this->input->post('pdar_relation'),
            'pdar_father' => $this->input->post('pdar_father'),
            'pdar_add1' => $this->input->post('pdar_add1'),
            'pdar_add2' => $this->input->post('pdar_add2'),
            'pdar_add3' => $this->input->post('pdar_add3'),
            'pdar_pan_no' => $this->input->post('pdar_pan_no'),
            'pdar_citizen_no' => $this->input->post('pdar_citizen_no'),
        );
        if ($this->db->field_exists('pdar_relation', 'chitha_pattadar')) {
            $data['data']['pdar_relation'] =  $this->input->post('pdar_relation');
        }
        $this->db->where(array('dist_code' => $dist_code, 'subdiv_code' => $subdiv_code, 'cir_code' => $cir_code, 'mouza_pargona_code' => $mouza_pargona_code, 'lot_no' => $lot_no, 'vill_townprt_code' => $vill_code, 'pdar_id' => $pdar_id, 'patta_no' => $patta_no, 'patta_type_code' => $patta_type_code));
        $data['data_1'] = $this->security->xss_clean($data['data']);
        $this->db->update('edited_chitha_pattadar', $data['data_1']);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    function pattadarexispid($pdar_id, $patta_no, $patta_type_code, $dag_no)
    {
        $dist_code = $this->session->userdata('dag_dist_code');
        $subdiv_code = $this->session->userdata('dag_subdiv_code');
        $cir_code = $this->session->userdata('dag_cir_code');
        $mouza_pargona_code = $this->session->userdata('dag_mouza_pargona_code');
        $lot_no = $this->session->userdata('dag_lot_no');
        $vill_code = $this->session->userdata('dag_vill_code');

        $sql = "select a.pdar_id,a.pdar_gender,a.pdar_name,a.patta_no,a.patta_type_code,a.pdar_guard_reln,a.pdar_father,a.pdar_add1,a.pdar_add2,a.pdar_add3,a.pdar_pan_no,a.pdar_citizen_no,
        b.dag_por_b,b.dag_por_k,b.dag_por_lc,b.dag_por_g,b.pdar_land_n,b.pdar_land_s,b.pdar_land_e,b.pdar_land_w,b.pdar_land_revenue,b.pdar_land_localtax,b.p_flag,b.pdar_land_acre 
        from edited_chitha_pattadar as a join edited_chitha_dag_pattadar as b on a.patta_no=b.patta_no and a.patta_type_code=b.patta_type_code and a.dist_code=b.dist_code and a.subdiv_code=b.subdiv_code and a.cir_code=b.cir_code and a.mouza_pargona_code=b.mouza_pargona_code and a.lot_no=b.lot_no and a.vill_townprt_code=b.vill_townprt_code and a.pdar_id=b.pdar_id where a.pdar_id=$pdar_id and b.dag_no='$dag_no' and a.patta_no='$patta_no' and a.patta_type_code='$patta_type_code' and a.dist_code='$dist_code' and a.subdiv_code='$subdiv_code' and a.cir_code='$cir_code' and a.mouza_pargona_code='$mouza_pargona_code' and a.lot_no='$lot_no' and a.vill_townprt_code='$vill_code' order by b.dag_no";

        $query = $this->db->query($sql);
        $row = $query->row();
        return $row;
    }
    function insertPattadarFromOtherDag($pid, $pdar_name, $patta, $ptype, $old_dag_no)
    {

        $dag_no = $this->session->userdata('new_dag_no');
        $dist_code = $this->session->userdata('dag_dist_code');
        $subdiv_code = $this->session->userdata('dag_subdiv_code');
        $cir_code = $this->session->userdata('dag_cir_code');
        $mouza_pargona_code = $this->session->userdata('dag_mouza_pargona_code');
        $lot_no = $this->session->userdata('dag_lot_no');
        $vill_code = $this->session->userdata('dag_vill_code');

        $this->db->trans_start();
        $where = "(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_code' and  dag_no='$dag_no' and pdar_id=$pid and patta_no='$patta' and patta_type_code='$ptype')";
        $this->db->select('dag_no,patta_no');
        $query = $this->db->get_where('edited_chitha_dag_pattadar', $where);
        //insert edited_chitha_dag_pattadar if not exists
        if ($query->num_rows() == 0) {
            $data['data'] = array(
                'dist_code' => $dist_code,
                'subdiv_code' => $subdiv_code,
                'cir_code' => $cir_code,
                'mouza_pargona_code' => $mouza_pargona_code,
                'lot_no' => $lot_no,
                'vill_townprt_code' => $vill_code,
                'dag_no' => $dag_no,
                'pdar_id' => $pid,
                'patta_no' => $patta,
                'patta_type_code' => $ptype,
                'dag_por_b' => 0,
                'dag_por_k' => 0,
                'dag_por_lc' => 0,
                'dag_por_g' =>  0,
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
            $nrows = $this->db->insert('edited_chitha_dag_pattadar', $data['data_1']);

            if ($nrows > 0) {
                //update splitted flag to chitha_dag_pattadar
                $this->db->where($where);
                $this->db->update('chitha_dag_pattadar',['is_splitted' => 'y']);

                //insert chitha_pattdar to edited_chitha_pattdar of not exists
                $where = "(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_code' and pdar_id=$pid and patta_no='$patta' and patta_type_code='$ptype')";
                $query = $this->db->get_where('edited_chitha_pattadar', $where);
                if ($query->num_rows() == 0) {
                    $pattdar = $this->db->get_where('chitha_pattadar', $where)->row();
                    $data['data3'] = array(
                        'dist_code' => $pattdar->dist_code,
                        'subdiv_code' => $pattdar->subdiv_code,
                        'cir_code' => $pattdar->cir_code,
                        'mouza_pargona_code' => $pattdar->mouza_pargona_code,
                        'lot_no' => $pattdar->lot_no,
                        'vill_townprt_code' => $pattdar->vill_townprt_code,
                        'pdar_id' => $pattdar->pdar_id,
                        'patta_no' => $pattdar->patta_no,
                        'patta_type_code' => $pattdar->patta_type_code,
                        'pdar_name' => $pattdar->pdar_name,
                        'pdar_guard_reln' => $pattdar->pdar_relation,
                        'pdar_father' => $pattdar->pdar_father,
                        'pdar_add1' => $pattdar->pdar_add1,
                        'pdar_add2' => $pattdar->pdar_add2,
                        'pdar_add3' => $pattdar->pdar_add3,
                        'pdar_pan_no' => $pattdar->pdar_pan_no,
                        'pdar_citizen_no' => $pattdar->pdar_citizen_no,
                        'pdar_gender' => $pattdar->pdar_gender,
                        'user_code' => $this->session->userdata('usercode'),
                        'date_entry' => date("Y-m-d | h:i:sa"),
                        'operation' => 'E',
                        'jama_yn' => 'n',
                    );

                    if ($this->db->field_exists('pdar_relation', 'edited_chitha_pattadar')) {
                        $data['data3']['pdar_relation'] =  $pattdar->pdar_relation;
                    }

                    $data['data_2'] = $this->security->xss_clean($data['data3']);
                    $this->db->insert('edited_chitha_pattadar', $data['data_2']);

                    //update splitted flag to chitha_pattadar 
                    $this->db->where($where);
                    $this->db->update('chitha_pattadar',['is_splitted' => 'y']);    
                }
            }
        }else{
            log_message('error','pdar exists - '.$pid.'-'.$dag_no);
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    function get_pattadars_by_dags($patta_no, $patta_type_code, $dagno = null, $current_dag_no)
    {

        $dist_code = $this->session->userdata('dag_dist_code');
        $subdiv_code = $this->session->userdata('dag_subdiv_code');
        $cir_code = $this->session->userdata('dag_cir_code');
        $mouza_pargona_code = $this->session->userdata('dag_mouza_pargona_code');
        $lot_no = $this->session->userdata('dag_lot_no');
        $vill_code = $this->session->userdata('dag_vill_code');
        $sql = "select cp.pdar_name,cp.pdar_father,cp.pdar_mother,cp.pdar_id,cp.subdiv_code,cp.dist_code,cp.cir_code,cp.mouza_pargona_code,cp.lot_no,cp.vill_townprt_code,cp.patta_no from edited_chitha_pattadar cp  join edited_chitha_dag_pattadar cdp on cp.dist_code=cdp.dist_code  and cp.subdiv_code=cdp.subdiv_code and cp.cir_code=cdp.cir_code 
	            and cp.mouza_pargona_code=cdp.mouza_pargona_code and cp.lot_no=cdp.lot_no and cp.vill_townprt_code=cdp.vill_townprt_code and cp.patta_type_code=cdp.patta_type_code and trim(cp.patta_no)=trim(cdp.patta_no) and cdp.pdar_id=cp.pdar_id where dag_no='$current_dag_no' and trim(cp.patta_no)=trim('$patta_no') and cp.pdar_id=cdp.pdar_id and cp.patta_type_code='$patta_type_code' and cp.dist_code='$dist_code' and cp.subdiv_code='$subdiv_code' and cp.cir_code='$cir_code' and cp.mouza_pargona_code='$mouza_pargona_code' and cp.lot_no='$lot_no' and cp.vill_townprt_code='$vill_code'";
        $pattadars = $this->db->query($sql)->result();
        if ($dagno) {
            $sql = "select a.pdar_id,a.pdar_name,a.patta_no,a.patta_type_code,a.lot_no,a.vill_townprt_code,b.dag_no from chitha_pattadar as a join chitha_dag_pattadar as b on a.patta_no=b.patta_no and a.patta_type_code=b.patta_type_code and a.dist_code=b.dist_code and a.subdiv_code=b.subdiv_code and a.cir_code=b.cir_code and a.mouza_pargona_code=b.mouza_pargona_code and a.lot_no=b.lot_no and a.vill_townprt_code=b.vill_townprt_code and a.pdar_id=b.pdar_id where b.dag_no='$dagno' and a.patta_no='$patta_no' and a.patta_type_code='$patta_type_code' and a.dist_code='$dist_code' and a.subdiv_code='$subdiv_code' and a.cir_code='$cir_code' and a.mouza_pargona_code='$mouza_pargona_code' and a.lot_no='$lot_no' and a.vill_townprt_code='$vill_code' order by b.dag_no";
            // from edited table
            $sql2 = "select a.pdar_id,a.pdar_name,a.patta_no,a.patta_type_code,a.lot_no,a.vill_townprt_code,b.dag_no from edited_chitha_pattadar as a join edited_chitha_dag_pattadar as b on a.patta_no=b.patta_no and a.patta_type_code=b.patta_type_code and a.dist_code=b.dist_code and a.subdiv_code=b.subdiv_code and a.cir_code=b.cir_code and a.mouza_pargona_code=b.mouza_pargona_code and a.lot_no=b.lot_no and a.vill_townprt_code=b.vill_townprt_code and a.pdar_id=b.pdar_id where b.dag_no='$dagno' and a.patta_no='$patta_no' and a.patta_type_code='$patta_type_code' and a.dist_code='$dist_code' and a.subdiv_code='$subdiv_code' and a.cir_code='$cir_code' and a.mouza_pargona_code='$mouza_pargona_code' and a.lot_no='$lot_no' and a.vill_townprt_code='$vill_code' order by b.dag_no";
        } else {
            $sql = "select a.pdar_id,a.pdar_name,a.patta_no,a.patta_type_code,a.lot_no,a.vill_townprt_code,b.dag_no from chitha_pattadar as a join chitha_dag_pattadar as b on a.patta_no=b.patta_no and a.patta_type_code=b.patta_type_code and a.dist_code=b.dist_code and a.subdiv_code=b.subdiv_code and a.cir_code=b.cir_code and a.mouza_pargona_code=b.mouza_pargona_code and a.lot_no=b.lot_no and a.vill_townprt_code=b.vill_townprt_code and a.pdar_id=b.pdar_id where a.patta_no='$patta_no' and a.patta_type_code='$patta_type_code' and a.dist_code='$dist_code' and a.subdiv_code='$subdiv_code' and a.cir_code='$cir_code' and a.mouza_pargona_code='$mouza_pargona_code' and a.lot_no='$lot_no' and a.vill_townprt_code='$vill_code' and b.dag_no!='$current_dag_no' order by b.dag_no";
            // from edited table
            $sql2 = "select a.pdar_id,a.pdar_name,a.patta_no,a.patta_type_code,a.lot_no,a.vill_townprt_code,b.dag_no from edited_chitha_pattadar as a join edited_chitha_dag_pattadar as b on a.patta_no=b.patta_no and a.patta_type_code=b.patta_type_code and a.dist_code=b.dist_code and a.subdiv_code=b.subdiv_code and a.cir_code=b.cir_code and a.mouza_pargona_code=b.mouza_pargona_code and a.lot_no=b.lot_no and a.vill_townprt_code=b.vill_townprt_code and a.pdar_id=b.pdar_id where a.patta_no='$patta_no' and a.patta_type_code='$patta_type_code' and a.dist_code='$dist_code' and a.subdiv_code='$subdiv_code' and a.cir_code='$cir_code' and a.mouza_pargona_code='$mouza_pargona_code' and a.lot_no='$lot_no' and a.vill_townprt_code='$vill_code' and b.dag_no!='$current_dag_no' order by b.dag_no";
        }

        $other_pattadars = [];
        $other_pattadars_all = $this->db->query($sql)->result();
        $other_pattadars_edited = $this->db->query($sql2)->result();

        foreach ($other_pattadars_all as $other_pattdar) {
            $is_exists = false;
            foreach ($pattadars as $pattadar) {
                if ($other_pattdar->dag_no == $pattadar->dag_no && $other_pattdar->pdar_id == $pattadar->pdar_id) {
                    $is_exists = true;
                }
            }
            if (!$is_exists) {
                $p['pdar_id'] = $other_pattdar->pdar_id;
                $p['pdar_name'] = $other_pattdar->pdar_name;
                $p['dag_no'] = $other_pattdar->dag_no;
                $p['patta_no'] = $other_pattdar->patta_no;
                $p['patta_type_code'] = $other_pattdar->patta_type_code;
                $other_pattadars[] = $p;
            }
        }

        foreach ($other_pattadars_edited as $other_pattdar) {
            $is_exists = false;
            foreach ($pattadars as $pattadar) {
                if ($other_pattdar->dag_no == $pattadar->dag_no && $other_pattdar->pdar_id == $pattadar->pdar_id) {
                    $is_exists = true;
                }
            }
            foreach ($other_pattadars_all as $pattadar) {
                if ($other_pattdar->dag_no == $pattadar->dag_no && $other_pattdar->pdar_id == $pattadar->pdar_id) {
                    $is_exists = true;
                }
            }
            if (!$is_exists) {
                $p['pdar_id'] = $other_pattdar->pdar_id;
                $p['pdar_name'] = $other_pattdar->pdar_name;
                $p['dag_no'] = $other_pattdar->dag_no;
                $p['patta_no'] = $other_pattdar->patta_no;
                $p['patta_type_code'] = $other_pattdar->patta_type_code;
                $other_pattadars[] = $p;
            }
        }

        $str = '';

        if (count($other_pattadars) > 0) {
            foreach ($other_pattadars as $row) {
                $pid = $row['pdar_id'];
                $pname = $row['pdar_name'];
                $patta = $row['patta_no'];
                $ptype = $row['patta_type_code'];
                $dno = $row['dag_no'];
                $vl = $pid . ',' . $pname . ',' . $patta . ',' . $ptype . ',' . $dno;
                $str = $str . '<tr><td><input type="checkbox" name="chk[]" id="chk[]" class="p_select" value="' . $vl . '"></td><td>' . $row['pdar_id'] . '</td><td>' . $row['dag_no'] . '</td><td>' . $row['pdar_name'] . '</td><tr>';
            }
        }
        return $str;
    }
    public function other_dags($patta_no, $patta_type_code, $dag_no)
    {
        $dist_code = $this->session->userdata('dag_dist_code');
        $subdiv_code = $this->session->userdata('dag_subdiv_code');
        $cir_code = $this->session->userdata('dag_cir_code');
        $mouza_pargona_code = $this->session->userdata('dag_mouza_pargona_code');
        $lot_no = $this->session->userdata('dag_lot_no');
        $vill_code = $this->session->userdata('dag_vill_code');
        $where = "(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_code' and patta_no='$patta_no' and patta_type_code='$patta_type_code')";
        $this->db->select('dag_no');
        $this->db->distinct();
        $dags_all = $this->db->get_where('chitha_dag_pattadar', $where)->result();
        // from edited table
        $where = "(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_code' and patta_no='$patta_no' and patta_type_code='$patta_type_code')";
        $this->db->select('dag_no');
        $this->db->distinct();
        $dags_edited = $this->db->get_where('edited_chitha_dag_pattadar', $where)->result();
        foreach($dags_edited as $dag){
            if(!in_array($dag,$dags_all)){
                $dags_all[] = $dag;
            }
        }
        return $dags_all;
    }
    public function other_pattadars_edited($patta_no, $patta_type_code, $dag_no)
    {
        $dist_code = $this->session->userdata('dag_dist_code');
        $subdiv_code = $this->session->userdata('dag_subdiv_code');
        $cir_code = $this->session->userdata('dag_cir_code');
        $mouza_pargona_code = $this->session->userdata('dag_mouza_pargona_code');
        $lot_no = $this->session->userdata('dag_lot_no');
        $vill_code = $this->session->userdata('dag_vill_code');

        $sql = "select a.pdar_id,a.pdar_name,a.patta_no,a.patta_type_code,b.dag_no from edited_chitha_pattadar as a join edited_chitha_dag_pattadar as b on a.patta_no=b.patta_no and a.patta_type_code=b.patta_type_code and a.dist_code=b.dist_code and a.subdiv_code=b.subdiv_code and a.cir_code=b.cir_code and a.mouza_pargona_code=b.mouza_pargona_code and a.lot_no=b.lot_no and a.vill_townprt_code=b.vill_townprt_code and a.pdar_id=b.pdar_id where a.patta_no='$patta_no' and a.patta_type_code='$patta_type_code' and a.dist_code='$dist_code' and a.subdiv_code='$subdiv_code' and a.cir_code='$cir_code' and a.mouza_pargona_code='$mouza_pargona_code' and a.lot_no='$lot_no' and a.vill_townprt_code='$vill_code' order by b.dag_no";

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    function other_pattadars($patta_no, $patta_type_code, $dag_no)
    {

        $dist_code = $this->session->userdata('dag_dist_code');
        $subdiv_code = $this->session->userdata('dag_subdiv_code');
        $cir_code = $this->session->userdata('dag_cir_code');
        $mouza_pargona_code = $this->session->userdata('dag_mouza_pargona_code');
        $lot_no = $this->session->userdata('dag_lot_no');
        $vill_code = $this->session->userdata('dag_vill_code');

        $sql = "select a.pdar_id,a.pdar_name,a.patta_no,a.patta_type_code,b.dag_no from chitha_pattadar as a join chitha_dag_pattadar as b on a.patta_no=b.patta_no and a.patta_type_code=b.patta_type_code and a.dist_code=b.dist_code and a.subdiv_code=b.subdiv_code and a.cir_code=b.cir_code and a.mouza_pargona_code=b.mouza_pargona_code and a.lot_no=b.lot_no and a.vill_townprt_code=b.vill_townprt_code and a.pdar_id=b.pdar_id where a.patta_no='$patta_no' and a.patta_type_code='$patta_type_code' and a.dist_code='$dist_code' and a.subdiv_code='$subdiv_code' and a.cir_code='$cir_code' and a.mouza_pargona_code='$mouza_pargona_code' and a.lot_no='$lot_no' and a.vill_townprt_code='$vill_code' order by b.dag_no";

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    function insertpattadar()
    {

        $dist_code = $this->session->userdata('dag_dist_code');
        $subdiv_code = $this->session->userdata('dag_subdiv_code');
        $cir_code = $this->session->userdata('dag_cir_code');
        $mouza_pargona_code = $this->session->userdata('dag_mouza_pargona_code');
        $lot_no = $this->session->userdata('dag_lot_no');
        $vill_code = $this->session->userdata('dag_vill_code');
        $patta_type_code = $this->input->post('patta_type_code');
        $patta_no = $this->input->post('patta_no');
        $dag_no = $this->session->userdata('new_dag_no');

        $data['data'] = array(
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'mouza_pargona_code' => $mouza_pargona_code,
            'lot_no' => $lot_no,
            'vill_townprt_code' => $vill_code,
            'dag_no' => $dag_no,
            'pdar_id' => $this->input->post('pdar_id'),
            'patta_no' => $patta_no,
            'patta_type_code' => $patta_type_code,
            'dag_por_b' => $this->input->post('dag_por_b') ? $this->input->post('dag_por_b') : 0,
            'dag_por_k' => $this->input->post('dag_por_k') ? $this->input->post('dag_por_k') : 0,
            'dag_por_lc' => $this->input->post('dag_por_lc') ?: 0,
            'dag_por_g' =>  $this->input->post('dag_por_g') ? $this->input->post('dag_por_g') : 0,
            'pdar_land_n' => $this->input->post('pdar_land_n'),
            'pdar_land_s' => $this->input->post('pdar_land_s'),
            'pdar_land_e' => $this->input->post('pdar_land_e'),
            'pdar_land_w' => $this->input->post('pdar_land_w'),
            'pdar_land_acre' => $this->input->post('pdar_land_acre') ? $this->input->post('pdar_land_acre') : 0,
            'pdar_land_revenue' => $this->input->post('pdar_land_revenue') ? $this->input->post('pdar_land_revenue') : 0,
            'pdar_land_localtax' => $this->input->post('pdar_land_localtax') ? $this->input->post('pdar_land_localtax') : 0,
            'user_code' => $this->session->userdata('usercode'),
            'date_entry' => date("Y-m-d | h:i:sa"),
            'operation' => 'E',
            'p_flag' => $this->input->post('p_flag'),
            'jama_yn' => 'n',

        );
        $data['data_1'] = $this->security->xss_clean($data['data']);
        $this->db->trans_start();
        $this->db->insert('edited_chitha_dag_pattadar', $data['data_1']);

        $data['data3'] = array(
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'mouza_pargona_code' => $mouza_pargona_code,
            'lot_no' => $lot_no,
            'vill_townprt_code' => $vill_code,
            'pdar_id' => $this->input->post('pdar_id'),
            'patta_no' => $patta_no,
            'patta_type_code' => $patta_type_code,
            'pdar_name' => $this->input->post('pdar_name'),
            'pdar_guard_reln' => $this->input->post('pdar_relation'),
			'pdar_relation' => $this->input->post('pdar_relation'),
            'pdar_father' => $this->input->post('pdar_father'),
            'pdar_add1' => $this->input->post('pdar_add1'),
            'pdar_add2' => $this->input->post('pdar_add2'),
            'pdar_add3' => $this->input->post('pdar_add3'),
            'pdar_pan_no' => $this->input->post('pdar_pan_no'),
            'pdar_citizen_no' => $this->input->post('pdar_citizen_no'),
            'pdar_gender' => $this->input->post('p_gender'),
            'user_code' => $this->session->userdata('usercode'),
            'date_entry' => date("Y-m-d | h:i:sa"),
            'operation' => 'o',
            'jama_yn' => 'n',
        );

        if ($this->db->field_exists('pdar_relation', 'chitha_pattadar')) {
            $data['data3']['pdar_relation'] =  $this->input->post('pdar_relation');
        }

        $data['data_2'] = $this->security->xss_clean($data['data3']);
        $this->db->insert('edited_chitha_pattadar', $data['data_2']);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    function checkpattadarid()
    {
        $dist_code = $this->session->userdata('dag_dist_code');
        $subdiv_code = $this->session->userdata('dag_subdiv_code');
        $cir_code = $this->session->userdata('dag_cir_code');
        $mouza_pargona_code = $this->session->userdata('dag_mouza_pargona_code');
        $lot_no = $this->session->userdata('dag_lot_no');
        $vill_code = $this->session->userdata('dag_vill_code');
        $patta_type_code = $this->session->userdata('dag_patta_type_code');
        $patta_no = $this->session->userdata('dag_patta_no');

        $where = "(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_code' and trim(patta_no)=trim('$patta_no') and patta_type_code='$patta_type_code')";
        $this->db->select_max('pdar_id', 'max');
        $query = $this->db->get_where('chitha_pattadar', $where);
        if ($query->num_rows() == 0) {
            return 1;
        }
        $max = $query->row()->max;
        $pdar_id = $max == 0 ? 1 : $max + 1;
        $where = "(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_code' and trim(patta_no)=trim('$patta_no') and patta_type_code='$patta_type_code')";
        $this->db->select_max('pdar_id', 'max');
        $is_exists = $this->db->get_where('edited_chitha_pattadar', $where);
        if ($is_exists->num_rows() == 0) {
            return $pdar_id;
        }
        $max2 = $is_exists->row()->max;
        $pdar_id2 = $max2 + 1;
        if ($pdar_id2 > $pdar_id) {
            return $pdar_id2;
        } else {
            return $pdar_id;
        }
    }
    function updateDag()
    {
        $dist_code = $this->session->userdata('dag_dist_code');
        $subdiv_code = $this->session->userdata('dag_subdiv_code');
        $cir_code = $this->session->userdata('dag_cir_code');
        $mouza_pargona_code = $this->session->userdata('dag_mouza_pargona_code');
        $lot_no = $this->session->userdata('dag_lot_no');
        $vill_code = $this->session->userdata('dag_vill_code');
        $patta_type_code = $this->input->post('patta_type_code');
        $patta_no = $this->input->post('patta_no');
        $old_patta_type_code = $this->session->userdata('dag_patta_type_code');
        $old_patta_no = $this->session->userdata('dag_patta_no');
        $old_dag_no = $this->session->userdata('dag_dag_no');
        $new_dag_no = $this->input->post('dag_no');
        $dag_no_int = $new_dag_no * 100;
        $this->session->set_userdata('new_dag_no', $new_dag_no);

        $this->db->trans_start();
        $data = array(
            'land_class_code' => $this->input->post('land_class_code'),
            'dag_area_b'    => $this->input->post('dag_area_b'),
            'dag_area_k'    => $this->input->post('dag_area_k'),
            'dag_area_lc'   => $this->input->post('dag_area_lc'),
            'dag_area_g'    => $this->input->post('dag_area_g') ? $this->input->post('dag_area_g') : 0,
            'dag_area_are'  => $this->input->post('dag_area_r'),
            'dag_revenue'   => $this->input->post('dag_land_revenue'),
            'dag_local_tax' => $this->input->post('dag_local_tax'),
            'dag_n_desc'    => $this->input->post('dag_n_desc'),
            'dag_s_desc'    => $this->input->post('dag_s_desc'),
            'dag_e_desc'    => $this->input->post('dag_e_desc'),
            'dag_w_desc'    => $this->input->post('dag_w_desc'),
            'dag_n_dag_no'  => $this->input->post('dag_n_dag_no'),
            'dag_s_dag_no'  => $this->input->post('dag_s_dag_no'),
            'dag_e_dag_no'  => $this->input->post('dag_e_dag_no'),
            'dag_w_dag_no'  => $this->input->post('dag_w_dag_no'),
            'dag_area_kr'   => '00',
            'dag_nlrg_no'   => $this->input->post('dag_nlrg_no'),
            'dp_flag_yn'    => $this->input->post('dp_flag_yn'),
            'user_code'     => $this->session->userdata('usercode'),
            'date_entry'    => date("Y-m-d | h:i:sa"),
            'operation'     => 'E',
            'jama_yn'       => 'n'
        );
        $where = "(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_code' and trim(patta_no)=trim('$patta_no') and patta_type_code='$patta_type_code' and dag_no='$new_dag_no')";
        $query = $this->db->get_where('edited_chitha_basic', $where);

        if ($query->num_rows() > 0) {
            $data = $this->security->xss_clean($data);
            // print_r($data); die();
            $query = $this->db->where($where);
            $this->db->update('edited_chitha_basic', $data);
        } else {
            $data['dist_code'] = $dist_code;
            $data['subdiv_code'] = $subdiv_code;
            $data['cir_code'] = $cir_code;
            $data['mouza_pargona_code'] = $mouza_pargona_code;
            $data['lot_no'] = $lot_no;
            $data['vill_townprt_code'] = $vill_code;
            $data['old_dag_no'] = $old_dag_no;
            $data['dag_no'] = $new_dag_no;
            $data['dag_no_int'] = $dag_no_int;
            $data['patta_type_code'] = $patta_type_code;
            $data['patta_no'] = $patta_no;
            $data['old_patta_type_code'] = $old_patta_type_code;
            $data['old_patta_no'] = $old_patta_no;
            $data = $this->security->xss_clean($data);
            $this->db->insert('edited_chitha_basic', $data);
            // print_r($data);
            //UPDATE ORIGINAL AREA
            $where = "(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_code' and trim(patta_no)=trim('$patta_no') and patta_type_code='$patta_type_code' and dag_no='$old_dag_no')";
            $chitha_basic = $this->db->get_where('chitha_basic',$where)->row();
            if($chitha_basic){
                $data_to_update = $this->getUpdatedAreaData();
                $query = $this->db->where($where);
                $this->db->update('chitha_basic', $data_to_update);        
            }
        }
        log_message('error', $this->db->last_query());
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    public function updateExistingDag()
    {
        $dist_code = $this->session->userdata('dag_dist_code');
        $subdiv_code = $this->session->userdata('dag_subdiv_code');
        $cir_code = $this->session->userdata('dag_cir_code');
        $mouza_pargona_code = $this->session->userdata('dag_mouza_pargona_code');
        $lot_no = $this->session->userdata('dag_lot_no');
        $vill_code = $this->session->userdata('dag_vill_code');
        $patta_type_code = $this->session->userdata('dag_patta_type_code');
        $patta_no = $this->session->userdata('dag_patta_no');
        $new_dag_no = $this->input->post('new_dag_no');

        $this->db->trans_start();
        $data = array(
            'land_class_code' => $this->input->post('land_class_code'),
            // 'dag_area_b'    => $this->input->post('dag_area_b'),
            // 'dag_area_k'    => $this->input->post('dag_area_k'),
            // 'dag_area_lc'   => $this->input->post('dag_area_lc'),
            // 'dag_area_g'    => $this->input->post('dag_area_g') ? $this->input->post('dag_area_g') : 0,
            'dag_area_are'  => $this->input->post('dag_area_r'),
            'dag_revenue'   => $this->input->post('dag_land_revenue'),
            'dag_local_tax' => $this->input->post('dag_local_tax'),
            'dag_n_desc'    => $this->input->post('dag_n_desc'),
            'dag_s_desc'    => $this->input->post('dag_s_desc'),
            'dag_e_desc'    => $this->input->post('dag_e_desc'),
            'dag_w_desc'    => $this->input->post('dag_w_desc'),
            'dag_n_dag_no'  => $this->input->post('dag_n_dag_no'),
            'dag_s_dag_no'  => $this->input->post('dag_s_dag_no'),
            'dag_e_dag_no'  => $this->input->post('dag_e_dag_no'),
            'dag_w_dag_no'  => $this->input->post('dag_w_dag_no'),
            'dag_area_kr'   => '00',
            'dag_nlrg_no'   => $this->input->post('dag_nlrg_no'),
            'dp_flag_yn'    => $this->input->post('dp_flag_yn'),
            'user_code'     => $this->session->userdata('usercode'),
            'date_entry'    => date("Y-m-d | h:i:sa"),
            'operation'     => 'E',
            'jama_yn'       => 'n',
        );
        $where = "(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_code' and trim(patta_no)=trim('$patta_no') and patta_type_code='$patta_type_code' and dag_no='$new_dag_no')";
        $query = $this->db->get_where('edited_chitha_basic', $where);
        if ($query->num_rows() > 0) {
            $data = $this->security->xss_clean($data);

            $query = $this->db->where($where);
            $this->db->update('edited_chitha_basic', $data);
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    public function getUpdatedAreaDataOld()
    {
        $area_original = $this->getDagArea();
        $area_submitted = $this->getSubmittedDagArea();
        if ($area_original['area'] && $area_submitted) {
            $area_diff = $area_original['area'] - $area_submitted;
            $area_deducted_total = $area_original['area_deducted'] + $area_submitted;
            if (($this->session->userdata('dag_dist_code') == '21') || ($this->session->userdata('dag_dist_code') == '22')) {
                $g = $area_diff * (6400 / 13.37804);
                $b = $g / 6400;
                $g = $g % 6400;
                $k = $g / 320;
                $g = $g % 320;
                $l = $g / 20;
                $g = $g % 20;

                $g_deducted = $area_deducted_total * (6400 / 13.37804);
                $b_deducted = $g_deducted / 6400;
                $g_deducted = $g_deducted % 6400;
                $k_deducted = $g_deducted / 320;
                $g_deducted = $g_deducted % 320;
                $l_deducted = $g_deducted / 20;
                $g_deducted = $g_deducted % 20;
                return [
                    'dag_area_b' => intval($b),
                    'dag_area_k' => intval($k),
                    'dag_area_lc' => intval($l),
                    'dag_area_g' => round($g,4),
                    'dag_area_b_deducted' => intval($b_deducted),
                    'dag_area_k_deducted' => intval($k_deducted),
                    'dag_area_lc_deducted' => round($l_deducted,4),
                    'dag_area_g_deducted' => round($g_deducted,4),
                    'is_splitted' => 'y',
                    'dag_area_are' => round($area_original['area'] - $area_submitted,5),
                    'dag_area_are_deducted' => round($area_original['area_deducted_total'] + $area_submitted,5)
                ];
            } else {
                $l = $area_diff * (747.45 / 100);
                $b = $l / 100;
                $l = $l % 100;
                $k = $l / 20;
                $l = $l % 20;

                $l_deducted = $area_deducted_total * (747.45 / 100);
                $b_deducted = $l_deducted / 100;
                $l_deducted = $l_deducted % 100;
                $k_deducted = $l_deducted / 20;
                $l_deducted = $l_deducted % 20;
                return [
                    'dag_area_b' => intval($b),
                    'dag_area_k' => intval($k),
                    'dag_area_lc' => round($l,4),
                    'dag_area_b_deducted' => intval($b_deducted),
                    'dag_area_k_deducted' => intval($k_deducted),
                    'dag_area_lc_deducted' => round($l_deducted,4),
                    'is_splitted' => 'y',
                    'dag_area_are' => round($area_original['area'] - $area_submitted,5),
                    'dag_area_are_deducted' => round($area_original['area_deducted_total'] + $area_submitted,5)
                ];
            }
        }
        return false;
    }
    public function getUpdatedAreaData()
    {
        $dist_code = $this->session->userdata('dag_dist_code');
        $subdiv_code = $this->session->userdata('dag_subdiv_code');
        $cir_code = $this->session->userdata('dag_cir_code');
        $mouza_pargona_code = $this->session->userdata('dag_mouza_pargona_code');
        $lot_no = $this->session->userdata('dag_lot_no');
        $vill_code = $this->session->userdata('dag_vill_code');
        $patta_type_code = $this->session->userdata('dag_patta_type_code');
        $patta_no = $this->session->userdata('dag_patta_no');
        $dag_no = $this->session->userdata('dag_dag_no');

        $b_submitted    = $this->input->post('dag_area_b');
        $k_submitted    = $this->input->post('dag_area_k');
        $l_submitted   = $this->input->post('dag_area_lc');
        $g_submitted    = $this->input->post('dag_area_g') ? $this->input->post('dag_area_g') : 0;

        $where = "(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_code' and patta_type_code='$patta_type_code' and patta_no='$patta_no' and dag_no='$dag_no')";
        $this->db->select('dag_area_b,dag_area_b_deducted,dag_area_are_deducted,dag_area_k,dag_area_k_deducted,dag_area_lc,dag_area_lc_deducted,dag_area_g,dag_area_g_deducted,dag_area_are,dag_revenue,dag_local_tax,land_class_code,old_dag_no,dag_no,patta_no,patta_type_code,dag_nlrg_no,dp_flag_yn,dag_area_are,dag_n_desc,dag_s_desc,dag_e_desc,dag_w_desc,dag_n_dag_no,dag_s_dag_no,dag_e_dag_no,dag_w_dag_no');
        $query = $this->db->get_where('chitha_basic', $where);
        $dag = $query->row();

        if ($dag) {
            $b_left_old = $dag->dag_area_b;
            $k_left_old = $dag->dag_area_k;
            $l_left_old = $dag->dag_area_lc;
            $g_left_old = $dag->dag_area_g;

            $b_deducted_old = $dag->dag_area_b_deducted;
            $k_deducted_old = $dag->dag_area_k_deducted;
            $l_deducted_old = $dag->dag_area_lc_deducted;
            $g_deducted_old = $dag->dag_area_g_deducted;

            $dag_area_are_deducted = $dag->dag_area_are_deducted;
            if (($this->session->userdata('dag_dist_codef') == '21') || ($this->session->userdata('dag_dist_code') == '22')) {
                $available_gandas_total = $b_left_old * 6400 + $k_left_old * 320 + $l_left_old * 20 + $g_left_old;
                $submitted_gandas_total = $b_submitted * 6400 + $k_submitted * 320 + $l_submitted * 20 + $g_submitted;
                $deducted_gandas_total = $b_deducted_old * 6400 + $k_deducted_old * 320 + $l_deducted_old * 20 + $g_deducted_old;
                $to_be_deducted_gandas_total = $submitted_gandas_total + $deducted_gandas_total;
                $left_gandas = $available_gandas_total  - $submitted_gandas_total;
                $b_left = $left_gandas / 6400;
                $g_left = $left_gandas % 6400;
                $k_left = $g_left / 320;
                $g_left = $g_left % 320;
                $l_left = $g_left / 20;
                $g_left = $g_left % 20;

                $b_deducted = $to_be_deducted_gandas_total / 6400;
                $g_deducted = $to_be_deducted_gandas_total % 6400;
                $k_deducted = $g_deducted / 320;
                $g_deducted = $g_deducted % 320;
                $l_deducted = $g_deducted / 20;
                $g_deducted = $g_deducted % 20;

                return [
                    'dag_area_b' => intval($b_left),
                    'dag_area_k' => intval($k_left),
                    'dag_area_lc' => intval($l_left),
                    'dag_area_g' => round($g_left,4),
                    'dag_area_b_deducted' => intval($b_deducted),
                    'dag_area_k_deducted' => intval($k_deducted),
                    'dag_area_lc_deducted' => round($l_deducted,4),
                    'dag_area_g_deducted' => round($g_deducted,4),
                    'is_splitted' => 'y',
                    'dag_area_are' => round($left_gandas * (13.37804 / 6400),4),
                    'dag_area_are_deducted' => round($dag_area_are_deducted + ($submitted_gandas_total * (13.37804 / 6400)),4)
                ];
            } else {
                $available_lessa_total = $b_left_old * 100 + $k_left_old * 20 + $l_left_old;
                $submitted_lessa_total = $b_submitted * 100 + $k_submitted * 20 + $l_submitted;
                $deducted_lessa_total = $b_deducted_old * 100 + $k_deducted_old* 20 + $l_deducted_old;
                $to_be_deducted_lessa_total = $submitted_lessa_total + $deducted_lessa_total;
                $left_lessa = $available_lessa_total - $submitted_lessa_total;

                $b_left = $left_lessa / 100;
                $l_left = $b_left % 100;
                $k_left = $l_left / 20;
                $l_left = $l_left % 20;

                $b_deducted = $to_be_deducted_lessa_total / 100;
                $l_deducted = $to_be_deducted_lessa_total % 100;
                $k_deducted = $l_deducted / 20;
                $l_deducted = $l_deducted % 20;
                return [
                    'dag_area_b' => intval($b_left),
                    'dag_area_k' => intval($k_left),
                    'dag_area_lc' => round($l_left,4),
                    'dag_area_b_deducted' => intval($b_deducted),
                    'dag_area_k_deducted' => intval($k_deducted),
                    'dag_area_lc_deducted' => round($l_deducted,4),
                    'is_splitted' => 'y',
                    'dag_area_are' => round($left_lessa * (100 / 747.45),4),
                    'dag_area_are_deducted' => round($dag_area_are_deducted + ($submitted_lessa_total * (100 / 747.45)),4)
                ];
            }
        }
        return false;
    }
    public function getDagArea()
    {
        $dist_code = $this->session->userdata('dag_dist_code');
        $subdiv_code = $this->session->userdata('dag_subdiv_code');
        $cir_code = $this->session->userdata('dag_cir_code');
        $mouza_pargona_code = $this->session->userdata('dag_mouza_pargona_code');
        $lot_no = $this->session->userdata('dag_lot_no');
        $vill_code = $this->session->userdata('dag_vill_code');
        $patta_type_code = $this->session->userdata('dag_patta_type_code');
        $patta_no = $this->session->userdata('dag_patta_no');
        $dag_no = $this->session->userdata('dag_dag_no');

        $where = "(dist_code='$dist_code' and subdiv_code='$subdiv_code' and cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_code' and patta_type_code='$patta_type_code' and patta_no='$patta_no' and dag_no='$dag_no')";
        $this->db->select('dag_area_b,dag_area_b_deducted,dag_area_are_deducted,dag_area_k,dag_area_k_deducted,dag_area_lc,dag_area_lc_deducted,dag_area_g,dag_area_g_deducted,dag_area_are,dag_revenue,dag_local_tax,land_class_code,old_dag_no,dag_no,patta_no,patta_type_code,dag_nlrg_no,dp_flag_yn,dag_area_are,dag_n_desc,dag_s_desc,dag_e_desc,dag_w_desc,dag_n_dag_no,dag_s_dag_no,dag_e_dag_no,dag_w_dag_no');
        $query = $this->db->get_where('chitha_basic', $where);
        $dag = $query->row();
        if ($dag) {
            if (($this->session->userdata('dag_dist_code') == '21') || ($this->session->userdata('dag_dist_code') == '22') || ($this->session->userdata('dag_dist_code') == '23')) {
                $b = $dag->dag_area_b;
                $k = $dag->dag_area_k;
                $l = $dag->dag_area_lc;
                $g = $dag->dag_area_g;
                $area_ganda = $b * 6400 + $k * 320 + $l * 20 + $g;
                $area_are_b = $area_ganda * (13.37804 / 6400);
                $total_area_are_b = $area_are_b;

                $b_deducted = $dag->dag_area_b_deducted;
                $k_deducted = $dag->dag_area_k_deducted;
                $l_deducted = $dag->dag_area_lc_deducted;
                $g_deducted = $dag->dag_area_g_deducted;
                $area_ganda_deducted = $b_deducted * 6400 + $k_deducted * 320 + $l_deducted * 20 + $g_deducted;
                $area_are_b_deducted = $area_ganda_deducted * (13.37804 / 6400);
                $total_area_are_b_deducted = $area_are_b_deducted;

                return ['area' => $total_area_are_b, 'area_deducted' => $total_area_are_b_deducted,'area_deducted_total' => $dag->dag_area_are_deducted];
            } else {
                $b = $dag->dag_area_b;
                $k = $dag->dag_area_k;
                $l = $dag->dag_area_lc;
                $area_lessa = $b * 100 + $k * 20 + $l;
                $area_are = $area_lessa * (100 / 747.45);
                $total_area_are = $area_are;

                $b_deducted = $dag->dag_area_b_deducted;
                $k_deducted = $dag->dag_area_k_deducted;
                $l_deducted = $dag->dag_area_lc_deducted;
                $area_lessa_deducted = $b_deducted * 100 + $k_deducted * 20 + $l_deducted;
                $area_are_deducted = $area_lessa_deducted * (100 / 747.45);
                $total_area_are_b_deducted = $area_are_deducted;
                
                return ['area' => $total_area_are, 'area_deducted' => $total_area_are_b_deducted,'area_deducted_total' => $dag->dag_area_are_deducted];
            }
        }
        return false;
    }
    public function getSubmittedDagArea()
    {
        $b    = $this->input->post('dag_area_b');
        $k    = $this->input->post('dag_area_k');
        $l   = $this->input->post('dag_area_lc');
        $g    = $this->input->post('dag_area_g') ? $this->input->post('dag_area_g') : 0;
        if (($this->session->userdata('dag_dist_code') == '21') || ($this->session->userdata('dag_dist_code') == '22') || ($this->session->userdata('dag_dist_code') == '23')) {
            $area_ganda = $b * 6400 + $k * 320 + $l * 20 + $g;
            $area_are_b = $area_ganda * (13.37804 / 6400);
            $total_area_are_b = $area_are_b;
            return $total_area_are_b;
        } else {
            $area_lessa = $b * 100 + $k * 20 + $l;
            $area_are = $area_lessa * (100 / 747.45);
            $total_area_are = $area_are;
            return $total_area_are;
        }
    }
}
