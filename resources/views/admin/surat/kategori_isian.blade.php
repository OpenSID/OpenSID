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
        <label class="col-sm-3 control-label" for="status">{{ str_replace('_', ' ', strtoupper($key)) }}</label>
        <input name="anchor" type="hidden" value="<?= $anchor ?>" />
        <div class="btn-group col-sm-8" data-toggle="buttons">
            {{-- <label class="btn btn-info btn-flat btn-sm col-sm-4 col-sm-4 col-md-4 col-lg-3 form-check-label active">
            <input id="saksi2_1" type="radio" name="saksi2" class="form-check-input" type="radio" value="1" checked
                autocomplete="off" onchange="ubah_saksi2(this.value);"> Warga Desa
        </label> --}}
            {{-- <label id="label_saksi2_2"
            class="btn btn-info btn-flat btn-sm col-sm-4 col-md-4 col-lg-3 form-check-label">
            <input id="saksi2_2" disabled type="radio" name="saksi2" class="form-check-input" type="radio" value="2"
                autocomplete="off" onchange="ubah_saksi2(this.value);"> Warga Luar Desa
        </label> --}}
        </div>
    </div>
    {{-- <div class="form-group saksi2_desa">
        <label class="col-xs-12 col-sm-3 col-lg-3 control-label bg-maroon"
            style="margin-top:-10px;padding-top:10px;padding-bottom:10px"><strong>DATA
                {{ strtoupper($key) }}</strong></label>
    </div> --}}
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

    <?php $keyname = $key; ?>
    {{-- kode isia kategori --}}
    <?php foreach ($kategori['kode_isian'] as $item): ?>
    <?php $nama = underscore($item->nama, true, true); ?>
    <?php $class = buat_class($item->atribut, '', $item->required) ?>
    <div class="form-group">
        <label for="<?= $item->nama ?>" class="col-sm-3 control-label">
            <?= $item->nama ?>
        </label>
        <?php if ($item->tipe == 'select-manual'): ?>
        <div class="col-sm-4">
            <select name="<?= $nama ?>_{{ $keyname }}"
                <?= $class ?>
                placeholder="<?= $item->deskripsi ?>">
                <option value="">--<?= $item->deskripsi ?>--</option>
                <?php foreach ($item->pilihan as $key => $pilih): ?>
                    <option @selected(set_value("{$nama}_{$keyname}") == $pilih) value="<?= $pilih ?>"><?= $pilih ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <?php elseif ($item->tipe == 'select-otomatis'): ?>
        <div class="col-sm-4">
            <select name="<?= $nama ?>_{{ $keyname }}"
                <?= $class ?>
                placeholder="<?= $item->deskripsi ?>">
                <option value="">--<?= $item->deskripsi ?> --</option>
                <?php foreach (ref($item->refrensi) as $key => $pilih): ?>
                <option @selected(set_value("{$nama}_{$keyname}") == $pilih->nama) value="<?= $pilih->nama ?>">
                    <?= $pilih->nama ?>
                </option>
                <?php endforeach ?>
            </select>
        </div>
        <?php elseif ($item->tipe == 'textarea'): ?>
        <div class="col-sm-8">
            <textarea name="<?= $nama ?>_{{ $keyname }}" <?= $class ?> placeholder="<?= $item->deskripsi ?>"><?= set_value("{$nama}_{$keyname}") ?></textarea>
        </div>
        <?php elseif ($item->tipe == 'date'): ?>
        <div class="col-sm-3 col-lg-2">
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input type="text"
                <?= buat_class($item->atribut, 'form-control input-sm tgl', $item->required) ?>
                    name="<?= $nama ?>_{{ $keyname }}" placeholder="<?= $item->deskripsi ?>" value="<?= set_value("{$nama}_{$keyname}") ?>" />
            </div>
        </div>
        <?php elseif ($item->tipe == 'time'): ?>
        <div class="col-sm-3 col-lg-2">
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                </div>
                <input type="text"
                <?= buat_class($item->atribut, 'form-control input-sm jam', $item->required) ?>
                    name="<?= $nama ?>_{{ $keyname }}" placeholder="<?= $item->deskripsi ?>" value="<?= set_value("{$nama}_{$keyname}") ?>" />
            </div>
        </div>
        <?php elseif ($item->tipe == 'datetime'): ?>
        <div class="col-sm-3 col-lg-2">
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input type="text"
                <?= buat_class($item->atribut, 'form-control input-sm tgl_jam', $item->required) ?>
                    name="<?= $nama ?>_{{ $keyname }}" placeholder="<?= $item->deskripsi ?>" value="<?= set_value("{$nama}_{$keyname}") ?>" />
            </div>
        </div>
        <?php else: ?>
        <div class="col-sm-8">
            <input type="<?= $item->tipe ?>"
                <?= $class ?>
                name="<?= $nama ?>_{{ $keyname }}" placeholder="<?= $item->deskripsi ?>" value="<?= set_value("{$nama}_{$keyname}") ?>" />
        </div>
        <?php endif ?>
    </div>
    <?php endforeach ?>
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
