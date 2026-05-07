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
 * PHP Univarsel Feed Parser class
 *
 * Parses RSS 1.0, RSS2.0 and ATOM Feed
 *
 * @license     GNU General Public License (GPL)
 *
 * @see http://www.ajaxray.com/blog/2008/05/02/php-universal-feed-parser-lightweight-php-class-for-parsing-rss-and-atom-feeds/
 */
class FeedParser
{
    private readonly XMLParser $xmlParser;
    private array $channels = [];
    private array $items    = [];
    private string $url;
    private string $version = '';

    // Handler properties
    private bool $insideItem = false;
    private string $currentTag = '';
    private array $itemTags = ['item', 'entry'];
    private array $channelTags = ['channel', 'feed'];
    private array $currentItem = [];
    private array $currentChannel = [];

    /**
     * Constructor - Initialize and set event handler functions to xmlParser
     */
    public function __construct()
    {
        $this->xmlParser = xml_parser_create();

        xml_set_object($this->xmlParser, $this);
        xml_set_element_handler($this->xmlParser, 'startElement', 'endElement');
        xml_set_character_data_handler($this->xmlParser, 'characterData');
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
     *
     * @return int
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
     * @return array Feed item as associative array
     *
     * @throws Exception
     */
    public function getItem(mixed $index)
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
     * @return string Channel tag value
     *
     * @throws Exception
     */
    public function getChannel(mixed $tagName)
    {
        if (array_key_exists(strtoupper((string) $tagName), $this->channels)) {
            return $this->channels[strtoupper((string) $tagName)];
        }

        throw new Exception("Channel tag {$tagName} not found.");
    }

    /**
     * Get the parsed URL
     *
     * @return string The feed URL that was parsed
     *
     * @throws Exception
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
     * @return string Feed version (e.g., 'rss', 'atom', '1.0', '2.0')
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
     * @return bool|null Returns null on success, false on error
     */
    public function parse(mixed $url): ?bool
    {
        $this->url = $url;

        try {
            $URLContent = $this->getUrlContent();
        } catch (\Exception $e) {
            logger()->error('Failed to load feed: ' . $e->getMessage());

            return false;
        }

        if ($URLContent !== '' && $URLContent !== '0') {
            $segments = str_split($URLContent, 4096);

            foreach ($segments as $index => $data) {
                $lastPiese = (count($segments) - 1) == $index;
                $result    = xml_parse($this->xmlParser, $data, $lastPiese);
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
        } else {
            logger()->error('Feed content is empty.');

            return false;
        }

        if ($this->version === '' || $this->version === '0') {
            logger()->error('Unable to detect feed version.');

            return false;
        }

        return null;
    }

    /**
     * Start element XML parser handler
     *
     * @param XMLParser $parser XML parser object
     * @param string $name Element tag name
     * @param array $attribs Element attributes
     *
     * @return void
     */
    private function startElement(XMLParser $parser, string $name, array $attribs): void
    {
        $name = strtolower($name);
        $this->currentTag = $name;

        // Detect feed version from root element
        if ($this->version === '' || $this->version === '0') {
            $this->findVersion($name, $attribs);
        }

        // Handle item/entry tags
        if (in_array($name, $this->itemTags, true)) {
            $this->insideItem = true;
            $this->currentItem = [];
        }

        // Handle channel/feed tags
        if (in_array($name, $this->channelTags, true)) {
            $this->currentChannel = [];
        }
    }

    /**
     * End element XML parser handler
     *
     * @param XMLParser $parser XML parser object
     * @param string $name Element tag name
     *
     * @return void
     */
    private function endElement(XMLParser $parser, string $name): void
    {
        $name = strtolower($name);

        // Save item when closing item/entry tag
        if (in_array($name, $this->itemTags, true)) {
            $this->insideItem = false;
            if (!empty($this->currentItem)) {
                $this->items[] = $this->currentItem;
            }
            $this->currentItem = [];
        }

        // Save channel when closing channel/feed tag
        if (in_array($name, $this->channelTags, true)) {
            if (!empty($this->currentChannel)) {
                $this->channels[strtoupper($name)] = $this->currentChannel;
            }
            $this->currentChannel = [];
        }

        $this->currentTag = '';
    }

    /**
     * Character data XML parser handler
     *
     * @param XMLParser $parser XML parser object
     * @param string $data Character data content
     *
     * @return void
     */
    private function characterData(XMLParser $parser, string $data): void
    {
        $data = trim($data);

        if ($data === '' || $data === '0') {
            return;
        }

        $data = $this->unhtmlentities($data);

        if ($this->insideItem) {
            // Store data in current item
            if (!isset($this->currentItem[strtoupper($this->currentTag)])) {
                $this->currentItem[strtoupper($this->currentTag)] = $data;
            } else {
                $this->currentItem[strtoupper($this->currentTag)] .= $data;
            }
        } else {
            // Store data in current channel
            if (!isset($this->currentChannel[strtoupper($this->currentTag)])) {
                $this->currentChannel[strtoupper($this->currentTag)] = $data;
            } else {
                $this->currentChannel[strtoupper($this->currentTag)] .= $data;
            }
        }
    }

    /**
     * Detect and set feed version from root element
     *
     * @param string $tagName Root element tag name
     * @param array $attribs Root element attributes
     *
     * @return void
     */
    private function findVersion(string $tagName, array $attribs): void
    {
        $tagName = strtolower($tagName);

        if ($tagName === 'rss') {
            $this->version = $attribs['version'] ?? '2.0';
        } elseif ($tagName === 'feed' && isset($attribs['xmlns'])) {
            if (str_contains($attribs['xmlns'], 'atom')) {
                $this->version = 'atom';
            }
        } elseif ($tagName === 'rdf:rdf') {
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
     * @return string Feed content
     *
     * @throws Exception
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
        } catch (\Exception $e) {
            throw new Exception("Error loading feed URL: {$e->getMessage()}");
        }
    }
}
