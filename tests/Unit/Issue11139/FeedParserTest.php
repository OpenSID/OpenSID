<?php

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace Tests\Unit\Issue11139;

use App\Libraries\FeedParser;
use Illuminate\Support\Facades\Http;
use Tests\BaseTestCase;

class FeedParserTest extends BaseTestCase
{
    private FeedParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = new FeedParser();
    }

    /**
     * Test FeedParser dapat diinstansiasi
     *
     * @test
     */
    public function test_feed_parser_can_be_instantiated()
    {
        $this->assertInstanceOf(FeedParser::class, $this->parser);
    }

    /**
     * Test parse() mengembalikan null pada sukses parsing RSS 2.0
     *
     * @test
     */
    public function test_parse_returns_null_on_successful_rss_parsing()
    {
        $rssContent = <<<'XML'
<?xml version="1.0"?>
<rss version="2.0">
    <channel>
        <title>Test Feed</title>
        <link>http://example.com</link>
        <description>Test Description</description>
        <item>
            <title>Test Item 1</title>
            <link>http://example.com/item1</link>
            <description>Item 1 Description</description>
        </item>
    </channel>
</rss>
XML;

        Http::fake([
            'http://example.com/feed.xml' => Http::response($rssContent, 200),
        ]);

        $result = $this->parser->parse('http://example.com/feed.xml');

        $this->assertNull($result);
    }

    /**
     * Test parse() mendeteksi versi RSS 2.0
     *
     * @test
     */
    public function test_parse_detects_rss_version()
    {
        $rssContent = <<<'XML'
<?xml version="1.0"?>
<rss version="2.0">
    <channel>
        <title>Test Feed</title>
        <item>
            <title>Item 1</title>
        </item>
    </channel>
</rss>
XML;

        Http::fake([
            'http://example.com/feed.xml' => Http::response($rssContent, 200),
        ]);

        $this->parser->parse('http://example.com/feed.xml');

        $this->assertEquals('2.0', $this->parser->getFeedVersion());
    }

    /**
     * Test getItems() mengembalikan array kosong sebelum parsing
     *
     * @test
     */
    public function test_get_items_returns_empty_array_before_parsing()
    {
        $items = $this->parser->getItems();

        $this->assertIsArray($items);
        $this->assertEmpty($items);
    }

    /**
     * Test getItems() mengembalikan items setelah parsing sukses
     *
     * @test
     */
    public function test_get_items_returns_parsed_items()
    {
        $rssContent = <<<'XML'
<?xml version="1.0"?>
<rss version="2.0">
    <channel>
        <title>Test Feed</title>
        <item>
            <title>Test Item 1</title>
            <description>Description 1</description>
        </item>
        <item>
            <title>Test Item 2</title>
            <description>Description 2</description>
        </item>
    </channel>
</rss>
XML;

        Http::fake([
            'http://example.com/feed.xml' => Http::response($rssContent, 200),
        ]);

        $this->parser->parse('http://example.com/feed.xml');
        $items = $this->parser->getItems();

        $this->assertCount(2, $items);
    }

    /**
     * Test getTotalItems() mengembalikan jumlah items yang benar
     *
     * @test
     */
    public function test_get_total_items_returns_correct_count()
    {
        $rssContent = <<<'XML'
<?xml version="1.0"?>
<rss version="2.0">
    <channel>
        <title>Test Feed</title>
        <item><title>Item 1</title></item>
        <item><title>Item 2</title></item>
        <item><title>Item 3</title></item>
    </channel>
</rss>
XML;

        Http::fake([
            'http://example.com/feed.xml' => Http::response($rssContent, 200),
        ]);

        $this->parser->parse('http://example.com/feed.xml');

        $this->assertEquals(3, $this->parser->getTotalItems());
    }

    /**
     * Test parse() mengembalikan false pada HTTP error
     *
     * @test
     */
    public function test_parse_returns_false_on_http_error()
    {
        Http::fake([
            'http://example.com/feed.xml' => Http::response('Not Found', 404),
        ]);

        $result = $this->parser->parse('http://example.com/feed.xml');

        $this->assertFalse($result);
    }

    /**
     * Test parse() mengembalikan false pada connection timeout
     *
     * @test
     */
    public function test_parse_returns_false_on_timeout()
    {
        Http::fake([
            'http://example.com/feed.xml' => Http::response(null, 0),
        ]);

        $result = $this->parser->parse('http://example.com/feed.xml');

        $this->assertFalse($result);
    }

    /**
     * Test parse() mengembalikan false pada invalid XML
     *
     * @test
     */
    public function test_parse_returns_false_on_invalid_xml()
    {
        $invalidContent = '<rss><channel><title>Invalid';

        Http::fake([
            'http://example.com/feed.xml' => Http::response($invalidContent, 200),
        ]);

        $result = $this->parser->parse('http://example.com/feed.xml');

        $this->assertFalse($result);
    }

    /**
     * Test parse() dapat menangani ATOM feed
     *
     * @test
     */
    public function test_parse_handles_atom_feed()
    {
        $atomContent = <<<'XML'
<?xml version="1.0"?>
<feed xmlns="http://www.w3.org/2005/Atom">
    <title>Test Atom Feed</title>
    <entry>
        <title>Atom Item 1</title>
        <content>Content 1</content>
    </entry>
</feed>
XML;

        Http::fake([
            'http://example.com/feed.xml' => Http::response($atomContent, 200),
        ]);

        $result = $this->parser->parse('http://example.com/feed.xml');

        $this->assertNull($result);
        $this->assertEquals('atom', $this->parser->getFeedVersion());
    }

    /**
     * Test getItem() mengembalikan item berdasarkan index
     *
     * @test
     */
    public function test_get_item_returns_item_by_index()
    {
        $rssContent = <<<'XML'
<?xml version="1.0"?>
<rss version="2.0">
    <channel>
        <item>
            <title>Test Item</title>
            <description>Test Description</description>
        </item>
    </channel>
</rss>
XML;

        Http::fake([
            'http://example.com/feed.xml' => Http::response($rssContent, 200),
        ]);

        $this->parser->parse('http://example.com/feed.xml');
        $item = $this->parser->getItem(0);

        $this->assertIsArray($item);
        $this->assertNotEmpty($item);
    }

    /**
     * Test getItem() melempar exception untuk index out of range
     *
     * @test
     */
    public function test_get_item_throws_exception_for_out_of_range_index()
    {
        $this->expectException(\Exception::class);

        $this->parser->getItem(999);
    }

    /**
     * Test getParsedUrl() mengembalikan URL yang di-parse
     *
     * @test
     */
    public function test_get_parsed_url_returns_url()
    {
        $rssContent = <<<'XML'
<?xml version="1.0"?>
<rss version="2.0">
    <channel>
        <title>Test</title>
    </channel>
</rss>
XML;

        Http::fake([
            'http://example.com/feed.xml' => Http::response($rssContent, 200),
        ]);

        $this->parser->parse('http://example.com/feed.xml');

        $this->assertEquals('http://example.com/feed.xml', $this->parser->getParsedUrl());
    }

    /**
     * Test getParsedUrl() melempar exception jika URL belum di-set
     *
     * @test
     */
    public function test_get_parsed_url_throws_exception_if_not_set()
    {
        $this->expectException(\Exception::class);

        $this->parser->getParsedUrl();
    }
}
