<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
 * File ini:
 *
 * View data terdata untuk modul suplemen
 *
 * donjo-app/views/suplemen/data_terdata.php,
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
		<h1>Data Terdata Suplemen</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url("suplemen/rincian/{$suplemen['id']}"); ?>"></i> Rincian Suplemen</a></li>
			<li class="active">Data Terdata Suplemen</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?= site_url("suplemen/rincian/{$suplemen['id']}"); ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Rincian Suplemen</a>
					</div>
					<div class="box-body ">
						<h5><b>Rincian Suplemen</b></h5>
						<table class="table table-bordered table-striped table-hover tabel-rincian">
							<tbody>
								<tr>
									<td width="20%">Nama Suplemen</td>
									<td width="1%">:</td>
									<td><?= strtoupper($suplemen['nama']); ?></td>
								</tr>
								<tr>
									<td>Sasaran</td>
									<td>:</td>
									<td><?= $sasaran[$suplemen['sasaran']]?></td>
								</tr>
								<tr>
									<td>Keterangan</td>
									<td>:</td>
									<td><?= $suplemen['keterangan']?></td>
								</tr>
							</tbody>
						</table>
					</div>

					<div class="box-body">
						<h5><b>Data Terdata</b></h5>
						<div class="table-responsive">
							<table class="table table-bordered table-striped table-hover tabel-rincian">
								<tbody>
									<?php $judul = ($suplemen['sasaran'] == 1) ? 'Penduduk' : 'KK' ?>
									<tr>
										<td width="20%"><?= ($suplemen['sasaran'] == 1) ? 'NIK / Nama Penduduk' : 'No. KK / Nama KK'; ?></td>
										<td width="1%">:</td>
										<td> <?= $terdata['terdata_nama'] . ' / ' . $terdata['terdata_info']?></td>
									</tr>
									<tr>
										<td>Alamat <?= $judul; ?></td>
										<td>:</td>
										<td><?= $individu['alamat_wilayah']; ?></td>
									</tr>
									<tr>
										<td>Tempat Tanggal Lahir (Umur) <?= $judul; ?></td>
										<td>:</td>
										<td><?= $individu['tempatlahir']?> <?= tgl_indo($individu['tanggallahir'])?> (<?= $individu['umur']?> Tahun)</td>
									</tr>
									<tr>
										<td>Pendidikan <?= $judul; ?></td>
										<td>:</td>
										<td><?= $individu['pendidikan']?></td>
									</tr>
									<tr>
										<td>Warganegara / Agama <?= $judul; ?></td>
										<td>:</td>
										<td><?= $individu['warganegara']?> / <?= $individu['agama']?></td>
									</tr>
									<tr>
										<td>Keterangan</td>
										<td>:</td>
										<td><?= $terdata['keterangan']?></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

