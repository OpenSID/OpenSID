<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<link type='text/css' href="<?= asset('front/css/slider.css') ?>" rel='Stylesheet' />
<script src="<?= asset('front/js/jquery.cycle2.caption2.min.js') ?>"></script>
<style type="text/css">
	#aparatur_desa .cycle-pager span {
		height: 10px;
		width: 10px;
	}

	.cycle-slideshow {
		max-height: none;
		margin-bottom: 0px;
		border: 0px;
	}

	.cycle-next,
	.cycle-prev {
		mix-blend-mode: difference;
	}
</style>

<!-- widget Aparatur Desa -->
<div class="single_bottom_rightbar">
	<h2 class="box-title">
		<i class="fa fa-user"></i>&ensp;<?= $judul_widget ?>
	</h2>
	<div class="box-body">
		<div class="content_middle_middle">
			<div id="aparatur_desa" class="cycle-slideshow" data-cycle-pause-on-hover=true data-cycle-fx=scrollHorz data-cycle-timeout=2000 data-cycle-caption-plugin=caption2 data-cycle-overlay-fx-out="slideUp" data-cycle-overlay-fx-in="slideDown" data-cycle-auto-height=4:6>
				<?php if ($this->web_widget_model->get_setting('aparatur_desa', 'overlay') == true) : ?>
					<span class="cycle-prev"><img src="<?= asset("images/back_button.png") ?> alt="Back"></span>
					<span class="cycle-next"><img src="<?= asset("images/next_button.png") ?> alt="Next"></span>
					<div class="cycle-caption"></div>
					<div class="cycle-overlay"></div>
				<?php else : ?>
					<span class="cycle-pager"></span> <!-- Untuk membuat tanda bulat atau link pada slider -->
				<?php endif; ?>
				<?php foreach ($aparatur_desa['daftar_perangkat'] as $data) : ?>
					<?php
					$desc = "<span class='cycle-overlay-title'>" . $data['nama'] . "</span>";
					if ($data['kehadiran'] == 1) $desc .= "<span class='label label-success'>" . ($data['status_kehadiran'] == 'hadir' ? 'Hadir' : '') . "</span>"
						. "<span class='label label-danger'>" . (($data['tanggal'] == date('Y-m-d')) && ($data['status_kehadiran'] != 'hadir') ? ucwords($data['status_kehadiran']) : '') . "</span>"
						. "<span class='label label-danger'>" . ($data['tanggal'] != date('Y-m-d') ? 'Belum Rekam Kehadiran' : '') . "</span>";
					?>
					<img data-src="<?= $data['foto'] ?>" src="<?= asset('images/img-loader.gif') ?>" class="yall_lazy" data-cycle-title="<?= $desc ?>" data-cycle-desc="<?= $data['jabatan'] ?>">
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>