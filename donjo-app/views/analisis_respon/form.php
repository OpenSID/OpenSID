<script>
$(function(){
	var op_item_width = (parseInt($('#op_item').width())/2);
	var label_width = (parseInt($('#op_item').width())/2)-32;
	$('#op_item div').css('clear','both');
	$('#op_item div').css('float','left');
	$('#op_item div').css('width',op_item_width);
	$('#op_item label').css('width',label_width);
	$('#op_item input:checked').parent().css({'background':'#c9cdff','border':'1px solid #7a82eb'});
	$('#op_item input').change(function(){
		if ($(this).is('input:checked')){
			$('#op_item input').parent().css({'background':'#ffffff','border':'1px solid #ddd'});
			$('#op_item input:checked').parent().css({'background':'#c9cdff','border':'1px solid #7a82eb'});
			$(this).parent().css({'background':'#c9cdff','border':'1px solid #7a82eb'});
		} else {
			$(this).parent().css({'background':'#fafafa','border':'1px solid #ddd'});
		}
	});
	$('#op_item label').click(function(){
		$(this).prev().trigger('click');
	})
});
</script>
<style>
	#op_item div{
		margin:1px 0;
		background:#fafafa;
		border:1px solid #ddd;
	}
	#op_item input{
		vertical-align:middle;
		margin:0px 2px;
	}
	#op_item label{
		padding:4px 10px 0px 2px;
		font-size:11px;
		line-height:14px;
		font-weight:normal;
	}
	table.head{
		font-size:14px;
		font-weight:bold;
	}
