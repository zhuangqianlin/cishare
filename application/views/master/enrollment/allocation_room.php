<link rel="stylesheet" href="<?=RES?>master/css/sdyinc.css" />
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header"><button type="button" class="bootbox-close-button close" data-dismiss="modal" aria-hidden="true">×</button><h4 class="modal-title">分配房间</h4></div>
						<div class="alert alert-block alert-success">
							<p>
								<strong>
									<?=$campus_name?>-<?=$building_name?>-第<?=$acc_info->floor?>层的<?=$room_name?>---可用
								</strong>
								
							</p>

							<p>
								<?php if($is_man==0):?>

								<button class="btn btn-sm btn-success" id="tijiao" onclick="allocation(<?=$acc_info->userid?>,<?=$roomid?>,<?=$id?>,<?=$campusid?>,<?=$buildingid?>,<?=$floor?>,<?=$is_tj?>)">分配</button>
								<?php else:?>
								<button class="btn btn-sm btn-success" id="tijiao" >该房间已满</button>
								<?php endif;?>
								<?php if(empty($biaoji)):?>
								<?php if(!empty($acc_info->tj)&&$acc_info->tj==1):?>
									<a class="btn btn-sm"  href="/master/enrollment/acc_apply/adjust?id=<?=$id?>">调剂</a>
								<?php endif;?>
								<a id="be" class="btn btn-sm" href="javascript:;" onclick="before(<?=$acc_info->userid?>,<?=$id?>)">预订失败</a>
								<?php endif;?>
							</p>
						</div>
				

		</div>
	</div>
</div>
<script src="<?=RES?>master/js/jquery.validate.min.js"></script>
<script type="text/javascript">
function allocation(userid,roomid,id,campusid,buildingid,floor,is_tj){
	$.ajax({
		beforeSend:function (){
					$('#tijiao').html('正在分配');
					$('#tijiao').attr({
						disabled:'disabled',
					});
				},
		url: '/master/enrollment/acc_apply/allocation_student?userid='+userid+'&roomid='+roomid+'&id='+id+'&campusid='+campusid+'&buildingid='+buildingid+'&floor='+floor+'&is_tj='+is_tj,
		type: 'GET',
		dataType: 'json',
		data: {},
	})
	.done(function(r) {
		if(r.state==1){
			window.location.href="/master/enrollment/acc_apply/index?&label_id=2";
		}
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
}
function before(userid,id){
	$.ajax({
		beforeSend:function (){
					$('#be').html('正在提交');
					$('#be').attr({
						disabled:'disabled',
					});
				},
		url: '/master/enrollment/acc_apply/before_student?userid='+userid+'&id='+id,
		type: 'GET',
		dataType: 'json',
		data: {},
	})
	.done(function(r) {
		if(r.state==1){
			window.location.href="/master/enrollment/acc_apply/index?&label_id=2";
		}
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
}
$(document).ready(function(){
	$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
					
						title: {
							required: true
						},
						desperation: {
							maxlength: 200,
						},
						state: 'required',
						
					},
			
					messages: {
						title:{
							required:"请输入标题",
						},
						desperation: {
							required: "最多输入200个字符",
							
						},
						
						state: "请选择状态",
						
					},
			
			
					highlight: function (e) {
						$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
					},
			
					success: function (e) {
						$(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
						$(e).remove();
					},
			
					errorPlacement: function (error, element) {
						if(element.is(':checkbox') || element.is(':radio')) {
							var controls = element.closest('div[class*="col-"]');
							if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
							else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
						}
						else if(element.is('.select2')) {
							error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
						}
						else if(element.is('.chosen-select')) {
							error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
						}
						else error.insertAfter(element.parent());
					},
					submitHandler: function (form) {
						
						var data=$(form).serialize();
						$.ajax({
							beforeSend:function (){
								$('#tijiao').html('<i class="ace-icon fa fa-check bigger-110"></i>正在提交');
								$('#tijiao').attr({
									disabled:'disabled',
								});
							},
							url: $(form).attr('action'),
							type: 'POST',
							dataType: 'json',
							data: data,
						})
						.done(function(r) {
							if(r.state==1){
								pub_alert_success();
								window.location.href="/master/test/test_paper/group_item";
							}
							if(r.state==2){
								$('#tijiao').html('<i class="ace-icon fa fa-check bigger-110"></i>提交');
								$('#tijiao').removeAttr('disabled');
								pub_alert_error(r.info);
							}
							
						})
						.fail(function() {
							
							pub_alert_error();
						})
						
						
					}
			
				});

});
</script>
<!--日期插件-->
