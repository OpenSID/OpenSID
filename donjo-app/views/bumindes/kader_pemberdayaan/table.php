<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * File ini:
 *
 * View daftar Buku Kader Pemberdayaan Masyarakat di modul Buku Administrasi Desa
 *
 * donjo-app/views/bumindes/pembangunan/table.php
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
 * @package	OpenSID
 * @author	Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright	Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license	http://www.gnu.org/licenses/gpl.html	GPL V3
 * @link 	https://github.com/OpenSID/OpenSID
 */
?>

<script>
	$(function() {
		var keyword = <?= $keyword?> ;
		$("#cari").autocomplete( {
			source: keyword,
			maxShowItems: 10,
		});
	});
</script>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Buku Kader Pemberdayaan Masyarakat</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid'); ?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Buku Kader Pemberdayaan Masyarakat</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" method="post">
			<div class="row">
					<div class="box box-info">
						<div class="box-header with-border">
							<a href="<?= site_url('bumindes_pembangunan/form'); ?>" title="Tambah Kader Pembangunan" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah Data</a>
							<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '<?= site_url("bumindes_pembangunan/delete_all")?>')" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
							<a href="<?= site_url("bumindes_pembangunan/cetakkader")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak" target="_blank"><i class="fa fa-print"></i> Cetak
						</a>	</div>
					
						<div class="box-body">
							<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
								<form id="mainform" name="mainform" method="post">
									
									<div class="table-responsive">

								
										<table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
											<thead class="bg-gray disabled color-palette">
												<tr>
													<th><input type="checkbox" id="checkall"/></th>
													
													<th width="10%">Aksi</th>
													<th width="10%">No</th>
													<th width="30%">Nama</th>
													<th width="10%">Umur</th>
													<th width="10%">Jenis Kelamin</th>
													<th width="10%">Pendidikan/Kursus</th>
													<th width="10%">Bidang</th>
													<th width="10%">Alamat</th>
													<th>Keterangan</th>
												</tr>
											</thead>
							<tbody>
																<?php foreach ($pembangunan as $data): ?>
																	<tr>
																		<td><input type="checkbox" name="id_cb[]" value="<?=$data['idpenduduk']?>" /></td>
																		
																		<td align="center" nowrap>
																			<a href="<?= site_url("bumindes_pembangunan/form/$data[idpenduduk]/$data[pendidikan_kk_id]")?>" class="btn bg-orange btn-flat btn-sm" data-remote="false" data-title="Ubah Data Kader" title="Ubah Data Kader"><i class="fa fa-edit"></i></a>
																			<a href="#" data-href="<?= site_url("bumindes_pembangunan/delete/$data[idpenduduk]/$data[pendidikan_kk_id]")?>" class="btn bg-maroon btn-flat btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
                                    </td>
									<td align="center"><?=$data['no']?></td>
                                    <td nowrap><?=$data['nama']?></td>
									
									<td align="center"><?=$data['umur']?></td>
																		<td align="center"><?=$data['jeniskelamin']?></td>
																		<td nowrap><?=$data['jeniskelamin']?></td>
																		<td nowrap><?=$data['pendidikanahli']?></td>
																		<td nowrap><?=$data['alamat']?></td>
																		<td nowrap><?=$data['keterangan']?></td>
																	</tr>
																<?php endforeach; ?>
															</tbody>
										</table>
									</div>
								</form>
								<?php $this->load->view('global/paging'); ?>
							</div>
						</div>
					</div>
				
			</div>
		</form>
	</section>
</div>
<?php $this->load->view('global/confirm_delete'); ?>
