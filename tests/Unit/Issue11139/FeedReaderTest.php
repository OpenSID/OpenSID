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

use App\Libraries\FeedReader;
use Illuminate\Support\Facades\Http;
use Tests\BaseTestCase;

class FeedReaderTest extends BaseTestCase
{
    /**
     * Test FeedReader dapat diinstansiasi dengan URL yang valid
     *
     * @test
     */
    public function test_feed_reader_can_be_instantiated_with_valid_url()
    {
        $rssContent = <<<'XML'
<?xml version="1.0"?>
<rss version="2.0">
    <channel>
        <title>Test Feed</title>
        <item>
            <title>Test Item</title>
        </item>
    </channel>
</rss>
XML;

        Http::fake([
            'http://example.com/feed.xml' => Http::response($rssContent, 200),
        ]);

        $reader = new FeedReader('http://example.com/feed.xml');

        $this->assertInstanceOf(FeedReader::class, $reader);
    }

    /**
     * Test FeedReader melempar exception ketika parsing gagal
     *
     * @test
     */
    public function test_feed_reader_throws_exception_on_parse_failure()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Failed to parse feed');

        Http::fake([
            'http://example.com/invalid.xml' => Http::response('Invalid XML', 200),
        ]);

        new FeedReader('http://example.com/invalid.xml');
    }

    /**
     * Test FeedReader melempar exception ketika HTTP error
     *
     * @test
     */
    public function test_feed_reader_throws_exception_on_http_error()
    {
        $this->expectException(\Exception::class);

        Http::fake([
            'http://example.com/feed.xml' => Http::response('Not Found', 404),
        ]);

        new FeedReader('http://example.com/feed.xml');
    }

    /**
     * Test getItems() mengembalikan array items
     *
     * @test
     */
    public function test_get_items_returns_array()
    {
        $rssContent = <<<'XML'
<?xml version="1.0"?>
<rss version="2.0">
    <channel>
        <title>Test Feed</title>
        <item>
            <title>Item 1</title>
            <description>Desc 1</description>
        </item>
        <item>
            <title>Item 2</title>
            <description>Desc 2</description>
        </item>
    </channel>
</rss>
XML;

        Http::fake([
            'http://example.com/feed.xml' => Http::response($rssContent, 200),
        ]);

        $reader = new FeedReader('http://example.com/feed.xml');
        $items = $reader->getItems();

        $this->assertIsArray($items);
        $this->assertCount(2, $items);
    }

    /**
     * Test getTotalItems() mengembalikan jumlah yang benar
     *
     * @test
     */
    public function test_get_total_items_returns_correct_count()
    {
        $rssContent = <<<'XML'
<?xml version="1.0"?>
<rss version="2.0">
    <channel>
        <item><title>Item 1</title></item>
        <item><title>Item 2</title></item>
        <item><title>Item 3</title></item>
    </channel>
</rss>
XML;

        Http::fake([
            'http://example.com/feed.xml' => Http::response($rssContent, 200),
        ]);

        $reader = new FeedReader('http://example.com/feed.xml');

        $this->assertEquals(3, $reader->getTotalItems());
    }

    /**
     * Test getItems() mengembalikan array kosong untuk feed tanpa items
     *
     * @test
     */
    public function test_get_items_returns_empty_array_for_feed_without_items()
    {
        $rssContent = <<<'XML'
<?xml version="1.0"?>
<rss version="2.0">
    <channel>
        <title>Empty Feed</title>
    </channel>
</rss>
XML;

        Http::fake([
            'http://example.com/feed.xml' => Http::response($rssContent, 200),
        ]);

        $reader = new FeedReader('http://example.com/feed.xml');
        $items = $reader->getItems();

        $this->assertIsArray($items);
        $this->assertEmpty($items);
    }

    /**
     * Test FeedReader hanya melakukan single HTTP request (tanpa retry)
     *
     * @test
     */
    public function test_feed_reader_makes_single_http_request()
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

        new FeedReader('http://example.com/feed.xml');

        Http::assertSentCount(1);
    }

    /**
     * Test FeedReader dengan timeout yang singkat
     *
     * @test
     */
    public function test_feed_reader_uses_short_timeout()
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

        new FeedReader('http://example.com/feed.xml');

        // Verify that HTTP request was made
        Http::assertSent(function ($request) {
            return $request->url() === 'http://example.com/feed.xml';
        });
    }
}
