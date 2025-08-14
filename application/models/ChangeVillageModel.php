<?php
class ChangeVillageModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('NcVillageModel');
    }

    public function checkVillName($loc_name,$locname_eng,$uuid)
    {
        $exist = $this->db->get_where('location', array('loc_name'=>$loc_name, 'uuid !='=>$uuid))->row();
        $existEng = $this->db->get_where('location', array('locname_eng'=>$locname_eng, 'uuid!='=>$uuid))->row();
        if($exist or $existEng){
            return true;
        }else{
            return false;
        }
    }
    public function insertChangeVillage($form_data)
    {
        $uuid = $form_data['uuid'];
        $vill = $this->db->query("select * from change_vill_name where uuid = '$uuid'")->row();
        $this->db->trans_start();
        if ($vill) {
            $this->updateChangeVillage($form_data, $vill);
        } else {

            $this->db->insert('change_vill_name', [
                'uuid' => $form_data['uuid'],
                'old_vill_name' => $form_data['old_vill_name'],
                'old_vill_name_eng' => $form_data['old_vill_name_eng'],
                'new_vill_name' => $form_data['new_vill_name'],
                'new_vill_name_eng' => $form_data['new_vill_name_eng'],
                'cu_user_code' => $form_data['cu_user_code'],
                'status' => $form_data['status'],
                'date_of_update_co' => date('Y-m-d H:i:s'),
                'dist_code' => $form_data['dist_code'],
                'subdiv_code' => $form_data['subdiv_code'],
                'cir_code' => $form_data['cir_code'],
                'co_verified' => "Y",
                'mouza_pargona_code' => $form_data['mouza_pargona_code'],
                'lot_no' => $form_data['lot_no'],
                'co_user_code' => $this->session->userdata('usercode'),
            ]);
        }
        $this->db->trans_complete();
        if ($this->db->trans_status()) {
            $nc_village = $this->db->query("SELECT * FROM nc_villages WHERE dist_code='".$form_data['dist_code']."' AND  subdiv_code='".$form_data['subdiv_code']."' AND  cir_code='".$form_data['cir_code']."' AND  mouza_pargona_code='".$form_data['mouza_pargona_code']."' AND  lot_no='".$form_data['lot_no']."' AND uuid='". $uuid ."' AND app_version='V2'")->row();

            $cur_user_code = $this->session->userdata('user_code');
            $cur_desig_code = $this->session->userdata('user_desig_code');
            $user_name = $cur_desig_code;
            if($cur_desig_code == 'CO'){
                $co_user = getCO($form_data['dist_code'], $cur_user_code);
                if(count($co_user)){
                    $user_name = $co_user['name'];
                }
            }else if($cur_desig_code == 'DC'){
                // Not using for DC
                $dc_user = getDC($form_data['dist_code'], $cur_user_code);
                if(count($dc_user)){
                    $user_name = $dc_user['name'];
                }
            }

            if(!empty($nc_village) && $nc_village->app_version == 'V2'){
                $url = API_LINK_ILRMS . "index.php/nc_village_v2/NcCommonController/insertNcVillageDetails";
                $method = 'POST';
                $update_data = [ 
                                    'proccess_type' => 'CHANGED_VILLAGE_NAME', 
                                    'dist_code' => $nc_village->dist_code,
                                    'subdiv_code' => $nc_village->subdiv_code,
                                    'cir_code' => $nc_village->cir_code,
                                    'mouza_pargona_code' => $nc_village->mouza_pargona_code,
                                    'lot_no' => $nc_village->lot_no,
                                    'vill_townprt_code' => $nc_village->vill_townprt_code,
                                    'uuid' => $nc_village->uuid,
                                    'only_for_log' => 'Y', 
                                    'pre_user' => $this->session->userdata('user_code'), 
                                    'cur_user' => $this->session->userdata('user_code'), 
                                    'pre_user_dig' => $this->session->userdata('user_desig_code'), 
                                    'cur_user_dig' => $this->session->userdata('user_desig_code'), 
                                    'remark' => $user_name . ' changed the village name from "' . $form_data['old_vill_name'] . ' (' . $form_data['old_vill_name_eng'] . ')" to "' . $form_data['new_vill_name'] . ' (' . $form_data['new_vill_name_eng'] . ')".'
                                ];

                $output = $this->NcVillageModel->callApiV2($url, $method, $update_data);
                $response = $output ? json_decode($output, true) : [];
                if($response['status'] != 1){
                    $this->db->trans_rollback();
                    return false;
                }
            }

            return true;
        } else {
            return false;
        }
    }
    public function updateChangeVillage($form_data)
    {
        $this->db->set([
            'cu_user_code' => "DC",
            'prev_user_code' => "CO",
            'new_vill_name' => $form_data['new_vill_name'],
            'new_vill_name_eng' => $form_data['new_vill_name_eng'],
            'status' => "P",
            'dc_verified' => "Y",
            'date_of_update_dc' => date('Y-m-d H:i:s'),
            'dc_user_code' => $this->session->userdata('usercode'),
        ]);
        $this->db->where('uuid', $form_data['uuid']);
        $this->db->where('dist_code', $form_data['dist_code']);
        $this->db->update('change_vill_name');
        $this->db->trans_complete();
        if ($this->db->trans_status()) {
            return true;
        } else {
            return false;
        }
    }
    public function approveChangeVillage($uuid, $dist_code, $user_code)
    {
        $vill = $this->db->query("select * from change_vill_name where uuid = '$uuid' and dist_code='$dist_code'")->row();
        $dataPost['api_key'] = "chitha_application";
        $dataPost['dist_code'] = $dist_code;
        $dataPost['loc_name'] = $vill->vill_name;
        $dataPost['uuid'] = $uuid;
        $dataPost['locname_eng'] = $vill->vill_name_eng;

        // $dataPost['api_key'] = "chitha_application";
        // $dataPost['dist_code'] = "07";
        // $dataPost['loc_name'] = $vill->new_vill_name;
        // $dataPost['uuid'] = "10000000004828";
        // $dataPost['locname_eng'] = $vill->new_vill_name_eng;
        $exist = $this->checkVillName( $dataPost['loc_name'],$dataPost['locname_eng'],$uuid);
        if(!$exist){
            $this->db->trans_start();
            $this->db->set([
                'cu_user_code' => "DLR",
                'prev_user_code' => "DC",
                'status' => "F",
                'date_of_update_dlr' => date('Y-m-d H:i:s'),
                'dlr_verified' => "Y",
                'dc_verified' => "Y",
                "dlr_user_code" => $user_code,
            ]);
            $this->db->where('uuid', $uuid);
            $this->db->where('dist_code', $dist_code);
            $this->db->update('change_vill_name');
            if ($this->db->affected_rows() > 0) {
                if(DHARITREE_NAME_CHANGE == 'y'){
                    $ch = curl_init(DHARITREE_LINK . "/index.php/ChangeVillageApiController/updateLocationFromChangeVillage");
                    $urL = DHARITREE_LINK . "/index.php/ChangeVillageApiController/updateLocationFromChangeVillage";
                    curl_setopt_array($ch, array(
                        CURLOPT_URL => $urL,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_SSL_VERIFYHOST => 2,
                        CURLOPT_POSTFIELDS => http_build_query($dataPost),
                        CURLOPT_VERBOSE => 1,
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/x-www-form-urlencoded',
                        ),
                    ));
    
                    $response = curl_exec($ch);
                    curl_close($ch);
                    $response_code = json_decode($response)->responseCode;
                    // return $error_msg;
                    if ($response_code == 200) {
                        //close
                        if ($vill) {
                            $this->db->set([
                                'loc_name' => $vill->new_vill_name,
                                'locname_eng' => $vill->new_vill_name_eng,
                            ]);
                            $this->db->where('uuid', $uuid);
                            $this->db->update('location');
                        }
                        $this->db->trans_complete();
                        if ($this->db->trans_status()) {
                            return true;
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                }else{
                    if ($vill) {
                        $this->db->set([
                            'loc_name' => $vill->new_vill_name,
                            'locname_eng' => $vill->new_vill_name_eng,
                        ]);
                        $this->db->where('uuid', $uuid);
                        $this->db->update('location');
                    }
                    $this->db->trans_complete();
                    if ($this->db->trans_status()) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }else {
                return false;
            }
        }else{
            return false;
        }  
    }
}
