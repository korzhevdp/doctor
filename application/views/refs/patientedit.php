<style type="text/css">
	table.patientTable1{
		width:700px;
		margin-right:10px;
	}
	table.patientTable1 td.col1{
		width:160px;
	}

	table.patientTable1 td input,
	table.patientTable1 td textarea{
		width:460px;
	}

	table.patientTable1 td select{
		width:474px;
	}

	#patDataSave{
		margin-left:445px;
	}
</style>

<h4><span id="editorHeader">Редактирование данных пациента</span>&nbsp;&nbsp;&nbsp;</h4>

<form method="post" id="patDataForm" action="/refs/pat_save" enctype="multipart/form-data" class="form-inline row-fluid">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab1" id="htab1" data-toggle="tab">Пациент</a></li>
		<li><a href="#tab2" id="htab2" data-toggle="tab">Информация для врача</a></li>
		<li><a href="#tab3" id="htab3" data-toggle="tab">Оргданные</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab1">
			<table class="patientTable1 table table-condensed">
			<tr>
				<td class="col1">Фамилия</td>
				<td><input type="text" name="pat_f" id="pat_f" tabindex=1 value="<?=$pat_f?>"></td>
			</tr>
			<tr>
				<td>Имя</td>
				<td><input type="text" name="pat_i" id="pat_i" tabindex=2 value="<?=$pat_i?>"></td>
			</tr>
			<tr>
				<td>Отчество</td>
				<td><input type="text" name="pat_o" id="pat_o" tabindex=3 value="<?=$pat_o?>"></td>
			</tr>
			<tr>
				<td title="Электронная почта сопоставленного клиента">E-mail</td>
				<td><input type="text" name="email" id="email" tabindex=4 disabled value="<?=$cli_mail?>"></td>
			</tr>
			<tr>
				<td>Телефон, моб.</td>
				<td ><input type="text" name="cphone" id="cphone" tabindex=5 title="Мобильный телефон пациента" value="<?=$pat_cphone;?>"></td>
			</tr>
			<tr>
				<td>Телефон, дом.</td>
				<td><input type="text" name="hphone" id="hphone" tabindex=6 title="Домашний телефон пациента" value="<?=$pat_hphone;?>"></td>
			</tr>
			<tr>
				<td>Адрес</td>
				<td><input type="text" name="address" id="address" tabindex=7 value="<?=$pat_address;?>"></td>
			</tr>
			<tr>
				<td>Расположение</td>
				<td><input type="text" name="location" id="location" tabindex=8 value="<?=$pat_location;?>"></td>
			</tr>
			<tr class="info">
				<td>Паспорт, серия</td>
				<td><input type="text" name="pass_s" id="pass_s" tabindex=9 value="<?=$pat_pass_s;?>"></td>
			</tr>
			<tr class="info">
				<td>Паспорт, номер</td>
				<td><input type="text" name="pass_n" id="pass_n" tabindex=10  value="<?=$pat_pass_n;?>"></td>
			</tr>
			<tr class="info">
				<td>Паспорт выдан</td>
				<td><input type="text" name="pass_issued" id="pass_issued" tabindex=11 value="<?=$pat_pass_issued;?>"></td>
			</tr>
			<tr class="info">
				<td>Паспорт, дата выдачи</td>
				<td><input type="text" name="pass_issuedate" id="pass_issuedate" tab="1" class="withCal" tabindex=12  value="<?=$pat_pass_issuedate;?>"></td>
			</tr>
			</table>
			<input type="hidden" id="patID" name="patid" value="<?=$id?>" />
		</div>
		<div class="tab-pane" id="tab2">
			<table class="patientTable1  table table-condensed">
				<tr>
					<td class="col1">Дата рождения</td>
					<td>
						<input type="text" name="birth" id="birth" tab="2" class="withCal" tabindex=4 value="<?=$pat_birthdate;?>">
					</td>
				</tr>
				<tr>
					<td>Диагноз</td>
					<td class="muted">
						Диагнозы скоро будут. Имплементация AES-256 не за горами.
					</td>
				</tr>
				<tr>
					<td>Заметки</td>
					<td>
						<textarea name="info" id="info" rows="4" cols="7" tabindex=13><?=$pat_info;?></textarea>
					</td>
				</tr>
			</table>
		</div>
		<div class="tab-pane" id="tab3">
			<table class="patientTable1  table table-condensed">
				<tr>
					<td>
						<a href="#" id="dirlink" title="Переход по ссылке на страницу поставщиков">Поставщик</a>
					</td>
					<td>
						<select name="directed" id="directed">
							<option value="0"> -- Не указан -- </option>
							<?=$supp;?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<a href="#" id="clientlink" title="Переход по ссылке на страницу клиентов">Клиент</a>
					</td>
					<td>
						<select name="clientid" id="clientid">
							<option value="0"> -- Не указан -- </option>
							<?=$cli;?>
						</select>
					</td>
				</tr>
			</table>
		</div>
	</div>
</form>


<?=$actbtn;?>
<button id="patDataSave"  class="btn btn-primary">Сохранить</button>

<script type="text/javascript">
<!--

		$("#patDataSave").click(function(){
			$("#patDataForm").submit();
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
