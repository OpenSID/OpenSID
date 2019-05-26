<!-- widget Acak Artikel -->

<div class="box box-primary box-solid">
  <div class="box-header">
    <h3 class="box-title"><a href="<?= site_url("first/arsip")?>"><i class="fa fa-archive"></i> Random Artikel</a></h3>
  </div>
  <div class="box-body">
    <ul id="ul-menu">
      <?php foreach ($arsip_rand as $l): ?>
        <li><a href="<?= site_url("first/artikel/$l[id]")?>"><?= $l['judul']?></a></li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>
