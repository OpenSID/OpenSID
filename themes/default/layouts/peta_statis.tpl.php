<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view($folder_themes.'/layouts/header.php');?>
      <div id="contentwrapper">
          <div class="innertube">
            <?php $this->load->view($halaman_peta); ?>
          </div>
      </div>
      <div id="footer">
        <?php $this->load->view(Web_Controller::fallback_default($this->theme, '/partials/copywright.tpl.php'));?>
      </div>
    </div>
  </body>
</html>
