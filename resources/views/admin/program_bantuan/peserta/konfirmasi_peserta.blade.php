<div class="form-group">
    <label class="col-sm-4 col-lg-5 control-label">{{ $individu['judul_nik'] }}</label>
    <div class="col-sm-7">
        <input class="form-control input-sm" type="text" disabled value="{{ $individu['nik'] }}">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 col-lg-5 control-label">Nama {{ $individu['judul'] }}</label>
    <div class="col-sm-7">
        <input class="form-control input-sm" type="text" disabled value="{{ $individu['nama'] }}">
    </div>
</div>
@if ($detail['sasaran'] == 2)
    <div class="form-group">
        <label class="col-sm-4 col-lg-5 control-label">Nomer KK</label>
        <div class="col-sm-7">
            <input class="form-control input-sm" type="text" disabled value="{{ $individu['no_kk'] }}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 col-lg-5 control-label">Nama Kepala Keluarga</label>
        <div class="col-sm-7">
            <input class="form-control input-sm" type="text" disabled value="{{ $individu['nama_kk'] }}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 col-lg-5 control-label">Status KK</label>
        <div class="col-sm-7">
            <input class="form-control input-sm" type="text" disabled value="{{ $individu['hubungan'] }}">
        </div>
    </div>
@endif
<div class="form-group">
    <label class="col-sm-4 col-lg-5 control-label">Alamat {{ $individu['judul'] }}</label>
    <div class="col-sm-7">
        <input class="form-control input-sm" type="text" disabled value="{{ $individu['alamat_wilayah'] }}">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 col-lg-5 control-label">Tempat Tanggal, Lahir {{ $individu['judul'] }}</label>
    <div class="col-sm-7">
        <input class="form-control input-sm" type="text" disabled value="{{ $individu['tempatlahir'] }}, {{ tgl_indo($individu['tanggallahir']) }}">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 col-lg-5 control-label">Jenis Kelamin {{ $individu['judul'] }}</label>
    <div class="col-sm-7">
        <input class="form-control input-sm" type="text" disabled value="{{ $individu['sex'] }}">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 col-lg-5 control-label">Umur {{ $individu['judul'] }}</label>
    <div class="col-sm-7">
        <input class="form-control input-sm" type="text" disabled value="{{ $individu['umur'] }} TAHUN">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 col-lg-5 control-label">Pendidikan {{ $individu['judul'] }}</label>
    <div class="col-sm-7">
        <input class="form-control input-sm" type="text" disabled value="{{ $individu['pendidikan'] }}">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 col-lg-5 control-label">Warga Negara / Agama {{ $individu['judul'] }}</label>
    <div class="col-sm-7">
        <input class="form-control input-sm" type="text" disabled value="{{ $individu['warganegara'] }} / {{ $individu['agama'] }}">
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 col-lg-5 control-label">Bantuan {{ $individu['judul'] }} Yang Sedang Diterima</label>
    <div class="col-sm-7">
        @foreach ($individu['program']['programkerja'] as $item)
            @if ($item->status == '1')
                {!! anchor("peserta_bantuan/data_peserta/{$item->peserta_id}/{$item->id}", '<span class="label label-success">' . $item->nama . '</span>&nbsp;', 'target="_blank"') !!}
            @endif
        @endforeach
    </div>
</div>
