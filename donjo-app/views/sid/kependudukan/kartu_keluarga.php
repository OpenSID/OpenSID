<script>
$(function(){
var nik = {};
nik.results = [
<?foreach($penduduk as $data){?>
   {id:'<?=$data['id']?>',name:"<?=$data['nik']." - ".($data['nama'])?>",info:"<?=($data['alamat'])?>"},
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

<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">

<td style="background:#fff;padding:0px;"> 

<div class="content-header">
<h3>Form Manajemen KK : <?=$kepala_kk['nama']?></h3>
</div>
<div id="contentpane">
<form id="mainform" name="mainform" action="<?=$form_action?>" method="post" enctype="multipart/form-data">
   <div class="ui-layout-north panel">
        <div class="left">
            <div class="uibutton-group">
<a href="<?=site_url("keluarga/anggota/$p/$o/$id_kk")?>" class="uibutton icon prev">Daftar Anggota</a>
<a href="<?=site_url("keluarga/form_a/$p/$o/$id_kk")?>" class="uibutton icon next">Tambah Anggota</a>


</div>
        </div>
</div>

<div class="ui-layout-center" id="maincontent" style="padding: 5px;">






<table width="100%" cellpadding="3" cellspacing="4">
<div align="center">
<h3> KARTU KELUARGA </h3>
<h4> SALINAN </h4>
<h4>No. <?=unpenetration($kepala_kk['no_kk'])?> </h4> 
</div>
<tr>
<td width="100">Alamat</td>
<td width="200">: <?=strtoupper(unpenetration(ununderscore($kepala_kk['dusun'])))  ?></td>
<td width="120">Kabupaten</td>
<td width="150">: <?=strtoupper(unpenetration($desa['nama_kabupaten'])) ?></td>
</tr>
<tr>
<td>RT/RW</td>
<td>: <?=unpenetration($kepala_kk['rt'])  ?> / <?=unpenetration($kepala_kk['rw'])  ?> </td>
<td>Kode Pos</td>
<td>: <?=$desa['kode_pos'] ?></td>
</tr>
<tr>
<td>Kelurahan/Desa</td>
<td>: <?=strtoupper(unpenetration($desa['nama_desa'])) ?></td>
<td>Propinsi</td>
<td>: <?=strtoupper(unpenetration($desa['nama_propinsi'])) ?></td>
</tr>
<tr>
<td>Kecamatan</td>
<td>: <?=strtoupper(unpenetration($desa['nama_kecamatan'])) ?></td>
<td>Jumlah Anggota Keluarga</td>
<td>: <?=count($main)?></td>
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


<? foreach($main as $data): ?>

<tr>
<td align="center" width="2"><?=$data['no']?></td>
<td><?=strtoupper(unpenetration($data['nama']))?></td>
<td><?=$data['nik']?></td>
<td><?=$data['sex']?></td>  
<td><?=$data['tempatlahir']?></td> 
<td><?=$data['tanggallahir']?></td> 
<td><?=$data['agama']?></td> 
<td><?=$data['pendidikan']?></td> 
<td><?=$data['pekerjaan']?></td>
</tr>
<? endforeach; ?>
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
<? foreach($main as $data): ?>
<tr>
<td align="center" width="2"><?=$data['no']?></td>
<td><?=$data['status_kawin']?></td>
<td><?=$data['hubungan']?></td>
<td><?=$data['warganegara']?></td> 
<td><?=$data['dokumen_pasport']?></td>
<td><?=$data['dokumen_kitas']?></td>  
<td><?=strtoupper($data['nama_ayah'])?></td> 
<td><?=strtoupper($data['nama_ibu'])?></td> 
<td><?=$data['golongan_darah']?></td>
</tr>
<? endforeach; ?>
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
<td align="center" width="150"><?=unpenetration($desa['nama_desa']) ?>, <?=tgl_indo(date("Y m d"))?></td>
</tr>


<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>

<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
</table>

<p style="font-family:verdana,arial,sans-serif;font-size:10px;"></p>


</div>
<div class="ui-layout-south panel bottom">
<div class="left"> 
<a href="<?=site_url()?>keluarga" class="uibutton icon prev">Kembali</a>
</div>
<div class="right">
<div class="uibutton-group">

<a href="<?=site_url("keluarga/cetak_kk/$id_kk")?>" target="_blank" class="uibutton special">Cetak</a>
</div>
</div>
</div> 
</form> 
</div>
</td></tr>
</table>
</div>
