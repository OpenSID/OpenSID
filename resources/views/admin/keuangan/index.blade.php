@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Keuangan
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Keuangan</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box box-info">
        <div class="box-header with-border">
            @if (can('u'))
                <a href="#modal-tambah" data-toggle="modal" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah Template</a>
                <a href="{{ ci_route('keuangan_manual.impor_data') }}" class="btn btn-social bg-navy btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Impor Data Keuangan"><i class="fa fa-upload"></i>Impor</a>
            @endif
        </div>
        <div class="box-body">
            <div class="row mepet">
                <div class="col-sm-3">
                    <select id="jenis_anggaran" name="jenis_anggaran" class="form-control input-sm select2">
                        <option value="">Pilih Jenis Anggaran</option>
                        @foreach ($jenis_anggaran as $item)
                            <optgroup label="{{ $item->uraian }}">
                                <option @selected($filter['jenis'] == $item->uuid) value="{{ $item->uuid }}">{{ "{$item->uuid} {$item->uraian}" }}</option>
                                @foreach ($item->children as $children)
                                    <option value="{{ $children->uuid }}">{{ "{$children->uuid} {$children->uraian}" }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <select id="tahun_anggaran" name="tahun_anggaran" class="form-control input-sm  select2">
                        <option value="">Pilih Tahun Anggaran</option>
                        @foreach ($tahun_anggaran as $item)
                            <option @selected($filter['tahun'] == $item->tahun) value="{{ $item->tahun }}">{{ $item->tahun }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <hr class="batas">
            {!! form_open(null, 'id="mainform" name="mainform"') !!}
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tabeldata">
                    <thead>
                        <tr>
                            <th class="padat">NO</th>
                            <th class="padat">AKSI</th>
                            <th class="padat">KODE REKENING</th>
                            <th>URAIAN</th>
                            <th>ANGGARAN</th>
                            <th>REALISASI</th>
                        </tr>
                    </thead>
                    <tbody id="dragable">
                    </tbody>
                </table>
            </div>
            </form>
        </div>
    </div>
    @include('admin.keuangan.form')
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                orderable: true,
                searching: true,
                paging: false,
                ajax: {
                    url: "{{ site_url('keuangan_manual/datatables') }}",
                    data: function(request) {
                        request.jenis_anggaran = $('#jenis_anggaran').val();
                        request.tahun_anggaran = $('#tahun_anggaran').val();
                    },
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
                        data: 'kode_menjorok',
                        name: 'template.uuid',
                        class: 'text-nowrap',
                    },
                    {
                        data: 'uraian_menjorok',
                        name: 'template.uraian',
                    },
                    {
                        data: 'anggaran',
                        name: 'anggaran',
                        class: 'text-nowrap'
                    },
                    {
                        data: 'realisasi',
                        name: 'realisasi',
                        class: 'text-nowrap'
                    },
                ],
                order: [
                    [2, 'asc']
                ],
            });

            if (hapus == 0) {
                TableData.column(0).visible(false);
            }

            if (ubah == 0) {
                TableData.column(2).visible(false);
            }

            $('#jenis_anggaran, #tahun_anggaran').change(function() {
                TableData.draw();
            })
        });
    </script>
@endpush
