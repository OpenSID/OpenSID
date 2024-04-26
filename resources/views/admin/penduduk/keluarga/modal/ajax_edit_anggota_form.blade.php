@include('admin.layouts.components.form_modal_validasi')
<form action="{{ $form_action }}" method="post" id="validasi">
    <div class='modal-body'>
        <div class="row">
            <div class="col-sm-12">
                <table id="tabel3" class="table table-hover">
                    <tr>
                        <td style="padding-top : 10px;padding-bottom : 10px; width:40%;">NIK</td>
                        <td> : {{ $main->nik }}</td>
                    </tr>
                    <tr>
                        <td style="padding-top : 10px;padding-bottom : 10px; width:40%;">Nama Penduduk</td>
                        <td> : {{ $main->nama }}</td>
                    </tr>
                </table>
                <div class="box box-danger">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="kk_level">Hubungan Keluarga</label>
                            <select name="kk_level" class="form-control input-sm select2 required" style="width:100%;">
                                <option value=""> ----- Pilih Hubungan Keluarga ----- </option>
                                @foreach ($hubungan as $key => $value)
                                    <option value="{{ $key }}" @selected($key == $main->kk_level)>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="reset" class="btn btn-social btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
        <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
    </div>
</form>
