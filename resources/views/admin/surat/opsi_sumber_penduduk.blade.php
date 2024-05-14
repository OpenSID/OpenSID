<div class="btn-group col-sm-8" data-toggle="buttons">
    @foreach ($opsiSumberPenduduk as $sumberPenduduk)
        <label style="text-transform: uppercase;" for="penduduk_{{ $sumberPenduduk }}" class="btn btn-info btn-flat btn-sm col-sm-6 col-md-6 col-lg-6 form-check-label {{ $sumberPenduduk == 1 ? 'active' : '' }}">
            <input name="{{ $kategori }}[opsi_penduduk]" type="radio" class="form-check-input" value="{{ $sumberPenduduk }}" autocomplete="off" onchange="dataPenduduk(this);"> {{ sebutanDesa($sumberPenduduk == 1 ? 'PENDUDUK [desa]' : $pendudukLuar[$sumberPenduduk]['title'] ?? 'Luar [desa]') }}
        </label>
    @endforeach
</div>
@push('scripts')
    <script type="text/javascript">
        function dataPenduduk(elm) {
            let _formGroup = $(elm).closest('.form-group')
            let _val = $(elm).val()
            _formGroup.nextUntil('.form-group').addClass('hide')
            _formGroup.next('.penduduk_desa').addClass('hide')
            // reset semua data yang telah dimasukkan
            _formGroup.next('.penduduk_desa').find('select.select2-nik-ajax').empty()
            _formGroup.next('.penduduk_desa').find('.data_penduduk_desa').empty()
            _formGroup.nextAll('.penduduk_luar_desa').find('input, select, textarea').val('')
            if (_val == 1) {
                _formGroup.next('.penduduk_desa').removeClass('hide')
                _formGroup.next('.penduduk_luar_desa').find('.isi-penduduk-luar').removeClass('required')
                _formGroup.next('.penduduk_desa').find('.isi-penduduk-desa').addClass('required')
                $('[data-visible-required=1]:hidden').removeClass('required')
            } else {
                _formGroup.next('.penduduk_luar_desa').find('.isi-penduduk-luar').addClass('required')
                _formGroup.next('.penduduk_desa').find('.isi-penduduk-desa').removeClass('required')
                _formGroup.nextAll('.penduduk_luar_' + _val).first().removeClass('hide')
                $('[data-visible-required=1]:visible').addClass('required')
            }
        }
    </script>
@endpush
