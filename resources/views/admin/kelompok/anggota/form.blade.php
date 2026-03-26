@extends('admin.layouts.index')
@include('admin.layouts.components.asset_form_request')
@section('title')
    <h1>
        Data Anggota {{ $module_name }}
    </h1>
@endsection
@section('breadcrumb')
    <li><a href="{{ site_url(str_replace('_anggota', '', $controller)) }}"> Daftar {{ $tipe }}</a></li>
    <li class="active">Data Anggota {{ $module_name }}</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    {!! form_open_multipart($form_action, 'class="form-horizontal" id="form_validasi"') !!}
    <div class="row">
        <div class="col-md-3">
            @include('admin.layouts.components.ambil_foto', [
                'id_sex' => $pend['id_sex'] ?? null,
                'foto' => ($pend['foto'] ?? null) ?? ($pend['foto_anggota'] ?? null),
                'lokasiFoto' => isset($pend['foto']) && $pend['foto'] && $tipe === 'Kelompok' ? LOKASI_FOTO_KELOMPOK : (isset($pend['foto']) && $pend['foto'] ? LOKASI_FOTO_LEMBAGA : LOKASI_USER_PICT),
            ])
        </div>
        <div class="col-md-9">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <x-kembali-button 
                        :url="$controller . '/detail/' . $kelompok"
                        :judul="'Kembali Ke Daftar Anggota ' . $tipe"
                    />

                </div>
                <div class="box-body">
                    @php
                        $isLuarDesa = old('luar_desa') !== null ? old('luar_desa') == '1' : ($pend !== null && $pend['id_penduduk'] === null);
                        $isEdit     = ! empty($pend);
                    @endphp

                    {{-- Toggle Sumber Anggota --}}
                    @if (! $isEdit)
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Asal Anggota</label>
                            <div class="col-sm-8">
                                <div class="btn-group" data-toggle="buttons">
                                    <label id="btn_penduduk_desa" class="btn btn-info btn-sm form-check-label {{ $isLuarDesa ? '' : 'active' }}" for="asal_desa">
                                        <input id="asal_desa" type="radio" name="luar_desa" value="0"
                                            class="form-check-input"
                                            {{ $isLuarDesa ? '' : 'checked' }}
                                            autocomplete="off"
                                            onchange="toggleAsalAnggota(0);">
                                        Penduduk Desa
                                    </label>
                                    <label id="btn_luar_desa" class="btn btn-info btn-sm form-check-label {{ $isLuarDesa ? 'active' : '' }}" for="asal_luar">
                                        <input id="asal_luar" type="radio" name="luar_desa" value="1"
                                            class="form-check-input"
                                            {{ $isLuarDesa ? 'checked' : '' }}
                                            autocomplete="off"
                                            onchange="toggleAsalAnggota(1);">
                                        Penduduk Luar Desa
                                    </label>
                                </div>
                            </div>
                        </div>
                    @else
                        <input type="hidden" name="luar_desa" value="{{ $isLuarDesa ? '1' : '0' }}">
                    @endif

                    {{-- Bagian Penduduk Desa --}}
                    <div id="section_penduduk_desa" class="{{ $isLuarDesa ? 'hide' : '' }}">
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="id_penduduk">Nama Anggota <span class="text-red">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-control input-sm" id="kelompok_penduduk" name="id_penduduk" data-kelompok="{{ $kelompok }}" data-tipe="{{ strtolower($tipe) }}" onchange="loadDataPenduduk(this)">
                                    <option value="">-- Silakan Masukan NIK / Nama --</option>
                                    @if ($pend && ! $isLuarDesa)
                                        <option value="{{ $pend['id_penduduk'] }}" selected>NIK :
                                            {{ $pend['nik'] . ' - ' . $pend['nama'] . ' - ' . $pend['alamat'] }}
                                        </option>
                                    @endif
                                </select>
                                @if ($isEdit && ! $isLuarDesa && ! empty($pend['id_penduduk']))
                                    <input type="hidden" name="id_penduduk" value="{{ $pend['id_penduduk'] }}">
                                @endif
                            </div>
                        </div>
                        <div class="data_penduduk_desa"></div>
                    </div>

                    {{-- Bagian Penduduk Luar Desa --}}
                    <div id="section_luar_desa" class="{{ $isLuarDesa ? '' : 'hide' }}">
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="nama_luar">Nama Lengkap <span class="text-red">*</span></label>
                            <div class="col-sm-8">
                                <input id="nama_luar" name="nama_luar"
                                    class="form-control input-sm"
                                    type="text" maxlength="100"
                                    placeholder="Nama Lengkap"
                                    value="{{ strtoupper(old('nama_luar', $pend['nama_luar'] ?? '')) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="nik_luar">NIK</label>
                            <div class="col-sm-8">
                                <input id="nik_luar" name="nik_luar"
                                    class="form-control input-sm"
                                    type="text" maxlength="16"
                                    placeholder="Nomor Induk Kependudukan"
                                    value="{{ old('nik_luar', $pend['nik_luar'] ?? '') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="sex_luar">Jenis Kelamin</label>
                            <div class="col-sm-8">
                                <select id="sex_luar" name="sex_luar" class="form-control input-sm">
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    @foreach ($list_jk as $key => $val)
                                        <option value="{{ $key }}" @selected(old('sex_luar', $pend['id_sex'] ?? '') == $key)>{{ $val }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="tempatlahir_luar">Tempat Lahir</label>
                            <div class="col-sm-8">
                                <input id="tempatlahir_luar" name="tempatlahir_luar"
                                    class="form-control input-sm"
                                    type="text" maxlength="100"
                                    placeholder="Tempat Lahir"
                                    value="{{ strtoupper(old('tempatlahir_luar', $pend['tempatlahir_luar'] ?? '')) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="tanggallahir_luar">Tanggal Lahir</label>
                            <div class="col-sm-5">
                                <div class="input-group input-group-sm date">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                    <input id="tanggallahir_luar" name="tanggallahir_luar"
                                        class="form-control input-sm pull-right tgl_1"
                                        type="text"
                                        value="{{ tgl_indo_out(old('tanggallahir_luar', $pend['tanggallahir_luar'] ?? '')) }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="alamat_luar">Alamat</label>
                            <div class="col-sm-8">
                                <textarea id="alamat_luar" name="alamat_luar"
                                    class="form-control input-sm"
                                    maxlength="300" placeholder="Alamat Lengkap" rows="3">{{ old('alamat_luar', $pend['alamat_luar'] ?? '') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="agama_luar">Agama <span class="text-red">*</span></label>
                            <div class="col-sm-8">
                                <select id="agama_luar" name="agama_luar" class="form-control input-sm select2">
                                    <option value="">-- Pilih Agama --</option>
                                    @foreach ($list_agama as $key => $val)
                                        <option value="{{ $key }}" @selected(old('agama_luar', $pend['agama_luar'] ?? '') == $key)>{{ $val }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="pendidikan_luar">Pendidikan Terakhir <span class="text-red">*</span></label>
                            <div class="col-sm-8">
                                <select id="pendidikan_luar" name="pendidikan_luar" class="form-control input-sm select2">
                                    <option value="">-- Pilih Pendidikan --</option>
                                    @foreach ($list_pendidikan as $key => $val)
                                        <option value="{{ $key }}" @selected(old('pendidikan_luar', $pend['pendidikan_luar'] ?? '') == $key)>{{ $val }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="no_anggota">Nomor Anggota <span class="text-red">*</span></label>
                        <div class="col-sm-8">
                            <input id="no_anggota" class="form-control input-sm" type="text" placeholder="Nomor Anggota" name="no_anggota" value="{{ old('no_anggota', $pend['no_anggota'] ?? '') }}">
                            <p><code>*Pastikan nomor anggota belum pernah dipakai.</code></p>
                        </div>
                    </div>
                    <div class="form-group">
                        @if (!empty($pend))
                            <input type="hidden" name="jabatan_lama" value="{{ $pend['jabatan'] }}">
                        @endif
                        <label class="col-sm-4 control-label" for="jabatan">Jabatan <span class="text-red">*</span></label>
                        <div class="col-sm-8">
                            <select class="form-control input-sm select2-tags" id="jabatan" name="jabatan">
                                <option value="">-- Silakan Pilih Jabatan --</option>
                                @foreach ($list_jabatan1 as $key => $value)
                                    <option value="{{ $key }}" @selected($key == old('jabatan', $pend['jabatan'] ?? null))>
                                        {{ $value }}
                                    </option>
                                @endforeach
                                @foreach ($list_jabatan2 as $value)
                                    <option value="{{ $value['jabatan'] }}" @selected($value['jabatan'] == old('jabatan', $pend['jabatan'] ?? null))>
                                        {{ $value['jabatan'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="no_sk_jabatan">Nomor SK Jabatan</label>
                        <div class="col-sm-8">
                            <input id="no_sk_jabatan" class="form-control input-sm" type="text" maxlength="50" placeholder="Nomor SK Jabatan" name="no_sk_jabatan" value="{{ old('no_sk_jabatan', $pend['no_sk_jabatan'] ?? '') }}">
                        </div>
                    </div>
                    @if ($tipe == 'Lembaga')
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Nomor SK Pengangkatan</label>
                            <div class="col-sm-5">
                                <input name="nmr_sk_pengangkatan" class="form-control input-sm" type="text" maxlength="30" placeholder="Nomor SK Pengangkatan" value="{{ old('nmr_sk_pengangkatan', $pend['nmr_sk_pengangkatan'] ?? '') }}"></input>
                            </div>
                        </div>
                        <div class='form-group'>
                            <label class="col-sm-4 control-label">Tanggal SK Pengangkatan</label>
                            <div class="col-sm-5">
                                <div class="input-group input-group-sm date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input class="form-control input-sm pull-right tgl_1" name="tgl_sk_pengangkatan" type="text" value="{{ tgl_indo_out(old('tgl_sk_pengangkatan', $pend['tgl_sk_pengangkatan'] ?? '')) }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Nomor SK Pemberhentian</label>
                            <div class="col-sm-5">
                                <input name="nmr_sk_pemberhentian" class="form-control input-sm" type="text" placeholder="Nomor SK Pemberhentian" value="{{ old('nmr_sk_pemberhentian', $pend['nmr_sk_pemberhentian'] ?? '') }}"></input>
                            </div>
                        </div>
                        <div class='form-group'>
                            <label class="col-sm-4 control-label">Tanggal SK Pemberhentian</label>
                            <div class="col-sm-5">
                                <div class="input-group input-group-sm date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input class="form-control input-sm pull-right tgl_1" name="tgl_sk_pemberhentian" type="text" value="{{ tgl_indo_out(old('tgl_sk_pemberhentian', $pend['tgl_sk_pemberhentian'] ?? '')) }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Masa Jabatan (Usia/Periode)</label>
                            <div class="col-sm-5">
                                <input name="periode" class="form-control input-sm" type="text" placeholder="Contoh: 6 Tahun Periode Pertama (2015 s/d 2021)" value="{{ old('periode', $pend['periode'] ?? '') }}"></input>
                            </div>
                        </div>
                    @endif
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="keterangan">Keterangan</label>
                        <div class="col-sm-8">
                            <textarea name="keterangan" class="form-control input-sm" maxlength="300" placeholder="Keterangan" rows="5">{{ old('keterangan', $pend['keterangan'] ?? '') }}</textarea>
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
        var penduduk   = "{{ $pend['id_penduduk'] ?? '' }}";
        var id_anggota = "{{ $pend['id'] ?? '' }}";
        var kategori   = "{{ $tipe }}";

        $(document).ready(function () {
            if (penduduk) {
                var selectElement = document.getElementById("kelompok_penduduk");
                loadDataPenduduk(selectElement);
                $('#kelompok_penduduk').prop('disabled', true);
            }
        });

        function toggleAsalAnggota(isLuar) {
            if (isLuar) {
                $('#section_penduduk_desa').addClass('hide');
                $('#section_luar_desa').removeClass('hide');
            } else {
                $('#section_penduduk_desa').removeClass('hide');
                $('#section_luar_desa').addClass('hide');
            }
        }

        function loadDataPenduduk(elm) {
            var _val = $(elm).val();
            $('.data_penduduk_desa').empty();
            if (!$.isEmptyObject(_val)) {
                $.get('{{ ci_route('kelompok_anggota.anggota') }}', {
                    id_penduduk: _val,
                    id_anggota: id_anggota,
                    kategori: kategori
                }, function(data) {
                    $('.data_penduduk_desa').html(data.html);
                    $('#foto').attr('src', data.foto);
                }, 'json');
            }
        }
    </script>
@endpush
