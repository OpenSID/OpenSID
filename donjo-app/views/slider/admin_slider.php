<style type="text/css">
  #pilihan_sumber label { width: 140px; margin-right: 10px;}
</style>

<div id="pageC">
<!-- Start of Space Admin -->
  <table class="inner">
  <tr style="vertical-align:top">
    <td style="background:#fff;padding:0px;">
      <div class="content-header">
      </div>
      <div id="contentpane">
        <div class="ui-layout-north panel">
          <h3>Pengaturan Slider Besar</h3>
        </div>
        <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
          <div class="left">
              <!--Impor data BIP-->
              <form id="mainform" action="<?php echo site_url('web/update_slider')?>" method="post">
               <table class="form">
                <tr>
                  <td width="500" colspan="3">
                    <p font-size="14px";>
                      Pilih sumber gambar untuk ditampilkan di slider besar:
                    </p>
                  </td>
                  <td>
                  &nbsp;
                  </td>
                </tr>
                <tr>
                  <td>
                    <div class="uiradio" id="pilihan_sumber">
                      <input type="radio" id="sumber1" name="pilihan_sumber" value="1"/<?php  if($this->setting->sumber_gambar_slider == '1' OR empty($this->setting->sumber_slider)){echo 'checked';}?>>
                      <label for="sumber1">Artikel Terbaru</label>
                      <span>10 gambar utama artikel terbaru</span><br>

                      <input type="radio" id="sumber2" name="pilihan_sumber" value="2"/<?php  if($this->setting->sumber_gambar_slider == '2'){echo 'checked';}?>>
                      <label for="sumber2">Artikel Terbaru Pilihan</label>
                      <span>10 gambar utama artikel terbaru yang masuk ke slider atas</span><br>

                      <input type="radio" id="sumber3" name="pilihan_sumber" value="3"/<?php  if($this->setting->sumber_gambar_slider == '3'){echo 'checked';}?>>
                      <label for="sumber3">Album Galeri</label>
                      <span>Gambar dalam album galeri yang dimasukkan ke slider</span>
                    </div>
                  </td>
                </tr>
              </table>
            </form>

          </div>
        </div>
        <div class="ui-layout-south panel bottom">
          <div class="right">
            <div class="uibutton-group">
              <button class="uibutton confirm" type="submit" onclick="$('#'+'mainform').submit();"><span class="fa fa-save"></span> Simpan</button>
            </div>
          </div>
        </div>

  </td></tr></table>
</div>
