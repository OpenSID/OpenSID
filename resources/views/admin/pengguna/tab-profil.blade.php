<div class="tab-pane active" id="profil">
    {!! form_open_multipart($form_action, 'id="validasi"') !!}
    <div class="box-body">
        <div class="form-group">
            <label for="tgl_peristiwa">Username</label>
            <input class="form-control input-sm" type="text" value="{{ auth()->username }}" autocomplete="off"
                disabled />
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input class="form-control input-sm" type="text" value="{{ auth()->email }}" readonly />
        </div>
        <div class="form-group">
            <label for="nama_lengkap">Nama Lengkap</label>
            <input class="form-control input-sm required" type="text" name="nama" value="{{ auth()->nama }}"
                autocomplete="off" />
        </div>
        <div class="form-group">
            <label for="foto">Ganti Foto</label>
            <div class="input-group input-group-sm">
                <input type="text" class="form-control" id="file_path" name="foto">
                <input type="file" class="hidden" id="file" name="foto">
                <input type="hidden" name="old_foto" value="{{ auth()->foto }}">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i>
                        Browse</button>
                </span>
            </div>
        </div>
        <div class="form-group">
            <label for="notif_telegram" class="control-label">Notifikasi Telegram</label>
            <div class="btn-group col-xs-12 col-sm-8 input-group" data-toggle="buttons">
                <label
                    class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label {{ jecho(auth()->notif_telegram, 1, 'active') }} {{ jecho(setting('telegram_token') == null || auth()->telegram_verified_at == null, true, 'disabled') }}">
                    <input type="radio" name="notif_telegram" class="form-check-input" value="1"
                        autocomplete="off" {{ selected(auth()->notif_telegram, 1) }}
                        {{ jecho(setting('telegram_token') == null || auth()->telegram_verified_at == null, true, 'disabled') }} />
                    Ya
                </label>
                <label
                    class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label {{ jecho(auth()->notif_telegram, 0, 'active') }}  {{ jecho(setting('telegram_token') == null || auth()->telegram_verified_at == null, true, 'disabled') }}">
                    <input type="radio" name="notif_telegram" class="form-check-input" value="0"
                        autocomplete="off" {{ selected(auth()->notif_telegram, 0) }}
                        {{ jecho(setting('telegram_token') == null || auth()->telegram_verified_at == null, true, 'disabled') }} />
                    Tidak
                </label>
            </div>
        </div>
        <div class="form-group">
            <label for="id_telegram">User ID Telegram</label>
            <input class="form-control input-sm bilangan" type="text" id="id_telegram" name="id_telegram"
                value="{{ auth()->id_telegram }}" {{ jecho(setting('telegram_token') == null, true, 'disabled') }} />
        </div>
    </div>
    <div class="box-footer">
        <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i>
            Batal</button>
        <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>
            Simpan</button>
    </div>
    </form>
</div>
