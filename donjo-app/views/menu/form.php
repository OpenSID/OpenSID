<style type="text/css">
	th {
		text-align: left;
	}
	td {
		padding-bottom: 5px;
	}
</style>
<script>
$(function(){
    var link = {};
    link.results = [
		<?php foreach($link as $data){?>
	   {id:'artikel/<?php echo $data['id']?>',name:'<?php echo $data['judul']?>',info:'Halaman Berisi <?php echo $data['judul']?>'},
		<?php }?>
	   {id:'gallery',name:'Gallery',info:'Halaman Galeri'},
		    ];
link.total = link.results.length;

$('#link').flexbox(link, {
	resultTemplate: '<div><label>No link : </label>{name}</div><div>{info}</div>',
	watermark: 'Pilih Artikel Statis',
    width: 260,
    noResultsText :'Tidak ada no link yang sesuai..',
	    onSelect: function() {
		$('#'+'main').submit();
    }
});
});
function ganti_jenis_link(jenis){
	$('.jenis_link').hide();
	$('.jenis_link').removeAttr( "name" );
  $('.jenis_link').attr('disabled','disabled');
	if(jenis == '1'){
		$('#link').show();
		$('#link').attr('name', 'link');
    $('#link').removeAttr('disabled');
	} else if(jenis == '2'){
		$('#statistik_penduduk').show();
		$('#statistik_penduduk').attr('name', 'link');
    $('#statistik_penduduk').removeAttr('disabled');
	} else if(jenis == '3'){
		$('#statistik_keluarga').show();
		$('#statistik_keluarga').attr('name', 'link');
    $('#statistik_keluarga').removeAttr('disabled');
	} else if(jenis == '4'){
		$('#statistik_program_bantuan').show();
		$('#statistik_program_bantuan').attr('name', 'link');
    $('#statistik_program_bantuan').removeAttr('disabled');
	} else if(jenis == '5'){
		$('#statistik_lainnya').show();
		$('#statistik_lainnya').attr('name', 'link');
    $('#statistik_lainnya').removeAttr('disabled');
	} else if(jenis == '99'){
		$('#eksternal').show();
		$('#eksternal > input').show();
		$('#eksternal > input').attr('name', 'link');
    $('#eksternal').removeAttr('disabled');
    $('#eksternal > input').removeAttr('disabled');
	}
}
</script>

<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<td class="side-menu">
<fieldset>
<legend>Kategori Menu</legend>
<div class="lmenu">
<ul>
<li <?php if($tip==1)echo "class='selected'";?>><a href="<?php echo site_url("menu/index/1")?>">Menu Statis</a></li>
<li <?php if($tip==2)echo "class='selected'";?>><a href="<?php echo site_url("kategori")?>">Kategori / Menu Dinamis</a></li>


