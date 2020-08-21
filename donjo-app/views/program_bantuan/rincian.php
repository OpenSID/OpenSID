<h4>Rician Program Bantuan</h4>
<table class="table table-bordered table-striped table-hover tabel-info">
	<tbody>
		<tr>
			<td width="20%">Nama Program</td>
			<td width="1">:</td>
			<td><?= strtoupper($detail["nama"]); ?></td>
		</tr>
		<tr>
			<td>Sasaran Peserta</td>
			<td> : </td>
			<td><?= $sasaran[$detail["sasaran"]]?></td>
		</tr>
		<tr>
			<td>Masa Berlaku</td>
			<td> : </td>
			<td><?= fTampilTgl($detail["sdate"],$detail["edate"])?></td>
		</tr>
		<tr>
			<td>Keterangan</td>
			<td> : </td>
			<td><?= $detail["ndesc"]?></td>
		</tr>
	</tbody>
</table>
