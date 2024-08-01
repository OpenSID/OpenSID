@push('css')
    <style>
        .text-14 {
            font-size: 14px;
            font-weight: normal;
        }
    </style>
@endpush
{!! form_open('', 'class="form-validasi" id="form-5"') !!}
<input type="hidden" name='tipe_save' value='bagian5'>
<div class="row">
    <div class="col-sm-12">
        <h5>501. Dalam satu tahun terakhir, apakah keluarga menerima program berikut?</h5>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive" id="tabel_program">
            <table class="table table-bordered table-hover" style="margin-bottom: 0px;">
                <thead class="bg-gray disabled color-palette">
                    <tr>
                        <td>Jenis Program</td>
                        <td>Kepesertaan</td>
                        <td>Periode Terakhir Mendapatkan Program (Bulan/Tahun)</td>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
    <hr class="col-sm-12">

    <div class="col-sm-12">
        <h5>502. Keluarga memiliki aset bergerak sebagai berikut</h5>
    </div>
    <div class="form-group col-xs-12 col-sm-6">
        <input type="hidden" name="pilihan[5][502a]" value="2">
        <input type="checkbox" name="pilihan[5][502a]" id="pilihan_5_502a" value="1" @checked($dtks->kd_tabung_gas_5_5_kg == '1')>
        <label class="text-14" for="pilihan_5_502a">a. Tabung gas 5 kg atau lebih</label>
    </div>
    <div class="form-group col-xs-12 col-sm-6">
        <input type="hidden" name="pilihan[5][502b]" value="2">
        <input type="checkbox" id="pilihan_5_502b" name="pilihan[5][502b]" value="1" @checked($dtks->kd_lemari_es == '1')>
        <label class="text-14" for="pilihan_5_502b">b. Lemari es/kulkas</label>
    </div>
    <div class="form-group col-xs-12 col-sm-6">
        <input type="hidden" name="pilihan[5][502c]" value="2">
        <input type="checkbox" id="pilihan_5_502c" name="pilihan[5][502c]" value="1" @checked($dtks->kd_ac == '1')>
        <label class="text-14" for="pilihan_5_502c">c. AC</label>
    </div>
    <div class="form-group col-xs-12 col-sm-6">
        <input type="hidden" name="pilihan[5][502d]" value="2">
        <input type="checkbox" id="pilihan_5_502d" name="pilihan[5][502d]" value="1" @checked($dtks->kd_pemanas_air == '1')>
        <label class="text-14" for="pilihan_5_502d">d. Pemanas air (water heater)</label>
    </div>
    <div class="form-group col-xs-12 col-sm-6">
        <input type="hidden" name="pilihan[5][502e]" value="2">
        <input type="checkbox" id="pilihan_5_502e" name="pilihan[5][502e]" value="1" @checked($dtks->kd_telepon_rumah == '1')>
        <label class="text-14" for="pilihan_5_502e">e. Telepon rumah (PSTN)</label>
    </div>
    <div class="form-group col-xs-12 col-sm-6">
        <input type="hidden" name="pilihan[5][502f]" value="2">
        <input type="checkbox" id="pilihan_5_502f" name="pilihan[5][502f]" value="1" @checked($dtks->kd_televisi == '1')>
        <label class="text-14" for="pilihan_5_502f">f. Televisi layar datar (min. 30 inchi)</label>
    </div>
    <div class="form-group col-xs-12 col-sm-6">
        <input type="hidden" name="pilihan[5][502g]" value="2">
        <input type="checkbox" id="pilihan_5_502g" name="pilihan[5][502g]" value="1" @checked($dtks->kd_perhiasan_10_gr_emas == '1')>
        <label class="text-14" for="pilihan_5_502g">g. Emas/perhiasan (min. 10 gram)</label>
    </div>
    <div class="form-group col-xs-12 col-sm-6">
        <input type="hidden" name="pilihan[5][502h]" value="2">
        <input type="checkbox" id="pilihan_5_502h" name="pilihan[5][502h]" value="1" @checked($dtks->kd_komputer_laptop == '1')>
        <label class="text-14" for="pilihan_5_502h">h. Komputer/laptop/tablet</label>
    </div>
    <div class="form-group col-xs-12 col-sm-6">
        <input type="hidden" name="pilihan[5][502i]" value="2">
        <input type="checkbox" id="pilihan_5_502i" name="pilihan[5][502i]" value="1" @checked($dtks->kd_sepeda_motor == '1')>
        <label class="text-14" for="pilihan_5_502i">i. Sepeda Motor</label>
    </div>
    <div class="form-group col-xs-12 col-sm-6">
        <input type="hidden" name="pilihan[5][502j]" value="2">
        <input type="checkbox" id="pilihan_5_502j" name="pilihan[5][502j]" value="1" @checked($dtks->kd_sepeda == '1')>
        <label class="text-14" for="pilihan_5_502j">j. Sepeda</label>
    </div>
    <div class="form-group col-xs-12 col-sm-6">
        <input type="hidden" name="pilihan[5][502k]" value="2">
        <input type="checkbox" id="pilihan_5_502k" name="pilihan[5][502k]" value="1" @checked($dtks->kd_mobil == '1')>
        <label class="text-14" for="pilihan_5_502k">k. Mobil</label>
    </div>
    <div class="form-group col-xs-12 col-sm-6">
        <input type="hidden" name="pilihan[5][502l]" value="2">
        <input type="checkbox" id="pilihan_5_502l" name="pilihan[5][502l]" value="1" @checked($dtks->kd_perahu == '1')>
        <label class="text-14" for="pilihan_5_502l">l. Perahu</label>
    </div>
    <div class="form-group col-xs-12 col-sm-6">
        <input type="hidden" name="pilihan[5][502m]" value="2">
        <input type="checkbox" id="pilihan_5_502m" name="pilihan[5][502m]" value="1" @checked($dtks->kd_kapal_perahu_motor == '1')>
        <label class="text-14" for="pilihan_5_502m">m. Kapal/ Perahu Motor</label>
    </div>
    <div class="form-group col-xs-12 col-sm-6">
        <input type="hidden" name="pilihan[5][502n]" value="2">
        <input type="checkbox" id="pilihan_5_502n" name="pilihan[5][502n]" value="1" @checked($dtks->kd_smartphone == '1')>
        <label class="text-14" for="pilihan_5_502n">n. Smartphone</label>
    </div>

    <hr class="col-sm-12">
    <div class="col-sm-12">
        <h5>503. Keluarga memiliki aset tidak bergerak sebagai berikut:</h5>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="pilihan_5_503a">a. Lahan</label>
            @include('admin.layouts.components.select_pilihan_dtks', ['class' => 'select2', 'attribut' => 'id="pilihan_5_503a" name="pilihan[5][503a]"', 'pilihan' => $pilihan5['ya_tidak'], 'selected_value' => $dtks->kd_lahan])
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="pilihan_5_503b">b. Rumah di tempat lain</label>
            @include('admin.layouts.components.select_pilihan_dtks', ['class' => 'select2', 'attribut' => 'id="pilihan_5_503b" name="pilihan[5][503b]"', 'pilihan' => $pilihan5['ya_tidak'], 'selected_value' => $dtks->kd_rumah_ditempat_lain])
        </div>
    </div>

    <hr class="col-sm-12">
    <div class="col-sm-12">
        <h5>504. Jumlah ternak yang dimiliki (ekor): (*maksimal 999)</h5>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="form-group">
            <label for="input_5_504a">a. Sapi</label>
            <input
                min="0"
                max="999"
                name="input[5][504a]"
                id="input_5_504a"
                class="form-control input-sm"
                type="number"
                value="{{ $dtks->jumlah_sapi }}"
            >
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="form-group">
            <label for="input_5_504b">b. Kerbau</label>
            <input
                min="0"
                max="999"
                name="input[5][504b]"
                id="input_5_504b"
                class="form-control input-sm"
                type="number"
                value="{{ $dtks->jumlah_kerbau }}"
            >
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="form-group">
            <label for="input_5_504c">c. Kuda</label>
            <input
                min="0"
                max="999"
                name="input[5][504c]"
                id="input_5_504c"
                class="form-control input-sm"
                type="number"
                value="{{ $dtks->jumlah_kuda }}"
            >
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="form-group">
            <label for="input_5_504d">d. Babi</label>
            <input
                min="0"
                max="999"
                name="input[5][504d]"
                id="input_5_504d"
                class="form-control input-sm"
                type="number"
                value="{{ $dtks->jumlah_babi }}"
            >
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="form-group">
            <label for="input_5_504e">e. Kambing/Domba</label>
            <input
                min="0"
                max="999"
                name="input[5][504e]"
                id="input_5_504e"
                class="form-control input-sm"
                type="number"
                value="{{ $dtks->jumlah_kambing_domba }}"
            >
        </div>
    </div>

    <hr class="col-sm-12">
    <div class="col-sm-12">
        <div class="form-group">
            <h5>
                <label for="pilihan_5_505">505. Jenis akses internet utama yang digunakan keluarga selama sebulan terakhir?</label>
            </h5>

            @include('admin.layouts.components.select_pilihan_dtks', ['class' => 'select2', 'attribut' => 'id="pilihan_5_505" name="pilihan[5][505]"', 'pilihan' => $pilihan5['505'], 'selected_value' => $dtks->kd_internet_sebulan])
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label for="pilihan_5_506">506. Apakah keluarga ini memiliki rekening aktif atau dompet digital</label>
            @include('admin.layouts.components.select_pilihan_dtks', ['class' => 'select2', 'attribut' => 'id="pilihan_5_506" name="pilihan[5][506]"', 'pilihan' => $pilihan5['506'], 'selected_value' => $dtks->kd_rek_aktif])
        </div>
    </div>

    <hr class="col-sm-12">
    <div class="col-sm-12 text-center">
        <button type="reset" class="btn btn-social btn-danger btn-sm"><i class='fa fa-times'></i>Batal</button>
        <button type="button" class="next-prev-bagian-5 btn btn-social btn-default btn-sm"><i class='fa fa-arrow-left'></i> Sebelumnya</button>
        <button type="button" class="next-prev-bagian-5 btn btn-social btn-default btn-sm">Selanjutnya <i class="fa fa-arrow-right"></i></button>
        <button type="submit" class="btn btn-social btn-info btn-sm"><i class="fa fa-check"></i>Simpan</button>
    </div>
