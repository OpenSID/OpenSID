@foreach ($form_kategori as $key => $kategori)
    <!-- jika bukan array maka jadikan array dulu, karena data lama bukan bentuk array -->
    @if (!$kategori['kode_isian']->isEmpty())
        <div id="kategori-{{ $key }}">
            @if ($judul_kategori[$key] != '-')
                <div class="form-group subtitle_head">
                    <label class="col-sm-3 control-label" for="status">{{ str_replace('_', ' ', strtoupper($judul_kategori[$key] ?? $key)) }}</label>
                    <input name="anchor" type="hidden" value="<?= $anchor ?>" />
                </div>
            @endif
            @if ($surat->form_isian->{$key}->info)
                <div class="callout callout-warning">
                    <b>{{ $surat->form_isian->{$key}->info }}</b>
                </div>
            @endif

            @if ($kategori["saksi_{$key}"])
                @php
                    $individu = $kategori["saksi_{$key}"];
                    $list_dokumen = $kategori["list_dokumen_{$key}"];
                @endphp

                @include('admin.surat_dinas.cetak.konfirmasi_pemohon')
            @endif

            {{-- kode isian kategori --}}
            @foreach ($kategori['kode_isian'] as $label => $item)
                @include('admin.surat_dinas.cetak.baris_kode_isian', ['groupLabel' => $item, 'keyname' => $key, 'label' => $label])
            @endforeach
            @if ($surat->form_isian->$key->sebagai == 2)
                <input type="hidden" name="sebagai" value="{{ $key }}">
            @endif
        </div>
    @endif
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
