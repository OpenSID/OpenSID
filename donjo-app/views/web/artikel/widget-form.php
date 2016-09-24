<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;">
<div id="contentpane">
<form id="validasi" action="<?php echo $form_action?>" method="POST" enctype="multipart/form-data">
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<h3>Form Widget <?php if($kategori){echo $kategori['kategori'];}else{echo "Artikel Statis";}?></h3>
<table class="form">
<tr>
<th width="120">Judul Widget</th>
<td><input class="inputbox" type="text" name="judul" value="<?php echo $artikel['judul']?>" size="60"/></td>
</tr>
<tr>
<tr>
<th width="120" colspan="2">Kode Widget</th>
</tr>
<tr>
<tr>
<td colspan="2">
<textarea  name="isi" style="width: 500px; height: 300px;">
<?php echo $artikel['isi']?>
</textarea>
</td>
</tr>
<tr>
<td colspan="2">
&nbsp;
</td>
</tr>
<?php if($artikel['gambar']){?>
<tr>
<th class="top">Gambar</th>
<td>
<div class="gallerybox-avatar">
<img src="<?php echo AmbilFotoArtikel($artikel['gambar'],'kecil')?>" alt="" width="200"/>
</div>
</td>
</tr>
<?php }?>
<tr>
<th>Dokumen Lampiran</th>
<td><input type="file" name="dokumen" /> <span style="color: #aaa;"></span></td>
</tr>
<tr>
<th>Nama Dokumen (Nantinya akan menjadi link unduh/download)</th>
<td><input size="30" type="text" name="link_dokumen" value="<?php echo $artikel['link_dokumen']?>"/></td>
</tr>
<tr>
<th>Unggah/Upload Gambar Utama</th>
<td><input type="file" name="gambar" /> <span style="color: #aaa;">(Kosongi jika tidak ingin mengubah gambar)</span></td>
</tr>
<tr>
<th class="top">Gambar</th>
<td>
<?php if($artikel['gambar1']){?>
<div class="gallerybox-avatar">
<img src="<?php echo AmbilFotoArtikel($artikel['gambar1'],'kecil')?>" alt="" width="200"/>
</div>
</td>
</tr>
<?php }?>
<tr>
<th>Gambar Tambahan</th>
<td><input type="file" name="gambar1" /> <span style="color: #aaa;">(Kosongi jika tidak ingin mengubah gambar)</span></td>
</tr>
<tr>
<th class="top">Gambar</th>
<td>
<?php if($artikel['gambar2']){?>
<div class="gallerybox-avatar">
<img src="<?php echo AmbilFotoArtikel($artikel['gambar2'],'kecil')?>" alt="" width="200"/>
</div>
</td>
</tr>
<?php }?>
<tr>
<th>Gambar Tambahan</th>
<td><input type="file" name="gambar2" /> <span style="color: #aaa;">(Kosongi jika tidak ingin mengubah gambar)</span></td>
</tr>
<tr>
<th class="top">Gambar</th>
<td>
<?php if($artikel['gambar3']){?>
<div class="gallerybox-avatar">
<img src="<?php echo AmbilFotoArtikel($artikel['gambar3'],'kecil')?>" alt="" width="200"/>
</div>
</td>
</tr>
<?php }?>
<tr>
<th>Gambar Tambahan</th>
<td><input type="file" name="gambar3" /> <span style="color: #aaa;">(Kosongi jika tidak ingin mengubah gambar)</span></td>
</tr>
</table>
</div>

<div class="ui-layout-south panel bottom">
<div class="left">
<a href="<?php echo site_url()?>web/widget/1" class="uibutton icon prev">Kembali</a>
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