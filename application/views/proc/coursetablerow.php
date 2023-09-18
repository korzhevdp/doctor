<tr class="<?=$status?>">
<td>
	<a href="<?=base_url();?>refs/pat_edit/<?=$patid;?>" target="_blank">пациент: <?=$pat_fio?></a><br>
	<a href="<?=base_url();?>refs/client_edit/<?=$clid;?>" class="muted" target="_blank">клиент: <?=$cli_fio;?></a>
</td>
<td style="text-align:center;vertical-align:middle;width:95px;"><?=$startdate;?></td>
<td style="vertical-align:middle;width:175px;"><ul><?=$conts;?></ul></td>
<td style="text-align:center;vertical-align:middle;width:75px;">
	<a href="<?=base_url();?>courses/item/<?=$id;?>" class="btn btn-info btn-mini" title="Редактировать данные">Редактировать</a>
</td>
</tr>