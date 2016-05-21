<script>
$(function(){
    var nik = {};
    nik.results = [
		<?foreach($penduduk as $data){?>
	   {id:'<?=$data['id']?>',name:"<?=$data['nik']." - ".($data['nama'])?>",info:"<?=($data['alamat'])?>"},
		<?}?>
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
<div id="pageC">
	<table class="inner">
	<tr style="vertical-align:top">
		<td style="background:#fff;padding:0px;"> 
<div id="contentpane">
    <form id="validasi" action="<?=$form_action?>" method="POST" enctype="multipart/form-data">
    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
    <h3>Form Data RW</h3>
        <table class="form">
            <tr>
                <th width="160">Nomor RW</th>
                <td><input name="rw" type="text" class="inputbox required number" size="40" value="<?=$rw?>"/></td>
            </tr>
			<tr>
			<?if($rw){?>
			<tr>
                <th>Ketua RW Sebelumnya</th>
                <td>
                    <?=$individu['nama']?>
					<br />NIK - <?=$individu['nik']?>
                </td>
            </tr>
			<?}?>
                <th>NIK / Nama Ketua RW</th>
                <td>
                    <div id="id_kepala" name="id_kepala"></div>
                </td>
            </tr>
        </table>
    </div>
   
    <div class="ui-layout-south panel bottom">
        <div class="left">     
            <a href="<?=site_url("sid_core/sub_rw/$id_dusun")?>" class="uibutton icon prev">Kembali</a>
        </div>
        <div class="right">
            <div class="uibutton-group">
                <button class="uibutton" type="reset">Clear</button>
                <button class="uibutton confirm" type="submit" >Simpan</button>
            </div>
        </div>
    </div> </form>
</div>
</td></tr></table>
</div>
