<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View untuk modul Buku Administrasi Desa > Buku Penduduk Sementara
 *
 * donjo-app/views/bumindes/penduduk/induk/content_sementara.php,
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
					url: '<?= site_url('bumindes_penduduk_sementara/autocomplete'); ?>',
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
<div class="box box-info">
	<div class="box-header with-border">
		<a href="<?= site_url("bumindes_penduduk_sementara/ajax_cetak/{$o}/cetak"); ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Buku Penduduk Sementara" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Buku Penduduk Sementara"><i class="fa fa-print "></i> Cetak</a>
		<a href="<?= site_url("bumindes_penduduk_sementara/ajax_cetak/{$o}/unduh"); ?>" title="Unduh Buku Penduduk Sementara" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Buku Penduduk Sementara" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Buku Penduduk Sementara"><i class="fa fa-download"></i> Unduh</a>
		<a href="<?= site_url($this->controller . '/clear') ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i>Bersihkan</a>
	</div>
	<div class="box-body">
		<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
			<form id="mainform" name="mainform" action="" method="post">
				<div class="row">
					<div class="col-sm-9">
						<select class="form-control input-sm " name="filter_tahun" onchange="formAction('mainform','<?= site_url($this->controller . '/filter/filter_tahun')?>')">
							<option value="">Tahun</option>
						<?php foreach ($list_tahun as $l_tahun): ?>
							<option value="<?= $l_tahun['tahun']?>" <?php selected($tahun, $l_tahun['tahun']); ?>><?= $l_tahun['tahun']?></option>
						<?php endforeach; ?>
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
							<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" title="Pencarian berdasarkan nama penduduk" value="<?=html_escape($cari); ?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= site_url('bumindes_penduduk_sementara/filter/cari'); ?>');$('#'+'mainform').submit();}">
							<div class="input-group-btn">
								<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= site_url('bumindes_penduduk_sementara/filter/cari'); ?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
							</div>
						</div>
					</div>
				</div>
				<div class="table-responsive table-min-height">
					<table class="table table-condensed table-bordered dataTable table-striped table-hover tabel-daftar">
						<thead class="bg-gray color-palette">
							<tr>
								<th rowspan="2">Nomor Urut</th>
								<th rowspan="2" style="width: 5px;"><?= url_order($o, "{$this->controller}/{$func}/{$p}", 3, 'Nama Lengkap'); ?></th>
								<th colspan="2">Jenis Kelamin</th>
								<th rowspan="2">Nomor Identitas / Tanda Pengenal</th>
								<th rowspan="2">Tempat dan Tanggal Lahir / Umur</th>
								<th rowspan="2">Pekerjaan</th>
								<th colspan="2">Kewarganegaraan</th>
								<th rowspan="2">Datang Dari</th>
								<th rowspan="2">Maksud dan Tujuan Kedatangan</th>
								<th rowspan="2">Nama dan Alamat yg Didatangi</th>
								<th rowspan="2">Datang Tanggal</th>
								<th rowspan="2">Pergi Tanggal</th>
								<th rowspan="2">Ket</th>
							</tr>
							<tr>
								<th>L</th>
								<th>P</th>
								<th>Kebangsaan</th>
								<th>Keturunan</th>
							</tr>
						</thead>
						<tbody>
						<!--
							"""
							Menunggu detil informasi data tiap attributnya sudah atau belum,
							jika sudah ada bagaimana proses menuju flow tersebut
							"""
						-->
							<?php if ($main): ?>
								<?php foreach ($main as $key => $data): ?>
									<tr>
										<td class="padat"><?= ($key + $paging->offset + 1); ?></td>
										<td><?= strtoupper($data['nama'])?></td>
										<td class="padat"><?= (strtoupper($data['sex']) == 'LAKI-LAKI') ? 'L' : '' ?></td>
										<td class="padat"><?= (strtoupper($data['sex']) == 'PEREMPUAN') ? 'P' : '' ?></td>
										<td><?= $data['nik']?></td>
										<td><?= $data['tempatlahir'] . ', ' . tgl_indo_out($data['tanggallahir']) . ' / ' . umur($data['tanggallahir']) ?></td>
										<td><?= ($data['pekerjaan'] == 'BELUM/TIDAK BEKERJA') ? '-' : $data['pekerjaan'] ?></td>
										<td><?= $data['warganegara']?></td>
										<td><?= empty($data['negara_asal']) ? '-' : $data['negara_asal'] ?></td>
										<td><?= empty($data['alamat_sebelumnya']) ? '-' : $data['alamat_sebelumnya'] ?></td>
										<td><?= empty($data['maksud_tujuan_kedatangan']) ? '-' : $data['maksud_tujuan_kedatangan'] ?></td>
										<td><?= strtoupper($data['alamat'] . ' RT ' . $data['rt'] . ' / RW ' . $data['rw'] . ' ' . $this->setting->sebutan_dusun . ' ' . $data['dusun'])?></td>
										<td><?= empty($data['tanggal_datang']) ? '-' : tgl_indo_out($data['tanggal_datang']) ?></td>
										<td><?= empty($data['tanggal_pergi']) ? '-' : tgl_indo_out($data['tanggal_pergi']) ?></td>
										<td><?= empty($data['ket']) ? '-' : $data['ket'] ?></td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td class="text-center" colspan="15">Data Tidak Tersedia</td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</form>
			<?php $this->load->view('global/paging'); ?>
		</div>
	</div>
</div>