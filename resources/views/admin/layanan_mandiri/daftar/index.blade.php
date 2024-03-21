@extends('admin.layouts.index')
@include('admin.layouts.components.asset_datatables')

@section('title')
    <h1>
        Pendaftar Layanan Mandiri
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Pendaftar Layanan Mandiri</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box box-info">
        <div class="box-header with-border">
            @if (can('u'))
                <a href="{{ ci_route('mandiri.ajax_pin') }}" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Buat PIN Warga" class="btn btn-social btn-success btn-sm"><i class="fa fa-plus"></i> Tambah Pengguna</a>
            @endif
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tabeldata">
                    <thead>
                        <tr>
                            <th class="padat">NO</th>
                            <th class="aksi">AKSI</th>
                            <th class="padat">NIK</th>
                            <th>Nama Penduduk</th>
                            <th class="padat">Tanggal Buat</th>
                            <th class="padat">Login Terakhir</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    @include('admin.layouts.components.konfirmasi_hapus')

    @if (session('info'))
        @php
            $info = session('info');
        @endphp
        <div
            class="modal fade"
            id="pinBox"
            role="dialog"
            aria-labelledby="myModalLabel"
            aria-hidden="true"
            data-backdrop="false"
            data-keyboard="false"
        >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header btn-info">
                        <button type='button' class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title' id='myModalLabel">PIN Warga ({{ $info['nama'] }})</h4>
                    </div>
                    <form action="{{ ci_route('mandiri.kirim', $info['id_pend']) }}" method="post" id="validasi" target="_blank">
                        <input type="hidden" id="pin" name="pin" value="{{ $info['pin'] }}">
                        <div class="modal-body">
                            Berikut adalah kode pin yang baru saja di hasilkan, silakan dicatat atau di ingat dengan baik,
                            kode pin ini sangat rahasia, dan hanya bisa dilihat sekali ini lalu setelah itu hanya bisa di
                            reset saja.<br />

                            <h4>Kode PIN : {{ $info['pin'] }}</h4>

                            @if (session('notif_kirim_verifikasi'))
                                @php
                                    $kirim_verifikasi = session('notif_kirim_verifikasi');
                                @endphp
                                <div class="callout callout-{{ $kirim_verifikasi['status'] == 1 ? 'success' : 'danger' }}" style="margin-top: 30px;">
                                    <p>{{ $kirim_verifikasi['pesan'] }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-social btn-warning btn-sm" data-dismiss="modal"><i class="fa fa-sign-out"></i> Tutup</button>
                            @if (cek_koneksi_internet() && $info['pin'] && $info['telepon'])
                                <button type="submit" class="btn btn-social btn-success btn-sm"><i class="fa fa-whatsapp"></i> Kirim</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            @if (session('info'))
                $('#pinBox').modal('show');
            @endif
            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ ci_route('mandiri.datatables') }}",
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
                        data: 'penduduk.nik',
                        name: 'penduduk.nik',
                        defaultContent: '',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'penduduk.nama',
                        name: 'penduduk.nama',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'tanggal_buat',
                        name: 'tanggal_buat',
                        searchable: false,
                        orderable: true,
                        class: 'padat'
                    },
                    {
                        data: 'last_login',
                        name: 'last_login',
                        searchable: false,
                        orderable: true,
                        class: 'padat'
                    },
                ],
                order: [
                    [4, 'desc']
                ],
                createdRow: function(row, data, dataIndex) {
                    if (!data.penduduk.telepon) {
                        $(row).addClass('select-row')
                    }
                },
            });

            if (ubah == 0 && hapus == 0) {
                TableData.column(1).visible(false);
            }
        });
    </script>
@endpush
