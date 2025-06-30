@extends('layanan_mandiri.auth.index')

@section('content')
    <div class="alert alert-info">
        <h3>Terima kasih telah mendaftar! Sebelum memulai, bisakah Anda memverifikasi Email dan Telegram Anda dengan mengklik tautan yang baru saja kami kirimkan kepada Anda? Jika Anda tidak menerima notifikasi tersebut, kami akan dengan senang hati mengirimkan yang lain.</h3>
    </div>
    <form id="validasi" action="{{ ci_route('layanan-mandiri.daftar.verifikasi.telegram.kirim') }}" method="post" class="login-form" enctype="multipart/form-data">
        <div class="form-group">
            <div class="form-group">
                <button @disabled(auth('penduduk')->user()->hasVerifiedTelegram()) type="submit" class="btn btn-block bg-green"><b>Kirim Ulang Verifikasi Telegram</b></button>
            </div>
        </div>
    </form>
    <form id="validasi" action="{{ ci_route('layanan-mandiri.daftar.verifikasi.email.kirim') }}" method="post" class="login-form" enctype="multipart/form-data">
        <div class="form-group">
            <div class="form-group">
                <button @disabled(auth('penduduk')->user()->hasVerifiedEmail()) type="submit" class="btn btn-block bg-green"><b>Kirim Ulang Verifikasi Email</b></button>
            </div>
        </div>
    </form>
    <form action="">
        <div class="form-group">
            <a href="{{ ci_route('layanan-mandiri/keluar') }}">
                <button type="button" class="btn btn-block bg-green"><b>Logout</b></button>
            </a>
        </div>
    </form>
@endsection
