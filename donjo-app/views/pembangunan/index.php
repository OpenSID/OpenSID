<div class="content-wrapper">
    <section class="content-header">
        <h1>Daftar Pembangunan</h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Daftar Pembangunan</li>
        </ol>
    </section>
    <section class="content" id="maincontent">
        <form id="mainformexcel" name="mainformexcel" action="" method="post" class="form-horizontal">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <a href="<?= site_url('pembangunan/new') ?>" class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Data Baru">
                                <i class="fa fa-plus"></i>Tambah Data
                            </a>
                            <a href="#" class="btn btn-social btn-flat bg-purple btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Data" data-remote="false" data-toggle="modal" data-target="#cetakBox" data-title="Cetak Inventaris">
                                <i class="fa fa-print"></i>Cetak
                            </a>
                            <a href="#" class="btn btn-social btn-flat bg-navy btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Data" data-remote="false" data-toggle="modal" data-target="#unduhBox" data-title="Unduh Inventaris">
                                <i class="fa fa-download"></i>Unduh
                            </a>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-sm-2">
                                                <select class="form-control input-sm select2" id="tahun" name="tahun" style="width:100%;">
                                                    <option selected value="semua">Semua Tahun</option>
                                                    <?php for ($i = date('Y'); $i >= 1999; $i--) : ?>
                                                        <option value="<?= $i ?>"><?= $i ?></option>
                                                    <?php endfor; ?>
                                                </select>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="table-responsive">
                                                <table id="tabel-pembangunan" class="table table-bordered dataTable table-hover">
                                                    <thead class="bg-gray">
                                                        <tr>
                                                            <th><input type="checkbox" id="checkall" /></th>
                                                            <th class="text-center">No</th>
                                                            <th width="190px" class="text-center">Aksi</th>
                                                            <th class="text-center">Judul</th>
                                                            <th class="text-center">Sumber Dana</th>
                                                            <th class="text-center">Persentase</th>
                                                            <th class="text-center">Volume</th>
                                                            <th class="text-center">Tahun</th>
                                                            <th class="text-center">Pelaksana</th>
                                                            <th class="text-center">Lokasi</th>
                                                            <th class="text-center">Keterangan</th>
                                                            <th class="text-center">Created at</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
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
<?php $this->load->view('global/confirm_delete'); ?>
<script>
    $(document).ready(function() {
        let tabelPembangunan = $('#tabel-pembangunan').DataTable({
            'processing': true,
            'serverSide': true,
            'autoWidth': false,
            'pageLength': 10,
            'order': [
                [11, 'desc']
            ],
            'columnDefs': [{
                'orderable': false,
                'targets': [0, 1, 2, 5],
            }],
            'ajax': {
                'url': "<?= site_url('pembangunan') ?>",
                'method': 'POST',
                'data': function(d) {
                    d.tahun = $('#tahun').val();
                }
            },
            'columns': [
                {
                    'data': function(data) {
                        return `<input type="checkbox" name="id_cb[]" value="${data.id}" />`
                    }
                },
                {
                    'data': null,
                },
                {
                    'data': function(data) {
                        let status;
                        if (data.status == 1) {
                            status = `<a href="<?= site_url('pembangunan/lock/') ?>${data.id}" class="btn bg-navy btn-flat btn-sm" title="Non Aktifkan Pembangunan"><i class="fa fa-unlock"></i></a>`
                        } else {
                            status = `<a href="<?= site_url('pembangunan/unlock/') ?>${data.id}" class="btn bg-navy btn-flat btn-sm" title="Aktifkan Pembangunan"><i class="fa fa-lock"></i></a>`
                        }

                        return `
                            <a href="<?= site_url('pembangunan/edit/'); ?>${data.id}" title="Edit Data"  class="btn bg-orange btn-flat btn-sm"><i class="fa fa-edit"></i></a>
                            <a href="<?= site_url('pembangunan/lokasi_maps/'); ?>${data.id}" class="btn bg-olive btn-flat btn-sm" title="Lokasi Pembangunan"><i class="fa fa-map"></i></a>
                            <a href="<?= site_url('pembangunan_dokumentasi/show/'); ?>${data.id}" class="btn bg-purple btn-flat btn-sm" title="Rincian Dokumentasi Kegiatan"><i class="fa fa-bars"></i></a>
                            ${status}
							<a href="#" data-href="<?= site_url('pembangunan/delete/'); ?>${data.id}" class="btn bg-maroon btn-flat btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
                            `
                    }
                },
                {
                    'data': 'judul'
                },
                {
                    'data': 'sumber_dana'
                },
                {
                    'data': 'max_persentase'
                },
                {
                    'data': 'volume'
                },
                {
                    'data': 'tahun_anggaran'
                },
                {
                    'data': 'pelaksana_kegiatan'
                },
                {
                    'data': 'alamat'
                },
                {
                    'data': 'keterangan'
                },
                {
                    'data': 'created_at'
                }
            ],
            'language': {
                'url': "<?= base_url('/assets/bootstrap/js/dataTables.indonesian.lang') ?>"
            }
        });

        tabelPembangunan.on('draw.dt', function() {
            let PageInfo = $('#tabel-pembangunan').DataTable().page.info();
            tabelPembangunan.column(1, {
                page: 'current'
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1 + PageInfo.start;
            });
        });

        $('#tahun').on('select2:select', function (e) {
            tabelPembangunan.ajax.reload();
        });
    });
</script>