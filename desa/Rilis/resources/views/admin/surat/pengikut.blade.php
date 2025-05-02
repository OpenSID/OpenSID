<div class="form-group">
    <label for="keperluan" class="col-sm-3 control-label">Anak usia dibawah 18 tahun</label>
    <div class="col-sm-9">
        <div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover tabel-daftar">
                    <thead class="bg-gray disabled color-palette">
                        <tr>
                            <th class="padat"><input type="checkbox" id="checkall" onclick="checkAll()" /></th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                            <th>Tempat Lahir</th>
                            <th>Tanggal Lahir</th>
                            <th>SHDK</th>
                            <th>Umur</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengikut as $key => $data)
                            <tr>
                                <td class="padat">
                                    <input type="checkbox" name="id_pengikut[]" value="<?= $data->id ?>" onchange="ket_($(this).is(':unchecked'),'<?= $data->id ?>');" />
                                </td>
                                <td class="padat">{{ $data->nik }}</td>
                                <td nowrap>{{ $data->nama }}</td>
                                <td nowrap>{{ $data->jenisKelamin->nama }}</td>
                                <td nowrap>{{ $data->tempatlahir }}</td>
                                <td nowrap>{{ tgl_indo($data->tanggallahir) }}</td>
                                <td nowrap>{{ $data->pendudukHubungan->nama }}</td>
                                <td nowrap>{{ $data->umur }}</td>
                                <td><input id="ket_<?= $data->id ?>" name="ket_<?= $data->id ?>" value="" disabled="disabled"></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        function ket_(centang, urut) {
            if (centang) {
                $('#ket_' + urut).attr('disabled', 'disabled');
                $('#ket_' + urut).val('');
            } else {
                $('#ket_' + urut).removeAttr('disabled');
            }
        }
    </script>
@endpush
