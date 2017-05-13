<div id="siteTitles">
	<h1><span id="header_sebutan_desa">
		<?php echo ucwords(config_item('sebutan_desa')." ")?></span>
			<?php echo ucwords(unpenetration($desa['nama_desa']))?>
	</h1>
	<h3><?php echo unpenetration($desa['alamat_kantor'])?> <?php echo ucwords(config_item('sebutan_kecamatan')." ".unpenetration($desa['nama_kecamatan']))?>&nbsp;
		<?php echo ucwords(config_item('sebutan_kabupaten')." ".unpenetration($desa['nama_kabupaten']))?> <?php echo unpenetration($desa['kode_pos'])?>. <?php echo unpenetration($desa['nama_propinsi'])?><br />
		Telepon : <?php echo unpenetration($desa['telepon'])?> - Website : <a href="http://www.hadakewa.desa.id"><?php echo unpenetration($desa['website'])?></a> - Email : <?php echo unpenetration($desa['email_desa'])?>
	</h3>
</div>
