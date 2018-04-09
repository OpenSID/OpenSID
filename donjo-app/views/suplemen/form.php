<div id="pageC">
<table class="inner">
	<tr style="vertical-align:top">
		<td class="side-menu">
		<?php
		$this->load->view('suplemen/menu_kiri.php')
		?>
		</td>
		<td class="contentpane">
			<legend>Form Penulisan Data Suplemen</legend>
			<div id="contentpane">
				<div class="ui-layout-center" id="maincontent" style="width:96%">
				<?php
				if(validation_errors()){
					echo "
					<div class=\"error\" style=\"border:solid 2px #c00;color:#c00;margin:1em 0;\">
						<div style=\"background:#c00;color:#fff;padding:1em;font-weight:bolder;\">
						Ada Kesalahan
						</div>
						<div style=\"padding:1em 2em;\">
					".validation_errors()."
						</div>
					</div>
					";
				}
				$cid = @$_REQUEST["cid"];

				 ?>
				<?php echo form_open($form_action)."\n"; ?>
					<div class="form-group">
						<label>Sasaran Data</label>
						<select class="form-control" name="cid" id="cid">
							<option value="">Pilih Sasaran Data <?php echo $cid;?></option>
							<?php
							$strC = ($cid == 1 OR $suplemen['sasaran'] == 1)?"selected=\"selected\"":"";
							echo "<option value=\"1\" ".$strC.">Penduduk Perorangan</option>";
							$strC = ($cid == 2 OR $suplemen['sasaran'] == 2)?"selected=\"selected\"":"";
							echo "<option value=\"2\" ".$strC.">Keluarga - KK</option>";
							?>
						</select>
					</div>
					<div class="form-group">
						<label>Nama Data</label>
						<input type="text" class="form-control" name="nama" id="nama" placeholder="Tuliskan nama data" value="<?php echo $suplemen['nama']?>"/>
					</div>
					<div class="form-group">
						<label>Keterangan</label>
						<textarea class="form-control" name="keterangan" id="keterangan"><?php echo $suplemen['keterangan']?></textarea>
					</div>

					<div class="form-group">
						<div class="uibutton-group">
							<button class="uibutton" type="reset" name="tombolreset" id="tombolreset"><span class="fa fa-times"></span> Batal</button>
							<button class="uibutton confirm" type="submit" name="tombol" id="tombol"><span class="fa fa-save"></span> Simpan</button>
						</div>
					</div>
				</form>
				<div style="height:5em;"></div>
				</div>
			</div>
		</td>
		<td style="width:250px;" class="contentpane">
		<?php
		$this->load->view('suplemen/panduan.php')
		?>
		</td>
	</tr>
</table>

<script type="text/javascript" src="<?php echo base_url()?>assets/tiny_mce/tiny_mce_src.js"></script>
<script type="text/javascript">
tinyMCE.init({
        // General options
		mode : "textareas",
		theme : "advanced",
		relative_urls: false,
		language : "en",
		skin : "o2k7",
        plugins : "jbimages,lists,pagebreak,table,advlink,preview,paste,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras",

        // Theme options
        theme_advanced_buttons1 : "pastetext,pasteword,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,outdent,indent,blockquote,|,link,unlink,jbimages,cleanup,help,code,|,preview,|,forecolor,backcolor|,fullscreen",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,

        // Skin options
        skin : "o2k7",
        skin_variant : "blue"
});
</script>
</div>
