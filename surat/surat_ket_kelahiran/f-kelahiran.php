<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');?>

<style type="text/css">

table.disdukcapil {
  width: 100%;
  border: solid 1px #000000;
  /*border-collapse: collapse;*/
}
table.disdukcapil td {
  padding: 1px 1px 1px 3px;
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
table.disdukcapil td.kanan { text-align: right; }
table.disdukcapil td.tengah { text-align: center; }

</style>

<page orientation="portrait" format="210x330" style="font-size: 7pt">


  <p style="text-align: center; margin-top: 2px;">
      <strong style="font-size: 10pt;">FORMULIR SURAT KETERANGAN LAHIR </strong>
  </p>
  <table class="disdukcapil" style="margin-top: 0px;">
    <col span="48" style="width: 2.0833%;">

     <tr><td colspan=48>&nbsp;</td></tr>
    <tr>
      <td colspan="10" class="left"><strong>PEMERINTAH PROPINSI</strong></td>
      <td>:</td>
      <?php for($i=0; $i<2; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($config['kode_propinsi'][$i]))
            echo $config['kode_propinsi'][$i];
            else echo "&nbsp;";
          ?>        </td>
      <?php endfor; ?>
      <td>&nbsp; </td>
      <td colspan="9" class="kotak"><?php echo $config['nama_propinsi'];?></td>
      <td>&nbsp;</td>
<td colspan="7" class=" left"><strong>KECAMATAN</strong></td>
      <td>:</td>
      <?php for($i=0; $i<2; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($config['kode_kecamatan'][$i]))
            echo $config['kode_kecamatan'][$i];
            else echo "&nbsp;";
          ?>        </td>
      <?php endfor; ?>
      <td colspan=3>&nbsp;</td>
      <td colspan="10" class="kotak"><?php echo $config['nama_kecamatan'];?></td>
      <td>&nbsp;</td>
    </tr>

    <tr>
      <td colspan="10" class="left"><strong>KABUPATEN/KOTA</strong></td>
      <td>:</td>
      <?php for($i=0; $i<2; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($config['kode_kabupaten'][$i]))
            echo $config['kode_kabupaten'][$i];
            else echo "&nbsp;";
          ?>        </td>
      <?php endfor; ?>
      <td>&nbsp;</td>
      <td colspan="9" class="kotak"><?php echo $config['nama_kabupaten'];?></td>
      <td>&nbsp;</td>
	  <td colspan="7" class="left"><strong>KELURAHAN/DESA</strong></td>
      <td>:</td>
      <?php for($i=0; $i<4; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($config['kode_desa'][$i]))
            echo $config['kode_desa'][$i];
            else echo "&nbsp;";
          ?>        </td>
      <?php endfor; ?>
      <td>&nbsp;</td>
      <td colspan="10" class="kotak"><?php echo $config['nama_desa'];?></td>
      <td>&nbsp;</td>
    </tr>

     <tr>    </tr>
	    <tr>
      <td colspan="48" class="left">&nbsp;</td>
          </tr>
		        <tr>
      <td colspan="10" class="left"><strong>Nama Kepala Keluarga </strong></td>
      <td>:</td>
      <?php for($i=0; $i<33; $i++): ?>
        <td class="kotak padat tengah"><?php if(isset($individu['kepala_kk'][$i]))
            echo $individu['kepala_kk'][$i];
            else echo "&nbsp;";
          ?></td>
      <?php endfor; ?>
      <td colspan="4">&nbsp;</td>
    </tr>
			        <tr>
      <td colspan="10" class="left"><strong>Nomor Kartu Keluarga </strong></td>
      <td>:</td>
          <?php for($i=0; $i<16; $i++): ?>
        <td class="kotak padat tengah"><?php if(isset($individu['no_kk'][$i]))
            echo $individu['no_kk'][$i];
            else echo "&nbsp;";
          ?></td>
      <?php endfor; ?>
	  <td colspan="4">&nbsp;</td></tr>

    <tr><td colspan=48 class="bawah">&nbsp;</td>
    </tr>

    <tr>
      <td colspan=48><strong>BAYI / ANAK </strong></td>
    </tr>

    <tr>
      <td colspan="10" class="left">1. &nbsp;&nbsp;Nama Lengkap</td>
      <td>:</td>
      <?php for($i=0; $i<33; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($input['nama_bayi'][$i]))
            echo $input['nama_bayi'][$i];
            else echo "&nbsp;";
          ?>        </td>
      <?php endfor; ?>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="10" class="left">2. &nbsp;&nbsp;NIK </td>
      <td>:</td>
      <?php for($i=0; $i<16; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($input['nik_bayi'][$i]))
            echo $input['nik_bayi'][$i];
            else echo "&nbsp;";
          ?>        </td>
      <?php endfor; ?>
      <td colspan=21>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="10" class="left">6. &nbsp;&nbsp;Jenis Kelamin </td>
      <td>:</td>
        <td class="kotak padat tengah">
          <?php echo $input['sex'];?>
        </td>
      <td colspan=5 class="left">1. LAKI-LAKI </td>
      <td colspan=7 class="left">2. PEREMPUAN </td>
      <td colspan=4 class="left">&nbsp;</td>
    </tr>
	   <tr>
      <td colspan=10 class="left">4. &nbsp;&nbsp;Tempat Dilahirkan </td>
      <td>:</td>
      <?php for($i=0; $i<1; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($input['tempatlahirbayi'][$i]))
            echo $input['tempatlahirbayi'][$i];
            else echo "&nbsp;";
          ?>        </td>
      <?php endfor; ?>

      <td colspan=4 class="left">1. RS/SB</td>
	  <td colspan=6 class="left">2. Puskesmas </td>
	  <td colspan=6 class="left">3. Polindes</td>
	  <td colspan=5 class="left">4. Rumah </td>
	   <td colspan=7 class="left">5. Lainnya &nbsp; </td>
	   <td colspan="2">&nbsp;</td>
	</tr>
	    <tr>
      <td colspan="10" class="left">5. &nbsp;&nbsp;Alamat Dilahirkan </td>
      <td>:</td>
      <td colspan="23" class="kotak"><?php echo $input['alamat_lahir_bayi']?></td>

      <td colspan="2">&nbsp;</td>
    </tr>
	   	   <tr>

      <td colspan="10" class="left">6. &nbsp;&nbsp;Hari dan Tanggal Lahir </td>
      <td>:</td>
      <td colspan="3" class="left">Hari : </td>
  	  <td colspan=4 class="kotak"><?php echo $input['hari'];?></td>
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
      <td colspan="10" class="left">7. &nbsp;&nbsp;Pukul </td>
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
      <td colspan=10 class="left">8. &nbsp;&nbsp;Jenis Kelahiran </td>
      <td>:</td>
      <?php for($i=0; $i<1; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($input['jenislahir'][$i]))
            echo $input['jenislahir'][$i];
            else echo "&nbsp;";
          ?>        </td>
      <?php endfor; ?>

      <td colspan=4 class="left">1. Tunggal</td>
	  <td colspan=6 class="left">2. Kembar 2 </td>
	  <td colspan=6 class="left">3. Kembar 3 </td>
	  <td colspan=5 class="left">4. Kembar 4 </td>
	   <td colspan=7 class="left">&nbsp;</td>
	   </tr>
    <tr>
      <td colspan="10" class="left">9. &nbsp;&nbsp;Kelahiran Anak Ke </td>
      <td>:</td>
	        <?php for($i=0; $i<1; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($input['Kelahiranke'][$i]))
            echo $input['Kelahiranke'][$i];
            else echo "&nbsp;";
          ?>        </td>
      <?php endfor; ?>

      <td colspan=4 class="left">1. Satu </td>
	  <td colspan=6 class="left">2. Dua </td>
	  <td colspan=6 class="left">3. Tiga </td>
	  <td colspan=5 class="left">4. Empat </td>
	   <td colspan=7 class="left">&nbsp;</td>
	      </tr>
		  <tr>
		  <td colspan="10" class="left">10. Penolong Kelahiran </td>
          <td>:</td>
	        <?php for($i=0; $i<1; $i++): ?>
        <td class="kotak padat tengah">
          <?php if(isset($input['penolong'][$i]))
            echo $input['penolong'][$i];
            else echo "&nbsp;";
          ?>        </td>
      <?php endfor; ?>

      <td colspan=4 class="left">1. Dokter </td>
	  <td colspan=6 class="left">2. Bidan/Perawat </td>
	  <td colspan=6 class="left">3. Dukun </td>
	  <td colspan=5 class="left">4. Lainnya </td>
	   <td colspan=7 class="left">&nbsp;</td></tr>

    <tr><td colspan=48 class="bawah"></td>
    </tr>

    <tr>
      <td colspan=48><strong>IBU</strong></td>
    </tr>

    <tr>
      <td colspan="10" class="left">1. &nbsp;&nbsp;NIK</td>
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
      <td colspan="10" class="left">2. &nbsp;&nbsp;Nama </td>
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
      <td colspan="10" class="left">3. &nbsp;&nbsp;Tanggal Lahir </td>
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
	<td colspan="10" class="left">4. &nbsp;&nbsp;Pekerjaan</td>
      <td>:</td>
      <td colspan="23" class="kotak"><?php echo $individu['pekerjaan']?></td>

      <td colspan="2">&nbsp;</td></tr>
    <tr>
      <td colspan="10" class="left">5. &nbsp;&nbsp;Alamat</td>
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
	  <tr>
  	  <td colspan="10" class="left">6. &nbsp;&nbsp;Kewarganegaraan </td>
      <td>:</td>
      <td class="kotak padat tengah"><?php echo $individu['warganegara_id'];?></td>
      <td colspan=4 class="left">1. WNI </td>
  	  <td colspan=6 class="left">2. WNA </td>
  	  <td colspan=6 class="left">&nbsp;</td>
	  </tr>
	  <tr>
	  <td colspan="10" class="left">7. &nbsp;&nbsp;Kebangsaan </td>
      <td>:</td>
      <td colspan="9" class="kotak">INDONESIA</td>

      <td colspan="2">&nbsp;</td>
	  </tr>
	  <tr>
	  <td colspan="10" class="left">7. &nbsp;&nbsp;Tanggal Perkawinan </td>
      <td>:</td>
      <td colspan="9" class="kotak"><?php echo tgl_indo_out($individu['tanggalperkawinan'])?></td>

      <td colspan="2">&nbsp;</td>
	  </tr>
	  <tr>
	  <td colspan=48 class="bawah"></td>
    </tr>

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
	<td colspan="10" class="left">4. &nbsp;&nbsp;Pekerjaan</td>
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
	  <tr>
  	  <td colspan="10" class="left">6. &nbsp;&nbsp;Kewarganegaraan </td>
      <td>:</td>
      <td class="kotak padat tengah">
        <?php echo $suami['warganegara_id'];?>
      </td>
      <td colspan=4 class="left">1. WNI </td>
  	  <td colspan=6 class="left">2. WNA </td>
  	  <td colspan=6 class="left">&nbsp;</td>
	  </tr>
	  <tr>
	  <td colspan="10" class="left">7. &nbsp;&nbsp;Kebangsaan </td>
      <td>:</td>
      <td colspan="9" class="kotak">INDONESIA</td>

      <td colspan="2">&nbsp;</td>
	  </tr>
	  <tr>
	  <td colspan="10" class="left">7. &nbsp;&nbsp;Tanggal Perkawinan </td>
      <td>:</td>
      <td colspan="9" class="kotak"><?php echo tgl_indo_out($suami['tanggalperkawinan'])?></td>

      <td colspan="2">&nbsp;</td>
	  </tr>
	  <tr>
	  <td colspan=48 class="bawah"></td>
    </tr>

    <tr>
      <td colspan=48><strong>PELAPOR</strong></td>
    </tr>

    <tr>
      <td colspan="10" class="left">1. &nbsp;&nbsp;NIK</td>
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
      <td colspan="10" class="left">2. &nbsp;&nbsp;Nama </td>
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
      <td colspan="10" class="left">3. &nbsp;&nbsp;Umur</td>
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
      <td colspan="10" class="left">6. &nbsp;&nbsp;Jenis Kelamin </td>
      <td>:</td>
        <td class="kotak padat tengah">
          <?php echo $input['jkpelapor'];?>
        </td>
      <td colspan=5 class="left">1. LAKI-LAKI </td>
      <td colspan=7 class="left">2. PEREMPUAN </td>
      <td colspan=4 class="left">&nbsp;</td>
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
	  <tr>
	  <td colspan=48 class="bawah"></td>
    </tr>

    <tr>
      <td colspan=48><strong>SAKSI 1 </strong></td>
    </tr>

    <tr>
      <td colspan="10" class="left">1. &nbsp;&nbsp;NIK</td>
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
      <td colspan="10" class="left">2. &nbsp;&nbsp;Nama </td>
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
      <td colspan="10" class="left">3. &nbsp;&nbsp;Umur</td>
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
      <td colspan="10" class="left">6. &nbsp;&nbsp;Jenis Kelamin </td>
      <td>:</td>
        <td class="kotak padat tengah">
          <?php echo $input['jksaksi1'];?>
        </td>
      <td colspan=5 class="left">1. LAKI-LAKI </td>
      <td colspan=7 class="left">2. PEREMPUAN </td>
      <td colspan=4 class="left">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="10" class="left">5. &nbsp;&nbsp;Pekerjaan</td>
      <td>:</td>
      <td colspan="23" class="kotak"><?php echo $input['pekerjaansaksi1']?></td>
      <td colspan="3" class="tengah">&nbsp;</td>
    </tr>
    <tr>
     <td colspan="10" class="left">6. &nbsp;&nbsp;Alamat</td>
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
	  <tr>
	  <td colspan=48 class="bawah"></td>
    </tr>

    <tr>
      <td colspan=48><strong>SAKSI 2 </strong></td>
    </tr>

    <tr>
      <td colspan="10" class="left">1. &nbsp;&nbsp;NIK</td>
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
      <td colspan="10" class="left">2. &nbsp;&nbsp;Nama </td>
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
      <td colspan="10" class="left">6. &nbsp;&nbsp;Jenis Kelamin </td>
      <td>:</td>
        <td class="kotak padat tengah">
          <?php echo $input['jksaksi2'];?>
        </td>
      <td colspan=5 class="left">1. LAKI-LAKI </td>
      <td colspan=7 class="left">2. PEREMPUAN </td>
      <td colspan=4 class="left">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="10" class="left">5. &nbsp;&nbsp;Pekerjaan</td>
      <td>:</td>
      <td colspan="23" class="kotak"><?php echo $input['pekerjaansaksi2']?></td>
      <td colspan="3" class="tengah">&nbsp;</td>
    </tr>
    <tr>
     <td colspan="10" class="left">6. &nbsp;&nbsp;Alamat</td>
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