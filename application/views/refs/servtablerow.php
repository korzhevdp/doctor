<tr class=<?=$activeness;?>" ref="<?=$id;?>" title="<?=$title;?>">
	<td class="servrow" ref="<?=$id;?>"><?=$serv_name;?>&nbsp;&nbsp;<?=$staffneeded?><br><span class="muted">[<?=$serv_alias?>]</span></td>
	<td class="servrow" ref="<?=$id;?>"><?=$serv_desc;?></td>
	<td class="servrow" ref="<?=$id;?>"><?=$location;?></td>
	<td class="servrow" ref="<?=$id;?>"><b>Базовая цена: <?=$serv_price;?> р.</b><br>
		<span title="Расценки: стажёр"><img src="/images/trainee.png" width="16" height="16" border="0" alt=""><?=$serv_price_trainee;?> р.</span><br>
		<span title="Расценки: инструктор"><img src="/images/medic.png" width="16" height="16" border="0" alt=""><?=$serv_price_instructor;?> р.</span>
	</td>
	<td class="servrow" ref="<?=$id;?>" style="vertical-align:middle;text-align:center;width:50px;"><?=$datafull;?></td>
	<td style="vertical-align:middle;text-align:center;width:50px;">
		<a href="/refs/serv_edit/<?=$id;?>" class="btn btn-success btn-mini" title="Редактировать данные услуги"><i class="icon-edit icon-white"></i></a>
	</td>
</tr>