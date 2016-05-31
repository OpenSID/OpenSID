<div id="pageC"> 
<!-- Start of Space Admin -->
	<table class="inner">
	<tr style="vertical-align:top">

<td style="background:#fff;padding:0px;"> 
<div class="content-header">
</div>
<div id="contentpane">    

    <div class="ui-layout-north panel">
    <h3>Full Backup / Restore Data SID</h3>
    </div>


<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
        <div class="table-panel top">
            <div class="left">
               <table class="list">
<tr>
<td width="320">Backup Seluruh Data SID</td>
<td width="70">
<div class="uibutton-group">
<a class="uibutton special" href="<?php echo site_url("database")?>/exec_backup">Download</a>
</div>
</td>
</tr>
		</table>
<form action="<?php echo $form_action?>" method="post" enctype="multipart/form-data">
               <table class="form">
			   <tr>
					<td width="500" colspan="3">
					<p font-size="14px";>
					Mempersiapkan data dengan bentuk excel untuk import ke dalam database SID:
					<br>
					<ol>

					<li value="1">Pastikan format data yang akan diimport sudah sesuai dengan aturan import data:
					<dl>
					<dl>-> Tidak boleh menggunakan tanda ' (petik satu) dalam penggunaan nama, 
					<br><dl>-> Format tanggal untuk terdeteksi dalam format date dalam database menggunakan tambahan ' (petik satu) di dalam excel. 
					<br>Contoh :  '1988-09-15
					<br><dl>-> Struktur RT RW, jika tidak ada dalam struktur wilayah desa diganti dengan tanda � (min/strip/dash)
					<br><dl>-> Data (Jenis Kelamin, Agama, Pendidikan, Pekerjaan, Status Perkawinan, Status Hubungan dalam Keluarga, Kewarganegaraan, Golongan darah, Jamkesmas, raskin, klasifikasi sosial ekonomi) terwakili dengan Kode Nomor. Misal : laki-laki terwakili dengan nomor 1 dan perempuan dengan nomor 2<br>
					</dl>
					</ol>
					</p>
					</td>
<td>
&nbsp;
</td>
				</tr>

<tr>


<td width="250">
<p>Pilih File .sql:
</td>
<td width="250">
<input name="userfile" type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"/>
<td>
<input type="submit" class="uibutton special" value="Restore" /> 
</td>
</p>
</td>
<td>
&nbsp;
</td>
</tr>
</table>
</form> 
            </div>
        </div>

    <div class="ui-layout-south panel bottom">
        <div class="left"> 
		<div class="table-info">
          </div>
        </div>
        <div class="right">
        </div>
    </div>
</div>
</td></tr></table>
</div>
