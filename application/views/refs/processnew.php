<style type="text/css">
	.hpointer{
		color: #aeaeae;
		cursor: pointer;
	}
	.hpointer.active{
		color: #000000;
	}
	.processed{
		color: #ff3300;
	}
	.mkbref{
		font-weight: bold;
		color: blue;
		cursor: pointer;
	}
	.diagDeleter{
		margin-left:10px;
		cursor: pointer;
	}
</style>
<h5>
<span id="hp1" class="hpointer active toCli">Клиент</span>&nbsp;&nbsp;&nbsp;>&nbsp;&nbsp;&nbsp;
<span id="hp2" class="hpointer toPat">Пациент</span>&nbsp;&nbsp;&nbsp;>&nbsp;&nbsp;&nbsp;
<span id="hp3" class="hpointer toDoc">Документы</span>
</h5><hr>

<div class="panels" id="clientEditor">
		<h4><span id="editorHeader">Добавление клиента&nbsp;&nbsp;&nbsp;&nbsp;<small>шаг 1.</small></span></h4>
		<u><span id="headerfio" class=""><span></u>
	<div class="modal-body" style="">
		<form method="post" id="clientDataForm" action="#" enctype="multipart/form-data" class="form-inline row-fluid">
			<table>
			<tr>
				<td style="width:160px;">Фамилия</td>
				<td><input type="text" name="client_f" id="client_f" tabindex=1></td>
				<td style="width:160px;padding-left:15px;">Паспорт, серия</td>
				<td><input type="text" name="pass_s" id="pass_s" tabindex=7></td>
			</tr>
			<tr>
				<td>Имя</td>
				<td><input type="text" name="client_i" id="client_i" tabindex=2></td>
				<td style="padding-left:15px;">Паспорт, номер</td>
				<td><input type="text" name="pass_n" id="pass_n" tabindex=8></td>
			</tr>
			<tr>
				<td>Отчество</td>
				<td><input type="text" name="client_o" id="client_o" tabindex=3></td>
				<td style="padding-left:15px;">Паспорт выдан</td>
				<td><input type="text" name="pass_issued" id="pass_issued" tabindex=9></td>
			</tr>
			<tr>
				<td>Адрес</td>
				<td><input type="text" name="address" id="address" tabindex=4></td>
				<td style="padding-left:15px;">Паспорт, дата выдачи</td>
				<td><input type="date" name="pass_issuedate" id="pass_issuedate" tab="1" tabindex=10></td>
			</tr>
			<tr>
				<td>Телефон, моб.</td>
				<td><input type="tel" name="cphone" id="cphone" tabindex=5></td>
				<td style="padding-left:15px;">Телефон, дом.</td>
				<td><input type="tel" name="hphone" id="hphone" tabindex=11></td>
			</tr>
			<tr>
				<td>E-mail</td>
				<td><input type="text" name="email" id="email" tabindex=6></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td>Заметки</td>
				<td colspan=3>
					<textarea name="note" id="note" rows="4" cols="7" style="width:98%;" tabindex=12></textarea>
				</td>
			</tr>
			</table>
		</form>
	</div>
	<div class="modal-footer">
			<a href="#" class="btn pull-left toPat" id="toPat" style="margin-top:10px;">Пропустить, клиент уже был внесён ранее</a>
			<a href="#" class="btn btn-primary" id="clientDataSave" style="margin-top:10px;">Сохранить и продолжить</a>

	</div>
</div>

