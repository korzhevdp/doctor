<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Start extends CI_Controller {

	function __construct(){
		parent::__construct();
		//phpinfo();
		//$this->output->enable_profiler(TRUE);
		$this->load->model("shedmodel");
	}

	public function index(){
		$output = array();
		$output['menu'] = $this->load->view('menu', $output, true);
		if(!$this->session->userdata('userid')){
			$output['content'] = $this->load->view('welcome', $output, true);
		}else{
			$output['content'] = "";
			if($this->session->userdata('rank') < 3){
				$output['content'] .= $this->shedmodel->shed_summary_get();
			}
			$output['content'] .= $this->shedmodel->myshedule();
		}
		$this->load->view('view', $output);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */