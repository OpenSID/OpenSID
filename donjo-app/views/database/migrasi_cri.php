<div id="pageC">
<!-- Start of Space Admin -->
  <table class="inner">
  <tr style="vertical-align:top">
    <td style="background:#fff;padding:0px;">
      <div class="content-header">
      </div>
      <div id="contentpane">
        <div class="ui-layout-north panel">
          <h3>Migrasi Database Ke OpenSID <?php echo AmbilVersi()?></h3>
        </div>
        <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
          <div class="left">
              <!--Impor data BIP-->
              <form action="<?php echo $form_action?>" method="post" enctype="multipart/form-data" id="excell">
               <table class="form">
                <tr>
                  <td width="500" colspan="3">
                    <p font-size="14px";>
                      Proses ini untuk mengubah database SID ke struktur database OpenSID <?php echo AmbilVersi()?>.
                      <br><br>
                      <div class="box-perhatian">
                        <strong><span class="fa fa-info-circle" style="color:red"></span> Sebelum melakukan migrasi ini, pastikan database SID anda telah dibackup.</strong>
                      </div>
                      <br>
                      Apabila sesudah melakukan konversi ini, masih ditemukan masalah, laporkan di
                      <ul>
                        <li>
                          <a href="https://github.com/eddieridwan/OpenSID/issues">https://github.com/eddieridwan/OpenSID/issues</a>, dan di
                        </li>
                        <li>
                          <a href="https://www.facebook.com/groups/OpenSID/">https://www.facebook.com/groups/OpenSID/</a>
                        </li>
                    </p>
                  </td>
                  <td>
                  &nbsp;
                  </td>
                </tr>
                <tr>
                  <td>
                    <a href="#" onclick="document.getElementById('excell').submit();" class="uibutton special" value="Import" target="confirm2" message="Harap tunggu sampai proses migrasi selesai. Prosses ini biasa memakan waktu beberapa menit.<div align='center'><img src='<?php echo base_url()?>assets/images/background/loading.gif'></div>" header="Proses Migrasi Sedang Berjalan."><span class="fa fa-retweet"></span> Migrasi Database Ke OpenSID <?php echo AmbilVersi()?></a>
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
