<style type="text/css">
	span.add-on{
		width: 150px;
	}
	select, input{
		margin-bottom:3px;
	}
	select{
		width:554px;
	}
	select.short{
		width:210px;
	}
	input.short{
		width:220px;
	}
	#rub{
		width:100px;
	}
	#kop{
		width:20px;
	}
	div.modal-body span.col1{
		width:150px;
	}
	#payData{
		width: 500px;
	}
</style>

<h3>Получение оплаты за услуги</h3>

<form method="post" action="payments" class="form-horizontal">
	<div class="input-prepend">
		<span class="add-on">Выберите клиента</span>
		<select id="client_id" name="client_id" style="width:320px;">
			<?=$clients;?>
		</select>
	</div>
	<button type="submit" class="btn btn-primary">Вывести информацию по платежам</button>
</form>


<h5><?=$client_fio?></h5>
<hr>
<div class="well well-small" style="width:300px;margin-top:20px;">
	<p><strong>Баланс</strong><br>
	Депозит: <strong><?=$deposit;?> р.</strong><br>
	Оказано услуг на: <strong><?=$paid;?> р.</strong><br>
	Остаток средств: <strong><?=$rest;?> р.</strong>
	</p>
</div>

<h4>Платежи</h4>
<table class="table table-bordered table-condensed table-striped" style="margin-top:10px;">
	<?=$payments;?>
</table>

<h4>Списания за услуги</h4>
<table class="table table-bordered table-condensed table-striped" style="margin-top:10px;">
<?=$servlist;?>
</table>

<div class="modal hide" id="payData">
	<form method="post" action="/payments/get_payment">
		<div class="modal-header" style="cursor:move;background-color: #d6d6d6">
			<button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
			<h4>Вносится платёж</h4>
		</div>
		<div class="modal-body" style="height:240px;">
			<div class="input-prepend input-append">
				<span class="add-on col1" for="sum">Сумма платежа</span>
				<input type="text" id="rub" name="rub" style="width:100px;" placeholder="Рубли">
				<span class="add-on">р.</span>
				<input type="text" id="kop" name="kop" style="width:20px;" placeholder="00" maxlength=2 title="Копейки вводить необязательно">
				<span class="add-on">коп.</span>
			</div>
			<div class="input-prepend">
				<span class="add-on col1" title="Необязательно">Целевая услуга</span>
				<select name="service" class="short">
					<option value="0" selected>Платёж не целевой</option>
					<?=$services;?>
				</select>
			</div>
			<div class="input-prepend">
				<span class="add-on col1">Форма оплаты</span>
				<label class="radio inline">
					<input type="radio" name="dtype" id="bso" value="bso" checked="checked"> БСО
				</label>
				<label class="radio inline" for="check">
					<input type="radio" name="dtype" id="check" value="check"> Чек
				</label>
				<label class="radio inline" for="term">
					<input type="radio" name="dtype" id="term" value="term"> Терминал
				</label>
			</div>
			<div class="input-prepend">
				<span class="add-on col1">Номер чека / БСО</span>
				<input type="text" name="dnum" class="short">
			</div>
		</div>
		<div class="modal-footer">
			<button type="submit" class="btn btn-primary">Внести платёж</button>
			<input type="hidden" name="contract_id" id="contract_id">
			<input type="hidden" name="client_id" value="<?=$client_id?>">
		</div>
	</form>
</div>


<script type="text/javascript">
<!--
	$(".modal").modal({show: 0});
	$(".p_add").click(function(){
		$("#payInfo").html($(this).attr('title'));
		$("#contract_id").val($(this).attr('ref'))
		$("#payData").modal("show");
	});
	$(".p_del").click(function(){
		alert($(this).attr('ref'));
		$.ajax({
			url: "/payments/off_payment",
			type: "POST",
			data: {
				id: $(this).attr('ref'),
			},
			dataType: 'text',
			success: function(data){
				window.location.reload();
			},
			error: function(data,stat,err){
				alert([data,stat,err].join("\n"));
			}
		});

	})

//-->
</script>