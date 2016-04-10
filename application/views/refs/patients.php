<h3>
	Пациенты&nbsp;&nbsp;&nbsp;<small>Балтийские Реабилитационные Технологии</small>
	<!-- <button class="btn btn-warning btn-small pull-right" style="margin-right:10px;" id="patAddNew">+ Добавить</button> -->
</h3>

<ul class="nav nav-tabs">
	<li class="active"><a href="#tab1" id="htab1" data-toggle="tab">Проходящие курс</a></li>
	<li><a href="#tab2" id="htab2" data-toggle="tab">Проходившие курс</a></li>
</ul>
<div class="tab-content">
	<div class="tab-pane active" id="tab1">
		<table class="table table-condensed table-bordered table-striped table-hover">
			<tr>
				<th>ФИО пациента</th>
				<th>Адрес</th>
				<th>Телефоны / Email</th>
				<th>Поставщик</th>
				<th title="Полнота данных">Данные</th>
				<th>Ред.</th>
			</tr>
			<?=$table?>
		</table>
	</div>
	<div class="tab-pane" id="tab2">
		<table class="table table-condensed table-bordered table-striped table-hover">
			<tr>
				<th>ФИО пациента</th>
				<th>Адрес</th>
				<th>Телефоны / Email</th>
				<th>Поставщик</th>
				<th title="Полнота данных">Данные</th>
				<th>Ред.</th>
			</tr>
			<?=$tableinactive?>
		</table>
	</div>
</div>

<div class="modal hide" id="patEditor" style="width:640px;">
	<div class="modal-header" style="cursor:move;background-color: #d6d6d6">
		<button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
		<h4><span id="editorHeader">Редактирование данных пациента</span>&nbsp;&nbsp;&nbsp;<button id="patActivator" class="btn btn-mini btn-warning" title="Пометить снятым с учёта">Исключить</button></h4>
		<u><span id="headerfio" class=""><span></u>
	</div>
	<div class="modal-body" style="height:420px;overflow:hidden;">
		<form method="post" id="patDataForm" action="/refs/pat_save" enctype="multipart/form-data" class="form-inline row-fluid">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tab1" id="htab1" data-toggle="tab">Пациент</a></li>
				<li><a href="#tab2" id="htab2" data-toggle="tab">Информация для врача</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="tab1">
					<table>
					<tr>
						<td>Фамилия</td>
						<td><input type="text" name="pat_f" id="pat_f" tabindex=1></td>
						<td><a href="#" id="dirlink" title="Переход по ссылке на страницу поставщиков">Поставщик</a></td>
						<td><select name="directed" id="directed">
							<option value="0"> -- Не указан -- </option>
						</select></td>
					</tr>
					<tr>
						<td>Имя</td>
						<td><input type="text" name="pat_i" id="pat_i" tabindex=2></td>
						<td><a href="#" id="clientlink" title="Переход по ссылке на страницу клиентов">Клиент</a></td>
						<td><select name="clientid" id="clientid">
							<option value="0"> -- Не указан -- </option>
						</select></td>
					</tr>
					<tr>
						<td>Отчество</td>
						<td><input type="text" name="pat_o" id="pat_o" tabindex=3></td>
						<td title="Электронная почта сопоставленного клиента">E-mail</td>
						<td><input type="text" name="email" id="email" tabindex=7 disabled></td>
					</tr>
					<tr>
						<td>Телефон, моб.</td>
						<td ><input type="text" name="cphone" id="cphone" tabindex=6 title="Мобильный телефон пациента"></td>
						<td>Телефон, дом.</td>
						<td><input type="text" name="hphone" id="hphone" tabindex=11 title="Домашний телефон пациента"></td>
					</tr>
					<tr>
						<td>Адрес</td>
						<td><input type="text" name="address" id="address" tabindex=5></td>
						<td>Расположение</td>
						<td><input type="text" name="location" id="location" tabindex=19></td>
					</tr>
					</table>
					<hr>
					<table>
					<tr>
						<td>Паспорт, серия</td>
						<td><input type="text" name="pass_s" id="pass_s" tabindex=7></td>
						<td>Паспорт, номер</td>
						<td><input type="text" name="pass_n" id="pass_n" tabindex=8></td>
					</tr>
					<tr>
						<td>Паспорт выдан</td>
						<td><input type="text" name="pass_issued" id="pass_issued" tabindex=9></td>
						<td>Паспорт, дата выдачи</td>
						<td><input type="text" name="pass_issuedate" id="pass_issuedate" tab="1" class="withCal" tabindex=10></td>
					</tr>
					</table>
					<input type="hidden" id="patID" name="patid" value="foo" />
				</div>
				<div class="tab-pane" id="tab2">
					<table style="width:98%">
					<tr>
						<td style="width:75px;">Дата рождения</td>
						<td colspan=3>
							<input type="text" name="birth" id="birth" tab="2" class="withCal" tabindex=4>
						</td>
					</tr>
					<table style="width:98%">
					<tr>
						<td style="width:75px;">Диагноз</td>
						<td colspan=3>
							<textarea name="diagnosis" id="diagnosis" rows="4" cols="7" style="width:98%;" tabindex=12></textarea>
						</td>
					</tr>
					<tr>
						<td>Заметки</td>
						<td colspan=3>
							<textarea name="info" id="info" rows="4" cols="7" style="width:98%;" tabindex=13></textarea>
						</td>
					</tr>
					</table>
				</div>
			</div>
		</form>
	</div>
	<div class="modal-footer">
			<button type="submit" class="btn btn-primary btn-block" id="patDataSave" style="margin-top:10px;">Сохранить</button>
	</div>
