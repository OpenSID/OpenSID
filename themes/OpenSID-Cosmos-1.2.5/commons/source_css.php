<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<link href="https://fonts.googleapis.com/css?family=Montserrat:300,600,700" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/vendor/bootstrap/css/bootstrap.min.css") ?>">
<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/vendor/font-awesome/css/all.min.css") ?>">
<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/vendor/font-awesome/css/fontawesome.min.css")?>">
<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/vendor/ace-menu/css/ace-responsive-menu.min.css") ?>">
<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/vendor/owl-carousel/css/owl.carousel.min.css") ?>">
<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/vendor/owl-carousel/css/owl.theme.default.min.css") ?>">
<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/vendor/fancybox/css/jquery.fancybox.min.css") ?>">
<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/vendor/jssocial/css/jssocials.css") ?>">
<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/vendor/jssocial/css/jssocials-theme-flat.css") ?>">
<link rel="stylesheet" href="<?= base_url("assets/css/leaflet.css")?>">
<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/assets/css/animate.min.css") ?>">
<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/assets/css/loader.min.css") ?>">
<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/assets/css/comments.min.css") ?>">
<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/assets/css/style.min.css") ?>">
<?php if (is_file("desa/css/$this->theme/desa-web.css")): ?>
	 <!-- Extra CSS untuk tema cosmos. Timpa aturan css di berkas ini. -->
	<link rel="stylesheet" href="<?php echo base_url("desa/css/$this->theme/desa-web.css"); ?>">
<?php endif; ?>