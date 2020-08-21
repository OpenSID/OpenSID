<br>
<table class="table table-bordered table-striped table-hover" >
	<tbody>
		<tr>
			<td>Nomor Kartu Peserta</td>
			<td> : <?= $peserta; ?></td>
		</tr>
		<tr>
			<td>NIK</td>
			<td> : <?= $kartu_nama; ?></td>
		</tr>
		<tr>
			<td>Nama</td>
			<td> : <?= $kartu_nik; ?></td>
		</tr>
		<tr>
			<td>Tempat Lahir</td>
			<td> : <?= $kartu_tempat_lahir; ?></td>
		</tr>
		<tr>
			<td>Tanggal Lahir</td>
			<td> : <?= tgl_indo($kartu_tanggal_lahir); ?></td>
		</tr>
		<tr>
			<td>Alamat</td>
			<td> : <?= $kartu_alamat; ?></td>
		</tr>
	</tbody>
</table>
