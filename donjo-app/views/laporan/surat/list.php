<div class="content-wrapper">
    <section class="content-header">
        <h1>Laporan Surat Bulanan</h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url( 'hom_sid' ) ?>"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Laporan Surat Bulanan</li>
        </ol>
    </section>
    <section class="content" id="maincontent">
        <form id="mainform" name="mainform" action="<?= site_url( $this->controller . '/index' ) ?>" method="GET"
            class="form-horizontal">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <a href="<?= site_url( "{$this->controller}/dialog/cetak/$tahun/$bulan" ) ?>"
                                class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                                title="Cetak Laporan" data-remote="false" data-toggle="modal" data-target="#modalBox"
                                data-title="Cetak Laporan"><i class="fa fa-print "></i> Cetak</a>
                            <a href="<?= site_url( "{$this->controller}/dialog/unduh/$tahun/$bulan" ) ?>"
                                title="Unduh Laporan"
                                class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                                title="Unduh Laporan" data-remote="false" data-toggle="modal" data-target="#modalBox"
                                data-title="Unduh Laporan"><i class="fa fa-download"></i> Unduh</a>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h4 class="text-center"><strong>PEMERINTAH KABUPATEN/KOTA
                                            <?= strtoupper( $config['nama_kabupaten'] ) ?></strong></h4>
                                    <h5 class="text-center"><strong>STATISTIK LAPORAN PEMBUATAN SURAT</strong></h5>
                                    <br />
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"
                                            for="kelurahan"><?= ucwords( $this->setting->sebutan_desa ) ?>/Kelurahan</label>
                                        <div class="col-sm-7 col-md-5">
                                            <input type="text" class="form-control input-sm"
                                                value="<?= $config['nama_desa']?>" disabled /></input>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"
                                            for="kecamatan"><?= ucwords( $this->setting->sebutan_kecamatan ) ?></label>
                                        <div class="col-sm-7 col-md-5">
                                            <input type="text" class="form-control input-sm"
                                                value="<?= $config['nama_kecamatan']?>" disabled /></input>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="tahun">Tahun</label>
                                        <div class="col-sm-2">
                                            <input name="tahun" placeholder="Tahun" type="number"
                                                class="form-control input-sm required submit-on-changes"
                                                value="<?= $tahun?>" /></input>
                                        </div>
                                        <label class="col-sm-2 col-md-1 control-label" for="tahun">Bulan</label>
                                        <div class="col-sm-3 col-md-2">
                                            <select class="form-control input-sm submit-on-changes" name="bulan"
                                                width="100%">
                                                <?php for ( $no = 1; $no <= 12; $no++ ) {
                                                    printf( '<option value="%d" %s>%s</option>', $no, $bulan == $no ? 'selected' : '', opensid_nama_bulan( $no ) );
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="table-responsive">
                                                    <table id="tfhover"
                                                        class="table table-bordered table-hover tftable lap-bulanan">
                                                        <thead class="bg-gray">
                                                            <tr>
                                                                <th width='2%' class="text-center">No</th>
                                                                <th width='45%' class="text-center">Jenis
                                                                    Surat
                                                                </th>
                                                                <th width='23%' class="text-center">Jumlah</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ( $result as $i => $row ): ?>
                                                            <tr>
                                                                <td><?= $i + 1 ?></td>
                                                                <td>
                                                                    <a href="<?= site_url($this->controller . "/detail/$tahun/$bulan/{$row['id_jenis']}") ?>"
                                                                        data-remote="false" data-toggle="modal"
                                                                        data-target="#modalBox"
                                                                        data-title="<?= htmlentities( $row['jenis'] ) ?>">
                                                                        <?= htmlentities( $row['jenis'] ) ?>
                                                                    </a>
                                                                </td>
                                                                <td><?= $row['jumlah'] ?></td>
                                                            </tr>
                                                            <?php endforeach ?>
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
<script>
    $(() => {
        let els = $('.submit-on-changes');
        if (!els.length) return;
        els.on('change keypress', function (e) {
            if (e.type == 'keypress' && e.keyCode != 13) return;
            e.preventDefault();
            let form = $(this).parents('form');
            location.href = [
                form.attr('action'),
                form.find('[name=tahun]').val(),
                form.find('[name=bulan]').val()
            ].join('/');
        })
    })
</script>