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
    <div class="col-sm-12" style="display: none;">
        <h5>501. Dalam satu tahun terakhir, apakah keluarga menerima program berikut?</h5>
    </div>
    <div class="col-sm-12" style="display: none;">
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
    <hr class="col-sm-12" style="display: none;">
    <div class="col-sm-12">
        <h5>502. Keluarga memiliki aset bergerak sebagai berikut</h5>
        <p class="text-muted small mb-3">
            <i class="fas fa-info-circle"></i> 
            <strong>Apabila tidak memiliki, isikan 0 pada aplikasi</strong>
            <i class="fas fa-info-circle"></i> 
            <strong>Dibatasi 1 Digit saja, kecuali emas atau smartphone</strong>
        </p>
    </div>

    <div class="form-group col-xs-12 col-sm-6">
        <label class="text-14" for="kd_tabung_gas_5_5_kg">a. Tabung gas 5,5 kg atau lebih</label>
        <input type="number" 
            class="form-control" 
            id="kd_tabung_gas_5_5_kg" 
            name="kd_tabung_gas_5_5_kg" 
            min="0" 
            max="99" 
            placeholder="Masukan Jumlah"
            value="{{ old('kd_tabung_gas_5_5_kg', $dtsen->kd_tabung_gas_5_5_kg ?? '') }}">
        
    </div>

    <div class="form-group col-xs-12 col-sm-6">
        <label class="text-14" for="kd_lemari_es">b. Lemari es/kulkas</label>
        <input type="number" 
            class="form-control" 
            id="kd_lemari_es" 
            name="kd_lemari_es" 
            min="0" 
            max="99" 
            placeholder="Masukan Jumlah"
            value="{{ old('kd_lemari_es', $dtsen->kd_lemari_es ?? '') }}">
        
    </div>

    <div class="form-group col-xs-12 col-sm-6">
        <label class="text-14" for="kd_ac">c. Jumlah Air Conditioner (AC)</label>
        <input type="number" 
            class="form-control" 
            id="kd_ac" 
            name="kd_ac" 
            min="0" 
            max="99" 
            placeholder="Masukan Jumlah"
            value="{{ old('kd_ac', $dtsen->kd_ac ?? '') }}">
        
    </div>

    <div class="form-group col-xs-12 col-sm-6">
        <label class="text-14" for="kd_pemanas_air">d. Pemanas Air (Water Heater) untuk mandi</label>
        <input type="number" 
            class="form-control" 
            id="kd_pemanas_air" 
            name="kd_pemanas_air" 
            min="0" 
            max="99" 
            placeholder="Masukan Jumlah"
            value="{{ old('kd_pemanas_air', $dtsen->kd_pemanas_air ?? '') }}">
        
    </div>

    <div class="form-group col-xs-12 col-sm-6">
        <label class="text-14" for="kd_telepon_rumah">e. Telepon Rumah (PSTN)</label>
        <input type="number" 
            class="form-control" 
            id="kd_telepon_rumah" 
            name="kd_telepon_rumah" 
            min="0" 
            max="99" 
            placeholder="Masukan Jumlah"
            value="{{ old('kd_telepon_rumah', $dtsen->kd_telepon_rumah ?? '') }}">
        
    </div>

    <div class="form-group col-xs-12 col-sm-6">
        <label class="text-14" for="kd_televisi">f. Televisi Layar Datar (Min. 30 Inch)</label>
        <input type="number" 
            class="form-control" 
            id="kd_televisi" 
            name="kd_televisi" 
            min="0" 
            max="99" 
            placeholder="Masukan Jumlah"
            value="{{ old('kd_televisi', $dtsen->kd_televisi ?? '') }}">
        
    </div>

    <div class="form-group col-xs-12 col-sm-6">
        <label class="text-14" for="kd_perhiasan_10_gr_emas">g. Jumlah Emas/Perhiasan (Gram)</label>
        <input type="number" 
            class="form-control" 
            id="kd_perhiasan_10_gr_emas" 
            name="kd_perhiasan_10_gr_emas" 
            min="0" 
            placeholder="Masukan Jumlah"
            value="{{ old('kd_perhiasan_10_gr_emas', $dtsen->kd_perhiasan_10_gr_emas ?? '') }}">
    </div>

    <div class="form-group col-xs-12 col-sm-6">
        <label class="text-14" for="kd_komputer_laptop">h. Jumlah Komputer/Laptop/Tablet</label>
        <input type="number" 
            class="form-control" 
            id="kd_komputer_laptop" 
            name="kd_komputer_laptop" 
            min="0" 
            max="99" 
            placeholder="Masukan Jumlah"
            value="{{ old('kd_komputer_laptop', $dtsen->kd_komputer_laptop ?? '') }}">
        
    </div>

    <div class="form-group col-xs-12 col-sm-6">
        <label class="text-14" for="kd_sepeda_motor">i. Jumlah Sepeda Motor</label>
        <input type="number" 
            class="form-control" 
            id="kd_sepeda_motor" 
            name="kd_sepeda_motor" 
            min="0" 
            max="99" 
            placeholder="Masukan Jumlah"
            value="{{ old('kd_sepeda_motor', $dtsen->kd_sepeda_motor ?? '') }}">
        
    </div>

    <div class="form-group col-xs-12 col-sm-6">
        <label class="text-14" for="kd_sepeda">j. Jumlah Sepeda</label>
        <input type="number" 
            class="form-control" 
            id="kd_sepeda" 
            name="kd_sepeda" 
            min="0" 
            max="99" 
            placeholder="Masukan Jumlah"
            value="{{ old('kd_sepeda', $dtsen->kd_sepeda ?? '') }}">
        
    </div>

    <div class="form-group col-xs-12 col-sm-6">
        <label class="text-14" for="kd_mobil">k. Jumlah Mobil</label>
        <input type="number" 
            class="form-control" 
            id="kd_mobil" 
            name="kd_mobil" 
            min="0" 
            max="99" 
            placeholder="Masukan Jumlah"
            value="{{ old('kd_mobil', $dtsen->kd_mobil ?? '') }}">
        
    </div>

    <div class="form-group col-xs-12 col-sm-6">
        <label class="text-14" for="kd_perahu">l. Jumlah Perahu</label>
        <input type="number" 
            class="form-control" 
            id="kd_perahu" 
            name="kd_perahu" 
            min="0" 
            max="99" 
            placeholder="Masukan Jumlah"
            value="{{ old('kd_perahu', $dtsen->kd_perahu ?? '') }}">
        
    </div>

    <div class="form-group col-xs-12 col-sm-6">
        <label class="text-14" for="kd_kapal_perahu_motor">m. Jumlah Kapal/Perahu Motor</label>
        <input type="number" 
            class="form-control" 
            id="kd_kapal_perahu_motor" 
            name="kd_kapal_perahu_motor" 
            min="0" 
            max="99" 
            placeholder="Masukan Jumlah"
            value="{{ old('kd_kapal_perahu_motor', $dtsen->kd_kapal_perahu_motor ?? '') }}">
        
    </div>

    <div class="form-group col-xs-12 col-sm-6">
        <label class="text-14" for="kd_smartphone">n. Jumlah Smartphone</label>
        <input type="number" 
            class="form-control" 
            id="kd_smartphone" 
            name="kd_smartphone" 
            min="0" 
            placeholder="Masukan Jumlah"
            value="{{ old('kd_smartphone', $dtsen->kd_smartphone ?? '') }}">
        <small class="form-text text-muted">Masukan Jumlah</small>
    </div>

    <!-- <div class="col-sm-12">
        <h5>502. Keluarga memiliki aset bergerak sebagai berikut</h5>
    </div>
    <div class="form-group col-xs-12 col-sm-6">
        <input type="hidden" name="pilihan[5][502a]" value="2">
        <input type="checkbox" name="pilihan[5][502a]" id="pilihan_5_502a" value="1" @checked($dtsen->kd_tabung_gas_5_5_kg == '1')>
        <label class="text-14" for="pilihan_5_502a">a. Tabung gas 5 kg atau lebih</label>
    </div>
    <div class="form-group col-xs-12 col-sm-6">
        <input type="hidden" name="pilihan[5][502b]" value="2">
        <input type="checkbox" id="pilihan_5_502b" name="pilihan[5][502b]" value="1" @checked($dtsen->kd_lemari_es == '1')>
        <label class="text-14" for="pilihan_5_502b">b. Lemari es/kulkas</label>
    </div>
    <div class="form-group col-xs-12 col-sm-6">
        <input type="hidden" name="pilihan[5][502c]" value="2">
        <input type="checkbox" id="pilihan_5_502c" name="pilihan[5][502c]" value="1" @checked($dtsen->kd_ac == '1')>
        <label class="text-14" for="pilihan_5_502c">c. AC</label>
    </div>
    <div class="form-group col-xs-12 col-sm-6">
        <input type="hidden" name="pilihan[5][502d]" value="2">
        <input type="checkbox" id="pilihan_5_502d" name="pilihan[5][502d]" value="1" @checked($dtsen->kd_pemanas_air == '1')>
        <label class="text-14" for="pilihan_5_502d">d. Pemanas air (water heater)</label>
    </div>
    <div class="form-group col-xs-12 col-sm-6">
        <input type="hidden" name="pilihan[5][502e]" value="2">
        <input type="checkbox" id="pilihan_5_502e" name="pilihan[5][502e]" value="1" @checked($dtsen->kd_telepon_rumah == '1')>
        <label class="text-14" for="pilihan_5_502e">e. Telepon rumah (PSTN)</label>
    </div>
    <div class="form-group col-xs-12 col-sm-6">
        <input type="hidden" name="pilihan[5][502f]" value="2">
        <input type="checkbox" id="pilihan_5_502f" name="pilihan[5][502f]" value="1" @checked($dtsen->kd_televisi == '1')>
        <label class="text-14" for="pilihan_5_502f">f. Televisi layar datar (min. 30 inchi)</label>
    </div>
    <div class="form-group col-xs-12 col-sm-6">
        <input type="hidden" name="pilihan[5][502g]" value="2">
        <input type="checkbox" id="pilihan_5_502g" name="pilihan[5][502g]" value="1" @checked($dtsen->kd_perhiasan_10_gr_emas == '1')>
        <label class="text-14" for="pilihan_5_502g">g. Emas/perhiasan (min. 10 gram)</label>
    </div>
    <div class="form-group col-xs-12 col-sm-6">
        <input type="hidden" name="pilihan[5][502h]" value="2">
        <input type="checkbox" id="pilihan_5_502h" name="pilihan[5][502h]" value="1" @checked($dtsen->kd_komputer_laptop == '1')>
        <label class="text-14" for="pilihan_5_502h">h. Komputer/laptop/tablet</label>
    </div>
    <div class="form-group col-xs-12 col-sm-6">
        <input type="hidden" name="pilihan[5][502i]" value="2">
        <input type="checkbox" id="pilihan_5_502i" name="pilihan[5][502i]" value="1" @checked($dtsen->kd_sepeda_motor == '1')>
        <label class="text-14" for="pilihan_5_502i">i. Sepeda Motor</label>
    </div>
    <div class="form-group col-xs-12 col-sm-6">
        <input type="hidden" name="pilihan[5][502j]" value="2">
        <input type="checkbox" id="pilihan_5_502j" name="pilihan[5][502j]" value="1" @checked($dtsen->kd_sepeda == '1')>
        <label class="text-14" for="pilihan_5_502j">j. Sepeda</label>
    </div>
    <div class="form-group col-xs-12 col-sm-6">
        <input type="hidden" name="pilihan[5][502k]" value="2">
        <input type="checkbox" id="pilihan_5_502k" name="pilihan[5][502k]" value="1" @checked($dtsen->kd_mobil == '1')>
        <label class="text-14" for="pilihan_5_502k">k. Mobil</label>
    </div>
    <div class="form-group col-xs-12 col-sm-6">
        <input type="hidden" name="pilihan[5][502l]" value="2">
        <input type="checkbox" id="pilihan_5_502l" name="pilihan[5][502l]" value="1" @checked($dtsen->kd_perahu == '1')>
        <label class="text-14" for="pilihan_5_502l">l. Perahu</label>
    </div>
    <div class="form-group col-xs-12 col-sm-6">
        <input type="hidden" name="pilihan[5][502m]" value="2">
        <input type="checkbox" id="pilihan_5_502m" name="pilihan[5][502m]" value="1" @checked($dtsen->kd_kapal_perahu_motor == '1')>
        <label class="text-14" for="pilihan_5_502m">m. Kapal/ Perahu Motor</label>
    </div>
    <div class="form-group col-xs-12 col-sm-6">
        <input type="hidden" name="pilihan[5][502n]" value="2">
        <input type="checkbox" id="pilihan_5_502n" name="pilihan[5][502n]" value="1" @checked($dtsen->kd_smartphone == '1')>
        <label class="text-14" for="pilihan_5_502n">n. Smartphone</label>
    </div> -->

    <hr class="col-sm-12">
    <div class="col-sm-12">
        <h5>503. Keluarga memiliki aset tidak bergerak sebagai berikut:</h5>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="pilihan_5_503a">a. Lahan</label>
            @include('admin.layouts.components.select_pilihan_dtsen', ['class' => 'select2', 'attribut' => 'id="pilihan_5_503a" name="pilihan[5][503a]"', 'pilihan' => $pilihan5['ya_tidak'], 'selected_value' => $dtsen->kd_lahan])
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="pilihan_5_503">Luas Lahan</label>
            @include('admin.layouts.components.select_pilihan_dtsen', ['class' => 'select2', 'attribut' => 'id="pilihan_5_503" name="pilihan[5][503]"', 'pilihan' => $pilihan5['503'], 'selected_value' => $dtsen->kd_luas_lahan])
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="pilihan_5_503b">b. Rumah di tempat lain</label>
            @include('admin.layouts.components.select_pilihan_dtsen', ['class' => 'select2', 'attribut' => 'id="pilihan_5_503b" name="pilihan[5][503b]"', 'pilihan' => $pilihan5['ya_tidak'], 'selected_value' => $dtsen->kd_rumah_ditempat_lain])
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
                value="{{ $dtsen->jumlah_sapi }}"
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
                value="{{ $dtsen->jumlah_kerbau }}"
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
                value="{{ $dtsen->jumlah_kuda }}"
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
                value="{{ $dtsen->jumlah_babi }}"
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
                value="{{ $dtsen->jumlah_kambing_domba }}"
            >
        </div>
    </div>

    <hr class="col-sm-12">
    <div class="col-sm-12" style="display: none;">
        <div class="form-group">
            <h5>
                <label for="pilihan_5_505">505. Jenis akses internet utama yang digunakan keluarga selama sebulan terakhir?</label>
            </h5>

            @include('admin.layouts.components.select_pilihan_dtsen', ['class' => 'select2', 'attribut' => 'id="pilihan_5_505" name="pilihan[5][505]"', 'pilihan' => $pilihan5['505'], 'selected_value' => $dtsen->kd_internet_sebulan])
        </div>
    </div>
    <div class="col-sm-12" style="display: none;">
        <div class="form-group">
            <label for="pilihan_5_506">506. Apakah keluarga ini memiliki rekening aktif atau dompet digital</label>
            @include('admin.layouts.components.select_pilihan_dtsen', ['class' => 'select2', 'attribut' => 'id="pilihan_5_506" name="pilihan[5][506]"', 'pilihan' => $pilihan5['506'], 'selected_value' => $dtsen->kd_rek_aktif])
        </div>
    </div>

    <hr class="col-sm-12" style="display: none;">
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
                '501a_dapat' => $dtsen->kd_bss_bnpt,
                '501b_dapat' => $dtsen->kd_pkh,
                '501c_dapat' => $dtsen->kd_blt_dana_desa,
                '501d_dapat' => $dtsen->kd_subsidi_listrik,
                '501e_dapat' => $dtsen->kd_bantuan_pemda,
                '501f_dapat' => $dtsen->kd_subsidi_pupuk,
                '501g_dapat' => $dtsen->kd_subsidi_lpg,
            
                '501a_bulan' => $dtsen->bulan_bss_bnpt,
                '501b_bulan' => $dtsen->bulan_pkh,
                '501c_bulan' => $dtsen->bulan_blt_dana_desa,
                '501d_bulan' => $dtsen->bulan_subsidi_listrik,
                '501e_bulan' => $dtsen->bulan_bantuan_pemda,
                '501f_bulan' => $dtsen->bulan_subsidi_pupuk,
                '501g_bulan' => $dtsen->bulan_subsidi_lpg,
            
                '501a_tahun' => $dtsen->tahun_bss_bnpt,
                '501b_tahun' => $dtsen->tahun_pkh,
                '501c_tahun' => $dtsen->tahun_blt_dana_desa,
                '501d_tahun' => $dtsen->tahun_subsidi_listrik,
                '501e_tahun' => $dtsen->tahun_bantuan_pemda,
                '501f_tahun' => $dtsen->tahun_subsidi_pupuk,
                '501g_tahun' => $dtsen->tahun_subsidi_lpg,
            ]) !!};

            let template_select_dapat_program = `@include('admin.layouts.components.select_pilihan_dtsen', [
                'class' => 'select2',
                'attribut' => 'id="pilihan_5_{no}_dapat" name="pilihan[5][{no}_dapat]"',
                'pilihan' => $pilihan5['ya_tidak'],
            ])`;
            let template_select_bulan_program = `@include('admin.layouts.components.select_pilihan_dtsen', [
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

                let selajutnya = $(this).text().includes("Selanjutnya");
                
                $.ajax({
                    type: 'POST',
                    url: "{{ route('dtsen_pendataan.save', $dtsen->id) }}",
                    data: form,
                    dataType: 'json'
                }).done(function() {
                    if (selajutnya) {
                        $(`#nav-bagian-2`).trigger('click');
                    } else {
                        $(`#nav-bagian-4`).trigger('click');
                    }
                }).fail(function() {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Gagal menyimpan',
                        text: 'Data tidak tersimpan. Silakan coba lagi.'
                    });
                });
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
                
                let btn = $(this).find('button[type=submit]');
                let originalContent = btn.html();
                btn.prop('disabled', true).html('<i class=\"fa fa-spinner fa-spin\"></i> Menyimpan...');
                
                ajax_save_dtsen("{{ route('dtsen_pendataan.save', $dtsen->id) }}", form, function() {
                    btn.prop('disabled', false).html(originalContent);
                }, function() {
                    btn.prop('disabled', false).html(originalContent);
                });
            });
        });
    </script>
@endpush
