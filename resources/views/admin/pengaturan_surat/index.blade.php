@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Daftar Surat
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Daftar Surat</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box box-info">
        <div class="box-header with-border">
            @if (can('u'))
                <a href="{{ route('surat_master.form') }}" title="Tambah Format Surat"
                    class="btn btn-social bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i
                        class="fa fa-plus"></i> Tambah</a>
            @endif
            @if (can('h'))
                <a href="#confirm-delete" title="Hapus Data"
                    onclick="deleteAllBox('mainform','{{ route('surat_master/deleteAll') }}')"
                    class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i
                        class='fa fa-trash-o'></i> Hapus</a>
            @endif
            @if (can('u', '', true))
                <a href="{{ route('surat_master.perbarui') }}" title="{{ SebutanDesa('Perbarui Surat [Desa]') }}"
                    class="btn btn-social bg-orange btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i
                        class="fa fa-recycle"></i> Perbarui</a>
            @endif
            @if (can('u'))
                <div class="btn-group-vertical radius-3">
                    <a class="btn btn-social btn-sm bg-navy" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i>
                        Impor / Ekspor</a>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href="{{ route('surat_master.impor') }}" class="btn btn-social btn-block btn-sm"
                                data-target="#impor-surat" data-remote="false" data-toggle="modal" data-backdrop="false"
                                data-keyboard="false"><i class="fa fa-upload"></i> Impor Surat TinyMCE</a>
                        </li>
                        <li>
                            <a target="_blank"
                                class="btn btn-social btn-block btn-sm aksi-terpilih" title="Ekspor Surat TinyMCE" onclick="formAction('mainform', '{{ route('surat_master.ekspor') }}'); return false;"><i
                                    class="fa fa-download"></i> Ekspor Surat TinyMCE</a>
                        </li>
                    </ul>
                </div>
                <a href="{{ route('surat_master.pengaturan') }}" title="Pengaturan"
                    class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i
                        class="fa fa-gear"></i> Pengaturan</a>
            @endif
        </div>
        {!! form_open(null, 'id="mainform" name="mainform"') !!}
        <div class="box-header with-border form-inline">
            <div class="row">
                <div class="col-sm-3">
                    <select class="form-control input-sm select2" id="jenis" name="jenis">
                        <option value="">Semua Surat</option>
                        @foreach ($jenisSurat as $key => $value)
                            <option value="{{ $key }}">{{ SebutanDesa($value) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover tabel-daftar" id="tabeldata">
                    <thead class="bg-gray">
                        <tr>
                            <th class="padat"><input type="checkbox" id="checkall" /></th>
                            <th class="padat">NO</th>
                            <th class="aksi">AKSI</th>
                            <th>NAMA SURAT</th>
                            <th class="padat">JENIS</th>
                            <th class="padat">KODE / KLASIFIKASI</th>
                            <th class="padat">LAMPIRAN</th>
                        </tr>
                    </thead>
                </table>
            </div>
            </form>
        </div>
    </div>

    @include('admin.layouts.components.konfirmasi_hapus')
    @include('admin.pengaturan_surat.impor')
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('surat_master.datatables') }}",
                    data: function(d) {
                        d.jenis = $('#jenis').val();
                    }
                },
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
                        data: 'jenis',
                        name: 'jenis',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'kode_surat',
                        name: 'kode_surat',
                        class: 'padat',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'lampiran',
                        name: 'lampiran',
                        class: 'padat',
                        searchable: true,
                        orderable: true
                    },
                ],
                order: [
                    [3, 'asc']
                ],
                pageLength: 25,
                createdRow: function(row, data, dataIndex) {
                    if (data.jenis == 2 || data.jenis == 4) {
                        $(row).addClass('select-row');
                    }
                }
            });

            if (hapus == 0) {
                TableData.column(0).visible(false);
            }

            if (ubah == 0) {
                TableData.column(2).visible(false);
                TableData.column(7).visible(false);
            }

            $('#jenis').on('select2:select', function(e) {
                TableData.draw();
            });
        });
    </script>
@endpush