<div class="hide panels" id="patEditor">
		<h4><span id="editorHeader">Добавление пациента&nbsp;&nbsp;&nbsp;&nbsp;<small>шаг 2.</small></span></h4>
		<u><span id="headerfio" class=""><span></u>
	<div class="modal-body" style="">
		<form method="post" id="patDataForm" action="<?=base_url();?>refs/pat_save" enctype="multipart/form-data" class="form-inline row-fluid">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tab1" id="htab1" data-toggle="tab">Пациент</a></li>
				<li><a href="#tab2" id="htab2" data-toggle="tab">Информация для врача</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="tab1">
					<table>
					<tr>
						<td style="width:160px;">Фамилия</td>
						<td><input type="text" name="pat_f" id="pat_f" tabindex=1></td>
						<td style="width:160px;padding-left:15px;"><a href="#" id="dirlink" title="Переход по ссылке на страницу поставщиков">Поставщик</a></td>
						<td><select name="directed" id="directed">
							<option value="0"> -- Не указан -- </option>
						</select></td>
					</tr>
					<tr>
						<td>Имя</td>
						<td><input type="text" name="pat_i" id="pat_i" tabindex=2></td>
						<td style="padding-left:15px;"><a href="#" id="clientlink" title="Переход по ссылке на страницу клиентов">Клиент</a></td>
						<td><select name="clientid" id="clientid">
							<option value="0"> -- Не указан -- </option>
						</select></td>
					</tr>
					<tr>
						<td>Отчество</td>
						<td><input type="text" name="pat_o" id="pat_o" tabindex=3></td>
						<td title="Электронная почта пациента" style="padding-left:15px;">E-mail</td>
						<td><input type="text" name="email" id="pat_email" tabindex=7></td>
					</tr>
					<tr>
						<td>Телефон, моб.</td>
						<td ><input type="text" name="cphone" id="pat_cphone" tabindex=6 title="Мобильный телефон"></td>
						<td style="padding-left:15px;">Телефон, дом.</td>
						<td><input type="text" name="hphone" id="pat_hphone" tabindex=11 title="Домашний телефон"></td>
					</tr>
					<tr>
						<td>Адрес</td>
						<td><input type="text" name="address" id="pat_address" tabindex=5></td>
						<td style="padding-left:15px;">Расположение</td>
						<td><input type="text" name="location" id="pat_location" tabindex=19></td>
					</tr>
					</table>
					<hr>
					<table>
					<tr>
						<td style="width:160px;">Паспорт, серия</td>
						<td><input type="text" name="pass_s" id="pat_pass_s" tabindex=7></td>
						<td style="width:160px;padding-left:15px;">Паспорт, номер</td>
						<td><input type="text" name="pass_n" id="pat_pass_n" tabindex=8></td>
					</tr>
					<tr>
						<td>Паспорт выдан</td>
						<td><input type="text" name="pass_issued" id="pat_pass_issued" tabindex=9></td>
						<td style="padding-left:15px;">Паспорт, дата выдачи</td>
						<td><input type="date" name="pass_issuedate" id="pat_pass_issuedate" tab="1" tabindex=10></td>
					</tr>
					</table>
				</div>
				<div class="tab-pane" id="tab2">
					<table style="width:780px;">
					<tr>
						<td style="width:160px;">Дата рождения</td>
						<td>
							<input type="date" name="birth" id="birth" tab="2" tabindex=4>
						</td>
					</tr>
					<tr>
						<td style="width:160px;">Диагноз</td>
						<td>
							<ul id="diagContent">
							</ul>
						</td>
					</tr>
					<tr>
						<td></td>
						<td>
							<button type="button" id="addToDiag" class="btn btn-primary btn-block">Добавить диагноз</button>
						</td>
					</tr>
					<tr>
						<td>Жалобы</td>
						<td>
							<textarea name="complaints" id="pat_complaints" rows="4" cols="7" style="width:98%;" tabindex=13></textarea>
						</td>
					</tr>
					<tr>
						<td>Заметки</td>
						<td>
							<textarea name="info" id="info" rows="4" cols="7" style="width:98%;" tabindex=14></textarea>
						</td>
					</tr>
					</table>
				</div>
			</div>
		</form>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn btn-block" id="copyFromCli" title="клиент и пациент - одно лицо" style="margin-top:10px;">Копировать от клиента</a>
		<a href="#" class="btn btn-info pull-left toCli" id="toCli" style="margin-top:10px;">Назад</a>
		<a href="#" class="btn btn-info pull-left toDoc" id="toDoc" style="margin-top:10px;">К документам</a>
		<a href="#" class="btn btn-primary pull-right" id="patDataSave" style="margin-top:10px;">Сохранить</a>
	</div>
</div>

