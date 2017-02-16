<script>
$(function(){
var nik = {};
nik.results = [
<?php foreach($penduduk as $data){?>
{id:'<?php echo $data['id']?>',name:"<?php echo $data['nik']." - ".($data['nama'])?>",info:"<?php echo ($data['alamat'])?>"},
<?php }?>
];
$('#nik').flexbox(nik, {
resultTemplate: '<div><label>No nik : </label>{name}</div><div>{info}</div>',
watermark: <?php if($individu){?>'<?php echo $individu['nik']?> - <?php echo spaceunpenetration($individu['nama'])?>'<?php }else{?>'Ketik no nik di sini..'<?php }?>,
width: 260,
noResultsText :'Tidak ada no nik yang sesuai..',
onSelect: function() {
$('#'+'main').submit();
} 
});
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
</style>
<div id="pageC">
	<table class="inner">
	<tr style="vertical-align:top">
<td style="background:#fff;padding:5px;"> 
<div class="content-header">
</div>
<div id="contentpane">
<div class="ui-layout-north panel">
<h3>Surat Keterangan Pindah Penduduk</h3>
</div>
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">
<tr>
<th width="80">NIK / Nama</th>
<td>
<form action="" id="main" name="main" method="POST">
<div id="nik" name="nik"></div>
</form>
</tr>
<form id="validasi" action="<?php echo $form_action?>" method="POST" target="_blank">
<input type="hidden" name="nik" value="<?php echo $individu['id']?>" class="inputbox required" >
<?php if($individu){ ?>
<tr>
<th>Tempat Tanggal Lahir (Umur)</th>
<td>
<?php echo $individu['tempatlahir']?> <?php echo tgl_indo($individu['tanggallahir'])?> (<?php echo $individu['umur']?> Tahun)
</td>
</tr>
<tr>
<th>Alamat</th>
<td>
<?php echo unpenetration($individu['alamat']); ?>
</td>
</tr>
<tr>
<th>Pendidikan</th>
<td>
<?php echo $individu['pendidikan']?>
</td>
</tr>
<tr>
<th>Warganegara / Agama</th>
<td>
<?php echo $individu['warganegara']?> / <?php echo $individu['agama']?>
</td>
</tr>
<tr>
<th>Dokumen Kelengkapan / Syarat</th>
<td>
<a header="Dokumen" target="ajax-modal" rel="dokumen" href="<?php echo site_url("penduduk/dokumen_list/$individu[id]")?>" class="uibutton special">Daftar Dokumen</a><a target="_blank" href="<?php echo site_url("penduduk/dokumen/$individu[id]")?>" class="uibutton confirm">Manajemen Dokumen</a> )* Atas Nama <?php echo $individu['nama']?> [<?php echo $individu['nik']?>]
</td>
</tr>
<?php }?>
			<tr>
				<th>Nomor Surat</th>
				<td>
					<input name="nomor" type="text" class="inputbox required" size="12"/>
				</td>
			</tr>
			<tr>
				<th>Alasan Pindah</th>
				<td>
					<input name="alasan" type="text" class="inputbox required" size="40"/>
				</td>
			</tr>
			<tr>
				<th>Jumlah Pengikut</th>
				<td>
					<input name="jml_pengikut" type="text" class="inputbox required" size="40"/>
				</td>
			</tr>
			<tr>
				<th colspan="1">Pengikut</th>
			<td colspan="1">
<div style="margin-left:0px;">
<table class="list">
	<thead>
		<tr>
			<th>No</th>
			<th><input type="checkbox" class="checkall"/></th>
			<th align="left" width='70'>NIK</th>
			<th align="left" width='100'>Nama</th>
			<th align="left" width='30' align="center">JK</th>
			<th width="70" align="left" >Umur</th>
			<th width="70" align="left" >Status Kawin</th>
			<th width="100" align="left" >Pendidikan</th> 
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
			<td><?php echo $data['umur']?></td>
			<td><?php echo $data['status_kawin']?></td>
			<td><?php echo $data['pendidikan']?></td>
		</tr> 
		<?php }?>
		<?php }?>
	</tbody>
</table>
</div>
		</td>
			</tr>
 
 
			
			<tr>
				<th>Pindah Ke</th>
				</tr>
			<tr>	
				<th>RW</th>
				<td>
					<input name="rw_tujuan" type="text" class="inputbox required" size="40"/>
				</td>
			</tr>
			<tr>
				<th>RT</th>
				<td>
					<input name="rt_tujuan" type="text" class="inputbox required" size="40"/>
				</td>
			</tr>
			<tr>
				<th>Kampung</th>
				<td>
					<input name="kampung_tujuan" type="text" class="inputbox required" size="40"/>
				</td>
			</tr>
			<tr>
				<th>Kelurahan</th>
				<td>
					<input name="kelurahan_tujuan" type="text" class="inputbox required" size="40"/>
				</td>
			</tr>
			<tr>
				<th>Kecamatan</th>
				<td>
					<input name="kecamatan_tujuan" type="text" class="inputbox required" size="40"/>
				</td>
			</tr>
			<tr>
				<th>kabupaten</th>
				<td>
					<input name="kabupaten_tujuan" type="text" class="inputbox required" size="40"/>
				</td>
			</tr>
			<tr>
				<th>provinsi</th>
				<td>
					<input name="kantor_tujuan" type="text" class="inputbox required" size="40"/>
				</td>
			</tr>
			<tr>
				<th>Berlaku</th>
				<td>
					<input name="awal" type="text" class="inputbox required datepicker" size="20"/>
				</td>
			</tr>
			<tr>
				<th>Keterangan</th>
				<td>
					<input name="keterangan" type="text" class="inputbox required" size="20"/>
				</td>
			</tr>
			
	<tr>
<th>Staf Pemerintah Desa</th>
<td>
<select name="pamong" class="inputbox required" >
<option value="">Pilih Staf Pemerintah Desa</option>
<?php foreach($pamong AS $data){?>
<option value="<?php echo $data['pamong_nama']?>"><font style="bold"><?php echo unpenetration($data['pamong_nama'])?></font> (<?php echo unpenetration($data['jabatan'])?>)</option>
<?php }?>
</select>
</td>
</tr>
<tr>
<th>Sebagai</th>
<td>
<select name="jabatan" class="inputbox required">
<option value="">Pilih Jabatan</option>
<?php foreach($pamong AS $data){?>
<option ><?php echo unpenetration($data['jabatan'])?></option>
<?php }?>
</select>
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
 
 
							<button type="button" onclick="$('#'+'validasi').attr('action','<?php echo $form_action?>');$('#'+'validasi').submit();" class="uibutton special"><span class="ui-icon ui-icon-print">&nbsp;</span>Cetak</button>
							<?php if (file_exists("surat/$url/$url.rtf")) { ?><button type="button" onclick="$('#'+'validasi').attr('action','<?php echo $form_action2?>');$('#'+'validasi').submit();" class="uibutton confirm"><span class="ui-icon ui-icon-document">&nbsp;</span>Export Doc</button><?php } ?>
 </div>
 </div>
 </div> </form>
</div>
</td></tr></table>
</div>