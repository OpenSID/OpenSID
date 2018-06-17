<style type="text/css">
  table#ektp th {
    background-color: lightgrey;
  }
  table#ektp td {
    text-align: left;
  }
  th.bagian { background-color: lightgrey }

</style>
<tr>
  <th class="top">Foto</th>
  <td>
    <div class="userbox-avatar">
      <img src="<?php echo AmbilFoto($penduduk['foto'])?>" alt=""/>
    </div>
  </td>
  <input type="hidden" name="old_foto" value="<?php echo $penduduk['foto']?>">
</tr>

<tr>
  <th>Ganti Foto</th>
  <td><input type="file" name="foto" /> <span style="color: #aaa;">(Kosongkan jika tidak ingin mengubah foto)</span></td>
</tr>

<tr>
  <th width="100">Nama</th>
  <td><input name="nama" type="text" class="inputbox required" size="60" value="<?php echo strtoupper($penduduk['nama'])?>"/></td>
</tr>

<tr>
  <th>NIK</th>
  <td>
    <input name="nik" type="text" class="inputbox required" size="30" value="<?php echo $penduduk['nik']?>"/>
    <input name="nik_lama" type="hidden" value="<?php echo $_SESSION['nik_lama']?>"/>
  </td>
</tr>
<tr>
  <th>Status Kepemilikan KTP</th>
  <td>
    <table id='ektp'>
      <tr>
        <th>Wajib KTP</th>
        <th>KTP-EL</th>
        <th>Status Rekam</th>
      </tr>
      <tr>
        <td><?php echo strtoupper($penduduk['wajib_ktp'])?></td>
        <td>
          <select name="ktp_el">
            <option value="">Pilih KTP-EL</option>
            <?php foreach($ktp_el as $id => $nama){?>
              <option value="<?php echo $id?>" <?php if(strtolower($penduduk['ktp_el'])==$nama){?>selected<?php }?>><?php echo strtoupper($nama)?></option>
            <?php }?>
          </select>
        </td>
        <td>
          <select name="status_rekam">
            <option value="">Pilih Status Rekam</option>
            <?php foreach($status_rekam as $id => $nama){?>
              <option value="<?php echo $id?>" <?php if(strtolower($penduduk['status_rekam'])==$nama){?>selected<?php }?>><?php echo strtoupper($nama)?></option>
            <?php }?>
          </select>
        </td>
      </tr>
    </table>
  </td>
</tr>
<tr>
  <th>Nomor KK Sebelumnya</th>
  <td>
    <input name="no_kk_sebelumnya" type="text" class="inputbox validkk" maxlength="16" size="30" value="<?php echo $penduduk['no_kk_sebelumnya']?>"/>
  </td>
</tr>
<tr>
  <th>Hubungan dalam Keluarga</th>
  <td>
    <select name="kk_level">
      <option value="">Pilih Hubungan</option>
      <?php foreach($hubungan as $data){?>
        <option value="<?php echo $data['id']?>"<?php if($penduduk['kk_level']==$data['id']){?> selected<?php }?>><?php echo strtoupper($data['nama'])?></option>
      <?php }?>
    </select>
  </td>
</tr>
<tr>
  <th>Jenis Kelamin</th>
  <td>
    <div class="uiradio">
      <input type="radio" id="sx1" name="sex" value="1" <?php if($penduduk['id_sex'] == '1' OR $penduduk['id_sex'] == ''){echo 'checked';}?>>
      <label for="sx1">Laki-laki</label>
      <input type="radio" id="sx2" name="sex" value="2" <?php if($penduduk['id_sex'] == '2'){echo 'checked';}?>>
      <label for="sx2">Perempuan</label>
    </div>
  </td>
</tr>
<tr>
  <th>Agama</th>
  <td>
    <select name="agama_id" class="required">
      <option value="">Pilih Agama</option>
      <?php foreach($agama as $data){?>
        <option value="<?php echo $data['id']?>" <?php if($penduduk['agama_id']==$data['id']){?>selected<?php }?>><?php echo strtoupper($data['nama'])?></option>
      <?php }?>
    </select>
  </td>
