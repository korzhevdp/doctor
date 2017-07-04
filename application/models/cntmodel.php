<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cntmodel extends CI_Model{
	
	function __construct(){
		parent::__construct();
	}
	// извлечение списка контрактов
	public function cnt_list_get($cli = 0, $pat = 0){
		$output = array(
			'active'   => array(),
			'inactive' => array()
		);
		$cont_serv = $this->cntListServicesGet();

		$result = $this->db->query("SELECT 
		`contracts`.id AS contid,
		`contracts`.cont_number,
		DATE_FORMAT(`contracts`.cont_date_start, '%d.%m.%Y г.') AS cont_date_start,
		IF(LENGTH(`contracts`.cont_date_end), DATE_FORMAT(`contracts`.cont_date_end, '%d.%m.%Y г.'), 'Контракт открыт') AS cont_date_end,
		`contracts`.active,
		`contracts`.signed,
		`clients`.cli_cphone,
		`clients`.cli_hphone,
		`clients`.cli_address,
		`clients`.cli_mail,
		`clients`.cli_note,
		`clients`.id AS clid,
		`patients`.id AS patid,
		`patients`.pat_address,
		IF(ISNULL(patients.pat_birthdate),
			patients.pat_age,
			YEAR(NOW()) - YEAR(patients.pat_birthdate) - (
				IF(DATE_FORMAT(NOW(), '%m%d') >= DATE_FORMAT(patients.pat_birthdate, '%m%d'), 0, 1)
			)
		) as pat_age,
		`patients`.pat_diagnosis,
		CONCAT_WS(' ',`clients`.cli_f,`clients`.cli_i,`clients`.cli_o) AS cli_fio,
		CONCAT_WS(' ',`patients`.pat_f,`patients`.pat_i,`patients`.pat_o) AS pat_fio
		FROM
		`contracts`
		LEFT OUTER JOIN `courses` ON (`contracts`.crsid = `courses`.id)
		LEFT OUTER JOIN `patients` ON (`patients`.id = `courses`.pid)
		LEFT OUTER JOIN `clients` ON (`patients`.pat_clientid = `clients`.id)
		ORDER BY `contracts`.active DESC, cli_fio");
		if ($result->num_rows()) {
			foreach($result->result_array() as $row){
				$row['services'] = (isset($cont_serv[$row['contid']])) ? implode($cont_serv[$row['contid']], "\n") : "";
				$item = $this->load->view('proc/contractinfo', $row, true);
				($row['active']) ? array_push($output['active'], $item) : array_push($output['inactive'], $item);
			}
		} else {
			array_push($output['active'], '<tr><td colspan="6"><h4>Данные о контрактах не найдены.<br><a href="#" class="contAdder">Создайте хотя бы один</a> и выберите оказываемые по нему услуги.</h4>');
		}
		$act = array(
			'table'         => implode($output['active'], "\n"),
			'tableinactive' => implode($output['inactive'], "\n"),
			'cstrict'       => $cli,
			'pstrict'       => $pat
		);
		return $this->load->view('proc/contracts', $act, true);
	}

	private function cntListServicesGet() {
		$output = array();
		# Собираем услуги по контракту
		$result = $this->db->query("SELECT
		IF(LENGTH(`services`.serv_alias), `services`.serv_alias, '<strong>No Alias!</strong>') AS serv_alias,
		`services`.active,
		`services`.id,
		`contract_services`.cont_id
		FROM
		`contract_services`
		INNER JOIN `services` ON (`contract_services`.serv_id = `services`.id)");
		if($result->num_rows()){
			foreach($result->result() as $row) {
				if (!isset($output[$row->cont_id])) {
					$output[$row->cont_id] = array();
				}
				$title  = "Услуга не может быть оказана";
				$marker = '<i class="icon-exclamation-sign"></i>&nbsp;&nbsp;';
				if ($row->active) {
					$title  = "Услуга может быть оказана";
					$marker = "";
				}
				$string = '<li title="'.$title.'"><a href="/refs/serv_edit/'.$row->id.'">'.$marker.$row->serv_alias.'</a></li>';
				array_push($output[$row->cont_id], $string);
			}
		}
		return $output;
	}
	// конец извлечения списка контрактов

	public function cnt_get($contractID){
		$act       = array('cid' => $contractID);
		/*
		работа этого модуля построена на AJAX. Обмен данными реализован на вьюхе.
		*/
		return $this->load->view('proc/contractdata', $act, true);
	}
	//сохранение контракта
	public function cnt_save(){
		//$this->output->enable_profiler(TRUE);
		//return false;
		// подготовка данных
		$cID          = $this->input->post("cID", true);
		$c_number     = $this->input->post("number", true);
		$datestart    = strlen($this->input->post("datestart")) ? implode(array_reverse(explode(".", $this->input->post("datestart", true))), "-") : date("Y-m-d");
		$dateend      = strlen($this->input->post("dateend"))   ? implode(array_reverse(explode(".", $this->input->post("dateend",   true))), "-") : "0000-00-00";
		$servstart    = strlen($this->input->post("servstart")) ? implode(array_reverse(explode(".", $this->input->post("servstart", true))), "-") : date("Y-m-d");
		$shedule_corr = array();
		//$sdate        = explode("-", $servstart);
		//$servstart_wd = date('w', mktime(0, 0, 0, $sdate[1], $sdate[2], $sdate[0]));
		//
		//$wd = ($this->input->post("wd")) ? 0 : 1 ;
		if($cID){
			// если передан ID контракта, значит исполняется режим ОБНОВЛЕНИЯ - обновляем контракт
			// но лишь при условии, что контракт ещё НЕ ПОДПИСАН
			$result = $this->db->query("SELECT `contracts`.signed FROM `contracts` WHERE `contracts`.id = ?", array($cID));
			if($result->num_rows()){
				$row = $result->row();
				// если не подписан
				if(!$row->signed){
					// сохранение базовых данных контракта
					$result = $this->db->query("UPDATE
					`contracts`
					SET
					`contracts`.cont_number = ?,
					`contracts`.cont_date_start = ?,
					`contracts`.cont_date_end = ?
					WHERE `contracts`.id = ?", array($c_number, $datestart, $dateend, $cID));
					$this->cnt_serv_repack($cID);
					// чистим календарь услуг от ещё неисполненных услуг
					//print $cID;
					//return false;
					$this->db->query("DELETE 
						FROM `service_calendar` 
						WHERE 
						(NOT `service_calendar`.is_done OR ISNULL(`service_calendar`.is_done) )
						AND `service_calendar`.contract_id = ?", array($cID)
					);

					// извлекаем данные для календаря услуг.
					// для случая когда часть услуг уже оказана вводим поправку -
					// исчисление нового календаря начнётся с момента оказания последней услуги:
					// (ЛОВУШКА разрыв может быть больше чем периодизация по календарю 
					// и тогда услуги окажутся в пропущенных. Ввести корректировку корректировки по NOW()) ??? надо ли ещё?
					$result = $this->db->query("SELECT
					service_calendar.service_id,
					DATE_FORMAT(service_calendar.ordered_date, '%Y-%m-%d') as last_date
					FROM
					service_calendar
					WHERE `service_calendar`.`id` IN(
						SELECT
						MAX(`service_calendar`.id) 
						FROM `service_calendar` 
						WHERE
						`service_calendar`.`contract_id` = ?
						AND `service_calendar`.`is_done`
						GROUP BY `service_calendar`.`service_id`
					)", array($cID));
					if($result->num_rows()){
						foreach($result->result() as $row){
							$shedule_corr[$row->service_id] = $row->last_date;
						}
					}
					// разбираем сами услуги и рассчитываем для каждой новый календарь
					// выбираем список услуг
					$result = $this->db->query("SELECT 
					contract_services.serv_id,
					contract_services.bas_days,
					contract_services.bas_num,
					contract_services.cont_id,
					`services`.serv_price
					FROM
					contract_services
					INNER JOIN `services` ON (contract_services.serv_id = `services`.id)
					WHERE
					(contract_services.cont_id = ?)", array($cID));
					if($result->num_rows()){
						//print $result->num_rows()."<br><br>";
						foreach($result->result() as $row){
							//корректировка на число уже оказанных процедур
							if(isset($shedule_corr[$row->serv_id])){
								$c_date = explode("-", $shedule_corr[$row->serv_id]);
								//$c_date_wd = date('w', mktime(0, 0, 0, $c_date[1] , $c_date[2] , $c_date[0]));
								$servstart = date('Y-m-d', mktime(0, 0, 0, $c_date[1] , $c_date[2] , $c_date[0]));
							}
							// ВНИМАНИЕ НЕОБХОДИМ НОВЫЙ КАЛЬКУЛЯТОР ГРАФИКА
							//$shedule = $this->shedule_calc($servstart, $row->bas_period, $row->bas_num, $wd);

							// сюда приходит, например 4  и что с ней делать? даты ведь нет и расчёт тоже невозможен.
							// как вариант - проверить basdays  на формат даты, но это малопродуктивно или нет?


							$basdays = ($row->bas_num == 1 && preg_match("/^\d\d\d\d\-\d\d\-\d\d$/", $row->bas_days)) 
								? $row->bas_days
								: explode(",", $row->bas_days);
							$shedule = $this->shedule_calc2($servstart, $basdays, $row->bas_num);
							// ПЕРЕПИСАТЬ ФУНКЦИЮ--- переписано?
							
							// вставляем услуги в график в $shedule уже находится массив со списком дат YYYY-MM-DD
							$shedule_task = array();
							foreach($shedule as $val){
								array_push($shedule_task, "('".$val."', ".$row->serv_id.", ".$row->cont_id.", ".($row->serv_price * 100).")");
							}
							$result = $this->db->query("INSERT INTO
							`service_calendar`(
								`service_calendar`.ordered_date,
								`service_calendar`.service_id,
								`service_calendar`.contract_id,
								`service_calendar`.pricefixed
							) VALUES ".implode($shedule_task, ",\n"));
						}
					}
				} else {
					// если контракт подписан (закрыт для изменения), тогда выделяется изменение и 
					// вставляется новый контракт имеющий ссылку на родительский. 
					// В терминах администрирования это "доп. соглашение" 
					// Новые услуги прикрепляются уже к нему
					//
					// Разбираем кейс с конца
					$this->output->enable_profiler(TRUE);
					return false;
					// курс уже должен быть в природе, но его ещё надо получить
					$result = $this->db->query("INSERT INTO
					`contracts`(
						`contracts`.crsid,
						`contracts`.cont_number,
						`contracts`.cont_date_start,
						`contracts`.cont_date_end,
						`contracts`.parent)
					VALUES( (SELECT `contracts`.crsid FROM `contracts` WHERE contracts.id = ?), ?, ?, ?, ? )", array(
						$cID,
						$c_number,
						$datestart,
						$dateend,
						$cID
					));
					$nID = $this->db->insert_id();
					$this->db->trans_complete();
					$this->cnt_serv_repack($nID);
					// услуги упакованы в новый контракт в базе данных, теперь нужно рассчитать им новые графики.
					// для этого услуги выбираются из базы и пропускаются через построитель графика
					$result = $this->db->query("SELECT 
					contract_services.serv_id,
					contract_services.bas_days,
					contract_services.bas_num,
					contract_services.cont_id,
					`services`.serv_price
					FROM
					contract_services
					INNER JOIN `services` ON (contract_services.serv_id = `services`.id)
					WHERE
					(contract_services.cont_id = ?)", array($nID));
					if($result->num_rows()){
						foreach($result->result() as $row){
							//корректировка на число уже оказанных процедур
							if(isset($shedule_corr[$row->serv_id])){
								$c_date = explode("-", $shedule_corr[$row->serv_id]);
								//$c_date_wd = date('w', mktime(0, 0, 0, $c_date[1] , $c_date[2] , $c_date[0]));
								$servstart = date('Y-m-d', mktime(0, 0, 0, $c_date[1] , $c_date[2] , $c_date[0]));
							}
							// ВНИМАНИЕ НЕОБХОДИМ НОВЫЙ КАЛЬКУЛЯТОР ГРАФИКА
							//$shedule = $this->shedule_calc($servstart, $row->bas_period, $row->bas_num, $wd);
							$basdays = ($row->bas_num == 1 && preg_match("/^\d\d\d\d\-\d\d\-\d\d$/", $row->bas_days)) 
								? $row->bas_days
								: explode(",", $row->bas_days);
							$shedule = $this->shedule_calc2($servstart, $basdays, $row->bas_num);
							// ПЕРЕПИСАТЬ ФУНКЦИЮ--- переписано?
							
							$shedule_task = array();
							foreach($shedule as $val){
								array_push($shedule_task, "('".$val."', ".$row->serv_id.", ".$row->cont_id.", ".($row->serv_price * 100).")");
							}
							$result = $this->db->query("INSERT INTO
							`service_calendar`(
								`service_calendar`.ordered_date,
								`service_calendar`.service_id,
								`service_calendar`.contract_id,
								`service_calendar`.pricefixed
							) VALUES ".implode($shedule_task, ",\n"));
						}
					}
				}
			}
		}else{
			/*
			активирован режим ДОБАВЛЕНИЯ нового контракта
			сначала производим проверку на наличие курса у пациента, т.к. это высшая номинативная единица, 
			и для обратной совместимости следует проверять его наличие
			*/
			$crsid = $this->cntCourseIDGet();
			// с курсом определились, берёмся за контракт.
			// все данные готовы
			$cID   = $this->insertNewContract($crsid, $c_number, $datestart, $dateend);
			$this->cnt_serv_repack($cID);

			// извлекаем данные для календаря услуг.
			// для случая когда часть услуг уже оказана вводим поправку -
			// исчисление нового календаря начнётся с момента оказания последней услуги:
			// (ЛОВУШКА разрыв может быть больше чем периодизация по календарю 
			// и тогда услуги окажутся в пропущенных. Внести корректировку корректировки по NOW())
			## выборка корректировок
			$sheduleBias = $this->serviceCalendarBiasGet($cID);

			// разбираем услуги и рассчитываем для каждой новый календарь
			// выбираем список услуг
			$result = $this->db->query("SELECT 
			`contract_services`.serv_id,
			`contract_services`.bas_days,
			`contract_services`.bas_num,
			`contract_services`.cont_id
			FROM
			`contract_services`
			WHERE `contract_services`.`cont_id` = ?", array($cID));
			if ($result->num_rows()) {
				foreach ($result->result() as $row){
					//корректировка графика на графики уже оказанных процедур
					if (isset($sheduleBias[$row->serv_id])) {
						$cDate     = explode("-", $sheduleBias[$row->serv_id]);
						$servstart = date('Y-m-d', mktime(0, 0, 0, $cDate[1] , $cDate[2] , $cDate[0]));
					}
					// ВНИМАНИЕ НЕОБХОДИМ НОВЫЙ КАЛЬКУЛЯТОР ГРАФИКА???
					//$shedule = $this->shedule_calc($servstart, $row->bas_period, $row->bas_num, $wd);
					$basdays = ($row->bas_num == 1 && preg_match("/^\d\d\d\d\-\d\d\-\d\d$/", $row->bas_days)) 
						? $row->bas_days
						: explode(",", $row->bas_days);
					$shedule = $this->shedule_calc2($servstart, $basdays, $row->bas_num);
					// ПЕРЕПИСАТЬ ФУНКЦИЮ
					
					$sheduleArray = array();
					foreach ($shedule as $val) {
						array_push($sheduleArray, "('".$val."', ".$row->serv_id.", ".$row->cont_id.")");
					}
					$this->db->query("INSERT INTO
					`service_calendar`(
						`service_calendar`.ordered_date,
						`service_calendar`.service_id,
						`service_calendar`.contract_id
					) VALUES ".implode($sheduleArray, ",\n"));
				}
			}
		}
		// финишный расчёт последнего дня контракта
		$this->db->query("UPDATE
		`contracts`
		SET
		cont_date_end = ( SELECT  MAX(`service_calendar`.ordered_date) FROM `service_calendar` WHERE `service_calendar`.`contract_id` = ? )
		WHERE contracts.id = ?", array($cID, $cID));

		//переадресация на контракт
		//$this->load->helper("url");
		//redirect("contracts/show/".$cID);
	}

	private function cntCourseIDGet() {
		$result = $this->db->query("SELECT
		`courses`.id AS cid
		FROM
		`courses`
		WHERE `courses`.`pid` = ?", array($this->input->post("patient")));
		if ($result->num_rows()) {
			// курс нашёлся
			$row    = $result->row(0);
			$crsid  = $row->cid;
		} else {
			// ну нет ещё курса... тогда вставляем
			$this->db->trans_start();
			$result = $this->db->query("INSERT INTO `courses`( `courses`.pid, `courses`.startdate) VALUES (?, NOW())", array($this->input->post("patient")));
			$crsid  = $this->db->insert_id();
			$this->db->trans_complete();
		}
		return $crsid;
	}

	private function insertNewContract($crsid = 0, $c_number = 0, $datestart = 0, $dateend = 0){
		$this->db->trans_start();
		$this->db->query("INSERT INTO
		`contracts`(
			`contracts`.crsid,
			`contracts`.cont_number,
			`contracts`.cont_date_start,
			`contracts`.cont_date_end,
			`contracts`.parent
		) VALUES( ?, ?, ?, ?, ? )", array(
			$crsid,
			$c_number,
			$datestart,
			$dateend,
			0
		));
		$cID = $this->db->insert_id();
		$this->db->trans_complete();
		return $cID;
	}

	private function serviceCalendarBiasGet($cID) {
		$output = array();
		$result = $this->db->query("SELECT
		service_calendar.service_id,
		DATE_FORMAT(service_calendar.ordered_date, '%Y-%m-%d') AS last_date
		FROM
		service_calendar
		WHERE `service_calendar`.`id` IN (
			SELECT
			MAX(`service_calendar`.id)
			FROM `service_calendar`
			WHERE
			`service_calendar`.`contract_id` = ?
			AND `service_calendar`.`is_done`
			GROUP BY `service_calendar`.`service_id`
		)", array($cID));
		if ($result->num_rows()) {
			foreach ($result->result() as $row) {
				$output[$row->service_id] = $row->last_date;
			}
		}
		return $output;
	}
	// конец сохранения контракта

	public function cnt_item_get($contractID = 0){
		//$this->output->enable_profiler(TRUE);
		$contractID = ($this->input->post("item")) ? $this->input->post("item") : $contractID;
		if (!$contractID) {
			return '<h4>Не указан идентификатор курса. Выберите требуемый курс из <a href="/courses">списка открытых курсов</a></h4>';
		}
		$result = $this->db->query("SELECT
		`courses`.id,
		`courses`.pid,
		DATE_FORMAT(`courses`.startdate, '%d.%m.%Y г.') AS startdate,
		IF(LENGTH(`courses`.enddate), DATE_FORMAT(`courses`.enddate, '%d.%m.%Y г.'), 'Курс проводится') AS enddate,
		`courses`.active,
		`courses`.note
		FROM courses
		WHERE
		`courses`.id = ?
		LIMIT 1", array($contractID));
		if ($result->num_rows()) {
			$output = $result->row_array();
		} else {
			return "<h3>Информации о проводимых курсах не найдено.<br>Создайте курс, чтобы иметь возможность открывать контракты.</h3>";
		}

		$result = $this->db->query("SELECT
		CONCAT_WS(' ', patients.pat_f, patients.pat_i, patients.pat_o) AS pat_fio,
		patients.pat_address,
		patients.pat_birthdate,
		patients.pat_diagnosis,
		patients.pat_info,
		patients.pat_location,
		`clients`.cli_cphone,
		`clients`.cli_hphone,
		`clients`.cli_mail
		FROM
		`clients`
		INNER JOIN patients ON (`clients`.id = patients.pat_clientid)
		WHERE
		(patients.id = ?)", array($output['pid']));
		if ($result->num_rows()) {
			$row = $result->row_array();
			$output = array_merge($output, $row);
		}

		$contracts = array();
		$result = $this->db->query("SELECT
		`contracts`.id,
		`contracts`.crsid,
		`contracts`.cont_number,
		DATE_FORMAT(`contracts`.cont_date_start, '%d.%m.%Y г.') AS cont_date_start,
		IF(LENGTH(`contracts`.cont_date_end), DATE_FORMAT(`contracts`.cont_date_end, '%d.%m.%Y г.'), 'Контракт открыт') AS cont_date_end,
		`contracts`.active
		FROM
		`contracts`
		WHERE
		`contracts`.crsid = ?", array($contractID));
		if ($result->num_rows()) {
			foreach($result->result() as $row) {
				$string = '<tr>
					<td># '.$row->cont_number.'</td>
					<td>
						Начало контракта: '.$row->cont_date_start.'<br>
						Конец контракта: '.$row->cont_date_end.'
					</td>
					<td></td>
				</tr>';
				array_push($contracts, $string);
			}
		} else {
			array_push($contracts, "<tr><td colspan=3><h4>Информации о контрактах не найдено.<br>Создайте контракт и выберите оказываемые по нему услуги.</h4></td></tr>");
		}
		$output['contracts'] = implode($contracts, "\n");
		return $this->load->view('proc/courseitem', $output, true);
	}

	public function cnt_initdata_get() {
		$clients = array('<option value="0" title="Выберите клиента">Выберите клиента</option>');
		$services = array('<option value="0" title="Выберите услугу">Выберите услугу</option>');
		$result = $this->db->query("SELECT
		MAX(contracts.id) + 1  AS id
		FROM
		contracts");
		if($result->num_rows()){
			$row = $result->row();
			$number = "А-".$row->id."/".date("m-y");
		}
		$result = $this->db->query("SELECT 
		CONCAT_WS(' ', clients.cli_f, clients.cli_i, clients.cli_o) AS cli_fio,
		CONCAT_WS(' ', clients.cli_f, CONCAT(LEFT(clients.cli_i, 1),'.', LEFT(clients.cli_o, 1),'.')) AS cli_init,
		clients.id
		FROM
		clients
		WHERE `clients`.`active`
		ORDER BY cli_fio");
		if($result->num_rows()){
			foreach($result->result() as $row){
				$string = '<option value="'.$row->id.'" title="'.$row->cli_fio.'">'.$row->cli_init.'</option>';
				array_push($clients, $string);
			}
		}
		$result = $this->db->query("SELECT 
		`services`.serv_name,
		`services`.serv_alias,
		`services`.id
		FROM
		`services`
		ORDER BY
		`services`.serv_name");
		if($result->num_rows()){
			foreach($result->result() as $row){
				$string = '<option value="'.$row->id.'" title="'.$row->serv_alias.'">'.$row->serv_name.'</option>';
				array_push($services, $string);
			}
		}

		return "initdata = {
			clients:  '".implode($clients,  "")."',
			services: '".implode($services, "")."',
			cn: '".$number."'
		}";
	}

	// извлечение данных контракта
	public function cnt_data_get($cnt = 0) {
		$cnt           = ($cnt) ? $cnt : $this->input->post('cnt');
		$data          = $this->cntDataGet($cnt);		// выборка свойств самого контракта
		$services      = $this->cntServicesGet($cnt);	// выборка свойств услуг по контракту
		$c_services    = $this->cntServicesListCompose($services);
		$servstartdate = $this->cntStartDateGet($cnt);
		# Подготовка справочников
		$servicesList  = $this->cntServicesFullListDataGet(); // выборка сдвоенного справочника %) цены на услуги/список услуг 
		$clientsList   = $this->cntClientDataGet($data['client']);
		return "contract = {
			data          : ".$data['contract_data'].",
			servstartdate : '".$servstartdate."',
			servlist      : '".implode($servicesList['list'], "")."',
			clientlist    : '".$clientsList."',
			patient       : '',
			services      : ".$c_services."
		};
		sp = { ".implode($servicesList['price'], ", ")." }";
	}

	private function cntDataGet($cnt = 0) {
		$output = array(
			'client'        => 0,
			'patient'       => 0,
			'contract_data' => '{}'
		);
		$result = $this->db->query("SELECT 
		contracts.id,
		contracts.cont_number as num,
		DATE_FORMAT(contracts.cont_date_start, '%d.%m.%Y') AS start,
		DATE_FORMAT(contracts.cont_date_end, '%d.%m.%Y') AS end,
		contracts.active,
		contracts.signed,
		contracts.parent,
		clients.id AS cid,
		patients.id AS pid
		FROM
		contracts
		LEFT OUTER JOIN courses ON (contracts.crsid = courses.id)
		LEFT OUTER JOIN patients ON (courses.pid = patients.id)
		LEFT OUTER JOIN clients ON (patients.pat_clientid = clients.id)
		WHERE
		(contracts.id = ?)
		LIMIT 1", array($cnt));
		if($result->num_rows()){
			$row = $result->row(0);
			$output = array(
				'client'   => $row->cid,
				'patient'  => $row->pid,
				'contract_data' => "{ id: ".$row->id.", num: '".$row->num."', start: '".$row->start."', end: '".$row->end."', active: '".$row->active."', client: '".$row->cid."', patient: '".$row->pid."', signed: ".$row->signed.", parent: ".$row->parent." }"
			);
		}
		return $output;
	}

	private function cntServicesGet($cnt) {
		$output = array();
		$result = $this->db->query("SELECT 
		contract_services.bas_num,
		contract_services.bas_days,
		DATE_FORMAT(MIN(service_calendar.ordered_date), '%d.%m.%Y') AS initdate,
		contract_services.serv_id,
		contract_services.refprice,
		`services`.serv_price
		FROM
		contract_services
		INNER JOIN service_calendar ON (contract_services.serv_id = service_calendar.service_id)
		AND (contract_services.cont_id = service_calendar.contract_id)
		INNER JOIN `services` ON (contract_services.serv_id = `services`.id)
		WHERE
		(contract_services.cont_id = ?)
		GROUP BY
		contract_services.serv_id", array($cnt));
		if($result->num_rows()){
			foreach($result->result() as $row){
				$price   = ($row->refprice) ? $row->refprice : $row->serv_price;
				$output[$row->serv_id] = array(
					'days'    => $row->bas_days,
					'num'     => $row->bas_num,
					'date'    => $row->initdate,
					'price'   => substr($price, 0, -2),
					'price_k' => ($price % 100) ? str_pad(($price % 100), 2, "0") : "00"
				);
			}
		}
		return $output;
	}

	private function cntStartDateGet($cnt) {
		$result = $this->db->query("SELECT
		DATE_FORMAT(MIN(service_calendar.ordered_date), '%d.%m.%Y') AS od
		FROM
		service_calendar
		WHERE
		(service_calendar.contract_id = ?)
		LIMIT 1", array($cnt));
		if ($result->num_rows()) {
			$row = $result->row();
			return $row->od;
		}
		return 0;
	}

	private function cntServicesListCompose($services){
		$output = array();
		foreach ($services as $key=>$val) {
			$string = $key.": { num: '".$val['num']."', days: '".$val['days']."', price: '".$val['price']."', price_k: '".$val['price_k']."', date: '".$val['date']."' }";
			array_push($output, $string);
		}
		return "{ ".implode($output, ",")." }";
	}
	
	private function cntServicesFullListDataGet () {
		$output = array(
			'list'  => array('<option value="0">Выберите услугу</option>'),
			'price' => array()
		);
		$result = $this->db->query("SELECT 
		`services`.serv_name,
		`services`.serv_alias,
		`services`.serv_price / 100 as sp,
		`services`.id
		FROM
		`services`
		ORDER BY
		`services`.serv_name");
		if($result->num_rows()){
			foreach($result->result() as $row){
				array_push($output['list'], '<option value="'.$row->id.'" title="'.$row->serv_alias.'">'.$row->serv_name.'</option>');
				array_push($output['price'], $row->id.": ".$row->sp);
			}
		}
		return $output;
	}
	
	private function cntClientDataGet($clientID) {
		$output = array('<option value="0" title="Выберите клиента">Выберите клиента</option>');
		$result = $this->db->query("SELECT 
		CONCAT_WS(' ', clients.cli_f, clients.cli_i, clients.cli_o) AS cli_fio,
		CONCAT_WS(' ', clients.cli_f, CONCAT(LEFT(clients.cli_i, 1),'.', LEFT(clients.cli_o, 1),'.')) AS cli_init,
		clients.id
		FROM
		clients
		WHERE `clients`.`active` 
		OR clients.id = ?
		ORDER BY cli_fio", array($clientID));
		if($result->num_rows()){
			foreach($result->result() as $row){
				$string = '<option value="'.$row->id.'" title="'.$row->cli_fio.'">'.$row->cli_init.'</option>';
				array_push($output, $string);
			}
		}
		return implode($output, "");
	}
	// конец извлечения данных контракта

	public function rel_pats_get(){
		$patients = array('<option value="0" title="Выберите пациента">Выберите пациента</option>');
		$result = $this->db->query("SELECT 
		CONCAT_WS(' ', `patients`.pat_f, `patients`.pat_i, `patients`.pat_o) AS pat_fio,
		`patients`.id
		FROM
		`patients`
		WHERE `patients`.`pat_clientid` = ?
		ORDER BY pat_fio", array($this->input->post("cli")));
		if($result->num_rows()){
			foreach($result->result() as $row){
				$string = '<option value="'.$row->id.'">'.$row->pat_fio.'</option>';
				array_push($patients, $string);
			}
		}
		return "data = '".implode($patients, "")."'";
	}

	public function addnew(){
		return $this->load->view('proc/newcontract', array(), true);
	}

	public function underwrite(){
		$this->db->query("UPDATE `contracts` SET signed = 1 WHERE `contracts`.`id` = ?", array($this->input->post("id")));
		if ($this->db->affected_rows()) {
			print "OK";
		}
	}

	public function cnt_serv_repack($cID) {
		// упаковка услуг сопоставленных контракту.
		// возможность вставки разовых услуг - сделана.
		// пересчёт разовой процедуры, введённой как процедура по графику - сделан.
		// почти атомарная функция :)
		$servoutput = array();
		for ($i = 1; $i <= $this->input->post('servcount'); $i++) {
			if(
				$this->input->post('service'.$i, true)
				&& strlen($this->input->post('service'.$i, true))
				&& $this->input->post('num'.$i, true)
				&& strlen($this->input->post('num'.$i, true))
				&& (strlen($this->input->post('servdaylist'.$i, true)) || strlen($this->input->post('servdate'.$i, true)))
			) {
				$basdays = ( $this->input->post('servdaylist'.$i, true) )
					? $this->input->post('servdaylist'.$i, true)
					: implode(array_reverse(explode(".", $this->input->post('servdate'.$i, true))), "-");

				$string = "(".$cID.", ".$this->input->post('service'.$i, true).", ".$this->input->post('num'.$i, true).", '".$basdays."', '".($this->input->post('servprice'.$i, true)).$this->input->post('servprice_k'.$i, true)."')";
				array_push($servoutput, $string);
			}
		}
		//print sizeof($servoutput)."<br><br>";
		$result = $this->db->query("DELETE from `contract_services` WHERE `contract_services`.cont_id = ?", array($cID));
		$result = $this->db->query("INSERT INTO 
		`contract_services`(
			`contract_services`.cont_id,
			`contract_services`.serv_id,
			`contract_services`.bas_num,
			`contract_services`.bas_days,
			`contract_services`.refprice
		) VALUES ".implode($servoutput, ",\n"));
	}

	// deprecated, left here for future analysis
	public function shedule_calc($startdate=0, $period=1, $rounds=1, $wd=true){
		$output = array();
		$date  = explode('-', $startdate);
		$year  = $date[0];
		$month = $date[1];
		$day   = $date[2];
		$wdc = 0;
		for($i = 0; $i < $rounds; $i++){
			if($wd){
				switch (date('w', mktime(0, 0, 0, $month , ($day + $wdc + ($i * $period)), $year))){
					// исключаем субботу как выходной (в субботу работаем, потому закомментировано)
					/*
					case 6:
						$wdc += 2;
					break;
					*/
					// исключаем воскресенье как выходной
					case 0:
						$wdc += 1;
					break;
					
				}
			}
			$target = mktime(0, 0, 0, $month , ($day + $wdc + ($i * $period)), $year);
			array_push($output, date('Y-m-d', $target));
		}
		return $output;
	}

	// sheduler now used:
	private function shedule_calc2($startdate=0, $days=array(), $rounds=1){
		$output  = array($days);
		//print implode($days, ", ")."<br>";
		//два режима работы - вставка услуги на единственный день и вставка услуги по опорному графику по дням недели
		if($rounds !== 1 && is_array($days)){
			// аварийная заглушка на случай отсутствия начальной даты.
			if(!$startdate){
				return array("0000-00-00");
			}
			// вставка услуги на дни недели из опорного графика
			$date    = explode('-', $startdate);
			$year    = $date[0];
			$month   = $date[1];
			$day     = $date[2];
			$init_wd = date("w", mktime(0, 0, 0, $month , $day, $year));
			// корректировка начального дня на график этой конкретной услуги
			foreach ($days as $val) {
				if ($init_wd <= $val) {
					$day += ($val - $init_wd);
					$init_wd = $val;
					break;
				}
				continue;
			}
			// корректировка произведена
			$markerWT = 0; // маркер холостого хода и счётчик недель
			while (sizeof($output) < $rounds) {
				foreach ($days as $val) {
					if(!$markerWT){ // холостой ход
						if ($init_wd >= $val){
							$markerWT++;
							
						}
						continue; // если рано - то пропуск цикла
					}
					$daydiff = (($markerWT-1) * 7) + $day + $val - 1;
					// формируем дату и вставляем её в сгенерированный график ГГГШ-ММ-ДД
					$target = mktime(0, 0, 0, $month , $daydiff, $year);
					array_push($output, date('Y-m-d', $target));
					// проверяем: не слишком ли много насчитали услуг. внутренний цикл не знает их числа
					if (sizeof($output) === $rounds) {
						break; // хватит. попробуй начать цикл заново.
					}
				}
				$markerWT++;
			}
		}
		//print_r($output);
		return $output;
	}
}

/* End of file crsmodel.php */
/* Location: ./system/application/models/crsmodel.php */