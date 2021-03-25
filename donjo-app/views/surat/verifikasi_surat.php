<style type="text/css">
	.content {
		margin: auto;
		width: 80%;
		border: 3px solid green;
		padding: 5px;
	}
	table.disdukcapil
	{
		width: 80%;
		margin-left: auto;
		margin-right: auto;
	}
	table.disdukcapil td
	{
		padding: 1px 1px 1px 3px;
	}
	table.disdukcapil td.abu
	{
		background-color: lightgrey;
	}
</style>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>
			<?=$this->setting->admin_title
				. ' ' . ucwords($this->setting->sebutan_desa)
				. (($config['nama_desa']) ? ' ' . $config['nama_desa']: '')
				. get_dynamic_title_page_from_path();
			?>
		</title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	</head>

	<body>
		<div class="content">
			<p style="text-align: center;">
				<strong style="font-size: 16pt;">
					Sistem Informasi Desa<br>
					(OpenSID)<br>
					Pemerintah <?=ucwords($this->setting->sebutan_kabupaten)?> <?= ucwords(strtolower($config['nama_kabupaten'])) ?><br>
					<?=ucwords($this->setting->sebutan_kecamatan)?> <?= ucwords(strtolower($config['nama_kecamatan'])) ?><br>
					<?=ucwords($this->setting->sebutan_desa)?> <?= ucwords(strtolower($config['nama_desa'])) ?><br>
					Menyatakan bahwa<br>
				</strong>
			</p>

			<table style="border-collapse: collapse;" class="disdukcapil">
				<col style="width: 38%;">
				<col style="width: 2%;">
				<col style="width: 60%;">
				<tr>
					<td class="judul abu">Nomor Surat</td>
					<td class="judul abu">:</td>
					<td class="judul abu"><?= $surat['format_nomor_surat'] ?></td>
				</tr>
				<tr>
					<td class="judul abu">Tanggal Surat</td>
					<td class="judul abu">:</td>
					<td class="judul abu"><?= $tanggal ?></td>
				</tr>
				<tr>
					<td class="judul abu">Perihal</td>
					<td class="judul abu">:</td>
					<td class="judul abu"><?= "Surat ".$surat['nama'] ?></td>
				</tr>
				<tr>
					<td class="judul abu"></td>
					<td class="judul abu"></td>
					<td class="judul abu"><?= "a/n ".$individu['nama'] ?></td>
				</tr>
			</table>

			<p style="text-align: center;">
				<strong style="font-size: 16pt;">
					Adalah benar dan tercatat dalam database sistem informasi kami.
				</strong>
			</p>

		</div>
	</body>
