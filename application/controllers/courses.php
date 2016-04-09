<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Courses extends CI_Controller {

	function __construct(){
		parent::__construct();
		//$this->output->enable_profiler(TRUE);
		if(!$this->session->userdata('userid')){
			$this->load->helper("url");
			redirect("login");
			exit;
		}
		$this->load->model("toolmodel");
		$this->load->model("crsmodel");
	}

	public $class = array(array("Курсы лечения", "/courses"));

	public function index(){
		array_push($this->class, array("Список курсов лечения"));
		$this->load->helper('html');
		$output = array(
			'menu'    => $this->load->view('menu', array(), true),
			'content' => $this->crsmodel->crs_list_get(),
			'bc'	  => $this->toolmodel->get_bc($this->class)
		);
		$this->load->view('view', $output);
	}

	public function item($id = 0){
		array_push($this->class, array("Описание курса лечения", "/courses/item/".$id));
		$output = array(
			'menu'    => $this->load->view('menu', array(), true),
			'content' => $this->crsmodel->crs_item_get($id),
			'bc'	  => $this->toolmodel->get_bc($this->class)
		);
		$this->load->view('view', $output);
	}
}

/* End of file courses.php */
/* Location: ./application/controllers/courses.php */