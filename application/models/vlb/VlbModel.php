<?php
class VlbModel extends CI_Model
{
    public function checkPreviousEntries($dist_code, $subdiv_code, $circle_code, $mouza_code, $lot_no, $vill_code, $dag_no)
    {
        $this->db->select("*")
            ->order_by('id', "DESC")
            ->where('dist_code', $dist_code)
            ->where('subdiv_code', $subdiv_code)
            ->where('cir_code', $circle_code)
            ->where('mouza_pargona_code', $mouza_code)
            ->where('lot_no', $lot_no)
            ->where('vill_townprt_code', $vill_code)
            ->where('dag_no ', $dag_no)
            ->from('land_bank_details');
        $query = $this->db->get();
        return $query->row();
    }
    public function updateVlb($vlb_details, $encroachers)
    {
        error_reporting(0);
        $land_bank_existing_details = $this->checkPreviousEntries($vlb_details['dist_code'], $vlb_details['subdiv_code'], $vlb_details['cir_code'], $vlb_details['mouza_pargona_code'], $vlb_details['lot_no'], $vlb_details['vill_townprt_code'], $vlb_details['dag_no']);
        $this->db->trans_begin();
        $this->db->where('id', $land_bank_existing_details->id)->update('land_bank_details', $vlb_details);
        if ($this->db->affected_rows() != 1) {
            $this->db->trans_rollback();
            log_message("error", "#LBSY001U, Error in update, table 'land_bank_details' with data :" . json_encode($vlb_details));
            echo json_encode(['status' => '0', 'msg' => 'Some error occured, Error-Code : #LBSY001U']);
        }

        if ($vlb_details['whether_encroached'] == 'N') {
            $this->db->where('land_bank_details_id', $land_bank_existing_details->id)->delete('land_bank_encroacher_details');
            if ($this->db->affected_rows() != 1) {
                $this->db->trans_rollback();
                log_message("error", "#LBSY002U, Error in delete, table 'land_bank_encroacher_details' ");
                echo json_encode(['status' => '0', 'msg' => 'Some error occured, Error-Code : #LBSY002U']);
            }
        } else {
            foreach ($encroachers as $encroacher) {
                $data_encroacher = [
                    'land_bank_details_id' => $land_bank_existing_details->id,
                    'name' => $encroacher->name,
                    'fathers_name' => $encroacher->fathers_name,
                    'gender' => $encroacher->gender,
                    'encroachment_from' => $encroacher->encroachment_from,
                    'encroachment_to' => $encroacher->encroachment_to,
                    'landless_indigenous' => $encroacher->landless_indigenous,
                    'landless' => $encroacher->landless,
                    'caste' => $encroacher->caste,
                    'erosion' => $encroacher->erosion,
                    'landslide' => $encroacher->landslide,
                    'type_of_land_use' => $encroacher->type_of_land_use,
                    'type_of_encroacher' => $encroacher->type_of_encroacher,
                    'created_at' => date('Y-m-d H:i:s')
                ];

                if ($encroacher->id) {
                    $this->db->where('id', $encroacher->id)->where('land_bank_details_id', $land_bank_existing_details->id)->update('land_bank_encroacher_details', $data_encroacher);
                    if ($this->db->affected_rows() != 1) {
                        $this->db->trans_rollback();
                        log_message("error", "#LBSY004U, Error in update(existing), table 'land_bank_encroacher_details' with data :" . json_encode($encroacher));
                        echo json_encode(['status' => '0', 'msg' => 'Some error occured, Error-Code : #LBSY004U']);
                    }
                } else {
                    $insert_lb_enc = $this->db->insert('land_bank_encroacher_details', $data_encroacher);
                    if ($this->db->affected_rows() != 1) {
                        $this->db->trans_rollback();
                        log_message("error", "#LBSY004U, Error in update(existing), table 'land_bank_encroacher_details' with data :" . json_encode($encroacher));
                        echo json_encode(['status' => '0', 'msg' => 'Some error occured, Error-Code : #LBSY004U']);
                    }
                }
            }
        }

        if ($this->db->trans_status() == FALSE) {
            $this->db->trans_rollback();
            log_message("error", "#LBSY0010U, Transaction Status Error In Land Bank Tables on Updation");
            echo json_encode(['status' => '0', 'msg' => 'Some error occured, Error-Code : #LBSY0010U']);
        } else {
            $this->db->trans_commit();
            echo json_encode(['status' => '1', 'msg' => 'Land Bank Details Updated Successfully And Forwarded to CO For Approval!']);
        }
    }
    public function getVlbListByLocation($subdiv_code = null, $cir_code = null, $mouza_code = null, $lot_no = null, $vill_code = null, $user_code = null, $status)
    {
        $dist_code = $this->session->userdata('dcode');
        if ($user_code) {
            $this->db->where('user_code', $user_code);
        }
        $this->db->where('dist_code', $dist_code);
        if ($subdiv_code) {
            $this->db->where('subdiv_code', $subdiv_code);
        }
        if ($cir_code) {
            $this->db->where('cir_code', $cir_code);
        }
        if ($mouza_code) {
            $this->db->where('mouza_pargona_code', $mouza_code);
        }
        if ($lot_no) {
            $this->db->where('lot_no', $lot_no);
        }
        if ($vill_code) {
            $this->db->where('vill_townprt_code', $vill_code);
        }
        $this->db->select("*")
            ->order_by('id', "DESC")
            ->where_in('status', $status);
        if ($status[0] == LAND_BANK_STATUS_APPROVED) {
            $this->db->from('c_land_bank_details');
        } else {
            $this->db->from('land_bank_details');
        }
        return $this->db->get()->result();
    }
    public function getVlbNotInClb($dist_code = null, $subdiv_code = null, $cir_code = null, $mouza_code = null, $lot_no = null, $vill_code = null)
    {
        if (!$dist_code) {
            $dist_code = $this->session->userdata('dcode');
        }
        $sub_query = "select count(*) as c from chitha_basic ";
        if ($dist_code) {
            $loc_query  = "where dist_code='$dist_code' ";
        }
        if ($subdiv_code) {
            $loc_query  = $loc_query . "and subdiv_code='$subdiv_code' ";
        }
        if ($cir_code) {
            $loc_query  = $loc_query . "and cir_code='$cir_code' ";
        }
        if ($mouza_code) {
            $loc_query  = $loc_query . "and mouza_pargona_code='$mouza_code' ";
        }
        if ($lot_no) {
            $loc_query  = $loc_query . "and lot_no='$lot_no' ";
        }
        if ($vill_code) {
            $loc_query  = $loc_query . "and vill_townprt_code='$vill_code' ";
        }
        $sub_query2 = $sub_query . $loc_query . "and patta_type_code in (select type_code from patta_code 
        where jamabandi='n') and (dag_area_b*100+dag_area_k*20+dag_area_lc::int) > 0
        and (dist_code,subdiv_code,cir_code,mouza_pargona_code,lot_no,vill_townprt_code,trim(dag_no)) 
        not in (select dist_code,subdiv_code,cir_code,mouza_pargona_code,lot_no,vill_townprt_code,
        trim(dag_no) from c_land_bank_details " . $loc_query;

        $sql = $sub_query2 . ") and (dist_code,subdiv_code,cir_code,mouza_pargona_code,lot_no,
                vill_townprt_code) in (select dist_code,subdiv_code,cir_code,mouza_pargona_code,lot_no,
                vill_townprt_code from location where nc_btad is null)";

