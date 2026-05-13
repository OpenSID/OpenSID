@php 
  defined('NAMA_DESA') || define('NAMA_DESA', $nama_desa);
  defined('THEME_NAME') || define('THEME_NAME', 'Palanta');
  defined('THEME_VERSION') || define('THEME_VERSION', 'v2509.0.0');
  $nama_desa = ucwords($setting->sebutan_desa) .' '.ucwords($desa['nama_desa']);
  $title = preg_replace("/[^A-Za-z0-9- ]/", '', trim(str_replace('-', ' ', get_dynamic_title_page_from_path())));
  $suffix = setting('website_title')
        . ' ' . ucwords(setting('sebutan_desa'))
        . (($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : '');
  $desa_title = $title ?  $title.' - '.$suffix : $suffix;  
@endphp

<meta http-equiv="encoding" content="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name='viewport' content='width=device-width, initial-scale=1' />
<meta name='google' content='notranslate' />
<meta name='theme' content='Palanta' />
<meta name='designer' content='Dafris' />
<meta name='theme:designer' content='Dafris' />
<meta name='theme:version' content='{{ THEME_VERSION }}' />
<meta name="keywords" content="{{ $setting->website_title . ' '.  $desa_title; }}"/>
<meta property="og:site_name" content="{{  $desa_title }}"/>
<meta property="og:type" content="article"/>
<meta property="fb:app_id" content="147912828718">
<link rel="shortcut icon" href="{{ favico_desa() }}"/>
@if(isset($single_artikel))
  <title>{{ $single_artikel["judul"] . " - " . $nama_desa }}</title>
  <meta name='description' content="{{ str_replace('"', "'", substr(strip_tags($single_artikel['isi']), 0, 150)) }}" />
  <meta property="og:title" content="{{ $single_artikel['judul'] }}"/>
  <meta itemprop="name" content="{{ $single_artikel['judul'] }}"/>
  <meta itemprop='description' content="{{ str_replace('"', "'", substr(strip_tags($single_artikel['isi']), 0, 150)) }}" />
  @if (trim($single_artikel['gambar']) != '')
    <meta property="og:image" content="{{ base_url(LOKASI_FOTO_ARTIKEL . 'sedang_' . $single_artikel['gambar']) }}"/>
    <meta itemprop="image" content="{{ base_url(LOKASI_FOTO_ARTIKEL . 'sedang_' . $single_artikel['gambar']) }}"/>
  @endif
  <meta property='og:description' content="{{ str_replace('"', "'", substr(strip_tags($single_artikel['isi']), 0, 150)) }}" />
@else
  <title>{{ $desa_title }}</title>
  <meta name='description' content="{{ $desa_title }} @if(!strpos($desa_title, $nama_desa)) {{ $nama_desa }} @endif {{ ucfirst(setting('sebutan_kecamatan')) }} {{ ucwords($desa['nama_kecamatan']) }}, {{ ucfirst(setting('sebutan_kabupaten')) }} {{ ucwords($desa['nama_kabupaten']) }}, Provinsi  {{ ucwords($desa['nama_propinsi']) }}" />
  <meta itemprop="name" content="{{ $desa_title }}"/>
  <meta property="og:title" content="{{ $desa_title }}"/>
  <meta property='og:description' content="{{ $desa_title }} @if(!strpos($desa_title, $nama_desa)) {{ $nama_desa }} @endif {{ ucfirst(setting('sebutan_kecamatan')) }} {{ ucwords($desa['nama_kecamatan']) }}, {{ ucfirst(setting('sebutan_kabupaten')) }} {{ ucwords($desa['nama_kabupaten']) }}, Provinsi  {{ ucwords($desa['nama_propinsi']) }}" />
@endif
