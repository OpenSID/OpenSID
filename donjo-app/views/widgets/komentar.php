<div class="box box-primary box-solid">
	<div class="box-header">
		<h3 class="box-title"><i class="fa fa-comments"></i> Komentar Terbaru</h3>
	</div>
	<div class="box-body">
		<marquee onmouseover="this.stop()" onmouseout="this.start()" scrollamount="2" direction="up" width="100%" height="100" align="center" behavior="alternate">
			<ul class="sidebar-latest" id="li-komentar">
				<?php foreach ($komen as $data): ?>
					<li>
						<i class="fa fa-comment"></i> <?= $data['owner']?><br/>
						<small><?= tgl_indo2($data['tgl_upload'])?></small><br/>
						<?= potong_teks($data['komentar'], 50); ?>...<a href="<?= site_url('artikel/' . buat_slug($data)); ?>">selengkapnya</a>
						<br />
						<br />
					</li>
				<?php endforeach; ?>
			</ul>
		</marquee>
	</div>
</div>
