<div class="row">
    @if ($jenis_peristiwa == 5 && !$penduduk['tgl_peristiwa'])
    <div class='col-sm-4'>
        <div class='form-group'>
            <label for="tgl_peristiwa">Tanggal Pindah Masuk</label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control input-sm pull-right tgl_sekarang required" name="tgl_peristiwa" type="text"
                    value="{{ $penduduk['tgl_peristiwa'] ? rev_tgl($penduduk['tgl_peristiwa']) : date('d-m-Y') }}">
            </div>
        </div>
    </div>
    @endif
    @if (!$penduduk['tgl_lapor'])
    <div class='col-sm-4'>
        <div class='form-group'>
            <label for="tgl_lapor">Tanggal Lapor</label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control input-sm pull-right tgl_sekarang required" name="tgl_lapor" type="text"
                    value="{{ $penduduk['tgl_lapor'] ? rev_tgl($penduduk['tgl_lapor']) : date('d-m-Y') }}">
            </div>
        </div>
    </div>
    @endif
    <div class='col-sm-12'>
        <div class="form-group subtitle_head">
            <label class="text-right"><strong>DATA DIRI :</strong></label>
        </div>
    </div>
    <div class='col-sm-4'>
        <div class='form-group'>
            <label for="nik">NIK <code id="tampil_nik" style="display: none;"> (Sementara) </code></label>
            <div class="input-group input-group-sm">
                <span class="input-group-addon">
                    <input type="checkbox" title="Centang jika belum memiliki NIK" id="nik_sementara">
                </span>
                <input id="nik" name="nik" class="form-control input-sm required nik" type="text"
                    placeholder="Nomor NIK" value="{{ $penduduk['nik'] }}" @readonly($cek_nik=='0' )></input>
            </div>
        </div>
    </div>
    <div class='col-sm-8'>
        <div class='form-group'>
            <label for="nama">Nama Lengkap <code> (Tanpa Gelar) </code> </label>
            <input id="nama" name="nama" class="form-control input-sm required {{ $jenis_peristiwa == 1 ? 'nama_baru_lahir' : 'nama' }}" maxlength="100" type="text"
                placeholder="Nama Lengkap" value="{{ strtoupper($penduduk['nama']) }}"></input>
        </div>
    </div>
    <div class='col-sm-12'>
        <div class="row">
            <div class="col-sm-12">
                <div class='form-group'>
                    <label for="nama">Status Kepemilikan Identitas</label>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" style="margin-bottom: 0px;">
                            <thead class="bg-gray disabled color-palette">
                                <tr>
                                    <th width='25%'>Wajib Identitas</th>
                                    <th>Identitas Elektronik</th>
                                    <th>Status Rekam</th>
                                    <th>Tag ID Card</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @if ($penduduk['wajib_ktp'] != null)
                                    <td width='25%'>{{ strtoupper($penduduk['wajib_ktp']) }}</td>
                                    @else
                                    <td width='25%'><label id="wajib_ktp"></label></td>
                                    @endif
                                    <td>
                                        <select name="ktp_el" id="ktp_el" class="form-control input-sm wajib_identitas"
                                            onchange="show_hide_ktp_el($(this).find(':selected').val())">
                                            <option value="">Pilih Identitas-EL</option>
                                            @foreach ($ktp_el as $key => $nama)
                                            <option value="{{ $key }}" @selected(($jenis_peristiwa=='1' && $key==3) ||
                                                $penduduk['ktp_el']==$key)>
                                                {{ strtoupper($nama) }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td width='25%'>
                                        <select name="status_rekam" class="form-control input-sm wajib_identitas">
                                            <option value="">Pilih Status Rekam</option>
                                            @foreach ($status_rekam as $key => $nama)
                                                <option value="{{ $key }}"
                                                    @if ($jenis_peristiwa == 1 && strtoupper($nama) == 'BELUM WAJIB')
                                                        selected
                                                    @elseif ($penduduk['status_rekam'] == $key)
                                                        selected
                                                    @endif
                                                >
                                                    {{ strtoupper($nama) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td width='25%'>
                                        <input id="tag_id_card" name="tag_id_card" class="form-control input-sm digits"
                                            type="text" minlength="10" maxlength="17" placeholder="Tag Id Card"
                                            value="{{ $penduduk['tag_id_card'] }}"></input>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="section_ktp_el">
            <div class='col-sm-4'>
                <div class='form-group'>
                    <label for="tempat_cetak_ktp">Tempat Penerbitan KTP</label>
                    <input id="tempat_cetak_ktp" name="tempat_cetak_ktp" class="form-control input-sm" maxlength="150"
                        type="text" placeholder="Tempat Penerbitan KTP"
                        value="{{ $penduduk['tempat_cetak_ktp'] }}"></input>
                </div>
            </div>
            <div class='col-sm-4'>
                <div class='form-group'>
                    <label for="tanggal_cetak_ktp">Tanggal Penerbitan KTP</label>
                    <div class="input-group input-group-sm date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input class="form-control input-sm pull-right" id="tanggal_cetak_ktp" name="tanggal_cetak_ktp"
                            type="text"
                            value="{{ $penduduk['tanggal_cetak_ktp'] ? date('d-m-Y', strtotime($penduduk['tanggal_cetak_ktp'])) : '' }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($jenis_peristiwa != 1)
        <div class='col-sm-4'>
            <div class='form-group'>
                <label for="no_kk_sebelumnya">Nomor KK Sebelumnya</label>
                <input id="no_kk_sebelumnya" name="no_kk_sebelumnya" class="form-control input-sm no_kk" maxlength="30"
                    type="text" placeholder="No KK Sebelumnya"
                    value="{{ strtoupper($penduduk['no_kk_sebelumnya']) }}"></input>
            </div>
        </div>
    @endif
    <div class='col-sm-4'>
        <div class='form-group'>
            <label for="kk_level">Hubungan Dalam Keluarga</label>
            @php
                // Disable jika penduduk adalah Kepala Keluarga atau belum punya id_kk
                $disableKkLevel = (($penduduk['kk_level'] == \App\Enums\SHDKEnum::KEPALA_KELUARGA) && $penduduk['id_kk']);
            @endphp
            @if ($jenis_peristiwa == 1)
                <select id="kk_level" class="form-control input-sm required select2" name="kk_level"
                    @disabled($disableKkLevel)>
                    <option value="">Pilih Hubungan Keluarga</option>
                    <option value="{{ \App\Enums\SHDKEnum::ANAK }}" @selected($penduduk['kk_level'] == \App\Enums\SHDKEnum::ANAK)>{{ strtoupper(\App\Enums\SHDKEnum::valueOf(\App\Enums\SHDKEnum::ANAK)) }}</option>
                    <option value="{{ \App\Enums\SHDKEnum::CUCU }}" @selected($penduduk['kk_level'] == \App\Enums\SHDKEnum::CUCU)>{{ strtoupper(\App\Enums\SHDKEnum::valueOf(\App\Enums\SHDKEnum::CUCU)) }}</option>
                    <option value="{{ \App\Enums\SHDKEnum::FAMILI_LAIN }}" @selected($penduduk['kk_level'] == \App\Enums\SHDKEnum::FAMILI_LAIN)>{{ strtoupper(\App\Enums\SHDKEnum::valueOf(\App\Enums\SHDKEnum::FAMILI_LAIN)) }}</option>
                </select>
                @if ($disableKkLevel)
                    {{-- Hidden input untuk memastikan nilai tetap terkirim saat submit karena field disabled tidak mengirim data --}}
                    <input type="hidden" name="kk_level" value="{{ $penduduk['kk_level'] }}">
                @endif
            @else
                <select id="kk_level" class="form-control input-sm required select2" name="kk_level"
                    @disabled($disableKkLevel)>
                    <option value="">Pilih Hubungan Keluarga</option>
                    @foreach ($hubungan as $key => $value)
                        <option value="{{ $key }}" @selected($penduduk['kk_level'] == $key) @disabled($key == 1 && $keluarga['status_dasar'] == '2')>
                            {{ strtoupper($value) }}</option>
                    @endforeach
                </select>
                @if ($disableKkLevel)
                    {{-- Hidden input untuk memastikan nilai tetap terkirim saat submit karena field disabled tidak mengirim data --}}
                    <input type="hidden" name="kk_level" value="{{ $penduduk['kk_level'] }}">
                @endif
            @endif
        </div>
    </div>
    <div class='col-sm-4'>
        <div class='form-group'>
            <label for="sex">Jenis Kelamin </label>
            <select class="form-control input-sm required" name="sex"
                onchange="ubah_sex($(this).find(':selected').val());">
                <option value="">Jenis Kelamin</option>
                @foreach(\App\Enums\JenisKelaminEnum::all() as $key => $label)
                    <option value="{{ $key }}" @selected($penduduk['id_sex'] == $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class='col-sm-7'>
        <div class='form-group'>
            <label for="agama_id">Agama</label>
            <select class="form-control input-sm required" name="agama_id">
                <option value="">Pilih Agama</option>
                @foreach (\App\Enums\AgamaEnum::all() as $key => $value)
                    <option value="{{ $key }}" @selected($penduduk['agama_id'] == $key)>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class='col-sm-5'>
        <div class='form-group'>
            <label for="status">Status Penduduk </label>
            <select class="form-control input-sm required" id="status_penduduk" name="status"
                onchange="show_hide_penduduk_tidak_tetap($(this).find(':selected').val())"
                @disabled($penduduk['no_kk'])>
                <option value="">Pilih Status Penduduk</option>
                @foreach ($status_penduduk as $key => $value)
                <option value="{{ $key }}" @selected($penduduk['id_status']==$key)>{{ strtoupper($value) }}
                </option>
                @endforeach
            </select>
        </div>
    </div>
    <div id="section_penduduk_tidak_tetap">
        <div class='col-sm-12'>
            <div class="form-group subtitle_head">
                <label class="text-right"><strong>DATA PENDUDUK TIDAK TETAP :</strong></label>
            </div>
        </div>
        <div class='col-sm-8'>
            <div class='form-group'>
                <label for="maksud_tujuan_kedatangan">Maksud dan Tujuan Kedatangan</label>
                <textarea id="maksud_tujuan_kedatangan" name="maksud_tujuan_kedatangan" class="form-control input-sm"
                    style="resize: none"
                    placeholder="Maksud dan Tujuan Kedatangan">{{ $penduduk['maksud_tujuan_kedatangan'] }}</textarea>
            </div>
        </div>
    </div>
    <div class='col-sm-12'>
        <div class="form-group subtitle_head">
            <label class="text-right"><strong>DATA KELAHIRAN :</strong></label>
        </div>
    </div>
    <div class='col-sm-4'>
        <div class='form-group'>
            <label for="akta_lahir">Nomor Akta Kelahiran </label>
            <input id="akta_lahir" name="akta_lahir" class="form-control input-sm nomor_sk" type="text" maxlength="40"
                placeholder="Nomor Akta Kelahiran" value="{{ $penduduk['akta_lahir'] }}"></input>
        </div>
    </div>
    <div class='col-sm-8'>
        <div class='form-group'>
            <label for="tempatlahir">Tempat Lahir</label>
            <input id="tempatlahir" name="tempatlahir" class="form-control input-sm required" maxlength="100"
                type="text" placeholder="Tempat Lahir" value="{{ strtoupper($penduduk['tempatlahir']) }}"></input>
        </div>
    </div>
    <div class='col-sm-4'>
        <div class='form-group'>
            <label for="tanggallahir">Tanggal Lahir</label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control input-sm pull-right required" id="tgl_lahir" name="tanggallahir" type="text"
                    value="{{ $penduduk['tanggallahir'] }}">
            </div>
        </div>
    </div>
    <div class='col-sm-4'>
        <div class='form-group'>
            <label for="waktulahir">Waktu Kelahiran </label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control input-sm pull-right" id="jammenit_1" name="waktu_lahir" type="text"
                    value="{{ $penduduk['waktu_lahir'] }}">
            </div>
        </div>
    </div>
    <div class='col-sm-4'>
        <div class='form-group'>
            <label for="tempat_dilahirkan">Tempat Dilahirkan</label>
            <select class="form-control input-sm" name="tempat_dilahirkan">
                <option value="">Pilih Tempat Dilahirkan</option>
                @foreach ($tempat_dilahirkan as $key => $value)
                <option value="{{ $key }}" @selected($penduduk['tempat_dilahirkan']==$key)>{{ strtoupper($value) }}
                </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class='col-sm-12'>
        <div class='row'>
            <div class='col-sm-4'>
                <div class='form-group'>
                    <label for="jenis_kelahiran">Jenis Kelahiran</label>
                    <select class="form-control input-sm" name="jenis_kelahiran">
                        <option value="">Pilih Jenis Kelahiran</option>
                        @foreach ($jenis_kelahiran as $key => $value)
                        <option value="{{ $key }}" @selected($penduduk['jenis_kelahiran']==$key)>
                            {{ strtoupper($value) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class='col-sm-4'>
                <div class='form-group'>
                    <label for="kelahiran_anak_ke">Anak Ke <code>(Isi dengan angka)</code></label>
                    <input id="kelahiran_anak_ke" name="kelahiran_anak_ke" class="form-control input-sm number" min="1"
                        max="20" type="number" placeholder="Anak Ke-"
                        value="{{ $penduduk['kelahiran_anak_ke'] }}"></input>
                </div>
            </div>
            <div class='col-sm-4'>
                <div class='form-group'>
                    <label for="penolong_kelahiran">Penolong Kelahiran</label>
                    <select class="form-control input-sm" name="penolong_kelahiran">
                        <option value="">Pilih Penolong Kelahiran</option>
                        @foreach ($penolong_kelahiran as $key => $nama)
                        <option value="{{ $key }}" @selected($penduduk['penolong_kelahiran']==$key)>{{ strtoupper($nama)
                            }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class='col-sm-12'>
        <div class='row'>
            <div class='col-sm-4'>
                <div class='form-group'>
                    <label for="berat_lahir">Berat Lahir <code>( Gram )</code></label>
                    <input id="berat_lahir" name="berat_lahir" class="form-control input-sm number" maxlength="6"
                        type="text" placeholder="Berat Lahir"
                        value="{{ strtoupper($penduduk['berat_lahir']) }}"></input>
                </div>
            </div>
            <div class='col-sm-4'>
                <div class='form-group'>
                    <label for="panjang_lahir">Panjang Lahir <code>( cm )</code></label>
                    <input id="panjang_lahir" name="panjang_lahir" class="form-control input-sm number" maxlength="3"
                        type="text" placeholder="Panjang Lahir"
                        value="{{ strtoupper($penduduk['panjang_lahir']) }}"></input>
                </div>
            </div>
        </div>
    </div>
    <div class='col-sm-12'>
        <div class="form-group subtitle_head">
            <label class="text-right"><strong>DATA PENDIDIKAN DAN PEKERJAAN :</strong></label>
        </div>
    </div>
    <div class='col-sm-4'>
        <div class='form-group'>
            <label for="pendidikan_kk_id">Pendidikan Dalam KK </label>
            <select class="form-control input-sm required" name="pendidikan_kk_id">
                <option value="">Pilih Pendidikan (Dalam KK) </option>
                @foreach (\App\Enums\PendidikanKKEnum::all() as $key => $value) <option value="{{ $key }}" @selected($penduduk['pendidikan_kk_id']==$key || ($jenis_peristiwa=='1' && $key==\App\Enums\PendidikanKKEnum::BELUM_SEKOLAH))>
                    {{ $value }}
                </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class='col-sm-4'>
        <div class='form-group'>
            <label for="pendidikan_sedang_id">Pendidikan Sedang Ditempuh </label>
            <select class="form-control input-sm" name="pendidikan_sedang_id">
                <option value="">Pilih Pendidikan</option>
                @foreach (\App\Enums\PendidikanSedangEnum::all() as $key => $value)
                <option value="{{ $key }}" @selected($penduduk['pendidikan_sedang_id']==$key || ($jenis_peristiwa=='1' && $key==18))>{{ strtoupper($value) }}
                </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class='col-sm-4'>
        <div class='form-group'>
            <label for="pekerjaan_id"><?= HEADER_PEKERJAAN ?></label>
            <select class="form-control input-sm required" name="pekerjaan_id">
                <option value="">Pilih <?= HEADER_PEKERJAAN ?></option>
                 @foreach (\App\Enums\PekerjaanEnum::all() as $key => $value)
                    <option value="{{ $key }}" @selected($penduduk['pekerjaan_id']==$key || ($jenis_peristiwa=='1' && $key==\App\Enums\PekerjaanEnum::BELUM_TIDAK_BEKERJA))>{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class='col-sm-4'>
        <div class='form-group'>
            <label for="pekerja_migran">Pekerja Migran</label>
            @if ($status_pantau)
            <select class="form-control input-sm" data-placeholder="Pilih Pekerja Migran" id="pekerja_migran"
                name="pekerja_migran">
                <option value="BUKAN PEKERJA MIGRAN" @selected(empty($penduduk['pekerja_migran']) ||
                    $penduduk['pekerja_migran']=='BUKAN PEKERJA MIGRAN' )>BUKAN PEKERJA MIGRAN</option>
                @if ($penduduk && !empty($penduduk['pekerja_migran']) && $penduduk['pekerja_migran'] != 'BUKAN PEKERJA
                MIGRAN')
                <option value="{{ $penduduk['pekerja_migran'] }}" selected>{{ $penduduk['pekerja_migran'] }}
                </option>
                @endif
            </select>
            @else
            <select class="form-control input-sm select2-tags pekerja_migran" id="pekerja_migran" name="pekerja_migran">
                <option value="BUKAN PEKERJA MIGRAN" @selected(empty($penduduk['pekerja_migran']) ||
                    $penduduk['pekerja_migran']=='BUKAN PEKERJA MIGRAN' )>BUKAN PEKERJA MIGRAN</option>
                @if ($pekerja_migran_penduduk)
                @foreach ($pekerja_migran_penduduk as $key => $value)
                @if ($key != 'BUKAN PEKERJA MIGRAN' && !empty($key))
                <option value="{{ $key }}" @selected($penduduk['pekerja_migran']==$key)>{{ $key }}
                </option>
                @endif
                @endforeach
                @endif
            </select>
            @endif
        </div>
    </div>

    <div class='col-sm-12'>
        <div class="form-group subtitle_head">
            <label class="text-right"><strong>DATA KESUKUAN :</strong></label>
        </div>
    </div>
    <div class='col-sm-4'>
        <div class='form-group'>
            <label for="adat">Wilayah Adat</label>
            @if ($status_pantau)
            <select class="form-control input-sm" data-placeholder="Pilih Adat" id="adat" name="adat">
                <option value=""></option>
            </select>
            @else
            <select class="form-control input-sm select2-tags nama_adat" id="adat" name="adat">
                <option value="">Pilih Adat</option>
                @if ($adat_penduduk)
                @foreach ($adat_penduduk as $key => $value)
                <option value="{{ $key }}" @selected($penduduk['adat']==$key)>{{ $key }}
                </option>
                @endforeach
                @endif
            </select>
            @endif
        </div>
    </div>
    <div class='col-sm-4'> 
        <div class='form-group'>
            <label for="etnis">Suku/Etnis</label>
            @if ($status_pantau)
                <select class="form-control input-sm" data-placeholder="Pilih Suku/Etnis" id="suku" name="suku">
                    <option value="">-- Pilih / Kosongkan --</option>
                </select>
            @else
                <select class="form-control input-sm select2-tags nama_suku" id="suku" name="suku">
                    <option value="">-- Pilih / Kosongkan --</option>
                    @if ($suku_penduduk)
                        @foreach ($suku_penduduk as $key => $value)
                            <option value="{{ $key }}" @selected($penduduk['suku']==$key)>{{ $key }}</option>
                        @endforeach
                        <optgroup label="----------"></optgroup>
                    @endif
                    @foreach ($suku as $key => $value)
                        <option value="{{ $key }}" @selected($penduduk['suku']==$key)>{{ $key }}</option>
                    @endforeach
                </select>
            @endif
        </div>
    </div>

    <div class='col-sm-4'>
        <div class='form-group'>
            <label for="marga">Marga</label>
            @if ($status_pantau)
            <select class="form-control input-sm" data-placeholder="Pilih Marga" id="marga" name="marga">
                <option value=""></option>
            </select>
            @else
            <select class="form-control input-sm select2-tags nama_suku" id="marga" name="marga">
                <option value="">Pilih Marga</option>
                @if ($marga_penduduk)
                @foreach ($marga_penduduk as $key => $value)
                <option value="{{ $key }}" @selected($penduduk['marga']==$key)>{{ $key }}
                </option>
                @endforeach
                <optgroup label="----------"></optgroup>
                @endif
                @foreach ($marga as $key => $value)
                <option value="{{ $key }}" @selected($penduduk['marga']==$key)>{{ $key }}
                </option>
                @endforeach
            </select>
            @endif
        </div>
    </div>

    <div class='col-sm-12'>
        <div class="form-group subtitle_head">
            <label class="text-right"><strong>DATA KEWARGANEGARAAN :</strong></label>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="row">
            <div class='col-sm-4'>
                <div class='form-group'>
                    <label for="warganegara_id">Status Warga Negara</label>
                    <select class="form-control input-sm required" id="warganegara_id" name="warganegara_id"
                        onchange="show_hide_status_warga_negara($(this).find(':selected').val())">
                        <option value="">Pilih Warga Negara</option>
                        @foreach (\App\Enums\WargaNegaraEnum::all() as $key => $value)
                            <option value="{{ $key }}" @selected($penduduk['warganegara_id'] == $key)>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class='col-sm-8'>
                <div class='form-group'>
                    <label for="dokumen_pasport">Nomor Paspor </label>
                    <input id="dokumen_pasport" name="dokumen_pasport" class="form-control input-sm nomor_sk"
                        maxlength="45" type="text" placeholder="Nomor Paspor"
                        value="{{ strtoupper($penduduk['dokumen_pasport']) }}"></input>
                </div>
            </div>
        </div>
    </div>
    <div class='col-sm-4'>
        <div class='form-group'>
            <label for="tanggal_akhir_paspor">Tgl Berakhir Paspor</label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control input-sm pull-right" id="tgl_2" name="tanggal_akhir_paspor" type="text"
                    value="{{ $penduduk['tanggal_akhir_paspor'] }}">
            </div>
        </div>
    </div>
    <div class='col-sm-8' id='field_dokumen_kitas'>
        <div class='form-group'>
            <label for="dokumen_kitas">Nomor KITAS/KITAP </label>
            <input id="dokumen_kitas" name="dokumen_kitas" class="form-control input-sm" maxlength="45"
                type="text" placeholder="Nomor KITAS/KITAP"
                value="{{ strtoupper($penduduk['dokumen_kitas']) }}"></input>
        </div>
    </div>
    <div class='col-sm-4' id='field_negara_asal'>
        <div class='form-group'>
            <label for="negara_asal">Negara Asal</label>
            <input id="negara_asal" name="negara_asal" class="form-control input-sm" maxlength="10" type="text"
                placeholder="Negara Asal" value="{{ strtoupper($penduduk['negara_asal']) }}"></input>
        </div>
    </div>
    <div class='col-sm-12'>
        <div class="form-group subtitle_head">
            <label class="text-right"><strong>DATA ORANG TUA :</strong></label>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="row">
            <div class='col-sm-4'>
                <div class='form-group'>
                    <label for="ayah_nik"> NIK Ayah </label>
                    <input id="ayah_nik" name="ayah_nik" class="form-control input-sm nik" type="text"
                        placeholder="Nomor NIK Ayah" value="{{ $penduduk['ayah_nik'] }}"></input>
                </div>
            </div>
            <div class='col-sm-8'>
                <div class='form-group'>
                    <label for="nama_ayah">Nama Ayah </label>
                    <input id="nama_ayah" name="nama_ayah" class="form-control input-sm required nama" maxlength="100"
                        type="text" placeholder="Nama Ayah" value="{{ strtoupper($penduduk['nama_ayah']) }}"></input>
                </div>
            </div>
        </div>
    </div>
    <div class='col-sm-4'>
        <div class='form-group'>
            <label for="ibu_nik"> NIK Ibu </label>
            <input id="ibu_nik" name="ibu_nik" class="form-control input-sm nik" type="text" placeholder="Nomor NIK Ibu"
                value="{{ $penduduk['ibu_nik'] }}"></input>
        </div>
    </div>
    <div class='col-sm-8'>
        <div class='form-group'>
            <label for="nama_ibu">Nama Ibu </label>
            <input id="nama_ibu" name="nama_ibu" class="form-control input-sm required nama" maxlength="100" type="text"
                placeholder="Nama Ibu" value="{{ strtoupper($penduduk['nama_ibu']) }}"></input>
        </div>
    </div>
    <div class='col-sm-12'>
        <div class="form-group subtitle_head">
            <label class="text-right"><strong>DATA ALAMAT :</strong></label>
        </div>
    </div>
    @if (!empty($penduduk['no_kk']) || $kk_baru)
    <!-- <div class='col-sm-12'>
        <div class='form-group'>
            <label for="alamat">Alamat KK </label>
            <input id="alamat" name="alamat" class="form-control input-sm nomor_sk required" 
                maxlength="200" type="text" placeholder="Alamat di Kartu Keluarga" 
                value="{{ $penduduk['alamat'] }}">
        </div>
    </div> -->
    @endif
    @if (empty($id_kk))
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group col-sm-3">
                <label for="dusun">{{ ucwords(setting('sebutan_dusun')) }} @if (!(empty($penduduk['no_kk']) &&
                    empty($kk_baru)))
                    {{ 'KK' }}
                    @endif
                </label>
                <select id="dusun" class="form-control input-sm select2 required">
                    <option value="">Pilih {{ ucwords(setting('sebutan_dusun')) }}</option>
                    @foreach ($wilayah as $keyDusun => $dusun)
                    <option value="{{ $keyDusun }}" @selected($keyDusun==$penduduk['wilayah']['dusun'])>{{ $keyDusun }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-sm-3">
                <label for="rw">RW @if (!(empty($penduduk['no_kk']) && empty($kk_baru)))
                    {{ 'KK' }}
                    @endif
                </label>
                <select id="rw" name="rw" class="form-control input-sm select2 required">
                    <option value="">Pilih RW</option>
                    @foreach ($wilayah as $keyDusun => $dusun)
                    <optgroup value="{{ $keyDusun }}" label="{{ ucwords(setting('sebutan_dusun')) . ' ' . $keyDusun }}"
                        @disabled($penduduk['wilayah']['dusun'] !=$keyDusun)>
                        @foreach ($dusun as $keyRw => $rw)
                        <option value="{{ $keyDusun }}__{{ $keyRw }}" @selected($penduduk['wilayah']['rw']==$keyRw &&
                            $penduduk['wilayah']['dusun']==$keyDusun)>{{ $keyRw }}</option>
                        @endforeach
                    </optgroup>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-sm-3">
                <label for="rt">RT @if (!(empty($penduduk['no_kk']) && empty($kk_baru)))
                    {{ 'KK' }}
                    @endif
                </label>
                <select id="id_cluster" name="id_cluster" class="form-control input-sm select2 required">
                    <option value="">Pilih RT</option>
                    @foreach ($wilayah as $keyDusun => $dusun)
                    @foreach ($dusun as $keyRw => $rw)
                    <optgroup value="{{ $keyDusun }}__{{ $keyRw }}" label="{{ 'RW ' . $keyRw }}"
                        @disabled($penduduk['wilayah']['rw'] !=$keyRw || $penduduk['wilayah']['dusun'] !=$keyDusun)>
                        @foreach ($rw as $rt)
                        <option value="{{ $rt->id }}" @selected($penduduk['id_cluster']==$rt->id)>
                            {{ $rt->rt }}</option>
                        @endforeach
                    </optgroup>
                    @endforeach
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    @endif
    <div class='col-sm-12'>
        <div class='form-group'>
            <label for="alamat_sebelumnya">Alamat Sebelumnya </label>
            <input id="alamat_sebelumnya" name="alamat_sebelumnya"
                class="form-control input-sm nomor_sk {{ jecho($jenis_peristiwa, 5, 'required') }}" maxlength="200"
                type="text" placeholder="Alamat Sebelumnya" value="{{ $penduduk['alamat_sebelumnya'] }}"></input>
        </div>
    </div>
    @if (!$penduduk['no_kk'] && !$kk_baru)
    <div class='col-sm-12'>
        <div class='form-group'>
            <label for="alamat_sekarang">Alamat Sekarang </label>
            <input id="alamat_sekarang" name="alamat_sekarang" class="form-control input-sm" maxlength="200" type="text"
                placeholder="Alamat Sekarang" value="{{ $penduduk['alamat_sekarang'] }}"></input>
        </div>
    </div>
    @endif
    <div class='col-sm-4'>
        <div class='form-group'>
            <label for="telepon"> Nomor Telepon </label>
            <input id="telepon" name="telepon" class="form-control input-sm number" type="text" maxlength="20"
                placeholder="Nomor Telepon" value="{{ $penduduk['telepon'] }}"></input>
        </div>
    </div>
    <div class='col-sm-4'>
        <div class='form-group'>
            <label for="email"> Email </label>
            <input id="email" name="email" class="form-control input-sm email" maxlength="50" placeholder="Alamat Email"
                value="{{ $penduduk['email'] }}"></input>
        </div>
    </div>
    <div class='col-sm-4'>
        <div class='form-group'>
            <label for="telegram"> Telegram </label>
            <input name="telegram" class="form-control input-sm number" maxlength="100" type="text"
                placeholder="Akun Telegram" value="{{ $penduduk['telegram'] }}"></input>
        </div>
    </div>
    <div class='col-sm-4'>
        <div class='form-group'>
            <label for="hubung_warga"> Cara Hubung Warga </label>
            <select class="form-control input-sm" name="hubung_warga">
                <option value="">Pilih Cara Hubungi</option>
                @foreach (['SMS', 'Email', 'Telegram'] as $value)
                @if ((bool) setting('aktifkan_sms') === false && $value === 'SMS')
                @continue;
                @endif

                <option value="{{ $value }}" @selected($penduduk['hubung_warga']==$value)>{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class='col-sm-12'>
        <div class="form-group subtitle_head">
            <label class="text-right"><strong>DATA PERKAWINAN :</strong></label>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="row">
            <div class='col-sm-4'>
                <div class='form-group'>
                    <label for="status_kawin">Status Perkawinan</label>
                    <select class="form-control input-sm required" name="status_kawin"
                        onchange="disable_kawin_cerai($(this).find(':selected').val())" id="status_perkawinan">
                        <option value="">Pilih Status Perkawinan</option>
                        @foreach ($kawin as $key => $value)
                        <option value="{{ $key }}" @selected($penduduk['status_kawin']==$key || ($jenis_peristiwa=='1' && $key==\App\Enums\StatusKawinEnum::BELUMKAWIN))>
                            {{ strtoupper($value) }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class='col-sm-4'>
                <div class='form-group'>
                    @if ($penduduk['agama_id'] == 0 || null === $penduduk['agama_id'])
                    <label for="akta_perkawinan">No. Akta Nikah (Buku Nikah)/Perkawinan </label>
                    @elseif ($penduduk['agama_id'] == 1)
                    <label for="akta_perkawinan">No. Akta Nikah (Buku Nikah) </label>
                    @else
                    <label for="akta_perkawinan">No. Akta Perkawinan </label>
                    @endif
                    <input id="akta_perkawinan" name="akta_perkawinan" class="form-control input-sm nomor_sk"
                        type="text" maxlength="40" placeholder="Nomor Akta Perkawinan"
                        value="{{ $penduduk['akta_perkawinan'] }}"></input>
                </div>
            </div>
            <div class='col-sm-4'>
                <div class='form-group'>
                    <label for="tanggalperkawinan">Tanggal Perkawinan <code>(Wajib diisi apabila status
                            KAWIN)</code></label>
                    <div class="input-group input-group-sm date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input class="form-control input-sm pull-right" id="tgl_3" name="tanggalperkawinan" type="text"
                            value="{{ $penduduk['tanggalperkawinan'] ? date('d-m-Y', strtotime($penduduk['tanggalperkawinan'])) : '' }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class='col-sm-8'>
        <div class='form-group'>
            <label for="akta_perceraian">Akta Perceraian </label>
            <input id="akta_perceraian" name="akta_perceraian" class="form-control input-sm nomor_sk" maxlength="40"
                type="text" placeholder="Akta Perceraian"
                value="{{ strtoupper($penduduk['akta_perceraian']) }}"></input>
        </div>
    </div>
    <div class='col-sm-4'>
        <div class='form-group'>
            <label for="tanggalperceraian">Tanggal Perceraian <code>(Wajib diisi apabila status CERAI)</code></label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control input-sm pull-right tgl_indo" name="tanggalperceraian" type="text"
                    value="{{ $penduduk['tanggalperceraian'] ? date('d-m-Y', strtotime($penduduk['tanggalperceraian'])) : '' }}">
            </div>
        </div>
    </div>
    <div class='col-sm-12'>
        <div class="form-group subtitle_head">
            <label class="text-right"><strong>DATA KESEHATAN :</strong></label>
        </div>
    </div>
    <div class='col-sm-12'>
        <div class="row">
            <div class='col-sm-4'>
                <div class='form-group'>
                    <label for="golongan_darah_id">Golongan Darah</label>
                    <select class="form-control input-sm required" name="golongan_darah_id">
                        <option value="">Pilih Golongan Darah</option>
                        @foreach (\App\Enums\GolonganDarahEnum::all() as $key => $value)
                            <option value="{{ $key }}" @selected($penduduk['golongan_darah_id'] == $key)>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class='col-sm-4'>
                <div class='form-group'>
                    <label for="cacat_id">Disabilitas</label>
                    <select class="form-control input-sm" name="cacat_id">
                        <option value="">Pilih Jenis Disabilitas</option>
                        @foreach (\App\Enums\CacatEnum::all() as $key => $value)
                        <option value="{{ $key }}" @selected($penduduk['cacat_id']==$key)>
                            {{ strtoupper($value) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class='col-sm-4'>
                <div class='form-group'>
                    <label for="sakit_menahun_id">Sakit Menahun</label>
                    <select class="form-control input-sm" name="sakit_menahun_id">
                        <option value="">Pilih Sakit Menahun</option>
                        @foreach ($sakit_menahun as $key => $value)
                        <option value="{{ $key }}" @selected($penduduk['sakit_menahun_id']==$key)>
                            {{ strtoupper($value) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    @if ($jenis_peristiwa != 1 && $jenis_peristiwa != 2)
        <div class='col-sm-4' id="akseptor_kb">
            <div class='form-group'>
                <label for="cara_kb_id">Akseptor KB</label>
                <select class="form-control input-sm" name="cara_kb_id">
                    <option value="">Pilih Cara KB Saat Ini</option>
                    @foreach (\App\Enums\CaraKBEnum::all() as $key => $value)
                    <option value="{{ $key }}" @selected($penduduk['cara_kb_id']==$key)>{{ strtoupper($value) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif
    <div id='isian_hamil' class='col-sm-4'>
        <div class='form-group'>
            <label for="hamil">Status Kehamilan </label>
            <select class="form-control input-sm" name="hamil">
                <option value="">Pilih Status Kehamilan</option>
                @foreach ($kehamilan as $key => $value)
                <option value="{{ $key }}" @selected($penduduk['hamil']==$key)>{{ strtoupper($value) }}
                </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class='col-sm-4'>
        <div class='form-group'>
            <label for="id_asuransi">Asuransi Kesehatan</label>
            <select class="form-control input-sm" name="id_asuransi"
                onchange="show_hide_asuransi($(this).find(':selected').val());">
                <option value="">Pilih Asuransi</option>
                @foreach ($pilihan_asuransi as $key => $value)
                <option value="{{ $key }}" @selected($penduduk['id_asuransi']==$key)>{{ strtoupper($value) }}
                </option>
                @endforeach
            </select>
        </div>
    </div>
    <div id='asuransi_pilihan' class='col-sm-4'>
        <div class='form-group'>
            <label id="label-no-asuransi" for="no_asuransi">No Asuransi </label>
            <input id="no_asuransi" name="no_asuransi" class="form-control input-sm nomor_sk" type="text"
                maxlength="100" placeholder="Nomor Asuransi" value="{{ $penduduk['no_asuransi'] }}"></input>
        </div>
    </div>
    <div id="status_asuransi" class="col-sm-4">
        <div class='form-group'>
            <label>Status Kepersertaan Asuransi Kesehatan</label>
            <select class="form-control input-sm" name="status_asuransi">
                <option value="" @selected($penduduk['status_asuransi']==null)>Pilih Kepersertaan Asuransi Kesehatan
                </option>
                @foreach (\App\Enums\AktifEnum::all() as $key => $value)
                <option value="{{ $key }}" @selected(isset($penduduk['status_asuransi']) &&
                    $penduduk['status_asuransi']==$key)>{{ strtoupper($value) }}
                </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-4">
                <div class='form-group'>
                    <label id="label-no-bpjs-ketenagakerjaan" for="bpjs_ketenagakerjaan">Nomor BPJS
                        Ketenagakerjaan</label>
                    <input id="bpjs_ketenagakerjaan" name="bpjs_ketenagakerjaan" class="form-control input-sm nomor_sk"
                        type="text" maxlength="100" placeholder="Nomor BPJS Ketenagakerjaan"
                        value="{{ $penduduk['bpjs_ketenagakerjaan'] }}"></input>
                </div>
            </div>
        </div>
    </div>
    <div class='col-sm-12'>
        <div class="form-group subtitle_head">
            <label class="text-right"><strong>DATA LAINNYA :</strong></label>
        </div>
    </div>
    <div class='col-sm-12'>
        <div class="row">
            <div class='col-sm-4'>
                <div class='form-group'>
                    <label for="bahasa_id">Dapat Membaca Huruf</label>
                    <select class="form-control input-sm" id="bahasa_id" name="bahasa_id">
                        <option value="0">Pilih Isian</option>
                        @foreach ($bahasa as $key => $value)
                        <option value="{{ $key }}" @selected($penduduk['bahasa_id']==$key)>
                            {{ strtoupper($value) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class='col-sm-8'>
                <div class='form-group'>
                    <label for="ket">Keterangan</label>
                    <textarea id="ket" name="ket" class="form-control input-sm" rows="3"
                        placeholder="Keterangan">{{ $penduduk['ket'] }}</textarea>
                </div>
            </div>
        </div>
    </div>
    @if ($jenis_peristiwa == 2)
        <div class='col-sm-12'>
            <div class="form-group subtitle_head">
                <label class="text-right"><strong>DATA KEMATIAN :</strong></label>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="row">

                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="meninggal_di">Tempat Meninggal</label>
                        <input name="meninggal_di" class="form-control input-sm" type="text" maxlength="50" placeholder="Tempat Meninggal">
                    </div>
                </div>
        
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="jam_mati">Jam Kematian</label>
                        <div class="input-group input-group-sm ">
                            <div class="input-group-addon">
                                <i class="fa fa-clock-o"></i>
                            </div>
                            <input name="jam_mati" id="jammenit_1" class="form-control input-sm" type="text" maxlength="50" placeholder="Jam Kematian">
                        </div>
                    </div>
                </div>
        
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="sebab">Penyebab Kematian</label>
                        <select id="sebab" name="sebab" class="form-control select2 input-sm required">
                            <option value="">Pilih Penyebab Kematian</option>
                            @foreach ($sebab as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="penolong_mati">Yang Menerangkan Kematian</label>
                        <select id="penolong_mati" name="penolong_mati" class="form-control select2 input-sm required">
                            <option value="">Pilih Yang Menerangkan Kematian</option>
                            @foreach ($penolong_mati as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
        
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="akta_mati">Nomor Akta Kematian</label>
                        <input name="akta_mati" class="form-control input-sm" type="text" maxlength="50" placeholder="Nomor Akta Kematian">
                    </div>
                </div>
        
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="file">File Akta Kematian : <code>(.jpg, .jpeg, .png, .pdf)</code></label>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" id="file_path" name="satuan">
                            <input type="file" class="hidden" id="file" name="nama_file" accept=".jpg,.jpeg,.png,.pdf">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-info" id="file_browser"><i class="fa fa-search"></i> Cari</button>
                            </span>
                        </div>
                        <span class="help-block"><code>Kosongkan jika tidak ingin mengubah dokumen. Ukuran maksimal <strong>{{ max_upload(true) }}</strong>.</code></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="tgl_peristiwa">Tanggal Peristiwa</label>
                        <div class="input-group input-group-sm date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input class="form-control input-sm pull-right required tgl_minimal" id="tgl_1" name="tgl_peristiwa" type="text" data-tgl-lebih-besar="#tgl_lapor" value="{{ $sekarang }}">
                        </div>
                    </div>
                </div>
        
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="tgl_lapor">Tanggal Lapor</label>
                        <div class="input-group input-group-sm date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input class="form-control input-sm pull-right tgl_indo required" id="tgl_lapor" name="tgl_lapor" type="text" value="{{ $sekarang }}">
                        </div>
                    </div>
                </div>
        
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="catatan">Catatan Peristiwa</label>
                        <textarea id="catatan" name="catatan" class="form-control input-sm" placeholder="Catatan" rows="5"></textarea>
                    </div>
                </div>
            </div>            
        </div>
    @endif
</div>


@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
            $('#tgl_lahir').datetimepicker({
                format: 'DD-MM-YYYY',
                locale: 'id',
                maxDate: 'now',
            });

            // Mulai Suku
            @if ($status_pantau)
                // Adat select2
                $('#adat').select2({
                    dropdownParent: $('#modal-ubah-biodata').length ? $('#modal-ubah-biodata .modal-content') : undefined,
                    tags: true,
                    minimumInputLength: 2,
                    placeholder: 'Pilih Adat',
                    allowClear: true,
                    language: {
                        inputTooShort: () => 'Ketik minimal 2 karakter',
                        errorLoading: () => 'Gagal memuat data. Kamu tetap bisa ketik manual.',
                        noResults: () => 'Tidak ditemukan. Tekan Enter untuk menambahkan.',
                        removeAllItems: () => 'Hapus data terpilih'
                    },
                    ajax: {
                        transport: function (params, success, failure) {
                            $.ajax(params).then(success).fail(function () {
                                // Jika koneksi ke server Pantau gagal, kirim hasil kosong
                                success({ results: [] });
                            });
                        },
                        url: "{{ config_item('server_pantau') }}/index.php/api/wilayah/adat?token={{ config_item('token_pantau') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                q: params.term || '',
                                page: params.page || 1
                            };
                        },
                        processResults: function (data) {
                            // --- hasil dari API Pantau ---
                            let resultsPantau = [];
                            if (data && Array.isArray(data.results)) {
                                resultsPantau = data.results.map(item => ({
                                    id: item.name,
                                    text: item.name
                                }));
                            }

                            // --- hasil lokal dari $adat_penduduk ---
                            let resultsLokal = [
                                @foreach($adat_penduduk ?? [] as $key => $value)
                                    { id: "{{ $key }}", text: "{{ $key }}" },
                                @endforeach
                            ];

                            // --- gabungkan dan hilangkan duplikat ---
                            let allResults = [...resultsPantau, ...resultsLokal];
                            let uniqueResults = allResults.filter(
                                (v, i, a) => a.findIndex(t => t.id === v.id) === i
                            );

                            return { results: uniqueResults };
                        },
                        cache: true
                    },
                    createTag: function (params) {
                        let term = $.trim(params.term);
                        if (term === '') return null;
                        return {
                            id: term,
                            text: term,
                            newOption: true
                        };
                    },
                    insertTag: function (data, tag) {
                        data.push(tag);
                    }
                });

                // Suku select2, tergantung adat
                $('#suku').select2({
                    dropdownParent: $('#modal-ubah-biodata').length ? $('#modal-ubah-biodata .modal-content') : undefined,
                    tags: true,
                    placeholder: 'Pilih Suku/Etnis',
                    allowClear: true,
                    language: {
                        errorLoading: () => 'Gagal memuat data. Kamu tetap bisa ketik manual.',
                        noResults: () => 'Tidak ditemukan. Tekan Enter untuk menambahkan.',
                        removeAllItems: () => 'Hapus data terpilih'
                    },
                    ajax: {
                        transport: function (params, success, failure) {
                            $.ajax(params).then(success).fail(function () {
                                // Jika koneksi ke Pantau gagal, tetap sukseskan dengan hasil kosong
                                success({ results: [] });
                            });
                        },
                        url: "{{ config_item('server_pantau') }}/index.php/api/wilayah/suku?token={{ config_item('token_pantau') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term || '',
                                page: params.page || 1,
                                wilayah_adat: $('#adat').val() || ''
                            };
                        },
                        processResults: function(data, params) {
                            // teks yang diketik user di select2
                            let term = (params.term || '').toLowerCase();

                            // --- hasil dari API Pantau ---
                            let resultsPantau = (data.results || []).map(item => ({
                                id: item.name,
                                text: item.name
                            }));

                            // --- hasil lokal dari database ---
                            let resultsLokal = [
                                @foreach($suku_penduduk ?? [] as $key => $value)
                                    { id: "{{ $key }}", text: "{{ $key }}" },
                                @endforeach
                            ];

                            // --- hasil enum dari konstanta SukuEnum ---
                            @php
                                $ref = new ReflectionClass(\App\Enums\SukuEnum::class);
                                $consts = $ref->getConstants();
                            @endphp
                            let resultsEnum = [
                                @foreach($consts as $key => $value)
                                    { id: "{{ $value }}", text: "{{ $value }}" },
                                @endforeach
                            ];

                            // ✅ filter data enum sesuai teks pencarian
                            if (term) {
                                resultsEnum = resultsEnum.filter(item => item.text.toLowerCase().includes(term));
                                resultsLokal = resultsLokal.filter(item => item.text.toLowerCase().includes(term));
                                resultsPantau = resultsPantau.filter(item => item.text.toLowerCase().includes(term));
                            }

                            // --- gabungkan semua sumber & hilangkan duplikat ---
                            let allResults = [...resultsPantau, ...resultsLokal, ...resultsEnum];
                            let uniqueResults = allResults.filter(
                                (v, i, a) => a.findIndex(t => t.id === v.id) === i
                            );

                            return { results: uniqueResults, pagination: data.pagination };
                        },

                        cache: true
                    },
                    createTag: function(params) {
                        let term = $.trim(params.term);
                        if (term === '') return null;
                        return {
                            id: term,
                            text: term,
                            newOption: true
                        };
                    },
                    insertTag: function(data, tag) {
                        data.push(tag);
                    }
                });

                // --- Inisialisasi Select2 untuk field Marga ---
                $('#marga').select2({
                    dropdownParent: $('#modal-ubah-biodata').length ? $('#modal-ubah-biodata .modal-content') : undefined,
                    tags: true,
                    placeholder: 'Pilih Marga',
                    allowClear: true,
                    language: {
                        errorLoading: () => 'Gagal memuat data. Kamu tetap bisa ketik manual.',
                        noResults: () => 'Tidak ditemukan. Tekan Enter untuk menambahkan.',
                        removeAllItems: () => 'Hapus data terpilih'
                    },
                    ajax: {
                        url: "{{ config_item('server_pantau') }}/index.php/api/wilayah/marga?token={{ config_item('token_pantau') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term || '',
                                page: params.page || 1,
                                name_suku: $('#suku').val() || ''
                            };
                        },
                        processResults: function(data, params) {
                            // --- hasil dari API pantau
                            let resultsPantau = (data.results || []).map(item => ({
                                id: item.name,
                                text: item.name
                            }));

                            // --- hasil lokal dari $marga_penduduk ---
                            let resultsLokal = [
                                @foreach($marga_penduduk ?? [] as $key => $value)
                                    { id: "{{ $key }}", text: "{{ $key }}" },
                                @endforeach
                            ];

                            // --- gabungkan dan hilangkan duplikat ---
                            let allResults = [...resultsPantau, ...resultsLokal];
                            let uniqueResults = allResults.filter(
                                (v, i, a) => a.findIndex(t => t.id === v.id) === i
                            );

                            return {
                                results: uniqueResults,
                                pagination: data.pagination
                            };
                        },
                        cache: true
                    },
                    createTag: function(params) {
                        let term = $.trim(params.term);
                        if (term === '') return null;
                        return {
                            id: term,
                            text: term,
                            newOption: true
                        };
                    },
                    insertTag: function(data, tag) {
                        data.push(tag);
                    },
                });

                // Pekerja Migran select2
                $('#pekerja_migran').select2({
                    dropdownParent: $('#modal-ubah-biodata').length ? $('#modal-ubah-biodata .modal-content') : undefined,
                    tags: true,
                    minimumInputLength: 0,
                    placeholder: 'Pilih Pekerja Migran',
                    language: {
                        errorLoading: () => 'Gagal memuat data. Kamu tetap bisa ketik manual.',
                        noResults: () => 'Tidak ditemukan. Tekan Enter untuk menambahkan.'
                    },
                    ajax: {
                        transport: function (params, success, failure) {
                            $.ajax(params).then(success).fail(function () {
                                success({ results: [] });
                            });
                        },
                        url: "{{ config_item('server_pantau') }}/index.php/api/wilayah/pekerjaan-pmi?token={{ config_item('token_pantau') }}",
                        // url: "http://pantau.test/api/wilayah/pekerjaan-pmi",
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term || '',
                                page: params.page || 1
                            };
                        },
                        processResults: function (data) {
                            let results = [
                                { id: 'BUKAN PEKERJA MIGRAN', text: 'BUKAN PEKERJA MIGRAN' }
                            ];

                            if (data && Array.isArray(data.results)) {
                                const apiResults = data.results
                                    .filter(item => item.nama && item.nama !== 'BUKAN PEKERJA MIGRAN')
                                    .map(item => ({
                                        id: item.nama,
                                        text: item.nama
                                    }));
                                results = results.concat(apiResults);
                            }

                            return { results: results };
                        },
                        createTag: function (params) {
                            let term = $.trim(params.term);
                            if (term === '') return null;
                            return {
                                id: term,
                                text: term,
                                newOption: true
                            };
                        },
                        insertTag: function (data, tag) {
                            data.push(tag);
                        },
                        cache: true
                    },
                });

                // --- Set initial values ---
                @if ($penduduk && !empty($penduduk['adat']))
                    $('#adat').append(new Option('{{ $penduduk["adat"] }}', '{{ $penduduk["adat"] }}', true, true)).trigger('change');
                @endif
                @if ($penduduk && !empty($penduduk['suku']))
                    $('#suku').append(new Option('{{ $penduduk["suku"] }}', '{{ $penduduk["suku"] }}', true, true)).trigger('change');
                @endif
                @if ($penduduk && !empty($penduduk['marga']))
                    $('#marga').append(new Option('{{ $penduduk["marga"] }}', '{{ $penduduk["marga"] }}', true, true)).trigger('change');
                @endif

            @else
                $('#adat').select2({
                    dropdownParent: $('#modal-ubah-biodata').length ? $('#modal-ubah-biodata .modal-content') : undefined,
                    tags: true,
                    placeholder: 'Pilih Adat',
                    minimumInputLength: 2,
                });
                $('#suku').select2({
                    dropdownParent: $('#modal-ubah-biodata').length ? $('#modal-ubah-biodata .modal-content') : undefined,
                    tags: true,
                    placeholder: 'Pilih Suku/Etnis',
                    minimumInputLength: 2,
                });
                $('#marga').select2({
                    dropdownParent: $('#modal-ubah-biodata').length ? $('#modal-ubah-biodata .modal-content') : undefined,
                    tags: true,
                    placeholder: 'Pilih Marga',
                    minimumInputLength: 2,
                });
                $('#pekerja_migran').select2({
                    dropdownParent: $('#modal-ubah-biodata').length ? $('#modal-ubah-biodata .modal-content') : undefined,
                    tags: true,
                    placeholder: 'Pilih Pekerja Migran',
                    minimumInputLength: 0,
                });
            @endif
            // Selesai Suku

            var addOrRemoveRequiredAttribute = function() {
                var tglsekarang = new Date();
                var tgllahir = parseInt($('#tgl_lahir').val().substring(6, 10));
                var selisih = tglsekarang.getFullYear() - tgllahir;
                var wajib_identitas = $('.wajib_identitas');
                var status_perkawinan = document.getElementById("status_perkawinan").value;
                if (selisih > 16 || (status_perkawinan != '' && status_perkawinan > 1)) {
                    $('#wajib_ktp').text('WAJIB');
                } else {
                    $('#wajib_ktp').text('BELUM WAJIB');
                }
            };

            $("#tgl_lahir").on('change keyup paste click keydown', addOrRemoveRequiredAttribute);
            $("#status_perkawinan").on('change keyup paste click keydown', addOrRemoveRequiredAttribute);
            $(".form-control").on('change keyup paste click keydown select', addOrRemoveRequiredAttribute);

            $('#tag_id_card').focus();

            $("#kk_level").change(function() {
                var selected = $(this).val();

                orang_tua();

                if (selected == 2 || selected == 3) {
                    $("#status_perkawinan").val("2").change();
                } else if (selected == 4 || selected == 6) {
                    $("#status_perkawinan").val("1").change();
                } else {
                    $("#status_perkawinan").val("").change();
                }
            });

            $("select[name='sex']").change();
            $("select[name='status_kawin']").change();
            $("select[name='id_asuransi']").change();

            var cek_nik = '{{ $cek_nik }}';
            $('#nik_sementara').change(function() {
                var nik_sementara_berikut = '{{ $nik_sementara }}';
                var nik_asli = '{{ $penduduk['nik'] }}';
                if ($('#nik_sementara').prop('checked')) {
                    $('#nik').removeClass('nik');
                    if (cek_nik != '0') $('#nik').val(nik_sementara_berikut);
                    $('#nik').prop('readonly', true);
                    $('#tampil_nik').show();
                } else {
                    $('#nik').addClass('nik');
                    $('#nik').val(nik_asli);
                    $('#nik').prop('readonly', false);
                    $('#tampil_nik').hide();
                }
            });

            if (cek_nik == '0') {
                $('#nik_sementara').prop('checked', true);
                $('#nik_sementara').trigger('change');
            }

            show_hide_penduduk_tidak_tetap($('#status_penduduk').val());
            show_hide_status_warga_negara($('#warganegara_id').val());
            show_hide_ktp_el($('#ktp_el').val());

        // Handler untuk perubahan Dusun
        $('#mainform #dusun').change(function() {
            let selectedDusun = $(this).find('option:selected').val();
            let $rwSelect = $('#mainform #rw');
            
            // Reset RW selection
            $rwSelect.val('');
            
            // Disable semua optgroup RW terlebih dahulu
            $rwSelect.find('optgroup').prop('disabled', true);
            
            if (selectedDusun) {
                $('#mainform #rw').closest('div').show();
                
                // Enable optgroup yang sesuai dengan dusun terpilih
                let $activeOptgroup = $rwSelect.find(`optgroup[value="${selectedDusun}"]`);
                $activeOptgroup.prop('disabled', false);
                
                // PINDAHKAN optgroup yang aktif ke posisi setelah option "Pilih RW"
                // Cari option pertama (yang value="")
                let $firstOption = $rwSelect.find('option:first');
                $activeOptgroup.insertAfter($firstOption);
            } else {
                $('#mainform #rw').closest('div').hide();
            }
            
            // Trigger change untuk update RT
            $rwSelect.trigger('change');
        });

        // Handler untuk perubahan RW
        $('#mainform #rw').change(function() {
            let selectedValue = $(this).find('option:selected').val();
            let $rtSelect = $('#mainform #id_cluster');
            
            // Reset RT selection
            $rtSelect.val('');
            
            // Disable semua optgroup RT terlebih dahulu
            $rtSelect.find('optgroup').prop('disabled', true);
            
            if (selectedValue) {
                $('#mainform #id_cluster').closest('div').show();
                
                // Enable optgroup yang sesuai dengan RW terpilih
                let $activeOptgroup = $rtSelect.find(`optgroup[value="${selectedValue}"]`);
                $activeOptgroup.prop('disabled', false);
                
                // DEBUGGING: Log untuk memastikan element ditemukan
                console.log('Selected RW:', selectedValue);
                console.log('Active optgroup found:', $activeOptgroup.length);
                console.log('Active optgroup label:', $activeOptgroup.attr('label'));
                
                // PINDAHKAN optgroup yang aktif ke posisi setelah option "Pilih RT"
                // Gunakan children() untuk hanya ambil direct child, bukan nested
                let $selectChildren = $rtSelect.children();
                let $firstOption = $selectChildren.filter('option[value=""]').first();
                
                console.log('First option found:', $firstOption.length);
                console.log('First option text:', $firstOption.text());
                
                if ($firstOption.length > 0 && $activeOptgroup.length > 0) {
                    // Detach dulu untuk menghindari clone
                    $activeOptgroup.detach();
                    // Insert setelah option pertama
                    $firstOption.after($activeOptgroup);
                    
                    console.log('Optgroup moved after first option');
                } else {
                    console.warn('Cannot move optgroup - first option or active optgroup not found');
                }
            } else {
                $('#mainform #id_cluster').closest('div').hide();
            }
            
            // Trigger change untuk update display
            $rtSelect.trigger('change');
        });

        // Trigger initial change jika ada data yang sudah dipilih
        @if (!$penduduk['id'])
            $('#mainform #dusun').trigger('change');
        @endif

        @if ($jenis_peristiwa == 1)
            $('#status_perkawinan').val('{{ \App\Enums\StatusKawinEnum::BELUMKAWIN }}').trigger('change').prop('disabled', true);
            orang_tua();
        @endif

        });

        $('#mainform').on('reset', function(e) {
            setTimeout(function() {
                $("select[name='sex']").change();
                $("select[name='status_kawin']").change();
                $("select[name='id_asuransi']").change();
            });
        });

        function ubah_sex(sex) {
            var old_foto = $('#old_foto').val();

            (sex == '2') ? $("#isian_hamil").show(): $("#isian_hamil").hide();

            if (old_foto == '') {
                $('#foto').attr("src", AmbilFoto(old_foto, 'kecil_', sex))
            }
        };

        function reset_hamil() {
            setTimeout(function() {
                $('select[name=sex]').change();
            });
        };

        function show_hide_asuransi(asuransi) {
            if (asuransi == '1' || asuransi == '') {
                $('#asuransi_pilihan').hide();
                $('#status_asuransi').hide();
            } else {
                if (asuransi == '99') {
                    $('#label-no-asuransi').text('Nama/nomor Asuransi');
                } else {
                    $('#label-no-asuransi').text('No Asuransi');
                }

                $('#asuransi_pilihan').show();
                $('#status_asuransi').show();
            }
        }

        function AmbilFoto(foto, ukuran = "kecil_", sex) {
            //Jika penduduk ada foto, maka pakai foto tersebut
            //Jika tidak, pakai foto default
            if (foto) {
                ukuran_foto = ukuran || null
                file_foto = '{{ LOKASI_USER_PICT }}' + ukuran_foto + foto;
            } else {
                file_foto = sex == '2' ? '{{ FOTO_DEFAULT_WANITA }}' : '{{ FOTO_DEFAULT_PRIA }}';
            }

            return file_foto;
        }

        function disable_kawin_cerai(status) {
            // Status 1 = belum kawin, 2 = kawin, 3 = cerai hidup, 4 = cerai mati
            switch (status) {
                case '1':
                    $("#akta_perkawinan").attr('disabled', true);
                    $("input[name=tanggalperkawinan]").attr('disabled', true);
                    $("#akta_perceraian").attr('disabled', true);
                    $("input[name=tanggalperceraian]").attr('disabled', true);
                    $('#wajib_ktp').text('BELUM WAJIB');
                    $('#akseptor_kb').hide();
                    break;
                case '2':
                    $("#akta_perkawinan").attr('disabled', false);
                    $("input[name=tanggalperkawinan]").attr('disabled', false);
                    $("#akta_perceraian").attr('disabled', true);
                    $("input[name=tanggalperceraian]").attr('disabled', true);
                    $('#wajib_ktp').text('WAJIB');
                    $('#akseptor_kb').show();
                    break;
                case '3':
                    $("#akta_perkawinan").attr('disabled', false);
                    $("input[name=tanggalperkawinan]").attr('disabled', false);
                    $("#akta_perceraian").attr('disabled', false);
                    $("input[name=tanggalperceraian]").attr('disabled', false);
                    $('#wajib_ktp').text('WAJIB');
                    $('#akseptor_kb').show();
                    break;
                case '4':
                    $("#akta_perkawinan").attr('disabled', false);
                    $("input[name=tanggalperkawinan]").attr('disabled', false);
                    $("#akta_perceraian").attr('disabled', false);
                    $("input[name=tanggalperceraian]").attr('disabled', false);
                    $('#wajib_ktp').text('WAJIB');
                    $('#akseptor_kb').show();
                    break;
            }
        }

        function show_hide_penduduk_tidak_tetap(status) {
            // status 1 = TETAP, 2 = TIDAK TETAP
            if (status == 2) {
                $('#section_penduduk_tidak_tetap').fadeIn();
            } else {
                $('#section_penduduk_tidak_tetap').fadeOut();
            }
        }

        function show_hide_status_warga_negara(warganegaraId) {
            // warganegara_id 1 = WNI, 2 = WNA, 3 = DUA KEWARGANEGARAAN
            if (warganegaraId == 2 || warganegaraId == 3) {
                $('#field_negara_asal').fadeIn();
                $('#field_dokumen_kitas').fadeIn();
            } else {
                $('#field_negara_asal').fadeOut();
                $('#field_dokumen_kitas').fadeOut();
            }
        }

        function show_hide_ktp_el(status) {
            // status 1 = BELUM MEMILIKI KTP-EL, 2 = SUDAH MEMILIKI KTP-EL
            if (status == 2) {
                $('#section_ktp_el').fadeIn();
            } else {
                $('#section_ktp_el').fadeOut();
            }
        }

        function orang_tua() {
            var id_kk = $('#id_kk').val();
            var kk_level = $('#kk_level').val();
            var jenis_peristiwa = '{{ $jenis_peristiwa }}';
            
            // Untuk bayi baru lahir
            if (jenis_peristiwa == 1) {
                // Jika SHDK adalah Anak (kk_level == 4), ambil data Kepala Keluarga dan Istri
                if (id_kk && kk_level == 4) {
                    $('#ayah_nik').val(@json($data_ayah['nik'] ?? '')).prop('readonly', true);
                    $('#nama_ayah').val(@json($data_ayah['nama'] ?? '')).prop('readonly', true);
                    $('#ibu_nik').val(@json($data_ibu['nik'] ?? '')).prop('readonly', true);
                    $('#nama_ibu').val(@json($data_ibu['nama'] ?? '')).prop('readonly', true);
                } else {
                    // Jika SHDK selain Anak (Cucu, Famili Lain), kosongkan dan biarkan input manual
                    $('#ayah_nik').val('').prop('readonly', false);
                    $('#nama_ayah').val('').prop('readonly', false);
                    $('#ibu_nik').val('').prop('readonly', false);
                    $('#nama_ibu').val('').prop('readonly', false);
                }
            } else {
                // Untuk bukan bayi baru lahir, gunakan logika lama
                if (id_kk && kk_level == 4) {
                    $('#ayah_nik').val(@json($data_ayah['nik'] ?? '')).prop('readonly', true);
                    $('#nama_ayah').val(@json($data_ayah['nama'] ?? '')).prop('readonly', true);
                    $('#ibu_nik').val(@json($data_ibu['nik'] ?? '')).prop('readonly', true);
                    $('#nama_ibu').val(@json($data_ibu['nama'] ?? '')).prop('readonly', true);
                } else {
                    $('#ayah_nik').val('{{ $penduduk['ayah_nik'] }}').prop('readonly', false);
                    $('#nama_ayah').val('{{ $penduduk['nama_ayah'] }}').prop('readonly', false);
                    $('#ibu_nik').val('{{ $penduduk['ibu_nik'] }}').prop('readonly', false);
                    $('#nama_ibu').val('{{ $penduduk['nama_ibu'] }}').prop('readonly', false);
                }
            }
        }
</script>
@endpush