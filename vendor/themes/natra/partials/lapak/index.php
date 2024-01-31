<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<div class="single_category wow fadeInDown">
	<h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">Lapak</span></h2>
</div>

<div class="box box-primary">
	<div class="box-body">
		<form method="get" class="form-inline text-center" id="filter">
			<div class="row">
				<div class="col-sm-12">
					<select class="form-control select2" id="id_kategori" name="id_kategori" onchange="$('#filter').submit();">
						<option selected value="">Semua Kategori</option>
						<?php foreach ($kategori as $kategori_item) : ?>
							<option value="<?= $kategori_item->id ?>" <?= selected($id_kategori, $kategori_item->id) ?>><?= $kategori_item->kategori ?></option>
						<?php endforeach; ?>
					</select>
					<input type="text" name="keyword" maxlength="50" class="form-control" value="<?= e($keyword); ?>" placeholder="Cari Produk">
					<button type="submit" class="btn btn-primary">Cari</button>
					<?php if (e($keyword)): ?>
						<a href="<?=site_url('lapak')?>" class="btn btn-info">Tampilkan Semua</a>
					<?php endif ?>
				</div>
			</div>
		</form>
		<br/>
		<?php if ($produk): ?>
			<div class="row">
					<?php foreach ($produk as $in => $pro): ?>
						<?php $foto = json_decode($pro->foto); ?>
						<div class="col-md-4">
							<div class="card mb-4 box-shadow">
								<?php if ($pro->foto): ?>
									<div class="slick_slider" style="margin-bottom:5px; max-height: 250px;">
									<?php for ($i = 0; $i < $this->setting->banyak_foto_tiap_produk; $i++): ?>
										<?php if ($foto[$i]): ?>
											<div class="item <?= jecho($i, 0, 'active'); ?>">
												<?php if (is_file(LOKASI_PRODUK . $foto[$i])): ?>
													<div class="single_iteam">
														<img class="tlClogo" src="<?= base_url(LOKASI_PRODUK . $foto[$i]); ?>" alt="Foto <?= ($i+1); ?>">
														<!-- <?= jecho($pro->kategori, true, '<div class="textgambar hidden-xs">' . $pro->kategori . '</div>'); ?> -->
													</div>
												<?php else: ?>
													<img class="card-img-top" style="width: auto; max-height: 250px;" src="<?= asset('images/404-image-not-found.jpg') ?>" alt="Foto Produk"/>
												<?php endif; ?>
											</div>
										<?php endif; ?>
									<?php endfor; ?>
									</div>
								<?php else: ?>
									<img class="card-img-top" style="width: auto; max-height: 250px;" src="<?= asset('images/404-image-not-found.jpg') ?>" alt="Foto Produk"/>
								<?php endif; ?>
								<div class="card-body">
									<!--
										Jika ingin menambahkan badge potongan dalam persen
										<?= persen(($pro->tipe_potongan == 1) ? $pro->potongan/100 : $pro->potongan / $pro->harga); ?>
									-->
									<h4><b><?= $pro->nama; ?></b></h4>
									<?php $harga_potongan = ($pro->tipe_potongan == 1) ? ($pro->harga * ($pro->potongan / 100)) : $pro->potongan; ?>
									<h6><b style="color:green;">Harga : <?= rupiah($pro->harga - $harga_potongan); ?>
										<?php if ($pro->potongan != 0): ?>
											&nbsp;&nbsp;<small style="color:red; text-decoration: line-through red;"><?= rupiah($pro->harga); ?></small>
										<?php endif; ?>
									</b></h6>
									<p class="card-text">
										<b>Deskripsi :</b>
										<br/>
										<?= nl2br(e($pro->deskripsi)); ?>
									</p>
									<div class="d-flex justify-content-between align-items-center">
										<div class="btn-group">
											<?php if ($pro->telepon): ?>
												<?php $pesan = strReplaceArrayRecursive(['[nama_produk]' => $pro->nama, '[link_web]' => base_url('lapak'), '<br />' => "%0A"], nl2br($this->setting->pesan_singkat_wa)); ?>
												<a class="btn btn-sm btn-success" href="https://api.whatsapp.com/send?phone=<?=format_telpon($pro->telepon); ?>&amp;text=<?= $pesan; ?>" rel="noopener noreferrer" target="_blank" title="WhatsApp"><i class="fa fa-whatsapp"></i> Beli</a>
											<?php endif; ?>
											<a class="btn btn-sm btn-primary lokasi-pelapak" data-remote="false" data-toggle="modal" data-target="#map-modal" title="Lokasi" data-lat="<?= $pro->lat?>" data-lng="<?= $pro->lng?>" data-zoom="<?= $pro->zoom?>" data-title="Lokasi Pelapak (<?= $pro->pelapak?>)"><i class="fa fa fa-map"></i> Lokasi</a>
										</div>
										<small class="text-muted"><b><i class="fa fa-user"></i>&nbsp;<?= $pro->pelapak; ?></b></small>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
			</div>

			<style type="text/css">
				.modal-backdrop.in {
					filter: alpha(opacity=50);
					opacity: 0;
				}
			</style>

			<!-- Modal lokasi -->
			<div class='modal fade' id="map-modal" tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
				<div class='modal-dialog'>
					<div class='modal-content'>
						<div class='modal-header'>
							<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
								<h4 class='modal-title'></h4>
						</div>
						<div class="modal-body">
						</div>
					</div>
				</div>
			</div>

			<?php $this->load->view("$folder_themes/commons/page"); ?>

		<?php else: ?>
			<h5>Belum ada produk yang ditawarkan.</h5>
		<?php endif;?>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$(document).on('shown.bs.modal','#map-modal', function(event) {
			let link = $(event.relatedTarget);
			let title = link.data('title');
			let modal = $(this);
			modal.find('.modal-title').text(title);
			modal.find('.modal-body').html("<div id='map' style='width: 100%;'></div>");

			let posisi = [link.data('lat'), link.data('lng')];
			let zoom = link.data('zoom');
			let logo = L.icon({
				iconUrl: "<?= asset('images/gis/point/fastfood.png'); ?>",
			});
			
			$("#lat").val(link.data('lat'));
			$("#lng").val(link.data('lng'));

			var options = {
				maxZoom: <?= setting('max_zoom_peta') ?>,
				minZoom: <?= setting('min_zoom_peta') ?>,
			};

			pelapak = L.map('map', options).setView(posisi, zoom);
			getBaseLayers(pelapak, "<?= setting('mapbox_key') ?>", "<?= setting('jenis_peta') ?>");
			pelapak.addLayer(new L.Marker(posisi, {icon:logo}));
		});
	});
</script>