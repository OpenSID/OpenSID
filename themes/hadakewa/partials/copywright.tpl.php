<p>
	<div id="siteTitle">
		<h1>
			<span id="header_sebutan_desa">
				<?php echo ucwords(config_item('sebutan_desa')." ")?>
			</span>
			<?php echo ucwords(unpenetration($desa['nama_desa']))?>
		</h1>
		<h3>
			<?php echo $desa['alamat_kantor']?> <?php echo ucwords(config_item('sebutan_kecamatan')." ".$desa['nama_kecamatan'])?>&nbsp;
			<?php echo ucwords(config_item('sebutan_kabupaten')." ".$desa['nama_kabupaten'])?> <?php echo $desa['kode_pos']?><br />
			Telepon : <?php echo $desa['telepon']?> - Website : <?php echo $desa['website']?> - Email : <?php echo $desa['email_desa']?>
		</h3>
	</div>
</p>
<p>
	&copy; 2006-<?php echo date("Y");?> <a target="_blank" href="https://github.com/eddieridwan/OpenSID">OpenSID</a> <?php echo AmbilVersi()?>. Pengembangan dari SID dibangun <a target="_blank" href="http://www.combine.or.id">COMBINE Resource Institution</a>.</br>
	  Desain tampilan dibuat oleh <a href="https://www.facebook.com/agung.adi.5623" target="_blank">Happy Agung</a>
</p>
