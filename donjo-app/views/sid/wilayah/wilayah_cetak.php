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
        <? foreach($main as $data): ?>
		<tr>
          <td align="center" width="2"><?=$data['no']?></td>
			<td align="center" width="5">
				<input type="checkbox" name="id_cb[]" value="<?=$data['dusun']?>" />
			</td>

                <td><?=$data['dusun']?></td>
	        <td><?=$data['nama_kadus']?></td> 
		
               <td><a href="<?=site_url("sid_core/sub_rw/$p/$o/$data[dusun]")?>" title="Rincian Sub Wilayah"><?=$data['jumlah_rw']?></a></td>
               <td><a href="<?=site_url("sid_core/list_dusun_rt/$p/$o/$data[dusun]")?>" title="Rincian Sub Wilayah"><?=$data['jumlah_rt']?></a></td>
                <td><?=$data['jumlah_kk']?></td>
                <td><?=$data['jumlah_warga']?></td>
                <td><?=$data['jumlah_warga_l']?></td>
                <td><?=$data['jumlah_warga_p']?></td>
		  </tr>
        <? endforeach; ?>
		</tbody>
		<thead>
            <tr>
                <th>No</th>
                <th><input type="checkbox" class="checkall"/></th>
                <th width="50">Total</th>
				<th align="left" align="center"></th>
				<th align="left" align="center"></th>
				<th align="left" align="center"><?=$total['total_rw']?></th>
				<th align="left" align="center"><?=$total['total_rt']?></th>
				<th align="left" align="center"><?=$total['total_kk']?></th>
				<th align="left" align="center"><?=$total['total_warga']?></th>
				<th align="left" align="center"><?=$total['total_warga_l']?></th>
				<th align="left" align="center"><?=$total['total_warga_p']?></th>
			</tr>
		</thead>
		
		
        </table>
