@extends('layanan_mandiri.auth.index')

@section('content')
    <form id="validasi" autocomplete="off" action="{{ $form_action }}" method="post" class="login-form">
        <div class="form-group form-login">
            <input type="text" autocomplete="off" class="form-control angka required {!! jecho($cek_anjungan['keyboard'] == 1, true, 'kbvnumber') !!}" name="nik" maxlength="16" placeholder="NIK">
        </div>
        {{-- Hidden input for UUID from local storage --}}
        <input type="hidden" name="anjungan_uuid" id="anjungan_uuid">
        <div class="form-group form-login">
            <input
                type="password"
                autocomplete="off"
                class="form-control angka required {!! jecho($cek_anjungan['keyboard'] == 1, true, 'kbvnumber') !!}"
                name="password"
                placeholder="PIN"
                id="pin"
                maxlength="6"
            >
        </div>
        <div class="form-group">
            <center><input type="checkbox" id="checkbox" style="display: initial;"> <label for="checkbox">Tampilkan PIN</label></center>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-block bg-green"><b>MASUK</b></button>
        </div>
        <div class="form-group">
            <a href="{{ site_url('layanan-mandiri/masuk-ektp') }}">
                <button type="button" class="btn btn-block bg-green"><b>MASUK DENGAN E-KTP</b></button>
            </a>
        </div>
        @if (setting('tampilkan_pendaftaran'))
            <div class="form-group">
                <a href="{{ site_url('layanan-mandiri/daftar') }}">
                    <button type="button" class="btn btn-block bg-green"><b>DAFTAR</b></button>
                </a>
            </div>
        @endif
        <div class="form-group">
            <a href="{{ site_url('layanan-mandiri/lupa-pin') }}">
                <button type="button" class="btn btn-block bg-green"><b>LUPA PIN</b></button>
            </a>
        </div>
        @if (in_array(\Modules\Anjungan\Models\Anjungan::ANJUNGAN, $cek_anjungan['tipe'] ?? []))
            <div class="form-group">
                <a href="<?= route('anjungan.index') ?>">
                    <button type="button" class="btn btn-block bg-green"><b>ANJUNGAN</b></button>
                </a>
            </div>
        @endif
    </form>
@endsection

@push('script')
    <script type="text/javascript">
        $('document').ready(function() {
            var pass = $("#pin");
            $('#checkbox').click(function() {
                if (pass.attr('type') === "password") {
                    pass.attr('type', 'text');
                } else {
                    pass.attr('type', 'password')
                }
            });
            
            // Get UUID from local storage and set it to the hidden input
            const anjungan_uuid = localStorage.getItem('anjungan_uuid');
            if (anjungan_uuid) {
                $('#anjungan_uuid').val(anjungan_uuid);
            }
        });
    </script>
@endpush
