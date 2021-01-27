<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<style type="text/css">
  .web .content-wrapper
  {
    margin-left: 0px !important;
  }
</style>
<?php $this->load->view($folder_themes.'/layouts/header.php');?>
      <div id="contentwrapper" class="web">
          <div class="innertube">
            <?php $this->load->view($halaman_statis); ?>
          </div>
      </div>
      <div id="footer">
        <?php $this->load->view(Web_Controller::fallback_default($this->theme, '/partials/copywright.tpl.php'));?>
      </div>
    </div>
  </body>
</html>
