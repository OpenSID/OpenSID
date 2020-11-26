<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * File ini:
 *
 * View untuk modul Rumah Tangga
 *
 * donjo-app/views/sid/kependudukan/rtm.php,
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
		$( "#cari" ).autocomplete( {
			source: keyword,
			maxShowItems: 10,
		});
	});
</script>
<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">
						Pengelompokan Rumah Tangga
					</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?= site_url('hom_sid'); ?>"><i class="fa fa-home"></i> Home</a></li>
						<li class="breadcrumb-item active">Daftar Rumah Tangga</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<section class="content" id="maincontent">
		<div class="card card-outline card-info">
			<div class="card-header with-border">
				<a href="<?= site_url('rtm/form_old'); ?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Tambah Data Rumah Tangga Per Penduduk" title="Tambah Dari Data Penduduk" class="btn btn-flat btn-success btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left"><i class='fa fa-plus'></i>Tambah Rumah Tangga</a>
				<?php if ($this->CI->cek_hak_akses('h')): ?>
					<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform','<?= site_url("rtm/delete_all"); ?>')" class="btn btn-flat	btn-danger btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
				<?php endif; ?>
				<a href="<?= site_url("rtm/ajax_cetak/$o/cetak"); ?>" class="btn btn-flat bg-purple btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left" title="Cetak Data" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Data" target="_blank"><i class="fa fa-print"></i> Cetak</a>
				<a href="<?= site_url("rtm/ajax_cetak/$o/unduh"); ?>" class="btn btn-flat bg-navy btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left" title="Unduh Data" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Data" target="_blank"><i class="fa fa-download"></i> Unduh</a>
				<a href="<?= site_url("{$this->controller}/clear"); ?>" class="btn btn-flat bg-purple btn-xs visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block visible-xl-inline-block text-left"><i class="fa fa-refresh"></i>Bersihkan Filter</a>
			</div>
			<div class="card-body">
				<div class="dataTables_wrapper dt-bootstrap no-footer">
					<form class="form-inline" id="mainform" name="mainform" action="" method="post">
						<div class="container-fluid">
							<div class="row mb-2">
								<div class="col-sm-8">
									<select class="form-control form-control-sm " name="dusun" onchange="formAction('mainform','<?= site_url('rtm/dusun'); ?>')">
										<option value="">Pilih <?= ucwords($this->setting->sebutan_dusun); ?></option>
										<?php foreach ($list_dusun AS $data): ?>
											<option value="<?= $data['dusun']?>" <?= selected($dusun, $data['dusun']); ?>><?= set_ucwords($data['dusun']); ?></option>
										<?php endforeach; ?>
									</select>
									<?php if ($dusun): ?>
										<select class="form-control form-control-sm" name="rw" onchange="formAction('mainform','<?= site_url('rtm/rw'); ?>')" >
											<option value="">Pilih RW</option>
											<?php foreach ($list_rw AS $data): ?>
												<option value="<?= $data['rw']?>" <?= selected($rw, $data['rw']); ?>><?= set_ucwords($data['rw']); ?></option>
											<?php endforeach; ?>
										</select>
									<?php endif; ?>
									<?php if ($rw): ?>
										<select class="form-control form-control-sm" name="rt" onchange="formAction('mainform','<?= site_url('rtm/rt'); ?>')">
											<option value="">Pilih RT</option>
											<?php foreach ($list_rt AS $data): ?>
												<option value="<?= $data['rt']?>" <?= selected($rt, $data['rt']); ?>><?= set_ucwords($data['rt']); ?></option>
											<?php endforeach; ?>
										</select>
									<?php endif; ?>
								</div>
								<div class="col-sm-4">
									<div class="input-group input-group-sm pull-right">
										<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?=html_escape($cari)?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?=site_url("rtm/filter/cari"); ?>');$('#'+'mainform').submit();}">
										<div class="input-group-btn">
											<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?=site_url("rtm/filter/cari"); ?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="table-responsive table-min-height">
							<table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
								<thead class="bg-gray disabled color-palette">
									<tr>
										<th><input type="checkbox" id="checkall"/></th>
										<th>No</th>
										<th>Aksi</th>
										<th>Foto</th>
										<?php if ($order_by == 2): ?>
											<th><a href="<?= site_url("rtm/filter/order_by/1"); ?>">Nomor Rumah Tangga <i class='fa fa-sort-asc fa-sm'></i></a></th>
										 <?php elseif ($order_by == 1): ?>
											<th><a href="<?= site_url("rtm/filter/order_by/2"); ?>">Nomor Rumah Tangga <i class='fa fa-sort-desc fa-sm'></i></a></th>
										<?php else: ?>
											<th><a href="<?= site_url("rtm/filter/order_by/1"); ?>">Nomor Rumah Tangga <i class='fa fa-sort fa-sm'></i></a></th>
										<?php endif; ?>
										<?php if ($order_by == 4): ?>
											<th><a href="<?= site_url("rtm/filter/order_by/3"); ?>">Kepala Rumah Tangga <i class='fa fa-sort-asc fa-sm'></i></a></th>
										<?php elseif ($order_by == 3): ?>
											<th><a href="<?= site_url("rtm/filter/order_by/4"); ?>">Kepala Rumah Tangga <i class='fa fa-sort-desc fa-sm'></i></a></th>
										<?php else: ?>
											<th><a href="<?= site_url("rtm/filter/order_by/3"); ?>">Kepala Rumah Tangga <i class='fa fa-sort fa-sm'></i></a></th>
										<?php endif; ?>
										<th width="10%">NIK</th>
										<th>Jumlah Anggota</th>
										<th>Alamat</th>
										<th><?= ucwords($this->setting->sebutan_dusun); ?></th>
										<th>RW</th>
										<th>RT</th>
										<?php if ($order_by == 6): ?>
											<th><a href="<?= site_url("rtm/filter/order_by/5"); ?>">Tanggal Terdaftar <i class='fa fa-sort-asc fa-sm'></i></a></th>
										<?php elseif ($order_by == 5): ?>
											<th><a href="<?= site_url("rtm/filter/order_by/6"); ?>">Tanggal Terdaftar <i class='fa fa-sort-desc fa-sm'></i></a></th>
										<?php else: ?>
											<th><a href="<?= site_url("rtm/filter/order_by/5"); ?>">Tanggal Terdaftar <i class='fa fa-sort fa-sm'></i></a></th>
										<?php endif; ?>
									</tr>
								</thead>
								<tbody>
									<?php if($main): ?>
										<?php foreach ($main as $key => $data): ?>
											<tr>
												<td class="padat"><input type="checkbox" name="id_cb[]" value="<?= $data['no_kk']?>" /></td>
												<td class="padat"><?= ($paging->offset + $key + 1) ; ?></td>
												<td class="aksi">
													<a href="<?= site_url("rtm/anggota/$data[id]"); ?>" class="btn bg-purple btn-flat btn-xs" title="Rincian Anggota Rumah Tangga"><i class="fa fa-list-ol"></i></a>
													<a href="<?= site_url("rtm/ajax_add_anggota/$data[id]"); ?>" title="Tambah Anggota Rumah Tangga" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Tambah Anggota Rumah Tangga" class="btn btn-success btn-flat btn-xs"><i class="fa fa-plus"></i></a>
													<a href="<?= site_url("rtm/edit_nokk/$data[id]"); ?>" title="Ubah Data" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Rumah Tangga" class="btn bg-orange btn-flat btn-xs"><i class='fa fa-edit'></i></a>
													<?php if ($this->CI->cek_hak_akses('h')): ?>
														<a href="#" data-href="<?= site_url("rtm/delete/$data[no_kk]"); ?>" class="btn bg-maroon btn-flat btn-xs" title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
													<?php endif; ?>
												</td>
												<td class="padat">
													<div class="user-panel">
														<div class="image2">
															<img src="<?= ! empty($data['foto']) ? AmbilFoto($data['foto']) : base_url('assets/files/user_pict/kuser.png'); ?>" class="rounded-circle" alt="Foto Penduduk"/>
														</div>
													</div>
												</td>
												<td>
													<label><?= $data['no_kk']?></label>
												</td>
												<td><?= strtoupper($data['kepala_kk']); ?></td>
												<td><?= strtoupper($data['nik']); ?></td>
												<td class="padat">
													<a href="<?= site_url("rtm/anggota/$data[id]"); ?>"><?= $data['jumlah_anggota']?></a>
												</td>
												<td><?= strtoupper($data['alamat']); ?></td>
												<td><?= strtoupper($data['dusun']); ?></td>
												<td><?= strtoupper($data['rw']); ?></td>
												<td><?= strtoupper($data['rt']); ?></td>
												<td><?= tgl_indo($data['tgl_daftar']); ?></td>
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
			</div>
		</div>
	</section>
</div>
<?php $this->load->view('global/confirm_delete'); ?>
