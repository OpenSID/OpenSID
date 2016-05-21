<script>
$(function(){
var nik = {};
nik.results = [
<?foreach($penduduk as $data){?>
{id:'<?=$data['id']?>',name:"<?=$data['nik']." - ".($data['nama'])?>",info:"<?=($data['alamat'])?>"},
<?}?>
];

$('#nik').flexbox(nik, {
resultTemplate: '<div><label>No nik : </label>{name}</div><div>{info}</div>',
watermark: <?if($individu){?>'<?=$individu['nik']?> - <?=spaceunpenetration($individu['nama'])?>'<?}else{?>'Ketik no nik di sini..'<?}?>,
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
	<td class="side-menu">
				<fieldset>
<legend>Surat Administrasi</legend>
<div  id="sidecontent2" class="lmenu">
<ul>
<?foreach($menu_surat AS $data){?>
        <li <? if($data['url_surat']==$lap){?>class="selected"<? }?>><a href="<?=site_url()?>surat/<?=$data['url_surat']?>"><?=unpenetration($data['nama'])?></a></li>
<?}?>
</ul>
</div>
</fieldset>
		
	</td>
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

<form id="validasi" action="<?=$form_action?>" method="POST" target="_blank">
<input type="hidden" name="nik" value="<?=$individu['id']?>"  class="inputbox required" >
<?if($individu){ //bagian info setelah terpilih?>
<tr>
<th>Tempat Tanggal Lahir (Umur)</th>
<td>
<?=$individu['tempatlahir']?> <?=tgl_indo($individu['tanggallahir'])?> (<?=$individu['umur']?> Tahun)
</td>
</tr>
<tr>
<th>Alamat</th>
<td>
<?=unpenetration($individu['alamat']); ?>
</td>
</tr>
<tr>
<th>Pendidikan</th>
<td>
<?=$individu['pendidikan']?>
</td>
</tr>
<tr>
<th>Warganegara / Agama</th>
<td>
<?=$individu['warganegara']?> / <?=$individu['agama']?>
</td>
</tr>
<?}?>
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
		<? if($anggota != NULL){$i=0;?>
		<? foreach($anggota AS $data){ $i++;?>
		<tr>
            <td align="center" width="2"><?=$i?></td>
			<td align="center" width="5">
				<input type="checkbox" name="id_cb[]" value="'<?=$data['nik']?>'" />
			</td>
			<td><?=$data['nik']?></td>
			<td><?=unpenetration($data['nama'])?></td>
			<td><?=$data['sex']?></td>
			<td><?=$data['umur']?></td>
			<td><?=$data['status_kawin']?></td>
			<td><?=$data['pendidikan']?></td>
		</tr>  
		<?}?>
		<?}?>
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
<select name="pamong"  class="inputbox required" >
<option value="">Pilih Staf Pemerintah Desa</option>
<?foreach($pamong AS $data){?>
<option value="<?=$data['pamong_id']?>"><font style="bold"><?=unpenetration($data['pamong_nama'])?></font> (<?=unpenetration($data['jabatan'])?>)</option>
<?}?>
</select>
</td>
</tr>
<tr>
<th>Sebagai</th>
<td>
<select name="jabatan"  class="inputbox required">
<option value="">Pilih Jabatan</option>
<?foreach($pamong AS $data){?>
<option ><?=unpenetration($data['jabatan'])?></option>
<?}?>
</select>
</td>
</tr>
</table>

</div>
   
    <div class="ui-layout-south panel bottom">
        <div class="left">     
            <a href="<?=site_url()?>surat" class="uibutton icon prev">Kembali</a>
        </div>
        <div class="right">
            <div class="uibutton-group">
                <button class="uibutton" type="reset">Clear</button>
                <button class="uibutton confirm" type="submit" >Cetak</button>
            </div>
        </div>
    </div> </form>
</div>
</td></tr></table>
</div>
