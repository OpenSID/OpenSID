<!-- widget Arsip Artikel -->

<div class="box box-primary box-solid">
  <div class="box-header">
    <h3 class="box-title"><a href="<?= site_url("first/arsip")?>"><i class="fa fa-archive"></i> Arsip Artikel</a></h3>
  </div>
  <div id="arsip_artikel" class="box-body">
    <ul class="nav nav-tabs">
      <li class="active"><a data-toggle="tab" href="#terkini">Terkini</a></li>
      <li><a data-toggle="tab" href="#acak">Acak</a></li>
    </ul>
    <div class="tab-content">
      <div id="terkini" class="tab-pane fade in active">
        <ul id="ul-menu">
          <?php foreach ($arsip as $l): ?>
            <li><a href="<?= site_url("first/artikel/$l[id]")?>"><?= $l['judul']?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div id="acak" class="tab-pane fade">
        <ul id="ul-menu">
          <?php foreach ($arsip_rand as $l): ?>
            <li><a href="<?= site_url("first/artikel/$l[id]")?>"><?= $l['judul']?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>
</div>
