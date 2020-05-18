<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php if (!is_null($transparansi)) $this->load->view(Web_Controller::fallback_default($this->theme, '/partials/apbdesa-tema.php'), $transparansi);?>

<div id="footer-left">&copy; 2016-<?= date("Y");?>
	<a target="_blank" href="https://opendesa.id">OpenDesa</a> <i class="fa fa-circle" style="font-size: smaller;"></i> <a target="_blank" href="https://github.com/OpenSID/OpenSID">OpenSID</a> <?= AmbilVersi()?> <i class="fa fa-circle" style="font-size: smaller;"></i> Tema Hadakewa
	<br>Dikembangkan oleh <a target="_blank" href="https://www.facebook.com/groups/OpenSID/">Komunitas OpenSID</a>
	<br/>Dikelola oleh Pemerintah <?= ucwords($this->setting->sebutan_desa)?> <?= $desa['nama_desa']?>
	<?php if (file_exists('mitra')): ?>
		<br/>Hosting didukung <a target="_blank" href="https://idcloudhost.com"><img src="<?= base_url('/assets/images/Logo-IDcloudhost.png')?>" height='15px' alt="Logo-IDCloudHost" title="Logo-IDCloudHost"></a>
	<?php endif; ?>
</div>
<div id="footer-right">
	<ul id="global-nav-right" class="top">
		<?php foreach ($sosmed As $data): ?>
			<?php if (!empty($data["link"])): ?>
				<li><a href="<?= $data['link']?>" target="_blank"><span style="color:#fff" ><i class="fa fa-<?= strtolower(str_replace(' ', '-', $data['nama']))?> fa-2x"></i></span></a></li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
</div>
