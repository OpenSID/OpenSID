@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')

@php
    $title = ucfirst($ci->controller);
@endphp

@section('title')
    <h1>
        Data {{ $module_name }}
        <small>{{ $action }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ site_url($ci->controller) }}">Data {{ $title }}</a></li>
    <li class="active">{{ $module_name }} {{ $action }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <form id="validasi" action="{{ $form_action }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-body box-profile preview-img">
                        <img class="penduduk img-responsive" src="{{ gambar_desa($kelompok['logo']) }}" alt="Logo">
                        <br />
                        <p class="text-muted text-center text-red">(Kosongkan, jika logo tidak berubah)</p>
                        <div class="input-group input-group-sm text-center">
                            <input type="text" class="form-control hidden" id="file_path" name="logo">
                            <input type="file" class="hidden file-input" id="file" name="logo" accept=".gif,.jpg,.jpeg,.png,.webp">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-info btn-block btn-mb-5 rounded" id="file_browser"><i class="fa fa-upload"></i> Unggah</button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <x-kembali-button 
                            :url="$ci->controller"
                            :judul="'Kembali Ke Daftar ' . $title"
                        />
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="nama">Nama {{ $title }}</label>
                            <div class="col-sm-7">
                                <input
                                    id="nama"
                                    class="form-control input-sm nama_terbatas required"
                                    type="text"
                                    placeholder="Nama {{ $title }}"
                                    name="nama"
                                    value="{{ $kelompok['nama'] }}"
                                    maxlength="50"
                                >
                            </div>
                        </div>
                        <div class="form-group ">
                            <label class="col-sm-3 control-label" for="kode">Kode {{ $title }}</label>
                            <div class="col-sm-7">
                                <input
                                    id="kode"
                                    class="form-control input-sm nomor_sk required"
                                    type="text"
                                    placeholder="Kode {{ $title }}"
                                    name="kode"
                                    value="{{ $kelompok['kode'] }}"
                                    maxlength="16"
                                >
                                <p><code>*Pastikan kode belum pernah dipakai di data lembaga / di data kelompok.</code></p>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label class="col-sm-3 control-label" for="kode">No. SK Pendirian {{ $title }}</label>
                            <div class="col-sm-7">
                                <input
                                    id="no_sk_pendirian"
                                    class="form-control input-sm nomor_sk"
                                    type="text"
                                    placeholder="No. SK Pendirian {{ $title }}"
                                    name="no_sk_pendirian"
                                    value="{{ $kelompok['no_sk_pendirian'] }}"
                                    maxlength="255"
                                >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="id_master">Kategori <?= $title ?></label>
                            <div class="col-sm-7">
                                <select class="form-control input-sm select2 required" id="id_master" name="id_master">
                                    <option value="">-- Silakan Masukkan Kategori {{ $title }}--</option>
                                    @foreach ($list_master as $data)
                                        <option value="{{ $data['id'] }}" @selected($kelompok['id_master'] == $data['id'])>{{ $data['kelompok'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="id_ketua">Ketua <?= $title ?></label>
                            <div class="col-sm-7">
                                <select class="form-control input-sm select2 required" id="kelompok_penduduk" name="id_ketua" data-tipe="{{ $ci->controller }}" data-kelompok="{{ optional($kelompok)['id'] ?? 0 }}" data-ajax-url="{{ site_url($ci->controller . '/apipendudukkelompok') }}" data-ajax-placeholder="Cari NIK / Nama..." @disabled($kelompok !== null)>
                                    <option value="">-- Silakan Masukkan NIK / Nama--</option>
                                    @if ($kelompok?->id_ketua)
                                        @php
                                            $ketua = $list_penduduk->firstWhere('id', $kelompok->id_ketua);
                                        @endphp
                                        @if ($ketua)
                                            <option value="{{ $ketua['id'] }}" selected>NIK : {{ $ketua['nik'] . ' - ' . $ketua['nama'] . ' - ' . $ketua['alamat'] }}</option>
                                        @endif
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="keterangan">Deskripsi <?= $title ?></label>
                            <div class="col-sm-7">
                                <textarea name="keterangan" class="form-control input-sm" placeholder="Deskripsi {{ $title }}" rows="3" maxlength="300">{{ $kelompok['keterangan'] }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
                        <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
$(function() {
    var $select = $('#kelompok_penduduk');
    
    // Initialize Select2 with AJAX loading for Ketua Kelompok/Lembaga
    // Menggunakan endpoint existing: apipendudukkelompok
    $select.select2({
        ajax: {
            url: $select.data('ajax-url'),
            type: 'GET',
            dataType: 'json',
            delay: 300,
            data: function(params) {
                var dataTipe = $select.data('tipe') || 'kelompok';
                var dataKelompok = parseInt($select.data('kelompok')) || 0;
                
                return {
                    q: params.term || '',           // Sesuai dengan parameter endpoint
                    page: params.page || 1,
                    tipe: dataTipe,
                    kelompok: dataKelompok
                };
            },
            processResults: function(data, params) {
                params.page = params.page || 1;
                return {
                    results: (data && data.results) ? data.results : [],
                    pagination: {
                        more: (data && data.pagination && data.pagination.more) ? true : false
                    }
                };
            },
            cache: true,
            error: function(xhr, status, error) {
                console.error('Error loading data:', error, status);
            }
        },
        minimumInputLength: 0,
        placeholder: $select.data('ajax-placeholder') || 'Cari NIK / Nama...',
        templateResult: function(data) {
            if (data.loading) {
                return data.text;
            }
            if (!data.id) {
                return data.text;
            }
            return $('<span>').text(data.text);
        },
        templateSelection: function(data) {
            return data.text || '';
        },
        language: {
            searching: function() { return 'Mencari...'; },
            noResults: function() { return 'Tidak ada data'; },
            errorLoading: function() { return 'Gagal memuat data'; }
        }
    });
});
</script>
@endpush
