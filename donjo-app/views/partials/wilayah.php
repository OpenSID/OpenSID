<div class="themes bigfull">
<div class='title'>
<h2>Tabel Data Kependudukan berdasarkan <?=$heading;?></h2>
</div>
<div class='entry'>
<link href="<?=base_url()?>assets/front/general.css" rel="stylesheet" type="text/css" />     
            <table class="list" style="font-size:12px;">
		<thead>
            <tr>
                <th width="5">No</th>
				<th width="200">Nama Dusun</th>
				<th width="130">Nama Kepala Dusun</th>
				<th width="75">Jumlah RW</th>
				<th width="75">Jumlah RT</th>
				<th width="50">KK</th>
				<th width="50">Jiwa</th>
				<th width="50">LK</th>
				<th width="50">PR</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
        <? foreach($main as $data): ?>
		<tr>
          <td align="center" width="2"><?=$data['no']?></td>
			<td><?=strtoupper(unpenetration(ununderscore($data['dusun'])))?></td>
			<td><?=strtoupper(unpenetration($data['nama_kadus']))?></td> 
			<td align="right"><?=$data['jumlah_rw']?></td>
			<td align="right"><?=$data['jumlah_rt']?></td>
			<td align="right"><?=$data['jumlah_kk']?></td>
			<td align="right"><?=$data['jumlah_warga']?></td>
			<td align="right"><?=$data['jumlah_warga_l']?></td>
			<td align="right"><?=$data['jumlah_warga_p']?></td>
				<td></td>
		</tr>
        <? endforeach; ?>
		</tbody>
		
            <tr style="background-color:#BDD498;font-weight:bold;">
                <td colspan="3" align="left"><label>TOTAL</label></td>
				<td align="right"><?=$total['total_rw']?></td>
				<td align="right"><?=$total['total_rt']?></td>
				<td align="right"><?=$total['total_kk']?></td>
				<td align="right"><?=$total['total_warga']?></td>
				<td align="right"><?=$total['total_warga_l']?></td>
				<td align="right"><?=$total['total_warga_p']?></td>
				<td></td>
			</tr>
        </table>
</div>
</div>
