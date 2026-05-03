<div class="box-header with-border">
    @include('admin.layouts.components.tombol_kembali', ['url' => ci_route('surat_dinas'), 'label' => 'Daftar Surat', 'id' => 'kembali'])

    @if (super_admin() && $ci->uri->segment(2) == 'pengaturan')
        <a href="javascript:void(0)" id="standar" title="Mengembalikan Standar Spesifikasi Surat" role="button" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
            <i class="fa fa-refresh"></i>Mengembalikan Standar Spesifikasi
        </a>
    @endif
</div>
