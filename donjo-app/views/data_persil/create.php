<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengelolaan Data Persil <?=ucwords($this->setting->sebutan_desa)?> <?= $desa["nama_desa"];?></h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?=site_url('data_persil/clear')?>"> Daftar Persil</a></li>
			<li class="active">Pengelolaan Data Persil</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
			<div class="row">
				<div class="col-md-3">
          <?php $this->load->view('data_persil/menu_kiri.php')?>
				</div>
				<div class="col-md-9">
					<div class="box box-info">
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<form action="" id="main" name="main" method="POST" class="form-horizontal">
										<div class="box-body">
											<div class="form-group">
												<label class="col-sm-3 control-label" >Cari Nama Pemilik</label>
												<div class="col-sm-8">
														<select class="form-control input-sm select2" style="width: 100%;" id="nik" name="nik" onchange="$('#'+'main').submit();">
															<option selected="selected">-- Silakan Masukan NIK / Nama --</option>
															<?php foreach ($penduduk as $item): ?>
																<option value="<?= $item['id']?>">Nama : <?= $item['nama']." Alamat : ".$item['info']?></option>
															<?php endforeach;?>
														</select>
												</div>
											</div>
										</div>
									</form>
									<form name='mainform' action="<?= site_url('data_persil/simpan_persil')?>" method="POST"  class="form-horizontal">
										<div class="box-body">
											<input name="jenis_pemilik" type="hidden" value="1">
											<?php if ($pemilik): ?>
												<div class="form-group">
													<label class="col-sm-3 control-label">Nama Penduduk</label>
													<div class="col-sm-8">
														<input  class="form-control input-sm" type="text" placeholder="Nama Pemilik" value="<?= $pemilik["nama"] ?>" disabled >
														<input type="hidden" name="nik_lama" value="<?= $pemilik["nik_lama"] ?>"/>
														<input type="hidden" name="nik" value="<?= $pemilik["nik"] ?>"/>
														<input type="hidden" name="id" value="<?= $persil_detail["id"] ?>"/>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">NIK Pemilik</label>
													<div class="col-sm-8">
														<input  class="form-control input-sm" type="text" placeholder="NIK Pemilik" value="<?= $pemilik["nik"] ?>" disabled >
													</div>
												</div>
												<div class="form-group">
													<label for="alamat"  class="col-sm-3 control-label">Alamat Pemilik</label>
													<div class="col-sm-8">
														<textarea  class="form-control input-sm" placeholder="Alamat Pemilik" disabled><?= "RT ".$pemilik["rt"]." / RT ".$pemilik["rw"]." - ".strtoupper($pemilik["dusun"]) ?></textarea>
													</div>
												</div>
											<?php endif; ?>
											<div class="form-group">
												<label for="nama"  class="col-sm-3 control-label">Nomor Persil</label>
												<div class="col-sm-8">
													<input  id="nama" class="form-control input-sm required" type="text" placeholder="Nomor Surat Persil" name="nama" value="<?= $persil_detail["nopersil"] ?>">
												</div>
											</div>
											<div class="form-group">
												<label for="id_master"  class="col-sm-3 control-label">Jenis Persil</label>
												<div class="col-sm-4">
													<select class="form-control  input-sm select2" id="cid" name="cid">
														<option >-- Pilih Jenis Persil--</option>
														<?php foreach ($persil_jenis as $key=>$item): ?>
															<option value="<?= $key ?>" <?php if ($key==$persil_detail["persil_jenis_id"]): ?>selected<?php endif; ?>><?= $item[0]?></option>
														<?php endforeach;?>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label for="luas_tanah"  class="col-sm-3 control-label">Luas Tanah (M<sup>2</sup>)</label>
												<div class="col-sm-4">
													<input  id="luas" name="luas"  type="text"  class="form-control input-sm" placeholder="Luas" value="<?= $persil_detail["luas"] ?>"></input>
												</div>
											</div>
											<div class="form-group">
												<label for=""  class="col-sm-3 control-label"></label>
												<div class="col-sm-8">
													<p class="help-block"><code>Gunakan tanda titik (.) untuk bilangan pecahan</code></p>
												</div>
											</div>
											<div class="form-group">
												<label for="kelas_tanah"  class="col-sm-3 control-label">Kelas Tanah</label>
												<div class="col-sm-8">
													<input  id="kelas" name="kelas"  type="text"  class="form-control input-sm" placeholder="Tuliskan Kelas Tanah" value="<?= $persil_detail["kelas"] ?>"></input>
												</div>
											</div>
											<div class="form-group">
												<label for="sid"  class="col-sm-3 control-label">Peruntukan</label>
												<div class="col-sm-4">
													<select class="form-control  input-sm select2" id="sid" name="sid">
														<option >-- Pilih Peruntukan--</option>
														<?php foreach ($persil_peruntukan as $key=>$item): ?>
															<option value="<?= $key?>" <?php if ($key==$persil_detail["persil_peruntukan_id"]): ?>selected<?php endif; ?>><?= $item[0]?></option>
														<?php endforeach;?>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label for="pid"  class="col-sm-3 control-label">Lokasi Tanah</label>
												<div class="col-sm-4">
													<select class="form-control  input-sm select2" id="pid" name="pid">
														<option >-- Pilih Lokasi Tanah--</option>
														<?php foreach ($persil_lokasi as $key=>$item): ?>
															<option value="<?= $item["id"] ?>" <?php if ($item["id"]==$persil_detail["id_clusterdesa"]): ?>selected<?php endif; ?>><?= strtoupper($item["dusun"])." - RW ".$item["rw"]." / RT ".$item["rt"] ?></option>
														<?php endforeach;?>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label for="sppt"  class="col-sm-3 control-label">Nomor SPPT PBB</label>
												<div class="col-sm-8">
													<input  id="sppt" name="sppt"  type="text"  class="form-control input-sm" placeholder="Tuliskan Nomor SPPT PBB" value="<?= $persil_detail["no_sppt_pbb"] ?>"></input>
												</div>
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
	</section>
</div>

