@extends('anjungan::frontend.beranda.index')

@push('css')
    <style>
        .btn-position {
            display: flex;
            justify-content: space-evenly;
        }

        .btn-position a {
            width: 35%;
        }

        .middle-content {
            margin-top: 10%;
            margin-bottom: 10%;
        }

        .info-box {
            border-radius: 10px;
        }

        .info-box-icon {
            border-radius: 10px;
        }

        .info-box-content {
            line-height: 77px;
            padding: 5px 10px;
            text-align: center !important;
        }

        .modal-content {
            border-radius: 5px;
            background: #ffffff;
            border: 2px solid black;
        }

        .info-box-text-widget {
            font-size: 18px;
            font-weight: 700;
        }
    </style>
@endpush
@section('content')
    <!-- Mulai Kolom Kanan -->
    <div class="area-content">
        <div class="area-content-inner">
            <section class="content-header middle-content">
                <div class="row hidden-xs">
                    <div class="col-lg-12 btn-position">
                        <a href="{{ route('anjungan.surat') }}">
                            <div class="col-xs-12">
                                <div class="info-box bg-green">
                                    <span class="info-box-icon"><i class="fa fa-file-word-o"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text-widget">Surat</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('anjungan.permohonan') }}">
                            <div class="col-xs-12">
                                <div class="info-box bg-yellow">
                                    <span class="info-box-icon"><i class="fa fa-print"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text-widget">Permohonan</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-12 btn-position">
                        <a href="#" data-toggle="modal" data-target="#pendapat" style="margin-top: 20px;">
                            <div class="col-xs-12">
                                <div class="info-box bg-red">
                                    <span class="info-box-icon"><i class="fa fa-sign-out"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text-widget">Keluar</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <!-- Batas Kolom Kanan -->
    @include('layanan_mandiri.components.pendapat')
@endsection
