<?php $this->load->view('web/mandiri/header_mandiri.php') ?>

      <div class="content-wrapper">
        <section class="content">
          <div class="row">
            <div class="col-md-3">
              <div class="box box-info">
                <div class="box-header with-border">
                </div>
                <div class="box-body no-padding">
                  <?php include('donjo-app/views/web/mandiri/layanan_mandiri.php'); ?>
                </div>
              </div>
            </div>
            <div class="col-md-9">
              <div class="box box-info">
                <div class="box-body">
                  <div class="row">
                    <div class="col-sm-12">
                    <?php
                      $views_partial_layout = '';
                      switch($m){
                        case 1 :
                          $views_partial_layout = 'web/mandiri/mandiri.php';
                          break;
                        case 2 :
                          $views_partial_layout = 'web/mandiri/layanan.php';
                          break;
                        case 4 :
                          $views_partial_layout = 'web/mandiri/bantuan.php';
                          break;
                        default:
                          $views_partial_layout = 'web/mandiri/lapor.php';
                      }
                      $this->load->view($views_partial_layout);
                    ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>

<?php $this->load->view('web/mandiri/footer_mandiri.php') ?>
