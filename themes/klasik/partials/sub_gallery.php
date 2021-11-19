<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div style="margin-left: .5em">
	<div class="box box-primary box-solid">
		<div class="box-header">
			<h3 class="box-title">
				<a href="<?= site_url('galeri'); ?>"><i class="fa fa-arrow-left"></i> Galeri Album:
					<?= $parrent['nama'] ?></a>
			</h3>
		</div>
		<div class="box-body">
			<?php if ($gallery): ?>
				<ul class="thumbnail">
					<?php $i = 1 ?>
					<?php foreach ($gallery as $data) : ?>
						<?php if (is_file(LOKASI_GALERI."sedang_{$data['gambar']}")) : ?>
							<li>
								<div class="entry">
									<a href="<?= AmbilGaleri($data['gambar'],'sedang') ?>" class="group2">
										<img src="<?= AmbilGaleri($data['gambar'], 'kecil') ?>" alt="">
									</a>
								</div>
								<div class="title">
									<?= $data['nama'] ?>
								</div>
							</li>
						<?php endif ?>
						<div class="<?php fmod($i, 2) == 0 and print('clearboth') ?>"></div>
						<?php $i++ ?>
					<?php endforeach ?>
				</ul>
			<?php else: ?>
				<p>Data tidak tersedia</p>
			<?php endif; ?>
			<div class="clearboth"></div>
		</div>

		<?php $this->load->view("$folder_themes/commons/page"); ?>
	</div>
</div>