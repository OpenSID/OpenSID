<?= header("Content-Type: application/rss+xml"); ?>
<?= '<?xml version="1.0" encoding="utf-8"?>' . "\n"; ?>
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/"
xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/"
xmlns:atom="http://www.w3.org/2005/Atom" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
xmlns:slash="http://purl.org/rss/1.0/modules/slash/">
	<channel>
		<title>Desa <?= $data_config["nama_desa"]; ?></title>
		<link><?= base_url(); ?></link>
		<description>Situs Web <?= ucwords($this->setting->sebutan_desa . ' ' . $data_config["nama_desa"] . ' ' . $this->setting->sebutan_kecamatan_singkat . ' ' . $data_config["nama_kecamatan"] . ' ' . $this->setting->sebutan_kabupaten_singkat . ' ' . $data_config["nama_kabupaten"] . ' Prov. ' . $data_config["nama_propinsi"]); ?></description>
		<language>ID</language>
		<generator>Sistem Informasi Desa</generator>
	</channel>
	<?php foreach ($feeds as $key): ?>
		<item>
			<title><?= htmlspecialchars($key->judul); ?></title>
			<link><?= site_url("artikel/".buat_slug((array) $key)); ?></link>
			<pubdate><?= date(DATE_RSS, strtotime($key->tgl_upload)); ?></pubdate>
			<description><?= htmlentities(strip_tags(substr($key->isi, 0, max(strpos($key->isi, " ", 260), 200))) . '[...]'); ?></description>
		</item>
	<?php endforeach; ?>
</rss>
