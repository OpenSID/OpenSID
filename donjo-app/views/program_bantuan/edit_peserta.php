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
    <table>
      <tr>
        <td style="padding-right: 5px">Nomor Kartu Peserta</td>
        <td>
          <input name="no_id_kartu" type="text" class="inputbox" size="30" value="<?php echo $no_id_kartu?>"/>
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
