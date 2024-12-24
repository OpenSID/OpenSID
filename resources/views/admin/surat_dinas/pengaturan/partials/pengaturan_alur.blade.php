<div class="tab-pane" id="alur">
    <div class="box-body">
        <div class="alert alert-warning alert-dismissible">
            <h4><i class="icon fa fa-warning"></i> Info Penting!</h4>
            Menonaktifkan verifikasi akan mempengaruhi log surat maka pastikan bahwa benar surat ingin
            diarsipkan semua.
        </div>
        <div class="form-group">
            <label>Verifikasi {{ setting('sebutan_sekretaris_desa') }}</label>
            <div class="input-group col-xs-12 col-sm-8">
                <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons" style="padding: 0px;">
                    <label class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label @active(setting('verifikasi_sekdes') == '1') @disabled(!$sekdes)">
                        <input
                            type="radio"
                            name="verifikasi_sekdes"
                            class="form-check-input"
                            value="1"
                            autocomplete="off"
                            @checked(setting('verifikasi_sekdes') == '1' && $sekdes)
                            @disabled(!$sekdes)
                        >Ya</label>
                    <label class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label @active(setting('verifikasi_sekdes') == '0') @disabled(!$sekdes)">
                        <input
                            type="radio"
                            name="verifikasi_sekdes"
                            class="form-check-input"
                            value="0"
                            autocomplete="off"
                            @checked(setting('verifikasi_sekdes') == '0' && $sekdes)
                            @disabled(!$sekdes)
                        >Tidak
                    </label>
                </div>
            </div>
            <span class="help-block text-red @display(!$sekdes)">User
                {{ setting('sebutan_sekretaris_desa') }} belum tersedia</span>
        </div>
    </div>
    <div class="box-body">
        <div class="form-group">
            <label>Verifikasi {{ setting('sebutan_kepala_desa') }}</label>
            <div class="input-group col-xs-12 col-sm-8">
                <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons" style="padding: 0px;">
                    <label class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label @active(setting('verifikasi_kades') == '1') @disabled(setting('tte') == '1' || !$kades)">
                        <input
                            type="radio"
                            name="verifikasi_kades"
                            class="form-check-input"
                            value="1"
                            autocomplete="off"
                            @checked(setting('verifikasi_kades') == '1')
                            @disabled(setting('tte') == '1' || !$kades)
                        >Ya</label>
                    <label class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label @active(setting('verifikasi_kades') == '0') @disabled(setting('tte') == '1' || !$kades)">
                        <input
                            type="radio"
                            name="verifikasi_kades"
                            class="form-check-input"
                            value="0"
                            autocomplete="off"
                            @checked(setting('verifikasi_kades') == '0')
                            @disabled(setting('tte') == '1' || !$kades)
                        >Tidak
                    </label>
                </div>
            </div>
            <span class="help-block text-red @display(!$kades)">User
                {{ setting('sebutan_kepala_desa') }} belum tersedia</span>
        </div>
    </div>
</div>
