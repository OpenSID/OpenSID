<?php $this->load->view('web/mandiri/header_mandiri.php') ?>
<style type="text/css">
  div.modal-header.bg-primary { padding: 10px; }
  #wrapper-mandiri .tdk-permohonan { display: none !important; }
  a.btn { color: #fff; }
  .unread > td { background-color: #ffeeaa !important; }
  .show-only-mobile{
    visibility: hidden;
  }
</style>
<div class="content-wrapper" id="wrapper-mandiri">
  <section class="content">
    <div class="row">
      <div class="col-md-3">
        <div class="box box-info" id="box-mandiri">
          <div class="box-header with-border">
            <div class="show-only-mobile" id="mobile-only">
              <h3 class="box-title">NIK: <?= $_SESSION['nik'];?></h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-bars"></i>
                </button>
              </div>
            </div>
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
                <?php if (empty($views_partial_layout)): ?>
                  <?php 
                    switch ($m) {
                      case 1:
                        $views_partial_layout = 'web/mandiri/mandiri';
                        break;
                      case 2:
                        $views_partial_layout = 'web/mandiri/layanan';
                        break;
                      case 3:
                        $views_partial_layout = 'web/mandiri/mailbox';
                        break;
                      case 4:
                        $views_partial_layout = 'web/mandiri/bantuan';
                        break;
                      case 5:
                        $views_partial_layout = 'web/mandiri/surat';
                        break;
                      default:
                        $views_partial_layout = 'web/mandiri/mandiri';
                    }
                  ?>
                <?php else: ?>
                  <?php $data['mandiri'] = 1; ?>
                <?php endif; ?>
                <?php $this->load->view($views_partial_layout, $data);?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<?php $this->load->view('web/mandiri/footer_mandiri.php') ?>
<script>
  $(document).ready(function() {
    // Di form surat ubah isian admin menjadi disabled
    $("#wrapper-mandiri .readonly-permohonan").attr('disabled', true);
    $("#wrapper-mandiri form#validasi").removeAttr('target');
    $("#wrapper-mandiri .tdk-permohonan textarea").removeClass('required');    
    $("#wrapper-mandiri .tdk-permohonan select").removeClass('required');    
    $("#wrapper-mandiri .tdk-permohonan input").removeClass('required');
    
    // untuk keperluan settings collapsible data mandiri
    // entah kalau di adminLTE ada cara yang lebih mudah
    function detectMobile(x) {
      const box = document.getElementById('box-mandiri');
      const hidden = document.getElementById('mobile-only');
      if (x.matches) { // If media query matches
        box.classList.add('collapsed-box');
        hidden.style.visibility = "visible";
      } else {
        const box = document.getElementById('box-mandiri');
        hidden.style.visibility = "hidden";
        box.classList.remove('collapsed-box');
      }
    }

    var x = window.matchMedia("(max-width: 700px)")
    detectMobile(x) // Call listener function at run time
    x.addListener(detectMobile)
  });
</script>


