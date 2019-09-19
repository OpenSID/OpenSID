<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/"
    xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:atom="http://www.w3.org/2005/Atom" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
    xmlns:slash="http://purl.org/rss/1.0/modules/slash/">
    <?php 
	$details = "<channel>
    <title>Desa ". $data_config["nama_desa"] ."</title>
    <link>". base_url() ."</link>
    <description>Situs Web Desa ". $data_config["nama_desa"] ." Kec. ". $data_config["nama_kecamatan"] ."
        ". ucwords($this->setting->sebutan_kabupaten_singkat)."
        ". $data_config["nama_kabupaten"] ." - ". $data_config["nama_propinsi"] ."</description>
    <language>ID</language>
    <generator>Sistem Informasi Desa</generator>

    <atom:link href=\"".htmlspecialchars(site_url("feed"))."\" rel=\"self\" type=\"application/rss+xml\" />
    ";
    foreach ($feeds as $key=>$item):
    if (strlen(trim($item["judul"]))>0):
    $kategori = (strlen(trim($item["kategori"]))==0)? "Artikel":$item["kategori"];
    $details .= "
    <item>
        <title>".htmlspecialchars($item["judul"])."</title>
        <link>".$item["url"]."</link>
        <description>
            ".htmlspecialchars($data_config["nama_desa"]).", ".htmlspecialchars($item["isi"])." 
        </description>
    </item>";
    endif;
    endforeach;
    $details .="</channel>
</rss>";
echo($details);