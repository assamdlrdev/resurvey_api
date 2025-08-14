<?php
class Dagflagmodel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->utilityclass->switchDb($this->session->userdata('dcode'));
	}
	
	public function getDistrictName()
	{
		$CI = &get_instance();
		$this->db2 = $CI->load->database('db2', TRUE);
		$district = $this->db2->query("select district_name,district_code AS district from   district_details ");
		return $district->result();
	}
	public function getInsertedCountOfVillage($uuid)
	{
		$this->utilityclass->switchDb($this->session->userdata('dcode'));
		$data = $this->db->query("select count(*) as total from area_dag_flag where uuid = '$uuid'");
		return $data->row()->total;
	}



	public function getRevertedVillageList($uuid)
	{
		$this->utilityclass->switchDb($this->session->userdata('dcode'));
		$data = $this->db->query("select count(*) as total from area_dag_flag where uuid = '$uuid' and status = 'R'");
		if (isset($data->row()->total)) {
			$count = $data->row()->total;
			return $count;
		} else {
			return 0;
		}
	}
	//not in use============
	public function getDagforchithalower($district_code, $subdivision_code, $circle_code, $mouza_code, $lot_code, $village_code)
	{
		$district = $this->db->query(""
			. "Select dag_no, dag_no_int from   Chitha_Basic where "
			. "Dist_code='$district_code' and Subdiv_code='$subdivision_code' and  patta_type_code='$p' and "
			. "Cir_code='$circle_code' and Mouza_Pargona_code='$mouza_code' and Lot_No='$lot_code' "
			. "and Vill_townprt_code='$village_code' and CAST(coalesce(dag_no_int, '0') AS numeric)>='$l' order by CAST(coalesce(dag_no_int, '0') AS numeric)");
		return $district->result();
	}

	public function getVillageCode($distCode, $subdivcode, $circode, $status)
	{
		$this->utilityclass->switchDb($this->session->userdata('dcode'));
		$district = $this->db->query("select distinct adf.uuid from   area_dag_flag adf join location l on 
        	adf.uuid = l.uuid
        	where "
			. "dist_code =?  and "
			. " subdiv_code=? and cir_code=? and status = ?", array($distCode, $subdivcode, $circode, $status));

		return $district->result();
	}

	public function getDagforMapping($uuid, $status)
	{
		$this->utilityclass->switchDb($this->session->userdata('dcode'));
		$district = $this->db->query("select dag_no,area,cat,co_remark from   area_dag_flag adf join settlement_premium_area l on 
        	adf.cat = l.paid
        	where "
			. "uuid =? and status = ?", array($uuid, $status));

		return $district->result();
	}

	public function updateChithaMapped($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag_no, $cat)
	{


		$query = "update chitha_basic set dag_area_type='$cat' where dist_code='$dist_code' and subdiv_code='$subdiv_code' and "
			. "cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' and dag_no in (" . $dag_no . ")";
		$this->db->query($query);

		// log_message('error',"QUERY01==".$this->db->last_query());
		return $this->db->affected_rows();
	}

	public function updateJamaMapped($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag_no, $cat)
	{


		$query = "update jama_dag set dag_area_type='$cat' where dist_code='$dist_code' and subdiv_code='$subdiv_code' and "
			. "cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' and dag_no in (" . $dag_no . ")";
		$this->db->query($query);


		return $this->db->affected_rows();
		// log_message('error',"QUERY01==".$this->db->last_query());
	}

	public function updateChithaMappedCompleteOrNot($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code)
	{
		$query = "Select count(*) as total from chitha_basic where dist_code='$dist_code' and subdiv_code='$subdiv_code' and "
			. "cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' and dag_area_type is not null";
		return $this->db->query($query)->total;
	}

	public function getDagforMappingForApprove($uuid, $status)
	{
		$this->utilityclass->switchDb($this->session->userdata('dcode'));

		$district = $this->db->query("select cat,string_agg(dag_no,',') as dag_no from area_dag_flag where uuid =? and status='P' group by cat", array($uuid));

		return $district->result();
	}

	public function getVillageCodeJSONRevert($distCode, $subdivcode, $circode, $mouzacode, $lotno)
	{
		$this->utilityclass->switchDb($this->session->userdata('dcode'));
		$district = $this->db->query("select distinct loc_name,vill_townprt_code,l.uuid,co_remark from   location l

        	join area_dag_flag adf on l.uuid = adf.uuid
         where adf.status = 'R' and "
			. "dist_code =?  and "
			. " subdiv_code=? and cir_code=? and mouza_pargona_code=? and "
			. " vill_townprt_code!='00000' and lot_no=? and nc_btad is null", array($distCode, $subdivcode, $circode, $mouzacode, $lotno));

		return $district->result();
	}

	public function getLocationFromUUID($uuid)
	{
		$CI = &get_instance();
		$sql = $CI->db->query("SELECT l.dist_code, l.subdiv_code, l.cir_code, l.mouza_pargona_code, l.lot_no, l.vill_townprt_code,
        (SELECT loc_name AS dist_name FROM location t WHERE t.dist_code= l.dist_code AND t.subdiv_code = '00'),
        (SELECT loc_name AS subdiv_name FROM location t WHERE t.dist_code= l.dist_code AND t.subdiv_code = l.subdiv_code AND t.cir_code = '00'),
        (SELECT loc_name AS cir_name FROM location t WHERE t.dist_code= l.dist_code AND t.subdiv_code = l.subdiv_code AND t.cir_code = l.cir_code AND t.mouza_pargona_code = '00'),
        (SELECT loc_name AS mouza_name FROM location t WHERE t.dist_code= l.dist_code AND t.subdiv_code = l.subdiv_code AND t.cir_code = l.cir_code AND t.mouza_pargona_code = l.mouza_pargona_code AND t.lot_no = '00'),
        (SELECT loc_name AS lot_name FROM location t WHERE t.dist_code= l.dist_code AND t.subdiv_code = l.subdiv_code AND t.cir_code = l.cir_code AND t.mouza_pargona_code = l.mouza_pargona_code AND t.lot_no = l.lot_no AND t.vill_townprt_code = '00000'),
        (SELECT loc_name AS village_name FROM location t WHERE t.dist_code= l.dist_code AND t.subdiv_code = l.subdiv_code AND t.cir_code = l.cir_code AND t.mouza_pargona_code = l.mouza_pargona_code AND t.lot_no = l.lot_no AND t.vill_townprt_code = l.vill_townprt_code) 
        FROM location l WHERE uuid = ?", array($uuid));

		if ($sql->num_rows() > 0) {
			return $sql->row();
		} else {
			return false;
		}
	}

	public function getDagListRuralUrban($uuid, $status)
	{
		$this->utilityclass->switchDb($this->session->userdata('dcode'));
		$district = $this->db->query("SELECT dag_no, rural_urban AS ru_flag,CASE rural_urban WHEN 'U' THEN 'Urban'WHEN 'R' THEN 'Rural' ELSE rural_urban END AS rural_urban,co_remark 
		FROM chitha_dag_flag WHERE uuid = ? AND status = ? AND rural_urban IS NOT NULL AND dag_flag IS NULL", array($uuid, $status));

		return $district->result();
	}

	public function getDagListOtherFlag($uuid, $status)
	{
		$this->utilityclass->switchDb($this->session->userdata('dcode'));
		$district = $this->db->query("select dag_no,flag_name,dag_flag,co_remark from   chitha_dag_flag cdf join dag_flag_master l on 
		cdf.dag_flag = l.flagid where uuid = ? and status = ? and dag_flag is not NULL and rural_urban is null", array($uuid, $status));

		return $district->result();
	}


	public function getPendingDagforRuralUrbanFlagging($uuid, $status)
	{
		$this->utilityclass->switchDb($this->session->userdata('dcode'));
		$district = $this->db->query("select dag_no,area,cat,co_remark from   area_dag_flag adf join settlement_premium_area l on 
        	adf.cat = l.paid
        	where "
			. "uuid =? and status = ?", array($uuid, $status));

		return $district->result();
	}

	public function getInsertedCountOfDagFlagVillage($uuid)
	{
		$this->utilityclass->switchDb($this->session->userdata('dcode'));
		$data = $this->db->query("select count(*) as total from chitha_dag_flag where uuid = '$uuid' and dag_flag is not null and rural_urban is null and status ='P'");
		return $data->row()->total;
	}


	public function getZonalDagforFlaggingForApprove($uuid, $status)
	{
		$this->utilityclass->switchDb($this->session->userdata('dcode'));

		$district = $this->db->query("select dag_flag,string_agg(dag_no,',') as dag_no from chitha_dag_flag where uuid =? and  dag_flag is not null and rural_urban is null and status='P' group by dag_flag", array($uuid));

		return $district->result();
	}

	public function getRuralUrbanDagforFlaggingForApprove($uuid, $status)
	{
		$this->utilityclass->switchDb($this->session->userdata('dcode'));

		$district = $this->db->query("select rural_urban,string_agg(dag_no,',') as dag_no from chitha_dag_flag where uuid =? and  dag_flag is  null and rural_urban is not null and status='P' group by rural_urban", array($uuid));

		return $district->result();
	}

	public function updateChithaZonalFlagged($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag_no, $flag)
	{


		$query = "update chitha_basic set dag_flag_type='$flag' where dist_code='$dist_code' and subdiv_code='$subdiv_code' and "
			. "cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' and dag_no in (" . $dag_no . ")";
		$this->db->query($query);

		// log_message('error',"QUERY01==".$this->db->last_query());
		return $this->db->affected_rows();
	}

	public function updateJamaZonalFlagged($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag_no, $cat)
	{


		$query = "update jama_dag set dag_flag_type='$cat' where dist_code='$dist_code' and subdiv_code='$subdiv_code' and "
			. "cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' and dag_no in (" . $dag_no . ")";
		$this->db->query($query);


		return $this->db->affected_rows();
		// log_message('error',"QUERY01==".$this->db->last_query());
	}

	public function getVillageCodeForOtherFlag($distCode, $subdivcode, $circode, $status)
	{
		$this->utilityclass->switchDb($this->session->userdata('dcode'));
		$district = $this->db->query("select distinct uuid from  chitha_dag_flag 
        	where "
			. "dist_code =?  and "
			. " subdiv_code=? and cir_code=? and status = ? and dag_flag is not NULL and rural_urban is null", array($distCode, $subdivcode, $circode, $status));

		return $district->result();
	}
	//Get Inserted Dag Count of Flag in case of Full Flagging
	public function getInsertedDagCountOfVillageOtherFlag($uuid)
	{
		$this->utilityclass->switchDb($this->session->userdata('dcode'));
		$data = $this->db->query("select * from chitha_dag_flag where uuid = '$uuid' and rural_urban is  NULL and dag_flag is not NULL");
		return $data;
	}
	public function getVillageCodeJSONRevertDagFlag($distCode, $subdivcode, $circode, $mouzacode, $lotno)
	{
		$this->utilityclass->switchDb($this->session->userdata('dcode'));
		$district = $this->db->query("select distinct l.loc_name,l.vill_townprt_code,l.uuid from   location l
        	join chitha_dag_flag cdf on l.uuid = cdf.uuid
         where cdf.status = 'R' and cdf.rural_urban is null and  cdf.dag_flag is not null and "
			. "l.dist_code =?  and "
			. " l.subdiv_code=? and l.cir_code=? and l.mouza_pargona_code=? and "
			. " l.vill_townprt_code!='00000' and l.lot_no=? and l.nc_btad is null", array($distCode, $subdivcode, $circode, $mouzacode, $lotno));

		return $district->result();
	}

	public function getDagforchithaAll($district_code, $subdivision_code, $circle_code, $mouza_code, $lot_code, $village_code)
	{
		$this->utilityclass->switchDb($this->session->userdata('dcode'));
		$q = "Select dag_no, dag_no_int from   Chitha_Basic where Dist_code='$district_code' and Subdiv_code='$subdivision_code' and "
			. "Cir_code='$circle_code' and Mouza_Pargona_code='$mouza_code' and Lot_No='$lot_code' "
			. "and Vill_townprt_code='$village_code' order by CAST(coalesce(dag_no_int, '0') AS numeric)";
		$district = $this->db->query($q);
		return $district->result();
	}

	public function getChithaDagsForFlaggingOtherFlag($district_code, $subdivision_code, $circle_code, $mouza_code, $lot_code, $village_code)
	{
		$this->utilityclass->switchDb($this->session->userdata('dcode'));

		$q = "SELECT dag_no, dag_no_int FROM Chitha_Basic C  WHERE C.dag_no  NOT IN (SELECT dag_no  FROM chitha_dag_flag WHERE dist_code = '$district_code' AND subdiv_code = '$subdivision_code' AND cir_code = '$circle_code' AND mouza_pargona_code = '$mouza_code' AND lot_no = '$lot_code' AND vill_townprt_code = '$village_code' AND rural_urban is  NULL AND dag_flag is NOT NULL) AND C.Dist_code = '$district_code' AND C.Subdiv_code = '$subdivision_code' AND C.Cir_code = '$circle_code' AND C.Mouza_Pargona_code = '$mouza_code' AND C.Lot_No = '$lot_code' AND C.Vill_townprt_code = '$village_code'  order by CAST(coalesce(C.dag_no_int, '0') AS numeric)";
		$unflaggedDag = $this->db->query($q);
		return $unflaggedDag->result();
	}



	public function deletePartialMappingDagOfVillageOtherFlag($uuid)
	{
		$this->utilityclass->switchDb($this->session->userdata('dcode'));
		$this->db->query("delete from chitha_dag_flag where uuid = '$uuid' and rural_urban is  NULL and dag_flag is not NULL");
		return $this->db->trans_status();
	}


	//Rural/Urban
	public function getInsertedDagCountOfVillageRuralUrban($uuid)
	{
		$this->utilityclass->switchDb($this->session->userdata('dcode'));
		$data = $this->db->query("select * from chitha_dag_flag where uuid = '$uuid' and rural_urban is not NULL and dag_flag is NULL");
		return $data;
	}


	public function getChithaDagsForFlaggingRuralUrbanFlag($district_code, $subdivision_code, $circle_code, $mouza_code, $lot_code, $village_code)
	{
		$this->utilityclass->switchDb($this->session->userdata('dcode'));

		$q = "SELECT dag_no, dag_no_int FROM Chitha_Basic C  WHERE C.dag_no  NOT IN (SELECT dag_no  FROM chitha_dag_flag WHERE dist_code = '$district_code' AND subdiv_code = '$subdivision_code' AND cir_code = '$circle_code' AND mouza_pargona_code = '$mouza_code' AND lot_no = '$lot_code' AND vill_townprt_code = '$village_code' AND rural_urban is NOT  NULL AND dag_flag is  NULL) AND C.Dist_code = '$district_code' AND C.Subdiv_code = '$subdivision_code' AND C.Cir_code = '$circle_code' AND C.Mouza_Pargona_code = '$mouza_code' AND C.Lot_No = '$lot_code' AND C.Vill_townprt_code = '$village_code'  order by CAST(coalesce(C.dag_no_int, '0') AS numeric)";
		$unflaggedDag = $this->db->query($q);
		return $unflaggedDag->result();
	}

	public function deletePartialMappingDagOfVillageRuralUrban($uuid)
	{
		$this->utilityclass->switchDb($this->session->userdata('dcode'));
		$this->db->query("delete from chitha_dag_flag where uuid = '$uuid' and rural_urban is not  NULL and dag_flag is  NULL");
		return $this->db->trans_status();
	}


	public function getVillageCodeForRuralUrban($distCode, $subdivcode, $circode, $status)
	{
		$this->utilityclass->switchDb($this->session->userdata('dcode'));
		$district = $this->db->query("select distinct uuid from  chitha_dag_flag 
        	where "
			. "dist_code =?  and "
			. " subdiv_code=? and cir_code=? and status = ? and dag_flag is  NULL and rural_urban is NOT null", array($distCode, $subdivcode, $circode, $status));

		return $district->result();
	}


	public function getInsertedCountOfRuralUrbanFlagVillage($uuid)
	{
		$this->utilityclass->switchDb($this->session->userdata('dcode'));
		$data = $this->db->query("select count(*) as total from chitha_dag_flag where uuid = '$uuid' and dag_flag is  null and rural_urban is not null");
		return $data->row()->total;
	}







	public function updateJamaRuralUrbanFlagged($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag_no, $cat)
	{


		$query = "update jama_dag set dag_rural_urban_type='$cat' where dist_code='$dist_code' and subdiv_code='$subdiv_code' and "
			. "cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' and dag_no in (" . $dag_no . ")";
		$this->db->query($query);


		return $this->db->affected_rows();
		// log_message('error',"QUERY01==".$this->db->last_query());
	}


	public function getVillageCodeJSONRevertRuralUrban($distCode, $subdivcode, $circode, $mouzacode, $lotno)
	{
		$this->utilityclass->switchDb($this->session->userdata('dcode'));
		$district = $this->db->query("select distinct l.loc_name,l.vill_townprt_code,l.uuid,cdf.co_remark from   location l
        	join chitha_dag_flag cdf on l.uuid = cdf.uuid
         where cdf.status = 'R' and cdf.rural_urban is not null and  cdf.dag_flag is  null and "
			. "l.dist_code =?  and "
			. " l.subdiv_code=? and l.cir_code=? and l.mouza_pargona_code=? and "
			. " l.vill_townprt_code!='00000' and l.lot_no=? and l.nc_btad is null", array($distCode, $subdivcode, $circode, $mouzacode, $lotno));

		return $district->result();
	}


	public function updateChithaRuralUrbanFlagged($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag_no, $flag)
	{


		$query = "update chitha_basic set dag_rural_urban_type='$flag' where dist_code='$dist_code' and subdiv_code='$subdiv_code' and "
			. "cir_code='$cir_code' and mouza_pargona_code='$mouza_pargona_code' and lot_no='$lot_no' and vill_townprt_code='$vill_townprt_code' and dag_no in (" . $dag_no . ")";
		$this->db->query($query);

		// log_message('error',"QUERY01==".$this->db->last_query());
		return $this->db->affected_rows();
	}
}
