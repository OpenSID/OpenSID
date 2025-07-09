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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Libraries;

use Exception;

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
    private $xmlParser;  // List of tag names which have sub tags
    private array $insideItem = [];                  // Keep track of current position in tag tree
    private $currentTag;                     // Last entered tag name
    private $currentAttr;                     // Attributes array of last entered tag
    private array $namespaces = [
        'http://purl.org/rss/1.0/'                 => 'RSS 1.0',
        'http://purl.org/rss/1.0/modules/content/' => 'RSS 2.0',
        'http://www.w3.org/2005/Atom'              => 'ATOM 1',
    ];

    // Namespaces to detact feed version
    private array $itemTags    = ['ITEM', 'ENTRY'];    // List of tag names which holds a feed item
    private array $channelTags = ['CHANNEL', 'FEED'];  // List of tag names which holds all channel elements
    private array $dateTags    = ['UPDATED', 'PUBDATE', 'DC:DATE'];
    private array $hasSubTags  = ['IMAGE', 'AUTHOR'];  // List of tag names which have sub tags
    private array $channels    = [];
    private array $items       = [];
    private string $url;                     // The parsed url
    private string $version = '';                     // Detected feed version

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
     * @param number  index of feed item
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
     * @param string  the name of channel tag
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
     * @param srting  teh feed url
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

        return false;
    }

    /**
     * Handle the start event of a tag while parsing
     *
     * @param object  the xmlParser object
     * @param string  name of currently entering tag
     * @param array   array of attributes
     * @param mixed $parser
     * @param mixed $tagName
     * @param mixed $attrs
     *
     * @return void
     */
    private function startElement($parser, $tagName, $attrs)
    {
        if (! $this->version) {
            $this->findVersion($tagName, $attrs);
        }

        $this->insideItem[] = $tagName;

        $this->currentTag  = $tagName;
        $this->currentAttr = $attrs;
    }

    /**
     * Handle the end event of a tag while parsing
     *
     * @param object  the xmlParser object
     * @param string  name of currently ending tag
     * @param mixed $parser
     * @param mixed $tagName
     *
     * @return void
     */
    private function endElement($parser, $tagName)
    {
        if (in_array($tagName, $this->itemTags)) {
            $this->itemIndex++;
        }

        array_pop($this->insideItem);
        $this->currentTag = $this->insideItem[count($this->insideItem) - 1];
    }

    /**
     * Handle character data of a tag while parsing
     *
     * @param object  the xmlParser object
     * @param string  tag value
     * @param mixed $parser
     * @param mixed $data
     *
     * @return void
     */
    private function characterData($parser, $data)
    {
        //Converting all date formats to timestamp
        if (in_array($this->currentTag, $this->dateTags)) {
            $data = strtotime($data);
        }

        if ($this->inChannel()) {
            // If has subtag, make current element an array and assign subtags as it's element
            if (in_array($this->getParentTag(), $this->hasSubTags)) {
                if (! is_array($this->channels[$this->getParentTag()])) {
                    $this->channels[$this->getParentTag()] = [];
                }

                $this->channels[$this->getParentTag()][$this->currentTag] .= strip_tags($this->unhtmlentities((trim($data))));

                return;
            }

            if (! in_array($this->currentTag, $this->hasSubTags)) {
                $this->channels[$this->currentTag] .= strip_tags($this->unhtmlentities((trim($data))));
            }

            if (! empty($this->currentAttr)) {
                $this->channels[$this->currentTag . '_ATTRS'] = $this->currentAttr;

                //If the tag has no value
                if (strlen($this->channels[$this->currentTag]) < 2) {
                    //If there is only one attribute, assign the attribute value as channel value
                    if (count($this->currentAttr) == 1) {
                        foreach ($this->currentAttr as $attrVal) {
                            $this->channels[$this->currentTag] = $attrVal;
                        }
                    }
                    //If there are multiple attributes, assign the attributs array as channel value
                    else {
                        $this->channels[$this->currentTag] = $this->currentAttr;
                    }
                }
            }
        } elseif ($this->inItem()) {
            // If has subtag, make current element an array and assign subtags as it's elements
            if (in_array($this->getParentTag(), $this->hasSubTags)) {
                if (! is_array($this->items[$this->itemIndex][$this->getParentTag()])) {
                    $this->items[$this->itemIndex][$this->getParentTag()] = [];
                }

                $this->items[$this->itemIndex][$this->getParentTag()][$this->currentTag] .= strip_tags($this->unhtmlentities((trim($data))));

                return;
            }

            if (! in_array($this->currentTag, $this->hasSubTags)) {
                $this->items[$this->itemIndex][$this->currentTag] .= strip_tags($this->unhtmlentities((trim($data))));
            }

            if (! empty($this->currentAttr)) {
                $this->items[$this->itemIndex][$this->currentTag . '_ATTRS'] = $this->currentAttr;

                //If the tag has no value

                if (strlen($this->items[$this->itemIndex][$this->currentTag]) < 2) {
                    //If there is only one attribute, assign the attribute value as feed element's value
                    if (count($this->currentAttr) == 1) {
                        foreach ($this->currentAttr as $attrVal) {
                            $this->items[$this->itemIndex][$this->currentTag] = $attrVal;
                        }
                    }
                    //If there are multiple attributes, assign the attribute array as feed element's value
                    else {
                        $this->items[$this->itemIndex][$this->currentTag] = $this->currentAttr;
                    }
                }
            }
        }
    }

    /**
     * Find out the feed version
     *
     * @param string  name of current tag
     * @param array   array of attributes
     * @param mixed $tagName
     * @param mixed $attrs
     *
     * @return void
     */
    private function findVersion($tagName, $attrs)
    {
        // Ambil versi RSS kalau ada
        if ($tagName == 'RSS') {
            foreach ($attrs as $attr => $value) {
                if ($attr == 'VERSION') {
                    $this->version = 'RSS ' . $value;

                    return;
                }
            }
        }

        $namespace = array_values($attrs);

        foreach ($this->namespaces as $value => $version) {
            if (in_array($value, $namespace)) {
                $this->version = $version;

                return;
            }
        }
    }

    private function getParentTag()
    {
        return $this->insideItem[count($this->insideItem) - 2];
    }

    /**
     * Detect if current position is in channel element
     *
     * @return bool
     */
    private function inChannel()
    {
        if ($this->version == 'RSS 1.0') {
            if (in_array('CHANNEL', $this->insideItem) && $this->currentTag != 'CHANNEL') {
                return true;
            }
        } elseif ($this->version == 'RSS 2.0') {
            if (in_array('CHANNEL', $this->insideItem) && ! in_array('ITEM', $this->insideItem) && $this->currentTag != 'CHANNEL') {
                return true;
            }
        } elseif ($this->version == 'ATOM 1') {
            if (in_array('FEED', $this->insideItem) && ! in_array('ENTRY', $this->insideItem) && $this->currentTag != 'FEED') {
                return true;
            }
        }

        return false;
    }

    /**
     * Detect if current position is in Item element
     *
     * @return bool
     */
    private function inItem()
    {
        if ($this->version == 'RSS 1.0' || $this->version == 'RSS 2.0') {
            if (in_array('ITEM', $this->insideItem) && $this->currentTag != 'ITEM') {
                return true;
            }
        } elseif ($this->version == 'ATOM 1') {
            if (in_array('ENTRY', $this->insideItem) && $this->currentTag != 'ENTRY') {
                return true;
            }
        }

        return false;
    }

    //This function is taken from lastRSS
    /**
     * Replace HTML entities &something; by real characters
     *
     * @see http://lastrss.oslab.net/
     *
     * @param string
     * @param mixed $string
     *
     * @return string
     */
    private function unhtmlentities($string)
    {
        // Get HTML entities table
        $trans_tbl = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
        // Flip keys<==>values
        $trans_tbl = array_flip($trans_tbl);
        // Add support for &apos; entity (missing in HTML_ENTITIES)
        $trans_tbl += ['&apos;' => "'"];

        // Replace entities by values
        return strtr($string, $trans_tbl);
    }
} //End class FeedParser
