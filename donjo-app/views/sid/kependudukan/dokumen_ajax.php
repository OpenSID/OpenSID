<style>
.bawah{
	position:absolute;
	bottom:10px;
	right:10px;
	width:430px;
}
</style>
 <table class="list">
		<thead>
 <tr>
 <th>No</th>
 <th>Nama Dokumen</th>
				<th>Tgl Upload</th>
			</tr>
		</thead>
		<tbody>
 <?php foreach($list_dokumen as $data){?>
		<tr>
			<td align="center" width="2"><?php echo $data['no']?></td>
			 <td><a href="<?php echo base_url().LOKASI_DOKUMEN?><?php echo urlencode($data['satuan'])?>" ><?php echo $data['nama']?></a></td>
			 <td><?php echo tgl_indo2($data['tgl_upload'])?></td>
		</tr>
 <?php }?>
		</tbody>
 </table>