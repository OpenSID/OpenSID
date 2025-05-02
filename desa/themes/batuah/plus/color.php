<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php if (config_item('01')): ?>
	<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/assets/css/color/01.css"); ?>">
<?php elseif (config_item('05')): ?>
	<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/assets/css/color/05.css"); ?>">
<?php elseif (config_item('07')): ?>
	<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/assets/css/color/07.css"); ?>">
<?php elseif (config_item('03')): ?>
	<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/assets/css/color/03.css"); ?>">
<?php elseif (config_item('02')): ?>
	<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/assets/css/color/02.css"); ?>">
<?php elseif (config_item('08')): ?>
	<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/assets/css/color/08.css"); ?>">	
<?php elseif (config_item('06')): ?>
	<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/assets/css/color/06.css"); ?>">	
<?php elseif (config_item('04')): ?>
	<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/assets/css/color/04.css"); ?>">	
<?php elseif (config_item('dark')): ?>
	<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/assets/css/color/dark.css"); ?>">	
<?php else : ?>
	<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/assets/css/color/01.css"); ?>">
<?php endif; ?>

<?php if(IS_PREMIUM) : ?>
<?php $this->load->view("$folder_themes/assets/css/color/warna.php"); ?>
<?php endif; ?>

<!-- Pilihan Warna -->
<link href="<?= base_url("$this->theme_folder/$this->theme/assets/css/color/01.css"); ?>" rel="stylesheet alternate" title="01"/>
<link href="<?= base_url("$this->theme_folder/$this->theme/assets/css/color/05.css"); ?>" rel="stylesheet alternate" title="05"/>
<link href="<?= base_url("$this->theme_folder/$this->theme/assets/css/color/07.css"); ?>" rel="stylesheet alternate" title="07"/>
<link href="<?= base_url("$this->theme_folder/$this->theme/assets/css/color/03.css"); ?>" rel="stylesheet alternate" title="03"/>
<link href="<?= base_url("$this->theme_folder/$this->theme/assets/css/color/02.css"); ?>" rel="stylesheet alternate" title="02"/>
<link href="<?= base_url("$this->theme_folder/$this->theme/assets/css/color/08.css"); ?>" rel="stylesheet alternate" title="08"/>
<link href="<?= base_url("$this->theme_folder/$this->theme/assets/css/color/06.css"); ?>" rel="stylesheet alternate" title="06"/>
<link href="<?= base_url("$this->theme_folder/$this->theme/assets/css/color/04.css"); ?>" rel="stylesheet alternate" title="04"/>
<link href="<?= base_url("$this->theme_folder/$this->theme/assets/css/color/dark.css"); ?>" rel="stylesheet alternate" title="dark"/>
<!-- -->


