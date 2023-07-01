@include('admin.layouts.components.asset_validasi')
@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Cetak Layanan Surat
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('surat') }}">Cetak Layanan Surat</a></li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box box-info">
        <div class="box-header with-border">
            <select class="form-control select2 " id="cetak_surat" name="cetak_surat" style="width: 100%;">
                <option selected="selected">--- Cari Judul Surat Yang Akan Dicetak ---</option>
                @foreach ($cetak_surat as $key => $value)
                    <option value="{{ $value->url_surat }}">
                        {{ '[' . (in_array($value->jenis, \App\Models\FormatSurat::RTF) ? 'RTF' : 'TinyMCE') . '] : ' . $value->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        {!! form_open($formAction, 'id="validasi"') !!}
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover tabel-daftar" id="tabeldata">
                    <thead class="bg-gray">
                        <tr>
                            <th class="padat">NO</th>
                            <th class="aksi">AKSI</th>
                            <th>NAMA SURAT</th>
                            <th class="padat">JENIS</th>
                            <th class="padat">KODE / KLASIFIKASI</th>
                            <th class="padat">LAMPIRAN</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#cetak_surat').select2().on('change', function(e) {
                window.location = SITE_URL + 'surat/form/' + this.value;
            });

            var TableData = $('#tabeldata').DataTable({
                ajax: {
                    url: "{{ route('surat.datatables') }}",
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
                        data: 'nama',
                        name: 'nama',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'jenis',
                        name: 'jenis',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'kode_surat',
                        name: 'kode_surat',
                        class: 'padat',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'lampiran',
                        name: 'lampiran',
                        class: 'aksi',
                        searchable: true,
                        orderable: true
                    },
                ],
                order: [
                    [2, 'asc']
                ],
                pageLength: 25,
                createdRow: function(row, data, dataIndex) {
                    if (data.jenis == 2 || data.jenis == 4) {
                        $(row).addClass('select-row');
                    }
                }
            });

            if (ubah == 0) {
                TableData.column(1).visible(false);
                TableData.column(4).visible(false);
            }

        });
    </script>
@endpush
