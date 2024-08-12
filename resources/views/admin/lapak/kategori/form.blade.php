@include('admin.layouts.components.validasi_form')

<form id="validasi" action="{{ $form_action }}" method="POST">
    <div class="modal-body">
        <div class="form-group">
            <label class="control-label" for="kategori">Kategori Produk</label>
            <input class="form-control input-sm required nama_produk" type="text" name="kategori" placeholder="Kategori Produk" value="{{ $main->kategori }}" />
        </div>
    </div>
    <div class="modal-footer">
        <button type="reset" class="btn btn-social  btn-danger btn-sm pull-left"><i class="fa fa-times"></i> Batal</button>
        <button type="submit" class="btn btn-social  btn-info btn-sm"><i class="fa fa-check"></i> Simpan</button>
    </div>
</form>
