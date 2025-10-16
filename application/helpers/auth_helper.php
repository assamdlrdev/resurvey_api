<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Firebase\JWT\JWT;
// use Firebase\JWT\Key;

function jwtencode($payload)
{
    // $CI =& get_instance();
    // $CI->load->library('JWT');
    $token = JWT::encode($payload, JWT_SECRET_KEY, 'HS256');
    return $token;
}

function jwtdecode($jwt_token)
{
    $decodedToken = JWT::decode($jwt_token, JWT_SECRET_KEY, ['HS256']);
    return $decodedToken;
}
function jwtVerify($jwt_token)
{
    $decoded = jwtdecode($jwt_token);
    if (!$decoded) {
        $this->output
            ->set_status_header(401)
            ->set_output(json_encode(['error' => 'Invalid or expired token']));
        exit;
    }
    return $decoded;
}

if (!function_exists('get_bearer_token')) {
    function get_bearer_token()
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            } elseif (isset($requestHeaders['authorization'])) {
                $headers = trim($requestHeaders['authorization']);
            }
        }

        if (!empty($headers) && preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            return $matches[1];
        }
        return null;
    }
}

if (!function_exists('validate_jwt')) {
    function validate_jwt()
    {
        $token = get_bearer_token();
        if (!$token) {
            return ['status' => false, 'message' => 'Authorization token not found'];
        }

        try {
            $decoded = jwtdecode($token);
            return ['status' => true, 'data' => $decoded];
        } catch (Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }
}
