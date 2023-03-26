@include('admin.pengaturan_surat.asset_tinymce')

<div class="tab-pane" id="template-surat">

    @include('admin.pengaturan_surat.kembali')

    <div class="box-body">
        <div class="form-group">
            <textarea name="template_desa" class="form-control input-sm editor required">{{ $suratMaster->template_desa ?? $suratMaster->template }}</textarea>
        </div>
    </div>
</div>
<div class="tab-pane" id="form-isian">

    @include('admin.pengaturan_surat.kembali')

    <div class="box-body">
        <h5><b>Sumber Data</b></h5>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <tbody>
                    <tr style="font-weight: bold;">
                        <td>Data Kategori</td>
                        <td>Pilihan</td>
                    </tr>
                    <tr>
                        <td>Data Individu</td>
                        <td>
                            @php $desa_pend = strtoupper(setting('sebutan_desa')) @endphp
                            <select class="form-control input-sm select2" name="data_utama">
                                <option value="1" @selected(1 == $suratMaster->form_isian->data)>PENDUDUK {{ $desa_pend }}
                                </option>
                                <option value="2" @selected(2 == $suratMaster->form_isian->data)>PENDUDUK LUAR {{ $desa_pend }}
                                </option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Jenis Kelamin</td>
                        <td>
                            <select class="form-control input-sm select2" name="individu_sex">
                                <option value="">SEMUA</option>
                                @foreach ($form_isian['daftar_jenis_kelamin'] as $key => $data)
                                    <option value="{{ $key }}" @selected($key == $suratMaster->form_isian->individu->sex)>{{ $data }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td>Jenis Peristiwa</td>
                        <td>
                            <select class="form-control input-sm select2" name="individu_status_dasar">
                                <option value="">SEMUA</option>
                                @foreach ($form_isian['daftar_status_dasar'] as $key => $data)
                                    <option value="{{ $key }}" @selected($key == $suratMaster->form_isian->individu->status_dasar)>{{ $data }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td>Status Hubungan Dalam Keluarga (SHDK)</td>
                        <td>
                            <select class="form-control input-sm select2" name="individu_kk_level">
                                <option value="">SEMUA</option>
                                @foreach ($form_isian['daftar_shdk'] as $key => $data)
                                    <option value="{{ $key }}" @selected($key == $suratMaster->form_isian->individu->kk_level)>{{ $data }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <hr>

        {{-- include kode isian --}}
        @include('admin.pengaturan_surat.kode_isian')

    </div>
</div>
