<?php
class Shedmodel extends CI_Model{
	
	public function __construct(){
		parent::__construct();
		//$this->output->enable_profiler(TRUE);
	}

	public function show($year, $month){
		//$this->output->enable_profiler(TRUE);
		$data   = array();
		$output = array();
		$result = $this->db->query("SELECT 
		DATE_FORMAT(service_calendar.ordered_date, '%e') as `workday`,
		DATE_FORMAT(service_calendar.ordered_date, '%Y/%m/%d') as `linkday`,
		CASE WHEN service_calendar.is_done THEN 'done'
			 WHEN DATEDIFF(`service_calendar`.`ordered_date`, NOW()) < 0 AND NOT service_calendar.is_done THEN 'missed'
			 ELSE 'info'
		END as `state`
		FROM
		service_calendar
		WHERE
		(DATE_FORMAT(service_calendar.ordered_date, '%Y%m') = ?)
		ORDER BY `workday`", array($year.$month));
		if($result->num_rows()){
			foreach($result->result() as $row){
				if(!isset($data[$row->workday])){
					$data[$row->workday] = array(
						'servnum' => 0,
						'missed'  => 0,
						'done'    => 0,
						'link'    => "/shedule/day/".$row->linkday,
						'link2'   => "/shedule/pload/".$row->linkday
					);
				}
				++$data[$row->workday]['servnum'];
				switch($row->state){
					case "missed":
						++$data[$row->workday]['missed'];
					break;
					case "done":
						++$data[$row->workday]['done'];
					break;
				}
			}
		}
		//print_r($data);
		return $this->load->view('proc/shedulecalendar', array('calendar' => $this->calendar->generate($year, $month, $data)), true);
	}

	public function day2($year, $month, $day){
		// $this->output->enable_profiler(TRUE);
		// calendar assumptions
		$starthour = 8;
		$endhour = 21;
		$discretion = 30; // minutes
		$q1 = 60 / $discretion; // planner period
		$q2 = ($endhour -$starthour) * $q1; // planner length
		//
		$data = array();
		$output = array();
		$table = array();
		$patients = array();
		$result = $this->db->query("SELECT 
		service_calendar.contract_id,
		service_calendar.id as cal_id,
		IF(LENGTH(service_calendar.staff_id) OR service_calendar.staff_id > 0, 1, 0) AS staff,
		patients.id AS patid,
		CONCAT(patients.pat_f, ' ', LEFT(patients.pat_i, 1), '.' ,LEFT(patients.pat_o, 1), '.') AS fio,
		services.id,
		services.serv_name,
		((TIME_FORMAT(service_calendar.ordered_time, '%H')     - ?) * ?) + (TIME_FORMAT(service_calendar.ordered_time,     '%i') / ?) AS `ot`,
		((TIME_FORMAT(service_calendar.ordered_time_end, '%H') - ?) * ?) + (TIME_FORMAT(service_calendar.ordered_time_end, '%i') / ?) AS `ote`
		FROM
		service_calendar
		LEFT OUTER JOIN contracts ON (service_calendar.contract_id = contracts.id)
		LEFT OUTER JOIN courses ON (contracts.crsid = courses.id)
		LEFT OUTER JOIN patients ON (courses.pid = patients.id)
		LEFT OUTER JOIN services ON (service_calendar.service_id = services.id)
		WHERE
		(DATE_FORMAT(service_calendar.ordered_date, '%Y%m%d') = ?)", array($starthour, $q1, $discretion, $starthour, $q1, $discretion, $year.$month.$day));
		if($result->num_rows()){
			// массив данных по услугам пациента
			foreach($result->result() as $row){
				$serv = array(
					'staffset' => $row->staff,
					'fio' => $row->fio,
					'cal_id' => $row->cal_id,
					'sn' => $row->serv_name,
					'sid' => $row->id,
					'id' => $row->patid,
					'ot' => (int) $row->ot,
					'ote' => (int) $row->ote);
				if(!isset($patients[$row->patid])){
					$patients[$row->patid] = array();
				}
				array_push($patients[$row->patid], $serv);
			}
			//print_r($patients);
			// вставка ячеек в строки
			function insertCells($patients, $quantum = 1){
				$output = array();
				foreach($patients as $val) {
					foreach($val as $servs){
						$classes = array('servcell');
						$icon = "&nbsp;";
						if($quantum <= $servs['ote'] && $quantum > $servs['ot']){
							array_push($classes, 'servactive');
							$icon = ($servs['staffset'])
								? '<img src="/images/alarm-clock.png" width="16" height="16" border="0" alt="">' 
								: '<img src="/images/alarm-clock-err.png" width="16" height="16" border="0" alt="">';
						}
						array_push($output, '<td style="text-align:center;" class="'.implode($classes, " ").' c'.$servs['cal_id'].'" title="'.$servs['sn'].'" sid="'.$servs['sid'].'" cal="'.$servs['cal_id'].'" ref="'.$quantum.'" fio="'.$servs['fio'].'">'.$icon.'</td>');
					}
				}
				return implode($output, "");
			}
			//вставка ячеек заголовка
			function insertHCells($patients){
				$output = array();
				foreach($patients as $val) {
					array_push($output, '<th colspan='.sizeof($val).' style="min-width:90px;text-align:center;">'.$val[0]['fio'].'</th>');
				}
				return implode($output, "");
			}
			// calculation args
			// $discretion = 30; // minutes
			// $q1 = 60 / $discretion; // planner period
			// $q2 = ($endhour -$starthour) * $q1; // planner length
			for($i = 0; $i < $q2; $i++){
				if($i == 0 || !($i%$q1)){
					$string = '<tr>
						<td rowspan='.$q1.' style="vertical-align:middle;text-align:center;width:50px;font-size:36px;color:#7e7e7e;font-weight:bold;">'.($starthour + ceil($i/$q1)).':00</td>
						<td style="vertical-align:middle;text-align:center;width:50px;font-size:14px;color:#7e7e7e;font-weight:bold;">'.($starthour + ceil($i/$q1)).":".($discretion*($i%$q1)).'0</td>
						'.insertCells($patients, ($i+1)).'
						<td></td>
					</tr>';
				}else{
					$string = '<tr>
						<td style="vertical-align:middle;text-align:center;width:50px;font-size:14px;color:#7e7e7e;font-weight:bold;">'.($starthour + ceil($i/$q1) - 1).":".($discretion*($i%$q1)).'</td>
						'.insertCells($patients, ($i+1)).'
						<td></td>
					</tr>';
				}
				array_push($table, $string);
			}
		}
		$tabledata['table'] = implode($table, "\n");
		$tabledata['date']  = implode(array($day, $month, $year), ".");
		$tabledata['headings']  = insertHCells($patients);
		$output['menu'] = $this->load->view('menu', $output, true);
		$output['content'] = $this->load->view('proc/shedule', $tabledata, true);
		$this->load->view('view', $output);
	}

	public function day($year, $month, $day){
		// $this->output->enable_profiler(TRUE);
		// calendar assumptions
		$starthour	= 8;
		$endhour	= 21;
		$discretion	= 30; // minutes
		$q1			= 60 / $discretion; // planner period
		$q2			= ($endhour -$starthour) * $q1; // planner length
		//
		$data		= array();
		$output		= array();
		$table		= array();
		$patients	= array();

		$result = $this->db->query("SELECT 
		service_calendar.contract_id,
		service_calendar.id as cal_id,
		IF(LENGTH(service_calendar.staff_id) OR service_calendar.staff_id > 0, 1, 0) AS staff,
		patients.id AS patid,
		CONCAT(patients.pat_f, ' ', LEFT(patients.pat_i, 1), '.' ,LEFT(patients.pat_o, 1), '.') AS fio,
		services.id,
		services.serv_name,
		services.serv_alias,
		((TIME_FORMAT(service_calendar.ordered_time, '%H')     - ?) * ?) + (TIME_FORMAT(service_calendar.ordered_time,     '%i') / ?) AS `ot`,
		((TIME_FORMAT(service_calendar.ordered_time_end, '%H') - ?) * ?) + (TIME_FORMAT(service_calendar.ordered_time_end, '%i') / ?) AS `ote`
		FROM
		service_calendar
		LEFT OUTER JOIN contracts ON (service_calendar.contract_id = contracts.id)
		LEFT OUTER JOIN courses   ON (contracts.crsid = courses.id)
		LEFT OUTER JOIN patients  ON (courses.pid = patients.id)
		LEFT OUTER JOIN services  ON (service_calendar.service_id = services.id)
		WHERE
		(DATE_FORMAT(service_calendar.ordered_date, '%Y%m%d') = ?)", array($starthour, $q1, $discretion, $starthour, $q1, $discretion, $year.$month.$day));

		if($result->num_rows()){
			// массив данных по услугам пациента
			foreach($result->result() as $row){
				$serv = array(
					'staffset'	=> $row->staff,
					'fio'		=> $row->fio,
					'cal_id'	=> $row->cal_id,
					'sn'		=> $row->serv_name,
					'sa'		=> $row->serv_alias,
					'sid'		=> $row->id,
					'id'		=> $row->patid,
					'ot'		=> (int) $row->ot,
					'ote'		=> (int) $row->ote);
				if(!isset($patients[$row->patid])){
					$patients[$row->patid] = array();
				}
				array_push($patients[$row->patid], $serv);
			}

			//print_r($patients);
			
			// вставка ячеек в строки
			foreach($patients as $val) {
				$n = 0;
				foreach($val as $servs){
					$icon = "&nbsp;";
					if(!$n){
						array_push($table, '<td rowspan="'.sizeof($val).'" style="vertical-align:middle;text-align:center;">'.$servs['fio'].'<br><small class="muted">'.$servs['sa'].'</small></td>');
						$n++;
					}
					for($quantum = 1; $quantum <=$q2; $quantum++){
						$classes = array('servcell');
						if($quantum <= $servs['ote'] && $quantum > $servs['ot']){
							array_push($classes, 'servactive');
							$icon = ($servs['staffset'])
								? '<img src="/images/alarm-clock.png" width="16" height="16" border="0" alt="">' 
								: '<img src="/images/alarm-clock-err.png" width="16" height="16" border="0" alt="">';
						}else{
							$icon = "&nbsp;";
						}
						array_push($table, '<td style="text-align:center;vertical-align:middle;" class="'.implode($classes, " ").' c'.$servs['cal_id'].'" title="'.$servs['sn'].'" sid="'.$servs['sid'].'" cal="'.$servs['cal_id'].'" ref="'.$quantum.'" fio="'.$servs['fio'].'">'.$icon.'</td>');
					}
					array_push($table, "</tr><tr>");
				}

			}
			//вставка ячеек заголовка
			$out1 = array();
			$out2 = array();
			for($i = 1; $i <= $q2; $i++){
				//row1
				if($i%2){
					$string = '<th colspan=2 style="vertical-align:middle;text-align:center;width:50px;font-size:14px;color:#7e7e7e;font-weight:bold;">'.($starthour + ceil($i/$q1) - 1).":".(($discretion*($i%$q1)) ? "00" : $discretion).'</th>';
					array_push($out1, $string);
				}
				//row2
				$string = '<th style="vertical-align:middle;text-align:center;width:50px;font-size:14px;color:#7e7e7e;font-weight:bold;">'.($starthour + ceil($i/$q1) - 1).":".(($discretion*($i%$q1)) ? "00" : $discretion).'</th>';
				array_push($out2, $string);
			}
			$headings1 = implode($out1, "\n");
			$headings2 = implode($out2, "\n");
			// calculation args
			// $discretion = 30; // minutes
			// $q1 = 60 / $discretion; // planner period
			// $q2 = ($endhour -$starthour) * $q1; // planner length
		}
		$tabledata['table']			= implode($table, "\n");
		$tabledata['date']			= implode(array($day, $month, $year), ".");
		$tabledata['headings1']		= $headings1;
		$tabledata['headings2']		= $headings2;
		$output['menu']				= $this->load->view('menu', $output, true);
		$output['content']			= $this->load->view('proc/shedule', $tabledata, true);
		$this->load->view('view', $output);
	}

	public function savetime(){
		$this->output->enable_profiler(TRUE);
		//return false;
		if($this->input->post('start') && $this->input->post('stop') && $this->input->post('cal')) {
			if(!$this->input->post('next') && !$this->input->post('all')){
				$result = $this->db->query("UPDATE
				service_calendar
				SET
				service_calendar.ordered_time = ?,
				service_calendar.ordered_time_end = ?,
				service_calendar.staff_id = ?
				WHERE
				service_calendar.id = ?", array(
					$this->input->post('start'),
					$this->input->post('stop'),
					$this->input->post('inst'),
					$this->input->post('cal')
					)
				);
				if($this->db->affected_rows()){
					print "OK";
				}
			}
			
			if($this->input->post('all')){
				$data = array();
				$result = $this->db->query("SELECT `service_calendar`.`id`
				FROM `service_calendar`
				WHERE `service_calendar`.`contract_id` = (
					SELECT `service_calendar`.`contract_id` FROM `service_calendar` WHERE `service_calendar`.id = ?
				)
				AND `service_calendar`.`service_id` = (
					SELECT `service_calendar`.`service_id` FROM `service_calendar` WHERE `service_calendar`.id = ?
				)", array(
					$this->input->post('cal'),
					$this->input->post('cal')
				));
				if($result->num_rows()){
					foreach($result->result() as $row){
						array_push($data, $row->id);
					}
					// по многочисленным заявкам ленивых секретарш в следующем запросе
					//
					// AND service_calendar.ordered_date > NOW() -- excluded
					//
					// теперь можно назначить на весь период, в том числе и в прошлом, но только на те, которые не были исполнены
					// ждём новых багов
					$this->db->query("UPDATE
					service_calendar
					SET
					service_calendar.ordered_time = ?,
					service_calendar.ordered_time_end = ?,
					service_calendar.staff_id = ?
					WHERE
					(service_calendar.id) IN (".implode($data, ", ").") 
					AND NOT service_calendar.is_done", array(
						$this->input->post('start'),
						$this->input->post('stop'),
						$this->input->post('inst')
					));
					if($this->db->affected_rows()){
						print "OK";
					}
				}
				return false;
			}

			if($this->input->post('next')){
				$data = array();
				$result = $this->db->query("SELECT `service_calendar`.`id`
				FROM `service_calendar`
				WHERE `service_calendar`.`contract_id` = (
					SELECT `service_calendar`.`contract_id` FROM `service_calendar` WHERE `service_calendar`.id = ?
				)
				AND `service_calendar`.`service_id` = (
					SELECT `service_calendar`.`service_id` FROM `service_calendar` WHERE `service_calendar`.id = ?
				)
				and `service_calendar`.id >= ?
				LIMIT 2", array($this->input->post('cal'), $this->input->post('cal'), $this->input->post('cal')));
				if($result->num_rows()){
					foreach($result->result() as $row){
						array_push($data, $row->id);
					}
					$this->db->query("UPDATE
					service_calendar
					SET
					service_calendar.ordered_time = ?,
					service_calendar.ordered_time_end = ?,
					service_calendar.staff_id = ?
					WHERE
					(service_calendar.id) IN (".implode($data, ", ").") 
					AND NOT service_calendar.is_done", array(
						$this->input->post('start'),
						$this->input->post('stop'),
						$this->input->post('inst')
					));
					if($this->db->affected_rows()){
						print "OK";
					}
				}
				return false;
			}
		}
	}

