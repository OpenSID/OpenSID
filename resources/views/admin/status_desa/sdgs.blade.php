@extends('admin.layouts.index')

@push('css')
    <style>
        .radius {
            border-radius: 5px;
        }

        .info-box-sdgs {
            border: 1px solid;
            border-radius: 10px;
            background-color: #fff;
            border-color: #d8dbe0;
        }

        .info-box-sdgs-icon {
            width: 120px;
            height: 120px;
        }

        .info-box-sdgs-content {
            padding: 5px 10px;
            margin-left: 130px;
        }

        .info-box-sdgs-icon {
            padding-top: 0;
            background: white;
        }

        .info-box-sdgs-text {
            text-transform: capitalize;
        }

        .info-box-icon-sdgs {
            border-radius: 5px;
        }

        .sdgs-logo {
            width: 120px;
            height: 100px;
        }

        .total-bumds {
            font-size: 32px;
            font-weight: bold;
            font-stretch: normal;
            font-style: normal;
            line-height: normal;
            letter-spacing: normal;
            text-align: left;
            color: #232b39;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .desc-bumds {
            margin-top: 8px;
            font-size: 21px;
            font-weight: normal;
            font-stretch: normal;
            font-style: normal;
            line-height: normal;
            letter-spacing: normal;
            text-align: left;
            color: #5a677d;
        }
    </style>
@endpush

@section('title')
    <h1>
        Status SDGS Desa
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Status SDGS Desa</li>
@endsection

@section('content')

    @include('admin.layouts.components.notifikasi')

    @include('admin.status_desa.navigasi')

    <div class="box box-info">
        @if (can('u'))
            <div class="box-header with-border">
                <a class="btn btn-social btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Perbarui" {!! !cek_koneksi_internet() ? 'disabled title="Perangkat tidak terhubung dengan jaringan"' : 'id="perbarui"' !!}><i class="fa fa-refresh"></i>Perbarui {{ $header }}</a>
            </div>
        @endif
        <div class="box-body">
            @if ($sdgs->error_msg)
                <div class="alert alert-danger">
                    {!! $sdgs->error_msg !!}
                </div>
            @else
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="info-box info-box-sdgs" style="display: flex;justify-content: center;">
                            <span class="info-box-number total-bumds" style="text-align: center;">{{ $sdgs->average }}
                                <span class="info-box-text info-box-sdgs-text desc-bumds" style="text-align: center;">Skor
                                    SDGs
                                    {{ setting('sebutan_desa') }}</span>
                            </span>
                        </div>
                    </div>

                    @foreach ($sdgs->data as $key => $value)
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="info-box info-box-sdgs">
                                <span class="info-box-icon info-box-icon-sdgs">
                                    <img class="sdgs-logo" src="{{ asset("images/sdgs/{$value->image}") }}" alt="{{ $value->image }}">
                                </span>
                                <div class="info-box-content info-box-sdgs-content">
                                    <span class="info-box-number info-box-sdgs-number total-bumds">{{ $value->score }}
                                        <span class="info-box-text info-box-sdgs-text desc-bumds">Nilai</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection

@if (can('u'))
    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function() {
                const server_pantau = "{{ config_item('server_pantau') }}";
                const token_pantau = "{{ config_item('token_pantau') }}";
                const kode_desa = "{{ $kode_desa }}";
                const kode_desa_bps = "{{ $kode_desa_bps }}";

                $('#perbarui').click(function(event) {
                    event.preventDefault;
                    location.replace('{{ ci_route('status_desa.perbarui_sdgs') }}');
                });
            });
        </script>
    @endpush
@endif
