<?php
function callLandhubAPI($method, $url, $data)
{
    $curl = curl_init();
    $data['apikey'] = LANDHUB_APIKEY;
    curl_setopt_array($curl, array(
        CURLOPT_URL => LANDHUB_BASE_URL . $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_POSTFIELDS => http_build_query($data),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    if ($response) {
        return json_decode($response)->data;
    } else {
        return null;
    }
}

function callLandhubAPIForChithaCount($method, $url, $data)
{
    $curl = curl_init();
    $data['apikey'] = LANDHUB_APIKEY;
    curl_setopt_array($curl, array(
        CURLOPT_URL => LANDHUB_BASE_URL . $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_POSTFIELDS => http_build_query($data),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    if ($response) {
        return json_decode($response);
    } else {
        return null;
    }
}
/** API */
function callIlrmsApi($url, $method = 'GET', $data = null)
{
    $curl = curl_init();
    if ($method == 'POST') {
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_VERBOSE => 1,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
            ),
        ));
    } else {
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
            ),
        ));
    }

    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    if ($httpcode != 200) {
        $arr = (object) array(
            'data' => array(),
            'status_code' => 404,
        );
        return $arr;
    }
    if ($response) {
        return json_decode($response);
    } else {
        $arr = (object) array(
            'data' => array(),
            'status_code' => 404,
        );
        return $arr;
    }
}
function callDharApi($method, $url, $data)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => DHARITREE_LINK . $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_POSTFIELDS => http_build_query($data),
        CURLOPT_VERBOSE => 1,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded',
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    return json_decode($response, true);
}

function sendOtpToPhone($mobile_no, $otp)
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => SMS_API,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{
            "key"       : "login_otp",
            "variables" : "' . $otp . '",
            "mobilenos" : "' . $mobile_no . '" 
        }',
    ));
    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    if ($httpcode != 200) {
        return false;
    } else {
        return true;
    }
}


function translateAssToEng($text)
{
    // $url = 'https://api.mymemory.translated.net/get?q=' . urlencode($text) . '&langpair=as|en';
    $url = 'https://landhub.assam.gov.in/CDAC-EnhanceTransliterationAPI/Transliteration.aspx?itext=' . urlencode($text) . '&locale=as_in&transliteration=name&transRev=true';

    // Initialize cURL session
    $ch = curl_init();

    curl_setopt_array($ch, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8'
        ),
    ));
    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute cURL session
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        // return 'Error: ' . curl_error($ch);
        $err_msg = curl_error($ch);

        return [
            'success' => false,
            'message' => curl_error($ch)
        ];
    }

    // Close cURL session
    curl_close($ch);

    // Decode JSON response
    // $data = json_decode($response, true);

    // return $data;
    return [
        'success' => true,
        'message' => $response
    ];
}

function translateAssToEng_old($text)
{
    $url = 'https://api.mymemory.translated.net/get?q=' . urlencode($text) . '&langpair=as|en';

    // Initialize cURL session
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute cURL session
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        return 'Error: ' . curl_error($ch);
    }

    // Close cURL session
    curl_close($ch);

    // Decode JSON response
    $data = json_decode($response, true);

    // Extract and return translation
    if (isset($data['responseData']['translatedText'])) {
        return $data['responseData']['translatedText'];
    } else {
        return 'Translation not available';
    }
}

function translateEngToAss($text)
{
    $url = 'https://api.mymemory.translated.net/get?q=' . urlencode($text) . '&langpair=en|as';

    // Initialize cURL session
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute cURL session
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        return 'Error: ' . curl_error($ch);
    }

    // Close cURL session
    curl_close($ch);

    // Decode JSON response
    $data = json_decode($response, true);

    // Extract and return translation
    if (isset($data['responseData']['translatedText'])) {
        return $data['responseData']['translatedText'];
    } else {
        return 'Translation not available';
    }
}

