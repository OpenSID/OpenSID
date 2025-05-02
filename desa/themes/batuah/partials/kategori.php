<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view("$folder_themes/plus/running"); ?>
<div class="col-default">
	<div class="bodypage bgwhite bordergrey1">
		<div class="center-head bggrad-color2 flexcenter">
			<?php if ($title) : ?>
				<?php if (!empty($judul_kategori)): ?>
					<div class="found-category icon-titlepage bgcolor-1 flexcenter"><svg viewBox="0 0 24 24"><path d="M3,4H7V8H3V4M9,5V7H21V5H9M3,10H7V14H3V10M9,11V13H21V11H9M3,16H7V20H3V16M9,17V19H21V17H9" /></svg></div>
					<div>
					<h2>Kategori</h2>
					<h1><?= $judul_kategori['kategori'] ?></h1>
					</div>
				<?php else: ?>
					<div class="icon-titlepage bgcolor-1 flexcenter"><svg viewBox="0 0 24 24"><path d="M3,4H7V8H3V4M9,5V7H21V5H9M3,10H7V14H3V10M9,11V13H21V11H9M3,16H7V20H3V16M9,17V19H21V17H9" /></svg></div>
					<h1><?= $title ?></h1>
				<?php endif; ?>
			<?php else: ?>
				<div class="icon-titlepage bgcolor-1 flexcenter"><svg viewBox="0 0 56.966 56.966" style="height:16px;"><path d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23 s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92 c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17 s-17-7.626-17-17S14.61,6,23.984,6z" /></svg></div>
				<h1>Hasil Pencarian</h1>
			<?php endif; ?>
		</div>
		<?php if ($artikel): ?>
			<div class="pagebox gridstyle">
			<div class="rowsame margin-minlr-5">
			<?php foreach ($artikel as $data): ?>
				<div class="articlerow-box bggrad-grey1 bordergrey1 forhover">
					<div class="artikelhome-image">
						<div class="image-artikelhome">
							<?php if (is_file(LOKASI_FOTO_ARTIKEL."kecil_".$data['gambar'])): ?>
								<img src="<?= AmbilFotoArtikel($data['gambar'],'kecil') ?>">
							<?php else: ?>
								<img src="<?= base_url("$this->theme_folder/$this->theme/images/pengganti.jpg") ?>"/>
								<div class="logo-no-image"><img src="<?= gambar_desa($desa['logo']);?>"/></div>
							<?php endif;?>
							<div class="hiderow artikel-date flexleft">
								<div class="metadate bgorange flexcenter"><?php echo date('d',strtotime($data['tgl_upload']));?></div>
								<div class="metanext flexleft"><?php echo date('M Y',strtotime($data['tgl_upload']));?></div>
							</div>
							<div class="hoverimage flexcenter">
								<div class="hoverimage-left bgcolor-1 hover-width"></div>
								<div class="hoverimage-right bgcolor-1 hover-width"></div>
								<div>
									<?php if (is_file(LOKASI_FOTO_ARTIKEL."kecil_".$data['gambar'])): ?>
									<a href="<?= AmbilFotoArtikel($data['gambar'],'kecil') ?>"  data-fancybox="images" data-caption="<?= $data["judul"] ?>">
									<div class="hoverimage-icon flexcenter hover-height">
									<svg viewBox="0 0 24 24"><path d="M9.5,13.09L10.91,14.5L6.41,19H10V21H3V14H5V17.59L9.5,13.09M10.91,9.5L9.5,10.91L5,6.41V10H3V3H10V5H6.41L10.91,9.5M14.5,13.09L19,17.59V14H21V21H14V19H17.59L13.09,14.5L14.5,13.09M13.09,9.5L17.59,5H14V3H21V10H19V6.41L14.5,10.91L13.09,9.5Z"/></svg>
									</div>
									</a>
									<?php endif;?>
									<a href="<?= site_url('artikel/'.buat_slug($data))?>">
									<div class="hoverimage-icon flexcenter hover-height">
									<svg viewBox="0 0 24 24"><path d="M20,18H22V6H20M11.59,7.41L15.17,11H1V13H15.17L11.59,16.58L13,18L19,12L13,6L11.59,7.41Z"/></svg>
									</div>
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="artikelhome-text">
						<a href="<?= site_url('artikel/'.buat_slug($data))?>">
						<h3><?= $data["judul"] ?></h3>
						</a>
						<div class="artikelhome-info flexleft">
							<div class="artikelmeta flexleft"><i class="fa fa-user flexleft"></i><p>Admin : <?= $data['owner'] ?></p></div>
						</div>
						<div class="artikelhome-info flexleft">
							<div class="artikelmeta flexleft"><i class="fa fa-eye flexleft"></i><p><?= hit($data['hit']) ?> dibuka</p></div>
							<div class="artikelmeta flexleft"><i class="fa fa-comments flexleft"></i><p><?php $baca_komentar = $this->db->query("SELECT * FROM komentar WHERE id_artikel = '".$data['id']."'"); $komentarku = $baca_komentar->num_rows(); echo number_format($komentarku,0,',','.'); ?> Komentar</p></div>
						</div>
					</div>
					<div class="artikelhome-link bgwhite bordergrey1">
						<div class="rowsame">
							<div class="artikelhome-link-col" style="width:100%;">
							<a href="<?= site_url('artikel/'.buat_slug($data))?>">
								<div class="artikelhome-link-item2 flexcenter">
								<i class="fa fa-external-link flexleft"></i><p>Buka Halaman</p>
								</div>
							</a>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
			</div>
			<?php $this->load->view("$folder_themes/commons/page"); ?>
			</div>	
		<?php else: ?>
			<div class="blankdata">
				<img style="width:100%;height:auto;display:block;border-radius:5px;" src="<?= base_url("$this->theme_folder/$this->theme/images/nature.jpg") ?>"/>
				<div class="blankdata-title">
				<h2>Mohon maaf,</h2>
				<?php if ($title) : ?>
				<?php if (!empty($judul_kategori)): ?>
					<h3>Belum ada data di kategori <?= $judul_kategori['kategori'] ?></h3>
				<?php else: ?>
					<h3>Halaman ini belum tersedia atau sedang dalam perbaikan...</h3>
				<?php endif; ?>
				<?php else: ?>
					<h3>Tidak ditemukan artikel dengan kata pencarian yang anda masukkan...</h3>
				<?php endif; ?>
				</div>
				<div class="blankdata-bottom">
					<img src="<?= base_url("$this->theme_folder/$this->theme/images/girl.png") ?>"/>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>
