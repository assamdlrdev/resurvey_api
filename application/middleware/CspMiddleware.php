<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CspMiddleware
{
    public function __construct()
    {
        $this->ci = &get_instance();
    }

    public function handle()
    {
        $cspHeader = "default-src 'self';
        img-src 'self' data:;
        font-src 'self' data:;
        object-src 'none';
        base-uri 'self';
        frame-ancestors 'none';";

        $this->ci->output->set_header("Content-Security-Policy: " . $cspHeader);
    }
}