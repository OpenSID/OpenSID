<div id="pageC">
<table class="inner">
	<tr style="vertical-align:top">
		<td class="side-menu">
			<?php
			$this->load->view('suplemen/menu_kiri.php')
			?>
		</td>
		<td style="background:#fff;padding:0px;">
			<div id="contentpane">
				<div class="ui-layout-center" id="maincontent">
					<?php if($program[0]["status"] == 0){?>

						<div class="left">
							<div class="uibutton-group" style="margin-top: 10px;">
								<a href="<?php echo site_url("suplemen/form_terdata/".$suplemen['id'])?>" class="uibutton tipsy south" title="Tambah Warga Terdata" ><span class="fa fa-plus-square">&nbsp;</span>Tambah Warga Terdata</a>
							</div>
						</div>
					<?php }?>
					<legend style="margin-top: 20px;">Rincian Data Suplemen</legend>
					<div>
						<?php
						if($_SESSION["success"]==1){
							echo "
							Data Pembaruan telah tersimpan
							";
						}
						echo "
						<div>
							<table class=\"form\">
								<tr><td>Nama Data</td><td><strong>".strtoupper($suplemen["nama"])."</strong></td></tr>
								<tr><td>Sasaran Terdata</td><td><strong>".$sasaran[$suplemen["sasaran"]]."</strong></td></tr>
								<tr><td>Keterangan</td><td><strong>".$suplemen["keterangan"]."</strong></td></tr>
							</table>
						</div>
						";

						?>
						<legend>Daftar Warga Terdata</legend>
						<table class="list">
							<thead>
								<tr>
									<th>No</th>
									<th>Aksi</th>
									<th><?php echo $suplemen["judul_terdata_nama"]?></th>
									<th><?php echo $suplemen["judul_terdata_info"]?></th>
									<th>Alamat</th>
									<th>Keterangan</th>
								</tr>
							</thead>
							<tbody>
							<?php
							$nomer = $paging->offset;
							if(is_array($terdata)){
								foreach ($terdata as $key=>$item){
									$nomer++;
								?>
									<tr>
										<td align="center" width="2"><?php echo $nomer; ?></td>
						        <td>
						          <div class="uibutton-group">
						            <?php if($_SESSION['grup']==1){?>
						                <a href="<?php echo site_url('suplemen/hapus_terdata/'.$suplemen["id"].'/'.$item["id"])?>" class="uibutton tipsy south" title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span class="fa fa-trash"></span></a>
							        			<a href="<?php echo site_url("suplemen/edit_terdata_form/$item[id]")?>"  class="uibutton tipsy south" title="Ubah Terdata" target="ajax-modalx" rel="window" header="Ubah Terdata" modalWidth="auto" modalHeight="auto"><span class="fa fa-edit"></span></a>
						            <?php } ?>
						          </div>
						        </td>
										<td><a href="<?php echo site_url('suplemen/terdata/'.$suplemen["sasaran"].'/'.$item["nik"].'/')?>" title="Daftar suplemen untuk terdata"><?php echo $item["terdata_nama"] ?></a></td>
										<td><a href="<?php echo site_url('suplemen/data_terdata/'.$item["id"])?>" title="Data terdata"><?php echo $item['terdata_info'];?></a></td>
										<td><?php echo $item["info"];?></td>
										<td><?php echo $item["keterangan"];?></td>
									</tr>
								<?php
								}
							}
							?>
							</tbody>
						</table>
						<div style="padding:1em 0;">
							<a class="uibutton" href="<?php echo site_url('suplemen/unduhsheet/'.$suplemen["id"])?>"><i class="fa fa-download"></i> Unduh dalam format .xls</a>
						</div>
					</div>
				</div>
		    <div class="ui-layout-south panel bottom">
		      <div class="left">
						<div class="table-info">
			        <form id="paging" action="<?php echo site_url('suplemen/rincian/1/'.$suplemen['id'])?>" method="post">
							  <label>Tampilkan</label>
			          <select name="per_page" onchange="$('#paging').submit()" >
			            <option value="50" <?php  selected($per_page,50); ?> >50</option>
			            <option value="100" <?php  selected($per_page,100); ?> >100</option>
			            <option value="200" <?php  selected($per_page,200); ?> >200</option>
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
								<a href="<?php echo site_url('suplemen/rincian/'.$paging->start_link.'/'.$suplemen['id'])?>" class="uibutton"  ><span class="fa fa-fast-backward"></span> Awal</a>
							<?php  endif; ?>
							<?php  if($paging->prev): ?>
								<a href="<?php echo site_url('suplemen/rincian/'.$paging->prev.'/'.$suplemen['id'])?>" class="uibutton"  ><span class="fa fa-step-backward"></span> Prev</a>
							<?php  endif; ?>
	          </div>
		        <div class="uibutton-group">
							<?php  for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
								<a href="<?php echo site_url('suplemen/rincian/'.$i.'/'.$suplemen['id'])?>" <?php  jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?php echo $i?></a>
							<?php  endfor; ?>
	          </div>
	          <div class="uibutton-group">
							<?php  if($paging->next): ?>
								<a href="<?php echo site_url('suplemen/rincian/'.$paging->next.'/'.$suplemen['id'])?>" class="uibutton">Next <span class="fa fa-step-forward"></span></a>
							<?php  endif; ?>
							<?php  if($paging->end_link): ?>
		            <a href="<?php echo site_url('suplemen/rincian/'.$paging->end_link.'/'.$suplemen['id'])?>" class="uibutton">Akhir <span class="fa fa-fast-forward"></span></a>
							<?php  endif; ?>
	          </div>
	        </div>
		    </div>
			</div>
		</td>
	</tr>
</table>
</div>
