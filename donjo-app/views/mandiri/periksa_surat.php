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

<style type="text/css">
	.content-wrapper.periksa {min-height: 0px !important;}
	div.form-surat .content-wrapper {padding-top: 0px !important;}
	.breadcrumb.admin {display: none;}
	.box-header.admin {display: none;}
	.tdk-periksa {display: none;}
	table#surat {width: auto;}
	.content.periksa
	{
		min-height: 0px !important;
		padding-bottom: 0px;
	}
	div.form-surat .content-header {padding-top: 0px !important;}
</style>

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
			<div class="col-md-7">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title">Pemohon</h3>
					</div>
					<div class="box-body">
						<form class="form-horizontal">
							<div class="form-group">
								<label class="control-label col-sm-3">Nama</label>
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
			</div>
			<div class="col-md-5">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title">Status Kelengkapan Dokumen</h3>
					</div>
					<div class="box-body">
						<div class="table-responsive">
							<table class="table table-striped table-bordered" id="surat">
								<tr>
									<th width="2"><center>No</center></th>
									<th>Syarat</th>
									<th>Dokumen Melengkapi Syarat</th>
								</tr>
								<?php if ($syarat_permohonan): ?>
									<?php $no = 1; foreach ($syarat_permohonan as $syarat): ?>
										<tr>
											<td class="padat"><?= $no;?></td>
											<td width="40%"><?= $syarat['ref_syarat_nama']?></td>
											<td width="60%">
												<?php if ($syarat['dok_id'] == '-1'): ?>
													<strong class="text-red"><i class="fa fa-exclamation-triangle text-red"></i>Bawa bukti fisik ke Kantor Desa</strong>
												<?php else: ?>
													<a href="<?= site_url('dokumen/unduh_berkas/'.$syarat[dok_id].'/'.$periksa[id_pemohon])?>"><?= $syarat['dok_nama']?></a>
												<?php endif; ?>
											</td>
										</tr>
									<?php $no++; endforeach; ?>
								<?php else: ?>
									<tr>
										<td class="text-center" colspan="9">Data Tidak Tersedia</td>
									</tr>
								<?php endif; ?>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<div class="row">
	<div class="col-md-12">
		<textarea id="isian_form" hidden="hidden"><?= $isian_form; ?></textarea>
		<div class="form-surat" id="periksa-permohonan">
			<?php $this->load->view($form_surat); ?>
		</sdiv>
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
