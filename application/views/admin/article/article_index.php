<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="#">后台</a>
	</li>
	<li class="active">文章管理</li>
</ul>
EOD;
?>		
<?php $this->load->view('admin/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<link rel="stylesheet" href="<?=RES?>admin/css/jquery.dataTables.css">
	<link rel="stylesheet" href="<?=RES?>admin/css/jquery-ui.min.css" />


<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		文章管理
	</h1>
</div><!-- /.page-header -->


<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->

		<div class="table-responsive">

			 <div class="dataTables_borderWrap"> 
				<div> 
				<div class="table-header">
			文章管理
			<a class="btn btn-primary btn-sm btn-default btn-sm" title="添加" type="button" href="/admin/article/article/add?type=1" style="float:right;">
					<span class="glyphicon  glyphicon-plus"></span>
					添加文章
			</a>
					<a class="btn btn-primary btn-sm btn-default btn-sm" title="添加" type="button" href="/admin/article/article/add?type=2" style="float:right;">
						<span class="glyphicon  glyphicon-plus"></span>
						添加专题文章
					</a>
<a type="button" title="返回上一级" class="btn btn-primary btn-sm btn-default btn-sm" href="javascript:history.back();" style="float:right;">
					<span class="ace-icon fa fa-reply"></span>
					返回上一级
				</a>			
			</div>
					<table id="sample-table-2" class="table table-striped table-bordered table-hover dataTable-ajax basic_major">
						<thead>
							<tr>
								<th class="center" width="50">
									ID
								</th>
								<th  width="400">标题</th>
								<th  width="100">是否显示</th>
								<th width="100">操作</th>
								
							</tr>
						</thead>
						<thead>
						<tr>
							<th>
                                <input type="text" id="art_id" placeholder="ID" style="width:50px;">
                            </th>
							<th>
                                 <input type="text" id="art_title" placeholder="标题" style="width:300px;">
                            </th>
							<th>
                                <select id="art_show" style="width:100px;">
								<option value="">-是否显示-</option>
								<option value="1">显示</option>
								<option value="2">隐藏</option>
								</select>
                            </th>
							<th></th>
						</tr>
						</thead>

						<tbody>
							
						</tbody>
					</table>

				</div>
			</div>
		</div>
	</div>
</div>

	<!-- script -->
	<!--[if lte IE 8]>
	<script src="<?=RES?>/admin/js/excanvas.min.js"></script>
	<![endif]-->
	<!-- ace scripts -->
	<script src="<?=RES?>admin/js/ace-extra.min.js"></script>
	<script src="<?=RES?>/admin/js/ace-elements.min.js"></script>
	<script src="<?=RES?>/admin/js/ace.min.js"></script>
	<script src="<?=RES?>admin/js/jquery.dataTables.min.js"></script>
	<script src="<?=RES?>admin/js/jquery.dataTables.bootstrap.js"></script>
	<!-- delete -->
	<script src="<?=RES?>admin/js/jquery-ui.min.js"></script>
	<link rel="stylesheet" href="<?=RES?>admin/css/ace.onpage-help.css" />
	<script src="<?=RES?>admin/js/x-editable/bootstrap-editable.min.js"></script>

<!-- delete -->
<script type="text/javascript">
	function del(id){
		pub_alert_confirm('/admin/article/article/del?id='+id);
	}
</script>
<script type="text/javascript">
	function edit_show(id,show){
		pub_alert_confirm('/admin/article/article/edit_show?pk='+id+'&value='+show);
	}
</script>
<script type="text/javascript">
$(function(){
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

		opt.bAutoWidth=true;
		opt.bStateSave = true;
		if($(this).hasClass("dataTable-ajax")){
			opt.bProcessing = true;
			opt.bServerSide = true;
			opt.sAjaxSource = "<?=$access_path?>article/article/index";
			opt.fnDrawCallback=function(){
				$('a[upload-config="show"]').editable({
					type:'select',
					source: [
						{value: 1, text: '显示'},
						{value: 2, text: '隐藏'}
					],
					url: function(params) {
						var d = new $.Deferred;
						$.ajax({
							type:'POST',
							url:'/admin/article/article/edit_show',
							data:$.param(params),
							dataType:'json',
							success: function(r) {
								if(r.state == 1){
									pub_alert_success(r.info);
									d.resolve();
								}else{
									return d.reject(r.info);
								}
							}
						});
						return d.promise();
					},
				});
			}
		}
		if($(this).hasClass("basic_major")){
			opt.bStateSave = false;
			opt.aoColumns = [
				{ "mData": "article_id" },
				{ "mData": "title" },
				{ "mData": "show" },
				{ "mData": "operation" }

			];
			opt.aaSorting = [[1,'desc']];
			opt.aoColumnDefs = [{ "bSortable": false, "aTargets": [3] }];
		}

		var oTable = $(this).dataTable(opt);
		if($(this).hasClass("dataTable-columnfilter")){
			oTable.columnFilter({
				"sPlaceHolder" : "head:after"
			});
		}
	});
	
	$('#art_id').on( 'keyup', function () {
		zjj_datatable_search(0,$("#art_id").val());
	} );

	$('#art_title').on( 'keyup', function () {
		zjj_datatable_search(1,$("#art_title").val());
	} );

	$('#art_show').change(function () {
		zjj_datatable_search(2,$("#art_show").val());
	} );

	function zjj_datatable_search(column,val){
		$('#sample-table-2').DataTable().column( column ).search( val,false, true).draw();
	}

}


});
	
</script>

<!-- end script -->
<?php $this->load->view('admin/public/footer');?>