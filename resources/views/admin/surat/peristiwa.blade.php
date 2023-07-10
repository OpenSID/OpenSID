@if ($peristiwa->kode_peristiwa == 2)
    <div class="form-group konfirmasi">
        <label for="keperluan" class="col-sm-3 control-label">Hari / Tanggal / Jam Kematian</label>
        <div class="col-sm-4">
            <input class="form-control input-sm" type="text" value="{{ strtoupper(hari($peristiwa->tgl_peristiwa)) }}" disabled>
        </div>
        <div class="col-sm-2">
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control input-sm" type="text" value="{{ strtoupper(tgl_indo($peristiwa->tgl_peristiwa)) }}" disabled>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                </div>
                <input class="form-control input-sm" type="text" value="{{ strtoupper($peristiwa->jam_mati) }}" disabled>
            </div>
        </div>
    </div>
    <div class="form-group konfirmasi">
        <label for="keperluan" class="col-sm-3 control-label">Tempat Meninggal</label>
        <div class="col-sm-8">
            <input class="form-control input-sm" type="text" value="{{ strtoupper($peristiwa->meninggal_di) }}"
                disabled>
        </div>
    </div>
    <div class="form-group konfirmasi">
        <label for="keperluan" class="col-sm-3 control-label">Penyebab Kematian</label>
        <div class="col-sm-4">
            <input class="form-control input-sm" type="text" value="{{ strtoupper($peristiwa->penyebab_kematian) }}"
                disabled>
        </div>
    </div>
    <div class="form-group konfirmasi">
        <label for="keperluan" class="col-sm-3 control-label">Yang Menerangkan</label>
        <div class="col-sm-4">
            <input class="form-control input-sm" type="text" value="{{ strtoupper($peristiwa->yang_menerangkan) }}"
                disabled>
        </div>
    </div>
    <div class="form-group konfirmasi">
        <label for="keperluan" class="col-sm-3 control-label">Anak Ke</label>
        <div class="col-sm-4">
            <input class="form-control input-sm" type="text" value="{{ strtoupper($peristiwa->penduduk->kelahiran_anak_ke) }}"
                disabled>
        </div>
    </div>
@endif

@if ($peristiwa->kode_peristiwa == 3)
    <div class="form-group konfirmasi">
        <label for="keperluan" class="col-sm-3 control-label">Hari / Tanggal Pindah</label>
        <div class="col-sm-4">
            <input class="form-control input-sm" type="text" value="{{ strtoupper(hari($peristiwa->tgl_peristiwa)) }}" disabled>
        </div>
        <div class="col-sm-2">
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control input-sm" type="text" value="{{ strtoupper(tgl_indo($peristiwa->tgl_peristiwa)) }}" disabled>
            </div>
        </div>
    </div>
    <div class="form-group konfirmasi">
        <label for="keperluan" class="col-sm-3 control-label">Alamat Tujuan</label>
        <div class="col-sm-8">
            <input class="form-control input-sm" type="text" value="{{ strtoupper($peristiwa->alamat_tujuan) }}"
                disabled>
        </div>
    </div>
@endif

@if ($peristiwa->kode_peristiwa == 4)
    <div class="form-group konfirmasi">
        <label for="keperluan" class="col-sm-3 control-label">Hari / Tanggal Hilang</label>
        <div class="col-sm-4">
            <input class="form-control input-sm" type="text" value="{{ strtoupper(hari($peristiwa->tgl_peristiwa)) }}" disabled>
        </div>
        <div class="col-sm-2">
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input class="form-control input-sm" type="text" value="{{ strtoupper(tgl_indo($peristiwa->tgl_peristiwa)) }}" disabled>
            </div>
        </div>
    </div>
@endif