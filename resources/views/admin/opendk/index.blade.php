@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        {{ strtoupper($judul) }}
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Daftar Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    @include('admin.layouts.components.konfirmasi_hapus')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    @if (can('u'))
                        <a
                            href="{{ ci_route($routePath . '.form') }}"
                            title="Tambah"
                            class="btn btn-social bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                            data-target="#modalBox"
                            data-remote="false"
                            data-toggle="modal"
                            data-backdrop="false"
                            data-keyboard="false"
                            data-title="Tambah {{ $judul }}"
                        ><i class="fa fa-plus"></i> Tambah</a>
                    @endif
                    @if (can('h'))
                        <a href="#confirm-delete" title="Hapus" onclick="deleteAllBox('mainform','{{ ci_route($routePath . '.delete') }}')" class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i
                                class='fa fa-trash-o'
                            ></i> Hapus
                        </a>
                    @endif
                    @if (can('u'))
                        @if (setting('sinkronisasi_opendk'))
                            <a href="#" title="Kirim Ke OpenDK" id="kirim" onclick="formAction('mainform','{{ ci_route($routePath . '.kirim') }}')"
                                class="btn btn-social btn-primary btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block aksi-terpilih" title="Kirim Ke OpenDK"
                            ><i class="fa fa-random"></i> Kirim Ke OpenDK</a>
                        @else
                            <a href="#" title="API Key Belum Ditentukan" class="btn btn-social btn-primary btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" disabled><i class="fa fa-random"></i> Kirim Ke OpenDK</a>
                        @endif
                    @endif

                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-2">
                            <select class="form-control input-sm select2" id="filter-tahun">
                                <option value="">Semua Tahun</option>
                                @foreach ($tahun as $thn)
                                    <option value="{{ $thn->tahun }}">{{ $thn->tahun }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <hr>
                    {!! form_open(null, 'id="mainform" name="mainform"') !!}
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover tabel-daftar" id="tabel-data">
                            <thead class="bg-gray">
                                <tr>
                                    <th><input type="checkbox" id="checkall" /></th>
                                    <th>No</th>
                                    <th>Aksi</th>
                                    <th>Judul</th>
                                    <th>{{ $kolom }}</th>
                                    <th>Tahun</th>
                                    <th>Tanggal Upload</th>
                                    <th>Tanggal Kirim</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let TableData = $('#tabel-data').DataTable({
                'processing': true,
                'serverSide': true,
                'autoWidth': false,
                'pageLength': 10,
                'order': [
                    [5, 'desc'],
                    [4, 'desc']
                ],
                'columnDefs': [{
                        'orderable': false,
                        'targets': [0, 1, 2]
                    },
                    {
                        'className': 'padat',
                        'targets': [0, 1, 4, 5, 6, 7]
                    },
                    {
                        'className': 'aksi',
                        'targets': [2]
                    },
                ],
                'ajax': {
                    'url': "{{ ci_route($routePath . '.datatables') }}",
                    'method': 'POST',
                    'data': function(d) {}
                },
                'columns': [{
                        'data': 'ceklist'
                    },
                    {
                        data: 'DT_RowIndex',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        'data': 'aksi'
                    },
                    {
                        'data': 'judul'
                    },
                    {
                        'data': 'semester'
                    },
                    {
                        'data': 'tahun'
                    },
                    {
                        'data': 'updated_at'
                    },
                    {
                        'data': 'kirim'
                    },
                ],
            });

            $('#kirim').on('click', function() {
                $('#loading').modal({
                    backdrop: 'static',
                    keyboard: false
                }).show();
            });


            if (hapus == 0) {
                TableData.column(0).visible(false);
            }

            if (ubah == 0) {
                TableData.column(2).visible(false);
            }
            $('#filter-tahun').change(function() {
                TableData.column(5).search($(this).val()).draw()
            })
        });
    </script>
@endpush
