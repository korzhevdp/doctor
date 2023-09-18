<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Crsmodel extends CI_Model{
	
	function __construct(){
		parent::__construct();
	}

	public function crs_list_get(){
		$act = array();
		$output = array();
		$output2 = array();
		$result = $this->db->query("SELECT 
		courses.pid,
		courses.id,
		courses.active,
		patients.id														AS patid,
		clients.id														AS clid,
		DATE_FORMAT(courses.startdate, '%d.%m.%Y г.')					AS startdate,
		DATE_FORMAT(courses.enddate, '%d.%m.%Y г.')						AS enddate,
		CONCAT_WS(' ', clients.cli_f, clients.cli_i, clients.cli_o)		AS cli_fio,
		CONCAT_WS(' ', patients.pat_f, patients.pat_i, patients.pat_o)	AS pat_fio
		FROM
		courses
		LEFT OUTER JOIN patients ON (courses.pid = patients.id)
		LEFT OUTER JOIN clients  ON (patients.pat_clientid = clients.id)");
		if($result->num_rows()){
			foreach($result->result_array() as $row){
				// список контрактов
				$conts = array();
				$result2 = $this->db->query("SELECT
				`contracts`.id,
				`contracts`.signed,
				`contracts`.active,
				DATE_FORMAT(`contracts`.cont_date_start, '%d.%m.%Y')	AS cont_date_start,
				DATE_FORMAT(`contracts`.cont_date_end, '%d.%m.%Y')		AS cont_date_end,
				`contracts`.cont_number
				FROM
				`contracts`
				WHERE
				`contracts`.`crsid` = ?", array($row['id']));
				if($result2->num_rows()){
					foreach($result2->result() as $row2){
						$status = ($row2->signed) ? "Подписан." : "";
						$status.= ($row2->active) ? "Действует." : "Истёк.";
						$string = '<li title="'.$status.'"><a href="'.base_url().'/contracts/'.$row2->id.'">'.$row2->cont_number."</a></li>";
						array_push($conts, $string);
					}
				}
				$row['status'] = ($row['active']) ? "" : "error";
				$row['conts']  = implode("\n", $conts);

				($row['active']) 
					? array_push($output, $this->load->view('proc/coursetablerow', $row, true)) 
					: array_push($output2, $this->load->view('proc/coursetablerow', $row, true));
			}
		}else{
			array_push($output, "<h4>Данные о проводимых курсах не найдены.<br>Создайте пациентов или откройте контракты к имеющимся клиентам</h4>");
		}

		$act['table'] = implode("\n", $output);
		$act['inactivetable'] = implode("\n", $output2);
		return $this->load->view('proc/courses', $act, true);
	}

	public function getCourseItem($id = 0) {
		//$this->output->enable_profiler(TRUE);
		$id = ($this->input->post("item")) ? $this->input->post("item") : $id;
		if(!$id){
			return '<h3>Не указан идентификатор курса. Выберите требуемый курс из <a href="'.base_url().'courses">списка открытых курсов</a></h3>';
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
		LIMIT 1", array($id));
		if($result->num_rows()){
			$output = $result->row_array();
		}else{
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
		if($result->num_rows()){
			$row = $result->row_array();
			$output = array_merge($output, $row);
		}else{
			$result = $this->db->query("Delete from courses WHERE courses.id = ?", array($id));
			$this->load->helper("url");
			redirect("courses");
		}

		$contracts = array();
		//print $id;
		$result = $this->db->query("SELECT 
		contracts.id,
		contracts.crsid,
		contracts.cont_number,
		DATE_FORMAT(contracts.cont_date_start, '%d.%m.%Y г.') AS cont_date_start,
		IF(LENGTH(contracts.cont_date_end), DATE_FORMAT(contracts.cont_date_end, '%d.%m.%Y г.'), 'Контракт открыт') AS cont_date_end,
		contracts.active,
		CONCAT(`clients`.cli_f, ' ', LEFT(`clients`.cli_i, 1), '.', LEFT(`clients`.cli_o, 1), '.') AS cli_fio
		FROM
		`courses`
		LEFT OUTER JOIN `patients` ON (`courses`.pid = `patients`.id)
		RIGHT OUTER JOIN contracts ON (`courses`.id = contracts.crsid)
		LEFT OUTER JOIN `clients` ON (`patients`.pat_clientid = `clients`.id)
		WHERE
		(contracts.crsid = ?)", array($id));
		if($result->num_rows()){
			foreach($result->result() as $row){
				$string = '<tr>
					<td># '.$row->cont_number.'</td>
					<td>'.$row->cli_fio.'</td>
					<td>
						Начало контракта: '.$row->cont_date_start.'<br>
						Конец контракта: '.$row->cont_date_end.'
					</td>
				</tr>';
				array_push($contracts, $string);
			}
		}else{
			array_push($contracts, "<tr><td colspan=3><h4>Информации о контрактах не найдено.<br>Создайте контракт и выберите оказываемые по нему услуги.</h4></td></tr>");
		}
		$output['contracts'] = implode("\n", $contracts);
		$output['finances']  = $this->getContractServices($courseID);
		//$output['deposit']  = $this->crsmodel->deposit_get($id);

		return $this->load->view('proc/courseitem', $output, true);
	}

	public function getContractServices($courseID){
		$result = $this->db->query("SELECT 
		`contracts`.cont_number,
		`service_calendar`.is_done AS isDone,
		`service_calendar`.done_by,
		`service_calendar`.contract_id,
		`services`.serv_name,
		`services`.m_active
		`services`.active																												AS serv_active,
		`contracts`.active																												AS cont_active,
		TRUNCATE((service_calendar.pricefixed / 10000), 2)																				AS pf,
		CONCAT_WS(' ',   DATE_FORMAT(service_calendar.ordered_date, '%d.%m.%Y'), DATE_FORMAT(service_calendar.ordered_time, '%H:%i'))	AS dtm,
		CONCAT_WS(' - ', DATE_FORMAT(contracts.cont_date_start, '%d.%m.%Y'),     DATE_FORMAT(contracts.cont_date_end, '%d.%m.%Y'))		AS ctm,
		FROM
		service_calendar
		LEFT OUTER JOIN contracts ON (service_calendar.contract_id = contracts.id)
		LEFT OUTER JOIN courses   ON (contracts.crsid = courses.id)
		LEFT OUTER JOIN services  ON (service_calendar.service_id = services.id)
		WHERE
		(courses.id = ?)
		ORDER BY
		service_calendar.ordered_date", array(
			$courseID
		));
		return $this->composeServicesReport( $result );
	}

	public function composeServicesReport($result, $withtotal=0, $percent = 10){
		$input    = array();
		$output   = array();
		$sum      = array();
		$totalsum = 0;
		

		if($result->num_rows()){
			foreach($result->result() as $row){
				if(!isset($input[$row->contract_id])){
					$conclass = array();
					$ctitle   = array();
					($row->cont_active) ? array_push($ctitle, "Контракт активен")	: array_push($ctitle, "Контракт неактивен");
					($row->cont_active) ? array_push($conclass, "success")			: array_push($conclass, "danger");
					$input[$row->contract_id] = array('<table class="table table-bordered table-condensed table-striped">
					<tr class="'.implode(" ", $conclass).'">
					<td colspan="5">
						<a href="'.base_url().'/contracts/show/'.$row->contract_id.'" target="_blank">Контракт №'.$row->cont_number.'&nbsp;&nbsp;&nbsp;<small>'.$row->ctm.'&nbsp;&nbsp;'.implode(" ", $ctitle).'</small></a>
					</td>
					</tr>
					<tr>
					<th style="width:120px;">Дата/Время</th>
					<th>Услуга</th>
					<th>Установленная цена</th>
					<th style="width:70px;">Оказана</th>
					<th style="width:300px;">Установленная сумма вознаграждения</th>
					</tr>');
					$sum[$row->contract_id] = 0;
				}
				$srclass = array();
				$rclass  = array();
				$rtitle  = array();
				($row->serv_active) ? ""											: array_push($srclass, "muted");
				($row->serv_active) ? array_push($rtitle, "Услуга оказывается")		: array_push($rtitle, "Услуга не оказывается");
				($row->isDone)		? array_push($rclass, "success")				: "";
				($row->isDone)		? array_push($rtitle, "услуга оказана")			: array_push($rtitle, "услуга не оказана");
				($row->isDone)		? $sum[$row->contract_id] += ($row->pf * $percent / 100)			: "" ;
				($row->isDone)		? $totalsum += ($row->pf * $percent / 100)							: "" ;

				array_push($input[$row->contract_id], '<tr class="'.implode(" ", $rclass).'" title="'.implode(", ", $rtitle).'">
				<td>'.$row->dtm.'</td>
				<td class="'.implode(" ", $srclass).'">'.$row->serv_name.'</td>
				<td style="width:150px;">'.$row->pf.' рублей</td>
				<td>'.(($row->isDone) ? "Да" : "Нет").'</td>
				<td style="width:150px;">'.(($row->isDone) ? "<strong>".($row->pf * $percent / 100)."</strong>" : "0.00").' рублей</td>
				</tr>');
			}
			
		}
		foreach($input as $key=>$val){
			array_push($output, implode("", $val)."<tr><td colspan=3></td><td><strong>ИТОГО</strong></td><td><strong>".$sum[$key]." рублей</strong></td></tr></table>");
		}

		if ($withtotal) {
			array_push($output, '<h4 class="pull-right" style="margin-bottom:160px;margin-right:20px;">Итого по контрактам: '.$totalsum.' рублей</h4>');
			array_unshift($output, '<h5 class="pull-right" style="margin-bottom:5px;margin-right:20px;">Итого по контрактам: '.$totalsum.' рублей</h5>');
		}
		return implode("\n", $output);
	}

	public function deposit_get($id){
		$output = array();
	}
}

/* End of file crsmodel.php */
/* Location: ./system/application/models/crsmodel.php */