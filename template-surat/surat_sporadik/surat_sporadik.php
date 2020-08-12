<script>
  function ubah_pelaku(peran, asal)
	{
    $('#'+peran).val(asal);
    if (asal == 1)
		{
      $('.'+peran+'_desa').show();
      $('.'+peran+'_luar_desa').hide();
      // Mungkin bug di jquery? Terpaksa hapus class radio button
      $('#label_'+peran+'_2').removeClass('ui-state-active');
    }
		else
		{
      $('.'+peran+'_desa').hide();
      $('.'+peran+'_luar_desa').show();
			$('#nik').val(''); // Hapus id
      submit_form_ambil_data();
    }
    $('input[name=anchor').val(peran);
  }

  function ubah_saksi1(asal)
	{
    $('#saksi1').val(asal);
    if (asal == 1)
		{
      $('.saksi1_desa').show();
      $('.saksi1_luar_desa').hide();
      // Mungkin bug di jquery? Terpaksa hapus class radio button
      $('#label_saksi1_2').removeClass('active');
    }
		else
		{
      $('.saksi1_desa').hide();
			$('.saksi1_luar_desa').show();
			$('#id_saksi1').val(''); // Hapus id
			$('#id_saksi1_validasi').val('*'); // Hapus id
			submit_form_ambil_data();
    }
    $('input[name=anchor').val('saksi1');
  }


  function ubah_saksi2(asal)
	{
    $('#saksi2').val(asal);
    if (asal == 1)
		{
      $('.saksi2_desa').show();
      $('.saksi2_luar_desa').hide();
      // Mungkin bug di jquery? Terpaksa hapus class radio button
      $('#label_saksi2_2').removeClass('active');
    }
		else
		{
      $('.saksi2_desa').hide();
			$('.saksi2_luar_desa').show();
			$('#id_saksi2').val(''); // Hapus id
      $('#id_saksi2_validasi').val('*'); // Hapus id
      submit_form_ambil_data();
    }
    $('input[name=anchor').val('saksi2');
  }
  function submit_form_ambil_data()
	{
    $('input').removeClass('required');
    $('select').removeClass('required');
    $('#'+'validasi').attr('action','');
    $('#'+'validasi').attr('target','');
    $('#'+'validasi').submit();
  }
