<!-- widget Arsip Artikel -->
    <div class="single_bottom_rightbar">
        <h2><i class="fa fa-archive"></i> Artikel</h2>
            <ul role="tablist" class="nav nav-tabs custom-tabs">
              <li class="active" role="presentation"><a data-toggle="tab" role="tab" aria-controls="home" href="#mostPopular">Terkini</a></li>
              <li role="presentation"><a data-toggle="tab" role="tab" aria-controls="messages" href="#recentComent">Random</a></li>
            </ul>
            <div class="tab-content">
              <div id="mostPopular" class="tab-pane fade in active" role="tabpanel">
                  <table id="ul-menu">
                  <?php foreach ($arsip as $l): ?>
                    <tr><td width="25%" valign="top">
                        <a href="<?= site_url("first/artikel/$l[id]")?>">
                            <?php if (is_file(LOKASI_FOTO_ARTIKEL."kecil_$l[gambar]")): ?>
                                <img width="100%" style="float:left; margin:0 8px 4px 0;" class="img-fluid img-thumbnail" src="<?= base_url("desa/upload/artikel/kecil_$l[gambar]")?>"/>
                            <?php else: ?>
                                <img width="100%" style="float:left; margin:0 8px 4px 0;" class="img-fluid img-thumbnail" src="<?= base_url("assets/images/404-image-not-found.jpg")?>"/>
                            <?php endif;?>
                        </td><td width="2%"></td><td valign="top">
                            <small><span class="meta_date"><font color="green"><?= tgl_indo2($l['tgl_upload']) ?></font></span><br><?= $l['judul']?></a></small>
                    </td></tr>
                  <?php endforeach; ?>
                </table>
              </div>
              <div id="recentComent" class="tab-pane fade" role="tabpanel">
                <table id="ul-menu">
                  <?php foreach ($arsip_rand as $l): ?>
                    <tr><td width="25%" valign="top">
                        <a href="<?= site_url("first/artikel/$l[id]")?>">
                            <?php if (is_file(LOKASI_FOTO_ARTIKEL."kecil_$l[gambar]")): ?>
                                <img width="100%" style="float:left; margin:0 8px 4px 0;" class="img-fluid img-thumbnail" src="<?= base_url("desa/upload/artikel/kecil_$l[gambar]")?>"/>
                            <?php else: ?>
                                <img width="100%" style="float:left; margin:0 8px 4px 0;" class="img-fluid img-thumbnail" src="<?= base_url("assets/images/404-image-not-found.jpg")?>"/>
                            <?php endif;?>
                        </td><td width="2%"></td><td valign="top">
                            <small><span class="meta_date"><font color="green"><?= tgl_indo2($l['tgl_upload']) ?></font></span><br><?= $l['judul']?></a></small>
                    </td></tr>
                  <?php endforeach; ?>
                </table>
              </div>
            </div>
          </div>