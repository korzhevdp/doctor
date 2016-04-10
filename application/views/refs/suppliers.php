<h3>Поставщики&nbsp;&nbsp;&nbsp;<small>клиентов</small><a href="/refs/new_supp" class="btn btn-warning btn-small pull-right" style="margin-right:10px;">+ Добавить</a></h3>
<table class="table table-condensed table-bordered table-striped table-hover">
	<tr>
		<th style="width:250px;">ФИО / Место работы</th>
		<th style="width:150px;">Должность / Специальность</th>
		<th>Адрес</th>
		<th style="width:75px;">Телефон / Email</th>
		<th style="width:150px;">Счёт</th>
	</tr>
	<?=$table?>
</table>

<!-- <div class="modal hide" id="suppEditor" style="width:640px;">
	<div class="modal-header" style="cursor:move;background-color: #d6d6d6">
		<button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
		<h4><span id="editorHeader">Редактирование данных поставщика</span>&nbsp;&nbsp;&nbsp;<button id="suppActivator" class="btn btn-mini btn-warning">Исключить</button></h4>
		<u><span id="headerfio" class=""><span></u>

	</div>
	<div class="modal-body" style="height:340px;overflow:hidden;">
		<form method="post" id="suppDataForm" action="/refs/supp_save" enctype="multipart/form-data" class="form-inline row-fluid">
			<table>
			<tr>
				<td>Фамилия</td>
				<td><input type="text" name="supp_f" id="supp_f" tabindex=1></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td>Имя</td>
				<td><input type="text" name="supp_i" id="supp_i" tabindex=2></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td>Отчество</td>
				<td><input type="text" name="supp_o" id="supp_o" tabindex=3></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td>Должность</td>
				<td><input type="text" name="staff" id="staff" tabindex=4></td>
				<td>Место работы</td>
				<td><input type="text" name="org" id="org" tabindex=5></td>
			</tr>
			<tr>
				<td>Адрес</td>
				<td><input type="text" name="address" id="address" tabindex=6></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td>Телефон</td>
				<td><input type="text" name="phone" id="phone"  tabindex=7></td>
				<td>E-mail</td>
				<td><input type="text" name="email" id="email" tabindex=8></td>
			</tr>
			<tr>
				<td>Заметки</td>
				<td colspan=3>
					<textarea name="info" id="info" rows="4" cols="7" style="width:98%;" tabindex=9></textarea>
				</td>
			</tr>
			<tr>
				<td>Вознаграждение</td>
				<td style="width:125px;">
					<div class="input-append" style="width:125px;">
						<input id="royalty" style="width:45px;" name="royalty" type="text">
						<span style="line-height:20px;height:20px;" class="add-on">%</span>
					</div>
				</td>
			</tr>
			</table>

			<input type="hidden" id="suppID" name="suppid" value="foo" />
		</form>
	</div>
	<div class="modal-footer">
			<button type="submit" class="btn btn-primary btn-block" id="suppDataSave" style="margin-top:10px;">Сохранить</button>
	</div>
</div>

<div class="modal hide" id="suppData" style="width:640px;">
	<div class="modal-header" style="cursor:move;background-color: #d6d6d6">
		<button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
		<h4>Карточка поставщика</h4>
	</div>
	<div class="modal-body" id="suppDataText" style="height:460px;"></div>
</div> -->

<script type="text/javascript">
<!--
	/*
	$(".modal").modal({show: 0});

	$(".suppEdit").click(function(event){
		var suppid = $(this).attr("ref");

		$("#suppID").val(suppid);
		$.ajax({
			url: "/refs/supp_item_get",
			type: "POST",
			data: { suppid: suppid },
			dataType: 'script',
			success: function(){
				for (a in suppdata){
					$("#" + a).val(suppdata[a]);
					//alert(suppdata[a]);
				}
				$("#headerfio").html([suppdata['supp_f'], suppdata['supp_i'], suppdata['supp_o']].join(" "));

				if(suppdata['active']){
					$("#suppActivator").html("Исключить");
					$("#suppActivator").unbind("click").click(function(){
						deactivate_supp();
					});
				}else{
					$("#suppActivator").html("Отменить исключение");
					$("#suppActivator").unbind("click").click(function(){
						activate_supp();
					});
				}
				$("#editorHeader").html("Редактирование данных поставщика");
				$("#suppActivator").removeClass("hide");
				$("#suppEditor").modal("show");
				return false;
			},
			error: function(data,stat,err){
				alert([data,stat,err].join("\n"));
			}
		});
		event.stopPropagation();
	});
	
	$("#suppAddNew").click(function(event){
		$("#suppDataForm input").each(function(){
			$(this).val("");
		});
		$("#suppActivator").addClass("hide");
		$("#editorHeader").html("Добавление нового поставщика");
		$("#suppDataForm").attr("action", "/refs/supp_item_add");
		$("#suppDataSave").html("Добавить");
		$("#suppEditor").modal("show");
	});
	*/
	function deactivate_supp(){
		suppid = $("#suppID").val();
		$.ajax({
			url: "/refs/supp_deactivate",
			type: "POST",
			data: { suppid: suppid },
			success: function(){
				window.location.reload();
			},
			error: function(data,stat,err){
				alert([data,stat,err].join("\n"));
			}
		});
	}

	function activate_supp(){
		suppid = $("#suppID").val();
		$.ajax({
			url: "/refs/supp_activate",
			type: "POST",
			data: { suppid: suppid },
			success: function(){
				window.location.reload();
			},
			error: function(data,stat,err){
				alert([data,stat,err].join("\n"));
			}
		});
	}
	/*
	$(".supprow").click(function(){
		var suppid = $(this).parent().attr("ref");
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
	$("#suppDataSave").click(function(){
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
			$("#suppDataForm").submit();
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
		$(".suppEdit[ref=" + window.location.hash.substr(2) + "]").click();
	}
//-->
</script>