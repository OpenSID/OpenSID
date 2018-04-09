<div id="pageC" class = "kode-isian">
  <div class="content-header">
  </div>
  <div id="contentpane">
    <div class="ui-layout-north panel"><h3>Kode Isian Form Surat <?php echo $surat_master['nama']?></h3></div>
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
      <p>
        Kode isian pada tabel di bawah dapat dimasukkan ke dalam template RTF Export Doc untuk jenis surat ini.
      </p><p>
        Pada waktu mencetak surat Export Doc memakai template itu, kode isian di bawah akan diganti
        dengan data yang diisi pada form isian untuk jenis surat ini.
      </p>
      <table class="list">
        <thead>
          <tr>
            <th>Kode</th>
            <th>Data di Form Isian</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($inputs as $kode => $keterangan) {?>
            <tr>
              <td>[form_<?php echo $kode?>]</td>
              <td><?php echo $keterangan?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
    <div class="ui-layout-south panel bottom">
      <div class="left">
      <a href="<?php echo site_url()?>surat_master" class="uibutton icon prev">Kembali</a>
      </div>
    </div>
  </div>
</div>
