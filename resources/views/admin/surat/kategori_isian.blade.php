@push('css')
    <style>
        .form-horizontal .form-group {
            margin-right: -10px;
            margin-left: -10px;
        }

        .subtitle_head {
            margin-left: -10px;
            margin-right: -10px;
            /* background-color: #d81b60 !important; */
        }

        .subtitle_head label {
            padding-left: 10px;
            padding-right: 10px;
            padding-top: 5px;
            padding-bottom: 5px;
            margin-bottom: 0px;
            /* color: #ffffff !important; */
        }
    </style>
@endpush
@foreach ($form_kategori as $key => $kategori)
    <div class="form-group subtitle_head" id="a_saksi2">
        <label class="col-sm-3 control-label" for="status">{{ str_replace('_', ' ', strtoupper($judul_kategori[$key] ?? $key)) }}</label>
        <input name="anchor" type="hidden" value="<?= $anchor ?>" />        
    </div>    
    <div class="form-group saksi2_desa">
        <label for="saksi2_desa" class="col-sm-3 control-label"><strong>NIK / Nama</strong></label>
        <div class="col-sm-5">
            <select class="form-control select2 input-sm select2-nik-ajax required" name="id_pend_{{ $key }}"
                style="width:100%;" data-surat="{{ $surat->id }}" data-kategori="{{ $key }}" data-url="<?= site_url('surat/list_penduduk_ajax') ?>"
                onchange="submit_form_ambil_data(this.id);">
                <?php if ($kategori["saksi_{$key}"]) : ?>
                <option value="<?= $kategori["saksi_{$key}"]['id'] ?>"
                    selected><?= $kategori["saksi_{$key}"]['nik'] . ' - ' . $kategori["saksi_{$key}"]['nama'] ?>
                </option>
                <?php endif; ?>
            </select>
        </div>
    </div>

    @if ($kategori["saksi_{$key}"])
        @php
            $individu = $kategori["saksi_{$key}"];
            $list_dokumen = $kategori["list_dokumen_{$key}"];
        @endphp

        @include('admin.surat.konfirmasi_pemohon')
    @endif
    
    {{-- kode isia kategori --}}
    @foreach ($kategori['kode_isian'] as $label => $item)
        @include('admin.surat.baris_kode_isian', ['groupLabel' => $item, 'keyname' => $key, 'label' => $label])
    @endforeach
@endforeach


@push('scripts')
    <script>
        $('.select2-nik-ajax').select2({
            ajax: {
                url: function() {
                    return $(this).data('url');
                },
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term || '', // search term
                        page: params.page || 1,
                        filter_sex: $(this).data('filter-sex'),
                        surat: $(this).data('surat'),
                        kategori: $(this).data('kategori'),
                    };
                },
                processResults: function(data, params) {
                    // parse the results into the format expected by Select2
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data, except to indicate that infinite
                    // scrolling can be used
                    // params.page = params.page || 1;

                    return {
                        results: data.results,
                        pagination: data.pagination
                    };
                },
                cache: true
            },
            templateResult: function(penduduk) {
                if (!penduduk.id) {
                    return penduduk.text;
                }
                var _tmpPenduduk = penduduk.text.split('\n');
                var $penduduk = $(
                    '<div>' + _tmpPenduduk[0] + '</div><div>' + _tmpPenduduk[1] + '</div>'
                );
                return $penduduk;
            },
            placeholder: '--  Cari NIK / Tag ID Card / Nama Penduduk --',
            minimumInputLength: 0,
        });

        function submit_form_ambil_data() {
            $('input').removeClass('required');
            $('select').removeClass('required');
            $('#' + 'validasi').attr('action', '');
            $('#' + 'validasi').attr('target', '');
            $('#' + 'validasi').submit();
        }
    </script>
@endpush
