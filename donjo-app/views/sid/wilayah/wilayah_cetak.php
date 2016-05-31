 <table class="list">
		<thead>
            <tr>
                <th>No</th>
				<th align="left" align="center">Nama Dusun</th>
				<th align="left" align="center">Nama Kadus</th>
				<th width="50" align="left" align="center">RW</th>
				<th width="50" align="left" align="center">RT</th>
				<th width="50" align="left" align="center">KK</th>
				<th width="50" align="left" align="center">Jiwa</th>
				<th width="50" align="left" align="center">LK</th>
				<th width="50" align="left" align="center">PR</th>
				
				
			</tr>
		</thead>
		<tbody>
        <?php  foreach($main as $data): ?>
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
        <?php  endforeach; ?>
		</tbody>
		<thead>
            <tr>
                <th>No</th>
                <th><input type="checkbox" class="checkall"/></th>
                <th width="50">Total</th>
				<th align="left" align="center"></th>
				<th align="left" align="center"></th>
				<th align="left" align="center"><?php echo $total['total_rw']?></th>
				<th align="left" align="center"><?php echo $total['total_rt']?></th>
				<th align="left" align="center"><?php echo $total['total_kk']?></th>
				<th align="left" align="center"><?php echo $total['total_warga']?></th>
				<th align="left" align="center"><?php echo $total['total_warga_l']?></th>
				<th align="left" align="center"><?php echo $total['total_warga_p']?></th>
			</tr>
		</thead>
		
		
        </table>
