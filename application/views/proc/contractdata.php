<h4><span id="editorHeader">Редактирование контракта</span>&nbsp;&nbsp;&nbsp;</h4>
<div class="modal-body">

		<ul class="nav nav-tabs">
			<li class="active"><a href="#tab1" id="tabber1" data-toggle="tab">Общие</a></li>
			<li><a href="#tab2" id="tabber2" data-toggle="tab">Услуги</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="tab1">
				<table>
				<tr>
					<td style="width:160px;padding-left:15px;">Номер</td>
					<td><input type="text" form="servDataForm" id="number" name="number" tabindex=1>
					</td>
				</tr>
				<tr>
					<td style="width:160px;padding-left:15px;">Клиент</td>
					<td>
						<select name="client" form="servDataForm" id="client" tabindex=2>

						</select>
					</td>
				</tr>
				<tr>
					<td style="width:160px;padding-left:15px;">Пациент</td>
					<td>
						<select name="patient" form="servDataForm" id="patient" tabindex=3>
							<option value="0">Выберите пациента</option>
						</select>
					</td>
				</tr>
				<tr>
					<td style="width:160px;padding-left:15px;">Дата подписания</td>
					<td><input type="text" name="datestart" form="servDataForm" id="datestart" class="withCal" placeholder="Укажите дату подписания (заключения) контракта" tabindex=4></td>
				</tr>
				<tr>
					<td style="width:160px;padding-left:15px;">Дата окончания</td>
					<td><input type="text" name="dateend" form="servDataForm" id="dateend" class="readonly" placeholder="Выберите дату окончания" tabindex=5 readonly></td>
				</tr>
				</table>
			</div>
			<div class="tab-pane" id="tab2">
				<h5>Услуги по контракту <span id="servCounter"></span>
				<a href="#" id="addSingleDateService" class="btn btn-mini btn-warning pull-right" title="Добавить услугу в договор">Добавить разовую услугу</a>
				<a href="#" id="addService" class="btn btn-mini btn-warning pull-right" title="Добавить услугу в договор">Добавить услугу</a>
				</h5>
				<table class="table table-bordered" style="width:auto">
				<tbody>
					<tr>
						<td colspan=4>Начало оказания услуг&nbsp;&nbsp;<input type="text" name="servstart" form="servDataForm" id="servstart" class="withCal"></td>
					</tr>
					<tr>
						<th>Услуга</th>
						<th>Стоимость</th>
						<th>Количество</th>
						<th>Дни оказания</th>
						<th>&nbsp;</th>
					</tr>
				</tbody>
				<tbody id="servContList"></tbody>
				</table>
			</div>
		</div>
		<input type="hidden" form="servDataForm" id="servcount" name="servcount" value="0" />
		<input type="hidden" form="servDataForm" id="cID" name="cID" value="<?=$cid;?>" />
	<form method="post" id="servDataForm" action="/contracts/cnt_save" enctype="multipart/form-data" class="form-inline row-fluid">
	</form>
	<div class="modal-footer">
		<a class="btn pull-left" id="cont_href" href="#" target="_blank">Получить копию контракта</a>
		<a class="btn pull-left" id="cont_a_href" href="#" target="_blank">Акт выполненных работ</a>
		<button id="contActivator" ref="<?=$cid;?>" class="btn btn-danger" title="Закрыть контракт (и отметить его неактивным)">Завершить контракт</button>
		<button type="submit" class="btn btn-primary" id="contDataSave">Сохранить</button>
		<button type="button" class="btn btn-warning" id="contUnderwrite" ref="<?=$cid;?>">Подписать</button>
	</div>
