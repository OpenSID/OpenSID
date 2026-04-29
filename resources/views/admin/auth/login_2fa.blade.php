@extends('admin.auth.index')

@section('content')
    <form id="validasi" class="login-form" action="{{ $form_action }}" method="post">
        <div class="form-group">
            <input
                name="oneTimePassword"
                type="text"
                autocomplete="off"
                placeholder="Kode verifikasi"
                class="form-username form-control required"
                minlength="6"
                maxlength="6"
            >
        </div>

        <div class="form-group">
            <button type="submit" class="btn">Masuk</button>
        </div>
    </form>
@endsection
