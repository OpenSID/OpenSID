 <table class="list">
		<thead>
 <tr>
 <th>No</th>
				<th>Nama Dusun</th>
				<th>Nama Kadus</th>
				<th width="50" >RW</th>
				<th width="50" >RT</th>
				<th width="50" >KK</th>
				<th width="50" >Jiwa</th>
				<th width="50" >LK</th>
				<th width="50" >PR</th>
				
				
			</tr>
		</thead>
		<tbody>
 <?php foreach($main as $data): ?>
		<tr>
 <td align="center" width="2"><?php echo $data['no']?></td>
			<td align="center" width="5">
				<input type="checkbox" name="id_cb[]" value="<?php echo $data['dusun']?>" />
			</td>
 <td><?php echo $data['dusun']?></td>
	 <td><?php echo $data['nama_kadus']?></td> 
		
 <td><a href="<?php echo site_url("sid_core/sub_rw/$p/$o/$data[dusun]")?>" title="Rincian Sub Wilayah"><?php echo $data['jumlah_rw']?></a></td>
 <td><a href="<?php echo site_url("sid_core/list_dusun_rt/$p/$o/$data[dusun]")?>" title="Rincian Sub Wilayah"><?php echo $data['jumlah_rt']?></a></td>
 <td><?php echo $data['jumlah_kk']?></td>
 <td><?php echo $data['jumlah_warga']?></td>
 <td><?php echo $data['jumlah_warga_l']?></td>
 <td><?php echo $data['jumlah_warga_p']?></td>
		 </tr>
 <?php endforeach; ?>
		</tbody>
		<thead>
 <tr>
 <th>No</th>
 <th><input type="checkbox" class="checkall"/></th>
 <th width="50">Total</th>
				<th></th>
				<th></th>
				<th><?php echo $total['total_rw']?></th>
				<th><?php echo $total['total_rt']?></th>
				<th><?php echo $total['total_kk']?></th>
				<th><?php echo $total['total_warga']?></th>
				<th><?php echo $total['total_warga_l']?></th>
				<th><?php echo $total['total_warga_p']?></th>
			</tr>
		</thead>
 </table>