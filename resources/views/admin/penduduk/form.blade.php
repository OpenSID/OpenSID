@extends('admin.layouts.index')
@include('admin.layouts.components.datetime_picker')
@section('title')
    <h1>
        Biodata {{ $module_name }}
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ ci_route('penduduk.clear') }}"> Data {{ $module_name }}</a></li>
    <li class="active">Biodata {{ $module_name }}</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <form id="mainform" name="mainform" action="{{ $form_action }}" method="post" enctype="multipart/form-data">
            @include('admin.penduduk.penduduk_form_isian')
        </form>
    </div>
@endsection
