<div id="pageC"> 
<table class="inner">
<tr style="vertical-align:top">
<td style="background:#fff;padding:5px;"> 
<div id="contentpane">
<div class="ui-layout-north panel"><h3>Panduan Pembuatan Surat Administrasi Kependudukan</h3>
</div>
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<?php $this->load->view('surat/signature.php');?>
<?foreach($menu_surat2 AS $data){?>
	<a class="csurat" href="<?=site_url()?>surat/<?=$data['url_surat']?>">
		<img src="<?=base_url()?>assets/images/cpanel/applications-office-5.png" alt="sss"/>
		<span><?=$data['nama']?></span>
	</a>
<?}?>
</div>
</td>
</tr>
</table>
</div>
