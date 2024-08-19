@extends('admin.layouts.index')

@section('title')
    <h1>
        Pengaturan Paket Tambahan
    </h1>
@endsection

@section('breadcrumb')
    <li class="active"><a href="{{ ci_route('plugin') }}">Pengaturan Paket Tambahan</a></li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li {!! $act_tab == 1 ? 'class="active"' : '' !!}><a href="{{ ci_route('plugin') }}">Paket Tersedia</a></li>
            @if (can('u'))
                <li {!! $act_tab == 2 ? 'class="active"' : '' !!}><a href="{{ ci_route('plugin.installed') }}">Paket Terpasang</a></li>
            @endif
        </ul>
        <div class="tab-content">
            @include($content)
        </div>
    @endsection
