<div id="pageC"> 
<!-- Start of Space Admin -->
	<table class="inner">
	<tr style="vertical-align:top">
		<td style="background:#fff;padding:0px;">
			<div class="content-header">
			</div>
			<div id="contentpane">
				<div class="ui-layout-north panel">
					<h3>Import Data PBDT</h3>
				</div>
				<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
					<div class="left">
							<!--impor data xls-->
 <table class="form"><?php /*
								<tr>
							<form action="<?php echo $form_action2?>" method="post" enctype="multipart/form-data">
									<td width="150">
										Rumah Tangga .xls:
									</td>
									<td width="250">
										<input name="userfile" type="file" />
									<td>
										<input type="submit" class="uibutton special" value="Import" /> 
									</td>
									<td>
										&nbsp;
									</td>
							</form>
								</tr> */ ?>
								<tr>
							<form action="<?php echo $form_action3?>" method="post" enctype="multipart/form-data">
									<td width="150">
										PBDT Individu .xls:
									</td>
									<td width="250">
										<input name="userfile" type="file" />
									<td>
										<input type="submit" class="uibutton special" value="Import" /> 
									</td>
									<td>
										&nbsp;
									</td>
							</form>
								</tr>
							<?php if(isset($_SESSION['gagal'])){?>
								<tr>
									<td width="150">
									<p>Jumlah Data Gagal
									</td>
									<td colspan="3">
									
										<?php echo $_SESSION['gagal']?>
									</td>
								</tr>
								<tr>
									<td width="150">
									<p>Letak Baris Data Gagal:
									</td>
									<td colspan="3">
									
										<?php echo $_SESSION['baris']?>
									</td>
								</tr>
								<tr>
									<td width="150">
									<p>Tota Data Berhasil:
									</td>
									<td colspan="3">
									
										<?php echo $_SESSION['sukses']?>
									</td>
								</tr>
							<?php }?>
							</table>
							<!--impor data xls-->
							
							<!--impor data siak-->
 </div>
				<div class="ui-layout-south panel bottom">
					<div class="left"> 
						<div class="table-info"></div>
 </div>
 <div class="right">
 </div>
			</div>
		</div>
	</td></tr></table>
</div>
<?php unset($_SESSION['sukses']);?>
<?php unset($_SESSION['baris']);?>
<?php unset($_SESSION['gagal']);?>