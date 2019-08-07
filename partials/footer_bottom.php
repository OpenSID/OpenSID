  <div class="footer_bottom">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
          <div class="footer_bottom_left">
              <?php if (file_exists('mitra')): ?>
              Hosting didukung <a target="_blank" href="https://my.idcloudhost.com/aff.php?aff=3172">
                  <img src="<?= base_url('/assets/images/Logo-IDcloudhost.png')?>" height='15px' alt="Logo-IDCloudHost" title="Logo-IDCloudHost"></a>
              <?php endif; ?>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
          <div class="footer_bottom_right">
             &copy; <a target="_blank" href="https://opendesa.id/">OpenDesa</a>
             <a target="_blank" href="https://github.com/OpenSID/OpenSID">OpenSID <?= AmbilVersi()?></a>
             <a target="_blank" href="<?= site_url(); ?>siteman"> Natra 4.1.08</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  