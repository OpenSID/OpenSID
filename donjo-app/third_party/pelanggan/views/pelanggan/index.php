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
        <h1><?= $title ?></h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('beranda') ?>"><i class="fa fa-home"></i> Beranda</a></li>
            <li class="active"><?= $title ?></li>
        </ol>
    </section>
    <section class="content" id="maincontent">
        <?php if ($this->session->error_premium) : ?>
            <div class="box box-danger">
                <div class="box-header with-border">
                    <i class="icon fa fa-ban"></i>
                    <?php if ($this->session->error_premium) : ?>
                        <h3 class="box-title"><?= $this->session->error_premium ?></h3>
                    <?php elseif (! cek_koneksi_internet()) : ?>
                        <h3 class="box-title">Tidak Terhubung Dengan Jaringan</h3>
                    <?php endif ?>
                </div>
                <div class="box-body">
                    <?php if ($pesan = $this->session->error_premium_pesan) : ?>
                        <div class="callout callout-warning">
                            <h5><?= $pesan ?></h5>
                        </div>
                    <?php elseif (empty($response)) : ?>
                        <div class="callout callout-danger">
                            <h5>Data Gagal Dimuat, Harap Periksa Dibawah Ini</h5>
                            <h5>Fitur ini khusus untuk pelanggan Layanan <?= config_item('nama_lembaga') ?> (hosting, Fitur Premium, dll) untuk menampilkan status langganan.</h5>
                            <li>Periksan koneksi anda, pastikan sudah terhubung dengan jaringan internet.</li>
                            <li>Periksa logs error terakhir di menu <strong><a href="<?= site_url('info_sistem#log_viewer'); ?>" style="text-decoration:none;">Pengaturan > Info Sistem > Logs</a></strong></li>
                            <li>Token pelanggan tidak terontentikasi. Periksa [Layanan <?= config_item('nama_lembaga') ?> Token] di <a href="#" style="text-decoration:none;" class="atur-token"><strong>Pengaturan Pelanggan&nbsp;(<i class="fa fa-gear"></i>)</strong></a></li>
                            <li>Jika masih mengalami masalah harap menghubungi pelaksana masing-masing.
                        </div>
                    <?php endif ?>
                </div>
            </div>
        <?php endif ?>

        <?php if ($response) : ?>
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="small-box bg-blue">
                        <div class="inner">
                            <h4>PEMESANAN LAYANAN</h4>
                            <h6 style="padding-left: 10px;">
                                <?php foreach ($response->body->pemesanan as $pemesanan) : ?>
                                    <?php if ($pemesanan->status_pemesanan == 'aktif') : ?>
                                        <?php foreach ($pemesanan->layanan as $layanan) : ?>
                                            <?php
                                            if (preg_match('/Hosting|Domain/', $layanan->nama) && ! file_exists('mitra')) {
                                                fopen('mitra', 'wb');
                                            }
                                            ?>
                                            <li><?= $layanan->nama ?></li>
                                        <?php endforeach ?>
                                    <?php endif ?>
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
                            <h5><?= tgl_indo($response->body->tanggal_berlangganan->mulai); ?> (Premium)</h5>
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
                            <h5><?= tgl_indo($response->body->tanggal_berlangganan->akhir); ?> (Premium)</h5>
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
                            <h5>Dokumen permohonan kerjasama Desa anda sedang diperiksa oleh Pelaksana Layanan <?= config_item('nama_lembaga') ?>.</h5>
                        </div>
                    </div>
                </div>
            <?php endif ?>
            <div class="box box-info">
                <?php if (can('u')) : ?>
                    <div class="box-header with-border">
                        <b>Rincian Pelanggan <a href="javascript:;" title="Perbarui" class="btn btn-social btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block perbarui"><i class="fa fa-refresh"></i> Perbarui</a></b>
                    </div>
                <?php endif ?>
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
                                <?php if (! config_item('demo_mode') && $response->body->token) : ?>
                                    <tr>
                                        <td>Token</td>
                                        <td> : </td>
                                        <td>
                                            <table>
                                                <tr>
                                                    <td><textarea id="token" rows="4" cols="180" type="text" class="form-control" readonly><?= $response->body->token ?></textarea></td>
                                                    <td>
                                                        <div class="input-group-text"><a href="#" id="copy" title="Copy"><i class="fa fa-copy"></i></a></div>
                                                    </td>
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
            <div class="box box-info" id="box-pemesanan-premium">
                <div class="box-header with-border">
                    <b>Rincian Pemesanan Premium</b>
                    <?php if ($permohonan = $this->session->errors->messages->permohonan) : ?>
                        <p class="error"><?= $permohonan ?></p>
                    <?php endif ?>
                    <br><br>
                    <span class="text-danger">Info: Nota faktur dapat dicetak hanya untuk pembayaran yang sudah lunas dan telah melakukan pendaftaran kerjasama sampai verifikasi email.</span>
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
                                    <tr id="tbl-premium-<?= $number ?>">
                                        <td class="padat"><?= ($number + 1) ?></td>
                                        <td class="aksi">
                                            <?php
                                            $server = config_item('server_layanan');
                                            $token  = $this->setting->layanan_opendesa_token;
                                            ?>
                                            <?php if ($pemesanan->status_pembayaran == 1 && $response->body->status_langganan === 'terdaftar' || $response->body->status_langganan === 'menunggu verifikasi pendaftaran' || $response->body->status_langganan === 'email telah terverifikasi') : ?>
                                                <a target="_blank" href="<?= "{$server}/api/v1/pelanggan/pemesanan/faktur?invoice={$pemesanan->faktur}&token={$token}" ?>" class="btn btn-social bg-purple btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Nota Faktur"><i class="fa fa-print"></i>Cetak Nota Faktur</a>
                                            <?php endif; ?>
                                            <?php if ($notif_langganan['warna'] == 'orange') : ?>
                                                <a href="<?= site_url('pelanggan/perpanjang_layanan?pemesanan_id=' . $pemesanan->id . '&server=' . $server . '&invoice=' . $pemesanan->faktur . '&token=' . $token) ?>" class="btn btn-social bg-green btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Perpanjang Layanan"><i class="fa fa-refresh"></i>Perpanjang</a>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php $pemesananPremium = 0; ?>
                                            <?php foreach ($pemesanan->layanan as $layanan) : ?>
                                                <?php if ($layanan->kategori_id == 4) : ?>
                                                    <?php $pemesananPremium++; ?>
                                                    <a href="#" data-parent="#layanan" data-target="<?= '#layanan' . $layanan->id ?>" data-toggle="modal" class="mt-5 btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Klik untuk melihat ketentuan <?= $layanan->nama; ?>"><i class="fa fa-info"></i> <?= $layanan->nama; ?><?= $layanan->number; ?></a><br>
                                                    <?= '<style>#tbl-premium-' . $number . ' { display:table-row!important;}</style>'; ?>

                                                <?php else : ?>
                                                    <?= '<style>#tbl-premium-' . $number . ' { display:none;}</style>'; ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </td>
                                        <td class="padat"><?= tgl_indo($pemesanan->tgl_mulai); ?></td>
                                        <td class="padat">
                                            <?php
                                            $a_date = $pemesanan->tgl_akhir;
                                            echo tgl_indo(date('Y-m-t', strtotime($a_date)));
                                            ?>
                                        </td>
                                        <td class="padat">
                                            <?php if ($notif_langganan['warna'] == 'orange') : ?>
                                                <span class="label label-warning">perlu diperpanjang</span>
                                            <?php else : ?>
                                                <span class="label label-<?= $pemesanan->status_pemesanan === 'aktif' ? 'success' : 'danger' ?>"><?= $pemesanan->status_pemesanan ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="padat">
                                            <span class="label label-<?= $pemesanan->status_pembayaran == 1 ? 'success' : 'danger' ?>"><?= $pemesanan->status_pembayaran == 1 ? 'lunas' : 'belum lunas' ?></span>
                                        </td>
                                    </tr>
                                    <?php if ($pemesananPremium == 0) : ?>
                                        <?= '<style>#box-pemesanan-premium { display:none;}</style>'; ?>
                                    <?php endif; ?>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="box box-info" id="box-pemesanan-lainnya">
                <div class="box-header with-border">
                    <b>Rincian Pemesanan Lainnya</b>
                    <?php if ($permohonan = $this->session->errors->messages->permohonan) : ?>
                        <p class="error"><?= $permohonan ?></p>
                    <?php endif ?>
                    <br><br>
                    <span class="text-danger">Info: Nota faktur dapat dicetak hanya untuk pembayaran yang sudah lunas dan telah melakukan pendaftaran kerjasama sampai verifikasi email.</span>
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
                                <?php $pemesananLainnya = 0; ?>
                                <?php foreach ($response->body->pemesanan as $number => $pemesanan) : ?>
                                    <tr id="tbl-pemesanan-<?= $number ?>">
                                        <td class="padat"><?= ($number + 1) ?></td>
                                        <td class="aksi">
                                            <?php
                                            $server = config_item('server_layanan');
                                            $token  = $this->setting->layanan_opendesa_token;
                                            ?>
                                            <?php if ($pemesanan->status_pembayaran == 1 && $response->body->status_langganan === 'terdaftar' || $response->body->status_langganan === 'menunggu verifikasi pendaftaran' || $response->body->status_langganan === 'email telah terverifikasi') : ?>
                                                <a target="_blank" href="<?= "{$server}/api/v1/pelanggan/pemesanan/faktur?invoice={$pemesanan->faktur}&token={$token}" ?>" class="btn btn-social bg-purple btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Nota Faktur"><i class="fa fa-print"></i>Cetak Nota Faktur</a>
                                            <?php endif; ?>
                                            <?php if ($notif_langganan['warna'] == 'orange') : ?>
                                                <a href="<?= site_url('pelanggan/perpanjang_layanan?pemesanan_id=' . $pemesanan->id . '&server=' . $server . '&invoice=' . $pemesanan->faktur . '&token=' . $token) ?>" class="btn btn-social bg-green btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Perpanjang Layanan"><i class="fa fa-refresh"></i>Perpanjang</a>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php foreach ($pemesanan->layanan as $layanan) : ?>
                                                <?php if ($layanan->kategori_id != 4) : ?>
                                                    <?php $pemesananLainnya++; ?>
                                                    <a href="#" data-parent="#layanan" data-target="<?= '#layanan' . $layanan->id ?>" data-toggle="modal" class="mt-5 btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Klik untuk melihat ketentuan <?= $layanan->nama; ?>"><i class="fa fa-info"></i> <?= $layanan->nama; ?></a><br>
                                                    <?= '<style>#tbl-pemesanan-' . $number . ' { display:table-row!important;}</style>'; ?>
                                                    <?php else : ?>
                                                    <?= '<style>#tbl-pemesanan-' . $number . ' { display:none;}</style>'; ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </td>
                                        <td class="padat"><?= tgl_indo($pemesanan->tgl_mulai); ?></td>
                                        <td class="padat"><?= tgl_indo($pemesanan->tgl_akhir); ?></td>
                                        <td class="padat">
                                            <?php if ($notif_langganan['warna'] == 'orange') : ?>
                                                <span class="label label-warning">perlu diperpanjang</span>
                                            <?php else : ?>
                                                <span class="label label-<?= $pemesanan->status_pemesanan === 'aktif' ? 'success' : 'danger' ?>"><?= $pemesanan->status_pemesanan ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="padat">
                                            <span class="label label-<?= $pemesanan->status_pembayaran == 1 ? 'success' : 'danger' ?>"><?= $pemesanan->status_pembayaran == 1 ? 'lunas' : 'belum lunas' ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                                <?php if ($pemesananLainnya == 0) : ?>
                                    <?= '<style>#box-pemesanan-lainnya { display:none;}</style>'; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="layanan">
                <?php foreach ($response->body->pemesanan as $pemesanan) : ?>
                    <?php foreach ($pemesanan->layanan as $layanan) : ?>
                        <div class="modal fade" id="layanan<?= $layanan->id ?>" style="">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <h4 class="modal-title">Ketentuan Layanan</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="box box-success">
                                            <div class="box-header with-border">
                                                <div class="text-center"><b>Ketentuan <?= $layanan->nama ?> ( <?= rupiah($layanan->harga) ?> )</b></div>
                                            </div>
                                            <div class="box-body">
                                                <?= $layanan->ketentuan ?? 'Belum tersedia'; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                <?php endforeach ?>
            </div>
        <?php endif ?>
    </section>
