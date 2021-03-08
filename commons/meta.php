<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<meta http-equiv="encoding" content="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<meta name='viewport' content='width=device-width, initial-scale=1' />
<meta name='google' content='notranslate' />
<meta name='theme' content='Natra' />
<meta name='designer' content='Ariandi Ryan Kahfi, S.Pd.' />
<meta name='theme:designer' content='Ariandi Ryan Kahfi, S.Pd.' />
<meta name='theme:version' content='4.4' />
<meta name="keywords" content="<?= $this->setting->website_title.' '.ucwords($this->setting->sebutan_desa).' '.$desa['nama_desa'].' '.ucwords($this->setting->sebutan_kecamatan).' '.$desa['nama_kecamatan'].' '.ucwords($this->setting->sebutan_kabupaten).' '.$desa['nama_kabupaten'];?>" />
<meta property="og:site_name" content="<?= ucwords($this->setting->sebutan_desa).' '.$desa['nama_desa'].' '.ucwords($this->setting->sebutan_kecamatan).' '.$desa['nama_kecamatan'].' '.ucwords($this->setting->sebutan_kabupaten).' '.$desa['nama_kabupaten'];?>"/>
<meta property="og:type" content="article"/>
<meta property="fb:app_id" content="147912828718">
<title>
<?php if ($single_artikel["judul"] == ""): ?>
	<?= $this->setting->website_title.' '.ucwords($this->setting->sebutan_desa).' '.$desa['nama_desa'].' '.ucwords($this->setting->sebutan_kecamatan).' '.$desa['nama_kecamatan'].' '.ucwords($this->setting->sebutan_kabupaten).' '.$desa['nama_kabupaten']; ?>
<?php else: ?>
	<?= $single_artikel["judul"].' - '.ucwords($this->setting->sebutan_desa).(($desa['nama_desa'])?' '.$desa['nama_desa']:''); ?>
<?php endif; ?>
</title>

<?php if(is_file(LOKASI_LOGO_DESA . "favicon.ico")): ?>
<link rel="shortcut icon" href="<?= base_url()?><?= LOKASI_LOGO_DESA?>favicon.ico" />
<?php else:?>
<link rel="shortcut icon" href="<?= base_url()?>favicon.ico" />
<?php endif;?>
<link rel="stylesheet" type="text/css" href="<?= base_url("$this->theme_folder/$this->theme/assets/css/bootstrap.min.css"); ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url("$this->theme_folder/$this->theme/assets/css/font-awesome.min.css"); ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url("$this->theme_folder/$this->theme/assets/css/animate.css"); ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url("$this->theme_folder/$this->theme/assets/css/slick.css"); ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url("$this->theme_folder/$this->theme/assets/css/theme.css"); ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url("$this->theme_folder/$this->theme/assets/css/style.css"); ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url("$this->theme_folder/$this->theme/assets/css/custom.css"); ?>">

<link type='text/css' href="<?= base_url()?>assets/css/font-awesome.min.css" rel='stylesheet' />
<link rel="stylesheet" href="<?= base_url()?>assets/css/leaflet.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<link rel="stylesheet" href="<?= base_url()?>assets/css/mapbox-gl.css" />
<link rel="stylesheet" href="<?= base_url()?>assets/css/peta.css">

<?php if(isset($single_artikel)): ?>
<meta property="og:title" content="<?= htmlspecialchars($single_artikel["judul"]);?>"/>
<meta property="og:url" content="<?= site_url('artikel/'.buat_slug($single_artikel))?>"/>
<meta property="og:image" content="<?= base_url()?><?= LOKASI_FOTO_ARTIKEL?>sedang_<?= $single_artikel['gambar'];?>"/>
<meta property="og:description" content="<?= potong_teks($single_artikel['isi'], 300)?> ..."/>
<?php else: ?>
<meta property="og:title" content="<?= ucwords($this->setting->sebutan_desa).' '.$desa['nama_desa'].' '
	.ucwords($this->setting->sebutan_kecamatan).' '.$desa['nama_kecamatan'].' '
	.ucwords($this->setting->sebutan_kabupaten).' '.$desa['nama_kabupaten'];?>"/>
