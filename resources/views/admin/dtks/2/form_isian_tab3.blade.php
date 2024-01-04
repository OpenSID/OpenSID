{!! form_open('', 'class="form-validasi" id="form-3"') !!}
<input type="hidden" name='tipe_save' value='bagian3'>
<div class="row">
    <div id="col_3_301a" class="col-sm-6">
        <div class="form-group">
            <label for="pilihan_3_301a">301a. Status kepemilikan bangunan tempat tinggal yang ditempati</label>
            @include('admin.layouts.components.select_pilihan_dtks', ['class' => 'select2', 'attribut' => 'id="pilihan_3_301a" name="pilihan[3][301a]"', 'pilihan' => $pilihan3['301a'], 'selected_value' => $dtks->kd_stat_bangunan_tinggal])
        </div>
    </div>
    <div id="col_3_301b" class="col-sm-6">
        <div class="form-group">
            <label for="pilihan_3_301b">301b. apa jenis bukti kepemilikan tanah bangunan tempat tinggal ini?</label>
            @include('admin.layouts.components.select_pilihan_dtks', ['class' => 'select2', 'attribut' => 'id="pilihan_3_301b" name="pilihan[3][301b]"', 'pilihan' => $pilihan3['301b'], 'selected_value' => $dtks->kd_sertiv_lahan_milik])
        </div>
    </div>
    <hr class="col-sm-12">
    <div id="col_3_302" class="col-sm-6">
        <div class="form-group">
            <label for="input_3_302">302. Luas Lantai (m<sup>2</sup>) <code>(3 angka)</code></label>
            <input maxlength="3" name="input[3][302]" id="input_3_302" class="form-control input-sm luas" type="text" value="{{ $dtks->luas_lantai }}">
        </div>
    </div>
    <div id="col_3_303" class="col-sm-6">
        <div class="form-group">
            <label for="pilihan_3_303">303. Jenis lantai terluas</label>
            @include('admin.layouts.components.select_pilihan_dtks', ['class' => 'select2', 'attribut' => 'id="pilihan_3_303" name="pilihan[3][303]"', 'pilihan' => $pilihan3['303'], 'selected_value' => $dtks->kd_jenis_lantai_terluas])
        </div>
    </div>
    <div id="col_3_304" class="col-sm-6">
        <div class="form-group">
            <label for="pilihan_3_304">304. Jenis dinding terluas</label>
            @include('admin.layouts.components.select_pilihan_dtks', ['class' => 'select2', 'attribut' => 'id="pilihan_3_304" name="pilihan[3][304]"', 'pilihan' => $pilihan3['304'], 'selected_value' => $dtks->kd_jenis_dinding])
        </div>
    </div>
    <div id="col_3_305" class="col-sm-6">
        <div class="form-group">
            <label for="pilihan_3_305">305. Jenis atap terluas</label>
            @include('admin.layouts.components.select_pilihan_dtks', ['class' => 'select2', 'attribut' => 'id="pilihan_3_305" name="pilihan[3][305]"', 'pilihan' => $pilihan3['305'], 'selected_value' => $dtks->kd_jenis_atap])
        </div>
    </div>
    <hr class="col-sm-12">
    <div id="col_3_306a" class="col-sm-6">
        <div class="form-group">
            <label for="pilihan_3_306a">306a. Sumber air minum</label>
            @include('admin.layouts.components.select_pilihan_dtks', ['class' => 'select2', 'attribut' => 'id="pilihan_3_306a" name="pilihan[3][306a]"', 'pilihan' => $pilihan3['306a'], 'selected_value' => $dtks->kd_sumber_air_minum])
        </div>
    </div>
    <div id="col_3_306b" class="col-sm-6">
        <div class="form-group">
            <label for="pilihan_3_306b">306b. Seberapa jauh jarak sumber air minum utama ke tempat penampungan limbah/kotoran/tinja terdekat?</label>
            @include('admin.layouts.components.select_pilihan_dtks', ['class' => 'select2', 'attribut' => 'id="pilihan_3_306b" name="pilihan[3][306b]"', 'pilihan' => $pilihan3['306b'], 'selected_value' => $dtks->kd_jarak_sumber_air_ke_tpl])
        </div>
    </div>
    <div id="col_3_307a" class="col-sm-12">
        <div class="form-group">
            <label for="pilihan_3_307a">307a. Sumber penerangan utama</label>
            @include('admin.layouts.components.select_pilihan_dtks', ['class' => 'select2', 'attribut' => 'id="pilihan_3_307a" name="pilihan[3][307a]"', 'pilihan' => $pilihan3['307a'], 'selected_value' => $dtks->kd_sumber_penerangan_utama])
        </div>
    </div>
    <div id="col_3_307b" class="col-sm-12">
        <label>Isikan kode daya untuk setiap meteran yang terpasang</label>
    </div>
    <div id="col_3_307b1" class="col-sm-4">
        <div class="form-group">
            <label for="pilihan_3_307b1">307b1. Daya terpasang 1</label>
            @include('admin.layouts.components.select_pilihan_dtks', ['class' => 'select2', 'attribut' => 'id="pilihan_3_307b1" name="pilihan[3][307b1]"', 'pilihan' => $pilihan3['307b1'], 'selected_value' => $dtks->kd_daya_terpasang])
        </div>
    </div>
    <div id="col_3_307b2" class="col-sm-4">
        <div class="form-group">
            <label for="pilihan_3_307b2">307b2. Daya terpasang 2</label>
            @include('admin.layouts.components.select_pilihan_dtks', ['class' => 'select2', 'attribut' => 'id="pilihan_3_307b2" name="pilihan[3][307b2]"', 'pilihan' => $pilihan3['307b2'], 'selected_value' => $dtks->kd_daya_terpasang2])
        </div>
    </div>
    <div id="col_3_307b3" class="col-sm-4">
        <div class="form-group">
            <label for="pilihan_3_307b3">307b3. Daya terpasang 3</label>
            @include('admin.layouts.components.select_pilihan_dtks', ['class' => 'select2', 'attribut' => 'id="pilihan_3_307b3" name="pilihan[3][307b3]"', 'pilihan' => $pilihan3['307b3'], 'selected_value' => $dtks->kd_daya_terpasang3])
        </div>
    </div>
    <hr class="col-sm-12">
    <div id="col_3_308" class="col-sm-12">
        <div class="form-group">
            <label for="pilihan_3_308">308. Bahan bakar/energi utama untuk memasak</label>
            @include('admin.layouts.components.select_pilihan_dtks', ['class' => 'select2', 'attribut' => 'id="pilihan_3_308" name="pilihan[3][308]"', 'pilihan' => $pilihan3['308'], 'selected_value' => $dtks->kd_bahan_bakar_memasak])
        </div>
    </div>
    <div id="col_3_309a" class="col-sm-6">
        <div class="form-group">
            <label for="pilihan_3_309a">309a. Kepemilikan dan penggunaan fasilitas tempat buang air besar</label>
            @include('admin.layouts.components.select_pilihan_dtks', ['class' => 'select2', 'attribut' => 'id="pilihan_3_309a" name="pilihan[3][309a]"', 'pilihan' => $pilihan3['309a'], 'selected_value' => $dtks->kd_fasilitas_tempat_bab])
        </div>
    </div>
    <div id="col_3_309b" class="col-sm-6">
        <div class="form-group">
            <label for="pilihan_3_309b">309b. Jenis kloset</label>
            @include('admin.layouts.components.select_pilihan_dtks', ['class' => 'select2', 'attribut' => 'id="pilihan_3_309b" name="pilihan[3][309b]"', 'pilihan' => $pilihan3['309b'], 'selected_value' => $dtks->kd_jenis_kloset])
        </div>
    </div>
    <div id="col_3_310" class="col-sm-12">
        <div class="form-group">
            <label for="pilihan_3_310">310. Tempat pembuangan akhir tinja:</label>
            @include('admin.layouts.components.select_pilihan_dtks', ['class' => 'select2', 'attribut' => 'id="pilihan_3_310" name="pilihan[3][310]"', 'pilihan' => $pilihan3['310'], 'selected_value' => $dtks->kd_pembuangan_akhir_tinja])
        </div>
    </div>

    <hr class="col-sm-12">
    <div class="col-sm-12 text-center">
        <button type="reset" class="btn btn-social btn-danger btn-sm"><i class='fa fa-times'></i>Batal</button>
        <button type="button" class="next-prev-bagian-3 btn btn-social btn-default btn-sm"><i class='fa fa-arrow-left'></i> Sebelumnya</button>
        <button type="button" class="next-prev-bagian-3 btn btn-social btn-default btn-sm">Selanjutnya <i class="fa fa-arrow-right"></i></button>
        <button type="subumit" class="btn btn-social btn-info btn-sm"><i class="fa fa-check"></i>Simpan</button>
    </div>
