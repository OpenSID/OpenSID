<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View modul Layanan Mandiri > Lapak
 *
 * donjo-app/views/fmandiri/lapak.php
 */

/*
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @copyright	  Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	  Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 *
 * @see 	https://github.com/OpenSID/OpenSID
 */
?>

<div class="box box-solid">
	<div class="box-header with-border bg-aqua">
		<h4 class="box-title">Lapak</h4>
	</div>
	<div class="box-header with-border">
		<form method="get" class="form-inline text-center">
			<select class="form-control" id="id_kategori" name="id_kategori">
				<option selected value="">Semua Kategori</option>
				<?php foreach ($kategori as $kategori_item) : ?>
					<option value="<?= $kategori_item->id ?>" <?= selected($id_kategori, $kategori_item->id) ?>><?= $kategori_item->kategori ?></option>
				<?php endforeach; ?>
			</select>
			<input type="text" name="keyword" maxlength="50" class="form-control" value="<?= $keyword; ?>" placeholder="Cari Produk">
			<button type="submit" class="btn btn-primary">Cari</button>
			<?php if ($keyword) : ?>
				<a href="<?= site_url('/layanan-mandiri/lapak') ?>" class="btn btn-info">Tampilkan Semua</a>
			<?php endif ?>
		</form>
	</div>
	<div class="box-body">
		<?php if ($produk) : ?>
			<div class="row" style="padding: 0px 20px;">
				<?php foreach ($produk as $in => $pro) : ?>
					<?php $foto = json_decode($pro->foto, null); ?>
					<div class="col-md-4">
						<div class="card mb-4 box-shadow">
							<?php if ($pro->foto) : ?>
								<div id="carousel-produk<?= ($in); ?>" class="carousel slide" data-ride="carousel">
									<ol class="carousel-indicators">
										<?php for ($i = 0; $i < $this->setting->banyak_foto_tiap_produk; $i++) : ?>
											<?php if ($foto[$i]) : ?>
												<li data-target="#carousel-produk<?= ($in); ?>" data-slide-to="<?= ($i); ?>" class="<?= jecho($i, 0, 'active'); ?>"></li>
											<?php endif; ?>
										<?php endfor; ?>
									</ol>

									<div class="carousel-inner">
										<?php for ($i = 0; $i < $this->setting->banyak_foto_tiap_produk; $i++) : ?>
											<?php if ($foto[$i]) : ?>
												<div class="item <?= jecho($i, 0, 'active'); ?>">
													<?php if (is_file(LOKASI_PRODUK . $foto[$i])) : ?>
														<img class="image-produk card-img-top" src="<?= base_url(LOKASI_PRODUK . $foto[$i]); ?>" alt="Produk <?= ($i + 1); ?>">
														<!-- <?= jecho($pro->kategori, true, '<div class="textgambar hidden-xs">' . $pro->kategori . '</div>'); ?> -->
													<?php else : ?>
														<img class="card-img-top" style="width: auto; max-height: 170px;" src="<?= asset('images/404-image-not-found.jpg') ?>" alt="Foto Produk" />
													<?php endif; ?>
												</div>
											<?php endif; ?>
										<?php endfor; ?>
									</div>
									<a class="left carousel-control" href="#carousel-produk<?= ($in); ?>" data-slide="prev">
										<span class="fa fa-angle-left"></span>
									</a>
									<a class="right carousel-control" href="#carousel-produk<?= ($in); ?>" data-slide="next">
										<span class="fa fa-angle-right"></span>
									</a>
								</div>
							<?php else : ?>
								<img class="card-img-top" style="width: auto; max-height: 170px;" src="<?= asset('images/404-image-not-found.jpg') ?>" alt="Foto Produk" />
							<?php endif; ?>

							<div class="card-body">
								<!--
									Jika ingin menambahkan badge potongan dalam persen
									<?= persen(($pro->tipe_potongan == 1) ? $pro->potongan / 100 : $pro->potongan / $pro->harga); ?>
									-->
								<h4><b><?= $pro->nama; ?></b></h4>
								<?php $harga_potongan = ($pro->tipe_potongan == 1) ? ($pro->harga * ($pro->potongan / 100)) : $pro->potongan; ?>
								<h6><b style="color:green;">Harga : <?= rupiah($pro->harga - $harga_potongan); ?>
										<?php if ($pro->potongan != 0) : ?>
											&nbsp;&nbsp;<small style="color:red; text-decoration: line-through red;"><?= rupiah($pro->harga); ?></small>
										<?php endif; ?>
									</b></h6>
								<!-- <p class="card-text">
									<b>Deskripsi:</b>
									<br />
									<?= nl2br($pro->deskripsi); ?>
								</p> -->
								<div class="d-flex justify-content-between align-items-center" style="margin-bottom: 30px;">
									<div class="btn-group">
										<?php if ($pro->telepon) : ?>
											<?php $pesan = strReplaceArrayRecursive(['[nama_produk]' => $pro->nama, '[link_web]' => base_url('lapak'), '<br />' => '%0A'], nl2br($this->setting->pesan_singkat_wa)); ?>
											<a class="btn btn-sm btn-success" href="https://api.whatsapp.com/send?phone=<?= format_telpon($pro->telepon); ?>&amp;text=<?= $pesan; ?>" rel="noopener noreferrer" target="_blank" title="WhatsApp"><i class="fa fa-whatsapp"></i> Beli</a>
										<?php endif; ?>
										<a class="btn btn-sm btn-warning lokasi-pelapak" data-remote="false" data-toggle="modal" data-target="#map-modal" title="Lokasi" data-lat="<?= $pro->lat ?>" data-lng="<?= $pro->lng ?>" data-zoom="<?= $pro->zoom ?>" data-title="Lokasi Pelapak (<?= $pro->pelapak ?>)"><i class="fa fa fa-map"></i> Lokasi</a>
										<a class="btn btn-sm btn-primary text-white" data-remote="false" data-toggle="modal" data-target="#descModal<?= ($in); ?>" title="Deskripsi"><i class="fa fa-info-circle"></i> Deskripsi</a>
									</div>
									<!-- <small class="text-muted"><b><i class="fa fa-user"></i>&nbsp;<?= $pro->pelapak ?? 'ADMIN'; ?></b></small> -->
								</div>
							</div>
						</div>
					</div>

					<!-- Modal Deskripsi -->
					<div class='modal fade' id="descModal<?= ($in); ?>" tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
						<div class='modal-dialog box-map'>
							<div class='modal-content-map'>
								<div class='modal-header'>
									<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
									<h4 class='modal-title'><b><?= $pro->nama; ?></b></h4>
								</div>
								<div class="modal-body">
									<p class="card-text" style="margin-bottom: 25px;">
										<b>Deskripsi:</b>
										<br />
										<?= nl2br($pro->deskripsi); ?>
									</p>

									<p>
										<b><i class="fa fa-user"></i>&nbsp;<?= $pro->pelapak ?? 'ADMIN'; ?></b><br />
										<b>Kontak:</b>&nbsp;<?= $pro->telepon ?? 'ADMIN'; ?>
									</p>
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
				<div class='modal-dialog box-map'>
					<div class='modal-content-map'>
						<div class='modal-header'>
							<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
							<h4 class='modal-title'></h4>
						</div>
						<div class="modal-body">
						</div>
					</div>
				</div>
			</div>

			<?php if ($paging->num_rows > $paging->per_page) : ?>
				<div class="pagination_area text-center">
					<div>Halaman <?= $paging->page ?> dari <?= $paging->end_link ?></div>
					<ul class="pagination">
						<?php if ($paging->start_link) : ?>
							<li><a href="<?= site_url("{$paging_page}/{$paging->start_link}" . $paging->suffix); ?>" title="Halaman Pertama"><i class="fa fa-fast-backward"></i>&nbsp;</a></li>
						<?php endif; ?>
						<?php if ($paging->prev) : ?>
							<li><a href="<?= site_url("{$paging_page}/{$paging->prev}" . $paging->suffix); ?>" title="Halaman Sebelumnya"><i class="fa fa-backward"></i>&nbsp;</a></li>
						<?php endif; ?>
						<?php for ($i = $paging->start_link; $i <= $paging->end_link; $i++) : ?>
							<li class="<?php if ($paging->page == $i) {
                                echo 'active';
                            } ?>"><a href="<?= site_url("{$paging_page}/{$i}" . $paging->suffix); ?>" title="<?= 'Halaman ' . $i ?>"><?= $i ?></a></li>
						<?php endfor; ?>
						<?php if ($paging->next) : ?>
							<li><a href="<?= site_url("{$paging_page}/{$paging->next}" . $paging->suffix); ?>" title="Halaman Selanjutnya"><i class="fa fa-forward"></i>&nbsp;</a></li>
						<?php endif; ?>
						<?php if ($paging->end_link) : ?>
							<li><a href="<?= site_url("{$paging_page}/{$paging->end_link}" . $paging->suffix); ?>" title="Halaman Terakhir"><i class="fa fa-fast-forward"></i>&nbsp;</a></li>
						<?php endif; ?>
					</ul>
				</div>
			<?php endif; ?>

		<?php else : ?>
			<h5>Belum ada produk yang ditawarkan.</h5>
		<?php endif; ?>
	</div>
