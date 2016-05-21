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
<div class="ui-layout-north panel"><h3>Form Pendataan - <a href="<?=site_url()?>analisis_master/menu/<?=$_SESSION['analisis_master']?>"><?=$analisis_master['nama']?></a></h3>
<h4> &nbsp; Keluarga - (<?=$subjek['no_kk']?>) <?=$subjek['nama']?></h4></br>
<h4> &nbsp; Daftar pertanyaan dan jawaban.</h4>
</div>
    <form id="validasi" action="<?=$form_action?>" method="POST">
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<input type="hidden" name="rt" value="">
<table>
	<?foreach($list_jawab AS $data){?>
	<tr><td><label><?=$data['no']?>) <?=$data['pertanyaan']?></label></td></tr>
	
	<?if($data['id_tipe']==1){?>
	<?foreach($data['parameter_respon'] AS $data2){?>
	<tr><td id="skpd_select">
	<div style="display:inline-block;"><input type="radio" class="required" name="rb[<?=$data['id']?>]" value="<?=$data['id']?>.<?=$data2['id_parameter']?>" <?if($data2['cek']){echo " checked";}?>><label><?=$data2['jawaban']?></label></div>
	<?}?>
	<?}elseif($data['id_tipe']==2){?>
	
	<?foreach($data['parameter_respon'] AS $data2){?>
	<tr><td id="skpd_select">
	<div style="display:inline-block;"><input type="checkbox" name="cb[<?=$data2['id_parameter']?>]" value="<?=$data2['id_parameter']?>.<?=$data['id']?>" <?if($data2['cek']){echo " checked";}?>><label><?=$data2['jawaban']?></label></div>
	<?}?>
	
	<?}elseif($data['id_tipe']==3){?>
	<?if($data['parameter_respon']){?>
	<?$data2=$data['parameter_respon'];?>
	<tr><td id="">
	<div style="display:inline-block;"><input name="ia[<?=$data['id']?>]" type="text" class="inputbox number" size="10" value="<?=$data2['jawaban']?>"/></div>
	<?}else{?>
	<tr><td id="">
	<div style="display:inline-block;"><input name="ia[<?=$data['id']?>]" type="text" class="inputbox number" size="10" value=""/></div>
	<?}?>
	<?}elseif($data['id_tipe']==4){?>
	<?if($data['parameter_respon']){?>
	<?$data2=$data['parameter_respon'];?>
	<tr><td id="">
	<div style="display:inline-block;"><input name="it[<?=$data['id']?>]" type="text" class="inputbox" size="100" value="<?=$data2['jawaban']?>"/></div>
	<?}else{?>
	<tr><td id="">
	<div style="display:inline-block;"><input name="it[<?=$data['id']?>]" type="text" class="inputbox" size="100" value=""/></div>
	<?}?>
	<?}?>
	<?}?>
</table>
    </div>
   
    <div class="ui-layout-south panel bottom">
        <div class="left">     
            <a href="<?=site_url()?>analisis_respon_keluarga" class="uibutton icon prev">Kembali</a>
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