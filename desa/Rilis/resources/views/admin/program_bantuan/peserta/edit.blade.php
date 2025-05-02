<script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/validasi.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/localization/messages_id.js') }}"></script>
<script>
    //File Upload
    $('#file_browser').click(function(e) {
        e.preventDefault();
        $('#file').click();
    });

    $('#file').change(function() {
        $('#file_path').val($(this).val());
    });

    $('#file_path').click(function() {
        $('#file_browser').click();
    });
    //Fortmat Tanggal
    $('#tgl_1').datetimepicker({
        format: 'DD-MM-YYYY'
    });
</script>
<form id="validasi" action="{{ $form_action }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
    <div class='modal-body'>
        <div class="box-header with-border">
            <h3 class="box-title">Rincian Program</h3>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover tabel-rincian">
                    <tbody>
                        <tr>
                            <td width="20%">{{ $judul_peserta }}</td>
                            <td width="1">:</td>
                            <td>{{ $peserta }}</td>
                        </tr>
                        <tr>
                            <td>{{ $judul_peserta_info }}</td>
                            <td>:</td>
                            <td>{{ $peserta_info }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="box-header with-border">
            <h3 class="box-title">Identitas Pada Kartu Peserta</h3>
        </div>
        <div class="box-body">
            <input type="hidden" name="program_id" value="{{ $program_id }}" />
            <div class="form-group">
                <label for="no_id_kartu" class="col-sm-4 control-label">Nomor Kartu Peserta</label>
                <div class="col-sm-7">
                    <input id="no_id_kartu" class="form-control input-sm nama_terbatas" type="text" placeholder="Nomor Kartu Peserta" name="no_id_kartu" value="{{ $no_id_kartu }}">
                </div>
            </div>
            @if ($kartu_peserta)
                <div class="form-group">
                    <label class="control-label col-sm-4" for="nama"></label>
                    <div class="col-sm-6">
                        <img class="attachment-img img-responsive img-circle" src="{{ to_base64(LOKASI_DOKUMEN . $kartu_peserta) }}" alt="Kartu Peserta">
                        <p><label class="control-label"><input type="checkbox" name="gambar_hapus" value="1" /> Hapus Gambar</label></p>
                    </div>
                </div>
            @endif
            <div class="form-group">
                <label for="gambar_peserta" class="col-sm-4 control-label">Gambar Kartu Peserta</label>
                <div class="col-sm-7">
                    <div class="input-group input-group-sm ">
                        <input type="text" class="form-control" id="file_path">
                        <input type="file" class="hidden" id="file" name="file" accept=".jpg,.jpeg,.png">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-info" id="file_browser"><i class="fa fa-search"></i> Browse</button>
                        </span>
                    </div>
                    <span class="help-block"><code> Kosongkan jika tidak ingin mengunggah gambar</code></span>
                </div>
            </div>
            <div class="form-group">
                <label for="kartu_nik" class="col-sm-4 control-label">NIK</label>
                <div class="col-sm-7">
                    <input id="kartu_nik" class="form-control input-sm required nik" type="text" placeholder="Nomor NIK Penduduk" name="kartu_nik" value="{{ $kartu_nik }}">
                </div>
            </div>
            <div class="form-group">
                <label for="kartu_nama" class="col-sm-4 control-label">Nama</label>
                <div class="col-sm-7">
                    <input id="kartu_nama" class="form-control input-sm required nama" type="text" placeholder="Nama Penduduk" name="kartu_nama" value="{{ $kartu_nama }}">
                </div>
            </div>
            <div class="form-group">
                <label for="kartu_tempat_lahir" class="col-sm-4 control-label">Tempat Lahir</label>
                <div class="col-sm-7">
                    <input
                        id="kartu_tempat_lahir"
                        class="form-control input-sm alamat required"
                        type="text"
                        placeholder="Tempat Lahir"
                        name="kartu_tempat_lahir"
                        maxlength="200"
                        value="{{ $kartu_tempat_lahir }}"
                    >
                </div>
            </div>
            <div class="form-group">
                <label for="kartu_tanggal_lahir" class="col-sm-4 control-label">Tanggal Lahir</label>
                <div class="col-sm-7">
                    <div class="input-group input-group-sm date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input class="form-control input-sm pull-right required" id="tgl_1" name="kartu_tanggal_lahir" placeholder="Tgl. Lahir" type="text" value="{{ date_format(date_create($kartu_tanggal_lahir), 'd-m-Y') }}">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="kartu_alamat" class="col-sm-4 control-label">Alamat</label>
                <div class="col-sm-7">
                    <input
                        id="kartu_alamat"
                        class="form-control input-sm alamat required"
                        type="text"
                        placeholder="Alamat"
                        name="kartu_alamat"
                        maxlength="200"
                        value="{{ $kartu_alamat }}"
                    >
                </div>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <button type="reset" class="btn btn-social btn-danger btn-sm" onclick="reset_form($(this).val());"><i class="fa fa-times"></i> Batal</button>
        <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
    </div>
</form>
