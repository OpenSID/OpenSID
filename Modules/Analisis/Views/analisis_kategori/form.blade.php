@include('admin.layouts.components.validasi_form')
<form id="validasi" action="{{ $form_action }}" method="post">
    <div class="modal-body">
        <div class="form-group">
            <label class="control-label" for="kategori">Nama Kategori / Variabel </label>
            <input
                id="kategori"
                class="form-control input-sm required nomor_sk"
                maxlength="50"
                type="text"
                placeholder="Kategori Indikator"
                name="kategori"
                value="{{ $analisis_kategori->kategori }}"
            >
        </div>
    </div>
    <div class="modal-footer">
        <button type="reset" class="btn btn-social btn-danger btn-sm pull-left"><i class='fa fa-times'></i> Batal</button>
        <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
    </div>
</form>