        $result = $this->db->query($sql);
        if ($result->num_rows() > 0) {
            $result = $result->row()->c;
        } else {
            $result = 0;
        }
        return $result;
    }
    public function getLBRevertUser($landBankId)
    {
        $sql = "select LEFT(approved_by, 2)  as revert_user from land_bank_proceeding_details where land_bank_details_id=? and status = ?";
        $query = $this->db->query($sql, array($landBankId, LAND_BANK_STATUS_REVERT_BACK));
        return $query->row()->revert_user;
    }
    public function approveVlbByCo($land_banK_details_id, $co_remark)
    {
        error_reporting(0);
        date_default_timezone_set("Asia/Calcutta");

        $land_bank_details = $this->db->select('*')->where('id',  $land_banK_details_id)->from('land_bank_details')->get()->row();
        $total_encroacher_updated = $this->db->select('count(*) as total')->where('land_bank_details_id',  $land_banK_details_id)->from('land_bank_encroacher_details')->get()->row()->total;

        $this->db->where('id', $land_banK_details_id)->update('land_bank_details', array(
            'status' => LAND_BANK_STATUS_APPROVED,
            'no_of_encroacher' => $total_encroacher_updated
        ));
        if ($this->db->affected_rows() != 1) {
            $this->db->trans_rollback();
            log_message("error", "#LBDC001U, Error in update, table 'land_bank_details' in changing status to approved");
            echo json_encode(['status' => '0', 'msg' => 'Some error occured, Error-Code : #LBDC001U']);
        }

        $insert_proceeding_status = $this->insertVlbProceeding($land_banK_details_id, $co_remark);
        if ($insert_proceeding_status != 1) {
            $this->db->trans_rollback();
            log_message("error", "#LBDC002U, Error in insert on land_bank_proceeding_details table with land bank details id " . $land_banK_details_id);
            echo json_encode(['status' => '0', 'msg' => 'Some error occured, Error-Code : #LBDC002U']);
        }

        $insert_vlb_in_clb = $this->insertVlbInClb($land_bank_details);
        if ($insert_vlb_in_clb == FALSE) {
            $this->db->trans_rollback();
            log_message("error", "#LBDC006U, Transaction Status Error In Land Bank Tables on Updation in CO profile");
            echo json_encode(['status' => '0', 'msg' => 'Some error occured, Error-Code : #LBDC006U']);
        } else {
            echo json_encode(['status' => '1', 'msg' => 'Land Bank Details Approved Successfully!']);
        }
    }

    public function insertVlbProceeding($land_banK_details_id, $co_remark)
    {
        $status = $this->db->insert('land_bank_proceeding_details', array(
            'land_bank_details_id' => $land_banK_details_id,
            'remark' => $co_remark,
            'status' => LAND_BANK_STATUS_APPROVED,
            'created_at' => date('Y-m-d H:i:s'),
            'approved_by' => $this->session->userdata('usercode')
        ));
        return $status;
    }

    public function insertVlbInClb($land_bank_details)
    {
        $this->db->select('id')
            ->where('village_uuid',  $land_bank_details->village_uuid)
            ->where('dag_no',  $land_bank_details->dag_no)
            ->from('c_land_bank_details');
        $query = $this->db->get();

        //delete exisiting clb and clb_encroachers
        if ($query->num_rows() != null && $query->num_rows() != '' && $query->num_rows() > 0) {
            $c_land_bank_details = $query->result();
            foreach ($c_land_bank_details as $c_land_bank_details) {
                $this->db->where('id', $c_land_bank_details->id)->delete('c_land_bank_details');
                if ($this->db->affected_rows() != 1) {
                    $this->db->trans_rollback();
                    log_message("error", "#LBDC0010U, Error in delete, table 'c_land_bank_details' with id " . $c_land_bank_details->id);
                    echo json_encode(['status' => '0', 'msg' => 'Some error occured, Error-Code : #LBDC0010U']);
                }
                $this->db->where('c_land_bank_details_id', $c_land_bank_details->id)->delete('c_land_bank_encroacher_details');
                if ($this->db->affected_rows() <= 0) {
                    $this->db->trans_rollback();
                    log_message("error", "#LBDC0010U, Error in delete, table 'c_land_bank_details' with id " . $c_land_bank_details->id);
                    echo json_encode(['status' => '0', 'msg' => 'Some error occured, Error-Code : #LBDC0010U']);
                }
            }
        }

        //insert vlb in clb
        $land_bank_details->status = LAND_BANK_STATUS_APPROVED;
        $lb_id = $land_bank_details->id;
        unset($land_bank_details->id);
        $insert_clb_status = $this->db->insert('c_land_bank_details', $land_bank_details);

        if ($insert_clb_status != 1) {
            $this->db->trans_rollback();
            log_message("error", "#LBDC004U, Error in insert on c_land_bank_details table");
            echo json_encode(['status' => '0', 'msg' => 'Some error occured, Error-Code : #LBDC004U']);
        }
        $c_land_bank_inserted_id = $this->db->insert_id();

        //insert vlb encroachers in clb encroachers
        $this->db->select('*')
            ->where('land_bank_details_id',  $lb_id)
            ->from('land_bank_encroacher_details');
        $query = $this->db->get();
        $land_bank_encroacher_details = $query->result_array();

        foreach ($land_bank_encroacher_details as $land_bank_encroacher_detail) {
            unset($land_bank_encroacher_detail['land_bank_details_id']);
            $land_bank_encroacher_detail['c_land_bank_details_id'] = $c_land_bank_inserted_id;
            $c_encroacher_insert_status = $this->db->insert('c_land_bank_encroacher_details', $land_bank_encroacher_detail);
            if ($c_encroacher_insert_status != 1) {
                $this->db->trans_rollback();
                log_message("error", "#LBDC005U, Error in insert on c_land_bank_encroacher_details table");
                echo json_encode(['status' => '0', 'msg' => 'Some error occured, Error-Code : #LBDC005U']);
            }
        }

        return $this->db->trans_status();
    }

    public function revertVlbCo($lb_details_id, $reject_remark)
    {
        $this->db->trans_begin();
        $this->db->where('id', $lb_details_id)->update('land_bank_details', array(
            'status' => LAND_BANK_STATUS_REVERT_BACK
        ));
        if ($this->db->affected_rows() != 1) {
            $this->db->trans_rollback();
            log_message("error", "#LBCOR001, Error in update, table 'land_bank_details' in updating reject status");
            echo json_encode(['status' => '0', 'msg' => 'Some error occured, Error-Code : #LBCOR001']);
        }

        //insert data in land bank proceeding details 
        $status = $this->db->insert('land_bank_proceeding_details', array(
            'land_bank_details_id' => $lb_details_id,
            'remark' => $reject_remark,
            'status' => LAND_BANK_STATUS_REVERT_BACK,
            'created_at' => date('Y-m-d H:i:s'),
            'approved_by' => $this->session->userdata('usercode')
        ));
        if ($status != 1) {
            $this->db->trans_rollback();
            log_message("error", "#LBCOR002, Error in insert on land_bank_proceeding_details table with land bank details id " . $lb_details_id);
            echo json_encode(['status' => '0', 'msg' => 'Some error occured, Error-Code : #LBCOR002']);
        }

        if ($this->db->trans_status() == FALSE) {
            $this->db->trans_rollback();
            log_message("error", "#LBCOR003, Transaction Status Error In Land Bank Tables on Rejection in CO profile");
            echo json_encode(['status' => '0', 'msg' => 'Some error occured, Error-Code : #LBCOR003']);
        } else {
            $this->db->trans_commit();
            echo json_encode(['status' => '1', 'msg' => 'Land Bank Details Reverted Successfully!']);
        }
    }

    public function getVillageListWithGovtDaag($dist_code, $subdiv_code, $cir_code, $mouza_code, $lot_no, $flag)
    {

        if ($flag == 1) {
            $sql = "select distinct on (dist_code,subdiv_code,cir_code,mouza_pargona_code,lot_no,
                vill_townprt_code) dist_code,subdiv_code,cir_code,mouza_pargona_code,lot_no,
                vill_townprt_code from chitha_basic where dist_code=? and subdiv_code=? 
                and cir_code=? and mouza_pargona_code=? and lot_no=? and patta_type_code in 
                (select type_code from patta_code where jamabandi='n') and 
                (dag_area_b*100+dag_area_k*20+dag_area_lc::int) > 0
		        and (dist_code,subdiv_code,cir_code,mouza_pargona_code,lot_no,vill_townprt_code,dag_no)
                not in (select dist_code,subdiv_code,cir_code,mouza_pargona_code,lot_no,vill_townprt_code,
                dag_no from land_bank_details where dist_code=? and subdiv_code=? and cir_code=?
                and mouza_pargona_code=? and lot_no=? and status in (?,?)) and (dist_code,subdiv_code,
                cir_code,mouza_pargona_code,lot_no,vill_townprt_code) in (select dist_code,subdiv_code,
                cir_code,mouza_pargona_code,lot_no,vill_townprt_code from location where nc_btad is null)";

            $query = $this->db->query($sql, array(
                $dist_code, $subdiv_code, $cir_code, $mouza_code, $lot_no,
                $dist_code, $subdiv_code, $cir_code, $mouza_code, $lot_no, LAND_BANK_STATUS_PENDING, LAND_BANK_STATUS_APPROVED
            ));
        } else {
            $sql = "select distinct vill_townprt_code from chitha_basic where dist_code = ? and subdiv_code = ?
                and cir_code = ? and mouza_pargona_code = ? and lot_no = ? and patta_type_code  in 
                (select type_code from patta_code where jamabandi='n') and 
                (dag_area_b*100+dag_area_k*20+dag_area_lc::int) > 0 and 
                (subdiv_code,cir_code,mouza_pargona_code, lot_no,vill_townprt_code) 
                in (select subdiv_code,cir_code,mouza_pargona_code, lot_no,vill_townprt_code from 
                location where nc_btad is null or TRIM(nc_btad) = '')";

            $query = $this->db->query($sql, array($dist_code, $subdiv_code, $cir_code, $mouza_code, $lot_no));
        }

        //echo $this->db->last_query();
        $village_codes = $query->result();
        $villageCodeWithNames =  array();
        foreach ($village_codes as $village_code) {
            array_push($villageCodeWithNames, [
                'village_code' => $village_code->vill_townprt_code,
                'village_name' => $this->utilityclass->getVillageName($dist_code, $subdiv_code, $cir_code, $mouza_code, $lot_no, $village_code->vill_townprt_code)
            ]);
        }
        return $villageCodeWithNames;
    }
    public function getVlbRejectedRmk($lb_details_id){
        $this->db->select("*")
                ->order_by('id',"DESC")
                ->where('land_bank_details_id', $lb_details_id)
                ->where('status', LAND_BANK_STATUS_REVERT_BACK)
                ->from('land_bank_proceeding_details');
        $query = $this->db->get(); 
        return $query->row();
    }
}
