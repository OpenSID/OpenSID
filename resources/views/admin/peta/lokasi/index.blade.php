@extends('admin.layouts.index')

@include('admin.layouts.components.asset_datatables')
@section('title')
    <h1>
        Lokasi
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Lokasi</li>
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
                    @if (can('u'))
                        <a href="{{ ci_route('plan.form', $parent) }}" id="btn-add" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Tambah</a>
                    @endif
                    @if (can('h'))
                        <a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform', '{{ ci_route('plan.delete', $parent) }}')" class="btn btn-social btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i
                                class='fa fa-trash-o'
                            ></i>Hapus</a>
                    @endif
                    @if ($parent_jenis)
                        <a href="{{ ci_route('lokasi.index') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                            <i class="fa fa-arrow-circle-left "></i>Kembali ke Lokasi
                        </a>
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
                        <div class="col-sm-2">
                            <select id="point" class="form-control input-sm select2">
                                <option value="">Pilih Jenis</option>
                                @foreach ($point as $item)
                                    <option data-children='{!! $item->children->toJson() !!}' value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-2">
                            <select id="subpoint" class="form-control input-sm select2">
                                <option value="">Pilih Kategori</option>
                                @foreach ($point as $item)
                                    <optgroup label="{{ $item->nama }}">
                                        @foreach ($item->children as $child)
                                            <option value="{{ $child->id }}">{{ $child->nama }}</option>
                                        @endforeach
                                    </optgroup>
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
                                    <th class="padat">No</th>
                                    <th class="padat">Aksi</th>
                                    <th>Lokasi</th>
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
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ ci_route('plan.datatables') }}?parent={{ $parent }}",
                    data: function(req) {
                        req.status = $('#status').val();
                        req.point = $('#point').val();
                        req.subpoint = $('#subpoint').val();
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
                        data: 'ref_point',
                        name: 'ref_point',
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

            $('#point').change(function() {
                let _label = $(this).find('option:selected').text()
                $('#subpoint').val('')
                $('#subpoint').find('optgroup').prop('disabled', 1)
                if ($(this).val()) {
                    $('#subpoint').closest('div').show()
                    $('#subpoint').find(`optgroup[label="${_label}"]`).prop('disabled', 0)
                } else {
                    $('#subpoint').closest('div').hide()
                }
                $('#btn-add').attr('href', '{{ ci_route('plan.form') }}/' + $(this).val())
                $('#subpoint').select2()
            })

            $('#subpoint').closest('div').hide()

            $('#subpoint, #point, #status').change(function() {
                TableData.draw()
            })

            if ({{ $parent }} > 0) {
                $('#point').val({{ $parent }})
                $('#point').trigger('change')
            }
        });
    </script>
@endpush
