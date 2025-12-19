<div class="tab-pane active" id="umum">
<div class="box-header with-border">
    @include('admin.layouts.components.tombol_kembali', ['url' => ci_route('identitas_desa'), 'label' => 'Data Identitas
    ' . ucwords(setting('sebutan_desa'))])
</div>
<div class="box-body">
    @php $koneksi = cek_koneksi_internet() && $status_pantau ? true : false; @endphp
    <div class="form-group">
        <label class="col-sm-3 control-label" for="nama_desa">Nama
            {{ ucwords(setting('sebutan_desa')) }}</label>
        <div class="col-sm-8">
            @if ($koneksi)
            <select
                id="pilih_desa"
                name="pilih_desa"
                class="form-control input-sm select-nama-desa"
                data-placeholder="{{ $main['nama_desa'] }} - {{ $main['nama_kecamatan'] }} - {{ $main['nama_kabupaten'] }} - {{ $main['nama_propinsi'] }}"
                data-token="{{ config_item('token_pantau') }}"
                data-tracker='{{ config_item('server_pantau') }}'
                style="display: none;"></select>
            @endif
            <input
                type="hidden"
                id="nama_desa"
                class="form-control input-sm nama_desa required"
                minlength="3"
                maxlength="50"
                name="nama_desa"
                value="{{ $main['nama_desa'] }}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label" for="kode_desa">Kode
            {{ ucwords(setting('sebutan_desa')) }}</label>
        <div class="col-sm-2">
            <input
                readonly
                id="kode_desa"
                name="kode_desa"
                class="form-control input-sm {{ jecho($koneksi, false, 'bilangan') }} required"
                {{ jecho($koneksi, false, 'minlength="10" maxlength="10"') }}
                type="text"
                onkeyup="tampil_kode_desa()"
                placeholder="Kode {{ ucwords(setting('sebutan_desa')) }}"
                value="{{ $main['kode_desa'] }}" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label" for="kode_desa_bps">Kode BPS
            {{ ucwords(setting('sebutan_desa')) }}</label>
        <div class="col-sm-2">
            <input
                id="kode_desa_bps"
                name="kode_desa_bps"
                type="text"
                class="form-control input-sm number"
                readonly
                value="{{ $main['kode_desa_bps'] }}"
                {{ jecho($koneksi, false, 'minlength="10" maxlength="10"') }} />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label" for="kode_pos">Kode Pos
            {{ ucwords(setting('sebutan_desa')) }}</label>
        <div class="col-sm-2">
            <input
                id="kode_pos"
                name="kode_pos"
                class="form-control input-sm number"
                minlength="5"
                maxlength="5"
                type="text"
                placeholder="Kode Pos {{ ucwords(setting('sebutan_desa')) }}"
                value="{{ $main['kode_pos'] }}" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label" for="pamong_id">
            {{ ucwords(setting('sebutan_kepala_desa')) }}</label>
        <div class="col-sm-8">
            <input class="form-control input-sm" type="text" placeholder="Nama {{ ucwords(setting('sebutan_kepala_desa')) }}" value="{{ $main['nama_kepala_desa'] }}" readonly />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">NIP {{ ucwords(setting('sebutan_kepala_desa')) }}</label>
        <div class="col-sm-8">
            <input class="form-control input-sm" type="text" placeholder="NIP {{ ucwords(setting('sebutan_kepala_desa')) }}" value="{{ $main['nip_kepala_desa'] }}" readonly />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label" for="alamat_kantor">Alamat Kantor
            {{ ucwords(setting('sebutan_desa')) }}</label>
        <div class="col-sm-8">
            <textarea
                id="alamat_kantor"
                name="alamat_kantor"
                class="form-control input-sm alamat required"
                maxlength="100"
                placeholder="Alamat Kantor {{ ucwords(setting('sebutan_desa')) }}"
                rows="3"
                style="resize:none;">{{ $main['alamat_kantor'] }}</textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label" for="email_desa">E-Mail
            {{ ucwords(setting('sebutan_desa')) }}</label>
        <div class="col-sm-8">
            <input
                id="email_desa"
                name="email_desa"
                class="form-control input-sm email"
                maxlength="50"
                type="email"
                placeholder="E-Mail {{ ucwords(setting('sebutan_desa')) }}"
                value="{{ $main['email_desa'] }}" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label" for="telepon">Nomor Telepon
            {{ ucwords(setting('sebutan_desa')) }}</label>
        <div class="col-sm-8">
            <input
                id="telepon"
                name="telepon"
                class="form-control input-sm bilangan"
                type="text"
                maxlength="15"
                placeholder="Telepon {{ ucwords(setting('sebutan_desa')) }}"
                value="{{ $main['telepon'] }}" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label" for="telepon">Nomor Ponsel
            {{ ucwords(setting('sebutan_desa')) }}</label>
        <div class="col-sm-8">
            <input
                id="telepon-operator"
                name="nomor_operator"
                class="form-control input-sm bilangan"
                type="text"
                maxlength="15"
                placeholder="Nomor Ponsel"
                value="{{ $main['nomor_operator'] }}" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label" for="website">Website
            {{ ucwords(setting('sebutan_desa')) }}</label>
        <div class="col-sm-8">
            <input
                id="website"
                name="website"
                class="form-control input-sm url"
                maxlength="50"
                type="text"
                placeholder="Website {{ ucwords(setting('sebutan_desa')) }}"
                value="{{ $main['website'] }}" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label" for="nama_kecamatan">Nama
            {{ ucwords(setting('sebutan_kecamatan')) }}</label>
        <div class="col-sm-8">
            <input
                readonly
                id="nama_kecamatan"
                name="nama_kecamatan"
                class="form-control input-sm required"
                type="text"
                placeholder="Nama {{ ucwords(setting('sebutan_kecamatan')) }}"
                value="{{ $main['nama_kecamatan'] }}" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label" for="kode_kecamatan">Kode
            {{ ucwords(setting('sebutan_kecamatan')) }}</label>
        <div class="col-sm-2">
            <input
                readonly
                id="kode_kecamatan"
                name="kode_kecamatan"
                class="form-control input-sm required"
                type="text"
                placeholder="Kode {{ ucwords(setting('sebutan_kecamatan')) }}"
                value="{{ $main['kode_kecamatan'] }}" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label" for="nama_kecamatan">Nama
            {{ ucwords(setting('sebutan_camat')) }}</label>
        <div class="col-sm-8">
            <input
                id="nama_kepala_camat"
                name="nama_kepala_camat"
                class="form-control input-sm nama required"
                maxlength="50"
                type="text"
                placeholder="Nama {{ ucwords(setting('sebutan_camat')) }}"
                value="{{ $main['nama_kepala_camat'] }}" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label" for="nip_kepala_camat">NIP
            {{ ucwords(setting('sebutan_camat')) }}</label>
        <div class="col-sm-4">
            <input
                id="nip_kepala_camat"
                name="nip_kepala_camat"
                class="form-control input-sm nomor_sk"
                maxlength="50"
                type="text"
                placeholder="NIP {{ ucwords(setting('sebutan_camat')) }}"
                value="{{ $main['nip_kepala_camat'] }}" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label" for="nama_kabupaten">Nama
            {{ ucwords(setting('sebutan_kabupaten')) }}</label>
        <div class="col-sm-8">
            <input
                readonly
                id="nama_kabupaten"
                name="nama_kabupaten"
                class="form-control input-sm required"
                type="text"
                placeholder="Nama {{ ucwords(setting('sebutan_kabupaten')) }}"
                value="{{ $main['nama_kabupaten'] }}" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label" for="kode_kabupaten">Kode
            {{ ucwords(setting('sebutan_kabupaten')) }}</label>
        <div class="col-sm-2">
            <input
                readonly
                id="kode_kabupaten"
                name="kode_kabupaten"
                class="form-control input-sm required"
                type="text"
                placeholder="Kode {{ ucwords(setting('sebutan_kabupaten')) }}"
                value="{{ $main['kode_kabupaten'] }}" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label" for="nama_propinsi">Nama Provinsi</label>
        <div class="col-sm-8">
            <input
                readonly
                id="nama_propinsi"
                name="nama_propinsi"
                class="form-control input-sm required"
                type="text"
                placeholder="Nama Propinsi"
                value="{{ $main['nama_propinsi'] }}" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label" for="kode_propinsi">Kode Provinsi</label>
        <div class="col-sm-2">
            <input
                readonly
                id="kode_propinsi"
                name="kode_propinsi"
                class="form-control input-sm required"
                type="text"
                placeholder="Kode Provinsi"
                value="{{ $main['kode_propinsi'] }}" />
        </div>
    </div>
    <hr>
    <h5 class="text-bold"> KONTAK PEMBERITAHUAN</h5>
    @php
    $required = !config_item('demo_mode') ? 'required' : '';
    @endphp
    <div class="form-group">
        <label class="col-sm-3 control-label" for="nama_kontak">Nama {{ ucwords(setting('sebutan_pemerintah_desa')) }}</label>
        <div class="col-sm-8">
            <input
                id="nama_kontak"
                name="nama_kontak"
                class="form-control input-sm nama {{ $required }}"
                type="text"
                placeholder="Nama"
                value="{{ $main['nama_kontak'] }}"
                maxlength="50" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label" for="hp_kontak">No. HP/WA</label>
        <div class="col-sm-8">
            <input
                id="hp_kontak"
                name="hp_kontak"
                class="form-control input-sm angka {{ $required }}"
                type="text"
                placeholder="No. HP"
                value="{{ $main['hp_kontak'] }}"
                maxlength="15" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label" for="jabatan_kontak">Jabatan</label>
        <div class="col-sm-8">
            <input
                id="jabatan_kontak"
                name="jabatan_kontak"
                class="form-control input-sm nama {{ $required }}"
                type="text"
                placeholder="Jabatan"
                value="{{ $main['jabatan_kontak'] }}"
                maxlength="50" />
        </div>
    </div>
</div>
<div class="box-footer">
    <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i>
        Batal</button>
    <button type="submit" class="btn btn-social btn-info btn-sm pull-right simpan"><i class="fa fa-check"></i>
        Simpan</button>
</div>
</div>