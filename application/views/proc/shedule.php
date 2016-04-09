<style type="text/css">
	.servcell{
		width: 30px;
		cursor:pointer;
		z-index:2;
	}
	.servactive{
		background-color: #009966;
	}
	.servselected{
		background-color: #dcb524;
	}
	.table{
		border-collapse:collapse;
	}
	#shed td{
		text-align: center;
		vertical-align: middle;
	}
</style>

<h3>Составление расписания работ на <?=$date;?>&nbsp;&nbsp;&nbsp;<a href="#" class="btn btn-small btn-warning pull-right" style="margin-right:25px;" id="iNeedHelp">Помощь</a></h3>
<table class="table table-bordered table-condensed" id="shed" style="margin-right:20px;">
	<tr>
		<th>Часы</th><?=$headings1;?>
	</tr>
	<tr>
		<th>Минуты</th><?=$headings2;?>
	</tr>
	<tr>
		<td colspan=27 style="text-align:left"><strong>Пациенты</strong></td>
	</tr>
	<?=$table;?>
</table>

<div class="modal hide" id="ifYesModal">
	<div class="modal-header">
		<h4>Назначение времени оказания услуги и исполнителя</h4>
	</div>
	<div class="modal-body">
		<div id="xsummary" style="margin-top:5px;margin-bottom:5px;font-size:12px;line-height:14px;color:#747474;border-bottom:2px solid #c6c6c6;"></div>
		<h5>Инструктор</h5>
		<select id="instructor" title="Инструкторы, у которых оформлен допуск на услугу. Если инструктора нет в списке - проверьте его допуски на странице Сотрудники"></select>
		<div id="instructor_info" style="height:100px;overflow:hidden;">&nbsp;</div>
		<label style="margin:1px;padding:1px;"><input type="checkbox" id="useTNext">&nbsp;&nbsp;Назначить на следующую процедуру</label>
		<label style="margin:1px;padding:1px;"><input type="checkbox" id="useTAlways">&nbsp;&nbsp;Назначить на весь курс</label>
		<center style="margin-top:10px;">
			<button class="ifYes btn btn btn-primary" value="1">Сохранить</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<button class="btn ifYes" value="0" data-dismiss="modal" aria-hidden="true">Закрыть</button>
		</center>
	</div>
</div>

<div class="modal hide" id="helpM">
	<div class="modal-header">
		<h4>Как этим пользоваться</h4>
	</div>
	<div class="modal-body">
		В столбцах расположены пациенты и прописанные им на указанную дату услуги и процедуры.<br><br>Щелчок на клетки стобца позволяет выбрать начало оказания услуги по графику, а повторный щелчок - позволяет перейти к выбору инструктора и, затем, подтвердить выбор. <br>Это назначит услугу в персональный график инструктора.
		<center style="margin-top:10px;">
			<button class="btn ifYes" value="0" data-dismiss="modal" aria-hidden="true">Закрыть</button>
		</center>
	</div>
</div>

<script type="text/javascript">
<!--
	
	var initTime = 0,
		endTime = 0,
		isSelected = 0,
		column = 0;

	$(".modal").modal({show: 0});

	$(".servcell").unbind('click').click(function(){
		var staff,
			calendarId = $(this).attr('cal'),
			serviceId = $(this).attr('sid');
		if(!isSelected){
			column = $(this).attr('cal');
			$('.c' + column).filter(".servselected").removeClass('servselected').empty();
			$(this).addClass('servselected').html('<img src="/images/question-button.png" width="16" height="16" border="0" alt="">');
			initTime = parseInt($(this).attr('ref'));
			isSelected = 1;
		}else{
			if(column != $(this).attr('cal')){
				return false;
			}

			endTime = parseInt($(this).attr('ref'));
			fio = $(this).attr('fio');
			title = $(this).attr('title');
			if(endTime < initTime){
				n = endTime;
				m = initTime;
				endTime = m;
				initTime = n;
			}
			$('.c' + column).removeClass('servselected');
			$('.c' + column).each(function(){
				a = parseInt($(this).attr('ref'));
				if(a >= initTime && a <= endTime){
					$(this).addClass("servselected").html('<img src="/images/question-button.png" width="16" height="16" border="0" alt="">');
				}
			});
			starttime = (Math.ceil(initTime / 2) + 7) + ((initTime % 2) ? ":00" : ":30");
			stoptime  = (Math.ceil(endTime / 2 ) + 7) + ((endTime %  2) ? 0 : 1) + ((endTime %  2) ? ":30" : ":00");
			ifYes(column, starttime, stoptime, fio, title, initTime, endTime);
			isSelected = 0;
			manageStaff(calendarId, serviceId);
		}
	});

	function manageStaff(calendarId, serviceId){
		$.ajax({
			url: "/shedule/getinst",
			type: "POST",
			dataType: 'script',
			data: {
				cal: calendarId,
				service: serviceId
			},
			success: function(){
				$("#instructor").empty().append("<option value=0>- выберите инструктора - </option>" + staffdata['suitableList']);
				$("#instructor option[value='" + staffdata['inst'] + "']").attr("selected", "selected");
				$("#instructor").val(staffdata['inst']);
				$("#instructor_info").empty();
				$("#instructor").unbind('change').change(function(){
					var id = parseInt($(this).val()),
						string = '<h4 class="muted" style="margin:5px, 5px;">Инструктор не выбран</h4>';
						$("#insSaver").attr("disabled", 'disabled');
					if(id){
						string = '<h4>' + staffdata.info[id].fio + '&nbsp;&nbsp;&nbsp;<small>' + staffdata.info[id].staff+ '</small></h4>' +
						'телефон: ' + staffdata.info[id].phone + '<br>' + 
						'курируемый район : ' + staffdata.info[id].location + '<br>' +
						'должность: ' + staffdata.info[id].rank;
						$("#insSaver").removeAttr("disabled");
					}
					$("#instructor_info").html(string);
					$("#cal_id").val(calendarId);

					// отображение5 информации о стаффе
				});
				$("#instructor").change();
				$("#instModal").modal("show");
			},
			error: function(data,stat,err){
				alert([data,stat,err].join("\n"));
			}
		});
	}

	$("#iNeedHelp").click(function(){
		$("#helpM").modal("show");
	})
