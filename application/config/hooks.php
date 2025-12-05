<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/
// $hook['post_controller_constructor'][] = array(
    
//     'class'    => 'SecurityHooks',
//     'function' => 'cors',
//     'filename' => 'SecurityHooks.php',
//     'filepath' => 'hooks',
    
// );
$hook['post_controller_constructor'][] = array(
    
        'class'    => 'SecurityHooks',
        'function' => 'set_security_headers',
        'filename' => 'SecurityHooks.php',
        'filepath' => 'hooks',
    
);

$hook['pre_system'] = function() {
    if (!in_array($_SERVER['REQUEST_METHOD'],['GET','POST','OPTIONS'])) {
        show_error('Invalid request method', 405);
    }
};
$hook['post_controller_constructor'][] = [
    'class'    => 'CspMiddleware',
    'function' => 'handle',
    'filename' => 'CspMiddleware.php',
    'filepath' => 'middleware',
];