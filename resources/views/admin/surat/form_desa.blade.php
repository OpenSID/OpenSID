@include('admin.layouts.components.asset_validasi')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Surat {{ ucwords($surat['nama']) }}
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('surat') }}">Daftar Cetak Surat</a></li>
    <li class="active"> Surat {{ ucwords($surat['nama']) }}</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box box-info">
        <div class="box-header with-border">
            <a href="{{ site_url('surat') }}"
                class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                title="Kembali Ke Daftar Wilayah">
                <i class="fa fa-arrow-circle-left "></i>Kembali Ke Daftar Cetak Surat
            </a>
        </div>
        <div class="box-body">
            <form id="main" name="main" method="POST" class="form-horizontal">
                <div class="form-group">
                    <label for="nik" class="col-sm-3 control-label">NIK / Nama</label>
                    <div class="col-sm-6 col-lg-4">
                        <select class="form-control required input-sm select2" id="nik" name="nik"
                            style="width:100%;" onchange="formAction('main')">
                            <option value="">-- Cari NIK / Nama Penduduk --</option>
                            @foreach ($penduduk as $data)
                                <option value="{{ $data->id }}" @selected($individu->id === $data->id)>NIK :
                                    {{ $data->nik . ' - ' . $data->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>

            {!! form_open($form_action, 'id="validasi" method="POST" class="form-surat form-horizontal"') !!}
            <input type="hidden" id="url_surat" name="url_surat" value="{{ $url }}">
            <input type="hidden" id="url_remote" name="url_remote" value="{{ site_url('surat/nomor_surat_duplikat') }}">
            @if ($individu)
                @include('admin.surat.konfirmasi_pemohon')
            @endif

            <div class="row jar_form">
                <label for="nomor" class="col-sm-3"></label>
                <div class="col-sm-8">
                    <input class="required" type="hidden" name="nik" value="{{ $individu['id'] }}">
                </div>
            </div>

            @include('admin.surat.nomor_surat')

            @foreach ($surat['kode_isian'] as $item)
                @php $nama = underscore($item->nama, true, true) @endphp
                <div class="form-group">
                    <label for="{{ $item->nama }}" class="col-sm-3 control-label">{{ $item->nama }}</label>
                    @if ($item->tipe == 'textarea')
                        <div class="col-sm-8">
                            <textarea name="{{ $nama }}" class="form-control input-sm" placeholder="{{ $item->deskripsi }}" {!! $item->atribut !!}></textarea>
                        </div>
                    @elseif ($item->tipe == 'date')
                        <div class="col-sm-3 col-lg-2">
                            <div class="input-group input-group-sm date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control input-sm tgl" name="{{ $nama }}" placeholder="{{ $item->deskripsi }}" {!! $item->atribut !!}/> 
                            </div>
                        </div>
                    @elseif ($item->tipe == 'time')
                        <div class="col-sm-3 col-lg-2">
                            <div class="input-group input-group-sm date">
                                <div class="input-group-addon">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                                <input type="text" class="form-control input-sm jam" name="{{ $nama }}" placeholder="{{ $item->deskripsi }}" {!! $item->atribut !!}/> 
                            </div>
                        </div>
                    @elseif ($item->tipe == 'datetime')
                        <div class="col-sm-3 col-lg-2">
                            <div class="input-group input-group-sm date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control input-sm tgl_jam" name="{{ $nama }}" placeholder="{{ $item->deskripsi }}" {!! $item->atribut !!}/> 
                            </div>
                        </div>
                    @else
                        <div class="col-sm-8">
                            <input type="{{ $item->tipe }}" class="form-control input-sm {{ $class }}" name="{{ $nama }}" placeholder="{{ $item->deskripsi }}" {!! $item->atribut !!}/> 
                        </div>
                    @endif
                </div>
            @endforeach

            @include('admin.surat.form_tgl_berlaku')

            @include('admin.surat.form_pamong')

        </div>
        <div class="box-footer">
            <button type="reset" class="btn btn-social btn-danger btn-sm" onclick="reset_form($(this).val());"><i
                    class="fa fa-times"></i> Batal</button>
            <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>
                Simpan</button>
        </div>
        </form>
    </div>
@endsection
