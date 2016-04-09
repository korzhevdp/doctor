<style type="text/css">
	table.suppDataTable{
		width:700px;
	}
	table.suppDataTable td.col1{
		width:160px;
	}
	table.suppDataTable td input,
	table.suppDataTable td textarea{
		width: 460px;
		max-width: 460px;
	}
	table.suppDataTable td select{
		width: 475px;
	}
	#suppDataSave{
		margin-left: 390px;
	}
</style>
<button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
<h4><span id="editorHeader">Редактирование данных поставщика</span>&nbsp;&nbsp;&nbsp;</h4>

<form method="post" id="suppDataForm" action="/refs/supp_item_add" enctype="multipart/form-data" class="form-inline row-fluid">
	<table class="suppDataTable table table-condensed">
	<tr>
		<td class="col1">Фамилия</td>
		<td><input type="text" name="supp_f" id="supp_f" tabindex=1 value="<?=$supp_f?>"></td>
	</tr>
	<tr>
		<td>Имя</td>
		<td><input type="text" name="supp_i" id="supp_i" tabindex=2 value="<?=$supp_i?>"></td>
	</tr>
	<tr>
		<td>Отчество</td>
		<td><input type="text" name="supp_o" id="supp_o" tabindex=3 value="<?=$supp_o?>"></td>
	</tr>
	<tr>
		<td>Должность</td>
		<td><input type="text" name="staff" id="staff" tabindex=4 value="<?=$supp_staff?>"></td>
	</tr>
	<tr>
		<td>Место работы</td>
		<td><input type="text" name="org" id="org" tabindex=5 value="<?=$supp_orgname?>"></td>
	</tr>
	<tr>
		<td>Адрес</td>
		<td><input type="text" name="address" id="address" tabindex=6 value="<?=$supp_address?>"></td>
	</tr>
	<tr>
		<td>Телефон</td>
		<td><input type="text" name="phone" id="phone"  tabindex=7 value="<?=$supp_phone?>"></td>
	</tr>
	<tr>
		<td>E-mail</td>
		<td><input type="text" name="email" id="email" tabindex=8 value="<?=$supp_email?>"></td>
	</tr>
	<tr>
		<td>Заметки</td>
		<td>
			<textarea name="note" id="note" rows="4" cols="7" tabindex=9><?=$supp_note?></textarea>
		</td>
	</tr>
	<tr>
		<td>Вознаграждение</td>
		<td>
			<div class="input-append" style="width:125px;">
				<input id="royalty" style="width:45px;" name="royalty" type="text" value="<?=$supp_royalty?>">
				<span style="line-height:20px;height:20px;" class="add-on">%</span>
			</div>
		</td>
	</tr>
	</table>
	<input type="hidden" id="suppID" name="suppid" value="<?=$sid?>" />
	<button id="suppActivator" class="btn btn-warning">Исключить</button>
	<button type="submit" class="btn btn-primary" id="suppDataSave" style="margin-left:390px;margin-top:10px;">Сохранить</button>
</form>