<div id="pageC">
	<div id="contentpane">
		<div class="ui-layout-north panel">
			<h3>Layanan Surat Administrasi Kependudukan</h3>
		</div>
		<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
			<div>
			<?php foreach($menu_surat2 AS $data){?>
				<?php
					if ($data['url_surat'] == 'surat_persetujuan_mempelai') :
						$surat_url = site_url()."surat/form/".$data['url_surat']."/clear";
					else:
						$surat_url = site_url()."surat/form/".$data['url_surat'];
					endif;
				?>

			<a class="csurat" href="<?php echo $surat_url?>">
				<img src="<?php echo base_url()?>assets/images/cpanel/applications-office-5.png" alt="sss"/>
				<span><?php echo $data['nama']?></span>
			</a>
			<?php }?>
			</div>
		</div>
	</div>
</div>
