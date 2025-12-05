<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('getAllLm')) {
    function getAllLm($dist_code, $subdiv_code, $cir_code, $mouza_pargona_code, $lot_no)
    {
        $CI =& get_instance();

        $CI->load->model('NcVillageModel');

        $url = base_url("index.php/nc_village_v2/NcVillageApiV2Controller/get_users");
        $method = 'POST';
        $data['desig_code'] = 'LM';
        $data['dist_code'] = $dist_code;
        $data['subdiv_code'] = $subdiv_code;
        $data['cir_code'] = $cir_code;
        $data['mouza_pargona_code'] = $mouza_pargona_code;
        $data['lot_no'] = $lot_no;
        $output = $CI->NcVillageModel->callApiV2($url, $method, $data);

        if (!$output) {
            log_message("error", 'FAIL TO FETCH LM');
            return [];
        } else {
            $resp = json_decode($output, true);
            if ($resp['success']) {
                return $resp['users'];
            }
        }

        return [];
    }
}

if (!function_exists('getLm')) {
    function getLm($dist_code, $user_code)
    {
        $CI =& get_instance();

        $CI->load->model('NcVillageModel');

        $url = base_url("index.php/nc_village_v2/NcVillageApiV2Controller/get_user");
        $method = 'POST';
        $data['desig_code'] = 'LM';
        $data['dist_code'] = $dist_code;
        $data['user_code'] = $user_code;
        $output = $CI->NcVillageModel->callApiV2($url, $method, $data);

        if (!$output) {
            log_message("error", 'FAIL TO FETCH LM');
            return [];
        } else {
            $resp = json_decode($output, true);
            if ($resp['success']) {
                return $resp['user'];
            }
        }

        return [];
    }
}

if (!function_exists('getAllSk')) {
    function getAllSk($dist_code, $subdiv_code, $cir_code)
    {
        $CI =& get_instance();

        $CI->load->model('NcVillageModel');

        $url = base_url("index.php/nc_village_v2/NcVillageApiV2Controller/get_users");
        $method = 'POST';
        $data['desig_code'] = 'SK';
        $data['dist_code'] = $dist_code;
        $data['subdiv_code'] = $subdiv_code;
        $data['cir_code'] = $cir_code;
        $output = $CI->NcVillageModel->callApiV2($url, $method, $data);

        if (!$output) {
            log_message("error", 'FAIL TO FETCH ALL SK');
            return [];
        } else {
            $resp = json_decode($output, true);
            if ($resp['success']) {
                return $resp['users'];
            }
        }

        return [];
    }
}

if (!function_exists('getSk')) {
    /**Get SK */
    function getSK($dist_code, $user_code)
    {
        $CI =& get_instance();

        $CI->load->model('NcVillageModel');

        $url = base_url("index.php/nc_village_v2/NcVillageApiV2Controller/get_user");
        $method = 'POST';
        $data['desig_code'] = 'SK';
        $data['dist_code'] = $dist_code;
        $data['user_code'] = $user_code;
        $output = $CI->NcVillageModel->callApiV2($url, $method, $data);

        if (!$output) {
            log_message("error", 'FAIL TO FETCH SK');
            return [];
        } else {
            $resp = json_decode($output, true);
            if ($resp['success']) {
                return $resp['user'];
            }
        }

        return [];
    }
}

if (!function_exists('getAllCO')) {
    function getAllCO($dist_code, $subdiv_code, $cir_code)
    {
        $CI =& get_instance();

        $CI->load->model('NcVillageModel');

        $url = base_url("index.php/nc_village_v2/NcVillageApiV2Controller/get_users");
        $method = 'POST';
        $data['desig_code'] = 'CO';
        $data['dist_code'] = $dist_code;
        $data['subdiv_code'] = $subdiv_code;
        $data['cir_code'] = $cir_code;
        $output = $CI->NcVillageModel->callApiV2($url, $method, $data);

        if (!$output) {
            log_message("error", 'FAIL TO FETCH ALL CO');
            return [];
        } else {
            $resp = json_decode($output, true);
            if ($resp['success']) {
                return $resp['users'];
            }
        }

        return [];
    }
}

if (!function_exists('getCO')) {
    function getCO($dist_code, $user_code)
    {
        $CI =& get_instance();

        $CI->load->model('NcVillageModel');

        $url = base_url("index.php/nc_village_v2/NcVillageApiV2Controller/get_user");
        $method = 'POST';
        $data['desig_code'] = 'CO';
        $data['dist_code'] = $dist_code;
        $data['user_code'] = $user_code;
        $output = $CI->NcVillageModel->callApiV2($url, $method, $data);

        if (!$output) {
            log_message("error", 'FAIL TO FETCH CO');
            return [];
        } else {
            $resp = json_decode($output, true);
            if ($resp['success']) {
                return $resp['user'];
            }
        }

        return [];
    }
}

if (!function_exists('getAllDC')) {
    function getAllDC($dist_code)
    {
        $CI =& get_instance();

        $CI->load->model('NcVillageModel');

        $url = base_url("index.php/nc_village_v2/NcVillageApiV2Controller/get_users");
        $method = 'POST';
        $data['desig_code'] = 'DC';
        $data['dist_code'] = $dist_code;
        $output = $CI->NcVillageModel->callApiV2($url, $method, $data);

        if (!$output) {
            log_message("error", 'FAIL TO FETCH ALL DC');
            return [];
        } else {
            $resp = json_decode($output, true);
            if ($resp['success']) {
                return $resp['users'];
            }
        }

        return [];
    }
}

if (!function_exists('getDC')) {
    function getDC($dist_code, $user_code)
    {
        $CI =& get_instance();

        $CI->load->model('NcVillageModel');

        $url = base_url("index.php/nc_village_v2/NcVillageApiV2Controller/get_user");
        $method = 'POST';
        $data['desig_code'] = 'DC';
        $data['dist_code'] = $dist_code;
        $data['user_code'] = $user_code;
        $output = $CI->NcVillageModel->callApiV2($url, $method, $data);

        if (!$output) {
            log_message("error", 'FAIL TO FETCH DC');
            return [];
        } else {
            $resp = json_decode($output, true);
            if ($resp['success']) {
                return $resp['user'];
            }
        }

        return [];
    }
}

if (!function_exists('getAllJDS')) {
    function getAllJDS()
    {
        $CI =& get_instance();

        $CI->load->model('NcVillageModel');

        $url = API_LINK_ILRMS . "index.php/nc_village_v2/NcCommonController/get_users";
        $method = 'POST';
        $data['user_desig'] = JDS;
        $output = $CI->NcVillageModel->callApiV2($url, $method, $data);

        if (!$output) {
            log_message("error", 'FAIL TO FETCH ALL CO');
            return [];
        } else {
            $resp = json_decode($output, true);
            if ($resp['success']) {
                return $resp['users'];
            }
        }

        return [];
    }

    function test($data, $exit = true)
    {
        echo "<pre>";
        var_dump($data);

        echo "<br>";
        $exit && exit('exit...');
    }

    function getClientIP()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    function currentDate()
    {
        return date('Y-m-d');
    }

    function currentYear()
    {
        return date('Y');
    }

    function currentDateTime()
    {
        return date('Y-m-d H:i:s');
    }


}