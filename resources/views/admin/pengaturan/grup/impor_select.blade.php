@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Pilih Grup Pengguna Yang Akan Ditambah/Diganti
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Impor Pengguna</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="alert alert-warning alert-dismissible">
        <h4><i class="icon fa fa-warning"></i> Info Penting!</h4>
        Melakukan impor pada grup pengguna yang sebelumnya sudah ada akan menimpa data pengguna dan hak akses lama. Pastikan melakukan backup database terlebih dahulu untuk menghindari hal yang tidak diinginkan.
    </div>
    <div class="box box-info">
        <div class="box-header with-border">
            @if (can('u'))
                <div class="btn-group-vertical radius-3">
                    <a class="btn btn-social btn-sm bg-olive" title="Impor Pengguna" onclick="formAction('mainform', '{{ ci_route('grup.impor_store') }}'); return false;"><i class="fa fa-upload"></i> Impor Pengguna</a>
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
                            <th>NAMA GRUP PENGGUNA</th>
                            <th>SLUG GRUP PENGGUNA</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $row)
                            <tr>
                                <td><input type="checkbox" name="id_cb[]" value="{{ $key }}" /></td>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $row['nama'] }}</td>
                                <td>{{ $row['slug'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            </form>
        </div>
    </div>
@endsection
