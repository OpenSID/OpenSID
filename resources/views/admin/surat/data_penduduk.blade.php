@if ($individu)
    @include('admin.surat.konfirmasi_pemohon')

    @if ($anggota)
        <div class="form-group">
            <label for="anggota" class="col-sm-3 control-label">Data Keluarga / KK</label>
            <div class="col-sm-8">
                <a id="showData" class="btn btn-social btn-danger btn-sm" onclick="$(this).closest('.form-group').next('#kel').removeClass('hide');$(this).next('a').removeClass('hide');$(this).toggleClass('hide ')"><i class="fa fa-search-plus"></i>
                    Tampilkan</a>
                <a id="hideData" class="btn btn-social btn-danger btn-sm hide" onclick="$(this).closest('.form-group').next('#kel').addClass('hide');$(this).prev('a').removeClass('hide');$(this).toggleClass('hide ')"><i class="fa fa-search-minus"></i>
                    Sembunyikan</a>
            </div>
        </div>

        <div id="kel" class="form-group hide">
            <label for="pengikut" class="col-sm-3 control-label"></label>
            <div class="col-sm-8">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover tabel-daftar">
                        <thead class="bg-gray disabled color-palette">
                            <tr>
                                <th>No</th>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Jenis Kelamin</th>
                                <th>Tempat Tanggal Lahir</th>
                                <th>Hubungan</th>
                                <th>Status Kawin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($anggota as $key => $data)
                                <tr>
                                    <td class="padat">{{ $key + 1 }}</td>
                                    <td class="padat">{{ $data->nik }}</td>
                                    <td nowrap>{{ $data->nama }}</td>
                                    <td nowrap>{{ $data->jenisKelamin->nama }}</td>
                                    <td nowrap>{{ $data->tempatlahir }},
                                        {{ tgl_indo($data->tanggallahir) }}
                                    </td>
                                    <td nowrap>{{ $data->pendudukHubungan->nama }}</td>
                                    <td nowrap>{{ $data->statusKawin->nama }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="form-group">
            <label for="keperluan" class="col-sm-3 control-label">Data Keluarga / KK</label>
            <div class="col-sm-8">
                <label class="text-red small">Penduduk yang dipilih bukan
                    {{ \App\Enums\SHDKEnum::valueOf(\App\Enums\SHDKEnum::KEPALA_KELUARGA) }}</label>
            </div>
        </div>
    @endif

    @if ($kategori == 'individu')
        <div class="row jar_form">
            <label for="nomor" class="col-sm-3"></label>
            <div class="col-sm-8">
                <input class="required" type="hidden" name="nik" value="{{ $individu['id'] }}">
            </div>
        </div>
    @else
        <div class="row jar_form">
            <label for="nomor" class="col-sm-3"></label>
            <div class="col-sm-8">
                <input class="required" type="hidden" name="id_pend_{{ $kategori }}" value="{{ $individu['id'] }}">
            </div>
        </div>
    @endif

    @includeWhen(isset($pengikut), 'admin.surat.pengikut')
    @includeWhen(isset($pengikut_kis), 'admin.surat.pengikut_kis')
    @includeWhen(isset($pengikut_pindah), 'admin.surat.pengikut_pindah')
@endif

@includeWhen(isset($peristiwa), 'admin.surat.peristiwa')

@if ($pasangan)
    @php
        $individu = $pasangan;
        $list_dokumen = $list_dokumen_pasangan;
    @endphp
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:10px;padding-top:10px;padding-bottom:10px"><strong>DATA {{ $pasangan->sex == 1 ? 'SUAMI' : 'ISTRI' }}</strong></label>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">NIK</label>
        <div class="col-sm-8">
            <input type="text" class="form-control input-sm" value="{{ $pasangan->nik }}" disabled>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Nama</label>
        <div class="col-sm-8">
            <input type="text" class="form-control input-sm" value="{{ $pasangan->nama }}" disabled>
        </div>
    </div>
    @include('admin.surat.konfirmasi_pemohon')
@endif

@if ($ayah)
    @php
        $individu = $ayah;
        $list_dokumen = $list_dokumen_ayah;
    @endphp
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:10px;padding-top:10px;padding-bottom:10px"><strong>DATA AYAH</strong></label>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">NIK</label>
        <div class="col-sm-8">
            <input type="text" class="form-control input-sm" value="{{ $ayah->nik }}" disabled>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Nama</label>
        <div class="col-sm-8">
            <input type="text" class="form-control input-sm" value="{{ $ayah->nama }}" disabled>
        </div>
    </div>
    @include('admin.surat.konfirmasi_pemohon')
@endif

@if ($ibu)
    @php
        $individu = $ibu;
        $list_dokumen = $list_dokumen_ibu;
    @endphp
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon" style="margin-top:10px;padding-top:10px;padding-bottom:10px"><strong>DATA IBU</strong></label>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">NIK</label>
        <div class="col-sm-8">
            <input type="text" class="form-control input-sm" value="{{ $ibu->nik }}" disabled>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">Nama</label>
        <div class="col-sm-8">
            <input type="text" class="form-control input-sm" value="{{ $ibu->nama }}" disabled>
        </div>
    </div>
    @include('admin.surat.konfirmasi_pemohon')
@endif
