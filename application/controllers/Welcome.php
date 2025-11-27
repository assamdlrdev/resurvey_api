<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		// Load routes.php
		$routes_path = APPPATH . 'config/routes.php';
		$routes = [];
		if (file_exists($routes_path)) {
			include($routes_path);
			if (isset($route) && is_array($route)) {
				foreach ($route as $key => $val) {
					// Only include custom endpoints (skip default controller, 404_override, etc.)
					if (!in_array($key, ['default_controller', '404_override', 'translate_uri_dashes'])) {
						$routes[$key] = base_url($key);
					}
				}
			}
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode([
				'status' => 'success',
				'message' => 'Welcome to the ChithaAPI service home page.',
				'endpoints' => $routes
			]));
	}
	
	
}
