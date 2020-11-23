<!-- https://itsolutionstuff.com/post/how-to-create-dynamic-sitemap-in-php-codeigniterexample.html -->

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	<url>
		<loc><?= base_url() ?></loc>
		<priority>1.0</priority>
		<changefreq>daily</changefreq>
	</url>

	<!-- Sitemap -->
	<?php foreach($artikel as $a): ?>
	<url>
		<loc><?= base_url('index.php/artikel/'.buat_slug($a)); ?></loc>
		<lastmod><?= date_format(date_create($a['tgl_upload']), 'Y-m-d') ?></lastmod>
		<priority>0.5</priority>
		<changefreq>weekly</changefreq>
	</url>
	<?php endforeach; ?>

</urlset>