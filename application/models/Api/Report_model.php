<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report_model extends CI_Model
{

    // 2️⃣ Circles under a District
    public function getReportCircles($district)
    {
        $data = [];

        $circles_with_mouza_count_query = $this->db->query("
            select loc.loc_name as name, loc.locname_eng as name_eng,loc.dist_code,loc.subdiv_code,loc.cir_code, count(distinct_mouzas.*) as mouzas_count from location loc
            join location distinct_mouzas on loc.dist_code = distinct_mouzas.dist_code and loc.subdiv_code = distinct_mouzas.subdiv_code and loc.cir_code = distinct_mouzas.cir_code and distinct_mouzas.mouza_pargona_code != '00' and distinct_mouzas.lot_no = '00'
            where loc.dist_code = ? and loc.cir_code != '00' and loc.mouza_pargona_code = '00'
            group by loc.loc_name, loc.locname_eng, loc.dist_code, loc.subdiv_code, loc.cir_code
            order by loc.locname_eng
        ", [$district]);
        $circles_with_mouza_count =  $circles_with_mouza_count_query->result();
        foreach ($circles_with_mouza_count as $key => $circle) {
            $cbsd_count = $this->db->query("select count(*) as cnt from chitha_basic_splitted_dags where dist_code = ? and subdiv_code = ? and cir_code = ?", [$district, $circle->subdiv_code, $circle->cir_code])->row()->cnt;
            $data[] = [
                'dist_code'      => $circle->dist_code,
                'subdiv_code'   => $circle->subdiv_code,
                'cir_code'      => $circle->cir_code,
                'name'           => $circle->name,
                'name_eng'       => $circle->name_eng,
                'mouzas_count'  => (int) $circle->mouzas_count,
                'data_collection'     => (int) $cbsd_count
            ];
        }
        return $data;
    }

    // 3️⃣ Mouzas under a Circle
    public function getReportMouzas($district, $subdiv, $circle)
    {
        $data = [];

        $mouzas_with_lot_count_query = $this->db->query("
            select loc.loc_name as name, loc.locname_eng as name_eng, loc.dist_code, loc.subdiv_code, loc.cir_code, loc.mouza_pargona_code, count(distinct_lots.*) as lots_count from location loc
            join location distinct_lots on loc.dist_code = distinct_lots.dist_code and loc.subdiv_code = distinct_lots.subdiv_code and loc.cir_code = distinct_lots.cir_code and loc.mouza_pargona_code = distinct_lots.mouza_pargona_code and distinct_lots.lot_no !='00' and distinct_lots.vill_townprt_code = '00000'
            where loc.dist_code = ? and loc.subdiv_code = ? and loc.cir_code = ? and loc.mouza_pargona_code != '00' and loc.lot_no = '00'
            group by loc.loc_name, loc.locname_eng, loc.dist_code, loc.subdiv_code, loc.cir_code, loc.mouza_pargona_code
            order by loc.locname_eng
        ", [$district, $subdiv, $circle]);
        $mouzas_with_lot_count =  $mouzas_with_lot_count_query->result();
        foreach ($mouzas_with_lot_count as $key => $mouza) {
            $cbsd_count = $this->db->query("select count(*) as cnt from chitha_basic_splitted_dags where dist_code = ? and subdiv_code = ? and cir_code = ? and mouza_pargona_code = ?", [$district, $subdiv, $circle, $mouza->mouza_pargona_code])->row()->cnt;
            $data[] = [
                'dist_code'      => $mouza->dist_code,
                'subdiv_code'   => $mouza->subdiv_code,
                'cir_code'      => $mouza->cir_code,
                'mouza_pargona_code' => $mouza->mouza_pargona_code,
                'name'           => $mouza->name,
                'name_eng'       => $mouza->name_eng,
                'lots_count'    => (int) $mouza->lots_count,
                'data_collection'     => (int) $cbsd_count
            ];
        }
        return $data;
    }

    // 4️⃣ Lots under a Mouza
    public function getReportLots($district, $subdiv, $circle, $mouza)
    {
        $data = [];

        $lots_query = $this->db->query("
            select loc.loc_name as name, loc.locname_eng as name_eng, loc.dist_code, loc.subdiv_code, loc.cir_code, loc.mouza_pargona_code, loc.lot_no, count(distinct_villages.*) as villages_count from location loc
            join location distinct_villages on loc.dist_code = distinct_villages.dist_code and loc.subdiv_code = distinct_villages.subdiv_code and loc.cir_code = distinct_villages.cir_code and loc.mouza_pargona_code = distinct_villages.mouza_pargona_code and loc.lot_no = distinct_villages.lot_no and distinct_villages.vill_townprt_code != '00000'
            where loc.dist_code = ? and loc.subdiv_code = ? and loc.cir_code = ? and loc.mouza_pargona_code = ? and loc.lot_no != '00' and loc.vill_townprt_code = '00000'
            group by loc.loc_name, loc.locname_eng, loc.dist_code, loc.subdiv_code, loc.cir_code, loc.mouza_pargona_code, loc.lot_no
            order by loc.locname_eng
        ", [$district, $subdiv, $circle, $mouza]);
        $lots =  $lots_query->result();
        foreach ($lots as $key => $lot) {
            $cbsd_count = $this->db->query("select count(*) as cnt from chitha_basic_splitted_dags where dist_code = ? and subdiv_code = ? and cir_code = ? and mouza_pargona_code = ? and lot_no = ?", [$district, $subdiv, $circle, $mouza, $lot->lot_no])->row()->cnt;
            $data[] = [
                'dist_code'      => $lot->dist_code,
                'subdiv_code'   => $lot->subdiv_code,
                'cir_code'      => $lot->cir_code,
                'mouza_pargona_code' => $lot->mouza_pargona_code,
                'lot_no'        => $lot->lot_no,
                'name'           => $lot->name,
                'name_eng'       => $lot->name_eng,
                'villages_count' => (int) $lot->villages_count,
                'data_collection'     => (int) $cbsd_count
            ];
        }
        return $data;
    }

    // 5️⃣ Villages under a Lot
    public function getReportVillages($district, $subdiv, $circle, $mouza, $lot)
    {
        $data = [];

        $villages_query = $this->db->query("
            select loc.loc_name as name, loc.locname_eng as name_eng, loc.dist_code, loc.subdiv_code, loc.cir_code, loc.mouza_pargona_code, loc.lot_no, loc.vill_townprt_code from location loc
            where loc.dist_code = ? and loc.subdiv_code = ? and loc.cir_code = ? and loc.mouza_pargona_code = ? and loc.lot_no = ? and loc.vill_townprt_code != '00000'
            order by loc.locname_eng
        ", [$district, $subdiv, $circle, $mouza, $lot]);
        $villages =  $villages_query->result();
        foreach ($villages as $key => $village) {
            $cbsd_count = $this->db->query("select count(*) as cnt from chitha_basic_splitted_dags where dist_code = ? and subdiv_code = ? and cir_code = ? and mouza_pargona_code = ? and lot_no = ? and vill_townprt_code = ?", [$district, $subdiv, $circle, $mouza, $lot, $village->vill_townprt_code])->row()->cnt;
            $data[] = [
                'dist_code'      => $village->dist_code,
                'subdiv_code'   => $village->subdiv_code,
                'cir_code'      => $village->cir_code,
                'mouza_pargona_code' => $village->mouza_pargona_code,
                'lot_no'        => $village->lot_no,
                'vill_townprt_code' => $village->vill_townprt_code,
                'name'           => $village->name,
                'name_eng'       => $village->name_eng,
                'data_collection'     => (int) $cbsd_count
            ];
        }
        return $data;
    }

    // 6️⃣ DAG Report for a Village
    public function getDagReport($district, $subdiv, $circode, $mouzacode, $lotcode, $villcode)
    {
        $data = [];
        $b = 1337.803776;//1 bigha  = 1337.803776 sqm
        $k = 267.5607552;//1 katha = 267.5607552 sqm
        $l = 13.37803776;//1 lecha = 13.37803776 sqm
        $dags_query = $this->db->query("
            select * from chitha_basic where dist_code = ? and subdiv_code = ? and cir_code = ? and mouza_pargona_code = ? and lot_no = ? and vill_townprt_code = ?
            order by dag_no
        ", [$district, $subdiv, $circode, $mouzacode, $lotcode, $villcode]);
        $dags =  $dags_query->result();
        $total_dags_area = 0;
        $total_splitted_dags_area = 0;
        $total_part_dags_entered = 0;
        foreach ($dags as $key => $dag) {
            $splitted_dags_query = $this->db->query("
                select * from chitha_basic_splitted_dags where dist_code = ? and subdiv_code = ? and cir_code = ? and mouza_pargona_code = ? and lot_no = ? and vill_townprt_code = ? and dag_no = ?
                order by survey_no
            ", [$district, $subdiv, $circode, $mouzacode, $lotcode, $villcode, $dag->dag_no]);
            $splitted_dags =  $splitted_dags_query->result();
            $dag_area_sqm =  ($dag->dag_area_b * $b) + ($dag->dag_area_k * $k) + ($dag->dag_area_lc * $l);
            $splitted_dags_area_sqm = 0;

            foreach ($splitted_dags as $sdkey => $splitted_dag) {
                $splitted_dag_area_sqm =  ($splitted_dag->dag_area_b * $b) + ($splitted_dag->dag_area_k * $k) + ($splitted_dag->dag_area_lc * $l);
                $splitted_dags_area_sqm += $splitted_dag_area_sqm;
            }

            $total_dags_area += $dag_area_sqm;
            $total_splitted_dags_area += $splitted_dags_area_sqm;
            $total_part_dags_entered += count($splitted_dags);

            $data[] = [
                'dist_code'      => $dag->dist_code,
                'subdiv_code'   => $dag->subdiv_code,
                'cir_code'      => $dag->cir_code,
                'mouza_pargona_code' => $dag->mouza_pargona_code,
                'lot_no'        => $dag->lot_no,
                'vill_townprt_code' => $dag->vill_townprt_code,
                'dag_no'        => $dag->dag_no,
                'splitted_dags' => $splitted_dags,
                'dag_area_sqm' => $dag_area_sqm,
                'splitted_dags_area_sqm' => $splitted_dags_area_sqm
            ];
        }


        $url = "https://landhub.assam.gov.in/api/index.php/BhunakshaApiController/getDraftVillageGeoJson";
		$method = 'POST';
		$data2['location'] = $district.'_'.$subdiv.'_'.$circode.'_'.$mouzacode.'_'.$lotcode.'_'.$villcode;

		$map_geojon = callApiV3($url, $method, $data2);
        $map_geojon_decoded = json_decode($map_geojon);
        $map_geojson = $map_geojon_decoded->features ?? [];

        return [
            'dags' => $data,
            'total_dags_area_sqm' => $total_dags_area,
            'total_splitted_dags_area_sqm' => $total_splitted_dags_area,
            'total_part_dags_entered' => $total_part_dags_entered,
            'map_geojson' => $map_geojson
        ];
    }
}
