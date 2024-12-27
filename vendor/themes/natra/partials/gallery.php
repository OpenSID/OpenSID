<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<div class="single_category wow fadeInDown">
	<h2><span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">Galeri <?= $desa["nama_desa"] ?></span></h2>
</div>

<div style="content_left">
	<div class="row">
		<?php if ($gallery): ?>
			<?php 
				$jumlah = 0;
				foreach ($gallery as $data): ?>
				<?php if (file_exists(LOKASI_GALERI . "sedang_" . $data['gambar']) || $data['jenis'] == 2): 
					$gambar = $data['jenis'] == 2 ? $data['gambar'] : AmbilGaleri($data['gambar'], 'kecil'); 
					$jumlah++;
					?>
					<a href="<?= site_url("galeri/{$data['id']}") ?>">
						<div class="col-sm-6">
							<div class="card">
								<img width="auto" class="img-fluid img-thumbnail" src="<?= $gambar ?>" alt="<?= $data['nama']; ?>"/>
								<p align="center"><b>Album : <?= $data['nama']; ?></b></p>
								<hr/>
							</div>
						</div>
					</a>
				<?php endif ?>
			<?php endforeach ?>
			
		<?php if ($jumlah == 0): ?>
			<div class="alert alert-danger" role="alert">
				Data tidak tersedia
			</div>
		<?php endif ?>
	</div>


	<?php $this->load->view("$folder_themes/commons/page"); ?>

	<?php else: ?>
		<div class="alert alert-danger" role="alert">
			Data tidak tersedia
		</div>
	<?php endif; ?>
</div>