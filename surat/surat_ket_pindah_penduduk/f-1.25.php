<style type="text/css">

table.disdukcapil {
  font-size: 10pt;
  width: 100%;
  /*border-collapse: collapse;*/
}
table.disdukcapil td {
  padding: 1px 1px 1px 3px;
}
table.disdukcapil td.satu {
  width: 10px;
  text-align: center;
}
table.disdukcapil td.padat {
  padding: 0px;
  margin: 0px;
  font-size: 9.5pt;
}
table.disdukcapil td.kotak {
  border: solid 1px #000000;
}
table.disdukcapil td.kanan { text-align: right; }
table.disdukcapil td.tengah { text-align: center; }

table.pengikut {
  font-size: 10pt;
  margin-top: 25px;
  border-collapse: collapse;
  border: solid 1px black;
  width:100%;
}
table.pengikut td,th {
  border: solid 1px black;
  padding: 1px 1px 1px 3px;
}
table.pengikut th {
  text-align: center;
  vertical-align: middle;
}
table.pengikut td.tengah { text-align: center; }

table.ttd {
  margin-top: 20px;
  width: 100%;
}

table.ttd td { text-align: center; }

</style>

<page orientation="portrait" format="210x330" style="font-size: 10pt">

  <table align="right" style="padding: 5px 20px; border: solid 1px black;">
    <tr><td><strong><?php echo $input['kode_format']?></strong></td></tr>
  </table>
  <table style="margin-top: 10px;" class="disdukcapil">
    <col style="width: 26%;">
    <col span="6" style="width: 4%;">
    <col style="width: 50%;">
    <tr>
      <td>PROVINSI</td>
      <td>:</td>
      <?php for($i=0; $i<2; $i++): ?>
        <td class="kotak tengah">
          <?php if(isset($config['kode_propinsi'][$i]))
            echo $config['kode_propinsi'][$i];
            else echo "&nbsp;";
          ?>
        </td>
      <?php endfor; ?>
      <td colspan=2>&nbsp;</td>
      <td class="kanan">*)</td>
      <td class="kotak"><?php echo $config['nama_propinsi'];?></td>
    </tr>
    <tr>
      <td>KABUPATEN/KOTA</td>
      <td>:</td>
      <?php for($i=0; $i<2; $i++): ?>
        <td class="kotak tengah">
          <?php if(isset($config['kode_kabupaten'][$i]))
            echo $config['kode_kabupaten'][$i];
            else echo "&nbsp;";
          ?>
        </td>
      <?php endfor; ?>
      <td colspan=2>&nbsp;</td>
      <td class="kanan">*)</td>
      <td class="kotak"><?php echo $config['nama_kabupaten'];?></td>
    </tr>
    <tr>
      <td>KECAMATAN</td>
      <td>:</td>
      <?php for($i=0; $i<2; $i++): ?>
        <td class="kotak tengah">
          <?php if(isset($config['kode_kecamatan'][$i]))
            echo $config['kode_kecamatan'][$i];
            else echo "&nbsp;";
          ?>
        </td>
      <?php endfor; ?>
      <td colspan=2>&nbsp;</td>
      <td class="kanan">*)</td>
      <td class="kotak"><?php echo $config['nama_kecamatan'];?></td>
    </tr>
    <tr>
      <td>DESA/KELURAHAN</td>
      <td>:</td>
      <?php for($i=0; $i<4; $i++): ?>
        <td class="kotak tengah">
          <?php if(isset($config['kode_desa'][$i]))
            echo $config['kode_desa'][$i];
            else echo "&nbsp;";
          ?>
        </td>
      <?php endfor; ?>
      <td class="kanan">*)</td>
      <td class="kotak"><?php echo $config['nama_desa'];?></td>
    </tr>
    <tr>
      <td>DUSUN/DUKUH/KAMPUNG</td>
      <td>:</td>
      <td colspan=6 class="kotak"><?php echo $individu['dusun'];?></td>
    </tr>
  </table>
  <p style="text-align: center;">
      <strong style="font-size: 12pt;">FORMULIR PERMOHONAN PINDAH WNI</strong><br>
      <?php echo $input['judul_format']?><br>
      No. .................................
  </p>

  <table class="disdukcapil">
    <col style="width: 3%;">
    <col style="width: 24.4%;">
    <col span="22" style="width: 3.3%">
    <tr>
      <td colspan=2><strong>DATA DAERAH ASAL</strong></td>
      <td colspan=22>&nbsp;</td>
    </tr>
    <tr>
      <td>1.</td>
      <td>Nomor Kartu Keluarga</td>
      <?php for($i=0; $i<16; $i++): ?>
        <td class="kotak satu">
          <?php if(isset($individu['no_kk'][$i]))
            echo $individu['no_kk'][$i];
            else echo "&nbsp;";
          ?>
        </td>
      <?php endfor; ?>
      <td colspan=6>&nbsp;</td>
    </tr>
    <tr>
      <td>2.</td>
      <td>Nama Kepala Keluarga</td>
      <td colspan=22 class="kotak"><?php echo $individu['kepala_kk']; ?></td>
    </tr>
    <tr>
      <td>3.</td>
      <td>Alamat</td>
      <td colspan=12 class="kotak"><?php echo $individu['alamat']; ?></td>
      <td colspan=2 style="text-align: center;">RT</td>
      <?php for($i=0; $i<3; $i++): ?>
        <td class="kotak satu">
          <?php if(isset($individu['rt'][$i]))
            echo $individu['rt'][$i];
            else echo "&nbsp;";
          ?>
        </td>
      <?php endfor; ?>
      <td colspan=2 style="text-align: center;">RW</td>
      <?php for($i=0; $i<3; $i++): ?>
        <td class="kotak satu">
          <?php if(isset($individu['rw'][$i]))
            echo $individu['rw'][$i];
            else echo "&nbsp;";
          ?>
        </td>
      <?php endfor; ?>
    </tr>

    <tr>
      <td colspan=2>&nbsp;</td>
      <td colspan=7>Dusun/Dukuh/Kampung</td>
      <td colspan=15 class="kotak"><?php echo $individu['dusun']; ?></td>
    </tr>

    <tr>
      <td>&nbsp;</td>
      <td>a. Desa/Kelurahan</td>
      <td colspan=8 class="kotak"><?php echo $config['nama_desa'];?></td>
      <td colspan=4>c. Kab/Kota</td>
      <td colspan=10 class="kotak"><?php echo $config['nama_kabupaten'];?></td>
    </tr>

    <tr>
      <td>&nbsp;</td>
      <td>b. Kecamatan</td>
      <td colspan=8 class="kotak"><?php echo $config['nama_kecamatan'];?></td>
      <td colspan=4>d. Provinsi</td>
      <td colspan=10 class="kotak"><?php echo $config['nama_propinsi'];?></td>
    </tr>

    <tr>
      <td colspan=2>&nbsp;</td>
      <td colspan=3>Kode Pos</td>
      <?php for($i=0; $i<5; $i++): ?>
        <td class="kotak satu">
          <?php if(isset($config['kode_pos'][$i]))
            echo $config['kode_pos'][$i];
            else echo "&nbsp;";
          ?>
        </td>
      <?php endfor; ?>
      <td colspan=4>Telepon</td>
      <?php for($i=0; $i<10; $i++): ?>
        <td class="kotak satu">
          <?php if(isset($input['telepon'][$i]))
            echo trim($input['telepon'][$i]);
            else echo "&nbsp;";
          ?>
        </td>
      <?php endfor; ?>
    </tr>

    <tr>
      <td>4.</td>
      <td>NIK Pemohon</td>
      <?php for($i=0; $i<16; $i++): ?>
        <td class="kotak satu">
          <?php if(isset($individu['nik'][$i]))
            echo $individu['nik'][$i];
            else echo "&nbsp;";
          ?>
        </td>
      <?php endfor; ?>
      <td colspan=6>&nbsp;</td>
    </tr>

    <tr>
      <td>5.</td>
      <td>Nama Lengkap</td>
      <td colspan=22 class="kotak"><?php echo $individu['nama']; ?></td>
    </tr>
    <tr><td colspan=24>&nbsp;</td></tr>
    <tr>
      <td colspan=2><strong>DATA KEPINDAHAN</strong></td>
      <td colspan=22>&nbsp;</td>
    </tr>

    <tr>
      <td rowspan="2">1.</td>
      <td rowspan="2">Alasan Pindah</td>
      <td rowspan="2" class="kotak satu"><?php echo trim($input['alasan_pindah_id'],"'"); ?></td>
      <td rowspan="2">&nbsp;</td>
      <td colspan=4 class="padat">1. Pekerjaan</td>
      <td colspan=4 class="padat">3. Keamanan</td>
      <td colspan=4 class="padat">5. Perumahan</td>
      <td colspan=8 class="padat">7. Lainnya (sebutkan)</td>
    </tr>

    <tr>
      <td colspan=4 class="padat">2. Pendidikan</td>
      <td colspan=4 class="padat">4. Kesehatan</td>
      <td colspan=4 class="padat">6. Keluarga</td>
      <td colspan=8 class="padat">
        <?php if($input['sebut_alasan'])
          echo "<span style='text-decoration: none; border-bottom: 1px dotted black;'>".$input['sebut_alasan']."</span>";
          else echo '..............................';
        ?>
      </td>
    </tr>

    <tr>
      <td>2.</td>
      <td>Alamat Tujuan Pindah</td>
      <td colspan=12 class="kotak"><?php echo $input['alamat_tujuan']; ?></td>
      <td colspan=2 style="text-align: center;">RT</td>
      <?php for($i=0; $i<3; $i++): ?>
        <td class="kotak satu">
          <?php if(isset($input['rt_tujuan'][$i]))
            echo $input['rt_tujuan'][$i];
            else echo "";
          ?>
        </td>
      <?php endfor; ?>
      <td colspan=2 style="text-align: center;">RW</td>
      <?php for($i=0; $i<3; $i++): ?>
        <td class="kotak satu">
          <?php if(isset($input['rw_tujuan'][$i]))
            echo $input['rw_tujuan'][$i];
            else echo "";
          ?>
        </td>
      <?php endfor; ?>
    </tr>

    <tr>
      <td colspan=2>&nbsp;</td>
      <td colspan=7>Dusun/Dukuh/Kampung</td>
      <td colspan=15 class="kotak"><?php echo $input['dusun_tujuan']; ?></td>
    </tr>

    <tr>
      <td>&nbsp;</td>
      <td>a. Desa/Kelurahan</td>
      <td colspan=8 class="kotak"><?php echo $input['desa_tujuan'];?></td>
      <td colspan=4>c. Kab/Kota</td>
      <td colspan=10 class="kotak"><?php echo $input['kabupaten_tujuan'];?></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>b. Kecamatan</td>
      <td colspan=8 class="kotak"><?php echo $input['kecamatan_tujuan'];?></td>
      <td colspan=4>d. Provinsi</td>
      <td colspan=10 class="kotak"><?php echo $input['provinsi_tujuan'];?></td>
    </tr>

    <tr>
      <td colspan=2>&nbsp;</td>
      <td colspan=3>Kode Pos</td>
      <?php for($i=0; $i<5; $i++): ?>
        <td class="kotak satu">
          <?php if(isset($input['kode_pos_tujuan'][$i]))
            echo $input['kode_pos_tujuan'][$i];
            else echo "&nbsp;";
          ?>
        </td>
      <?php endfor; ?>
      <td colspan=4>Telepon</td>
      <?php for($i=0; $i<10; $i++): ?>
        <td class="kotak satu">
          <?php if(isset($input['telepon_tujuan'][$i]))
            echo trim($input['telepon_tujuan'][$i]);
            else echo "&nbsp;";
          ?>
        </td>
      <?php endfor; ?>
    </tr>

    <tr>
      <td rowspan="2">3.</td>
      <td rowspan="2">Jenis Kepindahan</td>
      <td rowspan="2" class="kotak satu"><?php echo trim($input['jenis_kepindahan_id'],"'"); ?></td>
      <td colspan=11 class="padat">1. Kep. Keluarga</td>
      <td colspan=10 class="padat">3. Kep. Keluarga dan Sbg. Angg. Keluarga</td>
    </tr>
    <tr>
      <td colspan=11 class="padat">2. Kep. Keluarga dan Seluruh Angg. Keluarga</td>
      <td colspan=10 class="padat">4. Angg. Keluarga</td>
    </tr>

    <tr>
      <td>4.</td>
      <td>Status KK Bagi Yang Tidak Pindah</td>
      <td class="kotak satu"><?php echo ($input['status_kk_tidak_pindah_id']) ? $input['status_kk_tidak_pindah_id'] : "-"; ?></td>
      <td colspan=5 class="padat">1. Numpang KK</td>
      <td colspan=6 class="padat">2. Membuat KK Baru</td>
      <td colspan=7 class="padat">3. Nomor KK Tetap</td>
    </tr>

    <tr>
      <td>5.</td>
      <td>Status KK Bagi Yang Pindah</td>
      <td class="kotak satu"><?php echo $input['status_kk_pindah_id']; ?></td>
      <td colspan=5 class="padat">1. Numpang KK</td>
      <td colspan=6 class="padat">2. Membuat KK Baru</td>
      <td colspan=7 class="padat">3. Nomor KK Tetap</td>
    </tr>
    <tr>
      <td>6.</td>
      <td>Keluarga Yang Pindah</td>
      <td colspan=20>&nbsp;</td>
    </tr>
  </table>

  <table class="pengikut">
    <tr>
      <th style="width: 5%">NO.</th>
      <th style="width: 35%" colspan=16>NIK</th>
      <th style="width: 33%">NAMA</th>
      <th style="width: 17%">MASA BERLAKU KTP S/D</th>
      <th style="width: 10%" colspan=2>SHDK</th>
    </tr>

    <?php
      for ($i = 0; $i < MAX_PINDAH; $i++) {
        $nomor = $i+1;
        if ($i < count($input['id_cb'])) {
          $nik = trim($input['id_cb'][$i],"'");
          $penduduk = $this->penduduk_model->get_penduduk_by_nik($nik); ?>

          <tr>
            <td class="tengah"><?php echo $nomor;?></td>
            <?php for($j=0; $j<16; $j++):?>
              <td class="tengah">
                <?php if(isset($penduduk['nik'][$j]))
                  echo $penduduk['nik'][$j];
                  else echo "";
                ?>
              </td>
            <?php endfor; ?>
            <td><?php echo $penduduk['nama']; ?></td>
            <td>
              <?php if($input['ktp_berlaku'][$i])
                echo $input['ktp_berlaku'][$i];
                else echo "";
              ?>
            </td>
            <?php $shdk = str_pad($penduduk['kk_level'],2,"0",STR_PAD_LEFT);?>
            <?php for($j=0; $j<2; $j++):?>
              <td class="tengah">
                <?php echo $shdk[$j];?>
              </td>
            <?php endfor; ?>
          </tr>

        <?php } else { ?>
          <tr>
            <?php for($k=0; $k<21; $k++): ?>
              <td>&nbsp;</td>
            <?php endfor; ?>
          </tr>
        <?php } ?>
      <?php } ?>

   </table>

   <table class="ttd">
    <col style="width:45%">
    <col style="width:10%">
    <col style="width:45%">
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>............................, ........................</td>
    </tr>
    <tr>
      <td>Petugas Registrasi</td>
      <td>&nbsp;</td>
      <td>Pemohon</td>
    </tr>
    <tr style="font-size: 30mm; line-height: normal;">
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>(...............................................)</td>
      <td>&nbsp;</td>
      <td>(...............................................)</td>
    </tr>
  </table>
  <p>
    <strong>Keterangan:</strong><br>
    *) Diisi Oleh Petugas
  </p>

</page>