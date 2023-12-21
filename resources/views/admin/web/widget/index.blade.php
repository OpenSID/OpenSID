@include('admin.pengaturan_surat.asset_tinymce')
@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Widget
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Widget</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <form id="mainform" name="mainform" method="post">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        @if (can('u'))
                            <a href="{{ route('web_widget.form') }}" class="btn btn-social btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Tambah Widget">
                                <i class="fa fa-plus"></i> Tambah
                            </a>
                        @endif
                        @if (can('u'))
                            <a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '{{ route('web_widget.delete_all') }}')"
                                class="btn btn-social btn-danger btn-sm
                        visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block
                        hapus-terpilih"
                            ><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
                        @endif
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                    <form id="mainform" name="mainform" method="post">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <select name="status" id="status" class="form-control input-sm select2">
                                                    <option value="">Semua</option>
                                                    <option value="1">Aktif</option>
                                                    <option value="2">Tidak Aktif</option>
                                                </select>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover" id="tabeldata">
                                                        <thead class="bg-gray disabled color-palette">
                                                            <tr>
                                                                <th><input type="checkbox" id="checkall" /></th>
                                                                <th>No</th>
                                                                <th>Aksi</th>
                                                                <th width="20%">Judul</th>
                                                                <th nowrap>Jenis Widget</th>
                                                                <th>Aktif</th>
                                                                <th>Isi</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </form>

    @include('admin.layouts.components.konfirmasi_hapus')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('web_widget.datatables') }}",
                    data: function(req) {}
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
                        data: 'judul',
                        name: 'judul',
                        searchable: true,
                        orderable: false,
                    },
                    {
                        data: 'jenis_widget',
                        name: 'jenis_widget',
                        searchable: true,
                        orderable: false
                    },
                    {
                        data: 'enabled',
                        name: 'enabled',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'isi',
                        name: 'isi',
                        searchable: true,
                        orderable: false
                    },
                ],
                order: [
                    [3, 'asc']
                ],
                createdRow: function(row, data, dataIndex) {
                    $(row).attr('data-urut', data.urut ?? 0)
                    $(row).attr('data-id', data.id)
                },
                drawCallback: function(settings) {
                    if (ubah == 1) {

                        var api = this.api();

                        if (api.rows().count()) {
                            var lastRowIndex = api.rows().count() - 1;

                            api.row(lastRowIndex).node().querySelector('i.fa-arrow-down').parentNode.setAttribute("disabled", true)
                            api.row(0).node().querySelector('i.fa-arrow-up').parentNode.setAttribute("disabled", true)

                            $('a.pindahkan').click(function() {
                                const _trAsal = $(this).closest('tr');
                                const _arah = $(this).data('arah');
                                let _urutAsal = _trAsal.attr('data-urut');
                                let _trTujuan = (_arah == 'atas') ? _trAsal.prev() : _trAsal.next();
                                let _urutTujuan = _trTujuan.attr('data-urut');

                                if ((_arah == 'atas' && _urutAsal <= _urutTujuan) || (_arah == 'bawah' && _urutAsal >= _urutTujuan)) {
                                    if (_urutTujuan == 0) {
                                        _urutAsal = (_arah == 'atas') ? 1 : 2;
                                        _urutTujuan = (_arah == 'atas') ? 2 : 1;
                                    } else {
                                        const _tmpUrut = _urutAsal;
                                        _urutAsal = _urutTujuan;
                                        _urutTujuan = _tmpUrut;
                                    }
                                } else {
                                    const _tmpUrut = _urutAsal;
                                    _urutAsal = _urutTujuan;
                                    _urutTujuan = _tmpUrut;
                                }

                                const _dataKirim = {
                                    data: [{
                                        id: _trAsal.attr('data-id'),
                                        urut: _urutAsal
                                    }, {
                                        id: _trTujuan.attr('data-id'),
                                        urut: _urutTujuan
                                    }]
                                };

                                $.post(SITE_URL + 'web_widget/tukar', _dataKirim, function(data) {
                                    if (data.status) {
                                        TableData.draw();
                                    }
                                }, 'json');
                            });

                        }
                    }
                }
            });

            $('#status').change(function() {
                TableData.column(5).search($(this).val()).draw()
            })

            if (hapus == 0) {
                TableData.column(0).visible(false);
            }

            if (ubah == 0) {
                TableData.column(2).visible(false);
            }
        });
    </script>
@endpush
