@extends('admin.layouts.index')

@section('title')
    <h1>
        Keuangan
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ ci_route('keuangan_manual') }}">Laporan Keuangan</a></li>
    <li class="active">Rincian Realisasi</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box">
        <div class="box-header with-border">
            <form action="" method="get">
                <div class="row col-md-3">
                    <label class="col-md-4">Tahun Anggaran: </label>
                    <div class="col-md-8">
                        <select class="form-control" name="tahun" required onchange="this.form.submit()">
                            <option value="">Pilih Tahun</option>
                            @foreach ($tahun_anggaran as $item)
                                <option value="{{ $item->tahun }}" @selected($tahun == $item->tahun)>{{ $item->tahun }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-3">
                    @include('admin.keuangan.laporan.menu')
                </div>
                <div class="col-md-9">
                    @include('admin.keuangan.laporan.apbd')
                </div>
            </div>
        </div>
    </div>
@endsection
