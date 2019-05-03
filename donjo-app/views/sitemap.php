<?php echo'<?xml version="1.0" encoding="UTF-8" ?>' ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    <url>

        <loc><?php echo base_url();?></loc>

        <priority>1.0</priority>

        <changefreq>daily</changefreq>

    </url>


    <!-- Sitemap -->

    <?php foreach($artikel as $artikel) { ?>

    <url>

        <loc><?php echo base_url()."first/artikel/".$artikel->id ?></loc>

        <priority>0.5</priority>

        <changefreq>daily</changefreq>

    </url>

    <?php } ?>


</urlset>
