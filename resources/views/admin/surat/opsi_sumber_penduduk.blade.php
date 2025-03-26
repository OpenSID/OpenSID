<div class="btn-group col-sm-8" data-toggle="buttons">
    @foreach ($opsiSumberPenduduk as $sumberPenduduk)
        <label style="text-transform: uppercase;" for="penduduk_{{ $sumberPenduduk }}" class="btn btn-info btn-flat btn-sm col-sm-6 col-md-6 col-lg-6 form-check-label {{ old("{$kategori}.opsi_penduduk", 1) == $sumberPenduduk ? 'active' : '' }}">
            <input name="{{ $kategori }}[opsi_penduduk]" type="radio" class="form-check-input" value="{{ $sumberPenduduk }}" {{ old("{$kategori}.opsi_penduduk", 1) == $sumberPenduduk ? 'checked' : '' }} autocomplete="off">
            {{ sebutanDesa($sumberPenduduk == 1 ? 'PENDUDUK [desa]' : $pendudukLuar[$sumberPenduduk]['title'] ?? 'Luar [desa]') }}
        </label>
    @endforeach
</div>
@push('scripts')
    <script type="text/javascript">
        function dataPenduduk(element) {
            let formGroup = $(element).closest('.form-group');
            let value = $(element).val();

            formGroup.nextAll('.penduduk_form').addClass('hide');
            formGroup.next('.penduduk_desa').addClass('hide');
            // reset all entered data
            formGroup.next('.penduduk_desa').find('select.select2-nik-ajax').val('').trigger('change');
            formGroup.next('.penduduk_desa').find('.data_penduduk_desa').empty();
            formGroup.nextAll('.penduduk_luar_desa').find('input, textarea').val('');
            formGroup.nextAll('.penduduk_luar_desa').find('select, select2').val('').trigger('change');

            if (value == 1) {
                formGroup.next('.penduduk_desa').removeClass('hide');
                formGroup.next('.penduduk_luar_desa').find('.isi-penduduk-luar').removeClass('required');
                formGroup.next('.penduduk_desa').find('.isi-penduduk-desa').addClass('required');
                $('[data-visible-required=1]:hidden').removeClass('required');
            } else {
                formGroup.nextAll(`.penduduk_luar_${value}`).first().removeClass('hide');
                formGroup.next('.penduduk_luar_desa').find('.isi-penduduk-luar').addClass('required');
                formGroup.next('.penduduk_desa').find('.isi-penduduk-desa').removeClass('required');
                $('[data-visible-required=1]:visible').addClass('required');
            }
        }

        $(document).ready(function() {
            var kategori = '{{ $kategori }}';
            var inputName = kategori + '[opsi_penduduk]';
            var inputElement = $(`input[name="${inputName}"]:checked`);
            var pilih = inputElement.val();

            $(`input[name="${inputName}"]`).change(function() {
                var inputElement = $(this).filter(':checked')[0];
                dataPenduduk(inputElement);
            });
        });
    </script>
@endpush
