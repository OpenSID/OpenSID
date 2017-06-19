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
<td class="side-menu">

<div class="lmenu">
<ul>
<li ><a href="<?php echo site_url('keluar')?>">Surat Keluar</a></li>
<li class="selected"><a href="<?php echo site_url('keluar/perorangan')?>">Rekam Surat Perorangan</a></li>
<li ><a href="<?php echo site_url('keluar/graph')?>">Grafik Surat keluar</a></li>
</ul>
</div>

</td>
<td style="background:#fff;padding:5px;">

<div class="content-header">

</div>

<div id="contentpane">
    <div class="ui-layout-north panel">
<h3>Rekam Surat Perseorangan</h3></div>
<div class="ui-layout-center" id="maincontent" style="padding: 5px;">

<table class="form">
<tr>
<th>NIK / Nama</th>
<td>

<form action="" id="main" name="main" method="POST">
<div id="nik" name="nik"></div>
</form>

</tr>

<form id="validasi" action="<?php echo $form_action?>" method="POST" target="_blank">
<input type="hidden" name="nik" value="<?php echo $individu['id']?>">
<?php if($individu){ //bagian info setelah terpilih?>
<tr>
<th>Tempat/ Tanggal Lahir (Umur)</th>

<td>
<?php echo $individu['tempatlahir']?> / <?php echo tgl_indo($individu['tanggallahir'])?> (<?php echo $individu['umur']?> Tahun)
</td>
</tr>

<tr>
<th>Alamat</th>
<td>
<?php echo unpenetration($individu['alamat']); ?>
</td>
</tr>

<tr>
<th>Pendidikan</th>
<td>
<?php echo $individu['pendidikan']?>
</td>
</tr>

<tr>
<th>Warganegara / Agama</th>
<td>
<?php echo $individu['warganegara']?> / <?php echo $individu['agama']?>
</td>
</tr>

<?php }?>
</table>

<table class="list">
<thead>
  <tr>
<th>No</th>

<?php  if($o==2): ?>
<th align="left" width='250'>Nomor Surat</th>
<?php  elseif($o==1): ?>
<th align="left" width='100'>Nomor Surat</th>
<?php  else: ?>
<th align="left" width='100'>Nomor Surat</th>
<?php  endif; ?>

<th align="left">Jenis Surat</th>


<th align="left" width='200'>Nama Staf Pemerintah <?php echo ucwords($this->setting->sebutan_desa)?></th>

<?php  if($o==6): ?>
<th align="left" width='160'>Tanggal</th>
<?php  elseif($o==5): ?>
<th align="left" width='160'>Tanggal</th>
<?php  else: ?>
<th align="left" width='160'>Tanggal</th>
<?php  endif; ?>

</tr>
</thead>

<tbody>
        <?php  foreach($main as $data): ?>
<tr>
<td align="center" width="2"><?php echo $data['no']?></td>
<td><?php echo $data['no_surat']?></td>
<td><?php echo $data['format']?></td>
<td><?php echo unpenetration($data['pamong'])?></td>
<td><?php echo tgl_indo2($data['tanggal'])?></td>
</tr>
        <?php  endforeach; ?>
</tbody>

        </table>
</div>
</form>

<div class="ui-layout-south panel bottom">
        <div class="left">
		<div class="table-info">
          <form id="paging" action="<?php echo site_url("keluar/perorangan/$nik[no]")?>" method="post">
		  <label>Tampilkan</label>
            <select name="per_page" onchange="$('#paging').submit()" >
              <option value="20" <?php  selected($per_page,20); ?> >20</option>
              <option value="50" <?php  selected($per_page,50); ?> >50</option>
              <option value="100" <?php  selected($per_page,100); ?> >100</option>
            </select>
            <label>Dari</label>
            <label><strong><?php echo $paging->num_rows?></strong></label>
            <label>Total Data</label>
          </form>
          </div>
        </div>

  <div class="right">
            <div class="uibutton-group">
            <?php  if($paging->start_link): ?>
				<a href="<?php echo site_url("keluar/perorangan/$nik[no]/$paging->start_link/$o")?>" class="uibutton"  ><span class="fa fa-fast-backward"></span> Awal</a>
			<?php  endif; ?>
			<?php  if($paging->prev): ?>
				<a href="<?php echo site_url("keluar/perorangan/$nik[no]/$paging->prev/$o")?>" class="uibutton"  ><span class="fa fa-step-backward"></span> Prev</a>
			<?php  endif; ?>
            </div>
            <div class="uibutton-group">

				<?php  for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
				<a href="<?php echo site_url("keluar/perorangan/$nik[no]/$i/$o/")?>" <?php  jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?php echo $i?></a>
				<?php  endfor; ?>
            </div>
            <div class="uibutton-group">
			<?php  if($paging->next): ?>
				<a href="<?php echo site_url("keluar/perorangan/$nik[no]/$paging->next/$o")?>" class="uibutton">Next <span class="fa fa-step-forward"></span></a>
			<?php  endif; ?>
			<?php  if($paging->end_link): ?>
                <a href="<?php echo site_url("keluar/perorangan/$nik[no]/$paging->end_link/$o")?>" class="uibutton">Akhir <span class="fa fa-fast-forward"></span></a>
			<?php  endif; ?>
            </div>
        </div>


</table>
</div>
