<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Docmodel extends CI_Model{
	
	function __construct(){
		parent::__construct();
	}

	public function num2words($num){
		// приводим к числу
		$num = $num + 0;
		$digits = strlen($num);
		// определяем входные массивы, массив десятичных разрядных кванторов и массивы конверсии
		$input = array();
		$phrase = array();
		$substs = array(
			0 => '',
			1 => 'один',
			2 => 'два',
			3 => 'три',
			4 => 'четыре',
			5 => 'пять',
			6 => 'шесть',
			7 => 'семь',
			8 => 'восемь',
			9 => 'девять'
		);
		$substs10 = array(
			0 => '',
			1 => 'одиннадцать',
			2 => 'двенадцать',
			3 => 'тринадцать',
			4 => 'четырнадцать',
			5 => 'пятнадцать',
			6 => 'шестнадцать',
			7 => 'семнадцать',
			8 => 'восемнадцать',
			9 => 'девятнадцать'
		);
		$substs100 = array(
			0 => '',
			1 => 'десять',
			2 => 'двадцать',
			3 => 'тридцать',
			4 => 'сорок',
			5 => 'пятьдесят',
			6 => 'шестьдесят',
			7 => 'семьдесят',
			8 => 'восемьдесят',
			9 => 'девяносто'
		);
		$substs1000 = array(
			0 => '',
			1 => 'сто',
			2 => 'двести',
			3 => 'триста',
			4 => 'четыреста',
			5 => 'пятьсот',
			6 => 'шестьсот',
			7 => 'семьсот',
			8 => 'восемьсот',
			9 => 'девятьсот'
		);
		$quantors = array(
			0 => '',
			1 => 'тысяч',
			2 => 'миллион',
			3 => 'миллиард',
			4 => 'триллион',
			5 => 'квадриллион',
			6 => 'квинтиллион'
		);
		// setup done. computing :)
		// реверс строки для корректной обработки порядка разрядов
		$num = strrev($num);
		// рубим на разрядные группы 10^3
		for($i=0; $i*3 < $digits; $i++){
			array_push($input, substr($num, ($i*3), 3));
		}
		// ограничиваем длину до разумной величины
		if(sizeof($input) > 7){
			$input = array_slice($input, -7);
			//print "Друг, ты что - заработал все деньги мира?<br>";
		}

		foreach($input as $key => $val){
			// если разрядная группа содержит только нули, пропускаем её
			if($val == "000"){
				continue;
			}
			$cluster = array();
			// дополняем разрядную группу до полной тройки чисел.
			if(strlen($val) == 1){
				$val .= '00';
			}
			if(strlen($val) == 2){
				$val .= "0";
			}
			// определяем окончания разрядных кванторов
			$finish = "";
			// общий случай квантора
			if($key > 1){
				if(in_array($val[0], array(5, 6, 7, 8, 9))){
					$finish = "ов";
				}
				if(in_array($val[0], array(2, 3, 4))){
					$finish = "а";
				}
			}
			// квантор тысяч. Ужасный русский язык, тысяча - женского рода.
			if($key == 1){
				$finish = "а";
				if(in_array($val[0], array(5, 6, 7, 8, 9))){
					$finish = "";
				}
				if(in_array($val[0], array(2, 3, 4))){
					$finish = "и";
				}
			}

			// обработка чисел разрядных групп.
			// возвращаем числу правильный порядок разрядов в группе.
			$val = strrev($val);
			//если не тысячи
			if($key !== 1){
				// разряд сотен
				$cluster[0] = $substs1000[$val[0]];
				// разряд десятков. Учитываем специфику чисел 11-19
				if ($cluster[1] = ($val[1] == 1 && $val[2] !== 0)){
					$cluster[1] = $substs10[$val[2]];
					// сброс в массив-накопитель
					array_push($phrase, $quantors[$key].$finish);
					array_push($phrase, implode(" ", $cluster));
					// выход из обработки разрядной группы
					continue;
				}else{
					// обычные десятки: 20-90
					$cluster[1] = $substs100[$val[1]];
				}
				// разряд единиц.
				$cluster[2] = $substs[$val[2]];
			}else{
				// тысячи. Снова ужасы русского языка.
				$cluster[0] = $substs1000[$val[0]];
				if ($cluster[1] = ($val[1] == 1 && $val[2] !== 0)){
					$cluster[1] = $substs10[$val[2]];
					// сброс в массив-накопитель
					array_push($phrase, $quantors[$key].$finish);
					array_push($phrase, implode("", $cluster));
					continue;
				}else{
					$cluster[1] = $substs100[$val[1]];
				}
				// собственно, сами ужасы
				if($val[2] == 1){
					$cluster[2] = 'одна';
				}elseif($val[2] == 2){
					$cluster[2] = 'две';
				}else{
					$cluster[2] = $substs[$val[2]];
				}
			}
			// сброс в массив-накопитель
			array_push($phrase, $quantors[$key].$finish);
			array_push($phrase, implode(" ", $cluster));
		}
		return implode(" ", array_reverse($phrase));
	}

	public function contract_get($id){
		$result = $this->db->query("SELECT 
		CONCAT_WS(' ', patients.pat_f, patients.pat_i, patients.pat_o) AS pat_ffio,
		CONCAT_WS(' ', patients.pat_f, CONCAT(UCASE(LEFT(patients.pat_i, 1)),'.', UCASE(LEFT(patients.pat_o, 1)),'.')) AS pat_fio,
		IF(RIGHT(patients.pat_o, 2) = 'ич', 'm', 'f') AS pat_gender,
		CONCAT_WS(' ', clients.cli_f, clients.cli_i, clients.cli_o) AS cli_ffio,
		CONCAT_WS(' ', clients.cli_f, CONCAT(UCASE(LEFT(clients.cli_i, 1)), '.', UCASE(LEFT(clients.cli_o, 1)), '.')) AS cli_fio,
		IF(RIGHT(clients.cli_o, 2) = 'ич', 'm', 'f') AS cli_gender,
		clients.cli_hphone,
		clients.cli_cphone,
		clients.cli_mail,
		clients.cli_address,
		clients.cli_pass_s,
		clients.cli_pass_n,
		clients.cli_pass_issued,
		patients.pat_location,
		patients.pat_address,
		patients.pat_pass_s,
		patients.pat_pass_n,
		patients.pat_pass_issued,
		DATE_FORMAT(patients.pat_pass_issuedate, '%d.%m.%Y') AS pat_pass_issuedate,
		DATE_FORMAT(clients.cli_pass_issuedate, '%d.%m.%Y') AS cli_pass_issuedate,
		DATE_FORMAT(`contracts`.cont_date_start, '%d.%m.%Y') AS cont_date_start,
		DATE_FORMAT(`contracts`.cont_date_end, '%d.%m.%Y') AS cont_date_end,
		`contracts`.cont_number
		FROM
		patients
		LEFT OUTER JOIN clients ON (patients.pat_clientid = clients.id)
		INNER JOIN `courses` ON (patients.id = `courses`.pid)
		INNER JOIN `contracts` ON (`courses`.id = `contracts`.crsid)
		WHERE
		(`contracts`.`id` = ?)", array($id));
		if ( $result->num_rows() ) {
			$out = $result->row_array();
			$stm = explode("." ,$out['cont_date_start']);
			$etm = explode("." ,$out['cont_date_end']);
			$months = array( 1 => 'января', 2 => 'февраля', 3 => 'марта', 4 => 'апреля', 5 => 'мая', 6 => 'июня', 7 => 'июля', 8 => 'августа', 9 => 'сентября', 10 => 'октября', 11 => 'ноября', 12 => 'декабря' );
			$out['cont_date_start_w'] = implode("", array($stm[0], $months[(int) $stm[1]], $stm[2]));
			$out['cont_date_end_w']   = implode("", array($etm[0], $months[(int) $etm[1]], $etm[2]));
			$out['cli_gender1'] = ($out['cli_gender'] == 'm') ? 'ый' : 'ая' ;
			$out['cli_gender2'] = ($out['cli_gender'] == 'm') ? 'ий' : 'ая' ;
			$out['cli_gender3'] = ($out['cli_gender'] == 'm') ? '' : 'а' ;
			$out['pat_gender1'] = ($out['pat_gender'] == 'm') ? 'ый' : 'ая' ;
			$out['pat_gender2'] = ($out['pat_gender'] == 'm') ? 'ий' : 'ая' ;
			$out['pat_gender3'] = ($out['pat_gender'] == 'm') ? '' : 'а' ;
			$out['pat_gender4'] = ($out['pat_gender'] == 'm') ? 'ен' : 'на' ;
		}else{
			print "Не найден запрошенный контракт";
			exit;
		}

		$result = $this->db->query("SELECT 
		`contract_services`.bas_num,
		`services`.serv_name,
		`services`.serv_loc,
		`services`.serv_desc,
		`contract_services`.refprice as serv_price
		FROM
		`services`
		INNER JOIN `contract_services` ON (`services`.id = `contract_services`.serv_id)
		WHERE `contract_services`.cont_id = ?", array($id));
		$totalprice = 0;
		$pricetable = array('<tr>
			<td style="width:10mm;border:1px solid black;text-align:center;font-weight:bold;">№ п/п</td>
			<td style="width:40mm;border:1px solid black;text-align:center;font-weight:bold;"><p>Пациент<br />Ф.И.О</p></td>
			<td style="width:50mm;border:1px solid black;text-align:center;font-weight:bold;">Адрес оказания услуги</td>
			<td style="width:50mm;border:1px solid black;text-align:center;font-weight:bold;">Наименование Услуги</td>
			<td style="width:30mm;border:1px solid black;text-align:center;font-weight:bold;">Количество Услуг</td>
			<td style="width:30mm;border:1px solid black;text-align:center;font-weight:bold;">Стоимость одной Услуги, рублей</td>
			<td style="width:30mm;border:1px solid black;text-align:center;font-weight:bold;">Общая стоимость, рублей</td>
		</tr>');
		if($result->num_rows()){
			foreach($result->result() as $row){
				$totalprice += ($row->bas_num * $row->serv_price);
				$price = ($row->serv_price % 100 == 0) ? ($row->serv_price / 100).",00" : ($row->serv_price / 100).",".$row->serv_price % 100;
				//$price = ($row->serv_price % 100 == 0) ? ($row->serv_price / 100).",00" : ($row->serv_price / 100).",".$row->serv_price % 100;
				$s_location = ($row->serv_loc == "center") ? "Центр Здоровья<br>г. Санкт-Петербург, Большой проспект Васильевского Острова, д. 89" : $out['pat_location'] ;
				$string = '<tr>
					<td style="width:10mm;border:1px solid black;text-align:center;">'.(sizeof($pricetable)).'</td>
					<td style="width:40mm;border:1px solid black;text-align:center;">'.$out['pat_fio'].'</td>
					<td style="width:50mm;border:1px solid black;text-align:left;">'.$s_location.'</td>
					<td style="width:50mm;border:1px solid black;text-align:left;">'.$row->serv_name.'</td>
					<td style="width:30mm;border:1px solid black;text-align:center;">'.$row->bas_num.'</td>
					<td style="width:30mm;border:1px solid black;text-align:center;">'.$price.'</td>
					<td style="width:30mm;border:1px solid black;text-align:center;">'.($price * $row->bas_num).'</td>
				</tr>';
				array_push($pricetable, $string);
			}
		}
		$out['tprice']   = ($totalprice % 100 == 0) ? ($totalprice / 100) : substr($totalprice, -2);
		$out['tprice_k'] = substr($price, -2, 2);
		//$out['tprice'] = ($totalprice % 100 == 0) ? ($totalprice / 100).",00" : ($totalprice / 100).",".$totalprice % 100;
		array_push($pricetable, '<tr>
			<td colspan="6" style="border:1px solid black;font-weight:bold;font-size:14pt;text-indent:10mm;">ИТОГО</td>
			<td style="width:30mm;border:1px solid black;text-align:center;font-weight:bold;font-size:14pt;">'.$out['tprice'].'</td>
		</tr>');
		$out['tprice_w'] = $this->docmodel->num2words( ($totalprice / 100) );
		$out['pricetable'] = '<table style="margin-bottom:0mm;margin-top:0mm;margin-left:-5mm;border-spacing:0mm;border-collapse:collapse">'.implode("\n", $pricetable).'</table>';

		//print $out;
		// contract self
		$output = "<html><body>".$this->load->view('docs/dvhp3', $out, true);
		// enclosure 1
		$output .= "<br clear=all style='mso-special-character:line-break;page-break-before:always'>".$this->load->view('docs/dvhp1', $out, true);
		// enclosure 2
		$output .= "<br clear=all style='mso-special-character:line-break;page-break-before:always'>".$this->load->view('docs/dvhp2', $out, true);
		$output .= "</body></html>";
		//print $output;
		$this->load->helper('download');
		force_download('Контракт №'.$out['cont_number']." ".$out['cli_fio'].".doc", $output);
	}

	public function contract_act_get($id){
		$result = $this->db->query("SELECT 
		CONCAT_WS(' ', patients.pat_f, patients.pat_i, patients.pat_o) AS pat_ffio,
		CONCAT_WS(' ', patients.pat_f, CONCAT(UCASE(LEFT(patients.pat_i, 1)),'.', UCASE(LEFT(patients.pat_o, 1)),'.')) AS pat_fio,
		IF(RIGHT(patients.pat_o, 2) = 'ич', 'm', 'f') AS pat_gender,
		CONCAT_WS(' ', clients.cli_f, clients.cli_i, clients.cli_o) AS cli_ffio,
		CONCAT_WS(' ', clients.cli_f, CONCAT(UCASE(LEFT(clients.cli_i, 1)), '.', UCASE(LEFT(clients.cli_o, 1)), '.')) AS cli_fio,
		IF(RIGHT(clients.cli_o, 2) = 'ич', 'm', 'f') AS cli_gender,
		clients.cli_hphone,
		clients.cli_cphone,
		clients.cli_mail,
		clients.cli_address,
		clients.cli_pass_s,
		clients.cli_pass_n,
		clients.cli_pass_issued,
		patients.pat_address,
		patients.pat_pass_s,
		patients.pat_pass_n,
		patients.pat_pass_issued,
		DATE_FORMAT(patients.pat_pass_issuedate, '%d.%m.%Y') AS pat_pass_issuedate,
		DATE_FORMAT(clients.cli_pass_issuedate, '%d.%m.%Y') AS cli_pass_issuedate,
		DATE_FORMAT(`contracts`.cont_date_start, '%d.%m.%Y') AS cont_date_start,
		DATE_FORMAT(`contracts`.cont_date_end, '%d.%m.%Y') AS cont_date_end,
		`contracts`.cont_number
		FROM
		patients
		LEFT OUTER JOIN clients ON (patients.pat_clientid = clients.id)
		INNER JOIN `courses` ON (patients.id = `courses`.pid)
		INNER JOIN `contracts` ON (`courses`.id = `contracts`.crsid)
		WHERE
		(`contracts`.`id` = ?)", array($id));
		if($result->num_rows()){
			$out = $result->row_array();
			$stm = explode("." ,$out['cont_date_start']);
			$etm = explode("." ,$out['cont_date_end']);
			$months = array( 1 => 'января', 2 => 'февраля', 3 => 'марта', 4 => 'апреля', 5 => 'мая', 6 => 'июня', 7 => 'июля', 8 => 'августа', 9 => 'сентября', 10 => 'октября', 11 => 'ноября', 12 => 'декабря' );
			$out['cont_date_start_w'] = implode(" ", array($stm[0], $months[(int) $stm[1]], $stm[2]));
			$out['cont_date_end_w']   = implode(" ", array($etm[0], $months[(int) $etm[1]], $etm[2]));
			$out['cli_gender1'] = ($out['cli_gender'] == 'm') ? 'ый' : 'ая' ;
			$out['cli_gender2'] = ($out['cli_gender'] == 'm') ? 'ий' : 'ая' ;
			$out['cli_gender3'] = ($out['cli_gender'] == 'm') ? '' : 'а' ;
			$out['pat_gender1'] = ($out['pat_gender'] == 'm') ? 'ый' : 'ая' ;
			$out['pat_gender2'] = ($out['pat_gender'] == 'm') ? 'ий' : 'ая' ;
			$out['pat_gender3'] = ($out['pat_gender'] == 'm') ? '' : 'а' ;
			$out['pat_gender4'] = ($out['pat_gender'] == 'm') ? 'ен' : 'на' ;
		}else{
			print "Не найден запрошенный контракт";
			exit;
		}

		$result = $this->db->query("SELECT 
		COUNT(services.serv_name) AS sc,
		services.serv_name,
		sum(service_calendar.pricefixed) AS price
		FROM
		service_calendar
		INNER JOIN services ON (service_calendar.service_id = services.id)
		WHERE
		(service_calendar.contract_id = ?)
		-- AND (service_calendar.is_done)
		GROUP BY
		services.serv_name", array($id));
		$table = array('<tr>
			<td style="width:10mm;border:1px solid black;text-align:center;font-weight:bold;">№ п/п</td>
			<td style="width:70mm;border:1px solid black;text-align:center;font-weight:bold;">Услуга</td>
			<td style="width:30mm;border:1px solid black;text-align:center;font-weight:bold;">Количество</td>
			<td style="width:30mm;border:1px solid black;text-align:center;font-weight:bold;">Стоимость</td>
		</tr>');
		$price = 0;
		if($result->num_rows()){
			foreach($result->result() as $row){
				$price += $row->price;
				///print $row->price;
				$string = '<tr>
					<td style="border:1px solid black;text-align:center;">'.(sizeof($table) + 1).'</td>
					<td style="border:1px solid black;text-align:left;">'.$row->serv_name.'</td>
					<td style="border:1px solid black;text-align:center;">'.$row->sc.'</td>
					<td style="border:1px solid black;text-align:center;">'.($row->price / 10000).'</td>
				</tr>';
				array_push($table, $string);

			}
		}
		$out['tprice'] = ($price % 10000 == 0) ? ($price / 10000) : substr($price, -4);
		$out['tprice_k'] = substr($price, -4, 2);
		$out['tprice_w'] = $this->docmodel->num2words($price);
		$out['table'] = '<table style="margin-bottom:0mm;margin-top:0mm;margin-left:-18mm;width:170mm;border-spacing:0mm;border-collapse:collapse">'.implode("\n", $table).'</table>';

		//print $out;
		// contract self
		$output = "<html><body>".$this->load->view('docs/asc2', $out, true);
		// enclosure 1
		$output .= "</body></html>";
		//print $output;
		$this->load->helper('download');
		force_download('Контракт №'.$out['cont_number']." ".$out['cli_fio'].".doc", $output);
	}

	public function editor_get($docname=0){
		$output = array();
		// formal substitutors
		$fs = array(
			'cont_date_start_w'  => '/--cont_date_start_w--/',
			'cont_date_end'      => '/--cont_date_end--/',
			'cont_number'        => '/--cont_number--/',
			'pricetable'         => '/--pricetable--/',
			'tprice'             => '/--tprice--/',
			'tprice_w'           => '/--tprice_w--/',
			'tprice_k'           => '/--tprice_k--/',
			'cli_ffio'           => '/--cli_ffio--/',
			'cli_fio'            => '/--cli_fio--/',
			'cli_gender1'        => '/--cli_gender1--/',
			'table'              => '/--table--/',
			'cli_gender2'        => '/--cli_gender2--/',
			'cli_gender3'        => '/--cli_gender3--/',
			'cli_pass_s'         => '/--cli_pass_s--/',
			'cli_pass_n'         => '/--cli_pass_n--/',
			'cli_pass_issued'    => '/--cli_pass_issued--/',
			'cli_pass_issuedate' => '/--cli_pass_issuedate--/',
			'cli_cphone'         => '/--cli_cphone--/',
			'cli_hphone'         => '/--cli_hphone--/',
			'cli_address'        => '/--cli_address--/',
			'pat_ffio'           => '/--pat_ffio--/',
			'pat_fio'            => '/--pat_fio--/',
			'pat_location'       => '/--pat_location--/',
			'pat_gender1'        => '/--pat_gender1--/',
			'pat_gender2'        => '/--pat_gender2--/',
			'pat_gender3'        => '/--pat_gender3--/',

			'pat_gender4'        => '/--pat_gender4--/',
			'pat_pass_s'         => '/--pat_pass_s--/',
			'pat_pass_n'         => '/--pat_pass_n--/',
			'pat_pass_issued'    => '/--pat_pass_issued--/',
			'pat_pass_issuedate' => '/--pat_pass_issuedate--/',
			'pat_address'        => '/--pat_address--/'
		);
		$output['menu'] = $this->load->view('menu', array(), true);
		$output['is_list'] = (!$docname) ? 1 : 0 ;
		$output['docs'] = $this->index_show();
		$output['text'] = ($docname) ? $this->load->view('docs/'.$docname, $fs, true) : "Необходимо выбрать документ" ;
		$output['docname'] = $docname;
		$this->load->view('docs/editor/editor', $output);
	}

	public function index_show(){
		$this->load->helper('file');
		$list = read_file('application/views/docs/list.txt');
		$array = explode("\n", $list);
		$string = "";
		foreach($array as $val){
			$str = explode("-", $val);
			$string .= '<li><img src="/images/page_word.png" style="width:16px;height:16px;border:none" alt="">&nbsp;&nbsp;&nbsp;<a href="/docs/editor/'.$str[0].'">'.$str[1].'</a></li>';
		}
		return $string;
	}

	public function doc_save(){
		$text = $this->input->post('doctext');
		//print $text;
		preg_match_all('/\/\-\-(.*)\-\-\//U', $text, $matches, PREG_SET_ORDER);
		foreach($matches as $val){
			$text = str_replace($val[0], '<?=$'.$val[1].';?>', $text);
		}
		$this->load->helper('file');
		$this->load->helper('url');
		write_file('application/views/docs/'.$this->input->post("docname"), $text);
		redirect("docs/editor");
	}
}

/* End of file docmodel.php */
/* Location: ./system/application/models/docmodel.php */