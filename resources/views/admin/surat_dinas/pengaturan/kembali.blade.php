<div class="box-header with-border">
    <a href="{{ ci_route('surat_dinas') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
        <i class="fa fa-arrow-circle-left"></i>Kembali ke Daftar Surat
    </a>
    @if (in_array($suratDinas->jenis, [3, 4]))
        @if (super_admin() && $suratDinas->jenis == 3)
            <a href="#" data-href="{{ ci_route('surat_dinas.restore_surat_bawaan', $suratDinas->url_surat) }}" title="Mengembalikan Surat Bawaan/Sistem" data-toggle="modal" data-target="#confirm-restore"
                class="btn btn-social btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"
            >
                <i class="fa fa-refresh"></i>Mengembalikan Surat Bawaan/Sistem
            </a>
        @endif
    @endif
</div>
