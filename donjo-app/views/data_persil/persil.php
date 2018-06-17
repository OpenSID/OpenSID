<script>
	$(function() {
		var keyword = <?php echo $keyword?> ;
		$( "#cari" ).autocomplete({
			source: keyword
		});
	});
</script>
<style type="text/css">
	#maincontent .right {text-align: right;}
</style>
<div id="pageC">
<table class="inner">
<tr style="vertical-align:top">

	<?php if($this->modul_ini <> 15): ?>
		<td class="side-menu">
			<?php
				$this->load->view('data_persil/menu_kiri.php')
			?>
		</td>
	<?php endif; ?>

	<td style="background:#fff;padding:0px;">
		<div id="contentpane">
			<form id="mainform" name="mainform" action="" method="post">
		    <div class="ui-layout-north">
					<legend>Daftar Data Persil <?php echo $desa["nama_desa"];?></legend>
					<div class="uibutton-group">
	                <a href="<?php echo site_url("data_persil/cetak/$o")?>" class="uibutton tipsy south" title="Cetak Data" target="_blank"><span class="fa fa-print">&nbsp;</span>Cetak</a>
					<a href="<?php echo site_url("data_persil/excel/$o")?>" class="uibutton tipsy south" title="Unduh Data" target="_blank"><span class="fa fa-file-text">&nbsp;</span>Unduh</a>
	            </div>
		    </div>
		    <div class="ui-layout-center" id="maincontent" style="padding: 5px;">
		      <!-- <div class="table-panel top"> -->
		        <div class="right">
		          <input name="cari" id="cari" type="text" class="inputbox help tipped" size="20" value="<?php echo $cari?>" title="Cari.." onkeypress="if (event.keyCode == 13) {$('#'+'mainform').attr('action','<?php echo site_url("{$this->controller}/search")?>');$('#'+'mainform').submit();}" />
		          <button type="button" onclick="$('#'+'mainform').attr('action','<?php echo site_url("{$this->controller}/search")?>');$('#'+'mainform').submit();" class="uibutton tipsy south"  title="Cari Data"><span class="fa fa-search">&nbsp;</span>Cari</button>
		        </div>
		      <!-- </div> -->

					<?php
						if($_SESSION["success"]==1){
							echo "<div>".$_SESSION["pesan"]."</div>";
							$_SESSION["success"]=0;
							$_SESSION["pesan"]="";
						}
					?>

					<!-- List Data -->

					<?php	if ($persil): ?>
						<?php if (count($persil)>0): ?>
							<div class="table-panel top">
								<table class="list">
									<thead>
										<tr>
											<th>#</th>
											<th style="width:120px;"></th>
											<th>Nama Pemilik</th><th>NIK</th>
											<th>NO Persil</th>
											<th>Luas (m<sup>2</sup>)</th>
											<th>Nomor SPPT PBB</th>
										</tr>
									</thead>
									<tbody>
										<?php $nomer =0; ?>
										<?php foreach ($persil as $key=>$item): ?>
											<?php $nomer++; ?>
											<tr>
											<td class="angka"><?= $item['no'] ?></td>
											<td><div class="uibutton-group">
													<a class="uibutton tipsy south fa-tipis" href="<?= site_url("data_persil/detail/".$item["id"]) ?>" title="Rincian"><span class="fa fa-list"></span> Rincian</a>
													<?php if ($item['jenis_pemilik'] == '2'): ?>
														<a class="uibutton tipsy south" href="<?= site_url("data_persil/create_ext/".$item["id"]) ?>" title="Ubah"><span class="fa fa-pencil"></span></a>
													<?php else: ?>
														<a class="uibutton tipsy south" href="<?= site_url("data_persil/create/".$item["id"]) ?>" title="Ubah"><span class="fa fa-pencil"></span></a>
													<?php endif; ?>
													<a class="uibutton tipsy south" href="<?= site_url("data_persil/hapus/".$item["id"])?>" title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span class="fa fa-trash"></span></a>
												</div></td>
											<td><?= $item["namapemilik"] ?></td>
											<td><?= $item["nik"] ?></td>
											<td><?= $item["nopersil"] ?></td>
											<td><?= $item["luas"] ?></td>
											<td><a href="#"><?= $item["no_sppt_pbb"] ?></a></td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						<?php endif; ?>
					<?php else: ?>
						<div class="box box-warning">
							<h3 class="box-header">Belum ada Data</h3>
							<div class="box-body">Silakan ditambahkan data persil dengan menggunakan formulir dari menu <a href="<?= site_url("data_persil/create") ?>"><i class="fa fa-plus"></i> Tambah Data Persil Baru</a></div>
						</div>
					<?php endif; ?>

		    </div>
			</form>
		  <div class="ui-layout-south panel bottom">
		    <div class="left">
					<div class="table-info">
		        <form id="paging" action="<?php echo site_url($this->controller.'/index/'.$kat.'/'.$mana)?>" method="post">
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
							<a href="<?php echo site_url("{$this->controller}/index/$kat/$mana/$paging->start_link/$o")?>" class="uibutton"  ><span class="fa fa-fast-backward"></span> Awal</a>
						<?php  endif; ?>
						<?php  if($paging->prev): ?>
							<a href="<?php echo site_url("{$this->controller}/index/$kat/$mana/$paging->prev/$o")?>" class="uibutton"  ><span class="fa fa-step-backward"></span> Prev</a>
						<?php  endif; ?>
		      </div>
		      <div class="uibutton-group">

						<?php  for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
							<a href="<?php echo site_url("{$this->controller}/index/$kat/$mana/$i/$o")?>" <?php  jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?php echo $i?></a>
						<?php  endfor; ?>
		      </div>
		      <div class="uibutton-group">
					<?php  if($paging->next): ?>
						<a href="<?php echo site_url("{$this->controller}/index/$kat/$mana/$paging->next/$o")?>" class="uibutton">Next <span class="fa fa-step-forward"></span></a>
					<?php  endif; ?>
					<?php  if($paging->end_link): ?>
		        <a href="<?php echo site_url("{$this->controller}/index/$kat/$mana/$paging->end_link/$o")?>" class="uibutton">Akhir <span class="fa fa-fast-forward"></span></a>
					<?php  endif; ?>
		    </div>
		  </div>
		</div>
	</td>

	<td style="width:250px;" class="contentpane">
		<?php
			$this->load->view('data_persil/panduan.php');
		?>
	</td>
</tr>
</table>
</div>
