@foreach ($form_kategori as $key => $kategori)
    <div class="form-group subtitle_head" id="a_saksi2">
        <label class="col-sm-3 control-label" for="status">{{ str_replace('_', ' ', strtoupper($judul_kategori[$key] ?? $key)) }}</label>
        @includeWhen((count($surat->form_isian->{$key}->data) > 1 && ($surat->form_isian->{$key}->sumber ?? 1) == 1) , 'admin.surat.opsi_sumber_penduduk' ,['opsiSumberPenduduk' => $surat->form_isian->{$key}->data, 'kategori' => $key])
        <input name="anchor" type="hidden" value="<?= $anchor ?>" />        
    </div>
    @includeWhen((in_array(1, $surat->form_isian->{$key}->data) && ($surat->form_isian->{$key}->sumber ?? 1) == 1), 'admin.surat.penduduk_desa', ['opsiSumberPenduduk' => $surat->form_isian->{$key}->data, 'kategori' => $key])
    @includeWhen((in_array(2, $surat->form_isian->{$key}->data) && ($surat->form_isian->{$key}->sumber ?? 1) == 1), 'admin.surat.penduduk_luar_desa', ['opsiSumberPenduduk' => $surat->form_isian->{$key}->data, 'kategori' => $key])    
    {{-- <div class="form-group saksi2_desa">
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
    </div> --}}

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
        function submit_form_ambil_data() {
            $('input').removeClass('required');
            $('select').removeClass('required');
            $('#' + 'validasi').attr('action', '');
            $('#' + 'validasi').attr('target', '');
            $('#' + 'validasi').submit();
        }
    </script>
@endpush
