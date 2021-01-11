<?php
/*
 * File ini:
 *
 * Views di Modul Layanan Mandiri
 *
 * donjo-app/views/periksa_surat.php
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

 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.

 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package OpenSID
 * @author  Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license http://www.gnu.org/licenses/gpl.html  GPL V3
 * @link  https://github.com/OpenSID/OpenSID
 */
?>

<div class="content-wrapper periksa">
	<section class="content-header">
		<h1>Permohonan Surat</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('permohonan_surat_admin/index/1/0')?>"> Daftar Permohonan Surat</a></li>
			<li class="active">Surat Keterangan</li>
		</ol>
	</section>
	<section class="content periksa">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title">Pemohon</h3>
					</div>
					<div class="box-body">
						<form class="form-horizontal">
							<div class="form-group">
								<label class="control-label col-sm-3">NIK / Nama Penduduk</label>
								<div class="col-sm-9">
									<input class="form-control input-sm" readonly="readonly" value="<?= $individu['nik'].' - '.$individu['nama']; ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-3">Keterangan tambahan</label>
								<div class="col-sm-9">
									<textarea class="form-control input-sm" readonly="readonly" rows="3"><?= $periksa['keterangan'] ?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-3">No HP Aktif</label>
								<div class="col-sm-9">
									<input class="form-control input-sm" readonly="readonly" value="<?= $periksa['no_hp_aktif']?>">
								</div>
							</div>
						</form>
					</div>
				</div>

				<div class="box box-warning collapsed-box">
					<div class="box-header with-border">
						<h3 class="box-title">Periksa persyaratan</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
						</div>
					</div>
					<div class="box-body">
						Periksa setiap dokumen untuk memastikan sesuai dengan persyaratan surat ini.
						Kalau persyaratan belum lengkap:
						<ul>
							<li>Klik tombol Belum Lengkap</li>
							<li>Beritahu pemohon persyaratan mana yang belum lengkap</li>
						</ul>
						<p>Status permohonan akan secara otomatis diubah menjadi 'Belum Lengkap'.</p>
					</div>
				</div>

				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title">Kelengkapan Dokumen</h3>
					</div>
					<div class="box-body">
						<div class="table-responsive">
							<table class="table table-bordered table-striped table-hover tabel-daftar">
								<tr>
									<th>No</th>
									<th>Syarat</th>
									<th>Dokumen Melengkapi Syarat</th>
								</tr>
								<?php if ($syarat_permohonan): ?>
									<?php foreach ($syarat_permohonan as $key => $syarat): ?>
										<tr>
											<td class="padat"><?= ($key + 1); ?></td>
											<td width="40%"><?= $syarat['ref_syarat_nama']?></td>
											<td width="60%">
												<?php if ($syarat['dok_id'] == '-1'): ?>
													<strong class="text-red"><i class="fa fa-exclamation-triangle text-red"></i>Bawa bukti fisik ke Kantor Desa</strong>
												<?php else: ?>
													<a href="<?= site_url("dokumen/unduh_berkas/$syarat[dok_id]/$periksa[id_pemohon]"); ?>" class="btn btn-flat btn-info btn-sm"><i class="fa fa-eye"></i> Cek Dokumen</a>
												<?php endif; ?>
											</td>
										</tr>
									<?php endforeach; ?>
								<?php else: ?>
									<tr>
										<td class="text-center" colspan="9">Data Tidak Tersedia</td>
									</tr>
								<?php endif; ?>
							</table>
						</div>
					</div>
				</div>

				<div class="box box-warning collapsed-box">
					<div class="box-header with-border">
						<h3 class="box-title">Periksa isian form</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
						</div>
					</div>
					<div class="box-body">
						Kalau isian sudah lengkap:
						<ul>
							<li>Klik Ekspor Dok untuk mencetak surat. Lampiran dapat diunduh di Arsip Layanan.</li>
							<li>Berikan surat kepada petugas untuk ditandatangani</li>
						</ul>
						<p>Status permohonan akan secara otomatis diubah menjadi 'Menunggu Tandatangan'.</p>
						Kalau isian belum lengkap:
						<ul>
							<li>Klik tombol Belum Lengkap</li>
							<li>Beritahu pemohon isian mana yang belum lengkap</li>
						</ul>
						<p>Status permohonan akan secara otomatis diubah menjadi 'Belum Lengkap'.</p>
						<textarea id="isian_form" hidden="hidden"><?= $isian_form?></textarea>
					</div>
				</div>
			</div>
			</div>
		</div>
	</section>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="form-surat" id="periksa-permohonan">
			<?php $this->load->view($form_surat); ?>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
	// Di form surat ubah isian admin menjadi disabled
	$("#periksa-permohonan .readonly-periksa").attr('disabled', true);
	setTimeout(function() {isi_form();}, 100);
	});

	function isi_form() {

		var isian_form = JSON.parse($('#isian_form').val(), function(key, value) {

			if (key) {
				var elem = $('*[name=' + key + ']');
				elem.val(value);
				elem.change();
				// Kalau isian hidden, akan ada isian lain untuk menampilkan datanya
				if (elem.is(":hidden"))
				{
					var show = $('#' + key + '_show');
					show.val(value);
					show.change();
				}
			}
		});
	}
</script>
