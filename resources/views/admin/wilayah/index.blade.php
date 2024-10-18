@extends('admin.layouts.index')

@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.jquery_ui')
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
                <a href="{{ ci_route('wilayah.form_' . $level, $parent) }}" id="btn-add"
                    class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i
                        class="fa fa-plus"></i> Tambah</a>
            @endif
            @if ($level == 'dusun')
                <a href="{{ ci_route('wilayah.dialog.cetak') }}"
                    class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                    title="Cetak Data" data-remote="false" data-toggle="modal" data-target="#modalBox"
                    data-title="Cetak Data"><i class="fa fa-print "></i> Cetak</a>
                <a href="{{ ci_route('wilayah.dialog.unduh') }}" title="Unduh Data"
                    class="btn btn-social bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                    title="Unduh Data" data-remote="false" data-toggle="modal" data-target="#modalBox"
                    data-title="Unduh Data"><i class="fa fa-download"></i> Unduh</a>
            @else
                <a href='{{ ci_route('wilayah.cetak_' . $level, $parent) }}'
                    class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                    title="Cetak Data" target="_blank"><i class="fa fa-print "></i> Cetak</a>
                <a href='{{ ci_route('wilayah.unduh_' . $level, $parent) }}' title="Unduh Data"
                    class="btn btn-social bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
                    title="Unduh Data" target="_blank"><i class="fa fa-download"></i> Unduh</a>
            @endif

            @if ($parent)
                <a href="{{ $backUrl }}"
                    class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                    <i class="fa fa-arrow-circle-left "></i>Kembali ke Wilayah Administratif
                    {{ $level == 'rt' ? 'RW' : 'Dusun' }}
                </a>
            @endif
        </div>
        @if ($title)
            <div class="box-header">
                <strong>{{ $title }}</strong>
            </div>
        @endif
        <div class="box-body">
            {!! form_open(null, 'id="mainform" name="mainform"') !!}
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tabeldata">
                    <thead>
                        <tr>
                            <th class="padat">#</th>
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
                    <tbody id="dragable">
                    </tbody>
                    <tfoot>
                        <th colspan="5">Total</th>
                        <th></th>
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
            var level = "{{ $level }}";
            const refreshOrder = '{{ $refreshOrder ? true : false }}'

            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('wilayah.datatables') }}?parent={{ $parent }}&level={{ $level }}",
                    data: function(req) {}
                },
                columns: [{
                        data: 'drag-handle',
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
                        data: '{{ $level }}',
                        name: '{{ $level }}',
                        searchable: true,
                        orderable: false
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
                        visible: "{{ in_array($level, ['dusun']) ? 1 : 0 }}"
                    },
                    {
                        data: 'rts_count',
                        name: 'rts_count',
                        defaultContent: '-',
                        searchable: false,
                        orderable: false,
                        visible: "{{ in_array($level, ['dusun', 'rw']) ? 1 : 0 }}"
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
                aaSorting: [],
                createdRow: function(row, data, dataIndex) {
                    if ('{{ $level }}' == 'rw') {
                        if (data.rw == '-') {
                            $(row).find('td').eq(3).replaceWith(
                                '<td colspan="2">Pergunakan RW ini apabila RT berada langsung di bawah {{ $wilayah }}, yaitu tidak ada RW</td>'
                                )
                            $(row).find('td').eq(4).remove()
                        }
                    }

                    $(row).attr('data-id', data.id)
                    $(row).addClass('dragable-handle');
                },
                initComplete: function(settings, json){
                    if(refreshOrder){
                        // trigger update urut jika ada yang masih kosong
                        let order = [];
                        $('tr.dragable-handle').each(function(index, element) {
                            order.push($(this).attr('data-id'))
                        })
                        $.ajax({
                            type: "POST",
                            dataType: "json",
                            url: '{{ ci_route('wilayah.tukar') }}',
                            data: {
                                data: order,
                            },
                            success: function(response) {
                                if (response.status) {
                                    TableData.draw();
                                } else {
                                    TableData.draw();
                                }
                                refreshOrder = false
                            }
                        })
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

            if (level !== 'dusun') {
                if (level !== 'rw') {
                    TableData.column(7).visible(false);
                }
                TableData.column(6).visible(false);
            }

            if (hapus == 0) {
                TableData.column(1).visible(false);
            }

            if (ubah == 0) {
                TableData.column(3).visible(false);
            }
            // harus diletakkan didalam blok ini, jika tidak maka object TableData tidak dikenal
            @include('admin.layouts.components.draggable', ['urlDraggable' => ci_route('wilayah.tukar')])            
        });
    </script>
@endpush
