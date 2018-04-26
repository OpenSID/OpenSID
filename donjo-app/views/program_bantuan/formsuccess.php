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
		<td class="contentpane"><?php
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
		
		 ?>
		</td>
		<td style="width:250px;" class="contentpane">
		<?php
		$this->load->view('program_bantuan/panduan.php')
		?>
		</td>
	</tr>
</table>
</div>