if (!function_exists('response_json')) {
    function response_json($data = [], $responseCode = '200')
    {
        /**
         * Send reponse code = 403 for error and 200 for success
         */
        $ci = &get_instance();

        return $ci->output
            ->set_header("Content-Security-Policy: worker-src 'self' blob:; script-src 'self' blob:; style-src 'self' 'unsafe-inline';")
            ->set_status_header($responseCode)
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
}


function callLandhubAPI2($method, $url, $data)
{
    $curl = curl_init();
    $data['apikey'] = LANDHUB_APIKEY;
    $jsonData = json_encode($data);
    curl_setopt_array($curl, array(
        CURLOPT_URL => LANDHUB_BASE_URL . $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST =>  true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_POSTFIELDS => $jsonData,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    if ($response) {
        return json_decode($response);
    } else {
        return null;
    }
}

/** API */
function callIlrmsApi2($url, $method = 'GET', $data = null)
{

    $curl = curl_init();
    $jsonData = json_encode($data);
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST =>  true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_POSTFIELDS => $jsonData,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    return json_decode($response);

}

function callBhunakshaApiForProcessMap($data, $method = 'POST')
{
    $url = DXF_MAP_SHOW_BASE_URL . 'processMap';
    $jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHBpcmVzQXQiOiIyMDI2LTEyLTEyIiwibmFtZSI6IkNoaXRoYWVudHJ5IiwiaWF0IjoxNTE2MjM5MDIyfQ.qvoMqhKtveXsLhjGSOw54vI0ruFh9PIup8qAOhvMUSk';
    $curl = curl_init();
    $jsonData = json_encode($data);
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST =>  true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_POSTFIELDS => $jsonData,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData),
            'Authorization: Bearer ' . $jwt_token
        ),
    ));

    $response = curl_exec($curl);
    log_message('error', $response);
    $success = false;
    if(!curl_errno($curl)){
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if(in_array($http_code, [200, 201])){
            $success = true;
        }
    }
    curl_close($curl);
    return ['success' => $success, 'data' =>json_decode($response)];

}

function callBhunakshaApiForShpProcessMap($data, $method = 'POST')
{
    $url = DXF_MAP_SHOW_BASE_URL . 'processShp';
    $jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHBpcmVzQXQiOiIyMDI2LTEyLTEyIiwibmFtZSI6IkNoaXRoYWVudHJ5IiwiaWF0IjoxNTE2MjM5MDIyfQ.qvoMqhKtveXsLhjGSOw54vI0ruFh9PIup8qAOhvMUSk';
    $curl = curl_init();
    $jsonData = json_encode($data);
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST =>  true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_POSTFIELDS => $jsonData,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData),
            'Authorization: Bearer ' . $jwt_token
        ),
    ));

    $response = curl_exec($curl);
    log_message('error', $response);
    $success = false;
    if(!curl_errno($curl)){
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if(in_array($http_code, [200, 201])){
            $success = true;
        }
    }
    curl_close($curl);
    return ['success' => $success, 'data' =>json_decode($response)];

}

function callBhunakshaApiForUpdateProcessMap($data, $method = 'POST')
{
    $url = DXF_MAP_SHOW_BASE_URL . 'updateMap';
    $jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHBpcmVzQXQiOiIyMDI2LTEyLTEyIiwibmFtZSI6IkNoaXRoYWVudHJ5IiwiaWF0IjoxNTE2MjM5MDIyfQ.qvoMqhKtveXsLhjGSOw54vI0ruFh9PIup8qAOhvMUSk';
    $curl = curl_init();
    $jsonData = json_encode($data);
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST =>  true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_POSTFIELDS => $jsonData,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData),
            'Authorization: Bearer ' . $jwt_token
        ),
    ));

    $response = curl_exec($curl);
    log_message('error', $response);
    $success = false;
    if(!curl_errno($curl)){
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if(in_array($http_code, [200, 201])){
            $success = true;
        }
    }
    curl_close($curl);
    return ['success' => $success, 'data' =>json_decode($response)];

}

function callBhunakshaApiForUpdateShpProcessMap($data, $method = 'POST')
{
    $url = DXF_MAP_SHOW_BASE_URL . 'updateShp';
    $jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHBpcmVzQXQiOiIyMDI2LTEyLTEyIiwibmFtZSI6IkNoaXRoYWVudHJ5IiwiaWF0IjoxNTE2MjM5MDIyfQ.qvoMqhKtveXsLhjGSOw54vI0ruFh9PIup8qAOhvMUSk';
    $curl = curl_init();
    $jsonData = json_encode($data);
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST =>  true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_POSTFIELDS => $jsonData,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData),
            'Authorization: Bearer ' . $jwt_token
        ),
    ));

    $response = curl_exec($curl);
    log_message('error', $response);
    $success = false;
    if(!curl_errno($curl)){
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if(in_array($http_code, [200, 201])){
            $success = true;
        }
    }
    curl_close($curl);
    return ['success' => $success, 'data' =>json_decode($response)];

}

