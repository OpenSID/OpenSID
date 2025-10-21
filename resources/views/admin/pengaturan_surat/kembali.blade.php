<div class="box-header with-border">
    @include('admin.layouts.components.tombol_kembali', ['url' => ci_route('surat_master'), 'label' => 'Daftar Surat'])

    @if (super_admin() && $ci->uri->segment(2) == 'pengaturan')
        <a href="javascript:void(0)" id="standar" title="Mengembalikan Standar Spesifikasi Surat" role="button" class="btn btn-social btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
            <i class="fa fa-refresh"></i>Mengembalikan Standar Spesifikasi
        </a>
    @endif
    @if (in_array($suratMaster->jenis, [3, 4]))
        @if (setting('tte'))
            <br /><br />
            <div class="alert alert-info alert-dismissible">
                <h4><i class="icon fa fa-info"></i> Info !</h4>
                Jika surat ingin dikirim ke {{ setting('sebutan_kecamatan') }}, letakan kode [qr_camat] pada tempat yang ingin ditempelkan QRCode
                {{ setting('sebutan_kecamatan') }}.
            </div>
        @endif
    @endif
</div>
