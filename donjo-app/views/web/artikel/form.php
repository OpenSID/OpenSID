<script type="text/javascript" src="<?php echo base_url()?>assets/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript">

tinymce.init({
  selector: 'textarea',
  height: 500,
  theme: 'modern',
  plugins: [
         "advlist autolink link image lists charmap print preview hr anchor pagebreak",
         "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
         "table contextmenu directionality emoticons paste textcolor responsivefilemanager code"
   ],
   toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
   toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
   image_advtab: true ,
   external_filemanager_path:"<?php echo base_url()?>assets/filemanager/",
   filemanager_title:"Responsive Filemanager" ,
   filemanager_access_key:"<?php echo config_item('file_manager')?>",
   external_plugins: { "filemanager" : "<?php echo base_url()?>assets/filemanager/plugin.min.js"},
  templates: [
    { title: 'Test template 1', content: 'Test 1' },
    { title: 'Test template 2', content: 'Test 2' }
  ],
  content_css: [
    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
    '//www.tinymce.com/css/codepen.min.css'
  ]
 });
//date time init
$(document).ready(function(){
  $('#tgl-posting').datetimepicker({
    dateFormat : 'dd-mm-yy',
    timeFormat : 'HH:mm:ss'
  });
});
</script>
<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;">
<div id="contentpane">
<form id="validasi" action="<?php echo $form_action?>" method="POST" enctype="multipart/form-data">
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<h3>Form Artikel <?php if($kategori){echo $kategori['kategori'];}else{echo "Artikel Statis";}?></h3>
<table class="form">
<tr>
<th width="120">Judul Artikel</th>
<td><input class="inputbox required" type="text" name="judul" value="<?php echo $artikel['judul']?>" size="60"/></td>
</tr>
<tr>
<tr>
<th width="120" colspan="2">Isi Artikel</th>
</tr>
<tr>
<tr>
<td colspan="2">
<textarea name="isi" style="width: 800px; height: 500px;">
  <?= $artikel['isi']?>
</textarea>
</td>
</tr>
<tr>
<td colspan="2">
&nbsp;
</td>
</tr>
<tr>
<th>Dokumen Lampiran</th>
<td><input type="file" name="dokumen" /> <span style="color: #aaa;"></span></td>
</tr>
<?php if($artikel['dokumen']){?>
<tr>
<th class="top">Dokumen</th>
<td>
<a href="<?php echo base_url().LOKASI_DOKUMEN.$artikel['dokumen']?>"/>Download</a>
</td>
</tr>
<?php }?>
<tr>
<th>Nama Dokumen (Nantinya akan menjadi link unduh/download)</th>
<td><input size="30" type="text" name="link_dokumen" value="<?php echo $artikel['link_dokumen']?>"/></td>
</tr>
<tr>
<td colspan="2" style="background-color:#ffddcc;">
&nbsp;
</td>
</tr>
<?php if($artikel['gambar']){?>
<tr>
<th class="top">Gambar</th>
<td>
  <input type="hidden" name="old_gambar", value="<?php echo $artikel['gambar']?>">
  <div class="gallerybox-avatar">
    <img src="<?php echo AmbilFotoArtikel($artikel['gambar'],'kecil')?>" alt="" width="200"/>
  </div>
  <input type="checkbox" name="gambar_hapus" value="<?php echo $artikel['gambar']?>" /> Hapus Gambar
</td>
</tr>
<?php }?>
<tr>
<th>Unggah/Upload Gambar Utama</th>
<td><input type="file" name="gambar" /> <span style="color: #aaa;">(Kosongi jika tidak ingin mengubah gambar)</span></td>
</tr>
<?php if($artikel['gambar1']){?>
<tr>
<th class="top">Gambar</th>
<td>
  <input type="hidden" name="old_gambar1", value="<?php echo $artikel['gambar1']?>">
  <div class="gallerybox-avatar">
    <img src="<?php echo AmbilFotoArtikel($artikel['gambar1'],'kecil')?>" alt="" width="200"/>
  </div>
  <input type="checkbox" name="gambar1_hapus"  value="<?php echo $artikel['gambar1']?>"/> Hapus Gambar
</td>
</tr>
<?php }?>
<tr>
<th>Gambar Tambahan</th>
<td><input type="file" name="gambar1" /> <span style="color: #aaa;">(Kosongi jika tidak ingin mengubah gambar)</span></td>
</tr>
<?php if($artikel['gambar2']){?>
<tr>
<th class="top">Gambar</th>
<td>
  <input type="hidden" name="old_gambar2", value="<?php echo $artikel['gambar2']?>">
  <div class="gallerybox-avatar">
    <img src="<?php echo AmbilFotoArtikel($artikel['gambar2'],'kecil')?>" alt="" width="200"/>
  </div>
  <input type="checkbox" name="gambar2_hapus"  value="<?php echo $artikel['gambar2']?>"/> Hapus Gambar
</td>
</tr>
<?php }?>
<tr>
<th>Gambar Tambahan</th>
<td><input type="file" name="gambar2" /> <span style="color: #aaa;">(Kosongi jika tidak ingin mengubah gambar)</span></td>
</tr>
<?php if($artikel['gambar3']){?>
<tr>
<th class="top">Gambar</th>
<td>
  <input type="hidden" name="old_gambar3", value="<?php echo $artikel['gambar3']?>">
  <div class="gallerybox-avatar">
    <img src="<?php echo AmbilFotoArtikel($artikel['gambar3'],'kecil')?>" alt="" width="200"/>
  </div>
  <input type="checkbox" name="gambar3_hapus" value="<?php echo $artikel['gambar3']?>"/> Hapus Gambar
</td>
</tr>
<?php }?>
<tr>
<th>Gambar Tambahan</th>
<td><input type="file" name="gambar3" /> <span style="color: #aaa;">(Kosongi jika tidak ingin mengubah gambar)</span></td>
</tr>

<tr>
  <td colspan="2" style="background-color:#ffddcc;"> &nbsp;</td>
</tr>
<tr>
  <th>Tanggal Posting (Kosongkan jika ingin langsung di post, bisa digunakan untuk artikel terjadwal)</th>
  <td><input type="text" name="tgl_upload" id="tgl-posting" value="<?php echo $artikel['tgl_upload']?>"></td>
</tr>

</table>
</div>

<div class="ui-layout-south panel bottom">
<div class="left">
<a href="<?php echo site_url()?>web/index/<?php echo $cat?>" class="uibutton icon prev">Kembali</a>
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
