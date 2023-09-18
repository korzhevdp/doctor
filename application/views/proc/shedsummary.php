<h3>Финансовая статистика</h3>
<h5>Оказанные в этом месяце услуги</h5>
<table class="table table-bordered table-condensed">
	<tr>
		<th style="width:60%;">Название</th><th style="width:10%;">Количество</th>
		<th style="width:30%;">Финансовый результат, руб.</th>
	</tr>
	<?=$sdone;?>
	<tr>
		<td colspan=2 style="font-weight:bold;font-size:12pt;">ИТОГО</td>
		<td style="font-weight:bold;font-size:12pt;"><?=$done;?></td>
	</tr>
</table>

<h5>Назначенные в этом месяце услуги</h5>
<table class="table table-bordered table-condensed">
	<tr>
		<th style="width:60%;">Название</th>
		<th style="width:10%;">Количество</th>
		<th style="width:30%;">Финансовый результат, руб.</th>
	</tr>
	<?=$sndone;?>
	<tr>
		<td colspan=2 style="font-weight:bold;font-size:12pt;">ИТОГО</td>
		<td style="font-weight:bold;font-size:12pt;"><?=$forw;?></td>
	</tr>
</table>

<h5>Назначенные, но не оказанные в этом месяце услуги</h5>
<table class="table table-bordered table-condensed">
	<tr>
		<th style="width:60%;">Название</th>
		<th style="width:10%;">Количество</th>
		<th style="width:30%;">Финансовый результат, руб.</th>
	</tr>
	<?=$missed;?>
	<tr>
		<td colspan=2 style="font-weight:bold;font-size:12pt;">ИТОГО</td>
		<td style="font-weight:bold;font-size:12pt;"><?=$back;?></td>
	</tr>
</table>