</div>
</form>
@push('scripts')
    <script>
        $(document).ready(function() {
            let selected_value_program = {!! json_encode([
                '501a_dapat' => $dtks->kd_bss_bnpt,
                '501b_dapat' => $dtks->kd_pkh,
                '501c_dapat' => $dtks->kd_blt_dana_desa,
                '501d_dapat' => $dtks->kd_subsidi_listrik,
                '501e_dapat' => $dtks->kd_bantuan_pemda,
                '501f_dapat' => $dtks->kd_subsidi_pupuk,
                '501g_dapat' => $dtks->kd_subsidi_lpg,
            
                '501a_bulan' => $dtks->bulan_bss_bnpt,
                '501b_bulan' => $dtks->bulan_pkh,
                '501c_bulan' => $dtks->bulan_blt_dana_desa,
                '501d_bulan' => $dtks->bulan_subsidi_listrik,
                '501e_bulan' => $dtks->bulan_bantuan_pemda,
                '501f_bulan' => $dtks->bulan_subsidi_pupuk,
                '501g_bulan' => $dtks->bulan_subsidi_lpg,
            
                '501a_tahun' => $dtks->tahun_bss_bnpt,
                '501b_tahun' => $dtks->tahun_pkh,
                '501c_tahun' => $dtks->tahun_blt_dana_desa,
                '501d_tahun' => $dtks->tahun_subsidi_listrik,
                '501e_tahun' => $dtks->tahun_bantuan_pemda,
                '501f_tahun' => $dtks->tahun_subsidi_pupuk,
                '501g_tahun' => $dtks->tahun_subsidi_lpg,
            ]) !!};

            let template_select_dapat_program = `@include('admin.layouts.components.select_pilihan_dtks', [
                'class' => 'select2 required',
                'attribut' => 'id="pilihan_5_{no}_dapat" name="pilihan[5][{no}_dapat]"',
                'pilihan' => $pilihan5['ya_tidak'],
            ])`;
            let template_select_bulan_program = `@include('admin.layouts.components.select_pilihan_dtks', [
                'class' => 'select2',
                'attribut' => 'id="pilihan_5_{no}_bulan" name="pilihan[5][{no}_bulan]"',
                'pilihan' => $bulan,
            ])`;
            let tahun_awal = '{{ $tahun_awal }}';
            let option_tahun_program = '';
            for (let i = tahun_awal; i <= new Date().getFullYear(); i++) {
                option_tahun_program += `<option value="` + i + `">` + i + `</option>`;
            }


            let template_row_program = `<tr>` +
                `<td>{title}</td>` +
                `<td>` + template_select_dapat_program + `</td>` +
                `<td>` +
                `<div class="form-group col-sm-6 no-padding">` +
                `<label class="control-label" for="pilihan_5_{no}_bulan">Bulan</label>` +
                template_select_bulan_program +
                `</div>` +
                `<div class="form-group col-sm-6 no-padding">` +
                `<label class="control-label" for="pilihan_5_{no}_tahun">Tahun</label>` +
                `<select class="form-control select2 input-sm" id="pilihan_5_{no}_tahun" name="pilihan[5][{no}_tahun]" style="width:100%;">` +
                `<option selected value="">-- Pilih --</option>` +
                option_tahun_program +
                `</select>` +
                `</div>` +
                `</td>` +
                `</tr>`;

            // default program
            [{
                    'kode': 'a',
                    'title': 'a. Program Bantuan Sosial Sembako/ BPNT'
                },
                {
                    'kode': 'b',
                    'title': 'b. Program Keluarga Harapan (PKH)'
                },
                {
                    'kode': 'c',
                    'title': 'c. Program Bantuan Langsung Tunai (BLT) Dana Desa'
                },
                {
                    'kode': 'd',
                    'title': 'd. Program Subsidi Listrik (gratis/pemotongan biaya)'
                },
                {
                    'kode': 'e',
                    'title': 'e. Program Bantuan Pemerintah Daerah'
                },
                {
                    'kode': 'f',
                    'title': 'f. Program Bantuan Subsidi Pupuk'
                },
                {
                    'kode': 'g',
                    'title': 'g. Program Subsidi LPG'
                },
            ].forEach(function(item) {
                let tr = template_row_program.replace('{title}', item.title).replaceAll('{no}', '501' + item.kode);
                if (selected_value_program['501' + item.kode + '_dapat'] == null) {
                    tr = tr.replace('<td><sel', '<td class="bg-orange"><sel');
                }
                if (selected_value_program['501' + item.kode + '_dapat'] == 1 && (selected_value_program['501' + item.kode + '_bulan'] == null || selected_value_program['501' + item.kode + '_tahun'] == null)) {
                    tr = tr.replace('<td><div', '<td class="bg-orange"><div');
                }
                $('#tabel_program tbody').append(tr);
                $('#tabel_program tbody #pilihan_5_501' + item.kode + '_dapat').val(selected_value_program['501' + item.kode + '_dapat'])
                $('#tabel_program tbody #pilihan_5_501' + item.kode + '_bulan').val(selected_value_program['501' + item.kode + '_bulan'])
                $('#tabel_program tbody #pilihan_5_501' + item.kode + '_tahun').val(selected_value_program['501' + item.kode + '_tahun'])
                $('#pilihan_5_501' + item.kode + '_dapat').on('change', function() {
                    if (this.value == 1) {
                        $(this).parentsUntil('tr').parent().find('td:eq(1)').removeClass('bg-orange');
                        $(this).parentsUntil('tr').parent().find('td:gt(1)').addClass('bg-orange');
                    } else if (this.value == null) {
                        $(this).parentsUntil('tr').parent().find('td:gt(1)').removeClass('bg-orange');
                        $(this).parentsUntil('tr').parent().find('td:eq(1)').addClass('bg-orange');
                    } else if (this.value == 2) {
                        $(this).parentsUntil('tr').parent().find('td:eq(1)').removeClass('bg-orange');
                        $(this).parentsUntil('tr').parent().find('td:gt(1)').removeClass('bg-orange');
                    }
                    if ($('#pilihan_5_501' + item.kode + '_bulan').val() != null &&
                        $('#pilihan_5_501' + item.kode + '_tahun').val() != null) {
                        $('#pilihan_5_501' + item.kode + '_bulan').parent().parent().removeClass('bg-orange');
                    };
                });
                let fn_bulan_tahun = function() {
                    if ($('#pilihan_5_501' + item.kode + '_bulan').val() != null &&
                        $('#pilihan_5_501' + item.kode + '_tahun').val() != null) {
                        $(this).parent().parent().removeClass('bg-orange');
                    };
                };
                $('#pilihan_5_501' + item.kode + '_bulan').on('change', fn_bulan_tahun);
                $('#pilihan_5_501' + item.kode + '_tahun').on('change', fn_bulan_tahun);
            });

            $('.next-prev-bagian-5').on('click', function() {
                let is_valid = is_form_valid($(`#form-5`).attr('id'));
                if (!is_valid) {
                    return false;
                }

                let form = $('#form-5').serializeArray();
                $('#form-5 select').each(function(index, el) {
                    form.push({
                        'name': $(el).attr('name'),
                        'value': $(el).val()
                    });
                });

                $.ajax({
                    type: 'POST',
                    url: "{{ ci_route('dtks.save') . '/' . $dtks->id }}",
                    data: form,
                });

                let selajutnya = $(this).text().includes("Selanjutnya");
                if (selajutnya) {
                    $(`#nav-bagian-6`).trigger('click');
                } else {
                    $(`#nav-bagian-4`).trigger('click');
                }
            });
            $('#form-5 button[type=reset]').on('click', function(ev) {
                setTimeout(() => {
                    $('#form-5 select').trigger('change');
                }, 200);
            });
            $('#form-5').on('submit', function(ev) {
                ev.preventDefault();

                let is_valid = is_form_valid($(this).attr('id'));
                if (!is_valid) {
                    return false;
                }
                let form = $('#form-5').serializeArray();
                $('#form-5 select').each(function(index, el) {
                    form.push({
                        'name': $(el).attr('name'),
                        'value': $(el).val()
                    });
                });
                ajax_save_dtks("{{ ci_route('dtks.save') . '/' . $dtks->id }}", form);
            });
        });
    </script>
@endpush
