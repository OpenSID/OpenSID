<script>
$(function(){
    var nik = {};
    nik.results = [
<?foreach($penduduk as $data){?>
	   {id:'<?=$data['id']?>',name:'<?=$data['nik']." - ".spaceunpenetration($data['nama'])?>',info:''},
<?}?>
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
<form action="<?=$form_action?>" method="post" id="validasi">
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
<?foreach($hubungan as $data){?>
	<option value="<?=$data['id']?>"><?=$data['hubungan']?></option>
<?}?></select>
</td>
	</tr>
</table>
<div class="content-header">
    <h4>KK No.<?=$kepala_kk['no_kk']?> Keluarga : <?=$kepala_kk['nama']?></h4>
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
        <? foreach($main as $data): ?>
<tr>
          <td align="center" width="2"><?=$data['no']?></td>
          <td><?=$data['nik']?></td>
          <td><?=unpenetration($data['nama'])?></td>
          <td><?=$data['hubungan']?></td>
  </tr>
        <? endforeach; ?>
</tbody>
        </table>
<div class="buttonpane" style="text-align: right; width:400px;position:absolute;bottom:0px;">
    <div class="uibutton-group">
        <button class="uibutton" type="button" onclick="$('#window').dialog('close');">Tutup</button>
        <button class="uibutton confirm" type="submit">Simpan</button>
    </div>
</div>
</form>
