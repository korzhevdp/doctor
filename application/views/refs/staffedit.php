<style type="text/css">
	table.staffDataTable{
		width:700px;
	}
	table.staffDataTable td.col1{
		width:160px;
	}
	table.staffDataTable td input[type=text],
	table.staffDataTable td input[type=password],
	table.staffDataTable td textarea{
		width: 460px;
		max-width: 460px;
		margin-bottom:0px;
	}
	table.staffDataTable td select{
		margin-bottom:0px;
		width: 475px;
	}
	#staffDataSave{
		margin-left: 390px;
	}
	#servlist{
		list-style-type:none;
		margin-left:2px;
	}
	#servlist input[type=checkbox]{
		width: 30px;
		margin:4px;
		margin-top:2px;
	}
	.btn-act{
		margin-left:10px;
	}
</style>

<button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
<h4><span id="editorHeader">Редактирование данных сотрудника</span></h4>
<u><span id="headerfio" class=""><span></u>

<ul class="nav nav-tabs">
	<li class="active"><a href="#tab1" id="htab1" data-toggle="tab">Сотрудник</a></li>
	<li><a href="#tab2" id="htab2" data-toggle="tab">Квалификация, услуги</a></li>
	<li><a href="#tab3" id="htab3" data-toggle="tab">Учётные данные</a></li>
