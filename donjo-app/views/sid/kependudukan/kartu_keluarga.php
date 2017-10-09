<script>
$(function(){
var nik = {};
nik.results = [
<?php foreach($penduduk as $data){?>
   {id:'<?php echo $data['id']?>',name:"<?php echo $data['nik']." - ".($data['nama'])?>",info:"<?php echo ($data['alamat'])?>"},
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

<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">

<td style="background:#fff;padding:0px;">

<div class="content-header">
<h3>Form Manajemen KK : <?php echo $kepala_kk['nama']?></h3>
</div>
<div id="contentpane">
<form id="mainform" name="mainform" action="<?php echo $form_action?>" method="post" enctype="multipart/form-data">
   <div class="ui-layout-north panel">
        <div class="left">
            <div class="uibutton-group">
<a href="<?php echo site_url("keluarga/anggota/$p/$o/$id_kk")?>" class="uibutton icon prev">Daftar Anggota</a>
<a href="<?php echo site_url("keluarga/form_a/$p/$o/$id_kk")?>" class="uibutton icon next">Tambah Anggota</a>


</div>
        </div>
</div>

<div class="ui-layout-center" id="maincontent" style="padding: 5px;">






<table width="100%" cellpadding="3" cellspacing="4">
<div align="center">
<h3> KARTU KELUARGA </h3>
<h4> SALINAN </h4>
<h4>No. <?php echo unpenetration($kepala_kk['no_kk'])?> </h4>
</div>
<tr>
<td width="100">Alamat</td>
<td width="200">: <?php if(isset($kepala_kk['alamat_plus_dusun'])) echo strtoupper($kepala_kk['alamat_plus_dusun'])  ?></td>
<td width="120">Kabupaten/Kota</td>
<td width="150">: <?php echo strtoupper(unpenetration($desa['nama_kabupaten'])) ?></td>
</tr>
<tr>
<td>RT/RW</td>
<td>: <?php echo unpenetration($kepala_kk['rt'])  ?> / <?php echo unpenetration($kepala_kk['rw'])  ?> </td>
<td>Kode Pos</td>
<td>: <?php echo $desa['kode_pos'] ?></td>
</tr>
<tr>
<td>Kelurahan/Desa</td>
<td>: <?php echo strtoupper(unpenetration($desa['nama_desa'])) ?></td>
<td>Propinsi</td>
<td>: <?php echo strtoupper(unpenetration($desa['nama_propinsi'])) ?></td>
</tr>
<tr>
<td><?php echo ucwords($this->setting->sebutan_kecamatan)?></td>
<td>: <?php echo strtoupper(unpenetration($desa['nama_kecamatan'])) ?></td>
<td>Jumlah Anggota Keluarga</td>
<td>: <?php echo count($main)?></td>
</tr>
</table>
<p style="font-family:verdana,arial,sans-serif;font-size:10px;"></p>



<table class="list"  style="width:100%">
<thead>
<tr>
<th>No</th>
<th align="left" width='180'>Nama</th>
<th align="left">NIK</th>
<th align="left" width='100'>Jenis Kelamin</th>
<th align="left" width='100'>Tempat Lahir</th>
<th align="left" width='80'>Tanggal Lahir</th>
<th align="left" width='100'>Agama</th>
<th align="left" width='100'>Pendidikan</th>
<th align="left" width='100'>Pekerjaan</th>
</tr>
</thead>
<tbody>


<?php  foreach($main as $key => $data): ?>

<tr>
<td align="center" width="2"><?php echo $key+1?></td>
<td><?php echo strtoupper(unpenetration($data['nama']))?></td>
<td><?php echo $data['nik']?></td>
<td><?php echo $data['sex']?></td>
<td><?php echo $data['tempatlahir']?></td>
<td><?php echo tgl_indo($data['tanggallahir'])?></td>
<td><?php echo $data['agama']?></td>
<td><?php echo $data['pendidikan']?></td>
<td><?php echo $data['pekerjaan']?></td>
</tr>
<?php  endforeach; ?>
</tbody>
</table>


<table class="list"  style="width:100%">
<thead>
<tr>
<th>No</th>
<th align="left" width='100'>Status Perkawinan</th>
<th align="left" width='130'>Status Hubungan dalam Keluarga</th>
<th align="left" width='100'>Kewarganegaraan</th>
<th align="left" width='100'>No. Paspor</th>
<th align="left" width='100'>No. KITAS / KITAP</th>
<th align="left" width='100'>Nama Ayah</th>
<th align="left" width='100'>Nama Ibu</th>
<th align="left" width='100'>Golongan darah</th>

</tr>
</thead>


<tbody>
<?php  foreach($main as $key => $data): ?>
<tr>
<td align="center" width="2"><?php echo $key+1?></td>
<td><?php echo $data['status_kawin']?></td>
<td><?php echo $data['hubungan']?></td>
<td><?php echo $data['warganegara']?></td>
<td><?php echo $data['dokumen_pasport']?></td>
<td><?php echo $data['dokumen_kitas']?></td>
<td><?php echo strtoupper($data['nama_ayah'])?></td>
<td><?php echo strtoupper($data['nama_ibu'])?></td>
<td><?php echo $data['golongan_darah']?></td>
</tr>
<?php  endforeach; ?>
</tbody>
</table>



<table width="100%" cellpadding="3" cellspacing="4">

<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>

<tr>
<td width="100"></td>
<td width="400"></td>
<td align="center" width="150"><?php echo unpenetration($desa['nama_desa']) ?>, <?php echo tgl_indo(date("Y m d"))?></td>
</tr>
<tr>
	<td width="25%" align="center">KEPALA KELUARGA</td>
	<td width="50%"></td>
	<td align="center" width="150">KEPALA <?php echo strtoupper($this->setting->sebutan_desa)?> <?php echo strtoupper($desa['nama_desa']) ?></td>
	</tr>
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
<tr>
	<td width="25%" align="center"><?php echo strtoupper($kepala_kk['nama'])?></td>
	<td width="50%"></td>
	<td width="25%" align="center" width="150"><?php echo strtoupper($desa['nama_kepala_desa']) ?></td>
	</tr>

<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
</table>

<p style="font-family:verdana,arial,sans-serif;font-size:10px;"></p>


</div>
<div class="ui-layout-south panel bottom">
<div class="left">
<a href="<?php echo site_url()?>keluarga" class="uibutton icon prev">Kembali</a>
</div>
<div class="right">
<div class="uibutton-group">

<a href="<?php echo site_url("keluarga/cetak_kk/$id_kk")?>" target="_blank" class="uibutton special"><span class="fa fa-print"></span> Cetak</a>
<a href="<?php echo site_url("keluarga/doc_kk/$id_kk")?>" target="_blank" class="uibutton confirm"><span class="fa fa-file-text"></span> Export</a>
</div>
</div>
</div>
</form>
</div>
</td></tr>
</table>
</div>
