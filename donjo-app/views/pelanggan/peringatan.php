<div class="content-wrapper">
	<section class="content-header">
		<h1>Info Peringatan</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Peringatan</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="box box-danger">
				<div class="box-header with-border">
					<i class="icon fa fa-ban"></i>
					<h3 class="box-title"><?= $this->session->error_premium ?></h3>
				</div>
				<div class="box-body">
					<?php if ($pesan = $this->session->error_premium_pesan): ?>
						<div class="callout callout-warning">
							<h5><?= $pesan ?></h5>
						</div>
					<?php else: ?>
						<div class="callout callout-danger">
							<h5>Data Gagal Dimuat, Harap Periksa Dibawah Ini</h5>
							<h5>Fitur ini khusus untuk pelanggan Layanan OpenDesa</h5>
							<li>Periksa logs error terakhir di menu <strong><a href="<?= site_url('info_sistem#log_viewer'); ?>" style="text-decoration:none;">Pengaturan > Info Sistem > Logs</a></strong></li>
							<li>Token pelanggan tidak terontentikasi. Periksa [Layanan Opendesa Token] di <a href="#" style="text-decoration:none;" data-remote="false" data-toggle="modal" data-title="Pengaturan <?= ucwords($this->controller); ?>" data-target="#pengaturan"><strong>Pengaturan Pelanggan&nbsp;(<i class="fa fa-gear"></i>)</strong></a></li>
							<li>Jika masih mengalami masalah harap menghubungi pelaksana masing-masing.
						</div>
					<?php endif ?>
				</div>
			</div>
	</section>
</div>