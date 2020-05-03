<!-- Custom Tabs -->
<div class="nav-tabs-custom">
  <ul class="nav nav-tabs">
    <li class="active"><a href="#tab_1" data-toggle="tab">Surat Masuk</a></li>
    <li><a href="#tab_2" data-toggle="tab">Surat Keluar</a></li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane active" id="tab_1">
      <?php $this->load->view('buku/umum/content_agenda_masuk') ?>
    </div>
    <!-- /.tab-pane -->
    <div class="tab-pane" id="tab_2">
      <?php $this->load->view('buku/umum/content_agenda_keluar') ?>
    </div>
    <!-- /.tab-pane -->
  </div>
  <!-- /.tab-content -->
</div>
<!-- nav-tabs-custom -->