@foreach ($form_kategori as $key => $kategori)
    <!-- jika bukan array maka jadikan array dulu, karena data lama bukan bentuk array -->
    @php
        $sumberDataPenduduk = !is_array($surat->form_isian->{$key}->data) ? [$surat->form_isian->individu->data] : $surat->form_isian->{$key}->data;
    @endphp
    <div id="kategori-{{ $key }}">
        @if ($judul_kategori[$key] != '-')
            <div class="form-group subtitle_head">
                <label class="col-sm-3 control-label" for="status">{{ str_replace('_', ' ', strtoupper($judul_kategori[$key] ?? $key)) }}</label>
                @includeWhen(count($sumberDataPenduduk) > 1 && ($surat->form_isian->{$key}->sumber ?? 1) == 1, 'admin.surat.opsi_sumber_penduduk', ['opsiSumberPenduduk' => $surat->form_isian->{$key}->data, 'kategori' => $key, 'pendudukLuar' => $pendudukLuar])
                <input name="anchor" type="hidden" value="<?= $anchor ?>" />
            </div>
        @endif
        @if ($surat->form_isian->{$key}->info)
            <div class="callout callout-warning">
                <b>{{ $surat->form_isian->{$key}->info }}</b>
            </div>
        @endif
        @includeWhen(in_array(1, $sumberDataPenduduk) && ($surat->form_isian->{$key}->sumber ?? 1) == 1, 'admin.surat.penduduk_desa', ['opsiSumberPenduduk' => $surat->form_isian->{$key}->data, 'kategori' => $key])
        @foreach ($pendudukLuar as $index => $penduduk)
            @includeWhen(in_array($index, $sumberDataPenduduk), 'admin.surat.penduduk_luar_desa', ['index' => $index, 'opsiSumberPenduduk' => $surat->form_isian->{$key}->data, 'kategori' => $key, 'input' => explode(',', $penduduk['input'])])
        @endforeach

        @if ($kategori["saksi_{$key}"])
            @php
                $individu = $kategori["saksi_{$key}"];
                $list_dokumen = $kategori["list_dokumen_{$key}"];
            @endphp

            @include('admin.surat.konfirmasi_pemohon')
        @endif

        {{-- kode isian kategori --}}
        @foreach ($kategori['kode_isian'] as $label => $item)
            @include('admin.surat.baris_kode_isian', ['groupLabel' => $item, 'keyname' => $key, 'label' => $label])
        @endforeach
        @if ($surat->form_isian->$key->sebagai == 2)
            <input type="hidden" name="sebagai" value="{{ $key }}">
        @endif
    </div>
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
