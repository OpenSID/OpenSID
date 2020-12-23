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
                <div class="col-md-3">
                    <?php $this->load->view('pembangunan/menu_kiri'); ?>
                </div>
                <div class="col-md-9">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <a href="<?= site_url('inventaris_tanah/form') ?>" class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Data Baru">
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
                                            <div class="table-responsive">
                                                <table class="table table-bordered dataTable table-hover">
                                                    <thead class="bg-gray">
                                                        <tr>
                                                            <th class="text-center">No</th>
                                                            <th class="text-center">Aksi</th>
                                                            <th class="text-center">Jenis</th>
                                                            <th class="text-center">Sumber Dana</th>
                                                            <th class="text-center">Judul</th>
                                                            <th class="text-center">Volume</th>
                                                            <th class="text-center">Tahun Anggaran</th>
                                                            <th class="text-center">Pelaksana</th>
                                                            <th class="text-center">Status</th>
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