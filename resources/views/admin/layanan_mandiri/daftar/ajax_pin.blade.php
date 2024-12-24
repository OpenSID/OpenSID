{!! form_open($form_action, 'id="validasi"') !!}
<div class="modal-body">
    @if (!$id_pend)
        <div class="form-group">
            <label for="id_pend">NIK / Nama Penduduk {{ $id_pend }}</label>
            <select class="form-control input-sm select2 required" id="id_pend" name="id_pend">
                <option option value="">-- Silakan Cari NIK - Nama Penduduk --</option>
                @foreach ($penduduk as $data)
                    <option value="{{ $data['id'] }}" @selected($data['id'] == $id_pend)>{{ $data['nik'] }} - {{ $data['nama'] }}</option>
                @endforeach
            </select>
        </div>
    @endif
    <div class="form-group">
        <label class="control-label" for="pin">PIN</label>
        <input
            id="pin"
            name="pin"
            class="form-control input-sm digits"
            minlength="6"
            maxlength="6"
            type="text"
            placeholder="PIN Warga"
            <?= $id_pend ? 'disabled' : '' ?>
            style="margin-bottom: 15px;"
        />
        @if (!$id_pend)
            <p class="help-block"><code>1. Jika PIN tidak di isi maka sistem akan menghasilkan PIN secara acak.</code></p>
            <p class="help-block"><code>2. 6 (enam) digit Angka.</code></p>
        @endif
        @if ($id_pend)
            <p class="help-block"><code>1. Sistem akan menghasilkan PIN secara acak dengan cara menekan tombol 'Reset PIN'.</code></p>
            <p class="help-block"><code>2. PIN berisi 6 (enam) digit Angka.</code></p>
            <p class="help-block"><code>3. PIN akan dikirimkan ke akun Telegram atau Email yang sudah diverifikasi.</code></p>
            <p class="help-block"><code>4. Cara Verifikasi Telegram atau Email di menu Verifikasi pada Layanan Mandiri.</code></p>
        @endif
    </div>

    <div class="form-group">
        @if ($tgl_verifikasi_telegram || $tgl_verifikasi_email)
            <label style="margin-top: 10px; margin-bottom: 0px;">Kirim PIN Baru Melalui : </label>
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
    <button type="reset" class="btn btn-social btn-danger btn-sm pull-left"><i class='fa fa-times'></i> Batal</button>
    <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-check'></i> {{ !$id_pend ? 'Simpan' : 'Reset PIN' }} </button>
</div>
</form>
@include('admin.layouts.components.form_modal_validasi')