</script>
<div class="content-wrapper">
	<?php $this->load->view("surat/form/breadcrumb.php"); ?>
	<section class="content">
		<div class="row">
			<input id="nik_validasi" name="nik" type="hidden" value="<?= $_SESSION['post']['nik']?>">
			<input id="id_pemohon_validasi" name="id_pemohon" type="hidden" value="">
			<input id="id_saksi1_validasi" name="id_saksi1" type="hidden" value="<?= $_SESSION['id_saksi1']?>"/>
			<input id="id_saksi2_validasi" name="id_saksi2" type="hidden" value="<?= $_SESSION['id_saksi2']?>"/>
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?=site_url("surat")?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Kembali Ke Daftar Wilayah">
							<i class="fa fa-arrow-circle-left "></i>Kembali Ke Daftar Cetak Surat
						</a>
					</div>
					<div class="box-body">
						<form id="validasi" action="<?= $form_action?>" method="POST" target="_blank" class="form-horizontal">
							<div class="col-md-12">
								<input id="nik_validasi" name="nik" type="hidden" value="<?= $_SESSION['post']['nik']?>">
								<input id="id_pemohon_validasi" name="id_pemohon" type="hidden" value="">
								<input id="id_saksi1_validasi" name="id_saksi1" type="hidden" value="<?= $_SESSION['id_saksi1']?>"/>
								<input id="id_saksi2_validasi" name="id_saksi2" type="hidden" value="<?= $_SESSION['id_saksi2']?>"/>
								<div class="form-group subtitle_head">
									<label class="col-sm-3 control-label" for="status">PEMOHON</label>
									<div class="btn-group col-sm-8" data-toggle="buttons">
										<label id="pemohon_11" class="btn btn-info btn-flat btn-sm col-sm-2 form-check-label <?php if (!empty($individu)): ?>active<?php endif ?>">
											<input id="pemohon_1" type="radio" name="pemohon" class="form-check-input" type="radio" value="1" <?php if (!empty($individu)): ?>checked <?php endif ?> autocomplete="off" onchange="ubah_pelaku('pemohon',this.value);"> Warga Desa
										</label>
										<label id="pemohon_22" class="btn btn-info btn-flat btn-sm col-sm-2 form-check-label <?php if (empty($individu)): ?>active<?php endif; ?>">
											<input id="pemohon_2" type="radio" name="pemohon" class="form-check-input" type="radio" value="2" <?php if (empty($individu)): ?>checked<?php endif; ?> autocomplete="off" onchange="ubah_pelaku('pemohon',this.value);"> Warga Luar Desa
										</label>
									</div>
								</div>
								<div class="form-group pemohon_desa" <?php if (empty($individu)): ?>style="display: none;"<?php endif; ?>>
									<label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:-10px;padding-top:10px;padding-bottom:10px"><strong>DATA PEMOHON WARGA DESA</strong></label>
								</div>
								<div class="form-group pemohon_desa" <?php if (empty($individu)): ?>style="display: none;"<?php endif; ?>>
									<label for="nik"  class="col-sm-3 control-label">NIK / Nama</label>
									<div class="col-sm-6 col-lg-4">
										<select class="form-control input-sm select2-nik" id="nik" name="nik" style ="width:100%;" onchange="submit_form_ambil_data();">
											<option value="">--  Cari NIK / Nama Penduduk--</option>
											<?php foreach ($penduduk as $data): ?>
												<option value="<?= $data['id']?>" <?php selected($individu['nik'], $data['nik']); ?>><?= $data['info_pilihan_penduduk']?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
								<?php if ($individu): ?>
									<?php include("donjo-app/views/surat/form/konfirmasi_pemohon.php"); ?>
								<?php	endif; ?>
								<?php if (empty($individu)): ?>
									<div class="form-group pemohon_luar_desa" >
										<label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:-10px;padding-top:10px;padding-bottom:10px"><strong>DATA PEMOHON LUAR DESA</strong></label>
									</div>
									<div class="form-group pemohon_luar_desa">
										<label for="nomor"  class="col-sm-3 control-label">Nama</label>
										<div class="col-sm-8">
											<input name="nama_non_warga" class="form-control input-sm required" type="text" placeholder="Nama" value="<?= $_SESSION['post']['nama_non_warga']?>">
										</div>
									</div>
									<div class="form-group pemohon_luar_desa">
										<label for="nik_non_warga"  class="col-sm-3 control-label">Nomor KTP</label>
										<div class="col-sm-4">
											<input name="nik_non_warga" class="form-control input-sm required" type="text" placeholder="Nomor KTP" value="<?= $_SESSION['post']['nik_non_warga']?>">
										</div>
									</div>
									<div class="form-group pemohon_luar_desa">
										<label for="tempatlahir_pemohon"  class="col-sm-3 control-label">Tempat Tanggal Lahir</label>
										<div class="col-sm-4">
											<input name="tempatlahir_pemohon" class="form-control input-sm required" type="text" placeholder="Tempat Lahir" value="<?= $_SESSION['post']['tempatlahir_pemohon']?>">
										</div>
										<div class="col-sm-3 col-lg-2">
											<div class="input-group input-group-sm date">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<input title="Pilih Tanggal" class="form-control input-sm datepicker required" name="tanggallahir_pemohon" type="text" value="<?= $_SESSION['post']['tempatlahir_pemohon']?>"/>
											</div>
										</div>
									</div>
									<div class="form-group pemohon_luar_desa">
										<label for="pekerjaan_pemohon"  class="col-sm-3 control-label">Pekerjaan</label>
										<div class="col-sm-8">
											<input id="pekerjaan_pemohon" class="form-control input-sm required" type="text" placeholder="Pekerjaan" name="pekerjaan_pemohon">
										</div>
									</div>
									<div class="form-group pemohon_luar_desa">
										<label for="alamat_pemohon"  class="col-sm-3 control-label">Tempat Tinggal</label>
										<div class="col-sm-8">
											<input id="alamat_pemohon" class="form-control input-sm required" type="text" placeholder="Tempat Tinggal" name="alamat_pemohon">
										</div>
									</div>
								<?php endif; ?>
								<div class="form-group" >
									<label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:10px;padding-top:10px;padding-bottom:10px"><strong>ATAS BIDANG TANAH YANG TERLETAK DI</strong></label>
								</div>
								<div class="form-group">
									<label for="jalan"  class="col-sm-3 control-label">Jalan</label>
									<div class="col-sm-8">
										<input name="jalan" class="form-control input-sm" type="text" placeholder="Jalan" value="<?= $_SESSION['post']['jalan']?>">
									</div>
								</div>
								<div class="form-group">
									<label for="rtrw"  class="col-sm-3 control-label">RT/RW</label>
									<div class="col-sm-2">
										<input name="rtrw" class="form-control input-sm" type="text" placeholder="RT / RW" value="<?= $_SESSION['post']['rtrw']?>">
									</div>
								</div>
								<div class="form-group">
									<label for="desalurah"  class="col-sm-3 control-label">Desa / Kelurahan</label>
									<div class="col-sm-8">
										<input name="desalurah" class="form-control input-sm" type="text" placeholder="Desa / Kelurahan" value="<?= $desa['nama_desa']?>">
									</div>
								</div>
								<div class="form-group">
									<label for="camatt"  class="col-sm-3 control-label">Kecamatan</label>
									<div class="col-sm-8">
										<input name="camatt" class="form-control input-sm" type="text" value="<?= $desa['nama_kecamatan']?>">
									</div>
								</div>
								<div class="form-group">
									<label for="kabb"  class="col-sm-3 control-label">Kabupaten / Kota</label>
									<div class="col-sm-8">
										<input name="kabb" class="form-control input-sm" type="text" value="<?= $desa['nama_kabupaten']?>">
									</div>
								</div>
								<div class="form-group">
									<label for="nib"  class="col-sm-3 control-label">NIB</label>
									<div class="col-sm-8">
										<input name="nib" class="form-control input-sm required" type="text" placeholder="NIB" value="<?= $_SESSION['post']['nib']?>">
									</div>
								</div>
								<div class="form-group">
									<label for="luashak"  class="col-sm-3 control-label">Luas Tanah (m2)</label>
									<div class="col-sm-2">
										<input name="luashak" class="form-control input-sm required" type="text" placeholder="Luas Tanah" value="<?= $_SESSION['post']['luashak']?>">
									</div>
								</div>
								<div class="form-group">
									<label for="statustanah"  class="col-sm-3 control-label">Status Tanah</label>
									<div class="col-sm-8">
										<input name="statustanah" class="form-control input-sm required" type="text" placeholder="Status Tanah" value="<?= $_SESSION['post']['statustanah']?>">
									</div>
								</div>
								<div class="form-group">
									<label for="tanahuntuk"  class="col-sm-3 control-label">Dipergunakan</label>
									<div class="col-sm-8">
										<input name="tanahuntuk" class="form-control input-sm required" type="text" placeholder="Dipergunakan Untuk" value="<?= $_SESSION['post']['tanahuntuk']?>">
									</div>
								</div>
								<div class="form-group" >
									<label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:10px;padding-top:10px;padding-bottom:10px"><strong>BATAS-BATAS </strong></label>
								</div>
								<div class="form-group">
									<label for="utara"  class="col-sm-3 control-label">Sebelah Utara</label>
									<div class="col-sm-8">
										<input name="utara" class="form-control input-sm required" type="text" placeholder="Batas Sebelah Utara" value="<?= $_SESSION['post']['utara']?>">
									</div>
								</div>
								<div class="form-group">
									<label for="timur"  class="col-sm-3 control-label">Sebelah Timur</label>
									<div class="col-sm-8">
										<input name="timur" class="form-control input-sm required" type="text" placeholder="Batas Sebelah Timur" value="<?= $_SESSION['post']['timur']?>">
									</div>
								</div>
								<div class="form-group">
									<label for="selatan"  class="col-sm-3 control-label">Sebelah Selatan</label>
									<div class="col-sm-8">
										<input name="selatan" class="form-control input-sm required" type="text" placeholder="Batas Sebelah Selatan" value="<?= $_SESSION['post']['selatan']?>">
									</div>
								</div>
								<div class="form-group">
									<label for="barat"  class="col-sm-3 control-label">Sebelah Barat</label>
									<div class="col-sm-8">
										<input name="barat" class="form-control input-sm required" type="text" placeholder="Batas Sebelah Barat" value="<?= $_SESSION['post']['barat']?>">
									</div>
								</div>
								<div class="form-group" >
									<label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:10px;padding-top:10px;padding-bottom:10px"><strong>TANAH DI PEROLEH</strong></label>
								</div>
								<div class="form-group">
									<label for="peroleh"  class="col-sm-3 control-label">Dari </label>
									<div class="col-sm-8">
										<input name="peroleh" class="form-control input-sm required" placeholder="Diperoleh Dari" type="text" value="<?= $_SESSION['post']['peroleh']?>">
									</div>
								</div>
								<div class="form-group">
									<label for="peroleh"  class="col-sm-3 control-label">Sejak Tahun</label>
									<div class="col-sm-2">
										<input name="perolehtahun" class="form-control input-sm required" type="text" placeholder="Tahun Perolehan" value="<?= $_SESSION['post']['perolehtahun']?>">
									</div>
								</div>
								<div class="form-group">
									<label for="peroleh"  class="col-sm-3 control-label">Dengan Jalan</label>
									<div class="col-sm-8">
										<input name="denganjalan" class="form-control input-sm required" type="text" placeholder="Jalan Perolehan" value="<?= $_SESSION['post']['denganjalan']?>">
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group subtitle_head">
									<label class="col-sm-3 control-label" for="status">SAKSI 1</label>
									<div class="btn-group col-sm-8" data-toggle="buttons">
										<label class="btn btn-info btn-flat btn-sm col-sm-2 form-check-label <?php if (!empty($saksi1)): ?>active<?php endif ?>">
											<input id="saksi1_1" type="radio" name="saksi1" class="form-check-input" type="radio" value="1" <?php if (!empty($saksi)): ?>checked<?php endif ?> onchange="ubah_saksi1(this.value);"> Warga Desa
										</label>
										<label id="label_saksi1_2" class="btn btn-info btn-flat btn-sm col-sm-2 form-check-label <?php if (empty($saksi1)): ?>active<?php endif; ?>">
											<input id="saksi1_2" type="radio" name="saksi1" class="form-check-input" type="radio" value="2" <?php if (empty($saksi1)): ?>checked<?php endif; ?> onchange="ubah_saksi1(this.value);"> Warga Luar Desa
										</label>
									</div>
								</div>
								<div class="form-group saksi1_desa" <?php if (empty($saksi1)): ?>style="display: none;"<?php endif; ?>>
									<label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:-10px;padding-top:10px;padding-bottom:10px"><strong>DATA SAKSI 1 WARGA DESA</strong></label>
								</div>
								<div class="form-group saksi1_desa" <?php if (empty($saksi1)): ?>style="display: none;"<?php endif; ?>>
									<label for="id_saksi1"  class="col-sm-3 control-label">NIK / Nama</label>
									<div class="col-sm-6 col-lg-4">
										<select class="form-control input-sm select2-nik" id="id_saksi1" name="id_saksi1" style ="width:100%;" onchange="submit_form_ambil_data();">
											<option value="">--  Cari NIK / Nama Penduduk--</option>
											<?php foreach ($penduduk as $data): ?>
												<option value="<?= $data['id']?>" <?php selected($saksi1['nik'], $data['nik']); ?>><?= $data['info_pilihan_penduduk']?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
								<?php if ($saksi1): //bagian info setelah terpilih
									$individu = $saksi1;
									include("donjo-app/views/surat/form/konfirmasi_pemohon.php");
								endif; ?>

								<?php if (empty($saksi1)): ?>
									<div class="form-group saksi1_luar_desa">
										<label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:-10px;padding-top:10px;padding-bottom:10px"><strong>DATA SAKSI 1 LUAR DESA</strong></label>
									</div>
									<div class="form-group saksi1_luar_desa">
										<label for="namasaksii"  class="col-sm-3 control-label">Nama</label>
										<div class="col-sm-8">
											<input name="namasaksii" class="form-control input-sm required" type="text" placeholder="Nama Saksi 1" value="<?= $_SESSION['post']['namasaksii']?>">
										</div>
									</div>
									<div class="form-group saksi1_luar_desa">
										<label for="umursaksii" class="col-sm-3 control-label">Umur</label>
										<div class="col-sm-2">
											<input name="umursaksii" class="form-control input-sm required" type="text" placeholder="Umur Saksi 1 (Tahun)" value="<?= $_SESSION['post']['umursaksii']?>">
										</div>
									</div>
									<div class="form-group saksi1_luar_desa">
										<label for="pekerjaansaksii" class="col-sm-3 control-label">Pekerjaan</label>
										<div class="col-sm-8">
											<input name="pekerjaansaksii" class="form-control input-sm required" type="text" placeholder="Pekerjaan Saksi 1" value="<?= $_SESSION['post']['pekerjaansaksii']?>">
										</div>
									</div>
									<div class="form-group saksi1_luar_desa">
										<label for="alamatsaksii" class="col-sm-3 control-label">Alamat</label>
										<div class="col-sm-8">
											<input name="alamatsaksii" class="form-control input-sm required" type="text" placeholder="Alamat Saksi 1" value="<?= $_SESSION['post']['alamatsaksii']?>">
										</div>
									</div>
								<?php endif; ?>
							</div>
							<div class="col-md-12">
								<div class="form-group subtitle_head">
									<label class="col-sm-3 control-label" for="status">SAKSI 2</label>
									<div class="btn-group col-sm-8" data-toggle="buttons">
										<label class="btn btn-info btn-flat btn-sm col-sm-2 form-check-label <?php if (!empty($saksi2)): ?>active<?php endif ?>">
											<input id="saksi2_1" type="radio" name="saksi2" class="form-check-input" type="radio" value="1" <?php if (!empty($saksi2)): ?>checked <?php endif ?> autocomplete="off" onchange="ubah_saksi2(this.value);"> Warga Desa
										</label>
										<label id="label_saksi2_2" class="btn btn-info btn-flat btn-sm col-sm-2 form-check-label <?php if (empty($saksi2)): ?>active<?php endif; ?>">
											<input id="saksi2_2" type="radio" name="saksi2" class="form-check-input" type="radio" value="2" <?php if (empty($saksi2)): ?>checked<?php endif; ?> autocomplete="off" onchange="ubah_saksi2(this.value);"> Warga Luar Desa
										</label>
									</div>
								</div>
								<div class="form-group saksi2_desa" <?php if (empty($saksi2)): ?>style="display: none;"<?php endif; ?>>
									<label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:-10px;padding-top:10px;padding-bottom:10px"><strong>DATA SAKSI 2 WARGA DESA</strong></label>
								</div>
								<div class="form-group saksi2_desa" <?php if (empty($saksi2)): ?>style="display: none;"<?php endif; ?>>
									<label for="id_saksi2"  class="col-sm-3 control-label">NIK / Nama</label>
									<div class="col-sm-6 col-lg-4">
										<select class="form-control input-sm select2-nik" id="id_saksi2" name="id_saksi2" style ="width:100%;" onchange="submit_form_ambil_data();">
											<option value="">--  Cari NIK / Nama Penduduk--</option>
											<?php foreach ($penduduk as $data): ?>
												<option value="<?= $data['id']?>" <?php selected($saksi2['nik'], $data['nik']); ?>><?= $data['info_pilihan_penduduk']?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
								<?php if ($saksi2): //bagian info setelah terpilih
									$individu = $saksi2;
									include("donjo-app/views/surat/form/konfirmasi_pemohon.php");
								endif; ?>
								<?php if (empty($saksi2)): ?>
									<div class="form-group saksi2_luar_desa">
										<label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:-10px;padding-top:10px;padding-bottom:10px"><strong>DATA SAKSI 2 LUAR DESA</strong></label>
									</div>
									<div class="form-group saksi2_luar_desa">
										<label for="namasaksiii"  class="col-sm-3 control-label">Nama</label>
										<div class="col-sm-8">
											<input name="namasaksiii" class="form-control input-sm required" type="text" placeholder="Nama Saksi 2" value="<?= $_SESSION['post']['namasaksii']?>">
										</div>
									</div>
									<div class="form-group saksi2_luar_desa">
										<label for="umursaksiii" class="col-sm-3 control-label">Umur</label>
										<div class="col-sm-2">
											<input name="umursaksiii" class="form-control input-sm required" type="text" placeholder="Umur Saksi 2 (Tahun)" value="<?= $_SESSION['post']['umursaksiii']?>">
										</div>
									</div>
									<div class="form-group saksi2_luar_desa">
										<label for="pekerjaansaksiii" class="col-sm-3 control-label">Pekerjaan</label>
										<div class="col-sm-8">
											<input name="pekerjaansaksiii" class="form-control input-sm required" type="text" placeholder="Pekerjaan Sakasi 2" value="<?= $_SESSION['post']['pekerjaansaksiii']?>">
										</div>
									</div>
									<div class="form-group saksi2_luar_desa">
										<label for="alamatsaksiii" class="col-sm-3 control-label">Alamat</label>
										<div class="col-sm-8">
											<input name="alamatsaksiii" class="form-control input-sm required" type="text" placeholder="Alamat Saksi 2" value="<?= $_SESSION['post']['alamatsaksiii']?>">
										</div>
									</div>
								<?php endif; ?>
							</div>
							<div class="col-md-12">
								<div class="form-group subtitle_head">
									<label class="col-sm-3 control-label"><strong>PENANDA TANGAN</strong></label>
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
