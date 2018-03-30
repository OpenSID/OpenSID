<script>
	$(function(){
		var nik = {};
		nik.results = [
			<?php foreach($penduduk as $data){?>
				{id:'<?php echo $data['id']?>',name:"<?php echo $data['nik']." - ".($data['nama'])?>",info:"<?php echo ($data['alamat'])?>"},
			<?php }?>
		];

		$('#nik').flexbox(nik, {
			resultTemplate: '<div><label>No nik : </label>{name}</div><div>{info}</div>',
			watermark: '<?php echo $individu
				? $individu['nik']. ' - '.addslashes(spaceunpenetration($individu['nama']))
				: 'Ketik nama / nik di sini..'; ?>',
			width: 260,
			noResultsText :'Tidak ada no nik yang sesuai..',
			onSelect: function() {
        $('#nik_validasi').val($('#nik_hidden').val());
        submit_form_ambil_data();
			}
		});

	});

  function ubah_pelaku(peran, asal){
    $('#'+peran).val(asal);
    if(asal == 1){
      $('.'+peran+'_desa').show();
      $('.'+peran+'_luar_desa').hide();
      // Mungkin bug di jquery? Terpaksa hapus class radio button
      $('#label_'+peran+'_2').removeClass('ui-state-active');
    } else {
      $('.'+peran+'_desa').hide();
      $('.'+peran+'_luar_desa').show();
      $('#id_'+peran+'_validasi').val('*'); // Hapus id
      submit_form_ambil_data();
    }
    $('input[name=anchor').val(peran);
  }

  function ubah_saksi1(asal){
    $('#saksi1').val(asal);
    if(asal == 1){
      $('.saksi1_desa').show();
      $('.saksi1_luar_desa').hide();
      // Mungkin bug di jquery? Terpaksa hapus class radio button
      $('#label_saksi1_2').removeClass('ui-state-active');
    } else {
      $('.saksi1_desa').hide();
      $('.saksi1_luar_desa').show();
      $('#id_saksi1_validasi').val('*'); // Hapus id
      submit_form_ambil_data();
    }
    $('input[name=anchor').val('saksi1');
  }

	$(function(){
    var saksi1 = {};
    saksi1.results = [
      <?php foreach($penduduk as $data){?>
        {id:'<?php echo $data['id']?>',name:"<?php echo $data['nik']." - ".($data['nama'])?>",info:"<?php echo ($data['alamat'])?>"},
      <?php }?>
    ];

    $('#id_saksi1').flexbox(saksi1, {
      resultTemplate: '<div><label>No nik : </label>{name}</div><div>{info}</div>',
      watermark: '<?php echo $saksi1
		? $saksi1['nik']. ' - '.addslashes($saksi1['nama'])
		: 'Ketik nama / nik di sini..'; ?>',
      width: 260,
      noResultsText :'Tidak ada no nik yang sesuai..',
      onSelect: function() {
        $('input[name=anchor').val('saksi1');
        $('#id_saksi1_validasi').val($('#id_saksi1_hidden').val());
        submit_form_ambil_data();
      }
    });
  });

  function ubah_saksi2(asal){
    $('#saksi2').val(asal);
    if(asal == 1){
      $('.saksi2_desa').show();
      $('.saksi2_luar_desa').hide();
      // Mungkin bug di jquery? Terpaksa hapus class radio button
      $('#label_saksi2_2').removeClass('ui-state-active');
    } else {
      $('.saksi2_desa').hide();
      $('.saksi2_luar_desa').show();
      $('#id_saksi2_validasi').val('*'); // Hapus id
      submit_form_ambil_data();
    }
    $('input[name=anchor').val('saksi2');
  }

  $(function(){
    var saksi2 = {};
    saksi2.results = [
      <?php foreach($penduduk as $data){?>
        {id:'<?php echo $data['id']?>',name:"<?php echo $data['nik']." - ".($data['nama'])?>",info:"<?php echo ($data['alamat'])?>"},
      <?php }?>
    ];

    $('#id_saksi2').flexbox(saksi2, {
      resultTemplate: '<div><label>No nik : </label>{name}</div><div>{info}</div>',
      watermark: '<?php echo $saksi2
		? $saksi2['nik']. ' - '.addslashes(spaceunpenetration($saksi2['nama']))
		: 'Ketik nama / nik di sini..'; ?>',
      width: 260,
      noResultsText :'Tidak ada no nik yang sesuai..',
      onSelect: function() {
        $('input[name=anchor').val('saksi2');
        $('#id_saksi2_validasi').val($('#id_saksi2_hidden').val());
        submit_form_ambil_data();
      }
    });
  });

  function submit_form_ambil_data(){
    $('input').removeClass('required');
    $('select').removeClass('required');
    $('#'+'validasi').attr('action','');
    $('#'+'validasi').attr('target','');
    $('#'+'validasi').submit();
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
  .grey {
    background-color: lightgrey;
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
		<h3>Surat SPORADIK Sertifikat</h3>
	</div>
	<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
		<table class="form">

			<form id="validasi" action="<?php echo $form_action?>" method="POST" target="_blank">
        <input id="nik_validasi" name="nik" type="hidden" value="<?php echo $_SESSION['post']['nik']?>">
        <input id="id_pemohon_validasi" name="id_pemohon" type="hidden" value="">
			  <input id="id_saksi1_validasi" name="id_saksi1" type="hidden" value="<?php echo $_SESSION['id_saksi1']?>"/>
			  <input id="id_saksi2_validasi" name="id_saksi2" type="hidden" value="<?php echo $_SESSION['id_saksi2']?>"/>

				<!-- PEMOHON -->
				<tr><th><a name="pemohon"></a></th><td>&nbsp;</td></tr>
				<tr>
				  <th class="grey">PEMOHON</th>
				  <td class="grey">
				    <div class="uiradio">
				      <input type="radio" id="pemohon_1" name="pemohon" value="1" <?php if(!empty($individu)){echo 'checked';}?> onchange="ubah_pelaku('pemohon',this.value);">
				      <label for="pemohon_1">Warga Desa</label>
				      <input type="radio" id="pemohon_2" name="pemohon" value="2" <?php if(empty($individu)){echo 'checked';}?> onchange="ubah_pelaku('pemohon',this.value);">
				      <label id="label_pemohon_2" for="pemohon_2">Warga Luar Desa</label>
				    </div>
				  </td>
				</tr>

				<tr class="pemohon_desa" <?php if (empty($individu)) echo 'style="display: none;"'; ?>>
				  <th colspan="2">PEMOHON WARGA DESA</th>
				</tr>
				<tr class="pemohon_desa" <?php if (empty($individu)) echo 'style="display: none;"'; ?>>
				  <th class="indent">NIK / Nama</th>
				  <td>
				    <div id="nik" name="nik"></div>
				    <?php if($individu){ //bagian info setelah terpilih
				        include("donjo-app/views/surat/form/konfirmasi_pemohon.php");
				    }?>
				  </td>
				</tr>

				<?php if (empty($individu)) : ?>
				  <tr class="pemohon_luar_desa">
				    <th class="style6">DATA PEMOHON LUAR DESA</th>
				  </tr>
					<tr class="pemohon_luar_desa">
						<th>Nama</th>
						<td><input name="nama_non_warga" type="text" class="inputbox required" size="50" value="<?php echo $_SESSION['post']['nama_non_warga']?>"/></td>
					</tr>
					<tr class="pemohon_luar_desa">
						<th>Nomor KTP</th>
						<td><input name="nik_non_warga" type="text" class="inputbox required" size="30" value="<?php echo $_SESSION['post']['nik_non_warga']?>"/></td>
					</tr>
					<tr class="pemohon_luar_desa">
						<th class="indent">Tempat Tanggal Lahir</th>
						<td>
							<input name="tempatlahir_pemohon" type="text" class="inputbox" size="30" value="<?php echo $_SESSION['post']['tempatlahir_pemohon']?>"/>
							<input name="tanggallahir_pemohon" type="text" class="inputbox datepicker" size="20" value="<?php echo $_SESSION['post']['tempatlahir_pemohon']?>"/>
						</td>
					</tr>
					<tr class="pemohon_luar_desa">
						<th class="indent">Pekerjaan</th>
						<td>
					    <select name="pekerjaan_pemohon">
					      <option value="">Pilih Pekerjaan</option>
					      <?php foreach($pekerjaan as $data){?>
					        <option value="<?php echo $data['nama']?>" <?php if($data['nama']==$_SESSION['post']['pekerjaan_pemohon']) echo 'selected'?>><?php echo ucwords($data['nama'])?></option>
					      <?php }?>
					    </select>
						</td>
					</tr>
					<tr class="pemohon_luar_desa">
						<th class="indent">Tempat Tinggal</th>
						<td>
							<input name="alamat_pemohon" type="text" class="inputbox" size="100" value="<?php echo $_SESSION['post']['alamat_pemohon']?>"/>
						</td>
					</tr>
				<?php endif; ?>

				<tr>
					<th>ATAS BIDANG TANAH YANG TERLETAK DI :</th>
				</tr>
				<tr>
					<th>Jalan </th>
					<td><input name="jalan" type="text" class="inputbox required" size="50" value="<?php echo $_SESSION['post']['jalan']?>"/></td>
				</tr>
				<tr>
					<th>RT/RW</th>
					<td><input name="rtrw" type="text" class="inputbox required" size="8" value="<?php echo $_SESSION['post']['rtrw']?>"/></td>
				</tr>
				<tr>
					<th>Desa / Kelurahan</th>
					<td><input name="desalurah" type="text" class="inputbox required" readonly="readonly" size="35"/<option value="<?php echo $desa['nama_desa']?>"></td>
				</tr>
				<tr>
					<th>Kecamatan</th>
					<td><input name="camatt" type="text" class="inputbox required" readonly="readonly" size="15"/<option value="<?php echo $desa['nama_kecamatan']?>"></td>
				</tr>
				<tr>
					<th>Kabupaten / Kota</th>
					<td><input name="kabb" type="text" class="inputbox required" readonly="readonly" size="15"/<option value="<?php echo $desa['nama_kabupaten']?>"></td>
				</tr>
				<tr>
					<th>NIB</th>
					<td><input name="nib" type="text" class="inputbox required" size="40" value="<?php echo $_SESSION['post']['nib']?>"/></td>
				</tr>
				<tr>
					<th>Luas Tanah</th>
					<td><input name="luashak" type="text" class="inputbox required" size="12" value="<?php echo $_SESSION['post']['luashak']?>"/> M2</td>
				</tr>
				<tr>
					<th>Status Tanah</th>
					<td><input name="statustanah" type="text" class="inputbox required" size="30" value="<?php echo $_SESSION['post']['statustanah']?>"/></td>
				</tr>
				<tr>
					<th>Dipergunakan</th>
					<td>
						<input name="tanahuntuk" type="text" class="inputbox required" size="25" value="<?php echo $_SESSION['post']['tanahuntuk']?>"/>
					</td>
				</tr>
				<tr>
					<th>BATAS-BATAS</th>
				</tr>
				<tr>
					<th>Sebelah Utara</th>
					<td>
						<input name="utara" type="text" class="inputbox required" size="50" value="<?php echo $_SESSION['post']['utara']?>"/>
					</td>
				</tr>
				<tr>
					<th>Sebelah Timur</th>
					<td><input name="timur" type="text" class="inputbox required" size="50" value="<?php echo $_SESSION['post']['timur']?>"/></td>
				</tr>
				<tr>
					<th>Sebelah Selatan</th>
					<td>
						<input name="selatan" type="text" class="inputbox required" size="50" value="<?php echo $_SESSION['post']['selatan']?>"/>
					</td>
				</tr>
				<tr>
					<th>Sebelah Barat</th>
					<td><input name="barat" type="text" class="inputbox required" size="50" value="<?php echo $_SESSION['post']['barat']?>"/></td>
				</tr>
				<tr>
					<th>Tanah di Peroleh dari :</th>
					<td>
						<input name="peroleh" type="text" class="inputbox required" size="55" value="<?php echo $_SESSION['post']['peroleh']?>"/>Sejak Tahun
						<input name="perolehtahun" type="text" class="inputbox required" size="5" value="<?php echo $_SESSION['post']['perolehtahun']?>"/> dengan Jalan
						<input name="denganjalan" type="text" class="inputbox required" size="65" value="<?php echo $_SESSION['post']['denganjalan']?>"/>
					</td>
				</tr>

				<!-- SAKSI 1 -->
				<tr><th><a name="saksi1"></a></th><td>&nbsp;</td></tr>
				<tr>
				  <th class="grey">SAKSI 1</th>
				  <td class="grey">
				    <div class="uiradio">
				      <input type="radio" id="saksi1_1" name="saksi1" value="1" <?php if(!empty($saksi1)){echo 'checked';}?> onchange="ubah_saksi1(this.value);">
				      <label for="saksi1_1">Warga Desa</label>
				      <input type="radio" id="saksi1_2" name="saksi1" value="2" <?php if(empty($saksi1)){echo 'checked';}?> onchange="ubah_saksi1(this.value);">
				      <label id="label_saksi1_2" for="saksi1_2">Warga Luar Desa</label>
				    </div>
				  </td>
				</tr>

				<tr class="saksi1_desa" <?php if (empty($saksi1)) echo 'style="display: none;"'; ?>>
				  <th colspan="2">DATA SAKSI 1 WARGA DESA</th>
				</tr>
				<tr class="saksi1_desa" <?php if (empty($saksi1)) echo 'style="display: none;"'; ?>>
				  <th class="indent">NIK / Nama</th>
				  <td>
				    <div id="id_saksi1" name="id_saksi1"></div>
				    <?php if($saksi1){ //bagian info setelah terpilih
				        $individu = $saksi1;
				        include("donjo-app/views/surat/form/konfirmasi_pemohon.php");
				    }?>
				  </td>
				</tr>

				<?php if (empty($saksi1)) : ?>
				  <tr class="saksi1_luar_desa">
				    <th class="style6">DATA SAKSI 1 LUAR DESA</th>
				  </tr>
					<tr class="saksi1_luar_desa">
						<th>Nama</th>
						<td><input name="namasaksii" type="text" class="inputbox required" size="50" value="<?php echo $_SESSION['post']['namasaksii']?>"/></td>
					</tr>
					<tr class="saksi1_luar_desa">
						<th>Umur</th>
						<td><input name="umursaksii" type="text" class="inputbox required" size="10" value="<?php echo $_SESSION['post']['umursaksii']?>"/> Tahun </td>
					</tr>
					<tr class="saksi1_luar_desa">
						<th>Pekerjaan</th>
						<td>
							<input name="pekerjaansaksii" type="text" class="inputbox required" size="35" value="<?php echo $_SESSION['post']['pekerjaansaksii']?>"/>
						</td>
					</tr>
					<tr class="saksi1_luar_desa">
						<th>Alamat</th>
						<td>
							<input name="alamatsaksii" type="text" class="inputbox required" size="60" value="<?php echo $_SESSION['post']['alamatsaksii']?>"/>
						</td>
					</tr>
				<?php endif; ?>

				<!-- SAKSI 2 -->
				<tr><th><a name="saksi2"></a></th><td>&nbsp;</td></tr>
				<tr>
				  <th class="grey">SAKSI 2</th>
				  <td class="grey">
				    <div class="uiradio">
				      <input type="radio" id="saksi2_1" name="saksi2" value="1" <?php if(!empty($saksi2)){echo 'checked';}?> onchange="ubah_saksi2(this.value);">
				      <label for="saksi2_1">Warga Desa</label>
				      <input type="radio" id="saksi2_2" name="saksi2" value="2" <?php if(empty($saksi2)){echo 'checked';}?> onchange="ubah_saksi2(this.value);">
				      <label id="label_saksi2_2" for="saksi2_2">Warga Luar Desa</label>
				    </div>
				  </td>
				</tr>

				<tr class="saksi2_desa" <?php if (empty($saksi2)) echo 'style="display: none;"'; ?>>
				  <th colspan="2">DATA SAKSI 2 WARGA DESA</th>
				</tr>
				<tr class="saksi2_desa" <?php if (empty($saksi2)) echo 'style="display: none;"'; ?>>
				  <th class="indent">NIK / Nama</th>
				  <td>
				    <div id="id_saksi2" name="id_saksi2"></div>
				    <?php if($saksi2){ //bagian info setelah terpilih
				        $individu = $saksi2;
				        include("donjo-app/views/surat/form/konfirmasi_pemohon.php");
				    }?>
				  </td>
				</tr>

				<?php if (empty($saksi2)) : ?>
				  <tr class="saksi2_luar_desa">
				    <th class="style6">DATA SAKSI 2 LUAR DESA</th>
				  </tr>
					<tr class="saksi2_luar_desa">
						<th>Nama</th>
						<td><input name="namasaksiii" type="text" class="inputbox required" size="50" value="<?php echo $_SESSION['post']['namasaksiii']?>"/></td>
					</tr>
					<tr class="saksi2_luar_desa">
						<th>Umur</th>
						<td><input name="umursaksiii" type="text" class="inputbox required" size="10" value="<?php echo $_SESSION['post']['umursaksiii']?>"/> Tahun</td>
					</tr>
					<tr class="saksi2_luar_desa">
						<th>Pekerjaan</th>
						<td>
							<input name="pekerjaansaksiii" type="text" class="inputbox required" size="35" value="<?php echo $_SESSION['post']['pekerjaansaksiii']?>"/>
						</td>
					</tr>
					<tr class="saksi2_luar_desa">
						<th>Alamat</th>
						<td>
							<input name="alamatsaksiii" type="text" class="inputbox required" size="60" value="<?php echo $_SESSION['post']['alamatsaksiii']?>"/>
						</td>
					</tr>
				<?php endif; ?>

        <tr>
          <th colspan="2" class="grey">PENANDA TANGAN</th>
        </tr>
        <tr>
          <th>Atas Nama</th>
          <td>
            <select name="atas_nama"  type="text" class="inputbox">
              <option value="">Atas Nama</option>
              <?php  foreach($atas_nama as $data){?>
                <option value="<?php echo $data?>" <?php if($data==$_SESSION['post']['atas_nama']) echo 'selected'?>><?php echo $data?></option>
              <?php }?>
            </select>
          </td>
        </tr>
        <?php include("donjo-app/views/surat/form/_pamong.php"); ?>
			</form>
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
  </div>


</div>
</td></tr></table>
</div>