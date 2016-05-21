<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">
<td style="background:#fff;padding:0px;"> 

<div class="content-header">
</div>
<div id="contentpane">
<div class="ui-layout-north panel"><h3>Form Pendataan - <a href="<?=site_url()?>analisis_master/menu/<?=$_SESSION['analisis_master']?>"><?=$analisis_master['nama']?></a></h3>
<h4> &nbsp; Keluarga - (<?=$subjek['no_kk']?>) <?=$subjek['nama']?></h4></br>
<h4> &nbsp; Daftar pertanyaan dan jawaban.</h4>
</div>
    <form id="validasi" action="<?=$form_action?>" method="POST">
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<input type="hidden" name="rt" value="">
<table class="list">
	<?foreach($list_jawab AS $data){?>
	<tr><td><?=$data['no']?>) <?=$data['pertanyaan']?></td><td><?=$data['bobot']?></div>
	<td><?=$data['parameter_laporan']?></td></td></div>
	
	<?}?>
</table>
    </div>
   
    <div class="ui-layout-south panel bottom">
        <div class="left">     
            <a href="<?=site_url()?>analisis_laporan_keluarga" class="uibutton icon prev">Kembali</a>
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