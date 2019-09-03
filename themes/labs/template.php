<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en" class="no-focus">
<head>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-146951370-1');
</script>
<?php $this->load->view("$folder_themes/commons/meta.php"); ?>
<?php $this->load->view("$folder_themes/commons/style.php"); ?>
<?php $this->load->view("$folder_themes/commons/scripts.php"); ?>

<div id="page-container"  class="sidebar-inverse side-scroll page-header-fixed page-header-inverse main-content-boxed side-trans-enabled">
    <?php $this->load->view("$folder_themes/partials/header_mobile.php"); ?>
    <?php $this->load->view("$folder_themes/partials/header.php"); ?>
  <main id="main-container">
        <?php $this->load->view("$folder_themes/partials/home.php"); ?>
  </main>
  <?php $this->load->view("$folder_themes/partials/footer.php"); ?>
</div>
</body>
</html>