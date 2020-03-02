<div class="content-wrapper">
	<section class="content-header">
		<h1>Laporan Kependudukan Bulanan</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Laporan Kependudukan Bulanan</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" action="<?= site_url('laporan')?>" method="post" class="form-horizontal">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">
            <div class="box-header with-border">
							<a href="<?= site_url("{$this->controller}/dialog_cetak")?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Laporan" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Laporan"><i class="fa fa-print "></i> Cetak</a>
							<a href="<?= site_url("{$this->controller}/dialog_unduh")?>" title="Unduh Laporan" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Laporan" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Laporan"><i class="fa fa-download"></i> Unduh</a>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
                  <h4 class="text-center"><strong>PEMERINTAH KABUPATEN/KOTA <?= strtoupper($config['nama_kabupaten'])?></strong></h4>
                  <h5 class="text-center"><strong>LAPORAN PERKEMBANGAN PENDUDUK (LAMPIRAN A - 9)</strong></h5>
                  <br/>
									<div class="form-group">
                    <label class="col-sm-2 control-label" for="kelurahan"><?= ucwords($this->setting->sebutan_desa)?>/Kelurahan</label>
                    <div class="col-sm-7 col-md-5">
                      <input type="text" class="form-control input-sm" value="<?= $config['nama_desa']?>" disabled/></input>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="kecamatan"><?= ucwords($this->setting->sebutan_kecamatan)?></label>
                    <div class="col-sm-7 col-md-5">
                      <input type="text" class="form-control input-sm" value="<?= $config['nama_kecamatan']?>" disabled/></input>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="tahun">Tahun</label>
                    <div class="col-sm-2">
                      <input name="tahun" placeholder="Tahun" type="text" class="form-control input-sm required" value="<?= $tahun ?>"  onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?=site_url("laporan/bulan")?>');$('#'+'mainform').submit();}" /></input>
                    </div>
										<label class="col-sm-2 col-md-1 control-label" for="tahun">Bulan</label>
                    <div class="col-sm-3 col-md-2">
                      <select class="form-control input-sm" name="bulan" onchange="formAction('mainform','<?= site_url('laporan/bulan')?>')" width="100%">
                        <option value="">Pilih bulan</option>
                        <option value="1" <?php selected($bulan, "1"); ?>>Januari</option>
                        <option value="2" <?php selected($bulan, "2"); ?>>Februari</option>
                        <option value="3" <?php selected($bulan, "3"); ?>>Maret</option>
                        <option value="4" <?php selected($bulan, "4"); ?>>April</option>
                        <option value="5" <?php selected($bulan, "5"); ?>>Mei</option>
                        <option value="6" <?php selected($bulan, "6"); ?>>Juni</option>
                        <option value="7" <?php selected($bulan, "7"); ?>>Juli</option>
                        <option value="8" <?php selected($bulan, "8"); ?>>Agustus</option>
                        <option value="9" <?php selected($bulan, "9"); ?>>September</option>
                        <option value="10" <?php selected($bulan, "10"); ?>>Oktober</option>
                        <option value="11" <?php selected($bulan, "11"); ?>>November</option>
                        <option value="12" <?php selected($bulan, "12"); ?>>Desember</option>
                      </select>
                    </div>
                  </div>
									<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<div class="row">
											<div class="col-sm-12">
												<?php include ("donjo-app/views/laporan/tabel_bulanan.php"); ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</section>
</div>

