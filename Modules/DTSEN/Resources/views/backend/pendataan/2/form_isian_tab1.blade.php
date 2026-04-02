{!! form_open('', 'class="form-validasi" id="form-1"') !!}
<input type="hidden" name='tipe_save' value='bagian1'>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label class="control-label" for="provinsi">101. Provinsi ({{ $dtsen_prov }})</label>
            <input name="kode_provinsi" id="provinsi" class="form-control input-sm" disabled type="text" value="{{ $dtsen->kode_provinsi }}">
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label class="control-label" for="kabupaten">102. Kabupaten/Kota ({{ $dtsen_kab }})</label>
            <input name="kode_kabupaten" id="kabupaten" class="form-control input-sm" disabled type="text" value="{{ $dtsen->kode_kabupaten }}">
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label class="control-label" for="kecamatan">103. Kecamatan ({{ $dtsen_kec }})</label>
            <input name="kode_kecamatan" id="kecamatan" class="form-control input-sm" disabled type="text" value="{{ $dtsen->kode_kecamatan }}">
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label class="control-label" for="desa">104. Desa/Kelurahan ({{ $dtsen_desa }})</label>
            <input name="kode_desa" id="desa" class="form-control input-sm" disabled type="text" value="{{ $dtsen->kode_desa }}">
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-group">
            <label class="control-label" for="input_1_105">105. Kode SLS/Non SLS <br><code>(4 angka/huruf)</code></label>
            <input name="input[1][105]" id="input_1_105" maxlength="4" class="form-control input-sm alfanumerik" type="text" value="{{ $dtsen->kode_sls_non_sls }}">
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-group">
            <label class="control-label" for="input_1_105sub">105a. Kode Sub SLS <br><code>(2 angka/huruf)</code></label>
            <input name="input[1][105sub]" id="input_1_105sub" maxlength="2" class="form-control input-sm alfanumerik" type="text" value="{{ $dtsen->kode_sub_sls }}">
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label class="control-label" for="input_1_106">106. Nama SLS/NON SLS</label>
            <input name="input[1][106]" id="input_1_106" maxlength="100" class="form-control input-sm" type="text" value="{{ $dtsen->nama_sls_non_sls }}">
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label class="control-label" for="input_1_107">107. Alamat (Jalan/Gang/Nomor Rumah)</label>
            <textarea name="input[1][107]" disabled id="input_1_107" class="form-control input-sm">{{ $dtsen->alamat }}</textarea>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label class="control-label" for="nama_krt">108. Nama Kepala Keluarga (kk)</label>
            <input name="nama_krt" id="nama_krt" class="form-control input-sm" disabled type="text" value="{{ $dtsen->kepala_keluarga->nama }}">
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label class="control-label" for="input_1_109">109. No Urut Bangunan Tempat Tinggal <code>(3 digit angka)</code></label>
            <input name="input[1][109]" id="input_1_109" maxlength="3" class="form-control input-sm angka" type="text" value="{{ $dtsen->no_urut_bangunan_tinggal }}">
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label class="control-label" for="input_1_110">110. No Urut Keluarga Hasil Verifikasi <code>(3 angka)</code></label>
            <input name="input[1][110]" id="input_1_110" maxlength="3" class="form-control input-sm angka" type="text" value="{{ $dtsen->no_urut_keluarga_verif }}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label class="control-label" for="latitude">Latitude</label>
            <input name="latitude" id="latitude" class="form-control input-sm" type="text" value="{{ $dtsen->latitude }}">
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label class="control-label" for="longitude">Longitude</label>
            <input name="longitude" id="longitude" class="form-control input-sm" type="text" value="{{ $dtsen->longitude }}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6" style="display: none;">
        <div class="form-group">
            <label class="control-label" for="input_1_111">111. Status Keluarga <code>(1 angka)</code></label>
            <input name="input[1][111]" id="input_1_111" maxlength="1" class="form-control input-sm angka" type="text" value="{{ $dtsen->status_keluarga }}">
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label class="control-label" for="jumlah_anggota_dtsen">112. Jumlah Anggota Keluarga</label>
            <input name="jumlah_anggota_dtsen" id="jumlah_anggota_dtsen" class="form-control input-sm" disabled type="text" value="{{ $dtsen->jumlah_anggota_dtsen }}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6" style="display: none;">
        <div class="form-group">
            <label class="control-label" for="input_1_113">113. ID Landmark Wilkerstat <code>(6 angka/huruf)</code></label>
            <input name="input[1][113]" id="input_1_113" maxlength="6" class="form-control input-sm alfanumerik" type="text" value="{{ $dtsen->kode_landmark_wilkerstat }}">
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label class="control-label" for="no_kk">114. Nomor Kartu Keluarga</label>
            <input name="no_kk" id="no_kk" class="form-control input-sm" disabled type="text" value="{{ $dtsen->kepala_keluarga->keluarga->no_kk }}">
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label class="control-label" for="email">115. Kode Kartu Keluarga</label>
            @include('admin.layouts.components.select_pilihan_dtsen', ['class' => 'select2', 'attribut' => 'id="pilihan_1_115" name="pilihan[1][115]"', 'pilihan' => $pilihan1['115'], 'selected_value' => $dtsen->kd_kk])
        </div>
    </div>

    <hr class="col-sm-12">
    <div class="col-sm-12 text-center">
        <button type="reset" class="btn btn-social btn-danger btn-sm"><i class='fa fa-times'></i>Batal</button>
        <button type="button" disabled class="btn btn-social btn-default btn-sm"><i class='fa fa-arrow-left'></i> Sebelumnya</button>
        <button type="button" class="next-prev-bagian-1 btn btn-social btn-default btn-sm">Selanjutnya <i class="fa fa-arrow-right"></i></button>
        <button type="submit" class="btn btn-social btn-info btn-sm"><i class="fa fa-check"></i>Simpan</button>
    </div>
</div>
</form>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.next-prev-bagian-1').on('click', function() {
                let is_valid = is_form_valid($(`#form-1`).attr('id'));
                if (!is_valid) {
                    return false;
                }

                let form = $('#form-1').serializeArray();
                $('#form-1 select').each(function(index, el) {
                    form.push({
                        'name': $(el).attr('name'),
                        'value': $(el).val()
                    });
                });

                $.ajax({
                    type: 'POST',
                    url: "{{  ci_route('dtsen/pendataan/save') . '/' . $dtsen->id }}",
                    data: form,
                });

                $(`#nav-bagian-2`).trigger('click');
            })
            $('#form-1 button[type=reset]').on('click', function(ev) {
                setTimeout(() => {
                    $('#form-1 select').trigger('change');
                }, 200);
            });
            $('#form-1').on('submit', function(ev) {
                ev.preventDefault();
                let is_valid = is_form_valid($(this).attr('id'));
                if (!is_valid) {
                    return false;
                }

                let form = $('#form-1').serializeArray();
                $('#form-1 select').each(function(index, el) {
                    form.push({
                        'name': $(el).attr('name'),
                        'value': $(el).val()
                    });
                });
                ajax_save_dtsen("{{  ci_route('dtsen/pendataan/save') . '/' . $dtsen->id }}", form);
            });
        })
    </script>
@endpush
