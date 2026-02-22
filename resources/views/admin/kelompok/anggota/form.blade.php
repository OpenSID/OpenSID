@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')
@section('title')
    <h1>
        Data Anggota {{ $tipe }}
    </h1>
@endsection
@section('breadcrumb')
    <li><a href="{{ site_url(str_replace('_anggota', '', $controller)) }}"> Daftar {{ $tipe }}</a></li>
    <li class="active">Data Anggota {{ $tipe }}</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    @php
        $isLembaga = $tipe == 'Lembaga';
        $isEdit = !empty($pend['id']);
        $sumberAnggota = $pend['sumber_anggota'] ?? 'penduduk';
    @endphp
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
                    @include('admin.layouts.components.tombol_kembali', ['url' => site_url($controller . '/detail/' . $kelompok), 'label' => 'Anggota ' . $tipe])

                </div>
                <div class="box-body">
                    @if ($isLembaga)
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="sumber_anggota">Sumber Anggota</label>
                            <div class="col-sm-8">
                                <select class="form-control input-sm required" id="sumber_anggota" name="sumber_anggota" @disabled($isEdit)>
                                    <option value="penduduk" @selected($sumberAnggota === 'penduduk')>Penduduk Desa</option>
                                    <option value="luar_desa" @selected($sumberAnggota === 'luar_desa')>Luar Desa</option>
                                </select>
                                @if ($isEdit)
                                    <input type="hidden" name="sumber_anggota" value="{{ $sumberAnggota }}">
                                    <p><code>*Sumber anggota tidak dapat diubah saat edit (V1).</code></p>
                                @endif
                            </div>
                        </div>
                        <div class="callout callout-info" style="margin-top: 0;">
                            <p><strong>Panduan singkat:</strong></p>
                            <p>1) Pilih <strong>Penduduk Desa</strong> jika anggota sudah ada di data penduduk.</p>
                            <p>2) Pilih <strong>Luar Desa</strong> untuk input manual. Field wajib: nama dan jenis kelamin.</p>
                            <p>3) Pada V1, sumber anggota tidak bisa diubah saat edit dan anggota luar desa tidak bisa menjadi ketua.</p>
                        </div>
                    @endif

                    <div id="panel-penduduk">
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="id_penduduk">Nama Anggota</label>
                            <div class="col-sm-8">
                                <select class="form-control input-sm" id="kelompok_penduduk" name="id_penduduk" data-kelompok="{{ $kelompok }}" data-tipe="{{ strtolower($tipe) }}" onchange="loadDataPenduduk(this)">
                                    <option value="">-- Silakan Masukan NIK / Nama --</option>
                                    @if (!empty($pend['id_penduduk']))
                                        <option value="{{ $pend['id_penduduk'] }}" selected>NIK :
                                            {{ $pend['nik'] . ' - ' . $pend['nama'] . ' - ' . $pend['alamat'] }}
                                        </option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="data_penduduk_desa"></div>
                    </div>

                    <div id="panel-luar-desa">
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="nama_luar">Nama Anggota Luar Desa</label>
                            <div class="col-sm-8">
                                <input id="nama_luar" class="form-control input-sm" type="text" placeholder="Nama Lengkap" name="nama_luar" value="{{ $pend['nama_luar'] ?? $pend['nama'] }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="nik_luar">NIK (Opsional)</label>
                            <div class="col-sm-8">
                                <input id="nik_luar" class="form-control input-sm bilangan" type="text" placeholder="NIK" name="nik_luar" value="{{ $pend['nik_luar'] ?? $pend['nik'] }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="sex_luar">Jenis Kelamin</label>
                            <div class="col-sm-8">
                                <select class="form-control input-sm" id="sex_luar" name="sex_luar">
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    @foreach (\App\Enums\JenisKelaminEnum::all() as $sexKey => $sexLabel)
                                        <option value="{{ $sexKey }}" @selected((string) ($pend['sex_luar'] ?? $pend['id_sex']) === (string) $sexKey)>
                                            {{ $sexLabel }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="alamat_luar">Alamat (Opsional)</label>
                            <div class="col-sm-8">
                                <input id="alamat_luar" class="form-control input-sm" type="text" placeholder="Alamat" name="alamat_luar" value="{{ $pend['alamat_luar'] ?? $pend['alamat'] }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="tempatlahir_luar">Tempat Lahir (Opsional)</label>
                            <div class="col-sm-8">
                                <input id="tempatlahir_luar" class="form-control input-sm" type="text" placeholder="Tempat Lahir" name="tempatlahir_luar" value="{{ $pend['tempatlahir_luar'] ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="tanggallahir_luar">Tanggal Lahir (Opsional)</label>
                            <div class="col-sm-5">
                                <div class="input-group input-group-sm date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input id="tanggallahir_luar" class="form-control input-sm pull-right tgl_1" name="tanggallahir_luar" type="text" value="{{ tgl_indo_out($pend['tanggallahir_luar']) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="no_anggota">Nomor Anggota</label>
                        <div class="col-sm-8">
                            <input id="no_anggota" class="form-control input-sm number required" type="text" placeholder="Nomor Anggota" name="no_anggota" value="{{ $pend['no_anggota'] }}">
                            <p><code>*Pastikan nomor anggota belum pernah dipakai.</code></p>
                        </div>
                    </div>
                    <div class="form-group">
                        @if (!empty($pend['id']))
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
        var sumberAnggotaDefault = "{{ $sumberAnggota }}";
        var isLembaga = @json($isLembaga);
        var isEdit = @json($isEdit);

        function getSumberAnggota() {
            if (!isLembaga) {
                return 'penduduk';
            }

            return $('#sumber_anggota').val() || sumberAnggotaDefault || 'penduduk';
        }

        function toggleLuarDesaRequired(isRequired) {
            $('#nama_luar').toggleClass('required', isRequired);
            $('#sex_luar').toggleClass('required', isRequired);
        }

        function togglePanelSumberAnggota() {
            let isPenduduk = getSumberAnggota() === 'penduduk';
            $('#panel-penduduk').toggle(isPenduduk);
            $('#panel-luar-desa').toggle(!isPenduduk);
            $('#kelompok_penduduk').toggleClass('required', isPenduduk);
            toggleLuarDesaRequired(!isPenduduk);

            if (!isPenduduk) {
                $('.data_penduduk_desa').empty();
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            togglePanelSumberAnggota();

            if (isLembaga) {
                $('#sumber_anggota').on('change', function() {
                    togglePanelSumberAnggota();
                    if (getSumberAnggota() === 'penduduk' && $('#kelompok_penduduk').val()) {
                        loadDataPenduduk(document.getElementById("kelompok_penduduk"));
                    }
                });
            }

            if (getSumberAnggota() === 'penduduk' && penduduk) {
                loadDataPenduduk(document.getElementById("kelompok_penduduk"));
                if (isEdit) {
                    $('#kelompok_penduduk').prop('disabled', true);
                }
            }
        });

        function loadDataPenduduk(elm) {
            if (getSumberAnggota() !== 'penduduk') {
                return;
            }

            let _val = $(elm).val()
            $('.data_penduduk_desa').empty()
            if (!$.isEmptyObject(_val)) {
                $.get('{{ ci_route('kelompok_anggota.anggota') }}', {
                    id_penduduk: _val,
                    id_anggota: id_anggota,
                    kategori: kategori,
                    sumber_anggota: getSumberAnggota()
                }, function(data) {
                    $('.data_penduduk_desa').html(data.html)
                    $('#foto').attr('src', data.foto);
                }, 'json')
            }
        }
    </script>
@endpush
