<?php echo'<?xml version="1.0" encoding="UTF-8" ?>' ?>

<!-- https://itsolutionstuff.com/post/how-to-create-dynamic-sitemap-in-php-codeigniterexample.html -->

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc><?= base_url();?></loc>
        <priority>1.0</priority>
        <changefreq>daily</changefreq>
    </url>

    <!-- Sitemap -->
    <?php foreach($artikel as $satu_artikel): ?>
    <url>
        <loc><?= base_url()."first/artikel/".$satu_artikel->id ?></loc>
        <priority>0.5</priority>
        <changefreq>weekly</changefreq>
    </url>
    <?php endforeach; ?>


</urlset>
