<div class="tab-pane" id="profil">
    <div class="box-header with-border">
        @include('admin.layouts.components.tombol_kembali', ['url' => ci_route('identitas_desa'), 'label' => 'Data Identitas ' . ucwords(setting('sebutan_desa'))])
    </div>
    <div class="box-body">
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
                <textarea name="flora_fauna" class="form-control input-sm" placeholder="Contoh: Jenis tumbuhan dan hewan yang ada di desa" rows="3">{{ $profil_desa['flora_fauna'] ?? '' }}</textarea>
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
        <h4 class="text-bold">Jaringan Internet Kampung</h4>

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
                <textarea name="cakupan_wilayah" class="form-control input-sm" placeholder="Deskripsikan cakupan wilayah jaringan internet di desa" rows="3">{{ $profil_desa['cakupan_wilayah'] ?? '' }}</textarea>
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
                <textarea name="akses_publik" class="form-control input-sm" placeholder="Deskripsikan akses publik terhadap jaringan internet di desa" rows="3">{{ $profil_desa['akses_publik'] ?? '' }}</textarea>
            </div>
        </div>

        <hr>
        <h4 class="text-bold">Desa Adat</h4>

        <div class="form-group">
            <label class="col-sm-3 control-label">Status Desa</label>
            <div class="col-sm-8">
                <select name="status_desa" class="form-control input-sm">
                    <option value="adat" {{ ($profil_desa['status_desa'] ?? '') == 'adat' ? 'selected' : '' }}>Adat</option>
                    <option value="non_adat" {{ ($profil_desa['status_desa'] ?? '') == 'non_adat' ? 'selected' : '' }}>Non-Adat</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Lembaga Adat</label>
            <div class="col-sm-8">
                <input type="text" name="lembaga_adat" class="form-control input-sm" value="{{ $profil_desa['lembaga_adat'] ?? '' }}" placeholder="Contoh: Majelis Adat, Dewan Adat, dll">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Struktur Adat</label>
            <div class="col-sm-8">
                <textarea name="struktur_adat" class="form-control input-sm" placeholder="Deskripsikan struktur organisasi adat di desa" rows="3">{{ $profil_desa['struktur_adat'] ?? '' }}</textarea>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Wilayah Adat</label>
            <div class="col-sm-8">
                <textarea name="wilayah_adat" class="form-control input-sm" placeholder="Deskripsikan wilayah adat yang ada di desa" rows="3">{{ $profil_desa['wilayah_adat'] ?? '' }}</textarea>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Kegiatan Adat</label>
            <div class="col-sm-8">
                <textarea name="kegiatan_adat" class="form-control input-sm" placeholder="Deskripsikan kegiatan adat yang rutin dilaksanakan di desa" rows="3">{{ $profil_desa['kegiatan_adat'] ?? '' }}</textarea>
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