<div class="hide panels" id="docEditor" style="width:640px;">
	<h4><span id="editorHeader">Документы&nbsp;&nbsp;&nbsp;&nbsp;<small>шаг 3.</small></span></h4>
	<div class="modal-body" style="height:420px;overflow:hidden;">
		<div class="alert alert-error hide" id="docAlert">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<h4>Ошибка</h4>
			<span id="docAlertText"></span><br>
			<strong>Создание контракта невозможно</strong>
		</div>
		<h5>
			<img src="<?=base_url();?>images/cio.png" style="width:16px;height:16px;border:none;margin-right:10px;" alt="" >
			<a href="#" id="cHref" target="_blank">Клиент:</a>
			<img src="<?=base_url();?>images/question-button.png" style="width:16px;height:16px;border:none;margin-left:24px;" id="cOKMark" alt="" >
		</h5>
		<h5>
			<img src="<?=base_url();?>images/user_orange.png" style="width:16px;height:16px;border:none;margin-right:10px;" alt="" >
			<a href="#" id="pHref" target="_blank">Пациент: </a>
			<img src="<?=base_url();?>images/question-button.png" style="width:16px;height:16px;border:none;margin-left:15px;" id="pOKMark" alt="" >
		</h5>
		<hr>

		<h5><img src="<?=base_url();?>images/page_word.png" width="16" height="16" border="0" alt="">&nbsp;&nbsp;<a href="#" id="contLink" target="_blank">Перейти к созданию контракта</a></h5>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn btn-info pull-left toPat" style="margin-top:10px;">Назад</a>
		<a href="#" class="btn btn-info pull-left" id="downloadC" style="margin-top:10px;">Загрузить контракт</a>

	</div>
</div>

<input type="hidden" id="patId" value="0">
<input type="hidden" id="cliId" value="0">


<div class="modal hide" id="diagData" style="width:640px;">
	<div class="modal-header" style="cursor:move;background-color: #d6d6d6">
		<button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
		Диагноз
	</div>
	<div class="modal-body" id="patDataText" style="height:360px;">
		<table>
			<tr>
				<td style="width:100px;">
					<input type="text" style="width:70px;" id="mkb10id" placeholder="Код" title="Введите код МКБ-10. Например G23">
				</td>
				<td style="width:100%">
					<input type="text" style="width:98%" id="mkb10name" placeholder="Поиск диагноза" title="Введите диагноз по МКБ-10">
				</td>
			</tr>
			<tr>
				<td colspan=2>
					<select id="mkb10" style="width:100%;margin-top:3px;" size=5></select>
					<div id="mkb10ann" style="width:98%;height:175px;margin-top:3px;padding:3px;overflow:auto">
						<span class="muted" style="font-size:10px;">Аннотация к разделу МКБ10</span>
					</div>

				</td>
			</tr>
		</table>
		<button type="button" id="addToDiagList" class="btn btn-primary btn-block">Добавить диагноз в список</button>
	</div>
</div>



