@extends('layanan_mandiri.auth.index')

@section('content')
    <form id="validasi" autocomplete="off" action="{{ $form_action }}" method="post" class="login-form">
        <div class="form-group form-login">
            <input type="text" autocomplete="off" class="form-control required {!! jecho($cek_anjungan['keyboard'] == 1, true, 'kbvnumber') !!}" name="nik" placeholder="NIK">
        </div>
        <div class="form-group form-login">
            <input type="password" autocomplete="off" class="form-control required {!! jecho($cek_anjungan['keyboard'] == 1, true, 'kbvnumber') !!}" name="password" placeholder="PIN" id="pin">
        </div>
        <div class="form-group">
            <center><input type="checkbox" id="checkbox" style="display: initial;"> Tampilkan PIN</center>
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
        @if ($cek_anjungan['tipe'] == 1)
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
        });
    </script>
@endpush