function callBhunakshaApiForFetchingMapGeo($data, $method = 'GET')
{
    $url = DXF_MAP_SHOW_BASE_URL . 'getGeoJSON?refId=' . $data['refId'];
    $jwt_token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHBpcmVzQXQiOiIyMDI2LTEyLTEyIiwibmFtZSI6IkNoaXRoYWVudHJ5IiwiaWF0IjoxNTE2MjM5MDIyfQ.qvoMqhKtveXsLhjGSOw54vI0ruFh9PIup8qAOhvMUSk';
    $curl = curl_init();
    $jsonData = json_encode($data);
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST =>  true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_SSL_VERIFYHOST => 2,
        // CURLOPT_POSTFIELDS => $jsonData,
        CURLOPT_HTTPHEADER => array(
            // 'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData),
            'Authorization: Bearer ' . $jwt_token
        ),
    ));

    $response = curl_exec($curl);
    $success = false;
    if(!curl_errno($curl)){
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if(in_array($http_code, [200, 201])){
            $success = true;
        }
    }
    curl_close($curl);
    return ['success' => $success, 'data' =>json_decode($response)];

}

function callApiV2($url,$method, $data=null)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        // CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_POSTFIELDS => http_build_query($data),
        CURLOPT_VERBOSE => 1,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded',
        ),
    ));

    $response = curl_exec($curl);

    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    curl_close($curl);
    if($httpcode != 200)
    {
        log_message("error", 'API FAIL');
        return false;
    }

    return $response;
}

function callLandhubAPIMerge($method, $url, $data)
{
    $curl = curl_init();
    $data['apikey'] = LANDHUB_APIKEY;
    curl_setopt_array($curl, array(
        CURLOPT_URL => LANDHUB_BASE_URL_NEW . $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_POSTFIELDS => http_build_query($data),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    if ($response) {
        return json_decode($response);
    } else {
        return null;
    }
}

function callLandhubAPIWithHeader($method, $url, $data)
{
    $header = ['alg' => 'HS256', 'typ' => 'JWT'];
    $payload = [
        'sub' => 'API_Call',
        'iat' => 1516239022,
        'exp' => time() + 900
    ];
    $key = 'olkhnmnbgfdsaqwertgnjjmlgpvdhdfagsjsdfqwaspojwqaxsplnbdlydrnvfi'; // shared secret

    $base64UrlHeader = rtrim(strtr(base64_encode(json_encode($header)), '+/', '-_'), '=');
    $base64UrlPayload = rtrim(strtr(base64_encode(json_encode($payload)), '+/', '-_'), '=');
    $signature = hash_hmac('sha256', "$base64UrlHeader.$base64UrlPayload", $key, true);
    $base64UrlSignature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

    $token = "$base64UrlHeader.$base64UrlPayload.$base64UrlSignature";

    // var_dump($token);
    // die;

    $curl = curl_init();
    // $data['apikey'] = LANDHUB_APIKEY;
    $jsonData = json_encode($data);

    // curl_setopt_array($curl, [
    //     CURLOPT_URL => $url,
    //     CURLOPT_RETURNTRANSFER => true,
    //     CURLOPT_POST => true,
    //     CURLOPT_HTTPHEADER => [
    //         'Authorization: Bearer ' . $token,
    //         'Content-Type: application/json'
    //     ],
    //     CURLOPT_POSTFIELDS => json_encode($data)
    // ]);



    curl_setopt_array($curl, array(
        CURLOPT_URL => LANDHUB_BASE_URL . $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST =>  true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        // CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_POSTFIELDS => $jsonData,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData),
            'Authorization: Bearer ' . $token
        ),
    ));

    $response = curl_exec($curl);
    $resp = [];
    if (curl_errno($curl)) {
        $resp = [
            'error' => curl_error($curl),
            'http_status' => curl_getinfo($curl, CURLINFO_HTTP_CODE),
            'data' => ''
        ];
    }
    else {
        $resp = [
            'error' => '',
            'http_status' => curl_getinfo($curl, CURLINFO_HTTP_CODE),
            'data' => json_decode($response)
        ];
    }
    curl_close($curl);
    return $resp;
}
