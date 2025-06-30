<div class="form-group">
    <label for="nama" class="col-sm-3 control-label">Pemilik</label>
    <div class="col-sm-8">
        <div class="form-group">
            <label class="col-sm-3 control-label">Nama Penduduk</label>
            <div class="col-sm-9">
                <input class="form-control input-sm" type="text" placeholder="Nama Pemilik" value="{{ $pemilik['nama'] }}" disabled>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">NIK Pemilik</label>
            <div class="col-sm-9">
                <input class="form-control input-sm" type="text" placeholder="NIK Pemilik" value="{{ $pemilik['nik'] }}" disabled>
            </div>
        </div>
        <div class="form-group">
            <label for="alamat" class="col-sm-3 control-label">Alamat Pemilik</label>
            <div class="col-sm-9">
                <textarea class="form-control input-sm" placeholder="Alamat Pemilik" rows="5" disabled>{{ 'RT ' . $pemilik->wilayah['rt'] . ' / RT ' . $pemilik->wilayah['rw'] . ' - ' . strtoupper($pemilik->wilayah['dusun']) }}</textarea>
            </div>
        </div>
    </div>
</div>
