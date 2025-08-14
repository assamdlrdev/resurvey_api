<?php

include APPPATH . '/libraries/CommonTrait.php';
class PsTsController extends CI_Controller
{
	use CommonTrait;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('CommonModel');
//		$this->load->model('UtilsModel');
		$this->load->model('RemarkModel');
		$this->load->model('DagReportModel');
		$this->load->model('nc_village/PortingModel');
		if ($this->session->userdata('usertype') != 4) {
			show_error('<svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><g fill="none" stroke="#FF0000" stroke-linecap="round" stroke-width="2"><path d="M12 9v5m0 3.5v.5"/><path stroke-linejoin="round" d="M2.232 19.016L10.35 3.052c.713-1.403 2.59-1.403 3.302 0l8.117 15.964C22.45 20.36 21.544 22 20.116 22H3.883c-1.427 0-2.334-1.64-1.65-2.984Z"/></g></svg> <p>Unauthorized access</p>', "403");
		}
	}

	public function location()
	{
		$this->dbswitch();
		$dist_code = $this->session->userdata('dcode');
		$subdiv_code = $this->session->userdata('subdiv_code');
		$cir_code = $this->session->userdata('cir_code');
		$data['locations'] = $this->CommonModel->getLocations($dist_code, $subdiv_code, $cir_code);
		$data['mouzas'] = $this->db->select('loc_name,dist_code,subdiv_code,cir_code,mouza_pargona_code')
			->where(array('dist_code' => $dist_code, 'subdiv_code' => $subdiv_code, 'cir_code' => $cir_code, 'mouza_pargona_code !=' => '00', 'lot_no' => '00'))
			->get('location')->result_array();
		$data['_view'] = 'ps_ts_flag/location';
		$this->load->view('layout/layout', $data);
	}

	public function lotdetails()
	{
		$this->dataswitch();
		$data = [];
		$dis =  $this->session->userdata('dcode');
		$subdiv = $this->session->userdata('subdiv_code');
		$cir = $this->session->userdata('cir_code');
		$mza = $this->input->post('mza');
		$formdata = $this->Chithamodel->lotdetails($dis, $subdiv, $cir, $mza);
		foreach ($formdata as $value) {
			$data['test'][] = $value;
		}
		echo json_encode($data['test']);
	}

	public function villagedetails()
	{
		$data = [];
		$dis = $this->session->userdata('dcode');
		$subdiv = $this->session->userdata('subdiv_code');
		$cir = $this->session->userdata('cir_code');
		$mza = $this->input->post('mza');
		$lot = $this->input->post('lot');
		$this->dataswitch();

		$formdata = $this->Chithamodel->villagedetails($dis, $subdiv, $cir, $mza, $lot);

		foreach ($formdata as $key=>$value) {
			$flag_check = $this->db->select('count(*)')
				->where(array(
					'dist_code' => $dis,
					'subdiv_code' => $subdiv,
					'cir_code' => $cir,
					'mouza_pargona_code' => $mza,
					'lot_no' => $lot,
					'vill_townprt_code' => $value['vill_townprt_code']
				))
				->group_start() // Start grouping for OR condition
				->where('ps_ts_flag', PS_FLAG)
				->or_where('ps_ts_flag', TS_FLAG)
				->group_end() // End grouping
				->get('chitha_basic')
				->row_array();

			$data[$key] = array_merge($value,$flag_check);
		}
		echo json_encode($data);
	}

	public function submitFlag()
	{
		$this->form_validation->set_rules('mouza_pargona_code', 'Mouza Name', 'trim|integer|required');
		$this->form_validation->set_rules('lot_no', 'Lot Number', 'trim|integer|required');
		$this->form_validation->set_rules('vill_townprt_code', 'Village Name', 'trim|integer|required');
		$this->form_validation->set_rules('flag', 'Flag', 'trim|required');
		$dis = $this->session->userdata('dcode');
		$subdiv = $this->session->userdata('subdiv_code');
		$cir = $this->session->userdata('cir_code');
		$this->dataswitch();
		if ($this->form_validation->run()) {
			$mouza_pargona_code = trim($this->input->post('mouza_pargona_code'));
			$lot_no = trim($this->input->post('lot_no'));
			$vill_townprt_code = trim($this->input->post('vill_townprt_code'));
			$flag = trim($this->input->post('flag'));

			$count_dag = $this->db->select('count(*)')
				->where(array(
					'dist_code' => $dis,
					'subdiv_code' => $subdiv,
					'cir_code' => $cir,
					'mouza_pargona_code' => $mouza_pargona_code,
					'lot_no' => $lot_no,
					'vill_townprt_code' => $vill_townprt_code
				))
				->get('chitha_basic')
				->row_array();

			$this->db->where('dist_code', $dis)
				->where('subdiv_code', $subdiv)
				->where('cir_code', $cir)
				->where('mouza_pargona_code', $mouza_pargona_code)
				->where('lot_no', $lot_no)
				->where('vill_townprt_code', $vill_townprt_code)
				->update('chitha_basic', array('ps_ts_flag' => $flag));

			if ($this->db->affected_rows() > 0) {
				echo json_encode(array(
					'response' => 1,
					'dag' => $count_dag['count'],
				));
			}else{
				echo json_encode(array(
					'response' => 0,
					'dag' => $count_dag['count'],
				));
			}
		}
	}

	public function mergeVillage()
	{
		$this->dbswitch();
		$dist_code = $this->session->userdata('dcode');
		$subdiv_code = $this->session->userdata('subdiv_code');
		$cir_code = $this->session->userdata('cir_code');
		$data['locations'] = $this->CommonModel->getLocations($dist_code, $subdiv_code, $cir_code);
		$data['mouzas'] = $this->db->select('loc_name,dist_code,subdiv_code,cir_code,mouza_pargona_code')
			->where(array('dist_code' => $dist_code, 'subdiv_code' => $subdiv_code, 'cir_code' => $cir_code, 'mouza_pargona_code !=' => '00', 'lot_no' => '00'))
			->get('location')->result_array();
		$data['_view'] = 'ps_ts_flag/merge_flag';
		$this->load->view('layout/layout', $data);
	}

	public function getBothVillages()
	{
		$data = [];
		$dis = $this->session->userdata('dcode');
		$subdiv = $this->session->userdata('subdiv_code');
		$cir = $this->session->userdata('cir_code');
		$mza = $this->input->post('mza');
		$lot = $this->input->post('lot');
		$this->dataswitch();

		$formdata = $this->Chithamodel->villagedetails($dis, $subdiv, $cir, $mza, $lot);

		foreach ($formdata as $key=>$value) {
			$flag_check_ts = $this->db->select('count(*) as flag')
				->where(array(
					'dist_code' => $dis,
					'subdiv_code' => $subdiv,
					'cir_code' => $cir,
					'mouza_pargona_code' => $mza,
					'lot_no' => $lot,
					'vill_townprt_code' => $value['vill_townprt_code']
				))
				->where('ps_ts_flag', TS_FLAG)
				->get('chitha_basic')
				->row_array();

			$data['ts'][$key] = array_merge($value,$flag_check_ts);

			$flag_check_ps = $this->db->select('count(*) as flag')
				->where(array(
					'dist_code' => $dis,
					'subdiv_code' => $subdiv,
					'cir_code' => $cir,
					'mouza_pargona_code' => $mza,
					'lot_no' => $lot,
					'vill_townprt_code' => $value['vill_townprt_code']
				))
				->where('ps_ts_flag', PS_FLAG)
				->get('chitha_basic')
				->row_array();

			$data['ps'][$key] = array_merge($value,$flag_check_ps);
		}
		echo json_encode($data);
	}

	public function getTsDags()
	{
		$this->form_validation->set_rules('mouza_pargona_code', 'Mouza Name', 'trim|integer|required');
		$this->form_validation->set_rules('lot_no', 'Lot Number', 'trim|integer|required');
		$this->form_validation->set_rules('vill_townprt_code', 'Village Name', 'trim|integer|required');
		$this->dataswitch();
		if ($this->form_validation->run()) {
			$dist_code = $this->session->userdata('dcode');
			$subdiv_code = $this->session->userdata('subdiv_code');
			$cir_code = $this->session->userdata('cir_code');
			$mouza_pargona_code = $this->input->post('mouza_pargona_code');
			$lot_no = $this->input->post('lot_no');
			$vill_townprt_code = $this->input->post('vill_townprt_code');

			$dags = $this->DagReportModel->getAllDags($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code);
			foreach ($dags as $dag) {
				$encroachers = $this->DagReportModel->occupiers($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag->dag_no);
				foreach ($encroachers as $encroacher) {
					$encroacher->families = $this->DagReportModel->families($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag->dag_no, $encroacher->encro_id);
				}
				$dag->encroachers = $encroachers;
			}
			echo json_encode($dags);
		}
	}

	public function getPsDags()
	{
		$this->form_validation->set_rules('mouza_pargona_code', 'Mouza Name', 'trim|integer|required');
		$this->form_validation->set_rules('lot_no', 'Lot Number', 'trim|integer|required');
		$this->form_validation->set_rules('vill_townprt_code', 'Village Name', 'trim|integer|required');
		$this->dataswitch();
		if ($this->form_validation->run()) {
			$dist_code = $this->session->userdata('dcode');
			$subdiv_code = $this->session->userdata('subdiv_code');
			$cir_code = $this->session->userdata('cir_code');
			$mouza_pargona_code = $this->input->post('mouza_pargona_code');
			$lot_no = $this->input->post('lot_no');
			$vill_townprt_code = $this->input->post('vill_townprt_code');

			$dags = $this->DagReportModel->getAllDags($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code);
			foreach ($dags as $dag) {
				$encroachers = $this->DagReportModel->occupiers($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag->dag_no);
				foreach ($encroachers as $encroacher) {
					$encroacher->families = $this->DagReportModel->families($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code, $dag->dag_no, $encroacher->encro_id);
				}
				$dag->encroachers = $encroachers;
			}
			echo json_encode($dags);
		}
	}

	public function submitMerge()
	{
		$this->form_validation->set_rules('mouza_pargona_code', 'Mouza Name', 'trim|integer|required');
		$this->form_validation->set_rules('lot_no', 'Lot Number', 'trim|integer|required');
		$this->form_validation->set_rules('vill_townprt_code_ts', 'Temporary Settlement Village Name', 'trim|integer|required');
		$this->form_validation->set_rules('vill_townprt_code_ps', 'Permanent Settlement Village Name', 'trim|integer|required');
		$this->dataswitch();
		if ($this->form_validation->run()) {
			$dist_code = $this->session->userdata('dcode');
			$subdiv_code = $this->session->userdata('subdiv_code');
			$cir_code = $this->session->userdata('cir_code');
			$mouza_pargona_code = $this->input->post('mouza_pargona_code');
			$lot_no = $this->input->post('lot_no');
			$vill_townprt_code_ts = $this->input->post('vill_townprt_code_ts');
			$vill_townprt_code_ps = $this->input->post('vill_townprt_code_ps');

			$checkAlreadyPorted = $this->checkAlreadyPorted($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code_ts);
			if($checkAlreadyPorted > 0)
			{
				$array = array(
					'error' => true,
					'msg' => 'Given that the temporary settlement village is already merged.',
				);
				echo json_encode($array);
				return;
			}

			$distinctChithaBasic = $this->distinctChithaBasicTs($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code_ts);

			$max_dag = $this->maxDag($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code_ps);
			$max_patta_no = $this->maxPattaNo($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code_ps);

			$chithaBasicDataPs = $this->chithaBasicDataPs($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code_ps);

			$tables = $this->portAllTables();
			ini_set('max_execution_time', '0');
			ini_set('memory_limit', '-1');
			try {
				$this->db->trans_begin();
				foreach ($distinctChithaBasic as $disChBasic)
				{
					$chiha_basicsTs = $this->chihaBasicTs($dist_code, $subdiv_code, $cir_code,
						$mouza_pargona_code, $lot_no, $vill_townprt_code_ts,
						$disChBasic->patta_no,$disChBasic->patta_type_code);

					foreach ($chiha_basicsTs as $chitha_basic)
					{
						$insertIntoChithaBasic = $this->insertIntoChithaBasicPs($chitha_basic,$max_dag,$max_patta_no,$chithaBasicDataPs->uuid,$dist_code, $subdiv_code, $cir_code,
							$mouza_pargona_code, $lot_no, $vill_townprt_code_ps);

						$this->insertIntoTsPsMerge($dist_code, $subdiv_code, $cir_code,
							$mouza_pargona_code, $lot_no,
							$vill_townprt_code_ps,$max_dag,
							$max_patta_no,$chithaBasicDataPs->uuid,$chitha_basic);

						$chiha_dag_pattadars = $this->chihaDagPattdarTs($dist_code, $subdiv_code, $cir_code,
							$mouza_pargona_code, $lot_no, $vill_townprt_code_ts,
							$disChBasic->patta_no,$disChBasic->patta_type_code,$chitha_basic->dag_no);

						foreach ($chiha_dag_pattadars as $chitha_dag_pattadar)
						{
							$insertIntoChithaDagPattadarPs = $this->insertIntoChithaDagPattadarPs($chitha_dag_pattadar,$max_dag,$max_patta_no,$chithaBasicDataPs->uuid,$dist_code, $subdiv_code, $cir_code,
								$mouza_pargona_code, $lot_no, $vill_townprt_code_ps);
						}

						foreach ($tables as $table)
						{
							$where = [
								'dist_code' => $dist_code,
								'subdiv_code' => $subdiv_code,
								'lot_no' => $lot_no,
							];
							if ($table['is_cir_code']) {
								$where['cir_code'] = $cir_code;
							}
							if ($table['is_circle_code']) {
								$where['circle_code'] = $cir_code;
							}
							if ($table['is_mp_code']) {
								$where['mp_code'] = $mouza_pargona_code;
							}
							if ($table['is_mouza_pargona_code']) {
								$where['mouza_pargona_code'] = $mouza_pargona_code;
							}
							if ($table['is_mouza_code']) {
								$where['mouza_code'] = $mouza_pargona_code;
							}
							if ($table['is_vill_townprt_code']) {
								$where['vill_townprt_code'] = $vill_townprt_code_ts;
							}
							if ($table['is_vill_code']) {
								$where['vill_code'] = $vill_townprt_code_ts;
							}
							if ($table['is_vt_code']) {
								$where['vt_code'] = $vill_townprt_code_ts;
							}
							if ($table['is_vill_town_code']) {
								$where['vill_town_code'] = $vill_townprt_code_ts;
							}
							if($table['is_dag_no'] == TRUE && $table['is_patta_no'] == TRUE)
							{
								$where['dag_no'] = $chitha_basic->dag_no;
								$where['patta_no'] = $disChBasic->patta_no;
								$datas = $this->db->get_where($table['table'], $where)->result_array();
								foreach ($datas as $data)
								{
									if (isset($data['id'])) {
										unset($data['id']);
									}
									if ($table['is_vill_townprt_code']) {
										$data['vill_townprt_code'] = $vill_townprt_code_ps;
									}
									if ($table['is_vill_code']) {
										$data['vill_code'] = $vill_townprt_code_ps;
									}
									if ($table['is_vt_code']) {
										$data['vt_code'] = $vill_townprt_code_ps;
									}
									if ($table['is_vill_town_code']) {
										$data['vill_town_code'] = $vill_townprt_code_ps;
									}
									if ($table['is_uuid'] == TRUE) {
										$data['uuid'] = $chithaBasicDataPs->uuid;
									}
									$data['patta_no'] = $max_patta_no;
									$data['dag_no'] = $max_dag;
									$this->db->insert($table['table'],$data);
								}
							}
							else if($table['is_dag_no'] == TRUE && $table['is_patta_no'] == FALSE)
							{
								$where['dag_no'] = $chitha_basic->dag_no;
								$datas = $this->db->get_where($table['table'], $where)->result_array();
								foreach ($datas as $data)
								{
									if (isset($data['id'])) {
										unset($data['id']);
									}
									if ($table['is_vill_townprt_code']) {
										$data['vill_townprt_code'] = $vill_townprt_code_ps;
									}
									if ($table['is_vill_code']) {
										$data['vill_code'] = $vill_townprt_code_ps;
									}
									if ($table['is_vt_code']) {
										$data['vt_code'] = $vill_townprt_code_ps;
									}
									if ($table['is_vill_town_code']) {
										$data['vill_town_code'] = $vill_townprt_code_ps;
									}
									if ($table['is_uuid'] == TRUE) {
										$data['uuid'] = $chithaBasicDataPs->uuid;
									}
									$data['dag_no'] = $max_dag;
									$this->db->insert($table['table'],$data);
								}
							}
						}
						$max_dag++;
					}

					$ChithaPattadars = $this->ChithaPattadarsTs($dist_code, $subdiv_code,
						$cir_code, $mouza_pargona_code, $lot_no,
						$vill_townprt_code_ts,$disChBasic->patta_no,$disChBasic->patta_type_code);

					foreach ($ChithaPattadars as $cithapattadar)
					{
						$insertIntoChithaPattadarPs = $this->insertIntoChithaPattadarPs($cithapattadar,$max_patta_no,$chithaBasicDataPs->uuid,$dist_code, $subdiv_code, $cir_code,
							$mouza_pargona_code, $lot_no, $vill_townprt_code_ps);
					}
					foreach ($tables as $table) {
						$where = [
							'dist_code' => $dist_code,
							'subdiv_code' => $subdiv_code,
							'lot_no' => $lot_no,
						];
						if ($table['is_cir_code']) {
							$where['cir_code'] = $cir_code;
						}
						if ($table['is_circle_code']) {
							$where['circle_code'] = $cir_code;
						}
						if ($table['is_mp_code']) {
							$where['mp_code'] = $mouza_pargona_code;
						}
						if ($table['is_mouza_pargona_code']) {
							$where['mouza_pargona_code'] = $mouza_pargona_code;
						}
						if ($table['is_mouza_code']) {
							$where['mouza_code'] = $mouza_pargona_code;
						}
						if ($table['is_vill_townprt_code']) {
							$where['vill_townprt_code'] = $vill_townprt_code_ts;
						}
						if ($table['is_vill_code']) {
							$where['vill_code'] = $vill_townprt_code_ts;
						}
						if ($table['is_vt_code']) {
							$where['vt_code'] = $vill_townprt_code_ts;
						}
						if ($table['is_vill_town_code']) {
							$where['vill_town_code'] = $vill_townprt_code_ts;
						}

						if ($table['is_dag_no'] == FALSE && $table['is_patta_no'] == TRUE) {
							$where['patta_no'] = $disChBasic->patta_no;
							$datas = $this->db->get_where($table['table'], $where)->result_array();
							foreach ($datas as $data) {
								if (isset($data['id'])) {
									unset($data['id']);
								}
								if ($table['is_vill_townprt_code']) {
									$data['vill_townprt_code'] = $vill_townprt_code_ps;
								}
								if ($table['is_vill_code']) {
									$data['vill_code'] = $vill_townprt_code_ps;
								}
								if ($table['is_vt_code']) {
									$data['vt_code'] = $vill_townprt_code_ps;
								}
								if ($table['is_vill_town_code']) {
									$data['vill_town_code'] = $vill_townprt_code_ps;
								}
								$data['patta_no'] = $max_patta_no;
								if ($table['is_uuid'] == TRUE) {
									$data['uuid'] = $chithaBasicDataPs->uuid;
								}
								$this->db->insert($table['table'], $data);
							}
						}
					}
					$max_patta_no++;
				}

				foreach ($tables as $table) {
					$where = [
						'dist_code' => $dist_code,
						'subdiv_code' => $subdiv_code,
						'lot_no' => $lot_no,
					];
					if ($table['is_cir_code']) {
						$where['cir_code'] = $cir_code;
					}
					if ($table['is_circle_code']) {
						$where['circle_code'] = $cir_code;
					}
					if ($table['is_mp_code']) {
						$where['mp_code'] = $mouza_pargona_code;
					}
					if ($table['is_mouza_pargona_code']) {
						$where['mouza_pargona_code'] = $mouza_pargona_code;
					}
					if ($table['is_mouza_code']) {
						$where['mouza_code'] = $mouza_pargona_code;
					}
					if ($table['is_vill_townprt_code']) {
						$where['vill_townprt_code'] = $vill_townprt_code_ts;
					}
					if ($table['is_vill_code']) {
						$where['vill_code'] = $vill_townprt_code_ts;
					}
					if ($table['is_vt_code']) {
						$where['vt_code'] = $vill_townprt_code_ts;
					}
					if ($table['is_vill_town_code']) {
						$where['vill_town_code'] = $vill_townprt_code_ts;
					}
					if ($table['is_dag_no'] == FALSE && $table['is_patta_no'] == FALSE) {
						$datas = $this->db->get_where($table['table'], $where)->result_array();
						foreach ($datas as $data) {
							if ($table['is_vill_townprt_code']) {
								$data['vill_townprt_code'] = $vill_townprt_code_ps;
							}
							if ($table['is_vill_code']) {
								$data['vill_code'] = $vill_townprt_code_ps;
							}
							if ($table['is_vt_code']) {
								$data['vt_code'] = $vill_townprt_code_ps;
							}
							if ($table['is_vill_town_code']) {
								$data['vill_town_code'] = $vill_townprt_code_ps;
							}
							if ($table['is_uuid'] == TRUE) {
								$data['uuid'] = $chithaBasicDataPs->uuid;
							}
							$this->db->insert($table['table'], $data);
						}
					}
				}

				if ($this->db->trans_status() === false) {
					$this->db->trans_rollback();
					log_message("error", '#NC0010 Unable to complete ' . json_encode('#NC0010 Unable to insert data.'));
					$array = array(
						'error' => true,
						'msg' => '#NC0010 Unable to complete.',
					);
					echo json_encode($array);
				}else {
					$this->db->trans_commit();

					$array = array(
						'error' => false,
						'msg' => null,
					);
					echo json_encode($array);
				}
				} catch (Exception $e) {
					log_message("error", '#NC0011 Unable to complete ' . json_encode('#NC0011: Exception error. Unable to complete'));
					$array = array(
						'error' => true,
						'msg' => '#NC0011: Exception error. Unable to complete',
					);
					echo json_encode($array);
			}
		}
	}

	public function checkAlreadyPorted($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code_ts)
	{
		$this->db->select("count(*)");
		$this->db->where([
			'dist_code' => $dist_code,
			'subdiv_code' => $subdiv_code,
			'cir_code' => $cir_code,
			'mouza_pargona_code' => $mouza_pargona_code,
			'lot_no' => $lot_no,
			'vill_townprt_code_ts' => $vill_townprt_code_ts,
		]);
		$query = $this->db->get('tspsmerge');

		if ($query->num_rows() > 0) {
			$result = $query->row();
			return $result->count;
		} else {
			return 0;
		}
	}

	public function distinctChithaBasicTs($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code_ts)
	{
		$this->db->distinct();  // Set the distinct flag
		$this->db->select('patta_no, patta_type_code');  // Select distinct columns
		$this->db->where([
			'dist_code' => $dist_code,
			'subdiv_code' => $subdiv_code,
			'cir_code' => $cir_code,
			'mouza_pargona_code' => $mouza_pargona_code,
			'lot_no' => $lot_no,
			'vill_townprt_code' => $vill_townprt_code_ts,
		]);

		$query3 = $this->db->get('chitha_basic');

		if ($query3->num_rows() > 0) {
			return $query3->result();
		}
	}

	public function chithaBasicDataPs($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code_ps)
	{
		$this->db->select('*');
		$this->db->where([
			'dist_code' => $dist_code,
			'subdiv_code' => $subdiv_code,
			'cir_code' => $cir_code,
			'mouza_pargona_code' => $mouza_pargona_code,
			'lot_no' => $lot_no,
			'vill_townprt_code' => $vill_townprt_code_ps,
		]);
		$query = $this->db->get('chitha_basic');

		if ($query->num_rows() > 0) {
			return $query->row();
		}
	}

	public function chihaBasicTs($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code_ts)
	{
		$this->db->select('*');
		$this->db->where([
			'dist_code' => $dist_code,
			'subdiv_code' => $subdiv_code,
			'cir_code' => $cir_code,
			'mouza_pargona_code' => $mouza_pargona_code,
			'lot_no' => $lot_no,
			'vill_townprt_code' => $vill_townprt_code_ts,
		]);
		$query = $this->db->get('chitha_basic');

		if ($query->num_rows() > 0) {
			return $query->result();
		}
	}

	public function maxDag($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code_ps)
	{
		$this->db->select("CAST(MAX(CAST(dag_no AS INTEGER)) AS VARCHAR) AS dag_no");
		$this->db->where([
			'dist_code' => $dist_code,
			'subdiv_code' => $subdiv_code,
			'cir_code' => $cir_code,
			'mouza_pargona_code' => $mouza_pargona_code,
			'lot_no' => $lot_no,
			'vill_townprt_code' => $vill_townprt_code_ps,
		]);
		$query = $this->db->get('chitha_basic');

		if ($query->num_rows() > 0) {
			$result = $query->row();
			return (int)$result->dag_no + 1;
		} else {
			return 1;
		}
	}

	public function maxPattaNo($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code_ps)
	{
		$this->db->select("CAST(MAX(CAST(patta_no AS INTEGER)) AS VARCHAR) AS patta_no");
		$this->db->where([
			'dist_code' => $dist_code,
			'subdiv_code' => $subdiv_code,
			'cir_code' => $cir_code,
			'mouza_pargona_code' => $mouza_pargona_code,
			'lot_no' => $lot_no,
			'vill_townprt_code' => $vill_townprt_code_ps,
		]);
		$query = $this->db->get('chitha_pattadar');

		if ($query->num_rows() > 0) {
			$result = $query->row();
			return (int)$result->patta_no + 1;
		} else {
			return 1;
		}
	}

	public function ChithaPattadarsTs($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code_ts,$patta_no,$patta_type_code)
	{
		$this->db->select('*');
		$this->db->where([
			'dist_code' => $dist_code,
			'subdiv_code' => $subdiv_code,
			'cir_code' => $cir_code,
			'mouza_pargona_code' => $mouza_pargona_code,
			'lot_no' => $lot_no,
			'vill_townprt_code' => $vill_townprt_code_ts,
			'patta_no' => $patta_no,
			'patta_type_code' => $patta_type_code
		]);

		$query3 = $this->db->get('chitha_pattadar');

		if ($query3->num_rows() > 0) {
			return $query3->result();
		}else{
			return [];
		}
	}

	public function chihaDagPattdarTs($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no, $vill_townprt_code_ts,$patta_no,$patta_type_code,$dag_no)
	{
		$this->db->select('*');
		$this->db->where([
			'dist_code' => $dist_code,
			'subdiv_code' => $subdiv_code,
			'cir_code' => $cir_code,
			'mouza_pargona_code' => $mouza_pargona_code,
			'lot_no' => $lot_no,
			'vill_townprt_code' => $vill_townprt_code_ts,
			'patta_no' => $patta_no,
			'patta_type_code' => $patta_type_code,
			'dag_no' => $dag_no,
		]);

		$query3 = $this->db->get('chitha_dag_pattadar');

		if ($query3->num_rows() > 0) {
			return $query3->result();
		}else{
			return [];
		}
	}

	public function insertIntoChithaBasicPs($chitha_basic,$dag_no,$patta_no,$uuid,$dist_code, $subdiv_code, $cir_code,
											$mouza_pargona_code, $lot_no, $vill_townprt_code_ps)
	{
		return $this->db->insert('chitha_basic',  [
			'dist_code' => $dist_code,
			'subdiv_code' => $subdiv_code,
			'cir_code' => $cir_code,
			'mouza_pargona_code' => $mouza_pargona_code,
			'lot_no' => $lot_no,
			'vill_townprt_code' => $vill_townprt_code_ps,
			'old_dag_no' => trim($chitha_basic->dag_no),
			'dag_no' => trim($dag_no),
			'dag_no_int' => trim($dag_no) * 100,
			'dag_area_b' => $chitha_basic->dag_area_b,
			'dag_area_k' => $chitha_basic->dag_area_k,
			'dag_area_lc' => $chitha_basic->dag_area_lc,
			'dag_area_g' => $chitha_basic->dag_area_g,
			'dag_area_kr' => $chitha_basic->dag_area_kr,
			'dag_area_are' => $chitha_basic->dag_area_are,
			'dag_revenue' => $chitha_basic->dag_revenue,
			'dag_local_tax' => $chitha_basic->dag_local_tax,
			'patta_type_code' => $chitha_basic->patta_type_code,
			'patta_no' => $patta_no,
			'land_class_code' => $chitha_basic->land_class_code,
			'dag_no_map' => $chitha_basic->dag_no_map,
			'dag_n_desc' => $chitha_basic->dag_n_desc,
			'dag_s_desc' => $chitha_basic->dag_s_desc,
			'dag_e_desc' => $chitha_basic->dag_e_desc,
			'dag_w_desc' => $chitha_basic->dag_w_desc,
			'dag_n_dag_no' => $chitha_basic->dag_n_dag_no,
			'dag_s_dag_no' => $chitha_basic->dag_s_dag_no,
			'dag_e_dag_no' => $chitha_basic->dag_e_dag_no,
			'dag_w_dag_no' => $chitha_basic->dag_w_dag_no,
			'dag_nlrg_no' => $chitha_basic->dag_nlrg_no,
			'dp_flag_yn' => $chitha_basic->dp_flag_yn,
			'user_code' => $chitha_basic->user_code,
			'date_entry' => $chitha_basic->date_entry,
			'operation' => $chitha_basic->operation,
			'jama_yn' => $chitha_basic->jama_yn,
			'status' => $chitha_basic->status,
			'old_patta_no' => $chitha_basic->patta_no,
			'dag_name' => $chitha_basic->dag_name,
			'dag_dept_name' => $chitha_basic->dag_dept_name,
			'dag_status' => $chitha_basic->dag_status,
			'uuid' => $uuid,
			'police_station' =>  $chitha_basic->police_station,
			'block_code' =>  $chitha_basic->block_code,
			'gp_code' =>  $chitha_basic->gp_code,
			'revenue_paid_upto' =>  $chitha_basic->revenue_paid_upto,
			'zonal_value' =>  $chitha_basic->zonal_value,
			'category_id' =>  $chitha_basic->category_id,
		]);
	}

	public function insertIntoChithaDagPattadarPs($chitha_dag_pattadar,$dag_no,$patta_no,$uuid,$dist_code, $subdiv_code, $cir_code,
												  $mouza_pargona_code, $lot_no, $vill_townprt_code_ps)
	{
		return $this->db->insert('chitha_dag_pattadar',  [
			'dist_code' => $dist_code,
			'subdiv_code' => $subdiv_code,
			'cir_code' => $cir_code,
			'mouza_pargona_code' => $mouza_pargona_code,
			'lot_no' => $lot_no,
			'vill_townprt_code' => $vill_townprt_code_ps,
			'dag_no' => trim($dag_no),
			'pdar_id' => $chitha_dag_pattadar->pdar_id,
			'patta_no' => $patta_no,
			'patta_type_code' => $chitha_dag_pattadar->patta_type_code,
			'dag_por_b' => $chitha_dag_pattadar->dag_por_b,
			'dag_por_k' => $chitha_dag_pattadar->dag_por_k,
			'dag_por_lc' => $chitha_dag_pattadar->dag_por_lc,
			'dag_por_g' => $chitha_dag_pattadar->dag_por_g,
			'dag_por_kr' => $chitha_dag_pattadar->dag_por_kr,
			'pdar_land_n' => $chitha_dag_pattadar->pdar_land_n,
			'pdar_land_s' => $chitha_dag_pattadar->pdar_land_s,
			'pdar_land_e' => $chitha_dag_pattadar->pdar_land_e,
			'pdar_land_w' => $chitha_dag_pattadar->pdar_land_w,
			'pdar_land_map' => $chitha_dag_pattadar->pdar_land_map,
			'pdar_land_acre' => $chitha_dag_pattadar->pdar_land_acre,
			'pdar_land_revenue' => $chitha_dag_pattadar->pdar_land_revenue,
			'pdar_land_localtax' => $chitha_dag_pattadar->pdar_land_localtax,
			'user_code' => $chitha_dag_pattadar->user_code,
			'date_entry' => $chitha_dag_pattadar->date_entry,
			'operation' => $chitha_dag_pattadar->operation,
			'p_flag' => $chitha_dag_pattadar->p_flag,
			'jama_yn' => $chitha_dag_pattadar->jama_yn,
			'not_consistent' => $chitha_dag_pattadar->not_consistent,
			'uuid' => $uuid,
		]);
	}

	public function insertIntoChithaPattadarPs($chitha_pattadar,$patta_no,$uuid,$dist_code, $subdiv_code, $cir_code,
											   $mouza_pargona_code, $lot_no, $vill_townprt_code_ps)
	{
		return $this->db->insert('chitha_pattadar',  [
			'dist_code' => $dist_code,
			'subdiv_code' => $subdiv_code,
			'cir_code' => $cir_code,
			'mouza_pargona_code' => $mouza_pargona_code,
			'lot_no' => $lot_no,
			'vill_townprt_code' => $vill_townprt_code_ps,
			'pdar_id' => $chitha_pattadar->pdar_id,
			'patta_no' => $patta_no,
			'patta_type_code' => $chitha_pattadar->patta_type_code,
			'pdar_name' => $chitha_pattadar->pdar_name,
			'pdar_father' => $chitha_pattadar->pdar_father,
			'pdar_add1' => $chitha_pattadar->pdar_add1,
			'pdar_add2' => $chitha_pattadar->pdar_add2,
			'pdar_add3' => $chitha_pattadar->pdar_add3,
			'pdar_pan_no' => $chitha_pattadar->pdar_pan_no,
			'pdar_citizen_no' => $chitha_pattadar->pdar_citizen_no,
			'pdar_thumb_imp' => $chitha_pattadar->pdar_thumb_imp,
			'pdar_photo' => $chitha_pattadar->pdar_photo,
			'user_code' => $chitha_pattadar->user_code,
			'date_entry' => $chitha_pattadar->date_entry,
			'operation' => $chitha_pattadar->operation,
			'jama_yn' => $chitha_pattadar->jama_yn,
			'pdar_guard_reln' => $chitha_pattadar->pdar_guard_reln,
			'f1_case_no' => $chitha_pattadar->f1_case_no,
			'f2_case_no' => $chitha_pattadar->f2_case_no,
			'o1_case_no' => $chitha_pattadar->o1_case_no,
			'o2_case_no' => $chitha_pattadar->o2_case_no,
			'new_pdar_name' => $chitha_pattadar->new_pdar_name,
			'pdar_gender' => $chitha_pattadar->pdar_gender,
			'pdar_minor_yn' => $chitha_pattadar->pdar_minor_yn,
			'pdar_minor_dob' => $chitha_pattadar->pdar_minor_dob,
			'pdar_mother' => $chitha_pattadar->pdar_mother,
			'pdar_aadharno' => $chitha_pattadar->pdar_aadharno,
			'pdar_mobile' => $chitha_pattadar->pdar_mobile,
			'pdar_nrcno' => $chitha_pattadar->pdar_nrcno,
			'pdar_name_eng' => $chitha_pattadar->pdar_name_eng,
			'pdar_guard_eng' => $chitha_pattadar->pdar_guard_eng,
			'uuid' => $uuid,
		]);
	}

	public function insertIntoTsPsMerge($dist_code, $subdiv_code, $cir_code,
										$mouza_pargona_code, $lot_no,
										$vill_townprt_code_ps,$dag_no,
										$patta_no,$uuid,$chitha_basic)
	{
		return $this->db->insert('tspsmerge',  [
			'dist_code' => $dist_code,
			'subdiv_code' => $subdiv_code,
			'cir_code' => $cir_code,
			'mouza_pargona_code' => $mouza_pargona_code,
			'lot_no' => $lot_no,
			'vill_townprt_code_ts' => $chitha_basic->vill_townprt_code,
			'vill_townprt_code_ps' => $vill_townprt_code_ps,
			'dag_no_ts' => trim($chitha_basic->dag_no),
			'dag_no_ps' => trim($dag_no),
			'patta_type_code' => $chitha_basic->patta_type_code,
			'patta_no_ts' => $chitha_basic->patta_no,
			'patta_no_ps' => $patta_no,
			'uuid_ts' => $chitha_basic->uuid,
			'uuid_ps' => $uuid,
			'user_code' => $this->session->userdata('user_code'),
			'created_at' => date('Y-m-d H:i:s'),
			'json_ts' => json_encode($chitha_basic),
		]);
	}

	public function portAllTables()
	{
		$this->dataswitch();
		$tables = $this->PortingModel->tablesUptoVillageForVillMerge();
		return $tables;
		foreach ($tables as $table)
		{
			echo '<pre>';
			var_dump($table);
		}
	}
}
