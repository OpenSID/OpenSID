<script type="text/javascript">
  function kosongkan(key){
    $("[name='setting["+key+"][baris]']").val('');
    $("[name='setting["+key+"][kolom]']").val('');
    $("[name='setting["+key+"][judul]']").val('');
    $("[name='setting["+key+"][tautan]']").val('');
    $("[name='setting["+key+"][gambar]']").val('');
    $("[name='setting["+key+"][old_gambar]']").val('');
  }
</script>

<div id="pageC">
  <table class="inner">
  <tr style="vertical-align:top">
    <td>
      <div id="contentpane">
        <form id="validasi" action="<?php echo $form_action ?>" method="POST" enctype="multipart/form-data">
          <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
            <h3>Pengaturan Sinergi Program</h3>
            <table class="form list">
              <tr>
                <th>Aksi</th>
                <th>Baris</th>
                <th>Kolom</th>
                <th>Judul</th>
                <th>Gambar</th>
                <th>Tautan</th>
              </tr>
              <?php $kosong = 20 - count($setting);
                    $s = 0;?>
              <?php foreach($setting as $program): ?>
                <?php $s++; ?>
                <tr>
                  <td>
                    <div class="uibutton-group">
                      <a href="#" class="uibutton tipsy south" title="Kosongkan"><span class="fa fa-refresh" onclick="kosongkan(<?php echo $s; ?>)"></span></a>
                    </div>
                  </td>
                  <td>
                    <select name="setting[<?php echo $s?>][baris]" class="">
                      <option value="">Pilih Baris</option>
                      <?php for($i=1; $i<11; $i++){?>
                        <option value="<?php echo $i;?>" <?php if($program['baris']==$i){?>selected<?php }?>><?php echo $i;?></option>
                      <?php }?>
                    </select>
                  </td>
                  <td>
                    <select name="setting[<?php echo $s?>][kolom]" class="">
                      <option value="">Pilih Kolom</option>
                      <?php for($i=1; $i<4; $i++){?>
                        <option value="<?php echo $i;?>" <?php if($program['kolom']==$i){?>selected<?php }?>><?php echo $i;?></option>
                      <?php }?>
                    </select>
                  </td>
                  <td><input name="setting[<?php echo $s?>][judul]" type="text" class="inputbox" size="40" value="<?php echo $program['judul']?>"/></td>
                  <td>
                    <input type="hidden" name="setting[<?php echo $s?>][old_gambar]" value="<?php echo $program['gambar']?>"/>
                    <img src="<?php echo base_url().'desa/upload/widget/'.$program['gambar']?>" alt=""/><br>
                    <input type="file" name="setting[<?php echo $s?>][gambar]"/>
                    <span style="color: #aaa;">(Kosongkan jika tidak ingin mengubah gambar)</span>
                  </td>
                  <td><input name="setting[<?php echo $s?>][tautan]" type="text" class="inputbox" size="50" value="<?php echo $program['tautan']?>"/></td>
                </tr>
              <?php endforeach; ?>
              <?php for($s=count($setting)+1; $s <count($setting)+$kosong; $s++): ?>
                <tr>
                  <td>
                    <div class="uibutton-group">
                      <a href="#" class="uibutton tipsy south" title="Kosongkan"><span class="fa fa-refresh" onclick="kosongkan(<?php echo $s; ?>)"></span></a>
                    </div>
                  </td>
                  <td>
                    <select name="setting[<?php echo $s?>][baris]" class="">
                      <option value="">Pilih Baris</option>
                      <?php for($i=1; $i<11; $i++){?>
                        <option value="<?php echo $i;?>"><?php echo $i;?></option>
                      <?php }?>
                    </select>
                  </td>
                  <td>
                    <select name="setting[<?php echo $s?>][kolom]" class="">
                      <option value="">Pilih Kolom</option>
                      <?php for($i=1; $i<4; $i++){?>
                        <option value="<?php echo $i;?>"><?php echo $i;?></option>
                      <?php }?>
                    </select>
                  </td>
                  <td><input name="setting[<?php echo $s?>][judul]" type="text" class="inputbox" size="40" value=""/></td>
                  <td>
                    <input type="file" name="setting[<?php echo $s?>][gambar]"/>
                    <span style="color: #aaa;">(Kosongkan jika tidak ingin mengubah gambar)</span>
                  </td>
                  <td><input name="setting[<?php echo $s?>][tautan]" type="text" class="inputbox" size="50" value=""/></td>
                </tr>
              <?php endfor; ?>
            </table>
          </div>

          <div class="ui-layout-south panel bottom">

            <div class="left">
              <a href="<?php echo site_url()?>web_widget" class="uibutton icon prev">Kembali</a>
            </div>
            <div class="right">
              <div class="uibutton-group">
                <button class="uibutton" type="reset"><span class="fa fa-refresh"></span> Bersihkan</button>
                <button class="uibutton confirm" type="submit" ><span class="fa fa-save"></span> Simpan</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </td>
  </tr>
  </table>
</div>
