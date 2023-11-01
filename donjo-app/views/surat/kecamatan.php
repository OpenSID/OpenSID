<div class="content-wrapper">
    <section class="content-header">
        <h1><?= $title ?></h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
            <li class="active"><?= $title ?></li>
        </ol>
    </section>
    <section class="content" id="maincontent">
        <?php if ($widgets) : ?>
            <?php $this->load->view('surat_keluar/surat_widgets'); ?>
        <?php endif ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                    <form id="mainform" name="mainform" method="post">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover" id="tabeldata">
                                                        <thead>
                                                            <tr>
                                                                <th class="padat">AKSI</th>
                                                                <th>JENIS SURAT</th>
                                                                <th>PEMOHON</th>
                                                                <th>PENANDATANGAN</th>
                                                                <th>STATUS</th>
                                                                <th>KETERANGAN</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($main as $item): ?>
                                                            <tr>
                                                                <td>
                                                                    <?php if($item->log_verifikasi == 5): ?>
                                                                        <a href="<?= site_url("api/surat/download/{$item->nomor}") ?>" target="_blank" class="btn btn-social btn-flat bg-black btn-sm" title="Unduh"><i class="fa fa-download"></i> Unduh</a>
                                                                    <?php endif ?>
                                                                </td>
                                                                <td><?= $item->nama ?></td>
                                                                <td><?= $item->penduduk->nama ?></td>
                                                                <td><?= $item->pengurus->nama ?></td>
                                                                <td>
                                                                    <?php if ($item->log_verifikasi == 1): ?>
                                                                        <span class="label label-warning">Menunggu Verifikasi Operator</span>
                                                                    <?php elseif ($item->log_verifikasi == 2): ?>
                                                                        <span class="label label-warning">Menunggu Verifikasi Sekretaris</span>
                                                                    <?php elseif ($item->log_verifikasi == 3): ?>
                                                                        <span class="label label-warning">Menunggu Verifikasi Camat</span>
                                                                    <?php elseif ($item->log_verifikasi == 4): ?>
                                                                        <span class="label label-primary">Menunggu Ditandatangani</span>
                                                                    <?php elseif ($item->log_verifikasi == 5): ?>
                                                                        <span class="label label-success">Sudah Ditandatangani</span>
                                                                    <?php else: ?>
                                                                        <span class="label label-danger">Ditolak</span>
                                                                    <?php endif ?>
                                                                </td>
                                                                <td><?= $item->keterangan ?></td>
                                                            </tr>
                                                            <?php endforeach ?>
                                                        </tbody>
                                                    </table>
                                                </div>
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

<script>
    $(document).ready(function() {
        $('#tabeldata').DataTable({
            responsive: true,
            processing: true,
            serverSide: false,
            autoWidth: false,
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "Semua"]
            ],
            pageLength: 10,
            language: {
                url: "<?= asset('bootstrap/js/dataTables.indonesian.lang') ?>",
            }
        });
    });
</script>