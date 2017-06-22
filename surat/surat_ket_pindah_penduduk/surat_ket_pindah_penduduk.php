<script>
	function pilih_format_surat(kode_format){
		$('#kode_format').val(kode_format);
		if(kode_format == 'f108'){
			$('#status_kk_tidak_pindah_f108_show').show();
			$('#status_kk_tidak_pindah_show').hide();
		} else {
			$('#status_kk_tidak_pindah_f108_show').hide();
			$('#status_kk_tidak_pindah_show').show();
		}
	}
	function get_alasan(alasan){
		if(alasan == 7){
			$('#sebut_alasan').show();
		} else { $('#sebut_alasan').hide(); }
	}
	function enable_anggota(){
		jumlah_anggota = $("#jumlah_anggota").val();
		for (i = 1; i <= jumlah_anggota; i++) {
			anggota = $("#anggota_show"+i);
			if(anggota.length > 0){
				anggota.removeAttr('disabled');
			}
		}
	}
	function anggota_pindah(ya_atau_tidak){
		jumlah_anggota = $("#jumlah_anggota").val();
		for (i = 1; i <= jumlah_anggota; i++) {
			anggota = $("#anggota_show"+i);
			if(anggota.length > 0){
				anggota.attr("checked", ya_atau_tidak);
				anggota.trigger("onchange");
				anggota.attr('disabled', 'disabled');
			}
		}
	}
	function urus_anggota(jenis_pindah){
		if($('#kode_format').val() == "f108"){
			status_kk_tidak_pindah = "#status_kk_tidak_pindah_f108_show";
		} else {
			status_kk_tidak_pindah = "#status_kk_tidak_pindah_show";
		}
		// Hanya anggota yang pindah
		if(jenis_pindah == 4){
			$('#kk_show').attr("checked", false);
			$("#kk").attr('disabled', 'disabled');
			if($('#kode_format').val() == "f108"){
				$(status_kk_tidak_pindah).val("4");
			} else {
				$(status_kk_tidak_pindah).val("3");
			}
			$(status_kk_tidak_pindah).trigger("onchange");
			$(status_kk_tidak_pindah).attr('disabled', 'disabled');
			$("#status_kk_pindah_show").removeAttr('disabled');
			enable_anggota();
		} else {
			$('#kk_show').attr("checked", true);
			$("#kk").removeAttr('disabled');
			if($('#klasifikasi_pindah_id').val() < 3) {
				// Jika pindah di satu kecamatan, nomor KK tetap.
				// Jika pindah ke luar kecamatan, nomor KK ganti.
				$("#status_kk_pindah_show").val("3");
				$("#status_kk_pindah_show").trigger("onchange");
				$("#status_kk_pindah_show").attr('disabled', 'disabled');
			} else {
				$("#status_kk_pindah_show").removeAttr('disabled');
			}
			$(status_kk_tidak_pindah).removeAttr('disabled');
			// KK and semua anggota pindah
			if(jenis_pindah == 2){
				if($('#kode_format').val() == "f108"){
					$(status_kk_tidak_pindah).val("3");
				} else {
					$(status_kk_tidak_pindah).val(" ");
				}
				$(status_kk_tidak_pindah).trigger("onchange");
				$(status_kk_tidak_pindah).attr('disabled', 'disabled');
				anggota_pindah(true);
			}
			// KK dan sebagian anggota pindah
			if(jenis_pindah == 3){
				enable_anggota();
			}
			// Hanya KK yang pindah
			if(jenis_pindah == 1){
				anggota_pindah(false);
			}
		};
		$('#kk_show').trigger("onchange");
	}
	function urus_masa_ktp(centang, urut){
		// ktp_berlaku sekarang selalu 'Seumur Hidup' dan tidak diubah
		if (centang){
			$('#anggota' + urut).attr('disabled', 'disabled');
		}
		else {
			$('#anggota' + urut).removeAttr('disabled');
		}
	}
	function set_wilayah(tingkat_wilayah){
		wilayah = $('#' + tingkat_wilayah);
		wilayah_show = $('#' + tingkat_wilayah + '_show');
		wilayah.val(wilayah.attr('data-awal'));
		wilayah_show.val(wilayah.attr('data-awal'));
		wilayah_show.attr('disabled', 'disabled');
	}
	function urus_klasifikasi_pindah(klasifikasi_pindah){
		if(klasifikasi_pindah >= 1){
			set_wilayah('desa_tujuan');
			set_wilayah('kecamatan_tujuan');
			set_wilayah('kabupaten_tujuan');
			set_wilayah('provinsi_tujuan');
		}
		if(klasifikasi_pindah > 1){
			$('#kode_format').val('F-1.25');
			$('#desa_tujuan_show').removeAttr('disabled');
		} else {
			$('#kode_format').val('F-1.23');
		}
		if(klasifikasi_pindah > 2){
			$('#kode_format').val('F-1.29');
			$('#kecamatan_tujuan_show').removeAttr('disabled');
		}
		if(klasifikasi_pindah > 3){
			$('#kode_format').val('F-1.34');
			$('#kabupaten_tujuan_show').removeAttr('disabled');
		}
		if(klasifikasi_pindah > 4){
			$('#kode_format').val('F-1.34');
			$('#provinsi_tujuan_show').removeAttr('disabled');
		}
		$('#jenis_kepindahan_id').trigger('onchange');
	}

	$(function(){
		var nik = {};
		nik.results = [
			<?php foreach($penduduk as $data){?>
				{id:'<?php echo $data['id']?>',name:"<?php echo $data['nik']." - ".($data['nama'])?>",info:"<?php echo ($data['alamat'])?>"},
			<?php }?>
		];

		$('#nik').flexbox(nik, {
			resultTemplate: '<div><label>No nik : </label>{name}</div><div>{info}</div>',
			watermark: <?php if($individu){?>'<?php echo $individu['nik']?> - <?php echo spaceunpenetration($individu['nama'])?>'<?php }else{?>'Ketik no nik di sini..'<?php }?>,
			width: 260,
			noResultsText :'Tidak ada no nik yang sesuai..',
			onSelect: function() {
				$('#'+'main').submit();
			}
		});
	});
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
						<h3>Surat Keterangan Pindah Penduduk</h3>
					</div>
				<div class="ui-layout-center" id="maincontent" style="padding: 5px;">

