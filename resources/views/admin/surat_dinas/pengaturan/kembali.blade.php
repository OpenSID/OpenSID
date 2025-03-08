<div class="box-header with-border">
    @include('admin.layouts.components.tombol_kembali', ['url' => ci_route('surat_dinas'), 'label' => 'Daftar Surat', 'id' => 'kembali'])

    @if (super_admin() && $ci->uri->segment(2) == 'pengaturan')
        <button type="button" id="standar" title="Mengembalikan Standar Spesifikasi Surat" class="btn btn-social btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
            <i class="fa fa-refresh"></i>Mengembalikan Standar Spesifikasi
        </button>
    @endif
</div>
