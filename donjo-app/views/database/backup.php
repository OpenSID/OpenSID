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
					Proses <em>Download</em> akan mengunduh keseluruhan database SID anda.
          </p>
          <br>
          <ul>
          <li>Usahakan untuk melakukan backup secara rutin dan terjadwal.
          <li>Backup yang dihasilkan sebaiknya disimpan di komputer terpisah dari server SID.</li>
          </ul>
					<br>
          <p>Backup yang dibuat dapat dipergunakan untuk me-restore database SID anda apabila ada masalah. Klik tombol <em>Restore</em> di bawah untuk menggantikan keseluruhan database SID dengan data hasil backup terdahulu.
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
<input name="userfile" type="file" accept="application/sql"/>
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
