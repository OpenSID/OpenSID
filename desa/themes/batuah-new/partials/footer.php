<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="mainpage-bottom">
<div class="col-default-nopad margin-top-10">
	<div class="footer-container bgcolor-1">
		<div class="footer-inner">
			<div class="footer-logo flexcenter">
				<?php $weblogo = 'desa/logo.gif'; ?>
				<?php if(is_file($weblogo)) : ?>
					<img src="<?= base_url('desa/logo.gif')?>">
				<?php else : ?>
					<img src="<?= gambar_desa($desa['logo']);?>"/>
				<?php endif; ?>
				<div>
					<h3>Pemerintah</h3>
					<h2><?= ucwords($this->setting->sebutan_desa); ?> <?= ucwords(($desa['nama_desa']) ? ' ' . $desa['nama_desa'] : ''); ?></h2>
				</div>
			</div>
			<div class="footer-bottom flexcenter">
				<?php if ($desa['telepon'] || $desa['email_desa']): ?> <div class="footer-bottom-line"></div><?php endif ?>
				<div class="footer-address flexright">
				<div class="footer-address-inner">
					<p><?= ucwords(($desa['alamat_kantor']) ? ' ' . $desa['alamat_kantor'] : ''); ?><br/><?=ucwords($this->setting->sebutan_kecamatan)?> <?=$desa['nama_kecamatan']?>, <?=ucwords($this->setting->sebutan_kabupaten)?> <?=$desa['nama_kabupaten']?> - <?=$desa['nama_propinsi']?> <?= ucwords(($desa['kode_pos']) ? ' ' . $desa['kode_pos'] : ''); ?></p>
				</div>
				</div>
				<?php if ($desa['telepon'] || $desa['email_desa']): ?> 
				<div class="footer-right">
					<?php if ($desa['telepon']): ?> 
					<div class="footer-contact padding-topbottom-1 flexleft">
						<div class="contact-icon bg-color1 flexcenter">
						<svg viewBox="0 0 24 24"><path d="M19,11H21V9H19M20,15.5C18.75,15.5 17.55,15.3 16.43,14.93C16.08,14.82 15.69,14.9 15.41,15.18L13.21,17.38C10.38,15.94 8.06,13.62 6.62,10.79L8.82,8.59C9.1,8.31 9.18,7.92 9.07,7.57C8.7,6.45 8.5,5.25 8.5,4A1,1 0 0,0 7.5,3H4A1,1 0 0,0 3,4A17,17 0 0,0 20,21A1,1 0 0,0 21,20V16.5A1,1 0 0,0 20,15.5M17,9H15V11H17M13,9H11V11H13V9Z" /></svg>
						</div>
						<p>Telp. <?= ucwords(" ".$desa['telepon'])?></p>
					</div>
					<?php endif ?>
					<?php if ($desa['email_desa']): ?>
					<div class="footer-contact padding-topbottom-1 flexleft">
						<div class="contact-icon bg-color1 flexcenter">
						<svg viewBox="0 0 24 24"><path d="M20,8L12,13L4,8V6L12,11L20,6M20,4H4C2.89,4 2,4.89 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6C22,4.89 21.1,4 20,4Z" /></svg>
						</div>
						<p>Email <?= ucwords(" ".$desa['email_desa'])?></p>
					</div>
					<?php endif ?>
				</div>
				<?php endif ?>
			</div>
			<div class="footer-social flexcenter" style="margin-top:10px;">
				<?php foreach ($sosmed As $data): ?>
					<div class="social-icon flexcenter" style="background:transparent !important;padding:0!important;margin:0 1px!important;">
					<?php if (!empty($data["link"])): ?>
						<a href="<?= $data['link']?>" rel="noopener noreferrer" target="_blank">
							<?php if ($data["icon"]): ?>
								<img src="<?= $data['icon'] ?>" alt="<?= $data['nama'] ?>" style="width:26px;height:26px;margin:0 0 0 0!important;padding:0!important;" />
							<?php elseif ($data["gambar"]): ?>
								<img src="<?= asset("front/{$data['gambar']}") ?>" alt="<?= $data['nama'] ?>" style="width:26px;height:26px;margin:0 0 0 0!important;padding:0!important;" />
							<?php endif; ?>
						</a>
					<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	
	<div class="copyright-plus bgcolor-1">
		<div class="flexcenter">
			<div class="copyright-plus-inner flexcenter">
				<?php if(IS_PREMIUM) : ?>
				<div class="copyright-support flexleft">
					<div>
					<img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/bsre.png") ?>" height='18px'>
					</div>
				</div>
				<?php endif ?>
				<?php if (file_exists('mitra')): ?>
				<div class="copyright-support flexleft">
					<a href="https://my.idcloudhost.com/aff.php?aff=3172" rel="noopener noreferrer" target="_blank"><img src="<?= base_url('/assets/images/Logo-IDcloudhost.png')?>" height='18px' alt="IDCloudHost" title="IDCloudHost"></a>
				</div>
				<?php endif ?>
			</div>
		</div>
	</div>
	<div class="copyright bgcolor-2 flexcenter">
		<div class="copyright-title flexcenter">
			<div>
			<p>
			&copy;
			<a href="https://opendesa.id/" rel="noopener noreferrer" target="_blank">OpenDesa</a> - <a href="https://github.com/OpenSID/OpenSID" rel="noopener noreferrer" target="_blank">OpenSID <?= AmbilVersi()?></a></p>
			<p><a href="https://temabatuah.com" target="blank">Batuah <?= THEME_VERSION ?></a></p>
			</div>
		</div>
	</div>
</div>
</div>

<div class="footer-tool">
	<div id="totop" class="smooth-trans flexcenter">
		<svg viewBox="0 0 24 24"><path d="M14,20H10V11L6.5,14.5L4.08,12.08L12,4.16L19.92,12.08L17.5,14.5L14,11V20Z" /></svg>
	</div>
</div>
<script type='text/javascript'>
$(function() { $(window).scroll(function() { if($(this).scrollTop()>100) { $('#totop').fadeIn()} else { $('#totop').fadeOut();}});
$('#totop').click(function(){$('html,body').animate({scrollTop:0},1000);return false})});
</script>

<script>
	$(window).bind('scroll', function () {
	    if ($(window).scrollTop() > 150) {
		$('.footer-tool').addClass('footerscroll');
		} else {
		$('.footer-tool').removeClass('footerscroll');
		}
	});
</script>