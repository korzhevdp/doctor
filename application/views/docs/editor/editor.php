<!doctype html>
<html lang="en">
<head>
	<title>Административная консоль сайта</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<script type="text/javascript" src="<?=$this->config->item('api');?>/jscript/jquery.js"></script>
	<script type="text/javascript" src="<?=$this->config->item('api');?>/jqueryui/js/jqueryui.js"></script>
	<script type="text/javascript" src="<?=$this->config->item('api');?>/bootstrap/js/bootstrap.js"></script>
	<script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
	<link href="<?=$this->config->item('api');?>/bootstrap/css/bootstrap.css" rel="stylesheet">
	<link href="<?=$this->config->item('api');?>/jqueryui/css/jqueryui.css" rel="stylesheet">

</head>

<body style="padding:0px;margin:0px;font-size:12px;">
<table style="border:none;width:100%">
	<tr>
		<td colspan=2 class="navbar" style="vertical-align:top;">
			<div class="navbar-inner">
				<div class="container">
					<a class="brand" href="http://balt-reatech.ru"><img src="http://balt-reatech.ru/assets/images/brt_web.png" style="width:330x;height:66px;border:none;" alt=""></a>
				</div>
			</div>
		</td>
	</tr>
	<tr>
		<td class="well well-small" style="width:150px;vertical-align:top;">
			<ul class="nav nav-list" id="operation-menu" style="margin-left:0px;">
				<?=$menu;?>
			</ul>
			<!--Sidebar content-->
		</td>
		<td style="vertical-align:top;padding-left:30px;padding-right:30px;">
			<div class="accordion" id="accordion">
				<div class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#c1">Список файлов</a>
					</div>
					<div id="c1" class="accordion-body collapse <?=($is_list) ? 'in' : '' ;?>">
						<div class="accordion-inner">
							<ul>
								<?=$docs;?>
							</ul>
						</div>
					</div>
				</div>
				<div class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#c2">Редактор</a>
					</div>
					<div id="c2" class="accordion-body collapse <?=($is_list) ? '' : 'in' ;?>">
						<div class="accordion-inner">
							<form method="post" action="/docs/docsave">
								<textarea name="doctext" id="doctext" rows="8" cols="30"><?=$text;?></textarea>
								<input type="hidden" name="docname" value="<?=$docname;?>">
								<center><button type="submit" class="btn btn-large" style="margin-top:20px;">Сохранить документ</button></center>
							</form>
						</div>
					</div>
				</div>
			</div>
		</td>
	</tr>
</table>
<script  type="text/javascript">
	// Replace the <textarea id="editor1"> with a CKEditor
	// instance, using default configuration.
	CKEDITOR.config.protectedSource.push( /\/\-\-*\-\-\//g );
	CKEDITOR.config.extraPlugins = 'font';
	CKEDITOR.config.fontSize_sizes = '10/10pt;11/11pt;12/12pt;13/13pt;14/14pt;16/16pt;18/18pt;';
	CKEDITOR.config.allowedContent = true;
	CKEDITOR.replace( 'doctext' );

</script>
</body>
</html>