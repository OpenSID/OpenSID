<script type="text/javascript" src="<?=base_url()?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/validasi.js"></script>
<script>
$(function(){
    var nik = {};
    nik.results = [
<?foreach($penduduk as $data){?>
	   {id:'<?=$data['id']?>',name:'<?=$data['nik']." - ".spaceunpenetration($data['nama'])?>',info:'<?=spaceunpenetration($data['alamat'])?>'},
<?}?>
    ];
nik.total = nik.results.length;

$('#nik_kepala').flexbox(nik, {
	resultTemplate: '<div><label>No nik : </label>{name}</div><div>{info}</div>',
	watermark: 'Ketik nama / nik di sini..',
    width: 230,
    noResultsText :'Tidak ada nama / nik yang sesuai..',
	    onSelect: function() {
$('#'+'main').submit();
    }  
});
});
</script>
<form action="<?=$form_action?>" method="post" id="validasi">
<table>
<tr>
<th align="left">NIK Kepala KK</th>
	<td>
		<div id="nik_kepala" name="nik_kepala"></div class="required">
	</td>
</tr>

<tr>
<th align="left">Nomor KK</th>
	<td>
		<input type="text" name="no_kk" class="inputbox required">
	</td>
</tr>
</table>

<div class="buttonpane" style="text-align: right; width:400px;position:absolute;bottom:0px;">
    <div class="uibutton-group">
        <button class="uibutton" type="button" onclick="$('#window').dialog('close');">Tutup</button>
        <button class="uibutton confirm" type="submit">Simpan</button>
    </div>
</div>
</form>
