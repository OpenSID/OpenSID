
<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;">
<div id="contentpane">
<form id="validasi" action="<?php echo $form_action?>" method="POST" enctype="multipart/form-data">
  <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
    <h3>Form Widget</h3>
    <table class="form">
      <tr>
        <th width="120">Judul Widget</th>
        <td><input class="inputbox" type="text" name="judul" value="<?php echo $artikel['judul']?>" size="60"/></td>
      </tr>
      <tr>
        <th width="120">Jenis Widget</th>
        <td>
          <select id="jenis_widget" name="jenis_widget">
            <option value="">-- Pilih Jenis Widget --</option>
            <option value="2" <?php if($artikel['jenis_widget'] == 2) :?>selected<?php endif?>>Statis</option>
            <option value="3" <?php if($artikel['jenis_widget'] == 3) :?>selected<?php endif?>>Dinamis</option>
          </select>
        </td>
      </tr>
    </table>
    <?php if($artikel['jenis_widget'] AND $artikel['jenis_widget'] != 1 AND $artikel['jenis_widget'] !=2) $dinamis = true; ?>
    <table id="dinamis" class="form" <?php if(!$dinamis) echo 'style="display:none;"'?>>
      <tr>
        <th width="120" colspan="2">Kode Widget</th>
      </tr>
      <tr>
        <td colspan="2">
          <textarea  name="isi-dinamis" style="width: 500px; height: 300px;">
            <?php echo $artikel['isi']?>
          </textarea>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          &nbsp;
        </td>
      </tr>
      <tr>
        <th class="top">Gambar</th>
        <td>
          <?php if($artikel['gambar']){?>
            <div class="gallerybox-avatar">
              <img src="<?php echo AmbilFotoArtikel($artikel['gambar'],'kecil')?>" alt="" width="200"/>
            </div>
          <?php }?>
        </td>
      </tr>
      <tr>
        <th>Dokumen Lampiran</th>
        <td><input type="file" name="dokumen" /> <span style="color: #aaa;"></span></td>
        </tr>
      <tr>
        <th>Nama Dokumen (Nantinya akan menjadi link unduh/download)</th>
        <td><input size="30" type="text" name="link_dokumen" value="<?php echo $artikel['link_dokumen']?>"/></td>
      </tr>
      <tr>
        <th>Unggah/Upload Gambar Utama</th>
        <td><input type="file" name="gambar" /> <span style="color: #aaa;">(Kosongi jika tidak ingin mengubah gambar)</span></td>
      </tr>
      <tr>
        <th class="top">Gambar</th>
        <td>
          <?php if($artikel['gambar1']){?>
            <div class="gallerybox-avatar">
              <img src="<?php echo AmbilFotoArtikel($artikel['gambar1'],'kecil')?>" alt="" width="200"/>
            </div>
          <?php }?>
        </td>
      </tr>
      <tr>
        <th>Gambar Tambahan</th>
        <td><input type="file" name="gambar1" /> <span style="color: #aaa;">(Kosongi jika tidak ingin mengubah gambar)</span></td>
      </tr>
      <tr>
        <th class="top">Gambar</th>
        <td>
          <?php if($artikel['gambar2']){?>
            <div class="gallerybox-avatar">
              <img src="<?php echo AmbilFotoArtikel($artikel['gambar2'],'kecil')?>" alt="" width="200"/>
            </div>
          <?php }?>
        </td>
      </tr>
      <tr>
        <th>Gambar Tambahan</th>
        <td><input type="file" name="gambar2" /> <span style="color: #aaa;">(Kosongi jika tidak ingin mengubah gambar)</span></td>
      </tr>
      <tr>
        <th class="top">Gambar</th>
        <td>
          <?php if($artikel['gambar3']){?>
            <div class="gallerybox-avatar">
              <img src="<?php echo AmbilFotoArtikel($artikel['gambar3'],'kecil')?>" alt="" width="200"/>
            </div>
          <?php }?>
        </td>
      </tr>
      <tr>
        <th>Gambar Tambahan</th>
        <td><input type="file" name="gambar3" /> <span style="color: #aaa;">(Kosongi jika tidak ingin mengubah gambar)</span></td>
      </tr>
    </table>

    <?php if($artikel['jenis_widget'] AND $artikel['jenis_widget'] ==2) $statis = true; ?>
    <table id="statis" class="form" <?php if(!$statis) echo 'style="display:none;"'?>>
      <tr>
        <th width="120">Nama File Widget (.php)</th>
        <td>
          <input class="inputbox" type="text" name="isi-statis" value="<?php echo $artikel['isi']?>" size="60" />
        </td>
      </tr>
    </table>

  </div>

  <div class="ui-layout-south panel bottom">
    <div class="left">
      <a href="<?php echo site_url()?>web/widget/1" class="uibutton icon prev">Kembali</a>
    </div>
    <div class="right">
      <div class="uibutton-group">
        <button class="uibutton" type="reset">Clear</button>
        <button class="uibutton confirm" type="submit" >Simpan</button>
      </div>
    </div>
  </div>
</form>
</div>
</td></tr></table>
</div>

<script>
  var elem = document.getElementById("jenis_widget");
  elem.onchange = function(){
      var dinamis = document.getElementById("dinamis");
      var statis = document.getElementById("statis");
      dinamis.style.display = (this.value == "3") ? "block":"none";
      statis.style.display = (this.value == "2") ? "block":"none";
  };
</script>
