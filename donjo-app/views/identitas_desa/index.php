<?php
/**
 * File ini:
 *
 * View di Modul Identitas Desa
 *
 * donjo-app/views/identitas_desa/index.php
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

<style>
	.table {
		font-size: 12px;
	}

	.bg-identitas {
		width: 100%;
		height: 300px;
		background: url('<?= gambar_desa($main['kantor_desa'], TRUE); ?>');
		background-repeat: no-repeat;
		background-position: center center;
	}

	.img-identitas {
		margin: 30px auto;
		width: 100px;
		padding: 3px;
	}

	.text-identitas {
		text-align: center;
		font-weight: bold;
		color: #fff;
		text-shadow: 2px 2px 2px #0c83c5;;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Identitas <?= $desa; ?></h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid'); ?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Identitas <?= $desa; ?></li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" action="" method="post">
			<div class="box box-info">
				<div class="box-header with-border">
					<a href="<?= site_url('identitas_desa/form'); ?>" class="btn btn-social btn-flat btn-warning btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Ubah Biodata" ><i class="fa fa-edit"></i> Ubah Data <?= $desa; ?></a>
					<a href="<?= site_url('identitas_desa/maps/kantor'); ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class='fa fa-map-marker'></i> Lokasi Kantor <?= $desa; ?></a>
					<a href="<?= site_url('identitas_desa/maps/wilayah'); ?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class='fa fa-map'></i> Peta Wilayah <?= $desa; ?></a>
				</div>
				<div class="box-body">
					<div class="box-body bg-identitas">
						<img class="img-identitas img-responsive" src="<?= gambar_desa($main['logo']); ?>" alt="logo">
						<h3 class="text-identitas"><?= $desa; ?> <?= $main['nama_desa']; ?></h3>
						<p class="text-identitas"><b><?= $kecamatan; ?> <?= $main['nama_kecamatan']; ?>, <?= $kabupaten; ?> <?= $main['nama_kabupaten']; ?>, Provinsi <?= $main['nama_propinsi']; ?></b></p>
					</div>
					<hr>
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover tabel-rincian">
							<tbody>
								<tr>
									<th colspan="3" class="subtitle_head"><strong><?= strtoupper($desa); ?></strong></th>
								</tr>
								<tr>
									<td width="300">Nama <?= $desa; ?></td><td width="1">:</td>
									<td><?= $main['nama_desa']; ?></td>
								</tr>
								<tr>
									<td>Kode <?= $desa; ?></td><td>:</td>
									<td><?= kode_wilayah($main['kode_desa']); ?></td>
								</tr>
								<tr>
									<td>Kode Pos <?= $desa; ?></td><td>:</td>
									<td><?= $main['kode_pos']; ?></td>
								</tr>
								<tr>
									<td>Kepala <?= $desa; ?></td><td>:</td>
									<td><?= $main['nama_kepala_desa']; ?></td>
								</tr>
								<tr>
									<td>NIP Kepala <?= $desa; ?></td><td>:</td>
									<td><?= $main['nip_kepala_desa']; ?></td>
								</tr>
								<tr>
									<td>Alamat Kantor <?= $desa; ?></td><td>:</td>
									<td><?= $main['alamat_kantor']; ?></td>
								</tr>
								<tr>
									<td>E-Mail <?= $desa; ?></td><td>:</td>
									<td><?= $main['email_desa']; ?></td>
								</tr>
								<tr>
									<td>Telpon <?= $desa; ?></td><td>:</td>
									<td><?= $main['telepon']; ?></td>
								</tr>
								<tr>
									<td>Website <?= $desa; ?></td><td>:</td>
									<td><?= $main['website']; ?></td>
								</tr>
								<tr>
									<th colspan="3" class="subtitle_head"><strong><?= strtoupper($kecamatan); ?></strong></th>
								</tr>
								<tr>
									<td>Nama <?= $kecamatan; ?></td><td>:</td>
									<td><?= $main['nama_kecamatan']; ?></td>
								</tr>
								<tr>
									<td>Kode <?= $kecamatan; ?></td><td>:</td>
									<td><?= kode_wilayah($main['kode_kecamatan']); ?></td>
								</tr>
								<tr>
									<td>Nama <?= ucwords($this->setting->sebutan_camat); ?></td><td>:</td>
									<td><?= $main['nama_kepala_camat']; ?></td>
								</tr>
								<tr>
									<td>NIP <?= ucwords($this->setting->sebutan_camat); ?></td><td>:</td>
									<td><?= $main['nip_kepala_camat']; ?></td>
								</tr>
								<tr>
									<th colspan="3" class="subtitle_head"><strong><?= strtoupper($kabupaten); ?></strong></th>
								</tr>
								<tr>
									<td>Nama <?= $kabupaten; ?></td><td>:</td>
									<td><?= $main['nama_kabupaten']; ?></td>
								</tr>
								<tr>
									<td>Kode <?= $kabupaten; ?></td><td>:</td>
									<td><?= kode_wilayah($main['kode_kabupaten']); ?></td>
								</tr>
								<tr>
									<th colspan="3" class="subtitle_head"><strong>PROVINSI</strong></th>
								</tr>
								<tr>
									<td>Nama Provinsi</td><td>:</td>
									<td><?= $main['nama_propinsi']; ?></td>
								</tr>
								<tr>
									<td>Kode Provinsi</td><td>:</td>
									<td><?= kode_wilayah($main['kode_propinsi']); ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
