<style type="text/css">
	table.servDataTable,
	table.servDataTable2 {
		width:700px;
	}
	table.servDataTable td.col1,
	table.servDataTable2 td.col1 {
		width:150px;
	}
	table.servDataTable td input,
	table.servDataTable td textarea{
		width:460px;
	}
	table.servDataTable td select{
		width:474px;
	}
	table.servDataTable2 ul{
		list-style-type:none;
	}
	table.servDataTable2 input[type=checkbox]{
		margin-top:-3px;
		margin-right:10px;
	}
</style>

<h4>Редактирование данных услуги&nbsp;&nbsp;&nbsp;<small><?=$serv_name;?></small></h4><hr>

<div class="alert alert-error<?=(($active) ? " hide": "");?>" style="max-width:580px;" >
	У данной услуги отсутствуют квалифицированные исполнители. Установите в разделе <strong>Допущенный персонал</strong> допуски на оказание этой услуги.</div>

<div class="alert alert-error<?=(($m_active) ? " hide": "");?>" style="max-width:580px;">
	В данный момент услуга помечена не оказываемой.
</div>

<form method="post" id="servDataForm" action="/refs/serv_save" enctype="multipart/form-data" class="form-inline row-fluid">
<ul class="nav nav-tabs">
	<li class="active"><a href="#tab1" id="htab1" data-toggle="tab">Данные услуги</a></li>
	<li><a href="#tab2" id="htab2" data-toggle="tab">Допущенный персонал</a></li>
</ul>
<div class="tab-content">
	<div class="tab-pane active" id="tab1">
		<table class="servDataTable table table-condensed">
			<tr>
				<td class="col1">Название</td>
				<td><input type="text" name="name" id="name" tabindex=1 value="<?=$serv_name;?>"></td>
			</tr>
			<tr>
				<td>Сокращение</td>
				<td><input type="text" name="alias" id="alias" tabindex=2 value="<?=$serv_alias;?>"></td>
			</tr>
			<tr>
				<td>Место оказания</td>
				<td>
					<select name="location" id="location" tabindex=4>
						<?=$loc;?>
					</select>
				</td>
			</tr>
			<tr>
				<td></td>
				<td><h5>Цена</h5></td>
			</tr>
			<tr>
				<td>Общая</td>
				<td>
					<div class="input-append input-prepend" title="Копейки указывать не обязательно">
						<input type="text" name="price" id="price" tabindex=5 maxlength=7 value="<?=$serv_price;?>" style="width:75px;">
						<span class="add-on" style="line-height:18px;height:20px;">p.</span>
						<input type="text" name="price_k" id="price_k" tabindex=6 maxlength=2 style="width:20px;" placeholder="00">
						<span class="add-on" style="line-height:18px;height:20px;">коп.</span>
					</div>
				</td>
			</tr>
			<tr>
				<td>Стажёр</td>
				<td>
					<div class="input-append input-prepend" title="Копейки указывать не обязательно">
						<input type="text" name="price_t" id="price_t" tabindex=7 maxlength=7 value="<?=$serv_price_trainee;?>" style="width:75px;">
						<span class="add-on" style="line-height:18px;height:20px;">p.</span>
						<input type="text" name="price_tk" id="price_tk" tabindex=8 maxlength=2 style="width:20px;" placeholder="00">
						<span class="add-on" style="line-height:18px;height:20px;">коп.</span>
					</div>
				</td>
			</tr>
			<tr>
				<td>Инструктор</td>
				<td>
					<div class="input-append input-prepend" title="Копейки указывать не обязательно">
						<input type="text" name="price_i" id="price_i" tabindex=9 maxlength=7 value="<?=$serv_price_instructor;?>" style="width:75px;">
						<span class="add-on" style="line-height:16px;height:20px;">p.</span>
						<input type="text" name="price_ik" id="price_ik" tabindex=10 maxlength=2 style="width:20px;" placeholder="00">
						<span class="add-on" style="line-height:16px;height:20px;">коп.</span>

					</div>
				</td>
			</tr>
			<tr>
				<td>Описание</td>
				<td colspan=3>
					<textarea name="info" id="info" rows="4" cols="7" tabindex=11><?=$serv_desc?></textarea>
				</td>
			</tr>
		</table>
	</div>
	<div class="tab-pane" id="tab2">
		<table class="servDataTable2 table table-condensed">
			<tr>
				<td>
				<h4><?=$serv_name;?></h4>
				<ul><?=$ordering;?></ul>
				
				</td>
			</tr>
		</table>
	</div>
	<input type="hidden" id="servID" name="servid"  value="<?=$id;?>" />
</form>

<?=$actbtn;?>
<button id="servDataSave" class="btn btn-primary" title="Сохранить данные об услуге" style="margin-left:426px;">Сохранить</button>
<script type="text/javascript">
<!--
	$("#servDataSave").click(function(){
		$("#servDataForm").submit();
	});
//-->
</script>