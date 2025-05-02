<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<div class="widget-box bgwhite bordergrey">
	<div class="widget-head bggrad-color2 flexleft">
	<svg viewBox="0 0 24 24">
		<path d="M12,3C17.5,3 22,6.58 22,11C22,15.42 17.5,19 12,19C10.76,19 9.57,18.82 8.47,18.5C5.55,21 2,21 2,21C4.33,18.67 4.7,17.1 4.75,16.5C3.05,15.07 2,13.13 2,11C2,6.58 6.5,3 12,3M17,12V10H15V12H17M13,12V10H11V12H13M9,12V10H7V12H9Z" />
	</svg>
	<h1><?= $judul_widget ?></h1>
	</div>
	<div class="widget-comment">
	<div class="widget-comment-absolute">
		<marquee onmouseover="this.stop()" onmouseout="this.start()" scrollamount="3" direction="up" width="100%" behavior=”alternate”>
		<?php foreach($komen As $data): ?>
			<div class="comment-row">
				<div class="responden flexleft">
					<svg viewBox="0 0 24 24">
						<path d="M20,2H4A2,2 0 0,0 2,4V16A2,2 0 0,0 4,18H8L12,22L16,18H20A2,2 0 0,0 22,16V4A2,2 0 0,0 20,2M12,4.3C13.5,4.3 14.7,5.5 14.7,7C14.7,8.5 13.5,9.7 12,9.7C10.5,9.7 9.3,8.5 9.3,7C9.3,5.5 10.5,4.3 12,4.3M18,15H6V14.1C6,12.1 10,11 12,11C14,11 18,12.1 18,14.1V15Z" />
					</svg>
					<div>
					<h2><?= $data['owner']?></h2>
					<h3><?= tgl_indo2($data['tgl_upload'])?></h3>
					</div>
				</div>
				<a href="<?= site_url('artikel/' . buat_slug($data)); ?>">
				<p><?= potong_teks ($data['komentar'], 100); ?>...</p>
				</a>
			</div>
		<?php endforeach; ?>
		</marquee>
	</div>
	</div>
</div>