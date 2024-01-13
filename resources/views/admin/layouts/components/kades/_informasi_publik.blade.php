<div class="form-group">
    <label class="control-label col-sm-4" for="nama">Kategori Informasi Publik</label>
    <div class="col-sm-6">
        <select name="kategori_info_publik" class="form-control select2 input-sm required">
            <option value="">Pilih Kategori Informasi Publik</option>
            @foreach ($list_kategori_publik as $key => $value)
                <option value="{{ $key }}" @selected($dokumen['kategori_info_publik'], $key)>
                    {{ $value }}
                </option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-sm-4" for="nama">Tahun</label>
    <div class="col-sm-6">
        <input name="tahun" maxlength="4" class="form-control input-sm number required" type="text" placeholder="Contoh: 2019" value="{{ $dokumen['tahun'] }}"></input>
    </div>
</div>
