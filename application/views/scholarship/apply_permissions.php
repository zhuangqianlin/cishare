<?php $this->load->view('student/headermy.php')?>
<div class="width_925 clearfix applyonline-main">
  <div> <span>
    <?=!empty($info)?$info:lang('zzsh')?>
   
    <a href="/<?=$puri?>/scholarshipgrf/index/"><?=lang('returnapply')?></a>
  </span></div>
</div>
<script type="text/javascript">
$(function(){
      setTimeout("window.location.href='/<?=$puri?>/scholarshipgrf/index/'",3000);  
});
</script>
<?php $this->load->view('student/footer.php')?>