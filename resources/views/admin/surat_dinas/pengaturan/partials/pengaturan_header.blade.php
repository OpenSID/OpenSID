<div class="tab-pane active" id="header">
    <div class="box-body">
        <div class="form-group">
            <label>Tinggi Header Surat</label>
            <div class="input-group">
                <input
                    type="number"
                    name="tinggi_header_surat_dinas"
                    class="form-control input-sm required"
                    min="0"
                    max="100"
                    step="0.01"
                    value="{{ setting('tinggi_header_surat_dinas') }}"
                />
                <span class="input-group-addon input-sm">cm</span>
            </div>
        </div>
        <div class="form-group">
            <label>Template Header Surat</label>
            <textarea name="header_surat_dinas" class="form-control input-sm editor required" data-filemanager='{!! json_encode(['external_filemanager_path' => base_url('assets/kelola_file/'), 'filemanager_title' => 'Responsive Filemanager', 'filemanager_access_key' => $session->fm_key]) !!}' data-urlsalintemplate="surat_dinas" data-salintemplate="header-footer" data-jenis="header">{{ setting('header_surat_dinas') }}</textarea>
        </div>
    </div>
</div>
