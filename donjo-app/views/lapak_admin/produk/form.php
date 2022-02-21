<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View form untuk Modul Lapak Admin > Produk
 *
 *
 * donjo-app/views/lapak_admin/produk/form.php
 *
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

<div class="content-wrapper">
	<section class="content-header">
		<h1>
			PRODUK
			<small><?= $aksi; ?> Data</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('lapak_admin/produk') ?>"></i> Produk</a></li>
			<li class="active"><?= $aksi; ?> Data</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="validasi" action="<?= $form_action; ?>" method="POST" enctype="multipart/form-data">
			<div class="row">
				<div class="col-md-9">
					<div class="box box-info">
						<div class="box-header with-border">
							<a href="<?= site_url("{$this->controller}/produk"); ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Data Produk</a>
						</div>
						<div class="box-body">
							<div class="form-group">
								<label class="control-label" for="kategori">Nama Pelapak</label>
								<select class="form-control input-sm select2 required" name="id_pelapak">
									<option value="">Pilih Nama Pelapak</option>
									<?php foreach ($pelapak as $pel): ?>
										<option value="<?= $pel->id; ?>" <?= selected($main->id_pelapak, $pel->id); ?>><?= $pel->nik . ' - ' . $pel->pelapak; ?></option>
									<?php endforeach ?>
								</select>
							</div>
							<div class="form-group">
								<label class="control-label" for="nama">Nama Produk</label>
								<input name="nama" class="form-control input-sm nama_produk required" type="text" placeholder="Nama Produk" minlength="3" maxlength="100" value="<?= $main->nama; ?>"/>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label" for="kategori">Kategori Produk</label>
										<select class="form-control input-sm select2 required" name="id_produk_kategori">
											<option value="">Pilih Kategori Produk</option>
											<?php foreach ($kategori as $kat): ?>
												<option value="<?= $kat->id; ?>" <?= selected($main->id_produk_kategori, $kat->id); ?>><?= $kat->kategori; ?></option>
											<?php endforeach ?>
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label" for="harga">Harga Produk</label>
										<div class="input-group">
											<span class="input-group-addon input-sm">Rp.</span>
											<input id="harga" name="harga" onkeyup="cek_nominal();" class="form-control input-sm number required" type="number" placeholder="Harga Produk" style="text-align:right;" min="100" max="2000000000" step="100" value="<?= $main->harga; ?>"/>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label" for="satuan">Satuan Produk</label>
										<select class="form-control input-sm select2-tags required" name="satuan">
											<option value="">Pilih Satuan Produk</option>
											<?php foreach ($satuan as $sat): ?>
												<option value="<?= $sat; ?>" <?= selected($main->satuan, $sat); ?>><?= $sat; ?></option>
											<?php endforeach ?>
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<label for="nama">Potongan Harga Produk</label>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<select id="tipe_potongan" name="tipe_potongan" class="form-control input-sm required">
											<option value="1" <?= selected($main->tipe_potongan, 1); ?>>Persen (%)</option>
											<option value="2" <?= selected($main->tipe_potongan, 2); ?>>Nominal (Rp.)</option>
										</select>
									</div>
								</div>
								<div class="col-sm-6" id="tampil-persen" <?= jecho($main->tipe_potongan, 2, 'style="display:none;"'); ?>>
									<div class="form-group">
										<div class="input-group">
											<input type="number" class="form-control input-sm number required" <?= $main->tipe_potongan == 1 ? '' : 'disabled'; ?> id="persen" name="persen" onkeyup="cek_persen();" placeholder="Potongan Persen (%)"  style="text-align:right;" min="0" max="100" step="1" value="<?= $main->potongan ?? 0; ?>"/>
											<span class="input-group-addon input-sm">%</span>
										</div>
									</div>
								</div>

								<div class="col-sm-6" id="tampil-nominal" <?= jecho($main->tipe_potongan, 1, 'style="display:none;"'); ?>>
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon input-sm ">Rp.</span>
											<input type="number" class="form-control input-sm number required" <?= $main->tipe_potongan == 2 ? '' : 'disabled'; ?> id="nominal" name="nominal" onkeyup="cek_nominal();" placeholder="Potongan Nominal (Rp.)" style="text-align:right;" min="0" max="99999999999" step="10" value="<?= $main->potongan ?? 0; ?>"/>
										</div>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label" for="kode_desa">Deskripsi Produk</label>
								<textarea name="deskripsi" class="form-control input-sm required" rows="5"><?= $main->deskripsi; ?></textarea>
							</div>
						</div>
						<div class="box-footer">
							<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
							<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Foto Produk</h3>
							<div class="box-tools">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
							</div>
						</div>
						<div class="box-body">
							<center>
							<?php $foto = json_decode($main->foto); ?>
							<?php for ($i = 0; $i < $this->setting->banyak_foto_tiap_produk; $i++): ?>
								<b>Foto <?= ($i == 0) ? 'Utama' : 'Tambahan'; ?></b>
								<?php $ii = $i + 1; ?>
								<div class="form-group">
									<?php if (is_file(LOKASI_PRODUK . $foto[$i])): ?>
										<img class="img-responsive" src="<?= base_url(LOKASI_PRODUK . $foto[$i]); ?>" alt="Foto Produk">
									<?php else: ?>
										<img class="img-responsive" src="<?= base_url('assets/images/404-image-not-found.jpg') ?>" alt="Foto Produk"/>
									<?php endif; ?>
									<div class="input-group input-group-sm">
										<input type="hidden" name="old_foto_<?= $ii; ?>" value="<?= $foto[$i]; ?>">
										<input type="text" class="form-control" id="file_path<?= $ii; ?>">
										<input type="file" class="hidden" id="file<?= $ii; ?>" name="foto_<?= $ii; ?>">
										<span class="input-group-btn">
											<button type="button" class="btn btn-info btn-flat" id="file_browser<?= $ii; ?>"><i class="fa fa-search"></i></button>
										</span>
										<span class="input-group-addon" style="background-color: red; border: 1px solid #ccc;">
											<input type="checkbox" title="Centang Untuk Hapus Foto" name="hapus_foto_<?= $ii; ?>" value="hapus">
										</span>
									</div>
								</div>
								<hr/>
							<?php endfor; ?>
							</center>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
<script type="text/javascript">
	/**
	 * Tipe Potongan
	 * 1 = Persen
	 * 2 = Nominal
	 */
	$( document ).ready(function() {

		$('#tipe_potongan').change();

		$('#tipe_potongan').on('change', function() {
			if (this.value == 2) {
				$('#tampil-persen').hide();
				$('#tampil-nominal').show();
				$('#nominal').addClass('required');
				$('#persen').removeClass('required');
				$('#nominal').removeAttr("disabled");
				cek_nominal();
			} else {
				$('#tampil-nominal').hide();
				$('#tampil-persen').show();
				$('#persen').addClass('required');
				$('#nominal').removeClass('required');
				$('#persen').removeAttr("disabled");
				cek_persen();
			}
		});
	});

	function cek_persen() {
		if (parseInt($('#persen').val()) > 100) {
			$('#persen').val(100);
		}
	}

	function cek_nominal() {
		if (parseInt($('#nominal').val()) > parseInt($('#harga').val())) {
			$('#nominal').val($('#harga').val());
		}
	}
</script>