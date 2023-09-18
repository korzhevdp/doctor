<style type="text/css">
	#servlist{
		margin-left:0px;
	}
	#servlist li{
		float:left;
		width:285px;
		margin-right:10px;
		list-style-type:none;
		vertical-align:middle;
	}
	#servlist span.text{
		display:block;
		margin:5px;
		vertical-align:middle;
	}
	#servlist label{
		vertical-align:middle;
		display:block;
		font-size:12px;
		line-height:14px;
		width:100%;
		height:48px;
		border:1px solid #ddd;
		border-radius: 5px;  /* свойство для тех кто его поддерживает */
		-moz-border-radius: 5px;  /* для firefox */
		-webkit-border-radius: 5px;  /* для Safari и Chrome */
	}
	#servlist li input{
		float:left;
		margin:4px;
		margin-top:0px;
	}
</style>

<h3>Персонал&nbsp;&nbsp;&nbsp;<small>список</small><a href="<?=base_url();?>refs/new_staff" class="btn btn-warning btn-small pull-right" style="margin-right:10px;">+ Добавить</a></h3>

<ul class="nav nav-tabs">
	<li class="active"><a href="#tab1" id="htab1" data-toggle="tab">Сотрудники</a></li>
	<li><a href="#tab2" id="htab2" data-toggle="tab">Бывшие сотрудники</a></li>
</ul>
<div class="tab-content">
	<div class="tab-pane active" id="tab1">
		<table class="table table-condensed table-bordered table-striped table-hover">
			<tr>
				<th>ФИО / должность</th>
				<th>Образование / Специальность</th>
				<th>Адрес / курируемый район</th>
				<th>Телефон / Email</th>
				<th>Ред.</th>
			</tr>
			<?=$table?>
		</table>
	</div>
	<div class="tab-pane" id="tab2">
		<table class="table table-condensed table-bordered table-striped table-hover">
			<tr>
				<th>ФИО / должность</th>
				<th>Образование / Специальность</th>
				<th>Адрес / курируемый район</th>
				<th>Телефон / Email</th>
				<th>Ред.</th>
			</tr>
			<?=$inactivetable?>
		</table>
	</div>
</div>
<div class="modal hide" id="staffData" style="width:640px;">
	<div class="modal-header" style="cursor:move;background-color: #d6d6d6">
		<button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
		<h4>Карточка персонала</h4>
	</div>
	<div class="modal-body" id="staffDataText" style="height:460px;"></div>
</div>

<script type="text/javascript">
	<!--
	/*
		$(".modal").modal({show: 0});

		$(".staffEdit").click(function(event){
			var staffid = $(this).attr("ref");
			$("#staffID").val(staffid);
			$.ajax({
				url: "/refs/staff_item_get",
				type: "POST",
				data: { staffid: staffid },
				dataType: 'script',
				success: function(){
					for (a in staffdata){
						$("#" + a).val(staffdata[a]);
						//alert(staffdata[a]);
					}
					$("#servlist").empty().append(staffdata['services']);
					$("#headerfio").html([staffdata['staff_f'], staffdata['staff_i'], staffdata['staff_o']].join(" "));
					if(staffdata['active']){
						$("#staffActivator").html("Уволить");
						$("#staffActivator").unbind("click").click(function(){
							deactivate_staff();
						});
					}else{
						$("#staffActivator").html("Отменить увольнение");
						$("#staffActivator").unbind("click").click(function(){
							activate_staff();
						});
					}
					$("#editorHeader").html("Редактирование данных работника");
					$("#staffActivator").removeClass("hide");
					$("#staffEditor").modal("show");
					return false;
				},
				error: function(data,stat,err){
					alert([data,stat,err].join("\n"));
				}
			});
			event.stopPropagation();
		});
	*/
		$("#staffAddNew").click(function(event){
			$("#staffDataForm input").each(function(){
				$(this).val("");
			});
			$("#staffActivator").addClass("hide");
			$("#editorHeader").html("Добавление нового работника");
			$("#staffDataForm").attr("action", "/refs/staff_item_add");
			$("#staffDataSave").html("Добавить");
			$("#staffEditor").modal("show");
		});

		function deactivate_staff(){
			staffid = $("#staffID").val();
			$.ajax({
				url: "<?=base_url();?>refs/staff_deactivate",
				type: "POST",
				data: { staffid: staffid },
				success: function(){
					window.location.reload();
				},
				error: function(data,stat,err){
					alert([data,stat,err].join("\n"));
				}
			});
		}

		function activate_staff(){
			staffid = $("#staffID").val();
			$.ajax({
				url: "<?=base_url();?>refs/staff_activate",
				type: "POST",
				data: { staffid: staffid },
				success: function(){
					window.location.reload();
				},
				error: function(data,stat,err){
					alert([data,stat,err].join("\n"));
				}
			});
		}
	/*
		$(".staffrow").click(function(){
			var staffid = $(this).attr("ref");
			$.ajax({
				url: "/refs/staff_item_get",
				type: "POST",
				data: { staffid: staffid },
				dataType: 'script',
				success: function(){
					var string = "<h4>" + staffdata['staff_f'] + " " + staffdata['staff_i'] + " " + staffdata['staff_o'] + "</h4>" +
						"Должность: <b>" + staffdata['staff'] + "</b><br>" +
						"Образование: <b>" + staffdata['edu'] +  "</b> Специализация: <b>" + staffdata['spec'] + "</b><br>" +
						"Курируемый район: <b>" + staffdata['location'] + "</b><br>" +
						"Адрес: <b>" + staffdata['address'] + "</b> тел.: <b>" + staffdata['phone'] + "</b><br><br>" +
						"Паспортные данные: " + staffdata['passport'] + " серия: " + staffdata['pass_s'] + " № " + staffdata['pass_n'] + "<br>Выдан:" + staffdata['pass_issued'] + ", дата выдачи: " + staffdata['pass_issuedate'] + "<br>" +
						"ИНН: <b>" + staffdata['inn'] + "</b> СНИЛС: <b>" + staffdata['snils'] + "</b><hr>" + 
						"<b>Заметки:</b> " + staffdata['note'];
					$("#staffDataText").html(string);
					$("#staffData").modal("show");
					return false;
				},
				error: function(data,stat,err){
					alert([data,stat,err].join("\n"));
				}
			});
		});

		$("#staffDataSave").click(function(){
			var good = 1;
			$(".withCal").css('color', 'black');
			$(".withCal").each(function(){
				var ertab = $(this).attr('tab');
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
				$("#staffDataForm").submit();
			}
		});

		$("#credChange").unbind().click(function(){
			if($(this).attr("checked") == 'checked'){
				$("#username, #userpass, #userpassR, #masterPass").removeAttr('disabled');
				$("#masterPassTable").removeClass("hide");
			}else{
				$("#username, #userpass, #userpassR, #masterPass").attr('disabled', 'disabled');
				$("#masterPassTable").addClass("hide");
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
		*/
	//-->
</script>