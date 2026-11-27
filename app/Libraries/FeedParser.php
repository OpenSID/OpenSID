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

namespace App\Libraries;

use Exception;
use Illuminate\Support\Facades\Http;
use XMLParser;

/**
 * PHP Universal Feed Parser class
 *
 * Parses RSS 1.0, RSS 2.0 and ATOM Feed
 *
 * @license     GNU General Public License (GPL)
 *
 * @see http://www.ajaxray.com/blog/2008/05/02/php-universal-feed-parser-lightweight-php-class-for-parsing-rss-and-atom-feeds/
 */
class FeedParser
{
    private XMLParser $xmlParser;
    private array $channels = [];
    private array $items    = [];
    private string $url;
    private string $version = '';

    // Handler properties
    private bool $insideItem      = false;
    private string $currentTag    = '';
    private array $itemTags       = ['item', 'entry'];
    private array $channelTags    = ['channel', 'feed'];
    private array $currentItem    = [];
    private array $currentChannel = [];

    /**
     * Constructor - Initialize and set event handler functions to xmlParser
     */
    public function __construct()
    {
        $this->initParser();
    }

    /**
     * Get all channel elements
     *
     * @return array All channels as associative array
     */
    public function getChannels(): array
    {
        return $this->channels;
    }

    /**
     * Get all feed items
     *
     * @return array All feed items as associative array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * Get total number of feed items
     */
    public function getTotalItems(): int
    {
        return count($this->items);
    }

    /**
     * Get a feed item by index
     *
     * @param mixed $index Index of feed item
     *
     * @throws Exception
     *
     * @return array Feed item as associative array
     */
    public function getItem(mixed $index): array
    {
        if ($index < $this->getTotalItems()) {
            return $this->items[$index];
        }

        throw new Exception('Item index is larger than total items.');
    }

    /**
     * Get a channel element by name
     *
     * @param mixed $tagName Name of channel tag
     *
     * @throws Exception
     *
     * @return string Channel tag value
     */
    public function getChannel(mixed $tagName): string
    {
        if (array_key_exists(strtoupper((string) $tagName), $this->channels)) {
            return $this->channels[strtoupper((string) $tagName)];
        }

        throw new Exception("Channel tag {$tagName} not found.");
    }

    /**
     * Get the parsed URL
     *
     * @throws Exception
     *
     * @return string The feed URL that was parsed
     */
    public function getParsedUrl(): string
    {
        if (! isset($this->url) || ($this->url === '' || $this->url === '0')) {
            throw new Exception('Feed URL is not set yet.');
        }

        return $this->url;
    }

    /**
     * Get the detected Feed version
     *
     * @return string Feed version (e.g., 'atom', '1.0', '2.0')
     */
    public function getFeedVersion(): string
    {
        return $this->version;
    }

    /**
     * Parse a feed URL
     *
     * @param mixed $url The feed URL to parse
     *
     * @return bool Returns true on success, false on error
     */
    public function parse(mixed $url): bool
    {
        // Reset state so instance can be reused
        $this->reset();

        $this->url = $url;

        try {
            $URLContent = $this->getUrlContent();
        } catch (Exception $e) {
            logger()->error('Failed to load feed: ' . $e->getMessage());

            return false;
        }

        if ($URLContent === '' || $URLContent === '0') {
            logger()->error('Feed content is empty.');

            return false;
        }

        $segments = str_split($URLContent, 4096);

        foreach ($segments as $index => $data) {
            $lastPiece = (count($segments) - 1) === $index;
            $result    = xml_parse($this->xmlParser, $data, $lastPiece);

            if ($result === 0) {
                logger()->error('XML error: ' . sprintf(
                    '%s at line %d',
                    xml_error_string(xml_get_error_code($this->xmlParser)),
                    xml_get_current_line_number($this->xmlParser)
                ));

                return false;
            }
        }

        xml_parser_free($this->xmlParser);

        if ($this->version === '' || $this->version === '0') {
            logger()->error('Unable to detect feed version.');

            return false;
        }

        return true;
    }

    /**
     * Initialize the XML parser and register handlers.
     * Called in constructor and reset() so the parser can be reused.
     */
    private function initParser(): void
    {
        $this->xmlParser = xml_parser_create();

        xml_set_object($this->xmlParser, $this);
        xml_set_element_handler($this->xmlParser, 'startElement', 'endElement');
        xml_set_character_data_handler($this->xmlParser, 'characterData');
    }

    /**
     * Reset parser state so parse() can be called again on the same instance.
     */
    private function reset(): void
    {
        $this->channels       = [];
        $this->items          = [];
        $this->version        = '';
        $this->insideItem     = false;
        $this->currentTag     = '';
        $this->currentItem    = [];
        $this->currentChannel = [];

        // Re-create the XML parser because xml_parser_free() invalidates it
        $this->initParser();
    }

