<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Shedule extends CI_Controller {

	function __construct(){
		parent::__construct();
		session_start();
		if ( !isset($_SESSION['userid']) ) {
			$this->load->helper("url");
			redirect("login");
			exit;
		}
		$this->load->model("shedmodel");
		$this->load->model("toolmodel");
		$prefs = array (
			'show_next_prev'=> TRUE,
			'next_prev_url' => base_url().'/shedule/show/',
			'start_day'     => 'monday'
		);
		$this->load->library('calendar', $prefs);
	}

	public $bc = array(array("Расписания", "/shedule"));

	public function index(){
		$this->show(date("Y"), date("m"));
	}

	public function show($year, $month){
		array_push($this->bc, array("Календарь услуг", "#"));
		$output = array(
			'menu'		=> $this->load->view('menu', array(), true),
			'content'	=> $this->shedmodel->show($year, $month),
			'bc'		=> $this->toolmodel->get_bc($this->bc)
		);
		$this->load->view('view', $output);
	}

	public function pload($year=0, $month=0, $day=0){
		array_push($this->bc, array("Нагрузка на сотрудников", "#"));
		$year   = (!$year)  ? date("Y") : $year;
		$month  = (!$month) ? date("m") : $month;
		$day    = (!$day)   ? date("d") : $day;
		$output = array(
			'menu'		=> $this->load->view('menu', array(), true),
			'content'	=> $this->shedmodel->pload($year, $month, $day),
			'bc'		=> $this->toolmodel->get_bc($this->bc)
		);
		$this->load->view('view', $output);
	}
	
	public function day2($year, $month, $day){
		$this->shedmodel->day2($year, $month, $day);
	}

	public function day($year, $month, $day){
		$this->shedmodel->day($year, $month, $day);
	}

	public function savetime(){
		$this->shedmodel->savetime();
	}

	public function getinst(){
		$this->shedmodel->getinst();
	}

	public function saveinst(){
		$this->shedmodel->saveinst();
	}

	public function jobdone(){
		$this->shedmodel->jobdone();
	}

	public function jobundone(){
		$this->shedmodel->jobundone();
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */