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
                    <?php if ($this->tab_ini == 10) : ?>
                        <div class="box-header with-border">
                            <a href="<?= site_url('keluar/perorangan_clear') ?>" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-archive"></i> Rekam Surat Perorangan</a>
                            <a href="<?= site_url('keluar/graph') ?>" class="btn btn-social btn-flat bg-orange btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-pie-chart"></i> Pie Surat Keluar</a>
                            <a href="<?= site_url('keluar/dialog_cetak/cetak') ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Arsip Layanan Surat"><i class="fa fa-print"></i> Cetak</a>
                            <a href="<?= site_url('keluar/dialog_cetak/unduh') ?>" class="btn btn-social btn-flat bg-navy btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Unduh Arsip Layanan Surat"><i class="fa fa-download"></i> Unduh</a>
                            <a href="<?= site_url("{$this->controller}/clear") ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i>Bersihkan</a>
                        </div>
                    <?php endif ?>


                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                    <form id="mainform" name="mainform" method="post">
                                        <div class="row">
                                            <div class="col-sm-9">
                                                <div class="form-group">
                                                    <select class="form-control input-sm select2" name="tahun" onchange="formAction('mainform','<?= site_url($this->controller . '/filter/tahun') ?>')">
                                                        <option value="">Pilih Tahun</option>
                                                        <?php foreach ($tahun_surat as $thn) : ?>
                                                            <option value="<?= $thn['tahun'] ?>" <?php selected($tahun, $thn['tahun']) ?>><?= $thn['tahun'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <select class="form-control input-sm select2" name="bulan" onchange="formAction('mainform','<?= site_url($this->controller . '/filter/bulan') ?>')" <?= ($tahun != 0) ? '' : 'disabled'; ?>>
                                                        <option value="">Pilih Bulan</option>
                                                        <?php foreach ($bulan_surat as $bln) : ?>
                                                            <option value="<?= $bln['bulan'] ?>" <?php selected($bulan, $bln['bulan']) ?>><?= getBulan($bln['bulan']) ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <select class="form-control input-sm select2" name="jenis" onchange="formAction('mainform','<?= site_url($this->controller . '/filter/jenis') ?>')" style="width: 100%;">
                                                        <option value="">Pilih Jenis Surat</option>
                                                        <?php foreach ($jenis_surat as $data) : ?>
                                                            <option value="<?= $data['id'] ?>" <?php selected($jenis, $data['id']) ?>><?= $data['nama_surat'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <button id="perbaiki" type="button" title="Semua surat yang berstatus proses atau tidak ada statusnya akan di ubah menjadi siap cetak" class="btn btn-social btn-flat bg-orange btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-cogs "></i>Perbaiki</button>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="box-tools">
                                                    <div class="input-group input-group-sm pull-right">
                                                        <input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="<?= html_escape($cari) ?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= site_url("{$this->controller}/filter/cari") ?>');$('#'+'mainform').submit();}">
                                                        <div class="input-group-btn">
                                                            <button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= site_url("{$this->controller}/filter/cari") ?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered dataTable table-striped table-hover">
                                                        <thead class="bg-gray disabled color-palette">
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Aksi</th>
                                                                <th nowrap>Kode Surat</th>
                                                                <?php if ($o == 2) : ?>
                                                                    <th nowrap><a href="<?= site_url("keluar/index/{$p}/1") ?>">No Urut <i class='fa fa-sort-asc fa-sm'></i></a></th>
                                                                <?php elseif ($o == 1) : ?>
                                                                    <th nowrap><a href="<?= site_url("keluar/index/{$p}/2") ?>">No Urut <i class='fa fa-sort-desc fa-sm'></i></a></th>
                                                                <?php else : ?>
                                                                    <th nowrap><a href="<?= site_url("keluar/index/{$p}/1") ?>">No Urut <i class='fa fa-sort fa-sm'></i></a></th>
                                                                <?php endif; ?>
                                                                <th>Jenis Surat</th>
                                                                <?php if ($o == 4) : ?>
                                                                    <th nowrap><a href="<?= site_url("keluar/index/{$p}/3") ?>">Nama Penduduk <i class='fa fa-sort-asc fa-sm'></i></a></th>
                                                                <?php elseif ($o == 3) : ?>
                                                                    <th nowrap><a href="<?= site_url("keluar/index/{$p}/4") ?>">Nama Penduduk <i class='fa fa-sort-desc fa-sm'></i></a></th>
                                                                <?php else : ?>
                                                                    <th nowrap><a href="<?= site_url("keluar/index/{$p}/3") ?>">Nama Penduduk <i class='fa fa-sort fa-sm'></i></a></th>
                                                                <?php endif; ?>
                                                                <th nowrap>Keterangan</th>
                                                                <th nowrap>Ditandatangani Oleh</th>
                                                                <?php if ($o == 6) : ?>
                                                                    <th nowrap><a href="<?= site_url("keluar/index/{$p}/5") ?>">Tanggal <i class='fa fa-sort-asc fa-sm'></i></a></th>
                                                                <?php elseif ($o == 5) : ?>
                                                                    <th nowrap><a href="<?= site_url("keluar/index/{$p}/6") ?>">Tanggal <i class='fa fa-sort-desc fa-sm'></i></a></th>
                                                                <?php else : ?>
                                                                    <th nowrap><a href="<?= site_url("keluar/index/{$p}/5") ?>">Tanggal <i class='fa fa-sort fa-sm'></i></a></th>
                                                                <?php endif; ?>
                                                                <th>User</th>
                                                                <th>Status</th>
                                                                <th class="<?= jecho($this->tab_ini, 12, 'show-table') ?>" style="display: none;">Alasan Ditolak</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($main as $data) : ?>
                                                                <tr <?= jecho($data['status'], 0, 'class="select-row"'); ?>>
                                                                    <td class="padat"><?= $data['no'] ?></td>
                                                                    <td class="aksi">

                                                                        <?php if ($this->tab_ini == 10 && can('u')) : ?>
                                                                            <?php if (in_array($data['jenis'], [1, 2])) : ?>
                                                                                <a href="<?= site_url("keluar/edit_keterangan/{$data['id']}") ?>" title="Ubah Data" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Keterangan" class="btn bg-orange btn-flat btn-sm"><i class="fa fa-edit"></i></a>
                                                                            <?php endif; ?>
                                                                            <?php if (! in_array($data['jenis'], [1, 2]) && $data['status'] == 0) : ?>
                                                                                <a href="<?= site_url("surat/cetak/{$data['id']}"); ?>" class="btn btn-flat bg-orange btn-sm" title="Ubah" target="_blank"><i class="fa  fa-pencil-square-o"></i></a>
                                                                                <!-- hapus surat draft -->
                                                                                <?php if (can('h')) : ?>
                                                                                    <a href="#" data-href="<?= site_url("keluar/delete/{$data['id']}?redirect={$redirect}") ?>" class="btn bg-maroon btn-flat btn-sm" title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
                                                                                <?php endif; ?>
                                                                            <?php endif; ?>
                                                                        <?php endif; ?>

                                                                        <!-- hanya untuk surat permohonan -->
                                                                        <?php if (in_array($this->tab_ini, [11, 12])) : ?>
                                                                            <?php if (can('u')) : ?>
                                                                                <?php if (in_array($data['jenis'], [1, 2]) && $operator) : ?>
                                                                                    <a href="<?= site_url("keluar/edit_keterangan/{$data['id']}") ?>" title="Ubah Data" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Keterangan" class="btn bg-orange btn-flat btn-sm"><i class="fa fa-edit"></i></a>
                                                                                <?php else : ?>
                                                                                    <?php if ($data['status'] == 0 || $data['verifikasi'] == '-1') : ?>
                                                                                        <a href="<?= site_url("surat/cetak/{$data['id']}"); ?>" class="btn btn-flat bg-orange btn-sm" title="Ubah" target="_blank"><i class="fa  fa-pencil-square-o"></i></a>
                                                                                    <?php endif; ?>
                                                                                <?php endif; ?>

                                                                                <?php if ($data['verifikasi'] == '-1' && $data['mandiri'] == '1') : ?>
                                                                                    <button data-id="<?= $data['id'] ?>" type="button" class="btn btn-flat bg-blue btn-sm kembalikan" title="Kembalikan"> <i class="fa fa-undo"></i></button>
                                                                                <?php endif; ?>

                                                                                <?php if ($data['status_periksa'] == 0 && $data['status'] != 0) : ?>
                                                                                    <a href="<?= site_url("keluar/periksa/{$data['id']}"); ?>" class="btn bg-olive btn-sm" title="verifikasi"><i class="fa fa-check-square-o"></i></a>
                                                                                <?php endif; ?>

                                                                                <?php if ($data['status_periksa'] == 2) : ?>
                                                                                    <button data-id="<?= $data['id'] ?>" type="button" class="btn btn-flat bg-blue btn-sm passphrase " title="passphrase"> <i class="fa fa-key"></i></button>
                                                                                <?php endif; ?>
                                                                            <?php endif; ?>
                                                                        <?php endif; ?>

                                                                        <!-- hanya untuk arsip surat -->
                                                                        <?php if ($data['status'] == '1') : ?>
                                                                            <?php if (is_file($data['file_rtf'])) : ?>
                                                                                <a href="<?= site_url("{$this->controller}/unduh/rtf/{$data['id']}"); ?>" class="btn btn-flat bg-purple btn-sm" title="Unduh Surat RTF" target="_blank"><i class="fa fa-file-word-o"></i></a>
                                                                            <?php endif; ?>
                                                                            <?php if (is_file($data['file_pdf'])) : ?>
                                                                                <a href="<?= site_url("{$this->controller}/unduh/pdf/{$data['id']}"); ?>" class="btn btn-flat bg-fuchsia btn-sm" title="Cetak Surat PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                                                            <?php endif; ?>
                                                                            <?php if (is_file($data['file_lampiran'])) : ?>
                                                                                <a href="<?= site_url("{$this->controller}/unduh/lampiran/{$data['id']}"); ?>" target="_blank" class="btn btn-social btn-flat bg-olive btn-sm" title="Unduh Lampiran"><i class="fa fa-paperclip"></i> Lampiran</a>
                                                                            <?php endif; ?>
                                                                            <?php if ($data['urls_id']) : ?>
                                                                                <a href="<?= site_url("{$this->controller}/qrcode/{$data['urls_id']}"); ?>" title="QR Code" data-size="modal-sm" class="viewQR btn btn-flat bg-aqua btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="QR Code"><i class="fa fa-qrcode"></i></a>
                                                                            <?php endif; ?>
                                                                            <?php if ($data['isi_surat'] && $data['verifikasi_operator'] != '-1') : ?>
                                                                                <a href="<?= site_url("{$this->controller}/unduh/tinymce/{$data['id']}"); ?>" class="btn btn-flat bg-fuchsia btn-sm" title="Cetak Surat PDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                                                            <?php endif; ?>
                                                                            <?php if ($data['tte'] && $data['kecamatan'] == 2) : ?>
                                                                                <?php if ($this->setting->api_opendk_key) : ?>
                                                                                    <a data-id="<?= $data['id'] ?>" id="kirim-kecamatan" class="btn btn-social btn-flat bg-olive btn-sm" title="Kirim ke Kecamatan"><i class="fa fa-send"></i> Kirim ke Kecamatan</a>
                                                                                  <?php else: ?>
                                                                                    <a class="btn btn-social btn-flat bg-olive btn-sm" title="Kirim ke Kecamatan" disabled><i class="fa fa-send"></i> Kirim ke Kecamatan</a>
                                                                                  <?php endif; ?>
                                                                            <?php endif; ?>
                                                                            <?php if (can('h') && $user_admin) : ?>
                                                                                <!-- hapus surat -->
                                                                                <a href="#" data-href="<?= site_url("keluar/delete/{$data['id']}?redirect={$redirect}") ?>" class="btn bg-maroon btn-flat btn-sm" title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
                                                                            <?php endif; ?>

                                                                        <?php endif; ?>

                                                                    </td>
                                                                    <td><?= $data['kode_surat'] ?? '-' ?></td>
                                                                    <td><?= $data['no_surat'] ?? '-' ?></td>
                                                                    <td><?= $data['format'] ?></td>
                                                                    <td>
                                                                        <?php if ($data['nama']) : ?>
                                                                            <?= $data['nama']; ?>
                                                                        <?php elseif ($data['nama_non_warga']) : ?>
                                                                            <strong>Non-warga: </strong><?= $data['nama_non_warga']; ?><br>
                                                                            <strong>NIK: </strong><?= $data['nik_non_warga']; ?>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td><?= $data['keterangan'] ?? '-' ?></td>
                                                                    <td><?= $data['pamong_nama'] ?></td>
                                                                    <td class="padat"><?= tgl_indo2($data['tanggal']) ?></td>
                                                                    <td><?= $data['nama_user'] ?></td>
                                                                    <td>
                                                                        <?php if ($data['status'] == 1) : ?>
                                                                            <?php if ($data['verifikasi'] == 1) : ?>
                                                                                <?php if ($data['status_periksa'] == 1) : ?>
                                                                                    <?php if ($data['kecamatan'] == 2) : ?>
                                                                                        <span class="label label-success">Siap Dikirim ke Kecamatan</span>
                                                                                    <?php elseif ($data['kecamatan'] == 3) : ?>
                                                                                        <span class="label label-success">Telah Dikirim ke Kecamatan</span>
                                                                                    <?php else: ?>
                                                                                        <span class="label label-success">Siap Cetak</span>
                                                                                    <?php endif; ?>
                                                                                <?php else : ?>
                                                                                    <span class="label label-warning">Menunggu <?= $data['log_verifikasi'] ?></span>
                                                                                <?php endif ?>

                                                                            <?php endif ?>
                                                                        <?php else : ?>
                                                                            <span class="label label-danger">Konsep</span>
                                                                        <?php endif ?>

                                                                    </td>
                                                                    <td class="<?= jecho($this->tab_ini, 12, 'show-table') ?>" style="display: none;"><?= $data['alasan'] ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="dataTables_length">
                                                <form id="paging" action="<?= site_url('keluar') ?>" method="post" class="form-horizontal">
                                                    <label>
                                                        Tampilkan
                                                        <select name="per_page" class="form-control input-sm" onchange="$('#paging').submit()">
                                                            <option value="20" <?php selected($per_page, 20); ?>>20</option>
                                                            <option value="50" <?php selected($per_page, 50); ?>>50</option>
                                                            <option value="100" <?php selected($per_page, 100); ?>>100</option>
                                                        </select>
                                                        Dari
                                                        <strong><?= $paging->num_rows ?></strong>
                                                        Total Data
                                                    </label>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="dataTables_paginate paging_simple_numbers">
                                                <ul class="pagination">
                                                    <?php if ($paging->start_link) : ?>
                                                        <li><a href="<?= site_url("keluar/index/{$paging->start_link}/{$o}") ?>" aria-label="First"><span aria-hidden="true">Awal</span></a></li>
                                                    <?php endif; ?>
                                                    <?php if ($paging->prev) : ?>
                                                        <li><a href="<?= site_url("keluar/index/{$paging->prev}/{$o}") ?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
                                                    <?php endif; ?>
                                                    <?php for ($i = $paging->start_link; $i <= $paging->end_link; $i++) : ?>
                                                        <li <?= jecho($p, $i, "class='active'") ?>><a href="<?= site_url("keluar/index/{$i}/{$o}") ?>"><?= $i ?></a></li>
                                                    <?php endfor; ?>
                                                    <?php if ($paging->next) : ?>
                                                        <li><a href="<?= site_url("keluar/index/{$paging->next}/{$o}") ?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
                                                    <?php endif; ?>
                                                    <?php if ($paging->end_link) : ?>
                                                        <li><a href="<?= site_url("keluar/index/{$paging->end_link}/{$o}") ?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a></li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $this->load->view('global/confirm_delete'); ?>

<script src="<?= asset('js/sweetalert2/sweetalert2.all.min.js') ?>"></script>
<link rel="stylesheet" href="<?= asset('js/sweetalert2/sweetalert2.min.css') ?>">

<script>
    $(function() {

        var next = '<?= $next ?>';
        var pesan = `Apakah setuju surat ini di teruskan ke ${next}?`;
        var tte = "<?= setting('tte') ?>"
        var keyword = <?= $keyword ?>;
        $("#cari").autocomplete({
            source: keyword,
            maxShowItems: 10,
        });

        //swal alasan tolak
        $('button.verifikasi').click(function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var next = `<?= $next ?? '' ?>`;
            var tte = `<?= setting('tte') ?? '' ?>`;
            var pesan = `Apakah setuju surat ini di teruskan ke ${next}?`
            if (next == '' && tte == '0') {
                pesan = `Apakah setuju surat ini di teruskan ke Arsip?`
            } else if (next == '' && tte == '1') {
                pesan = 'Apakah setuju surat ini untuk ditandatangani secara elektronik?'
            }
            var ulr_ajax = {
                'confirm': `<?= site_url("{$this->controller}/verifikasi") ?>`,
                'denied': `<?= site_url("{$this->controller}/tolak") ?>`
            }

            var redirect = {
                'confirm': `<?= site_url("{$this->controller}/masuk") ?>`,
                'denied': `<?= site_url("{$this->controller}/masuk") ?>`
            }
            var data = {
                id: id
            };
            swal2_question(ulr_ajax, redirect, pesan, data, <?= ! $operator ?>);
        });

        $('button.kembalikan').click(function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var ulr_ajax = `<?= site_url("{$this->controller}/kembalikan") ?>`;
            var redirect = `<?= site_url("{$this->controller}/ditolak") ?>`;
            var pesan = `Kembalikan surat ke pemohon untuk diperbaiki?`;
            ditolak(id, ulr_ajax, redirect, pesan);
        });

        $('button.passphrase').click(function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            Swal.fire({
                customClass: {
                    popup: 'swal-lg',
                    input: 'swal-input-250'
                },
                title: 'TTE',
                html: `
                    <?php if (empty($this->setting->tte_api) || $this->setting->tte_api == base_url()) : ?>
                        <div class="alert alert-warning alert-dismissible">
                            <h4><i class="icon fa fa-warning"></i> Info Penting!</h4>
                            Modul TTE ini hanya sebuah simulasi untuk persiapan penerapan TTE di <?= config_item('nama_aplikasi') ?> dan Hanya berlaku untuk Surat yang Menggunakan TinyMCE
                        </div>
                    <?php endif; ?>
                    <object data="<?= site_url("{$this->controller}/unduh/tinymce"); ?>/${id}/true" style="width: 100%;min-height: 400px;" type="application/pdf"></object>
                    <input type="password" id="passphrase" autocomplete="off" class="swal2-input" placeholder="Masukkan Passphrase">
                `,
                showCancelButton: true,
                confirmButtonText: 'Kirim',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    const passphrase = Swal.getPopup().querySelector('#passphrase').value

                    if (!passphrase) {
                        Swal.showValidationMessage(`Mohon masukkan passphrase`)
                    }

                    const formData = new FormData();
                    formData.append('sidcsrf', getCsrfToken());
                    formData.append('id', id);
                    formData.append('passphrase', passphrase);

                    return fetch('<?= site_url('api/tte/sign_visible') ?>', {
                        method: 'post',
                        body: formData,
                    }).then(response => {
                        if (response.ok) {
                            return response.json();
                        }

                        if (!response.ok) {
                            throw new Error(response.statusText)
                        }
                        // return response.json()
                    }).catch(error => {
                        Swal.showValidationMessage(
                            `Request failed: ${error}`
                        )

                    })
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    let response = result.value
                    if (response.status == false) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Request failed',
                            text: response.pesan,
                        })
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Dokumen berhasil tertanda tangani secara elektronik',
                            showConfirmButton: true,
                        }).then((result) => {
                            window.location.replace("<?= site_url("{$this->controller}/masuk") ?>");
                        })
                    }
                }

            })
        });

        $('button#perbaiki').click(function(e) {
            swal.fire({
                title: 'Perbaiki Arsip Surat',
                text: 'Surat yang ada sekarang, akan diverifikasi semua. Ingin Melanjutkan?',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                denyButtonText: `Batalkan`,
                icon: 'warning',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?= site_url("{$this->controller}/perbaiki") ?>";
                }
            })
        });

        $('#kirim-kecamatan').click(function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            Swal.fire({
                title: 'Apakah anda yakin ingin mengirim surat ini ke kecamatan?',
                showCancelButton: true,
                confirmButtonText: 'Kirim',
                showLoaderOnConfirm: true,
                preConfirm: () => {

                    const formData = new FormData();
                    formData.append('sidcsrf', getCsrfToken());
                    formData.append('id', id);

                    return fetch('<?= site_url('api/surat/kirim') ?>', {
                        method: 'post',
                        body: formData,
                    }).then(response => {
                        if (response.ok) {
                            return response.json();
                        }

                        if (!response.ok) {
                            throw new Error(response.statusText)
                        }
                    }).catch(error => {
                        Swal.showValidationMessage(
                            `Request failed: ${error}`
                        )

                    })
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    let response = result.value
                    if (response.status == false) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Request failed',
                            text: response.pesan,
                        })
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Dokumen berhasil dikirim ke kecamatan',
                            showConfirmButton: true,
                        }).then((result) => {
                            window.location.replace("<?= site_url("{$this->controller}") ?>");
                        })
                    }
                }

            })
        });
    });
</script>