</tr>
<tr>
  <th>Status Penduduk</th>
  <td>
    <div class="uiradio">
      <?php $ch='checked';?>
      <input type="radio" id="group2" name="status" value="1" <?php if($penduduk['status'] == "TETAP" OR $penduduk['status'] == "1" OR $penduduk['status'] == ""){echo $ch;}?>><label for="group2">Tetap</label>
      <input type="radio" id="group3" name="status" value="2" <?php if($penduduk['status'] == "TIDAK AKTIF" OR $penduduk['status'] == "2"){echo $ch;}?>><label for="group3">Tidak Aktif</label>
      <?php if(strpos($form_action, 'keluarga') === false): ?>
        <input type="radio" id="group1" name="status" value="3" <?php if($penduduk['status'] == "PENDATANG" OR $penduduk['status'] == "3"){echo $ch;}?>><label for="group1">Pendatang</label>
      <?php endif; ?>
    </div>
  </td>
</tr>

<tr>
  <th colspan="2" class="bagian">DATA KELAHIRAN</th>
</tr>
<tr>
  <th>Akta Kelahiran</th>
  <td><input name="akta_lahir" type="text" class="inputbox" size="30" value="<?php echo $penduduk['akta_lahir']?>"/></td>
</tr>
<tr>
  <th>Tempat Lahir</th>
  <td><input name="tempatlahir" type="text" class="inputbox" size="65"  value="<?php echo strtoupper($penduduk['tempatlahir'])?>"/></td>
</tr>

<tr>
  <th>Tanggal Lahir</th>
  <td>
    <input name="tanggallahir" type="text" class="inputbox datepicker" size="20"  value="<?php echo $penduduk['tanggallahir']?>"/>
    <span><em>*Isi waktu kelahiran, mis. 08:00</em></span>
    <input name="waktu_lahir" type="text" class="inputbox" size="10" value="<?php echo $penduduk['waktu_lahir']?>">
  </td>
</tr>
<tr>
  <th>Tempat Dilahirkan </th>
  <td>
    <div class="uiradio">
      <?php foreach ($tempat_dilahirkan as $id => $nama): ?>
        <input name="tempat_dilahirkan" type="radio" value="<?php echo $id?>" id="radio1<?php echo $id?>" <?php if($penduduk['tempat_dilahirkan']==$id){echo 'checked';}?>/><label for="radio1<?php echo $id?>"><?php echo $nama?></label>
      <?php endforeach; ?>
    </div>
  </td>
</tr>
<tr>
  <th>Jenis Kelahiran </th>
  <td>
    <div class="uiradio">
      <?php foreach ($jenis_kelahiran as $id => $nama): ?>
        <input name="jenis_kelahiran" type="radio" value="<?php echo $id?>" id="radio2<?php echo $id?>" <?php if($penduduk['jenis_kelahiran']==$id){echo 'checked';}?>/><label for="radio2<?php echo $id?>"><?php echo $nama?></label>
      <?php endforeach; ?>
    </div>
  </td>
</tr>
<tr>
  <th>Kelahiran Anak Ke </th>
  <td>
    <input name="kelahiran_anak_ke" type="text" class="inputbox" id="kelahiran_anak_ke" size="8" value="<?php echo $penduduk['kelahiran_anak_ke']?>"/>
    <em>&nbsp;*isi dengan angka </em>
  </td>
</tr>
<tr>
  <th>Penolong Kelahiran </th>
  <td>
    <div class="uiradio">
      <?php foreach ($penolong_kelahiran as $id => $nama): ?>
        <input name="penolong_kelahiran" type="radio" value="<?php echo $id?>" id="radio3<?php echo $id?>" <?php if($penduduk['penolong_kelahiran']==$id){echo 'checked';}?>/><label for="radio3<?php echo $id?>"><?php echo $nama?></label>
      <?php endforeach; ?>
    </div>
  </td>
  </td>
</tr>
<tr>
  <th>Berat Lahir</th>
  <td><input name="berat_lahir" type="text" class="inputbox" size="8" value="<?php echo $penduduk['berat_lahir']?>"/> Kg</td>
</tr>
<tr>
  <th>Panjang Lahir</th>
  <td><input name="panjang_lahir" type="text" class="inputbox" size="8" value="<?php echo $penduduk['panjang_lahir']?>"/> cm</td>
</tr>

<tr>
  <th colspan="2" class="bagian">PENDIDIKAN DAN PEKERJAAN</th>
</tr>
<tr>
  <th>Pendidikan dalam KK</th>
  <td>
    <select name="pendidikan_kk_id">
      <option value="">Pilih Pendidikan</option>
      <?php foreach($pendidikan_kk as $data){?>
        <option value="<?php echo $data['id']?>" <?php if($penduduk['pendidikan_kk_id']==$data['id']){?>selected<?php }?>><?php echo strtoupper($data['nama'])?></option>
      <?php }?>
    </select>
  </td>
