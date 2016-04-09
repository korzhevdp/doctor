<h3 style="margin-bottom:20px;">Реестр контрактов&nbsp;&nbsp;&nbsp;<small>текущие контракты и история</small></h3> 
<!-- <button type="button" class="btn btn-warning pull-right contAdder" style="margin-right:10px;">Добавить контракт</button> -->

<ul class="nav nav-tabs">
	<li class="active"><a href="#tab1" id="htab1" data-toggle="tab">Текущие контракты</a></li>
	<li><a href="#tab2" id="htab2" data-toggle="tab">Закрытые контракты</a></li>
</ul>
<div class="tab-content">
	<div class="tab-pane active" id="tab1">
		<a href="/contracts/add" class="btn btn-warning pull-right" style="margin-right:10px;">Добавить контракт</a><br><br>
		<table class="table table-condensed table-bordered" style="margin-bottom:60px;margin-right:10px;">
			<tr>
			<th>Данные контракта</th>
			<th>Клиент</th>
			<th>Пациент</th>
			<th>Услуги по контракту</th>
			<th>График услуг</th>
			<th>Ред.</th>
			</tr>
		<?=$table?>
		</table>
	</div>
	<div class="tab-pane" id="tab2">
		<table class="table table-condensed table-bordered" id="closedContracts" style="margin-bottom:60px;margin-right:10px;">
			<tr>
			<th>Данные контракта</th>
			<th>Клиент</th>
			<th>Пациент</th>
			<th>Услуги по контракту</th>
			<th>График услуг</th>
			<th>Ред.</th>
			</tr>
		<?=$tableinactive?>
		</table>
	</div>
</div>






<!-- <div class="modal hide" id="contEditor" style="width:740px;">
	<div class="modal-header" style="cursor:move;background-color: #d6d6d6">
		<button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
		<h4><span id="editorHeader">Редактирование контракта</span>
	</div>

	<div class="modal-body" style="height:420px;overflow:auto;">
		<form method="post" id="servDataForm" action="/contracts/cnt_save" enctype="multipart/form-data" class="form-inline row-fluid">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tab1" data-toggle="tab">Общие</a></li>
				<li><a href="#tab2" data-toggle="tab">Услуги</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="tab1">
					<table>
					<tr>
						<td>Номер</td>
						<td><input type="text" name="number" id="number" tabindex=1>
						</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>Клиент</td>
						<td>
							<select name="client" id="client" tabindex=2>

							</select>
						</td>
						<td>Пациент</td>
						<td>
							<select name="patient" id="patient" tabindex=3>
								<option value="0">Выберите пациента</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Дата начала</td>
						<td><input type="text" name="datestart" id="datestart" class="withCal" placeholder="Выберите дату начала" tabindex=4></td>
						<td>Дата окончания</td>
						<td><input type="text" name="dateend" id="dateend" class="withCal" placeholder="Выберите дату окончания" tabindex=5></td>
					</tr>
					</table>
				</div>
				<div class="tab-pane" id="tab2">
					<h5>Услуги по договору <a href="#" id="addService" class="btn btn-mini btn-warning pull-right" title="Добавить услугу">Добавить</a></h5>
					<table class="table table-bordered">
					<tbody>
						<tr>
							<td colspan=4>Начало оказания услуг&nbsp;&nbsp;<input type="text" name="servstart" id="servstart" class="withCal"></td>
						</tr>
						<tr>
							<th style="width:220px;">Услуга</th>
							<th style="width:75px;">Количество</th>
							<th style="width:200px;">Дни оказания</th>
							<th style="width:20px;">&nbsp;</th>
						</tr>
					</tbody>

					<tbody id="servContList">
					
					</tbody>
					</table>
				</div>
			</div>
			<input type="hidden" id="servcount" name="servcount" value="0" />
			<input type="hidden" id="cID" name="cID" value="0" />
		</form>
	</div>
	<div class="modal-footer">
		<a class="btn pull-left" id="cont_href" href="#" target="_blank">Получить копию контракта</a>
		<button type="submit" class="btn btn-primary" id="contDataSave" style="margin-top:10px;">Сохранить</button>
		<button type="button" class="btn btn-warning" id="contDataSign" style="margin-top:10px;" disabled="disabled">Подписать</button>
	</div>
</div> 
-->

<input type="hidden" id="pStrict" value="<?=(isset($pstrict)) ? $pstrict : 0 ;?>">
<input type="hidden" id="cStrict" value="<?=(isset($cstrict)) ? $cstrict : 0 ;?>">

