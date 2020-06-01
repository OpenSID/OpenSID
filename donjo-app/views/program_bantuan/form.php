<style type="text/css">
	label.control-label.text-left { text-align: left }
	label.control-label.no-padding-top { padding-top: 0px }
</style>
<div class="content-wrapper">
  <?php $detail = $program[0];?>
	<section class="content-header">
		<h1>Peserta Program Bantuan</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('program_bantuan')?>"> Daftar Program Bantuan</a></li>
      <li><a href="<?= site_url("program_bantuan/detail/1/$detail[id]")?>/1"> Rincian Program Bantuan</a></li>
			<li class="active">Peserta Program Bantuan</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-body">
						<div class="box-header with-border">
							<a href="<?= site_url('program_bantuan')?>" class="btn btn-social btn-flat btn-primary btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Program Bantuan"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Daftar Program Bantuan</a>
							<a href="<?= site_url("program_bantuan/detail/$detail[id]")?>/1" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Rincian Program Bantuan"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Rincian Program Bantuan</a>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="row">
									<div class="col-sm-12">
										<div class="box-header with-border">
											<h3 class="box-title">Rincian Program</h3>
										</div>
										<div class="box-body">
											<table class="table table-bordered  table-striped table-hover" >
												<tbody>
													<tr>
														<td style="padding-top : 10px;padding-bottom : 10px; width:15%;" nowrap>Nama Program</td>
														<td> : <?= strtoupper($detail["nama"])?></td>
													</tr>
													<tr>
														<td style="padding-top : 10px;padding-bottom : 10px;" nowrap>Sasaran Peserta</td>
														<td> :  <?= $sasaran[$detail["sasaran"]]?></td>
													</tr>
													<tr>
														<td style="padding-top : 10px;padding-bottom : 10px;" nowrap>Masa Berlaku</td>
														<td> : <?= fTampilTgl($detail["sdate"],$detail["edate"])?></td>
													</tr>
													<tr>
														<td style="padding-top : 10px;padding-bottom : 10px;" >Keterangan</td>
														<td> : <?= $detail["ndesc"]?></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
									<div class="col-sm-12">
										<div class="box-header with-border">
											<h3 class="box-title">Tambah Peserta Program</h3>
										</div>
                     <div class="box-body">
                       <form action="" id="main" name="main" method="POST"  class="form-horizontal">
												<div class="form-group" >
									  			<label class="col-sm-4 col-lg-2 control-label <?php ($detail['sasaran'] != 1) and print('no-padding-top') ?>" for="nik"><?= $detail['judul_cari_peserta']?></label>
													<div class="col-sm-7">
														<select class="form-control select2 input-sm" id="nik" name="nik"  onchange="formAction('main')" style="width:100%" >
															<option selected="selected">-- Silakan Masukan <?= $detail['judul_cari_peserta']?> --</option>
															<?php foreach ($program[2]as $item):
										  					if (strlen($item["id"])>0): ?>
											  				  <option value="<?= $item['id']?>" <?php if ($individu['nik']==$item['nik']): ?>selected<?php endif; ?>>Nama : <?= $item['nama']." - ".$item['info']?></option>
																<?php endif;
                              endforeach;?>
  													</select>
													</div>
					    					</div>
                        <?php if ($individu): ?>
                          <?php include("donjo-app/views/program_bantuan/konfirmasi_peserta.php"); ?>
                        <?php endif; ?>
                      </form>
                      <form id="validasi" action="<?= $form_action?>/<?= $detail['id']?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
												<input type="hidden" name="nik" value="<?= $individu['nik']?>"  >
												<div class="form-group">
													<label for="kartu_nik" class="col-sm-11 control-label text-left">IDENTITAS PADA KARTU PESERTA</label>
												</div>
												<div class="form-group">
													<label for="no_id_kartu" class="col-sm-4 col-lg-2  control-label">Nomor Kartu Peserta</label>
													<div class="col-sm-7">
								  					<input  id="no_id_kartu" class="form-control input-sm required" type="text" placeholder="Nomor Kartu Peserta" name="no_id_kartu" >
													</div>
												</div>
												<div class="form-group">
													<label for="jenis_keramaian"  class="col-sm-4 col-lg-2 control-label">Gambar Kartu Peserta</label>
													<div class="col-sm-7">
														<div class="input-group input-group-sm ">
															<input type="text" class="form-control" id="file_path">
															<input type="file" class="hidden" id="file" name="satuan">
															<span class="input-group-btn">
																<button type="button" class="btn btn-info btn-flat"  id="file_browser"><i class="fa fa-search"></i> Browse</button>
															</span>
														</div>
														<p class="help-block text-red">Kosongkan jika tidak ingin mengunggah gambar.</p>
													</div>
												</div>
												<div class="form-group">
													<label for="kartu_nik"  class="col-sm-4 col-lg-2 control-label">NIK</label>
													<div class="col-sm-7">
														<input id="kartu_nik" class="form-control input-sm" type="text" placeholder="Nomor NIK Peserta" name="kartu_nik"  value="<?= $individu['nik_peserta']?>">
													</div>
												</div>
												<div class="form-group">
													<label for="kartu_nama"  class="col-sm-4 col-lg-2 control-label">Nama</label>
													<div class="col-sm-7">
														<input id="kartu_nama" class="form-control input-sm" type="text" placeholder="Nama Peserta" name="kartu_nama"  value="<?= $individu['nama']?>">
													</div>
												</div>
												<div class="form-group">
													<label for="kartu_tempat_lahir"  class="col-sm-4 col-lg-2 control-label">Tempat Lahir</label>
													<div class="col-sm-7">
													<input id="kartu_tempat_lahir" class="form-control input-sm" type="text" placeholder="Tempat Lahir" name="kartu_tempat_lahir"  value="<?= $individu['tempatlahir']?>">

													</div>
												</div>
												<div class="form-group">
										  		<label for="kartu_tanggal_lahir"  class="col-sm-4 col-lg-2 control-label">Tanggal Lahir</label>
													<div class="col-sm-7">
														<div class="input-group input-group-sm date">
															<div class="input-group-addon">
																<i class="fa fa-calendar"></i>
															</div>
															<input class="form-control input-sm pull-right" id="tgl_1" name="kartu_tanggal_lahir" placeholder="Tgl. Lahir" type="text" value="<?= tgl_indo_out($individu['tanggallahir'])?>">
														</div>
													</div>
												</div>
												<div class="form-group">
													<label for="kartu_alamat"  class="col-sm-4 col-lg-2 control-label">Alamat</label>
													<div class="col-sm-7">
											  		<input  id="kartu_alamat" class="form-control input-sm" type="text" placeholder="Alamat" name="kartu_alamat" value="<?= $individu['alamat_wilayah'];?>">
													</div>
												</div>
												<div class="box-footer">
													<div class="col-xs-12">
												  	<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
														<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
													</div>
												</div>
											</form>
                     </div>
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

