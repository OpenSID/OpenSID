<form id="validasi" action="{{ $form_action }}" method="POST" enctype="multipart/form-data">
    <div class="modal-body">
        <div class="form-group">
            <label for="judul">Judul</label>
            <input
                type="text"
                class="form-control input-sm required"
                id="judul"
                name="judul"
                value="{{ $main->judul }}"
                placeholder="Judul"
                @disabled($main->kirim)
            />
        </div>

        <div class="form-group">
            <label for="tahun">Tahun</label>
            <input
                type="number"
                class="form-control input-sm required"
                id="tahun"
                name="tahun"
                value="{{ $main->tahun }}"
                placeholder="Tahun"
                @disabled($main->kirim)
                min="1945"
                max="2030"
            />
        </div>

        <div class="form-group">
            <label for="bulan">Bulan</label>
            <select class="form-control input-sm select2 required" id="semester" name="semester" @disabled($main->kirim)>
                @foreach (bulan() as $key => $nama_bulan)
                    <option value="{{ $key }}" @selected($main->semester == $key)>{{ $nama_bulan }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="file">File : <code>(.pdf)</code></label>
            <div class="input-group input-group-sm">
                <input type="text" class="form-control" id="file_path" name="satuan">
                <input type="file" class="hidden @if (!$main) {{ 'required' }} @endif" id="file" name="nama_file" accept=".pdf" />
                <span class="input-group-btn">
                    <button type="button" class="btn btn-info" id="file_browser"><i class="fa fa-search"></i> Browse</button>
                </span>
            </div>
            <span class="help-block"><code>Kosongkan jika tidak ingin mengubah dokumen. Ukuran maksimal <strong>{{ max_upload() }} MB</strong>.</code></span>
        </div>
    </div>

    <div class="modal-footer">
        {!! batal() !!}
        <button type="submit" class="btn btn-social btn-info btn-sm" id="aksi"><i class="fa fa-check"></i> Simpan</button>
    </div>
</form>
@include('admin.layouts.components.validasi_form')
