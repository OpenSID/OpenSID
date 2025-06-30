<div class="form-group konfirmasi">
    <label class="col-sm-4 control-label">Tempat Tanggal Lahir / Umur</label>
    <div class="col-sm-4">
        <input class="form-control input-sm" type="text" value="{{ strtoupper($individu->tempatlahir) }}" disabled>
    </div>
    <div class="col-sm-2">
        <input class="form-control input-sm" type="text" value="{{ strtoupper(tgl_indo($individu->tanggallahir)) }}" disabled>
    </div>
    <div class="col-sm-2">
        <input class="form-control input-sm" type="text" value="{{ strtoupper($individu->umur) }} TAHUN" disabled>
    </div>
</div>
<div class="form-group konfirmasi">
    <label class="col-sm-4 control-label">Alamat</label>
    <div class="col-sm-8">
        <input class="form-control input-sm" type="text" value="{{ strtoupper($individu->alamat_wilayah) }}" disabled>
    </div>
</div>
<div class="form-group konfirmasi">
    <label class="col-sm-4 control-label">Pendidikan / Warga Negara /Agama</label>
    <div class="col-sm-4">
        <input class="form-control input-sm" type="text" value="{{ strtoupper($individu->pendidikanKK->nama) }}" disabled>
    </div>
    <div class="col-sm-2">
        <input class="form-control input-sm" type="text" value="{{ strtoupper($individu->warganegara->nama) }}" disabled>
    </div>
    <div class="col-sm-2">
        <input class="form-control input-sm" type="text" value="{{ strtoupper($individu->agama->nama) }}" disabled>
    </div>
</div>
