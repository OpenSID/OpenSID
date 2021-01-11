<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<p>
	<?php if ( ! is_null($transparansi)) $this->load->view(Web_Controller::fallback_default($this->theme, '/partials/apbdesa-tema.php'), $transparansi);?>
		<div>
			<a href="<?= site_url('siteman') ?>"><button class="btn btn-primary navbar-btn"><i class="fa fa-lock fa-lg"></i> Login Admin</button></a>
			<?php if ($this->setting->layanan_mandiri || $cek_anjungan): ?>
				<a href="<?= site_url('layanan-mandiri') ?>"><button class="btn btn-primary navbar-btn"><i class="fa fa-user fa-lg"></i> Layanan Mandiri</button></a>
			<?php endif; ?>
		</div>
		&copy; 2016-<?= date("Y");?> <a target="_blank" href="https://opendesa.id">OpenDesa</a> <i class="fa fa-circle" style="font-size: smaller;"></i>
		<a target="_blank" href="https://github.com/OpenSID/OpenSID">OpenSID</a> <?= AmbilVersi()?>
		<br>
		Aplikasi Sistem Informasi Desa (SID) ini dikembangkan oleh <a target="_blank" href="https://www.facebook.com/groups/OpenSID/">Komunitas OpenSID</a></br>

		<?php if (file_exists('mitra')): ?>
			Hosting didukung <a target="_blank" href="https://idcloudhost.com"><img src="<?= base_url('/assets/images/Logo-IDcloudhost.png')?>" height='15px' alt="Logo-IDCloudHost" title="Logo-IDCloudHost"></a>
		<?php endif; ?>
	</p>

	<div class="scrollToTop"><i class="fa fa-arrow-up fa-lg"></i></div>
