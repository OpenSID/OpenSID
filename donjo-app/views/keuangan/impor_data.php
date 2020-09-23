<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
 *  File ini:
 *
 * View untuk modul Keuangan
 *
 * donjo-app/views/keuangan/impor_data.php
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

<div class="content-wrapper">
	<section class="content-header">
		<h1>Impor Data Siskeudes</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Impor Data Siskeudes</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-sm-3">
				<div class="box box-info">
					<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data">
						<input type="hidden" name="jenis_impor" id="jenis_impor" value="baru">
						<input type="hidden" name="id_keuangan_master" id="id_keuangan_master" value="0">
						<div class="box-body">
							<div class="form-group">
								<label for="file"  class="control-label">Berkas Database Siskuedes :</label>
								<div class="input-group input-group-sm">
									<input type="text" class="form-control" id="file_path2">
									<input type="file" class="hidden" id="file2" name="keuangan">
									<span class="input-group-btn">
										<button type="button" class="btn btn-info btn-flat"  id="file_browser2"><i class="fa fa-search"></i> Browse</button>
									</span>
								</div>
								<span class="help-block"><code>Pastikan format berkas .zip berisi data Siskeudes dalam format .csv</code></span>
							</div>
						</div>
						<div class="box-footer">
							<button type='reset' class="btn btn-social btn-flat btn-danger btn-sm"><i class='fa fa-times'></i> Batal</button>
							<button type="button" class="btn btn-social btn-flat btn-info btn-sm pull-right" id="ok" onclick="simpan()"><i class='fa fa-check'></i> Simpan</button>
						</div>
					</form>
				</div>
			</div>
			<div class="col-sm-9">
				<div class="box box-info">
					<div class="box-header with-border">
						<h4 class="text-center"><strong>DAFTAR DATA SISKEUDES</strong></h4>
					</div>
					<div class="box-body">
						<div class="table-responsive">
							<table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
								<thead class="bg-gray disabled color-palette">
									<tr>
										<th>No</th>
										<th>Aksi</th>
										<th>Versi</th>
										<th>Tahun Anggaran</th>
										<th>Tanggal Impor</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($main as $data): ?>
										<tr>
											<td class="padat"><?=$data['no']?></td>
											<td class="aksi">
												<?php if (count($data['desa_ganda']) > 1): ?>
													<a href="<?= site_url("keuangan/pilih_desa/{$data['id']}")?>" title="Bersihkan desa ganda" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Bersihkan Desa Ganda" class="btn bg-orange btn-flat btn-sm"><i class="fa fa-minus-square"></i></a>
												<?php endif; ?>
												<a href="#" data-href="<?= site_url("keuangan/delete/$data[id]")?>" class="btn bg-maroon btn-flat btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
												<td><?=$data['versi_database']?></td>
												<td>
													<?=$data['tahun_anggaran']?>
													<?php if (count($data['desa_ganda']) > 1): ?>
														<span style="padding-left: 5px;">(Berisi data dari beberapa desa. Bersihkan dulu data desa ganda.)</span>
													<?php endif; ?>
												</td>
												<td><?=tgl_indo_out($data['tanggal_impor'])?></td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<div class="modal fade in"  id="getCodeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation-triangle text-red"></i> &nbsp;Konfirmasi</h4>
			</div>
			<div class="modal-body btn-info">
				<p>Data tahun anggaran <span id="tahun"></span> sudah ada pada sistem</p>
				<p>Apakah anda ingin melanjutkan proses impor untuk menindih datanya?</p>
			</div>
			<div class="modal-footer">
				<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
				<button type="button" class="btn btn-social btn-flat btn-info btn-sm" onclick="simpanDataUpdate()"><i class='fa fa-check'></i> Lanjutkan impor</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade in"  id="dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header btn-danger">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation-triangle"></i> &nbsp;Peringatan</h4>
			</div>
			<div class="modal-body">
				<p id="kata_peringatan"></p>
			</div>
			<div class="modal-footer">
				<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
			</div>
		</div>
	</div>
</div>

<?php $this->load->view('global/confirm_delete');?>

<script type="text/javascript">
	$(document).ready(function()
	{
		$('#file2').change(function()
		{
			//on change event
			formdata = new FormData();
			if($(this).prop('files').length > 0)
			{
				file =$(this).prop('files')[0];
				formdata.append("keuangan", file);
				formdata.append(csrfParam, getCsrfToken());
			}
		});
	});

	function simpan()
	{
		if ($("#file2").val() == '')
		{
			$("#kata_peringatan").text('Pilih berkas file terlebih dahulu!');
			$("#dialog").modal('show');
			$("#file2").focus();
		}
		else
		{
			$.ajax(
			{
				url: '<?= site_url("keuangan/cek_versi_database")?>',
				type: "POST",
				datatype:"json",
				data: formdata,
				processData: false,
				contentType: false,
				success: function(response) {
					if (response == 0)
					{
						addCsrfField($('#validasi')[0]);
						$('#validasi').submit();
					}
					else if (response == 1)
					{
						$("#kata_peringatan").text('File harus dalam format .zip');
						$("#dialog").modal('show');
						$("#file_path2").val('');
						$("#file2").focus();
					}
					else if (response == 2)
					{
						$("#kata_peringatan").text('File tidak berisi data Siskeudes');
						$("#dialog").modal('show');
						$("#file_path2").val('');
						$("#file2").focus();
					}
					else
					{
						var data = jQuery.parseJSON(response);
						$("#id_keuangan_master").val(data.id);
						$("#tahun").text(data.tahun_anggaran);
						$("#getCodeModal").modal('show');
					}
				}
			});
		}
	}

	function simpanDataUpdate()
	{
		$("#jenis_impor").val('update');
		addCsrfField($('#validasi')[0]);
		$('#validasi').submit();
	}
</script>