/*
	$(".setIns").unbind('click').click(function(){
		if($(this).val() != "0"){
			var cal =     $("#cal_id").val(),
				inst =    $("#instructor").val(),
				isNext = ($("#useNext").attr('checked') == 'checked')   ? 1 : 0,
				isAll =  ($("#useAlways").attr('checked') == 'checked') ? 1 : 0;
			//return false;

			$.ajax({
				url: "/shedule/saveinst",
				type: "POST",
				dataType: 'text',
				data: {
					cal: cal,
					inst: inst,
					next: isNext,
					all: isAll
				},
				success: function(data){
					if(data == "OK"){
						//alert($("#cal_id").val());
						$('.c' + $("#cal_id").val()).filter(".servactive").html('<img src="/images/alarm-clock.png" width="16" height="16" border="0" alt="">')
					}
					$("#instModal").modal("hide");
				},
				error: function(data,stat,err){
					alert([data,stat,err].join("\n"));
				}
			});
		}
	});
*/
	function ifYes(column, starttime, stoptime, fio, title, initTime, endTime){
		var announce = "<h4>" + title + "</h4>Вы хотите назначить пациенту <strong>" + fio + "</strong><br>производство работ, предусмотренных процедурой \"" + title + "\"<br>на период времени с: <strong>" + starttime + "</strong> по: <strong>"+ stoptime + "</strong>?";
		$("#xsummary").html(announce);
		$("#ifYesModal").modal("show");
		$(".ifYes").unbind('click').click(function(){
			if(parseInt($(this).val()) == 1){
				if($("#instructor").val() == "0"){
					$("#instructor").css('color', 'red');
					return false;
				}
				cal    = column,
				start  = starttime + ":00",
				stop   = stoptime  + ":00",
				isNext = ($("#useTNext").attr('checked') == 'checked')   ? 1 : 0,
				isAll  = ($("#useTAlways").attr('checked') == 'checked') ? 1 : 0,
				inst = $("#instructor").val();
				$.ajax({
					url: "/shedule/savetime",
					type: "POST",
					data: {
						cal: cal,
						start: start,
						stop: stop,
						next: isNext,
						all: isAll,
						inst: inst
					},
					dataType: 'text',
					success: function(data){
						if(data == "OK"){
							$('.c' + column).removeClass("servactive servselected").empty();
							$('.c' + column).each(function(){
								a = parseInt($(this).attr('ref'));
								if(a >= initTime && a <= endTime){
									$(this).addClass("servactive").html('<img src="/images/alarm-clock.png" width="16" height="16" border="0" alt="">');
								}
							});
							manageStaff();
						}
						initTime = 0;
						endTime = 0;
					},
					error: function(data,stat,err){
						alert([data,stat,err].join("\n"));
					}
				});
				$("#ifYesModal").modal("hide");
			}else{
				$("#ifYesModal").modal("hide");
				$('.c' + column).filter(".servselected").empty();
				$('.c' + column).removeClass('servselected');
				$('.c' + column).filter(".servactive").html('<img src="/images/alarm-clock.png" width="16" height="16" border="0" alt="">')
			}
		});
	}
	// starting Staff Daemon
	manageStaff();

//-->
</script>