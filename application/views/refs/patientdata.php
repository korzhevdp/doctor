<tr class="<?=$is_blocked;?>">
	<td class="patrow" ref="<?=$id;?>" title="Щелчок покажет карточку пациента">
		<h6><?=$fio;?></h6>
		Клиент: <a href="<?=base_url();?>refs/client_edit/<?=$client_id;?>" target="_blank" title="Щелчок покажет карточку клиента"><?=$cli_fio;?></a>
	</td>
	<td class="patrow" ref="<?=$id;?>" title="Щелчок покажет карточку пациента">
		<?=$pat_address;?><br>
		<small><?=$pat_location;?></small>
	</td>
	<td>
		моб: <?=$cli_cphone;?><br>
		дом: <?=$cli_hphone;?><br>
		email: <?=$cli_mail;?>
	</td>
	<td style="vertical-align:middle;text-align:center;width:150px;" class="supprow" ref="<?=((strlen($supp_id)) ? $supp_id : 0);?>"  title="Щелчок покажет карточку поставщика">
		<a href="<?=base_url();?>refs/editSupplier/<?=$supp_id;?>" target="_blank"><?=$supplier;?></a>
	</td>
	<td style="vertical-align:middle;text-align:center;width:50px;">
		<?=$errors;?>
	</td>
	<td style="vertical-align:middle; text-align:center;">
		<!-- <button class="btn btn-success btn-mini patEdit" ref="<?=$id;?>" title="Редактировать данные пациента"><i class="icon-edit icon-white"></i></button> -->
		<a class="btn btn-info btn-mini " href="<?=base_url();?>refs/editPatient/<?=$id;?>" title="Редактировать данные пациента"><i class="icon-edit icon-white"></i></a>
	</td>
</tr>