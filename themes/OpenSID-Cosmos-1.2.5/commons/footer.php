<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<footer>
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="logo">
				<img src="<?= LogoDesa($desa['logo']) ?>" alt="<?= ucfirst($this->setting->sebutan_desa).' '.ucwords($desa['nama_desa']) ?>" class="img-fluid">
				</div>
				<div class="detail">
					<span>
						Pemerintah <?= ucfirst($this->setting->sebutan_desa).' '.ucwords($desa['nama_desa']) ?>
					</span>
					<span>
						<?= ucfirst($this->setting->sebutan_kecamatan_singkat) ?>
						<?= ucwords($desa['nama_kecamatan']) ?>
						<?= ucfirst($this->setting->sebutan_kabupaten_singkat) ?>
						<?= ucwords($desa['nama_kabupaten']) ?>,
						<?= ucwords($data_config['nama_propinsi']) ?>,
						Indonesia
					</span>
				</div>
				<div class="col-12 mt-3 mb-0">
					<a href="<?= site_url('siteman') ?>" class="font-weight-bold" style="letter-spacing: 1px">LOGIN ADMIN</a>
				</div>
				<div class="copyright mt-3">
					<span>&copy; <?= date('Y') ?> - Hak Cipta dilindungi Undang-Undang.</span>
					<span>Tema <a href="https://github.com/dikisiswanto/OpenSID-Cosmos/">Cosmos</a> oleh <a href="https://dikisiswanto.github.io">Diki Siswanto</a></span>
					<span>Ditenagai oleh <a href="https://github.com/OpenSID/OpenSID">OpenSID</a> <?= AmbilVersi(); ?> yang dikembangkan oleh <a href="https://www.facebook.com/groups/OpenSID/">Komunitas OpenSID</a></span>
				</div>
			</div>
		</div>
	</div>
</footer>
<div id="kembali-ke-atas">
	<a href="#" class="tombol-ke-atas hide" title="Kembali ke atas">
		<i class="fa fa-arrow-up"></i>
	</a>
</div>