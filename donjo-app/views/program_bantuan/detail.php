<?php
/*
 * program.php
 *
 * Backend View untuk Program Bantuan
 *
 * Copyright 2015 Isnu Suntoro <isnusun@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 *
 *
 */

?>
<div id="pageC">
<table class="inner">
	<tr style="vertical-align:top">
		<td class="side-menu">
		<?php
		$this->load->view('program_bantuan/menu_kiri.php')
		?>
		</td>
		<td class="contentpane">
			<div id="contentpane">
				<div class="ui-layout-center" id="maincontent">
					<?php if($program[0]["status"] == 0){?>

						<div class="left">
							<div class="uibutton-group">
								<a href="<?php echo site_url("program_bantuan/form/".$program[0]['id'])?>" class="uibutton tipsy south" title="Tambah Peserta Baru" ><span class="fa fa-plus-square">&nbsp;</span>Tambah Peserta Baru</a>
							</div>
						</div>
					<?php }?>
					<legend style="margin-top: 30px;">Rincian Program</legend>
					<div>
						<?php
						if($_SESSION["success"]==1){
							echo "
							Data Pembaruan telah tersimpan
							";
						}
						$detail = $program[0];
						echo "
						<div>
							<table class=\"form\">
								<tr><td>Nama Program</td><td><strong>".strtoupper($detail["nama"])."</strong></td></tr>
								<tr><td>Sasaran Peserta</td><td><strong>".$sasaran[$detail["sasaran"]]."</strong></td></tr>
								<tr><td>Masa Berlaku</td><td><strong>".fTampilTgl($detail["sdate"],$detail["edate"])."</strong></td></tr>
								<tr><td>Keterangan</td><td><strong>".$detail["ndesc"]."</strong></td></tr>
							</table>
						</div>
						";

						$peserta = $program[1];
						?>
						<legend>Daftar Peserta Program</legend>
						<table class="list">
							<thead>
								<tr>
									<th rowspan="2">No</th>
									<th rowspan="2">Aksi</th>
									<th rowspan="2"><?php echo $detail["judul_peserta"]?></th>
									<th rowspan="2">No. Kartu Peserta</th>
									<th rowspan="2"><?php echo $detail["judul_peserta_info"]?></th>
									<th rowspan="2">Alamat</th>
									<th colspan="5" style="text-align: center;">Identitas di Kartu Peserta</th>
								</tr>
								<tr>
									<th>NIK</th>
									<th>Nama</th>
									<th>Tempat Lahir</th>
									<th>Tanggal Lahir</th>
									<th>Alamat</th>
								</tr>
							</thead>
							<tbody>
							<?php
							$nomer = $paging->offset;
							if(is_array($peserta)){
								foreach ($peserta as $key=>$item){
									$nomer++;
								?>
									<tr>
										<td align="center" width="2"><?php echo $nomer; ?></td>
						        <td>
						          <div class="uibutton-group">
						            <?php if($_SESSION['grup']==1){?>
						                <a href="<?php echo site_url('program_bantuan/hapus_peserta/'.$detail["id"].'/'.$item["id"])?>" class="uibutton tipsy south" title="Hapus Data" target="confirm" message="Apakah Anda Yakin?" header="Hapus Data"><span class="fa fa-trash"></span></a>
							        			<a href="<?php echo site_url("program_bantuan/edit_peserta_form/$item[id]")?>"  class="uibutton tipsy south" title="Ubah Data Peserta" target="ajax-modal" rel="window" header="Ubah Data Peserta" modalWidth="auto" modalHeight="400"><span class="fa fa-edit"></span></a>
						            <?php } ?>
						          </div>
						        </td>
										<td><a href="<?php echo site_url('program_bantuan/peserta/'.$detail["sasaran"].'/'.$item["nik"].'/')?>" title="Daftar program untuk peserta"><?php echo $item["peserta_nama"] ?></a></td>
										<td><a href="<?php echo site_url('program_bantuan/data_peserta/'.$item["id"])?>" title="Data peserta"><?php echo $item['no_id_kartu'];?></a></td>
										<td><?php echo $item["peserta_info"]?></td>
										<td><?php echo $item["info"];?></td>
										<td><?php echo $item["kartu_nik"];?></td>
										<td><?php echo $item["kartu_nama"];?></td>
										<td><?php echo $item["kartu_tempat_lahir"];?></td>
										<td><?php echo $item["kartu_tanggal_lahir"];?></td>
										<td><?php echo $item["kartu_alamat"];?></td>
									</tr>
								<?php
								}
							}
							?>
							</tbody>
						</table>
						<div style="padding:1em 0;">
							<a class="uibutton" href="<?php echo site_url('program_bantuan/unduhsheet/'.$detail["id"].'/')?>"><i class="fa fa-download"></i> Unduh dalam format .xls</a>
						</div>
					</div>
				</div>

		    <div class="ui-layout-south panel bottom">
		      <div class="left">
						<div class="table-info">
			        <form id="paging" action="<?php echo site_url('program_bantuan/detail/1/'.$detail['id'])?>" method="post">
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
								<a href="<?php echo site_url('program_bantuan/detail/'.$paging->start_link.'/'.$detail['id'])?>" class="uibutton"  ><span class="fa fa-fast-backward"></span> Awal</a>
							<?php  endif; ?>
							<?php  if($paging->prev): ?>
								<a href="<?php echo site_url('program_bantuan/detail/'.$paging->prev.'/'.$detail['id'])?>" class="uibutton"  ><span class="fa fa-step-backward"></span> Prev</a>
							<?php  endif; ?>
	          </div>
		        <div class="uibutton-group">
							<?php  for($i=$paging->start_link;$i<=$paging->end_link;$i++): ?>
								<a href="<?php echo site_url('program_bantuan/detail/'.$i.'/'.$detail['id'])?>" <?php  jecho($p,$i,"class='uibutton special'")?> class="uibutton"><?php echo $i?></a>
							<?php  endfor; ?>
	          </div>
	          <div class="uibutton-group">
							<?php  if($paging->next): ?>
								<a href="<?php echo site_url('program_bantuan/detail/'.$paging->next.'/'.$detail['id'])?>" class="uibutton">Next <span class="fa fa-step-forward"></span></a>
							<?php  endif; ?>
							<?php  if($paging->end_link): ?>
		            <a href="<?php echo site_url('program_bantuan/detail/'.$paging->end_link.'/'.$detail['id'])?>" class="uibutton">Akhir <span class="fa fa-fast-forward"></span></a>
							<?php  endif; ?>
	          </div>
	        </div>
		    </div>

			</div>
		</td>
	</tr>
</table>
</div>
