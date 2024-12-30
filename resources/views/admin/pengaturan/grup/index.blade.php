@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Manajemen Grup Pengguna
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Manajemen Grup Pengguna</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    @include('admin.layouts.components.konfirmasi_hapus')
    <div class="row">
        <div class="col-md-3">
            @include('admin.pengaturan.pengguna.menu')
        </div>
        <div class="col-md-9">
            <div class="box box-info">
                <?php if (can('u')) : ?>
                <div class="box-header with-border">
                    @if (can('u'))
                        <a href="{{ ci_route('grup/form') }}" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah</a>
                    @endif
                    @if (can('h'))
                        <a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform','{{ ci_route('grup/delete') }}')" class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i
                                class='fa fa-trash-o'></i> Hapus</a>
                    @endif
                    @if (can('u'))
                        <div class="btn-group-vertical radius-3">
                            <a class="btn btn-social btn-sm bg-navy" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i>
                                Impor / Ekspor</a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a
                                        href="{{ ci_route('grup.impor') }}"
                                        class="btn btn-social btn-block btn-sm"
                                        data-target="#impor-pengguna"
                                        data-remote="false"
                                        data-toggle="modal"
                                        data-backdrop="false"
                                        data-keyboard="false"
                                    ><i class="fa fa-upload"></i> Impor Pengguna</a>
                                </li>
                                <li>
                                    <a target="_blank" class="btn btn-social btn-block btn-sm aksi-terpilih" title="Ekspor Pengguna" onclick="formAction('mainform', '{{ ci_route('grup.ekspor') }}'); return false;"><i class="fa fa-download"></i> Ekspor Pengguna</a>
                                </li>
                            </ul>
                        </div>
                    @endif
                </div>
                @endif
                <div class="box-body">
                    <div class="row mepet">
                        <div class="col-sm-2">
                            <select id="status" class="form-control input-sm select2" name="status">
                                <option value="">Semua</option>
                                @foreach ($status as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['nama'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select id="jenis" class="form-control input-sm select2" name="jenis">
                                <option value="">Jenis Grup</option>
                                @foreach ($jenis as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <hr class="batas">
                    {!! form_open(null, 'id="mainform" name="mainform"') !!}
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover tabel-daftar" id="tabeldata">
                            <thead class="bg-gray">
                                <tr>
                                    <th class="padat"><input type="checkbox" id="checkall" /></th>
                                    <th class="padat">No</th>
                                    <th class="padat">Aksi</th>
                                    <th>Grup</th>
                                    <th>Jenis</th>
                                    <th>Jumlah Pengguna</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('admin.pengaturan.grup.impor')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('grup.datatables') }}",
                    data: function(req) {
                        req.status = $('#status').val();
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
                        visible: false,
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'users_count',
                        name: 'users_count',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                ],
                order: [
                    [3, 'asc']
                ],
                pageLength: 25,
            });

            if (hapus == 0) {
                TableData.column(0).visible(false);
            }

            // if (ubah == 0) {
            //     TableData.column(2).visible(false);
            // }

            $('#jenis').change(function() {
                TableData.column(4).search($(this).val()).draw()
            })

            $('#status').select2().val(1).trigger('change');

            $('#status').on('select2:select', function(e) {
                TableData.draw();
            });
        });
    </script>
@endpush
