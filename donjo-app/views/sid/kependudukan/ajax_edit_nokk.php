<script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/validasi.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/donjoscript/donjoscript2.js"></script>
<form action="<?php echo $form_action?>" method="post" id="validasi">
<table style="width:100%">
	<tr>
		<th align="left">Nomor KK</th>
		<td>
			<input type="text" name="no_kk" value="<?php echo $kk['no_kk']?>" class="inputbox required">
		</td>
	</tr>
	<tr>
		<th align="left">Tanggal Cetak Kartu Keluarga</th>
		<td>
			<input name="tgl_cetak_kk" type="text" value="<?php echo $kk['tgl_cetak_kk']?>" class="inputbox  datepicker" size="20">
		</td>
	</tr>
<?php /*
<tr>
	<th align="left">Kelas Sosial</th>
	<td>
	<div class="uiradio">
	<input type="radio" id="ks1" name="kelas_sosial" value="1"/<?php if($kk['kelas_sosial'] == '1'){echo 'checked';}?>>
	<label for="ks1">Sangat Miskin</label>
	<input type="radio" id="ks2" name="kelas_sosial" value="2"/<?php if($kk['kelas_sosial'] == '2' OR $kk['kelas_sosial'] == '0'){echo 'checked';}?>>
	<label for="ks2">Miskin</label>
	<input type="radio" id="ks3" name="kelas_sosial" value="3"/<?php if($kk['kelas_sosial'] == '3'){echo 'checked';}?>>
	<label for="ks3">Hampir Miskin</label>
	<input type="radio" id="ks4" name="kelas_sosial" value="4"/<?php if($kk['kelas_sosial'] == '4'){echo 'checked';}?>>
	<label for="ks4">Tidak Miskin</label>
	</div>
	</td>
</tr>
*/?>
<tr>
	<th>&nbsp;</th>
</tr>
<tr>
	<th colspan=2 align="left">Peserta Program Bantuan Keluarga</th>
</tr>
<?php foreach($program as $bantuan): ?>
	<tr>
		<td colspan=2>
			<input type="checkbox" name="id_program[]" value="<?php echo $bantuan['id']?>"/<?php if($bantuan['peserta'] != ''){echo 'checked';}?>>
			<a href="<?php echo site_url('program_bantuan/detail/1/'.$bantuan['id'])?>"><?php echo $bantuan['nama']?></a>
		</td>
	</tr>
<?php endforeach; ?>

</tbody>
        </table>
<div class="buttonpane" style="position:absolute;bottom:0px;right:0px;">
    <div class="uibutton-group">
        <button class="uibutton" type="button" onclick="$('#window').dialog('close');"><span class="fa fa-times"></span>Tutup</button>
        <button class="uibutton confirm" type="submit"><span class="fa fa-save"></span> Simpan</button>
    </div>
</div>
</form>
