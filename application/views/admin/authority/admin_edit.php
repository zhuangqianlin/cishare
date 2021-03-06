<?php
$uri4 = $this->uri->segment(4);
$title_h3 = $uri4 == 'edit' ? '修改' : '添加';
$r=!empty($info)?'编辑管理员':'添加管理员';
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="javascript:;">权限管理</a>
	</li>
	<li>
		<a href="javascript:;">账号管理</a>
	</li>
	<li><a href="index">管理员账号管理</a></li>
	<li>{$r}</li>
</ul>
EOD;
?>
<?php $this->load->view('admin/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
 <script src="<?=RES?>admin/js/jquery.validate.min.js"></script>
		
<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		管理员账号管理
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-sm-12">
		<div>
			<!-- #section:plugins/fuelux.wizard.container -->
			<div class="step-content pos-rel" id="step-container">
				<div class="step-pane active" id="step1">
					<h3 class="lighter block green"><?=!empty($info)?'编辑管理员':'添加管理员'?>
					<a href="javascript:history.back();" title='返回上一级' class="pull-right ">
						<i class="ace-icon fa fa-reply light-green bigger-130"></i>
					</a>
					</h3>

					<form class="form-horizontal" id="validation-form" method="post" action="/admin/authority/admin/save" enctype = 'multipart/form-data'>
						
						

						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">用户名:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" id="username" name="username" value="<?=!empty($info->username) ? $info->username : ''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						<div class="space-2"></div>

						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">密码:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="password" id="password" name="password" value="" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						<div class="space-2"></div>
						<hr />
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">姓名:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" id="nikename" name="nikename" value="<?=!empty($info->nikename) ? $info->nikename : ''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						<div class="space-2"></div>
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">邮箱:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" id="email" name="email" value="<?=!empty($info->email) ? $info->email : ''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						<div class="space-2"></div>
						


						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">电话:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" id="tel" name="tel" value="<?=!empty($info->tel) ? $info->tel : ''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						<div class="space-2"></div>

						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">手机:</label>

							<div class="col-xs-12 col-sm-9">
								<div class="clearfix">
									<input type="text" id="mobile" name="mobile" value="<?=!empty($info->mobile) ? $info->mobile : ''?>" class="col-xs-12 col-sm-5" />
								</div>
							</div>
						</div>

						<div class="space-2"></div>

						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">所属权限组:</label>

							<div class="col-xs-12 col-sm-4">
								<select id="groupid" class="form-control" name="groupid">
									<option value="">--请选择--</option>
									<?php if(!empty($group)){
										foreach ($group as $key => $value) {
									?>
										<option value="<?=$key?>" <?=!empty($info) && $info->groupid== $key?'selected':''?>><?=$value?></option>
									<?php }}?>
									
									
								</select>
							</div>
						</div>

						<div class="space-2"></div>
						
						<div class="form-group">
							<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="name">状态:</label>
							
							<div class="col-xs-12 col-sm-4">
								<select id="state" class="form-control" name="state">
									<option value="1" <?=!empty($info) && $info->state == 1?'selected':''?>>启用</option>
									<option value="0"  <?=!empty($info) && $info->state == 0?'selected':''?>>禁用</option>
									
								</select>
							</div>
						</div>

						<div class="space-2"></div>

						<input type="hidden" name="id"  value="<?=!empty($info->id)?$info->id:''?>">
						<input type="hidden" name="oldpass" value="<?=!empty($info) ? $info->password : ''?>">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-info" data-last="Finish">
								<i class="ace-icon fa fa-check bigger-110"></i>
									提交
							</button>
							<button class="btn" type="reset">
								<i class="ace-icon fa fa-undo bigger-110"></i>
									重置
							</button>
						</div>
						
						
						</form>
				</div>
			</div>

			<!-- /section:plugins/fuelux.wizard.container -->
		</div>
	</div>
</div>
<!--[if lte IE 8]>
<script src="<?=RES?>/admin/js/excanvas.min.js"></script>
<![endif]-->
<!-- ace scripts -->
<script src="<?=RES?>admin/js/ace-extra.min.js"></script>
<script src="<?=RES?>/admin/js/ace-elements.min.js"></script>
<script src="<?=RES?>/admin/js/ace.min.js"></script>
<!-- script -->
<script src="<?=RES?>admin/js/bootstrap-wysiwyg.min.js"></script>
<!--upload picture-->
<script src="<?=RES?>admin/js/ace-elements.min.js"></script>
<!-- upload picture -->



<script type="text/javascript">
$(document).ready(function(){
	$('#validation-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
						nikename: {
							required: true
						},
						username: {
							required: true,
							remote:'/admin/authority/admin/checkusername?id=<?=!empty($info->id)?$info->id:''?>'
						},
						email: {
							required: true,
							remote:'/admin/authority/admin/checkemail?id=<?=!empty($info->id)?$info->id:''?>'
						},
						mobile: {
							required: true
						},
						groupid: {
							required: true
						},
						
						state: 'required',
						
					},
			
					messages: {
						title:{
							required:"请输入群组名称",
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
						var host=window.location.host;
						
						$.ajax({
							url: $(form).attr('action'),
							type: 'POST',
							dataType: 'json',
							data: data,
						})
						.done(function(r) {
							if(r.state==1){
								pub_alert_success()
								//setTimeout(window.location.reload(),3000);
							}else{

								pub_alert_error();
							}
							
						})
						.fail(function() {

							pub_alert_error();
						})
						
						
					}
			
				});

	return false;
});
</script>

<?php $this->load->view('admin/public/footer');?>


