<style>
	.error {
		color: #dd4b39;
	}
</style>
<script>
	function change_all()
	{
		for (var i=1; i > 1; i++) {
			$('#check'+i).change();
		}
	}
	function ket_(centang, urut)
	{
		// ktp_berlaku sekarang selalu 'Seumur Hidup' dan tidak diubah
		if (centang)
		{
			$('#anggota' + urut).attr('disabled', 'disabled');
		}
		else
		{
			$('#anggota' + urut).removeAttr('disabled');
		}
	}
</script>
<div class="content-wrapper">
	<section class="content-header">
		<h1>Surat Pengantar Permohonan Penerbitan Buku Pas Lintas</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_desa/about')?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="<?= site_url('surat')?>"> Daftar Cetak Surat</a></li>
			<li class="active">Surat Pengantar</li>
		</ol>
	</section>
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
														<th><input type="checkbox" id="checkall" onchange="change_all(<?= count($anggota);?>);"/></th>
														<th>NIK</th>
														<th>Nama</th>
														<th>Jenis Kelamin</th>
														<th>Tempat Lahir</th>
														<th>Tanggal Lahir</th>
														<th>Umur</th>
														<th>SHDK</th>
													</tr>
												</thead>
												<tbody>
												<?php if ($anggota!=NULL): ?>
													<input id='jumlah_anggota' type='hidden' disabled='disabled' value="<?= count($anggota);?>">
													<?php $i=0;?>
													<?php foreach ($anggota AS $data): $i++; ?>
													<?php if ($data['umur'] <18 ) { ?>
														<tr>
															<td>
																<input id='anggota<?= $i?>' type="hidden" name="id_cb[]" disabled="disabled" value="'<?= $data['id']?>'"/>
																<input id='anggota_show<?= $i?>' type="checkbox" value="'<?= $data['nik']?>'" onchange="ket_($(this).is(':unchecked'),'<?= $i;?>');"/>
															</td>
															<td><?= $data['nik']?></td>
															<td><?= $data['nama']?></td>
															<td><?= $data['sex']?></td>
															<td><?= $data['tempatlahir']?></td>
															<td><?= $data['tanggallahir']?></td>
															<td><?= $data['umur']?></td>
															<td><?= $data['hubungan']?></td>
														</tr>
													<?php } ?>
													<?php endforeach;?>
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
