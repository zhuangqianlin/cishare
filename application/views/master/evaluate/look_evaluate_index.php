<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>
<li>
		<a href="javascript:;">教务管理</a>
	</li>
	<li>
		<a href="javascript:;">评教管理</a>
	</li>
	<li class="active">查看评教</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		评教管理
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->
		<div class="col-xs-12 col-sm-9">
			<!-- #section:plugins/fuelux.wizard.container -->
				<div class="step-content pos-rel" id="step-container">
					<div class="step-pane active" id="step1">

						<div class="widget-box">
							<div class="widget-header">
								<h4 class="widget-title">按条件筛选</h4>
							</div>
							<div class="widget-body">
								<div class="widget-main">
									<form class="form-inline" id="condition">
										<label class="control-label" for="platform">关键词:</label>
										<select id='where' onchange="select_change()" aria-required="true" aria-invalid="false">
											<option value="0">—请选择—</option>
											<option value="teacherid">按老师查看</option>
											<option value="squadid">按班级查看</option>
											<option value="majorid">按专业查看</option>
											<option value="courseid">按课程查看</option>
										</select>

										
										<a class="btn btn-info btn-sm" type="button" onclick="student_quick()">
											确认条件
										</a>
									</form>
								</div>
							</div>
						</div  transparent collapsed>
						<div id="tables-3" class="widget-box">
							<div class="widget-body" id="insert">
									
							</div>
						</div>
					</div>
				</div>
		</div>
	</div>
</div>
		
<!-- script -->
<!--[if lte IE 8]>
<script src="<?=RES?>/master/js/excanvas.min.js"></script>
<![endif]-->
<!-- ace scripts -->
<script src="<?=RES?>master/js/ace-extra.min.js"></script>
<script src="<?=RES?>/master/js/ace-elements.min.js"></script>
<script src="<?=RES?>/master/js/ace.min.js"></script>
<script type="text/javascript">
function select_change(){
	var value=$('#where').val();
	$.ajax({
		url: '/master/evaluate/look_evaluate/get_where_info?where='+value,
		type: 'POST',
		dataType: 'json',
		data: {},
	})
	.done(function(r) {
		if(r.state==1){
			$('#grf').remove();
			var str='<select id="grf" name="values" aria-required="true" aria-invalid="false">';
			$.each(r.data, function(k, v) {
				 str+= '<option value="'+v.id+'">'+v.name+'</option>';
			});
			str+='</select>';
			$('#where').after(str);
		}
	})
	.fail(function() {
		console.log("error");
	})
	
}
</script>
<!-- end script -->
<?php $this->load->view('master/public/footer');?>