</ul>
<div class="tab-content">

	<div class="tab-pane active" id="tab1">
		<table class="staffDataTable table table-condensed">
			<tr class="col1">
				<td>Фамилия</td>
				<td><input type="text" form="staffDataForm" name="staff_f" id="staff_f" tabindex=1 value="<?=$staff_f?>"></td>
			</tr>
			<tr>
				<td>Имя</td>
				<td><input type="text" form="staffDataForm" name="staff_i" id="staff_i" tabindex=2 value="<?=$staff_i?>"></td>
			</tr>
			<tr>
				<td>Отчество</td>
				<td><input type="text" form="staffDataForm" name="staff_o" id="staff_o" tabindex=3 value="<?=$staff_o?>"></td>
			</tr>
			<tr>
				<td>Должность</td>
				<td><input type="text" form="staffDataForm" name="staff" id="staff" tabindex=4 value="<?=$staff_staff?>"></td>
			</tr>

			<tr>
				<td>Адрес</td>
				<td><input type="text" form="staffDataForm" name="address" id="address" tabindex=5 value="<?=$staff_address?>"></td>
			</tr>
			<tr>
				<td>Дата рождения</td>
				<td><input type="text" form="staffDataForm" name="birth" id="birth" class="withCal" tab="1" tabindex=7 value="<?=$staff_birthdate?>"></td>
			</tr>
			<tr>
				<td>Телефон</td>
				<td><input type="text" form="staffDataForm" name="phone" id="phone" value="<?=$staff_phone?>" tabindex=6></td>
			</tr>
			<tr>
				<td>E-mail</td>
				<td><input type="text" form="staffDataForm" name="email" id="email" tabindex=16 value="<?=$staff_email?>"></td>
			</tr>
			<tr>
				<td>Паспорт, серия</td>
				<td><input type="text" form="staffDataForm" name="pass_s" id="pass_s" tabindex=11 value="<?=$staff_pass_s?>"></td>
			</tr>
			<tr>
				<td>Паспорт, номер</td>
				<td><input type="text" form="staffDataForm" name="pass_n" id="pass_n" tabindex=12 value="<?=$staff_pass_n?>"></td>
			</tr>
			<tr>
				<td>Паспорт, выдан</td>
				<td><input type="text" form="staffDataForm" name="pass_issued" id="pass_issued" tab="1" tabindex=13  value="<?=$staff_pass_issued?>"></td>
			</tr>
			<tr>
				<td>Паспорт, дата выдачи</td>
				<td><input type="text" form="staffDataForm" name="pass_issuedate" class="withCal" id="pass_issuedate" tabindex=14 value="<?=$staff_pass_issuedate?>"></td>
			</tr>
			<tr>
				<td>ИНН</td>
				<td><input type="text" form="staffDataForm" name="inn" id="inn" tabindex=9  value="<?=$staff_inn?>"></td>
			</tr>
			<tr>
				<td>СНИЛС</td>
				<td><input type="text" form="staffDataForm" name="snils" id="snils" tabindex=19 value="<?=$staff_snils?>"></td>
			</tr>
			<tr>
				<td>Заметки</td>
				<td>
					<textarea form="staffDataForm" name="note" id="note" rows="4" cols="7" tabindex=10><?=$staff_note?></textarea>
				</td>
			</tr>
		</table>
		<input type="hidden" form="staffDataForm" id="staffID" name="staffid" value="<?=$id;?>">
		<input type="hidden" form="staffDataForm" id="cbs"     name="cbs"     value="">
	</div>
	<div class="tab-pane" id="tab2">
		<table class="staffDataTable table table-condensed">
			<tr>
				<td class="col1">Образование</td>
				<td><input type="text" form="staffDataForm" name="edu" id="edu" tabindex=15 value="<?=$staff_edu?>"></td>
			</tr>
			<tr>
				<td>Специализация</td>
				<td><input type="text" form="staffDataForm" name="spec" id="spec" tabindex=17 value="<?=$staff_spec?>"></td>
			</tr>
			<tr>
				<td>Звание</td>
				<td>
					<select form="staffDataForm" name="rank" id="rank" tabindex=18>
					<option value="1" <?=(($staff_rank == "1") ? ' selected="selected"' : "" );?>>Стажёр</option>
					<option value="2" <?=(($staff_rank == "2") ? ' selected="selected"' : "" );?>>Инструктор</option>
					<option value="3" <?=(($staff_rank == "3") ? ' selected="selected"' : "" );?>>Другое</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>Курируемый район</td>
				<td><input type="text" form="staffDataForm" name="location" id="location" tabindex=8  value="<?=$staff_location?>"></td>
			</tr>
		</table>
		<hr>
		<h5>Назначение на оказание услуг</h5>
		<ul id="servlist"><?=$serv?></ul>
	</div>

	<div class="tab-pane" id="tab3">
		<table class="staffDataTable table table-condensed">
			<tbody>
			<tr>
				<td class="col1">&nbsp;</td>
				<td><label for="credChange"><input type="checkbox" id="credChange" style="margin-top:-2px;">&nbsp;&nbsp;Изменить учётные данные</label></td>
			</tr>
			<tr>
				<td>Пользователь</td>
				<td><input type="text" name="username" id="username" disabled placeholder="Логин для пользователя" tabindex=22 value=""></td>
			</tr>
			<tr>
				<td>Пароль</td>
				<td><input type="password" name="userpass" id="userpass" disabled placeholder="Пароль пользователя" tabindex=23></td>
			</tr>
			<tr>
				<td>Пароль</td>
				<td><input type="password" name="userpass" id="userpassR" disabled placeholder="Повторите пароль пользователя" tabindex=24></td>
			</tr>
		</tbody>
		<tbody id="masterPassTable" class="hide">
			<tr>
				<td class="col1">Пароль для шифровщика</td>
				<td>
					<input type="text" name="username" id="masterPass" tabindex=25 disabled placeholder="Мастер-пароль руководителя">
				</td>
			</tr>
		</tbody>
		</table>
	</div>
</div>
<?=$actbtn?>
<a class="btn btn-primary" id="staffSave" style="margin-left:335px;">Сохранить данные</a>
<form method="post" id="staffDataForm" action="/refs/staff_save" enctype="multipart/form-data">
</form>



<script type="text/javascript">
<!--

	$("#staffSave").click(function(){
		cbs = [];
		$(".cbs:checked").each(function(){
			cbs.push($(this).val());
		});
		$("#cbs").val(cbs.join(","));
		$("#staffDataForm").submit();
	});

	function deactivate_staff(){
		staffid = $("#staffID").val();
		$.ajax({
			url: "/refs/staff_deactivate",
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
			url: "/refs/staff_activate",
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
//-->
</script>