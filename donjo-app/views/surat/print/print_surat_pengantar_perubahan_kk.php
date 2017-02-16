<?php $this->load->view('print/headjs.php');?>
<div id="content" class="container_11 clearfix">
<div id="content-main" class="grid_7">   
<link href="<?php echo base_url()?>assets/css/surat.css" rel="stylesheet" type="text/css" />
<div align="left"><u><h3 class="left"><font size="3">FORMULIR PERMOHONAN PERUBAHAN KARTU KELUARGA (KK) WARGA NEGARA INDONESIA</font></h3></u></div>
<table width="100%">
<td>Perhatian:
<table width="100%">
<tr><td >1. Harap diisi dengan huruf cetak dan menggunakan tinta hitam  
<tr><td >2. Setelah formulir ini diisi dan ditandatangani, harap diserahkan kembali ke Kantor Desa/Kelurahan</td></tr>
</table>
<table width = 600 border =1>
<tr>
<tr><td width="40%">PEMERINTAH PROPINSI </td><td width="6%">: <?php echo $desa['kode_propinsi']?> </td><td class="indentasi" width="35%" ><?php echo $desa['nama_propinsi']?></td>
<tr><td width="25%">PEMERINTAH KABUPATEN</td><td width="3%">: <?php echo $desa['kode_kabupaten']?></td><td class="indentasi"><?php echo $desa['nama_kabupaten']?></td>
<tr><td width="25%">KECAMATAN </td><td width="3%">: <?php echo $desa['kode_kecamatan']?></td><td class="indentasi"><?php echo $desa['nama_kecamatan']?></td>
<tr><td width="25%">KELURAHAN/DESA </td><td width="3%">: <?php echo $desa['kode_desa']?></td><td class="indentasi"><?php echo $desa['nama_desa']?></td>
</tr>
</tr>
</table>
<table width = 650 border =1>
<tr>
<tr><td width="40%">1.	Nama Lengkap Pemohon</td><td><?php echo unpenetration($data['nama'])?></td>
<tr><td width="25%">2.	NIK Pemohon</td><td><?php echo $data['nik']?></td>
<tr><td width="25%">3.	Nama Kepala Keluarga</td><td><?php echo unpenetration($data['kepala_kk'])?></td>
<tr><td width="25%">4.	No. KK</td><td><?php echo $data['no_kk']?></td>
<tr><td width="25%">5.	Alamat</td><td><?php echo $data['alamat']?> RT. <?php echo $data['rt']?> RW. <?php echo $data['rw']?> Dusun <?php echo unpenetration(ununderscore($data['dusun']))?> Desa <?php echo unpenetration($desa['nama_desa'])?> Kecamatan <?php echo unpenetration($desa['nama_kecamatan'])?> Kabupaten <?php echo unpenetration($desa['nama_kabupaten'])?></td>
</tr>

