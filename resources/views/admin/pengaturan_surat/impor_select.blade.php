@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Pilih Surat Yang Akan Ditambah/Diganti
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Impor Surat</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box box-info">
        <div class="box-header with-border">
            @if (can('u'))
                <div class="btn-group-vertical radius-3">
                    <a class="btn btn-social btn-sm bg-olive" title="Impor Surat" onclick="formAction('mainform', '{{ ci_route('surat_master.impor_store') }}'); return false;"><i class="fa fa-upload"></i> Impor Surat</a>
                </div>
            @endif
        </div>
        {!! form_open(null, 'id="mainform" name="mainform"') !!}
        <div class="box-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover tabel-daftar" id="tabeldata">
                    <thead class="bg-gray">
                        <tr>
                            <th class="padat"><input type="checkbox" id="checkall" /></th>
                            <th class="padat">NO</th>
                            <th>NAMA SURAT</th>
                            <th>URL SURAT</th>
                            <th class="padat">KODE / KLASIFIKASI</th>
                            <th class="padat">LAMPIRAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $row)
                            <tr>
                                <td><input type="checkbox" name="id_cb[]" value="{{ $key }}" /></td>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $row['nama'] }}</td>
                                <td>{{ $row['url_surat'] }}</td>
                                <td>{{ $row['kode_surat'] }}</td>
                                <td>{{ $row['lampiran'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            </form>
        </div>
    </div>
@endsection