	public function getinst(){
		$list = array();
		$info = array();
		$inst = 0;
		$result = $this->db->query("SELECT DISTINCT
		`staff`.id,
		CONCAT_WS(' ', `staff`.staff_f, `staff`.staff_i, `staff`.staff_o) AS fio,
		`staff`.staff_location,
		`staff`.staff_phone,
		`staff`.staff_staff,
		`staff`.staff_rank
		FROM
		`servordering`
		INNER JOIN `staff` ON (`servordering`.staffid = `staff`.id)
		WHERE `servordering`.`servid` = ?", array($this->input->post("service")));
		if($result->num_rows()){
			foreach($result->result() as $row){
				array_push($list, '<option value="'.$row->id.'">'.$row->fio.'</option>');
				array_push($info, $row->id.": { fio: '".$row->fio."', phone: '".$row->staff_phone."', rank: '".$row->staff_rank."', location: '".$row->staff_location."', staff: '".$row->staff_staff."', }");
			}
		}
		$result = $this->db->query("SELECT 
		if(LENGTH(service_calendar.staff_id) OR NOT ISNULL(service_calendar.staff_id), service_calendar.staff_id, 0) as staff_id
		FROM
		service_calendar
		WHERE
		(service_calendar.id = ?)", array($this->input->post("cal")));
		if($result->num_rows()){
			$row = $result->row();
			$inst = $row->staff_id;
		}
		$string = "var staffdata = { 
			suitableList: '".implode($list, "")."',
			inst: ".$inst.",
			info: { ".implode($info, ", ")." }
		}";
		print $string;
	}

	public function saveinst(){
		if($this->input->post('inst') && $this->input->post('cal')){
			if(!$this->input->post('next') && !$this->input->post('all')){
				$this->db->query("UPDATE 
				`service_calendar` 
				SET `service_calendar`.staff_id = ? 
				WHERE (`service_calendar`.id = ?)", array($this->input->post('inst'), $this->input->post('cal')));
				if($this->db->affected_rows()){
					print "OK";
				}
				return false;
			}

			if($this->input->post('all')){
				$data = array();
				$result = $this->db->query("SELECT `service_calendar`.`id`
				FROM `service_calendar`
				WHERE `service_calendar`.`contract_id` = (
					SELECT `service_calendar`.`contract_id` FROM `service_calendar` WHERE `service_calendar`.id = ?
				)
				AND `service_calendar`.`service_id` = (
					SELECT `service_calendar`.`service_id` FROM `service_calendar` WHERE `service_calendar`.id = ?
				)", array($this->input->post('cal'), $this->input->post('cal')));
				if($result->num_rows()){
					foreach($result->result() as $row){
						array_push($data, $row->id);
					}
					$this->db->query("UPDATE
					service_calendar
					SET
					staff_id = ?
					WHERE
					(service_calendar.id) IN (".implode($data, ", ").") 
					AND NOT service_calendar.is_done", array($this->input->post('inst'), $this->input->post('cal'), $this->input->post('cal')));
					if($this->db->affected_rows()){
						print "OK";
					}
				}
				return false;
			}

			if($this->input->post('next')){
				$data = array();
				$result = $this->db->query("SELECT `service_calendar`.`id`
				FROM `service_calendar`
				WHERE `service_calendar`.`contract_id` = (
					SELECT `service_calendar`.`contract_id` FROM `service_calendar` WHERE `service_calendar`.id = ?
				)
				AND `service_calendar`.`service_id` = (
					SELECT `service_calendar`.`service_id` FROM `service_calendar` WHERE `service_calendar`.id = ?
				)
				AND `service_calendar`.id >= ? 
				AND NOT service_calendar.is_done
				LIMIT 2", array($this->input->post('cal'), $this->input->post('cal'), $this->input->post('cal')));
				if($result->num_rows()){
					foreach($result->result() as $row){
						array_push($data, $row->id);
					}
					$this->db->query("UPDATE
					service_calendar
					SET
					staff_id = ?
					WHERE
					(service_calendar.id) IN (".implode($data, ", ").") AND
					service_calendar.is_done", array($this->input->post('inst'), $this->input->post('cal'), $this->input->post('cal')));
					if($this->db->affected_rows()){
						print "OK";
					}
				}
				return false;
			}
		}
	}

	public function myshedule(){
		$data = array();
		$outdata = array();
		$output = array();
		$lines = array();
		$starthour = 8;
		$endhour = 21;
		$discretion = 30; // minutes
		$q1 = 60 / $discretion; // planner period
		$q2 = ($endhour -$starthour) * $q1; // planner length
		$result = $this->db->query("SELECT 
		DATE_FORMAT(`service_calendar`.ordered_date, '%d.%m.%Y') AS od,
		TIME_FORMAT(`service_calendar`.ordered_time, '%H:%i') as st,
		TIME_FORMAT(`service_calendar`.ordered_time_end, '%H:%i') as et,
		((TIME_FORMAT(service_calendar.ordered_time, '%H')     - ?) * ?) + (TIME_FORMAT(service_calendar.ordered_time,     '%i') / ?) AS `ot`,
		((TIME_FORMAT(service_calendar.ordered_time_end, '%H') - ?) * ?) + (TIME_FORMAT(service_calendar.ordered_time_end, '%i') / ?) AS `ote`,
		`service_calendar`.note,
		`service_calendar`.is_done,
		`service_calendar`.id,
		`services`.serv_name,
		  CONCAT(`patients`.pat_f, ' ', LEFT(`patients`.pat_i, 1), '.', LEFT(`patients`.pat_o, 1), '.') AS pat_fio,
		`patients`.pat_address,
		`patients`.pat_location
		FROM
		`services`
		INNER JOIN `service_calendar` ON (`services`.id = `service_calendar`.service_id)
		LEFT OUTER JOIN `contracts` ON (`service_calendar`.contract_id = `contracts`.id)
		LEFT OUTER JOIN `courses` ON (`contracts`.crsid = `courses`.id)
		INNER JOIN `patients` ON (`courses`.pid = `patients`.id)
		WHERE
		`service_calendar`.staff_id = ?
		AND DATE_FORMAT(`service_calendar`.ordered_date, '%Y%m%d') >= DATE_FORMAT(NOW(), '%Y%m%d')
		order by 
		`service_calendar`.ordered_date,
		`service_calendar`.ordered_time", array($starthour, $q1, $discretion, $starthour, $q1, $discretion, $this->session->userdata('userid')));
		if($result->num_rows()){
			foreach($result->result_array() as $row){
				if(sizeof($data) > 10){
					break;
				}
				if(!isset($data[$row['od']])){
					$data[$row['od']] = array();
				}
				array_push($data[$row['od']], $row);
			}
		}
		
		/*for($i=0; $i<=$q2; $i++){
			array_push($lines, '<td style="width:30px;text-align:center;">'.($starthour + ceil($i/2)).(($i%2) ? ":00" : ":30" ).'</td>');
		}
		*/
		
		foreach($data as $day=>$services){
			array_push($lines, '<tr><td colspan="'.($q2).'" class="muted" style="font-size:16px;"><strong>'.$day.'</strong></td></tr>');
			foreach($services as $service){
				$line = array();
				$span = ((int) $service['ote'] - (int) $service['ot']);
				for($i=1; $i<=$q2; $i++){
					if(($i > (int) $service['ot']) && ($i <= ((int) $service['ote']))){
						$i = $i + $span;
						$fin = 0;
						$stext = '<strong>'.$service['st'].' - '.$service['et'].'</strong>';
						$servcolor = substr(md5($service['serv_name']), 7, 3);
						if($service['is_done']){
							$servcolor = "484";
							$stext = '<img src="/images/tick-button.png" width="16" height="16" border="0" alt="">';
							$fin = 1;
						}
						$color = (hexdec($servcolor) > 800) ? '000' : 'fff' ;
						array_push($line, '<td class="servData" colspan='.($span).' fin="'.$fin.'" ref="'.$service['id'].'" style="width:30px;text-align:center;color:#'.$color.';background-color:#'.$servcolor.';" title="'.implode(array($service['pat_fio'], "\nпроцедура: ".$service['serv_name'].";", "\nадрес: ".$service['pat_address'], $service['pat_location']), " ").'">'.$stext.'</td>');
						//array_push($line, '<td style="width:30px;text-align:center;background-color:#33cccc;">'.(8+(($i-1)*0.5)).'</td>');
					}
					if($i < $q2){
						array_push($line, '<td style="width:30px;text-align:center;">-</td>');
					}
				}
				array_push($lines, '<tr title="'.$service['serv_name'].'">'.implode($line, "\n").'</tr>');
			}
			

		}
			array_push($output, implode($lines,"\n"));
		$outdata['content'] = implode($output, "\n");
		return $this->load->view("proc/myshedule", $outdata, true);
	}

	public function pload($year, $month, $day){
		//print $year.$month.$day;
		//$this->output->enable_profiler(TRUE);
		$input      = array();
		$output     = array();
		$staff      = array();
		$busy       = array();
		$serv       = array();
		$out        = array();
		$starthour  = 8;
		$endhour    = 21;
		$discretion = 30; // minutes
		$q1         = 60 / $discretion; // planner period
		$q2         = ($endhour -$starthour) * $q1; // planner length

		$result = $this->db->query("SELECT 
		CONCAT(`staff`.staff_f, ' ', LEFT(`staff`.staff_i, 1), '.', LEFT(`staff`.staff_o, 1), '.') AS fio,
		`staff`.id
		FROM
		`staff`");
		if($result->num_rows()){
			foreach($result->result() as $row){
				$fio = (strlen($row->fio)) ? $row->fio : '<span title="Имя не распознано. Уточните данные сотрудника">Ошибка!</span>';
				$staff[$row->id] = $fio;
			}
		}

		$result = $this->db->query("SELECT 
		IF(LENGTH(`services`.serv_alias), `services`.serv_alias, `services`.serv_name) AS sn,
		`services`.id
		FROM
		`services`");
		if($result->num_rows()){
			foreach($result->result() as $row){
				$serv[$row->id] = $row->sn;
			}
		}

		$result = $this->db->query("SELECT 
		((TIME_FORMAT(service_calendar.ordered_time, '%H')     - ?) * ?) + (TIME_FORMAT(service_calendar.ordered_time,     '%i') / ?) AS `ot`,
		((TIME_FORMAT(service_calendar.ordered_time_end, '%H') - ?) * ?) + (TIME_FORMAT(service_calendar.ordered_time_end, '%i') / ?) AS `ote`,
		service_calendar.staff_id,
		service_calendar.is_done,
		service_calendar.done_by,
		service_calendar.id,
		service_calendar.service_id,
		CONCAT(`patients`.pat_f, ' ',LEFT(`patients`.pat_i, 1),'.',LEFT(`patients`.pat_o, 1), '.') AS pat
		FROM
		service_calendar
		LEFT OUTER JOIN `contracts` ON (service_calendar.contract_id = `contracts`.id)
		LEFT OUTER JOIN `courses` ON (`contracts`.crsid = `courses`.id)
		LEFT OUTER JOIN `patients` ON (`courses`.pid = `patients`.id)
		WHERE 
		DATE_FORMAT(service_calendar.ordered_date, '%Y%m%d') = ?
		ORDER BY
		service_calendar.ordered_date, service_calendar.ordered_time", array(
			$starthour,
			$q1,
			$discretion,
			$starthour,
			$q1,
			$discretion,
			implode(array($year, $month, $day),"")
		));
		if($result->num_rows()){
			foreach($result->result() as $row){
				if(!isset($input[$row->staff_id])){
					$input[$row->staff_id] = array();
				}
				if(!isset($busy[$row->staff_id])){
					$busy[$row->staff_id] = array();
				}
				for($i = (int) $row->ot; $i <= (int) $row->ote; $i++){
					if(!isset($busy[$row->staff_id][$i])){
						$busy[$row->staff_id][$i] = array();
					}
					array_push($busy[$row->staff_id][$i], array($serv[$row->service_id], $row->is_done, $row->id, $row->pat, $row->id));
				}
				array_push($input[$row->staff_id], $row);
			}
		}
		$timeline = "";
		for($i=1; $i<=$q2-2; $i++){
			$timeline .= '<td style="font-size:10px;font-weight:bold;text-align:center;">'.($starthour + ceil($i/2)).(($i%2) ? ":00" : ":30" ).'</td>';
		}
		foreach($input as $key=>$staffdata){
			$staffname = (isset($staff[$key])) ? $staff[$key] : "";
			array_push($output, '<tr><td colspan="'.$q2.'"><h5 id="s'.$key.'">'.$staffname.'</h5></td></tr>');
			$line = array();
			for($i=1; $i<=$q2-2; $i++){
				$color = '';
				$class = array();
				$title = '';
				$text  = ($starthour +(($i-1)*0.5));
				if(isset($busy[$key][$i])){
					$title = $busy[$key][$i][0][0];
					$text  = $title;
					if(!$busy[$key][$i][0][1]){
						array_push($class, 'undone');
					}else{
						$text = '<center><img src="/images/tick-button.png" width="16" height="16" border="0" alt=""></center>';
					}
					if(sizeof($busy[$key][$i]) > 1){
						$class = array();
						array_push($class, 'collision');
						$title = "Коллизия в планировании: ".sizeof($busy[$key][$i]);
						$text  = "Ошибка";
					}else{
						array_push($class, 'good');
					}
					$string = '<td title="'.$title.' - '.$busy[$key][$i][0][3].'" class="busy '.implode($class," ").'" servid="'.$busy[$key][$i][0][4].'" sref="s'.$key.'" ref="'.$busy[$key][$i][0][2].'" style="width:'.ceil(100/$q2).'%;">'.$text.'</td>';
				}else{
					$string = '<td title="инструктор свободен" style="width:'.ceil(100/$q2).';text-align:center;">-</td>';
				}
				array_push($line, $string);
			}
			array_push($output, '<tr>'.implode($line, "\n").'</tr>');
		}

		$out['nextdate'] = date('Y/m/d', mktime(0, 0, 0, $month , $day+1 , $year));
		$out['prevdate'] = date('Y/m/d', mktime(0, 0, 0, $month , $day-1 , $year));
		$out['nowdate']  = implode(array($day, $month, $year), ".");
		$out['timeline'] = '<tr>'.$timeline.'</tr>';
		$out['content']  = implode($output, "\n");
		return $this->load->view("proc/pload", $out, true);
	}

	public function jobdone(){
		$nr = $this->input->post('noredirect', true);
		$result = $this->db->query("UPDATE
		`service_calendar`
		SET
		`service_calendar`.is_done = 1,
		`service_calendar`.done_by = ?,
		`service_calendar`.note = ?
		WHERE
		`service_calendar`.id = ?", array(
			$this->session->userdata('userid'),
			$this->input->post('comment', true),
			(int) $this->input->post('calId', true)
		));
		if (!$nr){
			$this->load->helper("url");
			redirect("start");
		}else{
			print $this->input->post('calId', true);
		}
	}

	public function jobundone(){
		$nr = $this->input->post('noredirect', true);
		$result = $this->db->query("UPDATE
		`service_calendar`
		SET
		`service_calendar`.is_done = 0,
		`service_calendar`.done_by = ?,
		`service_calendar`.note = ?
		WHERE
		`service_calendar`.id = ?", array(
			$this->session->userdata('userid'),
			$this->input->post('comment', true),
			(int) $this->input->post('calId', true)
		));
		if (!$nr){
			$this->load->helper("url");
			redirect("start");
		}else{
			print $this->input->post('calId', true);
		}
	}

	public function shed_summary_get(){
		$output          = array();
		$input           = array();
		$input['sdone']  = array();
		$input['sndone'] = array();
		$input['missed'] = array();
		$input['done']   = 0;
		$input['back']   = 0;
		$input['forw']   = 0;
		$result = $this->db->query("SELECT
		`services`.`id`,
		COUNT(`service_calendar`.`id`) as `counts`,
		`services`.serv_name,
		(SUM(`services`.serv_price) / 100) as ssum,
		IF(DATEDIFF(NOW(), `service_calendar`.`ordered_date`) < 0, 0, 1) as missed,
		`service_calendar`.is_done
		FROM
		`service_calendar`
		INNER JOIN `services` ON (`service_calendar`.service_id = `services`.id)
		WHERE DATE_FORMAT(`service_calendar`.`ordered_date`, '%Y%m') = DATE_FORMAT(NOW(), '%Y%m')
		GROUP BY `services`.`id`, `service_calendar`.`is_done`, missed");
		if($result->num_rows()){
			foreach($result->result() as $row){
				$string = '<tr class="shedServData" servref="'.$row->id.'"><td>'.$row->serv_name.'</td><td>'.$row->counts.'</td><td>'.($row->ssum).'</td></tr>';
				if($row->missed && !$row->is_done){
					array_push($input['missed'], $string);
					$input['back'] += (int) $row->ssum;
					continue;
				}
				if($row->is_done){
					array_push($input['sdone'], $string);
					$input['done'] += (int) $row->ssum;
					continue;
				}else{
					array_push($input['sndone'], $string);
					$input['forw'] += (int) $row->ssum;
				}
			}
		}
		$output['sdone']  = implode($input['sdone'],  "\n");
		$output['sndone'] = implode($input['sndone'], "\n");
		$output['missed'] = implode($input['missed'], "\n");
		$output['forw']   = $input['forw'];
		$output['back']   = $input['back'];
		$output['done']   = $input['done'];

		return $this->load->view("proc/shedsummary", $output, true);
	}

	public function payment_table_get($client_id = 0){
		$client_id = ($this->input->post('client_id')) ? $this->input->post('client_id') : $client_id;
		$clients   = array('<option value="0">Выберите клиента</option>');
		$contracts = array();
		$services  = array();
		$servlist  = array();
		$pay_in    = array();
		$pay_out   = array();
		$act       = array(
			'client_fio' => "ФИО клиента",
			'deposit'    => 0,
			'paid'       => 0,
			'client'     => 'нет данных'
		);
		$act['rest'] = ($act['deposit'] - $act['paid']);
		######################################################
		$result = $this->db->query("SELECT 
		services.serv_alias,
		services.serv_name,
		services.id
		FROM
		services
		WHERE
		(services.active)");
		if($result->num_rows()){
			foreach($result->result() as $row){
				$servlist[$row->id] = '['.$row->serv_alias.']'.$row->serv_name;
				array_push($services, '<option value="'.$row->id.'">['.$row->serv_alias.']'.$row->serv_name.'</option>');
			}
		}
		
		$result = $this->db->query("SELECT DISTINCT
		CONCAT_WS(' ', clients.cli_f, clients.cli_i, clients.cli_o) AS cli_fio,
		clients.id
		FROM
		`patients`
		RIGHT OUTER JOIN clients ON (`patients`.pat_clientid = clients.id)
		LEFT OUTER JOIN `courses` ON (`patients`.id = `courses`.pid)
		LEFT OUTER JOIN `contracts` ON (`courses`.id = `contracts`.crsid)
		WHERE
		(clients.active) AND `contracts`.`active`
		ORDER BY
		cli_fio");
		if($result->num_rows()){
			foreach($result->result() as $row){
				$selected = "";
				if($client_id == $row->id){
					$selected = ' selected="selected"';
					$act['client_fio'] = $act['client'] = $row->cli_fio;
				}
				$string = '<option value="'.$row->id.'"'.$selected.'>'.$row->cli_fio.'</option>';
				array_push($clients, $string);
			}
		}
		$act['clients'] = implode($clients, "\n");



		if($client_id){
			$result = $this->db->query("SELECT 
			TRUNCATE(SUM(services.serv_price) / 100, 2) AS sp
			FROM
			services
			RIGHT OUTER JOIN service_calendar ON (services.id = service_calendar.service_id)
			LEFT OUTER JOIN contracts ON (service_calendar.contract_id = contracts.id)
			LEFT OUTER JOIN courses ON (contracts.crsid = courses.id)
			LEFT OUTER JOIN `patients` ON (courses.pid = `patients`.id)
			WHERE
			(`patients`.`pat_clientid` = ?) AND
			(service_calendar.is_done)", array($client_id));
			if($result->num_rows()){
				$row = $result->row();
				$sp = (strlen($row->sp)) ? $row->sp : "0" ;
				$act['paid'] = $sp;
			}

			$result = $this->db->query("SELECT 
			CONCAT_WS(' - ', DATE_FORMAT(contracts.cont_date_start, '%d.%m.%Y'), DATE_FORMAT(contracts.cont_date_end, '%d.%m.%Y')) AS ds,
			contracts.cont_number ,
			contracts.active AS c_act,
			DATE_FORMAT(payments.getdate, '%d.%m.%Y') AS gd,
			TRUNCATE(payments.`sum` / 100, 2) AS sum,
			payments.active AS p_act,
			DATE_FORMAT(payments.deactdate, '%d.%m.%Y') AS dd,
			contracts.id as c_id,
			payments.id as p_id,
			payments.service_id,
			payments.dtype,
			payments.dnum
			FROM
			contracts
			LEFT OUTER JOIN courses ON (contracts.crsid = courses.id)
			LEFT OUTER JOIN patients ON (courses.pid = patients.id)
			LEFT OUTER JOIN clients ON (patients.pat_clientid = clients.id)
			LEFT OUTER JOIN payments ON (contracts.id = payments.contract_id)
			WHERE
			`clients`.`id` = ?", array($client_id));
			if($result->num_rows()){
				foreach($result->result() as $row){
					$class = array();
					$delbtn = "";
					array_push($class, ((!$row->c_act) ? "muted" : ""));
					array_push($class, ((!$row->p_act) ? "error hide" : ""));
					if(!isset($contracts[$row->c_id])){
						$contracts[$row->c_id] = array(
							'num' => $row->cont_number,
							'ds'  => $row->ds,
							'pm'  => array()
						);
						$divstring = '<tr>
							<td colspan=6>
							Контракт № '.$row->cont_number. ' ['.$row->ds.'] <button class="btn btn-success btn-small pull-right p_add" ref="'.$row->c_id.'" title="'.$act['client'].' - '.$row->cont_number. ' ['.$row->ds.']" style="margin-right:20px;">Внести платёж</button>
							</td>
						</tr>
						<tr><th style="width:50px;">№ п/п</th><th>Сумма</th><th>Дата</th><th>Целевая услуга</th><th>Реквизиты</th><th style="width:100px;">Удалить платёж</th></tr>';
						array_push($pay_out, $divstring);
					}
					if($row->p_act){
						$act['deposit'] += $row->sum;
						$delbtn = '<a href="#" class="btn btn-mini btn-warning p_del" ref="'.$row->p_id.'">Удалить</a>';
					}
					$servname = ($row->service_id) ? $servlist[$row->service_id] : "Нецелевой платёж";
					$dtype = ($row->dtype == "bso") ? "БСО" : "чек";
					$string = '<tr class="'.implode($class, " ").'"><td>'.(sizeof($contracts[$row->c_id]['pm']) + 1).'</td><td>'.($row->sum).'</td><td>'.$row->gd.'</td><td>'.$servname.'</td><td>'.$dtype.': №  '.$row->dnum.'</td><td>'.$delbtn.'</td></tr>';
					array_push($contracts[$row->c_id]['pm'], $string);
				}
				foreach($contracts as $key => $val){
					array_push($pay_out, implode($val['pm'], "\n"));
				}
			}else{
				array_push($pay_out, '<tr><td colspan=4>Нет данных о полученных платежах</td></tr>');
			}
		}

		$act['client_id'] = $client_id;
		$act['services']  = implode($services, "\n");
		$act['rest']      = $act['deposit'] - $act['paid'];
		$act['deposit']   = $act['deposit'];
		$act['payments']  = implode($pay_out, "\n");
		$act['servlist']  = $this->cli_serv_get($client_id);
		return $this->load->view('proc/paytable', $act, true);
	}

	public function cli_serv_get($client_id){
		$input  = array();
		$output = array();
		$sum    = array();
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
		LEFT OUTER JOIN contracts ON (service_calendar.contract_id = contracts.id)
		LEFT OUTER JOIN courses ON (contracts.crsid = courses.id)
		LEFT OUTER JOIN services ON (service_calendar.contract_id = services.id)
		LEFT OUTER JOIN `patients` ON (courses.pid = `patients`.id)
		WHERE
		`patients`.`pat_clientid` = ?
		ORDER BY
		service_calendar.ordered_date", array($client_id));

		if($result->num_rows()){
			foreach($result->result() as $row){
				if(!isset($input[$row->contract_id])){
					$conclass = array();
					$ctitle   = array();
					($row->cont_active) ? array_push($ctitle, "Контракт активен") : array_push($ctitle, "Контракт неактивен");
					($row->cont_active) ? array_push($conclass, "success") : array_push($conclass, "danger");
					$input[$row->contract_id] = array('<table class="table table-bordered table-condensed table-striped">
					<tr class="'.implode($conclass, " ").'">
					<td colspan="5">
						<a href="/contracts/show/'.$row->contract_id.'" target="_blank">Контракт №'.$row->cont_number.'&nbsp;&nbsp;&nbsp;<small>'.$row->ctm.'&nbsp;&nbsp;'.implode($ctitle, " ").'</small></a>
					</td>
					</tr>
					<tr>
					<th style="width:120px;">Дата/Время</th>
					<th>Услуга</th>
					<th>Установленная цена</th>
					<th style="width:70px;">Оказана</th>
					<th>Сумма к списанию</th>
					</tr>');
					$sum[$row->contract_id] = 0;
				}
				$srclass = array();
				$rclass  = array();
				$rtitle  = array();
				($row->serv_active) ? "" : array_push($srclass, "muted");
				($row->serv_active) ? array_push($rtitle, "Услуга оказывается")	: array_push($rtitle, "Услуга не оказывается");
				($row->is_done) ? array_push($rclass, "success") : "";
				($row->is_done) ? array_push($rtitle, "услуга оказана")			: array_push($rtitle, "услуга не оказана");
				($row->is_done) ? $sum[$row->contract_id] += $row->pf			: "" ;

				array_push($input[$row->contract_id], '<tr class="'.implode($rclass, " ").'" title="'.implode($rtitle, ", ").'">
				<td>'.$row->dtm.'</td>
				<td class="'.implode($srclass, " ").'">'.$row->serv_name.'</td>
				<td style="width:150px;">'.$row->pf.' рублей</td>
				<td>'.(($row->is_done) ? "Да" : "Нет").'</td>
				<td style="width:150px;">'.(($row->is_done) ? "<strong>".$row->pf."</strong>" : "0.00").' рублей</td>
				</tr>');
			}
			
		}
		foreach($input as $key=>$val){
			array_push($output, implode($val)."<tr><td colspan=3></td><td><strong>ИТОГО</strong></td><td><strong>".$sum[$key]." рублей</strong></td></tr></table>");
		}
		return implode($output, "\n");
	}

}
#
/* End of file shedmodel.php */
/* Location: ./application/models/shedmodel.php */