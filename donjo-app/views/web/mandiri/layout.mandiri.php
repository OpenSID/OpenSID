<?php $this->load->view('web/mandiri/header_mandiri.php') ?>
<style type="text/css">
  div.modal-header.bg-primary { padding: 10px; }
  #wrapper-mandiri .tdk-permohonan { display: none !important; }
  a.btn { color: #fff; }
  .unread > td { background-color: #ffeeaa !important; }
</style>
<div class="content-wrapper" id="wrapper-mandiri">
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
  });
</script>


