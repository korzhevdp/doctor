<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contracts extends CI_Controller {

	function __construct(){
		parent::__construct();
		//$this->output->enable_profiler(TRUE);
		if(!$this->session->userdata('userid')){
			$this->load->helper("url");
			redirect("login");
			exit;
		}
		$this->load->model("cntmodel");
		$this->load->model("toolmodel");
	}

	public $bc = array(array("Реестр контрактов", "/contracts"));

	public function index(){
		$this->show();
	}

	public function add(){
		$output = array();
		$output['menu'] = $this->load->view('menu', $output, true);
		$output['content'] = $this->cntmodel->addnew();
		$this->load->view('view', $output);
	}

	public function actsw(){
		$result = $this->db->query("UPDATE
		`contracts`
		SET
		`contracts`.active = IF(`contracts`.active = 1, 0 , 1)
		WHERE
		`contracts`.`id` = ?" , array($this->input->post("cnt")));
		print "done";
	}

	public function show($cid=0){
		($cid) ? array_push($this->bc, array("Просмотр контракта", "/refs/staff")) : array_push($this->bc, array("Список контрактов", "#"));
		$output = array(
			'menu'		=> $this->load->view('menu', array(), true),
			'content'	=> ($cid) ? $this->cntmodel->cnt_get($cid) : $this->cntmodel->cnt_list_get($cid),
			'bc'		=> $this->toolmodel->get_bc($this->bc)
		);
		$this->load->view('view', $output);
	}

	public function cnt_save(){
		$this->cntmodel->cnt_save();
	}

	public function cnt_initdata_get(){
		print $this->cntmodel->cnt_initdata_get();
	}

	public function rel_pats_get(){
		print $this->cntmodel->rel_pats_get();
	}

	public function cnt_data_get($cnt=0){
		print $this->cntmodel->cnt_data_get($cnt);
	}

	public function underwrite(){
		print $this->cntmodel->underwrite();
	}

	public function cnt_sheduler(){
		//$out = $this->cntmodel->shedule_calc2('2014-3-3', array(0), $rounds=25);
		//print "<br><br>".implode($out, " <BR>");
	}


}

/* End of file contracts.php */
/* Location: ./application/controllers/contracts.php */