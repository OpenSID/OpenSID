<script language="javascript" type="text/javascript">
	function calon_wanita_asal(asal){
		$('#calon_wanita').val(asal);
		if(asal == 1){
			$('.wanita_desa').show();
			$('.wanita_luar_desa').hide();
			// Mungkin bug di jquery? Terpaksa hapus class radio button
			$('#label_calon_wanita_2').removeClass('ui-state-active');
		} else {
			$('.wanita_desa').hide();
			$('.wanita_luar_desa').show();
			$('#id_wanita_validasi').val('*'); // Hapus $id_wanita
			submit_form_ambil_data();
		}
	}
	function calon_pria(asal){
		$('#calon_pria').val(asal);
		if(asal == 1){
			$('.pria_desa').show();
			$('.pria_luar_desa').hide();
		} else {
			$('.pria_desa').hide();
			$('.pria_luar_desa').show();
			$('#id_wanita_copy').val($('#id_wanita_hidden').val());
			$('#id_wanita_validasi').val($('#id_wanita_hidden').val());
			$('#id_pria_validasi').val('*'); // Hapus $id_pria
			submit_form_ambil_data();
		}
	}
	function nomor_surat(nomor){
		$('#nomor').val(nomor);
		$('#nomor_main').val(nomor);
	}
	function submit_form_ambil_data(){
		$('input').removeClass('required');
		$('select').removeClass('required');
		$('#'+'validasi').attr('action','')
		$('#'+'validasi').attr('target','')
		$('#'+'validasi').submit();
	}
	function submit_form_doc(){
		if(($('#id_pria_validasi').val()=='' || $('#id_pria_validasi').val()=='*') && ($('#id_wanita_validasi').val()=='' || $('#id_wanita_validasi').val()=='*')){
			$('#dialog').show();
			$('#dialog').dialog();
			return;
		}
		$('#'+'validasi').attr('action','<?php echo $form_action2?>');
		$('#'+'validasi').submit();
	}

