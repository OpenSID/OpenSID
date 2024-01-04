@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@php $tipe = str_replace('_master', '', $ci->controller); @endphp

@section('title')
    <h1>
        Kategori {{ ucfirst($tipe) }}
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="<?= site_url($tipe) ?>"> Daftar <?= ucfirst($tipe) ?></a></li>
    <li class="active">Kategori {{ ucfirst($tipe) }}</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    @include('admin.layouts.components.konfirmasi_hapus')

    <div class="box box-info">
        <div class="box-header with-border">
            @if (can('u'))
                <a href="<?= site_url("{$ci->controller}/form") ?>" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah</a>
            @endif
            @if (can('h'))
                <a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform','<?= site_url("{$ci->controller}/delete_all") ?>')" class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i
                        class='fa fa-trash-o'
                    ></i> Hapus</a>
            @endif
            <a href="<?= site_url($tipe) ?>" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar <?= ucfirst($tipe) ?></a>
        </div>
        <div class="box-body">
            {!! form_open(null, 'id="mainform" name="mainform"') !!}
            <div class="table-responsive">
                <table class="table table-bordered table-hover tabel-daftar" id="tabeldata">
                    <thead class="bg-gray">
                        <tr>
                            <th class="padat"><input type="checkbox" id="checkall" /></th>
                            <th class="padat">No</th>
                            <th class="aksi">Aksi</th>
                            <th class="padat">Kategori {{ ucfirst($tipe) }}</th>
                            <th>Deskripsi {{ ucfirst($tipe) }}</th>
                            <th class="padat">Jumlah {{ ucfirst($tipe) }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ $ci->controller }}",
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
                        data: 'kelompok',
                        name: 'kelompok',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'deskripsi',
                        name: 'deskripsi',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                ],
                order: [
                    [3, 'asc']
                ],
                pageLength: 25,
                createdRow: function(row, data, dataIndex) {
                    if (data.jenis == 0 || data.jenis == 1) {
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
        });
    </script>
@endpush
