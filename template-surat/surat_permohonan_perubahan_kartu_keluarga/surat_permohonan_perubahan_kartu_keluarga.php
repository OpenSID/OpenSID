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
					 	<a href="#" class="btn btn-social btn-flat btn-primary btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Lihat Info Isian Surat"  data-toggle="modal" data-target="#infoBox" data-title="Lihat Info Isian Surat">
						 	<i class="fa fa-info-circle"></i> Info Isian Surat
						</a>
					</div>
					<div class="box-body">
						<form id="main" name="main" method="POST" class="form-horizontal">
							<div class="form-group">
								<label for="nik"  class="col-sm-3 control-label">NIK / Nama KK</label>
								<div class="col-sm-6 col-lg-4">
									<select class="form-control  input-sm select2-nik" id="nik" name="nik" style ="width:100%;" onchange="formAction('main')">
										<option value="">-- Cari NIK / Nama Kepala Keluarga --</option>
										<?php foreach ($kepala_keluarga as $data): ?>
											<option value="<?= $data['id']?>" <?php selected($individu['nik'], $data['nik']); ?>><?= $data['info_pilihan_penduduk']?></option>
										<?php endforeach;?>
									</select>
								</div>
							</div>
						</form>
						<form id="validasi" action="<?= $form_action?>" method="POST" target="_blank" class="form-surat form-horizontal">
							<input type="hidden" id="url_surat" name="url_surat" value="<?= $url ?>">
							<input type="hidden" id="url_remote" name="url_remote" value="<?= site_url('surat/nomor_surat_duplikat')?>">
							<div class="row jar_form">
								<label for="nomor" class="col-sm-3"></label>
								<div class="col-sm-8">
									<input class="required" type="hidden" name="nik" value="<?= $individu['id']?>">
								</div>
							</div>
							<?php if ($individu): ?>
								<?php include("donjo-app/views/surat/form/konfirmasi_pemohon.php"); ?>
							<?php	endif; ?>
							<?php include("donjo-app/views/surat/form/nomor_surat.php"); ?>
							<div class="form-group">
								<input name="sebab_nama" type="hidden">
								<label for="sebab"  class="col-sm-3 control-label">Alasan Permohonan</label>
								<div class="col-sm-6 col-lg-4">
									<select class="form-control input-sm required" name="sebab" onchange="$('input[name=sebab_nama]').val($(this).find(':selected').data('sebabnama'));">
									<option value="">Pilih Alasan Permohonan</option>
						      <?php foreach ($sebab as $id => $nama): ?>
						        <option value="<?= $id?>" data-sebabnama="<?= $nama; ?>" <?php if ($id==$_SESSION['post']['sebab']): ?>selected<?php endif; ?>><?= $nama; ?></option>
						      <?php endforeach;?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="Alasan Lainnya"  class="col-sm-3 control-label">Alasan Lainnya</label>
								<div class="col-sm-8">
									<textarea name="alasan_lainnya" class="form-control input-sm" placeholder="Alasan Lainnya"></textarea>
									<p class="help-block">*)<i>Diisi apabila pilihan alasan permohonan yang dipilih adalah Lainnya.</i>
								</div>
							</div>
							<?php include("donjo-app/views/surat/form/_pamong.php"); ?>
						</form>
					</div>
					<?php include("donjo-app/views/surat/form/tombol_cetak.php"); ?>
				</div>
				<div class='modal fade' id='infoBox' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
					<div class='modal-dialog'>
						<div class='modal-content'>
							<div class='modal-header btn-default'>
								<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
								<h4 class='modal-title' id='myModalLabel'><i class='fa fa-info-circle'></i>&nbsp;&nbsp;Info Isian Surat</h4>
							</div>
							<div class='modal-body small'>
								<h5><strong>Form ini menghasilkan:</strong></h5>
								<ol>
                	<li>Surat Permohonan Perubahan Kartu Keluarga</li>
									<li>Lampiran F-1.16 FORMULIR PERMOHOHAN PERUBAHAN KARTU KELUARGA (KK) BARU WARGA NEGARA INDONESIA</li>
									<li>Lampiran F-1.01 FORMULIR ISIAN BIODATA PENDUDUK UNTUK WNI (PER KELUARGA) untuk keluarga pemohon.</li>
								</ol>
								<p>Pastikan semua biodata pemohon beserta keluarga sudah lengkap sebelum mencetak surat dan lampiran.</p>
								<p>Untuk melengkapi data itu, ubah data pemohon dan anggota keluarganya di form isian penduduk di modul Penduduk.</p>
								<p>Formulir di atas mengacu pada Peraturan Menteri Dalam Negeri Nomor 19 Tahun 2010. </p>
							</div>
							<div class='modal-footer btn-default'>
								<button type="button" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
