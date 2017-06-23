<script>
$(function(){ 
	var skpd_select_width = (parseInt($('#skpd_select').width())/2)-100;
	$('#skpd_select div').css('width',700);
	$('#skpd_select input:checked').parent().css({'background':'#c9cdff','border':'1px solid #7a82eb'});
	$('#skpd_select input').change(function(){
		if ($(this).is(':checked')){
			$('#skpd_select input').parent().css({'background':'#ffffff','border':'1px solid #ddd'});
			$('#skpd_select input:checked').parent().css({'background':'#c9cdff','border':'1px solid #7a82eb'});
			$(this).parent().css({'background':'#c9cdff','border':'1px solid #7a82eb'});
		} else {
			$(this).parent().css({'background':'#fafafa','border':'1px solid #ddd'});
		}
	});	
	$('#skpd_select label').click(function(){
		$(this).prev().trigger('click');
	})
});
</script>
<style>
#skpd_select div{
	vertical-align:top;
	margin:1px 0;
	padding:2px 2px 2px;
	background:#fafafa;
	border:1px solid #ddd;
}
#skpd_select input{
	vertical-align:middle;
	margin-right:2px;
}
#skpd_select label{
	font-size:11px;
	font-weight:normal;
}
</style>

<div id="active-menu">Data Responden</div>
<div class="content-header">
    <h3>Form Manajemen Responden</h3>
</div>
<div id="contentpane">
    <form id="validasi" action="<?php echo $form_action?>" method="POST">
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<input type="hidden" name="rt" value="">
<table>
	<?php $last="";foreach($list_jawab AS $data){?>
	<?php if($data['pertanyaan']!=$last){?></td></tr><tr><td>&nbsp;</td></tr><tr><td><label><?php echo $data['nomor']?>. <?php echo $data['pertanyaan']?></label></td></tr>
	<tr><td id="skpd_select">
	<div style="display:inline-block;"><input type="radio" class="required" name="cb[<?php echo $data['id']?>]" value="<?php echo $data['id']?>.<?php echo $data['id_jawaban']?>" <?php if($data['cek']){echo " checked";}?>><label><?php echo $data['huruf']?>. <?php echo $data['jawaban']?></label></div>
	<?php }else{?>
	<div style="display:inline-block;"><input type="radio" class="required" name="cb[<?php echo $data['id']?>]" value="<?php echo $data['id']?>.<?php echo $data['id_jawaban']?>"<?php if($data['cek']){echo " checked";}?>><label><?php echo $data['huruf']?>. <?php echo $data['jawaban']?></label></div>
	<?php }?>
	<?php $last=$data['pertanyaan'];
	}?>
</table>
    </div>
   
    <div class="ui-layout-south panel bottom">
        <div class="left">     
            <a href="<?php echo site_url()?>admin_manajemen_responden" class="uibutton icon prev">Kembali</a>
        </div>
        <div class="right">
            <div class="uibutton-group">
                <button class="uibutton" type="reset"><span class="fa fa-refresh"></span> Bersihkan</button>
                <button class="uibutton confirm" type="submit" ><span class="fa fa-save"></span> Simpan</button>
            </div>
        </div>
    </div>
</form>
</div>