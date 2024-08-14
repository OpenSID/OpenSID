@include('admin.layouts.components.asset_validasi')
@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Cetak Layanan Surat
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('surat') }}">Cetak Layanan Surat</a></li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box box-info">
        <div class="box-header with-border">
            <select id="cetak-surat" name="cetak_surat" class="form-control input-sm" data-placeholder="--- Cari Judul Surat Yang Akan Dicetak ---"></select>
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
            $('#cetak-surat').select2({
                ajax: {
                    url: SITE_URL + 'surat/apidaftarsurat',
                    dataType: 'json',
                    data: function(params) {
                        return {
                            q: params.term || '',
                            page: params.page || 1,
                        };
                    },
                    cache: true
                },
                placeholder: function() {
                    $(this).data('placeholder');
                },
                minimumInputLength: 0,
                allowClear: true,
                escapeMarkup: function(markup) {
                    return markup;
                },
            });

            $('#cetak-surat').change(function(e) {
                window.location = SITE_URL + 'surat/form/' + this.value;
            });

            var TableData = $('#tabeldata').DataTable({
                ajax: {
                    url: "{{ ci_route('surat.datatables') }}",
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
            }

        });
    </script>
@endpush
