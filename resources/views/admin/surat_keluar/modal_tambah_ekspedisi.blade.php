@include('admin.layouts.components.form_modal_validasi')
<form action="{{ $form_action }}" method="post" id="validasi" enctype="multipart/form-data">
    <div class="modal-body">
        <div class="form-group">
            <label for="tanggal_pengiriman">Tanggal Pengiriman</label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input id="tanggal_pengiriman" name="tanggal_pengiriman" class="form-control input-sm required" type="text" value="{{ $tanggal_pengiriman }}" />
            </div>
        </div>
        <div class="form-group">
            <label for="berkas_scan-{{ $row->id }}">Berkas Scan Tanda Terima</label>
            <div class="input-group input-group-sm col-sm-12">
                <input type="text" class="form-control" id="file_path-{{ $row->id }}" readonly>
                <input type="file" class="hidden" id="file-{{ $row->id }}" name="satuan" accept=".gif,.jpg,.jpeg,.png,.pdf" onchange="document.getElementById('file_path-{{ $row->id }}').value = this.value.split('\\').pop()">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-info btn-flat" onclick="document.getElementById('file-{{ $row->id }}').click()"><i class="fa fa-search"></i> Browse</button>
                </span>
            </div>
            <span class="help-block"><code>(Kosongkan jika tidak ingin mengubah berkas)</code></span>
        </div>
        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <textarea name="keterangan" class="form-control input-sm" placeholder="Isi Singkat/Keterangan" rows="3" style="resize:none;"></textarea>
        </div>
    </div>
    <div class="modal-footer">
        {!! batal() !!}
        <button type="submit" class="btn btn-social btn-info btn-sm"><i class="fa fa-check"></i>Simpan</button>
    </div>
</form>
<script>
    $('#tanggal_pengiriman').datetimepicker({
        format: 'DD-MM-YYYY',
        locale: 'id'
    });
</script>