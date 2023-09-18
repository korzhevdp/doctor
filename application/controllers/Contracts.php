<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contracts extends CI_Controller {

	function __construct(){
		parent::__construct();
		session_start();
		if ( !isset($_SESSION['userid']) ) {
			$this->load->helper("url");
			redirect("login");
			exit;
		}
		$this->load->model("cntmodel");
		$this->load->model("toolmodel");
		$this->breadCrumbs = array(array("Реестр контрактов", base_url()."contracts"));
	}

	public $breadCrumbs;

	public function index() {
		$this->show();
	}

	public function add() {
		$output = array(
			'menu'    => $this->load->view('menu', array(), true),
			'content' => $this->load->view('proc/newcontract', array(), true)
		);
		$this->load->view('view', $output);
	}

	public function actsw() {
		$this->db->query("UPDATE
		`contracts`
		SET
		`contracts`.active     = IF(`contracts`.active = 1, 0 , 1)
		WHERE `contracts`.`id` = ?" , array(
			$this->input->post("cnt")
		));
		print "done";
	}

	public function show( $contractID = 0 ) {
		( $contractID )
			? array_push($this->breadCrumbs, array("Просмотр контракта", base_url()."refs/staff")) 
			: array_push($this->breadCrumbs, array("Список контрактов", "#"));

		$output = array(
			'menu'		=> $this->load->view('menu', array(), true),
			'content'	=> ($contractID)
							? $this->cntmodel->getContractData($contractID)
							: $this->cntmodel->getContractsList($contractID),
			'bc'		=> $this->toolmodel->get_bc($this->breadCrumbs)
		);
		$this->load->view('view', $output);
	}

	public function cnt_save() {
		$this->cntmodel->cnt_save();
	}

	public function cnt_initdata_get() {
		print $this->cntmodel->cnt_initdata_get();
	}

	public function rel_pats_get() {
		print $this->cntmodel->rel_pats_get();
	}

	public function getContractData($cnt=0) {
		print $this->cntmodel->getContractData($cnt);
	}

	public function underwrite() {
		print $this->cntmodel->underwrite();
	}

	public function cnt_sheduler(){
		//$out = $this->cntmodel->shedule_calc2('2014-3-3', array(0), $rounds=25);
		//print "<br><br>".implode($out, " <BR>");
	}


}

/* End of file contracts.php */
/* Location: ./application/controllers/contracts.php */