</div>
<script src="<?= asset('js/sweetalert2/sweetalert2.all.min.js') ?>"></script>
<link rel="stylesheet" href="<?= asset('js/sweetalert2/sweetalert2.min.css') ?>">

<script type="text/javascript">
    $('#copy').on('click', function() {
        $('#token').select();
        document.execCommand('copy');
    });

    $('.atur-token').click(function(event) {
        Swal.fire({
            title: 'Pengaturan Pelanggan',
            text: 'Layanan ' + `<?= config_item('nama_lembaga') ?>` + ' Token',
            customClass: {
                popup: 'swal-lg',
            },
            input: 'textarea',
            inputValue: '<?= config_item('demo_mode') ? '' : setting('layanan_opendesa_token') ?>',
            inputAttributes: {
                inputPlaceholder: 'Token pelanggan Layanan ' + `<?= config_item('nama_lembaga') ?>`,
            },
            showCancelButton: true,
            cancelButtonText: 'Tutup',
            confirmButtonText: 'Simpan',
            showLoaderOnConfirm: true,
            preConfirm: (token) => {
                //cek token
                var parse_token = parseJwt(token);
                var ambilversi = "<?= substr(str_replace('.', '', AmbilVersi()), 0, 4) ?>";
                var ambiltanggal = ((parse_token.tanggal_berlangganan.akhir).replace('-', '')).substr(2, 4);
                if (ambilversi != ambiltanggal) {
                    if (moment(parse_token.tanggal_berlangganan.akhir, 'YYYY-MM-DD').diff(moment()) < 0) { // jika perbedaanya minus

                        Swal.showValidationMessage(
                            `Token Berlangganan sudah berakhir. Tanggal berlangganan sampai : ${parse_token.tanggal_berlangganan.akhir}`
                        )
                        return;
                    }
                }

                return fetch(`<?= config_item('server_layanan') ?>/api/v1/pelanggan/pemesanan`, {
                        headers: {
                            "Authorization": `Bearer ${token}`,
                            "X-Requested-With": `XMLHttpRequest`,
                        },
                        method: 'post',
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText)
                        }
                        return response.json()
                    })
                    .catch(error => {
                        Swal.showValidationMessage(
                            `Request failed: ${error}`
                        )
                    })
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                let response = result.value
                let data = {
                    body: response
                }
                if (response.desa_id == undefined) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Request failed',
                        text: 'Verifikasi token Gagal',
                    })
                } else {
                    $.ajax({
                            url: `${SITE_URL}pelanggan/pemesanan`,
                            type: 'Post',
                            dataType: 'json',
                            data: data,
                        })
                        .done(function(response) {
                            if (response.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    timer: 2000,
                                    text: response.message,
                                }).then((result) => {
                                    window.location.replace('pelanggan');
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    timer: 2000,
                                    text: response.message,
                                });
                            }
                        })
                        .fail(function(e) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Request failed',
                            })
                        });
                }
            }
        })
    });

    $('.perbarui').click(function(event) {
        Swal.fire({
            title: 'Sedang Memproses',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading()
            }
        });
        $.ajax({
                url: `<?= config_item('server_layanan') ?>/api/v1/pelanggan/pemesanan`,
                headers: {
                    "Authorization": `Bearer <?= setting('layanan_opendesa_token') ?>`,
                    "X-Requested-With": `XMLHttpRequest`,
                },
                type: 'Post',
            })
            .done(function(response) {
                let data = {
                    body: response
                }
                $.ajax({
                        url: `${SITE_URL}pelanggan/pemesanan`,
                        type: 'Post',
                        dataType: 'json',
                        data: data,
                    })
                    .done(function(result) {
                        if (result.status == false) {
                            Swal.fire({
                                title: 'Token Gagal',
                                text: result.message
                            })
                            return
                        }
                        Swal.fire({
                            title: 'Berhasil Tersimpan',
                        })
                        window.location.replace(`${SITE_URL}pelanggan`);

                    })
                    .fail(function(e) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Request failed',
                        })
                    });
            })
            .fail(function() {
                console.log("error");
            });
    });
</script>