<script type="text/javascript">
<!--
	dirlist = "";
	cl_list = "";
	$("#clientDataSave").click(function(){
		var good = 1;
		$(".withCal").css('color', 'black');
		$("#clientDataForm input.withCal").each(function(){
			if ( /\d\d\.\d\d.\d\d\d\d/.test($(this).val()) ) {
				//alert("вроде порядок");
			}else{
				good = 0;
				//alert("Неверный формат даты");
				$(this).val("Неверный формат даты").css('color', 'red');
				$(this).focus(function(){
					$(this).css('color', 'black');
				})
			}
		});
		if(!good){
			//alert(22)
			return false;
		}else{
			if($("#cliId").val() == "0"){
				$.ajax({
					url: "<?=base_url();?>refs/client_item_add",
					type: "POST",
					data: {
						client_f:       $("#client_f").val(),
						pass_s:         $("#pass_s").val(),
						client_i:       $("#client_i").val(),
						pass_n:         $("#pass_n").val(),
						client_o:       $("#client_o").val(),
						pass_issued:    $("#pass_issued").val(),
						address:        $("#address").val(),
						pass_issuedate: $("#pass_issuedate").val(),
						cphone:         $("#cphone").val(),
						hphone:         $("#hphone").val(),
						email:          $("#email").val(),
						note:           $("#note").val(),
						ajax:           'applied'
					},
					dataType: 'text',
					success: function(data){
						build_lists(data, 0);
						$("#cliId").val(data);
						$(".hpointer").removeClass("active");
						$("#hp2").addClass("active");
						$("#hp1").addClass("processed");
						$("#clientEditor").addClass("hide");
						$("#patEditor").removeClass("hide");
					},
					error: function(data,stat,err){
						alert([data,stat,err].join("\n"));
					}
				});
			}else{
				$.ajax({
					url: "<?=base_url();?>refs/client_save",
					type: "POST",
					data: {
						client_f:       $("#client_f").val(),
						pass_s:         $("#pass_s").val(),
						client_i:       $("#client_i").val(),
						pass_n:         $("#pass_n").val(),
						client_o:       $("#client_o").val(),
						pass_issued:    $("#pass_issued").val(),
						address:        $("#address").val(),
						pass_issuedate: $("#pass_issuedate").val(),
						cphone:         $("#cphone").val(),
						hphone:         $("#hphone").val(),
						email:          $("#email").val(),
						note:           $("#note").val(),
						clientid:       $("#cliId").val(),
						ajax:           'applied'
					},
					dataType: 'text',
					success: function(data){
						build_lists($("#cliId").val(), 0);
						$(".hpointer").removeClass("active");
						$("#hp2").addClass("active");
						$("#hp1").addClass("processed");
						$("#clientEditor").addClass("hide");
						$("#patEditor").removeClass("hide");
					
					},
					error: function(data,stat,err){
						alert([data,stat,err].join("\n"));
					}
				});
			}
		}
	});

	$("#patDataSave").click(function(){
		var good = 1;
		$(".withCal").css('color', 'black');
		$("#clientDataForm input.withCal").each(function(){
			if ( /\d\d\.\d\d.\d\d\d\d/.test($(this).val()) ) {
				//alert("вроде порядок");
			}else{
				good = 0;
				//alert("Неверный формат даты");
				$(this).val("Неверный формат даты").css('color', 'red');
				$(this).focus(function(){
					$(this).css('color', 'black');
				})
			}
		});
		if(!good){
			//alert(22)
			return false;
		}else{
			if($("#patId").val() == "0"){
				$.ajax({
					url: "<?=base_url();?>refs/pat_item_add",
					type: "POST",
					data: {
						pat_f:          $("#pat_f").val(),
						pass_s:         $("#pat_pass_s").val(),
						pat_i:          $("#pat_i").val(),
						pass_n:         $("#pat_pass_n").val(),
						pat_o:          $("#pat_o").val(),
						pass_issued:    $("#pat_pass_issued").val(),
						address:        $("#pat_address").val(),
						location:       $("#pat_location").val(),
						complaints:     $("#pat_complaints").val(),
						pass_issuedate: $("#pat_pass_issuedate").val(),
						cphone:         $("#pat_cphone").val(),
						hphone:         $("#pat_hphone").val(),
						email:          $("#pat_email").val(),
						info:           $("#info").val(),
						diagnosis:      $("#diagnosis").val(),
						clientid:       $("#clientid").val(),
						directed:       $("#directed").val(),
						ajax:           'applied'
					},
					dataType: 'text',
					success: function(data){
						data = data.split(",");
						build_lists(data[3], 0);
						$("#patId").val(data[3]);
						(parseInt(data[1]) == 1) 
							? $("#cOKMark").attr("src", "<?=base_url();?>images/tick.png") 
							: $("#cOKMark").attr("src", "<?=base_url();?>images/question-button.png");
						(parseInt(data[2]) == 1) 
							? $("#pOKMark").attr("src", "<?=base_url();?>images/tick.png") 
							: $("#pOKMark").attr("src", "<?=base_url();?>images/question-button.png");
						(parseInt(data[3]) != 0) 
							? $("#pHref").attr("href", "<?=base_url();?>refs/patients.html#p" + data[3]) 
							: $("#pHref").attr("href", "#");
						(parseInt($("#cliId").val()) != 0) 
							? $("#cHref").attr("href", "<?=base_url();?>refs/clients.html#c" + $("#cliId").val()) 
							: $("#cHref").attr("href", "#");
						if(parseInt(data[1]) == 1 && parseInt(data[2]) == 1 && parseInt($("#cliId").val()) != 0 && parseInt(data[3]) != 0){
							//alert("/contracts/" + $("#cliId").val() + "/" + data[3]);
							$("#contLink").attr("href", "<?=base_url();?>contracts/contracts_show/" + $("#cliId").val() + "/" + data[3]).text("Перейти к созданию контракта");
						}else{
							$("#contLink").attr("href", "#").text("Недостаточно данных для офрормления контракта");
						}
						
						$(".hpointer").removeClass("active");
						$("#hp3").addClass("active");
						$("#hp2").addClass("processed");
						$(".panels").addClass("hide");
						$("#docEditor").removeClass("hide");
					},
					error: function(data,stat,err){
						alert([data,stat,err].join("\n"));
					}
				});
			}else{
				$.ajax({
					url: "<?=base_url();?>refs/pat_save",
					type: "POST",
					data: {
						pat_f:          $("#pat_f").val(),
						pass_s:         $("#pat_pass_s").val(),
						pat_i:          $("#pat_i").val(),
						pass_n:         $("#pat_pass_n").val(),
						pat_o:          $("#pat_o").val(),
						pass_issued:    $("#pat_pass_issued").val(),
						address:        $("#pat_address").val(),
						location:       $("#pat_location").val(),
						complaints:     $("#pat_complaints").val(),
						pass_issuedate: $("#pat_pass_issuedate").val(),
						cphone:         $("#pat_cphone").val(),
						hphone:         $("#pat_hphone").val(),
						email:          $("#pat_email").val(),
						info:           $("#info").val(),
						diagnosis:      $("#diagnosis").val(),
						ajax:           'applied',
						clientid:       $("#clientid").val(),
						directed:       $("#directed").val(),
						patid:          $("#patId").val()
					},
					dataType: 'text',
					success: function(data){
						build_lists($("#cliId").val(), 0);
						data = data.split(",");
						(parseInt(data[1]) == 1) 
							? $("#cOKMark").attr("src", "<?=base_url();?>images/tick.png") 
							: $("#cOKMark").attr("src", "<?=base_url();?>images/question-button.png");
						(parseInt(data[2]) == 1) 
							? $("#pOKMark").attr("src", "<?=base_url();?>images/tick.png") 
							: $("#pOKMark").attr("src", "<?=base_url();?>images/question-button.png");
						(parseInt($("#cliId").val()) != 0) 
							? $("#cHref").attr("href", "<?=base_url();?>refs/clients.html#" + $("#cliId").val()) 
							: $("#cHref").attr("href", "#");
						(parseInt($("#patId").val()) != 0) 
							? $("#pHref").attr("href", "<?=base_url();?>refs/patients.html#" + $("#patId").val()) 
							: $("#pHref").attr("href", "#");

						if (
								parseInt(data[1]) == 1 
								&& parseInt(data[2]) == 1 
								&& parseInt($("#cliId").val()) != 0 
								&& parseInt(data[3]) != 0
							) {
							//alert("/contracts/" + $("#cliId").val() + "/" + data[3]);
							$("#contLink").attr("href", "<?=base_url();?>contracts/contracts_show/" + $("#cliId").val() + "/" + data[3]).text("Перейти к созданию контракта");
						}else{
							$("#contLink").attr("href", "#").text("Недостаточно данных для офрормления контракта");
						}

						$(".hpointer").removeClass("active");
						$("#hp3").addClass("active");
						$("#hp2").addClass("processed");
						$(".panels").addClass("hide");
						$("#docEditor").removeClass("hide");
					},
					error: function(data,stat,err){
						alert([data,stat,err].join("\n"));
					}
				});
			}
		}
	});

	$(".toPat").click(function(){
		$(".hpointer").removeClass("active");
		$("#hp2").addClass("active");
		$(".panels").addClass("hide");
		$("#patEditor").removeClass("hide");
	});

	$(".toDoc").click(function(){
		$(".hpointer").removeClass("active");
		$("#hp3").addClass("active");
		$(".panels").addClass("hide");
		$("#docEditor").removeClass("hide");
		if($("#patId").val() == "0" || $("#cliId").val() == "0"){
			alertText = [];
			if($("#patId").val() == "0"){
				alertText.push('Не были должным образом сохранёны данные пациента. Вернитесь на закладку "Пациент" и нажмите кнопку "Сохранить" ')
			}
			if($("#cliId").val() == "0"){
				alertText.push('Не были должным образом сохранёны данные клиента. Вернитесь на закладку "Клиент" и нажмите кнопку "Сохранить" ')
			}
			$("#docAlertText").html("<p>" + alertText.join("<p></p>") + "</p>");
			$("#docAlert").removeClass("hide");
		}else{
			$("#docAlert").addClass("hide");
		}
	});

	$("#hp1, #toCli").click(function(){
		$(".hpointer").removeClass("active");
		$("#hp1").addClass("active");
		$(".panels").addClass("hide");
		$("#clientEditor").removeClass("hide");
	});

	$("#copyFromCli").click(function(){
		$("#pat_f"              ).val($("#client_f").val());
		$("#pat_pass_s"         ).val($("#pass_s").val());
		$("#pat_i"              ).val($("#client_i").val());
		$("#pat_pass_n"         ).val($("#pass_n").val());
		$("#pat_o"              ).val($("#client_o").val());
		$("#pat_pass_issued"    ).val($("#pass_issued").val());
		$("#pat_address"        ).val($("#address").val());
		$("#pat_pass_issuedate" ).val( $("#pass_issuedate").val());
		$("#pat_cphone"         ).val($("#cphone").val());
		$("#pat_hphone"         ).val($("#hphone").val());
		$("#pat_email"          ).val($("#email").val());
	});
	
	function build_lists(a, b){
		$.ajax({
			url: "<?=base_url();?>refs/pat_lists_get",
			type: "POST",
			data: { 
				cli: a,
				sup: b
			},
			dataType: 'script',
			success: function(){
				$("#directed").empty().append(dirlist);
				$("#clientid").empty().append(cl_list);
				$("#clientid").unbind().change(function(){
					var clientid = $(this).val();
					$.ajax({
						url: "<?=base_url();?>refs/client_item_get",
						type: "POST",
						data: { clientid: clientid },
						dataType: 'script',
						success: function(){
							for (a in clientdata){
								$("#" + a).val(clientdata[a]);
							}
							$("#copyFromCli").addClass("btn-success");
							$("#cliId").val(clientid);
							(parseInt($("#cliId").val()) != 0) ? $("#cOKMark").attr("src", "images/tick.png") : $("#cOKMark").attr("src", "images/question-button.png")
						},
						error: function(data,stat,err){
							alert([data,stat,err].join("\n"));
						}
					});
				});
			},
			error: function(data,stat,err){
				alert([data,stat,err].join("\n"));
			}
		});
	}

	build_lists(0, 0);

	$.datepicker.regional['ru'] = {
		closeText: 'Закрыть',
		prevText: '&#x3c;Пред',
		nextText: 'След&#x3e;',
		currentText: 'Сегодня',
		monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
		monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн','Июл','Авг','Сен','Окт','Ноя','Дек'],
		dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
		dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
		dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
		dateFormat: 'dd.mm.yy',
		firstDay: 1,
		isRTL: false
	};
	$(".withCal").datepicker($.datepicker.regional['ru']);
	//changeMonth and changeYear
	//$(".withCal").datepicker( "option", "showWeek", true );
	$(".withCal").datepicker( "option", "changeYear", true);

	if (window.location.hash) {
		$(".clientEdit[ref=" + window.location.hash.substr(2) + "]").click();
	}
	

	$("#mkb10id").keyup(function(){
		$(this).clearQueue().delay(1000).queue(function(){
			var m = $("#mkb10id").val();
			if(!m.length){
				return false;
			}
			$.ajax({
				url: "<?=base_url();?>mkb10/getbyid",
				type: "POST",
				data: { 
					id: m
				},
				dataType: 'script',
				success: function(){
					out = [];
					for ( a in mkbdata ){
						out.push('<option value="' + a + '" title="' + mkbdata[a]['t'] + '">' + mkbdata[a]['t'] + '</option>');
					}
					$("#mkb10").empty().append(out.join("\n"));
					// поведение по щелчку на пункте МКБ
					$("#mkb10").unbind().live("click change", function(event){
						var c = $(this).val(),
							n = [];
						(mkbdata[c]['e'].length > 0) ? n.push(mkbdata[c]['e']) : "";
						(mkbdata[c]['i'].length > 0) ? n.push(mkbdata[c]['i']) : "";
						(mkbdata[c]['n'].length > 0) ? n.push(mkbdata[c]['n']) : "";
						$("#mkb10ann").empty().html(n.join("<br>"));
						// поведение по щелчку на референсной ссылке
						$(".mkbref").unbind().click(function(){
							var idx = $(this).html().slice(1, -1).split("-")[0].replace(".", "");
							$("#mkb10id").val(idx).keyup();
						});
					});
				},
				error: function(data,stat,err){
					alert([data,stat,err].join("\n"));
				}
			});
		})
	});
	diags = {};

	$("#addToDiagList").click(function(){
		var ref = $("#mkb10 option:selected").val(),
			tx  = $("#mkb10 option:selected").text();
			//alert(ref + ' *** ' + diags.toSource());
		if(typeof diags[ref] == 'undefined' && tx.length){
			diags[ref] = 1;
			$("#diagContent").append("<li>" + tx + '<span class="diagDeleter" ref="' + ref + '"><img src="/images/cross.png" width="16" height="16" border="0" alt=""></span></li>');
			$(".diagDeleter").unbind().click(function(){
				rf = $(this).attr("ref");
				delete diags[rf];
				$(this).parent().remove();
			});
		}
	});

	$("#addToDiag").click(function(){
		$("#diagData").modal("show");
	});
//-->
</script>