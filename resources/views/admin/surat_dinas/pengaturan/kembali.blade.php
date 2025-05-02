<div class="box-header with-border">
    <a id="kembali" href="{{ ci_route('surat_dinas') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
        <i class="fa fa-arrow-circle-left"></i>Kembali ke Daftar Surat
    </a>
    @if (super_admin() && $ci->uri->segment(2) == 'pengaturan')
        <button type="button" id="standar" title="Mengembalikan Standar Spesifikasi Surat" class="btn btn-social btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
            <i class="fa fa-refresh"></i>Mengembalikan Standar Spesifikasi
        </button>
    @endif
</div>
