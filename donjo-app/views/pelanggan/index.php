<style>
    .small-box {
        border-radius: 5px;
    }

    .small-box .icon {
        top: 3px;
    }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Info Layanan Pelanggan</h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Info Layanan Pelanggan</li>
        </ol>
    </section>
    <section class="content" id="maincontent">
        <?php if (null === $response) : ?>
            <div class="box box-danger">
                <div class="box-header with-border">
                    <i class="icon fa fa-ban"></i>
                    <h3 class="box-title"><?= "{$this->session->error_status_langganan}" ?></h3>
                </div>
                <div class="box-body">
                    <div class="callout callout-danger">
                        <h5>Data Gagal Dimuat, Harap Periksa Dibawah Ini</h5>
                        <h5>Fitur ini khusus untuk pelanggan Layanan OpenDesa (hosting, Fitur Premium, dll) untuk menampilkan status langganan.</h5>
                        <li>Periksa logs error terakhir di menu <strong><a href="<?= site_url('info_sistem#log_viewer'); ?>" style="text-decoration:none;">Pengaturan > Info Sistem > Logs</a></strong></li>
                        <li>Token pelanggan tidak terontentikasi. Periksa [Layanan Opendesa Token] di <a href="#" style="text-decoration:none;" data-remote="false" data-toggle="modal" data-title="Pengaturan <?= ucwords($this->controller); ?>" data-target="#pengaturan"><strong>Pengaturan Pelanggan&nbsp;(<i class="fa fa-gear"></i>)</strong></a></li>
                        <li>Jika masih mengalami masalah harap menghubungi pelaksana masing-masing.
                    </div>
                </div>
            </div>
        <?php else : ?>
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="small-box bg-blue">
                        <div class="inner">
                            <h4>PEMESANAN LAYANAN</h4>
                            <h6>
                                <?php foreach ($response->body->pemesanan as $pemesanan) : ?>
                                    <?php foreach ($pemesanan->layanan as $layanan) : ?>
                                        <?php
                                        if (preg_match('/Hosting|Domain/', $layanan->nama) && ! file_exists('mitra')) {
                                            fopen('mitra', 'wb');
                                        }
                                        ?>
                                        <li><?= $layanan->nama ?></li>
                                    <?php endforeach ?>
                                <?php endforeach ?>
                            </h6>
                        </div>
                        <div class="icon">
                            <i class="ion ion-card"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h4>STATUS PELANGGAN</h4>
                            <h5><?= ucwords($response->body->status_langganan); ?></h5>
                        </div>
                        <div class="icon">
                            <i class="ion-person-add"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h4>MULAI BERLANGGANAN</h4>
                            <h5><?= tgl_indo($response->body->tanggal_berlangganan->mulai); ?></h5>
                        </div>
                        <div class="icon">
                            <i class="ion ion-unlocked"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h4>AKHIR BERLANGGANAN</h4>
                            <h5><?= tgl_indo($response->body->tanggal_berlangganan->akhir); ?></h5>
                        </div>
                        <div class="icon">
                            <i class="ion ion-locked"></i>
                        </div>
                    </div>
                </div>
                <?php if ($response->body->status_langganan === 'aktif' || $response->body->status_langganan === 'suspended' || $response->body->status_langganan === 'tidak aktif' || $response->body->status_langganan === 'menunggu verifikasi email') : ?>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="box box-warning">
                            <div class="box-header with-border">
                                <i class="icon fa fa-info"></i>
                                <h3 class="box-title">Info</h3>
                            </div>
                            <div class="box-body">
                                <div class="callout callout-warning">
                                    <h5>Silahkan lakukan Pendaftaran Kerjasama minimal sampai Verifikasi Email, agar Anda bisa mencetak Nota Faktur.</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($response->body->status_langganan === 'menunggu verifikasi email') : ?>
                <div class="box box-info">
                    <div class="box-header with-border">
                        <i class="icon fa fa-info"></i>
                        <h3 class="box-title">Status Registrasi</h3> <a href="<?= site_url('pelanggan/perbarui') ?>" title="Perbarui" class="btn btn-social btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i> Perbarui</a>
                    </div>
                    <div class="box-body">
                        <div class="callout callout-info">
                            <h5>Silahkan cek email Anda untuk memverifikasi, atau kirim ulang pendaftaran kerjasama menggunakan email aktif untuk menerima link verifikasi baru.</h5>
                        </div>
                    </div>
                </div>
            <?php elseif ($response->body->status_langganan === 'menunggu verifikasi pendaftaran') : ?>
                <div class="box box-info">
                    <div class="box-header with-border">
                        <i class="icon fa fa-info"></i>
                        <h3 class="box-title">Status Registrasi</h3>
                    </div>
                    <div class="box-body">
                        <div class="callout callout-info">
                            <h5>Dokumen permohonan kerjasama Desa anda sedang diperiksa oleh Pelaksana Layanan OpenDesa.</h5>
                        </div>
                    </div>
                </div>
            <?php endif ?>
            <div class="box box-info">
                <div class="box-header with-border">
                    <b>Rincian Pelanggan <a href="<?= site_url('pelanggan/perbarui') ?>" title="Perbarui" class="btn btn-social btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i> Perbarui</a></b>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover tabel-rincian">
                            <tbody>
                            <tr>
                                <td width="20%">ID Pelanggan</td>
                                <td width="1">:</td>
                                <td><?= $response->body->id ?></td>
                            </tr>
                            <tr>
                                <td>KODE <?= strtoupper($this->setting->sebutan_desa) ?></td>
                                <td> : </td>
                                <td><?= $response->body->desa->kode_desa ?></td>
                            </tr>
                            <tr>
                                <td><?= strtoupper($this->setting->sebutan_desa) ?></td>
                                <td> : </td>
                                <td><?= "Desa {$response->body->desa->nama_desa}, Kecamatan {$response->body->desa->nama_kec}, Kabupaten {$response->body->desa->nama_kab}, Provinsi {$response->body->desa->nama_prov}" ?></td>
                            </tr>
                            <tr>
                                <td>Domain Desa</td>
                                <td> : </td>
                                <td><?= $response->body->domain ?></td>
                            </tr>
                            <tr>
                                <td>Nama Kontak</td>
                                <td> : </td>
                                <td>
                                    <?php foreach ($response->body->kontak as $kontak) : ?>
                                        <li><?= $kontak->nama ?></li>
                                    <?php endforeach ?>
                                </td>
                            </tr>
                            <?php if ($response->body->token) : ?>
                            <tr>
                                <td>Token</td>
                                <td> : </td>
                                <td>
                                    <table>
                                        <tr>
                                            <td><textarea id="token" rows="4" cols="180" type="text" class="form-control" readonly><?= $response->body->token ?></textarea></td>
                                            <td><div class="input-group-text"><a  href="#" id="copy" title="Copy"><i class="fa fa-copy"></i></a></div></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <?php endif ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="box box-info">
                <div class="box-header with-border">
                    <b>Rincian Pemesanan</b>
                    <?php if ($permohonan = $this->session->errors->messages->permohonan) : ?>
                        <p class="error"><?= $permohonan ?></p>
                    <?php endif ?>
                    <br><br>
                    <span class="text-danger">Info: Nota faktur dapat dicetak hanya untuk pembayaran yang sudah lunas.</span>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered dataTable table-hover tabel-daftar">
                            <thead class="bg-gray">
                            <tr>
                                <th>No</th>
                                <th>Aksi</th>
                                <th>Layanan</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Berakhir</th>
                                <th>Status Pemesanan</th>
                                <th>Status Pembayaran</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($response->body->pemesanan as $number => $pemesanan) : ?>
                                <tr>
                                    <td class="padat"><?= ($number + 1) ?></td>
                                    <td class="aksi">
                                        <?php
                                        $server = config_item('server_layanan');
                                $token          = $this->setting->layanan_opendesa_token;
                                ?>
                                        <?php if ($pemesanan->status_pembayaran == 1 && $response->body->status_langganan === 'terdaftar' || $response->body->status_langganan === 'menunggu verifikasi pendaftaran'): ?>
                                            <a target="_blank" href="<?= "{$server}/api/v1/pelanggan/pemesanan/faktur?invoice={$pemesanan->faktur}&token={$token}" ?>" class="btn btn-social bg-purple btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Nota Faktur"><i class="fa fa-print"></i>Cetak Nota Faktur</a>
                                        <?php endif; ?>
                                        <?php if ($pemesanan->mitra_id == ''): ?>
                                            <a href="#" data-toggle="modal" data-target="<?= "#{$pemesanan->id}" ?>" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Bukti Pembayaran"><i class="fa fa-file"></i>Bukti Pembayaran</a>
                                        <?php endif; ?>
                                        <?php if ($notif_langganan['warna'] == 'orange'): ?>
                                            <a href="<?= site_url('pelanggan/perpanjang_layanan?pemesanan_id=' . $pemesanan->id . '&server=' . $server . '&invoice=' . $pemesanan->faktur . '&token=' . $token) ?>" class="btn btn-social bg-green btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Perpanjang Layanan"><i class="fa fa-refresh"></i>Perpanjang</a>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php foreach ($pemesanan->layanan as $key => $layanan) : ?>
                                            <li>
                                                <a href="#" data-parent="#layanan" data-target="<?= '#' . url_title($layanan->nama, 'dash', true) ?>" data-toggle="collapse"><?= $layanan->nama; ?></a>
                                            </li>
                                        <?php endforeach; ?>
                                    </td>
                                    <td class="padat"><?= tgl_indo($pemesanan->tgl_mulai); ?></td>
                                    <td class="padat"><?= tgl_indo($pemesanan->tgl_akhir); ?></td>
                                    <td class="padat">
                                        <?php if ($notif_langganan['warna'] == 'orange'): ?>
                                            <span class="label label-warning">perlu diperpanjang</span>
                                        <?php else: ?>
                                            <span class="label label-<?= $pemesanan->status_pemesanan === 'aktif' ? 'success' : 'danger' ?>"><?= $pemesanan->status_pemesanan ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="padat">
                                        <span class="label label-<?= $pemesanan->status_pembayaran == 1 ? 'success' : 'danger' ?>"><?= $pemesanan->status_pembayaran == 1 ? 'lunas' : 'belum lunas' ?></span>
                                    </td>
                                    <div class="modal fade" id="<?= $pemesanan->id ?>" style="display: none;">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                    <h4 class="modal-title">Bukti Pembayaran</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <img class="img-thumbnail" src="<?= $pemesanan->bukti ?>" alt="<?= $pemesanan->bukti ?>">
                                                </div>
                                                <div class="modal-footer">
                                                    <a target="_blank" href="<?= $pemesanan->bukti ?>" role="button" class="btn btn-sm bg-navy" download="<?= $pemesanan->bukti ?>">Simpan</a>
                                                    <button type="button" class="btn btn-sm btn-info" data-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="layanan">
                <?php foreach ($response->body->pemesanan as $num1 => $pemesanan) : ?>
                    <?php foreach ($pemesanan->layanan as $num2 => $layanan) : ?>
                        <div id="<?= url_title($layanan->nama, 'dash', true) ?>" class="collapse">
                            <div class="box box-success">
                                <div class="box-header with-border">
                                    <div class="text-center"><b>Ketentuan <?= $layanan->nama ?> ( <?= rupiah($layanan->harga) ?> )</b></div>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <?= $layanan->ketentuan ?? 'Belum tersedia'; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                <?php endforeach ?>
            </div>
        <?php endif ?>
    </section>
</div>
<script>
    $('#copy').on('click', function() {
        $('#token').select();
        document.execCommand('copy');
    });
</script>
