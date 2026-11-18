@include('admin.layouts.components.form_modal_validasi')
<form method="post" action="{{ $form_action }}" id="validasi">
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12">
                <label for="nama">Umur</label>
            </div>
            @if ($input_umur)
                <div class="col-sm-4">
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

                <div class="col-sm-4">
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

                <div class="col-sm-4">
                    <div class="form-group">
                        <select class="form-control input-sm select2" id="umur" name="umur">
                            <option value="tahun" @selected($umur == 'tahun')>Tahun</option>
                            <option value="bulan" @selected($umur == 'bulan')>Bulan</option>
                        </select>
                    </div>
                </div>
            @endif

            <div class="col-sm-12">
                <div class="form-group">
                    <label for="birth_datepicker">Tanggal Lahir</label>
                    <div class="input-group">
                        <input type="text" id="birth_datepicker" class="form-control input-sm" placeholder="Pilih hari/bulan (opsional tahun)">
                        <span class="input-group-addon input-sm">
                            <input type="checkbox" id="include_birth_year" name="include_birth_year" value="1"> Tahun
                        </span>
                        <input type="hidden" id="birth_day" name="birth_day">
                        <input type="hidden" id="birth_month" name="birth_month">
                        <input type="hidden" id="birth_year" name="birth_year">
                    </div>
                    <small class="text-muted text-danger">Klik input untuk memilih hari & bulan. Centang "Tahun" untuk memilih tanggal lengkap.</small>
                </div>
            </div>

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

            @if ($kepemilikan_bpjs)
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="kepemilikan_bpjs">Kepemilikan BPJS Ketenagakerjaan</label>
                        <select class="form-control input-sm select2" id="kepemilikan_bpjs" name="kepemilikan_bpjs">
                            <option value=""> -- </option>
                            @foreach ($kepemilikan_bpjs as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
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

            @if ($list_adat)
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="id_kk">Adat</label>
                        <select class="form-control input-sm select2" id="adat" name="adat">
                            <option value=""> -- </option>
                            @foreach ($list_adat as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            @if ($list_suku)
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="id_kk">Suku / Etnis</label>
                        <select class="form-control input-sm select2" id="suku" name="suku">
                            <option value=""> -- </option>
                            @foreach ($list_suku as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            @if ($list_marga)
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="id_kk">Marga</label>
                        <select class="form-control input-sm select2" id="marga" name="marga">
                            <option value=""> -- </option>
                            @foreach ($list_marga as $key => $item)
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
            $('#umur_max').addClass('required');
        } else {
            $('#umur_max').removeClass('required');
        }
        $(this).attr('max', max);
    });
    $('#umur_max').on('input', function(e) {
        var max = $(this).val();
        var min = $('#umur_min').val();
        if (max) {
            $('#umur_min').addClass('required');
        } else {
            $('#umur_min').removeClass('required');
        }
        $(this).attr('min', min);
    });

    $(function() {
        let advanceSearch = $('#tabeldata').data('advancesearch');
        if (advanceSearch) {
            for (let x in advanceSearch) {
                if (advanceSearch[x]) {
                    $(`.modal [name='${x}']`).val(advanceSearch[x])
                    $(`.modal [name='${x}']`).trigger('change')
                }
            }
        }
        // Birth datepicker with year toggle
        function initBirthPicker(withYear) {
            // Destroy existing datepicker
            if ($('#birth_datepicker').data('datepicker')) {
                $('#birth_datepicker').datepicker('destroy');
            }
            // Get today's date
            const today = new Date();
            const day = today.getDate().toString().padStart(2, '0');
            const month = (today.getMonth() + 1).toString().padStart(2, '0');
            const year = today.getFullYear();
            // Set endDate
            let endDate = withYear ? `${day}-${month}-${year}` : `${day}-${month}`;
            $('#birth_datepicker').datepicker({
                format: withYear ? 'dd-mm-yyyy' : 'dd-mm',
                autoclose: true,
                clearBtn: true,
                language: 'id',
                endDate: endDate
            }).off('change').on('change', function() {
                const val = $(this).val();
                const parts = val.split('-');
                // Clear or set values
                $('#birth_day').val(parts[0] ? parseInt(parts[0]) : '');
                $('#birth_month').val(parts[1] ? parseInt(parts[1]) : '');
                $('#birth_year').val(withYear && parts[2] ? parseInt(parts[2]) : '');
            });
        }
        // Helper to format date value
        function formatDate(day, month, year) {
            const d = day.toString().padStart(2, '0');
            const m = month.toString().padStart(2, '0');
            return year ? `${d}-${m}-${year}` : `${d}-${m}`;
        }
        // Initialize
        const day = $('#birth_day').val();
        const month = $('#birth_month').val();
        const year = $('#birth_year').val();
        const withYear = !!year || $('#include_birth_year').is(':checked');
        $('#include_birth_year').prop('checked', withYear);
        initBirthPicker(withYear);
        // Set initial value if data exists
        if (day && month) {
            $('#birth_datepicker').val(formatDate(day, month, withYear ? year : ''));
        }
        // Handle checkbox toggle
        $('#include_birth_year').on('change', function() {
            const checked = $(this).is(':checked');
            const curDay = $('#birth_day').val();
            const curMonth = $('#birth_month').val();
            const curYear = $('#birth_year').val();

            console.log(curDay, curMonth, curYear, checked);
            initBirthPicker(checked);
            if (curDay && curMonth) {
                const yearVal = checked ? (curYear || new Date().getFullYear()) : '';
                $('#birth_datepicker').val(formatDate(curDay, curMonth, yearVal)).trigger('change');
            }
        });
    });
</script>
