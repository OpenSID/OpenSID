<div class="content-wrapper">
    <section class="content-header">
        <h1>Daftar Jenis</h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Daftar Jenis</li>
        </ol>
    </section>
    <section class="content" id="maincontent">
        <form id="mainformexcel" name="mainformexcel" action="" method="post" class="form-horizontal">
            <div class="row">
                <div class="col-md-3">
                    <?php $this->load->view('pembangunan/menu_kiri'); ?>
                </div>
                <div class="col-md-9">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <a href="<?= site_url('pembangunan_jenis/new') ?>" class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Data Baru">
                                <i class="fa fa-plus"></i>Tambah Data
                            </a>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table id="tabel-jenis" class="table table-bordered dataTable table-hover">
                                                    <thead class="bg-gray">
                                                        <tr>
                                                            <th class="text-center">No</th>
                                                            <th class="text-center">Aksi</th>
                                                            <th class="text-center">Jenis</th>
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
    $(function() {
        let tabelJenis = $('#tabel-jenis').DataTable({
            'processing': true,
            'serverSide': true,
            'autoWidth': false,
            'pageLength': 10,
            'order': [[2, 'asc']],
            'columnDefs': [{
                'orderable': false,
                'targets': [0,1]
            }],

            'ajax': {
                'url': "<?= site_url('pembangunan_jenis') ?>",
                'method': 'GET'
            },
            'columns': [
                {
                    'data': null,
                },
                {
                    'data': function (data) {
                        return `
                            <a href="<?= site_url('pembangunan_jenis/show/'); ?>${data.id}" title="Lihat Data" class="btn bg-info btn-flat btn-sm"><i class="fa fa-eye"></i></a>
                            <a href="<?= site_url('pembangunan_jenis/edit/'); ?>${data.id}" title="Edit Data"  class="btn bg-orange btn-flat btn-sm"><i class="fa fa-edit"></i> </a>
							<a href="#" data-href="<?= site_url("pembangunan_jenis/delete/"); ?>${data.id}" class="btn bg-maroon btn-flat btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
                            `
                    }, 'class': 'text-center', 'width': '15%'
                },
                {
                    'data': 'jenis'
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

        tabelJenis.on('draw.dt', function() {
            let PageInfo = $('#tabel-jenis').DataTable().page.info();
            tabelJenis.column(0, {
                page: 'current'
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1 + PageInfo.start;
            });
        });
    });
</script>