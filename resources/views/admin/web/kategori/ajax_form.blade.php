<form action="{{ $form_action }}" method="post" id="validasi">
    <div class="modal-body">
        <div class="form-group">
            <label class="control-label" for="nama">Nama Kategori</label>
            <input name="kategori" class="form-control input-sm required nomor_sk" maxlength="50" type="text" value="{{ $kategori->kategori ?? '' }}">
        </div>
    </div>
    <div class="modal-footer">
        {!! batal() !!}
        <button type="submit" class="btn btn-social btn-info btn-sm confirm"><i class="fa fa-check"></i> Simpan</button>
    </div>
</form>
@include('admin.layouts.components.form_modal_validasi')
