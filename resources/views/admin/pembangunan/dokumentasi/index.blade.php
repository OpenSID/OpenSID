@extends('admin.layouts.index')
@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.jquery_ui')

@section('title')
    <h1>
        Dokumentasi Pembangunan
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Dokumentasi Pembangunan</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            <x-tambah-button :url="'pembangunan_dokumentasi/form-dokumentasi/'.$pembangunan->id" />
            @php
                $listCetakUnduh = [
                    [
                        'url' => "pembangunan_dokumentasi/dialog/{$pembangunan->id}/cetak",
                        'judul' => 'Cetak',
                        'icon' => 'fa fa-print',
                        'modal' => true,
                    ],
                    [
                        'url' => "{$controller}/dialog/{$pembangunan->id}/unduh",
                        'judul' => 'Unduh',
                        'icon' => 'fa fa-download',
                        'modal' => true,
                    ]
                ];
            @endphp

            <x-split-button
                judul="Cetak/Unduh"
                :list="$listCetakUnduh"
                :icon="'fa fa-arrow-circle-down'"
                :type="'bg-purple'"
                :target="true"
            />
            <x-kembali-button judul="Kembali Ke Daftar Pembangunan" url="admin_pembangunan" />
        </div>
        <div class="box-body">
            <h5 class="text-bold">Rincian Dokumentasi Pembangunan</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover tabel-rincian">
                    <tbody>
                        <tr>
                            <td width="20%">Nama Kegiatan</td>
                            <td width="1">:</td>
                            <td>{{ $pembangunan->judul }}</td>
                        </tr>
                        <tr>
                            <td>Sumber Dana</td>
                            <td> : </td>
                            <td>{{ implode(', ', $pembangunan->sumber_dana) }}</td>
                        </tr>
                        <tr>
                            <td>Lokasi Pembangunan</td>
                            <td> : </td>
                            <td>{{ $pembangunan->alamat }}</td>
                        </tr>
                        <tr>
                            <td>Keterangan</td>
                            <td> : </td>
                            <td>{{ $pembangunan->keterangan }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <hr style="margin-bottom: 5px; margin-top: -5px;">
        <div class="box-body">
            {!! form_open(null, 'id="mainform" name="mainform"') !!}
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tabeldata">
                    <thead>
                        <tr>
                            <th class="padat">NO</th>
                            <th class="padat">AKSI</th>
                            <th class="padat">GAMBAR</th>
                            <th>PERSENTASE</th>
                            <th>KETERANGAN</th>
                            <th>TANGGAL REKAM</th>
                        </tr>
                    </thead>
                </table>
            </div>
            </form>
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
                ajax: "{{ ci_route('pembangunan_dokumentasi.datatables-dokumentasi') }}/{{ $pembangunan->id }}",
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
                        data: 'gambar',
                        name: 'gambar',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'persentase',
                        name: 'persentase',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        searchable: true,
                        orderable: true
                    },
                ],
                order: [
                    [3, 'asc']
                ]
            });

            if (ubah == 0) {
                TableData.column(1).visible(false);
            }
        });
    </script>
@endpush
