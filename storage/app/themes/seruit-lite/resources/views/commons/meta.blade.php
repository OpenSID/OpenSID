@php
$nama_desa_lengkap = ucwords(setting('sebutan_desa') . ' ' . $desa['nama_desa']);
$current_url = current_url();
$site_url = site_url();
$is_artikel = isset($single_artikel);
$logo_url = gambar_desa($desa['logo'], true);
$default_title_dynamic = preg_replace('/[^A-Za-z0-9- ]/', '', trim(str_replace('-', ' ', get_dynamic_title_page_from_path())));
$default_suffix = ' - ' . $nama_desa_lengkap;
$default_title = $default_title_dynamic ? $default_title_dynamic . ' - ' . $nama_desa_lengkap : $nama_desa_lengkap;

if ($is_artikel) {
    $meta_title = e($single_artikel['judul']) . ' - ' . $nama_desa_lengkap;
    $meta_description = e(potong_teks(strip_tags($single_artikel['isi']), 160));
    $og_type = 'article';
    $og_image = ($single_artikel['gambar'] && is_file(LOKASI_FOTO_ARTIKEL . 'sedang_' . $single_artikel['gambar'])) ? AmbilFotoArtikel($single_artikel['gambar'], 'sedang') : $logo_url;
} else {
    $meta_title = e($default_title);
    $meta_description = e("Situs web resmi " . $nama_desa_lengkap . ". " . setting('website_title'));
    $og_type = 'website';
    $og_image = $logo_url;
}
@endphp

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="google" content="notranslate" />
<meta name="theme" content="Seruit Lite" />
<meta name="author" content="Mariyadi (Updesa)" />
<meta name="designer" content="Updesa - https://updesa.com/" />
<meta name="theme:version" content="{{ $themeVersion ?? '3.0.0' }}" />
<meta name="theme-color" content="{{ $theme_color }}" />
<title>{{ $meta_title }}</title>
<meta name="description" content="{{ $meta_description }}" />
<link rel="canonical" href="{{ $current_url }}" />
<link rel="author" href="https://updesa.com/" />
<meta property="og:title" content="{{ $meta_title }}" />
<meta property="og:description" content="{{ $meta_description }}" />
<meta property="og:image" content="{{ $og_image }}" />
<meta property="og:image:secure_url" content="{{ $og_image }}" />
<meta property="og:type" content="{{ $og_type }}" />
<meta property="og:url" content="{{ $current_url }}" />
<meta property="og:site_name" content="{{ $nama_desa_lengkap }}" />
<meta property="og:locale" content="id_ID" />
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $meta_title }}">
<meta name="twitter:description" content="{{ $meta_description }}">
<meta name="twitter:image" content="{{ $og_image }}">
@if($is_artikel)
<meta property="article:published_time" content="{{ date('c', strtotime($single_artikel['tgl_upload'])) }}">
<meta property="article:modified_time" content="{{ date('c', strtotime($single_artikel['updated_at'])) }}">
<meta property="article:author" content="{{ e($single_artikel['owner']) }}">
@endif
<link rel="shortcut icon" href="{{ favico_desa() }}" />
<link rel="alternate" type="application/rss+xml" title="Feed {{ $nama_desa_lengkap }}" href="{{ site_url('sitemap') }}" />