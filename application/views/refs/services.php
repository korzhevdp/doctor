<h3>
	Услуги&nbsp;&nbsp;&nbsp;<small>Балтийские Реабилитационные Технологии</small>
	<button class="btn btn-warning btn-small pull-right" style="margin-right:10px;" id="servAddNew">+ Добавить</button>
</h3>

<ul class="nav nav-tabs">
	<li class="active"><a href="#tab1" id="htab1" data-toggle="tab">Оказываемые услуги</a></li>
	<li><a href="#tab2" id="htab2" data-toggle="tab">Отсутствует персонал</a></li>
	<li><a href="#tab3" id="htab3" data-toggle="tab">Административно приостановленные</a></li>

</ul>
<div class="tab-content">
	<div class="tab-pane active" id="tab1">
		<table class="table table-condensed table-bordered table-striped table-hover">
			<tr>
				<th>Название услуги</th>
				<th>Описание услуги</th>
				<th>Место оказания</th>
				<th>Цена за единицу услуги</th>
				<th title="Карточка данных заполнена корректно">Данные</th>
				<th>Ред.</th>
			</tr>
			<?=$table?>
		</table>
	</div>
	<div class="tab-pane" id="tab2">
		<table class="table table-condensed table-bordered table-striped table-hover">
			<tr>
				<th>Название услуги</th>
				<th>Описание услуги</th>
				<th>Место оказания</th>
				<th>Цена за единицу услуги</th>
				<th title="Карточка данных заполнена корректно">Данные</th>
				<th>Ред.</th>
			</tr>
			<?=$inactivetable?>
		</table>
	</div>
	<div class="tab-pane" id="tab3">
		<table class="table table-condensed table-bordered table-striped table-hover">
			<tr>
				<th>Название услуги</th>
				<th>Описание услуги</th>
				<th>Место оказания</th>
				<th>Цена за единицу услуги</th>
				<th title="Карточка данных заполнена корректно">Данные</th>
				<th>Ред.</th>
			</tr>
			<?=$minactivetable?>
		</table>
	</div>
</div>

<div class="modal hide" id="servEditor" style="width:640px;">
	<div class="modal-header" style="cursor:move;background-color: #d6d6d6">
		<button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
		<h4><span id="editorHeader">Редактирование данных услуги</span>&nbsp;&nbsp;&nbsp;<button id="servActivator" class="btn btn-mini btn-warning" title="Пометить снятым с учёта">Исключить</button></h4>
	</div>
	<div class="modal-body" style="height:420px;overflow:hidden;">
		<form method="post" id="servDataForm" action="/refs/serv_save" enctype="multipart/form-data" class="form-inline row-fluid">
			<table>
			<tr>
				<td >Название</td>
				<td colspan=3><input type="text" name="name" id="name" style="width:98%" tabindex=1></td>
			</tr>
			<tr>
				<td>Сокращение</td>
				<td><input type="text" name="alias" id="alias" tabindex=2></td>
				<td>Место оказания</td>
				<td>
					<select name="location" id="location" tabindex=4>
					<option value="0"> -- Не указано -- </option>
					<option value="center">В центре</option>
					<option value="home">На дому</option>
					</select>
				</td>
			</tr>
			<tr>
				<td></td>
				<td colspan=3><h6>Цена</h6></td>
			</tr>
			<tr>
				<td>Общая</td>
				<td>
					<div class="input-append input-prepend" title="Копейки указывать не обязательно">
						<input type="text" name="price" id="price" tabindex=5 maxlength=7 style="width:75px;">
						<span class="add-on" style="line-height:18px;height:20px;">p.</span>
						<input type="text" name="price_k" id="price_k" tabindex=6 maxlength=2 style="width:20px;">
						<span class="add-on" style="line-height:18px;height:20px;">коп.</span>
					</div>
				</td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td>Стажёр</td>
				<td>
					<div class="input-append input-prepend" title="Копейки указывать не обязательно">
						<input type="text" name="price_t" id="price_t" tabindex=7 maxlength=7 style="width:75px;">
						<span class="add-on" style="line-height:18px;height:20px;">p.</span>
						<input type="text" name="price_tk" id="price_tk" tabindex=8 maxlength=2 style="width:20px;">
						<span class="add-on" style="line-height:18px;height:20px;">коп.</span>
					</div>
				</td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td>Инструктор</td>
				<td>
					<div class="input-append input-prepend" title="Копейки указывать не обязательно">
						<input type="text" name="price_i" id="price_i" tabindex=9 maxlength=7 style="width:75px;">
						<span class="add-on" style="line-height:16px;height:20px;">p.</span>
						<input type="text" name="price_ik" id="price_ik" tabindex=10 maxlength=2 style="width:20px;">
						<span class="add-on" style="line-height:16px;height:20px;">коп.</span>

					</div>
				</td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td>Описание</td>
				<td colspan=3>
					<textarea name="info" id="info" rows="4" cols="7" style="width:98%;" tabindex=11></textarea>
				</td>
			</tr>
			</table>
			<input type="hidden" id="servID" name="servid" value="foo" />
		</form>
	</div>
	<div class="modal-footer">
			<button type="submit" class="btn btn-primary btn-block" id="servDataSave" style="margin-top:10px;">Сохранить</button>
	</div>
