<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ site_url() }}</loc>
        <priority>1.0</priority>
        <changefreq>daily</changefreq>
    </url>

    <!-- Sitemap -->
    @foreach ($artikel as $a)
        <url>
            <loc>{{ site_url('artikel/' . buat_slug($a)) }}</loc>
            <lastmod>{{ date_format(date_create($a['tgl_upload']), 'Y-m-d') }}</lastmod>
            <priority>0.5</priority>
            <changefreq>weekly</changefreq>
        </url>
    @endforeach

</urlset>
