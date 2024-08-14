@extends('layanan_mandiri.auth.index')

@section('content')
    <form id="validasi" action="<?= $form_action ?>" method="post" class="login-form" enctype="multipart/form-data">
        <h3><strong>PENDAFTARAN AKUN LAYANAN MANDIRI</strong></h3>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <input
                        style="height: 30px"
                        type="text"
                        autocomplete="off"
                        class="form-control required <?= jecho($cek_anjungan['keyboard'] == 1, true, 'kbvnumber') ?>"
                        name="nama"
                        placeholder="Nama"
                        value="{{ old('nama') }}"
                    >
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group" style="margin-bottom: 15px">
                    <input
                        style="height: 30px"
                        placeholder="Tanggal Lahir"
                        type="text"
                        class="form-control required <?= jecho($cek_anjungan['keyboard'] == 1, true, 'kbvnumber') ?>"
                        id="daftar_tgl_lahir"
                        name="tanggallahir"
                        autocomplete="off"
                        value="{{ old('tanggallahir') }}"
                    >
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <input
                        style="height: 30px"
                        type="text"
                        autocomplete="off"
                        class="form-control required <?= jecho($cek_anjungan['keyboard'] == 1, true, 'kbvnumber') ?>"
                        name="nik"
                        placeholder="NIK"
                        minlength="16"
                        maxlength="16"
                        value="{{ old('nik') }}"
                    >
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <input
                        style="height: 30px"
                        type="text"
                        autocomplete="off"
                        class="form-control required <?= jecho($cek_anjungan['keyboard'] == 1, true, 'kbvnumber') ?>"
                        name="no_kk"
                        placeholder="KK"
                        minlength="16"
                        maxlength="16"
                        value="{{ old('no_kk') }}"
                    >
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <input
                        style="height: 30px"
                        type="text"
                        autocomplete="off"
                        class="form-control required <?= jecho($cek_anjungan['keyboard'] == 1, true, 'kbvnumber') ?>"
                        name="email"
                        placeholder="Email"
                        value="{{ old('email') }}"
                    >
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group" style="margin-bottom: 15px">
                    <input
                        style="height: 30px"
                        type="text"
                        autocomplete="off"
                        class="form-control required <?= jecho($cek_anjungan['keyboard'] == 1, true, 'kbvnumber') ?>"
                        name="telegram"
                        placeholder="Telegram"
                        value="{{ old('telegram') }}"
                    >
                    <span class="input-group-addon"><i onclick="window.open('https://t.me/opensid_notifikasi_bot', '_blank');" class="fa fa-search"></i></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="input-group" style="margin-bottom: 15px">
                    <input
                        style="height: 30px"
                        type="password"
                        class="form-control bilangan pin required <?= jecho($cek_anjungan['keyboard'] == 1, true, 'kbvnumber') ?>"
                        name="password"
                        placeholder="PIN"
                        minlength="6"
                        maxlength="6"
                    >
                    <span class="input-group-addon"><i onclick="show(this)" class="fa fa-eye-slash"></i></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group" style="margin-bottom: 15px">
                    <input
                        style="height: 30px"
                        type="password"
                        class="form-control bilangan pin required <?= jecho($cek_anjungan['keyboard'] == 1, true, 'kbvnumber') ?>"
                        name="password_confirmation"
                        placeholder="Konfirmasi PIN"
                        minlength="6"
                        maxlength="6"
                    >
                    <span class="input-group-addon"><i onclick="show(this)" class="fa fa-eye-slash"></i></span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">Unggah Scan KTP</label>
                </div>
                <div class="col-sm-8">
                    <input style="height: 30px" type="file" id="fileInput" name="scan_1" class="form-control required" accept=".gif,.jpg,.jpeg,.png">
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">Unggah Scan KK</label>
                </div>
                <div class="col-sm-8">
                    <input style="height: 30px" type="file" id="fileInput" name="scan_2" class="form-control required" accept=".gif,.jpg,.jpeg,.png">
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-4">
                    <label class="control-label">Unggah Foto Selfie dan Membawa KTP</label>
                </div>
                <div class="col-sm-8">
                    <input style="height: 30px" type="file" id="fileInput" name="scan_3" class="form-control required" accept=".gif,.jpg,.jpeg,.png">
                </div>
            </div>
        </div>
        <div class="center">
            <small style="color: crimson; font-size: 12px;">Gambar ukuran maksimal: 1024kb, tipe gambar: .gif,.jpg,.jpeg,.png </small>
        </div>

        <div class="form-group">
            <div class="form-group">
                <button type="submit" class="btn btn-block bg-green"><b>BUAT AKUN</b></button>
            </div>
        </div>
        <div class="form-group">
            <a href="<?= site_url('layanan-mandiri/masuk') ?>">
                <button type="button" class="btn btn-block bg-green"><b>SUDAH PUNYA AKUN</b></button>
            </a>
        </div>
    </form>
@endsection

@push('script')
    <script>
        function show(elem) {
            if ($(elem).hasClass('fa-eye')) {
                $(".pin").attr('type', 'password');
                $(".fa-eye").addClass('fa-eye-slash');
                $(".fa-eye").removeClass('fa-eye');
            } else {
                $(".pin").attr('type', 'text');
                $(".fa-eye-slash").addClass('fa-eye');
                $(".fa-eye-slash").removeClass('fa-eye-slash');
            }
        }

        $('document').ready(function() {
            $('#daftar_tgl_lahir').datetimepicker({
                format: 'YYYY-MM-DD',
                locale: 'id',
                maxDate: 'now',
            });

            $("#daftar_tgl_lahir").on('change keyup paste click keydown', parseInt($('#daftar_tgl_lahir').val().substring(6, 10)));
        });
    </script>
@endpush
