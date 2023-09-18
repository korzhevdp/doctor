<h3>Курс лечения&nbsp;&nbsp;&nbsp;<small><a href="<?=base_url();?>refs/patients#p<?=$pid;?>" title="Карточка пациента" target="_blank"><?=$pat_fio?></a></small></h3>
<form method="post" action="<?=base_url();?>courses/item_saveinfo">
<style type="text/css">
	.crsInfo td{
		vertical-align: middle;
		line-height: 20px;
	}
	.crsInfo td input, .crsInfo td select{
		margin: 3px;
	}
</style>

<table class="crsInfo table table-bordered table-condensed table-striped">
<tr>
	<td>Пациент</td>
	<td><?=$pat_fio?></td>
</tr>
<tr>
	<td>Начало курса</td>
	<td><input type="text" name="startdate" value="<?=$startdate;?>" readonly></td>
</tr>
<tr>
	<td>Окончание курса</td>
	<td><input type="text" name="enddate" value="<?=$enddate;?>" readonly></td>
</tr>
</table>
</form>
<h3>Сведения о контрактах</h3>
<table class="table table-bordered table-condensed table-striped">
<tr>
	<th style="width:120px;">Номер</th>
	<th style="width:200px;">Клиент</th>
	<th>Дата контракта</th>
</tr>
<?=$contracts;?>
</table>

<h3>Назначенные услуги</h3>
<?=$finances;?>