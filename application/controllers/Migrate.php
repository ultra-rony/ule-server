<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migrate extends CI_Controller
{

	public function index()
	{
		// Command
		// php index.php migrate

		// if (!$this->input->is_cli_request()) {
		//	show_404();
		// }
		$this->load->library('migration');
		if ($this->migration->latest() === FALSE) {
			$this->output->set_status_header(403)
				->set_content_type('application/json')
				->set_output(json_encode([
					'migration' => false,
					'message' => $this->migration->error_string()
				], JSON_UNESCAPED_UNICODE));
		} else {
			$this->output->set_status_header(200)
				->set_content_type('application/json')
				->set_output(json_encode([
					'migration' => true,
					'message' => "Миграции успешно применены!"
				], JSON_UNESCAPED_UNICODE));
		}
	}
}
