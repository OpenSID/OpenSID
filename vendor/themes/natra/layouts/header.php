<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<?php $this->load->view("$folder_themes/commons/meta"); ?>
</head>
<body onLoad="renderDate()">
	<a class="scrollToTop" href="#"><i class="fa fa-angle-up"></i></a>
	<div class="container"style="background-color: #f6f6f6;">
		<header id="header">
			<?php $this->load->view("$folder_themes/partials/header"); ?>
		</header>
		<div id="navarea">
			<?php $this->load->view("$folder_themes/partials/menu_head"); ?>
		</div>
		<section>
			<div class="content_middle">
			</div>
			<div class="content_bottom">
				<div class="col-lg-12 col-md-12">
					<div class="content_left">
						<?php $this->load->view('head_tags_front') ?>
