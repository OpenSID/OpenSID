<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<script language="javascript" type="text/javascript">

	function ubah_penerima_kuasa(asal)
	{
		if (asal == 1)
		{
			$('.penerima_kuasa_desa').show();
			$('.penerima_kuasa_luar_desa').hide();
			$('input[name=anchor').val('a_penerima_kuasa');
		}
		else
		{
			$('.penerima_kuasa_desa').hide();
			$('.penerima_kuasa_luar_desa').show();
			$('#id_penerima_kuasa').val('*'); // Hapus $id_penerima_kuasa
			submit_form_ambil_data('a_penerima_kuasa');
		}
	}

 	function submit_form_ambil_data(jenis)
	{
		$('input[name=anchor').val(jenis);
		$('input').removeClass('required');
		$('select').removeClass('required');
		$('#'+'validasi').attr('action','');
		$('#'+'validasi').attr('target','');
		$('#'+'validasi').submit();
	}

	$('document').ready(function()
	{
		/* pergi ke bagian halaman sesudah mengisi warga desa */
		setTimeout(function() {location.hash = "#" + $('input[name=anchor]').val();}, 500);
	});
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
						 <a href="#" class="btn btn-social btn-flat btn-primary btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Lihat Info Isian Surat"  data-toggle="modal" data-target="#infoBox" data-title="Lihat Info Isian Surat">
						 	<i class="fa fa-info-circle"></i> Info Isian Surat
						</a>
					</div>

					<div class="box-body">

						<form id="validasi" action="<?= $form_action?>" method="POST" target="_blank" class="form-surat form-horizontal">
							<input type="hidden" id="url_surat" name="url_surat" value="<?= $url ?>">
							<input type="hidden" id="url_remote" name="url_remote" value="<?= site_url('surat/nomor_surat_duplikat')?>">
							<div class="col-md-12">

								<div class="form-group">
									<label for="nomor"  class="col-sm-3 control-label">Nomor Surat</label>
									<div class="col-sm-8">
										<input  id="nomor" class="form-control input-sm required" type="text" placeholder="Nomor Surat" name="nomor" value="<?= $_SESSION['post']['nomor']; ?>">
										<p class="help-block text-red small"><?= $surat_terakhir['ket_nomor']?><strong><?= $surat_terakhir['no_surat'];?></strong> (tgl: <?= $surat_terakhir['tanggal']?>)</p>
									</div>
								</div>

								<!-- pemberi kuasa -->
								<div class="form-group subtitle_head" id="a_pemberi_kuasa">
									<label class="col-sm-3 control-label" for="status">PEMBERI KUASA</label>
								</div>
								<div class="form-group pemberi_kuasa_desa">
									<label for="pemberi_kuasa_desa" class="col-sm-3 control-label" ><strong>NIK / Nama</strong></label>
									<div class="col-sm-5">
										<select class="form-control input-sm select2-nik" id="id_pemberi_kuasa" name="id_pemberi_kuasa" style ="width:100%;"  onchange="submit_form_ambil_data('a_pemberi_kuasa');">
											<option value="">--  Cari NIK / Nama Penduduk--</option>
											<?php foreach ($penduduk as $data): ?>
												<option value="<?= $data['id']?>" <?php selected($pemberi_kuasa['nik'], $data['nik']); ?>><?= $data['info_pilihan_penduduk']?></option>
											<?php endforeach;?>
										</select>
									</div>
								</div>

								<?php  if ($pemberi_kuasa): ?>
									<?php $individu = $pemberi_kuasa;?>
									<?php include("donjo-app/views/surat/form/konfirmasi_pemohon.php"); ?>
								<?php endif; ?>

								<!-- akhir pemberi kuasa -->

								<!-- penerima kuasa -->
								<div class="form-group subtitle_head" id="a_penerima_kuasa">
									<label class="col-sm-3 control-label" for="status">PENERIMA KUASA</label>
									<div class="btn-group col-sm-8" data-toggle="buttons">
										<label class="btn btn-info btn-flat btn-sm col-sm-4 col-sm-4 col-md-4 col-lg-3 form-check-label <?php if (!empty($penerima_kuasa)): ?>active<?php endif ?>">
											<input id="penerima_kuasa_1" type="radio" name="penerima_kuasa" class="form-check-input" type="radio" value="1" <?php if (!empty($penerima_kuasa)): ?>checked<?php endif; ?> autocomplete="off" onchange="ubah_penerima_kuasa(this.value);"> Warga Desa
										</label>
										<label id="label_penerima_kuasa_2"  class="btn btn-info btn-flat btn-sm col-sm-4 col-md-4 col-lg-3 form-check-label <?php if (empty($penerima_kuasa)): ?>active<?php endif; ?>">
											<input id="penerima_kuasa_2" type="radio" name="penerima_kuasa" class="form-check-input" type="radio" value="2" <?php if (empty($penerima_kuasa)): ?>checked<?php endif; ?> autocomplete="off" onchange="ubah_penerima_kuasa(this.value);"> Warga Luar Desa
										</label>
									</div>
								</div>

								<div class="form-group penerima_kuasa_desa" <?php if (empty($penerima_kuasa)): ?>style="display: none;"<?php endif; ?>>
									<label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:-10px;padding-top:10px;padding-bottom:10px"><strong>DATA PENERIMA KUASA DARI DATABASE</strong></label>
								</div>

								<div class="form-group penerima_kuasa_desa" <?php if (empty($penerima_kuasa)): ?>style="display: none;"<?php endif; ?>>
									<label for="penerima_kuasa_desa" class="col-sm-3 control-label" ><strong>NIK / Nama</strong></label>
									<div class="col-sm-5">
										<select class="form-control input-sm select2-nik" id="id_penerima_kuasa" name="id_penerima_kuasa" style ="width:100%;"  onchange="submit_form_ambil_data('a_penerima_kuasa');">
											<option value="">--  Cari NIK / Nama Penduduk--</option>
											<?php foreach ($penduduk as $data): ?>
												<option value="<?= $data['id']?>" <?php selected($penerima_kuasa['nik'], $data['nik']); ?>><?= $data['info_pilihan_penduduk']?></option>
											<?php endforeach;?>
										</select>
									</div>
								</div>

								<?php if ($penerima_kuasa): ?>
									<?php $individu = $penerima_kuasa;?>
									<?php include("donjo-app/views/surat/form/konfirmasi_pemohon.php"); ?>
								<?php endif; ?>

								<?php if (empty($penerima_kuasa)): ?>
									<div class="form-group penerima_kuasa_luar_desa" >
										<label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:-10px;padding-top:10px;padding-bottom:10px"><strong>DATA PENERIMA KUASA DARI LUAR DESA</strong></label>
									</div>
									<div class="form-group penerima_kuasa_luar_desa">
										<label for="nama_penerima_kuasa" class="col-sm-3 control-label">Nama Penerima Kuasa</label>
										<div class="col-sm-8">
											<input  class="form-control input-sm required" type="text" placeholder="Nama Penerima Kuasa" name="nama_penerima_kuasa" value="<?= $_SESSION['post']['nama_penerima_kuasa']?>">
										</div>
									</div>
									<div class="form-group penerima_kuasa_luar_desa">
										<label for="nik_penerima_kuasa"  class="col-sm-3 control-label">NIK Penerima Kuasa</label>
										<div class="col-sm-8">
											<input  class="form-control input-sm required" type="text" placeholder="NIK Penerima Kuasa" name="nik_penerima_kuasa" value="<?= $_SESSION['post']['nik_penerima_kuasa']?>">
										</div>
									</div>
									<div class="form-group penerima_kuasa_luar_desa">
										<label for="tempat_lahir_penerima_kuasa"  class="col-sm-3 control-label">Tempat  / Tanggal Lahir / Umur</label>
										<div class="col-sm-3 col-lg-4">
											<input class="form-control input-sm required" type="text" name="tempat_lahir_penerima_kuasa" id="tempat_lahir_penerima_kuasa" placeholder="Tempat Lahir Penerima Kuasa" value="<?= $_SESSION['post']['tempat_lahir_penerima_kuasa']?>">
										</div>
										<div class="col-sm-3 col-lg-2">
											<div class="input-group input-group-sm date">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<input title="Pilih Tanggal" class="form-control input-sm required datepicker" name="tanggal_lahir_penerima_kuasa" type="text" placeholder="Tgl. Lahir" value="<?= $_SESSION['post']['tanggal_lahir_penerima_kuasa']?>" onchange="$('input[name=umur_penerima_kuasa]').val(_calculateAge($(this).val()));"/>
											</div>
										</div>
										<div class="col-sm-2">
											<input class="form-control input-sm required" name="umur_penerima_kuasa" readonly="readonly" placeholder="Umur (Tahun)" type="text" value="<?= $_SESSION['post']['umur_penerima_kuasa']?>">
										</div>
									</div>

									<div class="form-group penerima_kuasa_luar_desa">
										<label for="jkpenerima_kuasa" class="col-sm-3 control-label" ><strong>Jenis Kelamin / Pekerjaan</strong></label>
										<div class="col-sm-4">
											<input type="hidden" name="jkid_penerima_kuasa">
											<select class="form-control input-sm required" name="jkpenerima_kuasa" id="jkpenerima_kuasa" onchange="$('input[name=jkid_penerima_kuasa]').val($(this).find(':selected').data('jkid'));">
												<option value="">-- Jenis Kelamin --</option>
												<?php foreach ($sex as $data): ?>
													<option value="<?= ucwords(strtolower($data['nama']))?>" data-jkid="<?= $data['id']?>" <?php selected(ucwords(strtolower($data['nama'])), $_SESSION['post']['jkpenerima_kuasa']) ?>><?= ucwords($data['nama'])?></option>
												<?php endforeach;?>
											</select>
										</div>
										<div class="col-sm-4">
											<input type="hidden" name="pekerjaanid_penerima_kuasa">
											<select class="form-control input-sm required" name="pekerjaanpenerima_kuasa" id="pekerjaanpenerima_kuasa" onchange="$('input[name=pekerjaanid_penerima_kuasa]').val($(this).find(':selected').data('pekerjaanid'));">
												<option value="">-- Pekerjaan --</option>
												<?php foreach ($pekerjaan as $data): ?>
													<option value="<?= $data['nama']?>" data-pekerjaanid="<?= $data['id']?>" <?php if ($data['nama']==$_SESSION['post']['pekerjaanpenerima_kuasa']): ?>selected<?php endif; ?>><?= $data['nama']?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
									<div class="form-group penerima_kuasa_luar_desa">
										<label for="alamat_penerima_kuasa"  class="col-sm-3 control-label">Alamat / RT / RW</label>
										<div class="col-sm-4">
											<input class="form-control input-sm required" type="text" name="alamat_penerima_kuasa" id="alamat_penerima_kuasa" placeholder="Alamat" value="<?= $_SESSION['post']['alamat_penerima_kuasa']?>">
										</div>
										<div class="col-sm-2">
											<input class="form-control input-sm required" type="text" name="rt_penerima_kuasa" id="rt_penerima_kuasa" placeholder="RT" value="<?= $_SESSION['post']['rt_penerima_kuasa']?>">
										</div>
										<div class="col-sm-2">
											<input class="form-control input-sm required" name="rw_penerima_kuasa" id="rw_penerima_kuasa"  type="text" placeholder="RW" value="<?= $_SESSION['post']['rw_penerima_kuasa']?>">
										</div>
									</div>
									<div class="form-group penerima_kuasa_luar_desa">
										<label for="alamat_penerima_kuasa"  class="col-sm-3 control-label">Desa / Kecamatan</label>
										<div class="col-sm-4">
											<input class="form-control input-sm required" type="text" name="desapenerima_kuasa" id="desapenerima_kuasa" placeholder="Desa" value="<?= $_SESSION['post']['desapenerima_kuasa']?>">
										</div>
										<div class="col-sm-4">
											<input class="form-control input-sm required" type="text" name="kecpenerima_kuasa" id="kecpenerima_kuasa" placeholder="Kecamatan" value="<?= $_SESSION['post']['kecpenerima_kuasa']?>">
										</div>
									</div>
									<div class="form-group penerima_kuasa_luar_desa">
										<label for="alamat_penerima_kuasa"  class="col-sm-3 control-label">Kabupaten / Provinsi</label>
										<div class="col-sm-4">
											<input class="form-control input-sm required" type="text" name="kabpenerima_kuasa" id="kabpenerima_kuasa" placeholder="Kabupaten" value="<?= $_SESSION['post']['kabpenerima_kuasa']?>">
										</div>
										<div class="col-sm-4">
											<input class="form-control input-sm required" type="text" name="provinsipenerima_kuasa" id="provinsipenerima_kuasa" placeholder="Provinsi" value="<?= $_SESSION['post']['provinsipenerima_kuasa']?>">
										</div>
									</div>
								<?php endif; ?>
								<!-- akhir penerima kuasa -->

								<div class="form-group">
									<label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:10px;padding-top:10px;padding-bottom:10px"><strong>PENANDA TANGAN</strong></label>
								</div>
								<div class="form-group">
										<label class="col-sm-3 control-label">Untuk / Keperluan</label>
										<div class="col-sm-8">
											<input class="form-control input-sm required" type="text" name="untuk_keperluan" id="untuk_keperluan" placeholder="Untuk/Keperluan" value="<?= $_SESSION['post']['untuk_keperluan']?>">
										</div>
									</div>
								<?php include("donjo-app/views/surat/form/_pamong.php"); ?>
							</div>
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
									<li>Surat Kuasa</li>

								</ol>
								<p>Aturan Pembuatan Surat Kuasa:  </p>
								<p>Pihak PEMBERI KUASA hanya warga desa yang terdata (bagi yang belum, mohon di data terlebih dahulu </p>
								<p>Pihak PENERIMA KUASA boleh warga desa sendiri atau warga luar desa.</p>
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
