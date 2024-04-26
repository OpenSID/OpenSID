@if ($peristiwa->kode_peristiwa == $logpenduduk::BARU_LAHIR)
    <div class="form-group">
        <label for="keperluan" class="col-sm-3 control-label">Hari / Tanggal / Jam Kelahiran</label>
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
                <input class="form-control input-sm" type="text" value="{{ strtoupper($peristiwa->penduduk->waktu_lahir) }}" disabled>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="keperluan" class="col-sm-3 control-label">Tempat Dilahirkan</label>
        <div class="col-sm-8">
            <input class="form-control input-sm" type="text" value="{{ strtoupper($peristiwa->penduduk->getDiLahirkanAttribute()) }}" disabled>
        </div>
    </div>
    <div class="form-group">
        <label for="keperluan" class="col-sm-3 control-label">Tempat Kelahiran</label>
        <div class="col-sm-8">
            <input class="form-control input-sm" type="text" value="{{ strtoupper($peristiwa->penduduk->tempatlahir) }}" disabled>
        </div>
    </div>
    <div class="form-group">
        <label for="keperluan" class="col-sm-3 control-label">Jenis Kelahiran</label>
        <div class="col-sm-4">
            <input class="form-control input-sm" type="text" value="{{ strtoupper($peristiwa->penduduk->getJenisLahirAttribute()) }}" disabled>
        </div>
    </div>
    <div class="form-group">
        <label for="keperluan" class="col-sm-3 control-label">Kelahiran Anak Ke</label>
        <div class="col-sm-4">
            <input class="form-control input-sm" type="text" value="{{ strtoupper($peristiwa->penduduk->kelahiran_anak_ke) }}" disabled>
        </div>
    </div>
    <div class="form-group">
        <label for="keperluan" class="col-sm-3 control-label">Penolong Kelahiran</label>
        <div class="col-sm-4">
            <input class="form-control input-sm" type="text" value="{{ strtoupper($peristiwa->penduduk->getPenolongLahirAttribute()) }}" disabled>
        </div>
    </div>
    <div class="form-group">
        <label for="keperluan" class="col-sm-3 control-label">Berat Bayi</label>
        <div class="col-sm-4">
            <input class="form-control input-sm" type="text" value="{{ strtoupper($peristiwa->penduduk->berat_lahir) }} gram" disabled>
        </div>
    </div>
    <div class="form-group">
        <label for="keperluan" class="col-sm-3 control-label">Panjang Bayi</label>
        <div class="col-sm-4">
            <input class="form-control input-sm" type="text" value="{{ strtoupper($peristiwa->penduduk->panjang_lahir) }} cm" disabled>
        </div>
    </div>
@endif

@if ($peristiwa->kode_peristiwa == $logpenduduk::MATI)
    <div class="form-group">
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
    <div class="form-group">
        <label for="keperluan" class="col-sm-3 control-label">Tempat Meninggal</label>
        <div class="col-sm-8">
            <input class="form-control input-sm" type="text" value="{{ strtoupper($peristiwa->meninggal_di) }}" disabled>
        </div>
    </div>
    <div class="form-group">
        <label for="keperluan" class="col-sm-3 control-label">Penyebab Kematian</label>
        <div class="col-sm-4">
            <input class="form-control input-sm" type="text" value="{{ strtoupper($peristiwa->penyebab_kematian) }}" disabled>
        </div>
    </div>
    <div class="form-group">
        <label for="keperluan" class="col-sm-3 control-label">Yang Menerangkan</label>
        <div class="col-sm-4">
            <input class="form-control input-sm" type="text" value="{{ strtoupper($peristiwa->yang_menerangkan) }}" disabled>
        </div>
    </div>
    <div class="form-group">
        <label for="keperluan" class="col-sm-3 control-label">Anak Ke</label>
        <div class="col-sm-4">
            <input class="form-control input-sm" type="text" value="{{ strtoupper($peristiwa->penduduk->kelahiran_anak_ke) }}" disabled>
        </div>
    </div>
@endif

@if ($peristiwa->kode_peristiwa == $logpenduduk::PINDAH_KELUAR)
    <div class="form-group">
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
    <div class="form-group">
        <label for="keperluan" class="col-sm-3 control-label">Alamat Tujuan</label>
        <div class="col-sm-8">
            <input class="form-control input-sm" type="text" value="{{ strtoupper($peristiwa->alamat_tujuan) }}" disabled>
        </div>
    </div>
@endif

@if ($peristiwa->kode_peristiwa == $logpenduduk::HILANG)
    <div class="form-group">
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
