<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Refmodel extends CI_Model{
	
	function __construct(){
		parent::__construct();
		//$this->output->enable_profiler(TRUE);
	}

	## STAFF CONTROL Section
	# Получение данных по работнику 
	public function staff_edit($id){
		$serv = array();
		$result = $this->db->query("SELECT 
		`services`.`id`,
		services.serv_alias,
		services.serv_name,
		IF(`services`.`id` IN (SELECT `servordering`.`servid` FROM `servordering` WHERE `servordering`.`staffid` = ?), 1, 0) as ordered
		FROM
		services", array( $id ));
		if($result->num_rows()){
			foreach($result->result() as $row){
				$active = ($row->ordered) ? ' checked="checked"' : '' ;
				$string = '<li><label><span class="text"><input type="checkbox" class="cbs" value="'.$row->id.'"'.$active.'>'.$row->serv_name.'</span></label></li>';
				array_push($serv, $string);
			}
		}

		$result = $this->db->query("SELECT 
		staff.id,
		staff.staff_f,
		staff.staff_i,
		staff.staff_o,
		staff.staff_address,
		DATE_FORMAT(staff.staff_birthdate, '%d.%m.%Y') AS staff_birthdate,
		DATEDIFF(NOW(), staff.staff_birthdate) AS date_diff,
		staff.staff_staff,
		staff.staff_phone,
		staff.staff_email,
		staff.staff_spec,
		staff.staff_location,
		staff.staff_passport,
		staff.staff_pass_s,
		staff.staff_pass_n,
		DATE_FORMAT(staff.staff_pass_issuedate, '%d.%m.%Y') AS staff_pass_issuedate,
		staff.staff_pass_issued,
		staff.staff_edu,
		staff.staff_note,
		staff.staff_inn,
		staff.staff_snils,
		staff.staff_rank,
		staff.active,
		staff.encoded,
		users.login AS username
		FROM
		users
		RIGHT OUTER JOIN staff ON (users.id = staff.id)
		WHERE
		(staff.id = ?)", array( $id ));
		if($result->num_rows()){
			$row = $result->row_array();
			if($row['encoded']){
				$row = $this->toolmodel->decrypt_an_array($row);
			}
			$row['serv'] = implode($serv, "\n");
			$row['actbtn'] = ($row['active']) 
			? '<a class="btn btn-danger btn-act"  href="/refs/staff_deactivate/'.$row['id'].'">Уволить с работы</a>'
			: '<a class="btn btn-warning btn-act" href="/refs/staff_activate/'.$row['id'].'">Восстановить на работе</a>';
		}
		return $this->load->view('refs/staffedit', $row, true);
	}

	# Извлечение списка работников
	public function staff_list_get(){
		//$this->output->enable_profiler(TRUE);
		$act = array();
		$output = array();
		$output2 = array();
		$result = $this->db->query("SELECT 
		`staff`.id,
		CONCAT_WS(' ',`staff`.staff_f, `staff`.staff_i, `staff`.staff_o) as fio,
		`staff`.staff_address,
		`staff`.staff_rank,
		`staff`.cio,
		`staff`.staff_location,
		`staff`.staff_birthdate,
		`staff`.staff_staff,
		`staff`.staff_phone,
		`staff`.staff_email,
		`staff`.staff_spec,
		`staff`.staff_edu,
		`staff`.staff_note,
		`staff`.cio,
		`staff`.active
		FROM
		`staff`
		ORDER BY staff.active DESC, staff.cio DESC, fio ASC");
		if($result->num_rows()){
			foreach($result->result_array() as $row){
				$row['class']  = ($row['active'])					? "" : " error";
				$row['class'] .= ($row['cio'])						? " success" : "";
				$row['title']  = ($row['active'])					? "" : "Уволен. ";
				$row['ico']    = ($row['cio'])						? '<img src="/images/cio.png" width="16" height="16" border="0" alt="boss" title="Руководитель">' : "";
				$row['ico']   .= ((int) $row['staff_rank'] == 1)	? '<img src="/images/trainee.png" width="16" height="16" border="0" alt="boss" title="Стажёр">'   : "";
				$row['ico']   .= ((int) $row['staff_rank'] == 2)	? '<img src="/images/medic.png" width="16" height="16" border="0" alt="boss" title="Инструктор">' : "";
				($row['active']) ? array_push($output, $this->load->view('refs/stafftablerow', $row, true)) : array_push($output2, $this->load->view('refs/stafftablerow', $row, true));
			}
			$act['table']			= implode($output, "\n");
			$act['inactivetable']	= implode($output2, "\n");
			return $this->load->view('refs/staff', $act, true);
		}
		return "Данные не найдены. Невозможно построить список сотрудников";
	}

	# Сохранение данных работника
	public function staff_save(){
		$data = array(
			$this->input->post('staff_f', true),
			$this->input->post('staff_i', true),
			$this->input->post('staff_o', true),
			$this->input->post('address', true),
			implode(array_reverse(explode(".", $this->input->post('birth', true))), "-"),
			$this->input->post('staff', true),
			$this->input->post('phone', true),
			$this->input->post('email', true),
			$this->input->post('pass_s', true),
			$this->input->post('pass_n', true),
			implode(array_reverse(explode(".", $this->input->post('pass_issuedate', true))), "-"),
			$this->input->post('pass_issued', true),
			$this->input->post('note', true),
			$this->input->post('inn', true),
			$this->input->post('snils', true),
			$this->input->post('spec', true),
			$this->input->post('location', true),
			$this->input->post('edu', true),
			$this->input->post('staffid', true)
		);
		//$data = $this->toolmodel->encrypt_an_array($data, $this->session->userdata('hash1'));
		//print_r($data);
		//$data = $this->toolmodel->decrypt_an_array($data, $this->session->userdata('hash1'));
		//print_r($data);
		//$this->output->enable_profiler(TRUE);
		//return false;
		$result = $this->db->query("UPDATE
		staff
		SET
		staff.staff_f = TRIM(?),
		staff.staff_i = TRIM(?),
		staff.staff_o = TRIM(?),
		staff.staff_address = TRIM(?),
		staff.staff_birthdate = TRIM(?),
		staff.staff_staff = TRIM(?),
		staff.staff_phone = TRIM(?),
		staff.staff_email = TRIM(?),
		staff.staff_pass_s = TRIM(?),
		staff.staff_pass_n = TRIM(?),
		staff.staff_pass_issuedate = TRIM(?),
		staff.staff_pass_issued = TRIM(?),
		staff.staff_note = TRIM(?),
		staff.staff_inn = TRIM(?),
		staff.staff_snils = TRIM(?),
		staff.staff_spec = TRIM(?),
		staff.staff_location = TRIM(?),
		staff.staff_edu = TRIM(?)
		WHERE
		staff.id = ?", $data);
		if($this->input->post('cbs') && strlen($this->input->post('cbs'))){
			$this->db->query("DELETE FROM servordering WHERE servordering.staffid = ?", array($this->input->post('staffid', true)));
			$cbs = explode(",", $this->input->post('cbs'));
			$services = array();
			foreach($cbs as $val){
				$string = "(".$val.", ".$this->input->post('staffid').")";
				array_push($services, $string);
			}
			$result = $this->db->query("INSERT INTO
			`servordering`(
				`servordering`.servid,
				`servordering`.staffid
			) VALUES".implode($services, ","));
		}
		$this->toolmodel->insert_audit("Сохранён профиль сотрудника ".implode(array($this->input->post('staff_f'), $this->input->post('staff_i'), $this->input->post('staff_o')), " "));
		$this->load->helper("url");
		redirect("refs/staff_edit/".$this->input->post('staffid'));
	}

	# Вставка данных нового работника
	public function staff_item_add(){
		$this->db->trans_start();
		$result = $this->db->query("INSERT INTO 
		staff (
			staff.staff_f,
			staff.staff_i,
			staff.staff_o,
			staff.staff_address,
			staff.staff_birthdate,
			staff.staff_staff,
			staff.staff_phone,
			staff.staff_email,
			staff.staff_spec,
			staff.staff_location,
			staff.staff_pass_s,
			staff.staff_pass_n,
			staff.staff_pass_issuedate,
			staff.staff_pass_issued,
			staff.staff_edu,
			staff.staff_note,
			staff.staff_inn,
			staff.staff_snils,
			staff.staff_rank
		)	VALUES (
			TRIM(?), TRIM(?), TRIM(?), TRIM(?), TRIM(?), TRIM(?), TRIM(?), TRIM(?), TRIM(?),
			TRIM(?), TRIM(?), TRIM(?), TRIM(?), TRIM(?), TRIM(?), TRIM(?), TRIM(?), TRIM(?), TRIM(?)
		)", array(
			$this->input->post('staff_f', true),
			$this->input->post('staff_i', true),
			$this->input->post('staff_o', true),
			$this->input->post('address', true),
			implode(array_reverse(explode(".", $this->input->post('birth', true))), "-"),
			$this->input->post('staff', true),
			$this->input->post('phone', true),
			$this->input->post('email', true),
			$this->input->post('spec', true),
			$this->input->post('location', true),
			$this->input->post('pass_s', true),
			$this->input->post('pass_n', true),
			implode(array_reverse(explode(".", $this->input->post('pass_issuedate', true))), "-"),
			$this->input->post('pass_issued', true),
			$this->input->post('edu', true),
			$this->input->post('note', true),
			$this->input->post('note', true),
			$this->input->post('inn', true),
			$this->input->post('snils', true),
			$this->input->post('rank', true)
		));
		$sID = $this->db->insert_id();
		$this->db->trans_complete();
		// saving serv_permissions
		if($this->input->post('servord') && sizeof($this->input->post('servord'))){
			$services = array();
			foreach($this->input->post('servord') as $val){
				$string = "(".$val.", ".$sID.")";
				array_push($services, $string);
			}
			$result = $this->db->query("INSERT INTO
			`servordering`(
				`servordering`.servid,
				`servordering`.staffid
			) VALUES".implode($services, ","));
		}
		if(
			$this->input->post('username') 
			&& $this->input->post('userpass') 
			&& strlen($this->input->post('username')) 
			&& strlen($this->input->post('userpass'))
		){
			$this->db->query("INSERT INTO users (
			users.login, 
			users.password,
			users.id,
			users.hash1
			) VALUES (?, ?, ?, '1234567890')", array(
				$this->input->post('username'), 
				md5('secret'.$this->input->post('userpass')),
				$sID
			));
		}
		$this->toolmodel->insert_audit("Добавлен профиль сотрудника ".implode(array($this->input->post('staff_f'), $this->input->post('staff_i'), $this->input->post('staff_o')), " ")." присвоен ID:".$sID);
		$this->load->helper("url");
		//$this->output->enable_profiler(TRUE);
		redirect("refs/staff");
	}
	#### STAFF CONTROL Section END
	###########################################################################

	## SUPPLIERS CONTROL Section
	# Получение данных по поставщику
	public function supp_edit($id){
		$result = $this->db->query("SELECT 
		`suppliers`.sid,
		`suppliers`.supp_f,
		`suppliers`.supp_i,
		`suppliers`.supp_o,
		`suppliers`.supp_orgname,
		`suppliers`.supp_staff,
		`suppliers`.supp_phone,
		`suppliers`.supp_email,
		`suppliers`.supp_note,
		`suppliers`.supp_address,
		(`suppliers`.supp_royalty /100) AS supp_royalty,
		`suppliers`.active
		FROM
		`suppliers`
		WHERE `suppliers`.sid = ?", array( $id ));
		if($result->num_rows()){
			$row = $result->row();
		}
		return $this->load->view('refs/supplieredit', $row, true);
	}
	# Получение данных счёта поставщика
	public function supp_billing_get($sid){
		//$this->output->enable_profiler(TRUE);
		$output = array();
		$result = $this->db->query("SELECT 
		CONCAT_WS(' ', `suppliers`.supp_f, `suppliers`.supp_i, `suppliers`.supp_o) AS fio,
		`suppliers`.supp_phone,
		(`suppliers`.supp_royalty / 100) AS supp_royalty
		FROM
		`suppliers`
		WHERE
		`suppliers`.`sid` = ?", array($sid));
		if($result->num_rows()){
			$row = $result->row();
			array_push($output, '<h3>'.$row->fio.'&nbsp;&nbsp;&nbsp;&nbsp;<small>тел. '.$row->supp_phone.'</small></h3><hr>Размер вознаграждения: <strong>'.$row->supp_royalty.'%</strong><br><br>');
		}
		$result = $this->db->query("SELECT 
		CONCAT_WS(' ', DATE_FORMAT(service_calendar.ordered_date, '%d.%m.%Y'), DATE_FORMAT(service_calendar.ordered_time, '%H:%i')) AS dtm,
		service_calendar.contract_id,
		TRUNCATE((service_calendar.pricefixed / 10000), 2) AS pf,
		service_calendar.is_done,
		contracts.cont_number,
		contracts.active AS cont_active,
		CONCAT_WS(' - ', DATE_FORMAT(contracts.cont_date_start, '%d.%m.%Y'), DATE_FORMAT(contracts.cont_date_end, '%d.%m.%Y')) AS ctm,
		service_calendar.done_by,
		services.serv_name,
		services.active AS serv_active,
		services.m_active
		FROM
		service_calendar
		LEFT OUTER JOIN contracts   ON (service_calendar.contract_id = contracts.id)
		LEFT OUTER JOIN courses     ON (contracts.crsid = courses.id)
		LEFT OUTER JOIN services    ON (service_calendar.contract_id = services.id)
		LEFT OUTER JOIN `patients`  ON (courses.pid = `patients`.id)
		LEFT OUTER JOIN `suppliers` ON (`patients`.pat_directed_by = `suppliers`.sid)
		WHERE
		(`suppliers`.`sid` = ?)
		ORDER BY
		service_calendar.ordered_date", array($sid));
		$this->load->model("crsmodel");
		array_push($output, $this->crsmodel->report_serv($result, 1, $row->supp_royalty));
		return implode($output, "\n");
	}
	# Извлечение списка поставщиков
	public function supp_list_get(){
		//$this->output->enable_profiler(TRUE);
		$act = array();
		$output = array();
		$result = $this->db->query("SELECT 
		CONCAT_WS(' ', `suppliers`.supp_f, `suppliers`.supp_i, `suppliers`.supp_o) as fio,
		`suppliers`.supp_orgname,
		`suppliers`.supp_staff,
		`suppliers`.supp_phone,
		`suppliers`.supp_email,
		`suppliers`.supp_address,
		`suppliers`.active,
		`suppliers`.sid
		FROM
		`suppliers`
		ORDER BY `suppliers`.active DESC, `suppliers`.supp_orgname, fio");
		if($result->num_rows()){
			foreach($result->result() as $row){
				$class = ($row->active) ? "" : " error";
				$string = '<tr class="'.$class.'" ref="'.$row->sid.'" title="Поставщики">
					<td class="supprow"><a href="/refs/supp_edit/'.$row->sid.'">'.$row->fio.'</a><br><small class="muted">'.$row->supp_orgname.'</small></td>
					<td class="supprow">'.$row->supp_staff.'</td>
					<td class="supprow">'.$row->supp_address.'</td>
					<td class="supprow">'.$row->supp_phone.'<br>'.$row->supp_email.'</td>
					<td style="vertical-align:middle;text-align:center;">
					<a href="/refs/suppliers_billing/'.$row->sid.'" class="btn btn-primary btn-mini" title="Детализация счёта поставщика"><i class="icon-signal icon-white"></i> Детализация счёта</a>
					</td>
				</tr>';
				array_push($output, $string);
			}
		}
		$act['table'] = implode($output, "\n");
		return $this->load->view('refs/suppliers', $act, true);
	}
	# сохранение поставщика
	public function supp_save(){
		$result = $this->db->query("UPDATE
		suppliers
		SET
		suppliers.supp_f = TRIM(?),
		suppliers.supp_i = TRIM(?),
		suppliers.supp_o = TRIM(?),
		suppliers.supp_orgname = TRIM(?),
		suppliers.supp_staff = TRIM(?),
		suppliers.supp_phone = TRIM(?),
		suppliers.supp_email = TRIM(?),
		suppliers.supp_address = TRIM(?),
		suppliers.supp_note = TRIM(?),
		suppliers.supp_royalty = TRIM(?)
		WHERE `suppliers`.`sid` = ?" , array(
			$this->input->post('supp_f', true),
			$this->input->post('supp_i', true),
			$this->input->post('supp_o', true),
			$this->input->post('org', true),
			$this->input->post('staff', true),
			$this->input->post('phone', true),
			$this->input->post('email', true),
			$this->input->post('address', true),
			$this->input->post('note', true),
			$this->input->post('royalty', true) * 100,
			$this->input->post('suppid', true)
		));
		$this->load->helper("url");
		//$this->output->enable_profiler(TRUE);
		redirect("refs/suppliers");
	}
	# Вставка данных нового поставщика
	public function supp_item_add(){
		$result = $this->db->query("INSERT INTO
		suppliers (
			suppliers.supp_f,
			suppliers.supp_i,
			suppliers.supp_o,
			suppliers.supp_orgname,
			suppliers.supp_staff,
			suppliers.supp_phone,
			suppliers.supp_email,
			suppliers.supp_address,
			suppliers.supp_note,
			suppliers.supp_royalty
		) VALUES (TRIM(?), TRIM(?), TRIM(?), TRIM(?), TRIM(?), TRIM(?), TRIM(?), TRIM(?), TRIM(?), TRIM(?) )" , array(
			$this->input->post('supp_f'  , true),
			$this->input->post('supp_i'  , true),
			$this->input->post('supp_o'  , true),
			$this->input->post('org'     , true),
			$this->input->post('staff'   , true),
			$this->input->post('phone'   , true),
			$this->input->post('email'   , true),
			$this->input->post('address' , true),
			$this->input->post('note'    , true),
			$this->input->post('royalty' , true) * 100
		));
		$this->load->helper("url");
		//$this->output->enable_profiler(TRUE);
		redirect("refs/suppliers");
	}
	## SUPPLIERS CONTROL Section END
	############################################################################

	## CLIENTS CONTROL Section
	# Получение данных по клиентам
	public function client_item_get(){
		$output = "clientdata = {}";
		$result = $this->db->query("SELECT 
		`clients`.id,
		`clients`.cli_f,
		`clients`.cli_i,
		`clients`.cli_o,
		`clients`.cli_address,
		`clients`.cli_pass_s,
		`clients`.cli_pass_n,
		`clients`.cli_pass_issued,
		DATE_FORMAT(`clients`.cli_pass_issuedate, '%d.%m.%Y') AS cli_pass_issuedate,
		`clients`.cli_note,
		`clients`.directed_by,
		`clients`.cli_cphone,
		`clients`.cli_hphone,
		`clients`.cli_mail,
		`clients`.active
		FROM
		`clients`
		WHERE `clients`.id = ?", array( (int) $this->input->post("clientid")));
		if($result->num_rows()){
			$row = $result->row();
			$output = "clientdata = {
			id: '".$row->id."',
			client_f: '".$row->cli_f."',
			client_i: '".$row->cli_i."',
			client_o: '".$row->cli_o."',
			address: '".$row->cli_address."',
			pass_s: '".$row->cli_pass_s."',
			pass_n: '".$row->cli_pass_n."',
			pass_issued: '".$row->cli_pass_issued."',
			pass_issuedate: '".$row->cli_pass_issuedate."',
			note: '".$row->cli_note."',
			directed: '".$row->directed_by."',
			cphone: '".$row->cli_cphone."',
			hphone: '".$row->cli_hphone."',
			email: '".$row->cli_mail."',
			active: ".$row->active."
			};\n";
		}
		return $output;
		//return $output."dirlist = '".implode($directed_list, "")."'";
	}

	# Извлечение списка клиентов
	public function clients_list_get(){
		//$this->output->enable_profiler(TRUE);
		$act = array();
		$output   = array();
		$outputinactive  = array();
		$patients = array();
		$result = $this->db->query("SELECT 
		CONCAT_WS(' ',`patients`.pat_f, `patients`.pat_i, `patients`.pat_o) AS fio,
		`patients`.id,
		`patients`.pat_clientid
		FROM
		`patients`
		WHERE `patients`.`active`");
		if($result->num_rows()){
			foreach($result->result() as $row){
				if(!isset($patients[$row->pat_clientid])){
					$patients[$row->pat_clientid] = array();
				}
				$string = '<li>
					<a title="Открыть пациента" href="/refs/pat_edit/'.$row->id.'" target="_blank" class="patrow" ref="'.$row->id.'">'.$row->fio.'</a>
				</li>';
				array_push($patients[$row->pat_clientid], $string);
			}
		}
		$result = $this->db->query("SELECT 
		clients.id,
		CONCAT_WS(' ', clients.cli_f, clients.cli_i, clients.cli_o) AS fio,
		clients.cli_address,
		clients.cli_card,
		clients.cli_cphone,
		clients.cli_hphone,
		clients.cli_mail,
		clients.active,
		clients.cli_datafull,
		CONCAT(suppliers.supp_f, ' ', LEFT(suppliers.supp_i, 1),'.', LEFT(suppliers.supp_o, 1), ', ', suppliers.supp_orgname) AS supplier
		FROM
		clients
		LEFT OUTER JOIN suppliers ON (clients.directed_by = suppliers.sid)
		ORDER BY
		clients.active DESC, fio");
		if($result->num_rows()){
			foreach($result->result() as $row){
				$datafull = ($row->cli_datafull) ? '<img src="/images/tick.png" width="16" height="16" border="0" alt="">' : '<img src="/images/docerror.png" title="Данные клиента неполны для оформления контракта" width="16" height="16" border="0" alt="">';
				$is_blocked = ($row->active) ? "": " error";
				$string = '<tr class="'.$is_blocked.'">
						<td colspan=6>
							<a name="c'.$row->id.'"></a><strong>'.$row->fio.' [&nbsp;'.$row->cli_card.'&nbsp;]</strong>
						</td>
					</tr>
					<tr class="'.$is_blocked.'" title="Клиенты">
					<td class="clientrow" ref="'.$row->id.'">
						Пациенты:
						<ul>'.(isset($patients[$row->id]) ? implode($patients[$row->id]): '').'</ul>
					</td>
					<td class="clientrow" ref="'.$row->id.'">'.$row->supplier.'</td>
					<td class="clientrow" ref="'.$row->id.'">'.$row->cli_address.'</td>
					<td class="clientrow" ref="'.$row->id.'">
						моб: '.$row->cli_cphone.'<br>дом: '.$row->cli_hphone.'<br>email: '.$row->cli_mail.'
					</td>
					<td class="clientrow fullycentered" ref="'.$row->id.'">'.$datafull.'</td>
					<td class="fullycentered">
						<!-- <button class="btn btn-success btn-mini clientEdit" ref="'.$row->id.'" title="Редактировать данные"><i class="icon-edit icon-white"></i></button> -->
						<a class="btn btn-success btn-mini" href="/refs/client_edit/'.$row->id.'" title="Редактировать данные"><i class="icon-edit icon-white"></i></a>
					</td>
				</tr>';
				($row->active) ? array_push($output, $string) : array_push($outputinactive, $string);
			}
		}
		$act['table'] = implode($output, "\n");
		$act['tableinactive'] = implode($outputinactive, "\n");
		return $this->load->view('refs/clients', $act, true);
	}
	
	public function client_edit($item = 0){
		//print $item;
		//$this->output->enable_profiler(TRUE);
		$result = $this->db->query("SELECT 
		`clients`.id,
		`clients`.cli_f,
		`clients`.cli_i,
		`clients`.cli_o,
		`clients`.cli_card,
		`clients`.cli_address,
		`clients`.cli_sib,
		`clients`.cli_cphone,
		`clients`.cli_hphone,
		`clients`.cli_mail,
		`clients`.cli_pass_s,
		`clients`.cli_pass_n,
		`clients`.cli_pass_issued,
		DATE_FORMAT(`clients`.cli_pass_issuedate, '%d.%m.%Y') as cli_pass_issuedate,
		`clients`.cli_note,
		`clients`.cli_datafull,
		`clients`.active
		FROM
		`clients`
		WHERE 
		`clients`.id = ?", array($item));
		if($result->num_rows()){
			$clidata = $result->row_array();
		}
		$clidata['actbtn'] = ($clidata['active']) 
			? '<a class="btn btn-danger"  href="/refs/client_deactivate/'.$clidata['id'].'">Сделать клиента неактивным</a>'
			: '<a class="btn btn-warning" href="/refs/client_activate/'.$clidata['id'].'">Сделать клиента активным</a>';

		//print_r($servdata);
		return $this->load->view('refs/clientedit', $clidata, true);
	}

	# сохранение клиента
	public function client_save(){
		$result = $this->db->query("UPDATE
		clients
		SET
		clients.cli_f = TRIM(?),
		clients.cli_i = TRIM(?),
		clients.cli_o = TRIM(?),
		clients.cli_pass_s = TRIM(?),
		clients.cli_pass_n = TRIM(?),
		clients.cli_pass_issued = TRIM(?),
		clients.cli_pass_issuedate = TRIM(?),
		clients.cli_cphone = TRIM(?),
		clients.cli_hphone = TRIM(?),
		clients.cli_mail = TRIM(?),
		clients.cli_address = TRIM(?),
		clients.cli_note = TRIM(?),
		clients.cli_card = TRIM(?)
		WHERE `clients`.`id` = ?" , array(
			$this->input->post('client_f', true),
			$this->input->post('client_i', true),
			$this->input->post('client_o', true),
			$this->input->post('pass_s', true),
			$this->input->post('pass_n', true),
			$this->input->post('pass_issued', true),
			implode(array_reverse(explode(".", $this->input->post('pass_issuedate', true))), "-"),
			$this->input->post('cphone', true),
			$this->input->post('hphone', true),
			$this->input->post('email', true),
			$this->input->post('address', true),
			$this->input->post('note', true),
			$this->input->post('card', true),
			$this->input->post('clientid', true)
		));
		// проверка на целостность
		$this->datafull_c_check($this->input->post('clientid', true));

		if($this->input->post('ajax') == 'applied'){
			print "saved";
		}else{
			$this->load->helper("url");
			//$this->output->enable_profiler(TRUE);
			redirect("refs/clients");
		}
	}

	public function datafull_c_check($cid){
		$this->db->query("UPDATE clients SET 
		clients.cli_datafull = IF(
		LENGTH(clients.cli_f) AND
		LENGTH(clients.cli_i) AND
		LENGTH(clients.cli_o) AND
		LENGTH(clients.cli_pass_s) AND
		LENGTH(clients.cli_pass_n) AND
		LENGTH(clients.cli_pass_issued) AND
		LENGTH(clients.cli_pass_issuedate) AND
		LENGTH(clients.cli_cphone) AND
		LENGTH(clients.cli_hphone) AND
		LENGTH(clients.cli_mail) AND
		LENGTH(clients.cli_address) AND
		LENGTH(clients.cli_note),
		1, 0) WHERE clients.id = ?", array($cid));
	}

	# Вставка данных нового клиента
	public function client_item_add(){
		$result = $this->db->query("INSERT INTO
		clients (
			clients.cli_f,
			clients.cli_i,
			clients.cli_o,
			clients.cli_pass_s,
			clients.cli_pass_n,
			clients.cli_pass_issued,
			clients.cli_pass_issuedate,
			clients.cli_cphone,
			clients.cli_hphone,
			clients.cli_mail,
			clients.cli_address,
			clients.cli_note
		) VALUES (TRIM(?), TRIM(?), TRIM(?), TRIM(?), TRIM(?), TRIM(?), TRIM(?), TRIM(?), TRIM(?), TRIM(?), TRIM(?), TRIM(?))" , array(
			$this->input->post('client_f', true),
			$this->input->post('client_i', true),
			$this->input->post('client_o', true),
			$this->input->post('pass_s', true),
			$this->input->post('pass_n', true),
			$this->input->post('pass_issued', true),
			implode(array_reverse(explode(".", $this->input->post('pass_issuedate', true))), "-"),
			$this->input->post('cphone', true),
			$this->input->post('hphone', true),
			$this->input->post('email', true),
			$this->input->post('address', true),
			$this->input->post('note', true)
		));
		$cid = $this->db->insert_id();
		$this->datafull_c_check($cid);
		if($this->input->post('ajax') == 'applied'){
			print $cid;
		}else{
			$this->load->helper("url");
			redirect("refs/clients");
		}
	}
	## CLIENTS CONTROL Section END
	############################################################################

	## PATIENTS CONTROL Section
	# Получение данных по клиентам
	public function patients_list_get(){
		//$this->output->enable_profiler(TRUE);
		$act = array();
		$output = array();
		$output2 = array();
		$result = $this->db->query("SELECT
		patients.id,
		clients.id AS client_id,
		suppliers.sid AS supp_id,
		CONCAT_WS(' ', patients.pat_f, patients.pat_i, patients.pat_o) AS fio,
		CONCAT_WS(' ', `suppliers`.supp_f, `suppliers`.supp_i, `suppliers`.supp_o) AS supp_fio,
		CONCAT_WS(' ', clients.cli_f, clients.cli_i, clients.cli_o) AS cli_fio,
		(SELECT COUNT(`courses`.id) FROM `courses` WHERE `courses`.`pid` = `patients`.`id` AND `courses`.`active`) AS crscount,
		patients.datafull,
		patients.active,
		patients.pat_address,
		patients.pat_directed_by,
		patients.pat_location,
		clients.cli_hphone,
		clients.cli_cphone,
		clients.cli_mail
		FROM
		patients
		LEFT OUTER JOIN clients ON (patients.pat_clientid = clients.id)
		LEFT OUTER JOIN `suppliers` ON (patients.pat_directed_by = `suppliers`.sid)
		ORDER BY 
		patients.active DESC,
		fio");
		if($result->num_rows()){
			foreach($result->result_array() as $row){
				$row['supplier'] = (strlen($row['supp_id'])) ? $row['supp_fio'] : "Поставщик нуждается в уточнении";
				$row['is_blocked'] = ($row['active']) ? "" : " error" ;
				$row['errors'] = ($row['datafull']) ? '<img src="/images/tick.png" width="16" height="16" border="0" alt="">' : '<img src="/images/error.png" width="16" height="16" border="0" alt="" title="Данные неполны. Проверьте карточки пациента и клиента">' ;
				$row['errors'] .= ($row['crscount']) ? '' : '&nbsp;<img src="/images/docerror.png" width="16" height="16" border="0" alt="" class="createCrs" ref="'.$row['id'].'" style="cursor:pointer;" title="Курс не сопоставлен. Создание договоров невозможно.">';
				($row['active']) ? array_push($output, $this->load->view('refs/patientdata', $row, true)) : array_push($output2, $this->load->view('refs/patientdata', $row, true));
			}
		}
		$act['table'] = implode($output, "\n");
		$act['tableinactive'] = implode($output2, "\n");
		return $this->load->view('refs/patients', $act, true);
	}

	public function pat_item_get(){
		$output = "patdata = {}";
		$result = $this->db->query("SELECT 
		patients.id,
		patients.pat_f,
		patients.pat_i,
		patients.pat_o,
		patients.pat_address,
		patients.pat_location,
		DATE_FORMAT(patients.pat_birthdate, '%d.%m.%Y') AS pat_birthdate,
		IF(ISNULL(patients.pat_birthdate),
			patients.pat_age,
			YEAR(NOW()) - YEAR(patients.pat_birthdate) - (
				IF(DATE_FORMAT(NOW(), '%m%d') >= DATE_FORMAT(patients.pat_birthdate, '%m%d'), 0, 1)
			)
		) as pat_age,
		REPLACE(REPLACE(patients.pat_info, '\r', ''), '\n', ' ') AS pat_info,
		patients.pat_directed_by,
		patients.pat_clientid,
		DATE_FORMAT(`patients`.pat_pass_issuedate, '%d.%m.%Y') AS pat_pass_issuedate,
		`patients`.pat_pass_issued,
		`patients`.pat_pass_n,
		`patients`.pat_pass_s,
		`patients`.active,
		`patients`.pat_cphone as cli_cphone,
		`patients`.pat_hphone as cli_hphone,
		REPLACE(REPLACE(patients.pat_diagnosis, '\r', ''), '\n', ' ') AS pat_diagnosis
		FROM
		patients
		LEFT OUTER JOIN `clients` ON (patients.pat_clientid = `clients`.id)
		WHERE `patients`.id = ?", array( (int) $this->input->post("patid")));
		if($result->num_rows()){
			$row = $result->row();
			$birth = (strlen($row->pat_birthdate)) ? $row->pat_birthdate : "Дата рождения не указана";
			$output = "patdata = {
			id: '".$row->id."',
			pat_f: '".$row->pat_f."',
			pat_i: '".$row->pat_i."',
			pat_o: '".$row->pat_o."',
			pass_s: '".$row->pat_pass_s."',
			pass_n: '".$row->pat_pass_n."',
			pass_issued: '".$row->pat_pass_issued."',
			pass_issuedate: '".$row->pat_pass_issuedate."',
			address: '".$row->pat_address."',
			location: '".$row->pat_location."',
			cphone: '".$row->cli_cphone."',
			hphone: '".$row->cli_hphone."',
			info: '".$row->pat_info."',
			diagnosis: '".$row->pat_diagnosis."',
			birth: '".$birth."',
			age: '".$row->pat_age."',
			directed: '".((strlen($row->pat_directed_by)) ? $row->pat_directed_by : 0)."',
			client: '".((strlen($row->pat_clientid)) ? $row->pat_clientid : 0)."',
			active: '".$row->active."'
			};\n";

			$directed_by = (strlen($row->pat_directed_by)) ? $row->pat_directed_by : 0;
			$clientid = (strlen($row->pat_clientid)) ? $row->pat_clientid : 0;
		}
		//return $output;
		return $output.$this->pat_lists_get();
	}

	public function pat_lists_get(){
		$directed_list = array();
		$clients_list = array();
		$result = $this->db->query("SELECT 
		suppliers.supp_orgname,
		CONCAT_WS(' ', suppliers.supp_f, suppliers.supp_i, suppliers.supp_o) AS fio,
		suppliers.sid
		FROM
		suppliers
		WHERE suppliers.active
		ORDER BY fio");
		if($result->num_rows()){
			foreach($result->result() as $row2){
				$sel = ($row2->sid == $this->input->post("sup")) ? ' selected="selected"' : "" ;
				$string = '<option value="'.$row2->sid.'" title="'.$row2->supp_orgname.' - '.$row2->fio.'"'.$sel.'>'.$row2->fio.'</option>';
				array_push($directed_list, $string);
			}
		}
		$clients_list = array();
		$result = $this->db->query("SELECT 
		CONCAT_WS(' ', clients.cli_f, clients.cli_i, clients.cli_o) AS fio,
		clients.id
		FROM
		clients
		WHERE clients.active
		ORDER BY fio");
		if($result->num_rows()){
			foreach($result->result() as $row2){
				$sel = ($row2->id == $this->input->post("cli")) ? ' selected="selected"' : "" ;
				$string = '<option value="'.$row2->id.'"'.$sel.'>'.$row2->fio.'</option>';
				array_push($clients_list, $string);
			}
		}
		return "dirlist = '".implode($directed_list, "")."';\ncl_list = '".implode($clients_list, "")."'";
	}

	public function pat_edit($item){
		$result = $this->db->query("SELECT 
		patients.id,
		patients.pat_f,
		patients.pat_i,
		patients.pat_o,
		patients.pat_address,
		patients.pat_location,
		DATE_FORMAT(patients.pat_birthdate, '%d.%m.%Y') AS pat_birthdate,
		IF(ISNULL(patients.pat_birthdate),
			patients.pat_age,
			YEAR(NOW()) - YEAR(patients.pat_birthdate) - (
				IF(DATE_FORMAT(NOW(), '%m%d') >= DATE_FORMAT(patients.pat_birthdate, '%m%d'), 0, 1)
			)
		) as pat_age,
		REPLACE(REPLACE(patients.pat_info, '\r', ''), '\n', ' ') AS pat_info,
		patients.pat_directed_by,
		patients.pat_clientid,
		DATE_FORMAT(`patients`.pat_pass_issuedate, '%d.%m.%Y') AS pat_pass_issuedate,
		`patients`.pat_pass_issued,
		`patients`.pat_pass_n,
		`patients`.pat_pass_s,
		`patients`.active,
		`patients`.pat_cphone,
		`patients`.pat_hphone,
		`clients`.cli_mail,
		REPLACE(REPLACE(patients.pat_diagnosis, '\r', ''), '\n', ' ') AS pat_diagnosis
		FROM
		patients
		LEFT OUTER JOIN `clients` ON (patients.pat_clientid = `clients`.id)
		WHERE `patients`.id = ?", array((int)$item));
		if($result->num_rows()){
			$row = $result->row_array();
			$suppliers = array();
			$result = $this->db->query("SELECT 
			suppliers.sid,
			suppliers.supp_f,
			suppliers.supp_i,
			suppliers.supp_o
			FROM
			suppliers
			ORDER BY CONCAT(suppliers.supp_f, suppliers.supp_i, suppliers.supp_o)");
			if($result->num_rows()){
				foreach($result->result() as $row2){
					//print $row['pat_directed_by']." == ".$row2->sid."<br>";
					$string = '<option'.(($row['pat_directed_by'] == $row2->sid) ? ' selected="selected"' : '').' value="'.$row2->sid.'">'.implode(array($row2->supp_f, $row2->supp_i, $row2->supp_o), " ").'</option>';
					array_push($suppliers, $string);
				}
			}
			$row['supp'] = implode($suppliers, "\n");

			$clients = array();
			$result  = $this->db->query("SELECT 
			`clients`.cli_f,
			`clients`.cli_i,
			`clients`.cli_o,
			`clients`.id
			from
			`clients`
			ORDER BY CONCAT(`clients`.cli_f,`clients`.cli_i,`clients`.cli_o)");
			if($result->num_rows()){
				foreach($result->result() as $row2){
					//print $row['pat_directed_by']." == ".$row2->sid."<br>";
					$string = '<option'.(($row['pat_clientid'] == $row2->id) ? ' selected="selected"' : '').' value="'.$row2->id.'">'.implode(array($row2->cli_f, $row2->cli_i, $row2->cli_o), " ").'</option>';
					array_push($clients, $string);
				}
			}
			$row['cli'] = implode($clients, "\n");
			$row['actbtn'] = ($row['active']) 
				? '<a id="patActivator" class="btn btn-danger"  title="Снять пациента с учёта" href="/refs/pat_deactivate/'.$row['id'].'">Снять с учёта</a>'
				: '<a id="patActivator" class="btn btn-warning" title="Пометить активной административно" href="/refs/pat_activate/'.$row['id'].'">Вернуть на учёт</a>';
			return $this->load->view('refs/patientedit', $row, true);
		}else{
			return "<h3>Такого пациента не зайдено!</h3>";
		}
	}

	public function pat_save(){
		//$this->output->enable_profiler(TRUE);
		$result = $this->db->query("UPDATE
		`patients`
		SET
		patients.pat_f = TRIM(?),
		patients.pat_i = TRIM(?),
		patients.pat_o = TRIM(?),
		patients.pat_clientid = ?,
		patients.pat_directed_by = ?,
		patients.pat_address = TRIM(?),
		patients.pat_birthdate = TRIM(?),
		patients.pat_diagnosis = TRIM(?),
		patients.pat_info = TRIM(?),
		patients.pat_location = TRIM(?),
		patients.pat_pass_s = TRIM(?),
		patients.pat_pass_n = TRIM(?),
		patients.pat_pass_issued = TRIM(?),
		patients.pat_pass_issuedate = TRIM(?),
		patients.pat_cphone = TRIM(?),
		patients.pat_hphone = TRIM(?),
		patients.pat_complaints = TRIM(?)
		WHERE
		patients.id = ?", array(
			$this->input->post('pat_f', true),
			$this->input->post('pat_i', true),
			$this->input->post('pat_o', true),
			$this->input->post('clientid', true),
			$this->input->post('directed', true),
			$this->input->post('address', true),
			implode(array_reverse(explode(".", $this->input->post('birth', true))), "-"),
			$this->input->post('diagnosis', true),
			$this->input->post('info', true),
			$this->input->post('location', true),
			$this->input->post('pass_s', true),
			$this->input->post('pass_n', true),
			$this->input->post('pass_issued', true),
			implode(array_reverse(explode(".", $this->input->post('pass_issuedate', true))), "-"),
			$this->input->post('cphone', true),
			$this->input->post('hphone', true),
			$this->input->post('complaints', true),
			$this->input->post('patid', true)
		));
		$this->datafull_p_check($this->input->post('patid'));
		//print "x,";
		if($this->input->post('ajax') == 'applied'){
			$result = $this->db->query("SELECT 
			CONCAT_WS(',', clients.cli_datafull, patients.datafull) AS df
			FROM
			patients
			INNER JOIN clients ON (patients.pat_clientid = clients.id)
			WHERE
			`patients`.`id` = ?", array($this->input->post('patid')));
			if($result->num_rows()){
				foreach($result->result() as $row){
					print $row->df.",";
				}
			}
			print $this->input->post('patid');
		}else{
			$this->load->helper("url");
			//$this->output->enable_profiler(TRUE);
			redirect("refs/patients");
		}
	}

	public function pat_item_add(){
		$this->db->trans_start();
		$this->db->query("INSERT INTO
		`patients` (
			patients.pat_f,
			patients.pat_i,
			patients.pat_o,
			patients.pat_clientid,
			patients.pat_directed_by,
			patients.pat_address,
			patients.pat_birthdate,
			patients.pat_diagnosis,
			patients.pat_info,
			patients.pat_location,
			patients.pat_pass_s,
			patients.pat_pass_n,
			patients.pat_pass_issued,
			patients.pat_pass_issuedate,
			patients.pat_complaints
		) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array(
			$this->input->post('pat_f', true),
			$this->input->post('pat_i', true),
			$this->input->post('pat_o', true),
			$this->input->post('clientid', true),
			$this->input->post('directed', true),
			$this->input->post('address', true),
			implode(array_reverse(explode(".", $this->input->post('birth', true))), "-"),
			$this->input->post('diagnosis', true),
			$this->input->post('note', true),
			$this->input->post('location', true),
			$this->input->post('pass_s', true),
			$this->input->post('pass_n', true),
			$this->input->post('pass_issued', true),
			implode(array_reverse(explode(".", $this->input->post('pass_issuedate', true))), "-"),
			$this->input->post('complaints', true),
		));
		$pid = $this->db->insert_id();
		// проверка на целостность данных
		$this->datafull_p_check($pid);
		// добавление курса лечения
		print $this->add_p_course($pid).",";
		$this->db->trans_complete();

		if($this->input->post('ajax') == 'applied'){
			$result = $this->db->query("SELECT 
			CONCAT_WS( ',', clients.cli_datafull, patients.datafull) AS df
			FROM
			patients
			INNER JOIN clients ON (patients.pat_clientid = clients.id)
			WHERE
			`patients`.`id` = ?", array($pid));
			if($result->num_rows()){
				foreach($result->result() as $row){
					print $row->df.",";
				}
			}
			print $pid;
		}else{
			$this->load->helper("url");
			//$this->output->enable_profiler(TRUE);
			redirect("refs/patients");
		}
	}

	public function datafull_p_check($pid){
		$this->db->query("UPDATE patients SET 
		patients.datafull = IF(
			LENGTH(patients.pat_f) AND
			LENGTH(patients.pat_i) AND
			LENGTH(patients.pat_o) AND
			LENGTH(patients.pat_clientid) AND
			LENGTH(patients.pat_address) AND
			LENGTH(patients.pat_pass_s) AND
			LENGTH(patients.pat_pass_n) AND
			LENGTH(patients.pat_pass_issued) AND
			LENGTH(patients.pat_pass_issuedate),
		1, 0) WHERE patients.id = ?", array($pid));
	}

	public function add_p_course($pid){
		$this->db->query("INSERT INTO
		`courses`(
			pid,
			startdate,
			active
		) VALUES (?, NOW(), 1)", array($pid));
		return $this->db->insert_id();
	}

	## PATIENTS CONTROL Section END
	############################################################################

	## SERVICES CONTROL Section
	# Получение данных по услугам
	public function serv_list_get(){
		//$this->output->enable_profiler(TRUE);
		$act = array();
		$output = array();
		$output2 = array();
		$output3 = array();
		$result = $this->db->query("SELECT 
		`services`.serv_name,
		`services`.id,
		`services`.serv_alias,
		`services`.serv_desc,
		`services`.serv_loc,
		`services`.serv_price / 100 as serv_price,
		`services`.serv_price_trainee / 100 as serv_price_trainee,
		`services`.serv_price_instructor / 100 as serv_price_instructor,
		`services`.datafull,
		`services`.active,
		`services`.m_active
		FROM
		`services`
		ORDER BY
		`services`.active DESC, `services`.serv_name");
		if($result->num_rows()){
			foreach($result->result_array() as $row){
				if($row['serv_loc'] == "center"){
					$row['location'] = "В условиях реабилитационного центра";
				}else{
					$row['location'] = "На дому у клиента/пациента";
				}
				$row['title'] = "";
				$row['datafull'] = ($row['datafull']) 
					? '<img src="/images/tick.png" width="16" height="16" border="0" alt="">' 
					: '<img src="/images/error.png" width="16" height="16" border="0" alt="">' ;

				$row['activeness'] = ($row['m_active']) ? "" : "error" ;
				$row['title'] .= ($row['m_active']) ? "Услуга оказывается" : "Услуга не оказывается" ;
				$row['title'] .= ($row['active']) ? "" : " Нет персонала" ;
				$row['staffneeded'] = ($row['active']) ? "" : '<img title="Для этой услуги отсутствует персонал" src="/images/user_delete.png" style="width:16px;height:16px;border:none alt="">';
				if(!$row['m_active']){
					array_push($output3, $this->load->view('refs/servtablerow', $row, true));
					continue;
				}
				if(!$row['active']){
					array_push($output2, $this->load->view('refs/servtablerow', $row, true));
					continue;
				}

				array_push($output, $this->load->view('refs/servtablerow', $row, true));
			}
		}
		$act['table'] = implode($output, "\n");
		$act['inactivetable'] = implode($output2, "\n");
		$act['minactivetable'] = implode($output3, "\n");
		return $this->load->view('refs/services', $act, true);
	}

	public function serv_item_get(){
		$output = "servdata = {}";
		$result = $this->db->query("SELECT 
		`services`.serv_name,
		`services`.id,
		`services`.serv_alias,
		`services`.serv_desc,
		`services`.serv_loc,
		`services`.serv_price,
		`services`.serv_price_trainee,
		`services`.serv_price_instructor,
		`services`.active
		FROM
		`services`
		WHERE `services`.id = ?", array($this->input->post("servid")));
		if($result->num_rows()){
			$row = $result->row();
			$output = "servdata = {
			id:       '".$row->id."',
			name:     '".$row->serv_name."',
			alias:    '".$row->serv_alias."',
			location: '".$row->serv_loc."',
			price:    '".mb_substr($row->serv_price, 0, -2)."',
			price_k:  '".mb_substr($row->serv_price, -2)."',
			price_t:  '".mb_substr($row->serv_price_trainee, 0, -2)."',
			price_tk: '".mb_substr($row->serv_price_trainee, -2)."',
			price_i:  '".mb_substr($row->serv_price_instructor, 0, -2)."',
			price_ik: '".mb_substr($row->serv_price_instructor, -2)."',
			info:     '".$row->serv_desc."',
			active:   '".$row->active."'
			};\n";
		}
		//return $output;
		return $output;
	}

	public function serv_save(){
		//$this->output->enable_profiler(TRUE);
		$result = $this->db->query("UPDATE
		services
		SET
		services.serv_name = ?,
		services.serv_alias = ?,
		services.serv_desc = ?,
		services.serv_loc = ?,
		services.serv_price = ?,
		services.serv_price_trainee = ?,
		services.serv_price_instructor = ?
		WHERE
		id = ?", array(
			$this->input->post('name',     true),
			$this->input->post('alias',    true),
			$this->input->post('info',     true),
			$this->input->post('location', true),
			($this->input->post('price',    true) * 100) + $this->input->post('price_k',  true),
			($this->input->post('price_t',  true) * 100) + $this->input->post('price_tk', true),
			($this->input->post('price_i',  true) * 100) + $this->input->post('price_ik', true),
			$this->input->post('servid',   true)
		));
		if($this->input->post("servord") && sizeof($this->input->post("servord"))){
			$ordering = array();
			foreach($this->input->post("servord") as $val){
				array_push($ordering, "(".$this->input->post('servid',   true).",".$val.")");
			}
			$this->db->query("DELETE FROM servordering WHERE servordering.servid = ?", array($this->input->post('servid', true)));
			if(sizeof($ordering)){
				$this->db->query("INSERT INTO servordering (servordering.servid, servordering.staffid) VALUES ".implode($ordering, ",\n"));
			}
		}else{
			$this->db->query("UPDATE services SET services.active = 0 WHERE services.id = ?", array($this->input->post('servid', true)));
		}
		$this->db->query("UPDATE services SET 
		services.datafull = IF(
			LENGTH(services.serv_name)
			AND LENGTH(services.serv_loc)
			AND LENGTH(services.serv_price)
			AND LENGTH(services.serv_price_instructor)
			AND LENGTH(services.serv_price_trainee),
		1, 0)
		WHERE services.id = ?", array($this->input->post('servid', true)));
		$this->load->helper("url");
		//$this->output->enable_profiler(TRUE);
		redirect("refs/serv_edit/".$this->input->post('servid', true));
	}

	public function serv_edit($item = 0){
		//print $item;
		//$this->output->enable_profiler(TRUE);
		$serv = array();
		$result = $this->db->query("SELECT 
		CONCAT_WS(' ', `staff`.staff_f, `staff`.staff_i, `staff`.staff_o) as fio,
		staff.staff_staff,
		`staff`.id,
		IF(`staff`.`id` IN (SELECT `servordering`.`staffid` FROM `servordering` WHERE `servordering`.`servid` = ?), 1, 0) as ordered
		FROM
		`staff`
		WHERE 
		staff.active", array( $item ));
		if($result->num_rows()){
			foreach($result->result() as $row){
				$active = ($row->ordered) ? ' checked="checked"' : '' ;
				$string = '<li><label><span class="text"><input type="checkbox" name="servord[]" value="'.$row->id.'"'.$active.'>'.$row->fio.'&nbsp;&nbsp;&nbsp;&nbsp;<small class="muted">'.$row->staff_staff.'</small></span></label></li>';
				array_push($serv, $string);
			}
		}
		$result = $this->db->query("SELECT 
		`services`.id,
		`services`.serv_name,
		`services`.serv_alias,
		`services`.serv_desc,
		`services`.serv_loc,
		(`services`.serv_price / 100) as serv_price,
		(`services`.serv_price_trainee / 100) as serv_price_trainee,
		(`services`.serv_price_instructor / 100) as serv_price_instructor,
		`services`.active,
		`services`.datafull,
		`services`.m_active
		FROM
		`services`
		WHERE
		`services`.id = ?", array($item));
		if($result->num_rows()){
			$servdata = $result->row_array();
		}
		$servdata['loc'] = '<option value="0" '.((!in_array($servdata['serv_loc'], array('center', 'home', 'home1', 'home2'))) ? 'selected="selected"' : "").'> -- Не указано -- </option>
		<option value="center" '.(($servdata['serv_loc'] == 'center') ? 'selected="selected"' : "").'>В центре</option>
		<option value="home" '.(($servdata['serv_loc'] == 'home') ? 'selected="selected"' : "").'>На дому (в пределах КАД)</option>
		<option value="home1" '.(($servdata['serv_loc'] == 'home1') ? 'selected="selected"' : "").'>На дому (в пределах 15 км от КАД)</option>
		<option value="home2" '.(($servdata['serv_loc'] == 'home2') ? 'selected="selected"' : "").'>На дому (в пределах 40 км от КАД)</option>';
		$servdata['actbtn'] = ($servdata['m_active']) 
		? '<a id="servActivator" class="btn btn-danger"  title="Пометить неактивной административно" href="/refs/serv_deactivate/'.$servdata['id'].'">Сделать услугу неактивной</a>'
		: '<a id="servActivator" class="btn btn-warning" title="Пометить активной административно"   href="/refs/serv_activate/'.$servdata['id'].'">Сделать услугу активной</a>';
		$servdata['ordering'] = implode($serv, "\n");
		//print_r($servdata);
		return $this->load->view('refs/serviceedit', $servdata, true);
	}

	public function serv_item_add(){
		$result = $this->db->query("INSERT INTO
		`services` (
			services.serv_name,
			services.serv_alias,
			services.serv_desc,
			services.serv_loc,
			services.serv_price,
			services.serv_price_trainee,
			services.serv_price_instructor
		) VALUES (?, ?, ?, ?, ?, ?, ?)", array(
			$this->input->post('name',     true),
			$this->input->post('alias',    true),
			$this->input->post('info',     true),
			$this->input->post('location', true),
			($this->input->post('price',   true) * 100) + $this->input->post('price_k',  true),
			($this->input->post('price_t', true) * 100) + $this->input->post('price_tk',  true),
			($this->input->post('price_i', true) * 100) + $this->input->post('price_ik',  true),
		));
		$this->load->helper("url");
		//$this->output->enable_profiler(TRUE);
		redirect("refs/services");
	}

	public function serv_avail_check(){
		$result = $this->db->query("SELECT 
		services.id,
		COUNT(staff.id) AS counts
		FROM
		services
		LEFT OUTER JOIN servordering ON (services.id = servordering.servid)
		INNER JOIN staff ON (servordering.staffid = staff.id)
		WHERE
		(staff.active)
		GROUP BY
		services.id,
		services.serv_name
		HAVING counts = 0");
		if($result->num_rows()){
			foreach($result->result() as $row){
				$this->db->query("UPDATE services set services.active = 0 WHERE services.id = ?", array($row->id));
			}
		}
	}

	## PATIENTS CONTROL Section END
	############################################################################
}

/* End of file refmodel.php */
/* Location: ./system/application/models/refmodel.php */