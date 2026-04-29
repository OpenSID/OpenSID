@extends('admin.layouts.index')

@include('admin.layouts.components.asset_datatables')
@section('title')
    <h1>
        Area
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Area</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div class="col-md-3">
            @include('admin.peta.nav')
        </div>
        <div class="col-md-9">
            <div class="box box-info">
                <div class="box-header with-border">
                    <x-tambah-button :url="'area/form/'. $parent" />
                    <x-hapus-button confirmDelete="true" selectData="true" :url="'area/delete/'. $parent" />
                    @if ($parent_jenis)
                        <x-kembali-button judul="Kembali Ke Area" url="area" />
                    @endif
                </div>
                <div class="box-body">
                    <div class="row mepet">
                        <div class="col-sm-2">
                            <select id="status" class="form-control input-sm select2">
                                <option value="">Pilih Status</option>
                                @foreach (\App\Enums\AktifEnum::all() as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select id="polygon" class="form-control input-sm select2">
                                <option value="">Pilih Jenis</option>
                                @foreach ($polygon as $item)
                                    <option data-children='{!! $item->children->toJson() !!}' value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-3">
                            <select id="subpolygon" class="form-control input-sm select2">
                                <option value="">Pilih Kategori</option>
                                @foreach ($polygon as $item)
                                    <optgroup label="{{ $item->nama }}">
                                        @foreach ($item->children as $child)
                                            <option value="{{ $child->id }}">{{ $child->nama }}</option>
                                        @endforeach
                                    </optgroup>
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
                                    <th class="padat">No</th>
                                    <th class="padat">Aksi</th>
                                    <th>Area</th>
                                    <th style="width:10%">Aktif</th>
                                    <th style="width:15%">Jenis</th>
                                    <th style="width:15%">Kategori</th>
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
@endsection
@push('css')
    <style>
        .select2-results__option[aria-disabled=true] {
            display: none;
        }
    </style>
@endpush
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#status').val(1).trigger('change');

            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('area.datatables') }}?parent={{ $parent }}",
                    data: function(req) {
                        req.status = $('#status').val();
                        req.polygon = $('#polygon').val();
                        req.subpolygon = $('#subpolygon').val();
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
                        data: 'nama',
                        name: 'nama',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'enabled',
                        name: 'enabled',
                        searchable: false,
                        orderable: true
                    },
                    {
                        data: 'ref_polygon',
                        name: 'ref_polygon',
                        label: 'jenis',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'kategori',
                        name: 'kategori',
                        searchable: false,
                        orderable: false
                    },
                ],
                order: [
                    [3, 'asc']
                ]
            });

            if (hapus == 0) {
                TableData.column(0).visible(false);
            }

            if (ubah == 0) {
                TableData.column(2).visible(false);
            }

            $('#polygon').change(function() {
                let _label = $(this).find('option:selected').text()
                $('#subpolygon').val('')
                $('#subpolygon').find('optgroup').prop('disabled', 1)
                if ($(this).val()) {
                    $('#subpolygon').closest('div').show()
                    $('#subpolygon').find(`optgroup[label="${_label}"]`).prop('disabled', 0)
                } else {
                    $('#subpolygon').closest('div').hide()
                }
                $('#btn-add').attr('href', '{{ ci_route('area.form') }}/' + $(this).val())
                $('#subpolygon').select2()
            })

            $('#subpolygon').closest('div').hide()

            $('#subpolygon, #polygon, #status').change(function() {
                TableData.draw()
            })

            if ({{ $parent }} > 0) {
                $('#polygon').val({{ $parent }})
                $('#polygon').trigger('change')
            }
        });
        @include('admin.layouts.components.lock_button')
    </script>
@endpush