<meta property="og:url" content="<?= site_url()?>"/>
<meta property="og:description" content="<?= $this->setting->website_title.' '
	.ucwords($this->setting->sebutan_desa).' '.$desa['nama_desa'].' '
	.ucwords($this->setting->sebutan_kecamatan).' '.$desa['nama_kecamatan'].' '
	.ucwords($this->setting->sebutan_kabupaten).' '.$desa['nama_kabupaten'];?>"/>
<?php endif; ?>
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ if (window.scrollY == 0) window.scrollTo(0,1); } </script>
<script language='javascript' src="<?= base_url('assets/front/js/jquery.min.js'); ?>"></script>
<script language='javascript' src="<?= base_url('assets/front/js/jquery.cycle2.min.js') ?>"></script>
<script language='javascript' src="<?= base_url('assets/front/js/jquery.cycle2.carousel.js') ?>"></script>
<script src="<?= base_url("$this->theme_folder/$this->theme/assets/js/bootstrap.min.js") ?>"></script>
<script src="<?= base_url()?>assets/js/leaflet.js"></script>
<script src="<?= base_url()?>assets/front/js/layout.js"></script>
<script src="<?= base_url()?>assets/front/js/jquery.colorbox.js"></script>
<script src="<?= base_url()?>assets/js/leaflet-providers.js"></script>
<script src="<?= base_url()?>assets/js/highcharts/highcharts.js"></script>
<script src="<?= base_url()?>assets/js/highcharts/highcharts-3d.js"></script>
<script src="<?= base_url()?>assets/js/highcharts/exporting.js"></script>
<script src="<?= base_url()?>assets/js/highcharts/highcharts-more.js"></script>
<script src="<?= base_url()?>assets/js/highcharts/sankey.js"></script>
<script src="<?= base_url()?>assets/js/highcharts/organization.js"></script>
<script src="<?= base_url()?>assets/js/highcharts/accessibility.js"></script>
<script src="<?= base_url()?>assets/js/mapbox-gl.js"></script>
<script src="<?= base_url()?>assets/js/leaflet-mapbox-gl.js"></script>
<script src="<?= base_url()?>assets/js/peta.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/bootstrap/css/dataTables.bootstrap.min.css">
<script src="<?= base_url() ?>assets/bootstrap/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>assets/bootstrap/js/dataTables.bootstrap.min.js"></script>
<?php $this->load->view('global/validasi_form', ['web_ui' => true]); ?>
<script type="text/javascript">
	var BASE_URL = "<?= base_url(); ?>";
</script>
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<script type="text/javascript">
	window.setTimeout("renderDate()",1);
	days = new Array("Minggu","Senin","Selasa","Rabu","Kamis","Jum'at","Sabtu");
	months = new Array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
	function renderDate()
	{
		var mydate = new Date();
		var year = mydate.getYear();
		if (year < 2000)
		{
			if (document.all)
				year = "19" + year;
			else
				year += 1900;
		}
		var day = mydate.getDay();
		var month = mydate.getMonth();
		var daym = mydate.getDate();
		if (daym < 10)
			daym = "0" + daym;
		var hours = mydate.getHours();
		var minutes = mydate.getMinutes();
		var seconds = mydate.getSeconds();
		if (hours <= 9)
			hours = "0" + hours;
		if (minutes <= 9)
			minutes = "0" + minutes;
		if (seconds <= 9)
			seconds = "0" + seconds;
		document.getElementById("jam").innerHTML = "<B>"+days[day]+", "+daym+" "+months[month]+" "+year+"</B><br>"+hours+" : "+minutes+" : "+seconds;
		setTimeout("renderDate()",1000)
	}
</script>
<style type="text/css">
	#jam{
		text-align:center;
		color: #ffffff;
	}
</style>
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/id_ID/sdk.js#xfbml=1&version=v3.2&appId=147912828718&autoLogAppEvents=1"></script>

<!--[if lt IE 9]>
<script src="<?= base_url("$this->theme_folder/$this->theme/assets/js/html5shiv.min.js"); ?>"></script>
<script src="<?= base_url("$this->theme_folder/$this->theme/assets/js/respond.min.js"); ?>"></script>
<![endif]-->
<?php $this->load->view('head_tags_front') ?>
