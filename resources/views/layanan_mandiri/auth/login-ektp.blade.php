@extends('layanan_mandiri.auth.index')

@section('content')
    <form id="validasi" autocomplete="off" action="{{ $form_action }}" method="post" class="login-form">
        <div class="login-footer-top">
            @if ($cek_anjungan)
                Tempelkan e-KTP Pada Card Reader
            @endif
            <div class="thumbnail">
                <img src="{{ asset('images/camera-scan.gif') }}" alt="scanner" class="center" style="width:30%">
            </div>
        </div>
        <div class="form-group form-login" style="{{ jecho($cek_anjungan == 0 || ENVIRONMENT == 'development', false, 'width: 0; height: 0; overflow: hidden;') }}">
            <input
                name="tag_id_card"
                id="tag"
                autocomplete="off"
                placeholder="Tempelkan e-KTP Pada Card Reader"
                class="form-control required number"
                type="password"
                onkeypress="if (event.keyCode == 13){$('#'+'validasi').attr('action', '{{ $form_action }}');$('#'+'validasi').submit();}"
            >
        </div>
        @if (!$cek_anjungan)
            <div class="form-group form-login">
                <input type="password" class="form-control required number" name="password" placeholder="Masukan PIN" autocomplete="off">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-block bg-green"><b>MASUK</b></button>
            </div>
        @endif
        <div class="form-group">
            <a href="{{ site_url('layanan-mandiri/masuk') }}">
                <button type="button" class="btn btn-block bg-green"><b>MASUK DENGAN NIK</b></button>
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
                <a href="{{ route('anjungan.index') }}">
                    <button type="button" class="btn btn-block bg-green"><b>ANJUNGAN</b></button>
                </a>
            </div>
        @endif
    </form>
@endsection
