<div id="pageC">
	<table class="inner">
	<tr style="vertical-align:top">
	<td class="side-menu">
      <?php $this->load->view('sosmed/_side-menu.php',array('media' => 'instagram')); ?>
	</td>
		<td>
<div class="content-header">
 <h3>Pengaturan Google</h3>
</div>
<div id="contentpane">
 <form id="validasi" action="<?php echo $form_action?>" method="POST" enctype="multipart/form-data">
 <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
 <table class="form">
		<tr>
			<td width="150">Link Akun Instagram</td><td><textarea name="link" class=" required" style="resize: none; height:100px; width:250px;" size="300" maxlength='160'><?php if($main){echo $main['link'];} ?></textarea></td>
		</tr>
		<tr>
			<th>Aktif</th>
			<td>
				<div class="uiradio">
					<?php $ch='checked';?>
					<input type="radio" id="g1" name="enabled" value="1"/<?php if($main['enabled'] == '1'){echo $ch;}?>><label for="g1">Ya</label>
					<input type="radio" id="g2" name="enabled" value="2"/<?php if($main['enabled'] == '2'){echo $ch;}?>><label for="g2">Tidak</label>
				</div>
			</td>
		</tr>
 </table>
 </div>

 <div class="ui-layout-south panel bottom">

	<div class="right">
	  <div class="uibutton-group">
	      <button class="uibutton" type="reset"><span class="fa fa-refresh"></span> Bersihkan</button>
	      <button class="uibutton confirm" type="submit" ><span class="fa fa-save"></span> Simpan</button>
	  </div>
 </div>
 </div> </form>
</div>
</td></tr></table>
</div>