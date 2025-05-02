<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php defined('THEME_NAME') or define('THEME_NAME', 'DeNava') ?>
<?php
function cekVersiMinimal($versiMinimal) {
    $versi = preg_replace("/[^0-9]/", "", ambilVersi());
    return $versi >= $versiMinimal;
}

function cekVersiMaksimal($versiMaksimal) {
    $versi = preg_replace("/[^0-9]/", "", ambilVersi());
    return $versi <= $versiMaksimal;
}
?>
<?php $this->load->view($folder_themes .'/commons/meta') ?>

<?php if (config_item('ip_address') === $this->input->ip_address() && in_array($this->uri->segment(1), ['']) && is_file(FCPATH . "$this->theme_folder/$this->theme/commons/display.php")) : ?>
<?php $this->load->view($folder_themes .'/commons/display') ?>
<?php else : ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php
  	function cekKehadiran($cekKeyHadir, $cekKethadir) {
        return theme_config($cekKeyHadir, $cekKethadir);
    }

    function cekKondisiColor() {
        return (theme_config('color') == 'sunrise');
    }

    function extract_youtube_id($url) {
        $pattern = '/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
        return '';
    }
  	
	function getLastLoginStatus($db, $pamongId) {
	  $lastLogin = $db->query("SELECT * FROM user WHERE pamong_id = ?", array($pamongId))->row_array();
	  if ($lastLogin && $lastLogin['active'] == '1' && $lastLogin['last_login'] !== NULL) {
		return 'Login Terakhir:<br>' . tgl_indo2(date($lastLogin['last_login']));
	  }
	  return '';
	}
	function getPamongData($db, $pamongId) {
		return $db->query("SELECT * FROM tweb_desa_pamong WHERE pamong_id = " . $pamongId)->row_array();
	}

	function getUpcomingEvent($file, $customTime) {
	$json = file_get_contents($file);
	$array = json_decode($json, true);
	$currentDate = time();
	$index = null;
	$nextEvent = null;

	foreach ($array as $date => $event) {
		$countDownDate = strtotime($date . ' ' . $customTime);
		if ($countDownDate > $currentDate && ($index === null || $countDownDate < $index)) {
		$index = $countDownDate;
		$nextEvent = $event;
		}
	}

	return ['index' => $index, 'nextEvent' => $nextEvent];
	}
	
	function getRandomDoa() {
		$base_url = "https://open-api.my.id/api/doa/";
		$response = file_get_contents($base_url);
		$doas = json_decode($response, true);
		$total_doas = count($doas ?? []);
		$random_id = rand(1, $total_doas);
		$random_doa_url = $base_url . $random_id;
		$response = file_get_contents($random_doa_url);
		$datadoa = json_decode($response, true);
		$datadoa['total_doas'] = $total_doas;
		return $datadoa;
	}

	function hitungJumlahData($db, $startDate, $endDate) {
		return $db->query("SELECT COUNT(*) as jumlah_data 
						FROM log_surat 
						WHERE status = '1' 
						AND deleted_at IS NULL 
						AND DATE(tanggal) BETWEEN '$startDate' AND '$endDate'")->row()->jumlah_data ?: 0;
	}

	function fetchEventData($db, $startDate, $endDate, $kodePeristiwa) {
		$query = "SELECT COUNT(*) as jumlah_data,
		GROUP_CONCAT(tp.nama SEPARATOR '#') as nama_penduduk,
		GROUP_CONCAT(DATE_FORMAT(tp.tanggallahir, '%Y-%m-%d')) as tanggal_lahir,
		GROUP_CONCAT(DATE_FORMAT(lp.tgl_peristiwa, '%Y-%m-%d')) as tanggal_peristiwa,
		GROUP_CONCAT(DATE_FORMAT(lp.tgl_lapor, '%Y-%m-%d')) as tanggal_lapor,
		GROUP_CONCAT(TIMESTAMPDIFF(YEAR, tp.tanggallahir, lp.tgl_peristiwa)) as umur,
		GROUP_CONCAT(tp.nama_ayah SEPARATOR '#') as nama_ayah,
		GROUP_CONCAT(tp.sex SEPARATOR '#') as sex,
		GROUP_CONCAT(cw.dusun SEPARATOR '#') as dusun,
		GROUP_CONCAT(cw.rt SEPARATOR '#') as rt,
		GROUP_CONCAT(cw.rw SEPARATOR '#') as rw,
		GROUP_CONCAT(lp.catatan SEPARATOR '#') as catatan,
		GROUP_CONCAT(pa.nama SEPARATOR '#') as agama,
		GROUP_CONCAT(tp.foto SEPARATOR '#') as foto,
		GROUP_CONCAT(lp.akta_mati SEPARATOR '#') as akta_mati,
		GROUP_CONCAT(tp.id_cluster SEPARATOR '#') as id_cluster
			FROM log_penduduk lp
			JOIN tweb_penduduk tp ON lp.id_pend = tp.id
			JOIN tweb_wil_clusterdesa cw ON tp.id_cluster = cw.id
			JOIN tweb_penduduk_agama pa ON tp.agama_id = pa.id
			WHERE lp.kode_peristiwa = ? 
			AND lp.tgl_peristiwa BETWEEN ? AND ?
			ORDER BY lp.tgl_peristiwa DESC";
		$result = $db->query($query, [$kodePeristiwa, $startDate, $endDate])->row();
		if ($result) {
			return $result;
		}
		return null;
	}

	function fsize($file){
		$a = array("B", "KB", "MB", "GB", "TB", "PB");
		$pos = 0;
		$size = filesize($file);
		while ($size >= 1024)
		{
			$size /= 1024;
			$pos++;
		}
		return round ($size,2)." ".$a[$pos];
	}

	function hr ($tgl) {
		$daftar_hari = array('Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu');
		$namahari = date('l', strtotime($tgl));
		$hari = $daftar_hari[$namahari].", ".tgl_indo2($tgl);
		return $hari;
	}	

	$CI =& get_instance();
	$CI->load->library('user_agent');
	$ip = $CI->input->ip_address();
	if(!isset($_SESSION['MemberOnline'])) {
		$cek = $this->db->query("SELECT Tanggal,ipAddress FROM sys_traffic WHERE Tanggal='".date("Y-m-d")."'");
		if($cek->num_rows()==0)
		{
			$up = $this->db->query("INSERT INTO sys_traffic (Tanggal,ipAddress,Jumlah) VALUES ('".date("Y-m-d")."','".$ip."','1')");
				$_SESSION['MemberOnline']=date('Y-m-d H:i:s');
		}
		else
		{
			$res = $cek->result_array();
			$ipaddr = $res['ipAddress'].$ip;
			$up = $this->db->query("UPDATE sys_traffic SET Jumlah=Jumlah + 1,ipAddress='".$ipaddr."' WHERE Tanggal='".date("Y-m-d")."'");
			$_SESSION['MemberOnline']=date('Y-m-d H:i:s');
		}
	}
	?>
	<?php $desa_title = $this->setting->website_title.' '.trim(ucwords($this->setting->sebutan_desa) . ' ' . $desa['nama_desa']); ?>
	<?php $desa_nama = trim(ucwords($this->setting->sebutan_desa) . ' ' . $desa['nama_desa']); ?>
	<?php $desa_wilayah = ucwords($this->setting->sebutan_kecamatan).' '.$desa['nama_kecamatan'].' '.ucwords($this->setting->sebutan_kabupaten).' '.$desa['nama_kabupaten'].' Provinsi '.$desa['nama_propinsi']; ?>
	<meta content="utf-8" http-equiv="encoding">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name='viewport' content='width=device-width, initial-scale=1' />
	<meta name='google' content='notranslate' />
	<meta name='theme' content='<?= THEME_NAME ?>' />
	<meta name='designer' content='Ariandi Ryan Kahfi, S.Pd.' />
	<meta name='theme:designer' content='Ariandi Ryan Kahfi, S.Pd.' />
	<meta name='theme:version' content='<?= THEME_VERSION ?>' />
	<meta name="keywords" content="<?= $desa_title.' '.ucwords($this->setting->sebutan_kecamatan).' '.$desa['nama_kecamatan'].' '.ucwords($this->setting->sebutan_kabupaten).' '.$desa['nama_kabupaten'];?>" />
	<meta property="og:site_name" content="<?= $desa_title ?>"/>
	<meta property="og:type" content="article"/>
	<meta property="fb:app_id" content="<?= config_item('fbappid') ? config_item('fbappid') : '147912828718'; ?>"/>
	<meta property="fb:admins" content="<?= config_item('fbadmin') ? config_item('fbadmin') : '1117950751'; ?>"/>
	<meta property="og:image:width" content="400" />
	<meta property="og:image:height" content="225" />
	<?php if(isset($single_artikel)): ?>
		<?php if(isset($single_artikel["judul"])): ?>
			<title><?= htmlspecialchars_decode($single_artikel["judul"]) . " - $desa_title" ?></title>
			<meta name='description' content="<?= str_replace('"', "'", substr(strip_tags($single_artikel['isi']), 0, 400)); ?>" />
			<meta property='og:url' content="<?= site_url('artikel/'.buat_slug($single_artikel))?>" />
			<meta property="og:title" content="<?= htmlspecialchars_decode($single_artikel["judul"]); ?>"/>
			<meta property='og:description' content="<?= str_replace('"', "'", substr(strip_tags($single_artikel['isi']), 0, 400)); ?>" />
		<?php else: ?>
			<title><?= "404 - " . trim(ucwords($this->setting->sebutan_desa) . ' ' . $desa['nama_desa']); ?></title>
			<meta name='description' content="<?= $desa_title. ' '.ucwords($this->setting->sebutan_kecamatan).' '.$desa['nama_kecamatan'].' '.ucwords($this->setting->sebutan_kabupaten).' '.$desa['nama_kabupaten'];?>" />
			<meta property="og:title" content="<?= "404 - " . trim(ucwords($this->setting->sebutan_desa) . ' ' . $desa['nama_desa']); ?>"/>
		<?php endif; ?>
	<?php elseif (in_array($this->uri->segment(1), ['pembangunan']) && !in_array($this->uri->segment(2), [''])) : ?>
		<title><?= ucwords(str_replace('-', ' ', $this->uri->segment(1)))." - ".htmlspecialchars_decode($pembangunan->judul)." - ".$desa_nama; ?></title>
		<meta name='description' content="<?= $pembangunan->keterangan; ?> - <?= htmlspecialchars_decode($pembangunan->manfaat) ?>" />
		<meta property="og:url" content="<?= current_url()?>"/>
		<meta property="og:title" content="<?= htmlspecialchars_decode($pembangunan->judul)." - ".$desa_nama; ?>"/>
		<meta property='og:description' content="<?= $pembangunan->keterangan; ?> - <?= htmlspecialchars_decode($pembangunan->manfaat) ?>" />
	<?php elseif (in_array($this->uri->segment(1), ['data-statistik', 'data-wilayah', 'data-vaksinasi']) || in_array($this->uri->segment(2), ['dpt', 'statistik'])) : ?>
		<title><?= "Data ".$heading." - ".$desa_nama; ?></title>
		<meta name='description' content="<?= "Data ".$heading ?> - <?= $desa_nama.' '.$desa_wilayah;?>" />
		<meta property="og:url" content="<?= current_url()?>"/>
		<meta property="og:title" content="<?= "Data ".$heading." - ".$desa_nama ?>"/>
		<meta property='og:description' content="<?= "Data ".$heading ?> - <?= $desa_nama.' '.$desa_wilayah;?>" />
	<?php elseif (in_array($this->uri->segment(1), ['status-idm'])) : ?>
		<title>Status IDM <?= $idm->SUMMARIES->TAHUN." - ".$desa_nama; ?></title>
		<meta name='description' content="Status IDM <?= $idm->SUMMARIES->TAHUN.' '.$desa_nama. ' '.$desa_wilayah;?>" />
		<meta property="og:url" content="<?= current_url()?>"/>
		<meta property="og:title" content="Status IDM <?= $desa_nama; ?> : <?= $idm->SUMMARIES->STATUS ?>"/>
		<meta property='og:description' content="Skor IDM <?= number_format($idm->SUMMARIES->SKOR_SAAT_INI, 4) ?> - <?= $desa_nama.' '.$desa_wilayah;?>" />
	<?php elseif (!in_array($this->uri->segment(1), [''])) : ?>
		<title><?= ucwords(str_replace('-', ' ', $this->uri->segment(1)))." - ".$desa_nama; ?></title>
		<meta name='description' content="<?= $desa_title. ' '.$desa_wilayah;?>" />
		<meta property="og:url" content="<?= current_url()?>"/>
		<meta property="og:title" content="<?= ucwords(str_replace('-', ' ', $this->uri->segment(1)))." - ".$desa_nama; ?>"/>
		<meta property='og:description' content="<?= $desa_title. ' '.$desa_wilayah;?>" />
	<?php else: ?>
		<title><?php $tmp = ltrim(get_dynamic_title_page_from_path(), ' -'); echo (trim($tmp)=='') ? $desa_title : "$tmp - $desa_title"; ?></title>
		<meta name='description' content="<?= $desa_title. ' '.$desa_wilayah;?>" />
		<meta property="og:url" content="<?= current_url()?>"/>
		<meta property="og:title" content="<?php $tmp = ltrim(get_dynamic_title_page_from_path(), ' -'); echo (trim($tmp)=='') ? $desa_nama : "$tmp - $desa_nama"; ?>"/>
		<meta property='og:description' content="<?= $desa_title. ' '.$desa_wilayah;?>" />
	<?php endif; ?>
	<?php if (trim($single_artikel['gambar'])!=''): ?>
		<meta property="og:image" content="<?= base_url()?><?= LOKASI_FOTO_ARTIKEL?>sedang_<?= $single_artikel['gambar'];?>"/>
	<?php elseif (in_array($this->uri->segment(1), ['pembangunan']) && !in_array($this->uri->segment(2), [''])) : ?>
		<meta property="og:image" content="<?= base_url() . LOKASI_GALERI . $pembangunan->foto; ?>"/>
	<?php else: ?>
		<meta property="og:image" content="<?= gambar_desa($desa['kantor_desa'], TRUE)?>"/>
	<?php endif; ?>
	<link rel="canonical" href="<?= site_url() ?>"/>
	<meta name='robots' content='index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1'/>
	<meta name="subject" content="<?= $desa_title. ' '.$desa_wilayah; ?>">
	<meta name="copyright" content="<?= $desa_title ?>">
	<meta name="language" content="Indonesia">
	<meta name="Classification" content="Government">
	<meta name="url" content="<?= site_url() ?>">
	<meta name="identifier-URL" content="<?= site_url() ?>">
	<meta name="category" content="<?= $desa_title ?>">
	<meta name="coverage" content="Worldwide">
	<meta name="distribution" content="Global">
	<meta name="rating" content="General">
	<meta http-equiv="Expires" content="0">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="imagetoolbar" content="no"/>
	<meta name="webcrawlers" content="all"/>
	<meta name="rating" content="general"/>
	<meta name="spiders" content="all"/>
	<link rel="alternate" type="application/rss+xml" title="Feed <?= $desa_title ?>" href="<?= site_url('sitemap') ?>"/> 
	<link rel="icon" href="<?= favico_desa() ?>"/>
	<link rel="apple-touch-icon" href="<?= favico_desa() ?>" />
	<link rel="apple-touch-icon-precomposed" href="<?= favico_desa() ?>" />
	<link rel="shortcut icon" href="<?= favico_desa() ?>"/>
	<link rel='stylesheet' type='text/css' href="<?= base_url()?>assets/css/font-awesome.min.css"/>
	<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/assets/fonts/custom.css?" . THEME_TIMESTAMP); ?>">
	<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/assets/css/bootstrap.css"); ?>">
	<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/assets/css/fancy.css"); ?>">
	<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/assets/css/menu.css?" . THEME_TIMESTAMP); ?>">
	<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/assets/css/style.css?" . THEME_TIMESTAMP); ?>">
	<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/assets/css/darkmode.css"); ?>">
	<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/assets/css/screen.css?" . THEME_TIMESTAMP); ?>">
	<?php if (!empty($desa['nomor_operator']) && theme_config('chats', true)) : ?>
	<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/assets/plugin/czm-chat-support.css"); ?>">
	<?php endif ?>
	<link rel="stylesheet" href="<?= base_url('assets/css/leaflet.css'); ?>"/>
	<link rel="stylesheet" href="<?= base_url('assets/css/mapbox-gl.css'); ?>"/>
	<link rel="stylesheet" href="<?= base_url('assets/css/peta.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/bootstrap/css/dataTables.bootstrap.min.css">
	<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ if (window.scrollY == 0) window.scrollTo(0,1); } </script>
	<script src="<?= base_url("$this->theme_folder/$this->theme/assets/js/jquery-first.min.js"); ?>"></script>
	<script src="<?= base_url("$this->theme_folder/$this->theme/assets/js/customize.js"); ?>"></script>
	<script src="<?= base_url("$this->theme_folder/$this->theme/assets/js/bootstrap.min.js"); ?>"></script>
	<script src="<?= base_url() ?>assets/bootstrap/js/jquery.dataTables.min.js"></script>
	<script src="<?= base_url() ?>assets/bootstrap/js/dataTables.bootstrap.min.js"></script>
	<script language='javascript' src="<?= base_url('assets/front/js/jquery.min.js'); ?>"></script>
	<?php if (!empty($desa['nomor_operator']) && theme_config('chats', true)) : ?>
	<div id="ChatSupport"></div>
	<script src="<?= base_url("$this->theme_folder/$this->theme/assets/plugin/components/moment/moment.min.js"); ?>"></script>
	<script src="<?= base_url("$this->theme_folder/$this->theme/assets/plugin/components/moment/moment-timezone-with-data.min.js"); ?>"></script>
	<script src="<?= base_url("$this->theme_folder/$this->theme/assets/plugin/czm-chat-support.min.js"); ?>"></script>
	<?php $this->load->view($folder_themes . "/partials/home/chats"); ?>
	<?php endif ?>
	<script src="<?= base_url("$this->theme_folder/$this->theme/assets/js/jquery.sticky.js") ?>"></script>
	<script src="<?= base_url("$this->theme_folder/$this->theme/assets/js/script.js") ?>"></script>
	<script src="<?= base_url()?>assets/js/leaflet.js"></script>
	<script src="<?= base_url()?>assets/front/js/layout.js"></script>
	<script src="<?= base_url()?>assets/front/js/jquery.colorbox.js"></script>
	<script src="<?= base_url()?>assets/js/leaflet-providers.js"></script>
	<script src="<?= base_url()?>assets/js/mapbox-gl.js"></script>
	<script src="<?= base_url()?>assets/js/leaflet-mapbox-gl.js"></script>
	<script src="<?= base_url()?>assets/js/peta.js"></script>
	<script src="<?= base_url()?>assets/js/highcharts/highcharts.js"></script>
	<script src="<?= base_url()?>assets/js/highcharts/highcharts-3d.js"></script>
	<script src="<?= base_url()?>assets/js/highcharts/exporting.js"></script>
	<script src="<?= base_url()?>assets/js/highcharts/highcharts-more.js"></script>
	<script src="<?= base_url()?>assets/js/highcharts/sankey.js"></script>
	<script src="<?= base_url()?>assets/js/highcharts/organization.js"></script>
	<script src="<?= base_url()?>assets/js/highcharts/accessibility.js"></script>
	<script src="<?= base_url("$this->theme_folder/$this->theme/assets/js/yall.min.js") ?>"></script>
	<style>
		img.yall_loaded {
			animation: progressiveReveal 0.2s linear;
		}

		@keyframes progressiveReveal {
		0% {
			opacity: 0;
			transform: scale(1.05)
		}

		to {
			opacity: 1;
			transform: scale(1)
		}
	}
	</style>
	<script>
		let yall_option = {
			useLoading : true
		}
		var lazyload = new yall(yall_option);

		window.addEventListener('DOMContentLoaded', (e) => {
		lazyload.run();
		});
	</script>

	<script src="<?= base_url()?>assets/front/js/jquery.cycle2.min.js"></script>
	<script src="<?= base_url()?>assets/front/js/jquery.cycle2.carousel.js"></script>
	<?php $this->load->view('global/validasi_form', ['web_ui' => true]); ?>
	<script type="text/javascript">
		var BASE_URL = '<?= base_url(); ?>';
	</script>
	<?php $this->load->view($folder_themes . "/partials/module_top"); ?>
	<script src="<?= base_url("$this->theme_folder/$this->theme/assets/js/widget.js?" . THEME_TIMESTAMP) ?>"></script>
	<script src="<?= base_url("$this->theme_folder/$this->theme/assets/js/fancybox.js"); ?>"></script>
	<script type="text/javascript" src="<?= base_url("$this->theme_folder/$this->theme/assets/js/default.js"); ?>"></script>
	<div id="fb-root"></div>
	<script async defer crossorigin="anonymous" src="https://connect.facebook.net/id_ID/sdk.js#xfbml=1&version=v14.0&appId=<?= config_item('fbappid') ?>&autoLogAppEvents=1" nonce="M5gMDuon"></script>
	<?= view('admin.layouts.components.token') ?>
