@extends('layanan_mandiri.auth.index')

@section('content')
    <form id="validasi" class="login-form" action="<?= site_url('layanan-mandiri/reset-password') ?>" method="post">
        <div class="form-group">
            @if (isset($telegram))
                <input name="telegram" type="text" placeholder="Telegram Pengguna" value="<?= $telegram ?>" class="form-control required" readonly>
                <input type="hidden" name="telegram" value="<?= $telegram ?>">
            @else
                <input name="email" type="text" placeholder="Email Pengguna" value="<?= $email ?>" class="form-control required" readonly>
                <input type="hidden" name="email" value="<?= $email ?>">
            @endif
            <input type="hidden" name="token" value="<?= $token ?>">
        </div>
        <div class="form-group">
            <input
                name="pin"
                type="password"
                placeholder="Password"
                autocomplete="off"
                class="form-control required"
                minlength="6"
                maxlength="6"
            >
        </div>
        <div class="form-group">
            <input
                name="pin_confirmation"
                type="password"
                placeholder="Konfirmasi Password"
                autocomplete="off"
                class="form-control required"
                minlength="6"
                maxlength="6"
            >
        </div>
        <div class="form-group">
            <button type="submit" class="btn">Reset Sandi</button>
        </div>
    </form>
@endsection
