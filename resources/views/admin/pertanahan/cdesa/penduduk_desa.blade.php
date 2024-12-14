<div class="penduduk_form penduduk_desa">
    <div class="form-group">
        <label for="nik" class="col-sm-3 control-label">Cari Nama Pemilik</label>
        <div class="col-sm-6 col-lg-4">
            <select autofocus name="nik" class="form-control input-sm isi-penduduk-desa required select2-nik-ajax" data-url="{{ site_url('surat/list_penduduk_ajax') }}" data-placeholder="-- Cari NIK / Tag ID Card / Nama Penduduk --" onchange="loadDataPenduduk(this)">
            </select>
        </div>
    </div>
    <div class="data_penduduk_desa"></div>
</div>
@push('scripts')
    <script type="text/javascript">
        function loadDataPenduduk(elm) {
            let _val = $(elm).val()
            let _pendudukDesaElm = $(elm).closest('.penduduk_desa')
            _pendudukDesaElm.find('.data_penduduk_desa').empty()
            if (!$.isEmptyObject(_val)) {
                $.get('{{ ci_route('datasuratpenduduk.index') }}', {
                    id_penduduk: _val
                }, function(data) {
                    _pendudukDesaElm.find('.data_penduduk_desa').html(data.html)
                }, 'json')
            }
        }
    </script>
@endpush
