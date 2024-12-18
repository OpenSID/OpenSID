@include('admin.layouts.components.validasi_form')
<form id="validasi" action="{{ $form_action }}" method="post">
    <div class="box-body">
        <div class="form-group">
            <label class="control-label" for="nama">Nama Periode</label>
            <input id="nama" class="form-control input-sm required nomor_sk" type="text" placeholder="Nama Priode" name="nama" value="{{ $analisis_periode['nama'] }}">
        </div>
        <div class="form-group">
            <label class="control-label" for="id_state">Tahap Pendataan</label>
            <select id="id_state" class="form-control input-sm select2 required" name="id_state">
                <option value="">Pilih Tahap Pendataan</option>
                @foreach ($tahapan as $key => $value)
                    <option value="{{ $key }}" @if ($key == $analisis_periode['id_state']) selected @endif>{{ $value }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="control-label" for="tahun_pelaksanaan">Tahun Pelaksanaan</label>
            <input
                id="tahun_pelaksanaan"
                class="form-control input-sm required bilangan"
                maxlength="4"
                type="text"
                placeholder="Tahun"
                name="tahun_pelaksanaan"
                value="{{ $analisis_periode['tahun_pelaksanaan'] }}"
            >
        </div>
        @if (!$analisis_periode)
            <div class="form-group">
                <label class="control-label" for="duplikasi">Duplikat data pendataan sebelumnya</label>
                <select id="duplikasi" class="form-control input-sm select2 required" name="duplikasi">
                    <option value="1">Ya</option>
                    <option value="0" selected>Tidak</option>
                </select>
            </div>
        @endif
        <div class="form-group">
            <label class="control-label" for="keterangan">Keterangan</label>
            <textarea id="keterangan" class="form-control input-sm" placeholder="Keterangan" name="keterangan" rows="5">{{ $analisis_periode['keterangan'] }}</textarea>
        </div>
        <div class="form-group">
            <label class="control-label" for="aktif">Status</label>
            <select id="aktif" class="form-control input-sm select2 required" name="aktif">
                <option value="">Pilih Status</option>
                <option value="1" @if ($analisis_periode['aktif'] == 1) selected @endif>Aktif</option>
                <option value="0" @if ($analisis_periode['aktif'] == 0) selected @endif>Tidak Aktif</option>
            </select>
        </div>
    </div>
    <div class="modal-footer">
        <button type="reset" class="btn btn-social btn-danger btn-sm pull-left"><i class='fa fa-times'></i> Batal</button>
        <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
    </div>
</form>
