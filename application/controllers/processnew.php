<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Processnew extends CI_Controller {

	function __construct(){
		parent::__construct();
		if(!$this->session->userdata('userid')){
			$this->load->helper("url");
			redirect("login");
			exit;
		}
		$this->load->model("refmodel");
	}

	public function index(){
		$output = array();
		$output['menu'] = $this->load->view('menu', $output, true);
		$output['content'] = $this->load->view('refs/processnew', $output, true);
		$this->load->view('view', $output);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */