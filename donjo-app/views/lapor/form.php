
<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<td class="side-menu">


</td>

<td style="background:#fff;padding:0px;"> 
<div class="content-header">
<h3>Komentar</h3>
</div>


<div id="contentpane">
<form id="validasi" action="<?php echo $form_action?>" method="POST" enctype="multipart/form-data">
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">


<table class="form">

<tr>
<th>Pengirim</th>
<td><input class="inputbox" type="text" name="owner" value="<?php echo $komentar['owner']?>" size="60"/></td>
</tr>

<tr>
<th>Email</th>
<td><input class="inputbox" type="text" name="email" value="<?php echo $komentar['email']?>" size="60"/></td>
</tr>

<tr>
<td colspan="2">
<textarea name="komentar" rows="15" cols="80" style="width: 100%; height: 100%">
<?php echo $komentar['komentar']?>
</textarea>
</td>
</tr>

<tr>
<th>Status</th>
<td>
<div class="uiradio">
<input type="radio" id="sx1" name="enabled" value="1"/<?php if($komentar['enabled'] == '1' OR $komentar['enabled'] == ''){echo 'checked';}?>>
<label for="sx1">enable</label>
<input type="radio" id="sx2" name="enabled" value="2"/<?php if($komentar['enabled'] == '0'){echo 'checked';}?>>
<label for="sx2">disable</label>
</div>
</td>
</tr>

</table>

   
<div class="ui-layout-south panel bottom">
<div class="left">
<a href="<?php echo site_url()?>komentar" class="uibutton icon prev">Kembali</a>
</div>
<div class="right">
<div class="uibutton-group">
<button class="uibutton" type="reset"><span class="fa fa-refresh"></span> Bersihkan</button>
<button class="uibutton confirm" type="submit" ><span class="fa fa-save"></span> Simpan</button>
</div>
</div>
</div> </form>
</div>
</td></tr></table>
</div>
