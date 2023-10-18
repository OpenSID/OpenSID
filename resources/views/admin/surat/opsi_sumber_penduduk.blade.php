<div class="btn-group col-sm-8" data-toggle="buttons">
    @foreach($opsiSumberPenduduk as $sumberPenduduk)
    <label for="penduduk_{{$sumberPenduduk}}" class="btn btn-info btn-flat btn-sm col-sm-4 col-sm-4 col-md-4 col-lg-3 form-check-label {{ $sumberPenduduk == 1 ? 'active' : ''}}">
        <input name="{{ $kategori }}[opsi_penduduk]" type="radio" class="form-check-input" value="{{ $sumberPenduduk }}" autocomplete="off" onchange="dataPenduduk(this);"> {{ $sumberPenduduk == 1 ? 'Warga Desa' : 'Warga Luar Desa' }}
    </label>
    @endforeach
</div>
@push('scripts')
    <script type="text/javascript">
        function dataPenduduk(elm) {
            let _formGroup = $(elm).closest('.form-group')
            let _val = $(elm).val()
            _formGroup.nextAll('.penduduk_luar_desa').first().addClass('hide')
            _formGroup.next('.penduduk_desa').addClass('hide')
            // reset semua data yang telah dimasukkan
            _formGroup.next('.penduduk_desa').find('select.select2-nik-ajax').empty()
            _formGroup.next('.penduduk_desa').find('.data_penduduk_desa').empty()
            _formGroup.nextAll('.penduduk_luar_desa').first().find('input, select, textarea').val('')
            if (_val == 1) {
                _formGroup.next('.penduduk_desa').removeClass('hide')
            }else {
                _formGroup.nextAll('.penduduk_luar_desa').first().removeClass('hide')
            }
        }
    </script>
@endpush
