@include('admin.layouts.components.validasi_form')
<form id="validasi" action="{{ $form_action }}" method="post">
    <div class="modal-body">
        <div class="form-group">
            <label class="control-label" for="pesan">Pesan Singkat Permohonan Surat {{ $judul }}</label>
            <textarea name="pesan" class="form-control input-sm required" placeholder="Pesan singkat alasan permohonan surat dibatalkan" rows="5"></textarea>
        </div>
    </div>
    <div class="modal-footer">
        <button type="reset" class="btn btn-social btn-danger btn-sm pull-left"><i class="fa fa-times"></i> Batal</button>
        <button type="submit" class="btn btn-social btn-info btn-sm"><i class="fa fa-send"></i> Kirim</button>
    </div>
</form>
