<div class="col-md-3">
	<div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title">Jenis Dokumen</h3>
			<div class="box-tools">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			</div>
		</div>
		<div class="box-body no-padding">
			<ul class="nav nav-pills nav-stacked">
				<li <?php if ($_SESSION['submenu'] == "Dokumen Umum"): ?>class="active"<?php endif; ?>><a href="<?= site_url('dokumen_sekretariat/produk_hukum/1')?>">Dokumen Umum</a></li>
				<li <?php if ($_SESSION['submenu'] == "SK Kades"): ?>class="active"<?php endif; ?>><a href="<?= site_url('dokumen_sekretariat/produk_hukum/2')?>">SK Kades</a></li>
				<li <?php if ($_SESSION['submenu'] == "Perdes"): ?>class="active"<?php endif; ?>><a href="<?= site_url('dokumen_sekretariat/produk_hukum/3')?>">Perdes</a></li>
				<li <?php if ($_SESSION['submenu'] == "Perades"): ?>class="active"<?php endif; ?>><a href="<?= site_url('dokumen_sekretariat/produk_hukum/4')?>">Perades</a></li>
				<li <?php if ($_SESSION['submenu'] == "Perkades"): ?>class="active"<?php endif; ?>><a href="<?= site_url('dokumen_sekretariat/produk_hukum/5')?>">Perkades</a></li>
				<li <?php if ($_SESSION['submenu'] == "Perakades"): ?>class="active"<?php endif; ?>><a href="<?= site_url('dokumen_sekretariat/produk_hukum/6')?>">Perakades</a></li>
			</ul>
		</div>
	</div>
</div>