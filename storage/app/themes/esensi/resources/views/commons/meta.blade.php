@php
    $nama_desa = ucwords(setting('sebutan_desa')) . ' ' . ucwords($desa['nama_desa']);

    $title = preg_replace('/[^A-Za-z0-9- ]/', '', trim(str_replace('-', ' ', get_dynamic_title_page_from_path())));
    $suffix = setting('website_title') . ' ' . ucwords(setting('sebutan_desa')) . ($desa['nama_desa'] ? ' ' . $desa['nama_desa'] : '');
    $desa_title = $title ? $title . ' - ' . $suffix : $suffix;
@endphp

<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name='viewport' content='width=device-width, initial-scale=1' />
<meta name='google' content='notranslate' />
<meta name='theme' content='Esensi' />
<meta name='designer' content='Diki Siswanto' />
<meta name='theme:designer' content='Diki Siswanto' />
<meta name='theme:version' content='{{ $themeVersion }}' />
<meta name="theme-color" content="#efefef">
<meta name='keywords'
    content="{{ $desa_title }} @if (!strpos($desa_title, $nama_desa)) {{ $nama_desa }} @endif {{ ucfirst(setting('sebutan_kecamatan')) }} {{ ucwords($desa['nama_kecamatan']) }}, {{ ucfirst(setting('sebutan_kabupaten')) }} {{ ucwords($desa['nama_kabupaten']) }}, Provinsi  {{ ucwords($desa['nama_propinsi']) }}"
/>
<meta property="og:site_name" content="{{ $nama_desa }}" />
<meta property="og:type" content="article" />
<link rel="canonical" href="{{ site_url() }}" />
<meta name='robots' content='index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1' />
<meta name="subject" content="Situs Web Desa">
<meta name="copyright" content="{{ $nama_desa }}">
<meta name="language" content="Indonesia">
<meta name="revised" content="Sunday, July 18th, 2010, 5:15 pm" />
<meta name="Classification" content="Government">
<meta name="url" content="{{ site_url() }}">
<meta name="identifier-URL" content="{{ site_url() }}">
<meta name="category" content="Desa, Pemerintahan">
<meta name="coverage" content="Worldwide">
<meta name="distribution" content="Global">
<meta name="rating" content="General">
<meta name="revisit-after" content="7 days">
<meta name="revisit-after" content="7" />
<meta name="webcrawlers" content="all" />
<meta name="rating" content="general" />
<meta name="spiders" content="all" />
<link rel="alternate" type="application/rss+xml" title="Feed {{ $nama_desa }}" href="{{ site_url('sitemap') }}" />

@if (isset($single_artikel))
    <title>{{ $single_artikel['judul'] . ' - ' . $nama_desa }}</title>
    <meta name='description' content="{{ str_replace('"', "'", substr(strip_tags($single_artikel['isi']), 0, 150)) }}" />
    <meta property="og:title" content="{{ $single_artikel['judul'] }}" />
    <meta itemprop="name" content="{{ $single_artikel['judul'] }}" />
    <meta itemprop='description' content="{{ str_replace('"', "'", substr(strip_tags($single_artikel['isi']), 0, 150)) }}" />
    @if (trim($single_artikel['gambar']) != '')
        <meta property="og:image" content="{{ base_url(LOKASI_FOTO_ARTIKEL . 'sedang_' . $single_artikel['gambar']) }}" />
        <meta itemprop="image" content="{{ base_url(LOKASI_FOTO_ARTIKEL . 'sedang_' . $single_artikel['gambar']) }}" />
    @endif
    <meta property='og:description' content="{{ str_replace('"', "'", substr(strip_tags($single_artikel['isi']), 0, 150)) }}" />
@else
    <title>{{ $desa_title }}</title>
    <meta name='description'
        content="{{ $desa_title }} @if (!strpos($desa_title, $nama_desa)) {{ $nama_desa }} @endif {{ ucfirst(setting('sebutan_kecamatan')) }} {{ ucwords($desa['nama_kecamatan']) }}, {{ ucfirst(setting('sebutan_kabupaten')) }} {{ ucwords($desa['nama_kabupaten']) }}, Provinsi  {{ ucwords($desa['nama_propinsi']) }}"
    />
    <meta itemprop="name" content="{{ $desa_title }}" />
    <meta property="og:title" content="{{ $desa_title }}" />
    <meta property='og:description'
        content="{{ $desa_title }} @if (!strpos($desa_title, $nama_desa)) {{ $nama_desa }} @endif {{ ucfirst(setting('sebutan_kecamatan')) }} {{ ucwords($desa['nama_kecamatan']) }}, {{ ucfirst(setting('sebutan_kabupaten')) }} {{ ucwords($desa['nama_kabupaten']) }}, Provinsi  {{ ucwords($desa['nama_propinsi']) }}"
    />
@endif
<meta property='og:url' content="{{ current_url() }}" />
<link rel="shortcut icon" href="{{ favico_desa() }}" />
<noscript>You must have JavaScript enabled in order to use this theme. Please enable JavaScript and then reload this page in order to continue.</noscript>
@if (cek_koneksi_internet())
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
@endif
<script>
    var BASE_URL = '{{ base_url() }}';
    var SITE_URL = '{{ site_url() }}';
    var setting = @json(setting());
    var config = @json(identitas());
</script>