</tr>
<tr>
  <th>Pendidikan sedang ditempuh</th>
  <td>
  <select name="pendidikan_sedang_id">
    <option value="">Pilih Pendidikan</option>
    <?php  foreach($pendidikan_sedang as $data){?>
      <option value="<?php echo $data['id']?>" <?php if($penduduk['pendidikan_sedang_id']==$data['id']){?>selected<?php }?>><?php echo strtoupper($data['nama'])?></option>
    <?php }?>
  </select>
  </td>
</tr>
<tr>
  <th>Pekerjaan</th>
  <td>
    <select name="pekerjaan_id">
      <option value="">Pilih Pekerjaan</option>
      <?php  foreach($pekerjaan as $data){?>
        <option value="<?php echo $data['id']?>" <?php if($penduduk['pekerjaan_id']==$data['id']){?>selected<?php }?>><?php echo strtoupper($data['nama'])?></option>
      <?php }?>
    </select>
  </td>
</tr>

<tr>
  <th colspan="2" class="bagian">DATA KEWARGANEGARAAN</th>
</tr>
<tr>
  <th>Warganegara</th>
  <td>
    <select name="warganegara_id">
      <option value="">Pilih warganegara</option>
      <?php foreach($warganegara as $data){?>
        <option value="<?php echo $data['id']?>" <?php if($penduduk['warganegara_id']==$data['id']){?>selected<?php }?>><?php echo strtoupper($data['nama'])?></option>
      <?php }?>
  </select>
  </td>
</tr>
<tr>
  <th>Nomor Paspor</th>
  <td><input name="dokumen_pasport" type="text" class="inputbox" size="20"  value="<?php echo strtoupper($penduduk['dokumen_pasport'])?>"/></td>
</tr>
<tr>
  <th>Tanggal Berakhir Paspor</th>
  <td><input name="tanggal_akhir_paspor" type="text" class="inputbox datepicker" size="20"  value="<?php echo $penduduk['tanggal_akhir_paspor']?>"/></td>
</tr>

<tr>
  <th>Nomor KITAS/KITAP</th>
  <td><input name="dokumen_kitas" type="text" class="inputbox" size="20"  value="<?php echo strtoupper($penduduk['dokumen_kitas'])?>"/></td>
</tr>

<tr>
  <th colspan="2" class="bagian">ORANG TUA</th>
</tr>
<tr>
  <th>NIK Ayah</th>
  <td><input name="ayah_nik" type="text" class="inputbox" size="30"  value="<?php echo $penduduk['ayah_nik']?>"/></td>
</tr>
<tr>
  <th>Nama Ayah</th>
  <td><input name="nama_ayah" type="text" class="inputbox" size="60"  value="<?php echo strtoupper($penduduk['nama_ayah'])?>"/></td>
</tr>
<tr>
  <th>NIK Ibu</th>
  <td><input name="ibu_nik" type="text" class="inputbox" size="30"  value="<?php echo $penduduk['ibu_nik']?>"/></td>
</tr>
<tr>
  <th>Nama Ibu</th>
  <td><input name="nama_ibu" type="text" class="inputbox" size="60"  value="<?php echo strtoupper($penduduk['nama_ibu'])?>"/></td>
</tr>
<tr>
  <th colspan="2" class="bagian">ALAMAT</th>
</tr>
<tr>
  <th>Nomor Telepon</th>
  <td><input name="telepon" type="text" class="inputbox" size="30" value="<?php echo $penduduk['telepon']?>"/></td>
</tr>
<tr>
  <th>Alamat Sebelumnya</th>
  <td><input name="alamat_sebelumnya" type="text" class="inputbox" size="60"  value="<?php echo strtoupper($penduduk['alamat_sebelumnya'])?>"/></td>
</tr>

<tr>
  <th>Alamat Sekarang</th>
  <td><input name="alamat_sekarang" type="text" class="inputbox" size="60"  value="<?php echo strtoupper($penduduk['alamat_sekarang'])?>"/></td>
</tr>
<?php if ($this->setting->offline_level < 2) { ?>
<tr>
  <th>Lokasi Penduduk</th>
  <td>
    <a href="<?php echo site_url("penduduk/ajax_penduduk_maps/1/0/$penduduk[id]")?>" target="ajax-modalz" rel="window<?php echo $penduduk['id']?>" header="Lokasi <?php echo $penduduk['nama']?>" class="uibutton special" title="Lokasi <?php echo $penduduk['nama']?>">Edit Lokasi</a>
  </td>
</tr>
<?php } ?>
<tr>
  <th colspan="2" class="bagian">STATUS KAWIN</th>
