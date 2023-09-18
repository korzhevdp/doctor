<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct(){
		parent::__construct();
		session_start();
		$this->load->model("loginmodel");
		$this->load->model("toolmodel");
	}

	public function index(){
		if ( $this->input->post('name') && $this->input->post('pass') ) {
			$this->loginmodel->_test_user();
			return true;
		}
		$this->load->view("login/login_view");
	}

	public function logout(){
		$this->loginmodel->logout();
	}

}

/* End of file login.php */
/* Location: ./application/controllers/login.php */