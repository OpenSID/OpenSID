<div class="content-wrapper">
	<section class="content-header">
		<h1>Laporan Kependudukan Bulanan</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('beranda')?>"><i class="fa fa-home"></i> Beranda</a></li>
			<li class="active">Laporan Kependudukan Bulanan</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<form id="mainform" name="mainform" action="<?= site_url($this->controller); ?>" method="post" class="form-horizontal">
			<div class="row">
				<div class="col-md-12">
          <?php if ($data_lengkap): ?>
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
                        <select class="form-control input-sm required" name="tahun" onchange="formAction('mainform','<?= site_url('laporan/bulan')?>')" width="100%">
                          <option value="">Pilih tahun</option>
                          <?php for ($t = $tahun_lengkap; $t <= date('Y'); $t++): ?>
                            <option value=<?= $t ?> <?php selected($tahun, $t); ?>><?= $t ?></option>
                          <?php endfor; ?>
                        </select>
                      </div>
                      <label class="col-sm-2 col-md-1 control-label" for="tahun">Bulan</label>
                      <div class="col-sm-3 col-md-2">
                        <select class="form-control input-sm" name="bulan" onchange="formAction('mainform','<?= site_url('laporan/bulan')?>')" width="100%">
                          <option value="">Pilih bulan</option>
                          <?php foreach (bulan() as $no_bulan => $nama_bulan): ?>
                            <option value=<?= $no_bulan ?> <?php selected($bulan, $no_bulan); ?>><?= $nama_bulan ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                    <?php if ($sesudah_data_lengkap): ?>
                      <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                        <div class="row">
                          <div class="col-sm-12">
                            <?php include 'donjo-app/views/laporan/tabel_bulanan.php'; ?>
                          </div>
                        </div>
                      </div>
                    <?php else: ?>
                      <div class="box box-info">
                        <div class="box-header with-border">
                        </div>
                        <div class="box-body">
                          <div class="alert alert-warning">
                            Tahun-bulan sebelum tanggal lengkap data pada <?= $this->session->tgl_lengkap ?>
                          </div>
                        </div>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          <?php else:
              view('admin.bumindes.penduduk.data_lengkap', ['judul' => 'Data Penduduk']);
          endif; ?>
				</div>
			</div>
		</form>
	</section>
</div>

