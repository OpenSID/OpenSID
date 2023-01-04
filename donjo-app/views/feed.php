<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/">
	<channel>
		<title>Desa <?= $data_config['nama_desa']; ?></title>
		<link><?= base_url(); ?></link>
		<atom:link href="<?= base_url('feed'); ?>" rel="self" type="application/rss+xml" />
		<description>Situs Web <?= ucwords($this->setting->sebutan_desa . ' ' . $data_config['nama_desa'] . ' ' . $this->setting->sebutan_kecamatan_singkat . ' ' . $data_config['nama_kecamatan'] . ' ' . $this->setting->sebutan_kabupaten_singkat . ' ' . $data_config['nama_kabupaten'] . ' Prov. ' . $data_config['nama_propinsi']); ?>.</description>
		<dc:language>id</dc:language>
		<dc:rights>Copyright 2016-<?= date('Y') ?> OpenDESA - OpenSID <?= $this->setting->current_version ?></dc:rights>
		<?php foreach ($feeds as $key): ?>
			<item>
				<title><?= htmlspecialchars($key->judul); ?></title>
				<link><?= site_url('artikel/' . buat_slug((array) $key)); ?></link>
				<guid><?= site_url('artikel/' . buat_slug((array) $key)); ?></guid>
				<pubDate><?= date(DATE_RSS, strtotime($key->tgl_upload)); ?></pubDate>
				<category><![CDATA[<?= $key->kategori ?>]]></category>
				<description>
					<![CDATA[
						<?php if (is_file(LOKASI_FOTO_ARTIKEL . "sedang_{$key->gambar}")): ?>
							<img src="<?= base_url(LOKASI_FOTO_ARTIKEL . "sedang_{$key->gambar}") ?>" />
						<?php endif; ?>
						<?= htmlentities(strip_tags(substr($key->isi, 0, max(strpos($key->isi, ' ', 260), 200))) . '[...]'); ?>
					]]>
				</description>
				<content:encoded>
					<![CDATA[
						<?php if (is_file(LOKASI_FOTO_ARTIKEL . "sedang_{$key->gambar}")): ?>
							<img src="<?= base_url(LOKASI_FOTO_ARTIKEL . "sedang_{$key->gambar}") ?>" />
						<?php endif; ?>
						<?= $key->isi ?>
					]]>
				</content:encoded>
				<dc:creator><?= $key->owner ?></dc:creator>
			</item>
		<?php endforeach; ?>
	</channel>
</rss>
