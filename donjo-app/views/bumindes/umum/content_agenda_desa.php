<!-- Custom Tabs -->
<div class="nav-tabs-custom">
  <ul class="nav nav-tabs">
    <li class="<?php compared_return($selected_tab, "agenda_masuk", "active"); ?>">
      <a href="#masuk" data-toggle="tab">Surat Masuk</a>
    </li>
    <li class="<?php compared_return($selected_tab, "agenda_keluar", "active"); ?>">
      <a href="#keluar" data-toggle="tab">Surat Keluar</a>
    </li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane active" id="masuk">
      <?php $this->load->view('bumindes/umum/content_agenda_masuk') ?>
    </div>
    <!-- /.tab-pane -->
    <div class="tab-pane" id="keluar">
      <?php $this->load->view('bumindes/umum/content_agenda_keluar') ?>
    </div>
    <!-- /.tab-pane -->
  </div>
  <!-- /.tab-content -->
</div>
<!-- nav-tabs-custom -->
<?php $this->load->view('global/confirm_delete');?>
