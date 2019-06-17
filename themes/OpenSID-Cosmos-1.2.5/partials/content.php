<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div id="main-content">
	<?php 
		if ((empty($_GET['cari'])) && ((count($slide_galeri)>0 || count($slide_artikel)>0)) AND $this->uri->segment(2) != 'kategori') {
			$this->load->view($folder_themes .'/partials/slider.php');
		}
	?>
	<section id="artikel-area" class="py-3">
		<div class="container">
			<?php if(empty($_GET['cari']) AND $headline AND $this->uri->segment(2) != 'kategori') : ?>
			<?php $abstrak_headline = potong_teks($headline['isi'], 250); ?>
			<?php $url = site_url("first/artikel/$headline[id]"); ?>
				<div class="col-12 mt-2 px-0">
					<div class="headline">
						<div class="jumbotron jumbotron-fluid">
							<div class="d-flex justify-content-between">
								<div class="headline-berita">
								<h2 class="judul-artikel"><a href="<?= $url ?>"><?= $headline['judul'] ?></a></h2>
									<p><?= $abstrak_headline ?></p>
								</div>
								<?php if(is_file(LOKASI_FOTO_ARTIKEL .'kecil_'.$headline['gambar'])) : ?>
									<div class="headline-gambar d-none d-lg-block">
										<img src="<?= AmbilFotoArtikel($headline['gambar'], 'kecil') ?>" alt="<?= $headline['judul'] ?>" title="<?= $headline['judul'] ?>" class="img-fluid">
									</div>
								<?php endif ?>
							</div>
						</div>
					</div>
				</div>
			<?php endif ?>
			
			<?php 
			$title = (!empty($judul_kategori))? $judul_kategori: 'Artikel Terkini';
				if(is_array($title)){
					foreach($title as $item){
						$title= $item;
					}
				}
			?>
			<h3 class="h3 py-5 mt-2 text-center"><?= $title ?></h3>
			<div class="article-content-wrapper">
				<div class="col-12 px-0">
					<div class="row mb-5 mx-auto justify-content-center" id="article-content-wrapper">
						<?php if($artikel) : ?>
							<?php foreach($artikel as $data) : ?>
								<?php $url = site_url('first/artikel/'.$data['id']) ?>
								<?php $abstrak = potong_teks($data['isi'], 180) ?>
								<div class="col-lg-4 col-md-6 col-sm-12 mb-4">
									<div class="card">
											<div class="card-image">
												<a href="<?= $url ?>">
													<?php if($data['gambar'] && is_file(LOKASI_FOTO_ARTIKEL.'kecil_'.$data['gambar'])) : ?>
													<img src="<?= AmbilFotoArtikel($data['gambar'],'kecil') ?>" alt="<?= $data['judul'] ?>" class="img-fluid">
														<?php else : ?>
														<img src="<?= base_url('assets/images/404-image-not-found.jpg') ?>" alt="" class="img-fluid">
													<?php endif ?>
													<div class="flash"></div>
												</a>
											</div>
										<div class="card-body">
											<?php if($data['kategori'] && trim($data['kategori'] != '')) : ?>
												<a href="<?= site_url('first/kategori/' . $data['id_kategori']) ?>">
													<span class="kategori-berita"><?= $data['kategori'] ?></span>
												</a>
											<?php endif ?>
											<a href="<?= $url ?>">
												<div class="judul-berita">
													<?= $data['judul'] ?>
												</div>
											</a>
											<p>
												<?= $abstrak ?>...
											</p>
										</div>
										<div class="card-footer">
											<div class="meta-berita">
												<span>
													<i class="fa fa-calendar-alt"></i>
													<?= tgl_indo($data['tgl_upload']) ?>
												</span>
												<span>
													<i class="fa fa-user"></i>
													<?= $data['owner'] ?>
												</span>
											</div>
										</div>
									</div>
								</div>
							<?php endforeach ?>
							<?php else : ?>
								<div class="col-12 text-center pt-3 pb-5 d-flex flex-column">
									<p>Belum ada artikel yang dituliskan dalam <strong><?= $title ?></strong>.</p>
									<p>Silakan kunjungi situs web kami dalam waktu dekat.</p>
									</p>
								</div>
						<?php endif ?>
				</div>
			</div>
			<?php if($artikel) : ?>
				<?php $this->load->view($folder_themes .'/commons/paging') ?>
			<?php endif ?>
		</div>
	</section>
	<section id="telusuri-by">
		<div class="container">
			<div class="kolom-telusuri">
				<div class="item-telusuri berita">
					<a href="<?= site_url('first/kategori/1') ?>" title="Berita Desa">
						<i class="fa fa-newspaper"></i>
						<span>Berita</span>
					</a>
				</div>
				<div class="item-telusuri agenda">
					<a href="<?= site_url('first/kategori/4') ?>" title="Agenda Desa">
						<i class="fa fa-calendar-alt"></i>
						<span>Agenda</span>
					</a>
				</div>
				<div class="item-telusuri peraturan">
					<a href="<?= site_url('first/kategori/5') ?>" title="Peraturan Desa">
						<i class="fa fa-gavel"></i>
						<span>Peraturan</span>
					</a>
				</div>
			</div>
		</div>
	</section>
	<?php $this->load->view($folder_themes .'/partials/mandiri/mandiri_login') ?>
	<?php if($w_gal) : ?>
		<section id="galeri-foto">
			<div class="container">
				<div class="galeri-header">
					<h3 class="h3"><i class="fa fa-camera"></i> GALERI FOTO</h3>
						<span>
							<a href="<?= site_url('first/gallery') ?>" class="btn btn-sm btn-lihat-semua">Selengkapnya <i class="fa fa-angle-right"></i></a>
						</span>
				</div>
				<div class="galeri-wrapper">
					<?php foreach(array_slice($w_gal, 0, 8) as $data) : ?>
						<?php if(is_file(LOKASI_GALERI . "kecil_" . $data['gambar'])) : ?>
							<?php $link = site_url('first/sub_gallery/'.$data['id']) ?>
							<a href="<?= $link ?>" class="item-foto">
								<img src="<?= AmbilGaleri($data['gambar'],'kecil') ?>" alt="<?= $data['nama'] ?>" class="img-fluid" title="<?= $data['nama'] ?>">
							</a>
						<?php endif ?>
					<?php endforeach ?>
				</div>
			</div>
		</section>
	<?php endif ?>
	<?php if(count($menu_atas[nested_array_search(24, $menu_atas)]['submenu'])>0) : ?>
		<section id="akses-data">
			<div class="container">
				<div class="col-12 px-0">
					<h3 class="h3"><i class="fa fa-database"></i> DATA STATISTIK</h3>
					<div class="owl-carousel owl-theme" id="akses-data-wrapper">
						
						<?php foreach($menu_atas[nested_array_search(24, $menu_atas)]['submenu'] as $data) : ?>
							<div class="item">
								<a href="<?= $data['link'] ?>">
									<div class="layer"></div>
									<div class="jenis-akses-data">
										<span><?= strtoupper($data['nama']) ?></span>
									</div>
								</a>
							</div>
						<?php endforeach ?>	
					</div>
				</div>
			</div>
		</section>
	<?php endif ?>
</div>

