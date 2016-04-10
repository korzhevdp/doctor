<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payments extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model("shedmodel");
		$this->load->model("toolmodel");
		//$this->load->model("crsmodel");
		//$this->output->enable_profiler(TRUE);
		if(!$this->session->userdata('userid')){
			$this->load->helper("url");
			redirect("login");
			exit;
		}
	}

	public function index(){
		$this->moneyflow();
	}

	public function moneyflow($client_id = 0){
		$output = array();
		$output['menu'] = $this->load->view('menu', $output, true);
		$output['content'] = $this->shedmodel->payment_table_get($client_id);
		$this->load->view('view', $output);
	}

	public function get_payment(){
		if($this->session->userdata('userid') > 2 || !$this->input->post("rub") || !$this->input->post('contract_id')){
			$this->load->helper("url");
			redirect("payments/moneyflow/".$this->input->post('client_id'));
		}
		$sum = ($this->input->post('rub') * 100) + $this->input->post('kop');
		$result = $this->db->query("INSERT INTO
		payments (
			payments.getdate,
			payments.`sum`,
			payments.contract_id,
			payments.service_id,
			payments.dtype,
			payments.dnum
		) VALUES ( NOW(), ?, ?, ?, ?, ?)", array($sum, $this->input->post('contract_id'), $this->input->post('service'), $this->input->post('dtype'), $this->input->post('dnum')));
		$this->toolmodel->insert_audit("Добавлен платёж по контракту #".$this->input->post('contract_id')." на сумму ".($sum / 100)."р. " );
		$this->load->helper("url");
		redirect("payments/moneyflow/".$this->input->post('client_id'));
	}

	public function off_payment(){
		if($this->session->userdata('userid') > 2){
			return false;
		}
		$result = $this->db->query("UPDATE
		payments
		SET
		active = 0,
		deactdate = NOW()
		WHERE payments.id = ?", array($this->input->post('id')));
		$this->toolmodel->insert_audit("Удалён платёж по контракту #".$this->input->post('contract_id').", внутренний номер платежа: ".$this->input->post('id'));
		$this->load->helper("url");
		redirect("payments/moneyflow/".$this->input->post('client_id'));
	}



}

/* End of file payments.php */
/* Location: ./application/controllers/payments.php */