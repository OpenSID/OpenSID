<div class="penduduk_luar_desa penduduk_luar_{{ $index }} {{ count($opsiSumberPenduduk) > 1 ? 'hide' : '' }}">
    <div class="form-group">
        <label class="col-sm-3 control-label"><strong>Nama Lengkap / NIK KTP</strong></label>
        <div class="col-sm-5 col-lg-6">
            <input {{ $kategori == 'individu' ? 'data-visible-required="1"' : '' }} name="{{ $kategori }}[nama]" class="form-control input-sm isi-penduduk-luar" type="text" placeholder="Nama Lengkap" />
        </div>
        <div class="col-sm-3 col-lg-2">
            <input {{ $kategori == 'individu' ? 'data-visible-required="1"' : '' }} name="{{ $kategori }}[nik]" class="form-control input-sm isi-penduduk-luar nik" type="text" placeholder="Nomor KTP" />
        </div>
    </div>
    @if (in_array('tempat_lahir', $input) && in_array('tanggal_lahir', $input))
    <div class="form-group">
        <label for="tempatlahir" class="col-sm-3 control-label">Tempat Tanggal Lahir</label>
        <div class="col-sm-5 col-lg-6">
            <input class="form-control input-sm" type="text" name="{{ $kategori }}[tempatlahir]" id="tempatlahir"
                placeholder="Tempat Lahir" />
        </div>
        <div class="col-sm-3 col-lg-2">
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input title="Pilih Tanggal" class="form-control datepicker input-sm" name="{{ $kategori }}[tanggallahir]"
                    type="text" placeholder="Tgl. Lahir" />
            </div>
        </div>
    </div>
    @elseif (in_array('tempat_lahir', $input))
    <div class="form-group">
        <label for="tempatlahir" class="col-sm-3 control-label">Tempat Lahir</label>
        <div class="col-sm-5 col-lg-6">
            <input class="form-control input-sm" type="text" name="{{ $kategori }}[tempatlahir]" id="tempatlahir"
                placeholder="Tempat Lahir" />
        </div>
    </div>
    @elseif (in_array('tanggal_lahir', $input))
    <div class="form-group">
        <label for="tempatlahir" class="col-sm-3 control-label">Tanggal Lahir</label>
        <div class="col-sm-3 col-lg-2">
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input title="Pilih Tanggal" class="form-control datepicker input-sm" name="{{ $kategori }}[tanggallahir]"
                    type="text" placeholder="Tgl. Lahir" />
            </div>
        </div>
    </div>
    @endif
    @if (in_array('agama', $input))
    <div class="form-group">
        <label for="tempatlahir" class="col-sm-3 control-label">Agama</label>
        <div class="col-sm-3">
            <select class="form-control input-sm select2" name="{{ $kategori }}[agama]">
                <option value="">-- Pilih Agama --</option>
                @foreach (\App\Models\Agama::get() as $data )
                <option value="{{ $data->nama }}">{{ $data->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>
    @endif
    @if (in_array('pekerjaan', $input))
    <div class="form-group">
        <label for="tempatlahir" class="col-sm-3 control-label">Pekerjaan</label>
        <div class="col-sm-3">
            <select class="form-control input-sm select2" name="{{ $kategori }}[pekerjaan]">
                <option value="">-- Pilih Pekerjaan --</option>
                @foreach (\App\Models\Pekerjaan::get() as $data )
                <option value="{{ $data->nama }}">{{ $data->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>
    @endif
    @if (in_array('warga_negara', $input))
    <div class="form-group">
        <label for="tempatlahir" class="col-sm-3 control-label">Warga Negara</label>
        <div class="col-sm-3">
            <select class="form-control input-sm select2" name="{{ $kategori }}[warga_negara]">
                <option value="">-- Pilih Warga Negara --</option>
                @foreach (\App\Models\WargaNegara::get() as $data )
                <option value="{{ $data->nama }}">{{ $data->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>
    @endif
    
    @if (in_array('pendidikan', $input))
    <div class="form-group">
        <label class="col-sm-3 control-label"><strong>Pendidikan Terakhir</strong></label>
        <div class="col-sm-3">
            <input name="{{ $kategori }}[pendidikan]" class="form-control input-sm" type="text" placeholder="Pendidikan Terakhir" />
        </div>
    </div>
    @endif
    @if (in_array('alamat', $input))
    <div class="form-group">
        <label class="col-sm-3 control-label"><strong>Tempat Tinggal</strong></label>
        <div class="col-sm-9">
            <input name="{{ $kategori }}[alamat]" class="form-control input-sm" type="text" placeholder="Tempat Tinggal" />
        </div>
    </div>
    @endif
</div>