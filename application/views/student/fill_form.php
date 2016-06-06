<?php $this->load->view('student/headermy');?>
<link rel="stylesheet" href="<?=RES?>home/css/bootstrap.min.css">
<script type="text/javascript" src="<?=RES?>home/js/plugins/bootstrap.min.js"></script>
<link rel="stylesheet" href="<?=RES?>home/js/plugins/poshytip/tip-twitter/tip-twitter.css">
<link rel="stylesheet" href="<?=RES?>home/js/plugins/jFormer/jformer.css">
<link rel="stylesheet" href="<?=RES?>home/css/apply.css">
<script type="text/javascript" src="<?=RES?>home/js/plugins/poshytip/jquery.poshytip.min.js"></script>
<script type="text/javascript" src="<?=RES?>home/js/plugins/jquery.sticky.js"></script>
<script type="text/javascript" src="<?=RES?>home/js/plugins/jquery.scrollTo.min.js"></script>
<link media="screen" rel="stylesheet" href="<?=RES?>home/js/plugins/datepicker/datepicker.css">
<script type="text/javascript" src="<?=RES?>home/js/plugins/datepicker/bootstrap-datepicker.js"></script>
 
<div class="width_925 clearfix">
	<?php $this->load->view('student/apply_coursename')?>
</div>
<div class="width_925 clearfix applyonline-main">
	<div class="list_title">
		<?php $this->load->view('student/apply_left')?>
	
	</div>
	<div class="applyonline-2-main">
		 <div class='float_nav'>
			<?=$html_left?>
		</div>
		<div class="f_r applyonline-left-nav-width625">
		<form method="post" id="myform" action="/<?=$puri?>/student/fillingoutforms/save/<?=$cid?>" onsubmit="return scroll_to_error();">
			<?=$html_form?>
			<div class="applyonline-2-btn">


				<input type="button" class="appBtnSN" onclick="do_save()" value="<?=lang('apply_save')?>">
				<input type="submit" class="appBtnSN" value="<?=lang('apply_next')?>">
			</div>
		</form>
		</div>
	</div>
</div>

<script type="text/javascript">
$('.RFormQues').poshytip({
	className: 'tip-twitter',
	showTimeout: 1,
	alignTo: 'target',
	alignX: 'center',
	offsetY: 5,
	allowTipHover: false,
	fade: false,
	slide: false
});

function scroll_to_error(){
	setTimeout(function(){
		if($('input.error').length > 0){
			$.scrollTo( $('input.error').offset().top,600 );
		}
	},300);
}

function do_save(){
	var data = $("#myform").serialize();
	$.ajax({
		url:'/<?=$puri?>/student/fillingoutforms/save/<?=cucas_base64_encode($apply_info['id'])?>',
		type:'post',
		data:data,
		dataType:'json',
		beforeSend:function(){

		},
		success:function(r){
			if(r.state == 1){
				var d = dialog({
					content: ''+r.info+''
				});
				d.show();
				setTimeout(function () {
					d.close().remove();
				}, 2000);
				setTimeout('window.location.reload()',1000);
			}else{
				art.dialog.alert(r.info);
			}
		}
	});
	return false;
}

$(function(){
	$("#myform").ajaxForm({
		url:'/<?=$puri?>/student/fillingoutforms/save/<?=cucas_base64_encode($apply_info['id'])?>/ischeck',
		dataType:'json',
		success:function(r){
			if(r.state == 1){
				var d = dialog({
					content: ''+r.info+''
				});
				d.show();
				setTimeout(function () {
					d.close().remove();
				}, 2000);
				setTimeout('window.location.reload()',1000);
				if(r.data == ''){
					setTimeout('window.location.reload()',1000);
				}else{
					window.location.href = r.data;
				}
			}else{
				var d = dialog({
					content: ''+r.info+''
				});
				d.show();
				setTimeout(function () {
					d.close().remove();
				}, 2000);
				setTimeout('window.location.reload()',1000);
			}
		}
	});

	$(".jFormComponentRemoveInstanceButton").click(function(){
		$(this).parent('div').slideUp(300,function(){ $(this).remove();group_order_by(li)});
	});
});

function group_order_by(obj){
	if(obj.find(".jFormComponentName").length > 0){
		var item = obj.find(".jFormComponentName");
		item.each(function(i,v){
			var input = $(v).find('input[type="text"]');
			input.each(function(){
				var field = $(this).attr('data-field');
				var name = $(this).attr('data-name');
				$(this).attr('name',field+'['+i+']['+name+']');
			});
		});
	}
}

if($(".jFormComponentAddInstanceButton").length > 0){
	$(".jFormComponentAddInstanceButton").click(function(){
		var li = $(this).parent('li')
		var p_html = li.find('.jFormComponent').eq(0).clone();
		var remove = $('<input type="button" value="　 Remove" class="jFormComponentRemoveInstanceButton">').click(function(){
			$(this).parent('div').slideUp(300,function(){ $(this).remove();group_order_by(li)});
		});
		p_html.append(remove);
		p_html.find('input[type="text"]').val('');
		p_html.find('.datepick').datepicker({format: 'yyyy-mm-dd'});
		$(this).before(p_html);
		group_order_by(li);
	});
}

