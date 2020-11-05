<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
	<?php $this->load->view($folder_themes . '/commons/meta') ?>
	<?php $this->load->view($folder_themes . '/commons/source_css') ?>
	<link rel="stylesheet" href="<?= base_url("$this->theme_folder/$this->theme/assets/css/bootstrap.min.css") ?>">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.20/js/jquery.dataTables.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/8.1.1/highcharts.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/8.1.1/highcharts-3d.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.27.0/feather.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body data-theme="light">
	<?php $this->load->view($folder_themes . '/commons/header') ?>
	<?php $this->load->view($folder_themes . '/partials/map') ?>
	<?php $this->load->view($folder_themes . '/commons/footer') ?>
	<script src="<?= base_url("$this->theme_folder/$this->theme/assets/js/script.min.js?v2.0-beta")?>"></script>
</body>

</html>