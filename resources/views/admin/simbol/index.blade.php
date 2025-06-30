@extends('admin.layouts.index')

@section('title')
    <h1>
        <h1>Simbol Lokasi</h1>
    </h1>
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
            font-size: 10px;
            height: 25px;
            word-wrap: break-word;
            /* Help out IE10+ with class names */
        }

        .bs-glyphicons li {
            float: left;
            width: 25%;
            height: auto;
            padding: 10px;
            margin: 0 -1px -1px 0;
            font-size: 12px;
            line-height: 1.2;
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

@section('breadcrumb')
    <li><a href="{{ ci_route('point.form') }}"> Simbol Lokasi</a></li>
@endsection

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
                    @if (can('u'))
                        <a href="#" id="btn_ikon" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i>Tambah</a>
                    @endif
                    @if (can('h'))
                        <a href="{{ ci_route('simbol.salin_simbol_default') }}" class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Salin">
                            <i class="fa fa-copy"></i>Salin
                        </a>
                    @endif
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="vertical-scrollbar" style="max-height:460px;">
                                <ul id="icons" class="bs-glyphicons">
                                    @foreach ($simbol as $data)
                                        <li>
                                            <label>
                                                <img src="{{ to_base64(LOKASI_SIMBOL_LOKASI . $data['simbol']) }}">
                                                <span class="glyphicon-class">{{ $data['simbol'] }}</span>
                                                @if (can('u'))
                                                    <a
                                                        href="#"
                                                        data-href="{{ ci_route("simbol.delete_simbol.{$data['id']}") }}"
                                                        style="margin-top:10px;"
                                                        class="btn btn-danger btn-sm btn-block"
                                                        title="Hapus"
                                                        data-toggle="modal"
                                                        data-target="#confirm-delete"
                                                    ><i class="fa fa-trash-o"></i></a>
                                                @endif
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
        <!--MODAL TAMBAH SIMBOL-->
        <div class="modal fade" id="ModalSimbol" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                        <h4 class="modal-title" id="myModalLabel">Tambah</h4>
                    </div>
                    <form id="mainform" name="mainform" action="{{ ci_route('simbol.tambah_simbol') }}" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="control-label">Pilih File Simbol</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" id="file_path">
                                    <input id="file" type="file" class="hidden" name="simbol" accept=".gif,.jpg,.jpeg,.png">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-info" id="file_browser"><i class="fa fa-search"></i> Browse</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            {!! batal() !!}
                            <button type="submit" class="btn btn-social btn-info btn-sm" id="simpan"><i class='fa fa-check'></i>Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--END MODAL TAMBAH SIMBOL-->

        @include('admin.layouts.components.konfirmasi_hapus')
    @endsection

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#btn_ikon').on('click', function() {
                    $('#ModalSimbol').modal('show');
                });
            });
        </script>
    @endpush
