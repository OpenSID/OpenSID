
<div id="pageC">
<!-- Start of Space Admin -->
	<table class="inner">
	<tr style="vertical-align:top">
	<td class="side-menu">
		<fieldset><legend>Laporan : </legend>
			<div class="lmenu">
				<ul>
				<li ><a href="<?php echo site_url()?>sid_laporan_bulanan">Laporan Bulanan</a></li>
				<li ><a href="<?php echo site_url()?>sid_laporan_kelompok">Data Kelompok Rentan</a></li>
				</ul>
			</div>
		</fieldset>
		<fieldset><legend>Statistik Keluarga Berdasarkan : </legend>
			<div class="lmenu">
				<ul>
				<li <?php if($lap==21){?>class="selected"<?php }?>>
					<a href="<?php echo site_url()?>sid_laporan_penduduk/index/21">Kelas Sosial</a></li>
				</ul>
			</div>
		</fieldset>

		<fieldset><legend>Statistik Penduduk Berdasarkan : </legend>
			<div class="lmenu">
				<ul>
				<li <?php if($lap==0){?>class="selected"<?php }?>>
					<a href="<?php echo site_url()?>sid_laporan_penduduk/index/0">Pendidikan</a></li>
				<li <?php if($lap==1){?>class="selected"<?php }?>>
					<a href="<?php echo site_url()?>sid_laporan_penduduk/index/1">Pekerjaan</a></li>
				<li <?php if($lap==2){?>class="selected"<?php }?>>
					<a href="<?php echo site_url()?>sid_laporan_penduduk/index/2">Status Perkawinan</a></li>
				<li <?php if($lap==3){?>class="selected"<?php }?>>
					<a href="<?php echo site_url()?>sid_laporan_penduduk/index/3">Agama</a></li>
				<li <?php if($lap==4){?>class="selected"<?php }?>>
					<a href="<?php echo site_url()?>sid_laporan_penduduk/index/4">Jenis Kelamin</a></li>
				<li <?php if($lap==5){?>class="selected"<?php }?>>
					<a href="<?php echo site_url()?>sid_laporan_penduduk/index/5">Warga Negara</a></li>
				<li <?php if($lap==6){?>class="selected"<?php }?>>
					<a href="<?php echo site_url()?>sid_laporan_penduduk/index/6">Status Penduduk</a></li>
				<li <?php if($lap==7){?>class="selected"<?php }?>>
					<a href="<?php echo site_url()?>sid_laporan_penduduk/index/7">Golongan Darah</a></li>
				<li <?php if($lap==8){?>class="selected"<?php }?>>
					<a href="<?php echo site_url()?>sid_laporan_penduduk/index/8">Cacat Fisik</a></li>
				<li <?php if($lap==9){?>class="selected"<?php }?>>
					<a href="<?php echo site_url()?>sid_laporan_penduduk/index/9">Cacat Mental</a></li>
				<li <?php if($lap==10){?>class="selected"<?php }?>>
					<a href="<?php echo site_url()?>sid_laporan_penduduk/index/10">Sakit Menahun</a></li>
				</ul>
			</div>
		</fieldset>
		<fieldset><legend>Statistik Keluarga Berdasarkan : </legend>
			<div class="lmenu">
				<ul>
					<li <?php if($lap==10){?>class="selected"<?php }?>>
					<a href="<?php echo site_url()?>sid_laporan_keluarga/index/10">Tingkat Kemiskinan Menurut BPS</a></li>
					<li ><a href="?code=1&amp;xcode=15">Tingkat Kemiskinan Menurut DINSOS</a></li>
					<li ><a href="?code=1&amp;xcode=16">Tingkat Kemiskinan Menurut KB</a></li>
					<li ><a href="?code=1&amp;xcode=17">Tingkat Kemiskinan Menurut DINKES</a></li>
					<li ><a href="?code=1&amp;xcode=18">Tingkat Kemiskinan Indikator Lokal</a></li>
					<li ><a href="?code=1&amp;xcode=19">Kepemilikan Rumah</a></li>
					<li ><a href="?code=1&amp;xcode=20">Pendapatan Per Bulan</a></li>
					<li ><a href="?code=1&amp;xcode=21">Pengeluaran Per Bulan</a></li>
					<li ><a href="?code=1&amp;xcode=22">Sumber Air Minum Utama</a></li>
					<li ><a href="?code=1&amp;xcode=23">Kualitas Air Minum</a></li>
					<li ><a href="?code=1&amp;xcode=24">Aset Kepemilikan Rumah: Menurut Dinding</a></li>
					<li ><a href="?code=1&amp;xcode=25">Aset Kepemilikan Rumah: Menurut Atap </a></li>
					<li ><a href="?code=1&amp;xcode=26">Aset Kepemilikan Rumah: menurut Lantai </a></li>
					<li ><a href="?code=1&amp;xcode=27">Kepemilikan Jamban</a></li>
					<li ><a href="?code=1&amp;xcode=28">Pola Makan Keluarga</a></li>
					<li ><a href="?code=1&amp;xcode=29">Energi Listrik</a></li>
				</ul>
			</div>
		</fieldset>

		</td>
<td style="background:#fff;padding:0px;">
<div class="content-header">
    <h3>Laporan</h3>
</div>
<div id="contentpane">
	<form id="mainform" name="mainform" action="" method="post">
    <div class="ui-layout-north panel">
        <div class="left">
            <div class="uibutton-group">
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
                <th>No</th>
				<th align="left" align="center">Nama</th>
				<th align="left" align="center">Jumlah KK</th>

			</tr>
		</thead>
		<tbody>
        <?php  foreach($main as $data): ?>
		<tr>
          <td align="center" width="2"><?php echo $data['no']?></td>
          <td><?php echo $data['tabel']?></td>
          <td>-</td>
		  </tr>
        <?php  endforeach; ?>
		</tbody>
        </table>
    </div>
	</form>
    <div class="ui-layout-south panel bottom">
        <div class="left">
		<div class="table-info">
          <form id="paging" action="<?php echo site_url('sid_laporan_penduduk')?>" method="post">
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
				<a href="<?php echo site_url("sid_laporan_penduduk/index/$lap/$paging->start_link/$o")?>" class="uibutton"  ><span class="fa fa-fast-backward"></span> Awal</a>
			<?php  endif; ?>
			<?php  if($paging->prev): ?>
				<a href="<?php echo site_url("sid_laporan_penduduk/index/$lap/$paging->prev/$o")?>" class="uibutton"  ><span class="fa fa-step-backward"></span> Prev</a>
			<?php  endif; ?>
            </div>
            <div class="uibutton-group">

				<?php  for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
				<a href="<?php echo site_url("sid_laporan_penduduk/index/$lap/$i/$o")?>" <?php  jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?php echo $i?></a>
				<?php  endfor; ?>
            </div>
            <div class="uibutton-group">
			<?php  if($paging->next): ?>
				<a href="<?php echo site_url("sid_laporan_penduduk/index/$lap/$paging->next/$o")?>" class="uibutton">Next <span class="fa fa-step-forward"></span></a>
			<?php  endif; ?>
			<?php  if($paging->end_link): ?>
                <a href="<?php echo site_url("sid_laporan_penduduk/index/$lap/$paging->end_link/$o")?>" class="uibutton">Akhir <span class="fa fa-fast-forward"></span></a>
			<?php  endif; ?>
            </div>
        </div>
    </div>
</div>
</td></tr></table>
</div>
