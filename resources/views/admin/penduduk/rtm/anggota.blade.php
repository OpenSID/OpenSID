@extends('admin.layouts.index')

@include('admin.layouts.components.asset_datatables')
@section('title')
<h1>
    Data Anggota {{ $module_name }}
</h1>
@endsection

@section('breadcrumb')
<li><a href="{{ ci_route('rtm') }}">Data {{ $module_name }}</a></li>
<li class="active">Data Anggota {{ $module_name }}</li>
@endsection

@section('content')
@include('admin.layouts.components.notifikasi')
<div class="box box-info">
    <div class="box-header with-border">
        @if ((string) $kepala_kk['status_dasar'] === '1')
        <x-tambah-button :url="'rtm/ajax_add_anggota/' . $kk" modal="true" />
        @endif
        <x-hapus-button confirmDelete="true" selectData="true" :url="'rtm/delete_all_anggota/' . $kk" />
        <x-btn-button judul="Kartu Rumah Tangga" icon="fa fa-book" type="bg-purple" :url="'rtm/kartu_rtm/' . $kk" />
        <x-kembali-button judul="Kembali Ke Daftar Rumah Tangga" url="rtm" />
    </div>

    <div class="box-body">
        <h5><b>Rincian Keluarga</b></h5>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover tabel-rincian">
                <tbody>
                    <tr>
                        <td width="20%">Nomor Rumah Tangga (RT)</td>
                        <td width="1%">:</td>
                        <td>{{ $kepala_kk['no_kk'] }}</td>
                    </tr>
                    <tr>
                        <td>Kepala Rumah Tangga</td>
                        <td>:</td>
                        <td>{{ $kepala_kk['nama'] }}</td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>:</td>
                        <td>{{ $kepala_kk['alamat_wilayah'] }}</td>
                    </tr>
                    <tr>
                        <td>Jumlah KK</td>
                        <td>:</td>
                        <td>{{ $kepala_kk['jumlah_kk'] }} </td>
                    </tr>
                    <tr>
                        <td>Jumlah Anggota</td>
                        <td>:</td>
                        <td>{{ count($main) }} </td>
                    </tr>
                    <tr>
                        <td>BDT</td>
                        <td>:</td>
                        <td>{{ $kepala_kk['bdt'] ?? '-' }} </td>
                    </tr>
                    <tr>
                        <td>
                            @if ($program['programkerja'])
                            {!! anchor("peserta_bantuan/peserta/3/{$kepala_kk['no_kk']}", 'Program Bantuan', 'target="_blank"') !!}
                            @else
                            Program Bantuan
                            @endif
                        </td>
                        <td>:</td>
                        <td>
                            @if ($program['programkerja'])
                            @foreach ($program['programkerja'] as $item)
                            {!! anchor("peserta_bantuan/data_peserta/{$item['id']}/{$item['program_id']}", '<span class="label label-success">' . $item['bantuan']['nama'] . '</span>&nbsp;', 'target="_blank"') !!}
                            @endforeach
                            @else
                            -
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <hr style="margin:0;">
    <div class="box-body">
        <h5><b>Daftar Anggota</b></h5>
        {!! form_open(null, 'id="mainform" name="mainform"') !!}
        <div class="table-responsive">
            <table class="table table-bordered table-hover tabel-daftar" id="tabeldata">
                <thead class="bg-gray disabled color-palette">
                    <tr>
                        @if (can('h'))
                        <th><input type="checkbox" id="checkall" /></th>
                        @endif
                        <th>No</th>
                        @if (can('u'))
                        <th>Aksi</th>
                        @endif
                        <th>NIK</th>
                        <th>Nomor KK</th>
                        <th width="25%">Nama</th>
                        <th>Jenis Kelamin</th>
                        <th width="35%">Alamat</th>
                        <th>Hubungan</th>
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
        var table = $('#tabeldata').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: "{{ ci_route('rtm.datatables_anggota', $kk) }}",
            order: [[8, 'desc']],
            columns: [{
                    data: 'ceklist',
                    orderable: false,
                    searchable: false,
                    className: 'padat'
                },{
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    className: 'padat'
                },{
                    data: 'aksi',
                    orderable: false,
                    searchable: false,
                    className: 'aksi'
                },{
                    data: 'nik',
                    name: 'nik'
                },
                {
                    data: 'keluarga.no_kk',
                    name: 'keluarga.no_kk'
                },
                {
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'sex',
                    name: 'sex',
                    className: 'padat'
                },
                {
                    data: 'alamat_wilayah',
                    name: 'alamat_wilayah'
                },
                {
                    data: 'rtm_level',
                    name: 'rtm_level'
                },
            ],
        });

        if (hapus == 0) {
            TableData.column(0).visible(false);
        }

        $('#checkall').on('click', function() {
            $('input[name="id_cb[]"]').prop('checked', this.checked);
        });
    });
</script>
@endpush