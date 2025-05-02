{!! form_open($form_action, 'id="validasi"') !!}
<div class="modal-body">
    <table class="table table-hover">
        <tr>
            <th width="20%">NIK</td>
            <td width="1%"> : </td>
            <td>{{ $penduduk['nik'] }}</td>
        </tr>
        <tr>
            <th>Nama Warga</td>
            <td> : </td>
            <td>{{ $penduduk['nama'] }}</td>
        </tr>
    </table>
    <div class="box box-danger">
        <div class="box-body">
            <div class="form-group">
                <label for="exampleInputEmail1">Scan KTP</label>
                <img class="img-responsive" src="{{ base_url("desa/upload/pendaftaran/{$penduduk['scan_ktp']}") }}" alt="Scan KTP" />
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Scan KK</label>
                <img class="img-responsive" src="{{ base_url("desa/upload/pendaftaran/{$penduduk['scan_kk']}") }}" alt="Scan KK" />
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Foto Selfie</label>
                <img class="img-responsive" src="{{ base_url("desa/upload/pendaftaran/{$penduduk['foto_selfie']}") }}" alt="Foto Selfie" />
            </div>
        </div>
    </div>
    <div class="form-group">
        @if ($tgl_verifikasi_telegram || $tgl_verifikasi_email)
            <label style="margin-top: 10px; margin-bottom: 0px;">Kirim Pemberitahuan Verifikasi Melalui : </label>
        @endif
        @if ($tgl_verifikasi_email)
            <div class="radio">
                <label style="font-size: 13.7px;">
                    <input type="radio" value="kirim_email" id="kirim_email" name="pilihan_kirim" checked> Email
                </label>
            </div>
        @endif

        @if ($tgl_verifikasi_telegram)
            <div class="radio">
                <label style="font-size: 13.7px;">
                    <input type="radio" value="kirim_telegram" id="kirim_telegram" name="pilihan_kirim" checked> Telegram
                </label>
            </div>
        @endif
    </div>
</div>
<div class="modal-footer">
    <button type="reset" class="btn btn-social btn-flat btn-danger btn-sm pull-left"><i class='fa fa-times'></i> Batal</button>

    @if ($tgl_verifikasi_telegram || $tgl_verifikasi_email)
        <button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Verifikasi</button>
    @endif
</div>
</form>
@include('admin.layouts.components.form_modal_validasi')
