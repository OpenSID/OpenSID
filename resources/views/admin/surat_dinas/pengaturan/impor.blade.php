<div class="modal fade in" id="impor-surat">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Impor Surat</h4>
            </div>
            {!! form_open(ci_route('surat_dinas.impor'), 'id="validasi" enctype="multipart/form-data"') !!}
            <div class="modal-body">
                <div class="form-group">
                    <label for="file" class="control-label">File Impor : </label>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" id="file_path" name="userfile" required>
                        <input type="file" class="hidden" id="file" name="userfile" accept="application/json">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-info" id="file_browser"><i class="fa fa-search"></i> Browse</button>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <a href="{{ $suratDinasBawaan }}" class="btn btn-social bg-navy btn-sm" target="_blank">
                        <i class="fa fa-download"></i> Contoh Surat Dinas Sistem </a>
                </div>
            </div>
            <div class="modal-footer">
                {!! batal() !!}
                <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class="fa fa-check"></i>
                    Simpan</button>
            </div>
            </form>
        </div>
    </div>
</div>
