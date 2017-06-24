<div id="pageC">
<!-- Start of Space Admin -->
	<table class="inner">
  	<tr style="vertical-align:top">
      <td style="background:#fff;padding:0px;">
        <div class="content-header">
        </div>
        <div id="contentpane">

          <div class="ui-layout-north panel">
            <h3>Full Backup / Restore Data SID</h3>
          </div>

          <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
            <div class="table-panel top">
              <div class="left">
                <table class="list-db">
                  <tr>
                    <td width="250">Backup Seluruh Data SID</td>
                    <td>
                      <div class="uibutton-group">
                        <a class="uibutton special" href="<?php echo site_url("database")?>/exec_backup"><span class="fa fa-download"></span> Download</a>
                      </div>
                    </td>
                  </tr>
            		</table>

                <form action="<?php echo $form_action?>" method="post" enctype="multipart/form-data">
                  <table class="form">
           			    <tr>
            					<td colspan="3">
              					<p>
                					Proses <em>Download</em> akan mengunduh keseluruhan database SID anda.
                        </p>
                        <br>
                        <ul>
                          <li>Usahakan untuk melakukan backup secara rutin dan terjadwal.
                          <li>Backup yang dihasilkan sebaiknya disimpan di komputer terpisah dari server SID.</li>
                        </ul>
              					<br>
                        <p>Backup yang dibuat dapat dipergunakan untuk me-restore database SID anda apabila ada masalah. Klik tombol <em>Restore</em> di bawah untuk menggantikan keseluruhan database SID dengan data hasil backup terdahulu.
                        </p>
                        <div>
                          <br>
                          <?php
                          if(strlen(@$_SESSION["SIAK"])>1){
                            echo $_SESSION["SIAK"];
                          }
                          $_SESSION["SIAK"] = "";

                          $max_upload = (int)(ini_get('upload_max_filesize'));
                          $max_post = (int)(ini_get('post_max_size'));
                          $memory_limit = (int)(ini_get('memory_limit'));
                          $upload_mb = min($max_upload, $max_post, $memory_limit);
                          echo "<p>Batas maksimal pengunggahan berkas <strong>".$upload_mb." MB.</strong></p>
                          <br><p>Proses ini akan membutuhkan waktu beberapa menit, menyesuaikan dengan spesifikasi
                          komputer server SID dan sambungan internet yang tersedia.</p>";

                          ?>
                        </div>
            					</td>
                      <td>&nbsp;</td>
            				</tr>

                    <tr>
                      <td width='100'>
                        <p>Pilih File .sql:</p>
                      </td>
                      <td width='100'><input name="userfile" type="file" accept="application/sql"/></td>
                      <td><button class="uibutton special" type="submit"><span class="fa fa-refresh"></span> Restore</button></td>
                      <td>&nbsp;</td>
                    </tr>
                  </table>
                </form>

                <!--Kosongkan DB-->
                <div class="left" style='margin-top: 40px;'>

                  <h3>Kosongkan Database SID</h4>
                  <table class="form">
                    <tr class="form">
                      <td>
                        <p>
                          Biasanya pada saat menginstall SID, desa mengimpor data awal yang merupakan
                          contoh yang disediakan agar pengguna dapat mempelajari fitur SID.
                          Data awal tersebut tentunya mengandung data contoh yang bukan data desa.
                          <br><br>
                          Sebelum memasukkan data desa yang sebenarnya ke dalam database SID, data contoh tersebut perlu dikosongkan dulu.
                          <br><br>
                          Klik tomboh <em>Kosongkan DB</em> di bawah untuk mengosongkan database SID siap untuk diisi dengan data desa.
                          <div style="margin-top: 20px;">
                            <div class="box-perhatian"><strong><span class="fa fa-info-circle" style="color:red"></span> Sebelum melalukan proses ini, backup dulu database SID.</strong></div></div>
                          <br>
                        </p>
                      </td>
                    </tr>
                  </table>

                  <table class="list-db">
                    <tr>
                      <td width="250"> Kosongkan Database SID</td>
                      <td>
                      <div class="uibutton-group">
                        <a class="uibutton special" href="<?php echo site_url("database/kosongkan_db")?>" title="Kosongkan DB"  target="confirm" message="Apakah anda yakin? Proses ini akan menghapus semua data penduduk dan data masukan lainnya." rel="window" header="Kosongkan DB"><span class="fa fa-circle-o-notch"></span> Kosongkan DB</a>
                      </div>
                      </td>
                    </tr>
                  </table>

                </div>
                <!--Kosongkan DB-->

              </div>

            </div>

            <div class="ui-layout-south panel bottom">
              <div class="left">
            		<div class="table-info">
                </div>
              </div>
              <div class="right">
              </div>
            </div>
          </div>
        </div>
      </td>
    </tr>
  </table>
</div>
