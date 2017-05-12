
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
    $this->load->view('program_bantuan/menu_kiri.php')
    ?>
    </td>
    <td class="contentpane">
      <div id="contentpane">
        <div class="ui-layout-center" id="maincontent">
          <legend>Data Peserta Program Bantuan</legend>

          <div>
            <legend style="margin-top: 30px;">Rincian Program</legend>
            <table class="form">
              <tr><td width="30%">Nama Program</td><td><strong><?php echo strtoupper($detail["nama"])?></strong></td></tr>
              <tr><td>Sasaran Peserta</td><td><strong><?php echo $sasaran[$detail["sasaran"]]?></strong></td></tr>
              <tr><td>Masa Berlaku</td><td><strong><?php echo fTampilTgl($detail["sdate"],$detail["edate"])?></strong></td></tr>
              <tr><td>Keterangan</td><td><strong><?php echo $detail["ndesc"]?></strong></td></tr>
            </table>
          </div>

          <legend style="margin-top: 30px;">Data Peserta</legend>
          <div style="margin-top: 10px;">

            <div id="form-cari-peserta">
                <table class="form">
                  <tr>
                    <?php if($detail["sasaran"] == 1): ?>
                      <th width="30%">NIK / Nama</td>
                    <?php elseif($detail["sasaran"] == 2): ?>
                      <th width="30%">No. KK / Nama KK</td>
                    <?php elseif($detail["sasaran"] == 3): ?>
                      <th width="30%">No. Rumah Tangga / Nama Kepala Rumah Tangga</td>
                    <?php elseif($detail["sasaran"] == 4): ?>
                      <th width="30%">Nama Kelompok / Nama Ketua Kelompok</td>
                    <?php endif; ?>
                    <td>
                      <?php echo $peserta["peserta_nama"]." / ".$peserta["peserta_info"]; ?>
                    </td>
                  </tr>
                </table>
            </div>

            <div id="form-melengkapi-data-peserta">
                <table class="form">
                  <?php if($individu){?>
                    <?php include("donjo-app/views/program_bantuan/konfirmasi_peserta.php"); ?>
                  <?php
                  }
                  ?>
                  <tr>
                    <th width="30%">Nomor Kartu Peserta</th>
                    <td width="70%">
                      <?php echo $peserta["no_id_kartu"] ?>
                    </td>
                  </tr>
                  <tr>
                    <th colspan="2">Identitas Pada Kartu Peserta</th>
                  </tr>
                  <tr>
                    <th class="indented">NIK</th>
                    <td>
                      <?php echo $peserta["kartu_nik"] ?>
                    </td>
                  </tr>
                  <tr>
                    <th class="indented">Nama</th>
                    <td>
                      <?php echo $peserta["kartu_nama"] ?>
                    </td>
                  </tr>
                  <tr>
                    <th class="indented"> Tempat Lahir</th>
                    <td>
                      <?php echo $peserta["kartu_tempat_lahir"] ?>
                    </td>
                  </tr>
                  <tr>
                    <th class="indented">Tanggal Lahir</th>
                    <td>
                      <?php echo tgl_indo($peserta["kartu_tanggal_lahir"]) ?>
                    </td>
                  </tr>
                  <tr>
                    <th class="indented">Alamat</th>
                    <td>
                      <?php echo $peserta["kartu_alamat"] ?>
                    </td>
                  </tr>
                </table>
            </div>
          </div>

        </div>

        <div class="ui-layout-south panel bottom">
          <div class="left">
            <a href="<?php echo site_url()?>program_bantuan/detail/1/<?php echo $detail['id']?>" class="uibutton icon prev">Kembali</a>
          </div>
        </div>

      </div>
    </td>
  </tr>
</table>
</div>

