@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Pengaturan Rentang Umur
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('statistik.penduduk.13') }}">Statistik Kependudukan</a></li>
    <li class="active">Pengaturan Rentang Umur</li>
@endsection

@push('css')
    <style type="text/css">
        .disabled {
            pointer-events: none;
            cursor: default;
        }
    </style>
@endpush

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div id="sidebar" class="col-sm-4">
            @include('admin.statistik.side')
        </div>
        <div id="content" class="col-sm-8">
            <div class="box box-info">
                <div class="box-header with-border">
                    @if (can('u'))
                        <a
                            href="{{ ci_route('statistik.rentang_umur.form') }}"
                            class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                            title="Tambah Rentang Umur"
                            data-remote="false"
                            data-toggle="modal"
                            data-target="#modalBox"
                            data-title="Tambah Rentang Umur"
                        ><i class="fa fa-plus"></i> Tambah</a>
                    @endif
                    @if (can('h'))
                        <a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '{{ route('statistik.rentang_umur.delete_all') }}')" class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i
                                class='fa fa-trash-o'
                            ></i> Hapus</a>
                    @endif
                    <a href="{{ ci_route('statistik.penduduk.13') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                        <i class="fa fa-arrow-circle-left "></i>Kembali ke Data Statistik
                    </a>
                </div>
                <div class="box-body">
                    {!! form_open(null, 'id="mainform" name="mainform"') !!}
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="tabeldata">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="checkall" /></th>
                                    <th class="padat">NO</th>
                                    <th class="padat">AKSI</th>
                                    <th>RENTANG</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    </form>
                </div>
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
                    ajax: "{{ ci_route('statistik.rentang_umur.datatables') }}",
                    columns: [{
                            data: 'ceklist',
                            class: 'padat'
                        },
                        {
                            data: 'DT_RowIndex',
                            class: 'padat'
                        },
                        {
                            data: 'aksi',
                            class: 'aksi'
                        },
                        {
                            data: 'nama',
                            name: 'nama'
                        },
                    ],
                    searching: false,
                    ordering: false,
                    order: []
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
