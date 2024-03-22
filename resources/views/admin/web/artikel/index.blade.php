@extends('admin.layouts.index')

@include('admin.layouts.components.asset_datatables')
@section('title')
    <h1>
        Artikel {{ $kategori }}
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Artikel</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div class="col-md-3">
            @include('admin.web.artikel.nav')
        </div>
        <div class="col-md-9">
            <div class="box box-info">
                <div class="box-header with-border">
                    @if (can('u') && !in_array($cat, ['0', '-1', null]))
                        <a href="{{ ci_route('web.form', $cat) }}" id="btn-add" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah
                            {{ $kategori ? $kategori : (in_array($cat, ['statis', 'agenda', 'keuangan']) ? ucfirst($cat) : '') }}</a>
                    @endif
                    @if (can('h'))
                        <a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '{{ ci_route('web.delete') }}')" class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i
                                class='fa fa-trash-o'
                            ></i> Hapus Data Terpilih</a>
                        @if (!in_array($cat, ['0', '-1', 'statis', 'agenda', 'keuangan']))
                            <a href="#confirm-delete" title="Hapus Kategori {{ $kategori }}" onclick="deleteAllBox('mainform', '{{ ci_route('web.hapus', $cat) }}')" class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i
                                    class='fa fa-trash-o'
                                ></i> Hapus Artikel Kategori {{ $kategori }}</a>
                        @endif
                    @endif
                    @if ($cat == 'statis')
                        <a href="{{ ci_route('web.reset', $cat) }}" class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Reset Hit" data-toggle="modal" data-target="#reset-hit" data-remote="false"><i
                                class="fa fa-spinner"></i> Reset Hit</a>
                    @endif
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-2">
                            <select id="status" class="form-control input-sm select2">
                                <option value="">Pilih Status</option>
                                @foreach ($status as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <hr>
                    {!! form_open(null, 'id="mainform" name="mainform"') !!}
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="tabeldata">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="checkall" /></th>
                                    <th class="padat">NO</th>
                                    <th class="padat">AKSI</th>
                                    <th nowrap>JUDUL</th>
                                    <th nowrap>HIT</th>
                                    <th width="15%" nowrap>DIPOSTING PADA</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('admin.layouts.components.konfirmasi_hapus')
    @includeWhen($cat == 'statis', 'admin.web.artikel.reset_hit_modal')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('web.datatables') }}?cat={{ $cat }}",
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
                        data: 'judul',
                        name: 'judul',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'hit',
                        name: 'hit',
                        searchable: false,
                        orderable: true,
                        class: 'padat'
                    },
                    {
                        data: 'tgl_upload',
                        name: 'tgl_upload',
                        searchable: false,
                        orderable: true
                    },
                ],
                order: [
                    [5, 'desc']
                ],
            });

            if (hapus == 0) {
                TableData.column(0).visible(false);
            }

            if (ubah == 0) {
                TableData.column(2).visible(false);
            }

            $('#status').change(function() {
                TableData.draw()
            })
        });
    </script>
@endpush
