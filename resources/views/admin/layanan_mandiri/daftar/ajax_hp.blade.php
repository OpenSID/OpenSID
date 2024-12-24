{!! form_open($form_action, 'id="validasi"') !!}
<div class="modal-body">
    <table class="table table-hover">
        <tr>
            <th width="20%">NIK</td>
            <td width="1%"> : </td>
            <td>{{ $penduduk['nik'] }}</td>
        </tr>
        <tr>
            <th>Nama Warga</td>
            <td> : </td>
            <td>{{ $penduduk['nama'] }}</td>
        </tr>
    </table>
    <div class="form-group">
        <label class="control-label" for="telepon">Nomor Telepon</label>
        <input
            name="telepon"
            class="form-control input-sm digits"
            minlength="8"
            maxlength="16"
            type="text"
            placeholder="No. HP Warga"
            value="{{ $penduduk['telepon'] }}"
        />
    </div>
</div>
<div class="modal-footer">
    <button type="reset" class="btn btn-social btn-danger btn-sm pull-left"><i class='fa fa-times'></i> Batal</button>
    <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
</div>
</form>
@include('admin.layouts.components.form_modal_validasi')
