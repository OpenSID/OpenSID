<style type="text/css">
  table#data_peserta {
    text-align: left;
    margin-bottom: 10px;
  }
  table#data_peserta th.indented {
    padding-left: 20px;
  }
  table#gambar_peserta img {
    max-width: 400px;
  }

  table.list {
      width: 100%;
      border-collapse: collapse;
      border: 1px solid #e5e5e5;
  }
  table.list tr {
      border-bottom: 1px solid #dAe6f3;
      background: #F5F9Fe;
  }
  table.list tr:nth-child(odd) {
      background: #ffffff;
  }
  table.list th {
      width: 50px;
      padding: 4px 5px;
  }
  table.list td {
    padding-left: 10px;
  }

</style>
<?php if($kartu_peserta): ?>
  <div id="gambar_peserta" class="gallerybox-avatar">
    <img src="<?php echo AmbilDokumen($kartu_peserta)?>" alt=""/>
  </div>
<?php else: ?>

    <table id="data_peserta" class="list">
      <tr>
        <th style="padding-right: 10px; white-space: nowrap;">Nomor Kartu Peserta</th>
        <td>
          <?php echo $no_id_kartu?>
        </td>
      </tr>
      <tr>
        <th colspan="2" style="padding-top: 5px;">Identitas Pada Kartu Peserta :</th>
      </tr>
      <tr>
        <th class="indented">NIK</th>
        <td>
          <?php echo $kartu_nik?>
        </td>
      </tr>
      <tr>
        <th class="indented">Nama</th>
        <td>
          <?php echo $kartu_nama?>
        </td>
      </tr>
      <tr>
        <th class="indented"> Tempat Lahir</th>
        <td>
          <?php echo $kartu_tempat_lahir?>
        </td>
      </tr>
      <tr>
        <th class="indented">Tanggal Lahir</th>
        <td>
          <?php echo tgl_indo(date($kartu_tanggal_lahir))?>
        </td>
      </tr>
      <tr>
        <th class="indented">Alamat</th>
        <td>
          <?php echo $kartu_alamat?>
        </td>
      </tr>
    </table>
<?php endif;?>

<div class="buttonpane" style="text-align: right;">
    <div class="uibutton-group">
        <button class="uibutton" type="button" onclick="$('#kartu_peserta').dialog('close');"><span class="fa fa-times"></span> Tutup</button>
    </div>
</div>

