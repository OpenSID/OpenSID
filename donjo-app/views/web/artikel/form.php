<script type="text/javascript" src="<?php echo base_url()?>assets/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
tinyMCE.init({
        // General options
		mode : "textareas",
		theme : "advanced",
		skin : "o2k7",
        plugins : "autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,anchor,image,insertlayer,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor,|,emotions,iespell,media,advhr,ltr,rtl,|,fullscreen",
		theme_advanced_buttons3 : "",
		theme_advanced_buttons4 : "",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,

        // Skin options
        skin : "o2k7",
        skin_variant : "blue",

        // Example content CSS (should be your site CSS)
        //content_css : "css/example.css",

        // Drop lists for link/image/media/template dialogs
        template_external_list_url : "js/template_list.js",
        external_link_list_url : "js/link_list.js",
        external_image_list_url : "js/image_list.js",
        media_external_list_url : "js/media_list.js",

        // Replace values for the template plugin
        template_replace_values : {
                username : "Some User",
                staffid : "991234"
        }
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
<td><input class="inputbox" type="text" name="judul" value="<?php echo $artikel['judul']?>" size="60"/></td>
</tr>
<tr>
<tr>
<th width="120" colspan="2">Isi Artikel</th>
</tr>
<tr>
<tr>
<td colspan="2">
<textarea  name="isi" style="width: 800px; height: 500px;">
<?php echo $artikel['isi']?>
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
<div class="gallerybox-avatar">
<img src="<?php echo AmbilFotoArtikel($artikel['gambar'],'kecil')?>" alt="" width="200"/>
</div><input type="checkbox" name="gambar_hapus" value="<?php echo $artikel['gambar']?>" /> Hapus Gambar
</td>
</tr>
<?php }?>
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
</div> <input type="checkbox" name="gambar1_hapus"  value="<?php echo $artikel['gambar1']?>"/> Hapus Gambar
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
</div> <input type="checkbox" name="gambar2_hapus"  value="<?php echo $artikel['gambar2']?>"/> Hapus Gambar
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
</div> <input type="checkbox" name="gambar3_hapus" value="<?php echo $artikel['gambar3']?>"/> Hapus Gambar
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