</ul>
</div>
</fieldset>
</td>
<td style="background:#fff;padding:0px;">
<div id="contentpane">
<form id="validasi" action="<?php echo $form_action?>" method="POST">
	<div class="ui-layout-center" id="maincontent" style="padding: 5px;">


	<table style="width:100%">
		<tr>
			<th align="left" width="120">Nama Menu</th>
			<td>
			<input type="text" name="nama" class="inputbox2 required" size="30" value="<?php echo $submenu['nama']?>">
			</td>
		</tr>
		<?php if(!empty($submenu['link'])): ?>
			<tr>
				<th>Link Sebelumnya</th>
				<td>
					<?php echo $submenu['link']; ?>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="padding-bottom: 10px;"><span>Ubah link menjadi -----</span></td>
			</tr>
		<?php endif; ?>
		<tr>
			<th>Jenis Link</th>
			<td>
		    <select name="link_tipe" class="required" onchange="ganti_jenis_link($(this).val());">
		      <option value="">Pilih Jenis Link</option>
          <option value="1" <?php if($submenu['link_tipe']=="1"){?>selected<?php }?>>Artikel Statis</option>
          <option value="2" <?php if($submenu['link_tipe']=="2"){?>selected<?php }?>>Statistik Penduduk</option>
          <option value="3" <?php if($submenu['link_tipe']=="3"){?>selected<?php }?>>Statistik Keluarga</option>
          <option value="4" <?php if($submenu['link_tipe']=="4"){?>selected<?php }?>>Statistik Program Bantuan</option>
          <option value="5" <?php if($submenu['link_tipe']=="5"){?>selected<?php }?>>Statistik Lainnya</option>
          <option value="99" <?php if($submenu['link_tipe']=="99"){?>selected<?php }?>>Eksternal</option>
		    </select>
			</td>
		</tr>
		<tr>
			<th>Link</th>
			<td>
				<div id="link" class="jenis_link" name="<?php if ($submenu['link_tipe']==1) echo 'link'?>" style="<?php if ($submenu['link_tipe']!=1) echo 'display: none;'?>" <?php if ($submenu['link_tipe']!=1) echo 'disabled="disabled"'?>></div>
		    <select id="statistik_penduduk" name="<?php if ($submenu['link_tipe']==2) echo 'link'?>" class="jenis_link required" style="<?php if ($submenu['link_tipe']!=2) echo 'display: none;'?>" <?php if ($submenu['link_tipe']!=2) echo 'disabled="disabled"'?>>
		      <option value="">Pilih Statistik Penduduk</option>
		      <?php foreach ($statistik_penduduk as $id => $nama): ?>
	          <option value="<?php echo $id?>" <?php if($submenu['link']==$id){?>selected<?php }?>><?php echo $nama?></option>
	        <?php endforeach; ?>
		    </select>
		    <select id="statistik_keluarga" name="<?php if ($submenu['link_tipe']==3) echo 'link'?>" class="jenis_link required" style="<?php if ($submenu['link_tipe']!=3) echo 'display: none;'?>" <?php if ($submenu['link_tipe']!=3) echo 'disabled="disabled"'?>>
		      <option value="">Pilih Statistik Keluarga</option>
		      <?php foreach ($statistik_keluarga as $id => $nama): ?>
	          <option value="<?php echo $id?>" <?php if($submenu['link']==$id){?>selected<?php }?>><?php echo $nama?></option>
	        <?php endforeach; ?>
		    </select>
		    <select id="statistik_program_bantuan" name="<?php if ($submenu['link_tipe']==4) echo 'link'?>" class="jenis_link required" style="<?php if ($submenu['link_tipe']!=4) echo 'display: none;'?>" <?php if ($submenu['link_tipe']!=4) echo 'disabled="disabled"'?>>
		      <option value="">Pilih Statistik Program Bantuan</option>
		      <?php foreach ($statistik_program_bantuan as $id => $nama): ?>
	          <option value="<?php echo $id?>" <?php if($submenu['link']==$id){?>selected<?php }?>><?php echo $nama?></option>
	        <?php endforeach; ?>
		    </select>
		    <select id="statistik_lainnya" name="<?php if ($submenu['link_tipe']==5) echo 'link'?>" class="jenis_link required" style="<?php if ($submenu['link_tipe']!=5) echo 'display: none;'?>" <?php if ($submenu['link_tipe']!=5) echo 'disabled="disabled"'?>>
		      <option value="">Pilih Statistik Lainnya</option>
		      <?php foreach ($statistik_lainnya as $id => $nama): ?>
	          <option value="<?php echo $id?>" <?php if($submenu['link']==$id){?>selected<?php }?>><?php echo $nama?></option>
	        <?php endforeach; ?>
		    </select>
		    <span id="eksternal" class="jenis_link" style="<?php if ($submenu['link_tipe']!=99) echo 'display: none;'?>">
			    <input name="<?php if ($submenu['link_tipe']==99) echo 'link'?>" class="jenis_link required"  size="40" value="<?php echo $submenu['link']?>" <?php if ($submenu['link_tipe']!=99) echo 'disabled="disabled"'?>>
			    <span>(misalnya: http://opensid.info)</span>
			  </span>
			</td>
		</tr>
	</table>
	</div>

	<div class="ui-layout-south panel bottom">
		<div class="left">
			<a href="<?php echo site_url()?>menu/index/<?php echo $tip?>" class="uibutton icon prev">Kembali</a>
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
</td></tr></table>
</div>