<table width = 650 border =1>
<tr>
<tr><td >a. Desa/Keluarga </td><td width="30%">: <?php echo $desa['nama_desa']?></td><td width="20%">b. Kecamatan<td width="30%">: <?php echo $desa['nama_kecamatan']?></td></td>
<tr><td >c. Kabupaten/Kota</td><td width="3%">: <?php echo $desa['nama_kabupaten']?></td><td width="6%">d. Propinsi<td width="6%">: <?php echo $desa['nama_propinsi']?></td></td>
<tr><td >Kode Pos</td><td width="3%">: <?php echo $desa['kode_pos']?></td><td width="6%">Telepon<td width="6%">: <?php echo $input['telepon']?></td>
</table>
<table width = 650 border =1>
<tr>
<tr><td width="40%">6.	Nama Kepala Keluarga Lama </td><td><?php echo unpenetration($data['kepala_kk'])?></td>
<tr><td width="25%">7.	No. KK Lama</td><td><?php echo $data['no_kk']?></td>
<tr><td width="25%">8.	Alamat Keluarga Lama</td><td><?php echo $data['rt']?>, RW. <?php echo $data['rw']?>, Dusun <?php echo unpenetration(ununderscore($data['dusun']))?>, Desa <?php echo unpenetration($desa['nama_desa'])?>, Kec. <?php echo unpenetration($desa['nama_kecamatan'])?>, Kab. <?php echo unpenetration($desa['nama_kabupaten'])?></td>
</tr>
<table width = 650 border =1>
<tr>
<tr><td >a. Desa/Keluarga </td><td width="30%">: <?php echo $desa['nama_desa']?></td><td width="20%">b. Kecamatan<td width="30%">: <?php echo $desa['nama_kecamatan']?></td></td>
<tr><td >c. Kabupaten/Kota</td><td width="3%">: <?php echo $desa['nama_kabupaten']?></td><td width="6%">d. Propinsi<td width="6%">: <?php echo $desa['nama_propinsi']?></td></td>
<tr><td >Kode Pos</td><td width="3%">: <?php echo $desa['kode_pos']?></td><td width="6%">Telepon<td width="6%">: <?php echo $input['telepon']?></td>
<table width = 250 border =1>
<tr>
<tr><td >9.	Alasan Permohonan </td><td width="10%">
</table>
<table width="100%">
<tr><tr >1. Karena Penambahan Anggota Keluarga (Kelahiran, Kedatangan)
<tr><tr >2. Karena Pengurangan Anggota Keluarga (Kematian, Kepindahan)
<tr><tr >3. Lainnya
<table width = 350 border =1>
<tr>
<tr><td >10. Jumlah Anggota Keluarga </td><td width="20%"><?php echo $input['jml_keluarga']?> Orang</td>
</table>
<?php  	
	$i=0;
	if($pengikut){
		foreach($pengikut AS $data1){
			$i++;
		}
	}
?>
<tr><td>11.	DAFTAR ANGGOTA KELUARGA PEMOHON (hanya diisi Anggota keluarga saja) 
</table>
<tr></tr>
<?php  if($pengikut){ ?>
<table style="border:1px solid ;" width="100%">
	<thead>
		<tr>
			<th>No</th>
			<th align="left" >NIK</th>
			<th align="left" >Nama</th>
			<th align="left" align="center">JK</th>
			<th  align="left" >Umur</th>
			<th align="left" >Status Kawin</th>      
		</tr>
	<tbody><?php  $i=0;?>
		<?php  foreach($pengikut AS $data1){$i++;?>
		<tr>
            <td align="center" width="2"><?php echo $i?></td>
			<td><?php echo $data1['nik']?></td>
			<td><?php echo unpenetration($data1['nama'])?></td>
			<td><?php echo $data1['sex']?></td>
			<td><?php echo $data1['umur']?></td>
			<td><?php echo $data1['status_kawin']?></td>
		</tr>  
		<?php  }?>
	</tbody>

<?php  } ?>
<tr></tr>
<?php  if($pengikut){ ?>
<tr></tr>	
<tr></tr>
<tr></tr>
<tr></tr>
<?php  } ?>
</table>
<table width="100%">
<tr></tr>
<tr></td><td width="30%"align="center">Mengetahui, </td></tr>
<tr></td></td><td width="40%" align="center">Kepala Dinas Kependudukan dan<tr></td></tr>
<tr></td></td><td width="40%" align="center">Pencatatan Sipi Kabupaten Lombok Timur</td></td><td width="30%"align="center">Camat <?php echo unpenetration($desa['nama_kecamatan'])?></td><td width="30%" align="center"><?php echo unpenetration($input['jabatan'])?></td></td><td  align="center">Pemohon<tr></td></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr><td width="30%" align="center">____________________</td><td width="40%" align="center"><?php echo unpenetration($desa['nama_kepala_camat'])?></td><td align="center"> <?php echo unpenetration($input['pamong'])?> </td><td align="center"><?php echo unpenetration($data['nama'])?></td></tr>
<tr></td><td width="30%" align="center">NIP. ......................... </td><td width="30%" align="center">NIP. <?php echo unpenetration($desa['nip_kepala_camat'])?> </td><td width="30%" align="center"><?php echo unpenetration($input['pamong_nip'])?>
</table> 
<table width="100%">
<tr><td width="30%"></td><td width="36%"></td><td  ><?php echo unpenetration($desa['nama_desa'])?>, <?php echo $tanggal_sekarang?> 
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr><td width="30%"></td><td width="36%"></td><td  >Paraf Petugas , ...............................
 </div></div>
</div>
<div id="aside">
</div>
<div id="footer" class="container_12">
</div></div>
</body>
</html>
