<div class="penduduk_desa">
    <div class="form-group">
        <label for="nik" class="col-sm-3 control-label">NIK / Nama</label>
        <div class="col-sm-6 col-lg-4">
            <select
                autofocus
                name="{{ $kategori }}[nik]"
                class="form-control input-sm isi-penduduk-desa {{ $kategori == 'individu' ? 'required' : '' }} select2-nik-ajax"
                data-surat="{{ $surat->id }}"
                data-hubungan="{{ $surat->form_isian->$kategori->hubungan }}"
                data-kategori="{{ $kategori }}"
                data-url="{{ site_url('surat/list_penduduk_ajax') }}"
                data-sumber_penduduk_berulang="{{ $surat->sumber_penduduk_berulang }}"
                data-placeholder="-- Cari NIK / Tag ID Card / Nama Penduduk --"
                onchange="loadDataPenduduk(this)"
            >
            </select>
        </div>
    </div>
    <div class="data_penduduk_desa"></div>
</div>
@push('scripts')
    <script type="text/javascript">
        function loadDataPenduduk(elm) {
            let _idSurat = $(elm).data('surat')
            let _val = $(elm).val()
            let _kategori = $(elm).data('kategori')
            let _pendudukDesaElm = $(elm).closest('.penduduk_desa')
            _pendudukDesaElm.find('.data_penduduk_desa').empty()
            if (!$.isEmptyObject(_val)) {
                $.get('{{ ci_route('datasuratpenduduk.index') }}', {
                    id_surat: _idSurat,
                    id_penduduk: _val,
                    kategori: _kategori
                }, function(data) {
                    _pendudukDesaElm.find('.data_penduduk_desa').html(data.html)
                }, 'json')
            }
        }
    </script>
@endpush
