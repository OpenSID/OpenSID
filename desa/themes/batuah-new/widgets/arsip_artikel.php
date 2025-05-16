<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<div class="widget-box bgwhite bordergrey">
	<div class="widget-head bggrad-color2 flexleft">
	    <svg viewBox="0 0 24 24">
		    <path d="M19,20H4C2.89,20 2,19.1 2,18V6C2,4.89 2.89,4 4,4H10L12,6H19A2,2 0 0,1 21,8H21L4,8V18L6.14,10H23.21L20.93,18.5C20.7,19.37 19.92,20 19,20Z" />
	    </svg>
	    <h1><?= $judul_widget ?></h1>
	</div>
	<div class="fortab">
		<div class="fortab-inner">
		    <div class="main">
			    <input id="tab_one" type="radio" name="tabx" checked>
			    <label class="flexcenter bordergrey" for="tab_one">Populer</label>
			    <input id="tab_two" type="radio" name="tabx">
			    <label class="flexcenter bordergrey" for="tab_two" style="border-left:1px solid;">Terbaru</label>
			    <div class="content2">  
				<div id="content2_one">
				<div class="widget-inner" style="padding-top:5px;padding-bottom:5px;">
				<?php foreach (array('populer' => 'arsip_populer') as $jenis => $jenis_arsip) : ?>
				<?php $count = 0;
				foreach ($$jenis_arsip as $arsip): ?>
				<?php if ($count < 5): ?>
				    
					<a href="<?= site_url('artikel/'.buat_slug($arsip))?>">
						<div class="arsip-row">
							<div class="arsip-row-image bordergrey">
						    <?php if (is_file(LOKASI_FOTO_ARTIKEL.'sedang_'.$arsip['gambar'])): ?>
								<img src="<?= base_url(LOKASI_FOTO_ARTIKEL.'sedang_'.$arsip['gambar'])?>"/>
									<?php else: ?>
								<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/pengganti.jpg") ?>"/>
							<?php endif;?>
							</div>
							<div class="arsip-row-title">
							<p><?= hit($arsip['hit']); ?> dibuka<br/><span><?= potong_teks ($arsip['judul'], 60); ?>...</span></p>
							</div>
						</div>
					</a>
				<?php endif;
				$count++;
				endforeach; ?>
				<?php endforeach ?>
				</div>
				</div>
			</div>
			<div class="content2">  
				<div id="content2_two">
				<div class="widget-inner" style="padding-top:5px;padding-bottom:5px;">
				<?php foreach (array('terkini' => 'arsip_terkini') as $jenis => $jenis_arsip) : ?>
				<?php $count = 0;
				foreach ($$jenis_arsip as $arsip): ?>
				<?php if ($count < 5): ?>
					<a href="<?= site_url('artikel/'.buat_slug($arsip))?>">
						<div class="arsip-row">
							<div class="arsip-row-image bordergrey">
							<?php if (is_file(LOKASI_FOTO_ARTIKEL.'sedang_'.$arsip['gambar'])): ?>
								<img src="<?= base_url(LOKASI_FOTO_ARTIKEL.'sedang_'.$arsip['gambar'])?>"/>
							<?php else: ?>
								<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/pengganti.jpg") ?>"/>
							<?php endif;?>
							</div>
							<div class="arsip-row-title">
							<p><?= tgl_indo($arsip['tgl_upload']); ?><br/><span><?= potong_teks ($arsip['judul'], 60); ?>...</span></p>
							</div>
						</div>
					</a>				
				<?php endif;
				$count++;
				endforeach; ?>
				<?php endforeach ?>
				</div>
				</div>
			</div>
		</div>
		</div>
	</div>
</div>