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
	if(jenis == '1'){
		$('#link').show();
		$('#link').attr('name', 'link');
	} else if(jenis == '2'){
		$('#statistik_penduduk').show();
		$('#statistik_penduduk').attr('name', 'link');
	} else if(jenis == '3'){
		$('#statistik_keluarga').show();
		$('#statistik_keluarga').attr('name', 'link');
	} else if(jenis == '4'){
		$('#statistik_program_bantuan').show();
		$('#statistik_program_bantuan').attr('name', 'link');
	} else if(jenis == '5'){
		$('#statistik_lainnya').show();
		$('#statistik_lainnya').attr('name', 'link');
	}
}
</script>
<form action="<?php echo $form_action?>" method="post" id="validasi">
	<table style="width:100%">
		<tr>
			<th align="left" width="120">Nama Sub Menu</th>
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
          <option value="5" <?php if($submenu['link_tipe']=="5"){?>selected<?php }?>>Lainnya</option>
		    </select>
			</td>
		</tr>
		<tr>
			<th>Link</th>
			<td>
				<div id="link" class="jenis_link" name="<?php if ($submenu['link_tipe']==1) echo 'link'?>" style="<?php if ($submenu['link_tipe']!=1) echo 'display: none;'?>"></div>
		    <select id="statistik_penduduk" name="<?php if ($submenu['link_tipe']==2) echo 'link'?>" class="jenis_link required" style="<?php if ($submenu['link_tipe']!=2) echo 'display: none;'?>">
		      <option value="">Pilih Statistik Penduduk</option>
		      <?php foreach ($statistik_penduduk as $id => $nama): ?>
	          <option value="<?php echo $id?>" <?php if($submenu['link']==$id){?>selected<?php }?>><?php echo $nama?></option>
	        <?php endforeach; ?>
		    </select>
		    <select id="statistik_keluarga" name="<?php if ($submenu['link_tipe']==3) echo 'link'?>" class="jenis_link required" style="<?php if ($submenu['link_tipe']!=3) echo 'display: none;'?>">
		      <option value="">Pilih Statistik Keluarga</option>
		      <?php foreach ($statistik_keluarga as $id => $nama): ?>
	          <option value="<?php echo $id?>" <?php if($submenu['link']==$id){?>selected<?php }?>><?php echo $nama?></option>
	        <?php endforeach; ?>
		    </select>
		    <select id="statistik_program_bantuan" name="<?php if ($submenu['link_tipe']==4) echo 'link'?>" class="jenis_link required" style="<?php if ($submenu['link_tipe']!=4) echo 'display: none;'?>">
		      <option value="">Pilih Statistik Program Bantuan</option>
		      <?php foreach ($statistik_program_bantuan as $id => $nama): ?>
	          <option value="<?php echo $id?>" <?php if($submenu['link']==$id){?>selected<?php }?>><?php echo $nama?></option>
	        <?php endforeach; ?>
		    </select>
		    <select id="statistik_lainnya" name="<?php if ($submenu['link_tipe']==5) echo 'link'?>" class="jenis_link required" style="<?php if ($submenu['link_tipe']!=5) echo 'display: none;'?>">
		      <option value="">Pilih Statistik Lainnya</option>
		      <?php foreach ($statistik_lainnya as $id => $nama): ?>
	          <option value="<?php echo $id?>" <?php if($submenu['link']==$id){?>selected<?php }?>><?php echo $nama?></option>
	        <?php endforeach; ?>
		    </select>
			</td>
		</tr>
	</table>
	<div class="buttonpane" style="text-align: right; width:400px;position:absolute;bottom:0px;">
    <div class="uibutton-group">
      <button class="uibutton" type="button" onclick="$('#window').dialog('close');"><span class="fa fa-times"></span> Tutup</button>
      <button class="uibutton confirm" type="submit"><span class="fa fa-save"></span> Simpan</button>
    </div>
	</div>
</form>
