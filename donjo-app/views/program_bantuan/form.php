
  <script>
    $(document).ready(function() {
      var nik = {};
      nik.results = [
        <?php  foreach($program[2]as $item){
          if(strlen($item["id"])>0)?>
          {id:"<?php echo $item['id']?>",name:<?php echo json_encode($item['nama'])?>,info:<?php echo json_encode($item['info'])?>},
        <?php  }?>
      ];

      $('#nik').flexbox(nik, {
        resultTemplate: '<div><label>No nik : </label>{name}</div><div>{info}</div>',
        watermark: <?php  if($individu){?> <?php echo json_encode($individu['nik'].' - '.$individu['nama'])?> <?php  }else{?>'Cari nama di sini..'<?php  }?>,
        width: 260,
        noResultsText :'Tidak ada no nik yang sesuai..',
        onSelect: function() {
          $('#'+'main').submit();
      }
      });
    });
  </script>

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
          <legend>Formulir Penambahan Peserta</legend>

          <?php $detail = $program[0];?>
          <div>
            <legend style="margin-top: 30px;">Rincian Program</legend>
            <table class="form">
              <tr><td width="30%">Nama Program</td><td><strong><?php echo strtoupper($detail["nama"])?></strong></td></tr>
              <tr><td>Sasaran Peserta</td><td><strong><?php echo $sasaran[$detail["sasaran"]]?></strong></td></tr>
              <tr><td>Masa Berlaku</td><td><strong><?php echo fTampilTgl($detail["sdate"],$detail["edate"])?></strong></td></tr>
              <tr><td>Keterangan</td><td><strong><?php echo $detail["ndesc"]?></strong></td></tr>
            </table>
          </div>

          <legend style="margin-top: 30px;">Tambahkan Peserta</legend>
          <div style="margin-top: 10px;">

            <div id="form-cari-peserta">
              <form action="" id="main" name="main" method="POST" class="formular">
                <label>Cari Nama Peserta dari Database Desa</label>
                <table class="form">
                  <tr>
                    <?php if($detail["sasaran"] == 1): ?>
                      <td width="30%">NIK / Nama</td>
                      <td>
                        <div id="nik" name="nik"></div>
                      </td>
                    <?php elseif($detail["sasaran"] == 2): ?>
                      <td width="30%">No. KK / Nama KK</td>
                      <td>
                        <div id="nik" name="nik"></div>
                      </td>
                    <?php elseif($detail["sasaran"] == 3): ?>
                      <td width="30%">No. Rumah Tangga / Nama Kepala Rumah Tangga</td>
                      <td>
                        <div id="nik" name="nik"></div>
                      </td>
                    <?php elseif($detail["sasaran"] == 4): ?>
                      <td width="30%">Nama Kelompok / Nama Ketua Kelompok</td>
                      <td>
                        <div id="nik" name="nik"></div>
                      </td>
                    <?php endif; ?>
                  </tr>
                </table>
              </form>
            </div>

            <div id="form-melengkapi-data-peserta">
              <form id="validasi" action="<?php echo $form_action?>/<?php echo $detail['id']?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="nik" value="<?php echo $individu['nik']?>" class="inputbox required" >
                <table class="form">
                  <?php if($individu){?>
                    <?php include("donjo-app/views/program_bantuan/konfirmasi_peserta.php"); ?>
                  <?php
                  }
                  ?>
                  <tr>
                    <th width="30%">Nomor Kartu Peserta</th>
                    <td width="70%"><input name="no_id_kartu" type="text" class="inputbox required" size="12"/></td>
                  </tr>
                  <tr>
                    <th>Gambar Kartu Peserta</th>
                    <td>
                      <input type="file" name="satuan" /> <span style="color: #aaa;">(Kosongkan jika tidak ingin mengunggah gambar)</span>
                    </td>
                  </tr>
                  <tr>
                    <th colspan="2">Identitas Pada Kartu Peserta</th>
                  </tr>
                  <tr>
                    <th class="indented">NIK</th>
                    <td>
                      <input name="kartu_nik" type="text" class="inputbox" size="30"/>
                    </td>
                  </tr>
                  <tr>
                    <th class="indented">Nama</th>
                    <td>
                      <input name="kartu_nama" type="text" class="inputbox" size="60"/>
                    </td>
                  </tr>
                  <tr>
                    <th class="indented"> Tempat Lahir</th>
                    <td>
                      <input name="kartu_tempat_lahir" type="text" class="inputbox" size="65" />
                    </td>
                  </tr>
                  <tr>
                    <th class="indented">Tanggal Lahir</th>
                    <td>
                      <input name="kartu_tanggal_lahir" type="text" class="inputbox datepicker" size="20"/>
                    </td>
                  </tr>
                  <tr>
                    <th class="indented">Alamat</th>
                    <td>
                      <input name="kartu_alamat" type="text" class="inputbox" size="60"/>
                    </td>
                  </tr>
                </table>
              </form>
            </div>
          </div>

        </div>

        <div class="ui-layout-south panel bottom">
          <div class="left">
            <a href="<?php echo site_url()?>program_bantuan/detail/1/<?php echo $detail['id']?>" class="uibutton icon prev">Kembali</a>
          </div>
          <div class="right">
              <div class="uibutton-group">
                  <button class="uibutton confirm" type="submit" onclick="$('#'+'validasi').submit();"><span class="fa fa-save"></span> Simpan</button>
              </div>
          </div>
        </div>

      </div>
    </td>
  </tr>
</table>
</div>

