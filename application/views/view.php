<!doctype html>
<html lang="en">
<head>
	<title>Административная консоль сайта</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<script type="text/javascript" src="<?=$this->config->item('api');?>/jscript/jquery.js"></script>
	<script type="text/javascript" src="<?=$this->config->item('api');?>/jqueryui/js/jqueryui.js"></script>
	<script type="text/javascript" src="<?=$this->config->item('api');?>/bootstrap/js/bootstrap.js"></script>
	<link href="<?=$this->config->item('api');?>/bootstrap/css/bootstrap.css" rel="stylesheet">
	<link href="<?=$this->config->item('api');?>/jqueryui/css/jqueryui.css" rel="stylesheet">
</head>

<body style="padding:0px;margin:0px;">
<table style="border:none;width:100%">
	<tr>
		<td colspan=2 class="navbar" style="vertical-align:top;">
			<div class="navbar-inner">
				<div class="container" style="width:400px;float:left;">
					<a class="brand" href="http://balt-reatech.ru"><img src="http://balt-reatech.ru/assets/images/brt_web.png" style="width:330x;height:66px;border:none;" alt=""></a>
				</div>
				<? if( $this->session->userdata('userid')) { ?>
				<div class="pull-right" title="Выйти из системы" style="float:right;margin-top:20px;">
					<a href="/login/logout" class="btn" style="margin-right:25px;">
						<h4>
							<img src="/images/cio.png" width="16" height="16" border="0" alt="">&nbsp;&nbsp;<?=$this->session->userdata('user_name');?> &times;
						</h4>
					</a>
				</div>
				<? } ?>
			</div>
		</td>
	</tr>
	<tr>
		<td class="well well-small" style="width:150px;vertical-align:top;">
			<ul class="nav nav-list" id="operation-menu" style="margin-left:0px;font-size:9pt;">
				<?=$menu;?>
			</ul>
			<!--Sidebar content-->
		</td>
		<td style="vertical-align:top;padding-left:30px;">
			<?=(isset($bc) ? $bc : "");?>
			<?=$content;?>
		</td>
	</tr>
</table>

<div id="announcer"></div>
<script type="text/javascript">
<!--
	$("#library").width($(window).width() - 240 + 'px').css("margin-left","0px");
	$("#calendarTable td").mouseenter(function(){
		$(this).css("border", "1px solid black");
		$(this).css("color", "ff3300");
	});

	$("#calendarTable td").mouseleave(function(){
		$(this).css("border", "1px solid gray");
		$(this).css("color", "000000");
	});
//-->
</script>
<!-- 
<SCRIPT TYPE="text/javascript">
	$(".info_table_innerdiv").css("width", "708px");
	$(".selector").datepicker($.datepicker.regional['ru']);
	$(".selector").datepicker( "option", "showWeek", true );
	$(".selector").datepicker( "option", "minDate", new Date());
	$(".selector").datepicker( "option", "gotoCurrent", true );

</SCRIPT> -->
</body>
</html>