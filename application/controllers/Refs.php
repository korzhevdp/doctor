<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Refs extends CI_Controller {

	function __construct(){
		//phpinfo();
		parent::__construct();
		session_start();
		if ( !isset($_SESSION['userid']) ) {
			$this->load->helper("url");
			redirect("login");
			exit;
		}
		$this->load->model("refmodel");
		$this->load->model("toolmodel");

		$this->bc = array(array("Справочники", base_url()."refs"));
	}

	public $bc;

	function index(){
		$output = array(
			'menu' => $this->load->view('menu', array(), true),
			'content' => '<a href="'.base_url().'refs/suppliers">Поставщики</a><br><br>
			<a href="'.base_url().'refs/patients">Пациенты</a><br><br>
			<a href="'.base_url().'refs/clients">Клиенты</a><br><br>
			<a href="'.base_url().'refs/services">Услуги</a><br><br>',
			'bc' => $this->toolmodel->get_bc($this->bc)
		);
		$this->load->view('view', $output);
	}

	public function staff(){
		array_push($this->bc, array("Сотрудники", base_url()."refs/staff"));
		$output = array(
			'menu'    => $this->load->view('menu', array(), true),
			'content' => $this->refmodel->getStaffList(),
			'bc'      => $this->toolmodel->get_bc($this->bc)
		);
		$this->load->view('view', $output);
	}

	public function editStaff( $staffID = 1 ) {
		array_push($this->bc, array("Сотрудники", base_url()."refs/staff"));
		array_push($this->bc, array("Редактирование сотрудника", base_url()."refs/editStaff"));
		$output = array(
			'menu'    => $this->load->view('menu', array(), true),
			'content' => $this->refmodel->editStaff( $staffID ),
			'bc'      => $this->toolmodel->get_bc( $this->bc )
		);
		$this->load->view('view', $output);
	}

	public function staff_save(){
		//$this->output->enable_profiler(TRUE);
		$this->refmodel->staff_save();
	}

	public function staff_save2(){
		//$this->output->enable_profiler(TRUE);
		$this->refmodel->staff_save2();
	}

	public function new_staff(){
		array_push($this->bc, array("Сотрудники", base_url()."refs/staff"));
		array_push($this->bc, array("Добавление сотрудника", base_url()."refs/new_staff"));
		$serv = array();
		$result = $this->db->query("SELECT 
		`services`.`id`,
		`services`.serv_alias,
		`services`.serv_name
		FROM
		services");
		if ( $result->num_rows() ) {
			foreach ( $result->result() as $row ) {
				$string = '<li><label><span class="text"><input type="checkbox" name="servord[]" value="'.$row->id.'">'.$row->serv_name.'</span></label></li>';
				array_push($serv, $string);
			}
		}
		$row = array(
			"staff_f"              => "",
			"staff_i"              => "",
			"staff_o"              => "",
			"staff_staff"          => "",
			"staff_address"        => "",
			"staff_birthdate"      => "",
			"staff_phone"          => "",
			"staff_email"          => "",
			"staff_pass_s"         => "",
			"staff_pass_n"         => "",
			"staff_pass_issued"    => "",
			"staff_pass_issuedate" => "",
			"staff_inn"            => "",
			"staff_snils"          => "",
			"staff_note"           => "",
			"staff_edu"            => "",
			"staff_spec"           => "",
			"staff_location"       => "",
			"staff_rank"           => 2,
			"id"                   => 0,
			"actbtn"               => "",
			"serv"                 => implode("\n", $serv)
		);
		$output = array(
			'menu'    => $this->load->view('menu', array(), true),
			'content' => $this->load->view('refs/staffnew', $row, true),
			'bc'      => $this->toolmodel->get_bc($this->bc)
		);
		$this->load->view('view', $output);
	}

	public function staff_item_add(){
		//$this->output->enable_profiler(TRUE);
		$this->refmodel->staff_item_add();
	}
	
	########################################
	public function suppliers(){
		array_push($this->bc, array("Поставщики", base_url()."refs/suppliers"));
		array_push($this->bc, array("Список поставщиков", base_url()."refs/suppliers"));
		$output = array(
			'menu'    => $this->load->view('menu', array(), true),
			'content' => $this->refmodel->getSuppliersList(),
			'bc'      => $this->toolmodel->get_bc($this->bc)
		);
		$this->load->view('view', $output);
	}

	public function suppliers_billing( $sid ) {
		array_push($this->bc, array("Поставщики", base_url()."refs/suppliers"));
		array_push($this->bc, array("Детализация счёта", base_url()."refs/suppliers_billing"));
		$output = array(
			'menu'    => $this->load->view('menu', array(), true),
			'content' => $this->refmodel->getSupplierBilling($sid),
			'bc'      => $this->toolmodel->get_bc($this->bc)
		);
		$this->load->view('view', $output);
	}

	public function editSupplier($id = 1) {
		array_push($this->bc, array("Поставщики", base_url()."refs/suppliers"));
		array_push($this->bc, array("Редактирование поставщика", base_url()."refs/editSupplier"));
		$output = array(
			'menu'    => $this->load->view('menu', array(), true),
			'content' => $this->refmodel->editSupplier($id),
			'bc'      => $this->toolmodel->get_bc($this->bc)
		);
		$this->load->view('view', $output);
	}

	public function addNewSupplier(){
		array_push($this->bc, array("Поставщики", base_url()."refs/suppliers"));
		array_push($this->bc, array("Добавление поставщика", base_url()."refs/addNewSupplier"));
		$row = array(
			"supp_f"       => "",
			"supp_i"       => "",
			"supp_o"       => "",
			"supp_staff"   => "",
			"supp_orgname" => "",
			"supp_address" => "",
			"supp_phone"   => "",
			"supp_email"   => "",
			"supp_note"    => "",
			"sid"          => 0,
			"supp_royalty" => 0,
			"actbtn"       => ""
		);
		$output = array(
			'menu'    => $this->load->view('menu', array(), true),
			'content' => $this->load->view('refs/newsupplier', $row, true),
			'bc'      => $this->toolmodel->get_bc($this->bc)
		);
		$this->load->view('view', $output);
	}

	public function saveSupplier(){
		//$this->output->enable_profiler(TRUE);
		$this->refmodel->saveSupplier();
	}

	public function supp_item_add(){
		//$this->output->enable_profiler(TRUE);
		$this->refmodel->supp_item_add();
	}
	########################################

	public function clients(){
		array_push($this->bc, array("Клиенты", base_url()."refs/clients"));
		$output = array(
			'menu'    => $this->load->view('menu', array(), true),
			'content' => $this->refmodel->getClientsList(),
			'bc'      => $this->toolmodel->get_bc($this->bc)
		);
		$this->load->view('view', $output);
	}

	public function editClient( $clientID = 0 ) {
		//$this->output->enable_profiler(TRUE);
		array_push($this->bc, array("Клиенты", base_url()."refs/clients"));
		array_push($this->bc, array("Редактирование клиента", base_url()."refs/editClient"));
		$output = array(
			'menu'    => $this->load->view('menu', array(), true),
			'content' => $this->refmodel->editClient( $clientID ),
			'bc'      => $this->toolmodel->get_bc($this->bc)
		);
		$this->load->view('view', $output);
	}
	
	public function saveClient(){
		//$this->output->enable_profiler(TRUE);
		$this->refmodel->saveClient();
	}

	public function addNewClient(){
		//$this->output->enable_profiler(TRUE);
		$this->refmodel->addNewClient();
	}

	public function new_client(){
		array_push($this->bc, array("Клиенты", base_url()."refs/clients"));
		array_push($this->bc, array("Добавление клиента", base_url()."refs/new_client"));
		$row = array(
			"cli_f"              => "",
			"cli_i"              => "",
			"cli_o"              => "",
			"cli_address"        => "",
			"cli_phone"          => "",
			"cli_cphone"         => "",
			"cli_card"           => "",
			"cli_mail"           => "",
			"cli_pass_s"         => "",
			"cli_pass_n"         => "",
			"cli_pass_issued"    => "",
			"cli_pass_issuedate" => "",
			"cli_note"           => "",
			"cli_hphone"         => "",
			"cli_location"       => "",
			"id"                 => 0,
			"actbtn"             => ""
		);
		$output = array(
			'menu'    => $this->load->view('menu', array(), true),
			'content' => $this->load->view('refs/clientnew', $row, true),
			'bc'      => $this->toolmodel->get_bc($this->bc)
		);
		$this->load->view('view', $output);
	}

	########################################
	public function patients($item = 0){
		array_push($this->bc, array("Пациенты", base_url()."refs/patients"));
		$output = array(
			'menu'    => $this->load->view('menu', array(), true),
			'content' => $this->refmodel->getPatientsList(),
			'bc'      => $this->toolmodel->get_bc($this->bc)
		);
		$this->load->view('view', $output);
	}

	public function editPatient($patientID = 0){
		//$this->output->enable_profiler(TRUE);
		array_push($this->bc, array("Пациенты", base_url()."refs/patients"));
		array_push($this->bc, array("Редактирование пациента", base_url()."refs/editPatient"));
		$output = array(
			'menu'    => $this->load->view('menu', array(), true),
			'content' => $this->refmodel->editPatient( $patientID ),
			'bc'      => $this->toolmodel->get_bc($this->bc)
		);
		$this->load->view('view', $output);
	}

	public function pat_save(){
		//$this->output->enable_profiler(TRUE);
		$this->refmodel->pat_save();
	}

	public function pat_item_add(){
		//$this->output->enable_profiler(TRUE);
		$this->refmodel->pat_item_add();
	}

	public function pat_lists_get(){
		//$this->output->enable_profiler(TRUE);
		print $this->refmodel->pat_lists_get();
	}

	########################################################
	public function services($item = 0){
		array_push($this->bc, array("Услуги", base_url()."refs/services"));
		$output = array(
			'menu'    => $this->load->view('menu', array(), true),
			'content' => $this->refmodel->serv_list_get(),
			'bc'      => $this->toolmodel->get_bc($this->bc)
		);
		$this->load->view('view', $output);
	}

	public function editService($item = 0){
		//$this->output->enable_profiler(TRUE);
		array_push($this->bc, array("Услуги", base_url()."refs/services"));
		array_push($this->bc, array("Редактирование услуги", base_url()."refs/editService"));
		$output = array(
			'menu'    => $this->load->view('menu', array(), true),
			'content' => $this->refmodel->editService($item),
			'bc'      => $this->toolmodel->get_bc($this->bc)
		);
		$this->load->view('view', $output);
	}

	public function saveService(){
		//$this->output->enable_profiler(TRUE);
		//exit;
		$this->refmodel->saveService();
	}

	public function serv_item_add(){
		//$this->output->enable_profiler(TRUE);
		$this->refmodel->serv_item_add();
	}

	######################################################################################
	#### REF AJAX FX ####
	#### Staff control AJAX FX
	# Получение данных по работнику - передача запроса в модель к соответствующей функции
	public function staff_item_get() {
		print $this->refmodel->staff_item_get();
	}

	# Снятие флага "уволен" с сотрудника
	# Непосредственным запросом - т.к. запрос "неразрушающий".
	# 
	public function staff_activate($id){
		if((int) $id){
			$this->db->query("UPDATE staff SET staff.active = 1 WHERE staff.id = ?", array( $id ));
		}
		$this->toolmodel->insert_audit("Активирован профиль сотрудника #".$id);
	}
	# Маркировка сотрудника уволенным
	public function staff_deactivate( $id ) {
		if((int)$id){
			$this->db->query("UPDATE staff SET staff.active = 0 WHERE staff.id = ?", array( $id ));
			$this->toolmodel->insert_audit("Деактивирован профиль сотрудника / уволен сотрудник #".$id);
		}
		$this->refmodel->checkServiceAvailability();
	}

	#### Suppliers control AJAX FX
	# Получение данных поставщика - передача запроса в модель к соответствующей функции
	public function supp_item_get() {
		print $this->refmodel->supp_item_get();
	}
	# Маркировка поставщика активным
	# Непосредственным запросом - т.к. запрос "неразрушающий".
	public function supp_activate() {
		if((int) $this->input->post("suppid")){
			$this->db->query("UPDATE suppliers SET suppliers.active = 1 WHERE suppliers.sid = ?", array( (int)$this->input->post("suppid")));
		}
		$this->toolmodel->insert_audit("Активирован поставщик supp#".$this->input->post("suppid"));
	}
	# Снятие флага "активный"
	public function supp_deactivate(){
		if((int) $this->input->post("suppid")){
			$this->db->query("UPDATE suppliers SET suppliers.active = 0 WHERE suppliers.sid = ?", array( (int)$this->input->post("suppid")));
		}
		$this->toolmodel->insert_audit("Снят с довольствия поставщик supp#".$this->input->post("suppid"));
	}

	#### Clients control AJAX FX
	# Получение данных клиента - передача запроса в модель к соответствующей функции
	public function client_item_get() {
		print $this->refmodel->client_item_get();
	}
	# Маркировка клиента активным
	# Непосредственным запросом - т.к. запрос "неразрушающий".
	public function activateClient( $clientID ) {
		if ( (int) $clientID ) {
			$this->db->query("UPDATE clients SET clients.active = 1 WHERE clients.id = ?", array( $clientID ) );
			$this->toolmodel->insert_audit("Активирован заново клиент cli#".$clientID);
		}
		$this->load->helper("url");
		redirect("refs/editClient/".$clientID);
	}

	# Снятие флага "активный"
	public function deactivateClient( $clientID ) {
		if ( (int) $clientID) {
			$this->db->query("UPDATE clients SET clients.active = 0 WHERE clients.id = ?", array( $clientID ) );
			// отключение связанных контрактов
			$result = $this->db->query("SELECT
			GROUP_CONCAT(contracts.id) AS id
			FROM
			patients
			LEFT OUTER JOIN clients		ON (patients.pat_clientid	= clients.id)
			RIGHT OUTER JOIN courses	ON (patients.id				= courses.pid)
			RIGHT OUTER JOIN contracts	ON (courses.id				= contracts.crsid)
			WHERE
			(clients.id = ?)", array(
				$clientID
			));
			if ( $result->num_rows() ) {
				$row = $result->row();
				if ( strlen($row->id) ) {
					$this->db->query("UPDATE contracts SET active = 0 WHERE contracts.id IN (".$row->id.")");
				}
			}
			$this->toolmodel->insert_audit("Разорваны дипломатические отношения с клиентом cli#".$clientID);
		}
		$this->load->helper("url");
		redirect("refs/editClient/".$clientID);
	}

	#### Patients control AJAX FX
	# Получение данных пациента - передача запроса в модель к соответствующей функции
	public function pat_item_get() {
		print $this->refmodel->pat_item_get();
	}
	# Маркировка пациента активным
	# Непосредственным запросом - т.к. запрос "неразрушающий".
	public function pat_activate($item) {
		if((int) $item){
			$this->db->query("UPDATE patients SET patients.active = 1 WHERE patients.id = ?", array((int) $item));
			$this->db->query("UPDATE `courses` SET `courses`.`active` = 1 WHERE `courses`.`pid` = ?", array((int) $item));
		}
		$this->toolmodel->insert_audit("Пациент снова с нами pat#".$item);
		$this->toolmodel->insert_audit("Курс лечения восстановлен у pat#".$item);
		$this->load->helper("url");
		redirect("refs/pat_edit/".$item);
	}

	# Снятие флага "активный"
	public function pat_deactivate($item){
		if((int) $item){
			$this->db->query("UPDATE patients SET patients.active = 0 WHERE patients.id = ?", array((int) $item));
			$this->db->query("UPDATE `courses` SET `courses`.`active` = 0 WHERE `courses`.`pid` = ?", array((int) $item));
		}
		$this->toolmodel->insert_audit("Пациент наc покинул / деактивирован pat#".$item);
		$this->toolmodel->insert_audit("Пациент наc покинул / деактивирован курс лечения у pat#".$item);
		$this->load->helper("url");
		redirect("refs/pat_edit/".$item);
	}

	# Создание курса для пациента "вручную" (для исправления)
	public function pat_createcrs(){
		if((int) $this->input->post("patid")){
			$this->db->query("INSERT INTO `courses`(`courses`.pid, `courses`.startdate) VALUES (?, NOW())", array($this->input->post("patid")));
		}
		$this->toolmodel->insert_audit("Создан курс для пациента pat#".$this->input->post("patid"));
	}

	#### Services control AJAX FX
	# Получение данных услуги - передача запроса в модель к соответствующей функции
	public function serv_item_get() {
		print $this->refmodel->serv_item_get();
	}

	# Маркировка услуги активной
	# Непосредственным запросом - т.к. запрос "неразрушающий".
	public function serv_activate($item) {
		if((int)$item){
			$this->db->query("UPDATE services SET services.m_active = 1 WHERE services.id = ?", array($item));
			$this->toolmodel->insert_audit("Услуга вновь оказывается (адм.) serv#".$item);
		}
		$this->load->helper("url");
		redirect("refs/editService/".$item);
	}

	# Снятие флага "активная"
	public function serv_deactivate($item){
		if((int)$item){
			$this->db->query("UPDATE services SET services.m_active = 0 WHERE services.id = ?", array($item));
			$this->toolmodel->insert_audit("Услуга более не оказывается (адм.) serv#".$item);
		}
		$this->load->helper("url");
		redirect("refs/editService/".$item);
	}

	#### Экспериментальные криптомодули
	############################################################
	public function test_enc(){
		$this->toolmodel->encrypt_an_array();
	}

	public function init_crypto(){
		$this->toolmodel->write_keyfile();
		$this->toolmodel->write_checkfile();
	}

	public function ut($password, $master_password, $userid){
		$this->toolmodel->calc_decoder($password, $master_password, $userid);
	}

	public function test_pass($password = '12345678'){
		$this->toolmodel->test_pass($password);
	}

}

/* End of file refs.php */
/* Location: ./application/controllers/refs.php */