<?php

defined('BASEPATH') || exit('No direct script access allowed');

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
 * @author Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2020 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license http://www.gnu.org/licenses/gpl.html GPL V3
 * @link https://github.com/OpenSID/OpenSID
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
		<div class="box box-info">
			<?php if ($this->CI->cek_hak_akses('u')): ?>
				<div class="box-header with-border">
					<?php if ($this->CI->cek_hak_akses('u')): ?>
						<a href="#" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-toggle="modal" data-target="#ModalAdd"><i class="fa fa-plus"></i> Tambah Data</a>
						<a href="#" id="btn_salin" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i>Tambah Template</a>
					<?php endif; ?>
					<?php if ($this->CI->cek_hak_akses('u')): ?>
						<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '<?=site_url('keuangan_manual/delete_all')?>')" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<div class="box-body">
				<div class="row">
					<div class="col-sm-6">
						<form action="<?= site_url('keuangan_manual/set_terpilih') ?>" method="POST" class="form form-horizontal">
							<table class="table table-bordered table-striped table-hover tabel-rincian">
								<tbody>
									<tr>
										<td width="30%">Jenis Anggaran</td>
										<td width="1">:</td>
										<td>
											<select class="form-control input-sm select2" name="jenis_anggaran" onchange="this.form.submit();" id="jenis">
												<option value="4.PENDAPATAN" <?= selected('4.PENDAPATAN', $jenis); ?>>4.PENDAPATAN</option>
												<option value="5.BELANJA" <?= selected('5.BELANJA', $jenis); ?>>5.BELANJA</option>
												<option value="6.PEMBIAYAAN" <?= selected('6.PEMBIAYAAN', $jenis); ?>>6.PEMBIAYAAN</option>
											</select>
										</td>
									</tr>
									<tr>
										<td>Tahun Anggaran</td>
										<td>:</td>
										<td>
											<select class="form-control input-sm select2" name="tahun_anggaran" onchange="this.form.submit();">
												<?php foreach ($tahun_anggaran as $ta) :?>
													<option value="<?= $ta ?>" <?= selected($ta, $tahun); ?>><?= $ta ?></option>
												<?php endforeach ?>
											</select>
										</td>
									</tr>
								</tbody>
							</table>
						</form>
					</div>
				</div>

				<form id="mainform" name="mainform" method="post">
					<div class="table-responsive">
						<table class="table table-bordered table-striped dataTable table-hover tabel-daftar" id="mydata">
							<thead class="bg-gray disabled color-palette">
								<tr>
									<th><input type="checkbox" id="checkall"/></th>
									<th>No</th>
									<th>Aksi</th>
									<th>Kode Rincian</th>
									<th>Anggaran</th>
									<th>Realisasi</th>
								</tr>
							</thead>
							<tbody id="show_data">
							</tbody>
						</table>
					</div>
				</form>
			</div>
		</div>
	</section>
</div>
<?php $this->load->view('global/confirm_delete'); ?>

