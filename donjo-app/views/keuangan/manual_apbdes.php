<?php
/*
 * File ini:
 *
 * View daftar anggaran dan realisasi Modul Keuangan Manual
 *
 * /donjo-app/views/keuangan/manual_apbdes.php
 *
 */

/*
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
<div class="content-wrapper">
	<section class="content-header">
		<h1>Manual Input Anggaran dan Realisasi APBDes</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Manual Input APBDes</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header">
						<a href="#" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-toggle="modal" data-target="#ModalAdd"><i class="fa fa-plus"></i> Tambah Data</a>
						<a href="#" id="btn_salin" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i>Tambah Data dari Template</a>
					</div>
					<div class="box-header with-border">
						<form action="<?= site_url('keuangan_manual/set_tahun_terpilih') ?>" method="POST" class="form form-horizontal">
							<div class="row">
								<div class="col-md-2">
									<label>Tahun Anggaran: </label>
								</div>
								<div class="col-md-2">
									<select class="form-control input-sm" name="tahun_anggaran" onchange="this.form.submit()">
										<option value="">Pilih Tahun</option>
										<?php foreach ($tahun_anggaran as $tahun) :?>
											<option value="<?= $tahun ?>" <?php selected($tahun, $this->session->set_tahun)?>><?= $tahun ?></option>
										<?php endforeach ?>
									</select>
								</div>
							</div>
						</form>
					</div>
					<div class="box-body">
						<!-- tab panes -->
						<div role="tabpanel" class="tab-pane" id="tab-header">
							<div class="row">
								<div class="col-xs-12">
									<!-- Nav sub tabs -->
									<ul class="nav nav-tabs" role="tablist" id="myTab">
										<li role="presentation" class="active"><a href="#pendapatan_tab" aria-controls="pendapatan_tab" role="tab" data-toggle="tab">Pendapatan</a></li>
										<li role="presentation"><a href="#belanja_tab" aria-controls="belanja_tab" role="tab" data-toggle="tab">Belanja</a></li>
										<li role="presentation"><a href="#pembiayaan_tab" aria-controls="pembiayaan_tab" role="tab" data-toggle="tab">Pembiayaan</a></li>
									</ul>
									<!-- Sub tab panes -->
									<div class="tab-content">
										<!-- TAB PENDAPATAN -->
										<div role="tabpanel" class="tab-pane active" id="pendapatan_tab">
											<div class="box-header">
												<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform','<?=site_url("keuangan_manual/delete_all/")?>')" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
											</div>
											<form id="mainform" name="mainform" action="" method="post">
												<div class="row">
													<div class="col-sm-12">
														<div class="table-responsive">
															<table class="table table-bordered table-striped dataTable table-hover" id="mydata_pd">
																<thead class="bg-gray disabled color-palette">
																	<tr>
																		<th><input type="checkbox" id="checkall"/</th>
																		<th>No</th>
																		<th>Aksi</th>
																		<th>Tahun</th>
																		<th>Jenis Anggaran</th>
																		<th>Kode Rincian</th>
																		<th>Anggaran</th>
																		<th>Realisasi</th>
																	</tr>
																</thead>
																<tbody id="show_data_pd">
																</tbody>
															</table>
														</div>
													</div>
												</div>
											</form>
										</div>
										<!-- TAB BELANJA -->
										<div role="tabpanel" class="tab-pane" id="belanja_tab">
											<div class="box-header">
												<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform_bl','<?=site_url("keuangan_manual/delete_all/")?>')" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
											</div>
											<form id="mainform_bl" name="mainform_bl" action="" method="post">
												<div class="row">
													<div class="col-sm-12">
														<div class="table-responsive">
															<table class="table table-bordered table-striped dataTable table-hover" id="mydata_bl">
																<thead class="bg-gray disabled color-palette">
																	<tr>
																		<th></th>
																		<th>No</th>
																		<th>Aksi</th>
																		<th>Tahun</th>
																		<th>Jenis Anggaran</th>
																		<th>Kode Kegiatan</th>
																		<th>Anggaran</th>
																		<th>Realisasi</th>
																	</tr>
																</thead>
																<tbody id="show_data_bl">
																</tbody>
															</table>
														</div>
													</div>
												</div>
											</form>
										</div>
										<!-- TAB PEMBIAYAAN -->
										<div role="tabpanel" class="tab-pane" id="pembiayaan_tab">
											<div class="box-header">
												<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform_by','<?=site_url("keuangan_manual/delete_all/")?>')" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
											</div>
											<form id="mainform_by" name="mainform_by" action="" method="post">
												<div class="row">
													<div class="col-sm-12">
														<div class="table-responsive">
															<table class="table table-bordered table-striped dataTable table-hover" id="mydata_by">
																<thead class="bg-gray disabled color-palette">
																	<tr>
																		<th></th>
																		<th>No</th>
																		<th>Aksi</th>
																		<th>Tahun</th>
																		<th>Jenis Anggaran</th>
																		<th>Kode Rincian</th>
																		<th>Anggaran</th>
																		<th>Realisasi</th>
																	</tr>
																</thead>
																<tbody id="show_data_by">
																</tbody>
															</table>
														</div>
													</div>
												</div>
											</form>
										</div>
										<!-- End Sub tab panes -->
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<?php $this->load->view('global/confirm_delete');?>

<!-- MODAL ADD GLOBAL-->
<div class="modal fade" id="ModalAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 class="modal-title" id="myModalLabel">Tambah Anggaran / Realisasi</h3>
			</div>

			<form class="form-horizontal">
				<div class="modal-body">
					<div class="box box-info"></div>
					<div class="form-group">
						<label class="col-sm-3 control-label" >Tahun</label>
						<div class="col-sm-8">
							<input type="text" class="form-control input-sm " id="Tahun" name="Tahun" placeholder="Tahun"/>
						</div>
					</div>

					<div class='form-group'>
						<label class="col-sm-3 control-label" >Jenis Anggaran</label>
						<div class="col-sm-8">
							<select class="form-control input-sm " id="Kd_Akun" name="Kd_Akun">
								<option value="">Pilih Jenis Anggaran</option>
								<?php foreach ($lakun as $data): ?>
									<option value="<?= $data['Akun']?><?= $data['Nama_Akun']?>" <?php selected($main['Kd_Akun'], $data['Akun']); ?>><?= $data['Akun'] ?><?= $data['Nama_Akun']?></option>
								<?php endforeach;?>
							</select>
						</div>
					</div>

					<div class='form-group' id="Pendapatan">
						<label class="col-sm-3 control-label" >Kode Rincian</label>
						<div class="col-sm-8">
							<select class="form-control input-sm" id="Kd_Rincian_pd" name="Kd_Rincian_pd">
								<option value="">Pilih Rekening Pendapatan</option>
								<?php foreach ($lpendapatan as $data): ?>
									<option value="<?= $data['Jenis']?> <?= $data['Nama_Jenis']?>" <?php selected($main['Kd_Rincian'], $data['Jenis']); ?>><?= $data['Jenis'] ?> <?= $data['Nama_Jenis']?></option>
								<?php endforeach;?>
							</select>
						</div>
					</div>

					<div class='form-group' id="Belanja">
						<label class="col-sm-3 control-label" >Kode Kegiatan</label>
						<div class="col-sm-8">
							<select class="form-control input-sm" id="Kd_Keg" name="Kd_Keg">
								<option value="">Pilih Rekening Belanja</option>
								<?php foreach ($lbelanja as $data): ?>
									<option value="<?= $data['Kd_Bid']?> <?= $data['Nama_Bidang']?>" <?php selected($main['Kd_Keg'], $data['Kd_Bid']); ?>><?= $data['Kd_Bid'] ?> <?= $data['Nama_Bidang']?></option>
								<?php endforeach;?>
							</select>
						</div>
					</div>

					<div class='form-group' id="Pembiayaan">
						<label class="col-sm-3 control-label" >Kode Rincian</label>
						<div class="col-sm-8">
							<select class="form-control input-sm" id="Kd_Rincian_by" name="Kd_Rincian_by">
								<option value="">Pilih Rekening Pembiayaan</option>
								<?php foreach ($lbiaya as $data): ?>
									<option value="<?= $data['Jenis']?> <?= $data['Nama_Jenis']?>" <?php selected($main['Kd_Rincian'], $data['Jenis']); ?>><?= $data['Jenis'] ?> <?= $data['Nama_Jenis']?></option>
								<?php endforeach;?>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label" >Nilai Anggaran</label>
						<div class="col-sm-8">
							<input type="text" class="form-control input-sm " id="Nilai_Anggaran" name="Nilai_Anggaran" placeholder="Nilai Anggaran"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label" >Nilai Realisasi</label>
						<div class="col-sm-8">
							<input type="text" class="form-control input-sm " id="Nilai_Realisasi" name="Nilai_Realisasi" placeholder="Nilai Realisasi"/>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal" aria-hidden="true"><i class='fa fa-sign-out'></i> Tutup</button>
					<button class="btn btn-social btn-flat btn-info btn-sm" id="btn_simpan"><i class='fa fa-check'></i>Simpan</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!--END MODAL ADD GLOBAL-->

<!-- MODAL EDIT GLOBAL-->
<div class="modal fade" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 class="modal-title" id="myModalLabel">Ubah Anggaran / Realisasi</h3>
			</div>
			<form class="form-horizontal">
				<div class="modal-body">
					<div class="box box-info"></div>

					<input type="hidden" id="id2" name="id_edit"/>

					<div class="form-group">
						<label class="col-sm-3 control-label" >Tahun</label>
						<div class="col-sm-8">
							<input type="text" class="form-control input-sm " id="Tahun2" name="Tahun_edit" placeholder="Tahun"/>
						</div>
					</div>

					<div class='form-group'>
						<label class="col-sm-3 control-label" >Jenis Anggaran</label>
						<div class="col-sm-8">
							<select class="form-control input-sm " id="Kd_Akun2" name="Kd_Akun_edit" disabled>
								<option value="">Pilih Jenis Anggaran</option>
								<?php foreach ($lakun as $data): ?>
									<option value="<?= $data['Akun']?><?= $data['Nama_Akun']?>" <?php selected($main['Kd_Akun'], $data['Akun']); ?>><?= $data['Akun'] ?><?= $data['Nama_Akun']?></option>
								<?php endforeach;?>
							</select>
						</div>
					</div>

					<div class='form-group' id="Pendapatan_edit">
						<label class="col-sm-3 control-label" >Kode Rincian</label>
						<div class="col-sm-8">
							<select class="form-control input-sm" id="Kd_Rincian2_pd" name="Kd_Rincian_edit_pd">
								<option value="">Pilih Rekening Pendapatan</option>
								<?php foreach ($lpendapatan as $data): ?>
									<option value="<?= $data['Jenis']?> <?= $data['Nama_Jenis']?>" <?php selected($main_pd['Kd_Rincian'], $data['Jenis']); ?>><?= $data['Jenis'] ?> <?= $data['Nama_Jenis']?></option>
								<?php endforeach;?>
							</select>
						</div>
					</div>

					<div class='form-group' id="Belanja_edit">
						<label class="col-sm-3 control-label" >Kode Kegiatan</label>
						<div class="col-sm-8">
							<select class="form-control input-sm" id="Kd_Keg2_bl" name="Kd_Keg_edit_bl">
								<option value="">Pilih Rekening Belanja</option>
								<?php foreach ($lbelanja as $data): ?>
									<option value="<?= $data['Kd_Bid']?> <?= $data['Nama_Bidang']?>" <?php selected($main_bl['Kd_Keg'], $data['Kd_Bid']); ?>><?= $data['Kd_Bid'] ?> <?= $data['Nama_Bidang']?></option>
								<?php endforeach;?>
							</select>
						</div>
					</div>

					<div class='form-group' id="Pembiayaan_edit">
						<label class="col-sm-3 control-label" >Kode Rincian</label>
						<div class="col-sm-8">
							<select class="form-control input-sm" id="Kd_Rincian2_by" name="Kd_Rincian_edit_by">
								<option value="">Pilih Rekening Pembiayaan</option>
								<?php foreach ($lbiaya as $data): ?>
									<option value="<?= $data['Jenis']?> <?= $data['Nama_Jenis']?>" <?php selected($main_by['Kd_Rincian'], $data['Jenis']); ?>><?= $data['Jenis'] ?> <?= $data['Nama_Jenis']?></option>
								<?php endforeach;?>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label" >Nilai Anggaran</label>
						<div class="col-sm-8">
							<input type="text" class="form-control input-sm " id="Nilai_Anggaran2" name="Nilai_Anggaran_edit" placeholder="Nilai Anggaran"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label" >Nilai Realisasi</label>
						<div class="col-sm-8">
							<input type="text" class="form-control input-sm " id="Nilai_Realisasi2" name="Nilai_Realisasi_edit" placeholder="Nilai Realisasi"/>
						</div>
					</div>

				</div>

				<div class="modal-footer">
					<button class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal" aria-hidden="true"><i class='fa fa-sign-out'></i> Tutup</button>
					<button class="btn btn-social btn-flat btn-info btn-sm" id="btn_update"><i class='fa fa-check'></i>Simpan</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!--END MODAL EDIT GLOBAL-->

<!--MODAL SALIN-->
<div class="modal fade" id="ModalSalin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></button>
				<h4 class="modal-title" id="myModalLabel">Tambah/Salin dari Template</h4>
			</div>
			<form class="form-horizontal">
				<div class="modal-body">
					<div class="box box-info"></div>
					<div class="form-group">
						<label class="col-sm-3 control-label" >Tahun Anggaran</label>
						<div class="col-sm-3">
							<input type="text" class="form-control input-sm " id="kodetahun" name="kodetahun" placeholder="Tahun Anggaran"/>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal" aria-hidden="true"><i class='fa fa-sign-out'></i> Tutup</button>
					<button class="btn btn-social btn-flat btn-info btn-sm" id="btn_salin1"><i class='fa fa-check'></i>Salin</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!--END MODAL SALIN-->

<script type="text/javascript">
$(document).ready(function(){
	//READ/SHOW
	generateTable('data_pendapatan', $('#show_data_pd') , $('#mydata_pd'));
	generateTable_belanja('data_belanja', $('#show_data_bl') , $('#mydata_bl'));
	generateTable('data_pembiayaan', $('#show_data_by') , $('#mydata_by'));

	//ADD
	saveAdd();

	//UPDATE
	getEdit($('#show_data_pd'));
	getEdit($('#show_data_bl'));
	getEdit($('#show_data_by'));
	//Simpan Edit Data
	saveEdit();

	//SALIN TEMPLATE DATA
	salinData();

	//MISC
	tools();
});
</script>
