<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Start extends CI_Controller {

	function __construct(){
		parent::__construct();
		session_start();
		$this->load->model("shedmodel");
	}

	public function index(){
		$output = array(
			'menu'    => $this->load->view('menu', array(), true),
			'content' => $this->load->view('welcome', array(), true)
		);

		if ( isset($_SESSION['userid']) ) {
			if ( isset($_SESSION['rank'] ) && $_SESSION['rank'] < 3 ) {
				$output['content'] = $this->shedmodel->shed_summary_get();
			}
			$output['content'] .= $this->shedmodel->myshedule();
			$this->load->view('view', $output);
			return true;
		}
		$this->load->view('view', $output);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */