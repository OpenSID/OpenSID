<form id="validasi" action="{{ $form_action }}" method="POST" enctype="multipart/form-data">
    <div class="modal-body">
        <div class="row">
            <div class="form-group">
                <label class="col-sm-4 col-lg-4 control-label">Tanggal Surat</label>
                <div class="col-sm-6 col-lg-6">
                    <div class="input-group input-group-sm date" style="margin-bottom: 10px;">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input class="form-control input-sm pull-right tgl_sekarang" name="tanggal_surat" type="text" value="{{ date('d-m-Y') }}" readonly>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 col-lg-4 control-label">Tujuan</label>
                <div class="col-sm-6 col-lg-6">
                    <input id="tujuan" name="tujuan" class="form-control input-sm" style="margin-bottom: 10px;" type="text" placeholder="Tujuan">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 col-lg-4 control-label">Isi Singkat/Perihal</label>
                <div class="col-sm-6 col-lg-6">
                    <textarea id="isi_singkat" name="isi_singkat" class="form-control input-sm" placeholder="Isi Singkat/Perihal" rows="3" style="resize:none;"></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        {!! batal() !!}
        <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
    </div>
</form>

@include('admin.layouts.components.form_modal_validasi')
