<div class="tab-pane" id="profil">
    <div class="box-header with-border">
        @include('admin.layouts.components.tombol_kembali', ['url' => ci_route('identitas_desa'), 'label' => 'Data Identitas ' . ucwords(setting('sebutan_desa'))])
    </div>
    <div class="box-body">
        <h5 class="text-bold">EKOLOGI</h5>
        <div class="form-group">
            <label class="col-sm-3 control-label">Jenis Tanah</label>
            <div class="col-sm-8">
                <input type="text" name="jenis_tanah" class="form-control input-sm" value="{{ $profil_desa['jenis_tanah'] ?? '' }}" placeholder="Contoh: Sawah, Kering, Hutan, dll">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Topografi</label>
            <div class="col-sm-8">
                <input type="text" name="topografi" class="form-control input-sm" value="{{ $profil_desa['topografi'] ?? '' }}" placeholder="Contoh: Datar, Bergelombang, Perbukitan, Pegunungan, dll">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Sumber Daya Alam</label>
            <div class="col-sm-8">
                <textarea name="sumber_daya_alam" class="form-control input-sm" placeholder="Contoh: Air, Mineral, Energi, Hutan, dll" rows="3">{{ $profil_desa['sumber_daya_alam'] ?? '' }}</textarea>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Flora & Fauna</label>
            <div class="col-sm-8">
                <textarea name="flora_fauna" class="form-control input-sm" placeholder="Contoh: Jenis tumbuhan dan hewan yang ada di {{ setting('sebutan_desa') }}" rows="3">{{ $profil_desa['flora_fauna'] ?? '' }}</textarea>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Rawan Bencana</label>
            <div class="col-sm-8">
                <input type="text" name="rawan_bencana" class="form-control input-sm" value="{{ $profil_desa['rawan_bencana'] ?? '' }}" placeholder="Contoh: Banjir, Tanah Longsor, Gempa Bumi, dll">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Kearifan Lokal</label>
            <div class="col-sm-8">
                <textarea name="kearifan_lokal" class="form-control input-sm" placeholder="Contoh: Tradisi, Budaya, Pengetahuan Lokal, dll" rows="3">{{ $profil_desa['kearifan_lokal'] ?? '' }}</textarea>
            </div>
        </div>

        <hr>
        <h5 class="text-bold">JARINGAN INTERNET</h5>

        <div class="form-group">
            <label class="col-sm-3 control-label">Jenis Jaringan</label>
            <div class="col-sm-8">
                <input type="text" name="jenis_jaringan" class="form-control input-sm" value="{{ $profil_desa['jenis_jaringan'] ?? '' }}" placeholder="Contoh: Fiber Optik, 4G, 5G, Satelit, dll">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Provider Internet</label>
            <div class="col-sm-8">
                <input type="text" name="provider_internet" class="form-control input-sm" value="{{ $profil_desa['provider_internet'] ?? '' }}" placeholder="Contoh: Telkomsel, XL, Indosat, dll">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Cakupan Wilayah</label>
            <div class="col-sm-8">
                <textarea name="cakupan_wilayah" class="form-control input-sm" placeholder="Deskripsikan cakupan wilayah jaringan internet di {{ setting('sebutan_desa') }}" rows="3">{{ $profil_desa['cakupan_wilayah'] ?? '' }}</textarea>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Kecepatan Internet</label>
            <div class="col-sm-8">
                <input type="text" name="kecepatan_internet" class="form-control input-sm" value="{{ $profil_desa['kecepatan_internet'] ?? '' }}" placeholder="Contoh: 10 Mbps, 20 Mbps, dll">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Akses Publik</label>
            <div class="col-sm-8">
                <textarea name="akses_publik" class="form-control input-sm" placeholder="Deskripsikan akses publik terhadap jaringan internet di {{ setting('sebutan_desa') }}" rows="3">{{ $profil_desa['akses_publik'] ?? '' }}</textarea>
            </div>
        </div>

        <hr>
        <h5 class="text-bold">STATUS {{ strtoupper(setting('sebutan_desa')) }}</h5>

        <div class="form-group">
            <label class="col-sm-3 control-label">Status {{ ucfirst(setting('sebutan_desa')) }}</label>
            <div class="col-sm-8">
                <select name="status_desa" class="form-control input-sm">
                    <option value="Adat" @selected(($profil_desa['status_desa'] ?? '') == 'Adat')>Adat</option>
                    <option value="Bukan Adat" @selected(($profil_desa['status_desa'] ?? '') == 'Bukan Adat')>Bukan Adat</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Lembaga Adat</label>
            <div class="col-sm-8">
                <input type="text" name="lembaga_adat" class="form-control input-sm" value="{{ $profil_desa['lembaga_adat'] ?? '' }}" placeholder="Contoh: Majelis Adat, Dewan Adat, dll">
            </div>
        </div>
        @php
            $struktur_adat_path = LOKASI_DOKUMEN . ($profil_desa['struktur_adat'] ?? '');
            $struktur_adat_file = FCPATH . $struktur_adat_path;

            $dokumen_regulasi_path = LOKASI_DOKUMEN . ($profil_desa['dokumen_regulasi_penetapan_kampung_adat'] ?? '');
            $dokumen_regulasi_file = FCPATH . $dokumen_regulasi_path;
        @endphp
        <div class="form-group">
            <label class="col-sm-3 control-label">Struktur Adat</label>
            <div class="col-sm-8">
                @if (!empty($profil_desa['struktur_adat']) && file_exists($struktur_adat_file))
                    <img class="attachment-img img-responsive img-circle" src="{{ base_url($struktur_adat_path) }}" alt="Foto">
                    <br><br>
                @endif
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" id="file_path_struktur" name="struktur_adat">
                    <input id="file_struktur" type="file" class="hidden" name="struktur_adat" accept=".jpg,.jpeg,.png,.webp" />
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-info browse-file" data-target="file_struktur"><i class="fa fa-search"></i> Browse</button>
                    </span>
                </div>

                @if ($profil_desa['struktur_adat'] && file_exists($struktur_adat_file))
                <code>(Kosongkan jika tidak ingin mengubah dokumen)</code>
                @endif
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Wilayah Adat</label>
            <div class="col-sm-8">
                <textarea name="wilayah_adat" class="form-control input-sm" placeholder="Deskripsikan wilayah adat yang ada di {{ setting('sebutan_desa') }}" rows="3">{{ $profil_desa['wilayah_adat'] ?? '' }}</textarea>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Peraturan Adat</label>
            <div class="col-sm-8">
                <textarea name="peraturan_adat" class="form-control input-sm" placeholder="Deskripsikan peraturan adat yang ada di {{ setting('sebutan_desa') }}" rows="3">{{ $profil_desa['peraturan_adat'] ?? '' }}</textarea>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Regulasi Penetapan {{ ucwords(setting('sebutan_desa')) }} Adat</label>
            <div class="col-sm-8">
                <input type="text" name="regulasi_penetapan_kampung_adat" class="form-control input-sm" value="{{ $profil_desa['regulasi_penetapan_kampung_adat'] ?? '' }}" placeholder="Contoh: Nomor SK/Perda"><br>
                @if (!empty($profil_desa['dokumen_regulasi_penetapan_kampung_adat']) && file_exists($dokumen_regulasi_file))
                    <i class="fa fa-file-pdf-o pop-up-pdf" aria-hidden="true" style="font-size: 60px;"
                    data-title="Berkas {{ $profil_desa['dokumen_regulasi_penetapan_kampung_adat'] }}"
                    data-url="{{ base_url($dokumen_regulasi_path) }}"></i>
                    <br><br>
                @endif
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" id="file_path_dokumen" name="dokumen_regulasi_penetapan_kampung_adat">
                    <input id="file_dokumen" type="file" class="hidden" name="dokumen_regulasi_penetapan_kampung_adat" accept=".pdf" />
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-info browse-file" data-target="file_dokumen"><i class="fa fa-search"></i> Browse</button>
                    </span>
                </div>

                @if ($profil_desa['dokumen_regulasi_penetapan_kampung_adat'] && file_exists($dokumen_regulasi_file))
                <code>(Kosongkan jika tidak ingin mengubah dokumen)</code>
                @endif
            </div>
        </div>
    </div>
    <div class="box-footer">
        <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i>
            Batal</button>
        <button type="submit" class="btn btn-social btn-info btn-sm pull-right simpan"><i class="fa fa-check"></i>
            Simpan</button>
    </div>
</div>

@push('scripts')
<script>
    // Klik tombol browse
    $(document).on('click', '.browse-file', function () {
        const target = $(this).data('target');
        $('#' + target).trigger('click');
    });

    // Klik input text -> buka file picker
    $(document).on('click', 'input[id^="file_path_"]', function () {
        const fileInputId = this.id.replace('file_path_', 'file_');
        $('#' + fileInputId).trigger('click');
    });

    // Saat file dipilih -> tampilkan nama file di input text
    $(document).on('change', 'input[type="file"]', function () {
        const fileName = $(this).val().split('\\').pop();
        const textInputId = this.id.replace('file_', 'file_path_');
        $('#' + textInputId).val(fileName);
    });
</script>
@endpush
