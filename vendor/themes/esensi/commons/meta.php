<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php $is_premium = preg_match('/premium/', ambilVersi()) ?>
<?php $nama_desa = ucwords($this->setting->sebutan_desa) .' '.ucwords($desa['nama_desa']) ?>

<?php defined('THEME_VERSION') or define('THEME_VERSION', 'v2404.0.0') ?>
<?php defined('IS_PREMIUM') or define('IS_PREMIUM', $is_premium) ?>
<?php defined('NAMA_DESA') or define('NAMA_DESA', $nama_desa) ?>

<?php $title = preg_replace("/[^A-Za-z0-9- ]/", '', trim(str_replace('-', ' ', get_dynamic_title_page_from_path())));
      $suffix = $this->setting->website_title
					. ' ' . ucwords($this->setting->sebutan_desa)
					. (($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : '');
      $desa_title = $title ?  $title.' - '.$suffix : $suffix ?>

<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name='viewport' content='width=device-width, initial-scale=1' />
<meta name='google' content='notranslate' />
<meta name='theme' content='Esensi' />
<meta name='designer' content='Diki Siswanto' />
<meta name='theme:designer' content='Diki Siswanto' />
<meta name='theme:version' content='<?= THEME_VERSION ?>' />
<meta name="theme-color" content="#efefef">
<meta name='keywords' content="<?= $desa_title ?> <?php !strpos($desa_title, NAMA_DESA) and print(NAMA_DESA) ?> <?= ucfirst($this->setting->sebutan_kecamatan) ?> <?= ucwords($desa['nama_kecamatan']) ?>, <?= ucfirst($this->setting->sebutan_kabupaten) ?> <?= ucwords($desa['nama_kabupaten']) ?>, Provinsi  <?= ucwords($desa['nama_propinsi']) ?>" />
<meta property="og:site_name" content="<?= NAMA_DESA ?>"/>
<meta property="og:type" content="article"/>
<link rel="canonical" href="<?= site_url() ?>"/>
<meta name='robots' content='index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1'/>
<meta name="subject" content="Situs Web Desa">
<meta name="copyright" content="<?= NAMA_DESA ?>">
<meta name="language" content="Indonesia">
<meta name="revised" content="Sunday, July 18th, 2010, 5:15 pm"/>
<meta name="Classification" content="Government">
<meta name="url" content="<?= site_url() ?>">
<meta name="identifier-URL" content="<?= site_url() ?>">
<meta name="category" content="Desa, Pemerintahan">
<meta name="coverage" content="Worldwide">
<meta name="distribution" content="Global">
<meta name="rating" content="General">
<meta name="revisit-after" content="7 days">
<meta name="revisit-after" content="7"/>
<meta name="webcrawlers" content="all"/>
<meta name="rating" content="general"/>
<meta name="spiders" content="all"/>
<link rel="alternate" type="application/rss+xml" title="Feed <?= NAMA_DESA ?>" href="<?= site_url('sitemap') ?>"/>  

<?php if(isset($single_artikel)): ?>
  <title><?= $single_artikel["judul"] . " - " . NAMA_DESA ?></title>
  <meta name='description' content="<?= str_replace('"', "'", substr(strip_tags($single_artikel['isi']), 0, 150)); ?>" />
  <meta property="og:title" content="<?= $single_artikel["judul"];?>"/>
  <meta itemprop="name" content="<?= $single_artikel["judul"];?>"/>
  <meta itemprop='description' content="<?= str_replace('"', "'", substr(strip_tags($single_artikel['isi']), 0, 150)); ?>" />
  <?php if (trim($single_artikel['gambar'])!=''): ?>
    <meta property="og:image" content="<?= base_url(LOKASI_FOTO_ARTIKEL . 'sedang_' .$single_artikel['gambar']) ?>"/>
    <meta itemprop="image" content="<?= base_url(LOKASI_FOTO_ARTIKEL . 'sedang_' . $single_artikel['gambar']) ?>"/>
  <?php endif; ?>
  <meta property='og:description' content="<?= str_replace('"', "'", substr(strip_tags($single_artikel['isi']), 0, 150)); ?>" />
<?php else: ?>
  <title><?= $desa_title ?></title>
  <meta name='description' content="<?= $desa_title ?> <?php !strpos($desa_title, NAMA_DESA) and print(NAMA_DESA) ?> <?= ucfirst($this->setting->sebutan_kecamatan) ?> <?= ucwords($desa['nama_kecamatan']) ?>, <?= ucfirst($this->setting->sebutan_kabupaten) ?> <?= ucwords($desa['nama_kabupaten']) ?>, Provinsi  <?= ucwords($desa['nama_propinsi']) ?>" />
  <meta itemprop="name" content="<?= $desa_title ?>"/>
  <meta property="og:title" content="<?= $desa_title ?>"/>
  <meta property='og:description' content="<?= $desa_title ?><?php !strpos($desa_title, NAMA_DESA) and print(NAMA_DESA) ?> <?= ucfirst($this->setting->sebutan_kecamatan) ?> <?= ucwords($desa['nama_kecamatan']) ?>, <?= ucfirst($this->setting->sebutan_kabupaten) ?> <?= ucwords($desa['nama_kabupaten']) ?>, Provinsi  <?= ucwords($desa['nama_propinsi']) ?>" />
<?php endif; ?>
<meta property='og:url' content="<?= current_url(); ?>" />
<link rel="shortcut icon" href="<?= favico_desa() ?>"/>
<noscript>You must have JavaScript enabled in order to use this theme. Please enable JavaScript and then reload this page in order to continue.</noscript>
<?php if (cek_koneksi_internet()): ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<?php endif ?>
<script src="<?= asset('js/highcharts/highcharts.js'); ?>"></script>
<script src="<?= asset('js/highcharts/highcharts-3d.js'); ?>"></script>
<script src="<?= asset('js/highcharts/exporting.js'); ?>"></script>
<script src="<?= asset('js/highcharts/highcharts-more.js'); ?>"></script>
<script src="<?= asset('js/highcharts/sankey.js'); ?>"></script>
<script src="<?= asset('js/highcharts/organization.js'); ?>"></script>
<script src="<?= asset('js/highcharts/accessibility.js'); ?>"></script>
<?php if (cek_koneksi_internet()): ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.1.0/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-providers/1.6.0/leaflet-providers.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mapbox-gl/1.11.1/mapbox-gl.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mapbox-gl-leaflet/0.0.14/leaflet-mapbox-gl.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.cycle2/2.1.6/jquery.cycle2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.cycle2/2.1.6/jquery.cycle2.carousel.js"></script>
<?php endif ?>
<script src="<?= asset('js/peta.js') ?>"></script>
<script>
  var BASE_URL = '<?= base_url() ?>';
</script>
