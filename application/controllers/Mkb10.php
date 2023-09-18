<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mkb10 extends CI_Controller {

	function __construct(){
		parent::__construct();
	}

	public function index(){
		print "Справочник МКБ-10";
	}

	public function getbyid(){
		//$this->output->enable_profiler(TRUE);
		$id = strtoupper(preg_replace("/[^A-Za-z0-9]/", '', $this->input->post("id")));
		$output = array();
		$result = $this->db->query("SELECT 
		`mkb10`.`lit`,
		`mkb10`.`section`,
		`mkb10`.`subsection`,
		`mkb10`.`subsection2`,
		`mkb10`.`sign`,
		`mkb10`.content,
		mkb10.id,
		mkb10.excl,
		mkb10.incl,
		mkb10.note
		FROM
		mkb10
		WHERE `mkb10`.string LIKE ?", array($id."%"));
		if($result->num_rows()){
			foreach($result->result() as $row){
				$excl = trim(str_replace("\n", "", str_replace("\r", "", $row->excl)));
				$incl = trim(str_replace("\n", "", str_replace("\r", "", $row->incl)));
				$note = trim(str_replace("\n", "", str_replace("\r", "", $row->note)));
				$note = preg_replace("/\([A-Z]\d\d(.*)\)/U", '<span class="mkbref">\\0<\/span>', $note);
				$incl = preg_replace("/\([A-Z]\d\d(.*)\)/U", '<span class="mkbref">\\0<\/span>', $incl);
				$excl = preg_replace("/\([A-Z]\d\d(.*)\)/U", '<span class="mkbref">\\0<\/span>', $excl);

				$cl = array($row->section);
				(strlen($row->subsection)) ? array_push($cl, $row->subsection) : "";
				(strlen($row->subsection2)) ? array_push($cl, $row->subsection2) : "";
				$content = $row->lit.' '.implode(".", $cl).$row->sign." ".$row->content;
				//$content = trim(str_replace("\n", "", str_replace("\r", "", $content)));
				$string = $row->id.": {e: '".$excl."', i: '".$incl."', n: '".$note."', t: '".$content."'}";
				array_push($output, $string);
			}
		}
		print "mkbdata = {\n".implode(",\n", $output)."\n}";
	}

	public function getbytext(){
		$id = $this->input->post("id");
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */