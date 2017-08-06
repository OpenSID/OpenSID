<script>
	$(function(){
		var nik = {};
		nik.results = [
			<?php  foreach($penduduk as $data){?>
				{id:'<?php echo $data['id']?>',name:"<?php echo $data['nik']." - ".($data['nama'])?>",info:"<?php echo ($data['alamat'])?>"},
			<?php  }?>
		];
		$('#nik').flexbox(nik, {
			resultTemplate: '<div><label>No nik : </label>{name}</div><div>{info}</div>',
			watermark: <?php  if($individu){?>'<?php echo $individu['nik']?> - <?php echo ($individu['nama'])?>'<?php  }else{?>'Ketik no nik di sini..'<?php  }?>,
			width: 260,
			noResultsText :'Tidak ada no nik yang sesuai..',
			onSelect: function(){$('#'+'main').submit();}  
		});
	
	
	$('#showData').click(function(){ 
		$('tr.hide').show();
		$('#showData').hide();
		$('#hideData').show();		
	});
	
	$('#hideData').click(function(){ 
		$('tr.hide').hide();
		$('#hideData').hide();
		$('#showData').show();		
	});
	
		$('#hideData').hide();
	});
</script>
<style>
table.form.detail th{
	padding:5px;
	background:#fafafa;
	border-right:1px solid #eee;
}
table.form.detail td{
	padding:5px;
}
tr .hide{
	display:none;
}
</style>
<div id="pageC">
	<table class="inner">
	<tr style="vertical-align:top">
	<td style="background:#fff;"> 
		<div id="contentpane">
			<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
				<h3>Formulir Layanan : Surat Keterangan Beda Identitas</h3>
				<div id="form-cari-pemohon">
					<form action="" id="main" name="main" method="POST" class="formular">
					<table class="form">
						<tr>
							<td width="150">NIK / Nama Kepala Keluarga</td>
							<td>
								<div id="nik" name="nik"></div>
							</td>
						</tr>
					</table>
					</form>
				</div>
				</br>
				<div id="form-melengkapi-data-permohonan">
					<form id="validasi" action="" method="POST" target="_blank">
					<input type="hidden" name="nik" value="<?php echo $individu['id']?>" class="inputbox required" >
					<table class="form">
						<?php 
						if($individu){ 
							?>
							<tr>
								<th width="10">Tempat Tanggal Lahir (Umur)</th>
								<td>
									<?php echo $individu['tempatlahir']?> <?php echo tgl_indo($individu['tanggallahir'])?> (<?php echo $individu['umur']?> Tahun)
								</td>
							</tr>
							<tr>
								<th>Alamat</th>
								<td><?php echo unpenetration($individu['alamat']); ?></td>
							</tr>
							<tr>
								<th>Pendidikan</th>
								<td><?php echo $individu['pendidikan']; ?></td>
							</tr>
							<tr>
								<th>Warganegara / Agama</th>
								<td><?php echo $individu['warganegara']?> / <?php echo $individu['agama']?></td>
							</tr>
							<tr>
<th>Dokumen Kelengkapan / Syarat</th>
<td>
<a header="Dokumen" target="ajax-modal" rel="dokumen" href="<?php echo site_url("penduduk/dokumen_list/$individu[id]")?>" class="uibutton special">Daftar Dokumen</a><a target="_blank" href="<?php echo site_url("penduduk/dokumen/$individu[id]")?>" class="uibutton confirm">Manajemen Dokumen</a> )* Atas Nama <?php echo $individu['nama']?> [<?php echo $individu['nik']?>]
</td>
</tr>
<?php }?>
<tr>
<th>Data Keluarga / KK </th>
<td></td>								
</tr>
<tr>								
								<th colspan="1">Keluarga</th>
								<td colspan="1">
									<div style="margin-left:100px;">
										<table class="list">
											<thead>
												<tr>
													<th>No</th>
													<th><input type="checkbox" class="checkall"/></th>
													<th align="left" width='70'>NIK</th>
													<th align="left" width='100'>Nama</th>
													<th align="left" width='30' align="center">Jenis Kelamin</th>
													<th align="left" width='30' align="center">Tempat Tanggal Lahir</th>
													<th width="70" align="left" >Hubungan</th>
																																				 
												</tr>
											</thead>
											<tbody>
												<?php 
												if($anggota!=NULL){
													$i=0;?>
												<?php foreach($anggota AS $data){ $i++;?>
												<tr>
													<td align="center" width="2"><?php echo $i?></td>
													<td align="center" width="5">
														<input type="checkbox" name="id_cb[]" value="'<?php echo $data['nik']?>'" />
													</td>
													<td><?php echo $data['nik']?></td>
													<td><?php echo unpenetration($data['nama'])?></td>
													<td><?php echo $data['sex']?></td>
													<td><?php echo $data['tempatlahir']?>, <?php echo tgl_indo($data['tanggallahir'])?></td>
													<td><?php echo $data['hubungan']?></td>
													<td><?php echo $data['status_kawin']?></td>
													
												</tr> 
												<?php }?>
												<?php }?>
											</tbody>
										</table>
									</div>
								</td>
							</tr>

<tr>
<td>BIODATA Kepala Keluarga dan Anggota Keluarga di Kartu Kartu Indonesia Sehat</td>							
</tr>		 
<tr> 
<th>Anggota Keluarga 1</th> 
</tr>
<tr>
<td width="0%">No. Urut<td> <input name="nomor1" type="text" class="inputbox " size="1"/<option value="1"></td></tr>
<tr> 
<td width="0%">No. Kartu<td> <input name="kartu1" type="text" class="inputbox " size="25"/> </td></tr>
<tr>
<td width="0%">Nama di Kartu<td> <input name="nama1" type="text" class="inputbox " size="40"/></td></tr> 
<tr>
<td width="0%">NIK<td> <input name="nik1" type="text" class="inputbox " size="21"/> </td></tr> 
<tr>
<td width="0%">Alamat di Kartu<td> <input name="alamat1" type="text" class="inputbox " size="70"/></td></tr> 
<td width="0%">Tanggal Lahir<td>  <input name="ttl1" type="text" class="inputbox " size="9"/></td></tr> 
<tr>
<td width="0%">Faskes Tingkat I<td> <input name="faskes1" type="text" class="inputbox " size="40"/></td></tr>

<tr> 
<th>Anggota Keluarga 2</th> 
</tr>
<tr>
<td width="0%">No. Urut<td> <input name="nomor2" type="text" class="inputbox " size="1"/<option value="2"></td></tr>
<tr> 
<td width="0%">No. Kartu<td> <input name="kartu2" type="text" class="inputbox " size="25"/> </td></tr>
<tr>
<td width="0%">Nama di Kartu<td> <input name="nama2" type="text" class="inputbox " size="40"/></td></tr> 
<tr>
<td width="0%">NIK<td> <input name="nik2" type="text" class="inputbox " size="21"/> </td></tr> 
<tr>
<td width="0%">Alamat di Kartu<td> <input name="alamat2" type="text" class="inputbox " size="70"/></td></tr> 
<td width="0%">Tanggal Lahir<td>  <input name="ttl2" type="text" class="inputbox " size="9"/></td></tr> 
<tr>
<td width="0%">Faskes Tingkat I<td> <input name="faskes2" type="text" class="inputbox " size="40"/></td></tr>

<tr> 
<th>Anggota Keluarga 3</th> 
</tr>
<tr>
<td width="0%">No. Urut<td> <input name="nomor3" type="text" class="inputbox " size="1"/<option value="3"></td></tr>
<tr> 
<td width="0%">No. Kartu<td> <input name="kartu3" type="text" class="inputbox " size="25"/> </td></tr>
<tr>
<td width="0%">Nama di Kartu<td> <input name="nama3" type="text" class="inputbox " size="40"/></td></tr> 
<tr>
<td width="0%">NIK<td> <input name="nik3" type="text" class="inputbox " size="21"/> </td></tr> 
<tr>
<td width="0%">Alamat di Kartu<td> <input name="alamat3" type="text" class="inputbox " size="70"/></td></tr> 
<td width="0%">Tanggal Lahir<td>  <input name="ttl3" type="text" class="inputbox " size="9"/></td></tr> 
<tr>
<td width="0%">Faskes Tingkat I<td> <input name="faskes3" type="text" class="inputbox " size="40"/></td></tr>

<tr> 
<th>Anggota Keluarga 4</th> 
</tr>
<tr>
<td width="0%">No. Urut<td> <input name="nomor4" type="text" class="inputbox " size="1"/<option value="4"></td></tr>
<tr> 
<td width="0%">No. Kartu<td> <input name="kartu4" type="text" class="inputbox " size="25"/> </td></tr>
<tr>
<td width="0%">Nama di Kartu<td> <input name="nama4" type="text" class="inputbox " size="40"/></td></tr> 
<tr>
<td width="0%">NIK<td> <input name="nik4" type="text" class="inputbox " size="21"/> </td></tr> 
<tr>
<td width="0%">Alamat di Kartu<td> <input name="alamat4" type="text" class="inputbox " size="70"/></td></tr> 
<td width="0%">Tanggal Lahir<td>  <input name="ttl4" type="text" class="inputbox " size="9"/></td></tr> 
<tr>
<td width="0%">Faskes Tingkat I<td> <input name="faskes4" type="text" class="inputbox " size="40"/></td></tr>

<tr> 
<th>Anggota Keluarga 5</th> 
</tr>
<tr>
<td width="0%">No. Urut<td> <input name="nomor5" type="text" class="inputbox " size="1"/<option value="5"></td></tr>
<tr> 
<td width="0%">No. Kartu<td> <input name="kartu5" type="text" class="inputbox " size="25"/> </td></tr>
<tr>
<td width="0%">Nama di Kartu<td> <input name="nama5" type="text" class="inputbox " size="40"/></td></tr> 
<tr>
<td width="0%">NIK<td> <input name="nik5" type="text" class="inputbox " size="21"/> </td></tr> 
<tr>
<td width="0%">Alamat di Kartu<td> <input name="alamat5" type="text" class="inputbox " size="70"/></td></tr> 
<td width="0%">Tanggal Lahir<td>  <input name="ttl5" type="text" class="inputbox " size="9"/></td></tr> 
<tr>
<td width="0%">Faskes Tingkat I<td> <input name="faskes5" type="text" class="inputbox " size="40"/></td></tr>

<tr> 
<th>Anggota Keluarga 6</th> 
</tr>
<tr>
<td width="0%">No. Urut<td> <input name="nomor6" type="text" class="inputbox " size="1"/<option value="6"></td></tr>
<tr> 
<td width="0%">No. Kartu<td> <input name="kartu6" type="text" class="inputbox " size="25"/> </td></tr>
<tr>
<td width="0%">Nama di Kartu<td> <input name="nama6" type="text" class="inputbox " size="40"/></td></tr> 
<tr>
<td width="0%">NIK<td> <input name="nik6" type="text" class="inputbox " size="21"/> </td></tr> 
<tr>
<td width="0%">Alamat di Kartu<td> <input name="alamat6" type="text" class="inputbox " size="70"/></td></tr> 
<td width="0%">Tanggal Lahir<td>  <input name="ttl6" type="text" class="inputbox " size="9"/></td></tr> 
<tr>
<td width="0%">Faskes Tingkat I<td> <input name="faskes6" type="text" class="inputbox " size="40"/></td></tr>

<tr> 
<th>Anggota Keluarga 7</th> 
</tr>
<tr>
<td width="0%">No. Urut<td> <input name="nomor7" type="text" class="inputbox " size="1"/<option value="7"></td></tr>
<tr> 
<td width="0%">No. Kartu<td> <input name="kartu7" type="text" class="inputbox " size="25"/> </td></tr>
<tr>
<td width="0%">Nama di Kartu<td> <input name="nama7" type="text" class="inputbox " size="40"/></td></tr> 
<tr>
<td width="0%">NIK<td> <input name="nik7" type="text" class="inputbox " size="21"/> </td></tr> 
<tr>
<td width="0%">Alamat di Kartu<td> <input name="alamat7" type="text" class="inputbox " size="70"/></td></tr> 
<td width="0%">Tanggal Lahir<td>  <input name="ttl7" type="text" class="inputbox " size="9"/></td></tr> 
<tr>
<td width="0%">Faskes Tingkat I<td> <input name="faskes7" type="text" class="inputbox " size="40"/></td></tr>

<tr>
<th width="200">Nomor Surat</th>
<td ><input name="nomor" type="text" class="inputbox " size="12"/></td>
</tr>
<tr>
<tr>
<th>Surat Keterangan ini dibuat untuk keperluan</th>
<td>
<input name="keperluan" type="text" class="inputbox required" size="60"/>
</td>
</tr>
<tr>
<th>Atas Nama</th>
<td>
<select name="atas_nama"  type="text" class="inputbox">
<option value="">Atas Nama</option>
<option value=""> </option>
<option value="An. Kepala Desa <?php echo unpenetration($desa['nama_desa'])?>"> An. Kepala Desa <?php echo unpenetration($desa['nama_desa'])?> </option>
<option value="Ub. Kepala Desa <?php echo unpenetration($desa['nama_desa'])?>"> Ub. Kepala Desa <?php echo unpenetration($desa['nama_desa'])?> </option>
</select>
</tr>
<tr>
<th>Sebagai</th>
<td>
<select name="jabatan"  class="inputbox required">
<option value="">Pilih Jabatan</option>
<?php foreach($pamong AS $data){?>
<option ><?php echo unpenetration($data['jabatan'])?></option>
<?php }?>
</select>
<tr>
<th>Staf Pemerintah Desa</th>
<td>
<select name="pamong"  class="inputbox required">
<option value="">Pilih Staf Pemerintah Desa</option>
<?php foreach($pamong AS $data){?>
<option value="<?php echo $data['pamong_nama']?>"><font style="bold"><?php echo unpenetration($data['pamong_nama'])?></font> (<?php echo unpenetration($data['jabatan'])?>)</option>
<?php }?>
<tr>
<th>N I P</th>
<td>
<select name="pamong_nip"  type="text" class="inputbox">
<option value="">Pilih No NIP</option>
<?php foreach($pamong AS $data){?>
<option ><?php echo($data['pamong_nip'])?></option>
<?php }?>
</select>

</td>
</tr>
</td>
</tr>
</table>
</div>
   
<div class="ui-layout-south panel bottom">
<div class="left">     
<a href="<?php echo site_url()?>surat" class="uibutton icon prev">Kembali</a>
</div>
<div class="right">
<div class="uibutton-group">
<button class="uibutton" type="reset">Clear</button>

<button type="button" onclick="$('#'+'validasi').attr('action','<?php echo $form_action?>');$('#'+'validasi').submit();" class="uibutton special"><span class="ui-icon ui-icon-print">&nbsp;</span>Cetak</button>
<?php if (file_exists("surat/$url/$url.rtf")) { ?><button type="button" onclick="$('#'+'validasi').attr('action','<?php echo $form_action2?>');$('#'+'validasi').submit();" class="uibutton confirm"><span class="ui-icon ui-icon-document">&nbsp;</span>Export Doc</button><?php } ?>
</div>
</div>
</div> </form>
</div>
</td></tr></table>
</div>