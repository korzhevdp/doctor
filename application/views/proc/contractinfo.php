<tr <?=(($active) ? "" : 'class="warning"');?>>
	<td>
		<a href="<?=base_url();?>contracts/show/<?=$contractID?>"><strong>Контракт № <?=$cont_number?></strong></a><br>
		<small class="muted">
		дата начала: <?=$cont_date_start;?><br>
		дата окончания: <?=$cont_date_end;?>
		</small>
	</td>
	<td>
		<a href="<?=base_url();?>refs/client_edit/<?=$clid?>" target="_blank"><?=$cli_fio;?></a><br>
		адрес: <?=$cli_address;?><br>
		моб.: <?=$cli_cphone;?><br>
		дом.: <?=$cli_hphone;?>
	</td>
	<td>
		<span title="<?=$pat_diagnosis;?>">
			<a href="<?=base_url();?>refs/pat_edit/<?=$patid;?>" target="_blank"><?=$pat_fio;?></a>, <?=$pat_age;?> <?=($pat_age > 200) ? "г.р." : "лет";?>
		</span>
	</td>

	<td>
		<ol><?=$services?></ol>
	</td>
	<td style="text-align:center;vertical-align:middle;cursor:pointer;" class="sheduleEdit">
		<img src="<?=base_url();?>images/barchart.png" width="32" height="32" border="0" alt="">
	</td>
</tr>
