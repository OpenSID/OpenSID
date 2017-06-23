<div id="pageC">
	<div class="content-header"> </div>
	<div id="contentpane">
		<div class="ui-layout-north panel">
			<h3>Form Manajemen Modul</h3>
		</div>
		<form id="validasi" action="<?php echo $form_action?>" method="POST" enctype="multipart/form-data">
			<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
				<table class="form">
					<tr>
						<th>Nama Modul</th>
						<td><input name="modul" type="text" class="inputbox required" size="40" value="<?php echo ($modul['modul'])?>"/></td>
					</tr>
					<tr>
						<th>URL</th>
						<td><input name="url" type="text" class="inputbox" size="20" value="<?php echo $modul['url']?>"/></td>
					</tr>
					<tr>
						<th>Ikon</th>
						<td><input name="ikon" type="text" class="inputbox" size="20" value="<?php echo ($modul['ikon'])?>"/></td>
					</tr> 
					<tr>
							<th width="100">Status</th>
						<td>
							<div class="uiradio">
							<input type="radio" id="g1" name="aktif" value="1" <?php if($modul['aktif'] == '1' OR $modul['aktif'] == ''){echo 'checked';}?>><label for="g1">Aktif</label>
							<input type="radio" id="g2" name="aktif" value="2" <?php if($modul['aktif'] == '2' ){echo 'checked';}?>><label for="g2">Tidak Aktif</label>
							</div>
						</td>
					</tr> 
				</table>
			</div>
			<div class="ui-layout-south panel bottom">
				<div class="left"> 
					<a href="<?php echo site_url()?>modul" class="uibutton icon prev">Kembali</a>
				</div>
				<div class="right">
					<div class="uibutton-group">
						
						<button class="uibutton confirm" type="submit" ><span class="fa fa-save"></span> Simpan</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>