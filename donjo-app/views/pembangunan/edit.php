<div class="content-wrapper">
    <section class="content-header">
        <h1>Ubah Data Pembangunan</h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="<?= site_url('pembangunan') ?>"><i class="fa fa-dashboard"></i>Daftar Pembangunan</a></li>
            <li class="active">Ubah Data</li>
        </ol>
    </section>
    <section class="content" id="maincontent">
        <form class="form-horizontal" id="validasi" method="post" action="<?= site_url("pembangunan/update/{$main->id}") ?>">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <a href="<?= site_url('pembangunan') ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Pembangunan</a>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" style="text-align:left;" for="sumber_dana">Sumber Dana</label>
                                        <div class="col-sm-7">
                                            <select class="form-control input-sm select2" id="sumber_dana" name="sumber_dana" style="width:100%;">
                                                <?php foreach ($sumber_dana as $value) : ?>
                                                    <option <?= $value === $main->sumber_dana ? 'selected' : '' ?> value="<?= $value ?>"><?= $value ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" style="text-align:left;">Judul</label>
                                        <div class="col-sm-7">
                                            <input maxlength="50" class="form-control input-sm required" name="judul" id="judul" value="<?= $main->judul ?>" type="text" placeholder="Judul Pembangunan" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" style="text-align:left;">Volume</label>
                                        <div class="col-sm-7">
                                            <input maxlength="50" class="form-control input-sm required" name="volume" id="volume" value="<?= $main->volume ?>" type="text" placeholder="Volume Pembangunan" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" style="text-align:left;" for="tahun_anggaran">Tahun Anggaran</label>
                                        <div class="col-sm-7">
                                            <select class="form-control input-sm select2" id="tahun_anggaran" name="tahun_anggaran" style="width:100%;">
                                                <?php for ($i = date('Y'); $i >= 1999; $i--) : ?>
                                                    <option value="<?= $i ?>"><?= $i ?></option>
                                                <?php endfor; ?>
                                            </select>
                                            <script>
                                                $('#tahun_anggaran').val("<?= $main->tahun_anggaran?>");
                                            </script>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" style="text-align:left;">Pelaksana Kegiatan</label>
                                        <div class="col-sm-7">
                                            <input maxlength="50" class="form-control input-sm required" name="pelaksana_kegiatan" id="pelaksana_kegiatan" value="<?= $main->pelaksana_kegiatan ?>" type="text" placeholder="Pelaksana Kegiatan Pembangunan" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" style="text-align:left;">Lokasi Pembangunan</label>
                                        <div class="col-sm-7">
                                            <input maxlength="50" class="form-control input-sm required" name="lokasi" id="lokasi" value="<?= $main->lokasi ?> " type="text" placeholder="Lokasi Kegiatan Pembangunan" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" style="text-align:left;" for="keterangan">Keterangan</label>
                                        <div class="col-sm-7">
                                            <textarea rows="5" class="form-control input-sm required" name="keterangan" id="keterangan" placeholder="Keterangan"><?= $main->keterangan ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="col-xs-12">
                                <button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
                                <button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
</div>