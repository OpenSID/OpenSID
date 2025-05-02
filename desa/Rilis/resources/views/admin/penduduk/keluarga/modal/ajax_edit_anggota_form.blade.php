@include('admin.layouts.components.form_modal_validasi')
<form action="{{ $form_action }}" method="post" id="validasi">
    <div class='modal-body'>
        <table id="tabel3" class="table table-hover">
            <tr>
                <td style="padding-top : 10px;padding-bottom : 10px; width:30%;">NIK</td>
                <td class="padat"> : </td>
                <td>{{ $main->nik }}</td>
            </tr>
            <tr>
                <td>Nama Penduduk</td>
                <td> : </td>
                <td>{{ $main->nama }}</td>
            </tr>
            <tr>
                <td>Hubungan Keluarga</td>
                <td> : </td>
                <td>
                    <select name="kk_level" class="form-control input-sm select2 required" style="width:100%;">
                        <option value=""> ----- Pilih Hubungan Keluarga ----- </option>
                        @foreach ($hubungan as $key => $value)
                            <option value="{{ $key }}" @selected($key == $main->kk_level)>{{ $value }}</option>
                        @endforeach
                    </select>
                </td>
        </table>
    </div>
    <div class="modal-footer">
        <button type="reset" class="btn btn-social btn-danger btn-sm pull-left" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
        <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
    </div>
</form>
