<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * File ini:
 *
 * View halaman lapak pada website
 *
 *
 * donjo-app/views/web/halaman_statis/lapak.php
 *
 */

/**
 *
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
 * @package	OpenSID
 * @author	Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */
?>

<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Lapak Desa</h3>
	</div>
	<div class="box-body">
		<?php if ($produk): ?>
			<div class="row">
				<?php foreach ($produk as $in => $pro): ?>
					<?php $foto = json_decode($pro->foto); ?>
					<div class="col-md-4">
						<div class="card mb-4 box-shadow">
							<?php if ($pro->foto): ?>
								<div class="row">
									<div class="col-md-12">
										<div id="foto-produk-<?= $in; ?>" class="carousel slide" data-ride="carousel">
											<div class="carousel-inner">
												<?php for ($i = 0; $i < $this->setting->banyak_foto_tiap_produk; $i++): ?>
													<?php if ($foto[$i]): ?>
														<div class="item <?= jecho($i, 0, 'active'); ?>">
															<?php if (is_file(LOKASI_PRODUK . $foto[$i])): ?>
																<img src="<?= base_url(LOKASI_PRODUK . $foto[$i]); ?>" alt="Foto <?= ($i+1); ?>">
															<?php else: ?>
																<img class="card-img-top" style="width: auto; max-height: 700px" src="<?= base_url('assets/images/404-image-not-found.jpg') ?>" alt="Foto Produk"/>
															<?php endif; ?>
														</div>
													<?php endif; ?>
												<?php endfor; ?>
											</div>
											<a class="left carousel-control" href="#foto-produk-<?= $in; ?>" data-slide="prev">
												<span class="fa fa-angle-left"></span>
											</a>
											<a class="right carousel-control" href="#foto-produk-<?= $in; ?>" data-slide="next">
												<span class="fa fa-angle-right"></span>
											</a>
										</div>
									</div>
								</div>
							<?php else: ?>
								<img class="card-img-top" style="width: auto; max-height: 700px" src="<?= base_url('assets/images/404-image-not-found.jpg') ?>" alt="Foto Produk"/>
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
									<?= nl2br($pro->deskripsi); ?>
								</p>
								<div class="d-flex justify-content-between align-items-center">
									<div class="btn-group">
										<?php if ($pro->telepon): ?>
											<?php $pesan = strReplaceArrayRecursive(['[nama_produk]' => $pro->nama, '[link_web]' => base_url('lapak'), '<br />' => "%0A"], nl2br($this->setting->pesan_singkat_wa)); ?>
											<a class="btn btn-sm btn-success" href="https://api.whatsapp.com/send?phone=<?=format_telpon($pro->telepon);?>&amp;text=<?= $pesan; ?>" rel="noopener noreferrer" target="_blank" title="WhatsApp"><i class="fa fa-whatsapp"></i> Beli</a>
										<?php endif; ?>
										<a class="btn btn-sm btn-primary lokasi-pelapak" data-remote="false" data-toggle="modal" data-target="#map-modal" title="Lokasi" data-lat="<?= $pro->lat?>" data-lng="<?= $pro->lng?>" data-zoom="<?= $pro->zoom?>" data-title="Lokasi <?= $pro->pelapak?>"><i class="fa fa fa-map"></i> Lokasi</a>
									</div>
									<small class="text-muted"><b><i class="fa fa-user"></i>&nbsp;<?= $pro->pelapak ?? 'ADMIN'; ?></b></small>
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

			<?php
				$paging_page = 'lapak';
				if ($paging->num_rows > $paging->per_page):
			?>
				<div class="box-footer text-center">
					<div>Halaman <?= $paging->page ?> dari <?= $paging->end_link ?></div>
					<ul class="pagination pagination-sm no-margin">
						<?php if ($paging->start_link): ?>
							<li><a href="<?= site_url($paging_page."/$paging->start_link" . $paging->suffix) ?>" title="Halaman Pertama"><i class="fa fa-fast-backward"></i>&nbsp;</a></li>
						<?php endif; ?>
						<?php if ($paging->prev): ?>
							<li><a href="<?= site_url($paging_page."/$paging->prev" . $paging->suffix) ?>" title="Halaman Sebelumnya"><i class="fa fa-backward"></i>&nbsp;</a></li>
						<?php endif; ?>

						<?php foreach ($pages as $i): ?>
							<li <?= jecho($paging->page, $i, 'class="active"'); ?>>
								<a href="<?= site_url($paging_page."/$i" . $paging->suffix) ?>" title="Halaman <?= $i ?>"><?= $i ?></a>
							</li>
						<?php endforeach; ?>

						<?php if ($paging->next): ?>
							<li><a href="<?= site_url($paging_page."/$paging->next" . $paging->suffix) ?>" title="Halaman Selanjutnya"><i class="fa fa-forward"></i>&nbsp;</a></li>
						<?php endif; ?>
						<?php if ($paging->end_link): ?>
							<li><a href="<?= site_url($paging_page."/$paging->end_link" . $paging->suffix) ?>" title="Halaman Terakhir"><i class="fa fa-fast-forward"></i>&nbsp;</a></li>
						<?php endif; ?>
					</ul>
				</div>
			<?php endif; ?>
		<?php else: ?>
			<h5>Belum ada produk yang ditawarkan.</h5>
		<?php endif;?>
	</div>
</div>
<script type="text/javascript">
	var token = "<?= $this->setting->mapbox_key; ?>";

	$(document).ready(function() {
		$(document).on('shown.bs.modal','#map-modal', function(event) {
			var link = $(event.relatedTarget);
			var title = link.data('title');
			var modal = $(this);
			modal.find('.modal-title').text(title);
			modal.find('.modal-body').html("<div id='map' style='width: 100%;'></div>");

			var posisi = [link.data('lat'), link.data('lng')];
			var zoom = link.data('zoom');
			$("#lat").val(link.data('lat'));
			$("#lng").val(link.data('lng'));

			// Inisialisasi tampilan peta
			pelapak = L.map('map').setView(posisi, zoom);

			// Menampilkan BaseLayers Peta
			getBaseLayers(pelapak, token);

			// Tampilkan Posisi Pelapak
			marker = new L.Marker(posisi, {draggable:false});
			pelapak.addLayer(marker);
		});
	});
</script>