
  <script>
    $(document).ready(function() {
      var nik = {};
      nik.results = [
        <?php foreach($list_sasaran as $item){
          if(strlen($item["id"])>0)?>
          {id:"<?php echo $item['id']?>",name:"<?php echo $item['nama']?>",info:"<?php echo ($item['info'])?>"},
        <?php  }?>
      ];

      $('#nik').flexbox(nik, {
        resultTemplate: '<div><label>No nik : </label>{name}</div><div>{info}</div>',
        watermark: <?php  if($individu){?>'<?php echo $individu['nik']?> - <?php echo ($individu['nama'])?>'<?php  }else{?>'Cari nama di sini..'<?php  }?>,
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
    $this->load->view('suplemen/menu_kiri.php')
    ?>
    </td>
    <td style="background:#fff;padding:0px;">
      <div id="contentpane">
        <div class="ui-layout-center" id="maincontent">
          <legend>Formulir Penambahan Terdata</legend>

          <div>
            <legend style="margin-top: 30px;">Rincian Data Suplemen</legend>
            <table class="form">
              <tr><td width="30%">Nama Data</td><td><strong><?php echo strtoupper($suplemen["nama"])?></strong></td></tr>
              <tr><td>Sasaran Terdata</td><td><strong><?php echo $sasaran[$suplemen["sasaran"]]?></strong></td></tr>
              <tr><td>Keterangan</td><td><strong><?php echo $suplemen["keterangan"]?></strong></td></tr>
            </table>
          </div>

          <legend style="margin-top: 30px;">Tambahkan Warga Terdata</legend>
          <div style="margin-top: 10px;">

            <div id="form-cari-peserta">
              <form action="" id="main" name="main" method="POST" class="formular">
                <label>Cari Nama Terdata dari Database Desa</label>
                <table class="form">
                  <colgroup>
                    <col style="width: 30%">
                    <col>
                  </colgroup>
                  <tr>
                    <?php if($suplemen["sasaran"] == 1): ?>
                      <th>NIK / Nama</td>
                      <td>
                        <div id="nik" name="nik"></div>
                      </td>
                    <?php elseif($suplemen["sasaran"] == 2): ?>
                      <th>No. KK / Nama KK</td>
                      <td>
                        <div id="nik" name="nik"></div>
                      </td>
                    <?php endif; ?>
                  </tr>
                </table>
              </form>
            </div>

            <div id="form-melengkapi-data-peserta">
              <form id="validasi" action="<?php echo $form_action?>/<?php echo $suplemen['id']?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="nik" value="<?php echo $individu['nik']?>" class="inputbox required" >
                <table class="form">
                  <colgroup>
                    <col style="width: 30%">
                    <col>
                  </colgroup>
                  <?php if($individu){?>
                    <?php include("donjo-app/views/suplemen/konfirmasi_terdata.php"); ?>
                  <?php }?>
                  <tr>
                    <th>Keterangan</th>
                    <td><input name="keterangan" size="100"></td>
                  </tr>
                </table>
              </form>
            </div>
          </div>

        </div>

        <div class="ui-layout-south panel bottom">
          <div class="left">
            <a href="<?php echo site_url()?>suplemen/rincian/1/<?php echo $suplemen['id']?>" class="uibutton icon prev">Kembali</a>
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

