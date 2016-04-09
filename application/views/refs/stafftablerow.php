<tr class="staffrow<?=$class;?>" ref="<?=$id;?>" title="<?=$title;?>">
	<td><a name="sta<?=$id;?>"></a><?=$fio;?><div class="pull-right"><?=$ico;?></div><br><small class="muted"><?=$staff_staff;?></small></td>
	<td><?=$staff_edu;?><br>мед.&nbsp;специализация: <b><?=((strlen($staff_spec)) ? $staff_spec : "нет");?></b></td>
	<td><?=$staff_address;?><br><abbr title="Курируемый район">КР:</abbr>&nbsp;<?=$staff_location;?></td>
	<td><?=$staff_phone;?><br><?=$staff_email;?></td>
	<td style="vertical-align:middle;text-align:center;width:50px;"><a class="btn btn-success btn-mini" href="/refs/staff_edit/<?=$id;?>" title="Редактировать данные"><i class="icon-edit icon-white"></i></a></td>
</tr>