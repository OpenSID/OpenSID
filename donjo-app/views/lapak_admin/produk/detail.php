<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View detail untuk Modul Lapak Admin > Produk
 *
 *
 * donjo-app/views/lapak_admin/produk/detail.php
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

<div class="modal-body">
	<?php if ($main->foto): ?>
		<div class="row">
			<div class="col-md-12">
				<div id="foto-produk" class="carousel slide" data-ride="carousel">
					<div class="carousel-inner">
						<?php $foto = json_decode($main->foto); ?>
						<?php for ($i = 0; $i < $this->setting->banyak_foto_tiap_produk; $i++): ?>
							<?php if ($foto[$i]): ?>
								<div class="item <?= jecho($i, 0, 'active'); ?>">
									<img src="<?= base_url(LOKASI_PRODUK . $foto[$i]); ?>" alt="Foto <?= ($i + 1); ?>">
									<div class="carousel-caption">
										Foto <?= ($i == 0) ? 'Utama' : 'Tambahan'; ?>
									</div>
								</div>
							<?php endif; ?>
						<?php endfor; ?>
					</div>
					<a class="left carousel-control" href="#foto-produk" data-slide="prev">
						<span class="fa fa-angle-left"></span>
					</a>
					<a class="right carousel-control" href="#foto-produk" data-slide="next">
						<span class="fa fa-angle-right"></span>
					</a>
				</div>
			</div>
		</div>
		<hr/>
	<?php endif; ?>
	<div class="form-group">
		<label class="control-label" for="pelapak">Nama Pelapak</label>
		<input name="pelapak" class="form-control input-sm" type="text" value="<?= $main->pelapak; ?>" disabled/>
	</div>
	<div class="form-group">
		<label class="control-label" for="nama">Nama Produk</label>
		<input name="nama" class="form-control input-sm" type="text" value="<?= $main->nama; ?>" disabled/>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label" for="kategori">Kategori Produk</label>
				<input name="kategori" class="form-control input-sm" type="text" value="<?= $main->kategori; ?>" disabled/>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label" for="harga">Harga Produk</label>
				<div class="input-group">
					<span class="input-group-addon input-sm">Rp.</span>
					<input name="harga" class="form-control input-sm" type="text" style="text-align:right;" value="<?= str_replace('Rp.', '', rupiah($main->harga)); ?>" disabled/>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label class="control-label" for="satuan">Satuan Produk</label>
				<input name="satuan" class="form-control input-sm" type="text" value="<?= $main->satuan; ?>" disabled/>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label class="control-label" for="potongan">Potongan Harga</label>
				<?php if ($main->tipe_potongan == 1): ?>
					<div class="input-group">
						<input name="potongan" class="form-control input-sm" type="text" style="text-align:right;" value="<?= $main->potongan; ?>" disabled/>
						<span class="input-group-addon input-sm">%</span>
					</div>
				<?php else: ?>
					<div class="input-group">
						<span class="input-group-addon input-sm">Rp.</span>
						<input name="potongan" class="form-control input-sm" type="text" style="text-align:right;" value="<?= str_replace('Rp.', '', rupiah($main->potongan)); ?>" disabled/>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label" for="kode_desa">Deskripsi Produk</label>
		<textarea name="deskripsi" class="form-control input-sm" rows="5" disabled><?= $main->deskripsi; ?></textarea>
	</div>
</div>
<div class="modal-footer">
	<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm pull-left" data-dismiss="modal"><i class="fa fa-sign-out"></i> Tutup</button>
	<a href="<?= site_url("lapak_admin/produk_form/{$main->id}"); ?>" class="btn btn-social btn-flat bg-orange btn-sm"><i class="fa fa-edit"></i> Ubah</a>
</div>
