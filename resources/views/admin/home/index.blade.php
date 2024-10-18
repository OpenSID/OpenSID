@extends('admin.layouts.index')

@push('css')
    <style>
        .catatan-scroll {
            height: 400px;
            overflow-y: scroll;
        }

        @media (max-width: 576px) {
            .komunikasi-opendk {
                display: none !important;
            }
        }
    </style>
@endpush

@section('title')
    <h1>
        Tentang <?= config_item('nama_aplikasi') ?>
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Tentang <?= config_item('nama_aplikasi') ?></li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    @include('admin.home.saas')

    @include('admin.home.premium')

    @include('admin.home.rilis')

    <div class="row">
        @foreach ($shortcut as $sc)
            @if (can('b', $sc['akses']))
                <div class="col-lg-3 col-sm-6 col-xs-6">
                    <div class="small-box" style="background-color: {!! $sc['warna'] !!}; border-radius: 5px;">
                        <div class="inner">
                            <h3 class="text-white">{{ $sc['count'] ?? '0' }}</h3>
                            <p class="text-white">{{ SebutanDesa($sc['judul']) }}</p>
                        </div>
                        <div class="icon">
                            <i class="faa {!! $sc['icon'] !!}"></i>
                        </div>
                        <a href="{{ ci_route($sc['link'] ?? '#') }}" class="small-box-footer text-white" style="border-radius:  0 0 5px 5px">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endsection
