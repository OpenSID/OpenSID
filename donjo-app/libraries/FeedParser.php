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
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

/**
 * PHP Univarsel Feed Parser class
 *
 * Parses RSS 1.0, RSS2.0 and ATOM Feed
 *
 * @license     GNU General Public License (GPL)
 *
 * @see        http://www.ajaxray.com/blog/2008/05/02/php-universal-feed-parser-lightweight-php-class-for-parsing-rss-and-atom-feeds/
 */
class FeedParser
{
    private $xmlParser;  // List of tag names which have sub tags
    private array $channels = [];
    private array $items    = [];
    private $url;                     // The parsed url
    private $version;                     // Detected feed version

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

    /*-----------------------------------------------------------------------+
    |  Public functions. Use to parse feed and get informations.             |
    +-----------------------------------------------------------------------*/

    /**
     * Get all channel elements
     *
     * @return array - All chennels as associative array
     */
    public function getChannels(): array
    {
        return $this->channels;
    }

    /**
     * Get all feed items
     *
     * @return array - All feed items as associative array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * Get total number of feed items
     *
     * @return number
     */
    public function getTotalItems(): int
    {
        return count($this->items);
    }

    /**
     * Get a feed item by index
     *
     * @param    number  index of feed item
     * @param mixed $index
     *
     * @return array feed item as associative array of it's elements
     */
    public function getItem($index)
    {
        if ($index < $this->getTotalItems()) {
            return $this->items[$index];
        }

        throw new Exception('Item index is learger then total items.');
    }

    /**
     * Get a channel element by name
     *
     * @param    string  the name of channel tag
     * @param mixed $tagName
     *
     * @return string
     */
    public function getChannel($tagName)
    {
        if (array_key_exists(strtoupper($tagName), $this->channels)) {
            return $this->channels[strtoupper($tagName)];
        }

        throw new Exception("Channel tag {$tagName} not found.");
    }

    /**
     * Get the parsed URL
     *
     * @return string
     */
    public function getParsedUrl()
    {
        if (empty($this->url)) {
            throw new Exception('Feed URL is not set yet.');
        }

        return $this->url;
    }

    /**
     * Get the detected Feed version
     *
     * @return string
     */
    public function getFeedVersion()
    {
        return $this->version;
    }

    /**
     * Parses a feed url
     *
     * @param    srting  teh feed url
     * @param mixed $url
     *
     * @return void
     */
    public function parse($url)
    {
        $this->url  = $url;
        $URLContent = $this->getUrlContent();

        if ($URLContent !== '' && $URLContent !== '0') {
            $segments = str_split($URLContent, 4096);

            foreach ($segments as $index => $data) {
                $lastPiese = (count($segments) - 1) == $index;
                $result    = xml_parse($this->xmlParser, $data, $lastPiese);
                if ($result === 0) {
                    log_message('error', sprintf(
                        'XML error: %s at line %d',
                        xml_error_string(xml_get_error_code($this->xmlParser)),
                        xml_get_current_line_number($this->xmlParser)
                    ));

                    return false;
                }
            }
            xml_parser_free($this->xmlParser);
        } else {
            log_message('error', 'Sorry! cannot load the feed url.');

            return false;
        }

        if (empty($this->version)) {
            log_message('error', 'Sorry! cannot detect the feed version.');

            return false;
        }
    }

    // End public functions -------------------------------------------------

    /*-----------------------------------------------------------------------+
    | Private functions. Be careful to edit them.                            |
    +-----------------------------------------------------------------------*/

    /**
     * Load the whole contents of a RSS/ATOM page
     *
     * @return string
     */
    private function getUrlContent()
    {
        if (empty($this->url)) {
            throw new Exception('URL to parse is empty!.');
        }

        if ($content = @file_get_contents($this->url)) {
            return $content;
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $content = curl_exec($ch);
        $error   = curl_error($ch);

        curl_close($ch);

        if ($error === '') {
            return $content;
        }

        throw new Exception("Erroe occured while loading url by cURL. <br />\n" . $error);
    }
} //End class FeedParser
