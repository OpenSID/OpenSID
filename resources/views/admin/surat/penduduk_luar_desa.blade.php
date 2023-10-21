<div class="penduduk_luar_desa penduduk_luar_{{ $index }} {{ count($opsiSumberPenduduk) > 1 ? 'hide' : '' }}">
    <div class="form-group">
        <label class="col-sm-3 control-label"><strong>Nama Lengkap / NIK KTP</strong></label>
        <div class="col-sm-5 col-lg-6">
            <input name="{{ $kategori }}[nama]" class="form-control input-sm" type="text" placeholder="Nama Lengkap">
        </div>
        <div class="col-sm-3 col-lg-2">
            <input name="{{ $kategori }}[no_ktp]" class="form-control input-sm" type="text" placeholder="Nomor KTP">
        </div>
    </div>
    @if (in_array('tempat_lahir', $input) && in_array('tanggal_lahir', $input))
    <div class="form-group">
        <label for="tempatlahir" class="col-sm-3 control-label">Tempat Tanggal Lahir</label>
        <div class="col-sm-5 col-lg-6">
            <input class="form-control input-sm" type="text" name="{{ $kategori }}[tempatlahir]" id="tempatlahir"
                placeholder="Tempat Lahir" value="">
        </div>
        <div class="col-sm-3 col-lg-2">
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input title="Pilih Tanggal" class="form-control datepicker input-sm" name="{{ $kategori }}[tanggallahir]"
                    type="text" placeholder="Tgl. Lahir" value="" />
            </div>
        </div>
    </div>
    @elseif (in_array('tempat_lahir', $input))
    <div class="form-group">
        <label for="tempatlahir" class="col-sm-3 control-label">Tempat Lahir</label>
        <div class="col-sm-5 col-lg-6">
            <input class="form-control input-sm" type="text" name="{{ $kategori }}[tempatlahir]" id="tempatlahir"
                placeholder="Tempat Lahir" value="">
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
                    type="text" placeholder="Tgl. Lahir" value="" />
            </div>
        </div>
    </div>
    @endif
    @if (in_array('agama', $input))
    <div class="form-group">
        <label for="tempatlahir" class="col-sm-3 control-label">Agama</label>        
        <div class="col-sm-3">
            <select class="form-control input-sm select2" name="{{ $kategori }}[agama]" style="width:100%;">
                <option value="">-- Pilih Agama --</option>
                @foreach (\App\Models\Agama::get() as $data )
                <option value="{{ $data->nama }}">
                    {{ $data->nama }}</option>
                @endforeach
            </select>
        </div>        
    </div>
    @endif
    @if (in_array('pekerjaan', $input))
    <div class="form-group">
        <label for="tempatlahir" class="col-sm-3 control-label">Pekerjaan</label>
        <div class="col-sm-9">
            <select class="form-control input-sm select2" name="{{ $kategori }}[wn]"  style="width:100%;">
                <option value="">-- Pilih warganegara --</option>
                @foreach (\App\Models\WargaNegara::get() as $data )
                <option value="{{ $data->nama }}">
                    {{ $data->nama }}</option>
                @endforeach
            </select>
        </div>        
    </div>
    @endif
    @if (in_array('warga_negara', $input))
    <div class="form-group">
        <label for="tempatlahir" class="col-sm-3 control-label">Warganegara</label>
        <div class="col-sm-9">
            <select class="form-control input-sm select2" name="{{ $kategori }}[wn]"  style="width:100%;">
                <option value="">-- Pilih warganegara --</option>
                @foreach (\App\Models\WargaNegara::get() as $data )
                <option value="{{ $data->nama }}">
                    {{ $data->nama }}</option>
                @endforeach
            </select>
        </div>        
    </div>
    @endif
    
    @if (in_array('pendidikan', $input))
    <div class="form-group">
        <label class="col-sm-3 control-label"><strong>Pendidikan Terakhir</strong></label>
        <div class="col-sm-8">
            <input name="{{ $kategori }}[pendidikan]" class="form-control input-sm" type="text" placeholder="Pendidikan Terakhir"
                value="">
        </div>
    </div>
    @endif
    @if (in_array('alamat', $input))
    <div class="form-group">
        <label class="col-sm-3 control-label"><strong>Tempat Tinggal</strong></label>
        <div class="col-sm-8">
            <input name="{{ $kategori }}[alamat]" class="form-control input-sm" type="text" placeholder="Tempat Tinggal"
                value="">
        </div>
    </div>
    @endif
</div>