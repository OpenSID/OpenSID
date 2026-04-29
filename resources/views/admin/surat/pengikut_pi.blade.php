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
                            <td nowrap>{{ $data->jenis_kelamin }}</td>
                            <td nowrap>{{ $data->tempatlahir }}, {{ tgl_indo($data->tanggallahir) }}</td>
                            <td nowrap>{{ $data->penduduk_hubungan }}</td>
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
                                {{ $data?->pendidikan_kk }}
                                <input name="pi[{{ $data->nik }}][pendidikan_semula]" type="hidden" class="form-control input-sm" value="{{ $data?->pendidikan_kk }}" />
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
        {{-- <div class="table-responsive">
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
                            <td nowrap>{{ $data->penduduk_hubungan }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div> --}}

        <div class="table-responsive">
            <table class="table table-bordered dataTable table-hover pi">
                <thead class="bg-gray disabled color-palette">
                    <tr>
                        <th rowspan="3" style="text-align: center">No</th>
                        <th colspan="6" style="text-align: center">Elemen Data</th>
                        <th rowspan="3" style="text-align: center">Keterangan</th>
                    </tr>
                    <tr>
                        <th colspan="3" style="text-align: center">Agama</th>
                        <th colspan="3" style="text-align: center">Lainnya, yaitu:
                            <select class="form-control input-sm select2" name="lainnya[]" id="lainnya" multiple="multiple" data-placeholder="Pilih Data Lainnya" style="width: 100%;">
                                @foreach (\App\Enums\PerubahanDataPiEnum::valuesToUpper() as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </th>
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
                                {{ $data?->agama }}
                                <input name="pi[{{ $data->nik }}][agama_semula]" type="hidden" class="form-control input-sm" value="{{ $data?->agama }}" />
                            </td>
                            <td>
                                <input name="pi[{{ $data->nik }}][agama_menjadi]" type="text" class="form-control input-sm" disabled />
                            </td>
                            <td>
                                <input name="pi[{{ $data->nik }}][agama_dasar_perubahan]" type="text" class="form-control input-sm" disabled />
                            </td>
                            <td>
                                <select class="form-control input-sm select2 lainnya_pilihan" data-placeholder="Pilih Data" style="width: 100%;" disabled>
                                    <option></option>
                                </select>
                                <input name="pi[{{ $data->nik }}][lainnya_semula]" type="text" class="form-control input-sm" disabled style="margin-top: 5px;" />
                            </td>
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
<script>
    const dataPendudukPi = @json($pengikut_pi->keyBy('id'));
    const fieldMap = {
        'nama': 'nama',
        'golongan_darah_id': 'golongan_darah',
        'alamat_sekarang': 'alamat_wilayah',
        'nama_ayah': 'nama_ayah',
        'nama_ibu': 'nama_ibu',
        'sex': 'jenis_kelamin',
        'tanggallahir': 'tanggallahir',
        'status_kawin': 'status_perkawinan',
        'tanggalperkawinan': 'tanggalperkawinan',
        'warganegara_id': 'warga_negara',
        'dokumen_pasport': 'dokumen_pasport'
    };

    $(document).ready(function() {
        $('#lainnya').select2({
            placeholder: "Pilih Data Lainnya"
        });
        $('.lainnya_pilihan').select2({
            placeholder: "Pilih Data Untuk Diubah"
        });

        $('#lainnya').on('change', function() {
            const selectedOptions = $(this).find('option:selected');
            const optionsHtml = selectedOptions.map(function() {
                return `<option value="${$(this).val()}">${$(this).text()}</option>`;
            }).get();

            $('.lainnya_pilihan').each(function() {
                const currentValue = $(this).val();
                // Tambahkan placeholder dan opsi baru
                $(this).html('<option></option>' + optionsHtml.join(''));

                // Periksa apakah nilai saat ini masih valid
                const isValid = selectedOptions.filter(function() {
                    return $(this).val() === currentValue;
                }).length > 0;

                if (isValid) {
                    $(this).val(currentValue).trigger('change');
                } else {
                    $(this).val(null).trigger('change');
                }
            });
        });

        $('.lainnya_pilihan').on('change', function() {
            const selectElement = $(this);
            const selectedField = selectElement.val();
            const tr = selectElement.closest('tr');
            const residentId = tr.data('row');
            const residentData = dataPendudukPi[residentId];
            const nilaiSemulaInput = tr.find('input[name="pi[' + residentData.nik + '][lainnya_semula]"]');

            if (!residentData || !selectedField) {
                nilaiSemulaInput.val('');
                return;
            }

            const propertyName = fieldMap[selectedField];
            let displayValue = residentData[propertyName] || '';

            // Format tanggal jika ada
            if ((selectedField === 'tanggallahir' || selectedField === 'tanggalperkawinan') && displayValue) {
                const date = new Date(displayValue);
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const year = date.getFullYear();
                displayValue = `${day}-${month}-${year}`;
            }

            nilaiSemulaInput.val(displayValue);
        });

        // Fungsi untuk menampilkan/menyembunyikan kolom "Semula" pada Agama
        function toggleAgamaSemula(row) {
            const menjadi = row.find('input[name*="[agama_menjadi]"]').val().trim();
            const dasar = row.find('input[name*="[agama_dasar_perubahan]"]').val().trim();
            const textSemula = row.find('.agama_semula_text');

            (menjadi !== '' && dasar !== '') ? textSemula.show() : textSemula.hide();
        }

        // Event listener untuk input pada kolom "Menjadi" dan "Dasar Perubahan"
        $('.pi').on('input', 'input[name*="[agama_menjadi]"], input[name*="[agama_dasar_perubahan]"]', function() {
            toggleAgamaSemula($(this).closest('tr'));
        });

        // Pastikan "Semula" kembali tersembunyi jika checkbox di-uncheck
        $('input[name="id_pengikut_pi[]"]').on('change', function() {
            if (!$(this).is(':checked')) {
                $('.pi tr[data-row="' + $(this).val() + '"]').find('.agama_semula_text').hide();
            }
        });
    });
</script>
