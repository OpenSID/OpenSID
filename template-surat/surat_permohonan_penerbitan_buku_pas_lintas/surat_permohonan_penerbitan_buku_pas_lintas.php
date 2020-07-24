<style>
	.error {
		color: #dd4b39;
	}
</style>
<script>
	function ket_(centang, urut)
	{
		if (centang)
		{
			$('#ket_' + urut).attr('disabled', 'disabled');
			$('#ket_' + urut).val('');
		}
		else
		{
			$('#ket_' + urut).removeAttr('disabled');
		}
	}
</script>
<div class="content-wrapper">
	<?php $this->load->view("surat/form/breadcrumb.php"); ?>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?=site_url("surat")?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Kembali Ke Daftar Wilayah">
							<i class="fa fa-arrow-circle-left "></i>Kembali Ke Daftar Cetak Surat
						</a>
					</div>
					<div class="box-body">
						<form action="" id="main" name="main" method="POST" class="form-horizontal">
							<?php include("donjo-app/views/surat/form/_cari_nik.php"); ?>
						</form>
						<form id="validasi" action="<?= $form_action?>" method="POST" target="_blank" class="form-surat form-horizontal">
							<input type="hidden" id="url_surat" name="url_surat" value="<?= $url ?>">
							<input type="hidden" id="url_remote" name="url_remote" value="<?= site_url('surat/nomor_surat_duplikat')?>">
							<?php if ($individu): ?>
								<?php include("donjo-app/views/surat/form/konfirmasi_pemohon.php"); ?>
								<div class="row jar_form">
									<label for="nomor" class="col-sm-3"></label>
									<div class="col-sm-8">
										<input class="required" type="hidden" name="nik" value="<?= $individu['id']?>">
									</div>
								</div>
								<?php include("donjo-app/views/surat/form/nomor_surat.php"); ?>
								<div class="form-group">
									<label for="nomor"  class="col-sm-3 control-label">Anak usia dibawah 18 tahun</label>
									<div class="col-sm-8">
										<div class="table-responsive">
											<table class="table table-bordered dataTable table-hover">
												<thead class="bg-gray disabled color-palette">
													<tr>
														<th><input type="checkbox" id="checkall"/></th>
														<th>NIK</th>
														<th>Nama</th>
														<th>Jenis Kelamin</th>
														<th>Tempat Lahir</th>
														<th>Tanggal Lahir</th>
														<th>Umur</th>
														<th>SHDK</th>
														<th>Keterangan</th>
													</tr>
												</thead>
												<tbody>
													<?php if ($anggota != NULL): ?>
														<?php foreach ($anggota as $data): ?>
															<?php if ($data['umur'] < 18 ): ?>
																<tr>
																	<td>
																		<input type="checkbox" name="id_cb[]" value="'<?= $data['id']?>'" onchange="ket_($(this).is(':unchecked'),'<?= $data['id'];?>');"/>
																	</td>
																	<td><?= $data['nik']?></td>
																	<td><?= $data['nama']?></td>
																	<td><?= $data['sex']?></td>
																	<td><?= $data['tempatlahir']?></td>
																	<td><?= $data['tanggallahir']?></td>
																	<td><?= $data['umur']?></td>
																	<td><?= $data['hubungan']?></td>
																	<td><input id="ket_<?= $data['id'] ?>" name="ket_<?= $data['id'] ?>" value="" disabled="disabled"></td>
																</tr>
															<?php endif; ?>
														<?php endforeach; ?>
													<?php endif; ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							<?php	endif; ?>
							<?php include("donjo-app/views/surat/form/_pamong.php"); ?>
						</form>
					</div>
					<?php include("donjo-app/views/surat/form/tombol_cetak.php"); ?>
				</div>
			</div>
		</div>
	</section>
</div>
