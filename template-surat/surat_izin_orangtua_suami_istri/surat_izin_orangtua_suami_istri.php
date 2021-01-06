<script>
  function submit_form_ambil_data()
  {
   	$('input').removeClass('required');
   	$('select').removeClass('required');
   	$('#'+'validasi').attr('action','');
    $('#'+'validasi').attr('target','');
    $('#'+'validasi').submit();
  }

  function pemberi_izin(selaku)
  {
    var yang_diizinkan;
    if (selaku == 'Orang Tua')
    {
      yang_diizinkan = 'Anak';
    }
    else if (selaku == "Suami")
    {
      yang_diizinkan = 'Istri';
    }
    else if (selaku == "Istri")
    {
      yang_diizinkan = 'Suami';
    }
    else if (selaku == "Keluarga")
    {
      yang_diizinkan = 'Keluarga';
    }
    $('#mengizinkan').val(yang_diizinkan);
    $('#mengizinkan_show').val(yang_diizinkan);
    submit_form_ambil_data();
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
						<form id="main" name="main" method="POST" class="form-horizontal">
							<div class="col-md-12">
								<div class="form-group subtitle_head">
									<label class="col-sm-3 text-right"><strong>PIHAK YANG MEMBERI IZIN</strong></label>
								</div>
								<?php include("donjo-app/views/surat/form/_cari_nik.php"); ?>
							</div>
						</form>
						<form id="validasi" action="<?= $form_action?>" method="POST" target="_blank" class="form-surat form-horizontal">
							<input type="hidden" id="url_surat" name="url_surat" value="<?= $url ?>">
							<input type="hidden" id="url_remote" name="url_remote" value="<?= site_url('surat/nomor_surat_duplikat')?>">
							<div class="col-md-12">
								<input id="nik_validasi" name="nik" type="hidden" value="<?= $_SESSION['post']['nik']?>">
								<input id="id_diberi_izin_validasi" name="id_diberi_izin" type="hidden" value="<?= $_SESSION['id_diberi_izin']?>"/>
								<?php if ($individu): ?>
									<?php include("donjo-app/views/surat/form/konfirmasi_pemohon.php"); ?>
								<?php	endif; ?>
								<div class="form-group">
									<label for="nik"  class="col-sm-3 control-label">Memberi Izin Selaku</label>
									<div class="col-sm-6 col-lg-4">
										<select class="form-control input-sm select2" name="selaku" id="selaku" onchange="pemberi_izin($(this).val());" style ="width:100%;">
										<option value="">Pilih Selaku</option>
										<?php foreach ($selaku as $data): ?>
											<option value="<?= $data?>" <?php if ($data==$_SESSION['post']['selaku']): ?>selected<?php endif; ?>><?= $data?></option>
										<?php endforeach;?>
										</select>
									</div>
								</div>
								<?php include("donjo-app/views/surat/form/nomor_surat.php"); ?>
								<div class="form-group subtitle_head">
									<label class="col-sm-3 text-right"><strong>PIHAK YANG DIBERI IZIN</strong></label>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Hubungan Dengan Pemberi Izin</label>
									<div class="col-sm-6 col-lg-4">
										<input id='mengizinkan' type="hidden" name="mengizinkan" value="<?= $_SESSION['post']['mengizinkan']?>"/>
										<select class="form-control input-sm" id="mengizinkan_show" disabled="disabled">
											<option value="">Pilih Hubungan</option>
											<?php foreach ($yang_diberi_izin as $data): ?>
												<option value="<?= $data?>" <?php if ($data==$_SESSION['post']['mengizinkan']): ?>selected<?php endif; ?>><?= $data?></option>
											<?php endforeach;?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label for="nik"  class="col-sm-3 control-label">NIK / Nama Yang Diberi Izin</label>
									<div class="col-sm-6 col-lg-4">
										<select class="form-control input-sm select2-nik" id="id_diberi_izin" name="id_diberi_izin" style ="width:100%;" onchange="submit_form_ambil_data(this.id);">
											<option value="">--  Cari NIK / Nama Penduduk--</option>
											<?php foreach ($penduduk_diberi_izin as $data): ?>
												<option value="<?= $data['id']?>" <?php selected($diberi_izin['nik'], $data['nik']); ?>><?= $data['info_pilihan_penduduk']?></option>
											<?php endforeach;?>
										</select>
									</div>
								</div>
								<?php if ($diberi_izin): ?>
									<?php //bagian info setelah terpilih
										$individu = $diberi_izin;
										include("donjo-app/views/surat/form/konfirmasi_pemohon.php");
									?>
								<?php endif; ?>
								<div class="form-group">
									<label for="negara_tujuan"  class="col-sm-3 control-label">Negara Tujuan</label>
									<div class="col-sm-8">
										<input  id="negara_tujuan" class="form-control input-sm required" type="text" placeholder="Negara Tujuan" name="negara_tujuan" value="<?= $_SESSION['post']['negara_tujuan']?>">
										<p class="help-block">Diisi dengan Negara yang dituju sprt: Malaysia, Korea, dll</p>
									</div>
								</div>
								<div class="form-group">
									<label for="nama_pptkis"  class="col-sm-3 control-label">Nama PPTKIS</label>
									<div class="col-sm-8">
										<input  id="nama_pptkis" class="form-control input-sm required" type="text" placeholder="Nama PPTKIS" name="nama_pptkis" value="<?= $_SESSION['post']['nama_pptkis']?>">
										<p class="help-block">*) Nama PT atau Perusahaan</p>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Status Pekerjaan/ TKI/ TKW</label>
									<div class="col-sm-6 col-lg-4">
										<select class="form-control input-sm" id="pekerja_show" disabled="disabled">
											<option value="">Pilih Status Pekerjaan/ TKI/ TKW</option>
											<?php foreach ($status_pekerjaan as $data): ?>
												<option value="<?= $data?>" <?php if ($data==$status_diberi_izin): ?>selected<?php endif; ?>><?= $data?></option>
											<?php endforeach;?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label for="masa_kontrak"  class="col-sm-3 control-label">Masa Kontrak (Tahun)</label>
									<div class="col-sm-8">
										<input  id="masa_kontrak" class="form-control input-sm required" type="text" placeholder="Masa Kontrak" name="masa_kontrak" value="<?= $_SESSION['post']['masa_kontrak']?>">
									</div>
								</div>
								<div class="form-group subtitle_head">
									<label class="col-sm-3 text-right"><strong>PENANDA TANGAN</strong></label>
								</div>
								<?php include("donjo-app/views/surat/form/_pamong.php"); ?>
							</div>
						</form>
					</div>
					<?php include("donjo-app/views/surat/form/tombol_cetak.php"); ?>
				</div>
			</div>
		</div>
	</section>
</div>
