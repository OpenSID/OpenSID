<div class="box box-primary">
    <div class="box-header with-border">
        <b>{{ $judul }}</b>
    </div>
    <div class="box-body box-profile text-center preview-img">
        <a href="{{ $foto }}" class="progressive replace">
            <img class="preview" loading="lazy" src="{{ base_url('assets/images/img-loader.gif') }}" alt="{{ $judul }}" width="100%" />
        </a>
        <p class="text-muted text-center text-red">(Kosongkan, jika {{ strtolower($judul) }} tidak berubah)</p>
        <div class="input-group input-group-sm">
            <input type="file" class="hidden file-input" id="file{{ $nomor }}" name="{{ $name }}" accept=".jpg,.jpeg,.png,.webp" />
            <input type="text" class="form-control hidden" id="file_path{{ $nomor }}" name="{{ $name }}">
        </div>
        <button type="button" class="btn btn-info btn-sm btn-block btn-mb-5" id="file_browser{{ $nomor }}"><i class="fa fa-upload"></i>
            Unggah</button>
    </div>
</div>
