<?php

class PartDagModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

    public function whetherDharitreeMerged($dist, $subdiv, $cir, $mouza, $lot, $vill, $dag_no) {
        $check = $this->db->query('SELECT * FROM chitha_basic WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND dag_no=?', [$dist, $subdiv, $cir, $mouza, $lot, $vill, $dag_no])->row();
        if(empty($check)) {
            return [
                'status' => 'n',
                'msg' => 'Please sync with the dharitree data before creating Part Dag!'
            ];
        }

        return [
            'status' => 'y',
            'msg' => 'Already synced with the dharitree dag!'
        ];
    }

    public function alphaDagParams($dist, $subdiv, $cir, $mouza, $lot, $vill, $split_dag) {
        $isAlphanumeric = $this->utilityclass->isAlphanumeric($split_dag);
        if($isAlphanumeric) {
            $alpha_dag = 1;
            $ascii = '';
            foreach (str_split($split_dag) as $char) {
                $isCharAlpha = $this->utilityclass->isAlphanumeric($char);
                if(!$isCharAlpha && ctype_digit($char)) {
                    // $ascii .= ord($char);
                    $ascii .= $char;
                }
            }
            if($ascii == '') {
                $asc = 0;
                foreach (str_split($split_dag) as $char) {
                    $isCharAlpha = $this->utilityclass->isAlphanumeric($char);
                    if($isCharAlpha || !ctype_digit($char)) {
                        $asc += ord($char);
                    }
                }
                $dag_int = $asc;
            }
            else {
                $dag_int = $ascii;
            }
            // $strlength = strlen($ascii);
            // if($strlength > 6) {
            //     $dag_int = substr($ascii, 0, 6);
            // }
            $dag_no_int = $dag_int * 100;
            $available = 0;
            while($available < 1) {
                $checkPartDag = $this->existDagNoInt($dist, $subdiv, $cir, $mouza, $lot, $vill, $dag_no_int);// possible issue in case dags not synced with dharitree
                if(empty($checkPartDag)) {
                    $available++;
                }
                else {
                    $dag_no_int = $dag_no_int * 10;
                    $available = 0;
                }
            }
        }
        else {
            $alpha_dag = 0;
            $dag_no_int = $split_dag * 100;
        }

        return [
            'status' => 'y',
            'msg' => 'Data Retrieved!',
            'data' => [
                'alpha_dag' => $alpha_dag,
                'dag_no_int' => $dag_no_int
            ]
        ];
    }

    public function existDagNoInt($dist, $subdiv, $cir, $mouza, $lot, $vill, $dag_no_int) {
        return $this->db->query("SELECT dag_no FROM chitha_basic WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND dag_no_int=?", [$dist, $subdiv, $cir, $mouza, $lot, $vill, $dag_no_int])->row();
    }

    public function getTotalDagArea($dist, $subdiv, $cir, $mouza, $lot, $vill, $dag_no) {
        $dag = $this->db->query("SELECT dag_area_b, dag_area_k, dag_area_lc, dag_area_g FROM chitha_basic WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND dag_no=?", [$dist, $subdiv, $cir, $mouza, $lot, $vill, $dag_no])->row();

        if(empty($dag)) {
            return false;
        }

        if(in_array($dist, BARAK_VALLEY)) {
            $totalDagArea = $dag->dag_area_b * 6400 + $dag->dag_area_k * 320 + $dag->dag_area_lc * 20 + $dag->dag_area_g;
        }
        else {
            $totalDagArea = $dag->dag_area_b * 100 + $dag->dag_area_k * 20 + $dag->dag_area_lc;
        }

        return $totalDagArea;
    }

    public function checkExistingDag($dist, $subdiv, $cir, $mouza, $lot, $vill, $split_dag, $bhunaksha_survey_no = null) {

        //check if bhunaksha_survey_no already used in that village
        $checkSplitted = $this->db->query("SELECT survey_no,dag_no FROM chitha_basic_splitted_dags WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND bhunaksha_survey_no=?", [$dist, $subdiv, $cir, $mouza, $lot, $vill, $bhunaksha_survey_no])->result();
        if(!empty($checkSplitted)) {
            return [
                'status' => 'n',
                'msg' => 'Bhunaksha Survey No Already used for dag no '. $checkSplitted[0]->dag_no.' and part dag no '.$checkSplitted[0]->survey_no.' of this village',
            ];
        }

        //check in chitha_basic_splitted_dags
        $checkSplitted = $this->db->query("SELECT dag_no FROM chitha_basic_splitted_dags WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND (dag_no=? OR survey_no=?)", [$dist, $subdiv, $cir, $mouza, $lot, $vill, $split_dag, $split_dag])->result();
        if(!empty($checkSplitted)) {
            return [
                'status' => 'n',
                'msg' => 'New Splitted Dag Already available',
            ];
        }

        //check in chitha_basic
        $checkSplittedBasic = $this->db->query("SELECT dag_no FROM chitha_basic WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND dag_no=?", [$dist, $subdiv, $cir, $mouza, $lot, $vill, $split_dag])->result();
        if(!empty($checkSplittedBasic)) {
            return [
                'status' => 'n',
                'msg' => 'New Splitted Dag Already available',
            ];
        }

        return [
            'status' => 'y',
            'msg' => 'Splitted Dag Validated!'
        ];
    }

    public function getChithaDagDetails($dist, $subdiv, $cir, $mouza, $lot, $vill, $dag_no) {
        return $this->db->query("SELECT * FROM chitha_basic WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND dag_no=?", [$dist, $subdiv, $cir, $mouza, $lot, $vill, $dag_no])->row();
    }

    public function partDagTotalArea($dist, $subdiv, $cir, $mouza, $lot, $vill, $dag_no) {
        $partDags = $this->db->query("SELECT survey_no, dag_area_b, dag_area_k, dag_area_lc, dag_area_g FROM chitha_basic_splitted_dags WHERE dist_code=? AND subdiv_code=? AND cir_code=? AND mouza_pargona_code=? AND lot_no=? AND vill_townprt_code=? AND dag_no=?", [$dist, $subdiv, $cir, $mouza, $lot, $vill, $dag_no])->result();

        $partDagTotalArea = 0;
        if(!empty($partDags)) {
            if(in_array($dist, BARAK_VALLEY)) {
                foreach ($partDags as $partDag) {
                    $partDagTotalArea += $partDag->dag_area_b * 6400 + $partDag->dag_area_k * 320 + $partDag->dag_area_lc * 20 + $partDag->dag_area_g;
                }
            }
            else {
                foreach ($partDags as $partDag) {
                    $partDagTotalArea += $partDag->dag_area_b * 100 + $partDag->dag_area_k * 20 + $partDag->dag_area_lc;
                }
            }
        }

        return $partDagTotalArea;
    }


}