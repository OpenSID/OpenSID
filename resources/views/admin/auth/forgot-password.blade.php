@extends('admin.auth.index')

@section('content')
    <form id="validasi" class="login-form" action="<?= site_url('siteman/kirim_lupa_sandi') ?>" method="post">
        <div class="form-group">
            <input name="email" type="text" placeholder="Email Pengguna" class="form-control required">
        </div>
        <div class="form-group">
            <a href="#" id="b-captcha" onclick="document.getElementById('captcha').src = '<?= site_url('captcha') ?>?' + Math.random();" style="color: #000000;">
                <img id="captcha" src="<?= site_url('captcha') ?>" alt="CAPTCHA Image" />
            </a>
        </div>
        <div class="form-group captcha">
            <input
                name="captcha_code"
                type="text"
                class="form-control"
                maxlength="6"
                placeholder="Masukkan kode diatas"
                autocomplete="off"
                required
            />
        </div>
        <div class="form-group">
            <button type="submit" class="btn">Kirim Lupa Sandi</button>
        </div>
    </form>
@endsection

@push('js')
    <script>
        $('#b-captcha').click();
    </script>
@endpush
