<div class="content-wrapper">
    <section class="content-header">
        <h1>Isi Data Jenis Pembangunan</h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="<?= site_url('pembangunan_sumber_dana') ?>"><i class="fa fa-dashboard"></i>Daftar Sumber Dana</a></li>
            <li class="active">Detail</li>
        </ol>
    </section>
    <section class="content" id="maincontent">
        <form class="form-horizontal" id="validasi" method="post" action="<?= site_url("pembangunan_sumber_dana/update/{$main->id}") ?>">
            <div class="row">
                <div class="col-md-3">
                    <?php $this->load->view('pembangunan/menu_kiri'); ?>
                </div>
                <div class="col-md-9">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <a href="<?= site_url('pembangunan_sumber_dana') ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Sumber Dana</a>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" style="text-align:left;" for="no_sertifikat">Sumber Dana</label>
                                        <div class="col-sm-7">
                                            <input maxlength="50" class="form-control input-sm required" name="sumber_dana" id="sumber_dana" type="text" placeholder="Jenis Pembangunan" value="<?= $main->sumber_dana ?>"/>
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