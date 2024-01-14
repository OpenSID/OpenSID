@include('admin.pengaturan_surat.asset_tinymce')
@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
<h1>
    Pengaturan Teks Berjalan
</h1>
@endsection

@section('breadcrumb')
<li class="active">Pengaturan Teks Berjalan</li>
@endsection

@section('content')

@include('admin.layouts.components.notifikasi')

<form id="mainform" name="mainform" method="post">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    @if (can('u'))
                    <a href="{{ route('teks_berjalan.form') }}" class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"  title="Tambah Artikel">
                        <i class="fa fa-plus"></i> Tambah Teks
                    </a>
                    @endif
                    @if (can('h'))
                    <a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '{{ route('teks_berjalan.delete_all') }}')" class="btn btn-social btn-flat btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
                    @endif
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                <form id="mainform" name="mainform" method="post">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped dataTable table-hover tabel-daftar">
                                                    <thead class="bg-gray disabled color-palette">
                                                        <tr>
                                                            @if (can('h'))
                                                                <th><input type="checkbox" id="checkall"/></th>
                                                            @endif
                                                            <th>No.</th>
                                                            @if (can('u'))
                                                                <th>Aksi</th>
                                                            @endif
                                                            <th>Isi Teks Berjalan</th>
                                                            <th>Tautan ke Artikel</th>
                                                            <th>Tampil Di</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($main as $data)
                                                            <tr>
                                                                @if (can('h'))
                                                                    <td class="padat"><input type="checkbox" name="id_cb[]" value="<?=$data['id']?>" /></td>
                                                                @endif
                                                                <td class="padat">{{ $data->no }} </td>
                                                                @if (can('u'))
                                                                    <td class="aksi">
                                                                        @if (can('u'))
                                                                            <a href="{{ route('teks_berjalan.urut').'/'. $data->id.'/1'}}" class="btn bg-olive btn-flat btn-sm"  title="Pindah Posisi Ke Bawah"><i class="fa fa-arrow-down"></i></a>
                                                                            <a href="{{ route('teks_berjalan.urut').'/'. $data->id.'/2'}}" class="btn bg-olive btn-flat btn-sm"  title="Pindah Posisi Ke Atas"><i class="fa fa-arrow-up"></i></a>
                                                                            <a href="{{ route('teks_berjalan.form').'/'. $data->id }}" class="btn bg-orange btn-flat btn-sm"  title="Ubah"><i class="fa fa-edit"></i></a>
                                                                            @if ($data->status == '2')
                                                                                <a href="{{ route('teks_berjalan.lock').'/'. $data->id.'/1'}}" class="btn bg-navy btn-flat btn-sm"  title="Aktifkan"><i class="fa fa-lock">&nbsp;</i></a>
                                                                            @else
                                                                                <a href="{{ route('teks_berjalan.lock').'/'. $data->id.'/2'}}" class="btn bg-navy btn-flat btn-sm"  title="Non Aktifkan"><i class="fa fa-unlock"></i></a>
                                                                            @endif
                                                                        @endif
                                                                        @if (can('h'))
                                                                    
                                                                        <a href="#" data-href="{{ route('teks_berjalan.delete').'/'. $data->id}}" class="btn bg-maroon btn-flat btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
                                                                        @endif
                                                                    </td>
                                                                @endif
                                                                <td>{{ $data->teks }}  <a href="{{ $data->tautan }}" target="_blank">{{ $data->judul_tautan }}</a></td>
                                                                <td width="10%" nowrap>
                                                                    <a href="{{ $data->judul_tautan }}" target="_blank">{!! tgl_indo($data->tgl_upload). ' <br> ' . $data->judul !!} </a>
                                                                </td>
                                                                <td class="padat">{{ $data->tampilkan }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@include('admin.layouts.components.konfirmasi_hapus')

@endsection
