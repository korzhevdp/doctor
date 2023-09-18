<style type="text/css">
	table.cliDataTable{
		width:700px;
	}
	table.cliDataTable td.col1{
		width:160px;
	}
	table.cliDataTable td input,
	table.cliDataTable td textarea{
		width: 460px;
		max-width: 460px;
		margin-bottom: 0px;
	}
	table.cliDataTable td select{
		width: 475px;
	}
	#clientDataSave{
		margin-left: 390px;
		margin-bottom: 60px;
	}
</style>
<h4>Добавление клиента&nbsp;&nbsp;&nbsp;<small id="client_fio"></small></h4><hr>

<table class="cliDataTable table table-condensed">
	<tr>
		<td class="col1">Фамилия</td>
		<td><input type="text" name="client_f" id="client_f" form="clientDataForm" tabindex=1 value="<?=$cli_f;?>"></td>
	</tr>
	<tr>
		<td>Имя</td>
		<td><input type="text" name="client_i" id="client_i" form="clientDataForm" tabindex=2 value="<?=$cli_i;?>"></td>
	</tr>
	<tr>
		<td>Отчество</td>
		<td><input type="text" name="client_o" id="client_o" form="clientDataForm" tabindex=3 value="<?=$cli_o;?>"></td>
	</tr>
	<tr>
		<td>Скидочная карта</td>
		<td><input type="text" name="card" id="card" form="clientDataForm" tabindex=4 value="<?=$cli_card;?>"></td>
	</tr>
	<tr>
		<td>Адрес</td>
		<td><input type="text" name="address" id="address" form="clientDataForm" tabindex=5 value="<?=$cli_address;?>"></td>
	</tr>
	<tr>
		<td>Телефон, моб.</td>
		<td><input type="text" name="cphone" id="cphone" form="clientDataForm" tabindex=6 value="<?=$cli_cphone;?>"></td>
	</tr>
		<tr>
		<td>Телефон, дом.</td>
		<td><input type="text" name="hphone" id="hphone" form="clientDataForm" tabindex=7 value="<?=$cli_hphone;?>"></td>
	</tr>
	<tr>
		<td>E-mail</td>
		<td><input type="text" name="email" id="email" form="clientDataForm" tabindex=8 value="<?=$cli_mail;?>"></td>
	</tr>
	<tr class="info">
		<td>Паспорт, серия</td>
		<td><input type="text" name="pass_s" id="pass_s" form="clientDataForm" tabindex=9 value="<?=$cli_pass_s;?>"></td>
	</tr>
	<tr class="info">
		<td>Паспорт, номер</td>
		<td><input type="text" name="pass_n" id="pass_n" form="clientDataForm" tabindex=10 value="<?=$cli_pass_n;?>"></td>
	</tr>
	<tr class="info">
		<td>Паспорт выдан</td>
		<td><input type="text" name="pass_issued" id="pass_issued" form="clientDataForm" tabindex=11 value="<?=$cli_pass_issued;?>"></td>
	</tr>
	<tr class="info">
		<td>Паспорт, дата выдачи</td>
		<td><input type="text" name="pass_issuedate" id="pass_issuedate" tab="1" class="withCal" form="clientDataForm" tabindex=12 value="<?=$cli_pass_issuedate;?>"></td>
	</tr>
	<tr>
		<td>Заметки</td>
		<td>
			<textarea name="note" id="note" rows="4" cols="7" form="clientDataForm" tabindex=13><?=$cli_note;?></textarea>
		</td>
	</tr>
</table>
<input type="hidden" form="clientDataForm" id="clientID" name="clientid" value="<?=$id?>" />

<form method="post" id="clientDataForm" action="<?=base_url();?>refs/client_item_add" enctype="multipart/form-data" class="form-inline row-fluid">
</form>
<?=$actbtn?>
<button type="submit" class="btn btn-primary" id="clientDataSave">Сохранить</button>

<script type="text/javascript">
	<!--
		
		$("#clientDataSave").click(function(){
			var good = 1;
			$(".withCal").css('color', 'black');
			$(".withCal").each(function(){
				var ertab = $(this).attr('tab');
				if ( /\d\d\.\d\d.\d\d\d\d/.test($(this).val()) ) {
					//alert("вроде порядок");
				}else{
					good = 0;
				}
			});
			if(!good){
				alert("Неверный формат даты");
				$(".withCal").css('color', 'red');
				return false;
			}else{
				$("#clientDataForm").submit();
			}
		});

		$("#client_f, #client_i, #client_o").unbind().keyup(function(){
			$("#client_fio").html( [ $("#client_f").val().trim(), $("#client_i").val().trim(), $("#client_o").val().trim() ].join(" ") );
		});

		$("#client_f").keyup();

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