<!doctype html>
<html lang="en">
<head>
	<title>Административная консоль сайта</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<script type="text/javascript" src="<?=base_url();?>jscript/jquery.js"></script>
	<script type="text/javascript" src="<?=base_url();?>bootstrap/js/bootstrap.js"></script>
	<link href="<?=base_url();?>bootstrap/css/bootstrap.css" rel="stylesheet">
	<link href="<?=base_url();?>css/system.css" rel="stylesheet">
</head>

<body style="padding:0px;margin:0px;">

	<table>
		<tr>
			<td colspan=2 class="navbar" style="vertical-align:top;">
				<div class="navbar-inner">
					<div class="container" style="width:400px;float:left;">
						<a class="brand" href="https://balt-reatech.ru"><img src="https://balt-reatech.ru/assets/images/brt_web.png" alt=""></a>
					</div>
					<?php if ( isset($_SESSION['userid']) ) { ?>
					<div class="pull-right" title="Выйти из системы" style="margin-top:20px;">
						<a href="<?=base_url();?>login/logout" class="btn">
							<h4>
								<img src="<?=base_url();?>images/cio.png" width="16" height="16" border="0" alt="">&nbsp;&nbsp;<?=$_SESSION['user_name'];?> &times;
							</h4>
						</a>
					</div>
					<?php } ?>
				</div>
			</td>
		</tr>
		<tr>
			<td class="well well-small" style="width:150px;vertical-align:top;">
				<ul class="nav nav-list" id="operation-menu" style="margin-left:0px;font-size:9pt;">
					<?=$menu;?>
				</ul>
			</td>
			<td style="vertical-align:top;padding-left:30px;">
				<?=( isset($bc) ) ? $bc : "";?>
				<?=$content;?>
			</td>
		</tr>
	</table>

<div id="announcer"></div>

<script type="text/javascript">
	$("#library").width($(window).width() - 240 + 'px').css("margin-left","0px");
	$("#calendarTable td").mouseenter(function() {
		$(this).css("border", "1px solid black");
		$(this).css("color", "ff3300");
	});

	$("#calendarTable td").mouseleave(function() {
		$(this).css("border", "1px solid gray");
		$(this).css("color", "000000");
	});
</script>
</body>
</html>