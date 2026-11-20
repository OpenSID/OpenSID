@extends('admin.layouts.index')

@section('title')
    <h1>Acak Data</h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ url('database') }}">Pengaturan Database</a></li>
    <li class="active">Acak Data</li>
@endsection

@section('content')
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><strong>Acak Data</strong></h3>
        </div>
        <div class="box-body">
            <div class="alert alert-warning mb-0">
                <p><i class="fa fa-exclamation-triangle"></i> Fitur acak data tidak tersedia untuk database multi-desa (multitenant).</p>
                <p class="mb-0">Acak data adalah alat de-identifikasi untuk satu desa. Pada database multi-desa, proses ini akan mengubah data seluruh desa sekaligus, sehingga sengaja dinonaktifkan.</p>
            </div>
        </div>
    </div>
@endsection
