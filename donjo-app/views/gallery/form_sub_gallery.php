<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;"> 

<div class="content-header">
<h3>Form Sub Gallery</h3>
</div>
<div id="contentpane">
<form id="validasi" action="<?php echo $form_action?>" method="POST" enctype="multipart/form-data">
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">
<tr>
<th>Judul Gambar</th>
<td><input name="nama" type="text" class="inputbox" size="60" value="<?php echo $gallery['nama']?>"/></td>
</tr>
<?php if($gallery['gambar']){?>
<tr>
<th class="top">Judul Gambar</th>
<td>
<div class="gallerybox-avatar">
<img src="<?php echo base_url()?>assets/files/galeri/sedang_<?php echo $gallery['gambar']?>" alt=""/>
</div>
</td>
<input type="hidden" name="old_gambar" value="<?php echo $gallery['gambar']?>">
</tr>
<?php }?>
<tr>
<th>Upload Gambar</th>
<td><input type="file" name="gambar" /> <span style="color: #aaa;">(Kosongi jika tidak ingin mengubah gambar)</span></td>
</tr>
</table>
</div>
   
<div class="ui-layout-south panel bottom">
<div class="left"> 
<a href="<?php echo site_url()?>gallery" class="uibutton icon prev">Kembali</a>
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
