<!doctype html>
<html>
	<head>
		<title>Управление данными</title>
		<meta http-equiv="content-type" content="text/html; charset=windows-1251">
		<link href="<?=$this->config->item("api")?>/bootstrap/css/bootstrap.css" rel="stylesheet">
		<link href="/css/frontstyle.css" rel="stylesheet" media="screen" type="text/css">
	</head>

	<body>
		<script src="/jscript/jquery.js" type="text/javascript"></script>

		<div class="navbar">
			<div class="navbar-inner">
				<div class="container">
					<div class="container" style="width:400px;float:left;">
						<a class="brand" href="http://balt-reatech.ru"><img src="http://balt-reatech.ru/assets/images/brt_web.png" style="width:330x;height:66px;border:none;" alt=""></a>
					</div>
					
					<? if( $this->session->userdata('userid')) { ?>
					<div class="pull-right" title="Выйти из системы" style="float:right;margin-top:20px;">
						<a href="/login/logout"><h4><?=$this->session->userdata('user_name');?></h4></a>
					</div>
					<? } ?>

				</div>
			</div>
		</div>

		<div class="tab-content span9" style="clear:both;">
			<div id="tabr1" class="tab-pane active">
				<h2 style="margin-bottom:24px;">Авторизуйтесь.&nbsp;&nbsp;&nbsp;&nbsp;<small>Мы ценим Ваше участие</small></h2>
				<form method=post action="/login">
					<label class="span2">Имя пользователя:</label>
					<input class="span6" type="text" name="name"><br>
					<label class="span2">Пароль:</label>
					<input class="span6" type="password" name="pass"><br>
					<button type="submit" class="btn btn-primary pull-right btn-large span4">Вход</button>
				</form>
			</div>
		</div>

		<div id="reg_errors">
			<?=(isset($errorlist)) ? $errorlist : "";?>
		</div>

		<div id="announcer"></div>
	</body>
</html>