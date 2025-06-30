@include('admin.layouts.components.validasi_form')
<form id="validasi" action="{{ $form_action }}" method="post">
    <div class="modal-body">
        <div class="form-group">
            <label class="control-label" for="nama">Klasifikasi</label>
            <input
                id="nama"
                class="form-control input-sm required nomor_sk"
                maxlength="36"
                type="text"
                placeholder="Klasifikasi"
                name="nama"
                value="{{ $analisis_klasifikasi['nama'] }}"
            >
        </div>
        <div class="form-group">
            <label class="control-label" for="minval">Nilai Minimal</label>
            <input id="minval" class="form-control input-sm required number" type="text" placeholder="Nilai Minimal" name="minval" value="{{ $analisis_klasifikasi['minval'] }}">
            <p class="small text-maroon"> Gunakan tanda titik (.) untuk bilangan pencahan.</p>
        </div>
        <div class="form-group">
            <label class="control-label" for="minval">Nilai Maksimal</label>
            <input id="maxval" class="form-control input-sm required number" type="text" placeholder="Nilai Maksimal" name="maxval" value="{{ $analisis_klasifikasi['maxval'] }}">
            <p class="small text-maroon"> Gunakan tanda titik (.) untuk bilangan pencahan.</p>
        </div>
    </div>
    <div class="modal-footer">
        <button type="reset" class="btn btn-social btn-danger btn-sm pull-left"><i class='fa fa-times'></i> Batal</button>
        <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
    </div>
</form>
