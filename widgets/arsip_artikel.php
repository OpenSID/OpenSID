<!-- widget Arsip Artikel -->
    <div class="single_bottom_rightbar">
        <h2><i class="fa fa-archive"></i> Arsip Artikel</h2>
            <ul role="tablist" class="nav nav-tabs custom-tabs">
              <li class="active" role="presentation"><a data-toggle="tab" role="tab" aria-controls="home" href="#recentComent">Terbaru</a></li>
              <li role="presentation"><a data-toggle="tab" role="tab" aria-controls="messages" href="#random">Acak</a></li>
            </ul>
            <div class="tab-content">
              <div id="recentComent" class="tab-pane fade in active" role="tabpanel">
                  <table id="ul-menu">
                  <?php foreach ($arsip as $l): ?>
                    <tr><td colspan="2">
                            <span class="meta_date"><?= tgl_indo2($l['tgl_upload']);?>&nbsp;
                            <i class="fa fa-eye"></i> <?= hit($l['hit']) ?> Dibaca</span>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" align="justify">
                            <a href="<?= site_url("first/artikel/$l[id]")?>">
                            <?php if (is_file(LOKASI_FOTO_ARTIKEL."sedang_$l[gambar]")): ?>
                                <img width="25%" style="float:left; margin:0 8px 4px 0;" class="img-fluid img-thumbnail" src="<?= base_url("desa/upload/artikel/sedang_$l[gambar]")?>"/>
                            <?php else: ?>
                                <img width="25%" style="float:left; margin:0 8px 4px 0;" class="img-fluid img-thumbnail" src="<?= base_url("assets/images/404-image-not-found.jpg")?>"/>
                            <?php endif;?>
                            <small><font color="green"><?= $l['judul']?></font></small>
                            </a>
                        </td>
                    </tr>
                  <?php endforeach; ?>
                </table>
              </div>
              <div id="random" class="tab-pane fade" role="tabpanel">
                <table id="ul-menu">
                  <?php foreach ($arsip_rand as $l): ?>
                    <tr><td colspan="2">
                            <span class="meta_date"><?= tgl_indo2($l['tgl_upload']);?>&nbsp;
                            <i class="fa fa-eye"></i> <?= hit($l['hit']) ?> Dibaca</span>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" align="justify">
                            <a href="<?= site_url("first/artikel/$l[id]")?>">
                            <?php if (is_file(LOKASI_FOTO_ARTIKEL."sedang_$l[gambar]")): ?>
                                <img width="25%" style="float:left; margin:0 8px 4px 0;" class="img-fluid img-thumbnail" src="<?= base_url("desa/upload/artikel/sedang_$l[gambar]")?>"/>
                            <?php else: ?>
                                <img width="25%" style="float:left; margin:0 8px 4px 0;" class="img-fluid img-thumbnail" src="<?= base_url("assets/images/404-image-not-found.jpg")?>"/>
                            <?php endif;?>
                            <small><font color="green"><?= $l['judul']?></font></small>
                            </a>
                        </td>
                    </tr>
                  <?php endforeach; ?>
                </table>
              </div>
            </div>
          </div>