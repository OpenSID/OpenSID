<div class="tab-pane" id="2fa">
    <div class="alert alert-info">
        <h4><i class="icon fa fa-info"></i>Autentikasi Dua Faktor (2FA)</h4>
        <p>Autentikasi Dua Faktor (2FA) menggunakan One-Time Password (OTP) memberikan lapisan keamanan tambahan untuk akun pengguna. Ketika diaktifkan, pengguna akan diminta memasukkan kode OTP 6 digit yang dikirim melalui telegram atau email selain password.</p>
    </div>

    {!! form_open_multipart(ci_route('pengguna.update_keamanan'), 'id="validasi"') !!}
    <div class="box-body">
        <div class="form-group">
            <label for="aktif" class="col-sm-3 control-label">Status</label>
            <div class="btn-group col-xs-12 col-sm-8 " data-toggle="buttons">
                <label class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label @active($userData['two_factor_enabled'] == '1')">
                    <input type="radio" name="two_factor_enabled" class="form-check-input" value="1"
                        @checked($userData['two_factor_enabled'] == '1')> Aktif
                </label>
                <label class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label @active($userData['two_factor_enabled'] != '1')">
                    <input type="radio" name="two_factor_enabled" class="form-check-input" value="0"
                        @checked($userData['two_factor_enabled'] != '1')> Tidak Aktif
                </label>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i>
            Batal</button>
        <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>
            Simpan</button>
    </div>
    {!! form_close() !!}
</div>
