<?php
class PortingModel extends CI_Model
{
    public static $ENUM_SUCCESS = 'success';
    public static $ENUM_FAILED = 'failed';
    public static $PORT_TYPE_CHITHA = 'chitha_data';
    public static $PORT_TYPE_CHITHA_FROM_DHAR = 'dhar_chitha_data';
    public static $PORT_TYPE_VLB = 'vlb_data';
    public static $PORT_TYPE_VLB_FROM_DHAR = 'dhar_vlb_data';


    public function allowed_porting_tables()
    {
        return [
            [
                'table' => 'chitha_dag_pattadar',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'pdar_id', 'patta_no', 'patta_type_code']
            ],
            [
                'table' => 'chitha_pattadar',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'pdar_id', 'patta_no', 'patta_type_code']
            ],
            [
                'table' => 'chitha_rmk_convorder',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'rmk_type_hist_no', 'ord_cron_no', 'ord_onbehalf_id']
            ],
            [
                'table' => 't_chitha_rmk_convorder',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'ord_no', 'ord_date', 'ord_onbehalf_id']
            ],
            [
                'table' => 'chitha_rmk_ordbasic',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'rmk_type_hist_no', 'ord_no', 'ord_date', 'ord_type_code', 'ord_cron_no']
            ],
            [
                'table' => 't_chitha_rmk_ordbasic',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'year_no', 'petition_no', 'ord_no', 'ord_date', 'ord_type_code']
            ],
            [
                'table' => 'petition_dag_details', ##NEED RECHECK
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'year_no', 'petition_no', 'dag_no']
            ],
            [
                'table' => 'petition_lm_note', ##NEED RECHECK
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'year_no', 'petition_no', 'dag_no', 'note_no']
            ],
            [
                'table' => 'petitioner_part', ##NEED RECHECK
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'year_no', 'petition_no', 'dag_no', 'patta_no', 'patta_type_code', 'pdar_id']
            ],
            [
                'table' => 'petition_bo_note', ##NEED RECHECK
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'year_no', 'petition_no', 'dag_no', 'case_no', 'note_no']
            ],
            [
                'table' => 'petition_byayprak',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'year_no', 'petition_no']
            ],
            [
                'table' => 'petition_notified',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'year_no', 'petition_no', 'notified_id']
            ],
            [
                'table' => 'petition_pattadar',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'year_no', 'petition_no', 'dag_no', 'patta_no', 'patta_type_code', 'pdar_id']
            ],
            [
                'table' => 'chitha_col8_inplace',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'col8order_cron_no', 'inplace_of_id']
            ],
            [
                'table' => 't_chitha_col8_inplace',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'year_no', 'petition_no', 'inplace_of_id']
            ],
            [
                'table' => 'chitha_col8_occup',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'col8order_cron_no', 'occupant_id']
            ],
            [
                'table' => 't_chitha_col8_occup',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'year_no', 'petition_no', 'occupant_id']
            ],
            [
                'table' => 'chitha_col8_order',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'col8order_cron_no']
            ],
            [
                'table' => 't_chitha_col8_order',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'year_no', 'petition_no']
            ],
            [
                'table' => 'field_mut_dag_details',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'year_no', 'petition_no']
            ],
            [
                'table' => 'field_mut_pattadar',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'year_no', 'petition_no', 'pdar_id']
            ],
            [
                'table' => 'chitha_rmk_allottee',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'rmk_type_hist_no', 'ord_no', 'ord_date', 'allottee_id']
            ],
            [
                'table' => 'chitha_rmk_alongwith',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'rmk_type_hist_no', 'ord_no', 'ord_date', 'alongwith_id']
            ],
            [
                'table' => 't_chitha_rmk_alongwith',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'ord_no', 'ord_date', 'alongwith_id']
            ],
            [
                'table' => 'chitha_rmk_convorder',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'rmk_type_hist_no', 'ord_cron_no', 'ord_onbehalf_id']
            ],
            [
                'table' => 'chitha_rmk_encro',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'rmk_type_hist_no', 'encro_id']
            ],
            [
                'table' => 'chitha_rmk_gen',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'rmk_type_hist_no', 'rmk_type_code']
            ],
            [
                'table' => 'chitha_rmk_infavor_of',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'rmk_type_hist_no', 'ord_no', 'ord_date', 'infavor_of_id']
            ],
            [
                'table' => 't_chitha_rmk_infavor_of',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'ord_no', 'ord_date', 'infavor_of_id']
            ],
            [
                'table' => 'chitha_rmk_inplace_of',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'rmk_type_hist_no', 'ord_no', 'ord_date', 'inplace_of_id']
            ],
            [
                'table' => 't_chitha_rmk_inplace_of',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'ord_no', 'ord_date', 'inplace_of_id']
            ],
            [
                'table' => 'chitha_rmk_lmnote',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'rmk_type_hist_no', 'lm_note_cron_no']
            ],
            [
                'table' => 'chitha_rmk_onbehalf',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'rmk_type_hist_no', 'ord_no', 'ord_date', 'onbehalf_id']
            ],
            [
                'table' => 'chitha_rmk_other_opp_party',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'rmk_type_hist_no', 'ord_no', 'ord_date', 'name_for_id']
            ],
            [
                'table' => 't_chitha_rmk_other_opp_party',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'ord_no', 'ord_date', 'name_for_id']
            ],
            [
                'table' => 'chitha_rmk_reclassification',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'proposal_no']
            ],
            [
                'table' => 't_reclassification',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'proposal_no']
            ],
            [
                'table' => 'chitha_mcrop',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'yearno', 'crop_code']
            ],
            [
                'table' => 'chitha_noncrop',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'noncrop_use_id']
            ],
            [
                'table' => 'chitha_fruit',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'fruit_plant_id']
            ],
            [
                'table' => 'chitha_rmk_sknote',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'sk_note_cron_no', 'rmk_type_hist_no']
            ],
            [
                'table' => 'apcancel_dag_details',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'year_no', 'petition_no']
            ],
            [
                'table' => 'apcancel_petition_pattadar',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'year_no', 'petition_no', 'pdar_cron_no']
            ],
            [
                'table' => 'apt_chitha_rmk_ordbasic',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'year_no', 'petition_no', 'ord_no', 'ord_date', 'ord_type_code']
            ],
            [
                'table' => 'apt_chitha_rmk_other',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'ord_no', 'ord_date', 'name_for_id']
            ],
            [
                'table' => 'chitha_subtenant',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'tenant_id', 'subtenant_id']
            ],
            [
                'table' => 'chitha_tenant',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'tenant_id']
            ],
            [
                'table' => 'chitha_basic',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no']
            ],
            [
                'table' => 'chitha_basic_entry',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no']
            ],
            [
                'table' => 'chitha_basic_issue', ##NEED TO RECHECK
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'dag_no', 'patta_no', 'patta_type_code']
            ],
            [
                'table' => 'patta_basic', ##NEED TO RECHECK
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'case_no', 'petition_no', 'patta_type', 'patta_type_code', 'patta_no']
            ],
            [
                'table' => 'patta_basic_dag',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'case_no', 'petition_no', 'patta_type', 'patta_type_code', 'patta_no', 'dag_no']
            ],
            [
                'table' => 'lm_code',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'lm_code']
            ],
            [
                'table' => 'jama_dag',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'patta_type_code', 'patta_no', 'dag_no']
            ],
            [
                'table' => 'jama_patta',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'patta_type_code', 'patta_no']
            ],
            [
                'table' => 'jama_pattadar',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'patta_type_code', 'patta_no', 'pdar_id']
            ],
            [
                'table' => 'jama_remark',
                'primary_cols' => ['dist_code', 'subdiv_code', 'cir_code', 'mouza_pargona_code', 'lot_no', 'vill_townprt_code', 'patta_type_code', 'patta_no', 'rmk_line_no']
            ],
        ];
    }
    public function tablesUptoVillage($db = null)
    {
        try {
            $db = $db ? $db : $this->db;
            $ts = $db->list_tables();
            sort($ts);
            $tables = [];
            foreach ($ts as $table) {
                if (($db->field_exists('vill_townprt_code', $table) || $db->field_exists('vill_code', $table) || $db->field_exists('vt_code', $table) || $db->field_exists('vill_town_code', $table)) && !in_array($table, $this->transfer_skip_tables())) {
                    $t['is_cir_code'] = $db->field_exists('cir_code', $table) ? true : false;
                    $t['is_circle_code'] = $db->field_exists('circle_code', $table) ? true : false;
                    $t['is_vill_townprt_code'] = $db->field_exists('vill_townprt_code', $table) ? true : false;
                    $t['is_vill_code'] = $db->field_exists('vill_code', $table) ? true : false;
                    $t['is_vt_code'] = $db->field_exists('vt_code', $table) ? true : false;
                    $t['is_vill_town_code'] = $db->field_exists('vill_town_code', $table) ? true : false;
                    $t['is_mp_code'] = $db->field_exists('mp_code', $table) ? true : false;
                    $t['is_mouza_pargona_code'] = $db->field_exists('mouza_pargona_code', $table) ? true : false;
                    $t['is_mouza_code'] = $db->field_exists('mouza_code', $table) ? true : false;
                    $t['table'] = $table;
                    $tables[] = $t;
                }
            }
            return $tables;
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    public function transfer_skip_tables()
    {
        return array(
            'location',
            'location_old',
            'arrrev',
            'dainikamdani',
            // 'land_bank_details',
            't_land_bank_details',
            // 'c_land_bank_details',
            // 'land_share_details'
        );
    }

	public function tablesUptoVillageForVillMerge($db = null)
	{
		try {
			$db = $db ? $db : $this->db;
			$ts = $db->list_tables();
			sort($ts);
			$tables = [];
			foreach ($ts as $table) {
				if (($db->field_exists('vill_townprt_code', $table) || $db->field_exists('vill_code', $table) || $db->field_exists('vt_code', $table) || $db->field_exists('vill_town_code', $table)) && !in_array($table, $this->transfer_skip_tables_for_vill_merge())) {
					$t['is_cir_code'] = $db->field_exists('cir_code', $table) ? true : false;
					$t['is_circle_code'] = $db->field_exists('circle_code', $table) ? true : false;
					$t['is_vill_townprt_code'] = $db->field_exists('vill_townprt_code', $table) ? true : false;
					$t['is_vill_code'] = $db->field_exists('vill_code', $table) ? true : false;
					$t['is_vt_code'] = $db->field_exists('vt_code', $table) ? true : false;
					$t['is_vill_town_code'] = $db->field_exists('vill_town_code', $table) ? true : false;
					$t['is_mp_code'] = $db->field_exists('mp_code', $table) ? true : false;
					$t['is_mouza_pargona_code'] = $db->field_exists('mouza_pargona_code', $table) ? true : false;
					$t['is_mouza_code'] = $db->field_exists('mouza_code', $table) ? true : false;
					$t['is_dag_no'] = $db->field_exists('dag_no', $table) ? true : false;
					$t['is_patta_no'] = $db->field_exists('patta_no', $table) ? true : false;
					$t['is_uuid'] = $db->field_exists('uuid', $table) ? true : false;
					$t['table'] = $table;
					$tables[] = $t;
				}
			}
			return $tables;
		} catch (\Throwable $th) {
			dd($th);
		}
	}

	public function transfer_skip_tables_for_vill_merge()
	{
		return array(
			'location',
			'location_old',
			'chitha_basic',
			'chitha_basic_nc',
			'chitha_dag_pattadar',
			'chitha_pattadar',
			'arrrev',
			'dainikamdani',
			'users',
			'usercirclemap',
			'user1',
			'loginuser_table',
			'nc_villages',
			'nc_village_dags',
			'nc_village_proposal',
			'change_vill_name',
			'changebuyer',
			'change_chitha_basic',
			'change_chitha_col8_order',
			'password_change_history_table',
			// 'land_bank_details',
			't_land_bank_details',
			// 'c_land_bank_details',
			// 'land_share_details'
		);
	}
}