<table class="form">
	<tr>
		<th>Gunakan Format</th>
		<td>
	    <select name="pakai_format" class="required" onchange="pilih_format_surat($(this).val());">
	      <option value="">Pilih Format Surat</option>
        <option value="f108">F-1.08</option>
        <option value="bukan_f108" selected>F-1.23, F-1.25, F-1.29, F-1.34 (sesuai tujuan)</option>
	    </select>
		</td>
	</tr>
	<tr>
		<th colspan=2>Pemohon</th>
	</tr>
	<tr>
		<td colspan=2>
			<table>
				<tr>
					<th width="80">NIK / Nama</th>
					<td width="350">
						<form action="" id="main" name="main" method="POST">
							<div id="nik" name="nik"></div>
						</form>
					</td>
				</tr>

				<form id="validasi" action="<?php echo $form_action?>" method="POST" target="_blank">

				<input type="hidden" name="nik" value="<?php echo $individu['id']?>"  class="inputbox required" >
				<input id="kode_format" type="hidden" name="kode_format" value="bukan_f108">

				<?php if($individu){ //bagian info setelah terpilih?>
					<?php include("donjo-app/views/surat/form/konfirmasi_pemohon.php"); ?>
				<?php }?>
				<tr>
					<th>Telepon</th>
					<td>
						<input name="telepon" type="text" class="inputbox required" size="20"/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<th>Nomor Surat</th>
		<td>
			<input name="nomor" type="text" class="inputbox required" size="20"/> <span>Terakhir: <?php echo $surat_terakhir['no_surat'];?> (tgl: <?php echo $surat_terakhir['tanggal']?>)</span>
		</td>
	</tr>
	<tr>
	  <th>Alasan Pindah</th>
	  <td>
	    <select name="alasan_pindah_id" class="required" onchange=get_alasan(this.value)>
	      <option value="">Pilih Alasan Pindah</option>
	      <?php foreach($kode['alasan_pindah'] as $key => $value){?>
	        <option value="<?php echo $key?>"><?php echo strtoupper($value)?></option>
	      <?php }?>
	    </select>
	    <span id="sebut_alasan" style="display:none; margin-left: 10px">
				Sebut alasan: <input name="sebut_alasan" type="text" class="inputbox" size="40"/>
			</span>
	  </td>
	</tr>
	<tr>
	  <th>Klasifikasi Pindah</th>
	  <td>
	    <select id="klasifikasi_pindah_id" name="klasifikasi_pindah_id" class="required" onchange="urus_klasifikasi_pindah($(this).val());">
	      <option value="">Pilih Klasifikasi Pindah</option>
	      <?php foreach($kode['klasifikasi_pindah'] as $key => $value){?>
	        <option value="<?php echo $key?>"><?php echo strtoupper($value)?></option>
	      <?php }?>
	    </select>
	  </td>
	</tr>
	<tr>
		<th>Alamat Tujuan</th>
	</tr>
	<tr>
		<td colspan=2>
			<table>
				<tr>
					<th>Alamat</th>
					<td>
						<input name="alamat_tujuan" type="text" class="inputbox required" size="40"/>
					</td>
				</tr>
				<tr>
					<th>RT</th>
					<td>
						<input name="rt_tujuan" type="text" class="inputbox required" size="10"/>
					<strong style="margin-left: 10px; margin-right: 10px">RW</strong>
						<input name="rw_tujuan" type="text" class="inputbox required" size="10"/>
					<strong style="margin-left: 10px; margin-right: 10px"><?php echo ucwords($this->setting->sebutan_dusun)?></strong>
						<input name="dusun_tujuan" type="text" class="inputbox required" size="20"/>
					</td>
				</tr>
				<tr>
					<th>Desa/Kelurahan</th>
					<td>
						<input id="desa_tujuan" name="desa_tujuan" type="hidden" data-awal="<?php echo $lokasi['nama_desa'];?>"/>
						<input id="desa_tujuan_show" type="text" class="inputbox required" size="40" onchange="$('#desa_tujuan').val($(this).val());"/>
					</td>
					</td>
				</tr>
				<tr>
					<th>Kecamatan</th>
					<td>
						<input id="kecamatan_tujuan" name="kecamatan_tujuan" type="hidden" data-awal="<?php echo $lokasi['nama_kecamatan'];?>"/>
						<input id="kecamatan_tujuan_show" type="text" class="inputbox required" size="40" onchange="$('#kecamatan_tujuan').val($(this).val());"/>
					</td>
				</tr>
				<tr>
					<th>Kabupaten/Kota</th>
					<td>
						<input id="kabupaten_tujuan" name="kabupaten_tujuan" type="hidden" data-awal="<?php echo $lokasi['nama_kabupaten'];?>"/>
						<input id="kabupaten_tujuan_show" type="text" class="inputbox required" size="40" onchange="$('#kabupaten_tujuan').val($(this).val());"/>
					</td>
				</tr>
				<tr>
					<th>Provinsi</th>
					<td>
						<input id="provinsi_tujuan" name="provinsi_tujuan" type="hidden" data-awal="<?php echo $lokasi['nama_propinsi'];?>"/>
						<input id="provinsi_tujuan_show" type="text" class="inputbox required" size="40" onchange="$('#provinsi_tujuan').val($(this).val());"/>
					</td>
				</tr>
				<tr>
					<th>Kode Pos</th>
					<td>
						<input name="kode_pos_tujuan" type="text" class="inputbox" size="40"/>
					</td>
				</tr>
				<tr>
					<th>Telepon</th>
					<td>
						<input name="telepon_tujuan" type="text" class="inputbox required" size="40"/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
	  <th>Jenis Kepindahan</th>
	  <td>
	    <select id="jenis_kepindahan_id" name="jenis_kepindahan_id" class="required" onchange="urus_anggota($(this).val());">
	      <option value="">Pilih Jenis Kepindahan</option>
	      <?php foreach($kode['jenis_kepindahan'] as $key => $value){?>
	        <option value="<?php echo $key?>"><?php echo strtoupper($value)?></option>
	      <?php }?>
	    </select>
	  </td>
	</tr>
	<tr>
	  <th>Status KK Bagi Yang Tidak Pindah</th>
	  <td>
			<input id='status_kk_tidak_pindah' type="hidden" name="status_kk_tidak_pindah_id""/>
	    <select id="status_kk_tidak_pindah_show" class="required" onchange="$('#status_kk_tidak_pindah').val($(this).val());">
	      <option value="">Pilih Status KK Tidak Pindah</option>
	      <?php foreach($kode['status_kk_tidak_pindah'] as $key => $value){?>
	        <option value="<?php echo $key?>"><?php echo strtoupper($value)?></option>
	      <?php }?>
	    </select>
	    <select id="status_kk_tidak_pindah_f108_show" style="display: none" class="required" onchange="$('#status_kk_tidak_pindah').val($(this).val());">
	      <option value="">Pilih Status KK Tidak Pindah</option>
	      <?php foreach($kode['status_kk_tidak_pindah_f108'] as $key => $value){?>
	        <option value="<?php echo $key?>"><?php echo strtoupper($value)?></option>
	      <?php }?>
	    </select>
	  </td>
	</tr>
	<tr>
	  <th>Status KK Bagi Yang Pindah</th>
	  <td>
			<input id='status_kk_pindah' type="hidden" name="status_kk_pindah_id""/>
	    <select id='status_kk_pindah_show' class="required" onchange="$('#status_kk_pindah').val($(this).val());">
	      <option value="">Pilih Status KK Pindah</option>
	      <?php foreach($kode['status_kk_pindah'] as $key => $value){?>
	        <option value="<?php echo $key?>"><?php echo strtoupper($value)?></option>
	      <?php }?>
	    </select>
	  </td>
	</tr>

	<tr>
		<th colspan="1">Keluarga Yang Pindah</th>
		<td colspan="1">
			<div style="margin-left:0px;">
				<table class="list">
					<thead>
						<tr>
							<th>No</th>
							<th>&nbsp;</th>
							<th align="left" width='70'>NIK</th>
							<th width="100" align="left" >KTP Berlaku S/D</th>
							<th align="left" width='100'>Nama</th>
							<th align="left" width='30' align="center">Jenis Kelamin</th>
							<th width="70" align="left" >Umur</th>
							<th width="70" align="left" >Hubungan</th>
						</tr>
					</thead>

					<tbody>
						<?php if($anggota!=NULL){
							echo "<input id='jumlah_anggota' type='hidden' disabled='disabled' value='".count($anggota)."'/>";
							$i=0;?>
							<?php  foreach($anggota AS $data){ $i++;?>
								<tr>
			            <td align="center" width="2"><?php echo $i?></td>
									<td align="center" width="5">
										<?php if($data['kk_level']=="1"): ?>
											<input id='kk' type="hidden" name="id_cb[]" value="'<?php echo $data['nik']?>'"/>
											<input id='kk_show' disabled='disabled' type="checkbox" onchange="urus_masa_ktp($(this).is(':unchecked'),'<?php echo $i;?>');"/>
										<?php else: ?>
											<input id='anggota<?php echo $i?>' type="hidden" name="id_cb[]" disabled="disabled" value="'<?php echo $data['nik']?>'"/>
											<input id='anggota_show<?php echo $i?>' type="checkbox" value="'<?php echo $data['nik']?>'" onchange="urus_masa_ktp($(this).is(':unchecked'),'<?php echo $i;?>');"/>
										<?php endif; ?>
									</td>
									<td><?php echo $data['nik']?></td>
									<td>
										<input id="ktp_berlaku<?php echo ($i)?>" type="hidden" name="ktp_berlaku[]" type="text" value="Seumur Hidup"/>
										<input disabled="disabled" type="text" value="Seumur Hidup" class="inputbox" size="20"/>
									</td>
									<td><?php echo unpenetration($data['nama'])?></td>
									<td><?php echo $data['sex']?></td>
									<td><?php echo $data['umur']?></td>
									<td><?php echo $data['hubungan']?></td>
							</tr>
							<?php }?>
						<?php }?>
					</tbody>
				</table>
			</div>
		</td>
	</tr>

	<tr>
		<th>Tanggal Pindah</th>
		<td>
			<input name="tanggal_pindah" type="text" class="inputbox required datepicker" size="20"/>
		</td>
	</tr>
	<tr>
		<th>Keterangan</th>
		<td>
			<input name="keterangan" type="text" class="inputbox required" size="40"/>
		</td>
	</tr>

	<?php include("donjo-app/views/surat/form/_pamong.php"); ?>
</table>

</div>

    <div class="ui-layout-south panel bottom">
        <div class="left">
            <a href="<?php echo site_url()?>surat" class="uibutton icon prev">Kembali</a>
        </div>
        <div class="right">
            <div class="uibutton-group">
                <button class="uibutton" type="reset"><span class="fa fa-refresh"></span> Bersihkan</button>

							<?php if (SuratCetak($url)) { ?>
								<button type="button" onclick="$('#'+'validasi').attr('action','<?php echo $form_action?>');$('#'+'validasi').submit();" class="uibutton special"><span class="fa fa-print">&nbsp;</span>Cetak</button>
							<?php } ?>
							<?php if (SuratExport($url)) { ?>
								<button type="button" onclick="$('#'+'validasi').attr('action','<?php echo $form_action2?>');$('#'+'validasi').submit();" class="uibutton confirm"><span class="fa fa-file-text">&nbsp;</span>Export Doc</button>
							<?php } ?>
            </div>
        </div>
    </div> </form>
</div>
</td></tr></table>
</div>
