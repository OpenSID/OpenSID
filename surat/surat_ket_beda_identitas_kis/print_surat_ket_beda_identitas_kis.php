<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');?>

<?php $this->load->view('print/headjs.php');?>

<body>
<div id="content" class="container_12 clearfix">
<div id="content-main" class="grid_7">

<link href="<?php echo base_url()?>assets/css/surat.css" rel="stylesheet" type="text/css" />
<div>

<table width="100%">
	<tr> <img src="<?php echo LogoDesa($desa['logo']);?>" alt="" class="logo"></tr>
	<div class="header">
		<h4 class="kop">PEMERINTAH <?php echo strtoupper($this->setting->sebutan_kabupaten)?> <?php echo strtoupper(unpenetration($desa['nama_kabupaten']))?> </h4>
		<h4 class="kop">KECAMATAN <?php echo strtoupper(unpenetration($desa['nama_kecamatan']))?> </h4>
		<h4 class="kop"><?php echo strtoupper($this->setting->sebutan_desa)?> <?php echo strtoupper(unpenetration($desa['nama_desa']))?></h4>
		<h5 class="kop2"><?php echo (unpenetration($desa['alamat_kantor']))?> </h5>
		<div style="text-align: center;">
			<hr />
		</div>
	</div>
	<div align="center"><u><h4 class="kop">SURAT KETERANGAN BEDA IDENTITAS</h4></u></div>
	<div align="center"><h4 class="kop3">Nomor : <?php echo $input['nomor']?></h4></div>
</table>
<div class="clear"></div>

<link href="<?php echo base_url()?>assets/css/report.css" rel="stylesheet" type="text/css">
<table width="100%">
	<tr>
		<td class="indentasi">
			Yang bertanda tangan dibawah ini <?php  echo unpenetration($input['jabatan'])?> <?php  echo unpenetration($desa['nama_desa'])?>, Kecamatan <?php  echo unpenetration($desa['nama_kecamatan'])?>,
			<?php echo ucwords($this->setting->sebutan_kabupaten)?> <?php echo unpenetration($desa['nama_kabupaten'])?>, Provinsi <?php  echo unpenetration($desa['nama_propinsi'])?> menerangkan dengan sebenarnya bahwa:
		</td>
	</tr>
</table>

<table class="border thick">
	<thead>
		<tr>
			<th align="center">No</th>
			<th align="center">Nama</th>
			<th align="center" >NIK</th>
			<th align="center">Jenis Kelamin</th>
			<th align="center">Tempat Tanggal Lahir</th>
			<th align="center" >Pekerjaan</th>
			<th align="center" >Alamat</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$id_cb = $this->input->post('id_cb');
			$pilih="";
			foreach($id_cb as $nik){
				$pilih .= $nik.',';
			}
			$pilih = rtrim($pilih,',');
			$anggota = $this->keluarga_model->list_anggota($pribadi['id_kk'],array('pilih'=>$pilih));
			foreach($anggota AS $key => $data1){ ?>
			<tr>
	      <td align="center"width="4"> <?php echo $key+1?></td>
				<td> <?php echo $data1['nama']?></td>
				<td align="center"><?php echo $data1['nik']?></td>
				<td align="center"><?php echo $data1['sex']?></td>
				<td align="left"> <?php echo $data1['tempatlahir']?>, <?php echo tgl_indo($data1['tanggallahir'])?></td>
				<td align="left"><?php echo $data1['pekerjaan']?></td>
				<td align="center"><?php echo $data['alamat']?></td>
			</tr>
		<?php }?>
	</tbody>
</table>

<table width="100%">
	<tr>
		<td class="indentasi">Nama tersebut di atas merupakan identitas yang tertera pada KTP dan Kartu Keluarga (KK) sedangkan pada Kartu Indonesia Sehat (KIS) tertulis : </td>
	</tr>
</table>

<table class="border thick">
	<thead>
		<tr class="border thick">
			<th align="center" width='10'>No</th>
			<th align="center" width='100'>No. Kartu</th>
			<th align="center" width='150'>Nama di Kartu</th>
			<th align="center" width='90'>NIK</th>
			<th align="center" width='150'>Alamat di Kartu</th>
			<th align="center" width='80'>Tanggal Lahir</th>
			<th align="center" width='80'>Faskes Tingkat I</th>
		</tr>
	</thead>
	<tbody>
		<?php for($i=1; $i<MAX_ANGGOTA+1; $i++): ?>
			<?php if(!empty($input["nomor$i"])): ?>
				<tr>
					<td align="center"><?php echo $i?></td>
					<td align="center"><?php echo $input["kartu$i"]?></td>
					<td align="left"><?php echo $input["nama$i"]?></td>
					<td align="center"><?php echo $input["nik$i"]?></td>
					<td align="left"><?php echo $input["alamat$i"]?></td>
					<td align="left"><?php echo $input["tanggallahir$i"]?></td>
					<td align="center"><?php echo $input["faskes$i"]?></td>
				</tr>
			<?php endif; ?>
		<?php endfor; ?>
	</tbody>
</table>

<table width="100%">
	<tr>
		<td class="indentasi" style="padding-bottom: 1em;">Menurut pengamatan dan pengetahuan kami hingga saat dikeluarkannya surat keterangan ini bahwa yang namanya di atas merupakan orang yang satu / sama.</td>
	</tr>
	<tr>
		<td class="indentasi" style="padding-bottom: 1em;">Surat Keterangan ini dibuat untuk keperluan : <b><?php echo $input['keperluan']?>.</b></td>
	</tr>
	<tr>
		<td class="indentasi" style="padding-bottom: 1em;">Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</td>
	</tr>
</table>

<table width="100%">
	<tr>
		<td width="55%"></td>
		<td align="center"><?php echo unpenetration($desa['nama_desa'])?>, <?php echo $tanggal_sekarang?></td>
	</tr>
	<tr>
		<td width="55%"></td>
		<td align="center"><?php echo ($input['atas_nama'])?></td>
	</tr>
	<tr>
		<td width="55%"></td>
		<td align="center"><?php echo unpenetration($input['jabatan'])?></td>
	</tr>
	<tr>
		<td width="55%"></td>
		<td align="center" style="padding-top: 7em;"><b><u><?php echo unpenetration($input['pamong'])?> </u></td>
	</tr>
	<tr>
		<td width="55%"></td>
		<td align="center"><?php echo ($input['pamong_nip'])?></td>
	</tr>
</table>

</div></div>
</div>
</body>
</html>
