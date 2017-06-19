<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/validasi.js"></script>
<script>
$(function(){
    var nik = {};
    nik.results = [
<?php foreach($penduduk as $data){?>
	   {id:'<?php echo $data['id']?>',name:"<?php echo $data['nik']." - ".$data['nama']." - ".$data['kk_level'];?>"},
<?php }?>
    ];
nik.total = nik.results.length;

$('#nik_kepala').flexbox(nik, {
	resultTemplate: '<div>{name}</div>',
	watermark: 'Ketik nama / nik di sini..',
    width: 280,
    noResultsText :'Tidak ada nama / nik yang sesuai..',
});
});
</script>
<form action="<?php echo $form_action?>" method="post" id="validasi">
<table class="list">
<tr>
<td align="left">NIK Kepala Rumah Tangga</td>
	<td>
		<div id="nik_kepala" name="nik_kepala" style="float:left;"></div class="required">
	</td>
</tr>
<tr>
<td align="left" colspan="2">&nbsp;</td>
</tr>
<tr>
<td align="left" colspan="2">Keterangan</td>
</tr>
	<td colspan="2">
		Silakan cari nama / NIK dari data penduduk yang sudah terinput.</br>
		Penduduk yang dipilih otomatis berstatus sebagai Kepala Rumah Tangga baru tersebut.
	</td>
</tr>
</table>

<div class="buttonpane" style="text-align: right; width:420px;position:absolute;bottom:0px;">
    <div class="uibutton-group">
        <button class="uibutton" type="button" onclick="$('#window').dialog('close');"><span class="fa fa-times"></span> Tutup</button>
        <button class="uibutton confirm" type="submit"><span class="fa fa-save"></span> Simpan</button>
    </div>
</div>
</form>

<tr>
<?php /*<th align="left">Nomor RTM</th>
	<td>
		<input type="text" name="no_kk" class="inputbox required">
	</td>
</tr>
*/?>
