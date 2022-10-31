<div class="content-wrapper">
	<section class="content-header">
		<h1>SINKRONISASI</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Sinkronisasi</li>
		</ol>
	</section>
    <section class="content">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#opendk" data-toggle="tab"><b>OPENDK</b></a></li>
                <li><a href="#tab_buat_key" data-toggle="tab"><b>BUAT KEY</b></a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="opendk">
                    <div class="table-responsive">
                        <table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
                            <thead class="bg-gray disabled color-palette">
                                <tr>
                                    <th>No.</th>
                                    <th>Kirim Data OpenSID Ke OpenDK</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($kirim_data): ?>
                                    <?php foreach ($kirim_data as $key => $data): ?>
                                        <tr>
                                            <td class="padat"><?= ($key + 1); ?></td>
                                            <td><?= $data; ?></td>
                                            <td class="padat">
                                                <?php $slug = url_title($data, 'dash', true); ?>
                                                <?php if (in_array($slug, ['penduduk'])): ?>
                                                    <a href="<?= site_url('sinkronisasi/unduh/') . $slug ?>" title="Unduh Data" class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-download"></i> Unduh Data</a>
                                                    <?php if ($this->setting->api_opendk_key): ?>
                                                        <a href="#" data-href="<?= site_url('sinkronisasi/kirim/') . $slug ?>" class="btn btn-social btn-flat btn-primary btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" id="kirim_data" title="Kirim Data" data-toggle="modal" data-target="#confirm-status" data-body="Apakah yakin mengirim data <?= $data; ?> ke OpenDK?" data-backdrop="false" data-keyboard="false"><i class="fa fa-random"></i> Kirim Data</a>
                                                    <?php else: ?>
                                                        <a href="#" title="API Key Belum Ditentukan" class="btn btn-social btn-flat btn-primary btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" disabled><i class="fa fa-random"></i> Kirim Data</a>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <a href="<?= site_url('sinkronisasi/kirim/') . $slug ?>" class="btn btn-social btn-flat btn-warning btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Buka Modul"><i class="fa fa-link"></i> Buka Modul</a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td class="text-center" colspan="3">Data Tidak Tersedia</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane" id="tab_buat_key">
                    <form id="validasi" class="form-horizontal" action="<?=site_url('setting/update'); ?>" method="POST">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td style="padding-top:20px; padding-bottom:10px;">
                                        <div class="form-group">
                                            <label class="col-sm-12 col-md-3" for="nama">Api Opendk Server</label>
                                            <div class="col-sm-12 col-md-4">
                                                <input id="api_opendk_server" name="api_opendk_server" class="form-control input-sm required" type="text" onkeyup="cek_input()" value="<?= $this->setting->api_opendk_server; ?>"/>
                                            </div>
                                            <label class="col-sm-12 col-md-5 pull-left" for="nama">Alamat Server OpenDK <code>(contoh: https://demodk.opendesa.id)</code></label>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-12 col-md-3" for="nama">Api Opendk User</label>
                                            <div class="col-sm-12 col-md-4">
                                                <input id="api_opendk_user" name="api_opendk_user" class="form-control input-sm required" type="text" onkeyup="cek_input()" value="<?= $this->setting->api_opendk_user; ?>"/>
                                            </div>
                                            <label class="col-sm-12 col-md-5 pull-left" for="nama">Email Login Pengguna OpenDK</label>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-12 col-md-3" for="nama">Api Opendk Password</label>
                                            <div class="col-sm-12 col-md-4">
                                                <input id="api_opendk_password" name="api_opendk_password" class="form-control input-sm required" type="password" onkeyup="cek_input()" value="<?= $this->setting->api_opendk_password; ?>"/>
                                            </div>
                                            <label class="col-sm-12 col-md-5 pull-left" for="nama">Password Login Pengguna OpenDK</label>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-12 col-md-3" for="nama">Api Opendk Key</label>
                                            <div class="col-sm-12 col-md-4">
                                                <textarea rows="5" id="api_opendk_key" name="api_opendk_key" class="form-control input-sm" type="text" placeholder="Silahkan Buat API Key OpenDK"><?= $this->setting->api_opendk_key; ?></textarea>
                                            </div>
                                            <label class="col-sm-12 col-md-5 pull-left" for="nama">OpenDK API Key untuk Sinkronisasi Data</label>
                                        </div>
                                        <div class="form-group">
                                            <label for="token" class="col-sm-3 control-label"></label>
                                            <div class="col-sm-4">
                                                <a class="btn btn-social btn-flat btn-success btn-sm" id="btn_buat_key"><i class='fa fa-key'></i>Buat Key</a>
                                                <button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan Pengaturan</button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id='loading' tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header btn-warning">
                    <h4 class="modal-title">Proses Sinkronisasi</h4>
                </div>
                <div class="modal-body">
                    Harap tunggu sampai proses sinkronisasi selesai. Proses ini bisa memakan waktu beberapa menit tergantung data yang dikirmkan.
                    <div class='text-center'>
                        <img src="<?= base_url('assets/images/background/loading.gif')?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if ($notif = $this->session->flashdata('notif')): ?>
        <div class="modal fade" id="response" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Response</h4>
                    </div>
                    <div class="modal-body btn-<?= $notif['status']; ?>">
                        <?= $notif['pesan']; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $this->load->view('global/konfirmasi'); ?>
<script src="<?= base_url(); ?>assets/js/axios.min.js"></script>
<script>
    $(document).ready(function() {
		cek_input();
        $('#response').modal({backdrop: 'static', keyboard: false}).show();
	});

    $('#ok-delete').on('click', function() {
        $('#confirm-status').hide();
		$('#loading').modal({backdrop: 'static', keyboard: false}).show();
	});

    $('#btn_simpan').on('click', function() {
		get_token();
		return false;
	});

    function cek_input() {
        if ($('#api_opendk_server').val() == '' || $('#api_opendk_user').val() == ''  || $('#api_opendk_password').val() == '') {
            $('#api_opendk_key').prop( "disabled", true);
            $('#btn_buat_key').prop( "disabled", true);
        } else {
            $('#api_opendk_key').prop( "disabled", false);
            $('#btn_buat_key').prop( "disabled", false);
        }
    }

    async function get_token() {
        let res = await axios({
            'method': 'post',
            'header': {
                'Accept': 'application/json'
            },
            'url': $('#api_opendk_server').val() + '/api/v1/auth/login',
            'data': {
                'email': $('#api_opendk_user').val(),
                'password': $('#api_opendk_password').val()
            }
        });

        if(res.status == 200) {
            $('#api_opendk_key').val(res.data.access_token);
        }

        return null;
	}

    $('#btn_buat_key').on('click', function() {
        $('#api_opendk_key').val('');
		get_token();
	});
</script>