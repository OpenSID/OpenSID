<script>
$(function(){
    var nik = {};
    nik.results = [
		<?php foreach($penduduk as $data){?>
	   {id:'<?php echo $data['id']?>',name:"<?php echo $data['nik']." - ".($data['nama'])?>",info:"<?php echo ($data['alamat'])?>"},
		<?php }?>
		    ];
nik.total = nik.results.length;

$('#id_kepala').flexbox(nik, {
	resultTemplate: '<div><label>No nik : </label>{name}</div><div>{info}</div>',
	watermark: 'Ketik no nik di sini..',
    width: 260,
    noResultsText :'Tidak ada no nik yang sesuai..',
	    onSelect: function() {
		$('#'+'main').submit();
    }  
});
$("#nik_detail").show();
});
</script>
<style>
table.form.detail th{
    padding:5px;
    background:#fafafa;
    border-right:1px solid #eee;
}
table.form.detail td{
    padding:5px;
}
</style>
<div id="pageC">
	<table class="inner">
	<tr style="vertical-align:top">
	<td class="side-menu">
		<fieldset>
			<div class="lmenu">
				<ul>
				<li class="selected"><a href="<?php echo site_url('sms/setting')?>">Pengaturan Balas Otomatis</a></li>
				</ul>
			</div>
		</fieldset>
		
	</td>
		<td style="background:#fff;padding:5px;"> 

<div class="content-header">
    <h3>Pengaturan Balas Otomatis</h3>
</div>
<div id="contentpane">
    <form id="validasi" action="<?php echo $form_action?>" method="POST" enctype="multipart/form-data">
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
        <table class="form">
		<tr>
			<td width="100">Isi Pesan Autoreply</td><td><textarea name="autoreply_text" class=" required" style="resize: none; height:100px; width:250px;" size="300" maxlength='160'><?php  if($main){echo $main['autoreply_text'];} ?></textarea></td>
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
