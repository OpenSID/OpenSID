@extends('layanan_mandiri.layouts.index')

@section('content')
    <div class="box box-solid">
        <div class="box-header with-border bg-navy">
            <h4 class="box-title">Ganti PIN</h4>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <form action="{{ $form_action }}" method="POST" id="validasi">
                        <div class="box-body">
                            @php $gagal = $data = session('notif'); @endphp
                            @if ($data['status'] == -1)
                                <div class="callout callout-danger">
                                    {!! $gagal['pesan'] !!}
                                </div>
                            @endif

                            @includeWhen($data['status'] == 1, 'layanan_mandiri.layouts.components.notif', $data)

                            <div class="form-group">
                                <label for="pin_lama">PIN Lama</label>
                                <div class="input-group">
                                    <input
                                        type="password"
                                        class="form-control input-md bilangan pin required {{ $cek_anjungan['keyboard'] == 1 ? 'kbvnumber' : '' }}"
                                        name="pin_lama"
                                        placeholder="Masukkan PIN Lama"
                                        minlength="6"
                                        maxlength="6"
                                        autocomplete="off"
                                    >
                                    <span class="input-group-addon"><i class="fa fa-eye-slash" id="lama" onclick="show(this);" aria-hidden="true"></i></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="pin_baru1">PIN Baru</label>
                                <div class="input-group">
                                    <input
                                        type="password"
                                        class="form-control input-md bilangan pin required {{ $cek_anjungan['keyboard'] == 1 ? 'kbvnumber' : '' }}"
                                        name="pin_baru1"
                                        id="pin_baru1"
                                        placeholder="Masukkan PIN Baru"
                                        minlength="6"
                                        maxlength="6"
                                        autocomplete="off"
                                    >
                                    <span class="input-group-addon"><i class="fa fa-eye-slash" id="baru1" onclick="show(this);" aria-hidden="true"></i></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="pin_baru2">Konfirmasi PIN Baru</label>
                                <div class="input-group">
                                    <input
                                        type="password"
                                        class="form-control input-md bilangan pin required {{ $cek_anjungan['keyboard'] == 1 ? 'kbvnumber' : '' }}"
                                        name="pin_baru2"
                                        id="pin_baru2"
                                        placeholder="Masukkan Konfirmasi PIN Baru"
                                        minlength="6"
                                        maxlength="6"
                                        autocomplete="off"
                                    >
                                    <span class="input-group-addon"><i class="fa fa-eye-slash" id="baru2" onclick="show(this);" aria-hidden="true"></i></span>
                                </div>
                            </div>

                            <div class="form-group">
                                @if ($tgl_verifikasi_telegram || $tgl_verifikasi_email)
                                    : ?>
                                    <label style="margin-top: 10px; margin-bottom: 0px;">Kirim PIN Baru Melalui : </label>
                                @endif

                                @if ($tgl_verifikasi_email)
                                    <div class="radio">
                                        <label style="font-size: 13.7px;">
                                            <input type="radio" value="kirim_email" id="kirim_email" name="pilihan_kirim" checked>Email
                                        </label>
                                    </div>
                                @endif

                                @if ($tgl_verifikasi_telegram)
                                    <div class="radio">
                                        <label style="font-size: 13.7px;">
                                            <input type="radio" value="kirim_telegram" id="kirim_telegram" name="pilihan_kirim" checked>Telegram
                                        </label>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="reset" class="btn bg-red">Batal</button>
                            <button type="submit" class="btn bg-green pull-right">Simpan</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-3"></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            setTimeout(function() {
                $('#pin_baru2').rules('add', {
                    equalTo: '#pin_baru1'
                });
            }, 500);
        });

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
    </script>
@endpush
