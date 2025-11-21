<?php

class LocationModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

    public function mergeVillageData($loc, $usercode) {
        $locArr = explode('-', $loc);
        $dist_code = $locArr[0];
        $subdiv_code = $locArr[1];
        $cir_code = $locArr[2];
        $mouza_pargona_code = $locArr[3];
        $lot_no = $locArr[4];
        $vill_townprt_code = $locArr[5];

        $checkMergingStatus = $this->db->query("SELECT is_merged FROM resurvey_villages WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code])->row();
        if (empty($checkMergingStatus)) {
           
            //merge and update
            $mergeStatus = $this->mergeVillage($loc);
            if ($mergeStatus['status'] != 'y') {
               
                $response = [
                    'status' => 'n',
                    'msg' => 'Could not sync dharitree data with chitha!'
                ];
               
                return $response;
            }

            //insert into resurvey_villages
            $insertArr = [
                'dist_code' => $dist_code,
                'subdiv_code' => $subdiv_code,
                'cir_code' => $cir_code,
                'mouza_pargona_code' => $mouza_pargona_code,
                'lot_no' => $lot_no,
                'vill_townprt_code' => $vill_townprt_code,
                'is_merged' => 1,
                'user_code' => $usercode,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $insertStatus = $this->db->insert('resurvey_villages', $insertArr);
            if (!$insertStatus || $this->db->affected_rows() < 1) {
                
                $response = [
                    'status' => 'n',
                    'msg' => 'Could not update into resurvey villages!'
                ];
                
                return $response;
            }

            return [
                'status' => 'y',
                'msg' => 'Village Merged Successfully !'
            ];

        } else {
            if ($checkMergingStatus->is_merged != 1) {
                
                $mergeStatus = $this->mergeVillage($loc);
                if ($mergeStatus['status'] != 'y') {
                    
                    $response = [
                        'status' => 'n',
                        'msg' => 'Could not sync dharitree data with chitha!'
                    ];
                   
                    return $response;
                }
                $updArr = [
                    'is_merged' => 1
                ];
                $this->db->where([
                    'dist_code' => $dist_code,
                    'subdiv_code' => $subdiv_code,
                    'cir_code' => $cir_code,
                    'mouza_pargona_code' => $mouza_pargona_code,
                    'lot_no' => $lot_no,
                    'vill_townprt_code' => $vill_townprt_code
                ]);
                $updStatus = $this->db->update('resurvey_villages', $updArr);
                if (!$updStatus || $this->db->affected_rows() < 1) {
                   
                    $response = [
                        'status' => 'n',
                        'msg' => 'Could not update into resurvey villages!'
                    ];
                   
                    return $response;
                }

                return [
                    'status' => 'y',
                    'msg' => 'Village Merged Successfully !'
                ];
               
            }
            else {
                return [
                    'status' => 'y',
                    'msg' => 'Village already merged !'
                ];
            }
        }
    }



    public function mergeVillage($input_string)
    {
        // $input_string = $this->input->post('vill_townprt_code', true);
        $inputArr = explode('-', $input_string);
        $dist_code = $inputArr[0];
        $subdiv_code = $inputArr[1];
        $cir_code = $inputArr[2];
        $mouza_pargona_code = $inputArr[3];
        $lot_no = $inputArr[4];
        $vill_townprt_code = $inputArr[5];

        // $this->dbswitch($dist_code);
        // $this->db->trans_begin();

        $location = $this->db->query("SELECT * FROM location WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code])->row();

        if (empty($location)) {
            $locationApi = callLandhubAPIMerge('POST', 'NicApiMerge/getLocation', [
                'dist_code' => $dist_code,
                'subdiv_code' => $subdiv_code,
                'cir_code' => $cir_code,
                'mouza_pargona_code' => $mouza_pargona_code,
                'lot_no' => $lot_no,
                'vill_townprt_code' => $vill_townprt_code
            ]);

            if ($locationApi->responseType != 2) {
                // $this->db->trans_rollback();
                return [
                    'status' => 'n',
                    'msg' => 'Could not retrieve location from API for merging!'
                ];
                // echo json_encode([
                //     "status" => "FAILED",
                //     "responseType" => 1,
                //     "msg" => "Could not retrieve location from API"
                // ]);
                // exit;
            }

            if (empty($locationApi->data)) {
                // $this->db->trans_rollback();
                return [
                    'status' => 'n',
                    'msg' => 'Location does not exist in dharitree!'
                ];
                // echo json_encode([
                //     "status" => "FAILED",
                //     "responseType" => 1,
                //     "msg" => "Location does not exist in dharitree"
                // ]);
                // exit;
            }

            // if(!empty($locationApi) && !empty($locationApi->data)) {
            //insert
            $insertLocationArr = [
                'dist_code' => $dist_code,
                'subdiv_code' => $subdiv_code,
                'cir_code' => $cir_code,
                'mouza_pargona_code' => $mouza_pargona_code,
                'lot_no' => $lot_no,
                'vill_townprt_code' => $vill_townprt_code,
                'loc_name' => $locationApi->data->loc_name,
                'unique_loc_code' => $locationApi->data->unique_loc_code,
                'locname_eng' => $locationApi->data->locname_eng,
                'cir_abbr' => $locationApi->data->cir_abbr,
                'dist_abbr' => $locationApi->data->dist_abbr,
                'rural_urban' => $locationApi->data->rural_urban,
                'uuid' => $locationApi->data->uuid,
                'is_gmc' => (isset($locationApi->data->is_gmc) && $locationApi->data->is_gmc != null) ? $locationApi->data->is_gmc : null,
                'lgd_code' => (isset($locationApi->data->lgd_code) && $locationApi->data->lgd_code != null) ? $locationApi->data->lgd_code : null,
                'village_status' => (isset($locationApi->data->village_status) && $locationApi->data->village_status != null) ? $locationApi->data->village_status : null,
                'is_map' => (isset($locationApi->data->is_map) && $locationApi->data->is_map != null) ? $locationApi->data->is_map : null,
                'created_date' => (isset($locationApi->data->created_date) && $locationApi->data->created_date != null) ? $locationApi->data->created_date : null,
                'updated_date' => (isset($locationApi->data->updated_date) && $locationApi->data->updated_date != null) ? $locationApi->data->updated_date : null,
                'user_code' => (isset($locationApi->data->user_code) && $locationApi->data->user_code != null) ? $locationApi->data->user_code : null,
                'status' => (isset($locationApi->data->status) && $locationApi->data->status != null) ? $locationApi->data->status : null,
                'nc_btad' => (isset($locationApi->data->nc_btad) && $locationApi->data->nc_btad != null) ? $locationApi->data->nc_btad : null,
                'is_periphary' => (isset($locationApi->data->is_periphary) && $locationApi->data->is_periphary != null) ? $locationApi->data->is_periphary : null,
                'is_tribal' => (isset($locationApi->data->is_tribal) && $locationApi->data->is_tribal != null) ? $locationApi->data->is_tribal : null,
                'district_headquater' => (isset($locationApi->data->district_headquater) && $locationApi->data->district_headquater != null) ? $locationApi->data->district_headquater : null
            ];
            // if($this->db->field_exists('village_status', 'location')) {
            //     $insertLocationArr['village_status'] = isset($locationApi->data->village_status) ? $locationApi->data->village_status : null;
            // }


            $status = $this->db->insert('location', $insertLocationArr);
            if (!$status || $this->db->affected_rows() < 1) {
                // $this->db->trans_rollback();
                return [
                    'status' => 'n',
                    'msg' => 'Could not insert location in chitha!'
                ];
                // echo json_encode([
                //     "status" => "FAILED",
                //     "responseType" => 1,
                //     "msg" => "Could not insert location in chitha"
                // ]);
                // exit;
            }
            // }
        }

        $dagsApi = callLandhubAPIMerge('POST', 'NicApiMerge/getDags', [
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'mouza_pargona_code' => $mouza_pargona_code,
            'lot_no' => $lot_no,
            'vill_townprt_code' => $vill_townprt_code
        ]);

        if ($dagsApi->responseType != 2) {
            // $this->db->trans_rollback();
            return [
                'status' => 'n',
                'msg' => 'Could not retrieve dags from API!'
            ];
            // echo json_encode([
            //     "status" => "FAILED",
            //     "responseType" => 1,
            //     "msg" => "Could not retrieve dags from API"
            // ]);
            // exit;
        }

        // if(empty($dagsApi->data)) {
        //     $this->db->trans_rollback();
        //     echo json_encode([
        //         "status" => "FAILED",
        //         "responseType" => 1,
        //         "msg" => "Nothing to merge. No dags available in this village!"
        //     ]);
        //     exit;
        // }

        if (!empty($dagsApi->data)) {
            foreach ($dagsApi->data as $dag) {
                //check in local database
                $dag_no = $dag->dag_no;
                $dag_exist = $this->db->query("SELECT dag_no FROM chitha_basic WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND dag_no=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag_no])->row();
                if (empty($dag_exist)) {
                    //then insert into chitha
                    $insertChithaArr = [
                        'dist_code' => $dist_code,
                        'subdiv_code' => $subdiv_code,
                        'cir_code' => $cir_code,
                        'mouza_pargona_code' => $mouza_pargona_code,
                        'lot_no' => $lot_no,
                        'vill_townprt_code' => $vill_townprt_code,
                        'dag_no' => $dag_no,
                        'dag_no_int' => $dag->dag_no_int,
                        // 'alpha_dag' => 0,
                        'old_dag_no' => $dag->old_dag_no,
                        'patta_type_code' => $dag->patta_type_code,
                        'patta_no' => $dag->patta_no,
                        'land_class_code' => $dag->land_class_code,
                        'dag_area_b' => $dag->dag_area_b,
                        'dag_area_k' => $dag->dag_area_k,
                        'dag_area_lc' => $dag->dag_area_lc,
                        'dag_area_kr' => $dag->dag_area_kr,
                        'dag_area_g' => $dag->dag_area_g,
                        'dag_area_are' => $dag->dag_area_are,
                        'dag_revenue' => $dag->dag_revenue,
                        'dag_local_tax' => $dag->dag_local_tax,
                        'dag_n_desc' => $dag->dag_n_desc,
                        'dag_s_desc' => $dag->dag_s_desc,
                        'dag_e_desc' => $dag->dag_e_desc,
                        'dag_w_desc' => $dag->dag_w_desc,
                        'dag_n_dag_no' => $dag->dag_n_dag_no,
                        'dag_s_dag_no' => $dag->dag_s_dag_no,
                        'dag_e_dag_no' => $dag->dag_e_dag_no,
                        'dag_w_dag_no' => $dag->dag_w_dag_no,
                        'dag_nlrg_no' => (!empty($dag->dag_nlrg_no)) ? $dag->dag_nlrg_no : '',
                        'dp_flag_yn' => $dag->dp_flag_yn,
                        'user_code' => $dag->user_code, //
                        'date_entry' => $dag->date_entry, //
                        'old_patta_no' => $dag->old_patta_no,
                        'jama_yn' => $dag->jama_yn,
                        // 'survey_no' => $split_dag,
                        'operation' => $dag->operation, //
                        'status' => (isset($dag->status) && $dag->status != null) ? $dag->status : null,
                        'zonal_value' => (isset($dag->zonal_value) && $dag->zonal_value != null) ? $dag->zonal_value : null,
                        'police_station' => (isset($dag->police_station) && $dag->police_station != null) ? $dag->police_station : null,
                        'revenue_paid_upto' => (isset($dag->revenue_paid_upto) && $dag->revenue_paid_upto != null) ? $dag->revenue_paid_upto : null,
                        'block_code' => (isset($dag->block_code) && $dag->block_code != null) ? $dag->block_code : null,
                        'gp_code' => (isset($dag->gp_code) && $dag->gp_code != null) ? $dag->gp_code : null,
                        'category_id' => (isset($dag->category_id) && $dag->category_id != null) ? $dag->category_id : null
                    ];
                    $insertChithaStatus = $this->db->insert('chitha_basic', $insertChithaArr);
                    if (!$insertChithaStatus || $this->db->affected_rows() < 1) {
                        // $this->db->trans_rollback();
                        return [
                            'status' => 'n',
                            'msg' => 'Dag entry Failed in chitha basic!'
                        ];
                        // echo json_encode([
                        //     "status" => "FAILED",
                        //     "responseType" => 1,
                        //     "msg" => "Dag entry Failed in chitha basic"
                        // ]);
                        // exit;
                    }
                }
            }
        }

        //chitha_pattadars
        $chithaPattadars = callLandhubAPIMerge('POST', 'NicApiMerge/getChithaPattadars', [
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'mouza_pargona_code' => $mouza_pargona_code,
            'lot_no' => $lot_no,
            'vill_townprt_code' => $vill_townprt_code
        ]);

        if ($chithaPattadars->responseType != 2) {
            // $this->db->trans_rollback();
            return [
                'status' => 'n',
                'msg' => 'Could not retrieve from API!'
            ];
            // echo json_encode([
            //     "status" => "FAILED",
            //     "responseType" => 1,
            //     "msg" => "Could not retrieve from API"
            // ]);
            // exit;
        }
        // if(empty($chithaPattadars->data)) {
        //     $this->db->trans_rollback();
        //     echo json_encode([
        //         "status" => "FAILED",
        //         "responseType" => 1,
        //         "msg" => "Nothing to merge. No chitha pattadars available in this village!"
        //     ]);
        //     exit;
        // }
        if (!empty($chithaPattadars->data)) {
            foreach ($chithaPattadars->data as $chithaPdar) {
                $pdarCheck = $this->db->query("SELECT pdar_id, patta_no, patta_type_code FROM chitha_pattadar WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND patta_no=? AND patta_type_code=? AND pdar_id=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $chithaPdar->patta_no, $chithaPdar->patta_type_code, $chithaPdar->pdar_id])->row();

                if (empty($pdarCheck)) {
                    //insert chitha_pattadar
                    $chithaPattadarArr = [
                        'dist_code' => $dist_code,
                        'subdiv_code' => $subdiv_code,
                        'cir_code' => $cir_code,
                        'mouza_pargona_code' => $mouza_pargona_code,
                        'lot_no' => $lot_no,
                        'vill_townprt_code' => $vill_townprt_code,
                        'pdar_id' => $chithaPdar->pdar_id,
                        'patta_no' => $chithaPdar->patta_no,
                        'patta_type_code' => $chithaPdar->patta_type_code,
                        'pdar_name' => $chithaPdar->pdar_name,
                        'pdar_guard_reln' => $chithaPdar->pdar_guard_reln,
                        'pdar_father' => $chithaPdar->pdar_father,
                        'pdar_add1' => (isset($chithaPdar->pdar_add1) && $chithaPdar->pdar_add1 != null) ? $chithaPdar->pdar_add1 : null,
                        'pdar_add2' => (isset($chithaPdar->pdar_add2) && $chithaPdar->pdar_add2 != null) ? $chithaPdar->pdar_add2 : null,
                        'pdar_add3' => (isset($chithaPdar->pdar_add3) && $chithaPdar->pdar_add3 != null) ? $chithaPdar->pdar_add3 : null,
                        'pdar_pan_no' => (isset($chithaPdar->pdar_pan_no) && $chithaPdar->pdar_pan_no != null) ? $chithaPdar->pdar_pan_no : null,
                        'pdar_citizen_no' => (isset($chithaPdar->pdar_citizen_no) && $chithaPdar->pdar_citizen_no != null) ? $chithaPdar->pdar_citizen_no : null,
                        'pdar_gender' => (isset($chithaPdar->pdar_gender) && $chithaPdar->pdar_gender != null) ? $chithaPdar->pdar_gender : null,
                        'user_code' => $chithaPdar->user_code,
                        'date_entry' => $chithaPdar->date_entry,
                        'operation' => $chithaPdar->operation,
                        'jama_yn' => $chithaPdar->jama_yn,
                    ];
                    if ($this->db->field_exists('pdar_relation', 'chitha_pattadar') && isset($chithaPdar->pdar_relation) && $chithaPdar->pdar_relation != null) {
                        $chithaPattadarArr['pdar_relation'] = $chithaPdar->pdar_relation;
                    }

                    $chithaPdarStatus = $this->db->insert('chitha_pattadar', $chithaPattadarArr);
                    if (!$chithaPdarStatus || $this->db->affected_rows() < 1) {
                        // $this->db->trans_rollback();
                        return [
                            'status' => 'n',
                            'msg' => 'Chitha Pattadar entry Failed in chitha pattadar!'
                        ];
                        // echo json_encode([
                        //     "status" => "FAILED",
                        //     "responseType" => 1,
                        //     "msg" => "Chitha Pattadar entry Failed in chitha pattadar"
                        // ]);
                        // exit;
                    }
                }
            }
        }

        //chitha_dag_pattadar
        $chithaDagPattadars = callLandhubAPIMerge('POST', 'NicApiMerge/getChithaDagPattadars', [
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'mouza_pargona_code' => $mouza_pargona_code,
            'lot_no' => $lot_no,
            'vill_townprt_code' => $vill_townprt_code
        ]);

        if ($chithaDagPattadars->responseType != 2) {
            // $this->db->trans_rollback();
            return [
                'status' => 'n',
                'msg' => 'Could not retrieve dag pattadars from API!'
            ];
            // echo json_encode([
            //     "status" => "FAILED",
            //     "responseType" => 1,
            //     "msg" => "Could not retrieve dag pattadars from API"
            // ]);
            // exit;
        }
        // if(empty($chithaDagPattadars->data)) {
        //     $this->db->trans_rollback();
        //     echo json_encode([
        //         "status" => "FAILED",
        //         "responseType" => 1,
        //         "msg" => "Nothing to merge. No chitha dag pattadars available in this village!"
        //     ]);
        //     exit;
        // }
        if (!empty($chithaDagPattadars->data)) {
            foreach ($chithaDagPattadars->data as $dagPdar) {
                $checkDagPdar = $this->db->query("SELECT * FROM chitha_dag_pattadar WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND patta_no=? AND patta_type_code=? AND dag_no=? AND pdar_id=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dagPdar->patta_no, $dagPdar->patta_type_code, $dagPdar->dag_no, $dagPdar->pdar_id])->row();

                $checkDagInChitha = $this->db->query("SELECT * FROM chitha_basic WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND dag_no=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dagPdar->dag_no])->row();

                if (empty($checkDagPdar) && !empty($checkDagInChitha)) {
                    //insert into chitha_dag_pattadar
                    $dagPattadarArr = array(
                        'dist_code' => $dist_code,
                        'subdiv_code' => $subdiv_code,
                        'cir_code' => $cir_code,
                        'mouza_pargona_code' => $mouza_pargona_code,
                        'lot_no' => $lot_no,
                        'vill_townprt_code' => $vill_townprt_code,
                        'dag_no' => $dagPdar->dag_no,
                        'pdar_id' => $dagPdar->pdar_id,
                        'patta_no' => $dagPdar->patta_no,
                        'patta_type_code' => $dagPdar->patta_type_code,
                        'dag_por_b' => $dagPdar->dag_por_b,
                        'dag_por_k' => $dagPdar->dag_por_k,
                        'dag_por_lc' => $dagPdar->dag_por_lc,
                        'dag_por_g' => $dagPdar->dag_por_g,
                        'dag_por_kr' => (isset($dagPdar->dag_por_kr) && $dagPdar->dag_por_kr != null) ? $dagPdar->dag_por_kr : null,
                        'pdar_land_n' => (isset($dagPdar->pdar_land_n) && $dagPdar->pdar_land_n != null) ? $dagPdar->pdar_land_n : null,
                        'pdar_land_s' => (isset($dagPdar->pdar_land_s) && $dagPdar->pdar_land_s != null) ? $dagPdar->pdar_land_s : null,
                        'pdar_land_e' => (isset($dagPdar->pdar_land_e) && $dagPdar->pdar_land_e != null) ? $dagPdar->pdar_land_e : null,
                        'pdar_land_w' => (isset($dagPdar->pdar_land_w) && $dagPdar->pdar_land_w != null) ? $dagPdar->pdar_land_w : null,
                        'pdar_land_acre' => (isset($dagPdar->pdar_land_acre) && $dagPdar->pdar_land_acre != null) ? $dagPdar->pdar_land_acre : null,
                        'pdar_land_revenue' => (isset($dagPdar->pdar_land_revenue) && $dagPdar->pdar_land_revenue != null) ? $dagPdar->pdar_land_revenue : null,
                        'pdar_land_localtax' => (isset($dagPdar->pdar_land_localtax) && $dagPdar->pdar_land_localtax != null) ? $dagPdar->pdar_land_localtax : null,
                        'user_code' => $dagPdar->user_code,
                        'date_entry' => $dagPdar->date_entry,
                        'operation' => $dagPdar->operation,
                        'p_flag' => (isset($dagPdar->p_flag) && $dagPdar->p_flag != null) ? $dagPdar->p_flag : null,
                        'jama_yn' => (isset($dagPdar->jama_yn) && $dagPdar->jama_yn != null) ? $dagPdar->jama_yn : null,
                        'pdar_land_map' => (isset($dagPdar->pdar_land_map) && $dagPdar->pdar_land_map != null) ? $dagPdar->pdar_land_map : null,

                    );
                    $dagPattadarStatus = $this->db->insert('chitha_dag_pattadar', $dagPattadarArr);
                    if (!$dagPattadarStatus || $this->db->affected_rows() < 1) {
                        // $this->db->trans_rollback();
                        return [
                            'status' => 'n',
                            'msg' => 'Chitha Dag Pattadar entry Failed in chitha dag pattadar!'
                        ];
                        // echo json_encode([
                        //     "status" => "FAILED",
                        //     "responseType" => 1,
                        //     "msg" => "Chitha Dag Pattadar entry Failed in chitha dag pattadar"
                        // ]);
                        // exit;
                    }
                }
            }
        }


        //chitha_rmk_lmnote
        $chithaLmNotes = callLandhubAPIMerge('POST', 'NicApiMerge/getChithaLmNote', [
            'dist_code' => $dist_code,
            'subdiv_code' => $subdiv_code,
            'cir_code' => $cir_code,
            'mouza_pargona_code' => $mouza_pargona_code,
            'lot_no' => $lot_no,
            'vill_townprt_code' => $vill_townprt_code
        ]);

        if ($chithaLmNotes->responseType != 2) {
            // $this->db->trans_rollback();
            return [
                'status' => 'n',
                'msg' => 'Could not retrieve lm notes from API!'
            ];
            // echo json_encode([
            //     "status" => "FAILED",
            //     "responseType" => 1,
            //     "msg" => "Could not retrieve lm notes from API"
            // ]);
            // exit;
        }


        if (!empty($chithaLmNotes->data)) {
            foreach ($chithaLmNotes->data as $lmnote) {
                $checkLmNote = $this->db->query("SELECT * FROM chitha_rmk_lmnote WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND dag_no=? AND lm_note_cron_no=? AND rmk_type_hist_no=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $lmnote->dag_no, $lmnote->lm_note_cron_no, $lmnote->rmk_type_hist_no])->row();

                $checkDagInChitha = $this->db->query("SELECT * FROM chitha_basic WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND dag_no=?", [$dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $lmnote->dag_no])->row();

                if (empty($checkLmNote) && !empty($checkDagInChitha)) {
                    $lmnoteArr = [
                        'dist_code' => $dist_code,
                        'subdiv_code' => $subdiv_code,
                        'cir_code' => $cir_code,
                        'mouza_pargona_code' => $mouza_pargona_code,
                        'lot_no' => $lot_no,
                        'vill_townprt_code' => $vill_townprt_code,
                        'dag_no' => $lmnote->dag_no,
                        'lm_note_cron_no' => $lmnote->lm_note_cron_no,
                        'rmk_type_hist_no' => $lmnote->rmk_type_hist_no,
                        'lm_note_lno' => $lmnote->lm_note_lno,
                        'lm_note' => $lmnote->lm_note,
                        'lm_note_date' => (isset($lmnote->lm_note_date) && $lmnote->lm_note_date != null) ? $lmnote->lm_note_date : null,
                        'lm_code' => (isset($lmnote->lm_code) && $lmnote->lm_code != null) ? $lmnote->lm_code : null,
                        'lm_sign' => $lmnote->lm_sign,
                        'co_approval' => $lmnote->co_approval,
                        'user_code' => $lmnote->user_code,
                        'date_entry' => $lmnote->date_entry,
                        'operation' => $lmnote->operation
                    ];
                    $lmnoteStatus = $this->db->insert('chitha_rmk_lmnote', $lmnoteArr);
                    if (!$lmnoteStatus || $this->db->affected_rows() < 1) {
                        // $this->db->trans_rollback();
                        return [
                            'status' => 'n',
                            'msg' => 'Chitha LM Note entry Failed in lmnote table!'
                        ];
                        // echo json_encode([
                        //     "status" => "FAILED",
                        //     "responseType" => 1,
                        //     "msg" => "Chitha LM Note entry Failed in lmnote table"
                        // ]);
                        // exit;
                    }
                }
            }
        }

        // if(!$this->db->trans_status()) {
        //     $this->db->trans_rollback();
        //     return [
        //         'status' => 'n',
        //         'msg' => 'DB Transaction Failed!' 
        //     ];
        // }

        // $this->db->trans_commit();

        return [
            'status' => 'y',
            'msg' => 'Successfully merged all dharitree data to chitha!'
        ];

        // echo json_encode([
        //     "status" => "SUCCESS",
        //     "responseType" => 2,
        //     "msg" => "Successfully merged all dharitree data to chitha",
        // ]);
        // exit;
    }
}

?>