</tr>
<tr>
  <th>Status Kawin</th>
  <td>
    <select name="status_kawin">
      <option value="">Pilih Status</option>
      <?php foreach($kawin as $data){?>
        <option value="<?php echo $data['id']?>" <?php if($penduduk['status_kawin']==$data['id']){?>selected<?php }?>><?php echo strtoupper($data['nama'])?></option>
      <?php }?>
    </select>
  </td>
</tr>
<tr>
  <?php if ($penduduk['agama_id']==0 OR is_null($penduduk['agama_id'])): ?>
    <th>No. Akta Nikah (Buku Nikah)/Perkawinan</th>
  <?php elseif ($penduduk['agama_id']==1): ?>
    <th>No. Akta Nikah (Buku Nikah)</th>
  <?php else: ?>
    <th>No. Akta Perkawinan</th>
  <?php endif; ?>
  <td><input name="akta_perkawinan" type="text" class="inputbox" size="60"  value="<?php echo $penduduk['akta_perkawinan']?>"/></td>
</tr>

<tr>
  <th>Tanggal Perkawinan</th>
  <td><input name="tanggalperkawinan" type="text" class="inputbox datepicker" size="20"  value="<?php echo $penduduk['tanggalperkawinan']?>"/></td>
</tr>

<tr>
  <th>Akta Perceraian</th>
  <td><input name="akta_perceraian" type="text" class="inputbox" size="60"  value="<?php echo $penduduk['akta_perceraian']?>"/></td>
</tr>

<tr>
  <th>Tanggal Perceraian</th>
  <td><input name="tanggalperceraian" type="text" class="inputbox datepicker" size="20"  value="<?php echo $penduduk['tanggalperceraian']?>"/></td>
</tr>

<tr>
  <th colspan="2" class="bagian">DATA KESEHATAN</th>
</tr>
<tr>
  <th>Golongan Darah</th>
  <td>
    <select name="golongan_darah_id" class="required">
      <option value="">Pilih Golongan Darah</option>
      <?php foreach($golongan_darah as $data){?>
        <option value="<?php echo $data['id']?>" <?php if($penduduk['golongan_darah_id']==$data['id']){?>selected<?php }?>><?php echo strtoupper($data['nama'])?></option>
      <?php }?>
    </select>
  </td>
</tr>
<tr>
  <th>Cacat</th>
  <td>
    <select name="cacat_id">
      <option value="">Pilih Jenis</option>
      <?php foreach($cacat as $data){?>
        <option value="<?php echo $data['id']?>" <?php if($penduduk['cacat_id']==$data['id']){?>selected<?php }?>><?php echo strtoupper($data['nama'])?></option>
      <?php }?>
    </select>
  </td>
</tr>
<tr>
  <th>Sakit Menahun</th>
  <td>
    <select name="sakit_menahun_id">
      <option value="">Pilih Jenis</option>
      <?php foreach($sakit_menahun as $data){?>
        <option value="<?php echo $data['id']?>" <?php if($penduduk['sakit_menahun_id']==$data['id']){?>selected<?php }?>><?php echo strtoupper($data['nama'])?></option>
      <?php }?>
    </select>
  </td>
</tr>

<?php if(!$penduduk['status_kawin'] OR $penduduk['status_kawin'] == 2): ?>
  <tr>
    <th>Akseptor KB</th>
    <td>
      <select name="cara_kb_id">
        <option value="">Pilih Cara KB Saat Ini</option>
        <?php foreach($cara_kb as $data){?>
          <option value="<?php echo $data['id']?>" <?php if($penduduk['cara_kb_id']==$data['id']){?>selected<?php }?>><?php echo strtoupper($data['nama'])?></option>
        <?php }?>
      </select>
    </td>
  </tr>
<?php endif; ?>
<tr>
  <th>Status Kehamilan</th>
  <td>
    <div class="uiradio">
      <input type="radio" id="sh2" name="hamil" value="0"/<?php if($penduduk['hamil'] == '0' OR $penduduk['hamil'] == ''){echo 'checked';}?>>
      <label for="sh2">Tidak hamil</label>
      <input type="radio" id="sh1" name="hamil" value="1"/<?php if($penduduk['hamil'] == '1' ){echo 'checked';}?>>
      <label for="sh1">Hamil</label>
    </div>
  </td>
</tr>
