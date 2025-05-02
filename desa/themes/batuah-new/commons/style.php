<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $randomcolor = theme_config('randomcolor', ''); ?>
<?php $clr1 = theme_config('clr1', '#6eab11'); ?>
<?php $clr2 = theme_config('clr2', '#00743e'); ?>
<?php $clr3 = theme_config('clr3', '#38c255'); ?>
<?php $clrsoft1 = theme_config('clrsoft1', '#b2e665'); ?>
<?php $clrsoft2 = theme_config('clrsoft2', '#d5edb8'); ?>

<?php if (theme_config('randomcolor') == 'true'): ?>
	<style type="text/css">
	:root{
		--clr1: <?=$clr1?>;
		--clr2: <?=$clr2?>;
		--clr3: <?=$color3?>;
		--clrsoft1: <?=$clrsoft1?>;
		--clrsoft2: <?=$clrsoft2?>;
	}
	</style>
<?php else : ?>	
	<?php if (theme_config('templatecolor') == '01'): ?>
		<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/assets/css/color/01.css"); ?>">
	<?php elseif (theme_config('templatecolor') == '02'): ?>
		<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/assets/css/color/02.css"); ?>">
	<?php elseif (theme_config('templatecolor') == '03'): ?>
		<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/assets/css/color/03.css"); ?>">	
	<?php elseif (theme_config('templatecolor') == '04'): ?>
		<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/assets/css/color/04.css"); ?>">
	<?php elseif (theme_config('templatecolor') == '05'): ?>
		<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/assets/css/color/05.css"); ?>">
	<?php elseif (theme_config('templatecolor') == '06'): ?>
		<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/assets/css/color/06.css"); ?>">
	<?php elseif (theme_config('templatecolor') == '07'): ?>
		<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/assets/css/color/07.css"); ?>">
	<?php elseif (theme_config('templatecolor') == '08'): ?>
		<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/assets/css/color/08.css"); ?>">
	<?php else : ?>	
		<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/assets/css/color/01.css"); ?>">
	<?php endif; ?>
<?php endif; ?>