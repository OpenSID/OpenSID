<script type="text/javascript">
$("document").ready(function(){
	$("#validasi").bind("submit",function(evt){
    var file = $('#file').attr('files')[0];
    if(file && file.size > <?php echo max_upload()*1000000?>) { // ukuran berkas dalam bytes)
      //Prevent default and display error
      evt.preventDefault();
      $('#dialog').html("<p>Berkas itu melebihi batas "+<?php echo "'".max_upload()."'" ?>+"MB. </p>");
			$('#dialog').show();
			$('#dialog').dialog();
    }
  });
});
</script>

<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;">

	<div id="dialog" title="Perhatian" style="display: none;">
	  <p>Berkas itu melebihi batas x MB.</p>
	</div>

<div class="content-header">
<h3>Form Manajemen Galeri</h3>
</div>
<div id="contentpane">
<form id="validasi" action="<?php echo $form_action?>" method="POST" enctype="multipart/form-data">
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">
<tr>
<th>Nama Album</th>
<td><input name="nama" type="text" class="inputbox" size="60" maxlength='50' value="<?php echo $gallery['nama']?>"/></td>
</tr>
<?php if($gallery['gambar']){?>
<tr>
<th class="top">Gambar</th>
<td>
<div>
<img width="440" height="300" src="<?php echo AmbilGaleri($gallery['gambar'], 'sedang') ?>" alt=""/>
</div>
</td>
<input type="hidden" name="old_gambar" value="<?php echo $gallery['gambar']?>">
</tr>
<?php }?>
<tr>
	<th>Upload Gambar</th>
	<td>
		<input id="file" type="file" name="gambar" /> <span style="color: #aaa;">(Kosongi jika tidak ingin mengubah gambar)</span>
    <?php $upload_mb = max_upload();
    	echo "<p>Batas maksimal pengunggahan berkas <strong>".$upload_mb." MB.</strong></p>"
    ?>
	</td>
</tr>
</table>
</div>

<div class="ui-layout-south panel bottom">
<div class="left">
<a href="<?php echo site_url()?>gallery" class="uibutton icon prev">Kembali</a>
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