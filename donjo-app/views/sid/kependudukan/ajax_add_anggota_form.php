<script>
$(function(){
    var nik = {};
    nik.results = [
<?php foreach($penduduk as $data){?>
	   {id:'<?php echo $data['id']?>',name:'<?php echo $data['nik']." - ".html_escape($data['nama'])?>',info:''},
<?php }?>
    ];
nik.total = nik.results.length;

$('#nik').flexbox(nik, {
	resultTemplate: '<div><label>No nik : </label>{name}</div><div>{info}</div>',
	watermark: 'Ketik nama / nik di sini..',
    width: 260,
    noResultsText :'Tidak ada nama / nik yang sesuai..',
	    onSelect: function() {
$('#'+'main').submit();
    }
});
});
</script>
<form action="<?php echo $form_action?>" method="post" id="validasi">
<table style="width:100%">
<tr>
<th align="left">NIK / Nama Penduduk</th>
<td>
<div id="nik" name="nik"></div class="required">
</td>
</tr>
<tr>
<th></th>
<td>
</td>
	</tr>
	<tr>
	<tr>
<th align="left">Hubungan</th>
<td><select name="kk_level" class="required">
<option value=""> --- </option>
<?php foreach($hubungan as $data){?>
	<option value="<?php echo $data['id']?>"><?php echo $data['nama']?></option>
<?php }?></select>
</td>
	</tr>
</table>
<div class="content-header">
    <h4>KK No.<?php echo $kepala_kk['no_kk']?> Keluarga : <?php echo $kepala_kk['nama']?></h4>
</div>
<table class="list"  style="width:95%">
<thead>
            <tr>
                <th>No</th>
<th align="left" width='100'>NIK</th>
<th align="left">Nama</th>
<th align="left" width='100'>Hubungan</th>

	</tr>
</thead>
<tbody>
        <?php  foreach($main as $data): ?>
<tr>
          <td align="center" width="2"><?php echo $data['no']?></td>
          <td><?php echo $data['nik']?></td>
          <td><?php echo $data['nama']?></td>
          <td><?php echo $data['hubungan']?></td>
  </tr>
        <?php  endforeach; ?>
</tbody>
        </table>
<div class="buttonpane" style="text-align: right; width:400px;position:absolute;bottom:0px;">
    <div class="uibutton-group">
        <button class="uibutton" type="button" onclick="$('#window').dialog('close');"><span class="fa fa-times"></span> Tutup</button>
        <button class="uibutton confirm" type="submit"><span class="fa fa-save"></span> Simpan</button>
    </div>
</div>
</form>
