@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')
@section('title')
    <h1>
        Data Anggota {{ $tipe }}
    </h1>
@endsection
@section('breadcrumb')
    <li><a href="{{ site_url($controller) }}"> Daftar {{ $tipe }}</a></li>
    <li class="active">Data Anggota {{ $tipe }}</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    {!! form_open_multipart($form_action, 'class="form-horizontal" id="validasi"') !!}
    <div class="row">
        <div class="col-md-3">
            @include('admin.layouts.components.ambil_foto', [
                'id_sex' => $pend['id_sex'],
                'foto' => $pend['foto'] ?? $pend['foto_anggota'],
                'lokasiFoto' => $pend['foto'] && $tipe === 'Kelompok' ? LOKASI_FOTO_KELOMPOK : ($pend['foto'] ? LOKASI_FOTO_LEMBAGA : LOKASI_USER_PICT),
            ])
        </div>
        <div class="col-md-9">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <a href="{{ site_url("{$controller}/detail/{$kelompok}") }}" class="btn btn-social btn-info btn-sm
                    visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left "></i> Kembali Ke Anggota
                        {{ $tipe }}</a>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="id_penduduk">Nama Anggota</label>
                        <div class="col-sm-8">
                            <select class="form-control input-sm required" id="kelompok_penduduk" name="id_penduduk" data-kelompok="{{ $kelompok }}" data-tipe="{{ strtolower($tipe) }}" onchange="loadDataPenduduk(this)">
                                <option value="">-- Silakan Masukan NIK / Nama --</option>
                                @if ($pend)
                                    <option value="{{ $pend['id_penduduk'] }}" selected>NIK :
                                        {{ $pend['nik'] . ' - ' . $pend['nama'] . ' - ' . $pend['alamat'] }}
                                    </option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="data_penduduk_desa"></div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="no_anggota">Nomor Anggota</label>
                        <div class="col-sm-8">
                            <input id="no_anggota" class="form-control input-sm number required" type="text" placeholder="Nomor Anggota" name="no_anggota" value="{{ $pend['no_anggota'] }}">
                            <p><code>*Pastikan nomor anggota belum pernah dipakai.</code></p>
                        </div>
                    </div>
                    <div class="form-group">
                        @if (!empty($pend))
                            <input type="hidden" name="jabatan_lama" value="{{ $pend['jabatan'] }}">
                        @endif
                        <label class="col-sm-4 control-label" for="jabatan">Jabatan</label>
                        <div class="col-sm-8">
                            <select class="form-control input-sm select2-tags required" id="jabatan" name="jabatan">
                                <option option value="">-- Silakan Pilih Jabatan --</option>
                                @foreach ($list_jabatan1 as $key => $value)
                                    <option value="{{ $key }}" @selected($key == $pend['jabatan'])>
                                        {{ $value }}
                                    </option>
                                @endforeach
                                @foreach ($list_jabatan2 as $value)
                                    <option value="{{ $value['jabatan'] }}" @selected($value['jabatan'] == $pend['jabatan'])>
                                        {{ $value['jabatan'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="no_sk_jabatan">Nomor SK Jabatan</label>
                        <div class="col-sm-8">
                            <input id="no_sk_jabatan" class="form-control input-sm nomor_sk" type="text" placeholder="Nomor SK Jabatan" name="no_sk_jabatan" value="{{ $pend['no_sk_jabatan'] }}">
                        </div>
                    </div>
                    @if ($tipe == 'Lembaga')
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Nomor SK Pengangkatan</label>
                            <div class="col-sm-5">
                                <input name="nmr_sk_pengangkatan" class="form-control input-sm" type="text" maxlength="30" placeholder="Nomor SK Pengangkatan" value="{{ $pend['nmr_sk_pengangkatan'] }}"></input>
                            </div>
                        </div>
                        <div class='form-group'>
                            <label class="col-sm-4 control-label">Tanggal SK Pengangkatan</label>
                            <div class="col-sm-5">
                                <div class="input-group input-group-sm date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input class="form-control input-sm pull-right tgl_1" name="tgl_sk_pengangkatan" type="text" value="{{ tgl_indo_out($pend['tgl_sk_pengangkatan']) }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Nomor SK Pemberhentian</label>
                            <div class="col-sm-5">
                                <input name="nmr_sk_pemberhentian" class="form-control input-sm" type="text" placeholder="Nomor SK Pemberhentian" value="{{ $pend['nmr_sk_pemberhentian'] }}"></input>
                            </div>
                        </div>
                        <div class='form-group'>
                            <label class="col-sm-4 control-label">Tanggal SK Pemberhentian</label>
                            <div class="col-sm-5">
                                <div class="input-group input-group-sm date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input class="form-control input-sm pull-right tgl_1" name="tgl_sk_pemberhentian" type="text" value="{{ tgl_indo_out($pend['tgl_sk_pemberhentian']) }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Masa Jabatan (Usia/Periode)</label>
                            <div class="col-sm-5">
                                <input name="periode" class="form-control input-sm" type="text" placeholder="Contoh: 6 Tahun Periode Pertama (2015 s/d 2021)" value="{{ $pend['periode'] }}"></input>
                            </div>
                        </div>
                    @endif
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="keterangan">Keterangan</label>
                        <div class="col-sm-8">
                            <textarea name="keterangan" class="form-control input-sm" maxlength="300" placeholder="Keterangan" rows="5">{{ $pend['keterangan'] }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i>
                        Batal</button>
                    <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>
                        Simpan</button>
                </div>
            </div>
            </form>
        </div>
    </div>
@endsection
@include('admin.layouts.components.capture')
@include('admin.layouts.components.datetime_picker')
@push('scripts')
    <script src="{{ asset('js/custom-select2.js') }}"></script>
    <script>
        var penduduk = "{{ $pend['id_penduduk'] }}";
        var id_anggota = "{{ $pend['id'] }}";
        var kategori = "{{ $tipe }}";

        if (penduduk) {
            document.addEventListener("DOMContentLoaded", function() {
                var selectElement = document.getElementById("kelompok_penduduk");
                loadDataPenduduk(selectElement);
                $('#kelompok_penduduk').prop('disabled', true);
            });
        }

        function loadDataPenduduk(elm) {
            let _val = $(elm).val()
            let _pendudukDesaElm = $(elm).closest('.penduduk_desa')
            _pendudukDesaElm.find('.data_penduduk_desa').empty()
            if (!$.isEmptyObject(_val)) {
                $.get('{{ ci_route('kelompok_anggota.anggota') }}', {
                    id_penduduk: _val,
                    id_anggota: id_anggota,
                    kategori: kategori
                }, function(data) {
                    $('.data_penduduk_desa').html(data.html)
                    $('#foto').attr('src', data.foto);
                }, 'json')
            }
        }
    </script>
@endpush
