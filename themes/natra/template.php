<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <?php $this->load->view("$folder_themes/commons/meta.php"); ?>
    <!-- </head> -->
</head>
<body onLoad="renderDate()">
<!--
<div id="preloader">
  <div id="status">&nbsp;</div>
</div>
-->
<a class="scrollToTop" href="#"><i class="fa fa-angle-up"></i></a>
<div class="container" style="background-color: #f6f6f6;">
    <header id="header">
        <?php $this->load->view("$folder_themes/partials/header.php"); ?>
    </header>
    <div id="navarea">
        <?php $this->load->view("$folder_themes/partials/menu_head.php"); ?>
    </div>
    <div class="row">
        <section>
            <div class="content_middle"></div>
            <div class="content_bottom">
                <div class="col-lg-9 col-md-9">
                    <?php $this->load->view("$folder_themes/partials/bottom_content_left.php"); ?>
                </div>
                <div class="col-lg-3 col-md-3">
                    <?php $this->load->view("$folder_themes/partials/bottom_content_right.php"); ?>
                </div>
            </div>
        </section>
    </div>
</div>
<footer id="footer">
<?php $this->load->view("$folder_themes/partials/footer_top.php"); ?>
<?php $this->load->view("$folder_themes/partials/footer_bottom.php"); ?>
</footer>
<?php $this->load->view("$folder_themes/commons/meta_footer.php"); ?>
<!-- </body></html> -->
</body>
</html>