</div>

<script src="<?= asset('js/mapbox-gl.js'); ?>"></script>
<script src="<?= asset('js/leaflet.js'); ?>"></script>
<script src="<?= asset('js/leaflet-providers.js'); ?>"></script>
<script src="<?= asset('js/leaflet-mapbox-gl.js'); ?>"></script>
<script src="<?= asset('js/peta.js')?>"></script>
<script type="text/javascript">
	$(document).ready(function() {
		var MAPBOX_KEY = '<?= setting('mapbox_key') ?>';
		var JENIS_PETA = '<?= setting('jenis_peta') ?>';


		$(document).on('shown.bs.modal', '#map-modal', function(event) {
			let link = $(event.relatedTarget);
			let title = link.data('title');
			let modal = $(this);
			modal.find('.modal-title').text(title);
			modal.find('.modal-body').html("<div id='map' style='width: 100%; height:300px;'></div>");

			let posisi = [link.data('lat'), link.data('lng')];
			let zoom = link.data('zoom');
			let logo = L.icon({
				iconUrl: "<?= asset('images/gis/point/fastfood.png'); ?>",
			});

			$("#lat").val(link.data('lat'));
			$("#lng").val(link.data('lng'));

			pelapak = L.map('map', options).setView(posisi, zoom);
			getBaseLayers(pelapak, MAPBOX_KEY, JENIS_PETA);

			pelapak.addLayer(new L.Marker(posisi, {
				icon: logo
			}));
		});
	});
</script>