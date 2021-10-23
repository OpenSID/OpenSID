<?php
//lapor_show_view
?> 
<div class='row2' style='margin:5px 10px;padding:10px'>
 
		<h3>Laporan <?=$pamong->nama;?> </h3>

		<table class="table table-bordered table-hover ">
			<thead class="bg-gray disabled color-palette">
				<tr>
					<th>Nama</th>
					<!--th>Waktu Lapor</th-->
					<th>Laporan</th>
				</tr>
			</thead>
			<tbody>
<?php 
foreach($lapor as $info)
{?>
			<tr>
				<td><?=@$info->nama;?></td>
				<!--td><?=@$info->tanggal;?></td-->
				<td><?=@$info->laporan;?></td>
			
			</tr>
<?php
}	
?>									
			</tbody>
		</table>
 
</div> 