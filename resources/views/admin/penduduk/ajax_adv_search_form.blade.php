@include('admin.layouts.components.form_modal_validasi')
<form method="post" action="{{ $form_action }}" id="validasi">
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12">
                <label for="nama">Umur</label>
            </div>
            @if ($input_umur)
                <div class="col-sm-5">
                    <div class="form-group">
                        <input
                            class="form-control input-sm bilangan"
                            maxlength="3"
                            type="text"
                            placeholder="Dari"
                            id="umur_min"
                            name="umur_min"
                            value="{{ $umur_min }}"
                        ></input>
                    </div>
                </div>

                <div class="col-sm-5">
                    <div class="form-group">
                        <input
                            id="umur_max"
                            class="form-control input-sm bilangan"
                            maxlength="3"
                            type="text"
                            placeholder="Sampai"
                            name="umur_max"
                            value="{{ $umur_max }}"
                        ></input>
                    </div>
                </div>

                <div class="col-sm-2">
                    <div class="form-group">
                        <select class="form-control input-sm select2" id="umur" name="umur">
                            <option value="tahun" @selected($umur == 'tahun')>Tahun</option>
                            <option value="bulan" @selected($umur == 'bulan')>Bulan</option>
                        </select>
                    </div>
                </div>
            @endif

            @if ($list_pekerjaan)
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="pekerjaan">Pekerjaan</label>
                        <select class="form-control input-sm select2" id="pekerjaan_id" name="pekerjaan_id">
                            <option value=""> -- </option>
                            @foreach ($list_pekerjaan as $key => $item)
                                <option value="{{ $key }}" @selected($pekerjaan_id == $key)>{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            @if ($list_status_kawin)
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="status_dasar">Status Perkawinan</label>
                        <select class="form-control input-sm select2" id="status_kawin" name="status_kawin">
                            <option value=""> -- </option>
                            @foreach ($list_status_kawin as $key => $item)
                                <option value="{{ $key }}" @selected($status == $key)>{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            @if ($list_agama)
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="agama">Agama</label>
                        <select class="form-control input-sm select2" id="agama" name="agama">
                            <option value=""> -- </option>
                            @foreach ($list_agama as $key => $item)
                                <option value="{{ $key }}" @selected($agama == $key)>{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            @if ($list_pendidikan)
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="pendidikan_sedang_id">Pendidikan Sedang Ditempuh</label>
                        <select class="form-control input-sm select2" id="pendidikan_sedang_id" name="pendidikan_sedang_id">
                            <option value=""> -- </option>
                            @foreach ($list_pendidikan as $key => $item)
                                <option value="{{ $key }}" @selected($pendidikan_sedang_id == $key)>{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            @if ($list_pendidikan_kk)
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="pendidikan_kk_id">Pendidikan Dalam KK</label>
                        <select class="form-control input-sm select2" id="pendidikan_kk_id" name="pendidikan_kk_id">
                            <option value=""> -- </option>
                            @foreach ($list_pendidikan_kk as $key => $item)
                                <option value="{{ $key }}" @selected($pendidikan_kk_id == $key)>{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            @if ($list_status_penduduk)
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="status_penduduk">Status Penduduk</label>
                        <select class="form-control input-sm select2" id="status_penduduk" name="status_penduduk">
                            <option value=""> -- </option>
                            @foreach ($list_status_penduduk as $key => $item)
                                <option value="{{ $key }}" @selected($status_penduduk == $key)>{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            @if ($list_sex)
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="sex">Jenis Kelamin</label>
                        <select class="form-control input-sm select2" id="sex" name="sex">
                            <option value=""> -- </option>
                            @foreach ($list_sex as $key => $item)
                                <option value="{{ $key }}" @selected($sex == $key)>{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            @if ($list_status_dasar)
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="status_dasar">Status Dasar</label>
                        <select class="form-control input-sm select2" id="status_dasar" name="status_dasar">
                            <option value=""> -- </option>
                            @foreach ($list_status_dasar as $key => $item)
                                <option value="{{ $key }}" @selected($status_dasar == $key)>{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            @if ($list_cacat)
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="cacat">Cacat</label>
                        <select class="form-control input-sm select2" id="cacat" name="cacat">
                            <option value=""> -- </option>
                            @foreach ($list_cacat as $key => $item)
                                <option value="{{ $key }}" @selected($cacat == $key)>{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            @if ($list_cara_kb)
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="cara_kb_id">Cara KB</label>
                        <select class="form-control input-sm select2" id="cara_kb_id" name="cara_kb_id">
                            <option value=""> -- </option>
                            @foreach ($list_cara_kb as $key => $item)
                                <option value="{{ $key }}" @selected($cara_kb_id == $key)>{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            @if ($list_status_ktp)
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="status_ktp">Status KTP</label>
                        <select class="form-control input-sm select2" id="status_ktp" name="status_ktp">
                            <option value=""> -- </option>
                            @foreach ($list_status_ktp as $key => $item)
                                <option value="{{ $key }}" @selected($status_ktp == $key)>{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            @if ($list_asuransi)
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="id_asuransi">Asuransi</label>
                        <select class="form-control input-sm select2" id="id_asuransi" name="id_asuransi">
                            <option value=""> -- </option>
                            @foreach ($list_asuransi as $key => $item)
                                <option value="{{ $key }}" @selected($id_asuransi == $key)>{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            @if ($list_warganegara)
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="warganegara">Warga Negara</label>
                        <select class="form-control input-sm select2" id="warganegara" name="warganegara">
                            <option value=""> -- </option>
                            @foreach ($list_warganegara as $key => $item)
                                <option value="{{ $key }}" @selected($warganegara == $key)>{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            @if ($list_golongan_darah)
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="golongan_darah">Golongan Darah</label>
                        <select class="form-control input-sm select2" id="golongan_darah" name="golongan_darah">
                            <option value=""> -- </option>
                            @foreach ($list_golongan_darah as $key => $item)
                                <option value="{{ $key }}" @selected($golongan_darah == $key)>{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            @if ($list_sakit_menahun)
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="menahun">Sakit Menahun</label>
                        <select class="form-control input-sm select2" id="menahun" name="menahun">
                            <option value=""> -- </option>
                            @foreach ($list_sakit_menahun as $key => $item)
                                <option value="{{ $key }}" @selected($menahun == $key)>{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            @if ($list_tag_id_card)
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="tag_id_card">Kepemilikan Tag ID Card</label>
                        <select class="form-control input-sm select2" id="tag_id_card" name="tag_id_card">
                            <option value=""> -- </option>
                            @foreach ($list_tag_id_card as $key => $value)
                                <option value="{{ $key }}" {{ selected($tag_id_card, (string) $key) }}>{{ strtoupper($value) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            @if ($list_id_kk)
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="id_kk">Kepemilikan Kartu Keluarga</label>
                        <select class="form-control input-sm select2" id="id_kk" name="id_kk">
                            <option value=""> -- </option>
                            @foreach ($list_id_kk as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="modal-footer">
        {!! batal() !!}
        <button type="submit" class="btn btn-social btn-info btn-sm"><i class='fa fa-check'></i> Simpan</button>
    </div>
</form>

<script>
    $('#umur_min').on('input', function(e) {
        var min = $(this).val();
        var max = $('#umur_max').val();

        if (min) {
            $('#umur_max').prop('class', 'required')
        } else {
            $('#umur_max').removeClass('required')
        }
        $(this).prop('max', max)
    });

    $('#umur_max').on('input', function(e) {
        var max = $(this).val();
        var min = $('#umur_min').val();

        if (max) {
            $('#umur_min').prop('class', 'required')
        } else {
            $('#umur_min').removeClass('required')
        }
        $(this).prop('min', min)
    });
    $(function() {
        let advanceSearch = $('#tabeldata').data('advancesearch')
        if (advanceSearch) {
            for (let x in advanceSearch) {
                console.log(advanceSearch[x])
                if (advanceSearch[x]) {
                    $('.modal [name=' + x + ']').val(advanceSearch[x])
                    $('.modal [name=' + x + ']').trigger('change')
                }

            }
        }
    })
</script>
