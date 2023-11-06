<div class="content-wrapper">
    <section class="content-header">
        <h1>Perpanjang Layanan</h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('beranda') ?>"><i class="fa fa-home"></i> Beranda</a></li>
            <li class="active">Layanan Pelanggan</li>
        </ol>
    </section>
    <section class="content" id="maincontent">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Form Perpanjang Layanan</h3>
                <a target="_blank" href="<?= "{$server}/api/v1/pelanggan/pemesanan/deskripsi-faktur?invoice={$invoice}&token={$token}" ?>" class="btn btn-social bg-purple btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Nota Faktur Pemesanan Sebelumnya"><i class="fa fa-print"></i>Lihat Total Pembayaran dan Deskripsi Pemesanan Disini</a>
            </div>
            <form id="validasi" action="<?= site_url('pelanggan/perpanjang') ?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Pemesanan ID</label>
                        <div class="col-sm-8">
                            <input class="form-control input-sm required" type="text" name="pemesanan_id" value="<?= $pemesanan_id ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="upload">Unggah Bukti Pembayaran<code>(format .pdf)</code></label>
                        <div class="col-sm-8">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="file_path" name="permohonan">
                                <input id="file" type="file" class="hidden" name="permohonan" accept=".pdf">
                                <span class="input-group-btn">
									<button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i> Pilih</button>
								</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <a href="<?= site_url('pelanggan') ?>" type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Kembali</a>
                    <button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right" <?= in_array($status, [5, 6]) ? 'disabled' : '' ?>><i class="fa fa-check"></i> Simpan</button>
                </div>
            </form>
        </div>
    </section>
</div>