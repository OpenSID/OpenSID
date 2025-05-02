<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!DOCTYPE html>

<html lang="en">
<head>
	<title>Verifikasi Surat <?= ucwords($this->setting->sebutan_desa) . (($config['nama_desa']) ? ' ' . $config['nama_desa']: '') . get_dynamic_title_page_from_path(); ?></title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<meta name="description" content="<?= 'Verifikasi Surat '.ucwords($this->setting->sebutan_desa . ' ' . $config['nama_desa']).' '.ucwords($this->setting->sebutan_kecamatan . ' ' . $config['nama_kecamatan']).' '.ucwords($this->setting->sebutan_kabupaten . ' ' . $config['nama_kabupaten']).' Propinsi '.ucwords($config['nama_propinsi']);?>">
	<meta property='og:url' content="<?= site_url(); ?>" />
	<meta property="og:title" content="<?= "Surat " . $surat->perihal." a.n. " . $surat->nama_penduduk ?? $surat->nama_non_warga; ?>"/>
	<meta property='og:description' content="<?= 'Verifikasi Surat '.ucwords($this->setting->sebutan_desa . ' ' . $config['nama_desa']).' '.ucwords($this->setting->sebutan_kecamatan . ' ' . $config['nama_kecamatan']).' '.ucwords($this->setting->sebutan_kabupaten . ' ' . $config['nama_kabupaten']).' Propinsi '.ucwords($config['nama_propinsi']);?>" />
	<meta property="og:image" content="<?= gambar_desa($config['logo']); ?>"/>
	<?php if(is_file(LOKASI_LOGO_DESA . "favicon.ico")): ?>
		<link rel="icon" type="image/x-icon" href="<?= base_url()?><?= LOKASI_LOGO_DESA?>favicon.ico" />
	<?php else: ?>
		<link rel="icon" type="image/x-icon" href="<?= base_url()?>favicon.ico" />
	<?php endif; ?>
	<link href="<?= base_url("$this->theme_folder/$this->theme/assets/css/styleWebFont.css"); ?>" rel="stylesheet" type="text/css">
	<link href="<?= base_url("$this->theme_folder/$this->theme/assets/css/style.bundle.css"); ?>" rel="stylesheet" type="text/css">
</head>
<body class="m-page--wide m-header--fixed m-header--fixed-mobile m-footer--push m-aside--offcanvas-default m-header--minimize-off">
	<div id="info_bsre" class="m-content list_data">
		<div class="m-portlet m--margin-top-0 m--margin-bottom-10">
			<div class="m-portlet__head" style="background-color:#3d3b56;">
				<div class="m-portlet__head-caption">
					<div class="m-portlet__head-title">
						<h3 class="m-portlet__head-text" style="color:#FFF">VERIFIKASI SURAT</h3>
					</div>
				</div>
			</div>
			<div class="text-break">
				<div class="m-invoice-2">
					<div class="m-invoice__wrapper">
						<div class="m-invoice__head">
							<div class="m-invoice__container m-invoice__container--centered">
								<div class="m-invoice__logo" style="padding:2.5rem 0 2.5rem; text-align:center">
									<div style="padding:0 0 1.5rem 0;">
										<img src="<?= gambar_desa($config['logo']); ?>" style="margin: 0px; vertical-align: top;" alt="">
									</div>
									<span class="m-invoice__subtitle" style="font-weight: bold;">
										Pemerintah <?= ucwords($this->setting->sebutan_kabupaten . ' ' . $config['nama_kabupaten']); ?><br/>
										<?= ucwords($this->setting->sebutan_kecamatan . ' ' . $config['nama_kecamatan']); ?><br/>
										<?= ucwords($this->setting->sebutan_desa . ' ' . $config['nama_desa']); ?>
									</span>
								</div>
								<div style="vertical-align:middle; font-size:16px;">
									<strong style="color:#333399">Info Dokumen</strong>
								</div>
								<div class="m-invoice__items" style="padding:0.5rem 0 2.5rem 0">
									<div class="m-invoice__item" style="padding-bottom: 0.5rem;">
										<span class="m-invoice__subtitle" style="padding-bottom: 0rem;">Nomor Surat</span>
										<span class="m-invoice__text"><?= $surat->nomor_surat; ?></span>
									</div>
									<div class="m-invoice__item" style="padding-bottom: 0.5rem;">
										<span class="m-invoice__subtitle" style="padding-bottom: 0rem;">Tanggal Surat</span>
										<span class="m-invoice__text"><?= tgl_indo($surat->tanggal); ?></span>
									</div>
									<?php if ($surat->nama_penduduk): ?>
									<div class="m-invoice__item" style="padding-bottom: 0.5rem;">
										<span class="m-invoice__subtitle" style="padding-bottom: 0rem;">Nama Pemohon</span>
										<span class="m-invoice__text"><?= "a.n. " . $surat->nama_penduduk ?></span>
									</div>
									<?php endif; ?>
									<div class="m-invoice__item" style="padding-bottom: 0.5rem;">
										<span class="m-invoice__subtitle" style="padding-bottom: 0rem;">Perihal</span>
										<span class="m-invoice__text"><?= "Surat " . $surat->perihal; ?></span>
									</div>
								</div>
								<div style="vertical-align:middle; font-size:16px">
									<strong style="color:#333399">Penandatangan</strong>
								</div>
								<div class="m-invoice__items" style="padding:0.5rem 0 1.5rem 0">
									<div class="m-invoice__item" style="padding-bottom: 0.5rem;">
										<span class="m-invoice__subtitle" style="padding-bottom: 0rem;">Nama</span>
										<span class="m-invoice__text"><?= $surat->pamong_nama; ?></span>
									</div>
									<div class="m-invoice__item" style="padding-bottom: 0.5rem;">
										<span class="m-invoice__subtitle" style="padding-bottom: 0rem;">Jabatan</span>
										<span class="m-invoice__text sembunyikan"><?= $surat->pamong_jabatan; ?></span>
									</div>
									<div class="m-invoice__item" style="padding-bottom: 0.5rem;">
										<span class="m-invoice__subtitle" style="padding-bottom: 0rem;">Pada Tanggal</span>
										<span class="m-invoice__text"><?= tgl_indo2($surat->tanggal); ?></span>
									</div>
								</div>
								<div class="m-invoice__logo text-break" style="padding:1rem 0; text-align:center">
									<div>
										<div style="vertical-align:middle; font-size:16px">
											<strong style="color:#333399">Adalah benar dan tercatat dalam database sistem informasi kami.</strong>
										</div>
										<div style="padding:1rem 0 1.0rem 0">
											Untuk memastikan kebenaran informasi ini pastikan URL pada browser anda adalah <mark><?= $config['website']?></mark>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
