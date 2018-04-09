<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');?>

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
<?php foreach($menu_surat AS $data){?>
        <li <?php  if($data['url_surat']==$lap){?>class="selected"<?php  }?>><a href="<?php echo site_url()?>surat/<?php echo $data['url_surat']?>"><?php echo $data['nama']?></a></li>
<?php }?>
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

    <form id="validasi" action="<?php echo $form_action?>" method="POST" target="_blank">
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
					<input name="nomor" type="text" class="inputbox required" size="12"/> <span>Terakhir: <?php echo $surat_terakhir['no_surat'];?> (tgl: <?php echo $surat_terakhir['tanggal']?>)</span>
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
					<input name="awal" type="text" class="inputbox required datepicker-start" size="20"/> S/d
					<input name="akhir" type="text" class="inputbox required datepicker-end" size="20"/>
				</td>
			</tr>
			<tr>
				<th>Keterangan Lain</th>
				<td>
					<input name="lain" type="text" class="inputbox" value="" size="40"/>
				</td>
			</tr>
		<tr>
<th>Staf Pemerintah <?php echo ucwords($this->setting->sebutan_desa)?></th>
<td>
<select name="pamong"  class="inputbox required">
<option value="">Pilih Staf Pemerintah <?php echo ucwords($this->setting->sebutan_desa)?></option>
<?php foreach($pamong AS $data){?>
<option value="<?php echo $data['pamong_nama']?>"><font style="bold"><?php echo $data['pamong_nama']?></font> (<?php echo $data['jabatan']?>)</option>
<?php }?>
</select>
</td>
</tr>
<tr>
<th>Sebagai</th>
<td>
<select name="jabatan"  class="inputbox required">
<option value="">Pilih Jabatan</option>
<?php foreach($pamong AS $data){?>
<option ><?php echo $data['jabatan']?></option>
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
                <button class="uibutton" type="reset"><span class="fa fa-refresh"></span> Bersihkan</button>

							<button type="button" onclick="$('#'+'validasi').attr('action','<?php echo $form_action?>');$('#'+'validasi').submit();" class="uibutton special"><span class="fa fa-print">&nbsp;</span>Cetak</button>
							<?php if (SuratExport($url)) { ?><button type="button" onclick="$('#'+'validasi').attr('action','<?php echo $form_action2?>');$('#'+'validasi').submit();" class="uibutton confirm"><span class="fa fa-file-text">&nbsp;</span>Export Doc</button><?php } ?>
            </div>
        </div>
    </div> </form>
</div>
</td></tr></table>
</div>
