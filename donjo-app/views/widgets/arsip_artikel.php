<!-- widget Arsip Artikel -->

<style type="text/css">
  #arsip_artikel .nav > li.active > a { color: green }
  #arsip_artikel img { width: 30%; margin:0 6px 4px 0; float: left;}
  #arsip_artikel td { padding-bottom: 2px; }
</style>
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
        <table>
          <?php foreach ($arsip as $l): ?>
            <tr><td>
              <a href="<?= site_url("first/artikel/$l[id]")?>">
                <?php if (is_file(LOKASI_FOTO_ARTIKEL."kecil_$l[gambar]")): ?>
                    <img class="img-fluid img-thumbnail" src="<?= base_url("desa/upload/artikel/kecil_$l[gambar]")?>"/>
                <?php else: ?>
                    <img class="img-fluid img-thumbnail" src="<?= base_url("assets/images/404-image-not-found.jpg")?>"/>
                <?php endif;?>
                <small><span class="meta_date"><font color="green"><?= tgl_indo2($l['tgl_upload']) ?></font></span><br><?= $l['judul']?></small>
              </a>
            </td></tr>
          <?php endforeach; ?>
        </table>
      </div>
      <div id="acak" class="tab-pane fade">
        <table>
          <?php foreach ($arsip_rand as $l): ?>
            <tr><td>
              <a href="<?= site_url("first/artikel/$l[id]")?>">
                <?php if (is_file(LOKASI_FOTO_ARTIKEL."kecil_$l[gambar]")): ?>
                    <img class="img-fluid img-thumbnail" src="<?= base_url("desa/upload/artikel/kecil_$l[gambar]")?>"/>
                <?php else: ?>
                    <img class="img-fluid img-thumbnail" src="<?= base_url("assets/images/404-image-not-found.jpg")?>"/>
                <?php endif;?>
                <small><span class="meta_date"><font color="green"><?= tgl_indo2($l['tgl_upload']) ?></font></span><br><?= $l['judul']?></small>
              </a>
            </td></tr>
          <?php endforeach; ?>
        </table>
      </div>
    </div>
  </div>
</div>
