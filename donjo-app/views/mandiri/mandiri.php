<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
 *  File ini:
 *
 * View untuk modul Layanan Mandiri
 *
 * donjo-app/views/mandiri.php
 *
 */
/*
 *  File ini bagian dari:
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

<script type="text/javascript">
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
		<h1>Pendaftar Layanan Mandiri</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid'); ?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Pendaftar Layanan Mandiri</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" action="" method="post">
			<div class="box box-info">
				<div class="box-header with-border">
					<a href="<?= site_url('mandiri/ajax_pin'); ?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Buat PIN Warga" class="btn btn-social btn-flat btn-success btn-sm"><i class="fa fa-plus"></i> Tambah Pengguna</a>
				</div>
				<div class="box-body">
					<form id="mainform" name="mainform" action="" method="post">
						<div class="row">
							<div class="col-sm-12 form-inline">
								<div class="input-group input-group-sm pull-right">
									<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?= html_escape($cari); ?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= site_url("mandiri/filter/cari"); ?>');$('#'+'mainform').submit();}">
									<div class="input-group-btn">
										<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= site_url("mandiri/filter/cari"); ?>');$('#'+'mainform').submit(); "><i class="fa fa-search"></i></button>
									</div>
								</div>
							</div>
						</div>
					</form>
					<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
						<form id="mainform" name="mainform" action="" method="post">
							<div class="table-responsive">
								<table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
									<thead class="bg-gray disabled color-palette">
										<tr>
											<th>No</th>
											<th>Aksi</th>
											<th>
												<?php if ($order_by == 2): ?>
													<a href="<?= site_url("mandiri/filter/order_by/1")?>">NIK<i class="fa fa-sort-asc fa-sm"></i></a>
												<?php elseif ($order_by == 1): ?>
													<a href="<?= site_url("mandiri/filter/order_by/2")?>">NIK<i class="fa fa-sort-desc fa-sm"></i></a>
												<?php else: ?>
													<a href="<?= site_url("mandiri/filter/order_by/1")?>">NIK<i class="fa fa-sort fa-sm"></i></a>
												<?php endif; ?>
											</th>
											<th width="50%">
												<?php if ($order_by == 4): ?>
													<a href="<?= site_url("mandiri/filter/order_by/3")?>">Nama Penduduk<i class="fa fa-sort-asc fa-sm"></i></a>
												<?php elseif ($order_by == 3): ?>
													<a href="<?= site_url("mandiri/filter/order_by/4")?>">Nama Penduduk<i class="fa fa-sort-desc fa-sm"></i></a>
												<?php else: ?>
													<a href="<?= site_url("mandiri/filter/order_by/3")?>">Nama Penduduk<i class="fa fa-sort fa-sm"></i></a>
												<?php endif; ?>
											</th>
											<th>
												<?php if ($order_by == 6): ?>
													<a href="<?= site_url("mandiri/filter/order_by/5")?>">Tanggal Buat<i class="fa fa-sort-asc fa-sm"></i></a>
												<?php elseif ($order_by == 5): ?>
													<a href="<?= site_url("mandiri/filter/order_by/6")?>">Tanggal Buat<i class="fa fa-sort-desc fa-sm"></i></a></th>
												<?php else: ?>
													<a href="<?= site_url("mandiri/filter/order_by/5")?>">Tanggal Buat<i class="fa fa-sort fa-sm"></i></a>
												<?php endif; ?>
											</th>
											<th>
												<?php if ($order_by == 8): ?>
													<a href="<?= site_url("mandiri/filter/order_by/7")?>">Login Terakhir<i class="fa fa-sort-asc fa-sm"></i></a>
												<?php elseif ($order_by == 7): ?>
													<a href="<?= site_url("mandiri/filter/order_by/8")?>">Login Terakhir<i class="fa fa-sort-desc fa-sm"></i></a>
												<?php else: ?>
													<a href="<?= site_url("mandiri/filter/order_by/7")?>">Login Terakhir<i class="fa fa-sort fa-sm"></i></a>
												<?php endif; ?>
											</th>
										</tr>
									</thead>
									<tbody>
										<?php if($main): ?>
											<?php foreach ($main as $key => $data): ?>
												<tr <?= jecho($data['telepon'], FALSE, 'class="select-row"'); ?>>
													<td class="padat"><?= ($key + 1); ?></td>
													<td class="aksi">
														<a href="<?= site_url("mandiri/ajax_pin/$data[id_pend]"); ?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ganti PIN Warga" title="Ganti PIN Warga" class="btn btn-flat btn-primary btn-sm"><i class="fa fa-key"></i></a>
														<a href="<?= site_url("mandiri/ajax_hp/$data[id_pend]"); ?>" data-remote="false"  data-toggle="modal" data-target="#modalBox"  data-title="<?= $data['telepon'] ? 'Ubah' : 'Tambah' ?> Telepon Warga" title="<?= $data['telepon'] ? 'Ubah' : 'Tambah' ?> Telepon" class="btn <?= $data['telepon'] ? 'bg-teal' : 'bg-green' ?> btn-flat btn-sm" ><i class="fa fa-phone"></i></a>
														<a href="#" data-href="<?= site_url("mandiri/delete/$data[id_pend]"); ?>" class="btn bg-maroon btn-flat btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
													</td>
													<td><?= $data['nik']; ?></td>
													<td><?= $data['nama']; ?></td>
													<td nowrap><?=tgl_indo2($data['tanggal_buat']); ?></td>
													<td nowrap><?=tgl_indo2($data['last_login']); ?></td>
												</tr>
											<?php endforeach; ?>
										<?php else: ?>
											<tr>
												<td class="text-center" colspan="6">Data Tidak Tersedia</td>
											</tr>
										<?php endif; ?>
									</tbody>
								</table>
							</div>
						</form>
						<?php $this->load->view('global/paging'); ?>
					</div>
					<?php $info = $this->session->flashdata('info'); ?>
					<div class="modal fade" id="pinBox" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false" data-keyboard="false">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header btn-info">
									<button type='button' class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h4 class="modal-title' id='myModalLabel">PIN Warga (<?= $info['nama']; ?>)</h4>
								</div>
								<form action="<?= site_url("mandiri/kirim/$info[id_pend]"); ?>" method="post" id="validasi" target="_blank">
									<input type="hidden" id="pin" name="pin" value="<?= $info['pin']; ?>">
									<div class="modal-body">
										Berikut adalah kode pin yang baru saja di hasilkan, silakan dicatat atau di ingat dengan baik, kode pin ini sangat rahasia, dan hanya bisa dilihat sekali ini lalu setelah itu hanya bisa di reset saja.<br /><h4>Kode PIN : <?= $info['pin']; ?></h4>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-social btn-flat btn-warning btn-sm" data-dismiss="modal"><i class="fa fa-sign-out"></i> Tutup</button>
										<?php if (cek_koneksi_internet() && $info['pin'] && $info['telepon']): ?>
											<button type="submit" class="btn btn-social btn-flat btn-success btn-sm"><i class="fa fa-whatsapp"></i> Kirim</button>
										<?php endif; ?>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>
<?php $this->load->view('global/confirm_delete');?>
<!-- Notifikasi PIN Warga -->
<script type="text/javascript">
	<?php if ($this->session->flashdata('info')): ?>
		$(window).on('load', function() {
			$('#pinBox').modal('show');
		});
	<?php endif ?>
</script>
