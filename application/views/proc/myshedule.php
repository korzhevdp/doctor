<style type="text/css">
	.servData{
		cursor:pointer;
	}
</style>
<h3>План работ на ближайшие 10 дней</h3>
<table class="table table-bordered">
<?=$content;?>
</table>

<div class="modal hide" id="calInfo">
	<div class="modal-header">
		<h4>Информация о процедурах и услугах</h4>
	</div>
	<div class="modal-body">
		<div id="patInfoText"></div>
		<center style="margin-top:15px;">
			<button class="btn btn-large btn-warning" data-dismiss="modal" aria-hidden="true" id="jobDone" style="margin-right:75px;">Исполнено</button>
			<button class="btn btn-large" data-dismiss="modal" aria-hidden="true">Закрыть</button>
		</center>
	</div>
</div>

<div class="modal hide" id="jobInfo">
	<div class="modal-header">
		<h4>Информация об исполнении</h4>
	</div>
	<div class="modal-body">
		<div id="jobInfoText"></div>
		<form method="post" action="/shedule/jobdone" id="jobform">
			<h5>Комментарий, замечания</h5>
			<textarea name="comment" style="width:96%; height:100px;" rows="5" cols="6"></textarea>
			<input type="hidden" name="calId" id="calId">
		</form>
		<center style="margin-top:15px;">
			<button class="btn btn-large btn-primary" id="jobDoneF" style="margin-right:75px;">Отправить</button>
			<button class="btn btn-large" data-dismiss="modal" aria-hidden="true">Закрыть</button>
		</center>
	</div>
</div>

<script type="text/javascript">
<!--
	$(".modal").modal({show: 0});

	$(".servData").click(function(){
		text = $(this).attr("title").split("\n");
		cal = $(this).attr("ref");
		time = $(this).html();
		if($(this).attr("fin") == "1"){
			$("#jobDoneF, #jobDone").addClass("hide");
		}else{
			$("#jobDoneF, #jobDone").removeClass("hide");
		}
		$("#patInfoText, #jobInfoText").empty().html('<h4>' + text[0] + '</h4><span style="font-size:14px;">' + text[1] + '<br>' + text[2] + '</span><center><u><h2 style="color:#777">' + time + '</h2></u></center>');
		$("#calInfo").modal("show");
		$("#calId").val(cal);
		$("#jobDone").unbind('click').click(function(){
			$("#calInfo").modal("hide").then($("#jobInfo").modal("show"));
		});
	});
	
	$("#jobDoneF").click(function(){
		$("#jobform").submit();
	})
//-->
</script>