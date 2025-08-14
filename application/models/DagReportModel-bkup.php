<?php
class DagReportModel extends CI_Model
{
    public function families($dist_code,$subdiv_code,$cir_code,$mouza_pargona_code,$lot_no,$vill_townprt_code,$dag_no,$encro_id)
    {
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
   

    public function occupiers($dist_code,$subdiv_code,$cir_code,$mouza_pargona_code,$lot_no,$vill_townprt_code,$dag_no)
    {
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
   
    public function getGovtDags($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code)
    {
        $govt_patta_codes = GovtPattaCode;
        $codes = [];
        foreach($govt_patta_codes as $code){
            $codes[] = "'".$code."'";
        }
        $govt_patta_codes = implode(',',$codes);
        $q = "SELECT landclass_code.land_type as full_land_type_name,chitha_basic.* FROM chitha_basic 
        join landclass_code on landclass_code.class_code = chitha_basic.land_class_code
        WHERE chitha_basic.dist_code='$dist_code' AND chitha_basic.subdiv_code='$subdiv_code' AND chitha_basic.cir_code='$cir_code' 
        AND chitha_basic.mouza_Pargona_code='$mouza_pargona_code' AND chitha_basic.lot_No='$lot_no' AND chitha_basic.vill_townprt_code='$vill_townprt_code' 
        AND chitha_basic.patta_type_code in ($govt_patta_codes)
        order by CAST(coalesce(dag_no_int, '0') AS numeric)";

        $query = $this->db->query($q);

        return $query->result();
    }
    public function getGovtDag($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag_no)
    {
        $govt_patta_codes = GovtPattaCode;
        $codes = [];
        foreach($govt_patta_codes as $code){
            $codes[] = "'".$code."'";
        }
        $govt_patta_codes = implode(',',$codes);
        $q = "SELECT dag_no, dag_no_int, dag_area_b, dag_area_k, dag_area_lc, dag_area_g, dag_area_kr, patta_no, patta_type_code,land_class_code FROM chitha_Basic WHERE dist_code='$dist_code' AND subdiv_code='$subdiv_code' AND cir_code='$cir_code' 
        AND mouza_Pargona_code='$mouza_pargona_code' AND lot_No='$lot_no' AND vill_townprt_code='$vill_townprt_code' 
        AND patta_type_code in ($govt_patta_codes) and dag_no = '$dag_no'
        order by CAST(coalesce(dag_no_int, '0') AS numeric)";

        $query = $this->db->query($q);

        return $query->row();
    }
    public function occupier($dist_code,$subdiv_code,$cir_code,$mouza_pargona_code,$lot_no,$vill_townprt_code,$dag_no,$enc_id)
    {
        $q = $this->db->select('encro_id,encro_name,encro_guardian,encro_guar_relation,encro_class_code,encro_land_b,encro_land_k,encro_land_lc,encro_land_g,encro_land_kr,encro_since')
            ->where('dist_code', $dist_code)
            ->where('subdiv_code', $subdiv_code)
            ->where('cir_code', $cir_code)
            ->where('mouza_pargona_code', $mouza_pargona_code)
            ->where('lot_no', $lot_no)
            ->where('vill_townprt_code', $vill_townprt_code)
            ->where('dag_no', $dag_no)
            ->where('encro_id', $enc_id)
            ->order_by('encro_name', 'ASC')
            ->get('chitha_rmk_encro');

        return $q->row();
    }
    public function occupierNames($dist_code,$subdiv_code,$cir_code,$mouza_pargona_code,$lot_no,$vill_townprt_code,$dag_no)
    {
        $q = $this->db->select('encro_name')
            ->where('dist_code', $dist_code)
            ->where('subdiv_code', $subdiv_code)
            ->where('cir_code', $cir_code)
            ->where('mouza_pargona_code', $mouza_pargona_code)
            ->where('lot_no', $lot_no)
            ->where('vill_townprt_code', $vill_townprt_code)
            ->where('dag_no', $dag_no)
            ->order_by('encro_name', 'ASC')
            ->get('chitha_rmk_encro');

        return $q->result();
    }

	public function insertBhunaksadagsIfCountNotMatchedWithChitha($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $application_no = NULL){
		$url = LANDHUB_BASE_URL_NEW."BhunakshaApiController/getVillageDagDetails";
		$method = 'POST';
		$data['location'] = $dist_code.'_'.$subdiv_code.'_'.$cir_code.'_'.$mouza_pargona_code.'_'.$lot_no.'_'.$vill_townprt_code;

		$data = json_decode($this->NcVillageModel->callApiV2($url, $method, $data));
		$bhunaksha_total_dags = $data->totalPlots;

		$loc_conditions = [
							'dist_code' => $dist_code,
							'subdiv_code' => $subdiv_code,
							'cir_code' => $cir_code,
							'mouza_pargona_code' => $mouza_pargona_code,
							'lot_no' => $lot_no,
							'vill_townprt_code' => $vill_townprt_code,
						];

		$chitha_basic_nc_query = $this->db->where($loc_conditions)->get('chitha_basic_nc');
		$chitha_basic_nc_count = $chitha_basic_nc_query->num_rows();

		if($bhunaksha_total_dags != $chitha_basic_nc_count){
			$before_sync_dags_with_bhunaksha_data = $loc_conditions;
			$before_sync_dags_with_bhunaksha_data['created_by'] = $this->session->userdata('user_code');
			$before_sync_dags_with_bhunaksha_data['created_at'] = date('Y-m-d H:i:s');

			if($chitha_basic_nc_count > 0){
				$chitha_basic_nc = $chitha_basic_nc_query->result_array();
				$before_sync_dags_with_bhunaksha_data['chitha_basic_nc_data'] = json_encode($chitha_basic_nc);
			}
			if($application_no){
				$before_sync_dags_with_bhunaksha_data['application_no'] = $application_no;
				$nc_village_dags = $this->db->where($loc_conditions)->where('application_no', $application_no)->get('nc_village_dags')->result_array();
				$nc_village = $this->db->where($loc_conditions)->where('application_no', $application_no)->get('nc_villages')->result_array();
				$before_sync_dags_with_bhunaksha_data['nc_village_dags_data'] = json_encode($nc_village_dags);
				$before_sync_dags_with_bhunaksha_data['nc_villages_data'] = json_encode($nc_village);

				$this->db->where($loc_conditions)->delete('nc_village_dags');
				// $this->db->where($loc_conditions)->delete('nc_villages');
			}

			$this->db->insert('before_sync_dags_with_bhunaksha_logs', $before_sync_dags_with_bhunaksha_data);
			$this->db->where($loc_conditions)->delete('chitha_basic_nc');

			$insert_bhunaksha_dags = $this->insertBhunaksadags($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code);

			if($application_no){
				$govtdags = $this->getGovtDagsFromChithaBasicNc($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code);
				foreach($govtdags as $dag){
					$nc_village_dags = array(
						'dist_code' => $dist_code,
						'subdiv_code' => $subdiv_code,
						'cir_code' => $cir_code,
						'mouza_pargona_code' => $mouza_pargona_code,
						'lot_no' => $lot_no,
						'vill_townprt_code' => $vill_townprt_code,
						'application_no' => $application_no,
						'dag_no' => $dag->dag_no,
						'dag_no_int' => $dag->dag_no_int,
						'dag_area_b' => $dag->dag_area_b,
						'dag_area_g' => $dag->dag_area_g,
						'dag_area_k' => $dag->dag_area_k,
						'dag_area_kr' => $dag->dag_area_kr,
						'dag_area_lc' => $dag->dag_area_lc,
						'patta_type_code' => $dag->patta_type_code,
						'patta_no' => $dag->patta_no,
						// 'lm_verified' => 'Y',
						'lm_verified' => null,
						'co_verified' => null,
						'dc_verified' => null,
						'created_at' => date('Y-m-d H:i:s'),
						'updated_at' => date('Y-m-d H:i:s'),
						// 'lm_verified_at' => date('Y-m-d H:i:s'),
						'lm_verified_at' => null,

					);

					$insert_nc_village_dags = $this->db->insert('nc_village_dags', $nc_village_dags);
				}
			}

		}

		return true;
	}

	public function insertBhunaksadags($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code,$case_type = null, $merge_with_c_village = null, $min_dag_no = null, $max_dag_no = null)

	{
		try {
			$url = LANDHUB_BASE_URL_NEW."BhunakshaApiController/getVillageDagDetails";
			$method = 'POST';
			if($case_type == 'NC_TO_C'){
				if(!$merge_with_c_village){
					throw new Exception("merge_with_c_village can not be null", 409);
				}
				$data['location'] = $merge_with_c_village['dist_code'].'_'.$merge_with_c_village['subdiv_code'].'_'.$merge_with_c_village['cir_code'].'_'.$merge_with_c_village['mouza_pargona_code'].'_'.$merge_with_c_village['lot_no'].'_'.$merge_with_c_village['vill_townprt_code'];
			}else{
				$data['location'] = $dist_code.'_'.$subdiv_code.'_'.$cir_code.'_'.$mouza_pargona_code.'_'.$lot_no.'_'.$vill_townprt_code;
			}
			$data = json_decode($this->NcVillageModel->callApiV2($url, $method, $data));
			// echo json_encode($data);die;

			if(!empty($data->plotInfo)) {
				$bhunksha_dags = [];
				foreach ($data->plotInfo as $key=>$bhunaksha_dag_raw)
				{
					$bhunaksha_dag_no= (int)$bhunaksha_dag_raw->plotNo;
					$bhunksha_dag = [];
					$bhunksha_dag['dag_no'] = $bhunaksha_dag_no;
					$bhunksha_dag['dag_area_b'] = $this->areaSqMetertoBKL($bhunaksha_dag_raw->plotArea)[0];
					$bhunksha_dag['dag_area_k'] = $this->areaSqMetertoBKL($bhunaksha_dag_raw->plotArea)[1];
					$bhunksha_dag['dag_area_lc'] = $this->areaSqMetertoBKL($bhunaksha_dag_raw->plotArea)[2];
					$bhunksha_dag['dag_area_g'] = 0;
					$bhunksha_dag['dag_area_kr'] = 0;
					$bhunksha_dags[] = $bhunksha_dag;
				}
				$chitha_basic_dags = $this->DagReportModel->getVillageChithaDags($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code);
				
				$is_dag_not_found = false;
				$is_dag_no_invalid = false;

				foreach($chitha_basic_dags as $chitha_basic_dag){
					$found = false;
					// Update area details of $chitha_basic_dag from $bhunksha_dags using dag_no
					foreach ($bhunksha_dags as $bhunksha_dag) {
						if ($bhunksha_dag['dag_no'] == $chitha_basic_dag->dag_no) {
							$chitha_basic_dag->dag_area_b = $bhunksha_dag['dag_area_b'];
							$chitha_basic_dag->dag_area_k = $bhunksha_dag['dag_area_k'];
							$chitha_basic_dag->dag_area_lc = $bhunksha_dag['dag_area_lc'];
							$chitha_basic_dag->dag_area_g = $bhunksha_dag['dag_area_g'];
							$chitha_basic_dag->dag_area_kr = $bhunksha_dag['dag_area_kr'];
							$found = true;
							break;
						}
					}
					if (!$found) {
						$is_dag_not_found = true;
					}
					if($case_type == 'NC_TO_C' && $min_dag_no && $max_dag_no){
						if ($chitha_basic_dag->dag_no < $min_dag_no || $chitha_basic_dag->dag_no > $max_dag_no) {
							$is_dag_no_invalid = true;
						}
					}
				}
				if($is_dag_not_found){
					throw new Exception("Some Dags not found in bhunaksa but found in chithaentry for the given location", 409);
				}
				if($is_dag_no_invalid){
					throw new Exception("Some dag numbers are invalid for the given range ($min_dag_no - $max_dag_no) of the village", 409);
				}
				$final_dags = [];
				foreach($chitha_basic_dags as $chitha_basic_dag){
					$final_dag = [];
					$final_dag['dist_code'] = $chitha_basic_dag->dist_code;
					$final_dag['subdiv_code'] = $chitha_basic_dag->subdiv_code;
					$final_dag['cir_code'] = $chitha_basic_dag->cir_code;
					$final_dag['mouza_pargona_code'] = $chitha_basic_dag->mouza_pargona_code;
					$final_dag['lot_no'] = $chitha_basic_dag->lot_no;
					$final_dag['vill_townprt_code'] = $chitha_basic_dag->vill_townprt_code;
					$final_dag['old_dag_no'] = isset($chitha_basic_dag->old_dag_no) ? $chitha_basic_dag->old_dag_no : null;
					$final_dag['dag_no'] = $chitha_basic_dag->dag_no;
					$final_dag['dag_no_int'] = isset($chitha_basic_dag->dag_no_int) ? $chitha_basic_dag->dag_no_int : null;
					$final_dag['patta_type_code'] = isset($chitha_basic_dag->patta_type_code) ? $chitha_basic_dag->patta_type_code : '';
					$final_dag['patta_no'] = isset($chitha_basic_dag->patta_no) ? $chitha_basic_dag->patta_no : '';
					$final_dag['land_class_code'] = isset($chitha_basic_dag->land_class_code) ? $chitha_basic_dag->land_class_code : '';
					$final_dag['dag_area_b'] = $chitha_basic_dag->dag_area_b;
					$final_dag['dag_area_k'] = $chitha_basic_dag->dag_area_k;
					$final_dag['dag_area_lc'] = $chitha_basic_dag->dag_area_lc;
					$final_dag['dag_area_g'] = $chitha_basic_dag->dag_area_g;
					$final_dag['dag_area_kr'] = $chitha_basic_dag->dag_area_kr;
					$final_dag['dag_area_are'] = isset($chitha_basic_dag->dag_area_are) ? $chitha_basic_dag->dag_area_are : null;
					$final_dag['dag_revenue'] = isset($chitha_basic_dag->dag_revenue) ? $chitha_basic_dag->dag_revenue : null;
					$final_dag['dag_local_tax'] = isset($chitha_basic_dag->dag_local_tax) ? $chitha_basic_dag->dag_local_tax : null;
					$final_dag['dag_no_map'] = isset($chitha_basic_dag->dag_no_map) ? $chitha_basic_dag->dag_no_map : null;
					$final_dag['dag_n_desc'] = isset($chitha_basic_dag->dag_n_desc) ? $chitha_basic_dag->dag_n_desc : null;
					$final_dag['dag_s_desc'] = isset($chitha_basic_dag->dag_s_desc) ? $chitha_basic_dag->dag_s_desc : null;
					$final_dag['dag_e_desc'] = isset($chitha_basic_dag->dag_e_desc) ? $chitha_basic_dag->dag_e_desc : null;
					$final_dag['dag_w_desc'] = isset($chitha_basic_dag->dag_w_desc) ? $chitha_basic_dag->dag_w_desc : null;
					$final_dag['dag_n_dag_no'] = isset($chitha_basic_dag->dag_n_dag_no) ? $chitha_basic_dag->dag_n_dag_no : null;
					$final_dag['dag_s_dag_no'] = isset($chitha_basic_dag->dag_s_dag_no) ? $chitha_basic_dag->dag_s_dag_no : null;
					$final_dag['dag_e_dag_no'] = isset($chitha_basic_dag->dag_e_dag_no) ? $chitha_basic_dag->dag_e_dag_no : null;
					$final_dag['dag_w_dag_no'] = isset($chitha_basic_dag->dag_w_dag_no) ? $chitha_basic_dag->dag_w_dag_no : null;
					$final_dag['dag_nlrg_no'] = isset($chitha_basic_dag->dag_nlrg_no) ? $chitha_basic_dag->dag_nlrg_no : null;
					$final_dag['dp_flag_yn'] = isset($chitha_basic_dag->dp_flag_yn) ? $chitha_basic_dag->dp_flag_yn : null;
					$final_dag['user_code'] = $this->session->userdata('user_code');
					$final_dag['date_entry'] = date('Y-m-d H:i:s');
					$final_dag['operation'] = isset($chitha_basic_dag->operation) ? $chitha_basic_dag->operation : 'E';
					$final_dag['jama_yn'] = isset($chitha_basic_dag->jama_yn) ? $chitha_basic_dag->jama_yn : null;
					$final_dag['status'] = isset($chitha_basic_dag->status) ? $chitha_basic_dag->status : null;
					$final_dag['old_patta_no'] = isset($chitha_basic_dag->old_patta_no) ? $chitha_basic_dag->old_patta_no : null;
					$final_dag['dag_name'] = isset($chitha_basic_dag->dag_name) ? $chitha_basic_dag->dag_name : null;
					$final_dag['dag_dept_name'] = isset($chitha_basic_dag->dag_dept_name) ? $chitha_basic_dag->dag_dept_name : null;
					$final_dag['dag_status'] = isset($chitha_basic_dag->dag_status) ? $chitha_basic_dag->dag_status : null;
					$final_dag['uuid'] = isset($chitha_basic_dag->uuid) ? $chitha_basic_dag->uuid : null;
					$final_dag['category_id'] = isset($chitha_basic_dag->category_id) ? $chitha_basic_dag->category_id : null;
					$final_dags[] = $final_dag;
				}
				// Insert updated $final_dags into chitha_basic_nc
				$this->db->insert_batch('chitha_basic_nc', $final_dags);
				return array(
					'code' => 200,
					'message' => 'Dags inserted successfully'
				);
			}else{
				throw new Exception("Bhunaksa data not found", 409);
			}
		} catch (Exception $e) {
			log_message('error', 'Error in insertBhunaksadags: ' . $e->getMessage());
			return array(
				'code' => 500,
				'message' => 'An error occurred: ' . $e->getMessage()
			);
		}
	}
	public function insertBhunaksadagsOld($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code)
	
	{
		$url = LANDHUB_BASE_URL_NEW."BhunakshaApiController/getVillageDagDetails";
		$method = 'POST';
		$data['location'] = $dist_code.'_'.$subdiv_code.'_'.$cir_code.'_'.$mouza_pargona_code.'_'.$lot_no.'_'.$vill_townprt_code;

		$data = json_decode($this->NcVillageModel->callApiV2($url, $method, $data));

		$dags = array();
		$chitha = array();

		if(!empty($data->plotInfo)) {
			foreach ($data->plotInfo as $key=>$dag)
			{
				$dag_no= (int)$dag->plotNo;
				$area = $this->areaSqMetertoBKL($dag->plotArea);
				$chitha_data = $this->DagReportModel->getChithaDags($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag_no);

				$chitha[$key]['dist_code'] = $dist_code;
				$chitha[$key]['subdiv_code'] = $subdiv_code;
				$chitha[$key]['cir_code'] = $cir_code;
				$chitha[$key]['mouza_pargona_code'] = $mouza_pargona_code;
				$chitha[$key]['lot_no'] = $lot_no;
				$chitha[$key]['vill_townprt_code'] = $vill_townprt_code;

				$chitha[$key]['dag_no'] = "$dag_no";
				$chitha[$key]['dag_area_b'] = $area[0];
				$chitha[$key]['dag_area_k'] = $area[1];
				$chitha[$key]['dag_area_lc'] = $area[2];
				$chitha[$key]['dag_area_g'] = 0;
				$chitha[$key]['dag_area_kr'] = 0;
				$chitha[$key]['dag_no_int'] = $dag_no.'00';

				if($chitha_data)
				{
					$chitha[$key]['patta_no'] = $chitha_data->patta_no;
					$chitha[$key]['patta_type_code'] = $chitha_data->patta_type_code;
					$chitha[$key]['land_class_code'] = $chitha_data->land_class_code;
					$chitha[$key]['operation'] = $chitha_data->operation;
					$chitha[$key]['block_code'] = $chitha_data->block_code;
					$chitha[$key]['gp_code'] = $chitha_data->gp_code;
					$chitha[$key]['zonal_value'] = $chitha_data->zonal_value;
					$chitha[$key]['category_id'] = $chitha_data->category_id;
					$chitha[$key]['uuid'] = $chitha_data->uuid;
					$chitha[$key]['user_code'] = $chitha_data->user_code;
					$chitha[$key]['date_entry'] = $chitha_data->date_entry;
				}else{
					$chitha[$key]['patta_no'] = '0';
					$chitha[$key]['patta_type_code'] = '0209';
					$chitha[$key]['land_class_code'] = '0134';
					$chitha[$key]['operation'] = 'E';
					$chitha[$key]['block_code'] = null;
					$chitha[$key]['gp_code'] = null;
					$chitha[$key]['zonal_value'] = null;
					$chitha[$key]['category_id'] = null;
					$chitha[$key]['uuid'] = null;
					$chitha[$key]['user_code'] = $this->session->userdata('user_code');
					$chitha[$key]['date_entry'] = date('Y/m/d');
				}
				$status = $this->db->insert('chitha_basic_nc', $chitha[$key]);
			}
		}
		return $status;
		// if(!$chitha)
		// {
		// 	return $chitha;
		// }
		// return $this->db->insert_batch('chitha_basic_nc', $chitha);
	}

	public function areaSqMetertoBKL($area)
	{
		$total_lessa = round($area/13.37803776);
		$mm = 0;
		if($total_lessa < 0)
		{
			$mm = 1;
			$total_lessa = abs($total_lessa);
		}
		$bigha = $total_lessa / 100;
		$rem_lessa = fmod($total_lessa, 100);
		$katha = $rem_lessa / 20;
		$r_lessa = fmod($rem_lessa, 20);
		$mesaure = array();
		$mesaure[].=($mm==1) ? -floor($bigha): floor($bigha);
		$mesaure[].=($mm==1) ? -floor($katha): floor($katha);
		$mesaure[].=($mm==1) ? -($r_lessa) : $r_lessa;
		$mesaure[].=0;
		return $mesaure;
	}

	public function getChithaDags($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code,$dag_no)
	{
		$govt_patta_codes = GovtPattaCode;
		$codes = [];
		foreach($govt_patta_codes as $code){
			$codes[] = "'".$code."'";
		}
		$govt_patta_codes = implode(',',$codes);
		$q = "SELECT landclass_code.land_type as full_land_type_name,chitha_basic.* FROM chitha_basic
        join landclass_code on landclass_code.class_code = chitha_basic.land_class_code
        WHERE chitha_basic.dist_code='$dist_code' AND chitha_basic.subdiv_code='$subdiv_code' AND chitha_basic.cir_code='$cir_code'
        AND chitha_basic.mouza_Pargona_code='$mouza_pargona_code' AND chitha_basic.lot_No='$lot_no' AND chitha_basic.vill_townprt_code='$vill_townprt_code'
        AND chitha_basic.patta_type_code in ($govt_patta_codes) and chitha_basic.dag_no = '$dag_no'
        order by CAST(coalesce(dag_no_int, '0') AS numeric)";

		$query = $this->db->query($q);

		return $query->row();
	}
	public function getVillageChithaDags($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code)
	{
		$govt_patta_codes = GovtPattaCode;
		$codes = [];
		foreach($govt_patta_codes as $code){
			$codes[] = "'".$code."'";
		}
		$govt_patta_codes = implode(',',$codes);
		$q = "SELECT landclass_code.land_type as full_land_type_name,chitha_basic.* FROM chitha_basic
        join landclass_code on landclass_code.class_code = chitha_basic.land_class_code
        WHERE chitha_basic.dist_code='$dist_code' AND chitha_basic.subdiv_code='$subdiv_code' AND chitha_basic.cir_code='$cir_code'
        AND chitha_basic.mouza_Pargona_code='$mouza_pargona_code' AND chitha_basic.lot_No='$lot_no' AND chitha_basic.vill_townprt_code='$vill_townprt_code'
        AND chitha_basic.patta_type_code in ($govt_patta_codes) order by CAST(coalesce(dag_no_int, '0') AS numeric)";

		$query = $this->db->query($q);

		return $query->result();
	}

	public function getGovtDagsFromChithaBasicNc($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code)
	{
		$govt_patta_codes = GovtPattaCode;
		$codes = [];
		foreach($govt_patta_codes as $code){
			$codes[] = "'".$code."'";
		}
		$govt_patta_codes = implode(',',$codes);
		$q = "SELECT landclass_code.land_type as full_land_type_name,chitha_basic_nc.* FROM chitha_basic_nc 
        join landclass_code on landclass_code.class_code = chitha_basic_nc.land_class_code
        WHERE chitha_basic_nc.dist_code='$dist_code' AND chitha_basic_nc.subdiv_code='$subdiv_code' AND chitha_basic_nc.cir_code='$cir_code' 
        AND chitha_basic_nc.mouza_Pargona_code='$mouza_pargona_code' AND chitha_basic_nc.lot_No='$lot_no' AND chitha_basic_nc.vill_townprt_code='$vill_townprt_code' 
        AND chitha_basic_nc.patta_type_code in ($govt_patta_codes)
        order by CAST(coalesce(dag_no_int, '0') AS numeric)";

		$query = $this->db->query($q);

		return $query->result();
	}

	public function checkChithaBasicNc($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code)
	{
		$q = $this->db->select('vill_townprt_code')
			->where('dist_code', $dist_code)
			->where('subdiv_code', $subdiv_code)
			->where('cir_code', $cir_code)
			->where('mouza_pargona_code', $mouza_pargona_code)
			->where('lot_no', $lot_no)
			->where('vill_townprt_code', $vill_townprt_code)
			->get('chitha_basic_nc');

		return $q->row();
	}

	public function getAllDags($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code)
	{
		$q = "SELECT landclass_code.land_type as full_land_type_name,chitha_basic.* FROM chitha_basic 
        join landclass_code on landclass_code.class_code = chitha_basic.land_class_code
        WHERE chitha_basic.dist_code='$dist_code' AND chitha_basic.subdiv_code='$subdiv_code' AND chitha_basic.cir_code='$cir_code' 
        AND chitha_basic.mouza_Pargona_code='$mouza_pargona_code' AND chitha_basic.lot_No='$lot_no' AND chitha_basic.vill_townprt_code='$vill_townprt_code'
        order by CAST(coalesce(dag_no_int, '0') AS numeric)";

		$query = $this->db->query($q);

		return $query->result();
	}

	public function updateChithaBasicNc($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code)
	{
		$q = "SELECT * FROM chitha_basic WHERE dist_code='$dist_code' AND subdiv_code='$subdiv_code' AND cir_code='$cir_code' 
        AND mouza_pargona_code='$mouza_pargona_code' AND lot_no='$lot_no' AND vill_townprt_code='$vill_townprt_code'";

		$query = $this->db->query($q);

		$chitha_dags =  $query->result();

		foreach ($chitha_dags as $dag)
		{
			$this->db->where('dist_code', $dist_code)
				->where('subdiv_code', $subdiv_code)
				->where('cir_code', $cir_code)
				->where('mouza_pargona_code', $mouza_pargona_code)
				->where('lot_no', $lot_no)
				->where('vill_townprt_code', $vill_townprt_code)
				->where('dag_no', $dag->dag_no)
				->update('chitha_basic_nc',
					array(
						'patta_no' => $dag->patta_no,
						'patta_type_code' => $dag->patta_type_code,
						'land_class_code' => $dag->land_class_code,
						'operation' => $dag->operation,
						'block_code' => $dag->block_code,
						'gp_code' => $dag->gp_code,
						'zonal_value' => $dag->zonal_value,
						'category_id' => $dag->category_id,
					));
		}
	}
}

