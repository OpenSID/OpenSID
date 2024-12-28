@php defined('BASEPATH') || exit('No direct script access allowed'); @endphp

<div class="artikel" id="artikel-blank">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="error_page_content">
            <h1>404</h1>
            <h3>{{ $judulPesan ?: 'Menu Tidak terdaftar' }}</h3>
            <p class="wow fadeInLeftBig">{!! $isiPesan ?: "Silahkan tambah menu terlebih dahulu.<br>Anda bisa melihat panduan membuat menu di link <a href='https://panduan.opendesa.id/opensid/halaman-administrasi/admin-web/menu' target='_blank'>Panduan</a>" !!}</p>
        </div>
    </div>
</div>
