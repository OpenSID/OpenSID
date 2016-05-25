<script>
$(function(){ 
	var skpd_select_width = (parseInt($('#skpd_select').width())/2)-100;
	$('#skpd_select div').css('clear','both');
	$('#skpd_select div').css('float','left');
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
	align:justify;
}
</style>
<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;"> 

<div class="content-header">
</div>
<div id="contentpane">
<div class="ui-layout-north panel"><h3>Form Pendataan - <a href="<?php echo site_url()?>analisis_master/menu/<?php echo $_SESSION['analisis_master']?>"><?php echo $analisis_master['nama']?></a></h3>
<h4> &nbsp; Keluarga - (<?php echo $subjek['no_kk']?>) <?php echo $subjek['nama']?></h4></br>
<h4> &nbsp; Daftar pertanyaan dan jawaban.</h4>
</div>
    <form id="validasi" action="<?php echo $form_action?>" method="POST">
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<input type="hidden" name="rt" value="">
<table>
	<?php foreach($list_jawab AS $data){?>
	<tr><td><label><?php echo $data['no']?>) <?php echo $data['pertanyaan']?></label></td></tr>
	
	<?php if($data['id_tipe']==1){?>
	<?php foreach($data['parameter_respon'] AS $data2){?>
	<tr><td id="skpd_select">
	<div style="display:inline-block;"><input type="radio" class="required" name="rb[<?php echo $data['id']?>]" value="<?php echo $data['id']?>.<?php echo $data2['id_parameter']?>" <?php if($data2['cek']){echo " checked";}?>><label><?php echo $data2['jawaban']?></label></div>
	<?php }?>
	<?php }elseif($data['id_tipe']==2){?>
	
	<?php foreach($data['parameter_respon'] AS $data2){?>
	<tr><td id="skpd_select">
	<div style="display:inline-block;"><input type="checkbox" name="cb[<?php echo $data2['id_parameter']?>]" value="<?php echo $data2['id_parameter']?>.<?php echo $data['id']?>" <?php if($data2['cek']){echo " checked";}?>><label><?php echo $data2['jawaban']?></label></div>
	<?php }?>
	
	<?php }elseif($data['id_tipe']==3){?>
	<?php if($data['parameter_respon']){?>
	<?php $data2=$data['parameter_respon'];?>
	<tr><td id="">
	<div style="display:inline-block;"><input name="ia[<?php echo $data['id']?>]" type="text" class="inputbox number" size="10" value="<?php echo $data2['jawaban']?>"/></div>
	<?php }else{?>
	<tr><td id="">
	<div style="display:inline-block;"><input name="ia[<?php echo $data['id']?>]" type="text" class="inputbox number" size="10" value=""/></div>
	<?php }?>
	<?php }elseif($data['id_tipe']==4){?>
	<?php if($data['parameter_respon']){?>
	<?php $data2=$data['parameter_respon'];?>
	<tr><td id="">
	<div style="display:inline-block;"><input name="it[<?php echo $data['id']?>]" type="text" class="inputbox" size="100" value="<?php echo $data2['jawaban']?>"/></div>
	<?php }else{?>
	<tr><td id="">
	<div style="display:inline-block;"><input name="it[<?php echo $data['id']?>]" type="text" class="inputbox" size="100" value=""/></div>
	<?php }?>
	<?php }?>
	<?php }?>
</table>
    </div>
   
    <div class="ui-layout-south panel bottom">
        <div class="left">     
            <a href="<?php echo site_url()?>analisis_respon_keluarga" class="uibutton icon prev">Kembali</a>
        </div>
        <div class="right">
            <div class="uibutton-group">
                <button class="uibutton" type="reset">Clear</button>
                <button class="uibutton confirm" type="submit" >Simpan</button>
            </div>
        </div>
    </div>
</form>
</div>
