<form id="validasi" action="{{ $form_action }}" method="POST" enctype="multipart/form-data">
    <div class='modal-body'>
        <div class="form-group">
            <label class="control-label">Nama</label>
            <input name="nama" class="form-control input-sm nomor_sk required" maxlength="100" type="text" value="{{ $polygon['nama'] }}"></input>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Warna</label>
                    <div class="input-group my-colorpicker2">
                        <input type="text" id="color" name="color" class="form-control input-sm color required" placeholder="#FFFFFF" value="{{ $polygon['color'] }}">
                        <div class="input-group-addon input-sm">
                            <i></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        {!! batal() !!}
        <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
    </div>
</form>
<script>
    $('.my-colorpicker2').colorpicker();
</script>
@include('admin.layouts.components.form_modal_validasi')
