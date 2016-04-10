<h3>Курсы лечения&nbsp;&nbsp;&nbsp;<small>текущие и история</small></h3>

<ul class="nav nav-tabs">
	<li class="active"><a href="#tab1" id="htab1" data-toggle="tab">Текущие курсы лечения</a></li>
	<li><a href="#tab2" id="htab2" data-toggle="tab">Закрытые курсы лечения</a></li>
</ul>
<div class="tab-content">
	<div class="tab-pane active" id="tab1">
		<table class="table table-condensed table-bordered table-striped table-hover">
			<tr>
			<td>Пациент / клиент</td>
			<td>Начало курса</td>
			<td>Контракты</td>
			<td>Ред.</td>
			</tr>
		<?=$table?>
		</table>
	</div>
	<div class="tab-pane" id="tab2">
		<table class="table table-condensed table-bordered table-striped table-hover">
			<tr>
			<td>Пациент / клиент</td>
			<td>Начало курса</td>
			<td>Контракты</td>
			<td>Ред.</td>
			</tr>
		<?=$inactivetable?>
		</table>
	</div>
</div>