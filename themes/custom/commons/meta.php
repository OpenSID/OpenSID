<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $desa_title = trim(ucwords($this->setting->sebutan_desa) . ' ' . $desa['nama_desa']); ?>

<meta content="utf-8" http-equiv="encoding">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name='viewport' content='width=device-width, initial-scale=1' />
<meta name='google' content='notranslate' />
<meta name='theme' content='cosmo' />
<meta name='designer' content='Diki Siswanto'/>
<meta name='theme:designer' content='Diki Siswanto' />
<meta name='theme:version' content='2.0-beta' />
<meta name="theme-color" content="#2E86AB">
<meta name='keywords' content="sid, sistem informasi desa, web, blog, informasi, website, tema cosmo, desa, kecamatan, kabupaten, indonesia, kampung, <?= $desa['nama_desa']; ?>, <?= $desa['nama_kecamatan']; ?>, <?= $desa['nama_kabupaten']; ?>, website resmi <?= $desa['nama_desa']; ?>" />
<meta property="og:site_name" content="<?= $desa_title ?>"/>
<meta property="og:type" content="article"/>

<?php if(isset($single_artikel)): ?>
	<title><?= $single_artikel["judul"] . " - $desa_title" ?></title>
	<meta name='description' content="<?= str_replace('"', "'", substr(strip_tags($single_artikel['isi']), 0, 400)); ?>" />
	<meta property="og:title" content="<?= $single_artikel["judul"];?>"/>
	<?php if (trim($single_artikel['gambar'])!=''): ?>
	<meta property="og:image" content="<?= base_url()?><?= LOKASI_FOTO_ARTIKEL?>sedang_<?= $single_artikel['gambar'];?>"/>
	<?php endif; ?>
	<meta property='og:description' content="<?= str_replace('"', "'", substr(strip_tags($single_artikel['isi']), 0, 400)); ?>" />
<?php else: ?>
	<title><?php $tmp = ltrim(get_dynamic_title_page_from_path(), ' -'); echo (trim($tmp)=='') ? $desa_title : "$tmp - $desa_title"; ?></title>
	<meta name='description' content="<?= $this->setting->website_title . ' ' . $desa_title; ?>" />
	<meta property="og:title" content="<?= $desa_title;?>"/>
	<meta property='og:description' content="<?= $this->setting->website_title . ' ' . $desa_title; ?>" />
<?php endif; ?>
<meta property='og:url' content="<?= current_url(); ?>" />
<?php if(is_file(LOKASI_LOGO_DESA . "favicon.ico")): ?>
<link rel="shortcut icon" href="<?= base_url() . LOKASI_LOGO_DESA?>favicon.ico" />
<?php else: ?>
<link rel="shortcut icon" href="<?= base_url('favicon.ico')?>" />
<?php endif; ?>
<noscript>
	You must have JavaScript enabled in order to use this theme. Please enable JavaScript and then reload this page in order to continue.
</noscript>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.6.0/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-providers/1.6.0/leaflet-providers.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mapbox-gl/1.11.1/mapbox-gl.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mapbox-gl-leaflet/0.0.12/leaflet-mapbox-gl.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.cycle2/2.1.6/jquery.cycle2.min.js"></script>
<script src="<?= base_url('assets/js/peta.js') ?>"></script>
<script>const BASE_URL = '<?= base_url() ?>';	</script>