@php defined('BASEPATH') OR exit('No direct script access allowed'); @endphp

@push('styles')
<style>
    .error_page_content {
        width: 100%;
        text-align: center;
        padding: 45px 0px;
    }

    .error_page_content h1 {
        font-size: 40px;
        font-weight: 700;
        margin: 0;
        color: #007976;
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
        border-top: 2px solid #007976;
        border-bottom: 2px solid #007976;
        padding: 10px;
        color: #555;
    }

    .error_page_content p a {
        color: #798992;
        text-decoration: none;
    }
</style>
@endpush
<main class="w-11/12 md:w-9/12 lg:w-7/12 mx-auto px-3 space-y-5 flex flex-col items-center justify-center text-center text-gray-700 container">
    <div class="error_page_content container">
        <h1>PEMBERITAHUAN</h1>
        <h3>{{ $judulPesan ?: 'Menu Belum Aktif' }}</h3>
        <p class="wow fadeInLeftBig">{!! $isiPesan ?: "Ikut Panduan berikut untuk mengaktifkan Menu Dinamis <br> <a href='https://panduan.opendesa.id/opensid/halaman-administrasi/admin-web/menu' target='_blank'>Link Panduan</a>" !!}</p>
    </div>
</main>