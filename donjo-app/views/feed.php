<?php header('Content-type: text/xml'); ?>
<?php echo'<?xml version="1.0" encoding="UTF-8" ?>' ?>
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/">
	<channel>
		<title>Desa <?= $data_config["nama_desa"] ?></title>
		<link><?= base_url() ?></link>
		<description>
			Situs Web Desa <?= $data_config["nama_desa"] ." Kec. ".
			$data_config["nama_kecamatan"] ."
			". ucwords($this->setting->sebutan_kabupaten_singkat)."
			". $data_config["nama_kabupaten"] ." - ". $data_config["nama_propinsi"] ?>
		</description>
		<language>ID</language>
		<generator>Sistem Informasi Desa</generator>
	</channel>
	<?php foreach ($feeds as $key): ?>
		<item>
			<title><?= htmlspecialchars($key->judul); ?></title>
			<link><?= site_url("artikel/".buat_slug((array) $key));?></link>
			<pubdate><?= date(DATE_RSS, strtotime($key->tgl_upload)); ?></pubdate>
			<description>
				<?= htmlspecialchars(substr($key->isi, 0, max(strpos($key->isi, " ", 260), 200)))?>
			</description>
		</item>
	<?php endforeach; ?>
</rss>
