<?php
/*
 * create.php
 * 
 * Backend View untuk Nulis Program Bantuan Baru
 * 
 * Copyright 2015 Isnu Suntoro <isnusun@gmail.com>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */

?>
<div id="pageC">
<table class="inner">
	<tr style="vertical-align:top">
		<td class="side-menu">
		<?php
		$this->load->view('program_bantuan/menu_kiri.php')
		?>
		</td>
		<td class="contentpane">
			<legend>Form Penulisan Program Bantuan</legend>
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
				<?php echo form_open('program_bantuan/create')."\n"; ?>
					<div class="form-group">
						<label>Sasaran Program</label>
						<select class="form-control" name="cid" id="cid">
							<option value="">Pilih Sasaran Program <?php echo $cid;?></option>
							<?php
							$strC = ($cid == 1)?"selected=\"selected\"":"";
							echo "<option value=\"1\" ".$strC.">Penduduk Perorangan</option>";
							$strC = ($cid == 2)?"selected=\"selected\"":"";
							echo "<option value=\"2\" ".$strC.">Keluarga - KK</option>";
							$strC = ($cid == 3)?"selected=\"selected\"":"";
							echo "<option value=\"3\" ".$strC.">Rumah Tangga</option>";
							$strC = ($cid == 4)?"selected=\"selected\"":"";
							echo "<option value=\"4\" ".$strC.">Kelompok / Organisasi</option>";
							?>
						</select>
					</div>
					<div class="form-group">
						<label>Nama Program</label>
						<input type="text" class="form-control" name="nama" id="nama" placeholder="Tuliskan nama program"/>
					</div>
					<div class="form-group">
						<label>Keterangan</label>
						<textarea class="form-control" name="ndesc" id="ndesc"></textarea>
					</div>
					<div class="form-group">
						<label>Rentang Waktu Program</label>
						Mulai <input type="text" class="inputbox required" style="width:200px" name="sdate" id="sdate" placeholder="" value=""/>
						s.d <input type="text" class="inputbox required" style="width:200px" name="edate" id="edate" placeholder="" value=""/>
					</div>
					
					<div class="form-group">
						<div class="uibutton-group">
						<button class="uibutton confirm" type="submit" name="tombol" id="tombol"><span class="fa fa-save"></span> Simpan</button>
						<button class="uibutton" type="reset" name="tombolreset" id="tombolreset"><span class="fa fa-times"></span> Batal</button>
						</div>
					</div>
				</form>
				<div style="height:5em;"></div>
				</div>
			</div>
		</td>
		<td style="width:250px;" class="contentpane">
		<?php
		$this->load->view('program_bantuan/panduan.php')
		?>
		</td>
	</tr>
</table>
<script>
$(document).ready(function () {
    var daysToAdd = 4;
    $("#sdate").datepicker({
        onSelect: function (selected) {
            var dtMax = new Date(selected);
            dtMax.setDate(dtMax.getDate() + daysToAdd); 
            var dd = dtMax.getDate();
            var mm = dtMax.getMonth() + 1;
            var y = dtMax.getFullYear();
            var dtFormatted = mm + '/'+ dd + '/'+ y;
            $("#edate").datepicker("option", "minDate", dtFormatted);
        }
    });
    
    $("#edate").datepicker({
        onSelect: function (selected) {
            var dtMax = new Date(selected);
            dtMax.setDate(dtMax.getDate() - daysToAdd); 
            var dd = dtMax.getDate();
            var mm = dtMax.getMonth() + 1;
            var y = dtMax.getFullYear();
            var dtFormatted = mm + '/'+ dd + '/'+ y;
            $("#sdate").datepicker("option", "maxDate", dtFormatted)
        }
    });
});</script>

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