<?php if ($this->CI->cek_hak_akses('u')): ?>
<!-- MODAL ADD GLOBAL-->
<div class="modal fade" id="ModalAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Tambah Anggaran / Realisasi</h4>
			</div>

			<form id="form-tambah">
				<div class="modal-body">
					<div class="form-group">
						<label>Tahun</label>
						<input type="text" class="form-control input-sm bilangan required" minlength="4" maxlength="4" id="Tahun" name="Tahun" placeholder="Tahun Anggaran"/>
					</div>

					<div class="form-group">
						<label>Jenis Anggaran</label>
						<select class="form-control input-sm select2 required" id="Kd_Akun" name="Kd_Akun">
							<option value="">Pilih Jenis Anggaran</option>
							<?php foreach ($lakun as $data): ?>
								<option value="<?= $data['Akun']?><?= $data['Nama_Akun']?>" <?= selected($jenis, $data['Akun'] . $data['Nama_Akun']); ?>><?= $data['Akun'] ?><?= $data['Nama_Akun']?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="form-group" id="Pendapatan" <?= jecho($jenis != '4.PENDAPATAN', true, 'style="display: none;"'); ?>>
						<label>Kode Rincian</label>
						<select class="form-control input-sm select2" id="Kd_Rincian_pd" name="Kd_Rincian_pd">
							<option value="">Pilih Rekening Pendapatan</option>
							<?php foreach ($lpendapatan as $data): ?>
								<option value="<?= $data['Jenis']?> <?= $data['Nama_Jenis']?>" <?= selected($main['Kd_Rincian'], $data['Jenis']); ?>><?= $data['Jenis'] ?> <?= $data['Nama_Jenis']?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="form-group" id="Belanja" <?= jecho($jenis != '5.BELANJA', true, 'style="display: none;"'); ?>>
						<label>Kode Kegiatan</label>
						<select class="form-control input-sm select2" id="Kd_Keg" name="Kd_Keg">
							<option value="">Pilih Rekening Belanja</option>
							<?php foreach ($lbelanja as $data): ?>
								<option value="<?= $data['Kd_Bid']?> <?= $data['Nama_Bidang']?>" <?= selected($main['Kd_Keg'], $data['Kd_Bid']); ?>><?= $data['Kd_Bid'] ?> <?= $data['Nama_Bidang']?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="form-group" id="Pembiayaan" <?= jecho($jenis != '6.PEMBIAYAAN', true, 'style="display: none;"'); ?>>
						<label>Kode Rincian</label>
						<select class="form-control input-sm select2" id="Kd_Rincian_by" name="Kd_Rincian_by">
							<option value="">Pilih Rekening Pembiayaan</option>
							<?php foreach ($lbiaya as $data): ?>
								<option value="<?= $data['Jenis']?> <?= $data['Nama_Jenis']?>" <?= selected($main['Kd_Rincian'], $data['Jenis']); ?>><?= $data['Jenis'] ?> <?= $data['Nama_Jenis']?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="form-group">
						<label>Nilai Anggaran</label>
						<input type="text" class="form-control input-sm bilangan required" maxlength="50" id="Nilai_Anggaran" name="Nilai_Anggaran" placeholder="Nilai Anggaran"/>
					</div>
					<div class="form-group">
						<label>Nilai Realisasi</label>
						<input type="text" class="form-control input-sm bilangan required" maxlength="50" id="Nilai_Realisasi" name="Nilai_Realisasi" placeholder="Nilai Realisasi"/>
					</div>
				</div>

				<div class="modal-footer">
					<button class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal" aria-hidden="true"><i class='fa fa-sign-out'></i> Tutup</button>
					<button class="btn btn-social btn-flat btn-info btn-sm" id="btn_simpan"><i class="fa fa-check"></i>Simpan</button>
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
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Ubah Anggaran / Realisasi</h4>
			</div>
			<form id="form-edit">
				<div class="modal-body">

					<input type="hidden" id="id2" name="id_edit"/>

					<div class="form-group">
						<label>Tahun</label>
						<input type="text" class="form-control input-sm bilangan required" minlength="4" maxlength="4" id="Tahun2" name="Tahun_edit" placeholder="Tahun Anggaran"/>
					</div>

					<div class="form-group">
						<label>Jenis Anggaran</label>
						<select class="form-control input-sm select2 required" id="Kd_Akun2" name="Kd_Akun_edit" disabled>
							<option value="">Pilih Jenis Anggaran</option>
							<?php foreach ($lakun as $data): ?>
								<option value="<?= $data['Akun']?><?= $data['Nama_Akun']?>" <?= selected($main['Kd_Akun'], $data['Akun']); ?>><?= $data['Akun'] ?><?= $data['Nama_Akun']?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="form-group" id="Pendapatan_edit">
						<label>Kode Rincian</label>
						<select class="form-control input-sm select2" id="Kd_Rincian2_pd" name="Kd_Rincian_edit_pd">
							<option value="">Pilih Rekening Pendapatan</option>
							<?php foreach ($lpendapatan as $data): ?>
								<option value="<?= $data['Jenis']?> <?= $data['Nama_Jenis']?>"><?= $data['Jenis'] ?> <?= $data['Nama_Jenis']?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="form-group" id="Belanja_edit">
						<label>Kode Kegiatan</label>
						<select class="form-control input-sm select2" id="Kd_Keg2_bl" name="Kd_Keg_edit_bl">
							<option value="">Pilih Rekening Belanja</option>
							<?php foreach ($lbelanja as $data): ?>
								<option value="<?= $data['Kd_Bid']?> <?= $data['Nama_Bidang']?>"><?= $data['Kd_Bid'] ?> <?= $data['Nama_Bidang']?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="form-group" id="Pembiayaan_edit">
						<label>Kode Rincian</label>
						<select class="form-control input-sm select2" id="Kd_Rincian2_by" name="Kd_Rincian_edit_by">
							<option value="">Pilih Rekening Pembiayaan</option>
							<?php foreach ($lbiaya as $data): ?>
								<option value="<?= $data['Jenis']?> <?= $data['Nama_Jenis']?>"><?= $data['Jenis'] ?> <?= $data['Nama_Jenis']?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="form-group">
						<label>Nilai Anggaran</label>
						<input type="text" class="form-control input-sm bilangan required" maxlength="50" id="Nilai_Anggaran2" name="Nilai_Anggaran_edit" placeholder="Nilai Anggaran"/>
					</div>
					<div class="form-group">
						<label>Nilai Realisasi</label>
						<input type="text" class="form-control input-sm bilangan required" maxlength="50" id="Nilai_Realisasi2" name="Nilai_Realisasi_edit" placeholder="Nilai Realisasi"/>
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
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Tambah dari Template</h4>
			</div>
			<form id="form-salin">
				<div class="modal-body">
					<div class="form-group">
						<label>Tahun Anggaran</label>
						<input type="text" class="form-control input-sm bilangan required" minlength="4" maxlength="4" id="kodetahun" name="kodetahun" placeholder="Tahun Anggaran"/>
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
<?php endif; ?>

<script type="text/javascript">
	$(document).ready(function(){
		var ubah = <?= $this->CI->cek_hak_akses('u'); ?>;
		var hapus =  <?= $this->CI->cek_hak_akses('h'); ?>;

		//READ/SHOW
		generateTable($('#show_data'), $('#mydata'), ubah, hapus);

		//ADD
		saveAdd();

		//UPDATE
		getEdit($('#show_data'));

		//Simpan Edit Data
		saveEdit();

		//SALIN TEMPLATE DATA
		salinData();

		//MISC
		tools();

		//ENTER KEY UP
		//ADD
		$('#form-tambah').bind("enterKey",function(e){
			$('#btn_simpan').click();
		});
		$('#form-tambah').keyup(function(e){
			if(e.keyCode == 13){
				$(this).trigger("enterKey");
			}
		});
		//UPDATE
		$('#form-edit').bind("enterKey",function(e){
			$('#btn_update').click();
		});
		$('#form-edit').keyup(function(e){
			if(e.keyCode == 13){
				$(this).trigger("enterKey");
			}
		});
	});
</script>
