@extends('anjungan::frontend.beranda.index')

@push('css')
    <style>
        .list-surat {
            margin-top: 19px;
            display: flex;
            justify-content: flex-start;
            flex-wrap: wrap;
            height: 320px;
            overflow-y: scroll;
        }

        .btn-app {
            margin: 0 0 10px 10px !important;
            padding: 5px;
            width: 110px;
            height: 110px;
        }

        .text-wrap {
            display: inline-block;
            white-space: normal;
            max-width: 100%;
        }

        .wrapper-mandiri {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .icon-label {
            display: grid;
            margin: 13px auto;
        }

        .icon-label i {
            font-size: large;
        }
    </style>
@endpush
@section('content')
    <!-- Mulai Kolom Kanan -->
    <div class="area-content">
        <div class="area-content-inner">
            <section class="content-header">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h3>Layanan Surat</h3>
                    </div>
                    <div class="col-lg-12">
                        <div class="list-surat">
                            @foreach ($menu_surat_mandiri as $item)
                                <a href="{{ route('anjungan.surat.form', $item['id']) }}" class="btn btn-app">
                                    {{-- <a href="{{ ci_route('layanan-mandiri.surat_anjungan.form', $item['id']) }}"
                                class="btn btn-app"> --}}
                                    <div class="icon-label">
                                        <i class="fa fa-file-text-o"></i><span class="text-wrap" style="margin-top: 10px;">{{ truncateText($item['nama'], 30) }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
            </section>
        </div>
    </div>
    <!-- Batas Kolom Kanan -->
@endsection
