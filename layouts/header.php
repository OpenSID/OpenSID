<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <?php $this->load->view("$folder_themes/commons/meta.php"); ?>
</head>
<body>
<a class="scrollToTop" href="#"><i class="fa fa-angle-up"></i></a>
<div class="container"style="background-color: #f6f6f6;">
    <header id="header">
        <?php $this->load->view("$folder_themes/partials/header.php"); ?>
    </header>
    <div id="navarea">
        <?php $this->load->view("$folder_themes/partials/menu_head.php"); ?>
    </div>
    <section id="mainContent">
        <div class="content_middle">
        </div>
        <div class="content_bottom">
            <div class="col-lg-12 col-md-12">
                <div class="content_bottom_left">
                    <script src="<?= base_url()?>assets/front/js/jquery.js"></script>
                    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/bootstrap/css/dataTables.bootstrap.min.css">
                    <script src="<?= base_url() ?>assets/bootstrap/js/jquery.dataTables.min.js"></script>
                    <script src="<?= base_url() ?>assets/bootstrap/js/dataTables.bootstrap.min.js"></script>
                    <script type="text/javascript">
                      var BASE_URL = "<?= base_url(); ?>";
                    </script>
