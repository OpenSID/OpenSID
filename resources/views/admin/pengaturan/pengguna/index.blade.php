@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Manajemen Pengguna
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Manajemen Pengguna</li>
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
                        <a href="{{ ci_route('man_user/form') }}" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah</a>
                    @endif
                    @if (can('h'))
                        <a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform','{{ ci_route('man_user/delete_all') }}')" class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i
                                class='fa fa-trash-o'
                            ></i> Hapus</a>
                    @endif
                </div>
                @endif
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-2">
                            <select id="status" class="form-control input-sm select2" name="status">
                                <option value="">Semua</option>
                                @foreach ($status as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['nama'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select id="group" class="form-control input-sm select2" name="group">
                                <option value="">Semua</option>
                                @foreach ($user_group as $id => $item)
                                    <option value="{{ $id }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <hr>
                    {!! form_open(null, 'id="mainform" name="mainform"') !!}
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover tabel-daftar" id="tabeldata">
                            <thead class="bg-gray">
                                <tr>
                                    <th class="padat"><input type="checkbox" id="checkall" /></th>
                                    <th class="padat">No</th>
                                    <th class="padat">Aksi</th>
                                    <th>Username</th>
                                    <th>Nama</th>
                                    <th>Staf</th>
                                    <th>Group</th>
                                    <th>Login Terakhir</th>
                                    <th>Tanggal Verifikasi</th>
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
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('man_user.index') }}",
                    data: function(req) {
                        req.status = $('#status').val();
                        req.group = $('#group').val();
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
                        data: 'username',
                        name: 'username',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'pamong_status',
                        name: 'pamong_status',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'user_grup.nama',
                        name: 'userGrup.nama',
                        class: 'padat',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'last_login',
                        name: 'last_login',
                        class: 'padat',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'email_verified_at',
                        name: 'email_verified_at',
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

            $('#status').select2().val(1).trigger('change');

            $('#status').on('select2:select', function(e) {
                TableData.draw();
            });
            $('#group').on('select2:select', function(e) {
                TableData.draw();
            });
        });
    </script>
@endpush
