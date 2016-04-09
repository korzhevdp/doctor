<style type="text/css">
	.busy{
		cursor:pointer;
		font-size:8px;
		text-align:center;
	}
	.good{
		background-color:#339966;
	}
	.undone{
		background-color:#ff9933;
	}
	.collision{
		background-color:#cc3300;
	}
	#dateLoad{
		margin-bottom:0px;
		margin-right:5px;
	}

</style>
<h2>Нагрузка на инструкторов&nbsp;&nbsp;&nbsp;&nbsp;<small"><input class="withCal" id="dateLoad" type="text" value="<?=$nowdate;?>"><span class="btn btn-info btn-mini" id="dateShow">Показать</span></small></h2>
<table class="table table-bordered">
<tr>
	<td>&nbsp;&nbsp;<a class="btn btn-info pull-left" href="/shedule/pload/<?=$prevdate;?>">Назад</a></td>
	<td><a class="btn btn-info pull-right" href="/shedule/pload/<?=$nextdate;?>">Далее</a>&nbsp;&nbsp;</td>
</tr>
</table>
<table class="table table-bordered">
<?=$timeline;?>
<?=$content;?>
<?=$timeline;?>
</table>

<div class="modal hide" id="calInfo">
	<div class="modal-header">
		<h4>Информация о процедурах и услугах</h4>
	</div>
	<div class="modal-body">
		<div id="patInfoText"></div>
		Заметки:<br>
		<textarea id="comment" rows="4" cols="4" style="width:97%"></textarea>
		<center style="margin-top:15px;">
			<button class="btn btn-large btn-warning" title="Услуга оказана" data-dismiss="modal" aria-hidden="true" id="jobDone" style="margin-right:75px;">Исполнено</button>
			<button class="btn btn-large btn-danger"  title="Услуге в календаре устанавливается состояние неоказанной" id="jobUnDone" data-dismiss="modal" aria-hidden="true">Отмена исполнения</button>
			<button class="btn btn-large" data-dismiss="modal" aria-hidden="true">Закрыть</button>
		</center>
	</div>
</div>

<script type="text/javascript">
<!--
	$(".modal").modal({show: 0});
	$(".busy").click(function(){
		$("#patInfoText").html($(this).attr("title") + ",<br>исполнитель: " + $("#" + $(this).attr("sref")).text());
		m = $(this).attr("servid");
		$("#jobDone").unbind().click(function(){
			$.ajax({
				url: "/shedule/jobdone",
				type: "POST",
				data: { 
					calId: m,
					comment: $("#comment").val(),
					noredirect: 1
				},
				dataType: 'script',
				success: function(data){
					$("td[servid=" + data + "]").html('<center><img src="/images/tick-button.png" width="16" height="16" border="0" alt=""></center>');
				},
				error: function(data,stat,err){
					alert([data,stat,err].join("\n"));
				}
			});
		});
		$("#jobUnDone").unbind().click(function(){
			$.ajax({
				url: "/shedule/jobundone",
				type: "POST",
				data: { 
					calId: m,
					comment: $("#comment").val(),
					noredirect: 1
				},
				dataType: 'script',
				success: function(data){
					$("td[servid=" + data + "]").html('<center><img src="/images/delete.png" width="16" height="16" border="0" alt=""></center>');
				},
				error: function(data,stat,err){
					alert([data,stat,err].join("\n"));
				}
			});
		});
		$("#calInfo").modal('show');
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
	$(".withCal").datepicker( "option", "changeYear", true);

	$("#dateShow").click(function(){
		val = $("#dateLoad").val().split(".").reverse().join("/");
		window.location.href = "/shedule/pload/" + val;
	});
//-->
</script>
