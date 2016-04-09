<style type="text/css">
	table.staffDataTable{
		width:700px;
	}
	table.staffDataTable td.col1{
		width:160px;
	}
	table.staffDataTable td input,
	table.staffDataTable td textarea{
		width: 460px;
		max-width: 460px;
	}
	table.staffDataTable td select{
		width: 475px;
	}
	#staffDataSave{
		margin-left: 390px;
	}
	#servlist input[type=checkbox]{
		margin:4px;
		margin-top:2px;
	}
</style>

<h4><span id="editorHeader">Добавление нового сотрудника</span></h4>

<ul class="nav nav-tabs">
	<li class="active"><a href="#tab1" id="htab1" data-toggle="tab">Сотрудник</a></li>
	<li><a href="#tab2" id="htab2" data-toggle="tab">Квалификация, услуги</a></li>
	<li><a href="#tab3" id="htab3" data-toggle="tab">Учётные данные</a></li>
</ul>
<form method="post" id="staffDataForm" action="/refs/staff_item_add" enctype="multipart/form-data">
<div class="tab-content">
	<div class="tab-pane active" id="tab1">
		<table class="staffDataTable table table-condensed">
			<tr class="col1">
				<td>Фамилия</td>
				<td><input type="text" name="staff_f" id="staff_f" tabindex=1 value="<?=$staff_f?>"></td>
			</tr>
			<tr>
				<td>Имя</td>
				<td><input type="text" name="staff_i" id="staff_i" tabindex=2 value="<?=$staff_i?>"></td>
			</tr>
			<tr>
				<td>Отчество</td>
				<td><input type="text" name="staff_o" id="staff_o" tabindex=3 value="<?=$staff_o?>"></td>
			</tr>
			<tr>
				<td>Должность</td>
				<td><input type="text" name="staff" id="staff" tabindex=4 value="<?=$staff_staff?>"></td>
			</tr>

			<tr>
				<td>Адрес</td>
				<td><input type="text" name="address" id="address" tabindex=5 value="<?=$staff_address?>"></td>
			</tr>
			<tr>
				<td>Дата рождения</td>
				<td><input type="text" name="birth" id="birth" class="withCal" tab="1" tabindex=7 value="<?=$staff_birthdate?>"></td>
			</tr>
			<tr>
				<td>Телефон</td>
				<td><input type="text" name="phone" id="phone" value="<?=$staff_phone?>" tabindex=6></td>
			</tr>
			<tr>
				<td>E-mail</td>
				<td><input type="text" name="email" id="email" tabindex=16 value="<?=$staff_email?>"></td>
			</tr>
			<tr>
				<td>Паспорт, серия</td>
				<td><input type="text" name="pass_s" id="pass_s" tabindex=11 value="<?=$staff_pass_s?>"></td>
			</tr>
			<tr>
				<td>Паспорт, номер</td>
				<td><input type="text" name="pass_n" id="pass_n" tabindex=12 value="<?=$staff_pass_n?>"></td>
			</tr>
			<tr>
				<td>Паспорт, выдан</td>
				<td><input type="text" name="pass_issued" id="pass_issued" tab="1" tabindex=13  value="<?=$staff_pass_issued?>"></td>
			</tr>
			<tr>
				<td>Паспорт, дата выдачи</td>
				<td><input type="text" name="pass_issuedate" class="withCal" id="pass_issuedate" tabindex=14 value="<?=$staff_pass_issuedate?>"></td>
			</tr>
			<tr>
				<td>ИНН</td>
				<td><input type="text" name="inn" id="inn" tabindex=9  value="<?=$staff_inn?>"></td>
			</tr>
			<tr>
				<td>СНИЛС</td>
				<td><input type="text" name="snils" id="snils" tabindex=19 value="<?=$staff_snils?>"></td>
			</tr>
			<tr>
				<td>Заметки</td>
				<td>
					<textarea name="note" id="note" rows="4" cols="7" tabindex=10><?=$staff_note?></textarea>
				</td>
			</tr>
		</table>
		<input type="hidden" id="staffID" name="staffid" value="<?=$id;?>">
		<?=$actbtn?>
		<button type="submit" class="btn btn-primary" style="margin-top:10px;margin-left:360px;">Сохранить данные</button>
	</div>
	<div class="tab-pane" id="tab2">
		<table class="staffDataTable table table-condensed">
			<tr>
				<td class="col1">Образование</td>
				<td><input type="text" name="edu" id="edu" tabindex=15 value="<?=$staff_edu?>"></td>
			</tr>
			<tr>
				<td>Специализация</td>
				<td><input type="text" name="spec" id="spec" tabindex=17 value="<?=$staff_spec?>"></td>
			</tr>
			<tr>
				<td>Звание</td>
				<td>
					<select name="rank" id="rank" tabindex=18>
					<option value="1" <?=(($staff_rank == "1") ? ' selected="selected"' : "" );?>>Стажёр</option>
					<option value="2" <?=(($staff_rank == "2") ? ' selected="selected"' : "" );?>>Инструктор</option>
					<option value="3" <?=(($staff_rank == "3") ? ' selected="selected"' : "" );?>>Другое</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>Курируемый район</td>
				<td><input type="text" name="location" id="location" tabindex=8  value="<?=$staff_location?>"></td>
			</tr>
		</table>
		<hr>
		<h5>Назначение на оказание услуг</h5>
		<ul id="servlist"><?=$serv?></ul>
		<input type="hidden" id="staffID" name="staffid" value="<?=$id;?>">
		<button type="submit" class="btn btn-primary" style="margin-top:10px;">Сохранить допуски</button>

	</div>

	<div class="tab-pane" id="tab3">
		<table class="staffDataTable table table-condensed">
			<tr>
				<td class="col1">&nbsp;</td>
				<td><label><input type="checkbox" id="credChange" style="margin-top:-2px;">&nbsp;&nbsp;Изменить учётные данные</label></td>
			</tr>
			<tr>
				<td>Пользователь</td>
				<td><input type="text" name="username" disabled id="username" tabindex=22 value="<?=$this->session->userdata("name")?>"></td>
			</tr>
			<tr>
				<td>Пароль</td>
				<td><input type="password" name="userpass" id="userpass" disabled tabindex=23></td>
			</tr>
			<tr>
				<td>Пароль</td>
				<td><input type="password" name="userpass" id="userpassR" disabled tabindex=24></td>
			</tr>
		</table>
		<hr>
		<table id="masterPassTable" class="hide">
			<tr>
				<td style="width:150px;">Пароль шифровщика</td>
				<td>
					<input type="text" name="username" id="masterPass" tabindex=25 disabled >
				</td>
			</tr>
		</table>
	</div>
</div>
		</form>
<script type="text/javascript">
<!--
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