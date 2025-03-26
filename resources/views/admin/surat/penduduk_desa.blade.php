<div class="penduduk_form penduduk_desa {{ in_array(old("{$kategori}.opsi_penduduk"), [2, 3]) ? 'hide' : '' }}">
    <div class="form-group">
        <label for="nik" class="col-sm-3 control-label">NIK / Nama</label>
        <div class="col-sm-6 col-lg-4">
            <select
                autofocus
                name="{{ $kategori }}[nik]"
                class="form-control input-sm isi-penduduk-desa nama-kategori-{{ $kategori }} {{ $kategori == 'individu' ? 'required' : '' }} select2-nik-ajax"
                data-old_{{ $kategori }}_nik="{{ old("id_pend_{$kategori}") }}"
                data-surat="{{ $surat->id }}"
                data-hubungan="{{ $surat->form_isian->$kategori->hubungan }}"
                data-kategori="{{ $kategori }}"
                data-url="{{ site_url('surat/list_penduduk_ajax') }}"
                data-sumber_penduduk_berulang="{{ setting('sumber_penduduk_berulang_surat') ?? $surat->sumber_penduduk_berulang }}"
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
        function loadDataPenduduk(element) {
            let suratId = $(element).data('surat');
            let selectedValue = $(element).val();
            let kategori = $(element).data('kategori');
            let pendudukDesaElement = $(element).closest('.penduduk_desa');
            pendudukDesaElement.find('.data_penduduk_desa').empty();
            if (!$.isEmptyObject(selectedValue)) {
                $.get('{{ ci_route('datasuratpenduduk.index') }}', {
                    id_surat: suratId,
                    id_penduduk: selectedValue,
                    kategori: kategori
                }, function(response) {
                    pendudukDesaElement.find('.data_penduduk_desa').html(response.html);

                    for (let i = 0; i < response.hubungan.length; i++) {
                        let hubungan = response.hubungan[i];
                        let option = response[`option${hubungan}`];
                        let html = response[`html${hubungan}`];
                        $(`#kategori-${hubungan}`).find('.select2-nik-ajax').empty().append(option);
                        $(`#kategori-${hubungan}`).find('.data_penduduk_desa').empty().html(html);
                    }
                }, 'json');
            }
            pendudukDesaElement.find('.data_penduduk_desa').show();
        }
    </script>
@endpush
