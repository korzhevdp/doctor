<style type="text/css">
	td.fullycentered {
		vertical-align:middle;
		text-align:center;
		width:50px;
	}
</style>
<h3>
	Клиенты&nbsp;&nbsp;&nbsp;<small>Балтийские Реабилитационные Технологии</small>
	<a href="/refs/new_client" class="btn btn-warning pull-right" style="margin-right:10px;">Добавить</a>
</h3>
<table class="table table-condensed table-bordered table-striped table-hover">
	<tr>
		<th>ФИО клиента</th>
		<th>Поставщик</th>
		<th>Адрес</th>
		<th>Телефоны / Email</th>
		<th title="Полнота данных клиента">Данные</th>
		<th>Ред.</th>
	</tr>
	<?=$table?>
</table>

<h3>Бывшие клиенты</h3>
<table class="table table-condensed table-bordered table-striped table-hover" style="margin-bottom:60px;">
	<tr>
		<th>ФИО клиента</th>
		<th>Поставщик</th>
		<th>Адрес</th>
		<th>Телефоны / Email</th>
		<th title="Полнота данных клиента">Данные</th>
		<th>Ред.</th>
	</tr>
	<?=$tableinactive?>
</table>

<div class="modal hide" id="clientEditor" style="width:640px;">
	<div class="modal-header" style="cursor:move;background-color: #d6d6d6">
		<button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
		<h4><span id="editorHeader">Редактирование клиента</span>&nbsp;&nbsp;&nbsp;<button id="clientActivator" class="btn btn-mini btn-warning">Исключить</button></h4>
		<u><span id="headerfio" class=""><span></u>
	</div>
	<div class="modal-body" style="height:420px;overflow:hidden;">
		<form method="post" id="clientDataForm" action="/refs/client_save" enctype="multipart/form-data" class="form-inline row-fluid">
			<table>
			<tr>
				<td>Фамилия</td>
				<td><input type="text" name="client_f" id="client_f" tabindex=1></td>
				<td>Паспорт, серия</td>
				<td><input type="text" name="pass_s" id="pass_s" tabindex=7></td>
			</tr>
			<tr>
				<td>Имя</td>
				<td><input type="text" name="client_i" id="client_i" tabindex=2></td>
				<td>Паспорт, номер</td>
				<td><input type="text" name="pass_n" id="pass_n" tabindex=8></td>
			</tr>
			<tr>
				<td>Отчество</td>
				<td><input type="text" name="client_o" id="client_o" tabindex=3></td>
				<td>Паспорт выдан</td>
				<td><input type="text" name="pass_issued" id="pass_issued" tabindex=9></td>
			</tr>
			<tr>
				<td>Адрес</td>
				<td><input type="text" name="address" id="address" tabindex=4></td>
				<td>Паспорт, дата выдачи</td>
				<td><input type="text" name="pass_issuedate" id="pass_issuedate" tab="1" class="withCal" tabindex=10></td>
			</tr>
			<tr>
				<td>Телефон, моб.</td>
				<td><input type="text" name="cphone" id="cphone" tabindex=5></td>
				<td>Телефон, дом.</td>
				<td><input type="text" name="hphone" id="hphone" tabindex=11></td>
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
			<input type="hidden" id="clientID" name="clientid" value="foo" />
		</form>
	</div>
	<div class="modal-footer">
			<button type="submit" class="btn btn-primary btn-block" id="clientDataSave" style="margin-top:10px;">Сохранить</button>
	</div>
</div>

<div class="modal hide" id="clientData" style="width:640px;">
	<div class="modal-header" style="cursor:move;background-color: #d6d6d6">
		<button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
		<h4>Карточка клиента</h4>
	</div>
	<div class="modal-body" id="clientDataText" style="height:460px;"></div>
</div>

<script type="text/javascript">
<!--
	$(".modal").modal({show: 0});

	$(".clientEdit").click(function(event){
		var clientid = $(this).attr("ref");
		$("#clientID").val(clientid);
		$.ajax({
			url: "/refs/client_item_get",
			type: "POST",
			data: { clientid: clientid },
			dataType: 'script',
			success: function(){
				for (a in clientdata){
					$("#" + a).val(clientdata[a]);
					//alert(clientdata[a]);
				}
				$("#headerfio").html([clientdata['client_f'], clientdata['client_i'], clientdata['client_o']].join(" "));

				if(clientdata['active']){
					$("#clientActivator").html("Исключить");
					$("#clientActivator").unbind("click").click(function(){
						deactivate_client();
					});
				}else{
					$("#clientActivator").html("Отменить исключение");
					$("#clientActivator").unbind("click").click(function(){
						activate_client();
					});
				}
				$("#editorHeader").html("Редактирование данных клиента");
				$("#clientActivator").removeClass("hide");
				$("#clientEditor").modal("show");
				return false;
			},
			error: function(data,stat,err){
				alert([data,stat,err].join("\n"));
			}
		});
		event.stopPropagation();
	});

	$("#clientAddNew").click(function(event){
		$("#clientDataForm input").each(function(){
			$(this).val("");
		});
		$("#clientActivator").addClass("hide");
		$("#editorHeader").html("Добавление клиента");
		$("#clientDataForm").attr("action", "/refs/client_item_add");
		$("#clientDataSave").html("Добавить");
		$("#clientEditor").modal("show");
	});

	function deactivate_client(){
		clientid = $("#clientID").val();
		$.ajax({
			url: "/refs/client_deactivate",
			type: "POST",
			data: { clientid: clientid },
			success: function(){
				window.location.reload();
			},
			error: function(data,stat,err){
				alert([data,stat,err].join("\n"));
			}
		});
	}

	function activate_client(){
		clientid = $("#clientID").val();
		$.ajax({
			url: "/refs/client_activate",
			type: "POST",
			data: { clientid: clientid },
			success: function(){
				window.location.reload();
			},
			error: function(data,stat,err){
				alert([data,stat,err].join("\n"));
			}
		});
	}

	$(".patrow").click(function(event){
		event.stopPropagation();
	});

	$(".clientrow").click(function(){
		var clientid = $(this).attr("ref");
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

	$("#clientDataSave").click(function(){
		var good = 1;
		$(".withCal").css('color', 'black');
		$(".withCal").each(function(){
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
			return false;
		}else{
			$("#clientDataForm").submit();
		}
	})



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
//-->
</script>