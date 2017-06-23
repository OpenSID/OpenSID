<script>
$(function(){
    var link = {};
    link.results = [
		<?php foreach($link as $data){?>
	   {id:'artikel/<?php echo $data['id']?>',name:'<?php echo $data['judul']?>',info:'Halaman Berisi <?php echo $data['judul']?>'},
		<?php }?>
	   {id:'gallery',name:'Gallery',info:'Halaman Galeri'},
		    ];
link.total = link.results.length;

$('#link').flexbox(link, {
	resultTemplate: '<div><label>No link : </label>{name}</div><div>{info}</div>',
	watermark: 'Pilih Menu Link',
    width: 260,
    noResultsText :'Tidak ada no link yang sesuai..',
	    onSelect: function() {
		$('#'+'main').submit();
    }
});
});
</script>
<form action="<?php echo $form_action?>" method="post" id="validasi">
<table style="width:100%">
<tr>
<th align="left" width="120">Nama Sub Menu</th>
<td>
<input type="text" name="nama" class="inputbox2 required" size="20" value="<?php echo $submenu['nama']?>">
</td>
</tr>
<tr>
	<th>Link</th>
	<td>
		<div id="link" name="link"></div>
	</td>
</tr>
</table>
<div class="buttonpane" style="text-align: right; width:400px;position:absolute;bottom:0px;">
    <div class="uibutton-group">
        <button class="uibutton" type="button" onclick="$('#window').dialog('close');"><span class="fa fa-times"></span> Tutup</button>
        <button class="uibutton confirm" type="submit"><span class="fa fa-save"></span> Simpan</button>
    </div>
</div>
</form>
