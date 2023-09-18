<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dbpack extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->mkb10();
		//phpinfo();
		$this->output->enable_profiler(TRUE);
	}
/*
CREATE TABLE `mkb10` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`class` int(11) DEFAULT NULL,
		`lit` text,
		`section` char(6) DEFAULT NULL,
		`subsection` int(11) DEFAULT NULL,
		`subsection2` int(11) DEFAULT NULL,
		`sign` char(1) DEFAULT NULL,
		`content` text,
		`prefix` text,
		`excl` text,
		`incl` text,
		`note` text,
		PRIMARY KEY (`id`)
		) ENGINE=InnoDB AUTO_INCREMENT=11344 DEFAULT CHARSET=utf8
*/
	public function mkb10(){

		ini_set('output_buffering', 'Off');
		ob_start();
		set_time_limit (0);

		$array = file("backup/mkb10-e.txt");
		$i = 0;
		$this->db->query("DROP TABLE IF EXISTS `mkb10`");
		$this->db->query("CREATE TABLE `mkb10` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`class` int(11) DEFAULT NULL,
		`lit` text,
		`section` char(6) DEFAULT NULL,
		`subsection` int(11) DEFAULT NULL,
		`subsection2` int(11) DEFAULT NULL,
		`sign` char(1) DEFAULT NULL,
		`content` text,
		`string` text,
		`prefix` text,
		`excl` text,
		`incl` text,
		`note` text,
		PRIMARY KEY (`id`)
		) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8");
		foreach($array as $key=>$val){
			$output = array(0 => '', 1 => '', 2 => '', 3 => '');
			$df = explode("\t", $val);

			$output[0] = $df[0];
			foreach($df as $vd=>$vm){
				if(preg_match("/^~INC~(.+)/", $vm, $matches)){
					$output[1] = $matches[1];
				}
				if(preg_match("/^~EX~(.+)/",  $vm, $matches)){
					$output[2] = $matches[1];
				}
				if(preg_match("/^~PR~(.+)/",  $vm, $matches)){
					$output[3] = $matches[1];
				}
			}

			$this->db->query("INSERT INTO 
			`mkb10` (
				`mkb10`.`content`,
				`mkb10`.`incl`,
				`mkb10`.`excl`,
				`mkb10`.`note`,
				`mkb10`.`string`
			) VALUES ( ?, ?, ?, ?, ? )", array($output[0], $output[1], $output[2], $output[3], $val));
			print $i++."<br>";
			//--;
			flush();
			ob_flush();
			//if(!$i){
			//	break;
			//}
		}
		ob_end_flush();
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */