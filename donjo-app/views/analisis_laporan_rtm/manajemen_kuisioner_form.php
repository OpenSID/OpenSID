<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;"> 

<div class="content-header">
</div>
<div id="contentpane">
<div class="ui-layout-north panel"><h3>Form Pendataan - <a href="<?php echo site_url()?>analisis_master/menu/<?php echo $_SESSION['analisis_master']?>"><?php echo $analisis_master['nama']?></a></h3>
<h4> &nbsp;  Rumah Tangga - (<?php echo $subjek['no_kk']?>) <?php echo $subjek['nama']?></h4></br>
<h4> &nbsp; Daftar pertanyaan dan jawaban.</h4>
</div>
    <form id="validasi" action="<?php echo $form_action?>" method="POST">
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<input type="hidden" name="rt" value="">
<table>
	<?php foreach($list_jawab AS $data){?>
	<tr><td><?php echo $data['no']?>) <?php echo $data['pertanyaan']?></td></tr>
	<tr><td> &nbsp; [v] <?php echo $data['parameter_laporan']?></div>
	
	<?php }?>
</table>
    </div>
   
    <div class="ui-layout-south panel bottom">
        <div class="left">     
            <a href="<?php echo site_url()?>analisis_laporan_rtm" class="uibutton icon prev">Kembali</a>
        </div>
        <div class="right">
            <div class="uibutton-group">
                <button class="uibutton" type="reset">Clear</button>
                <button class="uibutton confirm" type="submit" >Simpan</button>
            </div>
        </div>
    </div>
</form>
</div>