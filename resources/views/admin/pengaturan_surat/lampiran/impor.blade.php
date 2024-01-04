<div class="modal fade in" id="impor-surat">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Impor Lampiran</h4>
            </div>
            {!! form_open(ci_route('lampiran.impor'), 'id="validasi" enctype="multipart/form-data"') !!}
            <div class="modal-body">
                <div class="form-group">
                    <label for="file" class="control-label">File Impor : </label>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" id="file_path" name="userfile" required>
                        <input type="file" class="hidden" id="file" name="userfile" accept="application/json">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i> Browse</button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-social btn-danger btn-sm pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
                <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class="fa fa-check"></i>
                    Simpan</button>
            </div>
            </form>
        </div>
    </div>
</div>