</div>
</form>
@push('scripts')
    <script>
        $(document).ready(function() {
            let default_hide = ['col_3_301b', 'col_3_306b', 'col_3_307b', 'col_3_307b1', 'col_3_307b2', 'col_3_307b3'];
            default_hide.forEach(id => {
                $('#' + id).hide();
            });
            show_when_otherwise_hide(['1'].indexOf($('#pilihan_3_301a').val()) > -1, ['col_3_301b'], ['col_3_301b']);
            show_when_otherwise_hide(['4', '5', '6', '7', '8'].indexOf($('#pilihan_3_306a').val()) > -1, ['col_3_306b'], ['col_3_306b']);
            show_when_otherwise_hide(['1'].indexOf($('#pilihan_3_307a').val()) > -1, ['col_3_307b', 'col_3_307b1', 'col_3_307b2', 'col_3_307b3'], ['col_3_307b', 'col_3_307b1', 'col_3_307b2', 'col_3_307b3']);
            show_when_otherwise_hide(['1', '2', '3'].indexOf($('#pilihan_3_309a').val()) > -1, ['col_3_309b'], ['col_3_309b']);

            $('#form-3 button[type=reset]').on('click', function(ev) {
                setTimeout(() => {
                    $('#form-3 select').trigger('change');
                }, 200);
            });
            $('#pilihan_3_301a').on('change', function(ev) {
                show_when_otherwise_hide(['1'].indexOf($('#pilihan_3_301a').val()) > -1, ['col_3_301b'], ['col_3_301b', 'col_3_301c']);
            });
            $('#pilihan_3_306a').on('change', function(ev) {
                show_when_otherwise_hide(['4', '5', '6', '7', '8'].indexOf($('#pilihan_3_306a').val()) > -1, ['col_3_306b'], ['col_3_306b']);
            });
            $('#pilihan_3_307a').on('change', function(ev) {
                show_when_otherwise_hide(['1'].indexOf($('#pilihan_3_307a').val()) > -1, ['col_3_307b', 'col_3_307b1', 'col_3_307b2', 'col_3_307b3'], ['col_3_307b', 'col_3_307b1', 'col_3_307b2', 'col_3_307b3']);
            });
            $('#pilihan_3_309a').on('change', function(ev) {
                show_when_otherwise_hide(['1', '2', '3'].indexOf($('#pilihan_3_309a').val()) > -1, ['col_3_309b'], ['col_3_309b']);
            });

            $('.next-prev-bagian-3').on('click', function() {
                let is_valid = is_form_valid($(`#form-3`).attr('id'));
                if (!is_valid) {
                    return false;
                }

                let form = $('#form-3').serializeArray();
                $('#form-3 select').each(function(index, el) {
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
                    $(`#nav-bagian-4`).trigger('click');
                } else {
                    $(`#nav-bagian-2`).trigger('click');
                }
            });
            $('#form-3').on('submit', function(ev) {
                ev.preventDefault();
                let is_valid = is_form_valid($(this).attr('id'));
                if (!is_valid) {
                    return false;
                }

                let form = $('#form-3').serializeArray();
                $('#form-3 select').each(function(index, el) {
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
