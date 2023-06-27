<div class="modal-body">
    <div class="row">
        <div class="col-md-3">
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <img class="profile-user-img img-responsive img-circle" src="<?= AmbilFoto(auth()->foto) ?>"
                        alt="Foto">
                </div>
            </div>
            <?php if(auth()->email_verified_at === null): ?>
            <div class="row">
                <div class="col-sm-12">
                    <?= form_open(route('user_setting.kirim_verifikasi')) ?>
                    <button type="submit" class="btn btn-sm btn-warning btn-block"><i class="fa fa-share-square"></i>
                        Verifikasi Email</button>
                    </form>
                </div>
            </div>
            <br />
            <?php endif ?>

            <?php if(auth()->telegram_verified_at === null && setting('telegram_token') != null): ?>
            <div class="row">
                <div class="col-sm-12">
                    <button type="button" id="verif_telegram" class="btn btn-sm btn-warning btn-block"><i
                            class="fa fa-share-square"></i>
                        Verifikasi Telegram</button>
                </div>
            </div>
            <br />
            <?php endif ?>
        </div>
        <div class="col-md-9 col-sm-12">
            <div class="box box-danger">
                <?= form_open_multipart(route('user_setting.update'), 'id="validate_user"') ?>
                <div class="box-body">
                    <div class="form-group">
                        <label for="tgl_peristiwa">Username</label>
                        <input class="form-control input-sm" type="text" value="<?= auth()->username ?>"
                            autocomplete="off" disabled />
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input class="form-control input-sm" type="text" value="<?= auth()->email ?>" readonly />
                    </div>
                    <div class="form-group">
                        <label for="nama_lengkap">Nama Lengkap</label>
                        <input class="form-control input-sm" type="text" name="nama" value="<?= auth()->nama ?>"
                            autocomplete="off" />
                    </div>
                    <div class="form-group">
                        <label for="pass_lama">Kata Sandi Lama</label>
                        <input class="form-control input-sm required" type="password" name="pass_lama"
                            autocomplete="off" />
                    </div>
                    <div class="form-group">
                        <label for="pass_baru">Kata Sandi Baru</label>
                        <input class="form-control input-sm required pwdLengthNist" type="password" id="pass_baru"
                            name="pass_baru" autocomplete="off" />
                    </div>
                    <div class="form-group">
                        <label for="pass_baru1">Kata Sandi Baru (Ulangi)</label>
                        <input class="form-control input-sm required pwdLengthNist" type="password" id="pass_baru1"
                            name="pass_baru1" autocomplete="off" />
                    </div>
                    <div class="form-group">
                        <label for="foto">Ganti Foto</label>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" id="file_path_user" name="foto">
                            <input type="file" class="hidden" id="file_user" name="foto">
                            <input type="hidden" name="old_foto" value="<?= auth()->foto ?>">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-info btn-flat" id="file_browser_user"><i
                                        class="fa fa-search"></i> Browse</button>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="notif_telegram" class="control-label">Notifikasi Telegram</label>
                        <div class="btn-group col-xs-12 col-sm-8 input-group" data-toggle="buttons">
                            <label
                                class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label <?= jecho(auth()->notif_telegram, 1, 'active') ?> <?= jecho(setting('telegram_token') == null || auth()->telegram_verified_at == null, true, 'disabled') ?>">
                                <input type="radio" name="notif_telegram" class="form-check-input" value="1"
                                    autocomplete="off" <?= selected(auth()->notif_telegram, 1) ?>
                                    <?= jecho(setting('telegram_token') == null || auth()->telegram_verified_at == null, true, 'disabled') ?> />
                                Ya
                            </label>
                            <label
                                class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label <?= jecho(auth()->notif_telegram, 0, 'active') ?>  <?= jecho(setting('telegram_token') == null || auth()->telegram_verified_at == null, true, 'disabled') ?>">
                                <input type="radio" name="notif_telegram" class="form-check-input" value="0"
                                    autocomplete="off" <?= selected(auth()->notif_telegram, 0) ?>
                                    <?= jecho(setting('telegram_token') == null || auth()->telegram_verified_at == null, true, 'disabled') ?> />
                                Tidak
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="id_telegram">User ID Telegram</label>
                        <input class="form-control input-sm" type="text" id="id_telegram" name="id_telegram"
                            value="<?= auth()->id_telegram ?>"
                            <?= jecho(setting('telegram_token') == null, true, 'disabled') ?> />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-social btn-danger btn-sm pull" data-dismiss="modal"><i
                            class="fa fa-sign-out"></i> Tutup</button>
                    <button id="btnSubmit" type="submit" class="btn btn-social btn-info btn-sm"><i
                            class="fa fa-check"></i> Simpan</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
