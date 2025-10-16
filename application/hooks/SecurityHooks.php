<?php
defined('BASEPATH') or exit('No direct script access allowed');
class SecurityHooks{
    public function __construct()
    {
        $this->ci = &get_instance();
    }
    public function set_security_headers()
    {
        $this->ci->output->set_header('X-Content-Type-Options: nosniff');
        $this->ci->output->set_header('Strict-Transport-Security: max-age=31536000;includeSubDomains');
        $this->ci->output->set_header('X-Frame-Options: SAMEORIGIN');
        $this->ci->output->set_header('X-Powered-By: NIC');
    }

    public function cors () {
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
        } else {
            header("Access-Control-Allow-Origin: *");
        }
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
        header('Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, Authorization, X-Requested-With');
        header('Access-Control-Allow-Credentials: true');

        // Handle preflight OPTIONS request
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            header('HTTP/1.1 200 OK');
            exit();
        }
    }
}