@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')

@section('title')
    <h1>
        Pengguna
        <small>{{ $action }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ site_url('man_user') }}">Pengguna</a></li>
    <li class="active">{{ $action }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <form id="validasi" action="{{ $form_action }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-body box-profile preview-img">
                        <img class="penduduk img-responsive" src="{{ AmbilFoto($user['foto']) }}" alt="Foto Pengguna">
                        <br />
                        <p class="text-center text-bold">Foto Pengguna</p>
                        <p class="text-muted text-center text-red">(Kosongkan, jika foto tidak berubah)</p>
                        <br />
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control file-path" readonly name="foto">
                            <input type="file" class="hidden file-input" name="foto" accept=".gif,.jpg,.jpeg,.png">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-info btn-flat file-browser"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <a href="{{ site_url('man_user') }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Manajemen Pengguna</a>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="group">Group</label>
                            <div class="col-sm-8">
                                <select class="form-control input-sm required" id="id_grup" name="id_grup">
                                    @foreach ($user_group as $item)
                                        <option @selected($user['id_grup'] == $item['id']) value="{{ $item['id'] }}">
                                            {{ $item['nama'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="nama">Data Staf </label>
                            <div class="col-sm-8">
                                <select class="form-control select2 input-sm" id="pamong_id" name="pamong_id">
                                    <option value>-- Silakan Masukan Nama Staf --</option>
                                    @foreach ($pamong as $item)
                                        <option value="{{ $item->pamong_id }}" data-nama="{{ $item['pamong_nama'] }}" @selected($user['pamong_id'] == $item->pamong_id)>
                                            {{ $item->pamong_jabatan . ' - ' . $item->pamong_nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="username">Username</label>
                            <div class="col-sm-8">
                                <input
                                    id="username"
                                    name="username"
                                    class="form-control input-sm required username"
                                    type="text"
                                    placeholder="Username"
                                    value="{{ $user['username'] }}"
                                    autocomplete="off"
                                ></input>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="password">Kata Sandi</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input id="password" name="password" class="form-control input-sm pwdLengthNist_atau_kosong {{ $user ? '' : 'required' }}" type="password" placeholder="{{ $user ? 'Ubah Password' : 'Password' }}" autocomplete="off"></input>
                                    <span class="input-group-addon input-sm reveal"><i class="fa fa-eye-slash"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="aktif" class="col-sm-3 control-label">Status</label>
                            <div class="btn-group col-xs-12 col-sm-8 " data-toggle="buttons">
                                <label class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label {{ compared_return($user['active'], '1') }}">
                                    <input type="radio" name="aktif" class="form-check-input" value="1" @selected($user['active'] == 1)> Aktif
                                </label>
                                <label class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label {{ compared_return($user['active'], '0') }}">
                                    <input type="radio" name="aktif" class="form-check-input" value="0" @selected($user['active'] == 0)> Tidak Aktif
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="batasi_wilayah" class="col-sm-3 control-label">Akses Wilayah</label>
                            <div class="btn-group col-xs-12 col-sm-8 " data-toggle="buttons">
                                <label class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label @active($user['batasi_wilayah'] == '1')">
                                    <input type="radio" name="batasi_wilayah" class="form-check-input" value="1" @checked($user['batasi_wilayah'] == 1)> Aktif
                                </label>
                                <label class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label @active($user['batasi_wilayah'] != '1')">
                                    <input type="radio" name="batasi_wilayah" class="form-check-input" value="0" @checked($user['batasi_wilayah'] != 1)> Tidak Aktif
                                </label>
                            </div>
                        </div>

                        <div class="form-group akses_wilayah">
                            <div class="col-sm-8 col-sm-offset-3" style="padding: 0px">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th colspan="5">Wilayah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($wilayah as $dusun => $items)
                                            <tr>
                                                <td class="padat">{{ $loop->iteration }}</td>
                                                <td colspan="4">
                                                    <div class="checkbox">
                                                        <label><input type="checkbox" data-target="[data-dusun={{ underscore($dusun) }}]" class="dusun_checkbox"><strong>&nbsp;{{ strtoupper(setting('sebutan_dusun')) }} {{ $dusun }} </strong></label>
                                                    </div>
                                                </td>
                                                <td class="padat">
                                                    <a onclick="hideShow(this, 'rw')" data-target="[data-dusun={{ underscore($dusun) }}]" class="fa fa-plus btn" href="#"></a>
                                                </td>
                                            </tr>
                                            @foreach ($items as $rw => $item)
                                                <tr data-dusun="{{ underscore($dusun) }}" class="hide">
                                                    <td></td>
                                                    <td class="padat">&nbsp;&nbsp;{{ $loop->iteration }}</td>
                                                    <td colspan="3">
                                                        <div class="checkbox">
                                                            <label><input type="checkbox" data-target="[data-rw={{ underscore($dusun) }}_{{ $rw }}]" class="rw_checkbox" value=""><strong>&nbsp; RW {{ $rw }}</strong></label>
                                                        </div>
                                                    </td>
                                                    <td class="padat">
                                                        <a onclick="hideShow(this, 'rt')" data-target="[data-rw={{ underscore($dusun) }}_{{ $rw }}]" class="fa fa-plus btn" href="#"></a>
                                                    </td>
                                                </tr>
                                                @foreach ($item as $rt)
                                                    <tr data-rw="{{ underscore($dusun) }}_{{ $rw }}" class="hide">
                                                        <td></td>
                                                        <td></td>
                                                        <td class="padat">&nbsp;&nbsp;{{ $loop->iteration }}</td>
                                                        <td colspan="2">
                                                            <div class="checkbox">
                                                                <label><input type="checkbox" name="akses_wilayah[]" @checked(in_array($rt->id, $user['akses_wilayah'] ?? [])) value="{{ $rt->id }}"><strong>&nbsp;RT {{ $rt->rt }} </strong></label>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="nama">Nama</label>
                            <div class="col-sm-8">
                                <input
                                    id="nama"
                                    name="nama"
                                    class="form-control input-sm required nama"
                                    minlength="3"
                                    maxlength="50"
                                    type="text"
                                    placeholder="Nama"
                                    value="{{ $user['nama'] }}"
                                ></input>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="phone">Nomor HP</label>
                            <div class="col-sm-8">
                                <input
                                    id="phone"
                                    name="phone"
                                    class="form-control input-sm bilangan"
                                    minlength="10"
                                    maxlength="15"
                                    type="text"
                                    placeholder="Nomor HP"
                                    value="{{ $user['phone'] }}"
                                ></input>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="email">Email</label>
                            <div class="col-sm-8">
                                <input id="email" name="email" class="form-control input-sm email" type="email" placeholder="Alamat E-mail" value="{{ $user['email'] }}"></input>
                            </div>
                        </div>
                        @if ($notifikasi_telegram)
                            <div class="form-group">
                                <label for="notif_telegram" class="col-sm-3 control-label">Notifikasi Telegram</label>
                                <div class="btn-group col-xs-12 col-sm-8 " data-toggle="buttons">
                                    <label class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label {{ compared_return($user['notif_telegram'], '1') }}" @disabled(setting('telegram_token') == null)>
                                        <input
                                            type="radio"
                                            name="notif_telegram"
                                            class="form-check-input"
                                            value="1"
                                            autocomplete="off"
                                            @selected($user['notif_telegram'] == 1)
                                            @disabled(setting('telegram_token') == null)
                                        > Aktif
                                    </label>
                                    <label class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label {{ compared_return($user['notif_telegram'], '0') }}" @disabled(setting('telegram_token') == null)>
                                        <input
                                            type="radio"
                                            name="notif_telegram"
                                            class="form-check-input"
                                            value="0"
                                            autocomplete="off"
                                            @selected($user['notif_telegram'] == 0)
                                            @disabled(setting('telegram_token') == null)
                                        > Matikan
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="catatan" class="col-sm-3 control-label">User ID Telegram</label>
                                <div class="col-sm-8">
                                    <input
                                        class="form-control input-sm id_telegram"
                                        type="text"
                                        id="id_telegram"
                                        name="id_telegram"
                                        value="{{ $user['id_telegram'] }}"
                                        maxlength="10"
                                        @disabled(setting('telegram_token') == null)
                                    />
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="box-footer">
                        <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i>
                            Batal</button>
                        <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        function hideShow(elm, level) {
            const target = $(elm).data('target')
            $(elm).toggleClass('fa-minus fa-plus')
            if ($(elm).hasClass('fa-minus')) {
                $(target).removeClass('hide')
            } else {
                $(target).addClass('hide')
                if (level == 'rw') {
                    $(target).find('td:last>a').removeClass('fa-plus')
                    $(target).find('td:last>a').addClass('fa-minus')
                    $(target).find('td:last>a').trigger('click')
                }
            }

        }
        $(function() {
            $('input[name="batasi_wilayah"]').change(function(e) {
                e.preventDefault();
                if ($(this).val() == 1) {
                    $('.akses_wilayah').show();
                } else {
                    $('.akses_wilayah').hide();
                }
            });
            $('#pamong_id').on('select2:select', function(e) {
                var data = $('#pamong_id :selected').data('nama')
                $('#nama').val(data);
            });

            $('input[name="notif_telegram"]').change(function(e) {
                e.preventDefault();
                if ($(this).val() == 1) {
                    $('input[name="id_telegram"]').closest('.form-group').show();
                    $('input[name="id_telegram"]').addClass('required id_telegram');
                } else {
                    $('input[name="id_telegram"]').closest('.form-group').hide();
                    $('input[name="id_telegram"]').removeClass('required id_telegram');
                }
            });

            $('.reveal').on('click', function() {
                var $pwd = $("#password");
                if ($pwd.attr('type') === 'password') {
                    $pwd.attr('type', 'text');

                    $(".reveal i").removeClass("fa-eye-slash");
                    $(".reveal i").addClass("fa-eye");
                } else {
                    $pwd.attr('type', 'password');

                    $(".reveal i").addClass("fa-eye-slash");
                    $(".reveal i").removeClass("fa-eye");
                }
            });

            $('input[value="{{ $user['active'] ?? 1 }}"][name="aktif"]').parent().trigger('click');
            $('input[value="{{ $user['notif_telegram'] ?? 0 }}"][name="notif_telegram"]').parent().trigger('click');

            $('.rw_checkbox').change(function() {
                const target = $(this).data('target')
                if ($(this).is(':checked')) {
                    $(target).find(':checkbox').prop('checked', true)
                } else {
                    $(target).find(':checkbox').prop('checked', false)
                }
            })

            $('.dusun_checkbox').change(function() {
                const target = $(this).data('target')
                if ($(this).is(':checked')) {
                    $(target).find(':checkbox').prop('checked', true)
                } else {
                    $(target).find(':checkbox').prop('checked', false)
                }
                $(target).find(':checkbox').trigger('change')
            })

            $('.rw_checkbox').each(function() {
                const target = $(this).data('target')
                const check = $(target).find(':checkbox:checked').length
                if (check) {
                    $(this).prop('checked', true)
                }
            })

            $('.dusun_checkbox').each(function() {
                const target = $(this).data('target')
                const check = $(target).find(':checkbox:checked').length
                if (check) {
                    $(this).prop('checked', true)
                }
            })

            $('input[name="batasi_wilayah"]:checked').trigger('change')
        });
    </script>
@endpush
