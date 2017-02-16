<script>
	$(function(){
		var suami = {};
		suami.results = [
			<?php foreach($laki as $data){?>
			{id:'<?php echo $data['id']?>',name:"<?php echo $data['nik']." - ".($data['nama'])?>",info:"<?php echo ($data['alamat'])?>"},
			<?php }?>
		];

		$('#id_suami').flexbox(suami, {
			resultTemplate: '<div><label>No nik : </label>{name}</div><div>{info}</div>',
			watermark: <?php if($suami){?>'<?php echo $suami['nik']?> - <?php echo spaceunpenetration($suami['nama'])?>'<?php }else{?>'Ketik no nik di sini..'<?php }?>,
			width: 260,
			noResultsText :'Tidak ada no nik yang sesuai..',
			onSelect: function() {
				$('#'+'main').submit();
			}
		});

		var istri = {};
		istri.results = [
			<?php foreach($perempuan as $data){?>
			{id:'<?php echo $data['id']?>',name:"<?php echo $data['nik']." - ".($data['nama'])?>",info:"<?php echo ($data['alamat'])?>"},
			<?php }?>
		];

		$('#id_istri').flexbox(istri, {
			resultTemplate: '<div><label>No nik : </label>{name}</div><div>{info}</div>',
			watermark: <?php if($istri){?>'<?php echo $istri['nik']?> - <?php echo spaceunpenetration($istri['nama'])?>'<?php }else{?>'Ketik no nik di sini..'<?php }?>,
			width: 260,
			noResultsText :'Tidak ada no nik yang sesuai..',
			onSelect: function() {
				$('#'+'main').submit();
			}
		});

	});
</script>

<style>
table.form.detail th{
padding:5px;
background:#fafafa;
border-right:1px solid #eee;
}
table.form.detail td{
padding:5px;
}
</style>
<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<td class="side-menu">
<fieldset>
<legend>Surat Administrasi</legend>
<div  id="sidecontent2" class="lmenu">
<ul>
<?php foreach($menu_surat AS $data){?>
        <li <?php  if($data['url_surat']==$lap){?>class="selected"<?php  }?>><a href="<?php echo site_url()?>surat/<?php echo $data['url_surat']?>"><?php echo unpenetration($data['nama'])?></a></li>
<?php }?>
</ul>
</div>
</fieldset>
</td>
<td style="background:#fff;padding:5px;">
<div class="content-header">

</div>
<div id="contentpane">
<div class="ui-layout-north panel">
<h3>Surat Persetujuan Mempelai (N-3)</h3>
</div>
<div class="ui-layout-center" id="maincontent" style="padding: 5px;" >

	<div id="form-cari-pemohon">
		<form action="<?php echo $surat_url ?>" id="main" name="main" method="POST" class="formular">
		<table class="form">
			<tr>
				<th>DATA SUAMI (Berasal dari desa)			:</th>
			</tr>
			<tr>
				<th width="40%">Nama Suami</th>
				<td width="60%">
					<div id="id_suami" name="id_suami"></div>
					*) Diisi jika suami berasal dari dalam desa
				</td>
			</tr>


			<?php
			if($suami != ''){
				?>
				<tr>
					<th width="40%">Tempat Tanggal Lahir (Umur)</th>
					<td width="60%">
						<?php echo $laki['tempatlahir']?> <?php echo tgl_indo($suami['tanggallahir'])?> (<?php echo $suami['umur']?> Tahun)
					</td>
				</tr>
				<tr>
					<th>Alamat</th>
					<td><?php echo $suami['alamat_wilayah']; ?></td>
				</tr>
				<tr>
					<th>Pendidikan</th>
					<td><?php echo $suami['pendidikan']; ?></td>
				</tr>
				<tr>
					<th>Warganegara / Agama</th>
					<td><?php echo $suami['warganegara']?> / <?php echo $suami['agama']?></td>
				</tr>
			<?php
			}
			?>

			<tr>
				<th>DATA ISTRI (Berasal dari desa)			:</th>
			</tr>
			<tr>
				<th width="40%">Nama Istri</th>
				<td width="60%">
					<div id="id_istri" name="istri" value="10"></div>
					*) Diisi jika istri berasal dari dalam desa
				</td>
			</tr>


			<?php
			if($istri != ''){
				?>
				<tr>
					<th width="40%">Tempat Tanggal Lahir (Umur)</th>
					<td width="60%">
						<?php echo $istri['tempatlahir']?> <?php echo tgl_indo($istri['tanggallahir'])?> (<?php echo $istri['umur']?> Tahun)
					</td>
				</tr>
				<tr>
					<th>Alamat</th>
					<td><?php echo $istri['alamat_wilayah']; ?></td>
				</tr>
				<tr>
					<th>Pendidikan</th>
					<td><?php echo $istri['pendidikan']; ?></td>
				</tr>
				<tr>
					<th>Warganegara / Agama</th>
					<td><?php echo $istri['warganegara']?> / <?php echo $istri['agama']?></td>
				</tr>
			<?php
			}
			?>

		</table>
		</form>
	</div>


<form id="validasi" action="<?php echo $form_action?>" method="POST" target="_blank">
					<input type="hidden" name="id_suami" value="<?php echo $suami['id']?>">
					<input type="hidden" name="id_istri" value="<?php echo $istri['id']?>">
<table class="form">

	<tr>
		<th>Nomor Surat</th>
		<td><input name="nomor" type="text" class="inputbox required" size="30"/></td>
	</tr>
	
	
</table>
</div>

<div class="ui-layout-south panel bottom">
<div class="left">
<a href="<?php echo site_url()?>surat" class="uibutton icon prev">Kembali</a>
</div>
<div class="right">
<div class="uibutton-group">
<button class="uibutton" type="reset">Clear</button>

														<?php if (SuratExport($url)) { ?><button type="button" onclick="$('#'+'validasi').attr('action','<?php echo $form_action2?>');$('#'+'validasi').submit();" class="uibutton confirm"><span class="ui-icon ui-icon-document">&nbsp;</span>Export Doc</button><?php } ?>
</div>
</div>
</div> </form>
</div>
</td></tr></table>
</div>
