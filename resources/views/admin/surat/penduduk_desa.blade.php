@use('App\Libraries\UserAgent')

<div class="penduduk_form penduduk_desa {{ in_array(old("{$kategori}.opsi_penduduk"), array_keys($pendudukLuar)) ? 'hide' : '' }}">
    <div class="form-group">
        <label for="nik" class="col-sm-3 control-label">NIK / Nama</label>
        <div class="col-sm-8">
            @if ((new UserAgent())->is_mobile())
                <select
                    autofocus
                    name="{{ $kategori }}[nik]"
                    class="form-control input-sm isi-penduduk-desa nama-kategori-{{ $kategori }} {{ $kategori == 'individu' || $surat->form_isian->{$kategori}->sumber_wajib ? 'required' : '' }} select2-nik-ajax"
                    data-old_{{ $kategori }}_nik="{{ old("id_pend_{$kategori}") }}"
                    data-surat="{{ $surat->id }}"
                    data-hubungan="{{ $surat->form_isian->$kategori->hubungan }}"
                    data-kategori="{{ $kategori }}"
                    data-url="{{ site_url('surat/list_penduduk_ajax') }}"
                    data-sumber_penduduk_berulang="{{ $surat->sumber_penduduk_berulang ?? setting('sumber_penduduk_berulang_surat') }}"
                    data-placeholder="-- Cari NIK / Tag ID Card / Nama Penduduk --"
                    onchange="loadDataPenduduk(this);"
                ></select>
                <button
                    type="button"
                    class="btn btn-warning btn-sm ubah-biodata-link btn-block"
                    id="ubah-biodata-{{ $kategori }}"
                    disabled
                    title="Ubah Biodata Penduduk"
                    style="margin-top: 5px;"
                    onclick="ubahBiodataPenduduk('{{ $kategori }}', this);"
                >
                    <i class="fa fa-edit"></i> Ubah Biodata Penduduk
                </button>
            @else
                <div class="input-group input-group-sm">
                    <select
                        autofocus
                        name="{{ $kategori }}[nik]"
                        class="form-control input-sm isi-penduduk-desa nama-kategori-{{ $kategori }} {{ $kategori == 'individu' || $surat->form_isian->{$kategori}->sumber_wajib ? 'required' : '' }} select2-nik-ajax"
                        data-old_{{ $kategori }}_nik="{{ old("id_pend_{$kategori}") }}"
                        data-surat="{{ $surat->id }}"
                        data-hubungan="{{ $surat->form_isian->$kategori->hubungan }}"
                        data-kategori="{{ $kategori }}"
                        data-url="{{ site_url('surat/list_penduduk_ajax') }}"
                        data-sumber_penduduk_berulang="{{ $surat->sumber_penduduk_berulang ?? setting('sumber_penduduk_berulang_surat') }}"
                        data-placeholder="-- Cari NIK / Tag ID Card / Nama Penduduk --"
                        onchange="loadDataPenduduk(this);"
                    ></select>
                    <span class="input-group-btn">
                        <button
                            type="button"
                            class="btn btn-warning btn-flat ubah-biodata-link"
                            id="ubah-biodata-{{ $kategori }}"
                            disabled
                            title="Ubah Biodata Penduduk"
                            onclick="ubahBiodataPenduduk('{{ $kategori }}', this);"
                        >
                            <i class="fa fa-edit"></i>
                        </button>
                    </span>
                </div>
            @endif
        </div>
    </div>
    <div class="data_penduduk_desa"></div>
</div>

<div class="modal fade" id="modal-ubah-biodata" tabindex="-1" role="dialog" aria-labelledby="ubahBiodataLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                <h4 class="modal-title">Ubah Biodata Penduduk</h4>
            </div>
            <div class="modal-body" id="modal-biodata-body">
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        function loadDataPenduduk(element) {
            let suratId = $(element).data('surat');
            let selectedValue = $(element).val();
            let kategori = $(element).data('kategori');
            let pendudukDesaElement = $(element).closest('.penduduk_desa');
            pendudukDesaElement.find('.data_penduduk_desa').empty();

            if (! $.isEmptyObject(selectedValue)) {
                $(`#ubah-biodata-${kategori}`).prop('disabled', false);

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

                        $(`#ubah-biodata-${hubungan}`).prop('disabled', $.isEmptyObject(option));
                    }
                }, 'json');
            }

            pendudukDesaElement.find('.data_penduduk_desa').show();
        }

        function ubahBiodataPenduduk(kategori, btn) {
            const $modal = $('#modal-ubah-biodata');
            const id = $(`select[name="${kategori}[nik]"]`).val();
            const $btn = $(btn);
            const originalHtml = $btn.html();

            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

            $.get(`{{ url('penduduk/form') }}/${id}`, function (html) {
                $modal.data('penduduk-id', id).data('kategori', kategori).find('#modal-biodata-body').html(html);
                $modal.modal('show');
            }).fail(function () {
                $modal.find('#modal-biodata-body').html('<div class="alert alert-danger">Gagal memuat biodata penduduk.</div>');
                $modal.modal('show');
            }).always(function () {
                $btn.prop('disabled', false).html(originalHtml);
            });
        }
    </script>
@endpush
