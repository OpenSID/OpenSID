@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')
@section('title')
    <h1>
        Tipe Lokasi
        <small>{{ $aksi }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ ci_route('area') }}"> Area</a></li>
    <li class="active">{{ $aksi }} Data</li>
@endsection
@push('css')
    <style>
        .bs-glyphicons {
            padding-left: 0;
            padding-bottom: 1px;
            margin-bottom: 20px;
            list-style: none;
            overflow: hidden;
        }

        .bs-glyphicons .glyphicon {
            margin-top: 5px;
            margin-bottom: 10px;
            font-size: 24px;
        }

        .bs-glyphicons .glyphicon-class {
            display: block;
            text-align: center;
            word-wrap: break-word;
            /* Help out IE10+ with class names */
        }

        .bs-glyphicons li {
            float: left;
            width: 25%;
            height: 115px;
            padding: 10px;
            margin: 0 -1px -1px 0;
            font-size: 12px;
            line-height: 1.4;
            text-align: center;
            border: 1px solid #ddd;
        }

        .bs-glyphicons li:hover,
        .bs-glyphicons li.active {
            background-color: #605ca8;
            color: #fff;
        }

        @media (min-width: 768px) {
            .bs-glyphicons li {
                width: 12.5%;
            }
        }

        .vertical-scrollbar {
            overflow-x: hidden;
            overflow-y: auto;
        }
    </style>
@endpush
@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div class="col-md-3">
            @include('admin.peta.nav')
        </div>
        <div class="col-md-9">
            {!! form_open_multipart($form_action, 'class="form-horizontal" id="validasi"') !!}
            <div class="box box-info">
                <div class="box-header with-border">
                    <a href="{{ ci_route('point') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                        <i class="fa fa-arrow-circle-left "></i>Kembali ke Tipe Lokasi
                    </a>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label for="nama" class="col-sm-2 control-label">Nama Jenis Lokasi</label>
                        <div class="col-sm-8">
                            <input
                                id="nama"
                                class="form-control input-sm nomor_sk required"
                                maxlength="100"
                                type="text"
                                placeholder="Nama Jenis Lokasi"
                                name="nama"
                                required=""
                                value="<?= $point['nama'] ?>"
                            >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="nomor" class="col-sm-2 control-label">Simbol</label>
                        <div class="col-sm-4">
                            @if ($point['simbol'] != '')
                                <img src="{{ base_url(LOKASI_SIMBOL_LOKASI) . $point['simbol'] }}" />
                            @else
                                <img src="{{ base_url(LOKASI_SIMBOL_LOKASI) }}default.png" />
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="id_master" class="col-sm-2 control-label">Ganti Simbol</label>
                        <div class="col-sm-10">
                            <div class="vertical-scrollbar" style="max-height:300px;">
                                <ul id="icons" class="bs-glyphicons">
                                    @foreach ($simbol as $data)
                                        <li @if ($point['simbol'] == $data['simbol']) class="active" id="simbol_active" @endif onclick="li_active($(this).val());">
                                            <label>
                                                <input type="radio" name="simbol" id="simbol" class="hidden" value="{{ $data['simbol'] }}" @checked($point['simbol'] == $data['simbol'])>
                                                <img src="{{ base_url(LOKASI_SIMBOL_LOKASI) . $data['simbol'] }}">
                                                <span class="glyphicon-class">{{ $data['simbol'] }}</span>
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='box-footer'>
                    <div>
                        <button type='reset' class='btn btn-social btn-danger btn-sm'><i class='fa fa-times'></i>
                            Batal</button>
                        <button type='submit' class='btn btn-social btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i> Simpan</button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function li_active() {
            $('li').click(function() {
                $('li.active').removeClass('active');
                $(this).addClass('active');
                $(this).children("input[type=radio]").click();
            });
        };

        function reset_form() {
            $('li.active').removeClass('active');
            $('#simbol_active').addClass('active');
        };
    </script>
@endpush
