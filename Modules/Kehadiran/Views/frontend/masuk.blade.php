@extends('kehadiran::frontend.layouts.index')

@section('content')
    @include('admin.layouts.components.konfirmasi_cookie')
    @include('admin.layouts.components.aktifkan_cookie')

    <div class="row vertical-align" style="background-color: #ffffff">
        <div class="col-sm-8 hidden-xs" style="padding: 0px;">
            @include('kehadiran::frontend.left')
        </div>
        <div class="col-sm-4 col-xm-4">
            <div class="login-box">
                <div class="login-box-body">
                    <p class="login-logo"><b>Masuk Ke Aplikasi</b></p>
                    <div class="row">
                        @include('admin.layouts.components.notifikasi')
                    </div>
                    @if ($cek['status'])
                        {!! form_open(ci_route('kehadiran.cek'), 'class="form-horizontal" id="validasi"') !!}
                        @if ($ektp)
                            <div class="form-group thumbnail">
                                <img src="{{ asset('images/camera-scan.gif') }}" alt="scanner" class="center" style="width:30%">
                            </div>
                            <div class="form-group" style="{{ jecho(ENVIRONMENT == 'development', false, 'width: 0; overflow: hidden;') }}">
                                <input
                                    name="tag"
                                    id="tag"
                                    autocomplete="off"
                                    placeholder="Tempelkan e-KTP Pada Card Reader"
                                    class="form-control required number"
                                    type="password"
                                    onkeypress="if (event.keyCode == 13){$('#'+'validasi').attr('action', '{{ ci_route('kehadiran.cek-ektp') }}');$('#'+'validasi').submit();}"
                                >
                            </div>
                            <div class="form-group">
                                <a href="{{ ci_route('kehadiran.masuk') }}" class="btn btn-success btn-block btn-flat">MASUK DENGAN USERNAME/NIK</a>
                            </div>
                        @else
                            <div class="form-group has-feedback">
                                <input
                                    type="text"
                                    name="username"
                                    id="username"
                                    autocomplete="off"
                                    class="form-control"
                                    placeholder="Username / NIK"
                                    required
                                >
                                <span class="glyphicon glyphicon glyphicon-user form-control-feedback"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    autocomplete="off"
                                    class="form-control"
                                    placeholder="Password"
                                    required
                                >
                                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success btn-block btn-flat">MASUK</button>
                                <a href="{{ ci_route('kehadiran.masuk-ektp') }}" class="btn btn-success btn-block btn-flat">MASUK DENGAN EKTP</a>
                            </div>
                        @endif
                        </form>
                    @else
                        <div class="alert alert-danger">
                            <h4><i class="icon fa fa-ban"></i> {{ $cek['judul'] }}</h4>
                            {{ $cek['pesan'] }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $('document').ready(function() {
            var ektp = "{{ $ektp }}";

            if (ektp) {
                $('#tag').focus();
            } else {
                $('#username').focus();
            }
        });
    </script>
@endpush
