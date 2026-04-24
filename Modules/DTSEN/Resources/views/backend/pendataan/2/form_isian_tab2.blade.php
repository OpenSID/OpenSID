{!! form_open('', 'class="form-validasi" id="form-2"') !!}
<input type="hidden" name='tipe_save' value='bagian2'>
<div class="row">
    <div class="col-sm-3">
        <div class="form-group">
            <label for="input_2_201">201. Tanggal Pendataan</label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control input-sm tgl_1 pull-right" name="input[2][201]" id="input_2_201" type="text" value="{{ $dtsen->tanggal_pendataan ? $dtsen->tanggal_pendataan->format('d-m-Y') : '' }}" />
            </div>
        </div>
    </div>
    <div class="col-sm-5">
        <div class="form-group">
            <label for="input_2_202">202. Nama PPL</label>
            <input maxlength="100" name="input[2][202]" id="input_2_202" class="form-control input-sm nama" type="text" value="{{ $dtsen->nama_ppl }}">
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label for="input_2_202a">202a. Kode PPL <code>(4 angka/huruf)</code></label>
            <input maxlength="4" name="input[2][202a]" id="input_2_202a" class="form-control input-sm alfanumerik" type="text" value="{{ $dtsen->kode_ppl }}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-3">
        <div class="form-group">
            <label for="input_2_203">203. Tanggal Pemeriksaan</label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control input-sm tgl_1 pull-right" name="input[2][203]" id="input_2_203" type="text" value="{{ $dtsen->tanggal_pemeriksaan ? $dtsen->tanggal_pemeriksaan->format('d-m-Y') : '' }}" />
            </div>
        </div>
    </div>
    <div class="col-sm-5">
        <div class="form-group">
            <label for="input_2_204">204. Nama Pemeriksa</label>
            <input maxlength="100" name="input[2][204]" id="input_2_204" class="form-control input-sm nama" type="text" value="{{ $dtsen->nama_pml }}">
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label for="input_2_204a">204a. Kode Pemeriksa <code>(3 angka/huruf)</code></label>
            <input maxlength="3" name="input[2][204a]" id="input_2_204a" class="form-control input-sm alfanumerik" type="text" value="{{ $dtsen->kode_pml }}">
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="input_2_responden">Nama Responden</label>
            <input maxlength="100" name="input[2][responden]" id="input_2_responden" class="form-control input-sm nama" type="text" value="{{ $dtsen->nama_responden }}">
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="input_2_responden_hp">Nomor Handphone Responden</label>
            <input maxlength="16" name="input[2][responden_hp]" id="input_2_responden_hp" class="form-control input-sm number" type="text" value="{{ $dtsen->no_hp_responden }}">
            <label for="telepon" generated="true" class="error" style="display: none;">Silakan masukkan angka yang benar.</label>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label for="pilihan_2_205">205. Hasil pendataan keluarga</label>
            @include('admin.layouts.components.select_pilihan_dtsen', ['class' => 'select2', 'attribut' => 'id="pilihan_2_205" name="pilihan[2][205]"', 'pilihan' => $pilihan2['205'], 'selected_value' => $dtsen->kd_hasil_pendataan_keluarga])
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label for="pilihan_2_206">Status kesejahteraan</label>
            @include('admin.layouts.components.select_pilihan_dtsen', ['class' => 'select2', 'attribut' => 'id="pilihan_2_206" name="pilihan[2][206]"', 'pilihan' => $pilihan2['206'], 'selected_value' => $dtsen->kd_status_kesejahteraan])
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label for="pilihan_2_207">Peringkat kesejahteraan keluarga</label>
            @include('admin.layouts.components.select_pilihan_dtsen', ['class' => 'select2', 'attribut' => 'id="pilihan_2_207" name="pilihan[2][207]"', 'pilihan' => $pilihan2['207'], 'selected_value' => $dtsen->kd_peringkat_kesejahteraan_keluarga])
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

                let selajutnya = $(this).text().includes("Selanjutnya");
                
                $.ajax({
                    method: 'POST',
                    url: "{{ route('dtsen_pendataan.save', $dtsen->id) }}",
                    data: form,
                    dataType: 'json'
                }).done(function() {
                    if (selajutnya) {
                        $(`#nav-bagian-7`).trigger('click');
                    } else {
                        $(`#nav-bagian-5`).trigger('click');
                    }
                }).fail(function() {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Gagal menyimpan',
                        text: 'Data tidak tersimpan. Silakan coba lagi.'
                    });
                });
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
                
                let btn = $(this).find('button[type=submit]');
                let originalContent = btn.html();
                btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');
                
                // ↓ UBAH BAGIAN INI
                ajax_save_dtsen("{{ route('dtsen_pendataan.save', $dtsen->id) }}", form, 
                    function() {
                        btn.prop('disabled', false).html(originalContent);
                        
                        // Ambil nilai field 205 yang baru dipilih
                        var nilai205 = $('#pilihan_2_205').val();
                        var urlKembali = "{{ ci_route('dtsen/pendataan') }}";
                        var HASIL_LENGKAP = "{{ \Modules\DTSEN\Enums\DtsenEnum::HASIL_PENDATAAN_TERISI_LENGKAP }}";

                        @php
                            $mapLabelHasilPendataan = collect(Modules\DTSEN\Enums\Regsosek2022kEnum::pilihanBagian2()['205'])
                                ->mapWithKeys(static fn($label, $key) => [
                                    (string) $key => preg_replace('/^\d+\.\s*/', '', $label)
                                ])
                                ->prepend('Hasil pendataan belum dipilih', '')
                                ->toArray();
                        @endphp

                        var mapLabelHasilPendataan  = {!! json_encode($mapLabelHasilPendataan) !!};

                        var statusLabel = mapLabelHasilPendataan[nilai205] || 'Belum diketahui';

                        if (nilai205 == HASIL_LENGKAP) {
                            // Terisi lengkap → redirect langsung
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil Disimpan!',
                                text: 'Data lengkap. Kembali ke halaman daftar.',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(function() {
                                window.location.href = urlKembali;
                            });

                        } else {

                            Swal.fire({
                                icon: 'warning',
                                title: 'Data Belum Lengkap',
                                html: '<p>Status: <strong>' + statusLabel + '</strong></p>'
                                    + '<p>Apakah ingin melanjutkan pengisian?</p>',
                                showCancelButton: true,
                                confirmButtonText: '<i class="fa fa-edit"></i> Lanjut Pengisian',
                                cancelButtonText:  '<i class="fa fa-sign-out"></i> Kembali ke Daftar',
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor:  '#f39c12',
                            }).then(function(result) {
                                if (!result.isConfirmed) {
                                    // Pilih "Kembali ke Daftar"
                                    window.location.href = urlKembali;
                                }
                                // Pilih "Lanjut Pengisian" → tidak melakukan apa-apa
                            });
                        }
                    }, 
                    function() {
                        // callback gagal
                        btn.prop('disabled', false).html(originalContent);
                    }
                );
            });
        })
    </script>
@endpush
