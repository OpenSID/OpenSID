<div class="content-wrapper">
    <section class="content-header">
        <h1>Pengaturan Album</h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('beranda') ?>"><i class="fa fa-home"></i> Beranda</a></li>
            <li><a href="<?= site_url('gallery') ?>"><i class="fa fa-dashboard"></i> Daftar Album</a></li>
            <li class="active">Pengaturan Album</li>
        </ol>
    </section>
    <section class="content" id="maincontent">
        <form id="validasi" action="<?= $form_action ?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
            <div class="box box-info">
                <div class="box-header with-border">
                    <a href="<?= site_url('gallery') ?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Artikel">
                        <i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar Album
                    </a>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="nama">Nama Album</label>
                        <div class="col-sm-6">
                            <input name="nama" class="form-control input-sm nomor_sk required" maxlength="50" type="text" value="<?= $gallery['nama'] ?>"></input>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="nama">Jenis</label>
                        <div class="col-sm-6">
                            <select name="jenis" id="jenis" class="form-control input-sm required">
                                <option value="1" <?= selected($gallery['jenis'], 1) ?>>File</option>
                                <option value="2" <?= selected($gallery['jenis'], 2) ?>>URL</option>
                            </select>
                        </div>
                    </div>
                    <div id="jenis-file" style="display: <?= jecho($dokumen['jenis'] == 2, true, 'none') ?>;">
                        <?php if ($gallery['gambar']) : ?>
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="nama"></label>
                                <div class="col-sm-6">
                                    <input type="hidden" name="old_gambar" value="<?= $gallery['gambar'] ?>">
                                    <img class="attachment-img img-responsive img-circle" src="<?= AmbilGaleri($gallery['gambar'], 'sedang') ?>" alt="Gambar Album">
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="upload">Unggah Gambar</label>
                            <div class="col-sm-6">
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control <?= jecho($gallery['gambar'], false, 'required') ?>" id="file_path">
                                    <input id="file" type="file" class="hidden" name="gambar" accept=".gif,.jpg,.png,.jpeg">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i> Browse</button>
                                    </span>
                                </div>
                                <p><label class="control-label">Batas maksimal pengunggahan berkas <strong><?= max_upload() ?> MB.</strong></label></p>
                            </div>
                        </div>
                    </div>
                    <div id="jenis-url" class="form-group" style="display: <?= jecho($dokumen['jenis'] != 2, true, 'none') ?>;">
                        <label class="control-label col-sm-4" for="nama">Link/URL</label>
                        <div class="col-sm-6">
                            <input id="url" name="url" class="form-control input-sm <?= jecho($dokumen['jenis'], 2, 'required') ?>" type="text" value="<?= $dokumen['url'] ?>"></input>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
                    <button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right confirm"><i class="fa fa-check"></i> Simpan</button>
                </div>
            </div>
        </form>
    </section>
</div>
<script>
    $(document).ready(function() {
        $('#jenis').on('change', function() {
            if (this.value == 1) {
                console.log('file');
                $('#jenis-file').show();
                $('#jenis-url').hide();
                $("#file_path").addClass("required");
                $("#url").removeClass("required");
            } else {
                console.log('url');
                $('#jenis-file').hide();
                $('#jenis-url').show();
                $("#file_path").removeClass("required");
                $("#url").addClass("required");
            }
        });
    });
</script>