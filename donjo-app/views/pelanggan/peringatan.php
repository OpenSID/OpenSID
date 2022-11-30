<div class="content-wrapper">
    <section class="content-header">
        <h1>Info Peringatan</h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Peringatan</li>
        </ol>
    </section>
    <section class="content" id="maincontent">
        <div class="box box-danger">
                <div class="box-header with-border">
                    <i class="icon fa fa-ban"></i>
                    <h3 class="box-title"><?= $this->session->error_premium ?></h3>
                </div>
                <div class="box-body">
                    <?php if ($pesan = $this->session->error_premium_pesan): ?>
                        <div class="callout callout-warning">
                            <h5><?= $pesan ?></h5>
                        </div>
                    <?php else: ?>
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
    </section>
</div>
<script src="<?= asset('js/sweetalert2/sweetalert2.all.min.js') ?>"></script>
<link rel="stylesheet" href="<?= asset('js/sweetalert2/sweetalert2.min.css') ?>">

<script type="text/javascript">
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
                return fetch(`<?= config_item('server_layanan') ?>/api/v1/pelanggan/pemesanan`, {
                    headers: {
                        "Authorization" : `Bearer ${token}`,
                        "X-Requested-With" : `XMLHttpRequest`,
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
                    body : response
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
</script>