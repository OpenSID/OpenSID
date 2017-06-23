<script type="text/javascript" src="<?php echo base_url()?>assets/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
tinyMCE.init({
        // General options
		mode : "textareas",
		theme : "advanced",
		skin : "o2k7",
        plugins : "autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

        // Theme options
        theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
        theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
        theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
        theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,

        // Skin options
        skin : "o2k7",
        skin_variant : "blue",

        // Example content CSS (should be your site CSS)
        content_css : "css/example.css",

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
<script>
$(function(){
    var nik = {};
    nik.results = [
		<?php foreach($list_penduduk as $data){?>
	   {id:'<?php echo $data['id']?>',name:"<?php echo $data['nik']." - ".($data['nama'])?>",info:"<?php echo ($data['alamat'])?>"},
		<?php }?>
		    ];
nik.total = nik.results.length;

$('#id_ketua').flexbox(nik, {
	resultTemplate: '<div><label>No nik : </label>{name}</div><div>{info}</div>',
	watermark: 'Ketik no nik di sini..',
    width: 260,
    noResultsText :'Tidak ada no nik yang sesuai..',
	    onSelect: function() {
		$('#'+'main').submit();
    }  
});
$("#nik_detail").show();
});
</script>
<div id="pageC">
	<table class="inner">
		<tr style="vertical-align:top">
			<td style="background:#fff;padding:0px;"> 
				<div class="content-header"></div>
				<div id="contentpane">
					<div class="ui-layout-north panel"><h3>Form Master kelompok</h3></div>
					<form id="validasi" action="<?php echo $form_action?>" method="POST" enctype="multipart/form-data">
						<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
							<table class="form">
								<tr>
									<th>Nama kelompok</th>
									<td><input name="nama" type="text" class="inputbox" size="80" value="<?php echo $kelompok['nama']?>"/></td>
								</tr>
								<tr>
									<th width="100">Master Kelompok</th>
									<td>
										<select name="id_master" onchange="formAction('mainform','<?php echo site_url('kelompok/filter')?>')" class="required">	
											<option value="">-- Mater Kelompok --</option>
											<?php  foreach($list_master AS $data){?>
												<option value="<?php echo $data['id']?>" <?php if($kelompok['id_master'] == $data['id']) :?>selected<?php endif?>><?php echo $data['kelompok']?></option>
											<?php  }?>
										</select>
									</td>
								</tr>
								<tr>
									<th>Nama/NIK Pimpinan</th>
									<td>
										<div id="id_ketua" name="id_ketua"></div>
									</td>
								</tr>
								<th colspan="2">Deskripsi kelompok</th>
								</tr>
								<tr>
									<td colspan="2">
										<textarea  name="keterangan" style="width: 800px; height: 500px;">
											<?php echo $kelompok['keterangan']?>
										</textarea>
									</td>
								</tr> 
							</table>
						</div>
						<div class="ui-layout-south panel bottom">
							<div class="left"> 
								<a href="<?php echo site_url()?>kelompok" class="uibutton icon prev">Kembali</a>
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
			</td>
		</tr>
	</table>
</div>
