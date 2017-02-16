<div id="pageC">
	<table class="inner">
<tr style="vertical-align:top">
		<td style="background:#fff;padding:0px;"> 
<div class="content-header">
</div>
<div id="contentpane"> 
	<form id="mainform" name="mainform" action="" method="post">
 <div class="ui-layout-north panel">
 <h3>Data Anggota - Kelompok <?php echo $kelompok['nama'];?></h3>
 <div class="left">
 <div class="uibutton-group">
 <a href="<?php echo site_url('kelompok/clear')?>" class="uibutton tipsy south" title="Kelompok" ><span class="icon-list icon-large">&nbsp;</span>Kelompok</a>
 <a href="<?php echo site_url("kelompok/form_anggota/$kel")?>" class="uibutton tipsy south" title="Tambah Data" ><span class="icon-plus-sign icon-large">&nbsp;</span>Tambah Anggota Baru</a>
 <a href="<?php echo site_url("kelompok/cetak_a/$kel")?>" class="uibutton" title="Cetak Data" target="_blank"><span class="icon-print icon-large">&nbsp;</span>Cetak</a>
 <a href="<?php echo site_url("kelompok/excel_a/$kel")?>" class="uibutton tipsy south" title="Data Excel" target="_blank"><span class="icon-file-text icon-large">&nbsp;</span>Excel</a>
 </div>
 </div>
 </div>
 <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
 <div class="table-panel top">
 <div class="left">
 </div>
 <div class="right">
 </div>
 </div>
 <table class="list">
		<thead>
 <tr>
 <th width="10">No</th>
 <th width="50">Aksi</th>
 <th width="100">NIK</th>
 <th>Nama</th>
 <th>Alamat</th>
 <th>Umur (Tahun)</th>
 <th>Jenis Kelamin</th>
			</tr>
		</thead>
		<tbody>
 <?php foreach($main as $data): ?>
		<tr>
 <td align="center" width="2"><?php echo $data['no']?></td>
 <td><div class="uibutton-group"><a href="<?php echo site_url("kelompok/delete_a/$kel/$data[id]")?>" class="uibutton tipsy south" title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span class="icon-trash icon-large"></span> Hapus</a>
			</div>
 </td>
 <td><?php echo $data['nik']?></td>
 <td><?php echo $data['nama']?></td>
 <td><?php echo $data['alamat']?></td>
 <td><?php echo $data['umur']?></td>
 <td><?php if($data['sex']==1) echo "Laki-laki"; else echo "Perempuan";?></td>
		 </tr>
 <?php endforeach; ?>
		</tbody>
 </table>
 </div>
	</form>
 <div class="ui-layout-south panel bottom">
 <div class="left"> 
 </div>
 <div class="right">
 </div>
</div>
</td></tr></table>
</div>