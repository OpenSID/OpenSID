<!-- Start of Space Admin -->
<div class="content-header">
</div>
<div id="contentpane" style="padding:10px;"> 
 <div class="ui-layout-north panel">
 <h3>Backup / Restore Database SID</h3>
 </div>
<div class="ui-layout-center" id="maincontent">
<hr>
 <p>Backup seluruh database SID (.sql) yang bisa diimport melalui perangkat PhpMyAdmin</p>
			<a class="uibutton special" href="<?php echo site_url("database")?>/exec_backup" target="confirm" message="Sistem akan melakukan proses backup database SID setelah Anda menekan tombol YA. Simpan file (.sql) tersebut di tempat yang aman." header="Backup Database SID">Backup Database</a>
			<hr>
			<form action="http://localhost:8088/phpmyadmin/index.php" method="post" target="_blank">
				<p>
				Proses restore database SID disarankan menggunakan perangkat PhpMyAdmin untuk meminimalkan kesalahan. Silakan klik tombol berikut untuk masuk ke halaman PhpMyadmin.
				</p>
					<input type="hidden" name="pma_username" value="root" />
					<input type="hidden" name="pma_password" value="combine" />
					<input type="submit" class="uibutton special" value="Restore Database: PhpMyAdmin" /> 
			</form> 
</div>
</div>