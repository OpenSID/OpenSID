<?php 
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
