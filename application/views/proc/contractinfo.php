<tr <?=(($active) ? "" : 'class="warning"');?>>
	<td><a href="/contracts/show/<?=$contid?>"><strong>Контракт № <?=$cont_number?></strong></a><br>
		<small class="muted">
		дата начала: <?=$cont_date_start;?><br>
		дата окончания: <?=$cont_date_end;?>
		</small>
	</td>
	<td>
		<a href="/refs/clients#c<?=$clid?>" target="_blank"><?=$cli_fio;?></a><br>
		адрес: <?=$cli_address;?><br>
		моб.: <?=$cli_cphone;?><br>
		дом.: <?=$cli_hphone;?>
	</td>
	<td>
		<span title="<?=$pat_diagnosis?>"><a href="/refs/patients#p<?=$patid?>" target="_blank"><?=$pat_fio?></a>, <?=$pat_age?> лет</span><br>
	</td>

	<td>
		<ol><?=$services?></ol>
	</td>
	<td style="text-align:center;vertical-align:middle;cursor:pointer;" class="sheduleEdit">
		<img src="/images/barchart.png" width="32" height="32" border="0" alt="">
	</td>
	<td style="text-align:center;vertical-align:middle">
		<a class="btn btn-success btn-mini" href="/contracts/show/<?=$contid?>" title="Редактировать данные контракта"><i class="icon-edit icon-white"></i></a>
	</td>
</tr>
