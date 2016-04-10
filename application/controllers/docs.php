<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Docs extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("docmodel");
	}

	public function index(){
		header("HTTP/1.0 404 Not Found");
		header("HTTP/1.1 404 Not Found");
		header("Status: 404 Not Found");
	}

	public function cg($id){
		$this->docmodel->contract_get($id);
	}

	public function cag($id){
		$this->docmodel->contract_act_get($id);
	}


	public function editor($docname=0){
		$this->docmodel->editor_get($docname);
	}

	public function docsave(){
		$this->docmodel->doc_save();
	}
}

/* End of file docs.php */
/* Location: ./application/controllers/docs.php */