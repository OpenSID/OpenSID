<div class="form-group subtitle_head">
    <label class="col-sm-3 control-label"><strong>DATA KELUARGA / KK : PERUBAHAN PENDIDIKAN DAN PEKERJAAN</strong></label>
</div>

<div class="form-group">
    <label for="nomor" class="col-sm-3 control-label">Keluarga</label>
    <div class="col-sm-8">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover tabel-daftar">
                <thead class="bg-gray disabled color-palette">
                    <tr>
                        <th><input type="checkbox" id="checkall" onclick="checkAll()" /></th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Jenis Kelamin</th>
                        <th>Tempat, Tanggal Lahir</th>
                        <th>Hubungan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengikut_pi as $key => $data)
                        <tr>
                            <td class="padat">
                                <input type="checkbox" name="id_pengikut_pi[]" value="{{ $data->id }}" onchange="pilihAnggotaPiPertama(this)" />
                            </td>
                            <td class="padat">{{ $data->nik }}</td>
                            <td nowrap>{{ $data->nama }}</td>
                            <td nowrap>{{ $data->jenisKelamin->nama }}</td>
                            <td nowrap>{{ $data->tempatlahir }}, {{ tgl_indo($data->tanggallahir) }}</td>
                            <td nowrap>{{ $data->pendudukHubungan->nama }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered dataTable table-hover pi">
                <thead class="bg-gray disabled color-palette">
                    <tr>
                        <th rowspan="3" style="text-align: center">No</th>
                        <th colspan="6" style="text-align: center">Elemen Data</th>
                        <th rowspan="3" style="text-align: center">Keterangan</th>
                    </tr>
                    <tr>
                        <th colspan="3" style="text-align: center">Pendidikan</th>
                        <th colspan="3" style="text-align: center">Pekerjaan </th>
                    </tr>
                    <tr>
                        <th style="text-align: center">Semula</th>
                        <th style="text-align: center">Menjadi</th>
                        <th style="text-align: center">Dasar Perubahan</th>
                        <th style="text-align: center">Semula</th>
                        <th style="text-align: center">Menjadi</th>
                        <th style="text-align: center">Dasar Perubahan</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 0;
                    @endphp
                    @foreach ($pengikut_pi as $key => $data)
                        @php
                            $i++;
                        @endphp
                        <tr data-row="{{ $data->id }}">
                            <td style="text-align: center;" class="padat">{{ $i }}</td>
                            <td>
                                {{ $data?->pendidikan_k_k }}
                                <input name="pi[{{ $data->nik }}][pendidikan_semula]" type="hidden" class="form-control input-sm" value="{{ $data?->pendidikan_k_k }}" />
                            </td>
                            <td>
                                <input name="pi[{{ $data->nik }}][pendidikan_menjadi]" type="text" class="form-control input-sm" disabled />
                            </td>
                            <td>
                                <input name="pi[{{ $data->nik }}][pendidikan_dasar_perubahan]" type="text" class="form-control input-sm" disabled />
                            </td>
                            <td>
                                {{ $data?->pekerjaan?->nama }}
                                <input name="pi[{{ $data->nik }}][pekerjaan_semula]" type="hidden" class="form-control input-sm" value="{{ $data?->pekerjaan?->nama }}" />
                            </td>
                            <td>
                                <input name="pi[{{ $data->nik }}][pekerjaan_menjadi]" type="text" class="form-control input-sm" disabled />
                            </td>
                            <td>
                                <input name="pi[{{ $data->nik }}][pekerjaan_dasar_perubahan]" type="text" class="form-control input-sm" disabled />
                            </td>
                            <td>
                                <input name="pi[{{ $data->nik }}][keterangan]" type="text" class="form-control input-sm" disabled />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="form-group subtitle_head">
    <label class="col-sm-3 control-label"><strong>DATA KELUARGA / KK : PERUBAHAN AGAMA DAN LAINNYA</strong></label>
</div>

<div class="form-group">
    <label for="nomor" class="col-sm-3 control-label">Keluarga</label>
    <div class="col-sm-8">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover tabel-daftar">
                <thead class="bg-gray disabled color-palette">
                    <tr>
                        <th><input type="checkbox" id="checkall" onclick="checkAll()" /></th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Jenis Kelamin</th>
                        <th>Tempat, Tanggal Lahir</th>
                        <th>Hubungan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengikut_pi as $key => $data)
                        <tr>
                            <td class="padat">
                                <input type="checkbox" name="id_pengikut_pi[]" value="{{ $data->id }}" onchange="pilihAnggotaPiDua(this)" />
                            </td>
                            <td class="padat">{{ $data->nik }}</td>
                            <td nowrap>{{ $data->nama }}</td>
                            <td nowrap>{{ $data->jenisKelamin->nama }}</td>
                            <td nowrap>{{ $data->tempatlahir }}, {{ tgl_indo($data->tanggallahir) }}</td>
                            <td nowrap>{{ $data->pendudukHubungan->nama }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered dataTable table-hover pi2">
                <thead class="bg-gray disabled color-palette">
                    <tr>
                        <th rowspan="3" style="text-align: center">No</th>
                        <th colspan="6" style="text-align: center">Elemen Data</th>
                        <th rowspan="3" style="text-align: center">Keterangan</th>
                    </tr>
                    <tr>
                        <th colspan="3" style="text-align: center">Agama</th>
                        <th colspan="3" style="text-align: center">Lainnya, yaitu: <a href="#">Pilih</a></th>
                    </tr>
                    <tr>
                        <th style="text-align: center">Semula</th>
                        <th style="text-align: center">Menjadi</th>
                        <th style="text-align: center">Dasar Perubahan</th>
                        <th style="text-align: center">Semula</th>
                        <th style="text-align: center">Menjadi</th>
                        <th style="text-align: center">Dasar Perubahan</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 0;
                    @endphp
                    @foreach ($pengikut_pi as $key => $data)
                        @php
                            $i++;
                        @endphp
                        <tr data-row="{{ $data->id }}">
                            <td style="text-align: center;" class="padat">{{ $i }}</td>
                            <td>
                                {{ $data?->agama?->nama }}
                                <input name="pi[{{ $data->nik }}][agama_semula]" type="hidden" class="form-control input-sm" />
                            </td>
                            <td>
                                <input name="pi[{{ $data->nik }}][agama_menjadi]" type="text" class="form-control input-sm" disabled />
                            </td>
                            <td>
                                <input name="pi[{{ $data->nik }}][agama_dasar_perubahan]" type="text" class="form-control input-sm" disabled />
                            </td>
                            <td><a href="#">Pilih</a></td>
                            <td>
                                <input name="pi[{{ $data->nik }}][lainnya_menjadi]" type="text" class="form-control input-sm" disabled />
                            </td>
                            <td>
                                <input name="pi[{{ $data->nik }}][lainnya_dasar_perubahan]" type="text" class="form-control input-sm" disabled />
                            </td>
                            <td>
                                <input name="pi[{{ $data->nik }}][keterangan]" type="text" class="form-control input-sm" disabled />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
