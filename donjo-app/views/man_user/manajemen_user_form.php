<div class="content-wrapper">
    <section class="content-header">
        <h1>Form Manajemen Pengguna </h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="<?= site_url('man_user') ?>"> Daftar Pengguna</a></li>
            <li class="active">Form Manajemen Pengguna</li>
        </ol>
    </section>
    <section class="content" id="maincontent">
        <div class="row">
            <form id="validasi" action="<?= $form_action ?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
                <div class="col-md-3">
                    <div class="box box-primary">
                        <div class="box-body box-profile">
                            <img class="penduduk" src="<?= AmbilFoto($user['foto']); ?>" alt="Foto Pengguna">
                            <br />
                            <p class="text-center text-bold">Foto Pengguna</p>
                            <p class="text-muted text-center text-red">(Kosongkan, jika foto tidak berubah)</p>
                            <br />
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="file_path" name="foto">
                                <input type="file" class="hidden" id="file" name="foto" accept=".gif,.jpg,.jpeg,.png">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i> Browse</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <a href="<?= site_url('man_user') ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Manajemen Pengguna</a>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="group">Group</label>
                                <div class="col-sm-8">
                                    <select class="form-control input-sm required" id="id_grup" name="id_grup">
                                        <?php if ($user['id'] === super_admin()) : ?>
                                            <option <?php selected($user['id_grup'], '1'); ?> value="1">Administrator</option>
                                        <?php else : ?>
                                            <?php foreach ($user_group as $item) : ?>
                                                <option <?php selected($user['id_grup'], $item['id']); ?> value="<?= $item['id'] ?>"><?= $item['nama'] ?></option>
                                            <?php endforeach ?>
                                        <?php endif ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="nama">Data Staf </label>
                                <div class="col-sm-8">
                                    <select class="form-control select2 input-sm" id="pamong_id" name="pamong_id">
                                        <option value>-- Silakan Masukan Nama Staf --</option>
                                        <?php foreach ($pamong as $item) : ?>
                                            <option value="<?= $item->pamong_id ?>" data-nama="<?= $item['pamong_nama'] ?>" <?= selected($user['pamong_id'], $item->pamong_id); ?>><?= $item->jabatan->nama . ' - ' . $item->pamong_nama ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="username">Username</label>
                                <div class="col-sm-8">
                                    <input id="username" name="username" class="form-control input-sm required username" type="text" placeholder="Username" value="<?= $user['username'] ?>" autocomplete="off"></input>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="password">Kata Sandi</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input id="password" name="password" class="form-control input-sm pwdLengthNist_atau_kosong <?= ($user) ? '' : 'required' ?>" type="password" placeholder="<?= ($user) ? 'Ubah Password' : 'Password' ?>" autocomplete="off"></input>
                                        <span class="input-group-addon input-sm reveal"><i class="fa fa-eye-slash"></i></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="aktif" class="col-sm-3 control-label">Status</label>
                                <div class="btn-group col-xs-12 col-sm-8 " data-toggle="buttons">
                                    <label class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label <?= compared_return($user['active'], '1') ?>">
                                        <input type="radio" name="aktif" class="form-check-input" value="1" <?= selected($user['active'], 1) ?>> Aktif
                                    </label>
                                    <label class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label <?= compared_return($user['active'], '0') ?>">
                                        <input type="radio" name="aktif" class="form-check-input" value="0" <?= selected($user['notif_telegram'], 0) ?>> Tidak Aktif
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="nama">Nama</label>
                                <div class="col-sm-8">
                                    <input id="nama" name="nama" class="form-control input-sm required nama" minlength="3" maxlength="50" type="text" placeholder="Nama" value="<?= $user['nama'] ?>"></input>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="phone">Nomor HP</label>
                                <div class="col-sm-8">
                                    <input id="phone" name="phone" class="form-control input-sm bilangan" minlength="10" maxlength="15" type="text" placeholder="Nomor HP" value="<?= $user['phone'] ?>"></input>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="email">Email</label>
                                <div class="col-sm-8">
                                    <input id="email" name="email" class="form-control input-sm email" type="text" placeholder="Alamat E-mail" value="<?= $user['email'] ?>"></input>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="notif_telegram" class="col-sm-3 control-label">Notifikasi Telegram</label>
                                <div class="btn-group col-xs-12 col-sm-8 " data-toggle="buttons">
                                    <label class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label <?= compared_return($user['notif_telegram'], '1') ?> <?= jecho(setting('telegram_token'), null, 'disabled') ?>">
                                        <input type="radio" name="notif_telegram" class="form-check-input" value="1" autocomplete="off" <?= selected($user['notif_telegram'], 1) ?> <?= jecho(setting('telegram_token'), null, 'disabled') ?>> Aktif
                                    </label>
                                    <label class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label <?= compared_return($user['notif_telegram'], '0') ?> <?= jecho(setting('telegram_token'), null, 'disabled') ?>">
                                        <input type="radio" name="notif_telegram" class="form-check-input" value="0" autocomplete="off" <?= selected($user['notif_telegram'], 0) ?> <?= jecho(setting('telegram_token'), null, 'disabled') ?>> Matikan
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="catatan" class="col-sm-3 control-label">User ID Telegram</label>
                                <div class="col-sm-8">
                                    <input class="form-control input-sm bilangan" type="text" id="id_telegram" name="id_telegram" value="<?= $user['id_telegram'] ?>" maxlength="10" <?= jecho(setting('telegram_token'), null, 'disabled') ?> />
                                    <?php if (setting('telegram_token') == null) : ?>
                                        <span class="help-block" style="color: #f39c12;">Untuk mengaktifkan notifikasi telegram, harap masukkan token telegram di modul setting aplikasi </span>
                                    <?php endif ?>

                                </div>
                            </div>
                        </div>

                        <div class="box-footer">
                            <button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
                            <button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

<script type="text/javascript">
    $(function() {
        $('#pamong_id').on('select2:select', function(e) {
            var data = $('#pamong_id :selected').data('nama')
            $('#nama').val(data);
        });

        $('input[name="notif_telegram"]').change(function(e) {
            e.preventDefault();
            if ($(this).val() == 1) {
                $('input[name="id_telegram"]').addClass('required');
            } else {
                $('input[name="id_telegram"]').removeClass('required');
            }
        });

        $('.reveal').on('click', function() {
            var $pwd = $("#password");
            if ($pwd.attr('type') === 'password') {
                $pwd.attr('type', 'text');

                $(".reveal i").removeClass("fa-eye-slash");
                $(".reveal i").addClass("fa-eye");
            } else {
                $pwd.attr('type', 'password');

                $(".reveal i").addClass("fa-eye-slash");
                $(".reveal i").removeClass("fa-eye");
            }
        });

            $('input[value="<?= $user['active'] ?? 1 ?>"][name="aktif"]').parent().trigger('click')

    });
</script>