<div id="pageC"> 
<!-- Start of Space Admin -->
			<div class="content-header">
			</div>
			<div id="contentpane">
				<div class="ui-layout-north panel">
					<h3>Import Data SIAK</h3>
				</div>
				<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
					<div class="left">
							<!--impor data siak-->
							<div>
								<h4>Unggah Berkas</h4>
								<div>
									<?php
									if(strlen(@$_SESSION["SIAK"])>1){
										echo $_SESSION["SIAK"];
									}
									$_SESSION["SIAK"] = "";
									
									$max_upload = (int)(ini_get('upload_max_filesize'));
									$max_post = (int)(ini_get('post_max_size'));
									$memory_limit = (int)(ini_get('memory_limit'));
									$upload_mb = min($max_upload, $max_post, $memory_limit)/10;
									echo "<p>Batas Maksimal Pengunggahan Berkas <strong>".$upload_mb." MB</strong></p>
									<p>Proses ini akan membutuhkan waktu beberapa menit, menyesuaikan dengan spesifikasi
									komputer server SID dan sambungan internet yang tersedia.</p>";
									
									?>
								</div>
								<div>
									<form id="mainform" action="<?php echo $form_action; ?>" method="post" enctype="multipart/form-data">
									<table>
										<tr><td>Berkas DK :</td>
											<td><input type="file" name="file_dk" id="file_dk"/></td></tr>
										<tr><td>Berkas BW :</td>
											<td><input type="file" name="file_bw" id="file_bw"/></td></tr>
										<tr><td>&nbsp;</td>
											<td>
										<a onclick="formAction('mainform','<?php echo $form_action ?>')" class="uibutton special" target="confirm2" message="Proses Import Sedang Berlangsung!!!" header="Harap Tunggu"> Import </a>
										</td></tr>
									</table>
									</form>
								</div>
							</div>
							<!--impor data siak-->
				</div>
			</div>
				<div class="ui-layout-south panel bottom">
		</div>
</div>
</div>