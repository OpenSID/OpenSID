@extends('admin.auth.index')

@section('content')
    <form id="validasi" class="login-form" action="<?= site_url('siteman/verifikasi_sandi') ?>" method="post">
        <div class="form-group">
            <input name="email" type="text" placeholder="Email Pengguna" value="<?= $email ?>" class="form-control required" readonly>
            <input type="hidden" name="email" value="<?= $email ?>">
            <input type="hidden" name="token" value="<?= $token ?>">
        </div>
        <div class="form-group">
            <input name="password" type="password" placeholder="Password" autocomplete="off" class="form-control required pwdLengthNist">
        </div>
        <div class="form-group">
            <input name="password_confirmation" type="password" placeholder="Konfirmasi Password" autocomplete="off" class="form-control required pwdLengthNist">
        </div>
        <div class="form-group">
            <button type="submit" class="btn">Reset Sandi</button>
        </div>
    </form>
@endsection
