@extends('admin.auth.index')

@section('content')
        <div class="callout callout-info" style="margin-bottom: 15px;">
            <p style="margin: 0; font-size: 20px;">
                Login dengan OTP
            </p>
            <p class="text-muted">Masukkan email atau username Anda</p>
        </div>

        <form id="validasi" class="login-form" action="{{ $form_action }}" method="POST">
            <div class="form-group">
                <input
                    id="identifier"
                    name="identifier"
                    type="text"
                    autocomplete="off"
                    placeholder="Email atau Username"
                    class="form-username form-control required"
                    maxlength="100"
                    required autofocus
                >
            </div>

            <div class="form-group">
                <button type="submit" class="btn">Kirim Kode OTP</button>
            </div>

        </form>

        <a href="{{ site_url('siteman') }}" class="btn">
            <i class="fa fa-mobile"></i> Kembali ke Login Password
        </a>
@endsection
