
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
        <td><input class="inputbox" type="text" name="judul" value="<?php echo $widget['judul']?>" size="60"/></td>
      </tr>
      <tr>
        <th width="120">Jenis Widget</th>
        <td>
          <select id="jenis_widget" name="jenis_widget">
            <option value="">-- Pilih Jenis Widget --</option>
            <option value="2" <?php if($widget['jenis_widget'] == 2) :?>selected<?php endif?>>Statis</option>
            <option value="3" <?php if($widget['jenis_widget'] == 3) :?>selected<?php endif?>>Dinamis</option>
          </select>
        </td>
      </tr>
    </table>

    <?php if($widget['jenis_widget'] AND $widget['jenis_widget'] != 1 AND $widget['jenis_widget'] !=2) $dinamis = true; ?>

    <table id="dinamis" class="form" <?php if(!$dinamis) echo 'style="display:none;"'?>>
      <tr>
        <th width="120" colspan="2">Kode Widget</th>
      </tr>
      <tr>
        <td colspan="2">
          <textarea  name="isi-dinamis" style="width: 500px; height: 300px;">
            <?php echo $widget['isi']?>
          </textarea>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          &nbsp;
        </td>
      </tr>
    </table>

    <?php if($widget['jenis_widget'] AND $widget['jenis_widget'] ==2) $statis = true; ?>
    <table id="statis" class="form" <?php if(!$statis) echo 'style="display:none;"'?>>
      <tr>
        <th width="120">Nama File Widget (.php)</th>
        <td>
          <input class="inputbox" type="text" name="isi-statis" value="<?php echo $widget['isi']?>" size="60" />
        </td>
      </tr>
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
