<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/validasi.js"></script>
<script>
$(function(){$('#'+'tes').hide();
    var nik = {};
    nik.results = [
<?php foreach($penduduk as $data){?>
	   {id:'<?php echo $data['id']?>',name:"<?php echo $data['nik'].' - '.$data['nama'];?>",info:""},
<?php }?>
    ];
nik.total = nik.results.length;

$('#nik').flexbox(nik, {
	resultTemplate: '<div><label>NIK/ Nama : </label>{name}</div>',
	watermark: 'Ketik nama / nik di sini..',
    width: 260,
    noResultsText :'Tidak ada nama / nik yang sesuai..',
	onSelect: function() {
		//$('#'+'validasi').submit();
		$('#'+'tes').show();
    }  
});
});
</script>
<form action="<?php echo $form_action?>" method="post" id="validasi">
<table class="data">
<tr>
<th align="left">NIK / Nama Penduduk</th>
	<td>
		<div id="nik" name="nik" class="required"></div>
	</td>
</tr>

<tr>
	<td colspan="2">
		Jika PIN tidak di isi makan sistem akan menghasilkan PIN secara acak.
	</td>
</tr>
<tr>
<th align="left">PIN</th>
	<td>
		<input type="text" name="pin" id="pin" class="inputbox number"> <label> 6 (enam) digit Angka</label>
	</td>
</tr>
</table>

<div class="buttonpane" style="text-align: right; width:400px;position:absolute;bottom:0px;">
    <div class="uibutton-group">
        <button class="uibutton" type="button" onclick="$('#window').dialog('close');"><span class="fa fa-times"></span> Tutup</button>
        <button class="uibutton confirm" type="submit" id="tes" ><span class="fa fa-save"></span> Simpan</button>
    </div>
</div>
</form>
