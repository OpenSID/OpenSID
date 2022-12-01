<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View untuk modul Buku Administrasi Desa > Buku Mutasi Penduduk Desa
 *
 * donjo-app/views/bumindes/penduduk/mutasi/content_mutasi.php,
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

<script>
	$( function() {
		$( "#cari" ).autocomplete( {
			source: function( request, response ) {
				$.ajax( {
					type: "POST",
					url: '<?= site_url('bumindes_penduduk_mutasi/autocomplete'); ?>',
					dataType: "json",
					data: {
						cari: request.term
					},
					success: function( data ) {
						response( JSON.parse( data ));
					}
				} );
			},
			minLength: 2,
		} );
	} );
</script>
<?php if ($tgl_lengkap && $tgl_lengkap_aktif == 1): ?>
	<div class="box box-info">
		<div class="box-header with-border">
			<a href="<?= site_url($this->controller . "/ajax_cetak/{$o}/cetak"); ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Buku Mutasi Penduduk Desa" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Buku Mutasi Penduduk Desa"><i class="fa fa-print "></i> Cetak</a>
			<a href="<?= site_url($this->controller . "/ajax_cetak/{$o}/unduh"); ?>" title="Unduh Buku Mutasi Penduduk Desa" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Buku Mutasi Penduduk Desa" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Buku Mutasi Penduduk Desa"><i class="fa fa-download"></i> Unduh</a>
			<a href="<?= site_url($this->controller . '/clear') ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i>Bersihkan</a>
		</div>
		<div class="box-body">
			<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
				<form id="mainform" name="mainform" action="" method="post">
					<div class="row">
						<div class="col-sm-9">
							<select class="form-control input-sm " name="filter_tahun" onchange="formAction('mainform','<?= site_url($this->controller . '/filter/filter_tahun')?>')">
								<option value="">Tahun</option>
							<?php for ($t = $tahun_lengkap; $t <= date('Y'); $t++): ?>
								<option value=<?= $t ?> <?php selected($tahun, $t); ?>><?= $t ?></option>
							<?php endfor; ?>
							</select>
							<select class="form-control input-sm" name="filter_bulan" onchange="formAction('mainform','<?= site_url($this->controller . '/filter/filter_bulan')?>')" width="100%">
								<option value="">Pilih bulan</option>
							<?php foreach (bulan() as $idx => $nama_bulan): ?>
								<option value="<?= $idx?>" <?php selected($bulan, $idx); ?>><?= $nama_bulan?></option>
							<?php endforeach; ?>
							</select>
						</div>
						<div class="col-sm-3">
							<div class="input-group input-group-sm pull-right">
								<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" title="Pencarian berdasarkan nama penduduk" value="<?=html_escape($cari); ?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= site_url('bumindes_penduduk_mutasi/filter/cari'); ?>');$('#'+'mainform').submit();}">
								<div class="input-group-btn">
									<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= site_url('bumindes_penduduk_mutasi/filter/cari'); ?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
								</div>
							</div>
						</div>
					</div>
					<div class="table-responsive table-min-height">
						<table class="table table-condensed table-bordered dataTable table-striped table-hover tabel-daftar">
							<thead class="bg-gray color-palette">
								<tr>
									<th rowspan="2">Nomor Urut</th>
									<th rowspan="2" style="width: 5px;"><?= url_order($o, "{$this->controller}/{$func}/{$p}", 3, 'Nama Lengkap / Panggilan'); ?></th>
									<th colspan="2">Tempat & Tanggal Lahir</th>
									<th rowspan="2">Jenis Kelamin</th>
									<th rowspan="2">Kewarganegaraan</th>
									<th colspan="2">Penambahan</th>
									<th colspan="4">Pengurangan</th>
									<th rowspan="2">Ket</th>
								</tr>
								<tr>
									<th>Tempat Lahir</th>
									<th>Tanggal</th>
									<th>Datang Dari</th>
									<th>Tanggal</th>
									<th>Pindah Ke</th>
									<th>Tanggal</th>
									<th>Meninggal</th>
									<th>Tanggal</th>
								</tr>
							</thead>
							<tbody>
								<?php if ($main): ?>
									<?php foreach ($main as $key => $data): ?>
										<tr>
											<td class="padat"><?= ($key + $paging->offset + 1); ?></td>
											<td><?= strtoupper($data['nama'])?></td>
											<td><?= $data['tempatlahir']?></td>
											<td><?= tgl_indo_out($data['tanggallahir'])?></td>
											<td><?= strtoupper($data['sex']) ?></td>
											<td><?= $data['warganegara']?></td>
											<td><?= $data['kode_peristiwa'] == 5 ? strtoupper($data['alamat_sebelumnya']) : '-'; ?></td>
											<td><?= $data['kode_peristiwa'] == 5 ? tgl_indo_out($data['created_at']) : '-'; ?></td>
											<td><?= strtoupper($data['kode_peristiwa'] == 3 ? $data['alamat_tujuan'] : '-'); ?></td>
											<td><?= $data['kode_peristiwa'] == 3 ? tgl_indo_out($data['tgl_peristiwa']) : '-'; ?></td>
											<td><?= strtoupper($data['kode_peristiwa'] == 2 ? $data['meninggal_di'] : '-'); ?></td>
											<td><?= $data['kode_peristiwa'] == 2 ? tgl_indo_out($data['tgl_peristiwa']) : '-'; ?></td>
											<?php if (! $data['created_at']): ?>
												<td>Penduduk sudah dihapus.</td>
											<?php else: ?>
												<td><?= $data['catatan'] ? strtoupper($data['catatan']) : '-'; ?></td>
											<?php endif; ?>
										</tr>
									<?php endforeach; ?>
								<?php else: ?>
									<tr>
										<td class="text-center" colspan="13">Data Tidak Tersedia</td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</form>
				<?php $this->load->view('global/paging'); ?>
			</div>
			<?php if ($data_hapus['list_hapus']): ?>
				<div class="row" style="padding-top: 20px;">
					<div class="col-md-12">
						<div class="row">
							<div class="col-sm-8">
								<div class="form-group">
									<label class="col-sm-12 control-label"><h5><strong>BUKU MUTASI PENDUDUK TERHAPUS</strong></h5></label>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Total Hapus</label>
									<div class="col-sm-9">
										<p class="text-muted">: <?= $data_hapus['total']; ?></p>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="table-responsive">
									<table class="table table-bordered">
										<thead class="bg-gray disabled color-palette">
											<tr>
												<th class="text-center">No</th>
												<th class="text-center">NIK</th>
												<th class="text-center">Dihapus Pada</th>
												<th class="text-center">Catatan</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($data_hapus['list_hapus'] as $key => $data): ?>
											<tr>
												<td class="text-center" ><?= $key + 1?></td>
												<td><?= $data['nik']?></td>
												<td><?= tgl_indo($data['deleted_at'])?></td>
												<td><?= $data['catatan'] ?: 'Data dihapus karena salah pengisian.'; ?></td>
											</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
<?php else:

    $this->load->view('bumindes/penduduk/rekapitulasi/data_lengkap', ['judul_rekap' => 'Buku Mutasi Penduduk']);

endif; ?>