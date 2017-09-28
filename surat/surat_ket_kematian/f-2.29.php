<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');?>

<style type="text/css">

table.disdukcapil {
  width: 100%;
  border: solid 1px #000000;
  /*border-collapse: collapse;*/
}
table.disdukcapil td {
  padding: 1px 1px 1px 3px;
  margin: 0px;
}
table.disdukcapil td.padat {
  padding: 0px;
  margin: 0px;
}
table.disdukcapil td.anggota {
  border-left: solid 1px #000000;
  border-right: solid 1px #000000;
  border-top: dashed 1px #000000;
  border-bottom: dashed 1px #000000;
}
table.disdukcapil td.judul {
  border-left: solid 1px #000000;
  border-right: solid 1px #000000;
  border-top: double 1px #000000;
  border-bottom: double 1px #000000;
}
table.disdukcapil td.bawah {border-bottom: solid 1px #000000;}
table.disdukcapil td.atas {border-top: solid 1px #000000;}
table.disdukcapil td.tengah_blank {
  border-left: solid 1px #000000;
  border-right: solid 1px #000000;
}
table.disdukcapil td.pinggir_kiri {border-left: solid 1px #000000;}
table.disdukcapil td.pinggir_kanan {border-right: solid 1px #000000;}
table.disdukcapil td.kotak {border: solid 1px #000000;}
table.disdukcapil td.abu {background-color: lightgrey;}
table.disdukcapil td.kode {background-color: lightgrey;}
table.disdukcapil td.kode div {
  margin: 0px 15px 0px 15px;
  border: solid 1px black;
  background-color: white;
  text-align: center;
}
table.disdukcapil td.pakai-padding {
  padding-left: 20px;
  padding-right: 2px;
}
table.disdukcapil td.kiri { text-align: left; }
table.disdukcapil td.kanan { text-align: right; }
table.disdukcapil td.tengah { text-align: center; }

table#kop {
  margin-top: -5px;
  margin-bottom: 0px;
  padding: 0px;
  border: 0px;
  border-collapse: collapse;
}
table#kop td {padding: 0px; margin: 0px;}
table#kop tr {padding: 0px; margin: 0px;}

table#kode {
  padding: 2px 10px;
  border: solid 1px black;
  margin-top: 0px;
  margin-bottom: 0px;
  font-size: 11pt;
}
</style>

<page orientation="portrait" format="215x330" style="font-size: 7pt">

  <table id="kode" align="right">
    <tr><td><strong>Kode . F-2.29</strong></td></tr>
  </table>
 
  <table id="kop" class="disdukcapil">
    <col span="48" style="width: 2.0833%;">
    <tr><td colspan=48>&nbsp;</td></tr>
    <tr>
      <td colspan="10">Pemerintah Desa/Kelurahan</td>
      <td>: </td>
      <td colspan="7"><?php echo $config['nama_desa'];?></td>
      <td colspan="13">&nbsp;</td>
      <td colspan="3">Ket : </td>
      <td colspan="4">Lembar 1</td>
      <td>: </td>
      <td colspan="9">Untuk Yang Bersangkutan</td>
    </tr>
    <tr>
      <td colspan="10">Kecamatan</td>
      <td>: </td>
      <td colspan="7"><?php echo $config['nama_kecamatan'];?></td>
      <td colspan="16">&nbsp;</td>
      <td colspan="4">Lembar 2</td>
      <td>: </td>
      <td colspan="9">Untuk UPTD/Instansi Pelaksana</td>
    </tr>
    <tr>
      <td colspan="10">Kabupaten/Kota</td>
      <td>:</td>
      <td colspan="7"><?php echo $config['nama_kabupaten'];?></td>
      <td colspan="16">&nbsp;</td>
      <td colspan="4">Lembar 3</td>
      <td>: </td>
      <td colspan="9">Untuk Desa/Kelurahan</td>
    </tr>
    <tr>
      <td colspan="11">&nbsp;</td>
      <?php for($i=0; $i<9; $i++): ?>
        <td style="border-bottom: 1px solid black;">&nbsp;</td>
      <?php endfor; ?>
      <td colspan="14">&nbsp;</td>
      <td colspan="4">Lembar 4</td>
      <td>: </td>
      <td colspan="9">Untuk Kecamatan</td>
    </tr>
    <tr>
  	  <td colspan="10">Kode Wilayah</td>
      <td style="border-right: 1px solid black;">:</td>
      <?php for($i=0; $i<9; $i++): ?>
        <td class="kotak padat tengah">
          &nbsp;
        </td>
      <?php endfor; ?>
      <td colspan="28">&nbsp;</td>
    </tr>
    <!-- Untuk memaksa penampilan setiap kolom -->
    <tr>
      <?php for($i=0; $i<48; $i++): ?>
        <td>&nbsp;</td>
      <?php endfor; ?>
    </tr>
  </table>


  <p style="text-align: center; margin-top: 2px;">
      <strong style="font-size: 10pt;">SURAT KETERANGAN KEMATIAN </strong>
  </p>
  <table class="disdukcapil" style="margin-top: -5px; border: 0px;">
    <col span="48" style="width: 2.0833%;">
    <!-- Untuk memaksa penampilan setiap kolom -->
    <tr>
      <?php for($i=0; $i<48; $i++): ?>
        <td>&nbsp;</td>
      <?php endfor; ?>
    </tr>
    <tr>
      <td colspan="10">Nama Kepala Keluarga</td>
      <td>:</td>
      <?php for($i=0; $i<33; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($input['kepala_kk'][$i]))
            echo $input['kepala_kk'][$i];
            else echo "&nbsp;";
          ?>
        </td>
      <?php endfor; ?>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="10">Nomor Kartu Keluarga</td>
      <td>:</td>
      <?php for($i=0; $i<16; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($input['no_kk'][$i]))
            echo $input['no_kk'][$i];
            else echo "&nbsp;";
          ?>
        </td>
      <?php endfor; ?>
  	  <td colspan="21">&nbsp;</td>
    </tr>
  </table>

    <table class="disdukcapil" style="margin-top: 0px;">
    <col span="48" style="width: 2.0833%;">
	
    <tr>
      <td colspan=48><strong>JENAZAH </strong></td>
    </tr>

    <tr>
      <td colspan="10" class="left">1.&nbsp;&nbsp;NIK </td>
      <td>:</td>
      <?php for($i=0; $i<16; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($input['nik_jenazah'][$i]))
            echo $input['nik_jenazah'][$i];
            else echo "&nbsp;";
          ?>        </td>
      <?php endfor; ?>
      <td colspan=21>&nbsp;</td>
    </tr>
	<tr>
      <td colspan="10" class="left">2.&nbsp;&nbsp;Nama Lengkap</td>
      <td>:</td>
      <?php for($i=0; $i<33; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($input['nama_jenazah'][$i]))
            echo $input['nama_jenazah'][$i];
            else echo "&nbsp;";
          ?>        </td>
      <?php endfor; ?>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="10" class="left">3.&nbsp;&nbsp;Jenis Kelamin </td>
      <td>:</td>
        <td class="kotak padat tengah">
          <?php echo $input['sex'];?>
        </td>
      <td colspan=5 class="left">1. Laki-laki </td>
      <td colspan=7 class="left">2. Perempuan </td>
      <td colspan=4 class="left">&nbsp;</td>
    </tr>
	<tr>
      <td colspan="10" class="left">4.&nbsp;&nbsp;Tanggal Lahir / Umur </td>
      <td>:</td>
        <?php $tgl = date('dd',strtotime($suami['tanggallahir']));
        $bln = date('mm',strtotime($suami['tanggallahir']));
        $thn = date('Y',strtotime($suami['tanggallahir']));
      ?>
           <td colspan="4" class="left">Tanggal :</td>
		  <?php for($j=0; $j<2; $j++):?>
	  <td class="kotak padat tengah">
          <?php if(isset($tgl[$j]))
            echo $tgl[$j];
            else echo "&nbsp;";
          ?>      </td>
      <?php endfor; ?>
      <td colspan="4" class="right"><div align="right">Bulan : </div></td>
      <?php for($j=0; $j<2; $j++):?>
        <td class="kotak padat tengah">
          <?php if(isset($bln[$j]))
            echo $bln[$j];
            else echo "&nbsp;";
          ?>        </td>
      <?php endfor; ?>
      <td colspan="4" class="right"><div align="right">Tahun : </div></td>
      <?php for($j=0; $j<4; $j++):?>
        <td class="kotak padat tengah">
          <?php if(isset($thn[$j]))
            echo $thn[$j];
            else echo "&nbsp;";
          ?>        </td>
      <?php endfor; ?>
     <td colspan="5" class="left"><div align="right">Umur : </div></td>
  	  <td colspan=5 class="kotak"><?php echo $suami['umur'];?></td>
	  <td colspan="2">&nbsp;</td>
    </tr>
	   <tr>
      <td colspan=10 class="left">5.&nbsp;&nbsp;Tempat Dilahirkan </td>
      <td>:</td>
      <?php for($i=0; $i<1; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($input['tempatlahirjenazah'][$i]))
            echo $input['tempatlahirjenazah'][$i];
            else echo "&nbsp;";
          ?>        </td>
      <?php endfor; ?>
	   <td colspan="2">&nbsp;</td>
	</tr>
	    <tr>
      <td colspan="10" class="left">6.&nbsp;&nbsp;Agama </td>
      <td>:</td>
      <td colspan="23" class="kotak"><?php echo $input['agama']?></td>
      <td colspan="2">&nbsp;</td>
    </tr>
	<tr>
	<td colspan="10" class="left">7.&nbsp;&nbsp;Pekerjaan</td>
      <td>:</td>
      <td colspan="23" class="kotak"><?php echo $input['pekerjaan']?></td>
	</tr>
<tr>
      <td colspan="10" class="left">8.&nbsp;&nbsp;Alamat</td>
      <td>:</td>
      <td colspan="23" class="kotak"><?php echo $input['alamat']?></td>
      <td colspan="3" class="tengah">RT:</td>
      <?php for($i=0; $i<3; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($input['rt'][$i]))
            echo $input['rt'][$i];
            else echo "&nbsp;";
          ?>        </td>
      <?php endfor; ?>
      <td colspan="3" class="tengah">RW:</td>
      <?php for($i=0; $i<3; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($input['rw'][$i]))
            echo $input['rw'][$i];
            else echo "&nbsp;";
          ?>        </td>
      <?php endfor; ?>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="11" >&nbsp;</td>
      <td colspan="7" class="left">a. Desa/Kelurahan</td>
      <td colspan="12" class="kotak"><?php echo $config['nama_desa'];?></td>
      <td colspan="5" class="left">b. Kecamatan</td>
      <td colspan="12" class="kotak"><?php echo $config['nama_kecamatan'];?></td>
      <td colspan="1">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="11" >&nbsp;</td>
      <td colspan="7" class="left">c. Kabupaten/Kota</td>
      <td colspan="12" class="kotak"><?php echo $config['nama_kabupaten'];?></td>
      <td colspan="5" class="left">d. Propinsi</td>
      <td colspan="12" class="kotak"><?php echo $config['nama_propinsi'];?></td>
      <td colspan="1">&nbsp;</td>
	  </tr>	  
	   <tr>
      <td colspan="10" class="left">9.&nbsp;&nbsp;Anak ke </td>
      <td>:</td>
	        <?php for($i=0; $i<1; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($input['anakke'][$i]))
            echo $input['anakke'][$i];
            else echo "&nbsp;";
          ?>        </td>
      <?php endfor; ?>

      <td colspan=4 class="left">1. Satu </td>
	  <td colspan=6 class="left">2. Dua </td>
	  <td colspan=6 class="left">3. Tiga </td>
	  <td colspan=5 class="left">4. Empat </td>
	   <td colspan=5 class="left">... </td>
	   <td colspan=7 class="left">&nbsp;</td>
	      </tr>
	   	   <tr>
      <td colspan="10" class="left">10.&nbsp;&nbsp;Tanggal Kematian </td>
      <td>:</td>
        <?php $tgl = date('dd',strtotime($input['tanggal']));
        $bln = date('mm',strtotime($input['tanggal']));
        $thn = date('Y',strtotime($input['tanggal']));
      ?>
          <td colspan="4" class="right"><div align="right">Tanggal : </div></td>
		  <?php for($j=0; $j<2; $j++):?>
	  <td class="kotak padat tengah">
          <?php if(isset($tgl[$j]))
            echo $tgl[$j];
            else echo "&nbsp;";
          ?>        </td>
      <?php endfor; ?>
      <td colspan="4" class="right"><div align="right">Bulan : </div></td>
      <?php for($j=0; $j<2; $j++):?>
        <td class="kotak padat tengah">
          <?php if(isset($bln[$j]))
            echo $bln[$j];
            else echo "&nbsp;";
          ?>        </td>
      <?php endfor; ?>
      <td colspan="4" class="right"><div align="right">Tahun : </div></td>
      <?php for($j=0; $j<4; $j++):?>
        <td class="kotak padat tengah">
          <?php if(isset($thn[$j]))
            echo $thn[$j];
            else echo "&nbsp;";
          ?>        </td>
      <?php endfor; ?>
      <td colspan="12">&nbsp;</td>

	  <td colspan="2">&nbsp;</td>
    </tr>
	    <tr>
      <td colspan="10" class="left">11.&nbsp;&nbsp;Pukul </td>
      <td>:</td>
      <?php for($i=0; $i<5; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($input['jam'][$i]))
            echo $input['jam'][$i];
            else echo "&nbsp;";
          ?>        </td>
      <?php endfor; ?>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td colspan=10 class="left">12.&nbsp;&nbsp;Sebab Kematian </td>
      <td>:</td>
      <?php for($i=0; $i<1; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($input['sebab'][$i]))
            echo $input['sebab'][$i];
            else echo "&nbsp;";
          ?>        </td>
      <?php endfor; ?>

      <td colspan=4 class="left">1. Sakit biasa / tua</td>
	  <td colspan=6 class="left">2. Wabah Penyakit </td>
	  <td colspan=6 class="left">3. Kecelakaan </td>
	  <td colspan=5 class="left">4. Kriminalitas </td>
	  <td colspan=6 class="left">5. Bunuh Diri </td>
	  <td colspan=5 class="left">6. Lainnya </td>
	   <td colspan=7 class="left">&nbsp;</td>
	   </tr>
 <tr>
<td colspan="10" class="left">13.&nbsp;&nbsp;Tempat Kematian </td>
          <td>:</td>
		  <?php for($i=0; $i<1; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($input['tempat_mati'][$i]))
            echo $input['tempat_mati'][$i];
            else echo "&nbsp;";
          ?>        </td>
      <?php endfor; ?>
</tr>
<tr>
<td colspan="10" class="left">14.&nbsp;&nbsp;Yang menerangkan </td>
          <td>:</td>
		  <?php for($i=0; $i<1; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($input['penolong_mati'][$i]))
            echo $input['penolong_mati'][$i];
            else echo "&nbsp;";
          ?>        </td>
      <?php endfor; ?>

      <td colspan=4 class="left">1. Dokter </td>
	  <td colspan=6 class="left">2. Tenaga Kesehatan </td>
	  <td colspan=6 class="left">3. Kepolisian </td>
	  <td colspan=5 class="left">4. Lainnya </td>
	   <td colspan=7 class="left">&nbsp;</td></tr>

    <tr><td colspan=48 class="bawah"></td>
    </tr>

<!-- AWAL AYAH -->
    <tr>
      <td colspan=48><strong>AYAH</strong></td>
    </tr>

    <tr>
      <td colspan="10" class="left">1. &nbsp;&nbsp;NIK</td>
      <td>:</td>
      <?php for($i=0; $i<16; $i++): ?>
        <td class="kotak padat tengah"><?php if(isset($suami['nik'][$i]))
            echo $suami['nik'][$i];
            else echo "&nbsp;";
          ?></td>
      <?php endfor; ?>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="10" class="left">2. &nbsp;&nbsp;Nama </td>
      <td>:</td>
      <?php for($i=0; $i<33; $i++): ?>
        <td class="kotak padat tengah"><?php if(isset($suami['nama'][$i]))
            echo $suami['nama'][$i];
            else echo "&nbsp;";
          ?></td>
      <?php endfor; ?>
      <td colspan=21>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="10" class="left">3. &nbsp;Tanggal Lahir </td>
      <td>:</td>
        <?php $tgl = date('dd',strtotime($suami['tanggallahir']));
        $bln = date('mm',strtotime($suami['tanggallahir']));
        $thn = date('Y',strtotime($suami['tanggallahir']));
      ?>
           <td colspan="4" class="left">Tanggal :</td>
		  <?php for($j=0; $j<2; $j++):?>
	  <td class="kotak padat tengah">
          <?php if(isset($tgl[$j]))
            echo $tgl[$j];
            else echo "&nbsp;";
          ?>      </td>
      <?php endfor; ?>
      <td colspan="4" class="right"><div align="right">Bulan : </div></td>
      <?php for($j=0; $j<2; $j++):?>
        <td class="kotak padat tengah">
          <?php if(isset($bln[$j]))
            echo $bln[$j];
            else echo "&nbsp;";
          ?>        </td>
      <?php endfor; ?>
      <td colspan="4" class="right"><div align="right">Tahun : </div></td>
      <?php for($j=0; $j<4; $j++):?>
        <td class="kotak padat tengah">
          <?php if(isset($thn[$j]))
            echo $thn[$j];
            else echo "&nbsp;";
          ?>        </td>
      <?php endfor; ?>
     <td colspan="5" class="left"><div align="right">Umur : </div></td>
  	  <td colspan=5 class="kotak"><?php echo $suami['umur'];?></td>
	  <td colspan="2">&nbsp;</td>
    </tr>
	<tr>
	<td colspan="10" class="left">4.&nbsp;&nbsp;Pekerjaan</td>
      <td>:</td>
      <td colspan="23" class="kotak"><?php echo $suami['pek']?></td>

      <td colspan="2">&nbsp;</td></tr>
    <tr>
      <td colspan="10" class="left">5. &nbsp;&nbsp;Alamat</td>
      <td>:</td>
      <td colspan="23" class="kotak"><?php echo $suami['alamat']?></td>
      <td colspan="3" class="tengah">RT:</td>
      <?php for($i=0; $i<3; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($suami['rt'][$i]))
            echo $suami['rt'][$i];
            else echo "&nbsp;";
          ?>        </td>
      <?php endfor; ?>
      <td colspan="3" class="tengah">RW:</td>
      <?php for($i=0; $i<3; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($suami['rw'][$i]))
            echo $suami['rw'][$i];
            else echo "&nbsp;";
          ?>        </td>
      <?php endfor; ?>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="11" >&nbsp;</td>
      <td colspan="7" class="left">a. Desa/Keluarga</td>
      <td colspan="12" class="kotak"><?php echo $config['nama_desa'];?></td>
      <td colspan="5" class="left">b. Kecamatan</td>
      <td colspan="12" class="kotak"><?php echo $config['nama_kecamatan'];?></td>
      <td colspan="1">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="11" >&nbsp;</td>
      <td colspan="7" class="left">c. Kabupaten/Kota</td>
      <td colspan="12" class="kotak"><?php echo $config['nama_kabupaten'];?></td>
      <td colspan="5" class="left">d. Propinsi</td>
      <td colspan="12" class="kotak"><?php echo $config['nama_propinsi'];?></td>
      <td colspan="1">&nbsp;</td>
	  </tr>
<!-- AKHIR AYAH -->
	  <tr>
	  <td colspan=48 class="bawah"></td>
    </tr>
<!-- AWAL IBU -->
    <tr>
      <td colspan=48><strong>IBU</strong></td>
    </tr>

    <tr>
      <td colspan="10" class="left">1.&nbsp;&nbsp;NIK</td>
      <td>:</td>
      <?php for($i=0; $i<16; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($individu['nik'][$i]))
            echo $individu['nik'][$i];
            else echo "&nbsp;";
          ?>        </td>
      <?php endfor; ?>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="10" class="left">2.&nbsp;&nbsp;Nama </td>
      <td>:</td>
      <?php for($i=0; $i<33; $i++): ?>
        <td class="kotak padat tengah"><?php if(isset($individu['nama'][$i]))
            echo $individu['nama'][$i];
            else echo "&nbsp;";
          ?></td>
      <?php endfor; ?>
      <td colspan=21>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="10" class="left">3.&nbsp;&nbsp;Tanggal lahir </td>
      <td>:</td>
        <?php $tgl = date('dd',strtotime($individu['tanggallahir']));
        $bln = date('mm',strtotime($individu['tanggallahir']));
        $thn = date('Y',strtotime($individu['tanggallahir']));
      ?>
           <td colspan="4" class="left">Tanggal :</td>
		  <?php for($j=0; $j<2; $j++):?>
	  <td class="kotak padat tengah">
          <?php if(isset($tgl[$j]))
            echo $tgl[$j];
            else echo "&nbsp;";
          ?>      </td>
      <?php endfor; ?>
      <td colspan="4" class="right"><div align="right">Bulan : </div></td>
      <?php for($j=0; $j<2; $j++):?>
        <td class="kotak padat tengah">
          <?php if(isset($bln[$j]))
            echo $bln[$j];
            else echo "&nbsp;";
          ?>        </td>
      <?php endfor; ?>
      <td colspan="4" class="right"><div align="right">Tahun : </div></td>
      <?php for($j=0; $j<4; $j++):?>
        <td class="kotak padat tengah">
          <?php if(isset($thn[$j]))
            echo $thn[$j];
            else echo "&nbsp;";
          ?>        </td>
      <?php endfor; ?>
     <td colspan="5" class="left"><div align="right">Umur : </div></td>
  	  <td colspan=5 class="kotak"><?php echo $individu['umur'];?></td>
	  <td colspan="2">&nbsp;</td>
    </tr>
	<tr>
	<td colspan="10" class="left">4.&nbsp;&nbsp;Pekerjaan</td>
      <td>:</td>
      <td colspan="23" class="kotak"><?php echo $individu['pekerjaan']?></td>

      <td colspan="2">&nbsp;</td></tr>
    <tr>
      <td colspan="10" class="left">5.&nbsp;&nbsp;Alamat</td>
      <td>:</td>
      <td colspan="23" class="kotak"><?php echo $individu['alamat']?></td>
      <td colspan="3" class="tengah">RT:</td>
      <?php for($i=0; $i<3; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($individu['rt'][$i]))
            echo $individu['rt'][$i];
            else echo "&nbsp;";
          ?>        </td>
      <?php endfor; ?>
      <td colspan="3" class="tengah">RW:</td>
      <?php for($i=0; $i<3; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($individu['rw'][$i]))
            echo $individu['rw'][$i];
            else echo "&nbsp;";
          ?>        </td>
      <?php endfor; ?>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="11" >&nbsp;</td>
      <td colspan="7" class="left">a. Desa/Keluarga</td>
      <td colspan="12" class="kotak"><?php echo $config['nama_desa'];?></td>
      <td colspan="5" class="left">b. Kecamatan</td>
      <td colspan="12" class="kotak"><?php echo $config['nama_kecamatan'];?></td>
      <td colspan="1">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="11" >&nbsp;</td>
      <td colspan="7" class="left">c. Kabupaten/Kota</td>
      <td colspan="12" class="kotak"><?php echo $config['nama_kabupaten'];?></td>
      <td colspan="5" class="left">d. Propinsi</td>
      <td colspan="12" class="kotak"><?php echo $config['nama_propinsi'];?></td>
      <td colspan="1">&nbsp;</td>
	  </tr>
<!-- AKHIR IBU -->	
	  <tr>
	  <td colspan=48 class="bawah"></td>
    </tr>
<!-- AWAL PELAPOR -->
    <tr>
      <td colspan=48><strong>PELAPOR</strong></td>
    </tr>

    <tr>
      <td colspan="10" class="left">1.&nbsp;&nbsp;NIK</td>
      <td>:</td>
      <?php for($i=0; $i<16; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($input['nik_pelapor'][$i]))
            echo $input['nik_pelapor'][$i];
            else echo "&nbsp;";
          ?>        </td>
      <?php endfor; ?>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="10" class="left">2.&nbsp;&nbsp;Nama Lengkap</td>
      <td>:</td>
      <?php for($i=0; $i<33; $i++): ?>
        <td class="kotak padat tengah"><?php if(isset($input['nama_pelapor'][$i]))
            echo $input['nama_pelapor'][$i];
            else echo "&nbsp;";
          ?></td>
      <?php endfor; ?>
      <td colspan=21>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="10" class="left">3.&nbsp;&nbsp;Umur</td>
      <td>:</td>
      <?php for($i=0; $i<2; $i++): ?>
        <td class="kotak padat tengah"><?php if(isset($input['umur_pelapor'][$i]))
            echo $input['umur_pelapor'][$i];
            else echo "&nbsp;";
          ?></td>
      <?php endfor; ?>
      <td colspan=21>Tahun</td>
    </tr>
    <tr>
      <td colspan="10" class="left">5. &nbsp;&nbsp;Pekerjaan</td>
      <td>:</td>
      <td colspan="23" class="kotak"><?php echo $input['pekerjaanpelapor']?></td>
      <td colspan="3" class="tengah">&nbsp;</td>
    </tr>
    <tr>
     <td colspan="10" class="left">6. &nbsp;&nbsp;Alamat</td>
      <td>:</td>

      <td colspan="7" class="left">a. Desa/Keluarga</td>
      <td colspan="12" class="kotak"><?php echo $input['desapelapor'];?></td>
      <td colspan="5" class="left">b. Kecamatan</td>
      <td colspan="12" class="kotak"><?php echo $input['kecpelapor'];?></td>
      <td colspan="1">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="11" >&nbsp;</td>
      <td colspan="7" class="left">c. Kabupaten/Kota</td>
      <td colspan="12" class="kotak"><?php echo $input['kabpelapor'];?></td>
      <td colspan="5" class="left">d. Propinsi</td>
      <td colspan="12" class="kotak"><?php echo $input['provinsipelapor'];?></td>
      <td colspan="1">&nbsp;</td>
	  </tr>
<!-- AKHIR PELAPOR -->
	  <tr>
	  <td colspan=48 class="bawah"></td>
    </tr>
<!-- AWAL SAKSI 1 -->
    <tr>
      <td colspan=48><strong>SAKSI 1 </strong></td>
    </tr>

    <tr>
      <td colspan="10" class="left">1.&nbsp;&nbsp;NIK</td>
      <td>:</td>
      <?php for($i=0; $i<16; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($input['nik_saksi1'][$i]))
            echo $input['nik_saksi1'][$i];
            else echo "&nbsp;";
          ?>        </td>
      <?php endfor; ?>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="10" class="left">2.&nbsp;&nbsp;Nama </td>
      <td>:</td>
      <?php for($i=0; $i<33; $i++): ?>
        <td class="kotak padat tengah"><?php if(isset($input['nama_saksi1'][$i]))
            echo $input['nama_saksi1'][$i];
            else echo "&nbsp;";
          ?></td>
      <?php endfor; ?>
      <td colspan=21>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="10" class="left">3.&nbsp;&nbsp;Umur</td>
      <td>:</td>
      <?php for($i=0; $i<2; $i++): ?>
        <td class="kotak padat tengah"><?php if(isset($input['umur_saksi1'][$i]))
            echo $input['umur_saksi1'][$i];
            else echo "&nbsp;";
          ?></td>
      <?php endfor; ?>
      <td colspan=21>Tahun</td>
    </tr>
    <tr>
      <td colspan="10" class="left">5.&nbsp;&nbsp;Pekerjaan</td>
      <td>:</td>
      <td colspan="23" class="kotak"><?php echo $input['pekerjaansaksi1']?></td>
      <td colspan="3" class="tengah">&nbsp;</td>
    </tr>
    <tr>
     <td colspan="10" class="left">6.&nbsp;&nbsp;Alamat</td>
      <td>:</td>

      <td colspan="7" class="left">a. Desa/Keluarga</td>
      <td colspan="12" class="kotak"><?php echo $input['desasaksi1'];?></td>
      <td colspan="5" class="left">b. Kecamatan</td>
      <td colspan="12" class="kotak"><?php echo $input['kecsaksi1'];?></td>
      <td colspan="1">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="11" >&nbsp;</td>
      <td colspan="7" class="left">c. Kabupaten/Kota</td>
      <td colspan="12" class="kotak"><?php echo $input['kabsaksi1'];?></td>
      <td colspan="5" class="left">d. Propinsi</td>
      <td colspan="12" class="kotak"><?php echo $input['provinsisaksi1'];?></td>
      <td colspan="1">&nbsp;</td>
	  </tr>
<!-- AKHIR SAKSI 1 -->	  
	  <tr>
	  <td colspan=48 class="bawah"></td>
    </tr>
<!-- AWAL SAKSI 2 -->	
    <tr>
      <td colspan=48><strong>SAKSI 2 </strong></td>
    </tr>

    <tr>
      <td colspan="10" class="left">1.&nbsp;&nbsp;NIK</td>
      <td>:</td>
      <?php for($i=0; $i<16; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($input['nik_saksi2'][$i]))
            echo $input['nik_saksi2'][$i];
            else echo "&nbsp;";
          ?>        </td>
      <?php endfor; ?>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="10" class="left">2.&nbsp;&nbsp;Nama </td>
      <td>:</td>
      <?php for($i=0; $i<33; $i++): ?>
        <td class="kotak padat tengah"><?php if(isset($input['nama_saksi2'][$i]))
            echo $input['nama_saksi2'][$i];
            else echo "&nbsp;";
          ?></td>
      <?php endfor; ?>
      <td colspan=21>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="10" class="left">3. &nbsp;&nbsp;Umur</td>
      <td>:</td>
      <?php for($i=0; $i<2; $i++): ?>
        <td class="kotak padat tengah"><?php if(isset($input['umur_saksi2'][$i]))
            echo $input['umur_saksi2'][$i];
            else echo "&nbsp;";
          ?></td>
      <?php endfor; ?>
      <td colspan=21>Tahun</td>
    </tr>
 
    <tr>
      <td colspan="10" class="left">5.&nbsp;&nbsp;Pekerjaan</td>
      <td>:</td>
      <td colspan="23" class="kotak"><?php echo $input['pekerjaansaksi2']?></td>
      <td colspan="3" class="tengah">&nbsp;</td>
    </tr>
    <tr>
     <td colspan="10" class="left">6.&nbsp;&nbsp;Alamat</td>
      <td>:</td>

      <td colspan="7" class="left">a. Desa/Keluarga</td>
      <td colspan="12" class="kotak"><?php echo $input['desasaksi2'];?></td>
      <td colspan="5" class="left">b. Kecamatan</td>
      <td colspan="12" class="kotak"><?php echo $input['kecsaksi2'];?></td>
      <td colspan="1">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="11" >&nbsp;</td>
      <td colspan="7" class="left">c. Kabupaten/Kota</td>
      <td colspan="12" class="kotak"><?php echo $input['kabsaksi2'];?></td>
      <td colspan="5" class="left">d. Propinsi</td>
      <td colspan="12" class="kotak"><?php echo $input['provinsisaksi2'];?></td>
      <td colspan="1">&nbsp;</td>
	  </tr>
<!-- AKHIR SAKSI 2 -->		  
	  <tr>
	  <td colspan=48 class="bawah"></td>
    </tr>

    <tr>
      <td colspan=48>&nbsp;</td>
    </tr>
	  <tr>
	  <td colspan="46" style="text-align: right">
        <?php echo str_pad(".",40,".",STR_PAD_LEFT);?>,<?php echo str_pad(".",60,".",STR_PAD_LEFT);?>      </td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr><td colspan="48">&nbsp;</td></tr>
    <tr>
      <td colspan="4">&nbsp;</td>
      <td colspan="16" style="text-align: center;">Mengetahui</td>
      <td colspan="15">&nbsp;</td>
      <td colspan="10" style="text-align: center;">Pemohon</td>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr><td colspan="48"></td></tr>
    <tr>
       <td colspan="4">&nbsp;</td>

    <td colspan="16" style="text-align: center;">Kepala Desa / Lurah</td>
     <td colspan="15">&nbsp;</td>
	  <td colspan="10" style="text-align: center;">&nbsp;</td>
	  <td colspan="3">&nbsp;</td>
    </tr>
    <tr><td colspan="48">&nbsp;</td></tr>
    <tr><td colspan="48">&nbsp;</td></tr>
	 <tr><td colspan="48">&nbsp;</td></tr>
	 <tr><td colspan="48">&nbsp;</td></tr>
    <tr>
      <td colspan="4">&nbsp;</td>

      <td colspan="16" style="text-align: center;"><strong>(&nbsp;<?php echo padded_string_center(strtoupper($kepala_desa['pamong_nama']),30)?>&nbsp;)</strong></td>
      <td colspan="15">&nbsp;</td>
      <td colspan="10" style="text-align: center;"><strong>(&nbsp;<?php echo padded_string_center(strtoupper($individu['nama']),30)?>&nbsp;)</strong></td>
      <td colspan="3">&nbsp;</td>
    </tr>

    <tr><td colspan="48">&nbsp;</td>
	</tr>
	  <tr>	  	  </tr>
	  <tr>	  </tr>
    <tr>      </tr>
    <tr></tr>
    <tr>
      <?php for($i=0; $i<48; $i++): ?>
        <td>&nbsp;</td>
      <?php endfor; ?>
    </tr>
  </table>

</page>