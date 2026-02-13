@php defined('BASEPATH') OR exit('No direct script access allowed'); @endphp

@push('styles')
<style>
    .error_page_content {
        width: 100%;
        text-align: center;
        padding-bottom: 45px;
    }

    .error_page_content h1 {
        font-size: 180px;
        font-weight: 700;
        margin: 0;
        color: #006A82;
        position: relative;
        line-height: 1;
    }

    .error_page_content h1:before,
    .error_page_content h1:after {
        display: none;
        /* atau sesuaikan jika masih ingin garis */
    }

    .error_page_content h2 {
        font-size: 60px;
        font-weight: 400;
        color: #c1c0b4;
    }

    .error_page_content h3 {
        font-size: 24px;
        margin-top: 35px;
    }

    .error_page_content p {
        font-size: 20px;
        line-height: 1.5;
        margin-top: 20px;
        border-top: 2px solid #006A82;
        border-bottom: 2px solid #006A82;
        padding: 10px;
        color: #555;
    }

    .error_page_content p a {
        color: #798992;
        text-decoration: none;
    }
</style>
@endpush
<main class="w-11/12 md:w-9/12 lg:w-7/12 mx-auto px-3 space-y-5 min-h-screen mb-5 flex flex-col items-center justify-center text-center text-gray-700">
    <div class="error_page_content">
        <h1>404</h1>
        <h3>{{ $judulPesan ?: 'Menu Tidak terdaftar' }}</h3>
        <p class="wow fadeInLeftBig">{!! $isiPesan ?: "Silakan tambah menu terlebih dahulu.<br>Anda bisa melihat panduan membuat menu di link <a href='https://panduan.opendesa.id/opensid/halaman-administrasi/admin-web/menu' target='_blank'>Panduan</a>" !!}</p>
    </div>
</main>