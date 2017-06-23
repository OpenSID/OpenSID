<div id="pageC">
<!-- Start of Space Admin -->
  <table class="inner">
  <tr style="vertical-align:top">
    <td style="background:#fff;padding:0px;">
      <div class="content-header">
      </div>
      <div id="contentpane">
        <div class="ui-layout-north panel">
          <h3>Import Data Buku Induk Penduduk</h3>
        </div>
        <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
          <div class="left">
              <!--Impor data BIP-->
              <form action="<?php echo $form_action?>" method="post" enctype="multipart/form-data" id="excell">
               <table class="form">
                <tr>
                  <td width="500" colspan="3">
                    <p font-size="14px";>
                      Proses ini untuk mengimpor data Buku Induk Penduduk (BIP) yang diperoleh dari Disdukcapil dalam format Excel.
                      <br>
                      BIP yang dapat dibaca proses ini adalah yang tersusun
                      berdasarkan keluarga, seperti contoh yang dapat dilihat pada tautan berikut</a>.
                      <br><br>
                      UNDUH : <a class="uibutton confirm" href="<?php echo base_url()?>assets/import/format_bip_2012.xls"><span class="fa fa-download"></span> Contoh BIP 2012</a>
                      <a class="uibutton confirm" href="<?php echo base_url()?>assets/import/format_bip_2016.xls"><span class="fa fa-download"></span> Contoh BIP 2016</a>
                      <br><br>
                      Proses ini mengimpor data keluarga di semua worksheet di berkas BIP. Misalnya, apabila data BIP tersusun menjadi satu worksheet per dusun, proses ini akan mengimpor  data semua dusun.
                    </p><br>
                    <div class="box-perhatian">
                      <strong>Pastikan berkas BIP format Excel 2003, ber-ekstensi .xls <br><br>
                      Sebelum di-impor ganti semua format tanggal (seperti tanggal lahir) menjadi dd/mm/yyyy (misalnya 26/07/1964).</strong>
                    </div>
                    <div style='margin-top: 1em;'>
                      <?php
                      $max_upload = (int)(ini_get('upload_max_filesize'));
                      $max_post = (int)(ini_get('post_max_size'));
                      $memory_limit = (int)(ini_get('memory_limit'));
                      $upload_mb = min($max_upload, $max_post, $memory_limit);
                      echo "<p>Batas maksimal pengunggahan berkas <strong>".$upload_mb." MB.</strong></p><br>
                      <p>Proses ini akan membutuhkan waktu beberapa menit, menyesuaikan dengan spesifikasi
                      komputer server SID, banyaknya data dan sambungan internet yang tersedia.</p>";

                      ?>
                    </div>

                  </td>
                  <td>
                  &nbsp;
                  </td>
                </tr>
                <tr>
                  <td width="150">
                    Pilih File .xls:
                  </td>
                  <td width="250">
                    <input name="userfile" type="file" />
                  <td>
                    <a href="#" onclick="document.getElementById('excell').submit();" class="uibutton special" value="Import" target="confirm2" message="Harap tunggu sampai proses import selesai. Prosses ini biasa memakan waktu antara 1 (satu) Menit hingga 45 Menit, tergantung kecepatan komputer dan juga jumlah data penduduk yang di masukkan.<div align='center'><img src='<?php echo base_url()?>assets/images/background/loading.gif'></div>" header="Proses Import Sedang Berjalan."><span class="fa fa-upload"></span> Import</a>
                    <input type="checkbox" name="hapus_data" value='hapus' /> Hapus data penduduk sebelum import
                  </td>
                  <td>
                    &nbsp;
                  </td>
                </tr>
              <?php if(isset($_SESSION['gagal'])){?>
                <tr>
                  <td width="150">
                  <p>Jumlah Data Penduduk Gagal
                  </td>
                  <td colspan="3">
                    <?php echo $_SESSION['gagal']?>
                  </td>
                </tr>
                <tr>
                  <td width="150">
                  <p>Letak Baris Data Gagal:
                  </td>
                  <td colspan="3">
                    <?php echo $_SESSION['baris']?>
                  </td>
                </tr>
                <tr>
                  <td width="150">
                  <p>Total Data Penduduk Berhasil:
                  </td>
                  <td colspan="3">
                    <?php echo $_SESSION['total_penduduk']?>
                  </td>
                </tr>
                <tr>
                  <td width="150">
                  <p>Total Data Keluarga Berhasil:
                  </td>
                  <td colspan="3">
                    <?php echo $_SESSION['total_keluarga']?>
                  </td>
                </tr>
              <?php }?>
              </table>
            </form>
              <!--Impor data BIP-->

          </div>
        <div class="ui-layout-south panel bottom">
          <div class="left">
            <div class="table-info"></div>
        </div>
        <div class="right">
        </div>
      </div>
    </div>
  </td></tr></table>
</div>

<?php unset($_SESSION['sukses']);?>
<?php unset($_SESSION['baris']);?>
<?php unset($_SESSION['gagal']);?>
