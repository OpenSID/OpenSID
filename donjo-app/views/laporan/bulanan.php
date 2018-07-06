<div class="content-wrapper">
	<section class="content-header">
		<h1>Laporan Kependudukan Bulanan</h1>
		<ol class="breadcrumb">
			<li><a href="<?=site_url('hom_desa')?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Laporan Kependudukan Bulanan</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" action="<?= site_url('laporan')?>" method="post" class="form-horizontal">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">
            <div class="box-header with-border">
						<a href="<?= site_url('laporan/cetak')?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  target="_blank"><i class="fa fa-print "></i> Cetak</a>
							<a href="<?= site_url('laporan/excel')?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  target="_blank"><i class="fa fa-download"></i> Unduh</a>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
                  <?php foreach ($config as $data):?>
                    <h4 class="text-center"><strong>PEMERINTAH KABUPATEN/KOTA <?= strtoupper($data['nama_kabupaten'])?></strong></h4>
                    <h5 class="text-center"><strong>LAPORAN PERKEMBANGAN PENDUDUK (LAMPIRAN A - 9)</strong></h5>
                    <br/>
										<div class="form-group">
                      <label class="col-sm-2 control-label" for="kelurahan"><?= ucwords($this->setting->sebutan_desa)?>/Kelurahan</label>
                      <div class="col-sm-7 col-md-5">
                        <input type="text" class="form-control input-sm" value="<?= unpenetration($data['nama_desa'])?>" disabled/></input>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label" for="kecamatan"><?= ucwords($this->setting->sebutan_kecamatan)?></label>
                      <div class="col-sm-7 col-md-5">
                        <input type="text" class="form-control input-sm" value="<?= unpenetration($data['nama_kecamatan'])?>" disabled/></input>
                      </div>
                    </div>
                   <?php endforeach; ?>
                   <div class="form-group">
                    <label class="col-sm-2 control-label" for="tahun">Tahun</label>
                    <div class="col-sm-2">
                      <input name="tahun" placeholder="Tahun" type="text" class="form-control input-sm" value="<?= $tahun ?>"  onchange="formAction('mainform','<?= site_url('laporan/tahun')?>')" /></input>
                    </div>
										<label class="col-sm-2 col-md-1 control-label" for="tahun">Bulan</label>
                    <div class="col-sm-3 col-md-2">
                      <select class="form-control input-sm" name="bulan" onchange="formAction('mainform','<?= site_url('laporan/bulan')?>')" width="100%">
                        <option value="">Pilih bulan</option>
                        <option value="1" <?php if ($bulan=="1"):?>selected<?php endif;?>>Januari</option>
                        <option value="2" <?php if ($bulan=="2"):?>selected<?php endif;?>>Februari</option>
                        <option value="3" <?php if ($bulan=="3"):?>selected<?php endif;?>>Maret</option>
                        <option value="4" <?php if ($bulan=="4"):?>selected<?php endif;?>>April</option>
                        <option value="5" <?php if ($bulan=="5"):?>selected<?php endif;?>>Mei</option>
                        <option value="6" <?php if ($bulan=="6"):?>selected<?php endif;?>>Juni</option>
                        <option value="7" <?php if ($bulan=="7"):?>selected<?php endif;?>>Juli</option>
                        <option value="8" <?php if ($bulan=="8"):?>selected<?php endif;?>>Agustus</option>
                        <option value="9" <?php if ($bulan=="9"):?>selected<?php endif;?>>September</option>
                        <option value="10" <?php if ($bulan=="10"):?>selected<?php endif;?>>Oktober</option>
                        <option value="11" <?php if ($bulan=="11"):?>selected<?php endif;?>>November</option>
                        <option value="12" <?php if ($bulan=="12"):?>selected<?php endif;?>>Desember</option>
                      </select>
                    </div>
                  </div>
									<div class="form-group">
										<label class="col-sm-2 control-label" for="diketahui">Diketahui</label>
										<div class="col-sm-4 col-md-3">
											<select name="pamong" class="form-control input-sm" >
												<option value="">Pilih Staf Pemerintah <?= ucwords($this->setting->sebutan_desa)?></option>
												<?php foreach ($pamong AS $data):?>
													<option value="<?= $data['pamong_nama']?>"><?= $data['pamong_nama']?> (<?= $data['jabatan']?>)</option>
												<?php endforeach;?>
											</select>
										</div>
										<label class="col-sm-2 col-md-1 control-label" for="sebagai">Sebagai </label>
										<div class="col-sm-3">
											<select name="jabatan"  class="form-control input-sm">
												<option value="">Pilih Jabatan</option>
												<?php foreach ($pamong AS $data):?>
													<option ><?= $data['jabatan']?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
									<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
											<div class="row">
												<div class="col-sm-12">
													<?php include ("donjo-app/views/laporan/tabel_bulanan.php"); ?>
												</div>
											</div>
										</form>
									</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>

