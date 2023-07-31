<h5><b>Sumber Data</b></h5>
<div class="table-responsive">
    <table class="table table-hover table-striped sumber-data">
        <tbody>
            <tr style="font-weight: bold;">
                <td>Data Kategori</td>
                <td>Pilihan</td>
            </tr>

            <tr>
                <td>Data Individu</td>
                <td>
                    @php $desa_pend = strtoupper(setting('sebutan_desa')) @endphp
                    <select id="data_utama" class="form-control input-sm kategori select2"
                        name="kategori_data_utama[{{$item}}]">
                        <option value="1" selected>PENDUDUK {{
                            $desa_pend }}
                        </option>
                    </select>
                </td>
            </tr>

            <tr class="warga_desa">
                <td>Jenis Kelamin</td>
                <td>
                    <select class="form-control input-sm kategori select2" name="kategori_individu_sex[{{$item}}]">
                        <option value="">SEMUA</option>
                        @foreach ($form_isian['daftar_jenis_kelamin'] as $key => $data)
                        <option value="{{ $key }}" @selected($key==$suratMaster->form_isian->{{$item}}->sex)>{{ $data }}
                        </option>
                        @endforeach
                    </select>
                </td>
            </tr>

            <tr class="warga_desa">
                <td>Jenis Peristiwa</td>
                <td>
                    <select class="form-control input-sm select2 kategori"
                        name="kategori_individu_status_dasar[{{$item}}]">
                        <option value="">SEMUA</option>
                        @foreach ($form_isian['daftar_status_dasar'] as $key => $data)
                        <option value="{{ $key }}" @selected($key==$suratMaster->form_isian->{{$item}}->status_dasar)>{{ $data }}
                        </option>
                        @endforeach
                    </select>
                </td>
            </tr>

            <tr class="warga_desa">
                <td>Status Hubungan Dalam Keluarga (SHDK)</td>
                <td>
                    <select id="individu_kk_level" class="form-control input-sm select2 kategori"
                        name="kategori_individu_kk_level[{{$item}}]">
                        <option value="">SEMUA</option>
                        @foreach ($form_isian['daftar_shdk'] as $key => $data)
                        <option value="{{ $key }}" @selected($key==$suratMaster->form_isian->{{$item}}->kk_level)>{{ $data }}
                        </option>
                        @endforeach
                    </select>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<hr>