@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')

@section('title')
    <h1>
        Pengguna <small>Ubah Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('pengguna') }}">Pengguna</a></li>
    <li class="active">Profil</li>
@endsection

@if ($ci->session->change_password)
    <div
        class="modal show"
        id="change_password"
        tabindex="-1"
        role="dialog"
        aria-labelledby="change-password"
        aria-hidden="true"
        style="display: block;"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="change-password">
                        <i class="fa fa-exclamation-triangle text-red"></i>
                        &nbsp;Konfirmasi
                    </h4>
                </div>
                <div class="modal-body btn-info">
                    Anda akan di arahkan untuk melakukan login kembali! </div>
                <div class="modal-footer">
                    <a class="btn-ok" href="{{ site_url('siteman/logout') }}">
                        <button type="button" class="btn btn-social btn-flat btn-success btn-sm" id="ok-delete">
                            <i class="fa fa-sign-out"></i> Keluar
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div class="col-md-3">
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <img class="penduduk" id="foto" src="{{ AmbilFoto(ci_auth()->foto) }}" alt="Foto Penduduk">
                    <br>
                    <div class="input-group input-group-sm text-center">
                        <span class="input-group-btn">
                            @if ($userData->email_verified_at === null && !empty($userData->email))
                                {!! form_open(ci_route('pengguna.kirim_verifikasi')) !!}
                                <button type="submit" class="btn btn-sm btn-warning btn-block btn-mb-5"><i class="fa fa-share-square"></i>
                                    Verifikasi Email</button>
                                </form>
                                <br />
                            @endif
                            @if ($userData->telegram_verified_at === null && $list_setting->firstWhere('key', 'telegram_token')?->value != null && !empty($userData->id_telegram))
                                <button type="button" id="verif_telegram" class="btn btn-sm btn-warning btn-block btn-mb-5"><i class="fa fa-share-square"></i>
                                    Verifikasi Telegram</button>
                                <br />
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#profil" data-toggle="tab"><i class="fa fa-user"></i> &nbsp; Profil</a></li>
                    <li><a href="#sandi" data-toggle="tab"><i class="fa fa-gear"></i> &nbsp; Pengaturan Sandi</a></li>
                    <li><a href="#2fa" data-toggle="tab">Keamanan</a></li>
                    @if(setting('login_otp'))
                    <li class='{{ jecho($tab_ini, 12, 'active') }}'>
                        <a href="#otp" data-toggle="tab" title="Pengaturan Aktivasi OTP">
                            <i class="fa fa-key"></i> &nbsp; Pengaturan Aktivasi OTP  &nbsp;
                            @if(auth()->user()->otp_enabled)
                                {{-- Tampilan untuk layar besar (md, lg) --}}
                                <span class="pull-right hidden-xs hidden-sm"><small class="label pull-right bg-green">Aktif</small></span>
                                {{-- Tampilan ikon untuk layar kecil (xs, sm) --}}
                                <span class="pull-right visible-xs visible-sm"><i class="fa fa-check-circle text-green"></i></span>
                            @else
                                {{-- Tampilan untuk layar besar (md, lg) --}}
                                <span class="pull-right hidden-xs hidden-sm"><small class="label pull-right bg-yellow">Tidak Aktif</small></span>
                                {{-- Tampilan ikon untuk layar kecil (xs, sm) --}}
                                <span class="pull-right visible-xs visible-sm"><i class="fa fa-warning text-yellow"></i></span>
                            @endif
                        </a>
                    </li>
                    @endif
                </ul>
                <div class="tab-content">
                    @include('admin.pengguna.tab-profil')
                    @include('admin.pengguna.tab-sandi')
                    @include('admin.pengguna.tab-2fa')
                    @if(setting('login_otp'))
                    @include('admin.pengguna.tab-otp')
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        // Hapus localStorage untuk timer OTP aktivasi saat halaman ini dimuat.
        // Ini untuk memastikan timer tidak muncul lagi setelah proses selesai/dibatalkan.
        localStorage.removeItem('otpActivationExpiry');

        $(document).ready(function() {
            let _hash = window.location.hash.substring(1)
            if (_hash) {
                $('ul.nav.nav-tabs a[href="#' + _hash + '"]').click()
            }

            // Menyimpan hash tab saat tab diubah
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                let hash = $(e.target).attr("href");
                if (hash.substr(0, 1) == "#") {
                    let pos = $(document).scrollTop();
                    window.location.hash = hash;
                    $(document).scrollTop(pos); // Mencegah halaman melompat ke atas
                }
            });

            $('#verif_telegram').click(function() {
                Swal.fire({
                    title: 'Mengirim OTP',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });
                $.ajax({
                        url: '{{ ci_route('pengguna.kirim_otp_telegram') }}',
                        type: 'get',
                        data: {
                            'sidcsrf': getCsrfToken(),
                            'id_telegram': $('#id_telegram').val()
                        },
                    })
                    .done(function(response) {
                        if (response.status == true) {
                            Swal.fire({
                                title: 'Masukan Kode OTP',
                                input: 'text',
                                inputPlaceholder: 'Masukan Kode OTP',
                                inputValidator: (value) => {
                                    if (isNaN(value)) {
                                        return 'Kode OTP harus berupa angka'
                                    }
                                },
                                showCancelButton: true,
                                confirmButtonText: 'Kirim',
                                cancelButtonText: 'Tutup',
                                showLoaderOnConfirm: true,
                                preConfirm: (otp) => {
                                    const formData = new FormData();
                                    formData.append('sidcsrf', getCsrfToken());
                                    formData.append('id_telegram', response.data);
                                    formData.append('otp', otp);

                                    return fetch(
                                            `{{ ci_route('pengguna.verifikasi_telegram') }}`, {
                                                method: 'POST',
                                                body: formData,
                                            }).then(response => {
                                            if (!response.ok) {
                                                throw new Error(response.statusText)
                                            }
                                            return response.json()
                                        })
                                        .catch(error => {
                                            Swal.showValidationMessage(
                                                `Request failed: ${error}`
                                            )
                                        })
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    if (result.value.status == true) {
                                        $('.close').trigger("click"); //close modal
                                        Swal.fire({
                                            icon: 'success',
                                            title: result.value.message,
                                            showConfirmButton: false,
                                            timer: 1500
                                        })
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: result.value.message
                                        })
                                    }
                                }
                            })
                        } else {
                            Swal.fire({
                                icon: 'error',
                                text: response.message,
                            })
                        }
                    })
                    .fail(function(e) {
                        Swal.fire({
                            icon: 'error',
                            text: e.statusText,
                        })
                    });
            });

            $('input[name=notif_telegram]').change(function() {
                let _val = parseInt($('input[name=notif_telegram]:checked').val())
                if (_val) {
                    $('#id_telegram').closest('.form-group').removeClass('hide')
                } else {
                    $('#id_telegram').closest('.form-group').addClass('hide')
                }
            })
        });
    </script>
@endpush
