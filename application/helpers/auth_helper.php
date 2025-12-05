<?php

use phpDocumentor\Reflection\Types\Boolean;
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


// Function to check if authentication is successful
function isAuthSuccess(): bool
{
    // Directly return the status of validate_jwt() instead of using an if-else statement
    return (bool) $auth = validate_jwt()['status'];
}

// Function to retrieve authentication data
function authData()
{
    // Use null coalescing operator to simplify the code
    return validate_jwt()['data'] ?? null;
}

// Function to retrieve authentication data
function authMessage()
{
    // Use null coalescing operator to simplify the code
    return validate_jwt()['message'] ?? null;
}

// Function to check if authentication failed, return output directly
function authCheck()
{
    $CI =& get_instance(); // Get the CI instance

    if (!validate_jwt()['status']) {
        // Directly send the 401 response with the error message using the CI instance
        $CI->output
            ->set_status_header(401)
            ->set_content_type('application/json')
            ->set_output(json_encode(['error' => authMessage(), 'message' => 'Authentication Failed!']))
            ->_display();
        exit; // End the script execution after sending the response
    }
}