    /**
     * Start element XML parser handler
     *
     * @param XMLParser $parser  XML parser object
     * @param string    $name    Element tag name (uppercased by PHP xml_parser)
     * @param array     $attribs Element attributes (keys uppercased by PHP xml_parser)
     */
    private function startElement(XMLParser $parser, string $name, array $attribs): void
    {
        // PHP xml_parser uppercases all tag names and attribute keys
        $nameLower        = strtolower($name);
        $this->currentTag = $nameLower;

        // Detect feed version from root element
        if ($this->version === '' || $this->version === '0') {
            $this->findVersion($name, $attribs);
        }

        // Handle item/entry tags
        if (in_array($nameLower, $this->itemTags, true)) {
            $this->insideItem  = true;
            $this->currentItem = [];
        }

        // Handle channel/feed tags
        if (in_array($nameLower, $this->channelTags, true)) {
            $this->currentChannel = [];
        }
    }

    /**
     * End element XML parser handler
     *
     * @param XMLParser $parser XML parser object
     * @param string    $name   Element tag name (uppercased by PHP xml_parser)
     */
    private function endElement(XMLParser $parser, string $name): void
    {
        $nameLower = strtolower($name);

        // Save item when closing item/entry tag
        if (in_array($nameLower, $this->itemTags, true)) {
            $this->insideItem = false;
            if (! empty($this->currentItem)) {
                $this->items[] = $this->currentItem;
            }
            $this->currentItem = [];
        }

        // Save channel when closing channel/feed tag
        if (in_array($nameLower, $this->channelTags, true)) {
            if (! empty($this->currentChannel)) {
                $this->channels[strtoupper($nameLower)] = $this->currentChannel;
            }
            $this->currentChannel = [];
        }

        $this->currentTag = '';
    }

    /**
     * Character data XML parser handler
     *
     * @param XMLParser $parser XML parser object
     * @param string    $data   Character data content
     */
    private function characterData(XMLParser $parser, string $data): void
    {
        $data = trim($data);

        if ($data === '' || $data === '0') {
            return;
        }

        $data = $this->unhtmlentities($data);

        if ($this->insideItem) {
            $key = strtoupper($this->currentTag);
            if (! isset($this->currentItem[$key])) {
                $this->currentItem[$key] = $data;
            } else {
                $this->currentItem[$key] .= $data;
            }
        } else {
            $key = strtoupper($this->currentTag);
            if (! isset($this->currentChannel[$key])) {
                $this->currentChannel[$key] = $data;
            } else {
                $this->currentChannel[$key] .= $data;
            }
        }
    }

    /**
     * Detect and set feed version from root element.
     *
     * IMPORTANT: PHP xml_parser_create() uppercases ALL attribute keys.
     * 'xmlns' becomes 'XMLNS', 'version' becomes 'VERSION'.
     * $name passed here is the raw uppercased tag name from the parser.
     *
     * @param string $name    Root element tag name (uppercased)
     * @param array  $attribs Root element attributes (keys uppercased)
     */
    private function findVersion(string $name, array $attribs): void
    {
        $nameLower = strtolower($name);

        if ($nameLower === 'rss') {
            // 'VERSION' because xml_parser uppercases attribute keys
            $this->version = $attribs['VERSION'] ?? '2.0';
        } elseif ($nameLower === 'feed' && isset($attribs['XMLNS'])) {
            // 'XMLNS' because xml_parser uppercases attribute keys
            if (str_contains($attribs['XMLNS'], 'atom')) {
                $this->version = 'atom';
            }
        } elseif ($nameLower === 'rdf:rdf') {
            $this->version = '1.0';
        }
    }

    /**
     * Decode HTML entities in a string
     *
     * @param string $str Input string with HTML entities
     *
     * @return string Decoded string
     */
    private function unhtmlentities(string $str): string
    {
        $trans_tbl = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
        $trans_tbl = array_flip($trans_tbl);

        return strtr($str, $trans_tbl);
    }

    /**
     * Load the complete content of a RSS/ATOM feed URL
     *
     * @throws Exception
     *
     * @return string Feed content
     */
    private function getUrlContent(): string
    {
        if (! isset($this->url) || ($this->url === '' || $this->url === '0')) {
            throw new Exception('URL to parse is empty.');
        }

        try {
            $response = Http::timeout(5)->withOptions(['allow_redirects' => false])->get($this->url);

            if (! $response->successful()) {
                throw new Exception('HTTP ' . $response->status());
            }

            return $response->body();
        } catch (Exception $e) {
            throw new Exception("Error loading feed URL: {$e->getMessage()}");
        }
    }
}
