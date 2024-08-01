@extends('admin.layouts.index')

@section('title')
    <h1>
        Pengaturan Database
    </h1>
@endsection

@section('breadcrumb')
    <li class="active"><a href="{{ ci_route('database') }}">Pengaturan Database</a></li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li {!! $act_tab == 1 ? 'class="active"' : '' !!}><a href="{{ ci_route('database') }}">Backup {{ jecho(config_item('demo_mode'), false, ' /Restore') }}</a></li>
            @if (can('u'))
                <li {!! $act_tab == 2 ? 'class="active"' : '' !!}><a href="{{ ci_route('database.migrasi_cri') }}">Migrasi DB</a></li>
            @endif
        </ul>
        <div class="tab-content">
            @include($content)
        </div>
    @endsection
