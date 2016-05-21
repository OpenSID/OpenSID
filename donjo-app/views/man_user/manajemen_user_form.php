<script>
$(function(){
if ($('input[name=group]:checked').next('label').text()=='SKPD' || $('input[name=group]:checked').next('label').text()=='UPTD'){
$('tr.skpd_uptd').show();
} 
$('input[name=group]').click(function(){
if ($(this).next('label').text()=='SKPD' || $(this).next('label').text()=='UPTD'){
$('tr.skpd_uptd').show();
} else {
$('tr.skpd_uptd').hide();
}
});
if ($('input[name=group]:checked').next('label').text()=='SKPD'){
$('tr.skpd').show();
} 
$('input[name=group]').click(function(){
if ($(this).next('label').text()=='SKPD'){
$('tr.skpd').show();
} else {
$('tr.skpd').hide();
}
});
if ($('input[name=group]:checked').next('label').text()=='UPTD'){
$('tr.uptd').show();
} 
$('input[name=group]').click(function(){
if ($(this).next('label').text()=='UPTD'){
$('tr.uptd').show();
} else {
$('tr.uptd').hide();
}
});
});
</script>

<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;"> 

<div class="content-header">

</div>
<div id="contentpane">
<div class="ui-layout-north panel"><h3>Form Manajemen User</h3>
</div>
<form id="validasi" action="<?=$form_action?>" method="POST" enctype="multipart/form-data">
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">
<tr>
<th width="100">Group</th>
<td>
<div class="uiradio">
<?$ch='checked';?>
<?if($user['id_grup'] != '1'){?>
<input type="radio" id="group3" name="id_grup" value="3"/<?if($user['id_grup'] == '3' OR $user['id_grup'] == ''){echo $ch;}?>><label for="group3">Redaksi</label>
<input type="radio" id="group2" name="id_grup" value="2"/<?if($user['id_grup'] == '2'){echo $ch;}?>><label for="group2">Operator</label>
<?}?>
<input type="radio" id="group1" name="id_grup" value="1"/<?if($user['id_grup'] == '1'){echo $ch;}?>><label for="group1">Administrator</label>
</div>
</td>
</tr>
<tr>
<th>Username</th>
<td><input name="username" type="text" class="inputbox required" size="40" value="<?=$user['username']?>"/></td>
</tr>
<tr>
<th>Password</th>
<td><input name="password" type="password" class="inputbox" size="20" <?if($user){?>value="radiisi"<?}?>/></td>
</tr>
<tr>
<th>Nama</th>
<td><input name="nama" type="text" class="inputbox" size="40" value="<?=$user['nama']?>"/></td>
</tr>
<tr>
<th>Nomor HP</th>
<td><input name="phone" type="text" class="inputbox" size="20"  value="<?=$user['phone']?>"/></td>
</tr>   
<tr>
<th>Mail</th>
<td><input name="email" type="text" class="inputbox" size="20"  value="<?=$user['email']?>"/></td>
</tr> 

<tr>
<th class="top">Foto</th>
<td>
<div class="userbox-avatar">
<?if($user['foto']){?>
<img src="<?=base_url()?>assets/images/photo/kecil_<?=$user['foto']?>" alt=""/>
<?}else{?>
<img src="<?=base_url()?>assets/images/photo/kuser.png" alt=""/>
<?}?>
</div>
</td>
<input type="hidden" name="old_foto" value="<?=$user['foto']?>">
</tr>
<tr>
<th>Ganti Foto</th>
<td><input type="file" name="foto" /> <span style="color: #aaa;">(Kosongi jika tidak ingin merubah foto)</span></td>
</tr>
</table>
</div>
   
<div class="ui-layout-south panel bottom">
<div class="left"> 
<a href="<?=site_url()?>man_user" class="uibutton icon prev">Kembali</a>
</div>
<div class="right">
<div class="uibutton-group">
<button class="uibutton" type="reset">Clear</button>
<button class="uibutton confirm" type="submit" >Simpan</button>
</div>
</div>
</div> </form>
</div>
</td></tr></table>
</div>