</div>
<script type="text/javascript">
<!--
	var contract,
		servcount = 1;

	function returnString(a, signed){
		inform = ( signed ) ? "" : 'form="servDataForm"';
		return '<tr class="servicerow">' +
			'<td style="vertical-align:middle;padding:5px;">'+
				'<select name="service' + a + '" ' + inform + ' id="service' + a + '" ref="' + a + '" class="services" style="width:310px;"></select>'+
			'</td>' +
			'<td style="vertical-align:middle;padding:5px;"><div class="input-append input-prepend" title="Копейки указывать не обязательно">' +
				'<input type="text" name="servprice' + a + '" ' + inform + ' id="servprice' + a + '" value="" maxlength=7 style="width:75px;">' +
				'<span class="add-on" style="line-height:18px;height:20px;">p.</span>' +
				'<input type="text" name="servprice_k' + a + '" ' + inform + ' id="servprice_k' + a + '" value="00" maxlength=2 style="width:20px;">' +
				'<span class="add-on" style="line-height:18px;height:20px;">коп.</span>' +
				'</div>' +
			'</td>' +
			'<td style="vertical-align:middle;padding:5px;">'+
				'<input type="text" class="numOnly" name="num' + a + '" ' + inform + ' id="num' + a + '" placeholder="Введите количество" value="" style="width:75px;">'+
			'</td>' +
			'<td style="vertical-align:middle;padding:5px;">' +
				'<div class="btn-toolbar">' +
					'<div class="btn-group" id="dsw' + a + '" class="dsw" ref="' + a + '" title="Выберите дни оказания услуги">' +
						'<a href="#" day="1" class="btn btn-mini btd">пн</a><a href="#" day="2" class="btn btn-mini btd">вт</a><a href="#" day="3" class="btn btn-mini btd">ср</a>' +
						'<a href="#" day="4" class="btn btn-mini btd">чт</a><a href="#" day="5" class="btn btn-mini btd">пт</a><a href="#" day="6" class="btn btn-mini btd">сб</a>' +
						'<a href="#" day="0" class="btn btn-mini btd">вс</a><br>' +
						'<input type="hidden" ' + inform + ' id="servdaylist' + a + '" name="servdaylist' + a + '" value="">' +
					'</div>' +
				'</div>' +
			'</td>' +
			'<td style="vertical-align:middle;padding:5px;text-align:center">' +
				'<img src="/images/delete.png" class="rowDelete" style="width:16px;height:16px;border:none;cursor:pointer;" title="Удалить услугу" alt="Удалить">' +
			'</td></tr>'
	}

	function returnSingleDateString(a, signed){
		inform = ( signed ) ? "" : 'form="servDataForm"';
		return '<tr class="servicerow">' +
			'<td style="vertical-align:middle;padding:5px;">'+
				'<select name="service' + a + '" ' + inform + ' id="service' + a + '" ref="' + a + '" class="services" style="width:310px;"></select>'+
			'</td>' +
			'<td style="vertical-align:middle;padding:5px;"><div class="input-append input-prepend" title="Копейки указывать не обязательно">' +
				'<input type="text" name="servprice' + a + '" ' + inform + ' id="servprice' + a + '" value="" maxlength=7 style="width:75px;">' +
				'<span class="add-on" style="line-height:18px;height:20px;">p.</span>' +
				'<input type="text" name="servprice_k' + a + '" ' + inform + ' id="servprice_k' + a + '" value="00" maxlength=2 style="width:20px;">' +
				'<span class="add-on" style="line-height:18px;height:20px;">коп.</span>' +
				'</div>' +
			'</td>' +
			'<td style="vertical-align:middle;padding:5px;">'+
				'<input type="text" class="numOnly" name="num' + a + '" ' + inform + ' id="num' + a + '" placeholder="Введите количество" value="1" readonly="readonly" style="width:75px;">'+
			'</td>' +
			'<td style="vertical-align:middle;padding:5px;">' +
				'<div class="btn-toolbar">' +
					'<div class="btn-group" id="dsw' + a + '" class="dsw" ref="' + a + '" title="Выберите дни оказания услуги">' +
						'<input type="text" name="servdate' + a + '" ' + inform + ' class="withCal" title="дата оказания услуги" placeholder="00.00.0000">' +
					'</div>' +
				'</div>' +
			'</td>' +
			'<td style="vertical-align:middle;padding:5px;text-align:center">' +
				'<img src="/images/delete.png" class="rowDelete" style="width:16px;height:16px;border:none;cursor:pointer;" title="Удалить услугу" alt="Удалить">' +
			'</td></tr>'
	}
	
	function addServString(type, signed){
		a = ++servcount;
		//alert(servcount)
		(type == 'shedule') ? $("#servContList").append(returnString(a, signed)) : $("#servContList").append(returnSingleDateString(a, signed));
		if(typeof initdata != 'undefined' ){
			$("#service" + (a)).append(initdata.services);
		}
		if(typeof contract != 'undefined' ){
			$("#service" + (a)).append(contract.servlist);
		}
		$("#servCounter").html("(" + $("#servContList tr").length + ")");
		place_automation(a);
	}
	
	$("#addService").click(function(event){
		event.stopPropagation();
		addServString('shedule', false);
		return false;
	});

	$("#addSingleDateService").click(function(event){
		event.stopPropagation();
		addServString('single', false);
		return false;
	});

	$(".contAdder").click(function(event){
		cnt_initdata_get();
		event.stopPropagation();
	});
	
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

	// проверка на смещение графика услуг. 
	// Все неоказанные услуги смещаются, рассчитываясь от нового начала графика.
	// Но сначала - оценка возможных разрушений.
	$("#servstart").change(function(){
		//alert($(this).val());
	});

	function get_contract_data(cnt){
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
				//$("#contEditor").modal('show');
				$("#cont_href").attr('href'  , '/docs/cg/'  + contract.data.id);
				$("#cont_a_href").attr('href', '/docs/cag/' + contract.data.id);
				//alert(contract.data.signed);
				(contract.data.signed) ? $("#contUnderwrite").attr("disabled", "disabled") : $("#contUnderwrite").removeAttr("disabled");
				serv = contract.services;
				//alert(serv.toSource());
				for(a in serv){
					servcount = (a > servcount) ? a : servcount;
					inform = ( contract.data.signed ) ? "" : 'form="servDataForm"';
					am = '<tr class="servicerow">' +
					'<td style="vertical-align:middle;padding:5px;">'+
						'<select name="service' + a + '" ' + inform + ' id="service' + a + '" class="services" ref="'+ a +'" style="width:310px;">'+ contract.servlist +'</select>'+
					'</td>' +
					'<td style="vertical-align:middle;padding:5px;"><div class="input-append input-prepend" title="Копейки указывать не обязательно">' +
						'<input type="text" name="servprice' + a + '" ' + inform + ' id="servprice' + a + '" value="' + contract.services[a].price + '" maxlength=7 style="width:75px;">' +
						'<span class="add-on" style="line-height:18px;height:20px;">p.</span>' +
						'<input type="text" name="servprice_k' + a + '" ' + inform + ' id="servprice_k' + a + '" value="' + contract.services[a].price_k + '" maxlength=2 style="width:20px;">' +
						'<span class="add-on" style="line-height:18px;height:20px;">коп.</span>' +
						'</div>' +
					'</td>' +
					'<td style="vertical-align:middle;padding:5px;">'+
						'<input type="text" class="numOnly" name="num' + a + '" ' + inform + ' id="num' + a + '" placeholder="Введите количество" value="'+ contract.services[a].num +'" style="width:75px;">'+
					'</td>' +
					'<td style="vertical-align:middlepadding:5px;">' +
						'<div class="btn-toolbar" title="Выберите дни оказания услуги">' +
							'<div class="btn-group" id="dsw' + a + '" class="dsw" ref="' + a + '">' +
								((contract.services[a].num == "1") 
									? '<input type="text" ' + inform + ' name="servdate' + a + '" class="withCal" title="дата оказания услуги" placeholder="00.00.0000" value="' + contract.services[a].date + '">' 
									: '<a href="#" day="1" class="btn btn-mini btd">пн</a><a href="#" day="2" class="btn btn-mini btd">вт</a><a href="#" day="3" class="btn btn-mini btd">ср</a>' +
									'<a href="#" day="4" class="btn btn-mini btd">чт</a><a href="#" day="5" class="btn btn-mini btd">пт</a><a href="#" day="6" class="btn btn-mini btd">сб</a>' +
									'<a href="#" day="0" class="btn btn-mini btd">вс</a><br>' +
									'<input type="hidden" ' + inform + ' id="servdaylist' + a + '" name="servdaylist' + a + '" value="' + contract.services[a].days + '">') +
							'</div>' +
						'</div>' +
					'</td>' +
					'<td style="vertical-align:middle;padding:5px;;text-align:center"><img src="/images/delete.png" class="rowDelete" style="width:16px;height:16px;border:none;cursor:pointer;" alt="Удалить" title="Удалить услугу"></td></tr>';
					$("#servContList").append(am);
					$("#service" + a + " option[value='" + a + "']").attr("selected", "selected");
				}
				for (a in contract.services){
					src = contract.services[a].days.split(",");
					for (b in src){
						$("#dsw" + a + ' a[day=' + src[b] + ']').addClass("btn-success");
					}
				}
				
				$("#servCounter").html("(" + $("#servContList tr").length + ")");
				place_automation(servcount);
				//alert(servcount);

			},
			error: function(data,stat,err){
				alert([data,stat,err].join("\n"));
			}
		});
	}

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
		$(".services").unbind().change(function(){
			$("#servprice" + $(this).attr('ref')).val(sp[$(this).val()]);
		});

		setCalendar();
	}

	$("#contUnderwrite").click(function(){
		//alert("Контракт будет отмечен подписанным. Любые дальнейшие изменения в нём будут создавать дополнительные соглашения");
		id = $(this).attr("ref");
		$.ajax({
			url: "/contracts/underwrite",
			data: { id: id },
			type: "POST",
			dataType: 'text',
			success: function(data){
				if(data == "OK"){
					alert("Контракт подписан");
				}
			},
			error: function(data,stat,err){
				alert([data,stat,err].join("\n"));
			}
		});
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
				servcount = 1;
				addServString();
				$("#contEditor").modal('show');
			},
			error: function(data,stat,err){
				alert([data,stat,err].join("\n"));
			}
		});
	}
	
	if(parseInt($("#cID").val()) > 0){
		get_contract_data($("#cID").val());
	}

	$("#contDataSave").click(function(){
		var errCount = 0;
		/* Логика валидации:
		* на данном этапе информация о номере контракта и датах начала/окончания не потребуется
		* проверка их требуется на этапе подписания. зато здесь имеет смысл следить за полнотой 
		* данных во второй вкладке - с услугами
		*/
		$("#tab2 input").each(function(){
			if(!$(this).val().length){
				$(this).attr("placeholder", "Введите значение!");
				$(this,"#tabber2").css("border-color", "red");
				setTimeout(function(){
					$("#tab2 input").css("border-color", "#CCCCCC")
				}, 15000);
				errCount++;
			}
		});

		$("#tab2 select option:selected").parent().each(function(){
			if($(this).val() == "0"){
				$(this, "#tabber2").css("color", "red");
				errCount++;
				setTimeout(function(){
					$("#tab2 select option:selected").parent().css("color", "#555555")
				}, 15000);
			}
		});

		$("#tab1 select option:selected").parent().each(function(){
			if($(this).val() == "0"){
				$(this).css("color", "red");
				$("#tabber1").css("color", "red");
				errCount++;
				setTimeout(function(){
					$("#tab1 select option:selected, #tabber1").parent().css("color", "#555555");
				}, 15000);
			}
		});

		if(errCount){
			console.log("ошибок: " + errCount);
		}else{
			$("#servDataForm").submit();
		}

	});




	$("#contActivator").click(function(){
		cntid = $(this).attr("ref");
		$.ajax({
			url: "/contracts/actsw",
			type: "POST",
			data: {
				cnt: cntid
			},
			dataType: 'text',
			success: function(data){
				alert(data);
			},
			error: function(data,stat,err){
				alert([data,stat,err].join("\n"));
			}
		});
	});

	function setCalendar(){
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
		$(".withCal").unbind().datepicker($.datepicker.regional['ru']);
		//changeMonth and changeYear
		//$(".withCal").datepicker( "option", "showWeek", true );
		$(".withCal").datepicker( "option", "changeYear", true);
	}

	setCalendar();

	if (window.location.hash) {
		$(".contEdit[ref=" + window.location.hash.substr(2) + "]").click();
	}
//-->
</script>