</div>

<!-- Карточки участников процесса -->
<div class="modal hide" id="patData" style="width:640px;">
	<div class="modal-header" style="cursor:move;background-color: #d6d6d6">
		<button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
		<h4>Карточка пациента</h4>
	</div>
	<div class="modal-body" id="patDataText" style="height:460px;"></div>
</div>

<div class="modal hide" id="suppData" style="width:640px;">
	<div class="modal-header" style="cursor:move;background-color: #d6d6d6">
		<button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
		<h4>Карточка поставщика</h4>
	</div>
	<div class="modal-body" id="suppDataText" style="height:460px;"></div>
</div>

<div class="modal hide" id="clientData" style="width:640px;">
	<div class="modal-header" style="cursor:move;background-color: #d6d6d6">
		<button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
		<h4>Карточка клиента</h4>
	</div>
	<div class="modal-body" id="clientDataText" style="height:460px;"></div>
</div>
<!-- Карточки участников процесса -->

<script type="text/javascript">
<!--
	$(".modal").modal({show: 0});
	
	$("patEdit").click(function(event){
		var patid = $(this).attr("ref");
		$("#patID").val(patid);
		$.ajax({
			url: "/refs/pat_item_get",
			type: "POST",
			data: { patid: patid },
			dataType: 'script',
			success: function(){
				/*
				* получены:
				* объект patdata (ключи - id поля, values - значения полей таблицы), 
				* строки dirlist и cl_list - содержимое полей <select>
				*/
				for (a in patdata){
					$("#" + a).val(patdata[a]);
				}
				$("#directed").append(dirlist);
				$('#directed option[value="' + patdata['directed'] + '"]').attr("selected", "selected");
				$("#clientid").append(cl_list);
				$('#clientid option[value="' + patdata['client'] + '"]').attr("selected", "selected");
				$("#clientlink").attr("href", "/refs/clients.html#c" + patdata['client']);
				$("#dirlink").attr("href", "/refs/suppliers.html#s" + patdata['directed']);
				$("#headerfio").html([patdata['pat_f'], patdata['pat_i'], patdata['pat_o']].join(" "));
				if(patdata['active']){
					$("#patActivator").html("Исключить");
					$("#patActivator").unbind("click").click(function(){
						deactivate_pat();
					});
				}else{
					$("#patActivator").html("Отменить исключение");
					$("#patActivator").unbind("click").click(function(){
						activate_pat();
					});
				}
				$("#editorHeader").html("Редактирование данных пациента");
				$("#patActivator").removeClass("hide");
				$("#patEditor").modal("show");
				return false;
			},
			error: function(data,stat,err){
				alert([data,stat,err].join("\n"));
			}
		});
		event.stopPropagation();
	});

	$("#patAddNew").click(function(event){
		$.ajax({
			url: "/refs/pat_lists_get",
			type: "POST",
			dataType: 'script',
			success: function(){
				/*
				* строки dirlist и cl_list - содержимое полей <select>
				*/
				$("#directed").empty().append(dirlist);
				$("#clientid").empty().append(cl_list);
				$("#patDataForm input, #patDataForm textarea").each(function(){
					$(this).val("");
				});
				$("#patDataForm select").each(function(){
					$(this).val("0");
				});
				$("#patActivator").addClass("hide");
				$("#editorHeader").html("Добавление пациента");
				$("#patDataForm").attr("action", "/refs/pat_item_add");
				$("#patDataSave").html("Добавить");
				$("#patEditor").modal("show");
			},
			error: function(data,stat,err){
				alert([data,stat,err].join("\n"));
			}
		});
		event.stopPropagation();
	});

	function deactivate_pat(){
		patid = $("#patID").val();
		$.ajax({
			url: "/refs/pat_deactivate",
			type: "POST",
			data: { patid: patid },
			success: function(){
				window.location.reload();
			},
			error: function(data,stat,err){
				alert([data,stat,err].join("\n"));
			}
		});
	}

	function activate_pat(){
		patid = $("#patID").val();
		$.ajax({
			url: "/refs/pat_activate",
			type: "POST",
			data: { patid: patid },
			success: function(){
				window.location.reload();
			},
			error: function(data,stat,err){
				alert([data,stat,err].join("\n"));
			}
		});
	}

	$("#patDataSave").click(function(){
		var good = 1;
		$(".withCal").css('color', 'black');
		$(".withCal").each(function(){
			var ertab = $(this).attr('tab');
			//alert(ertab);
			if ( /\d\d\.\d\d.\d\d\d\d/.test($(this).val()) ) {
				//alert("вроде порядок");
			}else{
				good = 0;
				//alert("Неверный формат даты");
				$("#ertabber" + ertab).remove();
				$("#htab" + ertab).append(' <span id="ertabber' + ertab + '" class="badge badge-important">!</span>');
				$(this).val("Неверный формат даты").css('color', 'red');
				$(this).focus(function(){
					var ertab = $(this).attr('tab');
					$("#ertabber" + ertab).remove();
					$(this).css('color', 'black');
				})
			}
		});
		if(!good){
			return false;
		}else{
			$("#patDataForm").submit();
		}
	});

	$(".createCrs").click(function(){
		var patid = $(this).attr("ref");
		if(confirm("Создать курс для этого пациента?")){
			$.ajax({
				url: "/refs/pat_createcrs",
				type: "POST",
				data: { patid: patid },
				success: function(){
					window.location.reload();
				},
				error: function(data,stat,err){
					alert([data,stat,err].join("\n"));
				}
			});
		}
	});

	// ############ функции показа карточек участников процесса -- deprecated!!!
	/*
	$(".patrow").click(function(){
		var patid = $(this).attr("ref");
		$.ajax({
			url: "/refs/pat_item_get",
			type: "POST",
			data: { patid: patid },
			dataType: 'script',
			success: function(){
				var string = "<h4>" + patdata['pat_f'] + " " + patdata['pat_i'] + " " + patdata['pat_o'] + "</h4>" +
					"Адрес: <b>" + patdata['address'] + "</b>, расположение: <b>" + patdata['location'] + "</b><br>" +
					"Дата рождения: <b>" + patdata['birth'] + " (полных лет: " + patdata['age'] + ")</b><br>" +
					"тел.(дом): <b>" + patdata['hphone'] + "</b>, тел.(моб): <b>" + patdata['cphone'] + "</b><br><br>" +
					"<b>Диагноз:</b> " + patdata['diagnosis'] + "<br>" +
					"<b>Заметки:</b> " + patdata['info'];
				$("#patDataText").html(string);
				$("#patData").modal("show");
				return false;
			},
			error: function(data,stat,err){
				alert([data,stat,err].join("\n"));
			}
		});
	});

	$(".clientrow").click(function(){
		var clientid = $(this).attr("ref");
		if(clientid == "0"){
			alert("Данные отсутствуют");
			return false;
		}
		$.ajax({
			url: "/refs/client_item_get",
			type: "POST",
			data: { clientid: clientid },
			dataType: 'script',
			success: function(){
				var string = "<h4>" + clientdata['client_f'] + " " + clientdata['client_i'] + " " + clientdata['client_o'] + "</h4>" +
					"Адрес: <b>" + clientdata['address'] + "</b>, тел.(дом): <b>" + clientdata['hphone'] + "</b>, тел.(моб): <b>" + clientdata['cphone'] + "</b><br><br>" +
					"Паспортные данные: серия: " + clientdata['pass_s'] + " № " + clientdata['pass_n'] + "<br>Выдан:" + clientdata['pass_issued'] + ", дата выдачи: " + clientdata['pass_issuedate'] + "<br>" +
					"<b>Заметки:</b> " + clientdata['note'];
				$("#clientDataText").html(string);
				$("#clientData").modal("show");
				return false;
			},
			error: function(data,stat,err){
				alert([data,stat,err].join("\n"));
			}
		});
	});
	
	$(".supprow").click(function(){
		var suppid = $(this).attr("ref");
		if(suppid == "0"){
			alert("Данные отсутствуют");
			return false;
		}
		$.ajax({
			url: "/refs/supp_item_get",
			type: "POST",
			data: { suppid: suppid },
			dataType: 'script',
			success: function(){
				var string = "<h4>" + suppdata['supp_f'] + " " + suppdata['supp_i'] + " " + suppdata['supp_o'] + "</h4>" +
					"Место работы: <b>" + suppdata['org'] + "</b><br>" +
					"Должность/специализация: <b>" + suppdata['staff'] + "</b><br>" +
					"Адрес: <b>" + suppdata['address'] + "</b> тел.: <b>" + suppdata['phone'] + "</b><br>e-mail: <b>" + suppdata['email'] + "</b> <br><br>" +
					"<b>Заметки:</b> " + suppdata['note'];
				$("#suppDataText").html(string);
				$("#suppData").modal("show");
				return false;
			},
			error: function(data,stat,err){
				alert([data,stat,err].join("\n"));
			}
		});
	});
	*/
	// ############ END функции показа карточек участников процесса



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
		$(".patEdit[ref=" + window.location.hash.substr(2) + "]").click();
	}

//-->
</script>