<script type="text/javascript">
<!--
/*
	$(".modal").modal({show: 0});
	
	var contract,
		servcount = 1;
	
	function addServString(){
		a = ++servcount;
		//alert(servcount)
		var am = '<tr class="servicerow">' +
		'<td style="vertical-align:middle">'+
			'<select name="service' + a + '" id="service' + a + '" class="services" style="width:210px;"></select>' +
		'</td>' +
		'<td style="vertical-align:middle">'+
			'<input type="text" class="numOnly" name="num' + a + '" id="num' + a + '" placeholder="Введите количество" value="" style="width:75px;">'+
		'</td>' +
		'<td style="vertical-align:middle">' +
			'<div class="btn-toolbar">' +
				'<div class="btn-group" id="dsw' + a + '" class="dsw" ref="' + a + '">' +
					'<a href="#" day="1" class="btn btn-mini btd">пн</a><a href="#" day="2" class="btn btn-mini btd">вт</a><a href="#" day="3" class="btn btn-mini btd">ср</a>' +
					'<a href="#" day="4" class="btn btn-mini btd">чт</a><a href="#" day="5" class="btn btn-mini btd">пт</a><a href="#" day="6" class="btn btn-mini btd">сб</a>' +
					'<a href="#" day="0" class="btn btn-mini btd">вс</a>' +
					'<input type="hidden" id="servdaylist' + a + '" name="servdaylist' + a + '" value="">' +
				'</div>' +
			'</div>' +
		'</td>' +
		'<td style="vertical-align:middle;text-align:center">' +
			'<img src="/images/delete.png" class="rowDelete" style="width:16px;height:16px;border:none;cursor:pointer;" alt="Удалить">' +
		'</td></tr>';
		$("#servContList").append(am);
		if(typeof initdata != 'undefined' ){
			$("#service" + (a)).append(initdata.services);
		}
		if(typeof contract != 'undefined' ){
			$("#service" + (a)).append(contract.servlist);
		}
		place_automation(a);
	}
	
	$("#addService").click(function(event){
		event.stopPropagation();
		addServString();
		return false;
	});

	$(".contAdder").click(function(event){
		cnt_initdata_get();
		event.stopPropagation();
	});
	
	function cnt_initdata_get(){
		$("#editorHeader").html('Создание контракта');
		$.ajax({
			url: "/contracts/cnt_initdata_get",
			type: "POST",
			dataType: 'script',
			success: function(){
				$("#client").empty().append(initdata.clients);
				$("#servContList").empty();
				//alert($("#cStrict").val() +' '+ $("#pStrict").val());
				if($("#cStrict").val() != "0"){
					$('#client option[value=' + $("#cStrict").val() + ']').attr("selected", "selected");
					$('#client').change();
				}
				$("#number").val(initdata.cn);
				servcount = 1;
				addServString();
				$("#contEditor").modal('show');
			},
			error: function(data,stat,err){
				alert([data,stat,err].join("\n"));
			}
		});
	}

	$("#client").change(function(event){
		var cli = $(this).val();
		if(cli != "0"){
			$.ajax({
				url: "/contracts/rel_pats_get",
				data: { cli: cli },
				type: "POST",
				dataType: 'script',
				success: function(){
					$("#patient").empty().append(data);
					$("#contEditor").modal('show');
					// зачотный костыль из другой функции :)
					if(typeof contract != 'undefined' && typeof contract.data.patient != 'undefined'){
						$("#patient option[value='" + contract.data.patient + "']").attr("selected", "selected");
					}
					if($("#pStrict").val() != 0){
						$("#patient option[value='" + $("#pStrict").val() + "']").attr("selected", "selected");
					}
				},
				error: function(data,stat,err){
					alert([data,stat,err].join("\n"));
				}
			});
		}
		event.stopPropagation();
	});

	$(".sheduleEdit").click(function(){
		alert("Редактирую расписание услуг");
	});

	$(".contEdit").click(function(event){
		var cnt = $(this).attr('ref');
		$("#cID").val(cnt);
		//alert("Редактирую данные контракта - надо ли? Надо, но только до кнопки /Подписано/ ");
		event.stopPropagation();
		$("#servContList").empty();
		$.ajax({
			url: "/contracts/cnt_data_get",
			data: { cnt: cnt },
			type: "POST",
			dataType: 'script',
			success: function(){
				b = 0;
				$("#number").val(contract.data.num);
				$("#datestart").val(contract.data.start);
				$("#dateend").val(contract.data.end);
				$("#servstart").val(contract.servstartdate);
				$("#client").empty().append(contract.clientlist);
				$("#client option[value='" + contract.data.client + "']").attr("selected", "selected");
				$("#client").change();
				$("#patient option[value='" + contract.data.patient + "']").attr("selected", "selected");
				$("#contEditor").modal('show');
				$("#cont_href").attr('href', '/docs/cg/' + contract.data.id);
				serv = contract.services;
				//alert(serv.toSource());
				for(a in serv){
					servcount = (a > servcount) ? a : servcount;
					am = '<tr class="servicerow">' +
					'<td style="vertical-align:middle">'+
						'<select name="service' + a + '" id="service' + a + '" class="services" style="width:210px;">'+ contract.servlist +'</select>' +
					'</td>' +
					'<td style="vertical-align:middle">'+
						'<input type="text" class="numOnly" name="num' + a + '" id="num' + a + '" placeholder="Введите количество" value="'+ serv[a].num +'" style="width:75px;">'+
					'</td>' +
					'<td style="vertical-align:middle">' +
						'<div class="btn-toolbar">' +
							'<div class="btn-group" id="dsw' + a + '" class="dsw" ref="' + a + '">' +
								'<a href="#" day="1" class="btn btn-mini btd">пн</a><a href="#" day="2" class="btn btn-mini btd">вт</a><a href="#" day="3" class="btn btn-mini btd">ср</a>' +
								'<a href="#" day="4" class="btn btn-mini btd">чт</a><a href="#" day="5" class="btn btn-mini btd">пт</a><a href="#" day="6" class="btn btn-mini btd">сб</a>' +
								'<a href="#" day="0" class="btn btn-mini btd">вс</a>' +
								'<input type="hidden" id="servdaylist' + a + '" name="servdaylist' + a + '" value="' + contract.services[a].days + '">' +
							'</div>' +
						'</div>' +
					'</td><td style="vertical-align:middle;text-align:center"><img src="/images/delete.png" class="rowDelete" style="width:16px;height:16px;border:none;cursor:pointer;" alt="Удалить"></td></tr>';
					$("#servContList").append(am);
					$("#service" + a + " option[value='" + a + "']").attr("selected", "selected");
				}
				for (a in contract.services){
					src = contract.services[a].days.split(",");
					for (b in src){
						$("#dsw" + a + ' a[day=' + src[b] + ']').addClass("btn-success");
					}
				}
				place_automation(servcount);
				//alert(servcount);

			},
			error: function(data,stat,err){
				alert([data,stat,err].join("\n"));
			}
		});
		//return false;
	});

	function place_automation(b){
		$("#servcount").val(b);
		$(".numOnly").keyup(function(){
			$(this).val( $(this).val().replace(/[^0-9]/, '') );
		});
		$(".rowDelete").click(function(){
			$(this).parent().parent().remove();
		});
		$(".btd").unbind().click(function(){
			if($(this).hasClass('btn-success')){
				$(this).removeClass('btn-success');
			}else{
				$(this).addClass('btn-success');
			}
			output = [];
			$('.btd').each(function(){
				var serv = $(this).parent().attr('ref');
				if(typeof output[serv] == 'undefined'){
					output[serv] = [];
				}
				if($(this).hasClass('btn-success')){
					output[serv].push($(this).attr('day'));
				}
			});
			for (a in output){
				$('#servdaylist' + a).val(output[a].join(","));
			}
		});
	}

	if($("#pStrict").val() != "0" && $("#pStrict").val() != "0"){
		cnt_initdata_get();
	}

	$("#contDataSave").click(function(){
		var errCount = 0;
		/* Логика валидации:
		* на данном этапе информация о номере контракта и датах начала/окончания не потребуется
		* проверка их требуется на этапе подписания. зато здесь имеет смысл следить за полнотой 
		* данных во второй вкладке - с услугами
		*\/
		$("#tab2 input").each(function(){
			if(!$(this).val().length){
				$(this).attr("placeholder", "Введите значение!");
				$(this).css("border-color", "red");
				setTimeout(function(){
					$("#tab2 input").css("border-color", "#CCCCCC")
				}, 5000);
				errCount++;
			}
		});

		$("#tab2 select option:selected").parent().each(function(){
			if($(this).val() == "0"){
				$(this).css("color", "red");
				errCount++;
				setTimeout(function(){
					$("#tab2 select option:selected").parent().css("color", "#555555")
				}, 5000);
			}
		});

		$("#tab1 select option:selected").parent().each(function(){
			if($(this).val() == "0"){
				$(this).css("color", "red");
				errCount++;
				setTimeout(function(){
					$("#tab1 select option:selected").parent().css("color", "#555555")
				}, 5000);
			}
		});
		if(errCount){
			alert("ошибок: " + errCount);
		}else{
			$("#servDataForm").submit();
		}
	});


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
		$(".contEdit[ref=" + window.location.hash.substr(2) + "]").click();
	}
*/
$("#showClosed").click(function(){
	if($("#closedContracts").hasClass("hide")){
		$("#closedContracts").removeClass("hide");
		$("#showClosed").html("Скрыть контракты");
		return false
	}else{
		$("#closedContracts").addClass("hide");
		$("#showClosed").html("Показать контракты");
		return false;
	}
});
//-->
</script>