</head>
<body>
	<div class="<?= $artikel ? 'homestyle' : '' ?>">
		<?php $this->load->view($folder_themes . "/commons/loader"); ?>
		<?php $this->load->view($folder_themes . "/commons/sidebar"); ?>
		<div class="body-absolute">
			<?php $this->load->view($folder_themes . "/commons/header"); ?>
			<?php $this->load->view($folder_themes .'/partials/event/index') ?>
			<?php $this->load->view($folder_themes.'/commons/teks_berjalan.php') ?>
			<?php if(!in_array($this->uri->segment(1), ['', 'first']) && !in_array($this->uri->segment(2), ['kategori'])) : ?>
			<div class="bg-grey-medium">
			<?php endif; ?>
			<script type='text/javascript'>
				$(function()
				{
					$(window).scroll(function() {
						if($(this).scrollTop()>100) { $('#ScrollToTop').fadeIn()
					} else {
						$('#ScrollToTop').fadeOut();
					}
				});
					$('#ScrollToTop').click(function(){$('html,body').animate({scrollTop:0},1000);
						return false
					})
				});
			</script>
			<?php $this->load->view($folder_themes . "/commons/home"); ?>
			<?php include(FCPATH . "$this->theme_folder/$this->theme/partials/home/views.php"); ?>
			<?php if(in_array($this->uri->segment(1), ['', 'first']) && !in_array($this->uri->segment(2), ['dpt', 'statistik'])) : ?><?php $this->load->view($folder_themes . "/partials/home/banner"); ?><?php endif; ?>
			<?php if (theme_config('jadwal_sholat', false)) $this->load->view($folder_themes . "/partials/home/jadwal_shalat"); ?>
			<?php if ($this->setting->covid_rss) $this->load->view($folder_themes . "/partials/home/feed"); ?>
			<?php $this->load->view($folder_themes . "/partials/$tampil", $data); ?>
			<?php $this->load->view($folder_themes . "/partials/home/statistik"); ?>
			<?php if ($this->setting->layanan_mandiri) $this->load->view($folder_themes."/partials/home/layanan");?>
			<?php if(!in_array($this->uri->segment(1), ['', 'first']) && !in_array($this->uri->segment(2), ['kategori'])) : ?>
			</div>
			<?php endif; ?>
			<?php $this->load->view($folder_themes . "/widgets/aparatur_desa"); ?>
			<?php if(!in_array($this->uri->segment(1), ['', 'first']) || in_array($this->uri->segment(2), ['dpt', 'statistik'])) : ?><?php $this->load->view($folder_themes . "/partials/home/banner"); ?><?php endif; ?>
			<?php if (theme_config('hide_banner_laporan', true)): ?>
			<?php
			$thisMonthStart = date('Y-m-01', strtotime('first day of this month'));
			$thisMonthEnd = date('Y-m-t', strtotime('last day of this month'));
			$lastMonthStart = date('Y-m-01', strtotime('first day of previous month'));
			$lastMonthEnd = date('Y-m-t', strtotime('last day of previous month'));
			
			$eventTypes = [
				'Kelahiran' => 1,
				'Kematian' => 2,
				'Masuk' => 5,
				'Pindah' => 3,
			];
			$dataThisMonth = [];
			$dataLastMonth = [];
			foreach ($eventTypes as $eventName => $eventCode) {
				$dataThisMonth[$eventName] = fetchEventData($this->db, $thisMonthStart, $thisMonthEnd, $eventCode);
				$dataLastMonth[$eventName] = fetchEventData($this->db, $lastMonthStart, $lastMonthEnd, $eventCode);
			}
			?>
			<div class="relative-row ptb-5">
				<div class="covid-data border-grey-soft container-page mt-10">
					<div class="head-module-center border-grey-soft flexcenter"><h1>PERKEMBANGAN PENDUDUK</h1></div>
					<div class="panel-collapse">
						<div class="relative-row">
							<div class="bg-white border-grey-soft">
								<div class="samependk">
									<?php foreach (["Bulan Ini" => $dataThisMonth, "Bulan Lalu" => $dataLastMonth] as $title => $data): ?>
										<div>
											<div class="head-module-center border-grey-soft flexcenter"><h2><?= $title ?></h2></div>
											<div class="samepend">
												<?php foreach ($data as $eventName => $eventData): ?>
													<div class="covid-item bg-grey-medium">
														<div class="head-covid bg-color<?= ($eventData->jumlah_data != 0 && $title === "Bulan Ini") ? "2" : (($title === "Bulan Ini") ? "5" : "3")  ?> flexcenter">
														<?php if ($eventData->jumlah_data != 0 && $title === "Bulan Ini"): ?>
														<span data-remote="false" data-toggle="modal" data-target="#eventModal<?= $eventName ?>">
                                                        <?= $eventName ?>
                                                        </span>
														<?php else: ?>
                                                        <?= $eventName ?>
	                                                    <?php endif; ?>
														</div>
														<h2><?= number_format($eventData->jumlah_data) ?></h2>
														<p>Orang</p>
													</div>
													<div class="modal fade" id="eventModal<?= $eventName ?>" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true" data-backdrop="false">
														<div class="modal-dialog" style="margin:0 !important;">
															<div class="modal-container-medium">
																<div class="modal-article">
																	<?php $eventJudul = ($eventName === "Kematian" || $eventName === "Kelahiran") ? ($eventName === "Kelahiran" ? "Data $eventName" : "Berita Duka") : "Data Penduduk $eventName"; ?>
																	<div class="topmodal bg-grey-dark2 flexleft" data-dismiss="modal" aria-hidden="true" id="eventModalLabel"><?= $eventJudul ?><div class="close-button"></div></div>
																	<div class="modal-article-inner">
																		<div class="withscroll">
																			<div class="relative-hid2 modal-article-content bgwhite" id="eventModalLabel">
                                                                                <div style="display: flex; align-items: top; margin-bottom: 10px;">
                                                                                    <img src="<?= gambar_desa($desa['logo']);?>" alt="" height="70px">
                                                                                    <div style="margin-left: 10px; text-align: left;">
                                                                                        <span><?= ucwords($this->setting->sebutan_desa); ?> <?= ucwords(($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : ''); ?></span><br>
                                                                                        <span><?= ucwords($this->setting->sebutan_kecamatan_singkat." ".$desa['nama_kecamatan'])?>, <?= ucwords($this->setting->sebutan_kabupaten_singkat." ".$desa['nama_kabupaten'])?><br/><?= ucwords("Prov. ".$desa['nama_propinsi'])?></span>
                                                                                    </div>
                                                                                </div>
                                                                                <?php if($eventName === "Kematian") : ?>
																				<h3><?= theme_config('berita_duka', 'Sesungguhnya kita berasal dari Allah, dan kepada-Nya kita pasti akan kembali.'); ?></h3>
																				<?php endif; ?>
                                                                                <div class="table-responsive">
																					<table class="table table-striped table-bordered">
																						<thead>
																							<tr>
																							<?php
																							$eventText = ($eventName === "Kematian" || $eventName === "Kelahiran") ? (($eventName === "Kelahiran") ? "Tgl. Lahir" : "Tgl. Meninggal") : "";
																							$headers = ['', 'Nama Penduduk', $eventText ?: 'Umur', ucwords($this->setting->sebutan_dusun), 'RW', 'RT']; ?>
																							<?php foreach ($headers as $header): ?>
																							<th style="text-align: center;"><?= $header ?></th>
																							<?php endforeach; ?>
																							</tr>
																						</thead>
																						<tbody>
																							<?php
																							$namaPenduduk = explode('#', $eventData->nama_penduduk);
																							$umurPenduduk = explode(',', $eventData->umur);
																							$tanggalLahir = explode(',', $eventData->tanggal_lahir);
																							$tanggalPeristiwa = explode(',', $eventData->tanggal_peristiwa);
																							$namaAyahPenduduk = explode('#', $eventData->nama_ayah);
																							$jenisKelamin = explode('#', $eventData->sex);
																							$dusunPenduduk = explode('#', $eventData->dusun);
																							$catatanPenduduk = explode('#', $eventData->catatan);
																							$rtPenduduk = explode('#', $eventData->rt);
																							$agamaPenduduk = explode('#', $eventData->agama);
																							$foto = explode('#', $eventData->foto);
																							$akta_mati = explode('#', $eventData->akta_mati);
																							$rwPenduduk = explode('#', $eventData->rw); ?>
																							<?php for ($i = 0; $i < count($namaPenduduk ?? []); $i++): ?>
																								<tr>
																									<?php $ayahText = "";
																									if (($eventName === "Kematian" || $eventName === "Kelahiran") && $namaAyahPenduduk[$i] != "-" && ($jenisKelamin[$i] === "1" || $jenisKelamin[$i] === "2")) {
																										$ayahText = $jenisKelamin[$i] === "1" ? "bin" : "binti";
																										$ayahText .= " " . strtolower($namaAyahPenduduk[$i]);
																									} ?>
																									<td class="padat" style='text-align:center'><?= $i + 1 ?></td>
																									<td class="padat" style='text-align:left;'>
                                                                                                    <div style="display: flex; align-items: top;">
                                                                                                        <?= $foto[$i] ? "<img src=\"" . base_url() . "desa/upload/user_pict/" . $foto[$i] . "\" alt='' width='50px' style='margin-left: 10px;' oncontextmenu='return false;''>" : ''; ?>
                                                                                                        <div style="margin-left: 10px;">
                                                                                                            <span><?= $namaPenduduk[$i] ?></span><br>
                                                                                                            <span><?= ucwords($ayahText) ?></span>
                                                                                                        </div>
                                                                                                    </div>
																									</td>
																									<?php if($eventName === "Kelahiran") : ?>
																									<td class="padat" style='text-align:center;'><?= hr($tanggalLahir[$i]) ?></td>
																									<?php elseif($eventName === "Kematian") : ?>
																									<td class="padat" style='text-align:center;'><?= hr($tanggalPeristiwa[$i]) ?><br>Umur <?= $umurPenduduk[$i] ?> Tahun</td>
																									<?php else: ?>
																									<td class="padat" style='text-align:center;'><?= $umurPenduduk[$i] ?> Tahun</td>
																									<?php endif; ?>
																									<td class="padat" style='text-align:center;'>
																									<?= ucwords($dusunPenduduk[$i]) ?>
																									</td>
																									<td class="padat" style='text-align:center;'>
																									<?= $rwPenduduk[$i] ?>
																									</td>
																									<td class="padat" style='text-align:center;'>
																									<?= $rtPenduduk[$i] ?>
																									</td>
																								</tr>
																							<?php endfor; ?>
																						</tbody>
																					</table>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												<?php endforeach; ?>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php endif; ?>
            <?php if (theme_config('hide_banner_layanan', true)): ?>
			<?php
            $periods = [
                "Hari Ini" => [date("Y-m-d"), date("Y-m-d")],
                "Kemarin" => [date("Y-m-d", strtotime("-1 day")), date("Y-m-d", strtotime("-1 day"))],
                "Minggu Ini" => [date("Y-m-d", strtotime("this week")), date("Y-m-d")],
                "Bulan Ini" => [date("Y-m-01"), date("Y-m-t")],
                "Bulan Lalu" => [date("Y-m-d", strtotime("-1 month", strtotime(date("Y-m-01")))), date("Y-m-t", strtotime("-1 month"))],
                "Tahun Ini" => [date("Y-01-01"), date("Y-12-t")],
                "Tahun Lalu" => [date("Y-01-01", strtotime("-1 year")), date("Y-12-t", strtotime("-1 year"))]
            ];
            ?>
			<div class="relative-row ptb-5">
                <div class="covid-data border-grey-soft container-page mt-10">
                    <div class="head-module-center border-grey-soft flexcenter">
                        <h1>LAYANAN SURAT PENGANTAR</h1>
                    </div>
                    <div class="panel-collapse">
                        <div class="relative-row">
                            <div class="bg-white border-grey-soft">
                                <div class="samecovid">
                                    <?php foreach ($periods as $period => [$startDate, $endDate]): ?>
                                        <div class="covid-item bg-grey-medium">
                                            <div class="head-covid bg-color3 flexcenter"><?= $period ?></div>
                                            <h2><?= number_format(hitungJumlahData($this->db, $startDate, $endDate)) ?></h2>
                                            <p>Surat</p>
                                        </div>
                                    <?php endforeach; ?>
                                    <div class="covid-item bg-grey-medium">
                                        <div class="head-covid bg-color3 flexcenter">Total</div>
                                        <h2><?= number_format(hitungJumlahData($this->db, '', date("Y-m-d"))) ?></h2>
                                        <p>Surat</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif ?>
			<?php $this->load->view($folder_themes . "/partials/home/data_covid"); ?>
			<?php $this->load->view($folder_themes . "/partials/lapak/lapak_tema"); ?>
			<?php $this->load->view($folder_themes . "/widgets/galeri"); ?>
			<?php $this->load->view($folder_themes . "/partials/home/widgets/index"); ?>
			<?php if (!is_null($transparansi)) $this->load->view($folder_themes. '/widgets/keuangan', $transparansi);?>
			<div class="footer-container">
				<?php include(FCPATH . "$this->theme_folder/$this->theme/commons/social_icons.php"); ?>
				<style>
					.footer-container .leaflet-container .leaflet-control-attribution, .footer-container .leaflet-container .leaflet-control-scale{background-color:transparent !important;}
				</style>
				<div class="limit-footer">
					<div class="footer-inner bg-gradient-hor">
						<div class="footer-center">
							<div class="footer-logo">
								<div class="footer-logo-image flexcenter">
									<img src="<?= gambar_desa($desa['logo']);?>" alt="" />
								</div>
								<h3><?= ucwords(setting('sebutan_pemerintah_desa')) ?><br/><?= ucwords(($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : ''); ?></h3>
							</div>
							<p class="text-break"><?= ucwords(($desa['alamat_kantor']) ? ' ' . $desa['alamat_kantor'] : ''); ?>
							  <br/><?= ucwords($this->setting->sebutan_desa); ?><?= ucwords(($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : ''); ?>
							  <br/><?= ucwords($this->setting->sebutan_kecamatan_singkat); ?> <?= ucwords(($desa['nama_kecamatan']) ? ' ' . $desa['nama_kecamatan'] : ''); ?> <?= ucwords($this->setting->sebutan_kabupaten_singkat); ?> <?= ucwords(($desa['nama_kabupaten']) ? ' ' . $desa['nama_kabupaten'] : ''); ?>
							  <br/><?php if (!empty($desa['email_desa'])): ?><i class="fa fa-envelope" style="margin-right:0px;"></i><?= strtolower(" ".$desa['email_desa'])?><?php endif; ?>
							  <br/><?php if (!empty($desa['telepon'])): ?><i class="fa fa-phone" style="margin-right:0px;"></i><?= ucwords(" ".$desa['telepon'])?><?php endif; ?>
							  <?php if (!empty($desa['telepon']) AND !empty($desa['nomor_operator'])): ?><span style="margin-left:3px;margin-right:3px;">-</span><?php endif; ?>
							  <?php if (!empty($desa['nomor_operator'])): ?><i class="fa fa-mobile" style="margin-right:0px;"></i><?= ucwords(" ".$desa['nomor_operator'])?><?php endif; ?>
							</p>
							<?php if ($w_cos): ?>
							<?php foreach ($w_cos as $data): ?>
							<?php $widget = trim($data['isi']) ?>
							<?php if ($data["isi"] == "media_sosial.php"): ?>
							<?php $this->load->view($folder_themes . "/widgets/media_sosial"); ?>
							<?php endif; ?>
							<?php endforeach; ?>
							<?php endif; ?>
						</div>
						<div class="footer-left">
							<?php $this->load->view($folder_themes . "/widgets/peta_lokasi_kantor"); ?>
						</div>
						<div class="footer-right">
							<?php $this->load->view($folder_themes . "/widgets/peta_wilayah_desa"); ?>
						</div>
					</div>
				</div>
				<div class="copyright">
					<div class="limit-footer">
						&copy; <a href="https://opendesa.id/" rel="noopener noreferrer" target="_blank">OpenDesa</a>
						<?php if (file_exists('diskominfo')): ?>
						<a href="<?= config_item('diskominfo') ? config_item('diskominfo') : '#'; ?>" rel="noopener noreferrer" target="_blank">
							<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/diskominfo.png") ?>" style="width: 55px;" alt=""/>
						</a>
						<?php endif; ?>
						<?php if (file_exists('hosting')): ?>
						<a href="https://member.jagoanhosting.com/aff.php?aff=7056" rel="noopener noreferrer" target="_blank">
							<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/hosting.png") ?>" style="width: 55px;" alt=""/>
						</a>
						<?php endif; ?>
						<?php if (file_exists('mitra')): ?>
						<a href="https://my.idcloudhost.com/aff.php?aff=3172" rel="noopener noreferrer" target="_blank">
							<img src="<?= base_url('/assets/images/Logo-IDcloudhost.png')?>" style="height: 15px;" alt="">
						</a>
						<?php endif; ?>
						<?php if (setting('tte')): ?>
						<img src="<?= asset('assets/images/bsre.png?v', false); ?>" style="width: 55px;" alt="" />
						<?php endif ?>
						<br>
						<a href="https://github.com/OpenSID/OpenSID" rel="noopener noreferrer" target="_blank">Aplikasi OpenSID</a> -
						<a href="https://www.ariandi.net" rel="noopener noreferrer" title="<?= date('d F Y', strtotime(THEME_TIMESTAMP)) ?>" target="_blank"><?= THEME_NAME ?> <?= THEME_VERSION ?></a>
					</div>
				</div>
			</div>
			<?php $this->load->view($folder_themes . "/partials/module_bottom"); ?>
			<script type="text/javascript">
				$(window).load(function() { $("#loading").delay(100).fadeOut("slow"); } )
			</script>
			<div class="bottom-tombol">
				<div class="kembali bg-color5 flexcenter" onclick="goBack()">
					<svg viewBox="0 0 24 24">
						<g><path d="M8 7v4L2 6l6-5v4h5a8 8 0 1 1 0 16H4v-2h9a6 6 0 1 0 0-12H8z"/></g>
					</svg>	
				</div>
				<div class="to-dark flexcenter" onclick="setDarkMode(true)" id="darkBtn"><img style="height:20px;width:auto;" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/dark.svg") ?>"/></div>
				<div id="ScrollToTop" class="flexcenter">
					<svg viewBox="0 0 96 96">
						<g><path d="M82.6074,62.1072,52.6057,26.1052a6.2028,6.2028,0,0,0-9.2114,0L13.3926,62.1072a5.999,5.999,0,1,0,9.2114,7.6879L48,39.3246,73.396,69.7951a5.999,5.999,0,1,0,9.2114-7.6879Z"/></g>
					</svg>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		function goBack() {
			window.history.back();
		}
	</script>
	<script>
		$(document).ready(function(){
			$(".tip-top").tooltip({
				placement : 'top'
			});
			$(".tip-right").tooltip({
				placement : 'right'
			});
			$(".tip-bottom").tooltip({
				placement : 'bottom'
			});
			$(".tip-left").tooltip({
				placement : 'left'
			});
		});
	</script>
	<script>
		function setDarkMode(isDark)
		{
			var darkBtn = document.getElementById('darkBtn')
			var lightBtn = document.getElementById('lightBtn')
			if(isDark) {
				lightBtn.style.display = "block"
				darkBtn.style.display = "none"
			} else {
				lightBtn.style.display = "none"
				darkBtn.style.display = "block"
			}
			document.body.classList.toggle("darkmode");
		}
	</script>
	<script>
		if (localStorage.getItem('theme') == 'dark')
			setDarkMode()
		function setDarkMode() 
		{
			let emoticon = ''
			let isDark = document.body.classList.toggle('darkmode')
			if (isDark) 
			{      
				emoticon = '<img style="height:20px;width:auto;" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/light.svg") ?>"/>'      
				localStorage.setItem('theme','dark')
			} else {      
				emoticon = '<img style="height:20px;width:auto;" src="<?= base_url("$this->theme_folder/$this->theme/assets/images/icon/dark.svg") ?>"/>'
				localStorage.removeItem('theme')
			}
			document.getElementById('darkBtn').innerHTML = emoticon
		}
	</script>
	<script type="text/javascript">
		function printDiv(divName) {
			var printContents = document.getElementById(divName).innerHTML;
			var originalContents = document.body.innerHTML;
			document.body.innerHTML = printContents;
			window.print();
			document.body.innerHTML = originalContents;
		}
	</script>
</body>
</html>
<?php endif; ?>
