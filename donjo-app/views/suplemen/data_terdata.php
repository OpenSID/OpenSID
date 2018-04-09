
<style type="text/css">
  table.form th.indented {
    padding-left: 40px;
    font-weight: normal;
  }
</style>

<div id="pageC">
<table class="inner">
  <tr style="vertical-align:top">
    <td class="side-menu">
    <?php
    $this->load->view('suplemen/menu_kiri.php')
    ?>
    </td>
    <td class="contentpane">
      <div id="contentpane">
        <div class="ui-layout-center" id="maincontent">
          <legend>Data Terdata Suplemen</legend>

          <div>
            <legend style="margin-top: 30px;">Rincian Suplemen</legend>
            <table class="form">
              <tr><td width="30%">Nama Suplemen</td><td><strong><?php echo strtoupper($suplemen["nama"])?></strong></td></tr>
              <tr><td>Sasaran Suplemen</td><td><strong><?php echo $sasaran[$suplemen["sasaran"]]?></strong></td></tr>
              <tr><td>Keterangan</td><td><strong><?php echo $suplemen["keterangan"]?></strong></td></tr>
            </table>
          </div>

          <legend style="margin-top: 30px;">Data Terdata</legend>
          <div style="margin-top: 10px;">

            <div id="form-cari-peserta">
                <table class="form">
                  <tr>
                    <?php if($suplemen["sasaran"] == 1): ?>
                      <th width="30%">NIK / Nama</td>
                    <?php elseif($suplemen["sasaran"] == 2): ?>
                      <th width="30%">No. KK / Nama KK</td>
                    <?php endif; ?>
                    <td>
                      <?php echo $terdata["terdata_nama"]." / ".$terdata["terdata_info"]; ?>
                    </td>
                  </tr>
                  <?php if($individu){?>
                    <?php include("donjo-app/views/suplemen/konfirmasi_terdata.php"); ?>
                  <?php } ?>
                  <tr>
                    <th>Keterangan</th>
                    <td><?php echo $terdata["keterangan"]; ?></td>
                  </tr>
                </table>
            </div>
          </div>

        </div>

        <div class="ui-layout-south panel bottom">
          <div class="left">
            <a href="<?php echo site_url()?>suplemen/rincian/1/<?php echo $suplemen['id']?>" class="uibutton icon prev">Kembali</a>
          </div>
        </div>

      </div>
    </td>
  </tr>
</table>
</div>

