@extends('admin.layouts.index')

@section('title')
    <h1>
        Pengaduan
        <small>{{ $action }}</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('pengaduan_admin') }}">Daftar Pengaduan</a></li>
    <li class="active">{{ $action }}</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            <a href="{{ route('pengaduan_admin') }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Pengaduan</a>
        </div>
        <form class="form-horizontal">
            <div class="box-body">
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="nik">NIK</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" disabled value="{{ $pengaduan_warga->nik }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="nik">Nama</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" disabled value="{{ $pengaduan_warga->nama }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="nik">Email</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" disabled value="{{ $pengaduan_warga->email }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="nik">Nomor Telepon</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" disabled value="{{ $pengaduan_warga->telepon }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="nik">Judul</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" disabled value="{{ $pengaduan_warga->judul }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="nik">Tanggal</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" disabled value="{{ $pengaduan_warga->created_at }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="isi">Isi</label>
                    <div class="col-sm-9">
                        <textarea class="form-control input-sm" maxlength="300" rows="3" disabled style="resize:none;">{{ $pengaduan_warga->isi }}</textarea>
                    </div>
                </div>
                @if ($pengaduan_warga->foto)
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="isi">Gambar</label>
                        <div class="col-sm-9">
                            <img class="img-responsive" src="{{ to_base64(LOKASI_PENGADUAN . $pengaduan_warga->foto) }}">
                        </div>
                    </div>
                @endif

                @foreach ($tanggapan as $item)
                    <hr>
                    <div class="row support-content-comment">
                        <div class="col-md-12">
                            <p>Ditanggapi oleh <b>{{ $item->nama }}</b> | {{ $item->created_at }}</p>
                            <p>{{ $item->isi }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </form>
    </div>
@endsection
