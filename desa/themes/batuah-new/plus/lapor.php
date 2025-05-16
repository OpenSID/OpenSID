<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="withscroll-padding">
	<div class="pengaduan-pop">
		<div class="flexcenter" style="margin-bottom:15px;">
			<div>
				<div class="flexcenter"><img src="<?= base_url("$this->theme_folder/$this->theme/assets/images/pengaduan.png") ?>" /></div>
				<h3>Jika ada saran, pertanyaan, keluhan maupun kritikan dan pengaduan silahkan ajukan dengan menggunakan layanan dibawah...</h3>
			</div>
		</div>
		<?php if ($desa['telepon']) : ?>
			<div class="footer-contact padding-topbottom-1 flexleft">
				<div class="contact-icon bg-color1 flexcenter">
					<svg viewBox="0 0 24 24" style="opacity:1;">
						<path d="M19,11H21V9H19M20,15.5C18.75,15.5 17.55,15.3 16.43,14.93C16.08,14.82 15.69,14.9 15.41,15.18L13.21,17.38C10.38,15.94 8.06,13.62 6.62,10.79L8.82,8.59C9.1,8.31 9.18,7.92 9.07,7.57C8.7,6.45 8.5,5.25 8.5,4A1,1 0 0,0 7.5,3H4A1,1 0 0,0 3,4A17,17 0 0,0 20,21A1,1 0 0,0 21,20V16.5A1,1 0 0,0 20,15.5M17,9H15V11H17M13,9H11V11H13V9Z" />
					</svg>
				</div>
				<p><?= ucwords(" " . $desa['telepon']) ?></p>
			</div>
		<?php endif ?>
		<?php if ($desa['email_desa']) : ?>
			<div class="footer-contact padding-topbottom-1 flexleft">
				<div class="contact-icon bg-color1 flexcenter">
					<svg viewBox="0 0 24 24" style="opacity:1;">
						<path d="M20,8L12,13L4,8V6L12,11L20,6M20,4H4C2.89,4 2,4.89 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6C22,4.89 21.1,4 20,4Z" />
					</svg>
				</div>
				<p><?= ucwords(" " . $desa['email_desa']) ?></p>
			</div>
		<?php endif ?>

		<a href="<?= site_url('pengaduan') ?>">
			<div class="footer-contact padding-topbottom-1 flexleft">
				<div class="contact-icon bg-color1 flexcenter">
					<svg viewBox="0 0 24 24" style="opacity:1;">
						<path d="M20 2V4L4 8V6H2V18H4V16L6 16.5V18.5C6 20.4 7.6 22 9.5 22S13 20.4 13 18.5V18.3L20 20V22H22V2H20M11 18.5C11 19.3 10.3 20 9.5 20S8 19.3 8 18.5V17L11 17.8V18.5Z" />
					</svg>
				</div>
				<p><b>Layanan Pengaduan</b></p>
			</div>
		</a>

		<h3 style="margin-top:15px;">Kantor Desa :</h3>
		<p><?= ucwords(($desa['alamat_kantor']) ? ' ' . $desa['alamat_kantor'] : ''); ?>, <?= ucwords($this->setting->sebutan_kecamatan) ?> <?= $desa['nama_kecamatan'] ?>, <?= ucwords($this->setting->sebutan_kabupaten) ?> <?= $desa['nama_kabupaten'] ?> - Provinsi <?= $desa['nama_propinsi'] ?></p>
		<div class="footer-social flexcenter" style="margin-top:15px;">
			<?php foreach ($sosmed as $data) : ?>
				<?php if (!empty($data['link'])) : ?>
					<a href="<?= $data['link'] ?>" target="_blank" rel="noopener">
						<?php if ($data["icon"]): ?>
						<img src="<?= $data['icon'] ?>" alt="<?= $data['nama'] ?>" style="width:24px;height:24px;margin:0 3px !important;" />
						<?php elseif ($data["gambar"]): ?>
						<img src="<?= asset("front/{$data['gambar']}") ?>" alt="<?= $data['nama'] ?>" style="width:24px;height:24px;margin:0 3px !important;" />
						<?php endif ?>
					</a>
				<?php endif ?>
			<?php endforeach ?>
		</div>
	</div>
</div>