<div class="tab-pane" id="footer">
    <div class="box-body">
        <div class="form-group">
            <label>Tinggi Footer Surat</label>
            <div class="input-group">
                <input
                    type="number"
                    name="tinggi_footer_surat_dinas"
                    class="form-control input-sm required"
                    min="0"
                    max="100"
                    step="0.01"
                    value="{{ setting('tinggi_footer_surat_dinas') }}"
                />
                <span class="input-group-addon input-sm">cm</span>
            </div>
        </div>
        <div class="form-group">
            <label>Template Footer Surat</label>
            <textarea name="{{ setting('tte') == '1' ? 'footer_surat_dinas_tte' : 'footer_surat_dinas' }}" class="form-control input-sm editor required" data-filemanager='{!! json_encode(['external_filemanager_path' => base_url('assets/kelola_file/'), 'filemanager_title' => 'Responsive Filemanager', 'filemanager_access_key' => $session->fm_key]) !!}' data-urlsalintemplate="surat_dinas" data-salintemplate="header-footer" data-jenis="footer">{{ setting('tte') == '1' ? setting('footer_surat_dinas_tte') : setting('footer_surat_dinas') }}</textarea>
        </div>
    </div>
</div>
