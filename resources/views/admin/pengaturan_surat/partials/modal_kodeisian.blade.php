<div class="modal fade in" id="form-kodeisian">
    <div class="modal-dialog">
        <form>
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Kode Isian Alias</h4>
                </div>
                <div class="modal-body">
                    <div class="kode-isian-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="judul">Judul</label>
                                    <input type="text" class="form-control judul_kode_isian" minlength="3" maxlength="10" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="alias">Alias</label>
                                    <input type="text" class="form-control alias_kode_isian kode_isian" minlength="5" maxlength="12" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="isi">Isi</label>
                            <textarea id="editor-kodeisian" class="form-control value_isian_editor"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-social btn-danger btn-sm pull-left"><i class="fa fa-times"></i> Batal</button>
                    <button type="button" class="btn btn-sm btn-success" id="btn-tambah-alias"> <i class="fa fa-check"></i>Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
