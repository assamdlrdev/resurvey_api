

<?php
class DagModel extends CI_Model
{
    function getDags($postData = null)
    {

        $response = array();

        ## Read value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value

        $dag_del_dets    = $this->session->userdata('dag_del_dets');
        $dist_code   = $dag_del_dets['dist_code'];
        $subdiv_code = $dag_del_dets['subdiv_code'];
        $cir_code = $dag_del_dets['cir_code'];
        $mouza_pargona_code  = $dag_del_dets['mouza_pargona_code'];
        $lot_no      = $dag_del_dets['lot_no'];
        $vill_townprt_code   = $dag_del_dets['vill_townprt_code'];

        ## Search 
        $searchQuery = "";
        if ($searchValue != '') {
            $searchQuery = " (dag_no like '%" . $searchValue . "%' or patta_no like '%" . $searchValue . "%') ";
        }

        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $records = $this->db->get('chitha_basic')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        if ($searchQuery != '')
            $this->db->where($searchQuery);
        $records = $this->db->get('chitha_basic')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('dag_no,old_dag_no,patta_type_code,patta_no');
        if ($searchQuery != '') {
            $this->db->where($searchQuery);
        }
        $this->db->where('dist_code', $dist_code)
            ->where('subdiv_code', $subdiv_code)
            ->where('cir_code', $cir_code)
            ->where('mouza_pargona_code', $mouza_pargona_code)
            ->where('lot_no', $lot_no)
            ->where('vill_townprt_code', $vill_townprt_code)
            ->order_by('dag_no_int','ASC')
            ->join('patta_code','chitha_basic.patta_type_code = patta_code.type_code','left')
            ->select('patta_code.patta_type');
        // $this->db->order_by($columnName, $columnSortOrder);
        
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('chitha_basic')->result();

        $this->db->select('dag_no,old_dag_no,patta_type_code,patta_no');
        if ($searchQuery != '') {
            $this->db->where($searchQuery);
        }
        $this->db->where('dist_code', $dist_code)
            ->where('subdiv_code', $subdiv_code)
            ->where('cir_code', $cir_code)
            ->where('mouza_pargona_code', $mouza_pargona_code)
            ->where('lot_no', $lot_no)
            ->where('vill_townprt_code', $vill_townprt_code)
            ->order_by('dag_no_int','ASC')
            ->join('patta_code','chitha_basic.patta_type_code = patta_code.type_code','left')
            ->select('patta_code.patta_type');
        $count_data = $this->db->get('chitha_basic')->num_rows();
        $data = array();

        foreach ($records as $record) {

            $data[] = array(
                "dag_no" => $record->dag_no,
                "patta_type" => $record->patta_type,
                "patta_no" => $record->patta_no,
                "checkinput" => "<input value='$record->dag_no' name='selected_dags[]' type='checkbox'>",
                "pattadar_view_btn" => " <a href=".base_url()."index.php/dag/DagController/viewPattadars/".$record->dag_no." class='btn btn-sm btn-info'><i class='fa fa-eye'></i> Pattadars</a>",
            );
        }

        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" =>  $totalRecordwithFilter,
            "iTotalDisplayRecords" => $count_data,
            "aaData" => $data
        );

        return $response;
    }
    public function dagDetails($dag_no)
    {
        $dag_del_dets    = $this->session->userdata('dag_del_dets');
        $dist_code   = $dag_del_dets['dist_code'];
        $subdiv_code = $dag_del_dets['subdiv_code'];
        $cir_code = $dag_del_dets['cir_code'];
        $mouza_pargona_code  = $dag_del_dets['mouza_pargona_code'];
        $lot_no      = $dag_del_dets['lot_no'];
        $vill_townprt_code   = $dag_del_dets['vill_townprt_code'];

        $this->db->select('dag_no,old_dag_no,patta_type_code,patta_no');
        return $this->db->where('dist_code', $dist_code)
            ->where('subdiv_code', $subdiv_code)
            ->where('cir_code', $cir_code)
            ->where('mouza_pargona_code', $mouza_pargona_code)
            ->where('lot_no', $lot_no)
            ->where('vill_townprt_code', $vill_townprt_code)
            ->where('dag_no', $dag_no)
            ->join('patta_code','chitha_basic.patta_type_code = patta_code.type_code','left')
            ->select('patta_code.patta_type')
            ->get('chitha_basic')->row();
    }
}

?>