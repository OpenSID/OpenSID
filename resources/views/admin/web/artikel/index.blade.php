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
                    @if (!in_array($cat, ['0', '-1', null]))
                    <x-tambah-button 
                        :judul="'Tambah ' . ($kategori ? $kategori : (in_array($cat, ['statis', 'agenda', 'keuangan']) ? ucfirst($cat) : ''))" 
                        :url="'web/form/' . $cat" 
                    />
                    @endif
                    <x-hapus-button judul="Hapus Data Terpilih" confirmDelete="true" selectData="true" :url="'web/delete'" />
                    @if (!in_array($cat, ['0', '-1', 'statis', 'agenda', 'keuangan']))
                        <x-hapus-button :judul="'Hapus Artikel Kategori '. $kategori" :confirmDelete="true" :selectData="false" visible="true" :url="'web/hapus/'.$cat" />
                    @endif
                    @if ($cat == 'statis')
                        <x-btn-button judul="Reset Hit" icon="fa fa-spinner" modal='true' modalTarget="reset-hit" type="bg-purple" :url="'web/reset/'. $cat" />
                    @endif
                </div>
                <div class="box-body">
                    <div class="row mepet">
                        <div class="col-sm-2">
                            <select id="status" class="form-control input-sm select2">
                                <option value="">Pilih Status</option>
                                @foreach ($status as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
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
            $('#status').val(1).trigger('change');

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
