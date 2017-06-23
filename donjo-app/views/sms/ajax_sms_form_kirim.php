<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/validasi.js"></script>
<script>
$(function(){
    var nik = {};
    nik.results = [
<?php foreach($kontak as $data){?>
	   {id:'<?php echo $data['no_hp']?>',name:'<?php echo $data['nik']." - ".spaceunpenetration($data['nama'])." - ".$data['no_hp'] ?>',info:'<?php echo $data['alamat']?>'},
<?php }?>
    ];
nik.total = nik.results.length;

$('#DestinationNumber').flexbox(nik, {
	resultTemplate: '<div><label>No nik : </label>{name}</div><div>{info}</div>',
	watermark: 'Ketik nomor ponsel di sini..',
    width: 230,
    noResultsText :'Tidak ada nomor ponsel yang sesuai..',
	    onSelect: function() {
$('#'+'main').submit();
    }  
});
});
</script>
<form action="<?php echo $form_action?>" method="post" id="validasi">
<table>
<tr>
<th align="left">No HP Tujuan</th>
	<td>
		<div id="DestinationNumber" name="DestinationNumber"></div class="required">
	</td>
</tr>

<tr>
<th align="left">Pesan</th>
	<td>
		<textarea name="TextDecoded" class=" required" style="resize: none; height:200px; width:280px;" size="1000" maxlength='160'></textarea>
	</td>
</tr>
</table>

<div class="buttonpane" style="text-align: right; width:400px;position:absolute;bottom:0px;">
    <div class="uibutton-group">
        <button class="uibutton" type="button" onclick="$('#window').dialog('close');"><span class="fa fa-times"></span> Tutup</button>
        <button class="uibutton confirm" type="submit">Kirim</button>
    </div>
</div>
</form>
