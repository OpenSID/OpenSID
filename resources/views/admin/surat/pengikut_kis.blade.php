<div class="form-group pria_luar_desa subtitle_head">
    <label class="col-sm-3 control-label"><strong>DATA KELUARGA / KK</strong></label>
</div>
<div class="form-group">
    <label for="keperluan" class="col-sm-3 control-label">Keluarga</label>
    <div class="col-sm-9">
        <div>
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
                        @foreach ($pengikut_kis as $key => $data)
                            <tr>
                                <td>
                                    <input type="checkbox" name="id_pengikut_kis[]" value="{{ $data->id }}" onchange="pilihAnggota(this)" />
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
        </div>
    </div>
</div>

<div class="form-group subtitle_head">
    <label class="col-sm-3 control-label"><strong>DATA KELUARGA DI KARTU KIS</strong></label>
</div>

<div class="form-group">
    <label for="nomor" class="col-sm-3 control-label">Keluarga</label>
    <div class="col-sm-8">
        <div class="table-responsive">
            <table class="table table-bordered dataTable table-hover kis">
                <thead class="bg-gray disabled color-palette">
                    <tr>
                        <th>No</th>
                        <th>No. Kartu</th>
                        <th>Nama di Kartu</th>
                        <th>NIK</th>
                        <th>Alamat di Kartu</th>
                        <th>Tanggal Lahir</th>
                        <th>Faskes Tingkat I</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 0;
                    @endphp
                    @foreach ($pengikut_kis as $key => $data)
                        @php
                            $i++;
                        @endphp
                        <tr data-row="{{ $data->id }}">
                            <td width="7%"> <input type="text" class="form-control input-sm" value="{{ $i }}" readonly /></td>
                            <td> <input name="kis[{{ $data->nik }}][kartu]" type="text" class="form-control input-sm" disabled /></td>
                            <td> <input name="kis[{{ $data->nik }}][nama]" type="text" class="form-control input-sm" disabled /></td>
                            <td> <input name="kis[{{ $data->nik }}][nik]" type="text" class="form-control input-sm" disabled /></td>
                            <td> <input name="kis[{{ $data->nik }}][alamat]" type="text" class="form-control input-sm" disabled /></td>
                            <td>
                                <input class="form-control input-sm datepicker" name="kis[{{ $data->nik }}][tanggallahir]" type="text" disabled />
                            </td>
                            <td> <input name="kis[{{ $data->nik }}][faskes]" type="text" class="form-control input-sm" disabled /></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
