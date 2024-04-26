<form id="validasi" action="{{ $form_action }}" method="POST" enctype="multipart/form-data">
    <div class="modal-body">
        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <textarea class="form-control input-sm required" placeholder="Keterangan" name="keterangan" rows="5"><?= $main->keterangan ?></textarea>
        </div>
    </div>
    <div class="modal-footer">
        {!! batal() !!}
        <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
    </div>
</form>

@include('admin.layouts.components.form_modal_validasi')
