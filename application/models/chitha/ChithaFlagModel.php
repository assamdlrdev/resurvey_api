<?php

class ChithaFlagModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->utilityclass->switchDb($this->session->userdata('dcode'));
	}

	public function getDagforchithaAllNewMapping($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code, $start, $length, $order, $searchByCol_0, $searchByCol_1)
	{
		$this->utilityclass->switchDb($dist_code);

		if (isset($searchByCol_0) && $searchByCol_0 != null) {
			$cons = " and (dag_no like '%$searchByCol_0%') ";
		} else {
			$cons = '';
		}


		$q = "select t1.dag_no,t1.land_type from 
		(
			Select * from  chitha_basic join landclass_code on
			chitha_basic.land_class_code = landclass_code.class_code  where Dist_code=? and Subdiv_code=? and  cir_code=? 
			and Mouza_Pargona_code=? and Lot_No=?
			and Vill_townprt_code=? $cons order by CAST(coalesce(dag_no_int, '0') AS numeric)
		) as t1
		left join (

			Select * from  chitha_dag_all_flag_details  where Dist_code=? and Subdiv_code=? and  cir_code=? 
			and Mouza_Pargona_code=? and Lot_No=?
			and Vill_townprt_code=? 

		) as t2 
		on t1.dist_code = t2.dist_code
		and t1.subdiv_code = t2.subdiv_code 
		and t1.cir_code = t2.cir_code 
		and t1.Mouza_Pargona_code = t2.Mouza_Pargona_code
		and t1.lot_no = t2.lot_no 
		and t1.Vill_townprt_code = t2.Vill_townprt_code 
		and t1.dag_no=t2.dag_no 
		where t2.dag_no is null limit $length offset $start";


		$district = $this->db->query($q, array($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code, $dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code));

		return $district->result();
	}

	public function getDagforchithaAllNewMappingTotRecords($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code, $start, $length, $order, $searchByCol_0, $searchByCol_1)
	{
		$this->utilityclass->switchDb($dist_code);

		if (isset($searchByCol_0) && $searchByCol_0 != null) {
			$cons = " and (dag_no like '%$searchByCol_0%') ";
		} else {
			$cons = '';
		}


		$q = "select * from (
			Select * from  chitha_basic  where Dist_code=? and Subdiv_code=? and  cir_code=? 
			and Mouza_Pargona_code=? and Lot_No=?
			and Vill_townprt_code=? $cons order by CAST(coalesce(dag_no_int, '0') AS numeric)
		) as t1
		left join 
		(
			Select * from  chitha_dag_all_flag_details  where Dist_code=? and Subdiv_code=? and  cir_code=? 
			and Mouza_Pargona_code=? and Lot_No=?
			and Vill_townprt_code=? 
		) as t2 
		on t1.dist_code = t2.dist_code
		and t1.subdiv_code = t2.subdiv_code 
		and t1.cir_code = t2.cir_code 
		and t1.Mouza_Pargona_code = t2.Mouza_Pargona_code
		and t1.lot_no = t2.lot_no 
		and t1.Vill_townprt_code = t2.Vill_townprt_code 
		and t1.dag_no=t2.dag_no
		where t2.dag_no is null";
		$district = $this->db->query($q, array($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code, $dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code));

		return $district->result();
	}

	public function getPendingCountOfVillage($dist_code, $subdiv_code, $cir_code, $status)
	{
		$sql = "Select dist_code,subdiv_code,cir_code,mouza_pargona_code,lot_no,vill_townprt_code from  chitha_dag_all_flag_details  
					where Dist_code=? and Subdiv_code=? and  cir_code=? and status=? group by dist_code,subdiv_code,cir_code,mouza_pargona_code,lot_no,vill_townprt_code";
		$district = $this->db->query($sql, array($dist_code, $subdiv_code, $cir_code, $status));
		return $district->result();
	}
	public function getApprovedCountOfVillage($dist_code, $subdiv_code, $cir_code, $status)
	{
		$sql = "Select dist_code,subdiv_code,cir_code,mouza_pargona_code,lot_no,vill_townprt_code from  chitha_dag_all_flag_details_final  
					where Dist_code=? and Subdiv_code=? and  cir_code=? and status=? group by dist_code,subdiv_code,cir_code,mouza_pargona_code,lot_no,vill_townprt_code";
		$district = $this->db->query($sql, array($dist_code, $subdiv_code, $cir_code, $status));
		return $district->result();
	}
	public function getPendingCountOfVillageLM($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $status)
	{
		$sql = "Select dist_code,subdiv_code,cir_code,mouza_pargona_code,lot_no,vill_townprt_code from  chitha_dag_all_flag_details  
					where Dist_code=? and Subdiv_code=? and  cir_code=? and  mouza_pargona_code = ? and lot_no = ? and status=? group by dist_code,subdiv_code,cir_code,mouza_pargona_code,lot_no,vill_townprt_code";
		$district = $this->db->query($sql, array($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $status));
		return $district->result();
	}
	public function getApprovedCountOfVillageLM($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $status)
	{
		$sql = "Select dist_code,subdiv_code,cir_code,mouza_pargona_code,lot_no,vill_townprt_code from  chitha_dag_all_flag_details_final  
					where Dist_code=? and Subdiv_code=? and  cir_code=? and mouza_pargona_code = ? and lot_no = ? and status=? group by dist_code,subdiv_code,cir_code,mouza_pargona_code,lot_no,vill_townprt_code";
		$district = $this->db->query($sql, array($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $status));
		return $district->result();
	}

	public function getPendingDagFlagList($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code, $status)
	{
		$sql = "Select * from chitha_dag_all_flag_details join settlement_premium_area on chitha_dag_all_flag_details.area_flag = settlement_premium_area.paid where 
					Dist_code=? and Subdiv_code=? and  cir_code=? and mouza_pargona_code = ? and lot_no=? and vill_townprt_code = ? and status=? ";
		$district = $this->db->query($sql, array($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code, $status));
		return $district->result();
	}

	public function getRejectedCountOfVillage($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $status)
	{
		$sql = "Select * from chitha_dag_all_flag_details join settlement_premium_area on chitha_dag_all_flag_details.area_flag = settlement_premium_area.paid where 
					Dist_code=? and Subdiv_code=? and  cir_code=? and mouza_pargona_code = ? and lot_no = ? and vill_townprt_code = ? and status in ('R','V') ";
		$district = $this->db->query($sql, array($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code));
		return $district->result();
	}

	public function getForwardToCOCountOfVillage($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code)
	{
		$sql = "Select * from chitha_dag_all_flag_details join settlement_premium_area on chitha_dag_all_flag_details.area_flag = settlement_premium_area.paid where 
					Dist_code=? and Subdiv_code=? and  cir_code=? and mouza_pargona_code = ? and lot_no=? and vill_townprt_code = ? and (status is null or status in ('V','U')) ";
		$district = $this->db->query($sql, array($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code));
		return $district->result();
	}


	public function getDagforchithaAllNewMappingView($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code, $start, $length, $order, $searchByCol_0, $searchByCol_1, $status)
	{
		$this->utilityclass->switchDb($dist_code);

		if (isset($searchByCol_0) && $searchByCol_0 != null) {
			$cons = " and (dag_no like '%$searchByCol_0%') ";
		} else {
			$cons = '';
		}

		if (isset($searchByCol_1) && $searchByCol_1 != null) {
			$cons1 = " and (settlement_premium_area.area like '%$searchByCol_1%') ";
		} else {
			$cons1 = '';
		}


		$q = "select t1.dag_no,t1.land_type,t2.* from 
		(
			Select * from  chitha_basic join landclass_code on
			chitha_basic.land_class_code = landclass_code.class_code  where Dist_code=? and Subdiv_code=? and  cir_code=? 
			and Mouza_Pargona_code=? and Lot_No=?
			and Vill_townprt_code=? $cons order by CAST(coalesce(dag_no_int, '0') AS numeric)
		) as t1
		join (

			Select chitha_dag_all_flag_details_final.*,settlement_premium_area.area from  chitha_dag_all_flag_details_final join settlement_premium_area on chitha_dag_all_flag_details_final.area_flag=settlement_premium_area.paid where Dist_code=? and Subdiv_code=? and  cir_code=? 
			and Mouza_Pargona_code=? and Lot_No=?
			and Vill_townprt_code=? $cons1

		) as t2 
		on t1.dist_code = t2.dist_code
		and t1.subdiv_code = t2.subdiv_code 
		and t1.cir_code = t2.cir_code 
		and t1.Mouza_Pargona_code = t2.Mouza_Pargona_code
		and t1.lot_no = t2.lot_no 
		and t1.Vill_townprt_code = t2.Vill_townprt_code 
		and t1.dag_no=t2.dag_no 
		where t2.status = '$status' order by CAST(coalesce(t1.dag_no_int, '0') AS numeric) limit $length offset $start ";


		$district = $this->db->query($q, array($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code, $dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code));

		return $district->result();
	}

	public function getDagforchithaAllNewMappingTotRecordsView($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code, $start, $length, $order, $searchByCol_0, $searchByCol_1, $status)
	{
		$this->utilityclass->switchDb($dist_code);

		if (isset($searchByCol_0) && $searchByCol_0 != null) {
			$cons = " and (dag_no like '%$searchByCol_0%') ";
		} else {
			$cons = '';
		}

		if (isset($searchByCol_1) && $searchByCol_1 != null) {
			$cons1 = " and (settlement_premium_area.area like '%$searchByCol_1%') ";
		} else {
			$cons1 = '';
		}


		$q = "select * from (
			Select * from  chitha_basic  where Dist_code=? and Subdiv_code=? and  cir_code=? 
			and Mouza_Pargona_code=? and Lot_No=?
			and Vill_townprt_code=? $cons order by CAST(coalesce(dag_no_int, '0') AS numeric)
		) as t1
		join 
		(
			Select chitha_dag_all_flag_details_final.*,settlement_premium_area.area from  chitha_dag_all_flag_details_final join settlement_premium_area on chitha_dag_all_flag_details_final.area_flag=settlement_premium_area.paid where Dist_code=? and Subdiv_code=? and  cir_code=? 
			and Mouza_Pargona_code=? and Lot_No=?
			and Vill_townprt_code=? $cons1
		) as t2 
		on t1.dist_code = t2.dist_code
		and t1.subdiv_code = t2.subdiv_code 
		and t1.cir_code = t2.cir_code 
		and t1.Mouza_Pargona_code = t2.Mouza_Pargona_code
		and t1.lot_no = t2.lot_no 
		and t1.Vill_townprt_code = t2.Vill_townprt_code 
		and t1.dag_no=t2.dag_no
		where t2.status='$status'";
		$district = $this->db->query($q, array($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code, $dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code));

		return $district->result();
	}

	public function getDagforchithaAllNewMappingRevert($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code, $start, $length, $order, $searchByCol_0, $searchByCol_1, $status)
	{
		$this->utilityclass->switchDb($dist_code);

		if (isset($searchByCol_0) && $searchByCol_0 != null) {
			$cons = " and (dag_no like '%$searchByCol_0%') ";
		} else {
			$cons = '';
		}


		$q = "select t1.dag_no,t1.land_type,t2.* from 
		(
			Select * from  chitha_basic join landclass_code on
			chitha_basic.land_class_code = landclass_code.class_code  where Dist_code=? and Subdiv_code=? and  cir_code=? 
			and Mouza_Pargona_code=? and Lot_No=?
			and Vill_townprt_code=? $cons order by CAST(coalesce(dag_no_int, '0') AS numeric)
		) as t1
		join (

			Select chitha_dag_all_flag_details.*,settlement_premium_area.area from  chitha_dag_all_flag_details join settlement_premium_area on chitha_dag_all_flag_details.area_flag=settlement_premium_area.paid where Dist_code=? and Subdiv_code=? and  cir_code=? 
			and Mouza_Pargona_code=? and Lot_No=?
			and Vill_townprt_code=? 

		) as t2 
		on t1.dist_code = t2.dist_code
		and t1.subdiv_code = t2.subdiv_code 
		and t1.cir_code = t2.cir_code 
		and t1.Mouza_Pargona_code = t2.Mouza_Pargona_code
		and t1.lot_no = t2.lot_no 
		and t1.Vill_townprt_code = t2.Vill_townprt_code 
		and t1.dag_no=t2.dag_no 
		where t2.status = '$status' limit $length offset $start";


		$district = $this->db->query($q, array($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code, $dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code));

		return $district->result();
	}

	public function getDagforchithaAllNewMappingTotRecordsRevert($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code, $status)
	{
		$this->utilityclass->switchDb($dist_code);

		$q = "select * from (
			Select * from  chitha_basic  where Dist_code=? and Subdiv_code=? and  cir_code=? 
			and Mouza_Pargona_code=? and Lot_No=?
			and Vill_townprt_code=? order by CAST(coalesce(dag_no_int, '0') AS numeric)
		) as t1
		join 
		(
			Select * from  chitha_dag_all_flag_details  where Dist_code=? and Subdiv_code=? and  cir_code=? 
			and Mouza_Pargona_code=? and Lot_No=?
			and Vill_townprt_code=? 
		) as t2 
		on t1.dist_code = t2.dist_code
		and t1.subdiv_code = t2.subdiv_code 
		and t1.cir_code = t2.cir_code 
		and t1.Mouza_Pargona_code = t2.Mouza_Pargona_code
		and t1.lot_no = t2.lot_no 
		and t1.Vill_townprt_code = t2.Vill_townprt_code 
		and t1.dag_no=t2.dag_no
		where t2.status='$status'";
		$district = $this->db->query($q, array($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code, $dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code));

		return $district->result();
	}


	public function getDagforchithaAllNewMappingPendingInCO($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code, $start, $length, $order, $searchByCol_0, $searchByCol_1, $status)
	{
		$this->utilityclass->switchDb($dist_code);

		if (isset($searchByCol_0) && $searchByCol_0 != null) {
			$cons = " and (dag_no like '%$searchByCol_0%') ";
		} else {
			$cons = '';
		}

		if (isset($searchByCol_1) && $searchByCol_1 != null) {
			$cons1 = " and (settlement_premium_area.area like '%$searchByCol_1%') ";
		} else {
			$cons1 = '';
		}


		$q = "select t1.dag_no,t1.land_type,t2.* from 
		(
			Select * from  chitha_basic join landclass_code on
			chitha_basic.land_class_code = landclass_code.class_code  where Dist_code=? and Subdiv_code=? and  cir_code=? 
			and Mouza_Pargona_code=? and Lot_No=?
			and Vill_townprt_code=? $cons order by CAST(coalesce(dag_no_int, '0') AS numeric)
		) as t1
		join (

			Select chitha_dag_all_flag_details.*,settlement_premium_area.area from  chitha_dag_all_flag_details join settlement_premium_area on chitha_dag_all_flag_details.area_flag=settlement_premium_area.paid where Dist_code=? and Subdiv_code=? and  cir_code=? 
			and Mouza_Pargona_code=? and Lot_No=?
			and Vill_townprt_code=? $cons1 

		) as t2 
		on t1.dist_code = t2.dist_code
		and t1.subdiv_code = t2.subdiv_code 
		and t1.cir_code = t2.cir_code 
		and t1.Mouza_Pargona_code = t2.Mouza_Pargona_code
		and t1.lot_no = t2.lot_no 
		and t1.Vill_townprt_code = t2.Vill_townprt_code 
		and t1.dag_no=t2.dag_no 
		where t2.status = '$status' order by CAST(coalesce(t1.dag_no_int, '0') AS numeric) limit $length offset $start ";


		$district = $this->db->query($q, array($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code, $dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code));

		return $district->result();
	}

	public function getDagforchithaAllNewMappingTotRecordsPendingInCO($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code, $start, $length, $order, $searchByCol_0, $searchByCol_1, $status)
	{
		$this->utilityclass->switchDb($dist_code);

		if (isset($searchByCol_0) && $searchByCol_0 != null) {
			$cons = " and (dag_no like '%$searchByCol_0%') ";
		} else {
			$cons = '';
		}

		if (isset($searchByCol_1) && $searchByCol_1 != null) {
			$cons1 = " and (settlement_premium_area.area like '%$searchByCol_1%') ";
		} else {
			$cons1 = '';
		}

		$q = "select * from (
			Select * from  chitha_basic  where Dist_code=? and Subdiv_code=? and  cir_code=? 
			and Mouza_Pargona_code=? and Lot_No=?
			and Vill_townprt_code=? $cons order by CAST(coalesce(dag_no_int, '0') AS numeric)
		) as t1
		join 
		(
			
			Select chitha_dag_all_flag_details.*,settlement_premium_area.area from  chitha_dag_all_flag_details join settlement_premium_area on chitha_dag_all_flag_details.area_flag=settlement_premium_area.paid where Dist_code=? and Subdiv_code=? and  cir_code=? 
			and Mouza_Pargona_code=? and Lot_No=?
			and Vill_townprt_code=? $cons1 


		) as t2 
		on t1.dist_code = t2.dist_code
		and t1.subdiv_code = t2.subdiv_code 
		and t1.cir_code = t2.cir_code 
		and t1.Mouza_Pargona_code = t2.Mouza_Pargona_code
		and t1.lot_no = t2.lot_no 
		and t1.Vill_townprt_code = t2.Vill_townprt_code 
		and t1.dag_no=t2.dag_no
		where t2.status='$status'";
		$district = $this->db->query($q, array($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code, $dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code));

		return $district->result();
	}

	public function getDagforchithaAllNewMappingUpdate($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code, $start, $length, $order, $searchByCol_0, $searchByCol_1, $status)
	{
		$this->utilityclass->switchDb($dist_code);

		if (isset($searchByCol_0) && $searchByCol_0 != null) {
			$cons = " and (dag_no like '%$searchByCol_0%') ";
		} else {
			$cons = '';
		}


		$q = "select t1.dag_no,t1.land_type,t2.* from 
		(
			Select * from  chitha_basic join landclass_code on
			chitha_basic.land_class_code = landclass_code.class_code  where Dist_code=? and Subdiv_code=? and  cir_code=? 
			and Mouza_Pargona_code=? and Lot_No=?
			and Vill_townprt_code=? $cons order by CAST(coalesce(dag_no_int, '0') AS numeric)
		) as t1
		join (

			Select chitha_dag_all_flag_details.*,settlement_premium_area.area from  chitha_dag_all_flag_details join settlement_premium_area on chitha_dag_all_flag_details.area_flag=settlement_premium_area.paid where Dist_code=? and Subdiv_code=? and  cir_code=? 
			and Mouza_Pargona_code=? and Lot_No=?
			and Vill_townprt_code=? 

		) as t2 
		on t1.dist_code = t2.dist_code
		and t1.subdiv_code = t2.subdiv_code 
		and t1.cir_code = t2.cir_code 
		and t1.Mouza_Pargona_code = t2.Mouza_Pargona_code
		and t1.lot_no = t2.lot_no 
		and t1.Vill_townprt_code = t2.Vill_townprt_code 
		and t1.dag_no=t2.dag_no 
		where t2.status = '$status' order by CAST(coalesce(t1.dag_no_int, '0') AS numeric) limit $length offset $start ";


		$district = $this->db->query($q, array($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code, $dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code));

		return $district->result();
	}

	public function getDagforchithaAllNewMappingTotRecordsUpdate($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code, $start, $length, $order, $searchByCol_0, $searchByCol_1, $status)
	{
		$this->utilityclass->switchDb($dist_code);

		if (isset($searchByCol_0) && $searchByCol_0 != null) {
			$cons = " and (dag_no like '%$searchByCol_0%') ";
		} else {
			$cons = '';
		}

		$q = "select * from (
			Select * from  chitha_basic  where Dist_code=? and Subdiv_code=? and  cir_code=? 
			and Mouza_Pargona_code=? and Lot_No=?
			and Vill_townprt_code=? $cons order by CAST(coalesce(dag_no_int, '0') AS numeric)
		) as t1
		join 
		(
			Select * from  chitha_dag_all_flag_details  where Dist_code=? and Subdiv_code=? and  cir_code=? 
			and Mouza_Pargona_code=? and Lot_No=?
			and Vill_townprt_code=? 
		) as t2 
		on t1.dist_code = t2.dist_code
		and t1.subdiv_code = t2.subdiv_code 
		and t1.cir_code = t2.cir_code 
		and t1.Mouza_Pargona_code = t2.Mouza_Pargona_code
		and t1.lot_no = t2.lot_no 
		and t1.Vill_townprt_code = t2.Vill_townprt_code 
		and t1.dag_no=t2.dag_no
		where t2.status='$status'";
		$district = $this->db->query($q, array($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code, $dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code));

		return $district->result();
	}

	public function getSubCategory($category)
	{
		$sql = "Select * from settlement_premium_sub_category where cat_id = ? ";
		$district = $this->db->query($sql, array($category));
		return $district->result();
	}


	public function getApprovedFinalDagFlagList($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code, $status)
	{
		$sql = "Select * from chitha_dag_all_flag_details_final join settlement_premium_area on chitha_dag_all_flag_details_final.area_flag = settlement_premium_area.paid where 
					Dist_code=? and Subdiv_code=? and  cir_code=? and mouza_pargona_code = ? and lot_no=? and vill_townprt_code = ? and status=? ";
		$district = $this->db->query($sql, array($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_code, $status));
		return $district->result();
	}
}
