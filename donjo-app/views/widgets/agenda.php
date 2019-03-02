<!-- Widget Agenda -->

<?php if ($agenda): ?>
  <div class="box box-primary box-solid">
    <div class="box-header">
      <h3 class="box-title"><a href="<?= site_url();?>first/kategori/1000"><i class="fa fa-calendar"></i> Agenda</a></h3>
    </div>
    <div class="box-body">
      <ul id="ul-menu" class="sidebar-latest">
        <?php foreach ($agenda as $l): ?>
          <li><div id="small-agenda"><?= tgl_indo($l['tgl_agenda'])?></div><a href="<?= site_url("first/artikel/$l[id]")?>"><?= $l['judul']?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
<?php endif; ?>
