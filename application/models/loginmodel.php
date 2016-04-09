<?php
class Loginmodel extends CI_Model{
	
	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		//$this->output->enable_profiler(TRUE);
	}

	public function _test_user(){
		$errors = array();
		$act = array();
		$result = $this->db->query("SELECT 
		users.password,
		users.id,
		users.admin,
		users.rank,
		users.hash1,
		`staff`.active
		FROM
		users
		INNER JOIN `staff` ON (users.id = `staff`.id)
		WHERE
		(users.login = ?)
		LIMIT 1", array($this->input->post('name', TRUE)));

		if($result->num_rows()){
			$row = $result->row();
			if(md5('secret'.$this->input->post('pass')) == $row->password){ // если пароль верен
				if(!$row->active){//а может быть пользователя мы отключили?
					array_push($errors,"Пользователь с указанными именем и паролем неактивен. Напишите письмо в администрацию сайта по адресу: <u><a href=\"mailto:korzhevdp@gmail.com\">korzhevdp@gmail.com</a></u> если Вы желаете активировать его.");
				}
				if(!sizeof($errors)){
					$this->session->set_userdata('user_name', $this->input->post('name'));
					$this->session->set_userdata('userid'   , $row->id);
					$this->session->set_userdata('rank'     , $row->rank);
					$this->session->set_userdata('admin'    , $row->admin);
					$this->session->set_userdata('pass'     , $this->input->post('pass'));
					$this->session->set_userdata('ekey'     , $row->hash1);
					$this->toolmodel->insert_audit("Зарегистрирован вход пользователя #".$this->input->post('name'));
					redirect('start');
				}
			}else{
				array_push($errors,'<div class="alert alert-error span5" style="clear:both;margin:40px;"><a class="close" data-dismiss="alert" href="#">x</a>
				<h4 class="alert-heading">Ошибка!</h4>
				Пользователь с указанными именем и паролем не найден. Проверьте правильность ввода имени пользователя и пароля. Обратите внимание, что прописные и строчные буквы различаются
				</div>');
			}
		}else{
			array_push($errors,'<div class="alert alert-error span5" style="clear:both;margin:40px;"><a class="close" data-dismiss="alert" href="#">x</a>
				<h4 class="alert-heading">Ошибка!</h4>
				Пользователь с указанными именем и паролем не найден. Проверьте правильность ввода имени пользователя и пароля. Обратите внимание, что прописные и строчные буквы различаются
				</div>');
		}
		$act['errorlist']=implode($errors,"<br>\n");
		$this->load->view('login/login_view',$act);
	}

	public function logout(){
		$this->session->sess_destroy();
		$this->toolmodel->insert_audit("Пользователь #".$this->input->post('name')." вышел из системы.");
		redirect('start');
	}

}
#
/* End of file loginmodel.php */
/* Location: ./system/application/models/loginmodel.php */