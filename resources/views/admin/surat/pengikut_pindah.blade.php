<div class="form-group pria_luar_desa subtitle_head">
    <label class="col-sm-3 text-right"><strong>DATA KELUARGA / KK</strong></label>
</div>
<div class="form-group">
    <label for="keperluan" class="col-sm-3 control-label">Keluarga</label>
    <div class="col-sm-9">
        <div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover tabel-daftar">
                    <thead class="bg-gray disabled color-palette">
                        <tr>
                            <th><input type="checkbox" id="checkall"/></th>
                            <th>No</th>
                            <th>NIK</th>
                            <th>KTP Berlaku S/D</th>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                            <th>Umur</th>                            
                            <th>Status Kawin</th>                                                        
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengikut_pindah as $key => $data)
                        <tr>
                            <td>
                                <input type="checkbox" name="id_pengikut_pindah[]" value="{{ $data->id }}" />
                            </td>
                            <td class="padat">{{ $key + 1 }}</td>
                            <td class="padat">{{ $data->nik }}</td>
                            <td nowrap>Seumur Hidup</td>
                            <td nowrap>{{ $data->nama }}</td>
                            <td nowrap>{{ $data->jenisKelamin->nama }}</td>                            
                            <td nowrap>{{ $data->umur }}</td>
                            <td nowrap>{{ $data->statusKawin->nama }}</td>                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>