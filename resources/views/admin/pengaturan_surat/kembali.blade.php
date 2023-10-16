<div class="box-header with-border">
    <a href="{{ route('surat_master') }}"
        class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
        <i class="fa fa-arrow-circle-left"></i>Kembali ke Daftar Surat
    </a>
    @if ($data)
        <a href="#" id="addTabBtn2" data-toggle="modal" data-target="#modal-tab"
            class="btn btn-social btn-primary btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
            <i class="fa fa-plus"></i>Tambah Bagian Form</a>
    @endif
</div>
