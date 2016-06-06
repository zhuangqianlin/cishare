<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li class="active">权限管理</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
         <link rel="stylesheet" href="<?=RES?>master/css/jquery-ui.min.css" />

<!-- /section:settings.box -->
<div class="page-header">
	<h1>
	  权限管理
	</h1>
</div><!-- /.page-header -->

<div class="row">
	<div class="col-sm-12">
		<div>
			<div class="table-header">
				权限组列表
				<button style="float:right;" onclick="add()" class="btn btn-primary btn-sm btn-default btn-sm" title="添加权限组" type="button">
				<span class="glyphicon  glyphicon-plus"></span>
				添加权限组
				</button>	
			</div>

			<!-- <div class="table-responsive"> -->

			<!-- <div class="dataTables_borderWrap"> -->
			<div>
				<table id="sample-table-2" class="table table-striped table-bordered table-hover dataTable-ajax group_index">
					<thead>
						<tr>
							<th class="center">
								ID
							</th>
							<th>权限组名称</th>
							<th>创建时间</th>
							<th>状态</th>
							<th>操作</th>
						</tr>
					</thead>

					<tbody role="alert" aria-live="polite" aria-relevant="all">
						
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
		</div><!-- #dialog-message -->
<!-- script -->
<!--[if lte IE 8]>
<script src="<?=RES?>/master/js/excanvas.min.js"></script>
<![endif]-->
<!-- ace scripts -->
<script src="<?=RES?>master/js/ace-extra.min.js"></script>
<script src="<?=RES?>/master/js/ace-elements.min.js"></script>
<script src="<?=RES?>/master/js/ace.min.js"></script>
<script src="<?=RES?>master/js/jquery.dataTables.min.js"></script>
<script src="<?=RES?>master/js/jquery.dataTables.bootstrap.js"></script>
<!-- delete -->
<script src="<?=RES?>master/js/jquery-ui.min.js"></script>
<script type="text/javascript">
	function add(){
		window.location.href="/master/authority/group/add";
	}
</script>
<script type="text/javascript">
	if($('#sample-table-2').length > 0){
	$('#sample-table-2').each(function(){
		var opt = {
			"iDisplayLength" : 25,
			"sPaginationType": "full_numbers",
			"oLanguage":{
				"sSearch": "<span>搜索:</span> ",
				"sInfo": "<span>_START_</span> - <span>_END_</span> 共 <span>_TOTAL_</span>",
				"sLengthMenu": "_MENU_ <span>条每页</span>",
				"oPaginate": {
					"sFirst" : "首页",
					"sLast" : "尾页",
			   		"sPrevious": " 上一页 ",
			   		"sNext":     " 下一页 "
		   		},
				"sInfoEmpty" : "没有记录",
				"sInfoFiltered" : "",
				"sZeroRecords" : '没有找到想匹配记录'
			}
		};
		opt.bStateSave = true;
		if($(this).hasClass("dataTable-ajax")){
			opt.bProcessing = true;
			opt.bServerSide = true;
			opt.sAjaxSource = "/master/authority/group/index";
		}

		if($(this).hasClass("group_index")){
			opt.bStateSave = false;
			opt.aoColumns = [

								{ "mData": "id" },
								{ "mData": "title" },
								{ "mData": "createtime" },
								{ "mData": "state" },
								{ "mData": "operation" }
							];
			opt.aaSorting = [[0,'desc']];
			opt.aoColumnDefs = [{ "bSortable": false, "aTargets": [ 4 ] }];
		}
		
		var oTable = $(this).dataTable(opt);
		if($(this).hasClass("dataTable-columnfilter")){
			oTable.columnFilter({
				"sPlaceHolder" : "head:after"
			});
		}
	});
}

function del(id){
pub_alert_confirm('/master/authority/group/del?id='+id);
}

function upstate(id,state){
pub_alert_confirm('/master/authority/group/upstate?id='+id+'&state='+state);
}
</script>
<!-- end script -->
<script type="text/javascript">
	   function showpower(id){
         	$.ajax({
         		url: '/master/authority/group/get_power?id='+id,
         		type: 'GET',
         		dataType: 'json',
         	})
         	.done(function(r) {
         		if (r.state == 1) {
         			var modal = r.data;
         			var modal = $(modal);
					modal.modal("show").on("hidden", function(){
						modal.remove();
					});

			
         		};
         	})
         		
         }
</script>

<?php $this->load->view('master/public/footer');?>