<?php header("Content-Type: application/xml; charset=ISO-8859-1");
$details = "<rss xmlns:dc=\"http://purl.org/dc/elements/1.1/\" xmlns:sy=\"http://purl.org/rss/1.0/modules/syndication/\" xmlns:admin=\"http://webns.net/mvcb/\" xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\" xmlns:content=\"http://purl.org/rss/1.0/modules/content/\" version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">
<channel>
	<title>Desa ". $data_config["nama_desa"] ."</title>
	<link>". base_url() ."</link>
	<description>Situs Web Desa ". $data_config["nama_desa"] ." Kec. ". $data_config["nama_kecamatan"] ." <?php echo ucwords($this->setting->sebutan_kabupaten_singkat)?> ". $data_config["nama_kabupaten"] ." - ". $data_config["nama_propinsi"] ."</description>
	<language>ID</language>
	<generator>Sistem Informasi Desa</generator>
	<pubDate>".date(DATE_RFC2822)."</pubDate>
	<image>
		<title>Desa ". $data_config["nama_desa"] ."</title>
		<url>". base_url("assets/files/logo/".$data_config["logo"]."") ."</url>
		<link>". base_url() ."</link>
	</image>
	<atom:link href=\"".htmlspecialchars(site_url("feed"))."\" rel=\"self\" type=\"application/rss+xml\" />
	";
foreach($feeds as $key=>$item)
{
	if(strlen(trim($item["judul"]))>0)
	{
		$kategori = (strlen(trim($item["kategori"]))==0)? "Artikel":$item["kategori"];
		$details .= "
		<item>
			<title>".htmlspecialchars($item["judul"])."</title>
			<link>".$item["url"]."</link>
			<source url=\"".htmlspecialchars(site_url("feed"))."\">Situs Web Desa ". $data_config["nama_desa"] ."</source>
			<pubDate>".date(DATE_RFC2822,strtotime($item["tgl"]))."</pubDate>
			<dc:creator><![CDATA[ ".$item["author"]." ]]></dc:creator>
			<category><![CDATA[ ".htmlspecialchars($kategori)." ]]></category>
			<guid isPermaLink=\"false\">".htmlspecialchars($item["url"])."</guid>
			<description><![CDATA[ ".htmlspecialchars($data_config["nama_desa"]).", ".htmlspecialchars($item["isi"])." ]]></description>
		</item>\n";
	}
}
$details .="</channel>
</rss>";
printf($details);