</style>
<div id="pageC">
<div class="content-header">
</div>
<div id="contentpane">
	<div class="ui-layout-north panel">
	</div>
	<form id="validasi" action="<?php echo $form_action?>" method="POST" enctype="multipart/form-data">
	<div class="ui-layout-center" id="maincontent">
		<table class="head">
			<tr>
				<td width="150">Form Pendataan</td>
				<td> : </td>
				<td><a href="<?php echo site_url()?>analisis_master/menu/<?php echo $_SESSION['analisis_master']?>"><?php echo $analisis_master['nama']?></a></td>
			</tr>
			<tr>
				<td>Nomor Identitas</td>
				<td> : </td>
				<td><?php echo $subjek['nid']?></td>
			</tr>
			<tr>
				<td>Nama Subjek</td>
				<td> : </td>
				<td><?php echo $subjek['nama']?></td>
			</tr>
		</table>
		<?php if($list_anggota){?>
		<h4>DAFTAR ANGGOTA</h4>
		<table class="list data">
			<tr>
				<th width="10">NO</th>
				<?php if($analisis_master['id_child']!=0){?>
				<th width="70">AKSI</th>
				<?php } ?>
				<th width="100">NIK</th>
				<th width="200">NAMA</th>
				<th width="100">TANGGAL LAHIR</th>
				<th width="80">JENIS KELAMIN</th>
				<th>&nbsp;</th>
			</tr>
		<?php $i=1;foreach($list_anggota AS $ang){
			$idc = $ang['id'];
		?>
			<tr>
				<td><?php echo $i?></td>

				<?php if($analisis_master['id_child']!=0){?>
				<td>
					<div class="uibutton-group">
						<a href="<?php echo site_url("analisis_respon/kuisioner_child/$p/$o/$id/$idc")?>" class="uibutton south" target="ajax-modal-respon" rel="window" header="<?php echo $ang['nik']?> <?php echo $ang['nama']?>"><span class="fa fa-list"> Input Data</span></a>
					</div>
				</td>
				<?php } ?>

				<td><?php echo $ang['nik']?></td>
				<td><?php echo $ang['nama']?></td>
				<td><?php echo tgl_indo($ang['tanggallahir'])?></td>
				<td><?php if($ang['sex'] == 1) echo "LAKI-LAKI";?><?php if($ang['sex'] == 2) echo "PEREMPUAN";?></td>
				<td>&nbsp;</td>
			</tr>
		<?php $i++;}?>
		</table>
		<?php } ?>
		<table width="100%">
		<?php $new=1;$last=0; foreach($list_jawab AS $data){$data['no']="";?>
			<tr><td><hr></td></tr>
		<?php

			if($data['id_kategori']!=$last OR $last == 0){
				$new = 1;
			}
		if($new == 1){?>
			<tr><td>&nbsp;</td></tr>
			<tr style="background-color:#acff98;"><td><h3><?php echo $data['kategori']?></h3></td></tr>
			<tr><td>&nbsp;</td></tr>
		<?php
			$new=0;
			$last = $data['id_kategori'];
			}
		?>
			<tr><td><label class='tanya'><?php echo $data['no']?><?php echo $data['pertanyaan']?></label></td></tr>
			<?php if($data['id_tipe']==1){?>

				<tr><td id="op_item">
				<?php foreach($data['parameter_respon'] AS $data2){?>
				<div>
					<input type="radio" name="rb[<?php echo $data['id']?>]" value="<?php echo $data['id']?>.<?php echo $data2['id_parameter']?>" <?php if($data2['cek']){echo " checked";}?>><label><?php echo $data2['kode_jawaban']?>. <?php echo $data2['jawaban']?></label>
				</div>
				<?php }?>

			<?php }elseif($data['id_tipe']==2){?>

				<?php foreach($data['parameter_respon'] AS $data2){?>
				<tr><td id="op_item">
				<div>
					<input type="checkbox" name="cb[<?php echo $data2['id_parameter']?>]" value="<?php echo $data2['id_parameter']?>.<?php echo $data['id']?>" <?php if($data2['cek']){echo " checked";}?>>
					<label><?php echo $data2['kode_jawaban']?>. <?php echo $data2['jawaban']?></label>
				</div>
				<?php }?>

			<?php }elseif($data['id_tipe']==3){?>

				<?php if($data['parameter_respon']){?>
				<?php $data2=$data['parameter_respon'];?>
				<tr><td id="">
				<div style="display:inline-block;"><input name="ia[<?php echo $data['id']?>]" type="text" class="inputbox number" size="10" value="<?php echo $data2['jawaban']?>"/></div>
				<?php }else{?>
				<tr><td id="">
				<div style="display:inline-block;"><input name="ia[<?php echo $data['id']?>]" type="text" class="inputbox number" size="10" value=""/></div>
				<?php }?>

			<?php }elseif($data['id_tipe']==4){?>

				<?php if($data['parameter_respon']){?>
				<?php $data2=$data['parameter_respon'];?>
				<tr><td id="">
				<div style="display:inline-block;"><input name="it[<?php echo $data['id']?>]" type="text" class="inputbox" size="100" value="<?php echo $data2['jawaban']?>"/></div>
				<?php }else{?>
				<tr><td id="">
				<div style="display:inline-block;"><input name="it[<?php echo $data['id']?>]" type="text" class="inputbox" size="100" value=""/></div>
				<?php }?>

			<?php }?>
		<?php

		}?>
		<tr><td><hr></td></tr>
		</table>
		<table>
		<tr>
			<td><label>Upload Berkas Form Pendataan :</label><input name="pengesahan" type="file" />*) Format file harus *.jpg</td>
		</tr>
		<tr>
			<td>*) Berkas form pendtaan digunakan sebagai penguat / bukti pendataan maupun untuk verifikasi data yang sudah terinput.</td>
		</tr>
		<tr>
			<td>*) Berkas Bukti / Pengesahan Harus berupa file gambar dengan format JPG, dengan ukuran maksimal 1Mb (dua megabyte)</td>
		</tr>
		</table>
		<table>
		<tr>
		<?php foreach($list_bukti AS $bukti){?>
			<td>
				<a href="<?php echo base_url().LOKASI_PENGESAHAN.$bukti['pengesahan']?>" target="_blank">
				<img src="<?php echo base_url().LOKASI_PENGESAHAN.$bukti['pengesahan']?>" width ='320'>
				</a>
			</td>
		<?php }?>
		</tr>
		</table>
	</div>
	<div class="ui-layout-south panel bottom" id="bawah">
		<div class="left">
			<a href="<?php echo site_url()?>analisis_respon" class="uibutton icon prev">Kembali</a>
			<?php if(isset($_SESSION['fullscreen'])){?>
			<a href="<?php echo current_url()?>/2" class="uibutton">Normal</a>
			<?php } else { ?>
			<a href="<?php echo current_url()?>/1" class="uibutton special"><span class="fa fa-window-maximize" ></span> Full Screen</a>
			<?php } ?>
		</div>
		<div class="right">
			<div class="uibutton-group">
				<button class="uibutton confirm" type="submit" id="simpan"><span class="fa fa-save"></span> Simpan</button>
			</div>
		</div>
	</div>
</form>

<?php if(@$_SESSION['sukses']==1): ?>
<script>
	$(function(){
		notification('success','Data Berhasil Disimpan')();
	});
</script>
<?php elseif(@$_SESSION['sukses']==-1): ?>
<script>
	$(function(){
		notification('error','Data Gagal Disimpan')();
	});
</script>
<?php endif; ?>

<?php $_SESSION['sukses']=0;?>

</div>