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
  table#data_peserta img {
    width: 200px;
  }
</style>

  <strong>Data Terdata</strong>
  <table style="margin-bottom: 10px;">
    <tr>
      <td><?php echo $judul_terdata_nama?> : </td>
      <td><?php echo $terdata_nama?></td>
    </tr>
    <tr>
      <td><?php echo $judul_terdata_info?> : </td>
      <td><?php echo $terdata_info?></td>
    </tr>
  </table>

  <form action="<?php echo $form_action?>" method="post" id="validasi" enctype="multipart/form-data">
    <input type="hidden" name="id_suplemen" value="<?php echo $id_suplemen?>"/>

    <strong>Ubah Data Terdata</strong>
    <table id="data_peserta" class="form">
      <tr>
        <td style="padding-right: 5px; white-space: nowrap;">Keterangan</td>
        <td>
          <input name="keterangan" type="text" class="inputbox" size="50" value="<?php echo $keterangan?>"/>
        </td>
      </tr>
    </table>

    <div class="buttonpane" style="text-align: right;">
        <div class="uibutton-group">
            <button class="uibutton" type="button" onclick="$('#window').dialog('close');"><span class="fa fa-times"></span> Tutup</button>
            <button class="uibutton confirm" type="submit"><span class="fa fa-save"></span> Simpan</button>
        </div>
    </div>
  </form>