</div>

<!-- Карточка услуги -->
<div class="modal hide" id="servData" style="width:640px;">
	<div class="modal-header" style="cursor:move;background-color: #d6d6d6">
		<button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
		<h4>Карточка услуги</h4>
	</div>
	<div class="modal-body" id="servDataText" style="height:460px;"></div>
</div>

<!-- Карточка услуги -->

<script type="text/javascript">
<!--
	$(".modal").modal({show: 0});

	$(".servEdit").click(function(event){
		var servid = $(this).attr("ref");
		$("#servID").val(servid);
		$.ajax({
			url: "/refs/serv_item_get",
			type: "POST",
			data: { servid: servid },
			dataType: 'script',
			success: function(){
				/*
				* получены:
				* объект servdata (ключи - id поля, values - значения полей таблицы), 
				* строки dirlist и cl_list - содержимое полей <select>
				*/
				for (a in servdata){
					$("#" + a).val(servdata[a]);
				}
				if(servdata['active'] == '1'){
					$("#servActivator").html("Исключить");
					$("#servActivator").unbind("click").click(function(){
						deactivate_serv();
					});
				}else{
					$("#servActivator").html("Отменить исключение");
					$("#servActivator").unbind("click").click(function(){
						activate_serv();
					});
				}
				$("#editorHeader").html("Редактирование данных услуги");
				$("#servActivator").removeClass("hide");
				$("#servEditor").modal("show");
				return false;
			},
			error: function(data,stat,err){
				alert([data,stat,err].join("\n"));
			}
		});
		event.stopPropagation();
	});

	$("#servAddNew").click(function(event){
		$("#servDataForm input, #servDataForm textarea").each(function(){
			$(this).val("");
		});
		$("#servDataForm select").each(function(){
			$(this).val("0");
		});
		$("#servActivator").addClass("hide");
		$("#editorHeader").html("Добавление услуги");
		$("#servDataForm").attr("action", "/refs/serv_item_add");
		$("#servDataSave").html("Добавить");
		$("#servEditor").modal("show");
	});

	function deactivate_serv(){
		servid = $("#servID").val();
		$.ajax({
			url: "/refs/serv_deactivate",
			type: "POST",
			data: { servid: servid },
			success: function(){
				window.location.reload();
			},
			error: function(data,stat,err){
				alert([data,stat,err].join("\n"));
			}
		});
	}

	function activate_serv(){
		servid = $("#servID").val();
		$.ajax({
			url: "/refs/serv_activate",
			type: "POST",
			data: { servid: servid },
			success: function(){
				window.location.reload();
			},
			error: function(data,stat,err){
				alert([data,stat,err].join("\n"));
			}
		});
	}

	$("#servDataSave").click(function(){
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
			$("#servDataForm").submit();
		}
	})

	// ############ функции показа карточек участников процесса
	$(".servrow").click(function(){
		var servid = $(this).attr("ref");
		$.ajax({
			url: "/refs/serv_item_get",
			type: "POST",
			data: { servid: servid },
			dataType: 'script',
			success: function(){
				var s_location = "";
				switch (servdata['location']){
					case "home" :
						s_location = "Оказание услуги производится на дому у клиента/пациента";
						break;
					case "center":
						s_location = "Оказание услуги производится в условиях реабилитационного центра";
						break;
				}
				var string = "<h4>" + servdata['name'] + "</h4>[ " + servdata['alias'] + " ]<br>" +
					"Место оказания: <b>" + s_location + "</b><br><br>" +
					"Цена услуги:&nbsp;&nbsp;&nbsp;" + servdata['price'] + "<br>По категориям исполнителей:<br>" +
					"Стажёр:&nbsp;&nbsp;&nbsp;<b>" + servdata['price_t'] + " р.</b>, Инструктор:&nbsp;&nbsp;&nbsp;<b>" + servdata['price_i'] + " р.</b>";
				$("#servDataText").html(string);
				$("#servData").modal("show");
				return false;
			},
			error: function(data,stat,err){
				alert([data,stat,err].join("\n"));
			}
		});
	});

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
		$(".servEdit[ref=" + window.location.hash.substr(2) + "]").click();
	}
//-->
</script>