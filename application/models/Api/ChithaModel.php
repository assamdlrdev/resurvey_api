<?php

class ChithaModel extends CI_Model {
    


    public function getchithaDetailsALL($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag_no_lower, $dag_no_upper) {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');

        $chithaBasicSplittedDags = $this->db->query("SELECT * FROM chitha_basic_splitted_dags WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND survey_no=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag_no_lower])->row();

        $chithaBasic = $this->db->query("SELECT * FROM chitha_basic WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND dag_no=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag_no_lower])->row();

        if(empty($chithaBasic)) {
            return false;
        }
        
        $landClassDetails = $this->db->query("SELECT name, name_ass FROM land_class_groups WHERE land_class_code=?", [$chithaBasic->land_class_code])->row();
        if(!empty($landClassDetails)) {
            $chithaBasic->land_class_name = $landClassDetails->name_ass . " (" . $landClassDetails->name . ")";
        }
        else {
            $chithaBasic->land_class_name = "";
        }

        $pattaTypeDetails = $this->db->query("SELECT patta_type, pattatype_eng FROM patta_code WHERE type_code=?", [$chithaBasic->patta_type_code])->row();
        if(!empty($pattaTypeDetails)) {
            $chithaBasic->patta_type_name = $pattaTypeDetails->patta_type . " (" . $pattaTypeDetails->pattatype_eng . ")";
        }
        else {
            $chithaBasic->patta_type_name = "";
        }
        
        $pattadars = $this->db->query("SELECT cdp.*, cp.pdar_name, cp.pdar_father FROM chitha_dag_pattadar cdp, chitha_pattadar cp WHERE cdp.dist_code=cp.dist_code AND cdp.subdiv_code=cp.subdiv_code AND cdp.cir_code=cp.cir_code AND cdp.mouza_pargona_code=cp.mouza_pargona_code AND cdp.lot_no=cp.lot_no AND cdp.vill_townprt_code=cp.vill_townprt_code AND cdp.patta_no=cp.patta_no AND cdp.patta_type_code=cp.patta_type_code AND cdp.pdar_id=cp.pdar_id AND cdp.dist_code=? AND cdp.subdiv_code=? AND cdp.cir_code=? AND cdp.mouza_pargona_code=? AND cdp.lot_no=? AND cdp.vill_townprt_code=? AND cdp.dag_no=?", [$chithaBasic->dist_code, $chithaBasic->subdiv_code, $chithaBasic->cir_code, $chithaBasic->mouza_pargona_code, $chithaBasic->lot_no, $chithaBasic->vill_townprt_code, $chithaBasic->dag_no]);

        $occupiers = $this->db->query("SELECT * FROM splitted_dags_possessors WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND old_dag_no=? AND part_dag=?", [$chithaBasic->dist_code, $chithaBasic->subdiv_code, $chithaBasic->cir_code, $chithaBasic->mouza_pargona_code, $chithaBasic->lot_no, $chithaBasic->vill_townprt_code, $chithaBasicSplittedDags->dag_no, $chithaBasic->dag_no])->result();

        $partDags = [
            'chitha_basic' => $chithaBasic,
            'pattadars' => $pattadars,
            'occupiers' => $occupiers
        ];

        $oldChitha = $this->db->query("SELECT * FROM chitha_basic WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND dag_no=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $chithaBasicSplittedDags->dag_no])->row();

        $oldLandClassDetails = $this->db->query("SELECT land_type, landtype_eng FROM landclass_code WHERE class_code=?", [$oldChitha->land_class_code])->row();
        if(!empty($oldLandClassDetails)) {
            $oldChitha->land_class_name = $oldLandClassDetails->land_type . " (" . $oldLandClassDetails->landtype_eng . ")";
        }
        else {
            $oldChitha->land_class_name = "";
        }

        $oldPattaTypeDetails = $this->db->query("SELECT patta_type, pattatype_eng FROM patta_code WHERE type_code=?", [$oldChitha->patta_type_code])->row();
        if(!empty($oldPattaTypeDetails)) {
            $oldChitha->patta_type_name = $oldPattaTypeDetails->patta_type . " (" . $oldPattaTypeDetails->pattatype_eng . ")";
        }
        else {
            $oldChitha->patta_type_name = "";
        }

        $oldPattadars = $this->db->query("SELECT cdp.*, cp.pdar_name, cp.pdar_father FROM chitha_dag_pattadar cdp, chitha_pattadar cp WHERE cdp.dist_code=cp.dist_code AND cdp.subdiv_code=cp.subdiv_code AND cdp.cir_code=cp.cir_code AND cdp.mouza_pargona_code=cp.mouza_pargona_code AND cdp.lot_no=cp.lot_no AND cdp.vill_townprt_code=cp.vill_townprt_code AND cdp.patta_no=cp.patta_no AND cdp.patta_type_code=cp.patta_type_code AND cdp.pdar_id=cp.pdar_id AND cdp.dist_code=? AND cdp.subdiv_code=? AND cdp.cir_code=? AND cdp.mouza_pargona_code=? AND cdp.lot_no=? AND cdp.vill_townprt_code=? AND cdp.dag_no=?", [$oldChitha->dist_code, $oldChitha->subdiv_code, $oldChitha->cir_code, $oldChitha->mouza_pargona_code, $oldChitha->lot_no, $oldChitha->vill_townprt_code, $oldChitha->dag_no])->result();

        $originalDags = [
            'chitha_basic' => $oldChitha,
            'pattadars' => $oldPattadars
        ];

        $data = [
            'part_dag' => $partDags,
            'original_dag' => $originalDags
        ];

        return $data;
    }
}