$(function(){
var pria = {};
pria.results = [
<?php foreach($laki as $data){?>
{id:'<?php echo $data['id']?>',name:"<?php echo $data['nik']." - ".($data['nama'])?>",info:"<?php echo ($data['alamat'])?>"},
<?php }?>
];

$('#id_pria').flexbox(pria, {
resultTemplate: '<div><label>No nik : </label>{name}</div><div>{info}</div>',
watermark: <?php if($pria){?>'<?php echo $pria['nik']?> - <?php echo spaceunpenetration($pria['nama'])?>'<?php }else{?>'Ketik no nik di sini..'<?php }?>,
width: 260,
noResultsText :'Tidak ada no nik yang sesuai..',
onSelect: function() {
	// Hapus isian ayah, ibu, istri dulu jika ganti calon pria
	if($('#id_pria_hidden').val() != $('#id_pria_validasi').val()){
		$('.ibu_pria').val('');
		$('.ayah_pria').val('');
		$('.istri_dulu').val('');
	};
	// $('#id_wanita_copy').val($('#id_wanita_hidden').val());
	// $('#id_wanita_validasi').val($('#id_wanita_hidden').val());
	$('#id_pria_validasi').val($('#id_pria_hidden').val());
	submit_form_ambil_data();
}
});

var wanita = {};
wanita.results = [
<?php foreach($perempuan as $data){?>
{id:'<?php echo $data['id']?>',name:"<?php echo $data['nik']." - ".($data['nama'])?>",info:"<?php echo ($data['alamat'])?>"},
<?php }?>
];

$('#id_wanita').flexbox(wanita, {
resultTemplate: '<div><label>No nik : </label>{name}</div><div>{info}</div>',
watermark: <?php if($wanita){?>'<?php echo $wanita['nik']?> - <?php echo spaceunpenetration($wanita['nama'])?>'<?php }else{?>'Ketik no nik di sini..'<?php }?>,
width: 260,
noResultsText :'Tidak ada no nik yang sesuai..',
onSelect: function() {
	// Hapus isian wali, suami_dulu, ayah, ibu jika ganti calon wanita
	if($('#id_wanita_hidden').val() != $('#id_wanita_validasi').val()){
		$('.ibu_wanita').val('');
		$('.ayah_wanita').val('');
		$('.wali').val('');
		$('.suami_dulu').val('');
	};
	// $('#id_wanita_copy').val($('#id_wanita_hidden').val());
	$('#id_wanita_validasi').val($('#id_wanita_hidden').val());
	$('input').removeClass('required');
	$('select').removeClass('required');
	$('#'+'validasi').attr('action','')
	$('#'+'validasi').attr('target','')
	$('#'+'validasi').submit();
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
span.judul_tengah {
	font-weight: bold;
	padding-left: 10px;
	padding-right: 5px;
}
.grey {
	background-color: lightgrey;
}
table.form th.indent{
	padding-left: 30px;
}
table.form th.konfirmasi{
	padding-left: 30px;
}
</style>

<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">

<td style="background:#fff;padding:5px;">
<div class="content-header"></div>
<div id="contentpane">
	<div class="ui-layout-north panel">
		<h3>Surat Keterangan Untuk Nikah</h3>
	</div>

	<div id="dialog" title="Perhatian" style="display: none;">
	  <p>Salah satu calon pasangan, pria atau wanita, harus warga desa.</p>
	</div>
	<div class="ui-layout-center" id="maincontent" style="padding: 5px;">
		<table class="form">
			<tr>
				<th>Nomor Surat</th>
				<td>
					<input name="input_nomor" type="text" class="inputbox required" size="12" value="<?php echo $_SESSION['post']['nomor']; ?>" onchange="nomor_surat(this.value);"/>
					<span>Terakhir: <?php echo $surat_terakhir['no_surat'];?> (tgl: <?php echo $surat_terakhir['tanggal']?>)</span>
				</td>
			</tr>

			<?php $jenis_pasangan = "Istri"; ?>
			<tr>
				<th class="grey">A. CALON PASANGAN PRIA</th>
			  <td class="grey">
			    <div class="uiradio">
			      <input type="radio" id="calon_pria_1" name="calon_pria" value="1" <?php if(!empty($pria)){echo 'checked';}?> onchange="calon_pria(this.value);">
			      <label for="calon_pria_1">Warga Desa</label>
			      <input type="radio" id="calon_pria_2" name="calon_pria" value="2" <?php if(empty($pria)){echo 'checked';}?> onchange="calon_pria(this.value);">
			      <label for="calon_pria_2">Warga Luar Desa</label>
			    </div>
			  </td>
			</tr>

			<tr class="pria_desa" <?php if (empty($pria)) echo 'style="display: none;"'; ?>>
				<th colspan="2">A.1 DATA CALON PASANGAN PRIA WARGA DESA</th>
			</tr>
			<tr class="pria_desa" <?php if (empty($pria)) echo 'style="display: none;"'; ?>>
				<th class="indent">NIK / Nama</th>
				<td>
					<form action="" id="main" name="main" method="POST">
						<input id="nomor_main" name="nomor_main" type="hidden" value="<?php echo $nomor; ?>"/>
						<input id="calon_pria" name="calon_pria" type="hidden" value=""/>
						<div id="id_pria" name="id_pria"></div>
						<input id="calon_wanita" name="calon_wanita" type="hidden" value=""/>
						<!-- Diisi oleh script flexbox wanita -->
						<input id="id_wanita_copy" name="id_wanita" type="hidden" value="kosong"/>
					</form>
					<?php if($pria){ //bagian info setelah terpilih
							$individu = $pria;
						  include("donjo-app/views/surat/form/konfirmasi_pemohon.php");
					}?>
				</td>
			</tr>

			<form id="validasi" action="<?php echo $form_action?>" method="POST" target="_blank">
				<input id="nomor" name="nomor" type="hidden" value="<?php echo $_SESSION['post']['nomor']; ?>"/>
				<input id="id_pria_validasi" type="hidden" name="id_pria" value="<?php echo $_SESSION['id_pria']?>">
				<input id="id_wanita_validasi" name="id_wanita" type="hidden" value="<?php echo $_SESSION['id_wanita']?>"/>

				<?php if (empty($pria)) : ?>
					<tr class="pria_luar_desa">
						<th colspan="2">A.1 DATA CALON PASANGAN PRIA LUAR DESA</th>
					</tr>
					<tr class="pria_luar_desa">
						<th class="indent">Nama Lengkap</th>
						<td>
							<input name="nama_pria" type="text" class="inputbox" size="30" value="<?php echo $_SESSION['post']['nama_pria']?>"/>
						</td>
					</tr>
					<tr class="pria_luar_desa">
						<th class="indent">Tempat Tanggal Lahir</th>
						<td>
							<input name="tempatlahir_pria" type="text" class="inputbox" size="30" value="<?php echo $_SESSION['post']['tempatlahir_pria']?>"/>
							<input name="tanggallahir_pria" type="text" class="inputbox datepicker" size="20" value="<?php echo $_SESSION['post']['tempatlahir_pria']?>"/>
						</td>
					</tr>
					<tr class="pria_luar_desa">
						<th class="indent">Warganegara</th>
						<td colspan="5">
					    <select name="wn_pria">
					      <option value="">Pilih warganegara</option>
					      <?php foreach($warganegara as $data){?>
					        <option value="<?php echo $data['nama']?>" <?php if($data['nama']==$_SESSION['post']['wn_pria']) echo 'selected'?>><?php echo strtoupper($data['nama'])?></option>
					      <?php }?>
						  </select>
							<span class="judul_tengah">Agama</span>
					    <select name="agama_pria">
					      <option value="">Pilih Agama</option>
					      <?php foreach($agama as $data){?>
					        <option value="<?php echo $data['nama']?>" <?php if($data['nama']==$_SESSION['post']['agama_pria']) echo 'selected'?>><?php echo ucwords($data['nama'])?></option>
					      <?php }?>
					    </select>
							<span class="judul_tengah">Pekerjaan</span>
					    <select name="pekerjaan_pria">
					      <option value="">Pilih Pekerjaan</option>
					      <?php  foreach($pekerjaan as $data){?>
					        <option value="<?php echo $data['nama']?>" <?php if($data['nama']==$_SESSION['post']['pekerjaan_pria']) echo 'selected'?>><?php echo ucwords($data['nama'])?></option>
					      <?php }?>
					    </select>
						</td>
					</tr>
					<tr class="pria_luar_desa">
						<th class="indent">Tempat Tinggal</th>
						<td>
							<input name="alamat_pria" type="text" class="inputbox" size="40" value="<?php echo $_SESSION['post']['alamat_pria']?>"/>
						</td>
					</tr>
					<tr class="pria_luar_desa">
						<th class="indent">Jika pria, terangkan jejaka, duda atau beristri</th>
						<td>
					    <select name="status_kawin_pria">
					      <option value="">Pilih Status Kawin</option>
					      <?php  foreach($kode['status_kawin_pria'] as $data){?>
					        <option value="<?php echo $data?>" <?php if($data['nama']==$_SESSION['post']['status_kawin_pria']) echo 'selected'?>><?php echo ucwords($data)?></option>
					      <?php }?>
					    </select>
						</td>
					</tr>
					<tr class="pria_luar_desa">
						<th class="indent">Jika beristri, berapa istrinya</th>
						<td>
							<input name="jumlah_istri" type="text" class="inputbox " size="10" value="<?php echo $_SESSION['post']['jumlah_istri']?>"/>
						</td>
					</tr>
				<?php endif; ?>

				<?php if($pria) : ?>
					<tr>
						<th class="indent">Jika pria, terangkan jejaka, duda atau beristri</th>
						<td>
					    <select name="status_kawin_pria">
					      <option value="">Pilih Status Kawin</option>
					      <?php  foreach($kode['status_kawin_pria'] as $data){?>
					        <option value="<?php echo $data?>" <?php if($pria['status_kawin_pria']==$data) echo 'selected';?>><?php echo ucwords($data)?></option>
					      <?php }?>
					    </select>
							<span>(Status kawin: <?php echo $pria['status_kawin']?>)</span>
						</td>
					</tr>
					<?php if($pria['status_kawin']=="KAWIN") : ?>
						<tr>
							<th class="indent">Jika beristri, berapa istrinya</th>
							<td>
								<input name="jumlah_istri" type="text" class="inputbox " size="10" value="1"/>
							</td>
						</tr>
					<?php else:?>
						<input name="jumlah_istri" type="hidden" value=""/>
					<?php endif; ?>
				<?php endif; ?>
				<?php if ($ayah_pria) : ?>
					<tr>
						<th colspan="2">A.2 DATA AYAH PASANGAN PRIA</th>
					</tr>
					<tr>
						<th class="indent">Nama</th>
						<td><?php echo $ayah_pria['nama']?></td>
					</tr>
					<tr>
						<th class="indent">Tempat Tanggal Lahir</th>
						<td>
							<?php echo $ayah_pria['tempatlahir']." / ".tgl_indo_out($ayah_pria['tanggallahir'])?>
						</td>
					</tr>
					<tr>
						<th class="indent">Warganegara</th>
						<td>
							<?php echo $ayah_pria['wn']?>
							<span class="judul_tengah">Agama : </span>
							<?php echo $ayah_pria['agama']?>
							<span class="judul_tengah">Pekerjaan : </span>
							<?php echo $ayah_pria['pek']?>
						</td>
					</tr>
					<tr>
						<th class="indent">Tempat Tinggal</th>
						<td><?php echo $ayah_pria['alamat_wilayah']?></td>
					</tr>
				<?php else: ?>
					<tr>
						<th colspan="2">A.2 DATA AYAH PASANGAN PRIA (Isi jika ayah bukan warga <?php echo strtolower($this->setting->sebutan_desa)?> ini)</th>
					</tr>
					<tr>
						<th class="indent">Nama</th>
						<td><input name="nama_ayah_pria" type="text" class="ayah_pria inputbox " size="30" value="<?php echo $_SESSION['post']['nama_ayah_pria']?>" /></td>
					</tr>
					<tr>
						<th class="indent">Tempat Tanggal Lahir</th>
						<td>
							<input name="tempatlahir_ayah_pria" type="text" class="ayah_pria inputbox " size="30" value="<?php echo $_SESSION['post']['tempatlahir_ayah_pria']?>"/>
							<input name="tanggallahir_ayah_pria" type="text" class="ayah_pria inputbox datepicker" size="20" value="<?php echo $_SESSION['post']['tanggallahir_ayah_pria']?>"/>
						</td>
					</tr>
					<tr>
						<th class="indent">Warganegara</th>
						<td colspan="5">
					    <select name="wn_ayah_pria" class="ayah_pria">
					      <option value="">Pilih warganegara</option>
					      <?php foreach($warganegara as $data){?>
					        <option value="<?php echo $data['nama']?>" <?php if($data['nama']==$_SESSION['post']['wn_ayah_pria']) echo 'selected'?>> <?php echo strtoupper($data['nama'])?></option>
					      <?php }?>
						  </select>
							<span class="judul_tengah">Agama</span>
					    <select name="agama_ayah_pria" class="ayah_pria">
					      <option value="">Pilih Agama</option>
					      <?php foreach($agama as $data){?>
					        <option value="<?php echo $data['nama']?>" <?php if($data['nama']==$_SESSION['post']['agama_ayah_pria']) echo 'selected'?>> <?php echo ucwords($data['nama'])?></option>
					      <?php }?>
					    </select>
							<span class="judul_tengah">Pekerjaan</span>
					    <select name="pekerjaan_ayah_pria" class="ayah_pria">
					      <option value="">Pilih Pekerjaan</option>
					      <?php  foreach($pekerjaan as $data){?>
					        <option value="<?php echo $data['nama']?>" <?php if($data['nama']==$_SESSION['post']['pekerjaan_ayah_pria']) echo 'selected'?>> <?php echo ucwords($data['nama'])?></option>
					      <?php }?>
					    </select>
						</td>
					</tr>
					<tr>
						<th class="indent">Tempat Tinggal</th>
						<td><input name="alamat_ayah_pria" type="text" class="ayah_pria inputbox " size="80" value="<?php echo $_SESSION['post']['alamat_ayah_pria']?>"/></td>
					</tr>
				<?php endif; ?>

				<?php if ($ibu_pria) : ?>
					<tr>
						<th colspan="2">A.3 DATA IBU PASANGAN PRIA</th>
					</tr>
					<tr>
						<th class="indent">Nama</th>
						<td><?php echo $ibu_pria['nama']?></td>
					</tr>
					<tr>
						<th class="indent">Tempat Tanggal Lahir</th>
						<td>
							<?php echo $ibu_pria['tempatlahir']." / ".tgl_indo_out($ibu_pria['tanggallahir'])?>
						</td>
					</tr>
					<tr>
						<th class="indent">Warganegara</th>
						<td>
							<?php echo $ibu_pria['wn']?>
							<span class="judul_tengah">Agama : </span>
							<?php echo $ibu_pria['agama']?>
							<span class="judul_tengah">Pekerjaan : </span>
							<?php echo $ibu_pria['pek']?>
						</td>
					</tr>
					<tr>
						<th class="indent">Tempat Tinggal</th>
						<td><?php echo $ibu_pria['alamat_wilayah']?></td>
					</tr>
				<?php else: ?>
					<tr>
						<th colspan="2">A.3 DATA IBU PASANGAN PRIA (Isi jika ibu bukan warga <?php echo strtolower($this->setting->sebutan_desa)?> ini)</th>
					</tr>
					<tr>
						<th class="indent">Nama</th>
						<td><input name="nama_ibu_pria" type="text" class="ibu_pria inputbox " size="30" value="<?php echo $_SESSION['post']['nama_ibu_pria']?>"/></td>
					</tr>
					<tr>
						<th class="indent">Tempat Tanggal Lahir</th>
						<td>
							<input name="tempatlahir_ibu_pria" type="text" class="ibu_pria inputbox " size="30" value="<?php echo $_SESSION['post']['tempatlahir_ibu_pria']?>"/>
							<input name="tanggallahir_ibu_pria" type="text" class="ibu_pria inputbox  datepicker" size="20" value="<?php echo $_SESSION['post']['tanggallahir_ibu_pria']?>"/>
						</td>
					</tr>
					<tr>
						<th class="indent">Warganegara</th>
						<td colspan="5">
					    <select name="wn_ibu_pria" class="ibu_pria">
					      <option value="">Pilih warganegara</option>
					      <?php foreach($warganegara as $data){?>
					        <option value="<?php echo $data['nama']?>" <?php if($data['nama']==$_SESSION['post']['wn_ibu_pria']) echo 'selected'?>> <?php echo strtoupper($data['nama'])?></option>
					      <?php }?>
						  </select>
							<span class="judul_tengah">Agama</span>
					    <select name="agama_ibu_pria" class="ibu_pria">
					      <option value="">Pilih Agama</option>
					      <?php foreach($agama as $data){?>
					        <option value="<?php echo $data['nama']?>" <?php if($data['nama']==$_SESSION['post']['agama_ibu_pria']) echo 'selected'?>> <?php echo ucwords($data['nama'])?></option>
					      <?php }?>
					    </select>
							<span class="judul_tengah">Pekerjaan</span>
					    <select name="pekerjaan_ibu_pria" class="ibu_pria">
					      <option value="">Pilih Pekerjaan</option>
					      <?php  foreach($pekerjaan as $data){?>
					        <option value="<?php echo $data['nama']?>" <?php if($data['nama']==$_SESSION['post']['pekerjaan_ibu_pria']) echo 'selected'?>> <?php echo ucwords($data['nama'])?></option>
					      <?php }?>
					    </select>
						</td>
					</tr>
					<tr>
						<th class="indent">Tempat Tinggal</th>
						<td><input name="alamat_ibu_pria" type="text" class="ibu_pria inputbox " size="80" value="<?php echo $_SESSION['post']['alamat_ibu_pria']?>"/></td>
					</tr>
				<?php endif; ?>

				<?php if(empty($pria) OR $pria['status_kawin']=="CERAI MATI") : ?>
					<tr>
						<th colspan="2">A.4 DATA ISTRI TERDAHULU </th>
					</tr>
					<tr>
						<th class="indent">Nama <?php echo ucwords($jenis_pasangan)?> Terdahulu</th>
						<td>
							<input name="istri_dulu" type="text" class="istri_dulu inputbox " size="40" value="<?php echo $_SESSION['post']['istri_dulu']?>"/>
							<span class="judul_tengah">Binti :</span>
							<input name="binti" type="text" class="inputbox " size="40" value="<?php echo $_SESSION['post']['binti']?>"/>
						</td>
					</tr>
					<tr>
						<th class="indent">Tempat Tanggal Lahir</th>
						<td>
							<input name="tempatlahir_istri_dulu" type="text" class="istri_dulu inputbox " size="30" value="<?php echo $_SESSION['post']['tempatlahir_istri_dulu']?>"/>
							<input name="tanggallahir_istri_dulu" type="text" class="istri_dulu inputbox datepicker" size="20" value="<?php echo $_SESSION['post']['tanggallahir_istri_dulu']?>"/>
						</td>
					</tr>
					<tr>
						<th class="indent">Warganegara</th>
						<td colspan="5">
					    <select name="wn_istri_dulu" class="istri_dulu">
					      <option value="">Pilih warganegara</option>
					      <?php foreach($warganegara as $data){?>
					        <option value="<?php echo $data['nama']?>" <?php if($data['nama']==$_SESSION['post']['wn_istri_dulu']) echo 'selected'?>> <?php echo strtoupper($data['nama'])?></option>
					      <?php }?>
						  </select>
							<span class="judul_tengah">Agama</span>
					    <select name="agama_istri_dulu" class="istri_dulu">
					      <option value="">Pilih Agama</option>
					      <?php foreach($agama as $data){?>
					        <option value="<?php echo $data['nama']?>" <?php if($data['nama']==$_SESSION['post']['agama_istri_dulu']) echo 'selected'?>> <?php echo ucwords($data['nama'])?></option>
					      <?php }?>
					    </select>
							<span class="judul_tengah">Pekerjaan</span>
					    <select name="pek_istri_dulu" class="istri_dulu">
					      <option value="">Pilih Pekerjaan</option>
					      <?php  foreach($pekerjaan as $data){?>
					        <option value="<?php echo $data['nama']?>" <?php if($data['nama']==$_SESSION['post']['pek_istri_dulu']) echo 'selected'?>> <?php echo ucwords($data['nama'])?></option>
					      <?php }?>
					    </select>
						</td>
					</tr>
					<tr>
						<th class="indent">Tempat Tinggal</th>
						<td><input name="alamat_istri_dulu" type="text" class="istri_dulu inputbox " size="80" value="<?php echo $_SESSION['post']['alamat_istri_dulu']?>"/></td>
					</tr>
					<tr>
						<th class="indent">Keterangan <?php echo ucwords($jenis_pasangan)?> Dulu</th>
						<td><input name="ket_istri_dulu" type="text" class="istri_dulu inputbox " size="80" value="<?php echo $_SESSION['post']['ket_istri_dulu']?>"/></td>
					</tr>
				<?php endif; ?>

				<!-- CALON PASANGAN WANITA -->
				<?php $jenis_pasangan = "Suami"; ?>
				<tr>
					<th class="grey">B. CALON PASANGAN WANITA</th>
				  <td class="grey">
				    <div class="uiradio">
				      <input type="radio" id="calon_wanita_1" name="calon_wanita" value="1" <?php if(!empty($wanita)){echo 'checked';}?> onchange="calon_wanita_asal(this.value);">
				      <label for="calon_wanita_1">Warga Desa</label>
				      <input type="radio" id="calon_wanita_2" name="calon_wanita" value="2" <?php if(empty($wanita)){echo 'checked';}?> onchange="calon_wanita_asal(this.value);"">
				      <label id="label_calon_wanita_2" for="calon_wanita_2">Warga Luar Desa</label>
				    </div>
				  </td>
				</tr>

				<tr class="wanita_desa" <?php if (empty($wanita)) echo 'style="display: none;"'; ?>>
					<th colspan="2">B.1 DATA CALON PASANGAN WANITA WARGA DESA</th>
				</tr>
				<tr class="wanita_desa" <?php if (empty($wanita)) echo 'style="display: none;"'; ?>>
					<th class="indent">NIK / Nama</th>
					<td>
						<div id="id_wanita" name="id_wanita"></div>
						<?php if($wanita){ //bagian info setelah terpilih
								$individu = $wanita;
							  include("donjo-app/views/surat/form/konfirmasi_pemohon.php");
						}?>
					</td>
				</tr>
				<?php if($wanita) : ?>
					<tr>
						<th class="indent">Jika wanita, terangkan perawan atau janda</th>
						<td>
					    <select name="status_kawin_wanita">
					      <option value="">Pilih Status Kawin</option>
					      <?php  foreach($kode['status_kawin_wanita'] as $data){?>
					        <option value="<?php echo $data?>" <?php if($wanita['status_kawin_wanita']==$data) echo 'selected';?>><?php echo ucwords($data)?></option>
					      <?php }?>
					    </select>
							<span>(Status kawin: <?php echo $wanita['status_kawin']?>)</span>
						</td>
					</tr>
				<?php endif; ?>

				<?php if (empty($wanita)) : ?>
					<tr class="wanita_luar_desa">
						<th colspan="2">B.1 DATA CALON PASANGAN WANITA LUAR DESA</th>
					</tr>
					<tr class="wanita_luar_desa">
						<th class="indent">Nama Lengkap</th>
						<td>
							<input name="nama_wanita" type="text" class="inputbox" size="30" value="<?php echo $_SESSION['post']['nama_wanita']?>"/>
						</td>
					</tr>
					<tr class="wanita_luar_desa">
						<th class="indent">Tempat Tanggal Lahir</th>
						<td>
							<input name="tempatlahir_wanita" type="text" class="inputbox" size="30" value="<?php echo $_SESSION['post']['tempatlahir_wanita']?>"/>
							<input name="tanggallahir_wanita" type="text" class="inputbox datepicker" size="20" value="<?php echo $_SESSION['post']['tanggallahir_wanita']?>"/>
						</td>
					</tr>
					<tr class="wanita_luar_desa">
						<th class="indent">Warganegara</th>
						<td colspan="5">
					    <select name="wn_wanita">
					      <option value="">Pilih warganegara</option>
					      <?php foreach($warganegara as $data){?>
					        <option value="<?php echo $data['nama']?>" <?php if($data['nama']==$_SESSION['post']['wn_wanita']) echo 'selected'?>> <?php echo strtoupper($data['nama'])?></option>
					      <?php }?>
						  </select>
							<span class="judul_tengah">Agama</span>
					    <select name="agama_wanita">
					      <option value="">Pilih Agama</option>
					      <?php foreach($agama as $data){?>
					        <option value="<?php echo $data['nama']?>" <?php if($data['nama']==$_SESSION['post']['agama_wanita']) echo 'selected'?>> <?php echo ucwords($data['nama'])?></option>
					      <?php }?>
					    </select>
							<span class="judul_tengah">Pekerjaan</span>
					    <select name="pekerjaan_wanita">
					      <option value="">Pilih Pekerjaan</option>
					      <?php  foreach($pekerjaan as $data){?>
					        <option value="<?php echo $data['nama']?>" <?php if($data['nama']==$_SESSION['post']['pekerjaan_wanita']) echo 'selected'?>> <?php echo ucwords($data['nama'])?></option>
					      <?php }?>
					    </select>
						</td>
					</tr>
					<tr class="wanita_luar_desa">
						<th class="indent">Tempat Tinggal</th>
						<td>
							<input name="alamat_wanita" type="text" class="inputbox" size="40" value="<?php echo $_SESSION['post']['alamat_wanita']?>"/>
						</td>
					</tr>
					<tr class="wanita_luar_desa">
						<th class="indent">Jika wanita, terangkan perawan atau janda</th>
						<td>
					    <select name="status_kawin_wanita">
					      <option value="">Pilih Status Kawin</option>
					      <?php  foreach($kode['status_kawin_wanita'] as $data){?>
					        <option value="<?php echo $data?>" <?php if($data['nama']==$_SESSION['post']['status_kawin_wanita']) echo 'selected'?>><?php echo ucwords($data)?></option>
					      <?php }?>
					    </select>
						</td>
					</tr>
				<?php endif; ?>

				<?php if ($ayah_wanita) : ?>
					<tr>
						<th colspan="2">B.2 DATA AYAH PASANGAN WANITA</th>
					</tr>
					<tr>
						<th class="indent">Nama</th>
						<td><?php echo $ayah_wanita['nama']?></td>
					</tr>
					<tr>
						<th class="indent">Tempat Tanggal Lahir</th>
						<td>
							<?php echo $ayah_wanita['tempatlahir']." / ".tgl_indo_out($ayah_wanita['tanggallahir'])?>
						</td>
					</tr>
					<tr>
						<th class="indent">Warganegara</th>
						<td>
							<?php echo $ayah_wanita['wn']?>
							<span class="judul_tengah">Agama : </span>
							<?php echo $ayah_wanita['agama']?>
							<span class="judul_tengah">Pekerjaan : </span>
							<?php echo $ayah_wanita['pek']?>
						</td>
					</tr>
					<tr>
						<th class="indent">Tempat Tinggal</th>
						<td><?php echo $ayah_wanita['alamat_wilayah']?></td>
					</tr>
				<?php else: ?>
					<tr>
						<th colspan="2">B.2 DATA AYAH PASANGAN WANITA (Isi jika ayah bukan warga <?php echo strtolower($this->setting->sebutan_desa)?> ini)</th>
					</tr>
					<tr>
						<th class="indent">Nama</th>
						<td><input name="nama_ayah_wanita" type="text" class="ayah_wanita inputbox " size="30" value="<?php echo $_SESSION['post']['nama_ayah_wanita']?>" /></td>
					</tr>
					<tr>
						<th class="indent">Tempat Tanggal Lahir</th>
						<td>
							<input name="tempatlahir_ayah_wanita" type="text" class="ayah_wanita inputbox " size="30" value="<?php echo $_SESSION['post']['tempatlahir_ayah_wanita']?>"/>
							<input name="tanggallahir_ayah_wanita" type="text" class="ayah_wanita inputbox  datepicker" size="20" value="<?php echo $_SESSION['post']['tanggallahir_ayah_wanita']?>"/>
						</td>
					</tr>
					<tr>
						<th class="indent">Warganegara</th>
						<td colspan="5">
					    <select name="wn_ayah_wanita" class="ayah_wanita">
					      <option value="">Pilih warganegara</option>
					      <?php foreach($warganegara as $data){?>
					        <option value="<?php echo $data['nama']?>" <?php if($data['nama']==$_SESSION['post']['wn_ayah_wanita']) echo 'selected'?>> <?php echo strtoupper($data['nama'])?></option>
					      <?php }?>
						  </select>
							<span class="judul_tengah">Agama</span>
					    <select name="agama_ayah_wanita" class="ayah_wanita">
					      <option value="">Pilih Agama</option>
					      <?php foreach($agama as $data){?>
					        <option value="<?php echo $data['nama']?>" <?php if($data['nama']==$_SESSION['post']['agama_ayah_wanita']) echo 'selected'?>> <?php echo ucwords($data['nama'])?></option>
					      <?php }?>
					    </select>
							<span class="judul_tengah">Pekerjaan</span>
					    <select name="pekerjaan_ayah_wanita" class="ayah_wanita">
					      <option value="">Pilih Pekerjaan</option>
					      <?php  foreach($pekerjaan as $data){?>
					        <option value="<?php echo $data['nama']?>" <?php if($data['nama']==$_SESSION['post']['pekerjaan_ayah_wanita']) echo 'selected'?>> <?php echo ucwords($data['nama'])?></option>
					      <?php }?>
					    </select>
						</td>
					</tr>
					<tr>
						<th class="indent">Tempat Tinggal</th>
						<td><input name="alamat_ayah_wanita" type="text" class="ayah_wanita inputbox " size="80" value="<?php echo $_SESSION['post']['alamat_ayah_wanita']?>"/></td>
					</tr>
				<?php endif; ?>

				<?php if ($ibu_wanita) : ?>
					<tr>
						<th colspan="2">B.3 DATA IBU PASANGAN WANITA</th>
					</tr>
					<tr>
						<th class="indent">Nama</th>
						<td><?php echo $ibu_wanita['nama']?></td>
					</tr>
					<tr>
						<th class="indent">Tempat Tanggal Lahir</th>
						<td>
							<?php echo $ibu_wanita['tempatlahir']." / ".tgl_indo_out($ibu_wanita['tanggallahir'])?>
						</td>
					</tr>
					<tr>
						<th class="indent">Warganegara</th>
						<td>
							<?php echo $ibu_wanita['wn']?>
							<span class="judul_tengah">Agama : </span>
							<?php echo $ibu_wanita['agama']?>
							<span class="judul_tengah">Pekerjaan : </span>
							<?php echo $ibu_wanita['pek']?>
						</td>
					</tr>
					<tr>
						<th class="indent">Tempat Tinggal</th>
						<td><?php echo $ibu_wanita['alamat_wilayah']?></td>
					</tr>
				<?php else: ?>
					<tr>
						<th colspan="2">B.3 DATA IBU PASANGAN WANITA (Isi jika ibu bukan warga <?php echo strtolower($this->setting->sebutan_desa)?> ini)</th>
					</tr>
					<tr>
						<th class="indent">Nama</th>
						<td><input name="nama_ibu_wanita" type="text" class="ibu_wanita inputbox " size="30" value="<?php echo $_SESSION['post']['nama_ibu_wanita']?>"/></td>
					</tr>
					<tr>
						<th class="indent">Tempat Tanggal Lahir</th>
						<td>
							<input name="tempatlahir_ibu_wanita" type="text" class="ibu_wanita inputbox " size="30" value="<?php echo $_SESSION['post']['tempatlahir_ibu_wanita']?>"/>
							<input name="tanggallahir_ibu_wanita" type="text" class="ibu_wanita inputbox  datepicker" size="20" value="<?php echo $_SESSION['post']['tanggallahir_ibu_wanita']?>"/>
						</td>
					</tr>
					<tr>
						<th class="indent">Warganegara</th>
						<td colspan="5">
					    <select name="wn_ibu_wanita" class="ibu_wanita">
					      <option value="">Pilih warganegara</option>
					      <?php foreach($warganegara as $data){?>
					        <option value="<?php echo $data['nama']?>" <?php if($data['nama']==$_SESSION['post']['wn_ibu_wanita']) echo 'selected'?>> <?php echo strtoupper($data['nama'])?></option>
					      <?php }?>
						  </select>
							<span class="judul_tengah">Agama</span>
					    <select name="agama_ibu_wanita" class="ibu_wanita">
					      <option value="">Pilih Agama</option>
					      <?php foreach($agama as $data){?>
					        <option value="<?php echo $data['nama']?>" <?php if($data['nama']==$_SESSION['post']['agama_ibu_wanita']) echo 'selected'?>> <?php echo ucwords($data['nama'])?></option>
					      <?php }?>
					    </select>
							<span class="judul_tengah">Pekerjaan</span>
					    <select name="pekerjaan_ibu_wanita" class="ibu_wanita">
					      <option value="">Pilih Pekerjaan</option>
					      <?php  foreach($pekerjaan as $data){?>
					        <option value="<?php echo $data['nama']?>" <?php if($data['nama']==$_SESSION['post']['pekerjaan_ibu_wanita']) echo 'selected'?>> <?php echo ucwords($data['nama'])?></option>
					      <?php }?>
					    </select>
						</td>
					</tr>
					<tr>
						<th class="indent">Tempat Tinggal</th>
						<td><input name="alamat_ibu_wanita" type="text" class="ibu_wanita inputbox " size="80" value="<?php echo $_SESSION['post']['alamat_ibu_wanita']?>"/></td>
					</tr>
				<?php endif; ?>

				<?php if(empty($wanita) OR $wanita['status_kawin']=="CERAI MATI") : ?>
					<tr>
						<th colspan="2">B.4 DATA SUAMI TERDAHULU </th>
					</tr>
					<tr>
						<th class="indent">Nama <?php echo ucwords($jenis_pasangan)?> Terdahulu</th>
						<td>
							<input name="suami_dulu" type="text" class="suami_dulu inputbox " size="40" value="<?php echo $_SESSION['post']['suami_dulu']?>"/>
							<span class="judul_tengah">Bin :</span>
							<input name="bin_suami_dulu" type="text" class="suami_dulu inputbox " size="40" value="<?php echo $_SESSION['post']['bin_suami_dulu']?>"/>
						</td>
					</tr>
					<tr>
						<th class="indent">Tempat Tanggal Lahir</th>
						<td>
							<input name="tempatlahir_suami_dulu" type="text" class="suami_dulu inputbox " size="30" value="<?php echo $_SESSION['post']['tempatlahir_suami_dulu']?>"/>
							<input name="tanggallahir_suami_dulu" type="text" class="suami_dulu inputbox datepicker" size="20" value="<?php echo $_SESSION['post']['tanggallahir_suami_dulu']?>"/>
						</td>
					</tr>
					<tr>
						<th class="indent">Warganegara</th>
						<td colspan="5">
					    <select name="wn_suami_dulu" class="suami_dulu">
					      <option value="">Pilih warganegara</option>
					      <?php foreach($warganegara as $data){?>
					        <option value="<?php echo $data['nama']?>" <?php if($data['nama']==$_SESSION['post']['wn_suami_dulu']) echo 'selected'?>> <?php echo strtoupper($data['nama'])?></option>
					      <?php }?>
						  </select>
							<span class="judul_tengah">Agama</span>
					    <select name="agama_suami_dulu" class="suami_dulu">
					      <option value="">Pilih Agama</option>
					      <?php foreach($agama as $data){?>
					        <option value="<?php echo $data['nama']?>" <?php if($data['nama']==$_SESSION['post']['agama_suami_dulu']) echo 'selected'?>> <?php echo ucwords($data['nama'])?></option>
					      <?php }?>
					    </select>
							<span class="judul_tengah">Pekerjaan</span>
					    <select name="pek_suami_dulu" class="suami_dulu">
					      <option value="">Pilih Pekerjaan</option>
					      <?php  foreach($pekerjaan as $data){?>
					        <option value="<?php echo $data['nama']?>" <?php if($data['nama']==$_SESSION['post']['pek_suami_dulu']) echo 'selected'?>> <?php echo ucwords($data['nama'])?></option>
					      <?php }?>
					    </select>
						</td>
					</tr>
					<tr>
						<th class="indent">Tempat Tinggal</th>
						<td><input name="alamat_suami_dulu" type="text" class="suami_dulu inputbox " size="80" value="<?php echo $_SESSION['post']['alamat_suami_dulu']?>"/></td>
					</tr>
					<tr>
						<th class="indent">Keterangan <?php echo ucwords($jenis_pasangan)?> Dulu</th>
						<td><input name="ket_suami_dulu" type="text" class="suami_dulu inputbox " size="80" value="<?php echo $_SESSION['post']['ket_suami_dulu']?>"/></td>
					</tr>
				<?php endif; ?>

				<tr>
					<th colspan="2">B.5 DATA WALI NIKAH</th>
				</tr>
				<tr>
					<th class="indent">Nama Wali Nikah</th>
					<td>
						<input id="nama_wali" name="nama_wali" type="text" class="wali inputbox " size="40" value="<?php echo ($ayah_wanita AND $calon_wanita_berbeda) ? $ayah_wanita['nama'] : $_SESSION['post']['nama_wali']?>"/>
						<span class="judul_tengah">Bin :</span>
						<input id="bin_wali" name="bin_wali" type="text" class="wali inputbox " size="40" value="<?php echo ($ayah_wanita AND $calon_wanita_berbeda) ? $ayah_wanita['nama_ayah'] : $_SESSION['post']['bin_wali']?>"/>
					</td>
				</tr>
				<tr>
					<th class="indent">Tempat Tanggal Lahir</th>
					<td>
						<input id="tempatlahir_wali" name="tempatlahir_wali" type="text" class="wali inputbox " size="30" value="<?php echo ($ayah_wanita AND $calon_wanita_berbeda) ? $ayah_wanita['tempatlahir'] : $_SESSION['post']['tempatlahir_wali']?>"/>
						<input id="tanggallahir_wali" name="tanggallahir_wali" type="text" class="wali inputbox datepicker" size="20" value="<?php echo ($ayah_wanita AND $calon_wanita_berbeda) ? $ayah_wanita['tanggallahir'] : $_SESSION['post']['tanggallahir_wali']?>"/>
					</td>
				</tr>
				<tr>
					<th class="indent">Warganegara</th>
					<td colspan="5">
				    <select id="wn_wali" name="wn_wali" class="wali">
				      <option value="">Pilih warganegara</option>
				      <?php foreach($warganegara as $data){?>
				        <option value="<?php echo $data['nama']?>" <?php if($data['nama']==(($ayah_wanita AND $calon_wanita_berbeda) ? $ayah_wanita['wn'] : $_SESSION['post']['wn_wali'])) echo 'selected'?>> <?php echo strtoupper($data['nama'])?></option>
				      <?php }?>
					  </select>
						<span class="judul_tengah">Agama</span>
				    <select id="agama_wali" name="agama_wali" class="wali">
				      <option value="">Pilih Agama</option>
				      <?php foreach($agama as $data){?>
				        <option value="<?php echo $data['nama']?>" <?php if($data['nama']==(($ayah_wanita AND $calon_wanita_berbeda) ? $ayah_wanita['agama'] : $_SESSION['post']['agama_wali'])) echo 'selected'?>> <?php echo ucwords($data['nama'])?></option>
				      <?php }?>
				    </select>
						<span class="judul_tengah">Pekerjaan</span>
				    <select id="pek_wali" name="pek_wali" class="wali">
				      <option value="">Pilih Pekerjaan</option>
				      <?php  foreach($pekerjaan as $data){?>
				        <option value="<?php echo $data['nama']?>" <?php if($data['nama']==(($ayah_wanita AND $calon_wanita_berbeda) ? $ayah_wanita['pek'] : $_SESSION['post']['pek_wali'])) echo 'selected'?>> <?php echo ucwords($data['nama'])?></option>
				      <?php }?>
				    </select>
					</td>
				</tr>
				<tr>
					<th class="indent">Tempat Tinggal</th>
					<td><input id="alamat_wali" name="alamat_wali" type="text" class="wali inputbox " size="80" value="<?php echo ($ayah_wanita AND $calon_wanita_berbeda) ? $ayah_wanita['alamat_wilayah'] : $_SESSION['post']['alamat_wali']?>"/></td>
				</tr>
				<tr>
					<th class="indent">Hubungan Dengan Wali</th>
					<td><input id="hub_wali" name="hub_wali" type="text" class="wali inputbox " size="80" value="<?php echo ($ayah_wanita AND $calon_wanita_berbeda) ? 'Ayah' : $_SESSION['post']['hub_wali']?>"/></td>
				</tr>

				<tr>
					<th colspan="2" class="grey">C. DATA PERNIKAHAN</th>
				</tr>
				<tr>
					<th class="indent">Hari, Tanggal, Jam</th>
						<td><input name="hari_nikah" type="text" class="inputbox required" size="15" value="<?php echo $_SESSION['post']['hari_nikah']?>"/>,
						<input name="tanggal_nikah" type="text" class="inputbox required datepicker" size="15" value="<?php echo $_SESSION['post']['tanggal_nikah']?>"/>,
						<input name="jam_nikah" type="text" class="inputbox required" size="10" value="<?php echo $_SESSION['post']['jam_nikah']?>"/></td>
				</tr>
				<tr>
					<th class="indent">Mas Kawin</th>
					<td><input name="mas_kawin" type="text" class="inputbox required" size="40" value="<?php echo $_SESSION['post']['mas_kawin']?>"/></td>
				</tr>
				<tr>
					<th class="indent">Tunai / Hutang</th>
					<td><input name="tunai" type="text" class="inputbox required" size="10" value="tunai" value="<?php echo $_SESSION['post']['tunai']?>"/></td>
				</tr>
				<tr>
					<th class="indent">Tempat</th>
					<td><input name="tempat_nikah" type="text" class="inputbox required" size="40" value="<?php echo $_SESSION['post']['tempat_nikah']?>"/></td>
				</tr>

				<!-- PERANGKAT DESA -->
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
						<button type="button" onclick="submit_form_doc();" class="uibutton confirm"><span class="fa fa-file-text">&nbsp;</span>Export Doc</button>
					<?php } ?>
        </div>
    </div>
	</div>

</div>
</td></tr></table>
</div>
