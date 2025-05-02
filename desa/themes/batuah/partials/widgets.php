<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="col-default-right">
	<?php if ($w_cos): ?>
		<?php $count = 0;
		foreach ($w_cos as $widget): ?>
		<?php if ($count < 3): ?>
			<?php
				$judul_widget = [
				'judul_widget' => str_replace('Desa', ucwords($this->setting->sebutan_desa), strip_tags($widget['judul']))
				];
			?>
			<?php if ($widget["jenis_widget"] == 1): ?>	
				<?php if ($widget["isi"] == 'keuangan.php' || $widget["isi"] == 'galeri.php' || $widget["isi"] == 'statistik.php' || $widget["isi"] == 'aparatur_desa.php' || $widget["isi"] == 'peta_lokasi_kantor.php' || $widget["isi"] == 'peta_wilayah_desa.php'): ?>
				<?php else : ?>
					<div class="widget-padding"><?php $this->load->view("{$folder_themes}/widgets/{$widget['isi']}", $judul_widget) ?></div>
				<?php endif; ?>
			<?php elseif ($widget["jenis_widget"] == 2): ?>	
				<?php if ($widget["isi"] == 'keuangan.php' || $widget["isi"] == 'galeri.php' || $widget["isi"] == 'statistik.php' || $widget["isi"] == 'aparatur_desa.php' || $widget["isi"] == 'peta_lokasi_kantor.php' || $widget["isi"] == 'peta_wilayah_desa.php'): ?>
				<?php else : ?>
					<div class="widget-padding">
					<?php $this->load->view("../../{$widget['isi']}", $judul_widget) ?>
					</div>
				<?php endif; ?>	
			<?php else : ?>
				<div class="widget-padding">
				<div class="widget-box bgwhite bordergrey1">
					<div class="widget-head bggrad-color2 flexleft">
					<svg viewBox="0 0 24 24">
						<path d="M22,16A2,2 0 0,1 20,18H8C6.89,18 6,17.1 6,16V4C6,2.89 6.89,2 8,2H20A2,2 0 0,1 22,4V16M16,20V22H4A2,2 0 0,1 2,20V7H4V20H16Z" />
					</svg>
					<h1><?= strip_tags($widget['judul']) ?></h1>
					</div>
					<div class="widget-inner">
					<?= html_entity_decode($widget['isi']) ?>
					</div>
				</div>
				</div>
			<?php endif; ?>
		<?php endif;
		$count++;
		endforeach; ?>
	<?php endif; ?>
</div>
<div class="stickyarea">
	<div class="col-default-right">
	<?php if ($w_cos): ?>
		<?php $count = 0;
		foreach ($w_cos as $widget): ?>
		<?php if ($count > 2): ?>
			<?php
				$judul_widget = [
				'judul_widget' => str_replace('Desa', ucwords($this->setting->sebutan_desa), strip_tags($widget['judul']))
				];
			?>
			<?php if ($widget["jenis_widget"] == 1): ?>	
				<?php if ($widget["isi"] == 'keuangan.php' || $widget["isi"] == 'galeri.php' || $widget["isi"] == 'statistik.php' || $widget["isi"] == 'aparatur_desa.php' || $widget["isi"] == 'peta_lokasi_kantor.php' || $widget["isi"] == 'peta_wilayah_desa.php'): ?>
				<?php else : ?>
					<div class="widget-padding"><?php $this->load->view("{$folder_themes}/widgets/{$widget['isi']}", $judul_widget) ?></div>
				<?php endif; ?>
			<?php elseif ($widget["jenis_widget"] == 2): ?>	
				<?php if ($widget["isi"] == 'keuangan.php' || $widget["isi"] == 'galeri.php' || $widget["isi"] == 'statistik.php' || $widget["isi"] == 'aparatur_desa.php' || $widget["isi"] == 'peta_lokasi_kantor.php' || $widget["isi"] == 'peta_wilayah_desa.php'): ?>
				<?php else : ?>
					<div class="widget-padding">
					<?php $this->load->view("../../{$widget['isi']}", $judul_widget) ?>
					</div>
				<?php endif; ?>	
			<?php else : ?>
				<div class="widget-padding">
				<div class="widget-box bgwhite bordergrey1">
					<div class="widget-head bggrad-color2 flexleft">
					<svg viewBox="0 0 24 24">
						<path d="M22,16A2,2 0 0,1 20,18H8C6.89,18 6,17.1 6,16V4C6,2.89 6.89,2 8,2H20A2,2 0 0,1 22,4V16M16,20V22H4A2,2 0 0,1 2,20V7H4V20H16Z" />
					</svg>
					<h1><?= strip_tags($widget['judul']) ?></h1>
					</div>
					<div class="widget-inner">
					<?= html_entity_decode($widget['isi']) ?>
					</div>
				</div>
				</div>
			<?php endif; ?>
		<?php endif;
		$count++;
		endforeach; ?>
	<?php endif; ?>
	</div>
</div>