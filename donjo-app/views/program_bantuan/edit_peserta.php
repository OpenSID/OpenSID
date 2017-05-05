<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/donjoscript/donjoscript2.js"></script>

<style type="text/css">
  table#data_peserta {
    text-align: left;
  }
  table#data_peserta th.indented {
    font-weight: normal;
    padding-left: 20px;
  }
</style>

  <strong>Data Peserta</strong>
  <table style="margin-bottom: 10px;">
    <tr>
      <td><?php echo $judul_peserta?> : </td>
      <td><?php echo $peserta_nama?></td>
    </tr>
    <tr>
      <td><?php echo $judul_peserta_info?> : </td>
      <td><?php echo $peserta_info?></td>
    </tr>
  </table>

  <form action="<?php echo $form_action?>" method="post" id="validasi">
    <input type="hidden" name="program_id" value="<?php echo $program_id?>"/>

    <strong>Ubah Data Peserta</strong>
    <table id="data_peserta">
      <tr>
        <td style="padding-right: 5px; white-space: nowrap;">Nomor Kartu Peserta</td>
        <td>
          <input name="no_id_kartu" type="text" class="inputbox" size="50" value="<?php echo $no_id_kartu?>"/>
        </td>
      </tr>
      <tr>
        <th colspan="2" style="padding-top: 5px; font-weight: normal;">Identitas Pada Kartu Peserta :</th>
      </tr>
      <tr>
        <th class="indented">NIK</th>
        <td>
          <input name="kartu_nik" type="text" class="inputbox" size="50" value="<?php echo $kartu_nik?>"/>
        </td>
      </tr>
      <tr>
        <th class="indented">Nama</th>
        <td>
          <input name="kartu_nama" type="text" class="inputbox" size="50" value="<?php echo $kartu_nama?>"/>
        </td>
      </tr>
      <tr>
        <th class="indented"> Tempat Lahir</th>
        <td>
          <input name="kartu_tempat_lahir" type="text" class="inputbox" size="50" value="<?php echo $kartu_tempat_lahir?>"/>
        </td>
      </tr>
      <tr>
        <th class="indented">Tanggal Lahir</th>
        <td>
          <input name="kartu_tanggal_lahir" type="text" class="inputbox datepicker" size="20" value="<?php echo date_format(date_create($kartu_tanggal_lahir),"d-m-Y")?>"/>
        </td>
      </tr>
      <tr>
        <th class="indented">Alamat</th>
        <td>
          <input name="kartu_alamat" type="text" class="inputbox" size="50" value="<?php echo $kartu_alamat?>"/>
        </td>
      </tr>
    </table>

    <div class="buttonpane" style="text-align: right;">
        <div class="uibutton-group">
            <button class="uibutton" type="button" onclick="$('#window').dialog('close');">Tutup</button>
            <button class="uibutton confirm" type="submit">Simpan</button>
        </div>
    </div>
  </form>
