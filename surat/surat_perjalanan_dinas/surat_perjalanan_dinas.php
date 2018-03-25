<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');?>
<script>
	function updateName(elm){
		var index = 1;
		$('input[name^=pengikut]:checked').each(function(){
			$(this).attr("name","pengikut"+index);
			index++;
		})
	}
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

<td style="background:#fff;padding:5px;">
<div class="content-header">

</div>
<div id="contentpane">
<div class="ui-layout-north panel">
<h3>Surat Perjalanan Dinas</h3>
</div>
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
<table class="form">

<form id="validasi" action="<?php echo $form_action?>" method="POST" target="_blank">

<tr>
<th>Nomor Surat</th>
<td>
<input name="nomor" type="text" class="inputbox required" size="12"/> <span>Terakhir: <?php echo $surat_terakhir['no_surat'];?> (tgl: <?php echo $surat_terakhir['tanggal']?>)</span>
</td>
</tr>
<tr>
	<th>Yang memberi tugas</th>
	<td>
		<select name="pamong" class="inputbox required">
			<option value="">Pilih Staf Pemerintah <?php echo ucwords($this->setting->sebutan_desa)?></option>			
			<?php /* tampilkan hanya 2 saja kades dan sekdes  */ 
				$countPejabat = 0;
			?>
			<?php foreach($pamong AS $data){ if ($countPejabat > 1) break; ?>							
				<?php $tmp_nip = trim($data['pamong_nip'],'-'); ?>
				<option value="<?php echo $data['jabatan']?>">
					<?php echo $data['pamong_nama']?> (<?php echo $data['jabatan']?>) <?php if (!empty($tmp_nip)) echo "NIP: ".$data['pamong_nip']?>
				</option>
			<?php $countPejabat++; }?>
		</select>		
	</td>
</tr>
<tr>
	<th>Yang ditugaskan</th>
	<td>
		<select name="pamong_ditugaskan" class="inputbox required" onchange="var nip=$(this).find(':selected').data('nip'); $('#pamong_nip').val(nip);$(this).closest('table').find('select[name=jabatan]').val($(this).find(':selected').data('jabatan')) ">
			<option value="">Pilih Staf Pemerintah <?php echo ucwords($this->setting->sebutan_desa)?></option>						
			<?php foreach($pamong AS $data){ ?>							
				<?php $tmp_nip = trim($data['pamong_nip'],'-'); ?>
				<option value="<?php echo $data['pamong_nama']?>" data-jabatan="<?php echo trim($data['jabatan']) ?>" data-nip="<?php echo $data['pamong_nip']?>">
					<?php echo $data['pamong_nama']?> (<?php echo $data['jabatan']?>) <?php if (!empty($tmp_nip)) echo "NIP: ".$data['pamong_nip']?>
				</option>
			<?php }?>
		</select>		
	</td>
</tr>
<tr>
	<th>Sebagai</th>
	<td>
		<select name="jabatan"  class="inputbox required">
			<option value="">Pilih Jabatan</option>
			<?php foreach($pamong AS $data){?>
				<option <?php if($data['pamong_ttd']==1) echo "selected"?>><?php echo trim($data['jabatan']) ?></option>
			<?php }?>
		</select>
	</td>
</tr>
<tr>
	<th>NIP</th>
	<td>
		<input type="text" name="pamong_nip" id="pamong_nip" />
	</td>
</tr>

<tr>
	<th>Tujuan Perjalanan</th>
	<td>
		<input type="text" name="tujuan_perjalanan" size="50" class="required" />
	</td>
</tr>

<tr>
	<th>Dasar Telek</th>
	<td>
		<input type="text" name="dasar_telek" size="50" class="required" />
	</td>
</tr>

<tr>
	<th>Biaya Perjalanan</th>
	<td>
		<input type="text" name="biaya_perjalanan" size="50" class="required" />
	</td>
</tr>
<tr>
	<th>Biaya Terbilang</th>
	<td>
		<input type="text" name="biaya_terbilang" size="50" class="required" />
	</td>
</tr>

<tr>
	<th>Alat Angkut</th>
	<td>
		<input type="text" name="alat_angkut" size="50" class="required" />
	</td>
</tr>

<tr>
	<th>Beban Anggaran</th>
	<td>
		<input type="text" name="beban_anggaran" size="50" class="required" />
	</td>
</tr>

<tr>
	<th>Tempat Berangkat</th>
	<td>
		<input type="text" name="tempat_berangkat" size="50" class="required" />
	</td>
</tr>

<tr>
	<th>Tempat Tujuan</th>
	<td>
		<input type="text" name="tempat_tujuan" size="50" class="required" />
	</td>
</tr>

<tr>
	<th>Keterangan Perjalanan</th>
</tr>
<tr>
	<th>Lama Perjalanan</th>
	<td>
		<input type="text" name="lama_perjalanan" class="required" /> Hari
	</td>
</tr>
<tr>
	<th>Tanggal Berangkat</th>
	<td>
		<input type="text" name="berlaku_dari" class="required datepicker" />
	</td>
</tr>
<tr>
	<th>Waktu Kembali</th>
	<td>
		<input type="text" name="berlaku_sampai" class="required datepicker" />
	</td>
</tr>
<tr>
	<th>Pengikut</th>
	<td>
		<?php foreach($pamong AS $data){ ?>							
			<?php $tmp_nip = trim($data['pamong_nip'],'-'); ?>
			<div>
				<input type="checkbox" name="pengikut" value="<?php echo $data['pamong_nama'].' / '.$data['jabatan']?>" onclick="updateName(this)" />
				<?php echo $data['pamong_nama']?> (<?php echo $data['jabatan']?>)
			</div>
		<?php }?>
	</td>
</tr>
<tr>
	<th>Keterangan lain</th>
	<td>
		<input type="text" name="keterangan_lain" size="50" class="" />
	</td>
</tr>

</table>
</div>

<div class="ui-layout-south panel bottom">
<div class="left">
<a href="<?php echo site_url()?>surat" class="uibutton icon prev">Kembali</a>
</div>
<div class="right">
<div class="uibutton-group">
<button class="uibutton" type="reset"><span class="fa fa-refresh"></span> Bersihkan</button>

							<?php if (SuratExport($url)) { ?><button type="button" onclick="$('#'+'validasi').attr('action','<?php echo $form_action2?>');$('#'+'validasi').submit();" class="uibutton confirm"><span class="fa fa-file-text">&nbsp;</span>Export Doc</button><?php } ?>
</div>
</div>
</div> </form>
</div>
</td></tr></table>
</div>
