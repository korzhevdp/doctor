<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Toolmodel extends CI_Model{
	
	function __construct(){
		parent::__construct();
	}

	public function insert_audit($text){
		$this->db->query("INSERT INTO audit (
			audit.uid,
			audit.query
		) VALUES ( ?, ? )", array( $this->session->userdata('userid'), $text));
	}

	public function get_bc($src){
		/*
			<ul class="breadcrumb">
			<li><a href="#">Home</a> <span class="divider">/</span></li>
			<li><a href="#">Library</a> <span class="divider">/</span></li>
			<li class="active">Data</li>
			</ul>
		*/
		$output = array();
		$size = (sizeof($src) - 1);
		foreach($src as $key=>$val){
			$string = ($key < $size)
				? '<li><a href="'.$val[1].'" class="muted">'.$val[0].'</a> <span class="divider">/</span></li>'
				: '<li class="active"><a href="#">'.$val[0].'</a></li>' ;
			array_push($output, $string);
		}
		return '<ul class="breadcrumb">'.implode("\n", $output).'</ul>';
	}

	##########################################################################
	############################### Encryption Helpers
	##########################################################################
	public function encrypt_an_array($array = array(), $enc_key=""){
		$keyfile = file_get_contents("/var/www/html/doctordv/aes256/keyfile.aes256");
		$enc_key = (strlen($enc_key)) ? $enc_key : $this->config->item('encryption_key');
		//$array = array('who' => 'слон', 'what' => 'ест', 'what2' => 'куст');
		foreach ($array as $key => $val){
			$array[$key] = $this->encrypt->encode($array[$key], $enc_key);
		}
		return $array;
	}

	public function decrypt_an_array($array = array(), $enc_key=""){
		$enc_key = (strlen($enc_key)) ? $enc_key : $this->config->item('encryption_key');
		foreach ($array as $key => $val){
			$array[$key] = $this->encrypt->decode($array[$key], $enc_key);
		}
		return $array;
	}

	public function write_checkfile($master_password="12345678"){
		// Берём мастер пароль и пишем на его основе чек-файл. (тот ли это мастер-пароль)
		$string = "Everything is OK!";
		$enc_key = $master_password.$this->config->item('encryption_key');
		$file = "/var/www/html/doctordv/aes256/checkfile.aes256";
		$open = fopen($file, "wb");
		fputs($open, $this->encrypt->encode($string, $enc_key));
		fclose($open);
	}

	public function write_keyfile($master_password="12345678"){
		// Первичное шифрование мастер-пароля. 
		// Суровая, прилично рандомизированная строка шифруется мастер-паролем и открытой частью ключа.
		// Записывается в ключ-файл, расшифрованное содержимое которого потом будем использовать как ключ к боевому шифрованию данных
		$enc_key = $master_password.$this->config->item('encryption_key');
		$file = "/var/www/html/doctordv/aes256/keyfile.aes256";
		$open = fopen($file, "wb");
		fputs($open, $this->encrypt->encode($this->config->item('encryption_key2'), $enc_key));
		fclose($open);
		//файл записан
	}

	public function test_pass($password="12345678"){
		$file = file_get_contents("/var/www/html/doctordv/aes256/checkfile.aes256");
		$dec_key = $password.$this->config->item('encryption_key');
		if($this->encrypt->decode($file, $dec_key) === "Everything is OK!"){
			print "ssss";
			return true;
		}
		return false;
	}

	public function calc_decoder($password=0, $master_password = 0, $userid = 0){
		if(!$password || !$master_password || !$userid){
			print "Inconsistent data";
			return false;
		}
		// производим проверку: можно ли расшифровать предоставленным мастер-паролем
		// читаем файл:
		$checkfile = file_get_contents("/var/www/html/doctordv/aes256/checkfile.aes256");
		// собираем ключ:
		$dec_key = $master_password.$this->config->item('encryption_key');
		// тест на чек-файле
		if($this->encrypt->decode($checkfile, $dec_key) === "Everything is OK!"){
			// расшифровываем страшную строку суперключа и тут же пакуем её обратно с пользовательским ключом
			// читаем файл:
			$keyfile = file_get_contents("/var/www/html/doctordv/aes256/keyfile.aes256");
			// щёлкаем пальцами (перешифровываем из строки с мастер-паролем как соли, в строку с солью из пользовательского пароля)
			$userticket = $this->encrypt->encode($this->encrypt->decode($keyfile, $dec_key), $password.$this->config->item('encryption_key'));
			if($userid){
				$result = $this->db->query("UPDATE
				`users`
				SET
				`users`.`hash1` = ?
				WHERE
				`users`.`id` = ?", array($userticket, $userid));
				if($this->db->affected_rows()){
					return true;
				}
				print "Database was not updated";
				return false;
			}
			print "UID is 0";
			return false;
		}
		print "wrong MP";
		return false;
	}

	public function encode_pass_str($password, $master_password, $key){
		$password = 'password';
		$master_password = "123456";
		$cfile = "";
		foreach($array as $key => $val){
			$array[$key] = $this->encrypt->encode($array[$key], $enc_key);
		}
		print_r($array)."<br><br><br>";
		foreach ($array as $key => $val){
			$array[$key] = $this->encrypt->decode($array[$key], $enc_key);
		}
		print_r($array);
	}

}

/* End of file toolmodel.php */
/* Location: ./system/application/models/toolmodel.php */