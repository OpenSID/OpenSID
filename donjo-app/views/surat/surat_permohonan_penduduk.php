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
        <li <? if($data['url_surat']==$lap){?>class="selected"<? }?>><a href="<?=site_url()?>surat/<?=$data['url_surat']?>"><?=$data['nama']?></a></li>
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
<h3>Surat Permohonan Penduduk</h3>
</div>

    <form id="validasi" action="<?=$form_action?>" method="POST" target="_blank">
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
        <table class="form">
		<tr>
				<th>NIK</th>
				<td>
					<input name="nik" type="text" class="inputbox required" size="12"/>
				</td>
			</tr>
			<tr>
				<th>Nomor Surat</th>
				<td>
					<input name="nomor" type="text" class="inputbox required" size="12"/>
				</td>
			</tr>
			<tr>
				<th>Keperluan</th>
				<td>
					<input name="keperluan" type="text" class="inputbox required" size="40"/>
				</td>
			</tr>
			<tr>
				<th>Kantor Tujuan</th>
				<td>
					<input name="kantor_tujuan" type="text" class="inputbox required" size="40"/>
				</td>
			</tr>
			<tr>
				<th>Masa Berlaku</th>
				<td>
					<input name="awal" type="text" class="inputbox required datepicker" size="20"/> S/d 
					<input name="akhir" type="text" class="inputbox required datepicker" size="20"/>
				</td>
			</tr>
			<tr>
				<th>Keterangan Lain</th>
				<td>
					<input name="lain" type="text" class="inputbox" value="" size="40"/>
				</td>
			</tr>
		<tr>
<th>Staf Pemerintah Desa</th>
<td>
<select name="pamong"  class="inputbox required">
<option value="">Pilih Staf Pemerintah Desa</option>
<?foreach($pamong AS $data){?>
<option value="<?=$data['pamong_id']?>"><font style="bold"><?=$data['pamong_nama']?></font> (<?=$data['jabatan']?>)</option>
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
<option ><?=$data['jabatan']?></option>
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
