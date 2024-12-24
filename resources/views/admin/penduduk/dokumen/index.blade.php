@extends('admin.layouts.index')

@include('admin.layouts.components.asset_datatables')
@section('title')
    <h1>
        Dokumen / Kelengkapan Penduduk
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ ci_route('penduduk') }}"> Daftar Penduduk</a></li>
    <li class="active">Dokumen / Kelengkapan Penduduk</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box box-info">
        <div class="box-header with-border">
            @if (can('u'))
                <a
                    href="{{ ci_route('penduduk.dokumen_form', $penduduk->id) }}"
                    title="Tambah"
                    data-remote="false"
                    data-toggle="modal"
                    data-target="#modalBox"
                    data-title="Tambah"
                    class="btn btn-social bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                ><i class='fa fa-plus'></i>Tambah</a>
            @endif
            @if (can('h'))
                <a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform','{{ ci_route('penduduk.delete_dokumen', $penduduk->id) }}')" class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i
                        class='fa fa-trash-o'
                    ></i> Hapus</a>
            @endif
            <a href="{{ preg_match('/bumindes_arsip/i', $_SERVER['HTTP_REFERER']) ? ci_route('bumindes_arsip.clear') : ci_route('penduduk.detail', $penduduk->id) }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i
                    class="fa fa-arrow-circle-left"
                ></i> Kembali ke Halaman
                {{ $_SERVER['HTTP_REFERER'] == ci_route('bumindes_arsip') ? 'Bumindes Arsip' : 'Biodata Penduduk' }}</a>

        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <tbody>
                        <tr>
                            <td nowrap style="padding-top : 10px;padding-bottom : 10px; width:15%;">Nama Penduduk</td>
                            <td nowrap> : {{ $penduduk->nama }}</td>
                        </tr>
                        <tr>
                            <td nowrap style="padding-top : 10px;padding-bottom : 10px;">NIK</td>
                            <td nowrap> : {{ $penduduk->nik }}</td>
                        </tr>
                        <tr>
                            <td nowrap style="padding-top : 10px;padding-bottom : 10px;">Alamat</td>
                            <td nowrap> : {{ $penduduk->alamat_sekarang ?? $penduduk->alamat }} RT/RW :
                                {{ $penduduk->wilayah->rt }}/{{ $penduduk->wilayah->rw }}
                                {{ strtoupper(setting('sebutan_dusun')) }} : {{ $penduduk->wilayah->dusun }} </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            {!! form_open(null, 'id="mainform" name="mainform"') !!}
            <div class="table-responsive">
                @if ($parent_jenis)
                    <h5 class="box-title text-center">Daftar Kategori {{ $parent_jenis }}</h5>
                @endif
                <table class="table table-bordered table-hover" id="tabeldata">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="checkall"></th>
                            <th>No</th>
                            <th>Aksi</th>
                            <th>Nama Dokumen</th>
                            <th>Jenis Dokumen</th>
                            <th>Tanggal Upload</th>
                        </tr>
                    </thead>
                </table>
            </div>
            </form>
        </div>
    </div>

    @include('admin.layouts.components.konfirmasi_hapus')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ ci_route('penduduk.dokumen_datatables') }}?id_pend={{ $penduduk->id }}",
                columns: [{
                        data: 'ceklist',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'DT_RowIndex',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'aksi',
                        class: 'aksi',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'jenis_dokumen',
                        name: 'jenis_dokumen',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'tgl_upload',
                        name: 'tgl_upload',
                        searchable: false,
                        orderable: false
                    },
                ],
                order: [
                    [3, 'asc']
                ]
            });

            if (hapus == 0) {
                TableData.column(0).visible(false);
            }

            if (ubah == 0) {
                TableData.column(2).visible(false);
            }

        });
    </script>
@endpush
