
<!-- Start of Space Admin -->
<div class="content-header">
</div>
<div id="contentpane" style="padding:10px;"> 
 <div class="ui-layout-north panel">
 <h3>Full Backup / Restore Data SID</h3>
 </div>
<div class="ui-layout-center" id="maincontent">
<hr>
 <p>Backup Seluruh Data SID. File backup berekstensi .sql yang bisa di import melalui Tolls PhpMyAdmin</p>
			<a class="uibutton special" href="<?php echo site_url("database")?>/exec_backup" target="confirm" message="Sistem akan melakukan proses bakup database. Setelah Anda menekan tombol Oke maka web browser okan mengunduh file ber ekstensi .sql. Simpan file tersebut di tempat yang aman. Klik Ok untuk melanjutkan." header="Backup Database SID">Bakcup</a>
			<hr>
			<form action="http://localhost:8088/phpmyadmin/index.php" method="post" target="_blank">
				<p>
				Untuk melakukan proses restore disarankan untuk menggunakan tools PhpMyAdmin, untuk meminimalkan kesalahan, silahkan klik tonbol berikut untuk masuk ke halaman PhpMyadmin
				</p>
					<input type="hidden" name="pma_username" value="root" />
					<input type="hidden" name="pma_password" value="combine" />
					<input type="submit" class="uibutton special" value="PhpMyAdmin" /> 
			</form> 
</div>
</div>