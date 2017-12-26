<div id="pageC"> 
<?php $this->load->view('analisis_master/left',$data);?>
<div id="contentpane">
<div class="ui-layout-north panel">
<h3><a href="<?php echo site_url()?>analisis_master/menu/<?php echo $_SESSION['analisis_master']?>"><?php echo $analisis_master['nama']?></a></h3>
</div>
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
	<div style="max-width:640px;text-align:justify;border:1px solid #cecece;padding:10px 10px 10px 4px;background:#efffef;">
	<?php echo $analisis_master['deskripsi']?><br /><br /><br />
	</div>
</div>
<div class="ui-layout-south panel bottom">
<div class="left"> 
<a href="<?php echo site_url()?>analisis_master" class="uibutton icon prev">Kembali</a>
</div>
<div class="right">
<div class="uibutton-group">
</div>
</div>
</div> 
</td>
</tr>
</table>
</div>