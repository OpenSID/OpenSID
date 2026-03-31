@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp

<div class="artikel" id="artikel-blank">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="error_page_content">
            <h1>PEMBERITAHUAN</h1>
            <h3>{{ $judulPesan ?: 'Menu Belum Aktif' }}</h3>
            <p class="wow fadeInLeftBig">{!! $isiPesan ?: "Ikut Panduan berikut untuk mengaktifkan Menu Dinamis <br><a href='https://panduan.opendesa.id/opensid/halaman-administrasi/admin-web/menu' target='_blank'>Link Panduan</a>" !!}</p>
        </div>
    </div>
</div>
