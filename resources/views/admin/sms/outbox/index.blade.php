@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        SMS
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">SMS</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="row">
        <div class="col-md-3">
            @include('admin.sms.navigasi')
        </div>
        <div class="col-md-9">
            <div class="box box-info">
                <div class="box-header with-border">
                    @if (can('u'))
                        <a href="{{ ci_route('sms.form.4') }}" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Tulis Pesan Baru"class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i
                                class='fa fa-plus'></i>Tulis Pesan Baru</a>
                    @endif
                    @if (can('u'))
                        <a href="{{ ci_route('sms.broadcast.2') }}" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Tulis Pesan Broadcast" class="btn btn-social bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i
                                class='fa fa-weixin'
                            ></i> Tulis Pesan Ke Banyak</a>
                    @endif
                </div>
                <div class="box-body">
                    {!! form_open(null, 'id="mainform" name="mainform"') !!}
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="tabeldata">
                            <thead>
                                <tr>
                                    <th class="padat">No</th>
                                    <th>Nama</th>
                                    <th>Nomor HP</th>
                                    <th>Isi Pesan</th>
                                    <th>Diterima</th>
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
@push('scripts')
    <script>
        $(document).ready(function() {
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ ci_route('sms.outbox.datatables') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        class: 'padat',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'nama',
                        name: 'penduduk.nama',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'DestinationNumber',
                        name: 'DestinationNumber',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'TextDecoded',
                        name: 'TextDecoded',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'SendingDateTime',
                        name: 'SendingDateTime',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'penduduk.nama',
                        name: 'penduduk.nama',
                        searchable: true,
                        orderable: false,
                        visible: false,
                        defaultContent: ''
                    },
                    {
                        data: 'kontak.nama',
                        name: 'kontak.nama',
                        searchable: true,
                        orderable: false,
                        visible: false,
                        defaultContent: ''
                    },
                ],
                order: [
                    [2, 'asc']
                ]
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
