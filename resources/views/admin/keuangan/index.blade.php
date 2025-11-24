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
            <x-tambah-button 
                url="#modal-tambah"
                judul="Tambah Template"
                modal="true"
                noTarget="true"
            />

            @if (can('u'))
            <x-btn-button
                url="keuangan_manual/impor_data"
                judul="Impor"
                icon="fa fa-upload"
                type="bg-navy"
            />
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