var temp_level = 0;
var last_level = 0;
var level = 0;
function zjj_show(id,lastid){
	if(!id) return false;
	var t = $("#controlid_"+lastid);
	var that = $("#controlid_"+id);
	var t_level = t.attr('level');
	var that_level = that.attr('level');
	if(!t_level){
		level++
		t.attr('level',level);
		temp_level = level;
	}
	temp_level = t_level ? t_level : temp_level;
	if(level == temp_level){
		$("li[level='2']").attr('level','').hide();
		$("li[level='3']").attr('level','').hide();
		$("li[level='4']").attr('level','').hide();
		$("li[level='5']").attr('level','').hide();
		$("li[level='6']").attr('level','').hide();
	}else if(temp_level == 2){
		$("li[level='3']").attr('level','').hide();
		$("li[level='4']").attr('level','').hide();
		$("li[level='5']").attr('level','').hide();
		$("li[level='6']").attr('level','').hide();
	}else if(temp_level == 3){
		$("li[level='4']").attr('level','').hide();
		$("li[level='5']").attr('level','').hide();
		$("li[level='6']").attr('level','').hide();
	}else if(temp_level == 4){
		$("li[level='5']").attr('level','').hide();
		$("li[level='6']").attr('level','').hide();
	}else if(temp_level == 5){
		$("li[level='6']").attr('level','').hide();
	}

	$("#controlid_"+id).show();
	$("#controlid_"+id).attr('level',parseInt(temp_level)+1);

}
 $(window.document).scroll(function () {
	var scrolltop = $(document).scrollTop();
    var form_box = $(".appInfo");
   	var isq = [];
   	var last = '';
   	form_box.each(function(i,v){
   		var t = $(this).offset().top-120;
   		if(scrolltop > t){
   			$(".appNav a").removeClass('appNavAc');
   			$(".appNav a").eq(i).addClass('appNavAc');
   		}
   	});
});
$(".appNav").sticky({bottomSpacing:415});
$(".appNav a").each(function(i,v){
	$(this).click(function(){
		var form_box = $(".appInfo");
		$.scrollTo( $(form_box).eq(i).offset().top,600 );
		$(".appNav a").removeClass('appNavAc');
		$(this).addClass('appNavAc');
	});
});
$(function(){
if($('.datepick').length > 0){
	$('.datepick').datepicker({format: 'yyyy-mm-dd'});
}
    if($('.datepick-ym').length > 0){
        $('.datepick-ym').datepicker({format: 'yyyy-mm',viewMode:2,minViewMode:1});
    }

    $("input[name='maritalstatus']").click(function(){
       change_single();
    });

    $("select[name='is_in_china']").change(function(){
        var v = $(this).val();
        var s_i = $("input[name='is_school_name']");
        var s_i_p = s_i.parents().eq(0);

        if(v =='notinChina'){
            s_i_p.hide();
            s_i.rules('add',{ required: false});
        }else{
            s_i_p.show();
            s_i.rules('add',{ required: true});
        }
    });

    if($("select[name='is_in_china']").val() == 'notinChina'){
        var s_i = $("input[name='is_school_name']");
        var s_i_p = s_i.parents().eq(0);
        s_i_p.hide();
        s_i.rules('add',{ required: false});
    }

    var change_single = function(){
        var v = $("input[name='maritalstatus']:checked").val();
        var SpouseName = $("input[name='SpouseName']");
        var SpouseAge = $("input[name='SpouseAge']");
        var SpouseOccupation = $("input[name='SpouseOccupation']");
        var SpouseTel = $("input[name='SpouseTel']");
        var SpouseEmail = $("input[name='SpouseEmail']");

        var SpouseName_p = SpouseName.prev();
        var SpouseAge_p = SpouseAge.prev();
        var SpouseOccupation_p = SpouseOccupation.prev();
        var SpouseTel_p = SpouseTel.prev();
        var SpouseEmail_p = SpouseEmail.prev();

        if(v == 'Single'){
            SpouseName_p.hide();
            SpouseAge_p.hide();
            SpouseOccupation_p.hide();
            SpouseTel_p.hide();
            SpouseEmail_p.hide();

            SpouseName.rules('add',{ required: false});
            SpouseAge.rules('add',{ required: false});
            SpouseOccupation.rules('add',{ required: false});
            SpouseTel.rules('add',{ required: false});
            SpouseEmail.rules('add',{ required: false});
        }else{
            SpouseName_p.show();
            SpouseAge_p.show();
            SpouseOccupation_p.show();
            SpouseTel_p.show();
            SpouseEmail_p.show();

            SpouseName.rules('add',{ required: true});
            SpouseAge.rules('add',{ required: true});
            SpouseOccupation.rules('add',{ required: true});
            SpouseTel.rules('add',{ required: true});
            SpouseEmail.rules('add',{ required: true});
        }
    }

    change_single();
});

</script>
<?php $this->load->view('student/footer_no.php')?>