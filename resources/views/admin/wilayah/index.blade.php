@extends('admin.layouts.index')

@include('admin.layouts.components.asset_datatables')
@section('title')
    <h1>
        Wilayah Administratif {{ $wilayah }}
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Wilayah Administratif {{ $wilayah }}</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box box-info">
        <div class="box-header with-border">
            @if (can('u'))
                <a href="{{ route('wilayah.form_' . $level, $parent) }}" id="btn-add" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah</a>
            @endif
            @if ($level == 'dusun')
                <a
                    href="{{ route('wilayah.dialog.cetak') }}"
                    class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                    title="Cetak Data"
                    data-remote="false"
                    data-toggle="modal"
                    data-target="#modalBox"
                    data-title="Cetak Data"
                ><i class="fa fa-print "></i> Cetak</a>
                <a
                    href="{{ route('wilayah.dialog.unduh') }}"
                    title="Unduh Data"
                    class="btn btn-social bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                    title="Unduh Data"
                    data-remote="false"
                    data-toggle="modal"
                    data-target="#modalBox"
                    data-title="Unduh Data"
                ><i class="fa fa-download"></i> Unduh</a>
            @else
                <a href='{{ route('wilayah.cetak_' . $level, $parent) }}' class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak Data" target="_blank"><i class="fa fa-print "></i> Cetak</a>
                <a href='{{ route('wilayah.unduh_' . $level, $parent) }}' title="Unduh Data" class="btn btn-social bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh Data" target="_blank"><i class="fa fa-download"></i> Unduh</a>
            @endif

            @if ($parent)
                <a href="{{ $backUrl }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                    <i class="fa fa-arrow-circle-left "></i>Kembali ke Wilayah Administratif {{ $level == 'rt' ? 'RW' : 'Dusun' }}
                </a>
            @endif
        </div>
        <div class="box-header"><strong>{{ $title }}</strong></div>
        <div class="box-body">
            {!! form_open(null, 'id="mainform" name="mainform"') !!}
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tabeldata">
                    <thead>
                        <tr>
                            <th class="padat">No</th>
                            <th class="padat">Aksi</th>
                            <th>{{ $wilayah }}</th>
                            <th>{{ $jabatan }} {{ $wilayah }}</th>
                            <th>NIK {{ $jabatan }} {{ $wilayah }}</th>
                            <th style="width:5%">RW</th>
                            <th style="width:5%">RT</th>
                            <th style="width:5%">KK</th>
                            <th style="width:5%">L+P</th>
                            <th style="width:5%">L</th>
                            <th style="width:5%">P</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <th colspan="5">Total</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tfoot>
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
                ajax: {
                    url: "{{ route('wilayah.datatables') }}?parent={{ $parent }}&level={{ $level }}",
                    data: function(req) {}
                },
                columns: [{
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
                        data: '{{ $level }}',
                        name: '{{ $level }}',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'kepala',
                        name: 'kepala',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'nik_kepala',
                        name: 'nik_kepala',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'rws_count',
                        name: 'rws_count',
                        defaultContent: '-',
                        searchable: false,
                        orderable: false,
                        visible: {{ in_array($level, ['dusun']) ? 1 : 0 }}
                    },
                    {
                        data: 'rts_count',
                        name: 'rts_count',
                        defaultContent: '-',
                        searchable: false,
                        orderable: false,
                        visible: {{ in_array($level, ['dusun', 'rw']) ? 1 : 0 }}
                    },
                    {
                        data: 'keluarga_aktif_count',
                        name: 'keluarga_aktif_count',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'penduduk_count',
                        name: 'penduduk_count',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'penduduk_pria_count',
                        name: 'penduduk_pria_count',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'penduduk_wanita_count',
                        name: 'penduduk_wanita_count',
                        searchable: false,
                        orderable: false
                    },
                ],
                order: [
                    [3, 'asc']
                ],
                createdRow: function(row, data, dataIndex) {
                    if ('{{ $level }}' == 'rw') {
                        if (data.rw == '-') {
                            $(row).find('td').eq(3).replaceWith('<td colspan="2">Pergunakan RW ini apabila RT berada langsung di bawah {{ $wilayah }}, yaitu tidak ada RW</td>')
                            $(row).find('td').eq(4).remove()
                        }
                    }
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

                                $.post(SITE_URL + 'wilayah/tukar', _dataKirim, function(data) {
                                    if (data.status) {
                                        TableData.draw();
                                    }
                                }, 'json');
                            });

                        }
                    }

                },
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();

                    // Iterasi melalui setiap kolom dan menghitung total
                    for (var i = 5; i < api.columns().count(); i++) {
                        var columnData = api.column(i, {
                            page: 'current'
                        }).data();

                        // Menghitung total untuk kolom saat ini
                        var total = columnData.reduce(function(a, b) {
                            return a + parseFloat($(b).text());
                        }, 0);

                        // Menetapkan total ke elemen di bagian footer untuk kolom saat ini
                        $(api.column(i).footer()).html(total);
                    }
                }
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
