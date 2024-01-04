{!! form_open('', 'class="form-validasi" id="form-2"') !!}
<input type="hidden" name='tipe_save' value='bagian2'>
<div class="row">
    <div class="col-sm-3">
        <div class="form-group">
            <label for="input_2_201">201. Tanggal Pendataan</label>
            <input class="form-control input-sm" name="input[2][201]" id="input_2_201" type="date" value="{{ $dtks->tanggal_pendataan ? $dtks->tanggal_pendataan->format('Y-m-d') : '' }}" />
        </div>
    </div>
    <div class="col-sm-5">
        <div class="form-group">
            <label for="input_2_202">202. Nama PPL</label>
            <input maxlength="100" name="input[2][202]" id="input_2_202" class="form-control input-sm nama" type="text" value="{{ $dtks->nama_ppl }}">
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label for="input_2_202a">202a. Kode PPL <code>(4 angka/huruf)</code></label>
            <input maxlength="4" name="input[2][202a]" id="input_2_202a" class="form-control input-sm alfanumerik" type="text" value="{{ $dtks->kode_ppl }}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-3">
        <div class="form-group">
            <label for="input_2_203">203. Tanggal Pemeriksaan</label>
            <input class="form-control input-sm" name="input[2][203]" id="input_2_203" type="date" value="{{ $dtks->tanggal_pemeriksaan ? $dtks->tanggal_pemeriksaan->format('Y-m-d') : '' }}" />
        </div>
    </div>
    <div class="col-sm-5">
        <div class="form-group">
            <label for="input_2_204">204. Nama Pemeriksa</label>
            <input maxlength="100" name="input[2][204]" id="input_2_204" class="form-control input-sm nama" type="text" value="{{ $dtks->nama_pml }}">
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label for="input_2_204a">204a. Kode Pemeriksa <code>(3 angka/huruf)</code></label>
            <input maxlength="3" name="input[2][204a]" id="input_2_204a" class="form-control input-sm alfanumerik" type="text" value="{{ $dtks->kode_pml }}">
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="input_2_responden">Nama Responden</label>
            <input maxlength="100" name="input[2][responden]" id="input_2_responden" class="form-control input-sm nama" type="text" value="{{ $dtks->nama_responden }}">
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="input_2_responden_hp">Nomor Handphone Responden</label>
            <input maxlength="16" name="input[2][responden_hp]" id="input_2_responden_hp" class="form-control input-sm number" type="text" value="{{ $dtks->no_hp_responden }}">
            <label for="telepon" generated="true" class="error" style="display: none;">Silakan masukkan angka yang benar.</label>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label for="pilihan_2_205">205. Hasil pendataan keluarga</label>
            @include('admin.layouts.components.select_pilihan_dtks', ['class' => 'select2', 'attribut' => 'id="pilihan_2_205" name="pilihan[2][205]"', 'pilihan' => $pilihan2['205'], 'selected_value' => $dtks->kd_hasil_pendataan_keluarga])
        </div>
    </div>

    <hr class="col-sm-12">
    <div class="col-sm-12 text-center">
        <button type="reset" class="btn btn-social btn-danger btn-sm"><i class='fa fa-times'></i>Batal</button>
        <button type="button" class="next-prev-bagian-2 btn btn-social btn-default btn-sm"><i class='fa fa-arrow-left'></i> Sebelumnya</button>
        <button type="button" class="next-prev-bagian-2 btn btn-social btn-default btn-sm">Selanjutnya <i class="fa fa-arrow-right"></i></button>
        <button type="submit" class="btn btn-social btn-info btn-sm"><i class="fa fa-check"></i>Simpan</button>
    </div>
</div>
</form>
@push('scripts')
    <script>
        $(document).ready(function() {
            $('.next-prev-bagian-2').on('click', function() {
                let is_valid = is_form_valid($(`#form-2`).attr('id'));
                if (!is_valid) {
                    return false;
                }

                let form = $('#form-2').serializeArray();
                $('#form-2 select').each(function(index, el) {
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
                    $(`#nav-bagian-3`).trigger('click');
                } else {
                    $(`#nav-bagian-1`).trigger('click');
                }
            });
            $('#form-2 button[type=reset]').on('click', function(ev) {
                setTimeout(() => {
                    $('#form-2 select').trigger('change');
                }, 200);
            });
            $('#form-2').on('submit', function(ev) {
                ev.preventDefault();
                let is_valid = is_form_valid($(this).attr('id'));
                if (!is_valid) {
                    return false;
                }

                let form = $('#form-2').serializeArray();
                $('#form-2 select').each(function(index, el) {
                    form.push({
                        'name': $(el).attr('name'),
                        'value': $(el).val()
                    });
                });
                ajax_save_dtks("{{ ci_route('dtks.save') . '/' . $dtks->id }}", form);
            });
        })
    </script>
@endpush
